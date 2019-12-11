<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurProspectoRepository extends EntityRepository {    
    
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaTurnoBundle:TurProspecto c WHERE c.codigoProspectoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND c.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND c.codigoProspectoPk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY c.nombreCorto";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurProspecto')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }        
}