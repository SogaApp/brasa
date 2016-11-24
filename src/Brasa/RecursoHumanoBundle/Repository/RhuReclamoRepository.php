<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuReclamoRepository extends EntityRepository {
    
    public function listaDql($codigoEmpleado = "", $fechaDesde = "", $fechaHasta = "", $estadoCerrado = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT r FROM BrasaRecursoHumanoBundle:RhuReclamo r WHERE r.codigoReclamoPk <> 0";  
        if($codigoEmpleado != "" ) {
            $dql .= " AND r.codigoEmpleadoFk = " . $codigoEmpleado;
        }        
        if($estadoCerrado == 1 ) {
            $dql .= " AND r.estadoCerrado = 1";
        }
        if($estadoCerrado == "0") {
            $dql .= " AND r.estadoCerrado = 0";
        }     
        if($fechaDesde != '') {
            $dql .= " AND r.fecha >= '$fechaDesde'";
        }
        if($fechaHasta != '') {
            $dql .= " AND r.fecha <= '$fechaHasta'";
        }        
        $dql .= " ORDER BY r.fecha DESC";
        return $dql;
    } 
    
    public function listaMovimientoDql($strIdentificacion = "", $strNombre = "", $strEstudio = "", $strEstado = "", $fechaVencimientoControl = "", $fechaVencimientoAcreditacion = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT ee, e FROM BrasaRecursoHumanoBundle:RhuEmpleadoEstudio ee JOIN ee.empleadoRel e WHERE ee.codigoEmpleadoEstudioPk <> 0";
   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if($strNombre != "" ) {
            $dql .= " AND e.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strEstudio != "") {
            $dql .= " AND ee.codigoEmpleadoEstudioTipoFk = " . $strEstudio;
        }
        if($strEstado != "") {
            $dql .= " AND ee.codigoEstudioEstadoFk = " . $strEstado;
        }       
        if($fechaVencimientoControl != "" ) {
            $dql .= " AND ee.fechaVencimientoCurso <='" . $fechaVencimientoControl . "'";
        }
        
        if($fechaVencimientoAcreditacion != "" ) {
            $dql .= " AND ee.fechaVencimientoAcreditacion <='" . $fechaVencimientoAcreditacion . "'";
        }        
        $dql .= " ORDER BY ee.codigoEmpleadoEstudioPk desc";
        return $dql;
    }
}