<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ContabilizarLiquidacionController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/rhu/proceso/contabilizar/liquidacion/", name="brs_rhu_proceso_contabilizar_liquidacion")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 68)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');        
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
                    $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobanteLiquidacion());
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(1);
                    $codigoCuentaCesantias = $arCuenta->getCodigoCuentaFk();
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(2);
                    $codigoCuentaInteresesCesantias = $arCuenta->getCodigoCuentaFk();
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(3);
                    $codigoCuentaPrimas = $arCuenta->getCodigoCuentaFk();
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(4);
                    $codigoCuentaVacaciones = $arCuenta->getCodigoCuentaFk();
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(6);
                    $codigoCuentaIndemnizacion = $arCuenta->getCodigoCuentaFk();
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(5);
                    $codigoCuentaLiquidacion = $arCuenta->getCodigoCuentaFk();
                    foreach ($arrSeleccionados AS $codigo) {                                     
                        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
                        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigo);
                        if($arLiquidacion->getEstadoContabilizado() == 0) {                            
                            $arCentroCosto = $arLiquidacion->getEmpleadoRel()->getCentroCostoContabilidadRel();                                                       
                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arLiquidacion->getEmpleadoRel()->getNumeroIdentificacion()));
                            if(!$arTercero) {
                                $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                                $arTercero->setCiudadRel($arLiquidacion->getEmpleadoRel()->getCiudadRel());
                                $arTercero->setTipoIdentificacionRel($arLiquidacion->getEmpleadoRel()->getTipoIdentificacionRel());
                                $arTercero->setNumeroIdentificacion($arLiquidacion->getEmpleadoRel()->getNumeroIdentificacion());
                                $arTercero->setNombreCorto($arLiquidacion->getEmpleadoRel()->getNombreCorto());
                                $arTercero->setNombre1($arLiquidacion->getEmpleadoRel()->getNombre1());
                                $arTercero->setNombre2($arLiquidacion->getEmpleadoRel()->getNombre2());
                                $arTercero->setApellido1($arLiquidacion->getEmpleadoRel()->getApellido1());
                                $arTercero->setApellido2($arLiquidacion->getEmpleadoRel()->getApellido2());
                                $arTercero->setDireccion($arLiquidacion->getEmpleadoRel()->getDireccion());
                                $arTercero->setTelefono($arLiquidacion->getEmpleadoRel()->getTelefono());
                                $arTercero->setCelular($arLiquidacion->getEmpleadoRel()->getCelular());
                                $arTercero->setEmail($arLiquidacion->getEmpleadoRel()->getCorreo());
                                $em->persist($arTercero);                                 
                            }                            

                            //Cesantias
                            if($arLiquidacion->getVrCesantias() > 0) {
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaCesantias); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    $arRegistro->setDebito($arLiquidacion->getVrCesantias());                            
                                    $arRegistro->setDescripcionContable('CESANTIAS');
                                    $em->persist($arRegistro);
                                }             
                            }

                            //Intereses cesantias
                            if($arLiquidacion->getVrInteresesCesantias() > 0) {                                    
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaInteresesCesantias); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    $arRegistro->setDebito($arLiquidacion->getVrInteresesCesantias());                            
                                    $arRegistro->setDescripcionContable('INTERESES CESANTIAS');
                                    $em->persist($arRegistro);
                                }             
                            }  

                            //Primas
                            if($arLiquidacion->getVrPrima() > 0) {                                    
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaPrimas); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    $arRegistro->setDebito($arLiquidacion->getVrPrima());                            
                                    $arRegistro->setDescripcionContable('PRIMAS');
                                    $em->persist($arRegistro);
                                }             
                            } 

                            //Vacaciones
                            if($arLiquidacion->getVrVacaciones() > 0) {                                    
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaVacaciones); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    $arRegistro->setDebito($arLiquidacion->getVrVacaciones());                            
                                    $arRegistro->setDescripcionContable('VACACIONES');
                                    $em->persist($arRegistro);
                                }             
                            }        

                            //Indemnizacion
                            if($arLiquidacion->getVrIndemnizacion() > 0) {                                    
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaIndemnizacion); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    //$arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    $arRegistro->setDebito($arLiquidacion->getVrIndemnizacion());                            
                                    $arRegistro->setDescripcionContable('VACACIONES');
                                    $em->persist($arRegistro);
                                }             
                            }                                 

                            //Adicionales
                            $arLiquidacionAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
                            $arLiquidacionAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->findBy(array('codigoLiquidacionFk' => $codigo));
                            foreach ($arLiquidacionAdicionales as $arLiquidacionAdicional) {
                                $arCuenta = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arLiquidacionAdicional->getPagoConceptoRel()->getCodigoCuentaFk()); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    if($arCuenta->getExigeCentroCostos()) {
                                        $arRegistro->setCentroCostoRel($arCentroCosto);                                        
                                    }                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    if($arLiquidacionAdicional->getVrBonificacion() > 0) {
                                        $arRegistro->setDebito($arLiquidacionAdicional->getVrBonificacion());    
                                    } else {
                                        $arRegistro->setCredito($arLiquidacionAdicional->getVrDeduccion());
                                    }                                                                    
                                    $arRegistro->setDescripcionContable($arLiquidacionAdicional->getPagoConceptoRel()->getNombre());
                                    $em->persist($arRegistro);
                                }                                    
                            }                                

                            //Liquidacion
                            if($arLiquidacion->getVrTotal() > 0) {                                    
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaLiquidacion); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    $arRegistro->setCredito($arLiquidacion->getVrTotal());                            
                                    $arRegistro->setDescripcionContable('LIQUIDACION POR PAGAR');
                                    $em->persist($arRegistro);
                                }             
                            }

                            $arLiquidacion->setEstadoContabilizado(1);                                
                            $em->persist($arLiquidacion);                                 
                                                         
                        }
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_liquidacion'));
            }            
        }       
                
        $arLiquidaciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:liquidacion.html.twig', array(
            'arLiquidaciones' => $arLiquidaciones,
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
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->pendientesContabilizarDql();  
    }                 
    
    /**
     * @Route("/rhu/proceso/descontabilizar/liquidacion/", name="brs_rhu_proceso_descontabilizar_liquidacion")
     */    
    public function descontabilizarLiquidacionAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession(); 
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('numeroDesde', 'number', array('label'  => 'Numero desde'))
            ->add('numeroHasta', 'number', array('label'  => 'Numero hasta'))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                                            
            ->add('BtnDescontabilizar', 'submit', array('label'  => 'Descontabilizar',))    
            ->getForm();
        $form->handleRequest($request);        
        if ($form->isValid()) {             
            if ($form->get('BtnDescontabilizar')->isClicked()) {
                $intNumeroDesde = $form->get('numeroDesde')->getData();
                $intNumeroHasta = $form->get('numeroHasta')->getData();
                $dateFechaDesde = $form->get('fechaDesde')->getData();
                $dateFechaHasta = $form->get('fechaHasta')->getData();
                if($intNumeroDesde != "" || $intNumeroHasta != "" || $dateFechaDesde != "" || $dateFechaHasta != "") {
                    $arRegistros = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
                    $arRegistros = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->contabilizadosDql($intNumeroDesde,$intNumeroHasta,$dateFechaDesde,$dateFechaHasta);  
                    foreach ($arRegistros as $codigoRegistro) {
                        $arRegistro = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
                        $arRegistro = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoRegistro);
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
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:descontabilizarLiquidacion.html.twig', array(
            'form' => $form->createView()));
    }    
}
