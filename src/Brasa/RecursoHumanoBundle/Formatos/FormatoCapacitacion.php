<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoCapacitacion extends \FPDF_FPDF {
    public static $em;

    public static $codigoCapacitacion;

    public function Generar($miThis, $codigoCapacitacion) {
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoCapacitacion = $codigoCapacitacion;
        $pdf = new FormatoCapacitacion('L','mm','letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $this->Body($pdf);

        $pdf->Output("ActaCapacitacion.pdf", 'D');

    }

    public function Header() {
        $arCapacitacion = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion();
        $arCapacitacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find(self::$codigoCapacitacion);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(16);
        $this->SetFillColor(200, 200, 200);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10);
        $this->SetXY(10, 10);
        $this->Line(10, 10, 60, 10);
        $this->Line(10, 10, 10, 50);
        $this->Line(10, 50, 60, 50);
        $this->Cell(0, 0, $this->Image('imagenes/logos/logo.jpg' , 15 ,20, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo
        $this->SetXY(60, 10);
        $this->Cell(160, 10, utf8_decode(""), 1, 0, 'C', 1); //cuardo mitad arriba
        $this->SetXY(60, 20);
        $this->SetFillColor(236, 236, 236);
        $this->Cell(160, 20, utf8_decode($arContenidoFormatoA->getTitulo()), 1, 0, 'C', 1); //cuardo mitad medio
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(60, 40);
        $this->Cell(160, 10, utf8_decode(" "), 1, 0, 'C', 1); //cuardo mitad abajo
        $this->SetXY(220, 10);
        $this->Cell(53, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuadro derecho arriba
        $this->SetXY(220, 20);
        $this->Cell(53, 20, utf8_decode($arContenidoFormatoA->getCodigoFormatoIso()), 1, 0, 'C', 1); //cuadro derecho mitad 1
        $this->SetXY(220, 40);
        $this->Cell(53, 5, utf8_decode($arContenidoFormatoA->getVersion()), 1, 0, 'C', 1); //cuadro derecho abajo 1
        $this->SetXY(220, 45);
        $this->Cell(53, 5, $arContenidoFormatoA->getFechaVersion()->format('Y-m-d'), 1, 0, 'C', 1); //cuadro derecho abajo 2
        
        
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $arCapacitacion = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion();
        $arCapacitacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find(self::$codigoCapacitacion);
        //informacion capacitacion
        //linea 1
        $ciudad = "";
        if ($arCapacitacion->getCodigoCiudadFk() != null){
            $ciudad = $arCapacitacion->getCiudadRel()->getNombre();
        }
        $this->SetXY(10, 55);
        $this->SetFont('Arial','B',8);
        $this->Cell(15, 6, "FECHA:", 0, 0, '', 1);
        $this->Cell(20, 6, $arCapacitacion->getFechaCapacitacion()->format('Y-m-d'), 'B', 0, 'C', 1);
        $this->Cell(12, 6, "HORA:", 0, 0, '', 1);
        $this->Cell(20, 6, $arCapacitacion->getFechaCapacitacion()->format('H:i:s'), 'B', 0, 'C', 1);
        $this->Cell(21, 6, "DURACION:", 0, 0, 'B', 1);
        $this->Cell(20, 6, $arCapacitacion->getDuracion(), 'B', 0, 'C', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(15, 6, "CIUDAD:", 0, 0, '', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(60, 6, $ciudad, 'B', 0, 'C', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(15, 6, "LUGAR:", 0, 0, '', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(65, 6, utf8_decode($arCapacitacion->getLugar()), 'B', 0, 'C', 1);
        //linea 2
        $this->SetXY(10, 62);
        $this->SetFont('Arial','B',8);
        $this->Cell(15, 6, "TEMA:", 0, 0, '', 1);
        $this->Cell(248, 6, utf8_decode($arCapacitacion->getTema()), 'B', 0, 'L', 1);
        //linea 3
        $metodologia = "";
        if ($arCapacitacion->getCodigoCapacitacionMetodologiaFk() != null){
            $metodologia = $arCapacitacion->getCapacitacionMetodologiaRel()->getNombre();
        }
        $this->SetXY(10, 69);
        $this->SetFont('Arial','B',8);
        $this->Cell(25, 6, "METODOLOGIA:", 0, 0, '', 1);
        $this->Cell(238, 6, utf8_decode($metodologia), 'B', 0, 'L', 1);
        //linea 4
        $this->SetXY(10, 76);
        $this->Cell(18, 6, "OBJETIVO:", 0, 0, '', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(245, 6, utf8_decode($arCapacitacion->getObjetivo()), 'B', 0, 'L', 1);
        //linea 5
        $this->SetXY(10, 82);
        $this->SetFont('Arial','B',8);
        $this->Cell(20, 10, "CONTENIDO:", 0, 0, '', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(243, 10, utf8_decode($arCapacitacion->getContenido()), 'B', 0, '', 1);
        //linea 6
        $this->SetXY(10, 92);
        $this->SetFont('Arial','B',8);
        $this->Cell(22, 6, "FACILITADOR:", 0, 0, '', 1);
        $this->Cell(158, 6, $arCapacitacion->getFacilitador(), 'B', 0, '', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(28, 6, "IDENTIFICACION:", 0, 0, '', 1);
        $this->Cell(55, 6, $arCapacitacion->getNumeroIdentificacionFacilitador(), 'B', 0, '', 1);
        
        $this->Ln(10);
        $header = array(utf8_decode('N°'), 'NOMBRE', 'DOCUMENTO',  utf8_decode('CARGO'), 'PUESTO', 'CLIENTE', 'EV%', 'FIRMA');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(6, 55, 19, 40, 53, 53,9,30);
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
        $arCapacitacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
        $arCapacitacionDetalle = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->findBy(array('codigoCapacitacionFk' => self::$codigoCapacitacion));
        $nro = 0;
        foreach ($arCapacitacionDetalle as $arCapacitacionDetalle) {
            $nro ++;
            $asistencia = "NO";
            if ($arCapacitacionDetalle->getAsistencia() == 1){
                $asistencia = "SI";
            }
            $cargo = "";
            if ($arCapacitacionDetalle->getEmpleadoRel()->getCodigoCargoFk() != null){
                $cargo = $arCapacitacionDetalle->getEmpleadoRel()->getCargoRel()->getNombre();
            }
            $puesto = "";
            if ($arCapacitacionDetalle->getEmpleadoRel()->getCodigoPuestoFk() != null){
                $puesto = $arCapacitacionDetalle->getEmpleadoRel()->getPuestoRel()->getNombre();
            }
            $cliente = "";
            if ($arCapacitacionDetalle->getEmpleadoRel()->getCodigoPuestoFk() != null){
                $cliente = $arCapacitacionDetalle->getEmpleadoRel()->getPuestoRel()->getClienteRel()->getNombreCorto();
            }
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(6, 8, $nro, 1, 0, 'L');
            $pdf->Cell(55, 8, $arCapacitacionDetalle->getNombreCorto(), 1, 0, 'L');
            $pdf->Cell(19, 8, $arCapacitacionDetalle->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(40, 8, substr($cargo,0,28), 1, 0, 'L');
            $pdf->Cell(53, 8, substr($puesto,0,40), 1, 0, 'L');
            $pdf->Cell(53, 8, substr($cliente,0,40), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(9, 8, $arCapacitacionDetalle->getEvaluacion(), 1, 0, 'L');
            $pdf->Cell(30, 8, '', 1, 0, 'L');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }

    }

    public function Footer() {

        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');;
    }
}

?>
