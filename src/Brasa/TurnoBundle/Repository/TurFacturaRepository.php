<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurFacturaRepository extends EntityRepository {

    public function listaDql($numeroFactura = "", $codigoCliente = "", $boolEstadoAutorizado = "", $strFechaDesde = "", $strFechaHasta = "", $boolEstadoAnulado = "", $codigoFacturaTipo = "") {
        $dql   = "SELECT f FROM BrasaTurnoBundle:TurFactura f WHERE f.codigoFacturaPk <> 0";
        if($numeroFactura != "") {
            $dql .= " AND f.numero = " . $numeroFactura;
        }
        if($codigoCliente != "") {
            $dql .= " AND f.codigoClienteFk = " . $codigoCliente;
        }
        if($codigoFacturaTipo != "") {
            $dql .= " AND f.codigoFacturaTipoFk = " . $codigoFacturaTipo;
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
        if($strFechaDesde != "") {
            $dql .= " AND f.fecha >= '" . $strFechaDesde . " 00:00:00'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND f.fecha <= '" . $strFechaHasta . " 23:59:59'";
        }
        $dql .= " ORDER BY f.codigoFacturaTipoFk, f.fecha DESC, f.numero DESC";
        return $dql;
    }

    public function listaFechaDql($fechaDesde = "", $fechaHasta = "", $numeroDesde = "", $numeroHasta = "") {
        $dql   = "SELECT f FROM BrasaTurnoBundle:TurFactura f WHERE f.codigoFacturaPk <> 0";
        if($numeroDesde != "" && $numeroHasta != "") {            
            $dql .= " AND f.numero >= " . $numeroDesde . " AND f.numero <= " . $numeroHasta;            
        } 
        if($fechaDesde != "" && $fechaHasta != "") {            
            $dql .= " AND f.fecha >= '" . $fechaDesde->format('Y-m-d') . " 00:00:00' AND f.fecha <= '" . $fechaHasta->format('Y-m-d') . " 23:59:59'";                            
        }

        
        $dql .= " ORDER BY f.codigoFacturaTipoFk, f.fecha DESC, f.numero DESC";
        return $dql;
    }    
    
    public function listaPendienteContabilizarDql($numeroFactura = "", $codigoCliente = "", $boolEstadoAutorizado = "", $strFechaDesde = "", $strFechaHasta = "", $boolEstadoAnulado = "") {
        $dql   = "SELECT f FROM BrasaTurnoBundle:TurFactura f WHERE f.estadoContabilizado = 0 AND f.numero > 0";
        if($numeroFactura != "") {
            $dql .= " AND f.numero = " . $numeroFactura;
        }
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
        if($strFechaDesde != "") {
            $dql .= " AND f.fecha >= '" . $strFechaDesde . " 00:00:00'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND f.fecha <= '" . $strFechaHasta . " 23:59:59'";
        }
        $dql .= " ORDER BY f.codigoFacturaTipoFk, f.fecha DESC, f.numero DESC";
        return $dql;
    }

    public function pedidoMaestroDql() {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoTipoFk = 2";
        return $dql;
    }

    public function pedidoSinProgramarDql($strFechaDesde = '', $strFechaHasta = '') {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoTipoFk = 1 "
                . "AND p.programado = 0 ";

        if($strFechaDesde != '') {
            $dql .= " AND p.fecha >= '" . $strFechaDesde . "'";
        }
        if($strFechaHasta != '') {
            $dql .= " AND p.fecha <= '" . $strFechaHasta . "'";
        }
        return $dql;
    }

    public function liquidar($codigoFactura) {
        $em = $this->getEntityManager();
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $subtotal = 0;
        $iva = 0;
        $baseIva = 0;
        $total = 0;
        $retencionFuente = 0;
        
        $arFacturasDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        $arFacturasDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));
        foreach ($arFacturasDetalle as $arFacturaDetalle) {
            $arFacturasDetalleAct = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
            $arFacturasDetalleAct = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($arFacturaDetalle->getCodigoFacturaDetallePk());
            $subtotalDetalle = $arFacturaDetalle->getVrPrecio() * $arFacturaDetalle->getCantidad();
            $subtotalDetalle = $subtotalDetalle;
            $baseIvaDetalle = ($subtotalDetalle * $arFacturaDetalle->getPorBaseIva()) / 100;
            $baseIvaDetalle = $baseIvaDetalle;
            $ivaDetalle = ($baseIvaDetalle * $arFacturaDetalle->getPorIva()) / 100;
            $ivaDetalle = $ivaDetalle;
            $totalDetalle = $subtotalDetalle + $ivaDetalle;
            $totalDetalle = $totalDetalle;
            $arFacturasDetalleAct->setOperacion($arFactura->getOperacion());
            $arFacturasDetalleAct->setSubtotal($subtotalDetalle);
            $arFacturasDetalleAct->setSubtotalOperado($subtotalDetalle * $arFacturasDetalleAct->getOperacion());

            $arFacturasDetalleAct->setBaseIva($baseIvaDetalle);
            $arFacturasDetalleAct->setIva($ivaDetalle);
            $arFacturasDetalleAct->setTotal($totalDetalle);
            $em->persist($arFacturasDetalleAct);

            $subtotal += $subtotalDetalle;
            $iva += $ivaDetalle;
            $baseIva += $baseIvaDetalle;
            $total += $totalDetalle;
        }
        
        $porRetencionFuente = $arFactura->getFacturaServicioRel()->getPorRetencionFuente();
        $porBaseRetencionFuente = $arFactura->getFacturaServicioRel()->getPorBaseRetencionFuente();
        $baseRetencionFuente = ($subtotal * $porBaseRetencionFuente) / 100;
        $baseRetencionFuente = $baseRetencionFuente;
        if($baseRetencionFuente >= $arConfiguracion->getBaseRetencionFuente()) {
            $retencionFuente = ($baseRetencionFuente * $porRetencionFuente ) / 100;
        }
        $retencionFuente = $retencionFuente;
        $totalNeto = $subtotal + $iva - $retencionFuente;
        $arFactura->setVrBaseAIU($baseIva);
        $arFactura->setVrBaseRetencionFuente($baseRetencionFuente);
        $arFactura->setVrSubtotal($subtotal);
        $arFactura->setVrSubtotalOperado($subtotal * $arFactura->getOperacion());
        //$arFactura->setVrSubtotalOtros($floSubTotalConceptos);
        $arFactura->setVrRetencionFuente($retencionFuente);
        $arFactura->setVrIva($iva);
        $arFactura->setvrTotal($total);
        $arFactura->setVrTotalNeto($totalNeto);
        $em->persist($arFactura);
        $em->flush();
        return true;
    }

    public function festivo($arFestivos, $dateFecha) {
        $boolFestivo = 0;
        foreach ($arFestivos as $arFestivo) {
            if($arFestivo['fecha'] == $dateFecha) {
                $boolFestivo = 1;
            }
        }
        return $boolFestivo;
    }

    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                if($em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->numeroRegistros($codigo) <= 0) {
                    $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigo);
                    if($arFactura->getEstadoAutorizado() == 0 && $arFactura->getNumero() == 0) {
                        $em->remove($arFactura);
                    }
                }
            }
            $em->flush();
        }
    }

    public function autorizar($codigoFactura) {
        $em = $this->getEntityManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $strResultado = "";
        if($arFactura->getEstadoAutorizado() == 0) {

            if($arFactura->getFacturaTipoRel()->getTipo() == 1) {

                // Validar valor pendiente
                $dql   = "SELECT fd.codigoPedidoDetalleFk, SUM(fd.subtotalOperado) as vrPrecio FROM BrasaTurnoBundle:TurFacturaDetalle fd "
                        . "WHERE fd.codigoFacturaFk = " . $codigoFactura . " "
                        . "GROUP BY fd.codigoPedidoDetalleFk";
                $query = $em->createQuery($dql);
                $arrFacturaDetalles = $query->getResult();
                foreach ($arrFacturaDetalles as $arrFacturaDetalle) {
                    if($arrFacturaDetalle['codigoPedidoDetalleFk']) {
                        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arrFacturaDetalle['codigoPedidoDetalleFk']);
                        $floPrecio = $arrFacturaDetalle['vrPrecio'];
                        if(round($arPedidoDetalle->getVrTotalDetallePendiente()) < round($floPrecio)) {
                            $strResultado .= "Para el detalle de pedido " . $arrFacturaDetalle['codigoPedidoDetalleFk'] . " no puede facturar mas de lo pendiente valor a facturar = " . $floPrecio . " valor pendiente = " . $arPedidoDetalle->getVrTotalDetallePendiente();
                        }
                    }
                }
            }

            if($strResultado == "") {                
                if($arFactura->getAfectaValorPedido() == 1) {
                    $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                    $arFacturaDetalles = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));
                    foreach ($arFacturaDetalles as $arFacturaDetalle) {
                        if($arFacturaDetalle->getCodigoPedidoDetalleFk()) {

                                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arFacturaDetalle->getCodigoPedidoDetalleFk());
                                $floValorTotalPendiente = $arPedidoDetalle->getVrTotalDetallePendiente() - $arFacturaDetalle->getSubtotalOperado();
                                $arPedidoDetalle->setVrTotalDetallePendiente($floValorTotalPendiente);                            
                                $floValorTotalAfectado = $arPedidoDetalle->getVrTotalDetalleAfectado() + $arFacturaDetalle->getSubtotalOperado();
                                $arPedidoDetalle->setVrTotalDetalleAfectado($floValorTotalAfectado);
                                if($floValorTotalPendiente <= 0) {
                                    $arPedidoDetalle->setEstadoFacturado(1);
                                }
                                $em->persist($arPedidoDetalle);                            
                        }                                                
                    }
                }                
                $arFactura->setEstadoAutorizado(1);
                $em->persist($arFactura);
                $em->flush();
            }
        } else {
            $strResultado = "Ya esta autorizado";
        }
        return $strResultado;
    }

    public function desAutorizar($codigoFactura) {
        $em = $this->getEntityManager();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $strResultado = "";
        if($arFactura->getEstadoAutorizado() == 1 && $arFactura->getEstadoAnulado() == 0 && $arFactura->getNumero() == 0) {
            if($arFactura->getAfectaValorPedido() == 1) {
                $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                $arFacturaDetalles = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));
                foreach ($arFacturaDetalles as $arFacturaDetalle) {
                    if($arFacturaDetalle->getCodigoPedidoDetalleFk()) {                    
                        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arFacturaDetalle->getCodigoPedidoDetalleFk());                        
                        $floValorTotalPendiente = $arPedidoDetalle->getVrTotalDetallePendiente() + $arFacturaDetalle->getSubtotalOperado();
                        $arPedidoDetalle->setVrTotalDetallePendiente($floValorTotalPendiente);
                        $floValorTotalAfectado = $arPedidoDetalle->getVrTotalDetalleAfectado() - $arFacturaDetalle->getSubtotalOperado();
                        $arPedidoDetalle->setVrTotalDetalleAfectado($floValorTotalAfectado);
                        $arPedidoDetalle->setEstadoFacturado(0);
                        $em->persist($arPedidoDetalle);                        
                    }
                }
            }
            $arFactura->setEstadoAutorizado(0);
            $em->persist($arFactura);
            $em->flush();
        } else {
            $strResultado = "La factura debe estas autorizada y no puede estar anulada o impresa";
        }
        return $strResultado;
    }

    public function imprimir($codigoFactura) {
        $em = $this->getEntityManager();

        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $strResultado = "";
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        if($arFactura->getEstadoAutorizado() == 1) {
            if($arFactura->getNumero() == 0) {
                $intNumero = $em->getRepository('BrasaTurnoBundle:TurFacturaTipo')->consecutivo($arFactura->getCodigoFacturaTipoFk());
                $arFactura->setNumero($intNumero);
                $arFactura->setFecha(new \DateTime('now'));
                $dateFechaVence = $objFunciones->sumarDiasFecha($arFactura->getClienteRel()->getPlazoPago(), $arFactura->getFecha());
                $arFactura->setFechaVence($dateFechaVence);
                $arClienteTurno = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arClienteTurno = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($arFactura->getCodigoClienteFk());
                $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                $arClienteCartera = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arClienteTurno->getNit()));
                if ($arClienteCartera == null){
                    $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                    $arClienteCartera->setAsesorRel($arClienteTurno->getAsesorRel());
                    $arClienteCartera->setFormaPagoRel($arClienteTurno->getFormaPagoRel());
                    $arClienteCartera->setCiudadRel($arClienteTurno->getCiudadRel());
                    $arClienteCartera->setNit($arClienteTurno->getNit());
                    $arClienteCartera->setDigitoVerificacion($arClienteTurno->getDigitoVerificacion());
                    $arClienteCartera->setNombreCorto($arClienteTurno->getNombreCorto());
                    $arClienteCartera->setPlazoPago($arClienteTurno->getPlazoPago());
                    $arClienteCartera->setDireccion($arClienteTurno->getDireccion());
                    $arClienteCartera->setTelefono($arClienteTurno->getTelefono());
                    $arClienteCartera->setCelular($arClienteTurno->getCelular());
                    $arClienteCartera->setFax($arClienteTurno->getFax());
                    $arClienteCartera->setEmail($arClienteTurno->getEmail());
                    $arClienteCartera->setUsuario($arFactura->getUsuario());
                    $em->persist($arClienteCartera);
                }
                    $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                    $arCuentaCobrarTipo = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrarTipo')->find(2);
                    $arCuentaCobrar->setClienteRel($arClienteCartera);
                    $arCuentaCobrar->setAsesorRel($arClienteTurno->getAsesorRel());
                    $arCuentaCobrar->setCuentaCobrarTipoRel($arCuentaCobrarTipo);
                    $arCuentaCobrar->setFecha($arFactura->getFecha());
                    $arCuentaCobrar->setFechaVence($arFactura->getFechaVence());
                    $arCuentaCobrar->setCodigoFactura($arFactura->getCodigoFacturaPk());
                    $arCuentaCobrar->setSoporte($arFactura->getSoporte());
                    $arCuentaCobrar->setNumeroDocumento($arFactura->getNumero());
                    $arCuentaCobrar->setValorOriginal($arFactura->getVrTotal());
                    $arCuentaCobrar->setSaldo($arFactura->getVrTotal());
                    $arCuentaCobrar->setPlazo($arClienteTurno->getPlazoPago());
                    $arCuentaCobrar->setAbono(0);
                    if($arFactura->getProyectoRel()) {
                        $arCuentaCobrar->setGrupo($arFactura->getProyectoRel()->getNombre());
                    }
                    $em->persist($arCuentaCobrar);
            }
            $em->persist($arFactura);
            $em->flush();
        } else {
            $strResultado = "Debe autorizar la factura para imprimirla";
        }
        return $strResultado;
    }

    public function anular($codigoFactura) {
        $em = $this->getEntityManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();        
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        
        $strResultado = "";
        if($arFactura->getEstadoAutorizado() == 1 && $arFactura->getEstadoAnulado() == 0 && $arFactura->getNumero() != 0 && $arFactura->getEstadoContabilizado() == 0) {
            $boolAnular = TRUE;
            $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
            $arFacturaDetalles = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));
            //Devolver saldo a los pedidos
            foreach ($arFacturaDetalles as $arFacturaDetalle) {
                if($arFacturaDetalle->getCodigoPedidoDetalleFk()) {
                    $arPedidoDetalleAct = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalleAct = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arFacturaDetalle->getCodigoPedidoDetalleFk());
                    $floValorTotalPendiente = $arPedidoDetalleAct->getVrTotalDetallePendiente() + $arFacturaDetalle->getVrPrecio();
                    $arPedidoDetalleAct->setVrTotalDetallePendiente($floValorTotalPendiente);
                    $arPedidoDetalleAct->setEstadoFacturado(0);
                    $em->persist($arPedidoDetalleAct);                    
                }
            }
            //Actualizar los detalles de la factura a cero
            foreach ($arFacturaDetalles as $arFacturaDetalle) {
                $arFacturaDetalleAct = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                $arFacturaDetalleAct = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($arFacturaDetalle->getCodigoFacturaDetallePk());
                $arFacturaDetalle->setVrPrecio(0);
                $arFacturaDetalle->setCantidad(0);
                $arFacturaDetalle->setSubtotal(0);
                $arFacturaDetalle->setSubtotalOperado(0);
                $arFacturaDetalle->setBaseIva(0);
                $arFacturaDetalle->setIva(0);
                $arFacturaDetalle->setTotal(0);
                $em->persist($arFacturaDetalle);
            }
            $arFactura->setVrSubtotal(0);
            $arFactura->setVrRetencionFuente(0);
            $arFactura->setVrBaseAIU(0);
            $arFactura->setVrIva(0);
            $arFactura->setVrTotal(0);
            $arFactura->setVrTotalNeto(0);
            $arFactura->setEstadoAnulado(1);
            $em->persist($arFactura);

            //Anular cuenta por cobrar
            /*$arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
            $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->findOneBy(array('codigoCuentaCobrarTipoFk' => 2, 'numeroDocumento' => $arFactura->getNumero()));
            if($arCuentaCobrar) {
                if($arCuentaCobrar->getValorOriginal() == $arCuentaCobrar->getSaldo()) {
                    $arCuentaCobrar->setSaldo(0);
                    $arCuentaCobrar->setValorOriginal(0);
                    $arCuentaCobrar->setAbono(0);
                    $em->persist($arCuentaCobrar);
                }
            }*/
            $em->flush();

        } else {
            $strResultado = "La factura debe estar autorizada e impresa, no puede estar previamente anulada ni contabilizada";
        }
        return $strResultado;
    }

    public function contabilizar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {            
            foreach ($arrSeleccionados AS $codigo) {
                $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
                $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigo);
                if($arFactura->getEstadoAutorizado() == 1 && $arFactura->getEstadoContabilizado() == 0 && $arFactura->getNumero() != 0 ) {                    
                    $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arFactura->getClienteRel()->getNit()));
                    if(count($arTercero) <= 0) {
                        $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();                        
                        $arTercero->setCiudadRel($arFactura->getClienteRel()->getCiudadRel());
                        $arTercero->setTipoIdentificacionRel($arFactura->getClienteRel()->getTipoIdentificacionRel());
                        $arTercero->setNumeroIdentificacion($arFactura->getClienteRel()->getNit());
                        $arTercero->setDigitoVerificacion($arFactura->getClienteRel()->getDigitoVerificacion());
                        $arTercero->setNombreCorto($arFactura->getClienteRel()->getNombreCorto());
                        $arTercero->setNombre1($arFactura->getClienteRel()->getNombre1());
                        $arTercero->setNombre2($arFactura->getClienteRel()->getNombre2());
                        $arTercero->setApellido1($arFactura->getClienteRel()->getApellido1());
                        $arTercero->setApellido2($arFactura->getClienteRel()->getApellido2());
                        $arTercero->setDireccion($arFactura->getClienteRel()->getDireccion());
                        $arTercero->setTelefono($arFactura->getClienteRel()->getTelefono());
                        $arTercero->setCelular($arFactura->getClienteRel()->getCelular());
                        $arTercero->setEmail($arFactura->getClienteRel()->getEmail());
                        $em->persist($arTercero);                        
                    }
                    $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arFactura->getFacturaTipoRel()->getCodigoComprobante());            
                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                    
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arFactura->getFacturaServicioRel()->getCodigoCuentaCarteraFk());                                        
                    $arRegistro->setComprobanteRel($arComprobanteContable);
                    $arRegistro->setCuentaRel($arCuenta);
                    $arRegistro->setTerceroRel($arTercero);
                    $arRegistro->setNumero($arFactura->getNumero());
                    $arRegistro->setNumeroReferencia($arFactura->getNumero());
                    $arRegistro->setFecha($arFactura->getFecha());   
                    if($arFactura->getFacturaTipoRel()->getTipoCuentaCartera() == 1) {
                        $arRegistro->setDebito($arFactura->getVrTotalNeto());
                    } else {
                        $arRegistro->setCredito($arFactura->getVrTotalNeto());
                    }
                    $arRegistro->setDescripcionContable('FACTURACION ' . $this->MesesEspañol($arFactura->getFecha()->format('m')));
                    $em->persist($arRegistro);

                    //Retencion en la fuente
                    if($arFactura->getVrRetencionFuente() > 0) {
                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro(); 
                        $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arFactura->getFacturaServicioRel()->getCodigoCuentaRetencionFuenteFk());                                        
                        $arRegistro->setComprobanteRel($arComprobanteContable);
                        $arRegistro->setCuentaRel($arCuenta);
                        $arRegistro->setTerceroRel($arTercero);
                        $arRegistro->setNumero($arFactura->getNumero());
                        $arRegistro->setNumeroReferencia($arFactura->getNumero());
                        $arRegistro->setFecha($arFactura->getFecha());                    
                        $arRegistro->setBase($arFactura->getVrBaseRetencionFuente());
                        if($arFactura->getFacturaTipoRel()->getTipoCuentaRetencionFuente() == 1) {
                             $arRegistro->setDebito($arFactura->getVrRetencionFuente());
                        } else {
                             $arRegistro->setCredito($arFactura->getVrRetencionFuente());
                        }   
                        $arRegistro->setDescripcionContable('FACTURACION ' . $this->MesesEspañol($arFactura->getFecha()->format('m')));
                        $em->persist($arRegistro);                         
                    }                   

                    //Iva
                    if($arFactura->getVrIva() > 0) {
                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro(); 
                        if($arFactura->getFacturaTipoRel()->getTipo() == 2 || $arFactura->getFacturaTipoRel()->getTipo() == 3) {
                            $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arFactura->getFacturaServicioRel()->getCodigoCuentaIvaDevolucionFk());                                                                                                  
                        } else {
                            $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arFactura->getFacturaServicioRel()->getCodigoCuentaIvaFk());                                                                                               
                        }
                        if($arFactura->getFacturaTipoRel()->getTipoCuentaIva() == 1) {
                            $arRegistro->setDebito($arFactura->getVrIva());
                        } else {
                            $arRegistro->setCredito($arFactura->getVrIva());
                        }
                        $arRegistro->setComprobanteRel($arComprobanteContable);
                        $arRegistro->setCuentaRel($arCuenta);
                        $arRegistro->setTerceroRel($arTercero);
                        $arRegistro->setNumero($arFactura->getNumero());
                        $arRegistro->setNumeroReferencia($arFactura->getNumero());
                        $arRegistro->setFecha($arFactura->getFecha());
                        $arRegistro->setBase($arFactura->getVrBaseAIU()); 
                        $arRegistro->setDescripcionContable('FACTURACION ' . $this->MesesEspañol($arFactura->getFecha()->format('m')));                        
                        $em->persist($arRegistro);                        
                    }

                    //Ingreso
                    $strSql = "SELECT codigo_centro_costo_contabilidad_fk as centroCosto, SUM(subtotal) as subtotal                                        
                                FROM tur_factura_detalle                                                            
                                LEFT JOIN tur_puesto ON codigo_puesto_fk = codigo_puesto_pk
                                WHERE codigo_factura_fk = $codigo 
                                GROUP BY codigo_centro_costo_contabilidad_fk"; 
                    $connection = $em->getConnection();
                    $statement = $connection->prepare($strSql);        
                    $statement->execute();
                    $arFacturaDetalles = $statement->fetchAll();
                    foreach ($arFacturaDetalles as $arFacturaDetalle) {
                        $arCentroCosto =$em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find($arFacturaDetalle['centroCosto']);                           
                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro(); 
                        if($arFactura->getFacturaTipoRel()->getTipo() == 2 || $arFactura->getFacturaTipoRel()->getTipo() == 3) {
                            $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arFactura->getFacturaServicioRel()->getCodigoCuentaIngresoDevolucionFk());                                                                
                        } else {
                            $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arFactura->getFacturaServicioRel()->getCodigoCuentaIngresoFk());                                                                 
                        }
                        if($arFactura->getFacturaTipoRel()->getTipoCuentaIngreso() == 1) {
                            $arRegistro->setDebito($arFacturaDetalle['subtotal']);
                        } else {                            
                            $arRegistro->setCredito($arFacturaDetalle['subtotal']);
                        }                                                
                        $arRegistro->setComprobanteRel($arComprobanteContable);
                        $arRegistro->setCentroCostoRel($arCentroCosto);
                        $arRegistro->setCuentaRel($arCuenta);
                        $arRegistro->setTerceroRel($arTercero);
                        $arRegistro->setNumero($arFactura->getNumero());
                        $arRegistro->setNumeroReferencia($arFactura->getNumero());
                        $arRegistro->setFecha($arFactura->getFecha()); 
                        $arRegistro->setDescripcionContable('FACTURACION ' . $this->MesesEspañol($arFactura->getFecha()->format('m')));                    
                        $em->persist($arRegistro);                        
                    }                    
                    
                    $arFactura->setEstadoContabilizado(1);
                    $em->persist($arFactura);                                            
                }
            }
            $em->flush();
        }
    }
    
    public static function MesesEspañol($mes) {
        
        if ($mes == '01'){
            $mesEspañol = "ENERO";
        }
        if ($mes == '02'){
            $mesEspañol = "FEBRERO";
        }
        if ($mes == '03'){
            $mesEspañol = "MARZO";
        }
        if ($mes == '04'){
            $mesEspañol = "ABRIL";
        }
        if ($mes == '05'){
            $mesEspañol = "MAYO";
        }
        if ($mes == '06'){
            $mesEspañol = "JUNIO";
        }
        if ($mes == '07'){
            $mesEspañol = "JULIO";
        }
        if ($mes == '08'){
            $mesEspañol = "AGOSTO";
        }
        if ($mes == '09'){
            $mesEspañol = "SEPTIEMBRE";
        }
        if ($mes == '10'){
            $mesEspañol = "OCTUBRE";
        }
        if ($mes == '11'){
            $mesEspañol = "NOVIEMBRE";
        }
        if ($mes == '12'){
            $mesEspañol = "DICIEMBRE";
        }

        return $mesEspañol;
    }    
}