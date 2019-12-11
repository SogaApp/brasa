<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuPagoExamenRepository extends EntityRepository {                   
             
    public function eliminarPagoExamenSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigoPagoExamen) {                
                $arPagoExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->find($codigoPagoExamen);                     
                if($em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->devuelveRegistrosPagoExamenDetalle($codigoPagoExamen) <= 0){   
                    $em->remove($arPagoExamen);
                }
            }
            $em->flush();       
        }     
    }
    
    public function listaDQL($strCodigoEntidadExamen = "") {                
        $dql   = "SELECT pe FROM BrasaRecursoHumanoBundle:RhuPagoExamen pe WHERE pe.codigoPagoExamenPk <> 0";
        if($strCodigoEntidadExamen != "") {
            $dql .= " AND pe.codigoEntidadExamenFk = " . $strCodigoEntidadExamen;
        }     
        
        $dql .= " ORDER BY pe.codigoPagoExamenPk";
        return $dql;
    } 
    
    public function liquidar($codigoPagoExamen) {
        $em = $this->getEntityManager();
        $arPagoExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->find($codigoPagoExamen);
        $arPagoExamenDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamenDetalle')->findBy(array('codigoPagoExamenFk' => $codigoPagoExamen));
        $douTotal = 0;
        foreach ($arPagoExamenDetalles AS $arPagoExamenDetalle) {
            $douTotal += $arPagoExamenDetalle->getVrPrecio();
        }
        $arPagoExamen->setVrTotal($douTotal);
        $em->persist($arPagoExamen);
        $em->flush();
    }    
         
    public function devuelveRegistrosPagoExamenDetalle($codigoPagoExamen) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(ped.codigoPagoExamenDetallePk) FROM BrasaRecursoHumanoBundle:RhuPagoExamenDetalle ped WHERE ped.codigoPagoExamenFk = " . $codigoPagoExamen;
        $query = $em->createQuery($dql);
        $douRegistrosPagoExamenDetalle = $query->getSingleScalarResult();
        return $douRegistrosPagoExamenDetalle;
    }
    
}
