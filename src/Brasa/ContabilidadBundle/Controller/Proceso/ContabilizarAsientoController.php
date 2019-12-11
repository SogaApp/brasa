<?php

namespace Brasa\ContabilidadBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class ContabilizarAsientoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/ctb/procesos/contabilizar/asientos", name="brs_ctb_procesos_contabilizar_asientos")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 61)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {            
            if ($form->get('BtnContabilizar')->isClicked()) {    
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arAsiento = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();                    
                        $arAsiento = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->find($codigo);
                        $arAsientoDetalles = new \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle();                    
                        $arAsientoDetalles = $em->getRepository('BrasaContabilidadBundle:CtbAsientoDetalle')->findBy(array('codigoAsientoFk' => $codigo));
                        foreach ($arAsientoDetalles as $arAsientoDetalle){
                            $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                            $arRegistro->setCuentaRel($arAsientoDetalle->getCuentaRel());
                            $arRegistro->setCentroCostoRel($arAsientoDetalle->getCentroCostoRel());
                            $arRegistro->setTerceroRel($arAsientoDetalle->getTerceroRel());
                            $arRegistro->setComprobanteRel($arAsientoDetalle->getAsientoRel()->getComprobanteRel());
                            $arRegistro->setFecha($arAsientoDetalle->getAsientoRel()->getFecha());
                            $arRegistro->setNumero($arAsientoDetalle->getSoporte());
                            $arRegistro->setNumeroReferencia($arAsientoDetalle->getDocumentoReferente());
                            $arRegistro->setDebito($arAsientoDetalle->getDebito());
                            $arRegistro->setCredito($arAsientoDetalle->getCredito());
                            $arRegistro->setBase($arAsientoDetalle->getValorBase());
                            $arRegistro->setDescripcionContable($arAsientoDetalle->getDescripcion());
                            $em->persist($arRegistro);
                            $arAsiento->setEstadoContabilizado(1);
                            $em->persist($arAsiento);
                        }
                    }
                    $em->flush();
                }
            }            
        }       
                
        $arAsientos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaContabilidadBundle:Procesos/Contabilizar:Asiento.html.twig', array(
            'arAsientos' => $arAsientos,
            'form' => $form->createView()));
    }          
    
    private function formularioLista() {
        $form = $this->createFormBuilder()                        
            ->add('BtnContabilizar', 'submit', array('label'  => 'Contabilizar',))
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $this->strDqlLista = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->pendientesContabilizarDql();  
    }         
    
}
