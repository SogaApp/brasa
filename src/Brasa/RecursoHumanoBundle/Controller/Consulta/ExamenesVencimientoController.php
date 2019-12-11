<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class ExamenesVencimientoController extends Controller
{
    var $strDqlLista = "";        
    var $strFecha = "";
    var $strNumeroIdentificacion = "";
    
    /**
     * @Route("/rhu/consultas/examanes/vencimiento", name="brs_rhu_consultas_examenes_vencimiento")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 31)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->filtrarLista($form);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }

        }
        $arExamenesDetalles = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Examen:vencimiento.html.twig', array(
            'arExamenesDetalles' => $arExamenesDetalles,
            'form' => $form->createView()
            ));
    }        
    
    private function listar() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->listaDql($this->strNumeroIdentificacion, $this->strFecha);
    }       
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        
        $form = $this->createFormBuilder()
            ->add('TxtNumeroIdentificacion', 'text', array('label'  => 'Identificacion'))
            ->add('fecha','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => new \DateTime('now'), 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }           
    
    private function filtrarLista($form) {        
        $this->strFecha = $form->get('fecha')->getData()->format('Y-m-d');        
        $this->strNumeroIdentificacion = $form->get('TxtNumeroIdentificacion')->getData();        
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'IDENTIFICACIÓN')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'EXAMEN')                    
                    ->setCellValue('E1', 'VENCIMIENTO');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arEmpleadosExamanes = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
        $arEmpleadosExamanes = $query->getResult();
        foreach ($arEmpleadosExamanes as $arEmpleadoExamen) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleadoExamen->getCodigoEmpleadoExamenPk())
                    ->setCellValue('B' . $i, $arEmpleadoExamen->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arEmpleadoExamen->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arEmpleadoExamen->getExamenTipoRel()->getNombre())                    
                    ->setCellValue('E' . $i, $arEmpleadoExamen->getFechaVencimiento()->format('Y-m-d'));
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Estudios');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="EmpleadosEstudiosVencimiento.xlsx"');
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
