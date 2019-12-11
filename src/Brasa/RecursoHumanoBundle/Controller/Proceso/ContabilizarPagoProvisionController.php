<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ContabilizarPagoProvisionController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/proceso/contabilizar/provision", name="brs_rhu_proceso_contabilizar_provision")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 67)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');  
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {                                   
            if ($form->get('BtnContabilizar')->isClicked()) { 
                set_time_limit(0);
                ini_set("memory_limit", -1);                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();                    
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();                    
                    $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobanteProvision());
                    $arProvisionCesantias = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(1);
                    $arProvisionCesantias2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(2);
                    $arProvisionInteresesCesantias = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(3);
                    $arProvisionInteresesCesantias2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(4);
                    $arProvisionPrima = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(5);
                    $arProvisionPrima2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(6);                    
                    $arProvisionVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(7);
                    $arProvisionVacaciones2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(8);                    
                    $arProvisionIndemnizaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(9);
                    $arProvisionIndemnizaciones2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(10);                    
                    $arProvisionRiesgos = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(11);
                    $arProvisionRiesgos2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(12);                                        
                    $arProvisionSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(13);
                    $arProvisionSalud2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(14);                                        
                    $arProvisionPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(15);
                    $arProvisionPension2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(16);                                                            
                    $arProvisionCaja = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(17);
                    $arProvisionCaja2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(18);                                                            
                    $arProvisionIcbf = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(19);
                    $arProvisionIcbf2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(20);                                                            
                    $arProvisionSena = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(21);
                    $arProvisionSena2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(22);                                                            
                    $errorDatos = false;
                    foreach ($arrSeleccionados AS $codigo) {                          
                        $arProvision = new \Brasa\RecursoHumanoBundle\Entity\RhuProvision();
                        $arProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvision')->find($codigo);
                        $tipoEmpleado = $arProvision->getEmpleadoRel()->getEmpleadoTipoRel()->getTipo();
                        $arCentroCosto = $arProvision->getEmpleadoRel()->getCentroCostoContabilidadRel();
                        //$arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();                    
                        //$arCentroCosto = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find(1);                                               
                        if($arProvision->getEstadoContabilizado() == 0) {
                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arProvision->getEmpleadoRel()->getNumeroIdentificacion()));
                            if(count($arTercero) <= 0) {
                                $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                                $arTercero->setCiudadRel($arProvision->getEmpleadoRel()->getCiudadRel());
                                $arTercero->setTipoIdentificacionRel($arProvision->getEmpleadoRel()->getTipoIdentificacionRel());
                                $arTercero->setNumeroIdentificacion($arProvision->getEmpleadoRel()->getNumeroIdentificacion());
                                $arTercero->setNombreCorto($arProvision->getEmpleadoRel()->getNombreCorto());
                                $arTercero->setNombre1($arProvision->getEmpleadoRel()->getNombre1());
                                $arTercero->setNombre2($arProvision->getEmpleadoRel()->getNombre2());
                                $arTercero->setApellido1($arProvision->getEmpleadoRel()->getApellido1());
                                $arTercero->setApellido2($arProvision->getEmpleadoRel()->getApellido2());
                                $arTercero->setDireccion($arProvision->getEmpleadoRel()->getDireccion());
                                $arTercero->setTelefono($arProvision->getEmpleadoRel()->getTelefono());
                                $arTercero->setCelular($arProvision->getEmpleadoRel()->getCelular());
                                $arTercero->setEmail($arProvision->getEmpleadoRel()->getCorreo());
                                $em->persist($arTercero);                                 
                            }  
                            //Cesantias
                            if($arProvision->getVrCesantias() > 0) {   
                                $cuenta = $this->cuenta($arProvisionCesantias, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrCesantias());                            
                                    $arRegistro->setDescripcionContable('PROVISION CESANTIAS');
                                    $em->persist($arRegistro);
                                }  
                                $cuenta = $this->cuenta($arProvisionCesantias2, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrCesantias());                            
                                    $arRegistro->setDescripcionContable('PROVISION CESANTIAS');
                                    $em->persist($arRegistro);
                                }                                
                            }        
                            
                            //Cesantias Intereses
                            if($arProvision->getVrInteresesCesantias() > 0) {   
                                $cuenta = $this->cuenta($arProvisionInteresesCesantias, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrInteresesCesantias());                            
                                    $arRegistro->setDescripcionContable('PROVISION INTERESES CESANTIAS');
                                    $em->persist($arRegistro);
                                }  
                                $cuenta = $this->cuenta($arProvisionInteresesCesantias2, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrInteresesCesantias());                            
                                    $arRegistro->setDescripcionContable('PROVISION INTERESES CESANTIAS');
                                    $em->persist($arRegistro);
                                }                                
                            }         
                            
                            //Prima
                            if($arProvision->getVrPrimas() > 0) {   
                                $cuenta = $this->cuenta($arProvisionPrima, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrPrimas());                            
                                    $arRegistro->setDescripcionContable('PROVISION PRIMAS');
                                    $em->persist($arRegistro);
                                }  
                                $cuenta = $this->cuenta($arProvisionPrima2, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrPrimas());                            
                                    $arRegistro->setDescripcionContable('PROVISION PRIMAS');
                                    $em->persist($arRegistro);
                                }                                
                            } 

                            //Vacaciones
                            if($arProvision->getVrVacaciones() > 0) {   
                                $cuenta = $this->cuenta($arProvisionVacaciones, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrVacaciones());                            
                                    $arRegistro->setDescripcionContable('PROVISION VACACIONES');
                                    $em->persist($arRegistro);
                                }  
                                $cuenta = $this->cuenta($arProvisionVacaciones2, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrVacaciones());                            
                                    $arRegistro->setDescripcionContable('PROVISION VACACIONES');
                                    $em->persist($arRegistro);
                                }                                
                            }    
                            
                            //Indemnizaciones
                            if($arProvision->getVrIndemnizacion() > 0) {   
                                $cuenta = $this->cuenta($arProvisionIndemnizaciones, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrIndemnizacion());                            
                                    $arRegistro->setDescripcionContable('PROVISION INDEMNIZACION');
                                    $em->persist($arRegistro);
                                }  
                                $cuenta = $this->cuenta($arProvisionIndemnizaciones2, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrIndemnizacion());                            
                                    $arRegistro->setDescripcionContable('PROVISION INDEMNIZACION');
                                    $em->persist($arRegistro);
                                }                                
                            } 
                            
                            //Pension
                            if($arProvision->getVrPension() > 0) {   
                                $arTerceroPension = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arProvision->getContratoRel()->getEntidadPensionRel()->getNit()));
                                if($arTerceroPension) {
                                    $cuenta = $this->cuenta($arProvisionPension, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable); 
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroPension);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrPension());                            
                                        $arRegistro->setDescripcionContable('PROVISION PENSION');
                                        $em->persist($arRegistro);
                                    }  
                                    $cuenta = $this->cuenta($arProvisionPension2, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroPension);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrPension());                            
                                        $arRegistro->setDescripcionContable('PROVISION PENSION');
                                        $em->persist($arRegistro);
                                    }                                 
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad de pension " . $arProvision->getContratoRel()->getEntidadPensionRel()->getNombre() . " Nit: " . $arProvision->getContratoRel()->getEntidadPensionRel()->getNit(), $this);
                                    break 1;
                                }                                                                 
                            }                             
                            
                            //Salud
                            if($arProvision->getVrSalud() > 0) {   
                                $arTerceroSalud = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arProvision->getContratoRel()->getEntidadSaludRel()->getNit()));
                                if($arTerceroSalud) {
                                    $cuenta = $this->cuenta($arProvisionSalud, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);     
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroSalud);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrSalud());                            
                                        $arRegistro->setDescripcionContable('PROVISION SALUD');
                                        $em->persist($arRegistro);
                                    }  
                                    $cuenta = $this->cuenta($arProvisionSalud2, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroSalud);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrSalud());                            
                                        $arRegistro->setDescripcionContable('PROVISION SALUD');
                                        $em->persist($arRegistro);
                                    }                                     
                                }  else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad de salud " . $arProvision->getContratoRel()->getEntidadSaludRel()->getNombre() . " Nit: " . $arProvision->getContratoRel()->getEntidadSaludRel()->getNit(), $this);
                                    break 1;
                                }                                                               
                            }                             
                            
                            //Riesgos
                            if($arProvision->getVrRiesgos() > 0) {  
                                $arEntidadRiesgos = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional();
                                $arEntidadRiesgos = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->find($arConfiguracion->getCodigoEntidadRiesgoFk());
                                $arTerceroRiesgos = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arEntidadRiesgos->getNit()));
                                if($arTerceroRiesgos) {
                                    $cuenta = $this->cuenta($arProvisionRiesgos, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);   
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroRiesgos);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrRiesgos());                            
                                        $arRegistro->setDescripcionContable('PROVISION RIESGOS');
                                        $em->persist($arRegistro);
                                    }  
                                    $cuenta = $this->cuenta($arProvisionRiesgos2, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroRiesgos);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrRiesgos());                            
                                        $arRegistro->setDescripcionContable('PROVISION RIESGOS');
                                        $em->persist($arRegistro);
                                    }                                     
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad de riesgos " . $arEntidadRiesgos->getNombre() . " Nit: " . $arEntidadRiesgos->getNit(), $this);
                                    break 1;
                                }                                              
                            }                             
                            
                            //Caja
                            if($arProvision->getVrCaja() > 0) {
                                $arTerceroCaja = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arProvision->getContratoRel()->getEntidadCajaRel()->getNit()));
                                if($arTerceroCaja) {
                                    $cuenta = $this->cuenta($arProvisionCaja, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);  
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroCaja);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrCaja());                            
                                        $arRegistro->setDescripcionContable('PROVISION CAJA');
                                        $em->persist($arRegistro);
                                    }  
                                    $cuenta = $this->cuenta($arProvisionCaja2, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroCaja);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrCaja());                            
                                        $arRegistro->setDescripcionContable('PROVISION CAJA');
                                        $em->persist($arRegistro);
                                    }                                     
                                }  else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad de caja " . $arProvision->getContratoRel()->getEntidadCajaRel()->getNombre() . " Nit: " . $arProvision->getContratoRel()->getEntidadCajaRel()->getNit(), $this);
                                    break 1;
                                }                                                             
                            }                             

                            //Sena
                            if($arProvision->getVrSena() > 0) {   
                                $arTerceroSena = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arConfiguracion->getNitSena()));
                                if($arTerceroSena) {
                                    $cuenta = $this->cuenta($arProvisionSena, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable); 
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroSena);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrSena());                            
                                        $arRegistro->setDescripcionContable('PROVISION SENA');
                                        $em->persist($arRegistro);
                                    }  
                                    $cuenta = $this->cuenta($arProvisionSena2, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroSena);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrSena());                            
                                        $arRegistro->setDescripcionContable('PROVISION SENA');
                                        $em->persist($arRegistro);
                                    }                                     
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad sena ", $this);
                                    break 1;
                                }                                                               
                            }                             
                            
                            //Icbf
                            if($arProvision->getVrIcbf() > 0) {   
                                $arTerceroIcbf = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arConfiguracion->getNitIcbf()));
                                if($arTerceroIcbf) {
                                    $cuenta = $this->cuenta($arProvisionIcbf, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable); 
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroIcbf);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrIcbf());                            
                                        $arRegistro->setDescripcionContable('PROVISION ICBF');
                                        $em->persist($arRegistro);
                                    }  
                                    $cuenta = $this->cuenta($arProvisionIcbf2, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroIcbf);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrIcbf());                            
                                        $arRegistro->setDescripcionContable('PROVISION ICBF');
                                        $em->persist($arRegistro);
                                    }                                     
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad icbf ", $this);
                                    break 1;
                                }                               
                            }                             
                            
                            $arProvision->setEstadoContabilizado(1);                                
                            if($errorDatos == false) {
                                $em->persist($arProvision);                                                            
                            }                            
                        }
                    }
                    if($errorDatos == false) {
                        $em->flush();                        
                    }
                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_provision'));
            }   
                        
        }             
        $arProvisiones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:provision.html.twig', array(
            'arProvisiones' => $arProvisiones,
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
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvision')->pendientesContabilizarDql();  
    }         
   
    private function cuenta($arConfiguracion, $tipoEmpleado) {
        $cuenta = "";
        if($tipoEmpleado == 1) {
            $cuenta = $arConfiguracion->getCodigoCuentaFk();
        }        
        if($tipoEmpleado == 2) {
            $cuenta = $arConfiguracion->getCodigoCuentaOperacionFk();
        }        
        if($tipoEmpleado == 3) {
            $cuenta = $arConfiguracion->getCodigoCuentaComercialFk();
        }                
        return $cuenta;
    }        
      
}
