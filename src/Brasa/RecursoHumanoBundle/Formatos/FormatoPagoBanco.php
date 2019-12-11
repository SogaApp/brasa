<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoPagoBanco extends \FPDF_FPDF {
    public static $em;
    public static $codigoPagoBanco;
    
    public function Generar($miThis, $codigoPagoBanco) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoPagoBanco = $codigoPagoBanco;
        $pdf = new FormatoPagoBanco();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("PagoBanco$codigoPagoBanco.pdf", 'D');                
    } 
    
    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(6);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        
        $this->Image('imagenes/logos/logo.jpg', 12, 13, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->SetXY(50, 10);
        $this->Cell(150, 7, utf8_decode("PAGO BANCO"), 0, 0, 'C', 1);
        $this->SetXY(50, 18);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);        
        //
        $arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arPagoBanco = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find(self::$codigoPagoBanco);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200); 
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272); 
        $this->SetFont('Arial','',8);
        $this->Cell(30, 6, $arPagoBanco->getCodigoPagoBancoPk(), 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "BANCO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272); 
        $this->Cell(100, 6, utf8_decode($arPagoBanco->getCuentaRel()->getBancoRel()->getNombre()), 1, 0, 'L', 1);
        //linea 2
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("REGISTROS:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, $arPagoBanco->getNumeroRegistros() , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "DESCRIPCION:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->SetFillColor(272, 272, 272); 
        $this->Cell(100, 5, $arPagoBanco->getDescripcion() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        
        //linea 3
        $this->SetXY(10, 50);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("TIPO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, $arPagoBanco->getPagoBancoTipoRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "TOTAL:" , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272); 
        $this->Cell(100, 5, number_format($arPagoBanco->getVrTotalPago(), 0, '.', ',') , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array(utf8_decode('CÓDIGO'),'TIPO PAGO', utf8_decode('IDENTIFICACIÓN'), 'NOMBRE', 'VR PAGO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(20, 25,25, 105, 15);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $arPagoBancoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
        $arPagoBancoDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array('codigoPagoBancoFk' => self::$codigoPagoBanco));
        
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $var = 0;
        foreach ($arPagoBancoDetalles as $arPagoBancoDetalle) {            
            $pdf->Cell(20, 4, $arPagoBancoDetalle->getCodigoPagoBancoDetallePk(), 1, 0, 'L');
            $pdf->Cell(25, 4, $arPagoBancoDetalle->getPagoBancoRel()->getPagoBancoTipoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(25, 4, $arPagoBancoDetalle->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(105, 4, utf8_decode($arPagoBancoDetalle->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(15, 4, number_format($arPagoBancoDetalle->getVrPago(), 0, '.', ','), 1, 0, 'R');
            $var += $arPagoBancoDetalle->getVrPago();
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
            
        }
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(175, 5, "TOTAL: ", 1, 0, 'R');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(15, 5, number_format($var,0, '.', ','), 1, 0, 'R');
        
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
