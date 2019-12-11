<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurSimulacionDetalleRecursoRepository extends EntityRepository {
    public function listaDql($usuario) {
        $em = $this->getEntityManager();
        $dql   = "SELECT sdr FROM BrasaTurnoBundle:TurSimulacionDetalleRecurso sdr WHERE sdr.usuario ='" . $usuario . "'";
        $dql .= " ORDER BY sdr.codigoSimulacionDetalleRecursoPk";
        return $dql;
    }
    
}