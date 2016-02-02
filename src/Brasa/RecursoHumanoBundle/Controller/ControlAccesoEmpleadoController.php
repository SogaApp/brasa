<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuControlAccesoEmpleadoType;
use Doctrine\ORM\EntityRepository;


class ControlAccesoEmpleadoController extends Controller
{
    var $strSqlLista = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoControlAccesoEmpleado) {
                        $arControlAccesoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
                        $arControlAccesoEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->find($codigoControlAccesoEmpleado);
                        $em->remove($arControlAccesoEmpleado);
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_control_acceso_empleado_lista'));
                }
                $this->filtrarLista($form);
                $this->listar();
            }
            

            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arControlAccesoEmpleados = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ControlAcceso:empleado.html.twig', array(
            'arControlAccesoEmpleados' => $arControlAccesoEmpleados,
            'form' => $form->createView()
            ));
    }
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->listaDql(                    
            $session->get('filtroIdentificacion'),    
            $session->get('filtroNombre'),
            $session->get('filtroDesde'),
            $session->get('filtroHasta')
            );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtNumeroIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))    
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());
        $session->set('filtroNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }

    public function nuevoAction($codigoControlAcceso) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arControlAccesoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
        if ($codigoControlAcceso != 0)
        {
            $arControlAccesoEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->find($codigoControlAcceso);
            if ($arControlAccesoEmpleado->getEstadoSalida() == "1"){
                $intSalida = "SI";
            } else {
                $intSalida = "NO";
            }
            $arControlAccesoEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->find($codigoControlAcceso);
            if ($arControlAccesoEmpleado->getEstadoEntrada() == "1"){
                $intEntrada = "SI";
            } else {
                $intEntrada = "NO";
            }
            if ($arControlAccesoEmpleado->getFechaSalida() == null){
                $dateFechaSalida = new \DateTime('now');
            } else {
                $dateFechaSalida = $arControlAccesoEmpleado->getFechaSalida();
            }
        } 
         
        $form = $this->createFormBuilder()
            ->add('identificacion', 'text', array('data' => $arControlAccesoEmpleado->getEmpleadoRel()->getNumeroIdentificacion()))
            ->add('nombre', 'text', array('data' => $arControlAccesoEmpleado->getEmpleadoRel()->getNombreCorto()))    
            ->add('fechaEntrada', 'datetime', array('date_format' => 'yyyyMMMMdd H:i:s','required' => true, 'data' => $arControlAccesoEmpleado->getFechaEntrada()))
            ->add('fechaSalida', 'datetime', array('date_format' => 'yyyyMMMMdd H:i:s', 'required' => true, 'data' => $dateFechaSalida))
            ->add('entrada', 'choice', array('choices' => array($arControlAccesoEmpleado->getEstadoEntrada() => $intEntrada, '0' => 'NO', '1' => 'SI')))    
            ->add('salida', 'choice', array('choices' => array($arControlAccesoEmpleado->getEstadoSalida() => $intSalida, '0' => 'NO', '1' => 'SI')))    
            ->add('comentarios', 'textarea', array('data' => $arControlAccesoEmpleado->getComentarios(), 'required' => false))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $arControlAccesoEmpleado->setFechaEntrada($form->get('fechaEntrada')->getData());
            $arControlAccesoEmpleado->setFechaSalida($form->get('fechaSalida')->getData());
            $dateEntrada = $arControlAccesoEmpleado->getFechaEntrada();
            $dateSalida = $arControlAccesoEmpleado->getFechaSalida();
            $dateDiferencia = date_diff($dateSalida, $dateEntrada);
            $horas = $dateDiferencia->format('%H');
            $minutos = $dateDiferencia->format('%i');
            $segundos = $dateDiferencia->format('%s');
            $horaEntrada = $dateEntrada->format('H');
            $horaSalida = $dateSalida->format('H');
            $diferencia = $horas.":".$minutos.":".$segundos;
            if ($diferencia == "00:0:0"){
                $diferencia = "";
            }

            if ($form->get('entrada')->getData() == 1 && $form->get('salida')->getData() == 1) {
                if ($horaSalida >= $horaEntrada){

                    $arControlAccesoEmpleado->setFechaEntrada($form->get('fechaEntrada')->getData());
                    $arControlAccesoEmpleado->setFechaSalida($form->get('fechaSalida')->getData());
                    $arControlAccesoEmpleado->setEstadoSalida(1);
                    $arControlAccesoEmpleado->setEstadoEntrada(1);
                    $arControlAccesoEmpleado->setDuracionRegistro($diferencia);
                    $arControlAccesoEmpleado->setComentarios($form->get('comentarios')->getData());
                    
                    //calculo entradaTarde
                            $horaTurno = $arControlAccesoEmpleado->getHoraEntradaTurno()->format('H:i:s');
                            $horaLlegada = $arControlAccesoEmpleado->getFechaEntrada()->format('H:i:s');
                            if ($horaTurno < $horaLlegada){
                                $arControlAccesoEmpleado->setLlegadaTarde(1);
                                $date1 = strtotime($horaTurno);
                                $date2 = strtotime($horaLlegada);
                                $interval = $date2 - $date1;
                                $seconds = $interval % 60;
                                $minutes = floor(($interval % 3600) / 60);
                                $hours = floor($interval / 3600);
                                $timeLlegadaTarde = $hours.":".$minutes.":".$seconds;
                                $arControlAccesoEmpleado->setDuracionLlegadaTarde($timeLlegadaTarde);
                            }
                    
                     //calculo salidaAntes
                            $horaTurno = $arControlAccesoEmpleado->getHoraSalidaTurno()->format('H:i:s');
                            $horaSalida = $arControlAccesoEmpleado->getFechaSalida()->format('H:i:s');
                            if ($horaTurno > $horaSalida){
                                $arControlAccesoEmpleado->setSalidaAntes(1);
                                $date1 = strtotime($horaTurno);
                                $date2 = strtotime($horaSalida);
                                $interval = $date1 - $date2;
                                $seconds = $interval % 60;
                                $minutes = floor(($interval % 3600) / 60);
                                $hours = floor($interval / 3600);
                                $timeSalidaTarde = $hours.":".$minutes.":".$seconds;
                                $arControlAccesoEmpleado->setDuracionSalidaAntes($timeSalidaTarde);
                            }
                    
                    
                    $em->persist($arControlAccesoEmpleado);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_control_acceso_empleado_lista'));
                } else {
                        $objMensaje->Mensaje("error", "La hora de salida no puede ser menor a la hora de entrada", $this);
                    }  

            }
            if ($form->get('entrada')->getData() == 0 && $form->get('salida')->getData() == 0) {
                $arControlAccesoEmpleado->setFechaSalida(null);
                $arControlAccesoEmpleado->setFechaEntrada($form->get('fechaEntrada')->getData());
                $arControlAccesoEmpleado->setEstadoSalida(0);
                $arControlAccesoEmpleado->setEstadoEntrada(0);
                $arControlAccesoEmpleado->setLlegadaTarde(0);
                $arControlAccesoEmpleado->setSalidaAntes(0);
                $arControlAccesoEmpleado->setDuracionRegistro("");
                $arControlAccesoEmpleado->setDuracionLlegadaTarde("");
                $arControlAccesoEmpleado->setDuracionSalidaAntes("");
                $arControlAccesoEmpleado->setComentarios($form->get('comentarios')->getData());
                $em->persist($arControlAccesoEmpleado);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_control_acceso_empleado_lista'));
            }
            if ($form->get('entrada')->getData() == 0 && $form->get('salida')->getData() == 1) {
                $objMensaje->Mensaje("error", "No se puede salir el empleado sin haberse registrado", $this);
            }
            if ($form->get('entrada')->getData() == 1 && $form->get('salida')->getData() == 0) {
                $arControlAccesoEmpleado->setFechaSalida(null);
                $arControlAccesoEmpleado->setFechaEntrada($form->get('fechaEntrada')->getData());
                $arControlAccesoEmpleado->setEstadoSalida(0);
                $arControlAccesoEmpleado->setEstadoEntrada(1);
                $arControlAccesoEmpleado->setDuracionRegistro("");
                $arControlAccesoEmpleado->setComentarios($form->get('comentarios')->getData());
                //calculo entradaTarde
                    $horaTurno = $arControlAccesoEmpleado->getHoraEntradaTurno()->format('H:i:s');
                    $horaLlegada = $arControlAccesoEmpleado->getFechaEntrada()->format('H:i:s');
                    if ($horaTurno < $horaLlegada){
                        $arControlAccesoEmpleado->setLlegadaTarde(1);
                        $date1 = strtotime($horaTurno);
                        $date2 = strtotime($horaLlegada);
                        $interval = $date2 - $date1;
                        $seconds = $interval % 60;
                        $minutes = floor(($interval % 3600) / 60);
                        $hours = floor($interval / 3600);
                        $timeLlegadaTarde = $hours.":".$minutes.":".$seconds;
                        $arControlAccesoEmpleado->setDuracionLlegadaTarde($timeLlegadaTarde);
                    }
                $em->persist($arControlAccesoEmpleado);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_control_acceso_empleado_lista'));
            }
               
            
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ControlAcceso:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
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
                    ->setCellValue('R1', 'COMENTARIOS');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arControlAccesoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
        $arControlAccesoEmpleado = $query->getResult();
        $j = 1;
        foreach ($arControlAccesoEmpleado as $arControlAccesoEmpleado) {
            
            if ($arControlAccesoEmpleado->getFechaEntrada()->format('H:i:s') == "00:00:00"){
                $timeHoraEntrada = "SIN ENTRADA";
            } else {
                $timeHoraEntrada = $arControlAccesoEmpleado->getFechaEntrada()->format('H:i:s');
            }
            if ($arControlAccesoEmpleado->getFechaSalida() == null){
                $timeHoraSalida = "SIN SALIDA";
            } else {
                if ($arControlAccesoEmpleado->getFechaSalida()->format('H:i:s') == "00:00:00") {
                    $timeHoraSalida = "SIN SALIDA";
                }
                    $timeHoraSalida = $arControlAccesoEmpleado->getFechaSalida()->format('H:i:s');
                
            }
            if ($arControlAccesoEmpleado->getDuracionLlegadaTarde() == null){
                $duracionLLegadaTarde = "";
            } else {
                $duracionLLegadaTarde = $arControlAccesoEmpleado->getDuracionLlegadaTarde();
            }
            if ($arControlAccesoEmpleado->getDuracionSalidaAntes() == null){
                $duracionSalidaAntes = "";
            } else {
                $duracionSalidaAntes = $arControlAccesoEmpleado->getDuracionSalidaAntes();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $j)    
                ->setCellValue('B' . $i, $arControlAccesoEmpleado->getEmpleadoRel()->getNumeroIdentificacion())
                ->setCellValue('C' . $i, $arControlAccesoEmpleado->getEmpleadoRel()->getNombreCorto())
                ->setCellValue('D' . $i, $arControlAccesoEmpleado->getEmpleadoRel()->getCentroCostoRel()->getNombre())                        
                ->setCellValue('E' . $i, $arControlAccesoEmpleado->getEmpleadoRel()->getDepartamentoEmpresaRel()->getNombre())                    
                ->setCellValue('F' . $i, $arControlAccesoEmpleado->getEmpleadoRel()->getCargoRel()->getNombre())                    
                ->setCellValue('G' . $i, $arControlAccesoEmpleado->getFechaEntrada()->format('Y-m-d'))
                ->setCellValue('H' . $i, $arControlAccesoEmpleado->getCodigoTurnoFk())
                ->setCellValue('I' . $i, $arControlAccesoEmpleado->getHoraEntradaTurno()->format('H:i:s'))
                ->setCellValue('J' . $i, $timeHoraEntrada)
                ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arControlAccesoEmpleado->getLlegadaTarde()))
                ->setCellValue('L' . $i, $duracionLLegadaTarde)    
                ->setCellValue('M' . $i, $arControlAccesoEmpleado->getHoraSalidaTurno()->format('H:i:s'))        
                ->setCellValue('N' . $i, $timeHoraSalida)
                ->setCellValue('O' . $i, $objFunciones->devuelveBoolean($arControlAccesoEmpleado->getSalidaAntes()))    
                ->setCellValue('P' . $i, $duracionSalidaAntes)
                ->setCellValue('Q' . $i, $arControlAccesoEmpleado->getDuracionRegistro())        
                ->setCellValue('R' . $i, $arControlAccesoEmpleado->getComentarios());
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