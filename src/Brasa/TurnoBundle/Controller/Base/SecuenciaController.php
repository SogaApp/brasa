<?php

namespace Brasa\TurnoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurPlantillaType;

class SecuenciaController extends Controller {

    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";

    /**
     * @Route("/tur/base/secuencia/lista", name="brs_tur_base_secuencia_lista")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 82, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurSecuenciaDetalle')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_secuencia_lista'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                //$this->filtrar($form);
            }
            if ($form->get('BtnDetalleNuevo')->isClicked()) {
                $arSecuenciaDetalleNuevo = new \Brasa\TurnoBundle\Entity\TurSecuenciaDetalle();                
                $em->persist($arSecuenciaDetalleNuevo);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_base_secuencia_lista'));
            }            
        }

        $arSecuenciaDetalles = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Secuencia:lista.html.twig', array(
                    'arSecuenciaDetalles' => $arSecuenciaDetalles,
                    'form' => $form->createView()));
    }
    
    /**
     * @Route("/tur/base/secuencia/detalle/editar/{codigoSecuenciaDetalle}", name="brs_tur_base_secuencia_detalle_editar")
     */     
    public function detalleEditarAction(Request $request, $codigoSecuenciaDetalle) {
        $em = $this->getDoctrine()->getManager();        
        $arSecuenciaDetalleAct = new \Brasa\TurnoBundle\Entity\TurSecuenciaDetalle();
        $arSecuenciaDetalleAct = $em->getRepository('BrasaTurnoBundle:TurSecuenciaDetalle')->find($codigoSecuenciaDetalle);        
        $arSecuenciaDetalle = new \Brasa\TurnoBundle\Entity\TurSecuenciaDetalle();
        $arSecuenciaDetalle = $em->getRepository('BrasaTurnoBundle:TurSecuenciaDetalle')->findBy(array('codigoSecuenciaDetallePk' => $codigoSecuenciaDetalle));
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);       
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_tur_base_secuencia_detalle_editar', array('codigoSecuenciaDetalle' => $codigoSecuenciaDetalle)))
                ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)                
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrControles = $request->request->All();
            if ($form->get('BtnDetalleActualizar')->isClicked()) {
                $this->actualizarDetalle($arrControles);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Secuencia:detalleEditar.html.twig', array(
                    'arSecuenciaDetalle' => $arSecuenciaDetalle,
                    'form' => $form->createView()
        ));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurSecuenciaDetalle')->listaDQL();
    }

    private function filtrar($form) {
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->lista();
    }

    private function formularioFiltro() {
        $form = $this->createFormBuilder()
                ->add('TxtNombre', TextType::class, array('label' => 'Nombre', 'data' => $this->strNombre))
                ->add('TxtCodigo', TextType::class, array('label' => 'Codigo', 'data' => $this->strCodigo))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))                
                ->add('BtnDetalleNuevo', SubmitType::class, array('label' => 'Nuevo',))                
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function actualizarDetalle($arrControles) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arSecuenciaDetalle = new \Brasa\TurnoBundle\Entity\TurSecuenciaDetalle();
                $arSecuenciaDetalle = $em->getRepository('BrasaTurnoBundle:TurSecuenciaDetalle')->find($intCodigo);
                if ($arrControles['TxtDias' . $intCodigo] != '') {                    
                    $arSecuenciaDetalle->setDias($arrControles['TxtDias' . $intCodigo]);
                } else {
                    $arSecuenciaDetalle->setDias(1);
                }
                if ($arrControles['TxtNombre' . $intCodigo] != '') {                    
                    $arSecuenciaDetalle->setNombre($arrControles['TxtNombre' . $intCodigo]);
                } else {
                    $arSecuenciaDetalle->setNombre(null);
                }                
                if ($arrControles['TxtDia1' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia1' . $intCodigo]);
                    $arSecuenciaDetalle->setDia1($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia1(null);
                }
                if ($arrControles['TxtDia2' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia2' . $intCodigo]);
                    $arSecuenciaDetalle->setDia2($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia2(null);
                }
                if ($arrControles['TxtDia3' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia3' . $intCodigo]);
                    $arSecuenciaDetalle->setDia3($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia3(null);
                }
                if ($arrControles['TxtDia4' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia4' . $intCodigo]);
                    $arSecuenciaDetalle->setDia4($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia4(null);
                }
                if ($arrControles['TxtDia5' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia5' . $intCodigo]);
                    $arSecuenciaDetalle->setDia5($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia5(null);
                }
                if ($arrControles['TxtDia6' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia6' . $intCodigo]);
                    $arSecuenciaDetalle->setDia6($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia6(null);
                }
                if ($arrControles['TxtDia7' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia7' . $intCodigo]);
                    $arSecuenciaDetalle->setDia7($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia7(null);
                }
                if ($arrControles['TxtDia8' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia8' . $intCodigo]);
                    $arSecuenciaDetalle->setDia8($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia8(null);
                }
                if ($arrControles['TxtDia9' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia9' . $intCodigo]);
                    $arSecuenciaDetalle->setDia9($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia9(null);
                }
                if ($arrControles['TxtDia10' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia10' . $intCodigo]);
                    $arSecuenciaDetalle->setDia10($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia10(null);
                }
                if ($arrControles['TxtDia11' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia11' . $intCodigo]);
                    $arSecuenciaDetalle->setDia11($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia11(null);
                }
                if ($arrControles['TxtDia12' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia12' . $intCodigo]);
                    $arSecuenciaDetalle->setDia12($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia12(null);
                }
                if ($arrControles['TxtDia13' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia13' . $intCodigo]);
                    $arSecuenciaDetalle->setDia13($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia13(null);
                }
                if ($arrControles['TxtDia14' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia14' . $intCodigo]);
                    $arSecuenciaDetalle->setDia14($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia14(null);
                }
                if ($arrControles['TxtDia15' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia15' . $intCodigo]);
                    $arSecuenciaDetalle->setDia15($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia15(null);
                }
                if ($arrControles['TxtDia16' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia16' . $intCodigo]);
                    $arSecuenciaDetalle->setDia16($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia16(null);
                }
                if ($arrControles['TxtDia17' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia17' . $intCodigo]);
                    $arSecuenciaDetalle->setDia17($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia17(null);
                }
                if ($arrControles['TxtDia18' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia18' . $intCodigo]);
                    $arSecuenciaDetalle->setDia18($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia18(null);
                }
                if ($arrControles['TxtDia19' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia19' . $intCodigo]);
                    $arSecuenciaDetalle->setDia19($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia19(null);
                }
                if ($arrControles['TxtDia20' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia20' . $intCodigo]);
                    $arSecuenciaDetalle->setDia20($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia20(null);
                }
                if ($arrControles['TxtDia21' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia21' . $intCodigo]);
                    $arSecuenciaDetalle->setDia21($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia21(null);
                }
                if ($arrControles['TxtDia22' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia22' . $intCodigo]);
                    $arSecuenciaDetalle->setDia22($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia22(null);
                }
                if ($arrControles['TxtDia23' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia23' . $intCodigo]);
                    $arSecuenciaDetalle->setDia23($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia23(null);
                }
                if ($arrControles['TxtDia24' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia24' . $intCodigo]);
                    $arSecuenciaDetalle->setDia24($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia24(null);
                }
                if ($arrControles['TxtDia25' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia25' . $intCodigo]);
                    $arSecuenciaDetalle->setDia25($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia25(null);
                }
                if ($arrControles['TxtDia26' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia26' . $intCodigo]);
                    $arSecuenciaDetalle->setDia26($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia26(null);
                }
                if ($arrControles['TxtDia27' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia27' . $intCodigo]);
                    $arSecuenciaDetalle->setDia27($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia27(null);
                }
                if ($arrControles['TxtDia28' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia28' . $intCodigo]);
                    $arSecuenciaDetalle->setDia28($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia28(null);
                }
                if ($arrControles['TxtDia29' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia29' . $intCodigo]);
                    $arSecuenciaDetalle->setDia29($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia29(null);
                }
                if ($arrControles['TxtDia30' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia30' . $intCodigo]);
                    $arSecuenciaDetalle->setDia30($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia30(null);
                }
                if ($arrControles['TxtDia31' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia31' . $intCodigo]);
                    $arSecuenciaDetalle->setDia31($strTurno);
                } else {
                    $arSecuenciaDetalle->setDia31(null);
                }
                $em->persist($arSecuenciaDetalle);
            }            
        }

        $em->flush();
    }    
    
    private function validarTurno($strTurno) {
        $em = $this->getDoctrine()->getManager();
        $strTurnoDevolver = NUll;
        if($strTurno != "") {
            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurno);
            if($arTurno) {
                $strTurnoDevolver = $strTurno;
            }
        }

        return $strTurnoDevolver;
    }    
}