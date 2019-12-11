<?php
namespace Brasa\CarteraBundle\Formatos;
class EstadoCuenta extends \FPDF_FPDF {
    public static $em;   
    public static $strWhere;
    
    public function Generar($miThis, $strWhere) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$strWhere = $strWhere;
        $pdf = new EstadoCuenta();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("EstadoCuenta.pdf", 'D');        
        
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
        $this->Cell(150, 7, utf8_decode("ESTADO CUENTA"), 0, 0, 'C', 1);
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
        
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);                 
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {


        $header = array('TIPO', 'NUMERO', 'FECHA', 'VENCE', 'ASESOR', 'PLAZO', 'DIAS', 'RANGO', 'VALOR', 'ABONO', 'SALDO');
        $pdf->SetFillColor(236, 236, 236);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(.2);
        $pdf->SetFont('Arial', 'B', 6);

        //creamos la cabecera de la tabla.
        $w = array(23, 13, 15, 15, 45, 13, 13, 13, 13, 13, 13);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Ln(4);
        $connection = self::$em->getConnection();
        $strSql = "SELECT  
                            sql_car_cartera_edades.*
                    FROM
                            sql_car_cartera_edades                       
                    WHERE 1 " . self::$strWhere;                    
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $resultados = $statement->fetchAll();
        
        foreach ($resultados as $resultado) {
            $pdf->Cell(23, 4, $resultado['tipoCuentaCobrar'], 1, 0, 'L');                        
            $pdf->Cell(13, 4, $resultado['numeroDocumento'], 1, 0, 'L');                        
            $pdf->Cell(15, 4,$resultado['fecha'], 1, 0, 'L');                        
            $pdf->Cell(15, 4, $resultado['fechaVence'], 1, 0, 'L');                        
            $pdf->Cell(45, 4, $resultado['nombreAsesor'], 1, 0, 'L');                        
            $pdf->Cell(13, 4, $resultado['plazo'], 1, 0, 'R');
            $pdf->Cell(13, 4, $resultado['diasVencida'], 1, 0, 'R');
            $pdf->Cell(13, 4, $resultado['rango'], 1, 0, 'L');
            $pdf->Cell(13, 4, number_format($resultado['valorOriginal'], 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($resultado['abono'], 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($resultado['saldo'], 0, '.', ','), 1, 0, 'R');
            
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
