<?php
namespace Brasa\RecursoHumanoBundle\Formatos;

class FormatoDescargoEstelar extends \FPDF_FPDF {
    public static $em;
    public static $codigoDescargo;
    
    public function Generar($miThis, $codigoDescargo) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoDescargo = $codigoDescargo;
        $pdf = new FormatoDescargoEstelar();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Descargo$codigoDescargo.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10);                
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find(8);
        if ($arContenidoFormatoA->getRequiereFormatoIso() == 1){
            $this->SetFillColor(272, 272, 272);
            $this->SetFont('Arial','B',12);
            $this->SetXY(10, 10);
            $this->Line(10, 10, 190, 10); //horizontal superior
            $this->Line(10, 10, 10, 50); // vertical 1
            $this->Line(10, 50, 60, 50); // horizontal inferior
            //$this->Line(70, 70, 70, 50); // vertical 2 x1,y1,x2,y2
            $this->Cell(0, 0, $this->Image('imagenes/logos/logo.jpg' , 15 ,20, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo x,y,w,h
            $this->SetXY(60, 10);
            $this->Cell(140, 7, utf8_decode("SEGURIDAD ESTELAR LTDA"), 1, 0, 'C', 1); //cuadro mitad arriba
            $this->SetXY(60, 17);            
            $this->Cell(140, 7, utf8_decode("SISTEMA DE GESTIÓN INTEGRAL"), 1, 0, 'C', 1); //cuardo mitad medio
            $this->SetFont('Arial','B',10);
            $this->SetXY(60, 24);
            $this->Cell(47, 7, utf8_decode("CODIGO: ".utf8_decode($arContenidoFormatoA->getCodigoFormatoIso())." ".$arContenidoFormatoA->getVersion()), 1, 0, 'C', 1); //cuardo mitad abajo
            $this->Cell(47, 7, utf8_decode('PAGINA ') . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuardo mitad abajo
            $this->Cell(46, 7, utf8_decode("FECHA: 12/11/2013"), 1, 0, 'C', 1); //cuardo mitad abajo
            $this->SetXY(60, 31);
            $this->SetFont('Arial','B',8);
            $this->Cell(140, 19, utf8_decode("FORMATO PARA COMUNICACIÓN INTERNA Y EXTERNA"), 1, 0, 'C', 1); //cuardo mitad abajo
            
            //$this->Cell(50, 20, utf8_decode($arContenidoFormatoA->getCodigoFormatoIso()), 1, 0, 'C', 1); //cuadro derecho mitad 1
            //$this->SetXY(150, 40);
            //$this->Cell(50, 5, utf8_decode("Versión ". $arContenidoFormatoA->getVersion() .""), 1, 0, 'C', 1); //cuadro derecho abajo 1
            //$this->SetXY(150, 45);
            //$this->Cell(50, 5, $arContenidoFormatoA->getFechaVersion()->format('Y-m-d'), 1, 0, 'C', 1); //cuadro derecho abajo 2
        } else {
            $this->Image('imagenes/logos/logo.jpg' , 10 ,5, 50 , 30,'JPG');
            //$this->Image('imagenes/logos/encabezado.jpg' , 115 ,5, 90 , 40,'JPG');
        }

        
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);        
        $this->SetFont('Arial','','9');
        //$this->Text(10, 55, utf8_decode($arConfiguracion->getCiudadRel()->getNombre()). " ". date('Y-m-d'));
        $this->Ln(20);
    }

    public function Body($pdf) {
        $pdf->SetXY(10, 60);
        $pdf->SetFont('Arial', '', 10);                
        $arDescargo = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioDescargo();
        $arDescargo = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioDescargo')->find(self::$codigoDescargo);        
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find(8);
        
        $pdf->Text(10, 55, utf8_decode("MEDELLÍN - ANTIOQUIA ") .  $arDescargo->getFecha()->format('Y-m-d'));
        
        $pdf->Text(170, 65, utf8_decode("N°: ") .  $arDescargo->getCodigoDisciplinarioDescargoPk());
        //se reemplaza el contenido de la tabla tipo de proceso disciplinario
        $sustitucion1 = $arDescargo->getEmpleadoRel()->getNombreCorto();
        $sustitucion2 = $arDescargo->getEmpleadoRel()->getNumeroIdentificacion();
        $sustitucion3 = $arDescargo->getDescargo();
        
        $cadena = $arContenidoFormato->getContenido();
        $patron1 = '/#1/';
        $patron2 = '/#2/';
        $patron3 = '/#3/';
        $cadenaCambiada = preg_replace($patron1, $sustitucion1, $cadena);
        $cadenaCambiada = preg_replace($patron2, $sustitucion2, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron3, $sustitucion3, $cadenaCambiada);
        $pdf->MultiCell(0,5, $cadenaCambiada);        
   
    }

    public function Footer() {
        //$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
        //$this->Image('imagenes/logos/piedepagina.jpg' , 65 ,208, 150 , 90,'JPG');
        $this->SetXY(10,276);
        $this->SetFont('Arial','',10);
        $this->Cell(47,7, 'ELABORADO POR: SG',1,0,'C');
        $this->Cell(47,7, 'REVISADO POR: GG',1,0,'C');
        $this->Cell(47,7, 'APROBADO POR: GG',1,0,'C');
        $this->Cell(47,7, 'FECHA: 12/11/2013',1,0,'C');
        $this->SetXY(10,285);
        $this->SetFont('Arial','B',14);
        $this->Cell(0,5, 'PROPIEDAD PARA USO EXCLUSIVO DE SEGURIDAD ESTELAR LTDA',0,0,'C');
    } 
    
    public static function MesesEspañol($mes) {
        
        if ($mes == '01'){
            $mesEspañol = "Enero";
        }
        if ($mes == '02'){
            $mesEspañol = "Febrero";
        }
        if ($mes == '03'){
            $mesEspañol = "Marzo";
        }
        if ($mes == '04'){
            $mesEspañol = "Abril";
        }
        if ($mes == '05'){
            $mesEspañol = "Mayo";
        }
        if ($mes == '06'){
            $mesEspañol = "Junio";
        }
        if ($mes == '07'){
            $mesEspañol = "Julio";
        }
        if ($mes == '08'){
            $mesEspañol = "Agosto";
        }
        if ($mes == '09'){
            $mesEspañol = "Septiembre";
        }
        if ($mes == '10'){
            $mesEspañol = "Octubre";
        }
        if ($mes == '11'){
            $mesEspañol = "Noviembre";
        }
        if ($mes == '12'){
            $mesEspañol = "Diciembre";
        }

        return $mesEspañol;
    }
}

?>
