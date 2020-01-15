<<<<<<< HEAD
<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TransporteBundle\Form\Type\TteClienteType;



class BaseClienteController extends Controller
{
    var $strDqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);        
        $this->listar($form); 
        $arClientes = new \Brasa\TransporteBundle\Entity\TteCliente();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoCliente) {
                    $arCliente = new \Brasa\TransporteBundle\Entity\TteCliente();
                    $arCliente = $em->getRepository('BrasaTransporteBundle:TteCliente')->find($codigoCliente);
                    $em->remove($arCliente);
                    $em->flush();
                }
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
        }
        
        $arClientes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 35);                                       
        return $this->render('BrasaTransporteBundle:Base/Clientes:lista.html.twig', array(
                    'arClientes' => $arClientes,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoCliente) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arCliente = new \Brasa\TransporteBundle\Entity\TteCliente();
        if ($codigoCliente != 0)
        {
            $arCliente = $em->getRepository('BrasaTransporteBundle:TteCliente')->find($codigoCliente);
        }    
        $form = $this->createForm(new TteClienteType(), $arCliente);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arCliente = $form->getData();
            $em->persist($arCliente);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tte_base_clientes_lista'));
        }
        return $this->render('BrasaTransporteBundle:Base/Clientes:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function listar($form) {
        $em = $this->getDoctrine()->getManager(); 
                
        $this->strDqlLista = $em->getRepository('BrasaTransporteBundle:TteCliente')->listaDql(    
                $form->get('TxtNombreCliente')->getData(),
                $form->get('TxtIdentificacionCliente')->getData()                
        );  
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $form = $this->createFormBuilder()                                    
            ->add('TxtNombreCliente', 'text', array('label'  => 'Nombre','data' => "", 'required' => false))
            ->add('TxtIdentificacionCliente', 'text', array('label'  => 'Identificacion','data' => "", 'required' => false))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                                            
            ->getForm();        
        return $form;
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'CÓDIGO LISTA PRECIO')
                    ->setCellValue('C1', 'LISTA PRECIO')
                    ->setCellValue('D1', 'NIT')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'LIQUIDAR AUTOMÁTICAMENTE FLETE')
                    ->setCellValue('G1', 'LIQUIDAR AUTOMÁTICAMENTE MANEJO')
                    ->setCellValue('H1', 'PORCENTAJE MANEJO')
                    ->setCellValue('I1', 'VR. MANEJO MÍNIMO UNIDAD')
                    ->setCellValue('J1', 'DESCUENTO KILOS')
                    ->setCellValue('K1', 'CANTIDAD MÍNIMA')
                    ->setCellValue('L1', 'PAGA MANEJO CORRIENTE');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        
        $arClientes = $query->getResult();
        foreach ($arClientes as $arCliente) {
            if ($arCliente->getLiquidarAutomaticamenteFlete() == 1){
                $strLiquidarAutomaticamenteFlete = "SI";
            }else {
                $strLiquidarAutomaticamenteFlete = "NO";
            }
            if ($arCliente->getLiquidarAutomaticamenteManejo() == 1){
                $strLiquidarAutomaticamenteManejo = "SI";
            }else {
                $strLiquidarAutomaticamenteManejo = "NO";
            }
            if ($arCliente->getPagaManejoCorriente() == 1){
                $strPagaManejoCorriente = "SI";
            }else {
                $strPagaManejoCorriente = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCliente->getCodigoClientePk())
                    ->setCellValue('B' . $i, $arCliente->getCodigoListaPrecioFk())
                    ->setCellValue('C' . $i, $arCliente->getListaPrecioRel()->getNombre())
                    ->setCellValue('D' . $i, $arCliente->getNit())
                    ->setCellValue('E' . $i, $arCliente->getNombreCorto())
                    ->setCellValue('F' . $i, $strLiquidarAutomaticamenteFlete)
                    ->setCellValue('G' . $i, $strLiquidarAutomaticamenteManejo)
                    ->setCellValue('H' . $i, $arCliente->getPorcentajeManejo())
                    ->setCellValue('I' . $i, $arCliente->getvrManejoMinimoUnidad())
                    ->setCellValue('J' . $i, $arCliente->getDescuentoKilos())
                    ->setCellValue('K' . $i, $arCliente->getctPesoMinimoUnidad())
                    ->setCellValue('L' . $i, $strPagaManejoCorriente);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Clientes');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Clientes.xlsx"');
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
use Brasa\TransporteBundle\Form\Type\TteClienteType;



class BaseClienteController extends Controller
{
    var $strDqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);        
        $this->listar($form); 
        $arClientes = new \Brasa\TransporteBundle\Entity\TteCliente();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoCliente) {
                    $arCliente = new \Brasa\TransporteBundle\Entity\TteCliente();
                    $arCliente = $em->getRepository('BrasaTransporteBundle:TteCliente')->find($codigoCliente);
                    $em->remove($arCliente);
                    $em->flush();
                }
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
        }
        
        $arClientes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 35);                                       
        return $this->render('BrasaTransporteBundle:Base/Clientes:lista.html.twig', array(
                    'arClientes' => $arClientes,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoCliente) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arCliente = new \Brasa\TransporteBundle\Entity\TteCliente();
        if ($codigoCliente != 0)
        {
            $arCliente = $em->getRepository('BrasaTransporteBundle:TteCliente')->find($codigoCliente);
        }    
        $form = $this->createForm(new TteClienteType(), $arCliente);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arCliente = $form->getData();
            $em->persist($arCliente);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tte_base_clientes_lista'));
        }
        return $this->render('BrasaTransporteBundle:Base/Clientes:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function listar($form) {
        $em = $this->getDoctrine()->getManager(); 
                
        $this->strDqlLista = $em->getRepository('BrasaTransporteBundle:TteCliente')->listaDql(    
                $form->get('TxtNombreCliente')->getData(),
                $form->get('TxtIdentificacionCliente')->getData()                
        );  
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $form = $this->createFormBuilder()                                    
            ->add('TxtNombreCliente', 'text', array('label'  => 'Nombre','data' => "", 'required' => false))
            ->add('TxtIdentificacionCliente', 'text', array('label'  => 'Identificacion','data' => "", 'required' => false))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                                            
            ->getForm();        
        return $form;
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'CÓDIGO LISTA PRECIO')
                    ->setCellValue('C1', 'LISTA PRECIO')
                    ->setCellValue('D1', 'NIT')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'LIQUIDAR AUTOMÁTICAMENTE FLETE')
                    ->setCellValue('G1', 'LIQUIDAR AUTOMÁTICAMENTE MANEJO')
                    ->setCellValue('H1', 'PORCENTAJE MANEJO')
                    ->setCellValue('I1', 'VR. MANEJO MÍNIMO UNIDAD')
                    ->setCellValue('J1', 'DESCUENTO KILOS')
                    ->setCellValue('K1', 'CANTIDAD MÍNIMA')
                    ->setCellValue('L1', 'PAGA MANEJO CORRIENTE');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        
        $arClientes = $query->getResult();
        foreach ($arClientes as $arCliente) {
            if ($arCliente->getLiquidarAutomaticamenteFlete() == 1){
                $strLiquidarAutomaticamenteFlete = "SI";
            }else {
                $strLiquidarAutomaticamenteFlete = "NO";
            }
            if ($arCliente->getLiquidarAutomaticamenteManejo() == 1){
                $strLiquidarAutomaticamenteManejo = "SI";
            }else {
                $strLiquidarAutomaticamenteManejo = "NO";
            }
            if ($arCliente->getPagaManejoCorriente() == 1){
                $strPagaManejoCorriente = "SI";
            }else {
                $strPagaManejoCorriente = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCliente->getCodigoClientePk())
                    ->setCellValue('B' . $i, $arCliente->getCodigoListaPrecioFk())
                    ->setCellValue('C' . $i, $arCliente->getListaPrecioRel()->getNombre())
                    ->setCellValue('D' . $i, $arCliente->getNit())
                    ->setCellValue('E' . $i, $arCliente->getNombreCorto())
                    ->setCellValue('F' . $i, $strLiquidarAutomaticamenteFlete)
                    ->setCellValue('G' . $i, $strLiquidarAutomaticamenteManejo)
                    ->setCellValue('H' . $i, $arCliente->getPorcentajeManejo())
                    ->setCellValue('I' . $i, $arCliente->getvrManejoMinimoUnidad())
                    ->setCellValue('J' . $i, $arCliente->getDescuentoKilos())
                    ->setCellValue('K' . $i, $arCliente->getctPesoMinimoUnidad())
                    ->setCellValue('L' . $i, $strPagaManejoCorriente);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Clientes');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Clientes.xlsx"');
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
