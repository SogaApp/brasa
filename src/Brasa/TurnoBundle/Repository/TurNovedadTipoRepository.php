<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurNovedadTipoRepository extends EntityRepository {    
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT nt FROM BrasaTurnoBundle:TurNovedadTipo nt WHERE nt.codigoNovedadTipoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND nt.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND nt.codigoNovedadTipoPk = " . $strCodigo;
        }        
        $dql .= " ORDER BY nt.nombre";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurNovedadTipo')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }        
}