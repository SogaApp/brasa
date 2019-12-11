<?php
namespace Brasa\TurnoBundle\Formatos;
class FormatoPedido extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoPedido;
    
    public function Generar($miThis, $codigoPedido) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoPedido = $codigoPedido;
        $pdf = new FormatoPedido();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Pedido$codigoPedido.pdf", 'D');        
        
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
        $this->Cell(150, 7, utf8_decode("PEDIDO"), 0, 0, 'C', 1);
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
        
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arPedido = self::$em->getRepository('BrasaTurnoBundle:TurPedido')->find(self::$codigoPedido);        
        
        $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalles = self::$em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => self::$codigoPedido));
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        
        $intY = 40;
        $this->SetFillColor(272, 272, 272); 
        $this->SetXY(10, $intY);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "NUMERO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arPedido->getCodigoPedidoPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arPedido->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);       

        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "NIT:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arPedido->getClienteRel()->getNit(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);               
        
        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "CLIENTE:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arPedido->getClienteRel()->getNombreCorto(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'CONTACTO:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arPedido->getClienteRel()->getContacto(), 1, 0, 'L', 1);
        
        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "DIRECCION:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arPedido->getClienteRel()->getDireccion(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'CARGO:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 16);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "CIUDAD:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, "", 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'DEPARTAMENTO:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);        

        $this->SetXY(10, $intY + 20);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "TELEFONO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arPedido->getClienteRel()->getTelefono(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'CELULAR:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arPedido->getClienteRel()->getCelular(), 1, 0, 'L', 1);                
        
        $this->SetXY(10, $intY + 24);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "EMAIL:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arPedido->getClienteRel()->getEmail(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, '' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);                
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('COD', 'SERVICIO', 'MODALIDAD', 'PER', 'DESDE', 'HASTA', 'CANT', 'LU', 'MA', 'MI', 'JU', 'VI', 'SA', 'DO', 'FE', 'H', 'H.D', 'H.N', 'VALOR');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 30, 20, 10, 15, 15, 10, 5, 5, 5, 5, 5, 5, 5, 5, 8, 8, 8, 15);
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
        $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalles = self::$em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => self::$codigoPedido));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arPedidoDetalles as $arPedidoDetalle) {            
            $pdf->Cell(10, 4, $arPedidoDetalle->getCodigoPedidoDetallePk(), 1, 0, 'L');
            $pdf->Cell(30, 4, $arPedidoDetalle->getConceptoServicioRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(20, 4, $arPedidoDetalle->getModalidadServicioRel()->getNombre(), 1, 0, 'L');                
            $pdf->Cell(10, 4, $arPedidoDetalle->getPeriodoRel()->getNombre(), 1, 0, 'L');                
            $pdf->Cell(15, 4, $arPedidoDetalle->getDiaDesde(), 1, 0, 'L');                
            $pdf->Cell(15, 4, $arPedidoDetalle->getDiaHasta(), 1, 0, 'L');                                  
            $pdf->Cell(10, 4, number_format($arPedidoDetalle->getCantidad(), 0, '.', ','), 1, 0, 'R');                
            if($arPedidoDetalle->getLunes() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');                                  
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');                                  
            }
            if($arPedidoDetalle->getMartes() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');                                  
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');                                  
            }
            if($arPedidoDetalle->getMiercoles() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');                                  
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');                                  
            }
            if($arPedidoDetalle->getJueves() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');                                  
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');                                  
            }
            if($arPedidoDetalle->getViernes() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');                                  
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');                                  
            }
            if($arPedidoDetalle->getSabado() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');                                  
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');                                  
            }
            if($arPedidoDetalle->getDomingo() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');                                  
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');                                  
            }
            if($arPedidoDetalle->getFestivo() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');                                  
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');                                  
            }            
            $pdf->Cell(8, 4, $arPedidoDetalle->getHoras(), 1, 0, 'R');                                  
            $pdf->Cell(8, 4, $arPedidoDetalle->getHorasDiurnas(), 1, 0, 'R');                                  
            $pdf->Cell(8, 4, $arPedidoDetalle->getHorasNocturnas(), 1, 0, 'R');                                  
            $pdf->Cell(15, 4, number_format($arPedidoDetalle->getVrTotalDetalle(), 0, '.', ','), 1, 0, 'R');                
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
