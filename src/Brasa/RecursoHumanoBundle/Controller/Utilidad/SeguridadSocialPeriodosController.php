<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte;
use Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSsoPeriodoType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSsoPeriodoDetalleType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSsoAporteType;

class SeguridadSocialPeriodosController extends Controller
{
    var $strDqlLista = "";
    var $strDqlListaDetalle = "";
    var $strDqlListaEmpleados = "";
    var $strDqlListaDetalleAportes = "";
    var $strCodigoPeriodoDetalleTraslados = "";
    var $strCodigoPeriodoDetalleCopias = "";

    /**
     * @Route("/rhu/seguridadsocial/periodo/lista", name="brs_rhu_ss_periodo_lista")
     */
    public function listaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 77)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            if ($request->request->get('OpGenerar')) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $codigoPeriodo = $request->request->get('OpGenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->generar($codigoPeriodo);
            }
            if ($request->request->get('OpDesgenerar')) {
                $codigoPeriodo = $request->request->get('OpDesgenerar');
                $resultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->desgenerar($codigoPeriodo);
                if ($resultado == false) {
                    $objMensaje->Mensaje("error", "No se puede desgenerar el registro por que hay sucursal(es) generada(s) y cerrada(s)", $this);
                }
            }
            if ($request->request->get('OpCerrar')) {
                $codigoPeriodo = $request->request->get('OpCerrar');
                //$em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->cerrar($codigoPeriodo);
                $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
                $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
                $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
                if ($arPeriodo->getEstadoGenerado() == 0) {
                    $objMensaje->Mensaje("error", "Debe generar periodo para poder cerrarlo", $this);
                } else {
                    $arPeriodoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
                    $arPeriodoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->findBy(array('codigoPeriodoFk' => $codigoPeriodo, 'estadoCerrado' => 0));
                    $intTotal = count($arPeriodoDetalles);
                    if ($intTotal > 0) {
                        $objMensaje->Mensaje("error", "Hay periodos de sucursales sin cerrar", $this);
                    } else {
                        $arPeriodo->setEstadoCerrado(1);
                        $em->persist($arPeriodo);
                        $em->flush();
                    }

                }
            }
            if ($request->request->get('OpEliminar')) {
                //$codigoPeriodo = $request->request->get('OpEliminar');
                $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodo) {
                        $arPeriodoEliminar = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
                        $arPeriodoEliminar = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
                        if ($arPeriodoEliminar->getEstadoCerrado() == 1) {
                            $objMensaje->Mensaje("error", "No se puede eliminar el registro se encuentra cerrado", $this);
                        } else {
                            if ($arPeriodoEliminar->getEstadoGenerado() == 1) {
                                $objMensaje->Mensaje("error", "Se debe desgenerar el registro para eliminarlo", $this);
                            } else {
                                $em->remove($arPeriodoEliminar);
                                $em->flush();
                            }
                        }
                    }
                }
                return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_lista'));
            }
        }
        $arSsoPeriodos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:lista.html.twig', array(
            'arSsoPeriodos' => $arSsoPeriodos,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seguridadsocial/periodo/nuevo/{codigoPeriodo}", name="brs_rhu_ss_periodo_nuevo")
     */
    public function nuevoAction($codigoPeriodo)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
        if ($codigoPeriodo != 0) {
            $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
        }
        $form = $this->createForm(new RhuSsoPeriodoType(), $arPeriodo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            // guardar la tarea en la base de datos
            $arPeriodo = $form->getData();
            $em->persist($arPeriodo);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:nuevo.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seguridadsocial/periodo/detalle/{codigoPeriodo}", name="brs_rhu_ss_periodo_detalle")
     */
    public function detalleAction($codigoPeriodo)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        $this->listarDetalle($codigoPeriodo);
        if ($form->isValid()) {
            if ($request->request->get('OpEliminar')) {
                $codigoPeriodo = $request->request->get('OpEliminar');
                $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodoDetalle) {
                        $arPeriodoDetalleEliminar = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
                        $arPeriodoDetalleEliminar = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
                        if ($arPeriodoDetalleEliminar->getEstadoGenerado() == 1) {
                            $objMensaje->Mensaje("error", "No se puede eliminar el registro se encuentra generado", $this);
                        } else {
                            $arPeriodoEmpleadoEliminar = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                            $arPeriodoEmpleadoEliminar = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->findBy(array('codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));
                            foreach ($arPeriodoEmpleadoEliminar AS $arPeriodoEmpleadoEliminar) {
                                $em->remove($arPeriodoEmpleadoEliminar);
                                $em->flush();
                            }
                            $em->remove($arPeriodoDetalleEliminar);
                            $em->flush();
                        }
                        return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
                    }
                }
            }
            if ($request->request->get('OpGenerar')) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $codigoPeriodoDetalle = $request->request->get('OpGenerar');
                $arSsoAportes = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
                $arSsoAportes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->findBy(array('codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));
                $resultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->generar($codigoPeriodoDetalle);
//                $resultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->generar2($codigoPeriodoDetalle);
                if ($resultado == false) {
                    $objMensaje->Mensaje("error", "No hay personal a generar en el periodo detalle " . $codigoPeriodoDetalle . "", $this);
                }
                return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
            }
            if ($request->request->get('OpDesgenerar')) {
                $codigoPeriodoDetalle = $request->request->get('OpDesgenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->desgenerar($codigoPeriodoDetalle);
                return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
            }
            if ($request->request->get('OpCerrar')) {
                $codigoPeriodo = $request->request->get('OpCerrar');
                $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
                $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodo);
                if ($arPeriodoDetalle->getEstadoGenerado() == 0) {
                    $objMensaje->Mensaje("error", "Debe generar periodo de la sucursal para poder cerrarlo", $this);
                } else {
                    $arPeriodoDetalle->setEstadoCerrado(1);
                    $em->persist($arPeriodoDetalle);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
            }
            if ($request->request->get('OpGenerarArchivo')) {
                $codigoPeriodoDetalle = $request->request->get('OpGenerarArchivo');
                $this->generarPlano($codigoPeriodoDetalle);
            }
            if ($request->request->get('OpGenerarExcel')) {
                ob_clean();
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $codigoPeriodoDetalle = $request->request->get('OpGenerarExcel');
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
                for ($col = 'A'; $col !== 'AN'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                }
                for ($col = 'G'; $col !== 'M'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
                }
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'DOCUMENTO')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'G.PAGO')
                    ->setCellValue('E1', 'CONTRATO')
                    ->setCellValue('F1', 'C.COSTO')
                    ->setCellValue('G1', 'ING')
                    ->setCellValue('H1', 'RET')
                    ->setCellValue('I1', 'VST')
                    ->setCellValue('J1', 'SLN')
                    ->setCellValue('K1', 'IGE')
                    ->setCellValue('L1', 'LMA')
                    ->setCellValue('M1', 'VAC')
                    ->setCellValue('N1', 'IRP')
                    ->setCellValue('O1', 'SALARIO')
                    ->setCellValue('P1', 'VR.VAC')
                    ->setCellValue('Q1', 'SI')
                    ->setCellValue('R1', 'D.P')
                    ->setCellValue('S1', 'D.S')
                    ->setCellValue('T1', 'D.R.P')
                    ->setCellValue('U1', 'D.C')
                    ->setCellValue('V1', 'IBC P')
                    ->setCellValue('W1', 'IBC S')
                    ->setCellValue('X1', 'IBC R')
                    ->setCellValue('Y1', 'IBC C')
                    ->setCellValue('Z1', 'T.P')
                    ->setCellValue('AA1', 'T.S')
                    ->setCellValue('AB1', 'T.R')
                    ->setCellValue('AC1', 'T.C')
                    ->setCellValue('AD1', 'T.SN')
                    ->setCellValue('AE1', 'T.I')
                    ->setCellValue('AF1', 'C.P')
                    ->setCellValue('AG1', 'C.FSSO')
                    ->setCellValue('AH1', 'C.FSSU')
                    ->setCellValue('AI1', 'C.S')
                    ->setCellValue('AJ1', 'C.R')
                    ->setCellValue('AK1', 'C.C')
                    ->setCellValue('AL1', 'C.SN')
                    ->setCellValue('AM1', 'C.I')
                    ->setCellValue('AN1', 'TOTAL')
                    ->setCellValue('AO1', 'E.PENSION')
                    ->setCellValue('AP1', 'E.SALUD')
                    ->setCellValue('AQ1', 'E.RIESGOS')
                    ->setCellValue('AR1', 'E.CAJA');

                $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
                $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
                /*$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B1', $arPeriodoDetalle->getCodigoPeriodoDetallePk())
                    ->setCellValue('B2', $arPeriodoDetalle->getCodigoSucursalFk())
                    ->setCellValue('B3', $arPeriodoDetalle->getSsoSucursalRel()->getNombre())    ;*/
                $i = 2;
                $arSsoAportes = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
                $arSsoAportes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->findBy(array('codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));
                foreach ($arSsoAportes as $arSsoAporte) {
                    $suspencionTemporalContratoLicenciaServicios = "";
                    if ($arSsoAporte->getSuspensionTemporalContratoLicenciaServicios() == "X") {
                        $suspencionTemporalContratoLicenciaServicios = $arSsoAporte->getSuspensionTemporalContratoLicenciaServicios() . " " . $arSsoAporte->getDiasLicencia();
                    }
                    $incapacidadGeneral = "";
                    if ($arSsoAporte->getIncapacidadGeneral() == "X") {
                        $incapacidadGeneral = $arSsoAporte->getIncapacidadGeneral() . " " . $arSsoAporte->getDiasIncapacidadGeneral();
                    }
                    $licenciaMaternidad = "";
                    if ($arSsoAporte->getLicenciaMaternidad() == "X") {
                        $licenciaMaternidad = $arSsoAporte->getLicenciaMaternidad() . " " . $arSsoAporte->getDiasLicenciaMaternidad();
                    }
                    $vacaciones = "";
                    if ($arSsoAporte->getVacaciones() == "X") {
                        $vacaciones = $arSsoAporte->getVacaciones() . " " . $arSsoAporte->getDiasVacaciones();
                    }
                    $riesgosProfesionales = "";
                    if ($arSsoAporte->getIncapacidadAccidenteTrabajoEnfermedadProfesional() > 0) {
                        $riesgosProfesionales = $arSsoAporte->getIncapacidadAccidenteTrabajoEnfermedadProfesional();
                    }
                    $salarioIntegral = "";
                    if ($arSsoAporte->getSalarioIntegral() == "X") {
                        $salarioIntegral = $arSsoAporte->getSalarioIntegral();
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $arSsoAporte->getCodigoAportePk())
                        ->setCellValue('B' . $i, $arSsoAporte->getEmpleadoRel()->getNumeroIdentificacion())
                        ->setCellValue('C' . $i, $arSsoAporte->getEmpleadoRel()->getNombreCorto())
                        ->setCellValue('D' . $i, $arSsoAporte->getContratoRel()->getCentroCostoRel()->getNombre())
                        ->setCellValue('E' . $i, $arSsoAporte->getCodigoContratoFk())
                        ->setCellValue('F' . $i, $arSsoAporte->getEmpleadoRel()->getCodigoCentroCostoContabilidadFk())
                        ->setCellValue('G' . $i, $arSsoAporte->getIngreso())
                        ->setCellValue('H' . $i, $arSsoAporte->getRetiro())
                        ->setCellValue('I' . $i, $arSsoAporte->getVariacionTransitoriaSalario())
                        ->setCellValue('J' . $i, $suspencionTemporalContratoLicenciaServicios)
                        ->setCellValue('K' . $i, $incapacidadGeneral)
                        ->setCellValue('L' . $i, $licenciaMaternidad)
                        ->setCellValue('M' . $i, $vacaciones)
                        ->setCellValue('N' . $i, $riesgosProfesionales)
                        ->setCellValue('O' . $i, $arSsoAporte->getSalarioBasico())
                        ->setCellValue('P' . $i, $arSsoAporte->getVrVacaciones())
                        ->setCellValue('Q' . $i, $salarioIntegral)
                        ->setCellValue('R' . $i, $arSsoAporte->getDiasCotizadosPension())
                        ->setCellValue('S' . $i, $arSsoAporte->getDiasCotizadosSalud())
                        ->setCellValue('T' . $i, $arSsoAporte->getDiasCotizadosRiesgosProfesionales())
                        ->setCellValue('U' . $i, $arSsoAporte->getDiasCotizadosCajaCompensacion())
                        ->setCellValue('V' . $i, $arSsoAporte->getIbcPension())
                        ->setCellValue('W' . $i, $arSsoAporte->getIbcSalud())
                        ->setCellValue('X' . $i, $arSsoAporte->getIbcRiesgosProfesionales())
                        ->setCellValue('Y' . $i, $arSsoAporte->getIbcCaja())
                        ->setCellValue('Z' . $i, $arSsoAporte->getTarifaPension())
                        ->setCellValue('AA' . $i, $arSsoAporte->getTarifaSalud())
                        ->setCellValue('AB' . $i, $arSsoAporte->getTarifaRiesgos())
                        ->setCellValue('AC' . $i, $arSsoAporte->getTarifaCaja())
                        ->setCellValue('AD' . $i, $arSsoAporte->getTarifaSena())
                        ->setCellValue('AE' . $i, $arSsoAporte->getTarifaIcbf())
                        ->setCellValue('AF' . $i, $arSsoAporte->getCotizacionPension())
                        ->setCellValue('AG' . $i, $arSsoAporte->getAportesFondoSolidaridadPensionalSolidaridad())
                        ->setCellValue('AH' . $i, $arSsoAporte->getAportesFondoSolidaridadPensionalSubsistencia())
                        ->setCellValue('AI' . $i, $arSsoAporte->getCotizacionSalud())
                        ->setCellValue('AJ' . $i, $arSsoAporte->getCotizacionRiesgos())
                        ->setCellValue('AK' . $i, $arSsoAporte->getCotizacionCaja())
                        ->setCellValue('AL' . $i, $arSsoAporte->getCotizacionSena())
                        ->setCellValue('AM' . $i, $arSsoAporte->getCotizacionIcbf())
                        ->setCellValue('AN' . $i, $arSsoAporte->getTotalCotizacion());
                    if ($arSsoAporte->getCodigoEntidadPensionFk()) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO' . $i, $arSsoAporte->getEntidadPensionRel()->getNombre());
                    }
                    if ($arSsoAporte->getCodigoEntidadSaludFk()) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP' . $i, $arSsoAporte->getEntidadSaludRel()->getNombre());
                    }
                    if ($arSsoAporte->getCodigoEntidadRiesgoFk()) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ' . $i, $arSsoAporte->getEntidadRiesgoProfesionalRel()->getNombre());
                    }
                    if ($arSsoAporte->getCodigoEntidadCajaFk()) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR' . $i, $arSsoAporte->getEntidadCajaRel()->getNombre());
                    }
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('SsoAportes');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="SsoAportes.xlsx"');
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

            if ($request->request->get('OpGenerarPagosExcel')) {
                $this->generarPagosPeriodoExcel($codigoPeriodo);
            }
            if ($request->request->get('OpGenerarPagosDetalleExcel')) {
                $this->generarPagosDetallePeriodoExcel($codigoPeriodo);
            }
            if ($request->request->get('OpGenerarAportesExcel')) {
                $this->generarAportesPeriodoExcel($codigoPeriodo);
            }
        }
        $arSsoPeriodoDetalles = $paginator->paginate($em->createQuery($this->strDqlListaDetalle), $request->query->get('page', 1), 50);
        $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalle.html.twig', array(
            'arSsoPeriodoDetalles' => $arSsoPeriodoDetalles,
            'codigoPeriodo' => $codigoPeriodo,
            'arPeriodo' => $arPeriodo,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seguridadsocial/periodo/detalle/nuevo/{codigoPeriodo}/{codigoPeriodoDetallePk}", name="brs_rhu_ss_periodo_detalle_nuevo")
     */
    public function detalleNuevoAction($codigoPeriodo, $codigoPeriodoDetallePk)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
        $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        if ($codigoPeriodoDetallePk != 0) {
            $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetallePk);
        }
        $form = $this->createForm(new RhuSsoPeriodoDetalleType(), $arPeriodoDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPeriodoDetalle = $form->getData();
            $arPeriodoDetalle->setSsoPeriodoRel($arPeriodo);
            $em->persist($arPeriodoDetalle);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalleNuevo.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seguridadsocial/periodo/detalle/empleados/{codigoPeriodoDetalle}", name="brs_rhu_ss_periodo_detalle_empleados")
     */
    public function detalleEmpleadosAction($codigoPeriodoDetalle)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $form = $this->formularioDetalleEmpleado($arPeriodoDetalle);
        $form->handleRequest($request);
        $this->listarEmpleados($codigoPeriodoDetalle);
        if ($form->isValid()) {
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodoDetalleEmpleadoPk) {
                        $arPeriodoDetalleEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                        $arPeriodoDetalleEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->find($codigoPeriodoDetalleEmpleadoPk);
                        $em->remove($arPeriodoDetalleEmpleado);

                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle_empleados', array('codigoPeriodoDetalle' => $codigoPeriodoDetalle)));
                }
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarDetalleEmpleado($form);
                $this->listarEmpleados($codigoPeriodoDetalle);
            }
            if ($form->get('BtnActualizarEmpleadoAporte')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->actualizar($codigoPeriodoDetalle);
            }
            if ($form->get('BtnActualizarEmpleados')->isClicked()) {
                $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->contratosPeriodo($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()->format('Y-m-d'), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('Y-m-d'));
                foreach ($arContratos as $arContrato) {
                    if ($arContrato->getCentroCostoRel()->getCodigoSucursalFk() == $arPeriodoDetalle->getCodigoSucursalFk()) {
                        $arPeriodoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                        $arPeriodoEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->findOneBy(array('codigoEmpleadoFk' => $arContrato->getCodigoEmpleadoFk(), 'codigoPeriodoFk' => $arPeriodoDetalle->getCodigoPeriodoFk()));
                        if (!$arPeriodoEmpleado) {
                            $arPeriodoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                            $arPeriodoEmpleado->setEmpleadoRel($arContrato->getEmpleadoRel());
                            $arPeriodoEmpleado->setSsoPeriodoRel($arPeriodoDetalle->getSsoPeriodoRel());
                            $arPeriodoEmpleado->setSsoSucursalRel($arPeriodoDetalle->getSsoSucursalRel());
                            $arPeriodoEmpleado->setContratoRel($arContrato);
                            $arPeriodoEmpleado->setSsoPeriodoDetalleRel($arPeriodoDetalle);
                            $em->persist($arPeriodoEmpleado);
                        }
                    }
                }
                $em->flush();
                //$em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->actualizar($codigoPeriodoDetalle);
            }
            if ($form->get('BtnActualizarSalarioMinimo')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigo'] as $intCodigo) {
                    if ($arrControles['TxtSalario' . $intCodigo] != "" && $arrControles['TxtSalario' . $intCodigo] != 0) {
                        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                        $arPeriodoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                        $arPeriodoEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->find($intCodigo);
                        $floSalario = $arConfiguracion->getVrSalario();
                        $arPeriodoEmpleado->setVrSalario($floSalario);
                        $em->persist($arPeriodoEmpleado);
                    }
                }
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle_empleados', array('codigoPeriodoDetalle' => $codigoPeriodoDetalle)));
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->generarExcelEmpleados($codigoPeriodoDetalle);
            }
        }
        $arSsoPeriodoEmpleados = $paginator->paginate($em->createQuery($this->strDqlListaEmpleados), $request->query->get('page', 1), 500);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:empleados.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arPeriodoEmpleados' => $arSsoPeriodoEmpleados,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seguridadsocial/periodo/detalle/aportes/{codigoPeriodoDetalle}", name="brs_rhu_ss_periodo_detalle_aportes")
     */
    public function detalleAportesAction($codigoPeriodoDetalle)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioDetalleAporte();
        $form->handleRequest($request);
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $this->listarDetalleAportes($codigoPeriodoDetalle);
        if ($form->isValid()) {
            /*if ($request->request->get('OpGenerar')) {
                $codigoPeriodoDetalle = $request->request->get('OpGenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->generar($codigoPeriodoDetalle);
            }
            if ($request->request->get('OpDesgenerar')) {
                $codigoPeriodoDetalle = $request->request->get('OpDesgenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->desgenerar($codigoPeriodoDetalle);
            }*/
            if ($form->get('BtnLiquidar')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->liquidar($codigoPeriodoDetalle);
                return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle_aportes', array('codigoPeriodoDetalle' => $codigoPeriodoDetalle)));
            }
            /*if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodoDetalleAportePk) {
                        $arPeriodoDetalleAporte = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
                        $arPeriodoDetalleAporte =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->find($codigoPeriodoDetalleAportePk);
                        $em->remove($arPeriodoDetalleAporte);
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle_aportes', array('codigoPeriodoDetalle' => $codigoPeriodoDetalle)));
                }
            }*/
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarDetalleAporte($form);
                $this->listarDetalleAportes($codigoPeriodoDetalle);
            }
        }
        $arSsoAportes = $paginator->paginate($em->createQuery($this->strDqlListaDetalleAportes), $request->query->get('page', 1), 2000);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalleAportes.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arSsoAportes' => $arSsoAportes,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seguridadsocial/periodo/detalle/aporte/editar/{codigoAporte}", name="brs_rhu_seguridadsocial_periodo_detalle_aporte_editar")
     */
    public function detalleAportesEditarAction($codigoAporte)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator = $this->get('knp_paginator');
        $arAporte = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
        $arAporte = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->find($codigoAporte);
        $form = $this->createForm(new RhuSsoAporteType, $arAporte);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arAporte = $form->getData();
            if (!$arAporte->getIngreso()) {
                $arAporte->setIngreso(' ');
            }
            if (!$arAporte->getRetiro()) {
                $arAporte->setRetiro(' ');
            }
            $totalCotizacion = $arAporte->getAportesFondoSolidaridadPensionalSolidaridad() + $arAporte->getAportesFondoSolidaridadPensionalSubsistencia() + $arAporte->getCotizacionPension() + $arAporte->getCotizacionSalud() + $arAporte->getCotizacionRiesgos() + $arAporte->getCotizacionCaja() + $arAporte->getCotizacionIcbf() + $arAporte->getCotizacionSena();
            $arAporte->setTotalCotizacion($totalCotizacion);
            $em->persist($arAporte);
            $em->flush();
            $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->liquidar($arAporte->getCodigoPeriodoDetalleFk());
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalleAportesEditar.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seguridadsocial/periodo/detalle/empleados/trasladar/{codigoPeriodoDetalle}", name="brs_rhu_ss_periodo_detalle_empleados_trasladar")
     */
    public function trasladarEmpleadosAction($codigoPeriodoDetalle)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $form = $this->formularioTrasladarEmpleado($arPeriodoDetalle);
        $form->handleRequest($request);
        $this->listarTrasladoEmpleados($codigoPeriodoDetalle);
        if ($form->isValid()) {
            if ($form->get('BtnTrasladar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodoDetalleEmpleadoPk) {
                        $arPeriodoDetalleEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                        $arPeriodoDetalleEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->find($codigoPeriodoDetalleEmpleadoPk);
                        $arPeriodoDetalleEmpleado->setSsoPeriodoRel($arPeriodoDetalle->getSsoPeriodoRel());
                        $arPeriodoDetalleEmpleado->setSsoPeriodoDetalleRel($arPeriodoDetalle);
                        $arPeriodoDetalleEmpleado->setSsoSucursalRel($arPeriodoDetalle->getSsoSucursalRel());
                        $em->persist($arPeriodoDetalleEmpleado);
                    }
                }
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarTrasladoEmpleado($form, $codigoPeriodoDetalle);
                $this->listarTrasladoEmpleados($codigoPeriodoDetalle);
            }
        }
        $arSsoTrasladoEmpleados = $paginator->paginate($em->createQuery($this->strDqlListaEmpleados), $request->query->get('page', 1), 200);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:trasladar.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arTrasladoEmpleados' => $arSsoTrasladoEmpleados,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seguridadsocial/periodo/detalle/empleados/copiar/{codigoPeriodoDetalle}", name="brs_rhu_ss_periodo_detalle_empleados_copiar")
     */
    public function copiarEmpleadosAction($codigoPeriodoDetalle)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $form = $this->formularioCopiarEmpleado($arPeriodoDetalle);
        $form->handleRequest($request);
        $this->listarCopiarEmpleados($codigoPeriodoDetalle);
        if ($form->isValid()) {
            if ($form->get('BtnCopiar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodoDetalleEmpleadoPk) {
                        $arPeriodoDetalleEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                        $arPeriodoDetalleEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->find($codigoPeriodoDetalleEmpleadoPk);
                        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
                        $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($arPeriodoDetalleEmpleado->getCodigoPeriodoFk());
                        $arSucursal = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal();
                        $arSucursal = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoSucursal')->find($arPeriodoDetalleEmpleado->getCodigoSucursalFk());
                        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arPeriodoDetalleEmpleado->getCodigoEmpleadoFk());
                        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arPeriodoDetalleEmpleado->getCodigoContratoFk());
                        $arCopiarEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                        $arCopiarEmpleado->setSsoPeriodoRel($arPeriodo);
                        $arCopiarEmpleado->setSsoPeriodoDetalleRel($arPeriodoDetalle);
                        //$arCopiarEmpleado->setSsoSucursalRel($arSucursal);
                        $arCopiarEmpleado->setSsoSucursalRel($arPeriodoDetalle->getSsoSucursalRel());
                        $arCopiarEmpleado->setEmpleadoRel($arEmpleado);
                        $arCopiarEmpleado->setContratoRel($arContrato);
                        $arCopiarEmpleado->setDias($arPeriodoDetalleEmpleado->getDias());
                        $arCopiarEmpleado->setVrSalario($arPeriodoDetalleEmpleado->getVrSalario());
                        $arCopiarEmpleado->setVrSuplementario($arPeriodoDetalleEmpleado->getVrSuplementario());
                        $arCopiarEmpleado->setIngreso($arPeriodoDetalleEmpleado->getIngreso());
                        $arCopiarEmpleado->setRetiro($arPeriodoDetalleEmpleado->getRetiro());
                        $arCopiarEmpleado->setSalarioIntegral($arPeriodoDetalleEmpleado->getSalarioIntegral());
                        $arCopiarEmpleado->setDiasLicencia($arPeriodoDetalleEmpleado->getDiasLicencia());
                        $arCopiarEmpleado->setDiasIncapacidadGeneral($arPeriodoDetalleEmpleado->getDiasIncapacidadGeneral());
                        $arCopiarEmpleado->setDiasLicenciaMaternidad($arPeriodoDetalleEmpleado->getDiasLicenciaMaternidad());
                        $arCopiarEmpleado->setDiasIncapacidadLaboral($arPeriodoDetalleEmpleado->getDiasIncapacidadLaboral());
                        $arCopiarEmpleado->setDiasVacaciones($arPeriodoDetalleEmpleado->getDiasVacaciones());
                        $arCopiarEmpleado->setTarifaPension($arPeriodoDetalleEmpleado->getTarifaPension());
                        $arCopiarEmpleado->setTarifaRiesgos($arPeriodoDetalleEmpleado->getTarifaRiesgos());
                        $arCopiarEmpleado->setCodigoEntidadPensionPertenece($arPeriodoDetalleEmpleado->getCodigoEntidadPensionPertenece());
                        $arCopiarEmpleado->setCodigoEntidadSaludPertenece($arPeriodoDetalleEmpleado->getCodigoEntidadSaludPertenece());
                        $arCopiarEmpleado->setCodigoEntidadCajaPertenece($arPeriodoDetalleEmpleado->getCodigoEntidadCajaPertenece());

                        $em->persist($arCopiarEmpleado);
                    }
                }
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarCopiarEmpleado($form, $codigoPeriodoDetalle);
                $this->listarCopiarEmpleados($codigoPeriodoDetalle);
            }
        }
        $arSsoCopiarEmpleados = $paginator->paginate($em->createQuery($this->strDqlListaEmpleados), $request->query->get('page', 1), 200);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:copiar.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arCopiarEmpleados' => $arSsoCopiarEmpleados,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seguridadsocial/resumen/pago/{codigoPeriodoDetalle}/{codigoEmpleado}", name="brs_rhu_ss_resumen_pago")
     */
    public function resumenPagosAction($codigoPeriodoDetalle, $codigoEmpleado)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);

        $arPagos = $paginator->paginate($em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaConsultaPagosDQL("", $arEmpleado->getNumeroIdentificacion(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()->format('Y/m/d'), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('Y/m/d'), "", "")), $request->query->get('page', 1), 50);
        $arPagosDetalles = $paginator->paginate($em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaConsultaPagosDetallesDQL($arEmpleado->getNumeroIdentificacion(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta())), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:resumenPagos.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arPagos' => $arPagos,
            'arPagosDetalles' => $arPagosDetalles,
        ));
    }

    private function formularioDetalleEmpleado($ar)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrBotonEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonActualizarEmpleados = array('label' => 'Actualizar empleados', 'disabled' => false);
        $arrBotonActualizarDatos = array('label' => 'Actualizar datos', 'disabled' => false);
        $arrBotonActualizarSalarioMinimo = array('label' => 'Actualizar salario minimo', 'disabled' => false);

        $arrBotonExcel = array('label' => 'Excel', 'disabled' => false);
        if ($ar->getEstadoGenerado() == 1) {
            $arrBotonEliminar['disabled'] = true;
            $arrBotonActualizarDatos['disabled'] = true;
            $arrBotonActualizarSalarioMinimo['disabled'] = true;
            $arrBotonActualizarEmpleados['disabled'] = true;
        }
        $arrayPropiedades = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');
            },
            'property' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'empty_value' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('BtnFiltrar', 'submit', array('label' => 'Filtrar'))
            ->add('BtnActualizarEmpleadoAporte', 'submit', $arrBotonActualizarDatos)
            ->add('BtnActualizarEmpleados', 'submit', $arrBotonActualizarEmpleados)
            ->add('BtnActualizarSalarioMinimo', 'submit', $arrBotonActualizarSalarioMinimo)
            ->add('BtnExcel', 'submit', $arrBotonExcel)
            ->add('BtnEliminar', 'submit', $arrBotonEliminar)
            ->getForm();
        return $form;
    }

    private function formularioTrasladarEmpleado($ar)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $codigoPeriodo = $ar->getCodigoPeriodoFk();
        $arrayPropiedades = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');
            },
            'property' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'empty_value' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }

        $arrayPropiedadesSucursal = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle',
            'query_builder' => function (EntityRepository $er) use ($codigoPeriodo) {
                return $er->createQueryBuilder('ssopd')
                    ->where('ssopd.codigoPeriodoFk = :codigoPeriodo')
                    ->setParameter('codigoPeriodo', $codigoPeriodo)
                    ->orderBy('ssopd.codigoPeriodoDetallePk', 'ASC');
            },
            'property' => 'detalle',
            'required' => false,
            'empty_data' => "",
            'empty_value' => "TODOS",
        );
        $form = $this->createFormBuilder()
            ->add('numeroIdentificacion', 'text', array('label' => 'Identificacion', 'data' => $session->get('filtroNumeroIdentificacion')))
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('sucursalRel', 'entity', $arrayPropiedadesSucursal)
            ->add('BtnFiltrar', 'submit', array('label' => 'Filtrar'))
            ->add('BtnTrasladar', 'submit', array('label' => 'Trasladar',))
            ->getForm();
        return $form;
    }

    private function formularioCopiarEmpleado($ar)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $codigoPeriodo = $ar->getCodigoPeriodoFk();
        $arrayPropiedades = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');
            },
            'property' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'empty_value' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $arrayPropiedadesSucursal = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle',
            'query_builder' => function (EntityRepository $er) use ($codigoPeriodo) {
                return $er->createQueryBuilder('ssopd')
                    ->where('ssopd.codigoPeriodoFk = :codigoPeriodo')
                    ->setParameter('codigoPeriodo', $codigoPeriodo)
                    ->orderBy('ssopd.codigoPeriodoDetallePk', 'ASC');
            },
            'property' => 'detalle',
            'required' => false,
            'empty_data' => "",
            'empty_value' => "TODOS",
        );
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('numeroIdentificacion', 'text', array('label' => 'Identificacion', 'data' => $session->get('filtroNumeroIdentificacion')))
            ->add('sucursalRel', 'entity', $arrayPropiedadesSucursal)
            ->add('BtnFiltrar', 'submit', array('label' => 'Filtrar'))
            ->add('BtnCopiar', 'submit', array('label' => 'Copiar',))
            ->getForm();
        return $form;
    }

    private function formularioDetalleAporte()
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');
            },
            'property' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'empty_value' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('BtnFiltrar', 'submit', array('label' => 'Filtrar'))
            ->add('BtnLiquidar', 'submit', array('label' => 'Liquidar'))
            ->getForm();
        return $form;
    }

    private function filtrarDetalleEmpleado($form)
    {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
    }

    private function filtrarTrasladoEmpleado($form, $codigoPeriodoDetalle)
    {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $numeroIdentificacion = $form->get('numeroIdentificacion')->getData();
        $this->strCodigoPeriodoDetalleTraslados = $codigoPeriodoDetalle;
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroNumeroIdentificacion', $numeroIdentificacion);

    }

    private function filtrarCopiarEmpleado($form, $codigoPeriodoDetalle)
    {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $numeroIdentificacion = $form->get('numeroIdentificacion')->getData();
        $this->strCodigoPeriodoDetalleCopias = $codigoPeriodoDetalle;
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroNumeroIdentificacion', $numeroIdentificacion);
    }

    private function filtrarDetalleAporte($form)
    {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
    }

    private function listar()
    {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->listaDQL();
    }

    private function listarDetalle($codigoPeriodo)
    {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlListaDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->listaDQL($codigoPeriodo);
    }

    private function listarEmpleados($codigoPeriodoDetalle)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strDqlListaEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->listaDql($codigoPeriodoDetalle, $session->get('filtroCodigoCentroCosto'));
    }

    private function listarTrasladoEmpleados($codigoPeriodoDetalle)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $this->strDqlListaEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->listaTrasladoDql($codigoPeriodoDetalle, $session->get('filtroCodigoCentroCosto'), $this->strCodigoPeriodoDetalleCopias, $session->get('filtroNumeroIdentificacion'), $arPeriodoDetalle->getCodigoPeriodoFk());
    }

    private function listarCopiarEmpleados($codigoPeriodoDetalle)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $this->strDqlListaEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->listaCopiarDql($codigoPeriodoDetalle, $session->get('filtroCodigoCentroCosto'), $this->strCodigoPeriodoDetalleCopias, $session->get('filtroNumeroIdentificacion'), $arPeriodoDetalle->getCodigoPeriodoFk());
    }

    private function listarDetalleAportes($codigoPeriodoDetalle)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strDqlListaDetalleAportes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->listaDQL($codigoPeriodoDetalle, $session->get('filtroCodigoCentroCosto'));
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

    private function generarPagosPeriodoExcel($codigoPeriodo)
    {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CÓDIGO')
            ->setCellValue('B1', 'NÚMERO')
            ->setCellValue('C1', 'TIPO')
            ->setCellValue('D1', 'IDENTIFICACIÓN')
            ->setCellValue('E1', 'EMPLEADO')
            ->setCellValue('F1', 'CENTRO COSTO')
            ->setCellValue('G1', 'PERIODO PAGO')
            ->setCellValue('H1', 'FECHA PAGO DESDE')
            ->setCellValue('I1', 'FECHA PAGO HASTA')
            ->setCellValue('J1', 'DÍAS PERIODO')
            ->setCellValue('K1', 'VR SALARIO EMPLEADO')
            ->setCellValue('L1', 'VR SALARIO PERIODO')
            ->setCellValue('M1', 'VR AUX TRANSPORTE')
            ->setCellValue('N1', 'VR EPS')
            ->setCellValue('O1', 'VR PENSIÓN')
            ->setCellValue('P1', 'VR DEDUCCIONES')
            ->setCellValue('Q1', 'VR DEVENGADO')
            ->setCellValue('R1', 'VR INGRESO BASE COTIZACIÓN')
            ->setCellValue('S1', 'VR INGRESO BASE PRESTACIONAL')
            ->setCellValue('T1', 'VE NETO PAGAR');

        $i = 2;
        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
        $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
        $dateFechaPeriodo = $arPeriodo->getFechaDesde()->format('Y-m-d') . ' - ' . $arPeriodo->getFechaHasta()->format('Y-m-d');
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaDqlPagosPeriodoAportes($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta());
        $query = $em->createQuery($arPagos);
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                ->setCellValue('B' . $i, $arPago->getNumero())
                ->setCellValue('C' . $i, $arPago->getPagoTipoRel()->getNombre())
                ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                ->setCellValue('G' . $i, $arPago->getFechaDesde()->format('Y-m-d') . " - " . $arPago->getFechaHasta()->format('Y-m-d'))
                ->setCellValue('H' . $i, $arPago->getFechaDesdePago()->format('Y-m-d'))
                ->setCellValue('I' . $i, $arPago->getFechaHastaPago()->format('Y-m-d'))
                ->setCellValue('J' . $i, $arPago->getDiasPeriodo())
                ->setCellValue('K' . $i, $arPago->getVrSalarioEmpleado())
                ->setCellValue('L' . $i, $arPago->getVrSalarioPeriodo())
                ->setCellValue('M' . $i, $arPago->getVrAuxilioTransporte())
                ->setCellValue('N' . $i, 0)
                ->setCellValue('O' . $i, 0)
                ->setCellValue('P' . $i, $arPago->getVrDeducciones())
                ->setCellValue('Q' . $i, $arPago->getVrDevengado())
                ->setCellValue('R' . $i, $arPago->getVrIngresoBaseCotizacion())
                ->setCellValue('S' . $i, $arPago->getVrIngresoBasePrestacion())
                ->setCellValue('T' . $i, $arPago->getVrNeto());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Pagos ' . $dateFechaPeriodo);
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->createSheet(2)->setTitle('detalles' . $dateFechaPeriodo)
            ->setCellValue('A1', 'CODIGO')
            ->setCellValue('B1', 'PAGO')
            ->setCellValue('C1', 'CONCEPTO PAGO')
            ->setCellValue('D1', 'IDENTIFICACIÓN')
            ->setCellValue('E1', 'EMPLEADO')
            ->setCellValue('F1', 'CENTRO COSTO')
            ->setCellValue('G1', 'DESDE')
            ->setCellValue('H1', 'HASTA')
            ->setCellValue('I1', 'VR.PAGO')
            ->setCellValue('J1', 'VR.HORA')
            ->setCellValue('K1', 'VR.DÍA')
            ->setCellValue('L1', 'HORAS')
            ->setCellValue('M1', 'DIAS')
            ->setCellValue('N1', 'POR')
            ->setCellValue('O1', 'IBC')
            ->setCellValue('P1', 'IBP')
            ->setCellValue('Q1', 'ADI')
            ->setCellValue('R1', 'PRE')
            ->setCellValue('S1', 'COT');

        $i = 2;
        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
        $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
        $dateFechaPeriodo = $arPeriodo->getFechaDesde()->format('Y-m-d') . ' - ' . $arPeriodo->getFechaHasta()->format('Y-m-d');
        $arPagosDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagosDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->listaDqlPagosDetallePeriodoAportes($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta());

        foreach ($arPagosDetalle as $arPagoDetalle) {
            $objPHPExcel->setActiveSheetIndex(1)
                ->setCellValue('A' . $i, $arPagoDetalle->getCodigoPagoDetallePk())
                ->setCellValue('B' . $i, $arPagoDetalle->getCodigoPagoFk())
                ->setCellValue('C' . $i, $arPagoDetalle->getPagoConceptoRel()->getNombre())
                ->setCellValue('D' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNumeroIdentificacion())
                ->setCellValue('E' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNombreCorto())
                ->setCellValue('F' . $i, $arPagoDetalle->getPagoRel()->getCentroCostoRel()->getNombre())
                ->setCellValue('G' . $i, $arPagoDetalle->getPagoRel()->getFechaDesdePago())
                ->setCellValue('H' . $i, $arPagoDetalle->getPagoRel()->getFechaHastaPago())
                ->setCellValue('I' . $i, $arPagoDetalle->getVrPago())
                ->setCellValue('J' . $i, $arPagoDetalle->getVrHora())
                ->setCellValue('K' . $i, $arPagoDetalle->getVrDia())
                ->setCellValue('L' . $i, $arPagoDetalle->getNumeroHoras())
                ->setCellValue('M' . $i, $arPagoDetalle->getNumeroDias())
                ->setCellValue('N' . $i, $arPagoDetalle->getPorcentajeAplicado())
                ->setCellValue('O' . $i, $arPagoDetalle->getVrIngresoBaseCotizacion())
                ->setCellValue('P' . $i, $arPagoDetalle->getVrIngresoBasePrestacion())
                ->setCellValue('Q' . $i, $objFunciones->devuelveBoolean($arPagoDetalle->getAdicional()))
                ->setCellValue('R' . $i, $objFunciones->devuelveBoolean($arPagoDetalle->getPrestacional()))
                ->setCellValue('S' . $i, $objFunciones->devuelveBoolean($arPagoDetalle->getCotizacion()));
            $i++;
        }

        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'T'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for ($col = 'I'; $col !== 'P'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pagos ' . $dateFechaPeriodo . '.xlsx"');
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

    private function generarPagosDetallePeriodoExcel($codigoPeriodo)
    {
        /*    ob_clean();
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

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'CÓDIGO DETALLE')
                        ->setCellValue('B1', 'CÓDIGO PAGO')
                        ->setCellValue('C1', 'CONCEPTO PAGO')
                        ->setCellValue('D1', 'IDENTIFICACIÓN')
                        ->setCellValue('E1', 'EMPLEADO')
                        ->setCellValue('F1', 'CENTRO COSTO')
                        ->setCellValue('G1', 'FECHA PAGO DESDE')
                        ->setCellValue('H1', 'FECHA PAGO HASTA')
                        ->setCellValue('I1', 'VR PAGO')
                        ->setCellValue('J1', 'VR HORA')
                        ->setCellValue('K1', 'VR DÍA')
                        ->setCellValue('L1', 'NÚMERO HORAS')
                        ->setCellValue('M1', 'NÚMERO DÍAS')
                        ->setCellValue('N1', 'PORCENTAJE APLICADO')
                        ->setCellValue('O1', 'VR INGRESO BASE COTIZACIÓN')
                        ->setCellValue('P1', 'CÓDIGO PROGRAMACION PAGO DETALLE')
                        ->setCellValue('Q1', 'CÓDIGO CRÉDITO')
                        ->setCellValue('R1', 'VR INGRESO BASE PRESTACIONAL')
                        ->setCellValue('S1', 'DÍAS AUSENTIMO');

            $i = 2;
            $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
            $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
            $dateFechaPeriodo = $arPeriodo->getFechaDesde()->format('Y-m-d'). ' - ' . $arPeriodo->getFechaHasta()->format('Y-m-d');
            $arPagosDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
            $arPagosDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->listaDqlPagosDetallePeriodoAportes($arPeriodo->getFechaDesde(),$arPeriodo->getFechaHasta());

            foreach ($arPagosDetalle as $arPagoDetalle) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $arPagoDetalle->getCodigoPagoDetallePk())
                        ->setCellValue('B' . $i, $arPagoDetalle->getCodigoPagoFk())
                        ->setCellValue('C' . $i, $arPagoDetalle->getPagoConceptoRel()->getNombre())
                        ->setCellValue('D' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNumeroIdentificacion())
                        ->setCellValue('E' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNombreCorto())
                        ->setCellValue('F' . $i, $arPagoDetalle->getPagoRel()->getCentroCostoRel()->getNombre())
                        ->setCellValue('G' . $i, $arPagoDetalle->getPagoRel()->getFechaDesdePago())
                        ->setCellValue('H' . $i, $arPagoDetalle->getPagoRel()->getFechaHastaPago())
                        ->setCellValue('I' . $i, $arPagoDetalle->getVrPago())
                        ->setCellValue('J' . $i, $arPagoDetalle->getVrHora())
                        ->setCellValue('K' . $i, $arPagoDetalle->getVrDia())
                        ->setCellValue('L' . $i, $arPagoDetalle->getNumeroHoras())
                        ->setCellValue('M' . $i, $arPagoDetalle->getNumeroDias())
                        ->setCellValue('N' . $i, $arPagoDetalle->getPorcentajeAplicado())
                        ->setCellValue('O' . $i, $arPagoDetalle->getVrIngresoBaseCotizacion())
                        ->setCellValue('P' . $i, $arPagoDetalle->getCodigoProgramacionPagoDetalleFk())
                        ->setCellValue('Q' . $i, $arPagoDetalle->getCodigoCreditoFk())
                        ->setCellValue('R' . $i, $arPagoDetalle->getVrIngresoBasePrestacion())
                        ->setCellValue('S' . $i, $arPagoDetalle->getDiasAusentismo());
                $i++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('detalles'.$dateFechaPeriodo);
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="detalle '.$dateFechaPeriodo.'.xlsx"');
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
            exit;*/
    }

    private function generarAportesPeriodoExcel($codigoPeriodo)
    {
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
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AT')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AU')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AV')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AW')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AX')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AY')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AZ')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('BA')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('BB')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('BC')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('BD')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('BE')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('BF')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CODIGO')
            ->setCellValue('B1', 'SUCURSAL')
            ->setCellValue('C1', 'IDENTIFICACIÓN')
            ->setCellValue('D1', 'EMPLEADO')
            ->setCellValue('E1', 'SECUENCIA')
            ->setCellValue('F1', 'TIPO DOCUMENTO')
            ->setCellValue('G1', 'TIPO COTIZANTE')
            ->setCellValue('H1', 'SUBTIPO COTIZANTE')
            ->setCellValue('I1', 'DEPARTAMENTO')
            ->setCellValue('J1', 'MUNICIPIO')
            ->setCellValue('K1', 'INGRESO')
            ->setCellValue('L1', 'RETIRO')
            ->setCellValue('M1', 'TRANSLADO DESDE OTRA EPS')
            ->setCellValue('N1', 'TRANSLADO A OTRA EPS')
            ->setCellValue('O1', 'TRANSLADO DESDE OTRA PENSIÓN')
            ->setCellValue('P1', 'TRANSLADO A OTRA PENSIÓN')
            ->setCellValue('Q1', 'VARIACIÓN PERMANENTE SALARIO')
            ->setCellValue('R1', 'CORRECCIONES')
            ->setCellValue('S1', 'VARIACIÓN TRANSITORIA SALARIO')
            ->setCellValue('T1', 'SUSPENCIÓN TEMPORAL CONTRATO LICENCIA SERVICIOS')
            ->setCellValue('U1', 'DÍAS LICENCIAS')
            ->setCellValue('V1', 'SALARIO BÁSICO')
            ->setCellValue('W1', 'SALARIO MES ANTERIOR')
            ->setCellValue('X1', 'SALARIO INTEGRAL')
            ->setCellValue('Y1', 'SUPLEMENTARIO')
            ->setCellValue('Z1', 'INCAPACIDAD GENERAL')
            ->setCellValue('AA1', 'DÍAS INCAPACIDAD GENERAL')
            ->setCellValue('AB1', 'LICENCIA MATERNIDAD')
            ->setCellValue('AC1', 'DÍAS LICENCIAS MATERNIDAD')
            ->setCellValue('AD1', 'VACACIONES')
            ->setCellValue('AE1', 'APORTE VOLUNTARIO')
            ->setCellValue('AF1', 'VARIACIÓN CENTRO TRABAJO')
            ->setCellValue('AG1', 'INCAPACIDAD ACCIDENTE TRABAJO ENFERMEDAD PROFESIONAL')
            ->setCellValue('AH1', 'ENTIDAD PENSIÓN')
            ->setCellValue('AI1', 'ENTIDAD PENSIÓN TRASLADA')
            ->setCellValue('AJ1', 'ENTIDAD SALUD')
            ->setCellValue('AK1', 'ENTIDAD SALUD TRASLADA')
            ->setCellValue('AL1', 'CAJA COMPENSACIÓN')
            ->setCellValue('AM1', 'DÍAS COTIZADOS PENSIÓN')
            ->setCellValue('AN1', 'DÍAS COTIZADOS SALUD')
            ->setCellValue('AO1', 'DIAS COTIZADOS RIESGOS PROFESIONALES')
            ->setCellValue('AP1', 'DIAS COTIZADOS CAJAS COMPENSACIÓN')
            ->setCellValue('AQ1', 'IBC PENSIÓN')
            ->setCellValue('AR1', 'IBC SALUD')
            ->setCellValue('AS1', 'IBC RIESGOS PROFESIONALES')
            ->setCellValue('AT1', 'IBC CAJA COMPENSACIÓN')
            ->setCellValue('AU1', 'TARIFA PENSIÓN')
            ->setCellValue('AV1', 'TARIFA SALUD')
            ->setCellValue('AW1', 'TARIFA RIESGOS PROFESIONALES')
            ->setCellValue('AX1', 'TARIFA CAJA COMPENSACIÓN')
            ->setCellValue('AY1', 'COTIZACIÓN PENSIÓN')
            ->setCellValue('AZ1', 'COTIZACIÓN SALUD')
            ->setCellValue('BA1', 'COTIZACIÓN RIESGOS PROFESIONALES')
            ->setCellValue('BB1', 'COTIZACION CAJA COMPENSACIÓN')
            ->setCellValue('BC1', 'APORTE VOLUNTARIO FONDO PENSIONES OBLIGATORIAS')
            ->setCellValue('BD1', 'COTIZACIÓN VOLUNTARIO FONDO PENSIONES OBLIGATORIAS')
            ->setCellValue('BE1', 'TOTAL COTIZACIÓN')
            ->setCellValue('BF1', 'APORTES FONDO SOLIDARIDAD PENSIONAL SOLIDARIDAD');
        $i = 2;
        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
        $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
        $dateFechaPeriodo = $arPeriodo->getFechaDesde()->format('Y-m-d') . ' - ' . $arPeriodo->getFechaHasta()->format('Y-m-d');
        $arAportes = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
        $arAportes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->findBy(array('codigoPeriodoFk' => $codigoPeriodo));

        foreach ($arAportes as $arAporte) {
            $arEntidadPension = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
            $arEntidadPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->findBy(array('codigoInterface' => $arAporte->getCodigoEntidadPensionPertenece()));
            $arEntidadPensionPertenece = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
            $arEntidadPensionPertenece = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->find($arEntidadPension[0]);

            $arEntidadSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
            $arEntidadSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->findBy(array('codigoInterface' => $arAporte->getCodigoEntidadSaludPertenece()));
            $arEntidadSaludPertenece = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
            $arEntidadSaludPertenece = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->find($arEntidadSalud[0]);

            $arEntidadCaja = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja();
            $arEntidadCaja = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadCaja')->findBy(array('codigoInterface' => $arAporte->getCodigoEntidadCajaPertenece()));
            $arEntidadCajaPertenece = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja();
            $arEntidadCajaPertenece = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadCaja')->find($arEntidadCaja[0]);

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
                ->setCellValue('AH' . $i, $arEntidadPensionPertenece->getNombre())
                ->setCellValue('AI' . $i, $arAporte->getCodigoEntidadPensionTraslada())
                ->setCellValue('AJ' . $i, $arEntidadSaludPertenece->getNombre())
                ->setCellValue('AK' . $i, $arAporte->getCodigoEntidadSaludTraslada())
                ->setCellValue('AL' . $i, $arEntidadCajaPertenece->getNombre())
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
                ->setCellValue('BC' . $i, $arAporte->getAporteVoluntarioFondoPensionesObligatorias())
                ->setCellValue('BD' . $i, $arAporte->getCotizacionVoluntarioFondoPensionesObligatorias())
                ->setCellValue('BE' . $i, $arAporte->getTotalCotizacion())
                ->setCellValue('BF' . $i, $arAporte->getAportesFondoSolidaridadPensionalSolidaridad());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Aportes' . $dateFechaPeriodo);
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Aportes ' . $dateFechaPeriodo . '.xlsx"');
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

    private function generarExcelEmpleados($codigoPeriodoDetalle)
    {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        set_time_limit(0);
        ini_set("memory_limit", -1);
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'G'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for ($col = 'F'; $col !== 'G'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'DOCUMENTO')
            ->setCellValue('C1', 'EMPLEADO')
            ->setCellValue('D1', 'CENTRO COSTO')
            ->setCellValue('E1', 'CONTRATO')
            ->setCellValue('F1', 'DIAS');

        $i = 2;
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->listaDql($codigoPeriodoDetalle);
        $arPeriodoEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
        $query = $em->createQuery($dql);
        $arPeriodoEmpleados = $query->getResult();

        foreach ($arPeriodoEmpleados as $arPeriodoEmpleado) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $arPeriodoEmpleado->getCodigoPeriodoEmpleadoPk())
                ->setCellValue('B' . $i, $arPeriodoEmpleado->getEmpleadoRel()->getNumeroIdentificacion())
                ->setCellValue('C' . $i, $arPeriodoEmpleado->getEmpleadoRel()->getNombreCorto())
                ->setCellValue('E' . $i, $arPeriodoEmpleado->getCodigoContratoFk())
                ->setCellValue('F' . $i, $arPeriodoEmpleado->getDias());
            if ($arPeriodoEmpleado->getContratoRel()->getCodigoCentroCostoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arPeriodoEmpleado->getContratoRel()->getCentroCostoRel()->getNombre());
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PeriodoEmpleado');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PedidosDetalles.xlsx"');
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
    
    private function generarPlano($codigoPeriodoDetalle)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $arConfiguracionAporte = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionAporte();
        $arConfiguracionAporte = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionAporte')->find(1);

        $strRutaArchivo = $arConfiguracion->getRutaTemporal();
        $formaPresentacion = $arConfiguracionAporte->getFormaPresentacion();
        if ($formaPresentacion == null) {
            $formaPresentacion = 'S';
        }
        //Datos aportante
        $strNombreArchivo = "pila" . date('YmdHis') . ".txt";
        ob_clean();
        $ar = fopen($strRutaArchivo . $strNombreArchivo, "a") or
        die("Problemas en la creacion del archivo plano");
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $codigoSucursal = "";
        $sucursal = "";
        if ($formaPresentacion == "S") {
            $codigoSucursal = $arPeriodoDetalle->getSsoSucursalRel()->getCodigoInterface();
            $sucursal = $arPeriodoDetalle->getSsoSucursalRel()->getNombre();
        }
        $periodoPagoDiferenteSalud = $arPeriodoDetalle->getSsoPeriodoRel()->getAnio() . '-' . $this->RellenarNr($arPeriodoDetalle->getSsoPeriodoRel()->getMes(), "0", 2, "I");
        $periodoPagoSalud = $arPeriodoDetalle->getSsoPeriodoRel()->getAnioPago() . '-' . $this->RellenarNr($arPeriodoDetalle->getSsoPeriodoRel()->getMesPago(), "0", 2, "I");

        //1	2	1	2	N	Tipo de registro	Obligatorio. Debe ser 01
        fputs($ar, $this->RellenarNr("01", " ", 2, "D"));
        //2	1	3	3	N	Modalidad de la Planilla	Obligatorio. Lo genera autómaticamente el Operador de Información.
        fputs($ar, $this->RellenarNr("1", " ", 1, "D"));
        //3	4	4	7	N	Secuencia	Obligatorio. Verificación de la secuencia ascendente. Para cada aportante inicia en 0001. Lo genera el sistema en el caso en que se estén digitando los datos directamente en la web. El aportante debe reportarlo en el caso de que los datos se suban en archivos planos.
        fputs($ar, $this->RellenarNr("0001", " ", 4, "D"));
        //4	200	8	207	A	Nombre o razón social del aportante	El registrado en el campo 1 del archivo tipo 1
        fputs($ar, $this->RellenarNr($arConfiguracionAporte->getNombreEmpresa(), " ", 200, "D"));
        //5	2	208	209	A	Tipo documento del aportante	El registrado en el campo 2 del archivo tipo 1
        fputs($ar, $this->RellenarNr($arConfiguracionAporte->getTipoIdentificacionEmpresa(), " ", 2, "D"));
        //6	16	210	225	A	Número de identificación del aportante	El registrado en el campo 3 del archivo tipo 1
        fputs($ar, $this->RellenarNr($arConfiguracionAporte->getIdentificacionEmpresa(), " ", 16, "D"));
        //7	1	226	226	N	Dígito de verificación aportante	El registrado en el campo 4 del archivo tipo 1
        fputs($ar, $this->RellenarNr($arConfiguracionAporte->getDigitoVerificacionEmpresa(), " ", 1, "D"));
        //8	1	227	227	A	Tipo de Planilla	Obligatorio lo suministra el aportante
        fputs($ar, $this->RellenarNr("E", " ", 1, "D"));
        //9	10	228	237	N	Número de Planilla asociada a esta planilla.	Debe dejarse en blanco cuando el tipo de planilla sea E, A, I, M, S, Y, T o X. En este campo se incluirá el número de la planilla del periodo correspondiente cuando el tipo de planilla sea N ó F. Cuando se utilice la planilla U por parte de la UGPP, en este campo se diligenciará el número del título del depósito judicial.
        fputs($ar, $this->RellenarNr("", " ", 10, "D"));
        //10	10	238	247	A	Fecha de pago Planilla asociada a esta planilla. (AAAA-MM-DD)	Debe dejarse en blanco cuando el tipo de planilla sea E, A, I, M, S, Y, T, o X. En este campo se incluirá la fecha de pago de la planilla del período correspondiente cuando el tipo de planilla sea N ó F. Cuando se utilice la planilla U, la UGPP diligenciará la fecha en que se constituyó el depósito judicial.
        fputs($ar, $this->RellenarNr("", " ", 10, "D"));
        //11	1	248	248	A	Forma de presentación	El registrado en el campo 10 del archivo tipo 1.
        fputs($ar, $this->RellenarNr($formaPresentacion, " ", 1, "D"));
        //12	10	249	258	A	Código de la sucursal del Aportante	El registrado en el campo 5 del archivo tipo 1.
        fputs($ar, $this->RellenarNr($codigoSucursal, " ", 10, "D"));
        //13	40	259	298	A	Nombre de la sucursal	El registrado en el campo 6 del archivo tipo 1.
        fputs($ar, $this->RellenarNr($sucursal, " ", 40, "D"));
        //14	6	299	304	A	Código de la ARL a la cual el aportante se encuentra afiliado	Lo suministra el aportante
        fputs($ar, $this->RellenarNr($arConfiguracionAporte->getCodigoEntidadRiesgosProfesionales(), " ", 6, "D"));
        //15	7	305	311	A	Periodo de pago para los sistemas diferentes al de salud	Obligatorio. Formato año y mes (aaaa-mm). Lo calcula el Operador de Información.
        fputs($ar, $this->RellenarNr($periodoPagoDiferenteSalud, " ", 7, "D"));
        //16	7	312	318	A	Periodo de pago para el sistema de salud	Obligatorio. Formato año y mes (aaaa-mm). Lo suministra el aportante.
        fputs($ar, $this->RellenarNr($periodoPagoSalud, " ", 7, "D"));
        //17	10	319	328	N	Número de radicación o de la Planilla Integrada de Liquidación de aportes.	Asignado por el sistema . Debe ser único por operador de información.
        fputs($ar, $this->RellenarNr("", " ", 10, "D"));
        //18	10	329	338	A	Fecha de pago (aaaa-mm-dd)	Asignado por el sistema a partir de la fecha del día efectivo del pago.
        fputs($ar, $this->RellenarNr("", " ", 10, "D"));
        //19	5	339	343	N	Número total de empleados	Obligatorio. Se debe validar que sea igual al número de cotizantes únicos incluidos en el detalle del registro tipo 2, exceptuando los que tengan 40 en el campo 5 – Tipo de cotizante.
        fputs($ar, $this->RellenarNr($arPeriodoDetalle->getNumeroEmpleados(), "0", 5, "I"));
        //20	12	344	355	N	Valor total de la nómina	Obligatorio. Lo suministra el aportante, corresponde a la sumatoria de los IBC para el pago de los aportes de parafiscales de la totalidad de los empleados. Puede ser 0 para independientes
        fputs($ar, $this->RellenarNr($arPeriodoDetalle->getTotalIngresoBaseCotizacionCaja(), "0", 12, "I"));
        //21	2	356	357	N	Tipo de aportante	Obligatorio y debe ser igual al registrado en el campo 30 del archivo tipo 1
        fputs($ar, $this->RellenarNr("01", " ", 2, "D"));
        //22	2	358	359	N	Código del operador de información	Asignado por el sistema del operador de información.
        fputs($ar, $this->RellenarNr("88", " ", 2, "D"));
        fputs($ar, "\n");

        $arSsoAportes = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
        $arSsoAportes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->findBy(array('codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));
        foreach ($arSsoAportes as $arSsoAporte) {
            //1	2	1	2	N	Tipo de registro	Obligatorio. Debe ser 02.
            fputs($ar, $this->RellenarNr($arSsoAporte->getTipoRegistro(), "0", 2, "I"));
            //2	5	3	7	N	Secuencia	Debe iniciar en 00001 y ser secuencial para el resto de registros. Lo genera el sistema en el caso en que se estén digitando los datos directamente en la web. El aportante debe reportarlo en el caso de que los datos se suban en archivos planos.
            fputs($ar, $this->RellenarNr($arSsoAporte->getSecuencia(), "0", 5, "I"));
            //3	2	8	9	A	Tipo documento el cotizante	Obligatorio. Lo suministra el aportante. Los valores validos son:
            fputs($ar, $this->RellenarNr($arSsoAporte->getTipoDocumento(), " ", 2, "D"));
            //4	16	10	25	A	Número de identificación del cotizante	Obligatorio. Lo suministra el aportante. El operador de información validará que este campo este compuesto por letras de la A a la Z y los caracteres numéricos del Cero (0) al nueve (9). Sólo es permitido el número de identificación alfanumérico para los siguientes tipos de documentos de identidad: CE.  Cédula de Extranjería PA.  Pasaporte CD.  Carne Diplomático. Para los siguientes tipos de documento deben ser dígitos numéricos: TI.   Tarjeta de Identidad CC. Cédula de ciudadanía  SC.  Salvoconducto de permanencia RC.  Registro Civil
            fputs($ar, $this->RellenarNr($arSsoAporte->getEmpleadoRel()->getNumeroIdentificacion(), " ", 16, "D"));
            //5	2	26	27	N	Tipo de cotizante	Obligatorio. Lo suministra el aportante. Los valores validos son:
            fputs($ar, $this->RellenarNr($arSsoAporte->getTipoCotizante(), "0", 2, "I"));
            //6	2	28	29	N	Subtipo de cotizante	Obligatorio. Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getSubtipoCotizante(), "0", 2, "I"));
            //7	1	30	30	A	Extranjero no obligado a cotizar a pensiones 	Puede ser blanco o X Cuando aplique este campo los únicos tipos de documentos válidos son: CE. Cédula de extranjería PA.  Pasaporte CD.  Carné diplomático Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getExtranjeroNoObligadoCotizarPension(), " ", 1, "D"));
            //8	1	31	31	A	Colombiano en el exterior	Puede ser blanco o X si aplica.  Este campo es utilizado cuando el tipo de documento es: CC.  Cédula de ciudadanía TI.    Tarjeta de identidad Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getColombianoResidenteExterior(), " ", 1, "D"));
            //9	2	32	33	A	Código del Departamento de la ubicación laboral	Lo suministra el aportante. El operador de información deberá validar que este código este definido en la relación de la División Política y Administrativa – DIVIPOLA- expedida por el DANE Cuando marque el campo colombiano en el exterior se dejará  en blanco
            fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoDepartamentoUbicacionlaboral(), " ", 2, "D"));
            //10	3	34	36	A	Código del Municipio de la ubicación laboral	Lo suministra el aportante. El operador de información deberá validar que este código este definido en la relación de la División Política y Administrativa – DIVIPOLA- expedida por el DANE Cuando marque el campo colombiano en el exterior se dejará en blanco
            fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoMunicipioUbicacionlaboral(), " ", 3, "D"));
            //11	20	37	56	A	Primer apellido	Obligatorio. Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getPrimerApellido(), " ", 20, "D"));
            //12	30	57	86	A	Segundo apellido	Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getSegundoApellido(), " ", 30, "D"));
            //13	20	87	106	A	Primer nombre	Obligatorio. Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getPrimerNombre(), " ", 20, "D"));
            //14	30	107	136	A	Segundo nombre	Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getSegundoNombre(), " ", 30, "D"));
            //15	1	137	137	A	ING: ingreso	 Puede ser un blanco, R, X o C. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getIngreso(), " ", 1, "D"));
            //16	1	138	138	A	RET: retiro	Puede ser un blanco, P, R, X o C. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getRetiro(), " ", 1, "D"));
            //17	1	139	139	A	TDE: Traslado desde otra EPS ó EOC	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getTrasladoDesdeOtraEps(), " ", 1, "D"));
            //18	1	140	140	A	TAE: Traslado a otra EPS ó EOC	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getTrasladoAOtraEps(), " ", 1, "D"));
            //19	1	141	141	A	TDP: Traslado desde otra Administradora de Pensiones	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getTrasladoDesdeOtraPension(), " ", 1, "D"));
            //20	1	142	142	A	TAP: Traslado a otra  administradora de pensiones	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getTrasladoAOtraPension(), " ", 1, "D"));
            //21	1	143	143	A	VSP: Variación permantente de salario	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getVariacionPermanenteSalario(), " ", 1, "D"));
            //22	1	144	144	A	Correcciones	Puede ser un blanco, A o C. Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getCorrecciones(), " ", 1, "D"));
            //23	1	145	145	A	VST: Variación transitoria del salario	Puede ser un blanco o X. Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getVariacionTransitoriaSalario(), " ", 1, "D"));
            //24	1	146	146	A	SLN: suspensión temporal del contrato de trabajo o licencia no remunerada o comisión de servicios	Puede ser un blanco, X o C. Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getSuspensionTemporalContratoLicenciaServicios(), " ", 1, "D"));
            //25	1	147	147	A	IGE: Incapacidad Temporal por Enfermedad General	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getIncapacidadGeneral(), " ", 1, "D"));
            //26	1	148	148	A	LMA: Licencia de Maternidad  o de Paternidad	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getLicenciaMaternidad(), " ", 1, "D"));
            //27	1	149	149	A	VAC- LR: Vacaciones, Licencia Remunerada 	Puede ser: X:   Vacaciones L:    Licencia remunerada Blanco: Cuando no aplique esta novedad.
            fputs($ar, $this->RellenarNr($arSsoAporte->getVacaciones(), " ", 1, "D"));
            //28	1	150	150	A	AVP: Aporte Voluntario	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getAporteVoluntario(), " ", 1, "D"));
            //29	1	151	151	A	VCT: Variación centros de trabajo	Puede ser un blanco o X. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getVariacionCentrosTrabajo(), " ", 1, "D"));
            //30	2	152	153	N	IRL:Dias de  Incapacidad por accidente de trabajo o enfermedad laboral	Puede ser cero o el número de días (entre 01 y 30). Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getIncapacidadAccidenteTrabajoEnfermedadProfesional(), "0", 2, "I"));
            //31	6	154	159	A	Código de la Administradora de Fondo de Pensiones a la cual pertenece el afiliado	Es un campo obligatorio y solo se permite blanco, si el tipo de cotizante o el subtipo de cotizante no es obligado a aportar al Sistema General de Pensiones. Se debe utilizar un código válido y este lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoEntidadPensionPertenece(), " ", 6, "D"));
            //32	6	160	165	A	Código de la Administradora de Fondo de Pensiones a la cual se tralada el afiliado	Obligatorio si la novedad es traslado a otra administradora de fondo de pensiones. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoEntidadPensionTraslada(), " ", 6, "D"));
            //33	6	166	171	A	Código EPS ó EOC a la cual pertenece el afiliado	Es un campo obligatorio. Se debe utilizar un código válido y éste lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoEntidadSaludPertenece(), " ", 6, "D"));
            //34	6	172	177	A	Código EPS ó EOC a la cual se traslada el afiliado	Obligatorio si en el campo 18 del registro tipo 2 se marca X. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoEntidadSaludTraslada(), " ", 6, "D"));
            //35	6	178	183	A	Código CCF a la que pertenece el afiliado	Obligatorio y solo se permite blanco, si el tipo de cotizante no es obligado a aportar a CCF. Se debe utilizar un código válido y este lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoEntidadCajaPertenece(), " ", 6, "D"));
            //36	2	184	185	N	Número de días cotizados a pensión	Obligatorio y debe permitir valores entre 0 y 30. Solo se permite 0, si el tipo de cotizante o subtipo de cotizante no está obligado a aportar pensiones. Si es menor que 30 debe haber marcado una novedad de ingreso o retiro. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosPension(), "0", 2, "I"));
            //37	2	186	187	N	Número de días cotizados a salud	Obligatorio y debe permitir valores entre 0 y 30. Si es menor que 30 debe haber marcado  una  novedad  de ingreso o retiro. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosSalud(), "0", 2, "I"));
            //38	2	188	189	N	Número de días cotizados a Riesgos Laborales	Obligatorio y debe permitir valores entre 0 y 30. Solo se permite 0, si el tipo de cotizante no está obligado a aportar al Sistema General de Riesgos Laborales, o si en los campos 25, 26, 27, del registro tipo 2 se ha marcado X o el campo 30 del registro tipo 2 es mayor que 0. Si es menor que 30 debe haber marcado la novedad correspondiente. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosRiesgosProfesionales(), "0", 2, "I"));
            //39	2	190	191	N	Número de días cotizados a Caja de Compensación Familiar	Obligatorio y debe permitir valores entre 0 y 30. Solo se permite 0, si el tipo de cotizante no está obligado a aportar a Cajas de Compensación Familiar  Si es menor que 30 debe haber marcado la novedad correspondiente. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosCajaCompensacion(), "0", 2, "I"));
            //40	9	192	200	N	Salario básico 	Obligatorio, sin comas ni puntos. No puede ser menor cero. Puede ser menor que 1 smlmv. Lo suministra el aportante Este valor debe ser reportado sin centavos
            fputs($ar, $this->RellenarNr($arSsoAporte->getSalarioBasico(), "0", 9, "I"));
            //41	1	201	201	A	Salario Integral	Se debe indicar con una X si el salario es integral o blanco si no lo es. Es responsabilidad del aportante suministrar esta información.
            fputs($ar, $this->RellenarNr($arSsoAporte->getSalarioIntegral(), " ", 1, "D"));
            //42	9	202	210	N	IBC Pensión	Obligatorio. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getIbcPension(), "0", 9, "I"));
            //43	9	211	219	N	IBC Salud	Obligatorio. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getIbcSalud(), "0", 9, "I"));
            //44	9	220	228	N	IBC Riesgos Laborales	Obligatorio. Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getIbcRiesgosProfesionales(), "0", 9, "I"));
            //45	9	229	237	N	IBC CCF	 Es un campo obligatorio para los tipos de cotizante 1, 2, 18,22, 30, 51 y 55.  Lo suministra el aportante.  Para el caso del tipo de cotizante 31 no es obligatorio cuando la cooperativa o precooperativa de trabajo asociado este exceptuada por el Ministerio del Trabajo.
            fputs($ar, $this->RellenarNr($arSsoAporte->getIbcCaja(), "0", 9, "I"));
            //46	7	238	244	N	Tarifa de aportes pensiones	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
            fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaPension() / 100, 5, '.', ''), "0", 7, "I"));
            //47	9	245	253	N	Cotización obligatoria a Pensiones	Obligatorio. Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionPension(), "0", 9, "I"));
            //48	9	254	262	N	Aporte voluntario del afiliado al Fondo de Pensiones Obligatorias	Lo suministra el aportante. Solo aplica para las Administradoras de Pensiones del Régimen de ahorro individual
            fputs($ar, $this->RellenarNr($arSsoAporte->getAporteVoluntarioFondoPensionesObligatorias(), "0", 9, "I"));
            //49	9	263	271	N	Aporte voluntario del aportante al fondo de pensiones obligatoria. 	Lo suministra el aportante. Solo aplica para las Administradoras de Pensiones del Régimen de ahorro individual
            fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionVoluntarioFondoPensionesObligatorias(), "0", 9, "I"));
            //50	9	272	280	N	Total cotización sistema general de pensiones	Lo calcula el sistema. Sumatoria de los campos 47, 48 y 49 del registro tipo 2.
            fputs($ar, $this->RellenarNr($arSsoAporte->getTotalCotizacionFondos(), "0", 9, "I"));
            //51	9	281	289	N	Aportes a Fondo de Solidaridad  Pensional- Subcuenta de solidaridad	Lo suministra el aportante cuando aplique
            fputs($ar, $this->RellenarNr($arSsoAporte->getAportesFondoSolidaridadPensionalSolidaridad(), "0", 9, "I"));
            //52	9	290	298	N	Aportes a Fondo de Solidad Pensional- Subcuenta de subsistencia	Lo suministra el aportante cuando aplique
            fputs($ar, $this->RellenarNr($arSsoAporte->getAportesFondoSolidaridadPensionalSubsistencia(), "0", 9, "I"));
            //53	9	299	307	N	Valor no retenido por aportes voluntarios	Lo suministra el aportante
            fputs($ar, $this->RellenarNr("", "0", 9, "I"));
            //54	7	308	314	N	Tarifa de aportes de salud	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
            fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaSalud() / 100, 5, '.', ''), "0", 7, "I"));
            //55	9	315	323	N	Cotización Obligatoria a salud	Obligatorio. Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionSalud(), "0", 9, "I"));
            //56	9	324	332	N	Valor de la UPC adicional	Debe corresponder al valor reportado en el campo 11 del archivo “información de la Base de Datos Única de Afiliados – BDUA con destino a los operadores de información”
            fputs($ar, $this->RellenarNr($arSsoAporte->getValorUpcAdicional(), "0", 9, "I"));
            //57	15	333	347	A	N° autorización de la incapacidad por enfermedad general	Debe reportarse en blanco
            fputs($ar, $this->RellenarNr($arSsoAporte->getNumeroAutorizacionIncapacidadEnfermedadGeneral(), " ", 15, "D"));
            //58	9	348	356	N	Valor de incapacidad por enfermedad general	Debe reportarse en blanco
            fputs($ar, $this->RellenarNr($arSsoAporte->getValorIncapacidadEnfermedadGeneral(), "0", 9, "I"));
            //59	15	357	371	A	N° autorización de la licencia de maternidad o paternidad	Debe reportarse en blanco
            fputs($ar, $this->RellenarNr($arSsoAporte->getNumeroAutorizacionLicenciaMaternidadPaternidad(), " ", 15, "D"));
            //60	9	372	380	N	Valor de la licencia de maternidad	Debe reportarse en cero
            fputs($ar, $this->RellenarNr($arSsoAporte->getValorIncapacidadLicenciaMaternidadPaternidad(), "0", 9, "I"));
            //61	9	381	389	N	Tarifa de aportes a Riesgos Laborales	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
            fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaRiesgos() / 100, 7, '.', ''), "0", 9, "I"));
            //62	9	390	398	N	Centro de Trabajo CT	Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getCentroTrabajoCodigoCt(), "0", 9, "I"));
            //63	9	399	407	N	Cotización obligatoria al Sistema General de Riesgos Laborales	Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionRiesgos(), "0", 9, "I"));
            //64	7	408	414	N	Tarifa de aportes CCF	Lo suministra el aportante y la valida el Operador de Información de acuerdo con las tarifas vigentes en el periodo a liquidar
            fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaCaja() / 100, 5, '.', ''), "0", 7, "I"));
            //65	9	415	423	N	Valor aporte CCF	Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionCaja(), "0", 9, "I"));
            //66	7	424	430	N	Tarifa de aportes SENA	Lo suministra el aportante
            fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaSENA() / 100, 5, '.', ''), "0", 7, "I"));
            //67	9	431	439	N	Valor aportes SENA	Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionSena(), "0", 9, "I"));
            //68	7	440	446	N	Tarifa aportes ICBF	Lo suministra el aportante
            fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaIcbf() / 100, 5, '.', ''), "0", 7, "I"));
            //69	9	447	455	N	Valor aporte ICBF	Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionIcbf(), "0", 9, "I"));
            //70	7	456	462	N	Tarifa aportes ESAP	Lo suministra el aportante
            fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaAportesESAP() / 100, 5, '.', ''), "0", 7, "I"));
            //71	9	463	471	N	Valor aporte ESAP	Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getValorAportesESAP(), "0", 9, "I"));
            //72	7	472	478	N	Tarifa aportes MEN	Lo suministra el aportante
            fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaAportesMEN() / 100, 5, '.', ''), "0", 7, "I"));
            //73	9	479	487	N	Valor aporte MEN	Lo suministra el aportante
            fputs($ar, $this->RellenarNr($arSsoAporte->getValorAportesMEN(), "0", 9, "I"));
            //74	2	488	489	A	Tipo de documento del cotizante principal	Corresponde al tipo de documento del cotizante Principal que corresponde a: CC.  Cédula de ciudadanía CE.  Cédula de extranjería TI.    Tarjeta de identidad PA.  Pasaporte CD.  Carné diplomático SC.  Salvoconducto de permanencia Lo suministra el aportante Solo debe ser reportado cuando se reporte un cotizante 40.
            fputs($ar, $this->RellenarNr($arSsoAporte->getTipoDocumentoResponsableUPC(), " ", 2, "D"));
            //75	16	490	505	A	Número de identificación del cotizante principal	Lo suministra el aportante Solo debe ser reportado cuando se reporte un cotizante 40. El operador de información validará que este campo este compuesto por letras de la A a la Z y los caracteres numéricos del Cero (0) al nueve (9). Sólo es permitido el número de identificación alfanumérico para los siguientes tipos de documentos de identidad: CE.  Cédula de Extranjería PA.  Pasaporte CD.  Carne Diplomático   Para los siguientes tipos de documento deben ser dígitos numéricos: TI.   Tarjeta de Identidad CC. Cédula de ciudadanía  SC.  Salvoconducto de permanencia
            fputs($ar, $this->RellenarNr($arSsoAporte->getNumeroIdentificacionResponsableUPCAdicional(), " ", 16, "D"));
            //76	1	506	506	A	Cotizante exonerado de pago de aporte salud, SENA e ICBF - Ley 1607 de 2012 	Obligatorio.  Lo suministra el aportante. S = Si  N = No Cuando el valor del campo 43 – IBC Salud sea superior a 10 SMLMV este campo debe ser N Obligatorio.  Lo suministra el aportante. S = Si  N = No   Cuando personas naturales empleen dos o más trabajadores y el valor del campo 43 – IBC Salud sea superior a 10 SMLMV este campo debe ser N
            fputs($ar, $this->RellenarNr($arSsoAporte->getCotizanteExoneradoPagoAporteParafiscalesSalud(), " ", 1, "D"));
            //77	6	507	512	A	Código de la Administradora de Riesgos Laborales a la cual pertenece el afiliado	Lo suministra el aportante. Para el caso de cotizantes diferente al cotizante 3- independiente, se debe registrar el valor ingresado en el Campo 14 del registro Tipo 1 del archivo Tipo 2. Se deja en blanco cuando no sea obligatorio para el cotizante estar afiliado a una Administradora de Riesgos Laborales.
            fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoAdministradoraRiesgosLaborales(), " ", 6, "D"));
            //78	1	513	513	A	Clase de riesgo en la que se encuentra el afiliado	Lo suministra el aportante. 1. Clase de Riesgo I 2. Clase de Riesgo II 3. Clase de Riesgo III 4. Clase de Riesgo IV  5. Clase de Riesgo V  La clase de riesgo de acuerdo a la actividad económica establecida en el Decreto 1607 de 2002 o la norma que lo sustituya o modifique
            fputs($ar, $this->RellenarNr($arSsoAporte->getClaseRiesgoAfiliado(), " ", 1, "D"));
            //79	1	514	514	A	Indicador tarifa especial pensiones 	Lo suministra el aportante y es: Blanco  Tarifa normal 1. Actividades de alto riesgo 2. Senadores 3. CTI 4. Aviadores
            fputs($ar, $this->RellenarNr($arSsoAporte->getIndicadorTarifaEspecialPensiones(), " ", 1, "D"));
            //80	10	515	524	A	Fecha de ingreso Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de ingreso. Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaIngreso(), " ", 10, "D"));
            //81	10	525	534	A	Fecha de retiro. Formato (AAAA-MM- DD).	Es obligatorio cuando se reporte la novedad de retiro.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaRetiro(), " ", 10, "D"));
            //82	10	535	544	A	Fecha Inicio  VSP Formato (AAAA-MM- DD).	Es obligatorio cuando se reporte la novedad de VSP.  Lo suministra el aportante Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaInicioVsp(), " ", 10, "D"));
            //83	10	545	554	A	Fecha Inicio SLN Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de SLN. Lo suministra el aportante.   Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaInicioSln(), " ", 10, "D"));
            //84	10	555	564	A	Fecha fin SLN Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de SLN. Lo suministra el aportante.  Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaFinSln(), " ", 10, "D"));
            //85	10	565	574	A	Fecha inicio  IGE Formato (AAAA-MM- DD).	Es obligatorio cuando se reporte la novedad de IGE.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaInicioIge(), " ", 10, "D"));
            //86	10	575	584	A	Fecha fin IGE. Formato (AAAA-MM- DD) 	Es obligatorio cuando se reporte la novedad de IGE. Lo suministra el aportante.  Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaFinIge(), " ", 10, "D"));
            //87	10	585	594	A	Fecha inicio LMA Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad de LMA.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaInicioLma(), " ", 10, "D"));
            //88	10	595	604	A	Fecha fin LMA Formato (AAAA-MM- DD) 	Es obligatorio cuando se reporte la novedad de LMA.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaFinLma(), " ", 10, "D"));
            //89	10	605	614	A	Fecha inicio VAC - LR Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad VAC - LR. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaInicioVacLr(), " ", 10, "D"));
            //90	10	615	624	A	Fecha fin VAC - LR Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad VAC - LR. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaFinVacLr(), " ", 10, "D"));
            //91	10	625	634	A	Fecha inicio VCT Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad VCT.  Lo suministra el aportante. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaInicioVct(), " ", 10, "D"));
            //92	10	635	644	A	Fecha fin  VCT Formato (AAAA-MM- DD). 	Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaFinVct(), " ", 10, "D"));
            //93	10	645	654	A	Fecha inicio IRL Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad IRL. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco.
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaInicioIrl(), " ", 10, "D"));
            //94	10	655	664	A	Fecha fin  IRL Formato (AAAA-MM- DD). 	Es obligatorio cuando se reporte la novedad IRL. Debe reportarse una fecha valida siempre y cuando la novedad se presente en el periodo que se esté liquidando  Cuando no se reporte la novedad el campo se dejará en blanco
            fputs($ar, $this->RellenarNr($arSsoAporte->getFechaFinIrl(), " ", 10, "D"));
            //95	9	665	673	N	IBC otros parafiscales diferentes a CCF	Es un campo obligatorio para los tipos de cotizante 1, 18, 20, 22, 30, 31, y 55.   Lo suministra el aportante.
            fputs($ar, $this->RellenarNr($arSsoAporte->getIbcOtrosParafiscalesDiferentesCcf(), "0", 9, "I"));
            //96	3	674	676	N	Número de horas laboradas 	Es un campo obligatorio para los tipos de cotizante 1, 2, 18, 22, 30, 51 y 55.  Lo suministra el aportante.  Para el caso del tipo de cotizante 31 no es obligatorio cuando la cooperativa o precooperativa de trabajo asociado este exceptuada por el Ministerio del Trabajo.
            fputs($ar, $this->RellenarNr($arSsoAporte->getNumeroHorasLaboradas(), "0", 3, "I"));
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
    }

}
