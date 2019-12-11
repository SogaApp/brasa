<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurNovedadRepository extends EntityRepository {

    public function listaDql($codigo = '', $codigoRecurso = "", $boolEstadoAplicado = "", $codigoNovedad = "") {
        $dql   = "SELECT n FROM BrasaTurnoBundle:TurNovedad n WHERE n.codigoNovedadPk <> 0";
        if($codigo != "") {
            $dql .= " AND n.codigoNovedadPk = " . $codigo;
        }        
        if($codigoNovedad != "") {
            $dql .= " AND n.codigoNovedadTipoFk = " . $codigoNovedad;
        }        
        if($codigoRecurso != "") {
            $dql .= " AND n.codigoRecursoFk = " . $codigoRecurso;
        }         
        if($boolEstadoAplicado == "0") {
            $dql .= " AND n.estadoAplicada = 0";
        } 
        if($boolEstadoAplicado == 1 ) {
            $dql .= " AND n.estadoAplicada = 1";
        }        
        $dql .= " ORDER BY n.codigoNovedadPk DESC";
        return $dql;
    }

    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedad')->find($codigo);                
                $em->remove($arNovedad);                    
            }
            $em->flush();
        }
    }

    public function aplicar($codigoNovedad, $boorReemplazo = 1, $boolCambioTipo = 0) {
        $em = $this->getEntityManager();
        $arNovedad = new \Brasa\TurnoBundle\Entity\TurNovedad();
        $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedad')->find($codigoNovedad);                
        $strAnio = $arNovedad->getFechaDesde()->format('Y');
        $strMes = $arNovedad->getFechaDesde()->format('m');
        $strMesHasta = $arNovedad->getFechaHasta()->format('m');
        $strDiaDesde = $arNovedad->getFechaDesde()->format('j');
        $strDiaHasta = $arNovedad->getFechaHasta()->format('j');
        if($strMes != $strMesHasta) {
            $strDiaHasta = $strUltimoDiaMes = date("d",(mktime(0,0,0,$arNovedad->getFechaDesde()->format('m')+1,1,$arNovedad->getFechaDesde()->format('Y'))-1));
        }
        $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('anio' => $strAnio, 'mes' => $strMes, 'codigoRecursoFk' => $arNovedad->getCodigoRecursoFk()), array('horas' => 'DESC'));
        $diaControl = 1;
        foreach ($arProgramacionDetalles as $arProgramacionDetalle) {
            $arProgramacionDetalleAct = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
            $arProgramacionDetalleAct = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());
            //Actualizar o crear programacion para el recurso reemplazo 
            if($diaControl == 1) {
                if($boorReemplazo = 1) {
                if($arNovedad->getCodigoRecursoReemplazoFk() != '') {
                    $arProgramacionDetalleReemplazo = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                    $arProgramacionDetalleReemplazo = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findOneBy(array('codigoProgramacionFk' => $arProgramacionDetalleAct->getCodigoProgramacionFk(), 'codigoRecursoFk' => $arNovedad->getCodigoRecursoReemplazoFk()));
                    if($arProgramacionDetalleReemplazo) {
                        $arProgramacionDetalleReemplazoAct = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                        $arProgramacionDetalleReemplazoAct = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalleReemplazo->getCodigoProgramacionDetallePk());
                    } else {
                        $arProgramacionDetalleReemplazoAct = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();                    
                        $arProgramacionDetalleReemplazoAct->setProgramacionRel($arProgramacionDetalle->getProgramacionRel());
                        $arProgramacionDetalleReemplazoAct->setAnio($arProgramacionDetalle->getAnio());
                        $arProgramacionDetalleReemplazoAct->setMes($arProgramacionDetalle->getMes());
                        $arProgramacionDetalleReemplazoAct->setRecursoRel($arNovedad->getRecursoReemplazoRel());
                        $arProgramacionDetalleReemplazoAct->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());
                        $arProgramacionDetalleReemplazoAct->setPuestoRel($arProgramacionDetalle->getPuestoRel());                    
                    }
                    for($i = $strDiaDesde; $i <= $strDiaHasta; $i++) {
                        if($i == 1) {                        
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia1());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia1($arProgramacionDetalleAct->getDia1());    
                            }                        
                        }
                        if($i == 2) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia2());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia2($arProgramacionDetalleAct->getDia2());    
                            }
                        }
                        if($i == 3) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia3());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia3($arProgramacionDetalleAct->getDia3());    
                            }
                        }
                        if($i == 4) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia4());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia4($arProgramacionDetalleAct->getDia4());    
                            }
                        }
                        if($i == 5) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia5());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia5($arProgramacionDetalleAct->getDia5());    
                            }
                        }
                        if($i == 6) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia6());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia6($arProgramacionDetalleAct->getDia6());    
                            }
                        }
                        if($i == 7) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia7());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia7($arProgramacionDetalleAct->getDia7());    
                            }
                        }
                        if($i == 8) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia8());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia8($arProgramacionDetalleAct->getDia8());    
                            }
                        }
                        if($i == 9) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia9());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia9($arProgramacionDetalleAct->getDia9());    
                            }
                        }
                        if($i == 10) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia10());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia10($arProgramacionDetalleAct->getDia10());    
                            }
                        }
                        if($i == 11) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia11());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia11($arProgramacionDetalleAct->getDia11());    
                            }
                        }
                        if($i == 12) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia12());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia12($arProgramacionDetalleAct->getDia12());    
                            }
                        }
                        if($i == 13) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia13());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia13($arProgramacionDetalleAct->getDia13());    
                            }
                        }
                        if($i == 14) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia14());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia14($arProgramacionDetalleAct->getDia14());    
                            }
                        }
                        if($i == 15) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia15());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia15($arProgramacionDetalleAct->getDia15());    
                            }
                        }
                        if($i == 16) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia16());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia16($arProgramacionDetalleAct->getDia16());    
                            }
                        }
                        if($i == 17) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia17());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia17($arProgramacionDetalleAct->getDia17());    
                            }
                        }
                        if($i == 18) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia18());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia18($arProgramacionDetalleAct->getDia18());    
                            }
                        }
                        if($i == 19) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia19());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia19($arProgramacionDetalleAct->getDia19());    
                            }
                        }
                        if($i == 20) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia20());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia20($arProgramacionDetalleAct->getDia20());    
                            }
                        }
                        if($i == 21) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia21());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia21($arProgramacionDetalleAct->getDia21());    
                            }
                        }
                        if($i == 22) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia22());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia22($arProgramacionDetalleAct->getDia22());    
                            }
                        }
                        if($i == 23) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia23());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia23($arProgramacionDetalleAct->getDia23());    
                            }
                        }
                        if($i == 24) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia24());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia24($arProgramacionDetalleAct->getDia24());    
                            }
                        }
                        if($i == 25) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia25());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia25($arProgramacionDetalleAct->getDia25());    
                            }
                        }
                        if($i == 26) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia26());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia26($arProgramacionDetalleAct->getDia26());    
                            }
                        }
                        if($i == 27) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia27());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia27($arProgramacionDetalleAct->getDia27());    
                            }
                        }
                        if($i == 28) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia28());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia28($arProgramacionDetalleAct->getDia28());    
                            }
                        }
                        if($i == 29) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia29());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia29($arProgramacionDetalleAct->getDia29());    
                            }
                        }
                        if($i == 30) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia30());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia30($arProgramacionDetalleAct->getDia30());    
                            }
                        }
                        if($i == 31) {
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalleAct->getDia31());
                            if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                $arProgramacionDetalleReemplazoAct->setDia31($arProgramacionDetalleAct->getDia31());    
                            }
                        }                       
                    }
                    $em->persist($arProgramacionDetalleReemplazoAct);                         
                }                    
            } 
            
                //Actualizar recurso original
                for($i = $strDiaDesde; $i <= $strDiaHasta; $i++) {
                if($i == 1) {
                    $arProgramacionDetalleAct->setDia1($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 2) {
                    $arProgramacionDetalleAct->setDia2($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 3) {
                    $arProgramacionDetalleAct->setDia3($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 4) {
                    $arProgramacionDetalleAct->setDia4($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 5) {
                    $arProgramacionDetalleAct->setDia5($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 6) {
                    $arProgramacionDetalleAct->setDia6($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 7) {
                    $arProgramacionDetalleAct->setDia7($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 8) {
                    $arProgramacionDetalleAct->setDia8($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 9) {
                    $arProgramacionDetalleAct->setDia9($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 10) {
                    $arProgramacionDetalleAct->setDia10($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 11) {
                    $arProgramacionDetalleAct->setDia11($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 12) {
                    $arProgramacionDetalleAct->setDia12($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 13) {
                    $arProgramacionDetalleAct->setDia13($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 14) {
                    $arProgramacionDetalleAct->setDia14($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 15) {
                    $arProgramacionDetalleAct->setDia15($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 16) {
                    $arProgramacionDetalleAct->setDia16($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 17) {
                    $arProgramacionDetalleAct->setDia17($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 18) {
                    $arProgramacionDetalleAct->setDia18($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 19) {
                    $arProgramacionDetalleAct->setDia19($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 20) {
                    $arProgramacionDetalleAct->setDia20($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 21) {
                    $arProgramacionDetalleAct->setDia21($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 22) {
                    $arProgramacionDetalleAct->setDia22($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 23) {
                    $arProgramacionDetalleAct->setDia23($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 24) {
                    $arProgramacionDetalleAct->setDia24($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 25) {
                    $arProgramacionDetalleAct->setDia25($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 26) {
                    $arProgramacionDetalleAct->setDia26($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 27) {
                    $arProgramacionDetalleAct->setDia27($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 28) {
                    $arProgramacionDetalleAct->setDia28($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 29) {
                    $arProgramacionDetalleAct->setDia29($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 30) {
                    $arProgramacionDetalleAct->setDia30($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }
                if($i == 31) {
                    $arProgramacionDetalleAct->setDia31($arNovedad->getNovedadTipoRel()->getCodigoTurnoFk());
                }                       
            }            
            } else {
                //Actualizar recurso original
                for($i = $strDiaDesde; $i <= $strDiaHasta; $i++) {
                    if($i == 1) {
                        $arProgramacionDetalleAct->setDia1(null);
                    }
                    if($i == 2) {
                        $arProgramacionDetalleAct->setDia2(null);
                    }
                    if($i == 3) {
                        $arProgramacionDetalleAct->setDia3(null);
                    }
                    if($i == 4) {
                        $arProgramacionDetalleAct->setDia4(null);
                    }
                    if($i == 5) {
                        $arProgramacionDetalleAct->setDia5(null);
                    }
                    if($i == 6) {
                        $arProgramacionDetalleAct->setDia6(null);
                    }
                    if($i == 7) {
                        $arProgramacionDetalleAct->setDia7(null);
                    }
                    if($i == 8) {
                        $arProgramacionDetalleAct->setDia8(null);
                    }
                    if($i == 9) {
                        $arProgramacionDetalleAct->setDia9(null);
                    }
                    if($i == 10) {
                        $arProgramacionDetalleAct->setDia10(null);
                    }
                    if($i == 11) {
                        $arProgramacionDetalleAct->setDia11(null);
                    }
                    if($i == 12) {
                        $arProgramacionDetalleAct->setDia12(null);
                    }
                    if($i == 13) {
                        $arProgramacionDetalleAct->setDia13(null);
                    }
                    if($i == 14) {
                        $arProgramacionDetalleAct->setDia14(null);
                    }
                    if($i == 15) {
                        $arProgramacionDetalleAct->setDia15(null);
                    }
                    if($i == 16) {
                        $arProgramacionDetalleAct->setDia16(null);
                    }
                    if($i == 17) {
                        $arProgramacionDetalleAct->setDia17(null);
                    }
                    if($i == 18) {
                        $arProgramacionDetalleAct->setDia18(null);
                    }
                    if($i == 19) {
                        $arProgramacionDetalleAct->setDia19(null);
                    }
                    if($i == 20) {
                        $arProgramacionDetalleAct->setDia20(null);
                    }
                    if($i == 21) {
                        $arProgramacionDetalleAct->setDia21(null);
                    }
                    if($i == 22) {
                        $arProgramacionDetalleAct->setDia22(null);
                    }
                    if($i == 23) {
                        $arProgramacionDetalleAct->setDia23(null);
                    }
                    if($i == 24) {
                        $arProgramacionDetalleAct->setDia24(null);
                    }
                    if($i == 25) {
                        $arProgramacionDetalleAct->setDia25(null);
                    }
                    if($i == 26) {
                        $arProgramacionDetalleAct->setDia26(null);
                    }
                    if($i == 27) {
                        $arProgramacionDetalleAct->setDia27(null);
                    }
                    if($i == 28) {
                        $arProgramacionDetalleAct->setDia28(null);
                    }
                    if($i == 29) {
                        $arProgramacionDetalleAct->setDia29(null);
                    }
                    if($i == 30) {
                        $arProgramacionDetalleAct->setDia30(null);
                    }
                    if($i == 31) {
                        $arProgramacionDetalleAct->setDia31(null);
                    }                       
                }                
            }
                                
            $em->persist($arProgramacionDetalleAct);               
            $em->flush();
            $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($arProgramacionDetalleAct->getProgramacionRel()->getCodigoProgramacionPk());                        
            $diaControl++;
        }
               
        
        $arNovedad->setEstadoAplicada(1);
        $em->persist($arNovedad);
        $em->flush();        
    }
}