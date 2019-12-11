<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuAdicionalPagoType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPagoAdicionalPeriodoType;
use Symfony\Component\HttpFoundation\Request;

class PagosAdicionalesController extends Controller
{
    var $strDqlLista = "";
    var $strDqlListaTiempoSuplementarioMasivo = "";
    var $nombre = "";
    var $identificacion = "";
    var $aplicarDiaLaborado = 2;
    
    /**
     * @Route("/rhu/pagos/adicionales/lista/{modalidad}/{periodo}", name="brs_rhu_pagos_adicionales_lista")
     */
    public function listaAction($modalidad, $periodo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 10, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar($form, $modalidad, $periodo);
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if($form->isValid()) {
            if($form->get('BtnRetirarConcepto')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        $em->remove($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista', array('modalidad' => $modalidad, 'periodo' => $periodo)));
                }
            }
            if($form->get('BtnInactivar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        if($arPagoAdicional->getEstadoInactivo() == 1) {
                            $arPagoAdicional->setEstadoInactivo(0);
                            $arPagoAdicional->setFechaUltimaEdicion(new \DateTime('now'));
                        } else {
                            $arPagoAdicional->setEstadoInactivo(1);
                            $arPagoAdicional->setFechaUltimaEdicion(new \DateTime('now'));
                        }
                        $em->persist($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista', array('modalidad' => $modalidad, 'periodo' => $periodo)));
                }
            }            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $form = $this->formularioLista();
                $this->listar($form, $modalidad, $periodo);
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $form = $this->formularioLista();
                $this->listar($form, $modalidad, $periodo);
                $this->generarExcel();
            }
        }
        //$this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->listaDql("");        
        $nombreModalidad = "";
        if($modalidad == 1) {
            $nombreModalidad = "PERMANENTES";
        }
        if($modalidad == 2) {
            $nombreModalidad = "FECHA";
        }        
        $arPagosAdicionales = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:lista.html.twig', array(
                    'arPagosAdicionales' => $arPagosAdicionales,
                    'modalidad' => $modalidad,
                    'nombreModalidad' => $nombreModalidad,
                    'periodo' => $periodo,
                    'form' => $form->createView()
                    ));
    }
    
    /**
     * @Route("/rhu/pagos/adicionales/fecha/lista/{modalidad}", name="brs_rhu_pagos_adicionales_lista_fecha")
     */
    public function listaFechaAction($modalidad) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 34, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioPeriodo();
        $form->handleRequest($request);                
        if($form->isValid()) {
            if($form->get('BtnRetirarConcepto')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        $em->remove($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista', array('modalidad' => $modalidad, 'periodo' => $periodo)));
                }
            }
            if($form->get('BtnInactivar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        if($arPagoAdicional->getEstadoInactivo() == 1) {
                            $arPagoAdicional->setEstadoInactivo(0);
                        } else {
                            $arPagoAdicional->setEstadoInactivo(1);
                        }
                        $em->persist($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista', array('modalidad' => $modalidad, 'periodo' => $periodo)));
                }
            }            
            if($form->get('BtnAplicaDiaLaborado')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        if($arPagoAdicional->getAplicaDiaLaborado() == 1) {
                            $arPagoAdicional->setAplicaDiaLaborado(0);
                        } else {
                            $arPagoAdicional->setAplicaDiaLaborado(1);
                        }
                        $em->persist($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista', array('modalidad' => $modalidad, 'periodo' => $periodo)));
                }
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $form = $this->formularioLista();
                $this->listar($form, $modalidad);
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $form = $this->formularioLista();
                $this->listar($form, $modalidad);
                $this->generarExcel();
            }
        }
        $nombreModalidad = "";
        if($modalidad == 1) {
            $nombreModalidad = "PERMANENTES";
        }
        if($modalidad == 2) {
            $nombreModalidad = "FECHA";
        }        
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicionalPeriodo')->listaDql();
        $arPagosAdicionalesPeriodos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:periodo.html.twig', array(
                    'arPagosAdicionalesPeriodos' => $arPagosAdicionalesPeriodos,
                    'modalidad' => $modalidad,
                    'nombreModalidad' => $nombreModalidad,
                    'form' => $form->createView()
                    ));
    }    

    /**
     * @Route("/rhu/movimiento/pago/adicional/periodo/nuevo/{codigoPagoAdicionalPeriodo}", name="brs_rhu_movimiento_pago_adicional_periodo_nuevo")
     */    
    public function nuevoPeriodoAction($codigoPagoAdicionalPeriodo = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arPagoAdicionalPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalPeriodo();       
        if($codigoPagoAdicionalPeriodo != 0) {
            $arPagoAdicionalPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicionalPeriodo')->find($codigoPagoAdicionalPeriodo);
        } else {
            $arPagoAdicionalPeriodo->setFecha(new \DateTime('now'));
        }        

        $form = $this->createForm(new RhuPagoAdicionalPeriodoType(), $arPagoAdicionalPeriodo);                     
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arPagoAdicionalPeriodo = $form->getData();                                                                                                          
            $em->persist($arPagoAdicionalPeriodo);
            $em->flush();                
            return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista_fecha', array('modalidad' => 2)));                                                                                                                                                                                                             
        }                

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:periodoNuevo.html.twig', array(
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/rhu/movimiento/pago/adicional/detalle/{codigoPagoAdicional}", name="brs_rhu_movimiento_pago_adicional_detalle")
     */    
    public function detalleAdicionalAction($codigoPagoAdicional) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {           
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:detallePagoAdicional.html.twig', array(
                    'arPagoAdicional' => $arPagoAdicional,
                    'form' => $form->createView()
                    ));
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
        $arrayPropiedadesConcepto = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pc')
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoPagoConcepto')) {
            $arrayPropiedadesConcepto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $session->get('filtroCodigoPagoConcepto'));
        }
        $strNombreCorto = "";
        if($session->get('filtroNumeroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroNumeroIdentificacion')));
            if($arEmpleado) {
                $session->set('filtroNumeroIdentificacion', $arEmpleado->getNumeroIdentificacion());
                $strNombreCorto = $arEmpleado->getNombreCorto();
            }  else {
                $session->set('filtroNumeroIdentificacion', null);
            }          
        } else {
            $session->set('filtroNumeroIdentificacion', null);
        }       
        
        $form = $this->createFormBuilder()
            ->add('txtNumeroIdentificacion', 'text', array('label'  => 'Numero Identificacion','data' => $session->get('filtroNumeroIdentificacion'), 'required' => false))
            ->add('txtNombreCorto', 'text', array('label'  => 'NombreCorto','data' => $strNombreCorto))                    
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('pagoConceptoRel', 'entity', $arrayPropiedadesConcepto)    
            ->add('BtnRetirarConcepto', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnInactivar', 'submit', array('label'  => 'Inactivar',))            
            ->add('aplicarDiaLaborado', 'choice', array('choices' => array('2' => 'TODOS', '0' => 'NO', '1' => 'SI'), 'data' => $session->get('filtroAplicarDiaLaborado')))                
            ->add('estadoInactivo', 'choice', array('choices' => array('2' => 'TODOS', '0' => 'NO', '1' => 'SI'), 'data' => $session->get('filtroPagoAdicionalEstadoInactivo')))                                
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function formularioPeriodo() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();                 
        $form = $this->createFormBuilder()
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        return $form;
    }
    
    private function listar($form, $modalidad, $periodo) {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->listaAdicionalesDql(                    
            $session->get('filtroNumeroIdentificacion'),
            $session->get('filtroAplicarDiaLaborado'),        
            $session->get('filtroCodigoCentroCosto'),
            $session->get('filtroCodigoPagoConcepto'),
            $session->get('filtroPagoAdicionalEstadoInactivo'),
            $modalidad,
            $periodo
            );
    }

    private function filtrarLista($form) {
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        $controles = $request->request->get('form');
        $arrControles = $request->request->All();
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroNumeroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroAplicarDiaLaborado', $form->get('aplicarDiaLaborado')->getData());
        $session->set('filtroCodigoPagoConcepto', $controles['pagoConceptoRel']);
        $session->set('filtroPagoAdicionalEstadoInactivo', $form->get('estadoInactivo')->getData());
    }

    /**
     * @Route("/rhu/pagos/adicionales/detalle/{codigoProgramacionPago}", name="brs_rhu_pagos_adicionales_detalle")
     */ 
    public function detalleAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $form = $this->createFormBuilder()
            ->add('BtnRetirarConcepto', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnAplicaDiaLaborado', 'submit', array('label'  => 'Aplicar a dia laborado',))                
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnRetirarConcepto')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        $em->remove($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));
                }
            }
            if($form->get('BtnConceptoPermanente')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        if($arPagoAdicional->getPermanente() == 1) {
                            $arPagoAdicional->setPermanente(0);
                        } else {
                            $arPagoAdicional->setPermanente(1);
                        }
                        $em->persist($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));
                }
            }
            if($form->get('BtnAplicaDiaLaborado')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        if($arPagoAdicional->getAplicaDiaLaborado() == 1) {
                            $arPagoAdicional->setAplicaDiaLaborado(0);
                        } else {
                            $arPagoAdicional->setAplicaDiaLaborado(1);
                        }
                        $em->persist($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));
                }
            }            
        }
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->listaDql($codigoProgramacionPago);        
        $arPagosAdicionales = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:detalle.html.twig', array(
                    'arProgramacionPago' => $arProgramacionPago,
                    'arPagosAdicionales' => $arPagosAdicionales,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/pagos/adicionales/generarmasivo/lista", name="brs_rhu_pagos_adicionales_generarmasivo_lista")
     */
    public function generarMasivoListaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->findBy(array('estadoGenerado' => 0));

        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);

        $arCentrosCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentrosCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();


        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:generarMasivoLista.html.twig', array(
            'arProgramacionPago' => $arProgramacionPago,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/pagos/adicionales/generarmasivo/suplementario/detalle/{codigoProgramacionPago}", name="brs_rhu_pagos_adicionales_generarmasivo_suplementario_detalle")
     */
    public function generarMasivoSuplementarioDetalleAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $this->get('session');
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        //$query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->listaDQL('', $arProgramacionPago->getCodigoCentroCostoFk(), 1));
        //$arEmpleados = $paginator->paginate($query, $request->query->get('page', 1), 50);
        //$form = $this->formularioLista();
        
        $arrayPropiedadesDepartamentoEmpresa = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoDepartamentoEmpresa')) {
            $arrayPropiedadesDepartamentoEmpresa['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa", $session->get('filtroCodigoDepartamentoEmpresa'));
        }
        $form = $this->createFormBuilder()
            ->add('departamentoEmpresaRel', 'entity', $arrayPropiedadesDepartamentoEmpresa)
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                                
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))    
            ->getForm();
        $form->handleRequest($request);
        $this->listarTiempoSuplementarioMasivo($arProgramacionPago);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            
            if($form->get('BtnGuardar')->isClicked()) {
                if ($arProgramacionPago->getEstadoPagado() == 0){
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                        $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($intCodigo);
                        if(count($arProgramacionPagoDetalle) > 0) {
                            if($arrControles['TxtHN'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHN'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasNocturnas($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHFD'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHFD'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasFestivasDiurnas($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHFN'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHFN'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasFestivasNocturnas($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHEOD'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHEOD'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasExtrasOrdinariasDiurnas($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHEON'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHEON'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasExtrasOrdinariasNocturnas($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHEFD'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHEFD'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasExtrasFestivasDiurnas($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHEFN'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHEFN'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasExtrasFestivasNocturnas($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHRN'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHRN'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasRecargoNocturno($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHRFD'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHRFD'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasRecargoFestivoDiurno($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHRFN'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHRFN'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasRecargoFestivoNocturno($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                        }
                        $intIndice++;
                    }
                } else {
                    $objMensaje->Mensaje("error", "La programacion esta pagada, no se puede modificar el tiempo suplementario!", $this);
                }    
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_generarmasivo_suplementario_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));                                                
                    //echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarListaTiempoSuplementirioMasivo($form, $request);
                $this->listarTiempoSuplementarioMasivo($arProgramacionPago);
            }
        }
        
        $arProgramacionPagoDetalle = $paginator->paginate($arProgramacionPagoDetalle, $request->query->get('page', 1), 50);                               
        //$arEmpleados = $paginator->paginate($em->createQuery($this->strDqlListaTiempoSuplementarioMasivo), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:generarMasivoSuplementarioDetalle.html.twig', array(
            'arProgramacionPagoDetalle' => $arProgramacionPagoDetalle,
            'arProgramacionPago' => $arProgramacionPago,
            'form' => $form->createView()
            ));
    }
    
    private function listarTiempoSuplementarioMasivo($ar) {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->get('session');
        $this->strDqlListaTiempoSuplementarioMasivo = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaTiempoSuplementarioMasivoDql(
                    '',
                    $ar->getCodigoCentroCostoFk(),
                    1,
                    $session->get('filtroCodigoDepartamentoEmpresa')
                    );  
    }         
    
    private function filtrarListaTiempoSuplementirioMasivo($form, Request $request) {
        $session = $this->get('session');        
        $controles = $request->request->get('form');
        $session->set('filtroCodigoDepartamentoEmpresa', $controles['departamentoEmpresaRel']);
    }
    
    
    public function generarMasivoValorDetalleAction($codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $paginator  = $this->get('knp_paginator');

        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaDQL('', $codigoCentroCosto, 1));
        $arEmpleados = $paginator->paginate($query, $request->query->get('page', 1), 50);
        $form = $this->createFormBuilder()
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();

        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:generarMasivoSuplementarioDetalle.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
            ));
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'CONCEPTO')
                    ->setCellValue('C1', 'DETALLE')
                    ->setCellValue('D1', 'CENTRO COSTO')
                    ->setCellValue('E1', 'IDENTIFICACIÓN')
                    ->setCellValue('F1', 'EMPLEADO')
                    ->setCellValue('G1', 'CANTIDAD')
                    ->setCellValue('H1', 'VALOR')                    
                    ->setCellValue('I1', 'PER')
                    ->setCellValue('J1', 'ACT')
                    ->setCellValue('K1', 'ADL');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagoAdicional = $query->getResult();
        foreach ($arPagoAdicional as $arPagoAdicional) {
            if ($arPagoAdicional->getEmpleadoRel()->getCodigoCentroCostoFk() == null){
                $srtCentroCosto = "";
            } else {
                $srtCentroCosto = $arPagoAdicional->getEmpleadoRel()->getCentroCostoRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $arPagoAdicional->getCodigoPagoAdicionalPk())    
                ->setCellValue('B' . $i, $arPagoAdicional->getPagoConceptoRel()->getNombre())
                ->setCellValue('C' . $i, $arPagoAdicional->getDetalle())
                ->setCellValue('D' . $i, $srtCentroCosto)    
                ->setCellValue('E' . $i, $arPagoAdicional->getEmpleadoRel()->getNumeroIdentificacion())                        
                ->setCellValue('F' . $i, $arPagoAdicional->getEmpleadoRel()->getNombreCorto())                    
                ->setCellValue('G' . $i, $arPagoAdicional->getCantidad())                    
                ->setCellValue('H' . $i, $arPagoAdicional->getValor())
                ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arPagoAdicional->getPermanente()))
                ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arPagoAdicional->getEstadoInactivo()))
                ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arPagoAdicional->getAplicaDiaLaborado()));
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PagosAdicionales');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PagosAdicionales.xlsx"');
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