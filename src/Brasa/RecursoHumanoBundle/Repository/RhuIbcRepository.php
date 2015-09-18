<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuIbcRepository extends EntityRepository {
    
    public function devuelveIbcFecha($codigoEmpleado, $fechaDesde, $fechaHasta, $codigoContrato) {
            $em = $this->getEntityManager();
            $dql   = "SELECT SUM(ibc.vrIngresoBaseCotizacion) FROM BrasaRecursoHumanoBundle:RhuIbc ibc "
                    . "WHERE ibc.codigoEmpleadoFk = " . $codigoEmpleado . " "
                    . "AND ibc.codigoContratoFk = " . $codigoContrato . " "
                    . "AND ibc.fechaDesde >= '" . $fechaDesde . "' AND ibc.fechaHasta <= '" . $fechaHasta . "'";
            $query = $em->createQuery($dql);
            $arrayResultado = $query->getResult();
            return $arrayResultado;
        }    
    
    public function listaDqlCostosIbc($strContrato = "", $strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT i, e FROM BrasaRecursoHumanoBundle:RhuIbc i JOIN i.empleadoRel e WHERE i.codigoIbcPk <> 0";
        if($strContrato != "") {
            $dql .= " AND i.codigoContratoFk = " . $strContrato;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND i.fechaDesde >='" . date_format($strDesde, ('Y-m-d')). "'";
        }
        if($strHasta != "") {
            $dql .= " AND i.fechaHasta <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        //$dql .= " ORDER BY p.empleadoRel.nombreCorto";
        return $dql;
    }         
    
}