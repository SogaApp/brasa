<?php
namespace Brasa\TurnoBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurSoportePagoPeriodoType;
use Brasa\TurnoBundle\Form\Type\TurSoportePagoType;


class GenerarSoportePagoController extends Controller
{
    var $strListaDql = "";
    var $strListaDqlDetalle = "";
    
    /**
     * @Route("/tur/proceso/generar/soporte/pago", name="brs_tur_proceso_generar_soporte_pago")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 7)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioGenerar();
        $form->handleRequest($request); 
        $this->listaPeriodo();
        if ($form->isValid()) {
            if($request->request->get('OpGenerarProgramacion')) {
                /*set_time_limit(0);
                ini_set("memory_limit", -1);
                $codigoSoportePagoPeriodo = $request->request->get('OpGenerarProgramacion');
                $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                                
                $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
                $arSoportesPago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo));
                foreach ($arSoportesPago as $arSoportePago) {
                    $em->getRepository('BrasaTurnoBundle:TurSoportePago')->generarProgramacion($arSoportePago, $arSoportePagoPeriodo->getFechaDesde()->format('Y'), $arSoportePagoPeriodo->getFechaDesde()->format('m'));
                }                     
                $em->flush();                 
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));
                 * 
                 */
            }
            if($request->request->get('OpGenerar')) {  
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $codigoSoportePagoPeriodo = $request->request->get('OpGenerar');
                $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                $arCentroCosto = $arSoportePagoPeriodo->getCentroCostoRel();                
                $dateFechaDesde = $arSoportePagoPeriodo->getFechaDesde();
                $dateFechaHasta = $arSoportePagoPeriodo->getFechaHasta();
                $intDiaInicial = $dateFechaDesde->format('j');
                $intDiaFinal = $dateFechaHasta->format('j'); 
                $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($dateFechaDesde->format('Y-m-').'01', $dateFechaHasta->format('Y-m-').'31');                
                
                //Genera los recursos del soporte pago
                $dql   = "SELECT pd.codigoRecursoFk FROM BrasaTurnoBundle:TurProgramacionDetalle pd JOIN pd.recursoRel r "
                        . "WHERE pd.anio = " . $arSoportePagoPeriodo->getFechaDesde()->format('Y') . " AND pd.mes = " . $arSoportePagoPeriodo->getFechaDesde()->format('m') . " GROUP BY pd.codigoRecursoFk";      
                $query = $em->createQuery($dql);                
                $arRecursosResumen = $query->getResult();
                foreach($arRecursosResumen as $arRecursoResumen) {
                    $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                    $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arRecursoResumen['codigoRecursoFk']);                    
                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $arRecurso->getEmpleadoRel();
                    $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                            . "WHERE c.codigoEmpleadoFk = " . $arRecurso->getCodigoEmpleadoFk()
                            . " AND c.fechaUltimoPago < '" . $dateFechaHasta->format('Y-m-d') . "' "
                            . " AND c.fechaDesde <= '" . $dateFechaHasta->format('Y-m-d') . "' "
                            . " AND (c.fechaHasta >= '" . $dateFechaDesde->format('Y-m-d') . "' "
                            . " OR c.indefinido = 1)"; 
                    $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $query = $em->createQuery($dql);
                    $arContratos = $query->getResult();
                    $numeroContratos = count($arContratos);
                    foreach ($arContratos as $arContrato) {
                        if($arContrato->getCodigoCentroCostoFk() == $arSoportePagoPeriodo->getCodigoCentroCostoFk()) {
                            if($arContrato->getEstadoTerminado() == 0 || $arContrato->getFechaHasta() >= $arSoportePagoPeriodo->getFechaDesde()) {
                                if($arContrato->getFechaDesde() <= $arSoportePagoPeriodo->getFechaHasta()) {
                                    $arSoportePago = new \Brasa\TurnoBundle\Entity\TurSoportePago();                                                
                                    $arSoportePago->setCodigoContratoFk($arContrato->getCodigoContratoPk());
                                    $arSoportePago->setVrSalario($arContrato->getVrSalario()); 
                                    $arSoportePago->setVrDevengadoPactado($arContrato->getVrDevengadoPactado());
                                    $arSoportePago->setRecursoRel($arRecurso);
                                    $arSoportePago->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
                                    $arSoportePago->setAnio($arSoportePagoPeriodo->getAnio());
                                    $arSoportePago->setMes($arSoportePagoPeriodo->getMes());
                                    $arSoportePago->setDescansoOrdinario($arCentroCosto->getDescansoOrdinario());
                                    $arSoportePago->setSecuencia($arContrato->getSecuencia());
                                    if($numeroContratos > 1) {
                                        if($arContrato->getFechaDesde() > $arSoportePagoPeriodo->getFechaDesde()) {
                                            $arSoportePago->setFechaDesde($arContrato->getFechaDesde());
                                            $arSoportePago->setFechaHasta($arSoportePagoPeriodo->getFechaHasta());
                                        }
                                        if($arContrato->getFechaHasta() < $arSoportePagoPeriodo->getFechaHasta()) {
                                            $arSoportePago->setFechaDesde($arSoportePagoPeriodo->getFechaDesde());
                                            $arSoportePago->setFechaHasta($arContrato->getFechaHasta());                                        
                                        }
                                    } else {
                                        $arSoportePago->setFechaDesde($arSoportePagoPeriodo->getFechaDesde());
                                        $arSoportePago->setFechaHasta($arSoportePagoPeriodo->getFechaHasta());                                    
                                    }
                                    /* Se suspende mientras se analizan los datos de los turnos fijos
                                     * if($arContrato->getCodigoSalarioTipoFk() == 1) {
                                        $arSoportePago->setTurnoFijo(1);
                                    }
                                     * 
                                     */
                                    /*if($arRecurso->getCodigoTurnoFijoNominaFk()) {
                                       $arSoportePago->setTurnoFijo(1);
                                    }*/      
                                    if($arContrato->getTurnoFijoOrdinario()) {
                                        $arSoportePago->setTurnoFijo(1);
                                    }
                                    $em->persist($arSoportePago);                                                    
                                }
                            }                                                    
                        }                        
                    }   
                                      
                }                
                $em->flush();
                
                //Generar los detalles del soporte de pago
                $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
                $arSoportesPago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo));
                foreach ($arSoportesPago as $arSoportePago) {
                    $em->getRepository('BrasaTurnoBundle:TurSoportePago')->generar($arSoportePago, $arFestivos);                    
                }                                                
                $em->flush();
                
                //Genera soporte pago "programacion"
                foreach ($arSoportesPago as $arSoportePago) {
                    $em->getRepository('BrasaTurnoBundle:TurSoportePago')->generarProgramacion($arSoportePago, $arSoportePagoPeriodo->getFechaDesde()->format('Y'), $arSoportePagoPeriodo->getFechaDesde()->format('m'));
                }                
                $em->flush();                
                
                $arSoportePagoPeriodo->setEstadoGenerado(1);
                $em->persist($arSoportePagoPeriodo);
                $em->flush();
                
                $em->getRepository('BrasaTurnoBundle:TurSoportePago')->resumen($arSoportePagoPeriodo);                
                $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->analizarInconsistencias($codigoSoportePagoPeriodo);                                                                                                    
                $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->liquidar($codigoSoportePagoPeriodo);                                                                    

                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));
            }
            if($request->request->get('OpDeshacer')) {    
                $codigoSoportePagoPeriodo = $request->request->get('OpDeshacer');                
                $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                if($arSoportePagoPeriodo->getEstadoProgramacionPago() == 0) {
                    $strSql = "DELETE FROM tur_programacion_alterna WHERE codigo_soporte_pago_periodo_fk = " . $codigoSoportePagoPeriodo;           
                    $em->getConnection()->executeQuery($strSql);                    
                    $em->getRepository('BrasaTurnoBundle:TurSoportePagoInconsistencia')->limpiar($codigoSoportePagoPeriodo);                            
                    $strSql = "DELETE FROM tur_soporte_pago_detalle WHERE codigo_soporte_pago_periodo_fk = " . $codigoSoportePagoPeriodo;           
                    $em->getConnection()->executeQuery($strSql);
                    $strSql = "DELETE FROM tur_soporte_pago WHERE codigo_soporte_pago_periodo_fk = " . $codigoSoportePagoPeriodo;           
                    $em->getConnection()->executeQuery($strSql);                    
                    $strSql = "DELETE FROM tur_soporte_pago_programacion WHERE codigo_soporte_pago_periodo_fk = " . $codigoSoportePagoPeriodo;           
                    $em->getConnection()->executeQuery($strSql);                    
                    
                    $arSoportePagoPeriodo->setEstadoGenerado(0);
                    $arSoportePagoPeriodo->setInconsistencias(0);
                    $arSoportePagoPeriodo->setRecursos(0);
                    $arSoportePagoPeriodo->setVrPago(0);
                    $arSoportePagoPeriodo->setVrDevengado(0);
                    $em->persist($arSoportePagoPeriodo);
                    $em->flush();                    
                } else {
                    $objMensaje->Mensaje("error", "El soporte de pago fue utilizado en una programacion pago, debe desbloquearlo para poder desgenerar", $this);
                }                                                 
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
            }
            if($request->request->get('OpCerrar')) {
                $codigoSoportePagoPeriodo = $request->request->get('OpCerrar');
                $arSoportePagoPeriodo = NEW \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                $arSoportePagoPeriodo->setEstadoCerrado(1);                
                $em->persist($arSoportePagoPeriodo);
                $em->flush();                                                   
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
            } 
            if($request->request->get('OpBloquearProgramacion')) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $codigoSoportePagoPeriodo = $request->request->get('OpBloquearProgramacion');
                $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                $arSoportePagoPeriodo->setEstadoBloquearProgramacion(1);                
                $em->persist($arSoportePagoPeriodo);
                $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
                $arSoportesPago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo));                
                foreach($arSoportesPago as $arSoportePago) {
                    $arProgramacionesDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                    $arProgramacionesDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoRecursoFk' => $arSoportePago->getCodigoRecursoFk(), 'anio' => $arSoportePagoPeriodo->getFechaDesde()->format('Y'), 'mes' => $arSoportePagoPeriodo->getFechaDesde()->format('m')));                                    
                    foreach($arProgramacionesDetalles as $arProgramacionDetalle) {
                        $arProgramacionDetalleAct = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                        $arProgramacionDetalleAct = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                                    
                        $arProgramacionDetalleAct->setPeriodoBloqueo($arSoportePagoPeriodo->getFechaHasta()->format('j'));
                        $em->persist($arProgramacionDetalleAct);
                    }
                }
                $em->flush();                                                   
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
            }  
            if($request->request->get('OpDesBloquearProgramacion')) {
                $codigoSoportePagoPeriodo = $request->request->get('OpDesBloquearProgramacion');
                $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                $arSoportePagoPeriodo->setEstadoBloquearProgramacion(0);                
                $em->persist($arSoportePagoPeriodo);
                $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
                $arSoportesPago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo));                
                foreach($arSoportesPago as $arSoportePago) {
                    $arProgramacionesDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                    $arProgramacionesDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoRecursoFk' => $arSoportePago->getCodigoRecursoFk(), 'anio' => $arSoportePagoPeriodo->getFechaDesde()->format('Y'), 'mes' => $arSoportePagoPeriodo->getFechaDesde()->format('m')));                                    
                    foreach($arProgramacionesDetalles as $arProgramacionDetalle) {
                        $arProgramacionDetalleAct = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                        $arProgramacionDetalleAct = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                                    
                        $arProgramacionDetalleAct->setPeriodoBloqueo(0);
                        $em->persist($arProgramacionDetalleAct);
                    }
                }                
                $em->flush();                                                   
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
            } 
            if($request->request->get('OpAprobarPagoNomina')) {    
                $codigoSoportePagoPeriodo = $request->request->get('OpAprobarPagoNomina');                
                $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                if($arSoportePagoPeriodo->getEstadoAprobadoPagoNomina() == 0) {                    
                    $arSoportePagoPeriodo->setEstadoAprobadoPagoNomina(1);
                    $em->persist($arSoportePagoPeriodo);
                    $em->flush();                    
                }                                             
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
            }  
            if($request->request->get('OpDesAprobarPagoNomina')) {    
                $codigoSoportePagoPeriodo = $request->request->get('OpDesAprobarPagoNomina');                
                $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                if($arSoportePagoPeriodo->getEstadoAprobadoPagoNomina() == 1 && $arSoportePagoPeriodo->getEstadoCerrado() == 0) {                    
                    $arSoportePagoPeriodo->setEstadoAprobadoPagoNomina(0);
                    $em->persist($arSoportePagoPeriodo);
                    $em->flush();                    
                }                                             
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
            }            
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
                
            }                       
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);                
                $this->listaPeriodo();
            }             
        }        
        $arSoportePagoPeriodos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:lista.html.twig', array(
            'arSoportePagoPeriodos' => $arSoportePagoPeriodos,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/proceso/generar/soporte/pago/detalle/{codigoSoportePagoPeriodo}", name="brs_tur_proceso_generar_soporte_pago_detalle")
     */     
    public function detalleAction(Request $request, $codigoSoportePagoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
        $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);        
        $form = $this->formularioDetalle($arSoportePagoPeriodo);
        $form->handleRequest($request);
        $this->lista($codigoSoportePagoPeriodo);
        if ($form->isValid()) {
            if ($form->get('BtnExcel')->isClicked()) {
                $this->listaDetalle("", $codigoSoportePagoPeriodo);
                $this->generarExcel();
            }
            if ($form->get('BtnExcelPago')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 106)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }                
                $this->listaDetalle("", $codigoSoportePagoPeriodo);
                $this->generarExcelPago();
            }            
            if ($form->get('BtnEliminarDetalle')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurSoportePago')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->liquidar($codigoSoportePagoPeriodo);
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago_detalle', array('codigoSoportePagoPeriodo' => $codigoSoportePagoPeriodo)));                                
            }  
            if ($form->get('BtnLiquidar')->isClicked()) {                                                
                $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->liquidar($codigoSoportePagoPeriodo);
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago_detalle', array('codigoSoportePagoPeriodo' => $codigoSoportePagoPeriodo)));                                
            }                                 
            if ($form->get('BtnLiquidarCompensacion2')->isClicked()) { 
                $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
                $em->getRepository('BrasaTurnoBundle:TurSoportePago')->compensar("", $codigoSoportePagoPeriodo, $arConfiguracion->getTipoCompensacion());
                $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->liquidar($codigoSoportePagoPeriodo);                                
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago_detalle', array('codigoSoportePagoPeriodo' => $codigoSoportePagoPeriodo)));                
                
            }            
            if ($form->get('BtnGenerarProgramacionAlterna')->isClicked()) { 
                set_time_limit(0);
                ini_set("memory_limit", -1); 
                $em->getRepository('BrasaTurnoBundle:TurSoportePago')->generarProgramacionAlterna($codigoSoportePagoPeriodo);
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago_detalle', array('codigoSoportePagoPeriodo' => $codigoSoportePagoPeriodo)));                                
            }    
            if ($form->get('BtnAjustarDevengado')->isClicked()) { 
                $em->getRepository('BrasaTurnoBundle:TurSoportePago')->ajustarDevengado($codigoSoportePagoPeriodo);
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago_detalle', array('codigoSoportePagoPeriodo' => $codigoSoportePagoPeriodo)));                                
            }             
        }
        $arSoportesPago = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 1500);        
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:detalle.html.twig', array(            
            'arSoportesPagos' => $arSoportesPago,
            'arSoportePagoPeriodo' => $arSoportePagoPeriodo,
            'form' => $form->createView()));
    }    

    /**
     * @Route("/tur/proceso/generar/soporte/pago/nuevo/{codigoSoportePagoPeriodo}", name="brs_tur_proceso_generar_soporte_pago_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoSoportePagoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
        if($codigoSoportePagoPeriodo != 0) {
            $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);
        }else{
            $arSoportePagoPeriodo->setFechaDesde(new \DateTime('now'));            
            $arSoportePagoPeriodo->setFechaHasta(new \DateTime('now'));  
            $arSoportePagoPeriodo->setDiasPeriodo(15);
        }
        $form = $this->createForm(new TurSoportePagoPeriodoType, $arSoportePagoPeriodo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arSoportePagoPeriodo = $form->getData();
            $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arSoportePagoPeriodo->getFechaDesde()->format('Y-m-d'), $arSoportePagoPeriodo->getFechaHasta()->format('Y-m-d'));
            $arrDias = $this->festivosDomingos($arSoportePagoPeriodo->getFechaDesde(), $arSoportePagoPeriodo->getFechaHasta(), $arFestivos);
            $arSoportePagoPeriodo->setDiaDomingoReal($arrDias['domingos']);
            $arSoportePagoPeriodo->setDiaFestivoReal($arrDias['festivos']);
            
            if($codigoSoportePagoPeriodo == 0) {
                if($arSoportePagoPeriodo->getCentroCostoRel()->getDescansoCompensacionDominicales()) {
                    $arSoportePagoPeriodo->setDiaDescansoCompensacion($arrDias['domingos']+$arrDias['festivos']);
                }   
                if($arSoportePagoPeriodo->getCentroCostoRel()->getDescansoCompensacionFijo()) {
                    $arSoportePagoPeriodo->setDiaDescansoCompensacion($arSoportePagoPeriodo->getCentroCostoRel()->getDiasDescansoCompensacionFijo());
                }
                $arSoportePagoPeriodo->setPagarDia31($arSoportePagoPeriodo->getCentroCostoRel()->getPagarDia31());                
            }
            
            $arSoportePagoPeriodo->setAnio($arSoportePagoPeriodo->getFechaDesde()->format('Y'));
            $arSoportePagoPeriodo->setMes($arSoportePagoPeriodo->getFechaDesde()->format('m'));                        
            $em->persist($arSoportePagoPeriodo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                                                                              
        }
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:nuevo.html.twig', array(
            'arSoportePagoPeriodo' => $arSoportePagoPeriodo,
            'form' => $form->createView()));
    }    

    /**
     * @Route("/tur/proceso/generar/soporte/pago/editar/{codigoSoportePago}", name="brs_tur_proceso_generar_soporte_pago_editar")
     */     
    public function editarAction(Request $request, $codigoSoportePago) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arSoportePago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        if($codigoSoportePago != 0) {
            $arSoportePago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($codigoSoportePago);
        }
        $form = $this->createForm(new TurSoportePagoType, $arSoportePago);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arSoportePago = $form->getData();            
            $em->persist($arSoportePago);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:editar.html.twig', array(
            'arSoportePago' => $arSoportePago,
            'form' => $form->createView()));
    }     
    
    /**
     * @Route("/tur/proceso/generar/soporte/pago/detalle/ver/{codigoSoportePago}", name="brs_tur_proceso_generar_soporte_pago_detalle_ver")
     */    
    public function verAction(Request $request, $codigoSoportePago) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones(); 
        $arSoportePago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportePago =  $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($codigoSoportePago);                                        
        $form = $this->formularioVer($arSoportePago->getSoportePagoPeriodoRel());
        $form->handleRequest($request);
        $this->listaDetalle($codigoSoportePago, "");
        if ($form->isValid()) {
            if ($form->get('BtnActualizar')->isClicked()) {                
                set_time_limit(0);
                ini_set("memory_limit", -1);                
                $arSoportePago->setHorasDescansoReales(0);
                $arSoportePago->setHorasDiurnasReales(0);
                $arSoportePago->setHorasNocturnasReales(0);
                $arSoportePago->setHorasFestivasDiurnasReales(0);
                $arSoportePago->setHorasFestivasNocturnasReales(0);
                $arSoportePago->setHorasExtrasOrdinariasDiurnasReales(0);
                $arSoportePago->setHorasExtrasOrdinariasNocturnasReales(0);
                $arSoportePago->setHorasExtrasFestivasDiurnasReales(0);
                $arSoportePago->setHorasExtrasFestivasNocturnasReales(0); 
                $strSql = "DELETE FROM tur_soporte_pago_detalle WHERE codigo_soporte_pago_fk = " . $codigoSoportePago;           
                $em->getConnection()->executeQuery($strSql);
                
                $arSoportePagoPeriodo = $arSoportePago->getSoportePagoPeriodoRel();
                $dateFechaDesde = $arSoportePagoPeriodo->getFechaDesde();
                $dateFechaHasta = $arSoportePagoPeriodo->getFechaHasta();
                $intDiaInicial = $dateFechaDesde->format('j');
                $intDiaFinal = $dateFechaHasta->format('j');
                $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($dateFechaDesde->format('Y-m-').'01', $dateFechaHasta->format('Y-m-').'31');
                $em->getRepository('BrasaTurnoBundle:TurSoportePago')->generar($arSoportePago, $arFestivos, $arSoportePago->getCodigoRecursoFk());                
                $em->flush();                
                $em->getRepository('BrasaTurnoBundle:TurSoportePago')->resumenSoportePago($dateFechaDesde, $dateFechaHasta, $arSoportePago->getCodigoSoportePagoPk());                
                $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
                $em->getRepository('BrasaTurnoBundle:TurSoportePago')->compensar($arSoportePago->getCodigoSoportePagoPk(), $arSoportePagoPeriodo->getCodigoSoportePagoPeriodoPk(), $arConfiguracion->getTipoCompensacion());                        
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago_detalle_ver', array('codigoSoportePago' => $codigoSoportePago)));                                
            }
        }        
        $strAnio = $arSoportePago->getFechaDesde()->format('Y');
        $strMes = $arSoportePago->getFechaDesde()->format('m');
        $arrDiaSemana = $objFunciones->diasMes($arSoportePago->getFechaDesde(), $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arSoportePago->getFechaDesde()->format('Y-m-').'01', $arSoportePago->getFechaDesde()->format('Y-m-').'31'));       
        $arSoportePagoProgramacion = new \Brasa\TurnoBundle\Entity\TurSoportePagoProgramacion();
        $arSoportePagoProgramacion =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoProgramacion')->findBy(array('codigoSoportePagoFk' => $arSoportePago->getCodigoSoportePagoPk()));                                
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalle =  $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('anio' => $strAnio, 'mes' => $strMes, 'codigoRecursoFk' => $arSoportePago->getCodigoRecursoFk()));                        
        $arSoportesPagoDetalle = $paginator->paginate($em->createQuery($this->strListaDqlDetalle), $request->query->get('page', 1), 200);        
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:ver.html.twig', array(                        
            'arProgramacionDetalle' => $arProgramacionDetalle,
            'arSoportesPagosDetalles' => $arSoportesPagoDetalle,
            'arSoportePago' => $arSoportePago,
            'arrDiaSemana' => $arrDiaSemana,
            'arSoportePagoProgramacion' => $arSoportePagoProgramacion,
            'form' => $form->createView()));
    }     
    
    /**
     * @Route("/tur/proceso/generar/soporte/pago/inconsistencia/{codigoSoportePagoPeriodo}", name="brs_tur_proceso_generar_soporte_pago_inconsistencia")
     */    
    public function inconsistenciasAction(Request $request, $codigoSoportePagoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
        $arSoportePagoPeriodo =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                                        
        $form = $this->formularioInconsistencias();
        $form->handleRequest($request);        
        if ($form->isValid()) {
            if ($form->get('BtnAnalizar')->isClicked()) {  
                $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->analizarInconsistencias($codigoSoportePagoPeriodo);
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago_inconsistencia', array('codigoSoportePagoPeriodo' => $codigoSoportePagoPeriodo)));                                
            }
        }        
        
        $dql =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoInconsistencia')->listaDql($codigoSoportePagoPeriodo);                        
        $arSoportePagoInconsistencia = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 500);        
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:inconsistencia.html.twig', array(                        
            'arSoportePagoInconsistencia' => $arSoportePagoInconsistencia,
            'form' => $form->createView()));
    }                  

    /**
     * @Route("/tur/proceso/generar/soporte/pago/ver/programacion/{codigoSoportePagoPeriodo}", name="brs_tur_proceso_generar_soporte_pago_ver_programacion")
     */    
    public function verProgramacionAction(Request $request, $codigoSoportePagoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
        $arSoportePagoPeriodo =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                                        
        $form = $this->formularioVerProgramacionAlterna();
        $form->handleRequest($request);        
        if ($form->isValid()) {
            if ($form->get('BtnAnalizar')->isClicked()) {  
                $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->analizarInconsistencias($codigoSoportePagoPeriodo);
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago_inconsistencia', array('codigoSoportePagoPeriodo' => $codigoSoportePagoPeriodo)));                                
            }
        }        
        $strAnio = $arSoportePagoPeriodo->getFechaDesde()->format('Y');
        $strMes = $arSoportePagoPeriodo->getFechaDesde()->format('m');
        $strAnioMes = $arSoportePagoPeriodo->getFechaDesde()->format('Y/m');
        $arrDiaSemana = array();
        for($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana);
        }        
        $dql =  $em->getRepository('BrasaTurnoBundle:TurProgramacionAlterna')->listaDql($codigoSoportePagoPeriodo);                        
        $arProgramacionAlterna = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 500);        
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:verProgramacion.html.twig', array(                        
            'arProgramacionAlterna' => $arProgramacionAlterna,
            'arrDiaSemana' => $arrDiaSemana,
            'form' => $form->createView()));
    }     
    
    private function listaPeriodo() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->listaDql(                
                $session->get('filtroSoportePagoEstadoCerrado')
                );        
    }    
    
    private function lista($codigoSoportePagoPeriodo) {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurSoportePago')->listaDql(
                $codigoSoportePagoPeriodo,
                $session->get('filtroSoportePagoEstadoCerrado')
                );        
    }

    private function listaDetalle($codigoSoportePago, $codigoSoportePagoPeriodo) {
        $em = $this->getDoctrine()->getManager();        
        $this->strListaDqlDetalle =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoDetalle')->listaDql($codigoSoportePagoPeriodo, $codigoSoportePago);
    }    

    private function filtrar ($form) {
        $session = new Session;      
        $session->set('filtroSoportePagoEstadoCerrado', $form->get('estadoCerrado')->getData());          
    }    
    
    private function formularioGenerar() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $arrayPropiedadesRecursoGrupo = array(
                'class' => 'BrasaTurnoBundle:TurRecursoGrupo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rg')
                    ->orderBy('rg.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoRecursoGrupo')) {
            $arrayPropiedadesRecursoGrupo['data'] = $em->getReference("BrasaTurnoBundle:TurRecursoGrupo", $session->get('filtroCodigoRecursoGrupo'));
        }        
        if($session->get('filtroSoportePagoEstadoCerrado') == null) {
            $session->set('filtroSoportePagoEstadoCerrado', 0); 
        }
        $form = $this->createFormBuilder()
            ->add('recursoGrupoRel', EntityType::class, $arrayPropiedadesRecursoGrupo)
            ->add('estadoCerrado', ChoiceType::class, array('choices'   => array('0' => 'SIN CERRAR', '1' => 'CERRADO', '2' => 'TODOS'), 'data' => $session->get('filtroSoportePagoEstadoCerrado')))                                            
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar')) 
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))         
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);        
        $arrBotonLiquidar = array('label' => 'Liquidar', 'disabled' => false);        
        $arrBotonLiquidarCompensacion = array('label' => 'Compensacion', 'disabled' => true);        
        $arrBotonGenerarProgramacionAlterna = array('label' => 'Generar programacion alterna', 'disabled' => true);
        $arrBotonAjustarDevengado = array('label' => 'Ajustar devengado', 'disabled' => false);

        if($arConfiguracion->getHabilitarCompesacion()) {
            $arrBotonLiquidarCompensacion['disabled'] = false;
        }
        if($arConfiguracion->getHabilitarProgramacionAlterna()) {
            $arrBotonGenerarProgramacionAlterna['disabled'] = false;
        }
        if($ar->getEstadoAprobadoPagoNomina() == 1) {
            $arrBotonLiquidarCompensacion['disabled'] = true;
            $arrBotonGenerarProgramacionAlterna['disabled'] = true;
            $arrBotonAjustarDevengado['disabled'] = true;
            $arrBotonLiquidar['disabled'] = true;
            $arrBotonEliminarDetalle['disabled'] = true;
        }        
        $form = $this->createFormBuilder()
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))                        
            ->add('BtnExcelPago', SubmitType::class, array('label'  => 'Excel pago'))                        
            ->add('BtnLiquidar', SubmitType::class, $arrBotonLiquidar)                                    
            ->add('BtnLiquidarCompensacion2', SubmitType::class, $arrBotonLiquidarCompensacion)                                    
            ->add('BtnGenerarProgramacionAlterna', SubmitType::class, $arrBotonGenerarProgramacionAlterna)                        
            ->add('BtnAjustarDevengado', SubmitType::class, $arrBotonAjustarDevengado)
            ->add('BtnEliminarDetalle', SubmitType::class, $arrBotonEliminarDetalle)            
            ->getForm();
        return $form;
    }
    
    private function formularioVer($ar) {
        $arrBotonActualizar = array('label' => 'Actualizar', 'disabled' => false);
        if($ar->getEstadoAprobadoPagoNomina() == 1) {
            $arrBotonActualizar['disabled'] = true;
        }
        $form = $this->createFormBuilder()     
            ->add('BtnActualizar', SubmitType::class, $arrBotonActualizar)               
            ->getForm();
        return $form;
    }
    
    private function formularioInconsistencias() {
        $form = $this->createFormBuilder()     
            ->add('BtnAnalizar', SubmitType::class, array('label'  => 'Analizar'))                
            ->getForm();
        return $form;
    }    
    
    private function formularioVerProgramacionAlterna() {
        $form = $this->createFormBuilder()     
                            
            ->getForm();
        return $form;
    }    
    
    /*
     * ya no se usa
     */
    private function insertarSoportePago ($arSoportePagoPeriodo, $arProgramacionDetalle, $dateFechaDesde, $dateFechaHasta, $codigoTurno, $dateFecha, $dateFecha2, $boolFestivo, $boolFestivo2) {        
        $em = $this->getDoctrine()->getManager();       
        //$arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
        $strTurnoFijoNomina = $arSoportePagoPeriodo->getRecursoGrupoRel()->getCodigoTurnoFijoNominaFk();
        $strTurnoFijoDescanso = $arSoportePagoPeriodo->getRecursoGrupoRel()->getCodigoTurnoFijoDescansoFk();
        if($arProgramacionDetalle->getRecursoRel()->getCodigoTurnoFijoNominaFk()) {
            $strTurnoFijoNomina = $arProgramacionDetalle->getRecursoRel()->getCodigoTurnoFijoNominaFk();
        }
        if($arProgramacionDetalle->getRecursoRel()->getCodigoTurnoFijoDescansoFk()) {
            $strTurnoFijoDescanso = $arProgramacionDetalle->getRecursoRel()->getCodigoTurnoFijoDescansoFk();
        }        
        $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
        $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigoTurno);
        if($arTurno->getDescanso() == 0 && $arTurno->getNovedad() == 0) {                
            if($strTurnoFijoNomina) {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurnoFijoNomina);
            }                
        }     
        if($arTurno->getDescanso() == 1) {
            if($strTurnoFijoDescanso) {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurnoFijoDescanso);
            }
        }
        
        $intDias = 0;                       
        $intMinutoInicio = (($arTurno->getHoraDesde()->format('i') * 100)/60)/100;
        $intHoraInicio = $arTurno->getHoraDesde()->format('G');        
        $intHoraInicio += $intMinutoInicio;
        $intMinutoFinal = (($arTurno->getHoraHasta()->format('i') * 100)/60)/100;
        $intHoraFinal = $arTurno->getHoraHasta()->format('G');
        $intHoraFinal += $intMinutoFinal;
        $diaSemana = $dateFecha->format('N');
        $diaSemana2 = $dateFecha2->format('N');
        if($arTurno->getNovedad() == 0) {
            $intDias += 1;
        }                    
        if($diaSemana == 7) {
            $boolFestivo = 1;
        }
        if($diaSemana2 == 7) {
            $boolFestivo2 = 1;
        }        
        $arrHoras1 = null;
        if(($intHoraInicio + $intMinutoInicio) <= $intHoraFinal){  
            $arrHoras = $this->turnoHoras($intHoraInicio, $intMinutoInicio, $intHoraFinal, $boolFestivo, 0, $arTurno->getNovedad(), $arTurno->getDescanso());
        } else {
            $arrHoras = $this->turnoHoras($intHoraInicio, $intMinutoInicio, 24, $boolFestivo, 0, $arTurno->getNovedad(), $arTurno->getDescanso());
            $arrHoras1 = $this->turnoHoras(0, 0, $intHoraFinal, $boolFestivo2, $arrHoras['horas'], $arTurno->getNovedad(), $arTurno->getDescanso());                 
        }
        $arSoportePagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
        $arSoportePagoDetalle->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
        $arSoportePagoDetalle->setRecursoRel($arProgramacionDetalle->getRecursoRel());
        $arSoportePagoDetalle->setProgramacionDetalleRel($arProgramacionDetalle);
        $arSoportePagoDetalle->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());            
        $arSoportePagoDetalle->setFecha($dateFecha);
        $arSoportePagoDetalle->setTurnoRel($arTurno);
        $arSoportePagoDetalle->setDescanso($arTurno->getDescanso());
        $arSoportePagoDetalle->setNovedad($arTurno->getNovedad());
        $arSoportePagoDetalle->setIncapacidad($arTurno->getIncapacidad());
        $arSoportePagoDetalle->setLicencia($arTurno->getLicencia());
        $arSoportePagoDetalle->setLicenciaNoRemunerada($arTurno->getLicenciaNoRemunerada());
        $arSoportePagoDetalle->setVacacion($arTurno->getVacacion());
        $arSoportePagoDetalle->setIngreso($arTurno->getIngreso());
        $arSoportePagoDetalle->setRetiro($arTurno->getRetiro());
        $arSoportePagoDetalle->setDias($intDias);
        $arSoportePagoDetalle->setHoras($arTurno->getHorasNomina());        
        $arSoportePagoDetalle->setHorasDiurnas($arrHoras['horasDiurnas']);
        $arSoportePagoDetalle->setHorasNocturnas($arrHoras['horasNocturnas']);
        $arSoportePagoDetalle->setHorasExtrasOrdinariasDiurnas($arrHoras['horasExtrasDiurnas']);
        $arSoportePagoDetalle->setHorasExtrasOrdinariasNocturnas($arrHoras['horasExtrasNocturnas']);
        $arSoportePagoDetalle->setHorasFestivasDiurnas($arrHoras['horasFestivasDiurnas']);
        $arSoportePagoDetalle->setHorasFestivasNocturnas($arrHoras['horasFestivasNocturnas']);        
        $arSoportePagoDetalle->setHorasExtrasFestivasDiurnas($arrHoras['horasExtrasFestivasDiurnas']);
        $arSoportePagoDetalle->setHorasExtrasFestivasNocturnas($arrHoras['horasExtrasFestivasNocturnas']);
        $arSoportePagoDetalle->setHorasDescanso($arrHoras['horasDescanso']);
        $arSoportePagoDetalle->setHorasNovedad($arrHoras['horasNovedad']);
        if($strTurnoFijoNomina) {
            $arSoportePagoDetalle->setHorasDiurnas($arrHoras['horasDiurnas'] + $arrHoras['horasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasFestivasDiurnas(0);
        }
        $em->persist($arSoportePagoDetalle);

        if($arrHoras1) {
            $arSoportePagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
            $arSoportePagoDetalle->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
            $arSoportePagoDetalle->setRecursoRel($arProgramacionDetalle->getRecursoRel());
            $arSoportePagoDetalle->setProgramacionDetalleRel($arProgramacionDetalle);
            $arSoportePagoDetalle->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());
            $arSoportePagoDetalle->setFecha($dateFecha2);
            $arSoportePagoDetalle->setTurnoRel($arTurno);
            $arSoportePagoDetalle->setDescanso($arTurno->getDescanso());
            $arSoportePagoDetalle->setNovedad(0);
            $arSoportePagoDetalle->setDias(0);
            $arSoportePagoDetalle->setHoras($arTurno->getHorasNomina());        
            $arSoportePagoDetalle->setHorasDiurnas($arrHoras1['horasDiurnas']);
            $arSoportePagoDetalle->setHorasNocturnas($arrHoras1['horasNocturnas']);
            $arSoportePagoDetalle->setHorasExtrasOrdinariasDiurnas($arrHoras1['horasExtrasDiurnas']);
            $arSoportePagoDetalle->setHorasExtrasOrdinariasNocturnas($arrHoras1['horasExtrasNocturnas']);
            $arSoportePagoDetalle->setHorasFestivasDiurnas($arrHoras1['horasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasFestivasNocturnas($arrHoras1['horasFestivasNocturnas']);        
            $arSoportePagoDetalle->setHorasExtrasFestivasDiurnas($arrHoras1['horasExtrasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasExtrasFestivasNocturnas($arrHoras1['horasExtrasFestivasNocturnas']);
            $arSoportePagoDetalle->setHorasDescanso($arrHoras1['horasDescanso']);
            $arSoportePagoDetalle->setHorasNovedad($arrHoras1['horasNovedad']);
            $em->persist($arSoportePagoDetalle);            
        }                    
    }

    public function festivo($arFestivos, $dateFecha) {
        $boolFestivo = 0;
        foreach ($arFestivos as $arFestivo) {
            if($arFestivo['fecha'] == $dateFecha) {
                $boolFestivo = 1;
            }
        }
        return $boolFestivo;
    }

    private function calcularTiempo($intInicial, $intFinal, $intParametroInicio, $intParametroFinal) {
        $intHoras = 0;
        $intHoraIniciaTemporal = 0;
        $intHoraTerminaTemporal = 0;
        if($intInicial < $intParametroInicio) {
            $intHoraIniciaTemporal = $intParametroInicio;
        } else {
            $intHoraIniciaTemporal = $intInicial;
        }
        if($intFinal > $intParametroFinal) {
            if($intInicial > $intParametroFinal) {
                $intHoraTerminaTemporal = $intInicial;
            } else {
                $intHoraTerminaTemporal = $intParametroFinal;
            }
        } else {
            if($intFinal > $intParametroInicio) {
                $intHoraTerminaTemporal = $intFinal;
            } else {
                $intHoraTerminaTemporal = $intParametroInicio;
            }
        }
        $intHoras = $intHoraTerminaTemporal - $intHoraIniciaTemporal;
        return $intHoras;
    }

    private function generarExcel() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);        
        $em = $this->getDoctrine()->getManager();        
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'CODIGO')
                    ->setCellValue('C1', 'IDENTIFICACION')
                    ->setCellValue('D1', 'RECURSO')
                    ->setCellValue('E1', 'CONTRATO')
                    ->setCellValue('F1', 'DESDE')
                    ->setCellValue('G1', 'HASTA')
                    ->setCellValue('H1', 'DÍAS')
                    ->setCellValue('I1', 'DES')
                    ->setCellValue('J1', 'NOV')
                    ->setCellValue('K1', 'ING')    
                    ->setCellValue('L1', 'RET')
                    ->setCellValue('M1', 'VAC')
                    ->setCellValue('N1', 'INC')
                    ->setCellValue('O1', 'LIC')
                    ->setCellValue('P1', 'H')
                    ->setCellValue('Q1', 'HDS')
                    ->setCellValue('R1', 'HD')
                    ->setCellValue('S1', 'HN')
                    ->setCellValue('T1', 'HFD')
                    ->setCellValue('U1', 'HFN')                
                    ->setCellValue('V1', 'HEOD')
                    ->setCellValue('W1', 'HEON')
                    ->setCellValue('X1', 'HEFD')
                    ->setCellValue('Y1', 'HEFN')
                    ->setCellValue('Z1', 'HDSR')
                    ->setCellValue('AA1', 'HDR')
                    ->setCellValue('AB1', 'HNR')
                    ->setCellValue('AC1', 'HFDR')
                    ->setCellValue('AD1', 'HFNR')                
                    ->setCellValue('AE1', 'HEODR')
                    ->setCellValue('AF1', 'HEONR')
                    ->setCellValue('AG1', 'HEFDR')
                    ->setCellValue('AH1', 'HEFNR');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportesPago = $query->getResult();
        foreach ($arSoportesPago as $arSoportePago) {  
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSoportePago->getCodigoSoportePagoPk())
                    ->setCellValue('B' . $i, $arSoportePago->getRecursoRel()->getCodigoEmpleadoFk())
                    ->setCellValue('C' . $i, $arSoportePago->getRecursoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arSoportePago->getRecursoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arSoportePago->getCodigoContratoFk())
                    ->setCellValue('F' . $i, $arSoportePago->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('G' . $i, $arSoportePago->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('H' . $i, $arSoportePago->getDias())
                    ->setCellValue('I' . $i, $arSoportePago->getDescanso())
                    ->setCellValue('J' . $i, $arSoportePago->getNovedad())
                    ->setCellValue('K' . $i, $arSoportePago->getIngreso())
                    ->setCellValue('L' . $i, $arSoportePago->getRetiro())
                    ->setCellValue('M' . $i, $arSoportePago->getVacacion())
                    ->setCellValue('N' . $i, $arSoportePago->getIncapacidad())
                    ->setCellValue('O' . $i, $arSoportePago->getLicencia())
                    ->setCellValue('P' . $i, $arSoportePago->getHoras())
                    ->setCellValue('Q' . $i, $arSoportePago->getHorasDescanso())
                    ->setCellValue('R' . $i, $arSoportePago->getHorasDiurnas())
                    ->setCellValue('S' . $i, $arSoportePago->getHorasNocturnas())
                    ->setCellValue('T' . $i, $arSoportePago->getHorasFestivasDiurnas())
                    ->setCellValue('U' . $i, $arSoportePago->getHorasFestivasNocturnas())                    
                    ->setCellValue('V' . $i, $arSoportePago->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('W' . $i, $arSoportePago->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('X' . $i, $arSoportePago->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('Y' . $i, $arSoportePago->getHorasExtrasFestivasNocturnas())
                    ->setCellValue('Z' . $i, $arSoportePago->getHorasDescansoReales())
                    ->setCellValue('AA' . $i, $arSoportePago->getHorasDiurnasReales())
                    ->setCellValue('AB' . $i, $arSoportePago->getHorasNocturnasReales())
                    ->setCellValue('AC' . $i, $arSoportePago->getHorasFestivasDiurnasReales())
                    ->setCellValue('AD' . $i, $arSoportePago->getHorasFestivasNocturnasReales())                    
                    ->setCellValue('AE' . $i, $arSoportePago->getHorasExtrasOrdinariasDiurnasReales())
                    ->setCellValue('AF' . $i, $arSoportePago->getHorasExtrasOrdinariasNocturnasReales())
                    ->setCellValue('AG' . $i, $arSoportePago->getHorasExtrasFestivasDiurnasReales())
                    ->setCellValue('AH' . $i, $arSoportePago->getHorasExtrasFestivasNocturnasReales());

            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('SoportePago');
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->createSheet(2)->setTitle('Detalle')       
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'RECURSO')
                    ->setCellValue('C1', 'TURNO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'DIAS')
                    ->setCellValue('F1', 'DESCANSO')
                    ->setCellValue('G1', 'HDS')    
                    ->setCellValue('H1', 'HD')
                    ->setCellValue('I1', 'HN')
                    ->setCellValue('J1', 'HFD')
                    ->setCellValue('K1', 'HFN')                
                    ->setCellValue('L1', 'HEOD')
                    ->setCellValue('M1', 'HEON')
                    ->setCellValue('N1', 'HEFD')
                    ->setCellValue('O1', 'HEFN');
        
        $i = 2;
        
        $query = $em->createQuery($this->strListaDqlDetalle);
        $arSoportesPagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
        $arSoportesPagoDetalle = $query->getResult();

        foreach ($arSoportesPagoDetalle as $arSoportePagoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('A' . $i, $arSoportePagoDetalle->getCodigoSoportePagoDetallePk())
                    ->setCellValue('B' . $i, $arSoportePagoDetalle->getRecursoRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arSoportePagoDetalle->getCodigoTurnoFk())
                    ->setCellValue('D' . $i, $arSoportePagoDetalle->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arSoportePagoDetalle->getDias())
                    ->setCellValue('F' . $i, $arSoportePagoDetalle->getDescanso())
                    ->setCellValue('G' . $i, $arSoportePagoDetalle->getHorasDescanso())
                    ->setCellValue('H' . $i, $arSoportePagoDetalle->getHorasDiurnas())
                    ->setCellValue('I' . $i, $arSoportePagoDetalle->getHorasNocturnas())
                    ->setCellValue('J' . $i, $arSoportePagoDetalle->getHorasFestivasDiurnas())
                    ->setCellValue('K' . $i, $arSoportePagoDetalle->getHorasFestivasNocturnas())                    
                    ->setCellValue('L' . $i, $arSoportePagoDetalle->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('M' . $i, $arSoportePagoDetalle->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('N' . $i, $arSoportePagoDetalle->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('O' . $i, $arSoportePagoDetalle->getHorasExtrasFestivasNocturnas());

            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('Detalle');                
        $objPHPExcel->setActiveSheetIndex(0);
        
        //Hoja con las programaciones de los recursos                
        $objPHPExcel->createSheet(3)->setTitle('RecursoProgramacion')
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'COD.REC')
                    ->setCellValue('C1', 'IDENT')
                    ->setCellValue('D1', 'RECURSO')
                    ->setCellValue('E1', 'CLIENTE')
                    ->setCellValue('F1', 'PUESTO')
                    ->setCellValue('G1', 'D1')
                    ->setCellValue('H1', 'D2')
                    ->setCellValue('I1', 'D3')
                    ->setCellValue('J1', 'D4')
                    ->setCellValue('K1', 'D5')
                    ->setCellValue('L1', 'D6')    
                    ->setCellValue('M1', 'D7')
                    ->setCellValue('N1', 'D8')
                    ->setCellValue('O1', 'D9')
                    ->setCellValue('P1', 'D10')                
                    ->setCellValue('Q1', 'D11')
                    ->setCellValue('R1', 'D12')
                    ->setCellValue('S1', 'D13')
                    ->setCellValue('T1', 'D14')
                    ->setCellValue('U1', 'D15')
                    ->setCellValue('V1', 'D16')
                    ->setCellValue('W1', 'D17')
                    ->setCellValue('X1', 'D18')
                    ->setCellValue('Y1', 'D19')
                    ->setCellValue('Z1', 'D20')
                    ->setCellValue('AA1', 'D21')
                    ->setCellValue('AB1', 'D22')
                    ->setCellValue('AC1', 'D23')
                    ->setCellValue('AD1', 'D24')
                    ->setCellValue('AE1', 'D25')
                    ->setCellValue('AF1', 'D26')
                    ->setCellValue('AG1', 'D27')
                    ->setCellValue('AH1', 'D28')
                    ->setCellValue('AI1', 'D29')
                    ->setCellValue('AJ1', 'D30')
                    ->setCellValue('AK1', 'D31');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportesPago = $query->getResult();
        foreach ($arSoportesPago as $arSoportePago) { 
            $strAnio = $arSoportePago->getFechaDesde()->format('Y');
            $strMes = $arSoportePago->getFechaDesde()->format('m');            
            $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
            $arProgramacionDetalles =  $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('anio' => $strAnio, 'mes' => $strMes, 'codigoRecursoFk' => $arSoportePago->getCodigoRecursoFk()));                                    
            foreach($arProgramacionDetalles as $arProgramacionDetalle) {
                $objPHPExcel->setActiveSheetIndex(2)
                        ->setCellValue('A' . $i, $arSoportePago->getCodigoSoportePagoPk())
                        ->setCellValue('B' . $i, $arSoportePago->getCodigoRecursoFk())
                        ->setCellValue('C' . $i, $arSoportePago->getRecursoRel()->getNumeroIdentificacion())
                        ->setCellValue('D' . $i, $arSoportePago->getRecursoRel()->getNombreCorto())
                        ->setCellValue('E' . $i, $arProgramacionDetalle->getProgramacionRel()->getClienteRel()->getNombreCorto())                        
                        ->setCellValue('G' . $i, $arProgramacionDetalle->getDia1())
                        ->setCellValue('H' . $i, $arProgramacionDetalle->getDia2())
                        ->setCellValue('I' . $i, $arProgramacionDetalle->getDia3())
                        ->setCellValue('J' . $i, $arProgramacionDetalle->getDia4())
                        ->setCellValue('K' . $i, $arProgramacionDetalle->getDia5())
                        ->setCellValue('L' . $i, $arProgramacionDetalle->getDia6())
                        ->setCellValue('M' . $i, $arProgramacionDetalle->getDia7())
                        ->setCellValue('N' . $i, $arProgramacionDetalle->getDia8())
                        ->setCellValue('O' . $i, $arProgramacionDetalle->getDia9())
                        ->setCellValue('P' . $i, $arProgramacionDetalle->getDia10())
                        ->setCellValue('Q' . $i, $arProgramacionDetalle->getDia11())
                        ->setCellValue('R' . $i, $arProgramacionDetalle->getDia12())
                        ->setCellValue('S' . $i, $arProgramacionDetalle->getDia13())
                        ->setCellValue('T' . $i, $arProgramacionDetalle->getDia14())
                        ->setCellValue('U' . $i, $arProgramacionDetalle->getDia15())
                        ->setCellValue('V' . $i, $arProgramacionDetalle->getDia16())
                        ->setCellValue('W' . $i, $arProgramacionDetalle->getDia17())
                        ->setCellValue('X' . $i, $arProgramacionDetalle->getDia18())
                        ->setCellValue('Y' . $i, $arProgramacionDetalle->getDia19())
                        ->setCellValue('Z' . $i, $arProgramacionDetalle->getDia20())
                        ->setCellValue('AA' . $i, $arProgramacionDetalle->getDia21())
                        ->setCellValue('AB' . $i, $arProgramacionDetalle->getDia22())
                        ->setCellValue('AC' . $i, $arProgramacionDetalle->getDia23())
                        ->setCellValue('AD' . $i, $arProgramacionDetalle->getDia24())
                        ->setCellValue('AE' . $i, $arProgramacionDetalle->getDia25())
                        ->setCellValue('AF' . $i, $arProgramacionDetalle->getDia26())
                        ->setCellValue('AG' . $i, $arProgramacionDetalle->getDia27())
                        ->setCellValue('AH' . $i, $arProgramacionDetalle->getDia28())
                        ->setCellValue('AI' . $i, $arProgramacionDetalle->getDia29())
                        ->setCellValue('AJ' . $i, $arProgramacionDetalle->getDia30())
                        ->setCellValue('AK' . $i, $arProgramacionDetalle->getDia31());
                if($arProgramacionDetalle->getPuestoRel()) {
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('F' . $i, $arProgramacionDetalle->getPuestoRel()->getNombre());
                }
                $i++;                
            }
        }
        for($col = 'A'; $col !== 'AK'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);            
        }        
        $objPHPExcel->getActiveSheet()->setTitle('RecursoProgramacion');     
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0);
        
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SoportesPagoTurnos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    } 

    private function generarExcelPago() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);        
        $em = $this->getDoctrine()->getManager();        
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(8); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'AD'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'V'; $col !== 'AD'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'CODIGO')
                    ->setCellValue('C1', 'IDENTIFICACION')
                    ->setCellValue('D1', 'RECURSO')
                    ->setCellValue('E1', 'CONTRATO')
                    ->setCellValue('F1', 'GRUPO')
                    ->setCellValue('G1', 'DESDE')
                    ->setCellValue('H1', 'HASTA')
                    ->setCellValue('I1', 'DÍAS')
                    ->setCellValue('J1', 'H')
                    ->setCellValue('K1', 'HDS')
                    ->setCellValue('L1', 'HD')
                    ->setCellValue('M1', 'HN')
                    ->setCellValue('N1', 'HFD')
                    ->setCellValue('O1', 'HFN')                
                    ->setCellValue('P1', 'HEOD')
                    ->setCellValue('Q1', 'HEON')
                    ->setCellValue('R1', 'HEFD')
                    ->setCellValue('S1', 'HEFN')
                    ->setCellValue('T1', 'HRN')
                    ->setCellValue('U1', 'HRFD')
                    ->setCellValue('V1', 'HRFN')
                    ->setCellValue('W1', 'SALARIO')
                    ->setCellValue('X1', 'A.TRA')
                    ->setCellValue('Y1', 'PAGO')
                    ->setCellValue('Z1', 'DEVENGADO')
                    ->setCellValue('AA1', 'DEV_PACTADO')
                    ->setCellValue('AB1', 'DEV_AJUSTE')
                    ->setCellValue('AC1', 'SEC');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();      
        $arSoportesPago = $query->getResult();
        foreach ($arSoportesPago as $arSoportePago) { 
            $contratoGrupo = "";
            if($arSoportePago->getCodigoContratoFk()) {                
                $arContrato =  $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arSoportePago->getCodigoContratoFk());
                if($arContrato->getCodigoContratoGrupoFk()) {
                    $contratoGrupo = $arContrato->getContratoGrupoRel()->getNombre();
                }                
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSoportePago->getCodigoSoportePagoPk())
                    ->setCellValue('B' . $i, $arSoportePago->getRecursoRel()->getCodigoEmpleadoFk())
                    ->setCellValue('C' . $i, $arSoportePago->getRecursoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arSoportePago->getRecursoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arSoportePago->getCodigoContratoFk())
                    ->setCellValue('F' . $i, $contratoGrupo)
                    ->setCellValue('G' . $i, $arSoportePago->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('H' . $i, $arSoportePago->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $arSoportePago->getDias())
                    ->setCellValue('J' . $i, $arSoportePago->getHoras())
                    ->setCellValue('K' . $i, $arSoportePago->getHorasDescanso())
                    ->setCellValue('L' . $i, $arSoportePago->getHorasDiurnas())
                    ->setCellValue('M' . $i, $arSoportePago->getHorasNocturnas())
                    ->setCellValue('N' . $i, $arSoportePago->getHorasFestivasDiurnas())
                    ->setCellValue('O' . $i, $arSoportePago->getHorasFestivasNocturnas())                    
                    ->setCellValue('P' . $i, $arSoportePago->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('Q' . $i, $arSoportePago->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('R' . $i, $arSoportePago->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('S' . $i, $arSoportePago->getHorasExtrasFestivasNocturnas())
                    ->setCellValue('T' . $i, $arSoportePago->getHorasRecargoNocturno())
                    ->setCellValue('U' . $i, $arSoportePago->getHorasRecargoFestivoDiurno())
                    ->setCellValue('V' . $i, $arSoportePago->getHorasRecargoFestivoNocturno())
                    ->setCellValue('W' . $i, $arSoportePago->getVrSalario())
                    ->setCellValue('X' . $i, $arSoportePago->getVrAuxilioTransporte())
                    ->setCellValue('Y' . $i, $arSoportePago->getVrPago())
                    ->setCellValue('Z' . $i, $arSoportePago->getVrDevengado())
                    ->setCellValue('AA' . $i, $arSoportePago->getVrDevengadoPactado())
                    ->setCellValue('AB' . $i, $arSoportePago->getVrAjusteDevengadoPactado())
                    ->setCellValue('AC' . $i, $arSoportePago->getSecuencia());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('SoportePago');       
        $objPHPExcel->setActiveSheetIndex(0);
        
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SoportesPagoTurnos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }
    
    private function turnoHoras($intHoraInicio, $intMinutoInicio, $intHoraFinal, $boolFestivo, $intHoras, $boolNovedad = 0, $boolDescanso = 0) {        
        if($boolNovedad == 0) {
            $intHorasNocturnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 0, 6);        
            $intHorasExtrasNocturnas = 0;
            $intTotalHoras = $intHorasNocturnas + $intHoras;
            if($intTotalHoras > 8) {
                $intHorasJornada = 8 - $intHoras;
                if($intHorasJornada >= 1) {
                    $intHorasNocturnasReales = $intHorasNocturnas - $intHorasJornada;
                    $intHorasNocturnas = $intHorasNocturnas - $intHorasNocturnasReales;
                    $intHorasExtrasNocturnas = $intHorasNocturnasReales;
                } else {
                    $intHorasExtrasNocturnas = $intHorasNocturnas;
                    $intHorasNocturnas = 0;
                }
            }

            $intHorasDiurnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 6, 22);            
            $intHorasExtrasDiurnas = 0;
            $intTotalHoras = $intHoras + $intHorasNocturnas + $intHorasExtrasNocturnas + $intHorasDiurnas;
            if($intTotalHoras > 8) {
                $intHorasJornada = 8 - ($intHoras + $intHorasNocturnas + $intHorasExtrasNocturnas);                    
                if($intHorasJornada > 1) {
                    $intHorasDiurnasReales = $intHorasDiurnas - $intHorasJornada;
                    $intHorasDiurnas = $intHorasDiurnas - $intHorasDiurnasReales;
                    $intHorasExtrasDiurnas = $intHorasDiurnasReales;
                } else {
                    $intHorasExtrasDiurnas = $intHorasDiurnas;
                    $intHorasDiurnas = 0;
                }            
            }

            $intHorasNocturnasNoche = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 22, 24); 
            $intHorasExtrasNocturnasNoche = 0;
            $intTotalHoras = $intHorasDiurnas + $intHorasExtrasDiurnas + $intHorasNocturnas + $intHorasNocturnasNoche;                                        
            if($intTotalHoras > 8) {                    
                $intHorasJornada = 8 - ($intHorasNocturnas + $intHorasDiurnas + $intHorasExtrasDiurnas);                    
                if($intHorasJornada > 1) {
                    $intHorasNocturnasNocheReales = $intHorasNocturnasNoche - $intHorasJornada;
                    $intHorasNocturnasNoche = $intHorasNocturnasNoche - $intHorasNocturnasNocheReales;
                    $intHorasExtrasNocturnasNoche = $intHorasNocturnasNocheReales;                        
                } else {
                    $intHorasExtrasNocturnasNoche = $intHorasNocturnasNoche;
                    $intHorasNocturnasNoche = 0;
                }
            }
            $intHorasNocturnas += $intHorasNocturnasNoche;        
            $intHorasExtrasNocturnas += $intHorasExtrasNocturnasNoche;

            $intHorasFestivasDiurnas = 0;
            $intHorasFestivasNocturnas = 0;
            $intHorasExtrasFestivasDiurnas = 0;
            $intHorasExtrasFestivasNocturnas = 0;
            if($boolFestivo == 1) {
                $intHorasFestivasDiurnas = $intHorasDiurnas;
                $intHorasDiurnas = 0;
                $intHorasFestivasNocturnas = $intHorasNocturnas;
                $intHorasNocturnas = 0;
                $intHorasExtrasFestivasDiurnas = $intHorasExtrasDiurnas;
                $intHorasExtrasDiurnas = 0;
                $intHorasExtrasFestivasNocturnas = $intHorasExtrasNocturnas;
                $intHorasExtrasNocturnas = 0;
            }                
            $intTotalHoras = $intHorasDiurnas+$intHorasNocturnas+$intHorasExtrasDiurnas+$intHorasExtrasNocturnas+$intHorasFestivasDiurnas+$intHorasFestivasNocturnas+$intHorasExtrasFestivasDiurnas+$intHorasExtrasFestivasNocturnas;            
            if($boolDescanso == 1) {                
                $arrHoras = array(
                    'horasDescanso' => $intTotalHoras,
                    'horasNovedad' => 0,
                    'horasDiurnas' => 0, 
                    'horasNocturnas' => 0, 
                    'horasExtrasDiurnas' => 0, 
                    'horasExtrasNocturnas' => 0,
                    'horasFestivasDiurnas' => 0, 
                    'horasFestivasNocturnas' => 0, 
                    'horasExtrasFestivasDiurnas' => 0, 
                    'horasExtrasFestivasNocturnas' => 0,
                    'horas' => $intTotalHoras);                
            } else {
                $arrHoras = array(
                    'horasDescanso' => 0,
                    'horasNovedad' => 0,
                    'horasDiurnas' => $intHorasDiurnas, 
                    'horasNocturnas' => $intHorasNocturnas, 
                    'horasExtrasDiurnas' => $intHorasExtrasDiurnas, 
                    'horasExtrasNocturnas' => $intHorasExtrasNocturnas,
                    'horasFestivasDiurnas' => $intHorasFestivasDiurnas, 
                    'horasFestivasNocturnas' => $intHorasFestivasNocturnas, 
                    'horasExtrasFestivasDiurnas' => $intHorasExtrasFestivasDiurnas, 
                    'horasExtrasFestivasNocturnas' => $intHorasExtrasFestivasNocturnas,
                    'horas' => $intTotalHoras);                
            }
            
        } else {
            $arrHoras = array(
                'horasDescanso' => 0,
                'horasNovedad' => 8,
                'horasDiurnas' => 0, 
                'horasNocturnas' => 0, 
                'horasExtrasDiurnas' => 0, 
                'horasExtrasNocturnas' => 0,
                'horasFestivasDiurnas' => 0, 
                'horasFestivasNocturnas' => 0, 
                'horasExtrasFestivasDiurnas' => 0, 
                'horasExtrasFestivasNocturnas' => 0,
                'horas' => 0);            
        }  
        
        return $arrHoras;
    }
    
    private function devuelveTurnoDia($arProgramacionDetalle, $intDia) {        
        $strTurno = NULL;
        if($intDia == 1) {
            $strTurno = $arProgramacionDetalle->getDia1();
        }
        if($intDia == 2) {
            $strTurno = $arProgramacionDetalle->getDia2();
        }
        if($intDia == 3) {
            $strTurno = $arProgramacionDetalle->getDia3();
        }
        if($intDia == 4) {
            $strTurno = $arProgramacionDetalle->getDia4();
        }
        if($intDia == 5) {
            $strTurno = $arProgramacionDetalle->getDia5();
        }
        if($intDia == 6) {
            $strTurno = $arProgramacionDetalle->getDia6();
        }
        if($intDia == 7) {
            $strTurno = $arProgramacionDetalle->getDia7();
        }
        if($intDia == 8) {
            $strTurno = $arProgramacionDetalle->getDia8();
        }
        if($intDia == 9) {
            $strTurno = $arProgramacionDetalle->getDia9();
        }
        if($intDia == 10) {
            $strTurno = $arProgramacionDetalle->getDia10();
        }
        if($intDia == 11) {
            $strTurno = $arProgramacionDetalle->getDia11();
        }
        if($intDia == 12) {
            $strTurno = $arProgramacionDetalle->getDia12();
        }
        if($intDia == 13) {
            $strTurno = $arProgramacionDetalle->getDia13();
        }
        if($intDia == 14) {
            $strTurno = $arProgramacionDetalle->getDia14();
        }
        if($intDia == 15) {
            $strTurno = $arProgramacionDetalle->getDia15();
        }
        if($intDia == 16) {
            $strTurno = $arProgramacionDetalle->getDia16();
        }
        if($intDia == 17) {
            $strTurno = $arProgramacionDetalle->getDia17();
        }
        if($intDia == 18) {
            $strTurno = $arProgramacionDetalle->getDia18();
        }
        if($intDia == 19) {
            $strTurno = $arProgramacionDetalle->getDia19();
        }
        if($intDia == 20) {
            $strTurno = $arProgramacionDetalle->getDia20();
        }
        if($intDia == 21) {
            $strTurno = $arProgramacionDetalle->getDia21();
        }
        if($intDia == 22) {
            $strTurno = $arProgramacionDetalle->getDia22();
        }
        if($intDia == 23) {
            $strTurno = $arProgramacionDetalle->getDia23();
        }
        if($intDia == 24) {
            $strTurno = $arProgramacionDetalle->getDia24();
        }
        if($intDia == 25) {
            $strTurno = $arProgramacionDetalle->getDia25();
        }
        if($intDia == 26) {
            $strTurno = $arProgramacionDetalle->getDia26();
        }
        if($intDia == 27) {
            $strTurno = $arProgramacionDetalle->getDia27();
        }
        if($intDia == 28) {
            $strTurno = $arProgramacionDetalle->getDia28();
        }
        if($intDia == 29) {
            $strTurno = $arProgramacionDetalle->getDia29();
        }
        if($intDia == 30) {
            $strTurno = $arProgramacionDetalle->getDia30();
        }        
        if($intDia == 31) {
            $strTurno = $arProgramacionDetalle->getDia31();
        }
        return $strTurno;
    }
    
    private function festivosDomingos($desde, $hasta, $arFestivos) {
        $arrDias = array('festivos' => 0, 'domingos' => 0);
        $fechaDesde = date_create($desde->format('Y-m-d'));
        $domingos = 0;
        $festivos = 0;        
        while($fechaDesde <= $hasta) {              
            if($fechaDesde->format('N') == 7) {
               $domingos++; 
            }
            if($this->festivo($arFestivos, $fechaDesde) == true) {
               $festivos++; 
            }
            $fechaDesde->modify('+1 day');
        }
        $arrDias['domingos'] = $domingos;
        $arrDias['festivos'] = $festivos;
        return $arrDias;
    }
     
    private function devuelveDiaSemanaEspaniol ($dateFecha) {
        $strDia = "";
        switch ($dateFecha->format('N')) {
            case 1:
                $strDia = "l";
                break;
            case 2:
                $strDia = "m";
                break;
            case 3:
                $strDia = "i";
                break;
            case 4:
                $strDia = "j";
                break;
            case 5:
                $strDia = "v";
                break;
            case 6:
                $strDia = "s";
                break;
            case 7:
                $strDia = "d";
                break;
        }

        return $strDia;
    }    
}