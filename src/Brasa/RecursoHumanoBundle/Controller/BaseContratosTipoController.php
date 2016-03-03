<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuContratoTipoType;

/**
 * RhuContratosTipo controller.
 *
 */
class BaseContratosTipoController extends Controller
{
    var $strDqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoContratoTipo) {
                    $arContratoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo();
                    $arContratoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoTipo')->find($codigoContratoTipo);
                    $em->remove($arContratoTipo);
                    $em->flush();                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_base_contrato_tipo_lista'));
            }                        
        }
        
        $arContratosTipos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);        
        return $this->render('BrasaRecursoHumanoBundle:Base/ContratoTipo:listar.html.twig', array(
                    'arContratosTipos' => $arContratosTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoContratoTipo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arContratoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo();
        if ($codigoContratoTipo != 0) {
            $arContratoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoTipo')->find($codigoContratoTipo);
        }    
        $form = $this->createForm(new RhuContratoTipoType(), $arContratoTipo);
        $form->handleRequest($request);
        if ($form->isValid()) {                        
            $arContratoTipo = $form->getData();
            $em->persist($arContratoTipo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_contrato_tipo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/ContratoTipo:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();        
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoTipo')->listaDql();         
    }        
    
    private function generarExcel() {
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
                    ->setCellValue('A1', 'Codigo')
                    ->setCellValue('B1', 'Nombre');
        $i = 2;
        $arContratoTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoTipo')->findAll();

        foreach ($arContratoTipos as $arContratoTipos) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arContratoTipos->getcodigoContratoTipoPk())
                    ->setCellValue('B' . $i, $arContratoTipos->getnombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Contrato_tipos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Contratos_Tipos.xlsx"');
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