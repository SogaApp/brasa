<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoDetalleRepository extends EntityRepository {

    public function listaConsultaDql($numeroPedido = "", $codigoCliente = "", $boolEstadoAutorizado = "", $boolEstadoProgramado = "", $boolEstadoFacturado = "", $boolEstadoAnulado = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p WHERE pd.codigoPedidoDetallePk <> 0 ";
        if($numeroPedido != "") {
            $dql .= " AND p.numero = " . $numeroPedido;  
        }
        if($codigoCliente != "") {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;  
        } 
        if($boolEstadoProgramado == 1 ) {
            $dql .= " AND pd.estadoProgramado = 1";
        }
        if($boolEstadoProgramado == "0") {
            $dql .= " AND pd.estadoProgramado = 0";
        }  
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND p.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND p.estadoAutorizado = 0";
        }         
        if($boolEstadoFacturado == 1 ) {
            $dql .= " AND pd.estadoFacturado = 1";
        }
        if($boolEstadoFacturado == "0") {
            $dql .= " AND pd.estadoFacturado = 0";
        }        
        if($boolEstadoAnulado == 1 ) {
            $dql .= " AND p.estadoAnulado = 1";
        }
        if($boolEstadoAnulado == "0") {
            $dql .= " AND p.estadoAnulado = 0";
        }
        if($strFechaDesde != "") {
            $dql .= " AND p.fechaProgramacion >= '" . $strFechaDesde . "'";
        }        
        if($strFechaHasta != "") {
            $dql .= " AND p.fechaProgramacion <= '" . $strFechaHasta . "'";
        }        
        return $dql;
    }     
    
    public function pendientesCliente($codigoCliente) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoClienteFk = " . $codigoCliente . " AND pd.estadoProgramado = 0 AND p.estadoAnulado = 0 AND p.estadoAutorizado = 1";
                
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }
    
    public function listaCliente($codigoCliente) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoClienteFk = " . $codigoCliente . " AND p.estadoAutorizado = 1 AND p.estadoAnulado = 0";
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }    
    
    public function pendientesFacturarDql($codigoCliente, $boolMostrarTodo = 0) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.estadoAutorizado = 1 AND p.estadoAnulado = 0";
        if($boolMostrarTodo == 0) {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;
        }        
        return $dql;                
    }    
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo);                
                $em->remove($arPedidoDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function numeroRegistros($codigo) {
        $em = $this->getEntityManager();
        $arDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigo));
        return count($arDetalles);
    }  
    
    public function validarPuesto($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(pd.codigoPedidoDetallePk) as numeroRegistros FROM BrasaTurnoBundle:TurPedidoDetalle pd "
                . "WHERE pd.codigoPedidoFk = " . $codigo . " AND pd.codigoPuestoFk IS NULL";
        $query = $em->createQuery($dql);
        $arrPedidosDetalles = $query->getSingleResult(); 
        if($arrPedidosDetalles) {
            $intNumeroRegistros = $arrPedidosDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }     
    
}