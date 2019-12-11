<?php

namespace Brasa\ContabilidadBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\ContabilidadBundle\Form\Type\CtbTerceroType;



class TerceroController extends Controller
{
    /**
     * @Route("/ctb/base/terceros/lista", name="brs_ctb_base_terceros_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 90, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnCorregirDigitosVerificacion', 'submit', array('label'  => 'Corregir digitos verificacion'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arTerceros = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoTercero) {
                    $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                    $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->find($codigoTercero);
                    $em->remove($arTercero);
                    $em->flush();
                }
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
            if($form->get('BtnCorregirDigitosVerificacion')->isClicked()) {            
                $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
                $arTercerosVerificar = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                $arTercerosVerificar = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findAll();
                foreach ($arTercerosVerificar as $arTercero) {
                    $digito = $objFunciones->devuelveDigitoVerificacion($arTercero->getNumeroIdentificacion());
                    if($digito != $arTercero->getDigitoVerificacion() || $arTercero->getDigitoVerificacion() == null) {
                        $arTerceroActualizar = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                        $arTerceroActualizar = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->find($arTercero->getCodigoTerceroPk());
                        $arTerceroActualizar->setDigitoVerificacion($digito);
                        $em->persist($arTerceroActualizar);                        
                    }
                }
                $em->flush();                                
            }
        }
        $arTerceros = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
        $query = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findAll();
        $arTerceros = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);

        return $this->render('BrasaContabilidadBundle:Base/Terceros:lista.html.twig', array(
                    'arTerceros' => $arTerceros,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/ctb/base/terceros/nuevo/{codigoTercero}", name="brs_ctb_base_terceros_nuevo")
     */
    public function nuevoAction($codigoTercero) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
        if ($codigoTercero != 0)
        {
            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->find($codigoTercero);
        }    
        $form = $this->createForm(new CtbTerceroType(), $arTercero);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arTercero = $form->getData();
            $arTercero->setNombreCorto($arTercero->getRazonSocial());
            $em->persist($arTercero);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_ctb_base_terceros_lista'));
        }
        return $this->render('BrasaContabilidadBundle:Base/Terceros:nuevo.html.twig', array(
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
                    ->setCellValue('B1', 'TIPO IDENTIFICACIÓN')
                    ->setCellValue('C1', 'NÚMERO IDENTIFICACIÓN')
                    ->setCellValue('D1', 'DIGITO VERIFICACIÓN')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'RAZÓN SOCIAL')
                    ->setCellValue('G1', 'CIUDAD')
                    ->setCellValue('H1', 'DIRECCIÓN')
                    ->setCellValue('I1', 'TELÉFONO')
                    ->setCellValue('J1', 'CELULAR')
                    ->setCellValue('K1', 'FAX')
                    ->setCellValue('L1', 'EMAIL');

        $i = 2;
        $arTerceros = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findAll();

        foreach ($arTerceros as $arTerceros) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arTerceros->getCodigoTerceroPk())
                    ->setCellValue('B' . $i, $arTerceros->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('C' . $i, $arTerceros->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arTerceros->getDigitoVerificacion())
                    ->setCellValue('E' . $i, $arTerceros->getNombreCorto())
                    ->setCellValue('F' . $i, $arTerceros->getRazonSocial())
                    ->setCellValue('G' . $i, $arTerceros->getCiudadRel()->getNombre())
                    ->setCellValue('H' . $i, $arTerceros->getDireccion())
                    ->setCellValue('I' . $i, $arTerceros->getTelefono())
                    ->setCellValue('J' . $i, $arTerceros->getCelular())
                    ->setCellValue('K' . $i, $arTerceros->getFax())
                    ->setCellValue('L' . $i, $arTerceros->getEmail());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Terceros');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Terceros.xlsx"');
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
