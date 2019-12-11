<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoFactura extends \FPDF_FPDF {
    public static $em;
    public static $codigoFactura;
    public function Generar($miThis, $codigoFactura) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $pdf = new FormatoFactura();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 10);
        $this->Body($pdf);

        $pdf->Output("Factura$codigoFactura.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find(self::$codigoFactura);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(11);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("FACTURA DE VENTA N°: ". $arFactura->getCodigoFacturaPk().""), 0, 0, 'C', 1);
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
        //ENCABEZADO
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(10, 40);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(79, 6, $arFactura->getCodigoFacturaPk() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(25, 6, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, $arFactura->getFecha()->format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(22, 6, "BRUTO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, number_format($arFactura->getVrBruto(), 2,'.',',') , 1, 0, 'R', 1);
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("NÚMERO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(79, 6, $arFactura->getNumero() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(25, 6, "VENCE:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, $arFactura->getFechaVence()->format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(22, 6, "(-)RETE FUENTE:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, number_format($arFactura->getVrRetencionFuente(), 2,'.',',') , 1, 0, 'R', 1);
        $this->SetXY(10, 50);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, "TERCERO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(126, 6, utf8_decode($arFactura->getTerceroRel()->getNombreCorto()) , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(22, 6, "(-)RETE CREE:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, number_format($arFactura->getVrRetencionCree(), 2,'.',',') , 1, 0, 'R', 1);
        $this->SetXY(10, 55);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, "CENTRO COSTO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',6);
        $this->Cell(79, 6, $arFactura->getCentroCostoRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(25, 6, "BASE AIU:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, number_format($arFactura->getVrBaseAIU(), 2,'.',',') , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(22, 6, "(+)IVA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, number_format($arFactura->getVrIva(), 2,'.',',') , 1, 0, 'R', 1);
        $this->SetXY(10, 60);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, "" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(79, 6, "" , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(25, 6, utf8_decode("ADMINISTRACIÓN:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, number_format($arFactura->getVrTotalAdministracion(), 2,'.',',') , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(22, 6, "(-)RETE IVA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, number_format($arFactura->getVrRetencionIva(), 2,'.',',') , 1, 0, 'R', 1);
        $this->SetXY(10, 65);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, "" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(79, 6, "" , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(25, 6, utf8_decode("INGRESO MISIÓN:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, number_format($arFactura->getVrIngresoMision(), 2,'.',',') , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(22, 6, "TOTAL NETO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, number_format($arFactura->getVrNeto(), 2,'.',',') , 1, 0, 'R', 1);
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(10);
        $header = array('COD','IBC','T.A','V.A','AUX. TRANS','ARP', 'EPS', 'PENSION', 'CAJA', 'CESANTIAS', 'VACACIONES', 'ADMON');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 18, 16, 16, 17, 17, 17, 17, 17, 17, 18, 14);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $arFacturaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
        $arFacturaDetalle = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arFacturaDetalle as $arFacturaDetalle) {            
            $pdf->Cell(10, 4, $arFacturaDetalle->getCodigoFacturaDetallePk(), 1, 0, 'L');
            $pdf->Cell(18, 4, number_format($arFacturaDetalle->getVrIngresoBaseCotizacion(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(16, 4, number_format($arFacturaDetalle->getVrAdicionalTiempo(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(16, 4, number_format($arFacturaDetalle->getVrAdicionalValor(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(17, 4, number_format($arFacturaDetalle->getVrAuxilioTransporte(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(17, 4, number_format($arFacturaDetalle->getVrArp(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(17, 4, number_format($arFacturaDetalle->getVrEps(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(17, 4, number_format($arFacturaDetalle->getVrPension(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(17, 4, number_format($arFacturaDetalle->getVrCaja(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(17, 4, number_format($arFacturaDetalle->getVrCesantias(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(18, 4, number_format($arFacturaDetalle->getVrVacaciones(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(14, 4, number_format($arFacturaDetalle->getVrAdministracion(), 2, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 33);
        }      
    }

    public function Footer() {
        
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
