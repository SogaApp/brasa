<?php
namespace Brasa\TurnoBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GenerarPedidoController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/tur/proceso/generar/pedido/lista", name="brs_tur_proceso_generar_pedido_lista")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();                     
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 3)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }                    
        $paginator  = $this->get('knp_paginator');
        $mensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista($form);
        if ($form->isValid()) {
            $anio = $form->get('anio')->getData();
            $mes = $form->get('mes')->getData();
            $fecha = date_create($anio . "/" . $mes . "/01");            
            $strUltimoDiaMes = date("d",(mktime(0,0,0,$mes+1,1,$anio)-1)); 
            $dateFechaHasta = date_create($anio . "/" . $mes . "/" . $strUltimoDiaMes); 
            $dateFechaDesde = $fecha;
            
            if($request->request->get('OpGenerar')) {                
                $codigoServicio = $request->request->get('OpGenerar');
                $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
                $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio);                
                if($fecha > $arServicio->getFechaGeneracion()) {
                    $arPedidoTipo = $em->getRepository('BrasaTurnoBundle:TurPedidoTipo')->find(2);
                    $arPedidoNuevo = new \Brasa\TurnoBundle\Entity\TurPedido(); 
                    $arUsuario = $this->getUser();
                    $arPedidoNuevo->setUsuario($arUsuario->getUserName());                
                    $arPedidoNuevo->setPedidoTipoRel($arPedidoTipo);
                    $arPedidoNuevo->setClienteRel($arServicio->getClienteRel());
                    $arPedidoNuevo->setSectorRel($arServicio->getSectorRel());
                    $arPedidoNuevo->setFecha(new \DateTime('now'));
                    $arPedidoNuevo->setFechaProgramacion($dateFechaDesde);                                        
                    $em->persist($arPedidoNuevo);                    

                    $arServicioDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                    $arServicioDetalles =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $arServicio->getCodigoServicioPk())); 
                    foreach ($arServicioDetalles as $arServicioDetalle) {
                        $arPedidoDetalleNuevo = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                        $arPedidoDetalleNuevo->setPedidoRel($arPedidoNuevo);

                        $intDiaInicial = 0;
                        $intDiaFinal = 0;

                        if($dateFechaDesde < $arServicioDetalle->getFechaHasta()) {
                            $dateFechaProceso = $dateFechaDesde;
                            if($arServicioDetalle->getFechaDesde() <= $dateFechaHasta) {
                                if($arServicioDetalle->getFechaDesde() > $dateFechaProceso) {
                                    $dateFechaProceso = $arServicioDetalle->getFechaDesde();
                                    if($dateFechaProceso <= $arServicioDetalle->getFechaHasta()) {
                                        $intDiaInicial = $dateFechaProceso->format('j');
                                    }
                                } else {
                                   $intDiaInicial = $dateFechaProceso->format('j'); 
                                }                            
                            } 
                            $dateFechaProceso = $dateFechaHasta;
                            if($dateFechaHasta >= $arServicioDetalle->getFechaDesde()) {
                                if($arServicioDetalle->getFechaHasta() < $dateFechaProceso) {
                                    $dateFechaProceso = $arServicioDetalle->getFechaHasta();
                                    if($dateFechaProceso >= $arServicioDetalle->getFechaHasta()) {
                                        $intDiaFinal =  $dateFechaProceso->format('j');                                
                                    }                                                        
                                } else {
                                    $intDiaFinal =  $dateFechaProceso->format('j');
                                }                            
                            }                        
                        }

                        $arPedidoDetalleNuevo->setDiaDesde($intDiaInicial);
                        $arPedidoDetalleNuevo->setDiaHasta($intDiaFinal); 

                        $strUltimoDiaMes = date("j",(mktime(0,0,0,$dateFechaDesde->format('m')+1,1,$dateFechaDesde->format('Y'))-1));
                        $arPeriodo = new \Brasa\TurnoBundle\Entity\TurPeriodo();
                        if($intDiaInicial != 1 || $intDiaFinal != $strUltimoDiaMes) {
                            $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(2);
                        } else {
                            $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(1);
                        }
                        $arPedidoDetalleNuevo->setPeriodoRel($arPeriodo);
                        $arPedidoDetalleNuevo->setCantidad($arServicioDetalle->getCantidad());
                        $arPedidoDetalleNuevo->setConceptoServicioRel($arServicioDetalle->getConceptoServicioRel());
                        $arPedidoDetalleNuevo->setModalidadServicioRel($arServicioDetalle->getModalidadServicioRel());                    
                        $arPedidoDetalleNuevo->setProyectoRel($arServicioDetalle->getProyectoRel());
                        $arPedidoDetalleNuevo->setGrupoFacturacionRel($arServicioDetalle->getGrupoFacturacionRel());
                        $arPedidoDetalleNuevo->setPuestoRel($arServicioDetalle->getPuestoRel());
                        $arPedidoDetalleNuevo->setPlantillaRel($arServicioDetalle->getPlantillaRel());
                        $arPedidoDetalleNuevo->setServicioDetalleRel($arServicioDetalle);
                        $arPedidoDetalleNuevo->setLunes($arServicioDetalle->getLunes());
                        $arPedidoDetalleNuevo->setMartes($arServicioDetalle->getMartes());
                        $arPedidoDetalleNuevo->setMiercoles($arServicioDetalle->getMiercoles());
                        $arPedidoDetalleNuevo->setJueves($arServicioDetalle->getJueves());
                        $arPedidoDetalleNuevo->setViernes($arServicioDetalle->getViernes());
                        $arPedidoDetalleNuevo->setSabado($arServicioDetalle->getSabado());
                        $arPedidoDetalleNuevo->setDomingo($arServicioDetalle->getDomingo());
                        $arPedidoDetalleNuevo->setFestivo($arServicioDetalle->getFestivo());    
                        $arPedidoDetalleNuevo->setVrPrecioAjustado($arServicioDetalle->getVrPrecioAjustado());
                        $arPedidoDetalleNuevo->setFechaIniciaPlantilla($arServicioDetalle->getFechaIniciaPlantilla());
                        $arPedidoDetalleNuevo->setAjusteProgramacion($arServicioDetalle->getAjusteProgramacion());
                        $arPedidoDetalleNuevo->setAnio($anio);
                        $arPedidoDetalleNuevo->setMes($mes);                        
                        $arPedidoDetalleNuevo->setCompuesto($arServicioDetalle->getCompuesto());
                        if($intDiaInicial != 0 && $intDiaFinal != 0) { 
                            $em->persist($arPedidoDetalleNuevo);                      
                            $arServicioDetalleRecursos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
                            $arServicioDetalleRecursos =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->findBy(array('codigoServicioDetalleFk' => $arServicioDetalle->getCodigoServicioDetallePk())); 
                            foreach ($arServicioDetalleRecursos as $arServicioDetalleRecurso) {
                                $arPedidoDetalleRecursoNuevo = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso();
                                $arPedidoDetalleRecursoNuevo->setPedidoDetalleRel($arPedidoDetalleNuevo);
                                $arPedidoDetalleRecursoNuevo->setRecursoRel($arServicioDetalleRecurso->getRecursoRel());
                                $arPedidoDetalleRecursoNuevo->setPosicion($arServicioDetalleRecurso->getPosicion());
                                $em->persist($arPedidoDetalleRecursoNuevo);
                            }                        
                            
                            if($arServicioDetalle->getCompuesto() == 1) {
                                $arServicioDetallesCompuestos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto();
                                $arServicioDetallesCompuestos = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleCompuesto')->findBy(array('codigoServicioDetalleFk' => $arServicioDetalle->getCodigoServicioDetallePk()));
                                foreach ($arServicioDetallesCompuestos as $arServicioDetalleCompuesto) {
                                    $arPedidoDetalleCompuesto = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto();
                                    $arPedidoDetalleCompuesto->setPedidoDetalleRel($arPedidoDetalleNuevo);
                                    $arPedidoDetalleCompuesto->setModalidadServicioRel($arServicioDetalleCompuesto->getModalidadServicioRel());
                                    $arPedidoDetalleCompuesto->setPeriodoRel($arServicioDetalleCompuesto->getPeriodoRel());
                                    $arPedidoDetalleCompuesto->setConceptoServicioRel($arServicioDetalleCompuesto->getConceptoServicioRel());                                                                                                                                                       
                                    $arPedidoDetalleCompuesto->setDias($arServicioDetalleCompuesto->getDias());
                                    $arPedidoDetalleCompuesto->setLunes($arServicioDetalleCompuesto->getLunes());
                                    $arPedidoDetalleCompuesto->setMartes($arServicioDetalleCompuesto->getMartes());
                                    $arPedidoDetalleCompuesto->setMiercoles($arServicioDetalleCompuesto->getMiercoles());
                                    $arPedidoDetalleCompuesto->setJueves($arServicioDetalleCompuesto->getJueves());
                                    $arPedidoDetalleCompuesto->setViernes($arServicioDetalleCompuesto->getViernes());
                                    $arPedidoDetalleCompuesto->setSabado($arServicioDetalleCompuesto->getSabado());
                                    $arPedidoDetalleCompuesto->setDomingo($arServicioDetalleCompuesto->getDomingo());
                                    $arPedidoDetalleCompuesto->setFestivo($arServicioDetalleCompuesto->getFestivo());                            
                                    $arPedidoDetalleCompuesto->setCantidad($arServicioDetalleCompuesto->getCantidad());
                                    $arPedidoDetalleCompuesto->setVrPrecioAjustado($arServicioDetalleCompuesto->getVrPrecioAjustado());                                                                
                                    $arPedidoDetalleCompuesto->setLiquidarDiasReales($arServicioDetalleCompuesto->getLiquidarDiasReales());                                

                                    $strAnioMes = $arPedidoNuevo->getFechaProgramacion()->format('Y/m/');
                                    $dateFechaDesde = date_create($strAnioMes . "1");
                                    $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFechaDesde->format('m')+1,1,$dateFechaDesde->format('Y'))-1));
                                    $dateFechaHasta = date_create($strAnioMes . $strUltimoDiaMes);
                                    $intDiaInicial = 0;
                                    $intDiaFinal = 0;
                                    if($dateFechaDesde < $arServicioDetalle->getFechaHasta()) {
                                        $dateFechaProceso = $dateFechaDesde;
                                        if($arServicioDetalle->getFechaDesde() <= $dateFechaHasta) {
                                            if($arServicioDetalle->getFechaDesde() > $dateFechaProceso) {
                                                $dateFechaProceso = $arServicioDetalle->getFechaDesde();
                                                if($dateFechaProceso <= $arServicioDetalle->getFechaHasta()) {
                                                    $intDiaInicial = $dateFechaProceso->format('j');
                                                }
                                            } else {
                                               $intDiaInicial = $dateFechaProceso->format('j'); 
                                            }                            
                                        }                         
                                        $dateFechaProceso = $dateFechaHasta;
                                        if($dateFechaHasta >= $arServicioDetalle->getFechaDesde()) {
                                            if($arServicioDetalle->getFechaHasta() < $dateFechaProceso) {
                                                $dateFechaProceso = $arServicioDetalle->getFechaHasta();
                                                if($dateFechaProceso >= $arServicioDetalle->getFechaHasta()) {
                                                    $intDiaFinal =  $dateFechaProceso->format('j');                                
                                                }                                                        
                                            } else {
                                                $intDiaFinal =  $dateFechaProceso->format('j');
                                            }                            
                                        }                            
                                    }

                                    $arPedidoDetalleCompuesto->setDiaDesde($intDiaInicial);
                                    $arPedidoDetalleCompuesto->setDiaHasta($intDiaFinal); 

                                    $strUltimoDiaMes = date("j",(mktime(0,0,0,$dateFechaDesde->format('m')+1,1,$dateFechaDesde->format('Y'))-1));
                                    $arPeriodo = new \Brasa\TurnoBundle\Entity\TurPeriodo();
                                    if($intDiaInicial != 1 || $intDiaFinal != $strUltimoDiaMes) {
                                        $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(2);
                                    } else {
                                        $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(1);
                                    }                            
                                    $arPedidoDetalleCompuesto->setPeriodoRel($arPeriodo);  
                                    $em->persist($arPedidoDetalleCompuesto);
                                }
                            }                            
                            
                        }
                         
                    }                   
                    
                    $arServicioDetallesConceptos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto();
                    $arServicioDetallesConceptos =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalleConcepto')->findBy(array('codigoServicioFk' => $arServicio->getCodigoServicioPk()));                     
                    foreach ($arServicioDetallesConceptos as $arServicioDetalleConcepto) {
                        $arPedidoDetalleConcepto = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto();                        
                        $arPedidoDetalleConcepto->setPedidoRel($arPedidoNuevo);                         
                        $arPedidoDetalleConcepto->setConceptoServicioRel($arServicioDetalleConcepto->getConceptoServicioRel());
                        $arPedidoDetalleConcepto->setPuestoRel($arServicioDetalleConcepto->getPuestoRel());
                        $arPedidoDetalleConcepto->setCantidad($arServicioDetalleConcepto->getCantidad());
                        $arPedidoDetalleConcepto->setPorIva($arServicioDetalleConcepto->getPorIva());
                        $arPedidoDetalleConcepto->setPorBaseIva($arServicioDetalleConcepto->getPorBaseIva());
                        $arPedidoDetalleConcepto->setIva($arServicioDetalleConcepto->getIva());
                        $arPedidoDetalleConcepto->setPrecio($arServicioDetalleConcepto->getPrecio());
                        $arPedidoDetalleConcepto->setSubtotal($arServicioDetalleConcepto->getSubtotal());
                        $arPedidoDetalleConcepto->setTotal($arServicioDetalleConcepto->getTotal());
                        $em->persist($arPedidoDetalleConcepto);                          
                    }
                    $arServicio->setFechaGeneracion($fecha);                    
                    $em->persist($arServicio);
                    $em->flush();
                    $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $arPedidoNuevo->getCodigoPedidoPk(), 'compuesto' => 1));
                    foreach ($arPedidoDetalles as $arPedidoDetalle) {
                        $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->liquidar($arPedidoDetalle->getCodigoPedidoDetallePk());
                    }                    
                    $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($arPedidoNuevo->getCodigoPedidoPk());
                    $em->getRepository('BrasaTurnoBundle:TurPedido')->autorizar($arPedidoNuevo->getCodigoPedidoPk());                      
                } else {
                    $mensaje->Mensaje('error', "Ya esta generado este servicio para este periodo", $this);
                }                
                //return $this->redirect($this->generateUrl('brs_tur_proceso_generar_pedido_lista'));                                                 
            }
            
            if ($form->get('BtnGenerar')->isClicked()) {                 
                set_time_limit(0);
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoServicio) {
                        $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
                        $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio); 
                        if($fecha > $arServicio->getFechaGeneracion()) {                      
                            $arPedidoTipo = $em->getRepository('BrasaTurnoBundle:TurPedidoTipo')->find(2);
                            $arPedidoNuevo = new \Brasa\TurnoBundle\Entity\TurPedido();                    
                            $arPedidoNuevo->setPedidoTipoRel($arPedidoTipo);
                            $arPedidoNuevo->setClienteRel($arServicio->getClienteRel());
                            $arPedidoNuevo->setSectorRel($arServicio->getSectorRel());
                            $arPedidoNuevo->setFecha($dateFechaDesde);
                            $arPedidoNuevo->setFechaProgramacion($dateFechaDesde);                                        
                            $em->persist($arPedidoNuevo);                    

                            $arServicioDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                            $arServicioDetalles =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $arServicio->getCodigoServicioPk())); 
                            foreach ($arServicioDetalles as $arServicioDetalle) {
                                $arPedidoDetalleNuevo = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                                $arPedidoDetalleNuevo->setPedidoRel($arPedidoNuevo);
                                $intDiaInicial = 0;
                                $intDiaFinal = 0;
                                $dateFechaProceso = $dateFechaDesde;
                                if($arServicioDetalle->getFechaDesde() <= $dateFechaHasta) {
                                    if($arServicioDetalle->getFechaDesde() > $dateFechaProceso) {
                                        $dateFechaProceso = $arServicioDetalle->getFechaDesde();
                                        if($dateFechaProceso <= $arServicioDetalle->getFechaHasta()) {
                                            $intDiaInicial = $dateFechaProceso->format('j');
                                        }
                                    } else {
                                       $intDiaInicial = $dateFechaProceso->format('j'); 
                                    }                            
                                } 
                                $dateFechaProceso = $dateFechaHasta;
                                if($dateFechaHasta >= $arServicioDetalle->getFechaDesde()) {
                                    if($arServicioDetalle->getFechaHasta() < $dateFechaProceso) {
                                        $dateFechaProceso = $arServicioDetalle->getFechaHasta();
                                        if($dateFechaProceso >= $arServicioDetalle->getFechaHasta()) {
                                            $intDiaFinal =  $dateFechaProceso->format('j');                                
                                        }                                                        
                                    } else {
                                        $intDiaFinal =  $dateFechaProceso->format('j');
                                    }                            
                                }                           
                                $arPedidoDetalleNuevo->setDiaDesde($intDiaInicial);
                                $arPedidoDetalleNuevo->setDiaHasta($intDiaFinal); 

                                $strUltimoDiaMes = date("j",(mktime(0,0,0,$dateFechaDesde->format('m')+1,1,$dateFechaDesde->format('Y'))-1));
                                $arPeriodo = new \Brasa\TurnoBundle\Entity\TurPeriodo();
                                if($intDiaInicial != 1 || $intDiaFinal != $strUltimoDiaMes) {
                                    $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(2);
                                } else {
                                    $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(1);
                                }
                                $arPedidoDetalleNuevo->setPeriodoRel($arPeriodo);
                                $arPedidoDetalleNuevo->setCantidad($arServicioDetalle->getCantidad());
                                $arPedidoDetalleNuevo->setConceptoServicioRel($arServicioDetalle->getConceptoServicioRel());
                                $arPedidoDetalleNuevo->setModalidadServicioRel($arServicioDetalle->getModalidadServicioRel());                    
                                $arPedidoDetalleNuevo->setPuestoRel($arServicioDetalle->getPuestoRel());
                                $arPedidoDetalleNuevo->setPlantillaRel($arServicioDetalle->getPlantillaRel());
                                $arPedidoDetalleNuevo->setServicioDetalleRel($arServicioDetalle);
                                $arPedidoDetalleNuevo->setGrupoFacturacionRel($arServicioDetalle->getGrupoFacturacionRel());                                
                                $arPedidoDetalleNuevo->setProyectoRel($arServicioDetalle->getProyectoRel());                                
                                $arPedidoDetalleNuevo->setLunes($arServicioDetalle->getLunes());
                                $arPedidoDetalleNuevo->setMartes($arServicioDetalle->getMartes());
                                $arPedidoDetalleNuevo->setMiercoles($arServicioDetalle->getMiercoles());
                                $arPedidoDetalleNuevo->setJueves($arServicioDetalle->getJueves());
                                $arPedidoDetalleNuevo->setViernes($arServicioDetalle->getViernes());
                                $arPedidoDetalleNuevo->setSabado($arServicioDetalle->getSabado());
                                $arPedidoDetalleNuevo->setDomingo($arServicioDetalle->getDomingo());
                                $arPedidoDetalleNuevo->setFestivo($arServicioDetalle->getFestivo());    
                                $arPedidoDetalleNuevo->setVrPrecioAjustado($arServicioDetalle->getVrPrecioAjustado());
                                $arPedidoDetalleNuevo->setFechaIniciaPlantilla($arServicioDetalle->getFechaIniciaPlantilla());
                                $arPedidoDetalleNuevo->setAjusteProgramacion($arServicioDetalle->getAjusteProgramacion());
                                $arPedidoDetalleNuevo->setAnio($anio);
                                $arPedidoDetalleNuevo->setMes($mes);
                                $arPedidoDetalleNuevo->setCompuesto($arServicioDetalle->getCompuesto());
                                if($intDiaInicial != 0 && $intDiaFinal != 0) { 
                                    $em->persist($arPedidoDetalleNuevo);                      
                                    $arServicioDetalleRecursos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
                                    $arServicioDetalleRecursos =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->findBy(array('codigoServicioDetalleFk' => $arServicioDetalle->getCodigoServicioDetallePk())); 
                                    foreach ($arServicioDetalleRecursos as $arServicioDetalleRecurso) {
                                        $arPedidoDetalleRecursoNuevo = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso();
                                        $arPedidoDetalleRecursoNuevo->setPedidoDetalleRel($arPedidoDetalleNuevo);
                                        $arPedidoDetalleRecursoNuevo->setRecursoRel($arServicioDetalleRecurso->getRecursoRel());
                                        $arPedidoDetalleRecursoNuevo->setPosicion($arServicioDetalleRecurso->getPosicion());
                                        $em->persist($arPedidoDetalleRecursoNuevo);
                                    }                        
                                }
                                if($arServicioDetalle->getCompuesto() == 1) {
                                    $arServicioDetallesCompuestos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto();
                                    $arServicioDetallesCompuestos = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleCompuesto')->findBy(array('codigoServicioDetalleFk' => $arServicioDetalle->getCodigoServicioDetallePk()));
                                    foreach ($arServicioDetallesCompuestos as $arServicioDetalleCompuesto) {
                                        $arPedidoDetalleCompuesto = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto();
                                        $arPedidoDetalleCompuesto->setPedidoDetalleRel($arPedidoDetalleNuevo);
                                        $arPedidoDetalleCompuesto->setModalidadServicioRel($arServicioDetalleCompuesto->getModalidadServicioRel());
                                        $arPedidoDetalleCompuesto->setPeriodoRel($arServicioDetalleCompuesto->getPeriodoRel());
                                        $arPedidoDetalleCompuesto->setConceptoServicioRel($arServicioDetalleCompuesto->getConceptoServicioRel());                                                                                                                                                       
                                        $arPedidoDetalleCompuesto->setDias($arServicioDetalleCompuesto->getDias());
                                        $arPedidoDetalleCompuesto->setLunes($arServicioDetalleCompuesto->getLunes());
                                        $arPedidoDetalleCompuesto->setMartes($arServicioDetalleCompuesto->getMartes());
                                        $arPedidoDetalleCompuesto->setMiercoles($arServicioDetalleCompuesto->getMiercoles());
                                        $arPedidoDetalleCompuesto->setJueves($arServicioDetalleCompuesto->getJueves());
                                        $arPedidoDetalleCompuesto->setViernes($arServicioDetalleCompuesto->getViernes());
                                        $arPedidoDetalleCompuesto->setSabado($arServicioDetalleCompuesto->getSabado());
                                        $arPedidoDetalleCompuesto->setDomingo($arServicioDetalleCompuesto->getDomingo());
                                        $arPedidoDetalleCompuesto->setFestivo($arServicioDetalleCompuesto->getFestivo());                            
                                        $arPedidoDetalleCompuesto->setCantidad($arServicioDetalleCompuesto->getCantidad());
                                        $arPedidoDetalleCompuesto->setVrPrecioAjustado($arServicioDetalleCompuesto->getVrPrecioAjustado());                                                                
                                        $arPedidoDetalleCompuesto->setLiquidarDiasReales($arServicioDetalleCompuesto->getLiquidarDiasReales());                                

                                        $strAnioMes = $arPedidoNuevo->getFechaProgramacion()->format('Y/m/');
                                        $dateFechaDesde = date_create($strAnioMes . "1");
                                        $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFechaDesde->format('m')+1,1,$dateFechaDesde->format('Y'))-1));
                                        $dateFechaHasta = date_create($strAnioMes . $strUltimoDiaMes);
                                        $intDiaInicial = 0;
                                        $intDiaFinal = 0;
                                        if($dateFechaDesde < $arServicioDetalle->getFechaHasta()) {
                                            $dateFechaProceso = $dateFechaDesde;
                                            if($arServicioDetalle->getFechaDesde() <= $dateFechaHasta) {
                                                if($arServicioDetalle->getFechaDesde() > $dateFechaProceso) {
                                                    $dateFechaProceso = $arServicioDetalle->getFechaDesde();
                                                    if($dateFechaProceso <= $arServicioDetalle->getFechaHasta()) {
                                                        $intDiaInicial = $dateFechaProceso->format('j');
                                                    }
                                                } else {
                                                   $intDiaInicial = $dateFechaProceso->format('j'); 
                                                }                            
                                            }                         
                                            $dateFechaProceso = $dateFechaHasta;
                                            if($dateFechaHasta >= $arServicioDetalle->getFechaDesde()) {
                                                if($arServicioDetalle->getFechaHasta() < $dateFechaProceso) {
                                                    $dateFechaProceso = $arServicioDetalle->getFechaHasta();
                                                    if($dateFechaProceso >= $arServicioDetalle->getFechaHasta()) {
                                                        $intDiaFinal =  $dateFechaProceso->format('j');                                
                                                    }                                                        
                                                } else {
                                                    $intDiaFinal =  $dateFechaProceso->format('j');
                                                }                            
                                            }                            
                                        }

                                        $arPedidoDetalleCompuesto->setDiaDesde($intDiaInicial);
                                        $arPedidoDetalleCompuesto->setDiaHasta($intDiaFinal); 

                                        $strUltimoDiaMes = date("j",(mktime(0,0,0,$dateFechaDesde->format('m')+1,1,$dateFechaDesde->format('Y'))-1));
                                        $arPeriodo = new \Brasa\TurnoBundle\Entity\TurPeriodo();
                                        if($intDiaInicial != 1 || $intDiaFinal != $strUltimoDiaMes) {
                                            $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(2);
                                        } else {
                                            $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(1);
                                        }                            
                                        $arPedidoDetalleCompuesto->setPeriodoRel($arPeriodo);  
                                        $em->persist($arPedidoDetalleCompuesto);
                                    }
                                }                                
                                
                                
                            }  
                            
                            $arServicio->setFechaGeneracion($fecha);
                            $em->persist($arServicio);
                            $em->flush();
                            $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                            $arPedidoDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $arPedidoNuevo->getCodigoPedidoPk(), 'compuesto' => 1));
                            foreach ($arPedidoDetalles as $arPedidoDetalle) {
                                $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->liquidar($arPedidoDetalle->getCodigoPedidoDetallePk());
                            }                            
                            $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($arPedidoNuevo->getCodigoPedidoPk());
                            $em->getRepository('BrasaTurnoBundle:TurPedido')->autorizar($arPedidoNuevo->getCodigoPedidoPk()); 
                        }
                    }
                }
                set_time_limit(60);
                //return $this->redirect($this->generateUrl('brs_tur_proceso_generar_pedido_lista'));
            }  
            
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->lista($form);
            } 
            
            if ($form->get('BtnExcel')->isClicked()) {
                //$this->filtrar($form);
                $this->lista($form);
                $this->generarExcel();
            }
        }
        
        $arServicios = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 500);
        return $this->render('BrasaTurnoBundle:Procesos/GenerarPedido:lista.html.twig', array(
            'arServicios' => $arServicios, 
            'form' => $form->createView()));
    }        
    
    private function lista($form) {
        $em = $this->getDoctrine()->getManager();
        $anio = $form->get('anio')->getData();
        $mes = $form->get('mes')->getData();
        $fecha = $anio . "/" . $mes . "/01";                     
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurServicio')->listaDql("","",1,0, $fecha);
    }
    
    private function formularioLista() {  
        $fecha = new \DateTime('now');
        $anio = $fecha->format('Y');
        $mes = $fecha->format('m');
        $form = $this->createFormBuilder()
            ->add('mes', ChoiceType::class, array(
                'choices'  => array(
                    '01' => 'Enero','02' => 'Febrero','03' => 'Marzo','04' => 'Abril','05' => 'Mayo','06' => 'Junio','07' => 'Julio',
                    '08' => 'Agosto','09' => 'Septiembre','10' => 'Octubre','11' => 'Noviembre','12' => 'Diciembre',
                ),
                'data' => $mes,
            ))   
            ->add('anio', ChoiceType::class, array(
                'choices'  => array(
                    $anio -1 => $anio -1, $anio => $anio, $anio +1 =>$anio+1
                ),
                'data' => $anio,
            ))                
            ->add('BtnGenerar', SubmitType::class, array('label'  => 'Generar seleccionados'))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
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
                    ->setCellValue('E1', 'AUT')
                    ->setCellValue('F1', 'CER')
                    ->setCellValue('G1', 'HORAS')
                    ->setCellValue('H1', 'H.DIURNAS')
                    ->setCellValue('I1', 'H.NOCTURNAS')
                    ->setCellValue('J1', 'VALOR');

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
                    ->setCellValue('E' . $i, $objFunciones->devuelveBoolean($arServicio->getEstadoAutorizado()))
                    ->setCellValue('F' . $i, $objFunciones->devuelveBoolean($arServicio->getEstadoCerrado()))
                    ->setCellValue('G' . $i, $arServicio->getHoras())
                    ->setCellValue('H' . $i, $arServicio->getHorasDiurnas())
                    ->setCellValue('I' . $i, $arServicio->getHorasNocturnas())
                    ->setCellValue('J' . $i, $arServicio->getVrTotal());

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
    
}