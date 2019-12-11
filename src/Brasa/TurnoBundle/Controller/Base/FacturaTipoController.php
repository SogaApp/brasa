<?php
namespace Brasa\TurnoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurFacturaTipoType;


class FacturaTipoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/base/factura/tipo", name="brs_tur_base_factura_tipo")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 119, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurFacturaTipo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_factura_tipo'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arFacturaTipos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/FacturaTipo:lista.html.twig', array(
            'arFacturaTipos' => $arFacturaTipos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/factura/tipo/nuevo/{codigoFacturaTipo}", name="brs_tur_base_factura_tipo_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoFacturaTipo = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arFacturaTipo = new \Brasa\TurnoBundle\Entity\TurFacturaTipo();
        if($codigoFacturaTipo != '' && $codigoFacturaTipo != '0') {
            $arFacturaTipo = $em->getRepository('BrasaTurnoBundle:TurFacturaTipo')->find($codigoFacturaTipo);
        }        
        $form = $this->createForm(TurFacturaTipoType::class, $arFacturaTipo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arFacturaTipo = $form->getData();                        
            $em->persist($arFacturaTipo);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_factura_conceto_nuevo', array('codigoFacturaTipo' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_base_factura_tipo'));
            }                                   
        }
        return $this->render('BrasaTurnoBundle:Base/FacturaTipo:nuevo.html.twig', array(
            'arFacturaTipo' => $arFacturaTipo,
            'form' => $form->createView()));
    }        

    
    private function lista() {    
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurFacturaTipo')->listaDQL(
                $session->get('filtroFacturaTipoNombre')   
                ); 
    }

    private function filtrar ($form) {        
        $session = new session;     
        $session->set('filtroFacturaTipoNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = new session;
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroFacturaTipoNombre')))
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
        $arFacturaTipos = new \Brasa\TurnoBundle\Entity\TurFacturaTipo();
        $arFacturaTipos = $query->getResult();
                
        foreach ($arFacturaTipos as $arFacturaTipo) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFacturaTipo->getCodigoFacturaTipoPk())
                    ->setCellValue('B' . $i, $arFacturaTipo->getNombre());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('FacturaTipo');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="FacturaTipos.xlsx"');
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