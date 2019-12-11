<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPlantillaRepository extends EntityRepository {
    

    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPlantilla p WHERE p.codigoPlantillaPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND p.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND p.codigoPlantillaPk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY p.nombre";
        return $dql;
    }            
    
    public function liquidar($codigoProgramacion) {        
        $em = $this->getEntityManager();        
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();        
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion); 
        $douTotalHoras = 0;
        $arProgramacionesDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
        $arProgramacionesDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoProgramacionFk' => $codigoProgramacion));         
        foreach ($arProgramacionesDetalle as $arProgramacionDetalle) {
            $douHorasDetalle = 0;
            $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
            $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                                     
            if($arProgramacionDetalle->getDia1() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia1()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());                
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia2() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia2()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia3() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia3()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia4() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia4()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());                
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia5() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia5()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia6() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia6()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia7() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia7()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia8() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia8()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }            
            if($arProgramacionDetalle->getDia9() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia9()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia10() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia10()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia11() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia11()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia12() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia12()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia13() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia13()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia14() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia14()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia15() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia15()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia16() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia16()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia17() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia17()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia18() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia18()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia19() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia19()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia20() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia20()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia21() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia21()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia22() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia22()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia23() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia23()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia24() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia24()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia25() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia25()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia26() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia26()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia27() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia27()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia28() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia28()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia29() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia29()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia30() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia30()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia31() != '') {
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia31()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $douHorasDetalle += $arTurno->getHoras();
            }      
            $arProgramacionesDetalleActualizar->setHoras($douHorasDetalle);
            $em->persist($arProgramacionesDetalleActualizar);            
            $douTotalHoras += $douHorasDetalle;
        }
        $arProgramacion->setHoras($douTotalHoras);
        $em->persist($arProgramacion);
        $em->flush();
        return true;
    }  
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurPlantilla')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }    
}