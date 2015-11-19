<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoRepository extends EntityRepository {
    
    public function listaDQL() {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoPk <> 0";
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
        $arPedidosDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido));         
        foreach ($arPedidosDetalle as $arPedidoDetalle) {
            $intDias = $arPedidoDetalle->getFechaDesde()->diff($arPedidoDetalle->getFechaHasta());
            $intDias = $intDias->format('%a');                           
            $intDias += 1; 
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
                $intHorasRealesDiurnas = $arPedidoDetalle->getTurnoRel()->getHorasDiurnas() * $intTotalDias;
                $intHorasRealesNocturnas = $arPedidoDetalle->getTurnoRel()->getHorasNocturnas() * $intTotalDias;                            
            } else {
                $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arPedidoDetalle->getFechaDesde()->format('Y-m-d'), $arPedidoDetalle->getFechaHasta()->format('Y-m-d'));
                $fecha = $arPedidoDetalle->getFechaDesde()->format('Y-m-j');
                for($i = 0; $i < $intDias; $i++) {
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
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getTurnoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getTurnoRel()->getHorasNocturnas();                        
                            }
                        } 
                        if($diaSemana == 2) {
                            $intDiasOrdinarios += 1; 
                            if($arPedidoDetalle->getMartes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getTurnoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getTurnoRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 3) {
                            $intDiasOrdinarios += 1; 
                            if($arPedidoDetalle->getMiercoles() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getTurnoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getTurnoRel()->getHorasNocturnas();                        
                            }
                        }    
                        if($diaSemana == 4) {
                            $intDiasOrdinarios += 1; 
                            if($arPedidoDetalle->getJueves() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getTurnoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getTurnoRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 5) {
                            $intDiasOrdinarios += 1; 
                            if($arPedidoDetalle->getViernes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getTurnoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getTurnoRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 6) {
                           $intDiasSabados += 1; 
                            if($arPedidoDetalle->getSabado() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getTurnoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getTurnoRel()->getHorasNocturnas();                        
                            }                   
                        }
                        if($diaSemana == 7) {
                           $intDiasDominicales += 1; 
                            if($arPedidoDetalle->getDomingo() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getTurnoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getTurnoRel()->getHorasNocturnas();                        
                            }                   
                        }                    
                    }                                
                }                
            }
                                    
            
            $douHoras = ($intHorasRealesDiurnas + $intHorasRealesNocturnas ) * $arPedidoDetalle->getCantidad();            
            $arPedidoDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
            $arPedidoDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arPedidoDetalle->getCodigoPedidoDetallePk());                         
            $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1); 
            $floValorBaseServicio = $arConfiguracionNomina->getVrSalario() * $arPedido->getClienteRel()->getSectorRel()->getPorcentaje();
            $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $arPedidoDetalle->getModalidadServicioRel()->getPorcentaje() / 100);                        
            $floVrHoraDiurna = ((($floValorBaseServicioMes * 59.7) / 100)/30)/16;            
            $floVrHoraNocturna = ((($floValorBaseServicioMes * 40.3) / 100)/30)/8;                                  
            $floVrServicio = (($intHorasRealesDiurnas * $floVrHoraDiurna) + ($intHorasRealesNocturnas * $floVrHoraNocturna)) * $arPedidoDetalle->getCantidad();                        
            $arPedidoDetalleActualizar->setVrTotal($floVrServicio);
            $arPedidoDetalleActualizar->setHoras($douHoras);
            $arPedidoDetalleActualizar->setHorasDiurnas($intHorasRealesDiurnas);
            $arPedidoDetalleActualizar->setHorasNocturnas($intHorasRealesNocturnas);
            $arPedidoDetalleActualizar->setDias($intDias);
            
            $em->persist($arPedidoDetalleActualizar);            
            $douTotalHoras += $douHoras;
            $douTotalHorasDiurnas += $intHorasRealesDiurnas;
            $douTotalHorasNocturnas += $intHorasRealesNocturnas;
            $douTotalServicio += $floVrServicio;
            $intCantidad++;
        }
        $arPedido->setHoras($douTotalHoras);
        $arPedido->setHorasDiurnas($douTotalHorasDiurnas);
        $arPedido->setHorasNocturnas($douTotalHorasNocturnas);
        $arPedido->setVrTotal($douTotalServicio);
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
    
}