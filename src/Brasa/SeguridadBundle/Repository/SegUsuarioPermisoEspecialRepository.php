<?php

namespace Brasa\SeguridadBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SegUsuarioPermisoEspecialRepository extends EntityRepository {

    public function permisoEspecial($codigoUsuario, $codigoPermisoEspecial) {
        $em = $this->getEntityManager();
        $boolPermiso = false;
        $arUsuarioPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
        $arUsuarioPermisoEspecial = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->findOneBy(array('codigoUsuarioFk' => $codigoUsuario, 'codigoPermisoEspecialFk' => $codigoPermisoEspecial));
        if(count($arUsuarioPermisoEspecial) > 0) {
            if($arUsuarioPermisoEspecial->getPermitir() == 1) {
                $boolPermiso = true;
            }
        }        
        return $boolPermiso;        
    }
}