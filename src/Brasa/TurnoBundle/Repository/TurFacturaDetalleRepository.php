<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurFacturaDetalleRepository extends EntityRepository {

    public function listaDql($codigoFactura = "") {
        $dql   = "SELECT fd FROM BrasaTurnoBundle:TurFacturaDetalle fd WHERE fd.codigoFacturaDetallePk <> 0 ";
        
        if($codigoFactura != '') {
            $dql .= "AND fd.codigoFacturaFk = " . $codigoFactura . " ";  
        }        
        $dql .= " ORDER BY fd.codigoGrupoFacturacionFk, fd.codigoPuestoFk";
        return $dql;
    }     
    
    public function listaConsultaDql($numeroFactura = "", $codigoCliente = "", $boolEstadoAutorizado = "", $strFechaDesde = "", $strFechaHasta = "", $boolEstadoAnulado = "", $codigoFacturaTipo = "") {
        $dql   = "SELECT fd FROM BrasaTurnoBundle:TurFacturaDetalle fd JOIN fd.facturaRel f WHERE fd.codigoFacturaDetallePk <> 0";
        if($numeroFactura != "") {
            $dql .= " AND f.numero = " . $numeroFactura;
        }
        if($codigoCliente != "") {
            $dql .= " AND f.codigoClienteFk = " . $codigoCliente;
        }
        if($codigoFacturaTipo != "") {
            $dql .= " AND f.codigoFacturaTipoFk = " . $codigoFacturaTipo;
        }        
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND f.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND f.estadoAutorizado = 0";
        }
        if($boolEstadoAnulado == 1 ) {
            $dql .= " AND f.estadoAnulado = 1";
        }
        if($boolEstadoAnulado == "0") {
            $dql .= " AND f.estadoAnulado = 0";
        }
        if($strFechaDesde != "") {
            $dql .= " AND f.fecha >= '" . $strFechaDesde . " 00:00:00'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND f.fecha <= '" . $strFechaHasta . " 23:59:59'";
        }
        $dql .= " ORDER BY f.codigoFacturaTipoFk, f.fecha DESC, f.numero DESC";
        return $dql;
    }    
    
    public function listaCliente($codigoCliente, $codigoFactura = "", $tipo = "") {
        $dql   = "SELECT fd FROM BrasaTurnoBundle:TurFacturaDetalle fd JOIN fd.facturaRel f JOIN f.facturaTipoRel ft WHERE f.codigoClienteFk =  " . $codigoCliente . " ";
        
        if($codigoFactura != '') {
            $dql .= "AND fd.codigoFacturaFk = " . $codigoFactura . " ";  
        }    
        if($tipo != '') {
            $dql .= "AND ft.tipo = " . $tipo . " ";  
        }        
        $dql .= " ORDER BY fd.codigoFacturaDetallePk DESC";
        return $dql;
    }     
    
    public function pendientesCliente($codigoTercero) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoTerceroFk = " . $codigoTercero . " AND p.codigoPedidoTipoFk = 1";
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }
    
    public function eliminar($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                                
                $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($codigo);  
                /*$arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arFacturaDetalle->getCodigoPedidoDetalleFk());  
                $arPedidoDetalle->setEstadoFacturado(0);
                $em->persist($arPedidoDetalle);*/
                if($arFacturaDetalle->getCodigoPedidoDetalleConceptoFk()) {
                    $arPedidoDetalleConcepto = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto();
                    $arPedidoDetalleConcepto = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->find($arFacturaDetalle->getCodigoPedidoDetalleConceptoFk());  
                    $arPedidoDetalleConcepto->setEstadoFacturado(0);
                    $em->persist($arPedidoDetalleConcepto);
                }
                $em->remove($arFacturaDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function numeroRegistros($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(fd.codigoFacturaDetallePk) as numeroRegistros FROM BrasaTurnoBundle:TurFacturaDetalle fd "
                . "WHERE fd.codigoFacturaFk = " . $codigo;
        $query = $em->createQuery($dql);
        $arrFacturaDetalles = $query->getSingleResult(); 
        if($arrFacturaDetalles) {
            $intNumeroRegistros = $arrFacturaDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }          
    
}