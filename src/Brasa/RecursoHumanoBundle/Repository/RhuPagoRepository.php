<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuPagoRepository extends EntityRepository {
    
    public function liquidar($codigoPago, $arConfiguracion) {        
        $em = $this->getEntityManager();
        $douSalario = 0;
        $douAuxilioTransporte = 0;
        $douAdicionTiempo = 0;
        $douAdicionValor = 0;
        $douAdicionValorNoPrestacional = 0;
        $douAdicionCotizacion = 0;
        $douPension = 0;
        $douEps = 0;
        $douDeducciones = 0;
        $douDevengado = 0;        
        $douNeto = 0;
        $douIngresoBaseCotizacion = 0;
        $douIngresoBasePrestacion = 0;
        $intDiasAusentismo = 0;
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigoPago));         
        foreach ($arPagoDetalles as $arPagoDetalle) {
            if($arPagoDetalle->getOperacion() == 1) {
                $douDevengado = $douDevengado + $arPagoDetalle->getVrPago();
            }
            if($arPagoDetalle->getOperacion() == -1) {
                $douDeducciones = $douDeducciones + $arPagoDetalle->getVrPago();
            }
            if($arPagoDetalle->getPagoConceptoRel()->getComponeSalario() == 1) {
                $douSalario = $douSalario + $arPagoDetalle->getVrPago();
            }            
            if($arPagoDetalle->getPagoConceptoRel()->getConceptoAuxilioTransporte() == 1) {
                $douAuxilioTransporte = $douAuxilioTransporte + $arPagoDetalle->getVrPago();
            }            
            if($arPagoDetalle->getPagoConceptoRel()->getConceptoPension() == 1) {
                $douPension = $douPension + $arPagoDetalle->getVrPago();
            }
            if($arPagoDetalle->getPagoConceptoRel()->getConceptoSalud() == 1) {
                $douEps = $douEps + $arPagoDetalle->getVrPago();
            }            
            if($arPagoDetalle->getPagoConceptoRel()->getConceptoAdicion() == 1) {
                if($arPagoDetalle->getOperacion() == 1) {                
                    if($arPagoDetalle->getPagoConceptoRel()->getComponeValor() == 1) {
                        $douAdicionValor = $douAdicionValor + $arPagoDetalle->getVrPago();    
                    } else {
                        $douAdicionTiempo = $douAdicionTiempo + $arPagoDetalle->getVrPago();    
                    }                    
                }                                
            }
            
            if($arPagoDetalle->getAdicional() == 1) {
                if($arPagoDetalle->getPrestacional() == 0) {
                    if($arPagoDetalle->getOperacion() == 1) {
                        $douAdicionValorNoPrestacional = $douAdicionValorNoPrestacional + $arPagoDetalle->getVrPago();
                    }                    
                }
            }
            $douIngresoBaseCotizacion += $arPagoDetalle->getVrIngresoBaseCotizacion();
            $douIngresoBasePrestacion += $arPagoDetalle->getVrIngresoBasePrestacion();
            $intDiasAusentismo += $arPagoDetalle->getDiasAusentismo();
            $douAdicionCotizacion += $arPagoDetalle->getVrIngresoBaseCotizacionAdicional();
        }
        
        //$arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();                        
        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);                                
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $arPago->getContratoRel();
        $arPago->setVrDevengado($douDevengado);
        $arPago->setVrDeducciones($douDeducciones);
        $douNeto = $douDevengado - $douDeducciones;
        $arPago->setVrNeto($douNeto);
        $arPago->setVrSalario($douSalario);        
        $arPago->setVrAuxilioTransporte($douAuxilioTransporte);        
        $arPago->setVrAdicionalTiempo($douAdicionTiempo);
        $arPago->setVrAdicionalValor($douAdicionValor);
        $arPago->setVrAdicionalValorNoPrestasional($douAdicionValorNoPrestacional);
        $arPago->setVrAdicionalCotizacion($douAdicionCotizacion);                
        $arPago->setVrIngresoBaseCotizacion($douIngresoBaseCotizacion);
        $arPago->setVrIngresoBasePrestacion($douIngresoBasePrestacion);
        $arPago->setDiasAusentismo($intDiasAusentismo);
        
        $floSalarioMinimo = $arConfiguracion->getVrSalario();
        $floVrDiaMinimo = $floSalarioMinimo/30;        
        $douIngresoBaseCotizacionMinimo = $floVrDiaMinimo * $arPago->getDiasPeriodo();
        if($douIngresoBaseCotizacion < $douIngresoBaseCotizacionMinimo) {
            $douIngresoBaseCotizacion = $douIngresoBaseCotizacionMinimo;
        }
        
        /*
        //Provision
        $floAuxilioTransporteCotizacion = $arPago->getVrAuxilioTransporteCotizacion();
        $intTipoBaseVacaciones = $arConfiguracion->getTipoBasePagoVacaciones();
        $floPorcentajeRiesgos = $arContrato->getClasificacionRiesgoRel()->getPorcentaje();
        $floPorcentajePension = $arContrato->getTipoPensionRel()->getPorcentajeEmpleador();
        $floPorcentajePensionEmpleado = $arContrato->getTipoPensionRel()->getPorcentajeEmpleado();
        $floPorcentajeSalud = $arContrato->getTipoSaludRel()->getPorcentajeEmpleador();        
        $floPorcentajeSaludEmpleado = $arContrato->getTipoSaludRel()->getPorcentajeEmpleado();        
        $floPorcentajeCaja = $arConfiguracion->getAportesPorcentajeCaja();
        $floPorcentajeAporteVacaciones = $arConfiguracion->getAportesPorcentajeVacaciones();        
        $floPorcentajeCesantias = $arConfiguracion->getPrestacionesPorcentajeCesantias();        
        $floPorcentajeInteresesCesantias = $arConfiguracion->getPrestacionesPorcentajeInteresesCesantias();
        $floPorcentajeVacaciones = $arConfiguracion->getPrestacionesPorcentajeVacaciones();
        $floPorcentajePrimas = $arConfiguracion->getPrestacionesPorcentajePrimas();
        //Aportes sociales
        $douArp = ($douIngresoBaseCotizacion * $floPorcentajeRiesgos)/100;        
        $douPensionEmpleador = ($douIngresoBaseCotizacion * $floPorcentajePension) / 100; 
        $douSaludEmpleador = ($douIngresoBaseCotizacion * $floPorcentajeSalud) / 100;         
        $douCaja = ($douIngresoBaseCotizacion * $floPorcentajeCaja) / 100; //Porcentaje 4        
        $douSena = 0;
        $douIcbf = 0;
        
        //Seguridad social
        $douCesantias = (($douIngresoBasePrestacion + $floAuxilioTransporteCotizacion) * $floPorcentajeCesantias) / 100; // Porcentaje 8.33                
        $douInteresesCesantias = ($douCesantias * $floPorcentajeInteresesCesantias) / 100; // Porcentaje 1 sobre las cesantias                        
        $douBaseVacaciones = 0;
        if($intTipoBaseVacaciones == 1) {
            $douBaseVacaciones = $douSalario;
        } 
        if($intTipoBaseVacaciones == 2) {
            $douBaseVacaciones = $douSalario + $douAdicionTiempo + $douAdicionValor;
        }        
        if($intTipoBaseVacaciones == 3) {
            $douBaseVacaciones = $douSalario + $douAdicionTiempo + $douAdicionValor;
        }        
        $douVacaciones = ($douBaseVacaciones * $floPorcentajeVacaciones) / 100; // 4.17
        $douAporteVacaciones = ($floPorcentajeAporteVacaciones * $douVacaciones) / 100;
        $douPrimas = (($douIngresoBasePrestacion + $floAuxilioTransporteCotizacion) * $floPorcentajePrimas) / 100; // 8.33
        
        //12 aprendiz y 19 practicante        
        if($arContrato->getCodigoTipoCotizanteFk() == '19' || $arContrato->getCodigoTipoCotizanteFk() == '12') {            
            $douSaludEmpleador = ($douIngresoBaseCotizacion * $floPorcentajeSalud) / 100;
            $douPensionEmpleador = 0;            
            $douCaja = 0;
            $douCesantias = 0;
            $douInteresesCesantias = 0;
            $douBaseVacaciones = 0;
            $douVacaciones = 0;
            $douAporteVacaciones = 0;
            $douPrimas = 0;
            $douPrestaciones = 0;
            $douAportes = 0;
        }
        if($arContrato->getCodigoTipoCotizanteFk() == '12') {
            $douArp = 0;
        }  
        
        //Medios tiempos
        if($arContrato->getCodigoTipoTiempoFk() == 2) {
            $douPensionEmpleador = ($douIngresoBaseCotizacion * ($floPorcentajePension+$floPorcentajePensionEmpleado)) / 100; 
            $douPensionEmpleador = $douPensionEmpleador - $douPension;
            $douSaludEmpleador = ($douIngresoBaseCotizacion * $floPorcentajeSaludEmpleado) / 100;            
            $douSaludEmpleador = $douSaludEmpleador - $douEps;
        }
        
        $douPrestaciones = $douCesantias + $douInteresesCesantias + $douVacaciones + $douPrimas;
        $douAportes = $douPensionEmpleador + $douSaludEmpleador + $douArp + $douCaja + $douAporteVacaciones;        
        
        //Aportes empleado
        $arPago->setVrPension($douPensionEmpleador);
        $arPago->setVrEps($douEps);                                                
        
        //Liquidar aportes
        $arPago->setVrPensionEmpleador($douPensionEmpleador);
        $arPago->setVrEpsEmpleador($douSaludEmpleador);
        $arPago->setVrArp($douArp);
        $arPago->setVrCaja($douCaja);
        $arPago->setvrSena($douSena);
        $arPago->setvrIcbf($douIcbf);
        $arPago->setVrAportes($douAportes);
        $arPago->setVrAporteVacaciones($douAporteVacaciones);
        
        
        //Liquidar prestaciones        
        $arPago->setVrCesantias($douCesantias);
        $arPago->setVrInteresesCesantias($douInteresesCesantias);
        $arPago->setVrVacaciones($douVacaciones);        
        $arPago->setVrPrimas($douPrimas);        
        $arPago->setVrPrestaciones($douPrestaciones);
        */
        
        
        $arPago->setVrCosto(0);                       
        $em->persist($arPago);        
        return $douNeto;
    }    
    
    public function listaDql($intNumero = 0, $strCodigoCentroCosto = "", $strIdentificacion = "", $intTipo = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.codigoPagoPk <> 0";
        if($intNumero != "" && $intNumero != 0) {
            $dql .= " AND p.numero = " . $intNumero;
        }
        if($strCodigoCentroCosto != "") {
            $dql .= " AND p.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($intTipo != "" && $intTipo != 0) {
            $dql .= " AND p.codigoPagoTipoFk =" . $intTipo;
        }        
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if($strDesde != "" || $strDesde != 0){
            $dql .= " AND p.fechaDesde >='" . $strDesde . "'";
        }
        if($strHasta != "" || $strHasta != 0) {
            $dql .= " AND p.fechaHasta <='" . $strHasta . "'";
        }
        $dql .= " ORDER BY p.codigoPagoPk DESC";
        return $dql;
    }                        
    
    public function listaImpresionDql($codigoPago = "", $codigoProgramacionPago = "", $codigoZona = "", $codigoSubzona = "", $porFecha = false, $fechaDesde = "", $fechaHasta = "", $dato = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.codigoPagoPk <> 0";
        if($codigoPago != "") {
            $dql .= " AND p.codigoPagoPk = " . $codigoPago;
        }
        if($codigoProgramacionPago != "") {
            $dql .= " AND p.codigoProgramacionPagoFk = " . $codigoProgramacionPago;
        }                  
        if($codigoZona != "") {
            $dql .= " AND e.codigoZonaFk = " . $codigoZona;
        }
        if($codigoSubzona != "") {
            $dql .= " AND e.codigoSubzonaFk = " . $codigoSubzona;
        }        
        if($dato != "") {
            $dql .= " AND e.dato = " . $dato;
        }         
        if($porFecha == true) {
            if($fechaDesde != "" && $fechaHasta != "") {
                $dql .= " AND (p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaDesde <= '" . $fechaHasta . "')";
            }
        }
        $dql .= " ORDER BY p.codigoPagoPk DESC";
        return $dql;
    }                            
    
    public function listaConsultaPagosDQL($strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "", $strPago = "",$strProgramacionPago = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.codigoPagoPk <> 0";
        
        if($strCodigoCentroCosto != "") {
            $dql .= " AND p.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND p.fechaDesde >='" . date_format($strDesde, ('Y-m-d')). "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaDesde <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        if($strPago != "") {
            $dql .= " AND p.codigoPagoPk ='" . $strPago . "'";
        }
        
        return $dql;
    }
    
    public function listaConsultaPagosDetallesDQL($strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pd, p FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN p.empleadoRel e WHERE pd.codigoPagoDetallePk <> 0";
          
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND p.fechaDesde >='" . date_format($strDesde, ('Y-m-d')). "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaDesde <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        $dql .= " ORDER BY e.numeroIdentificacion";
        return $dql;
    }
    
    public function contabilizadosPagoNominaDql($intNumeroDesde = 0, $intNumeroHasta = 0,$strDesde = "",$strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p WHERE p.estadoContabilizado = 1 AND p.estadoPagado = 1";
        if($intNumeroDesde != "" || $intNumeroDesde != 0) {
            $dql .= " AND p.numero >= " . $intNumeroDesde;
        }
        if($intNumeroHasta != "" || $intNumeroHasta != 0) {
            $dql .= " AND p.numero <= " . $intNumeroHasta;
        }   
        if($strDesde != "" || $strDesde != 0){
            $dql .= " AND p.fechaDesde >='" . date_format($strDesde, ('Y-m-d')) . "'";
        }
        if($strHasta != "" || $strHasta != 0) {
            $dql .= " AND p.fechaHasta <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    } 
    
    public function pendientesContabilizarDql() {        
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p WHERE p.estadoContabilizado = 0 AND p.estadoPagado = 1 AND p.vrNeto > 0";       
        $dql .= " ORDER BY p.codigoPagoPk DESC";
        return $dql;
    } 
    
    public function pendientesContabilizarProvisionDql() {        
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p WHERE p.estadoContabilizadoProvision = 0 AND p.estadoPagado = 1 AND p.vrNeto > 0";       
        $dql .= " ORDER BY p.codigoPagoPk ASC";
        return $dql;
    } 

    public function listaDqlCostos($strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.codigoPagoTipoFk = 1 ";
        if($strCodigoCentroCosto != "") {
            $dql .= " AND p.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
           // $strDesde = new \DateTime($strDesde);
            $dql .= " AND p.fechaDesde >='" . date_format($strDesde, ('Y-m-d')). "'";
        }
        if($strHasta != "") {
            //$strHasta = new \DateTime($strHasta);
            $dql .= " AND p.fechaHasta <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        //$dql .= " ORDER BY p.empleadoRel.nombreCorto";
        return $dql;
    }                            
    
    public function listaDqlPagosDeducciones($strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.codigoPagoTipoFk = 1 AND p.estadoPagado = 1";
        if($strCodigoCentroCosto != "") {
            $dql .= " AND p.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            //$strDesde = new \DateTime($strDesde);
            $dql .= " AND p.fechaDesde >='" . date_format($strDesde, ('Y-m-d')). "'";
        }
        if($strHasta != "") {
            //$strHasta = new \DateTime($strHasta);
            $dql .= " AND p.fechaHasta <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        //$dql .= " ORDER BY p.empleadoRel.nombreCorto";
        return $dql;
    }                            
    
    public function generarPagoDetalleSede ($codigoPago) {
        $em = $this->getEntityManager();
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigoPago));
        foreach ($arPagoDetalles as $arPagoDetalle) {
            $arProgramacionPagoDetalleSedes = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede();
            $arProgramacionPagoDetalleSedes = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalleSede')->findBy(array('codigoProgramacionPagoDetalleFk' => $arPagoDetalle->getCodigoProgramacionPagoDetalleFk()));
            foreach ($arProgramacionPagoDetalleSedes as $arProgramacionPagoDetalleSede) {                
                $arPagoDetalleSede = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede();
                $arPagoDetalleSede->setPagoRel($arPagoDetalle->getPagoRel());
                $arPagoDetalleSede->setPagoConceptoRel($arPagoDetalle->getPagoConceptoRel());                                                        
                $arPagoDetalleSede->setSedeRel($arProgramacionPagoDetalleSede->getSedeRel());                                                        
                $arPagoDetalleSede->setVrPago(($arPagoDetalle->getVrPago() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);
                $arPagoDetalleSede->setOperacion($arPagoDetalle->getOperacion());
                $arPagoDetalleSede->setPorcentajeAplicado($arPagoDetalle->getPorcentajeAplicado());
                $arPagoDetalleSede->setNumeroHoras(($arPagoDetalle->getNumeroHoras() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);
                $arPagoDetalleSede->setVrPagoOperado(($arPagoDetalle->getVrPagoOperado() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);                
                $arPagoDetalleSede->setVrTotal(($arPagoDetalle->getVrTotal() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);                                
                $arPagoDetalleSede->setVrIngresoBaseCotizacion(($arPagoDetalle->getVrIngresoBaseCotizacion() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);                                
                $em->persist($arPagoDetalleSede);
            }
        }
        $em->flush();
    }
    
    public function pendienteCobrar($codigoCentroCosto) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p WHERE p.estadoCobrado = 0 "
                . " AND p.codigoCentroCostoFk = " . $codigoCentroCosto;
        return $dql;
    }
    
    public function listaPagosDQL($codigoProgramacionPago) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p WHERE p.codigoProgramacionPagoFk = ". $codigoProgramacionPago ."";
        return $dql;
    }
    
    public function devuelveCostosFecha($codigoEmpleado, $fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(p.vrIngresoBaseCotizacion) as IBC, SUM(p.vrPension) as Pension, SUM(p.vrEps) as Salud, SUM(p.vrAuxilioTransporte) as AuxilioTransporte, MIN(p.fechaDesde) as fechaInicio, MAX(p.fechaHasta) as fechaFin FROM BrasaRecursoHumanoBundle:RhuPago p "
                . "WHERE p.codigoEmpleadoFk = " . $codigoEmpleado . " AND p.estadoPagado = 1 "
                . "AND p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
    
    public function devuelvePrimasFechaCertificadoIngreso($codigoEmpleado, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(p.vrNeto) as Neto FROM BrasaRecursoHumanoBundle:RhuPago p "
                . "WHERE p.codigoEmpleadoFk = " . $codigoEmpleado . " AND p.estadoPagado = 1 AND p.codigoPagoTipoFk = 2 "
                . "AND p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaDesde <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
    
    public function devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(p.vrIngresoBaseCotizacion) as IBC, SUM(p.vrPension) as Pension, SUM(p.vrEps) as Salud, SUM(p.vrAuxilioTransporte) as AuxilioTransporte, MIN(p.fechaDesde) as fechaInicio, MAX(p.fechaHasta) as fechaFin, SUM(p.vrAdicionalValorNoPrestasional) as NoPrestacional, SUM(p.vrAuxilioTransporte) as AuxTransporte, SUM(p.vrIngresoBaseCotizacion) as Prestacional FROM BrasaRecursoHumanoBundle:RhuPago p "
                . "WHERE p.codigoEmpleadoFk = " . $codigoEmpleado . " AND p.estadoPagado = 1 "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
    
    public function tiempoSuplementario($fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(p.vrAdicionalCotizacion) as suplementario FROM BrasaRecursoHumanoBundle:RhuPago p "
                . "WHERE p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $floSuplementario = $arrayResultado[0]['suplementario'];
        if($floSuplementario == null) {
            $floSuplementario = 0;
        }
        return $floSuplementario;
    }

    public function ibc($fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(p.vrIngresoBaseCotizacion) as ibc FROM BrasaRecursoHumanoBundle:RhuPago p "
                . "WHERE p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $ibc = $arrayResultado[0]['ibc'];
        if($ibc == null) {
            $ibc = 0;
        }
        return $ibc;
    }
    
    public function tiempoSuplementarioCartaLaboral($intPeriodo, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p  "
                . "WHERE p.estadoPagado = 1 "
                . "AND p.codigoContratoFk = " . $codigoContrato . " ";
        $query = $em->createQuery($dql)
                    ->setFirstResult(0)
                    ->setMaxResults($intPeriodo);
        $arrayResultado = $query->getResult();
        $dato = 0;
        foreach ($arrayResultado as $arrayResultado) {
            $dato += $arrayResultado->getVrIngresoBasePrestacion();   
        }
        //$floSuplementario = $arrayResultado[0]['suplementario'];
        return $dato;
    }
    
    public function noPrestacionalCartaLaboral($intPeriodo, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p  "
                . "WHERE p.estadoPagado = 1 "
                . "AND p.codigoContratoFk = " . $codigoContrato . " ";
        $query = $em->createQuery($dql)
                    ->setFirstResult(0)
                    ->setMaxResults($intPeriodo);
        $arrayResultado = $query->getResult();
        $dato = 0;
        foreach ($arrayResultado as $arrayResultado) {
            $dato += $arrayResultado->getVrAdicionalValorNoPrestasional();   
        }
        //$floSuplementario = $arrayResultado[0]['suplementario'];
        return $dato;
    }
    
    public function devuelveCostosDane($fechaDesde, $fechaHasta, $fechaProceso) {
        $em = $this->getEntityManager();
        $dql   = "SELECT p, c FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.contratoRel c WHERE p.codigoPagoPk <> 0"
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
                if ($fechaProceso != ""){
                    $dql .= " AND p.fechaDesde LIKE '%".$fechaProceso. "%' AND p.fechaHasta LIKE '%".$fechaProceso. "%'";
                }
                
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
    
    //consulta pago pendientes al banco
    public function listaPagoPendientesBancoDql($strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.estadoPagadoBanco = 0";
        if($strCodigoCentroCosto != "") {
            $dql .= " AND p.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND p.fechaDesde >='" . date_format($strDesde, ('Y-m-d')). "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaHasta <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        //$dql .= " ORDER BY p.empleadoRel.nombreCorto";
        return $dql;
    }                            
    
    public function diasAusentismo($fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(p.diasAusentismo) as diasAusentismo FROM BrasaRecursoHumanoBundle:RhuPago p "
                . "WHERE p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaHastaPago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $intDiasAusentismo = $arrayResultado[0]['diasAusentismo'];
        if($intDiasAusentismo == null) {
            $intDiasAusentismo = 0;
        }
        return $intDiasAusentismo;
    }  
    
    public function listaDqlPagosPeriodoAportes($strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.codigoPagoTipoFk = 1 AND p.estadoPagado = 1";
        
        if ($strDesde != ""){
            $dql .= " AND p.fechaDesdePago >='" . $strDesde->format('Y-m-d'). "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaHastaPago <='" . $strHasta->format('Y-m-d') . "'";
        }

        return $dql;
    }
 
    public function pagosFecha($strDesde = "", $strHasta = "", $codigoEmpleado = "") {
        $em = $this->getEntityManager();             
        $strSql = "SELECT
                    COUNT(codigo_pago_pk) as numeroPagos,
                    SUM(vr_neto) as vrNeto,
                    SUM(vr_prestaciones) as vrPrestaciones,
                    SUM(vr_aportes) as vrAportes
                    FROM rhu_pago                                                            
                    WHERE rhu_pago.codigo_empleado_fk = $codigoEmpleado AND (rhu_pago.fecha_desde >='$strDesde' AND rhu_pago.fecha_hasta <='$strHasta')
                    GROUP BY codigo_empleado_fk"; 
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $results = $statement->fetchAll();        
        
        return $results;        
    }
}