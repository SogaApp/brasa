<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuFacturaRepository extends EntityRepository {
    public function liquidar($codigoFactura) {        
        $em = $this->getEntityManager();
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura); 
        $arFactura->setVrBaseAIU((($arFactura->getVrIngresoMision()+$arFactura->getVrTotalAdministracion())*10)/100);
        $em->persist($arFactura);
        $em->flush();
        return true;
    }        
}