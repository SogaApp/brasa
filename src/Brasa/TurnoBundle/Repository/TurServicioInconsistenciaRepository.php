<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurServicioInconsistenciaRepository extends EntityRepository {
    public function listaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT si FROM BrasaTurnoBundle:TurServicioInconsistencia si WHERE si.codigoServicioInconsistenciaPk <> 0";
        return $dql;
    }                
}