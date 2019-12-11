<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCostoServicioRepository extends EntityRepository {

    public function listaDql($codigoCliente = "", $mes = "") {
        $dql   = "SELECT cs FROM BrasaTurnoBundle:TurCostoServicio cs WHERE cs.codigoCostoServicioPk <> 0 ";
        if($codigoCliente != "") {
            $dql .= " AND cs.codigoClienteFk = " . $codigoCliente;  
        }
        if($mes != "") {
            $dql .= " AND cs.mes = " . $mes;  
        }        
        return $dql;
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {                                
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigo);                    
                if($arSoportePagoPeriodo->getEstadoGenerado() == 0) {
                    $em->remove($arSoportePagoPeriodo);                    
                }                                     
            }
            $em->flush();
        }
    }     

}