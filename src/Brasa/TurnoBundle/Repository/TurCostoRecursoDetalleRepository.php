<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCostoRecursoDetalleRepository extends EntityRepository {

    public function listaDql($codigoRecurso = "", $anio = "", $mes = "", $codigoPedidoDetalle = "") {
        $dql   = "SELECT crd FROM BrasaTurnoBundle:TurCostoRecursoDetalle crd WHERE crd.codigoCostoRecursoDetallePk <> 0";
        if($codigoRecurso != "") {
            $dql .= " AND crd.codigoRecursoFk = " . $codigoRecurso;  
        }  
        if($codigoPedidoDetalle != "") {
            $dql .= " AND crd.codigoPedidoDetalleFk = " . $codigoPedidoDetalle;  
        }         
        if($anio != "") {
            $dql .= " AND crd.anio = " . $anio;  
        }     
        if($mes != "") {
            $dql .= " AND crd.mes = " . $mes;  
        }         
        return $dql;
    }    

    public function listaConsultaDql($codigoCliente = "", $mes = "") {
        $dql   = "SELECT crd FROM BrasaTurnoBundle:TurCostoRecursoDetalle crd WHERE crd.codigoCostoRecursoDetallePk <> 0 ";
        if($codigoCliente != "") {
            $dql .= " AND crd.codigoClienteFk = " . $codigoCliente;  
        }
        if($mes != "") {
            $dql .= " AND crd.mes = " . $mes;  
        }        
        return $dql;
    }    
    
}