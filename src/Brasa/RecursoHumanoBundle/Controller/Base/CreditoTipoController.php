<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCreditoTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhucreditoTipo controller.
 *
 */
class CreditoTipoController extends Controller
{
    
    /**
     * @Route("/rhu/base/creditotipo/listar", name="brs_rhu_base_creditotipo_listar")
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 37, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnPdf', SubmitType::class, array('label'  => 'PDF'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arCreditoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoTipo();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoCreditoTipoPk) {
                        $arCreditoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
                        $arCreditoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipo')->find($codigoCreditoTipoPk);
                        $em->remove($arCreditoTipo);
                    }
                    $em->flush();
                    } catch (ForeignKeyConstraintViolationException $e) {
                        $objMensaje->Mensaje('error', 'No se puede eliminar el tipo de credito porque esta siendo utilizado', $this);
                    }
            }
        
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoTipoCredito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoTipoCredito();
                $objFormatoTipoCredito->Generar($this);
        }    
        if($form->get('BtnExcel')->isClicked()) {
            ob_clean();
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
                            ->setCellValue('C1', 'CUPO MAXIMO')
                            ->setCellValue('D1', 'PAGO CONCEPTO');

                $i = 2;
                $arCreditoTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipo')->findAll();
                
                foreach ($arCreditoTipos as $arCreditoTipo) {
                    $pagoConcepto = "";
                    if ($arCreditoTipo->getCodigoPagoConceptoFk() != null){
                        $pagoConcepto = $arCreditoTipo->getPagoConceptoRel()->getNombre();
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCreditoTipo->getcodigoCreditoTipoPk())
                            ->setCellValue('B' . $i, $arCreditoTipo->getnombre())
                            ->setCellValue('C' . $i, $arCreditoTipo->getCupoMaximo())
                            ->setCellValue('D' . $i, $pagoConcepto);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Creditos_Tipos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="CreditosTipos.xlsx"');
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
        $arCreditoTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipo')->findAll();
        $arCreditoTipos = $paginator->paginate($query, $this->get('Request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/CreditoTipo:listar.html.twig', array(
                    'arCreditoTipos' => $arCreditoTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/creditotipo/nuevo/{codigoCreditoTipoPk}", name="brs_rhu_base_creditotipo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoCreditoTipoPk) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCreditoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoTipo();
        if ($codigoCreditoTipoPk != 0)
        {
            $arCreditoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipo')->find($codigoCreditoTipoPk);
        }    
        $form = $this->createForm(RhuCreditoTipoType::class, $arCreditoTipo); 
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arCreditoTipo = $form->getData();
            if ($form->get('pagoConceptoRel')->getData() == null){
                $objMensaje->Mensaje("error", "Se debe asociar en pago concepto al tipo de credito", $this);
            } else {
                $em->persist($arCreditoTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_base_creditotipo_listar'));
            }
            
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/CreditoTipo:nuevo.html.twig', array(
            'formCreditoTipo' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/rhu/base/creditotipo/detalle/{codigoCreditoTipoPk}", name="brs_rhu_base_creditotipo_detalle")
     */
    public function detalleAction(Request $request, $codigoCreditoTipoPk) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()    
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->getForm();
        $form->handleRequest($request);
        $arCreditosTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoTipo();
        $arCreditosTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipo')->find($codigoCreditoTipoPk);
        $arCreditosTiposEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditosTiposEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findBy(array('codigoCreditoTipoFk' => $codigoCreditoTipoPk));
        if($form->isValid()) {
                      
            if($form->get('BtnExcel')->isClicked())$em = $this->getDoctrine()->getManager();{
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

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Codigo_Credito')
                            ->setCellValue('B1', 'Tipo_Credito')
                            ->setCellValue('C1', 'Fecha_Credito')
                            ->setCellValue('D1', 'Centro_Costo')
                            ->setCellValue('E1', 'Empleado')
                            ->setCellValue('F1', 'Valor_Credito')
                            ->setCellValue('G1', 'Valor_Cuota')
                            ->setCellValue('H1', 'Valor_Seguro')
                            ->setCellValue('I1', 'Cuotas')
                            ->setCellValue('J1', 'Cuota_Actual')
                            ->setCellValue('K1', 'Pagado')
                            ->setCellValue('L1', 'Aprobado')
                            ->setCellValue('M1', 'Suspendido');

                $i = 2;
                
                foreach ($arCreditosTiposEmpleados as $arCreditosTiposEmpleados) {
                    if ($arCreditosTiposEmpleados->getEstadoPagado() == 1)
                    {
                        $Estado = "SI";
                    }
                    else
                    {
                        $Estado = "NO"; 
                    }
                    if ($arCreditosTiposEmpleados->getAprobado() == 1)
                    {
                        $Aprobado = "SI";
                    }
                    else
                    {
                        $Aprobado = "NO"; 
                    }
                    if ($arCreditosTiposEmpleados->getEstadoSuspendido() == 1)
                    {
                        $Suspendido = "SI";
                    }
                    else
                    {
                        $Suspendido = "NO"; 
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCreditosTiposEmpleados->getCodigoCreditoPk())
                            ->setCellValue('B' . $i, $arCreditosTiposEmpleados->getCreditoTipoRel()->getNombre())
                            ->setCellValue('C' . $i, $arCreditosTiposEmpleados->getFecha())
                            ->setCellValue('D' . $i, $arCreditosTiposEmpleados->getEmpleadoRel()->getCentroCostoRel()->getNombre())
                            ->setCellValue('E' . $i, $arCreditosTiposEmpleados->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('F' . $i, $arCreditosTiposEmpleados->getVrPagar())
                            ->setCellValue('G' . $i, $arCreditosTiposEmpleados->getVrCuota())
                            ->setCellValue('H' . $i, $arCreditosTiposEmpleados->getSeguro())
                            ->setCellValue('I' . $i, $arCreditosTiposEmpleados->getNumeroCuotas())
                            ->setCellValue('J' . $i, $arCreditosTiposEmpleados->getNumeroCuotaActual())
                            ->setCellValue('K' . $i, $Estado)
                            ->setCellValue('L' . $i, $Aprobado)
                            ->setCellValue('M' . $i, $Suspendido);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Creditos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Creditos.xlsx"');
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
        $arCreditosTiposEmpleados = $paginator->paginate($arCreditosTiposEmpleados, $this->get('Request')->query->get('page', 1),30);
        return $this->render('BrasaRecursoHumanoBundle:Base/CreditoTipo:detalle.html.twig', array(
                    'arCreditosTiposEmpleados' => $arCreditosTiposEmpleados,
                    'arCreditosTipo' => $arCreditosTipo,
                    'form' => $form->createView()
                    ));
    }
    
}
