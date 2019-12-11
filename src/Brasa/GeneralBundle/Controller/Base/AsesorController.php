<?php

namespace Brasa\GeneralBundle\Controller\Base;

use Brasa\GeneralBundle\MisClases\Mensajes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\GeneralBundle\Form\Type\GenAsesorType;



class AsesorController extends Controller
{
    /**
     * @Route("/general/base/asesor", name="brs_gen_base_asesor")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $objMensajes = new Mensajes();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 100, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        //$arTerceros = new \Brasa\GeneralBundle\Entity\GenTercero();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoAsesor) {
                        $arAsesor = new \Brasa\GeneralBundle\Entity\GenAsesor();
                        $arAsesor = $em->getRepository('BrasaGeneralBundle:GenAsesor')->find($codigoAsesor);
                        $em->remove($arAsesor);
                    }
                    $em->flush();
                }catch (\Exception $exception){
                    $objMensajes->Mensaje("error","No se puede eliminar, el dato se encuentra en uso en el sistema.",$this);
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
                            ->setCellValue('B1', 'NOMBRE')
                            ->setCellValue('C1', 'DIRECCIÓN')
                            ->setCellValue('D1', 'TELÉFONO')
                            ->setCellValue('E1', 'CELULAR')
                            ->setCellValue('F1', 'EMAIL');

                $i = 2;
                $arAsesor = $em->getRepository('BrasaGeneralBundle:GenAsesor')->findAll();
                
                foreach ($arAsesor as $arAsesor) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arAsesor->getCodigoAsesorPk())
                            ->setCellValue('B' . $i, $arAsesor->getNombre())
                            ->setCellValue('C' . $i, $arAsesor->getDireccion())
                            ->setCellValue('D' . $i, $arAsesor->getTelefono())
                            ->setCellValue('E' . $i, $arAsesor->getCelular())
                            ->setCellValue('F' . $i, $arAsesor->getEmail());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Asesor');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Asesor.xlsx"');
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
        $dql = $em->getRepository('BrasaGeneralBundle:GenAsesor')->listaDql();
        $arAsesores = new \Brasa\GeneralBundle\Entity\GenAsesor();        
        //$arAsesores = $em->getRepository('BrasaGeneralBundle:GenAsesor')->findAll();
        $arAsesores = $paginator->paginate($em->createQuery($dql), $this->get('request')->query->get('page', 1),50);
        return $this->render('BrasaGeneralBundle:Base/Asesor:lista.html.twig', array(
                    'arAsesores' => $arAsesores,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/general/base/asesor/nuevo/{codigoAsesor}", name="brs_gen_base_asesor_nuevo")
     */
    public function nuevoAction($codigoAsesor) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arAsesor = new \Brasa\GeneralBundle\Entity\GenAsesor();
        if ($codigoAsesor != 0)
        {
            $arAsesor = $em->getRepository('BrasaGeneralBundle:GenAsesor')->find($codigoAsesor);
        }    
        $form = $this->createForm(new GenAsesorType(), $arAsesor);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arAsesor = $form->getData();
            $em->persist($arAsesor);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_gen_base_asesor'));
        }
        return $this->render('BrasaGeneralBundle:Base/Asesor:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
        
}
