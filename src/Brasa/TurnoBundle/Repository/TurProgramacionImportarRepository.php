<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurProgramacionImportarRepository extends EntityRepository {

    public function listaDql() {
        $dql   = "SELECT pi FROM BrasaTurnoBundle:TurProgramacionImportar pi WHERE pi.codigoProgramacionImportarPk <> 0 ";
        
        /*if($codigoProgramacion != '') {
            $dql .= " AND pd.codigoProgramacionFk = " . $codigoProgramacion . " ";  
        }  
        if($codigoPuesto != '') {
            $dql .= " AND pd.codigoPuestoFk = " . $codigoPuesto . " ";  
        }        
        $dql .= " ORDER BY pd.codigoPuestoFk";
         * 
         */
        return $dql;
    } 
        
}