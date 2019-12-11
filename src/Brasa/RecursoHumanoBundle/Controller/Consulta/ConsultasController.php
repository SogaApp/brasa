<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class ConsultasController extends Controller
{
    var $strSqlCreditoLista = "";
    var $strSqlServiciosPorCobrarLista = "";
    var $strSqlPagoLista = "";
    var $strSqlIncapacidadesLista = "";
    var $strSqlIncapacidadesCobrarLista = "";
    var $strSqlAportesLista = "";
    var $strSqlVacacionesPagarLista = "";
    var $strSqlFechaTerminacionLista = "";
    var $strSqlIngresosContratosLista = "";
    var $strSqlContratosPeriodoLista = "";
    var $strSqlCostosIbcLista = "";
    var $strSqlPagoPendientesBancoLista = "";
    var $strSqlEmpleadosLista = "";
    var $strSqlDotacionesPendientesLista = "";
    var $strSqlProcesosDisciplinariosLista = "";    

    /**
     * @Route("/rhu/consultas/pago/pendientes/banco", name="brs_rhu_consultas_pago_pendientes_banco")
     */
    public function PagoPendientesBancoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 30)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioPagoPendientesBancoLista();
        $form->handleRequest($request);
        $this->listarPagoPendientesBanco();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarPagoPendientesBancoLista($form);
                $this->listarPagoPendientesBanco();
                $this->generarPagoPendientesBancoExcel();
            }
            if($form->get('BtnPDF')->isClicked()) {
                $this->filtrarPagoPendientesBancoLista($form);
                $this->listarPagoPendientesBanco();
                $objReportePagoPendientesBanco = new \Brasa\RecursoHumanoBundle\Reportes\ReportePagoPendientesBanco();
                $objReportePagoPendientesBanco->Generar($this, $this->strSqlPagoPendientesBancoLista);
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarPagoPendientesBancoLista($form);
                $this->listarPagoPendientesBanco();
            }

        }
        $arPagos = $paginator->paginate($em->createQuery($this->strSqlPagoPendientesBancoLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/PagoPendientesBanco:PagoPendientesBanco.html.twig', array(
            'arPagos' => $arPagos,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/consultas/creditos/general", name="brs_rhu_consultas_creditos_general")
     */
    public function creditosGeneralAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 19)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioCreditosLista();
        $form->handleRequest($request);
        $this->CreditosListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelCredito')->isClicked()) {
                $this->filtrarCreditoLista($form);
                $this->CreditosListar();
                $this->generarCreditoExcel();
            }
            if($form->get('BtnPDFCredito')->isClicked()) {
                $this->filtrarCreditoLista($form);
                $this->CreditosListar();
                $objReporteCreditos = new \Brasa\RecursoHumanoBundle\Reportes\ReporteCreditos();
                $objReporteCreditos->Generar($this, $this->strSqlCreditoLista);
            }
            if($form->get('BtnFiltrarCredito')->isClicked()) {
                $this->filtrarCreditoLista($form);
                $this->CreditosListar();
            }

        }
        $arCreditos = $paginator->paginate($em->createQuery($this->strSqlCreditoLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Creditos:general.html.twig', array(
            'arCreditos' => $arCreditos,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/consultas/pago", name="brs_rhu_consultas_pago")
     */
    public function PagoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 20)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioPagoLista();
        $form->handleRequest($request);
        $this->PagoListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelPago')->isClicked()) {
                $this->filtrarPagoLista($form);
                $this->PagoListar();
                $this->generarPagoExcel();
            }
            /*if($form->get('BtnPDFPago')->isClicked()) {
                $this->filtrarPagoLista($form);
                $this->PagoListar();
                $objReportePago = new \Brasa\RecursoHumanoBundle\Reportes\ReportePago();
                $objReportePago->Generar($this, $this->strSqlPagoLista);

            }*/
            if($form->get('BtnFiltrarPago')->isClicked()) {
                $this->filtrarPagoLista($form);
                $this->PagoListar();
            }

        }
        $arPagos = $paginator->paginate($em->createQuery($this->strSqlPagoLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Pagos:Pago.html.twig', array(
            'arPagos' => $arPagos,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/consultas/servicios/cobrar", name="brs_rhu_consultas_servicios_cobrar")
     */
    public function serviciosCobrarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 38)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioServiciosPorCobrarLista();
        $form->handleRequest($request);
        $this->ServiciosPorCobrarListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelServiciosPorCobrar')->isClicked()) {
                $this->filtrarServiciosPorCobrarLista($form);
                $this->ServiciosPorCobrarListar();
                $this->generarServiciosPorCobrarExcel();
            }
            if($form->get('BtnPDFServiciosPorCobrar')->isClicked()) {
                $this->filtrarServiciosPorCobrarLista($form);
                $this->ServiciosPorCobrarListar();
                $objReporteServiciosPorCobrar = new \Brasa\RecursoHumanoBundle\Reportes\ReporteServiciosPorCobrar();
                $objReporteServiciosPorCobrar->Generar($this, $this->strSqlServiciosPorCobrarLista);
            }
            if($form->get('BtnFiltrarServiciosPorCobrar')->isClicked()) {
                $this->filtrarServiciosPorCobrarLista($form);
                $this->ServiciosPorCobrarListar();
            }

        }
        $arServiciosPorCobrar = $paginator->paginate($em->createQuery($this->strSqlServiciosPorCobrarLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Servicios:porCobrar.html.twig', array(
            'arServiciosPorCobrar' => $arServiciosPorCobrar,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/consultas/incapacidades/lista/", name="brs_rhu_consultas_incapacidades_lista")
     */
    public function IncapacidadesAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 22)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioIncapacidadesLista();
        $form->handleRequest($request);
        $this->IncapacidadesListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelIncapacidades')->isClicked()) {
                $this->filtrarIncapacidadesLista($form);
                $this->IncapacidadesListar();
                $this->generarIncapacidadesExcel();
            }
            if($form->get('BtnPDFIncapacidades')->isClicked()) {
                $this->filtrarIncapacidadesLista($form);
                $this->IncapacidadesListar();
                $objReporteIncapacidades = new \Brasa\RecursoHumanoBundle\Reportes\ReporteIncapacidades();
                $objReporteIncapacidades->Generar($this, $this->strSqlIncapacidadesLista);
            }
            if($form->get('BtnFiltrarIncapacidades')->isClicked()) {
                $this->filtrarIncapacidadesLista($form);
                $this->IncapacidadesListar();
            }

        }
        $arIncapacidades = $paginator->paginate($em->createQuery($this->strSqlIncapacidadesLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Incapacidades:Incapacidades.html.twig', array(
            'arIncapacidades' => $arIncapacidades,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/consultas/incapacidades/cobrar/pago", name="brs_rhu_consultas_incapacidades_cobrar_pago")
     */
    public function IncapacidadesCobrarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 23)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioIncapacidadesCobrarLista();
        $form->handleRequest($request);
        $this->IncapacidadesCobrarListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelIncapacidadesCobrar')->isClicked()) {
                $this->filtrarIncapacidadesCobrarLista($form);
                $this->IncapacidadesCobrarListar();
                $this->generarIncapacidadesCobrarExcel();
            }
            if($form->get('BtnPDFIncapacidadesCobrar')->isClicked()) {
                $this->filtrarIncapacidadesCobrarLista($form);
                $this->IncapacidadesCobrarListar();
                $objReporteIncapacidadesCobrar = new \Brasa\RecursoHumanoBundle\Reportes\ReporteIncapacidadesCobrar();
                $objReporteIncapacidadesCobrar->Generar($this, $this->strSqlIncapacidadesCobrarLista);
            }
            if($form->get('BtnFiltrarIncapacidadesCobrar')->isClicked()) {
                $this->filtrarIncapacidadesCobrarLista($form);
                $this->IncapacidadesCobrarListar();
            }

        }
        $arIncapacidadesCobrar = $paginator->paginate($em->createQuery($this->strSqlIncapacidadesCobrarLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/IncapacidadesCobrar:Incapacidades.html.twig', array(
            'arIncapacidadesCobrar' => $arIncapacidadesCobrar,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/consultas/procesos/disciplinarios", name="brs_rhu_consultas_procesos_disciplinarios")
     */
    public function ProcesosDisciplinariosAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 33)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioProcesosDisciplinariosLista();
        $form->handleRequest($request);
        $this->ProcesosDisciplinariosListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelProcesosDisciplinarios')->isClicked()) {
                $this->filtrarProcesosDisciplinariosLista($form);
                $this->ProcesosDisciplinariosListar();
                $this->generarProcesosDisciplinariosExcel();
            }
            /*if($form->get('BtnPDFProcesosDisciplinarios')->isClicked()) {
                $this->filtrarProcesosDisciplinariosLista($form);
                $this->ProcesosDisciplinariosListar();
                $objReporteProcesosDisciplinarios = new \Brasa\RecursoHumanoBundle\Reportes\ReporteProcesosDisciplinarios();
                $objReporteProcesosDisciplinarios->Generar($this, $this->strSqlProcesosDisciplinariosLista);
            }*/
            if($form->get('BtnFiltrarProcesosDisciplinarios')->isClicked()) {
                $this->filtrarProcesosDisciplinariosLista($form);
                $this->ProcesosDisciplinariosListar();
            }

        }
        $arProcesosDisciplinarios = $paginator->paginate($em->createQuery($this->strSqlProcesosDisciplinariosLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/ProcesosDisciplinarios:ProcesosDisciplinarios.html.twig', array(
            'arProcesosDisciplinarios' => $arProcesosDisciplinarios,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/consultas/sso/aportes", name="brs_rhu_consultas_sso_aportes")
     */
    public function AportesAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 24)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioAportesLista();
        $form->handleRequest($request);
        $this->AportesListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelAportes')->isClicked()) {
                $this->filtrarAportesLista($form);
                $this->AportesListar();
                $this->generarAportesExcel();
            }
            /*if($form->get('BtnPDFAportes')->isClicked()) {
                $this->filtrarAportesLista($form);
                $this->AportesListar();
                $objReporteAportes = new \Brasa\RecursoHumanoBundle\Reportes\ReporteAportes();
                $objReporteAportes->Generar($this, $this->strSqlAportesLista);
            }*/
            if($form->get('BtnFiltrarAportes')->isClicked()) {
                $this->filtrarAportesLista($form);
                $this->AportesListar();
            }

        }
        $arSsoAportes = $paginator->paginate($em->createQuery($this->strSqlAportesLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Aportes:Aportes.html.twig', array(
            'arSsoAportes' => $arSsoAportes,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/consultas/contrato/vacaciones/pagar", name="brs_rhu_consultas_contrato_vacaciones_pagar")
     */
    public function VacacionesPagarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 28)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioVacacionesPagarLista();
        $form->handleRequest($request);
        $this->VacacionesPagarListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelVacacionesPagar')->isClicked()) {
                $this->filtrarVacacionesPagarLista($form);
                $this->VacacionesPagarListar();
                $this->generarVacacionesPagarExcel();
            }
            if($form->get('BtnFiltrarVacacionesPagar')->isClicked()) {
                $this->filtrarVacacionesPagarLista($form);
                $this->VacacionesPagarListar();
            }

        }
        $arVacacionesPagar = $paginator->paginate($em->createQuery($this->strSqlVacacionesPagarLista), $request->query->get('page', 1), 40);

        return $this->render('BrasaRecursoHumanoBundle:Consultas/VacacionesPagar:VacacionesPagar.html.twig', array(
            'arVacacionesPagar' => $arVacacionesPagar,
            'form' => $form->createView()
            ));
    }
    
    /**
     * @Route("/rhu/consultas/contrato/fecha/terminacion", name="brs_rhu_consultas_contrato_fecha_terminacion")
     */
    public function FechaTerminacionAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 26)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFechaTerminacionLista();
        $form->handleRequest($request);
        $this->FechaTerminacionListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelFechaTerminacion')->isClicked()) {
                $this->filtrarFechaTerminacionLista($form);
                $this->FechaTerminacionListar();
                $this->generarFechaTerminacionExcel();
            }
            if($form->get('BtnFiltrarFechaTerminacion')->isClicked()) {
                $this->filtrarFechaTerminacionLista($form);
                $this->FechaTerminacionListar();
            }
        }
        $arFechaTerminacion = $paginator->paginate($em->createQuery($this->strSqlFechaTerminacionLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/FechaTerminacion:FechaTerminacion.html.twig', array(
            'arFechaTerminacion' => $arFechaTerminacion,
            'form' => $form->createView()
            ));
    }
    
    /**
     * @Route("/rhu/consultas/contrato/fecha/ingreso", name="brs_rhu_consultas_contrato_fecha_ingreso")
     */
    public function FechaIngresoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 25)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioIngresosContratosLista();
        $form->handleRequest($request);
        $this->contratosIngresosListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelIngresosContratos')->isClicked()) {
                $this->filtrarIngresosContratosLista($form);
                $this->contratosIngresosListar();
                $this->generarIngresosContratosExcel();
            }
            if($form->get('BtnFiltrarIngresosContratos')->isClicked()) {
                $this->filtrarIngresosContratosLista($form);
                $this->contratosIngresosListar();
            }

        }
        $arIngresosContratos = $paginator->paginate($em->createQuery($this->strSqlIngresosContratosLista), $request->query->get('page', 1), 40);

        return $this->render('BrasaRecursoHumanoBundle:Consultas/IngresosContratos:FechaIngreso.html.twig', array(
            'arIngresosContratos' => $arIngresosContratos,
            'form' => $form->createView()
            ));
    }
    
    /**
     * @Route("/rhu/consultas/contrato/periodo", name="brs_rhu_consultas_contrato_periodo")
     */
    public function ContratoPeriodoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 27)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioContratoPeriodo();
        $form->handleRequest($request);
        $this->contratosPeriodoListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelContratosPeriodo')->isClicked()) {
                $this->filtrarContratosPeriodoLista($form);
                $this->contratosPeriodoListar();
                $this->generarContratosPeriodoExcel();
            }
            if($form->get('BtnFiltrarContratosPeriodo')->isClicked()) {
                $this->filtrarContratosPeriodoLista($form);
                $this->contratosPeriodoListar();
            }

        }
        $arContratos = $paginator->paginate($em->createQuery($this->strSqlContratosPeriodoLista), $request->query->get('page', 1), 2000);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Contrato:periodo.html.twig', array(
            'arContrato' => $arContratos,
            'form' => $form->createView()
            ));
    }    

    /**
     * @Route("/rhu/consultas/empleado", name="brs_rhu_consultas_empleado")
     */
    public function EmpleadoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 14)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->formularioEmpleadoLista();
        $form->handleRequest($request);
        $this->EmpleadoListar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarEmpleadoLista($form);
                $this->EmpleadoListar();
            }

            /*if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarEmpleadoLista($form);
                $this->EmpleadoListar();
                $objFormatoEmpleado = new \Brasa\RecursoHumanoBundle\Formatos\FormatoEmpleado();
                $objFormatoEmpleado->Generar($this, $this->strSqlEmpleadosLista);

            }*/

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarEmpleadoLista($form);
                $this->EmpleadoListar();
                $this->generarEmpleadoExcel();
            }
        }
        $arEmpleados = $paginator->paginate($em->createQuery($this->strSqlEmpleadosLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Empleados:lista.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
            ));
    }
    
    /**
     * @Route("/rhu/consultas/dotacion/pendiente", name="brs_rhu_consultas_dotacion_pendiente")
     */
    public function DotacionesPendientesAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 39)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioDotacionesPendientesLista();
        $form->handleRequest($request);
        $this->DotacionesPendientesListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelDotacionesPendientes')->isClicked()) {
                $this->filtrarDotacionesPendientesLista($form);
                $this->DotacionesPendientesListar();
                $this->generarDotacionesPendientesExcel();
            }
            
            if($form->get('BtnFiltrarDotacionesPendientes')->isClicked()) {
                $this->filtrarDotacionesPendientesLista($form);
                $this->DotacionesPendientesListar();
            }

        }
        $arDotacionesPendientes = $paginator->paginate($em->createQuery($this->strSqlDotacionesPendientesLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/DotacionesPendientes:DotacionesPendientes.html.twig', array(
            'arDotacionesPendientes' => $arDotacionesPendientes,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/consultas/empleado/detalle/{codigoEmpleado}", name="brs_rhu_consultas_empleado_detalle")
     */
    public function EmpleadodetalleAction($codigoEmpleado) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
        $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arDisciplinarios = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arDisciplinarios = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arEmpleadoEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
        $arEmpleadoEstudios = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arEmpleadoFamilia = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia();
        $arEmpleadoFamilia = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoFamilia')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arEmpleadoDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
        $arEmpleadoDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        if($form->isValid()) {
        }
        $arIncapacidades = $paginator->paginate($arIncapacidades, $this->get('request')->query->get('page', 1),5);
        $arVacaciones = $paginator->paginate($arVacaciones, $this->get('request')->query->get('page', 1),5);
        $arLicencias = $paginator->paginate($arLicencias, $this->get('request')->query->get('page', 1),5);
        $arContratos = $paginator->paginate($arContratos, $this->get('request')->query->get('page', 1),5);
        $arCreditos = $paginator->paginate($arCreditos, $this->get('request')->query->get('page', 1),5);
        $arDisciplinarios = $paginator->paginate($arDisciplinarios, $this->get('request')->query->get('page', 1),5);
        $arEmpleadoEstudios = $paginator->paginate($arEmpleadoEstudios, $this->get('request')->query->get('page', 1),6);
        $arEmpleadoFamilia = $paginator->paginate($arEmpleadoFamilia, $this->get('request')->query->get('page', 1),8);
        $arEmpleadoDotacion = $paginator->paginate($arEmpleadoDotacion, $this->get('request')->query->get('page', 1),8);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Empleados:detalle.html.twig', array(
                    'arEmpleado' => $arEmpleado,
                    'arIncapacidades' => $arIncapacidades,
                    'arVacaciones' => $arVacaciones,
                    'arLicencias' => $arLicencias,
                    'arContratos' => $arContratos,
                    'arCreditos' => $arCreditos,
                    'arDisciplinarios' => $arDisciplinarios,
                    'arEmpleadoEstudios' => $arEmpleadoEstudios,
                    'arEmpleadoFamilia' => $arEmpleadoFamilia,
                    'arEmpleadoDotacion' => $arEmpleadoDotacion,
                    'form' => $form->createView()
                    ));
    }  

    private function listarPagoPendientesBanco() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlPagoPendientesBancoLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaPagoPendientesBancoDql(

                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }

    private function CreditosListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlCreditoLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->listaDQL(
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }

    private function ServiciosPorCobrarListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlServiciosPorCobrarLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->listaServiciosPorCobrarDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }

    private function PagoListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlPagoLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaConsultaPagosDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta'),
                    $session->get('filtroCodigoPago')
                    );
    }

    private function EmpleadoListar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strSqlEmpleadosLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->listaDQL(
                $session->get('filtroEmpleadoNombre'),
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroEmpleadoActivo'),
                $session->get('filtroIdentificacion'),
                "",
                $session->get('filtroEmpleadoContratado')
                );
    }

    private function AportesListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlAportesLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->listaAportesDQL(
                    $session->get('filtroRhuAnio'),
                    $session->get('filtroRhuMes'),
                    $session->get('filtroIdentificacion')
                    );
    }

    private function VacacionesPagarListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlVacacionesPagarLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->listaContratosVacacionCumplidaDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroHasta')
                    );
    }
    
    private function contratosIngresosListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlIngresosContratosLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->listaIngresosContratosDQL(
            $session->get('filtroCodigoContratoTipo'),
            $session->get('filtroCodigoEmpleadoTipo'),
            $session->get('filtroCodigoZona'),
            $session->get('filtroCodigoSubzona'),
            $session->get('filtroCodigoContrato'),
            $session->get('filtroIdentificacion'),
            $session->get('filtroDesde'),
            $session->get('filtroHasta')
            );
    }

    private function contratosPeriodoListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        if($session->get('filtroDesde') == "") {
            $fecha = new \DateTime('now');
            $session->set('filtroDesde', $fecha->format('Y/m/d'));                    
        }        
        if($session->get('filtroHasta') == "") {
            $fecha = new \DateTime('now');
            $session->set('filtroHasta', $fecha->format('Y/m/d'));
        }  
        if($session->get('filtroCodigoCentroCosto') == "") {
            $session->set('filtroCodigoCentroCosto', "0");
        }        
        $this->strSqlContratosPeriodoLista  = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                    . "WHERE "
                    . "c.fechaDesde <= '" .$session->get('filtroHasta') . "' "
                    . " AND (c.fechaHasta >= '" . $session->get('filtroDesde') . "' "
                    . " OR c.indefinido = 1) ";
        if($session->get('filtroCodigoCentroCosto')) {
            $this->strSqlContratosPeriodoLista .= " AND c.codigoCentroCostoFk = " . $session->get('filtroCodigoCentroCosto');
        }
        
    }    
    
    private function IncapacidadesListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlIncapacidadesLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->listaIncapacidadesDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta'),
                    $session->get('filtroCodigoEntidadSalud')
                    );
    }

    private function IncapacidadesCobrarListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlIncapacidadesCobrarLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->listaIncapacidadesCobrarDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta'),
                    $session->get('filtroCodigoEntidadSalud')
                    );
    }

    private function ProcesosDisciplinariosListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlProcesosDisciplinariosLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->listaProcesosDisciplinariosDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }
    
    private function DotacionesPendientesListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlDotacionesPendientesLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->listaDotacionesPendientesDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }
    
    private function FechaTerminacionListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlFechaTerminacionLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->listaContratosFechaTerminacionDQL(
                    $session->get('filtroCodigoContratoTipo'),
                    $session->get('filtroCodigoEmpleadoTipo'),
                    $session->get('filtroCodigoZona'),
                    $session->get('filtroCodigoSubzona'),
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDF', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function formularioPagoPendientesBancoLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDF', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function formularioCreditosLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();


        $form = $this->createFormBuilder()
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarCredito', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelCredito', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDFCredito', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function formularioPagoLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('codigoPago', 'text', array('label'  => 'codigoPago'))
            ->add('BtnFiltrarPago', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelPago', 'submit', array('label'  => 'Excel',))
            //->add('BtnPDFPago', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function formularioServiciosPorCobrarLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarServiciosPorCobrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelServiciosPorCobrar', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDFServiciosPorCobrar', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function formularioAportesLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();

        $form = $this->createFormBuilder()
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('TxtAnio','number', array('label'  => 'Anio','data' => $session->get('filtroRhuAnio')))
            ->add('TxtMes','number', array('label'  => 'Mes','data' => $session->get('filtroRhuMes')))            
            ->add('BtnFiltrarAportes', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelAportes', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioEmpleadoLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
            ->add('estadoActivo', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'ACTIVOS', '0' => 'INACTIVOS')))
            ->add('estadoContratado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO')))    
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioIncapacidadesLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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

        $arrayPropiedadesEntidadSalud = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('es')
                    ->orderBy('es.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoEntidadSalud')) {
            $arrayPropiedadesEntidadSalud['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEntidadSalud", $session->get('filtroCodigoEntidadSalud'));
        }

        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('entidadSaludRel', 'entity', $arrayPropiedadesEntidadSalud)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarIncapacidades', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelIncapacidades', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDFIncapacidades', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function formularioIncapacidadesCobrarLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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

        $arrayPropiedadesEntidadSalud = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('es')
                    ->orderBy('es.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoEntidadSalud')) {
            $arrayPropiedadesEntidadSalud['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEntidadSalud", $session->get('filtroCodigoEntidadSalud'));
        }

        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('entidadSaludRel', 'entity', $arrayPropiedadesEntidadSalud)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarIncapacidadesCobrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelIncapacidadesCobrar', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDFIncapacidadesCobrar', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function formularioProcesosDisciplinariosLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarProcesosDisciplinarios', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelProcesosDisciplinarios', 'submit', array('label'  => 'Excel',))
            //->add('BtnPDFProcesosDisciplinarios', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }
    
    private function formularioDotacionesPendientesLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarDotacionesPendientes', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelDotacionesPendientes', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioVacacionesPagarLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarVacacionesPagar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelVacacionesPagar', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }
    
    private function formularioFechaTerminacionLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
        $arrayPropiedadesTipo = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoEmpleadoTipo')) {
            $arrayPropiedadesTipo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEmpleadoTipo", $session->get('filtroCodigoEmpleadoTipo'));
        }
        $arrayPropiedadesZona = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuZona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoZona')) {
            $arrayPropiedadesZona['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuZona", $session->get('filtroCodigoZona'));
        }
        $arrayPropiedadesSubZona = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSubzona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sz')
                    ->orderBy('sz.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoSubzona')) {
            $arrayPropiedadesSubZona['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuSubzona", $session->get('filtroCodigoSubzona'));
        }
        $arrayPropiedadesTipoContrato = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuContratoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sz')
                    ->orderBy('sz.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoContratoTipo')) {
            $arrayPropiedadesTipoContrato['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuContratoTipo", $session->get('filtroCodigoContratoTipo'));
        }
        $form = $this->createFormBuilder()
            ->add('contratoTipoRel', 'entity', $arrayPropiedadesTipoContrato)
            ->add('centroCostoRel', 'entity', $arrayPropiedades)    
            ->add('empleadoTipoRel', 'entity', $arrayPropiedadesTipo)
            ->add('zonaRel', 'entity', $arrayPropiedadesZona)
            ->add('subZonaRel', 'entity', $arrayPropiedadesSubZona)    
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarFechaTerminacion', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelFechaTerminacion', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioIngresosContratosLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedadesTipo = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoEmpleadoTipo')) {
            $arrayPropiedadesTipo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEmpleadoTipo", $session->get('filtroCodigoEmpleadoTipo'));
        }
        $arrayPropiedadesZona = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuZona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoZona')) {
            $arrayPropiedadesZona['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuZona", $session->get('filtroCodigoZona'));
        }
        $arrayPropiedadesSubZona = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSubzona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sz')
                    ->orderBy('sz.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoSubzona')) {
            $arrayPropiedadesSubZona['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuSubzona", $session->get('filtroCodigoSubzona'));
        }
        $arrayPropiedadesTipoContrato = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuContratoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sz')
                    ->orderBy('sz.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoContratoTipo')) {
            $arrayPropiedadesTipoContrato['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuContratoTipo", $session->get('filtroCodigoContratoTipo'));
        }
        $form = $this->createFormBuilder()
            ->add('contratoTipoRel', 'entity', $arrayPropiedadesTipoContrato)    
            ->add('empleadoTipoRel', 'entity', $arrayPropiedadesTipo)
            ->add('zonaRel', 'entity', $arrayPropiedadesZona)
            ->add('subZonaRel', 'entity', $arrayPropiedadesSubZona)    
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('TxtContrato', 'text', array('label'  => 'Contrato','data' => $session->get('filtroCodigoContrato')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarIngresosContratos', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelIngresosContratos', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioContratoPeriodo() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        
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
        $arrayPropiedadesTipo = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoEmpleadoTipo')) {
            $arrayPropiedadesTipo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEmpleadoTipo", $session->get('filtroCodigoEmpleadoTipo'));
        }
        $arrayPropiedadesZona = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuZona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoZona')) {
            $arrayPropiedadesZona['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuZona", $session->get('filtroCodigoZona'));
        }
        $arrayPropiedadesSubZona = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSubzona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sz')
                    ->orderBy('sz.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoSubzona')) {
            $arrayPropiedadesSubZona['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuSubzona", $session->get('filtroCodigoSubzona'));
        }
        $arrayPropiedadesTipoContrato = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuContratoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sz')
                    ->orderBy('sz.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoContratoTipo')) {
            $arrayPropiedadesTipoContrato['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuContratoTipo", $session->get('filtroCodigoContratoTipo'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)    
            ->add('contratoTipoRel', 'entity', $arrayPropiedadesTipoContrato)    
            ->add('empleadoTipoRel', 'entity', $arrayPropiedadesTipo)
            ->add('zonaRel', 'entity', $arrayPropiedadesZona)
            ->add('subZonaRel', 'entity', $arrayPropiedadesSubZona)    
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))            
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarContratosPeriodo', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelContratosPeriodo', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }    
    
    private function filtrarPagoPendientesBancoLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
                
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

    private function filtrarCreditoLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        //$session->set('filtroDesde', $form->get('fechaDesde')->getData());
        //$session->set('filtroHasta', $form->get('fechaHasta')->getData());
        
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

    private function filtrarPagoLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        //$session->set('filtroDesde', $form->get('fechaDesde')->getData());
        //$session->set('filtroHasta', $form->get('fechaHasta')->getData());
        $session->set('filtroCodigoPago', $form->get('codigoPago')->getData());
        
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

    private function filtrarEmpleadoLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroEmpleadoActivo', $form->get('estadoActivo')->getData());
        $session->set('filtroEmpleadoContratado', $form->get('estadoContratado')->getData());
    }

    private function filtrarIncapacidadesLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        //$session->set('filtroDesde', $form->get('fechaDesde')->getData());
        //$session->set('filtroHasta', $form->get('fechaHasta')->getData());
        $session->set('filtroCodigoEntidadSalud', $controles['entidadSaludRel']);
        
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

    private function filtrarIncapacidadesCobrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        //$session->set('filtroDesde', $form->get('fechaDesde')->getData());
        //$session->set('filtroHasta', $form->get('fechaHasta')->getData());
        $session->set('filtroCodigoEntidadSalud', $controles['entidadSaludRel']);
        
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

    private function filtrarProcesosDisciplinariosLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
                
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
    
    private function filtrarDotacionesPendientesLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
                
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

    private function filtrarAportesLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();        
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());        
        $session->set('filtroRhuAnio', $form->get('TxtAnio')->getData());        
        $session->set('filtroRhuMes', $form->get('TxtMes')->getData());        
    }

    private function filtrarServiciosPorCobrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        //$session->set('filtroDesde', $form->get('fechaDesde')->getData());
        //$session->set('filtroHasta', $form->get('fechaHasta')->getData());
        
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

    private function filtrarVacacionesPagarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        //$session->set('filtroHasta', $form->get('fechaHasta')->getData());
        
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaHasta')->getData() == null){
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d')); 
        }
    }
    
    private function filtrarFechaTerminacionLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoContratoTipo', $controles['contratoTipoRel']);
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroCodigoEmpleadoTipo', $controles['empleadoTipoRel']);
        $session->set('filtroCodigoZona', $controles['zonaRel']);
        $session->set('filtroCodigoSubzona', $controles['subZonaRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
                
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
    
    private function filtrarIngresosContratosLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoContratoTipo', $controles['contratoTipoRel']);
        $session->set('filtroCodigoEmpleadoTipo', $controles['empleadoTipoRel']);
        $session->set('filtroCodigoZona', $controles['zonaRel']);
        $session->set('filtroCodigoSubzona', $controles['subZonaRel']);
        $session->set('filtroCodigoContrato', $form->get('TxtContrato')->getData());
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
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

    private function filtrarContratosPeriodoLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');    
        $fechaDesde = $form->get('fechaDesde')->getData();
        $fechaHasta = $form->get('fechaHasta')->getData();
        if(!$fechaDesde) {
            $fechaDesde = new \DateTime('now');
        }
        if(!$fechaHasta) {
            $fechaHasta = new \DateTime('now');
        }        
        $session->set('filtroCodigoContratoTipo', $controles['contratoTipoRel']);
        $session->set('filtroCodigoEmpleadoTipo', $controles['empleadoTipoRel']);
        $session->set('filtroCodigoZona', $controles['zonaRel']);
        $session->set('filtroCodigoSubzona', $controles['subZonaRel']);        
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $fechaDesde->format('Y/m/d'));
        $session->set('filtroHasta', $fechaHasta->format('Y/m/d'));
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'DESDE')
                    ->setCellValue('C1', 'HASTA')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'CENTRO COSTOS')
                    ->setCellValue('G1', 'BASICO')
                    ->setCellValue('H1', 'TIEMPO EXTRA')
                    ->setCellValue('I1', 'VALORES ADICIONALES')
                    ->setCellValue('J1', 'AUX. TRANSPORTE')
                    ->setCellValue('K1', 'ARP')
                    ->setCellValue('L1', 'EPS')
                    ->setCellValue('M1', 'PENSION')
                    ->setCellValue('N1', 'CAJA')
                    ->setCellValue('O1', 'ICBF')
                    ->setCellValue('P1', 'SENA')
                    ->setCellValue('Q1', 'CESANTIAS')
                    ->setCellValue('R1', 'VACACIONES')
                    ->setCellValue('S1', 'ADMON')
                    ->setCellValue('T1', 'COSTO')
                    ->setCellValue('U1', 'TOTAL')
                    ->setCellValue('W1', 'NETO')
                    ->setCellValue('X1', 'IBC')
                    ->setCellValue('Y1', 'AUX. TRANSPORTE COTIZACION')
                    ->setCellValue('Z1', 'DIAS PERIODO')
                    ->setCellValue('AA1', 'SALARIO PERIODO')
                    ->setCellValue('AB1', 'SALARIO EMPLEADO');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'AB'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        for($col = 'G'; $col !== 'Y'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        for($col = 'Z'; $col !== 'AB'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        foreach ($arPagos as $arPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('C' . $i, $arPago->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPago->getVrSalario())
                    ->setCellValue('H' . $i, $arPago->getVrAdicionalTiempo())
                    ->setCellValue('I' . $i, $arPago->getVrAdicionalValor())
                    ->setCellValue('J' . $i, $arPago->getVrAuxilioTransporte())
                    ->setCellValue('K' . $i, $arPago->getVrArp())
                    ->setCellValue('L' . $i, $arPago->getVrEps())
                    ->setCellValue('M' . $i, $arPago->getVrPension())
                    ->setCellValue('N' . $i, $arPago->getVrCaja())
                    ->setCellValue('O' . $i, $arPago->getVrIcbf())
                    ->setCellValue('P' . $i, $arPago->getVrSena())
                    ->setCellValue('Q' . $i, $arPago->getVrCesantias())
                    ->setCellValue('R' . $i, $arPago->getVrVacaciones())
                    ->setCellValue('S' . $i, $arPago->getVrAdministracion())
                    ->setCellValue('T' . $i, $arPago->getVrCosto())
                    ->setCellValue('U' . $i, $arPago->getVrTotalCobrar())
                    ->setCellValue('W' . $i, $arPago->getVrNeto())
                    ->setCellValue('X' . $i, $arPago->getVrIngresoBaseCotizacion())
                    ->setCellValue('Y' . $i, $arPago->getVrAuxilioTransporteCotizacion())
                    ->setCellValue('Z' . $i, $arPago->getDiasPeriodo())
                    ->setCellValue('AA' . $i, $arPago->getVrSalarioPeriodo())
                    ->setCellValue('AB' . $i, $arPago->getVrSalarioEmpleado());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('costos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Costos.xlsx"');
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

    private function generarPagoPendientesBancoExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'DESDE')
                    ->setCellValue('C1', 'HASTA')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'CENTRO COSTOS')
                    ->setCellValue('G1', 'BASICO')
                    ->setCellValue('H1', 'TIEMPO EXTRA')
                    ->setCellValue('I1', 'VALORES ADICIONALES')
                    ->setCellValue('J1', 'AUX. TRANSPORTE')
                    ->setCellValue('K1', 'COSTO')
                    ->setCellValue('L1', 'NETO')
                    ->setCellValue('M1', 'IBC')
                    ->setCellValue('N1', 'AUX. TRANSPORTE COTIZACION')
                    ->setCellValue('O1', 'DIAS PERIODO')
                    ->setCellValue('P1', 'SALARIO PERIODO')
                    ->setCellValue('Q1', 'SALARIO EMPLEADO');

        $i = 2;
        $query = $em->createQuery($this->strSqlPagoPendientesBancoLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }     
        for($col = 'G'; $col !== 'Q'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');
        }
        foreach ($arPagos as $arPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('C' . $i, $arPago->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPago->getVrSalario())
                    ->setCellValue('H' . $i, $arPago->getVrAdicionalTiempo())
                    ->setCellValue('I' . $i, $arPago->getVrAdicionalValor())
                    ->setCellValue('J' . $i, $arPago->getVrAuxilioTransporte())
                    ->setCellValue('K' . $i, $arPago->getVrCosto())
                    ->setCellValue('L' . $i, $arPago->getVrNeto())
                    ->setCellValue('M' . $i, $arPago->getVrIngresoBaseCotizacion())
                    ->setCellValue('N' . $i, $arPago->getVrAuxilioTransporteCotizacion())
                    ->setCellValue('O' . $i, $arPago->getDiasPeriodo())
                    ->setCellValue('P' . $i, $arPago->getVrSalarioPeriodo())
                    ->setCellValue('Q' . $i, $arPago->getVrSalarioEmpleado());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PagoPendientesBanco');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PagoPendientesBanco.xlsx"');
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

    private function generarCostosIbcExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'IDENTIFICACION')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'CODIGO CONTRATO')
                    ->setCellValue('E1', 'IBC')
                    ->setCellValue('F1', 'DESDE')
                    ->setCellValue('G1', 'HASTA');

        $i = 2;
        $query = $em->createQuery($this->strSqlCostosIbcLista);
        $arIbc = new \Brasa\RecursoHumanoBundle\Entity\RhuIbc();
        $arIbc = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'K'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        for($col = 'E'; $col !== 'E'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        foreach ($arIbc as $arIbc) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIbc->getCodigoIbcPk())
                    ->setCellValue('B' . $i, $arIbc->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arIbc->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arIbc->getCodigoContratoFk())
                    ->setCellValue('E' . $i, $arIbc->getvrIngresoBaseCotizacion())
                    ->setCellValue('F' . $i, $arIbc->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('G' . $i, $arIbc->getFechaDesde()->Format('Y-m-d'));
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('CostosIbcc');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CostosIbc.xlsx"');
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

    private function generarCreditoExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'VR. CREDITO')
                    ->setCellValue('G1', 'VR. CUOTA')
                    ->setCellValue('H1', 'VR. SALDO')
                    ->setCellValue('I1', 'CUOTAS')
                    ->setCellValue('J1', 'CUOTA ACTUAL')
                    ->setCellValue('K1', 'APROBADO')
                    ->setCellValue('L1', 'SUSPENDIDO');

        $i = 2;
        $query = $em->createQuery($this->strSqlCreditoLista);
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        for($col = 'F'; $col !== 'H'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        foreach ($arCreditos as $arCredito) {
            if ($arCredito->getAprobado() == 1) {
                $Aprobado = "SI";
            }
            if ($arCredito->getEstadoSuspendido() == 1) {
                $Suspendido = "SI";
            }
            else {
                $Suspendido = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCredito->getCodigoCreditoPk())
                    ->setCellValue('B' . $i, $arCredito->getCreditoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arCredito->getFecha()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arCredito->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arCredito->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arCredito->getVrPagar())
                    ->setCellValue('G' . $i, $arCredito->getVrCuota())
                    ->setCellValue('H' . $i, $arCredito->getSaldo())
                    ->setCellValue('I' . $i, $arCredito->getNumeroCuotas())
                    ->setCellValue('J' . $i, $arCredito->getNumeroCuotaActual())
                    ->setCellValue('K' . $i, $Aprobado)
                    ->setCellValue('L' . $i, $Suspendido);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('creditos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ConsultaCreditos.xlsx"');
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

    private function generarServiciosPorCobrarExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'CENTRO COSTOS')
                    ->setCellValue('C1', 'IDENTIFICACION')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'DESDE')
                    ->setCellValue('F1', 'HASTA')
                    ->setCellValue('G1', 'VR. SALARIO')
                    ->setCellValue('H1', 'VR. SALARIO PERIODO')
                    ->setCellValue('I1', 'VR. SALARIO EMPLEADO')
                    ->setCellValue('J1', 'VR. DEVENGADO')
                    ->setCellValue('K1', 'VR. DEDUCCIONES')
                    ->setCellValue('L1', 'VR. ADICIONAL TIEMPO')
                    ->setCellValue('M1', 'VR. ADICIONAL VALOR')
                    ->setCellValue('N1', 'VR. AUXILIO TRANSPORTE')
                    ->setCellValue('O1', 'VR. AUXILIO TRANSPORTE COTIZACION')
                    ->setCellValue('P1', 'VR. ARP')
                    ->setCellValue('Q1', 'VR. EPS')
                    ->setCellValue('R1', 'VR. PENSION')
                    ->setCellValue('S1', 'VR. CAJA COMPENSACION')
                    ->setCellValue('T1', 'VR. SENA')
                    ->setCellValue('U1', 'VR. ICBF')
                    ->setCellValue('V1', 'VR. CESANTIAS')
                    ->setCellValue('W1', 'VR. VACACIONES')
                    ->setCellValue('X1', 'VR. ADMINISTRACION')
                    ->setCellValue('Y1', 'VR. NETO')
                    ->setCellValue('Z1', 'VR. BRUTO')
                    ->setCellValue('AA1', 'VR. TOTAL COBRAR')
                    ->setCellValue('AB1', 'VR. COSTO')
                    ->setCellValue('AC1', 'VR. INGRESO BASE COTIZACION')
                    ->setCellValue('AD1', 'ESTADO COBRADO')
                    ->setCellValue('AE1', 'DIAS PERIODO');

        $i = 2;
        $query = $em->createQuery($this->strSqlServiciosPorCobrarLista);
        $arServiciosPorCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
        $arServiciosPorCobrar = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'AE'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        for($col = 'G'; $col !== 'AC'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        foreach ($arServiciosPorCobrar as $arServiciosPorCobrar) {
            if ($arServiciosPorCobrar->getEstadoCobrado() == 1) {
                $estado = "SI";
            } else {
                $estado = "NO";
            }

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arServiciosPorCobrar->getCodigoServicioCobrarPk())
                    ->setCellValue('B' . $i, $arServiciosPorCobrar->getCentroCostoRel()->getNombre())
                    ->setCellValue('C' . $i, $arServiciosPorCobrar->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arServiciosPorCobrar->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arServiciosPorCobrar->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arServiciosPorCobrar->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('G' . $i, $arServiciosPorCobrar->getVrSalario())
                    ->setCellValue('H' . $i, $arServiciosPorCobrar->getVrSalarioPeriodo())
                    ->setCellValue('I' . $i, $arServiciosPorCobrar->getVrSalarioEmpleado())
                    ->setCellValue('J' . $i, $arServiciosPorCobrar->getVrDevengado())
                    ->setCellValue('K' . $i, $arServiciosPorCobrar->getVrDeducciones())
                    ->setCellValue('L' . $i, $arServiciosPorCobrar->getVrAdicionalTiempo())
                    ->setCellValue('M' . $i, $arServiciosPorCobrar->getVrAdicionalValor())
                    ->setCellValue('N' . $i, $arServiciosPorCobrar->getVrAuxilioTransporte())
                    ->setCellValue('O' . $i, $arServiciosPorCobrar->getVrAuxilioTransporteCotizacion())
                    ->setCellValue('P' . $i, $arServiciosPorCobrar->getVrArp())
                    ->setCellValue('Q' . $i, $arServiciosPorCobrar->getVrEps())
                    ->setCellValue('R' . $i, $arServiciosPorCobrar->getVrPension())
                    ->setCellValue('S' . $i, $arServiciosPorCobrar->getVrCaja())
                    ->setCellValue('T' . $i, $arServiciosPorCobrar->getVrSena())
                    ->setCellValue('U' . $i, $arServiciosPorCobrar->getVrIcbf())
                    ->setCellValue('V' . $i, $arServiciosPorCobrar->getVrCesantias())
                    ->setCellValue('W' . $i, $arServiciosPorCobrar->getVrVacaciones())
                    ->setCellValue('X' . $i, $arServiciosPorCobrar->getVrAdministracion())
                    ->setCellValue('Y' . $i, $arServiciosPorCobrar->getVrNeto())
                    ->setCellValue('Z' . $i, $arServiciosPorCobrar->getVrBruto())
                    ->setCellValue('AA' . $i, $arServiciosPorCobrar->getVrTotalCobrar())
                    ->setCellValue('AB' . $i, $arServiciosPorCobrar->getVrCosto())
                    ->setCellValue('AC' . $i, $arServiciosPorCobrar->getVrIngresoBaseCotizacion())
                    ->setCellValue('AD' . $i, $estado)
                    ->setCellValue('AE' . $i, $arServiciosPorCobrar->getDiasPeriodo());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ServiciosPorCobrar');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ConsultaServiciosPorCobrar.xlsx"');
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

    private function generarPagoExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'NÚMERO')
                    ->setCellValue('C1', 'TIPO')
                    ->setCellValue('D1', 'IDENTIFICACIÓN')
                    ->setCellValue('E1', 'EMPLEADO')
                    ->setCellValue('F1', 'CENTRO COSTOS')
                    ->setCellValue('G1', 'PERIODO DESDE')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'HASTA')
                    ->setCellValue('J1', 'VR. SALARIO')
                    ->setCellValue('K1', 'IBC')
                    ->setCellValue('L1', 'IBP')
                    ->setCellValue('M1', 'VR. DEVENGADO')
                    ->setCellValue('N1', 'VR. DEDUCCIONES')
                    ->setCellValue('O1', 'VR. NETO');

        $i = 2;
        $query = $em->createQuery($this->strSqlPagoLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        for($col = 'J'; $col !== 'O'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        foreach ($arPagos as $arPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getNumero())
                    ->setCellValue('C' . $i, $arPago->getPagoTipoRel()->getNombre())
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPago->getFechaDesdePago()->format('Y/m/d'))
                    ->setCellValue('H' . $i, $arPago->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $arPago->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('J' . $i, $arPago->getVrSalario())
                    ->setCellValue('K' . $i, $arPago->getVrIngresoBaseCotizacion())
                    ->setCellValue('L' . $i, $arPago->getVrIngresoBasePrestacion())
                    ->setCellValue('M' . $i, $arPago->getVrDevengado())
                    ->setCellValue('N' . $i, $arPago->getVrDeducciones())
                    ->setCellValue('O' . $i, $arPago->getVrNeto());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Pagos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReportePagos.xlsx"');
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

    private function generarIncapacidadesExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'DIAGNOSTICO')
                    ->setCellValue('D1', 'EPS')
                    ->setCellValue('E1', 'IDENTIFICACIÓN')
                    ->setCellValue('F1', 'EMPLEADO')
                    ->setCellValue('G1', 'CENTRO COSTOS')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'HASTA')
                    ->setCellValue('J1', 'DÍAS')
                    ->setCellValue('K1', 'PRORROGA')
                    ->setCellValue('L1', 'TRANSCRIPCIÓN')
                    ->setCellValue('M1', 'VR. INCAPACIDAD')
                    ->setCellValue('N1', 'VR. PAGADO')
                    ->setCellValue('O1', 'VR. SALDO');

        $i = 2;
        $query = $em->createQuery($this->strSqlIncapacidadesLista);
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        for($col = 'M'; $col !== 'O'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        foreach ($arIncapacidades as $arIncapacidad) {
            if ($arIncapacidad->getEstadoProrroga() == 1){
                $prorroga = "SI";
            }else {
                $prorroga = "NO";
            }
            if ($arIncapacidad->getEstadoTranscripcion() == 1){
                $transcripcion = "SI";
            }else {
                $transcripcion = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIncapacidad->getCodigoIncapacidadPk())
                    ->setCellValue('B' . $i, $arIncapacidad->getIncapacidadTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arIncapacidad->getIncapacidadDiagnosticoRel()->getNombre())
                    ->setCellValue('D' . $i, $arIncapacidad->getEntidadSaludRel()->getNombre())
                    ->setCellValue('E' . $i, $arIncapacidad->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $arIncapacidad->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arIncapacidad->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $arIncapacidad->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $arIncapacidad->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('J' . $i, $arIncapacidad->getCantidad())
                    ->setCellValue('K' . $i, $prorroga)
                    ->setCellValue('L' . $i, $transcripcion)
                    ->setCellValue('M' . $i, $arIncapacidad->getVrIncapacidad())
                    ->setCellValue('N' . $i, $arIncapacidad->getVrPagado())
                    ->setCellValue('O' . $i, $arIncapacidad->getVrSaldo());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Incapacidades');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteIncapacidades.xlsx"');
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

    private function generarIncapacidadesCobrarExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'DIAGNOSTICO')
                    ->setCellValue('D1', 'EPS')
                    ->setCellValue('E1', 'IDENTIFICACIÓN')
                    ->setCellValue('F1', 'EMPLEADO')
                    ->setCellValue('G1', 'CENTRO COSTOS')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'HASTA')
                    ->setCellValue('J1', 'DÍAS')
                    ->setCellValue('K1', 'PRORROGA')
                    ->setCellValue('L1', 'TRANSCRIPCIÓN')
                    ->setCellValue('M1', 'VR. INCAPACIDAD')
                    ->setCellValue('N1', 'VR. PAGADO')
                    ->setCellValue('O1', 'VR. SALDO');

        $i = 2;
        $query = $em->createQuery($this->strSqlIncapacidadesCobrarLista);
        $arIncapacidadesCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidadesCobrar = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        for($col = 'M'; $col !== 'O'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        foreach ($arIncapacidadesCobrar as $arIncapacidadesCobrar) {
            if ($arIncapacidadesCobrar->getEstadoProrroga() == 1){
                $prorroga = "SI";
            }else {
                $prorroga = "NO";
            }
            if ($arIncapacidadesCobrar->getEstadoTranscripcion() == 1){
                $transcripcion = "SI";
            }else {
                $transcripcion = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIncapacidadesCobrar->getCodigoIncapacidadPk())
                    ->setCellValue('B' . $i, $arIncapacidadesCobrar->getIncapacidadTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arIncapacidadesCobrar->getIncapacidadDiagnosticoRel()->getNombre())
                    ->setCellValue('D' . $i, $arIncapacidadesCobrar->getEntidadSaludRel()->getNombre())
                    ->setCellValue('E' . $i, $arIncapacidadesCobrar->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $arIncapacidadesCobrar->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arIncapacidadesCobrar->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $arIncapacidadesCobrar->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $arIncapacidadesCobrar->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('J' . $i, $arIncapacidadesCobrar->getCantidad())
                    ->setCellValue('K' . $i, $prorroga)
                    ->setCellValue('L' . $i, $transcripcion)
                    ->setCellValue('M' . $i, $arIncapacidadesCobrar->getVrIncapacidad())
                    ->setCellValue('N' . $i, $arIncapacidadesCobrar->getVrPagado())
                    ->setCellValue('O' . $i, $arIncapacidadesCobrar->getVrSaldo());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('IncapacidadesCobrar');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteIncapacidadesCobrar.xlsx"');
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

    private function generarProcesosDisciplinariosExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'FECHA')
                    ->setCellValue('C1', 'PROCESO')
                    ->setCellValue('D1', 'CAUSAL O MOTIVO')
                    ->setCellValue('E1', 'IDENTIFICACIÓN')
                    ->setCellValue('F1', 'EMPLEADO')
                    ->setCellValue('G1', 'GRUPO PAGO')                   
                    ->setCellValue('H1', 'ZONA')
                    ->setCellValue('I1', 'OPERACION')
                    ->setCellValue('J1', 'USUARIO')
                    ->setCellValue('K1', 'PROCESO');

        $i = 2;
        $query = $em->createQuery($this->strSqlProcesosDisciplinariosLista);
        $arProcesosDisciplinarios = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arProcesosDisciplinarios = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'K'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        foreach ($arProcesosDisciplinarios as $arProcesoDisciplinario) {
            if ($arProcesoDisciplinario->getEstadoProcede() == 1){
                    $estadoProcede = "SI";
            } else {
                $estadoProcede = "NO";
            }
            $zona = '';
            if ($arProcesoDisciplinario->getEmpleadoRel()->getCodigoZonaFk() != null){
                $zona = $arProcesoDisciplinario->getEmpleadoRel()->getZonaRel()->getNombre();
            }
            $operacion = '';
            if ($arProcesoDisciplinario->getEmpleadoRel()->getCodigoSubzonaFk() != null){
                $operacion = $arProcesoDisciplinario->getEmpleadoRel()->getSubzonaRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProcesoDisciplinario->getCodigoDisciplinarioPk())
                    ->setCellValue('B' . $i, $arProcesoDisciplinario->getFecha())
                    ->setCellValue('C' . $i, $arProcesoDisciplinario->getDisciplinarioTipoRel()->getNombre())
                    ->setCellValue('D' . $i, $arProcesoDisciplinario->getDisciplinarioMotivoRel()->getNombre())
                    ->setCellValue('E' . $i, $arProcesoDisciplinario->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $arProcesoDisciplinario->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arProcesoDisciplinario->getCentroCostoRel()->getNombre())                    
                    ->setCellValue('H' . $i, $zona)
                    ->setCellValue('I' . $i, $operacion)
                    ->setCellValue('J' . $i, $estadoProcede)
                    ->setCellValue('K' . $i, $arProcesoDisciplinario->getCodigoUsuario());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ProcesosDisciplinarios');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteProcesosDisciplinarios.xlsx"');
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
    
    private function generarDotacionesPendientesExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'FECHA')
                    ->setCellValue('C1', 'CÓDIGO DOTACIÓN')    
                    ->setCellValue('D1', 'CENTRO COSTO')
                    ->setCellValue('E1', 'IDENTIFICACIÓN')
                    ->setCellValue('F1', 'EMPLEADO')
                    ->setCellValue('G1', 'ELEMENTO DOTACIÓN')
                    ->setCellValue('H1', 'CANTIDAD SOLICITADA')
                    ->setCellValue('I1', 'CANTIDAD PENDIENTE')
                    ->setCellValue('J1', 'NÚMERO INTERNO REFERENCIA')
                    ->setCellValue('K1', 'SERIE')
                    ->setCellValue('L1', 'LOTE');

        $i = 2;
        $query = $em->createQuery($this->strSqlDotacionesPendientesLista);
        $arDotacionesPendientes = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle();
        $arDotacionesPendientes = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }     
        foreach ($arDotacionesPendientes as $arDotacionPendiente) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arDotacionPendiente->getCodigoDotacionDetallePk())
                    ->setCellValue('B' . $i, $arDotacionPendiente->getDotacionRel()->getFecha()->format('Y/m/d'))
                    ->setCellValue('C' . $i, $arDotacionPendiente->getDotacionRel()->getCodigoDotacionPk())
                    ->setCellValue('D' . $i, $arDotacionPendiente->getDotacionRel()->getCentroCostoRel()->getNombre())
                    ->setCellValue('E' . $i, $arDotacionPendiente->getDotacionRel()->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $arDotacionPendiente->getDotacionRel()->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arDotacionPendiente->getDotacionElementoRel()->getDotacion())
                    ->setCellValue('H' . $i, $arDotacionPendiente->getCantidadAsignada())
                    ->setCellValue('I' . $i, $arDotacionPendiente->getCantidadAsignada() - $arDotacionPendiente->getCantidadDevuelta())
                    ->setCellValue('J' . $i, $arDotacionPendiente->getDotacionRel()->getCodigoInternoReferencia())
                    ->setCellValue('K' . $i, $arDotacionPendiente->getSerie())
                    ->setCellValue('L' . $i, $arDotacionPendiente->getLote());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('DotacionesPendientes');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteDotacionesPendientes.xlsx"');
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

    private function generarEmpleadoExcel() {
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'AV'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        } 
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'DV')
                    ->setCellValue('E1', 'CIUDAD EXPEDICIÓN IDENTIFICACIÓN')
                    ->setCellValue('F1', 'FECHA EXPEDICIÓN IDENTIFICACIÓN')
                    ->setCellValue('G1', 'LIBRETA MILITAR')
                    ->setCellValue('H1', 'CENTRO COSTO')
                    ->setCellValue('I1', 'NOMBRE')
                    ->setCellValue('J1', 'TELÉFONO')
                    ->setCellValue('K1', 'CELULAR')
                    ->setCellValue('L1', 'DIRECCIÓN')
                    ->setCellValue('M1', 'BARRIO')
                    ->setCellValue('N1', 'CIUDAD RESIDENCIA')
                    ->setCellValue('O1', 'RH')
                    ->setCellValue('P1', 'SEXO')
                    ->setCellValue('Q1', 'CORREO')
                    ->setCellValue('R1', 'FECHA NACIMIENTO')
                    ->setCellValue('S1', 'CIUDAD DE NACIMIENTO')
                    ->setCellValue('T1', 'ESTADO CIVIL')
                    ->setCellValue('U1', 'PADRE DE FAMILIA')
                    ->setCellValue('V1', 'CABEZA DE HOGAR')
                    ->setCellValue('W1', 'NIVEL DE ESTUDIO')
                    ->setCellValue('X1', 'ENTIDAD SALUD')
                    ->setCellValue('Y1', 'ENTIDAD PENSION')
                    ->setCellValue('Z1', 'ENTIDAD CAJA DE COMPESACIÓN')
                    ->setCellValue('AA1', 'ENTIDAD CESANTIAS')
                    ->setCellValue('AB1', 'CLASIFICACIÓN DE RIESGO')
                    ->setCellValue('AC1', 'CUENTA BANCARIA')
                    ->setCellValue('AD1', 'BANCO')
                    ->setCellValue('AE1', 'FECHA CONTRATO')
                    ->setCellValue('AF1', 'FECHA FINALIZA CONTRATO')
                    ->setCellValue('AG1', 'CARGO')
                    ->setCellValue('AH1', 'DESCRIPCIÓN CARGO')
                    ->setCellValue('AI1', 'TIPO PENSIÓN')
                    ->setCellValue('AJ1', 'TIPO COTIZANTE')
                    ->setCellValue('AK1', 'SUBTIPO COTIZANTE')
                    ->setCellValue('AL1', 'ESTADO ACTIVO')
                    ->setCellValue('AM1', 'ESTADO CONTRATO')
                    ->setCellValue('AN1', 'CODIGO CONTRATO')
                    ->setCellValue('AO1', 'TALLA CAMISA')
                    ->setCellValue('AP1', 'TALLA JEANS')
                    ->setCellValue('AQ1', 'TALLA CALZADO')
                    ->setCellValue('AR1', 'DEPARTAMENTO')
                    ->setCellValue('AS1', 'HORARIO')
                    ->setCellValue('AT1', 'DISCAPACIDAD')
                    ->setCellValue('AU1', 'ZONA')
                    ->setCellValue('AV1', 'SUBZONA')
                    ->setCellValue('AW1', 'TIPO');

        $i = 2;
        $query = $em->createQuery($this->strSqlEmpleadosLista);
        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleados = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'AW'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        foreach ($arEmpleados as $arEmpleado) {
            if ($arEmpleado->getCodigoCentroCostoFk() == null){
                $centroCosto = "";
            }else{
                $centroCosto = $arEmpleado->getCentroCostoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoClasificacionRiesgoFk() == null){
                $clasificacionRiesgo = "";
            }else{
                $clasificacionRiesgo = $arEmpleado->getClasificacionRiesgoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoCargoFk() == null){
                $cargo = "";
            }else{
                $cargo = $arEmpleado->getCargoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoTipoPensionFk() == null){
                $tipoPension = "";
            }else{
                $tipoPension = $arEmpleado->getTipoPensionRel()->getNombre();
            }
            if ($arEmpleado->getCodigoTipoCotizanteFk() == null){
                $tipoCotizante = "";
            }else{
                $tipoCotizante = $arEmpleado->getSsoTipoCotizanteRel()->getNombre();
            }
            if ($arEmpleado->getCodigoSubtipoCotizanteFk() == null){
                $subtipoCotizante = "";
            }else{
                $subtipoCotizante = $arEmpleado->getSsoSubtipoCotizanteRel()->getNombre();
            }
            if ($arEmpleado->getCodigoEntidadSaludFk() == null){
                $entidadSalud = "";
            }else{
                $entidadSalud = $arEmpleado->getEntidadSaludRel()->getNombre();
            }
            
            if ($arEmpleado->getCodigoEntidadPensionFk() == null){
                $entidadPension = "";
            }else{
                $entidadPension = $arEmpleado->getEntidadPensionRel()->getNombre();
            }
            
            if ($arEmpleado->getCodigoEntidadCajaFk() == null){
                $entidadCaja = "";
            }else{
                $entidadCaja = $arEmpleado->getEntidadCajaRel()->getNombre();
            }        
            if ($arEmpleado->getCodigoSexoFk() == "M"){
                $sexo = "MASCULINO";
            }else{
                $sexo = "FEMENINO";
            }
            if ($arEmpleado->getPadreFamilia() == 0){
                $padreFamilia = "NO";
            }else{
                $padreFamilia = "SI";
            }
            if ($arEmpleado->getCabezaHogar() == 0){
                $cabezaHogar = "NO";
            }else{
                $cabezaHogar = "SI";
            }
            if ($arEmpleado->getEstadoActivo() == 0){
                $estadoActivo = "NO";
            }else{
                $estadoActivo = "SI";
            }
            if ($arEmpleado->getDiscapacidad() == 0){
                $discapacidad = "NO";
            }else{
                $discapacidad = "SI";
            }
            if ($arEmpleado->getEstadoContratoActivo() == 0){
                $estadoContratoActivo = "NO VIGENTE";
            }else{
                $estadoContratoActivo = "VIGENTE";
            }
            if ($arEmpleado->getCodigoDepartamentoEmpresaFk() == null){
                $departamentoEmpresa = "";
            }else{
                $departamentoEmpresa = $arEmpleado->getDepartamentoEmpresaRel()->getNombre();
            }
            if ($arEmpleado->getCodigoHorarioFk() == null){
                $horario = "";
            }else{
                $horario = $arEmpleado->getHorarioRel()->getNombre();
            }
            if ($arEmpleado->getCodigoEmpleadoEstudioTipoFk() == null){
                $empleadoEstudioTipo = "";
            }else{
                $empleadoEstudioTipo = $arEmpleado->getEmpleadoEstudioTipoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoEntidadCesantiaFk() == null){
                $entidadCesantia = "";
            }else{
                $entidadCesantia = $arEmpleado->getEntidadCesantiaRel()->getNombre();
            }
            if ($arEmpleado->getCodigoCiudadExpedicionFk() != null){
                $ciudadExpedicion = $arEmpleado->getciudadExpedicionRel()->getNombre();
            } else {
                $ciudadExpedicion = "";
            }
            if ($arEmpleado->getCodigoCiudadNacimientoFk() != null){
                $ciudadNacimiento = $arEmpleado->getCiudadNacimientoRel()->getNombre();
            } else {
                $ciudadNacimiento = "";
            }
            if ($arEmpleado->getCodigoRhPk() != null){
                $rh = $arEmpleado->getRhRel()->getTipo();
            } else {
                $rh = "";
            }
            if ($arEmpleado->getCodigoBancoFk() != null){
                $banco = $arEmpleado->getBancoRel()->getNombre();
            } else {
                $banco = "";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleado->getCodigoEmpleadoPk())
                    ->setCellValue('B' . $i, $arEmpleado->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('C' . $i, $arEmpleado->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arEmpleado->getDigitoVerificacion())
                    ->setCellValue('E' . $i, $ciudadExpedicion)
                    ->setCellValue('F' . $i, $arEmpleado->getFechaExpedicionIdentificacion())
                    ->setCellValue('G' . $i, $arEmpleado->getLibretaMilitar())
                    ->setCellValue('H' . $i, $centroCosto)
                    ->setCellValue('I' . $i, $arEmpleado->getNombreCorto())
                    ->setCellValue('J' . $i, $arEmpleado->getTelefono())
                    ->setCellValue('K' . $i, $arEmpleado->getCelular())
                    ->setCellValue('L' . $i, $arEmpleado->getDireccion())
                    ->setCellValue('M' . $i, $arEmpleado->getBarrio())
                    ->setCellValue('N' . $i, $arEmpleado->getciudadRel()->getNombre())
                    ->setCellValue('O' . $i, $rh)
                    ->setCellValue('P' . $i, $sexo)
                    ->setCellValue('Q' . $i, $arEmpleado->getCorreo())
                    ->setCellValue('R' . $i, $arEmpleado->getFechaNacimiento())
                    ->setCellValue('S' . $i, $ciudadNacimiento)
                    ->setCellValue('T' . $i, $arEmpleado->getEstadoCivilRel()->getNombre())
                    ->setCellValue('U' . $i, $padreFamilia)
                    ->setCellValue('V' . $i, $cabezaHogar)
                    ->setCellValue('W' . $i, $empleadoEstudioTipo)
                    ->setCellValue('X' . $i, $entidadSalud)
                    ->setCellValue('Y' . $i, $entidadPension)
                    ->setCellValue('Z' . $i, $entidadCaja)
                    ->setCellValue('AA' . $i, $entidadCesantia)
                    ->setCellValue('AB' . $i, $clasificacionRiesgo)
                    ->setCellValue('AC' . $i, $arEmpleado->getCuenta())
                    ->setCellValue('AD' . $i, $banco)
                    ->setCellValue('AE' . $i, $arEmpleado->getFechaContrato())
                    ->setCellValue('AF' . $i, $arEmpleado->getFechaFinalizaContrato())
                    ->setCellValue('AG' . $i, $cargo)
                    ->setCellValue('AH' . $i, $arEmpleado->getCargoDescripcion())
                    ->setCellValue('AI' . $i, $tipoPension)
                    ->setCellValue('AJ' . $i, $tipoCotizante)
                    ->setCellValue('AK' . $i, $subtipoCotizante)
                    ->setCellValue('AL' . $i, $estadoActivo)
                    ->setCellValue('AM' . $i, $estadoContratoActivo)
                    ->setCellValue('AN' . $i, $arEmpleado->getCodigoContratoActivoFk())
                    ->setCellValue('AO' . $i, $arEmpleado->getCamisa())
                    ->setCellValue('AP' . $i, $arEmpleado->getJeans())
                    ->setCellValue('AQ' . $i, $arEmpleado->getCalzado())
                    ->setCellValue('AR' . $i, $departamentoEmpresa)
                    ->setCellValue('AS' . $i, $horario)
                    ->setCellValue('AT' . $i, $discapacidad);
            if($arEmpleado->getCodigoZonaFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU' . $i, $arEmpleado->getZonaRel()->getNombre()); 
            }
            if($arEmpleado->getCodigoSubzonaFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV' . $i, $arEmpleado->getSubzonaRel()->getNombre()); 
            }
            if($arEmpleado->getCodigoEmpleadoTipoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AW' . $i, $arEmpleado->getEmpleadoTipoRel()->getNombre()); 
            }            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Empleados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Empleados.xlsx"');
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

    private function generarAportesExcel() {
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
        for($col = 'A'; $col !== 'BI'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
                } 
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'SUCURSAL')
                    ->setCellValue('C1', 'DOCUMENTO')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'SEC')
                    ->setCellValue('F1', 'T_D')
                    ->setCellValue('G1', 'T_COT')
                    ->setCellValue('H1', 'SUBT_COT')
                    ->setCellValue('I1', 'DEPARTAMENTO')
                    ->setCellValue('J1', 'MUNICIPIO')
                    ->setCellValue('K1', 'ING')
                    ->setCellValue('L1', 'RET')
                    ->setCellValue('M1', 'T_D_O_E')
                    ->setCellValue('N1', 'T_A_O_E')
                    ->setCellValue('O1', 'T_D_O_P')
                    ->setCellValue('P1', 'T_A_O_P')
                    ->setCellValue('Q1', 'VSP')
                    ->setCellValue('R1', 'CORRECCIONES')
                    ->setCellValue('S1', 'VST')
                    ->setCellValue('T1', 'SLN')
                    ->setCellValue('U1', 'D_SLN')
                    ->setCellValue('V1', 'SALARIO')
                    ->setCellValue('W1', 'S_M_A')
                    ->setCellValue('X1', 'S_I')
                    ->setCellValue('Y1', 'SUP')
                    ->setCellValue('Z1', 'IG')
                    ->setCellValue('AA1', 'DIAS')
                    ->setCellValue('AB1', 'LM')
                    ->setCellValue('AC1', 'DIAS')
                    ->setCellValue('AD1', 'VAC')
                    ->setCellValue('AE1', 'A_VOLUNTARIO')
                    ->setCellValue('AF1', 'VCT')
                    ->setCellValue('AG1', 'IRP')
                    ->setCellValue('AH1', 'E_PENSIÓN')
                    ->setCellValue('AI1', 'E_PENSIÓN TRASLADA')
                    ->setCellValue('AJ1', 'E_SALUD')
                    ->setCellValue('AK1', 'E_SALUD TRASLADA')
                    ->setCellValue('AL1', 'C_COMPENSACIÓN')
                    ->setCellValue('AM1', 'D_P')
                    ->setCellValue('AN1', 'D_S')
                    ->setCellValue('AO1', 'D_R')
                    ->setCellValue('AP1', 'D_C')
                    ->setCellValue('AQ1', 'I_P')
                    ->setCellValue('AR1', 'I_S')
                    ->setCellValue('AS1', 'I_R')
                    ->setCellValue('AT1', 'I_C')
                    ->setCellValue('AU1', 'T_P')
                    ->setCellValue('AV1', 'T_S')
                    ->setCellValue('AW1', 'T_R')
                    ->setCellValue('AX1', 'T_C')
                    ->setCellValue('AY1', 'C_P')
                    ->setCellValue('AZ1', 'C_S')
                    ->setCellValue('BA1', 'C_R')
                    ->setCellValue('BB1', 'C_C')
                    ->setCellValue('BC1', 'C_SN')
                    ->setCellValue('BD1', 'C_I')
                    ->setCellValue('BE1', 'A_V_F_P_O')
                    ->setCellValue('BF1', 'C_V_F_P_O')
                    ->setCellValue('BG1', 'TOTAL COTIZACIÓN')
                    ->setCellValue('BH1', 'A_F_S_P_SL')
                    ->setCellValue('BI1', 'A_F_S_P_SB');
        $i = 2;
        $query = $em->createQuery($this->strSqlAportesLista);
        $arAportes = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
        $arAportes = $query->getResult();

        foreach ($arAportes as $arAporte) {
        /*$arEntidadPension = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
        $arEntidadPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->findBy(array('codigoInterface' =>$arAporte->getCodigoEntidadPensionPertenece()));
        $arEntidadPensionPertenece = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
        $arEntidadPensionPertenece = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->find($arEntidadPension[0]);

        $arEntidadSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
        $arEntidadSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->findBy(array('codigoInterface' =>$arAporte->getCodigoEntidadSaludPertenece()));
        $arEntidadSaludPertenece = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
        $arEntidadSaludPertenece = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->find($arEntidadSalud[0]);

        $arEntidadCaja = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja();
        $arEntidadCaja = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadCaja')->findBy(array('codigoInterface' =>$arAporte->getCodigoEntidadCajaPertenece()));
        $arEntidadCajaPertenece = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja();
        $arEntidadCajaPertenece = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadCaja')->find($arEntidadCaja[0]);
         * 
         */
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'BZ'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        for($col = 'V'; $col !== 'Y'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        for($col = 'AQ'; $col !== 'BJ'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAporte->getCodigoAportePk())
                    ->setCellValue('B' . $i, $arAporte->getSsoSucursalRel()->getNombre())
                    ->setCellValue('C' . $i, $arAporte->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arAporte->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arAporte->getSecuencia())
                    ->setCellValue('F' . $i, $arAporte->getEmpleadoRel()->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('G' . $i, $arAporte->getContratoRel()->getSsoTipoCotizanteRel()->getNombre())
                    ->setCellValue('H' . $i, $arAporte->getContratoRel()->getSsoSubtipoCotizanteRel()->getNombre())
                    ->setCellValue('I' . $i, $arAporte->getEmpleadoRel()->getCiudadRel()->getDepartamentoRel()->getNombre())
                    ->setCellValue('J' . $i, $arAporte->getEmpleadoRel()->getCiudadRel()->getNombre())
                    ->setCellValue('K' . $i, $arAporte->getIngreso())
                    ->setCellValue('L' . $i, $arAporte->getRetiro())
                    ->setCellValue('M' . $i, $arAporte->getTrasladoDesdeOtraEps())
                    ->setCellValue('N' . $i, $arAporte->getTrasladoAOtraEps())
                    ->setCellValue('O' . $i, $arAporte->getTrasladoDesdeOtraPension())
                    ->setCellValue('P' . $i, $arAporte->getTrasladoAOtraPension())
                    ->setCellValue('Q' . $i, $arAporte->getVariacionPermanenteSalario())
                    ->setCellValue('R' . $i, $arAporte->getCorrecciones())
                    ->setCellValue('S' . $i, $arAporte->getVariacionTransitoriaSalario())
                    ->setCellValue('T' . $i, $arAporte->getSuspensionTemporalContratoLicenciaServicios())
                    ->setCellValue('U' . $i, $arAporte->getDiasLicencia())
                    ->setCellValue('V' . $i, $arAporte->getSalarioBasico())
                    ->setCellValue('W' . $i, $arAporte->getSalarioMesAnterior())
                    ->setCellValue('X' . $i, $arAporte->getSalarioIntegral())
                    ->setCellValue('Y' . $i, $arAporte->getSuplementario())
                    ->setCellValue('Z' . $i, $arAporte->getIncapacidadGeneral())
                    ->setCellValue('AA' . $i, $arAporte->getDiasIncapacidadGeneral())
                    ->setCellValue('AB' . $i, $arAporte->getLicenciaMaternidad())
                    ->setCellValue('AC' . $i, $arAporte->getDiasLicenciaMaternidad())
                    ->setCellValue('AD' . $i, $arAporte->getVacaciones())
                    ->setCellValue('AE' . $i, $arAporte->getAporteVoluntario())
                    ->setCellValue('AF' . $i, $arAporte->getVariacionCentrosTrabajo())
                    ->setCellValue('AG' . $i, $arAporte->getIncapacidadAccidenteTrabajoEnfermedadProfesional())
                    ->setCellValue('AH' . $i, $arAporte->getEntidadPensionRel()->getNombre())
                    ->setCellValue('AI' . $i, $arAporte->getCodigoEntidadPensionTraslada())
                    ->setCellValue('AJ' . $i, $arAporte->getEntidadSaludRel()->getNombre())
                    ->setCellValue('AK' . $i, $arAporte->getCodigoEntidadSaludTraslada())
                    ->setCellValue('AL' . $i, $arAporte->getEntidadCajaRel()->getNombre())
                    ->setCellValue('AM' . $i, $arAporte->getDiasCotizadosPension())
                    ->setCellValue('AN' . $i, $arAporte->getDiasCotizadosSalud())
                    ->setCellValue('AO' . $i, $arAporte->getDiasCotizadosRiesgosProfesionales())
                    ->setCellValue('AP' . $i, $arAporte->getDiasCotizadosCajaCompensacion())
                    ->setCellValue('AQ' . $i, $arAporte->getIbcPension())
                    ->setCellValue('AR' . $i, $arAporte->getIbcSalud())
                    ->setCellValue('AS' . $i, $arAporte->getIbcRiesgosProfesionales())
                    ->setCellValue('AT' . $i, $arAporte->getIbcCaja())
                    ->setCellValue('AU' . $i, $arAporte->getTarifaPension())
                    ->setCellValue('AV' . $i, $arAporte->getTarifaSalud())
                    ->setCellValue('AW' . $i, $arAporte->getTarifaRiesgos())
                    ->setCellValue('AX' . $i, $arAporte->getTarifaCaja())
                    ->setCellValue('AY' . $i, $arAporte->getCotizacionPension())
                    ->setCellValue('AZ' . $i, $arAporte->getCotizacionSalud())
                    ->setCellValue('BA' . $i, $arAporte->getCotizacionRiesgos())
                    ->setCellValue('BB' . $i, $arAporte->getCotizacionCaja())
                    ->setCellValue('BC' . $i, $arAporte->getCotizacionSena())
                    ->setCellValue('BD' . $i, $arAporte->getCotizacionIcbf())
                    ->setCellValue('BE' . $i, $arAporte->getAporteVoluntarioFondoPensionesObligatorias())
                    ->setCellValue('BF' . $i, $arAporte->getCotizacionVoluntarioFondoPensionesObligatorias())
                    ->setCellValue('BG' . $i, $arAporte->getTotalCotizacion())
                    ->setCellValue('BH' . $i, $arAporte->getAportesFondoSolidaridadPensionalSolidaridad())
                    ->setCellValue('BI' . $i, $arAporte->getAportesFondoSolidaridadPensionalSubsistencia())
                    ;
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Aportes');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteAportes.xlsx"');
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

    private function generarVacacionesPagarExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO CONTRATO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'NÚMERO')
                    ->setCellValue('E1', 'IDENTIFICACIÓN')
                    ->setCellValue('F1', 'EMPLEADO')
                    ->setCellValue('G1', 'CENTRO COSTO')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'FECHA ULTIMO PAGO')
                    ->setCellValue('J1', 'FECHA ULTIMO VACACIONES')
                    ->setCellValue('K1', 'SALARIO')
                    ->setCellValue('L1', 'VIGENTE');
        $i = 2;
        $query = $em->createQuery($this->strSqlVacacionesPagarLista);
        $arVacacionesPagar = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arVacacionesPagar = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        for($col = 'K'; $col !== 'K'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        foreach ($arVacacionesPagar as $arVacacionesPagar) {
            if ($arVacacionesPagar->getEstadoActivo() == 1){
                $vigente = "SI";
            } else {
                $vigente = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arVacacionesPagar->getCodigoContratoPk())
                    ->setCellValue('B' . $i, $arVacacionesPagar->getContratoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arVacacionesPagar->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arVacacionesPagar->getNumero())
                    ->setCellValue('E' . $i, $arVacacionesPagar->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $arVacacionesPagar->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arVacacionesPagar->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $arVacacionesPagar->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $arVacacionesPagar->getFechaUltimoPago()->format('Y/m/d'))
                    ->setCellValue('J' . $i, $arVacacionesPagar->getFechaUltimoPagoVacaciones()->format('Y/m/d'))
                    ->setCellValue('K' . $i, $arVacacionesPagar->getVrSalarioPago())
                    ->setCellValue('L' . $i, $vigente);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ReporteVacacionesPorPagar');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteVacacionesPorPagar.xlsx"');
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
    
    private function generarFechaTerminacionExcel() {
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'TIPO CONTRATO')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'CENTRO COSTO')
                    ->setCellValue('F1', 'CARGO')
                    ->setCellValue('G1', 'DESDE')
                    ->setCellValue('H1', 'HASTA')
                    ->setCellValue('I1', 'MOTIVO')
                    ->setCellValue('J1', 'TIPO')
                    ->setCellValue('K1', 'ZONA')
                    ->setCellValue('L1', 'SUBZONA')
                    ->setCellValue('M1', 'USUARIO');
        $i = 2;
        $query = $em->createQuery($this->strSqlFechaTerminacionLista);
        $arFechaTerminaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arFechaTerminaciones = $query->getResult();
        foreach ($arFechaTerminaciones as $arFechaTerminacion) {
            $tipo = "";
            if ($arFechaTerminacion->getEmpleadoRel()->getCodigoEmpleadoTipoFk() != null){
                $tipo = $arFechaTerminacion->getEmpleadoRel()->getEmpleadoTipoRel()->getNombre();
            }
            $zona = "";
            if ($arFechaTerminacion->getEmpleadoRel()->getCodigoZonaFk() != null){
                $zona = $arFechaTerminacion->getEmpleadoRel()->getZonaRel()->getNombre();
            }
            $subzona = "";
            if ($arFechaTerminacion->getEmpleadoRel()->getCodigoSubzonaFk() != null){
                $subzona = $arFechaTerminacion->getEmpleadoRel()->getSubzonaRel()->getNombre();
            }
            $motivo = "";
            if ($arFechaTerminacion->getCodigoMotivoTerminacionContratoFk() != null){
                $motivo = $arFechaTerminacion->getTerminacionContratoRel()->getMotivo();
            }
            $cargo = "";
            if ($arFechaTerminacion->getCodigoCargoFk() != null){
                $cargo = $arFechaTerminacion->getCargoRel()->getNombre();
            }            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFechaTerminacion->getCodigoContratoPk())
                    ->setCellValue('B' . $i, $arFechaTerminacion->getContratoTipoRel()->getNombreCorto())                    
                    ->setCellValue('C' . $i, $arFechaTerminacion->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arFechaTerminacion->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arFechaTerminacion->getCentroCostoRel()->getNombre())
                    ->setCellValue('F' . $i, $cargo)
                    ->setCellValue('G' . $i, $arFechaTerminacion->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('H' . $i, $arFechaTerminacion->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $motivo)
                    ->setCellValue('J' . $i, $tipo)
                    ->setCellValue('K' . $i, $zona)
                    ->setCellValue('L' . $i, $subzona)
                    ->setCellValue('M' . $i, $arFechaTerminacion->getCodigoUsuarioTermina());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('ReporteFechaTerminacion');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteFechaTerminacion.xlsx"');
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
    
    private function generarIngresosContratosExcel() {
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
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'CONTRATO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'NÚMERO')
                    ->setCellValue('E1', 'IDENTIFICACIÓN')
                    ->setCellValue('F1', 'EMPLEADO')
                    ->setCellValue('G1', 'CENTRO COSTO')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'EMPLEADO TIPO')
                    ->setCellValue('J1', 'ZONA')
                    ->setCellValue('K1', 'SUBZONA');
        $i = 2;
        $query = $em->createQuery($this->strSqlIngresosContratosLista);
        $arFechaIngresos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arFechaIngresos = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'K'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        foreach ($arFechaIngresos as $arFechaIngreso) {
            $tipo = "";
            if ($arFechaIngreso->getEmpleadoRel()->getCodigoEmpleadoTipoFk() != null){
                $tipo = $arFechaIngreso->getEmpleadoRel()->getEmpleadoTipoRel()->getNombre();
            }
            $zona = "";
            if ($arFechaIngreso->getEmpleadoRel()->getCodigoZonaFk() != null){
                $zona = $arFechaIngreso->getEmpleadoRel()->getZonaRel()->getNombre();
            }
            $subzona = "";
            if ($arFechaIngreso->getEmpleadoRel()->getCodigoSubzonaFk() != null){
                $subzona = $arFechaIngreso->getEmpleadoRel()->getSubzonaRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFechaIngreso->getCodigoContratoPk())
                    ->setCellValue('B' . $i, $arFechaIngreso->getContratoTipoRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arFechaIngreso->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arFechaIngreso->getNumero())
                    ->setCellValue('E' . $i, $arFechaIngreso->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $arFechaIngreso->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arFechaIngreso->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $arFechaIngreso->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $tipo)
                    ->setCellValue('J' . $i, $zona)
                    ->setCellValue('K' . $i, $subzona);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ReporteFechaIngreso');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteFechaIngreso.xlsx"');
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
    
    private function generarContratosPeriodoExcel() {
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
        for($col = 'A'; $col !== 'K'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'TIPO CONTRATO')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'GRUPO PAGO')
                    ->setCellValue('F1', 'DESDE')
                    ->setCellValue('G1', 'HASTA')
                    ->setCellValue('H1', 'CARGO')
                    ->setCellValue('I1', 'TIPO')
                    ->setCellValue('J1', 'ZONA')
                    ->setCellValue('K1', 'SUBZONA')
                    ->setCellValue('L1', 'USUARIO');
        $i = 2;
        $query = $em->createQuery($this->strSqlContratosPeriodoLista);
        $arFechaTerminaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arFechaTerminaciones = $query->getResult();
        foreach ($arFechaTerminaciones as $arFechaTerminacion) {
            $tipo = "";
            if ($arFechaTerminacion->getEmpleadoRel()->getCodigoEmpleadoTipoFk() != null){
                $tipo = $arFechaTerminacion->getEmpleadoRel()->getEmpleadoTipoRel()->getNombre();
            }
            $zona = "";
            if ($arFechaTerminacion->getEmpleadoRel()->getCodigoZonaFk() != null){
                $zona = $arFechaTerminacion->getEmpleadoRel()->getZonaRel()->getNombre();
            }
            $subzona = "";
            if ($arFechaTerminacion->getEmpleadoRel()->getCodigoSubzonaFk() != null){
                $subzona = $arFechaTerminacion->getEmpleadoRel()->getSubzonaRel()->getNombre();
            }
            $motivo = "";
            if ($arFechaTerminacion->getCodigoMotivoTerminacionContratoFk() != null){
                $motivo = $arFechaTerminacion->getTerminacionContratoRel()->getMotivo();
            }
            $cargo = "";
            if($arFechaTerminacion->getCodigoCargoFk()) {
                $cargo = $arFechaTerminacion->getCargoRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFechaTerminacion->getCodigoContratoPk())
                    ->setCellValue('B' . $i, $arFechaTerminacion->getContratoTipoRel()->getNombreCorto())                    
                    ->setCellValue('C' . $i, $arFechaTerminacion->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arFechaTerminacion->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arFechaTerminacion->getCentroCostoRel()->getNombre())
                    ->setCellValue('F' . $i, $arFechaTerminacion->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('G' . $i, $arFechaTerminacion->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('H' . $i, $cargo)
                    ->setCellValue('I' . $i, $tipo)
                    ->setCellValue('J' . $i, $zona)
                    ->setCellValue('K' . $i, $subzona)
                    ->setCellValue('L' . $i, $arFechaTerminacion->getCodigoUsuarioTermina());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('ContratosPeriodo');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ContratosPeriodo.xlsx"');
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
