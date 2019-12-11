<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiClienteRepository extends EntityRepository
{
    public function listaDql($strNombre = "", $strCodigo = "", $strIdentificacion = "", $strIndependiente = "")
    {
        $em = $this->getEntityManager();
        $dql = "SELECT c FROM BrasaAfiliacionBundle:AfiCliente c WHERE c.codigoClientePk <> 0";

        if ($strNombre != "") {
            $dql .= " AND c.nombreCorto LIKE '%$strNombre%'";
        }
        if ($strCodigo != "") {
            $dql .= " AND c.codigoClientePk = " . $strCodigo . "";
        }
        if ($strIdentificacion != "") {
            $dql .= " AND c.nit = " . $strIdentificacion . "";
        }
        if ($strIndependiente == 1) {
            $dql .= " AND c.independiente = 1";
        }
        if ($strIndependiente == "0") {
            $dql .= " AND c.independiente = 0";
        }
        $dql .= " ORDER BY c.nombreCorto";
        return $dql;
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }
}