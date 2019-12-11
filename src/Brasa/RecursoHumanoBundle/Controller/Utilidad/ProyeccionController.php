<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class ProyeccionController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/utilidades/proyeccion", name="brs_rhu_utilidades_proyeccion")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 80)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->generarExcel();
            }
            if($form->get('BtnGenerar')->isClicked()) {                                 
                $fechaHasta = $form->get('fechaHasta')->getData();
                if($fechaHasta != null) {
                    set_time_limit(0);
                    ini_set("memory_limit", -1);                    
                    $strSql = "DELETE FROM rhu_proyeccion WHERE 1";
                    $em->getConnection()->executeQuery($strSql); 
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $arParametrosPrestacionPrima = new \Brasa\RecursoHumanoBundle\Entity\RhuParametroPrestacion();
                    $arParametrosPrestacionPrima = $em->getRepository('BrasaRecursoHumanoBundle:RhuParametroPrestacion')->findBy(array('tipo' => 'PRI'));                                                    
                    $arParametrosPrestacionCesantia = new \Brasa\RecursoHumanoBundle\Entity\RhuParametroPrestacion();
                    $arParametrosPrestacionCesantia = $em->getRepository('BrasaRecursoHumanoBundle:RhuParametroPrestacion')->findBy(array('tipo' => 'CES'));                                                    
                    $douAuxilioTransporte = $arConfiguracion->getVrAuxilioTransporte();                    
                    $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();                    
                    $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('estadoActivo' => 1));                    
                    foreach($arContratos as $arContrato) {
                        $douSalario = $arContrato->getVrSalarioPago();
                        $auxilioTransporte = $arConfiguracion->getVrAuxilioTransporte();
                        $dateFechaHasta = $fechaHasta;
                        $arProyeccion = new \Brasa\RecursoHumanoBundle\Entity\RhuProyeccion();
                        $arProyeccion->setContratoRel($arContrato);
                        $arProyeccion->setEmpleadoRel($arContrato->getEmpleadoRel());
                        $arProyeccion->setVrSalario($arContrato->getVrSalario());                        
                        $arProyeccion->setFechaHasta($fechaHasta);
                        
                        //Cesantias
                        if($arContrato->getSalarioIntegral() == 0) {
                            $dateFechaDesde = $arContrato->getFechaUltimoPagoCesantias();   
                            $dateFechaHastaCesantias = $arContrato->getFechaUltimoPago();
                            $ibpCesantiasInicial = $arContrato->getIbpCesantiasInicial();                            
                            $ibpCesantias = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->ibp($dateFechaDesde->format('Y-m-d'), $dateFechaHastaCesantias->format('Y-m-d'), $arContrato->getCodigoContratoPk());                
                            $ibpCesantias += $ibpCesantiasInicial;                  
                            $intDiasCesantias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestaciones($dateFechaDesde, $dateFechaHasta);                                                                 
                            $intDiasCesantiasSalarioPromedio = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestaciones($dateFechaDesde, $dateFechaHastaCesantias);                                                                 
                            $intDiasAusentismo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->diasAusentismo($dateFechaDesde->format('Y-m-d'), $dateFechaHastaCesantias->format('Y-m-d'), $arContrato->getCodigoContratoPk());                                                    
                            $salarioPromedioCesantias = 0;
                            if($arContrato->getCodigoSalarioTipoFk() == 2) {
                                if($intDiasCesantiasSalarioPromedio > 0) {
                                    $salarioPromedioCesantias = ($ibpCesantias / $intDiasCesantiasSalarioPromedio) * 30;                                                                    
                                } else {
                                    if($arContrato->getEmpleadoRel()->getAuxilioTransporte() == 1) {
                                        $salarioPromedioCesantias = $douSalario + $auxilioTransporte;
                                    } else {
                                        $salarioPromedioCesantias = $douSalario;
                                    }                                  
                                }                            
                            } else {                                        
                                if($arContrato->getEmpleadoRel()->getAuxilioTransporte() == 1) {
                                    $salarioPromedioCesantias = $douSalario + $auxilioTransporte;
                                } else {
                                    $salarioPromedioCesantias = $douSalario;
                                }                                            
                            }        
                            if($arConfiguracion->getPrestacionesAplicaPorcentajeSalario()) {                            
                                if($arContrato->getCodigoSalarioTipoFk() == 2) {            
                                    $intDiasLaborados = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestaciones($arContrato->getFechaDesde(), $dateFechaHasta);                                                                    
                                    foreach ($arParametrosPrestacionCesantia as $arParametroPrestacion) {
                                        if($intDiasLaborados >= $arParametroPrestacion->getDiaDesde() && $intDiasLaborados <= $arParametroPrestacion->getDiaHasta()) {
                                            if($arParametroPrestacion->getOrigen() == 'SAL') {
                                                if($arContrato->getEmpleadoRel()->getAuxilioTransporte() == 1) {
                                                    $salarioPromedioCesantias = $douSalario + $auxilioTransporte;
                                                } else {
                                                    $salarioPromedioCesantias = $douSalario;
                                                } 
                                            } else {
                                                $porcentaje = $arParametroPrestacion->getPorcentaje();
                                                $salarioPromedioCesantias = ($salarioPromedioCesantias * $porcentaje)/100;                                
                                            }                                            
                                        }
                                    }                              
                                }                                                        
                            }                        
                            $intDiasCesantias -= $intDiasAusentismo;
                            $douCesantias = ($salarioPromedioCesantias * $intDiasCesantias) / 360;          
                            $floPorcentajeIntereses = (($intDiasCesantias * 12) / 360)/100;
                            $douInteresesCesantias = $douCesantias * $floPorcentajeIntereses;                                                                                                                        
                            $arProyeccion->setDiasCesantias($intDiasCesantias);
                            $arProyeccion->setVrCesantias($douCesantias);
                            $arProyeccion->setVrSalarioPromedioCesantias($salarioPromedioCesantias);
                            $arProyeccion->setVrInteresesCesantias($douInteresesCesantias);
                            $arProyeccion->setFechaDesdeCesantias($dateFechaDesde);
                            $arProyeccion->setDiasAusentismo($intDiasAusentismo);                            
                        } else {
                            $arProyeccion->setFechaDesdeCesantias($dateFechaHasta);
                        }
                        
                        //Primas  
                        if($arContrato->getSalarioIntegral() == 0) {
                            $dateFechaDesde = $arContrato->getFechaUltimoPagoPrimas();                        
                            $dateFechaHastaPrimas = $arContrato->getFechaUltimoPago();
                            $intDiasPrima = 0;                                        
                            $intDiasPrima = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestaciones($dateFechaDesde, $dateFechaHasta);    
                            $intDiasPrimaSalarioPromedio = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestaciones($dateFechaDesde, $dateFechaHastaPrimas);                            
                            $intDiasPrimaLiquidar = $intDiasPrima;
                            if($dateFechaDesde->format('m-d') == '06-30' || $dateFechaDesde->format('m-d') == '12-30') {
                                $intDiasPrimaLiquidar -= 1;
                                $intDiasPrimaSalarioPromedio -= 1;
                            }
                            $ibpPrimasInicial = $arContrato->getIbpPrimasInicial();                    
                            $ibpPrimasInicial = round($ibpPrimasInicial);
                            $ibpPrimas = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->ibp($dateFechaDesde->format('Y-m-d'), $dateFechaHastaPrimas->format('Y-m-d'), $arContrato->getCodigoContratoPk());                
                            $ibpPrimas += $ibpPrimasInicial;                                            
                            $ibpPrimas = round($ibpPrimas);
                            $salarioPromedioPrimas = 0;
                            if($arContrato->getCodigoSalarioTipoFk() == 2) {
                                if($intDiasPrimaSalarioPromedio > 0) {
                                    $salarioPromedioPrimas = ($ibpPrimas / $intDiasPrimaSalarioPromedio) * 30;   
                                } else {
                                    if($arContrato->getEmpleadoRel()->getAuxilioTransporte() == 1) {
                                        $salarioPromedioPrimas = $douSalario + $auxilioTransporte;
                                    } else {
                                        $salarioPromedioPrimas = $douSalario;
                                    }                                
                                }                                                         
                            } else {
                                if($arContrato->getEmpleadoRel()->getAuxilioTransporte() == 1) {
                                    $salarioPromedioPrimas = $douSalario + $auxilioTransporte;
                                } else {
                                    $salarioPromedioPrimas = $douSalario;
                                }                                                
                            }                     
                            if($arConfiguracion->getPrestacionesAplicaPorcentajeSalario()) {                            
                                if($arContrato->getCodigoSalarioTipoFk() == 2) {  
                                    $intDiasLaborados = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestaciones($arContrato->getFechaDesde(), $dateFechaHasta);                                                                    
                                    foreach ($arParametrosPrestacionPrima as $arParametroPrestacion) {
                                        if($intDiasLaborados >= $arParametroPrestacion->getDiaDesde() && $intDiasLaborados <= $arParametroPrestacion->getDiaHasta()) {
                                            if($arParametroPrestacion->getOrigen() == 'SAL') {
                                                if($arContrato->getEmpleadoRel()->getAuxilioTransporte() == 1) {
                                                    $salarioPromedioPrimas = $douSalario + $auxilioTransporte;
                                                } else {
                                                    $salarioPromedioPrimas = $douSalario;
                                                } 
                                            } else {
                                                $porcentaje = $arParametroPrestacion->getPorcentaje();
                                                $salarioPromedioPrimas = ($salarioPromedioPrimas * $porcentaje)/100;                                
                                            }                                            
                                        }
                                    }                                               
                                }                                                        
                            }                        
                            $salarioPromedioPrimas = round($salarioPromedioPrimas);
                            $douPrima = ($salarioPromedioPrimas * $intDiasPrimaLiquidar) / 360;                
                            $douPrima = round($douPrima);         
                            $arProyeccion->setVrSalarioPromedioPrimas($salarioPromedioPrimas);
                            $arProyeccion->setVrPrimas($douPrima);
                            $arProyeccion->setDiasPrima($intDiasPrimaLiquidar);
                            $arProyeccion->setFechaDesdePrima($dateFechaDesde);                             
                        } else {
                            $arProyeccion->setFechaDesdePrima($dateFechaHasta);                                               
                        }
                        
                        $em->persist($arProyeccion);
                    }         
                    $em->flush();                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_utilidades_proyeccion'));           
            }            
            
            /*if($form->get('BtnPDF')->isClicked()) {
                $this->filtrarLista($form);
                $this->listarCostosGeneral();
                $objReporteCostos = new \Brasa\RecursoHumanoBundle\Reportes\ReporteCostos();
                $objReporteCostos->Generar($this, $this->strDqlLista);
            }*/            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }

        }
        $arProyecciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Proyeccion:lista.html.twig', array(
            'arProyecciones' => $arProyecciones,
            'form' => $form->createView()
            ));
    }        
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProyeccion')->listaDql(                                        
                    "",
                    "",
                    "",
                    ""
                    );
    }    

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $dateFecha = new \DateTime('now');
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroHasta') != "") {
            $strFechaHasta = $session->get('filtroHasta');
        }            
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()            
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }        

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');        
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());        
        $dateFechaHasta = $form->get('fechaHasta')->getData();        
        $session->set('filtroHasta', $dateFechaHasta->format('Y/m/d'));
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
        for($col = 'A'; $col !== 'R'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        }   
        for($col = 'G'; $col !== 'J'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        } 
        for($col = 'L'; $col !== 'O'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'DOCUMENTO')
                    ->setCellValue('B1', 'EMPLEADO')
                    ->setCellValue('C1', 'CONTRATO')
                    ->setCellValue('D1', 'GRUPO PAGO')
                    ->setCellValue('E1', 'SALARIO')
                    ->setCellValue('F1', 'HASTA')                    
                    ->setCellValue('G1', 'VACACIONES')
                    ->setCellValue('H1', 'S_PROMEDIO')    
                    ->setCellValue('I1', 'PRIMAS')
                    ->setCellValue('J1', 'DIAS')
                    ->setCellValue('K1', 'U.PAGO')
                    ->setCellValue('L1', 'S_PROMEDIO')    
                    ->setCellValue('M1', 'CESANTIAS')
                    ->setCellValue('N1', 'INTERESES')
                    ->setCellValue('O1', 'DIAS')
                    ->setCellValue('P1', 'U.PAGO')
                    ->setCellValue('Q1', 'D_AUS');
        
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arProyecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuProyeccion();
        $arProyecciones = $query->getResult();
        foreach ($arProyecciones as $arProyeccion) {
            $objPHPExcel->setActiveSheetIndex(0)                    
                    ->setCellValue('A' . $i, $arProyeccion->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('B' . $i, $arProyeccion->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arProyeccion->getCodigoContratoFk())
                    ->setCellValue('D' . $i, $arProyeccion->getContratoRel()->getCentroCostoRel()->getNombre())                                        
                    ->setCellValue('E' . $i, $arProyeccion->getVrSalario())
                    ->setCellValue('F' . $i, $arProyeccion->getFechaHasta()->Format('Y-m-d'))                    
                    ->setCellValue('G' . $i, $arProyeccion->getVrVacaciones())
                    ->setCellValue('H' . $i, $arProyeccion->getVrSalarioPromedioPrimas())                    
                    ->setCellValue('I' . $i, $arProyeccion->getVrPrimas())
                    ->setCellValue('J' . $i, $arProyeccion->getDiasPrima())
                    ->setCellValue('K' . $i, $arProyeccion->getFechaDesdePrima()->Format('Y-m-d'))                                        
                    ->setCellValue('L' . $i, $arProyeccion->getVrSalarioPromedioCesantias())                    
                    ->setCellValue('M' . $i, $arProyeccion->getVrCesantias())
                    ->setCellValue('N' . $i, $arProyeccion->getVrInteresesCesantias())
                    ->setCellValue('O' . $i, $arProyeccion->getDiasCesantias())
                    ->setCellValue('P' . $i, $arProyeccion->getFechaDesdeCesantias()->Format('Y-m-d'))
                    ->setCellValue('Q' . $i, $arProyeccion->getDiasAusentismo());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Proyeccion');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Proyeccion.xlsx"');
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
