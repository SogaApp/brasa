<?php

namespace Brasa\AfiliacionBundle\Controller\Movimiento;

use Brasa\AfiliacionBundle\Entity\AfiPeriodo;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Brasa\AfiliacionBundle\Form\Type\AfiPeriodoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;


class PeriodoController extends Controller
{

    var $strDqlLista = "";

    /**
     * @Route("/afi/movimiento/periodo", name="brs_afi_movimiento_periodo")
     */
    public function listaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 128, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');

            if ($request->request->get('OpGenerar')) {
                $codigoPeriodo = $request->request->get('OpGenerar');
                $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->generar($codigoPeriodo);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }

            if ($request->request->get('OpDeshacer')) {
                $codigoPeriodo = $request->request->get('OpDeshacer');
                $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
                if ($arPeriodo->getEstadoFacturado() == 0) {
                    $strSql = "DELETE FROM afi_periodo_detalle WHERE codigo_periodo_fk = " . $codigoPeriodo;
                    $em->getConnection()->executeQuery($strSql);
                    $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                    $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
                    $arPeriodo->setEstadoGenerado(0);
                    $arPeriodo->setSubtotal(0);
                    $arPeriodo->setTotal(0);
                    $arPeriodo->setIva(0);
                    $arPeriodo->setAdministracion(0);
                    $arPeriodo->setSalud(0);
                    $arPeriodo->setPension(0);
                    $arPeriodo->setRiesgos(0);
                    $arPeriodo->setCaja(0);
                    $arPeriodo->setIcbf(0);
                    $arPeriodo->setSena(0);
                    $arPeriodo->setInteresMora(0);
                    $arPeriodo->setTotalAnterior(0);
                    $arPeriodo->setSubtotalAnterior(0);
                    $em->persist($arPeriodo);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
                } else {
                    $objMensaje->Mensaje('error', 'No se puede desgenerar el cobro, esta siendo utilizado en facturas', $this);
                }
            }

            if ($request->request->get('OpGenerarPago')) {
                $codigoPeriodo = $request->request->get('OpGenerarPago');
                $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->generarPago($codigoPeriodo);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }

            if ($request->request->get('OpDeshacerPago')) {
                $codigoPeriodo = $request->request->get('OpDeshacerPago');
                $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);

                $strSql = "DELETE FROM afi_periodo_detalle_pago_detalle WHERE codigo_periodo_fk = " . $codigoPeriodo;
                $em->getConnection()->executeQuery($strSql);
                $strSql = "DELETE FROM afi_periodo_detalle_pago WHERE codigo_periodo_fk = " . $codigoPeriodo;
                $em->getConnection()->executeQuery($strSql);
                $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
                $arPeriodo->setEstadoPagoGenerado(0);
                $em->persist($arPeriodo);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }

            if ($request->request->get('OpCerrar')) {
                $codigoPeriodo = $request->request->get('OpCerrar');
                $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
                if ($arPeriodo->getEstadoCerrado() == 0) {
                    $arPeriodo->setEstadoCerrado(1);
                    $em->persist($arPeriodo);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }

            if ($form->get('BtnGenerarCobro')->isClicked()) {
                $arPeriodos = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                $arPeriodos = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->findBy(array('estadoGenerado' => 0, 'estadoCerrado' => 0));
                foreach ($arPeriodos as $arPeriodo) {
                    echo $arPeriodo->getCodigoPeriodoPk() . ', ';
                    $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->generar($arPeriodo->getCodigoPeriodoPk());
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }

            if ($form->get('BtnGenerarPago')->isClicked()) {
                $arPeriodos = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                $arPeriodos = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->findBy(array('estadoPagoGenerado' => 0, 'estadoCerrado' => 0));
                foreach ($arPeriodos as $arPeriodo) {
                    $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->generarPago($arPeriodo->getCodigoPeriodoPk());
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }

            if ($form->get('BtnGenerarInteresMora')->isClicked()) {
                $arPeriodos = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                $arPeriodos = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->findBy(array('estadoGenerado' => 1, 'estadoCerrado' => 0, 'estadoFacturado' => 0));
                foreach ($arPeriodos as $arPeriodo) {
                    $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->generarInteresMora($arPeriodo->getCodigoPeriodoPk());
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }

            if ($form->get('BtnEliminar')->isClicked()) {
                if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 128, 4)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
                }
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                try {
                    $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->eliminar($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
                } catch (ForeignKeyConstraintViolationException $e) {
                    $objMensaje->Mensaje('error', 'No se puede eliminar el registro, tiene detalles asociados', $this);
                }
            }

            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }

            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }

        $arPeriodos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Periodo:lista.html.twig', array(
            'arPeriodos' => $arPeriodos,
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/periodo/nuevo/{codigoPeriodo}", name="brs_afi_movimiento_periodo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoPeriodo = '')
    {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        if ($codigoPeriodo != '' && $codigoPeriodo != '0') {
            if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 128, 3)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
            }
            $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
        } else {
            if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 128, 2)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
            }
            $fecha = new \DateTime('now');
            $arPeriodo->setFechaDesde($fecha);
            $arPeriodo->setFechaHasta($fecha);
            $arPeriodo->setFechaPago($fecha);
            $arPeriodo->setAnio($fecha->format('Y'));
            $arPeriodo->setMes($fecha->format('m'));
            $arPeriodo->setAnioPago($fecha->format('Y'));
            $arPeriodo->setMesPago($fecha->format('m'));
        }
        $form = $this->createForm(new AfiPeriodoType, $arPeriodo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPeriodo = $form->getData();
            $em->persist($arPeriodo);
            $em->flush();
            if ($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo_nuevo', array('codigoPeriodo' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }
        }
        return $this->render('BrasaAfiliacionBundle:Movimiento/Periodo:nuevo.html.twig', array(
            'arPeriodo' => $arPeriodo,
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/periodo/detalle/{codigoPeriodo}", name="brs_afi_movimiento_periodo_detalle")
     */
    public function detalleAction(Request $request, $codigoPeriodo = '')
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
        $form = $this->formularioDetalle();
        $form->handleRequest($request);
        $this->listaDetalle($codigoPeriodo);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnDetalleCobroExcel')->isClicked()) {
                $this->listaDetalle($codigoPeriodo);
                $this->generarDetalleExcel();
            }
            if ($form->get('BtnDetalleCobroImprimir')->isClicked()) {
                if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 128, 1)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
                }
                $objPeriodoCobro = new \Brasa\AfiliacionBundle\Formatos\PeriodoCobro();
                $objPeriodoCobro->Generar($this, $codigoPeriodo);
                //$this->listaDetalle($codigoPeriodo);
                //$this->generarDetalleExcel();
            }
            if ($form->get('BtnDetallePagoExcel')->isClicked()) {
                $this->listaDetallePago($codigoPeriodo);
                $this->generarDetallePagoExcel();
            }
            if ($form->get('BtnEnviarEmail')->isClicked()) {
                $this->enviarEmail($arPeriodo);
            }
            if ($form->get('BtnDetalleActualizar')->isClicked()) {
                $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetalle')->actualizarDetalleCobro($codigoPeriodo);
            }
            if ($form->get('BtnDetalleCobroEliminar')->isClicked()) {
                //$arrSeleccionados = $request->request->get('ChkSeleccionar');
                $registros = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetalle')->eliminar($arrSeleccionados);
                if ($registros == TRUE) {
                    $objMensaje->Mensaje('error', 'No se puede eliminar el registro por que se encuentra en una factura', $this);
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
            }
            if ($form->get('BtnDetallePagoEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarDetallePago');
                $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetallePago')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
            }
            if ($form->get('BtnDetalleTrasladarNuevo')->isClicked()) { // los descartados se pasaran a un nuevo periodo
                //$arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arPeriodo->getEstadoFacturado() == 0) {
                    $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetalle')->trasladoNuevo($arrSeleccionados, $codigoPeriodo);
                } else {
                    $objMensaje->Mensaje('error', 'El periodo se encuentra facturado', $this);
                }

                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
            }

            if ($form->get('BtnDetalleInteresMora')->isClicked()) {
                $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->generarInteresMora($codigoPeriodo);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
            }
        }
        $arPeriodoDetalles = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 100);
        $dql = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetallePago')->listaDQL($codigoPeriodo);
        $arPeriodoDetallesPagos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 100);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Periodo:detalle.html.twig', array(
            'arPeriodo' => $arPeriodo,
            'arPeriodoDetalles' => $arPeriodoDetalles,
            'arPeriodoDetallesPagos' => $arPeriodoDetallesPagos,
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/periodo/interesmora/{codigoPeriodo}", name="brs_afi_movimiento_periodo_interesmora")
     */
    /* public function interesmoraAction(Request $request, $codigoPeriodo = '') {
      $request = $this->getRequest();
      $em = $this->getDoctrine()->getManager();
      $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
      $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
      $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
      $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
      $form = $this->createFormBuilder()
      ->setAction($this->generateUrl('brs_afi_movimiento_periodo_interesmora', array('codigoPeriodo' => $codigoPeriodo)))
      ->add('interesMora', 'number', array('data' =>$arPeriodo->getInteresMora() ,'required' => true))
      ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
      ->getForm();
      $form->handleRequest($request);

      if ($form->isValid()) {
      if ($arPeriodo->getEstadoGenerado() == 1 && $arPeriodo->getEstadoCerrado() == 0 && $arPeriodo->getEstadoFacturado() == 0){
      $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->generarInteresMora($codigoPeriodo);
      }
      return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
      }
      return $this->render('BrasaAfiliacionBundle:Movimiento/Periodo:interesMora.html.twig', array(
      'arPeriodo' => $arPeriodo,
      'form' => $form->createView()
      ));
      } */

    /**
     * @Route("/afi/movimiento/periodo/actualizarfechapago/{codigoPeriodo}", name="brs_afi_movimiento_periodo_actualizarfechapago")
     */
    public function actualizarFechasPagoAction(Request $request, $codigoPeriodo = '')
    {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if ($arPeriodo->getFechaPago() != null) {
            $fechaPago = $arPeriodo->getFechaPago();
        } else {
            $fechaPago = new \DateTime('now');
        }
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_afi_movimiento_periodo_actualizarfechapago', array('codigoPeriodo' => $codigoPeriodo)))
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $arPeriodo->getFechaDesde()))
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $arPeriodo->getFechaHasta()))
            ->add('fechaPago', DateType::class, array('format' => 'yyyyMMdd', 'data' => $fechaPago))
            ->add('anio', NumberType::class, array('required' => true, 'data' => $arPeriodo->getAnio()))
            ->add('mes', NumberType::class, array('required' => true, 'data' => $arPeriodo->getMes()))
            ->add('anioPago', NumberType::class, array('required' => true, 'data' => $arPeriodo->getAnioPago()))
            ->add('mesPago', NumberType::class, array('required' => true, 'data' => $arPeriodo->getMesPago()))
            ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $arPeriodo->setFechaDesde($form->get('fechaDesde')->getData());
            $arPeriodo->setFechaHasta($form->get('fechaHasta')->getData());
            $arPeriodo->setFechaPago($form->get('fechaPago')->getData());
            $arPeriodo->setAnio($form->get('anio')->getData());
            $arPeriodo->setMes($form->get('mes')->getData());
            $arPeriodo->setAnioPago($form->get('anioPago')->getData());
            $arPeriodo->setMesPago($form->get('mesPago')->getData());
            $em->persist($arPeriodo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
        }
        return $this->render('BrasaAfiliacionBundle:Movimiento/Periodo:actualizarFechasPago.html.twig', array(
            'arPeriodo' => $arPeriodo,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/afi/movimiento/periodo/archivoplano/{codigoPeriodo}", name="brs_afi_movimiento_periodo_archivoplano")
     */
    public function archivoPlanoAction(Request $request, $codigoPeriodo = '')
    {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
        if ($arPeriodo->getFechaPago() != null) {
            $fechaPago = $arPeriodo->getFechaPago();
        } else {
            $fechaPago = new \DateTime('now');
        }
        $arrayPropiedadesI = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');
            },
            'property' => 'nombre',
            'required' => true,
            'empty_data' => "",
            'empty_value' => "Seleccione...",
        );
        if($arPeriodo->getClienteRel()->getCodigoEntidadRiesgoFk()){
            $arrayPropiedadesI["data"] = $this->container->get('doctrine.orm.entity_manager')->getReference("BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional", $arPeriodo->getClienteRel()->getCodigoEntidadRiesgoFk());
        }
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_afi_movimiento_periodo_archivoplano', array('codigoPeriodo' => $codigoPeriodo)))
            ->add('arlIRel', 'entity', $arrayPropiedadesI)
            ->add('tipo', 'choice', array('choices' => array('U' => 'Independiente', 'S' => 'Sucursal'), 'required' => true))
            ->add('entidad', 'choice', array('choices' => array('88' => 'Simple', '89' => 'Enlace operativo'), 'required' => true))
            ->add('sucursal', 'integer', array('required' => false, 'data' => $arPeriodo->getClienteRel()->getCodigoSucursal()))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($request->request->get('OpGenerar')) {
                $codigoProceso = $request->request->get('OpGenerar');
                $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
                $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);

                //Funcion que genera la doble linea para las novedades y ordinarias
                $this->generarPeriodoDetallePagoDetalle($arPeriodo);

                //Funcion para generar el archivo plano
                $respuesta = $this->generarPlano($arPeriodo, $form, $codigoProceso);
                if ($respuesta) {
                    $objMensaje->Mensaje('error', $respuesta, $this);
                }
            }

            if ($request->request->get('OpGenerarSucursales')) {
                $codigoProceso = $request->request->get('OpGenerar');
                $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
                $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
                $respuesta = $this->generarPlanoSucursal();
                if ($respuesta) {
                    $objMensaje->Mensaje('error', $respuesta, $this);
                }
            }

            return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
        }
        return $this->render('BrasaAfiliacionBundle:Movimiento/Periodo:archivoPlano.html.twig', array(
            'arPeriodo' => $arPeriodo,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/afi/movimiento/periodo/parametros/intereces/{codigoPeriodo}", name="brs_afi_movimiento_periodo_parametros_intereces")
     */
    public function parametrosInterecesAction(Request $request, $codigoPeriodo = "")
    {

        $em = $this->getDoctrine()->getManager();
        $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_afi_movimiento_periodo_parametros_intereces', array('codigoPeriodo' => $codigoPeriodo)))
            ->add('vrInteres', NumberType::class, array('data' => $arPeriodo->getInteresMora(), 'required' => false))
            ->add('vrTotal', NumberType::class, array('data' => $arPeriodo->getTotal(), 'required' => false))
            ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $vrInteres = $form->get('vrInteres')->getData();
            $vrTotal = $form->get('vrTotal')->getData();
            $arPeriodo->setTotalAnterior($arPeriodo->getTotal());
            $arPeriodo->setInteresMora($vrInteres);
            $arPeriodo->setTotal($vrTotal);
            $em->persist($arPeriodo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
        }
        return $this->render('BrasaAfiliacionBundle:Movimiento/Periodo:parametrosPeriodo.html.twig', array(
            'arPeriodo' => $arPeriodo,
            'form' => $form->createView()
        ));
    }

    private function enviarEmail($arPeriodo)
    {
        $session = new session;
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $ruta = $arConfiguracionGeneral->getRutaTemporal();
        $arCorreos = explode(" ", $arPeriodo->getClienteRel()->getEmail());
        $nombre = $arPeriodo->getClienteRel()->getNombreCorto();
        if ($arCorreos[0] && filter_var($arCorreos[0], FILTER_VALIDATE_EMAIL)) {
            // se genera cuenta de cobro
            try {
                /** @var $mailer \Swift_Mailer */
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $objPeriodoCobro = new \Brasa\AfiliacionBundle\Formatos\PeriodoCobro();
                $objPeriodoCobro->Generar($this, $arPeriodo->getCodigoPeriodoPk(), $ruta);
                $host = $this->container->getParameter("mailer_host");
                $username = $this->container->getParameter("mailer_user");
                $password = $this->container->getParameter("mailer_password");
                $flag = false;// controla el envio de correo
                $rutaArchivo = $ruta . "PeriodoCobro" . $arPeriodo->getCodigoPeriodoPk() . ".pdf";
                $strMensaje = "Reciba un cordial saludo de HORUS.<br>" .
                    "Adjuntamos la cuenta de cobro de la Seguridad Social y quedamos atentos a las novedades a reportar, recuerde realizar el pago en la cuenta de ahorros Bancolombia N°014-038-579-35<br>" .
                    "Titular: SERVICIOS Y OBRAS SAS NIT . 901300482.<br>" .
                    "Por favor enviar soporte de pago .<br>" .
                    "Le recordamos que también ofrecemos servicios en:<br>" .
                    "* Certificaciones en alturas.<br>" .
                    "* Pólizas de seguros.<br>" .
                    "* Asesoría en salud ocupacional .<br>";
                $message = \Swift_Message::newInstance()
                    ->setFrom(array($username => $arConfiguracionGeneral->getNombreEmpresa()))
                    ->setTo(array(strtolower($arCorreos[0]) => $nombre))
                    ->setSubject('Relacion de cobro ')
                    ->setBody($strMensaje, 'text/html');
                if (count($arCorreos) > 0) {
                    for ($i = 1; $i <= (count($arCorreos) - 1); $i++) {
                        $message->addTo(strtolower($arCorreos[$i]));
                    }
                }
                if (file_exists($rutaArchivo)) {
                    $message->attach(\Swift_Attachment::fromPath($rutaArchivo));
                    $flag = true;
                }
                if ($flag) {
                    $transport = Swift_SmtpTransport::newInstance($host, 465, 'ssl')
                        ->setUsername($username)
                        ->setPassword($password);
                    $mailer = \Swift_Mailer::newInstance($transport);
                    $mailer->send($message);
                }
            } catch (\Exception $e) {
                $objMensaje->Mensaje('error', 'No se pudo enviar el correo "error:' . $e->getMessage() . '"', $this);
            }
            $objMensaje->Mensaje("informacion", "Se han enviado los correos exitosamente", $this);
        }

    }

    private function lista()
    {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->listaDQL(
            $session->get('filtroCodigoCliente'), $session->get('filtroPeriodoEstadoCerrado'), $session->get('filtroPeriodoPagoDesde'), $session->get('filtroPeriodoPagoHasta'), $session->get('filtroPeriodoEstadoCerrado')
        );
    }

    private function listaDetalle($codigoPeriodo)
    {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetalle')->listaDQL(
            $codigoPeriodo
        );
    }

    private function listaDetallePago($codigoPeriodo)
    {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetallePago')->listaDQL(
            $codigoPeriodo
        );
    }

    private function filtrar($form)
    {
        $session = new session;
        $session->set('filtroNit', $form->get('TxtNit')->getData());
        $session->set('filtroPeriodoEstadoCerrado', $form->get('estadoCerrado')->getData());
        $session->set('filtroPeriodoEstadoFacturado', $form->get('estadoFacturado')->getData());
        $fechaDesde = "";
        $fechaHasta = "";
        $session->set('filtrarFecha', $form->get('filtrarFecha')->getData());
        if ($form->get('filtrarFecha')->getData()) {
            $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
            $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
        }
        $session->set('filtroPeriodoPagoDesde', $fechaDesde);
        $session->set('filtroPeriodoPagoHasta', $fechaHasta);
        //$this->lista();
    }

    private function formularioFiltro()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if ($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if ($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            } else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }
        } else {
            $session->set('filtroCodigoCliente', null);
        }
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        if ($session->get("filtroPeriodoPagoDesde")) {
            $strFechaDesde = $session->get("filtroPeriodoPagoDesde");
        }
        if ($session->get("filtroPeriodoPagoHasta")) {
            $strFechaHasta = $session->get("filtroPeriodoPagoHasta");
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('TxtNit', TextType::class, array('label' => 'Nit', 'data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', TextType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))
            ->add('estadoCerrado', ChoiceType::class, array('choices' => array('2' => 'TODOS', '1' => 'CERRADO', '0' => 'SIN CERRAR'), 'data' => $session->get('filtroPeriodoEstadoCerrado')))
            ->add('estadoFacturado', ChoiceType::class, array('choices' => array('2' => 'TODOS', '1' => 'FACTURADO', '0' => 'SIN FACTURAR'), 'data' => $session->get('filtroPeriodoEstadoFacturado')))
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde, 'attr' => array('class' => 'date',)))
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta, 'attr' => array('class' => 'date',)))
            ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
            ->add('BtnGenerarCobro', SubmitType::class, array('label' => 'Generar cobro masivo',))
            ->add('BtnGenerarPago', SubmitType::class, array('label' => 'Generar pago masivo',))
            ->add('BtnGenerarInteresMora', SubmitType::class, array('label' => 'Generar financieros',))
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get("filtrarFecha")))
            ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle()
    {
        $session = new session;

        $form = $this->createFormBuilder()
            ->add('BtnDetalleActualizar', SubmitType::class, array('label' => 'Actualizar'))
            ->add('BtnDetalleCobroExcel', SubmitType::class, array('label' => 'Excel',))
            ->add('BtnEnviarEmail', SubmitType::class, array('label' => 'Enviar email'))
            ->add('BtnDetalleCobroImprimir', SubmitType::class, array('label' => 'Imprimir',))
            ->add('BtnDetallePagoEliminar', SubmitType::class, array('label' => 'Eliminar',))
            ->add('BtnDetalleCobroEliminar', SubmitType::class, array('label' => 'Eliminar',))
            ->add('BtnDetallePagoExcel', SubmitType::class, array('label' => 'Excel',))
            ->add('BtnDetalleTrasladarNuevo', SubmitType::class, array('label' => 'Traslado nuevo',))
            ->add('BtnDetalleInteresMora', SubmitType::class, array('label' => 'Financieros',))
            ->getForm();
        return $form;
    }

    private function generarExcel()
    {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes . ")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'C'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        /* for($col = 'AI'; $col !== 'AK'; $col++) {
          $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
          } */

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CÓDIG0')
            ->setCellValue('B1', 'CLIENTE');

        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arPeriodos = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        $arPeriodos = $query->getResult();

        foreach ($arPeriodos as $arPeriodo) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $arPeriodo->getCodigoPeriodoPk())
                ->setCellValue('B' . $i, $arPeriodo->getClienteRel()->getNombreCorto());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Periodo');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Periodos . xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

    private function generarDetalleExcel()
    {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes . ")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'R'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for ($col = 'I'; $col !== 'R'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'COD')
            ->setCellValue('B1', 'CLIENTE')
            ->setCellValue('C1', 'DESDE')
            ->setCellValue('D1', 'HASTA')
            ->setCellValue('E1', 'IDENTIFICACION')
            ->setCellValue('F1', 'NOMBRE')
            ->setCellValue('G1', 'ING')
            ->setCellValue('H1', 'DIAS')
            ->setCellValue('I1', 'SALARIO')
            ->setCellValue('J1', 'PENSION')
            ->setCellValue('K1', 'SALUD')
            ->setCellValue('L1', 'CAJA')
            ->setCellValue('M1', 'RIESGO')
            ->setCellValue('N1', 'SENA')
            ->setCellValue('O1', 'ICBF')
            ->setCellValue('P1', 'ADMIN')
            ->setCellValue('Q1', 'TOTAL');
        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arPeriodoDetalles = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle();
        $arPeriodoDetalles = $query->getResult();

        foreach ($arPeriodoDetalles as $arPeriodoDetalle) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $arPeriodoDetalle->getCodigoPeriodoDetallePk())
                ->setCellValue('B' . $i, $arPeriodoDetalle->getPeriodoRel()->getClienteRel()->getNombreCorto())
                ->setCellValue('C' . $i, $arPeriodoDetalle->getFechaDesde()->format('Y/m/d'))
                ->setCellValue('D' . $i, $arPeriodoDetalle->getFechaHasta()->format('Y/m/d'))
                ->setCellValue('E' . $i, $arPeriodoDetalle->getEmpleadoRel()->getNumeroIdentificacion())
                ->setCellValue('F' . $i, $arPeriodoDetalle->getEmpleadoRel()->getNombreCorto())
                ->setCellValue('G' . $i, $objFunciones->devuelveBoolean($arPeriodoDetalle->getIngreso()))
                ->setCellValue('H' . $i, $arPeriodoDetalle->getDias())
                ->setCellValue('I' . $i, $arPeriodoDetalle->getSalario())
                ->setCellValue('J' . $i, $arPeriodoDetalle->getPension())
                ->setCellValue('K' . $i, $arPeriodoDetalle->getSalud())
                ->setCellValue('L' . $i, $arPeriodoDetalle->getCaja())
                ->setCellValue('M' . $i, $arPeriodoDetalle->getRiesgos())
                ->setCellValue('N' . $i, $arPeriodoDetalle->getSena())
                ->setCellValue('O' . $i, $arPeriodoDetalle->getIcbf())
                ->setCellValue('P' . $i, $arPeriodoDetalle->getAdministracion())
                ->setCellValue('Q' . $i, $arPeriodoDetalle->getTotal());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PeriodoDetalle');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PeriodoDetalles . xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

    private function generarDetallePagoExcel()
    {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes . ")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'AL'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for ($col = 'D'; $col !== 'J'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'IDENTIFICACIÓN')
            ->setCellValue('B1', 'NOMBRE')
            ->setCellValue('C1', 'CONTRATO')
            ->setCellValue('D1', 'ING')
            ->setCellValue('E1', 'RET')
            ->setCellValue('F1', 'VST')
            ->setCellValue('G1', 'SLN')
            ->setCellValue('H1', 'IGE')
            ->setCellValue('I1', 'LMA')
            ->setCellValue('J1', 'VAC')
            ->setCellValue('K1', 'IRP')
            ->setCellValue('L1', 'SALARIO')
            ->setCellValue('M1', 'SUPLE')
            ->setCellValue('N1', 'DIAS.P')
            ->setCellValue('O1', 'DIAS.S')
            ->setCellValue('P1', 'DIAS.R.P')
            ->setCellValue('Q1', 'DIAS.C')
            ->setCellValue('R1', 'IBC P')
            ->setCellValue('S1', 'IBC S')
            ->setCellValue('T1', 'IBC R')
            ->setCellValue('U1', 'IBC C')
            ->setCellValue('V1', 'T.P')
            ->setCellValue('W1', 'T.S')
            ->setCellValue('X1', 'T.R')
            ->setCellValue('Y1', 'T.C')
            ->setCellValue('Z1', 'T.SN')
            ->setCellValue('AA1', 'T.I')
            ->setCellValue('AB1', 'C.P')
            ->setCellValue('AC1', 'C.S')
            ->setCellValue('AD1', 'C.R')
            ->setCellValue('AE1', 'C.C')
            ->setCellValue('AF1', 'C.SN')
            ->setCellValue('AG1', 'C.I')
            ->setCellValue('AH1', 'TOTAL');
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arPeriodoDetallesPagos = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago();
        $arPeriodoDetallesPagos = $query->getResult();

        foreach ($arPeriodoDetallesPagos as $arPeriodoDetallePago) {
            $suspension = '';
            if ($arPeriodoDetallePago->getSuspensionTemporalContratoLicenciaServicios() == 'X') {
                $suspension = $arPeriodoDetallePago->getDiasCotizadosPension();
            }
            $incapacidadGeneral = '';
            if ($arPeriodoDetallePago->getIncapacidadGeneral() == 'X') {
                $incapacidadGeneral = $arPeriodoDetallePago->getDiasIncapacidadGeneral();
            }

            $licenciaMaternidad = '';
            if ($arPeriodoDetallePago->getLicenciaMaternidad() == 'X') {
                $licenciaMaternidad = $arPeriodoDetallePago->getDiasLicenciaMaternidad();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $arPeriodoDetallePago->getEmpleadoRel()->getNumeroIdentificacion())
                ->setCellValue('B' . $i, $arPeriodoDetallePago->getEmpleadoRel()->getNombreCorto())
                ->setCellValue('C' . $i, $arPeriodoDetallePago->getCodigoContratoFk())
                ->setCellValue('D' . $i, $arPeriodoDetallePago->getIngreso())
                ->setCellValue('E' . $i, $arPeriodoDetallePago->getRetiro())
                ->setCellValue('F' . $i, $arPeriodoDetallePago->getVariacionTransitoriaSalario())
                ->setCellValue('G' . $i, $arPeriodoDetallePago->getSuspensionTemporalContratoLicenciaServicios() . $suspension)
                ->setCellValue('H' . $i, $arPeriodoDetallePago->getIncapacidadGeneral() . $incapacidadGeneral)
                ->setCellValue('I' . $i, $arPeriodoDetallePago->getLicenciaMaternidad() . $licenciaMaternidad)
                ->setCellValue('J' . $i, $arPeriodoDetallePago->getVacaciones())
                ->setCellValue('K' . $i, $arPeriodoDetallePago->getIncapacidadAccidenteTrabajoEnfermedadProfesional())
                ->setCellValue('L' . $i, $arPeriodoDetallePago->getSalarioBasico())
                ->setCellValue('M' . $i, $arPeriodoDetallePago->getSuplementario())
                ->setCellValue('N' . $i, $arPeriodoDetallePago->getDiasCotizadosPension())
                ->setCellValue('O' . $i, $arPeriodoDetallePago->getDiasCotizadosSalud())
                ->setCellValue('P' . $i, $arPeriodoDetallePago->getDiasCotizadosRiesgosProfesionales())
                ->setCellValue('Q' . $i, $arPeriodoDetallePago->getDiasCotizadosCajaCompensacion())
                ->setCellValue('R' . $i, $arPeriodoDetallePago->getIbcPension())
                ->setCellValue('S' . $i, $arPeriodoDetallePago->getIbcSalud())
                ->setCellValue('T' . $i, $arPeriodoDetallePago->getIbcRiesgosProfesionales())
                ->setCellValue('U' . $i, $arPeriodoDetallePago->getIbcCaja())
                ->setCellValue('V' . $i, $arPeriodoDetallePago->getTarifaPension())
                ->setCellValue('W' . $i, $arPeriodoDetallePago->getTarifaSalud())
                ->setCellValue('X' . $i, $arPeriodoDetallePago->getTarifaRiesgos())
                ->setCellValue('Y' . $i, $arPeriodoDetallePago->getTarifaCaja())
                ->setCellValue('Z' . $i, $arPeriodoDetallePago->getTarifaSena())
                ->setCellValue('AA' . $i, $arPeriodoDetallePago->getTarifaIcbf())
                ->setCellValue('AB' . $i, $arPeriodoDetallePago->getCotizacionPension())
                ->setCellValue('AC' . $i, $arPeriodoDetallePago->getCotizacionSalud())
                ->setCellValue('AD' . $i, $arPeriodoDetallePago->getCotizacionRiesgos())
                ->setCellValue('AE' . $i, $arPeriodoDetallePago->getCotizacionCaja())
                ->setCellValue('AF' . $i, $arPeriodoDetallePago->getCotizacionSena())
                ->setCellValue('AG' . $i, $arPeriodoDetallePago->getCotizacionIcbf())
                ->setCellValue('AH' . $i, $arPeriodoDetallePago->getTotalCotizacion());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PeriodoDetallePago');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PeriodoDetallePago . xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

    public static function RellenarNr($Nro, $Str, $NroCr, $strPosicion)
    {
        $Nro = utf8_decode($Nro);
        $Longitud = strlen($Nro);
        $Nc = $NroCr - $Longitud;
        for ($i = 0; $i < $Nc; $i++) {
            if ($strPosicion == "I") {
                $Nro = $Str . $Nro;
            } else {
                $Nro = $Nro . $Str;
            }
        }

        return (string)$Nro;
    }

    function comprimir($ruta, $zip_salida, $handle = false, $recursivo = false, $archivo = "")
    {

        /* Declara el handle del objeto */
        if (!$handle) {
            $handle = new \ZipArchive();
            if ($handle->open($zip_salida, ZipArchive::CREATE) === false) {
                return false; /* Imposible crear el archivo ZIP */
            }
        }

        /* Procesa directorio */
        if (is_dir($ruta)) {
            /* Aseguramos que sea un directorio sin carácteres corruptos */
            $ruta = dirname($ruta . '/arch.ext');
            $handle->addEmptyDir($ruta); /* Agrega el directorio comprimido */
            $dir = opendir($ruta);
            while ($current = readdir($dir)) {
                if ($current != " . " && $current != " ..") {
                    $this->comprimir($ruta . " / " . $current, $zip_salida, $handle, true, $current); /* Comprime el subdirectorio o archivo */
                }
            }
            //foreach (glob($ruta . '/*') as $url) { /* Procesa cada directorio o archivo dentro de el */
            //$this->comprimir($url, $zip_salida, $handle, true); /* Comprime el subdirectorio o archivo */
            //}

            /* Procesa archivo */
        } else {
            $handle->addFile($ruta, $archivo);
        }

        /* Finaliza el ZIP si no se está ejecutando una acción recursiva en progreso */
        if (!$recursivo) {
            $handle->close();
        }

        return true; /* Retorno satisfactorio */
    }

    /**
     * @param $arPeriodo AfiPeriodo
     */
    private function generarPeriodoDetallePagoDetalle($arPeriodo)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getRepository("BrasaAfiliacionBundle:AfiPeriodo")->generarPagoDetalle($arPeriodo->getCodigoPeriodoPk());
    }

    /**
     * Funcion para generar el archivo plano
     * @param $arPeriodo AfiPeriodo
     * @param $form
     * @param $codigoProceso
     * @return string
     */
    private function generarPlano($arPeriodo, $form, $codigoProceso)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $arEntidadRiesgos = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->find($arConfiguracionNomina->getCodigoEntidadRiesgoFk());
        $codigoInterfaceRiesgos = $arEntidadRiesgos->getCodigoInterface();
        if ($arPeriodo->getFechaPago() != null && $arPeriodo->getAnio() != null && $arPeriodo->getMes() != null && $arPeriodo->getAnioPago() != null && $arPeriodo->getMesPago() != null) {
            $arPeriodoDetallePagos = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetallePagoDetalle')->findBy(array('codigoPeriodoFk' => $arPeriodo->getCodigoPeriodoPk()));
            $totalCotizacion = 0;
            foreach ($arPeriodoDetallePagos as $arPeriodoDetallesumaTotales) {
                $totalCotizacion += $arPeriodoDetallesumaTotales->getTotalCotizacion();
            }
            if ($codigoProceso == 1) { //proceso a cargo del cliente independiente
                $codigoInterfaceRiesgos = $form->get('arlIRel')->getData();
                $arEntidadRiesgos2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->find($codigoInterfaceRiesgos);
                $codigoInterfaceRiesgos = $arEntidadRiesgos2->getCodigoInterface();
                $tipo = "I";
                $tipoDoc = "CC";
                $formaPresentacion = $form->get('tipo')->getData();
                $nit = $arPeriodo->getClienteRel()->getRazonSocialRel()->getNit();
                $dv = $arPeriodo->getClienteRel()->getRazonSocialRel()->getDv();
                $cliente = $arPeriodo->getClienteRel()->getRazonSocialRel()->getNombre();
                $codigoSucursal = $arPeriodo->getClienteRel()->getCodigoSucursal();
                $formato = '02';
                $entidad = $form->get('entidad')->getData();
            }
            if ($codigoProceso == 2) { //proceso a cargo del cliente externo
                $codigoInterfaceRiesgos = $form->get('arlIRel')->getData();
                $arEntidadRiesgos = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->find($codigoInterfaceRiesgos);
                $codigoInterfaceRiesgos = $arEntidadRiesgos->getCodigoInterface();
                $tipo = "E";
                $tipoDoc = $arPeriodo->getClienteRel()->getRazonSocialRel()->getTipoIdentificacion();
                $formaPresentacion = $form->get('tipo')->getData();
                $nit = $arPeriodo->getClienteRel()->getRazonSocialRel()->getNit();
                $dv = $arPeriodo->getClienteRel()->getRazonSocialRel()->getDv();
                $cliente = $arPeriodo->getClienteRel()->getRazonSocialRel()->getNombre();
                $codigoSucursal = $arPeriodo->getClienteRel()->getCodigoSucursal();
                $formato = '01'; //estaba en 2
                $entidad = $form->get('entidad')->getData();
            }
            if ($codigoProceso == 3) { //proceso interno horus
                $tipo = "E";
                $tipoDoc = "NI";
                $formaPresentacion = "S";
                $nit = $arConfiguracion->getNitEmpresa();
                $cliente = $arConfiguracion->getNombreEmpresa();
                $codigoSucursal = $arPeriodo->getClienteRel()->getCodigoSucursal();
                $dv = $arConfiguracion->getDigitoVerificacionEmpresa();
                $formato = '01';
                $entidad = $form->get('entidad')->getData();
            }
            $periodoPagoDiferenteSalud = $arPeriodo->getAnio() . '-' . $this->RellenarNr($arPeriodo->getMes(), "0", 2, "I");
            $periodoPagoSalud = $arPeriodo->getAnioPago() . '-' . $this->RellenarNr($arPeriodo->getMesPago(), "0", 2, "I");
            //archivo plano
            $strRutaArchivo = $arConfiguracion->getRutaTemporal();
            $strNombreArchivo = "pila" . date('YmdHis') . ".txt";
            ob_clean();
            $ar = fopen($strRutaArchivo . $strNombreArchivo, "a") or
            die("Problemas en la creacion del archivo plano");
            //1	2	1	2	N	Tipo de registro	Obligatorio. Debe ser 01
            fputs($ar, $this->RellenarNr("01", " ", 2, "D"));
            //2	1	3	3	N	Modalidad de la Planilla	Obligatorio. Lo genera autómaticamente el Operador de Información.
            fputs($ar, $this->RellenarNr("1", " ", 1, "D"));
            //3	4	4	7	N	Secuencia	Obligatorio. Verificación de la secuencia ascendente. Para cada aportante inicia en 0001. Lo genera el sistema en el caso en que se estén digitando los datos directamente en la web. El aportante debe reportarlo en el caso de que los datos se suban en archivos planos.
            fputs($ar, $this->RellenarNr("0001", " ", 4, "D"));
            //4	200	8	207	A	Nombre o razón social del aportante	El registrado en el campo 1 del archivo tipo 1
            fputs($ar, $this->RellenarNr($cliente, " ", 200, "D"));
            //5	2	208	209	A	Tipo documento del aportante	El registrado en el campo 2 del archivo tipo 1
            fputs($ar, $this->RellenarNr($tipoDoc, " ", 2, "D"));
            //6	16	210	225	A	Número de identificación del aportante	El registrado en el campo 3 del archivo tipo 1
            fputs($ar, $this->RellenarNr($nit, " ", 16, "D"));
            //7	1	226	226	N	Dígito de verificación aportante	El registrado en el campo 4 del archivo tipo 1
            fputs($ar, $this->RellenarNr($dv, " ", 1, "D"));
            //8	1	227	227	A	Tipo de Planilla	Obligatorio lo suministra el aportante
            fputs($ar, $this->RellenarNr($tipo, " ", 1, "D"));
            //9	10	228	237	N	Número de Planilla asociada a esta planilla.	Debe dejarse en blanco cuando el tipo de planilla sea E, A, I, M, S, Y, T o X. En este campo se incluirá el número de la planilla del periodo correspondiente cuando el tipo de planilla sea N ó F. Cuando se utilice la planilla U por parte de la UGPP, en este campo se diligenciará el número del título del depósito judicial.
            fputs($ar, $this->RellenarNr("", " ", 10, "D"));
            //10	10	238	247	A	Fecha de pago Planilla asociada a esta planilla. (AAAA-MM-DD)	Debe dejarse en blanco cuando el tipo de planilla sea E, A, I, M, S, Y, T, o X. En este campo se incluirá la fecha de pago de la planilla del período correspondiente cuando el tipo de planilla sea N ó F. Cuando se utilice la planilla U, la UGPP diligenciará la fecha en que se constituyó el depósito judicial.
            fputs($ar, $this->RellenarNr("", " ", 10, "D"));
            //11	1	248	248	A	Forma de presentación	El registrado en el campo 10 del archivo tipo 1.
            fputs($ar, $this->RellenarNr($formaPresentacion, " ", 1, "D"));
            //12	10	249	258	A	Código de la sucursal del Aportante	El registrado en el campo 5 del archivo tipo 1.
            fputs($ar, $this->RellenarNr($codigoSucursal, " ", 10, "D"));
            //13	40	259	298	A	Nombre de la sucursal	El registrado en el campo 6 del archivo tipo 1.
            fputs($ar, $this->RellenarNr($arPeriodo->getClienteRel()->getNombreCorto(), " ", 40, "D"));//ESTABA $arPeriodo->getClienteRel()->getNombreCorto()
            //14	6	299	304	A	Código de la ARL a la cual el aportante se encuentra afiliado	Lo suministra el aportante
            fputs($ar, $this->RellenarNr($codigoInterfaceRiesgos, " ", 6, "D"));
            //15	7	305	311	A	Periodo de pago para los sistemas diferentes al de salud	Obligatorio. Formato año y mes (aaaa-mm). Lo calcula el Operador de Información.
            fputs($ar, $this->RellenarNr($periodoPagoDiferenteSalud, " ", 7, "D"));
            //16	7	312	318	A	Periodo de pago para el sistema de salud	Obligatorio. Formato año y mes (aaaa-mm). Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($periodoPagoSalud, " ", 7, "D"));
            //17	10	319	328	N	Número de radicación o de la Planilla Integrada de Liquidación de aportes.	Asignado por el sistema . Debe ser único por operador de información.
            fputs($ar, $this->RellenarNr("", " ", 10, "D"));
            //18	10	329	338	A	Fecha de pago (aaaa-mm-dd)	Asignado por el sistema a partir de la fecha del día efectivo del pago.
            fputs($ar, $this->RellenarNr($arPeriodo->getFechaPago()->format('Y-m-d'), " ", 10, "D"));
            //19	5	339	343	N	Número total de empleados	Obligatorio. Se debe validar que sea igual al número de cotizantes únicos incluidos en el detalle del registro tipo 2, exceptuando los que tengan 40 en el campo 5 – Tipo de cotizante.
            fputs($ar, $this->RellenarNr(count($arPeriodoDetallePagos), "0", 5, "I"));
            //20	12	344	355	N	Valor total de la nómina	Obligatorio. Lo suministra el aportante, corresponde a la sumatoria de los IBC para el pago de los aportes de parafiscales de la totalidad de los empleados. Puede ser 0 para independientes
            fputs($ar, $this->RellenarNr($totalCotizacion, "0", 12, "I"));
            //21	2	356	357	N	Tipo de aportante	Obligatorio y debe ser igual al registrado en el campo 30 del archivo tipo 1
            fputs($ar, $this->RellenarNr($formato, " ", 2, "D"));//1 o 2
            //22	2	358	359	N	Código del operador de información	Asignado por el sistema del operador de información.
            fputs($ar, $this->RellenarNr($entidad, " ", 2, "D"));// entidad por la cual paga la pila enlace operativo (89), simple otros (88)
            fputs($ar, "\n");

            foreach ($arPeriodoDetallePagos as $arPeriodoDetallePago) {
                //1	2	1	2	N	Tipo de registro	Obligatorio. Debe ser 02.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTipoRegistro(), "0", 2, "I"));
                //2	5	3	7	N	Secuencia	Debe iniciar en 00001 y ser secuencial para el resto de registros. Lo genera el sistema en el caso en que se estén digitando los datos directamente en la web. El aportante debe reportarlo en el caso de que los datos se suban en archivos planos.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSecuencia(), "0", 5, "I"));
                //3	2	8	9	A	Tipo documento el cotizante	Obligatorio. Lo suministra el aportante. Los valores validos son:
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTipoDocumento(), " ", 2, "D"));
                //4	16	10	25	A	Número de identificación del cotizante	Obligatorio. Lo suministra el aportante. El operador de información validará que este campo este compuesto por letras de la A a la Z y los caracteres numéricos del Cero (0) al nueve (9). Sólo es permitido el número de identificación alfanumérico para los siguientes tipos de documentos de identidad: CE.  Cédula de Extranjería PA.  Pasaporte CD.  Carne Diplomático. Para los siguientes tipos de documento deben ser dígitos numéricos: TI.   Tarjeta de Identidad CC. Cédula de ciudadanía  SC.  Salvoconducto de permanencia RC.  Registro Civil
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getEmpleadoRel()->getNumeroIdentificacion(), " ", 16, "D"));
                //5	2	26	27	N	Tipo de cotizante	Obligatorio. Lo suministra el aportante. Los valores validos son:
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTipoCotizante(), "0", 2, "I"));
                //6	2	28	29	N	Subtipo de cotizante	Obligatorio. Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSubtipoCotizante(), "0", 2, "I"));
                //7	1	30	30	A	Extranjero no obligado a cotizar a pensiones 	Puede ser blanco o X Cuando aplique este campo los únicos tipos de documentos válidos son: CE. Cédula de extranjería PA.  Pasaporte CD.  Carné diplomático Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getExtranjeroNoObligadoCotizarPension(), " ", 1, "D"));
                //8	1	31	31	A	Colombiano en el exterior	Puede ser blanco o X si aplica.  Este campo es utilizado cuando el tipo de documento es: CC.  Cédula de ciudadanía TI.    Tarjeta de identidad Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getColombianoResidenteExterior(), " ", 1, "D"));
                //9	2	32	33	A	Código del Departamento de la ubicación laboral	Lo suministra el aportante. El operador de información deberá validar que este código este definido en la relación de la División Política y Administrativa – DIVIPOLA- expedida por el DANE Cuando marque el campo colombiano en el exterior se dejará  en blanco
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoDepartamentoUbicacionlaboral(), " ", 2, "D"));
                //10	3	34	36	A	Código del Municipio de la ubicación laboral	Lo suministra el aportante. El operador de información deberá validar que este código este definido en la relación de la División Política y Administrativa – DIVIPOLA- expedida por el DANE Cuando marque el campo colombiano en el exterior se dejará en blanco
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoMunicipioUbicacionlaboral(), " ", 3, "D"));
                //11	20	37	56	A	Primer apellido	Obligatorio. Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getPrimerApellido(), " ", 20, "D"));
                //12	30	57	86	A	Segundo apellido	Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSegundoApellido(), " ", 30, "D"));
                //13	20	87	106	A	Primer nombre	Obligatorio. Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getPrimerNombre(), " ", 20, "D"));
                //14	30	107	136	A	Segundo nombre	Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSegundoNombre(), " ", 30, "D"));
                //15	1	137	137	A	ING: ingreso	 Puede ser un blanco, R, X o C. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIngreso(), " ", 1, "D"));
                //16	1	138	138	A	RET: retiro	Puede ser un blanco, P, R, X o C. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getRetiro(), " ", 1, "D"));
                //17	1	139	139	A	TDE: Traslado desde otra EPS ó EOC	Puede ser un blanco o X. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTrasladoDesdeOtraEps(), " ", 1, "D"));
                //18	1	140	140	A	TAE: Traslado a otra EPS ó EOC	Puede ser un blanco o X. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTrasladoAOtraEps(), " ", 1, "D"));
                //19	1	141	141	A	TDP: Traslado desde otra Administradora de Pensiones	Puede ser un blanco o X. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTrasladoDesdeOtraPension(), " ", 1, "D"));
                //20	1	142	142	A	TAP: Traslado a otra  administradora de pensiones	Puede ser un blanco o X. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTrasladoAOtraPension(), " ", 1, "D"));
                //21	1	143	143	A	VSP: Variación permantente de salario	Puede ser un blanco o X. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getVariacionPermanenteSalario(), " ", 1, "D"));
                //22	1	144	144	A	Correcciones	Puede ser un blanco, A o C. Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCorrecciones(), " ", 1, "D"));
                //23	1	145	145	A	VST: Variación transitoria del salario	Puede ser un blanco o X. Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getVariacionTransitoriaSalario(), " ", 1, "D"));
                //24	1	146	146	A	SLN: suspensión temporal del contrato de trabajo o licencia no remunerada o comisión de servicios	Puede ser un blanco, X o C. Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSuspensionTemporalContratoLicenciaServicios(), " ", 1, "D"));
                //25	1	147	147	A	IGE: Incapacidad Temporal por Enfermedad General	Puede ser un blanco o X. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIncapacidadGeneral(), " ", 1, "D"));
                //26	1	148	148	A	LMA: Licencia de Maternidad  o de Paternidad	Puede ser un blanco o X. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getLicenciaMaternidad(), " ", 1, "D"));
                //27	1	149	149	A	VAC- LR: Vacaciones, Licencia Remunerada 	Puede ser: X:   Vacaciones L:    Licencia remunerada Blanco: Cuando no aplique esta novedad.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getVacaciones(), " ", 1, "D"));
                //28	1	150	150	A	AVP: Aporte Voluntario	Puede ser un blanco o X. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getAporteVoluntario(), " ", 1, "D"));
                //29	1	151	151	A	VCT: Variación centros de trabajo	Puede ser un blanco o X. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getVariacionCentrosTrabajo(), " ", 1, "D"));
                //30	2	152	153	N	IRL:Dias de  Incapacidad por accidente de trabajo o enfermedad laboral	Puede ser cero o el número de días (entre 01 y 30). Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIncapacidadAccidenteTrabajoEnfermedadProfesional(), "0", 2, "I"));
                //31	6	154	159	A	Código de la Administradora de Fondo de Pensiones a la cual pertenece el afiliado	Es un campo obligatorio y solo se permite blanco, si el tipo de cotizante o el subtipo de cotizante no es obligado a aportar al Sistema General de Pensiones. Se debe utilizar un código válido y este lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoEntidadPensionPertenece(), " ", 6, "D"));
                //32	6	160	165	A	Código de la Administradora de Fondo de Pensiones a la cual se tralada el afiliado	Obligatorio si la novedad es traslado a otra administradora de fondo de pensiones. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoEntidadPensionTraslada(), " ", 6, "D"));
                //33	6	166	171	A	Código EPS ó EOC a la cual pertenece el afiliado	Es un campo obligatorio. Se debe utilizar un código válido y éste lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoEntidadSaludPertenece(), " ", 6, "D"));
                //34	6	172	177	A	Código EPS ó EOC a la cual se traslada el afiliado	Obligatorio si en el campo 18 del registro tipo 2 se marca X. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoEntidadSaludTraslada(), " ", 6, "D"));
                //35	6	178	183	A	Código CCF a la que pertenece el afiliado	Obligatorio y solo se permite blanco, si el tipo de cotizante no es obligado a aportar a CCF. Se debe utilizar un código válido y este lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoEntidadCajaPertenece(), " ", 6, "D"));
                //36	2	184	185	N	Número de días cotizados a pensión	Obligatorio y debe permitir valores entre 0 y 30. Solo se permite 0, si el tipo de cotizante o subtipo de cotizante no está obligado a aportar pensiones. Si es menor que 30 debe haber marcado una novedad de ingreso o retiro. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getDiasCotizadosPension(), "0", 2, "I"));
                //37	2	186	187	N	Número de días cotizados a salud	Obligatorio y debe permitir valores entre 0 y 30. Si es menor que 30 debe haber marcado  una  novedad  de ingreso o retiro. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getDiasCotizadosSalud(), "0", 2, "I"));
                //38	2	188	189	N	Número de días cotizados a Riesgos Laborales	Obligatorio y debe permitir valores entre 0 y 30. Solo se permite 0, si el tipo de cotizante no está obligado a aportar al Sistema General de Riesgos Laborales, o si en los campos 25, 26, 27, del registro tipo 2 se ha marcado X o el campo 30 del registro tipo 2 es mayor que 0. Si es menor que 30 debe haber marcado la novedad correspondiente. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getDiasCotizadosRiesgosProfesionales(), "0", 2, "I"));
                //39	2	190	191	N	Número de días cotizados a Caja de Compensación Familiar	Obligatorio y debe permitir valores entre 0 y 30. Solo se permite 0, si el tipo de cotizante no está obligado a aportar a Cajas de Compensación Familiar  Si es menor que 30 debe haber marcado la novedad correspondiente. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getDiasCotizadosCajaCompensacion(), "0", 2, "I"));
                //40	9	192	200	N	Salario básico 	Obligatorio, sin comas ni puntos. No puede ser menor cero. Puede ser menor que 1 smlmv. Lo suministra el aportante Este valor debe ser reportado sin centavos
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSalarioBasico(), "0", 9, "I"));
                //41	1	201	201	A	Salario Integral	Se debe indicar con una X si el salario es integral o blanco si no lo es. Es responsabilidad del aportante suministrar esta información.
                fputs($ar, $this->RellenarNr('F', " ", 1, "D"));
                //42	9	202	210	N	IBC Pensión	Obligatorio. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIbcPension(), "0", 9, "I"));
                //43	9	211	219	N	IBC Salud	Obligatorio. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIbcSalud(), "0", 9, "I"));
                //44	9	220	228	N	IBC Riesgos Laborales	Obligatorio. Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIbcRiesgosProfesionales(), "0", 9, "I"));
                //45	9	229	237	N	IBC CCF	 Es un campo obligatorio para los tipos de cotizante 1, 2, 18,22, 30, 51 y 55.  Lo suministra el aportante.  Para el caso del tipo de cotizante 31 no es obligatorio cuando la cooperativa o precooperativa de trabajo asociado este exceptuada por el Ministerio del Trabajo.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIbcCaja(), "0", 9, "I"));
                //46	7	238	244	N	Tarifa de aportes pensiones	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
                fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaPension() / 100, 5, '.', ''), "0", 7, "I"));
                //47	9	245	253	N	Cotización obligatoria a Pensiones	Obligatorio. Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionPension(), "0", 9, "I"));
                //48	9	254	262	N	Aporte voluntario del afiliado al Fondo de Pensiones Obligatorias	Lo suministra el aportante. Solo aplica para las Administradoras de Pensiones del Régimen de ahorro individual
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getAporteVoluntarioFondoPensionesObligatorias(), "0", 9, "I"));
                //49	9	263	271	N	Aporte voluntario del aportante al fondo de pensiones obligatoria. 	Lo suministra el aportante. Solo aplica para las Administradoras de Pensiones del Régimen de ahorro individual
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionVoluntarioFondoPensionesObligatorias(), "0", 9, "I"));
                //50	9	272	280	N	Total cotización sistema general de pensiones	Lo calcula el sistema. Sumatoria de los campos 47, 48 y 49 del registro tipo 2.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTotalCotizacion(), "0", 9, "I"));
                //51	9	281	289	N	Aportes a Fondo de Solidaridad  Pensional- Subcuenta de solidaridad	Lo suministra el aportante cuando aplique
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getAportesFondoSolidaridadPensionalSolidaridad(), "0", 9, "I"));
                //52	9	290	298	N	Aportes a Fondo de Solidad Pensional- Subcuenta de subsistencia	Lo suministra el aportante cuando aplique
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getAportesFondoSolidaridadPensionalSubsistencia(), "0", 9, "I"));
                //53	9	299	307	N	Valor no retenido por aportes voluntarios	Lo suministra el aportante
                fputs($ar, $this->RellenarNr("", "0", 9, "I"));
                //54	7	308	314	N	Tarifa de aportes de salud	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
                fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaSalud() / 100, 5, '.', ''), "0", 7, "I"));
                //55	9	315	323	N	Cotización Obligatoria a salud	Obligatorio. Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionSalud(), "0", 9, "I"));
                //56	9	324	332	N	Valor de la UPC adicional	Debe corresponder al valor reportado en el campo 11 del archivo “información de la Base de Datos Única de Afiliados – BDUA con destino a los operadores de información”
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getValorUpcAdicional(), "0", 9, "I"));
                //57	15	333	347	A	N° autorización de la incapacidad por enfermedad general	Debe reportarse en blanco
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getNumeroAutorizacionIncapacidadEnfermedadGeneral(), " ", 15, "D"));
                //58	9	348	356	N	Valor de incapacidad por enfermedad general	Debe reportarse en blanco
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getValorIncapacidadEnfermedadGeneral(), "0", 9, "I"));
                //59	15	357	371	A	N° autorización de la licencia de maternidad o paternidad	Debe reportarse en blanco
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getNumeroAutorizacionLicenciaMaternidadPaternidad(), " ", 15, "D"));
                //60	9	372	380	N	Valor de la licencia de maternidad	Debe reportarse en cero
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getValorIncapacidadLicenciaMaternidadPaternidad(), "0", 9, "I"));
                //61	9	381	389	N	Tarifa de aportes a Riesgos Laborales	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
                fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaRiesgos() / 100, 7, '.', ''), "0", 9, "I"));
                //62	9	390	398	N	Centro de Trabajo CT	Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCentroTrabajoCodigoCt(), "0", 9, "I"));
                //63	9	399	407	N	Cotización obligatoria al Sistema General de Riesgos Laborales	Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionRiesgos(), "0", 9, "I"));
                //64	7	408	414	N	Tarifa de aportes CCF	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
                fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaCaja() / 100, 5, '.', ''), "0", 7, "I"));
                //65	9	415	423	N	Valor aporte CCF	Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionCaja(), "0", 9, "I"));
                //66	7	424	430	N	Tarifa de aportes SENA	Lo suministra el aportante
                fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaSENA() / 100, 5, '.', ''), "0", 7, "I"));
                //67	9	431	439	N	Valor aportes SENA	Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionSena(), "0", 9, "I"));
                //68	7	440	446	N	Tarifa aportes ICBF	Lo suministra el aportante
                fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaIcbf() / 100, 5, '.', ''), "0", 7, "I"));
                //69	9	447	455	N	Valor aporte ICBF	Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionIcbf(), "0", 9, "I"));
                //70	7	456	462	N	Tarifa aportes ESAP	Lo suministra el aportante
                fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaAportesESAP() / 100, 5, '.', ''), "0", 7, "I"));
                //71	9	463	471	N	Valor aporte ESAP	Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getValorAportesESAP(), "0", 9, "I"));
                //72	7	472	478	N	Tarifa aportes MEN	Lo suministra el aportante
                fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaAportesMEN() / 100, 5, '.', ''), "0", 7, "I"));
                //73	9	479	487	N	Valor aporte MEN	Lo suministra el aportante
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getValorAportesMEN(), "0", 9, "I"));
                //74	2	488	489	A	Tipo de documento del cotizante principal	Corresponde al tipo de documento del cotizante Principal que corresponde a: CC.  Cédula de ciudadanía CE.  Cédula de extranjería TI.    Tarjeta de identidad PA.  Pasaporte CD.  Carné diplomático SC.  Salvoconducto de permanencia Lo suministra el aportante Solo debe ser reportado cuando se reporte un cotizante 40.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTipoDocumentoResponsableUPC(), " ", 2, "D"));
                //75	16	490	505	A	Número de identificación del cotizante principal	Lo suministra el aportante Solo debe ser reportado cuando se reporte un cotizante 40. El operador de información validará que este campo este compuesto por letras de la A a la Z y los caracteres numéricos del Cero (0) al nueve (9). Sólo es permitido el número de identificación alfanumérico para los siguientes tipos de documentos de identidad: CE.  Cédula de Extranjería PA.  Pasaporte CD.  Carne Diplomático   Para los siguientes tipos de documento deben ser dígitos numéricos: TI.   Tarjeta de Identidad CC. Cédula de ciudadanía  SC.  Salvoconducto de permanencia
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getNumeroIdentificacionResponsableUPCAdicional(), " ", 16, "D"));
                //76	1	506	506	A	Cotizante exonerado de pago de aporte salud, SENA e ICBF - Ley 1607 de 2012 	Obligatorio.  Lo suministra el aportante. S = Si  N = No Cuando el valor del campo 43 – IBC Salud sea superior a 10 SMLMV este campo debe ser N Obligatorio.  Lo suministra el aportante. S = Si  N = No   Cuando personas naturales empleen dos o más trabajadores y el valor del campo 43 – IBC Salud sea superior a 10 SMLMV este campo debe ser N
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizanteExoneradoPagoAporteParafiscalesSalud(), " ", 1, "D"));
                if ($codigoProceso == 1 || $codigoProceso == 2) {
//                            fputs($ar, "              ");
//                            if ($codigoProceso == 1) {
//                                fputs($ar, "N");
//                            } else {
//                                fputs($ar, "S");
//                            }
                    //77	6	507	512	A	Código de la Administradora de Riesgos Laborales a la cual pertenece el afiliado	Lo suministra el aportante. Para el caso de cotizantes diferente al cotizante 3- independiente, se debe registrar el valor ingresado en el Campo 14 del registro Tipo 1 del archivo Tipo 2. Se deja en blanco cuando no sea obligatorio para el cotizante estar afiliado a una Administradora de Riesgos Laborales.
                    fputs($ar, $this->RellenarNr($codigoInterfaceRiesgos, " ", 6, "D"));
                    //78	1	513	513	A	Clase de riesgo en la que se encuentra el afiliado	Lo suministra el aportante. 1. Clase de Riesgo I 2. Clase de Riesgo II 3. Clase de Riesgo III 4. Clase de Riesgo IV  5. Clase de Riesgo V  La clase de riesgo de acuerdo a la actividad económica establecida en el Decreto 1607 de 2002 o la norma que lo sustituya o modifique
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getContratoRel()->getClasificacionRiesgoRel()->getCodigoClasificacionRiesgoPk(), " ", 1, "D"));
                } else {
                    //77	6	507	512	A	Código de la Administradora de Riesgos Laborales a la cual pertenece el afiliado	Lo suministra el aportante. Para el caso de cotizantes diferente al cotizante 3- independiente, se debe registrar el valor ingresado en el Campo 14 del registro Tipo 1 del archivo Tipo 2. Se deja en blanco cuando no sea obligatorio para el cotizante estar afiliado a una Administradora de Riesgos Laborales.
                    fputs($ar, $this->RellenarNr($codigoInterfaceRiesgos, " ", 6, "D"));
                    //78	1	513	513	A	Clase de riesgo en la que se encuentra el afiliado	Lo suministra el aportante. 1. Clase de Riesgo I 2. Clase de Riesgo II 3. Clase de Riesgo III 4. Clase de Riesgo IV  5. Clase de Riesgo V  La clase de riesgo de acuerdo a la actividad económica establecida en el Decreto 1607 de 2002 o la norma que lo sustituya o modifique
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getClaseRiesgoAfiliado(), " ", 1, "D"));
                }
                //79	1	514	514	A	Indicador tarifa especial pensiones 	Lo suministra el aportante y es: Blanco  Tarifa normal 1. Actividades de alto riesgo 2. Senadores 3. CTI 4. Aviadores
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIndicadorTarifaEspecialPensiones(), " ", 1, "D"));
                //80	10	515	524	A	Fecha de ingreso Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de ingreso. Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaIngreso(), " ", 10, "D"));
                //81	10	525	534	A	Fecha de retiro. Formato (AAAA-MM- DD).	Es obligatorio cuando se reporte la novedad de retiro.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaRetiro(), " ", 10, "D"));
                //82	10	535	544	A	Fecha Inicio  VSP Formato (AAAA-MM- DD).	Es obligatorio cuando se reporte la novedad de VSP.  Lo suministra el aportante Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaInicioVsp(), " ", 10, "D"));
                //83	10	545	554	A	Fecha Inicio SLN Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de SLN. Lo suministra el aportante.   Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaInicioSln(), " ", 10, "D"));
                //84	10	555	564	A	Fecha fin SLN Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de SLN. Lo suministra el aportante.  Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando Cuando no se reporte la novedad el campo se dejará en blanco.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaFinSln(), " ", 10, "D"));
                //85	10	565	574	A	Fecha inicio  IGE Formato (AAAA-MM- DD).	Es obligatorio cuando se reporte la novedad de IGE.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaInicioIge(), " ", 10, "D"));
                //86	10	575	584	A	Fecha fin IGE. Formato (AAAA-MM- DD) 	Es obligatorio cuando se reporte la novedad de IGE. Lo suministra el aportante.  Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaFinIge(), " ", 10, "D"));
                //87	10	585	594	A	Fecha inicio LMA Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de LMA.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaInicioLma(), " ", 10, "D"));
                //88	10	595	604	A	Fecha fin LMA Formato (AAAA-MM- DD) 	Es obligatorio cuando se reporte la novedad de LMA.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaFinLma(), " ", 10, "D"));
                //89	10	605	614	A	Fecha inicio VAC - LR Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad VAC - LR. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaInicioVacLr(), " ", 10, "D"));
                //90	10	615	624	A	Fecha fin VAC - LR Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad VAC - LR. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaFinVacLr(), " ", 10, "D"));
                //91	10	625	634	A	Fecha inicio VCT Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad VCT.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaInicioVct(), " ", 10, "D"));
                //92	10	635	644	A	Fecha fin  VCT Formato (AAAA-MM- DD). 	Cuando no se reporte la novedad el campo se dejará en blanco.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaFinVct(), " ", 10, "D"));
                //93	10	645	654	A	Fecha inicio IRL Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad IRL. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaInicioIrl(), " ", 10, "D"));
                //94	10	655	664	A	Fecha fin  IRL Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad IRL. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaFinIrl(), " ", 10, "D"));
                //95	9	665	673	N	IBC otros parafiscales diferentes a CCF	Es un campo obligatorio para los tipos de cotizante 1, 18, 20, 22, 30, 31, y 55.   Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIbcOtrosParafiscalesDiferentesCcf(), "0", 9, "I"));
                //96	3	674	676	N	Número de horas laboradas 	Es un campo obligatorio para los tipos de cotizante 1, 2, 18, 22, 30, 51 y 55.  Lo suministra el aportante.  Para el caso del tipo de cotizante 31 no es obligatorio cuando la cooperativa o precooperativa de trabajo asociado este exceptuada por el Ministerio del Trabajo.
                fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getNumeroHorasLaboradas(), "0", 3, "I"));
                //97	10	???	???	A	Fecha
                fputs($ar, $this->RellenarNr("", " ", 10, "D"));
                fputs($ar, "\n");
            }
            fclose($ar);
            $strArchivo = $strRutaArchivo . $strNombreArchivo;
            header('Content-Description: File Transfer');
            header('Content-Type: text/csv; charset=ISO-8859-15');
            header('Content-Disposition: attachment; filename=' . basename($strArchivo));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($strArchivo));
            readfile($strArchivo);
            $em->flush();
            exit;
        } else {
            return "Hay informacion sin registro para el pago de pila";
        }
    }

    /**
     * @param $arPeriodo AfiPeriodo
     * @param $form
     * @param $codigoProceso
     */
    private function generarPlanoSucursal($arPeriodo, $form, $codigoProceso)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $arEntidadRiesgos = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->find($arConfiguracionNomina->getCodigoEntidadRiesgoFk());
        $codigoInterfaceRiesgos = $arEntidadRiesgos->getCodigoInterface();
        if ($arPeriodo->getFechaPago() != null && $arPeriodo->getAnio() != null && $arPeriodo->getMes() != null && $arPeriodo->getAnioPago() != null && $arPeriodo->getMesPago() != null) {
            $arPeriodoDetallePagos = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetallePago')->findBy(array('codigoPeriodoFk' => $arPeriodo->getCodigoPeriodoPk()));
            $query = $em->createQuery($em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetallePago')->empleadoSucursales($arPeriodo->getCodigoPeriodoPk()));
            $arSucursales = $query->getResult();
            $totalSucursales = count($arSucursales);
            $totalCotizacion = 0;
            /* foreach ($arPeriodoDetallePagos as $arPeriodoDetallesumaTotales){
              $totalCotizacion += $arPeriodoDetallesumaTotales->getTotalCotizacion();
              } */
            $strRutaGeneral = $arConfiguracion->getRutaTemporal();
            if (!file_exists($strRutaGeneral)) {
                mkdir($strRutaGeneral, 0777);
            }
            $strRuta = $strRutaGeneral . "Pila / ";
            if (!file_exists($strRuta)) {
                mkdir($strRuta, 0777);
            }
            foreach ($arSucursales as $arSucursal) {
                $codSucursal = $arSucursal['codigoSucursalFk'];
                $tipo = "E";
                $tipoDoc = "NI";
                $formaPresentacion = "S";
                $nit = $arConfiguracion->getNitEmpresa();
                $cliente = $arConfiguracion->getNombreEmpresa();
                //$sucursal = $arPeriodo->getClienteRel()->getCodigoSucursal();
                $sucursal = $arSucursal['codigoSucursalFk'];
                $dv = $arPeriodo->getClienteRel()->getDigitoVerificacion();
                $formato = '1';
                $entidad = $form->get('entidad')->getData();

                //archivo plano
                $periodoPagoDiferenteSalud = $arPeriodo->getAnio() . '-' . $this->RellenarNr($arPeriodo->getMes(), "0", 2, "I");
                $periodoPagoSalud = $arPeriodo->getAnioPago() . '-' . $this->RellenarNr($arPeriodo->getMesPago(), "0", 2, "I");
                $strRutaGeneral = $arConfiguracion->getRutaTemporal();
                $strNombreArchivo = "pila" . date('YmdHis') . " - " . $codSucursal . " . txt";
                ob_clean();
                $ar = fopen($strRuta . $strNombreArchivo, "a") or
                die("Problemas en la creacion del archivo plano");
                //1	2	1	2	N	Tipo de registro	Obligatorio. Debe ser 01
                fputs($ar, $this->RellenarNr("01", " ", 2, "D"));
                //2	1	3	3	N	Modalidad de la Planilla	Obligatorio. Lo genera autómaticamente el Operador de Información.
                fputs($ar, $this->RellenarNr("1", " ", 1, "D"));
                //3	4	4	7	N	Secuencia	Obligatorio. Verificación de la secuencia ascendente. Para cada aportante inicia en 0001. Lo genera el sistema en el caso en que se estén digitando los datos directamente en la web. El aportante debe reportarlo en el caso de que los datos se suban en archivos planos.
                fputs($ar, $this->RellenarNr("0001", " ", 4, "D"));
                //4	200	8	207	A	Nombre o razón social del aportante	El registrado en el campo 1 del archivo tipo 1
                fputs($ar, $this->RellenarNr($cliente, " ", 200, "D"));
                //5	2	208	209	A	Tipo documento del aportante	El registrado en el campo 2 del archivo tipo 1
                fputs($ar, $this->RellenarNr($tipoDoc, " ", 2, "D"));
                //6	16	210	225	A	Número de identificación del aportante	El registrado en el campo 3 del archivo tipo 1
                fputs($ar, $this->RellenarNr($nit, " ", 16, "D"));
                //7	1	226	226	N	Dígito de verificación aportante	El registrado en el campo 4 del archivo tipo 1
                fputs($ar, $this->RellenarNr($dv, " ", 1, "D"));
                //8	1	227	227	A	Tipo de Planilla	Obligatorio lo suministra el aportante
                fputs($ar, $this->RellenarNr($tipo, " ", 1, "D"));
                //9	10	228	237	N	Número de Planilla asociada a esta planilla.	Debe dejarse en blanco cuando el tipo de planilla sea E, A, I, M, S, Y, T o X. En este campo se incluirá el número de la planilla del periodo correspondiente cuando el tipo de planilla sea N ó F. Cuando se utilice la planilla U por parte de la UGPP, en este campo se diligenciará el número del título del depósito judicial.
                fputs($ar, $this->RellenarNr("", " ", 10, "D"));
                //10	10	238	247	A	Fecha de pago Planilla asociada a esta planilla. (AAAA-MM-DD)	Debe dejarse en blanco cuando el tipo de planilla sea E, A, I, M, S, Y, T, o X. En este campo se incluirá la fecha de pago de la planilla del período correspondiente cuando el tipo de planilla sea N ó F. Cuando se utilice la planilla U, la UGPP diligenciará la fecha en que se constituyó el depósito judicial.
                fputs($ar, $this->RellenarNr("", " ", 10, "D"));
                //11	1	248	248	A	Forma de presentación	El registrado en el campo 10 del archivo tipo 1.
                fputs($ar, $this->RellenarNr($formaPresentacion, " ", 1, "D"));
                //12	10	249	258	A	Código de la sucursal del Aportante	El registrado en el campo 5 del archivo tipo 1.
                fputs($ar, $this->RellenarNr($sucursal, " ", 10, "D"));
                //13	40	259	298	A	Nombre de la sucursal	El registrado en el campo 6 del archivo tipo 1.
                fputs($ar, $this->RellenarNr("PAGO CONTADO", " ", 40, "D"));//ESTABA $arPeriodo->getClienteRel()->getNombreCorto()
                //14	6	299	304	A	Código de la ARL a la cual el aportante se encuentra afiliado	Lo suministra el aportante
                fputs($ar, $this->RellenarNr($codigoInterfaceRiesgos, " ", 6, "D"));
                //15	7	305	311	A	Periodo de pago para los sistemas diferentes al de salud	Obligatorio. Formato año y mes (aaaa-mm). Lo calcula el Operador de Información.
                fputs($ar, $this->RellenarNr($periodoPagoDiferenteSalud, " ", 7, "D"));
                //16	7	312	318	A	Periodo de pago para el sistema de salud	Obligatorio. Formato año y mes (aaaa-mm). Lo suministra el aportante.
                fputs($ar, $this->RellenarNr($periodoPagoSalud, " ", 7, "D"));
                //17	10	319	328	N	Número de radicación o de la Planilla Integrada de Liquidación de aportes.	Asignado por el sistema . Debe ser único por operador de información.
                fputs($ar, $this->RellenarNr("", " ", 10, "D"));
                //18	10	329	338	A	Fecha de pago (aaaa-mm-dd)	Asignado por el sistema a partir de la fecha del día efectivo del pago.
                fputs($ar, $this->RellenarNr($arPeriodo->getFechaPago()->format('Y-m-d'), " ", 10, "D"));
                //19	5	339	343	N	Número total de empleados	Obligatorio. Se debe validar que sea igual al número de cotizantes únicos incluidos en el detalle del registro tipo 2, exceptuando los que tengan 40 en el campo 5 – Tipo de cotizante.
                fputs($ar, $this->RellenarNr(count($arPeriodoDetallePagos), "0", 5, "I"));
                //20	12	344	355	N	Valor total de la nómina	Obligatorio. Lo suministra el aportante, corresponde a la sumatoria de los IBC para el pago de los aportes de parafiscales de la totalidad de los empleados. Puede ser 0 para independientes
                fputs($ar, $this->RellenarNr($totalCotizacion, "0", 12, "I"));
                //21	2	356	357	N	Tipo de aportante	Obligatorio y debe ser igual al registrado en el campo 30 del archivo tipo 1
                fputs($ar, $this->RellenarNr($formato, " ", 2, "D"));//1 o 2
                //22	2	358	359	N	Código del operador de información	Asignado por el sistema del operador de información.
                fputs($ar, $this->RellenarNr($entidad, " ", 2, "D"));// entidad por la cual paga la pila enlace operativo (89), simple otros (88)
                fputs($ar, "\n");

                foreach ($arPeriodoDetallePagos as $arPeriodoDetallePago) {
                    //1	2	1	2	N	Tipo de registro	Obligatorio. Debe ser 02.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTipoRegistro(), "0", 2, "I"));
                    //2	5	3	7	N	Secuencia	Debe iniciar en 00001 y ser secuencial para el resto de registros. Lo genera el sistema en el caso en que se estén digitando los datos directamente en la web. El aportante debe reportarlo en el caso de que los datos se suban en archivos planos.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSecuencia(), "0", 5, "I"));
                    //3	2	8	9	A	Tipo documento el cotizante	Obligatorio. Lo suministra el aportante. Los valores validos son:
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTipoDocumento(), " ", 2, "D"));
                    //4	16	10	25	A	Número de identificación del cotizante	Obligatorio. Lo suministra el aportante. El operador de información validará que este campo este compuesto por letras de la A a la Z y los caracteres numéricos del Cero (0) al nueve (9). Sólo es permitido el número de identificación alfanumérico para los siguientes tipos de documentos de identidad: CE.  Cédula de Extranjería PA.  Pasaporte CD.  Carne Diplomático. Para los siguientes tipos de documento deben ser dígitos numéricos: TI.   Tarjeta de Identidad CC. Cédula de ciudadanía  SC.  Salvoconducto de permanencia RC.  Registro Civil
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getEmpleadoRel()->getNumeroIdentificacion(), " ", 16, "D"));
                    //5	2	26	27	N	Tipo de cotizante	Obligatorio. Lo suministra el aportante. Los valores validos son:
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTipoCotizante(), "0", 2, "I"));
                    //6	2	28	29	N	Subtipo de cotizante	Obligatorio. Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSubtipoCotizante(), "0", 2, "I"));
                    //7	1	30	30	A	Extranjero no obligado a cotizar a pensiones 	Puede ser blanco o X Cuando aplique este campo los únicos tipos de documentos válidos son: CE. Cédula de extranjería PA.  Pasaporte CD.  Carné diplomático Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getExtranjeroNoObligadoCotizarPension(), " ", 1, "D"));
                    //8	1	31	31	A	Colombiano en el exterior	Puede ser blanco o X si aplica.  Este campo es utilizado cuando el tipo de documento es: CC.  Cédula de ciudadanía TI.    Tarjeta de identidad Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getColombianoResidenteExterior(), " ", 1, "D"));
                    //9	2	32	33	A	Código del Departamento de la ubicación laboral	Lo suministra el aportante. El operador de información deberá validar que este código este definido en la relación de la División Política y Administrativa – DIVIPOLA- expedida por el DANE Cuando marque el campo colombiano en el exterior se dejará  en blanco
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoDepartamentoUbicacionlaboral(), " ", 2, "D"));
                    //10	3	34	36	A	Código del Municipio de la ubicación laboral	Lo suministra el aportante. El operador de información deberá validar que este código este definido en la relación de la División Política y Administrativa – DIVIPOLA- expedida por el DANE Cuando marque el campo colombiano en el exterior se dejará en blanco
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoMunicipioUbicacionlaboral(), " ", 3, "D"));
                    //11	20	37	56	A	Primer apellido	Obligatorio. Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getPrimerApellido(), " ", 20, "D"));
                    //12	30	57	86	A	Segundo apellido	Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSegundoApellido(), " ", 30, "D"));
                    //13	20	87	106	A	Primer nombre	Obligatorio. Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getPrimerNombre(), " ", 20, "D"));
                    //14	30	107	136	A	Segundo nombre	Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSegundoNombre(), " ", 30, "D"));
                    //15	1	137	137	A	ING: ingreso	 Puede ser un blanco, R, X o C. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIngreso(), " ", 1, "D"));
                    //16	1	138	138	A	RET: retiro	Puede ser un blanco, P, R, X o C. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getRetiro(), " ", 1, "D"));
                    //17	1	139	139	A	TDE: Traslado desde otra EPS ó EOC	Puede ser un blanco o X. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTrasladoDesdeOtraEps(), " ", 1, "D"));
                    //18	1	140	140	A	TAE: Traslado a otra EPS ó EOC	Puede ser un blanco o X. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTrasladoAOtraEps(), " ", 1, "D"));
                    //19	1	141	141	A	TDP: Traslado desde otra Administradora de Pensiones	Puede ser un blanco o X. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTrasladoDesdeOtraPension(), " ", 1, "D"));
                    //20	1	142	142	A	TAP: Traslado a otra  administradora de pensiones	Puede ser un blanco o X. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTrasladoAOtraPension(), " ", 1, "D"));
                    //21	1	143	143	A	VSP: Variación permantente de salario	Puede ser un blanco o X. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getVariacionPermanenteSalario(), " ", 1, "D"));
                    //22	1	144	144	A	Correcciones	Puede ser un blanco, A o C. Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCorrecciones(), " ", 1, "D"));
                    //23	1	145	145	A	VST: Variación transitoria del salario	Puede ser un blanco o X. Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getVariacionTransitoriaSalario(), " ", 1, "D"));
                    //24	1	146	146	A	SLN: suspensión temporal del contrato de trabajo o licencia no remunerada o comisión de servicios	Puede ser un blanco, X o C. Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSuspensionTemporalContratoLicenciaServicios(), " ", 1, "D"));
                    //25	1	147	147	A	IGE: Incapacidad Temporal por Enfermedad General	Puede ser un blanco o X. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIncapacidadGeneral(), " ", 1, "D"));
                    //26	1	148	148	A	LMA: Licencia de Maternidad  o de Paternidad	Puede ser un blanco o X. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getLicenciaMaternidad(), " ", 1, "D"));
                    //27	1	149	149	A	VAC- LR: Vacaciones, Licencia Remunerada 	Puede ser: X:   Vacaciones L:    Licencia remunerada Blanco: Cuando no aplique esta novedad.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getVacaciones(), " ", 1, "D"));
                    //28	1	150	150	A	AVP: Aporte Voluntario	Puede ser un blanco o X. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getAporteVoluntario(), " ", 1, "D"));
                    //29	1	151	151	A	VCT: Variación centros de trabajo	Puede ser un blanco o X. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getVariacionCentrosTrabajo(), " ", 1, "D"));
                    //30	2	152	153	N	IRL:Dias de  Incapacidad por accidente de trabajo o enfermedad laboral	Puede ser cero o el número de días (entre 01 y 30). Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIncapacidadAccidenteTrabajoEnfermedadProfesional(), "0", 2, "I"));
                    //31	6	154	159	A	Código de la Administradora de Fondo de Pensiones a la cual pertenece el afiliado	Es un campo obligatorio y solo se permite blanco, si el tipo de cotizante o el subtipo de cotizante no es obligado a aportar al Sistema General de Pensiones. Se debe utilizar un código válido y este lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoEntidadPensionPertenece(), " ", 6, "D"));
                    //32	6	160	165	A	Código de la Administradora de Fondo de Pensiones a la cual se tralada el afiliado	Obligatorio si la novedad es traslado a otra administradora de fondo de pensiones. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoEntidadPensionTraslada(), " ", 6, "D"));
                    //33	6	166	171	A	Código EPS ó EOC a la cual pertenece el afiliado	Es un campo obligatorio. Se debe utilizar un código válido y éste lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoEntidadSaludPertenece(), " ", 6, "D"));
                    //34	6	172	177	A	Código EPS ó EOC a la cual se traslada el afiliado	Obligatorio si en el campo 18 del registro tipo 2 se marca X. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoEntidadSaludTraslada(), " ", 6, "D"));
                    //35	6	178	183	A	Código CCF a la que pertenece el afiliado	Obligatorio y solo se permite blanco, si el tipo de cotizante no es obligado a aportar a CCF. Se debe utilizar un código válido y este lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoEntidadCajaPertenece(), " ", 6, "D"));
                    //36	2	184	185	N	Número de días cotizados a pensión	Obligatorio y debe permitir valores entre 0 y 30. Solo se permite 0, si el tipo de cotizante o subtipo de cotizante no está obligado a aportar pensiones. Si es menor que 30 debe haber marcado una novedad de ingreso o retiro. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getDiasCotizadosPension(), "0", 2, "I"));
                    //37	2	186	187	N	Número de días cotizados a salud	Obligatorio y debe permitir valores entre 0 y 30. Si es menor que 30 debe haber marcado  una  novedad  de ingreso o retiro. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getDiasCotizadosSalud(), "0", 2, "I"));
                    //38	2	188	189	N	Número de días cotizados a Riesgos Laborales	Obligatorio y debe permitir valores entre 0 y 30. Solo se permite 0, si el tipo de cotizante no está obligado a aportar al Sistema General de Riesgos Laborales, o si en los campos 25, 26, 27, del registro tipo 2 se ha marcado X o el campo 30 del registro tipo 2 es mayor que 0. Si es menor que 30 debe haber marcado la novedad correspondiente. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getDiasCotizadosRiesgosProfesionales(), "0", 2, "I"));
                    //39	2	190	191	N	Número de días cotizados a Caja de Compensación Familiar	Obligatorio y debe permitir valores entre 0 y 30. Solo se permite 0, si el tipo de cotizante no está obligado a aportar a Cajas de Compensación Familiar  Si es menor que 30 debe haber marcado la novedad correspondiente. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getDiasCotizadosCajaCompensacion(), "0", 2, "I"));
                    //40	9	192	200	N	Salario básico 	Obligatorio, sin comas ni puntos. No puede ser menor cero. Puede ser menor que 1 smlmv. Lo suministra el aportante Este valor debe ser reportado sin centavos
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSalarioBasico(), "0", 9, "I"));
                    //41	1	201	201	A	Salario Integral	Se debe indicar con una X si el salario es integral o blanco si no lo es. Es responsabilidad del aportante suministrar esta información.
                    fputs($ar, $this->RellenarNr('F', " ", 1, "D"));
                    //42	9	202	210	N	IBC Pensión	Obligatorio. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIbcPension(), "0", 9, "I"));
                    //43	9	211	219	N	IBC Salud	Obligatorio. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIbcSalud(), "0", 9, "I"));
                    //44	9	220	228	N	IBC Riesgos Laborales	Obligatorio. Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIbcRiesgosProfesionales(), "0", 9, "I"));
                    //45	9	229	237	N	IBC CCF	 Es un campo obligatorio para los tipos de cotizante 1, 2, 18,22, 30, 51 y 55.  Lo suministra el aportante.  Para el caso del tipo de cotizante 31 no es obligatorio cuando la cooperativa o precooperativa de trabajo asociado este exceptuada por el Ministerio del Trabajo.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIbcCaja(), "0", 9, "I"));
                    //46	7	238	244	N	Tarifa de aportes pensiones	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
                    fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaPension() / 100, 5, '.', ''), "0", 7, "I"));
                    //47	9	245	253	N	Cotización obligatoria a Pensiones	Obligatorio. Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionPension(), "0", 9, "I"));
                    //48	9	254	262	N	Aporte voluntario del afiliado al Fondo de Pensiones Obligatorias	Lo suministra el aportante. Solo aplica para las Administradoras de Pensiones del Régimen de ahorro individual
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getAporteVoluntarioFondoPensionesObligatorias(), "0", 9, "I"));
                    //49	9	263	271	N	Aporte voluntario del aportante al fondo de pensiones obligatoria. 	Lo suministra el aportante. Solo aplica para las Administradoras de Pensiones del Régimen de ahorro individual
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionVoluntarioFondoPensionesObligatorias(), "0", 9, "I"));
                    //50	9	272	280	N	Total cotización sistema general de pensiones	Lo calcula el sistema. Sumatoria de los campos 47, 48 y 49 del registro tipo 2.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTotalCotizacion(), "0", 9, "I"));
                    //51	9	281	289	N	Aportes a Fondo de Solidaridad  Pensional- Subcuenta de solidaridad	Lo suministra el aportante cuando aplique
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getAportesFondoSolidaridadPensionalSolidaridad(), "0", 9, "I"));
                    //52	9	290	298	N	Aportes a Fondo de Solidad Pensional- Subcuenta de subsistencia	Lo suministra el aportante cuando aplique
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getAportesFondoSolidaridadPensionalSubsistencia(), "0", 9, "I"));
                    //53	9	299	307	N	Valor no retenido por aportes voluntarios	Lo suministra el aportante
                    fputs($ar, $this->RellenarNr("", "0", 9, "I"));
                    //54	7	308	314	N	Tarifa de aportes de salud	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
                    fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaSalud() / 100, 5, '.', ''), "0", 7, "I"));
                    //55	9	315	323	N	Cotización Obligatoria a salud	Obligatorio. Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionSalud(), "0", 9, "I"));
                    //56	9	324	332	N	Valor de la UPC adicional	Debe corresponder al valor reportado en el campo 11 del archivo “información de la Base de Datos Única de Afiliados – BDUA con destino a los operadores de información”
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getValorUpcAdicional(), "0", 9, "I"));
                    //57	15	333	347	A	N° autorización de la incapacidad por enfermedad general	Debe reportarse en blanco
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getNumeroAutorizacionIncapacidadEnfermedadGeneral(), " ", 15, "D"));
                    //58	9	348	356	N	Valor de incapacidad por enfermedad general	Debe reportarse en blanco
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getValorIncapacidadEnfermedadGeneral(), "0", 9, "I"));
                    //59	15	357	371	A	N° autorización de la licencia de maternidad o paternidad	Debe reportarse en blanco
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getNumeroAutorizacionLicenciaMaternidadPaternidad(), " ", 15, "D"));
                    //60	9	372	380	N	Valor de la licencia de maternidad	Debe reportarse en cero
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getValorIncapacidadLicenciaMaternidadPaternidad(), "0", 9, "I"));
                    //61	9	381	389	N	Tarifa de aportes a Riesgos Laborales	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
                    fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaRiesgos() / 100, 7, '.', ''), "0", 9, "I"));
                    //62	9	390	398	N	Centro de Trabajo CT	Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCentroTrabajoCodigoCt(), "0", 9, "I"));
                    //63	9	399	407	N	Cotización obligatoria al Sistema General de Riesgos Laborales	Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionRiesgos(), "0", 9, "I"));
                    //64	7	408	414	N	Tarifa de aportes CCF	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
                    fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaCaja() / 100, 5, '.', ''), "0", 7, "I"));
                    //65	9	415	423	N	Valor aporte CCF	Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionCaja(), "0", 9, "I"));
                    //66	7	424	430	N	Tarifa de aportes SENA	Lo suministra el aportante
                    fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaSENA() / 100, 5, '.', ''), "0", 7, "I"));
                    //67	9	431	439	N	Valor aportes SENA	Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionSena(), "0", 9, "I"));
                    //68	7	440	446	N	Tarifa aportes ICBF	Lo suministra el aportante
                    fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaIcbf() / 100, 5, '.', ''), "0", 7, "I"));
                    //69	9	447	455	N	Valor aporte ICBF	Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionIcbf(), "0", 9, "I"));
                    //70	7	456	462	N	Tarifa aportes ESAP	Lo suministra el aportante
                    fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaAportesESAP() / 100, 5, '.', ''), "0", 7, "I"));
                    //71	9	463	471	N	Valor aporte ESAP	Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getValorAportesESAP(), "0", 9, "I"));
                    //72	7	472	478	N	Tarifa aportes MEN	Lo suministra el aportante
                    fputs($ar, $this->RellenarNr(number_format($arPeriodoDetallePago->getTarifaAportesMEN() / 100, 5, '.', ''), "0", 7, "I"));
                    //73	9	479	487	N	Valor aporte MEN	Lo suministra el aportante
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getValorAportesMEN(), "0", 9, "I"));
                    //74	2	488	489	A	Tipo de documento del cotizante principal	Corresponde al tipo de documento del cotizante Principal que corresponde a: CC.  Cédula de ciudadanía CE.  Cédula de extranjería TI.    Tarjeta de identidad PA.  Pasaporte CD.  Carné diplomático SC.  Salvoconducto de permanencia Lo suministra el aportante Solo debe ser reportado cuando se reporte un cotizante 40.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTipoDocumentoResponsableUPC(), " ", 2, "D"));
                    //75	16	490	505	A	Número de identificación del cotizante principal	Lo suministra el aportante Solo debe ser reportado cuando se reporte un cotizante 40. El operador de información validará que este campo este compuesto por letras de la A a la Z y los caracteres numéricos del Cero (0) al nueve (9). Sólo es permitido el número de identificación alfanumérico para los siguientes tipos de documentos de identidad: CE.  Cédula de Extranjería PA.  Pasaporte CD.  Carne Diplomático   Para los siguientes tipos de documento deben ser dígitos numéricos: TI.   Tarjeta de Identidad CC. Cédula de ciudadanía  SC.  Salvoconducto de permanencia
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getNumeroIdentificacionResponsableUPCAdicional(), " ", 16, "D"));
                    //76	1	506	506	A	Cotizante exonerado de pago de aporte salud, SENA e ICBF - Ley 1607 de 2012 	Obligatorio.  Lo suministra el aportante. S = Si  N = No Cuando el valor del campo 43 – IBC Salud sea superior a 10 SMLMV este campo debe ser N Obligatorio.  Lo suministra el aportante. S = Si  N = No   Cuando personas naturales empleen dos o más trabajadores y el valor del campo 43 – IBC Salud sea superior a 10 SMLMV este campo debe ser N
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizanteExoneradoPagoAporteParafiscalesSalud(), " ", 1, "D"));
                    //77	6	507	512	A	Código de la Administradora de Riesgos Laborales a la cual pertenece el afiliado	Lo suministra el aportante. Para el caso de cotizantes diferente al cotizante 3- independiente, se debe registrar el valor ingresado en el Campo 14 del registro Tipo 1 del archivo Tipo 2. Se deja en blanco cuando no sea obligatorio para el cotizante estar afiliado a una Administradora de Riesgos Laborales.
                    fputs($ar, $this->RellenarNr($codigoInterfaceRiesgos, " ", 6, "D"));
                    //78	1	513	513	A	Clase de riesgo en la que se encuentra el afiliado	Lo suministra el aportante. 1. Clase de Riesgo I 2. Clase de Riesgo II 3. Clase de Riesgo III 4. Clase de Riesgo IV  5. Clase de Riesgo V  La clase de riesgo de acuerdo a la actividad económica establecida en el Decreto 1607 de 2002 o la norma que lo sustituya o modifique
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getClaseRiesgoAfiliado(), " ", 1, "D"));
                    //79	1	514	514	A	Indicador tarifa especial pensiones 	Lo suministra el aportante y es: Blanco  Tarifa normal 1. Actividades de alto riesgo 2. Senadores 3. CTI 4. Aviadores
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIndicadorTarifaEspecialPensiones(), " ", 1, "D"));
                    //80	10	515	524	A	Fecha de ingreso Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de ingreso. Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaIngreso(), " ", 10, "D"));
                    //81	10	525	534	A	Fecha de retiro. Formato (AAAA-MM- DD).	Es obligatorio cuando se reporte la novedad de retiro.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaRetiro(), " ", 10, "D"));
                    //82	10	535	544	A	Fecha Inicio  VSP Formato (AAAA-MM- DD).	Es obligatorio cuando se reporte la novedad de VSP.  Lo suministra el aportante Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaInicioVsp(), " ", 10, "D"));
                    //83	10	545	554	A	Fecha Inicio SLN Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de SLN. Lo suministra el aportante.   Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaInicioSln(), " ", 10, "D"));
                    //84	10	555	564	A	Fecha fin SLN Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de SLN. Lo suministra el aportante.  Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando Cuando no se reporte la novedad el campo se dejará en blanco.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaFinSln(), " ", 10, "D"));
                    //85	10	565	574	A	Fecha inicio  IGE Formato (AAAA-MM- DD).	Es obligatorio cuando se reporte la novedad de IGE.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaInicioIge(), " ", 10, "D"));
                    //86	10	575	584	A	Fecha fin IGE. Formato (AAAA-MM- DD) 	Es obligatorio cuando se reporte la novedad de IGE. Lo suministra el aportante.  Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaFinIge(), " ", 10, "D"));
                    //87	10	585	594	A	Fecha inicio LMA Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de LMA.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaInicioLma(), " ", 10, "D"));
                    //88	10	595	604	A	Fecha fin LMA Formato (AAAA-MM- DD) 	Es obligatorio cuando se reporte la novedad de LMA.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaFinLma(), " ", 10, "D"));
                    //89	10	605	614	A	Fecha inicio VAC - LR Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad VAC - LR. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaInicioVacLr(), " ", 10, "D"));
                    //90	10	615	624	A	Fecha fin VAC - LR Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad VAC - LR. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaFinVacLr(), " ", 10, "D"));
                    //91	10	625	634	A	Fecha inicio VCT Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad VCT.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaInicioVct(), " ", 10, "D"));
                    //92	10	635	644	A	Fecha fin  VCT Formato (AAAA-MM- DD). 	Cuando no se reporte la novedad el campo se dejará en blanco.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaFinVct(), " ", 10, "D"));
                    //93	10	645	654	A	Fecha inicio IRL Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad IRL. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaInicioIrl(), " ", 10, "D"));
                    //94	10	655	664	A	Fecha fin  IRL Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad IRL. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getFechaFinIrl(), " ", 10, "D"));
                    //95	9	665	673	N	IBC otros parafiscales diferentes a CCF	Es un campo obligatorio para los tipos de cotizante 1, 18, 20, 22, 30, 31, y 55.   Lo suministra el aportante.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIbcOtrosParafiscalesDiferentesCcf(), "0", 9, "I"));
                    //96	3	674	676	N	Número de horas laboradas 	Es un campo obligatorio para los tipos de cotizante 1, 2, 18, 22, 30, 51 y 55.  Lo suministra el aportante.  Para el caso del tipo de cotizante 31 no es obligatorio cuando la cooperativa o precooperativa de trabajo asociado este exceptuada por el Ministerio del Trabajo.
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getNumeroHorasLaboradas(), "0", 3, "I"));
                    //97	10	???	???	A	Fecha
                    fputs($ar, $this->RellenarNr("", " ", 10, "D"));
                    fputs($ar, "\n");
                }
                //fclose($ar);
                //$strArchivo = $strRuta.$strNombreArchivo;
                //header('Content-Description: File Transfer');
                //header('Content-Type: text/csv; charset=ISO-8859-15');
                //header('Content-Disposition: attachment; filename='.basename($strArchivo));
                //header('Expires: 0');
                //header('Cache-Control: must-revalidate');
                //header('Pragma: public');
                //header('Content-Length: ' . filesize($strArchivo));
                //readfile($strArchivo);
                //$em->flush();
                //exit;*/
            }
            $strRutaZip = $strRutaGeneral . 'Pila.zip';
            $this->comprimir($strRuta, $strRutaZip);
            $dir = opendir($strRuta);
            while ($current = readdir($dir)) {
                if ($current != " . " && $current != " ..") {
                    unlink($strRuta . $current);
                }
            }
            rmdir($strRuta);
            $strArchivo = $strRutaZip;
            header('Content-Description: File Transfer');
            header('Content-Type: text/csv; charset=ISO-8859-15');
            header('Content-Disposition: attachment; filename=' . basename($strArchivo));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($strArchivo));
            readfile($strArchivo);
            unlink($strRutaZip);
        } else {
            return "Hay informacion sin registro para el pago de pila";
        }
    }

}
