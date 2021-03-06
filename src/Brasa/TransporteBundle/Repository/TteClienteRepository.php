<?php

namespace Brasa\TransporteBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MovimientosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TteClienteRepository extends EntityRepository {
    
    public function ListaDql($strNombre = "", $strNit = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaTransporteBundle:TteCliente c WHERE c.codigoClientePk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND c.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strNit != "" ) {
            $dql .= " AND c.nit LIKE '%" . $strNit . "%'";
        }
        $dql .= " ORDER BY c.nombreCorto";
        return $dql;
    }    
}