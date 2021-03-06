<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuCentroCostoRepository extends EntityRepository {
    
    public function Liquidar($codigoPago) {        

        return true;
    }  
    
    public function generarProgramacionPago($codigoCentroCosto, $intTipo) {
        $em = $this->getEntityManager();                                                                       
        $arPagoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoTipo();
        $arPagoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoTipo')->find($intTipo);        
        $arCentroCostoProceso = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();        
        $arCentroCostoProceso = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        if($arCentroCostoProceso->getEstadoActivo() == 1) {
            //1 programacion nomina
            if($intTipo == 1) {
                $intDias = $arCentroCostoProceso->getPeriodoPagoRel()->getDias();
                $intDiasPeriodo = $arCentroCostoProceso->getPeriodoPagoRel()->getDias();
                $dateDesde = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('Y-m-d');
                if($arCentroCostoProceso->getPeriodoPagoRel()->getContinuo() == 1) {
                    $intDias = $intDias - 1;
                    $dateDesde = date("Y/m/d", strtotime("$dateDesde +1 day"));
                    $dateHasta = date("Y/m/d", strtotime("$dateDesde +$intDias day"));
                    if($arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('md') == '1229') {
                        $dateHasta = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('Y') . "/12/31";
                        $intDiasPeriodo = 2;                        
                    }
                    if($arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('md') == '1230') {                        
                        $dateHasta = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('Y') . "/12/31";                        
                        $intDiasPeriodo = 1;
                    }
                    $dateHastaReal = $dateHasta;
                } else {
                    //Para procesar el mes de febrero
                    $intDiasInhabilesFebrero = 0;
                    //Si el mes es febrero o el mes es enero 30 de periodos mensuales
                    if($arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('m') == '02' || ($arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('m/d') == '01/30' && $intDias == 30)) {
                        $year = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('Y');
                        //Verificar si el año es bisiesto
                        if(date('L',mktime(1,1,1,1,1,$year)) == 1) {
                            $intDiasInhabilesFebrero = 1;
                        } else {
                            $intDiasInhabilesFebrero = 2;
                        }
                        $intDias = $intDias - $intDiasInhabilesFebrero;
                        $intDiasMes = $intDias+$intDiasInhabilesFebrero;

                        $strMesDesde = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('Y/m');
                        $strMesHasta = date("Y/m", strtotime("$dateDesde +$intDiasMes day"));
                        if($strMesDesde == $strMesHasta) {
                            $intDias = $intDias + $intDiasInhabilesFebrero;
                        }
                        if(date("m", strtotime("$dateDesde +$intDias day")) == '03') {
                            $intDias = $intDiasMes;
                        }
                    }
                    $strMesDesde = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('Y/m');
                    $strMesHasta = date("Y/m", strtotime("$dateDesde +$intDias day"));
                    $strMes = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('m');
                    $strAnio = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('Y');
                    $strUltimoDia = date("d",(mktime(0,0,0,$strMes+1,1,$strAnio)-1));
                    
                    if($strMesDesde != $strMesHasta) {
                        $dateDesde = $strMesHasta . "/01";
                        $intDias = $intDias - 1;
                        $dateHasta = date("Y/m/d", strtotime("$dateDesde +$intDias day"));
                        $dateHastaReal = $dateHasta;
                    } else {
                        $intDias = $intDias - 1;
                        $dateDesde = date("Y/m/d", strtotime("$dateDesde +1 day"));
                        $dateHasta = date("Y/m/d", strtotime("$dateDesde +$intDias day"));
                        if($strUltimoDia == "31") {
                            $dateHastaReal = $strAnio . "/" . $strMes . "/" . $strUltimoDia;
                        } else {
                            $dateHastaReal = $dateHasta;
                        }
                        
                    }
                }
                $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                $arProgramacionPago->setPagoTipoRel($arPagoTipo);
                $arProgramacionPago->setFechaDesde(date_create($dateDesde));
                $arProgramacionPago->setFechaHasta(date_create($dateHasta));
                $arProgramacionPago->setFechaHastaReal(date_create($dateHastaReal));                
                $arProgramacionPago->setDias($intDiasPeriodo);
                $arProgramacionPago->setCentroCostoRel($arCentroCostoProceso);
                $arProgramacionPago->setCodigoUsuario($arCentroCostoProceso->getCodigoUsuario());
                $em->persist($arProgramacionPago);
                $arCentroCostoProceso->setPagoAbierto(1);
                $arCentroCostoProceso->setFechaUltimoPagoProgramado(date_create($dateHasta));                
            }
            
            //2 programacion prima
            if($intTipo == 2) {
                $intAnio = $arCentroCostoProceso->getFechaUltimoPagoPrima()->format('Y');
                if($arCentroCostoProceso->getFechaUltimoPagoPrima()->format('m-d') == '12-30') {
                    $intAnio += 1;
                    $fechaDesde = date_create_from_format('Y/m/d', $intAnio .'/01/01');
                    $fechaHasta = date_create_from_format('Y/m/d', $intAnio .'/06/30');
                    $fechaHastaReal = date_create_from_format('Y/m/d', $intAnio .'/06/30');
                }
                if($arCentroCostoProceso->getFechaUltimoPagoPrima()->format('m-d') == '06-30') {
                    $fechaDesde = date_create_from_format('Y/m/d', $intAnio .'/07/01');
                    $fechaHasta = date_create_from_format('Y/m/d', $intAnio .'/12/30');
                    $fechaHastaReal = date_create_from_format('Y/m/d', $intAnio .'/12/30');
                }   
                $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                $arProgramacionPago->setPagoTipoRel($arPagoTipo);
                $arProgramacionPago->setFechaDesde($fechaDesde);
                $arProgramacionPago->setFechaHasta($fechaHasta);
                $arProgramacionPago->setFechaHastaReal($fechaHastaReal);
                $arProgramacionPago->setDias(0);
                $arProgramacionPago->setCentroCostoRel($arCentroCostoProceso);
                $em->persist($arProgramacionPago);                
                //$arCentroCostoProceso->setFechaUltimoPagoPrima($fechaHasta);                                
            }
            
            //3 programacion cesantias
            if($intTipo == 3) {
                $intAnio = $arCentroCostoProceso->getFechaUltimoPagoCesantias()->format('Y');                
                $intAnio += 1;
                $fechaDesde = date_create_from_format('Y/m/d', $intAnio .'/01/01');
                $fechaHasta = date_create_from_format('Y/m/d', $intAnio .'/12/30');
                  
                $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                $arProgramacionPago->setPagoTipoRel($arPagoTipo);
                $arProgramacionPago->setFechaDesde($fechaDesde);
                $arProgramacionPago->setFechaHasta($fechaHasta);
                $arProgramacionPago->setFechaHastaReal($fechaHasta);
                $arProgramacionPago->setDias(0);
                $arProgramacionPago->setCentroCostoRel($arCentroCostoProceso);
                $em->persist($arProgramacionPago);                
                //$arCentroCostoProceso->setFechaUltimoPagoCesantias($fechaHasta);                                
            }            
            $em->persist($arCentroCostoProceso);
            $em->flush();
        }
        return true;
    }                            
    
    public function listaDQL($strNombre = "", $boolMostrarActivos = "" , $boolMostrarPagoAbierto = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT cc FROM BrasaRecursoHumanoBundle:RhuCentroCosto cc WHERE cc.codigoCentroCostoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND cc.nombre LIKE '%" . $strNombre . "%'";
        } 
        if($boolMostrarActivos) {
            if($boolMostrarActivos == 1) {
                $dql .= " AND cc.estadoActivo = 1";
            } elseif($boolMostrarActivos == 0) {
                $dql .= " AND cc.estadoActivo = 0";
            }            
        }
        /*if($boolMostrarPagoAbierto) {
            if($boolMostrarPagoAbierto == 1) {
                $dql .= " AND cc.pagoAbierto = 1";
            } elseif($boolMostrarPagoAbierto == 0) {
                $dql .= " AND cc.pagoAbierto = 0";
            }            
        }*/
        
        
        $dql .= " ORDER BY cc.nombre";
        return $dql;
    }                        

    public function ListaFechaPagoDQL($strFechaDesde = "", $strFechaHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT cc FROM BrasaRecursoHumanoBundle:RhuCentroCosto cc WHERE cc.estadoActivo = 1";
        if($strFechaDesde != "" ) {
            $dql .= " AND cc.fechaUltimoPagoProgramado >='" . $strFechaDesde . "'";
        }
        
        if($strFechaHasta != "") {
            $dql .= " AND cc.fechaUltimoPagoProgramado <='" . $strFechaHasta . "'";
        }
        
        $dql .= " ORDER BY cc.nombre";
        return $dql;
    } 
     
}