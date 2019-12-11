<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoSede extends \FPDF_FPDF {
    public static $em;
    
    public static $strDql;
    
    public function Generar($miThis, $dql) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$strDql = $dql;
        $pdf = new FormatoSede();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 11);
        $this->Body($pdf);
        $pdf->Output("Lista_sedes.pdf", 'D');        
    } 
    
    public function Header() {
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',12);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 20);
        $this->Cell(193, 8, "LISTADO SEDES" , 1, 0, 'C', 1);
        $this->EncabezadoDetalles();
    }
    
    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('CODIGO', 'CENTRO COSTO', 'NOMBRE');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 8);
        //creamos la cabecera de la tabla.
        $w = array(13, 100, 80);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
        //Restauracion de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }
    
    public function Body($pdf) {
        $query = self::$em->createQuery(self::$strDql);
        $arSedes = new \Brasa\RecursoHumanoBundle\Entity\RhuSede();
        $arSedes = $query->getResult();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arSedes as $arSede) {            
            $pdf->Cell(13, 4, $arSede->getCodigoSedePk(), 1, 0, 'L');
            $pdf->Cell(100, 4, utf8_decode($arSede->getCentroCostoRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(80, 4, utf8_decode($arSede->getNombre()), 1, 0, 'L');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }        
    }
    
    public function Footer() {
        $this->SetXY(160, 270);
        $this->Cell(30, 35, utf8_decode('   Página ') . $this->PageNo() . ' de {nb}' , 0, 0, 'L', 0);          
    }    
}


