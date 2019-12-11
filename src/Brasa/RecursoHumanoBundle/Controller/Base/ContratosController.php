<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuContratoType;
use Doctrine\ORM\EntityRepository;

class ContratosController extends Controller
{
    var $fechaDesdeInicia;
    var $fechaHastaInicia;
    var $strSqlLista = "";

    /**
     * @Route("/rhu/base/contratos/lista/", name="brs_rhu_base_contratos_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 33, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $session = new session;     
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
                if($em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 33, 4)) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionarContrato');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigo) {
                            $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                            $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigo);                                                
                            if($arContrato->getEstadoActivo() == 1 && $arContrato->getEstadoTerminado() == 0) {
                                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato->getCodigoEmpleadoFk());
                                $arEmpleado->setCodigoCentroCostoFk(NULL);
                                $arEmpleado->setCodigoTipoTiempoFk(NULL);
                                $arEmpleado->setVrSalario(0);
                                $arEmpleado->setCodigoClasificacionRiesgoFk(NULL);
                                $arEmpleado->setCodigoCargoFk(NULL);
                                $arEmpleado->setCargoDescripcion(NULL);
                                $arEmpleado->setCodigoTipoPensionFk(NULL);
                                $arEmpleado->setCodigoTipoCotizanteFk(NULL);
                                $arEmpleado->setCodigoSubtipoCotizanteFk(NULL);
                                $arEmpleado->setCodigoEntidadSaludFk(NULL);
                                $arEmpleado->setCodigoEntidadPensionFk(NULL);
                                $arEmpleado->setCodigoEntidadCajaFk(NULL);
                                $arEmpleado->setEstadoContratoActivo(0);
                                $arEmpleado->setCodigoContratoActivoFk(NULL);                        
                                $em->remove($arContrato);
                                $em->persist($arEmpleado);                            
                            }
                        }
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_base_contratos_lista'));
                    }
                } else {
                    $objMensaje->Mensaje('error', "No tiene permisos para esta opcion", $this);
                }                
            }
        }
        
        $arContratos = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:lista.html.twig', array(
            'arContratos' => $arContratos,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/base/contratos/detalle/{codigoContrato}", name="brs_rhu_base_contratos_detalles")
     */
    public function detalleAction(Request $request, $codigoContrato) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $mensaje = 0;
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        $arTrasladoPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuTrasladoPension')->findBy(array('codigoContratoFk' => $codigoContrato));
        $arTrasladoPension = $paginator->paginate($arTrasladoPension, $this->get('request')->query->get('page', 1),10);
        $arTrasladoSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuTrasladoSalud')->findBy(array('codigoContratoFk' => $codigoContrato));
        $arTrasladoSalud = $paginator->paginate($arTrasladoSalud, $this->get('request')->query->get('page', 1),10);
        if ($arContrato->getEstadoActivo() == 1 || $arContrato->getIndefinido() == 1){
            $disabled = FALSE;
        } else {
            $disabled = TRUE;
        }
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', SubmitType::class, array('label'  => 'Imprimir Contrato'))
            ->add('BtnImprimirCartaPresentacion', SubmitType::class, array('label'  => 'Carta presentación'))            
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoContrato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoContrato();
                $objFormatoContrato->Generar($this, $codigoContrato);
            }
            if($form->get('BtnImprimirCartaPresentacion')->isClicked()) {
                $arUsuario = $this->get('security.context')->getToken()->getUser();
                $objFormatoContrato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCartaPresentacion();
                $objFormatoContrato->Generar($this, $codigoContrato,$arUsuario);
            }
            
            //$arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($request->request->get('ImprimirTrasladoPension')) {
                $codigoTrasladoPension = $request->request->get('ImprimirTrasladoPension');
                $arUsuario = $this->get('security.context')->getToken()->getUser();
                $objFormatoTrasladoPension = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCartaTrasladoPension();
                $objFormatoTrasladoPension->Generar($this, $codigoTrasladoPension, $arUsuario);
                
            }
            if($request->request->get('ImprimirTrasladoSalud')) {
                $codigoTrasladoSalud = $request->request->get('ImprimirTrasladoSalud');
                $arUsuario = $this->get('security.context')->getToken()->getUser();
                $objFormatoTrasladoSalud = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCartaTrasladoSalud();
                $objFormatoTrasladoSalud->Generar($this, $codigoTrasladoSalud, $arUsuario);
                
            }
        }
        $arCambiosSalario = new \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario();
        $arCambiosSalario = $em->getRepository('BrasaRecursoHumanoBundle:RhuCambioSalario')->findBy(array('codigoContratoFk' => $codigoContrato));
        $arCambiosSalario = $paginator->paginate($arCambiosSalario, $this->get('Request')->query->get('page', 1),5);
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->findBy(array('codigoContratoFk' => $codigoContrato));
        $arVacaciones = $paginator->paginate($arVacaciones, $this->get('Request')->query->get('page', 1),5);
        $arContratoSedes = new \Brasa\RecursoHumanoBundle\Entity\RhuContratoSede();
        $arContratoSedes = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoSede')->findBy(array('codigoContratoFk' => $codigoContrato));
        $arContratoSedes = $paginator->paginate($arContratoSedes, $this->get('Request')->query->get('page', 1),5);
        
        $arContratoProrrogas = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoProrroga')->findBy(array('codigoContratoFk' => $codigoContrato), array('codigoContratoProrrogaPk' => 'DESC'));
        $arContratoProrrogas = $paginator->paginate($arContratoProrrogas, $this->get('Request')->query->get('page', 1),10);
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:detalle.html.twig', array(
                    'arContrato' => $arContrato,
                    'arCambiosSalario' => $arCambiosSalario,
                    'arVacaciones' => $arVacaciones,
                    'arContratoSedes' => $arContratoSedes,
                    'arTrasladoPension' => $arTrasladoPension,
                    'arTrasladoSalud' => $arTrasladoSalud,
                    'arContratoProrrogas' => $arContratoProrrogas,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/contratos/nuevo/{codigoContrato}/{codigoEmpleado}", name="brs_rhu_contratos_nuevo")
     */
    public function nuevoAction(Request $request, $codigoContrato, $codigoEmpleado) {
        $em = $this->getDoctrine()->getManager();        
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();        
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $intEstado = 0;
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SALARIO MINIMO
        $douSalarioMinimo = $arConfiguracion->getVrSalario();        
        if($codigoContrato != 0) {
            $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        } else {
            $arContrato->setFechaDesde(new \DateTime('now'));
            $arContrato->setFechaHasta(new \DateTime('now'));
            $arContrato->setIndefinido(1);
            $arContrato->setEstadoActivo(1);
            $arContrato->setVrSalario($douSalarioMinimo); //se Parametrizó con configuracion salario minimo
            $douValidarEmpleadoContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->validarEmpleadoContrato($codigoEmpleado);
            if ($douValidarEmpleadoContrato >= 1){
                $objMensaje->Mensaje("error", "El empleado tiene contrato abierto, no se puede generar otro contrato", $this);
                $intEstado = 1;
            }
            if ($arEmpleado->getEmpleadoInformacionInterna() == 1){
               $objMensaje->Mensaje("error", "El empleado esta bloqueado por información interna", $this); 
               $intEstado = 2;
            }

        }
        $form = $this->createForm(RhuContratoType::class, $arContrato); 
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arContrato = $form->getData();
            $boolValidarTipoContrato = TRUE;
            $boolValidarTipoContratoSalud = TRUE;
            $boolValidarContratoFijo = TRUE;
            $boolValidarSalarioIntegral = TRUE;
            if($arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 4 && ($arContrato->getSsoTipoCotizanteRel()->getCodigoTipoCotizantePk() != 12 && $arContrato->getSsoTipoCotizanteRel()->getCodigoTipoCotizantePk() != 19 || $arContrato->getSsoSubtipoCotizanteRel()->getCodigoSubtipoCotizantePk() != 0)) {
                $boolValidarTipoContrato = FALSE;
            } 
                
            if($arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 5 && ($arContrato->getSsoTipoCotizanteRel()->getCodigoTipoCotizantePk() != 23 || $arContrato->getSsoSubtipoCotizanteRel()->getCodigoSubtipoCotizantePk() != 0)) {
                $boolValidarTipoContrato = FALSE;
            }
            if($arContrato->getSalarioIntegral() == true) {
                if($arContrato->getVrSalario() < ($douSalarioMinimo * 13)) {
                    $boolValidarSalarioIntegral = FALSE;
                }
            }
            if($arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 4 || $arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() == 5) {
                if($arContrato->getTipoSaludRel()->getCodigoTipoSaludPk() != 2) {
                    $boolValidarTipoContratoSalud = FALSE;
                }
            } 
            //fin validación
            if ($codigoContrato == 0){
                $douValidarEmpleadoContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->validarEmpleadoContrato($codigoEmpleado);
                if ($douValidarEmpleadoContrato >= 1){
                    $objMensaje->Mensaje("error", "El empleado tiene contrato abierto, no se puede generar otro contrato", $this);
                    $intEstado = 1;
                } else{   
                    if($boolValidarTipoContrato == TRUE) {
                        if($boolValidarTipoContratoSalud == TRUE) {
                            if($boolValidarSalarioIntegral == TRUE) {
                                if($arContrato->getCentroCostoRel()->getFechaUltimoPago() < $arContrato->getFechaDesde() || $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),1)) {
                                    $arContrato->setFecha(date_create(date('Y-m-d H:i:s')));
                                    $arContrato->setEmpleadoRel($arEmpleado);
                                    $arContrato->setFechaHasta($form->get('fechaHasta')->getData());
                                    $dateFechaUltimoPago = $arContrato->getFechaDesde()->format('Y-m-d');
                                    $dateFechaUltimoPago = date("Y-m-d", strtotime("$dateFechaUltimoPago -1 day"));
                                    $dateFechaUltimoPago = date_create_from_format('Y-m-d H:i', $dateFechaUltimoPago . "00:00");
                                    $arContrato->setFechaUltimoPago($dateFechaUltimoPago);
                                    $arContrato->setFechaUltimoPagoCesantias($arContrato->getFechaDesde());
                                    $arContrato->setFechaUltimoPagoPrimas($arContrato->getFechaDesde());
                                    $arContrato->setFechaUltimoPagoVacaciones($arContrato->getFechaDesde());
                                    $arContrato->setFactor($arContrato->getTipoTiempoRel()->getFactor());
                                    $arContrato->setFactorHorasDia($arContrato->getTipoTiempoRel()->getFactorHorasDia());
                                    $arContrato->setContratoClaseRel($arContrato->getContratoTipoRel()->getContratoClaseRel());
                                    if($arContrato->getTipoTiempoRel()->getFactor() > 0) {
                                        $arContrato->setVrSalarioPago($arContrato->getVrSalario() / $arContrato->getTipoTiempoRel()->getFactor());
                                    } else {
                                        $arContrato->setVrSalarioPago($arContrato->getVrSalario());
                                    }                                    
                                    $arContrato->setCodigoUsuario($arUsuario->getUserName());
                                    $em->persist($arContrato);                                        

                                    //Insertar el recurso en recursos
                                    if($codigoContrato == 0) {
                                        if($arEmpleado->getEmpleadoTipoRel()->getTipo() == 2) {
                                            $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                                            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->findOneBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk()));
                                            if($arRecurso) {                                                
                                                $arRecurso->setEstadoRetiro(0);
                                                $arRecurso->setEstadoActivo(1);
                                                $em->persist($arRecurso);
                                            } else {
                                                $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                                                $arRecurso->setCodigoRecursoPk($arEmpleado->getCodigoEmpleadoPk());
                                                $arRecurso->setEmpleadoRel($arEmpleado);
                                                $arRecurso->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                                                $arRecurso->setNombreCorto($arEmpleado->getNombreCorto());
                                                $arRecurso->setTelefono($arEmpleado->getTelefono());
                                                $arRecurso->setCelular($arEmpleado->getCelular());
                                                $arRecurso->setDireccion($arEmpleado->getDireccion());
                                                $arRecurso->setCorreo($arEmpleado->getCorreo());
                                                $arRecurso->setFechaNacimiento($arEmpleado->getFechaNacimiento());
                                                $arRecursoGrupo = new \Brasa\TurnoBundle\Entity\TurRecursoGrupo();
                                                $arRecursoGrupo = $em->getRepository('BrasaTurnoBundle:TurRecursoGrupo')->find($arContrato->getCentroCostoRel()->getCodigoRecursoGrupoFk());                                                    
                                                if($arRecursoGrupo) {
                                                    $arRecurso->setRecursoGrupoRel($arRecursoGrupo);
                                                }
                                                $em->persist($arRecurso);
                                            }                                                 
                                        }                                           
                                    }

                                    $em->flush();

                                    if($codigoContrato == 0 && $arContrato->getVrSalario() <= $douSalarioMinimo * 2) {
                                        $arEmpleado->setAuxilioTransporte(1);
                                    } else {
                                        $arEmpleado->setAuxilioTransporte(0);
                                    }
                                    $arEmpleado->setCentroCostoRel($arContrato->getCentroCostoRel());
                                    $arEmpleado->setTipoTiempoRel($arContrato->getTipoTiempoRel());
                                    $arEmpleado->setVrSalario($arContrato->getVrSalario());
                                    $arEmpleado->setFechaContrato($arContrato->getFechaDesde());
                                    $arEmpleado->setFechaFinalizaContrato($arContrato->getFechaHasta());
                                    $arEmpleado->setClasificacionRiesgoRel($arContrato->getClasificacionRiesgoRel());
                                    $arEmpleado->setCargoRel($arContrato->getCargoRel());
                                    $arEmpleado->setCargoDescripcion($arContrato->getCargoDescripcion());
                                    $arEmpleado->setTipoPensionRel($arContrato->getTipoPensionRel());
                                    $arEmpleado->setTipoSaludRel($arContrato->getTipoSaludRel());
                                    $arEmpleado->setSsoTipoCotizanteRel($arContrato->getSsoTipoCotizanteRel());
                                    $arEmpleado->setSsoSubtipoCotizanteRel($arContrato->getSsoSubtipoCotizanteRel());
                                    $arEmpleado->setEstadoContratoActivo(1);
                                    $arEmpleado->setEstadoActivo(1);
                                    $arEmpleado->setCodigoContratoActivoFk($arContrato->getCodigoContratoPk());
                                    $arEmpleado->setEntidadPensionRel($arContrato->getEntidadPensionRel());
                                    $arEmpleado->setEntidadSaludRel($arContrato->getEntidadSaludRel());
                                    $arEmpleado->setEntidadCajaRel($arContrato->getEntidadCajaRel());
                                    $arEmpleado->setCodigoContratoUltimoFk($arContrato->getCodigoContratoPk());
                                    $em->persist($arEmpleado);
                                    $em->flush();
                                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                                } else {
                                    echo "La fecha de inicio del contrato debe ser mayor a la ultima fecha de pago del centro de costos " . $arContrato->getCentroCostoRel()->getFechaUltimoPago()->format('Y-m-d');
                                }                                    
                            } else {
                                $objMensaje->Mensaje('error', "El salario integral debe ser mayor a 13 salarios minimos", $this);
                            }                                
                        } else {
                            $objMensaje->Mensaje("error", "Los contratos de practicante/aprendizaje del sena (lectiva-productiva) la salud va a cargo del empleador", $this);
                        }
                    } else {
                        echo "Verifique el tipo de contrato con el tipo y subtipo de cotizante a seguridad social";
                    }                  
                }
            } else {
                if ($boolValidarContratoFijo == FALSE){
                    $objMensaje->Mensaje("error", "La duración del contrato no puede ser mayor o igual a un año", $this);
                } else {
                    if($boolValidarTipoContrato == TRUE) {
                        if($arContrato->getCentroCostoRel()->getFechaUltimoPago() < $arContrato->getFechaDesde() || $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),1)) {
                            $arContrato->setFecha(date_create(date('Y-m-d H:i:s')));
                            $arContrato->setEmpleadoRel($arEmpleado);
                            $arContrato->setFactor($arContrato->getTipoTiempoRel()->getFactor());
                            $arContrato->setFactorHorasDia($arContrato->getTipoTiempoRel()->getFactorHorasDia());
                            $arContrato->setContratoClaseRel($arContrato->getContratoTipoRel()->getContratoClaseRel());
                            if($arContrato->getTipoTiempoRel()->getFactor() > 0) {
                                $arContrato->setVrSalarioPago($arContrato->getVrSalario() / $arContrato->getTipoTiempoRel()->getFactor());
                            } else {
                                $arContrato->setVrSalarioPago($arContrato->getVrSalario());
                            }
                            $em->persist($arContrato);
                            $em->flush();
                            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SALARIO MINIMO
                            $douSalarioMinimo = $arConfiguracion->getVrSalario();
                            //$douSalarioMinimo = 644350;
                            if($arContrato->getVrSalario() <= $douSalarioMinimo * 2) {
                                $arEmpleado->setAuxilioTransporte(1);
                            } else {
                                $arEmpleado->setAuxilioTransporte(0);
                            }
                            $arEmpleado->setCentroCostoRel($arContrato->getCentroCostoRel());
                            $arEmpleado->setTipoTiempoRel($arContrato->getTipoTiempoRel());
                            $arEmpleado->setVrSalario($arContrato->getVrSalario());
                            $arEmpleado->setFechaContrato($arContrato->getFechaDesde());
                            $arEmpleado->setFechaFinalizaContrato($arContrato->getFechaHasta());
                            $arEmpleado->setClasificacionRiesgoRel($arContrato->getClasificacionRiesgoRel());
                            $arEmpleado->setCargoRel($arContrato->getCargoRel());
                            $arEmpleado->setCargoDescripcion($arContrato->getCargoDescripcion());
                            $arEmpleado->setTipoPensionRel($arContrato->getTipoPensionRel());
                            $arEmpleado->setTipoSaludRel($arContrato->getTipoSaludRel());
                            $arEmpleado->setSsoTipoCotizanteRel($arContrato->getSsoTipoCotizanteRel());
                            $arEmpleado->setSsoSubtipoCotizanteRel($arContrato->getSsoSubtipoCotizanteRel());
                            $arEmpleado->setEstadoContratoActivo(1);
                            $arEmpleado->setEstadoActivo(1);
                            $arEmpleado->setCodigoContratoActivoFk($arContrato->getCodigoContratoPk());
                            $arEmpleado->setEntidadPensionRel($arContrato->getEntidadPensionRel());
                            $arEmpleado->setEntidadSaludRel($arContrato->getEntidadSaludRel());
                            $arEmpleado->setEntidadCajaRel($arContrato->getEntidadCajaRel());
                            $arEmpleado->setEntidadCesantiaRel($arContrato->getEntidadCesantiaRel());
                            $em->persist($arEmpleado);
                            $em->flush();
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        } else {
                            echo "La fecha de inicio del contrato debe ser mayor a la ultima fecha de pago del centro de costos " . $arContrato->getCentroCostoRel()->getFechaUltimoPago()->format('Y-m-d');
                          }   
                    } else {
                        echo "Verifique el tipo de contrato con el tipo y subtipo de cotizante a seguridad social";
                      }  
                }  
            }   
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:nuevo.html.twig', array(
            'arContrato' => $arContrato,
            'arEmpleado' => $arEmpleado,
            'intEstado' => $intEstado,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/contratos/terminar/{codigoContrato}", name="brs_rhu_contratos_terminar")
     */
    public function terminarAction(Request $request, $codigoContrato) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        $formContrato = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_contratos_terminar', array('codigoContrato' => $codigoContrato)))
            ->add('fechaTerminacion', DateType::class, array('label'  => 'Terminacion', 'data' => new \DateTime('now')))
            ->add('terminacionContratoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuMotivoTerminacionContrato',
                        'choice_label' => 'motivo',
            ))      
            ->add('comentarioTerminacion', TextareaType::class, array('required' => false))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $formContrato->handleRequest($request);
        $arDotacionPendiente = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->dotacionDevolucion($arContrato->getCodigoEmpleadoFk());
        $registrosDotacionesPendientes = count($arDotacionPendiente);
        if ($registrosDotacionesPendientes > 0){
            $mensaje = "El empleado tiene dotaciones pendientes por entregar, no se puede terminar el contrato";
        }else{
            $mensaje = "";
        }    
        if ($formContrato->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $dateFechaHasta = $formContrato->get('fechaTerminacion')->getData();
            $arMotivoTerminacion = new \Brasa\RecursoHumanoBundle\Entity\RhuMotivoTerminacionContrato();
            $codigoMotivoContrato = $formContrato->get('terminacionContratoRel')->getData();            
            $arMotivoTerminacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuMotivoTerminacionContrato')->find($codigoMotivoContrato);           
            $comentarioTerminacion = $formContrato->get('comentarioTerminacion')->getData();            
            if($dateFechaHasta >= $arContrato->getFechaUltimoPago() || $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),11)) {
                if ($em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),113)){
                    if($em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->validarCierreContrato($dateFechaHasta, $arContrato->getCodigoEmpleadoFk())) {
                        if($em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->validarCierreContrato($dateFechaHasta, $arContrato->getCodigoEmpleadoFk())) {
                            if ($registrosDotacionesPendientes <= 0){
                                $arContrato->setFechaHasta($dateFechaHasta);
                                $arContrato->setIndefinido(0);
                                $arContrato->setEstadoActivo(0);
                                $arContrato->setEstadoLiquidado(1);
                                $arContrato->setEstadoTerminado(1);
                                $arContrato->setCodigoUsuarioTermina($arUsuario->getUserName());
                                $arContrato->setTerminacionContratoRel($codigoMotivoContrato);
                                $arContrato->setComentariosTerminacion($comentarioTerminacion);
                                $em->persist($arContrato);
                                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato->getCodigoEmpleadoFk());
                                $arEmpleado->setCodigoCentroCostoFk(NULL);
                                $arEmpleado->setCodigoTipoTiempoFk(NULL);
                                $arEmpleado->setVrSalario(0);
                                $arEmpleado->setCodigoClasificacionRiesgoFk(NULL);
                                $arEmpleado->setCodigoCargoFk(NULL);
                                $arEmpleado->setCargoDescripcion(NULL);
                                $arEmpleado->setCodigoTipoPensionFk(NULL);
                                $arEmpleado->setCodigoTipoCotizanteFk(NULL);
                                $arEmpleado->setCodigoSubtipoCotizanteFk(NULL);
                                $arEmpleado->setCodigoEntidadSaludFk(NULL);
                                $arEmpleado->setCodigoEntidadPensionFk(NULL);
                                $arEmpleado->setCodigoEntidadCajaFk(NULL);
                                $arEmpleado->setCodigoEntidadCesantiaFk(NULL);
                                $arEmpleado->setEstadoContratoActivo(0);
                                $arEmpleado->setCodigoContratoActivoFk(NULL);
                                $arEmpleado->setCodigoContratoUltimoFk($codigoContrato);
                                $em->persist($arEmpleado);

                                //Generar liquidacion
                                if($arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() != 4 && $arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() != 5) {
                                    $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
                                    $arLiquidacion->setFecha(new \DateTime('now'));
                                    $arLiquidacion->setCentroCostoRel($arContrato->getCentroCostoRel());
                                    $arLiquidacion->setEmpleadoRel($arContrato->getEmpleadoRel());
                                    $arLiquidacion->setContratoRel($arContrato);
                                    $arLiquidacion->setMotivoTerminacionRel($codigoMotivoContrato);
                                    if($arContrato->getFechaUltimoPagoCesantias() > $arContrato->getFechaDesde()) {
                                        $arLiquidacion->setFechaDesde($arContrato->getFechaUltimoPagoCesantias());
                                    } else {
                                        $arLiquidacion->setFechaDesde($arContrato->getFechaDesde());
                                    }
                                    $arLiquidacion->setFechaHasta($arContrato->getFechaHasta());
                                    $arLiquidacion->setLiquidarCesantias(1);
                                    $arLiquidacion->setLiquidarPrima(1);
                                    $arLiquidacion->setLiquidarVacaciones(1);
                                    if($arContrato->getSalarioIntegral() == 1) {
                                        $arLiquidacion->setLiquidarCesantias(0);
                                        $arLiquidacion->setLiquidarPrima(0);                                        
                                    }
                                    $arLiquidacion->setCodigoUsuario($arUsuario->getUserName());                                
                                    //Para clientes que manejan porcentajes en la liquidacion
                                    $arLiquidacion->setPorcentajeIbp(100);
                                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                                    if($arConfiguracion->getGeneraPorcetnajeLiquidacion()) {
                                        if($arContrato->getCodigoSalarioTipoFk() == 2) {
                                            if($arLiquidacion->getCodigoMotivoTerminacionContratoFk() != 5 && $arLiquidacion->getCodigoMotivoTerminacionContratoFk() != 4) {
                                                $intDiasLaborados = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestaciones($arContrato->getFechaDesde(), $arContrato->getFechaHasta());                                
                                                $arParametrosPrestacion = new \Brasa\RecursoHumanoBundle\Entity\RhuParametroPrestacion();
                                                $arParametrosPrestacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuParametroPrestacion')->findBy(array('tipo' => 'LIQ'));                                
                                                foreach ($arParametrosPrestacion as $arParametroPrestacion) {
                                                    if($intDiasLaborados >= $arParametroPrestacion->getDiaDesde() && $intDiasLaborados <= $arParametroPrestacion->getDiaHasta()) {
                                                        if($arParametroPrestacion->getOrigen() == 'SAL') {
                                                            $arLiquidacion->setLiquidarSalario(1);
                                                        } else {
                                                            $arLiquidacion->getPorcentajeIbp($arParametroPrestacion->getPorcentaje());
                                                        }
                                                    }
                                                }                                            
                                            }                                   
                                        }                                        
                                    }                                    
                                    $em->persist($arLiquidacion);
                                    //Verificar creditos
                                    $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                                    $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->pendientes($arContrato->getCodigoEmpleadoFk());
                                    foreach ($arCreditos as $arCredito) {
                                        $arLiquidacionAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
                                        $arLiquidacionAdicionales->setCreditoRel($arCredito);
                                        $arLiquidacionAdicionales->setPagoConceptoRel($arCredito->getCreditoTipoRel()->getPagoConceptoRel());
                                        $arLiquidacionAdicionales->setLiquidacionRel($arLiquidacion);
                                        $arLiquidacionAdicionales->setVrDeduccion($arCredito->getSaldo());
                                        $em->persist($arLiquidacionAdicionales);
                                    }
                                }

                                //Terminar un recurso programacion
                                $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                                $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->findOneBy(array('codigoEmpleadoFk' => $arContrato->getCodigoEmpleadoFk()));
                                if($arRecurso) {
                                    $arRecurso->setFechaRetiro($dateFechaHasta);
                                    $arRecurso->setEstadoRetiro(1);
                                    $arRecurso->setEstadoActivo(0);
                                    $em->persist($arRecurso);
                                }

                                $em->flush();
                                //$em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($arLiquidacion->getCodigoLiquidacionPk());
                            } else {
                                $objMensaje->Mensaje("error", "No puede terminar un contrato con dotaciones pendientes", $this);
                              }
                        } else {
                            $objMensaje->Mensaje("error", "No puede terminar un contrato con licencias pendientes", $this);
                        }

                    } else {
                        $objMensaje->Mensaje("error", "No puede terminar un contrato con incapacidades pendientes", $this);
                    }
                } else {
                    $objMensaje->Mensaje("error", "No tiene permisos para terminar un contrato", $this);
                }
            } else {
                $objMensaje->Mensaje("error", "No puede terminar un contrato antes del ultimo pago", $this);
            }
            return $this->redirect($this->generateUrl('brs_rhu_base_contratos_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:terminar.html.twig', array(
            'arContrato' => $arContrato,
            'formContrato' => $formContrato->createView(),
            'mensaje' => $mensaje
        ));
    }
    
    /**
     * @Route("/rhu/contratos/cambiotipocontrato/{codigoContrato}", name="brs_rhu_contratos_cambiotipocontrato")
     */
    public function cambioContratoAction(Request $request, $codigoContrato) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        $arCambioTipoContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato();
        $formContrato = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_contratos_cambiotipocontrato', array('codigoContrato' => $codigoContrato)))
            //->add('fechaTerminacion', 'date', array('label'  => 'Terminacion', 'data' => new \DateTime('now')))
            ->add('contratoTipoNuevoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuContratoTipo',
                'choice_label' => 'nombre',
                'required' => true        
            ))
            ->add('VrSalarioNuevo', NumberType::class, array('data' =>$arContrato->getVrSalario() ,'required' => true))                      
            ->add('detalle', TextType::class, array('required' => true))          
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $formContrato->handleRequest($request);
           
        if ($formContrato->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arCambioTipoContrato->setContratoRel($arContrato);
            $arCambioTipoContrato->setEmpleadoRel($arContrato->getEmpleadoRel());
            $arCambioTipoContrato->setContratoTipoAnteriorRel($arContrato->getContratoTipoRel());
            $arCambioTipoContrato->setContratoTipoNuevoRel($formContrato->get('contratoTipoNuevoRel')->getData());
            $arCambioTipoContrato->setFecha(new \DateTime('now'));
            $arCambioTipoContrato->setVrSalarioAnterior($arContrato->getVrSalario());
            $arCambioTipoContrato->setVrSalarioNuevo($formContrato->get('VrSalarioNuevo')->getData());
            $arCambioTipoContrato->setDetalle($formContrato->get('detalle')->getData());
            $arCambioTipoContrato->setCodigoUsuario($arUsuario->getUserName());
            $arContrato->setContratoTipoRel($formContrato->get('contratoTipoNuevoRel')->getData());
            $arContrato->setVrSalario($formContrato->get('VrSalarioNuevo')->getData());
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato->getCodigoEmpleadoFk());
            $arEmpleado->setVrSalario($formContrato->get('VrSalarioNuevo')->getData());
            $em->persist($arContrato);
            $em->persist($arCambioTipoContrato);
            $em->persist($arEmpleado);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_contratos_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:cambioTipoContrato.html.twig', array(
            'arContrato' => $arContrato,
            'formContrato' => $formContrato->createView()
        ));
    }
    
    /**
     * @Route("/rhu/contratos/actualizar/terminado/{codigoContrato}", name="brs_rhu_contratos_actualizar_terminado")
     */
    public function actualizarContratoTerminadoAction(Request $request, $codigoContrato) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        //$permiso = "";
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        $permiso = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),5);
        $formActualizar = $this->createFormBuilder()
            //->setAction($this->generateUrl('brs_rhu_contratos_actualizar_terminado', array('codigoContrato' => $codigoContrato)))
            ->add('clasificacionRiesgoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuClasificacionRiesgo',
                'choice_label' => 'nombre',
                'data' => $arContrato->getClasificacionRiesgoRel(),
            ))
            ->add('pensionRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadPension',
                'choice_label' => 'nombre',
                'data' => $arContrato->getEntidadPensionRel(),
            ))
            ->add('saludRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'choice_label' => 'nombre',
                'data' => $arContrato->getEntidadSaludRel(),
            ))
            ->add('cajaRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadCaja',
                'choice_label' => 'nombre',
                'data' => $arContrato->getCargoRel(),
            ))    
            ->add('terminacionContratoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuMotivoTerminacionContrato',
                'choice_label' => 'motivo',
                'data' => $arContrato->getTerminacionContratoRel(),
            ))   
            ->add('contratoTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuContratoTipo',
                'choice_label' => 'nombre',
                'data' => $arContrato->getContratoTipoRel(),
            ))                
            ->add('salarioTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSalarioTipo',
                'choice_label' => 'nombre',
                'data' => $arContrato->getSalarioTipoRel(),
            ))  
            ->add('ssoTipoCotizanteRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoTipoCotizante',
                'choice_label' => 'nombre',
                'data' => $arContrato->getSsoTipoCotizanteRel(),
            ))
            ->add('ssoSubtipoCotizanteRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoSubtipoCotizante',
                'choice_label' => 'nombre',
                'data' => $arContrato->getSsoSubtipoCotizanteRel(),
            ))                
            ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $arContrato->getFechaHasta()  ,'attr' => array('class' => 'date',)))                                    
            ->add('vrDevengadoPactado', NumberType::class, array('data' => $arContrato->getVrDevengadoPactado()))                
            ->add('turnoFijoOrdinario', CheckboxType::class, array('required'  => false, 'data' => $arContrato->getTurnoFijoOrdinario()))                 
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $formActualizar->handleRequest($request);
        if ($permiso == false){
                $objMensaje->Mensaje("error", "No tiene permisos para actualizar el contrato", $this);
        }
        if ($formActualizar->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            if ($permiso == false){
                $objMensaje->Mensaje("error", "No tiene permisos para actualizar el contrato", $this);
            } else {
            $arContrato->setContratoTipoRel($formActualizar->get('contratoTipoRel')->getData());
            $arContrato->setSalarioTipoRel($formActualizar->get('salarioTipoRel')->getData());
            $arContrato->setTerminacionContratoRel($formActualizar->get('terminacionContratoRel')->getData());
            $arContrato->setClasificacionRiesgoRel($formActualizar->get('clasificacionRiesgoRel')->getData());
            $arContrato->setEntidadPensionRel($formActualizar->get('pensionRel')->getData());
            $arContrato->setEntidadSaludRel($formActualizar->get('saludRel')->getData());
            $arContrato->setEntidadCajaRel($formActualizar->get('cajaRel')->getData());
            $arContrato->setFechaHasta($formActualizar->get('fechaHasta')->getData());
            $arContrato->setSsoTipoCotizanteRel($formActualizar->get('ssoTipoCotizanteRel')->getData());
            $arContrato->setSsoSubtipoCotizanteRel($formActualizar->get('ssoSubtipoCotizanteRel')->getData());
            $arContrato->setVrDevengadoPactado($formActualizar->get('vrDevengadoPactado')->getData());
            $arContrato->setTurnoFijoOrdinario($formActualizar->get('turnoFijoOrdinario')->getData());
            
            $em->persist($arContrato);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:actualizarContratoTerminado.html.twig', array(
            'arContrato' => $arContrato,
            'formActualizar' => $formActualizar->createView()
        ));
    }
    
    /**
     * @Route("/rhu/contratos/informacion/inicial/{codigoContrato}", name="brs_rhu_contratos_informacion_inicial")
     */
    public function informacionInicialAction(Request $request, $codigoContrato) {
        $em = $this->getDoctrine()->getManager();
        
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        $formIbpAdicional = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_contratos_informacion_inicial', array('codigoContrato' => $codigoContrato)))
            ->add('ibpCesantiasInicial', NumberType::class, array('data' =>$arContrato->getIbpCesantiasInicial() ,'required' => false))      
            ->add('ibpPrimasInicial', NumberType::class, array('data' =>$arContrato->getIbpPrimasInicial() ,'required' => false))                      
            ->add('promedioRecargoNocturnoInicial', NumberType::class, array('data' =>$arContrato->getPromedioRecargoNocturnoInicial() ,'required' => false))                                      
            ->add('fechaUltimoPagoCesantias', DateType::class, array('data' =>$arContrato->getFechaUltimoPagoCesantias(), 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                 
            ->add('fechaUltimoPagoPrimas', DateType::class, array('data' =>$arContrato->getFechaUltimoPagoPrimas(), 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                 
            ->add('fechaUltimoPagoVacaciones', DateType::class, array('data' =>$arContrato->getFechaUltimoPagoVacaciones(), 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $formIbpAdicional->handleRequest($request);    
        if ($formIbpAdicional->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $ibpCesantiasInicial = $formIbpAdicional->get('ibpCesantiasInicial')->getData();
            $ibpPrimasInicial = $formIbpAdicional->get('ibpPrimasInicial')->getData();
            $promedioRecargoNocturnoInicial = $formIbpAdicional->get('promedioRecargoNocturnoInicial')->getData();
            $fechaUltimoPagoCesantias = $formIbpAdicional->get('fechaUltimoPagoCesantias')->getData();
            $fechaUltimoPagoPrimas = $formIbpAdicional->get('fechaUltimoPagoPrimas')->getData();
            $fechaUltimoPagoVacaciones = $formIbpAdicional->get('fechaUltimoPagoVacaciones')->getData(); 
            $arContrato->setIbpCesantiasInicial($ibpCesantiasInicial);
            $arContrato->setIbpPrimasInicial($ibpPrimasInicial);
            $arContrato->setPromedioRecargoNocturnoInicial($promedioRecargoNocturnoInicial);
            $arContrato->setFechaUltimoPagoCesantias($fechaUltimoPagoCesantias);
            $arContrato->setFechaUltimoPagoPrimas($fechaUltimoPagoPrimas);
            $arContrato->setFechaUltimoPagoVacaciones($fechaUltimoPagoVacaciones);
            $em->persist($arContrato);
            $em->flush();
       
            return $this->redirect($this->generateUrl('brs_rhu_base_contratos_detalles', array('codigoContrato' => $codigoContrato)));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:ibpAdicional.html.twig', array(
            'arContrato' => $arContrato,
            'formIbpAdicional' => $formIbpAdicional->createView()
        ));
    }

    /**
     * @Route("/rhu/contratos/sedes/nuevo/{codigoContrato}", name="brs_rhu_contratos_sedes_nuevo")
     */
    public function detalleSedeNuevoAction(Request $request, $codigoContrato) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        $codigoCentroCosto = $arContrato->getCodigoCentroCostoFk();
        $form = $this->createFormBuilder()
            ->add('sedeRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSede',
                'query_builder' => function (EntityRepository $er) use($codigoCentroCosto) {
                    return $er->createQueryBuilder('s')
                    ->where('s.codigoCentroCostoFk = :centroCosto')
                    ->setParameter('centroCosto', $codigoCentroCosto)
                    ->orderBy('s.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('guardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $arContratoSede = new \Brasa\RecursoHumanoBundle\Entity\RhuContratoSede();
            $arContratoSede->setContratoRel($arContrato);
            $arContratoSede->setSedeRel($form->get('sedeRel')->getData());
            $em->persist($arContratoSede);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:nuevaSede.html.twig', array(
            'arContrato' => $arContrato,
            'form' => $form->createView()));
    }

    private function listar() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->listaDQL(
                $session->get('filtroIdentificacion'),
                $session->get('filtroDesdeInicia'),
                $session->get('filtroHastaInicia'),
                $session->get('filtroContratoActivo'),
                $session->get('filtroCodigoCentroCosto')
                );
    }
    
    /**
     * @Route("/rhu/base/contratos/documentos/{codigoContrato}", name="brs_rhu_base_contratos_documentos")
     */
    public function documentosAction(Request $request, $codigoContrato) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnEntregaDocumentos', SubmitType::class, array('label'  => 'Imprimir'))
            ->getForm(); 
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($arrSeleccionados == null){
                $objMensaje->Mensaje("error", "No ha seleccionado ningun documento", $this);
            } else {
                foreach ($arrSeleccionados AS $codigoDocumento){
                    //$arEntregaDocumento = new \Brasa\RecursoHumanoBundle\Entity\RhuEntregaDocumento();
                    $arEntregaDocumento = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntregaDocumento')->find($codigoDocumento);                                
                }
                $objFormatoContrato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoEntregaDocumentos();
                $objFormatoContrato->Generar($this, $codigoContrato,$arrSeleccionados);
            }   
            
        }
        $arEntregaDocumentos = new \Brasa\RecursoHumanoBundle\Entity\RhuEntregaDocumento();
        $arEntregaDocumentos = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntregaDocumento')->findAll();
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:documentos.html.twig', array(
                    'arEntregaDocumentos' => $arEntregaDocumentos,
                    'form'=> $form->createView()
        ));
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $strNombreEmpleado = "";
        if($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if($arEmpleado) {                
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
            }  else {
                $session->set('filtroIdentificacion', null);
            }          
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', EntityType::class, $arrayPropiedades)
            ->add('txtNumeroIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('txtNombreCorto', TextType::class, array('label'  => 'Nombre','data' => $strNombreEmpleado))
            ->add('fechaDesdeInicia', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHastaInicia', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('estadoActivo', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'ACTIVOS', '0' => 'INACTIVOS')))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrar ($form) {
        $session = new session;
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $arrControles = $request->request->All();
        $session->set('filtroDesdeInicia', $form->get('fechaDesdeInicia')->getData());
        $session->set('filtroHastaInicia', $form->get('fechaHastaInicia')->getData());
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroContratoActivo', $form->get('estadoActivo')->getData());
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
    }

    private function generarExcel() {
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
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'AB'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'COD.EMP')
                    ->setCellValue('C1', 'DOCUMENTO')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'TIPO')
                    ->setCellValue('F1', 'FECHA')
                    ->setCellValue('G1', 'C.COSTO')
                    ->setCellValue('H1', 'E.SALUD')
                    ->setCellValue('I1', 'E.PENSIÓN')
                    ->setCellValue('J1', 'E.CAJA')
                    ->setCellValue('K1', 'E.CESANTIA')
                    ->setCellValue('L1', 'COTIZANTE')
                    ->setCellValue('M1', 'SUBCOTIZANTE')
                    ->setCellValue('N1', 'TIEMPO')
                    ->setCellValue('O1', 'DESDE')
                    ->setCellValue('P1', 'HASTA')
                    ->setCellValue('Q1', 'SALARIO')
                    ->setCellValue('R1', 'SALARIO')
                    ->setCellValue('S1', 'CARGO')
                    ->setCellValue('T1', 'CARGO DESCRIPCION')
                    ->setCellValue('U1', 'RIESGO')
                    ->setCellValue('V1', 'ULT.PAGO')
                    ->setCellValue('W1', 'ULT.PAGO PRIMAS')
                    ->setCellValue('X1', 'ULT.PAGO CESANTIAS')
                    ->setCellValue('Y1', 'ULT.PAGO VACACIONES')
                    ->setCellValue('Z1', 'TERMINADO')
                    ->setCellValue('AA1', 'LHE');
        $i = 2;
        
        $query = $em->createQuery($this->strSqlLista);
        $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContratos = $query->getResult();
        
        foreach ($arContratos as $arContrato) {
            if ($arContrato->getCodigoSalarioTipoFk() == null){
                $tipoSalario = "";
            } else {
                $tipoSalario = $arContrato->getSalarioTipoRel()->getNombre();
            }
            
            if ($arContrato->getCodigoEntidadCesantiaFk() == null){
                $entidadCesantia = "";
            } else {
                $entidadCesantia = $arContrato->getEntidadCesantiaRel()->getNombre();
            }
            if ($arContrato->getFechaUltimoPagoVacaciones() != null){
                $fechaUltimaPagoVacaciones = $arContrato->getFechaUltimoPagoVacaciones()->Format('Y-m-d');
            } else {
                $fechaUltimaPagoVacaciones = "";
            }
            if ($arContrato->getFechaUltimoPagoCesantias() != null){
                $fechaUltimaPagoCesantias = $arContrato->getFechaUltimoPagoCesantias()->Format('Y-m-d');
            } else {
                $fechaUltimaPagoCesantias = "";
            }
            if ($arContrato->getFechaUltimoPagoPrimas() != null){
                $fechaUltimaPagoPrimas = $arContrato->getFechaUltimoPagoPrimas()->Format('Y-m-d');
            } else {
                $fechaUltimaPagoPrimas = "";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arContrato->getCodigoContratoPk())
                    ->setCellValue('B' . $i, $arContrato->getCodigoEmpleadoFk())
                    ->setCellValue('C' . $i, $arContrato->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arContrato->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arContrato->getContratoTipoRel()->getNombre())
                    ->setCellValue('F' . $i, $arContrato->getFecha()->Format('Y-m-d'))
                    ->setCellValue('G' . $i, $arContrato->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $arContrato->getEntidadSaludRel()->getNombre())
                    ->setCellValue('I' . $i, $arContrato->getEntidadPensionRel()->getNombre())
                    ->setCellValue('J' . $i, $arContrato->getEntidadCajaRel()->getNombre())
                    ->setCellValue('K' . $i, $entidadCesantia)
                    ->setCellValue('L' . $i, $arContrato->getSsoTipoCotizanteRel()->getNombre())
                    ->setCellValue('M' . $i, $arContrato->getSsoSubtipoCotizanteRel()->getNombre())
                    ->setCellValue('N' . $i, $arContrato->getTipoTiempoRel()->getNombre())
                    ->setCellValue('O' . $i, $arContrato->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('P' . $i, $arContrato->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('Q' . $i, $arContrato->getVrSalario())
                    ->setCellValue('R' . $i, $tipoSalario)
                    ->setCellValue('S' . $i, $arContrato->getCargoRel()->getNombre())
                    ->setCellValue('T' . $i, $arContrato->getCargoDescripcion())
                    ->setCellValue('U' . $i, $arContrato->getClasificacionRiesgoRel()->getNombre())
                    ->setCellValue('V' . $i, $arContrato->getFechaUltimoPago()->Format('Y-m-d'))
                    ->setCellValue('W' . $i, $fechaUltimaPagoPrimas)
                    ->setCellValue('X' . $i, $fechaUltimaPagoCesantias)
                    ->setCellValue('Y' . $i, $fechaUltimaPagoVacaciones)
                    ->setCellValue('Z' . $i, $objFunciones->devuelveBoolean($arContrato->getEstadoTerminado()))
                    ->setCellValue('AA' . $i, $objFunciones->devuelveBoolean($arContrato->getLimitarHoraExtra()));
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('contratos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Contratos.xlsx"');
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