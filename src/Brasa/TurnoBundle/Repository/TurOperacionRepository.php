<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurOperacionRepository extends EntityRepository {
    
    public function listaDql($codigoOperacion = '', $strNombre = "", $codigoProyecto = '') {
        $em = $this->getEntityManager();
        $dql   = "SELECT o FROM BrasaTurnoBundle:TurOperacion o WHERE o.codigoOperacionPk <> 0 ";
        if($codigoProyecto != "" ) {
            $dql .= " AND o.codigoProyectoFk = " . $codigoProyecto;
        }
        if($strNombre != "" ) {
            $dql .= " AND o.nombre LIKE '%" . $strNombre . "%'";
        }
        if($codigoOperacion != "" ) {
            $dql .= " AND o.codigoOperacionPk = " . $codigoOperacion;
        }
        $dql .= " ORDER BY o.nombre";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurOperacion')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }   
    
    public function liquidar($codigoPuesto) {        
        $em = $this->getEntityManager();        
        $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();        
        $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($codigoPuesto); 
        $costo = 0;
        $arPuestoDotaciones = new \Brasa\TurnoBundle\Entity\TurPuestoDotacion();        
        $arPuestoDotaciones = $em->getRepository('BrasaTurnoBundle:TurPuestoDotacion')->findBy(array('codigoPuestoFk' => $codigoPuesto));         
        foreach ($arPuestoDotaciones as $arPuestoDotacion) {
            $costo += $arPuestoDotacion->getTotal();
        }
        $arPuesto->setCostoDotacion($costo);
        $em->persist($arPuesto);
        $em->flush();
        return true;
    }        
    
}