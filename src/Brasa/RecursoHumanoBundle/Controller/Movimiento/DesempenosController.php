<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDesempenoType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDesempenoObservacionesType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDesempenoAspectosMejorarType;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DesempenosController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/desempeno/lista", name="brs_rhu_desempeno_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 22, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
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
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoDesempeno) {
                        $arDesempeno = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno();
                        $arDesempeno = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempeno')->find($codigoDesempeno);
                        $arDesempenoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle();
                        $arDesempenoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoDetalle')->findBy(array('codigoDesempenoFk' => $codigoDesempeno));
                        foreach ($arDesempenoDetalles AS $arDesempenoDetalle) {
                            if ($arDesempenoDetalle->getDesempenoRel()->getEstadoAutorizado() == 0){
                                $em->remove($arDesempenoDetalle);
                            }
                        }
                        if ($arDesempeno->getEstadoAutorizado() == 0){
                            $em->remove($arDesempeno);
                        }
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_desempeno_lista'));
                }
            }
        }

        $arDesempenos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Desempenos:lista.html.twig', array(
            'arDesempenos' => $arDesempenos,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/desempeno/nuevo/{codigoDesempeno}", name="brs_rhu_desempeno_nuevo")
     */
    public function nuevoAction($codigoDesempeno = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arDesempeno = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno();    
        if($codigoDesempeno != 0) {
            $arDesempeno = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempeno')->find($codigoDesempeno);
        } else {
            $arDesempeno->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuDesempenoType, $arDesempeno);         
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arrControles = $request->request->All();
            $arDesempeno = $form->getData();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    $arDesempeno->setEmpleadoRel($arEmpleado);
                    if($arEmpleado->getCodigoContratoActivoFk() != '') {                        
                        
                        $arDesempeno->setCargoRel($arEmpleado->getCargoRel());
                        $em->persist($arDesempeno);
                        $arDesempenosConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto();
                        $arDesempenosConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConcepto')->findAll();
                        foreach ($arDesempenosConceptos as $arDesempenoConcepto) {
                            $arDesempenoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle();
                            $arDesempenoDetalle->setDesempenoRel($arDesempeno);
                            $arDesempenoDetalle->setDesempenoConceptoRel($arDesempenoConcepto);
                            $em->persist($arDesempenoDetalle);
                        }
                        $em->flush();
                        if($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_desempeno_nuevo', array('codigoDesempeno' => 0 )));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_desempeno_lista'));
                        }                        
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo", $this);
                    }                    
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                }                
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Desempenos:nuevo.html.twig', array(
            'arDesempeno' => $arDesempeno,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/desempeno/detalle/{codigoDesempeno}", name="brs_rhu_desempeno_detalle")
     */
    public function detalleAction($codigoDesempeno) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arDesempeno = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno();
        $arDesempeno = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempeno')->find($codigoDesempeno);
        $form = $this->formularioDetalle($arDesempeno);
        $form->handleRequest($request);
        $intTotalAreaProfesional = 0;
        $intTotalCompromiso = 0;
        $intTotalUrbanidad = 0;
        $intTotalValores = 0;
        $intTotalOrientacionCliente = 0;
        $intTotalOrientacionResultados = 0;
        $intTotalConstruccionMantenimientoRelaciones = 0;
        $total = 0;
        $subTotal1 = 0;
        $subTotal2 = 0;
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if($arDesempeno->getEstadoAutorizado() == 0) {
                    if ($arDesempeno->getInconsistencia() == 0){
                        $arDesempeno->setEstadoAutorizado(1);
                        $em->persist($arDesempeno);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_desempeno_detalle', array('codigoDesempeno' => $codigoDesempeno)));
                    }else{
                        $objMensaje->Mensaje("error", "revise por favor las respuestas, tiene inconsistencias, de clic en actualizar para ver inconsistencias", $this);
                    }
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if($arDesempeno->getEstadoAutorizado() == 1) {
                    $arDesempeno->setEstadoAutorizado(0);
                    $em->persist($arDesempeno);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_desempeno_detalle', array('codigoDesempeno' => $codigoDesempeno)));
                }
            }
            if($form->get('BtnImprimir')->isClicked()) {
                if($arDesempeno->getEstadoAutorizado() == 1) {
                    if ($arDesempeno->getInconsistencia() == 0){
                        $objFormato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDesempenos();
                        $objFormato->Generar($this, $codigoDesempeno);
                    } else {
                        $objMensaje->Mensaje("error", "revise por favor las respuestas, tiene inconsistencias, de clic en actualizar para ver inconsistencias", $this);
                    }
                }    
            }
            if($form->get('BtnCerrar')->isClicked()) {
                if($arDesempeno->getEstadoAutorizado() == 1) {
                    $arDesempeno->setEstadoCerrado(1);
                    $em->persist($arDesempeno);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_desempeno_detalle', array('codigoDesempeno' => $codigoDesempeno)));
                }
            }            
            
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                if($arDesempeno->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados as $codigoDesempenoDetalle) {
                            $arDesempenoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle();
                            $arDesempenoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoDetalle')->find($codigoDesempenoDetalle);                        
                            $em->remove($arDesempenoDetalle);
                        }
                        $em->flush();                    
                    } 
                    return $this->redirect($this->generateUrl('brs_rhu_desempeno_detalle', array('codigoDesempeno' => $codigoDesempeno)));
                }    
            } 
            if ($form->get('BtnActualizarDetalle')->isClicked()) {                
                    $arrControles = $request->request->All();
                    $intIndice = 0;
                    $control = true;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        $arDesempenoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle();
                        $arDesempenoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoDetalle')->find($intCodigo);
                        
                            if($arrControles['TxtSiempre'.$intCodigo] != "") {
                                if ($arrControles['TxtSiempre'.$intCodigo] <= 1){
                                    $intSiempre = $arrControles['TxtSiempre'.$intCodigo];
                                    $arDesempenoDetalle->setSiempre($intSiempre);
                                    $em->persist($arDesempenoDetalle);
                                } else {
                                    $objMensaje->Mensaje("error", "La opcion de respuesta debe ser cero(0) o uno (1) en el registro " .$intCodigo."", $this);
                                    $control = false;
                                    $arDesempeno->setInconsistencia(true);
                                }
                            } else {
                                $objMensaje->Mensaje("error", "Hay opciones sin respuesta en el registro " .$intCodigo."", $this);
                                $control = false;
                                $arDesempeno->setInconsistencia(true);
                            }
                            
                            if($arrControles['TxtCasiSiempre'.$intCodigo] != "") {
                                if ($arrControles['TxtCasiSiempre'.$intCodigo] <= 1){
                                    $intCasiSiempre = $arrControles['TxtCasiSiempre'.$intCodigo];
                                    $arDesempenoDetalle->setCasiSiempre($intCasiSiempre);
                                    $em->persist($arDesempenoDetalle);
                                } else {
                                    $objMensaje->Mensaje("error", "La opcion de respuesta debe ser cero(0) o uno (1) en el registro " .$intCodigo."", $this);
                                    $control = false;
                                    $arDesempeno->setInconsistencia(true);
                                }
                            } else {
                                $objMensaje->Mensaje("error", "Hay opciones sin respuesta en el registro " .$intCodigo."", $this);
                                $control = false;
                                $arDesempeno->setInconsistencia(true);
                            }
                            if($arrControles['TxtAlgunasVeces'.$intCodigo] != "") {
                                if ($arrControles['TxtAlgunasVeces'.$intCodigo] <= 1){
                                    $intAlgunasVeces = $arrControles['TxtAlgunasVeces'.$intCodigo];
                                    $arDesempenoDetalle->setAlgunasVeces($intAlgunasVeces);
                                    $em->persist($arDesempenoDetalle);
                                } else {
                                    $objMensaje->Mensaje("error", "La opcion de respuesta debe ser cero(0) o uno (1) en el registro " .$intCodigo."", $this);
                                    $control = false;
                                    $arDesempeno->setInconsistencia(true);
                                }
                            } else {
                                $objMensaje->Mensaje("error", "Hay opciones sin respuesta en el registro " .$intCodigo."", $this);
                                $control = false;
                                $arDesempeno->setInconsistencia(true);
                            }
                            if($arrControles['TxtCasiNunca'.$intCodigo] != "") {
                                if ($arrControles['TxtCasiNunca'.$intCodigo] <= 1){
                                    $intCasiNunca = $arrControles['TxtCasiNunca'.$intCodigo];
                                    $arDesempenoDetalle->setCasiNunca($intCasiNunca);
                                    $em->persist($arDesempenoDetalle);
                                } else {
                                    $objMensaje->Mensaje("error", "La opcion de respuesta debe ser cero(0) o uno (1) en el registro " .$intCodigo."", $this);
                                    $control = false;
                                    $arDesempeno->setInconsistencia(true);
                                }
                            } else {
                                $objMensaje->Mensaje("error", "Hay opciones sin respuesta en el registro " .$intCodigo."", $this);
                                $control = false;
                                $arDesempeno->setInconsistencia(true);
                            }
                            if($arrControles['TxtNunca'.$intCodigo] != "") {
                                if ($arrControles['TxtNunca'.$intCodigo] <= 1){
                                    $intNunca = $arrControles['TxtNunca'.$intCodigo];
                                    $arDesempenoDetalle->setNunca($intNunca);
                                    $em->persist($arDesempenoDetalle);
                                } else {
                                    $objMensaje->Mensaje("error", "La opcion de respuesta debe ser cero(0) o uno (1) en el registro " .$intCodigo."", $this);
                                    $control = false;
                                    $arDesempeno->setInconsistencia(true);
                                }
                            } else {
                                $objMensaje->Mensaje("error", "Hay opciones sin respuesta en el registro " .$intCodigo."", $this);
                                $control = false;
                                $arDesempeno->setInconsistencia(true);
                            }
                            if ($intSiempre + $intCasiSiempre + $intAlgunasVeces + $intCasiNunca + $intNunca == 0){
                                $objMensaje->Mensaje("error", "Debe ingresar una opcion de respuesta en el registro " .$intCodigo."" , $this);
                                $control = false;
                                $arDesempeno->setInconsistencia(true);
                            }
                            if ($intSiempre + $intCasiSiempre + $intAlgunasVeces + $intCasiNunca + $intNunca > 1){
                                $objMensaje->Mensaje("error", "Debe ingresar solo un uno (1) en la opcion de respuesta en el registro " .$intCodigo."" , $this);
                                $control = false;
                                $arDesempeno->setInconsistencia(true);
                            } 
                    }
                    if ($control == true){
                        $arDesempeno->setInconsistencia(0);
                    }
                    $total = 0;
                    $arDesempeno->setSubTotal1(0);
                    $arDesempeno->setSubTotal2(0);
                    $arDesempeno->setTotalDesempeno(0);
                    $em->persist($arDesempeno);
                    $em->persist($arDesempenoDetalle);
                    $em->flush();
                    $strObservaciones = $arrControles['TextareaObservaciones'];
                    $strAspectosMejorar = $arrControles['TextareaAspectosMejorar'];
                    $arDesempeno->setObservaciones($strObservaciones);
                    $arDesempeno->setAspectosMejorar($strAspectosMejorar);
                    $intSiempreAreaProfesional = 0;
                    $intCasiSiempreAreaProfesional = 0;
                    $intAlgunasVecesAreaProfesional = 0;
                    $intCasiNuncaAreaProfesional = 0;
                    $intNuncaAreaProfesional = 0;
                    $intPreguntasAreaProfesional = 0;
                    $intSiempreCompromiso = 0;
                    $intCasiSiempreCompromiso = 0;
                    $intAlgunasVecesCompromiso = 0;
                    $intCasiNuncaCompromiso = 0;
                    $intNuncaCompromiso = 0;
                    $intPreguntasCompromiso = 0;
                    $intSiempreUrbanidad = 0;
                    $intCasiSiempreUrbanidad = 0;
                    $intAlgunasVecesUrbanidad = 0;
                    $intCasiNuncaUrbanidad = 0;
                    $intNuncaUrbanidad = 0;
                    $intPreguntasUrbanidad = 0;
                    $intSiempreValores = 0;
                    $intCasiSiempreValores = 0;
                    $intAlgunasVecesValores = 0;
                    $intCasiNuncaValores = 0;
                    $intNuncaValores = 0;
                    $intPreguntasValores = 0;
                    $intSiempreOrientacionCliente = 0;
                    $intCasiSiempreOrientacionCliente = 0;
                    $intAlgunasVecesOrientacionCliente = 0;
                    $intCasiNuncaOrientacionCliente = 0;
                    $intNuncaOrientacionCliente = 0;
                    $intPreguntasOrientacionCliente = 0;
                    $intSiempreOrientacionResultados = 0;
                    $intCasiSiempreOrientacionResultados = 0;
                    $intAlgunasVecesOrientacionResultados = 0;
                    $intCasiNuncaOrientacionResultados = 0;
                    $intNuncaOrientacionResultados = 0;
                    $intPreguntasOrientacionResultados = 0;
                    $intSiempreConstruccionMantenimientoRelaciones = 0;
                    $intCasiSiempreConstruccionMantenimientoRelaciones = 0;
                    $intAlgunasVecesConstruccionMantenimientoRelaciones = 0;
                    $intCasiNuncaConstruccionMantenimientoRelaciones = 0;
                    $intNuncaConstruccionMantenimientoRelaciones = 0;
                    $intPreguntasConstruccionMantenimientoRelaciones = 0;
                    $arDesempenoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle();
                    $arDesempenoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoDetalle')->findBy(array('codigoDesempenoFk' => $codigoDesempeno));
                    if ($control == true){
                    foreach ($arDesempenoDetalles as $arDesempenoDetalle) {
                        if ($arDesempenoDetalle->getDesempenoConceptoRel()->getCodigoDesempenoConceptoTipoFk() == 1){
                            $intSiempreAreaProfesional = $intSiempreAreaProfesional + $arDesempenoDetalle->getSiempre();
                            $intCasiSiempreAreaProfesional = $intCasiSiempreAreaProfesional + $arDesempenoDetalle->getCasiSiempre();
                            $intAlgunasVecesAreaProfesional = $intAlgunasVecesAreaProfesional + $arDesempenoDetalle->getalgunasVeces();
                            $intCasiNuncaAreaProfesional = $intCasiNuncaAreaProfesional + $arDesempenoDetalle->getCasiNunca();
                            $intNuncaAreaProfesional = $intNuncaAreaProfesional + $arDesempenoDetalle->getNunca();
                            $intPreguntasAreaProfesional ++;
                        }
                        if ($arDesempenoDetalle->getDesempenoConceptoRel()->getCodigoDesempenoConceptoTipoFk() == 2){
                            $intSiempreCompromiso = $intSiempreCompromiso + $arDesempenoDetalle->getSiempre();
                            $intCasiSiempreCompromiso = $intCasiSiempreCompromiso + $arDesempenoDetalle->getCasiSiempre();
                            $intAlgunasVecesCompromiso = $intAlgunasVecesCompromiso + $arDesempenoDetalle->getalgunasVeces();
                            $intCasiNuncaCompromiso = $intCasiNuncaCompromiso + $arDesempenoDetalle->getCasiNunca();
                            $intNuncaCompromiso = $intNuncaCompromiso + $arDesempenoDetalle->getNunca();
                            $intPreguntasCompromiso ++;
                        }
                        if ($arDesempenoDetalle->getDesempenoConceptoRel()->getCodigoDesempenoConceptoTipoFk() == 3){
                            $intSiempreUrbanidad = $intSiempreUrbanidad + $arDesempenoDetalle->getSiempre();
                            $intCasiSiempreUrbanidad = $intCasiSiempreUrbanidad + $arDesempenoDetalle->getCasiSiempre();
                            $intAlgunasVecesUrbanidad = $intAlgunasVecesUrbanidad + $arDesempenoDetalle->getalgunasVeces();
                            $intCasiNuncaUrbanidad = $intCasiNuncaUrbanidad + $arDesempenoDetalle->getCasiNunca();
                            $intNuncaUrbanidad = $intNuncaUrbanidad + $arDesempenoDetalle->getNunca();
                            $intPreguntasUrbanidad ++;
                        }
                        if ($arDesempenoDetalle->getDesempenoConceptoRel()->getCodigoDesempenoConceptoTipoFk() == 4){
                            $intSiempreValores = $intSiempreValores + $arDesempenoDetalle->getSiempre();
                            $intCasiSiempreValores = $intCasiSiempreValores + $arDesempenoDetalle->getCasiSiempre();
                            $intAlgunasVecesValores = $intAlgunasVecesValores + $arDesempenoDetalle->getalgunasVeces();
                            $intCasiNuncaValores = $intCasiNuncaValores + $arDesempenoDetalle->getCasiNunca();
                            $intNuncaValores = $intNuncaValores + $arDesempenoDetalle->getNunca();
                            $intPreguntasValores ++;
                        }
                        if ($arDesempenoDetalle->getCodigoDesempenoConceptoFk() == 26 || $arDesempenoDetalle->getCodigoDesempenoConceptoFk() == 27 || $arDesempenoDetalle->getCodigoDesempenoConceptoFk() == 28 || $arDesempenoDetalle->getCodigoDesempenoConceptoFk() == 29){
                            $intSiempreOrientacionCliente = $intSiempreOrientacionCliente + $arDesempenoDetalle->getSiempre();
                            $intCasiSiempreOrientacionCliente = $intCasiSiempreOrientacionCliente + $arDesempenoDetalle->getCasiSiempre();
                            $intAlgunasVecesOrientacionCliente = $intAlgunasVecesOrientacionCliente + $arDesempenoDetalle->getalgunasVeces();
                            $intCasiNuncaOrientacionCliente = $intCasiNuncaOrientacionCliente + $arDesempenoDetalle->getCasiNunca();
                            $intNuncaOrientacionCliente = $intNuncaOrientacionCliente + $arDesempenoDetalle->getNunca();
                            $intPreguntasOrientacionCliente ++;
                        }
                        if ($arDesempenoDetalle->getCodigoDesempenoConceptoFk() == 30 || $arDesempenoDetalle->getCodigoDesempenoConceptoFk() == 31 || $arDesempenoDetalle->getCodigoDesempenoConceptoFk() == 32){
                            $intSiempreOrientacionResultados = $intSiempreOrientacionResultados + $arDesempenoDetalle->getSiempre();
                            $intCasiSiempreOrientacionResultados = $intCasiSiempreOrientacionResultados + $arDesempenoDetalle->getCasiSiempre();
                            $intAlgunasVecesOrientacionResultados = $intAlgunasVecesOrientacionResultados + $arDesempenoDetalle->getalgunasVeces();
                            $intCasiNuncaOrientacionResultados = $intCasiNuncaOrientacionResultados + $arDesempenoDetalle->getCasiNunca();
                            $intNuncaOrientacionResultados = $intNuncaOrientacionResultados + $arDesempenoDetalle->getNunca();
                            $intPreguntasOrientacionResultados ++;
                        }
                        if ($arDesempenoDetalle->getCodigoDesempenoConceptoFk() == 33 || $arDesempenoDetalle->getCodigoDesempenoConceptoFk() == 34 || $arDesempenoDetalle->getCodigoDesempenoConceptoFk() == 35){
                            $intSiempreConstruccionMantenimientoRelaciones = $intSiempreConstruccionMantenimientoRelaciones + $arDesempenoDetalle->getSiempre();
                            $intCasiSiempreConstruccionMantenimientoRelaciones = $intCasiSiempreConstruccionMantenimientoRelaciones + $arDesempenoDetalle->getCasiSiempre();
                            $intAlgunasVecesConstruccionMantenimientoRelaciones = $intAlgunasVecesConstruccionMantenimientoRelaciones + $arDesempenoDetalle->getalgunasVeces();
                            $intCasiNuncaConstruccionMantenimientoRelaciones = $intCasiNuncaConstruccionMantenimientoRelaciones + $arDesempenoDetalle->getCasiNunca();
                            $intNuncaConstruccionMantenimientoRelaciones = $intNuncaConstruccionMantenimientoRelaciones + $arDesempenoDetalle->getNunca();
                            $intPreguntasConstruccionMantenimientoRelaciones ++;
                        }
                    }
                    //area profesional
                    $intTotalAreaProfesional = ($intSiempreAreaProfesional * 5) / $intPreguntasAreaProfesional;
                    $intTotalAreaProfesional += ($intCasiSiempreAreaProfesional * 4) / $intPreguntasAreaProfesional;
                    $intTotalAreaProfesional += ($intAlgunasVecesAreaProfesional * 3) / $intPreguntasAreaProfesional;
                    $intTotalAreaProfesional += ($intCasiNuncaAreaProfesional * 2 ) / $intPreguntasAreaProfesional;
                    $intTotalAreaProfesional += ($intNuncaAreaProfesional * 1) / $intPreguntasAreaProfesional;
                    $intTotalAreaProfesional = round(($intTotalAreaProfesional * 100) / 5);
                    //compromiso
                    $intTotalCompromiso = ($intSiempreCompromiso * 5) / $intPreguntasCompromiso;
                    $intTotalCompromiso += ($intCasiSiempreCompromiso * 4) / $intPreguntasCompromiso;
                    $intTotalCompromiso += ($intAlgunasVecesCompromiso * 3) / $intPreguntasCompromiso;
                    $intTotalCompromiso += ($intCasiNuncaCompromiso * 2 ) / $intPreguntasCompromiso;
                    $intTotalCompromiso += ($intNuncaCompromiso * 1) / $intPreguntasCompromiso;
                    $intTotalCompromiso = round(($intTotalCompromiso * 100) / 5);
                    //urbanidad
                    $intTotalUrbanidad = ($intSiempreUrbanidad * 5) / $intPreguntasUrbanidad;
                    $intTotalUrbanidad += ($intCasiSiempreUrbanidad * 4) / $intPreguntasUrbanidad;
                    $intTotalUrbanidad += ($intAlgunasVecesUrbanidad * 3) / $intPreguntasUrbanidad;
                    $intTotalUrbanidad += ($intCasiNuncaUrbanidad * 2 ) / $intPreguntasUrbanidad;
                    $intTotalUrbanidad += ($intNuncaUrbanidad * 1) / $intPreguntasUrbanidad;
                    $intTotalUrbanidad = round(($intTotalUrbanidad * 100) / 5);
                    //Valores
                    $intTotalValores = ($intSiempreValores * 5) / $intPreguntasValores;
                    $intTotalValores += ($intCasiSiempreValores * 4) / $intPreguntasValores;
                    $intTotalValores += ($intAlgunasVecesValores * 3) / $intPreguntasValores;
                    $intTotalValores += ($intCasiNuncaValores * 2 ) / $intPreguntasValores;
                    $intTotalValores += ($intNuncaValores * 1) / $intPreguntasValores;
                    $intTotalValores = round(($intTotalValores * 100) / 5);
                    //OrientacionCliente
                    $intTotalOrientacionCliente = ($intSiempreOrientacionCliente * 5) / $intPreguntasOrientacionCliente;
                    $intTotalOrientacionCliente += ($intCasiSiempreOrientacionCliente * 4) / $intPreguntasOrientacionCliente;
                    $intTotalOrientacionCliente += ($intAlgunasVecesOrientacionCliente * 3) / $intPreguntasOrientacionCliente;
                    $intTotalOrientacionCliente += ($intCasiNuncaOrientacionCliente * 2 ) / $intPreguntasOrientacionCliente;
                    $intTotalOrientacionCliente += ($intNuncaOrientacionCliente * 1) / $intPreguntasOrientacionCliente;
                    $intTotalOrientacionCliente = round(($intTotalOrientacionCliente * 100) / 5);
                    //OrientacionResultados
                    $intTotalOrientacionResultados = ($intSiempreOrientacionResultados * 5) / $intPreguntasOrientacionResultados;
                    $intTotalOrientacionResultados += ($intCasiSiempreOrientacionResultados * 4) / $intPreguntasOrientacionResultados;
                    $intTotalOrientacionResultados += ($intAlgunasVecesOrientacionResultados * 3) / $intPreguntasOrientacionResultados;
                    $intTotalOrientacionResultados += ($intCasiNuncaOrientacionResultados * 2 ) / $intPreguntasOrientacionResultados;
                    $intTotalOrientacionResultados += ($intNuncaOrientacionResultados * 1) / $intPreguntasOrientacionResultados;
                    $intTotalOrientacionResultados = round(($intTotalOrientacionResultados * 100) / 5);
                    //ConstruccionMantenimientoRelaciones
                    $intTotalConstruccionMantenimientoRelaciones = ($intSiempreConstruccionMantenimientoRelaciones * 5) / $intPreguntasConstruccionMantenimientoRelaciones;
                    $intTotalConstruccionMantenimientoRelaciones += ($intCasiSiempreConstruccionMantenimientoRelaciones * 4) / $intPreguntasConstruccionMantenimientoRelaciones;
                    $intTotalConstruccionMantenimientoRelaciones += ($intAlgunasVecesConstruccionMantenimientoRelaciones * 3) / $intPreguntasConstruccionMantenimientoRelaciones;
                    $intTotalConstruccionMantenimientoRelaciones += ($intCasiNuncaConstruccionMantenimientoRelaciones * 2 ) / $intPreguntasConstruccionMantenimientoRelaciones;
                    $intTotalConstruccionMantenimientoRelaciones += ($intNuncaConstruccionMantenimientoRelaciones * 1) / $intPreguntasConstruccionMantenimientoRelaciones;
                    $intTotalConstruccionMantenimientoRelaciones = round(($intTotalConstruccionMantenimientoRelaciones * 100) / 5);
                    $arDesempeno = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno();
                    $arDesempeno = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempeno')->find($codigoDesempeno);
                    $arDesempeno->setAreaProfesional($intTotalAreaProfesional);
                    $arDesempeno->setCompromiso($intTotalCompromiso);
                    $arDesempeno->setUrbanidad($intTotalUrbanidad);
                    $arDesempeno->setValores($intTotalValores);
                    $arDesempeno->setOrientacionCliente($intTotalOrientacionCliente);
                    $arDesempeno->setOrientacionResultados($intTotalOrientacionResultados);
                    $arDesempeno->setConstruccionMantenimientoRelaciones($intTotalConstruccionMantenimientoRelaciones);
                    $subTotal1 = round(($arDesempeno->getAreaProfesional() + $arDesempeno->getCompromiso() + $arDesempeno->getUrbanidad() + $arDesempeno->getValores()) / 4);
                    $subTotal2 = round(($arDesempeno->getOrientacionCliente() + $arDesempeno->getOrientacionResultados() + $arDesempeno->getConstruccionMantenimientoRelaciones()) / 3);
                    $total = ($subTotal1 + $subTotal2) / 2;
                    $arDesempeno->setSubTotal1(round($subTotal1));
                    $arDesempeno->setSubTotal2(round($subTotal2));
                    $arDesempeno->setTotalDesempeno(round($total));
                    $em->persist($arDesempeno);
                    $em->flush();
                    }
            }  
        }          
            $arDesempenosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle();
            $arDesempenosDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoDetalle')->ordenarPreguntasTipo($codigoDesempeno);
            $arDesempenosDetalles = $paginator->paginate($arDesempenosDetalles, $this->get('request')->query->get('page', 1),100);    
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Desempenos:detalle.html.twig', array(
                        'arDesempenosDetalles' => $arDesempenosDetalles,
                        'arDesempeno' => $arDesempeno,
                        'form' => $form->createView()
                    ));
    }
    
    /**
     * @Route("/rhu/desempeno/detalle/nuevo/{codigoDesempeno}", name="brs_rhu_desempeno_detalle_nuevo")
     */
    public function detalleNuevoAction($codigoDesempeno) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arDesempenoConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConcepto')->findAll();
        $arDesempeno = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno();
        $arDesempeno = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempeno')->find($codigoDesempeno);
        $form = $this->createFormBuilder()
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnAgregar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoDesempenoConcepto) {
                        $arDesempenoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConcepto')->find($codigoDesempenoConcepto);
                        $arDesempenoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle();
                        $arDesempenoDetalle->setDesempenoConceptoRel($arDesempenoConcepto);
                        $arDesempenoDetalle->setDesempenoRel($arDesempeno);
                        $em->persist($arDesempenoDetalle);
                    }
                    $em->flush();
                }
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Desempenos:detalleNuevo.html.twig', array(
            'arDesempenoConceptos' => $arDesempenoConceptos,
            'arDesempeno' => $arDesempeno,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/desempeno/detalle/nuevo/comentario/{codigoDesempeno}", name="brs_rhu_desempeno_detalle_nuevo_observacion")
     */
    public function detalleNuevoObservacionAction($codigoDesempeno) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arDesempeno = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno();
        $arDesempeno = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempeno')->find($codigoDesempeno);
        $form = $this->createForm(new RhuDesempenoObservacionesType, $arDesempeno);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arDesempeno = $form->getData();
            $em->persist($arDesempeno);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Desempenos:detalleNuevoObservacion.html.twig', array(
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/desempeno/detalle/nuevo/aspectosMejorar/{codigoDesempeno}", name="brs_rhu_desempeno_detalle_nuevo_aspectosMejorar")
     */
    public function detalleNuevoAspectosMejorarAction($codigoDesempeno) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arDesempeno = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno();
        $arDesempeno = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempeno')->find($codigoDesempeno);
        $form = $this->createForm(new RhuDesempenoAspectosMejorarType, $arDesempeno);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arDesempeno = $form->getData();
            $em->persist($arDesempeno);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Desempenos:detalleNuevoAspectosMejorar.html.twig', array(
            'form' => $form->createView()));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempeno')->listaDql();
    }
    
    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
    }

    private function formularioLista() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
                ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle($arDesempeno) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonCerrar = array('label' => 'Cerrar', 'disabled' => false);        
        $arrBotonActualizarDetalle = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);

        if($arDesempeno->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonEliminarDetalle['disabled'] = true;
            $arrBotonActualizarDetalle['disabled'] = true;
            if($arDesempeno->getEstadoCerrado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonCerrar['disabled'] = true;
            }
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
            $arrBotonCerrar['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnCerrar', 'submit', $arrBotonCerrar)
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)
                    ->add('BtnEliminarDetalle', 'submit', $arrBotonEliminarDetalle)
                    ->add('BtnActualizarDetalle', 'submit', $arrBotonActualizarDetalle)
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
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'FECHA')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'CARGO')
                    ->setCellValue('F1', 'AUTORIZADO');
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arDesempenos = $query->getResult();
        foreach ($arDesempenos as $arDesempeno) {
            if ($arDesempeno->getEstadoAutorizado() == 1){
                $autorizado = "SI";
            }else{
                $autorizado = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arDesempeno->getCodigoDesempenoPk())
                    ->setCellValue('B' . $i, $arDesempeno->getFecha()->format('Y-m-d'))
                    ->setCellValue('C' . $i, $arDesempeno->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arDesempeno->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arDesempeno->getCargoRel()->getNombre())
                    ->setCellValue('F' . $i, $autorizado);
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('Desempenos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Desempenos.xlsx"');
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
