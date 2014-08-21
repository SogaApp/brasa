<?php

namespace Brasa\ContabilidadBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CtbBancosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CtbBancosRepository extends EntityRepository
{
    /**
     * Busca los Bancos que coincida con la descripcion enviada con el parametro
     * @param string $strDescripcion La descripcion del producto a buscar
     * @return query el resultado de la busqueda. 
     */
    public function BuscarDescripcionBanco($strDescripcion) {
        try {
            $strDescripcion = str_replace(" ", "%", $strDescripcion);
            
            $em = $this->getEntityManager();
            $query = $em->createQueryBuilder()
                    ->select('banco')
                    ->from('BrasaContabilidadBundle:CtbBancos', 'banco')
                    ->where($em->createQueryBuilder()->expr()->like('banco.nombre', $em->createQueryBuilder()->expr()->literal('%' . $strDescripcion . '%')))
                    ->orWhere($em->createQueryBuilder()->expr()->like('banco.codigoBancoPk', $em->createQueryBuilder()->expr()->literal('%' . $strDescripcion . '%')))
                    ->getQuery();
            $arResultado = $query->getResult();
            return $arResultado;
        } catch (Exception $e) {
            return $e;
        }
    }    
}