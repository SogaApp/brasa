<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionEntrevistaTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuSeleccionEntrevistaTipo controller.
 *
 */
class SeleccionEntrevistaTipoController extends Controller
{

    /**
     * @Route("/rhu/base/seleccion/entrevista/tipo/listar", name="brs_rhu_base_seleccion_entrevista_tipo_listar")
     */
    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 50, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arSeleccionEntrevistaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevistaTipo();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoSeleccionEntrevistaTipoPk) {
                        $arSeleccionEntrevistaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevistaTipo();
                        $arSeleccionEntrevistaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionEntrevistaTipo')->find($codigoSeleccionEntrevistaTipoPk);
                        $em->remove($arSeleccionEntrevistaTipo);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_seleccion_entrevista_tipo_listar'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el tipo de entrevista porque esta siendo utilizado', $this);
                  }    
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
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'NOMBRE');

                $i = 2;
                $arSeleccionEntrevistaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionEntrevistaTipo')->findAll();
                
                foreach ($arSeleccionEntrevistaTipo as $arSeleccionEntrevistaTipo) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arSeleccionEntrevistaTipo->getCodigoSeleccionEntrevistaTipoPk())
                            ->setCellValue('B' . $i, $arSeleccionEntrevistaTipo->getNombre());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Selección entrevista tipo');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="SeleccionEntrevistaTipo.xlsx"');
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
        $arSeleccionEntrevistasTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevistaTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionEntrevistaTipo')->findAll();
        $arSeleccionEntrevistasTipo = $paginator->paginate($query, $this->get('request')->query->get('page', 1),50);

        return $this->render('BrasaRecursoHumanoBundle:Base/SeleccionEntrevistaTipo:listar.html.twig', array(
                    'arSeleccionEntrevistasTipo' => $arSeleccionEntrevistasTipo,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/seleccion/entrevista/tipo/nuevo{codigoSeleccionEntrevistaTipo}", name="brs_rhu_base_seleccion_entrevista_tipo_nuevo")
     */
    public function nuevoAction($codigoSeleccionEntrevistaTipo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arSeleccionEntrevistaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevistaTipo();
        if ($codigoSeleccionEntrevistaTipo != 0)
        {
            $arSeleccionEntrevistaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionEntrevistaTipo')->find($codigoSeleccionEntrevistaTipo);
        }    
        $formSeleccionEntrevistaTipo = $this->createForm(new RhuSeleccionEntrevistaTipoType(), $arSeleccionEntrevistaTipo);
        $formSeleccionEntrevistaTipo->handleRequest($request);
        if ($formSeleccionEntrevistaTipo->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arSeleccionEntrevistaTipo);
            $arSeleccionEntrevistaTipo = $formSeleccionEntrevistaTipo->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_seleccion_entrevista_tipo_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/SeleccionEntrevistaTipo:nuevo.html.twig', array(
            'formSeleccionEntrevistaTipo' => $formSeleccionEntrevistaTipo->createView(),
        ));
    }
}
