<?php
namespace Brasa\TransporteBundle\Formatos;
class FormatoReciboCaja extends \FPDF_FPDF {
    public static $em;
    public static $codigoReciboCaja;
    
    public function Generar($miThis, $codigoReciboCaja) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoReciboCaja = $codigoReciboCaja;
        $pdf = new FormatoReciboCaja();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("ReciboCaja" . $codigoReciboCaja . ".pdf", 'D');                
    } 
    
    public function Header() {
        $arReciboCaja = new \Brasa\TransporteBundle\Entity\TteReciboCaja();
        $arReciboCaja = self::$em->getRepository('BrasaTransporteBundle:TteReciboCaja')->find(self::$codigoReciboCaja);        
        $this->SetFillColor(255, 255, 255);        
        $this->SetFont('Arial','B',10);
        $this->SetXY(150, 10);
        $this->Cell(2, 6, "RECIBO DE CAJA ", 0, 0, 'L', 1);        
        $this->SetXY(200, 10);
        $this->Cell(2, 6, $arReciboCaja->getCodigoReciboCajaPk(), 0, 0, 'R', 1);        
        $this->Ln();
        $intY = 20;
        // Linea1
        $this->SetFont('Arial','B', 8);
        $this->SetXY(10, $intY);
        $this->MultiCell(70, 4, "Fecha\n ", 1, 'L',false);        
        $this->SetFont('Arial','B', 8);
        $this->SetXY(80, $intY);
        $this->MultiCell(60, 4, "Origen\n ", 1, 'L',false);
        $this->SetXY(140, $intY);
        $this->MultiCell(60, 4, "Destino\n ", 1, 'L',false);
        $this->SetFont('Arial','', 8);                
        $this->Text(11, $intY + 6, $arReciboCaja->getFecha()->format('Y/m/d'));
        $this->Text(81, $intY + 6 , $arReciboCaja->getCodigoReciboCajaPk());
        $this->Text(141, $intY + 6, $arReciboCaja->getCodigoReciboCajaPk());

        $intY = $intY + 8;
        // Linea2
        $this->SetFont('Arial','B', 8);
        $this->SetXY(10, $intY);
        $this->MultiCell(70, 4, "Remite\n ", 1, 'L',false);        
        $this->SetFont('Arial','B', 8);
        $this->SetXY(80, $intY);
        $this->MultiCell(60, 4, "Direccion\n ", 1, 'L',false);
        $this->SetXY(140, $intY);
        $this->MultiCell(60, 4, "Telefono\n ", 1, 'L',false);
        $this->SetFont('Arial','', 8);                
        $this->Text(11, $intY + 6, $arReciboCaja->getCodigoReciboCajaPk());
        $this->Text(81, $intY + 6 , $arReciboCaja->getCodigoReciboCajaPk());
        $this->Text(141, $intY + 6, $arReciboCaja->getCodigoReciboCajaPk());
        
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {

    }

    public function Body($pdf) {

    }

    public function Footer() {

    }    
}

?>
