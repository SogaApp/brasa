<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurConceptoServicioRepository extends EntityRepository {
    
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT cs FROM BrasaTurnoBundle:TurConceptoServicio cs WHERE cs.codigoConceptoServicioPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND cs.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND cs.codigoConceptoServicioPk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY cs.nombre";
        return $dql;
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurConceptoServicio')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }
    
}