<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurModalidadServicioRepository extends EntityRepository {
    
    public function listaDQL() {
        $dql   = "SELECT r FROM BrasaTurnoBundle:TurRecurso r WHERE c.codigoRecursoPk <> 0";
        return $dql;
    }
    
    public function liquidar($codigoPedido) {        
        $em = $this->getEntityManager();        
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();        
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido); 
        $intCantidad = 0;
        $douTotalHoras = 0;
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;
        $arPedidosDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido));         
        foreach ($arPedidosDetalle as $arPedidoDetalle) {
            $intDias = $arPedidoDetalle->getFechaDesde()->diff($arPedidoDetalle->getFechaHasta());
            $intDias = $intDias->format('%a');                           
            $intDias += 1;                   
            
            $douHoras = $arPedidoDetalle->getTurnoRel()->getHoras() * $intDias;            
            $douHorasDiurnas = $arPedidoDetalle->getTurnoRel()->getHorasDiurnas() * $intDias;            
            $douHorasNocturnas = $arPedidoDetalle->getTurnoRel()->getHorasNocturnas() * $intDias;            
            $arPedidoDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
            $arPedidoDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arPedidoDetalle->getCodigoPedidoDetallePk());                         
            //$arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            //$arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arPedidoDetalle->getDia15()); 
            $arPedidoDetalleActualizar->setHoras($douHoras);
            $arPedidoDetalleActualizar->setHorasDiurnas($douHorasDiurnas);
            $arPedidoDetalleActualizar->setHorasNocturnas($douHorasNocturnas);
            $arPedidoDetalleActualizar->setDias($intDias);
            $em->persist($arPedidoDetalleActualizar);            
            $douTotalHoras += $douHoras;
            $douTotalHorasDiurnas += $douHorasDiurnas;
            $douTotalHorasNocturnas += $douHorasNocturnas;
            $intCantidad++;
        }
        $arPedido->setHoras($douTotalHoras);
        $arPedido->setHorasDiurnas($douTotalHorasDiurnas);
        $arPedido->setHorasNocturnas($douTotalHorasNocturnas);
        $em->persist($arPedido);
        $em->flush();
        return true;
    }        
}