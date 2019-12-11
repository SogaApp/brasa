<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurRecursoTipoRepository extends EntityRepository {    
    
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT r FROM BrasaTurnoBundle:TurRecursoTipo r WHERE r.codigoRecursoTipoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND r.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND r.codigoRecursoTipoPk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY r.nombre";
        return $dql;
    }        
        
}