<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDotacionCargoType;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuDotacionCargo controller.
 *
 */
class DotacionCargoController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/base/dotacion/cargo/lista", name="brs_rhu_base_dotacion_cargo_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 71, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista(); 
        $form->handleRequest($request);     
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnEliminar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    try{
                        foreach ($arrSeleccionados AS $codigoDotacionCargo) {
                            $arDotacionCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionCargo')->find($codigoDotacionCargo);
                            $em->remove($arDotacionCargo);
                            $em->flush();
                        }
                   } catch (ForeignKeyConstraintViolationException $e) { 
                        $objMensaje->Mensaje('error', 'No se puede eliminar la dotacion por cargo porque esta siendo utilizado', $this);
                     }     
                }                
            }
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }            
        }                
        $arDotacionesCargos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/DotacionCargo:lista.html.twig', array(
                    'arDotacionesCargos' => $arDotacionesCargos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/dotacion/cargo/nuevo/{codigoDotacionCargo}", name="brs_rhu_base_dotacion_cargo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoDotacionCargo) {
        $em = $this->getDoctrine()->getManager();
        $arDotacionCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo();
        if ($codigoDotacionCargo != 0) {
            $arDotacionCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionCargo')->find($codigoDotacionCargo);
        }    
        $form = $this->createForm(new RhuDotacionCargoType(), $arDotacionCargo);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arDotacionCargo = $form->getData();
            $em->persist($arDotacionCargo);            
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_dotacion_cargo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/DotacionCargo:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/rhu/base/dotacion/cargo/nuevomultiple/{codigoDotacionCargo}", name="brs_rhu_base_dotacion_cargo_nuevomultiple")
     */
    public function nuevoMultipleAction(Request $request, $codigoDotacionCargo) {
        $em = $this->getDoctrine()->getManager();
        $arDotacionesElementos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
        $arDotacionesElementos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->findAll();
        $form = $this->createFormBuilder()
            ->add('cargoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'choice_label' => 'nombre',
            ))    
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('BtnGuardar')->isClicked()) {
                if (isset($arrControles['TxtCantidad'])) {
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        if($arrControles['TxtCantidad'][$intIndice] > 0 ){
                            $arDotacionElemento = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
                            $arDotacionElemento = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->find($intCodigo);
                            $arDotacionCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo();
                            $arDotacionCargo->setDotacionElementoRel($arDotacionElemento);
                            $arDotacionCargo->setCargoRel($form->get('cargoRel')->getData());
                            $intCantidad = $arrControles['TxtCantidad'][$intIndice];
                            $arDotacionCargo->setCantidadAsignada($intCantidad);
                            $em->persist($arDotacionCargo);
                        }
                        $intIndice++;
                    }
                }
                $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_base_dotacion_cargo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/DotacionCargo:nuevoMultiple.html.twig', array(
            'form' => $form->createView(),
            'arDotacionesElementos' => $arDotacionesElementos,
        ));
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCargo')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCargo", $session->get('filtroCodigoCargo'));
        }
        $form = $this->createFormBuilder()
            ->add('cargoRel', EntityType::class, $arrayPropiedades)    
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))                
            ->getForm();        
        return $form;
    }     
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionCargo')->listaDql(
        $session->get('filtroCodigoCargo'));         
    }    
    
    private function filtrar ($form) {
        $session = new session;
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCargo', $controles['cargoRel']);
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'CARGO')
                    ->setCellValue('C1', 'DOTACIÓN')
                    ->setCellValue('D1', 'CANTIDAD');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arDotacionesCargos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo();
        $arDotacionesCargos = $query->getResult();
        foreach ($arDotacionesCargos as $arDotacionCargo) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arDotacionCargo->getCodigoDotacionCargoPk())
                    ->setCellValue('B' . $i, $arDotacionCargo->getCargoRel()->getNombre())
                    ->setCellValue('C' . $i, $arDotacionCargo->getDotacionElementoRel()->getDotacion())
                    ->setCellValue('D' . $i, $arDotacionCargo->getCantidadAsignada());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('DotacionesCargos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="DotacionesCargos.xlsx"');
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
