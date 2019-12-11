<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDisciplinarioType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDisciplinarioDescargoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class DisciplinarioController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/rhu/movimiento/disciplinario/", name="brs_rhu_movimiento_disciplinario")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 20, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSelecionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnEliminar')->isClicked()){
                if(count($arrSelecionados) > 0) {
                    try{
                        foreach ($arrSelecionados AS $codigoDisciplinario) {
                            $arDisciplinario = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
                            $arDisciplinario = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find($codigoDisciplinario);
                            if ($arDisciplinario->getEstadoAutorizado() == 0){
                                $em->remove($arDisciplinario);
                            }else{
                                $objMensaje->Mensaje("error", "El proceso número ".$codigoDisciplinario. ", no se puede eliminar, se encuentra autorizado", $this);
                            }   
                        }
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_disciplinario'));
                    } catch (ForeignKeyConstraintViolationException $e) {
                        $objMensaje->Mensaje('error', 'No se puede eliminar el proceso disciplinario, tiene detalles relacionados', $this);
                    }    
                    
                }
            }

            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
        }

        $arDisciplinarios = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Disciplinario:lista.html.twig', array('arDisciplinarios' => $arDisciplinarios, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/disciplinario/nuevo/{codigoDisciplinario}", name="brs_rhu_movimiento_disciplinario_nuevo")
     */    
    public function nuevoAction($codigoDisciplinario = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arDisciplinario = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        if($codigoDisciplinario != 0) {
            $arDisciplinario = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find($codigoDisciplinario);
        } else {
            $arDisciplinario->setFecha(new \DateTime('now'));
            $arDisciplinario->setFechaDesdeSancion(new \DateTime('now'));
            $arDisciplinario->setFechaHastaSancion(new \DateTime('now'));
            $arDisciplinario->setFechaIncidente(new \DateTime('now'));
            $arDisciplinario->setFechaIngresoTrabajo(new \DateTime('now'));
            $arDisciplinario->setFechaNotificacion(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuDisciplinarioType, $arDisciplinario);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arDisciplinario = $form->getData();
            $contratoTerminado = false;
            if ($arDisciplinario->getEstadoCerrado() == 0){
                if($arrControles['form_txtNumeroIdentificacion'] != '') {
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                    if(count($arEmpleado) > 0) {
                        $arDisciplinario->setEmpleadoRel($arEmpleado);
                        if ($arEmpleado->getCodigoContratoActivoFk() != ''){
                            $codigoContrato = $arEmpleado->getCodigoContratoActivoFk();
                        } else {
                            $codigoContrato = $arEmpleado->getCodigoContratoUltimoFk();
                            $contratoTerminado = true;
                        }
                        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
                        if($codigoContrato != '') {
                            if ($contratoTerminado == true){
                                $arDisciplinario->setCentroCostoRel($arContrato->getCentroCostoRel());
                                $arDisciplinario->setCargoRel($arContrato->getCargoRel());
                            } else {
                                $arDisciplinario->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                                $arDisciplinario->setCargoRel($arEmpleado->getCargoRel());
                            }
                            if($codigoDisciplinario == 0) {
                                $arDisciplinario->setCodigoUsuario($arUsuario->getUserName());
                                $arDisciplinario->setContratoRel($arContrato);                            
                            }
                            $em->persist($arDisciplinario);
                            $em->flush();
                            if($form->get('guardarnuevo')->isClicked()) {
                                return $this->redirect($this->generateUrl('brs_rhu_movimiento_disciplinario_nuevo', array('codigoDisciplinario' => 0 )));
                            } else {
                                if ($codigoDisciplinario == 0){
                                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_disciplinario_detalle', array('codigoDisciplinario' => $arDisciplinario->getCodigoDisciplinarioPk())));
                                }else {
                                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_disciplinario'));
                                }

                            }
                        } else {
                            $objMensaje->Mensaje("error", "El empleado no tiene contrato activo", $this);
                        }
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no existe", $this);
                    }
                }
            } else {
                $objMensaje->Mensaje("error", "El proceso ya ha sido cerrado, no se puede editar", $this);
            }   
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Disciplinario:nuevo.html.twig', array(
            'arDisciplinario' => $arDisciplinario,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/disciplinario/detalle/{codigoDisciplinario}", name="brs_rhu_movimiento_disciplinario_detalle")
     */    
    public function detalleAction($codigoDisciplinario) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arrSeleccionados = $request->request->get('ChkSeleccionarDescargo');
        $objMensaje = $this->get('mensajes_brasa');
        $arProcesoDisciplinario = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arProcesoDisciplinario = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find($codigoDisciplinario);
        $form = $this->formularioDetalle($arProcesoDisciplinario);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arProcesoDisciplinario->getEstadoAutorizado() == 0) {
                    $arProcesoDisciplinario->setEstadoAutorizado(1);
                    $em->persist($arProcesoDisciplinario);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_disciplinario_detalle', array('codigoDisciplinario' => $codigoDisciplinario)));                                                
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arProcesoDisciplinario->getEstadoAutorizado() == 1) {
                    $arProcesoDisciplinario->setEstadoAutorizado(0);
                    $em->persist($arProcesoDisciplinario);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_disciplinario_detalle', array('codigoDisciplinario' => $codigoDisciplinario)));                                                
                }
            }
            if($form->get('BtnImprimir')->isClicked()) {
                if($arProcesoDisciplinario->getEstadoAutorizado() == 1) {
                    $codigoProcesoDisciplinarioTipo = $arProcesoDisciplinario->getCodigoDisciplinarioTipoFk();
                    $codigoProcesoDisciplinario = $arProcesoDisciplinario->getCodigoDisciplinarioPk();
                    
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    if ($arConfiguracion->getCodigoFormatoDisciplinario() == 0){
                        $objFormatoProceso = new \Brasa\RecursoHumanoBundle\Formatos\FormatoProcesoDisciplinario();
                        $objFormatoProceso->Generar($this, $codigoProcesoDisciplinarioTipo, $codigoProcesoDisciplinario);
                    }
                    if ($arConfiguracion->getCodigoFormatoDisciplinario() == 1){
                        $objFormatoProceso = new \Brasa\RecursoHumanoBundle\Formatos\FormatoProcesoDisciplinario1teg();
                        $objFormatoProceso->Generar($this, $codigoProcesoDisciplinarioTipo, $codigoProcesoDisciplinario);
                    }
                    if ($arConfiguracion->getCodigoFormatoDisciplinario() == 2){
                        $objFormatoProceso = new \Brasa\RecursoHumanoBundle\Formatos\FormatoProcesoDisciplinarioEstelar();
                        $objFormatoProceso->Generar($this, $codigoProcesoDisciplinarioTipo, $codigoProcesoDisciplinario);
                    }
                                                                               
                }    
            }
            if($form->get('BtnEliminarDescargo')->isClicked()) {
                if($arProcesoDisciplinario->getEstadoAutorizado() == 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->eliminarDescargo($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_disciplinario_detalle', array('codigoDisciplinario' => $codigoDisciplinario)));                                                
                }
            }
            if($form->get('BtnCerrar')->isClicked()) {
                if($arProcesoDisciplinario->getEstadoAutorizado() == 1) {
                    if($arProcesoDisciplinario->getEstadoCerrado() == 0) {
                        if($arProcesoDisciplinario->getEstadoSuspension() == 1) {
                            if($arProcesoDisciplinario->getEstadoProcede() == 1) {
                                $arLicenciaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuLicenciaTipo();
                                $arLicenciaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicenciaTipo')->find(6);
                                $arLicencia = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
                                $arLicencia->setAfectaTransporte(1);
                                $arLicencia->setCentroCostoRel($arProcesoDisciplinario->getCentroCostoRel());
                                $arLicencia->setContratoRel($arProcesoDisciplinario->getContratoRel());
                                $arLicencia->setEmpleadoRel($arProcesoDisciplinario->getEmpleadoRel());
                                $arLicencia->setFecha(new \DateTime('now'));
                                $arLicencia->setFechaDesde($arProcesoDisciplinario->getFechaDesdeSancion());
                                $arLicencia->setFechaHasta($arProcesoDisciplinario->getFechaHastaSancion());
                                $arLicencia->setCodigoUsuario($arProcesoDisciplinario->getCodigoUsuario());
                                $arLicencia->setLicenciaTipoRel($arLicenciaTipo);
                                $intDias = $arLicencia->getFechaDesde()->diff($arLicencia->getFechaHasta());
                                $intDias = $intDias->format('%a');
                                $intDias = $intDias + 1;
                                $arLicencia->setCantidad($intDias);
                                $em->persist($arLicencia);                            
                            }                            
                        }
                        $arProcesoDisciplinario->setEstadoCerrado(1);
                        $em->persist($arProcesoDisciplinario);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_disciplinario_detalle', array('codigoDisciplinario' => $codigoDisciplinario)));                                                
                } else {
                    $objMensaje = "Debe estar autorizado";
                }    
            }
            if($request->request->get('OpImprimir')) {
                $codigoDescargo = $request->request->get('OpImprimir');
                
                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                if ($arConfiguracion->getCodigoFormatoDescargo() == 0){
                    $objFormatoDescargo = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDescargo();
                    $objFormatoDescargo->Generar($this, $codigoDescargo);
                }
                if ($arConfiguracion->getCodigoFormatoDescargo() == 1){
                    $objFormatoDescargo = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDescargo();
                    $objFormatoDescargo->Generar($this, $codigoDescargo);
                }
                if ($arConfiguracion->getCodigoFormatoDescargo() == 2){
                    $objFormatoDescargo = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDescargoEstelar();
                    $objFormatoDescargo->Generar($this, $codigoDescargo);
                }
                
                         
            }
        }
        $arDisciplinario = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find($codigoDisciplinario);
        $arDescargos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioDescargo')->findBy(array('codigoDisciplinarioFk' => $codigoDisciplinario));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Disciplinario:detalle.html.twig', array(
                    'arDisciplinario' => $arDisciplinario,
                    'arDescargos' => $arDescargos,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/movimiento/disciplinario/descargo/nuevo/{codigoDisciplinario}/{codigoDisciplinarioDescargo}", name="brs_rhu_movimiento_disciplinario_descargo_nuevo")
     */ 
    
    public function nuevoDescargoAction($codigoDisciplinario, $codigoDisciplinarioDescargo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arDisciplinario = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arDisciplinario = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find($codigoDisciplinario);
        $arDisciplinarioDescargo = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioDescargo();
        if($codigoDisciplinarioDescargo != 0) {
            $arDisciplinarioDescargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioDescargo')->find($codigoDisciplinarioDescargo);
        }
        $form = $this->createForm(new RhuDisciplinarioDescargoType(), $arDisciplinarioDescargo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arDisciplinarioDescargo = $form->getData();
            $arDisciplinarioDescargo->setDisciplinarioRel($arDisciplinario);
            $arrControles = $request->request->All();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    if ($arEmpleado->getCodigoContratoActivoFk() != ''){
                        $codigoContrato = $arEmpleado->getCodigoContratoActivoFk();
                    } else {
                        $codigoContrato = $arEmpleado->getCodigoContratoUltimoFk();
                    }
                    if($codigoContrato != '') {
                        $arDisciplinarioDescargo->setEmpleadoRel($arEmpleado);
                        $em->persist($arDisciplinarioDescargo);
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo", $this);
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Disciplinario:nuevoDescargo.html.twig', array(
            'arDisciplinarioDescargo' => $arDisciplinarioDescargo,
            'form' => $form->createView()
            ));
    }    
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strListaDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->listaDQL(
                $session->get('filtroIdentificacion'),
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroCodigoZona'),
                $session->get('filtroCodigoOperacion'),
                $session->get('filtroEstadoCerrado'),
                $session->get('filtroEstadoProcede'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta')
                );
    }

    private function formularioFiltro() {
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
        $arrayPropiedadesZona = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuZona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoZona')) {
            $arrayPropiedadesZona['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuZona", $session->get('filtroCodigoZona'));
        }
        $arrayPropiedadesOperacion = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSubzona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoOperacion')) {
            $arrayPropiedadesOperacion['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuSubzona", $session->get('filtroCodigoOperacion'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('zonaRel', 'entity', $arrayPropiedadesZona)
            ->add('operacionRel', 'entity', $arrayPropiedadesOperacion)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('estadoCerrado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'),'data' => $session->get('filtroEstadoCerrado')))                                        
            ->add('estadoProcede', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'),'data' => $session->get('filtroEstadoProcede')))                                        
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroCodigoZona', $controles['zonaRel']);
        $session->set('filtroCodigoOperacion', $controles['operacionRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroEstadoCerrado', $form->get('estadoCerrado')->getData());
        $session->set('filtroEstadoProcede', $form->get('estadoProcede')->getData());
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
    
    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonCerrar = array('label' => 'Cerrar', 'disabled' => false);        
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);               
        $arrBotonEliminarDescargo = array('label' => 'Eliminar descargo', 'disabled' => false);               
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonEliminarDescargo['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
            $arrBotonCerrar['disabled'] = true;            
        }
        if($ar->getEstadoCerrado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonCerrar['disabled'] = true;            
        }
        
        $form = $this->createFormBuilder()    
            ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
            ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)
            ->add('BtnCerrar', 'submit', $arrBotonCerrar)            
            ->add('BtnImprimir', 'submit', $arrBotonImprimir)                                            
            ->add('BtnEliminarDescargo', 'submit', $arrBotonEliminarDescargo)                                            
            ->getForm();  
        return $form;
    }

    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        ob_clean();
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
                for($col = 'A'; $col !== 'AR'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
                }
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'CENTRO COSTOS')
                            ->setCellValue('C1', 'IDENTIFICACIÓN')
                            ->setCellValue('D1', 'EMPLEADO')
                            ->setCellValue('E1', 'CARGO')
                            ->setCellValue('F1', 'PUESTO')
                            ->setCellValue('G1', 'OPERACION')
                            ->setCellValue('H1', 'ZONA')
                            ->setCellValue('I1', 'PROCESO')
                            ->setCellValue('J1', 'CAUSAL O MOTIVO')
                            ->setCellValue('K1', 'FECHA DEL INCIDENTE')
                            ->setCellValue('L1', 'FECHA PROCESO INICIO')
                            ->setCellValue('M1', 'FECHA PROCESO HASTA')
                            ->setCellValue('N1', 'DÍAS SANCIÓN')
                            ->setCellValue('O1', 'FECHA INGRESO TRABAJO')
                            ->setCellValue('P1', 'REENTRENAMIENTO')
                            ->setCellValue('Q1', 'AUTORIZADO')
                            ->setCellValue('R1', 'PROCEDE')
                            ->setCellValue('S1', 'CERRADO')
                            ->setCellValue('T1', 'OBSERVACIONES')
                            ->setCellValue('U1', 'USUARIO');

                $i = 2;
                $query = $em->createQuery($this->strListaDql);
                $arDisciplinarios = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
                $arDisciplinarios = $query->getResult();

                foreach ($arDisciplinarios as $arDisciplinario) {
                if ($arDisciplinario->getAsunto() == Null){
                $asunto = "NO APLICA";
                } else {
                    $asunto = $arDisciplinario->getAsunto();
                }
                
                if ($arDisciplinario->getEstadoAutorizado() == 1){
                    $autorizado = "SI";
                } else {
                    $autorizado = "NO";
                }
                if ($arDisciplinario->getReentrenamiento() == 1){
                    $reentrenamiento = "SI";
                } else {
                    $reentrenamiento = "NO";
                }
                if ($arDisciplinario->getEstadoCerrado() == 1){
                    $estadoCerrado = "SI";
                } else {
                    $estadoCerrado = "NO";
                }
                if ($arDisciplinario->getEstadoProcede() == 1){
                    $estadoProcede = "SI";
                } else {
                    $estadoProcede = "NO";
                }
                $zona = '';
                if ($arDisciplinario->getEmpleadoRel()->getCodigoZonaFk() != null){
                    $zona = $arDisciplinario->getEmpleadoRel()->getZonaRel()->getNombre();
                }
                $operacion = '';
                if ($arDisciplinario->getEmpleadoRel()->getCodigoSubzonaFk() != null){
                    $operacion = $arDisciplinario->getEmpleadoRel()->getSubzonaRel()->getNombre();
                }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arDisciplinario->getCodigoDisciplinarioPk())
                            ->setCellValue('B' . $i, $arDisciplinario->getCentroCostoRel()->getNombre())
                            ->setCellValue('C' . $i, $arDisciplinario->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arDisciplinario->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('E' . $i, $arDisciplinario->getCargoRel()->getNombre())
                            ->setCellValue('F' . $i, $arDisciplinario->getPuesto())
                            ->setCellValue('G' . $i, $zona)
                            ->setCellValue('H' . $i, $operacion)
                            ->setCellValue('I' . $i, $arDisciplinario->getDisciplinarioTipoRel()->getNombre())
                            ->setCellValue('J' . $i, $asunto)
                            ->setCellValue('K' . $i, $arDisciplinario->getFechaIncidente())
                            ->setCellValue('L' . $i, $arDisciplinario->getFechaDesdeSancion())
                            ->setCellValue('M' . $i, $arDisciplinario->getFechaHastaSancion())
                            ->setCellValue('N' . $i, $arDisciplinario->getDiasSuspencion())
                            ->setCellValue('O' . $i, $arDisciplinario->getFechaIngresoTrabajo())
                            ->setCellValue('P' . $i, $reentrenamiento)
                            ->setCellValue('Q' . $i, $autorizado)
                            ->setCellValue('R' . $i, $estadoProcede)
                            ->setCellValue('S' . $i, $estadoCerrado)
                            ->setCellValue('T' . $i, $arDisciplinario->getComentarios())
                            ->setCellValue('U' . $i, $arDisciplinario->getCodigoUsuario());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('ProcesosDisciplinarios');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="ProcesosDisciplinarios.xlsx"');
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
