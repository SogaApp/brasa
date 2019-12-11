<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuProgramacionPagoRepository extends EntityRepository {

    /**
     * Elimina toda la programacion de pago y los pagos generados de esta
     *
     * @author		Mario Estrada
     *
     * @param integer	Codigo de la programacion de pago
     */
    public function eliminar($codigoProgramacionPago) {
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
    }

    
    /**
     * Generar una programacion de pago, crea pagos, afecta creditos, licencias e incapacidades
     *
     * @author		Mario Estrada
     *
     * @param integer	Codigo de la programacion de pago
     */    
    public function generar($codigoProgramacionPago) {
        $em = $this->getEntityManager(); 
        $strMensaje = "";
        set_time_limit(0);
        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        if($arProgramacionPagoProcesar->getEstadoGenerado() == 0 && $arProgramacionPagoProcesar->getEmpleadosGenerados() == 1 && $arProgramacionPagoProcesar->getInconsistencias() == 0) {
            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
            //Validar que este en el año actual
            if($arProgramacionPagoProcesar->getFechaDesde()->format('Y') <= $arConfiguracion->getAnioActual()) {
                $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPagoProcesar->getCodigoCentroCostoFk());
                
                //Nomina && primas
                if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 1 || $arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 2) { 
                    ini_set("memory_limit", -1);
                    $strSql = "DELETE rhu_pago_detalle FROM rhu_pago_detalle LEFT JOIN rhu_pago on rhu_pago_detalle.codigo_pago_fk = rhu_pago.codigo_pago_pk WHERE rhu_pago.codigo_programacion_pago_fk = " . $codigoProgramacionPago;                           
                    $em->getConnection()->executeQuery($strSql); 
                    $strSql = "DELETE FROM rhu_pago WHERE rhu_pago.codigo_programacion_pago_fk = " . $codigoProgramacionPago;                           
                    $em->getConnection()->executeQuery($strSql);
                    
                    $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $arProgramacionPagoProcesar->getCodigoProgramacionPagoPk()));
                    foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {                        
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->generarPago($arProgramacionPagoDetalle, $arProgramacionPagoProcesar, $arCentroCosto, $arConfiguracion);   
                    }
                    $arProgramacionPagoProcesar->setEstadoGenerado(1);
                    $em->persist($arProgramacionPagoProcesar);
                    $em->flush();                    
                }                
                
                //Cesantias
                if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 3) {
                    $arProgramacionPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                    $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $arProgramacionPagoProcesar->getCodigoProgramacionPagoPk()));
                    foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago->setPagoTipoRel($arProgramacionPagoProcesar->getPagoTipoRel());
                        $arContratoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                        $arPago->setEmpleadoRel($arProgramacionPagoDetalle->getEmpleadoRel());
                        $arPago->setCentroCostoRel($arCentroCosto);
                        $arPago->setFechaDesde($arProgramacionPagoProcesar->getFechaDesde());
                        $arPago->setFechaHasta($arProgramacionPagoProcesar->getFechaHasta());
                        $arPago->setVrSalarioEmpleado($arProgramacionPagoDetalle->getVrSalario());
                        $arPago->setVrSalarioPeriodo($arProgramacionPagoDetalle->getVrDevengado());
                        $arPago->setProgramacionPagoRel($arProgramacionPagoProcesar);
                        $arPago->setDiasPeriodo($arProgramacionPagoDetalle->getDias());
                        if($arProgramacionPagoDetalle->getCodigoContratoFk()) {
                            $arPago->setContratoRel($arProgramacionPagoDetalle->getContratoRel());
                        }
                        $em->persist($arPago);
                        $douSalarioMinimo = $arConfiguracion->getVrSalario();
                        $intDias = $arProgramacionPagoDetalle->getDias();
                        $intDiasContinuos = $arProgramacionPagoDetalle->getFechaDesde()->diff($arProgramacionPagoDetalle->getFechaHasta());
                        $intDiasContinuos = $intDiasContinuos->format('%a');
                        $intDiasContinuos += 1;   
                        
                        $floIbp = $em->getRepository('BrasaRecursoHumanoBundle:RhuIngresoBase')->devuelveIbpFecha($arProgramacionPagoDetalle->getCodigoEmpleadoFk(), $arProgramacionPagoDetalle->getFechaDesdePago()->format('Y-m-d'), $arProgramacionPagoProcesar->getFechaHastaReal()->format('Y-m-d'), $arProgramacionPagoDetalle->getCodigoContratoFk());
                        if($arCentroCosto->getPeriodoPagoRel()->getContinuo() == 1) {
                            $floSalarioPromedio = ($floIbp / $intDiasContinuos) * 30;
                        } else {
                            $floSalarioPromedio = ($floIbp / $intDias) * 30;
                        }                           
                        $strMensajeAuxilioTransporte = "";
                        if(round($floSalarioPromedio) <=  $douSalarioMinimo * 2 ) {
                            $floSalarioPromedio += $arConfiguracion->getVrAuxilioTransporte();
                            $strMensajeAuxilioTransporte = " + Aux. transporte (" . $arConfiguracion->getVrAuxilioTransporte() . ")";
                        }
                        $floTotalPago = ($floSalarioPromedio * $intDias) / 360;

                        //Cesantias
                        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(29);
                        $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                        $arPagoDetalle->setPagoRel($arPago);
                        $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                        $arPagoDetalle->setDetalle($intDias . " Dias de cesantias (IBP " . number_format($floIbp, 0, '.', ',') . " )" . $strMensajeAuxilioTransporte);
                        $arPagoDetalle->setNumeroDias($intDias);
                        $arPagoDetalle->setVrPago($floTotalPago);
                        $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                        $arPagoDetalle->setVrPagoOperado($floTotalPago * $arPagoConcepto->getOperacion());
                        $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                        $em->persist($arPagoDetalle);
                        
                        $floPorcentajeIntereses = (($intDias * 12) / 360)/100;
                        $floTotalPagoIntereses = $floTotalPago * $floPorcentajeIntereses;
                        
                        //Intereses cesantias
                        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(30);
                        $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                        $arPagoDetalle->setPagoRel($arPago);
                        $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                        $arPagoDetalle->setNumeroDias($intDias);
                        $arPagoDetalle->setVrPago($floTotalPagoIntereses);
                        $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                        $arPagoDetalle->setVrPagoOperado($floTotalPagoIntereses * $arPagoConcepto->getOperacion());
                        $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                        $em->persist($arPagoDetalle);                        
                    }
                    $arProgramacionPagoProcesar->setEstadoGenerado(1);                                
                    $em->persist($arProgramacionPagoProcesar);
                    $em->flush();
                    //$em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->liquidar($codigoProgramacionPago);
                }                
            } else {
                $strMensaje = "No se puede generar programacion porque el año es mayor al ultimo año cerrado";
            }                            
        } else {
            $strMensaje = "Solo se pueden generar programaciones que no esten generadas y que tengan empleados o tiene inconsistencias en la programacion";
        }        
        set_time_limit(90);
        return $strMensaje;
    }
    
    /**
     * Elimina los pagos generados de esta programacion y revierte los procesos
     *
     * @author		Mario Estrada
     *
     * @param integer	Codigo de la programacion de pago
     */
    public function deshacer($codigoProgramacionPago) {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getEntityManager();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();        
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);

        //Eliminar pagos
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
        $arProgramacionPago->setNoGeneraPeriodo(1);
        $arProgramacionPago->setVrNeto(0);
        $arProgramacionPago->setNumeroEmpleados(0);
        $arProgramacionPago->setEstadoGenerado(0);
        $em->persist($arProgramacionPago);
        $em->flush();
        return true;
    }

    /**
     * Liquidar toda la programacion de pago
     *
     * @author		Mario Estrada
     *
     * @param integer	Codigo de la programacion de pago
     */
    public function liquidar($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        set_time_limit(0);
        $numeroPagos = 0;
        $douNetoTotal = 0;        
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);        
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));        
        foreach ($arPagos as $arPago) {            
            $douNeto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->Liquidar($arPago->getCodigoPagoPk(), $arConfiguracion);
            $douNetoTotal += $douNeto;
            if($arPago->getCodigoProgramacionPagoDetalleFk()) {
                $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();                
                $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($arPago->getCodigoProgramacionPagoDetalleFk());
                $arProgramacionPagoDetalle->setVrNetoPagar($douNeto);
                $em->persist($arProgramacionPagoDetalle);
            }
            $numeroPagos++;
        }
        $arProgramacionPago->setVrNeto($douNetoTotal);
        $arProgramacionPago->setNumeroEmpleados($numeroPagos);
        $em->persist($arProgramacionPago);
        $em->flush();
        set_time_limit(90);
        return true;
    }

    /**
     * Valida si se puede pagar la programacion
     *
     * @author		Mario Estrada
     *
     * @param integer	Codigo de la programacion de pago
     */
    public function validarPagar($codigoProgramacionPago) {       
        $em = $this->getEntityManager(); 
        $errores = "";
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        if($arProgramacionPagoProcesar->getEstadoGenerado() == 1) {
            $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
            $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
            foreach ($arPagos as $arPago) { 
                if($arPago->getVrNeto() < 0) {
                    if($errores == "") {
                       $errores = "No se puede pagar la programacion porque existen pagos negativos"; 
                    }
                }
            }                                                             
        }                    
        return $errores;
    }    
    
    /**
     * Paga la programacion pago, este proceso no se puede deshacer
     *
     * @author		Mario Estrada
     *
     * @param integer	Codigo de la programacion de pago
     */
    public function pagar($codigoProgramacionPago) {       
        $em = $this->getEntityManager(); 
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPagoProcesar->getCodigoCentroCostoFk());                                
        if($arProgramacionPagoProcesar->getEstadoGenerado() == 1) {
            $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
            $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
            foreach ($arPagos as $arPago) {
                $arPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                $arPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($arPago->getCodigoPagoPk());                        
                $arPagosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                $arPagosDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $arPago->getCodigoPagoPk()));
                foreach ($arPagosDetalles AS $arPagoDetalle) {
                    if($arPagoDetalle->getCodigoCreditoFk() != "") {                        
                        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($arPagoDetalle->getCodigoCreditoFk());
                        //Crear credito pago, se guarda el pago en la tabla rhu_pago_credito
                        $arPagoCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
                        $arPagoCredito->setCreditoRel($arCredito);
                        $arPagoCredito->setPagoRel($arPagoDetalle->getPagoRel());
                        $arPagoCredito->setfechaPago(new \ DateTime("now"));
                        $arPagoCredito->setCreditoTipoPagoRel($arCredito->getCreditoTipoPagoRel());
                        $arPagoCredito->setVrCuota($arPagoDetalle->getVrPago());
                        //Actualizar el saldo del credito
                        $arCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual() + 1);
                        $arCredito->setSaldo($arCredito->getSaldo() - $arPagoDetalle->getVrPago()); 
                        $arCredito->setTotalPagos($arCredito->getTotalPagos() + $arPagoDetalle->getVrPago());
                        if($arCredito->getSaldo() <= 0) {
                           $arCredito->setEstadoPagado(1);
                        }
                        $arPagoCredito->setSaldo($arCredito->getSaldo());
                        $arPagoCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual());
                        $em->persist($arPagoCredito);
                        $em->persist($arCredito);
                    }                    
                }                                      

                $arPagoProcesar->setNumero($em->getRepository('BrasaRecursoHumanoBundle:RhuConsecutivo')->consecutivo(1));
                $arPagoProcesar->setEstadoPagado(1);
                $em->persist($arPagoProcesar); 
                
                $douIngresoBaseCotizacion = $arPagoProcesar->getVrIngresoBaseCotizacion();
                $douIngresoBasePrestacion = $arPagoProcesar->getVrIngresoBasePrestacion();
                if($arPagoProcesar->getCodigoPagoTipoFk() == 1) {
                    if($arPagoProcesar->getFechaDesdePago()->format('m') == $arPagoProcesar->getFechaHastaPago()->format('m')) {
                        $arIngresoBase = new \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase();
                        $arIngresoBase->setFechaDesde($arPagoProcesar->getFechaDesdePago());                        
                        $arIngresoBase->setFechaHasta($arPagoProcesar->getFechaHastaPago());
                        $arIngresoBase->setContratoRel($arPagoProcesar->getContratoRel());
                        $arIngresoBase->setEmpleadoRel($arPagoProcesar->getEmpleadoRel());
                        $arIngresoBase->setVrIngresoBaseCotizacion($douIngresoBaseCotizacion);
                        $arIngresoBase->setVrIngresoBasePrestacion($douIngresoBasePrestacion);
                        $em->persist($arIngresoBase);
                    } else {
                        $intDiasHasta = $arPagoProcesar->getFechaHastaPago()->format('j');
                        $intDiasDesde = $arPagoProcesar->getDiasPeriodo() - $intDiasHasta;
                        $vrIbcPromedioDia = 0;
                        $vrIbpPromedioDia = 0;
                        if($arPagoProcesar->getDiasPeriodo() > 0) {
                            $vrIbcPromedioDia = $douIngresoBaseCotizacion / $arPagoProcesar->getDiasPeriodo();                        
                            $vrIbpPromedioDia = $douIngresoBasePrestacion / $arPagoProcesar->getDiasPeriodo();                        
                        }                    
                        $strAnio = $arPagoProcesar->getFechaDesdePago()->format('Y');
                        $strMes = $arPagoProcesar->getFechaDesdePago()->format('m');
                        $intUltimoDiaMes = date("d",(mktime(0,0,0,$strMes+1,1,$strAnio)-1));
                        $strFechaHasta = $arPagoProcesar->getFechaDesdePago()->format('Y/m') ."/" . $intUltimoDiaMes;
                        $arIngresoBase = new \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase();                       
                        $arIngresoBase->setFechaDesde($arPagoProcesar->getFechaDesdePago());                                                
                        $arIngresoBase->setFechaHasta(date_create($strFechaHasta));
                        $arIngresoBase->setContratoRel($arPagoProcesar->getContratoRel());
                        $arIngresoBase->setEmpleadoRel($arPagoProcesar->getEmpleadoRel());
                        $arIngresoBase->setVrIngresoBaseCotizacion( $vrIbcPromedioDia * $intDiasDesde);
                        $arIngresoBase->setVrIngresoBasePrestacion( $vrIbpPromedioDia * $intDiasDesde);
                        $em->persist($arIngresoBase);

                        $strFechaDesde = $arPagoProcesar->getFechaHastaPago()->format('Y/m') . "/01";
                        $arIngresoBase = new \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase();
                        $arIngresoBase->setFechaDesde(date_create($strFechaDesde));
                        $arIngresoBase->setFechaHasta($arPagoProcesar->getFechaHastaPago());
                        $arIngresoBase->setContratoRel($arPagoProcesar->getContratoRel());
                        $arIngresoBase->setEmpleadoRel($arPagoProcesar->getEmpleadoRel());
                        $arIngresoBase->setVrIngresoBaseCotizacion($vrIbcPromedioDia * $intDiasHasta);
                        $arIngresoBase->setVrIngresoBasePrestacion($vrIbpPromedioDia * $intDiasHasta);
                        $em->persist($arIngresoBase);                    

                    }                    
                }
            }
            
            //Actualizar los contratos
            $arProgramacionPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
            $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
            foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {
                if($arProgramacionPagoDetalle->getCodigoContratoFk()) {
                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arProgramacionPagoDetalle->getCodigoContratoFk());                                                                        
                    if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 1) {                            
                        $arContrato->setFechaUltimoPago($arProgramacionPagoProcesar->getFechaHasta());
                    }
                    if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 2) {                        
                        $arContrato->setFechaUltimoPagoPrimas($arProgramacionPagoProcesar->getFechaHasta());
                    }
                    if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 3) {                       
                        $arContrato->setFechaUltimoPagoCesantias($arProgramacionPagoProcesar->getFechaHasta());
                    }   
                    $em->persist($arContrato);                            
                }                        
            }
            
            //Actualizar centros de costos
            if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 1) {
                $arCentroCosto->setFechaUltimoPago($arProgramacionPagoProcesar->getFechaHasta());
            }
            if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 2) {
                $arCentroCosto->setFechaUltimoPagoPrima($arProgramacionPagoProcesar->getFechaHasta());
            }
            if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 3) {
                $arCentroCosto->setFechaUltimoPagoCesantias($arProgramacionPagoProcesar->getFechaHasta());
            }                                            
            $em->persist($arCentroCosto);
            $arProgramacionPagoProcesar->setEstadoPagado(1);
            $em->persist($arProgramacionPagoProcesar);
            $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->liquidar($codigoProgramacionPago);
            if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 1) {
                if($arProgramacionPagoProcesar->getNoGeneraPeriodo() == 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarProgramacionPago($arProgramacionPagoProcesar->getCodigoCentroCostoFk(), 1);
                }                   
            }         
        }            
        $em->flush();   
        
    }    
    
    /**
     * Listar las programaciones de pago segun parametros
     *
     * @author		Mario Estrada
     *
     * @param string	$strFechaDesde          Fecha desde
     * @param string    $strFechaHasta          Fecha hasta
     * @param integer   $codigoCentroCosto      Codigo del centro de costos
     * @param boolean   $boolMostrarGenerados   Generados
     * @param boolean   $boolMostrarPagados     Pagados
     */
    public function listaDQL($strFechaDesde = "", $strFechaHasta = "", $codigoCentroCosto, $boolMostrarGenerados, $boolMostrarPagados, $intTipo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pp FROM BrasaRecursoHumanoBundle:RhuProgramacionPago pp WHERE pp.codigoProgramacionPagoPk <> 0 ";
        if($strFechaDesde != "" ) {
            $dql .= " AND pp.fechaHasta >='" . $strFechaDesde . "'";
        }

        if($strFechaHasta != "") {
            $dql .= " AND pp.fechaHasta <='" . $strFechaHasta . "'";
        }
        if($codigoCentroCosto != "" && $codigoCentroCosto != 0) {
            $dql .= " AND pp.codigoCentroCostoFk =" . $codigoCentroCosto;
        }
        if($intTipo != "" && $intTipo != 0) {
            $dql .= " AND pp.codigoPagoTipoFk =" . $intTipo;
        }        
        if($boolMostrarGenerados == 1 ) {
            $dql .= " AND pp.estadoGenerado = 1";
        }
        if($boolMostrarGenerados == "0") {
            $dql .= " AND pp.estadoGenerado = 0";
        }
        if($boolMostrarPagados == 1 ) {
            $dql .= " AND pp.estadoPagado = 1";
        }
        if($boolMostrarPagados == "0") {
            $dql .= " AND pp.estadoPagado = 0";
        }
        $dql .= " ORDER BY pp.codigoProgramacionPagoPk DESC";
        return $dql;
    }

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
    
    //listado nuevo por el cambio de centro de costo por programacion de pago
    public function listaGeneralPagoActivosDQL($strEstado = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pp FROM BrasaRecursoHumanoBundle:RhuProgramacionPago pp WHERE pp.estadoGenerado = 0 order by pp.fechaDesde Desc";
        $query = $em->createQuery($dql);
        $dql = $query->getResult();
        return $dql;
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
        set_time_limit(0);
        $intNumeroEmpleados = 0;
        $floNetoTotal = 0;
        $boolInconsistencias = 0;
        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoInconsistencia')->eliminarProgramacionPago($codigoProgramacionPago);
        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->eliminarEmpleados($codigoProgramacionPago);
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);        
        $arProgramacionPago->setInconsistencias(0);
        //Nomina
        if($arProgramacionPago->getCodigoPagoTipoFk() == 1) {             
            $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                    . "WHERE c.codigoCentroCostoFk = " . $arProgramacionPago->getCodigoCentroCostoFk()
                    . " AND c.fechaUltimoPago < '" . $arProgramacionPago->getFechaHastaReal()->format('Y-m-d') . "' "
                    . " AND c.fechaDesde <= '" . $arProgramacionPago->getFechaHastaReal()->format('Y-m-d') . "' "
                    . " AND (c.fechaHasta >= '" . $arProgramacionPago->getFechaDesde()->format('Y-m-d') . "' "
                    . " OR c.indefinido = 1)"; 
            $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $query = $em->createQuery($dql);
            $arContratos = $query->getResult();
            foreach ($arContratos as $arContrato) {                
                $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                $arProgramacionPagoDetalle->setProgramacionPagoRel($arProgramacionPago);
                $arProgramacionPagoDetalle->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arProgramacionPagoDetalle->setContratoRel($arContrato);                
                $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalarioPago());
                $arProgramacionPagoDetalle->setIndefinido($arContrato->getIndefinido());
                $arProgramacionPagoDetalle->setSalarioIntegral($arContrato->getSalarioIntegral());
                if($arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 4 || $arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 5) {
                    $arProgramacionPagoDetalle->setDescuentoPension(0);
                    $arProgramacionPagoDetalle->setDescuentoSalud(0);
                    $arProgramacionPagoDetalle->setPagoAuxilioTransporte(0);
                }
                if ($arContrato->getCodigoTipoPensionFk() == 5){
                    $arProgramacionPagoDetalle->setDescuentoPension(0);
                }
                $dateFechaDesde =  "";
                $dateFechaHasta =  "";
                $intDiasDevolver = 0;
                $fechaFinalizaContrato = $arContrato->getFechaHasta();
                if($arContrato->getIndefinido() == 1) {
                    $fecha = date_create(date('Y-m-d'));
                    date_modify($fecha, '+100000 day');
                    $fechaFinalizaContrato = $fecha;
                }
                if($arContrato->getFechaDesde() <  $arProgramacionPago->getFechaDesde() == true) {
                    $dateFechaDesde = $arProgramacionPago->getFechaDesde();
                } else {
                    if($arContrato->getFechaDesde() > $arProgramacionPago->getFechaHasta() == true) {
                        if($arContrato->getFechaDesde() == $arProgramacionPago->getFechaHastaReal()) {
                            $dateFechaDesde = $arProgramacionPago->getFechaHastaReal();
                            $intDiasDevolver = 1;                        
                        } else {
                            $intDiasDevolver = 0;                        
                        }
                        
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
                        
                        if($dateFechaDesde->format('d') == "31") {
                            $intDiasDevolver = 1;
                        } else {
                            $intDiasDevolver += $intDiasInhabilesFebrero;
                        }
                    } else {
                        $intDiasDevolver += $intDiasInhabilesFebrero;
                    }                    
                }
                
                $arProgramacionPagoDetalle->setFechaDesdePago($dateFechaDesde);
                $arProgramacionPagoDetalle->setFechaHastaPago($dateFechaHasta);
                $arProgramacionPagoDetalle->setFechaDesde($arContrato->getFechaDesde());
                $arProgramacionPagoDetalle->setFechaHasta($dateFechaHasta);
                
                $floValorDia = $arContrato->getVrSalarioPago() / 30;       
                $floValorHora = $floValorDia / $arContrato->getFactorHorasDia();   
                $arProgramacionPagoDetalle->setVrDia($floValorDia);
                $arProgramacionPagoDetalle->setVrHora($floValorHora);
                $floDevengado = $arProgramacionPagoDetalle->getDias() * $floValorDia;
                $arProgramacionPagoDetalle->setVrDevengado($floDevengado);
                $floCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->cuotaCreditosNomina($arContrato->getCodigoEmpleadoFk());
                $arProgramacionPagoDetalle->setVrCreditos($floCreditos);
                $floDeducciones = $floCreditos;
                $arProgramacionPagoDetalle->setVrDeducciones($floDeducciones);
                $floNeto = $floDevengado - $floDeducciones;
                $arProgramacionPagoDetalle->setVrNetoPagar($floNeto);                
                
                //dias vacaciones
                $arrVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->dias($arContrato->getCodigoEmpleadoFk(), $arContrato->getCodigoContratoPk(), $arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta());                
                $intDiasVacaciones = $arrVacaciones['dias'];                                
                if($intDiasVacaciones > 0) {                                        
                    $arProgramacionPagoDetalle->setDiasVacaciones($intDiasVacaciones);
                    $arProgramacionPagoDetalle->setIbcVacaciones($arrVacaciones['ibc']);
                }                
                //Licencias
                $intDiasLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->diasLicenciaPeriodo($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta(), $arContrato->getCodigoEmpleadoFk());                
                if($intDiasLicencia > 0) {                                        
                    $arProgramacionPagoDetalle->setDiasLicencia($intDiasLicencia);
                }     
                //dias incapacidad
                $intDiasIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidadPeriodo($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta(), $arContrato->getCodigoEmpleadoFk());                
                if($intDiasIncapacidad > 0) {                                        
                    $arProgramacionPagoDetalle->setDiasIncapacidad($intDiasIncapacidad);
                }     
                
                $diasNovedad = $intDiasIncapacidad + $intDiasLicencia + $intDiasVacaciones;
                $dias = $intDiasDevolver - $diasNovedad;
                $arProgramacionPagoDetalle->setDias($dias);
                $arProgramacionPagoDetalle->setDiasReales($intDiasDevolver);                
                
                $horasNovedad = ($intDiasIncapacidad + $intDiasLicencia + $intDiasVacaciones) * 8;
                $horasDiurnas = ($intDiasDevolver * $arContrato->getFactorHorasDia()) - $horasNovedad;
                $arProgramacionPagoDetalle->setHorasPeriodo($horasDiurnas);
                $arProgramacionPagoDetalle->setHorasDiurnas($horasDiurnas);
                $arProgramacionPagoDetalle->setHorasPeriodoReales($horasDiurnas);
                $arProgramacionPagoDetalle->setFactorDia($arContrato->getFactorHorasDia());  
                $diasTransporte = $intDiasDevolver - ($intDiasVacaciones+$intDiasLicencia+$intDiasIncapacidad);
                $arProgramacionPagoDetalle->setDiasTransporte($diasTransporte);
                $em->persist($arProgramacionPagoDetalle);
                
                if($floNeto < 0) {
                    $boolInconsistencias = 1;
                    $arProgramacionPagoInconsistencia = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoInconsistencia();
                    $arProgramacionPagoInconsistencia->setProgramacionPagoRel($arProgramacionPago);
                    $arProgramacionPagoInconsistencia->setInconsistencia("El empleado " . $arContrato->getEmpleadoRel()->getNombreCorto() . " tiene deducciones muy altas");
                    $em->persist($arProgramacionPagoInconsistencia);
                }
                $intNumeroEmpleados++;
                $floNetoTotal += $floNeto;                                
                
            }
            $arProgramacionPago->setNumeroEmpleados($intNumeroEmpleados);
            //$arProgramacionPago->setVrNeto($floNetoTotal);
            $arProgramacionPago->setInconsistencias($boolInconsistencias);
            $em->flush();            
        }
        
        //Primas
        if($arProgramacionPago->getCodigoPagoTipoFk() == 2) {
            $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);
            $arParametrosPrestacionPrima = new \Brasa\RecursoHumanoBundle\Entity\RhuParametroPrestacion();
            $arParametrosPrestacionPrima = $em->getRepository('BrasaRecursoHumanoBundle:RhuParametroPrestacion')->findBy(array('tipo' => 'PRI'));                                                                
            $salarioMinimo = $arConfiguracion->getVrSalario();
            $auxilioTransporte = $arConfiguracion->getVrAuxilioTransporte();            
            $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                . "WHERE c.codigoCentroCostoFk = " . $arProgramacionPago->getCodigoCentroCostoFk()
                . " AND c.fechaUltimoPagoPrimas < '" . $arProgramacionPago->getFechaHasta()->format('Y-m-d') . "' "                    
                . " AND c.fechaDesde <= '" . $arProgramacionPago->getFechaHasta()->format('Y-m-d') . "' "
                . " AND (c.fechaHasta >= '" . $arProgramacionPago->getFechaDesde()->format('Y-m-d') . "' "
                . " OR c.indefinido = 1) "
                . " AND c.estadoLiquidado = 0 AND c.codigoContratoClaseFk <> 4 AND c.codigoContratoClaseFk <> 5 AND c.salarioIntegral = 0";           
            
            $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $query = $em->createQuery($dql);
            $arContratos = $query->getResult();
            foreach ($arContratos as $arContrato) {
                $dateFechaDesde = $arContrato->getFechaUltimoPagoPrimas();
                $dateFechaHasta = $arProgramacionPago->getFechaHasta();
                $dateFechaHastaPago = $arContrato->getFechaUltimoPago();
                $douSalario = $arContrato->getVrSalarioPago();
                $intDiasPrima = 0;                                
                $intDiasPrima = $objFunciones->diasPrestaciones($dateFechaDesde, $dateFechaHasta);
                $intDiasSalrioPromedio = $objFunciones->diasPrestaciones($dateFechaDesde, $dateFechaHastaPago);
                $intDiasPrimaLiquidar = $intDiasPrima;
//                if($dateFechaDesde->format('m-d') == '06-30' || $dateFechaDesde->format('m-d') == '12-30') {
//                    $intDiasPrimaLiquidar -= 1;
//                    $intDiasSalrioPromedio -= 1;
//                    $intDiasPrima -= 1;
//                }
                $ibpPrimasInicial = $arContrato->getIbpPrimasInicial();                    
                $ibpPrimas = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->ibp($dateFechaDesde->format('Y-m-d'), $dateFechaHastaPago->format('Y-m-d'), $arContrato->getCodigoContratoPk());                
                $ibpPrimas += $ibpPrimasInicial;                                            
                $salarioPromedioPrimas = 0;
                if($arContrato->getCodigoSalarioTipoFk() == 2) {
                    if($intDiasSalrioPromedio > 0) {
                        //Se realiza para seracis
                        if($arConfiguracion->getPromedioPrimasLaborado()) {
                            if($arConfiguracion->getPromedioPrimasLaboradoDias() > 0) {
                                $intDiasPrima = $arConfiguracion->getPromedioPrimasLaboradoDias();
                            }                            
                            $salarioPromedioPrimas = ($ibpPrimas / ($intDiasPrima-15)) * 30;
                            if($salarioPromedioPrimas < $salarioMinimo) {
                                $salarioPromedioPrimas = $salarioMinimo + $auxilioTransporte;
                            }
                        } else {
                            $salarioPromedioPrimas = ($ibpPrimas / $intDiasSalrioPromedio) * 30;     
                        }                                                 
                    } else {
                        if($arContrato->getEmpleadoRel()->getAuxilioTransporte() == 1) {
                            $salarioPromedioPrimas = $douSalario + $auxilioTransporte;
                        } else {
                            $salarioPromedioPrimas = $douSalario;
                        }                         
                    }                                                        
                } else {
                    //Comisiones
                    $ibpConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->ibpConceptos($dateFechaDesde->format('Y-m-d'), $dateFechaHastaPago->format('Y-m-d'), $arContrato->getCodigoContratoPk());                
                    $otrosConceptos = 0;
                    if($intDiasSalrioPromedio > 0) {
                        $otrosConceptos = ($ibpConceptos / $intDiasSalrioPromedio) * 30;
                    }                    
                    if($arContrato->getEmpleadoRel()->getAuxilioTransporte() == 1) {
                        $salarioPromedioPrimas = $douSalario + $auxilioTransporte + $otrosConceptos;
                    } else {
                        $salarioPromedioPrimas = $douSalario + $otrosConceptos;
                    }                                                
                }      
                $aplicaPorcentaje = true;
                if($arContrato->getEmpleadoRel()->getPagadoEntidadSalud()) {
                    $salarioPromedioPrimas = $douSalario;
                    $aplicaPorcentaje = false;                
                }                 
                $porcentaje = 100;                                               
                if($arConfiguracion->getPrestacionesAplicaPorcentajeSalario()) {                            
                    if($arContrato->getCodigoSalarioTipoFk() == 2 && $aplicaPorcentaje) {                                    
                        $intDiasLaborados = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestaciones($arContrato->getFechaDesde(), $dateFechaHasta);                                
                        foreach ($arParametrosPrestacionPrima as $arParametroPrestacion) {
                            if($intDiasLaborados >= $arParametroPrestacion->getDiaDesde() && $intDiasLaborados <= $arParametroPrestacion->getDiaHasta()) {
                                if($arParametroPrestacion->getOrigen() == 'SAL') {
                                    if($arContrato->getEmpleadoRel()->getAuxilioTransporte() == 1) {
                                        $salarioPromedioPrimas = $douSalario + $auxilioTransporte;
                                    } else {
                                        $salarioPromedioPrimas = $douSalario;
                                    } 
                                } else {
                                    $porcentaje = $arParametroPrestacion->getPorcentaje();
                                    $salarioPromedioPrimas = ($salarioPromedioPrimas * $porcentaje)/100;                                
                                }                                            
                            }
                        }                               
                    }                                                        
                }   
                $diasAusentismo = 0;
                if($arConfiguracion->getDiasAusentismoPrimas()) {
                    $diasAusentismo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->diasAusentismo($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arContrato->getCodigoContratoPk());                                                
                }
                $salarioPromedioPrimas = round($salarioPromedioPrimas);                                                
                $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                $arProgramacionPagoDetalle->setProgramacionPagoRel($arProgramacionPago);
                $arProgramacionPagoDetalle->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arProgramacionPagoDetalle->setContratoRel($arContrato);
                $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalario());
                $arProgramacionPagoDetalle->setVrSalarioPrima($salarioPromedioPrimas);
                $arProgramacionPagoDetalle->setIndefinido($arContrato->getIndefinido());                
                $arProgramacionPagoDetalle->setFechaDesde($dateFechaDesde);
                $arProgramacionPagoDetalle->setFechaHasta($arProgramacionPago->getFechaHasta());
                $arProgramacionPagoDetalle->setFechaDesdePago($dateFechaDesde);
                $arProgramacionPagoDetalle->setDias($intDiasPrimaLiquidar);
                $arProgramacionPagoDetalle->setDiasReales($intDiasPrimaLiquidar);
                $arProgramacionPagoDetalle->setPorcentajeIbp($porcentaje);
                $arProgramacionPagoDetalle->setDiasAusentismo($diasAusentismo);
                $em->persist($arProgramacionPagoDetalle);
                $intNumeroEmpleados++;
                
            }
            $arProgramacionPago->setNumeroEmpleados($intNumeroEmpleados);            
            $em->flush();           
        }
        
        //Cesantias
        if($arProgramacionPago->getCodigoPagoTipoFk() == 3) {
            $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                . "WHERE c.codigoCentroCostoFk = " . $arProgramacionPago->getCodigoCentroCostoFk()
                . " AND c.fechaUltimoPagoCesantias < '" . $arProgramacionPago->getFechaHasta()->format('Y-m-d') . "' "                    
                . " AND c.fechaDesde <= '" . $arProgramacionPago->getFechaHasta()->format('Y-m-d') . "' "
                . " AND (c.fechaHasta >= '" . $arProgramacionPago->getFechaDesde()->format('Y-m-d') . "' "
                . " OR c.indefinido = 1) "
                . " AND c.estadoLiquidado = 0 AND c.codigoContratoTipoFk <> 4 AND c.codigoContratoTipoFk <> 5 and c.salarioIntegral = 0";            
            $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $query = $em->createQuery($dql);
            $arContratos = $query->getResult();
            foreach ($arContratos as $arContrato) {
                $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                $arProgramacionPagoDetalle->setProgramacionPagoRel($arProgramacionPago);
                $arProgramacionPagoDetalle->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arProgramacionPagoDetalle->setContratoRel($arContrato);
                $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalario());
                $arProgramacionPagoDetalle->setIndefinido($arContrato->getIndefinido());
                $dateFechaDesde =  "";
                $dateFechaDesdePago =  "";
                if($arContrato->getFechaUltimoPagoCesantias() <=  $arProgramacionPago->getFechaDesde() == true) {
                    $dateFechaDesde = $arProgramacionPago->getFechaDesde();
                    $dateFechaDesdePago = $arProgramacionPago->getFechaDesde();
                    if($arContrato->getFechaDesde() >= $dateFechaDesde) {
                        $dateFechaDesde = $arContrato->getFechaDesde();
                        $dateFechaDesdePago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->fechaPrimerPago($arContrato->getCodigoContratoPk());
                        if($dateFechaDesdePago) {
                            $dateFechaDesdePago = date_create_from_format('Y-m-d H:i', $dateFechaDesdePago . "00:00");
                        } else {
                            $dateFechaDesdePago = $dateFechaDesde;                            
                        }
                    }
                    
                } else {
                    $dateFechaDesde = $arContrato->getFechaUltimoPagoCesantias();
                    $dateFechaDesdePago = $arContrato->getFechaUltimoPagoCesantias();
                }
                $intDia = $dateFechaDesde->format('j');
                $intDiasMes = 31 - $intDia;                
                $intMes = $dateFechaDesde->format('n');                
                $intMesFinal = $arProgramacionPago->getFechaHasta()->format('n');
                $intMeses = $intMesFinal - $intMes;
                $intDias = ($intMeses * 30) + $intDiasMes;
                
                $arProgramacionPagoDetalle->setFechaDesde($dateFechaDesde);
                $arProgramacionPagoDetalle->setFechaHasta($arProgramacionPago->getFechaHasta());
                $arProgramacionPagoDetalle->setFechaDesdePago($dateFechaDesdePago);
                $arProgramacionPagoDetalle->setDias($intDias);
                $arProgramacionPagoDetalle->setDiasReales($intDias);
                $em->persist($arProgramacionPagoDetalle);
                $intNumeroEmpleados++;
                
            }
            $arProgramacionPago->setNumeroEmpleados($intNumeroEmpleados);            
            $em->flush();           
        }        
        
        return true;
    }
    
    public function actualizarEmpleado($codigoProgramacionPagoDetalle) {
        $em = $this->getEntityManager();
        set_time_limit(0);
        $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($codigoProgramacionPagoDetalle);        
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arProgramacionPago = $arProgramacionPagoDetalle->getProgramacionPagoRel();        
        
        //Nomina
        if($arProgramacionPago->getCodigoPagoTipoFk() == 1) {
            $arContrato = $arProgramacionPagoDetalle->getContratoRel();                            
            $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalarioPago());
            $arProgramacionPagoDetalle->setIndefinido($arContrato->getIndefinido());
            $arProgramacionPagoDetalle->setSalarioIntegral($arContrato->getSalarioIntegral());
            if($arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 4 || $arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 5) {
                $arProgramacionPagoDetalle->setDescuentoPension(0);
                $arProgramacionPagoDetalle->setDescuentoSalud(0);
                $arProgramacionPagoDetalle->setPagoAuxilioTransporte(0);
            }
            if ($arContrato->getCodigoTipoPensionFk() == 5){
                $arProgramacionPagoDetalle->setDescuentoPension(0);
            }

            $dateFechaDesde =  "";
            $dateFechaHasta =  "";
            $intDiasDevolver = 0;
            $fechaFinalizaContrato = $arContrato->getFechaHasta();
            if($arContrato->getIndefinido() == 1) {
                $fecha = date_create(date('Y-m-d'));
                date_modify($fecha, '+100000 day');
                $fechaFinalizaContrato = $fecha;
            }
            if($arContrato->getFechaDesde() <  $arProgramacionPago->getFechaDesde() == true) {
                $dateFechaDesde = $arProgramacionPago->getFechaDesde();
            } else {
                if($arContrato->getFechaDesde() > $arProgramacionPago->getFechaHasta() == true) {
                    if($arContrato->getFechaDesde() == $arProgramacionPago->getFechaHastaReal()) {
                        $dateFechaDesde = $arProgramacionPago->getFechaHastaReal();
                        $intDiasDevolver = 1;                        
                    } else {
                        $intDiasDevolver = 0;                        
                    }

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

                    if($dateFechaDesde->format('d') == "31") {
                        $intDiasDevolver = 1;
                    } else {
                        $intDiasDevolver += $intDiasInhabilesFebrero;
                    }
                } else {
                    $intDiasDevolver += $intDiasInhabilesFebrero;
                }                    
            }        

            $floValorDia = $arContrato->getVrSalarioPago() / 30;       
            $floValorHora = $floValorDia / $arContrato->getFactorHorasDia();   
            $arProgramacionPagoDetalle->setVrDia($floValorDia);
            $arProgramacionPagoDetalle->setVrHora($floValorHora);
            $floDevengado = $arProgramacionPagoDetalle->getDias() * $floValorDia;
            $arProgramacionPagoDetalle->setVrDevengado($floDevengado);
            $floCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->cuotaCreditosNomina($arContrato->getCodigoEmpleadoFk());
            $arProgramacionPagoDetalle->setVrCreditos($floCreditos);
            $floDeducciones = $floCreditos;
            $arProgramacionPagoDetalle->setVrDeducciones($floDeducciones);
            $floNeto = $floDevengado - $floDeducciones;
            $arProgramacionPagoDetalle->setVrNetoPagar($floNeto);                

            //dias vacaciones
            $arrVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->dias($arContrato->getCodigoEmpleadoFk(), $arContrato->getCodigoContratoPk(), $arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta());               
            $intDiasVacaciones = $arrVacaciones['dias'];
            if($intDiasVacaciones > 0) {                                        
                $arProgramacionPagoDetalle->setDiasVacaciones($intDiasVacaciones);
                $arProgramacionPagoDetalle->setIbcVacaciones($arrVacaciones['ibc']);
            }                
            //Licencias
            $intDiasLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->diasLicenciaPeriodo($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta(), $arContrato->getCodigoEmpleadoFk());                
            if($intDiasLicencia > 0) {                                        
                $arProgramacionPagoDetalle->setDiasLicencia($intDiasLicencia);
            }     
            //dias incapacidad
            $intDiasIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidadPeriodo($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta(), $arContrato->getCodigoEmpleadoFk());                
            if($intDiasIncapacidad > 0) {                                        
                $arProgramacionPagoDetalle->setDiasIncapacidad($intDiasIncapacidad);
            }  
            $diasNovedad = $intDiasIncapacidad + $intDiasLicencia + $intDiasVacaciones;
            $dias = $intDiasDevolver - $diasNovedad;
            $arProgramacionPagoDetalle->setDias($dias);
            $arProgramacionPagoDetalle->setDiasReales($intDiasDevolver);

            $horasNovedad = ($intDiasIncapacidad + $intDiasLicencia + $intDiasVacaciones) * 8;
            $horasDiurnas = ($intDiasDevolver * $arContrato->getFactorHorasDia()) - $horasNovedad;
            $arProgramacionPagoDetalle->setHorasPeriodo($horasDiurnas);
            $arProgramacionPagoDetalle->setHorasDiurnas($horasDiurnas);
            $arProgramacionPagoDetalle->setHorasPeriodoReales($horasDiurnas);
            $arProgramacionPagoDetalle->setFactorDia($arContrato->getFactorHorasDia());                
            $em->persist($arProgramacionPagoDetalle);                                

            $em->flush();                                                
        }        

        //Primas
        if($arProgramacionPago->getCodigoPagoTipoFk() == 2) {
            $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);
            $arParametrosPrestacionPrima = new \Brasa\RecursoHumanoBundle\Entity\RhuParametroPrestacion();
            $arParametrosPrestacionPrima = $em->getRepository('BrasaRecursoHumanoBundle:RhuParametroPrestacion')->findBy(array('tipo' => 'PRI'));                                                                
            $salarioMinimo = $arConfiguracion->getVrSalario();
            $auxilioTransporte = $arConfiguracion->getVrAuxilioTransporte();            
            $arContrato = $arProgramacionPagoDetalle->getContratoRel();            
            
            $dateFechaDesde = $arContrato->getFechaUltimoPagoPrimas();
            $dateFechaHasta = $arProgramacionPago->getFechaHasta();
            $dateFechaHastaPago = $arContrato->getFechaUltimoPago();
            $douSalario = $arContrato->getVrSalarioPago();
            $intDiasPrima = 0;                                
            $intDiasPrima = $objFunciones->diasPrestaciones($dateFechaDesde, $dateFechaHasta);
            $intDiasSalrioPromedio = $objFunciones->diasPrestaciones($dateFechaDesde, $dateFechaHastaPago);
            $intDiasPrimaLiquidar = $intDiasPrima;
            if($dateFechaDesde->format('m-d') == '06-30' || $dateFechaDesde->format('m-d') == '12-30') {
                $intDiasPrimaLiquidar -= 1;
                $intDiasSalrioPromedio -= 1;
                $intDiasPrima -= 1;
            }
            $ibpPrimasInicial = $arContrato->getIbpPrimasInicial();                    
            $ibpPrimas = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->ibp($dateFechaDesde->format('Y-m-d'), $dateFechaHastaPago->format('Y-m-d'), $arContrato->getCodigoContratoPk());                
            $ibpPrimas += $ibpPrimasInicial;                                            
            $salarioPromedioPrimas = 0;
            if($arContrato->getCodigoSalarioTipoFk() == 2) {
                if($intDiasSalrioPromedio > 0) {
                    //Se realiza para seracis
                    if($arConfiguracion->getPromedioPrimasLaborado()) {
                        if($arConfiguracion->getPromedioPrimasLaboradoDias() > 0) {
                            $intDiasPrima = $arConfiguracion->getPromedioPrimasLaboradoDias();
                        }                        
                        $salarioPromedioPrimas = ($ibpPrimas / ($intDiasPrima - 15)) * 30;
                        if($salarioPromedioPrimas < $salarioMinimo) {
                            $salarioPromedioPrimas = $salarioMinimo + $auxilioTransporte;
                        }
                    } else {
                        $salarioPromedioPrimas = ($ibpPrimas / $intDiasSalrioPromedio) * 30;     
                    }                    
                } else {
                    if($arContrato->getEmpleadoRel()->getAuxilioTransporte() == 1) {
                        $salarioPromedioPrimas = $douSalario + $auxilioTransporte;
                    } else {
                        $salarioPromedioPrimas = $douSalario;
                    }                         
                }                                                        
            } else {
                //Comisiones
                $ibpConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->ibpConceptos($dateFechaDesde->format('Y-m-d'), $dateFechaHastaPago->format('Y-m-d'), $arContrato->getCodigoContratoPk());                
                $otrosConceptos = 0;
                if($intDiasSalrioPromedio > 0) {
                    $otrosConceptos = ($ibpConceptos / $intDiasSalrioPromedio) * 30;
                }
                if($arContrato->getEmpleadoRel()->getAuxilioTransporte() == 1) {
                    $salarioPromedioPrimas = $douSalario + $auxilioTransporte + $otrosConceptos;
                } else {
                    $salarioPromedioPrimas = $douSalario + $otrosConceptos;
                }                                                
            }   
            $aplicaPorcentaje = true;
            if($arContrato->getEmpleadoRel()->getPagadoEntidadSalud()) {
                $salarioPromedioPrimas = $douSalario;
                $aplicaPorcentaje = false;                
            }             
                                                                                
            $porcentaje = 100;                                               
            if($arConfiguracion->getPrestacionesAplicaPorcentajeSalario()) {                            
                if($arContrato->getCodigoSalarioTipoFk() == 2 && $aplicaPorcentaje) {                                    
                    $intDiasLaborados = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestaciones($arContrato->getFechaDesde(), $dateFechaHasta);                                
                    foreach ($arParametrosPrestacionPrima as $arParametroPrestacion) {
                        if($intDiasLaborados >= $arParametroPrestacion->getDiaDesde() && $intDiasLaborados <= $arParametroPrestacion->getDiaHasta()) {
                            if($arParametroPrestacion->getOrigen() == 'SAL') {
                                if($arContrato->getEmpleadoRel()->getAuxilioTransporte() == 1) {
                                    $salarioPromedioPrimas = $douSalario + $auxilioTransporte;
                                } else {
                                    $salarioPromedioPrimas = $douSalario;
                                } 
                            } else {
                                $porcentaje = $arParametroPrestacion->getPorcentaje();
                                $salarioPromedioPrimas = ($salarioPromedioPrimas * $porcentaje)/100;                                
                            }                                            
                        }
                    }                               
                }                                                        
            }   
            $diasAusentismo = 0;
            if($arConfiguracion->getDiasAusentismoPrimas()) {
                $diasAusentismo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->diasAusentismo($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'), $arContrato->getCodigoContratoPk());                                                
            }
            $salarioPromedioPrimas = round($salarioPromedioPrimas);                                                                                                 
            $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalario());
            $arProgramacionPagoDetalle->setVrSalarioPrima($salarioPromedioPrimas);
            $arProgramacionPagoDetalle->setIndefinido($arContrato->getIndefinido());                
            $arProgramacionPagoDetalle->setFechaDesde($dateFechaDesde);
            $arProgramacionPagoDetalle->setFechaHasta($arProgramacionPago->getFechaHasta());
            $arProgramacionPagoDetalle->setFechaDesdePago($dateFechaDesde);
            $arProgramacionPagoDetalle->setDias($intDiasPrimaLiquidar);
            $arProgramacionPagoDetalle->setDiasReales($intDiasPrimaLiquidar);
            $arProgramacionPagoDetalle->setDiasAusentismo($diasAusentismo);
            $em->persist($arProgramacionPagoDetalle);                
            $em->flush();            
        }
                
        return true;
    }    
    
    private function horasExtra($arProgramacionPagoDetalle, $arConfiguracion) {
        //$arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        //$arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arrExtra = array();
        if($arProgramacionPagoDetalle->getHorasDescanso() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraDescanso(), 'horas' => $arProgramacionPagoDetalle->getHorasDescanso());
        }        
        if($arProgramacionPagoDetalle->getHorasNocturnas() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraNocturna(), 'horas' => $arProgramacionPagoDetalle->getHorasNocturnas());
        }
        if($arProgramacionPagoDetalle->getHorasFestivasDiurnas() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraFestivaDiurna(), 'horas' => $arProgramacionPagoDetalle->getHorasFestivasDiurnas());
        }
        if($arProgramacionPagoDetalle->getHorasFestivasNocturnas() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraFestivaNocturna(), 'horas' => $arProgramacionPagoDetalle->getHorasFestivasNocturnas());
        }        
        if($arProgramacionPagoDetalle->getHorasExtrasOrdinariasDiurnas() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraExtraOrdinariaDiurna(), 'horas' => $arProgramacionPagoDetalle->getHorasExtrasOrdinariasDiurnas());
        }
        if($arProgramacionPagoDetalle->getHorasExtrasOrdinariasNocturnas() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraExtraOrdinariaNocturna(), 'horas' => $arProgramacionPagoDetalle->getHorasExtrasOrdinariasNocturnas());
        }        
        if($arProgramacionPagoDetalle->getHorasExtrasFestivasDiurnas() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraExtraFestivaDiurna(), 'horas' => $arProgramacionPagoDetalle->getHorasExtrasFestivasDiurnas());
        }
        if($arProgramacionPagoDetalle->getHorasExtrasFestivasNocturnas() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraExtraFestivaNocturna(), 'horas' => $arProgramacionPagoDetalle->getHorasExtrasFestivasNocturnas());
        }        
        return $arrExtra;
    }
    
    //listado de programaciones de pago pagadas para generar archivo txt bancolombia
    public function listaDQLArchivoBanco($codigoCentroCosto, $intTipo = "", $boolMostrarGenerados, $strFechaDesde = "", $strFechaHasta = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pp FROM BrasaRecursoHumanoBundle:RhuProgramacionPago pp WHERE pp.estadoPagado = 1 ";
        
        if($codigoCentroCosto != "" && $codigoCentroCosto != 0) {
            $dql .= " AND pp.codigoCentroCostoFk =" . $codigoCentroCosto;
        }
        if($intTipo != "" && $intTipo != 0) {
            $dql .= " AND pp.codigoPagoTipoFk =" . $intTipo;
        }
        if($boolMostrarGenerados == 1 ) {
            $dql .= " AND pp.archivoExportadoBanco = 1";
        }
        if($boolMostrarGenerados == "0") {
            $dql .= " AND pp.archivoExportadoBanco = 0";
        }
        if($strFechaDesde != "" ) {
            $dql .= " AND pp.fechaHasta >='" . date_format($strFechaDesde, ('Y-m-d')). "'";
        }

        if($strFechaHasta != "") {
            $dql .= " AND pp.fechaHasta <='" . date_format($strFechaHasta, ('Y-m-d')). "'";
        }
        $dql .= " ORDER BY pp.codigoProgramacionPagoPk DESC";
        return $dql;
    }
    

}