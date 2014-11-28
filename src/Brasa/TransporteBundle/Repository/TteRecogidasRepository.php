<?php

namespace Brasa\TransporteBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MovimientosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TteRecogidasRepository extends EntityRepository {                
    
    public function ListaRecogidas($boolAnulada, $codigoRecogida, $fechaDesde, $fechaHasta, $codigoTercero = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT recogidas FROM BrasaTransporteBundle:TteRecogidas recogidas WHERE recogidas.codigoRecogidaPk <> 0";

        if($boolAnulada != 1 ) {
            $dql .= " AND recogidas.estadoAnulada = 0";
        }        
        if($codigoRecogida != "" ) {
            $dql .= " AND recogidas.codigoRecogidaPk = " . $codigoRecogida;
        }         
        if($codigoTercero != "" ) {
            $dql .= " AND recogidas.codigoTerceroFk = " . $codigoTercero;
        }        
        if($fechaDesde != "" ) {
            $dql .= " AND recogidas.fechaIngreso >= '" . $fechaDesde->format('Y/m/d') . " 00:00:00'";
        }        
        if($fechaHasta != "" ) {
            $dql .= " AND recogidas.fechaIngreso <= '" . $fechaHasta->format('Y/m/d') . " 23:59:59'";
        }        
        $query = $em->createQuery($dql);        
        return $query;
    }        
    
    public function ListaPendientes() {        
        $em = $this->getEntityManager();
        $dql   = "SELECT recogidas FROM BrasaTransporteBundle:TteRecogidas recogidas "
                . "WHERE recogidas.estadoAsignada = 0";       
        $query = $em->createQuery($dql);        
        return $query;
    }            
    
    public function ListaAsignadas() {        
        $em = $this->getEntityManager();
        $dql   = "SELECT recogidas FROM BrasaTransporteBundle:TteRecogidas recogidas "
                . "LEFT JOIN recogidas.planRecogidaRel planrecogida "
                . "WHERE recogidas.estadoAsignada = 1 AND planrecogida.estadoDescargado = 0";       
        $query = $em->createQuery($dql);        
        return $query;
    }                
}