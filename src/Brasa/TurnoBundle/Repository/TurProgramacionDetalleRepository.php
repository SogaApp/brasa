<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurProgramacionDetalleRepository extends EntityRepository {

    public function listaDql($codigoProgramacion = "", $codigoPuesto = "", $codigoPedidoDetalle = "") {        
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurProgramacionDetalle pd WHERE pd.codigoProgramacionDetallePk <> 0 ";        
        if($codigoPuesto == 0) {
            $codigoPuesto = '';
        }
        if($codigoPedidoDetalle == 0) {
            $codigoPedidoDetalle = '';
        }        
        if($codigoProgramacion != '') {
            $dql .= " AND pd.codigoProgramacionFk = " . $codigoProgramacion . " ";  
        }  
        if($codigoPuesto != '') {
            $dql .= " AND pd.codigoPuestoFk = " . $codigoPuesto . " ";  
        }      
        if($codigoPedidoDetalle != '') {
            $dql .= " AND pd.codigoPedidoDetalleFk = " . $codigoPedidoDetalle . " ";  
        }         
        $dql .= " ORDER BY pd.codigoPuestoFk, pd.codigoPedidoDetalleFk";
        return $dql;
    } 
    
    public function consultaDetalleDql($codigoCliente, $codigoRecurso, $codigoCentroCosto, $strFechaDesde = "", $strFechaHasta = "", $boolEstadoAutorizado = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurProgramacionDetalle pd JOIN pd.programacionRel p JOIN pd.recursoRel r "
                . "WHERE pd.codigoProgramacionDetallePk <> 0 ";
        if($codigoCliente != '') {
            $dql = $dql . "AND p.codigoClienteFk = " . $codigoCliente;
        }
        if($codigoRecurso != '') {
            $dql = $dql . "AND pd.codigoRecursoFk = " . $codigoRecurso;
        }
        if($codigoCentroCosto != '') {
            $dql = $dql . "AND r.codigoCentroCostoFk = " . $codigoCentroCosto;
        }
        if($strFechaDesde != "") {
            $dql .= " AND p.fecha >= '" . $strFechaDesde . "'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND p.fecha <= '" . $strFechaHasta . "'";
        }
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND p.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND p.estadoAutorizado = 0";
        }
        $dql .= " ORDER BY p.codigoClienteFk";
        return $dql;
    }

    public function pedido($codigoPedidoDetalle) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.horas) as horas "
                . "FROM BrasaTurnoBundle:TurProgramacionDetalle pd "
                . "WHERE pd.codigoPedidoDetalleFk = " . $codigoPedidoDetalle;
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado[0];
    }

    public function eliminarDetallesSeleccionados($arrSeleccionados) {
        $strResultado = "";
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {
                $intNumeroRegistros = 0;
                $dql   = "SELECT COUNT(spd.codigoProgramacionDetalleFk) as numeroRegistros FROM BrasaTurnoBundle:TurSoportePagoDetalle spd "
                        . "WHERE spd.codigoProgramacionDetalleFk = " . $codigo;
                $query = $em->createQuery($dql);
                $arrSoportePagoDetalles = $query->getSingleResult();
                if($arrSoportePagoDetalles) {
                    $intNumeroRegistros = $arrSoportePagoDetalles['numeroRegistros'];
                }
                if($intNumeroRegistros <= 0) {                    
                    $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                    $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigo);
                    $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arProgramacionDetalle->getCodigoPedidoDetalleFk());                                                    
                    $horasProgramadas = $arPedidoDetalle->getHorasProgramadas() - $arProgramacionDetalle->getHoras();
                    $horasDiurnasProgramadas = $arPedidoDetalle->getHorasDiurnasProgramadas() - $arProgramacionDetalle->getHorasDiurnas();
                    $horasNocturnasProgramadas = $arPedidoDetalle->getHorasNocturnasProgramadas() - $arProgramacionDetalle->getHorasNocturnas();
                    $arPedidoDetalle->setHorasProgramadas($horasProgramadas);
                    $arPedidoDetalle->setHorasDiurnasProgramadas($horasDiurnasProgramadas);
                    $arPedidoDetalle->setHorasNocturnasProgramadas($horasNocturnasProgramadas);                                       
                    $em->persist($arPedidoDetalle);
                    $em->remove($arProgramacionDetalle);
                } else {
                    $strResultado .= "El detalle " . $codigo . " no se puede eliminar porque tiene soportes de pago asociados ";
                }
            }
            $em->flush();
        }
        return $strResultado;
    }

    public function numeroRegistros($codigo) {
        $em = $this->getEntityManager();
        $arDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoProgramacionFk' => $codigo));
        return count($arDetalles);
    }

    public function periodo($strFechaDesde, $strFechaHasta, $codigoRecursoGrupo = "", $codigoRecurso = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurProgramacionDetalle pd JOIN pd.programacionRel p JOIN pd.recursoRel r "
                . "WHERE p.fecha >= '" . $strFechaDesde . "' AND p.fecha <='" . $strFechaHasta . "'";
        if($codigoRecursoGrupo != "") {
            $dql .= " AND r.codigoRecursoGrupoFk = " . $codigoRecursoGrupo;
        }
        if($codigoRecurso != "") {
            $dql .= " AND pd.codigoRecursoFk = " . $codigoRecurso;
        }        
        $query = $em->createQuery($dql);                
        $arResultado = $query->getResult();
        return $arResultado;
    }

    public function periodoDias($anio, $mes, $codigoRecurso = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd.codigoProgramacionDetallePk, pd.dia1, pd.dia2, pd.dia3, pd.dia4, pd.dia5, pd.dia6, pd.dia7, pd.dia8, pd.dia9, pd.dia10, "
                . "pd.dia11, pd.dia12, pd.dia13, pd.dia14, pd.dia15, pd.dia16, pd.dia17, pd.dia18, pd.dia19, pd.dia20, "
                . "pd.dia21, pd.dia22, pd.dia23, pd.dia24, pd.dia25, pd.dia26, pd.dia27, pd.dia28, pd.dia29, pd.dia30, pd.dia31 "
                . "FROM BrasaTurnoBundle:TurProgramacionDetalle pd "
                . "WHERE pd.anio = " . $anio . " AND pd.mes =" . $mes;
        if($codigoRecurso != "") {
            $dql .= " AND pd.codigoRecursoFk = " . $codigoRecurso;
        }        
        $query = $em->createQuery($dql);                
        $arResultado = $query->getResult();
        return $arResultado;
    }    
    
    public function validarRecurso($codigoProgramacion) {
        $em = $this->getEntityManager();
        $boolResultado = TRUE;
        $dql   = "SELECT pd.codigoProgramacionDetallePk FROM BrasaTurnoBundle:TurProgramacionDetalle pd "
                . "WHERE pd.codigoProgramacionFk = " . $codigoProgramacion . " AND pd.codigoRecursoFk IS NULL";
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        if(count($arResultado) > 0) {
            $boolResultado = FALSE;
        }
        return $boolResultado;
    }
    
    public function validarPuesto($codigoProgramacion) {
        $em = $this->getEntityManager();
        $boolResultado = TRUE;
        $dql   = "SELECT pd.codigoProgramacionDetallePk FROM BrasaTurnoBundle:TurProgramacionDetalle pd "
                . "WHERE pd.codigoProgramacionFk = " . $codigoProgramacion . " AND pd.codigoPuestoFk IS NULL";
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        if(count($arResultado) > 0) {
            $boolResultado = FALSE;
        }
        return $boolResultado;
    }    

    public function nuevo($codigoPedidoDetalle, $arProgramacion) {
        $em = $this->getEntityManager();
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);
        $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $validarHoras = $arConfiguracion->getValidarHorasProgramacion();        
        $horasDiurnas = $arPedidoDetalle->getHorasDiurnasProgramadas();
        $horasNocturnas = $arPedidoDetalle->getHorasNocturnasProgramadas();
        $horasDiurnasContratadas = $arPedidoDetalle->getHorasDiurnas();
        $horasNocturnasContratadas = $arPedidoDetalle->getHorasNocturnas();        
        $intDiaInicial = $arPedidoDetalle->getDiaDesde();
        $intDiaFinal = $arPedidoDetalle->getDiaHasta();
        $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arProgramacion->getFecha()->format('Y-m-') . $intDiaInicial, $arProgramacion->getFecha()->format('Y-m-') . $intDiaFinal);
        $strMesAnio = $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y/m');
        if($arPedidoDetalle->getPlantillaRel()) {
            if($arPedidoDetalle->getPlantillaRel()) {
                $arPlantilla = $arPedidoDetalle->getPlantillaRel();
            }
            $arPlantillaDetalles = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
            $arPlantillaDetalles = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->findBy(array('codigoPlantillaFk' => $arPlantilla->getCodigoPlantillaPk()));
            foreach ($arPlantillaDetalles as $arPlantillaDetalle) {
                $strFechaDesde = $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y/m') . "/" . $arPedidoDetalle->getDiaDesde();
                $strAnio = $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y');
                $intPosicion = $this->devuelvePosicionInicialMatrizPlantilla($strAnio, $arPlantilla->getDiasSecuencia(), $strFechaDesde, $arPedidoDetalle->getFechaIniciaPlantilla());
                $arrTurnos = $this->devuelveTurnosMes($arPlantillaDetalle);                    
                $arPedidoDetalleRecursos = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso();
                $arPedidoDetalleRecursos = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleRecurso')->findBy(array('codigoPedidoDetalleFk' => $codigoPedidoDetalle, 'posicion' => $arPlantillaDetalle->getPosicion()));
                foreach ($arPedidoDetalleRecursos as $arPedidoDetalleRecurso) {                        
                    $horasDiurnasProgramacion = 0;
                    $horasNocturnasProgramacion = 0;
                    $intPosicionPlantilla = $intPosicion;
                    $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                    $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                    $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                    $arProgramacionDetalle->setProyectoRel($arPedidoDetalle->getProyectoRel());
                    $arProgramacionDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                    $arProgramacionDetalle->setAnio($arProgramacion->getFecha()->format('Y'));
                    $arProgramacionDetalle->setMes($arProgramacion->getFecha()->format('m'));                        
                    $arProgramacionDetalle->setRecursoRel($arPedidoDetalleRecurso->getRecursoRel());
                    $arProgramacionDetalle->setAjusteProgramacion($arPedidoDetalle->getAjusteProgramacion());
                    for($i = 1; $i < 32; $i++) {                        
                        $strTurno = $arrTurnos[$intPosicionPlantilla];
                        $strFechaDia = $arProgramacion->getFecha()->format('Y-m-') . $i;
                        $dateFechaDia = date_create($strFechaDia);
                        $diaSemana = $dateFechaDia->format('N');

                        $boolFestivo = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->festivo($arFestivos, $dateFechaDia);
                        if($diaSemana == 1 && isset($arrTurnos['lunes'])) {
                            $strTurno = $arrTurnos['lunes'];
                        }
                        if($diaSemana == 2 && isset($arrTurnos['martes'])) {
                            $strTurno = $arrTurnos['martes'];
                        }
                        if($diaSemana == 3 && isset($arrTurnos['miercoles'])) {
                            $strTurno = $arrTurnos['miercoles'];
                        }
                        if($diaSemana == 4 && isset($arrTurnos['jueves'])) {
                            $strTurno = $arrTurnos['jueves'];
                        }
                        if($diaSemana == 5 && isset($arrTurnos['viernes'])) {
                            $strTurno = $arrTurnos['viernes'];
                        }
                        if($diaSemana == 6 && isset($arrTurnos['sabado'])) {
                            $strTurno = $arrTurnos['sabado'];
                        }
                        if($diaSemana == 7 && isset($arrTurnos['domingo'])) {
                            $strTurno = $arrTurnos['domingo'];
                        }
                        if($diaSemana == 7 && isset($arrTurnos['domingoFestivo'])) {
                            $strFechaDiaSiguiente = $arProgramacion->getFecha()->format('Y-m-') . ($i+1);
                            $dateFechaDiaSiguiente = date_create($strFechaDiaSiguiente);                            
                            $boolFestivoSiguiente = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->festivo($arFestivos, $dateFechaDiaSiguiente);                                
                            if($boolFestivoSiguiente == 1) {
                                $strTurno = $arrTurnos['domingoFestivo'];                                
                            }                                
                        }                            
                        if($boolFestivo == 1 && isset($arrTurnos['festivo'])) {
                            $strTurno = $arrTurnos['festivo'];
                        }

                        if($arPlantilla->getHomologarCodigoTurno() == 1) {
                            $strTurno = $this->devuelveCodigoTurno($arrTurnos[$intPosicionPlantilla]);
                        }
                        $arrTurno = $this->aplicaPlantilla($i, $intDiaInicial, $intDiaFinal, $strMesAnio, $arPedidoDetalle, $strTurno, $boolFestivo);                        
                        if($arrTurno['aplica'] == TRUE) {
                            if($i == 1) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia1($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia1($arrTurno['turno']);
                                    }                                     
                                }                               
                            }
                            if($i == 2) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia2($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia2($arrTurno['turno']);
                                    }                                    
                                }
                            }
                            if($i == 3) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia3($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia3($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 4) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia4($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia4($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 5) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia5($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia5($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 6) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia6($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia6($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 7) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia7($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia7($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 8) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia8($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia8($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 9) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia9($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia9($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 10) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia10($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia10($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 11) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia11($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia11($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 12) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia12($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia12($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 13) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia13($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia13($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 14) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia14($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia14($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 15) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia15($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia15($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 16) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia16($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia16($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 17) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia17($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia17($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 18) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia18($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia18($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 19) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia19($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia19($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 20) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia20($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia20($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 21) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia21($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia21($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 22) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia22($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia22($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 23) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia23($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia23($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 24) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia24($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia24($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 25) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia25($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia25($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 26) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia26($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia26($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 27) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia27($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia27($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 28) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia28($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia28($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 29) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia29($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia29($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 30) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia30($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia30($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                            if($i == 31) {
                                if($validarHoras == false) {
                                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                    $arProgramacionDetalle->setDia31($arrTurno['turno']);                                    
                                } else {
                                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                        $horasDiurnas += $arrTurno['horasDiurnas'];
                                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia31($arrTurno['turno']);
                                    }                                    
                                }                                
                            }
                        }
                        $intPosicionPlantilla++;
                        if($intPosicionPlantilla == ($arPlantilla->getDiasSecuencia() + 1)) {
                            $intPosicionPlantilla = 1;
                        }
                    } 
                                        
                    $arProgramacionDetalle->setHoras($horasDiurnasProgramacion + $horasNocturnasProgramacion);
                    $arProgramacionDetalle->setHorasDiurnas($horasDiurnasProgramacion);
                    $arProgramacionDetalle->setHorasNocturnas($horasNocturnasProgramacion);
                    $em->persist($arProgramacionDetalle);                        
                }
            }
        } else {
            if($arPedidoDetalle->getCodigoServicioDetalleFk()) {
                $arPlantillaDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetallePlantilla();
                $arPlantillaDetalles = $em->getRepository('BrasaTurnoBundle:TurServicioDetallePlantilla')->findBy(array('codigoServicioDetalleFk' => $arPedidoDetalle->getCodigoServicioDetalleFk()));
                foreach ($arPlantillaDetalles as $arPlantillaDetalle) {
                    $strFechaDesde = $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y/m') . "/" . $arPedidoDetalle->getDiaDesde();
                    $strAnio = $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y');
                    $intPosicion = $this->devuelvePosicionInicialMatrizPlantilla($strAnio, $arPedidoDetalle->getServicioDetalleRel()->getDiasSecuencia(), $strFechaDesde, $arPedidoDetalle->getFechaIniciaPlantilla());
                    $arrTurnos = $this->devuelveTurnosMes($arPlantillaDetalle);                        
                    $arPedidoDetalleRecursos = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso();
                    $arPedidoDetalleRecursos = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleRecurso')->findBy(array('codigoPedidoDetalleFk' => $codigoPedidoDetalle, 'posicion' => $arPlantillaDetalle->getPosicion()));
                    foreach ($arPedidoDetalleRecursos as $arPedidoDetalleRecurso) {
                        $intPosicionPlantilla = $intPosicion;
                        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                        $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                        $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                        $arProgramacionDetalle->setProyectoRel($arPedidoDetalle->getProyectoRel());
                        $arProgramacionDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                        $arProgramacionDetalle->setAjusteProgramacion($arPedidoDetalle->getAjusteProgramacion());                                                        
                        $arProgramacionDetalle->setAnio($arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y'));
                        $arProgramacionDetalle->setMes($arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('m'));                        
                        $arProgramacionDetalle->setRecursoRel($arPedidoDetalleRecurso->getRecursoRel());                            
                        for($i = 1; $i < 32; $i++) {                            
                            $strTurno = $arrTurnos[$intPosicionPlantilla];
                            $strFechaDia = $arProgramacion->getFecha()->format('Y-m-') . $i;
                            $dateFechaDia = date_create($strFechaDia);
                            $diaSemana = $dateFechaDia->format('N');

                            $boolFestivo = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->festivo($arFestivos, $dateFechaDia);
                            if($diaSemana == 1 && isset($arrTurnos['lunes'])) {
                                $strTurno = $arrTurnos['lunes'];
                            }
                            if($diaSemana == 2 && isset($arrTurnos['martes'])) {
                                $strTurno = $arrTurnos['martes'];
                            }
                            if($diaSemana == 3 && isset($arrTurnos['miercoles'])) {
                                $strTurno = $arrTurnos['miercoles'];
                            }
                            if($diaSemana == 4 && isset($arrTurnos['jueves'])) {
                                $strTurno = $arrTurnos['jueves'];
                            }
                            if($diaSemana == 5 && isset($arrTurnos['viernes'])) {
                                $strTurno = $arrTurnos['viernes'];
                            }
                            if($diaSemana == 6 && isset($arrTurnos['sabado'])) {
                                $strTurno = $arrTurnos['sabado'];
                            }
                            if($diaSemana == 7 && isset($arrTurnos['domingo'])) {
                                $strTurno = $arrTurnos['domingo'];
                            }
                            if($boolFestivo == 1 && isset($arrTurnos['festivo'])) {
                                $strTurno = $arrTurnos['festivo'];
                            }                 
                            if($diaSemana == 7 && isset($arrTurnos['domingoFestivo'])) {
                                $strFechaDiaSiguiente = $arProgramacion->getFecha()->format('Y-m-') . ($i+1);
                                $dateFechaDiaSiguiente = date_create($strFechaDiaSiguiente);                            
                                $boolFestivoSiguiente = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->festivo($arFestivos, $dateFechaDiaSiguiente);                                
                                if($boolFestivoSiguiente == 1) {
                                    $strTurno = $arrTurnos['domingoFestivo'];                                
                                }                                
                            }                                
                            $arrTurno = $this->aplicaPlantilla($i, $intDiaInicial, $intDiaFinal, $strMesAnio, $arPedidoDetalle, $strTurno, $boolFestivo);
                            if($arrTurno['aplica'] == TRUE) {
                                if($i == 1) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia1($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia1($arrTurno['turno']);
                                        }                                     
                                    }                               
                                }
                                if($i == 2) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia2($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia2($arrTurno['turno']);
                                        }                                    
                                    }
                                }
                                if($i == 3) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia3($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia3($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 4) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia4($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia4($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 5) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia5($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia5($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 6) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia6($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia6($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 7) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia7($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia7($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 8) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia8($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia8($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 9) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia9($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia9($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 10) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia10($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia10($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 11) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia11($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia11($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 12) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia12($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia12($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 13) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia13($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia13($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 14) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia14($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia14($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 15) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia15($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia15($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 16) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia16($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia16($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 17) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia17($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia17($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 18) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia18($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia18($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 19) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia19($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia19($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 20) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia20($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia20($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 21) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia21($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia21($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 22) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia22($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia22($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 23) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia23($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia23($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 24) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia24($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia24($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 25) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia25($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia25($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 26) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia26($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia26($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 27) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia27($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia27($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 28) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia28($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia28($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 29) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia29($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia29($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 30) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia30($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia30($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                                if($i == 31) {
                                    if($validarHoras == false) {
                                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                        $arProgramacionDetalle->setDia31($arrTurno['turno']);                                    
                                    } else {
                                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                                            $horasDiurnas += $arrTurno['horasDiurnas'];
                                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                                            $arProgramacionDetalle->setDia31($arrTurno['turno']);
                                        }                                    
                                    }                                
                                }
                            }
                            $intPosicionPlantilla++;
                            if($intPosicionPlantilla == ($arPedidoDetalle->getServicioDetalleRel()->getDiasSecuencia() + 1)) {
                                $intPosicionPlantilla = 1;
                            }
                        }
                        
                        
                        
                        $em->persist($arProgramacionDetalle);                            
                    }


                }
            } else {
                if($arPedidoDetalle->getCantidadRecurso() != 0) {
                    $intCantidad = $arPedidoDetalle->getCantidadRecurso();
                    for($k = 1; $k <= $intCantidad; $k++) {
                        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                        $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                        $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                        $arProgramacionDetalle->setProyectoRel($arPedidoDetalle->getProyectoRel());
                        $arProgramacionDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                        $arProgramacionDetalle->setAjusteProgramacion($arPedidoDetalle->getAjusteProgramacion());
                        $arProgramacionDetalle->setAnio($arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y'));
                        $arProgramacionDetalle->setMes($arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('m'));
                        $em->persist($arProgramacionDetalle);
                    }
                }
            }
        }
                
        $arPedidoDetalle->setHorasDiurnasProgramadas($horasDiurnas);
        $arPedidoDetalle->setHorasNocturnasProgramadas($horasNocturnas);
        $arPedidoDetalle->setHorasProgramadas($horasDiurnas+$horasNocturnas);        
        $arPedidoDetalle->setEstadoProgramado(1);
        $em->persist($arPedidoDetalle);
        $em->flush();
    }

    private function aplicaPlantilla ($i, $intDiaInicial, $intDiaFinal, $strMesAnio, $arPedidoDetalle, $strTurno, $festivo) {
        $em = $this->getEntityManager();
        $arrTurno = array('turno' => null, 'horasDiurnas' => 0, 'horasNocturnas' => 0, 'aplica' => false);        
        if($strTurno != '') {
            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurno);
            if($arTurno->getDescanso() == 1 && ($i >= $intDiaInicial && $i <= $intDiaFinal)) {
                $arrTurno['aplica'] = true; 
                $arrTurno['turno'] = $strTurno;
                $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas();                
            } else {
                if($i >= $intDiaInicial && $i <= $intDiaFinal) {
                    $strFecha = $strMesAnio . '/' . $i;
                    $dateNuevaFecha = date_create($strFecha);
                    $diaSemana = $dateNuevaFecha->format('N');
                    if($diaSemana == 1) {
                        if($arPedidoDetalle->getLunes() == 1) {
                            $arrTurno['aplica'] = true; 
                            $arrTurno['turno'] = $strTurno;
                            $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                            $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas(); 
                        }
                    }
                    if($diaSemana == 2) {
                        if($arPedidoDetalle->getMartes() == 1) {
                            $arrTurno['aplica'] = true; 
                            $arrTurno['turno'] = $strTurno;
                            $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                            $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas(); 
                        }
                    }
                    if($diaSemana == 3) {
                        if($arPedidoDetalle->getMiercoles() == 1) {
                            $arrTurno['aplica'] = true; 
                            $arrTurno['turno'] = $strTurno;
                            $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                            $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas(); 
                        }
                    }
                    if($diaSemana == 4) {
                        if($arPedidoDetalle->getJueves() == 1) {
                            $arrTurno['aplica'] = true; 
                            $arrTurno['turno'] = $strTurno;
                            $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                            $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas(); 
                        }
                    }
                    if($diaSemana == 5) {
                        if($arPedidoDetalle->getViernes() == 1) {
                            $arrTurno['aplica'] = true; 
                            $arrTurno['turno'] = $strTurno;
                            $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                            $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas(); 
                        }
                    }
                    if($diaSemana == 6) {
                        if($arPedidoDetalle->getSabado() == 1) {
                            $arrTurno['aplica'] = true; 
                            $arrTurno['turno'] = $strTurno;
                            $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                            $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas(); 
                        }
                    }
                    if($diaSemana == 7) {
                        if($arPedidoDetalle->getDomingo() == 1) {
                            $arrTurno['aplica'] = true; 
                            $arrTurno['turno'] = $strTurno;
                            $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                            $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas(); 
                        }
                    }
                    if($festivo == 1) {
                        if($arPedidoDetalle->getFestivo() == 1) {
                            $arrTurno['aplica'] = true; 
                            $arrTurno['turno'] = $strTurno;
                            $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                            $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas(); 
                        }                        
                    }
                }                            
            }
        }

        return $arrTurno;
    }

    private function devuelvePosicionInicialMatrizPlantilla($strAnio, $intPosiciones, $strFechaHasta, $dateFechaDesde) {
        if($intPosiciones == 0) {
            $intPosiciones = 1;
        }
        $intPos = 1;

        $dateFechaHasta = date_create($strFechaHasta);
        //$strFecha = $strAnio."/01/1";
        $strFecha = $dateFechaDesde->format('Y/m/j');
        if($dateFechaDesde < $dateFechaHasta) {
            while($strFecha != $strFechaHasta) {
                //$dateFecha = date_create($strAnio."/01/01");
                $nuevafecha = strtotime ( '+1 day' , strtotime ( $strFecha ) ) ;
                $strFecha = date ( 'Y/m/j' , $nuevafecha );

                $intPos++;
                if($intPos == ($intPosiciones+1)) {
                    $intPos = 1;
                }
            }
        }
        return $intPos;
    }

    private function devuelveTurnosMes($arPlantillaDetalle) {
        $arrTurnos = array(
            '1' => $arPlantillaDetalle->getDia1(),
            '2' => $arPlantillaDetalle->getDia2(),
            '3' => $arPlantillaDetalle->getDia3(),
            '4' => $arPlantillaDetalle->getDia4(),
            '5' => $arPlantillaDetalle->getDia5(),
            '6' => $arPlantillaDetalle->getDia6(),
            '7' => $arPlantillaDetalle->getDia7(),
            '8' => $arPlantillaDetalle->getDia8(),
            '9' => $arPlantillaDetalle->getDia9(),
            '10' => $arPlantillaDetalle->getDia10(),
            '11' => $arPlantillaDetalle->getDia11(),
            '12' => $arPlantillaDetalle->getDia12(),
            '13' => $arPlantillaDetalle->getDia13(),
            '14' => $arPlantillaDetalle->getDia14(),
            '15' => $arPlantillaDetalle->getDia15(),
            '16' => $arPlantillaDetalle->getDia16(),
            '17' => $arPlantillaDetalle->getDia17(),
            '18' => $arPlantillaDetalle->getDia18(),
            '19' => $arPlantillaDetalle->getDia19(),
            '20' => $arPlantillaDetalle->getDia20(),
            '21' => $arPlantillaDetalle->getDia21(),
            '22' => $arPlantillaDetalle->getDia22(),
            '23' => $arPlantillaDetalle->getDia23(),
            '24' => $arPlantillaDetalle->getDia24(),
            '25' => $arPlantillaDetalle->getDia25(),
            '26' => $arPlantillaDetalle->getDia26(),
            '27' => $arPlantillaDetalle->getDia27(),
            '28' => $arPlantillaDetalle->getDia28(),
            '29' => $arPlantillaDetalle->getDia29(),
            '30' => $arPlantillaDetalle->getDia30(),
            '31' => $arPlantillaDetalle->getDia31(),
            'lunes' => $arPlantillaDetalle->getLunes(),
            'martes' => $arPlantillaDetalle->getMartes(),
            'miercoles' => $arPlantillaDetalle->getMiercoles(),
            'jueves' => $arPlantillaDetalle->getJueves(),
            'viernes' => $arPlantillaDetalle->getViernes(),
            'sabado' => $arPlantillaDetalle->getSabado(),
            'domingo' => $arPlantillaDetalle->getDomingo(),
            'domingoFestivo' => $arPlantillaDetalle->getDomingoFestivo(),
            'festivo' => $arPlantillaDetalle->getFestivo(),);
        return $arrTurnos;
    }

    private function devuelveCodigoTurno ($strCodigo) {
        $strCodigoReal = "";
        if($strCodigo == 'A') {
            $strCodigoReal = '1';
        }
        if($strCodigo == 'B') {
            $strCodigoReal = '2';
        }
        if($strCodigo == 'D') {
            $strCodigoReal = 'D';
        }
        return $strCodigoReal;
    }

    public function detallesPedido ($codigoPedidoDetalle, $strAnio, $strMes) {
        $em = $this->getEntityManager();
        $strSql = "SELECT
                    COUNT(codigo_programacion_detalle_pk) as numeroRegistros,
                    SUM(horas) as horas,                    
                    SUM(horas * vr_hora_recurso) as vrRecurso
                    FROM tur_programacion_detalle
                    WHERE codigo_pedido_detalle_fk = $codigoPedidoDetalle AND anio = $strAnio AND mes = $strMes";        
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);
        $statement->execute();
        $results = $statement->fetchAll();
        return $results[0];        
    }
    
    public function detallesRecurso ($codigoRecurso, $strAnio, $strMes) {
        $em = $this->getEntityManager();
        $strSql = "SELECT
                    COUNT(codigo_programacion_detalle_pk) as numeroRegistros,
                    SUM(horas) as horas
                    FROM tur_programacion_detalle
                    WHERE codigo_recurso_fk = $codigoRecurso AND anio = $strAnio AND mes = $strMes";        
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);
        $statement->execute();
        $results = $statement->fetchAll();
        return $results[0];        
    }
    
    public function marcarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigo);                
                if($arProgramacionDetalle->getMarca() == 1) {
                    $arProgramacionDetalle->setMarca(0);
                } else {
                    $arProgramacionDetalle->setMarca(1);
                }                
            }                                         
            $em->flush();       
        }
        
    }            
    
    public function ajustarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigo);                
                if($arProgramacionDetalle->getAjusteProgramacion() == 1) {
                    $arProgramacionDetalle->setAjusteProgramacion(0);
                } else {
                    $arProgramacionDetalle->setAjusteProgramacion(1);
                }                
            }                                         
            $em->flush();       
        }
        
    }     
}