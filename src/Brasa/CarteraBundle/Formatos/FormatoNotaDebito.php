<?php
namespace Brasa\CarteraBundle\Formatos;
class FormatoNotaDebito extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoNotaDebito;
    
    public function Generar($miThis, $codigoNotaDebito) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoNotaDebito = $codigoNotaDebito;
        $pdf = new FormatoNotaDebito();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("NotaDebito$codigoNotaDebito.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("NOTA DEBITO"), 0, 0, 'C', 1);
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
        
        $arNotaDebito = new \Brasa\CarteraBundle\Entity\CarNotaDebito();
        $arNotaDebito = self::$em->getRepository('BrasaCarteraBundle:CarNotaDebito')->find(self::$codigoNotaDebito);        
        
        $arNotaDebitoDetalles = new \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle();
        $arNotaDebitoDetalles = self::$em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->findBy(array('codigoNotaDebitoFk' => self::$codigoNotaDebito));
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        
        $intY = 40;
        //linea 1
        $this->SetFillColor(272, 272, 272); 
        $this->SetXY(10, $intY);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, $arNotaDebito->getCodigoNotaDebitoPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("NÚMERO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(52, 5, $arNotaDebito->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, utf8_decode("") , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, "", 1, 0, 'R', 1);
        //linea 2
        $this->SetXY(10, $intY+5);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("CLIENTE:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',6.5);
        $this->Cell(52, 5, utf8_decode($arNotaDebito->getClienteRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("NIT:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, $arNotaDebito->getClienteRel()->getNit(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, utf8_decode("") , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, "", 1, 0, 'R', 1);
        //linea 3
        $this->SetXY(10, $intY+10);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("CUENTA BANCO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(52, 5, utf8_decode($arNotaDebito->getCuentaRel()->getNombre()), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("CONCEPTO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(52, 5, utf8_decode($arNotaDebito->getNotaDebitoConceptoRel()->getNombre()), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, utf8_decode("") , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, "", 1, 0, 'R', 1);
        //linea 4
        $this->SetXY(10, $intY+15);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("FECHA:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, $arNotaDebito->getFecha()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("FECHA PAGO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, $arNotaDebito->getFechaPago()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, utf8_decode("") , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, "", 1, 0, 'R', 1);
        //linea 5
        $this->SetXY(10, $intY+20);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("ANULADO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arNotaDebito->getEstadoAnulado() == 1){
            $estadoAnulado = "SI";
        } else {
            $estadoAnulado = "NO";
        }
        $this->Cell(52, 5, $estadoAnulado, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("IMPRESO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arNotaDebito->getEstadoImpreso() == 1){
            $estadoImpreso = "SI";
        } else {
            $estadoImpreso = "NO";
        }
        $this->Cell(52, 5, $estadoImpreso, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, utf8_decode("") , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, "", 1, 0, 'R', 1);
        //linea 6
        $this->SetXY(10, $intY+25);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("EXPORTADO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arNotaDebito->getEstadoExportado() == 1){
            $estadoExportado = "SI";
        } else {
            $estadoExportado = "NO";
        }
        $this->Cell(52, 5, $estadoExportado, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("AUTORIZADO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arNotaDebito->getEstadoAutorizado() == 1){
            $estadoAutorizado = "SI";
        } else {
            $estadoAutorizado = "NO";
        }
        $this->Cell(52, 5, $estadoAutorizado, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, utf8_decode("TOTAL:") , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, number_format($arNotaDebito->getValor(), 2, '.', ','), 1, 0, 'R', 1);
        //linea 7
        $this->SetXY(10, $intY+30);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("COMENTARIOS:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',6.5);
        $this->Cell(168, 5, utf8_decode($arNotaDebito->getComentarios()), 1, 0, 'L', 1);
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('CODIGO', utf8_decode('NUMERO'), 'TIPO CUENTA COBRAR','VALOR');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7.5);

        //creamos la cabecera de la tabla.
        $w = array(20, 25, 110, 40);
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
        $arNotaDebitoDetalles = new \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle();
        $arNotaDebitoDetalles = self::$em->getRepository('BrasaCarteraBundle:CarNotaDebitoDetalle')->findBy(array('codigoNotaDebitoFk' => self::$codigoNotaDebito));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        foreach ($arNotaDebitoDetalles as $arNotaDebitoDetalle) {            
            $pdf->Cell(20, 4, $arNotaDebitoDetalle->getCodigoNotaDebitoDetallePk(), 1, 0, 'L');
            $pdf->Cell(25, 4, $arNotaDebitoDetalle->getNumeroFactura(), 1, 0, 'L');
            $pdf->Cell(110, 4, utf8_decode($arNotaDebitoDetalle->getCuentaCobrarTipoRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(40, 4, number_format($arNotaDebitoDetalle->getValor(), 2, '.', ','), 1, 0, 'R');
                                             
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
        
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
