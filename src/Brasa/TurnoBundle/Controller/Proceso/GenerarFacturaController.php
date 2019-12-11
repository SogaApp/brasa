<?php
namespace Brasa\TurnoBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GenerarFacturaController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/tur/proceso/generar/factura", name="brs_tur_proceso_generar_factura")
     */      
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();  
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 6)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {                       
            if ($form->get('BtnGenerar')->isClicked()) { 
                $arrSeleccionados = $request->request->get('ChkSeleccionar');                
                foreach ($arrSeleccionados as $codigoPedido) {
                    $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
                    $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
                    if($arPedido->getEstadoFacturado() == 0 && $arPedido->getEstadoAutorizado() == 1 && $arPedido->getEstadoAnulado() == 0) {                    
                        $codigoFactura = $em->getRepository('BrasaTurnoBundle:TurPedido')->facturar($codigoPedido,  $this->getUser()->getUsername());                
                        if($codigoFactura == 0) {
                            $arPedido->setEstadoFacturado(1);
                            $em->persist($arPedido);                            
                        }
                    }                  
                    
                }
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_factura'));                                 
            }
        }
        
        $arPedidos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaTurnoBundle:Procesos/GenerarFactura:lista.html.twig', array(
            'arPedidos' => $arPedidos, 
            'form' => $form->createView()));
    }        
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurPedido')->listaDql("","",1,"",0,0,"","");
    }
    
    private function formularioLista() {  
        $form = $this->createFormBuilder()
            ->add('BtnGenerar', SubmitType::class, array('label'  => 'Generar'))            
            ->getForm();
        return $form;
    }        
    
}