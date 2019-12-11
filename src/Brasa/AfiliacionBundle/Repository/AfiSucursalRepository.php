<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiSucursalRepository extends EntityRepository {    
    public function listaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaAfiliacionBundle:AfiSucursal c WHERE c.codigoSucursalPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND c.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND c.codigoSucursalPk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY c.nombre";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiSucursal')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }        
}