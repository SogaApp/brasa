<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuClasificacionRiesgosType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuClasificacionRiesgos controller.
 *
 */
class ClasificacionRiesgosController extends Controller
{
    /**
     * @Route("/rhu/base/clasificacion/listar", name="brs_rhu_base_clasificacion_listar")
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 67, 1)) {
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
        $arCargos = new \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoClasificacionPk) {
                        $arClasificacion = new \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo();
                        $arClasificacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuClasificacionRiesgo')->find($codigoClasificacionPk);
                        $em->remove($arClasificacion);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_clasificacion_listar'));
                    } catch (ForeignKeyConstraintViolationException $e) { 
                        $objMensaje->Mensaje('error', 'No se puede eliminar clasificacion de riesgos porque esta siendo utilizado', $this);
                      }
            }
            
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoClasificacion = new \Brasa\RecursoHumanoBundle\Formatos\FormatoClasificacion();
                $objFormatoClasificacion->Generar($this);
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
                            ->setCellValue('A1', 'Codigo')
                            ->setCellValue('B1', 'Nombre')
                            ->setCellValue('C1', 'Porcentaje');

                $i = 2;
                $arClasificaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuClasificacionRiesgo')->findAll();
                
                foreach ($arClasificaciones as $arClasificaciones) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arClasificaciones->getCodigoClasificacionRiesgoPk())
                            ->setCellValue('B' . $i, $arClasificaciones->getNombre())
                            ->setCellValue('C' . $i, $arClasificaciones->getPorcentaje());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('ClasificacionRiesgos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="ClasificacionRiesgos.xlsx"');
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
        $arClasificaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuClasificacionRiesgo')->findAll();
        $arClasificaciones = $paginator->paginate($query, $this->get('Request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/Clasificacion:listar.html.twig', array(
                    'arClasificaciones' => $arClasificaciones,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/clasificacion/nuevo/{codigoClasificacionPk}", name="brs_rhu_base_clasificacion_nuevo")
     */
    public function nuevoAction(Request $request, $codigoClasificacionPk) {
        $em = $this->getDoctrine()->getManager();
        $arClasificacion = new \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo();
        if ($codigoClasificacionPk != 0)
        {
            $arClasificacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuClasificacionRiesgo')->find($codigoClasificacionPk);
        }    
        $form = $this->createForm(RhuClasificacionRiesgosType::class, $arClasificacion); 
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arClasificacion);
            $arClasificacion = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_clasificacion_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Clasificacion:nuevo.html.twig', array(
            'formClasificacion' => $form->createView(),
        ));
    }
    
}
