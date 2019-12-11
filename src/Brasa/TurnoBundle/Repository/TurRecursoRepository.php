<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
class TurRecursoRepository extends EntityRepository {    
    
    public function ListaDql($strNombre = "", $strCodigo = "", $codigoCentroCosto = "", $strNumeroIdentificacion = "", $codigoRecursoGrupo = "", $estadoRetirado = "", $estadoActivo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT r FROM BrasaTurnoBundle:TurRecurso r WHERE r.codigoRecursoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND r.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND r.codigoRecursoPk = " . $strCodigo;
        }
        if($strNumeroIdentificacion != "" ) {
            $dql .= " AND r.numeroIdentificacion LIKE '%" . $strNumeroIdentificacion . "%'";
        }        
        if($codigoCentroCosto != "" ) {
            $dql .= " AND r.codigoCentroCostoFk = " . $codigoCentroCosto ;
        }   
        if($codigoRecursoGrupo != "" ) {
            $dql .= " AND r.codigoRecursoGrupoFk = " . $codigoRecursoGrupo ;
        }        
        if($estadoRetirado == 1 ) {
            $dql .= " AND r.estadoRetiro = 1";
        }
        if($estadoRetirado == "0") {
            $dql .= " AND r.estadoRetiro = 0";
        }   
        if($estadoActivo == 1 ) {
            $dql .= " AND r.estadoActivo = 1";
        }
        if($estadoActivo == "0") {
            $dql .= " AND r.estadoActivo = 0";
        }         
        $dql .= " ORDER BY r.nombreCorto";
        return $dql;
    }            

    public function buscarDql($strNombre = "", $strCodigo = "", $codigoCentroCosto = "", $strNumeroIdentificacion = "", $codigoRecursoGrupo = "", $estadoRetirado = "", $inactivos = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT r FROM BrasaTurnoBundle:TurRecurso r WHERE r.codigoRecursoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND r.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND r.codigoRecursoPk = " . $strCodigo;
        }
        if($strNumeroIdentificacion != "" ) {
            $dql .= " AND r.numeroIdentificacion LIKE '%" . $strNumeroIdentificacion . "%'";
        }           
        if($codigoRecursoGrupo != "" ) {
            $dql .= " AND r.codigoRecursoGrupoFk = " . $codigoRecursoGrupo ;
        }        
        if($estadoRetirado == 1 ) {
            $dql .= " AND r.estadoRetiro = 1";
        }
        if($estadoRetirado == "0") {
            $dql .= " AND r.estadoRetiro = 0";
        }   
        if(!$inactivos) {
            $dql .= " AND r.estadoActivo = 1";
        }      
        $dql .= " ORDER BY r.nombreCorto";
        return $dql;
    }     
    
    public function disponibles($strDia = "", $strAnio = "", $strMes = "") {
        $em = $this->getEntityManager();             
        $strSql = "SELECT
                    tur_recurso.codigo_recurso_pk as codigoRecursoPk,
                    tur_recurso.nombre_corto as nombreCorto,
                    tur_recurso.telefono as telefono,
                    tur_recurso.celular as celular,
                    tur_recurso.estado_activo as estadoActivo,
                    tur_programacion_detalle.dia_$strDia as Dia$strDia,
                    tur_programacion_detalle.anio as anio,
                    tur_programacion_detalle.mes as mes,
                    tur_turno.descanso as descanso,
                    tur_turno.nombre as nombreTurno
                    FROM
                    tur_programacion_detalle
                    RIGHT JOIN tur_recurso ON tur_programacion_detalle.codigo_recurso_fk = tur_recurso.codigo_recurso_pk
                    LEFT OUTER JOIN tur_turno ON tur_programacion_detalle.dia_$strDia = tur_turno.codigo_turno_pk     
                    WHERE 
                    (                           
                        (tur_turno.descanso = 1 AND tur_programacion_detalle.anio = $strAnio AND tur_programacion_detalle.mes = $strMes) 
                            OR                            
                        dia_$strDia IS NULL                        
                    ) AND tur_recurso.estado_activo = 1 
                ORDER BY tur_recurso.nombre_corto"; 
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $results = $statement->fetchAll();        
        
        return $results;
    }                
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }     
    
    public function programacionFecha($strAnio, $strMes) {
        $em = $this->getEntityManager();             
        $strSql = "SELECT
                    tur_programacion_detalle.codigo_recurso_fk,
                    tur_recurso.codigo_empleado_fk
                    FROM tur_programacion_detalle                                        
                    LEFT JOIN tur_recurso ON tur_programacion_detalle.codigo_recurso_fk = tur_recurso.codigo_recurso_pk 
                    WHERE tur_programacion_detalle.anio = $strAnio AND tur_programacion_detalle.mes = $strMes AND tur_programacion_detalle.codigo_recurso_fk IS NOT NULL 
                    GROUP BY codigo_recurso_fk"; 
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $results = $statement->fetchAll();        
        
        return $results;
    } 
    
    public function programacionFechaRecurso($strAnio, $strMes, $codigoRecurso) {
        $em = $this->getEntityManager();             
        $strSql = "SELECT codigo_puesto_fk,
                    SUM(tur_programacion_detalle.horas)
                    FROM tur_programacion_detalle                                                            
                    WHERE tur_programacion_detalle.anio = $strAnio AND tur_programacion_detalle.mes = $strMes AND tur_programacion_detalle.codigo_recurso_fk = $codigoRecurso
                    GROUP BY codigo_puesto_fk ORDER BY SUM(tur_programacion_detalle.horas) DESC LIMIT 1"; 
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $results = $statement->fetchAll();        
        
        return $results;
    }    
}