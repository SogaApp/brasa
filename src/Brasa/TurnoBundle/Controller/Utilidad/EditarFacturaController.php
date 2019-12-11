<?php
namespace Brasa\TurnoBundle\Controller\Utilidad;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurFacturaEditarType;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;

class EditarFacturaController extends Controller
{
    var $strListaDql = "";    

    
    /**
     * @Route("/tur/utilidad/editar/factura", name="brs_tur_utilidad_editar_factura")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 95)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {                        
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }            
        }        
        $arFacturas = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Utilidades/Factura:editar.html.twig', array(
            'arFacturas' => $arFacturas,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/tur/utilidad/editar/factura/nuevo/{codigoFactura}", name="brs_tur_utilidad_editar_factura_nuevo")
     */
    public function nuevoAction(Request $request, $codigoFactura) {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();        
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();        
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $form = $this->createForm(new TurFacturaEditarType, $arFactura);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arFactura = $form->getData();                                                     
            $em->persist($arFactura);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tur_utilidad_editar_factura'));                       
        }
        return $this->render('BrasaTurnoBundle:Utilidades/Factura:nuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurFactura')->listaDql(
                $session->get('filtroFacturaNumero'),
                "",
                "",
                "",
                "",                
                "",
                ""
                );
    }      

    private function filtrar ($form) { 
        $session = new Session;            
        $session->set('filtroFacturaNumero', $form->get('TxtNumero')->getData());
    }   
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;          
        $form = $this->createFormBuilder()
            ->add('TxtNumero', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroFacturaNumero')))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    

}