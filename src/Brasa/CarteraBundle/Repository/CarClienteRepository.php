<?php

namespace Brasa\CarteraBundle\Repository;

use Doctrine\ORM\EntityRepository;


class CarClienteRepository extends EntityRepository {
    
    public function ListaDql($strNombre = "", $strCodigo = "", $strIdentificacion = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaCarteraBundle:CarCliente c WHERE c.codigoClientePk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND c.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND c.codigoClientePk LIKE '%" . $strCodigo . "%'";
        }
        if($strIdentificacion != "" ) {
            $dql .= " AND c.nit LIKE '%" . $strIdentificacion . "%'";
        }
        $dql .= " ORDER BY c.nombreCorto";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaCarteraBundle:CarCliente')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }      
}