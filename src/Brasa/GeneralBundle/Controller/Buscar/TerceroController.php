<?php

namespace Brasa\GeneralBundle\Controller\Buscar;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class TerceroController extends Controller
{
    var $strDqlLista = "";     
    var $strNit = "";
    var $strNombre = "";
    
    /**
     * @Route("/general/buscar/tercero/{campoCodigo}/{campoNombre}", name="brs_gen_buscar_tercero")
     */
    public function listaAction($campoCodigo, $campoNombre) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arTerceros = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaGeneralBundle:Buscar:tercero.html.twig', array(
            'arTerceros' => $arTerceros,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,            
            'form' => $form->createView()
            ));
    }        
    
    private function listar() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaGeneralBundle:GenTercero')->listaDQL(
                $this->strNombre,                
                $this->strNit   
                ); 
    }       
    
    private function formularioLista() {                
        $form = $this->createFormBuilder()                                                
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtNit', 'text', array('label'  => 'Identificacion','data' => $this->strNit))                            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }           

    private function filtrarLista($form) {
        $this->strNit = $form->get('TxtNit')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
    }    
          
}
