<?php
namespace Brasa\CarteraBundle\Controller\Consulta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class NotaDebitoController extends Controller
{
    var $strListaDql = "";
    var $strDetalleDql = "";
    var $strFechaDesde = "";
    var $strFechaHasta = "";
    
    /**
     * @Route("/cartera/consulta/notadebito/lista", name="brs_cartera_consulta_notadebito_lista")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 59)) {
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
                $form = $this->formularioFiltroLista();
                $this->lista();
            }
            if ($form->get('BtnExcelLista')->isClicked()) {
                $this->filtrarLista($form);
                $form = $this->formularioFiltroLista();
                $this->lista();
                $this->generarListaExcel();
            }
        }
        $arNotaDebitos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaCarteraBundle:Consultas/NotaDebito:lista.html.twig', array(
            'arNotaDebitos' => $arNotaDebitos,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/cartera/consulta/notadebito/detalle", name="brs_cartera_consulta_notadebito_detalle")
     */    
    public function detalleAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 60)) {
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
                $form = $this->formularioFiltroDetalle();
                $this->detalle();
            }
            if ($form->get('BtnExcelDetalle')->isClicked()) {
                $this->filtrarDetalle($form);
                $form = $this->formularioFiltroDetalle();
                $this->detalle();
                $this->generarDetalleExcel();
            }
        }
        
        $arNotaDebitosDetalles = $paginator->paginate($em->createQuery($this->strDetalleDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaCarteraBundle:Consultas/NotaDebito:detalle.html.twig', array(
            'arNotaDebitosDetalles' => $arNotaDebitosDetalles,
            'form' => $form->createView()));
    }
            
    private function lista() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $this->strListaDql =  $em->getRepository('BrasaCarteraBundle:CarNotaDebito')->listaConsultaDql(
                $session->get('filtroNumero'), 
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroNotaDebitoConcepto'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta'));
    }
    
    private function detalle() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $this->strDetalleDql =  $em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->detalleConsultaDql(
                $session->get('filtroNumero'), 
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroCuentaCobrarTipo'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta'));
    }

    private function filtrarLista ($form) {
        $session = $this->getRequest()->getSession(); 
        $arNotaDebitoConcepto = $form->get('notaDebitoConceptoRel')->getData();
        if ($arNotaDebitoConcepto == null){
            $codigo = "";
        } else {
            $codigo = $arNotaDebitoConcepto->getCodigoNotaDebitoConceptoPk();
        }
        $session->set('filtroNumero', $form->get('TxtNumero')->getData());           
        $session->set('filtroNotaDebitoConcepto', $codigo);
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $fechaDesde =  $form->get('fechaDesde')->getData();
        $fechaHasta =  $form->get('fechaHasta')->getData();
        if ($fechaDesde == null || $fechaHasta == null){
            $session->set('filtroDesde', $fechaDesde);
            $session->set('filtroHasta', $fechaHasta);
        } else {
            $session->set('filtroDesde', $fechaDesde->format('Y/m/d'));
            $session->set('filtroHasta', $fechaHasta->format('Y/m/d'));  
        }
    }
    
    private function filtrarDetalle ($form) {
        $session = $this->getRequest()->getSession(); 
        $arCuentaCobrarTipo = $form->get('cuentaCobrarTipoRel')->getData();
        if ($arCuentaCobrarTipo == null){
            $codigo = "";
        } else {
            $codigo = $arCuentaCobrarTipo->getCodigoCuentaCobrarTipoPk();
        }
        $session->set('filtroNumero', $form->get('TxtNumero')->getData());           
        $session->set('filtroCuentaCobrarTipo', $codigo);
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        
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
                'class' => 'BrasaCarteraBundle:CarNotaDebitoConcepto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ndc')
                    ->orderBy('ndc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroNotaDebitoConcepto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaCarteraBundle:CarNotaDebitoConcepto", $session->get('filtroNotaDebitoConcepto'));
        }
        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroPedidoNumero')))            
            ->add('notaDebitoConceptoRel', 'entity', $arrayPropiedades)
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date')))
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
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date')))
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
                    ->setCellValue('F1', 'CUENTA')
                    ->setCellValue('G1', 'CONCEPTO')
                    ->setCellValue('H1', 'FECHA PAGO')
                    ->setCellValue('I1', 'TOTAL')
                    ->setCellValue('J1', 'ANULADO')
                    ->setCellValue('K1', 'AUTORIZADO')
                    ->setCellValue('L1', 'IMPRESO');
        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arNotasDebitos = new \Brasa\CarteraBundle\Entity\CarNotaDebito();
        $arNotasDebitos = $query->getResult();
        foreach ($arNotasDebitos as $arNotaDebito) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arNotaDebito->getCodigoNotaDebitoPk())
                    ->setCellValue('B' . $i, $arNotaDebito->getNumero())
                    ->setCellValue('C' . $i, $arNotaDebito->getFecha()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arNotaDebito->getFechaPago()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arNotaDebito->getValor())
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arNotaDebito->getEstadoAnulado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arNotaDebito->getEstadoAutorizado()))
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arNotaDebito->getEstadoImpreso()));
            if($arNotaDebito->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arNotaDebito->getClienteRel()->getNit());
            }
            if($arNotaDebito->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('E' . $i, $arNotaDebito->getClienteRel()->getNombreCorto());
            }
            if($arNotaDebito->getCuentaRel()->getNombre()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F' . $i, $arNotaDebito->getCuentaRel()->getNombre());
            }
            if($arNotaDebito->getNotaDebitoConceptoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('G' . $i, $arNotaDebito->getNotaDebitoConceptoRel()->getNombre());
            }    
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('NotaDebitos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="NotaDebitos.xlsx"');
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
                    ->setCellValue('F1', 'TIPO')
                    ->setCellValue('G1', 'VALOR');

        $i = 2;
        $query = $em->createQuery($this->strDetalleDql);
        $arNotaDebitosDetalles = new \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle();
        $arNotaDebitosDetalles = $query->getResult();

        foreach ($arNotaDebitosDetalles as $arNotaDebitoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arNotaDebitoDetalle->getCodigoNotaDebitoDetallePk())
                    ->setCellValue('B' . $i, $arNotaDebitoDetalle->getNumeroFactura())
                    ->setCellValue('C' . $i, $arNotaDebitoDetalle->getNotaDebitoRel()->getFecha()->format('Y-m-d'))
                    ->setCellValue('D' . $i, $arNotaDebitoDetalle->getNotaDebitoRel()->getClienteRel()->getNit())
                    ->setCellValue('E' . $i, $arNotaDebitoDetalle->getNotaDebitoRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arNotaDebitoDetalle->getCuentaCobrarTipoRel()->getNombre())
                    ->setCellValue('G' . $i, $arNotaDebitoDetalle->getValor());   
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('NotaDebitosDetalles');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="NotaDebitosDetalles.xlsx"');
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