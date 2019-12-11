<?php
namespace Brasa\TransporteBundle\Formatos;
class FormatoRecogida extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoRecogida;
    
    public function Generar($miThis, $codigoRecogida) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoRecogida = $codigoRecogida;
        $pdf = new FormatoRecogida();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Recogida$codigoRecogida.pdf", 'D');        
        
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
        $this->Cell(150, 7, utf8_decode("ORDEN DE RECOGIDA"), 0, 0, 'C', 1);
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
        
        $arRecogida = new \Brasa\TransporteBundle\Entity\TteRecogida();
        $arRecogida = self::$em->getRepository('BrasaTransporteBundle:TteRecogida')->find(self::$codigoRecogida);                
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        
        $intY = 40;
        $this->SetFillColor(272, 272, 272);
        //linea 1
        $this->SetXY(10, $intY);
        $this->SetFont('Arial','B',8);
        $this->Cell(31, 6, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(66, 6, $arRecogida->getCodigoRecogidaPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, utf8_decode("CÓDIGO CLIENTE:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(66, 6, $arRecogida->getCodigoClienteFk(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        //linea 2
        $this->SetXY(10, $intY + 6);
        $this->Cell(31, 6, "CLIENTE:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7.2);
        $this->Cell(66, 6, utf8_decode($arRecogida->getClienteRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, utf8_decode("ANUNCIANTE:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7.2);
        $this->Cell(66, 6, $arRecogida->getAnunciante(), 1, 0, 'L', 1);
        //linea 3
        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial','B',8);
        $this->Cell(31, 6, utf8_decode("DIRECCIÓN:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7.2);
        $this->Cell(66, 6, $arRecogida->getDireccion(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, utf8_decode("TELÉFONO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(66, 6, $arRecogida->getTelefono(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        //linea 4
        $this->SetXY(10, $intY + 18);
        $this->SetFont('Arial','B',8);
        $this->Cell(31, 6, utf8_decode("FECHA ANUNCIO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(66, 6, $arRecogida->getFechaAnuncio()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, utf8_decode("FECHA RECOGIDA:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(66, 6, $arRecogida->getFechaRecogida()->format('Y-m-d'), 1, 0, 'L', 1);
        //linea 5
        $this->SetXY(10, $intY + 24);
        $this->SetFont('Arial','B',8);
        $this->Cell(31, 6, "UNIDADES:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(66, 6, utf8_decode($arRecogida->getUnidades()), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, utf8_decode("PESO REAL:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(66, 6, $arRecogida->getPesoReal(), 1, 0, 'L', 1);
        //linea 6
        $this->SetXY(10, $intY + 30);
        $this->SetFont('Arial','B',8);
        $this->Cell(31, 6, "VALOR DECLARADO:" , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        
        $this->Cell(66, 6, number_format($arRecogida->getVrDeclarado(), 2, '.', ','), 1, 0, 'R');
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, utf8_decode("PESO VOLUMEN:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(66, 6, $arRecogida->getPesoVolumen(), 1, 0, 'L', 1);
        //linea 7
        $this->SetXY(10, $intY + 38);
        $this->SetFont('Arial','B',8);
        $this->Cell(31, 8, utf8_decode("CONTENIDO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(162, 8, $arRecogida->getContenido() , 1, 0, 'L', 1);
        //linea 8
        $this->SetXY(10, $intY + 48);
        $this->SetFont('Arial','B',8);
        $this->Cell(31, 8, utf8_decode("COMENTARIOS:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(162, 8, $arRecogida->getComentarios() , 1, 0, 'L', 1);
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {

    }

    public function Body($pdf) {

    }

    public function Footer() {
        
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
