<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuProyeccionRepository extends EntityRepository {   
    
    public function listaDql($codigoContrato = "", $strFechaDesde = "", $strFechaHasta = "") {        
            $em = $this->getEntityManager();
            $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuProyeccion p WHERE p.codigoProyeccionPk <> 0";
            
            if($codigoContrato != "") {
                $dql .= " AND p.codigoContratoFk = " . $codigoContrato;
            }   

            if ($strFechaDesde != ""){
                $dql .= " AND p.fechaDesde >='" . $strFechaDesde . "'";
            }
            if($strFechaHasta != "") {
                $dql .= " AND p.fechaHasta <='" . $strFechaHasta . "'";
            }
            //$dql .= " ORDER BY p.empleadoRel.nombreCorto";
            return $dql;
        }             
}