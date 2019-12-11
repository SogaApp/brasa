<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class InformacionDaneController extends Controller
{
    /**
     * @Route("/rhu/utilidades/informaciondane/informe", name="brs_rhu_utilidades_informacion_dane_informe")
     */
    public function InformeAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 79)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $fechaActual = date('Y-m-j');
        $anioActual = date('Y');
        $fechaPrimeraAnterior = strtotime ( '-1 year' , strtotime ( $fechaActual ) ) ;
        $fechaPrimeraAnterior = date ( 'Y' , $fechaPrimeraAnterior );
        $fechaSegundaAnterior = strtotime ( '-2 year' , strtotime ( $fechaActual ) ) ;
        $fechaSegundaAnterior = date ( 'Y' , $fechaSegundaAnterior );
        $fechaTerceraAnterior = strtotime ( '-3 year' , strtotime ( $fechaActual ) ) ;
        $fechaTerceraAnterior = date ( 'Y' , $fechaTerceraAnterior );
        $form = $this->createFormBuilder()
            ->add('BtnGenerarArchivo', 'submit', array('label'  => 'Generar archivo',))
            ->add('fechaProceso', 'choice', array('choices' => array($anioActual = date('Y') => $anioActual = date('Y'),$fechaPrimeraAnterior => $fechaPrimeraAnterior, $fechaSegundaAnterior => $fechaSegundaAnterior, $fechaTerceraAnterior => $fechaTerceraAnterior),))
            ->add('formatos', 'choice', array('choices' => array('mts' => 'Muestra trimestral de servicios MTS')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $controles = $request->request->get('form');
            if($form->get('BtnGenerarArchivo')->isClicked()) {
                $objPHPExcel = new \PHPExcel();
                // Set document properties
                $intContratoObraoLabor = 0;
                $intContratoFijo = 0;
                $intContratoIndefinido = 0;
                $intContratoAprendiz = 0;
                $intContratoPracticante = 0;
                $intContratoObraoLaborBogota = 0;
                $intContratoFijoBogota = 0;
                $intContratoIndefinidoBogota = 0;
                $intContratoAprendizBogota = 0;
                $intContratoPracticanteBogota = 0;
                //TRIMESTRAL
                if ($controles['fechaDesde'] <> null || $controles['fechaHasta'] <> null){
                    $empleadosContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->createQueryBuilder('c')
                            ->where('c.codigoContratoPk <> 0')
                            ->andWhere('c.fechaDesde >= :fechaDesde')
                            ->andWhere('c.fechaDesde <= :fechaHasta')
                            ->setParameter('fechaDesde', $controles['fechaDesde'])
                            ->setParameter('fechaHasta', $controles['fechaHasta'])
                            ->getQuery()
                            ->getResult();
                    //devengado y prestaciones pagadas
                    $salariosEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->devuelveCostosDane($controles['fechaDesde'],$controles['fechaHasta'],"");
                    //parafiscales
                    $parafiscalesSsoEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->devuelveCostosParafiscales($controles['fechaDesde'],$controles['fechaHasta'],"");
                    //prestaciones liquidadas
                    $prestacionesLiquidadasEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->devuelveCostosPrestacionesDane($controles['fechaDesde'],$controles['fechaHasta'],"");
                } else { //ANUAL
                    $empleadosContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->createQueryBuilder('c')
                            ->where('c.codigoContratoPk <> 0')
                            ->andWhere('c.fechaDesde LIKE :fechaDesde')
                            ->andWhere('c.fechaHasta LIKE :fechaHasta')
                            ->setParameter('fechaDesde', '%'.$controles['fechaProceso'].'%')
                            ->setParameter('fechaHasta', '%'.$controles['fechaProceso'].'%')
                            ->getQuery()
                            ->getResult();
                    //devengado
                    $salariosEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->devuelveCostosDane("","",$controles['fechaProceso']);
                    //parafiscales
                    $parafiscalesSsoEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->devuelveCostosParafiscales("","",$controles['fechaProceso']);
                    //prestaciones liquidadas
                    $prestacionesLiquidadasEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->devuelveCostosPrestacionesDane("","",$controles['fechaProceso']);
                }
                    foreach ($empleadosContratos as $empleadosContrato) {
                        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto;
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($empleadosContrato->getCodigoCentroCostoFk());
                            if ($empleadosContrato->getCodigoContratoTipoFk() == 1){
                                $intContratoObraoLabor++;
                                if ($arCentroCosto->getCodigoCiudadFk() == 2387){
                                   $intContratoObraoLaborBogota++;
                                }
                            }
                            if ($empleadosContrato->getCodigoContratoTipoFk() == 2){
                                $intContratoFijo++;
                                if ($arCentroCosto->getCodigoCiudadFk() == 2387){
                                   $intContratoFijoBogota++;
                                }
                            }
                            if ($empleadosContrato->getCodigoContratoTipoFk() == 3){
                                $intContratoIndefinido++;
                                if ($arCentroCosto->getCodigoCiudadFk() == 2387){
                                   $intContratoIndefinidoBogota++;
                                }
                            }
                            if ($empleadosContrato->getCodigoContratoTipoFk() == 4){
                                $intContratoAprendiz++;
                                if ($arCentroCosto->getCodigoCiudadFk() == 2387){
                                   $intContratoAprendizBogota++;
                                }
                            }
                            if ($empleadosContrato->getCodigoContratoTipoFk() == 5){
                                $intContratoPracticante++;
                                if ($arCentroCosto->getCodigoCiudadFk() == 2387){
                                   $intContratoPracticanteBogota++;
                                }
                            }
                    }
                    //COSTOS Y GASTOS CAUSADOS
                    //SALARIOS Y DEVENGADO
                    $salarioEmpleadoObraLabor = 0;
                    $salarioEmpleadoFijo = 0;
                    $salarioEmpleadoIndefinido = 0;
                    $salarioEmpleadoAprendiz = 0;
                    $salarioEmpleadoPracticante = 0;
                    $salarioEmpleadoObraLaborBogota = 0;
                    $salarioEmpleadoFijoBogota = 0;
                    $salarioEmpleadoIndefinidoBogota = 0;
                    $salarioEmpleadoAprendizBogota = 0;
                    $salarioEmpleadoPracticanteBogota = 0;
                    foreach ($salariosEmpleados as $salariosEmpleado) {
                        if ($salariosEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 1){
                            $salarioEmpleadoObraLabor = $salarioEmpleadoObraLabor +  $salariosEmpleado->getVrDevengado();
                        }
                        if ($salariosEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 2){
                            $salarioEmpleadoFijo = $salarioEmpleadoFijo +  $salariosEmpleado->getVrDevengado();
                        }
                        if ($salariosEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 3){
                            $salarioEmpleadoIndefinido = $salarioEmpleadoIndefinido +  $salariosEmpleado->getVrDevengado();
                        }
                        if ($salariosEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 4){
                            $salarioEmpleadoAprendiz = $salarioEmpleadoAprendiz +  $salariosEmpleado->getVrDevengado();
                        }
                        if ($salariosEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 5){
                            $salarioEmpleadoPracticante = $salarioEmpleadoPracticante +  $salariosEmpleado->getVrDevengado();
                        }
                    }
                    //PARAFISCALES Y SEGURIDAD SOCIAL
                    $parafiscalesSsoEmpleadoObraLabor = 0;
                    $parafiscalesSsoEmpleadoFijo = 0;
                    $parafiscalesSsoEmpleadoIndefinido = 0;
                    $parafiscalesSsoEmpleadoAprendiz = 0;
                    $parafiscalesSsoEmpleadoPracticante = 0;
                    foreach ($parafiscalesSsoEmpleados as $parafiscalesSsoEmpleado) {
                        if ($parafiscalesSsoEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 1){
                            $parafiscalesSsoEmpleadoObraLabor = $parafiscalesSsoEmpleadoObraLabor + $parafiscalesSsoEmpleado->getCotizacionCaja() + $parafiscalesSsoEmpleado->getCotizacionSena() + $parafiscalesSsoEmpleado->getCotizacionIcbf() + $parafiscalesSsoEmpleado->getCotizacionPension() + $parafiscalesSsoEmpleado->getCotizacionSalud();
                        }
                        if ($parafiscalesSsoEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 2){
                            $parafiscalesSsoEmpleadoFijo = $parafiscalesSsoEmpleadoFijo + $parafiscalesSsoEmpleado->getCotizacionCaja() + $parafiscalesSsoEmpleado->getCotizacionSena() + $parafiscalesSsoEmpleado->getCotizacionIcbf() + $parafiscalesSsoEmpleado->getCotizacionPension() + $parafiscalesSsoEmpleado->getCotizacionSalud();
                        }
                        if ($parafiscalesSsoEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 3){
                            $parafiscalesSsoEmpleadoIndefinido = $parafiscalesSsoEmpleadoIndefinido + $parafiscalesSsoEmpleado->getCotizacionCaja() + $parafiscalesSsoEmpleado->getCotizacionSena() + $parafiscalesSsoEmpleado->getCotizacionIcbf() + $parafiscalesSsoEmpleado->getCotizacionPension() + $parafiscalesSsoEmpleado->getCotizacionSalud();
                        }
                        if ($parafiscalesSsoEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 4){
                            $parafiscalesSsoEmpleadoAprendiz = $parafiscalesSsoEmpleadoAprendiz + $parafiscalesSsoEmpleado->getCotizacionCaja() + $parafiscalesSsoEmpleado->getCotizacionSena() + $parafiscalesSsoEmpleado->getCotizacionIcbf() + $parafiscalesSsoEmpleado->getCotizacionPension() + $parafiscalesSsoEmpleado->getCotizacionSalud();
                        }
                        if ($parafiscalesSsoEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 5){
                            $parafiscalesSsoEmpleadoPracticante = $parafiscalesSsoEmpleadoPracticante + $parafiscalesSsoEmpleado->getCotizacionCaja() + $parafiscalesSsoEmpleado->getCotizacionSena() + $parafiscalesSsoEmpleado->getCotizacionIcbf() + $parafiscalesSsoEmpleado->getCotizacionPension() + $parafiscalesSsoEmpleado->getCotizacionSalud();
                        }
                    }
                    $prestacionesLiquidadasEmpleadosObraLabor = 0;
                    $prestacionesLiquidadasEmpleadosFijo = 0;
                    $prestacionesLiquidadasEmpleadosIndefinido = 0;
                    foreach ($prestacionesLiquidadasEmpleados as $prestacionesLiquidadasEmpleado) {
                        if ($prestacionesLiquidadasEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 1){
                            $prestacionesLiquidadasEmpleadosObraLabor = $prestacionesLiquidadasEmpleadosObraLabor + $prestacionesLiquidadasEmpleado->getVrCesantias() + $prestacionesLiquidadasEmpleado->getVrInteresesCesantias() + $prestacionesLiquidadasEmpleado->getVrPrima() + $prestacionesLiquidadasEmpleado->getVrVacaciones();
                        }
                        if ($prestacionesLiquidadasEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 2){
                            $prestacionesLiquidadasEmpleadosFijo = $prestacionesLiquidadasEmpleadosFijo + $prestacionesLiquidadasEmpleado->getVrCesantias() + $prestacionesLiquidadasEmpleado->getVrInteresesCesantias() + $prestacionesLiquidadasEmpleado->getVrPrima() + $prestacionesLiquidadasEmpleado->getVrVacaciones();
                        }
                        if ($prestacionesLiquidadasEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 3){
                            $prestacionesLiquidadasEmpleadosIndefinido = $prestacionesLiquidadasEmpleadosIndefinido + $prestacionesLiquidadasEmpleado->getVrCesantias() + $prestacionesLiquidadasEmpleado->getVrInteresesCesantias() + $prestacionesLiquidadasEmpleado->getVrPrima() + $prestacionesLiquidadasEmpleado->getVrVacaciones();
                        }
                    }
                            
                $objPHPExcel->getProperties()->setCreator("EMPRESA")
                    ->setLastModifiedBy("EMPRESA")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");

                $objPHPExcel->getActiveSheet()->mergeCells('A1:J1')->getStyle()->getAlignment()->setHorizontal('center');
                $objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
                $objPHPExcel->getActiveSheet()->mergeCells('A3:J3');
                $objPHPExcel->getActiveSheet()->mergeCells('A4:J4');
                $objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
                $objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
                $objPHPExcel->getActiveSheet()->mergeCells('A7:J7');
                $objPHPExcel->getActiveSheet()->mergeCells('A8:J8')
                        
                    ->setCellValue('A1', 'TIPO CONTRATACION')
                    ->setCellValue('A2', 'Propietarios, socios y familiares (sin remuneracion fija)')
                    ->setCellValue('A3', 'Personal permanente (contrato a termino indefinido)')
                    ->setCellValue('A4', 'Temporal Contratado directamente por la Empresa')
                    ->setCellValue('A5', 'Temporal en Mision en otras empresas (solo para empresas especializadas en suministro de personal')
                    ->setCellValue('A6', 'Temporal suministrado por otras empresas')
                    ->setCellValue('A7', 'Personal Aprendiz o estudiantes por convenio ( Universitario, tecnologo o tecnico)')
                    ->setCellValue('A8', 'TOTAL')
                    ->setCellValue('K1', 'Número de personas promedio trimestre TOTAL NACIONAL')
                    ->setCellValue('K2', 0)
                    ->setCellValue('K3', $intContratoIndefinido)
                    ->setCellValue('K4', $intContratoFijo)
                    ->setCellValue('K5', $intContratoObraoLabor)
                    ->setCellValue('K6', 0)
                    ->setCellValue('K7', $intContratoAprendiz + $intContratoPracticante)
                    ->setCellValue('K8', $intContratoIndefinido + $intContratoObraoLabor + $intContratoFijoBogota + $intContratoAprendiz + $intContratoPracticante)
                    ->setCellValue('L1', 'Número de personas promedio trimestre TOTAL BOGOTA')
                    ->setCellValue('L2', $intContratoFijoBogota)
                    ->setCellValue('L3', $intContratoIndefinidoBogota)
                    ->setCellValue('L4', 0)
                    ->setCellValue('L5', $intContratoObraoLaborBogota)
                    ->setCellValue('L6', 0)
                    ->setCellValue('L7', $intContratoAprendizBogota + $intContratoPracticanteBogota)
                    ->setCellValue('L8', $intContratoIndefinidoBogota + $intContratoObraoLaborBogota + $intContratoFijoBogota + $intContratoAprendizBogota + $intContratoPracticanteBogota);


                $objPHPExcel->getActiveSheet(0)->setTitle('1. PERSONAL OCUPADO');
                $objPHPExcel->setActiveSheetIndex(0);
                
                $objPHPExcel->createSheet(2)->setTitle('2. COSTOS Y GASTOS CAUSADOS')   
                    ->setCellValue('A1', 'CONCEPTO')
                    ->setCellValue('A2', 'Sueldo y salarios del personal permanente (en dinero y en especie, horas extras, dominicales, comisiones por ventas, viaticos permanentes)')
                    ->setCellValue('A3', 'Prestaciones sociales, cotizaciones y aportes personal permanente')
                    ->setCellValue('A4', 'Salarios y prestaciones, cotizaciones  y Aportes del personal temporal contratado directamente por la empresa')
                    ->setCellValue('A5', 'Sueldos y prestaciones del personal temporal en mision (solo para empresas especializadas en suministro de personal)')
                    ->setCellValue('A6', 'Valor causado por el personal contratado a traves de empresas de servicios temporales')
                    ->setCellValue('A7', 'Gastos causados por el personal aprendiz o estudiante por convenio ( universitario, tecnologo o tecnico)')
                    ->setCellValue('A8', 'TOTAL')
                    ->setCellValue('K1', 'TOTAL NACIONAL')
                    ->setCellValue('K2', $salarioEmpleadoIndefinido)
                    ->setCellValue('K3', $prestacionesLiquidadasEmpleadosIndefinido + $parafiscalesSsoEmpleadoIndefinido)
                    ->setCellValue('K4', $salarioEmpleadoFijo + $parafiscalesSsoEmpleadoFijo + $prestacionesLiquidadasEmpleadosFijo)
                    ->setCellValue('K5', $salarioEmpleadoObraLabor + $parafiscalesSsoEmpleadoObraLabor + $prestacionesLiquidadasEmpleadosObraLabor)
                    ->setCellValue('K6', 0)
                    ->setCellValue('K7', $salarioEmpleadoAprendiz + $salarioEmpleadoPracticante + $parafiscalesSsoEmpleadoAprendiz + $parafiscalesSsoEmpleadoPracticante)
                    ->setCellValue('K8', $salarioEmpleadoIndefinido + $prestacionesLiquidadasEmpleadosIndefinido + $parafiscalesSsoEmpleadoIndefinido + $salarioEmpleadoFijo + $parafiscalesSsoEmpleadoFijo + $prestacionesLiquidadasEmpleadosFijo + $salarioEmpleadoObraLabor + $parafiscalesSsoEmpleadoObraLabor + $prestacionesLiquidadasEmpleadosObraLabor + $salarioEmpleadoAprendiz + $salarioEmpleadoPracticante + $parafiscalesSsoEmpleadoAprendiz + $parafiscalesSsoEmpleadoPracticante)
                    ->setCellValue('L1', 'TOTAL BOGOTA')
                    ->setCellValue('L2', 0)
                    ->setCellValue('L3', 0)
                    ->setCellValue('L4', 0)
                    ->setCellValue('L5', 0)
                    ->setCellValue('L6', 0)
                    ->setCellValue('L7', 0)
                    ->setCellValue('L8', 0);


                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="InformeDane.xlsx"');
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

        return $this->render('BrasaRecursoHumanoBundle:Utilidades/InformacionDane:Informe.html.twig', array(
                'form' => $form->createView()
                ));
    }

}
