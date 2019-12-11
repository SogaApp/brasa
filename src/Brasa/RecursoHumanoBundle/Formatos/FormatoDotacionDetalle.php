<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoDotacionDetalle extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoDotacion;
    
    public function Generar($miThis, $codigoDotacion) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoDotacion = $codigoDotacion;
        $pdf = new FormatoDotacionDetalle();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Dotacion.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
        $arDotacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->find(self::$codigoDotacion);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(12);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 14);
        $this->Image('imagenes/logos/logo.jpg', 12, 15, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("DOTACIÓN EMPLEADO"), 0, 0, 'C', 1);
        $this->SetXY(53, 22);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 30);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 34);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0); 
        //FORMATO ISO
        $this->SetXY(168, 22);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(35, 5, "CODIGO: ".$arContenidoFormatoA->getCodigoFormatoIso(), 1, 0, 'L', 1);
        $this->SetXY(168, 27);
        $this->Cell(35, 5, utf8_decode("VERSIÓN: ".$arContenidoFormatoA->getVersion()), 1, 0, 'L', 1);
        $this->SetXY(168, 32);
        $this->Cell(35, 5, utf8_decode("FECHA: ".$arContenidoFormatoA->getFechaVersion()->format('Y-m-d')), 1, 0, 'L', 1);
        //FILA 1
        $this->SetXY(10, 40);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(23, 6, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(22, 6, $arDotacion->getCodigoDotacionPk() , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(18, 6, utf8_decode("FECHA:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(50, 6, $arDotacion->getFecha()->Format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',6);
        $this->Cell(30, 6, utf8_decode("N° INTERNO REFERENCIA:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(50, 6, $arDotacion->getCodigoInternoReferencia() , 1, 0, 'L', 1);
        
        //FILA 2
        $this->SetXY(10, 46);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(236, 236, 236);
        $this->Cell(23, 6, utf8_decode("IDENTIFICACIÓN:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(22, 6, $arDotacion->getEmpleadoRel()->getNumeroIdentificacion() , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(18, 6, utf8_decode("EMPLEADO:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',6.5);
        $this->Cell(50, 6, utf8_decode($arDotacion->getEmpleadoRel()->getNombreCorto()) , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(30, 6, utf8_decode("CENTRO COSTOS:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',6);
        $centroCosto = "";
        if ($arDotacion->getCodigoCentroCostoFk() != 0){
            $centroCosto = $arDotacion->getCentroCostoRel()->getNombre();
        }
        $this->Cell(50, 6, utf8_decode($centroCosto) , 1, 0, 'L', 1);
        //FILA 3
        $this->SetXY(10, 51);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(23, 6, "COMENTARIOS:" , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',6);
        $this->Cell(90, 6, utf8_decode($arDotacion->getComentarios()) , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(30, 6, utf8_decode("TIPO DOTACIÓN:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',7);
        $this->Cell(50, 6, utf8_decode($arDotacion->getDotacionTipoRel()->getNombre()) , 1, 0, 'L', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(10);
        $header = array(utf8_decode('CÓDIGO'), utf8_decode('ELEMENTO DOTACIÓN'), 'CANTIDAD ASIGNADA', 'CANTIDAD DEVUELTA', 'SERIE', 'LOTE');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(15, 58, 30, 30, 30, 30);
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
        $arDotacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle();
        $arDotacionDetalle = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionDetalle')->findBy(array('codigoDotacionFk' => self::$codigoDotacion));
        foreach ($arDotacionDetalle as $arDotacionDetalle) {            
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(15, 4, $arDotacionDetalle->getCodigoDotacionDetallePk(), 1, 0, 'L');
            $pdf->Cell(58, 4, $arDotacionDetalle->getDotacionElementoRel()->getDotacion(), 1, 0, 'L');
            $pdf->Cell(30, 4, $arDotacionDetalle->getCantidadAsignada(), 1, 0, 'R');
            $pdf->Cell(30, 4, $arDotacionDetalle->getCantidadDevuelta(), 1, 0, 'R');
            $pdf->Cell(30, 4, $arDotacionDetalle->getSerie(), 1, 0, 'R');
            $pdf->Cell(30, 4, $arDotacionDetalle->getLote(), 1, 0, 'R');            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
             
    }

    public function Footer() {
        
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
        $arDotacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->find(self::$codigoDotacion);
        $this->SetFont('Arial', 'B', 9);
        
        $this->Text(10, 240, "FIRMA: _____________________________________________");
        $this->Text(10, 247, $arDotacion->getEmpleadoRel()->getNombreCorto());
        $this->Text(10, 254, "C.C.:     ______________________ de ____________________");
        $this->Text(105, 240, "FIRMA: _____________________________________________");
        $this->Text(105, 247, $arConfiguracion->getNombreEmpresa());
        $this->Text(105, 254, "NIT: ". $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa());
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
