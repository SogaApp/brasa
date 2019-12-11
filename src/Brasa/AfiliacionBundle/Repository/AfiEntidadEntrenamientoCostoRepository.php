<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiEntidadEntrenamientoCostoRepository extends EntityRepository {    
    public function ListaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT eec FROM BrasaAfiliacionBundle:AfiEntidadEntrenamientoCosto eec WHERE eec.codigoEntidadEntrenamientoCostoPk <> 0";
        $dql .= " ORDER BY eec.codigoEntidadEntrenamientoCostoPk";
        return $dql;
    }            

    public function listaDetalleDql($codigoEntidadEntrenamiento) {
        $em = $this->getEntityManager();
        $dql   = "SELECT eec FROM BrasaAfiliacionBundle:AfiEntidadEntrenamientoCosto eec WHERE eec.codigoEntidadEntrenamientoFk = " . $codigoEntidadEntrenamiento;
        return $dql;
    } 
    
    public function costoCursoEntidadEntrenamiento($codigoEntidadEntrenamiento, $codigoCursoTipo) {
        $em = $this->getEntityManager();
        $costo = 0;
        $arEntidadEntrenamientoCosto = new \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamientoCosto();
        $arEntidadEntrenamientoCosto = $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamientoCosto')->findOneBy(array('codigoEntidadEntrenamientoFk' => $codigoEntidadEntrenamiento, 'codigoCursoTipoFk' => $codigoCursoTipo));
        if(count($arEntidadEntrenamientoCosto) > 0) {
            $costo = $arEntidadEntrenamientoCosto->getCosto();
        }
        //$dql   = "SELECT eec FROM BrasaAfiliacionBundle:AfiEntidadEntrenamientoCosto eec WHERE eec.codigoEntidadEntrenamientoFk = " . $codigoEntidadEntrenamiento;
        return $costo;        
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamientoCosto')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }        
}