<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurServicioDetallePlantillaRepository extends EntityRepository {
    
    public function listaDql($codigoServicioDetalle) {
        $dql   = "SELECT pdp FROM BrasaTurnoBundle:TurServicioDetallePlantilla pdp WHERE pdp.codigoServicioDetalleFk = " . $codigoServicioDetalle;
        $dql .= "";
        return $dql;
    }    
    
    public function eliminar($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $ar = $em->getRepository('BrasaTurnoBundle:TurServicioDetallePlantilla')->find($codigo);                
                $em->remove($ar);                  
            }                                         
            $em->flush();       
        }
        
    }
    
    public function listaConsultaDql() {
        $dql   = "SELECT sdr FROM BrasaTurnoBundle:TurServicioDetalleRecurso sdr WHERE sdr.codigoServicioDetalleRecursoPk <> 0 ";

        /*if($strFechaDesde != '') {
            $dql .= " AND p.fecha >= '" . $strFechaDesde . "'";  
        }
        if($strFechaHasta != '') {
            $dql .= " AND p.fecha <= '" . $strFechaHasta . "'";  
        } 
         * 
         */
        $dql .= " ORDER BY sdr.codigoServicioDetalleFk";
        return $dql;
    }      
}