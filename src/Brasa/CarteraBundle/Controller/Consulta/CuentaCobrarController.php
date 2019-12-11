<?php
namespace Brasa\CarteraBundle\Controller\Consulta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class CuentaCobrarController extends Controller
{
    var $strListaDql = "";
    var $strFechaDesde = "";
    var $strFechaHasta = "";

    /**
     * @Route("/cartera/consulta/cuentacobrar/lista", name="brs_cartera_consulta_cuentacobrar_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 50)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        //$this->estadoAnulado = 0;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $strWhere = "";
        if ($form->isValid()) {
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->formularioFiltro();
                $strWhere .= $this->devFiltro($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $strWhere .= $this->devFiltro($form);
                $this->generarExcel($strWhere);
            }
            if ($form->get('BtnPdf')->isClicked()) {
                $strWhere .= $this->devFiltro($form);
                $objEstadoCuenta = new \Brasa\CarteraBundle\Formatos\EstadoCuenta();
                $objEstadoCuenta->Generar($this, $strWhere);
            }            
        }
        $connection = $em->getConnection();
        $strSql = "SELECT  
                            sql_car_cartera_edades.*
                    FROM
                            sql_car_cartera_edades                       
                    WHERE 1 " . $strWhere;                    
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $resultados = $statement->fetchAll();        
        return $this->render('BrasaCarteraBundle:Consultas/CuentasCobrar:lista.html.twig', array(            
            'arCuentasCobrar' => $resultados,
            'form' => $form->createView()));
    }

    private function devFiltro($form) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strWhere = "";
        $arTipo = $form->get('cuentaCobrarTipoRel')->getData();  
        if($arTipo) {
           $strWhere .= " AND codigoCuentaCobrarTipoFk = " . $arTipo->getCodigoCuentaCobrarTipoPk(); 
        }
        $arAsesor = $form->get('asesorRel')->getData();  
        if($arAsesor) {
           $strWhere .= " AND codigoAsesorFk = " . $arAsesor->getCodigoAsesorPk(); 
        }  
        $intRango = $form->get('rango')->getData();
        if($intRango != 0) {
            $strWhere .= " AND rango = " . $intRango;
        } 
        $nit = $form->get('TxtNit')->getData();
        if($nit != "") {            
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $nit));
            if($arCliente) {            
                   $strWhere .= " AND codigo_cliente_fk = " . $arCliente->getCodigoClientePk(); 
            }            
        }
        $fecha = $form->get('fechaDesde')->getData();
        if ($fecha){
            $fecha = $fecha->format('Y-m-d');
            $strWhere .= " AND fecha >= " . $fecha;
        }
        $fecha = $form->get('fechaHasta')->getData();
        if ($fecha){
            $fecha = $fecha->format('Y-m-d');
            $strWhere .= " AND fecha <= " . $fecha;
        }
        return $strWhere;
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }
        } else {
            $session->set('filtroCodigoCliente', null);
        }
        $arrayPropiedades = array(
                'class' => 'BrasaCarteraBundle:CarCuentaCobrarTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCuentaCobrarTipo')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaCarteraBundle:CarCuentaCobrarTipo", $session->get('filtroCuentaCobrarTipo'));
        }
        $arrayPropiedadesAsesor = array(
                'class' => 'BrasaGeneralBundle:GenAsesor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                    ->orderBy('a.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );        
        if($session->get('filtroAsesor')) {
            $arrayPropiedadesAsesor['data'] = $em->getReference("BrasaGeneralBundle:GenAsesor", $session->get('filtroAsesor'));
        }        
        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroPedidoNumero')))
            ->add('cuentaCobrarTipoRel', 'entity', $arrayPropiedades)
            ->add('asesorRel', 'entity', $arrayPropiedadesAsesor)
            ->add('rango', 'choice', array('choices' => array('0' => 'TODOS','30' => '1 - 30', '60' => '31 - 60', '90' => '61 - 90', '180' => '91 - 180')))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date')))            
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function generarExcel($strWhere) {
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
        for($col = 'A'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for($col = 'J'; $col !== 'M'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'TIPO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'VENCE')
                    ->setCellValue('F1', 'SOPORTE')
                    ->setCellValue('G1', 'NIT')
                    ->setCellValue('H1', 'CLIENTE')
                    ->setCellValue('I1', 'ASESOR')
                    ->setCellValue('J1', 'VALOR')
                    ->setCellValue('K1', 'SALDO')                    
                    ->setCellValue('L1', 'ABONO')
                    ->setCellValue('M1', 'PLAZO')
                    ->setCellValue('N1', 'VENCIMIENTO')
                    ->setCellValue('O1', 'DIAS')
                    ->setCellValue('P1', 'RANGO')
                    ->setCellValue('Q1', 'GRUPO')
                    ->setCellValue('R1', 'SUBGRUPO');

        $i = 2;        
        $connection = $em->getConnection();
        $strSql = "SELECT  
                            sql_car_cartera_edades.*
                    FROM
                            sql_car_cartera_edades                       
                    WHERE 1 " . $strWhere;                    
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $arCuentasCobrar = $statement->fetchAll();
        foreach ($arCuentasCobrar as $arCuentasCobrar) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCuentasCobrar['codigoCuentaCobrarPk'])
                    ->setCellValue('B' . $i, $arCuentasCobrar['numeroDocumento'])
                    ->setCellValue('C' . $i, $arCuentasCobrar['tipoCuentaCobrar'])
                    ->setCellValue('D' . $i, $arCuentasCobrar['fecha'])
                    ->setCellValue('E' . $i, $arCuentasCobrar['fechaVence'])
                    ->setCellValue('F' . $i, $arCuentasCobrar['soporte'])
                    ->setCellValue('G' . $i, $arCuentasCobrar['nitCliente'])
                    ->setCellValue('H' . $i, $arCuentasCobrar['nombreCliente'])
                    ->setCellValue('I' . $i, $arCuentasCobrar['nombreAsesor'])
                    ->setCellValue('J' . $i, $arCuentasCobrar['valorOriginal'])
                    ->setCellValue('K' . $i, $arCuentasCobrar['saldo'])
                    ->setCellValue('L' . $i, $arCuentasCobrar['abono'])
                    ->setCellValue('M' . $i, $arCuentasCobrar['plazo'])
                    ->setCellValue('N' . $i, $arCuentasCobrar['tipoVencimiento'])
                    ->setCellValue('O' . $i, $arCuentasCobrar['diasVencida'])
                    ->setCellValue('P' . $i, $arCuentasCobrar['rango'])
                    ->setCellValue('Q' . $i, $arCuentasCobrar['grupo'])
                    ->setCellValue('R' . $i, $arCuentasCobrar['subgrupo']);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('CuentasCobrar');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CuentasCobrar.xlsx"');
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