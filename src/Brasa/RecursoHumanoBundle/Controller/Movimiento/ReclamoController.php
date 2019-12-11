<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuReclamoType;

class ReclamoController extends Controller
{
    var $strSqlLista = "";

    /**
     * @Route("/rhu/movimiento/reclamo/", name="brs_rhu_movimiento_reclamo")
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
                    foreach ($arrSeleccionados AS $codigoReclamo) {
                        $arReclamo = new \Brasa\RecursoHumanoBundle\Entity\RhuReclamo();
                        $arReclamo = $em->getRepository('BrasaRecursoHumanoBundle:RhuReclamo')->find($codigoReclamo);
                        if($arReclamo->getEstadoValidado() == 0) {
                            $em->remove($arReclamo);
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_reclamo'));
                }
            }

        }
        $arReclamos = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Reclamo:lista.html.twig', array(
            'arReclamos' => $arReclamos,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/movimiento/reclamo/nuevo/{codigoReclamo}", name="brs_rhu_movimiento_reclamo_nuevo")
     */
    public function nuevoAction($codigoReclamo = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arReclamo = new \Brasa\RecursoHumanoBundle\Entity\RhuReclamo();
        if($codigoReclamo != 0) {
            $arReclamo = $em->getRepository('BrasaRecursoHumanoBundle:RhuReclamo')->find($codigoReclamo);
        } else {
            $arReclamo->setFecha(new \DateTime('now'));
            $arReclamo->setFechaRegistro(new \DateTime('now'));            
        }

        $form = $this->createForm(new RhuReclamoType(), $arReclamo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arReclamo = $form->getData();
            $arrControles = $request->request->All();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    $arReclamo->setEmpleadoRel($arEmpleado);
                    if($codigoReclamo == 0) {
                        $arReclamo->setCodigoUsuario($arUsuario->getUserName());
                    }
                    $em->persist($arReclamo);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_reclamo_nuevo', array('codigoReclamo' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_reclamo'));
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Reclamo:nuevo.html.twig', array(
            'arReclamo' => $arReclamo,
            'form' => $form->createView()));
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
        if($session->get('filtroRhuReclamoFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroRhuReclamoFechaDesde');
        }
        if($session->get('filtroRhuReclamoFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroRhuReclamoFechaHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('txtNumeroIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('txtNombreCorto', 'text', array('label'  => 'Nombre','data' => $strNombreEmpleado))
            ->add('estadoCerrado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'CERRADO', '0' => 'SIN CERRAR'), 'data' => $session->get('filtroRhuReclamoEstadoCerrado')))
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
            ->add('filtrarFecha', 'checkbox', array('required'  => false, 'data' => $session->get('filtroRhuReclamoFiltrarFecha')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->getForm();
        return $form;
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroRhuReclamoFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroRhuReclamoFechaDesde');
            $strFechaHasta = $session->get('filtroRhuReclamoFechaHasta');
        }
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuReclamo')->listaDQL(
                $session->get('filtroRhuCodigoEmpleado'),
                $strFechaDesde,
                $strFechaHasta,
                $session->get('filtroRhuReclamoEstadoCerrado'));
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroRhuReclamoEstadoRechazado', $form->get('estadoRechazado')->getData());
        $session->set('filtroRhuReclamoEstadoValidado', $form->get('estadoValidado')->getData());
        $session->set('filtroRhuReclamoEstadoAcreditado', $form->get('estadoAcreditado')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroRhuReclamoFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroRhuReclamoFechaHasta', $dateFechaHasta->format('Y/m/d'));
        $session->set('filtroRhuReclamoFiltrarFecha', $form->get('filtrarFecha')->getData());
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
        $arReclamoes = new \Brasa\RecursoHumanoBundle\Entity\RhuReclamo();
        $arReclamoes = $query->getResult();
        foreach ($arReclamoes as $arReclamo) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arReclamo->getCodigoReclamoPk())
                    ->setCellValue('B' . $i, $arReclamo->getEmpleadoRel()->getnumeroIdentificacion())
                    ->setCellValue('C' . $i, $arReclamo->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arReclamo->getReclamoTipoRel()->getNombre())
                    ->setCellValue('E' . $i, $arReclamo->getFechaVenceCurso()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arReclamo->getReclamoTipoRel()->getCargo())
                    ->setCellValue('G' . $i, $arReclamo->getNumeroRegistro())
                    ->setCellValue('H' . $i, $objFunciones->devuelveBoolean($arReclamo->getEstadoRechazado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arReclamo->getEstadoValidado()))
                    ->setCellValue('K' . $i, $arReclamo->getNumeroValidacion())                    
                    ->setCellValue('M' . $i, $objFunciones->devuelveBoolean($arReclamo->getEstadoAcreditado()));
            if($arReclamo->getCodigoReclamoRechazoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $i, $arReclamo->getReclamoRechazoRel()->getNombre());
            }
            if($arReclamo->getEstadoValidado()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $i, $arReclamo->getFechaValidacion()->format('Y-m-d'));
            }
            if($arReclamo->getEstadoAcreditado()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $i, $arReclamo->getFechaReclamo()->format('Y-m-d'));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $i, $arReclamo->getFechaVencimiento()->format('Y-m-d'));
            }            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Reclamoes');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reclamoes.xlsx"');
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
