<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class PagosDeduccionesEmpleadoController extends Controller
{
    var $strDqlLista = "";        
    
    /**
     * @Route("/rhu/consultas/pagos/deducciones/empleado", name="brs_rhu_consultas_pagos_deducciones_empleado")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 16)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid())
        {
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
        $arPagosDeduccionesEmpleados = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/PagosyDeduccionesEmpleados:lista.html.twig', array(
            'arPagosDeduccionesEmpleados' => $arPagosDeduccionesEmpleados,
            'form' => $form->createView()
            ));
    }
           
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaDqlPagosDeducciones(

                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }          
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        //$session->set('filtroDesde', $form->get('fechaDesde')->getData());
        //$session->set('filtroHasta', $form->get('fechaHasta')->getData());
        
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null){
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d')); 
        }
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'DESDE')
                    ->setCellValue('C1', 'HASTA')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'CENTRO COSTOS')
                    ->setCellValue('G1', 'BASICO')
                    ->setCellValue('H1', 'TIEMPO EXTRA')
                    ->setCellValue('I1', 'VALORES ADICIONALES')
                    ->setCellValue('J1', 'AUX. TRANSPORTE')
                    ->setCellValue('K1', 'COSTO')
                    ->setCellValue('L1', 'NETO')
                    ->setCellValue('M1', 'IBC')
                    ->setCellValue('N1', 'AUX. TRANSPORTE COTIZACION')
                    ->setCellValue('O1', 'DIAS PERIODO')
                    ->setCellValue('P1', 'SALARIO PERIODO')
                    ->setCellValue('Q1', 'SALARIO EMPLEADO');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arPagosDeduccionesEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagosDeduccionesEmpleados = $query->getResult();
        foreach ($arPagosDeduccionesEmpleados as $arPagosDeduccionesEmpleado) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagosDeduccionesEmpleado->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPagosDeduccionesEmpleado->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('C' . $i, $arPagosDeduccionesEmpleado->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arPagosDeduccionesEmpleado->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPagosDeduccionesEmpleado->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPagosDeduccionesEmpleado->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPagosDeduccionesEmpleado->getVrSalario())
                    ->setCellValue('H' . $i, $arPagosDeduccionesEmpleado->getVrAdicionalTiempo())
                    ->setCellValue('I' . $i, $arPagosDeduccionesEmpleado->getVrAdicionalValor())
                    ->setCellValue('J' . $i, $arPagosDeduccionesEmpleado->getVrAuxilioTransporte())
                    ->setCellValue('K' . $i, $arPagosDeduccionesEmpleado->getVrCosto())
                    ->setCellValue('L' . $i, $arPagosDeduccionesEmpleado->getVrNeto())
                    ->setCellValue('M' . $i, $arPagosDeduccionesEmpleado->getVrIngresoBaseCotizacion())
                    ->setCellValue('N' . $i, $arPagosDeduccionesEmpleado->getVrAuxilioTransporteCotizacion())
                    ->setCellValue('O' . $i, $arPagosDeduccionesEmpleado->getDiasPeriodo())
                    ->setCellValue('P' . $i, $arPagosDeduccionesEmpleado->getVrSalarioPeriodo())
                    ->setCellValue('Q' . $i, $arPagosDeduccionesEmpleado->getVrSalarioEmpleado());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PagosDeduccionesEmpleados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PagosDeduccionesEmpleados.xlsx"');
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
