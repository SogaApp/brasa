<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiEntidadEntrenamientoRepository extends EntityRepository {    
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT ee FROM BrasaAfiliacionBundle:AfiEntidadEntrenamiento ee WHERE ee.codigoEntidadEntrenamientoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND ee.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND ee.codigoEntidadEntrenamientoPk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY ee.nombreCorto";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamiento')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }        
}