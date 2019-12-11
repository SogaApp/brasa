<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSsoSucursalType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;


class SsoSucursalController extends Controller
{
    
    /**
     * @Route("/rhu/base/ssosucursal/listar", name="brs_rhu_base_ssosucursal_listar")
     */
    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 61, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnPdf', 'submit', array('label'  => 'PDF'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arSsoSucursal = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoSucursalPk) {
                        $arSsoSucursal = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal();
                        $arSsoSucursal = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoSucursal')->find($codigoSucursalPk);
                        $em->remove($arSsoSucursal);
                    }
                    $em->flush();
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar la sucursal porque esta siendo utilizado', $this);
                  }                   
            }
            
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoSsoSucursal = new \Brasa\RecursoHumanoBundle\Formatos\FormatoSsoSucursal();
                $objFormatoSsoSucursal->Generar($this);
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
                            ->setCellValue('C1', 'Interface');

                $i = 2;
                $arSsoSucursales = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoSucursal')->findAll();
                
                foreach ($arSsoSucursales as $arSsoSucursales) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arSsoSucursales->getCodigoSucursalPk())
                            ->setCellValue('B' . $i, $arSsoSucursales->getNombre())
                            ->setCellValue('C' . $i, $arSsoSucursales->getCodigoInterface());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Sucursales_seguridad_social');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="SsoSucursales.xlsx"');
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
        $arSsoSucursales = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoSucursal')->findAll();
        $arSsoSucursales = $paginator->paginate($query, $this->get('request')->query->get('page', 1),30);

        return $this->render('BrasaRecursoHumanoBundle:Base/SsoSucursal:listar.html.twig', array(
                    'arSsoSucursales' => $arSsoSucursales,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/ssosucursal/nuevo/{codigoSucursalPk}", name="brs_rhu_base_ssosucursal_nuevo")
     */
    public function nuevoAction($codigoSucursalPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arSsoSucursal = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal();
        if ($codigoSucursalPk != 0)
        {
            $arSsoSucursal = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoSucursal')->find($codigoSucursalPk);
        }    
        $formSsoSucursal = $this->createForm(new RhuSsoSucursalType(), $arSsoSucursal);
        $formSsoSucursal->handleRequest($request);
        if ($formSsoSucursal->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arSsoSucursal);
            $arSsoSucursal = $formSsoSucursal->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_ssosucursal_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/SsoSucursal:nuevo.html.twig', array(
            'formSsoSucursal' => $formSsoSucursal->createView(),
        ));
    }
    
}
