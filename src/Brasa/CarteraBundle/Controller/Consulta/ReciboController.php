<?php
namespace Brasa\CarteraBundle\Controller\Consulta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class ReciboController extends Controller
{
    var $strListaDql = "";
    var $strDetalleDql = "";
    var $strFechaDesde = "";
    var $strFechaHasta = "";
    
    /**
     * @Route("/cartera/consulta/recibo/lista", name="brs_cartera_consulta_recibo_lista")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 55)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        //$this->estadoAnulado = 0;
        $form = $this->formularioFiltroLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {            
            if ($form->get('BtnFiltrarLista')->isClicked()) {
                $this->filtrarLista($form);
                //$form = $this->formularioFiltroLista();
                $this->lista();
            }
            if ($form->get('BtnExcelLista')->isClicked()) {
                $this->filtrarLista($form);
                //$form = $this->formularioFiltroLista();
                $this->lista();
                $this->generarListaExcel();
            }
        }
        $arRecibos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaCarteraBundle:Consultas/Recibo:lista.html.twig', array(
            'arRecibos' => $arRecibos,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/cartera/consulta/recibo/detalle", name="brs_cartera_consulta_recibo_detalle")
     */    
    public function detalleAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 56)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        //$this->estadoAnulado = 0;
        $form = $this->formularioFiltroDetalle();
        $form->handleRequest($request);
        $this->detalle();
        if ($form->isValid()) {            
            if ($form->get('BtnFiltrarDetalle')->isClicked()) {
                $this->filtrarDetalle($form);
                //$form = $this->formularioFiltroDetalle();
                $this->detalle();
            }
            if ($form->get('BtnExcelDetalle')->isClicked()) {
                $this->filtrarDetalle($form);
                //$form = $this->formularioFiltroDetalle();
                $this->detalle();
                $this->generarDetalleExcel();
            }
        }
        
        $arRecibosDetalles = $paginator->paginate($em->createQuery($this->strDetalleDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaCarteraBundle:Consultas/Recibo:detalle.html.twig', array(
            'arRecibosDetalles' => $arRecibosDetalles,
            'form' => $form->createView()));
    }
            
    private function lista() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $this->strListaDql =  $em->getRepository('BrasaCarteraBundle:CarRecibo')->listaConsultaDql(
                $session->get('filtroNumero'), 
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroReciboTipo'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta'));
    }
    
    private function detalle() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $this->strDetalleDql =  $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->detalleConsultaDql(
                $session->get('filtroNumero'), 
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroCuentaCobrarTipo'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta'));
    }

    private function filtrarLista ($form) {
        $session = $this->getRequest()->getSession(); 
        $arReciboTipo = $form->get('reciboTipoRel')->getData();
        if ($arReciboTipo == null){
            $codigo = "";
        } else {
            $codigo = $arReciboTipo->getCodigoReciboTipoPk();
        }
        $fechaDesde =  $form->get('fechaDesde')->getData();
        $fechaHasta =  $form->get('fechaHasta')->getData();        
        $session->set('filtroNumero', $form->get('TxtNumero')->getData());           
        $session->set('filtroReciboTipo', $codigo);
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $session->set('filtroDesde', $fechaDesde->format('Y/m/d'));
        $session->set('filtroHasta', $fechaHasta->format('Y/m/d'));
        
    }
    
    private function filtrarDetalle ($form) {
        $session = $this->getRequest()->getSession(); 
        $arCuentaCobrarTipo = $form->get('cuentaCobrarTipoRel')->getData();
        if ($arCuentaCobrarTipo == null){
            $codigo = "";
        } else {
            $codigo = $arCuentaCobrarTipo->getCodigoCuentaCobrarTipoPk();
        }
        $fechaDesde =  $form->get('fechaDesde')->getData();
        $fechaHasta =  $form->get('fechaHasta')->getData();          
        $session->set('filtroNumero', $form->get('TxtNumero')->getData());           
        $session->set('filtroCuentaCobrarTipo', $codigo);
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $session->set('filtroDesde', $fechaDesde->format('Y/m/d'));
        $session->set('filtroHasta', $fechaHasta->format('Y/m/d'));
        
    }

    private function formularioFiltroLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        }       
        $arrayPropiedades = array(
                'class' => 'BrasaCarteraBundle:CarReciboTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rt')
                    ->orderBy('rt.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroReciboTipo')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaCarteraBundle:CarReciboTipo", $session->get('filtroReciboTipo'));
        }
        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroPedidoNumero')))            
            ->add('reciboTipoRel', 'entity', $arrayPropiedades)
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => new \DateTime('now'))) 
            ->add('BtnExcelLista', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrarLista', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }   
    
    private function formularioFiltroDetalle() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        }       
        $arrayPropiedades = array(
                'class' => 'BrasaCarteraBundle:CarCuentaCobrarTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCuentaCobrarTipo')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaCarteraBundle:CarCuentaCobrarTipo", $session->get('filtroCuentaCobrarTipo'));
        }
        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroPedidoNumero')))            
            ->add('cuentaCobrarTipoRel', 'entity', $arrayPropiedades)
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))
            ->add('BtnExcelDetalle', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrarDetalle', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }   

    private function generarListaExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();        
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
        for($col = 'A'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        }     
        for($col = 'H'; $col !== 'O'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }      
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'NIT')                
                    ->setCellValue('E1', 'CLIENTE')
                    ->setCellValue('F1', 'CUENTA')
                    ->setCellValue('G1', 'RECIBO TIPO')
                    ->setCellValue('H1', 'FECHA PAGO')
                    ->setCellValue('I1', 'TOTAL DESCUENTO')
                    ->setCellValue('J1', 'TOTAL AJUSTE PESO')
                    ->setCellValue('K1', 'TOTAL RTE ICA')
                    ->setCellValue('L1', 'TOTAL RTE IVA')
                    ->setCellValue('M1', 'TOTAL RTE FUENTE')
                    ->setCellValue('N1', 'TOTAL')
                    ->setCellValue('O1', 'TOTAL PAGO')
                    ->setCellValue('P1', 'ANULADO')
                    ->setCellValue('Q1', 'AUTORIZADO')
                    ->setCellValue('R1', 'IMPRESO');
        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arRecibos = new \Brasa\CarteraBundle\Entity\CarRecibo();
        $arRecibos = $query->getResult();
        foreach ($arRecibos as $arRecibo) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRecibo->getCodigoReciboPk())
                    ->setCellValue('B' . $i, $arRecibo->getNumero())
                    ->setCellValue('C' . $i, $arRecibo->getFecha()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arRecibo->getFechaPago()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arRecibo->getVrTotalDescuento())
                    ->setCellValue('J' . $i, $arRecibo->getVrTotalAjustePeso())
                    ->setCellValue('K' . $i, $arRecibo->getVrTotalReteIca())
                    ->setCellValue('L' . $i, $arRecibo->getVrTotalReteIva())
                    ->setCellValue('M' . $i, $arRecibo->getVrTotalReteFuente())
                    ->setCellValue('N' . $i, $arRecibo->getVrTotal())
                    ->setCellValue('O' . $i, $arRecibo->getVrTotalPago())
                    ->setCellValue('P' . $i, $objFunciones->devuelveBoolean($arRecibo->getEstadoAnulado()))
                    ->setCellValue('Q' . $i, $objFunciones->devuelveBoolean($arRecibo->getEstadoAutorizado()))
                    ->setCellValue('R' . $i, $objFunciones->devuelveBoolean($arRecibo->getEstadoImpreso()));
            if($arRecibo->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arRecibo->getClienteRel()->getNit());
            }
            if($arRecibo->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('E' . $i, $arRecibo->getClienteRel()->getNombreCorto());
            }
            if($arRecibo->getCuentaRel()->getNombre()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F' . $i, $arRecibo->getCuentaRel()->getNombre());
            }
            if($arRecibo->getReciboTipoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('G' . $i, $arRecibo->getReciboTipoRel()->getNombre());
            }    
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Recibos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Recibos.xlsx"');
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
    
    private function generarDetalleExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();        
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
        for($col = 'A'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        }     
        for($col = 'H'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'NIT')                
                    ->setCellValue('E1', 'CLIENTE')
                    ->setCellValue('F1', 'CUENTA COBRAR TIPO')
                    ->setCellValue('G1', 'DESCUENTO')
                    ->setCellValue('H1', 'AJUSTE PESO')
                    ->setCellValue('I1', 'RTE ICA')
                    ->setCellValue('J1', 'RTE IVA')
                    ->setCellValue('K1', 'RTE FUENTE')
                    ->setCellValue('L1', 'VALOR')
                    ->setCellValue('M1', 'VALOR PAGO');

        $i = 2;
        $query = $em->createQuery($this->strDetalleDql);
        $arRecibosDetalles = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
        $arRecibosDetalles = $query->getResult();

        foreach ($arRecibosDetalles as $arReciboDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arReciboDetalle->getCodigoReciboDetallePk())
                    ->setCellValue('B' . $i, $arReciboDetalle->getNumeroFactura())
                    ->setCellValue('C' . $i, $arReciboDetalle->getReciboRel()->getFecha()->format('Y-m-d'))
                    ->setCellValue('D' . $i, $arReciboDetalle->getReciboRel()->getClienteRel()->getNit())
                    ->setCellValue('E' . $i, $arReciboDetalle->getReciboRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arReciboDetalle->getCuentaCobrarTipoRel()->getNombre())
                    ->setCellValue('G' . $i, $arReciboDetalle->getVrDescuento())
                    ->setCellValue('H' . $i, $arReciboDetalle->getVrAjustePeso())
                    ->setCellValue('I' . $i, $arReciboDetalle->getVrReteIca())
                    ->setCellValue('J' . $i, $arReciboDetalle->getVrReteIva())
                    ->setCellValue('K' . $i, $arReciboDetalle->getVrReteFuente())
                    ->setCellValue('L' . $i, $arReciboDetalle->getValor())
                    ->setCellValue('M' . $i, $arReciboDetalle->getVrPagoDetalle());   
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('RecibosDetalles');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="RecibosDetalles.xlsx"');
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