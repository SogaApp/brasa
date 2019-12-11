<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoListaPagos extends \FPDF_FPDF {
    public static $em;
    
    public static $strDql;
   
    public function Generar($miThis, $dql) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$strDql = $dql;
        $pdf = new FormatoListaPagos('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 11);
        
        $this->Body($pdf);
        $pdf->Output("Lista_pagos.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findAll();
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',12);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 15);
        $this->Cell(283, 8, "LISTADO DE PAGOS " , 1, 0, 'C', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('COD', 'PERIODO','IDENTIFICACION', 'EMPLEADO', 'CENTRO COSTO', 'SALARIO', 'DEVENGADO', 'DEDUCCIONES', 'NETO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 30, 22, 58, 85, 19, 19, 21,19);
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
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        //$arCreditos = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findAll();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arPagos as $arPago) {            
            $pdf->Cell(10, 4, $arPago->getCodigoPagoPk(), 1, 0, 'L');
            $pdf->Cell(30, 4, $arPago->getFechaDesde()->format('Y/m/d')."_".$arPago->getFechaHasta()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(22, 4, $arPago->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(58, 4, $arPago->getEmpleadoRel()->getNombreCorto(), 1, 0, 'L');
            $pdf->Cell(85, 4, $arPago->getCentroCostoRel()->getNombre(), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(19, 4, number_format($arPago->getVrSalario(),2,'.',','), 1, 0, 'R');
            $pdf->Cell(19, 4, number_format($arPago->getVrDevengado(),2,'.',','), 1, 0, 'R');
            $pdf->Cell(21, 4, number_format($arPago->getVrDeducciones(),2,'.',','), 1, 0, 'R');
            $pdf->Cell(19, 4, number_format($arPago->getVrNeto(),2,'.',','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }        
    }

    public function Footer() {
        $this->SetXY(245, 185);
        $this->Cell(30, 35, utf8_decode('Página ') . $this->PageNo() . ' de {nb}' , 0, 0, 'L', 0);          
    }    
}


