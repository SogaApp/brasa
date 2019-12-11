<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class PagoMasivo1 extends \FPDF_FPDF {
    public static $em;    
    public static $codigoProgramacionPago;
    public static $codigoPago;
    public static $codigoZona;
    public static $codigoSubzona;
    public static $porFecha;
    public static $fechaDesde;
    public static $fechaHasta;
    public static $dato;
    
    public function Generar($miThis, $codigoProgramacionPago = "", $strRuta = "", $codigoPago = "", $codigoZona = "", $codigoSubzona = "", $porFecha = false, $fechaDesde = "", $fechaHasta = "", $dato = "") {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoProgramacionPago = $codigoProgramacionPago;
        self::$codigoPago = $codigoPago;
        self::$codigoZona = $codigoZona;
        self::$codigoSubzona = $codigoSubzona;
        self::$porFecha = $porFecha;
        self::$fechaDesde = $fechaDesde;
        self::$fechaHasta = $fechaHasta;
        self::$dato = $dato;
        //$pdf = new FormatoPagoMasivo('P', 'mm', array(215, 147));
        $pdf = new PagoMasivo1('P','mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $pdf->SetFillColor(200, 200, 200);
        $this->Body($pdf);
        if($strRuta == "") {
            $pdf->Output("Pago$codigoProgramacionPago$codigoPago.pdf", 'D');        
        } else {
            $pdf->Output($strRuta."Pago$codigoProgramacionPago$codigoPago.pdf", 'F');        
        }
        
        
    } 
    
    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(5);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 3);
        $this->Image('imagenes/logos/logo.jpg', 12, 5, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, "COMPROBANTE PAGO NOMINA", 0, 0, 'C', 1);//$this->Cell(150, 7, utf8_decode("COMPROBANTE PAGO ". $arPago->getPagoTipoRel()->getNombre().""), 0, 0, 'C', 1);
        $this->SetXY(53, 11);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa() . " NIT:" . $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 15);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 19);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->SetXY(10, 53);
        //$this->Ln(45);
        $header = array('CODIGO', 'CONCEPTO DE PAGO', 'HORAS', 'DIAS', 'VR. HORA', '%', 'DEVENGADO', 'DEDUCCION');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 6.8);

        //creamos la cabecera de la tabla.
        $w = array(13,77, 10, 10, 22, 7, 27, 27);
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
        $pdf->SetFillColor(200, 200, 200);
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);        
        $dql = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaImpresionDql(self::$codigoPago, self::$codigoProgramacionPago, self::$codigoZona, self::$codigoSubzona, self::$porFecha, self::$fechaDesde, self::$fechaHasta, self::$dato);        
        $query = self::$em->createQuery($dql);
        $arPagos = $query->getResult();
        $numeroPagos = count($arPagos);
        $contador = 1;
        foreach ($arPagos as $arPago){
            $y = 25;
            //FILA 1
            $pdf->SetXY(10, $y);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->Cell(22, 6, "NUMERO:" , 1, 0, 'L', 1);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetFont('Arial','',7);
            $pdf->Cell(78, 6, $arPago->getNumero() , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, "FECHA:" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',7);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(24, 6, $arPago->getFechaHasta()->format('Y/m/d') , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, "CUENTA:" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',7);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(21, 6, $arPago->getEmpleadoRel()->getCuenta() , 1, 0, 'L', 1);
            //FILA 2
            $pdf->SetXY(10, $y+5);
            $pdf->SetFont('Arial','B',7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(22, 6, "EMPLEADO:" , 1, 0, 'L', 1);                            
            $pdf->SetFont('Arial','',6);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(78, 6, utf8_decode($arPago->getEmpleadoRel()->getNombreCorto()) , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, "IDENTIFICACION:" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',7);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(24, 6, $arPago->getEmpleadoRel()->getNumeroIdentificacion() . " (" .  $arPago->getCodigoEmpleadoFk() . ")" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, "BANCO:" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',6.5);
            $pdf->SetFillColor(255, 255, 255);            
            $pdf->Cell(21, 6, substr(utf8_decode($arPago->getEmpleadoRel()->getBancoRel()->getNombre()), 0, 13), 1, 0, 'L', 1);
            //FILA 3
            $pdf->SetXY(10, $y+10);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(22, 6, "CARGO:" , 1, 0, 'L', 1);                            
            $pdf->SetFont('Arial','',6);
            $pdf->SetFillColor(255, 255, 255);
            $cargo = "";
            if($arPago->getEmpleadoRel()->getCodigoCargoFk()) {
                $cargo = $arPago->getEmpleadoRel()->getCargoRel()->getNombre();
            }
            $pdf->Cell(78, 6, $cargo , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, "PERIODO PAGO:" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',6);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(24, 6, $arPago->getCentroCostoRel()->getPeriodoPagoRel()->getNombre() , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, utf8_decode("PENSIÓN :") , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',6);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(21, 6, utf8_decode($arPago->getContratoRel()->getEntidadPensionRel()->getNombre()) , 1, 0, 'L', 1);
            //FILA 4
            $pdf->SetXY(10, $y+15);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(22, 6, "CENTRO COSTOS:" , 1, 0, 'L', 1);                            
            $pdf->SetFont('Arial','',6);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(78, 5, $arPago->getCentroCostoRel()->getNombre() , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 5, "DESDE:" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',7);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(24, 5, $arPago->getFechaDesde()->format('Y/m/d') , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 5, "SALUD:" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',7);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(21, 5, substr(utf8_decode($arPago->getContratoRel()->getEntidadSaludRel()->getNombre()), 0, 10) , 1, 0, 'L', 1);
            //FILA 5
            $pdf->SetXY(10, $y+20);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(22, 5, "ZONA:" , 1, 0, 'L', 1);                            
            $pdf->SetFont('Arial','',6.5);
            $pdf->SetFillColor(255, 255, 255);
            $zona = "";
            if($arPago->getEmpleadoRel()->getCodigoZonaFk()) {
                $zona = $arPago->getEmpleadoRel()->getZonaRel()->getNombre();
            }            
            $subZona = "";
            if($arPago->getEmpleadoRel()->getCodigoSubzonaFk()) {
                $subZona = $arPago->getEmpleadoRel()->getSubzonaRel()->getNombre();
            }
            $pdf->Cell(78, 5, $zona . "-" .$subZona , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 5, "HASTA" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',7);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(24, 5, $arPago->getFechaHasta()->format('Y/m/d') , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 5, "SALARIO" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',7);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(21, 5, number_format($arPago->getVrSalarioEmpleado(), 0, '.', ',') , 1, 0, 'R', 1);                    
            $pdf->Ln(12);
            $totalExtras = 0;
            $totalCompensado = 0;
            $totalHorasCompensado = 0;            
            $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();        
            $dql = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->listaDql($arPago->getCodigoPagoPk());                
            $query = self::$em->createQuery($dql);
            $arPagoDetalles = $query->getResult();           
            foreach ($arPagoDetalles as $arPagoDetalle) {            
                $pdf->SetFont('Arial', '', 5.4);
                $pdf->Cell(13, 4, $arPagoDetalle->getCodigoPagoConceptoFk(), 1, 0, 'L');
                $pdf->Cell(77, 4, utf8_decode($arPagoDetalle->getPagoConceptoRel()->getNombre()), 1, 0, 'L');
                $pdf->SetFont('Arial', '', 5.5);            
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(10, 4, number_format($arPagoDetalle->getNumeroHoras(), 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(10, 4, number_format($arPagoDetalle->getNumeroDias(), 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(22, 4, number_format($arPagoDetalle->getVrHora(), 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(7, 4, number_format($arPagoDetalle->getPorcentajeAplicado(), 0, '.', ','), 1, 0, 'R');
                if($arPagoDetalle->getOperacion() == 1) {
                    $pdf->Cell(27, 4, number_format($arPagoDetalle->getVrPago(), 0, '.', ','), 1, 0, 'R');    
                } else {
                    $pdf->Cell(27, 4, number_format(0, 0, '.', ','), 1, 0, 'R');    
                }
                if($arPagoDetalle->getOperacion() == -1) {
                    $pdf->Cell(27, 4, "-".number_format($arPagoDetalle->getVrPago(), 0, '.', ','), 1, 0, 'R');    
                } else {
                    $pdf->Cell(27, 4, number_format(0, 0, '.', ','), 1, 0, 'R');    
                }
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 15);
            }
            
            //TOTALES
                $pdf->Ln(2);
                $pdf->Cell(143, 4, "", 0, 0, 'R');
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->SetFillColor(200, 200, 200);
                $pdf->Cell(30, 4, "TOTAL DEVENGADO:", 1, 0, 'R',true);
                $pdf->Cell(20, 4, number_format($arPago->getVrDevengado(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->Cell(143, 4, "", 0, 0, 'R');
                $pdf->Cell(30, 4, "TOTAL DEDUCCIONES:", 1, 0, 'R',true);
                $pdf->Cell(20, 4, "-".number_format($arPago->getVrDeducciones(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->Cell(143, 4, "", 0, 0, 'R');
                $pdf->Cell(30, 4, "NETO PAGAR", 1, 0, 'R',true);
                $pdf->Cell(20, 4, number_format($arPago->getVrNeto(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln(-8);
                
                if($arPago->getCodigoSoportePagoFk() && $arPago->getCentroCostoRel()->getImprimirProgramacionFormato()) {
                    $desde = $arPago->getFechaDesde()->format('j');
                    $hasta = $arPago->getFechaHasta()->format('j');
                    if($hasta == 30) {$hasta = 31;}

                $arSoportePago =  self::$em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arPago->getCodigoSoportePagoFk());
                    if($arSoportePago) {
                        $header = array('D1','D2','D3','D4','D5','D6','D7','D8','D9','D10','D11','D12','D13','D14','D15','D16','D17','D18','D19','D20','D21','D22','D23','D24','D25','D26','D27','D28','D29','D30','D31');
                        $pdf->SetFillColor(200, 200, 200);
                        $pdf->SetTextColor(0);
                        $pdf->SetDrawColor(0, 0, 0);
                        $pdf->SetLineWidth(.2);
                        $pdf->SetFont('', 'B', 6.8);

                        //creamos la cabecera de la tabla.
                        $w = array(6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2,6.2);
                        for ($i = $desde; $i <= $hasta; $i++) {
                            $pdf->Cell(6.2, 4, "D".$i, 1, 0, 'L', 1);
                        }
                        $pdf->Ln();
                        $arSoportePagoProgramaciones = new \Brasa\TurnoBundle\Entity\TurSoportePagoProgramacion();
                        $arSoportePagoProgramaciones =  self::$em->getRepository('BrasaTurnoBundle:TurSoportePagoProgramacion')->findBy(array('codigoSoportePagoFk' => $arPago->getCodigoSoportePagoFk()));
                        foreach ($arSoportePagoProgramaciones as $arSoportePagoProgramacion) {
                            $detalle = $this->convertirArray($arSoportePagoProgramacion);
                            $pdf->SetFont('Arial', '', 5);
                            for($j=$desde; $j<=$hasta; $j++) {                            
                                $pdf->Cell(6.2, 4, $detalle[$j], 1, 0, 'L');
                            }
                            $pdf->Ln();
                            $pdf->SetAutoPageBreak(true, 15);
                        }                                        
                    }                
                } else {
                   $pdf->Ln(8); 
                }
                $pdf->Ln(5);
                
                if($arConfiguracion->getImprimirMensajePago()) {
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->Cell(193, 4, utf8_decode($arPago->getProgramacionPagoRel()->getMensajePago()), 0, 0, 'C');                    
                }
                
                if($contador < $numeroPagos) {
                    $pdf->AddPage();
                }                
            $contador++;
        }
        
        
        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 7);
            
                    
    }

    public function Footer() {
        
        //$this->SetFont('Arial','', 8);  
        //$this->Text(185, 140, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }   
    
    private function convertirArray($arSoportePagoProgramacion) {
        $arrProgramacionDetalle = array();
        if($arSoportePagoProgramacion) {
            $arrProgramacionDetalle[1] = $arSoportePagoProgramacion->getDia1();
            $arrProgramacionDetalle[2] = $arSoportePagoProgramacion->getDia2();
            $arrProgramacionDetalle[3] = $arSoportePagoProgramacion->getDia3();
            $arrProgramacionDetalle[4] = $arSoportePagoProgramacion->getDia4();
            $arrProgramacionDetalle[5] = $arSoportePagoProgramacion->getDia5();
            $arrProgramacionDetalle[6] = $arSoportePagoProgramacion->getDia6();
            $arrProgramacionDetalle[7] = $arSoportePagoProgramacion->getDia7();
            $arrProgramacionDetalle[8] = $arSoportePagoProgramacion->getDia8();
            $arrProgramacionDetalle[9] = $arSoportePagoProgramacion->getDia9();
            $arrProgramacionDetalle[10] = $arSoportePagoProgramacion->getDia10();
            $arrProgramacionDetalle[11] = $arSoportePagoProgramacion->getDia11();
            $arrProgramacionDetalle[12] = $arSoportePagoProgramacion->getDia12();
            $arrProgramacionDetalle[13] = $arSoportePagoProgramacion->getDia13();
            $arrProgramacionDetalle[14] = $arSoportePagoProgramacion->getDia14();
            $arrProgramacionDetalle[15] = $arSoportePagoProgramacion->getDia15();
            $arrProgramacionDetalle[16] = $arSoportePagoProgramacion->getDia16();
            $arrProgramacionDetalle[17] = $arSoportePagoProgramacion->getDia17();
            $arrProgramacionDetalle[18] = $arSoportePagoProgramacion->getDia18();
            $arrProgramacionDetalle[19] = $arSoportePagoProgramacion->getDia19();
            $arrProgramacionDetalle[20] = $arSoportePagoProgramacion->getDia20();
            $arrProgramacionDetalle[21] = $arSoportePagoProgramacion->getDia21();
            $arrProgramacionDetalle[22] = $arSoportePagoProgramacion->getDia22();
            $arrProgramacionDetalle[23] = $arSoportePagoProgramacion->getDia23();
            $arrProgramacionDetalle[24] = $arSoportePagoProgramacion->getDia24();
            $arrProgramacionDetalle[25] = $arSoportePagoProgramacion->getDia25();
            $arrProgramacionDetalle[26] = $arSoportePagoProgramacion->getDia26();
            $arrProgramacionDetalle[27] = $arSoportePagoProgramacion->getDia27();
            $arrProgramacionDetalle[28] = $arSoportePagoProgramacion->getDia28();
            $arrProgramacionDetalle[29] = $arSoportePagoProgramacion->getDia29();
            $arrProgramacionDetalle[30] = $arSoportePagoProgramacion->getDia30();
            $arrProgramacionDetalle[31] = $arSoportePagoProgramacion->getDia31();
        }
        return $arrProgramacionDetalle;
    }    
    
}

?>
