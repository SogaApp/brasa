<?php

namespace Brasa\ContabilidadBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\ContabilidadBundle\Form\Type\CtbCuentaType;



class CuentaController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/ctb/base/cuentas/lista", name="brs_ctb_base_cuentas_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 92, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);        
        $this->listar($form); 
        $arCuentas = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoCuenta) {
                    $arCuenta = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuenta);
                    $em->remove($arCuenta);
                    $em->flush();
                }
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
        }
        
        $arCuentas = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 35);                                       
        return $this->render('BrasaContabilidadBundle:Base/Cuentas:lista.html.twig', array(
                    'arCuentas' => $arCuentas,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/ctb/base/cuentas/nuevo/{codigoCuenta}", name="brs_ctb_base_cuentas_nuevo")
     */
    public function nuevoAction($codigoCuenta) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCuenta = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
        if ($codigoCuenta != 0) {
            $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuenta);
        }    
        $form = $this->createForm(new CtbCuentaType(), $arCuenta);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arCuenta = $form->getData();
            $arCuentaPadre = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
            $arCuentaPadre = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arCuenta->getCodigoCuentaPadreFk());
            if($arCuentaPadre) {                
                $arCuenta->setNombreCuenta($arCuenta->getNombreCuenta());
                $em->persist($arCuenta);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_ctb_base_cuentas_lista'));                
            } else {
                $objMensaje->Mensaje('error', 'La cuenta padre no existe', $this);
            }

        }
        return $this->render('BrasaContabilidadBundle:Base/Cuentas:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function listar($form) {
        $em = $this->getDoctrine()->getManager(); 
                
        $this->strDqlLista = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->listaDql(    
                $form->get('TxtCodigoCuenta')->getData(),
                $form->get('TxtNombreCuenta')->getData()                
        );  
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $form = $this->createFormBuilder()                                    
            ->add('TxtCodigoCuenta', 'text', array('label'  => 'Código Cuenta','data' => "", 'required' => false))
            ->add('TxtNombreCuenta', 'text', array('label'  => 'Nombre Cuenta','data' => "", 'required' => false))
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NOMBRE')
                    ->setCellValue('C1', 'PERMITE MOVIMIENTOS')
                    ->setCellValue('D1', 'EXIGE NIT')
                    ->setCellValue('E1', 'EXIGE CENTRO COSTO')
                    ->setCellValue('F1', 'PORCENTAJE RETENCIÓN');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        //$arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arCuentas = $query->getResult();
        foreach ($arCuentas as $arCuenta) {
            if ($arCuenta->getPermiteMovimientos() == 1){
                $strPermiteMovimientos = "SI";
            }else {
                $strPermiteMovimientos = "NO";
            }
            if ($arCuenta->getExigeNit() == 1){
                $strExigeNit = "SI";
            }else {
                $strExigeNit = "NO";
            }
            if ($arCuenta->getExigeCentroCostos() == 1){
                $strExigeCentroCosto = "SI";
            }else {
                $strExigeCentroCosto = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCuenta->getCodigoCuentaPk())
                    ->setCellValue('B' . $i, $arCuenta->getNombreCuenta())
                    ->setCellValue('C' . $i, $strPermiteMovimientos)
                    ->setCellValue('D' . $i, $strExigeNit)
                    ->setCellValue('E' . $i, $strExigeCentroCosto)
                    ->setCellValue('F' . $i, $arCuenta->getPorcentajeRetencion());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Cuentas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Cuentas.xlsx"');
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
