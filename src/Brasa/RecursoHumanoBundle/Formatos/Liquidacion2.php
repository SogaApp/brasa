<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class Liquidacion2 extends \FPDF_FPDF {
    public static $em;
    public static $codigoLiquidacion;

    public function Generar($miThis, $codigoLiquidacion) {
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoLiquidacion = $codigoLiquidacion;
        $pdf = new Liquidacion2();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Liquidacion$codigoLiquidacion.pdf", 'D');

    }

    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(7);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 13, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(143, 7, utf8_decode("LIQUIDACION DE CONTRATO DE TRABAJO"), 0, 0, 'C', 1);
        $this->SetXY(53, 18);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);
        $this->Ln(1);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find(self::$codigoLiquidacion);
        $intY = 42;
        //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY(10, $intY);
        $this->Cell(35, 5, utf8_decode("LIQUIDACIÓN:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $arLiquidacion->getCodigoLiquidacionPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "SUBZONA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $subzona = "";
        if($arLiquidacion->getEmpleadoRel()->getCodigoSubzonaFk()) {
            $subzona = $arLiquidacion->getEmpleadoRel()->getSubzonaRel()->getNombre();
        }
           
        $this->Cell(95, 5, utf8_decode($subzona), 1, 0, 'L', 1);
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
        $this->Cell(35, 5, utf8_decode("FECHA INGRESO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $arLiquidacion->getFechaDesde()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, utf8_decode("NÚMERO CUENTA:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(27, 5, $arLiquidacion->getEmpleadoRel()->getCuenta(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(38, 5, utf8_decode("DIAS LABORADOS:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, $arLiquidacion->getNumeroDias(), 1, 0, 'R', 1);
        //Fila 4
        $intY += 5;
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(10, $intY);
        $this->Cell(35, 5, utf8_decode("FECHA RETIRO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $arLiquidacion->getFechaHasta()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "BANCO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 6.5);
        $this->Cell(27, 5, utf8_decode($arLiquidacion->getEmpleadoRel()->getBancoRel()->getNombre()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(38, 5, utf8_decode("SALARIO ") . "(" . $arLiquidacion->getContratoRel()->getSalarioTipoRel()->getNombre() . "):", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, number_format($arLiquidacion->getVrSalario(), 0, '.', ','), 1, 0, 'R', 1);

        //Fila 5
        $intY += 5;
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(10, $intY);
        $this->Cell(35, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 6.5);
        $this->Cell(27, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(38, 5, utf8_decode(""), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, "", 1, 0, 'R', 1);        

        //FILA 6
        $intY += 5;
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(10, $intY);
        $this->Cell(35, 5, utf8_decode("MOTIVO RETIRO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(150, 5, utf8_decode($arLiquidacion->getMotivoTerminacionRel()->getMotivo()), 1, 0, 'L', 1);
        
        $intY = 70;        
        //BLOQUE TOTALES
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        $intX = 35;
        $this->SetXY($intX + 55, 75);
        $this->Cell(15, 5, utf8_decode("DIAS"), 1, 0, 'R', 1);
        $this->SetXY($intX + 71, 75);
        $this->Cell(11, 5, utf8_decode("D.AUS"), 1, 0, 'R', 1);
        $this->SetXY($intX + 83, 75);
        $this->Cell(20, 5, utf8_decode("BASE"), 1, 0, 'R', 1);        
        $this->SetXY($intX + 103, 75);
        $this->Cell(25, 5, utf8_decode("ULT.PAGO"), 1, 0, 'C', 1);
        $this->SetXY($intX + 128, 75);
        $this->Cell(32, 5, utf8_decode("TOTAL"), 1, 0, 'R', 1);
        $intXlinea = 10;
        $this->SetXY($intX+$intXlinea, 81);
        $this->Cell(43, 5, utf8_decode("CESANTÍAS:"), 1, 0, 'L', 1);
        $this->SetXY($intX + $intXlinea, 87);
        $this->Cell(43, 5, "INTERESES:", 1, 0, 'L', 1);
        $this->SetXY($intX + $intXlinea, 93);
        $this->Cell(43, 5, "PRIMA SEMESTRAL:", 1, 0, 'L', 1);
        $this->SetXY($intX + $intXlinea, 99);
        $this->Cell(43, 5, "VACACIONES:", 1, 0, 'L', 1);
        $this->SetXY($intX + $intXlinea, 105);
        $this->Cell(43, 5, "INDEMNIZACION:", 1, 0, 'L', 1);
        $this->SetXY($intX + $intXlinea, 111);
        $this->Cell(43, 5, "BONIFICACIONES:", 1, 0, 'L', 1);    
        $this->SetXY($intX + $intXlinea, 117);
        $this->Cell(43, 5, "TOTAL DEVENGADO:", 1, 0, 'L', 1);        
        $this->SetXY($intX + $intXlinea, 123);
        $this->Cell(43, 5, "DEDUCCIONES", 1, 0, 'L', 1);
        $this->SetXY($intX + $intXlinea, 129);
        $this->Cell(43, 5, "DEDUCCIONES PRIMAS:", 1, 0, 'L', 1);
        $this->SetXY($intX + $intXlinea, 135);
        $this->Cell(43, 5, "TOTAL DEDUCCIONES:", 1, 0, 'L', 1);        
        $this->SetXY($intX + $intXlinea, 141);
        $this->Cell(43, 5, "NETO A PAGAR:", 1, 0, 'L', 1);

        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $intXlinea = 55;
        $this->SetXY($intX + $intXlinea, 81);
        $this->Cell(15, 5, number_format($arLiquidacion->getDiasCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 87);
        $this->Cell(15, 5, number_format($arLiquidacion->getDiasCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 93);
        $this->Cell(15, 5, number_format($arLiquidacion->getDiasPrimas(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 99);
        $this->Cell(15, 5, number_format($arLiquidacion->getDiasVacaciones(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 105);
        $this->Cell(15, 5, "", 0, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 111);
        $this->Cell(15, 5, "", 0, 0, 'R', 1);
        
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $intXlinea = 71;
        $this->SetXY($intX + $intXlinea, 81);
        $this->Cell(11, 5, number_format($arLiquidacion->getDiasCesantiasAusentismo(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 87);
        $this->Cell(11, 5, number_format($arLiquidacion->getDiasCesantiasAusentismo(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 93);
        $this->Cell(11, 5, "", 1, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 99);
        $this->Cell(11, 5, number_format($arLiquidacion->getDiasVacacionesAusentismo(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 105);
        $this->Cell(11, 5, "", 0, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 111);
        $this->Cell(11, 5, "", 0, 0, 'R', 1);        
        
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $intXlinea = 83;
        $this->SetXY($intX + $intXlinea, 81);
        $this->Cell(20, 5, number_format($arLiquidacion->getVrSalarioPromedioCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 87);
        $this->Cell(20, 5, "", 1, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 93);
        $this->Cell(20, 5, number_format($arLiquidacion->getVrSalarioPromedioPrimas(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 99);
        $this->Cell(20, 5, number_format($arLiquidacion->getVrSalarioVacaciones(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 105    );
        $this->Cell(20, 5, "", 0, 0, 'R', 1);
        $this->SetXY($intX + $intXlinea, 111);
        $this->Cell(20, 5, "", 0, 0, 'R', 1);        
        
        $this->SetXY($intX + 103, 81);
        $this->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoCesantias()->format('Y-m-d'), 1, 0, 'C', 1);
        $this->SetXY($intX + 103, 87);
        $this->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoCesantias()->format('Y-m-d'), 1, 0, 'C', 1);
        $this->SetXY($intX + 103, 93);
        $this->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoPrimas()->format('Y-m-d'), 1, 0, 'C', 1);
        $this->SetXY($intX + 103, 99);
        $this->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoVacaciones()->format('Y-m-d'), 1, 0, 'C', 1);
        $this->SetXY($intX + 103, 105);
        $this->Cell(25, 5, "", 0, 0, 'L', 1);
        $this->SetXY($intX + 103, 111);
        $this->Cell(25, 5, "", 0, 0, 'L', 1);

        //$intX = 163;
        $this->SetXY($intX + 128, 81);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 128, 87);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrInteresesCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 128, 93);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrPrima(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 128, 99);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrVacaciones(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 128, 105);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrIndemnizacion(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 128, 111);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrBonificaciones(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 128, 117);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrCesantias()+$arLiquidacion->getVrInteresesCesantias()+$arLiquidacion->getVrPrima()+$arLiquidacion->getVrVacaciones()+$arLiquidacion->getVrIndemnizacion()+$arLiquidacion->getVrBonificaciones(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 128, 123);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrDeducciones(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 128, 129);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrDeduccionPrima(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 128, 135);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrDeducciones()+$arLiquidacion->getVrDeduccionPrima(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 128, 141);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrTotal(), 0, '.', ','), 1, 0, 'R', 1);        
        $this->Ln(15);

        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(236, 236, 236);
        $this->Cell(185, 7, "DESCUENTOS - BONIFICACIONES", 1, 0, 'C', 1);
        $this->Ln();
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 7);
        $header = array(utf8_decode('CÓDIGO'), 'CONCEPTO', 'BONIFICACION', 'DEDUCCION','OBSERVACIONES');

        //creamos la cabecera de la tabla.
        $w = array(12, 81, 19, 17,56);
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
        $this->Footer($arLiquidacion);
    }

    public function Body($pdf) {
        $arLiquidacionAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
        $arLiquidacionAdicionales = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->findBy(array('codigoLiquidacionFk' => self::$codigoLiquidacion));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arLiquidacionAdicionales as $arLiquidacionAdicional) {
            $pdf->Cell(12, 4, $arLiquidacionAdicional->getCodigoPagoConceptoFk(), 1, 0, 'L');
            $concepto = '';
            if ($arLiquidacionAdicional->getCodigoPagoConceptoFk() != null){
                $concepto = $arLiquidacionAdicional->getPagoConceptoRel()->getNombre();
            }
            $pdf->Cell(81, 4, $concepto, 1, 0, 'L');              
            $pdf->Cell(19, 4, number_format($arLiquidacionAdicional->getVrBonificacion(), 0,'.',','), 1, 0, 'R');
            $pdf->Cell(17, 4, number_format($arLiquidacionAdicional->getVrDeduccion(), 0,'.',','), 1, 0, 'R');            
            
            $pdf->SetFont('Arial', '', 6.5);
            $pdf->Cell(56, 4, utf8_decode($arLiquidacionAdicional->getDetalle()), 1, 0, 'L');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
        $pdf->SetY(218);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(24, 4,utf8_decode(""), 0, 0, 'L');        
        $pdf->SetAutoPageBreak(1, 15);
    }

    public function Footer() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find(self::$codigoLiquidacion);
        $this->SetFont('Arial', 'B', 9);
        
        $this->Text(10, 240, "FIRMA: _____________________________________________");
        $this->Text(10, 247, $arLiquidacion->getEmpleadoRel()->getNombreCorto());
        $this->Text(10, 254, "C.C.:     ______________________ de ____________________");
        $this->Text(105, 240, "FIRMA: _____________________________________________");
        $this->Text(105, 247, $arConfiguracion->getNombreEmpresa());
        $this->Text(105, 254, "NIT: ". $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa());
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

?>
