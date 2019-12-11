<?php

namespace Brasa\TurnoBundle\Formatos;

class Factura1 extends \FPDF_FPDF {

    public static $em;
    public static $codigoFactura;
    public static $strLetras;

    public function Generar($miThis, $codigoFactura) {

        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $arrayNumero = explode(".", 0, 2);
        $intCentavos = 0;
        if (count($arrayNumero) > 1)
            $intCentavos = substr($arrayNumero[1], $arFactura->getVrTotal(), 2);
        $strLetras = \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($arFactura->getVrTotal());
        self::$strLetras = $strLetras;
        ob_clean();
        $pdf = new Factura1();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Factura$codigoFactura.pdf", 'D');
    }

    public function Header() {
        $this->GenerarEncabezadoFactura(self::$em);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionTurno = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracionTurno = self::$em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = self::$em->getRepository('BrasaTurnoBundle:TurFactura')->find(self::$codigoFactura);
        $this->SetXY(10, 10);
        $this->Cell(195, 268, '', 1, 0, 'L');

        $this->SetFont('Arial', '', 7);
        $this->SetXY(110, 75);
        $this->MultiCell(140, 3, $arConfiguracionTurno->getInformacionResolucionDianFactura(), 0, 'L');

        $this->SetFont('Arial', 'B', 9);

        $this->SetMargins(10, 1, 10);
        //$this->Rect(4, 40, 130, 20);
        $this->ln(1);
        $this->SetY(48);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 3, 'CLIENTE', 0, 0, 'L');
        $this->SetY(50);
        $this->Ln();
        $this->Ln(1);
        $this->SetFont('Arial', 'B', 9);
        $List1 = array('NOMBRE: ', 'NIT: ', 'TELEFONO:', 'DIRECCION:', 'SECTOR:', 'ESTRATO:');
        foreach ($List1 as $col) {
            $this->Cell(50, 4, $col, 0, 0, 'L');
            $this->Ln(4);
        }

        $Datos = array($arFactura->getClienteRel()->getNombreCorto(),
            $arFactura->getClienteRel()->getNit() . "-" . $arFactura->getClienteRel()->getDigitoVerificacion(),
            $arFactura->getClienteRel()->getTelefono(),
            $arFactura->getClienteRel()->getDireccion(),
            $arFactura->getClienteRel()->getSectorRel()->getNombre(),
            $arFactura->getClienteRel()->getEstrato());
        $this->SetFont('Arial', '', 8);
        $this->SetY(54);

        foreach ($Datos as $col) {
            $this->SetX(33);
            $this->Cell(50, 4, $col, 0, 'L');
            $this->Ln(4);
        }

        //$this->SetMargins(4, 1, 10);
        //$this->Rect(135, 26, 73, 20);
        $this->ln(1);
        $this->SetY(27);

        $List1 = array('FACTURA DE VENTA', 'Fecha emision:', 'Fecha vencimiento:', 'Forma pago:', 'Plazo:', 'Soporte:');
        $this->SetFont('Arial', 'B', 8);
        foreach ($List1 as $col) {
            $this->SetX(150);
            $this->Cell(10, 3, $col, 0, 0, 'L');
            $this->Ln();
        }

        $List1 = array('',
            $arFactura->getFecha()->format('Y-m-d'),
            $arFactura->getFechaVence()->format('Y-m-d'),
            $arFactura->getClienteRel()->getFormaPagoRel()->getNombre(),
            $arFactura->getClienteRel()->getPlazoPago(),
            $arFactura->getSoporte());
        $this->SetXY(175,25);
        $this->SetFont('Arial', '', 14);        
        $this->Cell(30, 3, $arFactura->getNumero(), 0, 0, 'R'); 
        $this->SetY(27);
        $this->SetFont('Arial', '', 8);        
        foreach ($List1 as $col) {
            $this->SetX(175);
            $this->Cell(30, 3, $col, 0, 0, 'R');
            $this->Ln();
        }
        $this->SetY(48);
        if($arFactura->getClienteDireccionRel()) {
            $arrayTexto = array($arFactura->getClienteDireccionRel()->getNombre(),
                $arFactura->getClienteDireccionRel()->getCiudadRel()->getNombre(),
                $arFactura->getClienteDireccionRel()->getDireccion(),
                $arFactura->getClienteDireccionRel()->getBarrio());            
        } else {
            $arrayTexto = array("PRINCIPAL",
                $arFactura->getClienteRel()->getCiudadRel()->getNombre(),
                $arFactura->getClienteRel()->getDireccion(),
                $arFactura->getClienteRel()->getBarrio());                        
        }
        
        $this->SetX(110);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 3, 'DIRECCION DE ENVIO', 0, 0, 'L');
        $this->SetY(50);
        $this->Ln();
        $this->Ln(1);
        $this->SetFont('Arial', '', 8);
        foreach ($arrayTexto as $col) {
            $this->SetX(110);
            $this->Cell(10, 4, $col, 0, 0, 'L');
            $this->Ln();
        }

        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('CODIGO', 'DETALLE', 'PEDIDO', 'CANTIDAD', 'VALOR');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(15, 125, 10, 15, 30);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0)
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
        $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        $arFacturaDetalles = self::$em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            if($arFacturaDetalle->getDetalle()) {
                $strDetalle = $arFacturaDetalle->getDetalle();
            } else {
                $strDetalle = "SERVICIO " . $arFacturaDetalle->getConceptoServicioRel()->getNombre() . " DESDE EL DIA " . $arFacturaDetalle->getPedidoDetalleRel()->getDiaDesde()
                        . " HASTA EL DIA " . $arFacturaDetalle->getPedidoDetalleRel()->getDiaHasta() . " DE " .
                $this->devuelveMes($arFacturaDetalle->getPedidoDetalleRel()->getPedidoRel()->getFechaProgramacion()->format('n')) . " " . $arFacturaDetalle->getPedidoDetalleRel()->getPedidoRel()->getFechaProgramacion()->format('Y');                
            }
            $pdf->Cell(15, 4, $arFacturaDetalle->getCodigoFacturaDetallePk(), 1, 0, 'L');
            $pdf->Cell(125, 4, $strDetalle, 1, 0, 'L');
            $pdf->Cell(10, 4, $arFacturaDetalle->getPedidoDetalleRel()->getPedidoRel()->getNumero(), 1, 0, 'L');
            $pdf->Cell(15, 4, number_format($arFacturaDetalle->getCantidad(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(30, 4, number_format($arFacturaDetalle->getSubtotal(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function devuelveMes($intMes) {
        $strMes = "";
        switch ($intMes) {
            case 1:
                $strMes = "ENERO";
                break;
            case 2:
                $strMes = "FEBRERO";
                break;
            case 3:
                $strMes = "MARZO";
                break;
            case 4:
                $strMes = "ABRIL";
                break;
            case 5:
                $strMes = "MAYO";
                break;
            case 6:
                $strMes = "JUNIO";
                break;
            case 7:
                $strMes = "JULIO";
                break;
            case 8:
                $strMes = "AGOSTO";
                break;
            case 9:
                $strMes = "SEPTIEMBRE";
                break;
            case 10:
                $strMes = "OCTUBRE";
                break;
            case 11:
                $strMes = "NOVIEMBRE";
                break;
            case 12:
                $strMes = "DICIEMBRE";
                break;
        }
        return $strMes;
    }

    public function Footer() {
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = self::$em->getRepository('BrasaTurnoBundle:TurFactura')->find(self::$codigoFactura);
        $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $this->SetY(180);
        $this->line(10, $this->GetY() + 5, 205, $this->GetY() + 5);

        $this->SetFont('Arial', 'B', 7.5);
        $this->ln(7);
        $totales = array('SUBTOTAL: ' . " " . " ",
            'BASE AIU: ' . " " . " ",
            '(+)IVA: ' . " " . " ",
            '(+)RTE FUENTE: ' . " " . " ",
            '(+)RTE IVA: ' . " " . " ",
            'TOTAL GENERAL: ' . " " . " "
        );

        $this->line(10, $this->GetY() + 40, 205, $this->GetY() + 40);

        $this->SetMargins(170, 2, 15);
        for ($i = 0; $i < count($totales); $i++) {
            $this->SetX(165);
            $this->Cell(20, 4, $totales[$i], 0, 0, 'R');
            $this->ln();
        }

        $totales2 = array(number_format($arFactura->getVrSubtotal(), 0, '.', ','),
            number_format($arFactura->getVrBaseAIU(), 0, '.', ','),
            number_format($arFactura->getVrIva(), 0, '.', ','),
            number_format($arFactura->getVrRetencionFuente(), 0, '.', ','),
            number_format($arFactura->getVrRetencionIva(), 0, '.', ','),
            number_format($arFactura->getVrTotalNeto(), 0, '.', ',')
        );

        $this->SetFont('Arial', '', 7.5);
        $this->SetXY(190, $this->GetY() - 36);
        $this->ln(12);
        for ($i = 0; $i < count($totales2); $i++) {
            $this->SetX(185);
            $this->Cell(20, 4, $totales2[$i], 0, 0, 'R');
            $this->ln();
        }

        $this->SetY($this->GetY() - 20);
        $this->SetFont('Arial', 'B', 8);
        $this->SetX(10);
        $this->Cell(20, 5, 'OBSERVACIONES:', 0, 'L');

        $this->ln();
        $this->SetX(10);
        $this->SetFont('Arial', '', 8);
        $this->MultiCell(140, 3, $arFactura->getComentarios(), 0, 'L');

        $arrayNumero = explode(".", 0, 2);
        $intCentavos = 0;
        if (count($arrayNumero) > 1)
            $intCentavos = substr($arrayNumero[1], 0, 2);
        $strLetras = "";
        //$strLetras = \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($arFactura->getVrTotal()) . " con " . \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($intCentavos);
        $this->SetFont('Arial', 'B', 6);
        $this->Text(12, 224, "SON : " . substr(strtoupper(self::$strLetras), 0, 96));
        $this->Ln();

        //$Text = array($arConfiguracion->getInformacionLegalFactura());

        $this->SetFont('Arial', '', 6);
        $this->GetY($this->SetY(228));
        $this->SetX(10);
        $this->MultiCell(90, 3, $arConfiguracion->getInformacionLegalFactura());


        $this->SetFont('Arial', '', 7);
        $this->GetY($this->SetY(255));
        $this->SetX(10);
        $this->MultiCell(90, 3, $arConfiguracion->getInformacionResolucionSupervigilanciaFactura(), 0, 'L');

        $this->GetY($this->SetY(235));
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(0, 0, $this->line(100, $this->GetY() + 15, 150, $this->GetY() + 15) . $this->Text(100, $this->GetY() + 18, "AUTORIZADO"));
        $this->Cell(0, 0, $this->line(154, $this->GetY() + 15, 205, $this->GetY() + 15) . $this->Text(154, $this->GetY() + 18, "RECIBI (Nombre y firma)"));

        $this->line(10, 260, 205, 260);
        $this->SetFont('Arial', '', 7);
        $this->SetY(-70);
        $this->Ln();
        $this->line(10, 269, 205, 269);



        $this->Ln(3);
        $this->SetFont('Arial', 'B', 8);
        $this->Text(20, $this->GetY($this->SetY(264)), $arConfiguracion->getInformacionPagoFactura());
        $this->SetFont('Arial', '', 7);
        $this->Text(60, $this->GetY($this->SetY(267)), $arConfiguracion->getInformacionContactoFactura());

        //Número de página
        $this->Text(188, 273, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }

    public function GenerarEncabezadoFactura($em) {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);

        $this->SetFont('Arial', '', 5);
        $this->Text(188, 13, ' [sogaApp - turnos]');
        $this->Image('imagenes/logos/logo.jpg', 15, 15, 35, 17);
        $this->ln(11);
        $this->SetFont('Arial', 'B', 12);
        $this->ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->Text(21, 35, "NIT " . $arConfiguracion->getNitEmpresa() . "-" . $arConfiguracion->getDigitoVerificacionEmpresa());
        $this->SetXY(258, 18);
    }

}

?>
