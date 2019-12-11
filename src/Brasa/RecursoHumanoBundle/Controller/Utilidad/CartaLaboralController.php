<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class CartaLaboralController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/utilidades/carta/laboral", name="brs_rhu_utilidades_carta_laboral")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 83)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {            
            if($request->request->get('OpImprimir')) {
                $codigoContrato = $request->request->get('OpImprimir');
                $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
                /*if ($arContrato->getEstadoActivo() == 1){
                    $codigoCartaTipo = 5; //vigente
                } else {
                    $codigoCartaTipo = 6; //retirado
                }*/
                $codigoCartaTipo = 6; 
                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                if ($arConfiguracion->getCodigoFormatoCarta() == 0){
                    $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCarta();
                    $objFormatoCarta->Generar($this, $codigoCartaTipo, date('Y-m-d'), "", $codigoContrato,"","","","","","");
                }
                if ($arConfiguracion->getCodigoFormatoCarta() == 1){
                    $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCarta1teg();
                    $objFormatoCarta->Generar($this, $codigoCartaTipo, date('Y-m-d'), "", $codigoContrato,"","","","","","");
                }
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
            } 
        }       
                
        $arContratos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Cartas/CartaLaboral:lista.html.twig', array(
            'arContratos' => $arContratos,
            'form' => $form->createView()));
    }         
    
    /**
     * @Route("/rhu/utilidades/carta/laboralparametros/{codigoContrato}", name="brs_rhu_utilidades_carta_laboralparametros")
     */
    public function cartarLaboralParametrosAction(Request $request, $codigoContrato) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        //Inicio promedio mensual
        $intPeriodo = 0;
        $strPeriodo = $arContrato->getCentroCostoRel()->getPeriodoPagoRel()->getNombre();
        if ($strPeriodo == "SEMANAL"){
            $intPeriodo = 4;
        }
        if ($strPeriodo == "DECADAL"){
            $intPeriodo = 3;
        }
        if ($strPeriodo == "CATORCENAL"){
            $intPeriodo = 2;
        }
        if ($strPeriodo == "QUINCENAL"){
            $intPeriodo = 2;
        }
        if ($strPeriodo == "MENSUAL"){
            $intPeriodo = 1;
        }
        $arSuplementario = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->tiempoSuplementarioCartaLaboral($intPeriodo, $arContrato->getCodigoContratoPk());            
        $floPromedioSalario = $arSuplementario;
        $arNoPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->noPrestacionalCartaLaboral($intPeriodo, $arContrato->getCodigoContratoPk());            
        $floNoPrestacional = $arNoPrestacional;
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_utilidades_carta_laboralparametros', array('codigoContrato' => $codigoContrato)))                        
            ->add('salario', 'checkbox', array('required'  => false, 'data' => true))                 
            ->add('promedioIbp', 'checkbox', array('required'  => false, 'data' => false))                 
            ->add('promedioNoPrestacional', 'checkbox', array('required'  => false, 'data' => false))                 
            ->add('salarioSugerido', 'text', array('required' => false))
            ->add('promedioIbpSugerido', 'text', array('required' => false))
            ->add('promedioNoPrestacionalSugerido', 'text', array('required' => false))    
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
           
        if ($form->isValid()) {
            $codigoCartaTipo = 5;
            $salario = $form->get('salario')->getData();
            $promedioIbp = $form->get('promedioIbp')->getData();
            $promedioNoPrestacional = $form->get('promedioNoPrestacional')->getData();
            $salarioSugerido = $form->get('salarioSugerido')->getData();
            $promedioIbpSugerido = $form->get('promedioIbpSugerido')->getData();
            $promedioNoPrestacionalSugerido = $form->get('promedioNoPrestacionalSugerido')->getData();            
            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
            if ($arConfiguracion->getCodigoFormatoCarta() == 0){
                $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCarta();
                $objFormatoCarta->Generar($this, $codigoCartaTipo, date('Y-m-d'), "", $codigoContrato,$salario,$promedioIbp,$promedioNoPrestacional,$salarioSugerido,$promedioIbpSugerido,$promedioNoPrestacionalSugerido);
            }
            if ($arConfiguracion->getCodigoFormatoCarta() == 1){
                $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCarta1teg();
                $objFormatoCarta->Generar($this, $codigoCartaTipo, date('Y-m-d'), "", $codigoContrato,$salario,$promedioIbp,$promedioNoPrestacional,$salarioSugerido,$promedioIbpSugerido,$promedioNoPrestacionalSugerido);
            }
            //return $this->redirect($this->generateUrl('brs_rhu_utilidades_carta_laboral'));
            
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Carta:CartaLaboralParametros.html.twig', array(
            'arContrato' => $arContrato,
            'promedioIbp' => $floPromedioSalario,
            'promedioNoPrestacional' => $floNoPrestacional,
            'form' => $form->createView()
        ));
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();        
        $session = $this->get('session');
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
        
        $form = $this->createFormBuilder()                        
            ->add('centroCostoRel', 'entity', $arrayPropiedadesCentroCosto)
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                            
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                                            
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->get('session');
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->listaContratosCartaLaboralDQL(
            $session->get('filtroCodigoCentroCosto'),
            $session->get('filtroIdentificacion')
            );  
    }         
    
    private function filtrarLista($form, Request $request) {
        $session = $this->get('session');        
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);                
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
    }         
    
}
