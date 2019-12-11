<?php
namespace Brasa\CarteraBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class AnticipoController extends Controller
{
    var $strListaDql = "";
    var $strDetalleDql = "";
    var $strFechaDesde = "";
    var $strFechaHasta = "";
    
    /**
     * @Route("/cartera/consulta/anticipo/lista", name="brs_cartera_consulta_anticipo_lista")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 52)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        //$this->estadoAnulado = 0;
        $form = $this->formularioFiltroLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {            
            if ($form->get('BtnFiltrarLista')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->lista();
            }
            if ($form->get('BtnExcelLista')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->lista();
                $this->generarListaExcel();
            }
        }
        $arAnticipos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaCarteraBundle:Consultas/Anticipo:lista.html.twig', array(
            'arAnticipos' => $arAnticipos,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/cartera/consulta/anticipo/detalle", name="brs_cartera_consulta_anticipo_detalle")
     */    
    public function detalleAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 53)) {
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
                $this->detalle();
            }
            if ($form->get('BtnExcelDetalle')->isClicked()) {
                $this->filtrarDetalle($form);
                $this->detalle();
                $this->generarDetalleExcel();
            }
        }
        
        $arAnticiposDetalles = $paginator->paginate($em->createQuery($this->strDetalleDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaCarteraBundle:Consultas/Anticipo:detalle.html.twig', array(
            'arAnticiposDetalles' => $arAnticiposDetalles,
            'form' => $form->createView()));
    }
            
    private function lista() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $this->strListaDql =  $em->getRepository('BrasaCarteraBundle:CarAnticipo')->listaConsultaDql(
                $session->get('filtroNumero'), 
                $session->get('filtroCodigoCliente'), 
                '',//$session->get('filtroAnticipoTipo'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta'));
    }
    
    private function detalle() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $this->strDetalleDql =  $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->detalleConsultaDql(
                $session->get('filtroNumero'), 
                $session->get('filtroCodigoCliente'), 
                '',//$session->get('filtroCuentaCobrarTipo'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta'));
    }

    private function filtrarLista ($form, Request $request) {
        $session = $this->getRequest()->getSession(); 
        /*$arAnticipoTipo = $form->get('anticipoTipoRel')->getData();
        if ($arAnticipoTipo == null){
            $codigo = "";
        } else {
            $codigo = $arAnticipoTipo->getCodigoAnticipoTipoPk();
        }*/
        $fechaDesde =  $form->get('fechaDesde')->getData();
        $fechaHasta =  $form->get('fechaHasta')->getData();        
        $session->set('filtroNumero', $form->get('TxtNumero')->getData());           
        //$session->set('filtroAnticipoTipo', $codigo);
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
        /*$arrayPropiedades = array(
                'class' => 'BrasaCarteraBundle:CarAnticipoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rt')
                    ->orderBy('rt.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroAnticipoTipo')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaCarteraBundle:CarAnticipoTipo", $session->get('filtroAnticipoTipo'));
        }*/
        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroPedidoNumero')))            
            //->add('anticipoTipoRel', 'entity', $arrayPropiedades)
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
        for($col = 'A'; $col !== 'P'; $col++) {
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
                    ->setCellValue('F1', 'CUENTA')
                    ->setCellValue('G1', 'FECHA PAGO')
                    ->setCellValue('H1', 'TOTAL DESCUENTO')
                    ->setCellValue('I1', 'TOTAL AJUSTE PESO')
                    ->setCellValue('J1', 'TOTAL RTE ICA')
                    ->setCellValue('K1', 'TOTAL RTE IVA')
                    ->setCellValue('L1', 'TOTAL RTE FUENTE')
                    ->setCellValue('M1', 'TOTAL')
                    ->setCellValue('N1', 'ANULADO')
                    ->setCellValue('O1', 'AUTORIZADO')
                    ->setCellValue('P1', 'IMPRESO');
        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arAnticipos = new \Brasa\CarteraBundle\Entity\CarAnticipo();
        $arAnticipos = $query->getResult();
        
        foreach ($arAnticipos as $arAnticipo) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAnticipo->getCodigoAnticipoPk())
                    ->setCellValue('B' . $i, $arAnticipo->getNumero())
                    ->setCellValue('C' . $i, $arAnticipo->getFecha()->format('Y-m-d'))
                    ->setCellValue('G' . $i, $arAnticipo->getFechaPago()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arAnticipo->getVrTotalDescuento())
                    ->setCellValue('I' . $i, $arAnticipo->getVrTotalAjustePeso())
                    ->setCellValue('J' . $i, $arAnticipo->getVrTotalReteIca())
                    ->setCellValue('K' . $i, $arAnticipo->getVrTotalReteIva())
                    ->setCellValue('L' . $i, $arAnticipo->getVrTotalReteFuente())
                    ->setCellValue('M' . $i, $arAnticipo->getVrTotal())
                    ->setCellValue('N' . $i, $objFunciones->devuelveBoolean($arAnticipo->getEstadoAnulado()))
                    ->setCellValue('O' . $i, $objFunciones->devuelveBoolean($arAnticipo->getEstadoAutorizado()))
                    ->setCellValue('P' . $i, $objFunciones->devuelveBoolean($arAnticipo->getEstadoImpreso()));
            if($arAnticipo->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arAnticipo->getClienteRel()->getNit());
            }
            if($arAnticipo->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('E' . $i, $arAnticipo->getClienteRel()->getNombreCorto());
            }
            if($arAnticipo->getCuentaRel()->getNombre()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F' . $i, $arAnticipo->getCuentaRel()->getNombre());
            }
            /*if($arAnticipo->getAnticipoTipoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('G' . $i, $arAnticipo->getAnticipoTipoRel()->getNombre());
            }*/    
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Anticipos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Anticipos.xlsx"');
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
                    ->setCellValue('L1', 'VALOR');

        $i = 2;
        $query = $em->createQuery($this->strDetalleDql);
        $arAnticiposDetalles = new \Brasa\CarteraBundle\Entity\CarAnticipoDetalle();
        $arAnticiposDetalles = $query->getResult();

        foreach ($arAnticiposDetalles as $arAnticipoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAnticipoDetalle->getCodigoAnticipoDetallePk())
                    ->setCellValue('B' . $i, $arAnticipoDetalle->getNumeroFactura())
                    ->setCellValue('C' . $i, $arAnticipoDetalle->getAnticipoRel()->getFecha()->format('Y-m-d'))
                    ->setCellValue('D' . $i, $arAnticipoDetalle->getAnticipoRel()->getClienteRel()->getNit())
                    ->setCellValue('E' . $i, $arAnticipoDetalle->getAnticipoRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arAnticipoDetalle->getCuentaCobrarTipoRel()->getNombre())
                    ->setCellValue('G' . $i, $arAnticipoDetalle->getVrDescuento())
                    ->setCellValue('H' . $i, $arAnticipoDetalle->getVrAjustePeso())
                    ->setCellValue('I' . $i, $arAnticipoDetalle->getVrReteIca())
                    ->setCellValue('J' . $i, $arAnticipoDetalle->getVrReteIva())
                    ->setCellValue('K' . $i, $arAnticipoDetalle->getVrReteFuente())
                    ->setCellValue('L' . $i, $arAnticipoDetalle->getValor());   
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('AnticiposDetalles');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="AnticiposDetalles.xlsx"');
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