<?php
namespace Brasa\TurnoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FacturasDetallesController extends Controller
{
    var $strListaDql = "";
    var $codigoRecurso = "";
    /**
     * @Route("/tur/consulta/facturas/detalles", name="brs_tur_consulta_facturas_detalles")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 89)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
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

        $arFacturaDetalle = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Consultas/Factura:detalle.html.twig', array(
            'arFacturaDetalle' => $arFacturaDetalle,                        
            'form' => $form->createView()));
    }        
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroFacturaFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroFacturaFechaDesde');
            $strFechaHasta = $session->get('filtroFacturaFechaHasta');
        }
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->listaConsultaDql(
                $session->get('filtroFacturaNumero'),
                $session->get('filtroCodigoCliente'),
                $session->get('filtroFacturaEstadoAutorizado'),
                $strFechaDesde,
                $strFechaHasta,                
                $session->get('filtroFacturaEstadoAnulado'),
                $session->get('filtroTurnosCodigoFacturaTipo')
                );
    }

    private function filtrar ($form) { 
        $session = new session;
        $arFacturaTipo = $form->get('facturaTipoRel')->getData();
        if($arFacturaTipo) {
            $session->set('filtroTurnosCodigoFacturaTipo', $arFacturaTipo->getCodigoFacturaTipoPk());
        } else {
            $session->set('filtroTurnosCodigoFacturaTipo', null);
        }             
        $session->set('filtroFacturaNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroFacturaEstadoAutorizado', $form->get('estadoAutorizado')->getData());          
        $session->set('filtroFacturaEstadoAnulado', $form->get('estadoAnulado')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroFacturaFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroFacturaFechaHasta', $dateFechaHasta->format('Y/m/d'));                 
        $session->set('filtroFacturaFiltrarFecha', $form->get('filtrarFecha')->getData());
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
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroFacturaFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroFacturaFechaDesde');
        }
        if($session->get('filtroFacturaFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroFacturaFechaHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        
        $arrayPropiedadesFacturaTipo = array(
                'class' => 'BrasaTurnoBundle:TurFacturaTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ft')
                    ->orderBy('ft.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroTurnosCodigoFacturaTipo')) {
            $arrayPropiedadesFacturaTipo['data'] = $em->getReference("BrasaTurnoBundle:TurFacturaTipo", $session->get('filtroTurnosCodigoFacturaTipo'));
        }        
        
        $form = $this->createFormBuilder()
            ->add('facturaTipoRel', EntityType::class, $arrayPropiedadesFacturaTipo)
            ->add('TxtNit', TextType::class, array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', TextType::class, array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumero', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroFacturaNumero')))
            ->add('estadoAutorizado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'AUTORIZADO', '0' => 'SIN AUTORIZAR'), 'data' => $session->get('filtroFacturaEstadoAutorizado')))                
            ->add('estadoAnulado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'ANULADO', '0' => 'SIN ANULAR'), 'data' => $session->get('filtroFacturaEstadoAnulado')))                                
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('filtrarFecha', CheckboxType::class, array('required'  => false, 'data' => $session->get('filtroFacturaFiltrarFecha')))                 
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
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
        for($col = 'A'; $col !== 'M'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }               
        for($col = 'G'; $col !== 'K'; $col++) {  
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('rigth');
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'TIPO')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'NIT')
                    ->setCellValue('E1', 'CLIENTE')
                    ->setCellValue('F1', 'SERVICIO')
                    ->setCellValue('G1', 'CANT')
                    ->setCellValue('H1', 'PRECIO')
                    ->setCellValue('I1', 'IVA')
                    ->setCellValue('J1', 'SUBTOTAL')
                    ->setCellValue('K1', 'ANIO')
                    ->setCellValue('L1', 'MES')
                    ->setCellValue('M1', 'PUESTO')
                    ->setCellValue('N1', 'C.COSTO');
        
        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        $arFacturaDetalles = $query->getResult();
        foreach ($arFacturaDetalles as $arFacturaDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFacturaDetalle->getFacturaRel()->getFacturaTipoRel()->getNombre())
                    ->setCellValue('B' . $i, $arFacturaDetalle->getFacturaRel()->getNumero())                    
                    ->setCellValue('C' . $i, $arFacturaDetalle->getFacturaRel()->getFecha()->format('Y-m'))
                    ->setCellValue('D' . $i, $arFacturaDetalle->getFacturaRel()->getClienteRel()->getNit())              
                    ->setCellValue('E' . $i, $arFacturaDetalle->getFacturaRel()->getClienteRel()->getNombreCorto())              
                    ->setCellValue('F' . $i, $arFacturaDetalle->getConceptoServicioRel()->getNombre())                                  
                    ->setCellValue('G' . $i, $arFacturaDetalle->getCantidad())
                    ->setCellValue('H' . $i, $arFacturaDetalle->getVrPrecio())
                    ->setCellValue('I' . $i, $arFacturaDetalle->getPorIva())
                    ->setCellValue('J' . $i, $arFacturaDetalle->getSubtotal());  
            
            if($arFacturaDetalle->getCodigoPedidoDetalleFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $i, $arFacturaDetalle->getPedidoDetalleRel()->getAnio());
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $i, $arFacturaDetalle->getPedidoDetalleRel()->getMes());
            }                     
            if($arFacturaDetalle->getCodigoPuestoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $i, $arFacturaDetalle->getPuestoRel()->getNombre());
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $i, $arFacturaDetalle->getPuestoRel()->getCodigoCentroCostoContabilidadFk());
            }             
            $i++;
        }
        $intNum = count($arFacturaDetalle);
        $intNum += 1;                
        //$objPHPExcel->getActiveSheet()->getStyle('A1:AL1')->getFont()->setBold(true);        
        
        //$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        
        $objPHPExcel->getActiveSheet()->setTitle('FacturaDetalle');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="FacturaDetalle.xlsx"');
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