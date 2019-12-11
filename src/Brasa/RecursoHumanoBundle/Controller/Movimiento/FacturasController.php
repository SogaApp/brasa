<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuFacturaType;
use Doctrine\ORM\EntityRepository;

class FacturasController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/facturas/lista", name="brs_rhu_facturas_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 16, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $strSqlLista = $this->getRequest()->getSession(); 
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->listar();          
        if ($form->isValid()) {            
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if($form->get('BtnEliminar')->isClicked()){    
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoFactura) {
                        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
                        $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
                        $arFacturasDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->devuelveNumeroFacturasDetalle($codigoFactura);    
                        if($arFacturasDetalle == 0){
                            $em->remove($arSelecciones);
                            $em->flush();
                        }
                        else {
                            $objMensaje->Mensaje("error", "No se puede eliminar la factura, tiene registros liquidados", $this);
                        }
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_facturas_lista'));    
                }
            }
            if ($form->get('BtnFiltrar')->isClicked()) {    
                $this->filtrar($form);
                $this->listar();
            }
            
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }            
        }                      
        $arFacturas = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:lista.html.twig', array('arFacturas' => $arFacturas, 'form' => $form->createView()));
    }       
    
    /**
     * @Route("/rhu/facturas/nuevo/{codigoFactura}", name="brs_rhu_facturas_nuevo")
     */
    public function nuevoAction($codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        if ($codigoFactura != 0) {
            $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        }
        else {
           $arFactura->setFecha(new \DateTime('now'));           
        }
        $form = $this->createForm(new RhuFacturaType(), $arFactura);       
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arFactura = $form->getData(); 
            $arTercero = new \Brasa\GeneralBundle\Entity\GenTercero();
            $arTercero = $em->getRepository('BrasaGeneralBundle:GenTercero')->find($form->get('terceroRel')->getData());
            $diasPlazo = $arTercero->getPlazoPagoCliente() - 1;
            $fechaVence = date('Y-m-d', strtotime('+'.$diasPlazo.' day')) ;  
            $arFactura->setFechaVence(new \DateTime($fechaVence));
            $em->persist($arFactura);
            $em->flush();                            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_facturas_nuevo', array('codigoFactura' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $arFactura->getCodigoFacturaPk())));
            }    
            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:nuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/rhu/facturas/detalle/{codigoFactura}", name="brs_rhu_facturas_detalle")
     */
    public function detalleAction($codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();                 
        $form = $this->createFormBuilder()                        
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))            
            ->add('BtnEliminarDetalleServicio', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnEliminarDetalleExamen', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnEliminarDetalleSeleccion', 'submit', array('label'  => 'Eliminar',))            
            ->getForm();
        $form->handleRequest($request);        
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnEliminarDetalleServicio')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarServicio');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoFacturaDetalle) {
                        $arFacturaDetalleEliminar = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->find($codigoFacturaDetalle);
                        $arServicioCobrar = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->find($arFacturaDetalleEliminar->getCodigoServicioCobrarFk());
                        $arServicioCobrar->setEstadoCobrado(0);
                        $em->persist($arServicioCobrar);
                        $em->remove($arFacturaDetalleEliminar);                        
                    }
                    $em->flush();  
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $codigoFactura)));
                }
            }
            if($form->get('BtnEliminarDetalleExamen')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarExamen');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoExamen) {
                        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
                        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
                        $arExamen->setEstadoCobrado(0);
                        $arExamen->setFacturaRel(NULL);                                                                        
                        $em->persist($arExamen);                       
                    }
                    $em->flush();  
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $codigoFactura)));
                }
            }            
            if($form->get('BtnEliminarDetalleSeleccion')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarSeleccion');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoSeleccion) {
                        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
                        $arSeleccion->setEstadoCobrado(0);
                        $arSeleccion->setFacturaRel(NULL);                                                                        
                        $em->persist($arSeleccion);                       
                    }
                    $em->flush();  
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $codigoFactura)));
                }
            }             
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoFactura = new \Brasa\RecursoHumanoBundle\Formatos\FormatoFactura();
                $objFormatoFactura->Generar($this, $codigoFactura);
            }       
        }
        $arFacturaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
        $arFacturaDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));        
        $arExamenes = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamenes = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->findBy(array('codigoFacturaFk' => $codigoFactura));                
        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->findBy(array('codigoFacturaFk' => $codigoFactura));                        
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:detalle.html.twig', array(
                    'arFactura' => $arFactura,
                    'arFacturaDetalles' => $arFacturaDetalles,
                    'arExamenes' => $arExamenes,
                    'arSelecciones' => $arSelecciones,
                    'form' => $form->createView(),
                    ));
    }
    
    /**
     * @Route("/rhu/facturas/detalle/nuevo/servicio/{codigoFactura}", name="brs_rhu_facturas_detalle_nuevo_servicio")
     */
    public function detalleNuevoServicioAction($codigoFactura) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);                
        $form = $this->createFormBuilder()
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnAgregar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoServicioCobrar) {
                        $arServicioCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
                        $arServicioCobrar = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->find($codigoServicioCobrar);
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($arServicioCobrar->getCodigoPagoFk());
                        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arServicioCobrar->getCodigoCentroCostoFk());
                        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($arServicioCobrar->getCodigoProgramacionPagoFk());
                        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arServicioCobrar->getCodigoEmpleadoFk());
                        if($arServicioCobrar->getEstadoCobrado() == 0) {
                            $arFacturaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
                            $arFacturaDetalle->setPagoRel($arPago);
                            $arFacturaDetalle->setEmpleadoRel($arEmpleado);
                            $arFacturaDetalle->setCentroCostoRel($arCentroCosto);
                            $arFacturaDetalle->setProgramacionPagoRel($arProgramacionPago);
                            $arFacturaDetalle->setFacturaRel($arFactura);
                            $arFacturaDetalle->setServicioCobrarRel($arServicioCobrar);
                            $arFacturaDetalle->setFechaDesde($arServicioCobrar->getFechaDesde());
                            $arFacturaDetalle->setFechaHasta($arServicioCobrar->getFechaHasta());
                            $arFacturaDetalle->setVrSalario($arServicioCobrar->getVrSalario());
                            $arFacturaDetalle->setVrSalarioPeriodo($arServicioCobrar->getVrSalarioPeriodo());
                            $arFacturaDetalle->setVrSalarioEmpleado($arServicioCobrar->getVrSalarioEmpleado());
                            $arFacturaDetalle->setVrDevengado($arServicioCobrar->getVrDevengado());
                            $arFacturaDetalle->setVrDeducciones($arServicioCobrar->getVrDeducciones());
                            $arFacturaDetalle->setVrAdicionalTiempo($arServicioCobrar->getVrAdicionalTiempo());
                            $arFacturaDetalle->setVrAdicionalValor($arServicioCobrar->getVrAdicionalValor());
                            $arFacturaDetalle->setVrAuxilioTransporte($arServicioCobrar->getVrAuxilioTransporte());
                            $arFacturaDetalle->setVrAuxilioTransporteCotizacion($arServicioCobrar->getVrAuxilioTransporteCotizacion());
                            $arFacturaDetalle->setVrArp($arServicioCobrar->getVrArp());
                            $arFacturaDetalle->setVrEps($arServicioCobrar->getVrEps());
                            $arFacturaDetalle->setVrPension($arServicioCobrar->getVrPension());
                            $arFacturaDetalle->setVrCaja($arServicioCobrar->getVrCaja());
                            $arFacturaDetalle->setVrSena($arServicioCobrar->getVrSena());
                            $arFacturaDetalle->setVrIcbf($arServicioCobrar->getVrIcbf());
                            $arFacturaDetalle->setVrCesantias($arServicioCobrar->getVrCesantias());
                            $arFacturaDetalle->setVrVacaciones($arServicioCobrar->getVrVacaciones());
                            $arFacturaDetalle->setVrAdministracion($arServicioCobrar->getVrAdministracion());
                            $arFacturaDetalle->setVrNeto($arServicioCobrar->getVrNeto());
                            $arFacturaDetalle->setVrBruto($arServicioCobrar->getVrBruto());
                            $arFacturaDetalle->setVrTotalCobrar($arServicioCobrar->getVrTotalCobrar());
                            $arFacturaDetalle->setVrCosto($arServicioCobrar->getVrCosto());
                            $arFacturaDetalle->setVrIngresoBaseCotizacion($arServicioCobrar->getVrIngresoBaseCotizacion());
                            $arFacturaDetalle->setEstadoCobrado($arServicioCobrar->getEstadoCobrado());
                            $arFacturaDetalle->setDiasPeriodo($arServicioCobrar->getDiasPeriodo());
                            $em->persist($arFacturaDetalle);                                                     
                            $arServicioCobrar->setEstadoCobrado(1);
                            $em->persist($arServicioCobrar);
                        }                        
                    }                    

                    $em->flush();                    
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->pendienteCobrar($arFactura->getCodigoCentroCostoFk()));        
        $arServiciosCobrar = $paginator->paginate($query, $request->query->get('page', 1), 50);                       
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:detalleNuevoServicio.html.twig', array(
            'arServiciosCobrar' => $arServiciosCobrar,
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/rhu/facturas/detalle/nuevo/examen/{codigoFactura}", name="brs_rhu_facturas_detalle_nuevo_examen")
     */
    public function detalleNuevoExamenAction($codigoFactura) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);                
        $form = $this->createFormBuilder()
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnAgregar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoExamen) {
                        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
                        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
                        if($arExamen->getEstadoCobrado() == 0) {
                            $arExamen->setFacturaRel($arFactura);
                            $arExamen->setEstadoCobrado(1);
                            $em->persist($arExamen);
                        }                                                
                    }                    
                    $em->flush();                    
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->pendienteCobrar($arFactura->getCodigoCentroCostoFk()));        
        $arExamenes = $paginator->paginate($query, $request->query->get('page', 1), 50);                       
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:detalleNuevoExamen.html.twig', array(
            'arExamenes' => $arExamenes,
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }        
    
    /**
     * @Route("/rhu/facturas/detalle/nuevo/seleccion/{codigoFactura}", name="brs_rhu_facturas_detalle_nuevo_seleccion")
     */
    public function detalleNuevoSeleccionAction($codigoFactura) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);                
        $form = $this->createFormBuilder()
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnAgregar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoSeleccion) {
                        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
                        if($arSeleccion->getEstadoCobrado() == 0) {
                            $arSeleccion->setFacturaRel($arFactura);
                            $arSeleccion->setEstadoCobrado(1);
                            $em->persist($arSeleccion);
                        }                                                
                    }                    
                    $em->flush();                    
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->pendienteCobrar($arFactura->getCodigoCentroCostoFk()));        
        $arSelecciones = $paginator->paginate($query, $request->query->get('page', 1), 50);                       
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:detalleNuevoSeleccion.html.twig', array(
            'arSelecciones' => $arSelecciones,
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }            
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->listaDql(
                    $session->get('filtroCodigoTerceros'),
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroNumero'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }
    
    private function filtrar($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoTerceros', $controles['terceroRel']);
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroNumero', $form->get('TxtNumero')->getData());
               
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
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
        
        $arrayPropiedadesTerceros = array(
                'class' => 'BrasaGeneralBundle:GenTercero',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                    ->orderBy('t.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoTerceros')) {
            $arrayPropiedadesTerceros['data'] = $em->getReference("BrasaGeneralBundle:GenTercero", $session->get('filtroCodigoTerceros'));
        }
        
        $form = $this->createFormBuilder()
            ->add('terceroRel', 'entity', $arrayPropiedadesTerceros)
            ->add('centroCostoRel', 'entity', $arrayPropiedadesCentroCosto)
            ->add('TxtNumero', 'text', array('label'  => 'Numero','data' => $session->get('filtroNumero')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        return $form;
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO FACTURA')
                    ->setCellValue('B1', 'NÚMERO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'FECHA VENCE')
                    ->setCellValue('E1', 'CLIENTE')
                    ->setCellValue('F1', 'CENTRO COSTO')
                    ->setCellValue('G1', 'VR. BRUTO')
                    ->setCellValue('H1', 'VR. NETO')
                    ->setCellValue('I1', 'VR. RETENCION FUENTE')
                    ->setCellValue('J1', 'VR. RETENCION CREE')
                    ->setCellValue('K1', 'VR. RETENCION IVA')
                    ->setCellValue('L1', 'VR. BASE AIU')
                    ->setCellValue('M1', 'VR. TOTAL ADMNISTRACION')
                    ->setCellValue('N1', 'VR. TOTAL INGRESO MISION')
                    ->setCellValue('O1', 'VR. COMENTARIOS');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arFacturas = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFacturas = $query->getResult();
        foreach ($arFacturas as $arFactura) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFactura->getCodigoFacturaPk())
                    ->setCellValue('B' . $i, $arFactura->getNumero())
                    ->setCellValue('C' . $i, $arFactura->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arFactura->getFechaVence()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arFactura->getTerceroRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arFactura->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arFactura->getVrBruto())
                    ->setCellValue('H' . $i, $arFactura->getVrNeto())
                    ->setCellValue('I' . $i, $arFactura->getVrRetencionFuente())
                    ->setCellValue('J' . $i, $arFactura->getVrRetencionCree())
                    ->setCellValue('K' . $i, $arFactura->getVrRetencionIva())
                    ->setCellValue('L' . $i, $arFactura->getVrBaseAIU())
                    ->setCellValue('M' . $i, $arFactura->getVrTotalAdministracion())
                    ->setCellValue('N' . $i, $arFactura->getVrIngresoMision())
                    ->setCellValue('O' . $i, $arFactura->getComentarios());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Facturas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="facturas.xlsx"');
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
