<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurContratoRepository extends EntityRepository {    
    
    public function listaDql($codigoContrato = '', $codigoCliente = '', $strNombre = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaTurnoBundle:TurContrato c WHERE c.codigoContratoPk <> 0 ";
        if($codigoCliente != "" ) {
            $dql .= " AND c.codigoClienteFk = " . $codigoCliente;
        }
        if($strNombre != "" ) {
            $dql .= " AND c.nombre LIKE '%" . $strNombre . "%'";
        }
        if($codigoContrato != "" ) {
            $dql .= " AND c.codigoContratoPk = " . $codigoContrato;
        }
        //$dql .= " ORDER BY p.nombre";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurContrato')->find($codigo);
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