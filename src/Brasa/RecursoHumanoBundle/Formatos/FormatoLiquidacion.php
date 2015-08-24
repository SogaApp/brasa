<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoLiquidacion extends \FPDF_FPDF {
    public static $em;
    public static $codigoLiquidacion;
    
    public function Generar($miThis, $codigoLiquidacion) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoLiquidacion = $codigoLiquidacion;
        $pdf = new FormatoLiquidacion();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Liquidacion$codigoLiquidacion.pdf", 'D');        
        
    } 
    
    public function Header() {                        
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find(self::$codigoLiquidacion);        
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        $this->SetXY(10, 16);
        $this->Cell(185, 7, utf8_decode('LIQUIDACIÓN DE PRESTACIONES SOCIALES'), 1, 0, 'C', 1);        
        $intY = 25;
        //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY(10, $intY);        
        $this->Cell(35, 5, utf8_decode("LIQUIDACIÓN:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $arLiquidacion->getCodigoLiquidacionPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "CENTRO COSTO:", 1, 0, 'L', 1);         
        $this->SetFont('Arial', '', 7);
        $this->Cell(95, 5, utf8_decode($arLiquidacion->getCentroCostoRel()->getNombre()), 1, 0, 'L', 1);
        //FILA 2
        $intY += 5;
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(10, $intY);        
        $this->Cell(35, 5, utf8_decode("DOCUMENTO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $arLiquidacion->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "EMPLEADO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(95, 5, utf8_decode($arLiquidacion->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L', 1);
        //FILA 3
        $intY += 5;
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(10, $intY);        
        $this->Cell(35, 5, utf8_decode("DESDE:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $arLiquidacion->getFechaDesde()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, utf8_decode("DIAS LABORADOS:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, $arLiquidacion->getNumeroDias(), 1, 0, 'R', 1);
        //Fila 4
        $intY += 5;
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(10, $intY);        
        $this->Cell(35, 5, utf8_decode("HASTA:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $arLiquidacion->getFechaHasta()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, utf8_decode("DIAS NOMINA ADIC:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, $arLiquidacion->getDiasAdicionalesIBC(), 1, 0, 'R', 1);        
        
        //BLOQUE BASE LIQUIDACIÓN
        $intX = 120;
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        
        $this->SetXY($intX, 50);        
        $this->Cell(43, 5, utf8_decode("IBC:"), 1, 0, 'L', 1);
        $this->SetXY($intX, 56);
        $this->Cell(43, 5, "BASE PRESTACIONES:", 1, 0, 'L', 1);
        $this->SetXY($intX, 62);
        $this->Cell(43, 5, "AUXILIO TRANSPORTE:", 1, 0, 'L', 1);            
        $this->SetXY($intX, 68);
        $this->Cell(43, 5, "BASE PRESTACIONES TOTAL:", 1, 0, 'L', 1);         
        $intX = 163;
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY($intX, 50);        
        $this->Cell(32, 5, number_format($arLiquidacion->getVrIngresoBaseCotizacionTotal(), 2, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, 56);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrBasePrestaciones(), 2, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, 62);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrAuxilioTransporte(), 2, '.', ','), 1, 0, 'R', 1);        
        $this->SetXY($intX, 68);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrBasePrestacionesTotal(), 2, '.', ','), 1, 0, 'R', 1);        
        
        //BLOQUE TOTALES
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);        
        $intX = 60;
        $this->SetXY($intX + 63, 96);        
        $this->Cell(15, 5, utf8_decode("DIAS"), 1, 0, 'R', 1);
        $this->SetXY($intX+78, 96);        
        $this->Cell(25, 5, utf8_decode("DESDE"), 1, 0, 'L', 1);        
        $this->SetXY($intX+103, 96);        
        $this->Cell(32, 5, utf8_decode("TOTAL"), 1, 0, 'R', 1);                
        
        $this->SetXY($intX + 28, 102);        
        $this->Cell(33, 5, utf8_decode("CESANTÍAS:"), 1, 0, 'L', 1);       
        $this->SetXY($intX + 28, 108);
        $this->Cell(33, 5, "INTERESES:", 1, 0, 'L', 1);
        $this->SetXY($intX + 28, 114);
        $this->Cell(33, 5, "PRIMA SEMESTRAL:", 1, 0, 'L', 1);        
        $this->SetXY($intX + 28, 120);
        $this->Cell(33, 5, "VACACIONES:", 1, 0, 'L', 1);    
        
        $this->SetFont('Arial', '', 8);   
        $this->SetFillColor(272, 272, 272);        
        $this->SetXY($intX + 63, 102);        
        $this->Cell(15, 5, number_format($arLiquidacion->getDiasCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 63, 108);
        $this->Cell(15, 5, number_format($arLiquidacion->getDiasCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 63, 114);
        $this->Cell(15, 5, number_format($arLiquidacion->getDiasPrimas(), 0, '.', ','), 1, 0, 'R', 1);        
        $this->SetXY($intX + 63, 120);
        $this->Cell(15, 5, number_format($arLiquidacion->getDiasVacaciones(), 0, '.', ','), 1, 0, 'R', 1);        

        $this->SetXY($intX + 78, 102);        
        $this->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoCesantias()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetXY($intX + 78, 108);
        $this->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoCesantias()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetXY($intX + 78, 114);
        $this->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoPrimas()->format('Y-m-d'), 1, 0, 'L', 1);        
        $this->SetXY($intX + 78, 120);
        $this->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoVacaciones()->format('Y-m-d'), 1, 0, 'L', 1);                
        
        //$intX = 163;
        $this->SetXY($intX + 103, 102);        
        $this->Cell(32, 5, number_format($arLiquidacion->getVrCesantias(), 2, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 103, 108);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrInteresesCesantias(), 2, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 103, 114);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrPrima(), 2, '.', ','), 1, 0, 'R', 1);        
        $this->SetXY($intX + 103, 120);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrVacaciones(), 2, '.', ','), 1, 0, 'R', 1);
        
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY($intX + 103, 126);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrTotal(), 2, '.', ','), 1, 0, 'R', 1);        
        
        $this->Ln(15);
        
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(236, 236, 236);
        $this->Cell(185, 7, "OTRAS DEDUCCIONES:", 1, 0, 'C', 1);
        $this->Ln();
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 8);
        $header = array('COD', 'CREDITO', 'NOMBRE', 'VR. DEDUCCION', 'DETALLES');
        
        //creamos la cabecera de la tabla.
        $w = array(12, 15, 45, 24, 89);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauraci�n de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $arLiquidacionDeduccion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccion();
        $arLiquidacionDeduccion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionDeduccion')->findBy(array('codigoLiquidacionFk' => self::$codigoLiquidacion));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        foreach ($arLiquidacionDeduccion as $arLiquidacionDeduccion) {            
            $pdf->Cell(12, 4, $arLiquidacionDeduccion->getCodigoLiquidacionDeduccionPk(), 1, 0, 'L');
            $pdf->Cell(15, 4, $arLiquidacionDeduccion->getCodigoCreditoFk(), 1, 0, 'L');
            $pdf->Cell(45, 4, $arLiquidacionDeduccion->getCreditoRel()->getCreditoTipoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(24, 4, number_format($arLiquidacionDeduccion->getVrDeduccion(), 2,'.',','), 1, 0, 'R');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(89, 4, $arLiquidacionDeduccion->getDetalle(), 1, 0, 'L');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
        
        $this->SetFont('Arial', 'B', 9);
        $this->Text(10, 240, "FIRMA: _____________________________________________");
        $this->Text(105, 240, "EMPRESA: __________________________________________");
        $this->Text(10, 247, "C.C.:     ______________________ de ____________________");
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
