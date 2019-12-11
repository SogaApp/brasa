<?php
namespace Brasa\TransporteBundle\Formatos;
class FormatoGuia extends \FPDF_FPDF {
    public static $em;
    public static $codigoGuia;
    
    public function Generar($miThis, $codigoGuia) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoGuia = $codigoGuia;
        $pdf = new FormatoGuia();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Guia" . $codigoGuia . ".pdf", 'D');                
    } 
    
    public function Header() {
        $arGuia = new \Brasa\TransporteBundle\Entity\TteGuia();
        $arGuia = self::$em->getRepository('BrasaTransporteBundle:TteGuia')->find(self::$codigoGuia);        
        $intY = 10;
        $intXMargen = 5;
        for($i = 0; $i <= 3; $i++) {
            $this->Image('imagenes/logos/LogoCotrascal.jpg', $intXMargen, $intY, 35, 17);        
            $this->SetFillColor(255, 255, 255);                
            $this->SetFont('Arial','B',6);
            $this->SetXY(150, $intY);
            $this->Text(50, $intY, "COMPAÑIA DE TRANSPORTE Y SERVICIOS LOGISTICOS COTRASCAL S.A.S");
            $this->Text(50, $intY+3, "NIT 900.151.590-1");
            $this->Text(50, $intY+6, "TEL 6463232 - FAX 6463535");
            $this->SetFont('Arial','B',10);
            $this->SetXY(150, $intY);
            $this->Cell(2, 6, "GUIA NUMERO", 0, 0, 'L', 1);        
            $this->SetXY(200, $intY);
            $this->Cell(2, 6, $arGuia->getNumeroGuia(), 0, 0, 'R', 1);        
            $this->Ln();

            $intY = $intY + 10;
            // Linea1
            $this->SetFont('Arial','B', 6);
            $this->SetXY(50, $intY);
            $this->MultiCell(30, 4, "Fecha\n ", 1, 'L',false);        
            $this->SetFont('Arial','B', 6);
            $this->SetXY(80, $intY);
            $this->MultiCell(60, 4, "Origen\n ", 1, 'L',false);
            $this->SetXY(140, $intY);
            $this->MultiCell(60, 4, "Tipo de guia\n ", 1, 'L',false);
            $this->SetFont('Arial','', 6);                
            $this->Text(53, $intY + 6, $arGuia->getFechaIngreso()->format('Y/m/d'));
            $this->Text(81, $intY + 6 , $arGuia->getCiudadOrigenRel()->getNombre());
            $this->Text(141, $intY + 6, $arGuia->getTipoPagoRel()->getNombre());

            $intY = $intY + 8;
            // Linea2
            $this->SetFont('Arial','B', 6);
            $this->SetXY($intXMargen, $intY);
            $this->MultiCell(75, 5, "REMITENTE ", 1, 'L',false);        
            $this->SetFont('Arial','B', 6);
            $this->SetXY(80, $intY);
            $this->MultiCell(30, 5, "NIT ", 1, 'L',false);
            $this->SetXY(110, $intY);
            $this->MultiCell(30, 5, "TELEFONO ", 1, 'L',false);
            $this->SetXY(140, $intY);
            $this->MultiCell(60, 5, "DIRECCION ", 1, 'L',false);        
            $this->SetFont('Arial','', 6);                
            $this->Text(24, $intY + 3,  substr($arGuia->getTerceroRel()->getNombreCorto(), 0, 50));
            $this->Text(85, $intY + 3, $arGuia->getTerceroRel()->getNit());
            $this->Text(123, $intY + 3, $arGuia->getTerceroRel()->getTelefono());        
            $this->Text(154, $intY + 3 , substr($arGuia->getTerceroRel()->getDireccion(), 0, 35));

            $intY = $intY + 5;
            // Linea3
            $this->SetFont('Arial','B', 6);
            $this->SetXY($intXMargen, $intY);
            $this->MultiCell(105, 5, "DESTINATARIO ", 1, 'L',false);        
            $this->SetFont('Arial','B', 6);
            $this->SetXY(110, $intY);
            $this->MultiCell(30, 5, "NIT ", 1, 'L',false);
            $this->SetXY(140, $intY);
            $this->MultiCell(60, 5, "TELEFONO ", 1, 'L',false);
            $this->SetFont('Arial','', 6);                
            $this->Text(28, $intY + 3, $arGuia->getNombreDestinatario());
            //$this->Text(123, $intY + 3 , $arGuia->getDireccionDestinatario());
            $this->Text(153, $intY + 3, $arGuia->getTelefonoDestinatario());        

            $intY = $intY + 5;
            // Linea4
            $this->SetFont('Arial','B', 6);
            $this->SetXY($intXMargen, $intY);
            $this->MultiCell(75, 5, "DIRECCION ", 1, 'L',false);        
            $this->SetFont('Arial','B', 6);
            $this->SetXY(80, $intY);
            $this->MultiCell(60, 5, "CIUDAD DESTINO ", 1, 'L',false);
            $this->SetXY(140, $intY);
            $this->MultiCell(60, 5, "BARRIO ", 1, 'L',false);        
            $this->SetFont('Arial','', 6);                
            $this->Text(24, $intY + 3, $arGuia->getDireccionDestinatario());
            $this->Text(100, $intY + 3 , $arGuia->getCiudadDestinoRel()->getNombre());
            //$this->Text(153, $intY + 3, $arGuia->getTelefonoDestinatario()); 

            $intY = $intY + 6;
            // Linea5
            $this->SetFont('Arial','B', 6);
            $this->SetXY($intXMargen, $intY);
            $this->Cell(10, 5, "UND ", 1, 0, 'L', 1);
            $this->SetXY(15, $intY);
            $this->Cell(15, 5, $arGuia->getCtUnidades(), 1, 0, 'L', 1);
            $this->SetXY(30, $intY);
            $this->Cell(10, 5, "KIL REAL ", 1, 0, 'L', 1);
            $this->SetXY(40, $intY);
            $this->Cell(10, 5, $arGuia->getCtPesoReal(), 1, 0, 'L', 1);
            $this->SetXY(50, $intY);        
            $this->Cell(10, 5, "KIL VOL ", 1, 0, 'L', 1);        
            $this->SetXY(60, $intY);        
            $this->Cell(10, 5, $arGuia->getCtPesoVolumen(), 1, 0, 'L', 1);        
            $this->SetXY(70, $intY);
            $this->Cell(60, 5, "DOCUMENTOS ANEXOS: " . $arGuia->getDocumentoCliente(), 1, 0, 'L', 1);        
            $this->SetXY(130, $intY);
            $this->Cell(30, 5, "VALOR DECLARADO ", 1, 0, 'L', 1);        
            $this->Cell(40, 5,  number_format($arGuia->getVrDeclarado(), 0, '.', ','), 1, 0, 'R', 1);        

            $intY = $intY + 5;
            // Linea6
            $this->SetFont('Arial','', 6);
            $this->SetXY($intXMargen, $intY);
            //$this->MultiCell(60, 5, "REMITENTE DECLARA QUE LA MERCANCIA ES DE ORIGEN LICITO Y DICE CONTENER ", 1, 'L',false);
            $this->Cell(65, 9, "", 1, 0, 'L', 1);                            
            $this->SetXY($intXMargen, $intY+1);
            $this->MultiCell(65, 2, $arGuia->getContenido());                                    
            $this->SetXY($intXMargen, $intY+9);
            $this->Cell(65, 11, "", 1, 0, 'L', 1);              
            $this->SetXY(70, $intY);       
            $this->Cell(60, 15, "", 1, 0, 'L', 1);        
            $this->SetXY(70, $intY+1);
            $this->MultiCell(60, 2, $arGuia->getComentarios());        
            $this->SetXY(130, $intY);
            $this->Cell(30, 5, "VALOR MANEJO ", 1, 0, 'L', 1);  
            $this->Cell(40, 5,  number_format($arGuia->getVrManejo(), 0, '.', ','), 1, 0, 'R', 1);        

            $intY = $intY + 5;
            // Linea4
            $this->SetFont('Arial','', 6);       
            $this->SetXY(130, $intY);
            $this->Cell(30, 5, "VALOR FLETE ", 1, 0, 'L', 1);        
            $this->Cell(40, 5,  number_format($arGuia->getVrFlete(), 0, '.', ','), 1, 0, 'R', 1);        

            $intY = $intY + 5;
            // Linea4
            $this->SetFont('Arial','', 6);       
            $this->SetXY(130, $intY);
            $this->Cell(30, 5, "VALOR TOTAL ", 1, 0, 'L', 1);         
            $this->Cell(40, 5,  number_format($arGuia->getVrFlete()+$arGuia->getVrManejo(), 0, '.', ','), 1, 0, 'R', 1);        

            $intY = $intY + 5;
            // Linea4
            $this->SetFont('Arial','', 6);       
            $this->SetXY(70, $intY);
            $this->Cell(24, 5, "FECHA ENTREGA ", 1, 0, 'L', 1);
            $this->SetXY(90, $intY);
            $this->Cell(6, 5, " ", 1, 0, 'L', 1);
            $this->Cell(6, 5, " ", 1, 0, 'L', 1);
            $this->Cell(6, 5, " ", 1, 0, 'L', 1);
            $this->Cell(12, 5, "HORA ", 1, 0, 'L', 1);
            $this->Cell(5, 5, " ", 1, 0, 'L', 1);
            $this->Cell(5, 5, " ", 1, 0, 'L', 1);
            $this->Cell(70, 5, " ", 1, 0, 'L', 1);

            $this->SetFont('Arial','B', 5);       
            $this->Text($intXMargen, $intY+7, "EL CONTRATO DE TRANSPORTE SE RIGE POR LOS ARTICULOS 1010, 1011, 1027, 1008 DEL C. DEL C.");            
            $intY = $intY +16;
        }

        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {

    }

    public function Body($pdf) {

    }

    public function Footer() {

    }    
}

?>
