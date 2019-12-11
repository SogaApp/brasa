<?php
namespace Brasa\AfiliacionBundle\Controller\Consulta;
use Brasa\GeneralBundle\ExtensionesTwig\BrasaTwigExtension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Brasa\AfiliacionBundle\Form\Type\AfiPeriodoType;
class PeriodoDetalleController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/consulta/periodo/detalle", name="brs_afi_consulta_periodo_detalle")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 107)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        } 
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {                      
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
                
            }
        }
        
        $arPeriodoDetalles = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 70);
        return $this->render('BrasaAfiliacionBundle:Consulta/Periodo:detalle.html.twig', array(
            'arPeriodoDetalles' => $arPeriodoDetalles, 
            'form' => $form->createView()));
    }
    
    private function lista() {    
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetalle')->listaConsultaDql(
                $session->get('filtroCursoNumero'),
                $session->get('filtroCodigoCliente'),
                $session->get('filtroPeriodoEstadoFacturado'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta'),
                $session->get('filtroAsesor')
                ); 
    }       

    private function filtrar ( $form) {        
        $session = new session;
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $arrControles = $request->request->All();
        $session->set('filtroPeriodoNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroPeriodoEstadoFacturado', $form->get('estadoFacturado')->getData());
        $session->set('filtroAsesor', $controles['asesorRel']);
        $session->set('filtroNit', $form->get('TxtNit')->getData()); 
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null){
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d')); 
        }
        $this->lista();
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $arrayPropiedades = array(
                'class' => 'BrasaGeneralBundle:GenAsesor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroAsesor')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaGeneralBundle:GenAsesor", $session->get('filtroAsesor'));
        }
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
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
        $form = $this->createFormBuilder() 
            ->add('asesorRel', EntityType::class, $arrayPropiedades)    
            ->add('TxtNit', textType::class, array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', textType::class, array('label'  => 'NombreCliente','data' => $strNombreCliente))
            ->add('TxtNumero', textType::class, array('label'  => 'Codigo','data' => $session->get('filtroCursoNumero')))
            ->add('estadoFacturado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'FACTURADO', '0' => 'SIN FACTURAR'), 'data' => $session->get('filtroPeriodoEstadoFacturado')))                
            ->add('fechaDesde', DateType::class ,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }            

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        for($col = 'I'; $col !== 'R'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        } 
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'COD')
                    ->setCellValue('B1', 'DESDE')
                    ->setCellValue('C1', 'HASTA')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'ING')
                    ->setCellValue('G1', 'DIAS')
                    ->setCellValue('H1', 'SALARIO')
                    ->setCellValue('I1', 'PENSION')
                    ->setCellValue('J1', 'SALUD')
                    ->setCellValue('K1', 'CAJA')
                    ->setCellValue('L1', 'RIESGO')
                    ->setCellValue('M1', 'ADMON')
                    ->setCellValue('N1', 'TOTAL')
                    ->setCellValue('O1', 'RECIBO')
                    ->setCellValue('P1', 'FECHA RECIBO');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                 
        //$arPeriodoDetalles = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle();
        $arPeriodoDetalles = $query->getResult();
        
        $cliente = '';
        $cliente2 = '';
        $douTotal = 0;
        $douTotalGeneral = 0;
        $contador = 0; 
        $contador2 = 0; 
        foreach ($arPeriodoDetalles as $arPeriodoDetalle) {
            $contador2 = $contador2 + 1;
            if ($contador == 0){
               $cliente = $arPeriodoDetalle->getPeriodoRel()->getCodigoClienteFk();  
               $cliente2 = $arPeriodoDetalle->getPeriodoRel()->getCodigoClienteFk();
            }
            if ($cliente2 !=  $arPeriodoDetalle->getPeriodoRel()->getCodigoClienteFk()){
                $objPHPExcel->getActiveSheet()->getStyle($i)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle($i)->getAlignment()->setHorizontal('right');
                $objPHPExcel->getActiveSheet()->getStyle($i)->getNumberFormat()->setFormatCode('#,##0');
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('M'. $i, 'TOTAL:')
                ->setCellValue('N'. $i, $douTotal);
                $i = $i+1;
                $cliente2 =  $arPeriodoDetalle->getPeriodoRel()->getCodigoClienteFk();
                $douTotal = 0;
                //$contador2 = $contador2 + 1;
            }
            if ($cliente != $arPeriodoDetalle->getPeriodoRel()->getCodigoClienteFk() || $contador == 0 ){
                $objPHPExcel->getActiveSheet()->getStyle($i)->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPeriodoDetalle->getPeriodoRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('B' . $i, $arPeriodoDetalle->getPeriodoRel()->getClienteRel()->getTelefono(). ' - ' .$arPeriodoDetalle->getPeriodoRel()->getClienteRel()->getCelular())
                    ->setCellValue('C' . $i, $arPeriodoDetalle->getPeriodoRel()->getClienteRel()->getEmail())
                    ->setCellValue('D' . $i, "ASESOR: ".$arPeriodoDetalle->getPeriodoRel()->getClienteRel()->getAsesorRel()->getNombre());
                $i = $i+1;
                $cliente = $arPeriodoDetalle->getPeriodoRel()->getCodigoClienteFk();
                //$contador2 = $contador2 + 1;
                
            }
                     
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPeriodoDetalle->getCodigoPeriodoDetallePk())
                    ->setCellValue('B' . $i, $arPeriodoDetalle->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('C' . $i, $arPeriodoDetalle->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arPeriodoDetalle->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPeriodoDetalle->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $objFunciones->devuelveBoolean($arPeriodoDetalle->getIngreso()))
                    ->setCellValue('G' . $i, $arPeriodoDetalle->getDias())
                    ->setCellValue('H' . $i, $arPeriodoDetalle->getSalario())
                    ->setCellValue('I' . $i, $arPeriodoDetalle->getContratoRel()->getEntidadPensionRel()->getNombre())
                    ->setCellValue('J' . $i, $arPeriodoDetalle->getContratoRel()->getEntidadSaludRel()->getNombre())
                    ->setCellValue('K' . $i, $arPeriodoDetalle->getContratoRel()->getEntidadCajaRel()->getNombre())
                    ->setCellValue('L' . $i, $arPeriodoDetalle->getContratoRel()->getClasificacionRiesgoRel()->getNombre())
                    ->setCellValue('M' . $i, $arPeriodoDetalle->getAdministracion())
                    ->setCellValue('N' . $i, $arPeriodoDetalle->getTotal());
                    $twigFuncions = new BrasaTwigExtension($em);
                    $arrRecibo = $twigFuncions->getValidarRecibo($arPeriodoDetalle->getCodigoPeriodoFk());
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('O' . $i, $arrRecibo[0])
                    ->setCellValue('P' . $i, $arrRecibo[1]);

            $douTotalGeneral = $douTotalGeneral + $arPeriodoDetalle->getTotal();
            $douTotal = $douTotal + $arPeriodoDetalle->getTotal();
            $contador++;
            $contador2++;
            $i++;
        }
        
        if ($i == ($contador + 3)){BrasaTwigExtension::
                $objPHPExcel->getActiveSheet()->getStyle($i)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle($i)->getAlignment()->setHorizontal('right');
                $objPHPExcel->getActiveSheet()->getStyle($i)->getNumberFormat()->setFormatCode('#,##0');
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('M'. $i, 'TOTAL:')
                ->setCellValue('N'. $i, $douTotal);
                
        }
        if ($contador2 == ($contador * 2)){
                $objPHPExcel->getActiveSheet()->getStyle($i)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle($i)->getAlignment()->setHorizontal('right');
                $objPHPExcel->getActiveSheet()->getStyle($i)->getNumberFormat()->setFormatCode('#,##0');
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('M'. $i, 'TOTAL:')
                ->setCellValue('N'. $i, $douTotal);
                
        }
        $i = $i + 1;
        $objPHPExcel->getActiveSheet()->getStyle($i)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle($i)->getAlignment()->setHorizontal('right');
        $objPHPExcel->getActiveSheet()->getStyle($i)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('M'. $i, 'TOTAL GENERAL:')
                ->setCellValue('N'. $i, $douTotalGeneral);
        
        $objPHPExcel->getActiveSheet()->setTitle('Periodo');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Periodos.xlsx"');
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