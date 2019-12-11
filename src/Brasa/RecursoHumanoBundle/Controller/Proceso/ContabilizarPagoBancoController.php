<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class ContabilizarPagoBancoController extends Controller
{
    var $strDqlLista = "";

    /**
     * @Route("/rhu/proceso/contabilizar/pago/banco/", name="brs_rhu_proceso_contabilizar_pago_banco")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 70)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if ($form->get('BtnContabilizar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);

                    $arCuentaPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(13);
                    $arCuentaSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(14);
                    $arCuentaRiesgos = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(15);
                    $arCuentaParafiscales = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(16);
                    
                    $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();
                    $arComprobanteContable =$em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobantePagoBanco());
                    $arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();
                    $arCentroCosto =$em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find(1);
                    foreach ($arrSeleccionados AS $codigo) {
                        $arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();                        
                        $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigo);
                        $arPagoBancoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                        $arPagoBancoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array('codigoPagoBancoFk' => $codigo));
                        if($arPagoBanco->getCodigoPagoBancoTipoFk() != 4) {
                            foreach ($arPagoBancoDetalles as $arPagoBancoDetalle) {
                                if($arPagoBancoDetalle->getVrPago() > 0) {                                
                                    $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPagoBancoDetalle->getEmpleadoRel()->getNumeroIdentificacion()));
                                    if(!$arTercero) {
                                        $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                                        $arTercero->setCiudadRel($arPagoBancoDetalle->getEmpleadoRel()->getCiudadRel());
                                        $arTercero->setTipoIdentificacionRel($arPagoBancoDetalle->getEmpleadoRel()->getTipoIdentificacionRel());
                                        $arTercero->getDigitoVerificacion($arPagoBancoDetalle->getEmpleadoRel()->getDigitoVerificacion());
                                        $arTercero->setNumeroIdentificacion($arPagoBancoDetalle->getEmpleadoRel()->getNumeroIdentificacion());
                                        $arTercero->setNombreCorto($arPagoBancoDetalle->getEmpleadoRel()->getNombreCorto());
                                        $arTercero->setNombre1($arPagoBancoDetalle->getEmpleadoRel()->getNombre1());
                                        $arTercero->setNombre2($arPagoBancoDetalle->getEmpleadoRel()->getNombre2());
                                        $arTercero->setApellido1($arPagoBancoDetalle->getEmpleadoRel()->getApellido1());
                                        $arTercero->setApellido2($arPagoBancoDetalle->getEmpleadoRel()->getApellido2());
                                        $arTercero->setDireccion($arPagoBancoDetalle->getEmpleadoRel()->getDireccion());
                                        $arTercero->setTelefono($arPagoBancoDetalle->getEmpleadoRel()->getTelefono());
                                        $arTercero->setCelular($arPagoBancoDetalle->getEmpleadoRel()->getCelular());
                                        $arTercero->setEmail($arPagoBancoDetalle->getEmpleadoRel()->getCorreo());
                                        $em->persist($arTercero);
                                    }
                                    $docRerefencia = $arPagoBanco->getCodigoPagoBancoPk();
                                    if($arPagoBancoDetalle->getCodigoVacacionFk()) {
                                        $docRerefencia = $arPagoBancoDetalle->getCodigoVacacionFk();
                                    }
                                    if($arPagoBancoDetalle->getCodigoLiquidacionFk()) {
                                        $docRerefencia = $arPagoBancoDetalle->getCodigoVacacionFk();
                                    }
                                    if($arPagoBancoDetalle->getCodigoPagoFk()) {
                                        $docRerefencia = $arPagoBancoDetalle->getPagoRel()->getNumero();
                                    }                                    
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arPagoBanco->getPagoBancoTipoRel()->getCodigoCuentaFk());
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arPagoBanco->getCodigoPagoBancoPk());
                                    $arRegistro->setNumeroReferencia($docRerefencia);
                                    $arRegistro->setFecha($arPagoBanco->getFecha());
                                    $arRegistro->setDebito($arPagoBancoDetalle->getVrPago());
                                    $arRegistro->setDescripcionContable('PAGO');
                                    $em->persist($arRegistro);                                    
                                   

                                    //Banco
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $codigoCuenta = $arPagoBanco->getCuentaRel()->getCodigoCuentaFk();
                                    $arCuentaBanco = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuenta);
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCuentaRel($arCuentaBanco);
                                    $arRegistro->setNumero($arPagoBanco->getCodigoPagoBancoPk());
                                    $arRegistro->setNumeroReferencia($docRerefencia);
                                    $arRegistro->setFecha($arPagoBancoDetalle->getPagoBancoRel()->getFechaAplicacion());
                                    $arRegistro->setCredito($arPagoBancoDetalle->getVrPago());
                                    $arRegistro->setDescripcionContable('');
                                    $em->persist($arRegistro);
                                }
                            }                            
                        } else {
                            foreach ($arPagoBancoDetalles as $arPagoBancoDetalle) {
                                if($arPagoBancoDetalle->getVrPago() > 0) {                                                                   
                                    //PENSION
                                    $dql   = "SELECT a.codigoEntidadPensionFk, SUM(a.cotizacionPension+a.aportesFondoSolidaridadPensionalSolidaridad+a.aportesFondoSolidaridadPensionalSubsistencia) as pension FROM BrasaRecursoHumanoBundle:RhuSsoAporte a "
                                            . "WHERE a.codigoPeriodoDetalleFk = " . $arPagoBancoDetalle->getCodigoPeriodoDetalleFk() . " GROUP BY a.codigoEntidadPensionFk";
                                    $query = $em->createQuery($dql);
                                    $arrayResultado = $query->getResult();                                    
                                    foreach ($arrayResultado as $detalle) {
                                        if($detalle['pension'] > 0) {                                            
                                            $arEntidadPension = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
                                            $arEntidadPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->find($detalle['codigoEntidadPensionFk']);
                                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arEntidadPension->getNit()));

                                            $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                            $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arCuentaPension->getCodigoCuentaFk());
                                            $arRegistro->setComprobanteRel($arComprobanteContable);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setNumero($arPagoBanco->getCodigoPagoBancoPk());
                                            $arRegistro->setNumeroReferencia($arPagoBanco->getCodigoPagoBancoPk());
                                            $arRegistro->setFecha($arPagoBanco->getFecha());
                                            $arRegistro->setDebito($detalle['pension']);
                                            $arRegistro->setDescripcionContable('SS ENTIDAD PENSION');
                                            $em->persist($arRegistro);                                            
                                        }
                                    }       

                                    //SALUD
                                    $dql   = "SELECT a.codigoEntidadSaludFk, SUM(a.cotizacionSalud) as salud FROM BrasaRecursoHumanoBundle:RhuSsoAporte a "
                                            . "WHERE a.codigoPeriodoDetalleFk = " . $arPagoBancoDetalle->getCodigoPeriodoDetalleFk() . " GROUP BY a.codigoEntidadSaludFk";
                                    $query = $em->createQuery($dql);
                                    $arrayResultado = $query->getResult();                                    
                                    foreach ($arrayResultado as $detalle) {
                                        if($detalle['salud'] > 0) {                                            
                                            $arEntidadSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
                                            $arEntidadSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->find($detalle['codigoEntidadSaludFk']);
                                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arEntidadSalud->getNit()));

                                            $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                            $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arCuentaSalud->getCodigoCuentaFk());
                                            $arRegistro->setComprobanteRel($arComprobanteContable);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setNumero($arPagoBanco->getCodigoPagoBancoPk());
                                            $arRegistro->setNumeroReferencia($arPagoBanco->getCodigoPagoBancoPk());
                                            $arRegistro->setFecha($arPagoBanco->getFecha());
                                            $arRegistro->setDebito($detalle['salud']);
                                            $arRegistro->setDescripcionContable('SS ENTIDAD SALUD');
                                            $em->persist($arRegistro);                                            
                                        }
                                    }

                                    //RIESGOS
                                    $dql   = "SELECT a.codigoEntidadRiesgoFk, SUM(a.cotizacionRiesgos) as riesgo FROM BrasaRecursoHumanoBundle:RhuSsoAporte a "
                                            . "WHERE a.codigoPeriodoDetalleFk = " . $arPagoBancoDetalle->getCodigoPeriodoDetalleFk() . " GROUP BY a.codigoEntidadRiesgoFk";
                                    $query = $em->createQuery($dql);
                                    $arrayResultado = $query->getResult();                                    
                                    foreach ($arrayResultado as $detalle) {
                                        if($detalle['riesgo'] > 0) {                                            
                                            $arEntidadRiesgo = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional();
                                            $arEntidadRiesgo = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->find($detalle['codigoEntidadRiesgoFk']);
                                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arEntidadRiesgo->getNit()));

                                            $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                            $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arCuentaRiesgos->getCodigoCuentaFk());
                                            $arRegistro->setComprobanteRel($arComprobanteContable);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setNumero($arPagoBanco->getCodigoPagoBancoPk());
                                            $arRegistro->setNumeroReferencia($arPagoBanco->getCodigoPagoBancoPk());
                                            $arRegistro->setFecha($arPagoBanco->getFecha());
                                            $arRegistro->setDebito($detalle['riesgo']);
                                            $arRegistro->setDescripcionContable('SS ENTIDAD RIESGOS');
                                            $em->persist($arRegistro);                                            
                                        }
                                    }                                    

                                    //PARAFISCALES (CAJA)
                                    $dql   = "SELECT a.codigoEntidadCajaFk, SUM(a.cotizacionCaja+a.cotizacionSena+a.cotizacionIcbf) as caja FROM BrasaRecursoHumanoBundle:RhuSsoAporte a "
                                            . "WHERE a.codigoPeriodoDetalleFk = " . $arPagoBancoDetalle->getCodigoPeriodoDetalleFk() . " GROUP BY a.codigoEntidadCajaFk";
                                    $query = $em->createQuery($dql);
                                    $arrayResultado = $query->getResult();                                    
                                    foreach ($arrayResultado as $detalle) {
                                        if($detalle['caja'] > 0) {                                            
                                            $arEntidadCaja = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja();
                                            $arEntidadCaja = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadCaja')->find($detalle['codigoEntidadCajaFk']);
                                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arEntidadCaja->getNit()));

                                            $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                            $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arCuentaParafiscales->getCodigoCuentaFk());
                                            $arRegistro->setComprobanteRel($arComprobanteContable);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setNumero($arPagoBanco->getCodigoPagoBancoPk());
                                            $arRegistro->setNumeroReferencia($arPagoBanco->getCodigoPagoBancoPk());
                                            $arRegistro->setFecha($arPagoBanco->getFecha());
                                            $arRegistro->setDebito($detalle['caja']);
                                            $arRegistro->setDescripcionContable('SS PARAFISCALES');
                                            $em->persist($arRegistro);                                            
                                        }
                                    }                                      
                                    
                                    //Banco
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $codigoCuenta = $arPagoBanco->getCuentaRel()->getCodigoCuentaFk();
                                    $arCuentaBanco = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuenta);
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCuentaRel($arCuentaBanco);
                                    $arRegistro->setNumero($arPagoBanco->getCodigoPagoBancoPk());
                                    $arRegistro->setNumeroReferencia($arPagoBanco->getCodigoPagoBancoPk());
                                    $arRegistro->setFecha($arPagoBancoDetalle->getPagoBancoRel()->getFechaAplicacion());
                                    $arRegistro->setCredito($arPagoBancoDetalle->getVrPago());
                                    $arRegistro->setDescripcionContable('');
                                    $em->persist($arRegistro);
                                }
                            }                            
                        }

                        $arPagoBanco->setEstadoContabilizado(1);
                        $em->persist($arPagoBanco);
                    }
                    $em->flush();
                }
            }
        }

        $arPagosBanco = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:pagoBanco.html.twig', array(
            'arPagosBanco' => $arPagosBanco,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/proceso/descontabilizar/pago/banco/", name="brs_rhu_proceso_descontabilizar_pago_banco")
     */
    public function descontabilizarPagoBancoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('pagoDesde', 'number', array('label'  => 'Pago desde'))
            ->add('pagoHasta', 'number', array('label'  => 'Pago hasta'))
            ->add('BtnDescontabilizar', 'submit', array('label'  => 'Descontabilizar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnDescontabilizar')->isClicked()) {
                $intPagoDesde = $form->get('pagoDesde')->getData();
                $intPagoHasta = $form->get('pagoHasta')->getData();
                if($intPagoDesde != "" || $intPagoHasta != "") {
                    $arRegistros = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                    $arRegistros = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->contabilizadosPagoBancoDql($intPagoDesde,$intPagoHasta);
                    foreach ($arRegistros as $codigoRegistro) {
                        $arRegistro = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                        $arRegistro = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->find($codigoRegistro);
                        $arRegistro->setEstadoContabilizado(0);
                        $em->persist($arRegistro);
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $objMensaje->Mensaje('error', 'Debe seleccionar un filtro', $this);
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:descontabilizarPagoBanco.html.twig', array(
            'form' => $form->createView()));
    }

    private function formularioLista() {
        $form = $this->createFormBuilder()
            ->add('BtnContabilizar', 'submit', array('label'  => 'Contabilizar',))
            ->getForm();
        return $form;
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->pendientesContabilizarDql();
    }
}
