<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;


use Brasa\RecursoHumanoBundle\Form\Type\RhuEntidadExamenType;

/**
 * RhuEntidadExamen controller.
 *
 */
class EntidadExamenController extends Controller
{
    /**
     * @Route("/rhu/base/entidadexamen/listar", name="brs_rhu_base_entidadexamen_listar")
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 96, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnPdf', SubmitType::class, array('label'  => 'PDF'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoEntidadExamenPk) {
                        $arEntidadExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen();
                        $arEntidadExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadExamen')->find($codigoEntidadExamenPk);
                        $arEntidadExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->findOneBy(array('codigoEntidadExamenFk' => $codigoEntidadExamenPk));
                        if ($arEntidadExamenDetalle == null){
                            $em->remove($arEntidadExamen);
                        } else {
                            $objMensaje->Mensaje("error", "No se puede eliminar el registro " . $codigoEntidadExamenPk .", tiene detalles asociados", $this);
                        }
                        $em->flush();
                    }
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar la entidad examen porque esta siendo utilizado', $this);
                  }    
            }
        
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoEntidadExamen = new \Brasa\RecursoHumanoBundle\Formatos\FormatoEntidadExamen();
                $objFormatoEntidadExamen->Generar($this);
        }    
        if($form->get('BtnExcel')->isClicked()) {
                $objPHPExcel = new \PHPExcel();
                ob_clean();
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
        $arEntidadesExamen = $paginator->paginate($query, $this->get('Request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/EntidadExamen:listar.html.twig', array(
                    'arEntidadesExamen' => $arEntidadesExamen,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/entidadexamen/nuevo/{codigoEntidadExamenPk}", name="brs_rhu_base_entidadexamen_nuevo")
     */
    public function nuevoAction(Request $request, $codigoEntidadExamenPk) {
        $em = $this->getDoctrine()->getManager();
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
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            if($codigoEntidadExamenPk == 0) {
               $arEntidadExamen->setCodigoUsuario($arUsuario->getUserName());
            }
            $em->persist($arEntidadExamen);
            $arEntidadExamen = $formEntidadExamen->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_entidadexamen_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/EntidadExamen:nuevo.html.twig', array(
            'formEntidadExamen' => $formEntidadExamen->createView(),
        ));
    }
    
    /**
     * @Route("/rhu/base/entidadexamen/detalle/{codigoEntidadExamenPk}", name="brs_rhu_base_entidadexamen_detalle")
     */
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
    
    /**
     * @Route("/rhu/base/entidadexamen/detalle/nuevo/{codigoEntidadExamenPk}", name="brs_rhu_base_entidadexamen_detalle_nuevo")
     */
    public function detalleNuevoAction(Request $request, $codigoEntidadExamenPk) {
        $em = $this->getDoctrine()->getManager();        
        $arEntidadExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen();
        $arEntidadExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadExamen')->find($codigoEntidadExamenPk);
        $arExamenTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo();
        $arExamenTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->findAll();
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
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
                                $arEntidadExamenDetalle->setCodigoUsuario($arUsuario->getUserName());
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
