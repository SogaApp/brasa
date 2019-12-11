<?php

namespace Brasa\AdministracionDocumentalBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AdDirectorioRepository extends EntityRepository {
    public function devolverDirectorio() {   
        $em = $this->getEntityManager();
        $arDirectorio = new \Brasa\AdministracionDocumentalBundle\Entity\AdDirectorio();
        $arDirectorio = $em->getRepository('BrasaAdministracionDocumentalBundle:AdDirectorio')->find(1);
        $arDirectorio->setNumeroArchivos($arDirectorio->getNumeroArchivos() + 1);                
        $em->persist($arDirectorio);
        $em->flush();
        return $arDirectorio;
    }                                
    
}