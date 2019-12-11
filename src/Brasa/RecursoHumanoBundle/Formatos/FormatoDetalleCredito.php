<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoDetalleCredito extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoCredito;
    
    public function Generar($miThis, $codigoCredito) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoCredito = $codigoCredito;
        $pdf = new FormatoDetalleCredito();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("DetalleCredito_$codigoCredito.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arDetallePago = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arDetallePago = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find(self::$codigoCredito);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(10);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',13);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 13, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("INFORMACIÓN DEL CRÉDITO"), 0, 0, 'C', 1);
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
        //FORMATO ISO
        $this->SetXY(168, 18);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(35, 6, "CODIGO: ".$arContenidoFormatoA->getCodigoFormatoIso(), 1, 0, 'L', 1);
        $this->SetXY(168, 24);
        $this->Cell(35, 6, utf8_decode("VERSIÓN: ".$arContenidoFormatoA->getVersion()), 1, 0, 'L', 1);
        $this->SetXY(168, 30);
        $this->Cell(35, 6, utf8_decode("FECHA: ".$arContenidoFormatoA->getFechaVersion()->format('Y-m-d')), 1, 0, 'L', 1);
        //fila 1
        $this->SetXY(10, 40);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "CODIGO CREDITO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getCodigoCreditoPk() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getFecha()->format('Y/m/d') , 1, 0, 'L', 1);
        //fila2
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "EMPLEADO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 6, utf8_decode($arDetallePago->getEmpleadoRel()->getNombreCorto()) , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "CREDITO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        if ($arDetallePago->getCodigoCreditoTipoFk() == null){
            $strCreditoTipo = "";
        }else{
            $strCreditoTipo = $arDetallePago->getCreditoTipoRel()->getNombre();
        }
        $this->Cell(65, 6, utf8_decode($strCreditoTipo) , 1, 0, 'L', 1);
        //fila3
        $this->SetXY(10, 50);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, utf8_decode("NÚMERO CUENTA:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getEmpleadoRel()->getCuenta() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "BANCO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getEmpleadoRel()->getBancoRel()->getNombre(), 1, 0, 'L', 1);
        
        $this->SetXY(10, 55);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "VALOR CREDITO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, number_format($arDetallePago->getVrPagar(), 2, '.', ',') , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "VALOR CUOTA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, number_format($arDetallePago->getVrCuota(), 2, '.', ',') , 1, 0, 'R', 1);
        $this->SetXY(10, 60);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "NUMERO CUOTAS:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getNumeroCuotas() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "CUOTA ACTUAL:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getNumeroCuotaActual() , 1, 0, 'L', 1);
        $this->SetXY(10, 65);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "SALDO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, number_format($arDetallePago->getSaldo(), 2, '.', ','), 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "PAGADO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arDetallePago->getEstadoPagado()== 1)
        {    
            $this->Cell(65, 6, "SI" , 1, 0, 'L', 1);
        }
        else
        {
            $this->Cell(65, 6, "NO" , 1, 0, 'L', 1);
        }    
        $this->SetXY(10, 70);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "SALDO TEMPORAL:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, number_format($arDetallePago->getSaldo() , 2, '.', ','),1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "APROBADO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arDetallePago->getAprobado()== 1)
        {    
            $this->Cell(65, 6, "SI" , 1, 0, 'L', 1);
        }
        else
        {
            $this->Cell(65, 6, "NO" , 1, 0, 'L', 1);
        }
        $this->SetXY(10, 75);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "TIPO PAGO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getCreditoTipoPagoRel()->getNombre(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "SUSPENDIDO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arDetallePago->getEstadoSuspendido()== 1)
        {    
            $this->Cell(65, 6, "SI" , 1, 0, 'L', 1);
        }
        else
        {
            $this->Cell(65, 6, "NO" , 1, 0, 'L', 1);
        }
        $this->SetXY(10, 80);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "SEGURO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, number_format($arDetallePago->getSeguro(), 2, '.', ','),1, 0, 'R', 1);
        $this->Cell(95, 6, "" , 1, 0, 'L', 1);
        $this->SetXY(10, 70);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 5, "COMENTARIOS:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(160, 5, $arDetallePago->getComentarios() , 1, 0, 'L', 1);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(20);
        $header = array('CODIGO PAGO', 'TIPO PAGO', 'PERIODO DESDE', 'PERIODO HASTA','FECHA PAGO', 'VR CUOTA');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(25, 45, 30, 30, 30, 30);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauraci�n de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $arCreditoPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
        $arCreditoPagos = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoPago')->findBy(array('codigoCreditoFk' => self::$codigoCredito));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        $douTotal = 0;
        foreach ($arCreditoPagos as $arCreditoPago) { 
            $pdf->Cell(25, 4, $arCreditoPago->getCodigoPagoCreditoPk(), 1, 0, 'L');
            if ($arCreditoPago->getCodigoCreditoTipoPagoFk() == null){
                $pdf->Cell(45, 4, "ABONO EXTERNO", 1, 0, 'L');
            }
            else {
                $pdf->Cell(45, 4, $arCreditoPago->getCreditoTipoPagoRel()->getNombre(), 1, 0, 'L');
            }
            if ($arCreditoPago->getCodigoPagoFk() != "") {
                $pdf->Cell(30, 4, $arCreditoPago->getPagoRel()->getFechaDesde()->format('Y/m/d'), 1, 0, 'R');
                $pdf->Cell(30, 4, $arCreditoPago->getPagoRel()->getFechaHasta()->format('Y/m/d'), 1, 0, 'R');
            }
            else {
                $pdf->Cell(30, 4, $arCreditoPago->getFechaPago()->format('Y/m/d'), 1, 0, 'R');
                $pdf->Cell(30, 4, $arCreditoPago->getFechaPago()->format('Y/m/d'), 1, 0, 'R');
            }
            $pdf->Cell(30, 4, $arCreditoPago->getFechaPago()->format('Y/m/d'), 1, 0, 'C');
            $pdf->Cell(30, 4, number_format($arCreditoPago->getVrCuota(), 2, '.', ','), 1, 0, 'R');
            $douTotal += $arCreditoPago->getVrCuota();
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 33);
        }
        
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(160, 4, "TOTAL", 1, 0, 'R');
        $pdf->Cell(30, 4, number_format($douTotal, 2, '.', ','), 1, 0, 'R');
    }   

    public function Footer() {
                
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
