<?php
namespace Brasa\RecursoHumanoBundle\Reportes;
class ReporteIncapacidades extends \FPDF_FPDF {
    public static $em;
    
    public static $strDql;    
    
    public function Generar($miThis, $dql) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$strDql = $dql;
        $pdf = new ReporteIncapacidades('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("ReporteIncapacidades.pdf", 'D');        
    } 
    
    public function Header() {
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arPagos = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findAll();
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',12);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 15);
        $this->Cell(283, 8, "LISTADO INCAPACIDADES" , 1, 0, 'C', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array(utf8_decode('CÓDIGO'), 'EPS', utf8_decode('IDENTIFICACIÓN'), 'EMPLEADO', 'CENTRO COSTO', 'DESDE', 'HASTA',  utf8_decode('DÍAS'),'VR. INCAPACIDAD','VR. PAGADO','VR. SALDO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(12, 46, 25, 45, 46, 15, 15, 10,25,22,22);
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
        $query = self::$em->createQuery(self::$strDql);
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $query->getResult();
        
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arIncapacidades as $arIncapacidad) {            
            $pdf->Cell(12, 4, $arIncapacidad->getCodigoIncapacidadPk(), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(46, 4, utf8_decode($arIncapacidad->getEntidadSaludRel()->getNombre()), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(25, 4, $arIncapacidad->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'R');
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(45, 4, utf8_decode($arIncapacidad->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(46, 4, utf8_decode($arIncapacidad->getCentroCostoRel()->getNombre()), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(15, 4, $arIncapacidad->getFechaDesde()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(15, 4, $arIncapacidad->getFechaHasta()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(10, 4, $arIncapacidad->getCantidad(), 1, 0, 'R');
            $pdf->Cell(25, 4, number_format($arIncapacidad->getVrIncapacidad(),0,'.',','), 1, 0, 'R');
            $pdf->Cell(22, 4, number_format($arIncapacidad->getVrPagado(),0,'.',','), 1, 0, 'R');
            $pdf->Cell(22, 4, number_format($arIncapacidad->getVrSaldo(),0,'.',','), 1, 0, 'R');
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
