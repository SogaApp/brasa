<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;


class PagoConceptoController extends Controller
{
    var $strDqlLista = "";
    var $intNumero = 0;
    /**
     * @Route("/rhu/consulta/pago/concepto", name="brs_rhu_consulta_pago_concepto")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();       
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnGenerar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
            }

        }
        $arConsultaPagoConcepto = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/PagoConcepto:detalle.html.twig', array(
            'arConsultaPagoConcepto' => $arConsultaPagoConcepto,
            'form' => $form->createView()
            ));
    }     
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuConsultaPagoConcepto')->listaDql();
    }  

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();        
        $session = $this->get('session');
        $arrayPropiedadesPagoConcepto = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pc')
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoPagoConcepto')) {
            $arrayPropiedadesPagoConcepto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $session->get('filtroCodigoPagoConcepto'));
        }
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y-m-')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y-m-').$intUltimoDia;
        if($session->get('filtroDesde') != "") {
            $strFechaDesde = $session->get('filtroDesde');
        }
        if($session->get('filtroHasta') != "") {
            $strFechaHasta = $session->get('filtroHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()                                    
            ->add('pagoConceptoRel', 'entity', $arrayPropiedadesPagoConcepto)                        
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('BtnGenerar', 'submit', array('label'  => 'Filtrar'))                                        
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                                            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))            
            ->getForm();        
        return $form;
    }

    private function filtrarLista($form, Request $request) {
        $em = $this->getDoctrine()->getManager(); 
        $session = $this->get('session');        
        $strSql = "DELETE FROM rhu_consulta_pago_concepto WHERE 1";
        $em->getConnection()->executeQuery($strSql);        
        
        $controles = $request->request->get('form');                
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());        
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
        $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
        $session->set('filtroCodigoPagoConcepto', $controles['pagoConceptoRel']);
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->listaDetalleDql(
                    "",
                    "",
                    $session->get('filtroIdentificacion'),
                    "",
                    $strFechaDesde = $session->get('filtroDesde'),
                    $strFechaHasta = $session->get('filtroHasta'),
                    $session->get('filtroCodigoPagoConcepto')
                    );               
        $query = $em->createQuery($dql);        
        $arPagoDetalles = $query->getResult();   
        foreach ($arPagoDetalles as $arPagoDetalle) {
            $arConsultaPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuConsultaPagoConcepto();
            $arConsultaPagoConcepto->setOrigen('NOMINA');
            $arConsultaPagoConcepto->setNumero($arPagoDetalle->getPagoRel()->getNumero());
            $arConsultaPagoConcepto->setNumeroIdentificacion($arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNumeroIdentificacion());
            $arConsultaPagoConcepto->setNombreCorto($arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNombreCorto());
            $arConsultaPagoConcepto->setCodigoPagoConceptoFk($arPagoDetalle->getCodigoPagoConceptoFk());
            $arConsultaPagoConcepto->setNombreConcepto($arPagoDetalle->getPagoConceptoRel()->getNombre());
            $arConsultaPagoConcepto->setFechaDesde($arPagoDetalle->getPagoRel()->getFechaDesde());
            $arConsultaPagoConcepto->setFechaHasta($arPagoDetalle->getPagoRel()->getFechaHasta());
            if($arPagoDetalle->getOperacion() == -1) {
                $arConsultaPagoConcepto->setVrDeduccion($arPagoDetalle->getVrPago());  
            }
            if($arPagoDetalle->getOperacion() == 1) {
                $arConsultaPagoConcepto->setVrBonificacion($arPagoDetalle->getVrPago());  
            }            
            $em->persist($arConsultaPagoConcepto);            
        }
        $codigoEmpleado = "";
        if($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if($arEmpleado) {
               $codigoEmpleado =  $arEmpleado->getCodigoEmpleadoPk();
            }
        }
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionAdicional')->listaConsultaDql($session->get('filtroDesde'), $session->get('filtroHasta'), $codigoEmpleado, $session->get('filtroCodigoPagoConcepto'));
        $query = $em->createQuery($dql);        
        $arVacacionesAdicionales = $query->getResult();   
        foreach ($arVacacionesAdicionales as $arVacacionAdicional) {
            $arConsultaPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuConsultaPagoConcepto();
            $arConsultaPagoConcepto->setOrigen('VACACION');
            $arConsultaPagoConcepto->setNumero($arVacacionAdicional->getCodigoVacacionFk());            
            $arConsultaPagoConcepto->setNumeroIdentificacion($arVacacionAdicional->getVacacionRel()->getEmpleadoRel()->getNumeroIdentificacion());
            $arConsultaPagoConcepto->setNombreCorto($arVacacionAdicional->getVacacionRel()->getEmpleadoRel()->getNombreCorto());
            $arConsultaPagoConcepto->setCodigoPagoConceptoFk($arVacacionAdicional->getCodigoPagoConceptoFk());
            $arConsultaPagoConcepto->setNombreConcepto($arVacacionAdicional->getPagoConceptoRel()->getNombre());            
            $arConsultaPagoConcepto->setVrBonificacion($arVacacionAdicional->getVrBonificacion());
            $arConsultaPagoConcepto->setVrDeduccion($arVacacionAdicional->getVrDeduccion());
            $arConsultaPagoConcepto->setFechaDesde($arVacacionAdicional->getVacacionRel()->getFecha());
            $arConsultaPagoConcepto->setFechaHasta($arVacacionAdicional->getVacacionRel()->getFecha());            
            $em->persist($arConsultaPagoConcepto);            
        }        
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->listaConsultaDql($session->get('filtroDesde'), $session->get('filtroHasta'), $codigoEmpleado, $session->get('filtroCodigoPagoConcepto'));
        $query = $em->createQuery($dql);        
        $arLiquidacionAdicionales = $query->getResult();   
        foreach ($arLiquidacionAdicionales as $arLiquidacionAdicional) {
            $arConsultaPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuConsultaPagoConcepto();
            $arConsultaPagoConcepto->setOrigen('LIQUIDACION');
            $arConsultaPagoConcepto->setNumero($arLiquidacionAdicional->getCodigoLiquidacionFk());            
            $arConsultaPagoConcepto->setNumeroIdentificacion($arLiquidacionAdicional->getLiquidacionRel()->getEmpleadoRel()->getNumeroIdentificacion());
            $arConsultaPagoConcepto->setNombreCorto($arLiquidacionAdicional->getLiquidacionRel()->getEmpleadoRel()->getNombreCorto());
            $arConsultaPagoConcepto->setCodigoPagoConceptoFk($arLiquidacionAdicional->getCodigoPagoConceptoFk());
            $arConsultaPagoConcepto->setNombreConcepto($arLiquidacionAdicional->getPagoConceptoRel()->getNombre());            
            $arConsultaPagoConcepto->setVrBonificacion($arLiquidacionAdicional->getVrBonificacion());
            $arConsultaPagoConcepto->setVrDeduccion($arLiquidacionAdicional->getVrDeduccion());
            $arConsultaPagoConcepto->setFechaDesde($arLiquidacionAdicional->getLiquidacionRel()->getFecha());
            $arConsultaPagoConcepto->setFechaHasta($arLiquidacionAdicional->getLiquidacionRel()->getFecha());            
            $em->persist($arConsultaPagoConcepto);            
        }        
        $em->flush();
        
    }

    private function generarExcel() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager(); 
        $session = $this->get('session');
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        for($col = 'A'; $col !== 'K'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        } 
        for($col = 'I'; $col !== 'K'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }          
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ORIGEN')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'CODIGO')
                    ->setCellValue('F1', 'CONCEPTO')
                    ->setCellValue('G1', 'DESDE')
                    ->setCellValue('H1', 'HASTA')
                    ->setCellValue('I1', 'BONIFICACION')
                    ->setCellValue('J1', 'DEDUCCION');

        $i = 2;
        $dql   = "SELECT cpc FROM BrasaRecursoHumanoBundle:RhuConsultaPagoConcepto cpc";        
        $query = $em->createQuery($dql);
        $arConsultaPagoConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuConsultaPagoConcepto();
        $arConsultaPagoConceptos = $query->getResult();
        
        foreach ($arConsultaPagoConceptos as $arConsultaPagoConcepto) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arConsultaPagoConcepto->getOrigen())
                    ->setCellValue('B' . $i, $arConsultaPagoConcepto->getNumero())                    
                    ->setCellValue('C' . $i, $arConsultaPagoConcepto->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arConsultaPagoConcepto->getNombreCorto())
                    ->setCellValue('E' . $i, $arConsultaPagoConcepto->getCodigoPagoConceptoFk())
                    ->setCellValue('F' . $i, $arConsultaPagoConcepto->getNombreConcepto())
                    ->setCellValue('G' . $i, $arConsultaPagoConcepto->getFechaDesde()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arConsultaPagoConcepto->getFechaHasta()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arConsultaPagoConcepto->getVrBonificacion())
                    ->setCellValue('J' . $i, $arConsultaPagoConcepto->getVrDeduccion());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('conceptoPagoConsolidado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="conceptoPagoConsolidado.xlsx"');
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