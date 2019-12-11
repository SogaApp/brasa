<?php
namespace Brasa\TurnoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurNovedadTipoType;


class NovedadTipoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/base/novedad/tipo", name="brs_tur_base_novedad_tipo")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 88, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurNovedadTipo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_novedad_tipo'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arNovedadTipos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/NovedadTipo:lista.html.twig', array(
            'arNovedadTipos' => $arNovedadTipos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/novedad/tipo/nuevo/{codigoNovedadTipo}", name="brs_tur_base_novedad_tipo_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoNovedadTipo = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arNovedadTipo = new \Brasa\TurnoBundle\Entity\TurNovedadTipo();
        if($codigoNovedadTipo != '' && $codigoNovedadTipo != '0') {
            $arNovedadTipo = $em->getRepository('BrasaTurnoBundle:TurNovedadTipo')->find($codigoNovedadTipo);
        }        
        $form = $this->createForm(TurNovedadTipoType::class, $arNovedadTipo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arNovedadTipo = $form->getData();                        
            $em->persist($arNovedadTipo);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_novedad_tipo_nuevo', array('codigoNovedadTipo' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_base_novedad_tipo'));
            }                                   
        }
        return $this->render('BrasaTurnoBundle:Base/NovedadTipo:nuevo.html.twig', array(
            'arNovedadTipo' => $arNovedadTipo,
            'form' => $form->createView()));
    }        

    
    private function lista() {    
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurNovedadTipo')->listaDQL(
                $session->get('filtroNovedadTipoNombre')   
                ); 
    }

    private function filtrar ($form) {        
        $session = new session;       
        $session->set('filtroNovedadTipoNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = new session;
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroNovedadTipoNombre')))
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
                    ->setCellValue('B1', 'NIT')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'ESTRATO')
                    ->setCellValue('E1', 'CONTACTO')
                    ->setCellValue('F1', 'TELEFONO')
                    ->setCellValue('G1', 'CELULAR')
                    ->setCellValue('H1', 'DIRECCION')
                    ->setCellValue('I1', 'BARRIO')
                    ->setCellValue('J1', 'CIUDAD')
                    ->setCellValue('K1', 'FORMA PAGO')
                    ->setCellValue('L1', 'PLAZO PAGO')
                    ->setCellValue('M1', 'FINANCIERO')
                    ->setCellValue('N1', 'CELULAR FINANCIERO')
                    ->setCellValue('O1', 'GERENTE')
                    ->setCellValue('P1', 'CELULAR GERENTE');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arNovedadTipos = new \Brasa\TurnoBundle\Entity\TurNovedadTipo();
                $arNovedadTipos = $query->getResult();
                
        foreach ($arNovedadTipos as $arNovedadTipo) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arNovedadTipo->getCodigoNovedadTipoPk())
                    ->setCellValue('B' . $i, $arNovedadTipo->getNit())
                    ->setCellValue('C' . $i, $arNovedadTipo->getNombreCorto())
                    ->setCellValue('D' . $i, $arNovedadTipo->getEstrato())
                    ->setCellValue('E' . $i, $arNovedadTipo->getContacto())
                    ->setCellValue('F' . $i, $arNovedadTipo->getTelefonoContacto())
                    ->setCellValue('G' . $i, $arNovedadTipo->getCelularContacto())
                    ->setCellValue('H' . $i, $arNovedadTipo->getDireccion())
                    ->setCellValue('I' . $i, $arNovedadTipo->getBarrio())
                    ->setCellValue('J' . $i, $arNovedadTipo->getCiudadRel()->getNombre())
                    ->setCellValue('K' . $i, $arNovedadTipo->getFormaPagoRel()->getNombre())
                    ->setCellValue('L' . $i, $arNovedadTipo->getPlazoPago())
                    ->setCellValue('M' . $i, $arNovedadTipo->getFinanciero())
                    ->setCellValue('N' . $i, $arNovedadTipo->getCelularFinanciero())
                    ->setCellValue('O' . $i, $arNovedadTipo->getGerente())
                    ->setCellValue('P' . $i, $arNovedadTipo->getCelularGerente());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('NovedadTipo');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="NovedadTipos.xlsx"');
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