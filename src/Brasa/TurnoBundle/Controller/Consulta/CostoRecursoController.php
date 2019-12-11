<?php
namespace Brasa\TurnoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CostoRecursoController extends Controller
{
    var $strListaDql = "";    
    
    /**
     * @Route("/tur/consulta/costo/recurso", name="brs_tur_consulta_costo_recurso")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 48)) {
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
                $this->lista();
                $this->generarExcel();
            }
        }

        $arCostoRecurso = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Consultas/Costo:recurso.html.twig', array(
            'arCostoRecurso' => $arCostoRecurso,                        
            'form' => $form->createView()));
    }        

    /**
     * @Route("/tur/consulta/costo/recurso/ver/detalle/{codigoCostoRecurso}", name="brs_tur_consulta_costo_recurso_ver_detalle")
     */    
    public function verDetalleAction(Request $request, $codigoCostoRecurso) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioVerDetalle();
        $form->handleRequest($request);
        $arCostoRecurso = new \Brasa\TurnoBundle\Entity\TurCostoRecurso();
        $arCostoRecurso = $em->getRepository('BrasaTurnoBundle:TurCostoRecurso')->find($codigoCostoRecurso);
        if ($form->isValid()) {                             

        }
        $dql = $em->getRepository('BrasaTurnoBundle:TurCostoRecursoDetalle')->listaDql($arCostoRecurso->getCodigoRecursoFk(), $arCostoRecurso->getAnio(), $arCostoRecurso->getMes());
        $arCostoRecursoDetalle = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Consultas/Costo:verDetalleRecurso.html.twig', array(
            'arCostoRecurso' => $arCostoRecurso,
            'arCostoRecursoDetalle' => $arCostoRecursoDetalle,                        
            'form' => $form->createView()));
    }
    
    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();        
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurCostoRecurso')->listaDql($session->get('filtroCodigoRecurso'));                    
    }

    private function filtrar ($form) {
        $session = new session;    
        $session->set('filtroCodigoRecurso', $form->get('TxtCodigoRecurso')->getData());
    }    
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;   
        $strNombreRecurso = "";
        if($session->get('filtroCodigoRecurso')) {
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($session->get('filtroCodigoRecurso'));
            if($arRecurso) {                
                $strNombreRecurso = $arRecurso->getNombreCorto();
            }  else {
                $session->set('filtroCodigoRecurso', null);
            }          
        }

        $form = $this->createFormBuilder()
            ->add('TxtCodigoRecurso', TextType::class, array('label'  => 'Nit','data' => $session->get('filtroCodigoRecurso')))
            ->add('TxtNombreRecurso', TextType::class, array('label'  => 'NombreCliente','data' => $strNombreRecurso))                                                                    
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
        for($col = 'F'; $col !== 'AZ'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');                
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'AÑO')
                    ->setCellValue('B1', 'MES')
                    ->setCellValue('C1', 'CODIGO')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'C.COSTO')
                    ->setCellValue('G1', 'C.NOMINA')
                    ->setCellValue('H1', 'C.PRESTACIONES')
                    ->setCellValue('I1', 'C.APORTES')
                    ->setCellValue('J1', 'TOTAL');
        
        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arCostoRecursos = new \Brasa\TurnoBundle\Entity\TurCostoRecurso();
        $arCostoRecursos = $query->getResult();
        foreach ($arCostoRecursos as $arCostoRecurso) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCostoRecurso->getAnio())
                    ->setCellValue('B' . $i, $arCostoRecurso->getMes())
                    ->setCellValue('C' . $i, $arCostoRecurso->getCodigoRecursoFk())
                    ->setCellValue('D' . $i, $arCostoRecurso->getRecursoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arCostoRecurso->getRecursoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arCostoRecurso->getRecursoRel()->getEmpleadoRel()->getCodigoCentroCostoContabilidadFk())
                    ->setCellValue('G' . $i, $arCostoRecurso->getVrNomina())
                    ->setCellValue('H' . $i, $arCostoRecurso->getVrPrestaciones())
                    ->setCellValue('I' . $i, $arCostoRecurso->getVrAportesSociales())
                    ->setCellValue('J' . $i, $arCostoRecurso->getVrCostoTotal());                         
            $i++;
        }                
        //$objPHPExcel->getActiveSheet()->getStyle('A1:AL1')->getFont()->setBold(true);        
        
        //$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        
        $objPHPExcel->getActiveSheet()->setTitle('CostoRecurso');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CostoRecurso.xlsx"');
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