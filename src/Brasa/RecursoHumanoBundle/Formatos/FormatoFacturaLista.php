<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoFacturaLista extends \FPDF_FPDF {
    public static $em;
   
    public function Generar($miThis) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new FormatoFacturaLista('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 11);
        
        $this->Body($pdf);
        $pdf->Output("Lista_facturas.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',12);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 15);
        $this->Cell(283, 8, "LISTADO DE FACTURAS " , 1, 0, 'C', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('COD', 'NUMERO', 'FECHA', 'F. VENCE', 'CLIENTE', 'CENTRO COSTO', 'BRUTO', 'NETO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 13, 15, 15, 95, 95, 20, 20);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauraci�n de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $arFacturas = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFacturas = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->findAll();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arFacturas as $arFactura) {            
            $pdf->Cell(10, 4, $arFactura->getCodigoFacturaPk(), 1, 0, 'L');
            $pdf->Cell(13, 4, $arFactura->getNumero(), 1, 0, 'L');
            $pdf->Cell(15, 4, $arFactura->getFecha()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(15, 4, $arFactura->getFechaVence()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(95, 4, $arFactura->getTerceroRel()->getNombreCorto(), 1, 0, 'L');
            $pdf->Cell(95, 4, $arFactura->getCentroCostoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(20, 4, number_format($arFactura->getVrBruto(), 2,'.',','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arFactura->getVrNeto(), 2,'.',','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);//33
        }        
    }

    public function Footer() {
        $this->SetXY(245, 190);
        $this->Cell(30, 35, utf8_decode('   Página ') . $this->PageNo() . ' de {nb}' , 0, 0, 'L', 0);          
    }    
}
