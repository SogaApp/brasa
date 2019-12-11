<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiCursoDetalleRepository extends EntityRepository {  
    
    public function ListaDql($codigoCurso = '') {
        $em = $this->getEntityManager();
        $dql   = "SELECT cd FROM BrasaAfiliacionBundle:AfiCursoDetalle cd WHERE cd.codigoCursoDetallePk <> 0 ";
        if($codigoCurso != '') {
           $dql .= " AND cd.codigoCursoFk = " . $codigoCurso; 
        }
        $dql .= " ORDER BY cd.codigoCursoDetallePk";
        return $dql;
    }            
    
    public function listaDqlConsulta($numero = "", $codigoCliente = "", $boolEstadoAutorizado = "", $boolAsistencia = "", $boolEstadoFacturado = "", $boolEstadoPagado = "", $boolEstadoAnulado = "", $strFechaDesde = "", $strFechaHasta = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT cd FROM BrasaAfiliacionBundle:AfiCursoDetalle cd JOIN cd.cursoRel c WHERE cd.codigoCursoDetallePk <> 0";
        if($numero != "") {
            $dql .= " AND c.numero = " . $numero;  
        }        
        if($codigoCliente != "") {
            $dql .= " AND c.codigoClienteFk = " . $codigoCliente;  
        }    
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND c.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND c.estadoAutorizado = 0";
        }        
        if($boolAsistencia == 1 ) {
            $dql .= " AND c.asistencia = 1";
        }
        if($boolAsistencia == "0") {
            $dql .= " AND c.asistencia = 0";
        }    
        if($boolEstadoFacturado == 1 ) {
            $dql .= " AND c.estadoFacturado = 1";
        }
        if($boolEstadoFacturado == "0") {
            $dql .= " AND c.estadoFacturado = 0";
        }
        if($boolEstadoPagado == 1 ) {
            $dql .= " AND c.estadoPagado = 1";
        }
        if($boolEstadoPagado == "0") {
            $dql .= " AND c.estadoPagado = 0";
        }        
        if($boolEstadoAnulado == 1 ) {
            $dql .= " AND c.estadoAnulado = 1";
        }
        if($boolEstadoAnulado == "0") {
            $dql .= " AND c.estadoAnulado = 0";
        }        
        if($strFechaDesde != "") {
            $dql .= " AND c.fecha >= '" . $strFechaDesde . "'";
        }        
        if($strFechaHasta != "") {
            $dql .= " AND c.fecha <= '" . $strFechaHasta . "'";
        }        
        $dql .= " ORDER BY c.fecha DESC";
        return $dql;
    }                
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    } 
    
    public function numeroRegistros($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(cd.codigoCursoDetallePk) as numeroRegistros FROM BrasaAfiliacionBundle:AfiCursoDetalle cd "
                . "WHERE cd.codigoCursoFk = " . $codigo;
        $query = $em->createQuery($dql);
        $arrCursosDetalles = $query->getSingleResult(); 
        if($arrCursosDetalles) {
            $intNumeroRegistros = $arrCursosDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    } 
    
    public function pendientePagoDql($codigoProveedor) {        
        $dql   = "SELECT cd FROM BrasaAfiliacionBundle:AfiCursoDetalle cd WHERE cd.estadoPagado = 0 AND cd.codigoProveedorFk = " . $codigoProveedor;
        $dql .= " ORDER BY cd.codigoCursoDetallePk DESC";
        return $dql;
    }     
        
}