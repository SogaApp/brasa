<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPlantillaDetalleRepository extends EntityRepository {
    public function eliminarDetalles($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arDetalle = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->find($codigo);                
                $em->remove($arDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }
    
    public function numeroRegistros($codigo) {
        $em = $this->getEntityManager();
        $arDetalles = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->findBy(array('codigoPlantillaFk' => $codigo));
        return count($arDetalles);
    }     
}