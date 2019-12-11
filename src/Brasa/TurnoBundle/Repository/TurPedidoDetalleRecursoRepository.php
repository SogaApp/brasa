<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoDetalleRecursoRepository extends EntityRepository {
    public function listaDql($codigoPedidoDetalle) {
        $dql   = "SELECT pdr FROM BrasaTurnoBundle:TurPedidoDetalleRecurso pdr WHERE pdr.codigoPedidoDetalleFk = " . $codigoPedidoDetalle;
        $dql .= " ORDER BY pdr.posicion";
        return $dql;
    }    
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $ar = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleRecurso')->find($codigo);                
                $em->remove($ar);                  
            }                                         
            $em->flush();       
        }
        
    }            
}