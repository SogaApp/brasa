<?php
namespace Brasa\AfiliacionBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\AfiliacionBundle\Form\Type\AfiCursoTipoType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CursoTipoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/base/curso/tipo", name="brs_afi_base_curso_tipo")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 124, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiCursoTipo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_factura_concepto'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arCursoTipos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/CursoTipo:lista.html.twig', array(
            'arCursoTipos' => $arCursoTipos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/base/curso/tipo/nuevo/{codigoCursoTipo}", name="brs_afi_base_curso_tipo_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoCursoTipo = '') {
        $em = $this->getDoctrine()->getManager();
        $arCursoTipo = new \Brasa\AfiliacionBundle\Entity\AfiCursoTipo();
        if($codigoCursoTipo != '' && $codigoCursoTipo != '0') {
            $arCursoTipo = $em->getRepository('BrasaAfiliacionBundle:AfiCursoTipo')->find($codigoCursoTipo);
        }        
        $form = $this->createForm(new AfiCursoTipoType, $arCursoTipo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arCursoTipo = $form->getData();                        
            $em->persist($arCursoTipo);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_base_curso_tipo_nuevo', array('codigoCursoTipo' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_base_curso_tipo'));
            }                                   
        }
        return $this->render('BrasaAfiliacionBundle:Base/CursoTipo:nuevo.html.twig', array(
            'arCursoTipo' => $arCursoTipo,
            'form' => $form->createView()));
    }           
    
    private function lista() {    
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiCursoTipo')->listaDQL(
                $session->get('filtroCursoTipoNombre')   
                ); 
    }

    private function filtrar ($form) {        
        $session = new Session();        
        $session->set('filtroCursoTipoNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = new Session();
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', textType::class, array('label'  => 'Nombre','data' => $session->get('filtroCursoTipoNombre')))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))            
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'D'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }            
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NOMBRE')
                    ->setCellValue('C1', 'PRECIO');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arCursoTipos = new \Brasa\AfiliacionBundle\Entity\AfiCursoTipo();
        $arCursoTipos = $query->getResult();
                
        foreach ($arCursoTipos as $arCursoTipo) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCursoTipo->getCodigoCursoTipoPk())
                    ->setCellValue('B' . $i, $arCursoTipo->getNombre())
                    ->setCellValue('C' . $i, $arCursoTipo->getPrecio());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('CursoTipo');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CursoTipos.xlsx"');
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