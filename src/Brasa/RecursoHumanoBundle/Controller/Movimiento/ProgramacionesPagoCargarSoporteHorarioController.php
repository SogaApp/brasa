<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;



class ProgramacionesPagoCargarSoporteHorarioController extends Controller
{
    /**
     * @Route("/rhu/programaciones/pago/cargar/soporte/horario/{codigoProgramacionPago}", name="brs_rhu_programaciones_pago_cargar_soporte_horario")
     */
    public function cargarAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();                
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $form = $this->createFormBuilder()                        
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($request->request->get('OpCargar')) {
                $codigoSoportePagoHorario = $request->request->get('OpCargar');
                $arSoportePagoHorario = new \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorario();
                $arSoportePagoHorario = $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorario')->find($codigoSoportePagoHorario);
                $arSoportePagoHorarioDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle();
                $arSoportePagoHorarioDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorarioDetalle')->findBy(array('codigoSoportePagoHorarioFk' => $codigoSoportePagoHorario));                                                                
                foreach ($arSoportePagoHorarioDetalles as $arSoportePagoHorarioDetalle) {                    
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $arSoportePagoHorarioDetalle->getEmpleadoRel();
                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContrato = $arSoportePagoHorarioDetalle->getContratoRel();
                    $floVrDia = $arContrato->getVrSalario() / 30;
                    $floVrHora = $floVrDia / 8;
                    $intHoras = $arSoportePagoHorarioDetalle->getHoras();
                    
                    $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                    $arProgramacionPagoDetalle->setEmpleadoRel($arEmpleado);
                    $arProgramacionPagoDetalle->setProgramacionPagoRel($arProgramacionPago);
                    $arProgramacionPagoDetalle->setContratoRel($arContrato);
                    $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalario());
                    $arProgramacionPagoDetalle->setFechaDesde($arSoportePagoHorarioDetalle->getFechaDesde());
                    $arProgramacionPagoDetalle->setFechaHasta($arSoportePagoHorarioDetalle->getFechaHasta());
                    $arProgramacionPagoDetalle->setFechaDesdePago($arSoportePagoHorarioDetalle->getFechaDesde());
                    $arProgramacionPagoDetalle->setFechaHastaPago($arSoportePagoHorarioDetalle->getFechaHasta());
                    $arProgramacionPagoDetalle->setHorasPeriodo($intHoras);
                    $arProgramacionPagoDetalle->setHorasPeriodoReales($intHoras);
                    $arProgramacionPagoDetalle->setDias($arSoportePagoHorarioDetalle->getDias());
                    $arProgramacionPagoDetalle->setDiasReales($arSoportePagoHorarioDetalle->getDias());    
                    $arProgramacionPagoDetalle->setFactorDia($arContrato->getFactorHorasDia());
                    $arProgramacionPagoDetalle->setVrDia($floVrDia);
                    $arProgramacionPagoDetalle->setVrHora($floVrHora);
                    $em->persist($arProgramacionPagoDetalle);

                    if($arSoportePagoHorarioDetalle->getHorasNocturnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 48, $arSoportePagoHorarioDetalle->getHorasNocturnas(), $arEmpleado);
                    }
                    if($arSoportePagoHorarioDetalle->getHorasFestivasDiurnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 51, $arSoportePagoHorarioDetalle->getHorasFestivasDiurnas(), $arEmpleado);
                    }
                    if($arSoportePagoHorarioDetalle->getHorasFestivasNocturnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 52, $arSoportePagoHorarioDetalle->getHorasFestivasNocturnas(), $arEmpleado);
                    }                    
                    if($arSoportePagoHorarioDetalle->getHorasExtrasOrdinariasDiurnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 44, $arSoportePagoHorarioDetalle->getHorasExtrasOrdinariasDiurnas(), $arEmpleado);
                    }
                    if($arSoportePagoHorarioDetalle->getHorasExtrasOrdinariasNocturnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 45, $arSoportePagoHorarioDetalle->getHorasExtrasOrdinariasNocturnas(), $arEmpleado);
                    }                    
                    if($arSoportePagoHorarioDetalle->getHorasExtrasFestivasDiurnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 43, $arSoportePagoHorarioDetalle->getHorasExtrasFestivasDiurnas(), $arEmpleado);
                    }
                    if($arSoportePagoHorarioDetalle->getHorasExtrasFestivasNocturnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 45, $arSoportePagoHorarioDetalle->getHorasExtrasFestivasNocturnas(), $arEmpleado);
                    }                    
                }
                
                $arProgramacionPago->setEmpleadosGenerados(1);
                $arProgramacionPago->setNumeroEmpleados(count($arSoportePagoHorarioDetalles));                
                $em->persist($arProgramacionPago);                
                $em->flush();                
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }                                  
        }   
        $arSoportePagoHorario = new \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorario();
        $arSoportePagoHorario = $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorario')->findAll();
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ProgramacionesPago:cargarSoporteHorario.html.twig', array(
            'arSoportePagoHorario' => $arSoportePagoHorario, 
            'form' => $form->createView()            
            ));
    }
    
    private function insertarAdicionalPago($arProgramacionPago, $codigoPagoConcepto, $cantidad, $arEmpleado) {
        $em = $this->getDoctrine()->getManager();
        $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($codigoPagoConcepto);                
        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
        $arPagoAdicional->setCantidad($cantidad);
        $arPagoAdicional->setEmpleadoRel($arEmpleado);
        $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
        $arPagoAdicional->setTipoAdicional($arPagoConcepto->getTipoAdicional());
        $em->persist($arPagoAdicional);
        return false;
    }
    
}
