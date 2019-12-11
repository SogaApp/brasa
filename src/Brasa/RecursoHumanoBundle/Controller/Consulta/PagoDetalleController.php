<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;


class PagoDetalleController extends Controller
{
    var $strDqlLista = "";
    var $intNumero = 0;
    /**
     * @Route("/rhu/consulta/pago/detalle", name="brs_rhu_consulta_pago_detalle")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 21)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
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
            if($form->get('BtnExcelResumen')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
                $this->generarExcelResumen();
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
            }

        }
        $arPagosDetalle = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/PagoDetalle:PagoDetalle.html.twig', array(
            'arPagosDetalle' => $arPagosDetalle,
            'form' => $form->createView()
            ));
    }     
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->listaDetalleDql(
                    $this->intNumero,
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroCodigoPagoTipo'),
                    $strFechaDesde = $session->get('filtroDesde'),
                    $strFechaHasta = $session->get('filtroHasta'),
                    $session->get('filtroCodigoPagoConcepto')
                    );
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
        $arrayPropiedadesCentroCosto = array(
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
            $arrayPropiedadesCentroCosto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $arrayPropiedadesTipo = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoPagoTipo')) {
            $arrayPropiedadesTipo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoTipo", $session->get('filtroCodigoPagoTipo'));
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
            ->add('centroCostoRel', 'entity', $arrayPropiedadesCentroCosto)
            ->add('pagoConceptoRel', 'entity', $arrayPropiedadesPagoConcepto)
            ->add('pagoTipoRel', 'entity', $arrayPropiedadesTipo)
            //->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))    
            //->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))    
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                            
            ->add('TxtNumero', 'text', array('label'  => 'Numero','data' => $session->get('filtroPagoNumero')))                                                   
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                                            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnExcelResumen', 'submit', array('label'  => 'Excel resumen',))    
            ->getForm();        
        return $form;
    }

    private function filtrarLista($form, Request $request) {
        $session = $this->get('session');        
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);                
        $session->set('filtroCodigoPagoTipo', $controles['pagoTipoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $this->intNumero = $form->get('TxtNumero')->getData();
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
        $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
        $session->set('filtroCodigoPagoConcepto', $controles['pagoConceptoRel']);
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        for($col = 'A'; $col !== 'X'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
                } 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'NUMERO')
                    ->setCellValue('B1', 'CODIGO')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'CODIGO')
                    ->setCellValue('F1', 'CONCEPTO')
                    ->setCellValue('G1', 'GRUPO PAGO')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'HASTA')
                    ->setCellValue('J1', 'DEVENGADO')
                    ->setCellValue('K1', 'DEDUCCION')
                    ->setCellValue('L1', 'HORAS')
                    ->setCellValue('M1', 'DÍAS')
                    ->setCellValue('N1', '%')
                    ->setCellValue('O1', 'VR IBC')    
                    ->setCellValue('P1', 'VR IBP')
                    ->setCellValue('Q1', 'N. CRED')
                    ->setCellValue('R1', 'PEN')
                    ->setCellValue('S1', 'SAL')
                    ->setCellValue('T1', 'ZONA')
                    ->setCellValue('U1', 'SUBZONA')
                    ->setCellValue('V1', 'TIPO EMPLEADO')
                    ->setCellValue('W1', 'C_COSTO');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arPagosDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagosDetalle = $query->getResult();
        
        foreach ($arPagosDetalle as $arPagoDetalle) {  
            $devengado = 0;
            $deduccion = 0;
            if($arPagoDetalle->getOperacion() == 1) {
                $devengado = $arPagoDetalle->getVrPago();
            }
            if($arPagoDetalle->getOperacion() == -1) {
                $deduccion = $arPagoDetalle->getVrPago();
            }            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagoDetalle->getPagoRel()->getNumero())
                    ->setCellValue('B' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getCodigoEmpleadoPk())
                    ->setCellValue('C' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arPagoDetalle->getCodigoPagoConceptoFk())
                    ->setCellValue('F' . $i, $arPagoDetalle->getPagoConceptoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPagoDetalle->getPagoRel()->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $arPagoDetalle->getPagoRel()->getFechaDesdePago()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arPagoDetalle->getPagoRel()->getFechaHastaPago()->format('Y-m-d'))
                    ->setCellValue('J' . $i, $devengado)
                    ->setCellValue('K' . $i, $deduccion)
                    ->setCellValue('L' . $i, $arPagoDetalle->getNumeroHoras())
                    ->setCellValue('M' . $i, $arPagoDetalle->getNumeroDias())
                    ->setCellValue('N' . $i, $arPagoDetalle->getPorcentajeAplicado())
                    ->setCellValue('O' . $i, round($arPagoDetalle->getVrIngresoBaseCotizacion()))
                    ->setCellValue('P' . $i, round($arPagoDetalle->getVrIngresoBasePrestacion()))
                    ->setCellValue('Q' . $i, $arPagoDetalle->getCodigoCreditoFk())
                    ->setCellValue('R' . $i, $objFunciones->devuelveBoolean($arPagoDetalle->getPension()))
                    ->setCellValue('S' . $i, $objFunciones->devuelveBoolean($arPagoDetalle->getSalud()));
            if($arPagoDetalle->getPagoRel()->getEmpleadoRel()->getCodigoZonaFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getZonaRel()->getNombre());
            }
            if($arPagoDetalle->getPagoRel()->getEmpleadoRel()->getCodigoSubzonaFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getSubzonaRel()->getNombre());
            }   
            if($arPagoDetalle->getPagoRel()->getEmpleadoRel()->getCodigoEmpleadoTipoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getEmpleadoTipoRel()->getNombre());
            }            
            if($arPagoDetalle->getPagoRel()->getEmpleadoRel()->getCodigoCentroCostoContabilidadFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getCentroCostoContabilidadRel()->getNombre());
            }            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('pagosDetalle');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PagosDetalle.xlsx"');
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
    
    private function generarExcelResumen() {
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
        for($col = 'A'; $col !== 'O'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
                } 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'IDENTIFICACIÓN')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'CODIGO')
                    ->setCellValue('E1', 'CONCEPTO')
                    ->setCellValue('F1', 'DEVENGADO')
                    ->setCellValue('G1', 'DEDUCCION');

        $i = 2;
        /*$query = $em->createQuery($this->strDqlLista);
        $arPagosDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagosDetalle = $query->getResult();*/
        $arPagosDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagosDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->listaDetalleResumenDql(
                    $this->intNumero,
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroCodigoPagoTipo'),
                    $strFechaDesde = $session->get('filtroDesde'),
                    $strFechaHasta = $session->get('filtroHasta'),
                    $session->get('filtroCodigoPagoConcepto')
                );
        
        //$arPagosDetalle = $em->createQuery($arPagosDetalle);
        //$arPagosDetalle = $arPagosDetalle->getResult();
        
        foreach ($arPagosDetalle as $arPagoDetalle) { 
            $devengado = 0;
            $deduccion = 0;
            if($arPagoDetalle['operacion'] == 1) {
                $devengado = $arPagoDetalle['Valor'];
            }
            if($arPagoDetalle['operacion'] == -1) {
                $deduccion = $arPagoDetalle['Valor'];
            }            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagoDetalle['codigoEmpleado'])
                    ->setCellValue('B' . $i, $arPagoDetalle['Identificacion'])
                    ->setCellValue('C' . $i, $arPagoDetalle['Empleado'])
                    ->setCellValue('D' . $i, $arPagoDetalle['codigoConcepto'])
                    ->setCellValue('E' . $i, $arPagoDetalle['Concepto'])
                    ->setCellValue('F' . $i, $devengado)
                    ->setCellValue('G' . $i, $deduccion);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('pagosDetalleResumen');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="pagosDetalleResumen.xlsx"');
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
