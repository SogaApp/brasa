<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoBanco extends \FPDF_FPDF {
    public static $em;
   
    public function Generar($miThis) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new FormatoBanco('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $this->Body($pdf);
        $pdf->Output("Lista_entidades_bancarias.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',12);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 20);
        $this->Cell(278, 8, "LISTADO ENTIDADES BANCARIAS" , 1, 0, 'C', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array(utf8_decode('CÓDIGO'), 'NOMBRE','NIT',utf8_decode('CÓDIGO GENERAL'),'CONVENIO',utf8_decode('TELÉFONO'),utf8_decode('DIRECCIÓN'),utf8_decode('NÚMERO DIGITOS'));
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 8);
        //creamos la cabecera de la tabla.
        $w = array(14, 100, 20,30,25,25,36,28);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
        //Restauracion de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $arBancos = new \Brasa\RecursoHumanoBundle\Entity\RhuBanco();
        $arBancos = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuBanco')->findAll();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        foreach ($arBancos as $arBancos) {            
            if ($arBancos->getConvenioNomina() == 1){
                $convenio = "SI";
            } else {
                $convenio = "NO";
            }
            $pdf->Cell(14, 4, $arBancos->getCodigoBancoPk(), 1, 0, 'L');
            $pdf->Cell(100, 4, utf8_decode($arBancos->getNombre()), 1, 0, 'L');
            $pdf->Cell(20, 4, $arBancos->getNit(), 1, 0, 'R');
            $pdf->Cell(30, 4, $arBancos->getCodigoGeneral(), 1, 0, 'R');
            $pdf->Cell(25, 4, $convenio, 1, 0, 'L');
            $pdf->Cell(25, 4, $arBancos->getTelefono(), 1, 0, 'R');
            $pdf->Cell(36, 4, $arBancos->getDireccion(), 1, 0, 'L');
            $pdf->Cell(28, 4, $arBancos->getNumeroDigitos(), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);//33
        }        
    }

    public function Footer() {
        $this->SetXY(260, 185);
        $this->Cell(30, 35, utf8_decode('   Página ') . $this->PageNo() . ' de {nb}' , 0, 0, 'L', 0);          
    }    
}
