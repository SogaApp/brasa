<?php

namespace Brasa\AfiliacionBundle\Repository;

use Brasa\AfiliacionBundle\Entity\AfiContrato;
use Doctrine\ORM\EntityRepository;

class AfiPeriodoRepository extends EntityRepository
{

    public function listaDql($codigoCliente = "", $boolEstadoCerrado = "", $strDesde = "", $strHasta = "", $boolEstadoFacturado = "")
    {
        $em = $this->getEntityManager();
        $dql = "SELECT p,c FROM BrasaAfiliacionBundle:AfiPeriodo p JOIN p.clienteRel c WHERE p.codigoPeriodoPk <> 0";
        if ($codigoCliente != "") {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;
        }
        if ($boolEstadoCerrado == 1) {
            $dql .= " AND p.estadoCerrado = 1";
        }
        if ($boolEstadoCerrado == "0") {
            $dql .= " AND p.estadoCerrado = 0";
        }
        if ($strDesde != "") {
            $dql .= " AND p.fechaDesde >='" . $strDesde . "'";
        }
        if ($strHasta != "") {
            $dql .= " AND p.fechaDesde <='" . $strHasta . "'";
        }
        if ($boolEstadoFacturado == 1) {
            $dql .= " AND p.estadoFacturado = 1";
        }
        if ($boolEstadoFacturado == "0") {
            $dql .= " AND p.estadoFacturado = 0";
        }
        $dql .= " ORDER BY p.codigoPeriodoPk DESC";
        return $dql;
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }

    public function generar($codigoPeriodo)
    {
        set_time_limit(0);
        ob_clean();
        $em = $this->getEntityManager();
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
        $administracion = $arPeriodo->getClienteRel()->getAdministracion();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1); //SALARIO MINIMO
        $salarioMinimo = $arConfiguracion->getVrSalario();
        $totalPension = 0;
        $totalSalud = 0;
        $totalCaja = 0;
        $totalRiesgos = 0;
        $totalSena = 0;
        $totalIcbf = 0;
        $totalAdministracion = 0;
        $subtotalGeneral = 0;
        $ivaGeneral = 0;
        $totalGeneral = 0;
        $arContratos = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->contratosPeriodo($arPeriodo->getFechaDesde()->format('Y/m/d'), $arPeriodo->getFechaHasta()->format('Y/m/d'), $arPeriodo->getCodigoClienteFk());
        $numeroContratos = count($arContratos);
        foreach ($arContratos as $arContrato) {
            $porcentajeIcbf = 3;
            $porcentajeSena = 2;
            $intDias = $this->diasContrato($arPeriodo, $arContrato);
            $salario = $arContrato->getVrSalario();
            $vrDia = $salario / 30;
            $salarioPeriodo = $vrDia * $intDias;
            $pension = 0;
            $salud = 0;
            $caja = 0;
            $riesgos = 0;
            $sena = 0;
            $icbf = 0;
            $empleado = $arContrato->getEmpleadoRel()->getNombreCorto();
            $intDiasCotizarRiesgos = 0;
            $intDiasVacaciones = 0; //$arPeriodoEmpleado->getDiasVacaciones();            
            $intDiasVacaciones = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasVacaciones($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arContrato->getCodigoEmpleadoFk(), 9);
            $intDiasIncacidadLaboral = 0; //$arPeriodoEmpleado->getDiasVacaciones();            
            $intDiasIncacidadLaboral = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arContrato->getCodigoEmpleadoFk(), 2);
            $intDiasIncacidadGeneral = 0; //$arPeriodoEmpleado->getDiasVacaciones();            
            $intDiasIncacidadGeneral = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arContrato->getCodigoEmpleadoFk(), 1);
            $intDiasLicenciaNoRemunerada = 0; //$arPeriodoEmpleado->getDiasVacaciones();            
            $intDiasLicenciaNoRemunerada = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arContrato->getCodigoEmpleadoFk(), 3);
            $intDiasLicenciaMaternidad = 0; //$arPeriodoEmpleado->getDiasVacaciones();            
            $intDiasLicenciaMaternidad = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arContrato->getCodigoEmpleadoFk(), 5);
            $intDiasLicenciaPaternidad = 0; //$arPeriodoEmpleado->getDiasVacaciones();            
            $intDiasLicenciaPaternidad = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arContrato->getCodigoEmpleadoFk(), 6);
            $intDiasLicenciaLuto = 0; //$arPeriodoEmpleado->getDiasVacaciones();            
            $intDiasLicenciaLuto = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arContrato->getCodigoEmpleadoFk(), 7);
            $intDiasAusentimo = 0; //$arPeriodoEmpleado->getDiasVacaciones();            
            $intDiasAusentimo = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arContrato->getCodigoEmpleadoFk(), 8);
            if ($arContrato->getGeneraPension() == 1) {
                $salarioPeriodo = $this->redondearIbc2($salarioPeriodo);
                $pension = ($salarioPeriodo * $arContrato->getPorcentajePension()) / 100;
                $pension = $this->redondearCien($pension);
            }
            if ($arContrato->getGeneraSalud() == 1) {
                $salud = ($salarioPeriodo * $arContrato->getPorcentajeSalud()) / 100;
                $salud = $this->redondearCien($salud);
            }
            if ($arContrato->getGeneraCaja() == 1) {
                $mesPeriodo = $arPeriodo->getFechaDesde()->format('m');
                if ($mesPeriodo == 02) {
                    $diaFinalPeriodo = $arPeriodo->getFechaHasta()->format('d');
                    if ($diaFinalPeriodo == 28) {
                        /* if ($intDias == 30){
                          $diasAdicionalFebrero = 0;
                          } else {
                          $diasAdicionalFebrero = 2;
                          } */
                        $diasAdicionalFebrero = 2;
                    } else {
                        $diasAdicionalFebrero = 1;
                    }
                } else {
                    $diasAdicionalFebrero = 0;
                }
                $diasNovedad = 0;
                /* if ($intDiasIncacidadLaboral != 0 || $intDiasIncacidadGeneral != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasLicenciaPaternidad != 0 || $intDiasAusentimo != 0 || $intDiasLicenciaLuto != 0 ){
                  //$diasNovedad = $diasAdicionalFebrero;
                  $diasNovedad = 0;
                  } */
                $dias = $intDias - $intDiasIncacidadLaboral - $intDiasIncacidadGeneral - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasLicenciaPaternidad - $intDiasAusentimo - $intDiasLicenciaLuto - $diasNovedad;
                if ($dias < 0) {
                    $dias = 0;
                }
                if ($dias == 0) {
                    $caja = 0;
                } else {
                    $caja = ($salarioPeriodo * $arContrato->getPorcentajeCaja()) / 100;
                    $caja = $this->redondearCien($caja);
                    $tarifa = ($arContrato->getPorcentajeCaja());
                    $floIbcBrutoCaja = ($dias * ($salario / 30)) + 0;
                    $floIbcCaja = $this->redondearIbc($dias, $floIbcBrutoCaja, $salarioMinimo);
                    $caja = $this->redondearAporte($salario + 0, $floIbcCaja, $tarifa, $dias, $salarioMinimo, "");
                    $caja = $this->redondearCien($caja);
                }
            }
            if ($arContrato->getGeneraRiesgos() == 1) {
                $mesPeriodo = $arPeriodo->getFechaDesde()->format('m');
                if ($mesPeriodo == 02) {
                    $diaFinalPeriodo = $arPeriodo->getFechaHasta()->format('d');
                    if ($diaFinalPeriodo == 28) {
                        /* if ($intDias == 30){
                          $diasAdicionalFebrero = 0;
                          } else {
                          $diasAdicionalFebrero = 2;
                          } */
                        $diasAdicionalFebrero = 2;
                    } else {
                        $diasAdicionalFebrero = 1;
                    }
                } else {
                    $diasAdicionalFebrero = 0;
                }
                $diasNovedad = 0;
                /* if ($intDiasIncacidadLaboral != 0 || $intDiasIncacidadGeneral != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasLicenciaPaternidad != 0 || $intDiasAusentimo != 0 || $intDiasLicenciaLuto != 0 ){
                  //$diasNovedad = $diasAdicionalFebrero;
                  $diasNovedad = 0;
                  } */
                $dias = $intDias - $intDiasIncacidadLaboral - $intDiasIncacidadGeneral - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasLicenciaPaternidad - $intDiasAusentimo - $intDiasLicenciaLuto - $intDiasVacaciones - $diasNovedad;
                if ($dias < 0) {
                    $dias = 0;
                }
                if ($dias == 0) {
                    $riesgos = 0;
                } else {
                    $riesgos = ($salarioPeriodo * $arContrato->getClasificacionRiesgoRel()->getPorcentaje()) / 100;
                    $riesgos = $this->redondearCien($riesgos);
                    $tarifa = ($arContrato->getClasificacionRiesgoRel()->getPorcentaje());
                    $floIbcBrutoRiesgos = ($dias * ($salario / 30)) + 0;
                    $floIbcRiesgos = $this->redondearIbc($dias, $floIbcBrutoRiesgos, $salarioMinimo);
                    $riesgos = $this->redondearAporte($salario + 0, $floIbcRiesgos, $tarifa, $dias, $salarioMinimo, "");
                    $riesgos = $this->redondearCien($riesgos);
                }
            }
            if ($arContrato->getGeneraSena() == 1) {
                //if($salarioPeriodo >= $salarioMinimo ) {
                //$icbf = ($salarioPeriodo * $porcentajeIcbf)/100;
                $sena = ($salarioPeriodo * $porcentajeSena) / 100;
                //}
            }
            if ($arContrato->getGeneraIcbf() == 1) {
                //if($salarioPeriodo >= $salarioMinimo ) {
                $icbf = ($salarioPeriodo * $porcentajeIcbf) / 100;
                //$sena = ($salarioPeriodo * $porcentajeSena)/100;
                //}
            }

            $floCotizacionFSPSolidaridad = 0;
            $floCotizacionFSPSubsistencia = 0;
            if ($salarioPeriodo >= ($salarioMinimo * 4)) {
                $floCotizacionFSPSolidaridad = round($salarioPeriodo * 0.005, -2, PHP_ROUND_HALF_DOWN);
                $floCotizacionFSPSubsistencia = round($salarioPeriodo * 0.005, -2, PHP_ROUND_HALF_DOWN);
            }
            $totalFondosSolidaridad = $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
            $subtotal = $pension + $salud + $caja + $riesgos + $sena + $icbf + $totalFondosSolidaridad;
            $iva = round($administracion * $arConfiguracion->getPorcentajeIva() / 100);
            //$iva = $this->redondearCien($iva);
            $total = $subtotal + $iva + $administracion;
            $arPeriodoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle();
            $arPeriodoDetalle->setPeriodoRel($arPeriodo);
            $arPeriodoDetalle->setContratoRel($arContrato);
            $arPeriodoDetalle->setEmpleadoRel($arContrato->getEmpleadoRel());
            $arPeriodoDetalle->setFechaDesde($arPeriodo->getFechaDesde());
            $arPeriodoDetalle->setFechaHasta($arPeriodo->getFechaHasta());
            $arPeriodoDetalle->setDias($intDias);
            $arPeriodoDetalle->setSalario($arContrato->getVrSalario());
            $arPeriodoDetalle->setIbc($salarioPeriodo);
            $arPeriodoDetalle->setPension($pension);
            $arPeriodoDetalle->setSalud($salud);
            $arPeriodoDetalle->setCaja($caja);

            $arPeriodoDetalle->setIcbf($icbf);
            $arPeriodoDetalle->setSena($sena);

            $arPeriodoDetalle->setRiesgos($riesgos);
            $arPeriodoDetalle->setAdministracion($administracion);
            $arPeriodoDetalle->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
            $arPeriodoDetalle->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSubsistencia);
            $arPeriodoDetalle->setSubtotal($subtotal);
            $arPeriodoDetalle->setIva($iva);
            $arPeriodoDetalle->setPorcentajeIva($arConfiguracion->getPorcentajeIva());
            $arPeriodoDetalle->setTotal($total);
            if ($arContrato->getFechaDesde() >= $arPeriodo->getFechaDesde()) {
                $arPeriodoDetalle->setIngreso(1);
            }
            if ($arContrato->getIndefinido() == 0) {
                $arPeriodoDetalle->setRetiro(1);
            }
            $em->persist($arPeriodoDetalle);
            $totalPension += $pension;
            $totalSalud += $salud;
            $totalCaja += $caja;
            $totalRiesgos += $riesgos;
            $totalSena += $sena;
            $totalIcbf += $icbf;
            $totalAdministracion += $administracion;
            $subtotalGeneral += $subtotal;
            $ivaGeneral += $iva;
            $totalGeneral += $total;
        }
        //if($arPeriodo->getClienteRel()->getRedondearCobro() == 1) {
        //    $totalGeneral = $this->redondearCien($totalGeneral);
        //}    
        $arPeriodo->setEstadoGenerado(1);
        $arPeriodo->setPension($totalPension);
        $arPeriodo->setSalud($totalSalud);
        $arPeriodo->setCaja($totalCaja);
        $arPeriodo->setRiesgos($totalRiesgos);
        $arPeriodo->setSena($totalSena);
        $arPeriodo->setIcbf($totalIcbf);
        $arPeriodo->setAdministracion($totalAdministracion);
        $arPeriodo->setSubtotal($subtotalGeneral);
        $arPeriodo->setIva($ivaGeneral);
        $arPeriodo->setTotal($totalGeneral);
        $arPeriodo->setNumeroEmpleados($numeroContratos);
        $em->persist($arPeriodo);
        $em->flush();

        // interes por mora
        if ($arPeriodo->getEstadoGenerado() == 1 && $arPeriodo->getEstadoCerrado() == 0 && $arPeriodo->getEstadoFacturado() == 0) {
            $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->generarInteresMora($codigoPeriodo);
        }
    }

    public function generarPago($codigoPeriodo)
    {
        set_time_limit(0);
        ob_clean();
        $em = $this->getEntityManager();
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1); //SALARIO MINIMO
        $salarioMinimo = $arConfiguracion->getVrSalario();
        $secuencia = 1;
        $arContratos = new \Brasa\AfiliacionBundle\Entity\AfiContrato();
        $arContratos = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->contratosPeriodo($arPeriodo->getFechaDesde()->format('Y/m/d'), $arPeriodo->getFechaHasta()->format('Y/m/d'), $arPeriodo->getCodigoClienteFk());
        foreach ($arContratos as $arContrato) {
            $arEmpleado = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
            $arEmpleado = $arContrato->getEmpleadoRel();
            $arPeriodoDetallePago = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago();
            $arPeriodoDetallePago->setPeriodoRel($arPeriodo);
            $arPeriodoDetallePago->setEmpleadoRel($arContrato->getEmpleadoRel());
            $arPeriodoDetallePago->setContratoRel($arContrato);
            $arPeriodoDetallePago->setTipoRegistro(2);
            $arPeriodoDetallePago->setAnio($arPeriodo->getAnioPago());
            $arPeriodoDetallePago->setMes($arPeriodo->getMes());
            $arPeriodoDetallePago->setFechaDesde($arPeriodo->getFechaDesde());
            $arPeriodoDetallePago->setFechaHasta($arPeriodo->getFechaHasta());
            $arPeriodoDetallePago->setSecuencia($secuencia);
            $arPeriodoDetallePago->setTipoDocumento($arEmpleado->getTipoIdentificacionRel()->getCodigoInterface());
            $arPeriodoDetallePago->setTipoCotizante($arContrato->getCodigoTipoCotizanteFk());
            $arPeriodoDetallePago->setSubtipoCotizante($arContrato->getCodigoSubtipoCotizanteFk());
            $arPeriodoDetallePago->setExtranjeroNoObligadoCotizarPension(" ");
            $arPeriodoDetallePago->setColombianoResidenteExterior(" ");
            $arPeriodoDetallePago->setCodigoDepartamentoUbicacionlaboral("05");
            $arPeriodoDetallePago->setCodigoMunicipioUbicacionlaboral("001");
            $arPeriodoDetallePago->setPrimerNombre($arEmpleado->getNombre1());
            $arPeriodoDetallePago->setSegundoNombre($arEmpleado->getNombre2());
            $arPeriodoDetallePago->setPrimerApellido($arEmpleado->getApellido1());
            $arPeriodoDetallePago->setSalarioBasico($arContrato->getVrSalario());
            $arPeriodoDetallePago->setSegundoApellido($arEmpleado->getApellido2());
            $arPeriodoDetallePago->setCodigoEntidadPensionPertenece($arContrato->getEntidadPensionRel()->getCodigoInterface());
            $arPeriodoDetallePago->setCodigoEntidadSaludPertenece($arContrato->getEntidadSaludRel()->getCodigoInterface());
            $arPeriodoDetallePago->setCodigoEntidadCajaPertenece($arContrato->getEntidadCajaRel()->getCodigoInterface());
            $arPeriodoDetallePago->setSucursalRel($arContrato->getSucursalRel());
            //Parametros generales
            $floSalario = $arContrato->getVrSalario();
            $floSalarioIntegral = $arPeriodoDetallePago->getSalarioIntegral();
            $floSuplementario = 0;
            //$floSuplementario = $arPeriodoEmpleado->getVrSuplementario();
            $floSuplementario = 0;
            $intDiasLicenciaNoRemunerada = 0; //$arPeriodoEmpleado->getDiasLicencia();
            $intDiasLicenciaNoRemunerada = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), 3);
            $intDiasSuspension = 0; //$arPeriodoEmpleado->getDiasLicencia();
            $intDiasSuspension = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), 8);

            $floIbcIncapacidades = 0;
            $empleado = $arEmpleado->getNombreCorto();
            $intDiasLicenciaLuto = 0; //$arPeriodoEmpleado->getDiasLicencia();
            $intDiasLicenciaLuto = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), 7);
            $intDiasIncapacidadGeneral = 0;
            $intDiasIncapacidadGeneral = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), 1);
            $intDiasIncapacidadLaboral = 0;
            $intDiasIncapacidadLaboral = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), 2);
            $intDiasIncapacidades = $intDiasIncapacidadGeneral + $intDiasIncapacidadLaboral; //$arPeriodoEmpleado->getDiasIncapacidadGeneral() + $arPeriodoEmpleado->getDiasIncapacidadLaboral();
            $intDiasLicenciaMaternidad = 0; //$arPeriodoEmpleado->getDiasLicenciaMaternidad();
            $intDiasLicenciaMaternidad = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), 5);
            $intDiasLicenciaPaternidad = 0; //$arPeriodoEmpleado->getDiasLicenciaMaternidad();
            $intDiasLicenciaPaternidad = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), 6);
            $intDiasVacaciones = 0; //$arPeriodoEmpleado->getDiasVacaciones();
            //$intDiasVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->diasVacacionesDisfrute($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), $arContrato->getCodigoContratoPk());
            $intDiasVacaciones = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasVacaciones($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), 9);
            $intDiasCotizarPension = $intDiasLicenciaNoRemunerada;
            $intDiasCotizarSalud = $intDiasLicenciaNoRemunerada;
            $intDiasCotizarRiesgos = $intDiasLicenciaNoRemunerada;
            $intDiasCotizarCaja = $intDiasLicenciaNoRemunerada;
            $fechaTerminaCotrato = $arContrato->getFechaHasta()->format('Y-m-d');
            if ($arContrato->getFechaDesde() >= $arPeriodo->getFechaDesde()) {
                $arPeriodoDetallePago->setIngreso('X');
            }
            if ($arContrato->getIndefinido() == 0 && $fechaTerminaCotrato <= $arPeriodo->getFechaHasta()) {
                $arPeriodoDetallePago->setRetiro('X');
            }
            if ($arPeriodoDetallePago->getIngreso() == 'X' && $arPeriodoDetallePago->getRetiro() == 'X') {
                $arPeriodoDetallePago->setIngreso('X');
//                $arPeriodoDetallePago->setRetiro('X');
            }
            if ($intDiasIncapacidadGeneral > 0) {
                $arPeriodoDetallePago->setIncapacidadGeneral('X');
                $arPeriodoDetallePago->setDiasIncapacidadGeneral($intDiasIncapacidadGeneral);
                $floSalarioMesActual = $floSalario;

                $floIbcIncapacidadGeneral = $this->liquidarIncapacidadGeneral($floSalarioMesActual, 0, $intDiasIncapacidadGeneral);
                $floIbcIncapacidades += $floIbcIncapacidadGeneral;
            }
            if ($intDiasLicenciaMaternidad > 0) {
                $arPeriodoDetallePago->setLicenciaMaternidad('X');
                $arPeriodoDetallePago->setDiasLicenciaMaternidad($intDiasLicenciaMaternidad);
            }
            if ($intDiasIncapacidadLaboral > 0) {
                $arPeriodoDetallePago->setIncapacidadAccidenteTrabajoEnfermedadProfesional($intDiasIncapacidadLaboral);
                $arPeriodoDetallePago->setDiasIncapacidadLaboral($intDiasIncapacidadLaboral);

                $floSalarioMesActual = $floSalario + $floSuplementario;

                $floIbcIncapacidadLaboral = $this->liquidarIncapacidadLaboral($floSalarioMesActual, 0, $intDiasIncapacidadLaboral);
                $floIbcIncapacidades += $floIbcIncapacidadLaboral;
            }

            //Dias
            $intDiasCotizar = $this->diasContrato($arPeriodo, $arContrato);
            $intDiasCotizarPension = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
            $intDiasCotizarSalud = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
            $mesPeriodo = $arPeriodo->getFechaDesde()->format('m');
            if ($mesPeriodo == 02) {
                $diaFinalPeriodo = $arPeriodo->getFechaHasta()->format('d');
                if ($diaFinalPeriodo == 28) {
                    /* if ($intDias == 30){
                      $diasAdicionalFebrero = 0;
                      } else {
                      $diasAdicionalFebrero = 2;
                      } */
                    $diasAdicionalFebrero = 2;
                } else {
                    $diasAdicionalFebrero = 1;
                }
            } else {
                $diasAdicionalFebrero = 0;
            }
            $diasNovedadRiesgos = 0;
            /* if ($intDiasIncapacidades != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasVacaciones != 0 || $intDiasSuspension != 0 || $intDiasLicenciaLuto != 0 || $intDiasLicenciaPaternidad != 0){
              $diasNovedadRiesgos = $diasAdicionalFebrero;
              $diasNovedadRiesgos = 0;
              } */
            $diasNovedadCaja = 0;
            /* if ($intDiasIncapacidades != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasSuspension != 0 || $intDiasLicenciaLuto != 0 || $intDiasLicenciaPaternidad != 0){
              $diasNovedadCaja = $diasAdicionalFebrero;
              $diasNovedadCaja = 0;
              } */
            $intDiasCotizarRiesgos = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasVacaciones - $intDiasSuspension - $intDiasLicenciaLuto - $intDiasLicenciaPaternidad - $diasNovedadRiesgos;
            $intDiasCotizarCaja = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasSuspension - $intDiasLicenciaLuto - $intDiasLicenciaPaternidad - $diasNovedadCaja;
            if ($arContrato->getCodigoTipoCotizanteFk() == '19' || $arContrato->getCodigoTipoCotizanteFk() == '12' || $arContrato->getCodigoTipoCotizanteFk() == '23') {
                $intDiasCotizarPension = 0;
                $intDiasCotizarCaja = 0;
            }
            if ($arContrato->getCodigoTipoCotizanteFk() == '12' || $arContrato->getCodigoTipoCotizanteFk() == '19') {
                $intDiasCotizarRiesgos = 0;
            }
            if ($arContrato->getCodigoEntidadPensionFk() == 10) { //sin fondo
                $intDiasCotizarPension = 0;
            }
            if ($arContrato->getCodigoEntidadCajaFk() == 44) { // sin caja
                $intDiasCotizarCaja = 0;
            }
            if ($arContrato->getGeneraCaja() == 0) { // sin caja
                $intDiasCotizarCaja = 0;
            }
            if ($arContrato->getGeneraRiesgos() == 0) { // sin caja
                $intDiasCotizarRiesgos = 0;
            }
            $arPeriodoDetallePago->setDiasCotizadosPension($intDiasCotizarPension);
            $arPeriodoDetallePago->setDiasCotizadosSalud($intDiasCotizarSalud);
            $arPeriodoDetallePago->setDiasCotizadosRiesgosProfesionales($intDiasCotizarRiesgos);
            $arPeriodoDetallePago->setDiasCotizadosCajaCompensacion($intDiasCotizarCaja);
            //Ibc
            $floIbcBrutoPension = (($intDiasCotizarPension - $intDiasIncapacidades) * ($floSalario / 30)) + $floIbcIncapacidades + $floSuplementario;
            $floIbcBrutoSalud = (($intDiasCotizarSalud - $intDiasIncapacidades) * ($floSalario / 30)) + $floIbcIncapacidades + $floSuplementario;
            $floIbcBrutoRiesgos = ($intDiasCotizarRiesgos * ($floSalario / 30)) + $floSuplementario;
            $floIbcBrutoCaja = ($intDiasCotizarCaja * ($floSalario / 30)) + $floSuplementario;

            $floIbcPension = $this->redondearIbc($intDiasCotizarPension, $floIbcBrutoPension, $salarioMinimo);
            $floIbcSalud = $this->redondearIbc($intDiasCotizarSalud, $floIbcBrutoSalud, $salarioMinimo);
            $floIbcRiesgos = $this->redondearIbc($intDiasCotizarRiesgos, $floIbcBrutoRiesgos, $salarioMinimo);
            $floIbcCaja = $this->redondearIbc($intDiasCotizarCaja, $floIbcBrutoCaja, $salarioMinimo);

            if ($intDiasCotizarRiesgos <= 0) {
                $floIbcRiesgos = 0;
            }
            if ($intDiasCotizarPension <= 0) {
                $floIbcPension = 0;
            }
            if ($intDiasCotizarCaja <= 0) {
                $floIbcCaja = 0;
            }
            $arPeriodoDetallePago->setIbcPension($floIbcPension);
            $arPeriodoDetallePago->setIbcSalud($floIbcSalud);
            $arPeriodoDetallePago->setIbcRiesgosProfesionales($floIbcRiesgos);
            $arPeriodoDetallePago->setIbcCaja($floIbcCaja);
            $floTarifaPension = $arContrato->getPorcentajePension();
            $floTarifaSalud = $arContrato->getPorcentajeSalud();
            $floTarifaRiesgos = $arContrato->getClasificacionRiesgoRel()->getPorcentaje();
            $floTarifaCaja = 4;
            $floTarifaIcbf = 0;
            $floTarifaSena = 0;

            if ($arContrato->getCodigoTipoCotizanteFk() == 19 || $arContrato->getCodigoTipoCotizanteFk() == 12) {
                $floTarifaSalud = 12.5;
            }
            if ((($floSalario + $floSuplementario) >= ($salarioMinimo))) {
                //$floTarifaSalud = 12.5;
                if ($arContrato->getGeneraIcbf() == 1) {
                    $floTarifaIcbf = 3;
                }
                if ($arContrato->getGeneraSena() == 1) {
                    $floTarifaSena = 2;
                }
                //$floTarifaIcbf = 3;
                //$floTarifaSena = 2;                
            }
            if ($floIbcRiesgos == 0) {
                $floTarifaRiesgos = 0;
            }
            if ($floIbcPension == 0) {
                $floTarifaPension = 0;
            }
            if ($floIbcCaja == 0) {
                $floTarifaCaja = 0;
            }
            $arPeriodoDetallePago->setTarifaPension($floTarifaPension);
            $arPeriodoDetallePago->setTarifaSalud($floTarifaSalud);
            $arPeriodoDetallePago->setTarifaRiesgos($floTarifaRiesgos);
            $arPeriodoDetallePago->setTarifaCaja($floTarifaCaja);
            $arPeriodoDetallePago->setTarifaIcbf($floTarifaIcbf);
            $arPeriodoDetallePago->setTarifaSena($floTarifaSena);
            $floCotizacionFSPSolidaridad = 0;
            $floCotizacionFSPSubsistencia = 0;
            $floAporteVoluntarioFondoPensionesObligatorias = 0;
            $floCotizacionVoluntariaFondoPensionesObligatorias = 0;
            $floCotizacionPension = $this->redondearAporte($floSalario, $floIbcPension, $floTarifaPension, $intDiasCotizarPension, $salarioMinimo, "");
            if ($floSalario >= ($salarioMinimo * 4)) {
                $floCotizacionFSPSolidaridad = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                $floCotizacionFSPSubsistencia = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
            }
            $floCotizacionSalud = $this->redondearAporte($floSalario + $floSuplementario, $floIbcSalud, $floTarifaSalud, $intDiasCotizarSalud, $salarioMinimo, "");
            $floCotizacionRiesgos = $this->redondearAporte($floSalario + $floSuplementario, $floIbcRiesgos, $floTarifaRiesgos, $intDiasCotizarRiesgos, $salarioMinimo, "");
            $floCotizacionCaja = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaCaja, $intDiasCotizarCaja, $salarioMinimo, "");
            $floCotizacionIcbf = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaIcbf, $intDiasCotizarCaja, $salarioMinimo, "");
            $floCotizacionSena = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaSena, $intDiasCotizarCaja, $salarioMinimo, "");
            $floTotalCotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $floCotizacionPension;
            $floTotalFondoSolidaridad = $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
            $arPeriodoDetallePago->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
            $arPeriodoDetallePago->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
            $arPeriodoDetallePago->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
            $arPeriodoDetallePago->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSolidaridad);
            $floCotizacionPension = $this->redondearCien($floCotizacionPension);
            $floCotizacionSalud = $this->redondearCien($floCotizacionSalud);
            $floCotizacionRiesgos = $this->redondearCien($floCotizacionRiesgos);
            $floCotizacionCaja = $this->redondearCien($floCotizacionCaja);
            $arPeriodoDetallePago->setCotizacionPension($floCotizacionPension);
            $arPeriodoDetallePago->setCotizacionSalud($floCotizacionSalud);
            $arPeriodoDetallePago->setCotizacionRiesgos($floCotizacionRiesgos);
            $arPeriodoDetallePago->setCotizacionCaja($floCotizacionCaja);
            $arPeriodoDetallePago->setCotizacionIcbf($floCotizacionIcbf);
            $arPeriodoDetallePago->setCotizacionSena($floCotizacionSena);
            //$arPeriodoDetallePago->setCentroTrabajoCodigoCt($arEmpleado->getContratoRel()->getCodigoCentroCostoFk());
            $floTotalCotizacion = $floTotalFondoSolidaridad + $floTotalCotizacionFondos + $floCotizacionSalud + $floCotizacionRiesgos + $floCotizacionCaja + $floCotizacionIcbf + $floCotizacionSena;
            $arPeriodoDetallePago->setTotalCotizacion($floTotalCotizacion);
            $em->persist($arPeriodoDetallePago);
            $secuencia++;
            //Para las licencias segunda linea solo licencias
            if ($intDiasLicenciaNoRemunerada > 0 || $intDiasSuspension > 0) {
                $arPeriodoDetallePago = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago();
                $arPeriodoDetallePago->setPeriodoRel($arPeriodo);
                $arPeriodoDetallePago->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arPeriodoDetallePago->setContratoRel($arContrato);
                $arPeriodoDetallePago->setTipoRegistro(2);
                $arPeriodoDetallePago->setSecuencia($secuencia);
                $arPeriodoDetallePago->setTipoDocumento($arEmpleado->getTipoIdentificacionRel()->getCodigoInterface());
                $arPeriodoDetallePago->setTipoCotizante($arContrato->getCodigoTipoCotizanteFk());
                $arPeriodoDetallePago->setSubtipoCotizante($arContrato->getCodigoSubtipoCotizanteFk());
                $arPeriodoDetallePago->setExtranjeroNoObligadoCotizarPension(" ");
                $arPeriodoDetallePago->setColombianoResidenteExterior(" ");
                $arPeriodoDetallePago->setCodigoDepartamentoUbicacionlaboral("05");
                $arPeriodoDetallePago->setCodigoMunicipioUbicacionlaboral("001");
                $arPeriodoDetallePago->setPrimerNombre($arEmpleado->getNombre1());
                $arPeriodoDetallePago->setSegundoNombre($arEmpleado->getNombre2());
                $arPeriodoDetallePago->setPrimerApellido($arEmpleado->getApellido1());
                $arPeriodoDetallePago->setSegundoApellido($arEmpleado->getApellido2());
                $arPeriodoDetallePago->setCodigoEntidadPensionPertenece($arContrato->getEntidadPensionRel()->getCodigoInterface());
                $arPeriodoDetallePago->setCodigoEntidadSaludPertenece($arContrato->getEntidadSaludRel()->getCodigoInterface());
                $arPeriodoDetallePago->setCodigoEntidadCajaPertenece($arContrato->getEntidadCajaRel()->getCodigoInterface());
                $arPeriodoDetallePago->setSalarioBasico($arContrato->getVrSalario());
                //Parametros generales
                $floSuplementario = 0;
                $floSuplementario = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->tiempoSuplementario($arPeriodo->getFechaDesde()->format('Y-m-d'), $arPeriodo->getFechaHasta()->format('Y-m-d'), $arContrato->getCodigoContratoPk());
                $floIbcIncapacidades = 0;
                $fechaTerminaCotrato = $arContrato->getFechaHasta()->format('Y-m-d');
                /* if($arContrato->getFechaDesde() >= $arPeriodo->getFechaDesde()) {
                  $arPeriodoDetallePago->setIngreso('X');
                  }
                  if($arContrato->getIndefinido() == 0 && $fechaTerminaCotrato <= $arPeriodo->getFechaHasta()) {
                  $arPeriodoDetallePago->setRetiro('X');
                  } */
                if ($intDiasSuspension > 0) {
                    $arPeriodoDetallePago->setSuspensionTemporalContratoLicenciaServicios('X');
                    $arPeriodoDetallePago->setDiasLicencia($intDiasLicenciaNoRemunerada);
                }
                if ($intDiasLicenciaNoRemunerada > 0) {
                    $intDiasCotizarPension = $intDiasLicenciaNoRemunerada;
                    $intDiasCotizarSalud = $intDiasLicenciaNoRemunerada;
                    $intDiasCotizarRiesgos = $intDiasLicenciaNoRemunerada;
                    $intDiasCotizarCaja = $intDiasLicenciaNoRemunerada;
                } else {
                    if ($intDiasSuspension > 0) {
                        $intDiasCotizarPension = $intDiasSuspension;
                        $intDiasCotizarSalud = $intDiasSuspension;
                        $intDiasCotizarRiesgos = $intDiasSuspension;
                        $intDiasCotizarCaja = $intDiasSuspension;
                    }
                }


                if ($arPeriodoDetallePago->getTipoCotizante() == '19' || $arPeriodoDetallePago->getTipoCotizante() == '12' || $arPeriodoDetallePago->getTipoCotizante() == '23') {
                    $intDiasCotizarPension = 0;
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getTipoCotizante() == '12' || $arPeriodoDetallePago->getTipoCotizante() == '19') {
                    $intDiasCotizarRiesgos = 0;
                }
                $arPeriodoDetallePago->setDiasCotizadosPension($intDiasCotizarPension);
                $arPeriodoDetallePago->setDiasCotizadosSalud($intDiasCotizarSalud);
                $arPeriodoDetallePago->setDiasCotizadosRiesgosProfesionales($intDiasCotizarRiesgos);
                $arPeriodoDetallePago->setDiasCotizadosCajaCompensacion($intDiasCotizarCaja);
                //Ibc
                $floIbcBrutoPension = (($intDiasCotizarPension - $intDiasIncapacidades) * ($floSalario / 30)) + $floIbcIncapacidades + $floSuplementario;
                $floIbcBrutoSalud = (($intDiasCotizarSalud - $intDiasIncapacidades) * ($floSalario / 30)) + $floIbcIncapacidades + $floSuplementario;
                $floIbcBrutoRiesgos = ($intDiasCotizarRiesgos * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoCaja = ($intDiasCotizarCaja * ($floSalario / 30)) + $floSuplementario;

                $floIbcPension = $this->redondearIbc($intDiasCotizarPension, $floIbcBrutoPension, $salarioMinimo);
                $floIbcSalud = $this->redondearIbc($intDiasCotizarSalud, $floIbcBrutoSalud, $salarioMinimo);
                $floIbcRiesgos = $this->redondearIbc($intDiasCotizarRiesgos, $floIbcBrutoRiesgos, $salarioMinimo);
                $floIbcCaja = $this->redondearIbc($intDiasCotizarCaja, $floIbcBrutoCaja, $salarioMinimo);

                if ($intDiasCotizarRiesgos <= 0) {
                    $floIbcRiesgos = 0;
                }

                $arPeriodoDetallePago->setIbcPension($floIbcPension);
                $arPeriodoDetallePago->setIbcSalud($floIbcSalud);
                $arPeriodoDetallePago->setIbcRiesgosProfesionales($floIbcRiesgos);
                $arPeriodoDetallePago->setIbcCaja($floIbcCaja);

                if ($intDiasSuspension <= 0) {
                    $arPeriodoDetallePago->setIbcSalud($floIbcSalud);
                    $arPeriodoDetallePago->setIbcRiesgosProfesionales($floIbcRiesgos);
                    $arPeriodoDetallePago->setIbcCaja($floIbcCaja);
                }

                if ($intDiasSuspension > 0) {
                    $floTarifaPension = 12;
                }
                $floTarifaSalud = 0;
                $floTarifaRiesgos = 0;
                $floTarifaCaja = 0;
                $arPeriodoDetallePago->setTarifaPension($floTarifaPension);
                $arPeriodoDetallePago->setTarifaSalud($floTarifaSalud);
                $arPeriodoDetallePago->setTarifaRiesgos($floTarifaRiesgos);
                $arPeriodoDetallePago->setTarifaCaja($floTarifaCaja);
                $floCotizacionFSPSolidaridad = 0;
                $floCotizacionFSPSubsistencia = 0;
                $floAporteVoluntarioFondoPensionesObligatorias = 0;
                $floCotizacionVoluntariaFondoPensionesObligatorias = 0;
                $floCotizacionPension = $this->redondearAporte($floSalario + $floSuplementario, $floIbcPension, $floTarifaPension, $intDiasCotizarPension, $salarioMinimo, "");
                if ($floSalario >= ($salarioMinimo * 4)) {
                    //$floCotizacionFSPSolidaridad = 0;
                    //$floCotizacionFSPSubsistencia = 0;
                    $floCotizacionFSPSolidaridad = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                    $floCotizacionFSPSubsistencia = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                }
                $floCotizacionSalud = $this->redondearAporte($floSalario + $floSuplementario, $floIbcSalud, $floTarifaSalud, $intDiasCotizarSalud, $salarioMinimo, "");
                $floCotizacionRiesgos = $this->redondearAporte($floSalario + $floSuplementario, $floIbcRiesgos, $floTarifaRiesgos, $intDiasCotizarRiesgos, $salarioMinimo, "");
                $floCotizacionCaja = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaCaja, $intDiasCotizarCaja, $salarioMinimo, "");
                $floTotalCotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $floCotizacionPension;
                $floTotalFondoSolidaridad = $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
                $arPeriodoDetallePago->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
                $arPeriodoDetallePago->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
                $arPeriodoDetallePago->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
                $arPeriodoDetallePago->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSolidaridad);
                //$arPeriodoDetallePago->setTotalCotizacionFondos($floTotalCotizacionFondos);
                $floCotizacionPension = $this->redondearCien($floCotizacionPension);
                $floCotizacionSalud = $this->redondearCien($floCotizacionSalud);
                $floCotizacionRiesgos = $this->redondearCien($floCotizacionRiesgos);
                $floCotizacionCaja = $this->redondearCien($floCotizacionCaja);
                $arPeriodoDetallePago->setCotizacionPension($floCotizacionPension);
                $arPeriodoDetallePago->setCotizacionSalud($floCotizacionSalud);
                $arPeriodoDetallePago->setCotizacionRiesgos($floCotizacionRiesgos);
                $arPeriodoDetallePago->setCotizacionCaja($floCotizacionCaja);
                //$arPeriodoDetallePago->setCentroTrabajoCodigoCt($arEmpleado->getContratoRel()->getCodigoCentroCostoFk());
                $totalCotizacion = $floTotalCotizacionFondos + $floTotalFondoSolidaridad + $floCotizacionSalud + $floCotizacionRiesgos + $floCotizacionCaja + $floCotizacionIcbf + $floCotizacionSena;
                //$totalCotizacionGeneral += $totalCotizacion;
                $totalCotizacion = $this->redondearCien($totalCotizacion);
                $arPeriodoDetallePago->setTotalCotizacion($totalCotizacion);
                $em->persist($arPeriodoDetallePago);
                $secuencia++;
            }
        }
        $fecha = new \DateTime('now');
        $fechaPeriodo = $arPeriodo->getFechaDesde();
        $arPeriodo->setFechaPago($fecha);
        $arPeriodo->setAnio($fechaPeriodo->format('Y'));
        $arPeriodo->setMes($fechaPeriodo->format('m'));
        $arPeriodo->setAnioPago($fecha->format('Y'));
        $arPeriodo->setMesPago($fecha->format('m'));
        $arPeriodo->setEstadoPagoGenerado(1);
        $em->persist($arPeriodo);
        $em->flush();
    }

    public function generarPagoDetalle($codigoPeriodo)
    {
        /**
         * @var AfiContrato $arContrato
         */
        set_time_limit(0);
        ob_clean();
        $em = $this->getEntityManager();
        $strSql = "DELETE FROM afi_periodo_detalle_pago_detalle";
        $em->getConnection()->executeQuery($strSql);
        $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1); //SALARIO MINIMO
        $salarioMinimo = $arConfiguracion->getVrSalario();
        $secuencia = 1;
        // nos indica si ya aplico las novedades de ingreso y retiro para periodos sin dias ordinarios
        $novedadesIngresoRetiro = FALSE;
        //$arContratos = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->contratosPeriodo($arPeriodo->getFechaDesde()->format('Y/m/d'), $arPeriodo->getFechaHasta()->format('Y/m/d'), $arPeriodo->getCodigoClienteFk());
        $arPeriodoDetallesPagos = $em->getRepository("BrasaAfiliacionBundle:AfiPeriodoDetallePago")->findBy(array('codigoPeriodoFk' => $codigoPeriodo));
        foreach ($arPeriodoDetallesPagos as $arPeriodoDetallePago) {
            //Parametros generales
            $floSalario = $arPeriodoDetallePago->getContratoRel()->getVrSalario();
            $floSuplementario = 0;
            $intDiasLicenciaNoRemunerada = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getCodigoEmpleadoPk(), 3);
            $intDiasSuspension = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getCodigoEmpleadoPk(), 8);
            $floIbcIncapacidades = 0;
            $floIbcIncapacidadesLaborales = 0;
            $intDiasLicenciaLuto = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getCodigoEmpleadoPk(), 7);
            $intDiasLicenciaRemunerada = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getCodigoEmpleadoPk(), 4);
            $intDiasIncapacidadGeneral = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getCodigoEmpleadoPk(), 1);
            $intDiasIncapacidadLaboral = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getCodigoEmpleadoPk(), 2);
            $intDiasIncapacidades = $intDiasIncapacidadGeneral + $intDiasIncapacidadLaboral; //$arPeriodoEmpleado->getDiasIncapacidadGeneral() + $arPeriodoEmpleado->getDiasIncapacidadLaboral();
            $intDiasLicenciaMaternidad = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getCodigoEmpleadoPk(), 5);
            $intDiasLicenciaPaternidad = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getCodigoEmpleadoPk(), 6);
            $intDiasVacaciones = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasVacaciones($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getCodigoEmpleadoPk(), 9);
            $intDiasCotizar = $this->diasContrato($arPeriodo, $arPeriodoDetallePago->getContratoRel());
            $intDiasLicenciaTotal = $intDiasLicenciaNoRemunerada + $intDiasLicenciaLuto + $intDiasLicenciaPaternidad + $intDiasLicenciaRemunerada;
            $intDiasLicenciaRemuneradaTotal = $intDiasLicenciaRemunerada + $intDiasLicenciaLuto;

            //Para dias ordinarios.
            $diasOrdinarios = $intDiasCotizar - $intDiasLicenciaMaternidad - $intDiasLicenciaTotal - $intDiasIncapacidades - $intDiasVacaciones;
            $horasOrdinarias = $diasOrdinarios * 8;

            ///Validacion para empezar a crear cada linea por la novedad reportada.

            //Para las licencias solo licencias
            if ($intDiasLicenciaNoRemunerada > 0 || $intDiasSuspension > 0) {
                $arPeriodoDetallePagoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle();
                $arPeriodoDetallePagoDetalle->setPeriodoRel($arPeriodo);
                $arPeriodoDetallePagoDetalle->setPeriodoDetallePagoRel($arPeriodoDetallePago);
                $arPeriodoDetallePagoDetalle->setEmpleadoRel($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel());
                $arPeriodoDetallePagoDetalle->setContratoRel($arPeriodoDetallePago->getContratoRel());
                $arPeriodoDetallePagoDetalle->setTipoRegistro(2);
                $arPeriodoDetallePagoDetalle->setSecuencia($secuencia);
                $arPeriodoDetallePagoDetalle->setTipoDocumento($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setTipoCotizante($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk());
                $arPeriodoDetallePagoDetalle->setSubtipoCotizante($arPeriodoDetallePago->getContratoRel()->getCodigoSubtipoCotizanteFk());
                $arPeriodoDetallePagoDetalle->setExtranjeroNoObligadoCotizarPension(" ");
                $arPeriodoDetallePagoDetalle->setColombianoResidenteExterior(" ");
                $arPeriodoDetallePagoDetalle->setCodigoDepartamentoUbicacionlaboral("05");
                $arPeriodoDetallePagoDetalle->setCodigoMunicipioUbicacionlaboral("001");
                $arPeriodoDetallePagoDetalle->setPrimerNombre($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getNombre1());
                $arPeriodoDetallePagoDetalle->setSegundoNombre($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getNombre2());
                $arPeriodoDetallePagoDetalle->setPrimerApellido($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getApellido1());
                $arPeriodoDetallePagoDetalle->setSegundoApellido($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getApellido2());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadPensionPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadPensionRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadSaludPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadSaludRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadCajaPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadCajaRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setSalarioBasico($arPeriodoDetallePago->getContratoRel()->getVrSalario());
                //Parametros generales
                $floSuplementario = 0;
                $floSuplementario = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->tiempoSuplementario($arPeriodo->getFechaDesde()->format('Y-m-d'), $arPeriodo->getFechaHasta()->format('Y-m-d'), $arPeriodoDetallePago->getContratoRel()->getCodigoContratoPk());
                $floIbcIncapacidades = 0;

                if ($diasOrdinarios <= 0 && $novedadesIngresoRetiro == FALSE) {
                    $fechaTerminaCotrato = $arPeriodoDetallePago->getContratoRel()->getFechaHasta()->format('Y-m-d');
                    if ($arPeriodoDetallePago->getContratoRel()->getFechaDesde() >= $arPeriodo->getFechaDesde()) {
                        $arPeriodoDetallePagoDetalle->setIngreso('X');
                        $novedadesIngresoRetiro = TRUE;
                    }
                    if ($arPeriodoDetallePago->getContratoRel()->getIndefinido() == 0 && $fechaTerminaCotrato <= $arPeriodo->getFechaHasta()) {
                        $arPeriodoDetallePagoDetalle->setRetiro('X');
                        $novedadesIngresoRetiro = TRUE;
                    }
                    if ($arPeriodoDetallePagoDetalle->getIngreso() == 'X' && $arPeriodoDetallePagoDetalle->getRetiro() == 'X') {
                        $arPeriodoDetallePagoDetalle->setIngreso('X');
                    }
                }
                if ($intDiasSuspension > 0) {
                    $arPeriodoDetallePagoDetalle->setSuspensionTemporalContratoLicenciaServicios('X');
                    $arPeriodoDetallePagoDetalle->setDiasLicencia($intDiasLicenciaNoRemunerada);
                }
                if ($intDiasLicenciaNoRemunerada > 0) {
                    $intDiasCotizarPension = $intDiasLicenciaNoRemunerada;
                    $intDiasCotizarSalud = $intDiasLicenciaNoRemunerada;
                    $intDiasCotizarRiesgos = $intDiasLicenciaNoRemunerada;
                    $intDiasCotizarCaja = $intDiasLicenciaNoRemunerada;
                } else {
                    if ($intDiasSuspension > 0) {
                        $intDiasCotizarPension = $intDiasSuspension;
                        $intDiasCotizarSalud = $intDiasSuspension;
                        $intDiasCotizarRiesgos = $intDiasSuspension;
                        $intDiasCotizarCaja = $intDiasSuspension;
                    }
                }


                if ($arPeriodoDetallePagoDetalle->getTipoCotizante() == '19' || $arPeriodoDetallePagoDetalle->getTipoCotizante() == '12' || $arPeriodoDetallePagoDetalle->getTipoCotizante() == '23') {
                    $intDiasCotizarPension = 0;
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePagoDetalle->getTipoCotizante() == '12' || $arPeriodoDetallePagoDetalle->getTipoCotizante() == '19') {
                    $intDiasCotizarRiesgos = 0;
                }
                $arPeriodoDetallePagoDetalle->setDiasCotizadosPension($intDiasCotizarPension);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosSalud($intDiasCotizarSalud);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosRiesgosProfesionales($intDiasCotizarRiesgos);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosCajaCompensacion($intDiasCotizarCaja);
                //Ibc
                $floIbcBrutoPension = (($intDiasCotizarPension - $intDiasIncapacidades) * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoSalud = (($intDiasCotizarSalud - $intDiasIncapacidades) * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoRiesgos = ($intDiasCotizarRiesgos * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoCaja = ($intDiasCotizarCaja * ($floSalario / 30)) + $floSuplementario;

                $floIbcPension = $this->redondearIbc($intDiasCotizarPension, $floIbcBrutoPension, $salarioMinimo);
                $floIbcSalud = $this->redondearIbc($intDiasCotizarSalud, $floIbcBrutoSalud, $salarioMinimo);
                $floIbcRiesgos = $this->redondearIbc($intDiasCotizarRiesgos, $floIbcBrutoRiesgos, $salarioMinimo);
                $floIbcCaja = $this->redondearIbc($intDiasCotizarCaja, $floIbcBrutoCaja, $salarioMinimo);

                if ($intDiasCotizarRiesgos <= 0) {
                    $floIbcRiesgos = 0;
                }

                $arPeriodoDetallePagoDetalle->setIbcPension($floIbcPension);
                $arPeriodoDetallePagoDetalle->setIbcSalud($floIbcSalud);
                $arPeriodoDetallePagoDetalle->setIbcRiesgosProfesionales($floIbcRiesgos);
                $arPeriodoDetallePagoDetalle->setIbcCaja($floIbcCaja);

                if ($intDiasSuspension <= 0) {
                    $arPeriodoDetallePagoDetalle->setIbcSalud($floIbcSalud);
                    $arPeriodoDetallePagoDetalle->setIbcRiesgosProfesionales($floIbcRiesgos);
                    $arPeriodoDetallePagoDetalle->setIbcCaja($floIbcCaja);
                }

                if ($intDiasSuspension > 0) {
                    $floTarifaPension = 12;
                }
                $floTarifaSalud = 0;
                $floTarifaRiesgos = 0;
                $floTarifaCaja = 0;
                $arPeriodoDetallePagoDetalle->setTarifaPension($floTarifaPension);
                $arPeriodoDetallePagoDetalle->setTarifaSalud($floTarifaSalud);
                $arPeriodoDetallePagoDetalle->setTarifaRiesgos($floTarifaRiesgos);
                $arPeriodoDetallePagoDetalle->setTarifaCaja($floTarifaCaja);
                $floCotizacionFSPSolidaridad = 0;
                $floCotizacionFSPSubsistencia = 0;
                $floAporteVoluntarioFondoPensionesObligatorias = 0;
                $floCotizacionVoluntariaFondoPensionesObligatorias = 0;
                $floCotizacionPension = $this->redondearAporte($floSalario + $floSuplementario, $floIbcPension, $floTarifaPension, $intDiasCotizarPension, $salarioMinimo, "");
                if ($floSalario >= ($salarioMinimo * 4)) {
                    //$floCotizacionFSPSolidaridad = 0;
                    //$floCotizacionFSPSubsistencia = 0;
                    $floCotizacionFSPSolidaridad = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                    $floCotizacionFSPSubsistencia = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                }
                $floCotizacionSalud = $this->redondearAporte($floSalario + $floSuplementario, $floIbcSalud, $floTarifaSalud, $intDiasCotizarSalud, $salarioMinimo, "");
                $floCotizacionRiesgos = $this->redondearAporte($floSalario + $floSuplementario, $floIbcRiesgos, $floTarifaRiesgos, $intDiasCotizarRiesgos, $salarioMinimo, "");
                $floCotizacionCaja = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaCaja, $intDiasCotizarCaja, $salarioMinimo, "");
                $floTotalCotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $floCotizacionPension;
                $floTotalFondoSolidaridad = $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
                $arPeriodoDetallePagoDetalle->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
                $arPeriodoDetallePagoDetalle->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
                $arPeriodoDetallePagoDetalle->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
                $arPeriodoDetallePagoDetalle->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSolidaridad);
                //$arPeriodoDetallePago->setTotalCotizacionFondos($floTotalCotizacionFondos);
                $floCotizacionPension = $this->redondearCien($floCotizacionPension);
                $floCotizacionSalud = $this->redondearCien($floCotizacionSalud);
                $floCotizacionRiesgos = $this->redondearCien($floCotizacionRiesgos);
                $floCotizacionCaja = $this->redondearCien($floCotizacionCaja);
                $arPeriodoDetallePagoDetalle->setCotizacionPension($floCotizacionPension);
                $arPeriodoDetallePagoDetalle->setCotizacionSalud($floCotizacionSalud);
                $arPeriodoDetallePagoDetalle->setCotizacionRiesgos($floCotizacionRiesgos);
                $arPeriodoDetallePagoDetalle->setCotizacionCaja($floCotizacionCaja);
                //$arPeriodoDetallePago->setCentroTrabajoCodigoCt($arEmpleado->getContratoRel()->getCodigoCentroCostoFk());
                $totalCotizacion = $floTotalCotizacionFondos + $floTotalFondoSolidaridad + $floCotizacionSalud + $floCotizacionRiesgos + $floCotizacionCaja;
                //$totalCotizacionGeneral += $totalCotizacion;
                $totalCotizacion = $this->redondearCien($totalCotizacion);
                $arPeriodoDetallePagoDetalle->setTotalCotizacion($totalCotizacion);
                $em->persist($arPeriodoDetallePagoDetalle);
                $secuencia++;
            }

            //Para incapacidades generales
            if ($intDiasIncapacidadGeneral > 0) {
                $horasIncapacidadGeneral = $intDiasIncapacidadGeneral * 8;
                $arPeriodoDetallePagoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle();
                $arPeriodoDetallePagoDetalle->setPeriodoDetallePagoRel($arPeriodoDetallePago);
                $arPeriodoDetallePagoDetalle->setPeriodoRel($arPeriodo);
                $arPeriodoDetallePagoDetalle->setEmpleadoRel($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel());
                $arPeriodoDetallePagoDetalle->setContratoRel($arPeriodoDetallePago->getContratoRel());
                $arPeriodoDetallePagoDetalle->setTipoRegistro(2);
                $arPeriodoDetallePagoDetalle->setAnio($arPeriodo->getAnioPago());
                $arPeriodoDetallePagoDetalle->setMes($arPeriodo->getMes());
                $arPeriodoDetallePagoDetalle->setFechaDesde($arPeriodo->getFechaDesde());
                $arPeriodoDetallePagoDetalle->setFechaHasta($arPeriodo->getFechaHasta());
                $arPeriodoDetallePagoDetalle->setSecuencia($secuencia);
                $arPeriodoDetallePagoDetalle->setTipoDocumento($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setTipoCotizante($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk());
                $arPeriodoDetallePagoDetalle->setSubtipoCotizante($arPeriodoDetallePago->getContratoRel()->getCodigoSubtipoCotizanteFk());
                $arPeriodoDetallePagoDetalle->setExtranjeroNoObligadoCotizarPension(" ");
                $arPeriodoDetallePagoDetalle->setColombianoResidenteExterior(" ");
                $arPeriodoDetallePagoDetalle->setCodigoDepartamentoUbicacionlaboral("05");
                $arPeriodoDetallePagoDetalle->setCodigoMunicipioUbicacionlaboral("001");
                $arPeriodoDetallePagoDetalle->setPrimerNombre($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getNombre1());
                $arPeriodoDetallePagoDetalle->setSegundoNombre($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getNombre2());
                $arPeriodoDetallePagoDetalle->setPrimerApellido($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getApellido1());
                $arPeriodoDetallePagoDetalle->setSalarioBasico($arPeriodoDetallePago->getContratoRel()->getVrSalario());
                $arPeriodoDetallePagoDetalle->setSegundoApellido($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getApellido2());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadPensionPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadPensionRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadSaludPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadSaludRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadCajaPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadCajaRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setSucursalRel($arPeriodoDetallePago->getContratoRel()->getSucursalRel());

                if ($diasOrdinarios <= 0 && $novedadesIngresoRetiro == FALSE) {
                    $fechaTerminaCotrato = $arPeriodoDetallePago->getContratoRel()->getFechaHasta()->format('Y-m-d');
                    if ($arPeriodoDetallePago->getContratoRel()->getFechaDesde() >= $arPeriodo->getFechaDesde()) {
                        $arPeriodoDetallePagoDetalle->setIngreso('X');
                        $novedadesIngresoRetiro = TRUE;
                    }
                    if ($arPeriodoDetallePago->getContratoRel()->getIndefinido() == 0 && $fechaTerminaCotrato <= $arPeriodo->getFechaHasta()) {
                        $arPeriodoDetallePagoDetalle->setRetiro('X');
                        $novedadesIngresoRetiro = TRUE;
                    }
                    if ($arPeriodoDetallePagoDetalle->getIngreso() == 'X' && $arPeriodoDetallePagoDetalle->getRetiro() == 'X') {
                        $arPeriodoDetallePagoDetalle->setIngreso('X');
                    }
                }

                if ($intDiasIncapacidadGeneral > 0) {
                    $arPeriodoDetallePagoDetalle->setIncapacidadGeneral('X');
                    $arPeriodoDetallePagoDetalle->setDiasIncapacidadGeneral($intDiasIncapacidadGeneral);
                    $floSalarioMesActual = $floSalario;

                    $floIbcIncapacidadGeneral = $this->liquidarIncapacidadGeneral($floSalarioMesActual, 0, $intDiasIncapacidadGeneral);
                    $floIbcIncapacidades += $floIbcIncapacidadGeneral;
                }

//                $intDiasCotizarPension = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
                $intDiasCotizarPension = $intDiasIncapacidadGeneral;
                $intDiasCotizarSalud = $intDiasIncapacidadGeneral;
//                $intDiasCotizarSalud = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
                $mesPeriodo = $arPeriodo->getFechaDesde()->format('m');
                if ($mesPeriodo == 02) {
                    $diaFinalPeriodo = $arPeriodo->getFechaHasta()->format('d');
                    if ($diaFinalPeriodo == 28) {
                        /* if ($intDias == 30){
                          $diasAdicionalFebrero = 0;
                          } else {
                          $diasAdicionalFebrero = 2;
                          } */
                        $diasAdicionalFebrero = 2;
                    } else {
                        $diasAdicionalFebrero = 1;
                    }
                } else {
                    $diasAdicionalFebrero = 0;
                }
                $diasNovedadRiesgos = 0;
                /* if ($intDiasIncapacidades != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasVacaciones != 0 || $intDiasSuspension != 0 || $intDiasLicenciaLuto != 0 || $intDiasLicenciaPaternidad != 0){
                  $diasNovedadRiesgos = $diasAdicionalFebrero;
                  $diasNovedadRiesgos = 0;
                  } */
                $diasNovedadCaja = 0;
                /* if ($intDiasIncapacidades != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasSuspension != 0 || $intDiasLicenciaLuto != 0 || $intDiasLicenciaPaternidad != 0){
                  $diasNovedadCaja = $diasAdicionalFebrero;
                  $diasNovedadCaja = 0;
                  } */
                $intDiasCotizarRiesgos = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasVacaciones - $intDiasSuspension - $intDiasLicenciaLuto - $intDiasLicenciaPaternidad - $diasNovedadRiesgos;
                $intDiasCotizarCaja = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasSuspension - $intDiasLicenciaLuto - $intDiasLicenciaPaternidad - $diasNovedadCaja;
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '19' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '12' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '23') {
                    $intDiasCotizarPension = 0;
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '12' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '19') {
                    $intDiasCotizarRiesgos = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoEntidadPensionFk() == 10) { //sin fondo
                    $intDiasCotizarPension = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoEntidadCajaFk() == 44) { // sin caja
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getGeneraCaja() == 0) { // sin caja
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getGeneraRiesgos() == 0) { // sin caja
                    $intDiasCotizarRiesgos = 0;
                }
                $arPeriodoDetallePagoDetalle->setDiasCotizadosPension($intDiasCotizarPension);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosSalud($intDiasCotizarSalud);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosRiesgosProfesionales($intDiasCotizarRiesgos);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosCajaCompensacion($intDiasCotizarCaja);
                $arPeriodoDetallePagoDetalle->setNumeroHorasLaboradas($horasIncapacidadGeneral);
                //Ibc
                $floIbcBrutoPension = (($intDiasCotizarPension - $intDiasIncapacidades) * ($floSalario / 30)) + $floIbcIncapacidades + $floSuplementario;
                $floIbcBrutoSalud = (($intDiasCotizarSalud - $intDiasIncapacidades) * ($floSalario / 30)) + $floIbcIncapacidades + $floSuplementario;
                $floIbcBrutoRiesgos = ($intDiasCotizarRiesgos * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoCaja = ($intDiasCotizarCaja * ($floSalario / 30)) + $floSuplementario;

                $floIbcPension = $this->redondearIbc($intDiasCotizarPension, $floIbcBrutoPension, $salarioMinimo);
                $floIbcSalud = $this->redondearIbc($intDiasCotizarSalud, $floIbcBrutoSalud, $salarioMinimo);
                $floIbcRiesgos = $this->redondearIbc($intDiasCotizarRiesgos, $floIbcBrutoRiesgos, $salarioMinimo);
                $floIbcCaja = $this->redondearIbc($intDiasCotizarCaja, $floIbcBrutoCaja, $salarioMinimo);

                if ($intDiasCotizarRiesgos <= 0) {
                    $floIbcRiesgos = 0;
                }
                if ($intDiasCotizarPension <= 0) {
                    $floIbcPension = 0;
                }
                if ($intDiasCotizarCaja <= 0) {
                    $floIbcCaja = 0;
                }
                $arPeriodoDetallePagoDetalle->setIbcPension($floIbcPension);
                $arPeriodoDetallePagoDetalle->setIbcSalud($floIbcSalud);
                $arPeriodoDetallePagoDetalle->setIbcRiesgosProfesionales($floIbcRiesgos);
                $arPeriodoDetallePagoDetalle->setIbcCaja($floIbcCaja);
                $floTarifaPension = $arPeriodoDetallePago->getContratoRel()->getPorcentajePension();
                $floTarifaSalud = $arPeriodoDetallePago->getContratoRel()->getPorcentajeSalud();
                $floTarifaRiesgos = $arPeriodoDetallePago->getContratoRel()->getClasificacionRiesgoRel()->getPorcentaje();
                $floTarifaCaja = 4;
                $floTarifaIcbf = 0;
                $floTarifaSena = 0;

                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == 19 || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == 12) {
                    $floTarifaSalud = 12.5;
                }
                if ((($floSalario + $floSuplementario) >= ($salarioMinimo))) {
                    //$floTarifaSalud = 12.5;
                    if ($arPeriodoDetallePago->getContratoRel()->getGeneraIcbf() == 1) {
                        $floTarifaIcbf = 3;
                    }
                    if ($arPeriodoDetallePago->getContratoRel()->getGeneraSena() == 1) {
                        $floTarifaSena = 2;
                    }
                    //$floTarifaIcbf = 3;
                    //$floTarifaSena = 2;
                }
                if ($floIbcRiesgos == 0) {
                    $floTarifaRiesgos = 0;
                }
                if ($floIbcPension == 0) {
                    $floTarifaPension = 0;
                }
                if ($floIbcCaja == 0) {
                    $floTarifaCaja = 0;
                }
                $arPeriodoDetallePagoDetalle->setTarifaPension($floTarifaPension);
                $arPeriodoDetallePagoDetalle->setTarifaSalud($floTarifaSalud);
                $arPeriodoDetallePagoDetalle->setTarifaRiesgos($floTarifaRiesgos);
                $arPeriodoDetallePagoDetalle->setTarifaCaja($floTarifaCaja);
                $arPeriodoDetallePagoDetalle->setTarifaIcbf($floTarifaIcbf);
                $arPeriodoDetallePagoDetalle->setTarifaSena($floTarifaSena);
                $floCotizacionFSPSolidaridad = 0;
                $floCotizacionFSPSubsistencia = 0;
                $floAporteVoluntarioFondoPensionesObligatorias = 0;
                $floCotizacionVoluntariaFondoPensionesObligatorias = 0;
                $floCotizacionPension = $this->redondearAporte($floSalario, $floIbcPension, $floTarifaPension, $intDiasCotizarPension, $salarioMinimo, "");
                if ($floSalario >= ($salarioMinimo * 4)) {
                    $floCotizacionFSPSolidaridad = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                    $floCotizacionFSPSubsistencia = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                }
                $floCotizacionSalud = $this->redondearAporte($floSalario + $floSuplementario, $floIbcSalud, $floTarifaSalud, $intDiasCotizarSalud, $salarioMinimo, "");
                $floCotizacionRiesgos = $this->redondearAporte($floSalario + $floSuplementario, $floIbcRiesgos, $floTarifaRiesgos, $intDiasCotizarRiesgos, $salarioMinimo, "");
                $floCotizacionCaja = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaCaja, $intDiasCotizarCaja, $salarioMinimo, "");
                $floCotizacionIcbf = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaIcbf, $intDiasCotizarCaja, $salarioMinimo, "");
                $floCotizacionSena = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaSena, $intDiasCotizarCaja, $salarioMinimo, "");
                $floTotalCotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $floCotizacionPension;
                $floTotalFondoSolidaridad = $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
                $arPeriodoDetallePagoDetalle->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
                $arPeriodoDetallePagoDetalle->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
                $arPeriodoDetallePagoDetalle->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
                $arPeriodoDetallePagoDetalle->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSolidaridad);
                $floCotizacionPension = $this->redondearCien($floCotizacionPension);
                $floCotizacionSalud = $this->redondearCien($floCotizacionSalud);
                $floCotizacionRiesgos = $this->redondearCien($floCotizacionRiesgos);
                $floCotizacionCaja = $this->redondearCien($floCotizacionCaja);
                $arPeriodoDetallePagoDetalle->setCotizacionPension($floCotizacionPension);
                $arPeriodoDetallePagoDetalle->setCotizacionSalud($floCotizacionSalud);
                $arPeriodoDetallePagoDetalle->setCotizacionRiesgos($floCotizacionRiesgos);
                $arPeriodoDetallePagoDetalle->setCotizacionCaja($floCotizacionCaja);
                $arPeriodoDetallePagoDetalle->setCotizacionIcbf($floCotizacionIcbf);
                $arPeriodoDetallePagoDetalle->setCotizacionSena($floCotizacionSena);
                //$arPeriodoDetallePago->setCentroTrabajoCodigoCt($arEmpleado->getContratoRel()->getCodigoCentroCostoFk());
                $floTotalCotizacion = $floTotalFondoSolidaridad + $floTotalCotizacionFondos + $floCotizacionSalud + $floCotizacionRiesgos + $floCotizacionCaja + $floCotizacionIcbf + $floCotizacionSena;
                $arPeriodoDetallePagoDetalle->setTotalCotizacion($floTotalCotizacion);
                $em->persist($arPeriodoDetallePagoDetalle);
                $secuencia++;
            }

            //Para incapacidades laborales
            if ($intDiasIncapacidadLaboral > 0) {
                $horasIncapacidadLaboral = $intDiasIncapacidadLaboral * 8;
                $arPeriodoDetallePagoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle();
                $arPeriodoDetallePagoDetalle->setPeriodoDetallePagoRel($arPeriodoDetallePago);
                $arPeriodoDetallePagoDetalle->setPeriodoRel($arPeriodo);
                $arPeriodoDetallePagoDetalle->setEmpleadoRel($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel());
                $arPeriodoDetallePagoDetalle->setContratoRel($arPeriodoDetallePago->getContratoRel());
                $arPeriodoDetallePagoDetalle->setTipoRegistro(2);
                $arPeriodoDetallePagoDetalle->setAnio($arPeriodo->getAnioPago());
                $arPeriodoDetallePagoDetalle->setMes($arPeriodo->getMes());
                $arPeriodoDetallePagoDetalle->setFechaDesde($arPeriodo->getFechaDesde());
                $arPeriodoDetallePagoDetalle->setFechaHasta($arPeriodo->getFechaHasta());
                $arPeriodoDetallePagoDetalle->setSecuencia($secuencia);
                $arPeriodoDetallePagoDetalle->setTipoDocumento($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setTipoCotizante($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk());
                $arPeriodoDetallePagoDetalle->setSubtipoCotizante($arPeriodoDetallePago->getContratoRel()->getCodigoSubtipoCotizanteFk());
                $arPeriodoDetallePagoDetalle->setExtranjeroNoObligadoCotizarPension(" ");
                $arPeriodoDetallePagoDetalle->setColombianoResidenteExterior(" ");
                $arPeriodoDetallePagoDetalle->setCodigoDepartamentoUbicacionlaboral("05");
                $arPeriodoDetallePagoDetalle->setCodigoMunicipioUbicacionlaboral("001");
                $arPeriodoDetallePagoDetalle->setPrimerNombre($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getNombre1());
                $arPeriodoDetallePagoDetalle->setSegundoNombre($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getNombre2());
                $arPeriodoDetallePagoDetalle->setPrimerApellido($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getApellido1());
                $arPeriodoDetallePagoDetalle->setSalarioBasico($arPeriodoDetallePago->getContratoRel()->getVrSalario());
                $arPeriodoDetallePagoDetalle->setSegundoApellido($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getApellido2());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadPensionPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadPensionRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadSaludPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadSaludRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadCajaPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadCajaRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setSucursalRel($arPeriodoDetallePago->getContratoRel()->getSucursalRel());

                //Novedad de ingreso y retiro si no se ha reportado una novedad en dias ordinarios.
                if ($diasOrdinarios <= 0 && $novedadesIngresoRetiro == FALSE) {
                    $fechaTerminaCotrato = $arPeriodoDetallePago->getContratoRel()->getFechaHasta()->format('Y-m-d');
                    if ($arPeriodoDetallePago->getContratoRel()->getFechaDesde() >= $arPeriodo->getFechaDesde()) {
                        $arPeriodoDetallePagoDetalle->setIngreso('X');
                        $novedadesIngresoRetiro = TRUE;
                    }
                    if ($arPeriodoDetallePago->getContratoRel()->getIndefinido() == 0 && $fechaTerminaCotrato <= $arPeriodo->getFechaHasta()) {
                        $arPeriodoDetallePagoDetalle->setRetiro('X');
                        $novedadesIngresoRetiro = TRUE;
                    }
                    if ($arPeriodoDetallePagoDetalle->getIngreso() == 'X' && $arPeriodoDetallePagoDetalle->getRetiro() == 'X') {
                        $arPeriodoDetallePagoDetalle->setIngreso('X');
                    }
                }

                //Incapacidad laboral
                if ($intDiasIncapacidadLaboral > 0) {
                    $arPeriodoDetallePagoDetalle->setIncapacidadAccidenteTrabajoEnfermedadProfesional($intDiasIncapacidadLaboral);
                    $arPeriodoDetallePagoDetalle->setFechaInicioIrl($arPeriodoDetallePago->getFechaDesde()->format('Y-m-d'));
                    $arPeriodoDetallePagoDetalle->setFechaFinIrl($arPeriodoDetallePago->getFechaHasta()->format('Y-m-d'));
                    $floSalarioMesActual = $floSalario;

                    $floIbcIncapacidadLaboral = $this->liquidarIncapacidadLaboral($floSalarioMesActual, 0, $intDiasIncapacidadLaboral);
                    $floIbcIncapacidadesLaborales += $floIbcIncapacidadLaboral;
                }

//                $intDiasCotizarPension = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
                $intDiasCotizarPension = $intDiasIncapacidadLaboral;
                $intDiasCotizarSalud = $intDiasIncapacidadLaboral;
//                $intDiasCotizarSalud = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
                $mesPeriodo = $arPeriodo->getFechaDesde()->format('m');
                if ($mesPeriodo == 02) {
                    $diaFinalPeriodo = $arPeriodo->getFechaHasta()->format('d');
                    if ($diaFinalPeriodo == 28) {
                        /* if ($intDias == 30){
                          $diasAdicionalFebrero = 0;
                          } else {
                          $diasAdicionalFebrero = 2;
                          } */
                        $diasAdicionalFebrero = 2;
                    } else {
                        $diasAdicionalFebrero = 1;
                    }
                } else {
                    $diasAdicionalFebrero = 0;
                }
                $diasNovedadRiesgos = 0;
                /* if ($intDiasIncapacidades != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasVacaciones != 0 || $intDiasSuspension != 0 || $intDiasLicenciaLuto != 0 || $intDiasLicenciaPaternidad != 0){
                  $diasNovedadRiesgos = $diasAdicionalFebrero;
                  $diasNovedadRiesgos = 0;
                  } */
                $diasNovedadCaja = 0;
                /* if ($intDiasIncapacidades != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasSuspension != 0 || $intDiasLicenciaLuto != 0 || $intDiasLicenciaPaternidad != 0){
                  $diasNovedadCaja = $diasAdicionalFebrero;
                  $diasNovedadCaja = 0;
                  } */
                $intDiasCotizarRiesgos = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasVacaciones - $intDiasSuspension - $intDiasLicenciaLuto - $intDiasLicenciaPaternidad - $diasNovedadRiesgos;
                $intDiasCotizarCaja = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasSuspension - $intDiasLicenciaLuto - $intDiasLicenciaPaternidad - $diasNovedadCaja;
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '19' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '12' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '23') {
                    $intDiasCotizarPension = 0;
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '12' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '19') {
                    $intDiasCotizarRiesgos = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoEntidadPensionFk() == 10) { //sin fondo
                    $intDiasCotizarPension = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoEntidadCajaFk() == 44) { // sin caja
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getGeneraCaja() == 0) { // sin caja
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getGeneraRiesgos() == 0) { // sin caja
                    $intDiasCotizarRiesgos = 0;
                }
                $arPeriodoDetallePagoDetalle->setDiasCotizadosPension($intDiasCotizarPension);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosSalud($intDiasCotizarSalud);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosRiesgosProfesionales($intDiasCotizarRiesgos);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosCajaCompensacion($intDiasCotizarCaja);
                $arPeriodoDetallePagoDetalle->setNumeroHorasLaboradas($horasIncapacidadLaboral);
                //Ibc
                $floIbcBrutoPension = (($intDiasCotizarPension - $intDiasIncapacidades) * ($floSalario / 30)) + $floIbcIncapacidadesLaborales + $floSuplementario;
                $floIbcBrutoSalud = (($intDiasCotizarSalud - $intDiasIncapacidades) * ($floSalario / 30)) + $floIbcIncapacidadesLaborales + $floSuplementario;
                $floIbcBrutoRiesgos = ($intDiasCotizarRiesgos * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoCaja = ($intDiasCotizarCaja * ($floSalario / 30)) + $floSuplementario;

                $floIbcPension = $this->redondearIbc($intDiasCotizarPension, $floIbcBrutoPension, $salarioMinimo);
                $floIbcSalud = $this->redondearIbc($intDiasCotizarSalud, $floIbcBrutoSalud, $salarioMinimo);
                $floIbcRiesgos = $this->redondearIbc($intDiasCotizarRiesgos, $floIbcBrutoRiesgos, $salarioMinimo);
                $floIbcCaja = $this->redondearIbc($intDiasCotizarCaja, $floIbcBrutoCaja, $salarioMinimo);

                if ($intDiasCotizarRiesgos <= 0) {
                    $floIbcRiesgos = 0;
                }
                if ($intDiasCotizarPension <= 0) {
                    $floIbcPension = 0;
                }
                if ($intDiasCotizarCaja <= 0) {
                    $floIbcCaja = 0;
                }
                $arPeriodoDetallePagoDetalle->setIbcPension($floIbcPension);
                $arPeriodoDetallePagoDetalle->setIbcSalud($floIbcSalud);
                $arPeriodoDetallePagoDetalle->setIbcRiesgosProfesionales($floIbcRiesgos);
                $arPeriodoDetallePagoDetalle->setIbcCaja($floIbcCaja);
                $floTarifaPension = $arPeriodoDetallePago->getContratoRel()->getPorcentajePension();
                $floTarifaSalud = $arPeriodoDetallePago->getContratoRel()->getPorcentajeSalud();
                $floTarifaRiesgos = $arPeriodoDetallePago->getContratoRel()->getClasificacionRiesgoRel()->getPorcentaje();
                $floTarifaCaja = 4;
                $floTarifaIcbf = 0;
                $floTarifaSena = 0;

                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == 19 || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == 12) {
                    $floTarifaSalud = 12.5;
                }
                if ((($floSalario + $floSuplementario) >= ($salarioMinimo))) {
                    //$floTarifaSalud = 12.5;
                    if ($arPeriodoDetallePago->getContratoRel()->getGeneraIcbf() == 1) {
                        $floTarifaIcbf = 3;
                    }
                    if ($arPeriodoDetallePago->getContratoRel()->getGeneraSena() == 1) {
                        $floTarifaSena = 2;
                    }
                    //$floTarifaIcbf = 3;
                    //$floTarifaSena = 2;
                }
                if ($floIbcRiesgos == 0) {
                    $floTarifaRiesgos = 0;
                }
                if ($floIbcPension == 0) {
                    $floTarifaPension = 0;
                }
                if ($floIbcCaja == 0) {
                    $floTarifaCaja = 0;
                }
                $arPeriodoDetallePagoDetalle->setTarifaPension($floTarifaPension);
                $arPeriodoDetallePagoDetalle->setTarifaSalud($floTarifaSalud);
                $arPeriodoDetallePagoDetalle->setTarifaRiesgos($floTarifaRiesgos);
                $arPeriodoDetallePagoDetalle->setTarifaCaja($floTarifaCaja);
                $arPeriodoDetallePagoDetalle->setTarifaIcbf($floTarifaIcbf);
                $arPeriodoDetallePagoDetalle->setTarifaSena($floTarifaSena);
                $floCotizacionFSPSolidaridad = 0;
                $floCotizacionFSPSubsistencia = 0;
                $floAporteVoluntarioFondoPensionesObligatorias = 0;
                $floCotizacionVoluntariaFondoPensionesObligatorias = 0;
                $floCotizacionPension = $this->redondearAporte($floSalario, $floIbcPension, $floTarifaPension, $intDiasCotizarPension, $salarioMinimo, "");
                if ($floSalario >= ($salarioMinimo * 4)) {
                    $floCotizacionFSPSolidaridad = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                    $floCotizacionFSPSubsistencia = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                }
                $floCotizacionSalud = $this->redondearAporte($floSalario + $floSuplementario, $floIbcSalud, $floTarifaSalud, $intDiasCotizarSalud, $salarioMinimo, "");
                $floCotizacionRiesgos = $this->redondearAporte($floSalario + $floSuplementario, $floIbcRiesgos, $floTarifaRiesgos, $intDiasCotizarRiesgos, $salarioMinimo, "");
                $floCotizacionCaja = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaCaja, $intDiasCotizarCaja, $salarioMinimo, "");
                $floCotizacionIcbf = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaIcbf, $intDiasCotizarCaja, $salarioMinimo, "");
                $floCotizacionSena = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaSena, $intDiasCotizarCaja, $salarioMinimo, "");
                $floTotalCotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $floCotizacionPension;
                $floTotalFondoSolidaridad = $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
                $arPeriodoDetallePagoDetalle->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
                $arPeriodoDetallePagoDetalle->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
                $arPeriodoDetallePagoDetalle->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
                $arPeriodoDetallePagoDetalle->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSolidaridad);
                $floCotizacionPension = $this->redondearCien($floCotizacionPension);
                $floCotizacionSalud = $this->redondearCien($floCotizacionSalud);
                $floCotizacionRiesgos = $this->redondearCien($floCotizacionRiesgos);
                $floCotizacionCaja = $this->redondearCien($floCotizacionCaja);
                $arPeriodoDetallePagoDetalle->setCotizacionPension($floCotizacionPension);
                $arPeriodoDetallePagoDetalle->setCotizacionSalud($floCotizacionSalud);
                $arPeriodoDetallePagoDetalle->setCotizacionRiesgos($floCotizacionRiesgos);
                $arPeriodoDetallePagoDetalle->setCotizacionCaja($floCotizacionCaja);
                $arPeriodoDetallePagoDetalle->setCotizacionIcbf($floCotizacionIcbf);
                $arPeriodoDetallePagoDetalle->setCotizacionSena($floCotizacionSena);
                //$arPeriodoDetallePago->setCentroTrabajoCodigoCt($arEmpleado->getContratoRel()->getCodigoCentroCostoFk());
                $floTotalCotizacion = $floTotalFondoSolidaridad + $floTotalCotizacionFondos + $floCotizacionSalud + $floCotizacionRiesgos + $floCotizacionCaja + $floCotizacionIcbf + $floCotizacionSena;
                $arPeriodoDetallePagoDetalle->setTotalCotizacion($floTotalCotizacion);
                $em->persist($arPeriodoDetallePagoDetalle);
                $secuencia++;
            }

            //Para licencias de maternidad o paternidad
            if ($intDiasLicenciaMaternidad > 0 || $intDiasLicenciaPaternidad > 0) {
                $arPeriodoDetallePagoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle();
                $arPeriodoDetallePagoDetalle->setPeriodoDetallePagoRel($arPeriodoDetallePago);
                $arPeriodoDetallePagoDetalle->setPeriodoRel($arPeriodo);
                $arPeriodoDetallePagoDetalle->setEmpleadoRel($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel());
                $arPeriodoDetallePagoDetalle->setContratoRel($arPeriodoDetallePago->getContratoRel());
                $arPeriodoDetallePagoDetalle->setTipoRegistro(2);
                $arPeriodoDetallePagoDetalle->setAnio($arPeriodo->getAnioPago());
                $arPeriodoDetallePagoDetalle->setMes($arPeriodo->getMes());
                $arPeriodoDetallePagoDetalle->setFechaDesde($arPeriodo->getFechaDesde());
                $arPeriodoDetallePagoDetalle->setFechaHasta($arPeriodo->getFechaHasta());
                $arPeriodoDetallePagoDetalle->setSecuencia($secuencia);
                $arPeriodoDetallePagoDetalle->setTipoDocumento($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setTipoCotizante($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk());
                $arPeriodoDetallePagoDetalle->setSubtipoCotizante($arPeriodoDetallePago->getContratoRel()->getCodigoSubtipoCotizanteFk());
                $arPeriodoDetallePagoDetalle->setExtranjeroNoObligadoCotizarPension(" ");
                $arPeriodoDetallePagoDetalle->setColombianoResidenteExterior(" ");
                $arPeriodoDetallePagoDetalle->setCodigoDepartamentoUbicacionlaboral("05");
                $arPeriodoDetallePagoDetalle->setCodigoMunicipioUbicacionlaboral("001");
                $arPeriodoDetallePagoDetalle->setPrimerNombre($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getNombre1());
                $arPeriodoDetallePagoDetalle->setSegundoNombre($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getNombre2());
                $arPeriodoDetallePagoDetalle->setPrimerApellido($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getApellido1());
                $arPeriodoDetallePagoDetalle->setSalarioBasico($arPeriodoDetallePago->getContratoRel()->getVrSalario());
                $arPeriodoDetallePagoDetalle->setSegundoApellido($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getApellido2());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadPensionPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadPensionRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadSaludPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadSaludRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadCajaPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadCajaRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setSucursalRel($arPeriodoDetallePago->getContratoRel()->getSucursalRel());

                //Novedad de ingreso y retiro si no se ha reportado una novedad en dias ordinarios.
                if ($diasOrdinarios <= 0 && $novedadesIngresoRetiro == FALSE) {
                    $fechaTerminaCotrato = $arPeriodoDetallePago->getContratoRel()->getFechaHasta()->format('Y-m-d');
                    if ($arPeriodoDetallePago->getContratoRel()->getFechaDesde() >= $arPeriodo->getFechaDesde()) {
                        $arPeriodoDetallePagoDetalle->setIngreso('X');
                        $novedadesIngresoRetiro = TRUE;
                    }
                    if ($arPeriodoDetallePago->getContratoRel()->getIndefinido() == 0 && $fechaTerminaCotrato <= $arPeriodo->getFechaHasta()) {
                        $arPeriodoDetallePagoDetalle->setRetiro('X');
                        $novedadesIngresoRetiro = TRUE;
                    }
                    if ($arPeriodoDetallePagoDetalle->getIngreso() == 'X' && $arPeriodoDetallePagoDetalle->getRetiro() == 'X') {
                        $arPeriodoDetallePagoDetalle->setIngreso('X');
                    }
                }

                //Licencia paternidad o maternidad
                $dias = 0;
                if ($intDiasLicenciaMaternidad > 0) {
                    $dias = $intDiasLicenciaMaternidad;
                    $horasLicencia = $intDiasLicenciaMaternidad * 8;
                } else if ($intDiasLicenciaPaternidad > 0) {
                    $dias = $intDiasLicenciaPaternidad;
                    $horasLicencia = $intDiasLicenciaPaternidad * 8;
                }
                $arPeriodoDetallePagoDetalle->setLicenciaMaternidad('X');
                $arPeriodoDetallePagoDetalle->setDiasLicenciaMaternidad($dias);

//                $intDiasCotizarPension = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
                $intDiasCotizarPension = $dias;
                $intDiasCotizarSalud = $dias;
//                $intDiasCotizarSalud = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
                $mesPeriodo = $arPeriodo->getFechaDesde()->format('m');
                if ($mesPeriodo == 02) {
                    $diaFinalPeriodo = $arPeriodo->getFechaHasta()->format('d');
                    if ($diaFinalPeriodo == 28) {
                        /* if ($intDias == 30){
                          $diasAdicionalFebrero = 0;
                          } else {
                          $diasAdicionalFebrero = 2;
                          } */
                        $diasAdicionalFebrero = 2;
                    } else {
                        $diasAdicionalFebrero = 1;
                    }
                } else {
                    $diasAdicionalFebrero = 0;
                }
                $diasNovedadRiesgos = 0;
                /* if ($intDiasIncapacidades != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasVacaciones != 0 || $intDiasSuspension != 0 || $intDiasLicenciaLuto != 0 || $intDiasLicenciaPaternidad != 0){
                  $diasNovedadRiesgos = $diasAdicionalFebrero;
                  $diasNovedadRiesgos = 0;
                  } */
                $diasNovedadCaja = 0;
                /* if ($intDiasIncapacidades != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasSuspension != 0 || $intDiasLicenciaLuto != 0 || $intDiasLicenciaPaternidad != 0){
                  $diasNovedadCaja = $diasAdicionalFebrero;
                  $diasNovedadCaja = 0;
                  } */
                $intDiasCotizarRiesgos = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasVacaciones - $intDiasSuspension - $intDiasLicenciaLuto - $intDiasLicenciaPaternidad - $diasNovedadRiesgos;
                $intDiasCotizarCaja = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasSuspension - $intDiasLicenciaLuto - $intDiasLicenciaPaternidad - $diasNovedadCaja;
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '19' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '12' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '23') {
                    $intDiasCotizarPension = 0;
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '12' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '19') {
                    $intDiasCotizarRiesgos = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoEntidadPensionFk() == 10) { //sin fondo
                    $intDiasCotizarPension = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoEntidadCajaFk() == 44) { // sin caja
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getGeneraCaja() == 0) { // sin caja
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getGeneraRiesgos() == 0) { // sin caja
                    $intDiasCotizarRiesgos = 0;
                }
                $arPeriodoDetallePagoDetalle->setDiasCotizadosPension($intDiasCotizarPension);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosSalud($intDiasCotizarSalud);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosRiesgosProfesionales($intDiasCotizarRiesgos);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosCajaCompensacion($intDiasCotizarCaja);
                $arPeriodoDetallePagoDetalle->setNumeroHorasLaboradas($horasLicencia);
                //Ibc
                $floIbcBrutoPension = (($intDiasCotizarPension - $intDiasIncapacidades) * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoSalud = (($intDiasCotizarSalud - $intDiasIncapacidades) * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoRiesgos = ($intDiasCotizarRiesgos * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoCaja = ($intDiasCotizarCaja * ($floSalario / 30)) + $floSuplementario;

                $floIbcPension = $this->redondearIbc($intDiasCotizarPension, $floIbcBrutoPension, $salarioMinimo);
                $floIbcSalud = $this->redondearIbc($intDiasCotizarSalud, $floIbcBrutoSalud, $salarioMinimo);
                $floIbcRiesgos = $this->redondearIbc($intDiasCotizarRiesgos, $floIbcBrutoRiesgos, $salarioMinimo);
                $floIbcCaja = $this->redondearIbc($intDiasCotizarCaja, $floIbcBrutoCaja, $salarioMinimo);

                if ($intDiasCotizarRiesgos <= 0) {
                    $floIbcRiesgos = 0;
                }
                if ($intDiasCotizarPension <= 0) {
                    $floIbcPension = 0;
                }
                if ($intDiasCotizarCaja <= 0) {
                    $floIbcCaja = 0;
                }
                $arPeriodoDetallePagoDetalle->setIbcPension($floIbcPension);
                $arPeriodoDetallePagoDetalle->setIbcSalud($floIbcSalud);
                $arPeriodoDetallePagoDetalle->setIbcRiesgosProfesionales($floIbcRiesgos);
                $arPeriodoDetallePagoDetalle->setIbcCaja($floIbcCaja);
                $floTarifaPension = $arPeriodoDetallePago->getContratoRel()->getPorcentajePension();
                $floTarifaSalud = $arPeriodoDetallePago->getContratoRel()->getPorcentajeSalud();
                $floTarifaRiesgos = $arPeriodoDetallePago->getContratoRel()->getClasificacionRiesgoRel()->getPorcentaje();
                $floTarifaCaja = 4;
                $floTarifaIcbf = 0;
                $floTarifaSena = 0;

                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == 19 || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == 12) {
                    $floTarifaSalud = 12.5;
                }
                if ((($floSalario + $floSuplementario) >= ($salarioMinimo))) {
                    //$floTarifaSalud = 12.5;
                    if ($arPeriodoDetallePago->getContratoRel()->getGeneraIcbf() == 1) {
                        $floTarifaIcbf = 3;
                    }
                    if ($arPeriodoDetallePago->getContratoRel()->getGeneraSena() == 1) {
                        $floTarifaSena = 2;
                    }
                    //$floTarifaIcbf = 3;
                    //$floTarifaSena = 2;
                }
                if ($floIbcRiesgos == 0) {
                    $floTarifaRiesgos = 0;
                }
                if ($floIbcPension == 0) {
                    $floTarifaPension = 0;
                }
                if ($floIbcCaja == 0) {
                    $floTarifaCaja = 0;
                }
                $arPeriodoDetallePagoDetalle->setTarifaPension($floTarifaPension);
                $arPeriodoDetallePagoDetalle->setTarifaSalud($floTarifaSalud);
                $arPeriodoDetallePagoDetalle->setTarifaRiesgos($floTarifaRiesgos);
                $arPeriodoDetallePagoDetalle->setTarifaCaja($floTarifaCaja);
                $arPeriodoDetallePagoDetalle->setTarifaIcbf($floTarifaIcbf);
                $arPeriodoDetallePagoDetalle->setTarifaSena($floTarifaSena);
                $floCotizacionFSPSolidaridad = 0;
                $floCotizacionFSPSubsistencia = 0;
                $floAporteVoluntarioFondoPensionesObligatorias = 0;
                $floCotizacionVoluntariaFondoPensionesObligatorias = 0;
                $floCotizacionPension = $this->redondearAporte($floSalario, $floIbcPension, $floTarifaPension, $intDiasCotizarPension, $salarioMinimo, "");
                if ($floSalario >= ($salarioMinimo * 4)) {
                    $floCotizacionFSPSolidaridad = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                    $floCotizacionFSPSubsistencia = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                }
                $floCotizacionSalud = $this->redondearAporte($floSalario + $floSuplementario, $floIbcSalud, $floTarifaSalud, $intDiasCotizarSalud, $salarioMinimo, "");
                $floCotizacionRiesgos = $this->redondearAporte($floSalario + $floSuplementario, $floIbcRiesgos, $floTarifaRiesgos, $intDiasCotizarRiesgos, $salarioMinimo, "");
                $floCotizacionCaja = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaCaja, $intDiasCotizarCaja, $salarioMinimo, "");
                $floCotizacionIcbf = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaIcbf, $intDiasCotizarCaja, $salarioMinimo, "");
                $floCotizacionSena = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaSena, $intDiasCotizarCaja, $salarioMinimo, "");
                $floTotalCotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $floCotizacionPension;
                $floTotalFondoSolidaridad = $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
                $arPeriodoDetallePagoDetalle->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
                $arPeriodoDetallePagoDetalle->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
                $arPeriodoDetallePagoDetalle->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
                $arPeriodoDetallePagoDetalle->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSolidaridad);
                $floCotizacionPension = $this->redondearCien($floCotizacionPension);
                $floCotizacionSalud = $this->redondearCien($floCotizacionSalud);
                $floCotizacionRiesgos = $this->redondearCien($floCotizacionRiesgos);
                $floCotizacionCaja = $this->redondearCien($floCotizacionCaja);
                $arPeriodoDetallePagoDetalle->setCotizacionPension($floCotizacionPension);
                $arPeriodoDetallePagoDetalle->setCotizacionSalud($floCotizacionSalud);
                $arPeriodoDetallePagoDetalle->setCotizacionRiesgos($floCotizacionRiesgos);
                $arPeriodoDetallePagoDetalle->setCotizacionCaja($floCotizacionCaja);
                $arPeriodoDetallePagoDetalle->setCotizacionIcbf($floCotizacionIcbf);
                $arPeriodoDetallePagoDetalle->setCotizacionSena($floCotizacionSena);
                //$arPeriodoDetallePago->setCentroTrabajoCodigoCt($arEmpleado->getContratoRel()->getCodigoCentroCostoFk());
                $floTotalCotizacion = $floTotalFondoSolidaridad + $floTotalCotizacionFondos + $floCotizacionSalud + $floCotizacionRiesgos + $floCotizacionCaja + $floCotizacionIcbf + $floCotizacionSena;
                $arPeriodoDetallePagoDetalle->setTotalCotizacion($floTotalCotizacion);
                $em->persist($arPeriodoDetallePagoDetalle);
                $secuencia++;


//                $arAporte->setFechaInicioLma($arPeriodoEmpleadoDetalle->getFechaDesde()->format('Y-m-d'));
//                $arAporte->setFechaFinLma($arPeriodoEmpleadoDetalle->getFechaHasta()->format('Y-m-d'));
            }

            //Para vacaciones
            if ($intDiasVacaciones > 0) {
                $horasVacaciones = $intDiasVacaciones * 8;
                $arPeriodoDetallePagoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle();
                $arPeriodoDetallePagoDetalle->setPeriodoDetallePagoRel($arPeriodoDetallePago);
                $arPeriodoDetallePagoDetalle->setPeriodoRel($arPeriodo);
                $arPeriodoDetallePagoDetalle->setEmpleadoRel($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel());
                $arPeriodoDetallePagoDetalle->setContratoRel($arPeriodoDetallePago->getContratoRel());
                $arPeriodoDetallePagoDetalle->setTipoRegistro(2);
                $arPeriodoDetallePagoDetalle->setAnio($arPeriodo->getAnioPago());
                $arPeriodoDetallePagoDetalle->setMes($arPeriodo->getMes());
                $arPeriodoDetallePagoDetalle->setFechaDesde($arPeriodo->getFechaDesde());
                $arPeriodoDetallePagoDetalle->setFechaHasta($arPeriodo->getFechaHasta());
                $arPeriodoDetallePagoDetalle->setSecuencia($secuencia);
                $arPeriodoDetallePagoDetalle->setTipoDocumento($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setTipoCotizante($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk());
                $arPeriodoDetallePagoDetalle->setSubtipoCotizante($arPeriodoDetallePago->getContratoRel()->getCodigoSubtipoCotizanteFk());
                $arPeriodoDetallePagoDetalle->setExtranjeroNoObligadoCotizarPension(" ");
                $arPeriodoDetallePagoDetalle->setColombianoResidenteExterior(" ");
                $arPeriodoDetallePagoDetalle->setCodigoDepartamentoUbicacionlaboral("05");
                $arPeriodoDetallePagoDetalle->setCodigoMunicipioUbicacionlaboral("001");
                $arPeriodoDetallePagoDetalle->setPrimerNombre($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getNombre1());
                $arPeriodoDetallePagoDetalle->setSegundoNombre($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getNombre2());
                $arPeriodoDetallePagoDetalle->setPrimerApellido($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getApellido1());
                $arPeriodoDetallePagoDetalle->setSalarioBasico($arPeriodoDetallePago->getContratoRel()->getVrSalario());
                $arPeriodoDetallePagoDetalle->setSegundoApellido($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getApellido2());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadPensionPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadPensionRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadSaludPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadSaludRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadCajaPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadCajaRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setSucursalRel($arPeriodoDetallePago->getContratoRel()->getSucursalRel());

                //Novedad de ingreso y retiro si no se ha reportado una novedad en dias ordinarios.
                if ($diasOrdinarios <= 0 && $novedadesIngresoRetiro == FALSE) {
                    $fechaTerminaCotrato = $arPeriodoDetallePago->getContratoRel()->getFechaHasta()->format('Y-m-d');
                    if ($arPeriodoDetallePago->getContratoRel()->getFechaDesde() >= $arPeriodo->getFechaDesde()) {
                        $arPeriodoDetallePagoDetalle->setIngreso('X');
                        $novedadesIngresoRetiro = TRUE;
                    }
                    if ($arPeriodoDetallePago->getContratoRel()->getIndefinido() == 0 && $fechaTerminaCotrato <= $arPeriodo->getFechaHasta()) {
                        $arPeriodoDetallePagoDetalle->setRetiro('X');
                        $novedadesIngresoRetiro = TRUE;
                    }
                    if ($arPeriodoDetallePagoDetalle->getIngreso() == 'X' && $arPeriodoDetallePagoDetalle->getRetiro() == 'X') {
                        $arPeriodoDetallePagoDetalle->setIngreso('X');
                    }
                }

                //Vacaciones
                if ($intDiasVacaciones > 0) {
                    $arPeriodoDetallePagoDetalle->setVacaciones('X');
                    $arPeriodoDetallePagoDetalle->setDiasVacaciones($intDiasVacaciones);
//                $arAporte->setFechaInicioVacLr($arPeriodoEmpleadoDetalle->getFechaDesde()->format('Y-m-d'));
//                $arAporte->setFechaFinVacLr($arPeriodoEmpleadoDetalle->getFechaHasta()->format('Y-m-d'));
                }

//                $intDiasCotizarPension = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
                $intDiasCotizarPension = $intDiasVacaciones;
                $intDiasCotizarSalud = $intDiasVacaciones;
//                $intDiasCotizarSalud = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
                $mesPeriodo = $arPeriodo->getFechaDesde()->format('m');
                if ($mesPeriodo == 02) {
                    $diaFinalPeriodo = $arPeriodo->getFechaHasta()->format('d');
                    if ($diaFinalPeriodo == 28) {
                        /* if ($intDias == 30){
                          $diasAdicionalFebrero = 0;
                          } else {
                          $diasAdicionalFebrero = 2;
                          } */
                        $diasAdicionalFebrero = 2;
                    } else {
                        $diasAdicionalFebrero = 1;
                    }
                } else {
                    $diasAdicionalFebrero = 0;
                }
                $diasNovedadRiesgos = 0;
                /* if ($intDiasIncapacidades != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasVacaciones != 0 || $intDiasSuspension != 0 || $intDiasLicenciaLuto != 0 || $intDiasLicenciaPaternidad != 0){
                  $diasNovedadRiesgos = $diasAdicionalFebrero;
                  $diasNovedadRiesgos = 0;
                  } */
                $diasNovedadCaja = 0;
                /* if ($intDiasIncapacidades != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasSuspension != 0 || $intDiasLicenciaLuto != 0 || $intDiasLicenciaPaternidad != 0){
                  $diasNovedadCaja = $diasAdicionalFebrero;
                  $diasNovedadCaja = 0;
                  } */
                $intDiasCotizarRiesgos = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasVacaciones - $intDiasSuspension - $intDiasLicenciaLuto - $intDiasLicenciaPaternidad - $diasNovedadRiesgos;
                $intDiasCotizarCaja = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasSuspension - $intDiasLicenciaLuto - $intDiasLicenciaPaternidad - $diasNovedadCaja;
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '19' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '12' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '23') {
                    $intDiasCotizarPension = 0;
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '12' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '19') {
                    $intDiasCotizarRiesgos = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoEntidadPensionFk() == 10) { //sin fondo
                    $intDiasCotizarPension = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoEntidadCajaFk() == 44) { // sin caja
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getGeneraCaja() == 0) { // sin caja
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getGeneraRiesgos() == 0) { // sin caja
                    $intDiasCotizarRiesgos = 0;
                }
                $arPeriodoDetallePagoDetalle->setDiasCotizadosPension($intDiasCotizarPension);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosSalud($intDiasCotizarSalud);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosRiesgosProfesionales($intDiasCotizarRiesgos);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosCajaCompensacion($intDiasCotizarCaja);
                $arPeriodoDetallePagoDetalle->setNumeroHorasLaboradas($horasVacaciones);
                //Ibc
                $floIbcBrutoPension = (($intDiasCotizarPension - $intDiasIncapacidades) * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoSalud = (($intDiasCotizarSalud - $intDiasIncapacidades) * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoRiesgos = ($intDiasCotizarRiesgos * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoCaja = ($intDiasCotizarCaja * ($floSalario / 30)) + $floSuplementario;

                $floIbcPension = $this->redondearIbc($intDiasCotizarPension, $floIbcBrutoPension, $salarioMinimo);
                $floIbcSalud = $this->redondearIbc($intDiasCotizarSalud, $floIbcBrutoSalud, $salarioMinimo);
                $floIbcRiesgos = $this->redondearIbc($intDiasCotizarRiesgos, $floIbcBrutoRiesgos, $salarioMinimo);
                $floIbcCaja = $this->redondearIbc($intDiasCotizarCaja, $floIbcBrutoCaja, $salarioMinimo);

                if ($intDiasCotizarRiesgos <= 0) {
                    $floIbcRiesgos = 0;
                }
                if ($intDiasCotizarPension <= 0) {
                    $floIbcPension = 0;
                }
                if ($intDiasCotizarCaja <= 0) {
                    $floIbcCaja = 0;
                }
                $arPeriodoDetallePagoDetalle->setIbcPension($floIbcPension);
                $arPeriodoDetallePagoDetalle->setIbcSalud($floIbcSalud);
                $arPeriodoDetallePagoDetalle->setIbcRiesgosProfesionales($floIbcRiesgos);
                $arPeriodoDetallePagoDetalle->setIbcCaja($floIbcCaja);
                $floTarifaPension = $arPeriodoDetallePago->getContratoRel()->getPorcentajePension();
                $floTarifaSalud = $arPeriodoDetallePago->getContratoRel()->getPorcentajeSalud();
                $floTarifaRiesgos = $arPeriodoDetallePago->getContratoRel()->getClasificacionRiesgoRel()->getPorcentaje();
                $floTarifaCaja = 4;
                $floTarifaIcbf = 0;
                $floTarifaSena = 0;

                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == 19 || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == 12) {
                    $floTarifaSalud = 12.5;
                }
                if ((($floSalario + $floSuplementario) >= ($salarioMinimo))) {
                    //$floTarifaSalud = 12.5;
                    if ($arPeriodoDetallePago->getContratoRel()->getGeneraIcbf() == 1) {
                        $floTarifaIcbf = 3;
                    }
                    if ($arPeriodoDetallePago->getContratoRel()->getGeneraSena() == 1) {
                        $floTarifaSena = 2;
                    }
                    //$floTarifaIcbf = 3;
                    //$floTarifaSena = 2;
                }
                if ($floIbcRiesgos == 0) {
                    $floTarifaRiesgos = 0;
                }
                if ($floIbcPension == 0) {
                    $floTarifaPension = 0;
                }
                if ($floIbcCaja == 0) {
                    $floTarifaCaja = 0;
                }
                $arPeriodoDetallePagoDetalle->setTarifaPension($floTarifaPension);
                $arPeriodoDetallePagoDetalle->setTarifaSalud($floTarifaSalud);
                $arPeriodoDetallePagoDetalle->setTarifaRiesgos($floTarifaRiesgos);
                $arPeriodoDetallePagoDetalle->setTarifaCaja($floTarifaCaja);
                $arPeriodoDetallePagoDetalle->setTarifaIcbf($floTarifaIcbf);
                $arPeriodoDetallePagoDetalle->setTarifaSena($floTarifaSena);
                $floCotizacionFSPSolidaridad = 0;
                $floCotizacionFSPSubsistencia = 0;
                $floAporteVoluntarioFondoPensionesObligatorias = 0;
                $floCotizacionVoluntariaFondoPensionesObligatorias = 0;
                $floCotizacionPension = $this->redondearAporte($floSalario, $floIbcPension, $floTarifaPension, $intDiasCotizarPension, $salarioMinimo, "");
                if ($floSalario >= ($salarioMinimo * 4)) {
                    $floCotizacionFSPSolidaridad = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                    $floCotizacionFSPSubsistencia = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                }
                $floCotizacionSalud = $this->redondearAporte($floSalario + $floSuplementario, $floIbcSalud, $floTarifaSalud, $intDiasCotizarSalud, $salarioMinimo, "");
                $floCotizacionRiesgos = $this->redondearAporte($floSalario + $floSuplementario, $floIbcRiesgos, $floTarifaRiesgos, $intDiasCotizarRiesgos, $salarioMinimo, "");
                $floCotizacionCaja = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaCaja, $intDiasCotizarCaja, $salarioMinimo, "");
                $floCotizacionIcbf = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaIcbf, $intDiasCotizarCaja, $salarioMinimo, "");
                $floCotizacionSena = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaSena, $intDiasCotizarCaja, $salarioMinimo, "");
                $floTotalCotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $floCotizacionPension;
                $floTotalFondoSolidaridad = $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
                $arPeriodoDetallePagoDetalle->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
                $arPeriodoDetallePagoDetalle->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
                $arPeriodoDetallePagoDetalle->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
                $arPeriodoDetallePagoDetalle->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSolidaridad);
                $floCotizacionPension = $this->redondearCien($floCotizacionPension);
                $floCotizacionSalud = $this->redondearCien($floCotizacionSalud);
                $floCotizacionRiesgos = $this->redondearCien($floCotizacionRiesgos);
                $floCotizacionCaja = $this->redondearCien($floCotizacionCaja);
                $arPeriodoDetallePagoDetalle->setCotizacionPension($floCotizacionPension);
                $arPeriodoDetallePagoDetalle->setCotizacionSalud($floCotizacionSalud);
                $arPeriodoDetallePagoDetalle->setCotizacionRiesgos($floCotizacionRiesgos);
                $arPeriodoDetallePagoDetalle->setCotizacionCaja($floCotizacionCaja);
                $arPeriodoDetallePagoDetalle->setCotizacionIcbf($floCotizacionIcbf);
                $arPeriodoDetallePagoDetalle->setCotizacionSena($floCotizacionSena);
                //$arPeriodoDetallePago->setCentroTrabajoCodigoCt($arEmpleado->getContratoRel()->getCodigoCentroCostoFk());
                $floTotalCotizacion = $floTotalFondoSolidaridad + $floTotalCotizacionFondos + $floCotizacionSalud + $floCotizacionRiesgos + $floCotizacionCaja + $floCotizacionIcbf + $floCotizacionSena;
                $arPeriodoDetallePagoDetalle->setTotalCotizacion($floTotalCotizacion);
                $em->persist($arPeriodoDetallePagoDetalle);
                $secuencia++;
            }

            //Para licencias remuneradas.
            if ($intDiasLicenciaRemuneradaTotal > 0) {
                $horasLicenciaRemunerada = $intDiasLicenciaRemuneradaTotal * 8;
                $arPeriodoDetallePagoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle();
                $arPeriodoDetallePagoDetalle->setPeriodoDetallePagoRel($arPeriodoDetallePago);
                $arPeriodoDetallePagoDetalle->setPeriodoRel($arPeriodo);
                $arPeriodoDetallePagoDetalle->setEmpleadoRel($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel());
                $arPeriodoDetallePagoDetalle->setContratoRel($arPeriodoDetallePago->getContratoRel());
                $arPeriodoDetallePagoDetalle->setTipoRegistro(2);
                $arPeriodoDetallePagoDetalle->setAnio($arPeriodo->getAnioPago());
                $arPeriodoDetallePagoDetalle->setMes($arPeriodo->getMes());
                $arPeriodoDetallePagoDetalle->setFechaDesde($arPeriodo->getFechaDesde());
                $arPeriodoDetallePagoDetalle->setFechaHasta($arPeriodo->getFechaHasta());
                $arPeriodoDetallePagoDetalle->setSecuencia($secuencia);
                $arPeriodoDetallePagoDetalle->setTipoDocumento($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setTipoCotizante($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk());
                $arPeriodoDetallePagoDetalle->setSubtipoCotizante($arPeriodoDetallePago->getContratoRel()->getCodigoSubtipoCotizanteFk());
                $arPeriodoDetallePagoDetalle->setExtranjeroNoObligadoCotizarPension(" ");
                $arPeriodoDetallePagoDetalle->setColombianoResidenteExterior(" ");
                $arPeriodoDetallePagoDetalle->setCodigoDepartamentoUbicacionlaboral("05");
                $arPeriodoDetallePagoDetalle->setCodigoMunicipioUbicacionlaboral("001");
                $arPeriodoDetallePagoDetalle->setPrimerNombre($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getNombre1());
                $arPeriodoDetallePagoDetalle->setSegundoNombre($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getNombre2());
                $arPeriodoDetallePagoDetalle->setPrimerApellido($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getApellido1());
                $arPeriodoDetallePagoDetalle->setSalarioBasico($arPeriodoDetallePago->getContratoRel()->getVrSalario());
                $arPeriodoDetallePagoDetalle->setSegundoApellido($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getApellido2());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadPensionPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadPensionRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadSaludPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadSaludRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadCajaPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadCajaRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setSucursalRel($arPeriodoDetallePago->getContratoRel()->getSucursalRel());

                //Novedad de ingreso y retiro si no se ha reportado una novedad en dias ordinarios.
                if ($diasOrdinarios <= 0 && $novedadesIngresoRetiro == FALSE) {
                    $fechaTerminaCotrato = $arPeriodoDetallePago->getContratoRel()->getFechaHasta()->format('Y-m-d');
                    if ($arPeriodoDetallePago->getContratoRel()->getFechaDesde() >= $arPeriodo->getFechaDesde()) {
                        $arPeriodoDetallePagoDetalle->setIngreso('X');
                        $novedadesIngresoRetiro = TRUE;
                    }
                    if ($arPeriodoDetallePago->getContratoRel()->getIndefinido() == 0 && $fechaTerminaCotrato <= $arPeriodo->getFechaHasta()) {
                        $arPeriodoDetallePagoDetalle->setRetiro('X');
                        $novedadesIngresoRetiro = TRUE;
                    }
                    if ($arPeriodoDetallePagoDetalle->getIngreso() == 'X' && $arPeriodoDetallePagoDetalle->getRetiro() == 'X') {
                        $arPeriodoDetallePagoDetalle->setIngreso('X');
                    }
                }

                //Licencia Remunerada
                if ($intDiasLicenciaLuto > 0) {
                    $arPeriodoDetallePagoDetalle->setVacaciones('L');
//                $arPeriodoDetallePagoDetalle->setFechaInicioVacLr($arPeriodoEmpleadoDetalle->getFechaDesde()->format('Y-m-d'));
//                $arPeriodoDetallePagoDetalle->setFechaFinVacLr($arPeriodoEmpleadoDetalle->getFechaHasta()->format('Y-m-d'));
                    $arPeriodoDetallePagoDetalle->setSuspensionTemporalContratoLicenciaServicios('');
                    $arPeriodoDetallePagoDetalle->setFechaInicioSln(null);
                    $arPeriodoDetallePagoDetalle->setFechaFinSln(null);
                }

//                $intDiasCotizarPension = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
                $intDiasCotizarPension = $intDiasLicenciaRemuneradaTotal;
                $intDiasCotizarSalud = $intDiasLicenciaRemuneradaTotal;
//                $intDiasCotizarSalud = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
                $mesPeriodo = $arPeriodo->getFechaDesde()->format('m');
                if ($mesPeriodo == 02) {
                    $diaFinalPeriodo = $arPeriodo->getFechaHasta()->format('d');
                    if ($diaFinalPeriodo == 28) {
                        /* if ($intDias == 30){
                          $diasAdicionalFebrero = 0;
                          } else {
                          $diasAdicionalFebrero = 2;
                          } */
                        $diasAdicionalFebrero = 2;
                    } else {
                        $diasAdicionalFebrero = 1;
                    }
                } else {
                    $diasAdicionalFebrero = 0;
                }
                $diasNovedadRiesgos = 0;
                /* if ($intDiasIncapacidades != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasVacaciones != 0 || $intDiasSuspension != 0 || $intDiasLicenciaLuto != 0 || $intDiasLicenciaPaternidad != 0){
                  $diasNovedadRiesgos = $diasAdicionalFebrero;
                  $diasNovedadRiesgos = 0;
                  } */
                $diasNovedadCaja = 0;
                /* if ($intDiasIncapacidades != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasSuspension != 0 || $intDiasLicenciaLuto != 0 || $intDiasLicenciaPaternidad != 0){
                  $diasNovedadCaja = $diasAdicionalFebrero;
                  $diasNovedadCaja = 0;
                  } */
                $intDiasCotizarRiesgos = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasVacaciones - $intDiasSuspension - $intDiasLicenciaRemuneradaTotal - $intDiasLicenciaPaternidad - $diasNovedadRiesgos;
                $intDiasCotizarCaja = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasSuspension - $intDiasLicenciaRemuneradaTotal - $intDiasLicenciaPaternidad - $diasNovedadCaja;
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '19' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '12' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '23') {
                    $intDiasCotizarPension = 0;
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '12' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '19') {
                    $intDiasCotizarRiesgos = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoEntidadPensionFk() == 10) { //sin fondo
                    $intDiasCotizarPension = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoEntidadCajaFk() == 44) { // sin caja
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getGeneraCaja() == 0) { // sin caja
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getGeneraRiesgos() == 0) { // sin caja
                    $intDiasCotizarRiesgos = 0;
                }
                $arPeriodoDetallePagoDetalle->setDiasCotizadosPension($intDiasCotizarPension);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosSalud($intDiasCotizarSalud);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosRiesgosProfesionales($intDiasCotizarRiesgos);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosCajaCompensacion($intDiasCotizarCaja);
                $arPeriodoDetallePagoDetalle->setNumeroHorasLaboradas($horasLicenciaRemunerada);
                //Ibc
                $floIbcBrutoPension = (($intDiasCotizarPension - $intDiasIncapacidades) * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoSalud = (($intDiasCotizarSalud - $intDiasIncapacidades) * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoRiesgos = ($intDiasCotizarRiesgos * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoCaja = ($intDiasCotizarCaja * ($floSalario / 30)) + $floSuplementario;

                $floIbcPension = $this->redondearIbc($intDiasCotizarPension, $floIbcBrutoPension, $salarioMinimo);
                $floIbcSalud = $this->redondearIbc($intDiasCotizarSalud, $floIbcBrutoSalud, $salarioMinimo);
                $floIbcRiesgos = $this->redondearIbc($intDiasCotizarRiesgos, $floIbcBrutoRiesgos, $salarioMinimo);
                $floIbcCaja = $this->redondearIbc($intDiasCotizarCaja, $floIbcBrutoCaja, $salarioMinimo);

                if ($intDiasCotizarRiesgos <= 0) {
                    $floIbcRiesgos = 0;
                }
                if ($intDiasCotizarPension <= 0) {
                    $floIbcPension = 0;
                }
                if ($intDiasCotizarCaja <= 0) {
                    $floIbcCaja = 0;
                }
                $arPeriodoDetallePagoDetalle->setIbcPension($floIbcPension);
                $arPeriodoDetallePagoDetalle->setIbcSalud($floIbcSalud);
                $arPeriodoDetallePagoDetalle->setIbcRiesgosProfesionales($floIbcRiesgos);
                $arPeriodoDetallePagoDetalle->setIbcCaja($floIbcCaja);
                $floTarifaPension = $arPeriodoDetallePago->getContratoRel()->getPorcentajePension();
                $floTarifaSalud = $arPeriodoDetallePago->getContratoRel()->getPorcentajeSalud();
                $floTarifaRiesgos = $arPeriodoDetallePago->getContratoRel()->getClasificacionRiesgoRel()->getPorcentaje();
                $floTarifaCaja = 4;
                $floTarifaIcbf = 0;
                $floTarifaSena = 0;

                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == 19 || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == 12) {
                    $floTarifaSalud = 12.5;
                }
                if ((($floSalario + $floSuplementario) >= ($salarioMinimo))) {
                    //$floTarifaSalud = 12.5;
                    if ($arPeriodoDetallePago->getContratoRel()->getGeneraIcbf() == 1) {
                        $floTarifaIcbf = 3;
                    }
                    if ($arPeriodoDetallePago->getContratoRel()->getGeneraSena() == 1) {
                        $floTarifaSena = 2;
                    }
                    //$floTarifaIcbf = 3;
                    //$floTarifaSena = 2;
                }
                if ($floIbcRiesgos == 0) {
                    $floTarifaRiesgos = 0;
                }
                if ($floIbcPension == 0) {
                    $floTarifaPension = 0;
                }
                if ($floIbcCaja == 0) {
                    $floTarifaCaja = 0;
                }
                $arPeriodoDetallePagoDetalle->setTarifaPension($floTarifaPension);
                $arPeriodoDetallePagoDetalle->setTarifaSalud($floTarifaSalud);
                $arPeriodoDetallePagoDetalle->setTarifaRiesgos($floTarifaRiesgos);
                $arPeriodoDetallePagoDetalle->setTarifaCaja($floTarifaCaja);
                $arPeriodoDetallePagoDetalle->setTarifaIcbf($floTarifaIcbf);
                $arPeriodoDetallePagoDetalle->setTarifaSena($floTarifaSena);
                $floCotizacionFSPSolidaridad = 0;
                $floCotizacionFSPSubsistencia = 0;
                $floAporteVoluntarioFondoPensionesObligatorias = 0;
                $floCotizacionVoluntariaFondoPensionesObligatorias = 0;
                $floCotizacionPension = $this->redondearAporte($floSalario, $floIbcPension, $floTarifaPension, $intDiasCotizarPension, $salarioMinimo, "");
                if ($floSalario >= ($salarioMinimo * 4)) {
                    $floCotizacionFSPSolidaridad = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                    $floCotizacionFSPSubsistencia = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                }
                $floCotizacionSalud = $this->redondearAporte($floSalario + $floSuplementario, $floIbcSalud, $floTarifaSalud, $intDiasCotizarSalud, $salarioMinimo, "");
                $floCotizacionRiesgos = $this->redondearAporte($floSalario + $floSuplementario, $floIbcRiesgos, $floTarifaRiesgos, $intDiasCotizarRiesgos, $salarioMinimo, "");
                $floCotizacionCaja = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaCaja, $intDiasCotizarCaja, $salarioMinimo, "");
                $floCotizacionIcbf = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaIcbf, $intDiasCotizarCaja, $salarioMinimo, "");
                $floCotizacionSena = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaSena, $intDiasCotizarCaja, $salarioMinimo, "");
                $floTotalCotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $floCotizacionPension;
                $floTotalFondoSolidaridad = $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
                $arPeriodoDetallePagoDetalle->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
                $arPeriodoDetallePagoDetalle->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
                $arPeriodoDetallePagoDetalle->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
                $arPeriodoDetallePagoDetalle->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSolidaridad);
                $floCotizacionPension = $this->redondearCien($floCotizacionPension);
                $floCotizacionSalud = $this->redondearCien($floCotizacionSalud);
                $floCotizacionRiesgos = $this->redondearCien($floCotizacionRiesgos);
                $floCotizacionCaja = $this->redondearCien($floCotizacionCaja);
                $arPeriodoDetallePagoDetalle->setCotizacionPension($floCotizacionPension);
                $arPeriodoDetallePagoDetalle->setCotizacionSalud($floCotizacionSalud);
                $arPeriodoDetallePagoDetalle->setCotizacionRiesgos($floCotizacionRiesgos);
                $arPeriodoDetallePagoDetalle->setCotizacionCaja($floCotizacionCaja);
                $arPeriodoDetallePagoDetalle->setCotizacionIcbf($floCotizacionIcbf);
                $arPeriodoDetallePagoDetalle->setCotizacionSena($floCotizacionSena);
                //$arPeriodoDetallePago->setCentroTrabajoCodigoCt($arEmpleado->getContratoRel()->getCodigoCentroCostoFk());
                $floTotalCotizacion = $floTotalFondoSolidaridad + $floTotalCotizacionFondos + $floCotizacionSalud + $floCotizacionRiesgos + $floCotizacionCaja + $floCotizacionIcbf + $floCotizacionSena;
                $arPeriodoDetallePagoDetalle->setTotalCotizacion($floTotalCotizacion);
                $em->persist($arPeriodoDetallePagoDetalle);
                $secuencia++;
            }

            //Para dias ordinarios.
            $diasOrdinarios = $intDiasCotizar - $intDiasLicenciaMaternidad - $intDiasLicenciaTotal - $intDiasIncapacidades - $intDiasVacaciones;
            $horasOrdinarias = $diasOrdinarios * 8;
            if ($diasOrdinarios > 0) {
                $arPeriodoDetallePagoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle();
                $arPeriodoDetallePagoDetalle->setPeriodoDetallePagoRel($arPeriodoDetallePago);
                $arPeriodoDetallePagoDetalle->setPeriodoRel($arPeriodo);
                $arPeriodoDetallePagoDetalle->setEmpleadoRel($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel());
                $arPeriodoDetallePagoDetalle->setContratoRel($arPeriodoDetallePago->getContratoRel());
                $arPeriodoDetallePagoDetalle->setTipoRegistro(2);
                $arPeriodoDetallePagoDetalle->setAnio($arPeriodo->getAnioPago());
                $arPeriodoDetallePagoDetalle->setMes($arPeriodo->getMes());
                $arPeriodoDetallePagoDetalle->setFechaDesde($arPeriodo->getFechaDesde());
                $arPeriodoDetallePagoDetalle->setFechaHasta($arPeriodo->getFechaHasta());
                $arPeriodoDetallePagoDetalle->setSecuencia($secuencia);
                $arPeriodoDetallePagoDetalle->setTipoDocumento($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setTipoCotizante($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk());
                $arPeriodoDetallePagoDetalle->setSubtipoCotizante($arPeriodoDetallePago->getContratoRel()->getCodigoSubtipoCotizanteFk());
                $arPeriodoDetallePagoDetalle->setExtranjeroNoObligadoCotizarPension(" ");
                $arPeriodoDetallePagoDetalle->setColombianoResidenteExterior(" ");
                $arPeriodoDetallePagoDetalle->setCodigoDepartamentoUbicacionlaboral("05");
                $arPeriodoDetallePagoDetalle->setCodigoMunicipioUbicacionlaboral("001");
                $arPeriodoDetallePagoDetalle->setPrimerNombre($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getNombre1());
                $arPeriodoDetallePagoDetalle->setSegundoNombre($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getNombre2());
                $arPeriodoDetallePagoDetalle->setPrimerApellido($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getApellido1());
                $arPeriodoDetallePagoDetalle->setSalarioBasico($arPeriodoDetallePago->getContratoRel()->getVrSalario());
                $arPeriodoDetallePagoDetalle->setSegundoApellido($arPeriodoDetallePago->getContratoRel()->getEmpleadoRel()->getApellido2());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadPensionPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadPensionRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadSaludPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadSaludRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setCodigoEntidadCajaPertenece($arPeriodoDetallePago->getContratoRel()->getEntidadCajaRel()->getCodigoInterface());
                $arPeriodoDetallePagoDetalle->setSucursalRel($arPeriodoDetallePago->getContratoRel()->getSucursalRel());

                $fechaTerminaCotrato = $arPeriodoDetallePago->getContratoRel()->getFechaHasta()->format('Y-m-d');
                if ($arPeriodoDetallePago->getContratoRel()->getFechaDesde() >= $arPeriodo->getFechaDesde()) {
                    $arPeriodoDetallePagoDetalle->setIngreso('X');
                    $novedadesIngresoRetiro = TRUE;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getIndefinido() == 0 && $fechaTerminaCotrato <= $arPeriodo->getFechaHasta()) {
                    $arPeriodoDetallePagoDetalle->setRetiro('X');
                    $novedadesIngresoRetiro = TRUE;
                }
                if ($arPeriodoDetallePagoDetalle->getIngreso() == 'X' && $arPeriodoDetallePagoDetalle->getRetiro() == 'X') {
                    $arPeriodoDetallePagoDetalle->setIngreso('X');
                }

//                $intDiasCotizarPension = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
                $intDiasCotizarPension = $diasOrdinarios;
                $intDiasCotizarSalud = $diasOrdinarios;
//                $intDiasCotizarSalud = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
                $mesPeriodo = $arPeriodo->getFechaDesde()->format('m');
                if ($mesPeriodo == 02) {
                    $diaFinalPeriodo = $arPeriodo->getFechaHasta()->format('d');
                    if ($diaFinalPeriodo == 28) {
                        /* if ($intDias == 30){
                          $diasAdicionalFebrero = 0;
                          } else {
                          $diasAdicionalFebrero = 2;
                          } */
                        $diasAdicionalFebrero = 2;
                    } else {
                        $diasAdicionalFebrero = 1;
                    }
                } else {
                    $diasAdicionalFebrero = 0;
                }
                $diasNovedadRiesgos = 0;
                /* if ($intDiasIncapacidades != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasVacaciones != 0 || $intDiasSuspension != 0 || $intDiasLicenciaLuto != 0 || $intDiasLicenciaPaternidad != 0){
                  $diasNovedadRiesgos = $diasAdicionalFebrero;
                  $diasNovedadRiesgos = 0;
                  } */
                $diasNovedadCaja = 0;
                /* if ($intDiasIncapacidades != 0 || $intDiasLicenciaNoRemunerada != 0 || $intDiasLicenciaMaternidad != 0 || $intDiasSuspension != 0 || $intDiasLicenciaLuto != 0 || $intDiasLicenciaPaternidad != 0){
                  $diasNovedadCaja = $diasAdicionalFebrero;
                  $diasNovedadCaja = 0;
                  } */
                $intDiasCotizarRiesgos = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasVacaciones - $intDiasSuspension - $intDiasLicenciaLuto - $intDiasLicenciaPaternidad - $diasNovedadRiesgos;
                $intDiasCotizarCaja = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasSuspension - $intDiasLicenciaLuto - $intDiasLicenciaPaternidad - $diasNovedadCaja;
//                $intDiasCotizarRiesgos = $diasOrdinarios;
//                $intDiasCotizarCaja = $diasOrdinarios;
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '19' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '12' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '23') {
                    $intDiasCotizarPension = 0;
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '12' || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == '19') {
                    $intDiasCotizarRiesgos = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoEntidadPensionFk() == 10) { //sin fondo
                    $intDiasCotizarPension = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getCodigoEntidadCajaFk() == 44) { // sin caja
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getGeneraCaja() == 0) { // sin caja
                    $intDiasCotizarCaja = 0;
                }
                if ($arPeriodoDetallePago->getContratoRel()->getGeneraRiesgos() == 0) { // sin caja
                    $intDiasCotizarRiesgos = 0;
                }
                $arPeriodoDetallePagoDetalle->setDiasCotizadosPension($intDiasCotizarPension);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosSalud($intDiasCotizarSalud);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosRiesgosProfesionales($intDiasCotizarRiesgos);
                $arPeriodoDetallePagoDetalle->setDiasCotizadosCajaCompensacion($intDiasCotizarCaja);
                $arPeriodoDetallePagoDetalle->setNumeroHorasLaboradas($horasOrdinarias);
                //Ibc
                $floIbcBrutoPension = (($intDiasCotizarPension - $intDiasIncapacidades) * ($floSalario / 30)) + $floIbcIncapacidades + $floSuplementario;
                $floIbcBrutoSalud = (($intDiasCotizarSalud - $intDiasIncapacidades) * ($floSalario / 30)) + $floIbcIncapacidades + $floSuplementario;
                $floIbcBrutoRiesgos = ($intDiasCotizarRiesgos * ($floSalario / 30)) + $floSuplementario;
                $floIbcBrutoCaja = ($intDiasCotizarCaja * ($floSalario / 30)) + $floSuplementario;

                $floIbcPension = $this->redondearIbc($intDiasCotizarPension, $floIbcBrutoPension, $salarioMinimo);
                $floIbcSalud = $this->redondearIbc($intDiasCotizarSalud, $floIbcBrutoSalud, $salarioMinimo);
                $floIbcRiesgos = $this->redondearIbc($intDiasCotizarRiesgos, $floIbcBrutoRiesgos, $salarioMinimo);
                $floIbcCaja = $this->redondearIbc($intDiasCotizarCaja, $floIbcBrutoCaja, $salarioMinimo);

                if ($intDiasCotizarRiesgos <= 0) {
                    $floIbcRiesgos = 0;
                }
                if ($intDiasCotizarPension <= 0) {
                    $floIbcPension = 0;
                }
                if ($intDiasCotizarCaja <= 0) {
                    $floIbcCaja = 0;
                }
                $arPeriodoDetallePagoDetalle->setIbcPension($floIbcPension);
                $arPeriodoDetallePagoDetalle->setIbcSalud($floIbcSalud);
                $arPeriodoDetallePagoDetalle->setIbcRiesgosProfesionales($floIbcRiesgos);
                $arPeriodoDetallePagoDetalle->setIbcCaja($floIbcCaja);
                $floTarifaPension = $arPeriodoDetallePago->getContratoRel()->getPorcentajePension();
                $floTarifaSalud = $arPeriodoDetallePago->getContratoRel()->getPorcentajeSalud();
                $floTarifaRiesgos = $arPeriodoDetallePago->getContratoRel()->getClasificacionRiesgoRel()->getPorcentaje();
                $floTarifaCaja = 4;
                $floTarifaIcbf = 0;
                $floTarifaSena = 0;

                if ($arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == 19 || $arPeriodoDetallePago->getContratoRel()->getCodigoTipoCotizanteFk() == 12) {
                    $floTarifaSalud = 12.5;
                }
                if ((($floSalario + $floSuplementario) >= ($salarioMinimo))) {
                    //$floTarifaSalud = 12.5;
                    if ($arPeriodoDetallePago->getContratoRel()->getGeneraIcbf() == 1) {
                        $floTarifaIcbf = 3;
                    }
                    if ($arPeriodoDetallePago->getContratoRel()->getGeneraSena() == 1) {
                        $floTarifaSena = 2;
                    }
                    //$floTarifaIcbf = 3;
                    //$floTarifaSena = 2;
                }
                if ($floIbcRiesgos == 0) {
                    $floTarifaRiesgos = 0;
                }
                if ($floIbcPension == 0) {
                    $floTarifaPension = 0;
                }
                if ($floIbcCaja == 0) {
                    $floTarifaCaja = 0;
                }
                $arPeriodoDetallePagoDetalle->setTarifaPension($floTarifaPension);
                $arPeriodoDetallePagoDetalle->setTarifaSalud($floTarifaSalud);
                $arPeriodoDetallePagoDetalle->setTarifaRiesgos($floTarifaRiesgos);
                $arPeriodoDetallePagoDetalle->setTarifaCaja($floTarifaCaja);
                $arPeriodoDetallePagoDetalle->setTarifaIcbf($floTarifaIcbf);
                $arPeriodoDetallePagoDetalle->setTarifaSena($floTarifaSena);
                $floCotizacionFSPSolidaridad = 0;
                $floCotizacionFSPSubsistencia = 0;
                $floAporteVoluntarioFondoPensionesObligatorias = 0;
                $floCotizacionVoluntariaFondoPensionesObligatorias = 0;
                $floCotizacionPension = $this->redondearAporte($floSalario, $floIbcPension, $floTarifaPension, $intDiasCotizarPension, $salarioMinimo, "");
                if ($floSalario >= ($salarioMinimo * 4)) {
                    $floCotizacionFSPSolidaridad = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                    $floCotizacionFSPSubsistencia = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                }
                $floCotizacionSalud = $this->redondearAporte($floSalario + $floSuplementario, $floIbcSalud, $floTarifaSalud, $intDiasCotizarSalud, $salarioMinimo, "");
                $floCotizacionRiesgos = $this->redondearAporte($floSalario + $floSuplementario, $floIbcRiesgos, $floTarifaRiesgos, $intDiasCotizarRiesgos, $salarioMinimo, "");
                $floCotizacionCaja = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaCaja, $intDiasCotizarCaja, $salarioMinimo, "");
                $floCotizacionIcbf = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaIcbf, $intDiasCotizarCaja, $salarioMinimo, "");
                $floCotizacionSena = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaSena, $intDiasCotizarCaja, $salarioMinimo, "");
                $floTotalCotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $floCotizacionPension;
                $floTotalFondoSolidaridad = $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
                $arPeriodoDetallePagoDetalle->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
                $arPeriodoDetallePagoDetalle->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
                $arPeriodoDetallePagoDetalle->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
                $arPeriodoDetallePagoDetalle->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSolidaridad);
                $floCotizacionPension = $this->redondearCien($floCotizacionPension);
                $floCotizacionSalud = $this->redondearCien($floCotizacionSalud);
                $floCotizacionRiesgos = $this->redondearCien($floCotizacionRiesgos);
                $floCotizacionCaja = $this->redondearCien($floCotizacionCaja);
                $arPeriodoDetallePagoDetalle->setCotizacionPension($floCotizacionPension);
                $arPeriodoDetallePagoDetalle->setCotizacionSalud($floCotizacionSalud);
                $arPeriodoDetallePagoDetalle->setCotizacionRiesgos($floCotizacionRiesgos);
                $arPeriodoDetallePagoDetalle->setCotizacionCaja($floCotizacionCaja);
                $arPeriodoDetallePagoDetalle->setCotizacionIcbf($floCotizacionIcbf);
                $arPeriodoDetallePagoDetalle->setCotizacionSena($floCotizacionSena);
                //$arPeriodoDetallePago->setCentroTrabajoCodigoCt($arEmpleado->getContratoRel()->getCodigoCentroCostoFk());
                $floTotalCotizacion = $floTotalFondoSolidaridad + $floTotalCotizacionFondos + $floCotizacionSalud + $floCotizacionRiesgos + $floCotizacionCaja + $floCotizacionIcbf + $floCotizacionSena;
                $arPeriodoDetallePagoDetalle->setTotalCotizacion($floTotalCotizacion);
                $em->persist($arPeriodoDetallePagoDetalle);
                $secuencia++;
            }

        }
        $fecha = new \DateTime('now');
        $fechaPeriodo = $arPeriodo->getFechaDesde();
        $arPeriodo->setFechaPago($fecha);
        $arPeriodo->setAnio($fechaPeriodo->format('Y'));
        $arPeriodo->setMes($fechaPeriodo->format('m'));
        $arPeriodo->setAnioPago($fecha->format('Y'));
        $arPeriodo->setMesPago($fecha->format('m'));
        $arPeriodo->setEstadoPagoGenerado(1);
        $em->persist($arPeriodo);
        $em->flush();
    }

    public function generarInteresMora($codigoPeriodo)
    {
        set_time_limit(0);
        ob_clean();
        $em = $this->getEntityManager();
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);

        $arConfiguracionAfiliacion = $em->getRepository('BrasaAfiliacionBundle:AfiConfiguracion')->find(1); //SALARIO MINIMO
        $arPeriodo->getFechaDesde();

        $fecha = $arPeriodo->getFechaDesde()->format('Y-m-d');
        $nuevafecha = strtotime('+1 month', strtotime($fecha));
        $nuevafecha = date('Y-m-d', $nuevafecha);
        //$fecha1 lleva 15 dias de mora
        //$nuevafecha = strtotime ( '+15 day' , strtotime ( $nuevafecha ) ) ;
        $nuevafecha = strtotime('+1 day', strtotime($nuevafecha)); // se cambio por un dia de mora en adelante, estaba a partir de 15 dias en adelante
        //$fecha1 lleva 1 dia de mora
        $fecha1 = date('Y-m-d', $nuevafecha);
        //$fecha2, mas de 20 dias de mora
        //$fecha2 = strtotime ( '+4 day' , strtotime ( $fecha1 ) ) ;
        $fecha2 = strtotime('+19 day', strtotime($fecha1));
        $fecha2 = date('Y-m-d', $fecha2);
        //$fecha1 lleva 1 dia de mora
        $fecha16 = new \DateTime($fecha1);
        //$fecha2, mas de 20 dias de mora
        $fecha20 = new \DateTime($fecha2);
        //fecha actual
        $hoy = new \DateTime(date('Y-m-d'));

        $porcentajeInteres = 0;
        $control = false;
        //si a fecha de hoy lleva mora de menos de 15 dias
        if ($hoy >= $fecha16 && $hoy <= $fecha20) {
            $porcentajeInteres = 0.5;
            $control = true;
        }
        //si a fecha de hoy lleva mora mas de 15 dias de mora
        if ($hoy > $fecha20) {
            $porcentajeInteres = 1;
            $control = true;
        }

        if ($control == true) {
            //Validar si se le tiene que aplicar interes de mora cuando los 2 ultimos digitos del cliente coincidan con los de la tabla de AfiPeriodoFechaPago
            $dosUltimosDigitosNitCliente = substr($arPeriodo->getClienteRel()->getNit(), -2);
            $fechaActual = new \DateTime('now');
            $anioActual = $fechaActual->format('Y');
            //$arPeriodoFechaPago = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoFechaPago')->DiaHabilPagar($anioActual, $dosUltimosDigitosNitCliente);
            $diasHabiles = $this->dias_semana($fechaActual->format('Y/m/') . "01", $fechaActual);
//            if ($diasHabiles >= $arPeriodoFechaPago->getDiaHabil()) { Se comentarea mientas ana maria hace el proceso de generar desgenerar
            //se valida si el interes de mora esta en 0.
            $porcentajeInteres = $arConfiguracionAfiliacion->getPorcentajeInteres();
            $diasinteres = $arConfiguracionAfiliacion->getDiasInteres();
            if ($arPeriodo->getInteresMora() == 0) {
                $valorTotal = $arPeriodo->getTotal();
                $valorSubtotal = $arPeriodo->getSubtotal();
                $valorInteresMora = $valorSubtotal * $porcentajeInteres / 100 * $diasinteres;
                $arPeriodo->setTotalAnterior($valorTotal);
                $arPeriodo->setInteresMora($valorInteresMora);
                $arPeriodo->setSubtotal($arPeriodo->getSubtotal() + $valorInteresMora);
                $arPeriodo->setSubtotalAnterior($valorSubtotal);
                $arPeriodo->setTotal($this->redondearCien($arPeriodo->getTotal() + $valorInteresMora));
            } else {
                $valorTotal = $arPeriodo->getTotalAnterior();
                $valorSubtotal = $arPeriodo->getSubtotalAnterior();
                $valorInteresMora = $valorTotal * $porcentajeInteres / 100 * $diasinteres;
                $arPeriodo->setInteresMora($valorInteresMora);
                $arPeriodo->setSubtotal($valorSubtotal + $valorInteresMora);
                $arPeriodo->setTotal($this->redondearCien($valorTotal + $valorInteresMora));
            }
//            }
        }
        $em->persist($arPeriodo);
        $em->flush();
    }

    public function redondearIbc2($ibc)
    {
        $ibcRetornar = ceil($ibc);
        return $ibcRetornar;
    }

    public function redondearIbc($intDias, $floIbcBruto, $salarioMinimo)
    {
        $floIbc = 0;
        $floIbcRedondedado = round($floIbcBruto, -3, PHP_ROUND_HALF_DOWN);
        $floIbcMinimo = $this->redondearIbcMinimo($intDias, $salarioMinimo);
        $floResiduo = fmod($floIbcBruto, 1000);
        if ($floIbcRedondedado < $floIbcMinimo) {
            if ($floResiduo > 500) {
                $floIbc = intval($floIbcBruto / 1000) * 1000 + 1000;
            } else {
                $floIbc = $floIbcBruto;
            }
            $floIbc = ceil($floIbc);
        } else {
            $floIbc = $floIbcRedondedado;
        }

        return $floIbc;
    }

    public function redondearIbcMinimo($intDias, $salarioMinimo)
    {
        $floValorDia = $salarioMinimo / 30;
        $floIbcBruto = intval($intDias * $floValorDia);
        return $floIbcBruto;
    }

    public function redondearAporte($floIbcTotal, $floIbc, $floTarifa, $intDias, $salarioMinimo, $intDiasVacaciones)
    {
        $floTarifa = $floTarifa / 100;
        $floIbcBruto = ($floIbcTotal / 30) * $intDias;
        $floCotizacionRedondeada = round($floIbc * $floTarifa, -2, PHP_ROUND_HALF_DOWN);
        $floCotizacionCalculada = $floIbcBruto * $floTarifa;
        $floCotizacionIBC = $floIbc * $floTarifa;
        $floResiduo = fmod($floCotizacionIBC, 100);
        $floCotizacionMinimo = $this->redondearAporteMinimo($floTarifa, $intDias, $salarioMinimo);
        if ($floCotizacionRedondeada < $floCotizacionMinimo) {
            if ($floResiduo > 50) {
                $floCotizacionRedondeada = intval($floCotizacionIBC / 100) * 100 + 100;
            } else {
                if ($floCotizacionIBC - $floResiduo >= $floCotizacionCalculada) {
                    $floCotizacionRedondeada = $floCotizacionIBC - $floResiduo;
                } else {
                    $floCotizacionRedondeada = $floCotizacionIBC;
                }
            }

            if (round($floCotizacionRedondeada) >= $floCotizacionCalculada) {
                $floCotizacion = round($floCotizacionRedondeada);
            } else {
                $floCotizacion = ceil($floCotizacionRedondeada);
            }
        } else {
            if ($floIbc <= $salarioMinimo) {
                #$floCotizacion = $floCotizacionMinimo; Validar bien este proceso.
                $floCotizacion = $floCotizacionIBC;
                $floResiduo2 = fmod($floCotizacion, 100);
                if ($floResiduo2 > 50) {
                    $floCotizacion = intval($floCotizacion / 100) * 100 + 100;
                } else {
                    $floCotizacion = $floCotizacion - $floResiduo2;
                }
            } else {
                if ($floResiduo > 50) {
                    $floCotizacionRedondeada = intval($floCotizacionIBC / 100) * 100 + 100;
                } else {
                    if ($floCotizacionIBC - $floResiduo >= $floCotizacionCalculada) {
                        $floCotizacionRedondeada = $floCotizacionIBC - $floResiduo;
                    } else {
                        $floCotizacionRedondeada = $floCotizacionIBC;
                    }
                }
                if (round($floCotizacionRedondeada) >= $floCotizacionCalculada) {
                    $floCotizacion = round($floCotizacionRedondeada);
                } else {
                    $floCotizacion = ceil($floCotizacionRedondeada);
                }
            }
        }
        return $floCotizacion;
    }

    public function redondearAporteMinimo($floTarifa, $intDias, $salarioMinimo)
    {
        $floSalario = $salarioMinimo;
        $douValorDia = $floSalario / 30;
        $floIbcReal = $douValorDia * $intDias;
        if ($intDias != 30) {
            $floIbcRedondeo = round($floIbcReal, -3, PHP_ROUND_HALF_DOWN);
            if ($floIbcRedondeo > $floIbcReal) {
                $floIbc = ceil($floIbcRedondeo);
            } else {
                $floIbc = ceil($floIbcReal);
            }
        } else {
            $floIbc = $floSalario;
        }
        $douCotizacion = 0;
        $floCotizacionCalculada = $floIbcReal * $floTarifa;
        $floCotizacionIBC = $floIbc * $floTarifa;
        $floResiduo = fmod($floCotizacionIBC, 100);
        if ($floResiduo > 50) {
            $floCotizacionRedondeada = intval($floCotizacionIBC / 100) * 100 + 100;
        } else {
            if ($floCotizacionIBC - $floResiduo >= $floCotizacionCalculada) {
                $floCotizacionRedondeada = $floCotizacionIBC - $floResiduo;
            } else {
                $floCotizacionRedondeada = $floCotizacionIBC;
            }
        }

        if (round($floCotizacionRedondeada) >= $floCotizacionCalculada) {
            $douCotizacion = round($floCotizacionRedondeada);
        } else {
            $douCotizacion = ceil($floCotizacionRedondeada);
        }
        return $douCotizacion;
    }

    public function redondearAporte2($aporte)
    {
        $aporteRedondeado = round($aporte);
        return $aporteRedondeado;
    }

    public function diasContrato($arPeriodo, $arContrato)
    {
        $dateFechaDesde = "";
        $dateFechaHasta = "";
        $intDiasDevolver = 0;
        $dia = "";
        $fechaFinalizaContrato = $arContrato->getFechaHasta();
        if ($arContrato->getIndefinido() == 1) {
            $fecha = date_create(date('Y-m-d'));
            date_modify($fecha, '+100000 day');
            $fechaFinalizaContrato = $fecha;
        }
        if ($arContrato->getFechaDesde() < $arPeriodo->getFechaDesde() == true) {
            $dateFechaDesde = $arPeriodo->getFechaDesde();
        } else {
            if ($arContrato->getFechaDesde() > $arPeriodo->getFechaHasta() == true) {
                if ($arContrato->getFechaDesde() == $arPeriodo->getFechaHasta()) {
                    $dateFechaDesde = $arPeriodo->getFechaHasta();
                    $intDiasDevolver = 1;
                } else {
                    $intDiasDevolver = 0;
                }
            } else {
                $dateFechaDesde = $arContrato->getFechaDesde();
            }
        }
        if ($fechaFinalizaContrato > $arPeriodo->getFechaHasta() == true) {
            $dateFechaHasta = $arPeriodo->getFechaHasta();
        } else {
            if ($fechaFinalizaContrato < $arPeriodo->getFechaDesde() == true) {
                $intDiasDevolver = 0;
            } else {
                $dateFechaHasta = $fechaFinalizaContrato;
            }
        }
        $diafebrero = 0;
        if ($dateFechaDesde != "" && $dateFechaHasta != "") {
            $intDias = $dateFechaDesde->diff($dateFechaHasta);
            $intDias = $intDias->format('%a');
            $febrero = $dateFechaHasta->format('m');
            if ($febrero == 02) {
                $dia = $dateFechaHasta->format('d');
                if ($dia == 28) {
                    $diafebrero = 2;
                } else {
                    $diafebrero = 1;
                }
                //Si se retira antes no sumarle los dias de febrero
                if ($dateFechaHasta < $arPeriodo->getFechaHasta()) {
                    $diafebrero = 0;
                }
            }
            $intDiasDevolver = $intDias + 1 + $diafebrero;

            if ($intDiasDevolver < 28 && $febrero == 02) {
                $intDiasDevolver = $intDiasDevolver;
            }
            $desde = $dateFechaDesde->format('d');
            if ($desde == 28 && $dia == 28 && $febrero == 02 && $arContrato->getIndefinido() == 0) {
                $intDiasDevolver = 1;
            }
        }
        return $intDiasDevolver;
    }

    public function pendienteDql($codigoCliente)
    {
        $dql = "SELECT p FROM BrasaAfiliacionBundle:AfiPeriodo p WHERE p.estadoFacturado = 0 AND p.codigoClienteFk = " . $codigoCliente;
        $dql .= " ORDER BY p.codigoPeriodoPk DESC";
        return $dql;
    }

    public function redondearCien($valor)
    {
        $valor = round($valor);
        if ($valor != 0) {
            $residuo = fmod($valor, 100);
            if ($residuo != 0) {
                if ($residuo > 1) {
                    $valor = intval($valor / 100) * 100 + 100;
                }
            }
        }
        return $valor;
    }

    public function liquidarIncapacidadGeneral($floSalario, $floSalarioAnterior, $intDias)
    {
        $em = $this->getEntityManager();
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        if ($floSalarioAnterior > 0) {
            $floSalario = $floSalarioAnterior;
        }
        $floValorDia = $floSalario / 30;
        $floValorDiaSalarioMinimo = $arConfiguracionNomina->getVrSalario() / 30;
        $floIbcIncapacidad = 0;

        if ($floSalario <= $arConfiguracionNomina->getVrSalario()) {
            $floIbcIncapacidad = $intDias * $floValorDia;
        }
        if ($floSalario > $arConfiguracionNomina->getVrSalario() && $floSalario <= $arConfiguracionNomina->getVrSalario() * 1.5) {
            $floIbcIncapacidad = $intDias * $floValorDiaSalarioMinimo;
        }
        if ($floSalario > ($arConfiguracionNomina->getVrSalario() * 1.5)) {
            $floIbcIncapacidad = $intDias * $floValorDia;
            $floIbcIncapacidad = ($floIbcIncapacidad * 66.67) / 100;
        }

        return $floIbcIncapacidad;
    }

    public function liquidarIncapacidadLaboral($floSalario, $floSalarioAnterior, $intDias)
    {
        if ($floSalarioAnterior > 0) {
            $floSalario = $floSalarioAnterior;
        }
        $floValorDia = $floSalario / 30;
        $floIbcIncapacidad = $intDias * $floValorDia;
        return $floIbcIncapacidad;
    }

    public function dias_semana($fechaInicio, $fechaFin)
    {
        $em = $this->getEntityManager();
        $arFestivo = new \Brasa\GeneralBundle\Entity\GenFestivo();
        $arFestivo = $em->getRepository('BrasaGeneralBundle:GenFestivo')->findOneBy(array('fecha' => $fechaFin));
        $fecha_1 = date_create($fechaInicio);
        $fecha_2 = $fechaFin;
        $cantidadDiaSemana = 0;
        if ($fecha_1 > $fecha_2)
            return FALSE;
        while ($fecha_1 <= $fecha_2) {
            $diaSemana = $fecha_1->format('w');
            if ($diaSemana > 0 && $diaSemana < 6) {
                if (count($arFestivo) < 1) {
                    $cantidadDiaSemana++;
                }
            }
            date_add($fecha_1, date_interval_create_from_date_string('1 day'));
        }
        return $cantidadDiaSemana;
    }

}
