<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuHorarioPeriodoRepository extends EntityRepository {
    
    public function listaDQL($boolEstadoGenerado = "", $boolEstadoCerrado = "") {        
        $dql   = "SELECT hp FROM BrasaRecursoHumanoBundle:RhuHorarioPeriodo hp WHERE hp.codigoHorarioPeriodoPk <> 0";
        if($boolEstadoGenerado == 1 ) {
            $dql .= " AND hp.estadoGenerado = 1";
        }
        if($boolEstadoGenerado == "0") {
            $dql .= " AND hp.estadoGenerado = 0";
        } 
        if($boolEstadoCerrado == 1 ) {
            $dql .= " AND hp.estadoCerrado = 1";
        }
        if($boolEstadoCerrado == "0") {
            $dql .= " AND hp.estadoCerrado = 0";
        }         
        
        return $dql;
    }
    
    public function generar($codigoHorarioPeriodo) {
        $em = $this->getEntityManager(); 
        $strMensaje = "";
        $intDiaSemana = "";
        
        $CodigoHorarioEmpleado = "";
        $arHorario = new \Brasa\RecursoHumanoBundle\Entity\RhuHorario();
        $arTurno = new \Brasa\RecursoHumanoBundle\Entity\RhuTurno();
        $arHorarioPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioPeriodo();
        $arHorarioPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioPeriodo')->find($codigoHorarioPeriodo);        
        $arHorarioPeriodosAbiertos = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioPeriodo')->findOneBy(array('estadoGenerado' => 1, 'estadoCerrado' => 0));
        if ($arHorarioPeriodosAbiertos == null){
            $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                    . "WHERE c.codigoContratoPk <> 0 "
                    . " AND (c.fechaHasta <= '" . $arHorarioPeriodo->getFechaPeriodo()->format('Y-m-d') . "' "
                    . " OR c.indefinido = 1)";            
            $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $query = $em->createQuery($dql);
            $arContratos = $query->getResult();
            foreach ($arContratos as $arContrato) {
                $arHorarioAcceso = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
                $arHorarioAcceso->setHorarioPeriodoRel($arHorarioPeriodo);
                $arHorarioAcceso->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arHorarioAcceso->setFechaEntrada($arHorarioPeriodo->getFechaPeriodo());
                $intDiaSemana = $arHorarioPeriodo->getFechaPeriodo()->format('N');
                $CodigoHorarioEmpleado = $arContrato->getEmpleadoRel()->getCodigoHorarioFk();
                $arHorario = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorario')->find($CodigoHorarioEmpleado);
                $em->persist($arHorarioAcceso);
                if ($intDiaSemana == 1) {
                    $arTurno = $em->getRepository('BrasaRecursoHumanoBundle:RhuTurno')->find($arHorario->getLunes());
                }
                if ($intDiaSemana == 2) {
                    $arTurno = $em->getRepository('BrasaRecursoHumanoBundle:RhuTurno')->find($arHorario->getMartes());
                }
                if ($intDiaSemana == 3) {
                    $arTurno = $em->getRepository('BrasaRecursoHumanoBundle:RhuTurno')->find($arHorario->getMiercoles());
                }
                if ($intDiaSemana == 4) {
                    $arTurno = $em->getRepository('BrasaRecursoHumanoBundle:RhuTurno')->find($arHorario->getJueves());
                }
                if ($intDiaSemana == 5) {
                    $arTurno = $em->getRepository('BrasaRecursoHumanoBundle:RhuTurno')->find($arHorario->getViernes());
                }
                if ($intDiaSemana == 6) {
                    $arTurno = $em->getRepository('BrasaRecursoHumanoBundle:RhuTurno')->find($arHorario->getSabado());
                }
                if ($intDiaSemana == 7) {
                    $arTurno = $em->getRepository('BrasaRecursoHumanoBundle:RhuTurno')->find($arHorario->getDomingo());
                }       
                $arHorarioAcceso->setTurnoRel($arTurno);
                $arHorarioAcceso->setHoraEntradaTurno($arTurno->getHoraDesde());
                $arHorarioAcceso->setHoraSalidaTurno($arTurno->getHoraHasta());
                if($arTurno->getSalidaDiaSiguiente() == 1) {
                    $dateFechaSalida = date_create($arHorarioPeriodo->getFechaPeriodo()->format('Y/m/d'));
                    $dateFechaSalida = date_add($dateFechaSalida, date_interval_create_from_date_string('1 days'));        
                    $arHorarioAcceso->setFechaSalida($dateFechaSalida);
                } else {
                    $arHorarioAcceso->setFechaSalida($arHorarioPeriodo->getFechaPeriodo());
                }
                $arHorarioAcceso->setSalidaDiaSiguiente($arTurno->getSalidaDiaSiguiente());
                $em->persist($arHorarioAcceso);                
            }
            $arHorarioPeriodo->setEstadoGenerado(1);
            $em->persist($arHorarioPeriodo);
            $em->flush();
        } else {
            $strMensaje = "Hay periodos pendientes por cerrar";
        }
        
        
        return $strMensaje;    
    }   
    
    public function cerrar($codigoHorarioPeriodo) {
        $em = $this->getEntityManager(); 
        $strMensaje = "";
        $arHorarioPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioPeriodo();
        $arHorarioPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioPeriodo')->find($codigoHorarioPeriodo);
        $arHorarioPeriodo->setEstadoCerrado(1);
        $em->persist($arHorarioPeriodo);
        $em->flush();
        return $strMensaje;    
    } 
}