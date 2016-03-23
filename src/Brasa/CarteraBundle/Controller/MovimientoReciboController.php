<?php
namespace Brasa\CarteraBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\CarteraBundle\Form\Type\CarReciboType;
use Brasa\CarteraBundle\Form\Type\CarReciboDetalleType;

class MovimientoReciboController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/cartera/movimiento/recibo/lista", name="brs_cartera_movimiento_recibo_listar")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->lista();        
        if ($form->isValid()) {               
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaCarteraBundle:CarRecibo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_listar'));                
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcel();
            }
        }

        $arRecibos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaCarteraBundle:Movimientos/Recibo:lista.html.twig', array(
            'arRecibos' => $arRecibos,            
            'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/movimiento/recibo/nuevo/{codigoRecibo}", name="brs_cartera_movimiento_recibo_nuevo")
     */
    public function nuevoAction($codigoRecibo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();
        if($codigoRecibo != 0) {
            $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo);
        }else{
            $arRecibo->setFecha(new \DateTime('now'));
            $arRecibo->setFechaPago(new \DateTime('now'));
        }
        $form = $this->createForm(new CarReciboType, $arRecibo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arRecibo = $form->getData();
            $arrControles = $request->request->All();
            $arCliente = new \Brasa\CarteraBundle\Entity\CarCliente();
            if($arrControles['txtNit'] != '') {                
                $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $arRecibo->setClienteRel($arCliente);
                }
            }
            $arUsuario = $this->getUser();
            $arRecibo->setUsuario($arUsuario->getUserName());            
            $em->persist($arRecibo);
            $em->flush();

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_nuevo', array('codigoRecibo' => 0 )));
            } else {
                if ($codigoRecibo != 0){
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_listar'));
                } else {
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $arRecibo->getCodigoReciboPk())));
                }
                
            }                       
            
        }
        return $this->render('BrasaCarteraBundle:Movimientos/Recibo:nuevo.html.twig', array(
            'arRecibo' => $arRecibo,
            'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/movimiento/recibo/detalle/{codigoRecibo}", name="brs_cartera_movimiento_recibo_detalle")
     */
    public function detalleAction($codigoRecibo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();
        $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo);
        $form = $this->formularioDetalle($arRecibo);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {  
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoRecibo);                
                if($arRecibo->getEstadoAutorizado() == 0) {
                    if($em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->numeroRegistros($codigoRecibo) > 0) {
                        $arRecibo->setEstadoAutorizado(1);
                        $em->persist($arRecibo);
                        $em->flush();                        
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles al recibo de caja', $this);
                    }                    
                }
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arRecibo->getEstadoAutorizado() == 1) {
                    $arRecibo->setEstadoAutorizado(0);
                    $em->persist($arRecibo);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));                
                }
            }              
            if($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoRecibo);                
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->eliminarSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->liquidar($codigoRecibo);
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));
            }    
            if($form->get('BtnImprimir')->isClicked()) {
                $strResultado = $em->getRepository('BrasaCarteraBundle:CarRecibo')->imprimir($codigoRecibo);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                } else {
                    $objRecibo = new \Brasa\CarteraBundle\Formatos\FormatoRecibo();
                    $objRecibo->Generar($this, $codigoRecibo);                   
                }                
            }                        
        }

        $arReciboDetalle = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
        $arReciboDetalle = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array ('codigoReciboFk' => $codigoRecibo));
        return $this->render('BrasaCarteraBundle:Movimientos/Recibo:detalle.html.twig', array(
                    'arRecibo' => $arRecibo,
                    'arReciboDetalle' => $arReciboDetalle,
                    'form' => $form->createView()
                    ));
    }
    
    /**
     * @Route("/cartera/movimiento/recibo/detalle/nuevo/{codigoRecibo}/{codigoReciboDetalle}", name="brs_cartera_movimiento_recibo_detalle_nuevo")
     */
    public function detalleNuevoAction($codigoRecibo, $codigoReciboDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();
        $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo);
        $arCuentasCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
        $arCuentasCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->cuentasCobrar($arRecibo->getCodigoClienteFk());
        $arCuentasCobrar = $paginator->paginate($arCuentasCobrar, $request->query->get('page', 1), 50);
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                $intIndice = 0;
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCuentaCobrar) {
                        $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($codigoCuentaCobrar);
                        $arReciboDetalle = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
                        $arReciboDetalle->setReciboRel($arRecibo);
                        $arReciboDetalle->setCuentaCobrarRel($arCuentaCobrar);
                        $arReciboDetalle->setValor($arrControles['TxtSaldo'.$codigoCuentaCobrar]);
                        $arReciboDetalle->setUsuario($arUsuario->getUserName());
                        $arReciboDetalle->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                        $em->persist($arReciboDetalle); 
                    }
                    $em->flush();
                } 
                $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->liquidar($codigoRecibo);
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        return $this->render('BrasaCarteraBundle:Movimientos/Recibo:detalleNuevo.html.twig', array(
            'arCuentasCobrar' => $arCuentasCobrar,
            'form' => $form->createView()));
    } 
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strListaDql =  $em->getRepository('BrasaCarteraBundle:CarRecibo')->listaDQL(
                $session->get('filtroReciboNumero'), 
                $session->get('filtroCodigoCliente'),
                $session->get('filtroReciboEstadoImpreso'));
    }

    private function filtrar ($form) {       
        $session = $this->getRequest()->getSession();        
        $session->set('filtroReciboNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroReciboEstadoImpreso', $form->get('estadoImpreso')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());   
    }

    private function formularioFiltro() {
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
        
        $form = $this->createFormBuilder()
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroCotizacionNumero')))
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))
            ->add('estadoImpreso', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'IMPRESO', '0' => 'SIN IMPRIMIR'), 'data' => $session->get('filtroReciboEstadoImpreso')))                
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;            
            $arrBotonAprobar['disabled'] = false;            
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)                 
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)
                    ->getForm();
        return $form;
    }

    private function generarExcel() {
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
                    ->setCellValue('C1', 'NIT')                
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'CUENTA')
                    ->setCellValue('F1', 'TIPO RECIBO')
                    ->setCellValue('G1', 'FECHA PAGO')
                    ->setCellValue('H1', 'TOTAL')
                    ->setCellValue('I1', 'ANULADO')
                    ->setCellValue('J1', 'AUTORIZADO');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arRecibos = new \Brasa\CarteraBundle\Entity\CarRecibo();
        $arRecibos = $query->getResult();

        foreach ($arRecibos as $arRecibo) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRecibo->getCodigoReciboPk())
                    ->setCellValue('B' . $i, $arRecibo->getNumero())
                    ->setCellValue('E' . $i, $arRecibo->getCuentaRel()->getNombre())
                    ->setCellValue('F' . $i, $arRecibo->getReciboTipoRel()->getNombre())
                    ->setCellValue('G' . $i, $arRecibo->getFechaPago()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arRecibo->getVrTotal())
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arRecibo->getEstadoAnulado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arRecibo->getEstadoAutorizado()));
            if($arRecibo->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C' . $i, $arRecibo->getClienteRel()->getNit());
            }
            if($arRecibo->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arRecibo->getClienteRel()->getNombreCorto());
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

    private function actualizarDetalle($arrControles, $codigoRecibo) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arReciboDetalle = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
                $arReciboDetalle = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->find($intCodigo);
                $arReciboDetalle->setVrDescuento($arrControles['TxtVrDescuento'.$intCodigo]);
                $arReciboDetalle->setVrAjustePeso($arrControles['TxtVrAjustePeso'.$intCodigo]);
                $arReciboDetalle->setVrReteIca($arrControles['TxtVrReteIca'.$intCodigo]);
                $arReciboDetalle->setVrReteIva($arrControles['TxtVrReteIva'.$intCodigo]);
                $arReciboDetalle->setValor($arrControles['TxtValor'.$intCodigo]);
                $em->persist($arReciboDetalle);
            }
            $em->flush();
            $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->liquidar($codigoRecibo);                   
        }
    }
    
}