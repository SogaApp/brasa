<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurClienteRepository extends EntityRepository {    
    public function ListaDql($strNombre = "", $strCodigo = "", $strNit = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaTurnoBundle:TurCliente c WHERE c.codigoClientePk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND c.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND c.codigoClientePk LIKE '%" . $strCodigo . "%'";
        }
        if($strNit != "" ) {
            $dql .= " AND c.nit LIKE '%" . $strNit . "%'";
        }        
        $dql .= " ORDER BY c.nombreCorto";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }        
}