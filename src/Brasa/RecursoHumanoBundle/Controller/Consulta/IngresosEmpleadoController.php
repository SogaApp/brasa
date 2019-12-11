<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class IngresosEmpleadoController extends Controller
{
    var $strDqlLista = "";        
    
    /**
     * @Route("/rhu/consultas/costos/ingresos/empleado", name="brs_rhu_consultas_costos_ingresos_empleado")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 15)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
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
        $arIngresosBase = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/IngresosEmpleado:lista.html.twig', array(
            'arIngresosBase' => $arIngresosBase,
            'form' => $form->createView()
            ));
    }        
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuIngresoBase')->listaDql(                    
                    $session->get('filtroContrato'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }       
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        
        $form = $this->createFormBuilder()
            ->add('TxtContrato', 'text', array('label'  => 'Contrato','data' => $session->get('filtroContrato')))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }           
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroContrato', $form->get('TxtContrato')->getData());
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        //$session->set('filtroDesde', $form->get('fechaDesde')->getData());
        //$session->set('filtroHasta', $form->get('fechaHasta')->getData());
        
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null){
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d')); 
        }
    }    
    
    private function generarExcel() {
        ob_clean();
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'IDENTIFICACION')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'CODIGO CONTRATO')                    
                    ->setCellValue('E1', 'DESDE')
                    ->setCellValue('F1', 'HASTA')
                    ->setCellValue('G1', 'IBC')
                    ->setCellValue('H1', 'IBP');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arIngresosBase = new \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase();
        $arIngresosBase = $query->getResult();
        foreach ($arIngresosBase as $arIngresoBase) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIngresoBase->getCodigoIngresoBasePk())
                    ->setCellValue('B' . $i, $arIngresoBase->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arIngresoBase->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arIngresoBase->getCodigoContratoFk())                    
                    ->setCellValue('E' . $i, $arIngresoBase->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('F' . $i, $arIngresoBase->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('G' . $i, $arIngresoBase->getvrIngresoBaseCotizacion())
                    ->setCellValue('H' . $i, $arIngresoBase->getvrIngresoBasePrestacion());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('IngresosEmpleado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="IngresosEmpleado.xlsx"');
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
