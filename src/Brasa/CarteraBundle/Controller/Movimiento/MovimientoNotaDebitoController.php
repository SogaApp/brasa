<?php
namespace Brasa\CarteraBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\CarteraBundle\Form\Type\CarNotaDebitoType;
use Brasa\CarteraBundle\Form\Type\CarNotaDebitoDetalleType;

class MovimientoNotaDebitoController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/cartera/movimiento/notadebito/lista", name="brs_cartera_movimiento_notadebito_listar")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 117, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->lista();        
        if ($form->isValid()) {               
            if ($form->get('BtnEliminar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 117, 4)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaCarteraBundle:CarNotaDebito')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_notadebito_listar'));                
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

        $arNotasDebitos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaCarteraBundle:Movimientos/NotaDebito:lista.html.twig', array(
            'arNotasDebitos' => $arNotasDebitos,            
            'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/movimiento/notadebito/nuevo/{codigoNotaDebito}", name="brs_cartera_movimiento_notadebito_nuevo")
     */
    public function nuevoAction($codigoNotaDebito) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arNotaDebito = new \Brasa\CarteraBundle\Entity\CarNotaDebito();
        if($codigoNotaDebito != 0) {
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 117, 3)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
            $arNotaDebito = $em->getRepository('BrasaCarteraBundle:CarNotaDebito')->find($codigoNotaDebito);
        }else{
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 117, 2)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
            $arNotaDebito->setFecha(new \DateTime('now'));
            $arNotaDebito->setFechaPago(new \DateTime('now'));
        }
        $form = $this->createForm(new CarNotaDebitoType, $arNotaDebito);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arNotaDebito = $form->getData();
            $arrControles = $request->request->All();
            $arCliente = new \Brasa\CarteraBundle\Entity\CarCliente();
            if($arrControles['txtNit'] != '') {                
                $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $arNotaDebito->setClienteRel($arCliente);
                }
            }
            if ($codigoNotaDebito != 0 && $em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->numeroRegistros($codigoNotaDebito) > 0) {
                if ($arNotaDebito->getCodigoClienteFk() == $arCliente->getCodigoClientePk()) {
                    $arUsuario = $this->getUser();
                    $arNotaDebito->setUsuario($arUsuario->getUserName());            
                    $em->persist($arNotaDebito);
                    $em->flush();
                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_notadebito_nuevo', array('codigoNotaDebito' => 0 )));
                    } else {
                        if ($codigoNotaDebito != 0){
                            return $this->redirect($this->generateUrl('brs_cartera_movimiento_notadebito_listar'));
                        } else {
                            return $this->redirect($this->generateUrl('brs_cartera_movimiento_notadebito_detalle', array('codigoNotaDebito' => $arNotaDebito->getCodigoNotaDebitoPk())));
                        }

                    }
                } else {
                    $objMensaje->Mensaje("error", "Para modificar el cliente debe eliminar los detalles asociados a este registro", $this);
                }
            } else {
                $arUsuario = $this->getUser();
                $arNotaDebito->setUsuario($arUsuario->getUserName());            
                $em->persist($arNotaDebito);
                $em->flush();
                if($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_notadebito_nuevo', array('codigoNotaDebito' => 0 )));
                } else {
                    if ($codigoNotaDebito != 0){
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_notadebito_listar'));
                    } else {
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_notadebito_detalle', array('codigoNotaDebito' => $arNotaDebito->getCodigoNotaDebitoPk())));
                    }
                }
            }
        }
        return $this->render('BrasaCarteraBundle:Movimientos/NotaDebito:nuevo.html.twig', array(
            'arNotaDebito' => $arNotaDebito,
            'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/movimiento/notadebito/detalle/{codigoNotaDebito}", name="brs_cartera_movimiento_notadebito_detalle")
     */
    public function detalleAction($codigoNotaDebito) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arNotaDebito = new \Brasa\CarteraBundle\Entity\CarNotaDebito();
        $arNotaDebito = $em->getRepository('BrasaCarteraBundle:CarNotaDebito')->find($codigoNotaDebito);
        $form = $this->formularioDetalle($arNotaDebito);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 117, 5)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $arrControles = $request->request->All();
                if ($arNotaDebito->getEstadoAutorizado() == 0){
                    $this->actualizarDetalle($arrControles, $codigoNotaDebito);
                    $arInconsistencias = $em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->findBy(array('codigoNotaDebitoFk' =>$codigoNotaDebito,'estadoInconsistencia' => 1));
                    if ($arInconsistencias == null){
                        if($arNotaDebito->getEstadoAutorizado() == 0) {
                            if ($em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->findBy(array('codigoNotaDebitoFk' => $codigoNotaDebito))){
                                if($em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->numeroRegistros($codigoNotaDebito) > 0) {
                                    $arNotaDebito->setEstadoAutorizado(1);
                                    $arDetallesNotaDebito = $em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->findBy(array('codigoNotaDebitoFk' => $codigoNotaDebito));
                                    foreach ($arDetallesNotaDebito AS $arDetalleNotaDebito) {
                                        $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                                        $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arDetalleNotaDebito->getCodigoCuentaCobrarFk());
                                        $arCuentaCobrar->setSaldo($arCuentaCobrar->getSaldo() + $arDetalleNotaDebito->getVrPagoDetalle());
                                        $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() - $arDetalleNotaDebito->getVrPagoDetalle());
                                        $em->persist($arCuentaCobrar);
                                    }
                                    $em->persist($arNotaDebito);
                                    $em->flush();                        
                                } else {
                                    $objMensaje->Mensaje('error', 'Debe adicionar detalles al recibo de caja', $this);
                                }                    
                            } else {
                                $arNotaDebito->setEstadoAutorizado(1);
                                $em->persist($arNotaDebito);
                                $em->flush();
                            }    
                        }
                    } else {
                        $objMensaje->Mensaje('error', 'No se puede autorizar, hay inconsistencias', $this);
                    }

                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_notadebito_detalle', array('codigoNotaDebito' => $codigoNotaDebito)));                
                } else {
                    $objMensaje->Mensaje('error', 'No se puede autorizar, ya esta autorizado', $this); 
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 117, 6)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                if($arNotaDebito->getEstadoAutorizado() == 1 && $arNotaDebito->getEstadoImpreso() == 0) {
                    $arNotaDebito->setEstadoAutorizado(0);
                    $arDetallesNotaDebito = $em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->findBy(array('codigoNotaDebitoFk' => $codigoNotaDebito));
                    foreach ($arDetallesNotaDebito AS $arDetalleNotaDebito) {
                        $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                        $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arDetalleNotaDebito->getCodigoCuentaCobrarFk());
                        $arCuentaCobrar->setSaldo($arCuentaCobrar->getSaldo() - $arDetalleNotaDebito->getVrPagoDetalle());
                        $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() + $arDetalleNotaDebito->getVrPagoDetalle());
                        $em->persist($arCuentaCobrar);
                    }
                    $em->persist($arNotaDebito);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_notadebito_detalle', array('codigoNotaDebito' => $codigoNotaDebito)));                
                } else {
                    $objMensaje->Mensaje('error', "La nota debito debe estar autorizado y no puede estar impreso", $this);
                }
            }
            if($form->get('BtnAnular')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 117, 9)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                if($arNotaDebito->getEstadoImpreso() == 1) {
                    $arNotaDebito->setEstadoAnulado(1);
                    $arNotaDebito->setValor(0);
                    $arDetallesNotaDebito = $em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->findBy(array('codigoNotaDebitoFk' => $codigoNotaDebito));
                    foreach ($arDetallesNotaDebito AS $arDetalleNotaDebito) {
                        $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                        $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arDetalleNotaDebito->getCodigoCuentaCobrarFk());
                        $arCuentaCobrar->setSaldo($arCuentaCobrar->getSaldo() - $arDetalleNotaDebito->getVrPagoDetalle());
                        $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() + $arDetalleNotaDebito->getVrPagoDetalle());
                        $arDetalleNotaDebitoAnulado = new \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle();
                        $arDetalleNotaDebitoAnulado = $em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->find($arDetalleNotaDebito->getCodigoNotaDebitoDetallePk());
                        $arDetalleNotaDebitoAnulado->setValor(0);
                        $em->persist($arCuentaCobrar);
                        $em->persist($arDetalleNotaDebitoAnulado);
                    }
                    $em->persist($arNotaDebito);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_notadebito_detalle', array('codigoNotaDebito' => $codigoNotaDebito)));                
                }
            }
            if($form->get('BtnDetalleActualizar')->isClicked()) {
                if($arNotaDebito->getEstadoAutorizado() == 0 ) {
                    $arrControles = $request->request->All();
                    $this->actualizarDetalle($arrControles, $codigoNotaDebito);                
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_notadebito_detalle', array('codigoNotaDebito' => $codigoNotaDebito)));
                } else {
                    $objMensaje->Mensaje("error", "No se puede imprimir el registro, esta autorizado", $this);
                }
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {
                if($arNotaDebito->getEstadoAutorizado() == 0 ) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->eliminarSeleccionados($arrSeleccionados);
                    $em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->liquidar($codigoNotaDebito);
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_notadebito_detalle', array('codigoNotaDebito' => $codigoNotaDebito)));
                } else {
                    $objMensaje->Mensaje("error", "No se puede eliminar el registro, esta autorizado", $this);
                }
            }    
            if($form->get('BtnImprimir')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 118, 10)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                if($arNotaDebito->getEstadoAutorizado() == 1 ) {
                    $strResultado = $em->getRepository('BrasaCarteraBundle:CarNotaDebito')->imprimir($codigoNotaDebito);
                    if($strResultado != "") {
                        $objMensaje->Mensaje("error", $strResultado, $this);
                    } else {
                        $objNotaDebito = new \Brasa\CarteraBundle\Formatos\FormatoNotaDebito();
                        $objNotaDebito->Generar($this, $codigoNotaDebito);
                    }
                } else {
                    $objMensaje->Mensaje("error", "No se puede imprimir el registro, no esta autorizado", $this);
                }
            }                        
        }
        $arNotaDebitoDetalle = new \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle();
        $arNotaDebitoDetalle = $em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->findBy(array ('codigoNotaDebitoFk' => $codigoNotaDebito));
        return $this->render('BrasaCarteraBundle:Movimientos/NotaDebito:detalle.html.twig', array(
                    'arNotaDebito' => $arNotaDebito,
                    'arNotaDebitoDetalle' => $arNotaDebitoDetalle,
                    'form' => $form->createView()
                    ));
    }
    
    /**
     * @Route("/cartera/movimiento/notadebito/detalle/nuevo/{codigoNotaDebito}/{codigoNotaDebitoDetalle}", name="brs_cartera_movimiento_notadebito_detalle_nuevo")
     */
    public function detalleNuevoAction($codigoNotaDebito, $codigoNotaDebitoDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $arNotaDebito = new \Brasa\CarteraBundle\Entity\CarNotaDebito();
        $arNotaDebito = $em->getRepository('BrasaCarteraBundle:CarNotaDebito')->find($codigoNotaDebito);
        $arCuentasCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
        $arCuentasCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->cuentasCobrar($arNotaDebito->getCodigoClienteFk());
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
                        if($em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->validarCuenta($codigoCuentaCobrar, $codigoNotaDebito)) {
                            $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($codigoCuentaCobrar);
                            $arNotaDebitoDetalle = new \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle();
                            $arNotaDebitoDetalle->setNotaDebitoRel($arNotaDebito);
                            $arNotaDebitoDetalle->setCuentaCobrarRel($arCuentaCobrar);
                            $arNotaDebitoDetalle->setValor($arrControles['TxtSaldo'.$codigoCuentaCobrar]);
                            $arNotaDebitoDetalle->setUsuario($arUsuario->getUserName());
                            $arNotaDebitoDetalle->setNumeroFactura($arCuentaCobrar->getNumeroDocumento());
                            $arNotaDebitoDetalle->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                            $em->persist($arNotaDebitoDetalle);                            
                        } 
                    }
                    $em->flush();
                } 
                $em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->liquidar($codigoNotaDebito);
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        return $this->render('BrasaCarteraBundle:Movimientos/NotaDebito:detalleNuevo.html.twig', array(
            'arCuentasCobrar' => $arCuentasCobrar,
            'arNotaDebito' => $arNotaDebito,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/cartera/movimiento/notadebito/anticipo/nuevo/{codigoNotaDebito}", name="brs_cartera_movimiento_notadebito_anticipo_nuevo")
     */
    public function anticipoAction($codigoNotaDebito) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $arNotaDebito = new \Brasa\CarteraBundle\Entity\CarNotaDebito();
        $arNotaDebito = $em->getRepository('BrasaCarteraBundle:CarNotaDebito')->find($codigoNotaDebito);
        
        $arAnticipos = new \Brasa\CarteraBundle\Entity\CarAnticipo();
        $arAnticipos = $em->getRepository('BrasaCarteraBundle:CarAnticipo')->anticipos($arNotaDebito->getCodigoClienteFk());
        $arAnticipos = $paginator->paginate($arAnticipos, $request->query->get('page', 1), 50);
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
                    foreach ($arrSeleccionados AS $codigoAnticipo) {
                        
                        $anticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipo')->find($codigoAnticipo);
                        $arNotaDebito->setValor($anticipo->getVrAnticipo());
                        $arNotaDebito->setNumero($anticipo->getNumero());
                        $em->persist($arNotaDebito);                            
                    }
                    $em->flush();
                } 
                //$em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->liquidar($codigoNotaDebito);
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        return $this->render('BrasaCarteraBundle:Movimientos/NotaDebito:anticipoNuevo.html.twig', array(
            'arAnticipos' => $arAnticipos,
            'arNotaDebito' => $arNotaDebito,
            'form' => $form->createView()));
    }
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strListaDql =  $em->getRepository('BrasaCarteraBundle:CarNotaDebito')->listaDQL(
                $session->get('filtroNotaDebitoNumero'), 
                $session->get('filtroCodigoCliente'),
                $session->get('filtroNotaDebitoEstadoImpreso'));
    }

    private function filtrar ($form) {       
        $session = $this->getRequest()->getSession();        
        $session->set('filtroNotaDebitoNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroNotaDebitoEstadoImpreso', $form->get('estadoImpreso')->getData());          
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
            ->add('estadoImpreso', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'IMPRESO', '0' => 'SIN IMPRIMIR'), 'data' => $session->get('filtroNotaDebitoEstadoImpreso')))                
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
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;            
            $arrBotonAnular['disabled'] = true;           
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
            $arrBotonAnular['disabled'] = true;
        }
        if($ar->getEstadoImpreso() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAnular['disabled'] = false;
        }
        if($ar->getEstadoAnulado() == 1) {
            $arrBotonAnular['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)                 
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnAnular', 'submit', $arrBotonAnular)
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
                    ->setCellValue('F1', 'CONCEPTO')
                    ->setCellValue('G1', 'FECHA PAGO')
                    ->setCellValue('H1', 'TOTAL')
                    ->setCellValue('I1', 'ANULADO')
                    ->setCellValue('J1', 'AUTORIZADO')
                    ->setCellValue('K1', 'IMPRESO');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arNotaDebitos = new \Brasa\CarteraBundle\Entity\CarNotaDebito();
        $arNotaDebitos = $query->getResult();

        foreach ($arNotaDebitos as $arNotaDebito) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arNotaDebito->getCodigoNotaDebitoPk())
                    ->setCellValue('B' . $i, $arNotaDebito->getNumero())
                    ->setCellValue('E' . $i, $arNotaDebito->getCuentaRel()->getNombre())
                    ->setCellValue('F' . $i, $arNotaDebito->getNotaDebitoConceptoRel()->getNombre())
                    ->setCellValue('G' . $i, $arNotaDebito->getFechaPago()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arNotaDebito->getValor())
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arNotaDebito->getEstadoAnulado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arNotaDebito->getEstadoAutorizado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arNotaDebito->getEstadoImpreso()));
            if($arNotaDebito->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C' . $i, $arNotaDebito->getClienteRel()->getNit());
            }
            if($arNotaDebito->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arNotaDebito->getClienteRel()->getNombreCorto());
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

    private function actualizarDetalle($arrControles, $codigoNotaDebito) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        $floTotal = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arNotaDebitoDetalle = new \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle();
                $arNotaDebitoDetalle = $em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->find($intCodigo);
                $floSaldo = $arNotaDebitoDetalle->getCuentaCobrarRel()->getSaldo();
                $floSaldoAfectar = $arrControles['TxtValor'.$intCodigo];
                
                $arNotaDebitoDetalle->setValor($arrControles['TxtValor'.$intCodigo]);
                $arNotaDebitoDetalle->setVrPagoDetalle($floSaldoAfectar);
                $em->persist($arNotaDebitoDetalle);
            }
            $em->flush();
            $em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->liquidar($codigoNotaDebito);                   
        }
    }
    
}