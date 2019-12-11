<?php
namespace Brasa\CarteraBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\CarteraBundle\Form\Type\CarAnticipoType;
use Brasa\CarteraBundle\Form\Type\CarAnticipoDetalleType;

class MovimientoAnticipoController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/cartera/movimiento/anticipo/lista", name="brs_cartera_movimiento_anticipo_listar")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 115, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->lista();        
        if ($form->isValid()) {               
            if ($form->get('BtnEliminar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 115, 4)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaCarteraBundle:CarAnticipo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_listar'));                
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

        $arAnticipos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 50);
        return $this->render('BrasaCarteraBundle:Movimientos/Anticipo:lista.html.twig', array(
            'arAnticipos' => $arAnticipos,            
            'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/movimiento/anticipo/nuevo/{codigoAnticipo}", name="brs_cartera_movimiento_anticipo_nuevo")
     */
    public function nuevoAction($codigoAnticipo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arAnticipo = new \Brasa\CarteraBundle\Entity\CarAnticipo();
        if($codigoAnticipo != 0) {
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 115, 3)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
            $arAnticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipo')->find($codigoAnticipo);
        }else{
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 115, 2)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
            $arAnticipo->setFecha(new \DateTime('now'));
            $arAnticipo->setFechaPago(new \DateTime('now'));
        }
        $form = $this->createForm(new CarAnticipoType, $arAnticipo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arAnticipo = $form->getData();
            $arrControles = $request->request->All();
            $arCliente = new \Brasa\CarteraBundle\Entity\CarCliente();
            if($arrControles['txtNit'] != '') {                
                $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $arAnticipo->setClienteRel($arCliente);
                    $arAnticipo->setAsesorRel($arCliente->getAsesorRel());
                }
            }
            if ($codigoAnticipo != 0 && $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->numeroRegistros($codigoAnticipo) > 0) {
                if ($arAnticipo->getCodigoClienteFk() == $arCliente->getCodigoClientePk()) {
                    $arUsuario = $this->getUser();
                    $arAnticipo->setUsuario($arUsuario->getUserName());
                    $em->persist($arAnticipo);
                    $em->flush();
                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_nuevo', array('codigoAnticipo' => 0 )));
                    } else {
                        if ($codigoAnticipo != 0){
                            return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_listar'));
                        } else {
                            return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_detalle', array('codigoAnticipo' => $arAnticipo->getCodigoAnticipoPk())));
                        }

                    }
                } else {
                    $objMensaje->Mensaje("error", "Para modificar el cliente debe eliminar los detalles asociados a este registro", $this);
                }
            } else {
                $arUsuario = $this->getUser();
                $arAnticipo->setUsuario($arUsuario->getUserName());
                $valorAnticipo = $form->get('vrAnticipo')->getData();
                if ($valorAnticipo > 0){
                    $arAnticipo->setVrTotal($arAnticipo->getVrAnticipo());
                    $arAnticipo->setVrTotalPago($arAnticipo->getVrAnticipo());
                    $em->persist($arAnticipo);
                    $em->flush();
                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_nuevo', array('codigoAnticipo' => 0 )));
                    } else {
                        if ($codigoAnticipo != 0){
                            return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_listar'));
                        } else {
                            return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_detalle', array('codigoAnticipo' => $arAnticipo->getCodigoAnticipoPk())));
                        }
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_listar'));
                    }
                } else {
                    $objMensaje->Mensaje("error", "El valor del anticipo debe ser mayor a cero", $this);
                }
                    
            }
        }
        return $this->render('BrasaCarteraBundle:Movimientos/Anticipo:nuevo.html.twig', array(
            'arAnticipo' => $arAnticipo,
            'form' => $form->createView()));
    }
  
    /**
     * @Route("/cartera/movimiento/anticipo/detalle/{codigoAnticipo}", name="brs_cartera_movimiento_anticipo_detalle")
     */
    public function detalleAction($codigoAnticipo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arAnticipo = new \Brasa\CarteraBundle\Entity\CarAnticipo();
        $arAnticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipo')->find($codigoAnticipo);
        $form = $this->formularioDetalle($arAnticipo);
        $form->handleRequest($request);
        $arUsuario = $this->getUser();
        $rol = $arUsuario->getRoles();
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 115, 5)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $arrControles = $request->request->All();
                if ($arAnticipo->getEstadoAutorizado() == 0){
                    $this->actualizarDetalle($arrControles, $codigoAnticipo);
                    $arInconsistencias = $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->findBy(array('codigoAnticipoFk' =>$codigoAnticipo,'estadoInconsistencia' => 1));
                    if ($arInconsistencias == null){
                        if($arAnticipo->getEstadoAutorizado() == 0) {
                            if($em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->numeroRegistros($codigoAnticipo) > 0) {
                                $arAnticipo->setEstadoAutorizado(1);
                                $arDetallesAnticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->findBy(array('codigoAnticipoFk' => $codigoAnticipo));
                                foreach ($arDetallesAnticipo AS $arDetalleAnticipo) {
                                    $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                                    $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arDetalleAnticipo->getCodigoCuentaCobrarFk()); 
                                    $arCuentaCobrar->setSaldo($arCuentaCobrar->getSaldo() - $arDetalleAnticipo->getVrPagoDetalle());
                                    $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() + $arDetalleAnticipo->getVrPagoDetalle());
                                    $em->persist($arCuentaCobrar);
                                }
                                $em->persist($arAnticipo);
                                $em->flush();                        
                            } else {
                                $objMensaje->Mensaje('error', 'Debe adicionar detalles al anticipo de caja', $this);
                            }                    
                        }
                    } else {
                        $objMensaje->Mensaje('error', 'No se puede autorizar, hay inconsistencias', $this);
                    }
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_detalle', array('codigoAnticipo' => $codigoAnticipo)));                
                } else {
                   $objMensaje->Mensaje('error', 'No se puede autorizar, ya esta autorizado', $this); 
                }
                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 115, 6)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                if($arAnticipo->getEstadoAutorizado() == 1 && $arAnticipo->getEstadoImpreso() == 0) {
                    $arAnticipo->setEstadoAutorizado(0);
                    $arDetallesAnticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->findBy(array('codigoAnticipoFk' => $codigoAnticipo));
                    foreach ($arDetallesAnticipo AS $arDetalleAnticipo) {
                        $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                        $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arDetalleAnticipo->getCodigoCuentaCobrarFk());
                        $arCuentaCobrar->setSaldo($arCuentaCobrar->getSaldo() + $arDetalleAnticipo->getVrPagoDetalle());
                        $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() - $arDetalleAnticipo->getVrPagoDetalle());
                        $em->persist($arCuentaCobrar);
                    }
                    $em->persist($arAnticipo);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_detalle', array('codigoAnticipo' => $codigoAnticipo)));                
                } else {
                    $objMensaje->Mensaje('error', "El anticipo debe estar autorizado y no puede estar impreso", $this);
                }
            }
            if($form->get('BtnAnular')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 115, 9)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                if($arAnticipo->getEstadoImpresoAnticipado() == 1) {
                    $arAnticipo->setEstadoAnulado(1);
                    $arAnticipo->setVrTotalAjustePeso(0);
                    $arAnticipo->setVrTotalDescuento(0);
                    $arAnticipo->setVrTotalReteIca(0);
                    $arAnticipo->setVrTotalReteIva(0);
                    $arAnticipo->setVrTotalReteFuente(0);
                    $arAnticipo->setVrTotal(0);
                    $arDetallesAnticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->findBy(array('codigoAnticipoFk' => $codigoAnticipo));
                    foreach ($arDetallesAnticipo AS $arDetalleAnticipo) {
                        $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                        $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arDetalleAnticipo->getCodigoCuentaCobrarFk());
                        $arCuentaCobrar->setSaldo($arCuentaCobrar->getSaldo() + $arDetalleAnticipo->getVrPagoDetalle());
                        $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() - $arDetalleAnticipo->getVrPagoDetalle());
                        $arDetalleAnticipoAnulado = new \Brasa\CarteraBundle\Entity\CarAnticipoDetalle();
                        $arDetalleAnticipoAnulado = $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->find($arDetalleAnticipo->getCodigoAnticipoDetallePk());
                        $arDetalleAnticipoAnulado->setVrDescuento(0);
                        $arDetalleAnticipoAnulado->setVrAjustePeso(0);
                        $arDetalleAnticipoAnulado->setVrReteIca(0);
                        $arDetalleAnticipoAnulado->setVrReteIva(0);
                        $arDetalleAnticipoAnulado->setVrReteFuente(0);
                        $arDetalleAnticipoAnulado->setValor(0);
                        $em->persist($arCuentaCobrar);
                        $em->persist($arDetalleAnticipoAnulado);
                    }
                    $em->persist($arAnticipo);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_detalle', array('codigoAnticipo' => $codigoAnticipo)));                
                }
            }
            if($form->get('BtnDetalleActualizar')->isClicked()) {
                if($arAnticipo->getEstadoAutorizado() == 0 ) {
                    $arrControles = $request->request->All();
                    $this->actualizarDetalle($arrControles, $codigoAnticipo);                
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_detalle', array('codigoAnticipo' => $codigoAnticipo)));
                } else {
                    $objMensaje->Mensaje("error", "No se puede actualizar el registro, esta autorizado", $this);
                }    
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {                
                if($arAnticipo->getEstadoAutorizado() == 0 ) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->eliminarSeleccionados($arrSeleccionados);
                    $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->liquidar($codigoAnticipo);                 
                } else {
                    $objMensaje->Mensaje("error", "No se puede eliminar el registro, esta autorizado", $this);
                }
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_detalle', array('codigoAnticipo' => $codigoAnticipo)));                    
            }    
            if($form->get('BtnImprimir')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 115, 10)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                if($arAnticipo->getEstadoAutorizado() == 1 ) {
                    $strResultado = $em->getRepository('BrasaCarteraBundle:CarAnticipo')->imprimir($codigoAnticipo);
                    if($strResultado != "") {
                        $objMensaje->Mensaje("error", $strResultado, $this);
                    } else {
                        $objAnticipo = new \Brasa\CarteraBundle\Formatos\FormatoAnticipo();
                        $objAnticipo->Generar($this, $codigoAnticipo);
                    }
                } else {
                    //$objMensaje->Mensaje("error", "No se puede imprimir el registro, no esta autorizado", $this);
                    $objAnticipo = new \Brasa\CarteraBundle\Formatos\FormatoAnticipo();
                    $objAnticipo->Generar($this, $codigoAnticipo);
                    $arAnticipo->setEstadoImpresoAnticipado(1);
                    if($arAnticipo->getNumero() == 0) {            
                        $intNumero = $em->getRepository('BrasaCarteraBundle:CarConsecutivo')->consecutivo(1);
                        $arAnticipo->setNumero($intNumero);
                    }
                    $em->persist($arAnticipo);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_detalle', array('codigoAnticipo' => $codigoAnticipo)));                        
                        
                }
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_detalle', array('codigoAnticipo' => $codigoAnticipo)));                        
            }                        
        }
        $arAnticipoDetalle = new \Brasa\CarteraBundle\Entity\CarAnticipoDetalle();
        $arAnticipoDetalle = $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->findBy(array ('codigoAnticipoFk' => $codigoAnticipo));
        return $this->render('BrasaCarteraBundle:Movimientos/Anticipo:detalle.html.twig', array(
                    'arAnticipo' => $arAnticipo,
                    'arAnticipoDetalle' => $arAnticipoDetalle,
                    'form' => $form->createView(),
                    'rol' => $rol
                    ));
    }
    
    /**
     * @Route("/cartera/movimiento/anticipo/detalle/nuevo/{codigoAnticipo}/{codigoAnticipoDetalle}", name="brs_cartera_movimiento_anticipo_detalle_nuevo")
     */
    public function detalleNuevoAction($codigoAnticipo, $codigoAnticipoDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();        
        $paginator  = $this->get('knp_paginator');
        $arAnticipo = new \Brasa\CarteraBundle\Entity\CarAnticipo();
        $arAnticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipo')->find($codigoAnticipo);
        $arCuentasCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
        $arCuentasCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->cuentasCobrar($arAnticipo->getCodigoClienteFk());
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
                $totalAnticipoDetalle = 0;
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCuentaCobrar) {
                        if($em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->validarCuenta($codigoCuentaCobrar,$codigoAnticipo)) {
                            $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($codigoCuentaCobrar);
                            $arAnticipoDetalle = new \Brasa\CarteraBundle\Entity\CarAnticipoDetalle();
                            $arAnticipoDetalle->setAnticipoRel($arAnticipo);
                            $arAnticipoDetalle->setCuentaCobrarRel($arCuentaCobrar);
                            $arAnticipoDetalle->setValor($arrControles['TxtSaldo'.$codigoCuentaCobrar]);
                            $arAnticipoDetalle->setUsuario($arUsuario->getUserName());
                            $arAnticipoDetalle->setNumeroFactura($arCuentaCobrar->getNumeroDocumento());
                            $arAnticipoDetalle->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                            $em->persist($arAnticipoDetalle);
                            $totalAnticipoDetalle = $totalAnticipoDetalle + $arrControles['TxtSaldo'.$codigoCuentaCobrar];
                        } 
                    }
                }
                if ($totalAnticipoDetalle > $arAnticipo->getVrAnticipo()){
                    $objMensaje->Mensaje("error", 'El valor a pagar no puede ser mayor al anticipo', $this);
                    
                } else {
                    $em->flush();
                    $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->liquidar($codigoAnticipo);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
                }
            }
            
            
        }
        return $this->render('BrasaCarteraBundle:Movimientos/Anticipo:detalleNuevo.html.twig', array(
            'arCuentasCobrar' => $arCuentasCobrar,
            'arAnticipo' => $arAnticipo,
            'form' => $form->createView()));
    } 
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        
        $this->strListaDql =  $em->getRepository('BrasaCarteraBundle:CarAnticipo')->listaDQL(
                $session->get('filtroAnticipoNumero'), 
                $session->get('filtroCodigoCliente'),
                $session->get('filtroAnticipoEstadoAutorizado'),
                $session->get('filtroAnticipoEstadoAnulado'),
                $session->get('filtroAnticipoEstadoImpreso'),
                $strFechaDesde = $session->get('filtroDesde'),
                $strFechaHasta = $session->get('filtroHasta'));
    }

    private function filtrar ($form) {       
        $session = $this->getRequest()->getSession();        
        $session->set('filtroAnticipoNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroAnticipoEstadoAutorizado', $form->get('estadoAutorizado')->getData());
        $session->set('filtroAnticipoEstadoAnulado', $form->get('estadoAnulado')->getData());
        $session->set('filtroAnticipoEstadoImpreso', $form->get('estadoImpreso')->getData());
        $session->set('filtroNit', $form->get('TxtNit')->getData()); 
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null){
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d')); 
        }
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
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroDesde') != "") {
            $strFechaDesde = $session->get('filtroDesde');
        }
        if($session->get('filtroHasta') != "") {
            $strFechaHasta = $session->get('filtroHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroCotizacionNumero')))
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))
            ->add('estadoAutorizado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAnticipoEstadoAutorizado')))                
            ->add('estadoAnulado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAnticipoEstadoAnulado')))                    
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
            ->add('estadoImpreso', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAnticipoEstadoImpreso')))                
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
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonAnular['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            //$arrBotonImprimir['disabled'] = true;
            $arrBotonAnular['disabled'] = true;
        }
        if($ar->getEstadoImpreso() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAnular['disabled'] = false;
        }
        
        if($ar->getEstadoAnulado() == 1) {
            $arrBotonAnular['disabled'] = true;
        }
        if($ar->getEstadoImpresoAnticipado() == 1) {
            //$arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAnular['disabled'] = false;
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
        for($col = 'G'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'NIT')                
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'ASESOR')
                    ->setCellValue('F1', 'CUENTA')
                    ->setCellValue('G1', 'FECHA PAGO')
                    ->setCellValue('H1', 'ANTICIPO')
                    ->setCellValue('I1', 'TOTAL')
                    ->setCellValue('J1', 'ANULADO')
                    ->setCellValue('K1', 'AUTORIZADO')
                    ->setCellValue('L1', 'IMPRESO');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arAnticipos = new \Brasa\CarteraBundle\Entity\CarAnticipo();
        $arAnticipos = $query->getResult();

        foreach ($arAnticipos as $arAnticipo) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAnticipo->getCodigoAnticipoPk())
                    ->setCellValue('B' . $i, $arAnticipo->getNumero())
                    ->setCellValue('F' . $i, $arAnticipo->getCuentaRel()->getNombre())
                    ->setCellValue('G' . $i, $arAnticipo->getFechaPago()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arAnticipo->getVrAnticipo())
                    ->setCellValue('I' . $i, $arAnticipo->getVrTotal())
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arAnticipo->getEstadoAnulado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arAnticipo->getEstadoAutorizado()))
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arAnticipo->getEstadoImpreso()));
            if($arAnticipo->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C' . $i, $arAnticipo->getClienteRel()->getNit());
            }
            if($arAnticipo->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arAnticipo->getClienteRel()->getNombreCorto());
            } 
            if($arAnticipo->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('E' . $i, $arAnticipo->getClienteRel()->getAsesorRel()->getNombre());
            } 
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
    
    private function actualizarDetalle($arrControles, $codigoActicipo) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arAnticipo = new \Brasa\CarteraBundle\Entity\CarAnticipo();
        $arAnticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipo')->find($codigoActicipo);
        $intIndice = 0;
        $floTotal = 0;
        $totalAnticipoDetalle = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arAnticipoDetalle = new \Brasa\CarteraBundle\Entity\CarAnticipoDetalle();
                $arAnticipoDetalle = $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->find($intCodigo);
                $floSaldo = $arAnticipoDetalle->getCuentaCobrarRel()->getSaldo();
                $floSaldoAfectar = $arrControles['TxtValor'.$intCodigo] + ($arrControles['TxtVrReteIca'.$intCodigo] + $arrControles['TxtVrReteIva'.$intCodigo] + $arrControles['TxtVrReteFuente'.$intCodigo] - $arrControles['TxtVrDescuento'.$intCodigo] - $arrControles['TxtVrAjustePeso'.$intCodigo]);
                if($floSaldo < $floSaldoAfectar) {
                    $arAnticipoDetalle->setEstadoInconsistencia(1);
                }else {
                    $arAnticipoDetalle->setEstadoInconsistencia(0);
                }
                $arAnticipoDetalle->setVrDescuento($arrControles['TxtVrDescuento'.$intCodigo]);
                $arAnticipoDetalle->setVrAjustePeso($arrControles['TxtVrAjustePeso'.$intCodigo]);
                $arAnticipoDetalle->setVrReteIca($arrControles['TxtVrReteIca'.$intCodigo]);
                $arAnticipoDetalle->setVrReteIva($arrControles['TxtVrReteIva'.$intCodigo]);
                $arAnticipoDetalle->setVrReteFuente($arrControles['TxtVrReteFuente'.$intCodigo]);
                $arAnticipoDetalle->setValor($arrControles['TxtValor'.$intCodigo]);
                $arAnticipoDetalle->setVrPagoDetalle($floSaldoAfectar);
                $em->persist($arAnticipoDetalle);
                $totalAnticipoDetalle = $totalAnticipoDetalle + $arrControles['TxtValor'.$intCodigo];
            }
            $Anticipo = $arAnticipo->getVrAnticipo();
            if ($totalAnticipoDetalle > $Anticipo){
                $objMensaje->Mensaje("error", 'El valor a pagar no puede ser mayor al anticipo', $this);
            } else {
                $em->flush();
                $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->liquidar($codigoActicipo);
            }                    
        }
    }

    
}