<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurSoportePagoRepository extends EntityRepository {
    
    public function listaDql($codigoSoportePagoPeriodo = "") {
        $dql   = "SELECT sp FROM BrasaTurnoBundle:TurSoportePago sp WHERE sp.codigoSoportePagoPeriodoFk = " . $codigoSoportePagoPeriodo;
        return $dql;
    }
    
    public function resumen($dateFechaDesde, $dateFechaHasta, $arSoportePagoPeriodo) {
        $em = $this->getEntityManager();
        $dql   = "SELECT spd.codigoRecursoFk, "
                . "SUM(spd.descanso) as descanso, "
                . "SUM(spd.dias) as dias, "
                . "SUM(spd.horasDiurnas) as horasDiurnas, "
                . "SUM(spd.horasNocturnas) as horasNocturnas, "
                . "SUM(spd.horasFestivasDiurnas) as horasFestivasDiurnas, "
                . "SUM(spd.horasFestivasNocturnas) as horasFestivasNocturnas, "                
                . "SUM(spd.horasExtrasOrdinariasDiurnas) as horasExtrasOrdinariasDiurnas, "
                . "SUM(spd.horasExtrasOrdinariasNocturnas) as horasExtrasOrdinariasNocturnas, "
                . "SUM(spd.horasExtrasFestivasDiurnas) as horasExtrasFestivasDiurnas, "
                . "SUM(spd.horasExtrasFestivasNocturnas) as horasExtrasFestivasNocturnas "
                . "FROM BrasaTurnoBundle:TurSoportePagoDetalle spd "
                . "WHERE spd.codigoSoportePagoPeriodoFk =  " . $arSoportePagoPeriodo->getCodigoSoportePagoPeriodoPk() . " "
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
            }            
            $arSoportePago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
            $arSoportePago->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
            $arSoportePago->setRecursoRel($arRecurso);
            $arSoportePago->setFechaDesde($dateFechaDesde);
            $arSoportePago->setFechaHasta($dateFechaHasta);
            $arSoportePago->setDias($arrayResultado[$i]['dias']);
            $arSoportePago->setDescanso($arrayResultado[$i]['descanso']);
            $arSoportePago->setHorasDiurnas($arrayResultado[$i]['horasDiurnas']);
            $arSoportePago->setHorasNocturnas($arrayResultado[$i]['horasNocturnas']);
            $arSoportePago->setHorasFestivasDiurnas($arrayResultado[$i]['horasFestivasDiurnas']);
            $arSoportePago->setHorasFestivasNocturnas($arrayResultado[$i]['horasFestivasNocturnas']);            
            $arSoportePago->setHorasExtrasOrdinariasDiurnas($arrayResultado[$i]['horasExtrasOrdinariasDiurnas']);
            $arSoportePago->setHorasExtrasOrdinariasNocturnas($arrayResultado[$i]['horasExtrasOrdinariasNocturnas']);
            $arSoportePago->setHorasExtrasFestivasDiurnas($arrayResultado[$i]['horasExtrasFestivasDiurnas']);
            $arSoportePago->setHorasExtrasFestivasNocturnas($arrayResultado[$i]['horasExtrasFestivasNocturnas']);
            if($arContrato) {
                $arSoportePago->setCodigoContratoFk($arContrato->getCodigoContratoPk());
                $arSoportePago->setVrSalario($arContrato->getVrSalario());
            }
            $em->persist($arSoportePago);            
        }
        $em->flush();
        return $arrayResultado;        
    }

}