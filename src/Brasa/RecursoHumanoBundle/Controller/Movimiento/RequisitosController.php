<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuRequisitoType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
class RequisitosController extends Controller
{
    var $strDqlLista = "";

    /**
     * @Route("/rhu/requisito/lista", name="brs_rhu_requisito_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 7, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }

            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoRequisito) {
                        $arRequisito = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisito')->find($codigoRequisito);
                        $em->remove($arRequisito);
                        $em->flush();
                    }
                }
            }

        }

        $arRequisitos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Requisitos:lista.html.twig', array(
            'arRequisitos' => $arRequisitos,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/requisito/detalle/{codigoRequisito}", name="brs_rhu_requisito_detalle")
     */
    public function detalleAction($codigoRequisito) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisito();
        $arRequisito = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisito')->find($codigoRequisito);
        $form = $this->formularioDetalle($arRequisito);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if($arRequisito->getEstadoAutorizado() == 0) {
                    $arRequisitosDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoDetalle')->findBy(array('codigoRequisitoFk' => $codigoRequisito));
                    if ($arRequisitosDetalle != null){
                        $arRequisito->setEstadoAutorizado(1);
                        $em->persist($arRequisito);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_requisito_detalle', array('codigoRequisito' => $codigoRequisito)));
                    } else {
                        $objMensaje->Mensaje("error", "Los requisitos no tienen detalles, no se puede autorizar", $this);
                    }
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if($arRequisito->getEstadoAutorizado() == 1) {
                    $arRequisito->setEstadoAutorizado(0);
                    $em->persist($arRequisito);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_requisito_detalle', array('codigoRequisito' => $codigoRequisito)));
                }
            }
            if($form->get('BtnImprimir')->isClicked()) {
                if($arRequisito->getEstadoAutorizado() == 1) {
                    $objFormato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoRequisitos();
                    $objFormato->Generar($this, $codigoRequisito);
                }
            }
            if($form->get('BtnCerrar')->isClicked()) {
                if($arRequisito->getEstadoAutorizado() == 1) {
                    $arRequisito->setEstadoCerrado(1);
                    $em->persist($arRequisito);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_requisito_detalle', array('codigoRequisito' => $codigoRequisito)));
                }
            }            
            if($form->get('BtnDetalleEntregado')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoRequisitoDetallePk) {
                        $arRequisitoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();
                        $arRequisitoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoDetalle')->find($codigoRequisitoDetallePk);
                        if($arRequisitoDetalle->getEstadoNoAplica() == 0) {
                            if($arRequisitoDetalle->getEstadoEntregado() == 1) {
                                $arRequisitoDetalle->setEstadoEntregado(0);
                                $arRequisitoDetalle->setEstadoPendiente(1);
                            } else {
                                $arRequisitoDetalle->setEstadoEntregado(1);
                                $arRequisitoDetalle->setEstadoPendiente(0);
                            }
                            $em->persist($arRequisitoDetalle);
                        }
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_requisito_detalle', array('codigoRequisito' => $codigoRequisito)));
            }
            if($form->get('BtnDetalleNoAplica')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoRequisitoDetallePk) {
                        $arRequisitoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();
                        $arRequisitoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoDetalle')->find($codigoRequisitoDetallePk);
                        if($arRequisitoDetalle->getEstadoEntregado() == 0) {
                            if($arRequisitoDetalle->getEstadoNoAplica() == 1) {
                                $arRequisitoDetalle->setEstadoNoAplica(0);
                                $arRequisitoDetalle->setEstadoPendiente(1);
                            } else {
                                $arRequisitoDetalle->setEstadoNoAplica(1);
                                $arRequisitoDetalle->setEstadoPendiente(0);
                            }
                            $em->persist($arRequisitoDetalle);
                        }
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_requisito_detalle', array('codigoRequisito' => $codigoRequisito)));
            }
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                if($arRequisito->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados as $codigoRequisitoDetalle) {
                            $arRequisitoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();
                            $arRequisitoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoDetalle')->find($codigoRequisitoDetalle);                        
                            $em->remove($arRequisitoDetalle);                        
                        }
                        $em->flush();                    
                    } 
                    return $this->redirect($this->generateUrl('brs_rhu_requisito_detalle', array('codigoRequisito' => $codigoRequisito)));
                }    
            } 
            if ($form->get('BtnActualizarDetalle')->isClicked()) {
                if($arRequisito->getEstadoAutorizado() == 0) {
                    $arrControles = $request->request->All();
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        if($arrControles['TxtCantidad'.$intCodigo] != "" && $arrControles['TxtCantidadPendiente'.$intCodigo] != '') {
                            $intCantidad = $arrControles['TxtCantidad'.$intCodigo];
                            $intCantidadPendiente = $arrControles['TxtCantidadPendiente'.$intCodigo];
                            $arRequisitoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();
                            $arRequisitoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoDetalle')->find($intCodigo);
                            $arRequisitoDetalle->setCantidad($intCantidad);
                            $arRequisitoDetalle->setCantidadPendiente($intCantidadPendiente);
                            $em->persist($arRequisitoDetalle);
                        }
                    }                
                    $em->flush();
                }      
            }            
        }
        $arRequisitosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();
        $arRequisitosDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoDetalle')->findBy(array('codigoRequisitoFk' => $codigoRequisito));
        $arRequisitosDetalles = $paginator->paginate($arRequisitosDetalles, $this->get('request')->query->get('page', 1),50);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Requisitos:detalle.html.twig', array(
                        'arRequisitosDetalles' => $arRequisitosDetalles,
                        'arRequisito' => $arRequisito,
                        'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/requisito/detalle/nuevo/{codigoRequisito}", name="brs_rhu_requisito_detalle_nuevo")
     */
    public function detalleNuevoAction($codigoRequisito) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arRequisito = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisito')->find($codigoRequisito);
        $form = $this->createFormBuilder()
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {            
            if($form->get('BtnAgregar')->isClicked()) {
                if ($arRequisito->getEstadoAutorizado() == 0){
                    $arrControles = $request->request->All();
                    if (isset($arrControles['TxtCantidad'])) {
                        $intIndice = 0;
                        foreach ($arrControles['LblCodigo'] as $intCodigo) {                        
                            $arRequisitoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoConcepto();
                            $arRequisitoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoConcepto')->find($intCodigo);                                                                
                            if($arrControles['TxtCantidad'][$intIndice] != "" && $arrControles['TxtCantidad'][$intIndice] != 0) {
                                $arRequisitoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();                            
                                $arRequisitoDetalle->setRequisitoRel($arRequisito);
                                $arRequisitoDetalle->setRequisitoConceptoRel($arRequisitoConcepto);                                                      
                                $intCantidad = $arrControles['TxtCantidad'][$intIndice];
                                $arRequisitoDetalle->setCantidad($intCantidad);
                                $arRequisitoDetalle->setCantidadPendiente($intCantidad);
                                $arRequisitoDetalle->setTipo('PERSONALIZADO');
                                $em->persist($arRequisitoDetalle);                                
                            }                        
                            $intIndice++;
                        }
                    }                
                    $em->flush();
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }
        }
        $arRequisitoConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoConcepto();
        $arRequisitoConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoConcepto')->findAll();
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Requisitos:detalleNuevo.html.twig', array(
            'arRequisito' => $arRequisito,
            'arRequisitoConceptos' => $arRequisitoConceptos,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/requisito/nuevo/{codigoRequisito}", name="brs_rhu_requisito_nuevo")
     */
    public function nuevoAction($codigoRequisito) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisito();
        $arRequisito->setFecha(new \DateTime('now'));
        $form = $this->createForm(new RhuRequisitoType(), $arRequisito);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arRequisito = $form->getData();
            $arRequisito->setCodigoUsuario($arUsuario->getUserName());
            $em->persist($arRequisito);
            $arRequisitosConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoConcepto();
            $arRequisitosConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoConcepto')->findBy(array('general' => 1));
            foreach ($arRequisitosConceptos as $arRequisitoConcepto) {
                $arRequisitoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();
                $arRequisitoDetalle->setRequisitoRel($arRequisito);
                $arRequisitoDetalle->setRequisitoConceptoRel($arRequisitoConcepto);
                $arRequisitoDetalle->setTipo('GENERAL');
                $arRequisitoDetalle->setCantidad(1);
                $arRequisitoDetalle->setCantidadPendiente(1);
                $em->persist($arRequisitoDetalle);
            }

            $arRequisitosCargos = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoCargo();
            $arRequisitosCargos = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoCargo')->findBy(array('codigoCargoFk'=> $form->get('cargoRel')->getData()));
            foreach ($arRequisitosCargos as $arRequisitoCargo) {
                $arRequisitoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();
                $arRequisitoDetalle->setRequisitoRel($arRequisito);
                $arRequisitoDetalle->setRequisitoConceptoRel($arRequisitoCargo->getRequisitoConceptoRel());
                $arRequisitoDetalle->setTipo('CARGO');
                $arRequisitoDetalle->setCantidad(1);
                $arRequisitoDetalle->setCantidadPendiente(1);                
                $em->persist($arRequisitoDetalle);
            }

            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_requisito_detalle', array('codigoRequisito' => $arRequisito->getCodigoRequisitoPk())));
            //echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Requisitos:nuevo.html.twig', array(
            'arRequisito' => $arRequisito,
            'form' => $form->createView()));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisito')->listaDql(
            $session->get('filtroIdentificacion')    
        );
    }

    private function formularioLista() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
                ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($arRequisito) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonCerrar = array('label' => 'Cerrar', 'disabled' => false);        
        $arrBotonDetalleEntregado = array('label' => 'Entregado', 'disabled' => false);
        $arrBotonDetalleNoAplica = array('label' => 'No aplica', 'disabled' => false);
        $arrBotonActualizarDetalle = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);

        if($arRequisito->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonDetalleEntregado['disabled'] = true;
            $arrBotonDetalleNoAplica['disabled'] = true;
            $arrBotonEliminarDetalle['disabled'] = true;
            $arrBotonActualizarDetalle['disabled'] = true;
            if($arRequisito->getEstadoCerrado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonCerrar['disabled'] = true;
            }
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
            $arrBotonCerrar['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnCerrar', 'submit', $arrBotonCerrar)
                    ->add('BtnDetalleEntregado', 'submit', $arrBotonDetalleEntregado)
                    ->add('BtnDetalleNoAplica', 'submit', $arrBotonDetalleNoAplica)
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)
                    ->add('BtnEliminarDetalle', 'submit', $arrBotonEliminarDetalle)
                    ->add('BtnActualizarDetalle', 'submit', $arrBotonActualizarDetalle)
                    ->getForm();
        return $form;


    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'IDENTIFICACIÓN')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'CARGO');
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        //$arRequisitos = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisito();
        $arRequisitos = $query->getResult();
        foreach ($arRequisitos as $arRequisito) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRequisito->getCodigoRequisitoPk())
                    ->setCellValue('B' . $i, $arRequisito->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arRequisito->getNombreCorto())
                    ->setCellValue('D' . $i, $arRequisito->getCargoRel()->getNombre());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('requisitos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Requisitos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

}
