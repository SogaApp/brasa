<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoVacacionesDisfrutadas extends \FPDF_FPDF {
    public static $em;
    public static $codigoVacacionDisfrute;
    
    public function Generar($miThis, $codigoVacacionDisfrute) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoVacacionDisfrute = $codigoVacacionDisfrute;
        $pdf = new FormatoVacacionesDisfrutadas();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("codigoVacacionDisfrutadas_$codigoVacacionDisfrute.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10);
        $this->SetXY(10, 10);
        $this->Line(10, 10, 60, 10);
        $this->Line(10, 10, 10, 50);
        $this->Line(10, 50, 60, 50);
        $this->Cell(0, 0, $this->Image('imagenes/logos/logo.jpg' , 15 ,20, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo
        $this->SetXY(60, 10);
        $this->Cell(90, 10, utf8_decode("PROCESO GESTIÓN HUMANA"), 1, 0, 'C', 1); //cuardo mitad arriba
        $this->SetXY(60, 20);
        $this->SetFillColor(236, 236, 236);
        $this->Cell(90, 20, utf8_decode("PROCESOS DE RÉGIMEN DISCIPLINARIO"), 1, 0, 'C', 1); //cuardo mitad medio
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(60, 40);
        $this->Cell(90, 10, utf8_decode("Régimen Organizacional Interno "), 1, 0, 'C', 1); //cuardo mitad abajo
        $this->SetXY(150, 10);
        $this->Cell(50, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuadro derecho arriba
        $this->SetXY(150, 20);
        $this->Cell(50, 20, utf8_decode(""), 1, 0, 'C', 1); //cuadro derecho mitad 1
        $this->SetXY(150, 40);
        $this->Cell(50, 5, utf8_decode("Versión 01"), 1, 0, 'C', 1); //cuadro derecho abajo 1
        $this->SetXY(150, 45);
        $this->Cell(50, 5, "FECHA: 01/09/2015", 1, 0, 'C', 1); //cuadro derecho abajo 2
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        $arVacacionDisfrute = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionDisfrute();
        $arVacacionDisfrute = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionDisfrute')->find(self::$codigoVacacionDisfrute);        
        $arContenidoFormato = new \Brasa\RecursoHumanoBundle\Entity\RhuContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContenidoFormato')->find($arDisciplinario->getCodigoDisciplinarioTipoFk());
        $this->SetFont('Arial','','9');
        $this->Text(10, 55, utf8_decode("MEDELLÍN - ANTIOQUIA ") . date('Y-m-d'));
        $this->SetFont('Arial','B','10');
        $this->Text(170, 65, utf8_decode("N°: ") .  $arVacacionDisfrute->getCodigoVacacionDisfrutePk());
        $this->Ln(20);
    }

    public function Body($pdf) {
        $pdf->SetXY(10, 60);
        $pdf->SetFont('Arial', '', 10);  
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);        
        $arVacacionDisfrute = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionDisfrute();
        $arVacacionDisfrute = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionDisfrute')->find(self::$codigoVacacionDisfrute);        
        $arContenidoFormato = new \Brasa\RecursoHumanoBundle\Entity\RhuContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContenidoFormato')->find($arDisciplinario->getCodigoDisciplinarioTipoFk());
        //se reemplaza el contenido de la tabla tipo de proceso disciplinario
        function nombreMes($mes){
            setlocale(LC_TIME, 'spanish');  
            $nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, 2000)); 
            return $nombre;
        }
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 
        $sustitucion1 = $arVacacionDisfrute->getEmpleadoRel()->getNombreCorto();
        $sustitucion2 = $arVacacionDisfrute->getEmpleadoRel()->getNumeroIdentificacion();
        $sustitucion3 = $arContenidoFormato->getNombre();
        $sustitucion4 = $arConfiguracion->getNombreEmpresa();
        $sustitucion5 = $arVacacionDisfrute->getFechaDesde()->format('d');
        $sustitucion6 = nombreMes($arVacacionDisfrute->getFechaDesde()->format('m'));
        $sustitucion7 = $arVacacionDisfrute->getFechaDesde()->format('Y');
        $sustitucion8 = $arVacacionDisfrute->getFechaHasta()->format('d');
        $sustitucion9 = nombreMes($arVacacionDisfrute->getFechaHasta()->format('m'));
        $sustitucion10 = $arVacacionDisfrute->getFechaHasta()->format('Y');
        
        $cadena = $arContenidoFormato->getContenido();
        $patron1 = '/#1/';
        $patron2 = '/#2/';
        $patron3 = '/#3/';
        $patron4 = '/#4/';
        $patron5 = '/#5/';
        $patron6 = '/#6/';
        $patron7 = '/#7/';
        $patron8 = '/#8/';
        $patron9 = '/#9/';
        $patron10 = '/#a/';
        
        $cadenaCambiada = preg_replace($patron1, $sustitucion1, $cadena);
        $cadenaCambiada = preg_replace($patron2, $sustitucion2, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron3, $sustitucion3, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron4, $sustitucion4, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron5, $sustitucion5, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron6, $sustitucion6, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron7, $sustitucion7, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron8, $sustitucion8, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron9, $sustitucion9, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron10, $sustitucion10, $cadenaCambiada);
        $pdf->MultiCell(0,5, $cadenaCambiada);        
   
    }

    public function Footer() {
        //$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
        $this->SetFont('Arial', 'B', '10');
        $this->Text(10, 277, '_________________________________________________________________________________________________');
        $this->SetFont('Arial', '', '7');
        $this->Text(10, 280, 'Documento de confidencialidad alta ' );
        $this->Text(130, 280, 'JGEFECTIVOS S.A.S por el cuidado del Medio Ambiente  ');
        $this->Text(10, 285, 'COPIA CONTROLADA. Uso exclusiva de GH  ' );
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
