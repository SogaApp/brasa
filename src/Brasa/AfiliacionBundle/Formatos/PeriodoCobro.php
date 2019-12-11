<?php
namespace Brasa\AfiliacionBundle\Formatos;

use Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle;

class PeriodoCobro extends \FPDF_FPDF
{
    public static $em;
    public static $codigoPeriodo;

    public function Generar($miThis, $codigoPeriodo, $strRuta = "")
    {
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoPeriodo = $codigoPeriodo;
        $pdf = new PeriodoCobro('L', 'mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        if ($strRuta == "") {
            $pdf->Output("PeriodoCobro$codigoPeriodo.pdf", 'D');
        } else {
            $pdf->Output($strRuta . "PeriodoCobro$codigoPeriodo.pdf", 'F');
        }
    }

    public function Header()
    {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        $this->Image('imagenes/logos/logo.jpg', 12, 13, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->SetXY(50, 10);
        $this->Cell(225, 7, utf8_decode("RELACION COBRO"), 0, 0, 'C', 1);
        $this->SetXY(50, 18);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa() . " - " . $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);
        //
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        $arPeriodo = self::$em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find(self::$codigoPeriodo);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, "NIT:", 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 6, $arPeriodo->getClienteRel()->getNit(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "CLIENTE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(100, 6, utf8_decode($arPeriodo->getClienteRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "DIRECCION:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(45, 6, utf8_decode($arPeriodo->getClienteRel()->getDireccion()), 1, 0, 'L', 1);
        //linea 2
        $this->SetXY(10, 45);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, "TELEFONO:", 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 6, $arPeriodo->getClienteRel()->getTelefono(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "CORREO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(100, 6, utf8_decode($arPeriodo->getClienteRel()->getEmail()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "FORMA PAGO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(45, 6, utf8_decode($arPeriodo->getClienteRel()->getFormaPagoRel()->getNombre()), 1, 0, 'L', 1);
        //linea 3
        $this->SetXY(10, 50);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, 'PLAZO', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, utf8_decode($arPeriodo->getClienteRel()->getPlazoPago()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "FINANCIERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(100, 5, number_format($arPeriodo->getInteresMora(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "SOPORTE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(45, 5, "", 1, 0, 'L', 1);
        //linea 3
        $this->SetXY(10, 55);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, 'FECHA EMISION', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, date('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "PERIODO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(100, 5, $arPeriodo->getFechaDesde()->format('Y-m-d') . ' - ' . $arPeriodo->getFechaHasta()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(45, 5, "", 1, 0, 'L', 1);

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(10);
        $header = array('TIPO', utf8_decode('IDENTIFICACION'), 'NOMBRE', 'DIAS', 'SALARIO', 'FECHA ING', 'PENSION', 'SALUD', 'RIESGOS', 'CAJA', 'SUBTOTAL', 'ADMON', 'MOV. F', 'TOTAL', 'RET');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 6);

        //creamos la cabecera de la tabla.
        $w = array(6, 20, 50, 8, 15, 15, 28, 30, 20, 15, 15, 11, 10, 15, 7);
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

    public function Body($pdf)
    {
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        $arPeriodo = self::$em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find(self::$codigoPeriodo);
        $arPeriodoDetalles = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle();
        $arPeriodoDetalles = self::$em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetalle')->findBy(array('codigoPeriodoFk' => self::$codigoPeriodo));

        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $totalGeneral = $arPeriodo->getTotal();
        $var2 = count($arPeriodoDetalles);
        $subtotal = 0;
        $totalAdministracion = 0;
        $totalIva = 0;
        /** @var  $arPeriodoDetalle AfiPeriodoDetalle */
        foreach ($arPeriodoDetalles as $arPeriodoDetalle) {
            $pdf->Cell(6, 4, utf8_decode($arPeriodoDetalle->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface()), 1, 0, 'L');
            $pdf->Cell(20, 4, $arPeriodoDetalle->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(50, 4, utf8_decode($arPeriodoDetalle->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(8, 4, $arPeriodoDetalle->getDias(), 1, 0, 'L');
            $pdf->Cell(15, 4, number_format($arPeriodoDetalle->getSalario(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(15, 4, $arPeriodoDetalle->getContratoRel()->getFechaDesde()->format('Y-m-d'), 1, 0, 'L');
            if ($arPeriodoDetalle->getContratoRel()->getGeneraPension() == 1) {
                $pdf->Cell(28, 4, utf8_decode(substr($arPeriodoDetalle->getContratoRel()->getEntidadPensionRel()->getNombre(), 0, 18)), 1, 0, 'L');
            } else {
                $pdf->Cell(28, 4, "SIN PENSION", 1, 0, 'L');
            }
            if ($arPeriodoDetalle->getContratoRel()->getGeneraSalud() == 1) {
                $pdf->Cell(30, 4, utf8_decode(substr($arPeriodoDetalle->getContratoRel()->getEntidadSaludRel()->getNombre(), 0, 22)), 1, 0, 'L');
            } else {
                $pdf->Cell(30, 4, "SIN SALUD", 1, 0, 'L');
            }
            if ($arPeriodoDetalle->getContratoRel()->getGeneraRiesgos() == 1) {
                $pdf->Cell(20, 4, utf8_decode($arPeriodoDetalle->getContratoRel()->getClasificacionRiesgoRel()->getNombre()), 1, 0, 'L');
            } else {
                $pdf->Cell(20, 4, "SIN RIESGO", 1, 0, 'L');
            }
            if ($arPeriodoDetalle->getContratoRel()->getGeneraCaja() == 1) {
                $pdf->Cell(15, 4, utf8_decode(substr($arPeriodoDetalle->getContratoRel()->getEntidadCajaRel()->getNombre(), 0, 10)), 1, 0, 'L');
            } else {
                $pdf->Cell(15, 4, "SIN CAJA", 1, 0, 'L');
            }
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(15, 4, number_format($arPeriodoDetalle->getSubtotal(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(11, 4, number_format($arPeriodoDetalle->getAdministracion(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(10, 4, number_format($arPeriodoDetalle->getIva(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($arPeriodoDetalle->getTotal(), 0, '.', ','), 1, 0, 'R');
            if ($arPeriodoDetalle->getRetiro() == 1) {
                $retiro = 'SI';
            } else {
                $retiro = 'NO';
            }
            $pdf->Cell(7, 4, $retiro, 1, 0, 'L');
            $totalAdministracion = $totalAdministracion + $arPeriodoDetalle->getAdministracion();
            $subtotal = $subtotal + $arPeriodoDetalle->getSubtotal();
            $totalIva = $totalIva + $arPeriodoDetalle->getIva();
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);

        }
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(207, 5);
        $pdf->Cell(15, 5, number_format($subtotal, 0, '.', ','), 1, 0, 'R');
        $pdf->Cell(11, 5, number_format($totalAdministracion, 0, '.', ','), 1, 0, 'R');
        $pdf->Cell(10, 5, number_format($totalIva, 0, '.', ','), 1, 0, 'R');
        $pdf->Cell(15, 5, number_format($totalGeneral, 0, '.', ','), 1, 0, 'R');
        $pdf->Cell(7, 5, "", 0, 0, 'R');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(35, 5, "NUMERO DE EMPLEADOS: ", 0, 0, 'R');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(19, 5, number_format($var2, 0, '.', ','), 1, 0, 'R');

    }

    public function Footer()
    {
        $arConfiguracion = new \Brasa\AfiliacionBundle\Entity\AfiConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaAfiliacionBundle:AfiConfiguracion')->find(1);
        $this->SetY(120);
        $this->Ln(3);
        $this->SetFont('Arial', 'B', 9);
        //$this->Text(10, $this->GetY($this->SetY(160)), utf8_decode($arConfiguracion->getInformacionPagoFactura()));
        $this->SetY(197);
        $this->MultiCell(261, 5, $arConfiguracion->getInformacionPagoFactura(), 0);

        $this->SetFont('Arial', 'B', 8);
        //$this->Text(60, $this->GetY($this->SetY(205)), utf8_decode($arConfiguracion->getInformacionContactoFactura()));
        $this->SetFont('Arial', '', 8);
        //Número de página
        $this->Text(257, 207, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }
}

?>
