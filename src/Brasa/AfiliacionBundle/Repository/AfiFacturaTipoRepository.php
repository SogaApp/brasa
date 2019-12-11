<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiFacturaTipoRepository extends EntityRepository { 
    
    public function ListaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT ft FROM BrasaAfiliacionBundle:AfiFacturaTipo ft WHERE ft.codigoFacturaTipoPk <> 0";
        $dql .= " ORDER BY ft.nombre";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaTipo')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }        
}