<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuLiquidacionAdicionalesRepository extends EntityRepository {

    public function listaConsultaDql($fechaDesde = "", $fechaHasta = "", $codigoEmpleado = "", $codigoPagoConcepto = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT la FROM BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales la JOIN la.liquidacionRel l WHERE la.codigoLiquidacionAdicionalPk <> 0";        
        $dql .= " AND l.fecha >= '" . $fechaDesde . "' AND l.fecha <='" . $fechaHasta . " '"; 
        if($codigoEmpleado != "") {
            $dql .= " AND l.codigoEmpleadoFk = " . $codigoEmpleado;
        }
        if($codigoPagoConcepto != "") {
            $dql .= " AND la.codigoPagoConceptoFk = " . $codigoPagoConcepto;
        }        
        return $dql;
    }     
    
}