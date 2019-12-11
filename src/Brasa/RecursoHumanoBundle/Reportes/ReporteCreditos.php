<?php
namespace Brasa\RecursoHumanoBundle\Reportes;
class ReporteCreditos extends \FPDF_FPDF {
    public static $em;
    public static $strDql;    
    
    public function Generar($miThis, $dql) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$strDql = $dql;
        $pdf = new ReporteCreditos('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("ReporteCreditos.pdf", 'D');        
        
    } 
    public function Header() {

        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        $this->SetXY(10, 10);
        $this->Cell(286, 8, 'REPORTE DE CREDITOS', 1, 0, 'C', 1);                                                
        $this->Ln(12);
        $this->EncabezadoDetalles();
        
    }
    public function EncabezadoDetalles() {
        
        $header = array('ID','TIPO', 'FECHA', 'CENTRO COSTOS', 'IDENTIFICACION', 'EMPLEADO', 'VR. CREDITO', 'VR. CUOTA', 'VR. SALDO', 'CUOTAS', 'C. ACTUAL');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 6);

        //creamos la cabecera de la tabla.
        $w = array(10, 48, 12, 79, 18, 53, 15, 13,15,10,13);
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
        $query = self::$em->createQuery(self::$strDql);
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $query->getResult();     
        $douTotalSaldo = 0;
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 6);
        foreach ($arCreditos as $arCredito) {            
            $pdf->Cell(10, 4, $arCredito->getCodigoCreditoPk(), 1, 0, 'L');
            $pdf->Cell(48, 4, utf8_decode($arCredito->getCreditoTipoRel()->getNombre()), 1, 0, 'L');            
            $pdf->Cell(12, 4, $arCredito->getFecha()->format('Y/m/d'), 1, 0, 'L');            
            $pdf->Cell(79, 4, utf8_decode($arCredito->getEmpleadoRel()->getCentroCostoRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(18, 4, $arCredito->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(53, 4, utf8_decode($arCredito->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(15, 4, number_format($arCredito->getVrPagar(), 2,'.',','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arCredito->getVrCuota(), 2,'.',','), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($arCredito->getSaldo(), 2,'.',','), 1, 0, 'R');
            $pdf->Cell(10, 4, $arCredito->getNumeroCuotas(), 1, 0, 'L');
            $pdf->Cell(13, 4, $arCredito->getNumeroCuotaActual(), 1, 0, 'L');
            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
            $douTotalSaldo += $arCredito->getSaldo();
        }    
        $pdf->Cell(10, 4, "", 1, 0, 'L');
        $pdf->Cell(48, 4, "", 1, 0, 'L');            
        $pdf->Cell(12, 4, "", 1, 0, 'L');            
        $pdf->Cell(79, 4, "", 1, 0, 'L');
        $pdf->Cell(18, 4, "", 1, 0, 'L');
        $pdf->Cell(53, 4, "", 1, 0, 'L');
        $pdf->Cell(15, 4, "", 1, 0, 'L');
        $pdf->Cell(13, 4, "", 1, 0, 'L');
        $pdf->Cell(15, 4, number_format($douTotalSaldo, 2, '.', ','), 1, 0, 'R');
        $pdf->Cell(10, 4, "", 1, 0, 'L');
        $pdf->Cell(13, 4, "", 1, 0, 'L');
        
        $pdf->Ln();        
    }
    public function Footer() {
        $this->SetFont('Arial','', 8);  
        $this->Text(265, 205, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
