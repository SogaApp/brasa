<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;



use Brasa\RecursoHumanoBundle\Form\Type\RhuEntidadExamenType;

/**
 * RhuEntidadExamen controller.
 *
 */
class BaseEntidadExamenController extends Controller
{

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnPdf', 'submit', array('label'  => 'PDF'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoEntidadExamenPk) {
                    $arEntidadExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen();
                    $arEntidadExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadExamen')->find($codigoEntidadExamenPk);
                    $em->remove($arEntidadExamen);
                    $em->flush();
                }
            }
        
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoEntidadExamen = new \Brasa\RecursoHumanoBundle\Formatos\FormatoEntidadExamen();
                $objFormatoEntidadExamen->Generar($this);
        }    
        if($form->get('BtnExcel')->isClicked()) {
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
                            ->setCellValue('A1', 'Código')
                            ->setCellValue('B1', 'Nombre')
                            ->setCellValue('C1', 'Nit')
                            ->setCellValue('D1', 'Dirección')
                            ->setCellValue('E1', 'Teléfono');

                $i = 2;
                $arEntidadesExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadExamen')->findAll();
                
                foreach ($arEntidadesExamen as $arEntidadesExamen) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arEntidadesExamen->getcodigoEntidadExamenPk())
                            ->setCellValue('B' . $i, $arEntidadesExamen->getnombre())
                            ->setCellValue('C' . $i, $arEntidadesExamen->getnit())
                            ->setCellValue('D' . $i, $arEntidadesExamen->getdireccion())
                            ->setCellValue('E' . $i, $arEntidadesExamen->gettelefono());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Entidades_Examenes');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Entidad_Examen.xlsx"');
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
        $arEntidadesExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadExamen')->findAll();
        $arEntidadesExamen = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/EntidadExamen:listar.html.twig', array(
                    'arEntidadesExamen' => $arEntidadesExamen,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoEntidadExamenPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arEntidadExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen();
        if ($codigoEntidadExamenPk != 0)
        {
            $arEntidadExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadExamen')->find($codigoEntidadExamenPk);
        }    
        $formEntidadExamen = $this->createForm(new RhuEntidadExamenType(), $arEntidadExamen);
        $formEntidadExamen->handleRequest($request);
        if ($formEntidadExamen->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arEntidadExamen);
            $arEntidadExamen = $formEntidadExamen->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_entidadexamen_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/EntidadExamen:nuevo.html.twig', array(
            'formEntidadExamen' => $formEntidadExamen->createView(),
        ));
    }
    
    public function detalleAction($codigoEntidadExamenPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()                                   
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnActualizar', 'submit', array('label'  => 'Actualizar',))    
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            
            if($form->get('BtnEliminar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoDetalleTipo) {
                        $arExamenListaPrecio = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->find($codigoDetalleTipo);                        
                        $em->persist($arExamenListaPrecio);
                        $em->remove($arExamenListaPrecio);                        
                    }
                    $em->flush();  
                    return $this->redirect($this->generateUrl('brs_rhu_base_entidadexamen_detalle', array('codigoEntidadExamenPk' => $codigoEntidadExamenPk)));
                }
            }
            if ($form->get('BtnActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                $arEntidadExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenListaPrecio();
                $arEntidadExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->findBy(array('codigoEntidadExamenFk' => $codigoEntidadExamenPk));
                foreach ($arEntidadExamenDetalle AS $arEntidadExamenDetalle) {                    
                    $arEntidadExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->find($arEntidadExamenDetalle);
                    $intPrecio = $arrControles['TxtPrecio'][$intIndice];
                    $arEntidadExamenDetalle->setPrecio($intPrecio);                                                
                    $em->persist($arEntidadExamenDetalle);
                    $intIndice++;
                }
                $em->flush();
            }
        } 
        $arEntidadExamenes = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen();
        $arEntidadExamenes = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadExamen')->find($codigoEntidadExamenPk);
        $arEntidadExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenListaPrecio();
        $arEntidadExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->findBy(array('codigoEntidadExamenFk' => $codigoEntidadExamenPk));
        return $this->render('BrasaRecursoHumanoBundle:Base/EntidadExamen:detalle.html.twig', array(
                    'arEntidadExamenes' => $arEntidadExamenes,
                    'arEntidadExamenDetalle' => $arEntidadExamenDetalle,
                    'form' => $form->createView()
                    ));
    } 
    
    public function detalleNuevoAction($codigoEntidadExamenPk) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arEntidadExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen();
        $arEntidadExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadExamen')->find($codigoEntidadExamenPk);
        $arExamenTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo();
        $arExamenTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->findAll();
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('BtnGuardar')->isClicked()) {
                if (isset($arrControles['TxtPrecio'])) {
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        if($arrControles['TxtPrecio'][$intIndice] > 0 ){
                            $intDato = 0;
                            $arExamenTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo();
                            $arExamenTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->find($intCodigo);
                            $arExamenListaPrecios = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->find($intCodigo);
                            $arEntidadExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenListaPrecio();
                            $arEntidadExamenDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->findBy(array('codigoEntidadExamenFk' => $codigoEntidadExamenPk));
                            foreach ($arEntidadExamenDetalles as $arEntidadExamenDetalles){
                               if ($arEntidadExamenDetalles->getCodigoExamenTipoFk() == $intCodigo){
                                   $intDato++;
                               } 
                            }
                            if ($intDato == 0){
                                $arEntidadExamenDetalle->setEntidadExamenRel($arEntidadExamen);
                                $arEntidadExamenDetalle->setExamenTipoRel($arExamenTipos);
                                $duoPrecio = $arrControles['TxtPrecio'][$intIndice];
                                $arEntidadExamenDetalle->setPrecio($duoPrecio);
                                $em->persist($arEntidadExamenDetalle);
                            }
                                                            
                        }                        
                        $intIndice++;
                    }
                }
                $em->flush();                                        
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/EntidadExamen:detallenuevo.html.twig', array(
            'arEntidadExamen' => $arEntidadExamen,
            'arExamenTipos' => $arExamenTipos,
            'form' => $form->createView()));
    }
}
