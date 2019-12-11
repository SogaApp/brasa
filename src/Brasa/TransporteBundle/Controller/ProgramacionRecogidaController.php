<?php
namespace Brasa\TransporteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TransporteBundle\Form\Type\TteProgramacionRecogidaType;
class ProgramacionRecogidaController extends Controller
{
    var $strListaDql = "";
    var $codigoProgramacionRecogida = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTransporteBundle:TteProgramacionRecogida')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_lista'));                                 
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }
        
        $arProgramacionesRecogidas = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTransporteBundle:Movimientos/ProgramacionRecogida:lista.html.twig', array(
            'arProgramacionesRecogidas' => $arProgramacionesRecogidas, 
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoProgramacionRecogida) {
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $em = $this->getDoctrine()->getManager();
        $arProgramacionRecogida = new \Brasa\TransporteBundle\Entity\TteProgramacionRecogida();
        if($codigoProgramacionRecogida != 0) {
            $arProgramacionRecogida = $em->getRepository('BrasaTransporteBundle:TteProgramacionRecogida')->find($codigoProgramacionRecogida);
        }else{
            $arProgramacionRecogida->setFecha(new \DateTime('now'));                        
        }        
        $form = $this->createForm(new TteProgramacionRecogidaType, $arProgramacionRecogida);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arProgramacionRecogida = $form->getData();                       
            $arrControles = $request->request->All();
            if($arrControles['txtCodigoConductor'] != '') {
                $arConductor = new \Brasa\TransporteBundle\Entity\TteConductor();
                $arConductor = $em->getRepository('BrasaTransporteBundle:TteConductor')->find($arrControles['txtCodigoConductor']);                
                if(count($arConductor) > 0) {
                    $arProgramacionRecogida->setConductorRel($arConductor);
                    $em->persist($arProgramacionRecogida);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tte_programacion_recogida_nuevo', array('codigoProgramacionRecogida' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tte_programacion_recogida_detalle', array('codigoProgramacionRecogida' => $arProgramacionRecogida->getCodigoProgramacionRecogidaPk())));
                    }                      
                } else {
                    $objMensaje->Mensaje("error", "El conductor no existe", $this);
                }                             
            }            
            
        }
        return $this->render('BrasaTransporteBundle:Movimientos/ProgramacionRecogida:nuevo.html.twig', array(
            'arProgramacionRecogida' => $arProgramacionRecogida,
            'form' => $form->createView()));
    }        

    public function detalleAction($codigoProgramacionRecogida) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arProgramacionRecogida = new \Brasa\TransporteBundle\Entity\TteProgramacionRecogida();
        $arProgramacionRecogida = $em->getRepository('BrasaTransporteBundle:TteProgramacionRecogida')->find($codigoProgramacionRecogida);
        $form = $this->formularioDetalle($arProgramacionRecogida);
        $form->handleRequest($request);
        if($form->isValid()) {                        
            if($form->get('BtnImprimir')->isClicked()) {                
                $objRecogida = new \Brasa\TransporteBundle\Formatos\FormatoRecogida();
                $objRecogida->Generar($this, $codigoProgramacionRecogida);
            }            
        }
        $arRecogidas = new \Brasa\TransporteBundle\Entity\TteRecogida();
        $arRecogidas = $em->getRepository('BrasaTransporteBundle:TteRecogida')->findBy(array ('codigoProgramacionRecogidaFk' => $codigoProgramacionRecogida));        
        return $this->render('BrasaTransporteBundle:Movimientos/ProgramacionRecogida:detalle.html.twig', array(
            'arRecogidas' => $arRecogidas,                            
            'arProgramacionRecogida' => $arProgramacionRecogida,                    
            'form' => $form->createView()
                    ));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTransporteBundle:TteProgramacionRecogida')->listaDql($this->codigoProgramacionRecogida);
    }

    private function filtrar ($form) {        
        $this->codigoProgramacionRecogida = $form->get('TxtCodigo')->getData();
    }
    
    private function formularioFiltro() {        
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $session->get('filtroIdentificacion')))            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    

    private function formularioDetalle($ar) {        
        
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);         
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);         
        $form = $this->createFormBuilder()      
                ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)    
                ->add('BtnImprimir', 'submit', $arrBotonImprimir)
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'CLIENTE');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arProgramacionesRecogidas = new \Brasa\TransporteBundle\Entity\TteProgramacionRecogida();
        $arProgramacionesRecogidas = $query->getResult();

        foreach ($arProgramacionesRecogidas as $arProgramacionRecogida) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProgramacionRecogida->getCodigoRecogidaPk())
                    ->setCellValue('B' . $i, $arProgramacionRecogida->getTerceroRel()->getNombreCorto());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Recogidaes');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Recogidaes.xlsx"');
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