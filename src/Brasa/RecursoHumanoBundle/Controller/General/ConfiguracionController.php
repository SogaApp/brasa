<?php

namespace Brasa\RecursoHumanoBundle\Controller\General;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

/**
 * RhuConfiguracion controller.
 *
 */
class ConfiguracionController extends Controller
{
    /**
     * @Route("/rhu/configuracion/{codigoConfiguracionPk}", name="brs_rhu_configuracion_nomina")
     */
    public function configuracionAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 92)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $arConsecutivo = new \Brasa\RecursoHumanoBundle\Entity\RhuConsecutivo();
        $arConsecutivo = $em->getRepository('BrasaRecursoHumanoBundle:RhuConsecutivo')->findAll();
        $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findAll();
        $arConfiguracionProvision = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionProvision();
        $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findAll();
        $arrayPropiedadesConceptoAuxilioTransporte = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoAuxilioTransporte['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoAuxilioTransporte());

        $arrayPropiedadesConceptoCredito = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoCredito['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoCredito());

        $arrayPropiedadesConceptoSeguro = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoSeguro['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoSeguro());

        $arrayPropiedadesConceptoHoraDiurnaTrabajada = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoHoraDiurnaTrabajada['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoHoraDiurnaTrabajada());

        $arrayPropiedadesConceptoRiesgoProfesional = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('rp')
                ->orderBy('rp.codigoEntidadRiesgoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoRiesgoProfesional['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional", $arConfiguracion->getCodigoEntidadRiesgoFk());

        $arrayPropiedadesConceptoIncapacidad = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoIncapacidad['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoIncapacidad());

        $arrayPropiedadesConceptoRetencionFuente = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoRetencionFuente['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoRetencionFuente());

        $arrayPropiedadesConceptoEntidadExamenIngreso = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuEntidadExamen',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('ee')
                ->orderBy('ee.codigoEntidadExamenPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoEntidadExamenIngreso['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEntidadExamen", $arConfiguracion->getCodigoEntidadExamenIngreso());

        $arrayPropiedadesConceptoEntidadComprobanteNomina = array(
            'class' => 'BrasaContabilidadBundle:CtbComprobante',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                ->orderBy('c.codigoComprobantePk', 'ASC');},
            'property' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoEntidadComprobanteNomina['data'] = $em->getReference("BrasaContabilidadBundle:CtbComprobante", $arConfiguracion->getCodigoComprobantePagoNomina());

        $arrayPropiedadesConceptoEntidadComprobanteBanco = array(
            'class' => 'BrasaContabilidadBundle:CtbComprobante',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                ->orderBy('c.codigoComprobantePk', 'ASC');},
            'property' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoEntidadComprobanteBanco['data'] = $em->getReference("BrasaContabilidadBundle:CtbComprobante", $arConfiguracion->getCodigoComprobantePagoBanco());
        if ($arConfiguracion->getControlPago() == 1){
            $srtControlPago = "SI";
        } else {
            $srtControlPago = "NO";
        }
        $arrayPropiedadesConceptoVacacion = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoVacacion['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoVacacion());

        $formConfiguracion = $this->createFormBuilder()
            ->add('conceptoAuxilioTransporte', 'entity', $arrayPropiedadesConceptoAuxilioTransporte)
            ->add('vrAuxilioTransporte', 'number', array('data' => $arConfiguracion->getVrAuxilioTransporte(), 'required' => true))
            ->add('vrSalario', 'number', array('data' => $arConfiguracion->getVrSalario(), 'required' => true))
            ->add('conceptoCredito', 'entity', $arrayPropiedadesConceptoCredito, array('required' => true))
            ->add('conceptoSeguro', 'entity', $arrayPropiedadesConceptoSeguro, array('required' => true))
            ->add('conceptoHoraDiurnaTrabajada', 'entity', $arrayPropiedadesConceptoHoraDiurnaTrabajada, array('required' => true))
            ->add('conceptoRiesgoProfesional', 'entity', $arrayPropiedadesConceptoRiesgoProfesional, array('required' => true))
            ->add('porcentajePensionExtra', 'number', array('data' => $arConfiguracion->getPorcentajePensionExtra(), 'required' => true))
            ->add('conceptoIncapacidad', 'entity', $arrayPropiedadesConceptoIncapacidad, array('required' => true))
            ->add('porcentajeIva', 'number', array('data' => $arConfiguracion->getPorcentajeIva(), 'required' => true))
            ->add('conceptoRetencionFuente', 'entity', $arrayPropiedadesConceptoRetencionFuente, array('required' => true))
            ->add('porcentajeBonificacionNoPrestacional', 'number', array('data' => $arConfiguracion->getPorcentajeBonificacionNoPrestacional(), 'required' => true))
            ->add('edadMinimaEmpleado', 'number', array('data' => $arConfiguracion->getEdadMinimaEmpleado(), 'required' => true))
            ->add('entidadExamenIngreso', 'entity', $arrayPropiedadesConceptoEntidadExamenIngreso, array('required' => true))
            ->add('comprobantePagoNomina', 'entity', $arrayPropiedadesConceptoEntidadComprobanteNomina, array('required' => true))
            ->add('comprobantePagoBanco', 'entity', $arrayPropiedadesConceptoEntidadComprobanteBanco, array('required' => true))
            ->add('controlPago', 'choice', array('choices'   => array($arConfiguracion->getControlPago() => $srtControlPago, '1' => 'SI', '0' => 'NO')))
            ->add('prestacionesPorcentajeCesantias', 'number', array('data' => $arConfiguracion->getPrestacionesPorcentajeCesantias(), 'required' => true))
            ->add('prestacionesPorcentajeInteresesCesantias', 'number', array('data' => $arConfiguracion->getPrestacionesPorcentajeInteresesCesantias(), 'required' => true))
            ->add('prestacionesPorcentajeVacaciones', 'number', array('data' => $arConfiguracion->getPrestacionesPorcentajeVacaciones(), 'required' => true))
            ->add('prestacionesPorcentajePrimas', 'number', array('data' => $arConfiguracion->getPrestacionesPorcentajePrimas(), 'required' => true))
            ->add('aportesPorcentajeCaja', 'number', array('data' => $arConfiguracion->getAportesPorcentajeCaja(), 'required' => true))
            ->add('aportesPorcentajeVacaciones', 'number', array('data' => $arConfiguracion->getAportesPorcentajeVacaciones(), 'required' => true))
            ->add('tipoBasePagoVacaciones', 'choice', array('choices' => array('1' => 'SALARIO', '2' => 'SALARIO PRESTACIONAL', '3' => 'SALARIO+RECARGOS NOCTURNOS', '0' => 'SIN ASIGNAR'), 'data' => $arConfiguracion->getTipoBasePagoVacaciones()))
            ->add('tipoPlanillaSso', 'choice', array('choices' => array('S' => 'SUCURSAL', 'U' => 'UNICA'), 'data' => $arConfiguracion->getTipoPlanillaSso()))
            ->add('cuentaNominaPagar', 'number', array('data' => $arConfiguracion->getCuentaNominaPagar(), 'required' => true))
            ->add('cuentaPago', 'number', array('data' => $arConfiguracion->getCuentaPago(), 'required' => true))
            ->add('conceptoVacacion', 'entity', $arrayPropiedadesConceptoVacacion, array('required' => true))
            ->add('afectaVacacionesParafiscales', 'checkbox', array('data' => $arConfiguracion->getAfectaVacacionesParafiscales(), 'required' => false))
            ->add('guardar', 'submit', array('label' => 'Actualizar'))
            //->add('guardarProvision', 'submit', array('label' => 'Actualizar'))
            ->getForm();
        $formConfiguracion->handleRequest($request);
        if ($formConfiguracion->isValid()) {
            $controles = $request->request->get('form');
            $codigoConceptoAuxilioTransporte = $controles['conceptoAuxilioTransporte'];
            $ValorAuxilioTransporte = $controles['vrAuxilioTransporte'];
            $porcentajePensionExtra = $controles['porcentajePensionExtra'];
            $cuentaNominaPagar = $controles['cuentaNominaPagar'];
            $cuentaPago = $controles['cuentaPago'];
            $ValorSalario = $controles['vrSalario'];
            $codigoConceptoCredito = $controles['conceptoCredito'];
            $codigoConceptoIncapacidad = $controles['conceptoIncapacidad'];
            $codigoConceptoSeguro = $controles['conceptoSeguro'];
            $codigoConceptoHoraDiurnaTrabajada = $controles['conceptoHoraDiurnaTrabajada'];
            $codigoConceptoRiesgoProfesional = $controles['conceptoRiesgoProfesional'];
            $codigoConceptoRetencionFuente = $controles['conceptoRetencionFuente'];
            $porcentajeIva = $controles['porcentajeIva'];
            $porcentajeBonificacionNoPrestacional = $controles['porcentajeBonificacionNoPrestacional'];
            $edadMinimaEmpleado = $controles['edadMinimaEmpleado'];
            $entidadExamenIngreso = $controles['entidadExamenIngreso'];
            $comprobantePagoNomina = $controles['comprobantePagoNomina'];
            $comprobantePagoBanco = $controles['comprobantePagoBanco'];
            $controlPago = $controles['controlPago'];
            $prestacionesPorcentajeCesantias = $controles['prestacionesPorcentajeCesantias'];
            $prestacionesPorcentajeInteresesCesantias = $controles['prestacionesPorcentajeInteresesCesantias'];
            $prestacionesPorcentajeVacaciones = $controles['prestacionesPorcentajeVacaciones'];
            $prestacionesPorcentajePrimas = $controles['prestacionesPorcentajePrimas'];
            $aportesPorcentajeCaja = $controles['aportesPorcentajeCaja'];
            $aportesPorcentajeVacaciones = $controles['aportesPorcentajeVacaciones'];
            $tipoBasePagoVacaciones = $controles['tipoBasePagoVacaciones'];
            $codigoConceptoVacacion = $controles['conceptoVacacion'];
            //$afectaVacacionesParafiscales = $controles['afectaVacacionesParafiscales'];
            $tipoPlanillaSso = $controles['tipoPlanillaSso'];
            // guardar la tarea en la base de datos
            $arConfiguracion->setCodigoAuxilioTransporte($codigoConceptoAuxilioTransporte);
            $arConfiguracion->setVrAuxilioTransporte($ValorAuxilioTransporte);
            $arConfiguracion->setPorcentajePensionExtra($porcentajePensionExtra);
            $arConfiguracion->setPorcentajeIva($porcentajeIva);
            $arConfiguracion->setVrSalario($ValorSalario);
            $arConfiguracion->setCodigoCredito($codigoConceptoCredito);
            $arConfiguracion->setCodigoIncapacidad($codigoConceptoIncapacidad);
            $arConfiguracion->setCodigoSeguro($codigoConceptoSeguro);
            $arConfiguracion->setCodigoHoraDiurnaTrabajada($codigoConceptoHoraDiurnaTrabajada);
            $arConfiguracion->setCodigoEntidadRiesgoFk($codigoConceptoRiesgoProfesional);
            $arConfiguracion->setCodigoRetencionFuente($codigoConceptoRetencionFuente);
            $arConfiguracion->setPorcentajeBonificacionNoPrestacional($porcentajeBonificacionNoPrestacional);
            $arConfiguracion->setEdadMinimaEmpleado($edadMinimaEmpleado);
            $arConfiguracion->setCodigoEntidadExamenIngreso($entidadExamenIngreso);
            $arConfiguracion->setCodigoComprobantePagoNomina($comprobantePagoNomina);
            $arConfiguracion->setCodigoComprobantepagoBanco($comprobantePagoBanco);
            $arConfiguracion->setControlPago($controlPago);
            $arConfiguracion->setPrestacionesPorcentajeCesantias($prestacionesPorcentajeCesantias);
            $arConfiguracion->setPrestacionesPorcentajeInteresesCesantias($prestacionesPorcentajeInteresesCesantias);
            $arConfiguracion->setPrestacionesPorcentajeVacaciones($prestacionesPorcentajeVacaciones);
            $arConfiguracion->setPrestacionesPorcentajePrimas($prestacionesPorcentajePrimas);
            $arConfiguracion->setAportesPorcentajeCaja($aportesPorcentajeCaja);
            $arConfiguracion->setAportesPorcentajeVacaciones($aportesPorcentajeVacaciones);
            $arConfiguracion->setTipoBasePagoVacaciones($tipoBasePagoVacaciones);
            //$arConfiguracion->setAfectaVacacionesParafiscales($afectaVacacionesParafiscales);
            $arConfiguracion->setTipoPlanillaSso($tipoPlanillaSso);
            $arrControles = $request->request->All();
            $intIndiceConsecutivo = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        $arConsecutivo = new \Brasa\RecursoHumanoBundle\Entity\RhuConsecutivo();
                        $arConsecutivo = $em->getRepository('BrasaRecursoHumanoBundle:RhuConsecutivo')->find($intCodigo);
                        if(count($arConsecutivo) > 0) {
                                $intConsecutivo = $arrControles['TxtConsecutivo'.$intCodigo];
                                $arConsecutivo->setConsecutivo($intConsecutivo);
                                $em->persist($arConsecutivo);

                        }
                        $intIndiceConsecutivo++;
                    }

                    //provision
            $intCodigoProvision = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        $arConfiguracionProvision = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionProvision();
                        $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find($intCodigo);
                        if(count($arConfiguracionProvision) > 0) {
                                $intCuenta = $arrControles['TxtCuenta'.$intCodigo];
                                $arConfiguracionProvision->setCodigoCuentaFk($intCuenta);
                                $intTipoCuenta = $arrControles['TxtTipoCuenta'.$intCodigo];
                                $arConfiguracionProvision->setTipoCuenta($intTipoCuenta);
                                $intCuentaOperacion = $arrControles['TxtCuentaOperacion'.$intCodigo];
                                $arConfiguracionProvision->setCodigoCuentaOperacionFk($intCuentaOperacion);
                                $intCuentaComercial = $arrControles['TxtCuentaComercial'.$intCodigo];
                                $arConfiguracionProvision->setCodigoCuentaComercialFk($intCuentaComercial);
                                $em->persist($arConfiguracionProvision);
                        }
                        $intCodigoProvision++;
                    }
                    //fin provision

            $arCuentaNominaPagar = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
            $arCuentaNominaPagar = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuentaNominaPagar);
            if ($arCuentaNominaPagar == null){
                $objMensaje->Mensaje("error", "La cuenta de contabilidad nomina por pagar " .$cuentaNominaPagar. " no existe", $this);
            } else {
                $arConfiguracion->setCuentaNominaPagar($cuentaNominaPagar);
            }
            $arCuentaPagos = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
            $arCuentaPagos = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuentaPago);
            if ($arCuentaPagos == null){
                $objMensaje->Mensaje("error", "La cuenta de contabilidad pagos " .$cuentaPago. " no existe", $this);
            } else {
                $arConfiguracion->setCuentaPago($cuentaPago);
            }
        $em->persist($arConfiguracion);
        $em->flush();
        return $this->redirect($this->generateUrl('brs_rhu_configuracion_nomina', array('codigoConfiguracionPk' => 1)));

        }
        return $this->render('BrasaRecursoHumanoBundle:Configuracion:Configuracion.html.twig', array(
            'formConfiguracion' => $formConfiguracion->createView(),
            'arConsecutivo' => $arConsecutivo,
            'arPagoConcepto' => $arPagoConcepto,
            'arConfiguracionProvision' => $arConfiguracionProvision
        ));
    }

    /**
     * @Route("/rhu/configuracion/nomina/parametros/prestaciones/", name="brs_rhu_configuracion_nomina_parametros_prestaciones")
     */
    public function configuracionParametrosPrestacionAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 115)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }        
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);        
        $form = $this->createFormBuilder()                     
            ->add('promedioPrimasLaborado', 'checkbox', array('data' => $arConfiguracion->getPromedioPrimasLaborado(), 'required' => false))
            ->add('promedioPrimasLaboradoDias', 'number', array('data' => $arConfiguracion->getPromedioPrimasLaboradoDias(), 'required' => true))                
            ->add('BtnGuardar', 'submit', array('label' => 'Guardar'))            
            ->add('BtnNuevo', 'submit', array('label' => 'Nuevo'))            
            ->add('BtnEliminar', 'submit', array('label' => 'Eliminar'))            
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if($form->get('BtnGuardar')->isClicked()) {                
                $arrControles = $request->request->All();
                $controles = $request->request->get('form');
                foreach ($arrControles['LblCodigo'] as $codigo) {
                    $arParametroPrestacion = new \Brasa\RecursoHumanoBundle\Entity\RhuParametroPrestacion();
                    $arParametroPrestacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuParametroPrestacion')->find($codigo);
                    if($arParametroPrestacion) {
                        if($arrControles['TxtTipo'.$codigo] != "" ) {                                
                            $arParametroPrestacion->setTipo($arrControles['TxtTipo'.$codigo]);                                
                        }
                        if($arrControles['TxtOrden'.$codigo] != "" ) {                                
                            $arParametroPrestacion->setOrden($arrControles['TxtOrden'.$codigo]);                                
                        }                        
                        if($arrControles['TxtDesde'.$codigo] != "" ) {                                
                            $arParametroPrestacion->setDiaDesde($arrControles['TxtDesde'.$codigo]);                                
                        }                        
                        if($arrControles['TxtHasta'.$codigo] != "" ) {                                
                            $arParametroPrestacion->setDiaHasta($arrControles['TxtHasta'.$codigo]);                                
                        }            
                        if($arrControles['TxtPorcentaje'.$codigo] != "" ) {                                
                            $arParametroPrestacion->setPorcentaje($arrControles['TxtPorcentaje'.$codigo]);                                
                        }     
                        if($arrControles['TxtOrigen'.$codigo] != "" ) {                                
                            $arParametroPrestacion->setOrigen($arrControles['TxtOrigen'.$codigo]);                                
                        }                        
                        $em->persist($arParametroPrestacion);
                    }
                } 
                if(isset($controles['promedioPrimasLaborado'])) {
                    $arConfiguracion->setPromedioPrimasLaborado(1);
                } else {
                    $arConfiguracion->setPromedioPrimasLaborado(0);
                }                
                $arConfiguracion->setPromedioPrimasLaboradoDias($controles['promedioPrimasLaboradoDias']);
                $em->flush();
            }
            
            if($form->get('BtnNuevo')->isClicked()) {             
                $arParametroPrestacion = new \Brasa\RecursoHumanoBundle\Entity\RhuParametroPrestacion();
                $em->persist($arParametroPrestacion);
                $em->flush();
            }
            if($form->get('BtnEliminar')->isClicked()) {             
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arParametroPrestacion = new \Brasa\RecursoHumanoBundle\Entity\RhuParametroPrestacion();
                        $arParametroPrestacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuParametroPrestacion')->find($codigo);
                        $em->remove($arParametroPrestacion);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_configuracion_nomina_parametros_prestaciones'));    
                }
            }
        }
        $arParametrosPrestacion = new \Brasa\RecursoHumanoBundle\Entity\RhuParametroPrestacion();
        $arParametrosPrestacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuParametroPrestacion')->findBy(array(), array('tipo' => 'ASC', 'orden' => 'ASC'));
        return $this->render('BrasaRecursoHumanoBundle:Configuracion:ConfiguracionParametrosPrestacion.html.twig', array(
            'form' => $form->createView(),
            'arParametrosPrestacion' => $arParametrosPrestacion
        ));
    }        
    
}
