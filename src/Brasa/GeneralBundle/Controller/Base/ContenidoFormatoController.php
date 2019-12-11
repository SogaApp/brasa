<?php

namespace Brasa\GeneralBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\GeneralBundle\Form\Type\GenContenidoFormatoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * GenContenidoFormato controller.
 *
 */
class ContenidoFormatoController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/general/base/contenido/formato/lista/", name="brs_gen_base_contenido_formato_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 104, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $this->lista();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoContenidoFormato) {
                        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
                        $arContenidoFormato = $em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find($codigoContenidoFormato);
                        $em->remove($arContenidoFormato);                    
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_gen_base_contenido_formato_lista'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar contenidos de los formatos porque esta siendo utilizado', $this);
                   }    
            }                        
        }
        $arContenidoFormatos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);        
        return $this->render('BrasaGeneralBundle:Base/ContenidoFormato:listar.html.twig', array(
                    'arContenidoFormatos' => $arContenidoFormatos,
                    'form'=> $form->createView()
        ));
    }
    
    /**
     * @Route("/general/base/contenido/formato/nuevo/{codigoContenidoFormato}", name="brs_gen_base_contenido_formato_nuevo")
     */
    public function nuevoAction($codigoContenidoFormato) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        if ($codigoContenidoFormato != 0) {
            $arContenidoFormato = $em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find($codigoContenidoFormato);
        }    
        $form = $this->createForm(new GenContenidoFormatoType(), $arContenidoFormato);
        $form->handleRequest($request);
        if ($form->isValid()) {                        
            $arContenidoFormato = $form->getData();
            $em->persist($arContenidoFormato);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_gen_base_contenido_formato_lista'));
        }
        return $this->render('BrasaGeneralBundle:Base/ContenidoFormato:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();        
        $this->strDqlLista = $em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->listaDql();         
    }
    
}
