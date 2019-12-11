<?php
namespace Brasa\TurnoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurCotizacionType;
use Brasa\TurnoBundle\Form\Type\TurCotizacionDetalleType;
use Brasa\TurnoBundle\Form\Type\TurCotizacionOtroType;

class CotizacionController extends Controller
{
    var $strListaDql = "";
    var $numero = "";
    var $codigoCliente = "";
    var $estadoAutorizado = "";
    
    /**
     * @Route("/tur/movimiento/cotizacion", name="brs_tur_movimiento_cotizacion")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 25, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->lista();        
        if ($form->isValid()) {               
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurCotizacion')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_cotizacion'));                
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

        $arCotizaciones = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Cotizacion:lista.html.twig', array(
            'arCotizaciones' => $arCotizaciones,            
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/cotizacion/nuevo/{codigoCotizacion}", name="brs_tur_movimiento_cotizacion_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoCotizacion) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arCotizacion = new \Brasa\TurnoBundle\Entity\TurCotizacion();
        if($codigoCotizacion != 0) {
            $arCotizacion = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->find($codigoCotizacion);
        }else{
            $arCotizacion->setFecha(new \DateTime('now'));
            $arCotizacion->setFechaVence(new \DateTime('now'));
        }
        $form = $this->createForm(TurCotizacionType::class, $arCotizacion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arCotizacion = $form->getData();
            $arrControles = $request->request->All();
            $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
            if($arrControles['txtNit'] != '') {                
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $arCotizacion->setClienteRel($arCliente);
                }
            }
            $arUsuario = $this->getUser();
            $arCotizacion->setUsuario($arUsuario->getUserName());            
            $em->persist($arCotizacion);
            $em->flush();

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_movimiento_cotizacion_nuevo', array('codigoCotizacion' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_movimiento_cotizacion_detalle', array('codigoCotizacion' => $arCotizacion->getCodigoCotizacionPk())));
            }                       
                               
             
            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Cotizacion:nuevo.html.twig', array(
            'arCotizacion' => $arCotizacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/cotizacion/detalle/{codigoCotizacion}", name="brs_tur_movimiento_cotizacion_detalle")
     */     
    public function detalleAction(Request $request, $codigoCotizacion) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $arCotizacion = new \Brasa\TurnoBundle\Entity\TurCotizacion();
        $arCotizacion = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->find($codigoCotizacion);
        $form = $this->formularioDetalle($arCotizacion);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {  
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoCotizacion);                
                if($arCotizacion->getEstadoAutorizado() == 0) {
                    if($em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->numeroRegistros($codigoCotizacion) > 0) {
                        $arCotizacion->setEstadoAutorizado(1);
                        $em->persist($arCotizacion);
                        $em->flush();                        
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles a la cotizacion', $this);
                    }                    
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_cotizacion_detalle', array('codigoCotizacion' => $codigoCotizacion)));                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arCotizacion->getEstadoAutorizado() == 1) {
                    $arCotizacion->setEstadoAutorizado(0);
                    $em->persist($arCotizacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_cotizacion_detalle', array('codigoCotizacion' => $codigoCotizacion)));                
                }
            }   
            if($form->get('BtnAprobar')->isClicked()) {            
                if($arCotizacion->getEstadoAutorizado() == 1) {
                    $arCotizacion->setEstadoAprobado(1);
                    $em->persist($arCotizacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_cotizacion_detalle', array('codigoCotizacion' => $codigoCotizacion)));                
                }
            }            
            if($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoCotizacion);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_cotizacion_detalle', array('codigoCotizacion' => $codigoCotizacion)));
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->eliminarSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurCotizacion')->liquidar($codigoCotizacion);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_cotizacion_detalle', array('codigoCotizacion' => $codigoCotizacion)));
            }  
            if($form->get('BtnOtroActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigoCotizacionOtro'] as $intCodigo) {
                    $arCotizacionOtro = new \Brasa\TurnoBundle\Entity\TurCotizacionOtro();
                    $arCotizacionOtro = $em->getRepository('BrasaTurnoBundle:TurCotizacionOtro')->find($intCodigo);
                    $arCotizacionDetalle->setCantidad($arrControles['TxtCantidad'.$intCodigo]);                                                          
                    $em->persist($arCotizacionDetalle);
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurCotizacion')->liquidar($codigoCotizacion);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_cotizacion_detalle', array('codigoCotizacion' => $codigoCotizacion)));
            }
            if($form->get('BtnOtroEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->eliminarSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurCotizacion')->liquidar($codigoCotizacion);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_cotizacion_detalle', array('codigoCotizacion' => $codigoCotizacion)));
            }   
            if($form->get('BtnImprimir')->isClicked()) {
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->imprimir($codigoCotizacion);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                } else {
                    $objCotizacion = new \Brasa\TurnoBundle\Formatos\FormatoCotizacion();
                    $objCotizacion->Generar($this, $codigoCotizacion);                   
                }                
            }                        
        }

        $arCotizacionDetalle = new \Brasa\TurnoBundle\Entity\TurCotizacionDetalle();
        $arCotizacionDetalle = $em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->findBy(array ('codigoCotizacionFk' => $codigoCotizacion));
        $arCotizacionOtros = new \Brasa\TurnoBundle\Entity\TurCotizacionOtro();
        $arCotizacionOtros = $em->getRepository('BrasaTurnoBundle:TurCotizacionOtro')->findBy(array ('codigoCotizacionFk' => $codigoCotizacion));        
        return $this->render('BrasaTurnoBundle:Movimientos/Cotizacion:detalle.html.twig', array(
                    'arCotizacion' => $arCotizacion,
                    'arCotizacionDetalle' => $arCotizacionDetalle,
                    'arCotizacionOtros' => $arCotizacionOtros,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/tur/movimiento/cotizacion/detalle/nuevo/{codigoCotizacion}/{codigoCotizacionDetalle}", name="brs_tur_movimiento_cotizacion_detalle_nuevo")
     */     
    public function detalleNuevoAction(Request $request, $codigoCotizacion, $codigoCotizacionDetalle = 0) {
        $em = $this->getDoctrine()->getManager();
        $arCotizacion = new \Brasa\TurnoBundle\Entity\TurCotizacion();
        $arCotizacion = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->find($codigoCotizacion);
        $arCotizacionDetalle = new \Brasa\TurnoBundle\Entity\TurCotizacionDetalle();
        if($codigoCotizacionDetalle != 0) {
            $arCotizacionDetalle = $em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->find($codigoCotizacionDetalle);
        } else {
            $arCotizacionDetalle->setLunes(true);
            $arCotizacionDetalle->setMartes(true);
            $arCotizacionDetalle->setMiercoles(true);
            $arCotizacionDetalle->setJueves(true);
            $arCotizacionDetalle->setViernes(true);
            $arCotizacionDetalle->setSabado(true);
            $arCotizacionDetalle->setDomingo(true);
            $arCotizacionDetalle->setFestivo(true);            
            $arCotizacionDetalle->setCantidad(1);
            $arCotizacionDetalle->setFechaDesde(new \DateTime('now'));
            $arCotizacionDetalle->setFechaHasta(new \DateTime('now'));
        }
        $form = $this->createForm(TurCotizacionDetalleType::class, $arCotizacionDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arCotizacionDetalle = $form->getData();
            $arCotizacionDetalle->setCotizacionRel($arCotizacion);
            $em->persist($arCotizacionDetalle);
            $em->flush();
            $em->getRepository('BrasaTurnoBundle:TurCotizacion')->liquidar($codigoCotizacion);
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_movimiento_cotizacion_detalle_nuevo', array('codigoCotizacion' => $codigoCotizacion, 'codigoCotizacionDetalle' => 0 )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Cotizacion:detalleNuevo.html.twig', array(
            'arCotizacion' => $arCotizacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/cotizacion/detalle/otro/nuevo/{codigoCotizacion}/{codigoCotizacionOtro}", name="brs_tur_movimiento_cotizacion_detalle_otro_nuevo")
     */
    public function detalleOtroNuevoAction(Request $request, $codigoCotizacion, $codigoCotizacionOtro = 0) {
        $em = $this->getDoctrine()->getManager();
        $arCotizacion = new \Brasa\TurnoBundle\Entity\TurCotizacion();
        $arCotizacion = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->find($codigoCotizacion);
        $arCotizacionOtro = new \Brasa\TurnoBundle\Entity\TurCotizacionOtro();
        if($codigoCotizacionOtro != 0) {
            $arCotizacionOtro = $em->getRepository('BrasaTurnoBundle:TurCotizacionOtro')->find($codigoCotizacionOtro);
        }
        $form = $this->createForm(new TurCotizacionOtroType, $arCotizacionOtro);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arCotizacionOtro = $form->getData();
            $arCotizacionOtro->setCotizacionRel($arCotizacion);
            $em->persist($arCotizacionOtro);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_movimiento_cotizacion_detalle_nuevo', array('codigoCotizacion' => $codigoCotizacion, 'codigoCotizacionDetalle' => 0 )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Cotizacion:otroNuevo.html.twig', array(
            'arCotizacion' => $arCotizacion,
            'form' => $form->createView()));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurCotizacion')->listaDQL(
                $session->get('filtroCotizacionNumero'), 
                $session->get('filtroCodigoCliente'),
                $session->get('filtroCotizacionEstadoAutorizado'));
    }

    private function filtrar ($form) {       
        $session = new session;   
        $session->set('filtroCotizacionNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroCotizacionEstadoAutorizado', $form->get('estadoAutorizado')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());   
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
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
            ->add('TxtNumero', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroCotizacionNumero')))
            ->add('TxtNit', TextType::class, array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', TextType::class, array('label'  => 'NombreCliente','data' => $strNombreCliente))
            ->add('estadoAutorizado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'AUTORIZADO', '0' => 'SIN AUTORIZAR'), 'data' => $session->get('filtroCotizacionEstadoAutorizado')))                
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);        
        $arrBotonAprobar = array('label' => 'Aprobar', 'disabled' => true);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonOtroActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonOtroEliminar = array('label' => 'Eliminar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;            
            $arrBotonAprobar['disabled'] = false;            
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonOtroEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonOtroActualizar['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
        if($ar->getEstadoAprobado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonAprobar['disabled'] = true;            
        } 
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)                 
                    ->add('BtnAprobar', SubmitType::class, $arrBotonAprobar)                 
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                    ->add('BtnDetalleActualizar', SubmitType::class, $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', SubmitType::class, $arrBotonDetalleEliminar)
                    ->add('BtnOtroActualizar', SubmitType::class, $arrBotonOtroActualizar)
                    ->add('BtnOtroEliminar', SubmitType::class, $arrBotonOtroEliminar)
                    ->getForm();
        return $form;
    }

    private function generarExcel() {
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
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
                    ->setCellValue('D1', 'VENCE')                
                    ->setCellValue('E1', 'CLIENTE')
                    ->setCellValue('F1', 'PROSPECTO')
                    ->setCellValue('G1', 'SECTOR')
                    ->setCellValue('H1', 'HORAS')
                    ->setCellValue('I1', 'H.DIURNAS')
                    ->setCellValue('J1', 'H.NOCTURNAS')
                    ->setCellValue('K1', 'P.MINIMO')
                    ->setCellValue('L1', 'P.AJUSTADO')
                    ->setCellValue('M1', 'TOTAL');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arCotizaciones = new \Brasa\TurnoBundle\Entity\TurCotizacion();
        $arCotizaciones = $query->getResult();

        foreach ($arCotizaciones as $arCotizacion) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCotizacion->getCodigoCotizacionPk())
                    ->setCellValue('B' . $i, $arCotizacion->getNumero())
                    ->setCellValue('C' . $i, $arCotizacion->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arCotizacion->getFechaVence()->format('Y/m/d'))
                    ->setCellValue('G' . $i, $arCotizacion->getSectorRel()->getNombre())
                    ->setCellValue('H' . $i, $arCotizacion->getHoras())
                    ->setCellValue('I' . $i, $arCotizacion->getHorasDiurnas())
                    ->setCellValue('J' . $i, $arCotizacion->getHorasNocturnas())
                    ->setCellValue('K' . $i, $arCotizacion->getVrTotalPrecioMinimo())
                    ->setCellValue('L' . $i, $arCotizacion->getVrTotalPrecioAjustado())
                    ->setCellValue('M' . $i, $arCotizacion->getVrTotal());
            if($arCotizacion->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('E' . $i, $arCotizacion->getClienteRel()->getNombreCorto());
            }
            if($arCotizacion->getProspectoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F' . $i, $arCotizacion->getProspectoRel()->getNombreCorto());
            }            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Cotizaciones');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Cotizaciones.xlsx"');
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

    private function actualizarDetalle($arrControles, $codigoCotizacion) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arCotizacionDetalle = new \Brasa\TurnoBundle\Entity\TurCotizacionDetalle();
                $arCotizacionDetalle = $em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->find($intCodigo);
                $arCotizacionDetalle->setCantidad($arrControles['TxtCantidad'.$intCodigo]);
                if(isset($arrControles['TxtProyecto'.$intCodigo])) {
                    if($arrControles['TxtProyecto'.$intCodigo] != '') {
                        $arProyecto = new \Brasa\TurnoBundle\Entity\TurProyecto();
                        $arProyecto = $em->getRepository('BrasaTurnoBundle:TurProyecto')->find($arrControles['TxtProyecto'.$intCodigo]);
                        if($arProyecto) {
                            $arCotizacionDetalle->setProyectoRel($arProyecto);
                        }
                    }                    
                }               
                if($arrControles['TxtValorAjustado'.$intCodigo] != '') {
                    $arCotizacionDetalle->setVrPrecioAjustado($arrControles['TxtValorAjustado'.$intCodigo]);                
                }                     
                if(isset($arrControles['chkLunes'.$intCodigo])) {
                    $arCotizacionDetalle->setLunes(1);
                } else {
                    $arCotizacionDetalle->setLunes(0);
                }
                if(isset($arrControles['chkMartes'.$intCodigo])) {
                    $arCotizacionDetalle->setMartes(1);
                } else {
                    $arCotizacionDetalle->setMartes(0);
                }
                if(isset($arrControles['chkMiercoles'.$intCodigo])) {
                    $arCotizacionDetalle->setMiercoles(1);
                } else {
                    $arCotizacionDetalle->setMiercoles(0);
                }
                if(isset($arrControles['chkJueves'.$intCodigo])) {
                    $arCotizacionDetalle->setJueves(1);
                } else {
                    $arCotizacionDetalle->setJueves(0);
                }
                if(isset($arrControles['chkViernes'.$intCodigo])) {
                    $arCotizacionDetalle->setViernes(1);
                } else {
                    $arCotizacionDetalle->setViernes(0);
                }
                if(isset($arrControles['chkSabado'.$intCodigo])) {
                    $arCotizacionDetalle->setSabado(1);
                } else {
                    $arCotizacionDetalle->setSabado(0);
                }
                if(isset($arrControles['chkDomingo'.$intCodigo])) {
                    $arCotizacionDetalle->setDomingo(1);
                } else {
                    $arCotizacionDetalle->setDomingo(0);
                }
                if(isset($arrControles['chkFestivo'.$intCodigo])) {
                    $arCotizacionDetalle->setFestivo(1);
                } else {
                    $arCotizacionDetalle->setFestivo(0);
                }                    
                $em->persist($arCotizacionDetalle);
            }
            $em->flush();
            $em->getRepository('BrasaTurnoBundle:TurCotizacion')->liquidar($codigoCotizacion);                   
        }
    }
    
}