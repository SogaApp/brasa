<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoPago extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoPago;
    
    public function Generar($miThis, $codigoPago) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoPago = $codigoPago;
        $pdf = new FormatoPago();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Pago$codigoPago.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find(self::$codigoPago);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 20);
        $this->Cell(193, 10, "COMPROBANTE PAGO NOMINA " , 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);  
        //FILA 1
        $this->SetXY(10, 30);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(22, 6, "PAGO:" , 1, 0, 'L', 1);                            
        $this->SetFont('Arial','',7);
        $this->Cell(78, 6, $arPago->getCodigoPagoPk() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(24, 6, $arPago->getFechaHasta()->format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(45, 6, "" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        //$this->Cell(21, 6, "" , 1, 0, 'L', 1);
        //FILA 2
        $this->SetXY(10, 35);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(22, 6, "EMPLEADO:" , 1, 0, 'L', 1);                            
        $this->SetFont('Arial','',6);
        $this->Cell(78, 6, $arPago->getEmpleadoRel()->getNombreCorto() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, "IDENTIFICACION:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(24, 6, $arPago->getEmpleadoRel()->getNumeroIdentificacion() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, "CUENTA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(21, 6, $arPago->getEmpleadoRel()->getCuenta() , 1, 0, 'L', 1);
        //FILA 3
        $this->SetXY(10, 40);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(22, 6, "CARGO:" , 1, 0, 'L', 1);                            
        $this->SetFont('Arial','',6);
        $this->Cell(78, 6, $arPago->getEmpleadoRel()->getCargoDescripcion() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, "EPS:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',6);
        $this->Cell(24, 6, utf8_decode($arPago->getEmpleadoRel()->getEntidadSaludRel()->getNombre()) , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, utf8_decode("PENSIÓN :") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',6);
        $this->Cell(21, 6, utf8_decode($arPago->getEmpleadoRel()->getEntidadPensionRel()->getNombre()) , 1, 0, 'L', 1);
        //FILA 4
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(22, 6, "CENTRO COSTOS:" , 1, 0, 'L', 1);                            
        $this->SetFont('Arial','',6);
        $this->Cell(78, 6, $arPago->getCentroCostoRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, "DESDE:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(24, 6, $arPago->getFechaDesde()->format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, "HASTA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(21, 6, $arPago->getFechaHasta()->format('Y/m/d') , 1, 0, 'L', 1);
        //FILA 5
        $this->SetXY(10, 50);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(22, 5, "PERIODO PAGO:" , 1, 0, 'L', 1);                            
        $this->SetFont('Arial','',6.5);
        $this->Cell(78, 5, $arPago->getCentroCostoRel()->getPeriodoPagoRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 5, "SALARIO" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(24, 5, number_format($arPago->getEmpleadoRel()->getVrSalario(), 2, '.', ',') , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 5, "SALARIO PERIODO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(21, 5, number_format($arPago->getVrSalario(), 2, '.', ',') , 1, 0, 'R', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('CONCEPTO', 'DETALLE', 'HORAS', 'VR. HORA', '%', 'DEDUCCION', 'DEVENGADO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(40, 83, 11, 14, 9, 18, 18);
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
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalle = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => self::$codigoPago));
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);
        foreach ($arPagoDetalle as $arPagoDetalle) {            
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(40, 4, $arPagoDetalle->getPagoConceptoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(83, 4, utf8_decode($arPagoDetalle->getDetalle()), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(11, 4, number_format($arPagoDetalle->getNumeroHoras(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(14, 4, number_format($arPagoDetalle->getVrHora(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(9, 4, number_format($arPagoDetalle->getPorcentajeAplicado(), 2, '.', ','), 1, 0, 'R');
            if($arPagoDetalle->getOperacion() == -1) {
                $pdf->Cell(18, 4, "-".number_format($arPagoDetalle->getVrPago(), 2, '.', ','), 1, 0, 'R');    
            } else {
                $pdf->Cell(18, 4, number_format(0, 2, '.', ','), 1, 0, 'R');    
            }            
            if($arPagoDetalle->getOperacion() == 1) {
                $pdf->Cell(18, 4, number_format($arPagoDetalle->getVrPago(), 2, '.', ','), 1, 0, 'R');    
            } else {
                $pdf->Cell(18, 4, number_format(0, 2, '.', ','), 1, 0, 'R');    
            }            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
            //TOTALES
            $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
            $arPago = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find(self::$codigoPago);
            $pdf->Ln(4);
            $pdf->Cell(143, 4, "", 0, 0, 'R');
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, "TOTAL DEDUCCIONES:", 1, 0, 'R');
            $pdf->Cell(20, 4, "-".number_format($arPago->getVrDeducciones(), 2, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->Cell(143, 4, "", 0, 0, 'R');
            $pdf->Cell(30, 4, "TOTAL DEVENGADO:", 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arPago->getVrDevengado(), 2, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->Cell(143, 4, "", 0, 0, 'R');
            $pdf->Cell(30, 4, "NETO PAGAR", 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arPago->getVrNeto(), 2, '.', ','), 1, 0, 'R');
            $pdf->Ln(8);
            // INFORMACION DE CREDITOS
            $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
            $arPagoDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => self::$codigoPago));
                $this->SetFillColor(236, 236, 236);
                $pdf->Cell(193, 4, utf8_decode("INFORMACIÓN DE CREDITOS"), 1, 0, 'L');
                $pdf->Ln(4);
                $pdf->Cell(24, 4, "CODIGO", 1, 0, 'L');
                $pdf->Cell(24, 4, "FECHA", 1, 0, 'L');
                $pdf->Cell(25, 4, "VALOR CREDITO", 1, 0, 'L');
                $pdf->Cell(24, 4, "CUOTAS", 1, 0, 'L');
                $pdf->Cell(24, 4, "CUOTA ACTUAL", 1, 0, 'L');
                $pdf->Cell(24, 4, "SALDO", 1, 0, 'L');
                $pdf->Cell(24, 4, "APROBADO", 1, 0, 'L');
                $pdf->Cell(24, 4, "SUSPENDIDO", 1, 0, 'L');
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                foreach ($arPagoDetalles as $arPagoDetalles) {
                    if ($arPagoDetalles->getCodigoCreditoFk() <> "" && $arPagoDetalles->getCodigoPagoConceptoFk() == $arConfiguracion->getCodigoCredito()) { 
                        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCredito = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($arPagoDetalles->getCodigoCreditoFk());
                        $this->SetFillColor(272, 272, 272);
                        $pdf->Cell(24, 4, $arCredito->getCodigoCreditoPk(), 1, 0, 'L');
                        $pdf->Cell(24, 4, $arCredito->getFecha()->format('Y/m/d'), 1, 0, 'L');
                        $pdf->Cell(25, 4, number_format($arCredito->getVrPagar(), 2, '.', ','), 1, 0, 'R');
                        $pdf->Cell(24, 4, $arCredito->getNumeroCuotas(), 1, 0, 'L');
                        $pdf->Cell(24, 4, $arCredito->getNumeroCuotaActual(), 1, 0, 'L');
                        $pdf->Cell(24, 4, number_format($arCredito->getSaldo(), 2, '.', ','), 1, 0, 'R');
                        if ($arCredito->getAprobado() == 1){
                            $pdf->Cell(24, 4, "SI", 1, 0, 'L');
                        }
                        else {
                            $pdf->Cell(24, 4, "NO", 1, 0, 'L');
                        }
                        if ($arCredito->getEstadoSuspendido() == 1){
                            $pdf->Cell(24, 4, "SI", 1, 0, 'L');
                        }
                        else {
                            $pdf->Cell(24, 4, "NO", 1, 0, 'L');
                        }
                        $pdf->Ln();
                    }
                
                }
            $pdf->Ln(8);
            $pdf->SetFont('Arial', 'B', 7);
            // INFORMACION DE INCAPACIDADES
            $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
            $arPagoDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => self::$codigoPago));
                $this->SetFillColor(236, 236, 236);
                $pdf->Cell(193, 4, utf8_decode("INFORMACIÓN DE INCAPACIDADES"), 1, 0, 'L');
                $pdf->Ln(4);
                $pdf->Cell(32, 4, utf8_decode("CÓDIGO"), 1, 0, 'L');
                $pdf->Cell(32, 4, "DESDE", 1, 0, 'L');
                $pdf->Cell(32, 4, "HASTA", 1, 0, 'L');
                $pdf->Cell(32, 4, "HORAS", 1, 0, 'L');
                $pdf->Cell(32, 4, "VALOR HORA", 1, 0, 'L');
                $pdf->Cell(33, 4, utf8_decode("NÚMERO EPS"), 1, 0, 'L');
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                foreach ($arPagoDetalles as $arPagoDetalles) {
                    if ($arPagoDetalles->getCodigoIncapacidadFk() <> "" && $arPagoDetalles->getCodigoPagoConceptoFk() == $arConfiguracion->getCodigoIncapacidad()) { 
                        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                        $arIncapacidad = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($arPagoDetalles->getCodigoIncapacidadFk());
                        $this->SetFillColor(272, 272, 272);
                        $pdf->Cell(32, 4, $arIncapacidad->getCodigoIncapacidadPk(), 1, 0, 'L');
                        $pdf->Cell(32, 4, $arIncapacidad->getFechaDesde()->format('Y/m/d'), 1, 0, 'L');
                        $pdf->Cell(32, 4, $arIncapacidad->getFechaHasta()->format('Y/m/d'), 1, 0, 'L');
                        $pdf->Cell(32, 4, $arPagoDetalles->getNumeroHoras(), 1, 0, 'L');
                        $pdf->Cell(32, 4, number_format($arPagoDetalles->getVrHora(), 2, '.', ','), 1, 0, 'R');
                        $pdf->Cell(33, 4, $arIncapacidad->getNumeroEps(), 1, 0, 'L');
                        $pdf->Ln();
                    }
                
                }    
    }

    public function Footer() {
        
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
