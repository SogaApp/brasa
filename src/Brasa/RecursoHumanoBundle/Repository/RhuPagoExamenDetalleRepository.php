<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuPagoExamenDetalleRepository extends EntityRepository {                   
    
    public function eliminarDetallesSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigoPagoExamenDetalle) {                
                $arPagoExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamenDetalle')->find($codigoPagoExamenDetalle);
                $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($arPagoExamenDetalle->getCodigoExamenFk());
                $arExamen->setEstadoPagado(0);
                $em->persist($arExamen);
                $em->remove($arPagoExamenDetalle);  
            }                                         
        }
        $em->flush();       
    }    
    
    public function numeroRegistros($codigoPagoExamen) {
        $em = $this->getEntityManager();
        $arPagoExamenDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamenDetalle')->findBy(array('codigoPagoExamenFk' => $codigoPagoExamen));
        return count($arPagoExamenDetalles);
    }    
}
