<?php
namespace Brasa\RecursoHumanoBundle\Reportes;
class ReporteServiciosPorCobrar extends \FPDF_FPDF {
    public static $em;
    
    public static $strDql;    
    
    public function Generar($miThis, $dql) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$strDql = $dql;
        $pdf = new ReporteServiciosPorCobrar('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("ReporteServiciosPorCobrar.pdf", 'D');        
    } 
    
    public function Header() {

        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        $this->SetXY(5, 10);
        $this->Cell(289, 8, 'REPORTE SERVICIOS POR COBRAR', 1, 0, 'C', 1);                                                
        $this->Ln(12);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->SetX(5);
        $header = array(utf8_decode('CÓDIGO'), 'CENTRO COSTOS', utf8_decode('IDENTIFICACIÓN'),'EMPLEADO','DESDE', 'HASTA', 'VR. SALARIO','VR. ARP','VR. EPS',  utf8_decode('VR. PENSIÓN'),'VR NETO','VR TOTAL COBRAR');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 6);

        //creamos la cabecera de la tabla.
        $w = array(12, 55, 20, 55, 15, 15,19,19,19,19,19,22);
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
        $arReporteServiciosPorCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
        $arReporteServiciosPorCobrar = $query->getResult();     
        $douTotalNeto = 0;
        $douTotalCobrar = 0;
        $pdf->SetX(5);
        $pdf->SetFont('Arial', '', 6.5);
        foreach ($arReporteServiciosPorCobrar as $arReporteServiciosPorCobrar) {
            $pdf->SetX(5);
            $pdf->Cell(12, 4, $arReporteServiciosPorCobrar->getCodigoServicioCobrarPk(), 1, 0, 'L');
            $pdf->Cell(55, 4, utf8_decode($arReporteServiciosPorCobrar->getCentroCostoRel()->getNombre()), 1, 0, 'L');            
            $pdf->Cell(20, 4, $arReporteServiciosPorCobrar->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(55, 4, utf8_decode($arReporteServiciosPorCobrar->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(15, 4, $arReporteServiciosPorCobrar->getFechaDesde()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(15, 4, $arReporteServiciosPorCobrar->getFechaHasta()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(19, 4, number_format($arReporteServiciosPorCobrar->getVrSalario(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(19, 4, number_format($arReporteServiciosPorCobrar->getVrArp(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(19, 4, number_format($arReporteServiciosPorCobrar->getVrEps(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(19, 4, number_format($arReporteServiciosPorCobrar->getVrPension(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(19, 4, number_format($arReporteServiciosPorCobrar->getVrNeto(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(22, 4, number_format($arReporteServiciosPorCobrar->getVrTotalCobrar(), 2, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
            $douTotalNeto += $arReporteServiciosPorCobrar->getVrNeto();
            $douTotalCobrar += $arReporteServiciosPorCobrar->getVrTotalCobrar();
        }
        $pdf->SetX(5);
        $pdf->SetFont('Arial', 'B', 6.5);
        $pdf->Cell(12, 4, "", 1, 0, 'L');
        $pdf->Cell(55, 4, "", 1, 0, 'L');            
        $pdf->Cell(20, 4, "", 1, 0, 'L');            
        $pdf->Cell(55, 4, "", 1, 0, 'R');
        $pdf->Cell(15, 4, "", 1, 0, 'R');
        $pdf->Cell(15, 4, "", 1, 0, 'R');
        $pdf->Cell(19, 4, "", 1, 0, 'R');
        $pdf->Cell(19, 4, "", 1, 0, 'R');
        $pdf->Cell(19, 4, "", 1, 0, 'R');
        $pdf->Cell(19, 4, "", 1, 0, 'R');
        $pdf->Cell(19, 4, number_format($douTotalNeto, 2, '.', ','), 1, 0, 'R');
        $pdf->Cell(22, 4, number_format($douTotalCobrar, 2, '.', ','), 1, 0, 'R');
        $pdf->Ln();        
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);  
        $this->Text(250, 200, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
