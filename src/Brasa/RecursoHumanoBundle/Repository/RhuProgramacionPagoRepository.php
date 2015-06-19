<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuProgramacionPagoRepository extends EntityRepository {
    
    public function listaGenerarPagoDQL($strFechaDesde = "", $strFechaHasta = "", $codigoCentroCosto) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pp FROM BrasaRecursoHumanoBundle:RhuProgramacionPago pp WHERE pp.estadoGenerado = 0 ";
        if($strFechaDesde != "" ) {
            $dql .= " AND pp.fechaHasta >='" . $strFechaDesde . "'";
        }
        
        if($strFechaHasta != "") {
            $dql .= " AND pp.fechaHasta <='" . $strFechaHasta . "'";
        }
        if($codigoCentroCosto != "" && $codigoCentroCosto != 0) {            
            $dql .= " AND pp.codigoCentroCostoFk =" . $codigoCentroCosto;          
        }
        return $dql;
    }                            
    
    /*
     * Liquidar todos los pagos de la programacion de pago
     */
    public function Liquidar($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        $douNeto = 0;
        foreach ($arPagos as $arPago) {
            $douNeto = $douNeto + $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->Liquidar($arPago->getCodigoPagoPk());
        }
        $arProgramacionPago->setVrTotalNeto($douNeto);
        $em->persist($arProgramacionPago);
        $em->flush();
        return true;
    }

    /*
     * Liquidar todos los pagos de la programacion de pago
     */
    public function generarPagoDetalleSede($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arPagos as $arPago) {
            $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->generarPagoDetalleSede($arPago->getCodigoPagoPk());
        }                
        return true;
    }    
    
    public function Anular($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arPagos as $arPago) {
            $arPagosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
            $arPagosDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $arPago->getCodigoPagoPk()));
            foreach ($arPagosDetalles as $arPagoDetalle) {
                $em->remove($arPagoDetalle);
            }
            $em->remove($arPago);
        }
        $arProgramacionPago->setEstadoAnulado(1);
        $em->persist($arProgramacionPago);
        $em->flush();
        return true;
    }
    
    public function Eliminar($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arPagos as $arPago) {
            $arPagosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
            $arPagosDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $arPago->getCodigoPagoPk()));
            foreach ($arPagosDetalles as $arPagoDetalle) {
                $em->remove($arPagoDetalle);
            }
            $em->remove($arPago);
        }
        //Eliminar detalles de programacion pago
        $arProgramacionPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {
            $em->remove($arProgramacionPagoDetalle);
        }   
        $em->remove($arProgramacionPago);
        $em->flush();
        return true;
    }    

    public function Deshacer($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        //Devolver incapacidades
        //Devolver Licencias
        //Devolver pagos adicionales
        $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arPagosAdicionales as $arPagoAdicional) {
            $arPagoAdicionalActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
            $arPagoAdicionalActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($arPagoAdicional->getCodigoPagoAdicionalPk());
            $arPagoAdicionalActualizar->setPagoAplicado(0);
            $arPagoAdicionalActualizar->setProgramacionPagoRel(null);
            $em->persist($arPagoAdicionalActualizar);
        }

        //Devolver creditos
        
        //Eliminar pagos
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arPagos as $arPago) {
            $arPagosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
            $arPagosDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $arPago->getCodigoPagoPk()));
            foreach ($arPagosDetalles as $arPagoDetalle) {                
                //Desahacer creditos
                /*$arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findBy(array('codigoEmpleadoFk' => $arPagoDetalle->getCodigoEmpleadoFk(), 'estadoPagado' => 0));
                foreach ($arCreditos as $arCredito) {
                    $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                    $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($arCreditos->getCodigoCreditoPk());
                    $arCredito->setVrSaldoTotal($arCredito->getSaldoTotal() + $arCredito->getVrCuotaTemporal());
                    $arCredito->setVrCuotaTemporal($arCredito->getVrCuotaTemporal() - $arCredito->getVrCuota());
                    $em->persist($arCredito);
                }*/
                //fin Deshacer creditos
                $em->remove($arPagoDetalle);
            }
            $em->remove($arPago);
        }
        $arProgramacionPago->setNoGeneraPeriodo(1);
        $arProgramacionPago->setVrTotalNeto(0);
        $arProgramacionPago->setEstadoGenerado(0);
        $em->persist($arProgramacionPago);
        $em->flush();
        return true;
    }

    public function eliminarEmpleados($codigoProgramacionPago) {
        $em = $this->getEntityManager();        
        $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {
            $em->remove($arProgramacionPagoDetalle);
        }
        $em->flush();
    }
    
    public function generarEmpleados($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->eliminarEmpleados($codigoProgramacionPago);
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();                
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                . "WHERE c.codigoCentroCostoFk = " . $arProgramacionPago->getCodigoCentroCostoFk()
                . " AND c.fechaDesde <= '" . $arProgramacionPago->getFechaHasta()->format('Y-m-d') . "' "
                . " AND (c.fechaHasta >= '" . $arProgramacionPago->getFechaDesde()->format('Y-m-d') . "' OR c.indefinido = 1)";        
        $query = $em->createQuery($dql);
        $arContratos = $query->getResult();        
        foreach ($arContratos as $arContrato) {
            $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
            $arProgramacionPagoDetalle->setProgramacionPagoRel($arProgramacionPago);
            $arProgramacionPagoDetalle->setEmpleadoRel($arContrato->getEmpleadoRel());                
            $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalario());
            $arProgramacionPagoDetalle->setFechaDesde($arContrato->getFechaDesde());
            $arProgramacionPagoDetalle->setFechaHasta($arContrato->getFechaHasta());
            $arProgramacionPagoDetalle->setIndefinido($arContrato->getIndefinido());
            $dateFechaDesde =  "";
            $dateFechaHasta =  "";
            $intDiasDevolver = 0;
            $fechaFinalizaContrato = $arContrato->getFechaHasta();
            if($arContrato->getIndefinido() == 1) {
                $fecha = date_create(date('Y-m-d'));
                date_modify($fecha, '+365 day');
                $fechaFinalizaContrato = $fecha;
            }
            if($arContrato->getFechaDesde() <  $arProgramacionPago->getFechaDesde() == true) {
                $dateFechaDesde = $arProgramacionPago->getFechaDesde();
            } else {
                if($arContrato->getFechaDesde() > $arProgramacionPago->getFechaHasta() == true) {
                    $intDiasDevolver = 0;
                } else {
                    $dateFechaDesde = $arContrato->getFechaDesde();
                }
            }   
        
            if($fechaFinalizaContrato >  $arProgramacionPago->getFechaHasta() == true) {
                $dateFechaHasta = $arProgramacionPago->getFechaHasta();
            } else {
                if($fechaFinalizaContrato < $arProgramacionPago->getFechaDesde() == true) {
                    $intDiasDevolver = 0;
                } else {
                    $dateFechaHasta = $fechaFinalizaContrato;
                }
            }
            if($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');
                $intDiasDevolver = $intDias + 1;
                //Mes de febrero para periodos NO continuos                
                $intDiasInhabilesFebrero = 0;                                           
                if($arProgramacionPago->getCentroCostoRel()->getPeriodoPagoRel()->getContinuo() == 0) {
                    if($dateFechaHasta->format('md') == '0228' || $dateFechaHasta->format('md') == '0229') {
                        //Verificar si el año es bisiesto
                        
                        if(date('L',mktime(1,1,1,1,1,$dateFechaHasta->format('Y'))) == 1) {
                            $intDiasInhabilesFebrero = 1;
                        } else {
                            $intDiasInhabilesFebrero = 2;
                        }                                                                    
                    }                    
                }
                $intDiasDevolver += $intDiasInhabilesFebrero;                
            }            
            $arProgramacionPagoDetalle->setDias($intDiasDevolver);
            $arProgramacionPagoDetalle->setDiasReales($intDiasDevolver);
            if($arContrato->getCodigoTipoTiempoFk() == 2) {                
                $arProgramacionPagoDetalle->setHorasPeriodo($intDiasDevolver * 4);
                $arProgramacionPagoDetalle->setHorasPeriodoReales($intDiasDevolver * 4);
                $arProgramacionPagoDetalle->setFactorDia(4);
            } else {
                $arProgramacionPagoDetalle->setHorasPeriodo($intDiasDevolver * 8);
                $arProgramacionPagoDetalle->setHorasPeriodoReales($intDiasDevolver * 8);
                $arProgramacionPagoDetalle->setFactorDia(8);
            }            
            $em->persist($arProgramacionPagoDetalle);
        }
        $em->flush();
        return true;
    }
    
    public function pagarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                $arProgramacionPagoProcesar->setEstadoPagado(1);
                $em->persist($arProgramacionPagoProcesar);
                $arPagosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                $arPagosDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->pagosDetallesProgramacionPago($codigoProgramacionPago);                              
                
                foreach ($arPagosDetalles AS $arPagoDetalle) {                    
                    $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                    $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($arPagoDetalle->getCodigoCreditoFk());
                    $arPagoCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
                    //se guarda el pago en la tabla rhu_pago_credito
                    $arPagoCredito->setCreditoRel($arCredito);
                    $arPagoCredito->setPagoRel($arPagoDetalle->getPagoRel());
                    $arPagoCredito->setfechaPago(new \ DateTime("now"));
                    $arPagoCredito->setSeguro($douSeguro);    
                    $arPagoCredito->setTipoPago("NOMINA");                       
                    if ($arCredito->getEstadoPagado() == 0 && $arCredito->getAprobado() == 1 && $arCredito->getEstadoSuspendido() == 0) {                            
                        //Actualizar el saldo del credito    
                        $douSeguro = $arCredito->getSeguro();                        
                        $arCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual() + 1);
                        if ($arCredito->getSaltoTotal() >= $arCreditoProcesar->getVrCuota()){
                            $arCredito->setSaldo($arCredito->getSaltoTotal());
                            $arPagoCredito->setVrCuota($arCredito->getVrCuota());
                            $arCredito->setVrCuotaTemporal(0);        
                        } else {
                            $arCredito->setSaldo($arCredito->getSaldoTotal());         
                            $arCredito->setVrCuota($arCredito->getVrPagar() / $arCredito->getNumeroCuotas() + $douSeguro);
                            $arPagoCredito->setvrCuota($arCredito->getVrCuotaTemporal());    
                          }
                        if ($arCredito->getSaldo() <= 0)
                        {
                           $arCredito->setEstadoPagado(1); 
                        }  
                        $em->persist($arCredito);
                    }                  
                }
                 
            }
            $em->flush();                            
        }
    }
}