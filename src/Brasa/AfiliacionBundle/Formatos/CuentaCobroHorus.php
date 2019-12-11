<?php

namespace Brasa\AfiliacionBundle\Formatos;

class CuentaCobroHorus extends \FPDF_FPDF {

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
        $pdf = new CuentaCobroHorus();
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

        //$this->SetFont('Arial', '', 7);
        //$this->SetXY(110, 75);
        //$this->MultiCell(140, 3, $arConfiguracionTurno->getInformacionResolucionDianFactura(), 0, 'L');

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
        $List1 = array('NOMBRE: ', 'NIT: ', 'TELEFONO:', 'DIRECCION:', 'CORREO:');
        foreach ($List1 as $col) {
            $this->Cell(50, 4, $col, 0, 0, 'L');
            $this->Ln(4);
        }

        $Datos = array(utf8_decode($arFactura->getClienteRel()->getNombreCorto()),
            $arFactura->getClienteRel()->getNit() . "-" . $arFactura->getClienteRel()->getDigitoVerificacion(),
            $arFactura->getClienteRel()->getTelefono(),
            $arFactura->getClienteRel()->getDireccion(),
            $arFactura->getClienteRel()->getEmail());
        $this->SetFont('Arial', '', 8);
        $this->SetY(54);

        foreach ($Datos as $col) {
            $this->SetX(33);
            $this->Cell(50, 4, $col, 0, 'L');
            $this->Ln(4);
        }
        $this->ln(1);
        
        $this->SetY(50);
        $List1 = array('Fecha emision:', 'Fecha vencimiento:', 'Forma pago:', 'Plazo:', 'Soporte:');
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
        
        $this->SetY(47);
        $this->SetFont('Arial', '', 8);        
        foreach ($List1 as $col) {
            $this->SetX(175);
            $this->Cell(30, 3, $col, 0, 0, 'R');
            $this->Ln();
        }       
        
        $this->SetXY(100,20);
        $this->SetFont('Arial', '', 10);        
        $this->Cell(30, 3, 'ALTURAS Y SEGURIDAD LABORAL', 0, 0, 'C');        
        $this->SetXY(100,25);
        $this->Cell(30, 3, $arConfiguracion->getNombreEmpresa(), 0, 0, 'C');        
        $this->SetXY(100,30);
        $this->Cell(30, 3, 'RUT: ' . number_format($arConfiguracion->getNitEmpresa(), 0, '.', '.') . '-' . $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'C');
        $this->SetXY(100,35);
        $this->Cell(30, 3, 'REGIMEN SIMPLIFICADO', 0, 0, 'C');
        
        $this->SetXY(150,42);
        $this->SetFont('Arial', '', 14);        
        $this->Cell(30, 3, "CUENTA DE COBRO", 0, 0, 'R');
        
        $this->SetXY(175,42);
        $this->SetFont('Arial', '', 14);        
        $this->Cell(30, 3, $arFactura->getNumero(), 0, 0, 'R');         
        
        $this->SetY(65);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {

    }

    public function Body($pdf) {
        //Cursos
        $arFacturaDetalles = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso();
        $arFacturaDetalles = self::$em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleCurso')->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        if(count($arFacturaDetalles) > 0) {
            $pdf->SetX(10);
            $pdf->Ln(14); 
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0);
            $pdf->SetFont('Arial', 'B', 14);        
            $pdf->Cell(10, 4, "", 0, 0, 'L', 1);

            $pdf->Ln(5);          
            $header = array('FECHA', 'NUMERO', 'IDENTIFICACION', 'EMPLEADO', 'CERTIFICADO', 'PRECIO');
            $pdf->SetFillColor(236, 236, 236);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(.2);
            $pdf->SetFont('', 'B', 7);

            //creamos la cabecera de la tabla.
            $w = array(20, 20, 25, 70, 40, 20);
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
                $arCursosDetalles = new \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle();
                $arCursosDetalles = self::$em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->findBy(array('codigoCursoFk' => $arFacturaDetalle->getCodigoCursoFk()));
                foreach ($arCursosDetalles as $arCursoDetalle) {
                    $pdf->Cell(20, 4, $arCursoDetalle->getCursoRel()->getFechaProgramacion()->format('Y/m/d'), 1, 0, 'L');
                    $pdf->Cell(20, 4, $arCursoDetalle->getCursoRel()->getNumero(), 1, 0, 'L');                
                    $pdf->Cell(25, 4, $arCursoDetalle->getCursoRel()->getNumeroIdentificacion(), 1, 0, 'L');                
                    $pdf->Cell(70, 4, $arCursoDetalle->getCursoRel()->getNombreCorto(), 1, 0, 'L');
                    $pdf->Cell(40, 4, $arCursoDetalle->getCursoTipoRel()->getNombre(), 1, 0, 'L');
                    $pdf->Cell(20, 4, number_format($arCursoDetalle->getPrecio(), 0, '.', ','), 1, 0, 'R');
                    $pdf->Ln();
                    $pdf->SetAutoPageBreak(true, 15);                    
                }
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
            $header = array('CODIGO', 'DESDE', 'HASTA', 'PRECIO');
            $pdf->SetFillColor(236, 236, 236);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(.2);
            $pdf->SetFont('', 'B', 7);

            //creamos la cabecera de la tabla.
            $w = array(15, 20, 20, 15, 30);
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
                $pdf->Cell(15, 4, number_format($arFacturaDetalle->getPrecio(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 15);
            }            
        }                
    }

    public function Footer() {
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        $arFactura = self::$em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find(self::$codigoFactura);
        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracionGeneral = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracion = new \Brasa\AfiliacionBundle\Entity\AfiConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaAfiliacionBundle:AfiConfiguracion')->find(1);
        $this->SetY(180);
        $this->line(10, $this->GetY() + 5, 205, $this->GetY() + 5);

        $this->SetFont('Arial', 'B', 7.5);
        $this->ln(7);
        $totales = array('SUBTOTAL: ' . " " . " ",            
            '(-)DESCUENTO: ' . " " . " ",
            'TOTAL: ' . " " . " "
        );

        $this->line(10, $this->GetY() + 40, 205, $this->GetY() + 40);

        $this->SetMargins(170, 2, 15);
        for ($i = 0; $i < count($totales); $i++) {
            $this->SetX(165);
            $this->Cell(20, 4, $totales[$i], 0, 0, 'R');
            $this->ln();
        }

        $totales2 = array(number_format($arFactura->getSubtotal(), 0, '.', ','),
            number_format(0, 0, '.', ','),            
            number_format($arFactura->getTotal(), 0, '.', ',')
        );

        $this->SetFont('Arial', '', 7.5);
        $this->SetXY(190, $this->GetY() - 24);
        $this->ln(12);
        for ($i = 0; $i < count($totales2); $i++) {
            $this->SetX(185);
            $this->Cell(20, 4, $totales2[$i], 0, 0, 'R');
            $this->ln();
        }

        $this->SetY($this->GetY() - 10);
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
        $this->Ln();

        $this->GetY($this->SetY(235));
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 6);
        $this->Text(12, $this->GetY() + 8, 'Atentamente,');
        $this->Cell(0, 0, $this->line(12, $this->GetY() + 20, 80, $this->GetY() + 20) . $this->Text(12, $this->GetY() + 23, $arConfiguracionGeneral->getRepresentanteLegal()) . $this->Text(12, $this->GetY() + 26, "C.C " . $arConfiguracionGeneral->getIdentificacionRepresentanteLegal()));        
        
        $this->SetFont('Arial', '', 7);
        $this->SetY(-70);
        $this->Ln();
        $this->line(10, 269, 205, 269);



        $this->Ln(3);
        $this->SetFont('Arial', 'B', 8);
        $this->Text(20, $this->GetY($this->SetY(230)), $arConfiguracion->getInformacionPagoFactura());
        $this->SetFont('Arial', 'B', 8);
        $this->Text(60, $this->GetY($this->SetY(267)), $arConfiguracion->getInformacionContactoFactura());
        $this->SetFont('Arial', '', 8);
        //Número de página
        $this->Text(180, 273, 'Pagina ' . $this->PageNo() . ' de {nb}');
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
        //$this->Text(21, 35, "NIT " . $arConfiguracion->getNitEmpresa() . "-" . $arConfiguracion->getDigitoVerificacionEmpresa());
        $this->SetXY(258, 18);
    }

}

?>
