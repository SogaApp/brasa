<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiPeriodoDetallePagoRepository extends EntityRepository {  
    
    public function listaDql($codigoPeriodo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pdp FROM BrasaAfiliacionBundle:AfiPeriodoDetallePago pdp WHERE pdp.codigoPeriodoDetallePagoPk <> 0";
        if($codigoPeriodo != "") {
            $dql .= " AND pdp.codigoPeriodoFk =" . $codigoPeriodo;
        }
        $dql .= " ORDER BY pdp.codigoPeriodoDetallePagoPk";
        return $dql;
    }            
    
    public function listaConsultaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaAfiliacionBundle:AfiPeriodoDetalle pd WHERE pd.codigoPeriodoDetallePk <> 0";
        $dql .= " ORDER BY pd.codigoPeriodoDetallePk";
        return $dql;
    }    
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetallePago')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }
    
    public function empleadoSucursales($codigoPeriodo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pdp.codigoSucursalFk FROM BrasaAfiliacionBundle:AfiPeriodoDetallePago pdp WHERE pdp.codigoPeriodoDetallePagoPk <> 0 ";
        if($codigoPeriodo != "") {
            $dql .= " AND pdp.codigoPeriodoFk = " . $codigoPeriodo ;
        }
        $dql .= " GROUP BY pdp.codigoSucursalFk ";
        return $dql;
    }
        
}