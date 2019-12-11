<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoCartaPresentacion extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoContrato;
    
    public static $usuario;
    
    public function Generar($miThis, $codigoContrato, $usuario) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoContrato = $codigoContrato;
        self::$usuario = $usuario;
        $pdf = new FormatoCartaPresentacion();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("CartaPresentacion$codigoContrato.pdf", 'D');        
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
        $this->Cell(90, 10, utf8_decode(""), 1, 0, 'C', 1); //cuardo mitad arriba
        $this->SetXY(60, 20);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',16);
        $this->Cell(90, 20, utf8_decode("CARTA PRESENTACIÓN"), 1, 0, 'C', 1); //cuardo mitad medio
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10);
        $this->SetXY(60, 40);
        $this->Cell(90, 10, utf8_decode(" "), 1, 0, 'C', 1); //cuardo mitad abajo
        $this->SetXY(150, 10);
        $this->Cell(50, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuadro derecho arriba
        $this->SetXY(150, 20);
        $this->Cell(50, 20, utf8_decode(""), 1, 0, 'C', 1); //cuadro derecho mitad 1
        $this->SetXY(150, 40);
        $this->Cell(50, 5, utf8_decode("Versión 01"), 1, 0, 'C', 1); //cuadro derecho abajo 1
        $this->SetXY(150, 45);
        $this->Cell(50, 5, "Fecha: 01/09/2015", 1, 0, 'C', 1); //cuadro derecho abajo 2
        
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find(self::$codigoContrato);        
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find(19);        
        $this->SetXY(10, 10);
        $this->Ln(10);
        //$this->Cell(0, 0, $this->Image('imagenes/logos/firmanomina.jpg' , 15 ,150, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo
    }

    public function Body($pdf) {
        $pdf->SetXY(10, 80);
        $pdf->SetFont('Arial', '', 10);  
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find(self::$codigoContrato);
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find(19);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $pdf->Text(10, 60, utf8_decode($arConfiguracion->getCiudadRel()->getNombre()). ", ". strftime("%d de %B de %Y", strtotime(date('Y-m-d'))));
        $usuarioCarta = self::$usuario;
        $usuarioCarta = $usuarioCarta->getNombreCorto();
        //se reemplaza el contenido de la tabla contenido formato
        $sustitucion1 = $arContrato->getEmpleadoRel()->getNumeroIdentificacion();
        $sustitucion2 = $arContrato->getEmpleadoRel()->getNombreCorto();
        $sustitucion3 = $arContrato->getCargoRel()->getNombre();
        
        $sustitucion4 = $arContrato->getFechaDesde()->format('Y-m-d');
        $sustitucion7 = $arContrato->getFechaHasta()->format('Y-m-d');
        $feci = $arContrato->getFechaDesde();
        $fecf = $arContrato->getFechaHasta();
        
        $sustitucion4 = strftime("%d de ". $this->MesesEspañol($feci->format('m')) ." de %Y", strtotime($sustitucion4));
        $sustitucion5 = $arConfiguracion->getNombreEmpresa();
        
        $sustitucion7 = strftime("%d de ". $this->MesesEspañol($fecf->format('m')) ." de %Y", strtotime($sustitucion7));
        $sustitucion8 = $arContrato->getContratoTipoRel()->getNombre();
        $salarioLetras = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->numtoletras($arContrato->getVrSalario());
        $sustitucion9 = $salarioLetras." $(";
        $sustitucion9 .= number_format($arContrato->getVrSalario(), 2,'.',',');
        $sustitucion9 .= ")";
        
        $sustitucion10 = date('Y/m/d');
        $dato = substr($sustitucion10, 5,2);
        $sustitucion10 = strftime("%d de ". $this->MesesEspañol($dato) ." de %Y", strtotime($sustitucion10));
        $floPromedioSalario = 0;
        $promedioSalarioLetras = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->numtoletras($floPromedioSalario);
        $sustitucion11 = $promedioSalarioLetras." $(";
        $sustitucion11 .= number_format($floPromedioSalario, 2,'.',',');
        $sustitucion11 .= ")";
        if ($arContrato->getEmpleadoRel()->getCodigoCiudadExpedicionFk() != null){
            $ciudadExpedicion = $arContrato->getEmpleadoRel()->getCiudadExpedicionRel()->getNombre();
        } else {
            $ciudadExpedicion = '';
        }
        $sustitucion12 = $ciudadExpedicion;
        //$sustitucion13 = "no prestacinal";
        $sustitucion14 = $arContrato->getEntidadSaludRel()->getNombre();
        $sustitucion15 = $arContrato->getEntidadPensionRel()->getNombre();
        $sustitucion16 = $arConfiguracionNomina->getEntidadRiesgoProfesionalRel()->getNombre();
        $sustitucion17 = $arContrato->getEntidadCajaRel()->getNombre();
        $sustitucion18 = $usuarioCarta;
        $cadena = $arContenidoFormato->getContenido();
        $patron1 = '/#1/';
        $patron2 = '/#2/';
        $patron3 = '/#3/';
        $patron4 = '/#4/';
        $patron5 = '/#5/';
        //$patron6 = '/#6/';
        $patron7 = '/#7/';
        $patron8 = '/#8/';
        $patron9 = '/#9/';
        $patron10 = '/#a/';
        //$patron11 = '/#b/';
        $patron12 = '/#c/';
        //$patron13 = '/#d/';
        $patron14 = '/#e/';
        $patron15 = '/#f/';
        $patron16 = '/#g/';
        $patron17 = '/#h/';
        $patron18 = '/#i/';
        $cadenaCambiada = preg_replace($patron1, $sustitucion1, $cadena);
        $cadenaCambiada = preg_replace($patron2, $sustitucion2, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron3, $sustitucion3, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron4, $sustitucion4, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron5, $sustitucion5, $cadenaCambiada);
        //$cadenaCambiada = preg_replace($patron6, $sustitucion6, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron7, $sustitucion7, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron8, $sustitucion8, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron9, $sustitucion9, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron10, $sustitucion10, $cadenaCambiada);
        //$cadenaCambiada = preg_replace($patron11, $sustitucion11, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron12, $sustitucion12, $cadenaCambiada);
        //$cadenaCambiada = preg_replace($patron13, $sustitucion13, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron14, $sustitucion14, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron15, $sustitucion15, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron16, $sustitucion16, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron17, $sustitucion17, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron18, $sustitucion18, $cadenaCambiada);
        $pdf->MultiCell(0,5, $cadenaCambiada);
    }

    public function Footer() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find(self::$codigoContrato);
        $strRutaImagen = '';
        if($arContrato->getEmpleadoRel()->getRutaFoto() != "") {
            $strRutaImagen = $arConfiguracion->getRutaAlmacenamiento()."imagenes/"."empleados/" . $arContrato->getEmpleadoRel()->getRutaFoto();
            //$strRutaImagen = "/var/www/html/almacenamientodorchester/imagenes/"."empleados/" . $arContrato->getEmpleadoRel()->getRutaFoto();
            $this->Cell(0, 0, $this->Image($strRutaImagen , 150 ,200, 25 , 32,'JPG'), 0, 0, 'C', 0); //foto
        }
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
        //$this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
        
    }

    public static function MesesEspañol($mes) {
        $mesEspañol = "";
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
