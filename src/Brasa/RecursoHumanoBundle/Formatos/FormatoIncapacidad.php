<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoIncapacidad extends \FPDF_FPDF {
    public static $em;
    
    public static $strDql;
   
    public function Generar($miThis, $dql) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$strDql = $dql;
        $pdf = new FormatoIncapacidad('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 11);
        $this->Body($pdf);
        $pdf->Output("Lista_incapacidades.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',12);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 15);
        $this->Cell(275, 8, "LISTADO DE INCAPACIDADES " , 1, 0, 'C', 1);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('CODIGO', 'IDENTIFICACION', 'EMPLEADO','CENTRO COSTOS', 'DESDE', 'HASTA', 'HORAS', 'COBRAR','PRORROGA','TRANSCRIPCION');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(12, 22, 60, 90, 15, 15, 11,12,16,22);
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
        //$arCreditos = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findAll();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arIncapacidades as $arIncapacidad) {            
            $pdf->Cell(12, 4, $arIncapacidad->getCodigoIncapacidadPk(), 1, 0, 'L');
            $pdf->Cell(22, 4, $arIncapacidad->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(60, 4, utf8_decode($arIncapacidad->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(90, 4, utf8_decode($arIncapacidad->getCentroCostoRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(15, 4, $arIncapacidad->getFechaDesde()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(15, 4, $arIncapacidad->getFechaHasta()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(11, 4, $arIncapacidad->getCantidad(), 1, 0, 'L');
            if ($arIncapacidad->getEstadoCobrar() == 1)
            {    
                $pdf->Cell(12, 4, "SI", 1, 0, 'L');
            }
            else
            {
                $pdf->Cell(12, 4, "NO", 1, 0, 'L');
            }
            if ($arIncapacidad->getEstadoProrroga() == 1)
            {    
                $pdf->Cell(16, 4, "SI", 1, 0, 'L');
            }
            else
            {
                $pdf->Cell(16, 4, "NO", 1, 0, 'L');
            }
            if ($arIncapacidad->getEstadoTranscripcion() == 1)
            {    
                $pdf->Cell(16, 4, "SI", 1, 0, 'L');
            }
            else
            {
                $pdf->Cell(22, 4, "NO", 1, 0, 'L');
            }
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }        
    }

    public function Footer() {
        $this->SetXY(235, 185);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 35, utf8_decode('   Página ') . $this->PageNo() . ' de {nb}' , 0, 0, 'L', 0);          
    }    
}


