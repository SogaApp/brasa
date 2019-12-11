<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurSoportePagoInconsistenciaRepository extends EntityRepository {
    
    public function listaDql($codigoSoportePagoPeriodo = "") {
        $dql   = "SELECT spi FROM BrasaTurnoBundle:TurSoportePagoInconsistencia spi WHERE spi.codigoSoportePagoPeriodoFk = " . $codigoSoportePagoPeriodo;
        return $dql;
    }
    
    public function limpiar($codigoSoportePagoPeriodo) {        
        $em = $this->getEntityManager();
        $strSql = "DELETE FROM tur_soporte_pago_inconsistencia WHERE codigo_soporte_pago_periodo_fk = " . $codigoSoportePagoPeriodo;
        $em->getConnection()->executeQuery($strSql);      
        return true;
    }    
    
}