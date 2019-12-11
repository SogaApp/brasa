<?php
namespace Brasa\TransporteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TransporteBundle\Form\Type\TteRecogidaType;
class RecogidaController extends Controller
{
    var $strListaDql = "";
    var $codigoRecogida = "";
    
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
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoRecogida) {
                        $arRecogida = new \Brasa\TransporteBundle\Entity\TteRecogida();
                        $arRecogida = $em->getRepository('BrasaTransporteBundle:TteRecogida')->find($codigoRecogida);
                        $em->remove($arRecogida);
                        $em->flush();
                    }
                }
                return $this->redirect($this->generateUrl('brs_tte_recogida_lista'));                                 
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
        
        $arRecogidas = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTransporteBundle:Movimientos/Recogida:lista.html.twig', array(
            'arRecogidas' => $arRecogidas, 
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoRecogida) {
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $em = $this->getDoctrine()->getManager();
        $arRecogida = new \Brasa\TransporteBundle\Entity\TteRecogida();
        if($codigoRecogida != 0) {
            $arRecogida = $em->getRepository('BrasaTransporteBundle:TteRecogida')->find($codigoRecogida);
        }else{
            $arRecogida->setFechaAnuncio(new \DateTime('now'));            
            $arRecogida->setFechaRecogida(new \DateTime('now'));            
        }        
        $form = $this->createForm(new TteRecogidaType, $arRecogida);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arRecogida = $form->getData();                       
            $arrControles = $request->request->All();
            if($arrControles['txtCodigo'] != '') {
                $arCliente = new \Brasa\TransporteBundle\Entity\TteCliente;
                $arCliente = $em->getRepository('BrasaTransporteBundle:TteCliente')->find($arrControles['txtCodigo']);                
                if(count($arCliente) > 0) {
                    $arRecogida->setClienteRel($arCliente);
                    $em->persist($arRecogida);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tte_recogida_nuevo', array('codigoRecogida' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tte_recogida_lista'));
                    }                      
                } else {
                    $objMensaje->Mensaje("error", "El cliente no existe", $this);
                }                             
            }            
            
        }
        return $this->render('BrasaTransporteBundle:Movimientos/Recogida:nuevo.html.twig', array(
            'arRecogida' => $arRecogida,
            'form' => $form->createView()));
    }        

    public function detalleAction($codigoRecogida) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arRecogida = new \Brasa\TransporteBundle\Entity\TteRecogida();
        $arRecogida = $em->getRepository('BrasaTransporteBundle:TteRecogida')->find($codigoRecogida);
        $form = $this->formularioDetalle($arRecogida);
        $form->handleRequest($request);
        if($form->isValid()) {                        
            if($form->get('BtnImprimir')->isClicked()) {                
                $objRecogida = new \Brasa\TransporteBundle\Formatos\FormatoRecogida();
                $objRecogida->Generar($this, $codigoRecogida);
            }            
        }
        return $this->render('BrasaTransporteBundle:Movimientos/Recogida:detalle.html.twig', array(
                    'arRecogida' => $arRecogida,                    
                    'form' => $form->createView()
                    ));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTransporteBundle:TteRecogida')->listaDql($this->codigoRecogida);
    }

    private function filtrar ($form) {        
        $this->codigoRecogida = $form->get('TxtCodigo')->getData();
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
        $form = $this->createFormBuilder()      
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
                    ->setCellValue('B1', 'FECHA ANUNCIO')
                    ->setCellValue('C1', 'FECHA RECOGIDA')
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'UNIDADES')
                    ->setCellValue('F1', 'PESO REAL')
                    ->setCellValue('G1', 'PESO VOLUMEN')
                    ->setCellValue('H1', 'VALOR DECLARADO');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arRecogidas = new \Brasa\TransporteBundle\Entity\TteRecogida();
        $arRecogidas = $query->getResult();

        foreach ($arRecogidas as $arRecogida) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRecogida->getCodigoRecogidaPk())
                    ->setCellValue('B' . $i, $arRecogida->getFechaAnuncio()->format('Y-m-d'))
                    ->setCellValue('C' . $i, $arRecogida->getFechaRecogida()->format('Y-m-d'))
                    ->setCellValue('D' . $i, $arRecogida->getClienteRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arRecogida->getUnidades())
                    ->setCellValue('F' . $i, $arRecogida->getPesoReal())
                    ->setCellValue('G' . $i, $arRecogida->getPesoVolumen())
                    ->setCellValue('H' . $i, $arRecogida->getVrDeclarado());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Recogidas');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Recogidas.xlsx"');
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