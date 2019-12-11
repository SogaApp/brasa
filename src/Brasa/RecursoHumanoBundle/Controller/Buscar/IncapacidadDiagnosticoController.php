<?php

namespace Brasa\RecursoHumanoBundle\Controller\Buscar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class IncapacidadDiagnosticoController extends Controller
{
    var $strDqlLista = "";        
    
    /**
     * @Route("/rhu/buscar/incapacidad/diagnostico/", name="brs_rhu_buscar_incapacidad_diagnostico")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arIncapacidadesDiagnosticos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Buscar:incapacidadDiagnostico.html.twig', array(
            'arIncapacidadesDiagnosticos' => $arIncapacidadesDiagnosticos,
            'form' => $form->createView()
            ));
    }        
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadDiagnostico')->listaDQL(
                $session->get('filtroIncapacidadDiagnosticoNombre'), 
                $session->get('filtroCodigoIncapacidadDiagnostico')
                ); 
    }       
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $form = $this->createFormBuilder()                        
            ->add('TxtNombreIncapacidadDiagnostico', 'text', array('label'  => 'Nombre','data' => $session->get('filtroIncapacidadDiagnosticoNombre')))
            ->add('TxtCodigoIncapacidadDiagnostico', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroCodigoIncapacidadDiagnostico')))                            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }           

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();                        
        $session->set('filtroIncapacidadDiagnosticoNombre', $form->get('TxtNombreIncapacidadDiagnostico')->getData());
        $session->set('filtroCodigoIncapacidadDiagnostico', $form->get('TxtCodigoIncapacidadDiagnostico')->getData());
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
                    ->setCellValue('B1', 'IDENTIFICACIÓN')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'CODIGO CONTRATO')                    
                    ->setCellValue('E1', 'DESDE')
                    ->setCellValue('F1', 'HASTA')
                    ->setCellValue('G1', 'IBC')
                    ->setCellValue('H1', 'IBP');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arIngresosBase = new \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase();
        $arIngresosBase = $query->getResult();
        foreach ($arIngresosBase as $arIngresoBase) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIngresoBase->getCodigoIngresoBasePk())
                    ->setCellValue('B' . $i, $arIngresoBase->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arIngresoBase->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arIngresoBase->getCodigoContratoFk())                    
                    ->setCellValue('E' . $i, $arIngresoBase->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('F' . $i, $arIngresoBase->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('G' . $i, $arIngresoBase->getvrIngresoBaseCotizacion())
                    ->setCellValue('H' . $i, $arIngresoBase->getvrIngresoBasePrestacion());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('IngresosEmpleado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="IngresosEmpleado.xlsx"');
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
