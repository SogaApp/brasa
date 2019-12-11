<?php

namespace Brasa\CarteraBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CarReciboDetalleRepository extends EntityRepository {
    
    public function detalleConsultaDql($numero = "", $codigoCliente = "", $codigoCuentaCobrarTipo = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT rd FROM BrasaCarteraBundle:CarReciboDetalle rd JOIN rd.reciboRel r  WHERE rd.codigoReciboDetallePk <> 0 ";
        if($numero != "") {
            $dql .= " AND rd.numeroFactura = " . $numero;  
        }
        if($codigoCliente != "") {
            $dql .= " AND r.codigoClienteFk = " . $codigoCliente;  
        }
        if($codigoCuentaCobrarTipo != "") {
            $dql .= " AND rd.codigoCuentaCobrarTipoFk = " . $codigoCuentaCobrarTipo;  
        }
        if ($strFechaDesde != ""){
            $dql .= " AND r.fecha >='" . $strFechaDesde. "'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND r.fecha <='" . $strFechaHasta . "'";
        }        
        return $dql;
    } 
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arReciboDetalle = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->find($codigo);                
                $em->remove($arReciboDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function numeroRegistros($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(rd.codigoReciboDetallePk) as numeroRegistros FROM BrasaCarteraBundle:CarReciboDetalle rd "
                . "WHERE rd.codigoReciboFk = " . $codigo;
        $query = $em->createQuery($dql);
        $arrReciboDetalles = $query->getSingleResult(); 
        if($arrReciboDetalles) {
            $intNumeroRegistros = $arrReciboDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }  

    public function liquidar($codigoRecibo) {        
        $em = $this->getEntityManager();        
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();        
        $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo); 
        $intCantidad = 0;
        $floValor = 0;
        $floValorPago = 0;
        $floDescuento = 0;
        $floAjustePeso = 0;
        $floReteIca = 0;
        $floReteIva = 0;
        $floReteFuente = 0;
        $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo);         
        $arRecibosDetalle = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();        
        $arRecibosDetalle = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => $codigoRecibo));         
        foreach ($arRecibosDetalle as $arReciboDetalle) {         
            $floDescuento += $arReciboDetalle->getVrDescuento();
            $floAjustePeso += $arReciboDetalle->getVrAjustePeso();
            $floReteIca += $arReciboDetalle->getVrReteIca();
            $floReteIva += $arReciboDetalle->getVrReteIva();
            $floReteFuente += $arReciboDetalle->getVrReteFuente();
            if($arReciboDetalle->getCuentaCobrarRel()){
            $floValor += $arReciboDetalle->getValor();
            $floValorPago += $arReciboDetalle->getVrPagoDetalle();
            }else{
                $floValorPago -= $arReciboDetalle->getVrPagoDetalle();
            }

        }
        if($arRecibo->getVrSaldoFavor() > 0){
            $floValorPago = $floValorPago + $arRecibo->getVrSaldoFavor();
        }
        $arRecibo->setVrTotal($floValor);
        $arRecibo->setVrTotalPago($floValorPago);
        $arRecibo->setVrTotalDescuento($floDescuento);
        $arRecibo->setVrTotalAjustePeso($floAjustePeso);
        $arRecibo->setVrTotalReteIca($floReteIca);
        $arRecibo->setVrTotalReteIva($floReteIva);
        $arRecibo->setVrTotalReteFuente($floReteFuente);
        $em->persist($arRecibo);
        $em->flush();
        return true;
    }
    
    public function validarCuenta($codigoCuenta, $codigoRecibo) {        
        $em = $this->getEntityManager();
        $boolValidar = TRUE;        
        $dql   = "SELECT COUNT(rd.codigoReciboDetallePk) as numeroRegistros FROM BrasaCarteraBundle:CarReciboDetalle rd "
                . "WHERE rd.codigoCuentaCobrarFk = " . $codigoCuenta . " AND rd.codigoReciboFk = " . $codigoRecibo;
        $query = $em->createQuery($dql);
        $arrReciboDetalles = $query->getSingleResult(); 
        if($arrReciboDetalles) {
            $intNumeroRegistros = $arrReciboDetalles['numeroRegistros'];
            if($intNumeroRegistros > 0) {
                $boolValidar = FALSE;
            }
        }
        return $boolValidar;
    } 

}