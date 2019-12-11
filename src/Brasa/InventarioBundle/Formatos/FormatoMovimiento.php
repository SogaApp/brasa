<?php
namespace Brasa\InventarioBundle\Formatos;

class FormatoMovimiento extends \FPDF_FPDF {
    
    public static $em;
    
    public static $codigoMovimiento;
    
    public function Generar($miThis, $codigoMovimiento) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoMovimiento = $codigoMovimiento;
        $pdf = new FormatoMovimiento();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Movimiento_$codigoMovimiento.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = self::$em->getRepository('BrasaInventarioBundle:InvMovimiento')->find(self::$codigoMovimiento);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(10);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',13);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 13, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode($arMovimiento->getDocumentoRel()->getNombre()), 0, 0, 'C', 1);
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
        //fila 1
        $this->SetXY(10, 40);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "CODIGO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arMovimiento->getCodigoMovimientoPk() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arMovimiento->getFecha()->format('Y/m/d') , 1, 0, 'L', 1);
        //fila2
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "NUMERO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 6, utf8_decode($arMovimiento->getNumero()) , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 6, utf8_decode("") , 1, 0, 'L', 1);
        //fila3
        $this->SetXY(10, 50);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, utf8_decode("TERCERO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, utf8_decode($arMovimiento->getTerceroRel()->getNombreCorto()) , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, "", 1, 0, 'L', 1);
        
        
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(20);
        $header = array('CODIGO', 'ITEM', 'CANTIDAD', 'PRECIO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(30, 80, 40, 40);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauraci�n de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $arDetalleMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
        $arDetalleMovimiento = self::$em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->findBy(array('codigoMovimientoFk' => self::$codigoMovimiento));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        $douTotal = 0;
        foreach ($arDetalleMovimiento as $arDetalleMovimiento) { 
            $pdf->Cell(30, 4, $arDetalleMovimiento->getCodigoDetalleMovimientoPk(), 1, 0, 'L');                        
            $pdf->Cell(80, 4, $arDetalleMovimiento->getItemRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(40, 4, $arDetalleMovimiento->getCantidad(), 1, 0, 'L');
            $pdf->Cell(40, 4, number_format($arDetalleMovimiento->getVrPrecio(), 2, '.', ','), 1, 0, 'R');
            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 33);
        }
        
        
    }   

    public function Footer() {
                
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
