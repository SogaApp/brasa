<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoRepository extends EntityRepository {
    
    public function listaDql($numeroPedido = "", $codigoCliente = "", $boolEstadoAutorizado = "", $boolEstadoProgramado = "", $boolEstadoFacturado = "", $boolEstadoAnulado = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoPk <> 0";
        if($numeroPedido != "") {
            $dql .= " AND p.numero = " . $numeroPedido;  
        }        
        if($codigoCliente != "") {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;  
        }    
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND p.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND p.estadoAutorizado = 0";
        }        
        if($boolEstadoProgramado == 1 ) {
            $dql .= " AND p.estadoProgramado = 1";
        }
        if($boolEstadoProgramado == "0") {
            $dql .= " AND p.estadoProgramado = 0";
        }    
        if($boolEstadoFacturado == 1 ) {
            $dql .= " AND p.estadoFacturado = 1";
        }
        if($boolEstadoFacturado == "0") {
            $dql .= " AND p.estadoFacturado = 0";
        }
        if($boolEstadoAnulado == 1 ) {
            $dql .= " AND p.estadoAnulado = 1";
        }
        if($boolEstadoAnulado == "0") {
            $dql .= " AND p.estadoAnulado = 0";
        }        
        if($strFechaDesde != "") {
            $dql .= " AND p.fechaProgramacion >= '" . $strFechaDesde . "'";
        }        
        if($strFechaHasta != "") {
            $dql .= " AND p.fechaProgramacion <= '" . $strFechaHasta . "'";
        }        
        $dql .= " ORDER BY p.fecha DESC";
        return $dql;
    }
    
    public function pedidoMaestroDql() {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoTipoFk = 2";
        return $dql;
    }    
    
    public function pedidoSinProgramarDql($strFechaDesde = '', $strFechaHasta = '') {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.estadoProgramado = 0 AND p.estadoAutorizado = 1 AND p.estadoAnulado = 0 ";

        if($strFechaDesde != '') {
            $dql .= " AND p.fechaProgramacion >= '" . $strFechaDesde . "'";  
        }
        if($strFechaHasta != '') {
            $dql .= " AND p.fechaProgramacion <= '" . $strFechaHasta . "'";  
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
        $subtotalGeneral = 0;
        $baseAuiGeneral = 0;
        $ivaGeneral = 0;
        $totalGeneral = 0;        
        $arPedidosDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido));         
        foreach ($arPedidosDetalle as $arPedidoDetalle) {
            if($arPedidoDetalle->getCompuesto() == 0) {                
                $intDiasFacturar = 0;
                if($arPedidoDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 2 || $arPedidoDetalle->getLiquidarDiasReales() == 1) {
                    $intDias = $arPedidoDetalle->getDiaHasta() - $arPedidoDetalle->getDiaDesde();
                    $intDias += 1;
                    if($arPedidoDetalle->getDiaHasta() == 0 || $arPedidoDetalle->getDiaDesde() == 0) {
                        $intDias = 0;
                    }
                    $intDiasFacturar = $intDias;
                } else {  
                    $intDias = date("d",(mktime(0,0,0,$arPedido->getFechaProgramacion()->format('m')+1,1,$arPedido->getFechaProgramacion()->format('Y'))-1));
                    $intDiasFacturar = 30;
                }

                $intHorasRealesDiurnas = 0;
                $intHorasRealesNocturnas = 0;            
                $intHorasDiurnasLiquidacion = 0;
                $intHorasNocturnasLiquidacion = 0;                        
                $intDiasOrdinarios = 0;
                $intDiasSabados = 0;
                $intDiasDominicales = 0;
                $intDiasFestivos = 0;                       

                $strFechaDesde = $arPedido->getFechaProgramacion()->format('Y-m') ."-". $arPedidoDetalle->getDiaDesde();
                $strFechaHasta = $arPedido->getFechaProgramacion()->format('Y-m') ."-". $arPedidoDetalle->getDiaHasta();
                $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($strFechaDesde, $strFechaHasta);
                $fecha = $strFechaDesde;
                for($i = 0; $i < $intDias; $i++) {
                    $nuevafecha = strtotime ( '+'.$i.' day' , strtotime ( $fecha ) ) ;
                    $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
                    $dateNuevaFecha = date_create($nuevafecha);
                    $diaSemana = $dateNuevaFecha->format('N');
                    if($this->festivo($arFestivos, $dateNuevaFecha) == 1) {
                        $intDiasFestivos += 1;
                        if($arPedidoDetalle->getFestivo() == 1) {                   
                            $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                        }                    
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
                if($arPedidoDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 1) {
                    if($arPedidoDetalle->getLiquidarDiasReales() == 0) {
                        $intDiasOrdinarios = 0;
                        $intDiasSabados = 0;
                        $intDiasDominicales = 0;
                        $intDiasFestivos = 0;                     
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
                        $intHorasDiurnasLiquidacion = $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas() * $intTotalDias;
                        $intHorasNocturnasLiquidacion = $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas() * $intTotalDias;                                                                   
                    } else {
                        $intHorasDiurnasLiquidacion = $intHorasRealesDiurnas;
                        $intHorasNocturnasLiquidacion = $intHorasRealesNocturnas;                                                                                      
                    }                
                } else {
                    $intHorasDiurnasLiquidacion = $intHorasRealesDiurnas;
                    $intHorasNocturnasLiquidacion = $intHorasRealesNocturnas;                 
                }
                $douHoras = ($intHorasRealesDiurnas + $intHorasRealesNocturnas ) * $arPedidoDetalle->getCantidad();                                                
                $douCostoCalculado = $arPedidoDetalle->getCantidad() * $arPedidoDetalle->getConceptoServicioRel()->getVrCosto();            
                $douCostoCalculado = $douCostoCalculado;
                $arPedidoDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
                $arPedidoDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arPedidoDetalle->getCodigoPedidoDetallePk());                         
                $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1); 
                $floValorBaseServicio = $arConfiguracionNomina->getVrSalario() * $arPedido->getSectorRel()->getPorcentaje();
                $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $arPedidoDetalle->getModalidadServicioRel()->getPorcentaje() / 100);                        
                $floVrHoraDiurna = ((($floValorBaseServicioMes * 59.7) / 100)/30)/16;            
                $floVrHoraNocturna = ((($floValorBaseServicioMes * 40.3) / 100)/30)/8;                                  
                if($arPedidoDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 1) {
                    $precio = ($intHorasDiurnasLiquidacion * $floVrHoraDiurna) + ($intHorasNocturnasLiquidacion * $floVrHoraNocturna);
                } else {
                    $precio = ($intHorasRealesDiurnas * $floVrHoraDiurna) + ($intHorasRealesNocturnas * $floVrHoraNocturna);    
                }

                $precio = round($precio);
                $floVrMinimoServicio = $precio;

                $floVrServicio = 0;
                $subTotalDetalle = 0;
                if($arPedidoDetalleActualizar->getVrPrecioAjustado() != 0) {
                    $floVrServicio = $arPedidoDetalleActualizar->getVrPrecioAjustado() * $arPedidoDetalle->getCantidad();
                    $precio = $arPedidoDetalleActualizar->getVrPrecioAjustado();
                } else {
                    $floVrServicio = $floVrMinimoServicio * $arPedidoDetalle->getCantidad();                
                }
                $subTotalDetalle = $floVrServicio;
                $subtotalGeneral += $subTotalDetalle;
                $baseAiuDetalle = $subTotalDetalle*10/100;
                $baseAiuDetalle = $baseAiuDetalle;
                $ivaDetalle = $baseAiuDetalle*16/100;
                $ivaDetalle = $ivaDetalle;
                $totalDetalle = $subTotalDetalle + $ivaDetalle;
                $totalDetalle = $totalDetalle;
                $arPedidoDetalleActualizar->setVrSubtotal($subTotalDetalle);
                $arPedidoDetalleActualizar->setVrBaseAiu($baseAiuDetalle);
                $arPedidoDetalleActualizar->setVrIva($ivaDetalle);
                $arPedidoDetalleActualizar->setVrTotalDetalle($totalDetalle); 
                $arPedidoDetalleActualizar->setVrTotalDetallePendiente($floVrServicio - $arPedidoDetalle->getVrTotalDetalleAfectado());
                $arPedidoDetalleActualizar->setVrPrecioMinimo($floVrMinimoServicio);
                $arPedidoDetalleActualizar->setVrPrecio($precio);
                $arPedidoDetalleActualizar->setVrCosto($douCostoCalculado);

                $intHorasRealesDiurnas = $intHorasRealesDiurnas * $arPedidoDetalle->getCantidad(); 
                $intHorasRealesNocturnas = $intHorasRealesNocturnas * $arPedidoDetalle->getCantidad(); 
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
            } else {
                $douTotalHoras += $arPedidoDetalle->getHoras();
                $douTotalHorasDiurnas += $arPedidoDetalle->getHorasDiurnas();
                $douTotalHorasNocturnas += $arPedidoDetalle->getHorasNocturnas();
                $douTotalMinimoServicio += $arPedidoDetalle->getVrPrecioMinimo();                
                $subtotalGeneral += $arPedidoDetalle->getVrSubtotal();
                $baseAuiGeneral += $arPedidoDetalle->getVrBaseAiu();
                $ivaGeneral += $arPedidoDetalle->getVrIva();
                $totalGeneral += $arPedidoDetalle->getVrTotalDetalle();                                                            
            }
        }
        
        //Otros conceptos
        $floSubTotalConceptos = 0;
        $arPedidoDetalleConceptos = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto();
        $arPedidoDetalleConceptos = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->findBy(array('codigoPedidoFk' => $codigoPedido));
        foreach ($arPedidoDetalleConceptos as $arPedidoDetalleConcepto) {
            $floSubTotalConceptos += $arPedidoDetalleConcepto->getSubtotal();            
        }      
        
        $arPedido->setHoras($douTotalHoras);
        $arPedido->setHorasDiurnas($douTotalHorasDiurnas);
        $arPedido->setHorasNocturnas($douTotalHorasNocturnas);
        
        $arPedido->setVrTotalServicio($douTotalServicio);
        $arPedido->setVrTotalPrecioMinimo($douTotalMinimoServicio);
        $arPedido->setVrTotalOtros($floSubTotalConceptos);
        $arPedido->setVrTotalCosto($douTotalCostoCalculado);
        $subtotal = $subtotalGeneral + $floSubTotalConceptos;
        $subtotal = $subtotal;
        $baseAiu = $subtotal*10/100;
        $baseAiu = $baseAiu;
        $iva = $baseAiu*16/100;
        $iva = $iva;
        $total = $subtotal + $iva;
        $total = $total;
        $arPedido->setVrSubtotal($subtotal);
        $arPedido->setVrBaseAiu($baseAiu);
        $arPedido->setVrIva($iva);
        $arPedido->setVrTotal($total);
        
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
                $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigo);
                if($arPedido->getNumero() == 0) {
                    $boolEliminar = TRUE;
                    $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigo));                
                    foreach ($arPedidoDetalles as $arPedidoDetalle) {
                        $arProgramacionesDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                        $arProgramacionesDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoPedidoDetalleFk' => $arPedidoDetalle->getCodigoPedidoDetallePk()));                    
                        if(count($arProgramacionesDetalles) > 0) {
                            $boolEliminar = FALSE;
                        }
                    }
                    if($boolEliminar == TRUE) {                    
                        $em->remove($arPedido);                      
                    }                    
                }
            }
            $em->flush();
        }
    }     
    
    public function autorizar($codigoPedido) {
        $em = $this->getEntityManager();                
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);            
        $strResultado = "";        
        if($arPedido->getEstadoAutorizado() == 0) {            
            $intSinPuesto = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->validarPuesto($codigoPedido);
            if($intSinPuesto <= 0) {
                $arPedido->setEstadoAutorizado(1);
                if($arPedido->getNumero() == 0) {
                    $intNumero = $em->getRepository('BrasaTurnoBundle:TurConsecutivo')->consecutivo(1);
                    $arPedido->setNumero($intNumero);
                }
                $em->persist($arPedido);
                $em->flush();                    
            } else {
                $strResultado = $intSinPuesto . " servicios no tienen puesto asignado";
            }                                    
        } else {
            $strResultado = "El pedido ya esta autorizado";
        }        
        return $strResultado;
    }
    
    public function anular($codigoPedido) {
        $em = $this->getEntityManager();                
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);            
        $strResultado = "";        
        if($arPedido->getEstadoAutorizado() == 1 && $arPedido->getEstadoAnulado() == 0) {
            $boolAnular = TRUE;
            $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
            $arPedidoDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido));                
            foreach ($arPedidoDetalles as $arPedidoDetalle) {
                $arProgramacionesDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                $arProgramacionesDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoPedidoDetalleFk' => $arPedidoDetalle->getCodigoPedidoDetallePk()));                    
                if(count($arProgramacionesDetalles) > 0) {
                    $boolAnular = FALSE;
                }
                $arFacturasDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                $arFacturasDetalles = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoPedidoDetalleFk' => $arPedidoDetalle->getCodigoPedidoDetallePk()));                    
                if(count($arFacturasDetalles) > 0) {
                    $boolAnular = FALSE;
                }                
            }
            if($boolAnular == TRUE) {
                foreach ($arPedidoDetalles as $arPedidoDetalle) {
                    $arPedidoDetalleAct = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalleAct = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arPedidoDetalle->getCodigoPedidoDetallePk());                                        
                    $arPedidoDetalleAct->setVrCosto(0);
                    $arPedidoDetalleAct->setVrPrecioAjustado(0);
                    $arPedidoDetalleAct->setVrPrecioMinimo(0);
                    $arPedidoDetalleAct->setVrTotalDetalle(0);
                    $arPedidoDetalleAct->setVrTotalDetallePendiente(0);
                    $arPedidoDetalleAct->setHoras(0);
                    $arPedidoDetalleAct->setHorasDiurnas(0);
                    $arPedidoDetalleAct->setHorasNocturnas(0);                    
                    $arPedidoDetalleAct->setDias(0);                    
                    $em->persist($arPedidoDetalleAct);
                }
                $arPedido->setEstadoAnulado(1);
                $arPedido->setVrTotalCosto(0);
                $arPedido->setVrTotalPrecioAjustado(0);
                $arPedido->setVrTotalPrecioMinimo(0);
                $arPedido->setVrTotal(0);                
                $arPedido->setHoras(0);
                $arPedido->setHorasDiurnas(0);
                $arPedido->setHorasNocturnas(0);
                $em->persist($arPedido);
                $em->flush();      
            } else {
                $strResultado = "Hay programaciones que dependen de este pedido, por lo tanto no se puede anular";
            }                            
        } else {
            $strResultado = "El pedido debe estar autorizado y no puede estar previamente anulado";
        }        
        return $strResultado;
    }    

    public function facturar($codigoPedido, $usuario) {
        $em = $this->getEntityManager();
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $codigoFactura = 0;
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido); 
        $arPedido->setEstadoFacturado(1);
        $em->persist($arPedido);            
        $arFacturaTipo = new \Brasa\TurnoBundle\Entity\TurFacturaTipo();
        $arFacturaTipo = $em->getRepository('BrasaTurnoBundle:TurFacturaTipo')->find(1);
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura->setFecha(new \DateTime('now'));
        $arFactura->setFacturaTipoRel($arFacturaTipo);
        $dateFechaVence = $objFunciones->sumarDiasFecha($arPedido->getClienteRel()->getPlazoPago(), $arFactura->getFecha());
        $arFactura->setFechaVence($dateFechaVence);            
        $arFactura->setClienteRel($arPedido->getClienteRel());                   
        $arFactura->setUsuario($usuario); 
        $arFactura->setOperacion($arFacturaTipo->getOperacion());
        $em->persist($arFactura);   
        $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido, 'estadoFacturado' => 0));                                    
        foreach ($arPedidoDetalles as $arPedidoDetalle) {  
            $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
            $arFacturaDetalle->setFacturaRel($arFactura);                        
            $arFacturaDetalle->setConceptoServicioRel($arPedidoDetalle->getConceptoServicioRel());
            $arFacturaDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
            $arFacturaDetalle->setModalidadServicioRel($arPedidoDetalle->getModalidadServicioRel());
            $arFacturaDetalle->setGrupoFacturacionRel($arPedidoDetalle->getGrupoFacturacionRel());
            $arFacturaDetalle->setPedidoDetalleRel($arPedidoDetalle);
            $arFacturaDetalle->setCantidad($arPedidoDetalle->getCantidad());
            $arFacturaDetalle->setVrPrecio($arPedidoDetalle->getVrPrecio()); 
            $arFacturaDetalle->setPorIva($arPedidoDetalle->getConceptoServicioRel()->getPorIva());
            $arFacturaDetalle->setPorBaseIva($arPedidoDetalle->getConceptoServicioRel()->getPorBaseIva());            
            $arFacturaDetalle->setFechaProgramacion($arPedido->getFechaProgramacion());
            $arFacturaDetalle->setTipoPedido($arPedido->getPedidoTipoRel()->getTipo());
            $arFacturaDetalle->setDetalle($arPedidoDetalle->getDetalle());
            $arFacturaDetalle->setOperacion($arFacturaTipo->getOperacion());
            $em->persist($arFacturaDetalle);                                
        }                         
        /*$arPedidoDetalleConceptos = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto();
        $arPedidoDetalleConceptos =  $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->findBy(array('codigoPedidoFk' => $codigoPedido));                     
        foreach ($arPedidoDetalleConceptos as $arPedidoDetalleConcepto) {
            $arPedidoDetalleConceptoAct = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto();
            $arPedidoDetalleConceptoAct = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->find($arPedidoDetalleConcepto->getCodigoPedidoDetalleConceptoPk());
            $arPedidoDetalleConceptoAct->setEstadoFacturado(1);
            $em->persist($arPedidoDetalleConceptoAct);

            $arFacturaDetalleConcepto = new \Brasa\TurnoBundle\Entity\TurFacturaDetalleConcepto();                        
            $arFacturaDetalleConcepto->setFacturaRel($arFactura);                         
            $arFacturaDetalleConcepto->setFacturaConceptoRel($arPedidoDetalleConcepto->getFacturaConceptoRel());
            $arFacturaDetalleConcepto->setPedidoDetalleConceptoRel($arPedidoDetalleConcepto);
            $arFacturaDetalleConcepto->setCantidad($arPedidoDetalleConcepto->getCantidad());
            $arFacturaDetalleConcepto->setIva($arPedidoDetalleConcepto->getIva());
            $arFacturaDetalleConcepto->setPrecio($arPedidoDetalleConcepto->getPrecio());
            $arFacturaDetalleConcepto->setSubtotal($arPedidoDetalleConcepto->getSubtotal());
            $arFacturaDetalleConcepto->setTotal($arPedidoDetalleConcepto->getTotal());
            $em->persist($arFacturaDetalleConcepto);                          
        }*/
                    
        $em->flush();  
        $codigoFactura = $arFactura->getCodigoFacturaPk();
        $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);                  
        return $codigoFactura;
    }    
    
    public function actualizarHorasProgramadas ($codigoPedido) {
        $em = $this->getEntityManager();
        $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido));
        foreach($arPedidoDetalles as $arPedidoDetalle) {
            $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->actualizarHorasProgramadas($arPedidoDetalle->getCodigoPedidoDetallePk());
        }
         $em->flush();               
    }
    
    public function actualizarPendienteFacturar ($codigoPedido) {
        $em = $this->getEntityManager();
        $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido));
        foreach($arPedidoDetalles as $arPedidoDetalle) {
            $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->actualizarPendienteFacturar($arPedidoDetalle->getCodigoPedidoDetallePk());
        }
         $em->flush();               
    }    
    
    public function actualizarEstadoProgramado ($codigoPedido) {
        $em = $this->getEntityManager();        
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
        $arPedidoDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido));
        if($arPedidoDetalles) {
            $arPedidoDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido, 'estadoProgramado' => 0));
            if(!$arPedidoDetalles) {
                $arPedido->setEstadoProgramado(1);
            }
        }
        $em->flush();               
    }    
    
}
