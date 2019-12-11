<?php
namespace Brasa\TurnoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CostoServicioController extends Controller
{
    var $strListaDql = "";
    var $strListaDetalleDql = "";
    /**
     * @Route("/tur/consulta/costo/servicio", name="brs_tur_consulta_costo_servicio")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 49)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator  = $this->get('knp_paginator');
        $this->filtrarFecha = TRUE;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
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
        }

        $arCostoServicio = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Consultas/Costo:servicio.html.twig', array(
            'arCostoServicio' => $arCostoServicio,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/consulta/costo/servicio/ver/detalle/{codigoCostoServicio}", name="brs_tur_consulta_costo_servicio_ver_detalle")
     */    
    public function verDetalleAction(Request $request, $codigoCostoServicio) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioVerDetalle();
        $form->handleRequest($request);
        $arCostoServicio = new \Brasa\TurnoBundle\Entity\TurCostoServicio();
        $arCostoServicio = $em->getRepository('BrasaTurnoBundle:TurCostoServicio')->find($codigoCostoServicio);
        if ($form->isValid()) {                             

        }
        $dql = $em->getRepository('BrasaTurnoBundle:TurCostoRecursoDetalle')->listaDql("", $arCostoServicio->getAnio(), $arCostoServicio->getMes(), $arCostoServicio->getCodigoPedidoDetalleFk());
        $arCostoRecursoDetalle = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Consultas/Costo:verDetalleServicio.html.twig', array(
            'arCostoRecursoDetalle' => $arCostoRecursoDetalle,                        
            'form' => $form->createView()));
    }    
    
    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurCostoServicio')->listaDql(
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroTurMes') 
                );
        $this->strListaDetalleDql =  $em->getRepository('BrasaTurnoBundle:TurCostoRecursoDetalle')->listaConsultaDql(
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroTurMes') 
                );        
    }

    private function filtrar ($form) {
        $session = new session;
        $session->set('filtroNit', $form->get('TxtNit')->getData());
        $session->set('filtroTurMes', $form->get('TxtMes')->getData());
        //$session->set('filtroCodigoRecurso', $form->get('TxtCodigoRecurso')->getData());
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
        /*$strNombreRecurso = "";
        if($session->get('filtroCodigoRecurso')) {
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($session->get('filtroCodigoRecurso'));
            if($arRecurso) {
                $strNombreRecurso = $arRecurso->getNombreCorto();
            }  else {
                $session->set('filtroCodigoRecurso', null);
            }
        }*/

        $form = $this->createFormBuilder()
            ->add('TxtNit', TextType::class, array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', TextType::class, array('label'  => 'NombreCliente','data' => $strNombreCliente))
            //->add('TxtCodigoRecurso', 'text', array('label'  => 'Nit','data' => $session->get('filtroCodigoRecurso')))
            ->add('TxtMes', TextType::class, array('data' => $session->get('filtroTurMes')))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioVerDetalle() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;    
        $form = $this->createFormBuilder()
            ->getForm();
        return $form;
    }     
    
    private function generarExcel() {
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'AZ'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }
        for($col = 'J'; $col !== 'AZ'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'AÑO')
                    ->setCellValue('B1', 'MES')
                    ->setCellValue('C1', 'CLIENTE')
                    ->setCellValue('D1', 'PUESTO')
                    ->setCellValue('E1', 'CONCEPTO')
                    ->setCellValue('F1', 'MODALIDAD')
                    ->setCellValue('G1', 'PERIODO')
                    ->setCellValue('H1', 'DES')
                    ->setCellValue('I1', 'HAS')
                    ->setCellValue('J1', 'DIAS')
                    ->setCellValue('K1', 'H')
                    ->setCellValue('L1', 'H.P')
                    ->setCellValue('M1', 'CANT')
                    ->setCellValue('N1', 'COSTO')
                    ->setCellValue('O1', 'PRECIO');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arCostoServicios = new \Brasa\TurnoBundle\Entity\TurCostoServicio();
        $arCostoServicios = $query->getResult();
        foreach ($arCostoServicios as $arCostoServicio) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCostoServicio->getAnio())
                    ->setCellValue('B' . $i, $arCostoServicio->getMes())
                    ->setCellValue('C' . $i, $arCostoServicio->getClienteRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arCostoServicio->getPuestoRel()->getNombre())
                    ->setCellValue('E' . $i, $arCostoServicio->getConceptoServicioRel()->getNombre())
                    ->setCellValue('F' . $i, $arCostoServicio->getModalidadServicioRel()->getNombre())
                    ->setCellValue('G' . $i, $arCostoServicio->getPeriodoRel()->getNombre())
                    ->setCellValue('H' . $i, $arCostoServicio->getDiaDesde())
                    ->setCellValue('I' . $i, $arCostoServicio->getDiaHasta())
                    ->setCellValue('J' . $i, $arCostoServicio->getDias())
                    ->setCellValue('K' . $i, $arCostoServicio->getHoras())
                    ->setCellValue('L' . $i, $arCostoServicio->getHorasProgramadas())
                    ->setCellValue('M' . $i, $arCostoServicio->getCantidad())
                    ->setCellValue('N' . $i, $arCostoServicio->getVrCostoRecurso())
                    ->setCellValue('O' . $i, $arCostoServicio->getVrTotal());
            $i++;
        }        

        $objPHPExcel->getActiveSheet()->setTitle('CostoServicio');
        
        $objPHPExcel->createSheet(1)->setTitle('CostoServicioDetalle')
                ->setCellValue('A1', 'AÑO')
                ->setCellValue('B1', 'MES')
                ->setCellValue('C1', 'COD')
                ->setCellValue('D1', 'CLIENTE')
                ->setCellValue('E1', 'CONCEPTO')
                ->setCellValue('F1', 'COD')
                ->setCellValue('G1', 'PUESTO')
                ->setCellValue('H1', 'C.COSTO')
                ->setCellValue('I1', 'MODALIDAD')
                ->setCellValue('J1', 'IDENTIFICACION')
                ->setCellValue('K1', 'NOMBRE')
                ->setCellValue('L1', 'C.COSTO')                
                ->setCellValue('M1', 'DS')
                ->setCellValue('N1', 'D')
                ->setCellValue('O1', 'N')
                ->setCellValue('P1', 'FD')
                ->setCellValue('Q1', 'FN')
                ->setCellValue('R1', 'EOD')
                ->setCellValue('S1', 'EON')
                ->setCellValue('T1', 'EFD')
                ->setCellValue('U1', 'EFN')
                ->setCellValue('V1', 'RN')
                ->setCellValue('W1', 'RFD')
                ->setCellValue('X1', 'RFN')
                ->setCellValue('Y1', 'C.DS')
                ->setCellValue('Z1', 'C.D')
                ->setCellValue('AA1', 'C.N')
                ->setCellValue('AB1', 'C.FD')
                ->setCellValue('AC1', 'C.FN')
                ->setCellValue('AD1', 'C.EOD')
                ->setCellValue('AE1', 'C.EON')
                ->setCellValue('AF1', 'C.EFD')
                ->setCellValue('AG1', 'C.EFN')
                ->setCellValue('AH1', 'C.RN')
                ->setCellValue('AI1', 'C.RFD')
                ->setCellValue('AJ1', 'C.RFN')                
                ->setCellValue('AK1', 'NOM')
                ->setCellValue('AL1', 'SSO')
                ->setCellValue('AM1', 'PRE')
                ->setCellValue('AN1', 'COSTO');

        $objPHPExcel->setActiveSheetIndex(1); 
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet(1)->getStyle('1')->getFont()->setBold(true);     
        for($col = 'A'; $col !== 'AO'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                  
        }            
        for($col = 'J'; $col !== 'AO'; $col++) { 
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');                 
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }             

        $i = 2;
            
        $query = $em->createQuery($this->strListaDetalleDql);
        $arCostoRecursoDetalles = new \Brasa\TurnoBundle\Entity\TurCostoRecursoDetalle();
        $arCostoRecursoDetalles = $query->getResult();
        foreach ($arCostoRecursoDetalles as $arCostoRecursoDetalle) {
            $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('A' . $i, $arCostoRecursoDetalle->getAnio())
                    ->setCellValue('B' . $i, $arCostoRecursoDetalle->getMes())
                    ->setCellValue('C' . $i, $arCostoRecursoDetalle->getCodigoClienteFk())
                    ->setCellValue('D' . $i, $arCostoRecursoDetalle->getClienteRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arCostoRecursoDetalle->getPedidoDetalleRel()->getConceptoServicioRel()->getNombre())                    
                    ->setCellValue('F' . $i, $arCostoRecursoDetalle->getCodigoPuestoFk())
                    ->setCellValue('G' . $i, $arCostoRecursoDetalle->getPuestoRel()->getNombre())
                    ->setCellValue('H' . $i, $arCostoRecursoDetalle->getPuestoRel()->getCodigoCentroCostoContabilidadFk())
                    ->setCellValue('I' . $i, $arCostoRecursoDetalle->getPedidoDetalleRel()->getModalidadServicioRel()->getNombre())
                    ->setCellValue('J' . $i, $arCostoRecursoDetalle->getRecursoRel()->getNumeroIdentificacion())
                    ->setCellValue('K' . $i, $arCostoRecursoDetalle->getRecursoRel()->getNombreCorto())
                    ->setCellValue('L' . $i, $arCostoRecursoDetalle->getRecursoRel()->getEmpleadoRel()->getCodigoCentroCostoContabilidadFk())                    
                    ->setCellValue('M' . $i, $arCostoRecursoDetalle->getHorasDescanso())
                    ->setCellValue('N' . $i, $arCostoRecursoDetalle->getHorasDiurnas())
                    ->setCellValue('O' . $i, $arCostoRecursoDetalle->getHorasNocturnas())
                    ->setCellValue('P' . $i, $arCostoRecursoDetalle->getHorasFestivasDiurnas())
                    ->setCellValue('Q' . $i, $arCostoRecursoDetalle->getHorasFestivasNocturnas())
                    ->setCellValue('R' . $i, $arCostoRecursoDetalle->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('S' . $i, $arCostoRecursoDetalle->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('T' . $i, $arCostoRecursoDetalle->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('U' . $i, $arCostoRecursoDetalle->getHorasExtrasFestivasNocturnas())
                    ->setCellValue('V' . $i, $arCostoRecursoDetalle->getHorasRecargoNocturno())
                    ->setCellValue('W' . $i, $arCostoRecursoDetalle->getHorasRecargoFestivoDiurno())
                    ->setCellValue('X' . $i, $arCostoRecursoDetalle->getHorasRecargoFestivoNocturno())
                    ->setCellValue('Y' . $i, $arCostoRecursoDetalle->getHorasDescansoCosto())
                    ->setCellValue('Z' . $i, $arCostoRecursoDetalle->getHorasDiurnasCosto())
                    ->setCellValue('AA' . $i, $arCostoRecursoDetalle->getHorasNocturnasCosto())
                    ->setCellValue('AB' . $i, $arCostoRecursoDetalle->getHorasFestivasDiurnasCosto())
                    ->setCellValue('AC' . $i, $arCostoRecursoDetalle->getHorasFestivasNocturnasCosto())
                    ->setCellValue('AD' . $i, $arCostoRecursoDetalle->getHorasExtrasOrdinariasDiurnasCosto())
                    ->setCellValue('AE' . $i, $arCostoRecursoDetalle->getHorasExtrasOrdinariasNocturnasCosto())
                    ->setCellValue('AF' . $i, $arCostoRecursoDetalle->getHorasExtrasFestivasDiurnasCosto())
                    ->setCellValue('AG' . $i, $arCostoRecursoDetalle->getHorasExtrasFestivasNocturnasCosto())
                    ->setCellValue('AH' . $i, $arCostoRecursoDetalle->getHorasRecargoNocturnoCosto())
                    ->setCellValue('AI' . $i, $arCostoRecursoDetalle->getHorasRecargoFestivoDiurnoCosto())
                    ->setCellValue('AJ' . $i, $arCostoRecursoDetalle->getHorasRecargoFestivoNocturnoCosto())
                    ->setCellValue('AK' . $i, $arCostoRecursoDetalle->getCostoNomina())
                    ->setCellValue('AL' . $i, $arCostoRecursoDetalle->getCostoSeguridadSocial())
                    ->setCellValue('AM' . $i, $arCostoRecursoDetalle->getCostoPrestaciones())
                    ->setCellValue('AN' . $i, $arCostoRecursoDetalle->getCosto());
            $i++;
        }           
        
        
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CostoServicio.xlsx"');
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