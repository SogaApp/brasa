<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDotacionElementoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuDotacionElemento controller.
 *
 */
class DotacionElementoController extends Controller
{

    /**
     * @Route("/rhu/base/dotacion/elemento/lista", name="brs_rhu_base_dotacion_elemento_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 70, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arDotacionElementos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoDotacionElementoPk) {
                        $arDotacionElementos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
                        $arDotacionElementos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->find($codigoDotacionElementoPk);
                        $em->remove($arDotacionElementos);
                        

                    }
                $em->flush();    
                return $this->redirect($this->generateUrl('brs_rhu_base_dotacion_elemento_lista'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar la dotacion elemento porque esta siendo utilizado', $this);
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
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'NOMBRE')
                            ->setCellValue('C1', 'TIPO');

                $i = 2;
                $arDotacionElementos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->findAll();
                
                foreach ($arDotacionElementos as $arDotacionElementos) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arDotacionElementos->getCodigoDotacionElementoPk())
                            ->setCellValue('B' . $i, $arDotacionElementos->getDotacion())
                            ->setCellValue('C' . $i, $arDotacionElementos->getDotacionElementoTipoRel()->getNombre());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Dotacion Elementos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="DotacionElementos.xlsx"');
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
        $arDotacionElementos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->findAll();
        $arDotacionElementos = $paginator->paginate($query, $this->get('Request')->query->get('page', 1),50);

        return $this->render('BrasaRecursoHumanoBundle:Base/DotacionElementos:listar.html.twig', array(
                    'arDotacionElementos' => $arDotacionElementos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/dotacion/elemento/nuevo/{codigoDotacionElemento}", name="brs_rhu_base_dotacion_elemento_nuevo")
     */
    public function nuevoAction(Request $request, $codigoDotacionElemento) {
        $em = $this->getDoctrine()->getManager();
        $arDotacionElemento = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
        if ($codigoDotacionElemento != 0)
        {
            $arDotacionElemento = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->find($codigoDotacionElemento);
        }    
        $formDotacionElemento = $this->createForm(new RhuDotacionElementoType(), $arDotacionElemento);
        $formDotacionElemento->handleRequest($request);
        if ($formDotacionElemento->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arDotacionElemento);
            $arDotacionElemento = $formDotacionElemento->getData();
            $em->flush();
            if($formDotacionElemento->get('guardarynuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_base_dotacion_elemento_nuevo', array('codigoDotacionElemento' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_base_dotacion_elemento_lista'));
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/DotacionElementos:nuevo.html.twig', array(
            'formDotacionElemento' => $formDotacionElemento->createView(),
        ));
    }
    
}
