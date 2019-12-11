<?php

namespace Brasa\AdministracionDocumentalBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArchivosController extends Controller
{
    
    /**
     * @Route("/ad/archivos/lista/{codigoDocumento}/{numero}", name="brs_ad_archivos_lista")
     */     
    public function listaAction(Request $request, $codigoDocumento, $numero) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $query = $em->createQuery($em->getRepository('BrasaAdministracionDocumentalBundle:AdArchivo')->listaDQL($codigoDocumento, $numero));        
        $arArchivos = $paginator->paginate($query, $request->query->get('page', 1), 50);                               
        return $this->render('BrasaAdministracionDocumentalBundle:Archivos:lista.html.twig', array(
            'arArchivos' => $arArchivos,
            'codigoDocumento' => $codigoDocumento,
            'numero' => $numero,
            ));
    }  
        
    /**
     * @Route("/ad/archivos/cargar/{codigoDocumento}/{numero}", name="brs_ad_archivos_cargar")
     */    
    public function cargarAction(Request $request, $codigoDocumento, $numero) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa'); 
        $form = $this->createFormBuilder()
            ->add('attachment', fileType::class)
            ->add('descripcion', textType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => false)) 
            ->add('BtnCargar', SubmitType::class, array('label'  => 'Cargar'))
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
                    $arArchivo->setDocumentoRel($em->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find($codigoDocumento));               
                    $arArchivo->setNumero($numero);
                    $arArchivo->setDescripcion($form->get('descripcion')->getData());
                    $arArchivo->setComentarios($form->get('comentarios')->getData());
                    $arDirectorio = $em->getRepository('BrasaAdministracionDocumentalBundle:AdDirectorio')->devolverDirectorio();
                    $arArchivo->setDirectorioRel($arDirectorio);
                    if ($objArchivo->getClientSize()){
                        $em->persist($arArchivo);
                        $em->flush();
                        $strDestino = $arDirectorio->getRutaPrincipal() . $arDirectorio->getNumero() . "/";
                        $strArchivo = $arArchivo->getCodigoArchivoPk() . "_" . $objArchivo->getClientOriginalName();
                        $form['attachment']->getData()->move($strDestino, $strArchivo);                    
                        return $this->redirect($this->generateUrl('brs_ad_archivos_lista', array('codigoDocumento' => $codigoDocumento, 'numero' => $numero)));
                    } else {
                        $objMensaje->Mensaje('error', "El archivo tiene un tamaño mayor al permitido", $this);
                    }    
                } else {
                    $objMensaje->Mensaje("error", "Solo se pueden cargar arhivos pdf", $this);
                }
            }                                   
        }         
        return $this->render('BrasaAdministracionDocumentalBundle:Archivos:cargar.html.twig', array(
            'form' => $form->createView()
            ));
    } 
    
    /**
     * @Route("/ad/archivos/descargar/{codigoArchivo}", name="brs_ad_archivos_descargar")
     */    
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
    
    /**
     * @Route("/ad/archivos/eliminar/{codigoArchivo}", name="brs_ad_archivos_eliminar")
     */    
    public function EliminarAction($codigoArchivo) {
        $em = $this->getDoctrine()->getManager();
        $arArchivo = new \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo();
        $arArchivo = $em->getRepository('BrasaAdministracionDocumentalBundle:AdArchivo')->find($codigoArchivo);
        $em->remove($arArchivo);
        $em->flush();
        //$rutadirectorio = $arArchivo->getDirectorioRel()->getRutaPrincipal() . $arArchivo->getDirectorioRel()->getNumero() . "/" . $arArchivo->getCodigoArchivoPk() . "_" . $arArchivo->getNombre();
        $strRuta = $arArchivo->getDirectorioRel()->getRutaPrincipal() . $arArchivo->getDirectorioRel()->getNumero() . "/" . $codigoArchivo . "_" . $arArchivo->getNombre();
        unlink($strRuta);
        return $this->redirect($this->generateUrl('brs_ad_archivos_lista', array('codigoDocumento' => $arArchivo->getCodigoDocumentoFk(), 'numero' =>$arArchivo->getNumero())));      
    }
    
    /**
     * @Route("/ad/archivos/enviar/{codigoDocumento}/{numero}/{codigoArchivo}", name="brs_ad_archivos_enviar")
     */    
    public function enviarAction(Request $request, $codigoDocumento, $numero,$codigoArchivo) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();  
        $arArchivo = new \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo();
        $arArchivo = $em->getRepository('BrasaAdministracionDocumentalBundle:AdArchivo')->find($codigoArchivo);
        $strRuta = $arArchivo->getDirectorioRel()->getRutaPrincipal() . $arArchivo->getDirectorioRel()->getNumero()  ."/" . $arArchivo->getCodigoArchivoPk() . "_" . $arArchivo->getNombre();
        
        $form = $this->createFormBuilder()
            ->add('asunto', textType::class, array('required' => true))
            ->add('email', textType::class, array('required' => true))
            ->add('mensaje', TextareaType::class, array('required' => true)) 
            ->add('BtnEnviar', SubmitType::class , array('label'  => 'Enviar'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnEnviar')->isClicked()) {
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);                                
                $strMail = $form->get('email')->getData();
                $strAsunto = $form->get('asunto')->getData();                  
                $strMensaje = $form->get('mensaje')->getData();                               
                $correoGeneral = $arConfiguracion->getCorreoGeneral();
                if($strMail) {
                    if ($correoGeneral){                                            
                    $message = \Swift_Message::newInstance()
                        ->setSubject($strAsunto)
                        ->setFrom($correoGeneral, "SogaApp")
                        ->setTo(strtolower($strMail))
                        ->setBody($strMensaje,'text/html')                            
                        ->attach(\Swift_Attachment::fromPath($strRuta));                
                    $this->get('mailer')->send($message);
                    $objMensaje->Mensaje("error", "Mensaje enviado con exito al correo ".$strMail."", $this);                                                                                
                    } else {
                        $objMensaje->Mensaje("error", "EL correo remitente no esta en la configuracion general"."", $this);
                    }
                } else {
                    $objMensaje->Mensaje("error", "No hay correo destino para enviar, por favor verificar", $this);
                }                                
            }
        }         
        return $this->render('BrasaAdministracionDocumentalBundle:Archivos:enviar.html.twig', array(
            'form' => $form->createView(),
            'codigoDocumento' => $codigoDocumento,
            'numero' => $numero,
            ));
              
    }
    
    /**
     * @Route("/ad/archivos/prueba/{codigoDocumento}/{numero}", name="brs_ad_archivos_prueba")
     */    
    public function pruebaAction(Request $request, $codigoDocumento, $numero) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                                       
        $message = \Swift_Message::newInstance()
            ->setSubject('Prueba email SogaApp')
            ->setFrom('sogaimplementacion@gmail.com', "SogaApp" )
            ->setTo(strtolower('sogaimplementacion@gmail.com'))
            ->setBody('Prueba SogaApp','text/html');
        $this->get('mailer')->send($message);
        $objMensaje->Mensaje("error", "Mensaje de prueba enviado con exito", $this);                       
        return $this->redirect($this->generateUrl('brs_ad_archivos_lista', array('codigoDocumento' => $codigoDocumento, 'numero' => $numero)));      
    }
    
    
}
