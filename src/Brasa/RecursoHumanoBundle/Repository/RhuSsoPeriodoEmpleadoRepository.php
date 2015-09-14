<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuSsoPeriodoEmpleadoRepository extends EntityRepository {
    
    public function listaDql($codigoPeriodo, $codigoSucursal) {                    
            $dql   = "SELECT pe FROM BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado pe "
                    ."WHERE pe.codigoPeriodoFk = " . $codigoPeriodo . " "
                    . "AND pe.codigoSucursalFk = " . $codigoSucursal;
            return $dql;
        } 
        
    public function actualizar($codigoPeriodoDetalle) {
        $em = $this->getEntityManager();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);             
        $arPeriodoEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
        $arPeriodoEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->findBy(array('codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));     
        foreach ($arPeriodoEmpleados as $arPeriodoEmpleado) {
            $arPeriodoEmpleadoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
            $arPeriodoEmpleadoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->find($arPeriodoEmpleado->getCodigoPeriodoEmpleadoPk());                        
            $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arPeriodoEmpleado->getCodigoContratoFk());                            
            $dateFechaDesde =  "";
            $dateFechaHasta =  "";
            $strNovedadIngreso = " ";
            $strNovedadRetiro = " ";
            $intDiasCotizar = 0;
            $fechaTerminaCotrato = $arContrato->getFechaHasta()->format('Y-m-d');
            if($fechaTerminaCotrato == '-0001-11-30') {
                $dateFechaHasta = $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta();
            } else {
                if($arContrato->getFechaHasta() > $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()) {
                    $dateFechaHasta = $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta();
                } else {
                    $dateFechaHasta = $arContrato->getFechaHasta();
                }
            }

            if($arContrato->getFechaDesde() <  $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde() == true) {
                $dateFechaDesde = $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde();
            } else {
                $dateFechaDesde = $arContrato->getFechaDesde();
            }

            if($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');                        
                $intDiasCotizar = $intDias + 1;
                if($intDiasCotizar == 31) {
                    $intDiasCotizar = $intDiasCotizar - 1;
                } else {
                    if($arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('d') == 28) {
                        if($arContrato->getFechaHasta() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta() || $arContrato->getIndefinido() == 1) {
                            $intDiasCotizar = $intDiasCotizar + 2;
                        }
                    }
                    if($arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('d') == 29) {
                        if($arContrato->getFechaHasta() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta() || $arContrato->getIndefinido() == 1) {
                            $intDiasCotizar = $intDiasCotizar + 1;
                        }
                    }                    
                    if($arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('d') == 31) {
                        if($arContrato->getFechaHasta() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta() || $arContrato->getIndefinido() == 1) {
                            if($arContrato->getFechaDesde()->format('d') != 31) {
                                $intDiasCotizar = $intDiasCotizar - 1;
                            }                                    
                        }
                    }                            
                }
            }

            if($arContrato->getFechaDesde() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()) {
                $strNovedadIngreso = "X";
            }

            if($arContrato->getIndefinido() == 0 && $fechaTerminaCotrato <= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()) {                    
                $strNovedadRetiro = "X";                    
            }
            $floSalario = $arContrato->getVrSalario();
            $arPeriodoEmpleadoActualizar->setVrSalario($floSalario);
            $arPeriodoEmpleadoActualizar->setDias($intDiasCotizar);
            $arPeriodoEmpleadoActualizar->setIngreso($strNovedadIngreso);
            $arPeriodoEmpleadoActualizar->setRetiro($strNovedadRetiro);
            $floSuplementario = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->tiempoSuplementario($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()->format('Y-m-d'), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('Y-m-d'), $arContrato->getCodigoContratoPk());            
            $arPeriodoEmpleadoActualizar->setVrSuplementario($floSuplementario);
            $intDiasLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->diasLicencia($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 2);
            $arPeriodoEmpleadoActualizar->setDiasLicencia($intDiasLicencia);
            $intDiasIncapacidadGeneral = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidad($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 28);
            $arPeriodoEmpleadoActualizar->setDiasIncapacidadGeneral($intDiasIncapacidadGeneral);
            $intDiasLicenciaMaternidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->diasLicencia($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 1);
            $arPeriodoEmpleadoActualizar->setDiasLicenciaMaternidad($intDiasLicenciaMaternidad);
            $intDiasIncapacidadLaboral = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidad($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 29);
            $arPeriodoEmpleadoActualizar->setDiasIncapacidadLaboral($intDiasIncapacidadLaboral);            
            $arPeriodoEmpleadoActualizar->setTarifaPension($arContrato->getTipoPensionRel()->getPorcentajeCotizacion());
            $arPeriodoEmpleadoActualizar->setTarifaRiesgos($arContrato->getClasificacionRiesgoRel()->getPorcentaje());
            $em->persist($arPeriodoEmpleadoActualizar);
        }
        $em->flush();
                        
        return true;
    }
}