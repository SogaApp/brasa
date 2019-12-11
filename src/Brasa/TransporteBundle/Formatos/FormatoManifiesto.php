<?php
namespace Brasa\TransporteBundle\Formatos;
class FormatoManifiesto extends \FPDF_FPDF {
    public static $em;
    public static $codigoDespacho;
    public function Generar($miThis, $codigoDespacho) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoDespacho = $codigoDespacho;
        $pdf = new FormatoManifiesto();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Despacho$codigoDespacho.pdf", 'D');        
        
    } 
    public function Header() {
        $arDespacho = new \Brasa\TransporteBundle\Entity\TteDespacho();
        $arDespacho = self::$em->getRepository('BrasaTransporteBundle:TteDespacho')->find(self::$codigoDespacho);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        $this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(150, 20);
        $this->Cell(50, 6, "RELACION DE ENTREGA " . $arDespacho->getCodigoDespachoPk(), 1, 0, 'L', 1);
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('NUMERO', 'FECHA', 'DESTINATARIO', 'DESTINO', 'DIRECCION', 'TELEFONO', 'UNIDADES', 'DECLARADO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(14, 14, 42, 30,30,15, 15, 30);
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
        $arGuias = new \Brasa\TransporteBundle\Entity\TteGuia();
        $arGuias = self::$em->getRepository('BrasaTransporteBundle:TteGuia')->findBy(array('codigoDespachoFk' => self::$codigoDespacho));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arGuias as $arGuias) {            
            $pdf->Cell(14, 4, $arGuias->getNumeroGuia(), 1, 0, 'L');
            $pdf->Cell(14, 4, $arGuias->getFechaIngreso()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(42, 4, substr($arGuias->getNombreDestinatario(), 0, 25), 1, 0, 'L');
            $pdf->Cell(30, 4, substr($arGuias->getCiudadDestinoRel()->getNombre(), 0, 18), 1, 0);
            $pdf->Cell(30, 4, substr($arGuias->getDireccionDestinatario(), 0, 18), 1, 0);
            $pdf->Cell(15, 4, $arGuias->getTelefonoDestinatario(), 1, 0);
            $pdf->Cell(15, 4, $arGuias->getCtUnidades(), 1, 0, 'R');
            $pdf->Cell(30, 4, number_format($arGuias->getVrDeclarado(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 33);
        }        
    }

    public function Footer() {
        $this->SetFont('Arial','B', 9);    
        $this->Line(30, 271, 100, 271);        
        $this->Line(120, 271, 180, 271);        
        $this->Text(50, 275, "FIRMA RECIBIDO"); 
        $this->Text(140, 275, "FIRMA BODEGA");
        $this->SetFont('Arial','', 10);  
        $this->Text(170, 290, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }    
}

?>
