<?php
namespace Brasa\TurnoBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GenerarProgramacionController extends Controller
{
    var $strListaDql = "";
    /**
     * @Route("/tur/proceso/generar/programacion/lista", name="brs_tur_proceso_generar_programacion_lista")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 4)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {            
            if($request->request->get('OpGenerar')) {
                set_time_limit(0);
                $codigoPedido = $request->request->get('OpGenerar');
                $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
                $arPedido =  $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);                 
                $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
                $arProgramacion->setClienteRel($arPedido->getClienteRel());
                $arProgramacion->setFecha($arPedido->getFechaProgramacion());
                $arUsuario = $this->getUser();
                $arProgramacion->setUsuario($arUsuario->getUserName());                                
                $em->persist($arProgramacion); 
                $arPedido->setEstadoProgramado(true);
                $em->persist($arPedido);
                
                $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                $arPedidoDetalles =  $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido)); 
                foreach ($arPedidoDetalles as $arPedidoDetalle) {
                    $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->nuevo($arPedidoDetalle->getCodigoPedidoDetallePk(), $arProgramacion);
                }        
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($arProgramacion->getCodigoProgramacionPk());
                $em->flush();    
                set_time_limit(60);
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_programacion_lista')); 
            }    
            if ($form->get('BtnGenerar')->isClicked()) {  
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPedido) {
                        $arPedidoActualizar = new \Brasa\TurnoBundle\Entity\TurPedido();
                        $arPedidoActualizar =  $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);                            
                        if($arPedidoActualizar) {
                            if($arPedidoActualizar->getEstadoProgramado() == 0) {
                                $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
                                $arProgramacion->setClienteRel($arPedidoActualizar->getClienteRel());
                                $arProgramacion->setFecha($arPedidoActualizar->getFechaProgramacion());
                                $em->persist($arProgramacion);                    
                                $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                                $arPedidoDetalles =  $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $arPedidoActualizar->getCodigoPedidoPk())); 
                                foreach ($arPedidoDetalles as $arPedidoDetalle) {
                                    $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->nuevo($arPedidoDetalle->getCodigoPedidoDetallePk(), $arProgramacion);
                                } 
                                $arPedidoActualizar->setEstadoProgramado(true);
                                $em->persist($arPedidoActualizar);
                                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($arProgramacion->getCodigoProgramacionPk());                                
                                $em->flush();
                            }
                        }
                    }
                    
                }    
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_programacion_lista'));                                                                     
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
            if ($form->get('BtnCerrarProgramacion')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPedido) {
                        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
                        $arPedido =  $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);                                                    
                        $arPedido->setEstadoProgramado(1);
                        $em->persist($arPedido);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_proceso_generar_programacion_lista'));
                }
            }                        
        }
        
        $arPedidos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 300);
        return $this->render('BrasaTurnoBundle:Procesos/GenerarProgramacion:lista.html.twig', array(
            'arPedidos' => $arPedidos, 
            'form' => $form->createView()));
    }        
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurPedido')->pedidoSinProgramarDql('2016-08-01');
    }
    
    private function formularioLista() {        
        $form = $this->createFormBuilder()                                            
            ->add('BtnGenerar', SubmitType::class, array('label'  => 'Generar seleccionados'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnCerrarProgramacion', SubmitType::class, array('label'  => 'Cerrar programacion'))
            ->getForm();
        return $form;
    }        
    
}