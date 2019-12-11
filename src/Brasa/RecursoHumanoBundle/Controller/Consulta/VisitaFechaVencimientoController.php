<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;


class VisitaFechaVencimientoController extends Controller
{
    var $strDqlLista = "";
    var $intNumero = 0;
    /**
     * @Route("/rhu/consulta/visita/fechavencimiento", name="brs_rhu_consulta_visita_fechavencimiento")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 96)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arVisitasFechaVencimiento = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Visita:FechaVencimiento.html.twig', array(
            'arVisitasFechaVencimiento' => $arVisitasFechaVencimiento,
            'form' => $form->createView()
            ));
    }     
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuVisita')->listaVisitasFechaVencimientoDQL(
            $session->get('filtroIdentificacion'),
            $session->get('filtroCodigoCentroCosto'),
            $session->get('filtroCodigoVisitaTipo'),
            $session->get('filtroVencimiento')
            );
    }  

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        
        $arrayPropiedadesVisitaTipo = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuVisitaTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sz')
                    ->orderBy('sz.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoVisitaTipo')) {
            $arrayPropiedadesVisitaTipo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuVisitaTipo", $session->get('filtroCodigoVisitaTipo'));
        }
        $form = $this->createFormBuilder()
            ->add('visitaTipoRel', 'entity', $arrayPropiedadesVisitaTipo)
            ->add('centroCostoRel', 'entity', $arrayPropiedades)    
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaVencimiento','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoVisitaTipo', $controles['visitaTipoRel']);
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
                
        $dateFechaVencimiento = $form->get('fechaVencimiento')->getData();
        if ($form->get('fechaVencimiento')->getData() == null ){
            $session->set('filtroVencimiento', $form->get('fechaVencimiento')->getData());
        } else {
            $session->set('filtroVencimiento', $dateFechaVencimiento->format('Y-m-d')); 
        }
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
                    ->setLastModifiedBy("EMPRESA")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");
                $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
                $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true); 
                for($col = 'A'; $col !== 'Z'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
                }
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CODIGO')
                            ->setCellValue('B1', 'FECHA')
                            ->setCellValue('C1', 'TIPO')
                            ->setCellValue('D1', 'GRUPO PAGO')
                            ->setCellValue('E1', 'IDENTIFICACION')
                            ->setCellValue('F1', 'EMPLEADO')
                            ->setCellValue('G1', 'REALIZA VISITA')
                            ->setCellValue('H1', 'VENCIMIENTO')
                            ->setCellValue('I1', 'AUTORIZADO')
                            ->setCellValue('J1', 'CERRADO')
                            ->setCellValue('K1', 'USUARIO')
                            ->setCellValue('L1', 'COMENTARIOS');
                $i = 2;
                $query = $em->createQuery($this->strDqlLista);
                $arVisitas = new \Brasa\RecursoHumanoBundle\Entity\RhuVisita();
                $arVisitas = $query->getResult();

                foreach ($arVisitas as $arVisita) {

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arVisita->getCodigoVisitaPk())
                            ->setCellValue('B' . $i, $arVisita->getFecha()->format('Y/m/d H:i:s'))
                            ->setCellValue('C' . $i, $arVisita->getVisitaTipoRel()->getNombre())
                            ->setCellValue('D' . $i, $arVisita->getEmpleadoRel()->getCentroCostoRel()->getNombre())
                            ->setCellValue('E' . $i, $arVisita->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('F' . $i, $arVisita->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('G' . $i, $arVisita->getNombreQuienVisita())
                            ->setCellValue('H' . $i, $objFunciones->devuelveBoolean($arVisita->getValidarVencimiento()))
                            ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arVisita->getEstadoAutorizado()))
                            ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arVisita->getEstadoCerrado()))
                            ->setCellValue('K' . $i, $arVisita->getCodigoUsuario())
                            ->setCellValue('L' . $i, $arVisita->getComentarios());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('VisitasFechaVencimiento');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="VisitasFechaVencimiento.xlsx"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');
                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0
                $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save('php://output');
                exit;
            }
    
}
