<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoExamen extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoExamen;
    
    public function Generar($miThis, $codigoExamen) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoExamen = $codigoExamen;
        $pdf = new FormatoExamen();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Examen$codigoExamen.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(2);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("ORDEN DE EXAMEN MEDICO"), 0, 0, 'C', 1);
        $this->SetXY(53, 18);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);        
        //FORMATO ISO
        $this->SetXY(168, 18);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(35, 6, "CODIGO: ".$arContenidoFormatoA->getCodigoFormatoIso(), 1, 0, 'L', 1);
        $this->SetXY(168, 24);
        $this->Cell(35, 6, utf8_decode("VERSIÓN: ".$arContenidoFormatoA->getVersion()), 1, 0, 'L', 1);
        $this->SetXY(168, 30);
        $this->Cell(35, 6, utf8_decode("FECHA: ".$arContenidoFormatoA->getFechaVersion()->format('Y-m-d')), 1, 0, 'L', 1);
        //
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamen = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find(self::$codigoExamen);        
        
        $arExamenDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
        $arExamenDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => self::$codigoExamen));
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        
        $intY = 40;
        $this->SetFillColor(272, 272, 272); 
        $this->SetXY(10, $intY);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "DOCUMENTO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(30, 6, $arExamen->getIdentificacion(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "NOMBRE:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(100, 6, utf8_decode($arExamen->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetXY(10, $intY + 5);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(30, 6, $arExamen->getFecha()->format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "CENTRO COSTOS:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(100, 6, "" , 1, 0, 'L', 1);
        $this->SetXY(10, $intY + 10);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "CLASE" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(30, 6, $arExamen->getExamenClaseRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "ENTIDAD EXAMEN:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(100, 6, $arExamen->getEntidadExamenRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetXY(10, $intY + 15);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 5, utf8_decode("DIRECCIÓN:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(100, 5, $arExamen->getEntidadExamenRel()->getDireccion() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 5, utf8_decode("TELÉFONO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(30, 5, $arExamen->getEntidadExamenRel()->getTelefono() , 1, 0, 'L', 1);
        
        $this->SetXY(10, $intY + 20);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 5, utf8_decode("COMENTARIOS:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(160, 5, $arExamen->getComentarios() , 1, 0, 'L', 1);        
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('COD', 'TIPO', 'TIPO EXAMEN');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 10, 170);
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
        $arExamenDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
        $arExamenDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => self::$codigoExamen));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arExamenDetalles as $arExamenDetalle) {            
            $pdf->Cell(10, 4, $arExamenDetalle->getCodigoExamenDetallePk(), 1, 0, 'L');
            $pdf->Cell(10, 4, $arExamenDetalle->getExamenTipoRel()->getCodigoExamenTipoPk(), 1, 0, 'L');
            $pdf->Cell(170, 4, utf8_decode($arExamenDetalle->getExamenTipoRel()->getNombre()), 1, 0, 'L');                
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
        
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
