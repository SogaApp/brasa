<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;


class ContratoFechaVencimientoController extends Controller
{
    var $strDqlLista = "";
    var $intNumero = 0;
    /**
     * @Route("/rhu/consulta/contrato/fechavencimiento", name="brs_rhu_consulta_contrato_fechavencimiento")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 29)) {
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
        $arContratosFechaVencimiento = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Contrato:FechaVencimiento.html.twig', array(
            'arContratosFechaVencimiento' => $arContratosFechaVencimiento,
            'form' => $form->createView()
            ));
    }     
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->listaContratosFechaVencimientoDQL(
                    $session->get('filtroCodigoContratoTipo'),
                    $session->get('filtroCodigoEmpleadoTipo'),
                    $session->get('filtroCodigoZona'),
                    $session->get('filtroCodigoSubzona'),
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroVencimiento')
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
        $arrayPropiedadesTipo = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoEmpleadoTipo')) {
            $arrayPropiedadesTipo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEmpleadoTipo", $session->get('filtroCodigoEmpleadoTipo'));
        }
        $arrayPropiedadesZona = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuZona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoZona')) {
            $arrayPropiedadesZona['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuZona", $session->get('filtroCodigoZona'));
        }
        $arrayPropiedadesSubZona = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSubzona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sz')
                    ->orderBy('sz.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoSubzona')) {
            $arrayPropiedadesSubZona['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuSubzona", $session->get('filtroCodigoSubzona'));
        }
        $arrayPropiedadesTipoContrato = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuContratoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sz')
                    ->orderBy('sz.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoContratoTipo')) {
            $arrayPropiedadesTipoContrato['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuContratoTipo", $session->get('filtroCodigoContratoTipo'));
        }
        $form = $this->createFormBuilder()
            ->add('contratoTipoRel', 'entity', $arrayPropiedadesTipoContrato)
            ->add('centroCostoRel', 'entity', $arrayPropiedades)    
            ->add('empleadoTipoRel', 'entity', $arrayPropiedadesTipo)
            ->add('zonaRel', 'entity', $arrayPropiedadesZona)
            ->add('subZonaRel', 'entity', $arrayPropiedadesSubZona)    
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaVencimiento','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            //->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoContratoTipo', $controles['contratoTipoRel']);
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroCodigoEmpleadoTipo', $controles['empleadoTipoRel']);
        $session->set('filtroCodigoZona', $controles['zonaRel']);
        $session->set('filtroCodigoSubzona', $controles['subZonaRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
                
        //$dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaVencimiento')->getData();
        if ($form->get('fechaVencimiento')->getData() == null ){
            //$session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroVencimiento', $form->get('fechaVencimiento')->getData());
        } else {
            //$session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroVencimiento', $dateFechaHasta->format('Y-m-d')); 
        }
    }

    private function generarExcel() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
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
        for($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'TIPO CONTRATO')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'CENTRO COSTO')
                    ->setCellValue('F1', 'CARGO')
                    ->setCellValue('G1', 'DESDE')
                    ->setCellValue('H1', 'HASTA')
                    ->setCellValue('I1', 'MOTIVO')
                    ->setCellValue('J1', 'TIPO')
                    ->setCellValue('K1', 'ZONA')
                    ->setCellValue('L1', 'SUBZONA');
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arFechaVencimientos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arFechaVencimientos = $query->getResult();
        foreach ($arFechaVencimientos as $arFechaVencimiento) {
            $tipo = "";
            if ($arFechaVencimiento->getEmpleadoRel()->getCodigoEmpleadoTipoFk() != null){
                $tipo = $arFechaVencimiento->getEmpleadoRel()->getEmpleadoTipoRel()->getNombre();
            }
            $zona = "";
            if ($arFechaVencimiento->getEmpleadoRel()->getCodigoZonaFk() != null){
                $zona = $arFechaVencimiento->getEmpleadoRel()->getZonaRel()->getNombre();
            }
            $subzona = "";
            if ($arFechaVencimiento->getEmpleadoRel()->getCodigoSubzonaFk() != null){
                $subzona = $arFechaVencimiento->getEmpleadoRel()->getSubzonaRel()->getNombre();
            }
            $motivo = "";
            if ($arFechaVencimiento->getCodigoMotivoTerminacionContratoFk() != null){
                $motivo = $arFechaVencimiento->getTerminacionContratoRel()->getMotivo();
            }
            $cargo = "";
            if ($arFechaVencimiento->getCodigoCargoFk() != null){
                $cargo = $arFechaVencimiento->getCargoRel()->getNombre();
            }            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFechaVencimiento->getCodigoContratoPk())
                    ->setCellValue('B' . $i, $arFechaVencimiento->getContratoTipoRel()->getNombreCorto())                    
                    ->setCellValue('C' . $i, $arFechaVencimiento->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arFechaVencimiento->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arFechaVencimiento->getCentroCostoRel()->getNombre())
                    ->setCellValue('F' . $i, $cargo)
                    ->setCellValue('G' . $i, $arFechaVencimiento->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('H' . $i, $arFechaVencimiento->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $motivo)
                    ->setCellValue('J' . $i, $tipo)
                    ->setCellValue('K' . $i, $zona)
                    ->setCellValue('L' . $i, $subzona);
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('ReporteFechaVencimiento');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteFechaVencimiento.xlsx"');
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
