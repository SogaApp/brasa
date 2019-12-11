<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiRazonSocialRepository extends EntityRepository
{
    public function listaDql($strNombre = "", $strCodigo = "", $strIdentificacion = "")
    {
        $em = $this->getEntityManager();
        $dql = "SELECT c FROM BrasaAfiliacionBundle:AfiRazonSocial c WHERE c.codigoRazonSocialPk <> 0";
        if ($strNombre != "") {
            $dql .= " AND c.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if ($strCodigo != "") {
            $dql .= " AND c.codigoClientePk = " . $strCodigo . "";
        }
        if ($strIdentificacion != "") {
            $dql .= " AND c.nit = " . $strIdentificacion . "";
        }
        $dql .= " ORDER BY c.nombre";
        return $dql;
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiRazonSocial')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }
}