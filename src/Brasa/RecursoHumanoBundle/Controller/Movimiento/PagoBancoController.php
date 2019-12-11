<?php
namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPagoBancoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class PagoBancoController extends Controller
{
    var $strSqlLista = "";
    var $strFecha = "";
    var $dqlListaDetalle = "";
    
    /**
     * @Route("/rhu/movimiento/pago/banco/", name="brs_rhu_movimiento_pago_banco")
     */    
    public function listaAction() {        
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 8, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $strSqlLista = $this->getRequest()->getSession();        
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->listar();          
        if ($form->isValid()) {            
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if ($form->get('BtnEliminar')->isClicked()) {  
                if(count($arrSeleccionados) > 0) {
                    try{
                        foreach ($arrSeleccionados as $codigoPagoBanco) {
                            $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);
                            $em->remove($arPagoBanco);                        
                        }
                        $em->flush();
                    } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el registro, tiene detalles asociados', $this);
                  }    
                }                
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_pago_banco'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {    
                $this->filtrar($form);
                $this->listar();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }            
        }                      
        $arPagoBancos = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoBanco/:lista.html.twig', array('arPagoBancos' => $arPagoBancos, 'form' => $form->createView()));
    } 
        
    /**
     * @Route("/rhu/movimiento/pago/banco/nuevo/{codigoPagoBanco}", name="brs_rhu_movimiento_pago_banco_nuevo")
     */    
    public function nuevoAction($codigoPagoBanco) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        if($codigoPagoBanco != 0) {
            $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);
            $arPagoBanco->getFechaAplicacion();
            $arPagoBanco->getFechaTrasmision();
        } else {
            $arPagoBanco->setFecha(new \DateTime('now'));
            $arPagoBanco->setFechaAplicacion(new \DateTime('now'));
            $arPagoBanco->setFechaTrasmision(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuPagoBancoType, $arPagoBanco);
        $form->handleRequest($request);
        if ($form->isValid()) {      
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arPagoBanco = $form->getData();
            $arPagoBanco->setCodigoUsuario($arUsuario->getUsername());
            $em->persist($arPagoBanco);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_pago_banco_nuevo', array('codigoPagoBanco' => 0)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoBanco:nuevo.html.twig', array(
            'arPagoBanco' => $arPagoBanco,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/movimiento/pago/banco/detalle/{codigoPagoBanco}", name="brs_rhu_movimiento_pago_banco_detalle")
     */      
    public function detalleAction($codigoPagoBanco) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $request = $this->getRequest();            
        $arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);                
        $form = $this->formularioDetalle($arPagoBanco);
        $form->handleRequest($request);
        if($form->isValid()) {            
            if($form->get('BtnAutorizar')->isClicked()) {
                if ($arPagoBanco->getEstadoAutorizado() == 0){
                    $arPagoBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array('codigoPagoBancoFk' => $codigoPagoBanco));
                    if ($arPagoBancoDetalle != null){
                        $arPagoBanco->setEstadoAutorizado(1);
                        $em->persist($arPagoBanco);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_pago_banco_detalle', array('codigoPagoBanco' => $codigoPagoBanco)));           
                    } else {
                        $objMensaje->Mensaje("error", "No hay detalles para los pagos al banco, no se puede autorizar", $this);
                    }    
                }
            }            
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if ($arPagoBanco->getEstadoAutorizado() == 1 && $arPagoBanco->getEstadoImpreso() == 0){
                    $arPagoBanco->setEstadoAutorizado(0);
                    $em->persist($arPagoBanco);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_pago_banco_detalle', array('codigoPagoBanco' => $codigoPagoBanco)));           
                } else {
                    $objMensaje->Mensaje("error", "La pago banco debe estar autorizado y no puede estar impreso", $this);
                }    
            }                        
            if($form->get('BtnImprimir')->isClicked()) {
                if ($arPagoBanco->getEstadoAutorizado() == 1){
                    $objFormatoPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPagoBanco();
                    $objFormatoPagoBancoDetalle->Generar($this, $codigoPagoBanco);
                    $arPagoBanco->setEstadoImpreso(1);
                    $em->persist($arPagoBanco);
                    $em->flush();
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir el archivo sin estar autorizada", $this);
                }   
            }
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                if ($arPagoBanco->getEstadoAutorizado() == 0){
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados as $codigoPagoBancoDetalle) {
                            $arPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                            $arPagoBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->find($codigoPagoBancoDetalle);
                            if($arPagoBancoDetalle->getCodigoPagoFk()) {
                                $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                                $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($arPagoBancoDetalle->getCodigoPagoFk());
                                $arPago->setEstadoPagadoBanco(0);
                                $em->persist($arPago);                                
                            }
                            if($arPagoBancoDetalle->getCodigoVacacionFk()) {
                                $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                                $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($arPagoBancoDetalle->getCodigoVacacionFk());
                                $arVacacion->setEstadoPagoBanco(0);
                                $em->persist($arVacacion);                                
                            }                            
                            $em->remove($arPagoBancoDetalle);
                        }
                        $em->flush();
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->liquidar($codigoPagoBanco);
                    } 
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_pago_banco_detalle', array('codigoPagoBanco' => $codigoPagoBanco)));           
                }    
            }
            if($form->get('BtnArchivoBancolombiaPab')->isClicked()) {
                if($arPagoBanco->getEstadoAutorizado() == 1) {
                    $this->generarArchivoBancolombiaPab($arPagoBanco);
                } else {
                    $objMensaje->Mensaje('error', 'El pago al banco debe estar autorizado', $this);
                }
            }
            if($form->get('BtnArchivoBancolombiaSap')->isClicked()) {
                if($arPagoBanco->getEstadoAutorizado() == 1) {
                    $this->generarArchivoBancolombiaSap($arPagoBanco);
                } else {
                    $objMensaje->Mensaje('error', 'El pago al banco debe estar autorizado', $this);
                }
            }
            if($form->get('BtnArchivoAvvillasInterno')->isClicked()) {
                if($arPagoBanco->getEstadoAutorizado() == 1) {
                    $this->generarArchivoAvvillasInterno($arPagoBanco);
                } else {
                    $objMensaje->Mensaje('error', 'El pago al banco debe estar autorizado', $this);
                }
            }
            if($form->get('BtnArchivoAvvillasOtros')->isClicked()) {
                if($arPagoBanco->getEstadoAutorizado() == 1) {
                    $this->generarArchivoAvvillasOtros($arPagoBanco);
                } else {
                    $objMensaje->Mensaje('error', 'El pago al banco debe estar autorizado ', $this);
                }
            }
            if($form->get('BtnArchivoDavivienda')->isClicked()) {
                if($arPagoBanco->getEstadoAutorizado() == 1) {
                    $this->generarArchivoDavivienda($arPagoBanco);
                } else {
                    $objMensaje->Mensaje('error', 'El pago al banco debe estar autorizado ', $this);
                }
            }
            if($form->get('BtnArchivoBogota')->isClicked()) {
                if($arPagoBanco->getEstadoAutorizado() == 1) {
                    $this->generarArchivoBogota($arPagoBanco);
                } else {
                    $objMensaje->Mensaje('error', 'El pago al banco debe estar autorizado ', $this);
                }
            }
            if($form->get('BtnArchivoColpatriaCsv')->isClicked()) {
                if($arPagoBanco->getEstadoAutorizado() == 1) {
                    $this->generarArchivoColpatriaCsv($arPagoBanco);
                } else {
                    $objMensaje->Mensaje('error', 'El pago al banco debe estar autorizado ', $this);
                }
            }
            if ($form->get('BtnDetalleExcel')->isClicked()) {                
                $this->generarDetalleExcel($codigoPagoBanco);
            }
            
        }        
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->listaDetalleDql($codigoPagoBanco);
        $arPagoBancoDetalle = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 700);                
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoBanco:detalle.html.twig', array(
                    'arPagoBanco' => $arPagoBanco,        
                    'arPagoBancoDetalle' => $arPagoBancoDetalle,
                    'form' => $form->createView()
                    ));
    }
    
    /**
     * @Route("/rhu/movimiento/pago/banco/detalle/nuevo/{codigoPagoBanco}", name="brs_rhu_movimiento_pago_banco_detalle_nuevo")
     */    
    public function detalleNuevoAction($codigoPagoBanco) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();        
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);
        $arProgramacionesPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionesPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->findBy(array('estadoPagado' => 1, 'estadoPagadoBanco' => 0));        
        
        $arrayPropiedadesBanco = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuBanco',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                    ->orderBy('b.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroRhuCodigoBanco')) {
            $arrayPropiedadesBanco['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuBanco", $session->get('filtroRhuCodigoBanco'));
        }        
        $form = $this->createFormBuilder()
            ->add('bancoRel', 'entity', $arrayPropiedadesBanco)                
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar',))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        $form->handleRequest($request); 
        $this->listarDetalle();
        if ($form->isValid()) {            
                if ($form->get('BtnGuardar')->isClicked()) {
                    if ($arPagoBanco->getEstadoAutorizado() == 0){
                        $arrSeleccionados = $request->request->get('ChkSeleccionarProgramacion');
                        if(count($arrSeleccionados) > 0) {
                            foreach ($arrSeleccionados AS $codigoProgramacionPago) {                           
                                $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                                $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);                                
                                if($arProgramacionPago->getEstadoPagadoBanco() == 0) {
                                    $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                                    $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago, 'estadoPagadoBanco' => 0));
                                    foreach ($arPagos as $arPago) {
                                        if($arPago->getEstadoPagadoBanco() == 0) {
                                            $arPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                                            $arPagoBancoDetalle->setPagoBancoRel($arPagoBanco);
                                            $arPagoBancoDetalle->setPagoRel($arPago);
                                            $arPagoBancoDetalle->setCuenta($arPago->getEmpleadoRel()->getCuenta());
                                            $valorPagar = round($arPago->getVrNeto());
                                            $arPagoBancoDetalle->setVrPago($valorPagar);
                                            $arPagoBancoDetalle->setBancoRel($arPago->getEmpleadoRel()->getBancoRel());                                        
                                            $arPagoBancoDetalle->setEmpleadoRel($arPago->getEmpleadoRel());
                                            $em->persist($arPagoBancoDetalle); 
                                            $arPago->setEstadoPagadoBanco(1);
                                            $em->persist($arPago);                            
                                        }
                                    }                            
                                }
                                $arProgramacionPago->setEstadoPagadoBanco(1);
                                $em->persist($arProgramacionPago);
                            }
                            $em->flush();
                        }                

                        $arrSeleccionados = $request->request->get('ChkSeleccionar');
                        if(count($arrSeleccionados) > 0) {
                            foreach ($arrSeleccionados AS $codigoPago) {   
                                $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                                $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);
                                if($arPago->getEstadoPagadoBanco() == 0) {
                                    $arPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                                    $arPagoBancoDetalle->setPagoBancoRel($arPagoBanco);
                                    $arPagoBancoDetalle->setPagoRel($arPago);
                                    $arPagoBancoDetalle->setCuenta($arPago->getEmpleadoRel()->getCuenta());
                                    $valorPagar = round($arPago->getVrNeto());
                                    $arPagoBancoDetalle->setVrPago($valorPagar); 
                                    $arPagoBancoDetalle->setBancoRel($arPago->getEmpleadoRel()->getBancoRel());                                        
                                    $arPagoBancoDetalle->setEmpleadoRel($arPago->getEmpleadoRel());
                                    $em->persist($arPagoBancoDetalle); 
                                    $arPago->setEstadoPagadoBanco(1);
                                    $em->persist($arPago);                            
                                }
                            }
                            $em->flush();
                        }
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->liquidar($codigoPagoBanco);
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
                    }
                }
                //Eliminar las programaciones que se pagaron individualmente PABLO ARANZAZU 13/05/2016
                if ($form->get('BtnEliminar')->isClicked()){
                    $arrSeleccionados = $request->request->get('ChkSeleccionarProgramacion');
                    $intTotalRegistros =0;
                    $intTotalRegistrosPagados =0;
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoProgramacionPago) {                           
                            $arPagosLimpiar = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                            $arPagosLimpiar = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
                            $intTotalRegistros = count($arPagosLimpiar);
                            foreach ($arPagosLimpiar AS $arPagoLimpiar) {
                                if ($arPagoLimpiar->getEstadoPagadoBanco() == 1){
                                    $intTotalRegistrosPagados++;
                                }
                            }
                            if ($intTotalRegistrosPagados == $intTotalRegistros){
                                $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                                $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);                                
                                $arProgramacionPago->setEstadoPagadoBanco(1);
                                $em->persist($arProgramacionPago);
                                $em->flush();
                                return $this->redirect($this->generateUrl('brs_rhu_movimiento_pago_banco_detalle_nuevo', array('codigoPagoBanco' => $codigoPagoBanco)));
                            } else {
                                $objMensaje->Mensaje("error", "No se puede Eliminar la programacion ".$codigoProgramacionPago." Tiene registros sin pagar", $this);
                            }
                        }
                    }    
                }
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrarNuevoDetalle($form);
                    $this->listarDetalle();                    
                }
                
            }    
            
        $arPagos = $paginator->paginate($em->createQuery($this->dqlListaDetalle), $request->query->get('page', 1), 2000);                        
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoBanco:detalleNuevo.html.twig', array(
            'arPagos' => $arPagos,
            'arProgramacionesPago' => $arProgramacionesPago,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/rhu/movimiento/pago/banco/detalle/vacacion/nuevo/{codigoPagoBanco}", name="brs_rhu_movimiento_pago_banco_detalle_vacacion_nuevo")
     */    
    public function detalleVacacionNuevoAction($codigoPagoBanco) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->findBy(array('estadoPagoGenerado' => 1, 'estadoPagoBanco' => 0));
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))            
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) {
            if ($arPagoBanco->getEstadoAutorizado() == 0){
                if ($form->get('BtnGuardar')->isClicked()) {                              
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigo) {   
                            $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                            $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigo);
                            if($arVacacion->getEstadoPagoBanco() == 0) {
                                $arPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                                $arPagoBancoDetalle->setPagoBancoRel($arPagoBanco);
                                $arPagoBancoDetalle->setVacacionRel($arVacacion);
                                $arPagoBancoDetalle->setCuenta($arVacacion->getEmpleadoRel()->getCuenta());
                                $valorPagar = round($arVacacion->getVrVacacion());
                                $arPagoBancoDetalle->setVrPago($valorPagar); 
                                $arPagoBancoDetalle->setBancoRel($arVacacion->getEmpleadoRel()->getBancoRel());                                        
                                $arPagoBancoDetalle->setEmpleadoRel($arVacacion->getEmpleadoRel());
                                $em->persist($arPagoBancoDetalle); 
                                $arVacacion->setEstadoPagoBanco(1);
                                $em->persist($arVacacion);                            
                            }
                        }
                        $em->flush();
                    }
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->liquidar($codigoPagoBanco);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
                }
            }                    
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoBanco:detalleVacacionNuevo.html.twig', array(
            'arVacaciones' => $arVacaciones,
            'form' => $form->createView()));
    }    

    /**
     * @Route("/rhu/movimiento/pago/banco/detalle/liquidacion/nuevo/{codigoPagoBanco}", name="brs_rhu_movimiento_pago_banco_detalle_liquidacion_nuevo")
     */    
    public function detalleLiquidacionNuevoAction($codigoPagoBanco) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);
        $arLiquidaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->findBy(array('estadoPagoGenerado' => 1, 'estadoPagoBanco' => 0));
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))            
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) {
            if ($arPagoBanco->getEstadoAutorizado() == 0){
                if ($form->get('BtnGuardar')->isClicked()) {                              
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigo) {   
                            $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
                            $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigo);
                            if($arLiquidacion->getEstadoPagoBanco() == 0) {
                                $arPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                                $arPagoBancoDetalle->setPagoBancoRel($arPagoBanco);
                                $arPagoBancoDetalle->setLiquidacionRel($arLiquidacion);
                                $arPagoBancoDetalle->setCuenta($arLiquidacion->getEmpleadoRel()->getCuenta());
                                $valorPagar = round($arLiquidacion->getVrTotal());
                                $arPagoBancoDetalle->setVrPago($valorPagar); 
                                $arPagoBancoDetalle->setBancoRel($arLiquidacion->getEmpleadoRel()->getBancoRel());                                        
                                $arPagoBancoDetalle->setEmpleadoRel($arLiquidacion->getEmpleadoRel());
                                $em->persist($arPagoBancoDetalle); 
                                $arLiquidacion->setEstadoPagoBanco(1);
                                $em->persist($arLiquidacion);                            
                            }
                        }
                        $em->flush();
                    }
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->liquidar($codigoPagoBanco);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
                }
            }                    
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoBanco:detalleLiquidacionNuevo.html.twig', array(
            'arLiquidaciones' => $arLiquidaciones,
            'form' => $form->createView()));
    } 

    /**
     * @Route("/rhu/movimiento/pago/banco/detalle/seguridad/social/nuevo/{codigoPagoBanco}", name="brs_rhu_movimiento_pago_banco_detalle_seguridad_social_nuevo")
     */    
    public function detalleSeguridadSocialNuevoAction($codigoPagoBanco) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);
        $arSsoPediodoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arSsoPediodoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->findBy(array('estadoCerrado' => 1, 'estadoPagoBanco' => 0));
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))            
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) {
            if ($arPagoBanco->getEstadoAutorizado() == 0){
                if ($form->get('BtnGuardar')->isClicked()) {                              
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigo) {  
                            $arSsoPediodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
                            $arSsoPediodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigo);                            
                            $arPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                            $arPagoBancoDetalle->setPagoBancoRel($arPagoBanco);
                            $arPagoBancoDetalle->setSsoPeriodoDetalleRel($arSsoPediodoDetalle);                            
                            $valorPagar = round($arSsoPediodoDetalle->getTotalCotizacion());
                            $arPagoBancoDetalle->setVrPago($valorPagar);                                                         
                            $em->persist($arPagoBancoDetalle);                             
                        }
                        $em->flush();
                    }
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->liquidar($codigoPagoBanco);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
                }
            }                    
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoBanco:detalleSeguridadSocialNuevo.html.twig', array(
            'arSsoPediodoDetalles' => $arSsoPediodoDetalles,
            'form' => $form->createView()));
    } 
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->listaDQL(
                $this->strFecha
                );        
    }    
    
    private function listarDetalle() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->dqlListaDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->pendientePagoBancoDql(                
                $session->get('filtroRhuCodigoBanco'));        
    }     
    
    private function filtrar ($form) {
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        $controles = $request->request->get('form');
        $dateFecha = $form->get('fecha')->getData();
        if($dateFecha != null) {            
            $this->strFecha = $dateFecha->format('Y-m-d');
        } else {
            $this->strFecha = "";
        }
        
    }
    
    private function filtrarNuevoDetalle ($form) {
        $session = $this->getRequest()->getSession();   
        $arBanco = $form->get('bancoRel')->getData();
        if($arBanco) {
            $session->set('filtroRhuCodigoBanco', $arBanco->getCodigoBancoPk());
        } else {
            $session->set('filtroRhuCodigoBanco', null);
        }        
    }    
    
    private function generarExcel() {
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'DESCRIPCIÓN')
                    ->setCellValue('C1', 'CUENTA')
                    ->setCellValue('D1', 'FECHA TRANSMISIÓN')
                    ->setCellValue('E1', 'FECHA APLICACIÓN')
                    ->setCellValue('F1', 'SECUENCIA');
                    
        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arPagoBancos = $query->getResult();
        foreach ($arPagoBancos as $arPagoBanco) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagoBanco->getCodigoPagoBancoPk())
                    ->setCellValue('B' . $i, $arPagoBanco->getDescripcion())
                    ->setCellValue('C' . $i, $arPagoBanco->getCuentaRel()->getNombre())
                    ->setCellValue('D' . $i, $arPagoBanco->getFechaTrasmision())
                    ->setCellValue('E' . $i, $arPagoBanco->getFechaAplicacion())
                    ->setCellValue('F' . $i, $arPagoBanco->getSecuencia());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('PagoBancos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PagoBancos.xlsx"');
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

    private function generarDetalleExcel($codigoPagoBanco = '') {
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
        for($col = 'A'; $col !== 'K'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        } 
        for($col = 'J'; $col !== 'K'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }          
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'PAG')
                    ->setCellValue('C1', 'VAC')
                    ->setCellValue('D1', 'LIQ')
                    ->setCellValue('E1', 'S.S')
                    ->setCellValue('F1', 'IDENTIFICACION')
                    ->setCellValue('G1', 'EMPLEADO')
                    ->setCellValue('H1', 'BANCO')
                    ->setCellValue('I1', 'CUENTA')
                    ->setCellValue('J1', 'PAGO');
                    
        $i = 2;
        //$query = $em->createQuery($this->strSqlLista);
        $arPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
        $arPagoBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array('codigoPagoBancoFk' => $codigoPagoBanco));
        //$arPagoBancos = $query->getResult();
        foreach ($arPagoBancoDetalle as $arPagoBancoDetalle) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagoBancoDetalle->getCodigoPagoBancoDetallePk())
                    ->setCellValue('B' . $i, $arPagoBancoDetalle->getCodigoPagoFk())
                    ->setCellValue('C' . $i, $arPagoBancoDetalle->getCodigoVacacionFk())
                    ->setCellValue('D' . $i, $arPagoBancoDetalle->getCodigoLiquidacionFk())
                    ->setCellValue('E' . $i, $arPagoBancoDetalle->getCodigoPeriodoDetalleFk())
                    ->setCellValue('F' . $i, $arPagoBancoDetalle->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('G' . $i, $arPagoBancoDetalle->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('H' . $i, $arPagoBancoDetalle->getBancoRel()->getNombre())
                    ->setCellValue('I' . $i, $arPagoBancoDetalle->getEmpleadoRel()->getCuenta())
                    ->setCellValue('J' . $i, $arPagoBancoDetalle->getVrPago());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('PagoBancosDetalles');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PagoBancosDetalles.xlsx"');
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
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
              
        $form = $this->createFormBuilder()
            //->add('entidadExamenRel', 'entity', $arrayPropiedades) 
            ->add('fecha','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }          
    
    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonArchivoBancolombiaPab = array('label' => 'Bancolombia Pab', 'disabled' => false);
        $arrBotonArchivoBancolombiaSap = array('label' => 'Bancolombia Sap', 'disabled' => false);
        $arrBotonArchivoAvvillasInterno = array('label' => 'Av Villas Interno', 'disabled' => false);
        $arrBotonArchivoAvvillasOtros = array('label' => 'Av Villas Otros', 'disabled' => false);
        $arrBotonArchivoDavivienda = array('label' => 'Davivienda', 'disabled' => false);
        $arrBotonArchivoBogota = array('label' => 'Bogota', 'disabled' => false);
        $arrBotonArchivoColpatriaCsv = array('label' => 'Colpatria csv', 'disabled' => false);        
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonEliminarDetalle['disabled'] = true;
        } else {
            $arrBotonImprimir['disabled'] = true;
            $arrBotonDesAutorizar['disabled'] = true;
        }
        if ($ar->getEstadoImpreso() == 1){
            $arrBotonDesAutorizar['disabled'] = true;
        }
        $form = $this->createFormBuilder()    
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)            
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)                                
                    ->add('BtnArchivoBancolombiaPab', 'submit', $arrBotonArchivoBancolombiaPab)
                    ->add('BtnArchivoBancolombiaSap', 'submit', $arrBotonArchivoBancolombiaSap)
                    ->add('BtnArchivoAvvillasInterno', 'submit', $arrBotonArchivoAvvillasInterno)
                    ->add('BtnArchivoAvvillasOtros', 'submit', $arrBotonArchivoAvvillasOtros)
                    ->add('BtnArchivoDavivienda', 'submit', $arrBotonArchivoDavivienda)
                    ->add('BtnArchivoBogota', 'submit', $arrBotonArchivoBogota)
                    ->add('BtnArchivoColpatriaCsv', 'submit', $arrBotonArchivoColpatriaCsv)
                    ->add('BtnEliminarDetalle', 'submit', $arrBotonEliminarDetalle)
                    ->add('BtnDetalleExcel', 'submit', array('label' => 'Excel'))
                    ->getForm();  
        return $form;
    }    
    
    private function generarArchivoBancolombiaPab ($arPagoBanco) {
        $em = $this->getDoctrine()->getManager();
        //$arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $strNombreArchivo = "pagoPab" . date('YmdHis') . ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;                                    
        //$strArchivo = "c:/xampp/" . $strNombreArchivo;                                    
        ob_clean();
        $ar = fopen($strArchivo,"a") or die("Problemas en la creacion del archivo plano");
        $strValorTotal = 0;
        $arPagosBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
        $arPagosBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array ('codigoPagoBancoFk' => $arPagoBanco->getCodigoPagoBancoPk()));                        
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            $strValorTotal += round($arPagoBancoDetalle->getVrPago());
        }        
        // Encabezado
        $strNitEmpresa = $this->RellenarNr(utf8_decode($arConfiguracionGeneral->getNitEmpresa()),"0",15);
        $strNombreEmpresa = $this->RellenarNr(utf8_decode(substr($arConfiguracionGeneral->getNombreEmpresa(), 0, 16)), 0, 16);
        $strTipoPagoSecuencia = "225          ";
        $strSecuencia = $arPagoBanco->getSecuencia();
        $strFechaCreacion = $arPagoBanco->getFechaTrasmision()->format('Ymd');                                                                                            
        $strFechaAplicacion = $arPagoBanco->getFechaAplicacion()->format('Ymd');
        $strNumeroRegistros = $this->RellenarNr($arPagoBanco->getNumeroRegistros(), "0", 6);        
        $strValorTotal = "00000000000000000" . $this->RellenarNr($strValorTotal, "0", 15) . "00";
        //Fin encabezado
        //(1) Tipo de registro, (10) Nit empresa, (225PAGO NOMI) descripcion transacion, (yymmdd) fecha creacion, (yymmdd) fecha aplicacion, (6) Numero de registros, (17) sumatoria de creditos, (11) Cuenta cliente a debitar, (1) Tipo de cuenta a debitar         
        fputs($ar, "1" . $strNitEmpresa . "I" . "               " .$strTipoPagoSecuencia . $strFechaCreacion . $strSecuencia. " " . $strFechaAplicacion . $strNumeroRegistros . $strValorTotal . $arPagoBanco->getCuentaRel()->getCuenta() . $arPagoBanco->getCuentaRel()->getTipo() . "\n");
        //Inicio cuerpo
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            fputs($ar, "6"); //(1)Tipo registro            
            fputs($ar, $this->RellenarNr($arPagoBancoDetalle->getEmpleadoRel()->getNumeroIdentificacion(), "0", 15)); //(15) Nit del beneficiario           
            fputs($ar, $this->RellenarNr(utf8_decode(substr($arPagoBancoDetalle->getEmpleadoRel()->getNombreCorto(), 0, 30)),"0", 30)); // (30) Nombre del beneficiario
            fputs($ar, "005600078"); // (9) Banco cuenta del beneficiario
            fputs($ar, $this->RellenarNr($arPagoBancoDetalle->getCuenta(), "0", 17)); // (17) Nro cuenta beneficiario
            fputs($ar, "337"); // (1) Indicador de lugar de pago (2) y tipo de transacción (37)
            $duoValorNetoPagar = round($arPagoBancoDetalle->getVrPago()); // (17) Valor transacción
            fputs($ar, $this->RellenarNr($duoValorNetoPagar, "0", 15) . "00");
            fputs($ar, $strFechaAplicacion);
            fputs($ar, "");
            fputs($ar, "\n");
        }
        fclose($ar);
        $em->flush();
        //Fin cuerpo                        
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename='.basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        exit;         
    }
    
    private function generarArchivoBancolombiaSap ($arPagoBanco) {
        $em = $this->getDoctrine()->getManager();
        //$arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $strNombreArchivo = "pagoSap" . date('YmdHis') . ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;                                    
        //$strArchivo = "c:/xampp/" . $strNombreArchivo;                                    
        ob_clean();
        $ar = fopen($strArchivo,"a") or die("Problemas en la creacion del archivo plano");
        $strValorTotal = 0;
        $arPagosBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
        $arPagosBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array ('codigoPagoBancoFk' => $arPagoBanco->getCodigoPagoBancoPk()));                        
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            $strValorTotal += round($arPagoBancoDetalle->getVrPago());
        }        
        // Encabezado
        $strNitEmpresa = $this->RellenarNr(utf8_decode($arConfiguracionGeneral->getNitEmpresa()),"0",10);
        $strNombreEmpresa = $this->RellenarNr(utf8_decode(substr($arConfiguracionGeneral->getNombreEmpresa(), 0, 16)), 0, 16);
        $strTipoPagoSecuencia = "225PAGO NOMI ";
        $strSecuencia = $arPagoBanco->getSecuencia();
        $strFechaCreacion = $arPagoBanco->getFechaTrasmision()->format('ymd');                                                                                            
        $strFechaAplicacion = $arPagoBanco->getFechaAplicacion()->format('ymd');
        $strNumeroRegistros = $this->RellenarNr($arPagoBanco->getNumeroRegistros(), "0", 6);
        $strValorTotal = $this->RellenarNr($strValorTotal, "0", 24);
        //Fin encabezado
        //(1) Tipo de registro, (10) Nit empresa, (225PAGO NOMI) descripcion transacion, (yymmdd) fecha creacion, (yymmdd) fecha aplicacion, (6) Numero de registros, (17) sumatoria de creditos, (11) Cuenta cliente a debitar, (1) Tipo de cuenta a debitar         
        fputs($ar, "1" . $strNitEmpresa . $strNombreEmpresa . $strTipoPagoSecuencia . $strFechaCreacion . $strSecuencia . $strFechaAplicacion . $strNumeroRegistros . $strValorTotal . $arPagoBanco->getCuentaRel()->getCuenta() . $arPagoBanco->getCuentaRel()->getTipo() . "\n");
        //Inicio cuerpo
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            fputs($ar, "6"); //(1)Tipo registro            
            fputs($ar, $this->RellenarNr($arPagoBancoDetalle->getEmpleadoRel()->getNumeroIdentificacion(), "0", 15)); //(15) Nit del beneficiario           
            fputs($ar, $this->RellenarNr(utf8_decode(substr($arPagoBancoDetalle->getEmpleadoRel()->getNombreCorto(), 0, 18)),"0", 18)); // (18) Nombre del beneficiario
            fputs($ar, "005600078"); // (9) Banco cuenta del beneficiario
            fputs($ar, $this->RellenarNr($arPagoBancoDetalle->getCuenta(), "0", 17)); // (17) Nro cuenta beneficiario
            fputs($ar, "S37"); // (3) Indicador de lugar de pago (S) y tipo de transacción (37)
            $duoValorNetoPagar = round($arPagoBancoDetalle->getVrPago()); // (17) Valor transacción
            fputs($ar, ($this->RellenarNr($duoValorNetoPagar, "0", 10)));
            fputs($ar, "                     ");
            fputs($ar, "\n");
        }
        fclose($ar);
        $em->flush();
        //Fin cuerpo                        
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename='.basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        exit;         
    }
    
    private function generarArchivoAvvillasInterno ($arPagoBanco) {
        $em = $this->getDoctrine()->getManager();
        //$arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $strNombreArchivo = "pagoAvvillasInterno" . date('YmdHis') . ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;                                    
        //$strArchivo = "c:/xampp/" . $strNombreArchivo;                                    
        ob_clean();
        $ar = fopen($strArchivo,"a") or die("Problemas en la creacion del archivo plano");
        $strValorTotal = 0;
        $arPagosBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
        $arPagosBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array ('codigoPagoBancoFk' => $arPagoBanco->getCodigoPagoBancoPk()));                        
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            $strValorTotal += round($arPagoBancoDetalle->getVrPago());
        }        
        // Encabezado
        //$strSecuencia = $arPagoBanco->getSecuencia();
        //$strSecuencia = $this->secuencia($strSecuencia);
        $strNumeroRegistros = $this->RellenarNr($arPagoBanco->getNumeroRegistros(), "0", 6);
        $strValorTotal = ($this->RellenarNr($strValorTotal, "0", 18) . "00");
        
        $strTipoRegistro = "01";
        $strFechaCreacion = $arPagoBanco->getFechaTrasmision()->format('Ymd');
        $strHoraCreacion = date('His');
        $oficina = "088";
        $adquiriente = "02";
        $nombreArchivo = "NominaVillas                                      ";
        $relleno = "                                                                                                                        ";
        //Fin encabezado
        //(1) Tipo de registro, (10) Nit empresa, (225PAGO NOMI) descripcion transacion, (yymmdd) fecha creacion, (yymmdd) fecha aplicacion, (6) Numero de registros, (17) sumatoria de creditos, (11) Cuenta cliente a debitar, (1) Tipo de cuenta a debitar         
        fputs($ar, $strTipoRegistro . $strFechaCreacion . $strHoraCreacion . $oficina . $adquiriente . $nombreArchivo . $relleno . "\n");
        //Inicio cuerpo
        $strSecuencia = 1;
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            if($arPagoBancoDetalle->getVrPago() > 0) {
                fputs($ar, "02"); //(1)Tipo registro            
                fputs($ar, "000023"); // codigo transaccion
                fputs($ar, "06"); // tipo producto origen
                fputs($ar, $this->RellenarNr($arPagoBanco->getCuentaRel()->getCuenta(), "0", 16)); // Nro cuenta origen
                fputs($ar, "052"); // entidad destino av villas 052
                fputs($ar, "01");// tipo producto destino
                fputs($ar, $this->RellenarNr($arPagoBancoDetalle->getCuenta(), "0", 16)); // Nro cuenta destino
                fputs($ar, ($this->RellenarNr($strSecuencia, "0", 9))); //secuencia
                $duoValorNetoPagar = round($arPagoBancoDetalle->getVrPago()); // (17) Valor transacción
                fputs($ar, $this->RellenarNr($duoValorNetoPagar, "0", 16) . "00");
                fputs($ar, "0000000000000000"); // numero factura duda
                fputs($ar, "0000000000000000"); // referencia 1
                fputs($ar, "0000000000000000"); // referencia 2
                fputs($ar, $this->RellenarNr2(utf8_decode(substr($arPagoBancoDetalle->getEmpleadoRel()->getNombreCorto(), 0, 30))," ", 30, "D")); // (30) Nombre del beneficiario
                fputs($ar, $this->RellenarNr($arPagoBancoDetalle->getEmpleadoRel()->getNumeroIdentificacion(), "0", 11)); // (30) Numero identificacion
                fputs($ar, "000000"); // numero de autorizacion
                fputs($ar, "00"); // codigo respuesta
                fputs($ar, "000000000000000000"); // retencion contigente
                fputs($ar, "00"); // relleno
                fputs($ar, "\n");
                $strSecuencia ++;                
            }
        }
        fputs($ar, "03" . $this->RellenarNr(($strSecuencia-1), "0", 9) . $strValorTotal . "\n");
        fclose($ar);
        $em->flush();
        //Fin cuerpo                        
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename='.basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        exit;         
    }
    
    private function generarArchivoAvvillasOtros ($arPagoBanco) {
        $em = $this->getDoctrine()->getManager();
        //$arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $strNombreArchivo = "pagoAvvillasOtros" . date('YmdHis') . ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;                                    
        //$strArchivo = "c:/xampp/" . $strNombreArchivo;   
        ob_clean();
        $ar = fopen($strArchivo,"a") or die("Problemas en la creacion del archivo plano");
        ob_clean();
        $strValorTotal = 0;
        $arPagosBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
        $arPagosBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array ('codigoPagoBancoFk' => $arPagoBanco->getCodigoPagoBancoPk()));                        
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            $strValorTotal += round($arPagoBancoDetalle->getVrPago());
        }        
        // Encabezado
        $strTipoRegistro = "1";
        $cuentaOrigen = $this->RellenarNr2($arPagoBanco->getCuentaRel()->getCuenta(), " ", 17, "D");
        $tipoCuentaOrigen = "0"; //duda
        $codigoProducto = "PP"; //duda
        $strFechaCreacion = $arPagoBanco->getFechaTrasmision()->format('Ymd');
        $strNitEmpresa = $this->RellenarNr(utf8_decode($arConfiguracionGeneral->getNitEmpresa()),"0",15);
        $tipoId = "03"; //duda
        $strNombreEmpresa = $this->RellenarNr2(utf8_decode(substr($arConfiguracionGeneral->getNombreEmpresa(), 0, 16)), " ", 16, "D");
        $codPlazaOrigen = "0002"; //duda
        $tipoRegistros = "PPD"; //duda
        $strSecuencia = "000000";
        $canal = "4"; //duda
        //$strValorTotal = $this->RellenarNr($strValorTotal, "0", 18);
        $strValorTotal = ($this->RellenarNr($strValorTotal, "0", 16) . "00");
        //Fin encabezado
        fputs($ar, $strTipoRegistro . $cuentaOrigen . $tipoCuentaOrigen . $codigoProducto . $strFechaCreacion . $strNitEmpresa . $tipoId . $strNombreEmpresa . $codPlazaOrigen . $tipoRegistros . $strSecuencia . $canal . "\n");
        //Inicio cuerpo
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            if($arPagoBancoDetalle->getVrPago() > 0) {
                fputs($ar, "2"); //(1)Tipo registro            
                fputs($ar, "32"); // codigo transaccion DUDA
                fputs($ar, "0040"); // codigo banco des
                fputs($ar, "0002"); // codigo plaza des
                fputs($ar, $this->RellenarNr($arPagoBancoDetalle->getEmpleadoRel()->getNumeroIdentificacion(), " ", 15)); //(15) Nit del beneficiario           
                fputs($ar, "01"); //(15) tipo identificacion
                fputs($ar, $this->RellenarNr2($arPagoBancoDetalle->getCuenta(), " ", 17, "D")); // Nro cuenta destino
                fputs($ar, "1");// tipo cuenta destino
                fputs($ar, $this->RellenarNr(utf8_decode(substr($arPagoBancoDetalle->getEmpleadoRel()->getNombreCorto(), 0, 22))," ", 22)); // (22) Nombre del beneficiario
                fputs($ar, "0");// duda addendas
                $duoValorNetoPagar = round($arPagoBancoDetalle->getVrPago()); // (17) Valor transacción
                fputs($ar, $this->RellenarNr($duoValorNetoPagar, "0", 16) . "00");
                fputs($ar, "1"); // valida identificacion
                fputs($ar, "\n");                
            }
        }
        //Fin cuerpo 
        //Pie de pagina
        fputs($ar, "4" . $this->RellenarNr(count($arPagosBancoDetalle), "0", 8) . $strValorTotal . "\n");
        fclose($ar);
        $em->flush();
                    
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename='.basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        exit;
        
        
    }
    
    private function generarArchivoDavivienda ($arPagoBanco) {
        $em = $this->getDoctrine()->getManager();
        //$arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $strNombreArchivo = "pagoDavivienda" . date('YmdHis') . ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;                                    
        //$strArchivo = "c:/xampp/" . $strNombreArchivo;   
        ob_clean();
        $ar = fopen($strArchivo,"a") or die("Problemas en la creacion del archivo plano");
        ob_clean();
        $strValorTotal = 0;
        $arPagosBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
        $arPagosBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array ('codigoPagoBancoFk' => $arPagoBanco->getCodigoPagoBancoPk()));                        
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            $strValorTotal += round($arPagoBancoDetalle->getVrPago());
        }        
        // Encabezado
        $strTipoRegistro = "RC";
        $strNitEmpresa = $this->RellenarNr(utf8_decode($arConfiguracionGeneral->getNitEmpresa().$arConfiguracionGeneral->getDigitoVerificacionEmpresa()),"0",16); //nit
        $strCodigoServicio = "NOMI";
        $strCodigoSubServicio = "0000";
        $cuentaOrigen = $this->RellenarNr($arPagoBanco->getCuentaRel()->getCuenta(), "0", 16); // cuenta
        $srtTipoCuenta = $arPagoBanco->getCuentaRel()->getTipo(); // tipo cuenta
        $srtCodigoBanco = "000051"; //codigo banco
        $strValorTotal = ($this->RellenarNr($strValorTotal, "0", 16) . "00"); // valor transaccion total
        $srtTotalRegistros = $this->RellenarNr($arPagoBanco->getNumeroRegistros(), "0", 6); // numero de registros
        $strFechaProceso = $arPagoBanco->getFechaTrasmision()->format('Ymd');
        $strHoraProceso = "000000";
        $strCodigoOperador = "0000";
        $strCodigoNoProcesado = "9999";
        $strFechaAplicacion = $arPagoBanco->getFechaAplicacion()->format('Ymd');
        $strHoraAplicacion = "000000";
        $strIndicadorInscripcion = "00";
        $strTipoIdentificacionEmpresa = "01";
        $strNumeroCliente = "000000000000";
        $strOficinaRecaudo = "0000";
        $strCampoFuturo = "0000000000000000000000000000000000000000";        
        //Fin encabezado
        fputs($ar, $strTipoRegistro . $strNitEmpresa . $strCodigoServicio . $strCodigoSubServicio . $cuentaOrigen . $srtTipoCuenta . $srtCodigoBanco . $strValorTotal . $srtTotalRegistros . $strFechaProceso . $strHoraProceso . $strCodigoOperador . $strCodigoNoProcesado . $strFechaAplicacion . $strHoraAplicacion . $strIndicadorInscripcion . $strTipoIdentificacionEmpresa . $strNumeroCliente . $strOficinaRecaudo . $strCampoFuturo . "\n");
        //Inicio cuerpo
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            if($arPagoBancoDetalle->getVrPago() > 0) {
                $tipocuenta = $arPagoBancoDetalle->getEmpleadoRel()->getTipoCuenta();
                if ($tipocuenta == "S"){
                    $tipo = "CA";
                }
                if ($tipocuenta == "D"){
                    $tipo = "CC";
                }
                if ($tipocuenta == "DP"){
                    $tipo = "DP";
                }
                fputs($ar, "TR"); //(1)Tipo registro de traslado            
                /*fputs($ar, "32"); // codigo transaccion DUDA
                fputs($ar, "0040"); // codigo banco des
                fputs($ar, "0002"); // codigo plaza des*/
                fputs($ar, $this->RellenarNr($arPagoBancoDetalle->getEmpleadoRel()->getNumeroIdentificacion(), "0", 16)); //(15) Nit del beneficiario           
                fputs($ar, "0000000000000000"); // referencia
                fputs($ar, $this->RellenarNr($arPagoBancoDetalle->getCuenta(), "0", 16)); // Nro cuenta destino
                fputs($ar, $tipo);// tipo producto
                fputs($ar, "000051");// codigo banco
                $duoValorNetoPagar = round($arPagoBancoDetalle->getVrPago()); // Valor transacción
                fputs($ar, $this->RellenarNr($duoValorNetoPagar, "0", 16) . "00");
                fputs($ar, "000000"); // talon
                fputs($ar, "02"); // tipo identificacion
                fputs($ar, "1"); // validacion ach
                fputs($ar, "9999"); // resultado del proceso
                fputs($ar, "0000000000000000000000000000000000000000"); // respuesta del proceso
                fputs($ar, "000000000000000000"); // valor acumulado del cobro
                fputs($ar, "00000000"); // fecha aplicacion
                fputs($ar, "0000"); // oficina de recuado
                fputs($ar, "0000"); // motivo
                fputs($ar, "0000000"); // campos futuros
                fputs($ar, "\n");                
            }
        }
        //Fin cuerpo 
        //Pie de pagina
        fclose($ar);
        $em->flush();
                    
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename='.basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        exit;
        
        
    }
    
    private function generarArchivoBogota ($arPagoBanco) {
        $em = $this->getDoctrine()->getManager();
        //$arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $strNombreArchivo = "pagoBancoBogota" . date('YmdHis') . ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;                                            
        ob_clean();
        $ar = fopen($strArchivo,"a") or die("Problemas en la creacion del archivo plano");
        ob_clean();
        $strValorTotal = 0;
        $arPagosBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
        $arPagosBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array ('codigoPagoBancoFk' => $arPagoBanco->getCodigoPagoBancoPk()));                        
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            $strValorTotal += round($arPagoBancoDetalle->getVrPago());
        }        
        // Encabezado
        $strTipoRegistro = "1";
        $strFechaAplicacion = $arPagoBanco->getFechaAplicacion()->format('Ymd');
        $espacios24 = "000000000000000000000000";        
        $srtTipoCuenta = $arPagoBanco->getCuentaRel()->getTipo(); // tipo cuenta
        if ($srtTipoCuenta == 'D'){
            $srtTipoCuenta = 1;
        } else {
            $srtTipoCuenta = 2;
        }
        $espacios6 = "000000";        
        $cuentaOrigen = $this->RellenarNr($arPagoBanco->getCuentaRel()->getCuenta(), "0", 11); // cuenta
        $strNombreEmpresa = $this->RellenarNr2(utf8_decode(substr($arConfiguracionGeneral->getNombreEmpresa(), 0, 40)), " ", 40, "D");
        $strNitEmpresa = $this->RellenarNr(utf8_decode($arConfiguracionGeneral->getNitEmpresa().$arConfiguracionGeneral->getDigitoVerificacionEmpresa()),"0",11); //nit
        $strTipoMovimiento = "001";
        $srtCodigoCiudadCuenta = "0001";
        $strFechaProceso = $arPagoBanco->getFechaTrasmision()->format('Ymd');
        $strCodigoOficina = "137";
        $strTipoIdentificacion = "N";
        $strEspacios129 = "                                                                                                                                 ";                        
        //Fin encabezado
        fputs($ar, $strTipoRegistro . $strFechaAplicacion . $espacios24 . $srtTipoCuenta . $espacios6 . $cuentaOrigen . $strNombreEmpresa . $strNitEmpresa . $strTipoMovimiento . $srtCodigoCiudadCuenta . $strFechaProceso . $strCodigoOficina . $strTipoIdentificacion . $strEspacios129 ."\n");
        //Inicio cuerpo
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            if($arPagoBancoDetalle->getVrPago() > 0) {
                fputs($ar, "2"); //(1)Tipo registro
                $tipoIdentificacion = $arPagoBancoDetalle->getEmpleadoRel()->getCodigoTipoIdentificacionFk();
                if ($tipoIdentificacion == 13){
                    $tipo = "C";
                }
                if ($tipoIdentificacion == 31){
                    $tipo = "N";
                }
                if ($tipoIdentificacion == 22){
                    $tipo = "E";
                }
                fputs($ar, $tipo); //(1)Tipo identificacion                           
                fputs($ar, $this->RellenarNr($arPagoBancoDetalle->getEmpleadoRel()->getNumeroIdentificacion(), "0", 11)); //(15) identificacion del beneficiario           
                fputs($ar, $this->RellenarNr2(utf8_decode(substr($arPagoBancoDetalle->getEmpleadoRel()->getNombreCorto(), 0, 40)), " ", 40, "D")); // nombre beneficiario
                fputs($ar, "0");
                $tipocuenta = $arPagoBancoDetalle->getEmpleadoRel()->getTipoCuenta();
                if ($tipocuenta == "S"){
                    $tipocuenta = 2;
                } 
                if ($tipocuenta == "D"){
                    $tipocuenta = 1;
                }
                fputs($ar, $tipocuenta);
                fputs($ar, $this->RellenarNr2(utf8_decode(substr($arPagoBancoDetalle->getCuenta(), 0, 17)), " ", 17, "D")); // Nro cuenta destino
                $duoValorNetoPagar = round($arPagoBancoDetalle->getVrPago()); // Valor transacción
                fputs($ar, $this->RellenarNr($duoValorNetoPagar, "0", 16) . "00");
                fputs($ar, "A"); // forma de pago abono                
                fputs($ar, "000"); 
                fputs($ar, "001"); // codigo compensacion del banco
                fputs($ar, "0001"); // codigo ciudad del banco
                fputs($ar, "ATEMPI   "); // informacion addenda
                fputs($ar, " "); 
                fputs($ar, $this->RellenarNr2(utf8_decode(substr($arPagoBancoDetalle->getPagoBancoRel()->getDescripcion(), 0, 70)), " ", 70, "D")); // nombre beneficiario
                fputs($ar, "0"); 
                fputs($ar, "0000000000"); //numero de factura o comprobante 
                fputs($ar, "N");  // envio de la informacion
                fputs($ar, "        "); // espacios en blanco
                fputs($ar, "000000000000000000"); //valor libranza
                fputs($ar, "           "); // numero libranza
                fputs($ar, "           "); // espacios
                fputs($ar, "N");  // indicador envio de mensaje
                fputs($ar, "        "); // espacios                                
                fputs($ar, "\n");                
            }
        }
        //Fin cuerpo 
        //Pie de pagina
        fclose($ar);
        $em->flush();
                    
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename='.basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        exit;
        
        
    }
    
    private function generarArchivoColpatriaCsv($arPagoBanco) {
        $em = $this->getDoctrine()->getManager();
        //$arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $strNombreArchivo = "pagoColpatriaCsv" . date('YmdHis') . ".csv";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;                                    
        //$strArchivo = "c:/xampp/" . $strNombreArchivo;                                    
        ob_clean();
        $ar = fopen($strArchivo,"a") or die("Problemas en la creacion del archivo plano");
        $strValorTotal = 0;
        $arPagosBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
        $arPagosBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array ('codigoPagoBancoFk' => $arPagoBanco->getCodigoPagoBancoPk()));                        
                
        // Encabezado                        
        $array = array("Cuenta Destino", ";","Nit Beneficiario",";", "Nombre Beneficiario",";", "Cod. Transaccion",";","Tipo de cargo",";","Valor Neto Pago",";","No. Factura",";","No. Control de pago",";","Valor Retencion en la Fuente",";","Valor IVA",";","Fecha Pago",";","Numero Nota Debito",";","Valor Nota Debito",";","Cod. Banco",";","Tipo Cuenta",";","Tipo Documento",";","Inf. Adicional");
        //Fin encabezado        
        
        foreach($array as $fields){
            fputs($ar,$fields);
        }   
        fputs($ar, "\n");
        //Inicio cuerpo
        $strSecuencia = 1;
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            if($arPagoBancoDetalle->getVrPago() > 0) {                
                $array = array($arPagoBancoDetalle->getCuenta(), ";",$arPagoBancoDetalle->getEmpleadoRel()->getNumeroIdentificacion(),";", $arPagoBancoDetalle->getEmpleadoRel()->getNombreCorto(),";","902",";","0",";",$arPagoBancoDetalle->getVrPago(),";","",";","",";","",";","",";",$arPagoBanco->getFechaTrasmision()->format('dmY'),";","",";","",";","560019",";","2",";","C",";",$arPagoBanco->getDescripcion());
                foreach($array as $fields){
                    fputs($ar,$fields);
                }  
                fputs($ar, "\n");
                $strSecuencia ++;                
            }
        }
        //fputs($ar, "03" . $this->RellenarNr(($strSecuencia-1), "0", 9) . $strValorTotal . "\n");
        fclose($ar);
        $em->flush();
        //Fin cuerpo                        
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename='.basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        exit; 
    }
    
    //Rellenar numeros
    public static function RellenarNr($Nro, $Str, $NroCr) {
        $Longitud = strlen($Nro);

        $Nc = $NroCr - $Longitud;
        for ($i = 0; $i < $Nc; $i++)
            $Nro = $Str . $Nro;

        return (string) $Nro;
    }
    
    public static function RellenarNr2($Nro, $Str, $NroCr, $strPosicion) {
        $Nro = utf8_decode($Nro);
        $Longitud = strlen($Nro);
        $Nc = $NroCr - $Longitud;
        for ($i = 0; $i < $Nc; $i++) {
            if($strPosicion == "I") {
                $Nro = $Str . $Nro;
            } else {
                $Nro = $Nro . $Str;
            }

        }

        return (string) $Nro;
    }
    
    //secuencia
    public static function secuencia($letra) {
        $nro = 0;
        if ($letra == "A"){
            $nro = 1;
        }
        if ($letra == "B"){
            $nro = 2;
        }
        if ($letra == "C"){
            $nro = 3;
        }
        if ($letra == "D"){
            $nro = 4;
        }
        if ($letra == "E"){
            $nro = 5;
        }
        if ($letra == "F"){
            $nro = 6;
        }
        if ($letra == "G"){
            $nro = 7;
        }
        if ($letra == "H"){
            $nro = 8;
        }
        if ($letra == "I"){
            $nro = 9;
        }
        if ($letra == "J"){
            $nro = 10;
        }
        if ($letra == "K"){
            $nro = 11;
        }
        if ($letra == "L"){
            $nro = 12;
        }
        if ($letra == "M"){
            $nro = 13;
        }
        if ($letra == "N"){
            $nro = 14;
        }
        if ($letra == "O"){
            $nro = 15;
        }
        if ($letra == "P"){
            $nro = 16;
        }
        if ($letra == "Q"){
            $nro = 17;
        }
        if ($letra == "R"){
            $nro = 18;
        }
        if ($letra == "S"){
            $nro = 19;
        }
        if ($letra == "T"){
            $nro = 20;
        }
        if ($letra == "U"){
            $nro = 21;
        }
        if ($letra == "V"){
            $nro = 22;
        }
        if ($letra == "W"){
            $nro = 23;
        }
        if ($letra == "X"){
            $nro = 24;
        }
        if ($letra == "Y"){
            $nro = 25;
        }
        if ($letra == "Z"){
            $nro = 26;
        }
        
        return $nro;
    }                
}