<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCentroCostoType;

class CentroCostoController extends Controller
{
    
    /**
     * @Route("/rhu/base/centroscostos/lista", name="brs_rhu_base_centros_costos_lista")
     */ 
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 31, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = new session;
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroNombreCentroCosto')))
            ->add('BtnBuscar', SubmitType::class, array('label'  => 'Buscar'))
            ->add('BtnPdf', SubmitType::class, array('label'  => 'PDF',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnInactivar', SubmitType::class, array('label'  => 'Activa / Inactiva',))
            ->getForm();
        $form->handleRequest($request);
        $arCentrosCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();        
        if($form->isValid()) {
            if($form->get('BtnBuscar')->isClicked() || $form->get('BtnExcel')->isClicked()) {
                $session->set('dqlCentroCosto', $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL(
                    $form->get('TxtNombre')->getData()
                    ));                
                $session->set('filtroNombreCentroCosto', $form->get('TxtNombre')->getData());                
            }
            
            if($form->get('BtnPdf')->isClicked()) {
                $objFormatoCentroCostos = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCentroCostos();
                $objFormatoCentroCostos->Generar($this);
            }
            
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
            if($form->get('BtnInactivar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCentroCosto) {
                        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
                        $arContratosCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('codigoCentroCostoFk' =>$codigoCentroCosto, 'estadoActivo' => 1)); 
                        $douNumeroContratoActivos = count($arContratosCentroCosto);
                        if($arCentroCosto->getEstadoActivo() == 1){
                            if ($douNumeroContratoActivos == 0){
                                $arCentroCosto->setEstadoActivo(0);
                            }else {
                                echo "<script>alert('No se  puede inactivar, el centro de costo tiene contrato(s) abierto(s)');</script>";
                            }
                        } else {
                            $arCentroCosto->setEstadoActivo(1);
                        }
                        $em->persist($arCentroCosto);
                    }
                    $em->flush();
                }
            }
        } else {
            $session->set('dqlCentroCosto', $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL(
                    $session->get('filtroNombreCentroCosto')
                    ));                          
        }        
        $arCentrosCostos = $paginator->paginate($em->createQuery($session->get('dqlCentroCosto')), $this->get('Request')->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/CentroCosto:lista.html.twig', array(
            'arCentrosCostos' => $arCentrosCostos,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/base/centroscostos/nuevo/{codigoCentroCosto}", name="brs_rhu_base_centros_costos_nuevo")
     */ 
    public function nuevoAction(Request $request, $codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();  
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto->setFechaUltimoPagoProgramado(new \DateTime('now'));
        if($codigoCentroCosto != 0) {
            $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        } else {
            $arCentroCosto->setFechaUltimoPago(new \DateTime('now'));
            $arCentroCosto->setFechaUltimoPagoCesantias(new \DateTime('now'));
            $arCentroCosto->setFechaUltimoPagoPrima(new \DateTime('now'));            
            $arCentroCosto->setEstadoActivo(true);            
        }
        $form = $this->createForm(RhuCentroCostoType::class, $arCentroCosto); 
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arCentroCosto = $form->getData();
            $strDia = $arCentroCosto->getFechaUltimoPagoProgramado()->format('d');
            $strMes = $arCentroCosto->getFechaUltimoPagoProgramado()->format('m');
            $strPeriodo = $arCentroCosto->getPeriodoPagoRel()->getCodigoPeriodoPagoPk();
            if ($codigoCentroCosto == 0){
                $arCentroCosto->setCodigoUsuario($arUsuario->getUserName());
            }
            if ($strPeriodo == 2 && ($strDia != 10 && $strDia != 20 && $strDia != 30 && $strMes != 2)) {
                $objMensaje->Mensaje("error", "El periodo debe terminar en dias 10, 20 o 30", $this);
            } else {
                if($strPeriodo == 4 && ($strDia != 15 && $strDia != 30 && $strMes != 2) ){
                    $objMensaje->Mensaje("error", "El periodo debe terminar en dias 15 o 30", $this);    
                } else {
                    if($strPeriodo == 5 && $strDia != 30 && $strMes != 2){
                        $objMensaje->Mensaje("error", "El periodo debe terminar en dia 30", $this);
                    }
                    else {     
                        $arCentroCosto->setFechaUltimoPago($arCentroCosto->getFechaUltimoPagoProgramado());
                        $em->persist($arCentroCosto);
                        $em->flush();
                        if($request->request->get('ChkGenerarPeriodo')) {
                            if($codigoCentroCosto == 0) {
                                $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarProgramacionPago($arCentroCosto->getCodigoCentroCostoPk(), 1);                                
                            }                            
                        }
                        if($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_base_centros_costos_nuevo', array('codigoCentroCosto' => 0)));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_base_centros_costos_lista'));
                        }                         
                    }
                }                
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Base/CentroCosto:nuevo.html.twig', array(
            'arCentroCosto' => $arCentroCosto,            
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/base/centroscostos/detalle/{codigoCentroCosto}", name="brs_rhu_base_centros_costos_detalle")
     */ 
    public function detalleAction(Request $request, $codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()               
            ->getForm();
        $form->handleRequest($request);
        $arSedes = new \Brasa\RecursoHumanoBundle\Entity\RhuSede();
        $arSedes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSede')->findBy(array('codigoCentroCostoFk' => $codigoCentroCosto));
        $arSedes = $paginator->paginate($arSedes, $this->get('Request')->query->get('page', 1),5);
        $arCentrosCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentrosCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        return $this->render('BrasaRecursoHumanoBundle:Base/CentroCosto:detalle.html.twig', array(
            'arSedes' => $arSedes,        
            'arCentrosCostos' => $arCentrosCostos,
            'form' => $form->createView()
                    ));
    }    
    
    private function generarExcel() {
         $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
                            ->setCellValue('B1', 'NOMBRE')
                            ->setCellValue('C1', 'CIUDAD')
                            ->setCellValue('D1', 'PERIODO')
                            ->setCellValue('E1', 'ABIERTO')
                            ->setCellValue('F1', 'GENERA SERV POR COBRAR')
                            ->setCellValue('G1', 'ULT PAGO')
                            ->setCellValue('H1', 'ULT PAGO PRIMA')
                            ->setCellValue('I1', 'ULT PAGO CESANTIAS');

                $i = 2;
                $arCentrosCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
                foreach ($arCentrosCostos as $arCentroCosto) {
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCentroCosto->getCodigoCentroCostoPk())
                            ->setCellValue('B' . $i, $arCentroCosto->getNombre())
                            ->setCellValue('C' . $i, $arCentroCosto->getCiudadRel()->getNombre())
                            ->setCellValue('D' . $i, $arCentroCosto->getPeriodoPagoRel()->getNombre())
                            ->setCellValue('E' . $i, $objFunciones->devuelveBoolean($arCentroCosto->getPagoAbierto()))
                            ->setCellValue('F' . $i, $objFunciones->devuelveBoolean($arCentroCosto->getGeneraServicioCobrar()))
                            ->setCellValue('G' . $i, $arCentroCosto->getFechaUltimoPago()->format('Y-m-d'))
                            ->setCellValue('H' . $i, $arCentroCosto->getFechaUltimoPagoPrima()->format('Y-m-d'))
                            ->setCellValue('I' . $i, $arCentroCosto->getFechaUltimoPagoCesantias()->format('Y-m-d'));
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('ccostos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="CentrosCostos.xlsx"');
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
