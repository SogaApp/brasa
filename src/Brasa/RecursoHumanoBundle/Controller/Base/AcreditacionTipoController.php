<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuAcreditacionTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

//use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
//use Doctrine\DBAL\Driver\PDOException;

/**
 * AcreditacionTipoController.
 *
 */
class AcreditacionTipoController extends Controller
{
    /**
     * @Route("/rhu/base/acreditacion/tipo/lista", name="brs_rhu_base_acreditacion_tipo_lista")
     */ 
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 53, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arAcreditacionesTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacionTipo();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoAcreditacionTipoPk) {
                        $arAcreditacionesTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacionTipo();
                        $arAcreditacionesTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacionTipo')->find($codigoAcreditacionTipoPk);
                        $em->remove($arAcreditacionesTipo);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_acreditacion_tipo_lista'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el tipo de acreditacion porque esta siendo utilizado', $this);
                  }     
            }   
        
        if($form->get('BtnExcel')->isClicked()) {
            ob_clean();
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
                for($col = 'A'; $col !== 'Z'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
                }
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CODIGO')
                            ->setCellValue('B1', 'CODIGO ESTUDIO')
                            ->setCellValue('C1', 'NOMBRE')
                            ->setCellValue('D1', 'CARGO');

                $i = 2;
                $arAcreditacionTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacionTipo')->findAll();
                
                foreach ($arAcreditacionTipo as $arAcreditacionTipo) {
                       
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arAcreditacionTipo->getCodigoAcreditacionTipoPk())
                            ->setCellValue('B' . $i, $arAcreditacionTipo->getCodigo())
                            ->setCellValue('C' . $i, $arAcreditacionTipo->getNombre())
                            ->setCellValue('D' . $i, $arAcreditacionTipo->getCargo());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('TipoAcreditacion');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="TipoAcreditacion.xlsx"');
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
        $arAcreditacionTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacionTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacionTipo')->findAll();
        $arAcreditacionTipo = $paginator->paginate($query, $this->get('Request')->query->get('page', 1),40);

        return $this->render('BrasaRecursoHumanoBundle:Base/AcreditacionTipo:listar.html.twig', array(
                    'arAcreditacionTipo' => $arAcreditacionTipo,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/acreditacion/tipo/nuevo/{codigoAcreditacionTipoPk}", name="brs_rhu_base_acreditacion_tipo_nuevo")
     */ 
    public function nuevoAction(Request $request, $codigoAcreditacionTipoPk) {
        $em = $this->getDoctrine()->getManager();
        $arAcreditacionTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacionTipo();
        if ($codigoAcreditacionTipoPk != 0)
        {
            $arAcreditacionTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacionTipo')->find($codigoAcreditacionTipoPk);
        }    
        $form = $this->createForm(RhuAcreditacionTipoType::class, $arAcreditacionTipo);    
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arAcreditacionTipo = $form->getData();
            $em->persist($arAcreditacionTipo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_acreditacion_tipo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/AcreditacionTipo:nuevo.html.twig', array(
            'formAcreditacionTipo' => $form->createView(),
        ));
    }
    
}
