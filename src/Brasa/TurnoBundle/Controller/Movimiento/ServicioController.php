<?php
namespace Brasa\TurnoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurServicioType;
use Brasa\TurnoBundle\Form\Type\TurServicioDetalleType;
use Brasa\TurnoBundle\Form\Type\TurServicioDetalleCompuestoType;

class ServicioController extends Controller
{
    var $strListaDql = "";
    var $strListaDqlRecurso = "";
    var $codigoRecurso = "";
    var $nombreRecurso = "";
    var $codigoCentroCosto = "";    
    
    /**
     * @Route("/tur/movimiento/servicio", name="brs_tur_movimiento_servicio")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 26, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();        
        if ($form->isValid()) {               
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurServicio')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio'));                 
                
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

        $arServicios = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:lista.html.twig', array(
            'arServicios' => $arServicios,            
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/servicio/nuevo/{codigoServicio}", name="brs_tur_movimiento_servicio_nuevo")
     */
    public function nuevoAction(Request $request, $codigoServicio) {
        $em = $this->getDoctrine()->getManager();
        $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
        if($codigoServicio != 0) {
            $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio);
        }
        $form = $this->createForm(TurServicioType::class, $arServicio);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arServicio = $form->getData();            
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {                
                $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $fecha = date('Y/m/j');
                    $nuevafecha = strtotime ( '-1 month' , strtotime ($fecha) ) ;
                    $nuevafecha = date ( 'Y/m/' , $nuevafecha );                    
                    $dateFechaGeneracion = date_create($nuevafecha . '01');
                    $arServicio->setFechaGeneracion($dateFechaGeneracion);
                    $arServicio->setClienteRel($arCliente);
                    $arUsuario = $this->getUser();
                    $arServicio->setUsuario($arUsuario->getUserName());
                    $em->persist($arServicio);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_nuevo', array('codigoServicio' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle', array('codigoServicio' => $arServicio->getCodigoServicioPk())));
                    }                       
                } else {
                    $objMensaje->Mensaje("error", "El cliente no existe", $this);
                }                             
            }            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:nuevo.html.twig', array(
            'arServicio' => $arServicio,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/servicio/detalle/{codigoServicio}", name="brs_tur_movimiento_servicio_detalle")
     */    
    public function detalleAction(Request $request, $codigoServicio) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
        $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio);
        $form = $this->formularioDetalle($arServicio);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoServicio);
                if($arServicio->getEstadoAutorizado() == 0) {                    
                    $arServicio->setEstadoAutorizado(1);
                    $em->persist($arServicio);
                    $em->flush();                                           
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle', array('codigoServicio' => $codigoServicio)));                
            }    
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arServicio->getEstadoAutorizado() == 1) {
                    $arServicio->setEstadoAutorizado(0);
                    $em->persist($arServicio);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle', array('codigoServicio' => $codigoServicio)));                
                }
            }    
            if($form->get('BtnCerrar')->isClicked()) {            
                if($arServicio->getEstadoAutorizado() == 1) {
                    $arServicio->setEstadoCerrado(1);
                    $em->persist($arServicio);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle', array('codigoServicio' => $codigoServicio)));                
                }
            }            
            if($form->get('BtnDetalleActualizar')->isClicked()) {                
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoServicio);                                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle', array('codigoServicio' => $codigoServicio)));
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->eliminarSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($codigoServicio);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle', array('codigoServicio' => $codigoServicio)));
            }
            if($form->get('BtnDetalleCerrar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->cerrarSeleccionados($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle', array('codigoServicio' => $codigoServicio)));
            }  
            if($form->get('BtnDetalleAbrir')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionarCerrado');
                $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->AbrirSeleccionados($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle', array('codigoServicio' => $codigoServicio)));
            }            
            if($form->get('BtnDetalleMarcar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->marcarSeleccionados($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle', array('codigoServicio' => $codigoServicio)));
            } 
            if($form->get('BtnDetalleAjuste')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->ajustarSeleccionados($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle', array('codigoServicio' => $codigoServicio)));
            }            
            if($form->get('BtnDetalleConceptoActualizar')->isClicked()) {   
                $arrControles = $request->request->All();
                $this->actualizarDetalleConcepto($arrControles, $codigoServicio);                                                 
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle', array('codigoServicio' => $codigoServicio)));
            }            
            if($form->get('BtnDetalleConceptoEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionarServicioConcepto');
                $em->getRepository('BrasaTurnoBundle:TurServicioDetalleConcepto')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($codigoServicio);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle', array('codigoServicio' => $codigoServicio)));
            }
            
            if($form->get('BtnImprimir')->isClicked()) {
                /*if($arServicio->getEstadoAutorizado() == 1) {
                    $objServicio = new \Brasa\TurnoBundle\Formatos\FormatoServicio();
                    $objServicio->Generar($this, $codigoServicio);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir una cotizacion sin estar autorizada", $this);
                }
                 * 
                 */
            }            
        }

        $dql = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->listaDql($codigoServicio, "0");       
        $arServicioDetalle = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 150);
        $dql = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->listaDql($codigoServicio, "1");       
        $arServicioDetalleCerrado = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 150);        
        $dql = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleConcepto')->listaDql($codigoServicio);       
        $arServicioDetalleConcepto = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 150);        
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:detalle.html.twig', array(
                    'arServicio' => $arServicio,
                    'arServicioDetalle' => $arServicioDetalle,
                    'arServicioDetalleCerrado' => $arServicioDetalleCerrado,
                    'arServicioDetalleConceptos' => $arServicioDetalleConcepto,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/tur/movimiento/servicio/compuesto/detalle/{codigoServicioDetalle}", name="brs_tur_movimiento_servicio_compuesto_detalle")
     */    
    public function detalleCompuestoAction(Request $request, $codigoServicioDetalle) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
        $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigoServicioDetalle);
        $form = $this->formularioCompuestoDetalle($arServicioDetalle);
        $form->handleRequest($request);
        if($form->isValid()) {              
            if($form->get('BtnDetalleActualizar')->isClicked()) {                
                $arrControles = $request->request->All();
                $this->actualizarDetalleCompuesto($arrControles, $codigoServicioDetalle, $arServicioDetalle->getCodigoServicioFk());                                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_compuesto_detalle', array('codigoServicioDetalle' => $codigoServicioDetalle)));
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurServicioDetalleCompuesto')->eliminarSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->liquidar($codigoServicioDetalle);
                $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($arServicioDetalle->getCodigoServicioFk());
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_compuesto_detalle', array('codigoServicioDetalle' => $codigoServicioDetalle)));
            }                                 
        }

        $dql = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleCompuesto')->listaDql($codigoServicioDetalle);       
        $arServiciosDetalleCompuesto = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 150);
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:detalleCompuesto.html.twig', array(
                    'arServicioDetalle' => $arServicioDetalle,
                    'arServiciosDetalleCompuesto' => $arServiciosDetalleCompuesto,
                    'form' => $form->createView()
                    ));
    }
    
    /**
     * @Route("/tur/movimiento/servicio/compuesto/detalle/nuevo/{codigoServicioDetalle}/{codigoServicioDetalleCompuesto}", name="brs_tur_movimiento_servicio_compuesto_detalle_nuevo")
     */    
    public function detalleCompuestoNuevoAction(Request $request, $codigoServicioDetalle, $codigoServicioDetalleCompuesto = 0) {
        $em = $this->getDoctrine()->getManager();
        $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
        $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigoServicioDetalle);
        $arServicioDetalleCompuesto = new \Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto();
        if($codigoServicioDetalleCompuesto != 0) {
            $arServicioDetalleCompuesto = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleCompuesto')->find($codigoServicioDetalleCompuesto);
        } else {
            $arPeriodo = new \Brasa\TurnoBundle\Entity\TurPeriodo();
            $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(1);
            $arServicioDetalleCompuesto->setLunes(true);
            $arServicioDetalleCompuesto->setMartes(true);
            $arServicioDetalleCompuesto->setMiercoles(true);
            $arServicioDetalleCompuesto->setJueves(true);
            $arServicioDetalleCompuesto->setViernes(true);
            $arServicioDetalleCompuesto->setSabado(true);
            $arServicioDetalleCompuesto->setDomingo(true);
            $arServicioDetalleCompuesto->setFestivo(true);
            $arServicioDetalleCompuesto->setCantidad(1);
            $arServicioDetalleCompuesto->setServicioDetalleRel($arServicioDetalle);
            $arServicioDetalleCompuesto->setPeriodoRel($arPeriodo);           
        }
        $form = $this->createForm(new TurServicioDetalleCompuestoType, $arServicioDetalleCompuesto);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arServicioDetalleCompuesto = $form->getData();
            $em->persist($arServicioDetalleCompuesto);
            $em->flush();
            $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->liquidar($codigoServicioDetalle);
            $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($arServicioDetalle->getCodigoServicioFk());
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:detalleCompuestoNuevo.html.twig', array(
            'arServicioDetalle' => $arServicioDetalle,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/tur/movimiento/servicio/detalle/concepto/nuevo/{codigoServicio}", name="brs_tur_movimiento_servicio_detalle_concepto_nuevo")
     */
    public function detalleConceptoNuevoAction(Request $request, $codigoServicio) {
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
        $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio);        
        $form = $this->formularioDetalleOtroNuevo($arServicio->getCodigoClienteFk());
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                $arPuesto = $form->get('puestoRel')->getData();
                if(count($arrSeleccionados) > 0) {    
                    foreach ($arrSeleccionados AS $codigo) {
                        $arConceptoServicio = new \Brasa\TurnoBundle\Entity\TurConceptoServicio();
                        $arConceptoServicio = $em->getRepository('BrasaTurnoBundle:TurConceptoServicio')->find($codigo);
                        $cantidad = $arrControles['TxtCantidad' . $codigo];
                        $precio = $arrControles['TxtPrecio' . $codigo];                        
                        $subtotal = $cantidad * $precio;
                        $subtotalAIU = $subtotal * $arConceptoServicio->getPorBaseIva()/100;
                        $iva = ($subtotalAIU * $arConceptoServicio->getPorIva())/100;
                        $total = $subtotal + $iva;
                        $arServicioDetalleConcepto = new \Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto();
                        $arServicioDetalleConcepto->setServicioRel($arServicio);                        
                        $arServicioDetalleConcepto->setConceptoServicioRel($arConceptoServicio);
                        $arServicioDetalleConcepto->setPuestoRel($arPuesto);
                        $arServicioDetalleConcepto->setPorIva($arConceptoServicio->getPorIva());
                        $arServicioDetalleConcepto->setPorBaseIva($arConceptoServicio->getPorBaseIva());
                        $arServicioDetalleConcepto->setCantidad($cantidad);
                        $arServicioDetalleConcepto->setPrecio($precio);
                        $arServicioDetalleConcepto->setSubtotal($subtotal);                        
                        $arServicioDetalleConcepto->setIva($iva);
                        $arServicioDetalleConcepto->setTotal($total);
                        $em->persist($arServicioDetalleConcepto);  
                    }
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($codigoServicio);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            } 
            if ($form->get('BtnFiltrar')->isClicked()) {            
                $this->filtrarDetalleNuevo($form);
            }
        }
        
        $arConceptosServicio = new \Brasa\TurnoBundle\Entity\TurConceptoServicio();
        $arConceptosServicio = $em->getRepository('BrasaTurnoBundle:TurConceptoServicio')->findBy(array('tipo' => 2));        
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:detalleOtroNuevo.html.twig', array(
            'arConceptosServicio' => $arConceptosServicio,
            'form' => $form->createView()));
    }     
    
    /**
     * @Route("/tur/movimiento/servicio/detalle/nuevo/{codigoServicio}/{codigoServicioDetalle}", name="brs_tur_movimiento_servicio_detalle_nuevo")
     */    
    public function detalleNuevoAction(Request $request, $codigoServicio, $codigoServicioDetalle = 0) {
        $em = $this->getDoctrine()->getManager();
        $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
        $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio);
        $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
        if($codigoServicioDetalle != 0) {
            $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigoServicioDetalle);
        } else {
            $arPeriodo = new \Brasa\TurnoBundle\Entity\TurPeriodo();
            $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(1);
            $arServicioDetalle->setLunes(true);
            $arServicioDetalle->setMartes(true);
            $arServicioDetalle->setMiercoles(true);
            $arServicioDetalle->setJueves(true);
            $arServicioDetalle->setViernes(true);
            $arServicioDetalle->setSabado(true);
            $arServicioDetalle->setDomingo(true);
            $arServicioDetalle->setFestivo(true);
            $arServicioDetalle->setCantidad(1);
            $arServicioDetalle->setFechaIniciaPlantilla(new \DateTime('now'));
            $arServicioDetalle->setServicioRel($arServicio);
            $arServicioDetalle->setPeriodoRel($arPeriodo);
            $arServicioDetalle->setFechaDesde(new \DateTime('now'));
            $arServicioDetalle->setFechaHasta(new \DateTime('now'));            
        }
        $form = $this->createForm(new TurServicioDetalleType, $arServicioDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arServicioDetalle = $form->getData();
            $em->persist($arServicioDetalle);
            $em->flush();

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle_nuevo', array('codigoServicio' => $codigoServicio, 'codigoServicioDetalle' => 0 )));
            } else {
                $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($codigoServicio);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:detalleNuevo.html.twig', array(
            'arServicio' => $arServicio,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/servicio/detalle/cotizacion/nuevo/{codigoServicio}/{codigoServicioDetalle}", name="brs_tur_movimiento_servicio_detalle_cotizacion_nuevo")
     */    
    public function detalleNuevoCotizacionAction(Request $request, $codigoServicio, $codigoServicioDetalle = 0) {
        $em = $this->getDoctrine()->getManager();
        $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
        $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio);
        $form = $this->createFormBuilder()
            ->add('prospectoRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurProspecto',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombreCorto', 'ASC');},
                'choice_label' => 'nombreCorto',
                'required' => false))                 
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar',))
            ->getForm();
        $form->handleRequest($request);
        $codigoProspecto = "";
        if ($form->isValid()) {
            if ($form->get('BtnFiltrar')->isClicked()) {
                $arProspecto = $form->get('prospectoRel')->getData();
                if($arProspecto) {
                    $codigoProspecto = $arProspecto->getCodigoProspectoPk();
                }                
            }
            if ($form->get('BtnGuardar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arCotizacion = new \Brasa\TurnoBundle\Entity\TurCotizacion();
                        $arCotizacion = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->find($codigo);                                                
                        $arCotizacionDetalles = new \Brasa\TurnoBundle\Entity\TurCotizacionDetalle();
                        $arCotizacionDetalles = $em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->findBy(array('codigoCotizacionFk' => $arCotizacion->getCodigoCotizacionPk()));
                        foreach($arCotizacionDetalles as $arCotizacionDetalle) {
                            $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                            $arServicioDetalle->setServicioRel($arServicio);
                            $arServicioDetalle->setProyectoRel($arCotizacionDetalle->getProyectoRel());
                            $arServicioDetalle->setModalidadServicioRel($arCotizacionDetalle->getModalidadServicioRel());
                            $arServicioDetalle->setPeriodoRel($arCotizacionDetalle->getPeriodoRel());
                            $arServicioDetalle->setConceptoServicioRel($arCotizacionDetalle->getConceptoServicioRel());
                            $arServicioDetalle->setDias($arCotizacionDetalle->getDias());
                            $arServicioDetalle->setLunes($arCotizacionDetalle->getLunes());
                            $arServicioDetalle->setMartes($arCotizacionDetalle->getMartes());
                            $arServicioDetalle->setMiercoles($arCotizacionDetalle->getMiercoles());
                            $arServicioDetalle->setJueves($arCotizacionDetalle->getJueves());
                            $arServicioDetalle->setViernes($arCotizacionDetalle->getViernes());
                            $arServicioDetalle->setSabado($arCotizacionDetalle->getSabado());
                            $arServicioDetalle->setDomingo($arCotizacionDetalle->getDomingo());
                            $arServicioDetalle->setFestivo($arCotizacionDetalle->getFestivo());                            
                            $arServicioDetalle->setCantidad($arCotizacionDetalle->getCantidad());
                            $arServicioDetalle->setFechaIniciaPlantilla(new \DateTime('now'));
                            $arServicioDetalle->setFechaDesde($arCotizacionDetalle->getFechaDesde());
                            $arServicioDetalle->setFechaHasta($arCotizacionDetalle->getFechaHasta());
                            $arServicioDetalle->setVrPrecioAjustado($arCotizacionDetalle->getVrPrecioAjustado());
                            $arServicioDetalle->setLiquidarDiasReales($arCotizacionDetalle->getLiquidarDiasReales());
                            $em->persist($arServicioDetalle);
                        }                       
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($codigoServicio);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }            
        }
        $arCotizaciones = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->pendientes($arServicio->getCodigoClienteFk(), $codigoProspecto);
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:detalleNuevoCotizacion.html.twig', array(
            'arServicio' => $arServicio,
            'arCotizaciones' => $arCotizaciones,
            'form' => $form->createView()));
    }           
    
    /**
     * @Route("/tur/movimiento/servicio/detalle/recurso/{codigoServicioDetalle}", name="brs_tur_movimiento_servicio_detalle_recurso")
     */     
    public function detalleRecursoAction(Request $request, $codigoServicioDetalle = 0) {
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $em = $this->getDoctrine()->getManager();
        $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
        $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigoServicioDetalle);        
        $arServicio = $arServicioDetalle->getServicioRel();
        $form = $this->formularioRecurso($arServicioDetalle->getDiasSecuencia(), $arServicioDetalle->getFechaIniciaPlantilla(), $arServicioDetalle->getPlantillaRel());
        $form->handleRequest($request);
        if ($form->isValid()) {
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->eliminarSeleccionados($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle_recurso', array('codigoServicioDetalle' => $codigoServicioDetalle)));                                
            } 
            if($form->get('BtnDetalleActualizar')->isClicked()) {                
                $arrControles = $request->request->All();
                $this->actualizarDetalleRecurso($arrControles);                                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle_recurso', array('codigoServicioDetalle' => $codigoServicioDetalle)));                                
            }   
            if($form->get('BtnPlantillaNuevo')->isClicked()) {   
                $arServicioDetallePlantilla = new \Brasa\TurnoBundle\Entity\TurServicioDetallePlantilla();
                $arServicioDetallePlantilla->setServicioDetalleRel($arServicioDetalle);
                $em->persist($arServicioDetallePlantilla);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle_recurso', array('codigoServicioDetalle' => $codigoServicioDetalle)));                                
            }
            if($form->get('BtnPlantillaActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $controles = $request->request->get('form');
                $intDiasSecuencia = $controles['TxtDiasSecuencia'];
                $arServicioDetalle->setDiasSecuencia($intDiasSecuencia);
                $em->persist($arServicioDetalle);
                $em->flush();
                $this->actualizarDetallePlantilla($arrControles);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle_recurso', array('codigoServicioDetalle' => $codigoServicioDetalle)));                                
            }
            if($form->get('BtnPlantillaEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionarPlantilla');
                $em->getRepository('BrasaTurnoBundle:TurServicioDetallePlantilla')->eliminar($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle_recurso', array('codigoServicioDetalle' => $codigoServicioDetalle)));                                
            }    
            if($form->get('BtnGuardarServicioDetalle')->isClicked()) {   
                $intDiasSecuencia = $form->get('TxtDiasSecuencia')->getData();     
                $fechaIniciaPlantilla = $form->get('fechaIniciaPlantilla')->getData();
                $arServicioDetalle->setDiasSecuencia($intDiasSecuencia);
                $arServicioDetalle->setFechaIniciaPlantilla($fechaIniciaPlantilla);
                $arServicioDetalle->setPlantillaRel($form->get('plantillaRel')->getData());
                $em->persist($arServicioDetalle);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_movimiento_servicio_detalle_recurso', array('codigoServicioDetalle' => $codigoServicioDetalle)));                                
            }            
        }
        $arPlantillaDetalles = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
        if($arServicioDetalle->getPlantillaRel()) {            
            $arPlantillaDetalles = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->findBy(array('codigoPlantillaFk' => $arServicioDetalle->getCodigoPlantillaFk()));
        }        
        $strLista = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->listaDql($codigoServicioDetalle);
        $strListaPlantilla = $em->getRepository('BrasaTurnoBundle:TurServicioDetallePlantilla')->listaDql($codigoServicioDetalle);
        $arServicioDetalleRecursos = $paginator->paginate($em->createQuery($strLista), $request->query->get('page', 1), 20);
        $arServicioDetallePlantilla = $paginator->paginate($em->createQuery($strListaPlantilla), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:detalleRecurso.html.twig', array(
            'arServicio' => $arServicio,
            'arServicioDetalle' => $arServicioDetalle,
            'arServicioDetalleRecursos' => $arServicioDetalleRecursos,
            'arServicioDetallePlantilla' => $arServicioDetallePlantilla,
            'arPlantillaDetalle' => $arPlantillaDetalles,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/tur/movimiento/servicio/detalle/recurso/nuevo/{codigoServicioDetalle}", name="brs_tur_movimiento_servicio_detalle_recurso_nuevo")
     */     
    public function detalleRecursoNuevoAction(Request $request, $codigoServicioDetalle = 0) {
        $objMensaje = $this->get('mensajes_brasa');
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
        $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigoServicioDetalle);        
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $this->nombreRecurso))
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo','data' => $this->codigoRecurso))                            
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))                
                
            ->getForm();        
        $form->handleRequest($request);
        $this->listaRecurso();
        if ($form->isValid()) {    
            if($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigo) {
                        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigo);
                        $arServicioDetalleRecurso = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
                        $arServicioDetalleRecurso->setServicioDetalleRel($arServicioDetalle);
                        $arServicioDetalleRecurso->setRecursoRel($arRecurso);
                        $em->persist($arServicioDetalleRecurso);
                    }
                    $em->flush();
                } 
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarRecurso($form);
                $this->listaRecurso();
            }                        
        }
        $arRecurso = $paginator->paginate($em->createQuery($this->strListaDqlRecurso), $request->query->get('page', 1), 100);
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:detalleRecursoNuevo.html.twig', array(
            'arServicioDetalle' => $arServicioDetalle,
            'arRecursos' => $arRecurso,
            'form' => $form->createView()));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;     
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurServicio')->listaDQL(
                $session->get('filtroServicioCodigo'), 
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroServicioEstadoAutorizado'),
                $session->get('filtroServicioEstadoCerrado'));
    }    

    private function listaRecurso() {        
        $em = $this->getDoctrine()->getManager();
        $this->strListaDqlRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->listaDQL(
                $this->nombreRecurso,                
                $this->codigoRecurso,
                ""
                ); 
    }     
    
    private function filtrar ($form) {        
        $session = new Session;  
        $session->set('filtroServicioCodigo', $form->get('TxtCodigo')->getData());
        $session->set('filtroServicioEstadoAutorizado', $form->get('estadoAutorizado')->getData());
        $session->set('filtroServicioEstadoCerrado', $form->get('estadoCerrado')->getData());
        $session->set('filtroNit', $form->get('TxtNit')->getData());
    }

    private function filtrarRecurso($form) {       
        $this->nombreRecurso = $form->get('TxtNombre')->getData();    
        $this->codigoRecurso = $form->get('TxtCodigo')->getData(); 
    }    
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
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
            ->add('TxtNit', TextType::class, array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', TextType::class, array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroServicioCodigo'))) 
            ->add('estadoAutorizado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'AUTORIZADO', '0' => 'SIN AUTORIZAR'), 'data' => $session->get('filtroServicioEstadoAutorizado')))
            ->add('estadoCerrado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'CERRADO', '0' => 'SIN CERRAR'), 'data' => $session->get('filtroServicioEstadoCerrado')))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);  
        $arrBotonCerrar = array('label' => 'Cerrar', 'disabled' => true);          
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);        
        $arrBotonDetalleCerrar = array('label' => 'Cerrar', 'disabled' => false);        
        $arrBotonDetalleAbrir = array('label' => 'Abrir', 'disabled' => false);        
        $arrBotonDetalleMarcar = array('label' => 'Marcar', 'disabled' => false);        
        $arrBotonDetalleAjuste = array('label' => 'Ajuste', 'disabled' => false);        
        $arrBotonDetalleConceptoActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonDetalleConceptoEliminar = array('label' => 'Eliminar', 'disabled' => false);          
        
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonCerrar['disabled'] = false;  
            $arrBotonDetalleConceptoActualizar['disabled'] = true;
            $arrBotonDetalleConceptoEliminar['disabled'] = true;            
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
 
        if($ar->getEstadoCerrado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;                        
            $arrBotonCerrar['disabled'] = true;
        }         
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)                 
                    ->add('BtnCerrar', SubmitType::class, $arrBotonCerrar)                                     
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                    ->add('BtnDetalleActualizar', SubmitType::class, $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', SubmitType::class, $arrBotonDetalleEliminar)
                    ->add('BtnDetalleCerrar', SubmitType::class, $arrBotonDetalleCerrar)    
                    ->add('BtnDetalleAbrir', SubmitType::class, $arrBotonDetalleAbrir)    
                    ->add('BtnDetalleMarcar', SubmitType::class, $arrBotonDetalleMarcar)
                    ->add('BtnDetalleAjuste', SubmitType::class, $arrBotonDetalleAjuste)
                    ->add('BtnDetalleConceptoActualizar', SubmitType::class, $arrBotonDetalleConceptoActualizar)
                    ->add('BtnDetalleConceptoEliminar', SubmitType::class, $arrBotonDetalleConceptoEliminar)                                    
                    ->getForm();
        return $form;
    }

    private function formularioCompuestoDetalle($ar) {        
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);                
        
        if($ar->getServicioRel()->getEstadoAutorizado() == 1) {                            
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
        }
          
        $form = $this->createFormBuilder()
                    ->add('BtnDetalleActualizar', SubmitType::class, $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', SubmitType::class, $arrBotonDetalleEliminar)
                    ->getForm();
        return $form;
    }    
    
    private function formularioRecurso($intDiasSecuencia, $fechaIniciaPlantilla, $arPlantilla) {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $form = $this->createFormBuilder()      
            ->add('plantillaRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurPlantilla',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'data' => $arPlantilla,
                'required' => false))                 
            ->add('TxtDiasSecuencia', TextType::class, array('label'  => 'Codigo','data' => $intDiasSecuencia)) 
            ->add('fechaIniciaPlantilla', DateType::class, array('data'  => $fechaIniciaPlantilla, 'format' => 'y MMMM d'))
            ->add('BtnDetalleEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnDetalleActualizar', SubmitType::class, array('label'  => 'Actualizar',))            
            ->add('BtnPlantillaNuevo', SubmitType::class, array('label'  => 'Nuevo',))                
            ->add('BtnPlantillaEliminar', SubmitType::class, array('label'  => 'Eliminar',))                
            ->add('BtnPlantillaActualizar', SubmitType::class, array('label'  => 'Actualizar',))                
            ->add('BtnGuardarServicioDetalle', SubmitType::class, array('label'  => 'Guardar',))                            
            ->getForm();
        return $form;
    }    
    
    private function formularioDetalleOtroNuevo($codigoCliente) {
        $form = $this->createFormBuilder()
            ->add('puestoRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurPuesto',
                'query_builder' => function (EntityRepository $er) use($codigoCliente) {
                    return $er->createQueryBuilder('p')
                    ->where('p.codigoClienteFk = :cliente')
                    ->setParameter('cliente', $codigoCliente)
                    ->orderBy('p.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                 
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'K'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        }     
        for($col = 'D'; $col !== 'K'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
               
        $objPHPExcel->setActiveSheetIndex(0)                    
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NIT')
                    ->setCellValue('C1', 'CLIENTE')
                    ->setCellValue('D1', 'SECTOR') 
                    ->setCellValue('E1', 'SECTOR_COM')
                    ->setCellValue('F1', 'AUT')
                    ->setCellValue('G1', 'CER')
                    ->setCellValue('H1', 'HORAS')
                    ->setCellValue('I1', 'H.DIURNAS')
                    ->setCellValue('J1', 'H.NOCTURNAS')
                    ->setCellValue('K1', 'VALOR');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arServicios = new \Brasa\TurnoBundle\Entity\TurServicio();
        $arServicios = $query->getResult();

        foreach ($arServicios as $arServicio) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arServicio->getCodigoServicioPk())  
                    ->setCellValue('B' . $i, $arServicio->getClienteRel()->getNit())
                    ->setCellValue('C' . $i, $arServicio->getClienteRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arServicio->getSectorRel()->getNombre())  
                    ->setCellValue('F' . $i, $objFunciones->devuelveBoolean($arServicio->getEstadoAutorizado()))
                    ->setCellValue('G' . $i, $objFunciones->devuelveBoolean($arServicio->getEstadoCerrado()))
                    ->setCellValue('H' . $i, $arServicio->getHoras())
                    ->setCellValue('I' . $i, $arServicio->getHorasDiurnas())
                    ->setCellValue('J' . $i, $arServicio->getHorasNocturnas())
                    ->setCellValue('K' . $i, $arServicio->getVrTotal());
            if($arServicio->getClienteRel()->getCodigoSectorComercialFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $i, $arServicio->getClienteRel()->getSectorComercialRel()->getNombre());
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Servicios');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Servicios.xlsx"');
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
    
    private function actualizarDetalle($arrControles, $codigoServicio) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($intCodigo);                
                if($arServicioDetalle->getCompuesto() == 0) {
                    if($arrControles['TxtValorAjustado'.$intCodigo] != '') {
                        $arServicioDetalle->setVrPrecioAjustado($arrControles['TxtValorAjustado'.$intCodigo]);                
                    }                                               
                    $em->persist($arServicioDetalle);                    
                }
            }
            $em->flush();                
            $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($codigoServicio);             
        }       
    }
    
    private function actualizarDetalleCompuesto($arrControles, $codigoServicioDetalle, $codigoServicio) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arServicioDetalleCompuesto = new \Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto();
                $arServicioDetalleCompuesto = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleCompuesto')->find($intCodigo);                
                if($arrControles['TxtValorAjustado'.$intCodigo] != '') {
                    $arServicioDetalleCompuesto->setVrPrecioAjustado($arrControles['TxtValorAjustado'.$intCodigo]);                
                }                              
                $em->persist($arServicioDetalleCompuesto);
            }
            $em->flush();                
            $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->liquidar($codigoServicioDetalle);
            $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($codigoServicio);
        }       
    }    
    
    private function actualizarDetalleRecurso($arrControles) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        foreach ($arrControles['LblCodigo'] as $intCodigo) {
            $arServicioDetalleRecurso = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
            $arServicioDetalleRecurso = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->find($intCodigo);
            $arServicioDetalleRecurso->setPosicion($arrControles['TxtPosicion'.$intCodigo]);
            $em->persist($arServicioDetalleRecurso);
        }
        $em->flush();                        
    }

    private function actualizarDetallePlantilla($arrControles) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigoDetallePlantilla'])) {
            foreach ($arrControles['LblCodigoDetallePlantilla'] as $intCodigo) {
                $arServicioDetallePlantilla = new \Brasa\TurnoBundle\Entity\TurServicioDetallePlantilla();
                $arServicioDetallePlantilla = $em->getRepository('BrasaTurnoBundle:TurServicioDetallePlantilla')->find($intCodigo);
                if ($arrControles['TxtPosicion' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setPosicion($arrControles['TxtPosicion' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setPosicion(0);
                }
                if ($arrControles['TxtDia1' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia1($arrControles['TxtDia1' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia1(null);
                }
                if ($arrControles['TxtDia2' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia2($arrControles['TxtDia2' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia2(null);
                }
                if ($arrControles['TxtDia3' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia3($arrControles['TxtDia3' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia3(null);
                }
                if ($arrControles['TxtDia4' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia4($arrControles['TxtDia4' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia4(null);
                }
                if ($arrControles['TxtDia5' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia5($arrControles['TxtDia5' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia5(null);
                }
                if ($arrControles['TxtDia6' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia6($arrControles['TxtDia6' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia6(null);
                }
                if ($arrControles['TxtDia7' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia7($arrControles['TxtDia7' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia7(null);
                }
                if ($arrControles['TxtDia8' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia8($arrControles['TxtDia8' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia8(null);
                }
                if ($arrControles['TxtDia9' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia9($arrControles['TxtDia9' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia9(null);
                }
                if ($arrControles['TxtDia10' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia10($arrControles['TxtDia10' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia10(null);
                }
                if ($arrControles['TxtDia11' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia11($arrControles['TxtDia11' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia11(null);
                }
                if ($arrControles['TxtDia12' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia12($arrControles['TxtDia12' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia12(null);
                }
                if ($arrControles['TxtDia13' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia13($arrControles['TxtDia13' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia13(null);
                }
                if ($arrControles['TxtDia14' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia14($arrControles['TxtDia14' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia14(null);
                }
                if ($arrControles['TxtDia15' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia15($arrControles['TxtDia15' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia15(null);
                }
                if ($arrControles['TxtDia16' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia16($arrControles['TxtDia16' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia16(null);
                }
                if ($arrControles['TxtDia17' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia17($arrControles['TxtDia17' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia17(null);
                }
                if ($arrControles['TxtDia18' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia18($arrControles['TxtDia18' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia18(null);
                }
                if ($arrControles['TxtDia19' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia19($arrControles['TxtDia19' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia19(null);
                }
                if ($arrControles['TxtDia20' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia20($arrControles['TxtDia20' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia20(null);
                }
                if ($arrControles['TxtDia21' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia21($arrControles['TxtDia21' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia21(null);
                }
                if ($arrControles['TxtDia22' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia22($arrControles['TxtDia22' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia22(null);
                }
                if ($arrControles['TxtDia23' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia23($arrControles['TxtDia23' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia23(null);
                }
                if ($arrControles['TxtDia24' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia24($arrControles['TxtDia24' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia24(null);
                }
                if ($arrControles['TxtDia25' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia25($arrControles['TxtDia25' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia25(null);
                }
                if ($arrControles['TxtDia26' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia26($arrControles['TxtDia26' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia26(null);
                }
                if ($arrControles['TxtDia27' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia27($arrControles['TxtDia27' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia27(null);
                }
                if ($arrControles['TxtDia28' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia28($arrControles['TxtDia28' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia28(null);
                }
                if ($arrControles['TxtDia29' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia29($arrControles['TxtDia29' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia29(null);
                }
                if ($arrControles['TxtDia30' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia30($arrControles['TxtDia30' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia30(null);
                }
                if ($arrControles['TxtDia31' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia31($arrControles['TxtDia31' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia31(null);
                }
                if ($arrControles['TxtLunes' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setLunes($arrControles['TxtLunes' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setLunes(null);
                }
                if ($arrControles['TxtMartes' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setMartes($arrControles['TxtMartes' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setMartes(null);
                }
                if ($arrControles['TxtMiercoles' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setMiercoles($arrControles['TxtMiercoles' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setMiercoles(null);
                }
                if ($arrControles['TxtJueves' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setJueves($arrControles['TxtJueves' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setJueves(null);
                }
                if ($arrControles['TxtViernes' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setViernes($arrControles['TxtViernes' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setViernes(null);
                }
                if ($arrControles['TxtSabado' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setSabado($arrControles['TxtSabado' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setSabado(null);
                }
                if ($arrControles['TxtDomingo' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDomingo($arrControles['TxtDomingo' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDomingo(null);
                }
                if ($arrControles['TxtDomingoFestivo' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDomingoFestivo($arrControles['TxtDomingoFestivo' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDomingoFestivo(null);
                }                
                if ($arrControles['TxtFestivo' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setFestivo($arrControles['TxtFestivo' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setFestivo(null);
                }
                $em->persist($arServicioDetallePlantilla);
            }            
        }

        $em->flush();
    }   
    
    private function actualizarDetalleConcepto($arrControles, $codigoServicio) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigoConcepto'])) {
            foreach ($arrControles['LblCodigoConcepto'] as $intCodigo) {
                $arServicioDetalleConcepto = new \Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto();
                $arServicioDetalleConcepto = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleConcepto')->find($intCodigo);                
                $cantidad = $arrControles['TxtCantidadConcepto' . $intCodigo];
                $precio = $arrControles['TxtPrecioConcepto'. $intCodigo];                
                $subtotal = $cantidad * $precio;
                $subtotalAIU = $subtotal * 10/100;
                $iva = ($subtotalAIU * $arServicioDetalleConcepto->getPorIva())/100;                
                $total = $subtotal + $iva;                
                $arServicioDetalleConcepto->setCantidad($cantidad);
                $arServicioDetalleConcepto->setPrecio($precio);
                $arServicioDetalleConcepto->setSubtotal($subtotal);                        
                $arServicioDetalleConcepto->setIva($iva);
                $arServicioDetalleConcepto->setTotal($total);                                                             
                $em->persist($arServicioDetalleConcepto);
            }
            $em->flush();                
            $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($codigoServicio);            
        }        
    }        
}