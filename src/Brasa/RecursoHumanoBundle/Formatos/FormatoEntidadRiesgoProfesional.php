<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoEntidadRiesgoProfesional extends \FPDF_FPDF {
    public static $em;
   
    public function Generar($miThis) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new FormatoEntidadRiesgoProfesional('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 11);
        $this->Body($pdf);
        $pdf->Output("Lista_entidades_riesgos_profesionales.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',12);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 20);
        $this->Cell(270, 8, "LISTADO ENTIDADES DE RIESGOS PROFESIONALES " , 1, 0, 'C', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('COD', 'NOMBRE', 'NIT', 'DIRECCION', 'TELEFONO','INTERFACE');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(10, 130, 20, 70, 20,20);
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
        $arEntidadesRiesgoProfesional = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional();
        $arEntidadesRiesgoProfesional = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->findAll();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        foreach ($arEntidadesRiesgoProfesional as $arEntidadesRiesgoProfesional) {            
            $pdf->Cell(10, 4, $arEntidadesRiesgoProfesional->getcodigoEntidadRiesgoPk(), 1, 0, 'C');
            $pdf->Cell(130, 4, utf8_decode($arEntidadesRiesgoProfesional->getNombre()), 1, 0, 'L');
            $pdf->Cell(20, 4, $arEntidadesRiesgoProfesional->getNit(), 1, 0, 'L');
            $pdf->Cell(70, 4, $arEntidadesRiesgoProfesional->getDireccion(), 1, 0, 'L');
            $pdf->Cell(20, 4, $arEntidadesRiesgoProfesional->getTelefono(), 1, 0, 'L');
            $pdf->Cell(20, 4, $arEntidadesRiesgoProfesional->getCodigoInterface(), 1, 0, 'C');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);//33
        }        
    }

    public function Footer() {
        $this->SetXY(260, 190);
        $this->Cell(30, 35, utf8_decode('   Página ') . $this->PageNo() . ' de {nb}' , 0, 0, 'L', 0);          
    }    
}
