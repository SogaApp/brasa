<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\RecursoHumanoBundle\Form\Type\RhuLiquidacionType;
class LiquidacionController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/movimiento/liquidacion/", name="brs_rhu_movimiento_liquidacion")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 9, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {            
            if($form->get('BtnLiquidar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
                        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigo);
                        if($arLiquidacion->getEstadoPagoGenerado() == 0) {
                            $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($codigo);    
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion'));
                }
            }
            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->formularioLista();
                $this->listar();
            }
            
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->formularioLista();
                $this->listar();
                $this->generarExcel();
            }
        }
        
        $arLiquidaciones = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:lista.html.twig', array('arLiquidaciones' => $arLiquidaciones, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/liquidacion/nuevo/{codigoLiquidacion}", name="brs_rhu_movimiento_liquidacion_nuevo")
     */     
    public function nuevoAction($codigoLiquidacion = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();        
        if($codigoLiquidacion != 0) {
            $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        }
        $form = $this->createForm(new RhuLiquidacionType, $arLiquidacion);         
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arrControles = $request->request->All();
            
            if($form->get('guardar')->isClicked()) {
                $arLiquidacion = $form->getData();
                $em->persist($arLiquidacion);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));                                                                                               
            }
        }                    
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:nuevo.html.twig', array(
            'arLiquidacion' => $arLiquidacion,            
            'form' => $form->createView()));
    }     
    
    /**
     * @Route("/rhu/movimiento/liquidacion/detalle/{codigoLiquidacion}", name="brs_rhu_movimiento_liquidacion_detalle")
     */    
    public function detalleAction($codigoLiquidacion) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        $form = $this->formularioDetalle($arLiquidacion);
        $form->handleRequest($request);

        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                if($arLiquidacion->getEstadoGenerado() == 1) {
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $codigoFormato = $arConfiguracion->getCodigoFormatoLiquidacion();
                    if($codigoFormato <= 1) {
                        $objFormatoLiquidacion = new \Brasa\RecursoHumanoBundle\Formatos\Liquidacion1();
                        $objFormatoLiquidacion->Generar($this, $codigoLiquidacion);                                          
                    }
                    if($codigoFormato == 2) {
                        $objFormatoLiquidacion = new \Brasa\RecursoHumanoBundle\Formatos\Liquidacion2();
                        $objFormatoLiquidacion->Generar($this, $codigoLiquidacion);                                     
                }                                       
                }
            }
            if($form->get('BtnAutorizar')->isClicked()) {
                if ($arLiquidacion->getEstadoAutorizado() == 0){
                    $arDotacionPendiente = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->dotacionDevolucion($arLiquidacion->getCodigoEmpleadoFk());
                    $registrosDotacionesPendientes = count($arDotacionPendiente);
                    if ($registrosDotacionesPendientes > 0){
                        $objMensaje->Mensaje("error", "El empleado tiene dotaciones pendientes por entregar, no se puede autorizar la liquidación", $this);
                    }else{
                        if ($arLiquidacion->getEstadoGenerado() == 0){
                            $objMensaje->Mensaje("error", "La liquidacion debe ser liquidada antes de autorizar", $this);
                        } else {
                            $arLiquidacion->setEstadoAutorizado(1);
                            $em->persist($arLiquidacion);
                            $em->flush();
                            return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                        }
                    }                    
                }
                    
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if ($arLiquidacion->getEstadoAutorizado() == 1){
                    $arLiquidacion->setEstadoAutorizado(0);
                    $em->persist($arLiquidacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                }    
            }            
            if($form->get('BtnLiquidar')->isClicked()) {
                if($arLiquidacion->getEstadoAutorizado() == 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($codigoLiquidacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                } else {
                    $objMensaje->Mensaje("error", "No puede reliquidar una liquidacion autorizada", $this);
                }

            }
            
            if($form->get('BtnGenerarPago')->isClicked()) {            
                if($arLiquidacion->getEstadoAutorizado() == 1) {
                    
                    $validar = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->pagar($codigoLiquidacion);
                    if ($validar == ''){
                        $arLiquidacion->setEstadoPagoGenerado(1);
                        $em->persist($arLiquidacion);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                    } else {
                        $objMensaje->Mensaje("error", "Hay saldos en creditos que son inferiores a la deducciones", $this);
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                    }                    
                } else {
                    $objMensaje->Mensaje("error", "No esta autorizado, no se puede generar pago", $this);
                }
            }
            
            if($form->get('BtnEliminarAdicional')->isClicked()) {
                if ($arLiquidacion->getEstadoAutorizado() == 0){
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoLiquidacionAdicional) {
                            $arLiquidacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
                            $arLiquidacionAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->find($codigoLiquidacionAdicional);
                            $em->remove($arLiquidacionAdicional);
                        }
                        $em->flush();                        
                    }
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($codigoLiquidacion);
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                }    
            }
        }
        $arLiquidacionAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
        $arLiquidacionAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->FindBy(array('codigoLiquidacionFk' => $codigoLiquidacion));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:detalle.html.twig', array(
                    'arLiquidacion' => $arLiquidacion,
                    'arLiquidacionAdicionales' => $arLiquidacionAdicionales,
                    'form' => $form->createView()
                    ));
    }    

    /**
     * @Route("/rhu/movimiento/liquidacion/adicional/{codigoLiquidacion}/{codigoLiquidacionAdicional}/{tipo}/", name="brs_rhu_movimiento_liquidacion_adicional")
     */     
    public function detalleAdicionalAction($codigoLiquidacion, $codigoLiquidacionAdicional, $tipo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        $arLiquidacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
        $valor = 0;
        $arrayPropiedadesPagoConcepto = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) use($tipo) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', $tipo)
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true,
                'data' => ""
            );
        if($codigoLiquidacionAdicional != 0) {            
            $arLiquidacionAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->find($codigoLiquidacionAdicional);
            if($tipo == 1) {
                $valor = $arLiquidacionAdicional->getVrBonificacion();
            }
            if($tipo == 2) {
                $valor = $arLiquidacionAdicional->getVrDeduccion();
            }        
            $arrayPropiedadesPagoConcepto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arLiquidacionAdicional->getCodigoPagoConceptoFk());            
        }
        
        $form = $this->createFormBuilder()
            ->add('pagoConceptoRel', 'entity', $arrayPropiedadesPagoConcepto) 
            ->add('TxtValor', 'number', array('required' => true, 'data' => $valor))                             
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) { 
            if ($form->get('BtnGuardar')->isClicked()) {
                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                $arPagoConcepto = $form->get('pagoConceptoRel')->getData();                                
                $arLiquidacionAdicional->setLiquidacionRel($arLiquidacion); 
                $arLiquidacionAdicional->setPagoConceptoRel($arPagoConcepto);
                if($tipo == 1) {
                    $arLiquidacionAdicional->setVrBonificacion($form->get('TxtValor')->getData());  
                } 
                if($tipo == 2) {
                    $arLiquidacionAdicional->setVrDeduccion($form->get('TxtValor')->getData());  
                }                                        
                $em->persist($arLiquidacionAdicional);                
                $em->flush();                        
                $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($codigoLiquidacion);   
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                
            }                        
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:detalleAdicionalNuevo.html.twig', array(
            'arLiquidacion' => $arLiquidacion,
            'form' => $form->createView()));
    }           
    
    /**
     * @Route("/rhu/movimiento/liquidacion/detalle/credito/{codigoLiquidacion}", name="brs_rhu_movimiento_liquidacion_detalle_credito")
     */     
    public function detalleCreditoAction($codigoLiquidacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->pendientes($arLiquidacion->getCodigoEmpleadoFk());
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
                        $arLiquidacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
                        $arLiquidacionAdicional->setLiquidacionRel($arLiquidacion);
                        $arLiquidacionAdicional->setVrDeduccion($valor);
                        $arLiquidacionAdicional->setPagoConceptoRel($arCredito->getCreditoTipoRel()->getPagoConceptoRel());                                               
                        $arLiquidacionAdicional->setCreditoRel($arCredito);
                        $em->persist($arLiquidacionAdicional);                                                 
                        $floVrDeducciones += $valor;
                    }                                                       
                    $em->flush();                        
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($arLiquidacion->getCodigoLiquidacionPk());                    
                }
                
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:detalleCreditoNuevo.html.twig', array(
            'arCreditos' => $arCreditos,
            'arLiquidacion' => $arLiquidacion,
            'form' => $form->createView()));
    }     
    
    /**
     * @Route("/rhu/movimiento/liquidacion/parametros/{codigoLiquidacion}", name="brs_rhu_movimiento_liquidacion_parametros")
     */    
    public function parametrosAction($codigoLiquidacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_movimiento_liquidacion_parametros', array('codigoLiquidacion' => $codigoLiquidacion)))            
            ->add('porcentajeIbp', 'number', array('data' =>$arLiquidacion->getPorcentajeIbp() ,'required' => false))      
            ->add('liquidarSalario', 'checkbox', array('required'  => false, 'data' =>$arLiquidacion->getLiquidarSalario()))
            ->add('vrIndemnizacion', 'number', array('data' =>$arLiquidacion->getVrIndemnizacion() ,'required' => false))                      
            ->add('diasAusentismoAdicional', 'number', array('data' =>$arLiquidacion->getDiasAusentismoAdicional() ,'required' => false))                      
            ->add('diasAusentismoPropuesto', 'number', array('data' =>$arLiquidacion->getDiasAusentismoPropuesto() ,'required' => false))                                      
            ->add('vrSalarioVacacionPropuesto', 'number', array('data' =>$arLiquidacion->getVrSalarioVacacionPropuesto() ,'required' => false))                      
            ->add('vrSalarioPrimaPropuesto', 'number', array('data' =>$arLiquidacion->getVrSalarioPrimaPropuesto() ,'required' => false))                                      
            ->add('vrSalarioCesantiasPropuesto', 'number', array('data' =>$arLiquidacion->getVrSalarioCesantiasPropuesto() ,'required' => false))                                      
            ->add('eliminarAusentismo', 'checkbox', array('required'  => false, 'data' =>$arLiquidacion->getEliminarAusentismo()))                
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();            
            $porcentajeIbp = $form->get('porcentajeIbp')->getData();
            $vrIndemnizacion = $form->get('vrIndemnizacion')->getData();
            $diasAusentismoAdicional = $form->get('diasAusentismoAdicional')->getData();
            $diasAusentismoPropuesto = $form->get('diasAusentismoPropuesto')->getData();
            $liquidarSalario = $form->get('liquidarSalario')->getData();
            $vrSalarioVacacionPropuesto = $form->get('vrSalarioVacacionPropuesto')->getData();
            $vrSalarioPrimaPropuesto = $form->get('vrSalarioPrimaPropuesto')->getData();
            $vrSalarioCesantiasPropuesto = $form->get('vrSalarioCesantiasPropuesto')->getData();
            $eliminarAusentismo = $form->get('eliminarAusentismo')->getData();
            $arLiquidacion->setPorcentajeIbp($porcentajeIbp);
            $arLiquidacion->setLiquidarSalario($liquidarSalario);
            $arLiquidacion->setVrIndemnizacion($vrIndemnizacion);
            $arLiquidacion->setDiasAusentismoAdicional($diasAusentismoAdicional);
            $arLiquidacion->setDiasAusentismoPropuesto($diasAusentismoPropuesto);
            $arLiquidacion->setVrSalarioVacacionPropuesto($vrSalarioVacacionPropuesto);
            $arLiquidacion->setVrSalarioPrimaPropuesto($vrSalarioPrimaPropuesto);
            $arLiquidacion->setVrSalarioCesantiasPropuesto($vrSalarioCesantiasPropuesto);
            $arLiquidacion->setEliminarAusentismo($eliminarAusentismo);
            $em->persist($arLiquidacion);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:parametros.html.twig', array(
            'arLiquidacion' => $arLiquidacion,
            'form' => $form->createView()           
        ));
    }    
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->listaDql(
               $session->get('filtroIdentificacion'),
               $session->get('filtroGenerado'),
               $session->get('filtroCodigoCentroCosto'),
               $session->get('filtroPagado'));
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
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedadesCentroCosto)    
            ->add('txtNumeroIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('txtNombreCorto', 'text', array('label'  => 'Nombre','data' => $strNombreEmpleado))
            ->add('estadoGenerado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'),'data' => $session->get('filtroGenerado')))
            ->add('estadoPagado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'),'data' => $session->get('filtroPagado')))
            ->add('BtnLiquidar', 'submit', array('label'  => 'Liquidar'))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonEliminarAdicional = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonLiquidar = array('label' => 'Liquidar', 'disabled' => false);
        $arrBotonGenerarPago = array('label' => 'Generar pago', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonEliminarAdicional['disabled'] = true;
            $arrBotonLiquidar['disabled'] = true;
        } else {            
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonGenerarPago['disabled'] = true;
        }
        if($ar->getEstadoPagoGenerado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonGenerarPago['disabled'] = true;
            $arrBotonLiquidar['disabled'] = true;
        }
        $form = $this->createFormBuilder()    
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)            
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnLiquidar', 'submit', $arrBotonLiquidar)
                    ->add('BtnEliminarAdicional', 'submit', $arrBotonEliminarAdicional)
                    ->add('BtnGenerarPago', 'submit', $arrBotonGenerarPago)
                    ->getForm();  
        return $form;
    }        
    
    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroGenerado', $controles['estadoGenerado']);
        $session->set('filtroPagado', $controles['estadoPagado']);
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
    }

    private function generarExcel() {
        ob_clean();
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
        for($col = 'A'; $col !== 'AZ'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        for($col = 'J'; $col !== 'S'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        for($col = 'W'; $col !== 'Z'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'NUMERO')
                    ->setCellValue('B1', 'CODIGO')
                    ->setCellValue('C1', 'DOCUMENTO')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'CENTRO COSTO')
                    ->setCellValue('F1', 'CONTRATO')
                    ->setCellValue('G1', 'DESDE')
                    ->setCellValue('H1', 'HASTA')
                    ->setCellValue('I1', 'AUX.TTE')
                    ->setCellValue('J1', 'CESANTIAS')
                    ->setCellValue('K1', 'INTERESES')
                    ->setCellValue('L1', 'PRIMA')
                    ->setCellValue('M1', 'DED.PRIMA')
                    ->setCellValue('N1', 'VACACIONES')
                    ->setCellValue('O1', 'INDEMNIZACION')
                    ->setCellValue('P1', 'D.CES')
                    ->setCellValue('Q1', 'D.VAC')
                    ->setCellValue('R1', 'D.PRI')
                    ->setCellValue('S1', 'F.ULT.PAGO')
                    ->setCellValue('T1', 'F.ULT.PAGO.PRI')
                    ->setCellValue('U1', 'F.ULT.PAGO.VAC')
                    ->setCellValue('V1', 'F.ULT.PAGO.CES')
                    ->setCellValue('W1', 'DEDUCCIONES')
                    ->setCellValue('X1', 'BONIFICACIONES')                
                    ->setCellValue('Y1', 'TOTAL');    
        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arLiquidaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidaciones = $query->getResult();
        foreach ($arLiquidaciones as $arLiquidacion) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arLiquidacion->getCodigoLiquidacionPk())
                    ->setCellValue('B' . $i, $arLiquidacion->getCodigoEmpleadoFk())
                    ->setCellValue('C' . $i, $arLiquidacion->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arLiquidacion->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arLiquidacion->getCentroCostoRel()->getNombre())
                    ->setCellValue('F' . $i, $arLiquidacion->getCodigoContratoFk())
                    ->setCellValue('G' . $i, $arLiquidacion->getFechaDesde()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arLiquidacion->getFechaHasta()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arLiquidacion->getVrAuxilioTransporte())
                    ->setCellValue('J' . $i, $arLiquidacion->getVrCesantias())
                    ->setCellValue('K' . $i, $arLiquidacion->getVrInteresesCesantias())
                    ->setCellValue('L' . $i, $arLiquidacion->getVrPrima())
                    ->setCellValue('M' . $i, $arLiquidacion->getVrDeduccionPrima())
                    ->setCellValue('N' . $i, $arLiquidacion->getVrVacaciones())
                    ->setCellValue('O' . $i, $arLiquidacion->getVrIndemnizacion())
                    ->setCellValue('P' . $i, $arLiquidacion->getDiasCesantias())
                    ->setCellValue('Q' . $i, $arLiquidacion->getDiasVacaciones())
                    ->setCellValue('R' . $i, $arLiquidacion->getDiasPrimas())                                        
                    ->setCellValue('W' . $i, $arLiquidacion->getVrDeducciones())
                    ->setCellValue('X' . $i, $arLiquidacion->getVrBonificaciones())
                    ->setCellValue('Y' . $i, $arLiquidacion->getVrTotal());
            if($arLiquidacion->getFechaUltimoPago()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S' . $i, $arLiquidacion->getFechaUltimoPago()->format('Y-m-d'));
            }
            if($arLiquidacion->getFechaUltimoPagoPrimas()) {
                 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T' . $i, $arLiquidacion->getFechaUltimoPagoPrimas()->format('Y-m-d'));
            }
            if($arLiquidacion->getFechaUltimoPagoVacaciones()) {
                 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U' . $i, $arLiquidacion->getFechaUltimoPagoVacaciones()->format('Y-m-d'));
            }
            if($arLiquidacion->getFechaUltimoPagoCesantias()) {
                 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V' . $i, $arLiquidacion->getFechaUltimoPagoCesantias()->format('Y-m-d'));
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Liquidaciones');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Liquidaciones.xlsx"');
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
