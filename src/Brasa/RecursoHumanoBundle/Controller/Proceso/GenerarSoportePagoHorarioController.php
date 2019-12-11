<?php
namespace Brasa\RecursoHumanoBundle\Controller\Proceso;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSoportePagoHorarioType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSoportePagoHorarioDetalleType;
class GenerarSoportePagoHorarioController extends Controller
{
    var $strListaDql = "";

    /**
     * @Route("/rhu/proceso/soporte/pago/horario", name="brs_rhu_proceso_soporte_pago_horario")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 64)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if($request->request->get('OpGenerar')) {
                $codigoSoportePagoHorario = $request->request->get('OpGenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorario')->generar($codigoSoportePagoHorario);
            }
            if($request->request->get('OpDeshacer')) {
                $codigoSoportePagoHorario = $request->request->get('OpDeshacer');
                $strSql = "DELETE FROM rhu_soporte_pago_horario_detalle WHERE codigo_soporte_pago_horario_fk = " . $codigoSoportePagoHorario;           
                $em->getConnection()->executeQuery($strSql);
                
                $arSoportePagoHorario = new \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorario();
                $arSoportePagoHorario = $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorario')->find($codigoSoportePagoHorario);                                
                $arSoportePagoHorario->setEstadoGenerado(0);
                $em->persist($arSoportePagoHorario);
                $em->flush();                                                  
                return $this->redirect($this->generateUrl('brs_rhu_proceso_soporte_pago_horario'));                
            }    
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorario')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_proceso_soporte_pago_horario'));
            }            
        }
        $arSoportesPagoHorario = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        //$arSoportesPagoHorarioDetalle = $paginator->paginate($em->createQuery($this->strListaDqlDetalle), $request->query->get('page', 1), 20);        
        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarSoportePagoHorario:lista.html.twig', array(
            'arSoportesPagosHorarios' => $arSoportesPagoHorario,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/rhu/proceso/soporte/pago/horario/nuevo/{codigoSoportePagoHorario}", name="brs_rhu_proceso_soporte_pago_horario_nuevo")
     */     
    public function nuevoAction($codigoSoportePagoHorario) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arSoportePagoHorario = new \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorario();
        if($codigoSoportePagoHorario != 0) {
            $arSoportePagoHorario = $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorario')->find($codigoSoportePagoHorario);
        }else{
            $arSoportePagoHorario->setFechaDesde(new \DateTime('now'));            
            $arSoportePagoHorario->setFechaHasta(new \DateTime('now'));  
        }
        $form = $this->createForm(new RhuSoportePagoHorarioType(), $arSoportePagoHorario);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arSoportePagoHorario = $form->getData();            
            $em->persist($arSoportePagoHorario);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_proceso_soporte_pago_horario'));                                                                              
        }
        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarSoportePagoHorario:nuevo.html.twig', array(
            'arSoportePagoHorario' => $arSoportePagoHorario,
            'form' => $form->createView()));
    }        
    
    /**
     * @Route("/rhu/proceso/soporte/pago/horario/detalle/{codigoSoportePagoHorario}", name="brs_rhu_proceso_soporte_pago_horario_detalle")
     */    
    public function detalleAction($codigoSoportePagoHorario) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioDetalle();
        $form->handleRequest($request);
        $this->listaDetalle($codigoSoportePagoHorario);
        if ($form->isValid()) {           
            if ($form->get('BtnExcel')->isClicked()) {
                //$this->filtrar($form);
                $this->listaDetalle($codigoSoportePagoHorario);
                $this->generarExcel();
            }    
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorarioDetalle')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_proceso_soporte_pago_horario_detalle', array('codigoSoportePagoHorario' => $codigoSoportePagoHorario)));
            }
        }
        $arSoportesPagoHorarioDetalle = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);        
        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarSoportePagoHorario:detalle.html.twig', array(
            'arSoportesPagosHorariosDetalles' => $arSoportesPagoHorarioDetalle,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/rhu/proceso/soporte/pago/horario/detalle/nuevo/{codigoSoportePagoHorarioDetalle}", name="brs_rhu_proceso_soporte_pago_horario_detalle_nuevo")
     */    
    public function detalleNuevoAction($codigoSoportePagoHorarioDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arSoportePagoHorarioDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle();
        if($codigoSoportePagoHorarioDetalle != 0) {
            $arSoportePagoHorarioDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorarioDetalle')->find($codigoSoportePagoHorarioDetalle);
        }
        $form = $this->createForm(new RhuSoportePagoHorarioDetalleType(), $arSoportePagoHorarioDetalle);
        $form->handleRequest($request);        
        if ($form->isValid()) {           
            $arSoportePagoHorarioDetalle = $form->getData();            
            $em->persist($arSoportePagoHorarioDetalle);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                            
        }
        
        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarSoportePagoHorario:detalleNuevo.html.twig', array(
            'form' => $form->createView()));
    }        
    
    /**
     * @Route("/rhu/proceso/soporte/pago/horario/detalle/ver/{codigoSoportePagoHorarioDetalle}", name="brs_rhu_proceso_soporte_pago_horario_detalle_ver")
     */    
    public function verAction($codigoSoportePagoHorarioDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');                        
        $arSoportePagoHorarioDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle();
        $arSoportePagoHorarioDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorarioDetalle')->find($codigoSoportePagoHorarioDetalle);                        
        $arHorarioAcceso = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
        $arHorarioAcceso = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->empleado($arSoportePagoHorarioDetalle->getFechaDesde()->format('Y/m/d'), $arSoportePagoHorarioDetalle->getFechaHasta()->format('Y/m/d'), $arSoportePagoHorarioDetalle->getCodigoEmpleadoFk());                        
        $arPermisos = new \Brasa\RecursoHumanoBundle\Entity\RhuPermiso();
        $arPermisos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPermiso')->permisoPeriodo($arSoportePagoHorarioDetalle->getFechaDesde()->format('Y/m/d'), $arSoportePagoHorarioDetalle->getFechaHasta()->format('Y/m/d'), $arSoportePagoHorarioDetalle->getCodigoEmpleadoFk());        
        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarSoportePagoHorario:ver.html.twig', array(                        
            'arHorarioAcceso' => $arHorarioAcceso,
            'arPermisos' => $arPermisos,
            ));
    }         
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorario')->listaDql();        
    }
    
    private function listaDetalle($codigoSoportePagoHorario) {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorarioDetalle')->listaDql($codigoSoportePagoHorario);        
    }  
    
    private function formularioLista() {
        $form = $this->createFormBuilder()
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))            
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle() {
        $form = $this->createFormBuilder()
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))                        
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))                        
            ->getForm();
        return $form;
    }
    
    private function generarExcel() {
        ob_clean();
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
        for($col = 'A'; $col !== 'U'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }     
        for($col = 'H'; $col !== 'Y'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')                    
                    ->setCellValue('B1', 'DESDE')
                    ->setCellValue('C1', 'HASTA')
                    ->setCellValue('D1', 'IDENTIFICACION')                
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'DEPARTAMENTO')
                    ->setCellValue('G1', 'HORARIO')
                    ->setCellValue('H1', 'DÍAS')
                    ->setCellValue('I1', 'INC')
                    ->setCellValue('J1', 'LIC')
                    ->setCellValue('K1', 'VAC')
                    ->setCellValue('L1', 'DES')                        
                    ->setCellValue('M1', 'H')
                    ->setCellValue('N1', 'HP')
                    ->setCellValue('O1', 'HN')
                    ->setCellValue('P1', 'HDS')
                    ->setCellValue('Q1', 'HD')
                    ->setCellValue('R1', 'HN')
                    ->setCellValue('S1', 'HFD')
                    ->setCellValue('T1', 'HFN')                
                    ->setCellValue('U1', 'HEOD')
                    ->setCellValue('V1', 'HEON')
                    ->setCellValue('W1', 'HEFD')
                    ->setCellValue('X1', 'HEFN');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arSoportesPagoHorarioDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle();
        $arSoportesPagoHorarioDetalles = $query->getResult();

        foreach ($arSoportesPagoHorarioDetalles as $arSoportesPagoHorarioDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSoportesPagoHorarioDetalle->getCodigoSoportePagoHorarioDetallePk())
                    ->setCellValue('B' . $i, $arSoportesPagoHorarioDetalle->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('C' . $i, $arSoportesPagoHorarioDetalle->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arSoportesPagoHorarioDetalle->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arSoportesPagoHorarioDetalle->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arSoportesPagoHorarioDetalle->getEmpleadoRel()->getDepartamentoEmpresaRel()->getNombre())
                    ->setCellValue('G' . $i, $arSoportesPagoHorarioDetalle->getEmpleadoRel()->getHorarioRel()->getNombre())                    
                    ->setCellValue('H' . $i, $arSoportesPagoHorarioDetalle->getDias())
                    ->setCellValue('I' . $i, $arSoportesPagoHorarioDetalle->getIncapacidad())
                    ->setCellValue('J' . $i, $arSoportesPagoHorarioDetalle->getLicencia())
                    ->setCellValue('K' . $i, $arSoportesPagoHorarioDetalle->getVacacion())
                    ->setCellValue('L' . $i, $arSoportesPagoHorarioDetalle->getDescanso())
                    ->setCellValue('M' . $i, $arSoportesPagoHorarioDetalle->getHoras())
                    ->setCellValue('N' . $i, $arSoportesPagoHorarioDetalle->getHorasPermiso())
                    ->setCellValue('O' . $i, $arSoportesPagoHorarioDetalle->getHorasNovedad())
                    ->setCellValue('P' . $i, $arSoportesPagoHorarioDetalle->getHorasDescanso())
                    ->setCellValue('Q' . $i, $arSoportesPagoHorarioDetalle->getHorasDiurnas())
                    ->setCellValue('R' . $i, $arSoportesPagoHorarioDetalle->getHorasNocturnas())
                    ->setCellValue('S' . $i, $arSoportesPagoHorarioDetalle->getHorasFestivasDiurnas())
                    ->setCellValue('T' . $i, $arSoportesPagoHorarioDetalle->getHorasFestivasNocturnas())                    
                    ->setCellValue('U' . $i, $arSoportesPagoHorarioDetalle->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('V' . $i, $arSoportesPagoHorarioDetalle->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('W' . $i, $arSoportesPagoHorarioDetalle->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('X' . $i, $arSoportesPagoHorarioDetalle->getHorasExtrasFestivasNocturnas());

            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('SoportePagoHorario');        
        
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SoportePagoHorario.xlsx"');
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