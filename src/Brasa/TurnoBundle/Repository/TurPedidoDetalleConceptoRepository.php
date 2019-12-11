<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoDetalleConceptoRepository extends EntityRepository {    
    
    public function listaDql($codigoPedido = "") {
        $dql   = "SELECT sdc FROM BrasaTurnoBundle:TurPedidoDetalleConcepto sdc WHERE sdc.codigoPedidoDetalleConceptoPk <> 0 ";
        
        if($codigoPedido != '') {
            $dql .= "AND sdc.codigoPedidoFk = " . $codigoPedido . " ";  
        }               
        return $dql;
    }        
    
    public function listaClienteDql($codigoCliente = "") {
        $dql   = "SELECT pdc FROM BrasaTurnoBundle:TurPedidoDetalleConcepto pdc JOIN pdc.pedidoRel p WHERE pdc.estadoFacturado = 0 ";        
        if($codigoCliente != '') {
            $dql .= "AND p.codigoClienteFk = " . $codigoCliente . " ";  
        }               
        return $dql;
    }    
    
    public function eliminar($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                                
                $ar = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->find($codigo);  
                $em->remove($ar);                  
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