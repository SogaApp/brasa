<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuExamenType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuExamenDetalleType;

class ExamenController extends Controller
{
    public function listaAction() {        
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();        
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->listar();          
        if ($form->isValid()) {            
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if ($form->get('BtnEliminar')->isClicked()) {    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->eliminarExamen($arrSeleccionados);
            }
            if ($form->get('BtnFiltrar')->isClicked()) {    
                $this->filtrar($form);
                $this->listar();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }            
        }                      
        $arExamenes = $paginator->paginate($em->createQuery($session->get('dqlExamenLista')), $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:Examen:lista.html.twig', array('arExamenes' => $arExamenes, 'form' => $form->createView()));     
    } 

    public function nuevoAction($codigoExamen) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        if($codigoExamen != 0) {
            $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
        }
        $form = $this->createForm(new RhuExamenType, $arExamen);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arExamen = $form->getData();
            $em->persist($arExamen);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_examen_nuevo', array('codigoExamen' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_examen_listar'));
            }

        }

        return $this->render('BrasaRecursoHumanoBundle:Examen:nuevo.html.twig', array(
            'arExamen' => $arExamen,
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoExamen) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');                     
        $form = $this->formularioDetalle();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if($form->get('BtnImprimir')->isClicked()) {                
                $objSeleccionGrupo = new \Brasa\RecursoHumanoBundle\Formatos\FormatoExamenDetalle();
                $objSeleccionGrupo->Generar($this, $codigoExamen);
            }
            if($form->get('BtnEliminar')->isClicked()) {                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->eliminarDetallesSeleccionados();
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));           
            }                      
        }        
        
        //$dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuExamen c where c.codigoExamenPk = $codigoExamen";
        //$query = $em->createQuery($dql);        
        //$arExamen = $query->getResult();
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
        $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
        $arExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array ('codigoExamenFk' => $codigoExamen));
        return $this->render('BrasaRecursoHumanoBundle:Examen:detalle.html.twig', array(
                    'arExamen' => $arExamen,
                    'arExamenDetalle' => $arExamenDetalle,
                    'form' => $form->createView()
                    ));
    }
    
    public function detallenuevoAction($codigoExamen) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
        $arExamenTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->findAll();
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) { 
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoExamenTipo) {                    
                        $arExamenTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->find($codigoExamenTipo);
                        $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
                        $arExamenDetalle->setExamenTipoRel($arExamenTipo); 
                        $arExamenDetalle->setExamenRel($arExamen);
                        //$douPrecio = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->devuelvePrecio($arExamen->getCodigoEntidadExamenFk(), $codigoExamenTipo);
                        $em->persist($arExamenDetalle);                    
                    }
                    $em->flush();
                }                
            }            
            return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));           
        }

        return $this->render('BrasaRecursoHumanoBundle:Examen:detallenuevo.html.twig', array(
            'arExamenDetalle' => $arExamenDetalle,
            'arExamenTipos' => $arExamenTipos,
            'arExamen' => $arExamen,
            'form' => $form->createView()));
    }    
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $session->set('dqlExamenLista', $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->listaDQL($session->get('filtroNombreExamen'), $session->get('filtroAprobadoExamen')));  
    }
    
    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $session->set('filtroNombreExamen', $form->get('TxtNombre')->getData());                
        $session->set('filtroAprobadoExamen', $form->get('estadoAprobado')->getData());                
    }
    
    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("JG Efectivos")
            ->setLastModifiedBy("JG Efectivos")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Codigo')
                    ->setCellValue('B1', 'Fecha')
                    ->setCellValue('C1', 'Nombre')
                    ->setCellValue('D1', 'Centro costo')
                    ->setCellValue('E1', 'Cantidad_solicitada')
                    ->setCellValue('F1', 'Abierto');
                    

        $i = 2;
        $query = $em->createQuery($session->get('dqlSeleccionGrupoLista'));
        $arSeleccionGrupos = $query->getResult();
        foreach ($arSeleccionGrupos as $arSeleccionGrupo) {
            $strNombreCentroCosto = "";
            if($arSeleccionGrupo->getCentroCostoRel()) {
                $strNombreCentroCosto = $arSeleccionGrupo->getCentroCostoRel()->getNombre();
            }
            if ($arSeleccionGrupo->getEstadoAbierto() == 1){
                $abierto = "SI";
            } else {
                $abierto = "NO";
            }
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSeleccionGrupo->getCodigoSeleccionGrupoPk())
                    ->setCellValue('B' . $i, $arSeleccionGrupo->getFecha())
                    ->setCellValue('C' . $i, $arSeleccionGrupo->getNombre())
                    ->setCellValue('D' . $i, $strNombreCentroCosto)
                    ->setCellValue('E' . $i, $arSeleccionGrupo->getCantidadSolicitida())
                    ->setCellValue('F' . $i, $abierto);
                    
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('GruposSeleccion');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="GruposSeleccion.xlsx"');
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
    
    private function formularioFiltro() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombreSeleccionGrupo')))
            ->add('estadoAprobado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAprobadoSeleccionGrupo'))) 
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnEstadoAprobar', 'submit', array('label'  => 'Aprobar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }   
    
    private function formularioDetalle() {        
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();        
        return $form;
    }    
        
}
