<?php
namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPagoExamenType;
//use Brasa\RecursoHumanoBundle\Form\Type\RhuExamenDetalleType;
class PagoExamenController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/pago/examen/lista", name="brs_rhu_pago_examen_lista")
     */
    public function listaAction() {        
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 6, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $strSqlLista = $this->getRequest()->getSession();        
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->listar();          
        if ($form->isValid()) {            
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if ($form->get('BtnEliminar')->isClicked()) {    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->eliminarPagoExamenSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_pago_examen_lista'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {    
                $this->filtrar($form);
                $this->listar();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }            
        }                      
        $arPagoExamenes = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoExamen:lista.html.twig', array('arPagoExamenes' => $arPagoExamenes, 'form' => $form->createView()));
    } 
    
    /**
     * @Route("/rhu/pago/examen/nuevo/{codigoPagoExamen}", name="brs_rhu_pago_examen_nuevo")
     */
    public function nuevoAction($codigoPagoExamen) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPagoExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen();
        if($codigoPagoExamen != 0) {
            $arPagoExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->find($codigoPagoExamen);
        }
        $form = $this->createForm(new RhuPagoExamenType, $arPagoExamen);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arPagoExamen = $form->getData();
            $em->persist($arPagoExamen);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_pago_examen_nuevo', array('codigoPagoExamen' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_pago_examen_detalle', array('codigoPagoExamen' => $arPagoExamen->getCodigoPagoExamenPk())));
            }            
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoExamen:nuevo.html.twig', array(
            'arPagoExamen' => $arPagoExamen,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/pago/examen/detalle/{codigoPagoExamen}", name="brs_rhu_pago_examen_detalle")
     */
    public function detalleAction($codigoPagoExamen) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');                     
        $arPagoExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen();
        $arPagoExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->find($codigoPagoExamen);        
        $form = $this->formularioDetalle($arPagoExamen);
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arPagoExamen->getEstadoAutorizado() == 0) {
                    if($em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamenDetalle')->numeroRegistros($codigoPagoExamen) > 0) {
                        $arPagoExamen->setEstadoAutorizado(1);
                        $em->persist($arPagoExamen);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_pago_examen_detalle', array('codigoPagoExamen' => $codigoPagoExamen)));                                                                        
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles al pago de examen', $this);
                    }
                }
                return $this->redirect($this->generateUrl('brs_rhu_pago_examen_detalle', array('codigoPagoExamen' => $codigoPagoExamen)));                                                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arPagoExamen->getEstadoAutorizado() == 1) {
                    $arPagoExamen->setEstadoAutorizado(0);
                    $em->persist($arPagoExamen);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pago_examen_detalle', array('codigoPagoExamen' => $codigoPagoExamen)));                                                
                }
            }            
            
            if($form->get('BtnImprimir')->isClicked()) {
                if($arPagoExamen->getEstadoAutorizado() == 1) {
                    $objFormatoPagoExamenDetalle = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPagoExamenDetalle();
                    $objFormatoPagoExamenDetalle->Generar($this, $codigoPagoExamen);
                }
            }
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                if($arPagoExamen->getEstadoAutorizado() == 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamenDetalle')->eliminarDetallesSeleccionados($arrSeleccionados);
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->liquidar($codigoPagoExamen);
                    return $this->redirect($this->generateUrl('brs_rhu_pago_examen_detalle', array('codigoPagoExamen' => $codigoPagoExamen)));           
                }
            }
        }                
        $arPagoExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle();
        $arPagoExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamenDetalle')->findBy(array ('codigoPagoExamenFk' => $codigoPagoExamen));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoExamen:detalle.html.twig', array(
                    'arPagoExamen' => $arPagoExamen,
                    'arPagoExamenDetalle' => $arPagoExamenDetalle,
                    'form' => $form->createView()
                    ));
    }
    
    /**
     * @Route("/rhu/pago/examen/detalle/nuevo/{codigoPagoExamen}", name="brs_rhu_pago_examen_detalle_nuevo")
     */
    public function detalleNuevoAction($codigoPagoExamen) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPagoExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen();
        $arPagoExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->find($codigoPagoExamen);
        $arExamenes = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamenes = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->findBy(array('codigoEntidadExamenFk' => $arPagoExamen->getCodigoEntidadExamenFk(), 'estadoPagado' => 0));
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) {
            if ($arPagoExamen->getEstadoAutorizado() == 0){
                if ($form->get('BtnGuardar')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoExamen) {
                            $arPagoExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamenDetalle')->findOneBy(array('codigoPagoExamenFk' => $codigoPagoExamen));
                            if ($arPagoExamenDetalle == null){
                                $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
                                $arPagoExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle();
                                $arPagoExamenDetalle->setPagoExamenRel($arPagoExamen);
                                $arPagoExamenDetalle->setExamenRel($arExamen);
                                $arPagoExamenDetalle->setVrPrecio($arExamen->getVrTotal());                                                
                                $em->persist($arPagoExamenDetalle); 
                                $arExamen->setEstadoPagado(1);
                                $em->persist($arExamen);
                            }   
                        }
                        $em->flush();
                    }
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->liquidar($codigoPagoExamen);
                }            
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoExamen:detalleNuevo.html.twig', array(
            'arExamenes' => $arExamenes,
            'form' => $form->createView()));
    }    
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->listaDQL(
                $session->get('filtroCodigoEntidadExamen')
                );        
    }
    
    private function filtrar ($form) {
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoEntidadExamen', $controles['entidadExamenRel']);                               
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
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'ENTIDAD')
                    ->setCellValue('C1', 'TOTAL');
                    
        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arPagoExamenes = $query->getResult();
        foreach ($arPagoExamenes as $arPagoExamen) {
            $strNombreEntidad = "";
            if($arPagoExamen->getEntidadExamenRel()) {
                $strNombreEntidad = $arPagoExamen->getEntidadExamenRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagoExamen->getCodigoPagoExamenPk())
                    ->setCellValue('B' . $i, $strNombreEntidad)
                    ->setCellValue('C' . $i, $arPagoExamen->getVrTotal());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('PagoExamen');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="pagoExamanes.xlsx"');
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
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadExamen',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ee')                                        
                    ->orderBy('ee.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,  
                'empty_data' => "",
                'empty_value' => "TODOS",    
                'data' => ""
            );  
        if($session->get('filtroCodigoEntidadExamen')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEntidadExamen", $session->get('filtroCodigoEntidadExamen'));                                    
        }        
        $form = $this->createFormBuilder()
            ->add('entidadExamenRel', 'entity', $arrayPropiedades)                  
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }   
       
    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);        
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);                
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;            
            $arrBotonEliminarDetalle['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;            
        }
        $form = $this->createFormBuilder()    
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)            
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)            
                    ->add('BtnEliminarDetalle', 'submit', $arrBotonEliminarDetalle)
                    ->getForm();  
        return $form;
    }    
        
}