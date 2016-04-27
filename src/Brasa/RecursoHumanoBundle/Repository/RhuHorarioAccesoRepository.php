<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuHorarioAccesoRepository extends EntityRepository {
    
    public function RegistroHoy($strFechaHoy = "") {
        
        $em = $this->getEntityManager();
        $dql   = "SELECT ha FROM BrasaRecursoHumanoBundle:RhuHorarioAcceso ha WHERE ha.codigoHorarioAccesoPk <> 0 AND ha.estadoSalida = 0";   
        if ($strFechaHoy != ""){
            $dql .= " AND ha.fechaEntrada >= '". $strFechaHoy . " 00:00:00' AND ha.fechaEntrada <= '" . $strFechaHoy . " 23:59:59' ";
        }
        return $dql;
    }
    
    public function Registro($strFechaHoy = "", $codigoHorarioAcceso= "") {
        
        $em = $this->getEntityManager();
        $dql   = "SELECT ha FROM BrasaRecursoHumanoBundle:RhuHorarioAcceso ha WHERE ha.codigoHorarioAccesoPk <> 0 ";   
        if ($strFechaHoy != ""){
            $dql .= " AND ha.fechaEntrada >= '". $strFechaHoy . " 00:00:00' AND ha.fechaEntrada <= '" . $strFechaHoy . " 23:59:59' ";
        }
        if ($codigoHorarioAcceso != ""){
            $dql .= " AND ha.codigoHorarioAccesoPk = ". $codigoHorarioAcceso;
        }
        $query = $em->createQuery($dql);
        $registro = $query->getResult();
        return $registro;
    }       
    
    public function empleado($strFechaDesde = "", $strFechaHasta = "", $codigoEmpleado = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT ha FROM BrasaRecursoHumanoBundle:RhuHorarioAcceso ha "
                . "WHERE  ha.fechaEntrada >= '". $strFechaDesde . " 00:00:00' AND ha.fechaEntrada <= '" . $strFechaHasta . " 23:59:59' "
                . "AND ha.codigoEmpleadoFk = " . $codigoEmpleado;                   
        $query = $em->createQuery($dql);
        $arEmpleado = $query->getResult();
        return $arEmpleado;
    }            
    
    public function validarIngreso($strFechaHoy = "", $strEmpleado= "") {          
        $em = $this->getEntityManager();
        $boolResultado = TRUE;
        $dql   = "SELECT ha FROM BrasaRecursoHumanoBundle:RhuHorarioAcceso ha "
                . "WHERE ha.fechaEntrada >= '". $strFechaHoy . " 00:00:00' AND ha.fechaEntrada <= '" . $strFechaHoy . " 23:59:59' "       
                . "AND ha.codigoEmpleadoFk = ". $strEmpleado;
        
        $query = $em->createQuery($dql);
        $arEmpleadoEntrada = $query->getResult();
        if(count($arEmpleadoEntrada) <= 0) {
            $boolResultado = FALSE;
        }
        return $boolResultado;
    }
    
    public function listaDql($strIdentificacion = "", $strNombre = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT ha,e FROM BrasaRecursoHumanoBundle:RhuHorarioAcceso ha JOIN ha.empleadoRel e WHERE ha.codigoHorarioAccesoPk <> 0 ";   
        if($strIdentificacion != "") {
            $dql .= " AND e.numeroIdentificacion = " . $strIdentificacion;
        }   
        if($strNombre != "" ) {
            $dql .= " AND e.nombreCorto LIKE '%". $strNombre . "%'";
        }
        if ($strDesde != ""){
            $dql .= " AND ha.fechaEntrada >= '". date_format($strDesde, 'Y-m-d') . " 00:00:00'";
        }
        if($strHasta != "") {
            $dql .= " AND ha.fechaEntrada <= '". date_format($strHasta, 'Y-m-d') . " 23:59:59'";
        }
        $dql .= " ORDER BY ha.fechaEntrada";
        return $dql;
    }

    public function listaDql2($codigoHorarioPeriodo = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT ha FROM BrasaRecursoHumanoBundle:RhuHorarioAcceso ha WHERE ha.codigoHorarioAccesoPk <> 0 ";   
        if($codigoHorarioPeriodo != "") {
            $dql .= " AND ha.codigoHorarioPeriodoFk = " . $codigoHorarioPeriodo;
        }   

        return $dql;
    }
    
    public function listaDql3($codigoHorarioPeriodo = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT ha FROM BrasaRecursoHumanoBundle:RhuHorarioAcceso ha WHERE ha.codigoHorarioAccesoPk <> 0 ";   
        if($codigoHorarioPeriodo != "") {
            $dql .= " AND ha.codigoHorarioPeriodoFk = " . $codigoHorarioPeriodo . " AND ha.salidaDiaSiguiente = 1 AND ha.estadoEntrada = 1 AND ha.estadoSalida = 0";
        }   

        return $dql;
    }    

    public function verificarSalidaPendiente($codigoHorarioPeriodo = "", $codigoEmpleado = "") { 
        $em = $this->getEntityManager();        
        $dql   = "SELECT ha FROM BrasaRecursoHumanoBundle:RhuHorarioAcceso ha WHERE ha.codigoHorarioAccesoPk <> 0 ";   
        $dql .= " AND ha.codigoHorarioPeriodoFk = " . $codigoHorarioPeriodo . " AND ha.codigoEmpleadoFk = " . $codigoEmpleado . " AND ha.salidaDiaSiguiente = 1 AND ha.estadoEntrada = 1 AND ha.estadoSalida = 0";        
        $query = $em->createQuery($dql);
        $arHorarioAcceso = $query->getResult();                   
        return $arHorarioAcceso;
    }    
    
    public function listaConsultaDql($strNombre = "", $strIdentificacion = "", $codigoCentroCosto = "", $codigoCargo = "", $codigoDepartametoEmpresa = "", $estadoEntrada, $estadoSalida, $strDesde = "", $strHasta = "", $entradaTarde, $salidaAntes) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT ha,e FROM BrasaRecursoHumanoBundle:RhuHorarioAcceso ha JOIN ha.empleadoRel e WHERE ha.codigoHorarioAccesoPk <> 0 ";   
        if($strNombre != "" ) {
            $dql .= " AND e.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if($codigoCentroCosto != "" || $codigoCentroCosto != 0 ) {
            $dql .= " AND e.codigoCentroCostoFk = " . $codigoCentroCosto;
        }
        if($codigoCargo != "" || $codigoCargo != 0 ) {
            $dql .= " AND e.codigoCargoFk = " . $codigoCargo;
        }
        if($codigoDepartametoEmpresa != "" || $codigoDepartametoEmpresa != 0 ) {
            $dql .= " AND e.codigoDepartamentoEmpresaFk = " . $codigoDepartametoEmpresa;
        }
        if($estadoEntrada == 1 ) {
            $dql .= " AND ha.estadoEntrada = 1";
        }
        if($estadoEntrada == 0 ) {
            $dql .= " AND ha.estadoEntrada = 0";
        }
        if($estadoSalida == 1 ) {
            $dql .= " AND ha.estadoSalida = 1";
        }
        if($estadoSalida == 0 ) {
            $dql .= " AND ha.estadoSalida = 0";
        }
        if ($strDesde != ""){
            $dql .= " AND ha.fechaEntrada >= '". date_format($strDesde, 'Y-m-d') . " 00:00:00'";
        }
        if($strHasta != "") {
            $dql .= " AND ha.fechaEntrada <= '". date_format($strHasta, 'Y-m-d') . " 23:59:59'";
        }
        if($entradaTarde == 1 ) {
            $dql .= " AND ha.entradaTarde = 1";
        }
        if($entradaTarde == 0 ) {
            $dql .= " AND ha.entradaTarde = 0";
        }
        if($salidaAntes == 1 ) {
            $dql .= " AND ha.salidaAntes = 1";
        }
        if($entradaTarde == 0 ) {
            $dql .= " AND ha.salidaAntes = 0";
        }
        $dql .= " ORDER BY ha.fechaEntrada";
        return $dql;
    }
    
    public function calculoHoras($strFechaDesde = "", $strFechaHasta = "", $codigoEmpleado = "") {        
        $em = $this->getEntityManager();
        
    }
    
}