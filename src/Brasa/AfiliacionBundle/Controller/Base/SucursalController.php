<?php
namespace Brasa\AfiliacionBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\AfiliacionBundle\Form\Type\AfiSucursalType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SucursalController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/base/sucursal", name="brs_afi_base_sucursal")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 125, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiSucursal')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_base_sucursal'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arSucursales = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/Sucursal:lista.html.twig', array(
            'arSucursales' => $arSucursales, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/base/sucursal/nuevo/{codigoSucursal}", name="brs_afi_base_sucursal_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoSucursal = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arSucursal = new \Brasa\AfiliacionBundle\Entity\AfiSucursal();
        if($codigoSucursal != '' && $codigoSucursal != '0') {
            $arSucursal = $em->getRepository('BrasaAfiliacionBundle:AfiSucursal')->find($codigoSucursal);
        }        
        $form = $this->createForm(new AfiSucursalType, $arSucursal);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arSucursal = $form->getData();                        
            $em->persist($arSucursal);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_base_sucursal_nuevo', array('codigoSucursal' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_base_sucursal'));
            }                                   
        }
        return $this->render('BrasaAfiliacionBundle:Base/Sucursal:nuevo.html.twig', array(
            'arSucursal' => $arSucursal,
            'form' => $form->createView()));
    }        
    
    private function lista() {    
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiSucursal')->listaDQL(
                $session->get('filtroSucursalNombre')   
                ); 
    }

    private function filtrar ($form) {        
        $session = new Session();       
        $session->set('filtroSucursalNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = new Session();
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', textType::class, array('label'  => 'Nombre','data' => $session->get('filtroSucursalNombre')))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))            
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }           
    
    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
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
        for($col = 'A'; $col !== 'P'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }            
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NOMBRE')
                    ->setCellValue('C1', 'CODIGO INTERFACE');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arSucursales = new \Brasa\AfiliacionBundle\Entity\AfiSucursal();
        $arSucursales = $query->getResult();
                
        foreach ($arSucursales as $arSucursal) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSucursal->getCodigoSucursalPk())
                    ->setCellValue('B' . $i, $arSucursal->getNombre())
                    ->setCellValue('C' . $i, $arSucursal->getCodigoInterface());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Sucursal');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Sucursals.xlsx"');
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