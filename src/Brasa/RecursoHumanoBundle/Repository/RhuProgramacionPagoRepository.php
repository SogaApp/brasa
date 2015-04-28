<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuProgramacionPagoRepository extends EntityRepository {
    /*
     * Liquidar todos los pagos de la programacion de pago
     */
    public function Liquidar($codigoProgramacionPago) {        
        $em = $this->getEntityManager();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);                 
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));         
        $douNeto = 0;
        foreach ($arPagos as $arPago) {
            $douNeto = $douNeto + $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->Liquidar($arPago->getCodigoPagoPk()); 
        }
        $arProgramacionPago->setVrTotalNeto($douNeto);
        $em->persist($arProgramacionPago);
        $em->flush();
        return true;
    } 
}