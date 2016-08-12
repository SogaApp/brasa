<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuVacacionRepository extends EntityRepository {        
    
    public function listaVacacionesDQL($strCodigoCentroCosto = "", $strIdentificacion = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT v, e FROM BrasaRecursoHumanoBundle:RhuVacacion v JOIN v.empleadoRel e WHERE v.codigoVacacionPk <> 0";
        
        if($strCodigoCentroCosto != "") {
            $dql .= " AND v.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        $dql .= " ORDER BY v.codigoVacacionPk DESC";
        return $dql;
    }

    public function liquidar($codigoVacacion) {        
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();            
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);                         
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $arVacacion->getContratoRel();
        $fechaDesdePeriodo = $arContrato->getFechaUltimoPagoVacaciones();                                
        $fechaHastaPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestacionesHasta(360, $fechaDesdePeriodo);
        $intDias = ($arVacacion->getDiasDisfrutados() + $arVacacion->getDiasPagados()) * 24;
        $fechaDesdePeriodo = $arContrato->getFechaUltimoPagoVacaciones();
        $fechaHastaPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestacionesHasta($intDias, $fechaDesdePeriodo);
        $arVacacion->setFechaDesdePeriodo($fechaDesdePeriodo);
        $arVacacion->setFechaHastaPeriodo($fechaHastaPeriodo);        
        $intDias = $arVacacion->getDiasVacaciones();
        $floSalario = $arVacacion->getEmpleadoRel()->getVrSalario();        
        //Analizar cambios de salario
        $fecha = $arVacacion->getFecha()->format('Y-m-d');
        $nuevafecha = strtotime ( '-90 day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
        $fechaDesdeCambioSalario = date_create_from_format('Y-m-d H:i', $nuevafecha . " 00:00");        
        $floSalarioPromedio = 0;        
        /*$arCambiosSalario = new \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario();
        $arCambiosSalario = $em->getRepository('BrasaRecursoHumanoBundle:RhuCambioSalario')->cambiosSalario($arVacacion->getContratoRel()->getCodigoContratoPk(), $fechaDesdeCambioSalario->format('Y-m-d'), $arVacacion->getFecha()->format('Y-m-d'));                 
        if(count($arCambiosSalario) > 0) {
            $floPrimerSalario = $arCambiosSalario[0]->getVrSalarioAnterior();
            $intNumeroRegistros = count($arCambiosSalario) + 1;
            $floSumaSalarios = 0;
            foreach ($arCambiosSalario as $arCambioSalario) {
                $floSumaSalarios += $arCambioSalario->getVrSalarioNuevo();
            }
            $floSalarioPromedio = round((($floSumaSalarios + $floPrimerSalario) / $intNumeroRegistros));
            
        } else {
            $floSalarioPromedio = $floSalario;
        }         
         * 
         */
        $recargosNocturnos = $arContrato->getPromedioRecargoNocturnoInicial();
        $promedioRecargosNocturnos = $recargosNocturnos;
        if ($promedioRecargosNocturnos == null){
            $promedioRecargosNocturnos = 0;
        }
        $arVacacion->setVrPromedioRecargoNocturno($promedioRecargosNocturnos);
        if($arContrato->getCodigoSalarioTipoFk() == 1) {
            $floSalarioPromedio = $arContrato->getVrSalario();
        } else {
            
            $floSalarioPromedio = $arContrato->getVrSalario() + $promedioRecargosNocturnos;
        }        
        $floTotalVacacionBrutoDisfrute = $floSalarioPromedio / 30 * $arVacacion->getDiasDisfrutadosReales();
        $floTotalVacacionBrutoPagados = $arContrato->getVrSalario() / 30 * $arVacacion->getDiasPagados();
        $floTotalVacacionBruto = $floTotalVacacionBrutoDisfrute + $floTotalVacacionBrutoPagados;  
        
        $douSalud = ($floTotalVacacionBrutoDisfrute * 4) / 100;
        $arVacacion->setVrSalud($douSalud);
        if ($floTotalVacacionBruto >= ($arConfiguracion->getVrSalario() * 4)){
            $douPorcentaje = $arConfiguracion->getPorcentajePensionExtra();
            $douPension = ($floSalario * $douPorcentaje) /100;
        } else {
            $douPension = ($floTotalVacacionBrutoDisfrute * 4) / 100;
        }
        $arVacacion->setVrPension($douPension);                                   
        $floDeducciones = 0;
        $arVacacionDeducciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito();
        $arVacacionDeducciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionCredito')->FindBy(array('codigoVacacionFk' => $codigoVacacion));        
        foreach ($arVacacionDeducciones as $arVacacionDeduccion) {
            $floDeducciones += $arVacacionDeduccion->getVrDeduccion();
        }
        $floBonificaciones = 0;
        $arVacacionBonificaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionBonificacion();
        $arVacacionBonificaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionBonificacion')->FindBy(array('codigoVacacionFk' => $codigoVacacion));        
        foreach ($arVacacionBonificaciones as $arVacacionBonificacion) {
            $floBonificaciones += $arVacacionBonificacion->getVrBonificacion();
        }        
        $arVacacion->setVrBonificacion($floBonificaciones);
        $arVacacion->setVrDeduccion($floDeducciones);
        $arVacacion->setVrVacacionBruto($floTotalVacacionBruto);
        $floTotalVacacion = ($floTotalVacacionBruto+$floBonificaciones) - $floDeducciones - $arVacacion->getVrPension() - $arVacacion->getVrSalud();        
        $arVacacion->setVrVacacion($floTotalVacacion);        
        $arVacacion->setVrSalarioActual($floSalario);
        $arVacacion->setVrSalarioPromedio($floSalarioPromedio);
        $em->persist($arVacacion);
        $em->flush();
        
        return true;
    }    
    
    public function devuelveVacacionesFecha($codigoEmpleado, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(v.vrVacacion) as Vacaciones FROM BrasaRecursoHumanoBundle:RhuVacacion v "
                . "WHERE v.codigoEmpleadoFk = " . $codigoEmpleado 
                . "AND v.fechaDesdePeriodo >= '" . $fechaDesde . "' AND v.fechaHastaPeriodo <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
    
    public function dias($codigoEmpleado, $codigoContrato, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $dql = "SELECT v FROM BrasaRecursoHumanoBundle:RhuVacacion v "
                . "WHERE (((v.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (v.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (v.fechaDesdeDisfrute >= '$strFechaDesde' AND v.fechaDesdeDisfrute <= '$strFechaHasta') "
                . "OR (v.fechaHastaDisfrute >= '$strFechaHasta' AND v.fechaDesdeDisfrute <= '$strFechaDesde')) "
                . "AND v.codigoEmpleadoFk = '" . $codigoEmpleado . "' AND v.codigoContratoFk = " . $codigoContrato . " AND v.diasDisfrutados > 0";
        
        $query = $em->createQuery($dql);
        $arVacaciones = $query->getResult();
        $intDiasDevolver = 0;
        foreach ($arVacaciones as $arVacacion) {
            $dateFechaDesde =  "";
            $dateFechaHasta =  "";                            
            if($arVacacion->getFechaDesdeDisfrute() <  $fechaDesde == true) {
                $dateFechaDesde = $fechaDesde;
            } else {
                $dateFechaDesde = $arVacacion->getFechaDesdeDisfrute();
            }

            if($arVacacion->getFechaHastaDisfrute() >  $fechaHasta == true) {
                $dateFechaHasta = $fechaHasta;
            } else {
                $dateFechaHasta = $arVacacion->getFechaHastaDisfrute();
            }
            if($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');
                $intDiasDevolver += $intDias + 1;
            }                            
        }
        return $intDiasDevolver;
    }    
    
    //Seguridad social
    public function diasVacacionesDisfrute($fechaDesde, $fechaHasta, $codigoEmpleado, $codigoContrato) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT v FROM BrasaRecursoHumanoBundle:RhuVacacion v "
                . "WHERE (((v.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (v.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (v.fechaDesdeDisfrute >= '$strFechaDesde' AND v.fechaDesdeDisfrute <= '$strFechaHasta') "
                . "OR (v.fechaHastaDisfrute >= '$strFechaHasta' AND v.fechaDesdeDisfrute <= '$strFechaDesde')) "
                . "AND v.codigoEmpleadoFk = " . $codigoEmpleado . " AND v.codigoContratoFk = " . $codigoContrato;
        $objQuery = $em->createQuery($dql);  
        $arVacacionesDisfrute = $objQuery->getResult();        
        $intDiasVacacionesTotal = 0;
        $vrAporteParafiscales = 0;
        foreach ($arVacacionesDisfrute as $arVacacionDisfrute) {            
            $intDiasVacaciones = 0;
            $intDiaInicio = 1;            
            $intDiaFin = 30;
            if($arVacacionDisfrute->getFechaDesdeDisfrute() <  $fechaDesde) {
                $intDiaInicio = 1;                
            } else {
                $intDiaInicio = $arVacacionDisfrute->getFechaDesdeDisfrute()->format('j');
            }
            if($arVacacionDisfrute->getFechaHastaDisfrute() > $fechaHasta) {
                $intDiaFin = 30;                
            } else {
                $intDiaFin = $arVacacionDisfrute->getFechaHastaDisfrute()->format('j');
            }            
            $intDiasVacaciones = (($intDiaFin - $intDiaInicio)+1);
            if($intDiasVacaciones == 1) {
                $intDiasVacaciones = 0;
            }     
            $intDiasVacacionesTotal += $intDiasVacaciones;
            //$arVacacionDisfrute = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
            if($arVacacionDisfrute->getDiasDisfrutados() > 1) {
                $vrDiaDisfrute = ($arVacacionDisfrute->getVrVacacionBruto() / $arVacacionDisfrute->getDiasDisfrutados());    
                $vrAporteParafiscales += $intDiasVacaciones * $vrDiaDisfrute;
            } else {
                $vrAporteParafiscales += $arVacacionDisfrute->getVrVacacionBruto();
            }            
            
        }
        if($intDiasVacacionesTotal > 30) {
            $intDiasVacacionesTotal = 30;
        }
        $arrVacaciones = array('dias' => $intDiasVacacionesTotal, 'aporte' => $vrAporteParafiscales);
        return $arrVacaciones;                     
    }     
    
    
    public function periodo($fechaDesde, $fechaHasta, $codigoEmpleado = "", $codigoCentroCosto = "") {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT vacacion FROM BrasaRecursoHumanoBundle:RhuVacacion vacacion "
                . "WHERE (((vacacion.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (vacacion.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (vacacion.fechaDesdeDisfrute >= '$strFechaDesde' AND vacacion.fechaDesdeDisfrute <= '$strFechaHasta') "
                . "OR (vacacion.fechaHastaDisfrute >= '$strFechaHasta' AND vacacion.fechaDesdeDisfrute <= '$strFechaDesde')) ";
        if($codigoEmpleado != "") {
            $dql = $dql . "AND vacacion.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        }
        if($codigoCentroCosto != "") {
            $dql = $dql . "AND vacacion.codigoCentroCostoFk = " . $codigoCentroCosto . " ";
        }        

        $objQuery = $em->createQuery($dql);  
        $arVacaciones = $objQuery->getResult();         
        return $arVacaciones;
    }                        
}

