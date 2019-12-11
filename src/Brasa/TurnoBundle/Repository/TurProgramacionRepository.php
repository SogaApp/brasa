<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurProgramacionRepository extends EntityRepository {
    
    public function listaDql($codigoProgramacion = "", $codigoCliente = "", $boolEstadoAutorizado = "", $strFechaDesde = "", $strFechaHasta = "", $boolEstadoAnulado = "") {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurProgramacion p WHERE p.codigoProgramacionPk <> 0";
        if($codigoProgramacion != "") {
            $dql .= " AND p.codigoProgramacionPk = " . $codigoProgramacion;  
        }        
        if($codigoCliente != "") {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;  
        }    
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND p.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND p.estadoAutorizado = 0";
        } 
        if($boolEstadoAnulado == 1 ) {
            $dql .= " AND p.estadoAnulado = 1";
        }
        if($boolEstadoAnulado == "0") {
            $dql .= " AND p.estadoAnulado = 0";
        }        
        if($strFechaDesde != "") {
            $dql .= " AND p.fecha >= '" . $strFechaDesde . " 00:00:00'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND p.fecha <= '" . $strFechaHasta . " 23:59:59'";
        }    
        $dql .= " ORDER BY p.fecha DESC";
        return $dql;
    }    
    
    public function liquidar($codigoProgramacion) {        
        $em = $this->getEntityManager();        
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();        
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion); 
        $douTotalHoras = 0;        
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;        
        $arProgramacionesDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
        $arProgramacionesDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoProgramacionFk' => $codigoProgramacion));         
        foreach ($arProgramacionesDetalle as $arProgramacionDetalle) {
            $douTotalHorasDiurnas += $arProgramacionDetalle->getHorasDiurnas();
            $douTotalHorasNocturnas += $arProgramacionDetalle->getHorasNocturnas();
            $douTotalHoras += $arProgramacionDetalle->getHoras();

            /*$arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
            $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                                     
            if($arProgramacionDetalle->getDia1() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia1()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());                
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();
            }
            if($arProgramacionDetalle->getDia2() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia2()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia3() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia3()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia4() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia4()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());                
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia5() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia5()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia6() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia6()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia7() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia7()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia8() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia8()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }            
            if($arProgramacionDetalle->getDia9() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia9()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia10() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia10()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia11() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia11()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia12() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia12()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia13() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia13()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia14() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia14()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia15() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia15()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia16() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia16()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia17() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia17()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia18() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia18()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia19() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia19()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia20() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia20()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia21() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia21()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia22() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia22()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia23() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia23()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia24() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia24()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia25() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia25()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia26() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia26()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();
            }
            if($arProgramacionDetalle->getDia27() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia27()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia28() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia28()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia29() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia29()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia30() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia30()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }
            if($arProgramacionDetalle->getDia31() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia31()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
                $douHorasDiurnas += $arTurno->getHorasDiurnas();
                $douHorasNocturnas += $arTurno->getHorasNocturnas();                
            }                  
            $douTotalHoras += $douHorasDetalle;
            $douTotalHorasDiurnas += $douHorasDiurnas;
            $douTotalHorasNocturnas += $douHorasNocturnas;
            $arProgramacionesDetalleActualizar->setHoras($douHorasDetalle);
            $arProgramacionesDetalleActualizar->setHorasDiurnas($douHorasDiurnas);
            $arProgramacionesDetalleActualizar->setHorasNocturnas($douHorasNocturnas);
            $em->persist($arProgramacionesDetalleActualizar);  
            */
        }
        $arProgramacion->setHoras($douTotalHoras);
        $arProgramacion->setHorasDiurnas($douTotalHorasDiurnas);
        $arProgramacion->setHorasNocturnas($douTotalHorasNocturnas);
        $em->persist($arProgramacion);
        $em->flush();
        return true;
    }        
    
    public function eliminar($arrSeleccionados) {        
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $booEliminar = TRUE;
                $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoProgramacionFk' => $codigo));
                foreach ($arProgramacionDetalles as $arProgramacionDetalle) {
                    $intNumeroRegistros = 0;
                    $dql   = "SELECT COUNT(spd.codigoSoportePagoDetallePk) as numeroRegistros FROM BrasaTurnoBundle:TurSoportePagoDetalle spd "
                            . "WHERE spd.codigoProgramacionDetalleFk = " . $arProgramacionDetalle->getCodigoProgramacionDetallePk();
                    $query = $em->createQuery($dql);
                    $arrCotizacionDetalles = $query->getSingleResult(); 
                    if($arrCotizacionDetalles['numeroRegistros'] > 0) {
                       $booEliminar = FALSE;
                    }                    
                }
                if($booEliminar) {
                    $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigo);
                    $em->remove($arProgramacion);                    
                }
            }
            $em->flush();
        }
    }      
    
    public function validarAutorizar($codigoProgramacion) {
        $em = $this->getEntityManager(); 
        $strResultados = "";
        if($em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->numeroRegistros($codigoProgramacion) > 0) {        
            if($em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->validarRecurso($codigoProgramacion)) {        
                if($em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->validarPuesto($codigoProgramacion)) {        

                } else {
                    $strResultados = "Existen detalles sin puesto, no se puede autorizar, verifique la programacion";
                }                
            } else {
                $strResultados = "Hay detalles sin recursos asignados";
            }
        } else {
            $strResultados = "La programacion no tiene registros";
        }
        return $strResultados;
    }
    
    public function anular($codigoProgramacion) {
        $em = $this->getEntityManager();                
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);            
        $strResultado = "";        
        if($arProgramacion->getEstadoAutorizado() == 1 && $arProgramacion->getEstadoAnulado() == 0) {
            $arProgramacion->setEstadoAnulado(1);
            $em->persist($arProgramacion);
            $em->flush();      
                           
        } else {
            $strResultado = "La programacion debe estar autorizada y no puede estar previamente anulada";
        }        
        return $strResultado;
    }     
    
    public function autorizar($codigoProgramacion) {
        $em = $this->getEntityManager();                
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);            
        $strResultado = "";        
        /*$arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
        $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoProgramacionFk' => $codigoProgramacion));            
        foreach ($arProgramacionDetalles as $arProgramacionDetalle) {
            if($arProgramacionDetalle->getCodigoPedidoDetalleFk()) {
                $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arProgramacionDetalle->getCodigoPedidoDetalleFk());
                $horasProgramadas = $arPedidoDetalle->getHorasProgramadas() + $arProgramacionDetalle->getHoras();
                $horasDiurnasProgramadas = $arPedidoDetalle->getHorasDiurnasProgramadas() + $arProgramacionDetalle->getHorasDiurnas();
                $horasNocturnasProgramadas = $arPedidoDetalle->getHorasNocturnasProgramadas() + $arProgramacionDetalle->getHorasNocturnas();                
                $arPedidoDetalle->setHorasProgramadas($horasProgramadas);
                $arPedidoDetalle->setHorasDiurnasProgramadas($horasDiurnasProgramadas);
                $arPedidoDetalle->setHorasNocturnasProgramadas($horasNocturnasProgramadas);
                $em->persist($arPedidoDetalle);
            }
        }*/
        $arProgramacion->setEstadoAutorizado(1);
        $em->persist($arProgramacion);
        $em->flush();                                         
        return $strResultado;
    }     
    
    public function desAutorizar($codigoProgramacion) {
        $em = $this->getEntityManager();                
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);            
        $strResultado = "";        
        /*$arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
        $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoProgramacionFk' => $codigoProgramacion));            
        foreach ($arProgramacionDetalles as $arProgramacionDetalle) {
            if($arProgramacionDetalle->getCodigoPedidoDetalleFk()) {
                $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arProgramacionDetalle->getCodigoPedidoDetalleFk());
                $horasProgramadas = $arPedidoDetalle->getHorasProgramadas() - $arProgramacionDetalle->getHoras();
                $horasDiurnasProgramadas = $arPedidoDetalle->getHorasDiurnasProgramadas() - $arProgramacionDetalle->getHorasDiurnas();
                $horasNocturnasProgramadas = $arPedidoDetalle->getHorasNocturnasProgramadas() - $arProgramacionDetalle->getHorasNocturnas();                
                $arPedidoDetalle->setHorasProgramadas($horasProgramadas);
                $arPedidoDetalle->setHorasDiurnasProgramadas($horasDiurnasProgramadas);
                $arPedidoDetalle->setHorasNocturnasProgramadas($horasNocturnasProgramadas);
                $em->persist($arPedidoDetalle);
            }
        }
         * 
         */
        $arProgramacion->setEstadoAutorizado(0);
        $em->persist($arProgramacion);
        $em->flush();                                         
        return $strResultado;
    }  
    
    public function actualizarHorasProgramadas($codigoProgramacion) {        
        $em = $this->getEntityManager();        
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();        
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion); 
        $douTotalHoras = 0;        
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;        
        $arProgramacionesDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
        $arProgramacionesDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoProgramacionFk' => $codigoProgramacion));         
        foreach ($arProgramacionesDetalle as $arProgramacionDetalle) {
            $douHorasDetalle = 0;
            $douHorasDiurnas = 0;
            $douHorasNocturnas = 0;
            $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
            $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                                     
            if($arProgramacionDetalle->getDia1() != '') {                
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia1());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }
            }
            if($arProgramacionDetalle->getDia2() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia2());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                               
            }
            if($arProgramacionDetalle->getDia3() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia3());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }               
            }
            if($arProgramacionDetalle->getDia4() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia4());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia5() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia5());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                 
            }
            if($arProgramacionDetalle->getDia6() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia6());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }              
            }
            if($arProgramacionDetalle->getDia7() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia7());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia8() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia8());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }               
            }            
            if($arProgramacionDetalle->getDia9() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia9());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }               
            }
            if($arProgramacionDetalle->getDia10() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia10());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }               
            }
            if($arProgramacionDetalle->getDia11() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia11());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia12() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia12());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia13() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia13());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia14() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia14());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia15() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia15());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia16() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia16());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }               
            }
            if($arProgramacionDetalle->getDia17() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia17());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia18() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia18()); 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia19() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia19()); 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia20() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia20()); 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia21() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia21()); 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia22() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia22());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia23() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia23()); 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                 
            }
            if($arProgramacionDetalle->getDia24() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia24());                 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                 
            }
            if($arProgramacionDetalle->getDia25() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia25()); 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                 
            }
            if($arProgramacionDetalle->getDia26() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia26()); 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                } 
            }
            if($arProgramacionDetalle->getDia27() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia27()); 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia28() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia28()); 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia29() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia29()); 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                 
            }
            if($arProgramacionDetalle->getDia30() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia30()); 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }
            if($arProgramacionDetalle->getDia31() != '') {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia31()); 
                if($arTurno) {
                    $douHorasDetalle += $arTurno->getHoras();
                    $douHorasDiurnas += $arTurno->getHorasDiurnas();
                    $douHorasNocturnas += $arTurno->getHorasNocturnas();                    
                }                
            }                  
            $douTotalHoras += $douHorasDetalle;
            $douTotalHorasDiurnas += $douHorasDiurnas;
            $douTotalHorasNocturnas += $douHorasNocturnas;
            $arProgramacionesDetalleActualizar->setHoras($douHorasDetalle);
            $arProgramacionesDetalleActualizar->setHorasDiurnas($douHorasDiurnas);
            $arProgramacionesDetalleActualizar->setHorasNocturnas($douHorasNocturnas);
            $em->persist($arProgramacionesDetalleActualizar);  
            
        }
        $arProgramacion->setHoras($douTotalHoras);
        $arProgramacion->setHorasDiurnas($douTotalHorasDiurnas);
        $arProgramacion->setHorasNocturnas($douTotalHorasNocturnas);
        $em->persist($arProgramacion);        
        return true;
    }            
}