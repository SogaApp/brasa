<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurSoportePagoRepository extends EntityRepository {

    public function listaDql($codigoSoportePagoPeriodo = "") {
        $dql   = "SELECT sp FROM BrasaTurnoBundle:TurSoportePago sp WHERE sp.codigoSoportePagoPeriodoFk = " . $codigoSoportePagoPeriodo;
        return $dql;
    }

    public function resumen($arSoportePagoPeriodo) {
        $em = $this->getEntityManager();
        $arSoportePagoPeriodoActualizar = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();        
        $arSoportePagoPeriodoActualizar = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($arSoportePagoPeriodo->getCodigoSoportePagoPeriodoPk());
        $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportesPago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $arSoportePagoPeriodo->getCodigoSoportePagoPeriodoPk()));
        foreach ($arSoportesPago as $arSoportePago) {
            $arSoportePagoAct = new \Brasa\TurnoBundle\Entity\TurSoportePago();
            $arSoportePagoAct = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arSoportePago->getCodigoSoportePagoPk());
            $dql   = "SELECT spd.codigoRecursoFk, "
                    . "SUM(spd.descanso) as descanso, "
                    . "SUM(spd.novedad) as novedad, "
                    . "SUM(spd.incapacidad) as incapacidad, "
                    . "SUM(spd.licencia) as licencia, "
                    . "SUM(spd.licenciaNoRemunerada) as licenciaNoRemunerada, "
                    . "SUM(spd.vacacion) as vacacion, "
                    . "SUM(spd.ingreso) as ingreso, "
                    . "SUM(spd.retiro) as retiro, "
                    . "SUM(spd.induccion) as induccion, "
                    . "SUM(spd.dias) as dias, "
                    . "SUM(spd.horasDescanso) as horasDescanso, "
                    . "SUM(spd.horasNovedad) as horasNovedad, "
                    . "SUM(spd.horasDiurnas) as horasDiurnas, "
                    . "SUM(spd.horasNocturnas) as horasNocturnas, "
                    . "SUM(spd.horasFestivasDiurnas) as horasFestivasDiurnas, "
                    . "SUM(spd.horasFestivasNocturnas) as horasFestivasNocturnas, "
                    . "SUM(spd.horasExtrasOrdinariasDiurnas) as horasExtrasOrdinariasDiurnas, "
                    . "SUM(spd.horasExtrasOrdinariasNocturnas) as horasExtrasOrdinariasNocturnas, "
                    . "SUM(spd.horasExtrasFestivasDiurnas) as horasExtrasFestivasDiurnas, "
                    . "SUM(spd.horasExtrasFestivasNocturnas) as horasExtrasFestivasNocturnas, "
                    . "SUM(spd.horasRecargoNocturno) as horasRecargoNocturno, "
                    . "SUM(spd.horasRecargoFestivoDiurno) as horasRecargoFestivoDiurno, "
                    . "SUM(spd.horasRecargoFestivoNocturno) as horasRecargoFestivoNocturno "
                    . "FROM BrasaTurnoBundle:TurSoportePagoDetalle spd "
                    . "WHERE spd.codigoSoportePagoFk =  " . $arSoportePago->getCodigoSoportePagoPk() . " "
                    . "GROUP BY spd.codigoRecursoFk" ;
            $query = $em->createQuery($dql);
            $arrayResultado = $query->getResult();
            for($i = 0; $i < count($arrayResultado); $i++){
                if($arSoportePagoPeriodo->getDiasAdicionales() > 0) {
                    $arrayResultado[$i]['dias'] += $arSoportePagoPeriodo->getDiasAdicionales();
                    $arrayResultado[$i]['horasDiurnas'] += $arSoportePagoPeriodo->getDiasAdicionales() * 8;
                }
                $intHorasPago = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];

                if($arSoportePagoPeriodoActualizar->getDescansoFestivoFijo()) {
                    $arrayResultado[$i]['horasDiurnas'] += $arSoportePagoPeriodoActualizar->getFestivos() * 8;
                    $arrayResultado[$i]['descanso'] += $arSoportePagoPeriodoActualizar->getFestivos();
                }
                /*if($codigoRecurso == 450) {
                    echo "hola";
                }*/
                $intHoras = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasNovedad'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];
                if($arrayResultado[$i]['dias'] > $arSoportePagoPeriodoActualizar->getDiasPeriodo()) {
                    $arrayResultado[$i]['dias'] = $arSoportePagoPeriodoActualizar->getDiasPeriodo();
                }
                $diasTransporte = $arrayResultado[$i]['dias']+$arrayResultado[$i]['induccion'];
                if($diasTransporte > $arSoportePagoPeriodoActualizar->getDiasPeriodo()) {
                    $diasTransporte = $arSoportePagoPeriodoActualizar->getDiasPeriodo();
                }
                $arSoportePagoAct->setDias($arrayResultado[$i]['dias']);
                $arSoportePagoAct->setDiasTransporte($diasTransporte);
                $arSoportePagoAct->setDescanso($arrayResultado[$i]['descanso']);
                $arSoportePagoAct->setNovedad($arrayResultado[$i]['novedad']);
                $arSoportePagoAct->setIncapacidad($arrayResultado[$i]['incapacidad']);
                $arSoportePagoAct->setLicencia($arrayResultado[$i]['licencia']);
                $arSoportePagoAct->setLicenciaNoRemunerada($arrayResultado[$i]['licenciaNoRemunerada']);
                $arSoportePagoAct->setVacacion($arrayResultado[$i]['vacacion']);
                $arSoportePagoAct->setInduccion($arrayResultado[$i]['induccion']);
                $arSoportePagoAct->setIngreso($arrayResultado[$i]['ingreso']);
                $arSoportePagoAct->setRetiro($arrayResultado[$i]['retiro']);
                $arSoportePagoAct->setHorasPago($intHorasPago);
                $arSoportePagoAct->setHoras($intHoras);
                $arSoportePagoAct->setHorasDescanso($arrayResultado[$i]['horasDescanso']);
                $arSoportePagoAct->setHorasNovedad($arrayResultado[$i]['horasNovedad']);
                $arSoportePagoAct->setHorasDiurnas($arrayResultado[$i]['horasDiurnas']);
                $arSoportePagoAct->setHorasNocturnas($arrayResultado[$i]['horasNocturnas']);
                $arSoportePagoAct->setHorasFestivasDiurnas($arrayResultado[$i]['horasFestivasDiurnas']);
                $arSoportePagoAct->setHorasFestivasNocturnas($arrayResultado[$i]['horasFestivasNocturnas']);
                $arSoportePagoAct->setHorasExtrasOrdinariasDiurnas($arrayResultado[$i]['horasExtrasOrdinariasDiurnas']);
                $arSoportePagoAct->setHorasExtrasOrdinariasNocturnas($arrayResultado[$i]['horasExtrasOrdinariasNocturnas']);
                $arSoportePagoAct->setHorasExtrasFestivasDiurnas($arrayResultado[$i]['horasExtrasFestivasDiurnas']);
                $arSoportePagoAct->setHorasExtrasFestivasNocturnas($arrayResultado[$i]['horasExtrasFestivasNocturnas']);
                $arSoportePagoAct->setHorasRecargoNocturno($arrayResultado[$i]['horasRecargoNocturno']);
                $arSoportePagoAct->setHorasRecargoFestivoDiurno($arrayResultado[$i]['horasRecargoFestivoDiurno']);
                $arSoportePagoAct->setHorasRecargoFestivoNocturno($arrayResultado[$i]['horasRecargoFestivoNocturno']);
                $arSoportePagoAct->setHorasDescansoReales($arrayResultado[$i]['horasDescanso']);
                $arSoportePagoAct->setHorasDiurnasReales($arrayResultado[$i]['horasDiurnas']);
                $arSoportePagoAct->setHorasNocturnasReales($arrayResultado[$i]['horasNocturnas']);
                $arSoportePagoAct->setHorasFestivasDiurnasReales($arrayResultado[$i]['horasFestivasDiurnas']);
                $arSoportePagoAct->setHorasFestivasNocturnasReales($arrayResultado[$i]['horasFestivasNocturnas']);
                $arSoportePagoAct->setHorasExtrasOrdinariasDiurnasReales($arrayResultado[$i]['horasExtrasOrdinariasDiurnas']);
                $arSoportePagoAct->setHorasExtrasOrdinariasNocturnasReales($arrayResultado[$i]['horasExtrasOrdinariasNocturnas']);
                $arSoportePagoAct->setHorasExtrasFestivasDiurnasReales($arrayResultado[$i]['horasExtrasFestivasDiurnas']);
                $arSoportePagoAct->setHorasExtrasFestivasNocturnasReales($arrayResultado[$i]['horasExtrasFestivasNocturnas']);

                $em->persist($arSoportePagoAct);

            }
        }
        $arSoportePagoPeriodoActualizar->setRecursos(count($arSoportesPago));
        $em->persist($arSoportePagoPeriodoActualizar);
        $em->flush();
    }

    public function resumenSoportePago($dateFechaDesde, $dateFechaHasta, $codigoSoportePago) {
        $em = $this->getEntityManager();
        $arSoportePago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportePago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($codigoSoportePago);
        $dql   = "SELECT spd.codigoRecursoFk, "
                . "SUM(spd.descanso) as descanso, "
                . "SUM(spd.novedad) as novedad, "
                . "SUM(spd.incapacidad) as incapacidad, "
                . "SUM(spd.licencia) as licencia, "
                . "SUM(spd.licenciaNoRemunerada) as licenciaNoRemunerada, "
                . "SUM(spd.vacacion) as vacacion, "
                . "SUM(spd.ingreso) as ingreso, "
                . "SUM(spd.retiro) as retiro, "
                . "SUM(spd.induccion) as induccion, "
                . "SUM(spd.dias) as dias, "
                . "SUM(spd.horasDescanso) as horasDescanso, "
                . "SUM(spd.horasNovedad) as horasNovedad, "
                . "SUM(spd.horasDiurnas) as horasDiurnas, "
                . "SUM(spd.horasNocturnas) as horasNocturnas, "
                . "SUM(spd.horasFestivasDiurnas) as horasFestivasDiurnas, "
                . "SUM(spd.horasFestivasNocturnas) as horasFestivasNocturnas, "
                . "SUM(spd.horasExtrasOrdinariasDiurnas) as horasExtrasOrdinariasDiurnas, "
                . "SUM(spd.horasExtrasOrdinariasNocturnas) as horasExtrasOrdinariasNocturnas, "
                . "SUM(spd.horasExtrasFestivasDiurnas) as horasExtrasFestivasDiurnas, "
                . "SUM(spd.horasExtrasFestivasNocturnas) as horasExtrasFestivasNocturnas, "
                . "SUM(spd.horasRecargoNocturno) as horasRecargoNocturno, "
                . "SUM(spd.horasRecargoFestivoDiurno) as horasRecargoFestivoDiurno, "
                . "SUM(spd.horasRecargoFestivoNocturno) as horasRecargoFestivoNocturno "
                . "FROM BrasaTurnoBundle:TurSoportePagoDetalle spd "
                . "WHERE spd.codigoSoportePagoFk =  " . $codigoSoportePago . " "
                . "GROUP BY spd.codigoRecursoFk" ;
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        for($i = 0; $i < count($arrayResultado); $i++){
            $codigoRecurso = $arrayResultado[$i]['codigoRecursoFk'];
            $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);

            $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
            $arEmpleado = $arRecurso->getEmpleadoRel();
            if($arEmpleado->getEstadoContratoActivo()) {
                $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoActivoFk());
            } else {
                if($arEmpleado->getCodigoContratoUltimoFk()) {
                    $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoUltimoFk());
                }
            }
            if($arSoportePago->getSoportePagoPeriodoRel()->getDiasAdicionales() > 0) {
                $arrayResultado[$i]['dias'] += $arSoportePagoPeriodo->getDiasAdicionales();
                $arrayResultado[$i]['horasDiurnas'] += $arSoportePagoPeriodo->getDiasAdicionales() * 8;
            }
            $intHorasPago = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];

            if($arSoportePago->getSoportePagoPeriodoRel()->getDescansoFestivoFijo()) {
                $arrayResultado[$i]['horasDiurnas'] += $arSoportePago->getSoportePagoPeriodoRel()->getFestivos() * 8;
                $arrayResultado[$i]['descanso'] += $arSoportePago->getSoportePagoPeriodoRel()->getFestivos();
            }
            /*if($codigoRecurso == 450) {
                echo "hola";
            }*/
            $intHoras = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasNovedad'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];
            if($arrayResultado[$i]['dias'] > $arSoportePago->getSoportePagoPeriodoRel()->getDiasPeriodo()) {
                $arrayResultado[$i]['dias'] = $arSoportePago->getSoportePagoPeriodoRel()->getDiasPeriodo();
            }
            $arSoportePago->setDias($arrayResultado[$i]['dias']);
            $arSoportePago->setDiasTransporte($arrayResultado[$i]['dias']+$arrayResultado[$i]['induccion']);
            $arSoportePago->setDescanso($arrayResultado[$i]['descanso']);
            $arSoportePago->setNovedad($arrayResultado[$i]['novedad']);
            $arSoportePago->setIncapacidad($arrayResultado[$i]['incapacidad']);
            $arSoportePago->setLicencia($arrayResultado[$i]['licencia']);
            $arSoportePago->setLicenciaNoRemunerada($arrayResultado[$i]['licenciaNoRemunerada']);
            $arSoportePago->setVacacion($arrayResultado[$i]['vacacion']);
            $arSoportePago->setIngreso($arrayResultado[$i]['ingreso']);
            $arSoportePago->setRetiro($arrayResultado[$i]['retiro']);
            $arSoportePago->setInduccion($arrayResultado[$i]['induccion']);
            $arSoportePago->setHorasPago($intHorasPago);
            $arSoportePago->setHoras($intHoras);
            $arSoportePago->setHorasDescanso($arrayResultado[$i]['horasDescanso']);
            $arSoportePago->setHorasNovedad($arrayResultado[$i]['horasNovedad']);
            $arSoportePago->setHorasDiurnas($arrayResultado[$i]['horasDiurnas']);
            $arSoportePago->setHorasNocturnas($arrayResultado[$i]['horasNocturnas']);
            $arSoportePago->setHorasFestivasDiurnas($arrayResultado[$i]['horasFestivasDiurnas']);
            $arSoportePago->setHorasFestivasNocturnas($arrayResultado[$i]['horasFestivasNocturnas']);
            $arSoportePago->setHorasExtrasOrdinariasDiurnas($arrayResultado[$i]['horasExtrasOrdinariasDiurnas']);
            $arSoportePago->setHorasExtrasOrdinariasNocturnas($arrayResultado[$i]['horasExtrasOrdinariasNocturnas']);
            $arSoportePago->setHorasExtrasFestivasDiurnas($arrayResultado[$i]['horasExtrasFestivasDiurnas']);
            $arSoportePago->setHorasExtrasFestivasNocturnas($arrayResultado[$i]['horasExtrasFestivasNocturnas']);
            $arSoportePago->setHorasRecargoNocturno($arrayResultado[$i]['horasRecargoNocturno']);
            $arSoportePago->setHorasRecargoFestivoDiurno($arrayResultado[$i]['horasRecargoFestivoDiurno']);
            $arSoportePago->setHorasRecargoFestivoNocturno($arrayResultado[$i]['horasRecargoFestivoNocturno']);
            $arSoportePago->setHorasDescansoReales($arrayResultado[$i]['horasDescanso']);
            $arSoportePago->setHorasDiurnasReales($arrayResultado[$i]['horasDiurnas']);
            $arSoportePago->setHorasNocturnasReales($arrayResultado[$i]['horasNocturnas']);
            $arSoportePago->setHorasFestivasDiurnasReales($arrayResultado[$i]['horasFestivasDiurnas']);
            $arSoportePago->setHorasFestivasNocturnasReales($arrayResultado[$i]['horasFestivasNocturnas']);
            $arSoportePago->setHorasExtrasOrdinariasDiurnasReales($arrayResultado[$i]['horasExtrasOrdinariasDiurnas']);
            $arSoportePago->setHorasExtrasOrdinariasNocturnasReales($arrayResultado[$i]['horasExtrasOrdinariasNocturnas']);
            $arSoportePago->setHorasExtrasFestivasDiurnasReales($arrayResultado[$i]['horasExtrasFestivasDiurnas']);
            $arSoportePago->setHorasExtrasFestivasNocturnasReales($arrayResultado[$i]['horasExtrasFestivasNocturnas']);
            if($arContrato) {
                $arSoportePago->setCodigoContratoFk($arContrato->getCodigoContratoPk());
                $arSoportePago->setVrSalario($arContrato->getVrSalario());
            }
            if($arSoportePago->getRecursoRel()->getCodigoTurnoFijoNominaFk()) {
                $arSoportePago->setTurnoFijo(1);
            }
            $em->persist($arSoportePago);

        }
        $em->flush();
        return true;
    }

    public function generar($arSoportePago = "", $arFestivos) {
        $em = $this->getEntityManager();
        $intDiaInicial= $arSoportePago->getFechaDesde()->format('j');
        $intDiaFinal = $arSoportePago->getFechaHasta()->format('j');
        $dateFechaDesde = $arSoportePago->getFechaDesde();
        $dateFechaHasta = $arSoportePago->getFechaHasta();
        $turnoFijo = 0;
        $arRecurso = $arSoportePago->getRecursoRel();        
        if($arSoportePago->getTurnoFijo()) {
            $turnoFijo = 1;
        }
        $arrTurnoFijo[] = null;
        $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->periodoDias($dateFechaDesde->format('Y'), $dateFechaDesde->format('m'), $arRecurso->getCodigoRecursoPk());
        for($i = $intDiaInicial; $i <= $intDiaFinal; $i++) {
            $strFecha = $dateFechaDesde->format('Y/m/') . $i;
            $dateFecha = date_create($strFecha);
            $nuevafecha = strtotime ( '+1 day' , strtotime ( $strFecha ) ) ;
            $dateFecha2 = date ( 'Y/m/j' , $nuevafecha );
            $dateFecha2 = date_create($dateFecha2);
            $boolFestivo = $this->festivo($arFestivos, $dateFecha);
            $boolFestivo2 = $this->festivo($arFestivos, $dateFecha2);
            $arrTurnos = array();
            foreach ($arProgramacionDetalles as $arProgramacionDetalle) {
                if($arProgramacionDetalle['dia'.$i] != "") {
                    $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle['dia'.$i]);
                    if($arTurno) {
                        $arrTurnos[] = array('horaDesde' => $arTurno->getHoraDesde(), 'turno' => $arTurno->getCodigoTurnoPk(), 'codigoProgramacionDetallePk' => $arProgramacionDetalle['codigoProgramacionDetallePk'], 'novedad' => $arTurno->getNovedad());
                    }
                }
            }
            asort($arrTurnos);
            $horasIniciales = 0;
            foreach ($arrTurnos as $arrTurno) {
                $strTurno = $arrTurno['turno'];
                if($turnoFijo == 1) {
                    if(!isset($arrTurnoFijo[$i])) {
                        $arrTurnoFijo[$i] = $strTurno;
                    } else {
                       if($arrTurnoFijo[$i] == null) {
                          $arrTurnoFijo[$i] = $strTurno;
                       } else {
                           $strTurno = null;
                       }
                    }
                    if($i == 31) {
                        if(!$arrTurno['novedad']) {
                            $strTurno = null;
                        }
                    }
                }
                if($strTurno) {
                    $horasIniciales = $this->insertarSoportePago($arSoportePago, $strTurno, $dateFecha, $dateFecha2, $boolFestivo, $boolFestivo2, $horasIniciales, $arrTurno['codigoProgramacionDetallePk']);
                }
            }
        }
        return true;
    }

    public function generarAlterna($arSoportePago = "", $arFestivos) {
        $em = $this->getEntityManager();
        $intDiaInicial= $arSoportePago->getFechaDesde()->format('j');
        $intDiaFinal = $arSoportePago->getFechaHasta()->format('j');
        $dateFechaDesde = $arSoportePago->getFechaDesde();
        $dateFechaHasta = $arSoportePago->getFechaHasta();
        $turnoFijo = 0;
        $arRecurso = $arSoportePago->getRecursoRel();
        if($arSoportePago->getTurnoFijo()) {
            $turnoFijo = 1;
        }
        $arrTurnoFijo[] = null;        
        $arProgramacionesAlternas = new \Brasa\TurnoBundle\Entity\TurProgramacionAlterna();
        $arProgramacionesAlternas = $em->getRepository('BrasaTurnoBundle:TurProgramacionAlterna')->periodoDias($dateFechaDesde->format('Y'), $dateFechaDesde->format('m'), $arRecurso->getCodigoRecursoPk());
        for($i = $intDiaInicial; $i <= $intDiaFinal; $i++) {
            $strFecha = $dateFechaDesde->format('Y/m/') . $i;
            $dateFecha = date_create($strFecha);
            $nuevafecha = strtotime ( '+1 day' , strtotime ( $strFecha ) ) ;
            $dateFecha2 = date ( 'Y/m/j' , $nuevafecha );
            $dateFecha2 = date_create($dateFecha2);
            $boolFestivo = $this->festivo($arFestivos, $dateFecha);
            $boolFestivo2 = $this->festivo($arFestivos, $dateFecha2);
            $arrTurnos = array();
            foreach ($arProgramacionesAlternas as $arProgramacionAlterna) {
                if($arProgramacionAlterna['dia'.$i] != "") {
                    $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionAlterna['dia'.$i]);
                    if($arTurno) {
                        $arrTurnos[] = array('horaDesde' => $arTurno->getHoraDesde(), 'turno' => $arTurno->getCodigoTurnoPk(), 'codigoProgramacionDetallePk' => NULL, 'novedad' => $arTurno->getNovedad());
                    }
                }
            }
            asort($arrTurnos);
            $horasIniciales = 0;
            foreach ($arrTurnos as $arrTurno) {
                $strTurno = $arrTurno['turno'];
                if($turnoFijo == 1) {
                    if(!isset($arrTurnoFijo[$i])) {
                        $arrTurnoFijo[$i] = $strTurno;
                    } else {
                       if($arrTurnoFijo[$i] == null) {
                          $arrTurnoFijo[$i] = $strTurno;
                       } else {
                           $strTurno = null;
                       }
                    }
                    if($i == 31) {
                        if(!$arrTurno['novedad']) {
                            $strTurno = null;
                        }
                    }
                }
                if($strTurno) {
                    $horasIniciales = $this->insertarSoportePago($arSoportePago, $strTurno, $dateFecha, $dateFecha2, $boolFestivo, $boolFestivo2, $horasIniciales, NULL);
                }
            }
        }
        return true;
    }    
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $arSoportePago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($codigo);
                $em->remove($arSoportePago);
            }
            $em->flush();
        }
    }

    public function insertarSoportePago ($arSoportePago, $codigoTurno, $dateFecha, $dateFecha2, $boolFestivo, $boolFestivo2, $horasIniciales, $codigoProgramacionDetalle = "") {
        $em = $this->getEntityManager();        
        if($codigoProgramacionDetalle) {
            $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigoProgramacionDetalle);            
        }        
        $arSoportePagoPeriodo = $arSoportePago->getSoportePagoPeriodoRel();
        $strTurnoFijoNomina = $arSoportePagoPeriodo->getCentroCostoRel()->getCodigoTurnoFijoNominaFk();
        $strTurnoFijoDescanso = $arSoportePagoPeriodo->getCentroCostoRel()->getCodigoTurnoFijoDescansoFk();

        if($arSoportePago->getRecursoRel()->getCodigoTurnoFijoNominaFk()) {
            $strTurnoFijoNomina = $arSoportePago->getRecursoRel()->getCodigoTurnoFijoNominaFk();
        }
        if($arSoportePago->getRecursoRel()->getCodigoTurnoFijoDescansoFk()) {
            $strTurnoFijoDescanso = $arSoportePago->getRecursoRel()->getCodigoTurnoFijoDescansoFk();
        }
        $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
        $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigoTurno);
        if($arTurno->getDescanso() == 0 && $arTurno->getNovedad() == 0) {
            if($strTurnoFijoNomina) {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurnoFijoNomina);
            }
            if($dateFecha->format('d') == 31) {
                if($arSoportePago->getRecursoRel()->getCodigoTurnoFijo31Fk()) {
                    $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arSoportePago->getRecursoRel()->getCodigoTurnoFijo31Fk());
                }
            }
            
        }
        if($arTurno->getDescanso() == 1) {
            if($strTurnoFijoDescanso) {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurnoFijoDescanso);
            }
        }

        $intDias = 0;
        $intMinutoInicio = (($arTurno->getHoraDesde()->format('i') * 100)/60)/100;
        $intHoraInicio = $arTurno->getHoraDesde()->format('G');
        $intHoraInicio += $intMinutoInicio;
        $intMinutoFinal = (($arTurno->getHoraHasta()->format('i') * 100)/60)/100;
        $intHoraFinal = $arTurno->getHoraHasta()->format('G');
        $intHoraFinal += $intMinutoFinal;
        $diaSemana = $dateFecha->format('N');
        $diaSemana2 = $dateFecha2->format('N');
        if($arTurno->getNovedad() == 0) {
            $intDias += 1;
        }
        if($diaSemana == 7) {
            $boolFestivo = 1;
        }
        if($diaSemana2 == 7) {
            $boolFestivo2 = 1;
        }
        $arrHoras1 = null;
        if(($intHoraInicio + $intMinutoInicio) <= $intHoraFinal){
            $arrHoras = $this->turnoHoras($intHoraInicio, $intMinutoInicio, $intHoraFinal, $boolFestivo, $horasIniciales, $arTurno->getNovedad(), $arTurno->getDescanso());
            $horasTotales = $arrHoras['horas']+$arrHoras1['horas'];
        } else {
            $arrHoras = $this->turnoHoras($intHoraInicio, $intMinutoInicio, 24, $boolFestivo, $horasIniciales, $arTurno->getNovedad(), $arTurno->getDescanso());
            $arrHoras1 = $this->turnoHoras(0, 0, $intHoraFinal, $boolFestivo2, $arrHoras['horas'], $arTurno->getNovedad(), $arTurno->getDescanso());
            $horasTotales = $arrHoras1['horas'];
        }
        if($arTurno->getDescanso() == 1) {
            if($arSoportePago->getDescansoOrdinario()) {
                $arrHoras['horasDescanso'] = 8;
            }
        }
        if($arTurno->getNovedad() == 0 && $arSoportePago->getTurnoFijo()) {
            $arrHoras = $this->horasOrdinarias();
            $arrHoras1 = $this->horasAnuladas();
        }
        
        $arSoportePagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
        $arSoportePagoDetalle->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
        $arSoportePagoDetalle->setSoportePagoRel($arSoportePago);
        $arSoportePagoDetalle->setAnio($arSoportePago->getAnio());
        $arSoportePagoDetalle->setMes($arSoportePago->getMes());
        $arSoportePagoDetalle->setRecursoRel($arSoportePago->getRecursoRel());
        $arSoportePagoDetalle->setFecha($dateFecha);
        $arSoportePagoDetalle->setTurnoRel($arTurno);
        $arSoportePagoDetalle->setDescanso($arTurno->getDescanso());
        $arSoportePagoDetalle->setNovedad($arTurno->getNovedad());
        $arSoportePagoDetalle->setIncapacidad($arTurno->getIncapacidad());
        $arSoportePagoDetalle->setLicencia($arTurno->getLicencia());
        $arSoportePagoDetalle->setLicenciaNoRemunerada($arTurno->getLicenciaNoRemunerada());
        $arSoportePagoDetalle->setVacacion($arTurno->getVacacion());
        $arSoportePagoDetalle->setIngreso($arTurno->getIngreso());
        $arSoportePagoDetalle->setInduccion($arTurno->getInduccion());
        
        
        if($dateFecha->format('d') == 31 && $arSoportePagoPeriodo->getPagarDia31() == false) {
            $arSoportePagoDetalle->setDias(0);
            $arSoportePagoDetalle->setHoras(0);
            $arSoportePagoDetalle->setHorasDiurnas(0);
            $arSoportePagoDetalle->setHorasNocturnas(0);
            $arSoportePagoDetalle->setHorasFestivasDiurnas(0);
            $arSoportePagoDetalle->setHorasFestivasNocturnas(0);
            $arSoportePagoDetalle->setHorasRecargoNocturno($arrHoras['horasNocturnas']);
            $arSoportePagoDetalle->setHorasRecargoFestivoDiurno($arrHoras['horasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasRecargoFestivoNocturno($arrHoras['horasFestivasNocturnas']);
        } else {
            $arSoportePagoDetalle->setRetiro($arTurno->getRetiro());
            $arSoportePagoDetalle->setDias($intDias);
            $arSoportePagoDetalle->setHoras($arTurno->getHorasNomina());
            $arSoportePagoDetalle->setHorasDiurnas($arrHoras['horasDiurnas']);
            $arSoportePagoDetalle->setHorasNocturnas($arrHoras['horasNocturnas']);
            $arSoportePagoDetalle->setHorasFestivasDiurnas($arrHoras['horasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasFestivasNocturnas($arrHoras['horasFestivasNocturnas']);
        }

        $arSoportePagoDetalle->setHorasExtrasOrdinariasDiurnas($arrHoras['horasExtrasDiurnas']);
        $arSoportePagoDetalle->setHorasExtrasOrdinariasNocturnas($arrHoras['horasExtrasNocturnas']);
        $arSoportePagoDetalle->setHorasExtrasFestivasDiurnas($arrHoras['horasExtrasFestivasDiurnas']);
        $arSoportePagoDetalle->setHorasExtrasFestivasNocturnas($arrHoras['horasExtrasFestivasNocturnas']);
        $arSoportePagoDetalle->setHorasDescanso($arrHoras['horasDescanso']);
        $arSoportePagoDetalle->setHorasNovedad($arrHoras['horasNovedad']);
        
        if($strTurnoFijoNomina) {
            $arSoportePagoDetalle->setHorasDiurnas($arrHoras['horasDiurnas'] + $arrHoras['horasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasFestivasDiurnas(0);
            if($dateFecha->format('d') == 31 && $arSoportePagoPeriodo->getPagarDia31() == false) {
                $arSoportePagoDetalle->setHorasDiurnas(0);
                $arSoportePagoDetalle->setHorasFestivasDiurnas(0);                
            }
        }
        if($codigoProgramacionDetalle) {
            $arSoportePagoDetalle->setProgramacionDetalleRel($arProgramacionDetalle);
            $arSoportePagoDetalle->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());            
        }
        $arSoportePagoDetalle->setFestivo($boolFestivo);
        $em->persist($arSoportePagoDetalle);

        if($arrHoras1) {
            $arSoportePagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
            $arSoportePagoDetalle->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
            $arSoportePagoDetalle->setSoportePagoRel($arSoportePago);
            $arSoportePagoDetalle->setAnio($arSoportePago->getAnio());
            $arSoportePagoDetalle->setMes($arSoportePago->getMes());
            $arSoportePagoDetalle->setRecursoRel($arSoportePago->getRecursoRel());
            $arSoportePagoDetalle->setFecha($dateFecha2);
            $arSoportePagoDetalle->setTurnoRel($arTurno);
            $arSoportePagoDetalle->setDescanso($arTurno->getDescanso());
            $arSoportePagoDetalle->setNovedad(0);
            if($dateFecha->format('d') == 31 && $arSoportePagoPeriodo->getPagarDia31() == false) {
                $arSoportePagoDetalle->setDias(0);
                $arSoportePagoDetalle->setHoras(0);
                $arSoportePagoDetalle->setHorasDiurnas(0);
                $arSoportePagoDetalle->setHorasNocturnas(0);
                $arSoportePagoDetalle->setHorasFestivasDiurnas(0);
                $arSoportePagoDetalle->setHorasFestivasNocturnas(0);
                $arSoportePagoDetalle->setHorasRecargoNocturno($arrHoras1['horasNocturnas']);
                $arSoportePagoDetalle->setHorasRecargoFestivoDiurno($arrHoras1['horasFestivasDiurnas']);
                $arSoportePagoDetalle->setHorasRecargoFestivoNocturno($arrHoras1['horasFestivasNocturnas']);
            } else {
                $arSoportePagoDetalle->setDias(0);
                $arSoportePagoDetalle->setHoras(0);
                $arSoportePagoDetalle->setHorasDiurnas($arrHoras1['horasDiurnas']);
                $arSoportePagoDetalle->setHorasNocturnas($arrHoras1['horasNocturnas']);
                $arSoportePagoDetalle->setHorasFestivasDiurnas($arrHoras1['horasFestivasDiurnas']);
                $arSoportePagoDetalle->setHorasFestivasNocturnas($arrHoras1['horasFestivasNocturnas']);
            }
            $arSoportePagoDetalle->setHorasExtrasOrdinariasDiurnas($arrHoras1['horasExtrasDiurnas']);
            $arSoportePagoDetalle->setHorasExtrasOrdinariasNocturnas($arrHoras1['horasExtrasNocturnas']);
            $arSoportePagoDetalle->setHorasExtrasFestivasDiurnas($arrHoras1['horasExtrasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasExtrasFestivasNocturnas($arrHoras1['horasExtrasFestivasNocturnas']);
            $arSoportePagoDetalle->setHorasDescanso($arrHoras1['horasDescanso']);
            $arSoportePagoDetalle->setHorasNovedad($arrHoras1['horasNovedad']);
            if($codigoProgramacionDetalle) {
                $arSoportePagoDetalle->setProgramacionDetalleRel($arProgramacionDetalle);
                $arSoportePagoDetalle->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());                
            }
            $arSoportePagoDetalle->setFestivo($boolFestivo2);
            $em->persist($arSoportePagoDetalle);
        }

        return $horasTotales;
    }

    private function turnoHoras($intHoraInicio, $intMinutoInicio, $intHoraFinal, $boolFestivo, $intHoras, $boolNovedad = 0, $boolDescanso = 0) {
        if($boolNovedad == 0) {
            $intHorasNocturnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 0, 6);
            $intHorasExtrasNocturnas = 0;
            $intTotalHoras = $intHorasNocturnas + $intHoras;
            if($intTotalHoras > 8) {
                $intHorasJornada = 8 - $intHoras;
                if($intHorasJornada >= 1) {
                    $intHorasNocturnasReales = $intHorasNocturnas - $intHorasJornada;
                    $intHorasNocturnas = $intHorasNocturnas - $intHorasNocturnasReales;
                    $intHorasExtrasNocturnas = $intHorasNocturnasReales;
                } else {
                    $intHorasExtrasNocturnas = $intHorasNocturnas;
                    $intHorasNocturnas = 0;
                }
            }

            $intHorasDiurnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 6, 22);
            $intHorasExtrasDiurnas = 0;
            $intTotalHoras = $intHoras + $intHorasNocturnas + $intHorasExtrasNocturnas + $intHorasDiurnas;
            if($intTotalHoras > 8) {
                $intHorasJornada = 8 - ($intHoras + $intHorasNocturnas + $intHorasExtrasNocturnas);
                if($intHorasJornada > 1) {
                    $intHorasDiurnasReales = $intHorasDiurnas - $intHorasJornada;
                    $intHorasDiurnas = $intHorasDiurnas - $intHorasDiurnasReales;
                    $intHorasExtrasDiurnas = $intHorasDiurnasReales;
                } else {
                    $intHorasExtrasDiurnas = $intHorasDiurnas;
                    $intHorasDiurnas = 0;
                }
            }

            $intHorasNocturnasNoche = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 22, 24);
            $intHorasExtrasNocturnasNoche = 0;
            $intTotalHoras = $intHorasDiurnas + $intHorasExtrasDiurnas + $intHorasNocturnas + $intHorasNocturnasNoche;
            if($intTotalHoras > 8) {
                $intHorasJornada = 8 - ($intHorasNocturnas + $intHorasDiurnas + $intHorasExtrasDiurnas);
                if($intHorasJornada > 1) {
                    $intHorasNocturnasNocheReales = $intHorasNocturnasNoche - $intHorasJornada;
                    $intHorasNocturnasNoche = $intHorasNocturnasNoche - $intHorasNocturnasNocheReales;
                    $intHorasExtrasNocturnasNoche = $intHorasNocturnasNocheReales;
                } else {
                    $intHorasExtrasNocturnasNoche = $intHorasNocturnasNoche;
                    $intHorasNocturnasNoche = 0;
                }
            }
            $intHorasNocturnas += $intHorasNocturnasNoche;
            $intHorasExtrasNocturnas += $intHorasExtrasNocturnasNoche;

            $intHorasFestivasDiurnas = 0;
            $intHorasFestivasNocturnas = 0;
            $intHorasExtrasFestivasDiurnas = 0;
            $intHorasExtrasFestivasNocturnas = 0;
            if($boolFestivo == 1) {
                $intHorasFestivasDiurnas = $intHorasDiurnas;
                $intHorasDiurnas = 0;
                $intHorasFestivasNocturnas = $intHorasNocturnas;
                $intHorasNocturnas = 0;
                $intHorasExtrasFestivasDiurnas = $intHorasExtrasDiurnas;
                $intHorasExtrasDiurnas = 0;
                $intHorasExtrasFestivasNocturnas = $intHorasExtrasNocturnas;
                $intHorasExtrasNocturnas = 0;
            }
            $intTotalHoras = $intHorasDiurnas+$intHorasNocturnas+$intHorasExtrasDiurnas+$intHorasExtrasNocturnas+$intHorasFestivasDiurnas+$intHorasFestivasNocturnas+$intHorasExtrasFestivasDiurnas+$intHorasExtrasFestivasNocturnas;
            if($boolDescanso == 1) {
                $arrHoras = array(
                    'horasDescanso' => $intTotalHoras,
                    'horasNovedad' => 0,
                    'horasDiurnas' => 0,
                    'horasNocturnas' => 0,
                    'horasExtrasDiurnas' => 0,
                    'horasExtrasNocturnas' => 0,
                    'horasFestivasDiurnas' => 0,
                    'horasFestivasNocturnas' => 0,
                    'horasExtrasFestivasDiurnas' => 0,
                    'horasExtrasFestivasNocturnas' => 0,
                    'horas' => $intTotalHoras);
            } else {
                $arrHoras = array(
                    'horasDescanso' => 0,
                    'horasNovedad' => 0,
                    'horasDiurnas' => $intHorasDiurnas,
                    'horasNocturnas' => $intHorasNocturnas,
                    'horasExtrasDiurnas' => $intHorasExtrasDiurnas,
                    'horasExtrasNocturnas' => $intHorasExtrasNocturnas,
                    'horasFestivasDiurnas' => $intHorasFestivasDiurnas,
                    'horasFestivasNocturnas' => $intHorasFestivasNocturnas,
                    'horasExtrasFestivasDiurnas' => $intHorasExtrasFestivasDiurnas,
                    'horasExtrasFestivasNocturnas' => $intHorasExtrasFestivasNocturnas,
                    'horas' => $intTotalHoras);
            }

        } else {
            $arrHoras = array(
                'horasDescanso' => 0,
                'horasNovedad' => 8,
                'horasDiurnas' => 0,
                'horasNocturnas' => 0,
                'horasExtrasDiurnas' => 0,
                'horasExtrasNocturnas' => 0,
                'horasFestivasDiurnas' => 0,
                'horasFestivasNocturnas' => 0,
                'horasExtrasFestivasDiurnas' => 0,
                'horasExtrasFestivasNocturnas' => 0,
                'horas' => 0);
        }

        return $arrHoras;
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

    private function calcularTiempo($intInicial, $intFinal, $intParametroInicio, $intParametroFinal) {
        $intHoras = 0;
        $intHoraIniciaTemporal = 0;
        $intHoraTerminaTemporal = 0;
        if($intInicial < $intParametroInicio) {
            $intHoraIniciaTemporal = $intParametroInicio;
        } else {
            $intHoraIniciaTemporal = $intInicial;
        }
        if($intFinal > $intParametroFinal) {
            if($intInicial > $intParametroFinal) {
                $intHoraTerminaTemporal = $intInicial;
            } else {
                $intHoraTerminaTemporal = $intParametroFinal;
            }
        } else {
            if($intFinal > $intParametroInicio) {
                $intHoraTerminaTemporal = $intFinal;
            } else {
                $intHoraTerminaTemporal = $intParametroInicio;
            }
        }
        $intHoras = $intHoraTerminaTemporal - $intHoraIniciaTemporal;
        return $intHoras;
    }

    public function compensar($codigoSoportePago = "", $codigoSoportePagoPeriodo = "", $tipo = 1) {
        $em = $this->getEntityManager();
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
        $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);
        $descanso = $arSoportePagoPeriodo->getDiaDescansoCompensacion();
        $diasPeriodo = $arSoportePagoPeriodo->getDiasPeriodo();
        $horasPeriodo =  $diasPeriodo * 8;
        $horasDescanso = $descanso * 8;
        $horasTope = $horasPeriodo - $horasDescanso;
        //Semanas para ausentismo y descontar descansos
        $arrSemanas = array();
        $arrSemanasCompensacion = array();
        $arrDomingos = array();
        $dateFechaDesde = $arSoportePagoPeriodo->getFechaDesde();
        $dateFechaHasta = $arSoportePagoPeriodo->getFechaHasta();
        $intDiaInicial = $dateFechaDesde->format('j');
        $intDiaFinal = $dateFechaHasta->format('j');
        $diaInicialSemana = $intDiaInicial;
        for($i = $intDiaInicial; $i <= $intDiaFinal; $i++) {
            $strFecha = $dateFechaDesde->format('Y/m/') . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $dateFecha->format('N');
            if($diaSemana == 7) {
                $arrSemanas[] = array('diaInicial' => $diaInicialSemana, 'diaFinal' => $i, 'fechaInicial' => $dateFechaDesde->format('Y/m/') . $diaInicialSemana, 'fechaFinal' => $dateFechaDesde->format('Y/m/') . $i);
                $diaInicialSemana = $i + 1;
                $arrDomingos[] = array('domingo' => $dateFecha);
            }
        }
        $arrSemanasCompensacion[] = array('diaInicial' => 1, 'diaFinal' => 7, 'fechaInicial' => $dateFechaDesde->format('Y/m/') . 1, 'fechaFinal' => $dateFechaDesde->format('Y/m/') . 7);
        $arrSemanasCompensacion[] = array('diaInicial' => 8, 'diaFinal' => 15, 'fechaInicial' => $dateFechaDesde->format('Y/m/') . 8, 'fechaFinal' => $dateFechaDesde->format('Y/m/') . 15);
        $arrSemanasCompensacion[] = array('diaInicial' => 16, 'diaFinal' => 22, 'fechaInicial' => $dateFechaDesde->format('Y/m/') . 16, 'fechaFinal' => $dateFechaDesde->format('Y/m/') . 22);
        $arrSemanasCompensacion[] = array('diaInicial' => 23, 'diaFinal' => 30, 'fechaInicial' => $dateFechaDesde->format('Y/m/') . 23, 'fechaFinal' => $dateFechaDesde->format('Y/m/') . 30);

        $arSoportePagos = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        if($codigoSoportePago != "") {
            $arSoportePagos = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPk' => $codigoSoportePago));
        } else {
            $arSoportePagos = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo));
        }

        foreach ($arSoportePagos as $arSoportePago) {
            if($arSoportePago->getTurnoFijo() == 0) {
                if($tipo == 1) {
                    $diasDescansoSoportePago = $descanso;
                    //Descansos de compensacion
                    $descansoCompensacion = $descanso;
                    $novedadesIngresoRetiro = $arSoportePago->getIngreso() + $arSoportePago->getRetiro();
                    if($novedadesIngresoRetiro > 0) {
                        $descansoDescontar = 0;
                        foreach ($arrSemanasCompensacion as $arrSemana) {
                           $numeroIngresoRetiro =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoDetalle')->numeroIngresoRetiros($arSoportePago->getCodigoSoportePagoPk(), $arrSemana['fechaInicial'], $arrSemana['fechaFinal']);
                           if($numeroIngresoRetiro > 0) {
                               $descansoDescontar++;
                           }
                        }
                        if($descansoDescontar <= $descansoCompensacion) {
                            $descansoCompensacion = $descansoCompensacion - $descansoDescontar;
                        } else {
                            $descansoCompensacion = 0;
                        }
                    }
                    //Descanso por sln
                    $novedadesAfectaDescanso = $arSoportePago->getLicenciaNoRemunerada();
                    if($novedadesAfectaDescanso > 0) {
                        $descansoDescontar = 0;
                        foreach ($arrSemanas as $arrSemana) {
                           $numeroLicenciasNoRemuneradas =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoDetalle')->numeroLicenciasNoRemunerada($arSoportePago->getCodigoSoportePagoPk(), $arrSemana['fechaInicial'], $arrSemana['fechaFinal']);
                           if($numeroLicenciasNoRemuneradas > 0) {
                               $descansoDescontar++;
                           }
                        }
                        if($descansoDescontar <= $diasDescansoSoportePago) {
                            $diasDescansoSoportePago = $diasDescansoSoportePago - $descansoDescontar;
                        } else {
                            $diasDescansoSoportePago = 0;
                        }
                    }
                    if($diasDescansoSoportePago > 0) {
                        $domingosPagados = $this->domingosPagados($arrDomingos, $arSoportePago->getCodigoSoportePagoPk());
                        if($domingosPagados <= $diasDescansoSoportePago) {
                            $diasDescansoSoportePago = $diasDescansoSoportePago - $domingosPagados;
                        } else {
                            $diasDescansoSoportePago = 0;
                        }
                    }
                    //$diasPeriodoCompensar = $diasPeriodo - ($arSoportePago->getIngreso()+$arSoportePago->getRetiro());
                    $diasPeriodoCompensar = $diasPeriodo;
                    $horasPeriodo =  $diasPeriodoCompensar * 8;
                    //$horasPeriodo =  $diasPeriodo * 8;
                    $horasDescansoSoportePago = $diasDescansoSoportePago * 8;
                    //$horasTopeSoportePago = $horasPeriodo - ($descansoCompensacion * 8);
                    $horasTopeSoportePago = $horasPeriodo - ($descanso * 8);
                    $horasDia = $arSoportePago->getHorasDiurnasReales();
                    $horasNoche = $arSoportePago->getHorasNocturnasReales();
                    $horasFestivasDia = $arSoportePago->getHorasFestivasDiurnasReales();
                    $horasFestivasNoche = $arSoportePago->getHorasFestivasNocturnasReales();
                    $horasExtraDia = $arSoportePago->getHorasExtrasOrdinariasDiurnasReales();
                    $horasExtraNoche = $arSoportePago->getHorasExtrasOrdinariasNocturnasReales();
                    $horasExtraFestivasDia = $arSoportePago->getHorasExtrasFestivasDiurnasReales();
                    $horasExtraFestivasNoche = $arSoportePago->getHorasExtrasFestivasNocturnasReales();
                    $totalHoras = $horasDia + $horasNoche + $horasFestivasDia + $horasFestivasNoche;
                    $horasPorCompensar = $horasTopeSoportePago - $totalHoras;
                    if($horasPorCompensar > 0) {
                        $totalExtras = $horasExtraDia + $horasExtraNoche + $horasExtraFestivasDia + $horasExtraFestivasNoche;
                        if($horasPorCompensar > $totalExtras) {
                            $horasPorCompensar = $totalExtras;
                        }
                        $porExtraDiurna = 0;
                        $porExtraNocturna = 0;
                        $porExtraFestivaDiurna = 0;
                        $porExtraFestivaNocturna = 0;
                        if($totalExtras > 0) {
                            $porExtraDiurna = $horasExtraDia / $totalExtras;
                            $porExtraNocturna = $horasExtraNoche / $totalExtras;
                            $porExtraFestivaDiurna = $horasExtraFestivasDia / $totalExtras;
                            $porExtraFestivaNocturna = $horasExtraFestivasNoche / $totalExtras;
                        }

                        $horasCompensarDia = round($porExtraDiurna * $horasPorCompensar);
                        $horasCompensarNoche = round($porExtraNocturna * $horasPorCompensar);
                        $horasCompensadas = $horasCompensarDia + $horasCompensarNoche;
                        if($horasCompensadas > $horasPorCompensar) {
                            $horasCompensarNoche -= 1;
                        }
                        $horasCompensarFestivaDia = round($porExtraFestivaDiurna * $horasPorCompensar);
                        $horasCompensadas = $horasCompensarDia + $horasCompensarNoche+$horasCompensarFestivaDia;
                        if($horasCompensadas > $horasPorCompensar) {
                            $horasCompensarFestivaDia -= 1;
                        }
                        $horasCompensarFestivaNoche = round($porExtraFestivaNocturna * $horasPorCompensar);
                        $horasCompensadas = $horasCompensarDia + $horasCompensarNoche+$horasCompensarFestivaDia+$horasCompensarFestivaNoche;
                        if($horasCompensadas > $horasPorCompensar) {
                            $horasCompensarFestivaNoche -= 1;
                        }
                        //Para tema de redondeo
                        $horasCompensadas = $horasCompensarDia + $horasCompensarNoche + $horasCompensarFestivaDia + $horasCompensarFestivaNoche;
                        if($horasCompensadas < $horasPorCompensar) {
                            if($horasExtraFestivasNoche > 0) {
                                $horasCompensarFestivaNoche += 1;
                            } else {
                                $horasCompensarFestivaDia += 1;
                            }
                        }
                        $horasDia += $horasCompensarDia;
                        $horasNoche += $horasCompensarNoche;
                        $horasFestivasDia += $horasCompensarFestivaDia;
                        $horasFestivasNoche += $horasCompensarFestivaNoche;
                        $horasExtraDia -= $horasCompensarDia;
                        $horasExtraNoche -= $horasCompensarNoche;
                        $horasExtraFestivasDia -= $horasCompensarFestivaDia;
                        $horasExtraFestivasNoche -= $horasCompensarFestivaNoche;
                    } else {
                        $horasPorCompensar = $horasPorCompensar * -1;
                        $totalOrdinarias = $horasDia + $horasNoche + $horasFestivasDia + $horasFestivasNoche;
                        if($horasPorCompensar > $totalOrdinarias) {
                            $horasPorCompensar = $totalOrdinarias;
                        }
                        $porDiurna = 0;
                        $porNocturna = 0;
                        $porFestivaDiurna = 0;
                        $porFestivaNocturna = 0;
                        if($totalOrdinarias > 0) {
                            $porDiurna = $horasDia / $totalOrdinarias;
                            $porNocturna = $horasNoche / $totalOrdinarias;
                            $porFestivaDiurna = $horasFestivasDia / $totalOrdinarias;
                            $porFestivaNocturna = $horasFestivasNoche / $totalOrdinarias;
                        }

                        $horasCompensarDia = round($porDiurna * $horasPorCompensar);
                        $horasCompensarNoche = round($porNocturna * $horasPorCompensar);
                        $horasCompensadas = $horasCompensarDia + $horasCompensarNoche;
                        if($horasCompensadas > $horasPorCompensar) {
                            $horasCompensarNoche -= 1;
                        }
                        $horasCompensarFestivaDia = round($porFestivaDiurna * $horasPorCompensar);
                        $horasCompensadas = $horasCompensarDia + $horasCompensarNoche+$horasCompensarFestivaDia;
                        if($horasCompensadas > $horasPorCompensar) {
                            $horasCompensarFestivaDia -= 1;
                        }
                        $horasCompensarFestivaNoche = round($porFestivaNocturna * $horasPorCompensar);
                        $horasCompensadas = $horasCompensarDia + $horasCompensarNoche+$horasCompensarFestivaDia+$horasCompensarFestivaNoche;
                        if($horasCompensadas > $horasPorCompensar) {
                            $horasCompensarFestivaNoche -= 1;
                        }
                        //Para tema de redondeo
                        $horasCompensadas = $horasCompensarDia + $horasCompensarNoche + $horasCompensarFestivaDia + $horasCompensarFestivaNoche;
                        if($horasCompensadas < $horasPorCompensar) {
                            $horasCompensarFestivaNoche += 1;
                        }
                        $horasExtraDia += $horasCompensarDia;
                        $horasExtraNoche += $horasCompensarNoche;
                        $horasExtraFestivasDia += $horasCompensarFestivaDia;
                        $horasExtraFestivasNoche += $horasCompensarFestivaNoche;
                        $horasDia -= $horasCompensarDia;
                        $horasNoche -= $horasCompensarNoche;
                        $horasFestivasDia -= $horasCompensarFestivaDia;
                        $horasFestivasNoche -= $horasCompensarFestivaNoche;
                    }

                    $arSoportePagoAct = new \Brasa\TurnoBundle\Entity\TurSoportePago();
                    $arSoportePagoAct = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arSoportePago->getCodigoSoportePagoPk());
                    $arSoportePagoAct->setHorasDiurnas($horasDia);
                    $arSoportePagoAct->setHorasNocturnas($horasNoche);
                    $arSoportePagoAct->setHorasFestivasDiurnas($horasFestivasDia);
                    $arSoportePagoAct->setHorasFestivasNocturnas($horasFestivasNoche);
                    $arSoportePagoAct->setHorasExtrasOrdinariasDiurnas($horasExtraDia);
                    $arSoportePagoAct->setHorasExtrasOrdinariasNocturnas($horasExtraNoche);
                    $arSoportePagoAct->setHorasExtrasFestivasDiurnas($horasExtraFestivasDia);
                    $arSoportePagoAct->setHorasExtrasFestivasNocturnas($horasExtraFestivasNoche);
                    $arSoportePagoAct->setDiasPeriodoCompensar($diasPeriodoCompensar);
                    $arSoportePagoAct->setDiasPeriodoDescansoCompensar($descansoCompensacion);
                    $horasDescansoRecurso = $horasDescansoSoportePago;
                    if($diasPeriodo == $arSoportePago->getNovedad()) {
                       $horasDescansoRecurso = 0;
                    }
                    $arSoportePagoAct->setHorasDescanso($horasDescansoRecurso);
                    $horas = $horasDia + $horasNoche + $horasFestivasDia + $horasFestivasNoche + $horasDescansoRecurso;
                    $arSoportePagoAct->setHoras($horas);
                    $em->persist($arSoportePagoAct);                    
                }
                if($tipo == 2) {
                    $diasDescansoSoportePago = $descanso;
                    //Descansos de compensacion
                    $descansoCompensacion = $descanso;
                    $novedadesIngresoRetiro = $arSoportePago->getIngreso() + $arSoportePago->getRetiro();
                    if($novedadesIngresoRetiro > 0) {
                        $descansoDescontar = 0;
                        foreach ($arrSemanasCompensacion as $arrSemana) {
                           $numeroIngresoRetiro =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoDetalle')->numeroIngresoRetiros($arSoportePago->getCodigoSoportePagoPk(), $arrSemana['fechaInicial'], $arrSemana['fechaFinal']);
                           if($numeroIngresoRetiro > 0) {
                               $descansoDescontar++;
                           }
                        }
                        if($descansoDescontar <= $descansoCompensacion) {
                            $descansoCompensacion = $descansoCompensacion - $descansoDescontar;
                        } else {
                            $descansoCompensacion = 0;
                        }
                    }
                    //Descanso por sln
                    $novedadesAfectaDescanso = $arSoportePago->getLicenciaNoRemunerada();
                    if($novedadesAfectaDescanso > 0) {
                        $descansoDescontar = 0;
                        foreach ($arrSemanas as $arrSemana) {
                           $numeroLicenciasNoRemuneradas =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoDetalle')->numeroLicenciasNoRemunerada($arSoportePago->getCodigoSoportePagoPk(), $arrSemana['fechaInicial'], $arrSemana['fechaFinal']);
                           if($numeroLicenciasNoRemuneradas > 0) {
                               $descansoDescontar++;
                           }
                        }
                        if($descansoDescontar <= $diasDescansoSoportePago) {
                            $diasDescansoSoportePago = $diasDescansoSoportePago - $descansoDescontar;
                        } else {
                            $diasDescansoSoportePago = 0;
                        }
                    }
                    if($diasDescansoSoportePago > 0) {
                        $domingosPagados = $this->domingosPagados($arrDomingos, $arSoportePago->getCodigoSoportePagoPk());
                        if($domingosPagados <= $diasDescansoSoportePago) {
                            $diasDescansoSoportePago = $diasDescansoSoportePago - $domingosPagados;
                        } else {
                            $diasDescansoSoportePago = 0;
                        }
                    }                    
                    $diasPeriodoCompensar = $diasPeriodo;
                    $horasPeriodo =  $diasPeriodoCompensar * 8;                    
                    $horasDescansoSoportePago = $diasDescansoSoportePago * 8;                    
                    $horasTopeSoportePago = $horasPeriodo - ($descanso * 8);
                    $descansoAuxilioTransporte = 0;
                    $horasDia = 0;
                    $horasNoche = 0;
                    $horasFestivasDia = 0;
                    $horasFestivasNoche = 0;
                    $horasExtraDia = 0;
                    $horasExtraNoche = 0;
                    $horasExtraFestivasDia = 0;
                    $horasExtraFestivasNoche = 0;   
                    $horasRecargoNocturno = 0;
                    $totalHoras = $horasDia + $horasNoche + $horasFestivasDia + $horasFestivasNoche;                    
                    $horasTurno = 0;
                    $auxilio = 0;
                    $arSoportePagoDetalles = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
                    $arSoportePagoDetalles = $em->getRepository('BrasaTurnoBundle:TurSoportePagoDetalle')->findBy(array('codigoSoportePagoFk' => $arSoportePago->getCodigoSoportePagoPk()));                    
                    foreach ($arSoportePagoDetalles as $arSoportePagoDetalle) {                        
                        if($arSoportePagoDetalle->getFestivo() == 0) {
                            $horasTurno += $arSoportePagoDetalle->getHoras();
                            $horasDia += $arSoportePagoDetalle->getHorasDiurnas() + $arSoportePagoDetalle->getHorasFestivasDiurnas() + $arSoportePagoDetalle->getHorasExtrasOrdinariasDiurnas() + $arSoportePagoDetalle->getHorasExtrasFestivasDiurnas();
                            $horasNoche += $arSoportePagoDetalle->getHorasNocturnas() + $arSoportePagoDetalle->getHorasFestivasNocturnas() + $arSoportePagoDetalle->getHorasExtrasOrdinariasNocturnas() + $arSoportePagoDetalle->getHorasExtrasFestivasNocturnas();
                            $horasRecargoNocturno += $arSoportePagoDetalle->getHorasNocturnas();
                        } else {
                            $horasFestivasDia +=  $arSoportePagoDetalle->getHorasDiurnas() + $arSoportePagoDetalle->getHorasFestivasDiurnas();
                            $horasFestivasNoche +=  $arSoportePagoDetalle->getHorasNocturnas() + $arSoportePagoDetalle->getHorasFestivasNocturnas();                            
                            $horasExtraFestivasDia +=  $arSoportePagoDetalle->getHorasExtrasOrdinariasDiurnas() + $arSoportePagoDetalle->getHorasExtrasFestivasDiurnas();
                            $horasExtraFestivasNoche +=  $arSoportePagoDetalle->getHorasExtrasOrdinariasNocturnas() + $arSoportePagoDetalle->getHorasExtrasFestivasNocturnas();
                        }
                        $descansoAuxilioTransporte += $arSoportePagoDetalle->getDescanso();
                    }
                    $horasExtra = 0;                
                    if($horasTurno > $horasTopeSoportePago) {
                        $horasExtrasDiurnasSobrantes = $horasTurno - $horasTopeSoportePago;
                        $horasExtra = $horasExtrasDiurnasSobrantes;  
                        $porDiurnas = ($horasDia*100) / $horasTurno;
                        $porNocturna = ($horasNoche*100) / $horasTurno;
                        $horasExtraDia = round(($horasExtra * $porDiurnas)/100);
                        $horasExtraNoche = round(($horasExtra * $porNocturna)/100);                          
                    } else {
                        $horasTopeSoportePago = $horasTurno;
                    }
                     
                    $totalExtra = $horasExtra + $horasExtraFestivasDia + $horasExtraFestivasNoche;
                    if($totalExtra > 26) {
                        $diferencia = $totalExtra - 26;
                        $horasExtra = $horasExtra - $diferencia; 
                        $porDiurnas = ($horasDia*100) / $horasTurno;
                        $porNocturna = ($horasNoche*100) / $horasTurno;
                        $horasExtraDia = round(($horasExtra * $porDiurnas)/100);
                        $horasExtraNoche = round(($horasExtra * $porNocturna)/100);                                                                                                
                        $horasDiferenciaDia = round(($diferencia * $porDiurnas)/100);
                        $horasDiferenciaNoche = round(($diferencia * $porNocturna)/100);
                        
                        $salario = $arSoportePago->getVrSalario();
                        $vrDia = $salario / 30;
                        $vrHora = $vrDia / 8;
                        $auxilio = ($vrHora * 1.25) * $horasDiferenciaDia;
                        $auxilio += ($vrHora * 1.75) * $horasDiferenciaNoche;
                        $auxilio = round($auxilio);
                    }
                    $horasDia = $horasTopeSoportePago;
                    $horasNoche = 0; 
                    
                    $arSoportePagoAct = new \Brasa\TurnoBundle\Entity\TurSoportePago();                        
                    $arSoportePagoAct = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arSoportePago->getCodigoSoportePagoPk());
                    $arSoportePagoAct->setHorasDiurnas($horasDia);
                    $arSoportePagoAct->setHorasNocturnas($horasNoche);
                    $arSoportePagoAct->setHorasFestivasDiurnas($horasFestivasDia);
                    $arSoportePagoAct->setHorasFestivasNocturnas($horasFestivasNoche);
                    $arSoportePagoAct->setHorasExtrasOrdinariasDiurnas($horasExtraDia);
                    $arSoportePagoAct->setHorasExtrasOrdinariasNocturnas($horasExtraNoche);
                    $arSoportePagoAct->setHorasExtrasFestivasDiurnas($horasExtraFestivasDia);
                    $arSoportePagoAct->setHorasExtrasFestivasNocturnas($horasExtraFestivasNoche);
                    $arSoportePagoAct->setHorasRecargoNocturno($horasRecargoNocturno);
                    $arSoportePagoAct->setDiasPeriodoCompensar($diasPeriodoCompensar);
                    $arSoportePagoAct->setDiasPeriodoDescansoCompensar($descansoCompensacion);
                    $arSoportePagoAct->setVrAjusteCompensacion($auxilio);
                    $diasAuxilioTransporte = $arSoportePagoAct->getDiasTransporte() - $descansoAuxilioTransporte;
                    $arSoportePagoAct->setDiasTransporte($diasAuxilioTransporte);
                    $horasDescansoRecurso = $horasDescansoSoportePago;
                    if($diasPeriodo == $arSoportePago->getNovedad()) {
                       $horasDescansoRecurso = 0;
                    }
                    $arSoportePagoAct->setHorasDescanso($horasDescansoRecurso);
                    $horas = $horasDia + $horasDescansoRecurso;
                    $arSoportePagoAct->setHoras($horas);
                    $em->persist($arSoportePagoAct);                    
                }
            } else {
                $arSoportePagoAct = new \Brasa\TurnoBundle\Entity\TurSoportePago();
                $arSoportePagoAct = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arSoportePago->getCodigoSoportePagoPk());
                $horasDia = $arSoportePago->getHorasDiurnasReales();
                $horasNoche = $arSoportePago->getHorasNocturnasReales();
                $horasFestivasDia = $arSoportePago->getHorasFestivasDiurnasReales();
                $horasFestivasNoche = $arSoportePago->getHorasFestivasNocturnasReales();
                $horasExtraDia = $arSoportePago->getHorasExtrasOrdinariasDiurnasReales();
                $horasExtraNoche = $arSoportePago->getHorasExtrasOrdinariasNocturnasReales();
                $horasExtraFestivasDia = $arSoportePago->getHorasExtrasFestivasDiurnasReales();
                $horasExtraFestivasNoche = $arSoportePago->getHorasExtrasFestivasNocturnasReales();

                $arSoportePagoAct->setHorasDiurnas($horasDia);
                $arSoportePagoAct->setHorasNocturnas($horasNoche);
                $arSoportePagoAct->setHorasFestivasDiurnas($horasFestivasDia);
                $arSoportePagoAct->setHorasFestivasNocturnas($horasFestivasNoche);
                $arSoportePagoAct->setHorasExtrasOrdinariasDiurnas($horasExtraDia);
                $arSoportePagoAct->setHorasExtrasOrdinariasNocturnas($horasExtraNoche);
                $arSoportePagoAct->setHorasExtrasFestivasDiurnas($horasExtraFestivasDia);
                $arSoportePagoAct->setHorasExtrasFestivasNocturnas($horasExtraFestivasNoche);
                $arSoportePagoAct->setHorasDescanso(0);
                $horas = $horasDia + $horasNoche + $horasFestivasDia + $horasFestivasNoche;
                $arSoportePagoAct->setHoras($horas);
                $em->persist($arSoportePagoAct);
            }
        }
        $em->flush();
    }

    private function domingosPagados($arrDomingos, $codigoSoportePago) {
        $em = $this->getEntityManager();
        $descansosPagados = 0;
        foreach ($arrDomingos as $arrDomingo) {
            $descansoPagado = false;
            $arSoportePagoDetalles = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
            $arSoportePagoDetalles = $em->getRepository('BrasaTurnoBundle:TurSoportePagoDetalle')->findBy(array('codigoSoportePagoFk' => $codigoSoportePago, 'fecha' => $arrDomingo['domingo']));
            foreach ($arSoportePagoDetalles as $arSoportePagoDetalle) {
                if($arSoportePagoDetalle->getIncapacidad() || $arSoportePagoDetalle->getVacacion() || $arSoportePagoDetalle->getIngreso() || $arSoportePagoDetalle->getRetiro()) {
                    $descansoPagado = true;
                }
            }
            if($descansoPagado == true) {
                $descansosPagados++;
            }
        }
        return $descansosPagados;
    }

    public function generarProgramacion($arSoportePago, $strAnio, $strMes) {
        $em = $this->getEntityManager();
        $arrProgramacion = array();
        $dql   = "SELECT pd.dia1, pd.dia2, pd.dia3, pd.dia4, pd.dia5, pd.dia6, pd.dia7, pd.dia8, pd.dia9, pd.dia10, pd.dia11, pd.dia12, pd.dia13, pd.dia14, pd.dia15, pd.dia16, pd.dia17, pd.dia18, pd.dia19, pd.dia20, pd.dia21, pd.dia22, pd.dia23, pd.dia24, pd.dia25, pd.dia26, pd.dia27, pd.dia28, pd.dia29, pd.dia30, pd.dia31 FROM BrasaTurnoBundle:TurProgramacionDetalle pd WHERE pd.anio = " . $strAnio . " AND pd.mes = " . $strMes . " AND pd.codigoRecursoFk = " . $arSoportePago->getRecursoRel()->getCodigoRecursoPk();
        $query = $em->createQuery($dql);
        $arResultados = $query->getResult();
        $numeroProgramaciones = count($arResultados);

        foreach($arResultados as $arResultado) {
            for($j=1; $j<=31; $j++) {
                if($arResultado['dia'.$j]) {
                    if(isset($arrProgramacion[1][$j])){
                        if(!$arrProgramacion[1][$j]) {
                            $arrProgramacion[1][$j] = $arResultado['dia'.$j];
                        }
                    } else {
                         $arrProgramacion[1][$j] = $arResultado['dia'.$j];
                    }
                } else {
                    if(isset($arrProgramacion[1][$j])){
                        if(!$arrProgramacion[1][$j]) {
                            $arrProgramacion[1][$j] = null;
                        }
                    } else {
                        $arrProgramacion[1][$j] = null;
                    }

                }
            }

        }

        foreach ($arrProgramacion as $detalle) {
            $arSoportePagoProgramacion = new \Brasa\TurnoBundle\Entity\TurSoportePagoProgramacion();
            $arSoportePagoProgramacion->setCodigoSoportePagoPeriodoFk($arSoportePago->getSoportePagoPeriodoRel()->getCodigoSoportePagoPeriodoPk());
            $arSoportePagoProgramacion->setCodigoSoportePagoFk($arSoportePago->getCodigoSoportePagoPk());
            $arSoportePagoProgramacion->setCodigoRecursoFk($arSoportePago->getRecursoRel()->getCodigoRecursoPk());
            $arSoportePagoProgramacion->setAnio($strAnio);
            $arSoportePagoProgramacion->setMes($strMes);
            for($j=1; $j<=31; $j++) {
                if($j == 1) {
                    $arSoportePagoProgramacion->setDia1($detalle[$j]);
                }
                if($j == 2) {
                    $arSoportePagoProgramacion->setDia2($detalle[$j]);
                }
                if($j == 3) {
                    $arSoportePagoProgramacion->setDia3($detalle[$j]);
                }
                if($j == 4) {
                    $arSoportePagoProgramacion->setDia4($detalle[$j]);
                }
                if($j == 5) {
                    $arSoportePagoProgramacion->setDia5($detalle[$j]);
                }
                if($j == 6) {
                    $arSoportePagoProgramacion->setDia6($detalle[$j]);
                }
                if($j == 7) {
                    $arSoportePagoProgramacion->setDia7($detalle[$j]);
                }
                if($j == 8) {
                    $arSoportePagoProgramacion->setDia8($detalle[$j]);
                }
                if($j == 9) {
                    $arSoportePagoProgramacion->setDia9($detalle[$j]);
                }
                if($j == 10) {
                    $arSoportePagoProgramacion->setDia10($detalle[$j]);
                }
                if($j == 11) {
                    $arSoportePagoProgramacion->setDia11($detalle[$j]);
                }
                if($j == 12) {
                    $arSoportePagoProgramacion->setDia12($detalle[$j]);
                }
                if($j == 13) {
                    $arSoportePagoProgramacion->setDia13($detalle[$j]);
                }
                if($j == 14) {
                    $arSoportePagoProgramacion->setDia14($detalle[$j]);
                }
                if($j == 15) {
                    $arSoportePagoProgramacion->setDia15($detalle[$j]);
                }
                if($j == 16) {
                    $arSoportePagoProgramacion->setDia16($detalle[$j]);
                }
                if($j == 17) {
                    $arSoportePagoProgramacion->setDia17($detalle[$j]);
                }
                if($j == 18) {
                    $arSoportePagoProgramacion->setDia18($detalle[$j]);
                }
                if($j == 19) {
                    $arSoportePagoProgramacion->setDia19($detalle[$j]);
                }
                if($j == 20) {
                    $arSoportePagoProgramacion->setDia20($detalle[$j]);
                }
                if($j == 21) {
                    $arSoportePagoProgramacion->setDia21($detalle[$j]);
                }
                if($j == 22) {
                    $arSoportePagoProgramacion->setDia22($detalle[$j]);
                }
                if($j == 23) {
                    $arSoportePagoProgramacion->setDia23($detalle[$j]);
                }
                if($j == 24) {
                    $arSoportePagoProgramacion->setDia24($detalle[$j]);
                }
                if($j == 25) {
                    $arSoportePagoProgramacion->setDia25($detalle[$j]);
                }
                if($j == 26) {
                    $arSoportePagoProgramacion->setDia26($detalle[$j]);
                }
                if($j == 27) {
                    $arSoportePagoProgramacion->setDia27($detalle[$j]);
                }
                if($j == 28) {
                    $arSoportePagoProgramacion->setDia28($detalle[$j]);
                }
                if($j == 29) {
                    $arSoportePagoProgramacion->setDia29($detalle[$j]);
                }
                if($j == 30) {
                    $arSoportePagoProgramacion->setDia30($detalle[$j]);
                }
                if($j == 31) {
                    $arSoportePagoProgramacion->setDia31($detalle[$j]);
                }
            }
            $em->persist($arSoportePagoProgramacion);
        }
    }    

    public function generarProgramacionAlterna($codigoSoportePagoPeriodo) {
        $em = $this->getEntityManager();
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();        
        $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                        
        $arSoportePagoPeriodo->setAjusteDevengado(1);
        $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arSoportePagoPeriodo->getFechaDesde()->format('Y-m-').'01', $arSoportePagoPeriodo->getFechaHasta()->format('Y-m-').'31');                        
        
        $strSql = "DELETE FROM tur_programacion_alterna WHERE codigo_soporte_pago_periodo_fk = " . $codigoSoportePagoPeriodo;
        $em->getConnection()->executeQuery($strSql);      
        $strSql = "DELETE FROM tur_soporte_pago_detalle WHERE codigo_soporte_pago_periodo_fk = " . $codigoSoportePagoPeriodo;
        $em->getConnection()->executeQuery($strSql);        
        $indiceSecuencia = 0;
        $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportesPago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo));                                
        foreach ($arSoportesPago as $arSoportePago) {
            if($arSoportePago->getSecuencia()) {
                $arSecuencia = new \Brasa\TurnoBundle\Entity\TurSecuencia();
                $arSecuencia = $em->getRepository('BrasaTurnoBundle:TurSecuencia')->find($arSoportePago->getSecuencia());                
                $arSecuenciaDetalle = new \Brasa\TurnoBundle\Entity\TurSecuenciaDetalle();
                $arSecuenciaDetalle = $em->getRepository('BrasaTurnoBundle:TurSecuenciaDetalle')->findBy(array('codigoSecuenciaFk' => $arSoportePago->getSecuencia()));                                        

                $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->periodoDias($arSoportePago->getAnio(), $arSoportePago->getMes(), $arSoportePago->getCodigoRecursoFk());        
                $arProgramacionAlterna = new \Brasa\TurnoBundle\Entity\TurProgramacionAlterna();
                $arProgramacionAlterna->setSoportePagoPeriodoRel($arSoportePago->getSoportePagoPeriodoRel());
                $arProgramacionAlterna->setSoportePagoRel($arSoportePago);
                $arProgramacionAlterna->setAnio($arSoportePago->getAnio());
                $arProgramacionAlterna->setMes($arSoportePago->getMes());            
                $arProgramacionAlterna->setRecursoRel($arSoportePago->getRecursoRel());              
                for($i = 1; $i <= 31; $i++) {
                    $strTurnoDefinitivo = NULL;
                    foreach ($arProgramacionDetalles as $arProgramacionDetalle) {
                        $strTurno = $arProgramacionDetalle['dia'.$i];
                        if($strTurno != '') {
                            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();                        
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurno); 
                            if($arTurno) {
                                if($arTurno->getNovedad()) {
                                    $strTurnoDefinitivo = $strTurno;
                                }
                            }
                        }
                    }
                    if($arSoportePago->getSecuencia() == 3) {
                        if($indiceSecuencia >= 3) {
                            $indiceSecuencia = 0;
                        }                         
                        $strTurnoDefinitivo = $this->turnoDefinitivo($arSecuenciaDetalle[$indiceSecuencia], $i, $strTurnoDefinitivo);
                        $indiceSecuencia++;                        
                    } else {
                        $strTurnoDefinitivo = $this->turnoDefinitivo($arSecuenciaDetalle[0], $i, $strTurnoDefinitivo);
                    }
                    
                    if($i == 1) {
                        $arProgramacionAlterna->setDia1($strTurnoDefinitivo);                    
                    }
                    if($i == 2) {
                        $arProgramacionAlterna->setDia2($strTurnoDefinitivo);                    
                    }
                    if($i == 3) {
                        $arProgramacionAlterna->setDia3($strTurnoDefinitivo);                    
                    }
                    if($i == 4) {
                        $arProgramacionAlterna->setDia4($strTurnoDefinitivo);                    
                    }
                    if($i == 5) {
                        $arProgramacionAlterna->setDia5($strTurnoDefinitivo);                    
                    }
                    if($i == 6) {
                        $arProgramacionAlterna->setDia6($strTurnoDefinitivo);                    
                    }
                    if($i == 7) {
                        $arProgramacionAlterna->setDia7($strTurnoDefinitivo);                    
                    }
                    if($i == 8) {
                        $arProgramacionAlterna->setDia8($strTurnoDefinitivo);                    
                    }
                    if($i == 9) {
                        $arProgramacionAlterna->setDia9($strTurnoDefinitivo);                    
                    }
                    if($i == 10) {
                        $arProgramacionAlterna->setDia10($strTurnoDefinitivo);                    
                    }
                    if($i == 11) {
                        $arProgramacionAlterna->setDia11($strTurnoDefinitivo);                    
                    }
                    if($i == 12) {
                        $arProgramacionAlterna->setDia12($strTurnoDefinitivo);                    
                    }
                    if($i == 13) {
                        $arProgramacionAlterna->setDia13($strTurnoDefinitivo);                    
                    }
                    if($i == 14) {
                        $arProgramacionAlterna->setDia14($strTurnoDefinitivo);                    
                    }
                    if($i == 15) {
                        $arProgramacionAlterna->setDia15($strTurnoDefinitivo);                    
                    }
                    if($i == 16) {
                        $arProgramacionAlterna->setDia16($strTurnoDefinitivo);                    
                    }
                    if($i == 17) {
                        $arProgramacionAlterna->setDia17($strTurnoDefinitivo);                    
                    }
                    if($i == 18) {
                        $arProgramacionAlterna->setDia18($strTurnoDefinitivo);                    
                    }
                    if($i == 19) {
                        $arProgramacionAlterna->setDia19($strTurnoDefinitivo);                    
                    }
                    if($i == 20) {
                        $arProgramacionAlterna->setDia20($strTurnoDefinitivo);                    
                    }
                    if($i == 21) {
                        $arProgramacionAlterna->setDia21($strTurnoDefinitivo);                    
                    }
                    if($i == 22) {
                        $arProgramacionAlterna->setDia22($strTurnoDefinitivo);                    
                    }
                    if($i == 23) {
                        $arProgramacionAlterna->setDia23($strTurnoDefinitivo);                    
                    }
                    if($i == 24) {
                        $arProgramacionAlterna->setDia24($strTurnoDefinitivo);                    
                    }
                    if($i == 25) {
                        $arProgramacionAlterna->setDia25($strTurnoDefinitivo);                    
                    }
                    if($i == 26) {
                        $arProgramacionAlterna->setDia26($strTurnoDefinitivo);                    
                    }
                    if($i == 27) {
                        $arProgramacionAlterna->setDia27($strTurnoDefinitivo);                    
                    }
                    if($i == 28) {
                        $arProgramacionAlterna->setDia28($strTurnoDefinitivo);                    
                    }
                    if($i == 29) {
                        $arProgramacionAlterna->setDia29($strTurnoDefinitivo);                    
                    }
                    if($i == 30) {
                        $arProgramacionAlterna->setDia30($strTurnoDefinitivo);                    
                    }
                    if($i == 31) {
                        $arProgramacionAlterna->setDia31($strTurnoDefinitivo);                    
                    } 
                }
                $em->persist($arProgramacionAlterna);                                            
            }
        }               
        $em->flush();
        
        //Generar los detalles del soporte de pago
        
        $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportesPago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo));
        foreach ($arSoportesPago as $arSoportePago) {
            if($arSoportePago->getSecuencia()) {
                $em->getRepository('BrasaTurnoBundle:TurSoportePago')->generarAlterna($arSoportePago, $arFestivos);                                    
            }            
        }                                                
        $em->flush();                

        $em->getRepository('BrasaTurnoBundle:TurSoportePago')->resumen($arSoportePagoPeriodo);                     
        $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->liquidar($codigoSoportePagoPeriodo);                                                                     
        
    }
    
    private function turnoDefinitivo ($arSecuencia, $dia, $strTurnoDefinitivo) {
        $strTurnoDevolver = $strTurnoDefinitivo;
        if(!$strTurnoDefinitivo) {
            if($dia==1) {
                $strTurnoDevolver = $arSecuencia->getDia1();
            }
            if($dia==2) {
                $strTurnoDevolver = $arSecuencia->getDia2();
            }
            if($dia==3) {
                $strTurnoDevolver = $arSecuencia->getDia3();
            }
            if($dia==4) {
                $strTurnoDevolver = $arSecuencia->getDia4();
            }
            if($dia==5) {
                $strTurnoDevolver = $arSecuencia->getDia5();
            }
            if($dia==6) {
                $strTurnoDevolver = $arSecuencia->getDia6();
            }
            if($dia==7) {
                $strTurnoDevolver = $arSecuencia->getDia7();
            }
            if($dia==8) {
                $strTurnoDevolver = $arSecuencia->getDia8();
            }
            if($dia==9) {
                $strTurnoDevolver = $arSecuencia->getDia9();
            }
            if($dia==10) {
                $strTurnoDevolver = $arSecuencia->getDia10();
            }
            if($dia==11) {
                $strTurnoDevolver = $arSecuencia->getDia11();
            }
            if($dia==12) {
                $strTurnoDevolver = $arSecuencia->getDia12();
            }
            if($dia==13) {
                $strTurnoDevolver = $arSecuencia->getDia13();
            }
            if($dia==14) {
                $strTurnoDevolver = $arSecuencia->getDia14();
            }
            if($dia==15) {
                $strTurnoDevolver = $arSecuencia->getDia15();
            }
            if($dia==16) {
                $strTurnoDevolver = $arSecuencia->getDia16();
            }
            if($dia==17) {
                $strTurnoDevolver = $arSecuencia->getDia17();
            }
            if($dia==18) {
                $strTurnoDevolver = $arSecuencia->getDia18();
            }
            if($dia==19) {
                $strTurnoDevolver = $arSecuencia->getDia19();
            }
            if($dia==20) {
                $strTurnoDevolver = $arSecuencia->getDia20();
            }
            if($dia==21) {
                $strTurnoDevolver = $arSecuencia->getDia21();
            }
            if($dia==22) {
                $strTurnoDevolver = $arSecuencia->getDia22();
            }
            if($dia==23) {
                $strTurnoDevolver = $arSecuencia->getDia23();
            }
            if($dia==24) {
                $strTurnoDevolver = $arSecuencia->getDia24();
            }
            if($dia==25) {
                $strTurnoDevolver = $arSecuencia->getDia25();
            }
            if($dia==26) {
                $strTurnoDevolver = $arSecuencia->getDia26();
            }
            if($dia==27) {
                $strTurnoDevolver = $arSecuencia->getDia27();
            }
            if($dia==28) {
                $strTurnoDevolver = $arSecuencia->getDia28();
            }
            if($dia==29) {
                $strTurnoDevolver = $arSecuencia->getDia29();
            }
            if($dia==30) {
                $strTurnoDevolver = $arSecuencia->getDia30();
            }        
            if($dia==31) {
                $strTurnoDevolver = $arSecuencia->getDia31();
            }                     
        }
        return $strTurnoDevolver;
    }
    
    public function ajustarDevengado($codigoSoportePagoPeriodo) {
        $em = $this->getEntityManager();
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();        
        $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                        
        $arSoportePagoPeriodo->setAjusteDevengado(1);        
        $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportesPago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo));                                
        foreach ($arSoportesPago as $arSoportePago) {             
            $arSoportePagoAct = new \Brasa\TurnoBundle\Entity\TurSoportePago();
            $arSoportePagoAct = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arSoportePago->getCodigoSoportePagoPk());
            $arSoportePagoAct->setHoras($arSoportePago->getDiasTransporte() * 8);
            $arSoportePagoAct->setHorasDescanso(0);
            $arSoportePagoAct->setHorasDiurnas($arSoportePago->getDiasTransporte() * 8);
            $arSoportePagoAct->setHorasNocturnas(0);
            $arSoportePagoAct->setHorasFestivasDiurnas(0);
            $arSoportePagoAct->setHorasFestivasNocturnas(0);
            $arSoportePagoAct->setHorasExtrasOrdinariasDiurnas(0);
            $arSoportePagoAct->setHorasExtrasOrdinariasNocturnas(0);
            $arSoportePagoAct->setHorasExtrasFestivasDiurnas(0);
            $arSoportePagoAct->setHorasExtrasFestivasNocturnas(0);            
            $arSoportePagoAct->setHorasRecargoNocturno(0);
            $arSoportePagoAct->setHorasRecargoFestivoDiurno(0);            
            $arSoportePagoAct->setHorasRecargoFestivoNocturno(0);                        
            $em->persist($arSoportePagoAct);
        }               
        $em->flush();                    
        $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->liquidar($codigoSoportePagoPeriodo);                                                                             
    }  
    
    public function horasOrdinarias() {
        $arrHoras = array(
            'horasDescanso' => 0,
            'horasNovedad' => 0,
            'horasDiurnas' => 8,
            'horasNocturnas' => 0,
            'horasExtrasDiurnas' => 0,
            'horasExtrasNocturnas' => 0,
            'horasFestivasDiurnas' => 0,
            'horasFestivasNocturnas' => 0,
            'horasExtrasFestivasDiurnas' => 0,
            'horasExtrasFestivasNocturnas' => 0,
            'horas' => 8); 
        return $arrHoras;
    }
    
    public function horasAnuladas() {
        $arrHoras = array(
            'horasDescanso' => 0,
            'horasNovedad' => 0,
            'horasDiurnas' => 0,
            'horasNocturnas' => 0,
            'horasExtrasDiurnas' => 0,
            'horasExtrasNocturnas' => 0,
            'horasFestivasDiurnas' => 0,
            'horasFestivasNocturnas' => 0,
            'horasExtrasFestivasDiurnas' => 0,
            'horasExtrasFestivasNocturnas' => 0,
            'horas' => 0); 
        return $arrHoras;
    }    
    
}