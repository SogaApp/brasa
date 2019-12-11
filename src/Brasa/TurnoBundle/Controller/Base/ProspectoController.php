<?php
namespace Brasa\TurnoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurProspectoType;

class ProspectoController extends Controller
{
    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";
    
    /**
     * @Route("/tur/base/prospecto/lista", name="brs_tur_base_prospecto_lista")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();                
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 85, 1)) {
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
                $em->getRepository('BrasaTurnoBundle:TurProspecto')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_prospecto_lista'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arProspectos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Prospecto:lista.html.twig', array(
            'arProspectos' => $arProspectos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/prospecto/nuevo/{codigoProspecto}", name="brs_tur_base_prospecto_nuevo")
     */     
    public function nuevoAction(Request $request, $codigoProspecto = '') {        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arProspecto = new \Brasa\TurnoBundle\Entity\TurProspecto();
        if($codigoProspecto != '' && $codigoProspecto != '0') {
            $arProspecto = $em->getRepository('BrasaTurnoBundle:TurProspecto')->find($codigoProspecto);
        }        
        $form = $this->createForm(TurProspectoType::class, $arProspecto);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arProspecto = $form->getData(); 
            $arUsuario = $this->getUser();
            $arProspecto->setUsuario($arUsuario->getUserName());            
            $em->persist($arProspecto);
            $em->flush();            

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_prospecto_nuevo', array('codigoProspecto' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_base_prospecto_lista'));
            }                                                                                          
        }
        return $this->render('BrasaTurnoBundle:Base/Prospecto:nuevo.html.twig', array(
            'arProspecto' => $arProspecto,
            'form' => $form->createView()));
    }        
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurProspecto')->listaDQL(
                $this->strNombre,                
                $this->strCodigo   
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
                    ->add('BtnEliminarPuesto', SubmitType::class, $arrBotonEliminarPuesto)            
                    ->getForm();  
        return $form;
    }

    private function generarExcel() {
        ob_clean();
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NIT')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'ESTRATO')
                    ->setCellValue('E1', 'CONTACTO')
                    ->setCellValue('F1', 'TELEFONO')
                    ->setCellValue('G1', 'CELULAR');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arProspectos = new \Brasa\TurnoBundle\Entity\TurProspecto();
                $arProspectos = $query->getResult();
                
        foreach ($arProspectos as $arProspecto) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProspecto->getCodigoProspectoPk())
                    ->setCellValue('B' . $i, $arProspecto->getNit())
                    ->setCellValue('C' . $i, $arProspecto->getNombreCorto())
                    ->setCellValue('D' . $i, $arProspecto->getEstrato())
                    ->setCellValue('E' . $i, $arProspecto->getContacto())
                    ->setCellValue('F' . $i, $arProspecto->getTelefonoContacto())
                    ->setCellValue('G' . $i, $arProspecto->getCelularContacto());
                        
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Prospecto');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Prospectos.xlsx"');
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