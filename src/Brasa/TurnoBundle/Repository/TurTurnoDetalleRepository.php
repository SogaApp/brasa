<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurTurnoDetalleRepository extends EntityRepository {
    
    public function eliminarDetallesSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigo);                
                $em->remove($arProgramacionDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }  
    
    public function numeroRegistros($codigo) {
        $em = $this->getEntityManager();
        $arDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoProgramacionFk' => $codigo));
        return count($arDetalles);
    }  
    
    public function periodo($strFechaDesde, $strFechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurProgramacionDetalle pd JOIN pd.programacionRel p "
                . "WHERE p.fecha >= '" . $strFechaDesde . "' AND p.fecha <='" . $strFechaHasta . "'";
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }    
}