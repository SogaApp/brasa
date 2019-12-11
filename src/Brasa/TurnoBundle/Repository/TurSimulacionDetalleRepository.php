<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurSimulacionDetalleRepository extends EntityRepository {

    public function listaDql($usuario) {        
        $dql   = "SELECT sd FROM BrasaTurnoBundle:TurSimulacionDetalle sd WHERE sd.usuario = '" . $usuario . "'";
        $dql .= " ORDER BY sd.codigoSimulacionDetallePk";
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
                    $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigo);
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

    public function periodo($strFechaDesde, $strFechaHasta, $codigoCentroCosto) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurProgramacionDetalle pd JOIN pd.programacionRel p JOIN pd.recursoRel r "
                . "WHERE p.fecha >= '" . $strFechaDesde . "' AND p.fecha <='" . $strFechaHasta . "' AND r.codigoCentroCostoFk = " . $codigoCentroCosto;
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

    public function nuevo($codigoServicioDetalle, $fechaProgramacion, $usuario = '') {
        $em = $this->getEntityManager();
        $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
        $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigoServicioDetalle);
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$fechaProgramacion->format('m')+1,1,$fechaProgramacion->format('Y'))-1));
        $intDiaInicial = 1;
        $intDiaFinal = $intUltimoDia;        
        $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($fechaProgramacion->format('Y-m-') . $intDiaInicial, $fechaProgramacion->format('Y-m-') . $intDiaFinal);
        $strMesAnio = $fechaProgramacion->format('Y/m');        
        if($arServicioDetalle->getPlantillaRel()) {
            if($arServicioDetalle->getPlantillaRel()) {
                $arPlantilla = $arServicioDetalle->getPlantillaRel();
            }
            $arPlantillaDetalles = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
            $arPlantillaDetalles = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->findBy(array('codigoPlantillaFk' => $arPlantilla->getCodigoPlantillaPk()));
            foreach ($arPlantillaDetalles as $arPlantillaDetalle) {
                $strFechaDesde = $fechaProgramacion->format('Y/m') . "/" . "1";
                $strAnio = $fechaProgramacion->format('Y');
                $intPosicion = $this->devuelvePosicionInicialMatrizPlantilla($strAnio, $arPlantilla->getDiasSecuencia(), $strFechaDesde, $arServicioDetalle->getFechaIniciaPlantilla());
                $arrTurnos = $this->devuelveTurnosMes($arPlantillaDetalle);                    
                $arServicioDetalleRecursos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
                $arServicioDetalleRecursos = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->findBy(array('codigoServicioDetalleFk' => $codigoServicioDetalle, 'posicion' => $arPlantillaDetalle->getPosicion()));
                foreach ($arServicioDetalleRecursos as $arServicioDetalleRecurso) {                        
                    $intPosicionPlantilla = $intPosicion;
                    $arSimulacionDetalle = new \Brasa\TurnoBundle\Entity\TurSimulacionDetalle();                                                                        
                    $arSimulacionDetalle->setPuestoRel($arServicioDetalle->getPuestoRel());
                    $arSimulacionDetalle->setAnio($fechaProgramacion->format('Y'));
                    $arSimulacionDetalle->setMes($fechaProgramacion->format('m'));                        
                    $arSimulacionDetalle->setRecursoRel($arServicioDetalleRecurso->getRecursoRel());
                    $arSimulacionDetalle->setUsuario($usuario);
                    for($i = 1; $i < 32; $i++) {                        
                        $strTurno = $arrTurnos[$intPosicionPlantilla];
                        $strFechaDia = $fechaProgramacion->format('Y-m-') . $i;                            
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
                            $strFechaDiaSiguiente = $fechaProgramacion->format('Y-m-') . ($i+1);
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
                        $boolAplica = $this->aplicaPlantilla($i, $intDiaInicial, $intDiaFinal, $strMesAnio, $arServicioDetalle, $strTurno, $boolFestivo);                        
                        if($boolAplica == TRUE) {
                            if($i == 1) {
                                $arSimulacionDetalle->setDia1($strTurno);
                            }
                            if($i == 2) {
                                $arSimulacionDetalle->setDia2($strTurno);
                            }
                            if($i == 3) {
                                $arSimulacionDetalle->setDia3($strTurno);
                            }
                            if($i == 4) {
                                $arSimulacionDetalle->setDia4($strTurno);
                            }
                            if($i == 5) {
                                $arSimulacionDetalle->setDia5($strTurno);
                            }
                            if($i == 6) {
                                $arSimulacionDetalle->setDia6($strTurno);
                            }
                            if($i == 7) {
                                $arSimulacionDetalle->setDia7($strTurno);
                            }
                            if($i == 8) {
                                $arSimulacionDetalle->setDia8($strTurno);
                            }
                            if($i == 9) {
                                $arSimulacionDetalle->setDia9($strTurno);
                            }
                            if($i == 10) {
                                $arSimulacionDetalle->setDia10($strTurno);
                            }
                            if($i == 11) {
                                $arSimulacionDetalle->setDia11($strTurno);
                            }
                            if($i == 12) {
                                $arSimulacionDetalle->setDia12($strTurno);
                            }
                            if($i == 13) {
                                $arSimulacionDetalle->setDia13($strTurno);
                            }
                            if($i == 14) {
                                $arSimulacionDetalle->setDia14($strTurno);
                            }
                            if($i == 15) {
                                $arSimulacionDetalle->setDia15($strTurno);
                            }
                            if($i == 16) {
                                $arSimulacionDetalle->setDia16($strTurno);
                            }
                            if($i == 17) {
                                $arSimulacionDetalle->setDia17($strTurno);
                            }
                            if($i == 18) {
                                $arSimulacionDetalle->setDia18($strTurno);
                            }
                            if($i == 19) {
                                $arSimulacionDetalle->setDia19($strTurno);
                            }
                            if($i == 20) {
                                $arSimulacionDetalle->setDia20($strTurno);
                            }
                            if($i == 21) {
                                $arSimulacionDetalle->setDia21($strTurno);
                            }
                            if($i == 22) {
                                $arSimulacionDetalle->setDia22($strTurno);
                            }
                            if($i == 23) {
                                $arSimulacionDetalle->setDia23($strTurno);
                            }
                            if($i == 24) {
                                $arSimulacionDetalle->setDia24($strTurno);
                            }
                            if($i == 25) {
                                $arSimulacionDetalle->setDia25($strTurno);
                            }
                            if($i == 26) {
                                $arSimulacionDetalle->setDia26($strTurno);
                            }
                            if($i == 27) {
                                $arSimulacionDetalle->setDia27($strTurno);
                            }
                            if($i == 28) {
                                $arSimulacionDetalle->setDia28($strTurno);
                            }
                            if($i == 29) {
                                $arSimulacionDetalle->setDia29($strTurno);
                            }
                            if($i == 30) {
                                $arSimulacionDetalle->setDia30($strTurno);
                            }
                            if($i == 31) {
                                $arSimulacionDetalle->setDia31($strTurno);
                            }
                        }
                        $intPosicionPlantilla++;
                        if($intPosicionPlantilla == ($arPlantilla->getDiasSecuencia() + 1)) {
                            $intPosicionPlantilla = 1;
                        }
                    }                        
                    $em->persist($arSimulacionDetalle);                        
                }
            }
        } else {                
            $arPlantillaDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetallePlantilla();
            $arPlantillaDetalles = $em->getRepository('BrasaTurnoBundle:TurServicioDetallePlantilla')->findBy(array('codigoServicioDetalleFk' => $codigoServicioDetalle));
            foreach ($arPlantillaDetalles as $arPlantillaDetalle) {
                $strFechaDesde = $fechaProgramacion->format('Y/m') . "/1";
                $strAnio = $fechaProgramacion->format('Y');
                $intPosicion = $this->devuelvePosicionInicialMatrizPlantilla($strAnio, $arServicioDetalle->getDiasSecuencia(), $strFechaDesde, $arServicioDetalle->getFechaIniciaPlantilla());
                $arrTurnos = $this->devuelveTurnosMes($arPlantillaDetalle);
                $arServicioDetalleRecursos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
                $arServicioDetalleRecursos = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->findBy(array('codigoServicioDetalleFk' => $codigoServicioDetalle, 'posicion' => $arPlantillaDetalle->getPosicion()));
                foreach ($arServicioDetalleRecursos as $arServicioDetalleRecurso) {
                    $intPosicionPlantilla = $intPosicion;
                    $arSimulacionDetalle = new \Brasa\TurnoBundle\Entity\TurSimulacionDetalle();                                                                        
                    $arSimulacionDetalle->setPuestoRel($arServicioDetalle->getPuestoRel());
                    $arSimulacionDetalle->setAnio($fechaProgramacion->format('Y'));
                    $arSimulacionDetalle->setMes($fechaProgramacion->format('m')); 
                    $arSimulacionDetalle->setRecursoRel($arServicioDetalleRecurso->getRecursoRel());
                    $arSimulacionDetalle->setUsuario($usuario);
                    for($i = 1; $i < 32; $i++) {                            
                        $strTurno = $arrTurnos[$intPosicionPlantilla];
                        $strFechaDia = $fechaProgramacion->format('Y-m-') . $i;
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
                            $strFechaDiaSiguiente = $fechaProgramacion->format('Y-m-') . ($i+1);
                            $dateFechaDiaSiguiente = date_create($strFechaDiaSiguiente);                            
                            $boolFestivoSiguiente = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->festivo($arFestivos, $dateFechaDiaSiguiente);                                
                            if($boolFestivoSiguiente == 1) {
                                $strTurno = $arrTurnos['domingoFestivo'];                                
                            }                                
                        }                            
                        $boolAplica = $this->aplicaPlantilla($i, $intDiaInicial, $intDiaFinal, $strMesAnio, $arServicioDetalle, $strTurno, $boolFestivo);
                        if($boolAplica == TRUE) {
                            if($i == 1) {
                                $arSimulacionDetalle->setDia1($strTurno);
                            }
                            if($i == 2) {
                                $arSimulacionDetalle->setDia2($strTurno);
                            }
                            if($i == 3) {
                                $arSimulacionDetalle->setDia3($strTurno);
                            }
                            if($i == 4) {
                                $arSimulacionDetalle->setDia4($strTurno);
                            }
                            if($i == 5) {
                                $arSimulacionDetalle->setDia5($strTurno);
                            }
                            if($i == 6) {
                                $arSimulacionDetalle->setDia6($strTurno);
                            }
                            if($i == 7) {
                                $arSimulacionDetalle->setDia7($strTurno);
                            }
                            if($i == 8) {
                                $arSimulacionDetalle->setDia8($strTurno);
                            }
                            if($i == 9) {
                                $arSimulacionDetalle->setDia9($strTurno);
                            }
                            if($i == 10) {
                                $arSimulacionDetalle->setDia10($strTurno);
                            }
                            if($i == 11) {
                                $arSimulacionDetalle->setDia11($strTurno);
                            }
                            if($i == 12) {
                                $arSimulacionDetalle->setDia12($strTurno);
                            }
                            if($i == 13) {
                                $arSimulacionDetalle->setDia13($strTurno);
                            }
                            if($i == 14) {
                                $arSimulacionDetalle->setDia14($strTurno);
                            }
                            if($i == 15) {
                                $arSimulacionDetalle->setDia15($strTurno);
                            }
                            if($i == 16) {
                                $arSimulacionDetalle->setDia16($strTurno);
                            }
                            if($i == 17) {
                                $arSimulacionDetalle->setDia17($strTurno);
                            }
                            if($i == 18) {
                                $arSimulacionDetalle->setDia18($strTurno);
                            }
                            if($i == 19) {
                                $arSimulacionDetalle->setDia19($strTurno);
                            }
                            if($i == 20) {
                                $arSimulacionDetalle->setDia20($strTurno);
                            }
                            if($i == 21) {
                                $arSimulacionDetalle->setDia21($strTurno);
                            }
                            if($i == 22) {
                                $arSimulacionDetalle->setDia22($strTurno);
                            }
                            if($i == 23) {
                                $arSimulacionDetalle->setDia23($strTurno);
                            }
                            if($i == 24) {
                                $arSimulacionDetalle->setDia24($strTurno);
                            }
                            if($i == 25) {
                                $arSimulacionDetalle->setDia25($strTurno);
                            }
                            if($i == 26) {
                                $arSimulacionDetalle->setDia26($strTurno);
                            }
                            if($i == 27) {
                                $arSimulacionDetalle->setDia27($strTurno);
                            }
                            if($i == 28) {
                                $arSimulacionDetalle->setDia28($strTurno);
                            }
                            if($i == 29) {
                                $arSimulacionDetalle->setDia29($strTurno);
                            }
                            if($i == 30) {
                                $arSimulacionDetalle->setDia30($strTurno);
                            }
                            if($i == 31) {
                                $arSimulacionDetalle->setDia31($strTurno);
                            }
                        }
                        $intPosicionPlantilla++;
                        if($intPosicionPlantilla == ($arServicioDetalle->getDiasSecuencia() + 1)) {
                            $intPosicionPlantilla = 1;
                        }
                    }
                    $em->persist($arSimulacionDetalle);                    
                }
            }                
        }      
        $em->flush();
    }

    private function aplicaPlantilla ($i, $intDiaInicial, $intDiaFinal, $strMesAnio, $arPedidoDetalle, $strTurno, $festivo) {
        $em = $this->getEntityManager();
        $boolResultado = FALSE;
        if($strTurno != '') {
            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurno);
            if($arTurno->getDescanso() == 1 && ($i >= $intDiaInicial && $i <= $intDiaFinal)) {
                $boolResultado = TRUE;
            } else {
                if($i >= $intDiaInicial && $i <= $intDiaFinal) {
                    $strFecha = $strMesAnio . '/' . $i;
                    $dateNuevaFecha = date_create($strFecha);
                    $diaSemana = $dateNuevaFecha->format('N');
                    if($diaSemana == 1) {
                        if($arPedidoDetalle->getLunes() == 1) {
                            $boolResultado = TRUE;
                        }
                    }
                    if($diaSemana == 2) {
                        if($arPedidoDetalle->getMartes() == 1) {
                            $boolResultado = TRUE;
                        }
                    }
                    if($diaSemana == 3) {
                        if($arPedidoDetalle->getMiercoles() == 1) {
                            $boolResultado = TRUE;
                        }
                    }
                    if($diaSemana == 4) {
                        if($arPedidoDetalle->getJueves() == 1) {
                            $boolResultado = TRUE;
                        }
                    }
                    if($diaSemana == 5) {
                        if($arPedidoDetalle->getViernes() == 1) {
                            $boolResultado = TRUE;
                        }
                    }
                    if($diaSemana == 6) {
                        if($arPedidoDetalle->getSabado() == 1) {
                            $boolResultado = TRUE;
                        }
                    }
                    if($diaSemana == 7) {
                        if($arPedidoDetalle->getDomingo() == 1) {
                            $boolResultado = TRUE;
                        }
                    }
                    if($festivo == 1) {
                        if($arPedidoDetalle->getFestivo() == 1) {
                            $boolResultado = TRUE;
                        }                        
                    }
                }                            
            }
        }

        return $boolResultado;
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
    
}