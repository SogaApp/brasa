<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCotizacionRepository extends EntityRepository {
    
    public function listaDql($numero, $codigoCliente = "", $boolEstadoAutorizado = "") {
        $dql   = "SELECT c FROM BrasaTurnoBundle:TurCotizacion c WHERE c.codigoCotizacionPk <> 0";
        if($numero != "") {
            $dql .= " AND c.numero = " . $numero;  
        }        
        if($codigoCliente != "") {
            $dql .= " AND c.codigoClienteFk = " . $codigoCliente;  
        }    
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND c.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND c.estadoAutorizado = 0";
        }        
        $dql .= " ORDER BY c.fecha DESC";
        return $dql;
    }
    
    public function pendientes($codigoCliente = "", $codigoProspecto = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaTurnoBundle:TurCotizacion c "
                . "WHERE c.codigoClienteFk = " . $codigoCliente;
        if($codigoProspecto != "") {
            $dql .= " OR c.codigoProspectoFk = " . $codigoProspecto;
        }
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }    
    
    public function liquidar($codigoCotizacion) {        
        $em = $this->getEntityManager();        
        $arCotizacion = new \Brasa\TurnoBundle\Entity\TurCotizacion();        
        $arCotizacion = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->find($codigoCotizacion); 
        $intCantidad = 0;
        $douTotalHoras = 0;
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;
        $douTotalServicio = 0;
        $douTotalMinimoServicio = 0;
        $douTotalCostoCalculado = 0;
        $arCotizacionesDetalle = new \Brasa\TurnoBundle\Entity\TurCotizacionDetalle();        
        $arCotizacionesDetalle = $em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->findBy(array('codigoCotizacionFk' => $codigoCotizacion));         
        foreach ($arCotizacionesDetalle as $arCotizacionDetalle) {
            if($arCotizacionDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 2) {
                $intDias = $arCotizacionDetalle->getFechaDesde()->diff($arCotizacionDetalle->getFechaHasta());
                $intDias = $intDias->format('%a');                           
                $intDias += 1;
                if($arCotizacionDetalle->getFechaHasta()->format('d') == '31') {
                    $intDias = $intDias - 1;
                }
                if($arCotizacionDetalle->getDia31() == 1) {
                    if($arCotizacionDetalle->getFechaHasta()->format('d') == '31') {
                        $intDias = $intDias + 1;    
                    }                    
                }
            } else {
                $intDias = 30;
            }

            $intHorasRealesDiurnas = 0;
            $intHorasRealesNocturnas = 0;            
            $intDiasOrdinarios = 0;
            $intDiasSabados = 0;
            $intDiasDominicales = 0;
            $intDiasFestivos = 0;
            if($arCotizacionDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 1) {                
                if($arCotizacionDetalle->getLunes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arCotizacionDetalle->getMartes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arCotizacionDetalle->getMiercoles() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arCotizacionDetalle->getJueves() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arCotizacionDetalle->getViernes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arCotizacionDetalle->getSabado() == 1) {
                    $intDiasSabados = 4;    
                }
                if($arCotizacionDetalle->getDomingo() == 1) {
                    $intDiasDominicales = 4;    
                }                
                if($arCotizacionDetalle->getFestivo() == 1) {
                    $intDiasFestivos = 2;    
                }                               
                $intTotalDias = $intDiasOrdinarios + $intDiasSabados + $intDiasDominicales + $intDiasFestivos;
                $intHorasRealesDiurnas = $arCotizacionDetalle->getConceptoServicioRel()->getHorasDiurnas() * $intTotalDias;
                $intHorasRealesNocturnas = $arCotizacionDetalle->getConceptoServicioRel()->getHorasNocturnas() * $intTotalDias;                            
            } else {
                $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arCotizacionDetalle->getFechaDesde()->format('Y-m-d'), $arCotizacionDetalle->getFechaHasta()->format('Y-m-d'));
                $fecha = $arCotizacionDetalle->getFechaDesde()->format('Y-m-j');
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
                            if($arCotizacionDetalle->getLunes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arCotizacionDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arCotizacionDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        } 
                        if($diaSemana == 2) {
                            $intDiasOrdinarios += 1; 
                            if($arCotizacionDetalle->getMartes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arCotizacionDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arCotizacionDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 3) {
                            $intDiasOrdinarios += 1; 
                            if($arCotizacionDetalle->getMiercoles() == 1) {                   
                                $intHorasRealesDiurnas +=  $arCotizacionDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arCotizacionDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }    
                        if($diaSemana == 4) {
                            $intDiasOrdinarios += 1; 
                            if($arCotizacionDetalle->getJueves() == 1) {                   
                                $intHorasRealesDiurnas +=  $arCotizacionDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arCotizacionDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 5) {
                            $intDiasOrdinarios += 1; 
                            if($arCotizacionDetalle->getViernes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arCotizacionDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arCotizacionDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 6) {
                           $intDiasSabados += 1; 
                            if($arCotizacionDetalle->getSabado() == 1) {                   
                                $intHorasRealesDiurnas +=  $arCotizacionDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arCotizacionDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }                   
                        }
                        if($diaSemana == 7) {
                           $intDiasDominicales += 1; 
                            if($arCotizacionDetalle->getDomingo() == 1) {                   
                                $intHorasRealesDiurnas +=  $arCotizacionDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arCotizacionDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }                   
                        }                    
                    }                                
                }                
            }
                                    
            $douCostoCalculado = $arCotizacionDetalle->getCantidad() * $arCotizacionDetalle->getConceptoServicioRel()->getVrCosto();
            $douHoras = ($intHorasRealesDiurnas + $intHorasRealesNocturnas ) * $arCotizacionDetalle->getCantidad();            
            $arCotizacionDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurCotizacionDetalle();        
            $arCotizacionDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->find($arCotizacionDetalle->getCodigoCotizacionDetallePk());                         
            $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1); 
            $floValorBaseServicio = $arConfiguracionNomina->getVrSalario() * $arCotizacion->getSectorRel()->getPorcentaje();
            $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $arCotizacionDetalle->getModalidadServicioRel()->getPorcentaje() / 100);                        
            $floVrHoraDiurna = ((($floValorBaseServicioMes * 59.7) / 100)/30)/16;            
            $floVrHoraNocturna = ((($floValorBaseServicioMes * 40.3) / 100)/30)/8;                                  
            $floVrMinimoServicio = (($intHorasRealesDiurnas * $floVrHoraDiurna) + ($intHorasRealesNocturnas * $floVrHoraNocturna)) * $arCotizacionDetalle->getCantidad();                        
            $floVrServicio = 0;            
            if($arCotizacionDetalleActualizar->getVrPrecioAjustado() != 0) {
                $floVrServicio = $arCotizacionDetalleActualizar->getVrPrecioAjustado();
            } else {
                $floVrServicio = $floVrMinimoServicio;
            }            
            $arCotizacionDetalleActualizar->setVrTotalDetalle($floVrServicio);            
            $arCotizacionDetalleActualizar->setVrPrecioMinimo($floVrMinimoServicio);
            $arCotizacionDetalleActualizar->setVrCosto($douCostoCalculado);
            
            $arCotizacionDetalleActualizar->setHoras($douHoras);
            $arCotizacionDetalleActualizar->setHorasDiurnas($intHorasRealesDiurnas);
            $arCotizacionDetalleActualizar->setHorasNocturnas($intHorasRealesNocturnas);
            $arCotizacionDetalleActualizar->setDias($intDias);
            
            $em->persist($arCotizacionDetalleActualizar);            
            $douTotalHoras += $douHoras;
            $douTotalHorasDiurnas += $intHorasRealesDiurnas;
            $douTotalHorasNocturnas += $intHorasRealesNocturnas;
            $douTotalMinimoServicio += $floVrMinimoServicio;
            $douTotalCostoCalculado += $douCostoCalculado;
            $douTotalServicio += $floVrServicio;
            $intCantidad++;
        }
        $arCotizacion->setHoras($douTotalHoras);
        $arCotizacion->setHorasDiurnas($douTotalHorasDiurnas);
        $arCotizacion->setHorasNocturnas($douTotalHorasNocturnas);
        $arCotizacion->setVrTotal($douTotalServicio);
        $arCotizacion->setVrTotalPrecioMinimo($douTotalMinimoServicio);
        $arCotizacion->setVrTotalCosto($douTotalCostoCalculado);
        $em->persist($arCotizacion);
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
    
    public function imprimir($codigo) {
        $em = $this->getEntityManager();  
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $strResultado = "";
        $arCotizacion = new \Brasa\TurnoBundle\Entity\TurCotizacion();        
        $arCotizacion = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->find($codigo);        
        if($arCotizacion->getEstadoAutorizado() == 1) {
            if($arCotizacion->getNumero() == 0) {            
                $intNumero = $em->getRepository('BrasaTurnoBundle:TurConsecutivo')->consecutivo(3);
                $arCotizacion->setNumero($intNumero);
                $arCotizacion->setFecha(new \DateTime('now'));                
            }   
            
            $em->persist($arCotizacion);
            $em->flush();
        } else {
            $strResultado = "Debe autorizar la cotizacion para imprimirla";
        }
        return $strResultado;
    }    
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {                
                if($em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->numeroRegistros($codigo) <= 0) {
                    $arCotizacion = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->find($codigo);                    
                    if($arCotizacion->getEstadoAutorizado() == 0 && $arCotizacion->getNumero() == 0) {
                        $em->remove($arCotizacion);                    
                    }                     
                }               
            }
            $em->flush();
        }
    }    
}