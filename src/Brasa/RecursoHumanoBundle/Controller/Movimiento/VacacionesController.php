<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuVacacionType;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class VacacionesController extends Controller
{
    var $strSqlLista = "";

    /**
     * @Route("/rhu/movimiento/vacacion/", name="brs_rhu_movimiento_vacacion")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 14, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();

        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    try{
                        foreach ($arrSeleccionados AS $codigoVacacion) {
                            $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                            $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
                            if ($arVacaciones->getEstadoAutorizado() == 1 ) {
                                $objMensaje->Mensaje("error", "No se puede eliminar el registro, esta autorizado!", $this);
                            }
                            else {
                                if ($arVacaciones->getEstadoPagoGenerado() == 1 ) {
                                    $objMensaje->Mensaje("error", "No se puede eliminar el registro, ya fue pagada!", $this);
                                } else {
                                    $em->remove($arVacaciones);
                                    $em->flush();
                                }

                            }
                        }
                    } catch (ForeignKeyConstraintViolationException $e) {
                        $objMensaje->Mensaje('error', 'No se puede eliminar el registro, tiene detalles relacionados', $this);
                      }
                }
                $this->filtrarLista($form);
                $this->listar();
            }

            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            /*if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoCredito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCredito();
                $objFormatoCredito->Generar($this, $this->strSqlLista);
            }*/
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arVacaciones = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Vacaciones:lista.html.twig', array(
            'arVacaciones' => $arVacaciones,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/movimiento/vacacion/nuevo/{codigoVacacion}", name="brs_rhu_movimiento_vacacion_nuevo")
     */
    public function nuevoAction($codigoVacacion = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        if($codigoVacacion != 0) {
            $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        } else {
            $arVacacion->setFecha(new \DateTime('now'));
            $arVacacion->setFechaDesdeDisfrute(new \DateTime('now'));
            $arVacacion->setFechaHastaDisfrute(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuVacacionType, $arVacacion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arVacacion = $form->getData();
            if($form->get('guardar')->isClicked()) {
                if($arrControles['form_txtNumeroIdentificacion'] != '') {
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                    if(count($arEmpleado) > 0) {
                        $arVacacion->setEmpleadoRel($arEmpleado);
                        if($arEmpleado->getCodigoContratoActivoFk() != '') {
                            if ($form->get('fechaDesdeDisfrute')->getData() >  $form->get('fechaHastaDisfrute')->getData()){
                                $objMensaje->Mensaje("error", "La fecha desde no debe ser mayor a la fecha hasta", $this);
                            } else {
                                if ($form->get('diasDisfrutados')->getData() == 0 && $form->get('diasPagados')->getData() == 0){
                                    $objMensaje->Mensaje("error", "Los dias pagados o los dias disfrutados, no pueden estar en ceros", $this);
                                } else {
                                    $arVacacion->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                                    $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoActivoFk());
                                    $arVacacion->setContratoRel($arContrato);
                                    $fechaDesdePeriodo = $arContrato->getFechaUltimoPagoVacaciones();
                                    if ($fechaDesdePeriodo == null){
                                        $fechaDesdePeriodo = $arContrato->getFechaDesde();
                                    }
                                    $fechaHastaPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestacionesHasta(360, $fechaDesdePeriodo);
                                    $intDias = ($arVacacion->getDiasDisfrutados() + $arVacacion->getDiasPagados()) * 24;

                                    $strFechaDesde = $fechaDesdePeriodo->format('Y-m-d');
                                    $strFechaDesde = strtotime ( '+1 day' , strtotime ( $strFechaDesde ) ) ;
                                    $strFechaDesde = date ( 'Y-m-d' , $strFechaDesde );
                                    $fechaDesdePeriodo = date_create($strFechaDesde);

                                    $fechaHastaPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestacionesHasta($intDias+1, $fechaDesdePeriodo);
                                    $arVacacion->setFechaDesdePeriodo($fechaDesdePeriodo);
                                    $arVacacion->setFechaHastaPeriodo($fechaHastaPeriodo);
                                    $intDiasDevolver = $arVacacion->getDiasPagados();
                                    if($arVacacion->getDiasDisfrutados() > 0){
                                        $intDias = $arVacacion->getFechaDesdeDisfrute()->diff($arVacacion->getFechaHastaDisfrute());
                                        $intDias = $intDias->format('%a');
                                        $intDiasDevolver += $intDias + 1;
                                    }
                                    $arVacacion->setDiasVacaciones($intDiasDevolver);
                                    if($codigoVacacion == 0) {
                                        $arVacacion->setCodigoUsuario($arUsuario->getUserName());
                                    }
                                    $intDiasDevolver = 0;
                                    if($arVacacion->getDiasDisfrutados() > 0) {
                                        $intDias = $arVacacion->getFechaDesdeDisfrute()->diff($arVacacion->getFechaHastaDisfrute());
                                        $intDias = $intDias->format('%a');
                                        $intDiasDevolver += $intDias + 1;
                                    }
                                    $arVacacion->setDiasDisfrutadosReales($intDiasDevolver);

                                    $em->persist($arVacacion);

                                    //Calcular deducciones credito
                                    if($codigoVacacion == 0) {
                                        $floVrDeducciones = 0;
                                        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                                        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->pendientes($arEmpleado->getCodigoEmpleadoPk());
                                        foreach ($arCreditos as $arCredito) {
                                            $arVacacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionAdicional();
                                            $arVacacionAdicional->setCreditoRel($arCredito);
                                            $arVacacionAdicional->setVacacionRel($arVacacion);
                                            $arVacacionAdicional->setVrDeduccion($arCredito->getVrCuota());
                                            $arVacacionAdicional->setPagoConceptoRel($arCredito->getCreditoTipoRel()->getPagoConceptoRel());
                                            $em->persist($arVacacionAdicional);
                                            $floVrDeducciones += $arCredito->getVrCuota();
                                        }
                                    }

                                    $em->flush();
                                    $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($arVacacion->getCodigoVacacionPk());
                                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion'));
                                }
                            }
                        } else {
                            $objMensaje->Mensaje("error", "El empleado no tiene contrato activo", $this);
                        }
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no existe", $this);
                    }
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Vacaciones:nuevo.html.twig', array(
            'arVacacion' => $arVacacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/vacacion/detalle/{codigoVacacion}", name="brs_rhu_movimiento_vacacion_detalle")
     */
    public function detalleAction($codigoVacacion) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        $form = $this->formularioDetalle($arVacacion);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if($arVacacion->getEstadoAutorizado() == 0) {
                    $arVacacion->setEstadoAutorizado(1);
                    $em->persist($arVacacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if($arVacacion->getEstadoAutorizado() == 1) {
                    $arVacacion->setEstadoAutorizado(0);
                    $em->persist($arVacacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
                }
            }
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoDetalleVacaciones = new \Brasa\RecursoHumanoBundle\Formatos\FormatoVacaciones();
                $objFormatoDetalleVacaciones->Generar($this, $codigoVacacion);
            }
            if($form->get('BtnLiquidar')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($codigoVacacion);
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
            }

            if($form->get('BtnGenerarPago')->isClicked()) {
                if($arVacacion->getEstadoAutorizado() == 1) {
                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContrato =  $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arVacacion->getCodigoContratoFk());
                    $validar = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->pagar($codigoVacacion);
                    if ($validar == ''){
                        $arContrato->setFechaUltimoPagoVacaciones($arVacacion->getFechaHastaPeriodo());
                        $arVacacion->setEstadoPagoGenerado(1);
                        $em->persist($arContrato);
                        $em->persist($arVacacion);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
                    } else {
                        $objMensaje->Mensaje("error", "Una de las deducciones de creditos es mayor al saldo pendiente, por favor verifique los creditos del empleado", $this);
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
                    }
                } else {
                    $objMensaje->Mensaje("error", "No esta autorizado, no se puede generar pago", $this);
                }
            }

            if($form->get('BtnEliminarAdicional')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arVacacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionAdicional();
                        $arVacacionAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionAdicional')->find($codigo);
                        $em->remove($arVacacionAdicional);
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($codigoVacacion);
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
            }            
            if($form->get('BtnEliminarBonificacion')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarBonificacion');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoVacacionBonificacion) {
                        $arVacacionBonificacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionBonificacion();
                        $arVacacionBonificacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionBonificacion')->find($codigoVacacionBonificacion);
                        $em->remove($arVacacionBonificacion);
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($codigoVacacion);
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
            }
            

        }

        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionAdicional')->listaDql($codigoVacacion);
        $arVacacionAdicionales = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Vacaciones:detalle.html.twig', array(
                    'arVacaciones' => $arVacacion,
                    'arVacacionAdicionales' => $arVacacionAdicionales,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/movimiento/vacacion/detalle/credito/{codigoVacacion}", name="brs_rhu_movimiento_vacacion_detalle_credito")
     */
    public function detalleCreditoAction($codigoVacacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->pendientes($arVacacion->getCodigoEmpleadoFk());
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrControles = $request->request->All();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $floVrDeducciones = 0;
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCredito) {
                        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
                        $valor = 0;
                        if($arrControles['TxtValor'.$codigoCredito] != '') {
                            $valor = $arrControles['TxtValor'.$codigoCredito];
                        }
                        $arVacacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionAdicional();
                        $arVacacionAdicional->setCreditoRel($arCredito);
                        $arVacacionAdicional->setVacacionRel($arVacacion);
                        $arVacacionAdicional->setVrDeduccion($valor);
                        $arVacacionAdicional->setPagoConceptoRel($arCredito->getCreditoTipoRel()->getPagoConceptoRel());
                        $em->persist($arVacacionAdicional);
                        $floVrDeducciones += $valor;
                    }
                    $em->flush();
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($arVacacion->getCodigoVacacionPk());
                }

            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Vacaciones:detallenuevo.html.twig', array(
            'arCreditos' => $arCreditos,
            'arVacacion' => $arVacacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/vacacion/detalle/descuento/{codigoVacacion}", name="brs_rhu_movimiento_vacacion_detalle_descuento")
     */
    public function detalleDescuentoAction($codigoVacacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        $form = $this->createFormBuilder()
            ->add('pagoConceptoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', 2)
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('TxtValor', 'number', array('required' => true))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
                $arVacacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionAdicional();
                $arVacacionAdicional->setVacacionRel($arVacacion);
                $arVacacionAdicional->setPagoConceptoRel($arPagoConcepto);
                $arVacacionAdicional->setVrDeduccion($form->get('TxtValor')->getData());
                $em->persist($arVacacionAdicional);
                $em->flush();
                $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($arVacacion->getCodigoVacacionPk());
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Vacaciones:detalleNuevoDescuento.html.twig', array(
            'arVacacion' => $arVacacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/vacacion/detalle/bonificacion/{codigoVacacion}", name="brs_rhu_movimiento_vacacion_detalle_bonificacion")
     */
    public function detalleBonificacionAction($codigoVacacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        $arPagoConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        $arPagoConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('tipoAdicional' => 1));
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrControles = $request->request->All();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($codigo);
                        $valor = 0;
                        if($arrControles['TxtValor'.$codigo] != '') {
                            $valor = $arrControles['TxtValor'.$codigo];
                        }
                        $arVacacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionAdicional();
                        $arVacacionAdicional->setPagoConceptoRel($arPagoConcepto);
                        $arVacacionAdicional->setVacacionRel($arVacacion);
                        $arVacacionAdicional->setVrBonificacion($valor);
                        $em->persist($arVacacionAdicional);
                    }
                    $em->flush();
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($arVacacion->getCodigoVacacionPk());
                }

            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Vacaciones:detalleBonificacionNuevo.html.twig', array(
            'arPagoConceptos' => $arPagoConceptos,
            'arVacacion' => $arVacacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/vacacion/modificar/{codigoVacacion}", name="brs_rhu_movimiento_vacacion_modificar")
     */
    public function modificarInformacionAction($codigoVacacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),112)){
            $objMensaje->Mensaje("error", "No tiene permisos para modificar la vacacion, comuniquese con el administrador", $this);
        }
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        $form = $this->createFormBuilder()
            //->setAction($this->generateUrl('brs_rhu_movimiento_vacacion_modificar', array('codigoVacacion' => $codigoVacacion)))
            ->add('fechaDesdeDisfrute', 'date', array('label'  => 'Fecha desde', 'data' => $arVacacion->getFechaDesdeDisfrute()))
            ->add('fechaHastaDisfrute', 'date', array('label'  => 'Fecha hasta', 'data' => $arVacacion->getFechaHastaDisfrute()))
            ->add('vrSalud', 'number', array('data' =>$arVacacion->getVrSalud() ,'required' => false))
            ->add('vrPension', 'number', array('data' =>$arVacacion->getVrPension() ,'required' => false))            
            ->add('vrVacacion', 'number', array('data' =>$arVacacion->getVrVacacion() ,'required' => false))                    
            ->add('diasDisfrute', 'number', array('data' =>$arVacacion->getDiasDisfrutados() ,'required' => false))        
            ->add('diasPagados', 'number', array('data' =>$arVacacion->getDiasPagados() ,'required' => false))            
            ->add('vrSalarioPromedio', 'number', array('data' =>$arVacacion->getVrSalarioPromedio() ,'required' => false))            
            ->add('totalVacaciones', 'number', array('data' =>$arVacacion->getVrVacacionBruto() ,'required' => false))                
            ->add('vrRecargoNocturno', 'number', array('data' =>$arVacacion->getVrPromedioRecargoNocturno() ,'required' => false))                            
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),112)){
                $objMensaje->Mensaje("error", "No tiene permisos para modificar la vacacion, comuniquese con el administrador", $this);
            } else {
                $fechaDesdeDisfrute = $form->get('fechaDesdeDisfrute')->getData();
                $fechaHastaDisfrute = $form->get('fechaHastaDisfrute')->getData();
                $vrSalud = $form->get('vrSalud')->getData();
                $vrPension = $form->get('vrPension')->getData();            
                $vrVacacion = $form->get('vrVacacion')->getData();            
                $diasDisfrute = $form->get('diasDisfrute')->getData();
                $diasPagados = $form->get('diasPagados')->getData();
                $vrSalarioPromedio = $form->get('vrSalarioPromedio')->getData();
                $totalVacaciones = $form->get('totalVacaciones')->getData();
                $vrRecargoNocuturno = $form->get('vrRecargoNocturno')->getData();

                $arVacacion->setFechaDesdeDisfrute($fechaDesdeDisfrute);
                $arVacacion->setFechaHastaDisfrute($fechaHastaDisfrute);
                $arVacacion->setVrSalud($vrSalud);
                $arVacacion->setVrPension($vrPension);
                $arVacacion->setVrVacacion($vrVacacion);
                $arVacacion->setDiasDisfrutados($diasDisfrute);
                $arVacacion->setDiasPagados($diasPagados);
                $arVacacion->setVrSalarioPromedio($vrSalarioPromedio);
                $arVacacion->setVrVacacionBruto($totalVacaciones);
                $arVacacion->setVrPromedioRecargoNocturno($vrRecargoNocuturno);

                $em->persist($arVacacion);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Vacaciones:modificar.html.twig', array(
            'arVacacion' => $arVacacion,
            'form' => $form->createView()
        ));
    }

    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->listaVacacionesDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroPagado'),
                    $session->get('filtroAutorizado')
                    );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreEmpleado = "";
        if($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if($arEmpleado) {
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
                $session->set('filtroRhuCodigoEmpleado', $arEmpleado->getCodigoEmpleadoPk());
            }  else {
                $session->set('filtroIdentificacion', null);
                $session->set('filtroRhuCodigoEmpleado', null);
            }
        } else {
            $session->set('filtroRhuCodigoEmpleado', null);
        }
        $arrayPropiedades = array(
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
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }

        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('txtNumeroIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('txtNombreCorto', 'text', array('label'  => 'Nombre','data' => $strNombreEmpleado))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('estadoPagado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'),'data' => $session->get('filtroPagado')))
            ->add('estadoAutorizado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'),'data' => $session->get('filtroAutorizado')))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroPagado', $controles['estadoPagado']);
        $session->set('filtroAutorizado', $controles['estadoAutorizado']);
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
    }

    private function formularioDetalle($arVacacion) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonGenerarPago = array('label' => 'Generar pago', 'disabled' => false);
        $arrBotonLiquidar = array('label' => 'Liquidar', 'disabled' => false);
        $arrBotonEliminarAdicional = array('label'  => 'Eliminar', 'disabled' => false);

        if($arVacacion->getEstadoAutorizado() == 1) {
            $arrBotonLiquidar['disabled'] = true;
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonEliminarAdicional['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonGenerarPago['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
        }
        if($arVacacion->getEstadoPagoGenerado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonGenerarPago['disabled'] = true;
            $arrBotonLiquidar['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnGenerarPago', 'submit', $arrBotonGenerarPago)
                    ->add('BtnLiquidar', 'submit', $arrBotonLiquidar)
                    ->add('BtnEliminarAdicional', 'submit', $arrBotonEliminarAdicional)
                    ->getForm();
        return $form;
    }

    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        ob_clean();
        $session = $this->getRequest()->getSession();
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
                for($col = 'A'; $col !== 'S'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                }
                for($col = 'H'; $col !== 'S'; $col++) {
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
                }

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CODIGO')
                            ->setCellValue('B1', 'FECHA')
                            ->setCellValue('C1', 'GRUPO PAGO')
                            ->setCellValue('D1', 'DESDE')
                            ->setCellValue('E1', 'HASTA')
                            ->setCellValue('F1', 'DOCUMENTO')
                            ->setCellValue('G1', 'EMPLEADO')
                            ->setCellValue('H1', 'D.DIS')
                            ->setCellValue('I1', 'D.DIS.REALES')
                            ->setCellValue('J1', 'D.PAG')
                            ->setCellValue('K1', 'DIAS')
                            ->setCellValue('L1', 'VR_VACACIONES')
                            ->setCellValue('M1', 'VR_SALUD')
                            ->setCellValue('N1', 'VR_PENSION')
                            ->setCellValue('O1', 'VR_DEDUCCIONES')                            
                            ->setCellValue('P1', 'VR_BONIFICACIONES')
                            ->setCellValue('Q1', 'VR_PAGAR')
                            ->setCellValue('R1', 'PAGADO');

                $i = 2;
                $query = $em->createQuery($this->strSqlLista);
                $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                $arVacaciones = $query->getResult();

                foreach ($arVacaciones as $arVacacion) {
                    if ($arVacacion->getEstadoPagado() == 1) {
                        $Estado = "SI";
                    } else {
                        $Estado = "NO";
                    }

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arVacacion->getCodigoVacacionPk())
                            ->setCellValue('B' . $i, $arVacacion->getFecha()->format('Y/m/d'))
                            ->setCellValue('C' . $i, $arVacacion->getCentroCostoRel()->getNombre())
                            ->setCellValue('D' . $i, $arVacacion->getFechaDesdeDisfrute()->format('Y/m/d'))
                            ->setCellValue('E' . $i, $arVacacion->getFechaHastaDisfrute()->format('Y/m/d'))
                            ->setCellValue('F' . $i, $arVacacion->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('G' . $i, $arVacacion->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('H' . $i, $arVacacion->getDiasDisfrutados())
                            ->setCellValue('I' . $i, $arVacacion->getDiasDisfrutadosReales())
                            ->setCellValue('J' . $i, $arVacacion->getDiasPagados())
                            ->setCellValue('K' . $i, $arVacacion->getDiasVacaciones())
                            ->setCellValue('L' . $i, round($arVacacion->getVrVacacionBruto()))
                            ->setCellValue('M' . $i, round($arVacacion->getVrPension()))
                            ->setCellValue('N' . $i, round($arVacacion->getVrSalud()))
                            ->setCellValue('O' . $i, round($arVacacion->getVrDeduccion()))
                            ->setCellValue('P' . $i, round($arVacacion->getVrBonificacion()))
                            ->setCellValue('Q' . $i, round($arVacacion->getVrVacacion()))
                            ->setCellValue('R' . $i, $Estado);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Vacaciones');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Vacaciones.xlsx"');
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
