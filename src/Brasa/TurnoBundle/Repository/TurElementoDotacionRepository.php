<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurElementoDotacionRepository extends EntityRepository {
    
    public function listaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT ed FROM BrasaTurnoBundle:TurElementoDotacion ed WHERE ed.codigoElementoDotacionPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND ed.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND ed.codigoElementoDotacionPk = " . $strCodigo;
        }
        $dql .= " ORDER BY ed.nombre";
        return $dql;
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurElementoDotacion')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }
    
}