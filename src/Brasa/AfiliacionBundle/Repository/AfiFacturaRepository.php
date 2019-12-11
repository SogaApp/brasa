<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiFacturaRepository extends EntityRepository {  
    
    public function listaDql($codigoCliente = "", $boolEstadoAutorizado = "", $boolEstadoAnulado = "", $boolEstadoAfiliacion = "", $strFechaDesde = "", $strFechaHasta = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT f FROM BrasaAfiliacionBundle:AfiFactura f WHERE f.codigoFacturaPk <> 0";
        /*if($numero != "") {
            $dql .= " AND c.numero = " . $numero;  
        }*/        
        if($codigoCliente != "") {
            $dql .= " AND f.codigoClienteFk = " . $codigoCliente;  
        }          
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND f.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND f.estadoAutorizado = 0";
        }
        if($boolEstadoAnulado == 1 ) {
            $dql .= " AND f.estadoAnulado = 1";
        }
        if($boolEstadoAnulado == "0") {
            $dql .= " AND f.estadoAnulado = 0";
        }
        if($boolEstadoAfiliacion == 1 ) {
            $dql .= " AND f.afiliacion = 1";
        }
        if($boolEstadoAfiliacion == "0") {
            $dql .= " AND f.afiliacion = 0";
        }           
        if($strFechaDesde != "") {
            $dql .= " AND f.fecha >= '" . $strFechaDesde . "'";
        }        
        if($strFechaHasta != "") {
            $dql .= " AND f.fecha <= '" . $strFechaHasta . "'";
        }        
        $dql .= " ORDER BY f.fecha DESC";
        return $dql;
    }                        
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }             

    public function liquidar($codigoFactura) {        
        $em = $this->getEntityManager();        
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();        
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);                                 
        $subtotal = 0;        
        $iva = 0;        
        $total = 0;
        $interesMora = 0;
        $floCurso = 0;        
        $arFacturasDetalle = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle();        
        $arFacturasDetalle = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));                 
        foreach ($arFacturasDetalle as $arFacturaDetalle) {
            $subtotal +=  $arFacturaDetalle->getSubtotal();
            $iva += $arFacturaDetalle->getIva();
            $total +=  $arFacturaDetalle->getTotal();
            $interesMora += $arFacturaDetalle->getInteresMora();
        }                           
        
        $arFacturasDetalleCursos = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso();        
        $arFacturasDetalleCursos = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleCurso')->findBy(array('codigoFacturaFk' => $codigoFactura));                 
        foreach ($arFacturasDetalleCursos as $arFacturasDetalleCurso) {
            $total +=  $arFacturasDetalleCurso->getPrecio();
            $floCurso +=  $arFacturasDetalleCurso->getPrecio();
        }        
        $arFactura->setCurso($floCurso);
        $arFactura->setSubTotal($subtotal);
        $arFactura->setIva($iva);
        $arFactura->setInteresMora($interesMora);
        $arFactura->setTotal($total);
        $em->persist($arFactura);
        $em->flush();
        return true;
    }
    
    public function liquidarAfiliacion($codigoFactura) {        
        $em = $this->getEntityManager();        
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();        
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);                                 
        $subtotal = 0;        
        $iva = 0;        
        $total = 0;
        $floAfiliacion = 0;        
        $arFacturasDetalleAfiliacion = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion();        
        $arFacturasDetalleAfiliacion = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleAfiliacion')->findBy(array('codigoFacturaFk' => $codigoFactura));                 
        foreach ($arFacturasDetalleAfiliacion as $arFacturaDetalle) {
            //$subtotal +=  $arFacturaDetalle->getSubtotal();
            //$iva += $arFacturaDetalle->getIva();
            $total +=  $arFacturaDetalle->getPrecio();
        }                           
        
        /*$arFacturasDetalleCursos = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso();        
        $arFacturasDetalleCursos = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleCurso')->findBy(array('codigoFacturaFk' => $codigoFactura));                 
        foreach ($arFacturasDetalleCursos as $arFacturasDetalleCurso) {
            $total +=  $arFacturasDetalleCurso->getPrecio();
            $floCurso +=  $arFacturasDetalleCurso->getPrecio();
        }*/        
        //$arFactura->setCurso($floCurso);
        //$arFactura->setSubTotal($subtotal);
        //$arFactura->setIva($iva);
        $arFactura->setSubtotal($total);
        $arFactura->setTotal($total);
        $em->persist($arFactura);
        $em->flush();
        return true;
    }

    public function anular($codigoFactura) {        
        $em = $this->getEntityManager();        
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();                   
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);                 
        $arFacturasDetalle = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle();        
        $arFacturasDetalle = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));                 
        foreach ($arFacturasDetalle as $arFacturaDetalle) {
            $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
            $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($arFacturaDetalle->getCodigoPeriodoFk());
            $arPeriodo->setEstadoFacturado(0);
            $em->persist($arPeriodo);
            $arFacturaDetalleAct = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle();
            $arFacturaDetalleAct = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->find($arFacturaDetalle->getCodigoFacturaDetallePk());
            $arFacturaDetalleAct->setAdministracion(0);
            $arFacturaDetalleAct->setCaja(0);
            $arFacturaDetalleAct->setIcbf(0);
            $arFacturaDetalleAct->setSena(0);
            $arFacturaDetalleAct->setRiesgos(0);
            $arFacturaDetalleAct->setSalud(0);
            $arFacturaDetalleAct->setPension(0);
            $arFacturaDetalleAct->setSubtotal(0);
            $arFacturaDetalleAct->setTotal(0);
            $arFacturaDetalleAct->setIva(0);
            $em->persist($arFacturaDetalleAct);
        }                                           
        
        $arFacturasDetalleCursos = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso();        
        $arFacturasDetalleCursos = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleCurso')->findBy(array('codigoFacturaFk' => $codigoFactura));                 
        foreach ($arFacturasDetalleCursos as $arFacturasDetalleCurso) {
            $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();        
            $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($arFacturasDetalleCurso->getCodigoCursoFk());                             
            $arCurso->setEstadoFacturado(0);
            $em->persist($arCurso);
        }
        
        $arFacturasDetalleAfiliaciones = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion();        
        $arFacturasDetalleAfiliaciones = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleAfiliacion')->findBy(array('codigoFacturaFk' => $codigoFactura));                 
        foreach ($arFacturasDetalleAfiliaciones as $arFacturasDetalleAfiliacion) {
            $arContrato = new \Brasa\AfiliacionBundle\Entity\AfiContrato();        
            $arContrato = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->find($arFacturasDetalleAfiliacion->getCodigoContratoFk());                             
            $arContrato->setEstadoGeneradoCtaCobrar(0);
            $em->persist($arContrato);
        }
        
        $arFactura->setEstadoAnulado(1);
        $arFactura->setCurso(0);
        $arFactura->setSubTotal(0);
        $arFactura->setTotal(0);
        $em->persist($arFactura);
        //Anular cuenta por cobrar        
        if($arFactura->getCodigoFacturaTipoFk() == 1) {
            $tipoCuentaCobrar = 3;
        } else {
            $tipoCuentaCobrar = 4;
        }
        $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
        $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->findOneBy(array('codigoCuentaCobrarTipoFk' => $tipoCuentaCobrar, 'numeroDocumento' => $arFactura->getNumero()));
        if($arCuentaCobrar) {
            $arCuentaCobrar->setSaldo(0);
            $arCuentaCobrar->setValorOriginal(0);
            $arCuentaCobrar->setAbono(0);
            $em->persist($arCuentaCobrar);
        }
        $em->flush();
        return "";
    }    
    
    public function autorizar($codigoFactura) {
        $em = $this->getEntityManager();                
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);            
        $strResultado = "";        
        if($arFactura->getEstadoAutorizado() == 0) {            
            $facturaDetalles = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));
            $facturaDetallesCursos = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleCurso')->findBy(array('codigoFacturaFk' => $codigoFactura)); 
            $facturaDetallesAfiliaciones = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleAfiliacion')->findBy(array('codigoFacturaFk' => $codigoFactura)); 
            if ($facturaDetalles != null && $facturaDetallesCursos != null && $facturaDetallesAfiliaciones != null){
                $strResultado = 'No pueden haber detalles de seguridad social, cursos y afiliaciones';
            }
            if ($facturaDetalles == null && $facturaDetallesCursos == null && $facturaDetallesAfiliaciones == null && $arFactura->getFacturaTipoRel()->getCodigoFacturaTipoPk() != 3){
               $strResultado = 'La factura no tiene detalles' ;
            } else {
                if($strResultado == '') {
                    $arFactura->setEstadoAutorizado(1);
                    $em->persist($arFactura);
                    $em->flush();                              
                }
            }    
        } else {
            $strResultado = "Ya esta autorizado";
        }        
        return $strResultado;
    } 
    
    public function desAutorizar($codigoFactura) {
        $em = $this->getEntityManager();                
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);            
        $strResultado = "";        
        if($arFactura->getEstadoAutorizado() == 1 && $arFactura->getEstadoAnulado() == 0 && $arFactura->getNumero() == 0) {                                            
            $arFactura->setEstadoAutorizado(0);
            $em->persist($arFactura);
            $em->flush();                                                        
        } else {
            $strResultado = "El factura debe estar autorizado y no puede estar anulada o impresa";
        }        
        return $strResultado;
    }    
    
    public function imprimir($codigoFactura) {
        $em = $this->getEntityManager();                
        $strResultado = "";
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();        
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);        
        if($arFactura->getEstadoAutorizado() == 1) {
            if($arFactura->getNumero() == 0) {            
                if($arFactura->getCodigoFacturaTipoFk() == 1) {
                    $intNumero = $em->getRepository('BrasaAfiliacionBundle:AfiConsecutivo')->consecutivo(2);
                    $arCuentaCobrarTipo = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrarTipo')->find(3);
                } 
                if($arFactura->getCodigoFacturaTipoFk() == 2) {
                    $intNumero = $em->getRepository('BrasaAfiliacionBundle:AfiConsecutivo')->consecutivo(3);
                    $arCuentaCobrarTipo = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrarTipo')->find(4);
                }
                if($arFactura->getCodigoFacturaTipoFk() == 3){
                    $intNumero = $em->getRepository('BrasaAfiliacionBundle:AfiConsecutivo')->consecutivo(5);
                    $arCuentaCobrarTipo = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrarTipo')->find(5);
                }
                $arFactura->setNumero($intNumero);
                
                $arClienteAfiliacion = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
                $arClienteAfiliacion = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->find($arFactura->getCodigoClienteFk());                 
                $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                $arClienteCartera = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arClienteAfiliacion->getNit()));                 
                if ($arClienteCartera == null){
                    $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                    $arClienteCartera->setFormaPagoRel($arClienteAfiliacion->getFormaPagoRel());
                    $arClienteCartera->setAsesorRel($arClienteAfiliacion->getAsesorRel());
                    $arClienteCartera->setCiudadRel($arClienteAfiliacion->getCiudadRel());
                    $arClienteCartera->setNit($arClienteAfiliacion->getNit());
                    $arClienteCartera->setDigitoVerificacion($arClienteAfiliacion->getDigitoVerificacion());
                    $arClienteCartera->setNombreCorto($arClienteAfiliacion->getNombreCorto());
                    $arClienteCartera->setPlazoPago($arClienteAfiliacion->getPlazoPago());
                    $arClienteCartera->setDireccion($arClienteAfiliacion->getDireccion());
                    $arClienteCartera->setTelefono($arClienteAfiliacion->getTelefono());
                    $arClienteCartera->setCelular($arClienteAfiliacion->getCelular());
                    $arClienteCartera->setFax($arClienteAfiliacion->getFax());
                    $arClienteCartera->setEmail($arClienteAfiliacion->getEmail());                    
                    $em->persist($arClienteCartera);                                    
                }                
                $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();                                
                $arCuentaCobrar->setClienteRel($arClienteCartera);
                $arCuentaCobrar->setAsesorRel($arClienteAfiliacion->getAsesorRel());
                $arCuentaCobrar->setCuentaCobrarTipoRel($arCuentaCobrarTipo);
                $arCuentaCobrar->setFecha($arFactura->getFecha());
                $arCuentaCobrar->setFechaVence($arFactura->getFechaVence());
                $arCuentaCobrar->setNumeroDocumento($arFactura->getNumero());
                $arCuentaCobrar->setCodigoFactura($arFactura->getCodigoFacturaPk());
                $arCuentaCobrar->setSoporte($arFactura->getSoporte());
                $arCuentaCobrar->setValorOriginal($arFactura->getTotal());
                $arCuentaCobrar->setSaldo($arFactura->getTotal());
                $arCuentaCobrar->setPlazo($arClienteAfiliacion->getPlazoPago());                
                $arCuentaCobrar->setAbono(0);
                if ($arFactura->getAfiliacion() == 1){
                    $arCuentaCobrar->setAfiliacion(1);
                }
                $em->persist($arCuentaCobrar);                
            }              
            $em->persist($arFactura);
            $em->flush();
        } else {
            $strResultado = "Debe autorizar la factura para imprimir";
        }
        return $strResultado;
    }            
}