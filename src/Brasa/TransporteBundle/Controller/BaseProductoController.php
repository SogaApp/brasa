<<<<<<< HEAD
<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TransporteBundle\Form\Type\TteProductoType;



class BaseProductoController extends Controller
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
        $arProductos = new \Brasa\TransporteBundle\Entity\TteProducto();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoProducto) {
                    $arProducto = new \Brasa\TransporteBundle\Entity\TteProducto();
                    $arProducto = $em->getRepository('BrasaTransporteBundle:TteProducto')->find($codigoProducto);
                    $em->remove($arProducto);
                    $em->flush();
                }
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
        }
        $arProductos = new \Brasa\TransporteBundle\Entity\TteProducto();
        $query = $em->getRepository('BrasaTransporteBundle:TteProducto')->findAll();
        $arProductos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);

        return $this->render('BrasaTransporteBundle:Base/Productos:lista.html.twig', array(
                    'arProductos' => $arProductos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoProducto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arProducto = new \Brasa\TransporteBundle\Entity\TteProducto();
        if ($codigoProducto != 0)
        {
            $arProducto = $em->getRepository('BrasaTransporteBundle:TteProducto')->find($codigoProducto);
        }    
        $form = $this->createForm(new TteProductoType(), $arProducto);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arProducto = $form->getData();
            $em->persist($arProducto);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tte_base_productos_lista'));
        }
        return $this->render('BrasaTransporteBundle:Base/Productos:nuevo.html.twig', array(
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'PRODUCTO');

        $i = 2;
        $arProductos = $em->getRepository('BrasaTransporteBundle:TteProducto')->findAll();

        foreach ($arProductos as $arProducto) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProducto->getCodigoProductoPk())
                    ->setCellValue('B' . $i, $arProducto->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Productos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Productos.xlsx"');
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
=======
<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TransporteBundle\Form\Type\TteProductoType;



class BaseProductoController extends Controller
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
        $arProductos = new \Brasa\TransporteBundle\Entity\TteProducto();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoProducto) {
                    $arProducto = new \Brasa\TransporteBundle\Entity\TteProducto();
                    $arProducto = $em->getRepository('BrasaTransporteBundle:TteProducto')->find($codigoProducto);
                    $em->remove($arProducto);
                    $em->flush();
                }
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
        }
        $arProductos = new \Brasa\TransporteBundle\Entity\TteProducto();
        $query = $em->getRepository('BrasaTransporteBundle:TteProducto')->findAll();
        $arProductos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);

        return $this->render('BrasaTransporteBundle:Base/Productos:lista.html.twig', array(
                    'arProductos' => $arProductos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoProducto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arProducto = new \Brasa\TransporteBundle\Entity\TteProducto();
        if ($codigoProducto != 0)
        {
            $arProducto = $em->getRepository('BrasaTransporteBundle:TteProducto')->find($codigoProducto);
        }    
        $form = $this->createForm(new TteProductoType(), $arProducto);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arProducto = $form->getData();
            $em->persist($arProducto);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tte_base_productos_lista'));
        }
        return $this->render('BrasaTransporteBundle:Base/Productos:nuevo.html.twig', array(
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'PRODUCTO');

        $i = 2;
        $arProductos = $em->getRepository('BrasaTransporteBundle:TteProducto')->findAll();

        foreach ($arProductos as $arProducto) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProducto->getCodigoProductoPk())
                    ->setCellValue('B' . $i, $arProducto->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Productos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Productos.xlsx"');
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
>>>>>>> 30fa032eaa121a2a644bd8fdcc615fe16e877a7d
