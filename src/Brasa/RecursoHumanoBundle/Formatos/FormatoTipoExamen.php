<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoTipoExamen extends \FPDF_FPDF {
    public static $em;
   
    public function Generar($miThis) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new FormatoTipoExamen();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 11);
        $this->Body($pdf);
        $pdf->Output("Lista_tipo_examen.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',12);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 20);
        $this->Cell(190, 8, "LISTADO TIPOS DE EXAMENES " , 1, 0, 'C', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('CODIGO', 'NOMBRE');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(20, 170);
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
        $arTiposExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo();
        $arTiposExamen = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->findAll();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arTiposExamen as $arTiposExamen) {            
            $pdf->Cell(20, 4, $arTiposExamen->getCodigoExamenTipoPk(), 1, 0, 'L');
            $pdf->Cell(170, 4, utf8_decode($arTiposExamen->getNombre()), 1, 0, 'L');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);//33
        }        
    }

    public function Footer() {
        $this->SetXY(160, 270);
        $this->Cell(30, 35, utf8_decode('Página ') . $this->PageNo() . ' de {nb}' , 0, 0, 'L', 0);          
    }    
}
