<?php

namespace Brasa\AfiliacionBundle\Formatos;

class ConsultaContratoIngreso extends \FPDF_FPDF {

    public static $em;
    public static $arIngresos;

    public function Generar($miThis, $arIngresos) {
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$arIngresos = $arIngresos;
        $pdf = new ConsultaContratoIngreso();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("ConsultaContratoIngreso.pdf", 'D');
    }

    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("CONSULTA CONTRATOS INGRESOS "), 0, 0, 'C', 1);
        $this->SetXY(53, 18);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa() . " - " . $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);



        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('COD', 'CLIENTE', 'IDENTIFICACION', 'NOMBRE', 'F.INGRESO', 'RECIBO', 'F. PAGO', 'RECIBO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7.5);

        //creamos la cabecera de la tabla.
        $w = array(10, 50, 22, 50, 15, 15, 15, 15);
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
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $arIngresos = new \Brasa\AfiliacionBundle\Entity\AfiContrato();
        $query = self::$arIngresos;
        //$arIngresos = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
        $arIngresos = $query->getResult();
        foreach ($arIngresos as $arIngreso) {
            $pdf->Cell(10, 4, $arIngreso->getCodigoContratoPk(), 1, 0, 'L');
            $pdf->Cell(50, 4, substr($arIngreso->getClienteRel()->getNombreCorto(), 0,30), 1, 0, 'L');
            $pdf->Cell(22, 4, $arIngreso->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(50, 4, substr($arIngreso->getEmpleadoRel()->getNombreCorto(),0,30), 1, 0, 'L');
            $pdf->Cell(15, 4, $arIngreso->getFechaDesde()->format('d/m/Y'), 1, 0, 'L');
            $pdf->Cell(15, 4, $arIngreso->getNumeroRecibo(), 1, 0, 'L');
            $pdf->Cell(15, 4, $arIngreso->getFormaPago(), 1, 0, 'L');
            $pdf->Cell(15, 4, number_format($arIngreso->getValor(), 2, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
        $this->SetFont('Arial', 'B', 9);

        /* /*$this->Text(10, 240, "FIRMA: _____________________________________________");
          $this->SetFont('Arial','', 8);
          $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}'); */
    }

}

?>
