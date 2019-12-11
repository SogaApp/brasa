<?php
namespace Brasa\AfiliacionBundle\Formatos;

class PagoCurso extends \FPDF_FPDF {
    public static $em;
    public static $codigoPagoCurso;
    
    public function Generar($miThis, $codigoPagoCurso) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoPagoCurso = $codigoPagoCurso;
        $pdf = new PagoCurso();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("PagoCurso$codigoPagoCurso.pdf", 'D');                
    } 
    
    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        
        $this->Image('imagenes/logos/logo.jpg', 12, 13, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->SetXY(50, 10);
        $this->Cell(150, 7, utf8_decode("PAGO BANCO"), 0, 0, 'C', 1);
        $this->SetXY(50, 18);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);        
        //FORMATO ISO
        $this->SetXY(168, 18);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(35, 8, "FECHA: 01/09/2015", 1, 0, 'L', 1);
        $this->SetXY(168, 26);
        $this->Cell(35, 8, utf8_decode("VERSIÓN: 01"), 1, 0, 'L', 1);
        //
        $arPagoCurso = new \Brasa\AfiliacionBundle\Entity\AfiPagoCurso();
        $arPagoCurso = self::$em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->find(self::$codigoPagoCurso);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200); 
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, utf8_decode("NUMERO:") , 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272); 
        $this->SetFont('Arial','',8);
        $this->Cell(30, 6, $arPagoCurso->getCodigoPagoCursoPk(), 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "PROVEEDOR:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272); 
        $this->Cell(100, 6, $arPagoCurso->getProveedorRel()->getNombreCorto() , 1, 0, 'L', 1); 
        
        //linea 2
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("SOPORTE:") , 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272); 
        $this->SetFont('Arial','',8);
        $this->Cell(30, 6, '', 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "BANCO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272); 
        $this->Cell(100, 6, utf8_decode($arPagoCurso->getCuentaRel()->getBancoRel()->getNombre()), 1, 0, 'L', 1);         
        //linea 3
        $this->SetXY(10, 50);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("FECHA:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, $arPagoCurso->getFecha()->format('Y/m/d') , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "CUENTA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272); 
        $this->Cell(100, 6, utf8_decode($arPagoCurso->getCuentaRel()->getNombre()), 1, 0, 'L', 1);    
        //linea 4
        $this->SetXY(10, 55);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, '' , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "TOTAL:" , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272); 
        $this->Cell(100, 5, number_format($arPagoCurso->getTotal(), 0, '.', ',') , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array(utf8_decode('CÓDIGO'), utf8_decode('IDENTIFICACIÓN'), 'NOMBRE', 'CURSO', 'VR PAGO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(20, 25, 80, 50, 15);
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
        $arPagoCursoDetalles = new \Brasa\AfiliacionBundle\Entity\AfiPagoCursoDetalle();
        $arPagoCursoDetalles = self::$em->getRepository('BrasaAfiliacionBundle:AfiPagoCursoDetalle')->findBy(array('codigoPagoCursoFk' => self::$codigoPagoCurso));
        
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $var = 0;
        foreach ($arPagoCursoDetalles as $arPagoCursoDetalle) {            
            $pdf->Cell(20, 4, $arPagoCursoDetalle->getCodigoPagoCursoDetallePk(), 1, 0, 'L');            
            $pdf->Cell(25, 4, $arPagoCursoDetalle->getCursoDetalleRel()->getCursoRel()->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(80, 4, utf8_decode($arPagoCursoDetalle->getCursoDetalleRel()->getCursoRel()->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L');                
            $pdf->Cell(50, 4, utf8_decode($arPagoCursoDetalle->getCursoDetalleRel()->getCursoTipoRel()->getNombre()), 1, 0, 'L');                            
            $pdf->Cell(15, 4, number_format($arPagoCursoDetalle->getCosto(), 0, '.', ','), 1, 0, 'R');
            $var += $arPagoCursoDetalle->getCosto();
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
            
        }
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(175, 5, "TOTAL: ", 1, 0, 'R');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(15, 5, number_format($var,0, '.', ','), 1, 0, 'R');
        
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
