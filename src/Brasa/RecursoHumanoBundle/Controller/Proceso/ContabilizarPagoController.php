<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ContabilizarPagoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/rhu/proceso/contabilizar/pago/", name="brs_rhu_proceso_contabilizar_pago")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 66)) {
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
                    $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion;
                    $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();                    
                    $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobantePagoNomina());                    
                    foreach ($arrSeleccionados AS $codigo) {                                     
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigo);
                        if($arPago->getEstadoContabilizado() == 0) {
                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getEmpleadoRel()->getNumeroIdentificacion()));
                            if(count($arTercero) <= 0) {
                                $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                                $arTercero->setCiudadRel($arPago->getEmpleadoRel()->getCiudadRel());
                                $arTercero->setTipoIdentificacionRel($arPago->getEmpleadoRel()->getTipoIdentificacionRel());
                                $arTercero->setNumeroIdentificacion($arPago->getEmpleadoRel()->getNumeroIdentificacion());
                                $arTercero->setNombreCorto($arPago->getEmpleadoRel()->getNombreCorto());
                                $arTercero->setNombre1($arPago->getEmpleadoRel()->getNombre1());
                                $arTercero->setNombre2($arPago->getEmpleadoRel()->getNombre2());
                                $arTercero->setApellido1($arPago->getEmpleadoRel()->getApellido1());
                                $arTercero->setApellido2($arPago->getEmpleadoRel()->getApellido2());
                                $arTercero->setDireccion($arPago->getEmpleadoRel()->getDireccion());
                                $arTercero->setTelefono($arPago->getEmpleadoRel()->getTelefono());
                                $arTercero->setCelular($arPago->getEmpleadoRel()->getCelular());
                                $arTercero->setEmail($arPago->getEmpleadoRel()->getCorreo());
                                $em->persist($arTercero);                                 
                            }  
                            $this->contabilizarPagoNomina($codigo,$arComprobanteContable, $arPago->getEmpleadoRel()->getCentroCostoContabilidadRel(), $arTercero,$arPago, $arConfiguracionNomina);  
                            $arPago->setEstadoContabilizado(1);                                
                            $em->persist($arPago);                            
                        }
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_pago'));
            }            
        }       
                
        $arPagos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:pago.html.twig', array(
            'arPagos' => $arPagos,
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
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->pendientesContabilizarDql();  
    }         
    
    private function contabilizarPagoNomina($codigo,$arComprobanteContable,$arCentroCosto,$arTercero,$arPago, $arConfiguracionNomina) {
        $em = $this->getDoctrine()->getManager();
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigo));
        foreach ($arPagoDetalles as $arPagoDetalle) {
            if($arPagoDetalle->getVrPago() > 0) {
                $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                 
                if($arPago->getEmpleadoRel()->getEmpleadoTipoRel()->getTipo() == 1) {
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arPagoDetalle->getPagoConceptoRel()->getCodigoCuentaFk());                            
                } 
                if($arPago->getEmpleadoRel()->getEmpleadoTipoRel()->getTipo() == 2) {
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arPagoDetalle->getPagoConceptoRel()->getCodigoCuentaOperacionFk());                            
                }
                if($arPago->getEmpleadoRel()->getEmpleadoTipoRel()->getTipo() == 3) {
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arPagoDetalle->getPagoConceptoRel()->getCodigoCuentaComercialFk());                            
                }
                $arRegistro->setComprobanteRel($arComprobanteContable);
                if($arCuenta->getExigeCentroCostos() == 1) {
                    $arRegistro->setCentroCostoRel($arCentroCosto);    
                }                
                $arRegistro->setCuentaRel($arCuenta);
                $arRegistro->setTerceroRel($arTercero);
                $arRegistro->setNumero($arPago->getNumero());
                $arRegistro->setNumeroReferencia($arPago->getNumero());
                $arRegistro->setFecha($arPago->getFechaHasta());
                if($arPagoDetalle->getPagoConceptoRel()->getTipoCuenta() == 1) {
                    $arRegistro->setDebito($arPagoDetalle->getVrPago());
                } else {
                    $arRegistro->setCredito($arPagoDetalle->getVrPago());
                }
                $arRegistro->setDescripcionContable($arPagoDetalle->getPagoConceptoRel()->getNombre());
                $em->persist($arRegistro);                 
            }                                           
        }
        //Nomina por pagar
        if($arPago->getVrNeto() > 0) {
            if($arConfiguracionNomina->getCuentaNominaPagar() != '') {           
                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionNomina->getCuentaNominaPagar()); //estaba 250501                           
                if($arCuenta) {
                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                    $arRegistro->setComprobanteRel($arComprobanteContable);
                    //$arRegistro->setCentroCostoRel($arCentroCosto);
                    $arRegistro->setCuentaRel($arCuenta);
                    $arRegistro->setTerceroRel($arTercero);
                    $arRegistro->setNumero($arPago->getNumero());
                    $arRegistro->setNumeroReferencia($arPago->getNumero());
                    $arRegistro->setFecha($arPago->getFechaHasta());
                    $arRegistro->setCredito($arPago->getVrNeto());                            
                    $arRegistro->setDescripcionContable('NOMINA POR PAGAR');
                    $em->persist($arRegistro);
                }            
            }            
        }        
    }    
 
    /**
     * @Route("/rhu/proceso/descontabilizar/pago/", name="brs_rhu_proceso_descontabilizar_pago")
     */
    
    public function descontabilizarPagoNominaAction() {
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
                    $arRegistros = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                    $arRegistros = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->contabilizadosPagoNominaDql($intNumeroDesde,$intNumeroHasta,$dateFechaDesde,$dateFechaHasta);  
                    foreach ($arRegistros as $codigoRegistro) {
                        $arRegistro = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arRegistro = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoRegistro);
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
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:descontabilizarPagoNomina.html.twig', array(
            'form' => $form->createView()));
    }
    
}
