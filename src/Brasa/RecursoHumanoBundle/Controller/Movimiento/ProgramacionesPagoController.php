<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\RecursoHumanoBundle\Form\Type\RhuProgramacionPagoType;

class ProgramacionesPagoController extends Controller
{
    var $strDqlLista = "";
    var $intNumero = 0;
    
    /**
     * @Route("/rhu/programaciones/pago/lista", name="brs_rhu_programaciones_pago_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 1, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $session = $this->get('session');
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $session->set('filtroEstadoPagado', 0);
        $form = $this->formularioLista();
        $form->handleRequest($request);        
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($request->request->get('OpGenerar')) {
                $codigoProgramacionPago = $request->request->get('OpGenerar');
                $strResultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->generar($codigoProgramacionPago);
                if($strResultado == "") {
                    return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_lista'));
                } else {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
            }            
            if($request->request->get('OpLiquidar')) {
                $codigoProgramacionPago = $request->request->get('OpLiquidar');
                $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                if($arProgramacionPago->getEstadoGenerado() == 1 && $arProgramacionPago->getEstadoPagado() == 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->liquidar($codigoProgramacionPago);                    
                }                
            }            
            if($request->request->get('OpDeshacer')) {
                $codigoProgramacionPago = $request->request->get('OpDeshacer');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->deshacer($codigoProgramacionPago);
                return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_lista'));
            }
            if($request->request->get('OpPagar')) {
                $codigoProgramacionPago = $request->request->get('OpPagar');
                $inconsistencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->validarPagar($codigoProgramacionPago);
                if($inconsistencias == "") {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->pagar($codigoProgramacionPago);
                } else {
                    $objMensaje->Mensaje("error", $inconsistencias, $this);
                }
                
                return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_lista'));
            }
            if($request->request->get('OpExcelDetalle')) {
                $codigoProgramacionPago = $request->request->get('OpExcelDetalle');
                $this->generarExcelDetalle($codigoProgramacionPago);
            }
            if($form->get('BtnEliminarPago')->isClicked()) {
                if ($arrSeleccionados > 0 ){
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        if($arProgramacionPago->getEstadoPagado() == 0 && $arProgramacionPago->getEstadoGenerado() == 0) {
                            $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
                            if ($arProgramacionPagoDetalles == null){
                                $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->eliminar($codigoProgramacionPago);
                            } else {
                                $objMensaje->Mensaje("error", "La programación de pago tiene registros asociados, no se puede eliminar", $this);
                            }  
                        } else {
                            $objMensaje->Mensaje("error", "La programación de pago esta pagada o generada, no se puede eliminar", $this);
                        }
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_lista'));
                }
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
            }

        }

        $arProgramacionPago = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ProgramacionesPago:lista.html.twig', array(
            'arProgramacionPago' => $arProgramacionPago,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/programaciones/pago/nuevo/{codigoProgramacionPago}", name="brs_rhu_programaciones_pago_nuevo")
     */
    public function nuevoAction($codigoProgramacionPago, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        if($codigoProgramacionPago != 0) {
            $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        } else {
            $arProgramacionPago->setFechaDesde(new \DateTime('now'));
            $arProgramacionPago->setFechaHasta(new \DateTime('now'));
            $arProgramacionPago->setFechaHastaReal(new \DateTime('now'));             
        }
        $form = $this->createForm(new RhuProgramacionPagoType(), $arProgramacionPago);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arProgramacionPago = $form->getData();            
            if($codigoProgramacionPago == 0) {
                $arProgramacionPago->setNoGeneraPeriodo(1);
                $arUsuario = $this->get('security.context')->getToken()->getUser();
                $arProgramacionPago->setCodigoUsuario($arUsuario->getUserName());
            }
            
            $em->persist($arProgramacionPago);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ProgramacionesPago:nuevo.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/programaciones/pago/detalle/{codigoProgramacionPago}", name="brs_rhu_programaciones_pago_detalle")
     */
    public function detalleAction($codigoProgramacionPago, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $paginator  = $this->get('knp_paginator');
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $form = $this->formularioDetalle($arProgramacionPago);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGenerarEmpleados')->isClicked()) {
                if($arProgramacionPago->getEstadoGenerado() == 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->generarEmpleados($codigoProgramacionPago);
                    $arProgramacionPago->setEmpleadosGenerados(1);
                    $em->persist($arProgramacionPago);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));
                } else {
                    $objMensaje->Mensaje("error", "No puede generar empleados cuando la programacion esta generada", $this);
                }
            }
            if($form->get('BtnDesbloquearSoportePagoTurnos')->isClicked()) {
                if($arProgramacionPago->getCodigoSoportePagoPeriodoFk()) {
                    $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();                       
                    $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($arProgramacionPago->getCodigoSoportePagoPeriodoFk());                
                    if($arSoportePagoPeriodo->getEstadoCerrado() == 0) {
                        $arSoportePagoPeriodo->setEstadoBloqueoNomina(0);
                        $em->persist($arSoportePagoPeriodo);
                        $em->flush();
                        $objMensaje->Mensaje("informacion", "Se desbloqueo el soporte de pago de turnos, ahora puede ser modificado", $this);                        
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));
                }
            }                                    
            if($form->get('BtnEliminarEmpleados')->isClicked()) {               
                    $arrSeleccionados = $request->request->get('ChkSeleccionarSede');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoProgramacionPagoSede) {
                            $arProgramacionPagoDetalleSede = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede();
                            $arProgramacionPagoDetalleSede = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalleSede')->find($codigoProgramacionPagoSede);
                            $em->remove($arProgramacionPagoDetalleSede);
                        }
                    }

                    $arrSeleccionados = $request->request->get('ChkSeleccionarDetalle');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigo) {
                            $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoDetalleFk' => $codigo));
                            foreach ($arPagos as $arPago) {
                                $strSql = "DELETE FROM rhu_pago_detalle WHERE codigo_pago_fk = " . $arPago->getCodigoPagoPk();                           
                                $em->getConnection()->executeQuery($strSql);                    
                                $em->remove($arPago);
                            }                                                        
                            $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                            $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($codigo);
                            $em->remove($arProgramacionPagoDetalle);
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));

            }               
            if($form->get('BtnEliminarTodoEmpleados')->isClicked()) {
                if ($arProgramacionPago->getEstadoGenerado() == 0 ){
                    $resultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->eliminarTodoEmpleados($codigoProgramacionPago);
                }
                return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));
            }
        }
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPago->getCodigoCentroCostoFk());

        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->periodo($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta(), "", $arProgramacionPago->getCodigoCentroCostoFk());                       
        $arIncapacidades = $paginator->paginate($arIncapacidades, $request->query->get('page', 1), 200);
        $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
        $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->periodo($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta(), "", $arProgramacionPago->getCodigoCentroCostoFk());                       
        $arLicencias = $paginator->paginate($arLicencias, $request->query->get('page', 1), 200);        
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->periodo($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta(), "", $arProgramacionPago->getCodigoCentroCostoFk());                       
        $arVacaciones = $paginator->paginate($arVacaciones, $request->query->get('page', 1), 200);        
        
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->listaDQL($codigoProgramacionPago));
        $arProgramacionPagoDetalles = $paginator->paginate($query, $request->query->get('page', 1), 2000);
        $arProgramacionPagoDetalleSedes = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede();
        $arProgramacionPagoDetalleSedes = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalleSede')->findAll();
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ProgramacionesPago:detalle.html.twig', array(
                    'arCentroCosto' => $arCentroCosto,                                        
                    'arIncapacidades' => $arIncapacidades,
                    'arLicencias' => $arLicencias,
                    'arVacaciones' => $arVacaciones,
                    'arProgramacionPagoDetalles' => $arProgramacionPagoDetalles,
                    'arProgramacionPagoDetalleSedes' => $arProgramacionPagoDetalleSedes,
                    'arProgramacionPago' => $arProgramacionPago,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/programaciones/pago/detalle/prima/{codigoProgramacionPago}", name="brs_rhu_programaciones_pago_detalle_prima")
     */
    public function detallePrimaAction($codigoProgramacionPago, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $paginator  = $this->get('knp_paginator');
        $permisoParametros = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 114); 
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $form = $this->formularioDetallePrima($arProgramacionPago);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGenerarEmpleados')->isClicked()) {
                if($arProgramacionPago->getEstadoGenerado() == 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->generarEmpleados($codigoProgramacionPago);
                    $arProgramacionPago->setEmpleadosGenerados(1);
                    $em->persist($arProgramacionPago);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_detalle_prima', array('codigoProgramacionPago' => $codigoProgramacionPago)));
                } else {
                    $objMensaje->Mensaje("error", "No puede generar empleados cuando la programacion esta generada", $this);
                }
            }                        
            if($form->get('BtnEliminarEmpleados')->isClicked()) {               
                    $arrSeleccionados = $request->request->get('ChkSeleccionarSede');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoProgramacionPagoSede) {
                            $arProgramacionPagoDetalleSede = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede();
                            $arProgramacionPagoDetalleSede = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalleSede')->find($codigoProgramacionPagoSede);
                            $em->remove($arProgramacionPagoDetalleSede);
                        }
                    }

                    $arrSeleccionados = $request->request->get('ChkSeleccionarDetalle');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigo) {
                            $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoDetalleFk' => $codigo));
                            foreach ($arPagos as $arPago) {
                                $strSql = "DELETE FROM rhu_pago_detalle WHERE codigo_pago_fk = " . $arPago->getCodigoPagoPk();                           
                                $em->getConnection()->executeQuery($strSql);                    
                                $em->remove($arPago);
                            }                                                        
                            $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                            $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($codigo);
                            $em->remove($arProgramacionPagoDetalle);
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_detalle_prima', array('codigoProgramacionPago' => $codigoProgramacionPago)));

            }            
            if($form->get('BtnEliminarTodoEmpleados')->isClicked()) {
                if ($arProgramacionPago->getEstadoGenerado() == 0 ){
                    $resultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->eliminarTodoEmpleados($codigoProgramacionPago);
                }
                return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_detalle_prima', array('codigoProgramacionPago' => $codigoProgramacionPago)));
            }

        }
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPago->getCodigoCentroCostoFk());
        
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->listaDQL($codigoProgramacionPago));
        $arProgramacionPagoDetalles = $paginator->paginate($query, $request->query->get('page', 1), 2000);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ProgramacionesPago:detallePrima.html.twig', array(
                    'arCentroCosto' => $arCentroCosto,                                        
                    'arProgramacionPagoDetalles' => $arProgramacionPagoDetalles,
                    'arProgramacionPago' => $arProgramacionPago,
                    'permisoParametros' => $permisoParametros,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/programaciones/pago/agregar/empleado/{codigoProgramacionPago}", name="brs_rhu_programaciones_pago_agregar_empleado")
     */
    public function agregarEmpleadoAction($codigoProgramacionPago, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
            $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $form->getData('numeroIdentificacion')));
            if(count($arEmpleado) > 0) {
                if($arEmpleado->getCodigoContratoActivoFk()) {
                    $intCodigoContrato = $arEmpleado->getCodigoContratoActivoFk();
                } else {
                    $intCodigoContrato = $arEmpleado->getCodigoContratoUltimoFk();
                }
                $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($intCodigoContrato);
                if(count($arContrato) > 0) {
                    $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                    $arProgramacionPagoDetalle->setEmpleadoRel($arEmpleado);
                    $arProgramacionPagoDetalle->setProgramacionPagoRel($arProgramacionPago);
                    $arProgramacionPagoDetalle->setFechaDesde($arProgramacionPago->getFechaDesde());
                    $arProgramacionPagoDetalle->setFechaHasta($arProgramacionPago->getFechaHasta());
                    $arProgramacionPagoDetalle->setFechaDesdePago($arProgramacionPago->getFechaDesde());
                    $arProgramacionPagoDetalle->setFechaHastaPago($arProgramacionPago->getFechaHasta());                    
                    $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalario());
                    $arProgramacionPagoDetalle->setIndefinido($arContrato->getIndefinido());
                    $arProgramacionPagoDetalle->setContratoRel($arContrato);
                    $arProgramacionPagoDetalle->setCodigoSoportePagoFk(1);
                    if($arContrato->getCodigoTipoTiempoFk() == 2) {
                        $arProgramacionPagoDetalle->setFactorDia(4);
                    } else {
                        $arProgramacionPagoDetalle->setFactorDia(8);
                    }

                    $em->persist($arProgramacionPagoDetalle);
                    $em->flush();
                }
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";


        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ProgramacionesPago:agregarEmpleado.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/programaciones/pago/inconsistencias/{codigoProgramacionPago}", name="brs_rhu_programaciones_pago_inconsistencias")
     */
    public function inconsistenciasAction ($codigoProgramacionPago, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()    
                    ->add('BtnLimpiar', 'submit', array('label'  => 'Limpiar',))
                    ->getForm();    
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnLimpiar')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoInconsistencia')->eliminarProgramacionPago($codigoProgramacionPago);
                $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);            
                $arProgramacionPago->setInconsistencias(0);
                $em->persist($arProgramacionPago);
                $em->flush();                                
                echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";                                
            }   
        }
        $arProgramacionPagoInconsistencias = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoInconsistencia();
        $arProgramacionPagoInconsistencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoInconsistencia')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ProgramacionesPago:inconsistencias.html.twig', array(
            'arProgramacionPagoInconsistencias' => $arProgramacionPagoInconsistencias,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/programacion/pago/resumen/turno/ver/{codigoProgramacionPagoDetalle}", name="brs_rhu_programacion_pago_resumen_turno_ver")
     */    
    public function verResumenTurnosAction($codigoProgramacionPagoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();        
        $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($codigoProgramacionPagoDetalle);        
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findOneBy(array('codigoProgramacionPagoDetalleFk' => $codigoProgramacionPagoDetalle));                
        $form = $this->formularioVerReusmenTurno($arProgramacionPagoDetalle->getProgramacionPagoRel());
        $form->handleRequest($request);
        if ($form->isValid()) {
            if($form->get('BtnActualizar')->isClicked()) {
                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoDetalleFk' => $codigoProgramacionPagoDetalle));
                foreach ($arPagos as $arPago) {
                    $strSql = "DELETE FROM rhu_pago_detalle WHERE codigo_pago_fk = " . $arPago->getCodigoPagoPk();                           
                    $em->getConnection()->executeQuery($strSql);                    
                    $em->remove($arPago);
                }
                if(!$arProgramacionPagoDetalle->getCodigoSoportePagoFk()) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->actualizarEmpleado($codigoProgramacionPagoDetalle);                    
                } else {
                    $arContrato = $arProgramacionPagoDetalle->getContratoRel();                            
                    $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalarioPago());
                    $arProgramacionPagoDetalle->setIndefinido($arContrato->getIndefinido());
                    $arProgramacionPagoDetalle->setSalarioIntegral($arContrato->getSalarioIntegral());
                    if($arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 4 || $arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 5) {
                        $arProgramacionPagoDetalle->setDescuentoPension(0);
                        $arProgramacionPagoDetalle->setDescuentoSalud(0);
                        $arProgramacionPagoDetalle->setPagoAuxilioTransporte(0);
                    }
                    if ($arContrato->getCodigoTipoPensionFk() == 5){
                        $arProgramacionPagoDetalle->setDescuentoPension(0);
                    }   
                    $floValorDia = $arContrato->getVrSalarioPago() / 30;       
                    $floValorHora = $floValorDia / $arContrato->getFactorHorasDia();   
                    $arProgramacionPagoDetalle->setVrDia($floValorDia);
                    $arProgramacionPagoDetalle->setVrHora($floValorHora);
                    $floDevengado = $arProgramacionPagoDetalle->getDias() * $floValorDia;
                    $arProgramacionPagoDetalle->setVrDevengado($floDevengado);                    
                    $em->persist($arProgramacionPagoDetalle);
                    $em->flush();
                }               
                
                $codigoPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->generarPago($arProgramacionPagoDetalle, $arProgramacionPagoDetalle->getProgramacionPagoRel(), $arProgramacionPagoDetalle->getProgramacionPagoRel()->getCentroCostoRel(), $arConfiguracion, 1);                   
                if($codigoPago > 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->liquidar($codigoPago, $arConfiguracion);
                }                
                $em->flush();                
                return $this->redirect($this->generateUrl('brs_rhu_programacion_pago_resumen_turno_ver', array('codigoProgramacionPagoDetalle' => $codigoProgramacionPagoDetalle)));
            }
            if($form->get('BtnActualizarHoras')->isClicked()) {  
                $arrControles = $request->request->All();
                if($arrControles['TxtDiasTransporte'] != "") {
                    $arProgramacionPagoDetalle->setDiasTransporte($arrControles['TxtDiasTransporte']);                
                }                
                if($arrControles['TxtHorasDescanso'] != "") {
                    $arProgramacionPagoDetalle->setHorasDescanso($arrControles['TxtHorasDescanso']);                
                }
                if($arrControles['TxtHorasDiurnas'] != "") {
                    $arProgramacionPagoDetalle->setHorasDiurnas($arrControles['TxtHorasDiurnas']);                
                }
                if($arrControles['TxtHorasNocturnas'] != "") {
                    $arProgramacionPagoDetalle->setHorasNocturnas($arrControles['TxtHorasNocturnas']);                
                }
                if($arrControles['TxtHorasFestivasDiurnas'] != "") {
                    $arProgramacionPagoDetalle->setHorasFestivasDiurnas($arrControles['TxtHorasFestivasDiurnas']);                
                }
                if($arrControles['TxtHorasFestivasNocturnas'] != "") {
                    $arProgramacionPagoDetalle->setHorasFestivasNocturnas($arrControles['TxtHorasFestivasNocturnas']);                
                }
                if($arrControles['TxtHorasExtrasOrdinariasDiurnas'] != "") {
                    $arProgramacionPagoDetalle->setHorasExtrasOrdinariasDiurnas($arrControles['TxtHorasExtrasOrdinariasDiurnas']);                
                }
                if($arrControles['TxtHorasExtrasOrdinariasNocturnas'] != "") {
                    $arProgramacionPagoDetalle->setHorasExtrasOrdinariasNocturnas($arrControles['TxtHorasExtrasOrdinariasNocturnas']);                
                }
                if($arrControles['TxtHorasExtrasFestivasDiurnas'] != "") {
                    $arProgramacionPagoDetalle->setHorasExtrasFestivasDiurnas($arrControles['TxtHorasExtrasFestivasDiurnas']);                
                }                
                if($arrControles['TxtHorasExtrasFestivasNocturnas'] != "") {
                    $arProgramacionPagoDetalle->setHorasExtrasFestivasNocturnas($arrControles['TxtHorasExtrasFestivasNocturnas']);                
                }   
                if($arrControles['TxtHorasRecargoFestivoDiurno'] != "") {
                    $arProgramacionPagoDetalle->setHorasRecargoFestivoDiurno($arrControles['TxtHorasRecargoFestivoDiurno']);                
                }  
                if($arrControles['TxtHorasRecargoFestivoNocturno'] != "") {
                    $arProgramacionPagoDetalle->setHorasRecargoFestivoNocturno($arrControles['TxtHorasRecargoFestivoNocturno']);                
                }                
                $em->persist($arProgramacionPagoDetalle);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_programacion_pago_resumen_turno_ver', array('codigoProgramacionPagoDetalle' => $codigoProgramacionPagoDetalle)));
            } 
            if($form->get('BtnActualizarHorasSoportePago')->isClicked()) {
                if($arProgramacionPagoDetalle->getCodigoSoportePagoFk()) {
                    $arSoportePago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
                    $arSoportePago =  $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arProgramacionPagoDetalle->getCodigoSoportePagoFk());                                
                    if($arSoportePago) {                        
                        $arProgramacionPagoDetalle->setDiasTransporte($arSoportePago->getDiasTransporte());                                                                                
                        $arProgramacionPagoDetalle->setHorasDescanso($arSoportePago->getHorasDescanso());                                                                
                        $arProgramacionPagoDetalle->setHorasDiurnas($arSoportePago->getHorasDiurnas());                                                                
                        $arProgramacionPagoDetalle->setHorasNocturnas($arSoportePago->getHorasNocturnas());                                       
                        $arProgramacionPagoDetalle->setHorasFestivasDiurnas($arSoportePago->getHorasFestivasDiurnas());                                                                
                        $arProgramacionPagoDetalle->setHorasFestivasNocturnas($arSoportePago->getHorasFestivasNocturnas());                                                                
                        $arProgramacionPagoDetalle->setHorasExtrasOrdinariasDiurnas($arSoportePago->getHorasExtrasOrdinariasDiurnas());                                                                
                        $arProgramacionPagoDetalle->setHorasExtrasOrdinariasNocturnas($arSoportePago->getHorasExtrasOrdinariasNocturnas());                                                                
                        $arProgramacionPagoDetalle->setHorasExtrasFestivasDiurnas($arSoportePago->getHorasExtrasFestivasDiurnas());                                                                                
                        $arProgramacionPagoDetalle->setHorasExtrasFestivasNocturnas($arSoportePago->getHorasExtrasFestivasNocturnas());                                                                 
                        $arProgramacionPagoDetalle->setHorasRecargoFestivoDiurno($arSoportePago->getHorasRecargoFestivoDiurno());                                                                  
                        $arProgramacionPagoDetalle->setHorasRecargoFestivoNocturno($arSoportePago->getHorasRecargoFestivoNocturno());                                                        
                        $em->persist($arProgramacionPagoDetalle);
                        $em->flush();
                    }                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_programacion_pago_resumen_turno_ver', array('codigoProgramacionPagoDetalle' => $codigoProgramacionPagoDetalle)));
            }                         
            if($form->get('BtnEliminarPagoAdicional')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarValor');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        $em->remove($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_programacion_pago_resumen_turno_ver', array('codigoProgramacionPagoDetalle' => $codigoProgramacionPagoDetalle)));
                }
            }            
            if($form->get('BtnInactivarPagoAdicional')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarValor');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        if($arPagoAdicional->getEstadoInactivo() == 1) {
                            $arPagoAdicional->setEstadoInactivo(0);
                            $arPagoAdicional->setFechaUltimaEdicion(new \DateTime('now'));
                        } else {
                            $arPagoAdicional->setEstadoInactivo(1);
                            $arPagoAdicional->setFechaUltimaEdicion(new \DateTime('now'));
                        }
                        $em->persist($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_programacion_pago_resumen_turno_ver', array('codigoProgramacionPagoDetalle' => $codigoProgramacionPagoDetalle)));
                }
            }
            if($form->get('BtnActualizarPagoAdicional')->isClicked()) {
                $arUsuario = $this->get('security.context')->getToken()->getUser();
                $arrSeleccionados = $request->request->get('ChkSeleccionarValor');
                if(count($arrSeleccionados) > 0) {
                    $arrControles = $request->request->All();
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        if($arrControles['TxtValor'.$codigoPagoAdicional] != "") {
                            $valor = $arrControles['TxtValor'.$codigoPagoAdicional];
                            $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                            $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);                            
                            $arPagoAdicional->setEstadoInactivo(0);
                            $arPagoAdicional->setFechaUltimaEdicion(new \DateTime('now'));
                            $arPagoAdicional->setValor($valor);
                            $arPagoAdicional->setCodigoUsuarioUltimaEdicion($arUsuario->getUserName());            
                            $em->persist($arPagoAdicional);
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_programacion_pago_resumen_turno_ver', array('codigoProgramacionPagoDetalle' => $codigoProgramacionPagoDetalle)));
                }
            }            
            if($form->get('BtnMarcar')->isClicked()) {
                $arProgramacionPagoDetalle->setMarca(1);
                $em->persist($arProgramacionPagoDetalle);
                $em->flush();
            }
        }
        $arSoportePago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arSoportePagoProgramacion = new \Brasa\TurnoBundle\Entity\TurSoportePagoProgramacion();
        $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($codigoProgramacionPagoDetalle);
        if($arProgramacionPagoDetalle->getCodigoSoportePagoFk()) {
            $arSoportePago =  $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arProgramacionPagoDetalle->getCodigoSoportePagoFk());                                
            if($arSoportePago) {
                $strAnio = $arSoportePago->getFechaDesde()->format('Y');
                $strMes = $arSoportePago->getFechaDesde()->format('m');        
                $arProgramacionDetalle =  $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('anio' => $strAnio, 'mes' => $strMes, 'codigoRecursoFk' => $arSoportePago->getCodigoRecursoFk()));                                                    
                $arSoportePagoProgramacion =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoProgramacion')->findBy(array('codigoSoportePagoFk' => $arProgramacionPagoDetalle->getCodigoSoportePagoFk()));                                                                    
            }
        }        
 
        $arrDiaSemana = $objFunciones->diasMes($arProgramacionPagoDetalle->getFechaDesde(), $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arProgramacionPagoDetalle->getFechaDesde()->format('Y-m-').'01', $arProgramacionPagoDetalle->getFechaDesde()->format('Y-m-').'31'));        
        if($arProgramacionPagoDetalle->getProgramacionPagoRel()->getCodigoPagoTipoFk() == 1) {
            $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->programacionPagoGeneralDql($arProgramacionPagoDetalle->getCodigoEmpleadoFk(), $arProgramacionPagoDetalle->getFechaDesde()->format('Y/m/d'), $arProgramacionPagoDetalle->getFechaHasta()->format('Y/m/d')));
            $arPagosAdicionales = $paginator->paginate($query, $request->query->get('page', 1), 20);                    
        } else {
            $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        }

        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->listaDql("", $codigoProgramacionPagoDetalle);                
        $arPagoDetalles = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 50);        
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ProgramacionesPago:verResumenTurno.html.twig', array(                        
            'arProgramacionPagoDetalle' => $arProgramacionPagoDetalle,
            'arProgramacionDetalle' => $arProgramacionDetalle,  
            'arPagoDetalles' => $arPagoDetalles,
            'arSoportePago' => $arSoportePago,
            'arPago' => $arPago,
            'arrDiaSemana' => $arrDiaSemana,
            'arPagosAdicionales' => $arPagosAdicionales,
            'arSoportePagoProgramacion' => $arSoportePagoProgramacion,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/movimiento/programacion/pago/detalle/parametros/prima/{codigoProgramacionPagoDetalle}", name="brs_rhu_movimiento_programacion_pago_detalle_parametros_prima")
     */    
    public function parametrosPrimaAction($codigoProgramacionPagoDetalle) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($codigoProgramacionPagoDetalle);        
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_movimiento_programacion_pago_detalle_parametros_prima', array('codigoProgramacionPagoDetalle' => $codigoProgramacionPagoDetalle)))            
            ->add('porcentajeIbp', 'number', array('data' => $arProgramacionPagoDetalle->getPorcentajeIbp() ,'required' => false))      
            ->add('vrSalarioPrimaPropuesto', 'number', array('data' => $arProgramacionPagoDetalle->getVrSalarioPrimaPropuesto() ,'required' => false))                                      
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $porcentajeIbp = $form->get('porcentajeIbp')->getData();            
            $vrSalarioPrimaPropuesto = $form->get('vrSalarioPrimaPropuesto')->getData();            
            $arProgramacionPagoDetalle->setPorcentajeIbp($porcentajeIbp);
            $arProgramacionPagoDetalle->setVrSalarioPrimaPropuesto($vrSalarioPrimaPropuesto);
            $em->persist($arProgramacionPagoDetalle);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_detalle_prima', array('codigoProgramacionPago' => $arProgramacionPagoDetalle->getCodigoProgramacionPagoFk())));
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ProgramacionesPago:parametrosPrima.html.twig', array(
            'arProgramacionPagoDetalle' => $arProgramacionPagoDetalle,
            'form' => $form->createView()           
        ));
    }     
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $arrayPropiedadesCentroCosto = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedadesCentroCosto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $arrayPropiedadesTipo = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoPagoTipo')) {
            $arrayPropiedadesTipo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoTipo", $session->get('filtroCodigoPagoTipo'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedadesCentroCosto)
            ->add('pagoTipoRel', 'entity', $arrayPropiedadesTipo)
            ->add('estadoGenerado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'GENERADO', '0' => 'SIN GENERAR'), 'data' => $session->get('filtroEstadoGenerado')))
            ->add('estadoPagado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'PAGADOS', '0' => 'SIN PAGAR'), 'data' => $session->get('filtroEstadoPagado')))
            ->add('fechaHasta', 'date', array('required' => true, 'widget' => 'single_text'))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminarPago', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($arProgramacionPago) {
        $arrBotonGenerarEmpleados = array('label' => 'Cargar contratos', 'disabled' => false);
        $arrBotonDesbloquearSoportePagoTurnos = array('label' => 'Desbloquear soporte pago turnos', 'disabled' => false);
        $arrBotonEliminarEmpleados = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonEliminarTodoEmpleados = array('label' => 'Eliminar todo', 'disabled' => false);
        //$arrBotonActualizarDetalle = array('label' => 'Actualizar detalle', 'disabled' => false);
        if($arProgramacionPago->getEstadoGenerado() == 1) {            
            $arrBotonGenerarEmpleados['disabled'] = true;                                            
            $arrBotonEliminarTodoEmpleados['disabled'] = true;            
            
        }
        if($arProgramacionPago->getEstadoPagado() == 1) {            
            $arrBotonGenerarEmpleados['disabled'] = true;         
            $arrBotonEliminarEmpleados['disabled'] = true;                                    
            $arrBotonEliminarTodoEmpleados['disabled'] = true;
            //$arrBotonActualizarDetalle['disabled'] = true;            
            
        }        
        
        $form = $this->createFormBuilder()    
                   // ->add('BtnActualizarDetalle', 'submit', $arrBotonActualizarDetalle)
                    ->add('BtnDesbloquearSoportePagoTurnos', 'submit', $arrBotonDesbloquearSoportePagoTurnos)                            
                    ->add('BtnGenerarEmpleados', 'submit', $arrBotonGenerarEmpleados)                        
                    ->add('BtnEliminarEmpleados', 'submit', $arrBotonEliminarEmpleados)
                    ->add('BtnEliminarTodoEmpleados', 'submit', $arrBotonEliminarTodoEmpleados)                    
                    ->getForm();  
        return $form;
    }    
    
    private function formularioDetallePrima($arProgramacionPago) {
        $arrBotonGenerarEmpleados = array('label' => 'Cargar contratos', 'disabled' => false);        
        $arrBotonEliminarEmpleados = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonEliminarTodoEmpleados = array('label' => 'Eliminar todo', 'disabled' => false);
        //$arrBotonActualizarDetalle = array('label' => 'Actualizar detalle', 'disabled' => false);
        if($arProgramacionPago->getEstadoGenerado() == 1) {            
            $arrBotonGenerarEmpleados['disabled'] = true;                                            
            $arrBotonEliminarTodoEmpleados['disabled'] = true;            
            
        }
        if($arProgramacionPago->getEstadoPagado() == 1) {            
            $arrBotonGenerarEmpleados['disabled'] = true;         
            $arrBotonEliminarEmpleados['disabled'] = true;                                    
            $arrBotonEliminarTodoEmpleados['disabled'] = true;
            //$arrBotonActualizarDetalle['disabled'] = true;            
            
        }        
        
        $form = $this->createFormBuilder()                        
                    ->add('BtnGenerarEmpleados', 'submit', $arrBotonGenerarEmpleados)                        
                    ->add('BtnEliminarEmpleados', 'submit', $arrBotonEliminarEmpleados)
                    ->add('BtnEliminarTodoEmpleados', 'submit', $arrBotonEliminarTodoEmpleados)                    
                    ->getForm();  
        return $form;
    }        
    
    private function formularioVerReusmenTurno($arProgramacionPago) {                
        $arrBotonActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonActualizarHoras = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonActualizarHorasSoportePago = array('label' => 'Actualizar del soporte pago', 'disabled' => false);        
        
        $arrBotonEliminarPagoAdicional = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonInactivarPagoAdicional = array('label' => 'Inactivar', 'disabled' => false);
        $arrBotonActualizarPagoAdicional = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonMarcar = array('label' => 'Marcar', 'disabled' => false);
        if($arProgramacionPago->getEstadoPagado() == 1) {            
            $arrBotonActualizar['disabled'] = true;
            $arrBotonActualizarHoras['disabled'] = true;
            $arrBotonActualizarHorasSoportePago['disabled'] = true;            
            $arrBotonEliminarPagoAdicional['disabled'] = true;            
            $arrBotonInactivarPagoAdicional['disabled'] = true;
            $arrBotonActualizarPagoAdicional['disabled'] = true;
            $arrBotonMarcar['disabled'] = true;
        }        
        if($arProgramacionPago->getCodigoPagoTipoFk() == 2) {
            //$arrBotonActualizar['disabled'] = true;
            $arrBotonActualizarHoras['disabled'] = true;
            $arrBotonActualizarHorasSoportePago['disabled'] = true;            
            $arrBotonEliminarPagoAdicional['disabled'] = true;            
            $arrBotonInactivarPagoAdicional['disabled'] = true;
            $arrBotonActualizarPagoAdicional['disabled'] = true;
            $arrBotonMarcar['disabled'] = true;            
        }
        $form = $this->createFormBuilder()             
            ->add('BtnActualizar', 'submit', $arrBotonActualizar)            
            ->add('BtnActualizarHoras', 'submit', $arrBotonActualizarHoras)
            ->add('BtnActualizarHorasSoportePago', 'submit',$arrBotonActualizarHorasSoportePago)            
            ->add('BtnEliminarPagoAdicional', 'submit', $arrBotonEliminarPagoAdicional)
            ->add('BtnInactivarPagoAdicional', 'submit', $arrBotonInactivarPagoAdicional)
            ->add('BtnActualizarPagoAdicional', 'submit', $arrBotonActualizarPagoAdicional)
            ->add('BtnMarcar', 'submit', $arrBotonMarcar)
            ->getForm();
        return $form;
    }    
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->listaDQL(
                    "",
                    $session->get('filtroFechaHasta'),
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroEstadoGenerado'),
                    $session->get('filtroEstadoPagado'),
                    $session->get('filtroCodigoPagoTipo')
                    );
    }

    private function filtrarLista($form, Request $request) {
        $session = $this->get('session');
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroCodigoPagoTipo', $controles['pagoTipoRel']);
        $session->set('filtroEstadoGenerado', $form->get('estadoGenerado')->getData());
        $session->set('filtroEstadoPagado', $form->get('estadoPagado')->getData());
        if($form->get('fechaHasta')->getData()) {
            $session->set('filtroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
        } else {
            $session->set('filtroFechaHasta', "");
        }


    }

    private function generarExcel() {
        ob_clean();
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'CENTRO COSTO')
                    ->setCellValue('D1', 'PERIODO')
                    ->setCellValue('E1', 'DESDE')
                    ->setCellValue('F1', 'HASTA')
                    ->setCellValue('G1', 'DÍAS')
                    ->setCellValue('H1', 'EMPLEADOS')
                    ->setCellValue('I1', 'ESTADO GENERADO')
                    ->setCellValue('J1', 'ESTADO PAGADO')
                    ->setCellValue('K1', 'NETO');
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arProgramacionesPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionesPagos = $query->getResult();
        foreach ($arProgramacionesPagos as $arProgramacionPago) {
            if ($arProgramacionPago->getEstadoGenerado() == 1){
                $estadoGenerado = "SI";
            } else {
                $estadoGenerado = "NO";
            }
            if ($arProgramacionPago->getEstadoPagado() == 1){
                $estadoPagado = "SI";
            } else {
                $estadoPagado = "NO";
            }
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProgramacionPago->getCodigoProgramacionPagoPk())
                    ->setCellValue('B' . $i, $arProgramacionPago->getPagoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arProgramacionPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('D' . $i, $arProgramacionPago->getCentroCostoRel()->getPeriodoPagoRel()->getNombre())
                    ->setCellValue('E' . $i, $arProgramacionPago->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arProgramacionPago->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('G' . $i, $arProgramacionPago->getDias())
                    ->setCellValue('H' . $i, $arProgramacionPago->getNumeroEmpleados())
                    ->setCellValue('I' . $i, $estadoGenerado)
                    ->setCellValue('J' . $i, $estadoPagado)
                    ->setCellValue('K' . $i, $arProgramacionPago->getVrNeto());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('ProgramacionesPago');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ProgramacionesPago.xlsx"');
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

    private function generarExcelDetalle($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);        
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);       
            $objPHPExcel = new \PHPExcel();
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("EMPRESA")
                ->setLastModifiedBy("EMPRESA")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
            $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
            $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
            for($col = 'A'; $col !== 'M'; $col++) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                  
            }     
            for($col = 'F'; $col !== 'M'; $col++) {
                $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right'); 
                $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
            } 
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'CODIGO')
                        ->setCellValue('B1', 'DOCUMENTO')
                        ->setCellValue('C1', 'NOMBRE')
                        ->setCellValue('D1', 'BANCO')
                        ->setCellValue('E1', 'CUENTA')
                        ->setCellValue('F1', 'DESDE')
                        ->setCellValue('G1', 'HASTA')
                        ->setCellValue('H1', 'SALARIO')
                        ->setCellValue('I1', 'DEVENGADO')
                        ->setCellValue('J1', 'DEV_PAC')
                        ->setCellValue('K1', 'DEDUCCIONES')
                        ->setCellValue('L1', 'NETO');
            $i = 2;

            $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
            $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
            foreach ($arPagos as $arPago) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $arPago->getCodigoEmpleadoFk())
                        ->setCellValue('B' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                        ->setCellValue('C' . $i, $arPago->getEmpleadoRel()->getNombreCorto())                    
                        ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getCuenta())
                        ->setCellValue('F' . $i, $arPago->getFechaDesde()->format('Y/m/d'))
                        ->setCellValue('G' . $i, $arPago->getFechaHasta()->format('Y/m/d'))
                        ->setCellValue('H' . $i, $arPago->getVrSalarioEmpleado())
                        ->setCellValue('I' . $i, $arPago->getVrDevengado())
                        ->setCellValue('J' . $i, $arPago->getContratoRel()->getVrDevengadoPactado())
                        ->setCellValue('K' . $i, $arPago->getVrDeducciones())
                        ->setCellValue('L' . $i, $arPago->getVrNeto());
                if($arPago->getEmpleadoRel()->getCodigoBancoFk()) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getBancoRel()->getNombre());
                }
                $i++;
            }
            $objPHPExcel->getActiveSheet()->setTitle('Pagos');
                       
            
            $objPHPExcel->createSheet(1)->setTitle('PagosDetalle')
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'DOCUMENTO')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'COD')
                    ->setCellValue('E1', 'CONCEPTO')
                    ->setCellValue('F1', 'HORAS')
                    ->setCellValue('G1', 'DEVENGADO')
                    ->setCellValue('H1', 'DEDUCCION');
            
            $objPHPExcel->setActiveSheetIndex(1); 
            $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
            $objPHPExcel->getActiveSheet(1)->getStyle('1')->getFont()->setBold(true);     
            for($col = 'A'; $col !== 'I'; $col++) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                  
            }            
            for($col = 'F'; $col !== 'I'; $col++) { 
                $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');                 
                $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
            }             
            
            $i = 2;
            $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
            $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->pagosDetallesProgramacionPago($codigoProgramacionPago);            
            foreach ($arPagoDetalles as $arPagoDetalle) {
                $objPHPExcel->setActiveSheetIndex(1)
                        ->setCellValue('A' . $i, $arPagoDetalle->getCodigoPagoDetallePk())
                        ->setCellValue('B' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNumeroIdentificacion())
                        ->setCellValue('C' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNombreCorto())
                        ->setCellValue('D' . $i, $arPagoDetalle->getCodigoPagoConceptoFk())
                        ->setCellValue('E' . $i, $arPagoDetalle->getPagoConceptoRel()->getNombre())
                        ->setCellValue('F' . $i, $arPagoDetalle->getNumeroHoras());
                if($arPagoDetalle->getOperacion() == 1) {
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue('G' . $i, $arPagoDetalle->getVrPago());
                }
                if($arPagoDetalle->getOperacion() == -1) {
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue('H' . $i, $arPagoDetalle->getVrPago());
                }                
                $i++;
            }             
            
            //Incapacidades
            $objPHPExcel->createSheet()->setTitle('Incapacidades')
                    ->setCellValue('A1', 'TIPO')
                    ->setCellValue('B1', 'DESDE')
                    ->setCellValue('C1', 'HASTA')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'DIAS');
            $objPHPExcel->setActiveSheetIndex(2);             
            $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
            $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);                  
            
            $i = 2;
            $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
            $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->periodo($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta(), "", $arProgramacionPago->getCodigoCentroCostoFk());                       
            foreach ($arIncapacidades as $arIncapacidad) {
                $objPHPExcel->setActiveSheetIndex(2)
                        ->setCellValue('A' . $i, $arIncapacidad->getIncapacidadTipoRel()->getNombre())
                        ->setCellValue('B' . $i, $arIncapacidad->getFechaDesde()->format('Y/m/d'))
                        ->setCellValue('C' . $i, $arIncapacidad->getFechaHasta()->format('Y/m/d'))
                        ->setCellValue('D' . $i, $arIncapacidad->getEmpleadoRel()->getNombreCorto())
                        ->setCellValue('E' . $i, $arIncapacidad->getCantidad());
                $i++;
            }              
            
            $objPHPExcel->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Pagos.xlsx"');
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
}

