<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurServicioDetalleCompuestoRepository extends EntityRepository {

    public function listaDql($codigoServicioDetalle = "") {
        $dql   = "SELECT sdc FROM BrasaTurnoBundle:TurServicioDetalleCompuesto sdc WHERE sdc.codigoServicioDetalleCompuestoPk <> 0 ";                               
        if($codigoServicioDetalle != '') {
            $dql .= " AND sdc.codigoServicioDetalleFk = " . $codigoServicioDetalle;  
        }                 
        return $dql;
    }    
    
    public function listaConsultaDql($codigoServicio = "", $codigoCliente = "", $estadoCerrado, $fechaHasta = "") {
        $dql   = "SELECT sd FROM BrasaTurnoBundle:TurServicioDetalle sd JOIN sd.servicioRel s WHERE sd.codigoServicioDetallePk <> 0 ";                
        if($codigoCliente != '') {
            $dql .= "AND s.codigoClienteFk = " . $codigoCliente;  
        }
        if($estadoCerrado == 1 ) {
            $dql .= " AND sd.estadoCerrad0 = 1";
        }
        if($estadoCerrado == "0") {
            $dql .= " AND sd.estadoCerrado = 0";
        } 
        if($fechaHasta != "") {
            $dql .= " AND sd.fechaHasta >= '" . $fechaHasta . "'";
        }         
        $dql .= " ORDER BY s.codigoClienteFk, sd.codigoGrupoFacturacionFk, sd.codigoPuestoFk";
        return $dql;
    }     
    
    public function pendientesCliente($codigoCliente) {
        $em = $this->getEntityManager();
        $dql   = "SELECT sd FROM BrasaTurnoBundle:TurServicioDetalle sd JOIN sd.servicioRel s "
                . "WHERE s.codigoClienteFk = " . $codigoCliente . " AND s.estadoCerrado = 0";
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {
                $arServicioDetalleCompuesto = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleCompuesto')->find($codigo);                
                $em->remove($arServicioDetalleCompuesto);                                                      
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function cerrarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigo);                
                if($arServicioDetalle->getEstadoCerrado() == 0) {
                    $arServicioDetalle->setEstadoCerrado(1);
                }              
            }                                         
            $em->flush();       
        }
        
    }    
    
    public function AbrirSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigo);                
                if($arServicioDetalle->getEstadoCerrado() == 1) {
                    $arServicioDetalle->setEstadoCerrado(0);
                }              
            }                                         
            $em->flush();       
        }
        
    } 
    
    public function marcarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigo);                
                if($arServicioDetalle->getMarca() == 1) {
                    $arServicioDetalle->setMarca(0);
                } else {
                    $arServicioDetalle->setMarca(1);
                }                
            }                                         
            $em->flush();       
        }
        
    }            
    
    public function ajustarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigo);                
                if($arServicioDetalle->getAjusteProgramacion() == 1) {
                    $arServicioDetalle->setAjusteProgramacion(0);
                } else {
                    $arServicioDetalle->setAjusteProgramacion(1);
                }                
            }                                         
            $em->flush();       
        }
        
    }                
    
    public function numeroRegistros($codigo) {
        $em = $this->getEntityManager();
        $arDetalles = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $codigo));
        return count($arDetalles);
    }          
    
}