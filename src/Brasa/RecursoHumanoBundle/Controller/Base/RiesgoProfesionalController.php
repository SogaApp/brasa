<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuRiesgoProfesionalType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;


class RiesgoProfesionalController extends Controller
{
    /**
     * @Route("/rhu/base/riesgoProfesional/listar", name="brs_rhu_base_riesgoProfesional_listar")
     */
    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 68, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnPdf', 'submit', array('label'  => 'PDF'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arEntidadRiesgosProfesionales = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
               try{
                    foreach ($arrSeleccionados AS $codigoEntidadRiesgoProfesionalPk) {
                        $arEntidadRiesgosProfesionales = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional();
                        $arEntidadRiesgosProfesionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->find($codigoEntidadRiesgoProfesionalPk);
                        $em->remove($arEntidadRiesgosProfesionales);
                    }
                    $em->flush();
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar la entidad de riesgos porque esta siendo utilizado', $this);
                  }     
            }
            
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoEntidadRiesgoProfesional = new \Brasa\RecursoHumanoBundle\Formatos\FormatoEntidadRiesgoProfesional();
                $objFormatoEntidadRiesgoProfesional->Generar($this);
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
                            ->setCellValue('E1', 'Teléfono')
                            ->setCellValue('F1', 'Código_interface');
                $i = 2;
                $arEntidadRiesgosProfesionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->findAll();
                
                foreach ($arEntidadRiesgosProfesionales as $arEntidadRiesgosProfesionales) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arEntidadRiesgosProfesionales->getCodigoEntidadRiesgoPk())
                            ->setCellValue('B' . $i, $arEntidadRiesgosProfesionales->getNombre())
                            ->setCellValue('C' . $i, $arEntidadRiesgosProfesionales->getNit())
                            ->setCellValue('D' . $i, $arEntidadRiesgosProfesionales->getDireccion())
                            ->setCellValue('E' . $i, $arEntidadRiesgosProfesionales->getTelefono())
                            ->setCellValue('F' . $i, $arEntidadRiesgosProfesionales->getCodigoInterface());
                    $i++;
                }
                $objPHPExcel->getActiveSheet()->setTitle('Riesgos_Profesionales');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Entidades_riesgos_Profesionales.xlsx"');
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
        $arEntidadesRiesgosProfesionales = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->findAll();
        $arEntidadesRiesgosProfesionales = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/RiesgoProfesional:listar.html.twig', array(
                    'arEntidadesRiesgosProfesionales' => $arEntidadesRiesgosProfesionales,
                    'form'=> $form->createView()
           
        ));
    }
    
    
    /**
     * @Route("/rhu/base/riesgoProfesional/nuevo/{codigoEntidadRiesgoProfesionalPk}", name="brs_rhu_base_riesgoProfesional_nuevo")
     */
    public function nuevoAction($codigoEntidadRiesgoProfesionalPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arEntidadRiesgoProfesional = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional();
        if ($codigoEntidadRiesgoProfesionalPk != 0)
        {
            $arEntidadRiesgoProfesional = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->find($codigoEntidadRiesgoProfesionalPk);
        }    
        $formEntidadRiesgoProfesional = $this->createForm(new RhuRiesgoProfesionalType(), $arEntidadRiesgoProfesional);
        $formEntidadRiesgoProfesional->handleRequest($request);
        if ($formEntidadRiesgoProfesional->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arEntidadRiesgoProfesional);
            $arCaja = $formEntidadRiesgoProfesional->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_riesgoProfesional_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/RiesgoProfesional:nuevo.html.twig', array(
            'formEntidadRiesgoProfesional' => $formEntidadRiesgoProfesional->createView(),
        ));
    }
}
