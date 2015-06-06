<?php

namespace Brasa\AdministracionDocumentalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
class ArchivosController extends Controller
{
    public function listaAction($codigoArchivoTipo, $numero) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');
        $query = $em->createQuery($em->getRepository('BrasaAdministracionDocumentalBundle:AdArchivo')->listaDQL($codigoArchivoTipo, $numero));        
        $arArchivos = $paginator->paginate($query, $request->query->get('page', 1), 50);                               
        return $this->render('BrasaAdministracionDocumentalBundle:Archivos:lista.html.twig', array(
            'arArchivos' => $arArchivos,
            'codigoArchivoTipo' => $codigoArchivoTipo,
            'numero' => $numero,
            ));
    }  
    
    public function cargarAction($codigoArchivoTipo, $numero) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa'); 
        $form = $this->createFormBuilder()
            ->add('attachment', 'file')
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {                
                $objArchivo = $form['attachment']->getData();
                if($objArchivo->getClientOriginalExtension() == 'pdf') {
                    $arArchivo = new \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo();                    
                    $arArchivo->setNombre($objArchivo->getClientOriginalName());
                    $arArchivo->setExtensionOriginal($objArchivo->getClientOriginalExtension());                
                    $arArchivo->setTamano($objArchivo->getClientSize());
                    $arArchivo->setTipo($objArchivo->getClientMimeType());
                    $arArchivo->setArchivoTipoRel($em->getRepository('BrasaAdministracionDocumentalBundle:AdArchivoTipo')->find($codigoArchivoTipo));               
                    $arArchivo->setNumero($numero);
                    $arDirectorio = $em->getRepository('BrasaAdministracionDocumentalBundle:AdDirectorio')->devolverDirectorio();
                    $arArchivo->setDirectorioRel($arDirectorio);                    
                    $em->persist($arArchivo);
                    $em->flush();
                    $strDestino = $arDirectorio->getRutaPrincipal() . $arDirectorio->getNumero() . "/";
                    $strArchivo = $arArchivo->getCodigoArchivoPk() . "_" . $objArchivo->getClientOriginalName();
                    $form['attachment']->getData()->move($strDestino, $strArchivo);                    
                    return $this->redirect($this->generateUrl('brs_ad_archivos_lista', array('codigoArchivoTipo' => $codigoArchivoTipo, 'numero' => $numero)));
                } else {
                    $objMensaje->Mensaje("error", "Solo se pueden cargar arhivos pdf", $this);
                }
            }                                   
        }         
        return $this->render('BrasaAdministracionDocumentalBundle:Archivos:cargar.html.twig', array(
            'form' => $form->createView()
            ));
    }    
    
    public function descargarAction($codigoArchivo) {
        $em = $this->getDoctrine()->getManager();
        $arArchivo = new \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo();
        $arArchivo = $em->getRepository('BrasaAdministracionDocumentalBundle:AdArchivo')->find($codigoArchivo);
        $strRuta = $arArchivo->getDirectorioRel()->getRutaPrincipal() . $arArchivo->getDirectorioRel()->getNumero() . "/" . $arArchivo->getCodigoArchivoPk() . "_" . $arArchivo->getNombre();
        // Generate response
        $response = new Response();
        
        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', $arArchivo->getTipo());
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $arArchivo->getNombre() . '";');
        $response->headers->set('Content-length', $arArchivo->getTamano());        
        $response->sendHeaders();
        $response->setContent(readfile($strRuta));        
              
    }
}
