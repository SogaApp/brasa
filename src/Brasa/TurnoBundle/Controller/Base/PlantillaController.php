<?php

namespace Brasa\TurnoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurPlantillaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PlantillaController extends Controller {

    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";

    /**
     * @Route("/tur/base/plantilla/lista", name="brs_tur_base_plantilla_lista")
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
                $em->getRepository('BrasaTurnoBundle:TurPlantilla')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_lista'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }

        $arPlantillas = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Plantilla:lista.html.twig', array(
                    'arPlantillas' => $arPlantillas,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/plantilla/nuevo/{codigoPlantilla}", name="brs_tur_base_plantilla_nuevo")
     */     
    public function nuevoAction(Request $request, $codigoPlantilla = 0) {        
        $em = $this->getDoctrine()->getManager();
        $arPlantilla = new \Brasa\TurnoBundle\Entity\TurPlantilla();
        if ($codigoPlantilla != 0) {
            $arPlantilla = $em->getRepository('BrasaTurnoBundle:TurPlantilla')->find($codigoPlantilla);
        }
        $form = $this->createForm(TurPlantillaType::class, $arPlantilla);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPlantilla = $form->getData();
            $arUsuario = $this->getUser();
            $arPlantilla->setUsuario($arUsuario->getUserName());
            $em->persist($arPlantilla);
            $em->flush();

            if ($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_nuevo', array('codigoPlantilla' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_detalle', array('codigoPlantilla' => $arPlantilla->getCodigoPlantillaPk())));
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Plantilla:nuevo.html.twig', array(
                    'arPlantilla' => $arPlantilla,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/plantilla/detalle/{codigoPlantilla}", name="brs_tur_base_plantilla_detalle")
     */     
    public function detalleAction(Request $request, $codigoPlantilla) {
        $em = $this->getDoctrine()->getManager();        
        $objMensaje = $this->get('mensajes_brasa');
        $arPlantilla = new \Brasa\TurnoBundle\Entity\TurPlantilla();
        $arPlantilla = $em->getRepository('BrasaTurnoBundle:TurPlantilla')->find($codigoPlantilla);
        $form = $this->formularioDetalle($arPlantilla);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrControles = $request->request->All();
            if ($form->get('BtnAutorizar')->isClicked()) {
                if ($arPlantilla->getEstadoAutorizado() == 0) {
                    if ($em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->numeroRegistros($codigoPlantilla) > 0) {
                        $arPlantilla->setEstadoAutorizado(1);
                        $em->persist($arPlantilla);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_tur_base_plantilla_detalle', array('codigoPlantilla' => $codigoPlantilla)));
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles al examen', $this);
                    }
                }
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_detalle', array('codigoPlantilla' => $codigoPlantilla)));
            }
            if ($form->get('BtnDesAutorizar')->isClicked()) {
                if ($arPlantilla->getEstadoAutorizado() == 1) {
                    $arPlantilla->setEstadoAutorizado(0);
                    $em->persist($arPlantilla);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_base_plantilla_detalle', array('codigoPlantilla' => $codigoPlantilla)));
                }
            }
            if ($form->get('BtnImprimir')->isClicked()) {
                if ($arPlantilla->getEstadoAutorizado() == 1) {
                    $objExamen = new \Brasa\TurnoBundle\Formatos\FormatoExamen();
                    $objExamen->Generar($this, $codigoPlantilla);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir una orden de examen sin estar autorizada", $this);
                }
            }
            if ($form->get('BtnDetalleEliminar')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->eliminarDetalles($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_detalle', array('codigoPlantilla' => $codigoPlantilla)));
            }
            if ($form->get('BtnDetalleActualizar')->isClicked()) {
                $this->actualizarDetalle($arrControles);
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_detalle', array('codigoPlantilla' => $codigoPlantilla)));
            }
            if ($form->get('BtnDetalleNuevo')->isClicked()) {
                //$this->actualizarDetalle($arrControles);
                $arPlantillaDetalleNuevo = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
                $arPlantillaDetalleNuevo->setPlantillaRel($arPlantilla);
                $em->persist($arPlantillaDetalleNuevo);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_detalle', array('codigoPlantilla' => $codigoPlantilla)));
            }
        }

        $arPlantillaDetalle = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
        $arPlantillaDetalle = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->findBy(array('codigoPlantillaFk' => $codigoPlantilla));
        return $this->render('BrasaTurnoBundle:Base/Plantilla:detalle.html.twig', array(
                    'arPlantilla' => $arPlantilla,
                    'arPlantillaDetalle' => $arPlantillaDetalle,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/tur/base/plantilla/detalle/editar/{codigoPlantillaDetalle}", name="brs_tur_base_plantilla_detalle_editar")
     */     
    public function detalleEditarAction(Request $request, $codigoPlantillaDetalle) {
        $em = $this->getDoctrine()->getManager();        
        $arPlantillaDetalleAct = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
        $arPlantillaDetalleAct = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->find($codigoPlantillaDetalle);        
        $arPlantillaDetalle = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
        $arPlantillaDetalle = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->findBy(array('codigoPlantillaDetallePk'=>$codigoPlantillaDetalle));
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);       
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_tur_base_plantilla_detalle_editar', array('codigoPlantillaDetalle' => $codigoPlantillaDetalle)))
                ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrControles = $request->request->All();

            if ($form->get('BtnDetalleEliminar')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->eliminarDetalles($arrSeleccionados);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if ($form->get('BtnDetalleActualizar')->isClicked()) {
                $this->actualizarDetalle($arrControles);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Plantilla:detalleEditar.html.twig', array(
                    'arPlantillaDetalle' => $arPlantillaDetalle,
                    'form' => $form->createView()
        ));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurPlantilla')->listaDQL(
                $this->strNombre, $this->strCodigo
        );
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
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => true);
        $arrBotonDetalleNuevo = array('label' => 'Nuevo', 'disabled' => false);
        if ($ar->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonDetalleNuevo['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
        }

        $form = $this->createFormBuilder()
                ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)
                ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)
                ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                ->add('BtnDetalleActualizar', SubmitType::class, $arrBotonDetalleActualizar)
                ->add('BtnDetalleEliminar', SubmitType::class, $arrBotonDetalleEliminar)
                ->add('BtnDetalleNuevo', SubmitType::class, $arrBotonDetalleNuevo)
                ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
                ->setLastModifiedBy("EMPRESA")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIG0')
                ->setCellValue('B1', 'NOMBRE');

        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arPlantillas = new \Brasa\TurnoBundle\Entity\TurPlantilla();
        $arPlantillas = $query->getResult();

        foreach ($arPlantillas as $arPlantilla) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPlantilla->getCodigoPlantillaPk())
                    ->setCellValue('B' . $i, $arPlantilla->getNombreCorto());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Plantilla');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Plantillas.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

    private function actualizarDetalle($arrControles) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arPlantillaDetalle = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
                $arPlantillaDetalle = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->find($intCodigo);
                if ($arrControles['TxtPosicion' . $intCodigo] != '') {
                    $arPlantillaDetalle->setPosicion($arrControles['TxtPosicion' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setPosicion(0);
                }
                if ($arrControles['TxtDia1' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia1' . $intCodigo]);
                    $arPlantillaDetalle->setDia1($strTurno);
                } else {
                    $arPlantillaDetalle->setDia1(null);
                }
                if ($arrControles['TxtDia2' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia2' . $intCodigo]);
                    $arPlantillaDetalle->setDia2($strTurno);
                } else {
                    $arPlantillaDetalle->setDia2(null);
                }
                if ($arrControles['TxtDia3' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia3' . $intCodigo]);
                    $arPlantillaDetalle->setDia3($strTurno);
                } else {
                    $arPlantillaDetalle->setDia3(null);
                }
                if ($arrControles['TxtDia4' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia4' . $intCodigo]);
                    $arPlantillaDetalle->setDia4($strTurno);
                } else {
                    $arPlantillaDetalle->setDia4(null);
                }
                if ($arrControles['TxtDia5' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia5' . $intCodigo]);
                    $arPlantillaDetalle->setDia5($strTurno);
                } else {
                    $arPlantillaDetalle->setDia5(null);
                }
                if ($arrControles['TxtDia6' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia6' . $intCodigo]);
                    $arPlantillaDetalle->setDia6($strTurno);
                } else {
                    $arPlantillaDetalle->setDia6(null);
                }
                if ($arrControles['TxtDia7' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia7' . $intCodigo]);
                    $arPlantillaDetalle->setDia7($strTurno);
                } else {
                    $arPlantillaDetalle->setDia7(null);
                }
                if ($arrControles['TxtDia8' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia8' . $intCodigo]);
                    $arPlantillaDetalle->setDia8($strTurno);
                } else {
                    $arPlantillaDetalle->setDia8(null);
                }
                if ($arrControles['TxtDia9' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia9' . $intCodigo]);
                    $arPlantillaDetalle->setDia9($strTurno);
                } else {
                    $arPlantillaDetalle->setDia9(null);
                }
                if ($arrControles['TxtDia10' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia10' . $intCodigo]);
                    $arPlantillaDetalle->setDia10($strTurno);
                } else {
                    $arPlantillaDetalle->setDia10(null);
                }
                if ($arrControles['TxtDia11' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia11' . $intCodigo]);
                    $arPlantillaDetalle->setDia11($strTurno);
                } else {
                    $arPlantillaDetalle->setDia11(null);
                }
                if ($arrControles['TxtDia12' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia12' . $intCodigo]);
                    $arPlantillaDetalle->setDia12($strTurno);
                } else {
                    $arPlantillaDetalle->setDia12(null);
                }
                if ($arrControles['TxtDia13' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia13' . $intCodigo]);
                    $arPlantillaDetalle->setDia13($strTurno);
                } else {
                    $arPlantillaDetalle->setDia13(null);
                }
                if ($arrControles['TxtDia14' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia14' . $intCodigo]);
                    $arPlantillaDetalle->setDia14($strTurno);
                } else {
                    $arPlantillaDetalle->setDia14(null);
                }
                if ($arrControles['TxtDia15' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia15' . $intCodigo]);
                    $arPlantillaDetalle->setDia15($strTurno);
                } else {
                    $arPlantillaDetalle->setDia15(null);
                }
                if ($arrControles['TxtDia16' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia16' . $intCodigo]);
                    $arPlantillaDetalle->setDia16($strTurno);
                } else {
                    $arPlantillaDetalle->setDia16(null);
                }
                if ($arrControles['TxtDia17' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia17' . $intCodigo]);
                    $arPlantillaDetalle->setDia17($strTurno);
                } else {
                    $arPlantillaDetalle->setDia17(null);
                }
                if ($arrControles['TxtDia18' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia18' . $intCodigo]);
                    $arPlantillaDetalle->setDia18($strTurno);
                } else {
                    $arPlantillaDetalle->setDia18(null);
                }
                if ($arrControles['TxtDia19' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia19' . $intCodigo]);
                    $arPlantillaDetalle->setDia19($strTurno);
                } else {
                    $arPlantillaDetalle->setDia19(null);
                }
                if ($arrControles['TxtDia20' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia20' . $intCodigo]);
                    $arPlantillaDetalle->setDia20($strTurno);
                } else {
                    $arPlantillaDetalle->setDia20(null);
                }
                if ($arrControles['TxtDia21' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia21' . $intCodigo]);
                    $arPlantillaDetalle->setDia21($strTurno);
                } else {
                    $arPlantillaDetalle->setDia21(null);
                }
                if ($arrControles['TxtDia22' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia22' . $intCodigo]);
                    $arPlantillaDetalle->setDia22($strTurno);
                } else {
                    $arPlantillaDetalle->setDia22(null);
                }
                if ($arrControles['TxtDia23' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia23' . $intCodigo]);
                    $arPlantillaDetalle->setDia23($strTurno);
                } else {
                    $arPlantillaDetalle->setDia23(null);
                }
                if ($arrControles['TxtDia24' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia24' . $intCodigo]);
                    $arPlantillaDetalle->setDia24($strTurno);
                } else {
                    $arPlantillaDetalle->setDia24(null);
                }
                if ($arrControles['TxtDia25' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia25' . $intCodigo]);
                    $arPlantillaDetalle->setDia25($strTurno);
                } else {
                    $arPlantillaDetalle->setDia25(null);
                }
                if ($arrControles['TxtDia26' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia26' . $intCodigo]);
                    $arPlantillaDetalle->setDia26($strTurno);
                } else {
                    $arPlantillaDetalle->setDia26(null);
                }
                if ($arrControles['TxtDia27' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia27' . $intCodigo]);
                    $arPlantillaDetalle->setDia27($strTurno);
                } else {
                    $arPlantillaDetalle->setDia27(null);
                }
                if ($arrControles['TxtDia28' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia28' . $intCodigo]);
                    $arPlantillaDetalle->setDia28($strTurno);
                } else {
                    $arPlantillaDetalle->setDia28(null);
                }
                if ($arrControles['TxtDia29' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia29' . $intCodigo]);
                    $arPlantillaDetalle->setDia29($strTurno);
                } else {
                    $arPlantillaDetalle->setDia29(null);
                }
                if ($arrControles['TxtDia30' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia30' . $intCodigo]);
                    $arPlantillaDetalle->setDia30($strTurno);
                } else {
                    $arPlantillaDetalle->setDia30(null);
                }
                if ($arrControles['TxtDia31' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia31' . $intCodigo]);
                    $arPlantillaDetalle->setDia31($strTurno);
                } else {
                    $arPlantillaDetalle->setDia31(null);
                }
                if ($arrControles['TxtLunes' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtLunes' . $intCodigo]);
                    $arPlantillaDetalle->setLunes($strTurno);
                } else {
                    $arPlantillaDetalle->setLunes(null);
                }
                if ($arrControles['TxtMartes' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtMartes' . $intCodigo]);
                    $arPlantillaDetalle->setMartes($strTurno);
                } else {
                    $arPlantillaDetalle->setMartes(null);
                }
                if ($arrControles['TxtMiercoles' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtMiercoles' . $intCodigo]);
                    $arPlantillaDetalle->setMiercoles($strTurno);
                } else {
                    $arPlantillaDetalle->setMiercoles(null);
                }
                if ($arrControles['TxtJueves' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtJueves' . $intCodigo]);
                    $arPlantillaDetalle->setJueves($strTurno);
                } else {
                    $arPlantillaDetalle->setJueves(null);
                }
                if ($arrControles['TxtViernes' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtViernes' . $intCodigo]);
                    $arPlantillaDetalle->setViernes($strTurno);
                } else {
                    $arPlantillaDetalle->setViernes(null);
                }
                if ($arrControles['TxtSabado' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtSabado' . $intCodigo]);
                    $arPlantillaDetalle->setSabado($strTurno);
                } else {
                    $arPlantillaDetalle->setSabado(null);
                }
                if ($arrControles['TxtDomingo' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDomingo' . $intCodigo]);
                    $arPlantillaDetalle->setDomingo($strTurno);
                } else {
                    $arPlantillaDetalle->setDomingo(null);
                }
                if ($arrControles['TxtDomingoFestivo' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDomingoFestivo' . $intCodigo]);
                    $arPlantillaDetalle->setDomingoFestivo($strTurno);
                } else {
                    $arPlantillaDetalle->setDomingoFestivo(null);
                }                
                if ($arrControles['TxtFestivo' . $intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtFestivo' . $intCodigo]);
                    $arPlantillaDetalle->setFestivo($strTurno);
                } else {
                    $arPlantillaDetalle->setFestivo(null);
                }
                $em->persist($arPlantillaDetalle);
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