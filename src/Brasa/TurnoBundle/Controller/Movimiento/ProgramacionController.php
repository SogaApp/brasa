<?php
namespace Brasa\TurnoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurProgramacionType;

class ProgramacionController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/tur/movimiento/programacion", name="brs_tur_movimiento_programacion")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 28, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();        
        if ($form->isValid()) {
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion'));
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

        $arProgramaciones = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:lista.html.twig', array(
            'arProgramaciones' => $arProgramaciones,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/programacion/nuevo/{codigoProgramacion}", name="brs_tur_movimiento_programacion_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoProgramacion) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        if($codigoProgramacion != 0) {
            if($em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->numeroRegistros($codigoProgramacion) > 0) {
                $objMensaje->Mensaje("error", "La programacion tiene detalles y no se puede editar", $this);
            }
            $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
            
        }else{
            $arProgramacion->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(TurProgramacionType::class, $arProgramacion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arProgramacion = $form->getData();
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {
                $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));
                if(count($arCliente) > 0) {
                    $arProgramacion->setClienteRel($arCliente);
                    $arUsuario = $this->getUser();
                    $arProgramacion->setUsuario($arUsuario->getUserName()); 
                    if($em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->numeroRegistros($codigoProgramacion) <= 0) {
                        $em->persist($arProgramacion);
                        $em->flush();
                    }


                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_nuevo', array('codigoProgramacion' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $arProgramacion->getCodigoProgramacionPk())));
                    }
                } else {
                    $objMensaje->Mensaje("error", "El cliente no existe", $this);
                }
            }

        }
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:nuevo.html.twig', array(
            'arProgramacion' => $arProgramacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/programacion/detalle/{codigoProgramacion}", name="brs_tur_movimiento_programacion_detalle")
     */    
    public function detalleAction(Request $request, $codigoProgramacion) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();        
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        $form = $this->formularioDetalle($arProgramacion);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if($arProgramacion->getEstadoAutorizado() == 0) {
                    //$arrControles = $request->request->All();
                    //$this->actualizarDetalle($arrControles, $codigoProgramacion);                    
                    $strResultados = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->validarAutorizar($codigoProgramacion);
                    if($strResultados == "") {
                        $em->getRepository('BrasaTurnoBundle:TurProgramacion')->autorizar($codigoProgramacion);                        
                    } else {
                        $objMensaje->Mensaje('error', $strResultados, $this);
                    }
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));                        
                }                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if($arProgramacion->getEstadoAutorizado() == 1) {
                    $em->getRepository('BrasaTurnoBundle:TurProgramacion')->desAutorizar($codigoProgramacion);
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));                    
                }
            }
            if($form->get('BtnAprobar')->isClicked()) {
                if($arProgramacion->getEstadoAutorizado() == 1) {
                    $arProgramacion->setEstadoAprobado(1);
                    $em->persist($arProgramacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
                }
            }
            if($form->get('BtnDetalleMarcar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->marcarSeleccionados($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            } 
            if($form->get('BtnDetalleAjuste')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->ajustarSeleccionados($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }            
            if($form->get('BtnDetalleEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $strResultado =  $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->eliminarDetallesSeleccionados($arrSeleccionados);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }
            if($form->get('BtnImprimir')->isClicked()) {
                $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
                $codigoFormato = $arConfiguracion->getCodigoFormatoProgramacion();
                if($codigoFormato <= 1) {
                    $objProgramacion = new \Brasa\TurnoBundle\Formatos\Programacion1();
                    $objProgramacion->Generar($this, $codigoProgramacion);                    
                }
                if($codigoFormato == 2) {
                    $objProgramacion = new \Brasa\TurnoBundle\Formatos\Programacion2();
                    $objProgramacion->Generar($this, $codigoProgramacion);                    
                }                
            }
            if($form->get('BtnAnular')->isClicked()) {
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->anular($codigoProgramacion);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }
        }
        $arrDiaSemana = $objFunciones->diasMes($arProgramacion->getFecha(), $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arProgramacion->getFecha()->format('Y-m-').'01', $arProgramacion->getFecha()->format('Y-m-').'31'));
        $formDetalle = $this->createFormBuilder()->getForm(); 
        $dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->listaDql($codigoProgramacion);       
        $arProgramacionDetalle = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 1000);
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalle.html.twig', array(
                    'arProgramacion' => $arProgramacion,
                    'arProgramacionDetalle' => $arProgramacionDetalle,
                    'arrDiaSemana' => $arrDiaSemana,
                    'form' => $form->createView(),
                    'formDetalle' => $form->createView()
                    ));
    }

    /**
     * @Route("/tur/movimiento/programacion/detalle/editar/{codigoPuesto}/{codigoPedidoDetalle}/{codigoProgramacion}/", name="brs_tur_movimiento_programacion_detalle_editar")
     */        
    public function detalleEditarAction(Request $request, $codigoPuesto, $codigoPedidoDetalle, $codigoProgramacion) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();
        $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($codigoPuesto);        
        $form = $this->formularioDetalleEditar();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGuardar')->isClicked()) {
                if($arProgramacion->getEstadoAutorizado() == 0) {
                    $arrControles = $request->request->All();
                    $resultado = $this->actualizarDetalle($arrControles, $codigoPedidoDetalle); 
                    if($resultado == false) {
                        $em->flush();
                        $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                        
                    }
                }                
            }
        }
        $strAnioMes = $arProgramacion->getFecha()->format('Y/m');
        $arrDiaSemana = $objFunciones->diasMes($arProgramacion->getFecha(), $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arProgramacion->getFecha()->format('Y-m-').'01', $arProgramacion->getFecha()->format('Y-m-').'31'));               
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->listaDql($codigoProgramacion, $codigoPuesto, $codigoPedidoDetalle);            
        $arProgramacionDetalle = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 15);
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalleEditar.html.twig', array(
                    'arProgramacion' => $arProgramacion,
                    'arProgramacionDetalle' => $arProgramacionDetalle,
                    'arrDiaSemana' => $arrDiaSemana,
                    'codigoPuesto' => $codigoPuesto,
                    'form' => $form->createView(), 
                    'arPuesto' => $arPuesto
                    ));
    } 
    
    /**
     * @Route("/tur/movimiento/programacion/detalle/nuevo/{codigoProgramacion}/{codigoPuesto}", name="brs_tur_movimiento_programacion_detalle_nuevo")
     */        
    public function detalleNuevoAction(Request $request, $codigoProgramacion, $codigoPuesto) {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);               
        $form = $this->createFormBuilder()
            ->add('secuenciaDetalleRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurSecuenciaDetalle',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('s')
                    ->orderBy('s.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false))                  
            ->add('TxtCodigoRecurso', TextType::class)
            ->add('TxtNombreRecurso', TextType::class)    
            ->add('TxtPosicion', NumberType::class, array('data' => 1))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arRecurso = null;
                $codigoRecurso = $form->get('TxtCodigoRecurso')->getData();                
                if($codigoRecurso) {                    
                    $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
                }
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $intCantidad = $arrControles['TxtCantidad'.$codigo];
                        for($i = 1; $i <= $intCantidad; $i++) {
                            $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                            $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo);
                            $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                            $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                            $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                            $arProgramacionDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());                            
                            $arProgramacionDetalle->setAnio($arProgramacion->getFecha()->format('Y'));
                            $arProgramacionDetalle->setMes($arProgramacion->getFecha()->format('m'));
                            if($arRecurso) {
                                $arProgramacionDetalle->setRecursoRel($arRecurso);
                            }
                            
                            $arSecuenciaDetalle = $form->get('secuenciaDetalleRel')->getData();
                            if($arSecuenciaDetalle) {
                                $posicionInicial = $form->get('TxtPosicion')->getData();
                                $arrSecuenciaDetalle = $em->getRepository('BrasaTurnoBundle:TurSecuenciaDetalle')->convertirArray($arSecuenciaDetalle);
                                $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$arProgramacion->getFecha()->format('m')+1,1,$arProgramacion->getFecha()->format('Y'))-1));
                                $j = 1;
                                if($posicionInicial <= $arrSecuenciaDetalle) {
                                  $j = $posicionInicial;
                                }                                
                                for($i=1; $i<=$intUltimoDia; $i++) {
                                    if($i == 1) {
                                        $arProgramacionDetalle->setDia1($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 2) {
                                        $arProgramacionDetalle->setDia2($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 3) {
                                        $arProgramacionDetalle->setDia3($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 4) {
                                        $arProgramacionDetalle->setDia4($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 5) {
                                        $arProgramacionDetalle->setDia5($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 6) {
                                        $arProgramacionDetalle->setDia6($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 7) {
                                        $arProgramacionDetalle->setDia7($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 8) {
                                        $arProgramacionDetalle->setDia8($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 9) {
                                        $arProgramacionDetalle->setDia9($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 10) {
                                        $arProgramacionDetalle->setDia10($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 11) {
                                        $arProgramacionDetalle->setDia11($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 12) {
                                        $arProgramacionDetalle->setDia12($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 13) {
                                        $arProgramacionDetalle->setDia13($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 14) {
                                        $arProgramacionDetalle->setDia14($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 15) {
                                        $arProgramacionDetalle->setDia15($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 16) {
                                        $arProgramacionDetalle->setDia16($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 17) {
                                        $arProgramacionDetalle->setDia17($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 18) {
                                        $arProgramacionDetalle->setDia18($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 19) {
                                        $arProgramacionDetalle->setDia19($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 20) {
                                        $arProgramacionDetalle->setDia20($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 21) {
                                        $arProgramacionDetalle->setDia21($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 22) {
                                        $arProgramacionDetalle->setDia22($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 23) {
                                        $arProgramacionDetalle->setDia23($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 24) {
                                        $arProgramacionDetalle->setDia24($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 25) {
                                        $arProgramacionDetalle->setDia25($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 26) {
                                        $arProgramacionDetalle->setDia26($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 27) {
                                        $arProgramacionDetalle->setDia27($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 28) {
                                        $arProgramacionDetalle->setDia28($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 29) {
                                        $arProgramacionDetalle->setDia29($arrSecuenciaDetalle[$j]);
                                    }
                                    if($i == 30) {
                                        $arProgramacionDetalle->setDia30($arrSecuenciaDetalle[$j]);
                                    }      
                                    if($i == 31) {
                                        $arProgramacionDetalle->setDia31($arrSecuenciaDetalle[$j]);
                                    }                                                                                                             
                                    $j++;
                                    if($j > $arrSecuenciaDetalle['dias']) { $j=1;}
                                }                                
                            }
                            $em->persist($arProgramacionDetalle);
                        }
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->listaClienteFecha($arProgramacion->getCodigoClienteFk(), $codigoPuesto, '', $arProgramacion->getFecha()->format('Y'), $arProgramacion->getFecha()->format('m'));
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalleNuevo.html.twig', array(
            'arProgramacion' => $arProgramacion,
            'arPedidosDetalle' => $arPedidosDetalle,
            'codigoPuesto' => $codigoPuesto,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/tur/movimiento/programacion/detalle/pedido/nuevo/{codigoProgramacion}/{codigoProgramacionDetalle}", name="brs_tur_movimiento_programacion_detalle_pedido_nuevo")
     */        
    public function detalleNuevoPedidoAction(Request $request, $codigoProgramacion, $codigoProgramacionDetalle = 0) {
        $em = $this->getDoctrine()->getManager();
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->nuevo($codigo, $arProgramacion);
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->listaClienteFecha($arProgramacion->getCodigoClienteFk(), '', 0, $arProgramacion->getFecha()->format('Y'), $arProgramacion->getFecha()->format('m'));
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalleNuevoPedido.html.twig', array(
            'arProgramacion' => $arProgramacion,
            'arPedidosDetalle' => $arPedidosDetalle,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/programacion/detalle/resumen/{codigoProgramacionDetalle}", name="brs_tur_movimiento_programacion_detalle_resumen")
     */
    public function detalleResumenAction(Request $request, $codigoProgramacionDetalle) {
        $em = $this->getDoctrine()->getManager();   
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigoProgramacionDetalle);
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();       
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arProgramacionDetalle->getCodigoPedidoDetalleFk());
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalleResumen.html.twig', array(
                    'arProgramacionDetalle' => $arProgramacionDetalle,
                    'arPedidoDetalle' => $arPedidoDetalle,
                    ));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroProgramacionFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroProgramacionFechaDesde');
            $strFechaHasta = $session->get('filtroProgramacionFechaHasta');
        }
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurProgramacion')->listaDQL(
                $session->get('filtroProgramacionCodigo'),
                $session->get('filtroCodigoCliente'),
                $session->get('filtroProgramacionEstadoAutorizado'),
                $strFechaDesde,
                $strFechaHasta,
                $session->get('filtroProgramacionEstadoAnulado'));
    }

    private function filtrar ($form) {
        $session = new session;       
        $session->set('filtroProgramacionCodigo', $form->get('TxtCodigo')->getData());
        $session->set('filtroProgramacionEstadoAutorizado', $form->get('estadoAutorizado')->getData());          
        $session->set('filtroProgramacionEstadoAnulado', $form->get('estadoAnulado')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroProgramacionFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroProgramacionFechaHasta', $dateFechaHasta->format('Y/m/d'));                 
        $session->set('filtroProgramacionFiltrarFecha', $form->get('filtrarFecha')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
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
        if($session->get('filtroProgramacionFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroProgramacionFechaDesde');
        }
        if($session->get('filtroProgramacionFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroProgramacionFechaHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('TxtNit', TextType::class, array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', TextType::class, array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroProgramacionCodigo')))
            ->add('estadoAutorizado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'AUTORIZADO', '0' => 'SIN AUTORIZAR'), 'data' => $session->get('filtroProgramacionEstadoAutorizado')))                
            ->add('estadoAnulado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'ANULADO', '0' => 'SIN ANULAR'), 'data' => $session->get('filtroProgramacionEstadoAnulado')))                                
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('filtrarFecha', CheckboxType::class, array('required'  => false, 'data' => $session->get('filtroProgramacionFiltrarFecha')))                 
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonAprobar = array('label' => 'Aprobar', 'disabled' => true);
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => true);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);        
        $arrBotonDetalleMarcar = array('label' => 'Marcar', 'disabled' => false);        
        $arrBotonDetalleAjuste = array('label' => 'Ajuste', 'disabled' => false);                
        
        if($ar->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonAprobar['disabled'] = false;
            $arrBotonDetalleEliminar['disabled'] = true;            
            $arrBotonAnular['disabled'] = false;
            if($ar->getEstadoAnulado() == 1 || $ar->getCierreMes() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonAnular['disabled'] = true;
                $arrBotonAprobar['disabled'] = true;
            }
            
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
        }
        if($ar->getEstadoAprobado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAprobar['disabled'] = true;
        }
        $form = $this->createFormBuilder(array(), array('csrf_protection' => false))
                    ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)
                    ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)
                    ->add('BtnAprobar', SubmitType::class, $arrBotonAprobar)
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                    ->add('BtnAnular', SubmitType::class, $arrBotonAnular)                    
                    ->add('BtnDetalleEliminar', SubmitType::class, $arrBotonDetalleEliminar)
                    ->add('BtnDetalleMarcar', SubmitType::class, $arrBotonDetalleMarcar)
                    ->add('BtnDetalleAjuste', SubmitType::class, $arrBotonDetalleAjuste)
                    ->getForm();
        return $form;
    }

    private function formularioDetalleEditar() {
        $form = $this->createFormBuilder(array(), array('csrf_protection' => false))                    
                    ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
                    ->getForm();
        return $form;
    }    
    
    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        for($col = 'A'; $col !== 'H'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for($col = 'G'; $col !== 'H'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'AÑO')
                    ->setCellValue('C1', 'MES')
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'AUT')
                    ->setCellValue('F1', 'ANU')
                    ->setCellValue('G1', 'HORAS');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arProgramaciones = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramaciones = $query->getResult();

        foreach ($arProgramaciones as $arProgramacion) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProgramacion->getCodigoProgramacionPk())
                    ->setCellValue('B' . $i, $arProgramacion->getFecha()->format('Y'))
                    ->setCellValue('C' . $i, $arProgramacion->getFecha()->format('F'))
                    ->setCellValue('D' . $i, $arProgramacion->getClienteRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $objFunciones->devuelveBoolean($arProgramacion->getEstadoAutorizado()))
                    ->setCellValue('F' . $i, $objFunciones->devuelveBoolean($arProgramacion->getEstadoAnulado()))
                    ->setCellValue('G' . $i, $arProgramacion->getHoras());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Programaciones');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Programaciones.xlsx"');
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

    private function actualizarDetalle ($arrControles, $codigoPedidoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $error = false;
        $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);                                                                           
        $validarHoras = $arConfiguracion->getValidarHorasProgramacion();
        $intIndice = 0;
        $boolTurnosSobrepasados = false;
        $arrTotalHoras = $this->horasControles($arrControles);
        $horasDiurnasPendientes = $arPedidoDetalle->getHorasDiurnas() - ($arPedidoDetalle->getHorasDiurnasProgramadas() - $arrTotalHoras['horasDiurnasProgramacion']);        
        $horasDiurnasRestantes = $horasDiurnasPendientes - $arrTotalHoras['horasDiurnas'];
        $horasNocturnasPendientes = $arPedidoDetalle->getHorasNocturnas() - ($arPedidoDetalle->getHorasNocturnasProgramadas() - $arrTotalHoras['horasNocturnasProgramacion']);        
        $horasNocturnasRestantes = $horasNocturnasPendientes - $arrTotalHoras['horasNocturnas'];        
        if($validarHoras) {
            if($horasDiurnasRestantes < 0) {
                $error = TRUE;        
                $objMensaje->Mensaje("error", "Las horas diurnas de los turnos ingresadas [" . $arrTotalHoras['horasDiurnas'] . "], superan las horas del pedido disponibles para programar [" . $horasDiurnasPendientes . "]", $this);                
            }
            if($horasNocturnasRestantes < 0) {
                $error = TRUE;        
                $objMensaje->Mensaje("error", "Las horas nocturnas de los turnos ingresadas [" . $arrTotalHoras['horasNocturnas'] . "], superan las horas del pedido disponibles para programar [" . $horasNocturnasPendientes . "]", $this);                
            }            
        }        
        if($error == FALSE) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($intCodigo);
                $validar = $this->validarHoras($intCodigo, $arrControles);             
                if($validar['validado']) {                                                                                                              
                    if($arProgramacionDetalle->getPeriodoBloqueo() == 0) {
                        if($arrControles['TxtRecurso'.$intCodigo] != '') {
                            $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arrControles['TxtRecurso'.$intCodigo]);
                            if($arRecurso) {
                                $arProgramacionDetalle->setRecursoRel($arRecurso);
                            }
                        } else {
                            $arProgramacionDetalle->setRecursoRel(NULL);
                        }                
                    }            

                    if($arrControles['TxtDia01D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia1($arrControles['TxtDia01D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia1(null);
                    }                                    
                    if($arrControles['TxtDia02D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia2($arrControles['TxtDia02D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia2(null);
                    }
                    if($arrControles['TxtDia03D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia3($arrControles['TxtDia03D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia3(null);
                    }
                    if($arrControles['TxtDia04D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia4($arrControles['TxtDia04D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia4(null);
                    }
                    if($arrControles['TxtDia05D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia5($arrControles['TxtDia05D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia5(null);
                    }
                    if($arrControles['TxtDia06D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia6($arrControles['TxtDia06D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia6(null);
                    }
                    if($arrControles['TxtDia07D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia7($arrControles['TxtDia07D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia7(null);
                    }
                    if($arrControles['TxtDia08D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia8($arrControles['TxtDia08D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia8(null);
                    }
                    if($arrControles['TxtDia09D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia9($arrControles['TxtDia09D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia9(null);
                    }
                    if($arrControles['TxtDia10D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia10($arrControles['TxtDia10D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia10(null);
                    }
                    if($arrControles['TxtDia11D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia11($arrControles['TxtDia11D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia11(null);
                    }
                    if($arrControles['TxtDia12D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia12($arrControles['TxtDia12D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia12(null);
                    }
                    if($arrControles['TxtDia13D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia13($arrControles['TxtDia13D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia13(null);
                    }
                    if($arrControles['TxtDia14D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia14($arrControles['TxtDia14D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia14(null);
                    }
                    if($arrControles['TxtDia15D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia15($arrControles['TxtDia15D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia15(null);
                    }
                    if($arrControles['TxtDia16D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia16($arrControles['TxtDia16D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia16(null);
                    }
                    if($arrControles['TxtDia17D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia17($arrControles['TxtDia17D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia17(null);
                    }
                    if($arrControles['TxtDia18D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia18($arrControles['TxtDia18D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia18(null);
                    }
                    if($arrControles['TxtDia19D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia19($arrControles['TxtDia19D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia19(null);
                    }
                    if($arrControles['TxtDia20D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia20($arrControles['TxtDia20D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia20(null);
                    }
                    if($arrControles['TxtDia21D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia21($arrControles['TxtDia21D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia21(null);
                    }
                    if($arrControles['TxtDia22D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia22($arrControles['TxtDia22D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia22(null);
                    }
                    if($arrControles['TxtDia23D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia23($arrControles['TxtDia23D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia23(null);
                    }
                    if($arrControles['TxtDia24D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia24($arrControles['TxtDia24D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia24(null);
                    }
                    if($arrControles['TxtDia25D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia25($arrControles['TxtDia25D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia25(null);
                    }
                    if($arrControles['TxtDia26D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia26($arrControles['TxtDia26D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia26(null);
                    }
                    if($arrControles['TxtDia27D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia27($arrControles['TxtDia27D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia27(null);
                    }
                    if($arrControles['TxtDia28D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia28($arrControles['TxtDia28D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia28(null);
                    }
                    if($arrControles['TxtDia29D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia29($arrControles['TxtDia29D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia29(null);
                    }
                    if($arrControles['TxtDia30D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia30($arrControles['TxtDia30D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia30(null);
                    }
                    if($arrControles['TxtDia31D'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia31($arrControles['TxtDia31D'.$intCodigo]);                                            
                    } else {
                        $arProgramacionDetalle->setDia31(null);
                    }                        
                    $arProgramacionDetalle->setHorasDiurnas($validar['horasDiurnas']);
                    $arProgramacionDetalle->setHorasNocturnas($validar['horasNocturnas']);
                    $arProgramacionDetalle->setHoras($validar['horasDiurnas']+$validar['horasNocturnas']);
                    $em->persist($arProgramacionDetalle);                                                                      
                } else {
                    $error = true;
                    $objMensaje->Mensaje("error", $validar['mensaje'], $this);                
                }
                if($error) {
                    break;
                }
            }
            $horasProgramadasDiurnasPedidoTotales = ($arPedidoDetalle->getHorasDiurnasProgramadas() - $arrTotalHoras['horasDiurnasProgramacion']) + $arrTotalHoras['horasDiurnas'];
            $horasProgramadasNocturnasPedidoTotales = ($arPedidoDetalle->getHorasNocturnasProgramadas() - $arrTotalHoras['horasNocturnasProgramacion']) + $arrTotalHoras['horasNocturnas'];
            $arPedidoDetalle->setHorasDiurnasProgramadas($horasProgramadasDiurnasPedidoTotales);
            $arPedidoDetalle->setHorasNocturnasProgramadas($horasProgramadasNocturnasPedidoTotales);
            $arPedidoDetalle->setHorasProgramadas($horasProgramadasDiurnasPedidoTotales+$horasProgramadasNocturnasPedidoTotales);                                          
            $em->persist($arPedidoDetalle);             
        }                
        return $error;
    }

    private function validarTurno($strTurno) {
        $em = $this->getDoctrine()->getManager();        
        $arrTurno = array('turno' => null, 'horasDiurnas' => 0, 'horasNocturnas' => 0, 'errado' => false);
        if($strTurno != "") {
            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurno);
            if($arTurno) {
                $arrTurno['turno'] = $strTurno;
                $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas();
            } else {
                $arrTurno['errado'] = true;
            } 
        }

        return $arrTurno;
    }
    
    private function validarHoras($codigoProgramacionDetalle, $arrControles) {        
        $arrDetalle = array('validado' => true, 'horasDiurnas' => 0, 'horasNocturnas' => 0, 'mensaje' => '');
        $horasDiurnas = 0;
        $horasNocturnas = 0;
        for($i=1; $i<=31; $i++) {
            $dia = $i;
            if(strlen($dia) < 2) {
                $dia = "0" . $i;
            }
            if($arrControles['TxtDia'.$dia.'D'.$codigoProgramacionDetalle] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia'.$dia.'D'.$codigoProgramacionDetalle]);                                        
                if($arrTurno['errado'] == true) {
                    $arrDetalle['validado'] = false;
                    $arrDetalle['mensaje'] = "Turno " . $arrControles['TxtDia'.$dia.'D'.$codigoProgramacionDetalle] . " no esta creado";
                    break;
                }
                $horasDiurnas += $arrTurno['horasDiurnas'];
                $horasNocturnas += $arrTurno['horasNocturnas'];                        
            }            
        }
        $arrDetalle['horasDiurnas'] = $horasDiurnas;
        $arrDetalle['horasNocturnas'] = $horasNocturnas;
        return $arrDetalle;
    }
    
    private function horasControles($arrControles) {       
        $em = $this->getDoctrine()->getManager();
        $arrDetalle = array('validado' => true, 'horasDiurnas' => 0, 'horasNocturnas' => 0, 'horasDiurnasProgramacion' => 0, 'horasNocturnasProgramacion' => 0,  'mensaje' => '');
        $horasDiurnas = 0;
        $horasNocturnas = 0;
        $horasDiurnasProgramacion = 0;
        $horasNocturnasProgramacion = 0;        
        foreach ($arrControles['LblCodigo'] as $codigo) {
            for($i=1; $i<=31; $i++) {
                $dia = $i;
                if(strlen($dia) < 2) {
                    $dia = "0" . $i;
                }
                if($arrControles['TxtDia'.$dia.'D'.$codigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia'.$dia.'D'.$codigo]);                                        
                    if($arrTurno['errado'] == true) {
                        $arrDetalle['validado'] = false;
                        $arrDetalle['mensaje'] = "Turno " . $arrControles['TxtDia'.$dia.'D'.$codigo] . " no esta creado";
                        break;
                    }
                    $horasDiurnas += $arrTurno['horasDiurnas'];
                    $horasNocturnas += $arrTurno['horasNocturnas'];                        
                }            
            }
            $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
            $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigo);
            $horasDiurnasProgramacion += $arProgramacionDetalle->getHorasDiurnas();
            $horasNocturnasProgramacion += $arProgramacionDetalle->getHorasNocturnas();             
        }
        $arrDetalle['horasDiurnas'] = $horasDiurnas;
        $arrDetalle['horasNocturnas'] = $horasNocturnas;
        $arrDetalle['horasDiurnasProgramacion'] = $horasDiurnasProgramacion;
        $arrDetalle['horasNocturnasProgramacion'] = $horasNocturnasProgramacion;        
        return $arrDetalle;
    }    

}