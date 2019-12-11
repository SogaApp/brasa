<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoProcesoDisciplinario1teg extends \FPDF_FPDF {
    
    public static $em;
    
    public static $codigoProcesoDisciplinarioTipo;
    public static $codigoProcesoDisciplinario;
    
    public function Generar($miThis, $codigoProcesoDisciplinarioTipo,$codigoProcesoDisciplinario) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoProcesoDisciplinarioTipo = $codigoProcesoDisciplinarioTipo;
        self::$codigoProcesoDisciplinario = $codigoProcesoDisciplinario;
        $pdf = new FormatoProcesoDisciplinario1teg();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $this->Body($pdf);
        $pdf->Output("ProcesoDisciplinario$codigoProcesoDisciplinarioTipo.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10);
        //$this->SetXY(10, 5);
        $arProcesoDisciplinarioTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo();
        $arProcesoDisciplinarioTipo = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioTipo')->find(self::$codigoProcesoDisciplinarioTipo);
        //$codigoProcesoDisciplinarioTipo = $arProcesoDisciplinarioTipo->getCodigoDisciplinarioTipoPk();
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find($arProcesoDisciplinarioTipo->getCodigoContenidoFormatoFk());
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
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);        
        $this->SetFont('Arial','','9');
        $this->Text(10, 55, utf8_decode($arConfiguracion->getCiudadRel()->getNombre()). " ". date('Y-m-d'));
        $this->Ln(20);
    }

    public function Body($pdf) {
        $pdf->SetXY(10, 65);
        $pdf->SetFont('Arial', '', 10);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);        
        $arProcesoDisciplinario = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arProcesoDisciplinario = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find(self::$codigoProcesoDisciplinario);
        $arProcesoDisciplinarioTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo();
        $arProcesoDisciplinarioTipo = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioTipo')->find(self::$codigoProcesoDisciplinarioTipo);
        $codigoProcesoDisciplinarioTipo = $arProcesoDisciplinarioTipo->getCodigoDisciplinarioTipoPk();
        $codigoContenidoFormato = $arProcesoDisciplinarioTipo->getCodigoContenidoFormatoFk();
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        if ($codigoProcesoDisciplinarioTipo == null){
           $cadena = "El proceso disciplinario no tiene asociado un formato tipo proceso disciplinario"; 
        } else {
           if ($codigoContenidoFormato == null){
               $cadena = "El proceso disciplinario no tiene un formato creado en el sistema";
           } else {
               $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find($arProcesoDisciplinarioTipo->getCodigoContenidoFormatoFk());
               $cadena = $arContenidoFormato->getContenido();
             }
          }            
        //se reemplaza el contenido de la tabla tipo de proceso disciplinario
        $sustitucion1 = $arProcesoDisciplinario->getEmpleadoRel()->getNumeroIdentificacion();
        $sustitucion2 = $arProcesoDisciplinario->getEmpleadoRel()->getNombreCorto();
        $cargo = "";
        if ($arProcesoDisciplinario->getCodigoCargoFk() != null){
            $cargo = $arProcesoDisciplinario->getCargoRel()->getNombre();
        }
        $sustitucion3 = $cargo;
        if ($arProcesoDisciplinario->getFechaDesdeSancion() == null){
            $sustitucion4 = new \DateTime('now');
        } else {
            $sustitucion4 = $arProcesoDisciplinario->getFechaDesdeSancion();
        }
        
        $sustitucion4 = strftime("%d de ". $this->MesesEspañol($sustitucion4->format('m')) ." de %Y", strtotime($sustitucion4->format('Y/m/d')));
        $sustitucion5 = $arConfiguracion->getNombreEmpresa();
        $sustitucion6 = $arProcesoDisciplinario->getAsunto();
        //$sustitucion7 = $arProcesoDisciplinario->getAsunto();
        if ($arProcesoDisciplinario->getCodigoDisciplinarioMotivoFk() == null){
            $descargos = "";
        } else {
            $descargos = $arProcesoDisciplinario->getDisciplinarioMotivoRel()->getNombre();
        }
        $sustitucion8 = $descargos;
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arProcesoDisciplinario->getCodigoContratoFk());
        $sustitucion9 = $arContrato->getContratoTipoRel()->getNombre();
        $sustitucion10 = $arProcesoDisciplinario->getDisciplinarioTipoRel()->getNombre();
        if ($arProcesoDisciplinario->getFechaHastaSancion() == null){
            $sustitucion11 = new \DateTime('now');
        } else {
            $sustitucion11 = $arProcesoDisciplinario->getFechaHastaSancion();
        }
        
        $sustitucion11 = strftime("%d de ". $this->MesesEspañol($sustitucion11->format('m')) ." de %Y", strtotime($sustitucion11->format('Y/m/d'))); 
        if ($arProcesoDisciplinario->getFechaIngresoTrabajo() == null){
            $sustitucion12 = new \DateTime('now');
        } else {
            $sustitucion12 = $arProcesoDisciplinario->getFechaIngresoTrabajo();
        }
        $sustitucion12 = strftime("%d de ". $this->MesesEspañol($sustitucion12->format('m')) ." de %Y", strtotime($sustitucion12->format('Y/m/d')));
        $sustitucion13 = $arProcesoDisciplinario->getDiasSuspencion();
        $sustitucion14 = $arProcesoDisciplinario->getPuesto();
        $sustitucion15 = $arProcesoDisciplinario->getZona();
        $sustitucion16 = $arProcesoDisciplinario->getOperacion();
        if ($arProcesoDisciplinario->getFechaIncidente() == null){
            $sustitucion17 = new \DateTime('now');
        } else {
            $sustitucion17 = $arProcesoDisciplinario->getFechaIncidente();
        }
        $sustitucion17 = strftime("%d de ". $this->MesesEspañol($sustitucion17->format('m')) ." de %Y", strtotime($sustitucion17->format('Y/m/d')));
        if ($arProcesoDisciplinario->getFechaNotificacion() == null){
            $sustitucion18 = new \DateTime('now');
        } else {
            $sustitucion18 = $arProcesoDisciplinario->getFechaNotificacion();
        }
        $sustitucion18 = strftime("%d de ". $this->MesesEspañol($sustitucion18->format('m')) ." de %Y", strtotime($sustitucion18->format('Y/m/d')));
        $sustitucion19 = $arProcesoDisciplinario->getCodigoDisciplinarioPk();
        //$cadena = $arContenidoFormato->getContenido();
        $patron1 = '/#1/';
        $patron2 = '/#2/';
        $patron3 = '/#3/';
        $patron4 = '/#4/';
        $patron5 = '/#5/';
        $patron6 = '/#6/';
        //$patron7 = '/#7/';
        $patron8 = '/#8/';
        $patron9 = '/#9/';
        $patron10 = '/#a/';
        $patron11 = '/#b/';
        $patron12 = '/#c/';
        $patron13 = '/#d/';
        $patron14 = '/#e/';
        $patron15 = '/#f/';
        $patron16 = '/#g/';
        $patron17 = '/#h/';
        $patron18 = '/#i/';
        $patron19 = '/#j/';
        $cadenaCambiada = preg_replace($patron1, $sustitucion1, $cadena);
        $cadenaCambiada = preg_replace($patron2, $sustitucion2, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron3, $sustitucion3, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron4, $sustitucion4, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron5, $sustitucion5, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron6, $sustitucion6, $cadenaCambiada);
        //$cadenaCambiada = preg_replace($patron7, $sustitucion7, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron8, $sustitucion8, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron9, $sustitucion9, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron10, $sustitucion10, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron11, $sustitucion11, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron12, $sustitucion12, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron13, $sustitucion13, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron14, $sustitucion14, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron15, $sustitucion15, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron16, $sustitucion16, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron17, $sustitucion17, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron18, $sustitucion18, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron19, $sustitucion19, $cadenaCambiada);
        
        $pdf->MultiCell(0,5, $cadenaCambiada);
    }

    public function Footer() {
        //$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
        $this->Image('imagenes/logos/piedepagina.jpg' , 65 ,208, 150 , 90,'JPG');
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
