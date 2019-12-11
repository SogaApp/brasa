<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCajaType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuCaja controller.
 *
 */
class CajaController extends Controller
{
    /**
     * @Route("/rhu/base/caja/listar", name="brs_rhu_base_caja_listar")
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 64, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnPdf', SubmitType::class, array('label'  => 'PDF'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arCajas = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoEntidadCajaPk) {
                        $arCaja = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja();
                        $arCaja = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadCaja')->find($codigoEntidadCajaPk);
                        $em->remove($arCaja);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_caja_listar'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar la caja de compensacion porque esta siendo utilizado', $this);
                  }    
            }
            
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoCaja = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCaja();
                $objFormatoCaja->Generar($this);
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
                            ->setCellValue('C1', 'NIT')
                            ->setCellValue('D1', 'DIRECCIÓN')
                            ->setCellValue('E1', 'TELEFONO')
                            ->setCellValue('F1', 'CÓDIGO INTERFACE');
                $i = 2;
                $arCajas = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadCaja')->findAll();
                
                foreach ($arCajas as $arCaja) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCaja->getcodigoEntidadCajaPk())
                            ->setCellValue('B' . $i, $arCaja->getnombre())
                            ->setCellValue('C' . $i, $arCaja->getnit())
                            ->setCellValue('D' . $i, $arCaja->getdireccion())
                            ->setCellValue('E' . $i, $arCaja->gettelefono())
                            ->setCellValue('F' . $i, $arCaja->getCodigoInterface());
                    $i++;
                }
                $objPHPExcel->getActiveSheet()->setTitle('Cajas_compensacion');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="CajaCompensacion.xlsx"');
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
        $arEntidadesCaja = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadCaja')->findAll();
        $arEntidadesCaja = $paginator->paginate($query, $this->get('Request')->query->get('page', 1),30);

        return $this->render('BrasaRecursoHumanoBundle:Base/CajaCompensacion:listar.html.twig', array(
                    'arEntidadesCaja' => $arEntidadesCaja,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/caja/nuevo/{codigoEntidadCajaPk}", name="brs_rhu_base_caja_nuevo")
     */
    public function nuevoAction(Request $request, $codigoEntidadCajaPk) {
        $em = $this->getDoctrine()->getManager();
        $arCaja = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja();
        if ($codigoEntidadCajaPk != 0)
        {
            $arCaja = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadCaja')->find($codigoEntidadCajaPk);
        }    
        $form = $this->createForm(RhuCajaType::class, $arCaja);   
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arCaja);
            $arCaja = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_caja_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/CajaCompensacion:nuevo.html.twig', array(
            'formCaja' => $form->createView(),
        ));
    }
}
