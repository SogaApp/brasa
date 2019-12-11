<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoCarta1teg extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoTipoCarta;
    public static $fechaProceso;
    public static $fechaOpcional;
    public static $codigoContrato;
    
    public function Generar($miThis, $codigoTipoCarta,$fechaProceso,$fechaOpcional,$codigoContrato) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoTipoCarta = $codigoTipoCarta;
        self::$fechaProceso = $fechaProceso;
        self::$fechaOpcional = $fechaOpcional;
        self::$codigoContrato = $codigoContrato;
        $pdf = new FormatoCarta1teg();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $this->Body($pdf);
        $pdf->Output("Carta$codigoContrato.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10);
        $arCartaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCartaTipo();
        $arCartaTipo = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCartaTipo')->find(self::$codigoTipoCarta);
        $codigoCartaTipo = $arCartaTipo->getCodigoCartaTipoPk();
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find($arCartaTipo->getCodigoContenidoFormatoFk());
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
            $this->Image('imagenes/logos/firma.jpg' , 10 ,175, 50 , 30,'JPG');
        }
        
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);        
        $this->SetFont('Arial','','9');
        $this->Text(10, 55, utf8_decode($arConfiguracion->getCiudadRel()->getNombre()). " ". self::$fechaProceso);
        $this->Ln(20);
    }

    public function Body($pdf) {
        $pdf->SetXY(10, 65);
        $pdf->SetFont('Arial', '', 10);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);        
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find(self::$codigoContrato);        
        
        //Inicio promedio mensual
        $intPeriodo = 0;
        $strPeriodo = $arContrato->getCentroCostoRel()->getPeriodoPagoRel()->getNombre();
        if ($strPeriodo == "SEMANAL"){
            $intPeriodo = 4;
        }
        if ($strPeriodo == "DECADAL"){
            $intPeriodo = 3;
        }
        if ($strPeriodo == "CATORCENAL"){
            $intPeriodo = 2;
        }
        if ($strPeriodo == "QUINCENAL"){
            $intPeriodo = 2;
        }
        if ($strPeriodo == "MENSUAL"){
            $intPeriodo = 1;
        }
        $arSuplementario = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->tiempoSuplementarioCartaLaboral($intPeriodo, $arContrato->getCodigoContratoPk());            
        $floPromedioSalario = $arSuplementario;
        $arNoPrestacional = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->noPrestacionalCartaLaboral($intPeriodo, $arContrato->getCodigoContratoPk());            
        $floNoPrestacional = $arNoPrestacional;
        //fin promedio mensual
        $arCartaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCartaTipo();
        $arCartaTipo = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCartaTipo')->find(self::$codigoTipoCarta);
        $codigoCartaTipo = $arCartaTipo->getCodigoCartaTipoPk();
        $codigoContenidoFormato = $arCartaTipo->getCodigoContenidoFormatoFk();
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        if ($codigoCartaTipo == null){
           $cadena = "La carta no tiene asociado un formato tipo carta"; 
        } else {
           if ($codigoContenidoFormato == null){
               $cadena = "La carta no tiene un formato creado en el sistema";
           } else {
               $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find($arCartaTipo->getCodigoContenidoFormatoFk());
               $cadena = $arContenidoFormato->getContenido();
             }
          }            
        //se reemplaza el contenido de la tabla tipo de proceso disciplinario
        $sustitucion1 = $arContrato->getEmpleadoRel()->getNumeroIdentificacion();
        $sustitucion2 = $arContrato->getEmpleadoRel()->getNombreCorto();
        $sustitucion3 = $arContrato->getCargoRel()->getNombre();
        /*if ($arContrato->getFechaProrrogaInicio() == null){
            $sustitucion4 = $arContrato->getFechaDesde()->format('Y-m-d');
            $sustitucion7 = $arContrato->getFechaHasta()->format('Y-m-d');
            $feci = $arContrato->getFechaDesde();
            $fecf = $arContrato->getFechaHasta();
        } else {
            $sustitucion4 = $arContrato->getFechaProrrogaInicio()->format('Y-m-d');
            $sustitucion7 = $arContrato->getFechaProrrogaFinal()->format('Y-m-d');
            $feci = $arContrato->getFechaProrrogaInicio();
            $fecf = $arContrato->getFechaProrrogaFinal();
        }*/
        $sustitucion4 = $arContrato->getFechaDesde()->format('Y-m-d');
        $sustitucion7 = $arContrato->getFechaHasta()->format('Y-m-d');
        $feci = $arContrato->getFechaDesde();
        $fecf = $arContrato->getFechaHasta();
        $sustitucion4 = strftime("%d de ". $this->MesesEspañol($feci->format('m')) ." de %Y", strtotime($sustitucion4));
        $sustitucion5 = $arConfiguracion->getNombreEmpresa();
        if (self::$fechaOpcional == null ){
            $sustitucion6 = "";
        } else {
            $sustitucion6 = self::$fechaOpcional;
            $sustitucion6 = strftime("%d de ". $this->MesesEspañol($sustitucion6->format('m')) ." de %Y", strtotime($sustitucion6->format('Y-m-d')));
        }
        $sustitucion7 = strftime("%d de ". $this->MesesEspañol($fecf->format('m')) ." de %Y", strtotime($sustitucion7));
        $sustitucion8 = $arContrato->getContratoTipoRel()->getNombre();
        $salarioLetras = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->numtoletras($arContrato->getVrSalario());
        $sustitucion9 = $salarioLetras." $(";
        $sustitucion9 .= number_format($arContrato->getVrSalario(), 2,'.',',');
        $sustitucion9 .= ")";
        $sustitucion10 = self::$fechaProceso;
        $dato = substr($sustitucion10, 5,2);
        $sustitucion10 = strftime("%d de ". $this->MesesEspañol($dato) ." de %Y", strtotime($sustitucion10));
        $promedioSalarioLetras = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->numtoletras($floPromedioSalario);
        $sustitucion11 = $promedioSalarioLetras." $(";
        $sustitucion11 .= number_format($floPromedioSalario, 2,'.',',');
        $sustitucion11 .= ")";
        $floNoPrestacionalLetras = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->numtoletras($floNoPrestacional);
        $sustitucion13 = $floNoPrestacionalLetras." $(";
        $sustitucion13 .= number_format($floNoPrestacional, 2,'.',',');
        $sustitucion13 .= ")";
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
        $patron11 = '/#b/';
        $patron13 = '/#d/';
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
        $cadenaCambiada = preg_replace($patron11, $sustitucion11, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron13, $sustitucion13, $cadenaCambiada);
        $pdf->MultiCell(0,5, $cadenaCambiada);
    }

    public function Footer() {
        //$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
        $this->Image('imagenes/logos/piedepagina.jpg' , 10 ,260, 190 , 40,'JPG'); //x,y,largo,ancho
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
