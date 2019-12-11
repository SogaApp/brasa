<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuSeleccionRequisitoRepository extends EntityRepository {                   
         
    public function eliminarSeleccionRequisitos($arrSeleccionados) {
        $em = $this->getEntityManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoSeleccionRequisito) {
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->find($codigoSeleccionRequisito);                     
                if ($arSeleccion->getEstadoCerrado() == 0){
                    if($em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->devuelveNumeroDetalleRequisito($codigoSeleccionRequisito) <= 0){
                        $em->remove($arSeleccion);  
                    }
                } else {
                    echo "La requisicion " . $codigoSeleccionRequisito . " esta cerrada, no se puede eliminar <br>";
                }                                         
            }
            $em->flush();       
        }     
    }     
    
    public function listaDQL($strNombre = "", $boolCerrado = 2, $strCargo = "", $strDesde = "", $strHasta= "") {                
        $dql   = "SELECT sq FROM BrasaRecursoHumanoBundle:RhuSeleccionRequisito sq WHERE sq.codigoSeleccionRequisitoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND sq.nombre LIKE '%" . $strNombre . "%'";
        }   
        
        if($boolCerrado == 1 ) {
            $dql .= " AND sq.estadoCerrado = 1";
        } elseif($boolCerrado == '0' || $boolCerrado == 0) {
            $dql .= " AND sq.estadoCerrado = 0";
        }            
        
        if($strCargo != "") {
            $dql .= " AND sq.codigoCargoFk = " . $strCargo;
        }
        if($strDesde != "" || $strDesde != 0){
            $dql .= " AND sq.fecha >='" . $strDesde . "'";
        }
        if($strHasta != "" || $strHasta != 0) {
            $dql .= " AND sq.fecha <='" . $strHasta . "'";
        }
         
        $dql .= " ORDER BY sq.codigoSeleccionRequisitoPk DESC";
        return $dql;
    }   
    
    public function listaDetalleDql($strNombre = "", $boolCerrado = 2, $strCargo = "", $strDesde = "", $strHasta= "") {                
        $dql   = "SELECT sra,sr FROM BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante sra JOIN sra.seleccionRequisitoRel sr WHERE sr.codigoSeleccionRequisitoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND sr.nombre LIKE '%" . $strNombre . "%'";
        }   
        
        if($boolCerrado == 1 ) {
            $dql .= " AND sr.estadoCerrado = 1";
        } elseif($boolCerrado == 0 || $boolCerrado == '0') {
            $dql .= " AND sr.estadoCerrado = 0";
        }            
        
        if($strCargo != "") {
            $dql .= " AND sr.codigoCargoFk = " . $strCargo;
        }
        if($strDesde != "" || $strDesde != 0){
            $dql .= " AND sr.fecha >='" . $strDesde . "'";
        }
        if($strHasta != "" || $strHasta != 0) {
            $dql .= " AND sr.fecha <='" . $strHasta . "'";
        }
         
        $dql .= " ORDER BY sr.codigoSeleccionRequisitoPk DESC";
        return $dql;
    }   
    
    // Esta funcion cambiar el estado abierto del requisito (Abierto / Cerrado)
    public function estadoAbiertoSeleccionRequisitos($arrSeleccionados) {
        $em = $this->getEntityManager();
        $estado = true;
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoSeleccion) {                
                $arSeleccionRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito();
                $arSeleccionRequisito = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->find($codigoSeleccion);
                $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->findBy(array('codigoSeleccionRequisitoFk' => $codigoSeleccion));
                if ($arSeleccionRequisito->getEstadoCerrado() == 0){
                    if (count($arSeleccion) > 0){
                        foreach ($arSeleccion AS $arSeleccion) {
                            if ($arSeleccion->getEstadoCerrado() == 0){
                                $estado = false;
                            }
                        }
                        //$em->persist($arSeleccion);
                    }
                    if ($estado == true){
                        $arSeleccionRequisito->setEstadoCerrado(1);
                    } else {
                        echo "No se puede cerrar la requisicion " .$codigoSeleccion. ", tiene procesos de selecciones abiertos! "." <br>";
                    }
                    
                    
                } 
                $em->persist($arSeleccionRequisito);
            }
            $em->flush();       
        }     
    }
    
    public function devuelveNumeroDetalleRequisito($codigoSeleccionRequisito) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoSeleccionPk) FROM BrasaRecursoHumanoBundle:RhuSeleccion s WHERE s.codigoSeleccionRequisitoFk = " . $codigoSeleccionRequisito;
        $query = $em->createQuery($dql);
        $douNumeroDetalleRequisito = $query->getSingleScalarResult();
        return $douNumeroDetalleRequisito;
    }
}
