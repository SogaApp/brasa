<?php

namespace Brasa\TransporteBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MovimientosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TteDespachoRepository extends EntityRepository {

    public function Generar($codigoDespacho) {        
        $em = $this->getEntityManager();
        $arDespacho = new \Brasa\TransporteBundle\Entity\TteDespacho();
        $arDespacho = $em->getRepository('BrasaTransporteBundle:TteDespacho')->find($codigoDespacho);
        $arDespacho->setEstadoGenerado(1);
        $em->persist($arDespacho);
        $em->flush(); 
        return "";
    }    
    
    public function ListaDespachos($boolMostrarDescargados, $boolMostrarAnulados, $codigoDespacho, $numeroDespacho, $fechaDesde, $fechaHasta) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT despachos FROM BrasaTransporteBundle:TteDespacho despachos WHERE despachos.codigoDespachoPk <> 0";
        if($boolMostrarDescargados != 1 ) {
            $dql .= " AND despachos.estadoDescargado = 0";
        }
        if($boolMostrarAnulados != 1 ) {
            $dql .= " AND despachos.estadoAnulado = 0";
        }        
        if($codigoDespacho != "" ) {
            $dql .= " AND despachos.codigoDespachoPk = " . $codigoDespacho;
        }        
        if($numeroDespacho != "" ) {
            $dql .= " AND despachos.numeroDespacho = " . $numeroDespacho;
        }  
        if($fechaDesde != "" ) {
            $dql .= " AND despachos.fecha >= '" . $fechaDesde->format('Y/m/d') . " 00:00:00'";
        }        
        if($fechaHasta != "" ) {
            $dql .= " AND despachos.fecha <= '" . $fechaHasta->format('Y/m/d') . " 23:59:59'";
        }        
        $query = $em->createQuery($dql);        
        return $query;
    }            
}