<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPensionType;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuEntidadPension controller.
 *
 */
class PensionController extends Controller
{
    /**
     * @Route("/rhu/base/pension/listar", name="brs_rhu_base_pension_listar")
     */
    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 63, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder() //
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF'))                
            ->getForm(); 
        $form->handleRequest($request);
        
        $arPensiones = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoEntidadPensionPk) {
                        $arPension = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
                        $arPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->find($codigoEntidadPensionPk);
                        $em->remove($arPension);
                    }
                    $em->flush();
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar la entidad de pension porque esta siendo utilizado', $this);
                  }    
            }
        
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoPension = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPension();
                $objFormatoPension->Generar($this);
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
                            ->setCellValue('D1', 'Direccion')
                            ->setCellValue('E1', 'Telefono')
                            ->setCellValue('F1', 'Codigo_interface');

                $i = 2;
                $arPensiones = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->findAll();
                
                
                foreach ($arPensiones as $arPension) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arPension->getcodigoEntidadPensionPk())
                            ->setCellValue('B' . $i, $arPension->getnombre())
                            ->setCellValue('C' . $i, $arPension->getnit())
                            ->setCellValue('D' . $i, $arPension->getdireccion())
                            ->setCellValue('E' . $i, $arPension->gettelefono())
                            ->setCellValue('F' . $i, $arPension->getCodigoInterface());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Entidades_Pension');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Pension.xlsx"');
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
        
        $arEntidadesPension = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->findAll();
        $arEntidadesPension = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/Pension:listar.html.twig', array(
                    'arEntidadesPension' => $arEntidadesPension,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/pension/nuevo/{codigoEntidadPensionPk}", name="brs_rhu_base_pension_nuevo")
     */
    public function nuevoAction($codigoEntidadPensionPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arPension = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
        if ($codigoEntidadPensionPk != 0)
        {
            $arPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->find($codigoEntidadPensionPk);
        }    
        $formPension = $this->createForm(new RhuPensionType(), $arPension);
        $formPension->handleRequest($request);
        if ($formPension->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arPension);
            $arPension = $formPension->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_pension_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Pension:nuevo.html.twig', array(
            'formPension' => $formPension->createView(),
        ));
    }
    
}
