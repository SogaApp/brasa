<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurProgramacionAlternaRepository extends EntityRepository {      
    public function listaDql($codigoSoportePagoPeriodo = "") {
        $dql   = "SELECT pa FROM BrasaTurnoBundle:TurProgramacionAlterna pa WHERE pa.codigoProgramacionAlternaPk <> 0 ";
        
        if($codigoSoportePagoPeriodo != '') {
            $dql .= " AND pa.codigoSoportePagoPeriodoFk = " . $codigoSoportePagoPeriodo;  
        }        
        //$dql .= " ORDER BY pd.codigoPuestoFk";
        return $dql;
    }     
    
    public function periodoDias($anio, $mes, $codigoRecurso = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pa.codigoProgramacionAlternaPk, pa.dia1, pa.dia2, pa.dia3, pa.dia4, pa.dia5, pa.dia6, pa.dia7, pa.dia8, pa.dia9, pa.dia10, "
                . "pa.dia11, pa.dia12, pa.dia13, pa.dia14, pa.dia15, pa.dia16, pa.dia17, pa.dia18, pa.dia19, pa.dia20, "
                . "pa.dia21, pa.dia22, pa.dia23, pa.dia24, pa.dia25, pa.dia26, pa.dia27, pa.dia28, pa.dia29, pa.dia30, pa.dia31 "
                . "FROM BrasaTurnoBundle:TurProgramacionAlterna pa "
                . "WHERE pa.anio = " . $anio . " AND pa.mes =" . $mes;
        if($codigoRecurso != "") {
            $dql .= " AND pa.codigoRecursoFk = " . $codigoRecurso;
        }        
        $query = $em->createQuery($dql);                
        $arResultado = $query->getResult();
        return $arResultado;
    }        
}