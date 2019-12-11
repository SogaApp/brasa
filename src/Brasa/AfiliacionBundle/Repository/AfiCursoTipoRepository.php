<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiCursoTipoRepository extends EntityRepository { 
    
    public function listaDql($nombre = '') {
        $em = $this->getEntityManager();
        $dql   = "SELECT ct FROM BrasaAfiliacionBundle:AfiCursoTipo ct WHERE ct.codigoCursoTipoPk <> 0 ";
        if($nombre != "" ) {
            $dql .= " AND ct.nombre LIKE '%" . $nombre . "%'";
        }
        $dql .= " ORDER BY ct.nombre";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }        
}