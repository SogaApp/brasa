<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDesempenoConceptoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuDesempenoConcepto controller.
 *
 */
class DesempenoConceptoController extends Controller
{
    
    /**
     * @Route("/rhu/base/desempeno/concepto/listar", name="brs_rhu_base_desempeno_concepto_listar")
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arDesempenoConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoDesempenoConcepto) {
                        $arDesempenoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto();
                        $arDesempenoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConcepto')->find($codigoDesempenoConcepto);
                        $em->remove($arDesempenoConcepto);
                    }
                    $em->flush();
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el concepto porque esta siendo utilizado', $this);
                  }    
            }
              
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }
        }
        $arDesempenoConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConcepto')->findAll();
        $arDesempenoConceptos = $paginator->paginate($query, $this->get('Request')->query->get('page', 1),100);

        return $this->render('BrasaRecursoHumanoBundle:Base/DesempenoConcepto:listar.html.twig', array(
                    'arDesempenoConceptos' => $arDesempenoConceptos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/desempeno/concepto/nuevo/{codigoDesempenoConcepto}", name="brs_rhu_base_desempeno_concepto_nuevo")
     */
    public function nuevoAction(Request $request, $codigoDesempenoConcepto) {
        $em = $this->getDoctrine()->getManager();
        $arDesempenoConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto();
        if ($codigoDesempenoConcepto != 0)
        {
            $arDesempenoConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConcepto')->find($codigoDesempenoConcepto);
        }    
        $form = $this->createForm(new RhuDesempenoConceptoType(), $arDesempenoConceptos);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arDesempenoConceptos = $form->getData();
            $em->persist($arDesempenoConceptos);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_desempeno_concepto_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/DesempenoConcepto:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function generarExcel() {
        ob_clean();
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
                    ->setCellValue('B1', 'TIPO CONCEPTO')
                    ->setCellValue('C1', 'NOMBRE');
        $i = 2;
        $arDesempenoConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConcepto')->findAll();

        foreach ($arDesempenoConceptos as $arDesempenoConcepto) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arDesempenoConcepto->getcodigoDesempenoConceptoPk())
                    ->setCellValue('B' . $i, $arDesempenoConcepto->getDesempenoConceptoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arDesempenoConcepto->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('DesempenosConceptos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="DesempenosConceptos.xlsx"');
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
