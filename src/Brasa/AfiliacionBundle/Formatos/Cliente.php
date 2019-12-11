<?php
namespace Brasa\AfiliacionBundle\Formatos;
class Cliente extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoCliente;
    
    public function Generar($miThis, $codigoCliente) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoCliente = $codigoCliente;
        $pdf = new Cliente();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Cliente$codigoCliente.pdf", 'D');        
        
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
        $this->Cell(150, 7, utf8_decode("CLIENTE"), 0, 0, 'C', 1);
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
        
        $arCliente = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
        $arCliente = self::$em->getRepository('BrasaAfiliacionBundle:AfiCliente')->find(self::$codigoCliente);        
                
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        $intY = 40;
        //linea 1
        $this->SetFillColor(272, 272, 272); 
        $this->SetXY(10, $intY);
        $this->SetFont('Arial','B',7);
        $this->Cell(20, 5, utf8_decode("CODIGO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(49, 5, $arCliente->getCodigoClientePk(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(19, 5, utf8_decode("NIT:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(47, 5, $arCliente->getNit(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(17, 5, utf8_decode("DV:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(45, 5, $arCliente->getDigitoVerificacion(), 1, 0, 'L', 1);
        //linea 2
        $this->SetXY(10, $intY+5);
        $this->SetFont('Arial','B',7);
        $this->Cell(20, 5, utf8_decode("CLIENTE:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',6.7);
        $this->Cell(49, 5, $arCliente->getNombreCorto(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(19, 5, utf8_decode("ASESOR:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',6.4);
        $asesor = "";
        if ($arCliente->getCodigoAsesorFk() != null){
            $asesor = $arCliente->getAsesorRel()->getNombre();
        }
        $this->Cell(47, 5, utf8_decode($asesor), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(17, 5, 'EMAIL', 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(45, 5, $arCliente->getEmail(), 1, 0, 'L', 1);
        //linea 3
        $this->SetXY(10, $intY+10);
        $this->SetFont('Arial','B',7);
        $this->Cell(20, 5, utf8_decode("TELEFONO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(49, 5, $arCliente->getTelefono(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(19, 5, utf8_decode("CELULAR:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(47, 5, $arCliente->getCelular(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(17, 5, 'FAX', 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(45, 5, $arCliente->getFax(), 1, 0, 'L', 1);        
        //linea 4
        $this->SetXY(10, $intY+15);
        $this->SetFont('Arial','B',7);
        $this->Cell(20, 5, utf8_decode("CIUDAD:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $ciudad = "";
        if ($arCliente->getCodigoCiudadFk() != null){
            $ciudad = $arCliente->getCiudadRel()->getNombre();
        }
        $this->Cell(49, 5, $ciudad, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(19, 5, utf8_decode("DIRECCION:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(47, 5, $arCliente->getDireccion(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(17, 5, 'BARRIO' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(45, 5, $arCliente->getBarrio(), 1, 0, 'L', 1);
        //linea 5
        $this->SetXY(10, $intY+20);
        $this->SetFont('Arial','B',7);
        $this->Cell(20, 5, utf8_decode("CONTACTO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',6.7);
        $this->Cell(49, 5, $arCliente->getContacto(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(19, 5, 'CELULAR', 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(47, 5, $arCliente->getCelularContacto(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(17, 5, utf8_decode("TELEFONO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(45, 5, $arCliente->getTelefonoContacto(), 1, 0, 'L', 1);
        //linea 6
        $this->SetXY(10, $intY+25);
        $this->SetFont('Arial','B',7);
        $this->Cell(20, 5, utf8_decode("SUCURSAL:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(49, 5, $arCliente->getCodigoSucursal(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6);
        $this->Cell(19, 5, 'INDEPENDIENTE:', 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        if ($arCliente->getIndependiente() == 1){
            $independiente = "SI";
        } else {
            $independiente = "NO";
        }
        $this->Cell(47, 5, $independiente, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(17, 5, utf8_decode("") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(45, 5, '', 1, 0, 'L', 1);
        //linea 7
        $this->SetXY(10, $intY+30);
        $this->SetFont('Arial','B',7);
        $this->Cell(20, 5, utf8_decode("PENSION:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arCliente->getGeneraPension() == 1){
            $pension = "SI";
        } else {
            $pension = "NO";
        }
        $this->Cell(49, 5, $pension, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(19, 5, '% PENSION:', 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(47, 5, $arCliente->getPorcentajePension(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(17, 5, "F. PAGO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $formaPago = "";
        if ($arCliente->getCodigoFormaPagoFk() != null){
            $formaPago = $arCliente->getFormaPagoRel()->getNombre();
        }
        $this->Cell(45, 5, $formaPago, 1, 0, 'L', 1);
        //linea 8
        $this->SetXY(10, $intY+35);
        $this->SetFont('Arial','B',7);
        $this->Cell(20, 5, utf8_decode("SALUD:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arCliente->getGeneraSalud() == 1){
            $salud = "SI";
        } else {
            $salud = "NO";
        }
        $this->Cell(49, 5, $salud, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(19, 5, '% SALUD:', 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(47, 5, $arCliente->getPorcentajeSalud(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(17, 5, "P. PAGO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(45, 5, $arCliente->getPlazoPago(), 1, 0, 'L', 1);
        //linea 9
        $this->SetXY(10, $intY+40);
        $this->SetFont('Arial','B',7);
        $this->Cell(20, 5, utf8_decode("CAJA:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arCliente->getGeneraCaja() == 1){
            $caja = "SI";
        } else {
            $caja = "NO";
        }
        $this->Cell(49, 5, $caja, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(19, 5, '% CAJA:', 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(47, 5, $arCliente->getPorcentajeCaja(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(17, 5, "AFILIACION:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(45, 5, number_format($arCliente->getAfiliacion(), 2, '.', ','), 1, 0, 'R', 1);
        //linea 10
        $this->SetXY(10, $intY+45);
        $this->SetFont('Arial','B',7);
        $this->Cell(20, 5, utf8_decode("RIESGOS:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arCliente->getGeneraRiesgos() == 1){
            $riesgo = "SI";
        } else {
            $riesgo = "NO";
        }
        $this->Cell(49, 5, $riesgo, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(19, 5, 'REDONDEO C:', 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arCliente->getRedondearCobro() == 1){
            $redondedo = "SI";
        } else {
            $redondedo = "NO"; 
        }
        $this->Cell(47, 5, $redondedo, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(17, 5, "ADMON:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(45, 5, number_format($arCliente->getAdministracion(), 2, '.', ','), 1, 0, 'R', 1);
        //linea 11
        $this->SetXY(10, $intY+50);
        $this->SetFont('Arial','B',7);
        $this->Cell(20, 5, utf8_decode("SENA:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arCliente->getGeneraSena() == 1){
            $sena = "SI";
        } else {
            $sena = "NO";
        }
        $this->Cell(49, 5, $sena, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(19, 5, '', 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(47, 5, '', 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(17, 5, '' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(45, 5, '', 1, 0, 'L', 1);
        //linea 12
        $this->SetXY(10, $intY+55);
        $this->SetFont('Arial','B',7);
        $this->Cell(20, 5, utf8_decode("ICBF:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        if ($arCliente->getGeneraIcbf() == 1){
            $icbf = "SI";
        } else {
            $icbf = "NO";
        }
        $this->Cell(49, 5, $icbf, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(19, 5, '', 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(47, 5, '', 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(17, 5, '' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(45, 5, '', 1, 0, 'L', 1);
        //linea 13
        $this->SetXY(10, $intY+60);
        $this->SetFont('Arial','B',7);
        $this->Cell(197, 5, utf8_decode("COMENTARIOS:").' '.$arCliente->getComentarios() , 1, 0, 'L', 1);        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        /*$this->Ln(14);
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
        $this->Ln(4);*/
    }

    public function Body($pdf) {
        /*$arClienteDetalles = new \Brasa\AfiliacionBundle\Entity\AfiClienteDetalle();
        $arClienteDetalles = self::$em->getRepository('BrasaAfiliacionBundle:AfiClienteDetalle')->findBy(array('codigoClienteFk' => self::$codigoCliente));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arClienteDetalles as $arClienteDetalle) {            
            $pdf->Cell(11, 4, $arClienteDetalle->getCodigoClienteDetallePk(), 1, 0, 'L');
            $pdf->Cell(167, 4, $arClienteDetalle->getClienteTipoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(15, 4, number_format($arClienteDetalle->getPrecio(), 2, '.', ','), 1, 0, 'R');                                             
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }*/
    }

    public function Footer() {
        
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
