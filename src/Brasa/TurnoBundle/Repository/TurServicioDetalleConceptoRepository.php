<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurServicioDetalleConceptoRepository extends EntityRepository {    
    
    public function listaDql($codigoServicio = "") {
        $dql   = "SELECT sdc FROM BrasaTurnoBundle:TurServicioDetalleConcepto sdc WHERE sdc.codigoServicioDetalleConceptoPk <> 0 ";
        
        if($codigoServicio != '') {
            $dql .= "AND sdc.codigoServicioFk = " . $codigoServicio . " ";  
        }               
        return $dql;
    }        

    public function listaClienteDql($codigoCliente = "") {
        $dql   = "SELECT sdc FROM BrasaTurnoBundle:TurServicioDetalleConcepto sdc JOIN sdc.servicioRel s WHERE sdc.codigoServicioDetalleConceptoPk <> 0 ";        
        if($codigoCliente != '') {
            $dql .= "AND s.codigoClienteFk = " . $codigoCliente . " ";  
        }               
        return $dql;
    }
    
    public function eliminar($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                                
                $ar = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleConcepto')->find($codigo);  
                $em->remove($ar);                  
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function numeroRegistros($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(fd.codigoFacturaDetallePk) as numeroRegistros FROM BrasaTurnoBundle:TurFacturaDetalle fd "
                . "WHERE fd.codigoFacturaFk = " . $codigo;
        $query = $em->createQuery($dql);
        $arrFacturaDetalles = $query->getSingleResult(); 
        if($arrFacturaDetalles) {
            $intNumeroRegistros = $arrFacturaDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }          
    
}