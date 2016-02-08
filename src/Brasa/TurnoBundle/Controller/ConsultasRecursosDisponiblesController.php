<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class ConsultasRecursosDisponiblesController extends Controller
{
    var $strListaDql = "";
    var $codigoPedido = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
        $anio = "";
        $mes = "";
        if ($form->isValid()) {            
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $dateFecha = $form->get('fecha')->getData();    
                $anio = $dateFecha->format('Y');
                $mes = $dateFecha->format('m');
                $arRecurso =  $em->getRepository('BrasaTurnoBundle:TurRecurso')->disponibles($dateFecha->format('j'), $dateFecha->format('Y'), $dateFecha->format('m'));                
            }
        }
                
        return $this->render('BrasaTurnoBundle:Consultas/Recurso:disponible.html.twig', array(
            'arRecurso' => $arRecurso,
            'anio' => $anio,
            'mes' => $mes,
            'form' => $form->createView()));
    }        
    
    public function programacionAction($anio, $mes, $codigoRecurso) {
        $em = $this->getDoctrine()->getManager();        
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalle =  $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('anio' => $anio, 'mes' => $mes, 'codigoRecursoFk' => $codigoRecurso));                
        return $this->render('BrasaTurnoBundle:Consultas/Recurso:disponibleProgramacion.html.twig', array(
            'arProgramacionDetalle' => $arProgramacionDetalle));
    }        
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurTurno')->listaDql();
    }

    private function filtrar ($form) {                
        
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('fecha', 'date', array('format' => 'yyyyMMMMdd', 'data' => new \DateTime('now')))            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'CLIENTE');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arProgramaciones = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arProgramaciones = $query->getResult();

        foreach ($arProgramaciones as $arPedido) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPedido->getCodigoPedidoPk())
                    ->setCellValue('B' . $i, $arPedido->getTerceroRel()->getNombreCorto());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Pedidos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pedidos.xlsx"');
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