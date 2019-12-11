<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurProgramacionInconsistenciaRepository extends EntityRepository {
    public function listaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT pi FROM BrasaTurnoBundle:TurProgramacionInconsistencia pi WHERE pi.codigoProgramacionInconsistenciaPk <> 0";
        return $dql;
    }                
}