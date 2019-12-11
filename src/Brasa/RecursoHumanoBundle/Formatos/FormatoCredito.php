<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoCredito extends \FPDF_FPDF {
    public static $em;
    
    public static $strDql;
   
    public function Generar($miThis, $dql) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$strDql = $dql;
        $pdf = new FormatoCredito('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 11);
        $this->Body($pdf);
        $pdf->Output("Lista_Creditos.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findAll();
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',12);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 15);
        $this->Cell(279, 8, "LISTADO DE CREDITOS " , 1, 0, 'C', 1);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('CODIGO', 'TIPO', 'FECHA', 'EMPLEADO', 'VR. CREDITO', 'VR. CUOTA', 'VR. SEGURO', 'CUOTAS', 'C. ACTUAL', 'PAGADO', 'APROBADO', 'SUSPENDIDO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(14, 45, 17, 65, 19, 18, 18, 15, 16, 16,18,18);
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
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $query->getResult();
        //$arCreditos = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findAll();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arCreditos as $arCredito) {
            if ($arCredito->getCodigoCreditoTipoFk() == null){
                $strCreditoTipo = "";
            }else{
                $strCreditoTipo = $arCredito->getCreditoTipoRel()->getNombre();
            }
            $pdf->Cell(14, 4, $arCredito->getCodigoCreditoPk(), 1, 0, 'L');
            $pdf->Cell(45, 4, utf8_decode($strCreditoTipo), 1, 0, 'L');
            $pdf->Cell(17, 4, $arCredito->getFecha()->format('Y/m/d'), 1, 0, 'C');
            $pdf->Cell(65, 4, utf8_decode($arCredito->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(19, 4, number_format($arCredito->getVrPagar(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(18, 4, number_format($arCredito->getVrCuota(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(18, 4, number_format($arCredito->getSeguro(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(15, 4, $arCredito->getNumeroCuotas(), 1, 0, 'R');
            $pdf->Cell(16, 4, $arCredito->getNumeroCuotaActual(), 1, 0, 'R');
            if ($arCredito->getEstadoPagado() == 1)
            {    
                $pdf->Cell(16, 4, "SI", 1, 0, 'L');
            }
            else
            {
                $pdf->Cell(16, 4, "NO", 1, 0, 'L');
            }
            if ($arCredito->getAprobado() == 1)
            {    
                $pdf->Cell(18, 4, "SI", 1, 0, 'L');
            }
            else
            {
                $pdf->Cell(18, 4, "NO", 1, 0, 'L');
            }
            if ($arCredito->getEstadoSuspendido() == 1)
            {    
                $pdf->Cell(18, 4, "SI", 1, 0, 'L');
            }
            else
            {
                $pdf->Cell(18, 4, "NO", 1, 0, 'L');
            }
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }        
    }

    public function Footer() {
        $this->SetXY(235, 190);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 35, utf8_decode('   Página ') . $this->PageNo() . ' de {nb}' , 0, 0, 'L', 0);          
    }    
}


