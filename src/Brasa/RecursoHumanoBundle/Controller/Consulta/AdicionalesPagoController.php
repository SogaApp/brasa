<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class AdicionalesPagoController extends Controller
{
    var $strDqlLista = "";   
    var $nombre = "";
    var $identificacion = "";
    var $centroCosto = "";
    var $aplicaDiaLaborado = 2;
    
    /**
     * @Route("/rhu/consultas/adicionales/pago", name="brs_rhu_consultas_adicionales_pago")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 12)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar($form);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar($form);
                $this->generarExcel();
            }            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar($form);
            }

        }
        $arAdicionalesPago = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/AdicionalesPagos:lista.html.twig', array(
            'arAdicionalesPago' => $arAdicionalesPago,
            'form' => $form->createView()
            ));
    }        
    
    private function listar($form) {
        $session = $this->getRequest()->getSession();         
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->listaConsultaDql(                    
            $this->nombre,
            $this->identificacion,
            $this->centroCosto,
            $this->aplicaDiaLaborado);
    }       
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();            
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->nombre))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificaciín','data' => $this->identificacion))
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""))
            ->add('aplicaDiaLaborado', 'choice', array('choices' => array('2' => 'TODOS', '0' => 'NO', '1' => 'SI')))                
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }           
    
    private function filtrarLista($form) {
        
        $arCentroCosto = $form->get('centroCostoRel')->getData();
        if ($arCentroCosto == null){
            $intCentroCosto = "";
        }else {
            $intCentroCosto = $arCentroCosto->getCodigoCentroCostoPk();
        }
        
        $this->nombre = $form->get('TxtNombre')->getData();
        $this->identificacion = $form->get('TxtIdentificacion')->getData();
        $this->centroCosto = $intCentroCosto;
        $this->aplicaDiaLaborado = $form->get('aplicaDiaLaborado')->getData();
    }    
    
    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'CONCEPTO')
                    ->setCellValue('C1', 'DETALLE')
                    ->setCellValue('D1', 'IDENTIFICACIÓN')
                    ->setCellValue('E1', 'EMPLEADO')
                    ->setCellValue('F1', 'CENTRO COSTO')
                    ->setCellValue('G1', 'CANTIDAD')
                    ->setCellValue('H1', 'VALOR')                    
                    ->setCellValue('I1', 'PERMANENTE')
                    ->setCellValue('J1', 'APLICA DIA LABORADO');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagoAdicional = $query->getResult();
        foreach ($arPagoAdicional as $arPagoAdicional) {
            $centroCosto = "";
            if ($arPagoAdicional->getEmpleadoRel()->getCodigoCentroCostoFk() != null){
                $centroCosto = $arPagoAdicional->getEmpleadoRel()->getCentroCostoRel()->getNombre();
            }    
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $arPagoAdicional->getCodigoPagoAdicionalPk())    
                ->setCellValue('B' . $i, $arPagoAdicional->getPagoConceptoRel()->getNombre())
                ->setCellValue('C' . $i, $arPagoAdicional->getDetalle())
                ->setCellValue('D' . $i, $arPagoAdicional->getEmpleadoRel()->getNumeroIdentificacion())                        
                ->setCellValue('E' . $i, $arPagoAdicional->getEmpleadoRel()->getNombreCorto()) 
                ->setCellValue('F' . $i, $centroCosto) 
                ->setCellValue('G' . $i, $arPagoAdicional->getCantidad())                    
                ->setCellValue('H' . $i, $arPagoAdicional->getValor())
                ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arPagoAdicional->getPermanente()))
                ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arPagoAdicional->getAplicaDiaLaborado()));
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PagosAdicionales');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PagosAdicionales.xlsx"');
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
