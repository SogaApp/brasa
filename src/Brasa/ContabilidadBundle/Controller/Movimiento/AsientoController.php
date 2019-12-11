<?php

namespace Brasa\ContabilidadBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\ContabilidadBundle\Form\Type\CtbAsientoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\ContabilidadBundle\Form\Type\CtbAsientoDetalleType;


class AsientoController extends Controller
{
    var $strListaDql = "";
    var $numeroAsiento = "";
    var $codigoComprobante = "";
    var $fechaDesde = "";
    var $fechaHasta = "";
    
    /**
     * @Route("/ctb/movimientos/asientos/lista", name="brs_ctb_mov_asientos_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 113, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $this->listar();
        if($form->isValid()) {
            $arrSelecionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnEliminar')->isClicked()){
                if(count($arrSelecionados) > 0) {
                    foreach ($arrSelecionados AS $codigoAsiento) {
                        $arAsiento = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();
                        $arAsiento = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->find($codigoAsiento);
                        if ($arAsiento->getEstadoAutorizado() == 1){
                            $objMensaje->Mensaje("error", "El asiento ". $codigoAsiento ." ya fue autorizada, no se pude eliminar", $this);
                        }else{
                            $arRegistros = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->validarAsientosDQL($codigoAsiento);
                            if ($arRegistros){
                                $objMensaje->Mensaje("error", "El asiento ". $codigoAsiento ." contiene registros asignados", $this);
                            }else{
                                $em->remove($arAsiento);
                            }
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_lista'));
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

        $arAsientos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaContabilidadBundle:Movimientos/Asientos:lista.html.twig', array('arAsientos' => $arAsientos, 'form' => $form->createView()));
    }

    /**
     * @Route("/ctb/movimientos/asientos/nuevo/{codigoAsiento}", name="brs_ctb_mov_asientos_nuevo")
     */
    public function nuevoAction($codigoAsiento = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arAsiento = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();    
        if($codigoAsiento != 0) {
            $arAsiento = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->find($codigoAsiento);
        } else {
            $arAsiento->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(new CtbAsientoType, $arAsiento);         
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arAsiento = $form->getData();
            $em->persist($arAsiento);
            $em->flush();
            if($form->get('BtnGuardarNuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_nuevo', array('codigoAsiento' => 0 )));
            } else {
                if ($codigoAsiento == 0){
                    return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle', array('codigoAsiento' => $arAsiento->getCodigoAsientoPk() )));
                }else {
                    return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_lista'));
                }    
            }                                    
        }

        return $this->render('BrasaContabilidadBundle:Movimientos/Asientos:nuevo.html.twig', array(
            'arAsiento' => $arAsiento,
            'form' => $form->createView()));
    }

    /**
     * @Route("/ctb/movimientos/asientos/detalle/{codigoAsiento}", name="brs_ctb_mov_asientos_detalle")
     */
    public function detalleAction($codigoAsiento) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arAsiento = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();
        $arAsiento = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->find($codigoAsiento);
        $arAsientoDetalleNew = new \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle();
        $arAsientoTipoNew = new \Brasa\ContabilidadBundle\Entity\CtbAsientoTipo();
        $arCuentaNew = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
        $arTerceroNew = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
        $arCentroCostoNew = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();
        $strCuenta = "";
        $strIdentificacion = "";
        $StrCentroCosto = "";
        $form = $this->formularioDetalle($arAsiento);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if ($arAsiento->getEstadoAutorizado() == 0){
                    if($em->getRepository('BrasaContabilidadBundle:CtbAsientoDetalle')->numeroRegistros($codigoAsiento) > 0) {
                        $autorizar = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->Autorizar($codigoAsiento);
                        if ($autorizar != ""){
                            $objMensaje->Mensaje("error", $autorizar, $this);
                        }
                        return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle', array('codigoAsiento' => $codigoAsiento)));
                    } else {
                                $objMensaje->Mensaje('error', 'Debe adicionar detalles al asiento', $this);
                            }    
                } else {
                    $objMensaje->Mensaje("error", "El asiento ya esta autorizado", $this);
                }
                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if ($arAsiento->getEstadoAutorizado() == 1){
                    $autorizar = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->DesAutorizar($codigoAsiento);
                    if ($autorizar != ""){
                        $objMensaje->Mensaje("error", $autorizar, $this);
                    }
                    return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle', array('codigoAsiento' => $codigoAsiento)));
                } else {
                    $objMensaje->Mensaje("error", "El asiento ya esta desautorizado", $this);
                }
                
            }
            
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                $arrSelecionados = $request->request->get('ChkSeleccionar');
                if ($arAsiento->getEstadoAutorizado() == 0){
                    if(count($arrSelecionados) > 0) {
                        foreach ($arrSelecionados AS $codigoAsientoDetalle) {
                            $arAsientoDetalle = new \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle();
                            $arAsientoDetalle = $em->getRepository('BrasaContabilidadBundle:CtbAsientoDetalle')->find($codigoAsientoDetalle);
                            if ($arAsientoDetalle->getEstadoAutorizado() == 1){
                                $objMensaje->Mensaje("error", "El asiento detalle ". $codigoAsientoDetalle ." ya fue autorizada, no se pude eliminar", $this);
                            }else{
                                $em->remove($arAsientoDetalle);
                            }
                        }
                        $em->flush();
                        $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->liquidar($codigoAsiento);
                        return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle', array('codigoAsiento' => $codigoAsiento)));
                    }
                } else {
                    $objMensaje->Mensaje("error", "No se puede eliminar el registro, el asiento esta autorizado", $this);
                }
                
            }
            
            if($form->get('BtnAgregar')->isClicked()) {
                $arrControlesNew = $request->request->All();
                if ($arAsiento->getEstadoAutorizado() == 0 ){
                    $strIdentificacion = $arrControlesNew['TxtNumeroIdentificacionNew'];
                    $strCuenta = $arrControlesNew['TxtCuentaNew'];
                    $StrCentroCosto = $arrControlesNew['TxtCodigoCentroCostoNew'];
                    $intIndice = 0;
                    if ($strCuenta != null){
                        $arAsientoNew = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();
                        $arAsientoNew = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->find($codigoAsiento);
                        $arTerceroNew = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $strIdentificacion));
                        $registrosNew = count($arTerceroNew);
                        $arCuentaNew = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($strCuenta);
                        $arAsientoTipoNew = $em->getRepository('BrasaContabilidadBundle:CtbAsientoTipo')->find($arrControlesNew['CboCodigoAsientoTipoNew']);
                        $arCentroCostoNew = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find($arrControlesNew['TxtCodigoCentroCostoNew']);
                        $arAsientoDetalleNew->setAsientoRel($arAsientoNew);
                        $arAsientoDetalleNew->setCuentaRel($arCuentaNew);
                        $arAsientoDetalleNew->setAsientoTipoRel($arAsientoTipoNew);
                        $arAsientoDetalleNew->setTerceroRel($arTerceroNew);
                        $arAsientoDetalleNew->setCentroCostoRel($arCentroCostoNew);
                        $arAsientoDetalleNew->setDocumentoReferente($arrControlesNew['TxtDocumentoReferenteNew']);
                        $arAsientoDetalleNew->setSoporte($arrControlesNew['TxtSoporteNew']);
                        $arAsientoDetalleNew->setPlazo($arrControlesNew['TxtPlazoNew']);
                        $arAsientoDetalleNew->setValorBase($arrControlesNew['TxtValorBaseNew']);
                        if ($arAsientoTipoNew->getCodigoAsientoTipoPk() == 1){
                            $arAsientoDetalleNew->setDebito($arrControlesNew['TxtDebitoNew']);
                        }else{
                            $arAsientoDetalleNew->setCredito($arrControlesNew['TxtCreditoNew']);
                        }
                        //$arAsientoDetalleNew->setDebito($arrControlesNew['TxtDebitoNew']);
                        //$arAsientoDetalleNew->setCredito($arrControlesNew['TxtCreditoNew']);
                        $arAsientoDetalleNew->setDescripcion($arrControlesNew['TxtDescripcionNew']);
                        if ($arCuentaNew == null){
                            $objMensaje->Mensaje("error", "El sistema no agregó el registro , por que el número de cuenta ". $strCuenta . " no existe en el sistema" , $this);
                        }else {
                            if ($registrosNew == 0 ){
                                if ($strIdentificacion == null){
                                    if ($arCuentaNew->getExigeNit() == 1){
                                        $objMensaje->Mensaje("error", "El sistema no agregó el registro , por que la cuenta exige número de identificación" , $this);
                                    }else{

                                        $em->persist($arAsientoDetalleNew);
                                        $em->flush();
                                        $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->liquidar($codigoAsiento);
                                        return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle', array('codigoAsiento' => $codigoAsiento)));
                                    }
                                }else {
                                    $objMensaje->Mensaje("error", "El sistema no agregó el registro , por que el número de identificación ". $strIdentificacion . " no existe en el sistema" , $this);
                                }
                            }else {
                                if ($arCentroCostoNew == null){
                                    if ($arrControlesNew['TxtCodigoCentroCostoNew'] != null){
                                        $objMensaje->Mensaje("error", "El sistema no agregó el registro , por que el centro de costo ". $arrControlesNew['TxtCodigoCentroCostoNew'] . " no existe en el sistema" , $this);
                                    }else{ 
                                        $em->persist($arAsientoDetalleNew);
                                        $em->flush();
                                        $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->liquidar($codigoAsiento);
                                        return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle', array('codigoAsiento' => $codigoAsiento)));
                                    }
                                }else {
                                    $em->persist($arAsientoDetalleNew);
                                    $em->flush();
                                    $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->liquidar($codigoAsiento);
                                    return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle', array('codigoAsiento' => $codigoAsiento)));
                                }        
                            }   
                        }
                    } else {
                        $objMensaje->Mensaje("error", "El registro no tiene número de cuenta", $this);
                    }
                } else {
                   $objMensaje->Mensaje("error", "No se puede agregar registro, el asiento ha sido autorizado", $this); 
                }
                
            }
            
            if($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                if ($arAsiento->getEstadoAutorizado() == 0){
                    if (isset($arrControles['LblCodigoGuia'])){
                        foreach ($arrControles['LblCodigoGuia'] as $intCodigo) {
                            $arAsientoDetalle = new \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle();
                            $arAsientoDetalle = $em->getRepository('BrasaContabilidadBundle:CtbAsientoDetalle')->find($intCodigo);
                            $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arrControles['TxtNumeroIdentificacion'.$intCodigo]));
                            $registros = count($arTercero);
                            $arCuenta = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
                            $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arrControles['TxtCuenta'.$intCodigo]);
                            $arAsientoTipo = new \Brasa\ContabilidadBundle\Entity\CtbAsientoTipo();
                            $arAsientoTipo = $em->getRepository('BrasaContabilidadBundle:CtbAsientoTipo')->find($arrControles['CboCodigoAsientoTipo'.$intCodigo]);
                            $arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();
                            $arCentroCosto = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find($arrControles['TxtCodigoCentroCosto'.$intCodigo]);
                            $arAsientoDetalle->setCuentaRel($arCuenta);
                            $arAsientoDetalle->setAsientoTipoRel($arAsientoTipo);
                            $arAsientoDetalle->setTerceroRel($arTercero);
                            $arAsientoDetalle->setCentroCostoRel($arCentroCosto);
                            $arAsientoDetalle->setDocumentoReferente($arrControles['TxtDocumentoReferente'.$intCodigo]);
                            $arAsientoDetalle->setSoporte($arrControles['TxtSoporte'.$intCodigo]);
                            $arAsientoDetalle->setPlazo($arrControles['TxtPlazo'.$intCodigo]);
                            $arAsientoDetalle->setValorBase($arrControles['TxtValorBase'.$intCodigo]);
                            if ($arAsientoTipo->getCodigoAsientoTipoPk() == 1){
                                $arAsientoDetalle->setDebito($arrControles['TxtDebito'.$intCodigo]);
                                $arAsientoDetalle->setCredito(0);
                            }else{
                                $arAsientoDetalle->setCredito($arrControles['TxtCredito'.$intCodigo]);
                                $arAsientoDetalle->setDebito(0);
                            }
                            //$arAsientoDetalle->setDebito($arrControles['TxtDebito'.$intCodigo]);
                            //$arAsientoDetalle->setCredito($arrControles['TxtCredito'.$intCodigo]);
                            $arAsientoDetalle->setDescripcion($arrControles['TxtDescripcion'.$intCodigo]);
                            if ($arCuenta == null){
                                $objMensaje->Mensaje("error", "El sistema no modificó el registro ".$intCodigo.", por que el número de cuenta ". $arrControles['TxtCuenta'.$intCodigo] . " no existe" , $this);
                            }else {
                                if ($registros == 0 ){
                                    if ($arrControles['TxtNumeroIdentificacion'.$intCodigo] == null){
                                        if ($arCuenta->getExigeNit() == 1){
                                            $objMensaje->Mensaje("error", "El sistema no modificó el registro ".$intCodigo.", por que la cuenta exige número de identificación" , $this);
                                        } else {

                                            $em->persist($arAsientoDetalle);
                                        }
                                    }else {
                                        $objMensaje->Mensaje("error", "El sistema no modificó el registro ".$intCodigo.", por que el número de identificación ". $arrControles['TxtNumeroIdentificacion'.$intCodigo] . " no existe" , $this);
                                    }
                                }else {
                                    if ($arCentroCosto == null){
                                        if ($arrControles['TxtCodigoCentroCosto'.$intCodigo] != null){
                                            $objMensaje->Mensaje("error", "El sistema no modificó el registro ".$intCodigo.", por que el centro de costo ". $arrControles['TxtCodigoCentroCosto'.$intCodigo] . " no existe" , $this);
                                        }else {

                                            $em->persist($arAsientoDetalle);
                                        }    
                                    }else {

                                        $em->persist($arAsientoDetalle);
                                    }    
                                }    
                            }   
                        }
                    }
                } else {
                    $objMensaje->Mensaje("error", "No se puede actualizar los detalles, el asiento ha sido autorizado", $this);
                }
                $em->flush();
                $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->liquidar($codigoAsiento);
                return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle', array('codigoAsiento' => $codigoAsiento)));
            }
            
            if($form->get('BtnImprimir')->isClicked()) {
                if ($arAsiento->getEstadoAutorizado() == 1){
                    $objFormatoAsientoDetalle = new \Brasa\ContabilidadBundle\Formatos\FormatoAsientoDetalle();
                    $objFormatoAsientoDetalle->Generar($this, $codigoAsiento);
                } else {
                    $objMensaje->Mensaje("error", "No se puede imprimir, el asiento no esta autorizado", $this);
                }    
            }
        }
        $arAsientoDetalles = new \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle();
        $arAsientoDetalles = $em->getRepository('BrasaContabilidadBundle:CtbAsientoDetalle')->FindBy(array('codigoAsientoFk' => $codigoAsiento));
        return $this->render('BrasaContabilidadBundle:Movimientos/Asientos:detalle.html.twig', array(
                    'arAsiento' => $arAsiento,
                    'arAsientoDetalles' => $arAsientoDetalles,
                    'arAsientoDetalleNew' => $arAsientoDetalleNew,
                    'arAsientoTipoNew' => $arAsientoTipoNew,
                    'identificacion' => $strIdentificacion,
                    'cuenta' => $strCuenta,
                    'centroCosto' => $StrCentroCosto,
                    'form' => $form->createView()
                    ));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();        
        $this->strListaDql = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->listaDQL(
                $this->numeroAsiento,
                $this->codigoComprobante,
                $this->fechaDesde,
                $this->fechaHasta
                );
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
                    
        $form = $this->createFormBuilder()
            ->add('TxtNumeroAsiento', 'text', array('label'  => 'Número asiento'))
            ->add('comprobanteRel', 'entity', array(
                'class' => 'BrasaContabilidadBundle:CtbComprobante',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrar ($form) {
        $em = $this->getDoctrine()->getManager();
        $arComprobante = $form->get('comprobanteRel')->getData();
        if ($arComprobante == null){
            $intComprobante = "";
        }else {
            $intComprobante = $arComprobante->getCodigoComprobantePk();
        }
        $this->numeroAsiento = $form->get('TxtNumeroAsiento')->getData();
        $this->codigoComprobante = $intComprobante;
        $this->fechaDesde = $form->get('fechaDesde')->getData();
        $this->fechaHasta = $form->get('fechaHasta')->getData();
    }    
    
    private function formularioDetalle($ar) {
        $arrBotonAgregar = array('label' => 'Agregar', 'disabled' => false);
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        //$arrBotonAprobar = array('label' => 'Aprobar', 'disabled' => false);
        //$arrBotonAnular = array('label' => 'Anular', 'disabled' => false);
        //$arrBotonContabilizar = array('label' => 'Contabilizar', 'disabled' => false);
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {            
            
            $arrBotonAgregar['disabled'] = true;
            $arrBotonAutorizar['disabled'] = true;            
            $arrBotonEliminarDetalle['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
            //$arrBotonContabilizar['disabled'] = true;
            //$arrBotonAprobar['disabled'] = true;
            //$arrBotonAnular['disabled'] = true;
        }
        if($ar->getEstadoAprobado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;            
            //$arrBotonAprobar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
            //$arrBotonContabilizar['disabled'] = true;
            //$arrBotonAnular['disabled'] = true;
        }
        $form = $this->createFormBuilder()    
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)            
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    /*->add('BtnAnular', 'submit', $arrBotonAnular)
                    ->add('BtnContabilizar', 'submit', $arrBotonContabilizar)
                    ->add('BtnAprobar', 'submit', $arrBotonAprobar)*/
                    ->add('BtnEliminarDetalle', 'submit', $arrBotonEliminarDetalle)
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                    ->add('BtnAgregar', 'submit', $arrBotonAgregar)
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
                            ->setCellValue('B1', 'CÓDIGO COMPROBANTE')
                            ->setCellValue('C1', 'COMPROBANTE')
                            ->setCellValue('D1', 'NÚMERO ASIENTO')
                            ->setCellValue('E1', 'SOPORTE')
                            ->setCellValue('F1', 'FECHA')
                            ->setCellValue('G1', 'TOTAL DÉBITO')
                            ->setCellValue('H1', 'TOTAL CRÉDITO')
                            ->setCellValue('I1', 'AUTORIZADO');
                $i = 2;
                $query = $em->createQuery($this->strListaDql);
                $arAsientos = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();
                $arAsientos = $query->getResult();

                foreach ($arAsientos as $arAsiento) {
                    if ($arAsiento->getEstadoAutorizado() == 1){
                        $autorizado = "SI";
                    }else {
                        $autorizado = "NO";
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arAsiento->getCodigoAsientoPk())
                            ->setCellValue('B' . $i, $arAsiento->getCodigoComprobanteFk())
                            ->setCellValue('C' . $i, $arAsiento->getComprobanteRel()->getNombre())
                            ->setCellValue('D' . $i, $arAsiento->getNumeroAsiento())
                            ->setCellValue('E' . $i, $arAsiento->getSoporte())
                            ->setCellValue('F' . $i, $arAsiento->getFecha()->format('Y-m-d'))
                            ->setCellValue('G' . $i, $arAsiento->getTotalDebito())
                            ->setCellValue('H' . $i, $arAsiento->getTotalCredito())
                            ->setCellValue('I' . $i, $autorizado);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Asientos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Asientos.xlsx"');
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
