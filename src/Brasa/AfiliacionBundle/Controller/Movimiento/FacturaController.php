<?php
namespace Brasa\AfiliacionBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Brasa\AfiliacionBundle\Form\Type\AfiFacturaType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;


class FacturaController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/movimiento/factura", name="brs_afi_movimiento_factura")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 130, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');

            if($request->request->get('OpGenerar')) {
                $codigoFactura = $request->request->get('OpGenerar');
                $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->generar($codigoFactura);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura'));
            }

            if($request->request->get('OpDeshacer')) {
                $codigoFactura = $request->request->get('OpDeshacer');
                $strSql = "DELETE FROM afi_factura_detalle WHERE codigo_factura_fk = " . $codigoFactura;
                $em->getConnection()->executeQuery($strSql);
                $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
                $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);
                $arFactura->setEstadoGenerado(0);
                $em->persist($arFactura);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura'));
            }
            if ($form->get('BtnEliminar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 130, 4)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                try{
                    $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->eliminar($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_afi_movimiento_factura'));
                 } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el registro, tiene detalles asociados', $this);
                 }   
                //return $this->redirect($this->generateUrl('brs_afi_movimiento_factura'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {                
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();                
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcel();
            }
        }

        $arFacturas = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Factura:lista.html.twig', array(
            'arFacturas' => $arFacturas,
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/factura/nuevo/{codigoFactura}", name="brs_afi_movimiento_factura_nuevo")
     */
    public function nuevoAction(Request $request, $codigoFactura = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();        
        if($codigoFactura != '' && $codigoFactura != '0') {
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 130, 3)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
            $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);
        } else{
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 130, 2)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
            $arFactura->setFecha(new \DateTime('now'));
            $arFactura->setFechaVence(new \DateTime('now'));
        }
        $form = $this->createForm(new AfiFacturaType, $arFactura);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arFactura = $form->getData();
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {
                $arCliente = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
                $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $arrControles['txtNit']));
                if($arCliente) {
                    $arFactura->setClienteRel($arCliente);
                    $dateFechaVence = $objFunciones->sumarDiasFecha($arFactura->getClienteRel()->getPlazoPago(), $arFactura->getFecha());
                    $arFactura->setFechaVence($dateFechaVence);
                    $arUsuario = $this->getUser();
                    $arFactura->setUsuario($arUsuario->getUserName());
                    if($form->get('facturaTipoRel')->getData()->getCodigoFacturaTipoPk() == 3){
                        $total = intval($form->get('valorPorEmpleado')->getData()) * intval($form->get('cantidad')->getData());
                        $arFactura->setSubTotal($total);
                        $arFactura->setTotal($total);
                    }
                    $em->persist($arFactura);
                    $em->flush();
                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_nuevo', array('codigoFactura' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $arFactura->getCodigoFacturaPk())));
                    }
                } else {
                    $objMensaje->Mensaje("error", "El cliente no existe", $this);
                }
            }
        }
        return $this->render('BrasaAfiliacionBundle:Movimiento/Factura:nuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/factura/detalle/{codigoFactura}", name="brs_afi_movimiento_factura_detalle")
     */
    public function detalleAction(Request $request, $codigoFactura = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);
        $validar = '';
        $form = $this->formularioDetalle($arFactura);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 130, 5)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoFactura);
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->autorizar($codigoFactura);
                if($strResultado != '') {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }
            if($form->get('BtnAnular')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 130, 9)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 130, 9)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->anular($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 130, 6)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->desAutorizar($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }
            if($form->get('BtnImprimir')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 130, 10)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->imprimir($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                } else {
                    if($arFactura->getCodigoFacturaTipoFk() == 1) {
                        $objFactura = new \Brasa\AfiliacionBundle\Formatos\Factura();
                        $objFactura->Generar($this, $codigoFactura);
                    } else {
                        $facturaDetalles = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));
                        $facturaDetallesCursos = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleCurso')->findBy(array('codigoFacturaFk' => $codigoFactura));
                        $facturaDetallesAfiliaciones = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleAfiliacion')->findBy(array('codigoFacturaFk' => $codigoFactura));
                        if ($facturaDetalles != null && $facturaDetallesCursos == null && $facturaDetallesAfiliaciones == null){
                            $objCuentaCobro = new \Brasa\AfiliacionBundle\Formatos\CuentaCobroHorus2();
                            $objCuentaCobro->Generar($this, $codigoFactura);
                        }
                        if ($facturaDetallesCursos != null && $facturaDetalles == null && $facturaDetallesAfiliaciones == null){
                            $objCuentaCobro = new \Brasa\AfiliacionBundle\Formatos\CuentaCobroHorus();
                            $objCuentaCobro->Generar($this, $codigoFactura);
                        }
                        if ($facturaDetallesAfiliaciones != null && $facturaDetalles == null && $facturaDetallesCursos == null){
                            $objCuentaCobro = new \Brasa\AfiliacionBundle\Formatos\CuentaCobroAfiliacion();
                            $objCuentaCobro->Generar($this, $codigoFactura);
                        }

                    }

                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }
            if($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoFactura);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }
            if ($form->get('BtnDetalleEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->liquidar($codigoFactura);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }
            if ($form->get('BtnDetalleAfiliacionEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleAfiliacion')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->liquidarAfiliacion($codigoFactura);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }
            if ($form->get('BtnDetalleCursoEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleCurso')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->liquidar($codigoFactura);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }

        }
        $dql = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->listaDQL($codigoFactura);
        $arFacturaDetalles = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        $dql = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleCurso')->listaDQL($codigoFactura);
        $arFacturaDetalleCursos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        $dql = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleAfiliacion')->listaDQL($codigoFactura);
        $arFacturaDetalleAfiliaciones = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Factura:detalle.html.twig', array(
            'arFactura' => $arFactura,
            'arFacturaDetalles' => $arFacturaDetalles,
            'arFacturaDetalleCursos' => $arFacturaDetalleCursos,
            'arFacturaDetalleAfiliaciones' => $arFacturaDetalleAfiliaciones,
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/factura/detalle/nuevo/{codigoFactura}", name="brs_afi_movimiento_factura_detalle_nuevo")
     */
    public function detalleNuevoAction(Request $request, $codigoFactura = '') {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);
        $form = $this->formularioDetalleNuevo();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoPeriodo) {
                    $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
                    if($arPeriodo){
                        $arPeriodo->setEstadoFacturado(1);
                        $em->persist($arPeriodo);
                        $arFacturaDetalle = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle();
                        $arFacturaDetalle->setFacturaRel($arFactura);
                        $arFacturaDetalle->setPeriodoRel($arPeriodo);
                        $arFacturaDetalle->setFechaDesde($arPeriodo->getFechaDesde());
                        $arFacturaDetalle->setFechaHasta($arPeriodo->getFechaHasta());
                        $arFacturaDetalle->setPrecio($arPeriodo->getTotal());
                        $arFacturaDetalle->setSubtotal($arPeriodo->getSubtotal());
                        $arFacturaDetalle->setIva($arPeriodo->getIva());
                        $arFacturaDetalle->setTotal($arPeriodo->getTotal());
                        $arFacturaDetalle->setPension($arPeriodo->getPension());
                        $arFacturaDetalle->setSalud($arPeriodo->getSalud());
                        $arFacturaDetalle->setRiesgos($arPeriodo->getRiesgos());
                        $arFacturaDetalle->setCaja($arPeriodo->getCaja());
                        $arFacturaDetalle->setSena($arPeriodo->getSena());
                        $arFacturaDetalle->setIcbf($arPeriodo->getIcbf());
                        $arFacturaDetalle->setAdministracion($arPeriodo->getAdministracion());
                        $arFacturaDetalle->setInteresMora($arPeriodo->getInteresMora());
                        $em->persist($arFacturaDetalle);
                    }
                }
                $em->flush();
                $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->liquidar($codigoFactura);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $dqlPeriodosPendientes = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->pendienteDql($arFactura->getCodigoClienteFk());
        $arPeriodos = $paginator->paginate($em->createQuery($dqlPeriodosPendientes), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Factura:detalleNuevo.html.twig', array(
            'arFactura' => $arFactura,
            'arPeriodos' => $arPeriodos,
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/factura/detalle/curso/nuevo/{codigoFactura}", name="brs_afi_movimiento_factura_detalle_curso_nuevo")
     */
    public function detalleCursoNuevoAction(Request $request, $codigoFactura = '') {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);
        $form = $this->formularioDetalleNuevo();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoCurso) {
                    $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
                    $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);
                    $arFacturaDetalleCurso = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso();
                    $arFacturaDetalleCurso->setFacturaRel($arFactura);
                    $arFacturaDetalleCurso->setCursoRel($arCurso);
                    $arFacturaDetalleCurso->setPrecio($arCurso->getTotal());
                    $em->persist($arFacturaDetalleCurso);
                    $arCurso->setEstadoFacturado(1);
                    $em->persist($arCurso);
                }
                $em->flush();
                $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->liquidar($codigoFactura);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $dqlCursosPendientes = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->pendienteDql($arFactura->getCodigoClienteFk());
        $arCursos = $paginator->paginate($em->createQuery($dqlCursosPendientes), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Factura:detalleCursoNuevo.html.twig', array(
            'arFactura' => $arFactura,
            'arCursos' => $arCursos,
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/factura/detalle/afiliacion/nuevo/{codigoFactura}", name="brs_afi_movimiento_factura_detalle_afiliacion_nuevo")
     */
    public function detalleAfiliacionNuevoAction(Request $request, $codigoFactura = '') {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);
        $form = $this->formularioDetalleNuevo();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoContrato) {
                    $arContrato = new \Brasa\AfiliacionBundle\Entity\AfiContrato();
                    $arContrato = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->find($codigoContrato);
                    $arFacturaDetalleAfiliacion = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion();
                    $arFacturaDetalleAfiliacion->setFacturaRel($arFactura);
                    $arFacturaDetalleAfiliacion->setContratoRel($arContrato);
                    $arFacturaDetalleAfiliacion->setPrecio($arFactura->getClienteRel()->getAfiliacion());
                    $arFacturaDetalleAfiliacion->setTotal($arFactura->getClienteRel()->getAfiliacion());
                    $em->persist($arFacturaDetalleAfiliacion);
                    $arContrato->setEstadoGeneradoCtaCobrar(1);
                    $em->persist($arContrato);
                }
                $em->flush();
                $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->liquidarAfiliacion($codigoFactura);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $dqlAfiliacionesPendientes = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->pendienteAfiliacionDql($arFactura->getCodigoClienteFk());
        $arContratos = $paginator->paginate($em->createQuery($dqlAfiliacionesPendientes), $request->query->get('page', 1), 300);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Factura:detalleAfiliacionNuevo.html.twig', array(
            'arFactura' => $arFactura,
            'arContratos' => $arContratos,
            'form' => $form->createView()));
    }

    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->listaDQL(
                $session->get('filtroCodigoCliente'),
                $session->get('filtroEstadoAutorizado'),
                $session->get('filtroEstadoAnulado'),
                $session->get('filtroEstadoAfiliado'),
                $strFechaDesde = $session->get('filtroDesde'),
                $strFechaHasta = $session->get('filtroHasta')
                );
    }

    private function filtrar ($form) {
        $session = new session;
        $session->set('filtroEstadoAnulado', $form->get('estadoAnulado')->getData());
        $session->set('filtroEstadoAutorizado', $form->get('estadoAutorizado')->getData());
        $session->set('filtroEstadoAfiliado', $form->get('estadoAfiliado')->getData());
        $session->set('filtroNit', $form->get('TxtNit')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null){
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d')); 
        }
        //$this->lista();
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }
        } else {
            $session->set('filtroCodigoCliente', null);
        }
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroDesde') != "") {
            $strFechaDesde = $session->get('filtroDesde');
        }
        if($session->get('filtroHasta') != "") {
            $strFechaHasta = $session->get('filtroHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('TxtNit', textType::class)
            ->add('TxtNombreCliente', textType::class)       
            ->add('estadoAutorizado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroEstadoAutorizado')))
            ->add('estadoAnulado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroEstadoAnulado')))
            ->add('estadoAfiliado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroEstadoAfiliado')))
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta)) 
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => true);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonDetalleCursoEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleAfiliacionEliminar = array('label' => 'Eliminar', 'disabled' => false);

        if($ar->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonAnular['disabled'] = false;
            $arrBotonDetalleCursoEliminar['disabled'] = true;
            $arrBotonDetalleAfiliacionEliminar['disabled'] = true;
            if($ar->getEstadoAnulado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonAnular['disabled'] = true;
            }
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
        }

        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)
                    ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                    ->add('BtnAnular', SubmitType::class, $arrBotonAnular)
                    ->add('BtnDetalleActualizar', SubmitType::class, $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', SubmitType::class, $arrBotonDetalleEliminar)
                    ->add('BtnDetalleCursoEliminar', SubmitType::class, $arrBotonDetalleEliminar)
                    ->add('BtnDetalleAfiliacionEliminar', SubmitType::class, $arrBotonDetalleAfiliacionEliminar)
                    ->getForm();
        return $form;
    }

    private function formularioDetalleNuevo() {
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm();
        return $form;
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        }
        for($col = 'I'; $col !== 'L'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'TIPO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'VENCE')
                    ->setCellValue('F1', 'CLIENTE')
                    ->setCellValue('G1', 'NIT')
                    ->setCellValue('H1', 'SOPORTE')
                    ->setCellValue('I1', 'SUBTOTAL')
                    ->setCellValue('J1', 'IVA')
                    ->setCellValue('K1', 'TOTAL')
                    ->setCellValue('L1', 'AUTORIZADO')
                    ->setCellValue('M1', 'ANULADO')
                    ->setCellValue('N1', 'USUARIO')
                    ->setCellValue('O1', 'COMENTARIOS')
                    ->setCellValue('P1', 'AFILIACION');

        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arFacturas = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        $arFacturas = $query->getResult();

        foreach ($arFacturas as $arFactura) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFactura->getCodigoFacturaPk())
                    ->setCellValue('B' . $i, $arFactura->getNumero())
                    ->setCellValue('C' . $i, $arFactura->getFacturaTipoRel()->getNombre())
                    ->setCellValue('D' . $i, $arFactura->getFecha()->format('Y-m-d'))
                    ->setCellValue('E' . $i, $arFactura->getFechaVence()->format('Y-m-d'))
                    ->setCellValue('F' . $i, $arFactura->getClienteRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arFactura->getClienteRel()->getNit())
                    ->setCellValue('H' . $i, $arFactura->getSoporte())
                    ->setCellValue('I' . $i, $arFactura->getSubTotal())
                    ->setCellValue('J' . $i, $arFactura->getIva())
                    ->setCellValue('K' . $i, $arFactura->getTotal())
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arFactura->getEstadoAutorizado()))
                    ->setCellValue('M' . $i, $objFunciones->devuelveBoolean($arFactura->getEstadoAnulado()))
                    ->setCellValue('N' . $i, $arFactura->getUsuario())
                    ->setCellValue('O' . $i, $arFactura->getComentarios())
                    ->setCellValue('P' . $i, $objFunciones->devuelveBoolean($arFactura->getAfiliacion()));
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Factura');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Facturas.xlsx"');
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

    private function actualizarDetalle($arrControles, $codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arFacturaDetalle = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle;
                $arFacturaDetalle = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->find($intCodigo);
                $arFacturaDetalle->setPrecio($arrControles['TxtPrecio'.$intCodigo]);
                $em->persist($arFacturaDetalle);
            }
            $em->flush();
            $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->liquidar($codigoFactura);
        }
    }

}