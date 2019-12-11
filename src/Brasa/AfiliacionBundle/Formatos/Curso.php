<?php
namespace Brasa\AfiliacionBundle\Formatos;
class Curso extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoCurso;
    
    public function Generar($miThis, $codigoCurso) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoCurso = $codigoCurso;
        $pdf = new Curso();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Curso$codigoCurso.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("CURSO"), 0, 0, 'C', 1);
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
        
        $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
        $arCurso = self::$em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find(self::$codigoCurso);        
        
        $arCursoDetalles = new \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle();
        $arCursoDetalles = self::$em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->findBy(array('codigoCursoFk' => self::$codigoCurso));
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        
        $intY = 40;
        //linea 1
        $this->SetFillColor(272, 272, 272); 
        $this->SetXY(10, $intY);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("NUMERO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, $arCurso->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("CODIGO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(52, 5, $arCurso->getCodigoCursoPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, utf8_decode("FECHA") , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, $arCurso->getFecha()->format('Y-m-d'), 1, 0, 'R', 1);
        //linea 2
        $this->SetXY(10, $intY+5);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("CLIENTE:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(52, 5, $arCurso->getClienteRel()->getNombreCorto(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("NIT:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, $arCurso->getClienteRel()->getNit(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, '', 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, '', 1, 0, 'R', 1);
        //linea 2
        $this->SetXY(10, $intY+10);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("EMPLEADO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(52, 5, $arCurso->getNombreCorto(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("CEDULA:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, $arCurso->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, '', 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, '', 1, 0, 'R', 1);        
        //linea 3
        $this->SetXY(10, $intY+15);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("LUGAR:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(52, 5, $arCurso->getEntidadEntrenamientoRel()->getNombreCorto(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, utf8_decode("FECHA:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(52, 5,  $arCurso->getFechaProgramacion()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, '' , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, '', 1, 0, 'R', 1);
        //linea 4
        $this->SetXY(10, $intY+20);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("DIRECCION") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, $arCurso->getEntidadEntrenamientoRel()->getDireccion(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(21, 5, '', 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, '', 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(23, 5, utf8_decode("TOTAL PAGO:") , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(20, 5, number_format($arCurso->getTotal(), 2, '.', ','), 1, 0, 'R', 1);
        //linea 8
        $this->SetXY(10, $intY+25);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("COMENTARIOS:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(168, 5, "", 1, 0, 'L', 1);
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('COD', 'CURSO', 'PRECIO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7.5);

        //creamos la cabecera de la tabla.
        $w = array(11, 167, 15);
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
        $arCursoDetalles = new \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle();
        $arCursoDetalles = self::$em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->findBy(array('codigoCursoFk' => self::$codigoCurso));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arCursoDetalles as $arCursoDetalle) {            
            $pdf->Cell(11, 4, $arCursoDetalle->getCodigoCursoDetallePk(), 1, 0, 'L');
            $pdf->Cell(167, 4, $arCursoDetalle->getCursoTipoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(15, 4, number_format($arCursoDetalle->getPrecio(), 2, '.', ','), 1, 0, 'R');                                             
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
