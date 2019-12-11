<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiNovedadTipoRepository extends EntityRepository { 
    
    public function ListaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT nt FROM BrasaAfiliacionBundle:AfiNovedadTipo nt WHERE nt.codigoNovedadTipoPk <> 0";
        $dql .= " ORDER BY nt.nombre";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiNovedadTipo')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }        
}