<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuAcreditacionType;

class AcreditacionController extends Controller
{
    var $strSqlLista = "";

    /**
     * @Route("/rhu/movimiento/acreditacion/", name="brs_rhu_movimiento_acreditacion")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        /*if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 12, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }*/
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->formularioLista();
                $this->listar();
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->formularioLista();
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnExcelInforme')->isClicked()) {
                $this->generarInformeExcel();
            }
            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoAcreditacion) {
                        $arAcreditacion = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();
                        $arAcreditacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacion')->find($codigoAcreditacion);
                        if($arAcreditacion->getEstadoValidado() == 0) {
                            $em->remove($arAcreditacion);
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_acreditacion'));
                }
            }

        }
        $arAcreditaciones = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Acreditacion:lista.html.twig', array(
            'arAcreditaciones' => $arAcreditaciones,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/movimiento/acreditacion/nuevo/{codigoAcreditacion}", name="brs_rhu_movimiento_acreditacion_nuevo")
     */
    public function nuevoAction($codigoAcreditacion = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arAcreditacion = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();
        if($codigoAcreditacion != 0) {
            $arAcreditacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacion')->find($codigoAcreditacion);
        } else {
            $arAcreditacion->setFecha(new \DateTime('now'));
            $arAcreditacion->setFechaVenceCurso(new \DateTime('now'));
            $arAcreditacion->setFechaVencimiento(new \DateTime('now'));
        }

        $form = $this->createForm(new RhuAcreditacionType(), $arAcreditacion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arAcreditacion = $form->getData();
            $arrControles = $request->request->All();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    $arAcreditacion->setEmpleadoRel($arEmpleado);
                    if($codigoAcreditacion == 0) {
                        $arAcreditacion->setCodigoUsuario($arUsuario->getUserName());
                    }
                    $em->persist($arAcreditacion);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_acreditacion_nuevo', array('codigoAcreditacion' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_acreditacion'));
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Acreditacion:nuevo.html.twig', array(
            'arAcreditacion' => $arAcreditacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/acreditacion/cargar/validacion/", name="brs_rhu_movimiento_acreditacion_cargar_validacion")
     */
    public function cargarValidacionAction() {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $request = $this->getRequest();
        $rutaTemporal = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $rutaTemporal = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $form = $this->createFormBuilder()
            ->add('numero', 'text', array('required' => true))
            ->add('fecha', 'date', array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))
            ->add('attachment', 'file')
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $arUsuario = $this->get('security.context')->getToken()->getUser();
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $form['attachment']->getData()->move($rutaTemporal->getRutaTemporal(), "archivo.csv");
                $numero = $form->get('numero')->getData();
                $fecha = $form->get('fecha')->getData();
                $ruta = $rutaTemporal->getRutaTemporal(). "archivo.csv";
                if (($gestor = fopen($ruta, "r")) !== FALSE) {
                    while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                        $registro = explode("\t",$datos[0]);
                        if(count($registro) > 1) {
                            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $registro[4]));
                            if($arEmpleado) {
                                $arAcreditaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();
                                $arAcreditaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacion')->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk(), 'estadoValidado' => 0, 'estadoRechazado' => 0));
                                foreach ($arAcreditaciones as $arAcreditacion) {
                                    $cargo = $arAcreditacion->getAcreditacionTipoRel()->getCargo();
                                    //Para quitar los formatos de html del string
                                    $cargo2 = strip_tags($registro[5]);
                                    $detalle = strip_tags($registro[6]);
                                    if ($cargo == $cargo2){
                                        $arAcreditacionActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();
                                        $arAcreditacionActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacion')->find($arAcreditacion->getCodigoAcreditacionPk());
                                        $arAcreditacionActualizar->setNumeroValidacion($numero);
                                        $arAcreditacionActualizar->setFechaValidacion($fecha);
                                        $arAcreditacionActualizar->setEstadoValidado(1);
                                        $arAcreditacionActualizar->setDetalleValidacion($detalle);
                                        $em->persist($arAcreditacionActualizar);
                                    }
                                }
                            }
                        }
                    }
                    fclose($gestor);
                    $em->flush();
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Acreditacion:cargarValidacion.html.twig', array(
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/movimiento/acreditacion/cargar/acreditacion/", name="brs_rhu_movimiento_acreditacion_cargar_acreditacion")
     */
    public function cargarAcreditacionAction() {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $request = $this->getRequest();
        $rutaTemporal = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $rutaTemporal = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $form = $this->createFormBuilder()
            ->add('attachment', 'file')
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $arUsuario = $this->get('security.context')->getToken()->getUser();
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $form['attachment']->getData()->move($rutaTemporal->getRutaTemporal(), "archivo.csv");
                $ruta = $rutaTemporal->getRutaTemporal(). "archivo.csv";
                if (($gestor = fopen($ruta, "r")) !== FALSE) {
                    while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                        $registro = explode("\t",$datos[0]);
                        if(count($registro) > 1) {
                            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $registro[4]));
                            if($arEmpleado) {
                                $arAcreditaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();
                                $arAcreditaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacion')->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk(), 'estadoValidado' => 1, 'estadoAcreditado' => 0));
                                foreach ($arAcreditaciones as $arAcreditacion) {
                                    $cargo = $arAcreditacion->getAcreditacionTipoRel()->getCargo();
                                    $cargo2 = strip_tags($registro[5]);
                                    $strFecha = strip_tags($registro[6]);
                                    $fecha = date_create($strFecha);
                                    if ($cargo == $cargo2){
                                        $arAcreditacionActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();
                                        $arAcreditacionActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacion')->find($arAcreditacion->getCodigoAcreditacionPk());
                                        $arAcreditacionActualizar->setEstadoAcreditado(1);
                                        $arAcreditacionActualizar->setFechaAcreditacion(new \DateTime('now'));
                                        $arAcreditacionActualizar->setFechaVencimiento($fecha);
                                        $em->persist($arAcreditacionActualizar);
                                    }
                                }
                            }
                        }
                    }
                    fclose($gestor);
                    $em->flush();
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Acreditacion:cargarAcreditacion.html.twig', array(
            'form' => $form->createView()
            ));
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreEmpleado = "";
        if($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if($arEmpleado) {
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
                $session->set('filtroRhuCodigoEmpleado', $arEmpleado->getCodigoEmpleadoPk());
            }  else {
                $session->set('filtroIdentificacion', null);
                $session->set('filtroRhuCodigoEmpleado', null);
            }
        } else {
            $session->set('filtroRhuCodigoEmpleado', null);
        }
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroRhuAcreditacionFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroRhuAcreditacionFechaDesde');
        }
        if($session->get('filtroRhuAcreditacionFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroRhuAcreditacionFechaHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('txtNumeroIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('txtNombreCorto', 'text', array('label'  => 'Nombre','data' => $strNombreEmpleado))
            ->add('estadoRechazado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'RECHAZADO', '0' => 'SIN RECHAZAR'), 'data' => $session->get('filtroRhuAcreditacionEstadoRechazado')))
            ->add('estadoValidado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'VALIDADO', '0' => 'SIN VALIDAR'), 'data' => $session->get('filtroRhuAcreditacionEstadoValidado')))
            ->add('estadoAcreditado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'ACREDITADO', '0' => 'SIN ACREDITAR'), 'data' => $session->get('filtroRhuAcreditacionEstadoAcreditado')))
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
            ->add('filtrarFecha', 'checkbox', array('required'  => false, 'data' => $session->get('filtroRhuAcreditacionFiltrarFecha')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcelInforme', 'submit', array('label'  => 'Informe'))
            ->getForm();
        return $form;
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroRhuAcreditacionFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroRhuAcreditacionFechaDesde');
            $strFechaHasta = $session->get('filtroRhuAcreditacionFechaHasta');
        }
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacion')->listaDQL(
                $session->get('filtroRhuCodigoEmpleado'),
                $session->get('filtroRhuAcreditacionEstadoRechazado'),
                $session->get('filtroRhuAcreditacionEstadoValidado'),
                $session->get('filtroRhuAcreditacionEstadoAcreditado'),
                $strFechaDesde,
                $strFechaHasta
                );
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroRhuAcreditacionEstadoRechazado', $form->get('estadoRechazado')->getData());
        $session->set('filtroRhuAcreditacionEstadoValidado', $form->get('estadoValidado')->getData());
        $session->set('filtroRhuAcreditacionEstadoAcreditado', $form->get('estadoAcreditado')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroRhuAcreditacionFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroRhuAcreditacionFechaHasta', $dateFechaHasta->format('Y/m/d'));
        $session->set('filtroRhuAcreditacionFiltrarFecha', $form->get('filtrarFecha')->getData());
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
        for($col = 'A'; $col !== 'O'; $col++) {            
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'DOCUMENTO')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'TIPO')
                    ->setCellValue('E1', 'VENCE')
                    ->setCellValue('F1', 'CARGO')
                    ->setCellValue('G1', 'REGISTRO')
                    ->setCellValue('H1', 'REC')
                    ->setCellValue('I1', 'MOTIVO')
                    ->setCellValue('J1', 'VAL')
                    ->setCellValue('K1', 'NUMERO')
                    ->setCellValue('L1', 'FECHA')
                    ->setCellValue('M1', 'ACREDITADO')
                    ->setCellValue('N1', 'FECHA')
                    ->setCellValue('O1', 'VENCE');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arAcreditaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();
        $arAcreditaciones = $query->getResult();
        foreach ($arAcreditaciones as $arAcreditacion) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAcreditacion->getCodigoAcreditacionPk())
                    ->setCellValue('B' . $i, $arAcreditacion->getEmpleadoRel()->getnumeroIdentificacion())
                    ->setCellValue('C' . $i, $arAcreditacion->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arAcreditacion->getAcreditacionTipoRel()->getNombre())
                    ->setCellValue('E' . $i, $arAcreditacion->getFechaVenceCurso()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arAcreditacion->getAcreditacionTipoRel()->getCargo())
                    ->setCellValue('G' . $i, $arAcreditacion->getNumeroRegistro())
                    ->setCellValue('H' . $i, $objFunciones->devuelveBoolean($arAcreditacion->getEstadoRechazado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arAcreditacion->getEstadoValidado()))
                    ->setCellValue('K' . $i, $arAcreditacion->getNumeroValidacion())                    
                    ->setCellValue('M' . $i, $objFunciones->devuelveBoolean($arAcreditacion->getEstadoAcreditado()));
            if($arAcreditacion->getCodigoAcreditacionRechazoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $i, $arAcreditacion->getAcreditacionRechazoRel()->getNombre());
            }
            if($arAcreditacion->getEstadoValidado()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $i, $arAcreditacion->getFechaValidacion()->format('Y-m-d'));
            }
            if($arAcreditacion->getEstadoAcreditado()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $i, $arAcreditacion->getFechaAcreditacion()->format('Y-m-d'));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $i, $arAcreditacion->getFechaVencimiento()->format('Y-m-d'));
            }            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Acreditaciones');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Acreditaciones.xlsx"');
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
                for($col = 'A'; $col !== 'Y'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
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
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                $dql   = "SELECT a FROM BrasaRecursoHumanoBundle:RhuAcreditacion a WHERE a.estadoValidado = 0 AND a.estadoRechazado = 0";
                $query = $em->createQuery($dql);
                $arAcreditaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();
                $arAcreditaciones = $query->getResult();
                foreach ($arAcreditaciones as $arAcreditacion) {

                    //tipo identificacion
                    $tipoIdentificacion = 1;
                    if ($arAcreditacion->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 13){
                        $tipoIdentificacion = 1;
                    }
                    if ($arAcreditacion->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 12){
                        $tipoIdentificacion = 1;
                    }
                    if ($arAcreditacion->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 21){
                        $tipoIdentificacion = 3;
                    }
                    if ($arAcreditacion->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 22){
                        $tipoIdentificacion = 3;
                    }
                    if ($arAcreditacion->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 41){
                        $tipoIdentificacion = 6;
                    }
                    //
                    $sexo = "";
                    if ($arAcreditacion->getEmpleadoRel()->getCodigoSexoFk() == "M"){
                        $sexo = 1;
                    } else {
                        $sexo = 2;
                    }

                    //CONTRATO
                    $codigoContrato = "";
                    if ($arAcreditacion->getEmpleadoRel()->getCodigoContratoActivoFk() != null){
                        $codigoContrato = $arAcreditacion->getEmpleadoRel()->getCodigoContratoActivoFk();
                    } else {
                        $codigoContrato = $arAcreditacion->getEmpleadoRel()->getCodigoContratoUltimoFk();
                    }
                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);

                    $gradoBachiller = "11";
                    $superior = "Ninguna";
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arConfiguracion->getNitEmpresa().$arConfiguracion->getDigitoVerificacionEmpresa())
                            ->setCellValue('B' . $i, strtoupper($arConfiguracion->getNombreEmpresa()))
                            ->setCellValue('C' . $i, $tipoIdentificacion)
                            ->setCellValue('D' . $i, $arAcreditacion->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('E' . $i, strtoupper($arAcreditacion->getEmpleadoRel()->getNombre1()))
                            ->setCellValue('F' . $i, strtoupper($arAcreditacion->getEmpleadoRel()->getNombre2()))
                            ->setCellValue('G' . $i, strtoupper($arAcreditacion->getEmpleadoRel()->getApellido1()))
                            ->setCellValue('H' . $i, strtoupper($arAcreditacion->getEmpleadoRel()->getApellido2()))
                            ->setCellValue('I' . $i, $arAcreditacion->getEmpleadoRel()->getFechaNacimiento()->format('d/m/Y'))
                            ->setCellValue('J' . $i, $sexo)
                            ->setCellValue('K' . $i, $arAcreditacion->getAcreditacionTipoRel()->getCargoCodigo())
                            ->setCellValue('L' . $i, $arContrato->getFechaDesde()->format('d/m/Y'))
                            ->setCellValue('M' . $i, $arAcreditacion->getAcreditacionTipoRel()->getCodigo())
                            ->setCellValue('N' . $i, $arAcreditacion->getAcademiaRel()->getNit())
                            ->setCellValue('O' . $i, $arAcreditacion->getNumeroRegistro())
                            ->setCellValue('P' . $i, "Principal")
                            ->setCellValue('Q' . $i, $arAcreditacion->getEmpleadoRel()->getTelefono())
                            ->setCellValue('R' . $i, $arAcreditacion->getEmpleadoRel()->getDireccion())
                            ->setCellValue('S' . $i, $arAcreditacion->getEmpleadoRel()->getDireccion())
                            ->setCellValue('T' . $i, $arContrato->getCiudadLaboraRel()->getDepartamentoRel()->getNombre())
                            ->setCellValue('U' . $i, $arContrato->getCiudadLaboraRel()->getNombre())
                            ->setCellValue('V' . $i, $gradoBachiller)
                            ->setCellValue('W' . $i, ucfirst($superior))
                            ->setCellValue('X' . $i, "Ninguna");
                    $i++;
                }

                $nombreArchivo = "APO".$arConfiguracion->getNitEmpresa().$arConfiguracion->getDigitoVerificacionEmpresa()."".date('Ymd')."01";
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
