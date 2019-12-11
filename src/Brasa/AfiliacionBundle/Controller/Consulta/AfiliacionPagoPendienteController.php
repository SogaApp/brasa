<?php
namespace Brasa\AfiliacionBundle\Controller\Consulta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


//use Brasa\AfiliacionBundle\Form\Type\AfiIngresoType;
class AfiliacionPagoPendienteController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/consulta/contrato/afiliacionpagopendiente", name="brs_afi_consulta_contrato_afiliacionpagopendiente")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();     
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 102)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
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
        
        $arPagoPendientes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 70);
        return $this->render('BrasaAfiliacionBundle:Consulta/Contrato:afiliacionPagoPendiente.html.twig', array(
            'arPagoPendientes' => $arPagoPendientes, 
            'form' => $form->createView()));
    }
    
    private function lista() {    
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->listaConsultaPagoPendienteDql(
                $session->get('filtroEmpleadoNombre'),
                $session->get('filtroCodigoCliente'),
                $session->get('filtroEmpleadoIdentificacion'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta')
                ); 
    }       

    private function filtrar ($form) {        
        $session = new session;                        
        $session->set('filtroNit', $form->get('TxtNit')->getData()); 
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroEmpleadoIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());
        $fechaDesde = $form->get('fechaDesde')->getData();
        $fechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null){
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $fechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $fechaHasta->format('Y-m-d'));
        }
        
        $this->lista();
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
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
            ->add('TxtNit', textType::class, array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', textType::class, array('label'  => 'NombreCliente','data' => $strNombreCliente))                                
            ->add('TxtNombre', textType::class, array('label'  => 'Nombre','data' => $session->get('filtroEmpleadoNombre')))
            ->add('TxtNumeroIdentificacion', textType::class, array('label'  => 'Nombre','data' => $session->get('filtroEmpleadoIdentificacion')))
            ->add('fechaDesde', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;        
    }            

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = new session;
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
        
        for($col = 'G'; $col !== 'H'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'IDENTIFICACION')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'FECHA DESDE')
                    ->setCellValue('E1', 'FECHA HASTA')
                    ->setCellValue('F1', 'CLIENTE')
                    ->setCellValue('G1', 'AFILIACION')
                    ->setCellValue('H1', 'ACTIVO');
        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        //$arIngresos = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
        $arPagosPendientes = $query->getResult();
                
        foreach ($arPagosPendientes as $arPagoPendiente) {
        
        if ($arPagoPendiente->getEmpleadoRel()->getCodigoContratoActivo() == null){
            $codigoContratoActivo = 0;
        } else {
            $codigoContratoActivo = $arPagoPendiente->getEmpleadoRel()->getCodigoContratoActivo();
        }
        $arContrato = new \Brasa\AfiliacionBundle\Entity\AfiContrato();
        $arContrato = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->find($codigoContratoActivo);
        
        
        $fechaDesde = '';
        $fechaHasta = '';
        
        if ($arContrato != null){
            
            
            if ($arContrato->getFechaDesde() != null){
                $fechaDesde = $arContrato->getFechaDesde()->format('Y-m-d');
            }
            if ($arContrato->getFechaHasta() != null){
                $fechaHasta = $arContrato->getFechaHasta()->format('Y-m-d');
            }
        }
        $cliente = '';
        if ($arPagoPendiente->getEmpleadoRel()->getCodigoClienteFk() != null){
            $cliente = $arPagoPendiente->getEmpleadoRel()->getClienteRel()->getNombreCorto();
        }
        if ($arPagoPendiente->getEmpleadoRel()->getEstadoActivo() == 1){
            $activo = 'SI';
        } else {
            $activo = 'NO';
        }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagoPendiente->getCodigoEmpleadoFk())
                    ->setCellValue('B' . $i, $arPagoPendiente->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arPagoPendiente->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $fechaDesde)
                    ->setCellValue('E' . $i, $fechaHasta)
                    ->setCellValue('F' . $i, $cliente)
                    ->setCellValue('G' . $i, $arPagoPendiente->getClienteRel()->getAfiliacion())
                    ->setCellValue('H' . $i, $activo);                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('PagoPendiente');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Empleados.xlsx"');
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