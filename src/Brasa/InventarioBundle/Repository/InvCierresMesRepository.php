<?php

namespace Brasa\InventarioBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CierresMesInventarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvCierresMesRepository extends EntityRepository
{
    public function UltimoCierre() {
        $objRepositorio = $this->getEntityManager()->getRepository('BrasaInventarioBundle:InvCierresMes');              
        $objQuery = $objRepositorio->createQueryBuilder('inv_cierres_mes')
                ->select('cm')
                ->from('BrasaInventarioBundle:InvCierresMes', 'cm')                
                ->where('cm.estadoCerrado = 1')
                ->orderBy('cm.annio', 'DESC')
                ->orderBy('cm.mes', 'DESC')                
                ->getQuery();            
                $objQuery->setMaxResults(1);                
        return $objQuery->getResult();
        
    }
}