<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurSoportePagoProgramacionRepository extends EntityRepository {

    public function listaDql($codigoProgramacion = "", $codigoPuesto = "") {
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurProgramacionDetalle pd WHERE pd.codigoProgramacionDetallePk <> 0 ";
        
        if($codigoProgramacion != '') {
            $dql .= " AND pd.codigoProgramacionFk = " . $codigoProgramacion . " ";  
        }  
        if($codigoPuesto != '') {
            $dql .= " AND pd.codigoPuestoFk = " . $codigoPuesto . " ";  
        }        
        $dql .= " ORDER BY pd.codigoPuestoFk";
        return $dql;
    } 
    
}