<?php
namespace Brasa\RecursoHumanoBundle\Formatos;

class FormatoDescargo extends \FPDF_FPDF {
    public static $em;
    public static $codigoDescargo;
    
    public function Generar($miThis, $codigoDescargo) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoDescargo = $codigoDescargo;
        $pdf = new FormatoDescargo();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Descargo$codigoDescargo.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10);        
        /*$arProcesoDisciplinarioTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo();
        $arProcesoDisciplinarioTipo = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioTipo')->find(self::$codigoProcesoDisciplinarioTipo);        */
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find(8);
        if ($arContenidoFormatoA->getRequiereFormatoIso() == 1){
            $this->SetFillColor(272, 272, 272);
            $this->SetFont('Arial','B',10);
            $this->SetXY(10, 10);
            $this->Line(10, 10, 60, 10);
            $this->Line(10, 10, 10, 50);
            $this->Line(10, 50, 60, 50);
            $this->Cell(0, 0, $this->Image('imagenes/logos/logo.jpg' , 15 ,20, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo
            $this->SetXY(60, 10);
            $this->Cell(90, 10, utf8_decode("PROCESO GESTIÓN HUMANA"), 1, 0, 'C', 1); //cuadro mitad arriba
            $this->SetXY(60, 20);
            $this->SetFillColor(236, 236, 236);
            $this->Cell(90, 20, utf8_decode($arContenidoFormatoA->getTitulo()), 1, 0, 'C', 1); //cuardo mitad medio
            $this->SetFillColor(272, 272, 272);
            $this->SetXY(60, 40);
            $this->Cell(90, 10, utf8_decode(""), 1, 0, 'C', 1); //cuardo mitad abajo
            $this->SetXY(150, 10);
            $this->Cell(50, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuadro derecho arriba
            $this->SetXY(150, 20);
            $this->Cell(50, 20, utf8_decode($arContenidoFormatoA->getCodigoFormatoIso()), 1, 0, 'C', 1); //cuadro derecho mitad 1
            $this->SetXY(150, 40);
            $this->Cell(50, 5, utf8_decode("Versión ". $arContenidoFormatoA->getVersion() .""), 1, 0, 'C', 1); //cuadro derecho abajo 1
            $this->SetXY(150, 45);
            $this->Cell(50, 5, $arContenidoFormatoA->getFechaVersion()->format('Y-m-d'), 1, 0, 'C', 1); //cuadro derecho abajo 2
        } else {
            $this->Image('imagenes/logos/logo.jpg' , 10 ,5, 50 , 30,'JPG');
            $this->Image('imagenes/logos/encabezado.jpg' , 115 ,5, 90 , 40,'JPG');
        }        
        $this->EncabezadoDetalles();         
    }

    public function EncabezadoDetalles() {
        $arDescargo = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioDescargo();
        $arDescargo = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioDescargo')->find(self::$codigoDescargo);        
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find(8);        
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
        $this->SetFont('Arial', 'B', '10');
        /*$this->Text(10, 277, '_________________________________________________________________________________________________');
        $this->SetFont('Arial', '', '7');
        $this->Text(10, 280, 'Documento de confidencialidad alta ' );
        $this->Text(130, 280, 'JGEFECTIVOS S.A.S por el cuidado del Medio Ambiente  ');
        $this->Text(10, 285, 'COPIA CONTROLADA. Uso exclusiva de GH  ' );*/
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
