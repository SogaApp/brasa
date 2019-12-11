<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;



class ProgramacionesPagoCargarSoporteTurnoController extends Controller
{
    /**
     * @Route("/rhu/programaciones/pago/cargar/soporte/turno/{codigoProgramacionPago}", name="brs_rhu_programaciones_pago_cargar_soporte_turno")
     */
    public function cargarAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->eliminarEmpleados($codigoProgramacionPago);
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();                
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $form = $this->createFormBuilder()                        
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($request->request->get('OpCargar')) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $codigoSoportePagoPeriodo = $request->request->get('OpCargar');  
                $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();                       
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                if($arSoportePagoPeriodo->getEstadoAprobadoPagoNomina() == 1) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoInconsistencia')->eliminarProgramacionPago($codigoProgramacionPago);
                    $arrInconsistencias = array();                
                    $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();                       
                    $arSoportesPago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo));
                    foreach ($arSoportesPago as $arSoportePago) {                    
                        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        $arEmpleado = $arSoportePago->getRecursoRel()->getEmpleadoRel();
                        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arSoportePago->getCodigoContratoFk());
                        $floVrDia = $arContrato->getVrSalario() / 30;
                        $floVrHora = $floVrDia / 8;                    
                        $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                        $arProgramacionPagoDetalle->setEmpleadoRel($arEmpleado);
                        $arProgramacionPagoDetalle->setProgramacionPagoRel($arProgramacionPago);
                        $arProgramacionPagoDetalle->setContratoRel($arContrato);
                        $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalario());
                        $arProgramacionPagoDetalle->setSoporteTurno(TRUE);
                        $arProgramacionPagoDetalle->setCodigoSoportePagoFk($arSoportePago->getCodigoSoportePagoPk());
                        $arProgramacionPagoDetalle->setFechaDesde($arSoportePago->getFechaDesde());
                        $arProgramacionPagoDetalle->setFechaHasta($arSoportePago->getFechaHasta());
                        if($arContrato->getFechaDesde() < $arProgramacionPago->getFechaDesde()) {
                            $arProgramacionPagoDetalle->setFechaDesdePago($arSoportePago->getFechaDesde());    
                        } else {
                            $arProgramacionPagoDetalle->setFechaDesdePago($arContrato->getFechaDesde());
                        }                    
                        $arProgramacionPagoDetalle->setFechaHastaPago($arSoportePago->getFechaHasta());                    
                        $intDias = $arSoportePago->getDias();
                        $intDiasTransporte = $arSoportePago->getDiasTransporte();
                        $arProgramacionPagoDetalle->setDias($intDias);
                        $arProgramacionPagoDetalle->setDiasReales($intDias); 
                        $arProgramacionPagoDetalle->setDiasTransporte($intDiasTransporte); 
                        $arProgramacionPagoDetalle->setFactorDia($arContrato->getFactorHorasDia());
                        $arProgramacionPagoDetalle->setVrDia($floVrDia);
                        $arProgramacionPagoDetalle->setVrHora($floVrHora);
                        //Tiempo adicional
                        $horasNovedad = $arSoportePago->getNovedad() * 8;
                        $intHoras = $arSoportePago->getHorasDescanso() + $arSoportePago->getHorasDiurnas() + $arSoportePago->getHorasNocturnas() + $arSoportePago->getHorasFestivasDiurnas() + $arSoportePago->getHorasFestivasNocturnas();
                        $intHorasReales = $intHoras + $horasNovedad;
                        $arProgramacionPagoDetalle->setHorasPeriodo($intHoras);
                        $arProgramacionPagoDetalle->setHorasPeriodoReales($intHorasReales);                    
                        $arProgramacionPagoDetalle->setHorasNovedad($horasNovedad);
                        $arProgramacionPagoDetalle->setHorasDescanso($arSoportePago->getHorasDescanso());
                        $arProgramacionPagoDetalle->setHorasDiurnas($arSoportePago->getHorasDiurnas());
                        $arProgramacionPagoDetalle->setHorasNocturnas($arSoportePago->getHorasNocturnas());
                        $arProgramacionPagoDetalle->setHorasFestivasDiurnas($arSoportePago->getHorasFestivasDiurnas());
                        $arProgramacionPagoDetalle->setHorasFestivasNocturnas($arSoportePago->getHorasFestivasNocturnas());
                        $arProgramacionPagoDetalle->setHorasExtrasOrdinariasDiurnas($arSoportePago->getHorasExtrasOrdinariasDiurnas());
                        $arProgramacionPagoDetalle->setHorasExtrasOrdinariasNocturnas($arSoportePago->getHorasExtrasOrdinariasNocturnas());
                        $arProgramacionPagoDetalle->setHorasExtrasFestivasDiurnas($arSoportePago->getHorasExtrasFestivasDiurnas());
                        $arProgramacionPagoDetalle->setHorasExtrasFestivasNocturnas($arSoportePago->getHorasExtrasFestivasNocturnas());                    
                        $arProgramacionPagoDetalle->setHorasRecargoNocturno($arSoportePago->getHorasRecargoNocturno());                                        
                        $arProgramacionPagoDetalle->setHorasRecargoFestivoDiurno($arSoportePago->getHorasRecargoFestivoDiurno());
                        $arProgramacionPagoDetalle->setHorasRecargoFestivoNocturno($arSoportePago->getHorasRecargoFestivoNocturno());
                        //Pregunta por el tipo de pension, si es pensionado no le retiene pension (PABLO ARANZAZU 27/04/2016)
                        if ($arContrato->getCodigoTipoPensionFk() == 5){
                            $arProgramacionPagoDetalle->setDescuentoPension(0);
                        }   
                        //dias vacaciones
                        $arrVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->dias($arContrato->getCodigoEmpleadoFk(), $arContrato->getCodigoContratoPk(), $arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHastaReal());                
                        $intDiasVacaciones = $arrVacaciones['dias'];                                             
                        if($intDiasVacaciones > 0) {                                        
                            $arProgramacionPagoDetalle->setDiasVacaciones($intDiasVacaciones);
                            $arProgramacionPagoDetalle->setIbcVacaciones($arrVacaciones['ibc']);
                        }           

                        //dias licencia                    
                        $intDiasLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->diasLicenciaPeriodo31($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHastaReal(), $arContrato->getCodigoEmpleadoFk());                
                        if($intDiasLicencia > 0) {                                        
                            $arProgramacionPagoDetalle->setDiasLicencia($intDiasLicencia);
                        }     

                        //dias incapacidad
                        $intDiasIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidadPeriodo31($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHastaReal(), $arContrato->getCodigoEmpleadoFk());                
                        if($intDiasIncapacidad > 0) {                                        
                            $arProgramacionPagoDetalle->setDiasIncapacidad($intDiasIncapacidad);
                        }                    

                        if($intDiasVacaciones != $arSoportePago->getVacacion()) {
                            $arrInconsistencias[] = array('inconsistencia' => "El empleado " . $arEmpleado->getNumeroIdentificacion() . "-" . $arEmpleado->getNombreCorto() . " tiene vacaciones de " . $arSoportePago->getVacacion() . " dias en turnos y de " . $intDiasVacaciones . " en recurso humano");
                        }
                        $intDiasLicenciaSoportePago = $arSoportePago->getLicencia()+$arSoportePago->getLicenciaNoRemunerada();
                        if($intDiasLicencia != $intDiasLicenciaSoportePago) {
                            $arrInconsistencias[] = array('inconsistencia' => "El empleado " . $arEmpleado->getNumeroIdentificacion() . "-" . $arEmpleado->getNombreCorto() . " tiene licencias de " . $intDiasLicenciaSoportePago . " dias en turnos y de " . $intDiasLicencia . " en recurso humano");
                        }

                        if($intDiasIncapacidad != $arSoportePago->getIncapacidad()) {
                            $arrInconsistencias[] = array('inconsistencia' => "El empleado " . $arEmpleado->getNumeroIdentificacion() . "-" . $arEmpleado->getNombreCorto() . " tiene incapacidades de " . $arSoportePago->getIncapacidad() . " dias en turnos y de " . $intDiasIncapacidad . " en recurso humano");
                        }         
                        if($arSoportePagoPeriodo->getAjusteDevengado()) {
                            if($arSoportePago->getVrAjusteDevengadoPactado() > 0) {
                                $arProgramacionPagoDetalle->setVrAjusteDevengado($arSoportePago->getVrAjusteDevengadoPactado());
                            }
                        }
                        if($arSoportePago->getVrAjusteCompensacion() > 0) {
                            $arProgramacionPagoDetalle->setVrAjusteDevengado($arSoportePago->getVrAjusteCompensacion());
                        }

                        $em->persist($arProgramacionPagoDetalle);                
                    }                
                    $arProgramacionPago->setInconsistencias(0);
                    if(count($arrInconsistencias) > 0) {
                        $arProgramacionPago->setInconsistencias(1);
                        foreach ($arrInconsistencias as $arrInconsistencia) {
                            $arProgramacionPagoInconsistencia = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoInconsistencia();
                            $arProgramacionPagoInconsistencia->setProgramacionPagoRel($arProgramacionPago);
                            $arProgramacionPagoInconsistencia->setInconsistencia($arrInconsistencia['inconsistencia']);
                            $em->persist($arProgramacionPagoInconsistencia);                        
                        }
                    }
                    $arProgramacionPago->setEmpleadosGenerados(1);
                    $arProgramacionPago->setNumeroEmpleados(count($arSoportesPago)); 
                    $arProgramacionPago->setCodigoSoportePagoPeriodoFk($codigoSoportePagoPeriodo);
                    $em->persist($arProgramacionPago);
                    $arSoportePagoPeriodo->setEstadoBloqueoNomina(1);
                    $em->flush();                                 
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                                    
            }                                                                             
        }  
        
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
        $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->findBy(array('estadoGenerado' => 1, 'estadoCerrado' => 0));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ProgramacionesPago:cargarSoporteTurno.html.twig', array(
            'arSoportePagoPeriodos' => $arSoportePagoPeriodo,
            'form' => $form->createView()
            ));
    }    
    
}
