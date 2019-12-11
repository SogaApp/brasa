<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoDetalleCompuestoRepository extends EntityRepository {

    public function listaDql($codigoPedidoDetalle = "") {
        $dql   = "SELECT pdc FROM BrasaTurnoBundle:TurPedidoDetalleCompuesto pdc WHERE pdc.codigoPedidoDetalleCompuestoPk <> 0 ";
        
        if($codigoPedidoDetalle != '') {
            $dql .= "AND pdc.codigoPedidoDetalleFk = " . $codigoPedidoDetalle . " ";  
        }        
        //$dql .= " ORDER BY pdc.codigoGrupoFacturacionFk, pd.codigoPuestoFk";
        return $dql;
    }        
    
    public function listaConsultaDql($numeroPedido = "", $codigoCliente = "", $boolEstadoAutorizado = "", $boolEstadoProgramado = "", $boolEstadoFacturado = "", $boolEstadoAnulado = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p WHERE pd.codigoPedidoDetallePk <> 0 ";
        if($numeroPedido != "") {
            $dql .= " AND p.numero = " . $numeroPedido;  
        }
        if($codigoCliente != "") {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;  
        } 
        if($boolEstadoProgramado == 1 ) {
            $dql .= " AND pd.estadoProgramado = 1";
        }
        if($boolEstadoProgramado == "0") {
            $dql .= " AND pd.estadoProgramado = 0";
        }  
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND p.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND p.estadoAutorizado = 0";
        }         
        if($boolEstadoFacturado == 1 ) {
            $dql .= " AND pd.estadoFacturado = 1";
        }
        if($boolEstadoFacturado == "0") {
            $dql .= " AND pd.estadoFacturado = 0";
        }        
        if($boolEstadoAnulado == 1 ) {
            $dql .= " AND p.estadoAnulado = 1";
        }
        if($boolEstadoAnulado == "0") {
            $dql .= " AND p.estadoAnulado = 0";
        }
        if($strFechaDesde != "") {
            $dql .= " AND p.fechaProgramacion >= '" . $strFechaDesde . "'";
        }        
        if($strFechaHasta != "") {
            $dql .= " AND p.fechaProgramacion <= '" . $strFechaHasta . "'";
        }      
        $dql .= " ORDER BY p.codigoClienteFk, pd.codigoGrupoFacturacionFk, pd.codigoPuestoFk";        
        return $dql;
    }     
    
    public function listaConsultaPendienteFacturarDql($numeroPedido = "", $codigoCliente = "", $boolEstadoAutorizado = "", $boolEstadoProgramado = "", $boolEstadoFacturado = "", $boolEstadoAnulado = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p WHERE pd.vrTotalDetallePendiente > 0 ";
        if($numeroPedido != "") {
            $dql .= " AND p.numero = " . $numeroPedido;  
        }
        if($codigoCliente != "") {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;  
        } 
        if($boolEstadoProgramado == 1 ) {
            $dql .= " AND pd.estadoProgramado = 1";
        }
        if($boolEstadoProgramado == "0") {
            $dql .= " AND pd.estadoProgramado = 0";
        }  
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND p.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND p.estadoAutorizado = 0";
        }         
        if($boolEstadoFacturado == 1 ) {
            $dql .= " AND pd.estadoFacturado = 1";
        }
        if($boolEstadoFacturado == "0") {
            $dql .= " AND pd.estadoFacturado = 0";
        }        
        if($boolEstadoAnulado == 1 ) {
            $dql .= " AND p.estadoAnulado = 1";
        }
        if($boolEstadoAnulado == "0") {
            $dql .= " AND p.estadoAnulado = 0";
        }
        if($strFechaDesde != "") {
            $dql .= " AND p.fechaProgramacion >= '" . $strFechaDesde . "'";
        }        
        if($strFechaHasta != "") {
            $dql .= " AND p.fechaProgramacion <= '" . $strFechaHasta . "'";
        }      
        $dql .= " ORDER BY p.codigoClienteFk, pd.codigoGrupoFacturacionFk, pd.codigoPuestoFk";        
        return $dql;
    }         
    
    public function pendientesCliente($codigoCliente) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoClienteFk = " . $codigoCliente . " AND pd.estadoProgramado = 0 AND p.estadoAnulado = 0 AND p.estadoAutorizado = 1";
                
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }
    
    public function fecha($strFechaDesde = "", $strFechaHasta = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd "
                . "FROM BrasaTurnoBundle:TurPedidoDetalle pd "
                . "JOIN pd.pedidoRel p "                
                . "WHERE p.fechaProgramacion >= '" . $strFechaDesde . "' AND p.fechaProgramacion <='" . $strFechaHasta . "'";                
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }    
    
    public function listaCliente($codigoCliente, $fechaProgramacion = '', $codigoPuesto = "", $programado = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoClienteFk = " . $codigoCliente . " AND p.estadoAutorizado = 1 AND p.estadoAnulado = 0 ";
        if($fechaProgramacion != '') {
            $dql .= " AND p.fechaProgramacion >= '" . $fechaProgramacion . "'";
        }
        if($programado == 1) {
            $dql .= " AND pd.estadoProgramado = 1";
        }        
        if($programado == '0') {
            $dql .= " AND pd.estadoProgramado = 0";
        }        
        if($codigoPuesto != "" && $codigoPuesto != 0) {
            $dql .= " AND pd.codigoPuestoFk = " . $codigoPuesto;
        }
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }    

    public function listaClienteFecha($codigoCliente, $codigoPuesto = "", $programado = "", $anio = "", $mes = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoClienteFk = " . $codigoCliente . " AND p.estadoAutorizado = 1 AND p.estadoAnulado = 0 ";
        if($anio != '') {
            $dql .= " AND pd.anio >= " . $anio;
        }
        if($mes != '') {
            $dql .= " AND pd.mes >= " . $mes;
        }        
        if($programado == 1) {
            $dql .= " AND pd.estadoProgramado = 1";
        }        
        if($programado == '0') {
            $dql .= " AND pd.estadoProgramado = 0";
        }        
        if($codigoPuesto != "" && $codigoPuesto != 0) {
            $dql .= " AND pd.codigoPuestoFk = " . $codigoPuesto;
        }
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }    
    
    public function pendientesFacturarDql($codigoCliente, $boolMostrarTodo = 0, $numero = '') {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.estadoAutorizado = 1 AND p.estadoAnulado = 0 AND pd.vrTotalDetallePendiente > 0 ";
        if($boolMostrarTodo == 0) {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;
        } 
        if($numero != '') {
            $dql .= " AND p.numero = " . $numero;
        }
        return $dql;                
    }    
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {  
                $arPedidoDetalleCompuesto = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleCompuesto')->find($codigo);                
                $em->remove($arPedidoDetalleCompuesto);                     
            }                                         
            $em->flush();         
        }
        
    }         
    
    public function numeroRegistros($codigo) {
        $em = $this->getEntityManager();
        $arDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigo));
        return count($arDetalles);
    }  
    
    public function validarPuesto($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(pd.codigoPedidoDetallePk) as numeroRegistros FROM BrasaTurnoBundle:TurPedidoDetalle pd "
                . "WHERE pd.codigoPedidoFk = " . $codigo . " AND pd.codigoPuestoFk IS NULL";
        $query = $em->createQuery($dql);
        $arrPedidosDetalles = $query->getSingleResult(); 
        if($arrPedidosDetalles) {
            $intNumeroRegistros = $arrPedidosDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }     
 
    public function actualizarPendienteFacturar($codigoPedidoDetalle) {        
        $em = $this->getEntityManager();
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);
        $dql   = "SELECT SUM(fd.subtotalOperado) as valor FROM BrasaTurnoBundle:TurFacturaDetalle fd JOIN fd.facturaRel f "
                . "WHERE fd.codigoPedidoDetalleFk = " . $codigoPedidoDetalle . " AND f.estadoAutorizado = 1 AND f.afectaValorPedido = 1";
        $query = $em->createQuery($dql);
        $arrFacturaDetalle = $query->getSingleResult(); 
        if($arrFacturaDetalle) {
            $totalAfectado = 0;
            if($arrFacturaDetalle['valor']) {
                $totalAfectado = $arrFacturaDetalle['valor'];
            }
            $arPedidoDetalle->setVrTotalDetalleAfectado($totalAfectado);
            $pendiente = $arPedidoDetalle->getVrSubtotal() - $totalAfectado;
            $arPedidoDetalle->setVrTotalDetallePendiente($pendiente);
            $em->persist($arPedidoDetalle);
        }       
    }  
    
    public function actualizarHorasProgramadas($codigoPedidoDetalle) {        
        $em = $this->getEntityManager();
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);
        $dql   = "SELECT SUM(pd.horas) as horas, SUM(pd.horasDiurnas) as horasDiurnas, SUM(pd.horasNocturnas) as horasNocturnas FROM BrasaTurnoBundle:TurProgramacionDetalle pd JOIN pd.programacionRel p "
                . "WHERE pd.codigoPedidoDetalleFk = " . $codigoPedidoDetalle;
        $query = $em->createQuery($dql);
        $arrProgramacionDetalle = $query->getSingleResult(); 
        if($arrProgramacionDetalle) {
            $horasProgramadas = 0;
            $horasDiurnas = 0;
            $horasNocturnas = 0;
            if($arrProgramacionDetalle['horas']) {
                $horasProgramadas = $arrProgramacionDetalle['horas'];
            }
            if($arrProgramacionDetalle['horasDiurnas']) {
                $horasDiurnas = $arrProgramacionDetalle['horasDiurnas'];
            }
            if($arrProgramacionDetalle['horasNocturnas']) {
                $horasNocturnas = $arrProgramacionDetalle['horasNocturnas'];
            }
            $arPedidoDetalle->setHorasProgramadas($horasProgramadas);
            $arPedidoDetalle->setHorasDiurnasProgramadas($horasDiurnas);
            $arPedidoDetalle->setHorasNocturnasProgramadas($horasNocturnas);
            $em->persist($arPedidoDetalle);
        }       
    }     
    
    public function marcarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo);                
                if($arPedidoDetalle->getMarca() == 1) {
                    $arPedidoDetalle->setMarca(0);
                } else {
                    $arPedidoDetalle->setMarca(1);
                }                
            }                                         
            $em->flush();       
        }
        
    }            
    
    public function ajustarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo);                
                if($arPedidoDetalle->getAjusteProgramacion() == 1) {
                    $arPedidoDetalle->setAjusteProgramacion(0);
                } else {
                    $arPedidoDetalle->setAjusteProgramacion(1);
                }                
            }                                         
            $em->flush();       
        }
        
    }     
    
}