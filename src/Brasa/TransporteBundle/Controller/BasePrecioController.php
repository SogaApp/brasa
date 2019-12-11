<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TransporteBundle\Form\Type\TtePrecioDetalleType;



class BasePrecioController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arPrecios = new \Brasa\TransporteBundle\Entity\TteListaPrecio();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoPrecio) {
                    $arPrecio = new \Brasa\TransporteBundle\Entity\TteListaPrecio();
                    $arPrecio = $em->getRepository('BrasaTransporteBundle:TteListaPrecio')->find($codigoPrecio);
                    $em->remove($arPrecio);
                    $em->flush();
                }
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
        }
        $arPrecios = new \Brasa\TransporteBundle\Entity\TteListaPrecio();
        $query = $em->getRepository('BrasaTransporteBundle:TteListaPrecio')->findAll();
        $arPrecios = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);

        return $this->render('BrasaTransporteBundle:Base/Precios:lista.html.twig', array(
                    'arPrecios' => $arPrecios,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoPrecio) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arPrecio = new \Brasa\TransporteBundle\Entity\TteListaPrecio();
        if ($codigoPrecio != 0)
        {
            $arPrecio = $em->getRepository('BrasaTransporteBundle:TteListaPrecio')->find($codigoPrecio);
        }else {
            $arPrecio->setFechaVencimiento(new \DateTime('now'));
        }   
        $form = $this->createForm(new TtePrecioType(), $arPrecio);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arPrecio = $form->getData();
            $em->persist($arPrecio);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tte_base_precios_lista'));
        }
        return $this->render('BrasaTransporteBundle:Base/Precios:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function detalleAction($codigoPrecio) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arPrecio = new \Brasa\TransporteBundle\Entity\TteListaPrecio();
        $arPrecio = $em->getRepository('BrasaTransporteBundle:TteListaPrecio')->find($codigoPrecio);
        $form = $this->formularioDetalle($arPrecio);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnEliminar')->isClicked()) {  
                $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPrecioDetalle) {
                        $arPrecioDetalle = new \Brasa\TransporteBundle\Entity\TteListaPrecioDetalle();
                        $arPrecioDetalle = $em->getRepository('BrasaTransporteBundle:TteListaPrecioDetalle')->find($codigoPrecioDetalle);                        
                        $em->remove($arPrecioDetalle);                        
                    }
                    $em->flush();                    
                } 
                return $this->redirect($this->generateUrl('brs_tte_base_precios_detalle', array('codigoPrecio' => $codigoPrecio)));
            } 
                        
        }
        $arPrecioDetalles = new \Brasa\TransporteBundle\Entity\TteListaPrecioDetalle();
        $arPrecioDetalles = $em->getRepository('BrasaTransporteBundle:TteListaPrecioDetalle')->findBy(array('codigoListaPrecioFk' => $codigoPrecio));
        return $this->render('BrasaTransporteBundle:Base/Precios:detalle.html.twig', array(
                        'arPrecioDetalles' => $arPrecioDetalles,
                        'arPrecio' => $arPrecio,
                        'form' => $form->createView()
                    ));
    }
    
    public function detallenuevoAction($codigoPrecioDetalle, $codigoPrecio) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arPrecio = new \Brasa\TransporteBundle\Entity\TteListaPrecio();
        $arPrecio = $em->getRepository('BrasaTransporteBundle:TteListaPrecio')->find($codigoPrecio);
        $arPrecioDetalle = new \Brasa\TransporteBundle\Entity\TteListaPrecioDetalle();
        if($codigoPrecioDetalle != 0) {
            $arPrecioDetalle = $em->getRepository('BrasaTransporteBundle:TteListaPrecioDetalle')->find($codigoPrecioDetalle);
        }
        $form = $this->createForm(new TtePrecioDetalleType(), $arPrecioDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPrecioDetalle = $form->getData();
            $arPrecioDetalle->setListaPrecioRel($arPrecio);
            $em->persist($arPrecioDetalle);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tte_base_precios_detalle_nuevo', array('codigoPrecioDetalle' => 0, 'codigoPrecio' => $codigoPrecio)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }

        return $this->render('BrasaTransporteBundle:Base/Precios:detallenuevo.html.twig', array(
            'form' => $form->createView()
            ));
    }
    
    private function formularioDetalle($ar) {
        
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);
        $form = $this->createFormBuilder()
                    ->add('BtnEliminar', 'submit', $arrBotonEliminarDetalle)
                    ->getForm();
        return $form;

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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'PRODUCTO')
                    ->setCellValue('C1', 'FECHA VENCIMIENTO');

        $i = 2;
        $arPrecios = $em->getRepository('BrasaTransporteBundle:TteListaPrecio')->findAll();

        foreach ($arPrecios as $arPrecio) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPrecio->getCodigoListaPrecioPk())
                    ->setCellValue('B' . $i, $arPrecio->getNombre())
                    ->setCellValue('C' . $i, $arPrecio->getFechaVencimiento());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Precios');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Precios.xlsx"');
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
