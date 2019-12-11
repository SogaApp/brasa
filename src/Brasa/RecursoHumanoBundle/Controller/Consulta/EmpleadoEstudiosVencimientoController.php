<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class EmpleadoEstudiosVencimientoController extends Controller
{
    var $strDqlLista = "";        
    var $strFecha = "";
    var $strNumeroIdentificacion = "";
    
    /**
     * @Route("/rhu/consultas/empleado/estudios/vencimiento", name="brs_rhu_consultas_empleado_estudios_vencimiento")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 17)) {
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
            if($form->get('BtnExcelInforme')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarInformeExcel();
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }

        }
        $arEmpleadosEstudios = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/EmpleadoEstudiosVencimiento:lista.html.twig', array(
            'arEmpleadoEstudios' => $arEmpleadosEstudios,
            'form' => $form->createView()
            ));
    }        
    
    private function listar() {        
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->listaDql(
                $session->get('filtroIdentificacion'),
                $session->get('filtroHasta'),
                $session->get('filtroHastaAcreditacion')
                    );
    }       
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        
        $form = $this->createFormBuilder()
            ->add('TxtNumeroIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fecha','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))    
            ->add('fechaAcreditacion','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))        
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnExcelInforme', 'submit', array('label'  => 'Informe'))    
            ->getForm();
        return $form;
    }           
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');        
        $session->set('filtroIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());        
        $dateFechaHasta = $form->get('fecha')->getData();
        $dateFechaHastaAcreditacion = $form->get('fechaAcreditacion')->getData();
        if ($form->get('fecha')->getData() == null){
            $session->set('filtroHasta', $form->get('fecha')->getData());
        } else {
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
        }
        if ($form->get('fechaAcreditacion')->getData() == null){
            $session->set('filtroHastaAcreditacion', $form->get('fechaAcreditacion')->getData());
        } else {
            $session->set('filtroHastaAcreditacion', $dateFechaHastaAcreditacion->format('Y-m-d')); 
        }
        
    }    
    
    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
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
                for($col = 'A'; $col !== 'Z'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
                }
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'FECHA')
                            ->setCellValue('C1', 'IDENTIFICACIÓN')
                            ->setCellValue('D1', 'EMPLEADO')
                            ->setCellValue('E1', 'CARGO')
                            ->setCellValue('F1', 'TIPO ESTUDIO')
                            ->setCellValue('G1', 'INSTITUCION')
                            ->setCellValue('H1', 'TITULO')
                            ->setCellValue('I1', 'CIUDAD')
                            ->setCellValue('J1', 'FECHA INICIO')
                            ->setCellValue('K1', 'FECHA TERMINACIÓN')
                            ->setCellValue('L1', 'FECHA VENCIMIENTO ESTUDIO/CURSO')
                            ->setCellValue('M1', 'GRADO BACHILLER')
                            ->setCellValue('N1', 'GRADUADO')
                            ->setCellValue('O1', 'CURSO ACREDITACIÓN')
                            ->setCellValue('P1', 'ACADEMIA')
                            ->setCellValue('Q1', 'FECHA INICIO ACREDITACIÓN')
                            ->setCellValue('R1', 'FECHA VENCIMIENTO ACREDITACIÓN')
                            ->setCellValue('S1', 'NUMERO REGISTRO')
                            ->setCellValue('T1', 'NUMERO APROBACIÓN')
                            ->setCellValue('U1', 'VALIDAR')
                            ->setCellValue('V1', 'ESTADO')
                            ->setCellValue('W1', 'ESTADO INVALIDO')
                            ->setCellValue('x1', 'COMENTARIOS');

                $i = 2;
                $query = $em->createQuery($this->strDqlLista);
                $arEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
                $arEstudios = $query->getResult();

                foreach ($arEstudios as $arEstudios) {
                    $fechaInicio = "";
                    if ($arEstudios->getFechaInicio() != null) {
                        $fechaInicio = $arEstudios->getFechaInicio()->format('Y/m/d');
                    }
                    $fechaTerminacion = "";
                    if ($arEstudios->getFechaTerminacion() != null) {
                        $fechaTerminacion = $arEstudios->getFechaTerminacion()->format('Y/m/d');
                    }
                    $fechaInicioAcreditacion = "";
                    if ($arEstudios->getFechaInicioAcreditacion() != null) {
                        $fechaInicioAcreditacion = $arEstudios->getFechaInicioAcreditacion()->format('Y/m/d');
                    }
                    $fechaVencimientoControl = "";
                    if ($arEstudios->getFechaVencimientoCurso() != null) {
                        $fechaVencimientoControl = $arEstudios->getFechaVencimientoCurso()->format('Y/m/d');
                    }
                    $fechaVencimientoAcreditacion = "";
                    if ($arEstudios->getFechaVencimientoAcreditacion() != null) {
                        $fechaVencimientoAcreditacion = $arEstudios->getFechaVencimientoAcreditacion()->format('Y/m/d');
                    }
                    $tipoAcreditacion = "";
                    if ($arEstudios->getCodigoEstudioTipoAcreditacionFk() != null) {
                        $tipoAcreditacion = $arEstudios->getEstudioTipoAcreditacionRel()->getNombre();
                    }
                    $academia = "";
                    if ($arEstudios->getCodigoAcademiaFk() != null) {
                        $academia = $arEstudios->getAcademiaRel()->getNombre();
                    }
                    $estadoInvalidado = "";
                    if ($arEstudios->getCodigoEstudioEstadoInvalidoFk() != null) {
                        $estadoInvalidado = $arEstudios->getEstudioEstadoInvalidoRel()->getNombre();
                    }
                    $gradoBachiller = "";
                    if ($arEstudios->getCodigoGradoBachillerFk() != null) {
                        $gradoBachiller = $arEstudios->getGradoBachillerRel()->getGrado();
                    }
                    $estado = "";
                    if ($arEstudios->getCodigoEstudioEstadoFk() != null) {
                        $estado = $arEstudios->getEstudioEstadoRel()->getNombre();
                    }
                    $graduado = "";
                    if ($arEstudios->getGraduado() == 1){
                        $graduado = "SI";
                    } else {
                        $graduado = "NO";
                    }
                    $ciudad = '';
                    if ($arEstudios->getCodigoCiudadFk() != null){
                        $ciudad = $arEstudios->getCiudadRel()->getNombre();
                    }
                    $cargo = '';
                    if ($arEstudios->getEmpleadoRel()->getCodigoCargoFk() != null){
                        $cargo = $arEstudios->getEmpleadoRel()->getCargoRel()->getNombre();
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arEstudios->getCodigoEmpleadoEstudioPk())
                            ->setCellValue('B' . $i, $arEstudios->getFecha()->format('Y/m/d'))
                            ->setCellValue('C' . $i, $arEstudios->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arEstudios->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('E' . $i, $cargo)
                            ->setCellValue('F' . $i, $arEstudios->getEmpleadoEstudioTipoRel()->getNombre())
                            ->setCellValue('G' . $i, $arEstudios->getInstitucion())
                            ->setCellValue('H' . $i, $arEstudios->getTitulo())
                            ->setCellValue('I' . $i, $ciudad)
                            ->setCellValue('J' . $i, $fechaInicio)
                            ->setCellValue('K' . $i, $fechaTerminacion)
                            ->setCellValue('L' . $i, $fechaVencimientoControl)
                            ->setCellValue('M' . $i, $gradoBachiller)
                            ->setCellValue('N' . $i, $objFunciones->devuelveBoolean($arEstudios->getGraduado()))
                            ->setCellValue('O' . $i, $tipoAcreditacion)
                            ->setCellValue('P' . $i, $academia)
                            ->setCellValue('Q' . $i, $fechaInicioAcreditacion)
                            ->setCellValue('R' . $i, $fechaVencimientoAcreditacion)
                            ->setCellValue('S' . $i, $arEstudios->getNumeroRegistro())
                            ->setCellValue('T' . $i, $arEstudios->getNumeroAcreditacion())
                            ->setCellValue('U' . $i, $objFunciones->devuelveBoolean($arEstudios->getValidarVencimiento()))
                            ->setCellValue('V' . $i, $estado)
                            ->setCellValue('W' . $i, $estadoInvalidado)
                            ->setCellValue('X' . $i, $arEstudios->getComentarios());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Estudios');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Estudios.xlsx"');
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
            
    private function generarInformeExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $nombreArchivo = "";
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
                for($col = 'A'; $col !== 'Z'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
                }
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Nit')
                            ->setCellValue('B1', 'RazonSocial')
                            ->setCellValue('C1', 'TipoDocumento')
                            ->setCellValue('D1', 'NoDocumento')
                            ->setCellValue('E1', 'Nombre1')
                            ->setCellValue('F1', 'Nombre2')
                            ->setCellValue('G1', 'Apellido1')
                            ->setCellValue('H1', 'Apellido2')
                            ->setCellValue('I1', 'FechaNacimiento')
                            ->setCellValue('J1', 'Genero')
                            ->setCellValue('K1', 'Cargo')
                            ->setCellValue('L1', 'FechaVinculacion')
                            ->setCellValue('M1', 'CodigoCurso')
                            ->setCellValue('N1', 'NitEscuela')
                            ->setCellValue('O1', 'Nro')
                            ->setCellValue('P1', 'TipoEstablecimiento')
                            ->setCellValue('Q1', 'TelefonoR')
                            ->setCellValue('R1', 'DireccionR')
                            ->setCellValue('S1', 'DireccionP')
                            ->setCellValue('T1', 'Departamento')
                            ->setCellValue('U1', 'Ciudad')
                            ->setCellValue('V1', 'EducacionBM')
                            ->setCellValue('W1', 'EducacionSuperior')
                            ->setCellValue('X1', 'Discapacidad');

                $i = 2;
                $query = $em->createQuery($this->strDqlLista);
                $arEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
                $arEstudios = $query->getResult();
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                foreach ($arEstudios as $arEstudios) {
                    $fechaInicio = "";
                    if ($arEstudios->getFechaInicio() != null) {
                        $fechaInicio = $arEstudios->getFechaInicio()->format('Y/m/d');
                    }
                    $fechaTerminacion = "";
                    if ($arEstudios->getFechaTerminacion() != null) {
                        $fechaTerminacion = $arEstudios->getFechaTerminacion()->format('Y/m/d');
                    }
                    $fechaInicioAcreditacion = "";
                    if ($arEstudios->getFechaInicioAcreditacion() != null) {
                        $fechaInicioAcreditacion = $arEstudios->getFechaInicioAcreditacion()->format('Y/m/d');
                    }
                    $tipoAcreditacion = "";
                    if ($arEstudios->getCodigoEstudioTipoAcreditacionFk() != null) {
                        $tipoAcreditacion = $arEstudios->getEstudioTipoAcreditacionRel()->getCodigoEstudioAcreditacion();
                    }
                    $academia = "";
                    if ($arEstudios->getCodigoAcademiaFk() != null) {
                        $academia = $arEstudios->getAcademiaRel()->getNit();
                    }
                    $estadoInvalidado = "";
                    if ($arEstudios->getCodigoEstudioEstadoInvalidoFk() != null) {
                        $estadoInvalidado = $arEstudios->getEstudioEstadoInvalidoRel()->getNombre();
                    }
                    $gradoBachiller = "";
                    if ($arEstudios->getCodigoGradoBachillerFk() != null) {
                        $gradoBachiller = $arEstudios->getGradoBachillerRel()->getGrado();
                    }
                    $estado = "";
                    if ($arEstudios->getCodigoEstudioEstadoFk() != null) {
                        $estado = $arEstudios->getEstudioEstadoRel()->getNombre();
                    }
                    $graduado = "";
                    if ($arEstudios->getGraduado() == 1){
                        $graduado = "SI";
                    } else {
                        $graduado = "NO";
                    }
                    
                    //tipo identificacion
                    $tipoIdentificacion = 1;
                    if ($arEstudios->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 13){
                        $tipoIdentificacion = 1;
                    }
                    if ($arEstudios->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 12){
                        $tipoIdentificacion = 1;
                    }
                    if ($arEstudios->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 21){
                        $tipoIdentificacion = 3;
                    }
                    if ($arEstudios->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 22){
                        $tipoIdentificacion = 3;
                    }
                    if ($arEstudios->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 41){
                        $tipoIdentificacion = 6;
                    }
                    //
                    $sexo = "";
                    if ($arEstudios->getEmpleadoRel()->getCodigoSexoFk() == "M"){
                        $sexo = 1;
                    } else {
                        $sexo = 2;
                    }
                    $cargo = "";
                    if ($arEstudios->getCodigoEstudioTipoAcreditacionFk() != null){
                        if ($arEstudios->getEstudioTipoAcreditacionRel()->getCargo() == "VIGILANTE"){
                            $cargo = 1;
                        }
                        if ($arEstudios->getEstudioTipoAcreditacionRel()->getCargo() == "ESCOLTA"){
                            $cargo = 2;
                        }
                        if ($arEstudios->getEstudioTipoAcreditacionRel()->getCargo() == "TRIPULANTE"){
                            $cargo = 3;
                        }
                        if ($arEstudios->getEstudioTipoAcreditacionRel()->getCargo() == "SUPERVISOR"){
                            $cargo = 4;
                        }
                        if ($arEstudios->getEstudioTipoAcreditacionRel()->getCargo() == "OPERADOR DE MEDIOS TECNOLOGICOS"){
                            $cargo = 5;
                        }
                        if ($arEstudios->getEstudioTipoAcreditacionRel()->getCargo() == "MANEJADOR CANINO"){
                            $cargo = 6;
                        }
                        if ($arEstudios->getEstudioTipoAcreditacionRel()->getCargo() == "DIRECTIVO"){
                            $cargo = 7;
                        }
                    } else {
                        $cargo = "";
                    }    
                    //CONTRATO
                    $codigoContrato = "";
                    if ($arEstudios->getEmpleadoRel()->getCodigoContratoActivoFk() != null){
                        $codigoContrato = $arEstudios->getEmpleadoRel()->getCodigoContratoActivoFk();
                    } else {
                        if ($arEstudios->getEmpleadoRel()->getCodigoContratoUltimoFk() != 0){
                            $codigoContrato = $arEstudios->getEmpleadoRel()->getCodigoContratoUltimoFk();
                        } else {
                            $codigoContrato = 0;
                        }
                        
                    }
                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
                    //
                    $telefono = "";
                    if ($arEstudios->getEmpleadoRel()->getTelefono() != null){
                        $telefono = $arEstudios->getEmpleadoRel()->getTelefono();
                    } else {
                        $telefono = $arEstudios->getEmpleadoRel()->getCelular();
                    }
                    $ciudadLabora = '';
                    $departamentoLabora = '';
                    $fechaDesde = '';
                    if ($arContrato){
                        $ciudadLabora = $arContrato->getCiudadLaboraRel()->getNombre();
                        $ciudadLabora = explode(" ", $ciudadLabora);
                        $ciudadLabora = $ciudadLabora[0];
                        $departamentoLabora = $arContrato->getCiudadLaboraRel()->getDepartamentoRel()->getNombre();
                        $fechaDesde = $arContrato->getFechaDesde()->format('d/m/Y');
                        
                    }
                    
                    $nivelEstudio = "";
                    $gradoBachiller = "Ninguna";
                    $superior = "Ninguna";
                    
                    $arEmpleadoBachiller = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
                    $arEmpleadoBachiller = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->findBy(array('codigoEmpleadoFk' => $arEstudios->getCodigoEmpleadoFk()));
                    foreach ($arEmpleadoBachiller as $arEmpleadoBachiller){
                        if ($arEmpleadoBachiller->getGraduado() == 1){
                            $gradoBachiller = 11;
                        }
                        if ($arEmpleadoBachiller->getCodigoGradoBachillerFk() != null){
                            $gradoBachiller = $arEmpleadoBachiller->getGradoBachillerRel()->getGrado();
                        }
                        if ($arEmpleadoBachiller->getCodigoEmpleadoEstudioTipoFk() == 3){
                            $superior = $arEmpleadoBachiller->getTitulo();
                            $gradoBachiller = 11;
                        }
                    }
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arConfiguracion->getNitEmpresa().$arConfiguracion->getDigitoVerificacionEmpresa())
                            ->setCellValue('B' . $i, strtoupper($arConfiguracion->getNombreEmpresa()))
                            ->setCellValue('C' . $i, $tipoIdentificacion)
                            ->setCellValue('D' . $i, $arEstudios->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('E' . $i, strtoupper($arEstudios->getEmpleadoRel()->getNombre1()))
                            ->setCellValue('F' . $i, strtoupper($arEstudios->getEmpleadoRel()->getNombre2()))
                            ->setCellValue('G' . $i, strtoupper($arEstudios->getEmpleadoRel()->getApellido1()))
                            ->setCellValue('H' . $i, strtoupper($arEstudios->getEmpleadoRel()->getApellido2()))
                            ->setCellValue('I' . $i, $arEstudios->getEmpleadoRel()->getFechaNacimiento()->format('d/m/Y'))
                            ->setCellValue('J' . $i, $sexo)
                            ->setCellValue('K' . $i, $cargo)
                            ->setCellValue('L' . $i, $fechaDesde)
                            ->setCellValue('M' . $i, $tipoAcreditacion)
                            ->setCellValue('N' . $i, $academia)
                            ->setCellValue('O' . $i, $arEstudios->getNumeroAcreditacion())
                            ->setCellValue('P' . $i, "Principal")
                            ->setCellValue('Q' . $i, $telefono)
                            ->setCellValue('R' . $i, $arEstudios->getEmpleadoRel()->getDireccion())
                            ->setCellValue('S' . $i, "sin definir")
                            ->setCellValue('T' . $i, $departamentoLabora)
                            ->setCellValue('U' . $i, $ciudadLabora)
                            ->setCellValue('V' . $i, $gradoBachiller)
                            ->setCellValue('W' . $i, ucfirst($superior))
                            ->setCellValue('X' . $i, "Ninguna");
                    $i++;
                }
                
                $nombreArchivo = "APO".$arConfiguracion->getNitEmpresa()."".date('Y-m-d');
                $objPHPExcel->getActiveSheet()->setTitle('EstudiosInforme');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'.$nombreArchivo.'.xlsx"');
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
