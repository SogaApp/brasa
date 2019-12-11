<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurClienteDireccionRepository extends EntityRepository {    
    
    public function ListaDql($strNombre = "", $strCodigo = "", $strCliente = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT cd FROM BrasaTurnoBundle:TurClienteDireccion cd JOIN cd.clienteRel c WHERE cd.codigoClienteDireccionPk <> 0";
        
        if($strNombre != "" ) {
            $dql .= " AND cd.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND cd.codigoClientePk LIKE '%" . $strCodigo . "%'";
        }
        
        if($strCliente != "" ) {
            $dql .= " AND c.nombreCorto LIKE '%" . $strCliente . "%'";
        }        
        $dql .= " ORDER BY c.nombreCorto";
        return $dql;
    }                
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurClienteDireccion')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }         
}