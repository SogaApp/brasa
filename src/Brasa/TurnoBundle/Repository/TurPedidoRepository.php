<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoRepository extends EntityRepository {
    
    public function listaDql() {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoPk <> 0";
        return $dql;
    }
    
    public function pedidoMaestroDql() {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoTipoFk = 2";
        return $dql;
    }    
    
    public function pedidoSinProgramarDql($strFechaDesde = '', $strFechaHasta = '') {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.estadoProgramado = 0 AND p.estadoAutorizado = 1";

        if($strFechaDesde != '') {
            $dql .= " AND p.fecha >= '" . $strFechaDesde . "'";  
        }
        if($strFechaHasta != '') {
            $dql .= " AND p.fecha <= '" . $strFechaHasta . "'";  
        }        
        return $dql;
    }        
    
    public function liquidar($codigoPedido) {        
        $em = $this->getEntityManager();        
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();        
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido); 
        $intCantidad = 0;
        $douTotalHoras = 0;
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;
        $douTotalServicio = 0;
        $douTotalMinimoServicio = 0;
        $douTotalCostoCalculado = 0;        
        $arPedidosDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido));         
        foreach ($arPedidosDetalle as $arPedidoDetalle) {
            if($arPedidoDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 2) {
                $intDias = $arPedidoDetalle->getDiaHasta() - $arPedidoDetalle->getDiaDesde();
                $intDias += 1;
            } else {
                $intDias = 30;
            }

            $intHorasRealesDiurnas = 0;
            $intHorasRealesNocturnas = 0;            
            $intDiasOrdinarios = 0;
            $intDiasSabados = 0;
            $intDiasDominicales = 0;
            $intDiasFestivos = 0;
            if($arPedidoDetalle->getCodigoPeriodoFk() == 1) {                
                if($arPedidoDetalle->getLunes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arPedidoDetalle->getMartes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arPedidoDetalle->getMiercoles() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arPedidoDetalle->getJueves() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arPedidoDetalle->getViernes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arPedidoDetalle->getSabado() == 1) {
                    $intDiasSabados = 4;    
                }
                if($arPedidoDetalle->getDomingo() == 1) {
                    $intDiasDominicales = 4;    
                }                
                if($arPedidoDetalle->getFestivo() == 1) {
                    $intDiasFestivos = 2;    
                }                               
                $intTotalDias = $intDiasOrdinarios + $intDiasSabados + $intDiasDominicales + $intDiasFestivos;
                $intHorasRealesDiurnas = $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas() * $intTotalDias;
                $intHorasRealesNocturnas = $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas() * $intTotalDias;                            
            } else {
                $strFechaDesde = $arPedido->getFechaProgramacion()->format('Y-m') ."-". $arPedidoDetalle->getDiaDesde();
                $strFechaHasta = $arPedido->getFechaProgramacion()->format('Y-m') ."-". $arPedidoDetalle->getDiaHasta();
                $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($strFechaDesde, $strFechaHasta);
                $fecha = $strFechaDesde;
                for($i = 1; $i <= $intDias; $i++) {
                    $nuevafecha = strtotime ( '+'.$i.' day' , strtotime ( $fecha ) ) ;
                    $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
                    $dateNuevaFecha = date_create($nuevafecha);
                    $diaSemana = $dateNuevaFecha->format('N');
                    if($this->festivo($arFestivos, $dateNuevaFecha) == 1) {
                        $intDiasFestivos += 1;
                    } else {
                        if($diaSemana == 1) {
                            $intDiasOrdinarios += 1; 
                            if($arPedidoDetalle->getLunes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        } 
                        if($diaSemana == 2) {
                            $intDiasOrdinarios += 1; 
                            if($arPedidoDetalle->getMartes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 3) {
                            $intDiasOrdinarios += 1; 
                            if($arPedidoDetalle->getMiercoles() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }    
                        if($diaSemana == 4) {
                            $intDiasOrdinarios += 1; 
                            if($arPedidoDetalle->getJueves() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 5) {
                            $intDiasOrdinarios += 1; 
                            if($arPedidoDetalle->getViernes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 6) {
                           $intDiasSabados += 1; 
                            if($arPedidoDetalle->getSabado() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }                   
                        }
                        if($diaSemana == 7) {
                           $intDiasDominicales += 1; 
                            if($arPedidoDetalle->getDomingo() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }                   
                        }                    
                    }                                
                }                
            }
                                    
            $douCostoCalculado = $arPedidoDetalle->getCantidad() * $arPedidoDetalle->getConceptoServicioRel()->getVrCostoCalculado();
            $douHoras = ($intHorasRealesDiurnas + $intHorasRealesNocturnas ) * $arPedidoDetalle->getCantidad();            
            $arPedidoDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
            $arPedidoDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arPedidoDetalle->getCodigoPedidoDetallePk());                         
            $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1); 
            $floValorBaseServicio = $arConfiguracionNomina->getVrSalario() * $arPedido->getSectorRel()->getPorcentaje();
            $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $arPedidoDetalle->getModalidadServicioRel()->getPorcentaje() / 100);                        
            $floVrHoraDiurna = ((($floValorBaseServicioMes * 59.7) / 100)/30)/16;            
            $floVrHoraNocturna = ((($floValorBaseServicioMes * 40.3) / 100)/30)/8;                                  
            $floVrMinimoServicio = (($intHorasRealesDiurnas * $floVrHoraDiurna) + ($intHorasRealesNocturnas * $floVrHoraNocturna)) * $arPedidoDetalle->getCantidad();                        
            $floVrServicio = 0;            
            if($arPedidoDetalleActualizar->getVrTotalAjustado() != 0) {
                $floVrServicio = $arPedidoDetalleActualizar->getVrTotalAjustado();
            } else {
                $floVrServicio = $floVrMinimoServicio;
            }            
            $arPedidoDetalleActualizar->setVrTotal($floVrServicio);            
            $arPedidoDetalleActualizar->setVrTotalMinimo($floVrMinimoServicio);
            $arPedidoDetalleActualizar->setVrCostoCalculado($douCostoCalculado);
            
            $arPedidoDetalleActualizar->setHoras($douHoras);
            $arPedidoDetalleActualizar->setHorasDiurnas($intHorasRealesDiurnas);
            $arPedidoDetalleActualizar->setHorasNocturnas($intHorasRealesNocturnas);
            $arPedidoDetalleActualizar->setDias($intDias);
            
            $em->persist($arPedidoDetalleActualizar);            
            $douTotalHoras += $douHoras;
            $douTotalHorasDiurnas += $intHorasRealesDiurnas;
            $douTotalHorasNocturnas += $intHorasRealesNocturnas;
            $douTotalMinimoServicio += $floVrMinimoServicio;
            $douTotalCostoCalculado += $douCostoCalculado;
            $douTotalServicio += $floVrServicio;
            $intCantidad++;
        }
        $arPedido->setHoras($douTotalHoras);
        $arPedido->setHorasDiurnas($douTotalHorasDiurnas);
        $arPedido->setHorasNocturnas($douTotalHorasNocturnas);
        $arPedido->setVrTotal($douTotalServicio);
        $arPedido->setVrTotalMinimo($douTotalMinimoServicio);
        $arPedido->setVrCostoCalculado($douTotalCostoCalculado);
        $em->persist($arPedido);
        $em->flush();
        return true;
    }
    
    public function festivo($arFestivos, $dateFecha) {
        $boolFestivo = 0;
        foreach ($arFestivos as $arFestivo) {
            if($arFestivo['fecha'] == $dateFecha) {
                $boolFestivo = 1;
            }
        }
        return $boolFestivo;
    }    

    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }     
}