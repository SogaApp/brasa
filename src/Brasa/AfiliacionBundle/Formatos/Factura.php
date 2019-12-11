<?php

namespace Brasa\AfiliacionBundle\Formatos;

class Factura extends \FPDF_FPDF {

    public static $em;
    public static $codigoFactura;
    public static $strLetras;

    public function Generar($miThis, $codigoFactura) {

        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);
        $arrayNumero = explode(".", 0, 2);
        $intCentavos = 0;
        if (count($arrayNumero) > 1)
            $intCentavos = substr($arrayNumero[1], $arFactura->getTotal(), 2);
        $strLetras = \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($arFactura->getTotal());
        self::$strLetras = $strLetras;
        ob_clean();
        $pdf = new Factura();
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
        $arConfiguracionTurno = new \Brasa\AfiliacionBundle\Entity\AfiConfiguracion();
        $arConfiguracionTurno = self::$em->getRepository('BrasaAfiliacionBundle:AfiConfiguracion')->find(1);
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        $arFactura = self::$em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find(self::$codigoFactura);
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
        $List1 = array('NOMBRE: ', 'NIT: ', 'TELEFONO:', 'DIRECCION:');
        foreach ($List1 as $col) {
            $this->Cell(50, 4, $col, 0, 0, 'L');
            $this->Ln(4);
        }

        $Datos = array($arFactura->getClienteRel()->getNombreCorto(),
            $arFactura->getClienteRel()->getNit() . "-" . $arFactura->getClienteRel()->getDigitoVerificacion(),
            $arFactura->getClienteRel()->getTelefono(),
            $arFactura->getClienteRel()->getDireccion());
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

        $List1 = array($arFactura->getFacturaTipoRel()->getNombre(), 'Fecha emision:', 'Fecha vencimiento:', 'Forma pago:', 'Plazo:', 'Soporte:');
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
            substr (utf8_decode($arFactura->getSoporte()),0,16));
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

        $arrayTexto = array("PRINCIPAL",
            $arFactura->getClienteRel()->getCiudadRel()->getNombre(),
            $arFactura->getClienteRel()->getDireccion(),
            $arFactura->getClienteRel()->getBarrio());                        
        
        
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

    }

    public function Body($pdf) {
        //Cursos
        $arFacturaDetalles = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle();
        $arFacturaDetalles = self::$em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleCurso')->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        if(count($arFacturaDetalles) > 0) {
            $pdf->SetX(10);
            $pdf->Ln(14); 
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0);
            $pdf->SetFont('Arial', 'B', 14);        
            $pdf->Cell(10, 4, "CURSOS", 0, 0, 'L', 1);

            $pdf->Ln(5);          
            $header = array('CODIGO', 'FECHA', 'IDENTIFICACION', 'EMPLEADO', 'PRECIO');
            $pdf->SetFillColor(236, 236, 236);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(.2);
            $pdf->SetFont('', 'B', 7);

            //creamos la cabecera de la tabla.
            $w = array(15, 20, 25, 80, 15, 30);
            for ($i = 0; $i < count($header); $i++)
                if ($i == 0)
                    $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
                else
                    $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

            //Restauración de colores y fuentes
            $pdf->SetFillColor(224, 235, 255);
            $pdf->SetTextColor(0);
            $pdf->SetFont('');
            $pdf->Ln(4);


            $pdf->SetX(10);
            $pdf->SetFont('Arial', '', 7);
            foreach ($arFacturaDetalles as $arFacturaDetalle) {

                $pdf->Cell(15, 4, $arFacturaDetalle->getCodigoFacturaDetalleCursoPk(), 1, 0, 'L');
                $pdf->Cell(20, 4, $arFacturaDetalle->getCursoRel()->getFechaProgramacion()->format('Y/m/d'), 1, 0, 'L');
                $pdf->Cell(25, 4, $arFacturaDetalle->getCursoRel()->getNumeroIdentificacion(), 1, 0, 'L');
                $pdf->Cell(80, 4, $arFacturaDetalle->getCursoRel()->getNombreCorto(), 1, 0, 'L');
                $pdf->Cell(15, 4, number_format($arFacturaDetalle->getPrecio(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 15);
            }            
        }
        
        //Seguridad social
        $arFacturaDetalles = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle();
        $arFacturaDetalles = self::$em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->findBy(array('codigoFacturaFk' => self::$codigoFactura));        
        if(count($arFacturaDetalles) > 0) {
            $pdf->SetX(10);
            $pdf->Ln(14); 
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0);
            $pdf->SetFont('Arial', 'B', 14);        
            $pdf->Cell(10, 4, "SEGURIDAD SOCIAL", 0, 0, 'L', 1);

            $pdf->Ln(5);          
            $header = array('CODIGO', 'DESDE', 'HASTA', 'SUBTOTAL', 'IVA', 'TOTAL');
            $pdf->SetFillColor(236, 236, 236);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(.2);
            $pdf->SetFont('', 'B', 7);

            //creamos la cabecera de la tabla.
            $w = array(15, 20, 20, 20, 20, 20);
            for ($i = 0; $i < count($header); $i++)
                if ($i == 0)
                    $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
                else
                    $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

            //Restauración de colores y fuentes
            $pdf->SetFillColor(224, 235, 255);
            $pdf->SetTextColor(0);
            $pdf->SetFont('');
            $pdf->Ln(4);

            $pdf->SetX(10);
            $pdf->SetFont('Arial', '', 7);
            foreach ($arFacturaDetalles as $arFacturaDetalle) {

                $pdf->Cell(15, 4, $arFacturaDetalle->getCodigoFacturaDetallePk(), 1, 0, 'L');
                $pdf->Cell(20, 4, $arFacturaDetalle->getFechaDesde()->format('Y/m/d'), 1, 0, 'L');
                $pdf->Cell(20, 4, $arFacturaDetalle->getFechaHasta()->format('Y/m/d'), 1, 0, 'L');
                $pdf->Cell(20, 4, number_format($arFacturaDetalle->getSubtotal(), 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arFacturaDetalle->getIva(), 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arFacturaDetalle->getTotal(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 15);
            }            
        }                
    }

    public function Footer() {
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        $arFactura = self::$em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find(self::$codigoFactura);
        $arConfiguracion = new \Brasa\AfiliacionBundle\Entity\AfiConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaAfiliacionBundle:AfiConfiguracion')->find(1);
        $this->SetY(180);
        $this->line(10, $this->GetY() + 5, 205, $this->GetY() + 5);

        $this->SetFont('Arial', 'B', 7.5);
        $this->ln(7);
        $totales = array('SUBTOTAL: ' . " " . " ",            
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

        $totales2 = array(number_format($arFactura->getSubtotal(), 0, '.', ','),
            number_format($arFactura->getIva(), 0, '.', ','),            
            number_format(0, 0, '.', ','),
            number_format(0, 0, '.', ','),
            number_format($arFactura->getTotal(), 0, '.', ',')
        );

        $this->SetFont('Arial', '', 7.5);
        $this->SetXY(190, $this->GetY() - 32);
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
        $this->Text(188, 13, ' [sogaApp - afiliacion]');
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
