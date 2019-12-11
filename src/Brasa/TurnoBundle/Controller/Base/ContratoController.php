<?php
namespace Brasa\TurnoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Brasa\TurnoBundle\Form\Type\TurContratoType;

class ContratoController extends Controller
{
    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";

    /**
     * @Route("/tur/base/contrato/", name="brs_tur_base_contrato")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 75, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurContrato')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_contrato'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arContratos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Contrato:lista.html.twig', array(
            'arContratos' => $arContratos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/contrato/nuevo/{codigoContrato}", name="brs_tur_base_contrato_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoContrato = '') {        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContrato = new \Brasa\TurnoBundle\Entity\TurContrato();
        if($codigoContrato != '' && $codigoContrato != '0') {
            $arContrato = $em->getRepository('BrasaTurnoBundle:TurContrato')->find($codigoContrato);
        }        
        
        $form = $this->createForm(TurContratoType::class, $arContrato);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arContrato = $form->getData();
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {                
                $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $arContrato->setClienteRel($arCliente);
                    $em->persist($arContrato);
                    $em->flush();            
                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_base_contrato_nuevo', array('codigoContrato' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_base_contrato'));
                    }                    
                }   
            }                                                                                                                                                                              
        }
        return $this->render('BrasaTurnoBundle:Base/Contrato:nuevo.html.twig', array(
            'arContrato' => $arContrato,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/tur/base/contrato/detalle/{codigoContrato}", name="brs_tur_base_contrato_detalle")
     */     
    public function detalleAction(Request $request, $codigoContrato) {
        $em = $this->getDoctrine()->getManager();         
        $objMensaje = $this->get('mensajes_brasa');
        $arContrato = new \Brasa\TurnoBundle\Entity\TurContrato();
        $arContrato = $em->getRepository('BrasaTurnoBundle:TurContrato')->find($codigoContrato);
        $form = $this->formularioDetalle($arContrato);
        $form->handleRequest($request);
        if($form->isValid()) {                        
              
        }


        return $this->render('BrasaTurnoBundle:Base/Contrato:detalle.html.twig', array(
                    'arContrato' => $arContrato,                    
                    'form' => $form->createView()
                    ));
    } 
              
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurContrato')->listaDQL(
                $this->strCodigo,
                '',
                $this->strNombre                
                ); 
    }

    private function filtrar ($form) {
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->lista();
    }
    
    private function formularioFiltro() {
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo','data' => $this->strCodigo))                            
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))            
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle($ar) {
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);                
       
        $form = $this->createFormBuilder()    
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)                                
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'J'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'I'; $col !== 'J'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NIT')
                    ->setCellValue('C1', 'CLIENTE')
                    ->setCellValue('D1', 'NOMBRE')
                    ->setCellValue('E1', 'CONTACTO')
                    ->setCellValue('F1', 'TELEFONO')
                    ->setCellValue('G1', 'CELULAR')
                    ->setCellValue('H1', 'DIRECCION')
                    ->setCellValue('I1', 'COSTO');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arContratos = new \Brasa\TurnoBundle\Entity\TurContrato();
                $arContratos = $query->getResult();
                
        foreach ($arContratos as $arContrato) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arContrato->getCodigoContratoPk())
                    ->setCellValue('B' . $i, $arContrato->getClienteRel()->getNit())
                    ->setCellValue('C' . $i, $arContrato->getClienteRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arContrato->getNombre())                    
                    ->setCellValue('E' . $i, $arContrato->getContacto())
                    ->setCellValue('F' . $i, $arContrato->getTelefono())
                    ->setCellValue('G' . $i, $arContrato->getCelular())
                    ->setCellValue('H' . $i, $arContrato->getDireccion())
                    ->setCellValue('I' . $i, $arContrato->getCostoDotacion());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Contrato');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Contratos.xlsx"');
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