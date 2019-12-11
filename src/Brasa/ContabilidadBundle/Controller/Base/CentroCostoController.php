<?php

namespace Brasa\ContabilidadBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\ContabilidadBundle\Form\Type\CtbCentroCostoType;



class CentroCostoController extends Controller
{
    /**
     * @Route("/ctb/base/centro/costo/lista", name="brs_ctb_base_centro_costo_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 93, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arCentroCostos = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoCentroCosto) {
                    $arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();
                    $arCentroCosto = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find($codigoCentroCosto);
                    $em->remove($arCentroCosto);
                    $em->flush();
                }
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
        }
        $arCentroCostos = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();
        $query = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->findAll();
        $arCentroCostos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);

        return $this->render('BrasaContabilidadBundle:Base/CentroCosto:lista.html.twig', array(
                    'arCentroCostos' => $arCentroCostos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/ctb/base/centro/costo/nuevo/{codigoCentroCosto}", name="brs_ctb_base_centro_costo_nuevo")
     */
    public function nuevoAction($codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();
        if ($codigoCentroCosto != 0)
        {
            $arCentroCosto = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find($codigoCentroCosto);
        }    
        $form = $this->createForm(new CtbCentroCostoType(), $arCentroCosto);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arCentroCosto = $form->getData();
            $em->persist($arCentroCosto);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_ctb_base_centro_costo_lista'));
        }
        return $this->render('BrasaContabilidadBundle:Base/CentroCosto:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function generarExcel(){
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
                    ->setCellValue('B1', 'NOMBRE');

        $i = 2;
        $arCentroCostos = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->findAll();

        foreach ($arCentroCostos as $arCentroCostos) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCentroCostos->getCodigoCentroCostoPk())
                    ->setCellValue('B' . $i, $arCentroCostos->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('CentroCostos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CentroCostos.xlsx"');
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
