<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class ControlAccesosEmpleadosController extends Controller
{
    var $strDqlLista = "";   
    var $nombre = "";
    var $identificacion = "";
    var $centroCosto = "";
    var $cargo = "";
    var $departamentoEmpresa = "";
    var $registrado = 2;
    var $salida = 2;
    var $fechaDesde = "";
    var $fechaHasta = "";
    var $entradaTarde = 2;
    var $salidaAntes = 2;
    
    /**
     * @Route("/rhu/consultas/control/acceso/empleado", name="brs_rhu_consultas_control_acceso_empleados")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 40)) {
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
        $arControlAccesosEmpleados = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/ControlAcceso:empleados.html.twig', array(
            'arControlAccesosEmpleados' => $arControlAccesosEmpleados,
            'form' => $form->createView()
            ));
    }        
    
    private function listar($form) {
        $session = $this->getRequest()->getSession();         
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->listaConsultaDql(                    
            $this->nombre,
            $this->identificacion,
            $this->centroCosto,
            $this->cargo,
            $this->departamentoEmpresa,
            $this->registrado,
            $this->salida,
            $this->fechaDesde = $form->get('fechaDesde')->getData(),
            $this->fechaHasta = $form->get('fechaHasta')->getData(),
            $this->entradaTarde,
            $this->salidaAntes);
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
            ->add('cargoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""))
            ->add('departamentoEmpresaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('de')
                    ->orderBy('de.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => "")) 
            ->add('estadoEntrada', 'choice', array('choices' => array('2' => 'TODOS', '0' => 'NO', '1' => 'SI')))
            ->add('estadoSalida', 'choice', array('choices' => array('2' => 'TODOS', '0' => 'NO', '1' => 'SI')))
            ->add('entradaTarde', 'choice', array('choices' => array('2' => 'TODOS', '0' => 'NO', '1' => 'SI')))
            ->add('salidaAntes', 'choice', array('choices' => array('2' => 'TODOS', '0' => 'NO', '1' => 'SI')))                
            ->add('fechaDesde','date', array('data' => new \DateTime('now'), 'format' => 'yyyyMMdd'))
            ->add('fechaHasta','date', array('data' => new \DateTime('now'), 'format' => 'yyyyMMdd'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }           
    
    private function filtrarLista($form) {
        $arDepartamentoEmpresa = $form->get('departamentoEmpresaRel')->getData();
        if ($arDepartamentoEmpresa == null){
            $intDepartamentoEmpresa = "";
        }else {
            $intDepartamentoEmpresa = $arDepartamentoEmpresa->getCodigoDepartamentoEmpresaPk();
        }
        $arCentroCosto = $form->get('centroCostoRel')->getData();
        if ($arCentroCosto == null){
            $intCentroCosto = "";
        }else {
            $intCentroCosto = $arCentroCosto->getCodigoCentroCostoPk();
        }
        $arCargo = $form->get('cargoRel')->getData();
        if ($arCargo == null){
            $intCargo = "";
        }else {
            $intCargo = $arCargo->getCodigoCargoPk();
        }
        $this->nombre = $form->get('TxtNombre')->getData();
        $this->identificacion = $form->get('TxtIdentificacion')->getData();
        $this->centroCosto = $intCentroCosto;
        $this->cargo = $intCargo;
        $this->departamentoEmpresa = $intDepartamentoEmpresa;
        $this->registrado = $form->get('estadoEntrada')->getData();
        $this->salida = $form->get('estadoSalida')->getData();
        $this->entradaTarde = $form->get('entradaTarde')->getData();
        $this->salidaAntes = $form->get('salidaAntes')->getData();
        $this->fechaDesde = $form->get('fechaDesde')->getData();
        $this->fechaHasta = $form->get('fechaHasta')->getData();
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'IDENTIFICACIÓN')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'CENTRO COSTO')
                    ->setCellValue('E1', 'DEPARTAMENTO EMPRESA')                    
                    ->setCellValue('F1', 'CARGO')
                    ->setCellValue('G1', 'FECHA')
                    ->setCellValue('H1', 'TURNO')
                    ->setCellValue('I1', 'HORA ENTRADA TURNO')
                    ->setCellValue('J1', 'HORA ENTRADA')
                    ->setCellValue('K1', 'LLEGADA TARDE')
                    ->setCellValue('L1', 'DURACIÓN LLEGADA TARDE')
                    ->setCellValue('M1', 'HORA SALIDA TURNO')
                    ->setCellValue('N1', 'HORA SALIDA')
                    ->setCellValue('O1', 'SALIDA ANTES')
                    ->setCellValue('P1', 'DURACIÓN SALIDA ANTES')
                    ->setCellValue('Q1', 'DURACIÓN TOTAL REGISTRO')
                    ->setCellValue('R1', 'ANULADO')
                    ->setCellValue('S1', 'COMENTARIOS');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arHorarioAcceso = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
        $arHorarioAcceso = $query->getResult();
        $j = 1;
        foreach ($arHorarioAcceso as $arHorarioAcceso) {
            
            if ($arHorarioAcceso->getFechaEntrada()->format('H:i:s') == "00:00:00"){
                $timeHoraEntrada = "SIN ENTRADA";
            } else {
                $timeHoraEntrada = $arHorarioAcceso->getFechaEntrada()->format('H:i:s');
            }
            if ($arHorarioAcceso->getFechaSalida() == null){
                $timeHoraSalida = "SIN SALIDA";
            } else {
                if ($arHorarioAcceso->getFechaSalida()->format('H:i:s') == "00:00:00") {
                    $timeHoraSalida = "SIN SALIDA";
                }
                    $timeHoraSalida = $arHorarioAcceso->getFechaSalida()->format('H:i:s');
                
            }
            if ($arHorarioAcceso->getDuracionEntradaTarde() == null){
                $duracionEntradaTarde = "";
            } else {
                $duracionEntradaTarde = $arHorarioAcceso->getDuracionEntradaTarde();
            }
            if ($arHorarioAcceso->getDuracionSalidaAntes() == null){
                $duracionSalidaAntes = "";
            } else {
                $duracionSalidaAntes = $arHorarioAcceso->getDuracionSalidaAntes();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $j)    
                ->setCellValue('B' . $i, $arHorarioAcceso->getEmpleadoRel()->getNumeroIdentificacion())
                ->setCellValue('C' . $i, $arHorarioAcceso->getEmpleadoRel()->getNombreCorto())
                ->setCellValue('D' . $i, $arHorarioAcceso->getEmpleadoRel()->getCentroCostoRel()->getNombre())                        
                ->setCellValue('E' . $i, $arHorarioAcceso->getEmpleadoRel()->getDepartamentoEmpresaRel()->getNombre())                    
                ->setCellValue('F' . $i, $arHorarioAcceso->getEmpleadoRel()->getCargoRel()->getNombre())                    
                ->setCellValue('G' . $i, $arHorarioAcceso->getFechaEntrada()->format('Y-m-d'))
                ->setCellValue('H' . $i, $arHorarioAcceso->getCodigoTurnoFk())
                ->setCellValue('I' . $i, $arHorarioAcceso->getHoraEntradaTurno()->format('H:i:s'))
                ->setCellValue('J' . $i, $timeHoraEntrada)
                ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arHorarioAcceso->getEntradaTarde()))
                ->setCellValue('L' . $i, $duracionEntradaTarde)    
                ->setCellValue('M' . $i, $arHorarioAcceso->getHoraSalidaTurno()->format('H:i:s'))        
                ->setCellValue('N' . $i, $timeHoraSalida)
                ->setCellValue('O' . $i, $objFunciones->devuelveBoolean($arHorarioAcceso->getSalidaAntes()))    
                ->setCellValue('P' . $i, $duracionSalidaAntes)
                ->setCellValue('Q' . $i, $arHorarioAcceso->getDuracionRegistro())        
                ->setCellValue('R' . $i, $objFunciones->devuelveBoolean($arHorarioAcceso->getAnulado()))    
                ->setCellValue('S' . $i, $arHorarioAcceso->getComentarios());
            $i++;
            $j++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ControlAccesoEmpleado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ControlAccesoEmpleado.xlsx"');
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
