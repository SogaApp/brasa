<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuAcademiaType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

//use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
//use Doctrine\DBAL\Driver\PDOException;

/**
 * RhuAcademia controller.
 *
 */
class AcademiaController extends Controller
{
    /**
     * @Route("/rhu/base/academia/listar", name="brs_rhu_base_academia_listar")
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 52, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arAcademias = new \Brasa\RecursoHumanoBundle\Entity\RhuAcademia();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoAcademiaPk) {
                        $arAcademia = new \Brasa\RecursoHumanoBundle\Entity\RhuAcademia();
                        $arAcademia = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcademia')->find($codigoAcademiaPk);
                        $em->remove($arAcademia);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_academia_listar'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar la academia porque esta siendo utilizado', $this);
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
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CODIGO')
                            ->setCellValue('B1', 'NOMBRE')
                            ->setCellValue('C1', 'NIT')
                            ->setCellValue('D1', 'CIUDAD')
                            ->setCellValue('E1', 'SEDE')
                            ->setCellValue('F1', 'TELEFONO')
                            ->setCellValue('G1', 'DIRECCION');

                $i = 2;
                $arAcademias = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcademia')->findAll();
                
                foreach ($arAcademias as $arAcademias) {
                    $ciudad= "";
                    if ($arAcademias->getCodigoCiudadFk() != null){
                        $ciudad = $arAcademias->getCiudadRel()->getNombre();
                    }
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arAcademias->getCodigoAcademiaPk())
                            ->setCellValue('B' . $i, $arAcademias->getNombre())
                            ->setCellValue('C' . $i, $arAcademias->getNit())
                            ->setCellValue('D' . $i, $ciudad)
                            ->setCellValue('E' . $i, $arAcademias->getSede())
                            ->setCellValue('F' . $i, $arAcademias->getTelefono())
                            ->setCellValue('G' . $i, $arAcademias->getDireccion());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Academias');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Academias.xlsx"');
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
        $arAcademias = new \Brasa\RecursoHumanoBundle\Entity\RhuAcademia();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcademia')->findAll();
        $arAcademias = $paginator->paginate($query, $this->get('Request')->query->get('page', 1),40);

        return $this->render('BrasaRecursoHumanoBundle:Base/Academia:listar.html.twig', array(
                    'arAcademias' => $arAcademias,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/academia/nuevo/{codigoAcademiaPk}", name="brs_rhu_base_academia_nuevo")
     */
    public function nuevoAction(Request $request, $codigoAcademiaPk) {
        $em = $this->getDoctrine()->getManager();
        $arAcademia = new \Brasa\RecursoHumanoBundle\Entity\RhuAcademia();
        if ($codigoAcademiaPk != 0)
        {
            $arAcademia = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcademia')->find($codigoAcademiaPk);
        }    
        $form = $this->createForm(RhuAcademiaType::class, $arAcademia);     
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arAcademia = $form->getData();
            $em->persist($arAcademia);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_academia_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Academia:nuevo.html.twig', array(
            'formAcademia' => $form->createView(),
        ));
    }
    
}
