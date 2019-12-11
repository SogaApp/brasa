<?php
namespace Brasa\TurnoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurPuestoType;
use Brasa\TurnoBundle\Form\Type\TurPuestoDireccionType;

class PuestoController extends Controller
{
    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";

    /**
     * @Route("/tur/base/puesto/", name="brs_tur_base_puesto")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 76, 1)) {
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
                $em->getRepository('BrasaTurnoBundle:TurPuesto')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_puesto'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arPuestos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Puesto:lista.html.twig', array(
            'arPuestos' => $arPuestos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/puesto/nuevo/{codigoPuesto}", name="brs_tur_base_puesto_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoPuesto = '') {        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();
        if($codigoPuesto != '' && $codigoPuesto != '0') {
            $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($codigoPuesto);
        }        
        $form = $this->createForm(TurPuestoType::class, $arPuesto);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPuesto = $form->getData();
            $em->persist($arPuesto);
            $em->flush();            
            return $this->redirect($this->generateUrl('brs_tur_base_puesto'));
        }
        return $this->render('BrasaTurnoBundle:Base/Puesto:nuevo.html.twig', array(
            'arPuesto' => $arPuesto,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/tur/base/puesto/detalle/{codigoPuesto}", name="brs_tur_base_puesto_detalle")
     */     
    public function detalleAction(Request $request, $codigoPuesto) {
        $em = $this->getDoctrine()->getManager();         
        $objMensaje = $this->get('mensajes_brasa');
        $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();
        $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($codigoPuesto);
        $form = $this->formularioDetalle($arPuesto);
        $form->handleRequest($request);
        if($form->isValid()) {                        
            if($form->get('BtnEliminarPuestoDotacion')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPuestoDotacion');
                $em->getRepository('BrasaTurnoBundle:TurPuestoDotacion')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurPuesto')->liquidar($codigoPuesto);
                return $this->redirect($this->generateUrl('brs_tur_base_puesto_detalle', array('codigoPuesto' => $codigoPuesto)));
            }                
        }

        $arPuestoDotaciones = new \Brasa\TurnoBundle\Entity\TurPuestoDotacion();
        $arPuestoDotaciones = $em->getRepository('BrasaTurnoBundle:TurPuestoDotacion')->findBy(array ('codigoPuestoFk' => $codigoPuesto));
        return $this->render('BrasaTurnoBundle:Base/Puesto:detalle.html.twig', array(
                    'arPuesto' => $arPuesto,
                    'arPuestoDotaciones' => $arPuestoDotaciones,                    
                    'form' => $form->createView()
                    ));
    } 
    
    /**
     * @Route("/tur/base/puesto/dotacion/nuevo/{codigoPuesto}", name="brs_tur_base_puesto_dotacion_nuevo")
     */    
    public function dotacionNuevoAction(Request $request, $codigoPuesto) {        
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();        
        $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();
        $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($codigoPuesto);      
        $form = $this->formularioDotacionNuevo();
        $form->handleRequest($request); 
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                      
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrControles = $request->request->All();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(isset($arrSeleccionados)) {
                    foreach ($arrSeleccionados as $codigoElmentoDotacion) {
                        $cantidad = $arrControles['TxtCantidad'.$codigoElmentoDotacion];
                        if($cantidad > 0) {
                            $arElementoDotacion = new \Brasa\TurnoBundle\Entity\TurElementoDotacion();
                            $arElementoDotacion = $em->getRepository('BrasaTurnoBundle:TurElementoDotacion')->find($codigoElmentoDotacion);
                            $arPuestoDotacion = new \Brasa\TurnoBundle\Entity\TurPuestoDotacion();
                            $arPuestoDotacion->setPuestoRel($arPuesto);   
                            $arPuestoDotacion->setElementoDotacionRel($arElementoDotacion);
                            $arPuestoDotacion->setClienteRel($arPuesto->getClienteRel());                                                            
                            $arPuestoDotacion->setCantidad($cantidad);
                            $total = $cantidad * $arElementoDotacion->getCosto();
                            $arPuestoDotacion->setCosto($arElementoDotacion->getCosto());
                            $arPuestoDotacion->setTotal($total);
                            $em->persist($arPuestoDotacion);                        
                        }                    
                    }                    
                    $em->flush();                    
                    $em->getRepository('BrasaTurnoBundle:TurPuesto')->liquidar($codigoPuesto);
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }           
        }
        $dqlElementosDotacion = $em->getRepository('BrasaTurnoBundle:TurElementoDotacion')->listaDql();
        $arElementosDotacion = $paginator->paginate($em->createQuery($dqlElementosDotacion), $request->query->get('page', 1), 20);        
        return $this->render('BrasaTurnoBundle:Base/Puesto:dotacionNuevo.html.twig', array(
            'arPuesto' => $arPuesto,
            'arElementosDotacion' => $arElementosDotacion,
            'form' => $form->createView()));
    }             
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurPuesto')->listaDQL(
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
        $arrBotonEliminarPuesto = array('label' => 'Eliminar', 'disabled' => false);                        
       
        $form = $this->createFormBuilder()    
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)            
                    ->add('BtnEliminarPuestoDotacion', SubmitType::class, $arrBotonEliminarPuesto)                                
                    ->getForm();  
        return $form;
    }

    private function formularioDotacionNuevo() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;       
        $form = $this->createFormBuilder()                            
            ->add('TxtNombre', TextType::class, array('label'  => 'NombreCliente'))                                                                                                                       
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))                                    
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'M'; $col++) {
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
                    ->setCellValue('I1', 'COSTO')
                    ->setCellValue('J1', 'INTERFACE')
                    ->setCellValue('K1', 'OPERACION')
                    ->setCellValue('L1', 'C.COSTO');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arPuestos = new \Brasa\TurnoBundle\Entity\TurPuesto();
                $arPuestos = $query->getResult();
                
        foreach ($arPuestos as $arPuesto) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPuesto->getCodigoPuestoPk())
                    ->setCellValue('B' . $i, $arPuesto->getClienteRel()->getNit())
                    ->setCellValue('C' . $i, $arPuesto->getClienteRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arPuesto->getNombre())                    
                    ->setCellValue('E' . $i, $arPuesto->getContacto())
                    ->setCellValue('F' . $i, $arPuesto->getTelefono())
                    ->setCellValue('G' . $i, $arPuesto->getCelular())
                    ->setCellValue('H' . $i, $arPuesto->getDireccion())
                    ->setCellValue('I' . $i, $arPuesto->getCostoDotacion())
                    ->setCellValue('J' . $i, $arPuesto->getCodigoInterface())
                    ->setCellValue('L' . $i, $arPuesto->getCodigoCentroCostoContabilidadFk());                                    
                        
            if($arPuesto->getCodigoOperacionFk()) {
                 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $i, $arPuesto->getOperacionRel()->getNombre());
            }
            $i++;
            
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Puesto');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Puestos.xlsx"');
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