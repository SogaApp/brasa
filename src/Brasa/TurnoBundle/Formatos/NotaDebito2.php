<?php

namespace Brasa\TurnoBundle\Formatos;

class NotaDebito2 extends \FPDF_FPDF {

    public static $em;
    public static $codigoFactura;
    public static $strLetras;

    public function Generar($miThis, $codigoFactura) {

        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        ob_clean();
        $pdf = new NotaDebito2();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("NotaDebito$codigoFactura.pdf", 'D');
    }

    public function Header() {
        $this->GenerarEncabezadoFactura(self::$em);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionTurno = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracionTurno = self::$em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = self::$em->getRepository('BrasaTurnoBundle:TurFactura')->find(self::$codigoFactura);
        $this->SetFont('Arial', '', 12);
        $this->Text(90, 50, "NOTA DEBITO " . $arFactura->getNumero());        
        $this->SetFont('Arial', '', 9);
        $this->Text(15, 65, "Fecha Factura");
        $this->Text(45, 65, ucwords(strtolower($this->devuelveMes($arFactura->getFecha()->format('m')))) . " " . $arFactura->getFecha()->format('d') . " de " . $arFactura->getFecha()->format('Y'));
        $this->Text(135, 65, "Fecha Vence");
        $this->Text(170, 65, ucwords(strtolower($this->devuelveMes($arFactura->getFechaVence()->format('m')))) . " " . $arFactura->getFechaVence()->format('d') . " de " . $arFactura->getFechaVence()->format('Y'));        
        $this->Text(15, 70, utf8_decode("Señores"));
        $this->Text(45, 70, $arFactura->getClienteRel()->getNombreCorto());
        $this->Text(135, 70, "Nit");
        $this->Text(170, 70, $arFactura->getClienteRel()->getNit(). "-" . $arFactura->getClienteRel()->getDigitoVerificacion());        
        $this->Text(15, 80, "Direccion");
        $this->Text(45, 80, $arFactura->getClienteRel()->getDireccion());
        $this->Text(135, 80, "Telefono");
        $this->Text(170, 80, $arFactura->getClienteRel()->getTelefono());                
        
        $this->SetXY(110, 75);
        $this->SetMargins(10, 1, 10);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $this->SetX(15);
        $header = array('CANT', 'DETALLE', 'Vr. UNITARIO', 'Vr. TOTAL');
        //$this->SetFillColor(236, 236, 236);
        //$this->SetTextColor(0);
        //$this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 9);

        //creamos la cabecera de la tabla.
        $w = array(10, 124, 28, 28);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0)
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'L');
            else
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(8);
    }

    public function Body($pdf) {
        $pdf->Rect(15, 96, 10, 100);
        $pdf->Rect(25, 96, 124, 100);
        $pdf->Rect(149, 96, 28, 100);
        $pdf->Rect(177, 96, 28, 100);
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = self::$em->getRepository('BrasaTurnoBundle:TurFactura')->find(self::$codigoFactura);         
        $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        $arFacturaDetalles = self::$em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        $arrMeses = array();
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $strMes = $this->devuelveMes($arFacturaDetalle->getFechaProgramacion()->format('m')) . $arFacturaDetalle->getFechaProgramacion()->format('/Y');
            $nuevo = true;
            foreach ($arrMeses as $mes) {
               if($mes['mes'] == $strMes) {
                 $nuevo = false;  
               }
            }
            if($nuevo == true) {
                $arrMeses[] = array('mes' => $strMes);
            }            
        }
        $strMeses = "";
        foreach ($arrMeses as $mes) {           
            $strMeses .= $mes['mes'];             
        }
        $pdf->SetX(15);
        $pdf->Cell(10, 4, '', 0, 0, 'R');                        
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(120, 4, $arFactura->getDescripcion(), 0, 0, 'L');                        
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(30, 4, '', 0, 0, 'R');
        $pdf->Cell(30, 4, '', 0, 0, 'R'); 
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 9);
        if($arFactura->getImprimirRelacion() == false) {
            if($arFactura->getImprimirAgrupada() == 0) {                
                foreach ($arFacturaDetalles as $arFacturaDetalle) {
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, number_format($arFacturaDetalle->getCantidad(), 0, '.', ','), 0, 0, 'C');                        
                    $pdf->SetFont('Arial', 'B', 9);
                    $modalidad = "";
                    if($arFacturaDetalle->getCodigoModalidadServicioFk()) {
                        $modalidad = $arFacturaDetalle->getModalidadServicioRel()->getNombre();
                    }                    
                    $pdf->Cell(124, 4, substr(utf8_decode($arFacturaDetalle->getPuestoRel()->getNombre()) . '-'  . $modalidad, 0, 61), 0, 0, 'L');                        
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Cell(28, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 0, 0, 'R');
                    $pdf->Cell(28, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 0, 0, 'R');
                    $pdf->Ln();
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, '', 0, 0, 'R');                                   
                    $strCampo = $arFacturaDetalle->getConceptoServicioRel()->getNombreFacturacion() . " " . $arFacturaDetalle->getDetalle();            
                    $pdf->MultiCell(124, 4, $strCampo, 0, 'L'); 
                    //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');                        
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');            
                    $pdf->Ln(2);
                    $pdf->SetAutoPageBreak(true, 15);
                }                
            } else {                
                $strSql = "SELECT tur_puesto.nombre AS puesto, tur_modalidad_servicio.nombre AS modalidadServicio, tur_concepto_servicio.nombre_facturacion AS conceptoServicio, cantidad  AS cantidad, vr_precio AS precio                           
                            FROM
                            tur_factura_detalle
                            LEFT JOIN tur_puesto ON tur_factura_detalle.codigo_puesto_fk = tur_puesto.codigo_puesto_pk                            
                            LEFT JOIN tur_modalidad_servicio ON tur_factura_detalle.codigo_modalidad_servicio_fk = tur_modalidad_servicio.codigo_modalidad_servicio_pk
                            LEFT JOIN tur_concepto_servicio ON tur_factura_detalle.codigo_concepto_servicio_fk = tur_concepto_servicio.codigo_concepto_servicio_pk                            
                            WHERE codigo_factura_fk = " . self::$codigoFactura . " AND codigo_grupo_facturacion_fk IS NULL"; 
                $connection = self::$em->getConnection();
                $statement = $connection->prepare($strSql);        
                $statement->execute();
                $results = $statement->fetchAll();                
                foreach ($results as $arFacturaDetalle) {
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, number_format($arFacturaDetalle['cantidad'], 0, '.', ','), 0, 0, 'C');                        
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(124, 4, substr(utf8_decode($arFacturaDetalle['puesto']) . '-'  . $arFacturaDetalle['modalidadServicio'], 0, 61), 0, 0, 'L');                        
                    $pdf->SetFont('Arial', '', 9);

                    $pdf->Cell(28, 4, number_format($arFacturaDetalle['precio'], 0, '.', ','), 0, 0, 'R');
                    $pdf->Cell(28, 4, number_format($arFacturaDetalle['precio'], 0, '.', ','), 0, 0, 'R');
                    $pdf->Ln();
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, '', 0, 0, 'R');                                   
                    $strCampo = $arFacturaDetalle['conceptoServicio'];            
                    $pdf->MultiCell(124, 4, $strCampo, 0, 'L'); 
                    //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');                        
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');            
                    $pdf->Ln(2);
                    $pdf->SetAutoPageBreak(true, 15);
                }                
                
                $strSql = "SELECT tur_grupo_facturacion.nombre as puesto, tur_grupo_facturacion.concepto as conceptoServicio, SUM(cantidad)  AS cantidad, SUM(vr_precio) AS precio                           
                            FROM
                            tur_factura_detalle
                            LEFT JOIN tur_puesto ON tur_factura_detalle.codigo_puesto_fk = tur_puesto.codigo_puesto_pk                            
                            LEFT JOIN tur_modalidad_servicio ON tur_factura_detalle.codigo_modalidad_servicio_fk = tur_modalidad_servicio.codigo_modalidad_servicio_pk
                            LEFT JOIN tur_concepto_servicio ON tur_factura_detalle.codigo_concepto_servicio_fk = tur_concepto_servicio.codigo_concepto_servicio_pk                            
                            LEFT JOIN tur_grupo_facturacion ON tur_factura_detalle.codigo_grupo_facturacion_fk = tur_grupo_facturacion.codigo_grupo_facturacion_pk                                                        
                            WHERE codigo_factura_fk = " . self::$codigoFactura . "  AND codigo_grupo_facturacion_fk IS NOT NULL 
                        GROUP BY tur_factura_detalle.codigo_grupo_facturacion_fk "; 
                $connection = self::$em->getConnection();
                $statement = $connection->prepare($strSql);        
                $statement->execute();
                $results = $statement->fetchAll();                
                foreach ($results as $arFacturaDetalle) {
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, number_format($arFacturaDetalle['cantidad'], 0, '.', ','), 0, 0, 'C');                        
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(124, 4, substr(utf8_decode($arFacturaDetalle['puesto']), 0, 61), 0, 0, 'L');                        
                    $pdf->SetFont('Arial', '', 9);
                    if($arFacturaDetalle['cantidad'] > 0) {
                        $precioUnitario = $arFacturaDetalle['precio'] / $arFacturaDetalle['cantidad'];
                    }                    
                    $pdf->Cell(28, 4, number_format($precioUnitario, 0, '.', ','), 0, 0, 'R');
                    $pdf->Cell(28, 4, number_format($arFacturaDetalle['precio'], 0, '.', ','), 0, 0, 'R');
                    $pdf->Ln();
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, '', 0, 0, 'R');                                   
                    $strCampo = $arFacturaDetalle['conceptoServicio'];            
                    $pdf->MultiCell(124, 4, $strCampo, 0, 'L'); 
                    //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');                        
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');            
                    $pdf->Ln(2);
                    $pdf->SetAutoPageBreak(true, 15);
                }                                
            }            
        } else {
                $pdf->SetX(15);
                $pdf->Cell(10, 4, number_format(1, 0, '.', ','), 0, 0, 'C');                        
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(124, 4, $arFactura->getClienteRel()->getNombreCorto(), 0, 0, 'L');                        
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(28, 4, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 0, 0, 'R');
                $pdf->Cell(28, 4, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 0, 0, 'R');
                $pdf->Ln();
                $pdf->SetX(15);
                $pdf->Cell(10, 4, '', 0, 0, 'R');                                                   
                $pdf->MultiCell(124, 4, 'SERVICIOS DE VIGILANCIA FIJA DEL MES ' . $strMeses . ' SEGUN RELACION ANEXA', 0, 'L'); 
                //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');                        
                $pdf->Cell(28, 4, '', 0, 0, 'R');
                $pdf->Cell(28, 4, '', 0, 0, 'R');            
                $pdf->Ln(2);            
        }

    }

    public function devuelveMes($intMes) {
        $strMes = "";
        switch ($intMes) {
            case 1:
                $strMes = "ENERO";
                break;
            case 2:
                $strMes = "FEBRERO";
                break;
            case 3:
                $strMes = "MARZO";
                break;
            case 4:
                $strMes = "ABRIL";
                break;
            case 5:
                $strMes = "MAYO";
                break;
            case 6:
                $strMes = "JUNIO";
                break;
            case 7:
                $strMes = "JULIO";
                break;
            case 8:
                $strMes = "AGOSTO";
                break;
            case 9:
                $strMes = "SEPTIEMBRE";
                break;
            case 10:
                $strMes = "OCTUBRE";
                break;
            case 11:
                $strMes = "NOVIEMBRE";
                break;
            case 12:
                $strMes = "DICIEMBRE";
                break;
        }
        return $strMes;
    }

    public function Footer() {
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = self::$em->getRepository('BrasaTurnoBundle:TurFactura')->find(self::$codigoFactura);
        $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);        
        $this->SetXY(15,196);
        $this->Cell(50, 21, '', 1, 0, 'R');        
        $this->Cell(84, 21, '', 1, 0, 'R'); 
        $this->SetXY(15,217);
        $this->Cell(134, 7, '', 1, 0, 'R');        
        $this->SetXY(149,196);
        $this->Cell(28, 7, 'SUB TOTAL', 1, 0, 'L');        
        $this->Cell(28, 7, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(149,203);
        $this->Cell(28, 7, 'Base Gravable', 1, 0, 'L');        
        $this->Cell(28, 7, number_format($arFactura->getVrBaseAIU(), 0, '.', ',') , 1, 0, 'R');
        $this->SetXY(149,210);
        $this->Cell(28, 7, 'IVA 16 %', 1, 0, 'L');        
        $this->Cell(28, 7, number_format($arFactura->getVrIva(), 0, '.', ','), 1, 0, 'R'); 
        $this->SetXY(149,217);
        $this->Cell(28, 7, 'TOTAL', 1, 0, 'L');        
        $this->Cell(28, 7, number_format($arFactura->getVrTotal(), 0, '.', ','), 1, 0, 'R');                    
        $this->SetFont('Arial', '', 8);
        $plazoPago = $arFactura->getClienteRel()->getPlazoPago();
        $this->Text(66, 201, "CONDICIONES DE PAGO: A $plazoPago DIAS A PARTIR");
        $this->Text(66, 205, "DE LA FECHA DE EXPEDICION");
        $this->SetFont('Arial', '', 9);
        $this->Text(20, 201, "Recibi conforme:");
        $this->Text(20, 206, "Fecha y Nombre:");
        $this->Text(20, 211, "Sello:");
        $this->Text(20, 221, "Actividad Comercial");
        $this->Text(60, 221, $arFactura->getClienteRel()->getSectorComercialRel()->getNombre());
        $this->Text(90, 221, "Estrato =");
        $this->Ln(4);
        //$this->SetFont('Arial', '', 8);
        //$this->Text(20, $this->GetY($this->SetY(244)), $arConfiguracion->getInformacionPagoFactura());
        //$this->SetXY(30,228);
        //$this->MultiCell(110, 5, $arConfiguracion->getInformacionPagoFactura(), 0, 'L');                
        //$this->Ln();
        //$this->SetFont('Arial', 'B', 8);        
        //$this->Text(30, 241, "Observacion: Si efectura retencion en la fuente, favor aplicar tarifa del 2% Sobre Base Gravable");
        //$this->MultiCell(100, 5, "Observacion: Si efectura retencion en la fuente, favor aplicar tarifa del 2% Sobre Base Gravable", 0, 'L');                
        //$this->SetFont('Arial', '', 7);
        //$this->Text(50, 251, "Favor remitir copia de la consignacion a los correos a.mona@seracis.com y d.mejia@seracis.com");

        //Número de página
        //$this->Text(188, 273, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }

    public function GenerarEncabezadoFactura($em) {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);

        $this->SetFont('Arial', '', 5);
        $this->Text(188, 13, '');
        $this->Image('imagenes/logos/logo.jpg', 0, 0, 0.01, 0.01);
        $this->ln(11);
        $this->SetFont('Arial', 'B', 12);
        $this->ln(5);
        $this->SetFont('Arial', 'B', 10);
        //$this->Text(21, 35, "NIT " . $arConfiguracion->getNitEmpresa() . "-" . $arConfiguracion->getDigitoVerificacionEmpresa());
        $this->SetXY(258, 18);
    }

}

?>
