<?php

namespace Brasa\TransporteBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MovimientosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TteConductorRepository extends EntityRepository {
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaTransporteBundle:TteConductor c WHERE c.codigoConductorPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND c.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND c.codigoConductorPk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY c.nombreCorto";
        return $dql;
    }    
}