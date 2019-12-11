<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCapacitacionType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCapacitacionDetalleType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCapacitacionNotaType;
use Doctrine\ORM\EntityRepository;
class CapacitacionesController extends Controller
{
    var $strDqlLista = "";
    var $strDqlListaNuevoDetalleEmpleado = "";

    /**
     * @Route("/rhu/capacitacion/lista", name="brs_rhu_capacitacion_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 21, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCapacitacion) {
                        $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);
                        if ($arCapacitacion->getEstado() == 0){
                            $arCapacitacionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->findBy(array('codigoCapacitacionFk' => $codigoCapacitacion));
                            if ($arCapacitacionDetalle != null){
                                $objMensaje->Mensaje("error", "No se puede eliminar la capacitación " . $codigoCapacitacion ." tiene detalles", $this);
                            } else {
                                $em->remove($arCapacitacion);
                                $em->flush();
                            }
                        } else {
                            $objMensaje->Mensaje("error", "Esta cerrada la capacitación", $this);
                        }
                    }
                }
            }

        }

        $arCapacitaciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Capacitaciones:lista.html.twig', array(
            'arCapacitaciones' => $arCapacitaciones,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/capacitacion/detalle/{codigoCapacitacion}", name="brs_rhu_capacitacion_detalle")
     */
    public function detalleAction($codigoCapacitacion) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCapacitacion = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion();
        $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);
        $form = $this->formularioDetalle($arCapacitacion);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                if ($arCapacitacion->getEstadoAutorizado() == 1){
                    $objFormato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCapacitacion();
                    $objFormato->Generar($this, $codigoCapacitacion);
                }
            }
            if($form->get('BtnImprimirNotas')->isClicked()) {
                if ($arCapacitacion->getEstadoAutorizado() == 1){
                    $objFormato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCapacitacionNotas();
                    $objFormato->Generar($this, $codigoCapacitacion);
                }
            }
            if($form->get('BtnAutorizar')->isClicked()) {
                if ($arCapacitacion->getEstadoAutorizado() == 0){
                    $arCapacitacionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->findBy(array('codigoCapacitacionFk' => $codigoCapacitacion));
                    if ($arCapacitacionDetalle != null){
                        $arCapacitacion->setEstadoAutorizado(1);
                        $em->persist($arCapacitacion);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_capacitacion_detalle', array('codigoCapacitacion' => $codigoCapacitacion)));
                    } else {
                        $objMensaje->Mensaje("error", "La capacitación no tiene detalles, no se puede autorizar", $this);
                    }

                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if ($arCapacitacion->getEstadoAutorizado() == 1){
                    $arCapacitacion->setEstadoAutorizado(0);
                    $em->persist($arCapacitacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_capacitacion_detalle', array('codigoCapacitacion' => $codigoCapacitacion)));
                }
            }
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                $contador = 0;
                if ($arCapacitacion->getEstadoAutorizado() == 0){
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados as $codigoCapacitacionDetalle) {
                            $arCapacitacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
                            $arCapacitacionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->find($codigoCapacitacionDetalle);
                            if ($arCapacitacionDetalle->getAsistencia() == 1){
                                $contador ++;
                            }
                            $em->remove($arCapacitacionDetalle);
                        }
                        $arCapacitacion = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion();
                        $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);
                        $arCapacitacion->setNumeroPersonasAsistieron($arCapacitacion->getNumeroPersonasAsistieron() - $contador);
                        $em->persist($arCapacitacion);
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_capacitacion_detalle', array('codigoCapacitacion' => $codigoCapacitacion)));
                }
            }
            if($form->get('BtnEliminarNota')->isClicked()) {
                if ($arCapacitacion->getEstadoAutorizado() == 0){
                    $arrSeleccionados = $request->request->get('ChkSeleccionarNota');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados as $codigoCapacitacionNota) {
                            $arCapacitacionNota = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionNota();
                            $arCapacitacionNota = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionNota')->find($codigoCapacitacionNota);
                            $em->remove($arCapacitacionNota);
                        }
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_capacitacion_detalle', array('codigoCapacitacion' => $codigoCapacitacion)));
                }
            }
            if($form->get('BtnAsistio')->isClicked()) {
                $contador = 0;
                if ($arCapacitacion->getEstadoAutorizado() == 0){
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados as $codigoCapacitacionDetalle) {
                            $arCapacitacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
                            $arCapacitacionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->find($codigoCapacitacionDetalle);
                            $arCapacitacionDetalle->setAsistencia(1);
                            $em->persist($arCapacitacion);
                        }
                        $em->flush();
                        $arCapacitacionDetalleAsistencia = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
                        $arCapacitacionDetalleAsistencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->findBy(array('codigoCapacitacionFk' => $codigoCapacitacion, 'asistencia' => 1));
                        $arCapacitacion = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion();
                        $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);
                        $arCapacitacion->setNumeroPersonasAsistieron(count($arCapacitacionDetalleAsistencia));
                        $em->persist($arCapacitacion);
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_capacitacion_detalle', array('codigoCapacitacion' => $codigoCapacitacion)));
                }
            }
            if($form->get('BtnNoAsistio')->isClicked()) {
                $contador = 0;
                if ($arCapacitacion->getEstadoAutorizado() == 0){
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados as $codigoCapacitacionDetalle) {
                            $arCapacitacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
                            $arCapacitacionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->find($codigoCapacitacionDetalle);
                            $arCapacitacionDetalle->setAsistencia(0);
                            $em->persist($arCapacitacion);
                        }
                        $em->flush();
                        $arCapacitacionDetalleAsistencia = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
                        $arCapacitacionDetalleAsistencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->findBy(array('codigoCapacitacionFk' => $codigoCapacitacion, 'asistencia' => 1));
                        $arCapacitacion = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion();
                        $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);
                        $arCapacitacion->setNumeroPersonasAsistieron(count($arCapacitacionDetalleAsistencia));
                        $em->persist($arCapacitacion);
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_capacitacion_detalle', array('codigoCapacitacion' => $codigoCapacitacion)));
                }
            }
            /*if($form->get('BtnActualizar')->isClicked()) {
                if ($arCapacitacion->getEstadoAutorizado() == 0){
                    $arrControles = $request->request->All();
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        $arCapacitacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
                        $arCapacitacionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->find($intCodigo);
                        $arCapacitacionDetalle->setAsistencia($arrControles['cboAsistencia'.$intCodigo]);
                        $em->persist($arCapacitacionDetalle);
                    }
                    $em->flush();
                    $arCapacitacionDetalleAsistencia = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
                    $arCapacitacionDetalleAsistencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->findBy(array('codigoCapacitacionFk' => $codigoCapacitacion, 'asistencia' => 1));
                    $arCapacitacion = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion();
                    $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);
                    $arCapacitacion->setNumeroPersonasAsistieron(count($arCapacitacionDetalleAsistencia));
                    $em->persist($arCapacitacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_capacitacion_detalle', array('codigoCapacitacion' => $codigoCapacitacion)));
                }
            }*/
            if ($form->get('BtnActualizarDetalle')->isClicked()) {
                if ($arCapacitacion->getEstadoAutorizado() == 0){
                    $arrControles = $request->request->All();
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        if($arrControles['TxtEvaluacion'.$intCodigo] != "") {
                            $arCapacitacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
                            $arCapacitacionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->find($intCodigo);
                            $evaluacion = $arrControles['TxtEvaluacion'.$intCodigo];
                            $arCapacitacionDetalle->setEvaluacion($evaluacion);
                            $em->persist($arCapacitacionDetalle);
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_capacitacion_detalle', array('codigoCapacitacion' => $codigoCapacitacion)));
                }
            }
            if($form->get('BtnCerrar')->isClicked()) {
                if ($arCapacitacion->getEstadoAutorizado() == 1){
                    $arCapacitacionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->findBy(array('codigoCapacitacionFk' => $codigoCapacitacion));
                    if ($arCapacitacionDetalle != null){
                        if ($arCapacitacion->getEstado() == 1){
                            $arCapacitacion->setEstado(0);
                        } else {
                            $arCapacitacion->setEstado(1);
                        }
                        $em->persist($arCapacitacion);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_capacitacion_detalle', array('codigoCapacitacion' => $codigoCapacitacion)));
                    } else {
                        $objMensaje->Mensaje("error", "La capacitación no tiene detalles, no se puede cerrar", $this);
                    }

                }
            }
        }
        $arCapacitacionesDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
        $arCapacitacionesDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->findBy(array('codigoCapacitacionFk' => $codigoCapacitacion));
        $arCapacitacionesDetalles = $paginator->paginate($arCapacitacionesDetalles, $this->get('request')->query->get('page', 1),50);
        $arCapacitacionesNotas = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionNota();
        $arCapacitacionesNotas = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionNota')->findBy(array('codigoCapacitacionFk' => $codigoCapacitacion));
        $arCapacitacionesNotas = $paginator->paginate($arCapacitacionesNotas, $this->get('request')->query->get('page', 1),50);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Capacitaciones:detalle.html.twig', array(
                        'arCapacitacionesDetalles' => $arCapacitacionesDetalles,
                        'arCapacitacionesNotas' => $arCapacitacionesNotas,
                        'arCapacitacion' => $arCapacitacion,
                        'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/capacitacion/detalle/nuevo/{codigoCapacitacion}", name="brs_rhu_capacitacion_detalle_nuevo")
     */
    public function detalleNuevoAction($codigoCapacitacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);
        $arCapacitacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
        $form = $this->createForm(new RhuCapacitacionDetalleType(), $arCapacitacionDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($arCapacitacion->getEstadoAutorizado() == 0){
                $arCapacitacionDetalle = $form->getData();
                $arCapacitacionDetalle->setCapacitacionRel($arCapacitacion);
                $em->persist($arCapacitacionDetalle);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Capacitaciones:detalleNuevo.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/capacitacion/detalle/nuevo/empleado/{codigoCapacitacion}", name="brs_rhu_capacitacion_detalle_nuevo_empleado")
     */
    public function detalleNuevoEmpleadoAction($codigoCapacitacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);

        $form = $this->formularioDetalleNuevoEmpleado();
        $form->handleRequest($request);
        $this->listarDetalleNuevoEmpleado();

        if ($form->isValid()) {
            if ($form->get('BtnAgregar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arCapacitacion->getEstadoAutorizado() == 0){
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoEmpleado) {
                            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
                            $arCapacitacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
                            $arCapacitacionDetalle->setCapacitacionRel($arCapacitacion);
                            $arCapacitacionDetalle->setEmpleadoRel($arEmpleado);
                            $arCapacitacionDetalle->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                            $arCapacitacionDetalle->setNombreCorto($arEmpleado->getNombreCorto());
                            $em->persist($arCapacitacionDetalle);
                        }
                        $em->flush();
                    }
                }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarDetalleNuevoEmpleado($form);
                $this->listarDetalleNuevoEmpleado();
            }

        }

        /*$arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionalesConcepto();
        $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findBy(array('estadoActivo' => 1));
        $arEmpleados = $paginator->paginate($arEmpleados, $request->query->get('page', 1), 90);*/

        $arEmpleados = $paginator->paginate($em->createQuery($this->strDqlListaNuevoDetalleEmpleado), $request->query->get('page', 1), 90);

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Capacitaciones:detalleNuevoEmpleado.html.twig', array(
            'arCapacitacion' => $arCapacitacion,
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/capacitacion/detalle/nuevo/nota/{codigoCapacitacion}", name="brs_rhu_capacitacion_detalle_nuevo_nota")
     */
    public function detalleNuevoNotaAction($codigoCapacitacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);
        $arCapacitacionNota = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionNota();
        $form = $this->createForm(new RhuCapacitacionNotaType(), $arCapacitacionNota);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($arCapacitacion->getEstadoAutorizado() == 0){
                $arCapacitacionDetalle = $form->getData();
                $arCapacitacionDetalle->setCapacitacionRel($arCapacitacion);
                $em->persist($arCapacitacionDetalle);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            } else {
              echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Capacitaciones:detalleNuevoNota.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/capacitacion/nuevo/{codigoCapacitacion}", name="brs_rhu_capacitacion_nuevo")
     */
    public function nuevoAction($codigoCapacitacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arCapacitacion = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion();
        if ($codigoCapacitacion != 0)
        {
            $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);
        } else {
            $arCapacitacion->setFecha(new \DateTime('now'));
            $arCapacitacion->setFechaCapacitacion(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuCapacitacionType(), $arCapacitacion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($arCapacitacion->getEstadoAutorizado() == 0){
                $arCapacitacion = $form->getData();
                $em->persist($arCapacitacion);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Capacitaciones:nuevo.html.twig', array(
            'arRequisito' => $arCapacitacion,
            'form' => $form->createView()));
    }

    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->listaDql(
            $session->get('filtroTipo'),
            $session->get('filtroTema'),
            $session->get('filtroEstado'),
            $session->get('filtroDesde'),
            $session->get('filtroHasta')
            );
    }

    private function listarDetalleNuevoEmpleado() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strDqlListaNuevoDetalleEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->listaDql(
        $session->get('filtroCodigoCargo'),
        $session->get('filtroCodigoCentroCosto'),
        $session->get('filtroIdentificacion'),
        $session->get('filtroNombre'),
        $session->get('filtroCodigoCliente'),
        $session->get('filtroNombreCliente'),
        $session->get('filtroCodigoPuesto')
        );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCapacitacionTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                    ->orderBy('ct.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroTipo')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCapacitacionTipo", $session->get('filtroTipo'));
        }
        $form = $this->createFormBuilder()
            ->add('capacitacionTipoRel', 'entity', $arrayPropiedades)
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('TxtTema', 'text', array('label'  => 'TEMA','data' => $session->get('filtroTema')))
            ->add('estado', 'choice', array('choices'   => array('2' => 'TODOS', '0' => 'SI', '1' => 'NO'), 'data' => $session->get('filtroEstado')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir asistencia', 'disabled' => true);
        $arrBotonImprimirNotas = array('label' => 'Imprimir notas', 'disabled' => true);
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonAsistio = array('label' => 'Asistio', 'disabled' => false);
        $arrBotonNoAsistio = array('label' => 'No asistio', 'disabled' => false);
        $arrBotonActualizarDetalle = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonEliminarNota = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonCerrar = array('label' => 'Cerrar/Abrir', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {
            if ($ar->getEstado() == 1){
                $arrBotonAutorizar['disabled'] = true;
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonEliminarDetalle['disabled'] = true;
                $arrBotonAsistio['disabled'] = true;
                $arrBotonNoAsistio['disabled'] = true;
                $arrBotonActualizarDetalle['disabled'] = true;
                $arrBotonEliminarNota['disabled'] = true;
                $arrBotonImprimir['disabled'] = true;
                $arrBotonImprimirNotas['disabled'] = true;
            } else {
                $arrBotonAutorizar['disabled'] = true;
                $arrBotonEliminarDetalle['disabled'] = true;
                $arrBotonAsistio['disabled'] = true;
                $arrBotonNoAsistio['disabled'] = true;
                $arrBotonActualizarDetalle['disabled'] = true;
                $arrBotonEliminarNota['disabled'] = true;
                $arrBotonImprimir['disabled'] = false;
                $arrBotonImprimirNotas['disabled'] = false;
            }


        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonCerrar['disabled'] = true;
        }

        $form = $this->createFormBuilder()
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnImprimirNotas', 'submit', $arrBotonImprimirNotas)
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)
                    ->add('BtnAsistio', 'submit', $arrBotonAsistio)
                    ->add('BtnNoAsistio', 'submit', $arrBotonNoAsistio)
                    ->add('BtnActualizarDetalle', 'submit', $arrBotonActualizarDetalle)
                    ->add('BtnEliminarDetalle', 'submit', $arrBotonEliminarDetalle)
                    ->add('BtnEliminarNota', 'submit', $arrBotonEliminarNota)
                    ->add('BtnCerrar', 'submit', $arrBotonCerrar)
                    ->getForm();
        return $form;
    }

    private function formularioDetalleNuevoEmpleado() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedadesCargo = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCargo')) {
            $arrayPropiedadesCargo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCargo", $session->get('filtroCodigoCargo'));
        }
        $arrayPropiedadesCentro = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedadesCentro['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $arrayPropiedadesPuesto = array(
                'class' => 'BrasaTurnoBundle:TurPuesto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoPuesto')) {
            $arrayPropiedadesPuesto['data'] = $em->getReference("BrasaTurnoBundle:TurPuesto", $session->get('filtroCodigoPuesto'));
        }
        $form = $this->createFormBuilder()
            ->add('cargoRel', 'entity', $arrayPropiedadesCargo)
            ->add('centroCostoRel', 'entity', $arrayPropiedadesCentro)
            ->add('puestoRel', 'entity', $arrayPropiedadesPuesto)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtCodigoCliente', 'text', array('label'  => 'Codigo Cliente','data' => $session->get('filtroCodigoCliente')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'Nombre Cliente','data' => $session->get('filtroNombreCliente')))
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroTipo', $controles['capacitacionTipoRel']);
        $session->set('filtroTema', $form->get('TxtTema')->getData());
        $session->set('filtroEstado', $form->get('estado')->getData());

        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null){
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
        }
    }

    private function filtrarDetalleNuevoEmpleado($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCargo', $controles['cargoRel']);
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroCodigoPuesto', $controles['puestoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroCodigoCliente', $form->get('TxtCodigoCliente')->getData());
        $session->set('filtroNombreCliente', $form->get('TxtNombreCliente')->getData());
    }

    private function generarExcel() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
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
                for($col = 'A'; $col !== 'Z'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
                }
                for($col = 'N'; $col !== 'O'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
                }
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'FECHA')
                            ->setCellValue('C1', 'HORA')
                            ->setCellValue('D1', 'DURACION')
                            ->setCellValue('E1', 'CIUDAD')
                            ->setCellValue('F1', 'LUGAR')
                            ->setCellValue('G1', 'TIPO')
                            ->setCellValue('H1', 'TEMA')
                            ->setCellValue('I1', 'METODOLOGIA')
                            ->setCellValue('J1', 'OBJETIVO')
                            ->setCellValue('K1', 'CONTENIDO')
                            ->setCellValue('L1', 'A CAPACITAR')
                            ->setCellValue('M1', 'ASISTIERON')
                            ->setCellValue('N1', 'VR CAPACITACION')
                            ->setCellValue('O1', 'FACILITADOR')
                            ->setCellValue('P1', 'IDENTIFICACION')
                            ->setCellValue('Q1', 'ABIERTO');

                $i = 2;
                $query = $em->createQuery($this->strDqlLista);
                $arCapacitaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion();
                $arCapacitaciones = $query->getResult();

                foreach ($arCapacitaciones as $arCapacitacion) {
                    if ($arCapacitacion->getCodigoCapacitacionTipoFk() == null){
                        $strCapacitacionTipo = "";
                    }else{
                        $strCapacitacionTipo = $arCapacitacion->getCapacitacionTipoRel()->getNombre();
                    }
                    if ($arCapacitacion->getCodigoCiudadFk() == null){
                        $ciudad = "";
                    }else{
                        $ciudad = $arCapacitacion->getCiudadRel()->getNombre();
                    }
                    if ($arCapacitacion->getCodigoCapacitacionMetodologiaFk() == null){
                        $strCapacitacionMetodologia = "";
                    }else{
                        $strCapacitacionMetodologia = $arCapacitacion->getCapacitacionMetodologiaRel()->getNombre();
                    }
                    $estado = "SI";
                    if ($arCapacitacion->getEstado() == 1){
                        $estado = "NO";
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCapacitacion->getCodigoCapacitacionPk())
                            ->setCellValue('B' . $i, $arCapacitacion->getFechaCapacitacion()->format('Y-m-d'))
                            ->setCellValue('C' . $i, $arCapacitacion->getFechaCapacitacion()->format('H:i:s'))
                            ->setCellValue('D' . $i, $arCapacitacion->getDuracion())
                            ->setCellValue('E' . $i, $ciudad)
                            ->setCellValue('F' . $i, $arCapacitacion->getLugar())
                            ->setCellValue('G' . $i, $strCapacitacionTipo)
                            ->setCellValue('H' . $i, $arCapacitacion->getTema())
                            ->setCellValue('I' . $i, $strCapacitacionMetodologia)
                            ->setCellValue('J' . $i, $arCapacitacion->getObjetivo())
                            ->setCellValue('K' . $i, $arCapacitacion->getContenido())
                            ->setCellValue('L' . $i, $arCapacitacion->getNumeroPersonasCapacitar())
                            ->setCellValue('M' . $i, $arCapacitacion->getNumeroPersonasAsistieron())
                            ->setCellValue('N' . $i, $arCapacitacion->getVrCapacitacion())
                            ->setCellValue('O' . $i, $arCapacitacion->getFacilitador())
                            ->setCellValue('P' . $i, $arCapacitacion->getNumeroIdentificacionFacilitador())
                            ->setCellValue('Q' . $i, $estado);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Capacitaciones');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Capacitaciones.xlsx"');
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
