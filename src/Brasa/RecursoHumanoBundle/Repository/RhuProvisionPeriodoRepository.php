<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuProvisionPeriodoRepository extends EntityRepository {
    public function listaDql() {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pp FROM BrasaRecursoHumanoBundle:RhuProvisionPeriodo pp WHERE pp.codigoProvisionPeriodoPk <> 0";
        $dql .= " ORDER BY pp.codigoProvisionPeriodoPk ASC";
        return $dql;
    }                            
    
}