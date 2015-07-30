<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuSsoPeriodoDetalleRepository extends EntityRepository {
    public function listaDQL($codigoPeriodo) {                    
            $dql   = "SELECT pd FROM BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle pd WHERE pd.codigoPeriodoFk = " . $codigoPeriodo;
            return $dql;
        } 
    
    public function generar($codigoPeriodo) {
        $em = $this->getEntityManager();
        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
        $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);        
        $arSucursales = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal();
        $arSucursales = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoSucursal')->findAll();
        foreach ($arSucursales as $arSucursal) {
            $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
            $arPeriodoDetalle->setSsoPeriodoRel($arPeriodo);
            $arPeriodoDetalle->setSsoSucursalRel($arSucursal);
            $em->persist($arPeriodoDetalle);            
        }
        $arPeriodo->setEstadoGenerado(1);
        $em->persist($arPeriodo);
        $em->flush();

        return true;
    }
}