<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ProcesoCierreAnioController extends Controller
{
    var $strSqlLista = "";

    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $boolError = 0;
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->findBy(array('estadoAutorizado' => 0));
        if(count($arLiquidacion) > 0) {
            $objMensaje->Mensaje("error", "Hay liquidaciones sin autorizar y no se puede efectuar el cierre", $this);
        }
        
        $this->listar();
        if($form->isValid()) {

        }

        $arCierresAnios = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Procesos/CierreAnio:lista.html.twig', array(
            'arCierresAnios' => $arCierresAnios,
            'form' => $form->createView()));
    }

    public function cerrarAction($codigoCierreAnio) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_proceso_cierre_anio_cerrar', array('codigoCierreAnio' => $codigoCierreAnio)))
            ->add('salarioMinimo', 'number')
            ->add('auxilioTransporte', 'number')            
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        $arCierreAnio = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreAnio();
        $arCierreAnio = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreAnio')->find($codigoCierreAnio);                
        
        if ($form->isValid()) {  
            $floSalarioMinimo = $form->get('salarioMinimo')->getData();
            $floAuxilioTransporte = $form->get('auxilioTransporte')->getData();            
            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
            $floSalarioMinimoAnterior = $arConfiguracion->getVrSalario();
                        
            $arCierreAnio = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreAnio();
            $arCierreAnio = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreAnio')->find($codigoCierreAnio);
            $arCierreAnio->setEstadoCerrado(1);
            
            $em->persist($arCierreAnio);
            
            $strFechaCambio = $arConfiguracion->getAnioActual()."/12/30";
            $arContratoMinimos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $strDql = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c WHERE c.estadoActivo = 1 AND c.indefinido = 1 AND c.VrSalario <= " . $arConfiguracion->getVrSalario();
            $query = $em->createQuery($strDql);
            $arContratoMinimos = $query->getResult();                        
            foreach ($arContratoMinimos as $arContratoMinimo){
                $arCambioSalario = new \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario();
                $arCambioSalario->setContratoRel($arContratoMinimo);
                $arCambioSalario->setEmpleadoRel($arContratoMinimo->getEmpleadoRel());
                $arCambioSalario->setFecha(date_create($strFechaCambio));
                $arCambioSalario->setVrSalarioAnterior($floSalarioMinimoAnterior);
                $arCambioSalario->setVrSalarioNuevo($floSalarioMinimo);
                $arCambioSalario->setDetalle('ACTUALIZACION SALARIO MINIMO');
                $em->persist($arCambioSalario);
                $arContratoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                $arContratoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arContratoMinimo->getCodigoContratoPk());
                $arContratoActualizar->setVrSalario($floSalarioMinimo);
                $arContratoActualizar->setVrSalarioPago($floSalarioMinimo);
                if ($arContratoActualizar->getCodigoTipoTiempoFk() == 2){
                    $arContratoActualizar->setVrSalarioPago($floSalarioMinimo / 2);
                }
                $em->persist($arContratoActualizar);
                $arEmpleadoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleadoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContratoMinimo->getCodigoEmpleadoFk());
                $arEmpleadoActualizar->setVrSalario($floSalarioMinimo);
                $em->persist($arEmpleadoActualizar);
            }
            $arConfiguracion->setAnioActual($arConfiguracion->getAnioActual() + 1);
            $arConfiguracion->setVrSalario($floSalarioMinimo);
            $arConfiguracion->setVrAuxilioTransporte($floAuxilioTransporte);
            $em->persist($arConfiguracion);
            $em->flush();            
            return $this->redirect($this->generateUrl('brs_rhu_proceso_cierre_anio'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Procesos/CierreAnio:cerrar.html.twig', array(
            'arCierreAnio' => $arCierreAnio,
            'form' => $form->createView()
        ));
    }

    private function formularioLista() {
        $form = $this->createFormBuilder()
            ->getForm();
        return $form;
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreAnio')->listaDql();
    }

}
