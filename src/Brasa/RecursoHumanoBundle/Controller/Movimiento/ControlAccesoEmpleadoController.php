<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuControlAccesoEmpleadoType;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuHorarioAccesoType;

class ControlAccesoEmpleadoController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/control/acceso/empleado/lista", name="brs_rhu_control_acceso_empleado_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 23, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnAnular')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoControlAccesoEmpleado) {
                        $arControlAccesoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
                        $arControlAccesoEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->find($codigoControlAccesoEmpleado);
                        //$em->remove($arControlAccesoEmpleado);
                        $arControlAccesoEmpleado->setAnulado(1);
                        $arControlAccesoEmpleado->setComentarios("REGISTRO ANULADO");
                        $em->persist($arControlAccesoEmpleado);
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_control_acceso_empleado_lista'));
                }
                $this->filtrarLista($form);
                //$this->listar();
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
            ->add('BtnAnular', 'submit', array('label'  => 'Anular'))    
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

    /**
     * @Route("/rhu/control/acceso/empleado/nuevo/{codigoHorarioAcceso}", name="brs_rhu_control_acceso_empleado_nuevo")
     */
    public function nuevoAction($codigoHorarioAcceso) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();       
        $arHorarioAcceso = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();                
        $arHorarioAcceso = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->find($codigoHorarioAcceso);
        $arTurno = new \Brasa\RecursoHumanoBundle\Entity\RhuTurno();
        $arTurno = $em->getRepository('BrasaRecursoHumanoBundle:RhuTurno')->find($arHorarioAcceso->getCodigoTurnoFk());
        
        $form = $this->createForm(new RhuHorarioAccesoType, $arHorarioAcceso);
        $form->handleRequest($request);
        if ($form->isValid()) { 
            $arHorarioAcceso = $form->getData();               
            $em->persist($arHorarioAcceso);
            $em->flush();   
            return $this->redirect($this->generateUrl('brs_rhu_control_acceso_empleado_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ControlAcceso:nuevo.html.twig', array(
            'form' => $form->createView(),
            'arHorarioAcceso' => $arHorarioAcceso,
            'arTurno' => $arTurno
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
            if ($arControlAccesoEmpleado->getDuracionEntradaTarde() == null){
                $duracionEntradaTarde = "";
            } else {
                $duracionEntradaTarde = $arControlAccesoEmpleado->getDuracionEntradaTarde();
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
                ->setCellValue('L' . $i, $duracionEntradaTarde)    
                ->setCellValue('M' . $i, $arControlAccesoEmpleado->getHoraSalidaTurno()->format('H:i:s'))        
                ->setCellValue('N' . $i, $timeHoraSalida)
                ->setCellValue('O' . $i, $objFunciones->devuelveBoolean($arControlAccesoEmpleado->getSalidaAntes()))    
                ->setCellValue('P' . $i, $duracionSalidaAntes)
                ->setCellValue('Q' . $i, $arControlAccesoEmpleado->getDuracionRegistro())
                ->setCellValue('R' . $i, $objFunciones->devuelveBoolean($arControlAccesoEmpleado->getAnulado()))    
                ->setCellValue('S' . $i, $arControlAccesoEmpleado->getComentarios());
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
