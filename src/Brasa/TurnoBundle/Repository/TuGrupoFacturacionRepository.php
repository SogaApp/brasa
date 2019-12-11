<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurGrupoFacturacionRepository extends EntityRepository {
    
    public function listaDql($codigoProyecto = '', $strNombre = "", $codigoCliente = '') {
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurProyecto p WHERE p.codigoProyectoPk <> 0 ";
        if($codigoCliente != "" ) {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;
        }
        if($strNombre != "" ) {
            $dql .= " AND p.nombre LIKE '%" . $strNombre . "%'";
        }
        if($codigoProyecto != "" ) {
            $dql .= " AND p.codigoProyectoPk = " . $codigoProyecto;
        }
        $dql .= " ORDER BY p.nombre";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurProyecto')->find($codigo);
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