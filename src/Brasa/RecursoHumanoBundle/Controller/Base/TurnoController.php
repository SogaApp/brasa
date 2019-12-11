<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuTurnoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuTurno controller.
 *
 */
class TurnoController extends Controller
{
    /**
     * @Route("/rhu/base/turno/listar", name="brs_rhu_base_turno_listar")
     */
    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 60, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arTurnos = new \Brasa\RecursoHumanoBundle\Entity\RhuTurno();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoTurnoPk) {
                        $arTurno = new \Brasa\RecursoHumanoBundle\Entity\RhuTurno();
                        $arTurno = $em->getRepository('BrasaRecursoHumanoBundle:RhuTurno')->find($codigoTurnoPk);
                        $em->remove($arTurno);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_turno_listar'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el turno porque esta siendo utilizado', $this);
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
                            ->setCellValue('C1', 'HORA DESDE')
                            ->setCellValue('D1', 'HORA HASTA')
                            ->setCellValue('E1', 'HORAS')
                            ->setCellValue('F1', 'HORAS DIURNAS')
                            ->setCellValue('G1', 'HORAS NOCTURNAS')
                            ->setCellValue('H1', 'NOVEDAD')
                            ->setCellValue('I1', 'DESCANSO')
                            ->setCellValue('J1', 'COMENTARIOS');

                $i = 2;
                $arTurnos = $em->getRepository('BrasaRecursoHumanoBundle:RhuTurno')->findAll();
                
                foreach ($arTurnos as $arTurno) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arTurno->getCodigoTurnoPk())
                            ->setCellValue('B' . $i, $arTurno->getNombre())
                            ->setCellValue('C' . $i, $arTurno->getHoraDesde()->format('H:i:s'))
                            ->setCellValue('D' . $i, $arTurno->getHoraHasta()->format('H:i:s'))
                            ->setCellValue('E' . $i, $arTurno->getHoras())
                            ->setCellValue('F' . $i, $arTurno->getHorasDiurnas())
                            ->setCellValue('G' . $i, $arTurno->getHorasNocturnas())
                            ->setCellValue('H' . $i, $arTurno->getNovedad())
                            ->setCellValue('I' . $i, $arTurno->getDescanso())
                            ->setCellValue('J' . $i, $arTurno->getComentarios());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Turnos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Turnos.xlsx"');
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
        $arTurnos = new \Brasa\RecursoHumanoBundle\Entity\RhuTurno();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuTurno')->findAll();
        $arTurnos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/Turno:listar.html.twig', array(
                    'arTurnos' => $arTurnos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/turno/nuevo/{codigoTurnoPk}", name="brs_rhu_base_turno_nuevo")
     */
    public function nuevoAction($codigoTurnoPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arTurno = new \Brasa\RecursoHumanoBundle\Entity\RhuTurno();
        if ($codigoTurnoPk != "0")
        {
            $arTurno = $em->getRepository('BrasaRecursoHumanoBundle:RhuTurno')->find($codigoTurnoPk);
        }    
        $formTurno = $this->createForm(new RhuTurnoType(), $arTurno);
        $formTurno->handleRequest($request);
        if ($formTurno->isValid())
        {
            // guardar la tarea en la base de datos
            $arTurno = $formTurno->getData();
            $em->persist($arTurno);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_turno_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Turno:nuevo.html.twig', array(
            'formTurno' => $formTurno->createView(),
        ));
    }
    
}
