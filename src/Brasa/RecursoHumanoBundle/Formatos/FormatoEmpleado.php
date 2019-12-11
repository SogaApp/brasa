<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoEmpleado extends \FPDF_FPDF {
    public static $em;
    
    public static $strDql;
    
    public function Generar($miThis, $dql) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$strDql = $dql;
        $pdf = new FormatoEmpleado('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 11);
        
        $this->Body($pdf);
        $pdf->Output("Lista_empleados.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',12);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 20);
        $this->Cell(275, 8, "LISTADO DE EMPLEADOS " , 1, 0, 'C', 1);
        $this->EncabezadoDetalles();
        
    }
    
    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('CODIGO', 'IDENTIFICACION', 'NOMBRE', 'CENTRO COSTOS', 'F. NACIMIENTO', 'TELEFONO', 'SALARIO', 'AUX.T', 'ACTIVO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(12, 22, 73, 95, 20, 16, 17, 10, 10);
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
        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleados = $query->getResult();
        //$arEmpleados = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findAll();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arEmpleados as $arEmpleado) {            
            $pdf->Cell(12, 4, $arEmpleado->getCodigoEmpleadoPk(), 1, 0, 'L');
            $pdf->Cell(22, 4, $arEmpleado->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(73, 4, utf8_decode($arEmpleado->getNombreCorto()), 1, 0, 'L');
            if ($arEmpleado->getCodigoCentroCostoFk() <> ""){
                $pdf->Cell(95, 4, utf8_decode($arEmpleado->getCentroCostoRel()->getNombre()), 1, 0, 'L');
            } else {
                $pdf->Cell(95, 4, "", 1, 0, 'L');
            }
            $pdf->Cell(20, 4, $arEmpleado->getFechaNacimiento()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(16, 4, $arEmpleado->getTelefono(), 1, 0, 'L');
            $pdf->Cell(17, 4, number_format($arEmpleado->getVrSalario(), 2, '.', ','), 1, 0, 'R');
            if ($arEmpleado->getAuxilioTransporte() == 1) {    
                $pdf->Cell(10, 4, "SI", 1, 0, 'L');
            } else {
                $pdf->Cell(10, 4, "NO", 1, 0, 'L');
            }
            if ($arEmpleado->getEstadoActivo() == 1){    
                $pdf->Cell(10, 4, "SI", 1, 0, 'L');
            } else {
                $pdf->Cell(10, 4, "NO", 1, 0, 'L');
            }
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }        
    }
    
    public function Footer() {
        $this->SetXY(245, 190);
        $this->Cell(30, 35, utf8_decode('   Página ') . $this->PageNo() . ' de {nb}' , 0, 0, 'L', 0);          
    }    
}


