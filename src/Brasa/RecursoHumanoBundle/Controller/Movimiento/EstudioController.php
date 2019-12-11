<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoEstudioType;


class EstudioController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/rhu/estudio/lista", name="brs_rhu_estudio_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 36, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSelecionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnEliminar')->isClicked()){
                if(count($arrSelecionados) > 0) {
                    foreach ($arrSelecionados AS $codigoEstudio) {
                        $arEstudio = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
                        $arEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEstudio);
                        $em->remove($arEstudio);   
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_estudio_lista'));
                }
            }

            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }
            
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnExcelInforme')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarInformeExcel();
            }
        }

        $arEstudios = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Estudios:lista.html.twig', array('arEstudios' => $arEstudios, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/estudio/nuevo/{codigoEstudio}", name="brs_rhu_estudio_nuevo")
     */
    public function nuevoAction($codigoEstudio = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arEstudio = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
        if($codigoEstudio != 0) {
            $arEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEstudio);
        } else {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arEstudio->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuEmpleadoEstudioType, $arEstudio);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arEstudio = $form->getData();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    $arEstudio->setEmpleadoRel($arEmpleado);
                    if($arEmpleado->getCodigoContratoActivoFk() != '') {
                        if ($codigoEstudio == 0){
                            $arEstudio->setCodigoUsuario($arUsuario->getUserName());
                        }
                        $em->persist($arEstudio);
                        $em->flush();
                        if($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_estudio_nuevo', array('codigoEstudio' => 0 )));
                        } else {
                            if ($codigoEstudio == 0){
                                return $this->redirect($this->generateUrl('brs_rhu_estudio_detalle', array('codigoEstudio' => $arEstudio->getCodigoEmpleadoEstudioPk())));
                            } else {
                                return $this->redirect($this->generateUrl('brs_rhu_estudio_lista'));
                            }
                            
                        }
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo", $this);
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Estudios:nuevo.html.twig', array(
            'arEstudio' => $arEstudio,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/estudio/detalle/{codigoEstudio}", name="brs_rhu_estudio_detalle")
     */
    public function detalleAction($codigoEstudio) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arEstudio = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
        $arEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEstudio);
        $form = $this->formularioDetalle($arEstudio);
        $form->handleRequest($request);
        if($form->isValid()) {
            
            
        }
        $arEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEstudio);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Estudios:detalle.html.twig', array(
                    'arEstudio' => $arEstudio,
                    'form' => $form->createView()
                    ));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strListaDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->listaMovimientoDQL(
                $session->get('filtroIdentificacion'),
                $session->get('filtroNombre'),
                $session->get('filtroEstudio'),
                $session->get('filtroDesde')
                );
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedadesEstudio = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroEstudio')) {
            $arrayPropiedadesEstudio['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo", $session->get('filtroEstudio'));
        }
        

        $form = $this->createFormBuilder()
            ->add('empleadoEstudioTipoRel', 'entity', $arrayPropiedadesEstudio)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))    
            ->add('fechaVencimientoCurso','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            //->add('BtnExcelInforme', 'submit', array('label'  => 'Informe'))    
            
            ->getForm();
        return $form;
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroEstudio', $controles['empleadoEstudioTipoRel']);
        $dateFechaHasta = $form->get('fechaVencimientoCurso')->getData();
        if ($form->get('fechaVencimientoCurso')->getData() == null){
            $session->set('filtroDesde', $form->get('fechaVencimientoCurso')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaHasta->format('Y-m-d'));
        }
    }
    
    private function formularioDetalle($ar) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrBotonGenerar = array('label' => 'Generar solicitud', 'disabled' => false);
        $arrBotonValidado = array('label' => 'Validado', 'disabled' => false);
        $arrBotonNoValidado = array('label' => 'No validado', 'disabled' => false);
        $arrBotonAcreditado = array('label' => 'Acreditado', 'disabled' => false);
        $form = $this->createFormBuilder()
            
                ->getForm();  
        return $form;
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
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
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
                            ->setCellValue('L1', 'FECHA VENCIMIENTO CONTROL')
                            ->setCellValue('M1', 'GRADO BACHILLER')
                            ->setCellValue('N1', 'GRADUADO')
                            ->setCellValue('O1', 'NUMERO REGISTRO')
                            ->setCellValue('P1', 'VALIDAR')
                            ->setCellValue('Q1', 'COMENTARIOS');

                $i = 2;
                $query = $em->createQuery($this->strListaDql);
                $arEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
                $arEstudios = $query->getResult();

                foreach ($arEstudios as $arEstudios) {
                    $fecha = "";
                    if ($arEstudios->getFecha() != null) {
                        $fecha = $arEstudios->getFecha()->format('Y/m/d');
                    }
                    $ciudad = "";
                    if ($arEstudios->getCodigoCiudadFk() != null) {
                        $ciudad = $arEstudios->getCiudadRel()->getNombre();
                    }
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
                    $fechaEstado = "";
                    if ($arEstudios->getFechaEstado() != null) {
                        $fechaEstado = $arEstudios->getFechaEstado()->format('Y/m/d');
                    }
                    $fechaEstadoInvalido = "";
                    if ($arEstudios->getFechaEstadoInvalido() != null) {
                        $fechaEstadoInvalido = $arEstudios->getFechaEstadoInvalido()->format('Y/m/d');
                    }
                    $cargo = '';
                    if ($arEstudios->getEmpleadoRel()->getCodigoCargoFk() != null){
                        $cargo = $arEstudios->getEmpleadoRel()->getCargoRel()->getNombre();
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arEstudios->getCodigoEmpleadoEstudioPk())
                            ->setCellValue('B' . $i, $fecha)
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
                            ->setCellValue('O' . $i, $arEstudios->getNumeroRegistro())
                            ->setCellValue('P' . $i, $objFunciones->devuelveBoolean($arEstudios->getValidarVencimiento()))
                            ->setCellValue('Q' . $i, $arEstudios->getComentarios())
                            
                            ;
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
            
    /*private function generarInformeExcel() {
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
                $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
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
                $query = $em->createQuery($this->strListaDql);
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
                        if ($arEstudios->getEmpleadoRel()->getCodigoContratoUltimoFk() != null){
                            $codigoContrato = $arEstudios->getEmpleadoRel()->getCodigoContratoUltimoFk();
                        } else {
                            $codigoContrato = 0;
                        }
                        
                    }
                    
                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
                    //
                    
                    $ciudadLabora = "";
                    if ($arContrato != null){
                        if ($arContrato->getCodigoCiudadLaboraFk() != null){
                            $ciudadLabora = $arContrato->getCiudadLaboraRel()->getNombre();
                        }
                        $ciudadLabora = explode(" ", $ciudadLabora);
                        $ciudadLabora = $ciudadLabora[0];

                        if ($ciudadLabora == ""){
                            $departamentoCiudadLabora = "";
                        } else {
                            $departamentoCiudadLabora = $arContrato->getCiudadLaboraRel()->getDepartamentoRel()->getNombre();
                        }
                    $contratoFechaDesde = $arContrato->getFechaDesde()->format('d/m/Y');    
                    } else {
                        $ciudadLabora = "";
                        $departamentoCiudadLabora = "";
                        $contratoFechaDesde = "";
                    }
                        
                    
                    $telefono = "";
                    if ($arEstudios->getEmpleadoRel()->getTelefono() != null){
                        $telefono = $arEstudios->getEmpleadoRel()->getTelefono();
                    } else {
                        $telefono = $arEstudios->getEmpleadoRel()->getCelular();
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
                            ->setCellValue('L' . $i, $contratoFechaDesde)
                            ->setCellValue('M' . $i, $tipoAcreditacion)
                            ->setCellValue('N' . $i, $academia)
                            ->setCellValue('O' . $i, $arEstudios->getNumeroAcreditacion())
                            ->setCellValue('P' . $i, "Principal")
                            ->setCellValue('Q' . $i, $telefono)
                            ->setCellValue('R' . $i, $arEstudios->getEmpleadoRel()->getDireccion())
                            ->setCellValue('S' . $i, $arEstudios->getEmpleadoRel()->getDireccion())//FALTA LA DIRECCION DEL PUESTO
                            ->setCellValue('T' . $i, $departamentoCiudadLabora)
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
            } */       
}
