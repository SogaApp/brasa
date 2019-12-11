<?php

namespace Brasa\CarteraBundle\Controller\Movimiento;

use Brasa\CarteraBundle\Entity\CarSaldoFavor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\CarteraBundle\Form\Type\CarReciboType;
use Brasa\CarteraBundle\Form\Type\CarReciboDetalleType;

class MovimientoReciboController extends Controller {

    var $strListaDql = "";

    /**
     * @Route("/cartera/movimiento/recibo/lista", name="brs_cartera_movimiento_recibo_listar")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if ($form->get('BtnEliminar')->isClicked()) {
                if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 4)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
                }
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
        if ($codigoRecibo != 0) {
            if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 3)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
            }
            $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo);
        } else {
            if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 2)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
            }
            $arRecibo->setFecha(new \DateTime('now'));
            $arRecibo->setFechaPago(new \DateTime('now'));
        }
        $form = $this->createForm(new CarReciboType, $arRecibo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arRecibo = $form->getData();
            $arrControles = $request->request->All();
            $arCliente = new \Brasa\CarteraBundle\Entity\CarCliente();
            if ($arrControles['txtNit'] != '') {
                $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arrControles['txtNit']));
                if ($arCliente) {
                    $arRecibo->setClienteRel($arCliente);
                    $arRecibo->setAsesorRel($arCliente->getAsesorRel());
                }
            }
            if ($codigoRecibo != 0 && $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->numeroRegistros($codigoRecibo) > 0) {
                if ($arRecibo->getCodigoClienteFk() == $arCliente->getCodigoClientePk()) {
                    $arUsuario = $this->getUser();
                    $arRecibo->setUsuario($arUsuario->getUserName());
                    $em->persist($arRecibo);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_nuevo', array('codigoRecibo' => 0)));
                    } else {
                        if ($codigoRecibo != 0) {
                            return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_listar'));
                        } else {
                            return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $arRecibo->getCodigoReciboPk())));
                        }
                    }
                } else {
                    $objMensaje->Mensaje("error", "Para modificar el cliente debe eliminar los detalles asociados a este registro", $this);
                }
            } else {
                $arUsuario = $this->getUser();
                $arRecibo->setUsuario($arUsuario->getUserName());
                $em->persist($arRecibo);
                $em->flush();
                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_nuevo', array('codigoRecibo' => 0)));
                } else {
                    if ($codigoRecibo != 0) {
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_listar'));
                    } else {
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $arRecibo->getCodigoReciboPk())));
                    }
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
        $arUsuario = $this->getUser();
        $rol = $arUsuario->getRoles();
        if ($form->isValid()) {
            if ($form->get('BtnAutorizar')->isClicked()) {
                if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 5)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
                }
                $arrControles = $request->request->All();
                if ($arRecibo->getEstadoAutorizado() == 0) {
                    $this->actualizarDetalle($arrControles, $codigoRecibo);
                    $arInconsistencias = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => $codigoRecibo, 'estadoInconsistencia' => 1));
                    if ($arInconsistencias == null) {
                        if ($arRecibo->getEstadoAutorizado() == 0) {
                            if ($em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->numeroRegistros($codigoRecibo) > 0) {
                                $arRecibo->setEstadoAutorizado(1);
                                if($arRecibo->getVrSaldoFavor() > 0){
                                    $arSaldoFavor = $em->getRepository("BrasaCarteraBundle:CarSaldoFavor")->findBy(array('codigoReciboFk' => $codigoRecibo));
                                    if($arSaldoFavor){
                                        $arSaldoFavor->setValor($arRecibo->getVrSaldoFavor());
                                        $arSaldoFavor->setSaldo($arRecibo->getVrSaldoFavor());
                                        $em->persist($arSaldoFavor);
                                    }else{
                                    $arSaldoFavor = new CarSaldoFavor();
                                    $arSaldoFavor->setReciboRel($arRecibo);
                                    $arSaldoFavor->setValor($arRecibo->getVrSaldoFavor());
                                    $arSaldoFavor->setSaldo($arRecibo->getVrSaldoFavor());
                                    $arSaldoFavor->setAbono(0);

                                        $em->persist($arSaldoFavor);
                                    }
                                }
                                $arDetallesRecibo = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => $codigoRecibo));
                                foreach ($arDetallesRecibo AS $arDetalleRecibo) {
                                    if($arDetalleRecibo->getCodigoCuentaCobrarFk()){
                                        $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                                        $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arDetalleRecibo->getCodigoCuentaCobrarFk());
                                        $arCuentaCobrar->setSaldo($arCuentaCobrar->getSaldo() - $arDetalleRecibo->getVrPagoDetalle() - $arDetalleRecibo->getVrDescuento() - $arDetalleRecibo->getvrAjustePeso());
                                        $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() + $arDetalleRecibo->getVrPagoDetalle());
                                        $em->persist($arCuentaCobrar);
                                    }else{
                                        $arSaldoFavor = new CarSaldoFavor();
                                        $arSaldoFavor = $em->getRepository("BrasaCarteraBundle:CarSaldoFavor")->find($arDetalleRecibo->getNumeroFactura());
                                        $arSaldoFavor->setSaldo($arSaldoFavor->getSaldo() - $arDetalleRecibo->getVrPagoDetalle() - $arDetalleRecibo->getVrDescuento() - $arDetalleRecibo->getvrAjustePeso());
                                        $arSaldoFavor->setAbono($arSaldoFavor->getAbono() + $arDetalleRecibo->getVrPagoDetalle());
                                        $em->persist($arSaldoFavor);
                                    }
                                }
                                $em->persist($arRecibo);
                                $em->flush();
                            } else {
                                $objMensaje->Mensaje('error', 'Debe adicionar detalles al recibo de caja', $this);
                            }
                        }
                    } else {
                        $objMensaje->Mensaje('error', 'No se puede autorizar, hay inconsistencias', $this);
                    }
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));
                } else {
                    $objMensaje->Mensaje('error', 'No se puede autorizar, ya esta autorizado', $this);
                }
            }
            if ($form->get('BtnDesAutorizar')->isClicked()) {
                if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 6)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
                }
                if ($arRecibo->getEstadoAutorizado() == 1 && $arRecibo->getEstadoImpreso() == 0) {





                    $arRecibo->setEstadoAutorizado(0);
                    $arDetallesRecibo = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => $codigoRecibo));
                    foreach ($arDetallesRecibo AS $arDetalleRecibo) {
                        if($arDetalleRecibo->getCuentaCobrarRel()){
                            $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                            $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arDetalleRecibo->getCodigoCuentaCobrarFk());
                            $arCuentaCobrar->setSaldo($arCuentaCobrar->getSaldo() + $arDetalleRecibo->getVrPagoDetalle() + $arDetalleRecibo->getVrDescuento() + $arDetalleRecibo->getvrAjustePeso());
                            $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() - $arDetalleRecibo->getVrPagoDetalle());
                            $em->persist($arCuentaCobrar);
                        }else{
                            $arSaldoFavor = new \Brasa\CarteraBundle\Entity\CarSaldoFavor();
                            $arSaldoFavor = $em->getRepository("BrasaCarteraBundle:CarSaldoFavor")->find($arDetalleRecibo->getNumeroFactura());
                            $arSaldoFavor->setSaldo($arSaldoFavor->getSaldo() + $arDetalleRecibo->getVrPagoDetalle() + $arDetalleRecibo->getVrDescuento() + $arDetalleRecibo->getvrAjustePeso());
                            $arSaldoFavor->setAbono($arSaldoFavor->getAbono() - $arDetalleRecibo->getVrPagoDetalle());
                            $em->persist($arSaldoFavor);
                        }
                    }
                    if($arRecibo->getVrSaldoFavor() && $arRecibo->getVrSaldoFavor() > 0){
                        $arSaldoFavor = $em->getRepository("BrasaCarteraBundle:CarSaldoFavor")->findOneBy(array('codigoReciboFk' => $codigoRecibo));
                        $em->remove($arSaldoFavor);
                    }
                    $em->persist($arRecibo);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));


                } else {
                    $objMensaje->Mensaje('error', "El recibo debe estar autorizado y no puede estar impreso", $this);
                }
            }
            if ($form->get('BtnAnular')->isClicked()) {
                if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 9)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
                }
                if ($arRecibo->getEstadoImpreso() == 1) {
                    $arRecibo->setEstadoAnulado(1);
                    $arRecibo->setVrTotalAjustePeso(0);
                    $arRecibo->setVrTotalDescuento(0);
                    $arRecibo->setVrTotalReteIca(0);
                    $arRecibo->setVrTotalReteIva(0);
                    $arRecibo->setVrTotalReteFuente(0);
                    $arRecibo->setVrTotal(0);
                    $arDetallesRecibo = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => $codigoRecibo));
                    foreach ($arDetallesRecibo AS $arDetalleRecibo) {
                        $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                        if($arDetalleRecibo->getCuentaCobrarRel()){
                            $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arDetalleRecibo->getCodigoCuentaCobrarFk());
                            $arCuentaCobrar->setSaldo($arCuentaCobrar->getSaldo() + $arDetalleRecibo->getVrPagoDetalle());
                            $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() - $arDetalleRecibo->getVrPagoDetalle());
                        }else{
                            $arSaldoFavor = $em->getRepository("BrasaCarteraBundle:CarSaldoFavor")->find($arDetalleRecibo->getNumeroFactura());
                            $arSaldoFavor->setSaldo($arSaldoFavor->getSaldo() + $arDetalleRecibo->getVrPagoDetalle());
                            $arSaldoFavor->setAbono($arSaldoFavor->getAbono() - $arDetalleRecibo->getVrPagoDetalle());
                        }
                        $arDetalleReciboAnulado = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
                        $arDetalleReciboAnulado = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->find($arDetalleRecibo->getCodigoReciboDetallePk());
                        $arDetalleReciboAnulado->setVrDescuento(0);
                        $arDetalleReciboAnulado->setVrAjustePeso(0);
                        $arDetalleReciboAnulado->setVrReteIca(0);
                        $arDetalleReciboAnulado->setVrReteIva(0);
                        $arDetalleReciboAnulado->setVrReteFuente(0);
                        $arDetalleReciboAnulado->setValor(0);
                        $em->persist($arCuentaCobrar);
                        $em->persist($arDetalleReciboAnulado);
                    }
                    $em->persist($arRecibo);

                    if($arRecibo->getVrSaldoFavor() > 0){
                        $arSaldoFavor = $em->getRepository("BrasaCarteraBundle:CarSaldoFavor")->findOneBy(array('codigoReciboFk' => $arRecibo->getCodigoReciboPk()));
                        if($arSaldoFavor){
                            $arDetallesRecibo = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findOneBy(array('codigoCuentaCobrarFk' => null,'numeroFactura' => $arSaldoFavor->getCodigoSaldoFavorPk()));
                            if($arDetallesRecibo && !$arDetallesRecibo->getReciboRel()->getEstadoAnulado()){
                                $objMensaje->Mensaje("error","El recibo no puede eliminar, tiene un saldo a favor referenciado en otro recibo.",$this);
                            }else{
                                $em->flush();
                            }
                        }
                    }else{
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));
                }
            }
            if ($form->get('BtnDetalleActualizar')->isClicked()) {
                if ($arRecibo->getEstadoAutorizado() == 0) {
                    $arrControles = $request->request->All();
                    $this->actualizarDetalle($arrControles, $codigoRecibo);
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));
                } else {
                    $objMensaje->Mensaje("error", "No se puede actualizar el registro, esta autorizado", $this);
                }
            }
            if ($form->get('BtnDetalleEliminar')->isClicked()) {
                if ($arRecibo->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->eliminarSeleccionados($arrSeleccionados);
                    $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->liquidar($codigoRecibo);
                } else {
                    $objMensaje->Mensaje("error", "No se puede eliminar el registro, esta autorizado", $this);
                }
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));
            }
            if ($form->get('BtnImprimir')->isClicked()) {
                if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 10)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
                }
                if ($arRecibo->getEstadoAutorizado() == 1) {
                    $strResultado = $em->getRepository('BrasaCarteraBundle:CarRecibo')->imprimir($codigoRecibo);
                    if ($strResultado != "") {
                        $objMensaje->Mensaje("error", $strResultado, $this);
                    } else {
                        $objRecibo = new \Brasa\CarteraBundle\Formatos\FormatoRecibo();
                        $objRecibo->Generar($this, $codigoRecibo);
                    }
                } else {
                    $objMensaje->Mensaje("error", "No se puede imprimir el registro, no esta autorizado", $this);
                }
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));
            }
        }
        $arReciboDetalle = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
        $arReciboDetalle = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => $codigoRecibo));
        return $this->render('BrasaCarteraBundle:Movimientos/Recibo:detalle.html.twig', array(
                    'arRecibo' => $arRecibo,
                    'arReciboDetalle' => $arReciboDetalle,
                    'form' => $form->createView(),
                    'rol' => $rol
        ));
    }

    /**
     * @Route("/cartera/movimiento/recibo/detalle/nuevo/{codigoRecibo}/{codigoReciboDetalle}", name="brs_cartera_movimiento_recibo_detalle_nuevo")
     */
    public function detalleNuevoAction($codigoRecibo, $codigoReciboDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator = $this->get('knp_paginator');
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();
        $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo);
        $arCuentasCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
        $arCuentasCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->cuentasCobrar($arRecibo->getCodigoClienteFk());
        $arCuentasCobrar = $paginator->paginate($arCuentasCobrar, $request->query->get('page', 1), 50);
        $form = $this->createFormBuilder()
                ->add('BtnGuardar', 'submit', array('label' => 'Guardar',))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                $intIndice = 0;
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCuentaCobrar) {
                        if ($em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->validarCuenta($codigoCuentaCobrar, $codigoRecibo)) {
                            $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($codigoCuentaCobrar);
                            $arReciboDetalle = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
                            $arReciboDetalle->setReciboRel($arRecibo);
                            $arReciboDetalle->setCuentaCobrarRel($arCuentaCobrar);
                            $arReciboDetalle->setValor($arrControles['TxtSaldo' . $codigoCuentaCobrar]);
                            $arReciboDetalle->setUsuario($arUsuario->getUserName());
                            $arReciboDetalle->setNumeroFactura($arCuentaCobrar->getNumeroDocumento());
                            $arReciboDetalle->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                            $em->persist($arReciboDetalle);
                        }
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->liquidar($codigoRecibo);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaCarteraBundle:Movimientos/Recibo:detalleNuevo.html.twig', array(
                    'arCuentasCobrar' => $arCuentasCobrar,
                    'arRecibo' => $arRecibo,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/movimiento/recibo/detalle/agregar/saldo/favor/{codigoRecibo}", name="brs_cartera_movimiento_recibo_detalle_agregar_saldo_favor")
     */
    public function nuevoSaldoFavorAction(Request $request,$codigoRecibo){
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('vrSaldoFavor', 'integer', array('label'  => 'Saldo a favor',))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        $arRecibo = $em->getRepository("BrasaCarteraBundle:CarRecibo")->find($codigoRecibo);
        if($form->isSubmitted()){
            if ($form->isValid()){
             if($form->get("BtnGuardar")->isClicked()){
                 $vrSaldoFavor = $form->get("vrSaldoFavor")->getData();
                 if($vrSaldoFavor >= 0){
                     $arRecibo->setVrSaldoFavor($vrSaldoFavor);
                     $em->persist($arRecibo);
                     $em->flush();
                     echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                 }
             }
            }
        }
        return $this->render('BrasaCarteraBundle:Movimientos/Recibo:agregarSaldo.html.twig',array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/cartera/movimiento/recibo/detalle/nuevo/saldo/favor/{codigoRecibo}", name="brs_cartera_movimiento_recibo_detalle_nuevo_saldo_favor")
     */
    public function nuevoDesdeSaldo(Request $request,$codigoRecibo){
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label' => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        $arUsuario = $this->get('security.context')->getToken()->getUser();
        $arRecibo = $em->getRepository("BrasaCarteraBundle:CarRecibo")->find($codigoRecibo);
        $arSaldosFavor = $em->getRepository("BrasaCarteraBundle:CarSaldoFavor")->listarSaldosClientes($arRecibo->getClienteRel()->getCodigoClientePk());
        if ($form->isValid()) {
            if($form->get("BtnGuardar")->isclicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigoSaldoFavor) {
                            $arSaldosFavor = $em->getRepository("BrasaCarteraBundle:CarSaldoFavor")->find($codigoSaldoFavor);
                            $arReciboDetalle = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
                            $arReciboDetalle->setReciboRel($arRecibo);
//                            $arReciboDetalle->setCuentaCobrarRel($arCuentaCobrar);
                            $arReciboDetalle->setValor($arSaldosFavor->getSaldo());
                            $arReciboDetalle->setUsuario($arUsuario->getUserName());
                            $arReciboDetalle->setNumeroFactura($arSaldosFavor->getCodigoSaldoFavorPk());
                            $em->persist($arReciboDetalle);
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->liquidar($codigoRecibo);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";

        }
        return $this->render('BrasaCarteraBundle:Movimientos/Recibo:nuevoSaldoFavor.html.twig',array(
            'arSaldosFavor' => $arSaldosFavor,
            'form' => $form->createView()
        ));
    }



    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strListaDql = $em->getRepository('BrasaCarteraBundle:CarRecibo')->listaDQL(
                $session->get('filtroReciboNumero'), 
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroReciboEstadoImpreso'), 
                $session->get('filtroAsesor'));
    }

    private function filtrar($form) {
        $session = $this->getRequest()->getSession();
        $arAsesor = $form->get('asesorRel')->getData();
        if ($arAsesor == null) {
            $codigoAsesor = "";
        } else {
            $codigoAsesor = $arAsesor->getCodigoAsesorPk();
        }
        $session->set('filtroAsesor', $codigoAsesor);
        $session->set('filtroReciboNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroReciboEstadoImpreso', $form->get('estadoImpreso')->getData());
        $session->set('filtroNit', $form->get('TxtNit')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if ($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if ($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            } else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }
        } else {
            $session->set('filtroCodigoCliente', null);
        }
        $arrayPropiedadesAsesor = array(
            'class' => 'BrasaGeneralBundle:GenAsesor',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('a')
                                ->orderBy('a.nombre', 'ASC');
            },
            'property' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'empty_value' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroAsesor')) {
            $arrayPropiedadesAsesor['data'] = $em->getReference("BrasaGeneralBundle:GenAsesor", $session->get('filtroAsesor'));
        }
        $form = $this->createFormBuilder()
                ->add('asesorRel', 'entity', $arrayPropiedadesAsesor)
                ->add('TxtNumero', 'text', array('label' => 'Codigo', 'data' => $session->get('filtroCotizacionNumero')))
                ->add('TxtNit', 'text', array('label' => 'Nit', 'data' => $session->get('filtroNit')))
                ->add('TxtNombreCliente', 'text', array('label' => 'NombreCliente', 'data' => $strNombreCliente))
                ->add('estadoImpreso', 'choice', array('choices' => array('2' => 'TODOS', '1' => 'IMPRESO', '0' => 'SIN IMPRIMIR'), 'data' => $session->get('filtroReciboEstadoImpreso')))
                ->add('BtnEliminar', 'submit', array('label' => 'Eliminar',))
                ->add('BtnExcel', 'submit', array('label' => 'Excel',))
                ->add('BtnFiltrar', 'submit', array('label' => 'Filtrar'))
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
        if ($ar->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonAnular['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
            $arrBotonAnular['disabled'] = true;
        }
        if ($ar->getEstadoImpreso() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAnular['disabled'] = false;
        }
        if ($ar->getEstadoAnulado() == 1) {
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
        for ($col = 'A'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for ($col = 'H'; $col !== 'N'; $col++) {
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
                ->setCellValue('G1', 'ASESOR')
                ->setCellValue('H1', 'FECHA PAGO')
                ->setCellValue('I1', 'TOTAL')
                ->setCellValue('J1', 'ANULADO')
                ->setCellValue('K1', 'AUTORIZADO')
                ->setCellValue('L1', 'IMPRESO');

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
                    ->setCellValue('G' . $i, $arRecibo->getAsesorRel()->getNombre())
                    ->setCellValue('H' . $i, $arRecibo->getFechaPago()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arRecibo->getVrTotal())
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arRecibo->getEstadoAnulado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arRecibo->getEstadoAutorizado()))
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arRecibo->getEstadoImpreso()));
            if ($arRecibo->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('C' . $i, $arRecibo->getClienteRel()->getNit());
            }
            if ($arRecibo->getClienteRel()) {
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
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

    private function actualizarDetalle($arrControles, $codigoRecibo) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        $floTotal = 0;
        if (isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arReciboDetalle = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
                $arReciboDetalle = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->find($intCodigo);
                $floSaldo = 0;
                if(!$arReciboDetalle->getCuentaCobrarRel()){
                    $arReciboDetalle->getNumeroFactura();
                    $arSaldoFavor = $em->getRepository("BrasaCarteraBundle:CarSaldoFavor")->find($arReciboDetalle->getNumeroFactura());
                    $floSaldo = $arSaldoFavor->getSaldo();
                }else{
                    $floSaldo = $arReciboDetalle->getCuentaCobrarRel()->getSaldo();
                }

                $floSaldoAfectar = $arrControles['TxtValor' . $intCodigo] + ($arrControles['TxtVrReteIca' . $intCodigo] + $arrControles['TxtVrReteIva' . $intCodigo] + $arrControles['TxtVrReteFuente' . $intCodigo] - $arrControles['TxtVrDescuento' . $intCodigo] - $arrControles['TxtVrAjustePeso' . $intCodigo]);
                if ($floSaldo < $floSaldoAfectar) {
                    $arReciboDetalle->setEstadoInconsistencia(1);
                } else {
                    $arReciboDetalle->setEstadoInconsistencia(0);
                }
                $arReciboDetalle->setVrDescuento($arrControles['TxtVrDescuento' . $intCodigo]);
                $arReciboDetalle->setVrAjustePeso($arrControles['TxtVrAjustePeso' . $intCodigo]);
                $arReciboDetalle->setVrReteIca($arrControles['TxtVrReteIca' . $intCodigo]);
                $arReciboDetalle->setVrReteIva($arrControles['TxtVrReteIva' . $intCodigo]);
                $arReciboDetalle->setVrReteFuente($arrControles['TxtVrReteFuente' . $intCodigo]);
                $arReciboDetalle->setValor($arrControles['TxtValor' . $intCodigo]);
                $arReciboDetalle->setVrPagoDetalle($floSaldoAfectar);
                $em->persist($arReciboDetalle);
            }
            $em->flush();
            $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->liquidar($codigoRecibo);
        }
    }

}
