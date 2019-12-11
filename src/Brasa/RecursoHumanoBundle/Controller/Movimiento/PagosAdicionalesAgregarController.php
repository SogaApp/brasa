<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class PagosAdicionalesAgregarController extends Controller
{
    /**
     * @Route("/rhu/pagos/adicionales/agregar/valoradicional/{tipo}/{modalidad}/{periodo}", name="brs_rhu_pagos_adicionales_agregar_valoradicional")
     */
    public function valorAdicionalAction($tipo, $modalidad, $periodo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $intTipoAdicional = $tipo;
        $form = $this->createFormBuilder()
            ->add('pagoConceptoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) use($intTipoAdicional) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', $intTipoAdicional)
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('TxtValor', 'number', array('required' => true))                             
            ->add('TxtDetalle', 'text', array('required' => false))            
            ->add('aplicarDiaLaborado', 'choice', array('choices' => array('0' => 'NO', '1' => 'SI')))                
            ->add('aplicarDiaLaboradoSinDescanso', 'choice', array('choices' => array('0' => 'NO', '1' => 'SI')))                                            
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->add('BtnGuardaryNuevo', 'submit', array('label'  => 'Guardar y nuevo',))
            ->getForm();
                    
        $form->handleRequest($request);
        if($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
            if ($arrControles['form_txtNumeroIdentificacion'] == ""){
                $objMensaje->Mensaje("error", "Digite el número de identificación", $this);
            }else {
                if ($arEmpleado == null){
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                } else {
                    if ($arEmpleado->getCodigoContratoActivoFk() != null){
                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoActivoFk());
                    }else {                       
                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoUltimoFk());
                    }
                    if ($arContrato == null){
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato", $this);
                    } else {
                        if($form->get('TxtValor')->getData() != "" && $form->get('TxtValor')->getData() != 0) {                    
                            $boolError = FALSE;
                             $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                             $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
                            if($arPagoConcepto->getPrestacional() == 0 && $tipo == 1) {
                                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);        
                                $floSalario = $arEmpleado->getVrSalario();
                                $floVrDia = ($floSalario / 30);
                                if($arEmpleado->getCodigoContratoActivoFk()) {
                                    $codigoContrato = $arEmpleado->getCodigoContratoActivoFk();
                                } else {
                                    $codigoContrato = $arEmpleado->getCodigoContratoUltimoFk();
                                }
                                $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                                $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);                                
                                $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arContrato->getCodigoCentroCostoFk());        
                                $strPeriodoPago = $arCentroCosto->getPeriodoPagoRel()->getNombre();
                                if ($strPeriodoPago == "MENSUAL"){
                                    $intDias = 30;
                                }
                                if ($strPeriodoPago == "QUINCENAL"){
                                    $intDias = 15;
                                }
                                if ($strPeriodoPago == "CATORCENAL"){
                                    $intDias = 14;
                                }
                                if ($strPeriodoPago == "DECADAL"){
                                    $intDias = 10;
                                }
                                if ($strPeriodoPago == "SEMANAL"){
                                    $intDias = 7;
                                }
                                /*$floSalarioEmpleado = $floVrDia * $intDias;
                                $floBonificacionMaxima = $floSalarioEmpleado * ($arConfiguracion->getPorcentajeBonificacionNoPrestacional() / 100);
                                $floBonificacionNoPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->bonificacionNoPrestacional($arEmpleado->getCodigoEmpleadoPk(), 0);                                
                                $floBonificacion = $form->get('TxtValor')->getData();
                                $floBonificacionTotal = $floBonificacionNoPrestacional + $floBonificacion;
                                if($floBonificacionTotal > $floBonificacionMaxima) {
                                    //echo "La bonificacion NO PRESTACIONAL no puede superar: " . $floBonificacionMaxima . " ya tiene bonificaciones por:" . $floBonificacionNoPrestacional;
                                    $objMensaje->Mensaje("error", "La bonificacion NO PRESTACIONAL no puede superar: " . $floBonificacionMaxima . " ya tiene bonificaciones por:" . $floBonificacionNoPrestacional, $this);
                                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_valoradicional', array('tipo' => $tipo) ));
                                    $boolError = TRUE;
                                }  
                                 * 
                                 */                                                                      
                            }
                            if($boolError == FALSE) {
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();                     
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setValor($form->get('TxtValor')->getData());                    
                                $arPagoAdicional->setDetalle($form->get('TxtDetalle')->getData());                    
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);                    
                                $arPagoAdicional->setPrestacional($arPagoConcepto->getPrestacional());
                                $arPagoAdicional->setTipoAdicional($tipo);                                
                                $arPagoAdicional->setAplicaDiaLaborado($form->get('aplicarDiaLaborado')->getData());
                                $arPagoAdicional->setAplicaDiaLaboradoSinDescanso($form->get('aplicarDiaLaboradoSinDescanso')->getData());
                                $arPagoAdicional->setCodigoUsuario($arUsuario->getUserName());
                                $arPagoAdicional->setModalidad($modalidad);
                                if($periodo != 0) {
                                    $arPagoAdicionalPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalPeriodo();                                    
                                    $arPagoAdicionalPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicionalPeriodo')->find($periodo);
                                    if($arPagoAdicionalPeriodo) {
                                        $arPagoAdicional->setCodigoPeriodoFk($periodo);   
                                        $arPagoAdicional->setFecha($arPagoAdicionalPeriodo->getFecha());
                                    }                                                                                                            
                                } else {
                                    $arPagoAdicional->setPermanente(1);
                                }
                                $arPagoAdicional->setFechaCreacion(new \DateTime('now'));
                                $arPagoAdicional->setFechaUltimaEdicion(new \DateTime('now'));
                                $em->persist($arPagoAdicional);                                                        
                                $em->flush();
                            }
                            if($form->get('BtnGuardaryNuevo')->isClicked()) {
                                return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_valoradicional', array('tipo' => $tipo, 'modalidad' => $modalidad, 'periodo' => $periodo) ));
                            } else {
                                return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista', array('modalidad' => $modalidad, 'periodo' => $periodo)));
                            }
                        }
                    }                                                                                                                                                      
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:agregarValorAdicional.html.twig', array(                        
            'form' => $form->createView(),
            'tipo' => $tipo,
            'modalidad' => $modalidad,
            'periodo' => $periodo
            ));
    }
    
    /**
     * @Route("/rhu/pagos/adicionales/agregar/valoradicionaleditar/{tipo}/{codigoPagoAdicional}/{modalidad}/{periodo}", name="brs_rhu_pagos_adicionales_agregar_valoradicionaleditar")
     */
    public function valorAdicionalEditarAction($tipo, $codigoPagoAdicional, $modalidad, $periodo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
        $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($arPagoAdicional->getCodigoPagoConceptoFk());
        $codigoAdicionalDetalle = $arPagoConcepto->getTipoAdicional();
        if ($arPagoAdicional->getAplicaDiaLaborado() == 1){
            $intAplicaDiaLaborado = "SI";
        } else {
            $intAplicaDiaLaborado = "NO";
        }
        if ($arPagoAdicional->getAplicaDiaLaborado() == 1){
            $intAplicaDiaLaboradoSinDescanso = "SI";
        } else {
            $intAplicaDiaLaboradoSinDescanso = "NO";
        }                
        $codigoEmpleado = $arPagoAdicional->getCodigoEmpleadoFk();
        $codigoCentroCosto = $arPagoAdicional->getEmpleadoRel()->getCodigoCentroCostoFk();        
        $codigoPagoConcepto = $arPagoAdicional->getCodigoPagoConceptoFk();
        $pagoConcepto = $arPagoAdicional->getPagoConceptoRel()->getNombre();
        $intTipoAdicional = $tipo;
        $aplicaDiaLaborado = $arPagoAdicional->getAplicaDiaLaborado();
        if ($aplicaDiaLaborado == false ){
            $aplicaDiaLaborado = 0;
        } else {
            $aplicaDiaLaborado = 1;
        }
        $aplicaDiaLaboradoSinDescanso = $arPagoAdicional->getAplicaDiaLaboradoSinDescanso();
        if ($aplicaDiaLaboradoSinDescanso == false ){
            $aplicaDiaLaboradoSinDescanso = 0;
        } else {
            $aplicaDiaLaboradoSinDescanso = 1;
        }        
        $arrayPropiedadesPagoConcepto = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) use ($intTipoAdicional) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', $intTipoAdicional)
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true,
                'data' => ""
            ); 
        if($codigoPagoConcepto) {
            $arrayPropiedadesPagoConcepto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $codigoPagoConcepto);
        }                    
        
        $form = $this->createFormBuilder() 
            ->add('empleadoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleado',
                'query_builder' => function (EntityRepository $er) use($codigoEmpleado) {
                    return $er->createQueryBuilder('e')
                    ->where('e.codigoEmpleadoPk = :empleado')
                    ->setParameter('empleado', $codigoEmpleado)
                    ->orderBy('e.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))    
            ->add('pagoConceptoRel', 'entity', $arrayPropiedadesPagoConcepto)               
            ->add('TxtValor', 'number', array('required' => true, 'data' => $arPagoAdicional->getValor()))                             
            ->add('TxtDetalle', 'text', array('required' => false, 'data' => $arPagoAdicional->getDetalle()))
            ->add('aplicarDiaLaborado', 'choice', array('choices' => array($aplicaDiaLaborado => $intAplicaDiaLaborado, '0' => 'NO', '1' => 'SI')))                
            ->add('aplicarDiaLaboradoSinDescanso', 'choice', array('choices' => array($aplicaDiaLaboradoSinDescanso => $intAplicaDiaLaboradoSinDescanso, '0' => 'NO', '1' => 'SI')))                
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->add('BtnGuardaryNuevo', 'submit', array('label'  => 'Guardar y nuevo',))
            ->getForm();
        $form->handleRequest($request);
        
        if ($codigoAdicionalDetalle == 0){
            $objMensaje->Mensaje("error", "El tipo de adicional al pago para el item " . $arPagoConcepto->getNombre() . " en la tabla pago concepto no debe estar en cero (0), 1: bonificación, 2:descuento, 3: comisión, 4: tiempo suplementario!", $this);
        }
        if($form->isValid()) {
            $arrControles = $request->request->All();
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arPagoAdicional->getCodigoEmpleadoFk());        
            if ($codigoCentroCosto == null){
                $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoUltimoFk());
                $codigoCentroCosto = $arContrato->getCodigoCentroCostoFk();
            }
                if($form->get('TxtValor')->getData() != "" && $form->get('TxtValor')->getData() != 0) {                    
                    $boolError = FALSE;
                     $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                     $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
                    if($arPagoConcepto->getPrestacional() == 0 && $tipo == 1) {
                        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);        
                        $floSalario = $arEmpleado->getVrSalario();
                        $floVrDia = ($floSalario / 30);
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);        
                        $strPeriodoPago = $arCentroCosto->getPeriodoPagoRel()->getNombre();
                        if ($strPeriodoPago == "MENSUAL"){
                            $intDias = 30;
                        }
                        if ($strPeriodoPago == "QUINCENAL"){
                            $intDias = 15;
                        }
                        if ($strPeriodoPago == "CATORCENAL"){
                            $intDias = 14;
                        }
                        if ($strPeriodoPago == "DECADAL"){
                            $intDias = 10;
                        }
                        if ($strPeriodoPago == "SEMANAL"){
                            $intDias = 7;
                        }
                        $floSalarioEmpleado = $floVrDia * $intDias;
                        $floBonificacionMaxima = $floSalarioEmpleado * ($arConfiguracion->getPorcentajeBonificacionNoPrestacional() / 100);
                        $floBonificacionNoPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->bonificacionNoPrestacional($arPagoAdicional->getCodigoEmpleadoFk(), 0);                                
                        $floBonificacion = $form->get('TxtValor')->getData();
                        $floBonificacionTotal = $floBonificacionNoPrestacional+ $floBonificacion;                                                                       
                    }
                    if($boolError == FALSE) {
                        $arPagoAdicional->setEmpleadoRel($arEmpleado);
                        $arPagoAdicional->setValor($form->get('TxtValor')->getData());                    
                        $arPagoAdicional->setDetalle($form->get('TxtDetalle')->getData());                    
                        $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);                    
                        $arPagoAdicional->setPrestacional($arPagoConcepto->getPrestacional());
                        $arPagoAdicional->setTipoAdicional($tipo);
                        $arPagoAdicional->setPermanente(1);
                        $arPagoAdicional->setAplicaDiaLaborado($form->get('aplicarDiaLaborado')->getData());
                        $arPagoAdicional->setAplicaDiaLaboradoSinDescanso($form->get('aplicarDiaLaboradoSinDescanso')->getData());
                        $arPagoAdicional->setFechaUltimaEdicion(new \DateTime('now'));
                        $arPagoAdicional->setCodigoUsuarioUltimaEdicion($arUsuario->getUserName());
                        $em->persist($arPagoAdicional);                                                        
                        $em->flush();
                        if($form->get('BtnGuardaryNuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_valoradicional', array('tipo' => $tipo, 'modalidad' => $modalidad) ));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista', array('modalidad' => $modalidad, 'periodo' => $periodo)));
                        }
                    } 
                    
                }                                                                                                                                                                          
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:editarAdicional.html.twig', array(                        
            'form' => $form->createView(),
            'tipo' => $tipo,
            'modalidad' => $modalidad,
            'periodo' => $periodo
            ));
    }
    
    
}
