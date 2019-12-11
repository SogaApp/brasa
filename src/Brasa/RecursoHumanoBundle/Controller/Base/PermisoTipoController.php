<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPermisoTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuPermisoTipo controller.
 *
 */
class PermisoTipoController extends Controller
{
    /**
     * @Route("/rhu/base/permiso/tipo/lista", name="brs_rhu_base_permiso_tipo_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 51, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arPermisoTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuPermisoTipo();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoPermisoTipoPk) {
                        $arPermisoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPermisoTipo();
                        $arPermisoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPermisoTipo')->find($codigoPermisoTipoPk);
                        $em->remove($arPermisoTipo);
                    }
                    $em->flush();
               } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el tipo de permiso porque esta siendo utilizado', $this);
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
                $arPermisoTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPermisoTipo')->findAll();
                
                foreach ($arPermisoTipos as $arPermisoTipo) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arPermisoTipo->getCodigoPermisoTipoPk())
                            ->setCellValue('B' . $i, $arPermisoTipo->getNombre());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Permisto tipos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="permisoTipos.xlsx"');
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
        $arPermisoTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuPermisoTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuPermisoTipo')->findAll();
        $arPermisoTipos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/PermisoTipo:listar.html.twig', array(
                    'arPermisoTipos' => $arPermisoTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/permiso/tipo/nuevo/{codigoPermisoTipoPk}", name="brs_rhu_base_permiso_tipo_nuevo")
     */
    public function nuevoAction($codigoPermisoTipoPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arPermisoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPermisoTipo();
        if ($codigoPermisoTipoPk != 0)
        {
            $arPermisoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPermisoTipo')->find($codigoPermisoTipoPk);
        }    
        $formPermisoTipo = $this->createForm(new RhuPermisoTipoType(), $arPermisoTipo);
        $formPermisoTipo->handleRequest($request);
        if ($formPermisoTipo->isValid())
        {
            // guardar la tarea en la base de datos
            $arPermisoTipo = $formPermisoTipo->getData();
            $em->persist($arPermisoTipo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_permiso_tipo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/PermisoTipo:nuevo.html.twig', array(
            'formPermisoTipo' => $formPermisoTipo->createView(),
        ));
    }
    
}
