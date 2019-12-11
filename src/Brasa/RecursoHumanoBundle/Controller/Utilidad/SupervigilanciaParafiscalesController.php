<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class SupervigilanciaParafiscalesController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/utilidades/supervigilancia/parafiscales", name="brs_rhu_utilidades_supervigilancia_parafiscales")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 81)) {
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
                $this->generarExcel();
            }
            if($form->get('BtnGenerar')->isClicked()) { 
                $fechaDesde = $form->get('fechaDesde')->getData();
                $fechaHasta = $form->get('fechaHasta')->getData();
                if($fechaDesde && $fechaHasta) {
                    $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
                    $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
                
                    if($fechaDesde != null && $fechaHasta != null) {
                        $strSql = "DELETE FROM rhu_supervigilancia_parafiscales WHERE 1";
                        $em->getConnection()->executeQuery($strSql);    
                        $arrAportes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->parafiscalesSupervigilancia($fechaDesde, $fechaHasta);
                        foreach ($arrAportes as $arAporte) {
                            $arSupervigilanciaParafiscales = new \Brasa\RecursoHumanoBundle\Entity\RhuSupervigilanciaParafiscales();
                            $arSupervigilanciaParafiscales->setMes($arAporte['mes']);
                            $arSupervigilanciaParafiscales->setEmpleados($arAporte['numeroEmpleados']);
                            $arSupervigilanciaParafiscales->setCargo($arAporte['nombre']);
                            $arSupervigilanciaParafiscales->setVrEps($arAporte['eps']);
                            $arSupervigilanciaParafiscales->setVrPension($arAporte['pension']);
                            $arSupervigilanciaParafiscales->setVrArl($arAporte['arl']);
                            $arSupervigilanciaParafiscales->setVrCcf($arAporte['ccf']);
                            $arSupervigilanciaParafiscales->setVrSena($arAporte['sena']);
                            $arSupervigilanciaParafiscales->setVrIcbf($arAporte['icbf']);
                            $arSupervigilanciaParafiscales->setVrNomina($arAporte['nomina']);
                            $em->persist($arSupervigilanciaParafiscales);
                        }
                        $em->flush();
                    }
                    //return $this->redirect($this->generateUrl('brs_rhu_utilidades_supervigilancia_parafiscales'));                               
                }
            }            
                        
            /*if($form->get('BtnFiltrar')->isClicked()) {
                //$this->filtrarLista($form);
                $this->listar();
            }*/

        }    
        $arSupervigilanciaParafiscales = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);        
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Supervigilancia:parafiscales.html.twig', array(
            'arSupervigilanciaParafiscales' => $arSupervigilanciaParafiscales,
            'form' => $form->createView()
            ));
    }        
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSupervigilanciaParafiscales')->listaDql(
          $session->get('filtroIdentificacion')      
                
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
        $form = $this->createFormBuilder()
            //->add('centroCostoRel', 'entity', $arrayPropiedades)
            //->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))
            //->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))            
            ->getForm();
        return $form;
    }        

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        //$session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        //$session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
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
        for($col = 'A'; $col !== 'L'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                    
        }
        for($col = 'E'; $col !== 'P'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'MES')
                    ->setCellValue('C1', 'CARGO')
                    ->setCellValue('D1', 'EMPLEADOS')
                    ->setCellValue('E1', 'NÓMINA')
                    ->setCellValue('F1', 'EPS')
                    ->setCellValue('G1', 'PENSIÓN')
                    ->setCellValue('H1', 'ARL')
                    ->setCellValue('I1', 'CAJA')
                    ->setCellValue('J1', 'SENA')
                    ->setCellValue('K1', 'ICBF');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arSupervigilanciaParafiscales = new \Brasa\RecursoHumanoBundle\Entity\RhuSupervigilanciaParafiscales();
        $arSupervigilanciaParafiscales = $query->getResult();
        foreach ($arSupervigilanciaParafiscales as $arSupervigilanciaParafiscales) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSupervigilanciaParafiscales->getCodigoSupervigilanciaParafiscalesPk())
                    ->setCellValue('B' . $i, $arSupervigilanciaParafiscales->getMes())
                    ->setCellValue('C' . $i, $arSupervigilanciaParafiscales->getCargo())
                    ->setCellValue('D' . $i, $arSupervigilanciaParafiscales->getEmpleados())
                    ->setCellValue('E' . $i, $arSupervigilanciaParafiscales->getVrNomina())
                    ->setCellValue('F' . $i, $arSupervigilanciaParafiscales->getVrEps())
                    ->setCellValue('G' . $i, $arSupervigilanciaParafiscales->getVrPension())
                    ->setCellValue('H' . $i, $arSupervigilanciaParafiscales->getVrArl())
                    ->setCellValue('I' . $i, $arSupervigilanciaParafiscales->getVrCcf())
                    ->setCellValue('J' . $i, $arSupervigilanciaParafiscales->getVrSena())
                    ->setCellValue('K' . $i, $arSupervigilanciaParafiscales->getVrIcbf());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('SVParafiscales');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SVParafiscales.xlsx"');
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
