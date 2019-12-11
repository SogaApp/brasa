<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPensionTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuPensionTipo controller.
 *
 */
class PensionTipoController extends Controller
{
    /**
     * @Route("/rhu/base/pension/tipo/lista", name="brs_rhu_base_pension_tipo_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 66, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arPensionTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuTipoPension();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoPensionTipo) {
                        $arPensionTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuTipoPension();
                        $arPensionTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuTipoPension')->find($codigoPensionTipo);
                        $em->remove($arPensionTipo);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_pension_tipo_lista'));
               } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el tipo de pension porque esta siendo utilizado', $this);
                 }     
            }
              
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }
        }
        $arPensionTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuTipoPension();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuTipoPension')->findAll();
        $arPensionTipos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/PensionTipo:listar.html.twig', array(
                    'arPensionTipos' => $arPensionTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/pension/tipo/nuevo/{codigoPensionTipo}", name="brs_rhu_base_pension_tipo_nuevo")
     */
    public function nuevoAction($codigoPensionTipo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arPensionTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuTipoPension();
        if ($codigoPensionTipo != 0)
        {
            $arPensionTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuTipoPension')->find($codigoPensionTipo);
        }    
        $form = $this->createForm(new RhuPensionTipoType(), $arPensionTipo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arPensionTipo = $form->getData();
            $em->persist($arPensionTipo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_pension_tipo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/PensionTipo:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function generarExcel() {
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
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'NOMBRE')
                    ->setCellValue('C1', 'PORCENTAJE EMPLEADO')
                    ->setCellValue('D1', 'PORCENTAJE EMPLEADOR')
                    ->setCellValue('E1', 'CONCEPTO');
        $i = 2;
        $arPensionTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuTipoPension')->findAll();

        foreach ($arPensionTipos as $arPensionTipo) {
            if ($arPensionTipo->getCodigoPagoConceptoFk() == null){
                $pagoConcepto = "";
            } else {
                $pagoConcepto = $arPensionTipo->getPagoConceptoRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPensionTipo->getCodigoTipoPensionPk())
                    ->setCellValue('B' . $i, $arPensionTipo->getNombre())
                    ->setCellValue('C' . $i, $arPensionTipo->getPorcentajeEmpleado())
                    ->setCellValue('D' . $i, $arPensionTipo->getPorcentajeEmpleador())
                    ->setCellValue('E' . $i, $pagoConcepto);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PensionTipos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PensionTipos.xlsx"');
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
