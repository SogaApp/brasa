<?php
namespace Brasa\TurnoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ServiciosDetallesController extends Controller
{
    var $strListaDql = "";
    var $codigoServicio = "";
    var $codigoCliente = "";
    
    /**
     * @Route("/tur/consulta/servicios/detalles", name="brs_tur_consulta_servicios_detalles")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 43)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        if ($form->isValid()) {    
            $arrControles = $request->request->All();          
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();                
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcel();
            }
            if ($form->get('BtnExcelResumido')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcelResumido();
            }            
        }
        $arServiciosDetalles = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaTurnoBundle:Consultas/Servicio:detalle.html.twig', array(
            'arServiciosDetalles' => $arServiciosDetalles,
            'form' => $form->createView()));
    }
            
    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();        
        $strFechaHasta = "";                
        $strFechaHasta = $session->get('filtroServicioDetalleFechaHasta');                         
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->listaConsultaDql(
                $this->codigoServicio, 
                $session->get('filtroCodigoCliente'),
                $session->get('filtroServicioDetalleEstadoCerrado'),
                $strFechaHasta                
                );
    }

    private function filtrar ($form) {   
        $session = new session;
        $this->codigoServicio = $form->get('TxtCodigo')->getData();
        $session->set('filtroNit', $form->get('TxtNit')->getData());
        $session->set('filtroServicioDetalleEstadoCerrado', $form->get('estadoCerrado')->getData());        
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroServicioDetalleFechaHasta', $dateFechaHasta->format('Y/m/d'));        
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        }        
        $dateFecha = new \DateTime('now');
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroServicioDetalleFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroServicioDetalleFechaHasta');
        }    
        $dateFechaHasta = date_create($strFechaHasta);        
        $form = $this->createFormBuilder()
            ->add('TxtNit', TextType::class, array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', TextType::class, array('label'  => 'NombreCliente','data' => $strNombreCliente))                                
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo','data' => $this->codigoServicio))
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta)) 
            ->add('estadoCerrado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'CERRADO', '0' => 'SIN CERRAR'), 'data' => $session->get('filtroServicioDetalleEstadoCerrado')))                                                
            ->add('BtnExcelResumido', SubmitType::class, array('label'  => 'Excel resumido',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    

    private function generarExcelResumido() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        //$objPHPExcel->getActiveSheet()->get
        for($col = 'A'; $col !== 'AE'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }     
        for($col = 'M'; $col !== 'Q'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');
        }        
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CLIENTE')
                    ->setCellValue('B1', 'GRUPO')
                    ->setCellValue('C1', 'PUESTO')
                    ->setCellValue('D1', 'SERVICIO')
                    ->setCellValue('E1', 'MODALIDAD')                                    
                    ->setCellValue('F1', 'DESDE')
                    ->setCellValue('G1', 'HASTA')
                    ->setCellValue('H1', 'ZONA')
                    ->setCellValue('I1', 'CANT')
                    ->setCellValue('J1', 'H')
                    ->setCellValue('K1', 'HD')
                    ->setCellValue('L1', 'HN')
                    ->setCellValue('M1', 'SUBTOTAL')
                    ->setCellValue('N1', 'BASE AIU')
                    ->setCellValue('O1', 'IVA')
                    ->setCellValue('P1', 'TOTAL');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arServiciosDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
        $arServiciosDetalles = $query->getResult();
        foreach ($arServiciosDetalles as $arServicioDetalle) {   
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arServicioDetalle->getServicioRel()->getClienteRel()->getNombreCorto())                                        
                    ->setCellValue('D' . $i, $arServicioDetalle->getConceptoServicioRel()->getNombreFacturacion())
                    ->setCellValue('E' . $i, $arServicioDetalle->getModalidadServicioRel()->getNombre())                    
                    ->setCellValue('F' . $i, $arServicioDetalle->getFechaDesde()->format('Y/m/d'))                                        
                    ->setCellValue('I' . $i, $arServicioDetalle->getCantidad())                    
                    ->setCellValue('J' . $i, $arServicioDetalle->getHoras())
                    ->setCellValue('K' . $i, $arServicioDetalle->getHorasDiurnas())
                    ->setCellValue('L' . $i, $arServicioDetalle->getHorasNocturnas())                    
                    ->setCellValue('M' . $i, $arServicioDetalle->getVrSubtotal())
                    ->setCellValue('N' . $i, $arServicioDetalle->getVrBaseAiu())
                    ->setCellValue('O' . $i, $arServicioDetalle->getVrIva())
                    ->setCellValue('P' . $i, $arServicioDetalle->getVrTotalDetalle());
            if($arServicioDetalle->getEstadoCerrado() == 1) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $i, $arServicioDetalle->getFechaHasta()->format('Y/m/d'));
            }
            if($arServicioDetalle->getGrupoFacturacionRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B' . $i, $arServicioDetalle->getGrupoFacturacionRel()->getNombre());
            }            

            if($arServicioDetalle->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C' . $i, $arServicioDetalle->getPuestoRel()->getNombre());
                if($arServicioDetalle->getPuestoRel()->getZonaRel()) {
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('H' . $i, $arServicioDetalle->getPuestoRel()->getZonaRel()->getNombre());
                }                
            }                 
                       
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('ServiciosDetalles');
        $objPHPExcel->setActiveSheetIndex(0);
                   
        $objPHPExcel->createSheet(1)->setTitle('Otros')
                ->setCellValue('A1', 'CLIENTE')
                ->setCellValue('B1', 'CONCEPTO')
                ->setCellValue('C1', 'CANT')
                ->setCellValue('D1', 'PRECIO')
                ->setCellValue('E1', 'SUBTOTAL')
                ->setCellValue('F1', 'IVA')
                ->setCellValue('G1', 'TOTAL');
            $objPHPExcel->setActiveSheetIndex(1); 
            
            //$objPHPExcel->getActiveSheet()->get
            for($col = 'A'; $col !== 'H'; $col++) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
            }     
            for($col = 'C'; $col !== 'H'; $col++) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
                $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');
            } 
        
            $objPHPExcel->getActiveSheet()->setTitle('Otros');                                     
            $i = 2;
            $query = $em->createQuery($em->getRepository('BrasaTurnoBundle:TurServicioDetalleConcepto')->listaDql());
            $arServiciosDetallesConceptos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto();
            $arServiciosDetallesConceptos = $query->getResult();
            foreach ($arServiciosDetallesConceptos as $arServicioDetalleConcepto) {   
                $objPHPExcel->setActiveSheetIndex(1)
                        ->setCellValue('A' . $i, $arServicioDetalleConcepto->getServicioRel()->getClienteRel()->getNombreCorto())                                        
                        ->setCellValue('B' . $i, $arServicioDetalleConcepto->getConceptoServicioRel()->getNombre())                                        
                        ->setCellValue('C' . $i, $arServicioDetalleConcepto->getCantidad())
                        ->setCellValue('D' . $i, $arServicioDetalleConcepto->getPrecio())                    
                        ->setCellValue('E' . $i, $arServicioDetalleConcepto->getSubtotal())
                        ->setCellValue('F' . $i, $arServicioDetalleConcepto->getIva()) 
                        ->setCellValue('G' . $i, $arServicioDetalleConcepto->getTotal());               
                $i++;
            }
   
        $objPHPExcel->setActiveSheetIndex(0);
        
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ServiciosDetalles.xlsx"');
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

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'AE'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }     
        for($col = 'Y'; $col !== 'AC'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');
        }        
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'CLIENTE')
                    ->setCellValue('C1', 'SECTOR')             
                    ->setCellValue('D1', 'PUESTO')
                    ->setCellValue('E1', 'SERVICIO')
                    ->setCellValue('F1', 'MODALIDAD')
                    ->setCellValue('G1', 'PERIODO')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'HASTA')                
                    ->setCellValue('J1', 'PLANTILLA')
                    ->setCellValue('K1', 'FECHA.P')
                    ->setCellValue('L1', 'CANT')
                    ->setCellValue('M1', 'CANT.R')
                    ->setCellValue('N1', 'LU')
                    ->setCellValue('O1', 'MA')
                    ->setCellValue('P1', 'MI')
                    ->setCellValue('Q1', 'JU')
                    ->setCellValue('R1', 'VI')
                    ->setCellValue('S1', 'SA')
                    ->setCellValue('T1', 'DO')
                    ->setCellValue('U1', 'FE')
                    ->setCellValue('V1', 'H')
                    ->setCellValue('W1', 'H.D')
                    ->setCellValue('X1', 'H.N')
                    ->setCellValue('Y1', 'DIAS')
                    ->setCellValue('Z1', 'VR.MINIMO')
                    ->setCellValue('AA1', 'VR.AJUSTE')
                    ->setCellValue('AB1', 'VALOR')
                    ->setCellValue('AC1', 'M')
                    ->setCellValue('AD1', 'A');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arServiciosDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
        $arServiciosDetalles = $query->getResult();

        foreach ($arServiciosDetalles as $arServicioDetalle) {   
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arServicioDetalle->getCodigoServicioDetallePk())
                    ->setCellValue('B' . $i, $arServicioDetalle->getServicioRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arServicioDetalle->getServicioRel()->getSectorRel()->getNombre())                    
                    ->setCellValue('E' . $i, $arServicioDetalle->getConceptoServicioRel()->getNombre())
                    ->setCellValue('F' . $i, $arServicioDetalle->getModalidadServicioRel()->getNombre())
                    ->setCellValue('G' . $i, $arServicioDetalle->getPeriodoRel()->getNombre())
                    ->setCellValue('H' . $i, $arServicioDetalle->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $arServicioDetalle->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('K' . $i, $arServicioDetalle->getFechaIniciaPlantilla()->format('Y/m/d'))
                    ->setCellValue('L' . $i, $arServicioDetalle->getCantidad())
                    ->setCellValue('M' . $i, $arServicioDetalle->getCantidadRecurso())
                    ->setCellValue('N' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getLunes()))
                    ->setCellValue('O' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getMartes()))
                    ->setCellValue('P' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getMiercoles()))
                    ->setCellValue('Q' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getJueves()))
                    ->setCellValue('R' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getViernes()))
                    ->setCellValue('S' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getSabado()))
                    ->setCellValue('T' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getDomingo()))
                    ->setCellValue('U' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getFestivo()))
                    ->setCellValue('V' . $i, $arServicioDetalle->getHoras())
                    ->setCellValue('W' . $i, $arServicioDetalle->getHorasDiurnas())
                    ->setCellValue('X' . $i, $arServicioDetalle->getHorasNocturnas())
                    ->setCellValue('Y' . $i, $arServicioDetalle->getDias())
                    ->setCellValue('Z' . $i, $arServicioDetalle->getVrPrecioMinimo())
                    ->setCellValue('AA' . $i, $arServicioDetalle->getVrPrecioAjustado())
                    ->setCellValue('AB' . $i, $arServicioDetalle->getVrTotalDetalle())
                    ->setCellValue('AC' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getMarca()))
                    ->setCellValue('AD' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getAjusteProgramacion()));
            
            if($arServicioDetalle->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arServicioDetalle->getPuestoRel()->getNombre());
            }
            if($arServicioDetalle->getPlantillaRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('J' . $i, $arServicioDetalle->getPlantillaRel()->getNombre());
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ServiciosDetalles');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ServiciosDetalles.xlsx"');
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