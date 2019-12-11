<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuRequisitoConceptoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuRequisitoConcepto controller.
 *
 */
class RequisitoConceptoController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/base/requisito/concepto/lista", name="brs_rhu_base_requisito_concepto_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 55, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista(); 
        $form->handleRequest($request);     
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnEliminar')->isClicked()) {                           
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    try{
                        foreach ($arrSeleccionados AS $codigoRequisitoConcepto) {
                            $arRequisitoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoConcepto')->find($codigoRequisitoConcepto);
                            $em->remove($arRequisitoConcepto);
                        }
                        $em->flush();
                    } catch (ForeignKeyConstraintViolationException $e) { 
                        $objMensaje->Mensaje('error', 'No se puede eliminar el concepto porque esta siendo utilizado', $this);
                      }    
                }                
            }
            
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }            
        }                
        $arRequisitosConceptos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/RequisitoConcepto:lista.html.twig', array(
                    'arRequisitosConceptos' => $arRequisitosConceptos,
                    'form'=> $form->createView()
        ));
    }
    
    /**
     * @Route("/rhu/base/requisito/concepto/nuevo/{codigoRequisitoConcepto}", name="brs_rhu_base_requisito_concepto_nuevo")
     */
    public function nuevoAction($codigoRequisitoConcepto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arRequisitoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoConcepto();
        if ($codigoRequisitoConcepto != 0) {
            $arRequisitoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoConcepto')->find($codigoRequisitoConcepto);
        }    
        $form = $this->createForm(new RhuRequisitoConceptoType(), $arRequisitoConcepto);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arRequisitoConcepto = $form->getData();
            $em->persist($arRequisitoConcepto);            
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_requisito_concepto_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/RequisitoConcepto:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function formularioLista() {
        $form = $this->createFormBuilder()                        
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))                
            ->getForm();        
        return $form;
    }     
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoConcepto')->listaDql();         
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
                    ->setCellValue('B1', 'NOMBRE');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arRequisitosConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arRequisitosConceptos = $query->getResult();
        foreach ($arRequisitosConceptos as $arRequisitosConcepto) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRequisitosConcepto->getCodigoRequisitoConceptoPk())
                    ->setCellValue('B' . $i, $arRequisitosConcepto->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('RequistosConceptos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="RequistosConceptos.xlsx"');
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
