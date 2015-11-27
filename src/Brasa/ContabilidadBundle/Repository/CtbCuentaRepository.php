<?php

namespace Brasa\ContabilidadBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CuentasContablesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CtbCuentaRepository extends EntityRepository
{
    public function listaDql($intCodigoCuenta = "", $strNombreCuenta = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaContabilidadBundle:CtbCuenta c WHERE c.codigoCuentaPk <> 0";
        if($intCodigoCuenta != "" && $intCodigoCuenta != 0) {
            $dql .= " AND c.codigoCuentaPk = " . $intCodigoCuenta;
        }
        if($strNombreCuenta != "" ) {
            $dql .= " AND c.nombreCuenta like '%" . $strNombreCuenta."%'";
        }
        return $dql;
    }
}