<?php
namespace Brasa\TurnoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurProyectoType;

class ProyectoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/base/proyecto", name="brs_tur_base_proyecto")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager(); 
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 83, 1)) {
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
                $em->getRepository('BrasaTurnoBundle:TurProyecto')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_proyecto'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arProyectos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Proyecto:lista.html.twig', array(
            'arProyectos' => $arProyectos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/proyecto/nuevo/{codigoProyecto}", name="brs_tur_base_proyecto_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoProyecto = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arProyecto = new \Brasa\TurnoBundle\Entity\TurProyecto();
        if($codigoProyecto != '' && $codigoProyecto != '0') {
            $arProyecto = $em->getRepository('BrasaTurnoBundle:TurProyecto')->find($codigoProyecto);
        }        
        $form = $this->createForm(TurProyectoType::class, $arProyecto);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arProyecto = $form->getData();                        
            $em->persist($arProyecto);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_proyecto_nuevo', array('codigoProyecto' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_base_proyecto'));
            }                                   
        }
        return $this->render('BrasaTurnoBundle:Base/Proyecto:nuevo.html.twig', array(
            'arProyecto' => $arProyecto,
            'form' => $form->createView()));
    }        

    
    private function lista() {    
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurProyecto')->listaDQL('',
                $session->get('filtroProyectoNombre'), ''   
                ); 
    }

    private function filtrar ($form) {        
        $session = new session;   
        $session->set('filtroProyectoNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = new session;
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroProyectoNombre')))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))            
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NOMBRE');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arProyectos = new \Brasa\TurnoBundle\Entity\TurProyecto();
        $arProyectos = $query->getResult();
                
        foreach ($arProyectos as $arProyecto) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProyecto->getCodigoProyectoPk())
                    ->setCellValue('B' . $i, $arProyecto->getNombre());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Proyecto');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Proyectos.xlsx"');
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