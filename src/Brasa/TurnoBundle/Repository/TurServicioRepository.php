<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurServicioRepository extends EntityRepository {

    public function listaDql($codigoServicio = "", $codigoCliente = "", $boolEstadoAutorizado = "", $boolEstadoCerrado = "", $fechaGeneracion='') {
        $dql   = "SELECT s FROM BrasaTurnoBundle:TurServicio s WHERE s.codigoServicioPk <> 0 ";
        if($codigoServicio != "") {
            $dql .= " AND s.codigoServicioPk = " . $codigoServicio;
        }
        if($codigoCliente != "") {
            $dql .= " AND s.codigoClienteFk = " . $codigoCliente;
        }
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND s.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND s.estadoAutorizado = 0";
        }

        if($boolEstadoCerrado == 1 ) {
            $dql .= " AND s.estadoCerrado = 1";
        }
        if($boolEstadoCerrado == "0") {
            $dql .= " AND s.estadoCerrado = 0";
        }
        if($fechaGeneracion != '') {
            $dql .= " AND s.fechaGeneracion < '" . $fechaGeneracion . "'";
        }
        $dql .= " ORDER BY s.codigoServicioPk";
        return $dql;
    }

    public function pedidoMaestroDql() {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurServicio p WHERE p.codigoServicioTipoFk = 2";
        return $dql;
    }

    public function pedidoSinProgramarDql($strFechaDesde = '', $strFechaHasta = '') {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurServicio p WHERE p.codigoServicioTipoFk = 1 "
                . "AND p.estadoProgramado = 0 AND p.estadoAutorizado = 1";

        if($strFechaDesde != '') {
            $dql .= " AND p.fecha >= '" . $strFechaDesde . "'";
        }
        if($strFechaHasta != '') {
            $dql .= " AND p.fecha <= '" . $strFechaHasta . "'";
        }
        return $dql;
    }

    public function liquidar($codigoServicio) {
        $em = $this->getEntityManager();
        $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
        $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio);
        $intCantidad = 0;
        $precio = 0;
        $douTotalHoras = 0;
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;
        $douTotalServicio = 0;
        $douTotalMinimoServicio = 0;
        $douTotalCostoCalculado = 0;
        $subtotalGeneral = 0;
        $baseAuiGeneral = 0;
        $ivaGeneral = 0;
        $totalGeneral = 0;
        $arServiciosDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
        $arServiciosDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $codigoServicio));
        foreach ($arServiciosDetalle as $arServicioDetalle) {
            if($arServicioDetalle->getCompuesto() == 0) {
                if($arServicioDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 2) {
                    $intDias = $arServicioDetalle->getFechaDesde()->diff($arServicioDetalle->getFechaHasta());
                    $intDias = $intDias->format('%a');
                    $intDias += 1;
                    if($arServicioDetalle->getFechaHasta()->format('d') == '31') {
                        $intDias = $intDias - 1;
                    }
                    if($arServicioDetalle->getDia31() == 1) {
                        if($arServicioDetalle->getFechaHasta()->format('d') == '31') {
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
                if($arServicioDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 1) {
                    if($arServicioDetalle->getLunes() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if($arServicioDetalle->getMartes() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if($arServicioDetalle->getMiercoles() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if($arServicioDetalle->getJueves() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if($arServicioDetalle->getViernes() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if($arServicioDetalle->getSabado() == 1) {
                        $intDiasSabados = 4;
                    }
                    if($arServicioDetalle->getDomingo() == 1) {
                        $intDiasDominicales = 4;
                    }
                    if($arServicioDetalle->getFestivo() == 1) {
                        $intDiasFestivos = 2;
                    }
                    $intTotalDias = $intDiasOrdinarios + $intDiasSabados + $intDiasDominicales + $intDiasFestivos;
                    $intHorasRealesDiurnas = $arServicioDetalle->getConceptoServicioRel()->getHorasDiurnas() * $intTotalDias;
                    $intHorasRealesNocturnas = $arServicioDetalle->getConceptoServicioRel()->getHorasNocturnas() * $intTotalDias;
                } else {
                    $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arServicioDetalle->getFechaDesde()->format('Y-m-d'), $arServicioDetalle->getFechaHasta()->format('Y-m-d'));
                    $fecha = $arServicioDetalle->getFechaDesde()->format('Y-m-j');
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
                                if($arServicioDetalle->getLunes() == 1) {
                                    $intHorasRealesDiurnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                    $intHorasRealesNocturnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasNocturnas();
                                }
                            }
                            if($diaSemana == 2) {
                                $intDiasOrdinarios += 1;
                                if($arServicioDetalle->getMartes() == 1) {
                                    $intHorasRealesDiurnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                    $intHorasRealesNocturnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasNocturnas();
                                }
                            }
                            if($diaSemana == 3) {
                                $intDiasOrdinarios += 1;
                                if($arServicioDetalle->getMiercoles() == 1) {
                                    $intHorasRealesDiurnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                    $intHorasRealesNocturnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasNocturnas();
                                }
                            }
                            if($diaSemana == 4) {
                                $intDiasOrdinarios += 1;
                                if($arServicioDetalle->getJueves() == 1) {
                                    $intHorasRealesDiurnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                    $intHorasRealesNocturnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasNocturnas();
                                }
                            }
                            if($diaSemana == 5) {
                                $intDiasOrdinarios += 1;
                                if($arServicioDetalle->getViernes() == 1) {
                                    $intHorasRealesDiurnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                    $intHorasRealesNocturnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasNocturnas();
                                }
                            }
                            if($diaSemana == 6) {
                               $intDiasSabados += 1;
                                if($arServicioDetalle->getSabado() == 1) {
                                    $intHorasRealesDiurnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                    $intHorasRealesNocturnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasNocturnas();
                                }
                            }
                            if($diaSemana == 7) {
                               $intDiasDominicales += 1;
                                if($arServicioDetalle->getDomingo() == 1) {
                                    $intHorasRealesDiurnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                    $intHorasRealesNocturnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasNocturnas();
                                }
                            }
                        }
                    }
                }
                $douCostoCalculado = $arServicioDetalle->getCantidad() * $arServicioDetalle->getConceptoServicioRel()->getVrCosto();
                $douCostoCalculado = $douCostoCalculado;
                $douHoras = ($intHorasRealesDiurnas + $intHorasRealesNocturnas ) * $arServicioDetalle->getCantidad();
                $arServicioDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                $arServicioDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($arServicioDetalle->getCodigoServicioDetallePk());
                $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                $floValorBaseServicio = $arConfiguracionNomina->getVrSalario() * $arServicio->getSectorRel()->getPorcentaje();
                $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $arServicioDetalle->getModalidadServicioRel()->getPorcentaje() / 100);
                $floVrHoraDiurna = ((($floValorBaseServicioMes * 59.7) / 100)/30)/16;             
                $floVrHoraNocturna = ((($floValorBaseServicioMes * 40.3) / 100)/30)/8;            

                $precio = ($intHorasRealesDiurnas * $floVrHoraDiurna) + ($intHorasRealesNocturnas * $floVrHoraNocturna);
                $precio = $precio;
                $floVrMinimoServicio = $precio;

                $floVrServicio = 0;
                $subTotalDetalle = 0;
                if($arServicioDetalleActualizar->getVrPrecioAjustado() != 0) {
                    $floVrServicio = $arServicioDetalleActualizar->getVrPrecioAjustado() * $arServicioDetalle->getCantidad();
                    $precio = $arServicioDetalleActualizar->getVrPrecioAjustado();
                } else {
                    $floVrServicio = $floVrMinimoServicio * $arServicioDetalle->getCantidad();                
                }                
                $subTotalDetalle = $floVrServicio;
                $baseAiuDetalle = $subTotalDetalle*10/100;
                $baseAiuDetalle = $baseAiuDetalle;
                $ivaDetalle = $baseAiuDetalle*16/100;
                $ivaDetalle = $ivaDetalle;
                $totalDetalle = $subTotalDetalle + $ivaDetalle;
                $totalDetalle = $totalDetalle;
                
                $arServicioDetalleActualizar->setVrSubtotal($subTotalDetalle);
                $arServicioDetalleActualizar->setVrBaseAiu($baseAiuDetalle);
                $arServicioDetalleActualizar->setVrIva($ivaDetalle);
                $arServicioDetalleActualizar->setVrTotalDetalle($totalDetalle);                        
                $arServicioDetalleActualizar->setVrPrecioMinimo($floVrMinimoServicio);
                $arServicioDetalleActualizar->setVrPrecio($precio);
                $arServicioDetalleActualizar->setVrCosto($douCostoCalculado);

                $arServicioDetalleActualizar->setHoras($douHoras);
                $arServicioDetalleActualizar->setHorasDiurnas($intHorasRealesDiurnas);
                $arServicioDetalleActualizar->setHorasNocturnas($intHorasRealesNocturnas);
                $arServicioDetalleActualizar->setDias($intDias);

                $em->persist($arServicioDetalleActualizar);
                
                $subtotalGeneral += $subTotalDetalle;
                $baseAuiGeneral += $baseAiuDetalle;
                $ivaGeneral += $ivaDetalle;
                $totalGeneral += $totalDetalle;
                
                $douTotalHoras += $douHoras;
                $douTotalHorasDiurnas += $intHorasRealesDiurnas;
                $douTotalHorasNocturnas += $intHorasRealesNocturnas;
                $douTotalMinimoServicio += $floVrMinimoServicio;
                $douTotalCostoCalculado += $douCostoCalculado;
                $douTotalServicio += $floVrServicio;
                $intCantidad++;                
            } else {               
                $douTotalHoras += $arServicioDetalle->getHoras();
                $douTotalHorasDiurnas += $arServicioDetalle->getHorasDiurnas();
                $douTotalHorasNocturnas += $arServicioDetalle->getHorasNocturnas();
                $douTotalMinimoServicio += $arServicioDetalle->getVrPrecioMinimo();                
                $subtotalGeneral += $arServicioDetalle->getVrSubtotal();
                $baseAuiGeneral += $arServicioDetalle->getVrBaseAiu();
                $ivaGeneral += $arServicioDetalle->getVrIva();
                $totalGeneral += $arServicioDetalle->getVrTotalDetalle();                
                
            }
        }

        //Otros conceptos
        $floSubTotalConceptos = 0;
        $arServicioDetalleConceptos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto();
        $arServicioDetalleConceptos = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleConcepto')->findBy(array('codigoServicioFk' => $codigoServicio));
        foreach ($arServicioDetalleConceptos as $arServicioDetalleConcepto) {
            $floSubTotalConceptos += $arServicioDetalleConcepto->getSubtotal();            
        }
        $arServicio->setHoras($douTotalHoras);
        $arServicio->setHorasDiurnas($douTotalHorasDiurnas);
        $arServicio->setHorasNocturnas($douTotalHorasNocturnas);
        $arServicio->setVrTotalServicio($douTotalServicio);
        $arServicio->setVrTotalPrecioMinimo($douTotalMinimoServicio);
        $arServicio->setVrTotalOtros($floSubTotalConceptos);
        $arServicio->setVrTotalCosto($douTotalCostoCalculado);
        //$subtotal = $douTotalServicio + $floSubTotalConceptos;
        //$baseAiu = $subtotal*10/100;
        //$iva = $baseAiu*16/100;
        //$total = $subtotal + $iva;
        $arServicio->setVrSubtotal($subtotalGeneral);
        $arServicio->setVrBaseAiu($baseAuiGeneral);
        $arServicio->setVrIva($ivaGeneral);
        $arServicio->setVrTotal($totalGeneral);
        $em->persist($arServicio);
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
                $ar = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }
}