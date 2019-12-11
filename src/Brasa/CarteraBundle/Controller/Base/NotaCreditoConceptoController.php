<?php
namespace Brasa\CarteraBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\CarteraBundle\Form\Type\CarNotaCreditoConceptoType;

class NotaCreditoConceptoController extends Controller
{
    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";
    /**
     * @Route("/cartera/base/notacredito/concepto/lista", name="brs_cartera_base_notacredito_concepto_listar")
     */   
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 109, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 109, 4)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaCarteraBundle:CarNotaCreditoConcepto')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_cartera_base_notacredito_concepto_listar'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arNotaCreditoConceptos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaCarteraBundle:Base/NotaCreditoConcepto:lista.html.twig', array(
            'arNotaCreditoConceptos' => $arNotaCreditoConceptos, 
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/cartera/base/notacredito/concepto/nuevo/{codigoNotaCreditoConcepto}", name="brs_cartera_base_notacredito_concepto_nuevo")
     */
    public function nuevoAction($codigoNotaCreditoConcepto = '') {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arNotaCreditoConcepto = new \Brasa\CarteraBundle\Entity\CarNotaCreditoConcepto();
        if($codigoNotaCreditoConcepto != '' && $codigoNotaCreditoConcepto != '0') {
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 109, 3)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
            $arNotaCreditoConcepto = $em->getRepository('BrasaCarteraBundle:CarNotaCreditoConcepto')->find($codigoNotaCreditoConcepto);
        } else {
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 109, 2)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
        }        
        $form = $this->createForm(new CarNotaCreditoConceptoType, $arNotaCreditoConcepto);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arNotaCreditoConcepto = $form->getData();
            $em->persist($arNotaCreditoConcepto);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_cartera_base_notacredito_concepto_nuevo', array('codigoNotaCreditoConcepto' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_cartera_base_notacredito_concepto_listar'));
            }                                   
                                                                                        

        }
        return $this->render('BrasaCarteraBundle:Base/NotaCreditoConcepto:nuevo.html.twig', array(
            'arNotaCreditoConcepto' => $arNotaCreditoConcepto,
            'form' => $form->createView()));
    }          
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaCarteraBundle:CarNotaCreditoConcepto')->listaDQL(
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
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->strCodigo))                            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NOMBRE');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arNotaCreditoConceptos = new \Brasa\CarteraBundle\Entity\CarNotaCreditoConcepto();
                $arNotaCreditoConceptos = $query->getResult();
                
        foreach ($arNotaCreditoConceptos as $arNotaCreditoConcepto) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arNotaCreditoConcepto->getCodigoNotaCreditoConceptoPk())
                    ->setCellValue('B' . $i, $arNotaCreditoConcepto->getNombre());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('NotaCreditoConcepto');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="NotaCreditoConceptos.xlsx"');
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