<?php
namespace Brasa\CarteraBundle\Formatos;
class FormatoRecibo extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoRecibo;
    
    public function Generar($miThis, $codigoRecibo) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoRecibo = $codigoRecibo;
        $pdf = new FormatoRecibo();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Recibo$codigoRecibo.pdf", 'D');        
        
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
        $this->Cell(150, 7, utf8_decode("RECIBO DE CAJA"), 0, 0, 'C', 1);
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
        
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();
        $arRecibo = self::$em->getRepository('BrasaCarteraBundle:CarRecibo')->find(self::$codigoRecibo);        
        
        $arReciboDetalles = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
        $arReciboDetalles = self::$em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => self::$codigoRecibo));
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        
        $intY = 40;
        //linea 1
        $this->SetFillColor(272, 272, 272); 
        $this->SetXY(10, $intY);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, $arRecibo->getCodigoReciboPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("NÚMERO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(52, 5, $arRecibo->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, utf8_decode("TOTAL DCTO:") , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, number_format($arRecibo->getVrTotalDescuento(), 2, '.', ','), 1, 0, 'R', 1);
        //linea 2
        $this->SetXY(10, $intY+5);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("CLIENTE:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(52, 5, $arRecibo->getClienteRel()->getNombreCorto(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("NIT:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, $arRecibo->getClienteRel()->getNit(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, utf8_decode("T. AJ. PESO:") , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, number_format($arRecibo->getVrTotalAjustePeso(), 2, '.', ','), 1, 0, 'R', 1);
        //linea 3
        $this->SetXY(10, $intY+10);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("CUENTA BANCO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(52, 5, $arRecibo->getCuentaRel()->getNombre(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("TIPO RECIBO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(52, 5, $arRecibo->getReciboTipoRel()->getNombre(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, utf8_decode("T. RETE ICA:") , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, number_format($arRecibo->getVrTotalReteIca(), 2, '.', ','), 1, 0, 'R', 1);
        //linea 4
        $this->SetXY(10, $intY+15);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("FECHA:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, $arRecibo->getFecha()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("FECHA PAGO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, $arRecibo->getFechaPago()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, utf8_decode("T. RETE IVA:") , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, number_format($arRecibo->getVrTotalReteIva(), 2, '.', ','), 1, 0, 'R', 1);
        //linea 5
        $this->SetXY(10, $intY+20);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("ANULADO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arRecibo->getEstadoAnulado() == 1){
            $estadoAnulado = "SI";
        } else {
            $estadoAnulado = "NO";
        }
        $this->Cell(52, 5, $estadoAnulado, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("ASESOR:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(52, 5, $arRecibo->getAsesorRel()->getNombre(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, utf8_decode("T. RET FUENTE:") , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, number_format($arRecibo->getVrTotalReteFuente(), 2, '.', ','), 1, 0, 'R', 1);
        //linea 6
        $this->SetXY(10, $intY+25);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("EXPORTADO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arRecibo->getEstadoExportado() == 1){
            $estadoExportado = "SI";
        } else {
            $estadoExportado = "NO";
        }
        $this->Cell(52, 5, $estadoExportado, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("AUTORIZADO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arRecibo->getEstadoAutorizado() == 1){
            $estadoAutorizado = "SI";
        } else {
            $estadoAutorizado = "NO";
        }
        $this->Cell(52, 5, $estadoAutorizado, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, utf8_decode("VALOR:") , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, number_format($arRecibo->getVrTotal(), 2, '.', ','), 1, 0, 'R', 1);
        //linea 7
        $this->SetXY(10, $intY+30);
        $this->Cell(26, 5, "" , 1, 0, 'L', 1);
        $this->Cell(52, 5, "" , 1, 0, 'L', 1);
        $this->Cell(21, 5, "" , 1, 0, 'L', 1);
        $this->Cell(52, 5, "" , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, "TOTAL PAGO:" , 1, 0, 'R', 1);
        $this->Cell(20, 5, number_format($arRecibo->getVrTotalPago(), 2, '.', ','), 1, 0, 'R', 1);
        //linea 8
        $this->SetXY(10, $intY+35);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("COMENTARIOS:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(168, 5, $arRecibo->getComentarios(), 1, 0, 'L', 1);
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('COD', 'TIPO', utf8_decode('NUMERO'),'DESCUENTO', 'AJUSTE PESO', 'RETE ICA', 'RETE IVA', 'RETE FUENTE', 'VALOR', 'SALDO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7.5);

        //creamos la cabecera de la tabla.
        $w = array(11, 33, 14, 19, 20, 18, 18,20,20,20);
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
        $arReciboDetalles = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
        $arReciboDetalles = self::$em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => self::$codigoRecibo));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arReciboDetalles as $arReciboDetalle) {            
            $pdf->Cell(11, 4, $arReciboDetalle->getCodigoReciboDetallePk(), 1, 0, 'L');
            $pdf->Cell(33, 4, utf8_decode($arReciboDetalle->getCuentaCobrarTipoRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(14, 4, $arReciboDetalle->getNumeroFactura(), 1, 0, 'L');            
            $pdf->Cell(19, 4, number_format($arReciboDetalle->getVrDescuento(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arReciboDetalle->getVrAjustePeso(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(18, 4, number_format($arReciboDetalle->getVrReteIca(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(18, 4, number_format($arReciboDetalle->getVrReteIva(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arReciboDetalle->getVrReteFuente(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arReciboDetalle->getValor(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arReciboDetalle->getCuentaCobrarRel()->getSaldo(), 2, '.', ','), 1, 0, 'R');                                             
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
        $pdf->SetFont('Arial', 'B', 7);
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();
        $arRecibo = self::$em->getRepository('BrasaCarteraBundle:CarRecibo')->find(self::$codigoRecibo); 
        $pdf->Cell(197, 5, utf8_decode("Usuario sistema: ").' '.$arRecibo->getUsuario() , 0, 0, 'L', 0);
    }

    public function Footer() {
        
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
