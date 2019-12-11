<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuHorarioType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuHorario controller.
 *
 */
class HorarioController extends Controller
{
    /**
     * @Route("/rhu/base/horario/listar", name="brs_rhu_base_horario_listar")
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 59, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arHorarios = new \Brasa\RecursoHumanoBundle\Entity\RhuHorario();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoHorarioPk) {
                        $arHorario = new \Brasa\RecursoHumanoBundle\Entity\RhuHorario();
                        $arHorario = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorario')->find($codigoHorarioPk);
                        $em->remove($arHorario);
                    }
                     $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_horario_listar'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el horario porque esta siendo utilizado', $this);
                  }    
            }    
        
        if($form->get('BtnExcel')->isClicked()) {
                $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
                ob_clean();
                $em = $this->getDoctrine()->getManager();  
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
                            ->setCellValue('B1', 'NOMBRE')
                            ->setCellValue('C1', 'HORA ENTRADA')
                            ->setCellValue('D1', 'HORA SALIDA')
                            ->setCellValue('E1', 'HORA GENERA HE')
                            ->setCellValue('F1', 'HORA LUNES')
                            ->setCellValue('G1', 'HORA MARTES')
                            ->setCellValue('H1', 'HORA MIERCOLES')
                            ->setCellValue('I1', 'HORA JUEVES')
                            ->setCellValue('J1', 'HORA VIERNES')
                            ->setCellValue('H1', 'HORA SABADO')
                            ->setCellValue('L1', 'HORA DOMINGO')
                            ->setCellValue('M1', 'HORA FESTIVO');

                $i = 2;
                $arHorarios = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorario')->findAll();
                
                foreach ($arHorarios as $arHorarios) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arHorarios->getCodigoHorarioPk())
                            ->setCellValue('B' . $i, $arHorarios->getNombre())
                            ->setCellValue('C' . $i, $arHorarios->getHoraEntrada()->format('H:i:s'))
                            ->setCellValue('D' . $i, $arHorarios->getHoraSalida()->format('H:i:s'))
                            ->setCellValue('E' . $i, $objFunciones->devuelveBoolean($arHorarios->getGeneraHoraExtra()))
                            ->setCellValue('F' . $i, $arHorarios->getLunes())
                            ->setCellValue('G' . $i, $arHorarios->getMartes())
                            ->setCellValue('H' . $i, $arHorarios->getMiercoles())
                            ->setCellValue('I' . $i, $arHorarios->getJueves())
                            ->setCellValue('J' . $i, $arHorarios->getViernes())
                            ->setCellValue('K' . $i, $arHorarios->getSabado())
                            ->setCellValue('L' . $i, $arHorarios->getDomingo())
                            ->setCellValue('M' . $i, $arHorarios->getFestivo());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Horarios');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Horarios.xlsx"');
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
        $arHorarios = new \Brasa\RecursoHumanoBundle\Entity\RhuHorario();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorario')->findAll();
        $arHorarios = $paginator->paginate($query, $this->get('Request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/Horario:listar.html.twig', array(
                    'arHorarios' => $arHorarios,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/horario/nuevo/{codigoHorarioPk}", name="brs_rhu_base_horario_nuevo")
     */
    public function nuevoAction(Request $request, $codigoHorarioPk) {
        $em = $this->getDoctrine()->getManager();
        $arHorario = new \Brasa\RecursoHumanoBundle\Entity\RhuHorario();
        if ($codigoHorarioPk != 0)
        {
            $arHorario = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorario')->find($codigoHorarioPk);
        }    
        $formHorario = $this->createForm(new RhuHorarioType(), $arHorario);
        $formHorario->handleRequest($request);
        if ($formHorario->isValid())
        {
            // guardar la tarea en la base de datos
            $arHorario = $formHorario->getData();
            $em->persist($arHorario);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_horario_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Horario:nuevo.html.twig', array(
            'formHorario' => $formHorario->createView(),
        ));
    }
    
}
