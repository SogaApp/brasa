<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class MediosMagneticosController extends Controller
{
    /**
     * @Route("/rhu/utilidades/mediosmagneticos/informe", name="brs_rhu_utilidades_medios_magneticos_informe")
     */           
    public function InformeAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 78)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $fechaActual = date('Y-m-j');
        $anioActual = date('Y');
        $fechaPrimeraAnterior = strtotime ( '-1 year' , strtotime ( $fechaActual ) ) ;
        $fechaPrimeraAnterior = date ( 'Y' , $fechaPrimeraAnterior );
        $fechaSegundaAnterior = strtotime ( '-2 year' , strtotime ( $fechaActual ) ) ;
        $fechaSegundaAnterior = date ( 'Y' , $fechaSegundaAnterior );
        $fechaTerceraAnterior = strtotime ( '-3 year' , strtotime ( $fechaActual ) ) ;
        $fechaTerceraAnterior = date ( 'Y' , $fechaTerceraAnterior );
        $form = $this->createFormBuilder()
            ->add('BtnGenerarArchivo', 'submit', array('label'  => 'Generar archivo',))
            ->add('fechaProceso', 'choice', array('choices' => array($anioActual = date('Y') => $anioActual = date('Y'),$fechaPrimeraAnterior => $fechaPrimeraAnterior, $fechaSegundaAnterior => $fechaSegundaAnterior, $fechaTerceraAnterior => $fechaTerceraAnterior),))    
            ->add('formatos', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuMedioMagneticoExogena',
                'property' => 'formato',
            ))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGenerarArchivo')->isClicked()) {
                $arFormato = new \Brasa\RecursoHumanoBundle\Entity\RhuMedioMagneticoExogena();
                $arFormato = $em->getRepository('BrasaRecursoHumanoBundle:RhuMedioMagneticoExogena')->find($form->get('formatos')->getData());
                if($arFormato->getCodigoFormato() == 1001) {
                    $this->generarFormato1001Excel();
                }
                if($arFormato->getCodigoFormato() == 1003) {
                    $this->generarFormato1003Excel();
                }
                if($arFormato->getCodigoFormato() == 1005) {
                    $this->generarFormato1005Excel();
                }
                if($arFormato->getCodigoFormato() == 1006) {
                    $this->generarFormato1006Excel();
                }
                if($arFormato->getCodigoFormato() == 1007) {
                    $this->generarFormato1007Excel();
                }
                if($arFormato->getCodigoFormato() == 1008) {
                    $this->generarFormato1008Excel();
                }
                if($arFormato->getCodigoFormato() == 1009) {
                    $this->generarFormato1009Excel();
                }
                if($arFormato->getCodigoFormato() == 1010) {
                    $this->generarFormato1010Excel();
                }
                if($arFormato->getCodigoFormato() == 1011) {
                    $this->generarFormato1011Excel();
                }
                if($arFormato->getCodigoFormato() == 1012) {
                    $this->generarFormato1012Excel();
                }
                
            }   
        }
                
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/MediosMagneticos:Informe.html.twig', array(
                'form' => $form->createView() 
                ));
    } 
    
    private function generarFormato1001Excel() {
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
                            ->setCellValue('A1', 'Concepto')
                            ->setCellValue('B1', 'Tipo de Documento')
                            ->setCellValue('C1', 'Número Identificación')
                            ->setCellValue('D1', 'Primer apellido del informado')
                            ->setCellValue('E1', 'Segundo apellido del informado')
                            ->setCellValue('F1', 'Primer nombre del informado')
                            ->setCellValue('G1', 'Otros nombres del informado')
                            ->setCellValue('H1', 'Razón social informado')
                            ->setCellValue('I1', 'Dirección')
                            ->setCellValue('J1', 'Codigo departamento')
                            ->setCellValue('K1', 'Codigo municipio')
                            ->setCellValue('L1', 'País de Residencia o domicilio')
                            ->setCellValue('M1', 'Pago o abono en cuenta deducible')
                            ->setCellValue('N1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('O1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('P1', 'IVA mayor valor del costo o gasto deducible')
                            ->setCellValue('Q1', 'IVA mayor valor del costo o gasto no deducible')
                            ->setCellValue('R1', 'Retención en la fuente practicada Renta')
                            ->setCellValue('S1', 'Retención en la fuente asumida Renta')
                            ->setCellValue('T1', 'Retención en la fuente practicada Iva Régimen común')
                            ->setCellValue('U1', 'Retención en la fuente asumida IVA Régimen Simplificado')
                            ->setCellValue('V1', 'Retención en la fuente practicada Iva no domiciliados')
                            ->setCellValue('W1', 'Retención en la fuente practicadas  CREE')
                            ->setCellValue('X1', 'Retención en la fuente asumidas CREE');

                $i = 2;
                $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arEmpleados as $arEmpleados) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "5001")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, $arEmpleados->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arEmpleados->getApellido1())
                            ->setCellValue('E' . $i, $arEmpleados->getApellido2())
                            ->setCellValue('F' . $i, $arEmpleados->getNombre1())
                            ->setCellValue('G' . $i, $arEmpleados->getNombre2())
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arEmpleados->getDireccion())
                            ->setCellValue('J' . $i, $arEmpleados->getCiudadRel()->getCodigoDepartamentoFk())
                            ->setCellValue('K' . $i, $arEmpleados->getCodigoCiudadFk())
                            ->setCellValue('L' . $i, "169")
                            ->setCellValue('M' . $i, $arEmpleados->getCuenta())
                            ->setCellValue('N' . $i, "0")
                            ->setCellValue('O' . $i, "0")
                            ->setCellValue('P' . $i, "0")
                            ->setCellValue('Q' . $i, "0")
                            ->setCellValue('R' . $i, "0")
                            ->setCellValue('S' . $i, "0")
                            ->setCellValue('T' . $i, "0")
                            ->setCellValue('U' . $i, "0")
                            ->setCellValue('V' . $i, "0")
                            ->setCellValue('W' . $i, "0")
                            ->setCellValue('X' . $i, "0");
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1001.xlsx"');
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
    
    private function generarFormato1003Excel() {
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
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CONCEPTO')
                            ->setCellValue('B1', 'TIPO DE DOCUMENTO')
                            ->setCellValue('C1', 'NÚMERO IDENTIFICACIÓN')
                            ->setCellValue('D1', 'DV')
                            ->setCellValue('E1', 'SEGUNDO APELLIDO DEL INFORMADO')
                            ->setCellValue('F1', 'PRIMER APELLIDO DEL INFORMADO')
                            ->setCellValue('G1', 'PRIMER NOMBRE DEL INFORMADO')
                            ->setCellValue('H1', 'OTROS NOMBRES DEL INFORMADO')
                            ->setCellValue('I1', 'RAZÓN SOCIAL INFORMADO')
                            ->setCellValue('J1', 'DIRECCIÓN')
                            ->setCellValue('K1', 'CÓDIGO DEPARTAMENTO')
                            ->setCellValue('L1', 'CÓDIGO MUNICIPIO')
                            ->setCellValue('M1', 'VALOR ACUMULADO DEL PAGO O ABONO SUJETO A RETENCIÓN EN LA FUENTE')
                            ->setCellValue('N1', 'RETENCIÓN EN LA FUENTE QUE LE PRACTICARON');

                $i = 2;
                $arCentroCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                $arCentroCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arCentroCostos as $arCentroCosto) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "1303")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, "nit o cedula")
                            ->setCellValue('D' . $i, "DV")
                            ->setCellValue('E' . $i, "")
                            ->setCellValue('F' . $i, "")
                            ->setCellValue('G' . $i, "")
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arCentroCosto->getNombre())
                            ->setCellValue('J' . $i, "DIRECCION RAZON SOCIAL")
                            ->setCellValue('K' . $i, "COD DEP RAZON S")
                            ->setCellValue('L' . $i, "COD MUNIC RAZON S")
                            ->setCellValue('M' . $i, "VALOR ACUMULADO")
                            ->setCellValue('N' . $i, "RETENCION");
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1003.xlsx"');
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
    
    private function generarFormato1005Excel() {
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
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'TIPO DE DOCUMENTO')
                            ->setCellValue('B1', 'NÚMERO IDENTIFICACIÓN')
                            ->setCellValue('C1', 'DV')
                            ->setCellValue('D1', 'PRIMER APELLIDO DEL INFORMADO')
                            ->setCellValue('E1', 'SEGUNDO APELLIDO DEL INFORMADO')
                            ->setCellValue('F1', 'PRIMER NOMBRE DEL INFORMADO')
                            ->setCellValue('G1', 'OTROS NOMBRES DEL INFORMADO')
                            ->setCellValue('H1', 'RAZÓN SOCIAL INFORMADO')
                            ->setCellValue('I1', 'IMPUESTO DESCONTABLE')
                            ->setCellValue('J1', 'IVA RESULTANTE POR DEVOLUCIONES EN VENTAS ANULADAS, RESCINDIDAS O RESUELTAS');

                $i = 2;
                $arCentroCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                $arCentroCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arCentroCostos as $arCentroCosto) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "13")
                            ->setCellValue('B' . $i, "nit o cedula")
                            ->setCellValue('C' . $i, "DV")
                            ->setCellValue('D' . $i, "")
                            ->setCellValue('E' . $i, "")
                            ->setCellValue('F' . $i, "")
                            ->setCellValue('G' . $i, "")
                            ->setCellValue('H' . $i, $arCentroCosto->getNombre())
                            ->setCellValue('I' . $i, 0)
                            ->setCellValue('J' . $i, 0);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1005.xlsx"');
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
    
    private function generarFormato1006Excel() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $objPHPExcel = new \PHPExcel();
                // Set document properties
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'TIPO DE DOCUMENTO')
                            ->setCellValue('B1', 'NÚMERO IDENTIFICACIÓN')
                            ->setCellValue('C1', 'DV')
                            ->setCellValue('D1', 'PRIMER APELLIDO DEL INFORMADO')
                            ->setCellValue('E1', 'SEGUNDO APELLIDO DEL INFORMADO')
                            ->setCellValue('F1', 'PRIMER NOMBRE DEL INFORMADO')
                            ->setCellValue('G1', 'OTROS NOMBRES DEL INFORMADO')
                            ->setCellValue('H1', 'RAZÓN SOCIAL INFORMADO')
                            ->setCellValue('I1', 'IMPUESTO GENERADO')
                            ->setCellValue('J1', 'IVA RECUPERADO POR OPERACIONES EN DEVOLUCIONES EN COMPRAS ANULADAS, RESCINDIDAAS O RESUELTAS')
                            ->setCellValue('K1', 'IMPUESTO AL CONSUMO');

                $i = 2;
                $arCentroCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                $arCentroCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arCentroCostos as $arCentroCosto) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "13")
                            ->setCellValue('B' . $i, "nit o cedula")
                            ->setCellValue('C' . $i, "DV")
                            ->setCellValue('D' . $i, "")
                            ->setCellValue('E' . $i, "")
                            ->setCellValue('F' . $i, "")
                            ->setCellValue('G' . $i, "")
                            ->setCellValue('H' . $i, $arCentroCosto->getNombre())
                            ->setCellValue('I' . $i, 0)
                            ->setCellValue('J' . $i, 0)
                            ->setCellValue('K' . $i, 0);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1006.xlsx"');
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
    
    private function generarFormato1007Excel() {
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
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CONCEPTO')
                            ->setCellValue('B1', 'TIPO DE DOCUMENTO')
                            ->setCellValue('C1', 'NÚMERO IDENTIFICACIÓN')
                            ->setCellValue('D1', 'DV')
                            ->setCellValue('E1', 'PRIMER APELLIDO DEL INFORMADO')
                            ->setCellValue('F1', 'SEGUNDO APELLIDO DEL INFORMADO')
                            ->setCellValue('G1', 'PRIMER NOMBRE DEL INFORMADO')
                            ->setCellValue('H1', 'OTROS NOMBRES DEL INFORMADO')
                            ->setCellValue('I1', 'RAZÓN SOCIAL INFORMADO')
                            ->setCellValue('J1', 'CÓDIGO PAIS')
                            ->setCellValue('K1', 'INGRESOS BRUTOS RECIBIDOS POR OPERACIONES PROPIAS')
                            ->setCellValue('L1', 'INGRESOS BRUTOS A TRAVES DE CONSORCIOS O UNIONES TEMPORALES')
                            ->setCellValue('M1', 'INGRESOS A TRAVES DE CONTRATOS DE MANDATO O ADMON DELEGADA')
                            ->setCellValue('N1', 'INGRESOS A TRAVES DE EXPLORACIÓN Y EXPLOTACIÓN DE MINERALES')
                            ->setCellValue('O1', 'INGRESOS A TRAVES DE FIDUCIA')
                            ->setCellValue('P1', 'INGRESOS A TRAVES DE TERCEROS')
                            ->setCellValue('Q1', 'DEVOLUCIONES, REBAJAS Y DESCUENTOS');

                $i = 2;
                $arCentroCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                $arCentroCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arCentroCostos as $arCentroCosto) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "4001")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, "nit o cedula")
                            ->setCellValue('D' . $i, "DV")
                            ->setCellValue('E' . $i, "")
                            ->setCellValue('F' . $i, "")
                            ->setCellValue('G' . $i, "")
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arCentroCosto->getNombre())
                            ->setCellValue('J' . $i, 169)
                            ->setCellValue('K' . $i, 0)
                            ->setCellValue('L' . $i, 0)
                            ->setCellValue('M' . $i, 0)
                            ->setCellValue('N' . $i, 0)
                            ->setCellValue('O' . $i, 0)
                            ->setCellValue('P' . $i, 0)
                            ->setCellValue('Q' . $i, 0);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1007.xlsx"');
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
    
    private function generarFormato1008Excel() {
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
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CONCEPTO')
                            ->setCellValue('B1', 'TIPO DE DOCUMENTO')
                            ->setCellValue('C1', 'NÚMERO IDENTIFICACIÓN')
                            ->setCellValue('D1', 'DV')
                            ->setCellValue('E1', 'PRIMER APELLIDO DEL INFORMADO')
                            ->setCellValue('F1', 'SEGUNDO APELLIDO DEL INFORMADO')
                            ->setCellValue('G1', 'PRIMER NOMBRE DEL INFORMADO')
                            ->setCellValue('H1', 'OTROS NOMBRES DEL INFORMADO')
                            ->setCellValue('I1', 'RAZÓN SOCIAL INFORMADO')
                            ->setCellValue('J1', 'DIRECCIÓN')
                            ->setCellValue('K1', 'CÓDIGO DEPARTAMENTO')
                            ->setCellValue('L1', 'CÓDIGO MUNICIPIO')
                            ->setCellValue('M1', 'CÓDIGO PAIS')
                            ->setCellValue('N1', 'SALDOS CUENTAS POR COBRAR AL 31 DICIEMBRE');

                $i = 2;
                $arCentroCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                $arCentroCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arCentroCostos as $arCentroCosto) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "1315")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, "nit o cedula")
                            ->setCellValue('D' . $i, "DV")
                            ->setCellValue('E' . $i, "")
                            ->setCellValue('F' . $i, "")
                            ->setCellValue('G' . $i, "")
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arCentroCosto->getNombre())
                            ->setCellValue('J' . $i, "DIRECCIÓN")
                            ->setCellValue('K' . $i, "CODIGO DEPARTAMENTO")
                            ->setCellValue('L' . $i, "CODIGO MUNICIPIO")
                            ->setCellValue('M' . $i, 169)
                            ->setCellValue('N' . $i, 0);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1008.xlsx"');
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
    
    private function generarFormato1009Excel() {
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
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CONCEPTO')
                            ->setCellValue('B1', 'TIPO DE DOCUMENTO')
                            ->setCellValue('C1', 'NÚMERO IDENTIFICACIÓN')
                            ->setCellValue('D1', 'DV')
                            ->setCellValue('E1', 'PRIMER APELLIDO DEL INFORMADO')
                            ->setCellValue('F1', 'SEGUNDO APELLIDO DEL INFORMADO')
                            ->setCellValue('G1', 'PRIMER NOMBRE DEL INFORMADO')
                            ->setCellValue('H1', 'OTROS NOMBRES DEL INFORMADO')
                            ->setCellValue('I1', 'RAZÓN SOCIAL INFORMADO')
                            ->setCellValue('J1', 'DIRECCIÓN')
                            ->setCellValue('K1', 'CÓDIGO DEPARTAMENTO')
                            ->setCellValue('L1', 'CÓDIGO MUNICIPIO')
                            ->setCellValue('M1', 'CÓDIGO PAIS')
                            ->setCellValue('N1', 'SALDOS CUENTAS POR PAGAR AL 31 DICIEMBRE');

                $i = 2;
                $arCentroCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                $arCentroCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arCentroCostos as $arCentroCosto) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "1315")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, "nit o cedula")
                            ->setCellValue('D' . $i, "DV")
                            ->setCellValue('E' . $i, "")
                            ->setCellValue('F' . $i, "")
                            ->setCellValue('G' . $i, "")
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arCentroCosto->getNombre())
                            ->setCellValue('J' . $i, "DIRECCIÓN")
                            ->setCellValue('K' . $i, "CODIGO DEPARTAMENTO")
                            ->setCellValue('L' . $i, "CODIGO MUNICIPIO")
                            ->setCellValue('M' . $i, 169)
                            ->setCellValue('N' . $i, 0);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1009.xlsx"');
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
    
    private function generarFormato1010Excel() {
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
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'TIPO DE DOCUMENTO')
                            ->setCellValue('B1', 'NÚMERO IDENTIFICACIÓN')
                            ->setCellValue('C1', 'DV')
                            ->setCellValue('D1', 'PRIMER APELLIDO DEL INFORMADO')
                            ->setCellValue('E1', 'SEGUNDO APELLIDO DEL INFORMADO')
                            ->setCellValue('F1', 'PRIMER NOMBRE DEL INFORMADO')
                            ->setCellValue('G1', 'OTROS NOMBRES DEL INFORMADO')
                            ->setCellValue('H1', 'RAZÓN SOCIAL INFORMADO')
                            ->setCellValue('I1', 'DIRECCIÓN')
                            ->setCellValue('J1', 'CÓDIGO DEPARTAMENTO')
                            ->setCellValue('K1', 'CÓDIGO MUNICIPIO')
                            ->setCellValue('L1', 'CÓDIGO PAIS')
                            ->setCellValue('M1', 'VALOR PATRIMONIAL ACCIONES A APORTES AL 31 DICIEMBRE')
                            ->setCellValue('N1', 'PORCENTAJE DE PARTICIPACIÓN')
                            ->setCellValue('O1', 'PORCENTAJE DE PARTICIPACIÓN (POSICIÓN DECIMAL)');

                $i = 2;
                $arCentroCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                $arCentroCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arCentroCostos as $arCentroCosto) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "13")
                            ->setCellValue('B' . $i, "nit o cedula")
                            ->setCellValue('C' . $i, "DV")
                            ->setCellValue('D' . $i, "")
                            ->setCellValue('E' . $i, "")
                            ->setCellValue('F' . $i, "")
                            ->setCellValue('G' . $i, "")
                            ->setCellValue('H' . $i, $arCentroCosto->getNombre())
                            ->setCellValue('I' . $i, "DIRECCIÓN")
                            ->setCellValue('J' . $i, "CODIGO DEPARTAMENTO")
                            ->setCellValue('K' . $i, "CODIGO MUNICIPIO")
                            ->setCellValue('L' . $i, 169)
                            ->setCellValue('M' . $i, 0)
                            ->setCellValue('N' . $i, 0)
                            ->setCellValue('O' . $i, 0);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1010.xlsx"');
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
    
    private function generarFormato1011Excel() {
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
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CONCEPTO')
                            ->setCellValue('B1', 'SALDOS AL 31 DICIEMBRE')
                            ->setCellValue('C1', 'DESCRIPCIÓN');

                //$i = 2;
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . 2, "8207")
                            ->setCellValue('A' . 3, "8211")
                            ->setCellValue('A' . 4, "8220")
                            ->setCellValue('A' . 5, "8228")
                            ->setCellValue('A' . 6, "8233")
                            ->setCellValue('A' . 7, "8234")
                            ->setCellValue('A' . 8, "8237")
                            ->setCellValue('A' . 9, "8241")
                            ->setCellValue('A' . 10, "8242")
                            ->setCellValue('A' . 11, "8243")
                            ->setCellValue('A' . 12, "8245")
                            ->setCellValue('A' . 13, "8259")
                            ->setCellValue('B' . 2, 0)
                            ->setCellValue('B' . 3, 0)
                            ->setCellValue('B' . 4, 0)
                            ->setCellValue('B' . 5, 0)
                            ->setCellValue('B' . 6, 0)
                            ->setCellValue('B' . 7, 0)
                            ->setCellValue('B' . 8, 0)
                            ->setCellValue('B' . 9, 0)
                            ->setCellValue('B' . 10, 0)
                            ->setCellValue('B' . 11, 0)
                            ->setCellValue('B' . 12, 0)
                            ->setCellValue('B' . 13, 0)
                            ->setCellValue('B' . 14, 0)
                            ->setCellValue('C' . 2, "Deducción por salarios, y demás pagos laborales")
                            ->setCellValue('C' . 3, "Deducción por gravamen a los movimientos financieros")
                            ->setCellValue('C' . 4, "Deducción por donación a asociaciones, corporaciones y fundaciones sin animo de lucro, Núm. 2 Art. 125 ET")
                            ->setCellValue('C' . 5, "Costo o deducción por  reparaciones locativas realizadas sobre inmuebles")
                            ->setCellValue('C' . 6, "Deducción por impuestos pagados")
                            ->setCellValue('C' . 7, "Deducción o costos por intereses, Art 117 ET")
                            ->setCellValue('C' . 8, "Deducción por publicidad y propaganda")
                            ->setCellValue('C' . 9, "ICBF")
                            ->setCellValue('C' . 10, "Deducción o costo por aportes a CCF Cajas de Compensación Familiar")
                            ->setCellValue('C' . 11, "SENA")
                            ->setCellValue('C' . 12, "Deducción  por cesantías efectivamente pagadas o reconocidas al trabajador")
                            ->setCellValue('C' . 13, "Deducción por tasas y contribuciones fiscales pagadas");

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1011.xlsx"');
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
    
    private function generarFormato1012Excel() {
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
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CONCEPTO')
                            ->setCellValue('B1', 'TIPO DE DOCUMENTO')
                            ->setCellValue('C1', 'NÚMERO IDENTIFICACIÓN')
                            ->setCellValue('D1', 'DV')
                            ->setCellValue('E1', 'PRIMER APELLIDO DEL INFORMADO')
                            ->setCellValue('F1', 'SEGUNDO APELLIDO DEL INFORMADO')
                            ->setCellValue('G1', 'PRIMER NOMBRE DEL INFORMADO')
                            ->setCellValue('H1', 'OTROS NOMBRES DEL INFORMADO')
                            ->setCellValue('I1', 'RAZÓN SOCIAL INFORMADO')
                            ->setCellValue('J1', 'CÓDIGO PAIS')
                            ->setCellValue('K1', 'VALOR AL 31 DICIEMBRE');

                $i = 2;
                $arCentroCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                $arCentroCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arCentroCostos as $arCentroCosto) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "1110")
                            ->setCellValue('B' . $i, "31")
                            ->setCellValue('C' . $i, "NIT O CEDULA")
                            ->setCellValue('D' . $i, "DV")
                            ->setCellValue('E' . $i, "")
                            ->setCellValue('F' . $i, "")
                            ->setCellValue('G' . $i, "")
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arCentroCosto->getNombre())
                            ->setCellValue('J' . $i, 169)
                            ->setCellValue('K' . $i, 0);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1012.xlsx"');
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
