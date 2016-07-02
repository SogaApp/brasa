<?php
namespace Brasa\TurnoBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurFacturaType;
use Brasa\TurnoBundle\Form\Type\TurFacturaDetalleType;
use Brasa\TurnoBundle\Form\Type\TurFacturaDetalleNuevoType;
class FacturaController extends Controller
{
    var $strListaDql = "";    
    var $boolMostrarTodo = "";
    
    /**
     * @Route("/tur/movimiento/factura", name="brs_tur_movimiento_factura")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {            
            if ($form->get('BtnContabilizar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurFactura')->contabilizar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura'));                                 
            }            
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurFactura')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura'));                                 
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
            if ($form->get('BtnInterfaz')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcelInterfaz();
            }            
        }

        $arFacturas = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:lista.html.twig', array(
            'arFacturas' => $arFacturas,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/factura/nuevo/{codigoFactura}", name="brs_tur_movimiento_factura_nuevo")
     */
    public function nuevoAction($codigoFactura) {
        $request = $this->getRequest();
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        if($codigoFactura != 0) {
            $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        }else{
            $arFactura->setFecha(new \DateTime('now'));
            $arFactura->setFechaVence(new \DateTime('now'));
        }
        $form = $this->createForm(new TurFacturaType, $arFactura);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arFactura = $form->getData();            
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {
                $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $arFactura->setClienteRel($arCliente);
                    $arClienteDireccion = new \Brasa\TurnoBundle\Entity\TurClienteDireccion();
                    if($arrControles['txtCodigoDireccion'] != '') {                        
                        $arClienteDireccion = $em->getRepository('BrasaTurnoBundle:TurClienteDireccion')->find($arrControles['txtCodigoDireccion']);                
                        if(count($arClienteDireccion) > 0) {
                            if($arClienteDireccion->getCodigoClienteFk() == $arCliente->getCodigoClientePk()) {
                                $arFactura->setClienteDireccionRel($arClienteDireccion);                                
                            } else {
                                $objMensaje->Mensaje("error", "La direccion no pertenece al cliente", $this);
                            }                            
                        }                        
                    }
                    $dateFechaVence = $objFunciones->sumarDiasFecha($arCliente->getPlazoPago(), $arFactura->getFecha());
                    $arFactura->setFechaVence($dateFechaVence); 
                    $arUsuario = $this->getUser();
                    $arFactura->setUsuario($arUsuario->getUserName());
                    $em->persist($arFactura);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_nuevo', array('codigoFactura' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $arFactura->getCodigoFacturaPk())));
                    }                    
                } else {
                    $objMensaje->Mensaje("error", "El tercero no existe", $this);
                }                             
            }            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:nuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/factura/detalle/{codigoFactura}", name="brs_tur_movimiento_factura_detalle")
     */
    public function detalleAction($codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $form = $this->formularioDetalle($arFactura);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {      
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoFactura);
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurFactura')->autorizar($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                                
            }    
            if($form->get('BtnDesAutorizar')->isClicked()) {                            
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurFactura')->desAutorizar($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                                
            }               
            if($form->get('BtnDetalleActualizar')->isClicked()) {   
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoFactura);                                 
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }
            
            if($form->get('BtnDetalleConceptoActualizar')->isClicked()) {   
                $arrControles = $request->request->All();
                $this->actualizarDetalleConcepto($arrControles, $codigoFactura);                                 
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }
            
            if($form->get('BtnAnular')->isClicked()) {                                 
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurFactura')->anular($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                
            }            
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }
            if($form->get('BtnDetalleConceptoEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionarFacturaConcepto');
                $em->getRepository('BrasaTurnoBundle:TurFacturaDetalleConcepto')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }            
            if($form->get('BtnImprimir')->isClicked()) {
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurFactura')->imprimir($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                } else {
                    if($arFactura->getFacturaTipoRel()->getTipo() == 1) {
                        $objFactura = new \Brasa\TurnoBundle\Formatos\Factura2();
                        $objFactura->Generar($this, $codigoFactura);                                            
                    } else {
                        $objNotaCredito = new \Brasa\TurnoBundle\Formatos\NotaCredito2();
                        $objNotaCredito->Generar($this, $codigoFactura);                        
                    }
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                                                
            }
            if($form->get('BtnVistaPrevia')->isClicked()) {                                
                if($arFactura->getFacturaTipoRel()->getTipo() == 1) {
                    $objFactura = new \Brasa\TurnoBundle\Formatos\Factura2();
                    $objFactura->Generar($this, $codigoFactura);                                            
                } else {
                    $objNotaCredito = new \Brasa\TurnoBundle\Formatos\NotaCredito2();
                    $objNotaCredito->Generar($this, $codigoFactura);                        
                }                                   
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                                                
            }            
        }
        
        $dql = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->listaDql($codigoFactura);       
        $arFacturaDetalle = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 150);                
        $arFacturaDetalleConceptos = new \Brasa\TurnoBundle\Entity\TurFacturaDetalleConcepto();
        $arFacturaDetalleConceptos = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalleConcepto')->findBy(array ('codigoFacturaFk' => $codigoFactura));        
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalle.html.twig', array(
                    'arFactura' => $arFactura,
                    'arFacturaDetalle' => $arFacturaDetalle,
                    'arFacturaDetalleConceptos' => $arFacturaDetalleConceptos,
                    'form' => $form->createView()
                    ));
    }
     
    /**
     * @Route("/tur/movimiento/factura/detalle/nuevo/{codigoFactura}/{codigoFacturaDetalle}", name="brs_tur_movimiento_factura_detalle_nuevo")
     */    
    public function detalleNuevoAction($codigoFactura, $codigoFacturaDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        if($codigoFacturaDetalle != 0) {
            $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($codigoFacturaDetalle);
        } else {
            $arFacturaDetalle->setFacturaRel($arFactura);
        }
        $form = $this->createForm(new TurFacturaDetalleNuevoType, $arFacturaDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arFacturaDetalle = $form->getData();   
            $arConceptoFactura = $form->get('conceptoServicioRel')->getData();
            $arFacturaDetalle->setPorIva($arConceptoFactura->getPorIva());
            $arFacturaDetalle->setPorBaseIva($arConceptoFactura->getPorBaseIva());
            $arFacturaDetalle->setFechaProgramacion($arFactura->getFecha());
            $em->persist($arFacturaDetalle);
            $em->flush();
            $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalleNuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/tur/movimiento/factura/detalle/pedido/nuevo/{codigoFactura}", name="brs_tur_movimiento_factura_detalle_pedido_nuevo")
     */
    public function detallePedidoNuevoAction($codigoFactura) {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);        
        $form = $this->formularioDetalleNuevo();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {    
                    foreach ($arrSeleccionados AS $codigo) {
                        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo);                        
                        $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                        $arFacturaDetalle->setFacturaRel($arFactura);                        
                        $arFacturaDetalle->setConceptoServicioRel($arPedidoDetalle->getConceptoServicioRel());
                        $arFacturaDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                        $arFacturaDetalle->setModalidadServicioRel($arPedidoDetalle->getModalidadServicioRel());
                        $arFacturaDetalle->setGrupoFacturacionRel($arPedidoDetalle->getGrupoFacturacionRel());
                        $arFacturaDetalle->setPedidoDetalleRel($arPedidoDetalle);
                        $arFacturaDetalle->setCantidad($arPedidoDetalle->getCantidad());
                        $arFacturaDetalle->setVrPrecio($arPedidoDetalle->getVrPrecio());
                        $arFacturaDetalle->setPorIva($arPedidoDetalle->getConceptoServicioRel()->getPorIva());
                        $arFacturaDetalle->setPorBaseIva($arPedidoDetalle->getConceptoServicioRel()->getPorBaseIva());
                        $arFacturaDetalle->setFechaProgramacion($arPedidoDetalle->getPedidoRel()->getFechaProgramacion());
                        $arFacturaDetalle->setDetalle($arPedidoDetalle->getDetalle());
                        $em->persist($arFacturaDetalle);   
                    }
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            } 
            if ($form->get('BtnFiltrar')->isClicked()) {            
                $this->filtrarDetalleNuevo($form);
            }
        }
        
        $arPedidoDetalles = $paginator->paginate($em->createQuery($this->listaDetalleNuevo($arFactura->getCodigoClienteFk())), $request->query->get('page', 1), 500);        
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalleNuevoPedido.html.twig', array(
            'arFactura' => $arFactura,
            'arPedidoDetalles' => $arPedidoDetalles,
            'boolMostrarTodo' => $form->get('mostrarTodo')->getData(),
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/factura/detalle/factura/nuevo/{codigoFactura}", name="brs_tur_movimiento_factura_detalle_factura_nuevo")
     */
    public function detalleFacturaNuevoAction($codigoFactura) {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);        
        $form = $this->createFormBuilder()            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar',))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {    
                    foreach ($arrSeleccionados AS $codigo) {
                        $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                        $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($codigo);                        
                        $arFacturaDetalleNueva = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                        $arFacturaDetalleNueva->setFacturaRel($arFactura);                        
                        $arFacturaDetalleNueva->setConceptoServicioRel($arFacturaDetalle->getConceptoServicioRel());
                        $arFacturaDetalleNueva->setPuestoRel($arFacturaDetalle->getPuestoRel());
                        $arFacturaDetalleNueva->setModalidadServicioRel($arFacturaDetalle->getModalidadServicioRel());
                        $arFacturaDetalleNueva->setGrupoFacturacionRel($arFacturaDetalle->getGrupoFacturacionRel());
                        $arFacturaDetalleNueva->setPedidoDetalleRel($arFacturaDetalle->getPedidoDetalleRel());
                        $arFacturaDetalleNueva->setFacturaDetalleRel($arFacturaDetalle);
                        $arFacturaDetalleNueva->setCantidad($arFacturaDetalle->getCantidad());
                        $arFacturaDetalleNueva->setVrPrecio($arFacturaDetalle->getVrPrecio());
                        $arFacturaDetalleNueva->setPorIva($arFacturaDetalle->getConceptoServicioRel()->getPorIva());
                        $arFacturaDetalleNueva->setPorBaseIva($arFacturaDetalle->getConceptoServicioRel()->getPorBaseIva());
                        $arFacturaDetalleNueva->setFechaProgramacion($arFacturaDetalle->getFechaProgramacion());
                        $arFacturaDetalleNueva->setDetalle($arFacturaDetalle->getDetalle());
                        $em->persist($arFacturaDetalleNueva);   
                    }
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            } 
            if ($form->get('BtnFiltrar')->isClicked()) {            
                //$this->filtrarDetalleNuevo($form);
            }
        }
        
        $dql = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->listaCliente($arFactura->getCodigoClienteFk());
        $arFacturaDetalles = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 500);        
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalleNuevoFactura.html.twig', array(
            'arFactura' => $arFactura,
            'arFacturaDetalles' => $arFacturaDetalles,            
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/tur/movimiento/factura/detalle/editar/{codigoFactura}/{codigoFacturaDetalle}", name="brs_tur_movimiento_factura_detalle_editar")
     */    
    public function detalleEditarAction($codigoFactura, $codigoFacturaDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        if($codigoFacturaDetalle != 0) {
            $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($codigoFacturaDetalle);
        } 
        $form = $this->createForm(new TurFacturaDetalleType, $arFacturaDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arFacturaDetalle = $form->getData();                        
            $em->persist($arFacturaDetalle);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalleEditar.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/tur/movimiento/factura/detalle/concepto/nuevo/{codigoFactura}", name="brs_tur_movimiento_factura_detalle_concepto_nuevo")
     */
    public function detalleConceptoNuevoAction($codigoFactura) {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);        
        $form = $this->formularioDetalleOtroNuevo();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                if(count($arrSeleccionados) > 0) {    
                    foreach ($arrSeleccionados AS $codigo) {
                        $arFacturaConcepto = new \Brasa\TurnoBundle\Entity\TurFacturaConcepto();
                        $arFacturaConcepto = $em->getRepository('BrasaTurnoBundle:TurFacturaConcepto')->find($codigo);
                        $cantidad = $arrControles['TxtCantidad' . $codigo];
                        $precio = $arrControles['TxtPrecio' . $codigo];
                        $subtotal = $cantidad * $precio;
                        $iva = ($subtotal * $arFacturaConcepto->getPorIva())/100;
                        $total = $subtotal + $iva;
                        $arFacturaDetalleConcepto = new \Brasa\TurnoBundle\Entity\TurFacturaDetalleConcepto();
                        $arFacturaDetalleConcepto->setFacturaRel($arFactura);                        
                        $arFacturaDetalleConcepto->setFacturaConceptoRel($arFacturaConcepto);                        
                        $arFacturaDetalleConcepto->setPorIva($arFacturaConcepto->getPorIva());
                        $arFacturaDetalleConcepto->setCantidad($cantidad);
                        $arFacturaDetalleConcepto->setPrecio($precio);
                        $arFacturaDetalleConcepto->setSubtotal($subtotal);                        
                        $arFacturaDetalleConcepto->setIva($iva);
                        $arFacturaDetalleConcepto->setTotal($total);
                        $em->persist($arFacturaDetalleConcepto);  
                    }
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            } 
            if ($form->get('BtnFiltrar')->isClicked()) {            
                $this->filtrarDetalleNuevo($form);
            }
        }
        
        $arFacturaConceptos = new \Brasa\TurnoBundle\Entity\TurFacturaConcepto();
        $arFacturaConceptos = $em->getRepository('BrasaTurnoBundle:TurFacturaConcepto')->findAll();        
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalleOtroNuevo.html.twig', array(
            'arFacturaConceptos' => $arFacturaConceptos,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/tur/movimiento/factura/detalle/concepto/pedido/nuevo/{codigoFactura}", name="brs_tur_movimiento_factura_detalle_concepto_pedido_nuevo")
     */     
    public function detalleNuevoConceptoPedidoAction($codigoFactura) {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionarServicioConcepto');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {   
                        $arPedidoDetalleConcepto = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto();
                        $arPedidoDetalleConcepto = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->find($codigo);
                        
                        $arFacturaDetalleConcepto = new \Brasa\TurnoBundle\Entity\TurFacturaDetalleConcepto();                        
                        $arFacturaDetalleConcepto->setFacturaRel($arFactura);                         
                        $arFacturaDetalleConcepto->setFacturaConceptoRel($arPedidoDetalleConcepto->getFacturaConceptoRel());
                        $arFacturaDetalleConcepto->setPedidoDetalleConceptoRel($arPedidoDetalleConcepto);
                        $arFacturaDetalleConcepto->setCantidad($arPedidoDetalleConcepto->getCantidad());
                        $arFacturaDetalleConcepto->setIva($arPedidoDetalleConcepto->getIva());
                        $arFacturaDetalleConcepto->setPrecio($arPedidoDetalleConcepto->getPrecio());
                        $arFacturaDetalleConcepto->setSubtotal($arPedidoDetalleConcepto->getSubtotal());
                        $arFacturaDetalleConcepto->setTotal($arPedidoDetalleConcepto->getTotal());
                        $em->persist($arFacturaDetalleConcepto);
                        $arPedidoDetalleConcepto->setEstadoFacturado(1);
                        $em->persist($arPedidoDetalleConcepto);                        
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($codigoFactura);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $dql = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->listaClienteDql($arFactura->getCodigoClienteFk());
        $arPedidoDetallesConceptos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 50);                
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalleNuevoPedidoConcepto.html.twig', array(
            'arFactura' => $arFactura,
            'arPedidoDetalleConceptos' => $arPedidoDetallesConceptos,
            'form' => $form->createView()));
    }         
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroFacturaFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroFacturaFechaDesde');
            $strFechaHasta = $session->get('filtroFacturaFechaHasta');
        }
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurFactura')->listaDql(
                $session->get('filtroFacturaNumero'),
                $session->get('filtroCodigoCliente'),
                $session->get('filtroFacturaEstadoAutorizado'),
                $strFechaDesde,
                $strFechaHasta,
                $session->get('filtroFacturaEstadoAnulado'));
    }   
    
    private function listaDetalleNuevo($codigoCliente) {
        $em = $this->getDoctrine()->getManager();
        $strDql =  $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->pendientesFacturarDql($codigoCliente, $this->boolMostrarTodo);
        return $strDql;
    }

    private function filtrar ($form) {                
        $session = $this->getRequest()->getSession();        
        $session->set('filtroFacturaNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroFacturaEstadoAutorizado', $form->get('estadoAutorizado')->getData());          
        $session->set('filtroFacturaEstadoAnulado', $form->get('estadoAnulado')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroFacturaFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroFacturaFechaHasta', $dateFechaHasta->format('Y/m/d'));                 
        $session->set('filtroFacturaFiltrarFecha', $form->get('filtrarFecha')->getData());
    }

    private function filtrarDetalleNuevo ($form) {                
        $this->boolMostrarTodo = $form->get('mostrarTodo')->getData();
    }    
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroFacturaFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroFacturaFechaDesde');
        }
        if($session->get('filtroFacturaFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroFacturaFechaHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroFacturaNumero')))
            ->add('estadoAutorizado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'AUTORIZADO', '0' => 'SIN AUTORIZAR'), 'data' => $session->get('filtroFacturaEstadoAutorizado')))                
            ->add('estadoAnulado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'ANULADO', '0' => 'SIN ANULAR'), 'data' => $session->get('filtroFacturaEstadoAnulado')))                                
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('filtrarFecha', 'checkbox', array('required'  => false, 'data' => $session->get('filtroFacturaFiltrarFecha')))                 
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnContabilizar', 'submit', array('label'  => 'Contabilizar',))
            ->add('BtnInterfaz', 'submit', array('label'  => 'Interfaz',))                
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);      
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => true);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonVistaPrevia = array('label' => 'Vista previa', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonDetalleConceptoActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonDetalleConceptoEliminar = array('label' => 'Eliminar', 'disabled' => false);
        
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
            $arrBotonDetalleEliminar['disabled'] = true;            
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonDetalleConceptoActualizar['disabled'] = true;
            $arrBotonDetalleConceptoEliminar['disabled'] = true;
            $arrBotonAnular['disabled'] = false; 
            if($ar->getEstadoAnulado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonAnular['disabled'] = true;
            }            
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
 
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)                                     
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnVistaPrevia', 'submit', $arrBotonVistaPrevia)
                    ->add('BtnAnular', 'submit', $arrBotonAnular)                
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)
                    ->add('BtnDetalleConceptoActualizar', 'submit', $arrBotonDetalleConceptoActualizar)
                    ->add('BtnDetalleConceptoEliminar', 'submit', $arrBotonDetalleConceptoEliminar)
                    ->getForm();
        return $form;
    }
    
    private function formularioDetalleNuevo() {
        $em = $this->getDoctrine()->getManager();        
        $form = $this->createFormBuilder()
            ->add('mostrarTodo', 'checkbox', array('required'  => false))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar',))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        return $form;
    }    
    
    private function formularioDetalleOtroNuevo() {
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        return $form;
    } 
    
    private function actualizarDetalleConcepto($arrControles, $codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigoConcepto'])) {
            foreach ($arrControles['LblCodigoConcepto'] as $intCodigo) {
                $arFacturaDetalleConcepto = new \Brasa\TurnoBundle\Entity\TurFacturaDetalleConcepto();
                $arFacturaDetalleConcepto = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalleConcepto')->find($intCodigo);                
                $cantidad = $arrControles['TxtCantidadConcepto' . $intCodigo];
                $precio = $arrControles['TxtPrecioConcepto'. $intCodigo];                
                $subtotal = $cantidad * $precio;
                $iva = ($subtotal * $arFacturaDetalleConcepto->getPorIva())/100;
                $total = $subtotal + $iva;                
                $arFacturaDetalleConcepto->setCantidad($cantidad);
                $arFacturaDetalleConcepto->setPrecio($precio);
                $arFacturaDetalleConcepto->setSubtotal($subtotal);                        
                $arFacturaDetalleConcepto->setIva($iva);
                $arFacturaDetalleConcepto->setTotal($total);                                                             
                $em->persist($arFacturaDetalleConcepto);
            }
            $em->flush();                
            $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);            
        }        
    }    
    
    private function actualizarDetalle($arrControles, $codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($intCodigo);
                $arFacturaDetalle->setVrPrecio($arrControles['TxtPrecio'.$intCodigo]); 
                $arFacturaDetalle->setDetalle($arrControles['TxtDetalle'.$intCodigo]); 
                $em->persist($arFacturaDetalle);
            }
            $em->flush();                
            $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);            
        }        
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
        for($col = 'A'; $col !== 'O'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'I'; $col !== 'O'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'VENCE')
                    ->setCellValue('E1', 'NIT')                    
                    ->setCellValue('F1', 'CLIENTE')
                    ->setCellValue('G1', 'AUT')
                    ->setCellValue('H1', 'ANU')
                    ->setCellValue('I1', 'SUBTOTAL')    
                    ->setCellValue('J1', 'BASE AUI')
                    ->setCellValue('K1', 'IVA')
                    ->setCellValue('L1', 'RTEIVA')
                    ->setCellValue('M1', 'RTEFTE')
                    ->setCellValue('N1', 'TOTAL');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arFacturas = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFacturas = $query->getResult();

        foreach ($arFacturas as $arFactura) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFactura->getCodigoFacturaPk())
                    ->setCellValue('B' . $i, $arFactura->getNumero())
                    ->setCellValue('C' . $i, $arFactura->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arFactura->getFechaVence()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arFactura->getClienteRel()->getNit())
                    ->setCellValue('F' . $i, $arFactura->getClienteRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $objFunciones->devuelveBoolean($arFactura->getEstadoAutorizado()))
                    ->setCellValue('H' . $i, $objFunciones->devuelveBoolean($arFactura->getEstadoAnulado()))
                    ->setCellValue('I' . $i, $arFactura->getVrSubtotal())
                    ->setCellValue('J' . $i, $arFactura->getVrBaseAIU())
                    ->setCellValue('K' . $i, $arFactura->getVrIva())
                    ->setCellValue('L' . $i, $arFactura->getVrRetencionIva())
                    ->setCellValue('M' . $i, $arFactura->getVrRetencionFuente())
                    ->setCellValue('N' . $i, $arFactura->getVrTotal());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Facturas');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Facturas.xlsx"');
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
    
    private function generarExcelInterfaz() {
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
        for($col = 'A'; $col !== 'O'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'I'; $col !== 'O'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)                
                    ->setCellValue('A1', 'ABONO')
                    ->setCellValue('B1', 'ACTIVA')
                    ->setCellValue('C1', 'APAGAR')
                    ->setCellValue('D1', 'APORTE1')
                    ->setCellValue('E1', 'APORTE2')
                    ->setCellValue('F1', 'APORTE3')
                    ->setCellValue('G1', 'APROBADO')
                    ->setCellValue('H1', 'APRUEBA')
                    ->setCellValue('I1', 'AUTOREQUI')
                    ->setCellValue('J1', 'AUTORET')
                    ->setCellValue('K1', 'AUTORIZA')
                    ->setCellValue('L1', 'AUTORIZADO')
                    ->setCellValue('M1', 'AUTORIZPOR')
                    ->setCellValue('N1', 'AUTRETFAC')
                    ->setCellValue('O1', 'AUTRETICA')
                    ->setCellValue('P1', 'BANCOPRV')
                    ->setCellValue('Q1', 'BASEIVA')
                    ->setCellValue('R1', 'BRUTO')
                    ->setCellValue('S1', 'CAJAREG')
                    ->setCellValue('T1', 'CALICA')
                    ->setCellValue('U1', 'CALRETE')
                    ->setCellValue('V1', 'CALRETICA')
                    ->setCellValue('W1', 'CARGADA')
                    ->setCellValue('X1', 'CIUDADCLI')
                    ->setCellValue('Y1', 'CODCAJA')
                    ->setCellValue('Z1', 'CODCC')
                    ->setCellValue('AA1', 'CODCTACXP')
                    ->setCellValue('AB1', 'CODICA')
                    ->setCellValue('AC1', 'CODIGOCTA')
                    ->setCellValue('AD1', 'CODINT')
                    ->setCellValue('AE1', 'CODMONEDA')
                    ->setCellValue('AF1', 'CODRETE')						                
                    ->setCellValue('AG1', 'CODTCXP')
                    ->setCellValue('AH1', 'CODVEN')
                    ->setCellValue('AI1', 'COMENTARIO')
                    ->setCellValue('AJ1', 'CONIVA')
                    ->setCellValue('AK1', 'CONSFECHA')
                    ->setCellValue('AL1', 'CONSINV')
                    ->setCellValue('AM1', 'CONSNUMERO')		
                    ->setCellValue('AN1', 'CONTADO')
                    ->setCellValue('AO1', 'CTRLCORIG')
                    ->setCellValue('AP1', 'CTRTOPES')
                    ->setCellValue('AQ1', 'D1FECHA1')
                    ->setCellValue('AR1', 'D1FECHA2')
                    ->setCellValue('AS1', 'D1FECHA3')
                    ->setCellValue('AT1', 'D2FECHA1')
                    ->setCellValue('AU1', 'D2FECHA2')
                    ->setCellValue('AV1', 'D2FECHA3')
                    ->setCellValue('AW1', 'D3FECHA1')
                    ->setCellValue('AX1', 'D3FECHA2')
                    ->setCellValue('AY1', 'D3FECHA3')
                    ->setCellValue('AZ1', 'DCTOORD')
                    ->setCellValue('BA1', 'DCTOPMP')
                    ->setCellValue('BB1', 'DCTOPRV')
                    ->setCellValue('BC1', 'DCTORCM')
                    ->setCellValue('BD1', 'DCTOREQUI')
                    ->setCellValue('BE1', 'DCTORESER')						                
                    ->setCellValue('BF1', 'DECIMALES')
                    ->setCellValue('BG1', 'DESCARGADO')
                    ->setCellValue('BH1', 'DESCECOL')
                    ->setCellValue('BI1', 'DESCFINANC')
                    ->setCellValue('BJ1', 'DESCTOPP')
                    ->setCellValue('BK1', 'DESCUENTO')
                    ->setCellValue('BL1', 'DETERIORO')
                    ->setCellValue('BM1', 'DIASPDPP')
                    ->setCellValue('BN1', 'DIASPLAZO')
                    ->setCellValue('BO1', 'DIR')
                    ->setCellValue('BP1', 'DSCTOCOM')
                    ->setCellValue('BQ1', 'ENVIADOA')
                    ->setCellValue('BR1', 'FACTORSUS')
                    ->setCellValue('BS1', 'FACTURADO')
                    ->setCellValue('BT1', 'FECCAJA')
                    ->setCellValue('BU1', 'FECHA')
                    ->setCellValue('BV1', 'FECHA1')
                    ->setCellValue('BW1', 'FECHA2')
                    ->setCellValue('BX1', 'FECHA3')
                    ->setCellValue('BY1', 'FECHAING')
                    ->setCellValue('BZ1', 'FECHAMOD')
                    ->setCellValue('CA1', 'FECHANIF')
                    ->setCellValue('CB1', 'FECHAPANTE')
                    ->setCellValue('CC1', 'FECING')
                    ->setCellValue('CD1', 'FECMOD')
                    ->setCellValue('CE1', 'FHAUTORIZA')
                    ->setCellValue('CF1', 'FISCAL')
                    ->setCellValue('CG1', 'FISRL')
                    ->setCellValue('CH1', 'FIVA')
                    ->setCellValue('CI1', 'FLETES')										                
                    ->setCellValue('CJ1', 'FLLAMADA')
                    ->setCellValue('CK1', 'FORMAPAGO')
                    ->setCellValue('CL1', 'FRECAUDO')
                    ->setCellValue('CM1', 'GENELECT')
                    ->setCellValue('CN1', 'HORA')
                    ->setCellValue('CO1', 'IDADJUNTOS')
                    ->setCellValue('CP1', 'IDINTEGRA')
                    ->setCellValue('CQ1', 'IMPORTAC')
                    ->setCellValue('CR1', 'IMPRESO')
                    ->setCellValue('CS1', 'INTEGRADO')
                    ->setCellValue('CT1', 'INTNIIF')								                
                    ->setCellValue('CU1', 'IVABRUTO')
                    ->setCellValue('CV1', 'MARCDIST')
                    ->setCellValue('CW1', 'MECADORIG')
                    ->setCellValue('CX1', 'MECANCELA')
                    ->setCellValue('CY1', 'MEDIOPAG')
                    ->setCellValue('CZ1', 'MEFALLO')
                    ->setCellValue('DA1', 'MEFECHAT')
                    ->setCellValue('DB1', 'MENOCERSAT')
                    ->setCellValue('DC1', 'MESELLOCFD')
                    ->setCellValue('DD1', 'MESELLOSAT')
                    ->setCellValue('DE1', 'MEUUID')
                    ->setCellValue('DF1', 'MEVERSION')
                    ->setCellValue('DG1', 'MEXML')
                    ->setCellValue('DH1', 'MOTIVOTRAS')
                    ->setCellValue('DI1', 'MULTIMON')										                
                    ->setCellValue('DJ1', 'NIT')
                    ->setCellValue('DK1', 'NITCAJA')
                    ->setCellValue('DL1', 'NITRESP')
                    ->setCellValue('DM1', 'NOCORRIENT')
                    ->setCellValue('DN1', 'NOTA')
                    ->setCellValue('DO1', 'NROCMPISLR')
                    ->setCellValue('DP1', 'NROCMPIVA')
                    ->setCellValue('DQ1', 'NRODCTO')
                    ->setCellValue('DR1', 'NRODCTOAN')
                    ->setCellValue('DS1', 'NROSOLI')
                    ->setCellValue('DT1', 'NUMCUOTAS')								                
                    ->setCellValue('DU1', 'NUMEROCRP')
                    ->setCellValue('DV1', 'ORDEN')
                    ->setCellValue('DW1', 'ORIGEN')
                    ->setCellValue('DX1', 'OTRAMON')
                    ->setCellValue('DY1', 'OTROIMPU')
                    ->setCellValue('DZ1', 'PAIS')
                    ->setCellValue('EA1', 'PASSWORDAU')
                    ->setCellValue('EB1', 'PASSWORDIN')
                    ->setCellValue('EC1', 'PASSWORDMO')
                    ->setCellValue('ED1', 'PEDGUID')
                    ->setCellValue('EE1', 'PESODOLAR')
                    ->setCellValue('EF1', 'PGIVA')
                    ->setCellValue('EG1', 'PLACA')
                    ->setCellValue('EH1', 'PLANEADO')
                    ->setCellValue('EI1', 'PLANILLA')										                
                    ->setCellValue('EJ1', 'PLANPED')
                    ->setCellValue('EK1', 'PORAIU')
                    ->setCellValue('EL1', 'PRETECREE')
                    ->setCellValue('EM1', 'PRETENIVA')
                    ->setCellValue('EN1', 'PRETICA')
                    ->setCellValue('EO1', 'PRETIVA')
                    ->setCellValue('EP1', 'PRETPERC')
                    ->setCellValue('EQ1', 'PRETPERP')
                    ->setCellValue('ER1', 'PRIORIDAD')
                    ->setCellValue('ES1', 'REGSIMP')
                    ->setCellValue('ET1', 'REMIFACT')								                
                    ->setCellValue('EU1', 'REMISION')
                    ->setCellValue('EV1', 'RESERVADO')
                    ->setCellValue('EW1', 'RESPICA')
                    ->setCellValue('EX1', 'RESPONSA')
                    ->setCellValue('EY1', 'RESPRETE')
                    ->setCellValue('EZ1', 'RETEVAL')
                    ->setCellValue('FA1', 'RTEFTE')
                    ->setCellValue('FB1', 'SINIVA')
                    ->setCellValue('FC1', 'STADSINCRO')
                    ->setCellValue('FD1', 'TASAINTERE')
                    ->setCellValue('FE1', 'TCAMBIO')
                    ->setCellValue('FF1', 'TCAMBIOMM')
                    ->setCellValue('FG1', 'TCR')
                    ->setCellValue('FH1', 'TIPOABONO')
                    ->setCellValue('FI1', 'TIPOCAR')										                
                    ->setCellValue('FJ1', 'TIPOCXP')
                    ->setCellValue('FK1', 'TIPODCTO')
                    ->setCellValue('FL1', 'TIPODCTOAN')
                    ->setCellValue('FM1', 'TIPODCTOPC')
                    ->setCellValue('FN1', 'TIPODCTORE')
                    ->setCellValue('FO1', 'TIPODCTOSI')
                    ->setCellValue('FP1', 'TIPODCTOTR')
                    ->setCellValue('FQ1', 'TIPOMONEDA')
                    ->setCellValue('FR1', 'TIPOMVTO')
                    ->setCellValue('FS1', 'TIPOPER')
                    ->setCellValue('FT1', 'TIPOREQ')								                
                    ->setCellValue('FU1', 'TIPOTRAN')
                    ->setCellValue('FV1', 'TIPOVTA')
                    ->setCellValue('FW1', 'TOPE')
                    ->setCellValue('FX1', 'TRANSPORTA')
                    ->setCellValue('FY1', 'UNDTRIBU')
                    ->setCellValue('FZ1', 'UPAC')
                    ->setCellValue('GA1', 'VALCONSUMO')
                    ->setCellValue('GB1', 'VALORPLAN')
                    ->setCellValue('GC1', 'VDETRACCIO')
                    ->setCellValue('GD1', 'VIGENTE')
                    ->setCellValue('GE1', 'VIPCONSU')
                    ->setCellValue('GF1', 'VLRECAUDO')
                    ->setCellValue('GG1', 'VLRECONOCE')
                    ->setCellValue('GH1', 'VLRETCREE')
                    ->setCellValue('GI1', 'VLRETCREEA')										                
                    ->setCellValue('GJ1', 'VLRETFTE')
                    ->setCellValue('GK1', 'VPERCEPCIO')
                    ->setCellValue('GL1', 'VRECICA')
                    ->setCellValue('GM1', 'VRETENIVA')
                    ->setCellValue('GN1', 'VRETICA')
                    ->setCellValue('GO1', 'VRETICAA')
                    ->setCellValue('GP1', 'VRETIVA')
                    ->setCellValue('GQ1', 'VRETIVASIM')
                    ->setCellValue('GR1', 'VRSEGURIDA')
                    ->setCellValue('GS1', 'VRTEFTEA')
                    ->setCellValue('GT1', 'XBASEIVA')								                
                    ->setCellValue('GU1', 'XBRUTO')
                    ->setCellValue('GV1', 'XCONIVA')
                    ->setCellValue('GW1', 'XDESCUENTO')
                    ->setCellValue('GX1', 'XDESFINANC')
                    ->setCellValue('GY1', 'XFLETES')
                    ->setCellValue('GZ1', 'XIVABRUTO')
                    ->setCellValue('HA1', 'XRECICA')
                    ->setCellValue('HB1', 'XRETENIVA')
                    ->setCellValue('HC1', 'XRETICA')
                    ->setCellValue('HD1', 'XRETIVA')
                    ->setCellValue('HE1', 'XRETIVASIM')
                    ->setCellValue('HF1', 'XSINIVA')
                    ->setCellValue('HG1', 'XVLRETCREE')
                    ->setCellValue('HH1', 'XVLRETFTE')
                    ->setCellValue('HI1', 'XVRETCREEA')										                
                    ->setCellValue('HJ1', 'XVRETICAA')
                    ->setCellValue('HK1', 'XVRTEFTEA')	                                                                                                                                
                    ->setCellValue('HL1', 'ZBASEIVA')
                    ->setCellValue('HM1', 'ZBRUTO')
                    ->setCellValue('HN1', 'ZCONIVA')
                    ->setCellValue('HO1', 'ZDESCUENTO')
                    ->setCellValue('HP1', 'ZDESFINANC')
                    ->setCellValue('HQ1', 'ZFLETES')
                    ->setCellValue('HR1', 'ZIVABRUTO')
                    ->setCellValue('HS1', 'ZRECICA')
                    ->setCellValue('HT1', 'ZRETENIVA')								                
                    ->setCellValue('HU1', 'ZRETICA')
                    ->setCellValue('HV1', 'ZRETIVA')
                    ->setCellValue('HW1', 'ZRETIVASIM')
                    ->setCellValue('HX1', 'ZSINIVA')
                    ->setCellValue('HY1', 'ZVLRETCREE')
                    ->setCellValue('HZ1', 'ZVLRETFTE')
                    ->setCellValue('IA1', 'ZVRETCREEA')
                    ->setCellValue('IB1', 'ZVRETICAA')
                    ->setCellValue('IC1', 'ZVRTEFTEA')
                    ->setCellValue('ID1', 'INDEPENDIE')
                    ->setCellValue('IE1', 'VALORAYS');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arFacturas = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFacturas = $query->getResult();

        foreach ($arFacturas as $arFactura) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFactura->getCodigoFacturaPk())
                    ->setCellValue('B' . $i, $arFactura->getNumero())
                    ->setCellValue('C' . $i, $arFactura->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arFactura->getFechaVence()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arFactura->getClienteRel()->getNit())
                    ->setCellValue('F' . $i, $arFactura->getClienteRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $objFunciones->devuelveBoolean($arFactura->getEstadoAutorizado()))
                    ->setCellValue('H' . $i, $objFunciones->devuelveBoolean($arFactura->getEstadoAnulado()))
                    ->setCellValue('I' . $i, $arFactura->getVrSubtotal())
                    ->setCellValue('J' . $i, $arFactura->getVrBaseAIU())
                    ->setCellValue('K' . $i, $arFactura->getVrIva())
                    ->setCellValue('L' . $i, $arFactura->getVrRetencionIva())
                    ->setCellValue('M' . $i, $arFactura->getVrRetencionFuente())
                    ->setCellValue('N' . $i, $arFactura->getVrTotal());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Facturas');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Facturas.xlsx"');
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