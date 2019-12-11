<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurServicioDetalleRepository extends EntityRepository {

    public function listaDql($codigoServicio = "", $estadoCerrado = "") {
        $dql   = "SELECT sd FROM BrasaTurnoBundle:TurServicioDetalle sd WHERE sd.codigoServicioDetallePk <> 0 ";
        
        if($codigoServicio != '') {
            $dql .= " AND sd.codigoServicioFk = " . $codigoServicio;  
        }        
        
        if($estadoCerrado == 1) {
            $dql .= " AND sd.estadoCerrado = 1"; 
        }
        if($estadoCerrado == 0) {
            $dql .= " AND sd.estadoCerrado = 0"; 
        }        
        $dql .= " ORDER BY sd.codigoGrupoFacturacionFk, sd.codigoPuestoFk";
        return $dql;
    }    
    
    public function listaConsultaDql($codigoServicio = "", $codigoCliente = "", $estadoCerrado, $fechaHasta = "") {
        $dql   = "SELECT sd FROM BrasaTurnoBundle:TurServicioDetalle sd JOIN sd.servicioRel s WHERE sd.codigoServicioDetallePk <> 0 ";                
        if($codigoCliente != '') {
            $dql .= "AND s.codigoClienteFk = " . $codigoCliente;  
        }
        if($estadoCerrado == 1 ) {
            $dql .= " AND sd.estadoCerrad0 = 1";
        }
        if($estadoCerrado == "0") {
            $dql .= " AND sd.estadoCerrado = 0";
        } 
        if($fechaHasta != "") {
            $dql .= " AND sd.fechaHasta >= '" . $fechaHasta . "'";
        }         
        $dql .= " ORDER BY s.codigoClienteFk, sd.codigoGrupoFacturacionFk, sd.codigoPuestoFk";
        return $dql;
    }     
    
    public function pendientesCliente($codigoCliente) {
        $em = $this->getEntityManager();
        $dql   = "SELECT sd FROM BrasaTurnoBundle:TurServicioDetalle sd JOIN sd.servicioRel s "
                . "WHERE s.codigoClienteFk = " . $codigoCliente . " AND s.estadoCerrado = 0";
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {
                $intNumeroRegistros = 0;
                $dql   = "SELECT COUNT(pd.codigoPedidoDetallePk) as numeroRegistros FROM BrasaTurnoBundle:TurPedidoDetalle pd "
                        . "WHERE pd.codigoServicioDetalleFk = " . $codigo;
                $query = $em->createQuery($dql);
                $arrPedidoDetalles = $query->getSingleResult(); 
                if($arrPedidoDetalles) {
                    $intNumeroRegistros = $arrPedidoDetalles['numeroRegistros'];
                }
                if($intNumeroRegistros <= 0) {
                    $strSql = "DELETE FROM tur_servicio_detalle_compuesto WHERE codigo_servicio_detalle_fk = " . $codigo;
                    $em->getConnection()->executeQuery($strSql);                          
                    $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigo);                
                    $em->remove($arServicioDetalle);                                      
                }
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function cerrarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigo);                
                if($arServicioDetalle->getEstadoCerrado() == 0) {
                    $arServicioDetalle->setEstadoCerrado(1);
                }              
            }                                         
            $em->flush();       
        }
        
    }    
    
    public function AbrirSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigo);                
                if($arServicioDetalle->getEstadoCerrado() == 1) {
                    $arServicioDetalle->setEstadoCerrado(0);
                }              
            }                                         
            $em->flush();       
        }
        
    } 
    
    public function marcarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigo);                
                if($arServicioDetalle->getMarca() == 1) {
                    $arServicioDetalle->setMarca(0);
                } else {
                    $arServicioDetalle->setMarca(1);
                }                
            }                                         
            $em->flush();       
        }
        
    }            
    
    public function ajustarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigo);                
                if($arServicioDetalle->getAjusteProgramacion() == 1) {
                    $arServicioDetalle->setAjusteProgramacion(0);
                } else {
                    $arServicioDetalle->setAjusteProgramacion(1);
                }                
            }                                         
            $em->flush();       
        }
        
    }                
    
    public function numeroRegistros($codigo) {
        $em = $this->getEntityManager();
        $arDetalles = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $codigo));
        return count($arDetalles);
    }          
    
    public function liquidar($codigoServicioDetalle) {
        $em = $this->getEntityManager();
        $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
        $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigoServicioDetalle);
        $intCantidad = 0;
        $precio = 0;
        $douTotalHoras = 0;
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;
        $douTotalServicio = 0;
        $douTotalMinimoServicio = 0;
        $douTotalCostoCalculado = 0;
        $arServiciosDetalleCompuesto = new \Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto();
        $arServiciosDetalleCompuesto = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleCompuesto')->findBy(array('codigoServicioDetalleFk' => $codigoServicioDetalle));
        foreach ($arServiciosDetalleCompuesto as $arServicioDetalleCompuesto) {            
            if($arServicioDetalleCompuesto->getPeriodoRel()->getCodigoPeriodoPk() == 2) {
                $intDias = 30;
            } else {
                $intDias = 30;
            }

            $intHorasRealesDiurnas = 0;
            $intHorasRealesNocturnas = 0;
            $intDiasOrdinarios = 0;
            $intDiasSabados = 0;
            $intDiasDominicales = 0;
            $intDiasFestivos = 0;
            if($arServicioDetalleCompuesto->getPeriodoRel()->getCodigoPeriodoPk() == 1) {
                if($arServicioDetalleCompuesto->getLunes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arServicioDetalleCompuesto->getMartes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arServicioDetalleCompuesto->getMiercoles() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arServicioDetalleCompuesto->getJueves() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arServicioDetalleCompuesto->getViernes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arServicioDetalleCompuesto->getSabado() == 1) {
                    $intDiasSabados = 4;
                }
                if($arServicioDetalleCompuesto->getDomingo() == 1) {
                    $intDiasDominicales = 4;
                }
                if($arServicioDetalleCompuesto->getFestivo() == 1) {
                    $intDiasFestivos = 2;
                }
                $intTotalDias = $intDiasOrdinarios + $intDiasSabados + $intDiasDominicales + $intDiasFestivos;
                $intHorasRealesDiurnas = $arServicioDetalleCompuesto->getConceptoServicioRel()->getHorasDiurnas() * $intTotalDias;
                $intHorasRealesNocturnas = $arServicioDetalleCompuesto->getConceptoServicioRel()->getHorasNocturnas() * $intTotalDias;
            }
                        
            $douHoras = ($intHorasRealesDiurnas + $intHorasRealesNocturnas ) * $arServicioDetalleCompuesto->getCantidad();
            $arServicioDetalleCompuestoActualizar = new \Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto();
            $arServicioDetalleCompuestoActualizar = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleCompuesto')->find($arServicioDetalleCompuesto->getCodigoServicioDetalleCompuestoPk());
            $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
            $floValorBaseServicio = $arConfiguracionNomina->getVrSalario() * $arServicioDetalle->getServicioRel()->getSectorRel()->getPorcentaje();
            $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $arServicioDetalleCompuesto->getModalidadServicioRel()->getPorcentaje() / 100);
            $floVrHoraDiurna = ((($floValorBaseServicioMes * 59.7) / 100)/30)/16;             
            $floVrHoraNocturna = ((($floValorBaseServicioMes * 40.3) / 100)/30)/8;            

            $precio = ($intHorasRealesDiurnas * $floVrHoraDiurna) + ($intHorasRealesNocturnas * $floVrHoraNocturna);
            $precio = round($precio);
            $floVrMinimoServicio = $precio;
            $floVrServicio = 0;
            $subTotalDetalle = 0;
            if($arServicioDetalleCompuestoActualizar->getVrPrecioAjustado() != 0) {                
                $floVrServicio = $arServicioDetalleCompuestoActualizar->getVrPrecioAjustado() * $arServicioDetalleCompuesto->getCantidad();
                $precio = $arServicioDetalleCompuestoActualizar->getVrPrecioAjustado();
            } else {
                $floVrServicio = $floVrMinimoServicio * $arServicioDetalleCompuesto->getCantidad();                
            }
            $subTotalDetalle = $floVrServicio;
            $baseAiuDetalle = $subTotalDetalle*10/100;
            $ivaDetalle = $baseAiuDetalle*16/100;
            $totalDetalle = $subTotalDetalle + $ivaDetalle;

            $arServicioDetalleCompuestoActualizar->setVrSubtotal($subTotalDetalle);
            $arServicioDetalleCompuestoActualizar->setVrBaseAiu($baseAiuDetalle);
            $arServicioDetalleCompuestoActualizar->setVrIva($ivaDetalle);
            $arServicioDetalleCompuestoActualizar->setVrTotalDetalle($totalDetalle);                        
            $arServicioDetalleCompuestoActualizar->setVrPrecioMinimo($floVrMinimoServicio);
            $arServicioDetalleCompuestoActualizar->setVrPrecio($precio);

            $arServicioDetalleCompuestoActualizar->setHoras($douHoras);
            $arServicioDetalleCompuestoActualizar->setHorasDiurnas($intHorasRealesDiurnas);
            $arServicioDetalleCompuestoActualizar->setHorasNocturnas($intHorasRealesNocturnas);
            $arServicioDetalleCompuestoActualizar->setDias($intDias);

            $em->persist($arServicioDetalleCompuestoActualizar);
            $douTotalHoras += $douHoras;
            $douTotalHorasDiurnas += $intHorasRealesDiurnas;
            $douTotalHorasNocturnas += $intHorasRealesNocturnas;
            $douTotalMinimoServicio += $floVrMinimoServicio;
            $douTotalServicio += $floVrServicio;
            $intCantidad++;                
             
        }

        $arServicioDetalle->setHoras($douTotalHoras);
        $arServicioDetalle->setHorasDiurnas($douTotalHorasDiurnas);
        $arServicioDetalle->setHorasNocturnas($douTotalHorasNocturnas);        
        $arServicioDetalle->setVrPrecioMinimo($douTotalMinimoServicio);        
        $subtotal = $douTotalServicio;
        $baseAiu = $subtotal*10/100;
        $iva = $baseAiu*16/100;
        $total = $subtotal + $iva;
        $arServicioDetalle->setVrSubtotal($subtotal);
        $arServicioDetalle->setVrBaseAiu($baseAiu);
        $arServicioDetalle->setVrIva($iva);
        $arServicioDetalle->setVrTotalDetalle($total);
        $em->persist($arServicioDetalle);
        $em->flush();
        return true;
    }    
    
}