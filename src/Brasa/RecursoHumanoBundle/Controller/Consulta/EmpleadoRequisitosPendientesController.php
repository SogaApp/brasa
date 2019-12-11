<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class EmpleadoRequisitosPendientesController extends Controller
{
    var $strDqlLista = "";        
    var $strNumeroIdentificacion = "";
    
    /**
     * @Route("/rhu/consultas/empleado/requisitos/pendientes", name="brs_rhu_consultas_empleado_requisitos_pendientes")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 18)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->filtrarLista($form);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }

        }
        $arRequisitosDetalles = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/EmpleadoRequisitosPendientes:lista.html.twig', array(
            'arRequisitosDetalles' => $arRequisitosDetalles,
            'form' => $form->createView()
            ));
    }        
    
    private function listar() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoDetalle')->listaDql($this->strNumeroIdentificacion);
    }       
    
    private function formularioLista() {        
        $form = $this->createFormBuilder()
            ->add('TxtNumeroIdentificacion', 'text', array('label'  => 'Identificacion'))            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }           
    
    private function filtrarLista($form) {                
        $this->strNumeroIdentificacion = $form->get('TxtNumeroIdentificacion')->getData();        
    }    
    
    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
                    ->setCellValue('B1', 'REQUISITO')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'CONCEPTO')
                    ->setCellValue('F1', 'TIPO');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arRequisitosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();
        $arRequisitosDetalles = $query->getResult();
        foreach ($arRequisitosDetalles as $arRequisitoDetalle) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRequisitoDetalle->getCodigoRequisitoDetallePk())
                    ->setCellValue('B' . $i, $arRequisitoDetalle->getCodigoRequisitoFk())                    
                    ->setCellValue('C' . $i, $arRequisitoDetalle->getRequisitoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arRequisitoDetalle->getRequisitoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arRequisitoDetalle->getRequisitoConceptoRel()->getNombre())
                    ->setCellValue('F' . $i, $arRequisitoDetalle->getTipo());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('RequisitosPendientes');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="RequisitosPendientes.xlsx"');
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
