<?php

namespace Brasa\GeneralBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\GeneralBundle\Form\Type\GenDirectorioType;
use Symfony\Component\HttpFoundation\Response;

class DirectorioController extends Controller
{
    /**
     * @Route("/general/utilidad/gestorarchivo/{codigoDirectorioPadre}", name="brs_gen_utilidad_gestorarchivo")
     */
    public function listaAction($codigoDirectorioPadre = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 72)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnEliminarDirectorio', 'submit', array('label'  => 'Eliminar directorio'))
            ->add('BtnEliminarArchivo', 'submit', array('label'  => 'Eliminar archivo'))
            ->getForm(); 
        $form->handleRequest($request);
        $arDirectorios = new \Brasa\GeneralBundle\Entity\GenDirectorio();
        if($form->isValid()) {
            if($form->get('BtnEliminarDirectorio')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarDirectorio');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoDirectorio) {
                        $arDirectorio = new \Brasa\GeneralBundle\Entity\GenDirectorio();
                        $arDirectorio = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->find($codigoDirectorio);
                        $arDirectorioArchivo = $em->getRepository('BrasaGeneralBundle:GenArchivo')->findBy(array('codigoDirectorioFk' => $codigoDirectorio));
                        if (count($arDirectorioArchivo) == 0){
                            $em->remove($arDirectorio);
                            $em->flush();
                        }
                    }
                    return $this->redirect($this->generateUrl('brs_gen_utilidad_gestorarchivo', array('codigoDirectorioPadre' => $codigoDirectorioPadre)));
                }
            }
            if($form->get('BtnEliminarArchivo')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarArchivo');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoArchivo) {
                        $arArchivo = new \Brasa\GeneralBundle\Entity\GenArchivo();
                        $arArchivo = $em->getRepository('BrasaGeneralBundle:GenArchivo')->find($codigoArchivo);
                        $em->remove($arArchivo);
                        $em->flush();
                        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                        $rutadirectorio = $arArchivo->getCodigoDirectorioFk();
                        if ($rutadirectorio == null){
                            $strRuta = $arConfiguracion->getRutaDirectorio() . $codigoArchivo. "_" .$arArchivo->getNombre();
                        }else{
                            $strRuta = $arConfiguracion->getRutaDirectorio() . $arArchivo->getDirectorioRel()->getRuta(). $codigoArchivo. "_" .$arArchivo->getNombre();
                        }
                        unlink($strRuta);
                    }
                    return $this->redirect($this->generateUrl('brs_gen_utilidad_gestorarchivo', array('codigoDirectorioPadre' => $codigoDirectorioPadre)));
                }
            }
        }
        
        $queryDirectorios = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->findBy(array('codigoDirectorioPadreFk' => $codigoDirectorioPadre));
        $arDirectorios = $paginator->paginate($queryDirectorios, $this->get('request')->query->get('page', 1),40);
        if ($codigoDirectorioPadre == 0){
            $codigo = null;
        }else{
            $codigo = $codigoDirectorioPadre;
        }
        $queryArchivos = $em->getRepository('BrasaGeneralBundle:GenArchivo')->findBy(array('codigoDirectorioFk' => $codigo));
        $arArchivos = $paginator->paginate($queryArchivos, $this->get('request')->query->get('page', 1),40);        
        
        $codigoDirectorioPadreAux = $codigoDirectorioPadre;
        while ($codigoDirectorioPadreAux != null && $codigoDirectorioPadreAux != 0) {
            $arDirectorio = new \Brasa\GeneralBundle\Entity\GenDirectorio();
            $arDirectorio = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->find($codigoDirectorioPadreAux);            
            $codigoDirectorioPadreAux = $arDirectorio->getCodigoDirectorioPadreFk();
            $arrBreadCrumb[] = array('directorio' => $arDirectorio->getNombre(), 'codigo' => $arDirectorio->getCodigoDirectorioPk());
        }
        $arrBreadCrumb[] = array('directorio' => 'INICIO', 'codigo' => 0);
        $arrBreadCrumb = array_reverse($arrBreadCrumb);
        return $this->render('BrasaGeneralBundle:Utilidades/Directorio:lista.html.twig', array(
                    'arDirectorios' => $arDirectorios,
                    'codigoDirectorioPadre' => $codigoDirectorioPadre,
                    'arArchivos' => $arArchivos,
                    'breadCrumb' => $arrBreadCrumb,
                    'form'=> $form->createView()
        ));
    }
    
    /**
     * @Route("/general/utilidad/gestorarchivo/directorio/nuevo/{codigoDirectorio}/{codigoDirectorioPadre}", name="brs_gen_utilidad_gestorarchivo_directorio_nuevo")
     */
    public function nuevoDirectorioAction($codigoDirectorio,$codigoDirectorioPadre) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arDirectorio = new \Brasa\GeneralBundle\Entity\GenDirectorio();
        if ($codigoDirectorio != 0)
        {
            $arDirectorio = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->find($codigoDirectorio);
        }    
        $form = $this->createForm(new GenDirectorioType(), $arDirectorio);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arDirectorio = $form->getData();
            $ruta = $arDirectorio->getNombre()."/";
            $arDirectorio->setRuta(strtolower($ruta));
            $arDirectorio->setCodigoDirectorioPadreFk($codigoDirectorioPadre);
            $em->persist($arDirectorio);
            $em->flush();
            //return $this->redirect($this->generateUrl('brs_gen_utilidad_gestorarchivo', array('codigoDirectorioPadre' => $codigoDirectorioPadre)));
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaGeneralBundle:Utilidades/Directorio:nuevo.html.twig', array(
            'form' => $form->createView(),
            'codigoDirectorioPadre' => $codigoDirectorioPadre,
        ));
    }
    
    /**
     * @Route("/general/utilidad/gestorarchivo/cargar/archivo/{codigoDirectorioPadre}", name="brs_gen_utilidad_gestorarchivo_cargar_archivo")
     */
    public function cargarArchivoAction($codigoDirectorioPadre) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes(); 
        $form = $this->createFormBuilder()
            ->add('descripcion', 'text', array('required' => false))    
            ->add('attachment', 'file') 
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {                
                $objArchivo = $form['attachment']->getData();
                $arDirectorio = new \Brasa\GeneralBundle\Entity\GenDirectorio();
                $arDirectorio = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->find($codigoDirectorioPadre);
                $arArchivo = new \Brasa\GeneralBundle\Entity\GenArchivo();
                $arArchivo->setDescripcion($form->get('descripcion')->getData());
                $arArchivo->setNombre($objArchivo->getClientOriginalName());
                $arArchivo->setArchivo($objArchivo->getClientMimeType());                               
                $arArchivo->setDirectorioRel($arDirectorio);               
                if ($objArchivo->getClientSize()){
                    $em->persist($arArchivo);
                    $em->flush();
                    $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                    $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                    if ($arDirectorio == null){
                        $strDestino = $arConfiguracion->getRutaDirectorio();
                    }else{
                        $strDestino = $arConfiguracion->getRutaDirectorio() . $arDirectorio->getRuta();
                    }
                    $strArchivo = $arArchivo->getCodigoArchivoPk() . "_" . $objArchivo->getClientOriginalName();
                    $form['attachment']->getData()->move($strDestino, $strArchivo);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $objMensaje->Mensaje('error', "El archivo tiene un tamaño mayor al permitido", $this);
                }    
                
                
            }                                   
        }         
        return $this->render('BrasaGeneralBundle:Utilidades/Directorio:cargar.html.twig', array(
            'form' => $form->createView()
            ));
    }
    
    /**
     * @Route("/general/utilidad/gestorarchivo/descargar/archivo/{codigoArchivo}", name="brs_gen_utilidad_gestorarchivo_descargar_archivo")
     */
    public function descargarArchivoAction($codigoArchivo) {
        $em = $this->getDoctrine()->getManager();
        $arArchivo = new \Brasa\GeneralBundle\Entity\GenArchivo();
        $arArchivo = $em->getRepository('BrasaGeneralBundle:GenArchivo')->find($codigoArchivo);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $rutadirectorio = $arArchivo->getCodigoDirectorioFk();
        if ($rutadirectorio == null){
            $strRuta = $arConfiguracion->getRutaDirectorio() . $codigoArchivo. "_" .$arArchivo->getNombre();
        }else{
            $strRuta = $arConfiguracion->getRutaDirectorio() . $arArchivo->getDirectorioRel()->getRuta(). $codigoArchivo. "_" .$arArchivo->getNombre();
        }
        
        // Generate response
        $response = new Response();
        
        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', $arArchivo->getArchivo());
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $arArchivo->getNombre() . '";');
        //$response->headers->set('Content-length', $arArchivo->getTamano());        
        $response->sendHeaders();
        $response->setContent(readfile($strRuta));        
              
    }   
}
