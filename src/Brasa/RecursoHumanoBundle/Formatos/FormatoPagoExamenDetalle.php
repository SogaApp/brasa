<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoPagoExamenDetalle extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoPagoExamen;
    
    public function Generar($miThis, $codigoPagoExamen) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoPagoExamen = $codigoPagoExamen;
        $pdf = new FormatoPagoExamenDetalle();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("PagoExamenDetalle$codigoPagoExamen.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(3);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->SetXY(50, 10);
        $this->Cell(150, 7, utf8_decode("DETALLE PAGO ENTIDAD EXAMEN"), 0, 0, 'C', 1);
        $this->SetXY(50, 18);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 30);
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
        $arPagoExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen();
        $arPagoExamen = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->find(self::$codigoPagoExamen);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200); 
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','',8);
        $this->Cell(30, 6, $arPagoExamen->getCodigoPagoExamenPk(), 1, 0, 'R', 1);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "ENTIDAD:" , 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','',8);
        $this->Cell(100, 6, utf8_decode($arPagoExamen->getEntidadExamenRel()->getNombre()), 1, 0, 'L', 1);
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("NÚMERO SOPORTE:") , 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','',8);
        $this->Cell(30, 5, $arPagoExamen->getNumeroSoporte() , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "TOTAL:" , 1, 0, 'R', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','',8);
        $this->Cell(100, 5, number_format($arPagoExamen->getVrTotal(), 2, '.', ',') , 1, 0, 'R', 1);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array(utf8_decode('CÓDIGO'), utf8_decode('IDENTIFICACIÓN'), 'NOMBRE', 'CP' ,'PRECIO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(20, 25, 125,5, 15);
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
        $arPagoExamenDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle();
        $arPagoExamenDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamenDetalle')->findBy(array('codigoPagoExamenFk' => self::$codigoPagoExamen));
        
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $var = 0;
        foreach ($arPagoExamenDetalles as $arPagoExamenDetalle) {
            if ($arPagoExamenDetalle->getExamenRel()->getControlPago() == 0){
                $srtControlExamen = "NO";
            } else {
                $srtControlExamen = "SI";
            }
            $pdf->Cell(20, 4, $arPagoExamenDetalle->getCodigoPagoExamenDetallePk(), 1, 0, 'L');
            $pdf->Cell(25, 4, $arPagoExamenDetalle->getExamenRel()->getIdentificacion(), 1, 0, 'L');
            $pdf->Cell(125, 4, utf8_decode($arPagoExamenDetalle->getExamenRel()->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(5, 4, $srtControlExamen, 1, 0, 'L');
            $pdf->Cell(15, 4, number_format($arPagoExamenDetalle->getVrPrecio(), 2, '.', ','), 1, 0, 'R');
            $var += $arPagoExamenDetalle->getVrPrecio();
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
            
        }
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(175, 5, "TOTAL: ", 1, 0, 'R');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(15, 5, number_format($var,2, '.', ','), 1, 0, 'R');
        
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
