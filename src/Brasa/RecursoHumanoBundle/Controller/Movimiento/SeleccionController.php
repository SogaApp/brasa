<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionReferenciaType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionPruebaType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionVisitaType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionEntrevistaType;

class SeleccionController extends Controller
{
    /**
     * @Route("/rhu/seleccion/lista", name="brs_rhu_seleccion_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 4, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $session = $this->getRequest()->getSession();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnEliminar')->isClicked()){
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $id) {
                        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                        $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($id);
                        $totalReferencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->devuelveNumeroReferencias($id);
                        $totalPruebas = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->devuelveNumeroPruebas($id);
                        $totalVisitas = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->devuelveNumeroVisitas($id);
                        if($totalReferencias == 0){
                            if($totalPruebas == 0){
                                if($totalVisitas == 0){
                                    if ($arSelecciones->getEstadoCerrado() == 0 and $arSelecciones->getEstadoAutorizado()== 0 and $arSelecciones->getReferenciasVerificadas()== 0 and $arSelecciones->getPresentaPruebas()== 0){
                                         $em->remove($arSelecciones);
                                         $em->flush();
                                    }
                                } else {
                                    $objMensaje->Mensaje("error", "Tiene visitas creadas en esta selección", $this);
                                  }
                            } else {
                                $objMensaje->Mensaje("error", "Tiene pruebas creadas en esta selección", $this);
                              }
                        } else {
                            $objMensaje->Mensaje("error", "Tiene referencias creadas en esta selección", $this);
                        }
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista'));
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

        $arSelecciones = $paginator->paginate($em->createQuery($session->get('dqlSeleccionLista')), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Seleccion:lista.html.twig', array('arSelecciones' => $arSelecciones, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seleccion/nuevo/{codigoSeleccion}", name="brs_rhu_seleccion_nuevo")
     */
    public function nuevoAction($codigoSeleccion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        if($codigoSeleccion != 0) {
            $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        } else {
            $arSeleccion->setFechaPruebas(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuSeleccionType, $arSeleccion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arSeleccion = $form->getData();
            $arSeleccion->setNombreCorto($arSeleccion->getNombre1() . " " . $arSeleccion->getNombre2() . " " .$arSeleccion->getApellido1() . " " . $arSeleccion->getApellido2());
            $arSeleccion->setFecha(new \DateTime('now'));
            if($codigoSeleccion == 0) {
                $arSeleccion->setCodigoUsuario($arUsuario->getUserName());
            }
            $em->persist($arSeleccion);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_nuevo', array('codigoSeleccion' => 0)));
            } else {
                if ($codigoSeleccion == 0){
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $arSeleccion->getCodigoSeleccionPk())));
                }else {
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista'));
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Seleccion:nuevo.html.twig', array(
            'arSeleccion' => $arSeleccion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seleccion/detalle/{codigoSeleccion}", name="brs_rhu_seleccion_detalle")
     */
    public function detalleAction($codigoSeleccion) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        $form = $this->formularioDetalle($arSeleccion);
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnAutorizar')->isClicked()) {
                if($arSeleccion->getEstadoAutorizado() == 0) {
                    $arSeleccionEntrevista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionEntrevista')->findBy(array('codigoSeleccionFk' => $codigoSeleccion));
                    if ($arSeleccionEntrevista != null){
                        $arSeleccion->setEstadoAutorizado(1);
                        $em->persist($arSeleccion);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
                    } else {
                        $objMensaje->Mensaje("error", "La selección no tiene entrevistas, no se puede autorizar", $this);
                    }    
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if($arSeleccion->getEstadoAutorizado() == 1) {
                    $arSeleccion->setEstadoAutorizado(0);
                    $em->persist($arSeleccion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
                }
            }

            if($form->get('BtnAprobar')->isClicked()){
                if($arSeleccion->getEstadoAutorizado() == 1) {
                    $strRespuesta = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->estadoAprobadoSelecciones($codigoSeleccion);
                    if ($strRespuesta == ''){
                        return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
                    }else{
                        $objMensaje->Mensaje('error', $strRespuesta, $this);
                    }
                }    
            }

            /*if($form->get('BtnCerrar')->isClicked()){
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->cerrarSeleccion($codigoSeleccion);
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
            }*/

            if ($form->get('BtnEliminarReferencia')->isClicked()){
                if($arSeleccion->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionarReferencia');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $id) {
                            $arSeleccionReferencias = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia();
                            $arSeleccionReferencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionReferencia')->find($id);
                            $em->remove($arSeleccionReferencias);
                            $em->flush();
                        }
                        return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
                    }
                }    
            }
            if ($form->get('BtnEliminarPrueba')->isClicked()){
                if($arSeleccion->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionarPrueba');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $id) {
                            $arSeleccionPruebas = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba();
                            $arSeleccionPruebas = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionPrueba')->find($id);
                            $em->remove($arSeleccionPruebas);
                            $em->flush();
                        }
                        return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
                    }
                }    
            }
            if ($form->get('BtnEliminarVisita')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionarVisita');
                if($arSeleccion->getEstadoAutorizado() == 0) {
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $id) {
                            $arSeleccionVisita = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionVisita();
                            $arSeleccionVisita = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionVisita')->find($id);
                            $em->remove($arSeleccionVisita);
                            $em->flush();
                        }
                        return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
                    }
                }    
            }
            
            if ($form->get('BtnEliminarEntrevista')->isClicked()){
                if($arSeleccion->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionarEntrevista');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $id) {
                            $arSeleccionEntrevista = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevista();
                            $arSeleccionEntrevista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionEntrevista')->find($id);
                            $em->remove($arSeleccionEntrevista);
                            $em->flush();
                        }
                        return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
                    }
                }    
            }
            
            if($form->get('BtnDetalleVerificarReferencia')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarReferencia');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoSeleccionReferencia) {
                        $arSeleccionReferencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionReferencia')->find($codigoSeleccionReferencia);
                        if ($arSeleccionReferencias->getEstadoVerificada() == 1){
                            $arSeleccionReferencias->setEstadoVerificada(0);
                        }else{
                            $arSeleccionReferencias->setEstadoVerificada(1);
                        }

                        $em->persist($arSeleccionReferencias);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
                }
            }
        }

        $arSeleccionReferencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionReferencia')->findBy(array('codigoSeleccionFk' => $codigoSeleccion));
        $arSeleccionPruebas = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionPrueba')->findBy(array('codigoSeleccionFk' => $codigoSeleccion));
        $arSeleccionVisita = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionVisita')->findBy(array('codigoSeleccionFk' => $codigoSeleccion));
        $arSeleccionEntrevista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionEntrevista')->findBy(array('codigoSeleccionFk' => $codigoSeleccion));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Seleccion:detalle.html.twig', array(
                    'arSeleccion' => $arSeleccion,
                    'arSeleccionReferencias' => $arSeleccionReferencias,
                    'arSeleccionPruebas' => $arSeleccionPruebas,
                    'arSeleccionVisita' => $arSeleccionVisita,
                    'arSeleccionEntrevista' => $arSeleccionEntrevista,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/seleccion/agregar/referencia/{codigoSeleccion}/{codigoSeleccionReferencia}", name="brs_rhu_seleccion_agregar_referencia")
     */
    public function agregarReferenciaAction($codigoSeleccion, $codigoSeleccionReferencia) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        $arSeleccionReferencia = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia();
        if($codigoSeleccionReferencia != 0) {
            $arSeleccionReferencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionReferencia')->find($codigoSeleccionReferencia);
        }
        $form = $this->createForm(new RhuSeleccionReferenciaType(), $arSeleccionReferencia);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            if ($arSeleccion->getEstadoAutorizado() == 0){
                $arSeleccionReferencia = $form->getData();
                $arSeleccionReferencia->setSeleccionRel($arSeleccion);
                if($codigoSeleccionReferencia == 0) {
                    $arSeleccionReferencia->setCodigoUsuario($arUsuario->getUserName());
                }
                $em->persist($arSeleccionReferencia);
                $em->flush();
                if($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_agregar_referencia', array('codigoSeleccion' => $codigoSeleccion, 'codigoSeleccionReferencia' => 0)));
                } else {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            } else {    
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
              }  
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Seleccion:agregarReferencia.html.twig', array(
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/seleccion/agregar/prueba/{codigoSeleccion}/{codigoSeleccionPrueba}", name="brs_rhu_seleccion_agregar_prueba")
     */
    public function agregarPruebaAction($codigoSeleccion, $codigoSeleccionPrueba) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        $arSeleccionPrueba = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba();
        if($codigoSeleccionPrueba != 0) {
            $arSeleccionPrueba = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionPrueba')->find($codigoSeleccionPrueba);
        }
        $form = $this->createForm(new RhuSeleccionPruebaType(), $arSeleccionPrueba);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            if ($arSeleccion->getEstadoAutorizado() == 0){
                $arSeleccionPrueba = $form->getData();
                $arSeleccionPrueba->setSeleccionRel($arSeleccion);
                if($codigoSeleccionPrueba == 0) {
                    $arSeleccionPrueba->setCodigoUsuario($arUsuario->getUserName());
                }
                $em->persist($arSeleccionPrueba);
                $em->flush();
                if($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_agregar_prueba', array('codigoSeleccion' => $codigoSeleccion, 'codigoSeleccionPrueba' => 0)));
                } else {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Seleccion:agregarPrueba.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seleccion/agregar/visita/{codigoSeleccion}/{codigoSeleccionVisita}", name="brs_rhu_seleccion_agregar_visita")
     */
    public function agregarVisitaAction($codigoSeleccion, $codigoSeleccionVisita) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        $arSeleccionVisita = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionVisita();
        if($codigoSeleccionVisita != 0) {
            $arSeleccionVisita = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionVisita')->find($codigoSeleccionVisita);
        }
        $form = $this->createForm(new RhuSeleccionVisitaType(), $arSeleccionVisita);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            if ($arSeleccion->getEstadoAutorizado() == 0){
                $arSeleccionVisita = $form->getData();
                $arSeleccionVisita->setSeleccionRel($arSeleccion);
                if($codigoSeleccionVisita == 0) {
                    $arSeleccionVisita->setCodigoUsuario($arUsuario->getUserName());
                }
                $em->persist($arSeleccionVisita);
                $em->flush();
                if($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_agregar_visita', array('codigoSeleccion' => $codigoSeleccion, 'codigoSeleccionVisita' => 0)));
                } else {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }               
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }            
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Seleccion:agregarVisita.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seleccion/agregar/entrevista/{codigoSeleccion}/{codigoSeleccionEntrevista}", name="brs_rhu_seleccion_agregar_entrevista")
     */
    public function agregarEntrevistaAction($codigoSeleccion, $codigoSeleccionEntrevista) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        $arSeleccionEntrevista = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevista();
        if($codigoSeleccionEntrevista != 0) {
            $arSeleccionEntrevista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionEntrevista')->find($codigoSeleccionEntrevista);
        }
        $form = $this->createForm(new RhuSeleccionEntrevistaType(), $arSeleccionEntrevista);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            if ($arSeleccion->getEstadoAutorizado() == 0){
                if ($form->get('seleccionEntrevistaTipoRel')->getData() == null){
                    $objMensaje->Mensaje("error", "Debe selecionar un tipo de entrevista", $this);
                } else {
                    $arSeleccionEntrevista = $form->getData();
                    $arSeleccionEntrevista->setSeleccionRel($arSeleccion);
                    if($codigoSeleccionEntrevista == 0) {
                        $arSeleccionEntrevista->setCodigoUsuario($arUsuario->getUserName());
                    }
                    $em->persist($arSeleccionEntrevista);
                    $em->flush();
                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_rhu_seleccion_agregar_entrevista', array('codigoSeleccion' => $codigoSeleccion, 'codigoSeleccionEntrevista' => 0)));
                    } else {
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    }
                }
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }            
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Seleccion:agregarEntrevista.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seleccion/cerrar/{codigoSeleccion}", name="brs_rhu_seleccion_cerrar")
     */
    public function cerrarAction($codigoSeleccion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        $dato = "";
        if ($arSeleccion->getEstadoCerrado() == 1){
            $dato = "el proceso de seleccion esta cerrado, no se puede realizar el proceso";
        }
        $formSeleccion = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_seleccion_cerrar', array('codigoSeleccion' => $codigoSeleccion)))
            ->add('comentarios', 'textarea', array('data' =>$arSeleccion->getComentarios() ,'required' => true))                      
            ->add('fechaCierre', 'date', array('label'  => 'Fecha', 'data' => new \DateTime('now')))
            ->add('motivoCierreSeleccionRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuMotivoCierreSeleccion',
                'property' => 'nombre',
            ))
            ->add('bloqueado', 'checkbox', array('required'  => false))
            ->add('comentariosAspirante', 'textarea', array('required'  => false))                      
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $formSeleccion->handleRequest($request);
           
        if ($formSeleccion->isValid()) {
            if ($arSeleccion->getEstadoAutorizado() == 1){
                if ($arSeleccion->getEstadoCerrado() == 0){
                    $arUsuario = $this->get('security.context')->getToken()->getUser();
                    $arSeleccion->setComentarios($formSeleccion->get('comentarios')->getData());
                    $arSeleccion->setFechaCierre($formSeleccion->get('fechaCierre')->getData());
                    $arSeleccion->setCodigoUsuario($arUsuario->getUserName());
                    $arSeleccion->setEstadoCerrado(1);
                    $arSeleccion->setMotivoCierreSeleccionRel($formSeleccion->get('motivoCierreSeleccionRel')->getData());
                    if ($arSeleccion->getNumeroIdentificacion()){
                        if ($formSeleccion->get('bloqueado')->getData() == true){
                            $arAspirante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->findOneBy(array('numeroIdentificacion' => $arSeleccion->getNumeroIdentificacion()));
                            $arAspirante->setBloqueado(1);
                            $arAspirante->setComentarios($formSeleccion->get('comentariosAspirante')->getData());
                            $em->persist($arAspirante);
                        }
                    }
                    $em->persist($arSeleccion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
                } else {
                    $objMensaje->Mensaje("error", "El proceso de seleccion esta cerrado, no se puede realizar el proceso", $this);
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
                }    
            } else {
                $objMensaje->Mensaje("error", "El proceso de seleccion debe estar autorizado", $this);
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
            }
            
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Seleccion:cerrarSeleccion.html.twig', array(
            'arSeleccion' => $arSeleccion,
            'dato' => $dato,
            'formSeleccion' => $formSeleccion->createView()
        ));
    }
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $session->set('dqlSeleccionLista', $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->listaDQL(
                $session->get('filtroNombreSeleccion'),
                $session->get('filtroIdentificacionSeleccion'),
                $session->get('filtroAbiertoSeleccion'),
                $session->get('filtroAprobadoSeleccion'),
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroCodigoRequisicion')
                ));
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
        $arrayPropiedadesRequisicion = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSeleccionRequisito',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                    ->where('r.estadoCerrado = 0')
                    ->orderBy('r.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );                    
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        if($session->get('filtroCodigoRequisicion')) {
            $arrayPropiedadesRequisicion['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuSeleccionRequisito", $session->get('filtroCodigoRequisicion'));
        }        
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('requisicionRel', 'entity', $arrayPropiedadesRequisicion)
            ->add('estadoAprobado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAprobadoSeleccion')))
            ->add('estadoCerrado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAbiertoSeleccion')))
            ->add('TxtNombre', 'text', array('label'  => 'Nombre', 'data' => $session->get('filtroNombreSeleccion')))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacionSeleccion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonEliminarReferencia = array('label' => 'Eliminar referencia', 'disabled' => false);
        $arrBotonEliminarPrueba = array('label' => 'Eliminar prueba', 'disabled' => false);
        $arrBotonEliminarVisita = array('label' => 'Eliminar visita', 'disabled' => false);
        $arrBotonEliminarEntrevista = array('label' => 'Eliminar Entrevista', 'disabled' => false);
        $arrBotonDetalleVerificarReferencia = array('label' => 'Verificar', 'disabled' => false);
        $arrBotonAprobar = array('label' => 'Aprobar', 'disabled' => false);
        //$arrBotonCerrar = array('label' => 'Cerrar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 0) {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAprobar['disabled'] = true;
            //$arrBotonCerrar['disabled'] = true;
        } else {
            $arrBotonDetalleVerificarReferencia['disabled'] = true;
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonEliminarReferencia['disabled'] = true;
            $arrBotonEliminarPrueba['disabled'] = true;
            //$arrBotonEliminarVisita['disabled'] = true;
            $arrBotonEliminarEntrevista['disabled'] = true;
        }
        if($ar->getEstadoAprobado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAprobar['disabled'] = true;
        }
        if ($ar->getEstadoCerrado() == 1){
            //$arrBotonCerrar['disabled'] = true;
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAprobar['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                    ->add('BtnAprobar', 'submit', $arrBotonAprobar)
                    //->add('BtnCerrar', 'submit', $arrBotonCerrar)
                    ->add('BtnDetalleVerificarReferencia', 'submit', $arrBotonDetalleVerificarReferencia)
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)
                    ->add('BtnEliminarReferencia', 'submit', $arrBotonEliminarReferencia)
                    ->add('BtnEliminarPrueba', 'submit', $arrBotonEliminarPrueba)
                    ->add('BtnEliminarVisita', 'submit', $arrBotonEliminarVisita)
                    ->add('BtnEliminarEntrevista', 'submit', $arrBotonEliminarEntrevista)
                    ->getForm();
        return $form;
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroCodigoRequisicion', $controles['requisicionRel']);
        $session->set('filtroNombreSeleccion', $form->get('TxtNombre')->getData());
        $session->set('filtroIdentificacionSeleccion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroAbiertoSeleccion', $form->get('estadoCerrado')->getData());
        $session->set('filtroAprobadoSeleccion', $form->get('estadoAprobado')->getData());
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'REQUISICIÓN')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'CENTRO COSTOS')
                    ->setCellValue('G1', 'CARGO')
                    ->setCellValue('H1', 'TELEFONO')
                    ->setCellValue('I1', 'CELULAR')
                    ->setCellValue('J1', 'PRUEBAS_PRESENTADAS')
                    ->setCellValue('K1', 'APROBADO')
                    ->setCellValue('L1', 'REFERENCIAS_VERIFICADAS')
                    ->setCellValue('M1', 'CERRADO')
                    ->setCellValue('N1', 'FECHA CIERRE')
                    ->setCellValue('O1', 'MOTIVO')
                    ->setCellValue('P1', 'COMENTARIOS');

        $i = 2;
        $query = $em->createQuery($session->get('dqlSeleccionLista'));
        $arSelecciones = $query->getResult();
        foreach ($arSelecciones as $arSelecciones) {

            if ($arSelecciones->getCodigoSeleccionRequisitoFk() == null)
            {
                $seleccionRequisito = "";
            }
            else
            {
                $seleccionRequisito = $arSelecciones->getSeleccionRequisitoRel()->getNombre();
            }
            $motivo = "";
            if ($arSelecciones->getCodigoMotivoCierreSeleccionFk() != null){
                $motivo = $arSelecciones->getMotivoCierreSeleccionRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSelecciones->getCodigoSeleccionPk())
                    ->setCellValue('B' . $i, $arSelecciones->getSeleccionTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $seleccionRequisito)
                    ->setCellValue('D' . $i, $arSelecciones->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arSelecciones->getNombreCorto())
                    ->setCellValue('F' . $i, $arSelecciones->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arSelecciones->getCargoRel()->getNombre())
                    ->setCellValue('H' . $i, $arSelecciones->getTelefono())
                    ->setCellValue('I' . $i, $arSelecciones->getCelular())
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arSelecciones->getPresentaPruebas()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arSelecciones->getEstadoAprobado()))
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arSelecciones->getReferenciasVerificadas()))
                    ->setCellValue('M' . $i, $objFunciones->devuelveBoolean($arSelecciones->getEstadoCerrado()))
                    ->setCellValue('N' . $i, $arSelecciones->getFechaCierre())
                    ->setCellValue('O' . $i, $motivo)
                    ->setCellValue('P' . $i, $arSelecciones->getComentarios());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Seleccionados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="seleccionados.xlsx"');
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
