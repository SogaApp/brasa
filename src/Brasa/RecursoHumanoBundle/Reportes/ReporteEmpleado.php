<?php
namespace Brasa\RecursoHumanoBundle\Reportes;
class ReporteEmpleado extends \FPDF_FPDF {
    public static $em;
    
    public static $strDql;    
    
    public function Generar($miThis, $dql) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$strDql = $dql;
        $pdf = new ReporteEmpleado('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("ReporteEmpleados.pdf", 'D');        
    } 
    
    public function Header() {
        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleados = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findAll();
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',12);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(5, 15);
        $this->Cell(288, 8, "LISTADO EMPLEADOS " , 1, 0, 'C', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $this->SetX(5);
        $header = array(utf8_decode('CÓDIGO'), utf8_decode('IDENTIFICACIÓN'), 'EMPLEADO', 'FECHA CONTRATO', 'CENTRO COSTO','EPS', utf8_decode('PENSIÓN'),utf8_decode('CAJA COMPENSACIÓN'));
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 6.5);

        //creamos la cabecera de la tabla.
        $w = array(12, 21, 50, 25, 50, 50, 30, 50,);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauraci�n de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $query = self::$em->createQuery(self::$strDql);
        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleados = $query->getResult();
        
        $pdf->SetX(5);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arEmpleados as $arEmpleado) {
            if ($arEmpleado->getCodigoCentroCostoFk() == Null) {
                $centroCosto = "Sin definir";
            }else {
                $centroCosto = utf8_decode($arEmpleado->getCentroCostoRel()->getNombre());
            }
            if ($arEmpleado->getFechaContrato() == Null) {
                $dateFechaContrato = "Sin definir";
            }else {
                $dateFechaContrato = $arEmpleado->getFechaContrato()->format('Y/m/d');
            }
            $pdf->SetX(5);
            $pdf->Cell(12, 4, $arEmpleado->getCodigoEmpleadoPk(), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(21, 4, $arEmpleado->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(50, 4, utf8_decode($arEmpleado->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(25, 4, $dateFechaContrato, 1, 0, 'L');
            $pdf->Cell(50, 4, $centroCosto, 1, 0, 'L');
            $pdf->Cell(50, 4, utf8_decode($arEmpleado->getEntidadSaludRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(30, 4, utf8_decode($arEmpleado->getEntidadPensionRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(50, 4, utf8_decode($arEmpleado->getEntidadCajaRel()->getNombre()), 1, 0, 'L');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }        
    }

    public function Footer() {
        $this->SetXY(245, 185);
        $this->Cell(30, 35, utf8_decode('Página ') . $this->PageNo() . ' de {nb}' , 0, 0, 'L', 0);          
    }    
}

?>
