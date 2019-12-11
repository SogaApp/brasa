<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoRequisitos extends \FPDF_FPDF {
    public static $em;

    public static $codigoRequisito;

    public function Generar($miThis, $codigoRequisito) {
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoRequisito = $codigoRequisito;
        $pdf = new FormatoRequisitos();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Requisito.pdf", 'D');

    }

    public function Header() {
        $arRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisito();
        $arRequisito = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuRequisito')->find(self::$codigoRequisito);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(4);
        $this->SetFillColor(200, 200, 200);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10);
        $this->SetXY(10, 10);
        $this->Line(10, 10, 60, 10);
        $this->Line(10, 10, 10, 50);
        $this->Line(10, 50, 60, 50);
        $this->Cell(0, 0, $this->Image('imagenes/logos/logo.jpg' , 15 ,20, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo
        $this->SetXY(60, 10);
        $this->Cell(90, 10, utf8_decode(""), 1, 0, 'C', 1); //cuardo mitad arriba
        $this->SetXY(60, 20);
        $this->SetFillColor(236, 236, 236);
        $this->Cell(90, 20, utf8_decode("LISTA DE REQUISITOS DE INGRESO"), 1, 0, 'C', 1); //cuardo mitad medio
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(60, 40);
        $this->Cell(90, 10, utf8_decode(" "), 1, 0, 'C', 1); //cuardo mitad abajo
        $this->SetXY(150, 10);
        $this->Cell(53, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuadro derecho arriba
        $this->SetXY(150, 20);
        $this->Cell(53, 20, utf8_decode($arContenidoFormatoA->getCodigoFormatoIso()), 1, 0, 'C', 1); //cuadro derecho mitad 1
        $this->SetXY(150, 40);
        $this->Cell(53, 5, utf8_decode($arContenidoFormatoA->getVersion()), 1, 0, 'C', 1); //cuadro derecho abajo 1
        $this->SetXY(150, 45);
        $this->Cell(53, 5, $arContenidoFormatoA->getFechaVersion()->format('Y-m-d'), 1, 0, 'C', 1); //cuadro derecho abajo 2
        
        //fecha de impresión
        $this->SetXY(10, 60);
        $this->SetFont('Arial','',10);
        $fechaImpresion = date('Y-m-d');
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $fechaImpresion = strftime("%d de %B de %Y", strtotime($fechaImpresion));
        $this->Cell(53, 5, utf8_decode('Medellín, '.$fechaImpresion.''), 0, 0, 'L', 1); //cuadro derecho abajo 2
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(10);
        $header = array(utf8_decode('CÓDIGO'), utf8_decode('CONCEPTO'), 'TIPO', 'ENTREGADO', 'APLICA', 'ESTADO');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(15, 100, 24, 21, 12, 21);
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
        $pdf->SetFont('Arial', '', 8);
        $arRequisitoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();
        $arRequisitoDetalle = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoDetalle')->findBy(array('codigoRequisitoFk' => self::$codigoRequisito));
        foreach ($arRequisitoDetalle as $arRequisitoDetalle) {
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(15, 4, $arRequisitoDetalle->getCodigoRequisitoDetallePk(), 1, 0, 'L');
            $pdf->Cell(100, 4, utf8_decode($arRequisitoDetalle->getRequisitoConceptoRel()->getNombre()), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 7.5);
            $pdf->Cell(24, 4, $arRequisitoDetalle->getTipo(), 1, 0, 'L');
            if($arRequisitoDetalle->getEstadoEntregado() == 1) {
                $pdf->Cell(21, 4, 'SI', 1, 0, 'L');
            } else {
                $pdf->Cell(21, 4, 'NO', 1, 0, 'L');
            }
            if($arRequisitoDetalle->getEstadoNoAplica() == 1) {
                $pdf->Cell(12, 4, 'NO', 1, 0, 'L');
            } else {
                $pdf->Cell(12, 4, 'SI', 1, 0, 'L');
            }
            if($arRequisitoDetalle->getEstadoPendiente() == 1) {
                $pdf->Cell(21, 4, 'PENDIENTE', 1, 0, 'L');
            } else {
                $pdf->Cell(21, 4, 'CERRADO', 1, 0, 'L');
            }

            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
        $pdf->Ln(12);
        $pdf->SetFont('Arial', '', 10);
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find(12);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        
        //se reemplaza el contenido de la tabla tipo de proceso disciplinario
        $sustitucion1 = $arRequisitoDetalle->getRequisitoRel()->getNombreCorto();
        $sustitucion2 = $arRequisitoDetalle->getRequisitoRel()->getNumeroIdentificacion();
        $sustitucion3 = $arConfiguracion->getNombreEmpresa();

        $cadena = $arContenidoFormato->getContenido();
        $patron1 = '/#1/';
        $patron2 = '/#2/';
        $patron3 = '/#3/';
        $cadenaCambiada = preg_replace($patron1, $sustitucion1, $cadena);
        $cadenaCambiada = preg_replace($patron2, $sustitucion2, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron3, $sustitucion3, $cadenaCambiada);
        $pdf->MultiCell(0,5, $cadenaCambiada);

    }

    public function Footer() {

        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisito();
        $arRequisito = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuRequisito')->find(self::$codigoRequisito);
        $this->SetFont('Arial', 'B', 9);

        $this->Text(10, 240, "FIRMA: _____________________________________________");
        $this->Text(10, 247, $arRequisito->getNombreCorto());
        $this->Text(10, 254, "C.C.:     ______________________ de ____________________");
        $this->Text(105, 240, "FIRMA: _____________________________________________");
        $this->Text(105, 247, $arConfiguracion->getNombreEmpresa());
        $this->Text(105, 254, "NIT: ". $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa());
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

?>
