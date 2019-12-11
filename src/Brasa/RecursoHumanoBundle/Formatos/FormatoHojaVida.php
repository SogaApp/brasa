<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoHojaVida extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoEmpleado;
    
    public function Generar($miThis, $codigoEmpleado) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoEmpleado = $codigoEmpleado;
        $pdf = new FormatoHojaVida();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Empleado$codigoEmpleado.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find(self::$codigoEmpleado);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(18);
        
        $this->SetFillColor(272, 272, 272);        
        $this->SetFont('Arial','b',8);
        $this->SetXY(10, 5);
        $this->Line(10, 5, 60, 5);
        $this->Line(10, 5, 10, 26);
        $this->Line(10, 26, 60, 26);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);

        $this->SetXY(50, 5);
        $this->SetFont('Arial','b',12);
        $this->Cell(100, 21, "HOJA DE VIDA EMPRESARIAL" , 1, 0, 'C', 1);
        $this->SetFont('Arial','b',8);
        $this->Cell(45, 7, $arContenidoFormatoA->getCodigoFormatoIso() , 1, 0, 'C', 1);
        $this->SetXY(150, 12);
        $this->Cell(45, 7, $arContenidoFormatoA->getVersion() , 1, 0, 'C', 1);
        $this->SetXY(150, 19);
        $this->Cell(45, 7, $arContenidoFormatoA->getFechaVersion()->format('Y-m-d') , 1, 0, 'C', 1);
        $this->SetXY(195, 5);
        $this->Cell(9, 7, "" , 1, 0, 'C', 1);
        $this->SetXY(195, 12);
        $this->Cell(9, 7, "" , 1, 0, 'C', 1);
        $this->SetXY(195, 19);
        $this->Cell(9, 7, "" , 1, 0, 'C', 1);
        
        $this->SetXY(164, 35);
        $this->Cell(35, 45, 'foto', 1, 0, 'C', 1);
        if($arEmpleado->getRutaFoto() != "") {
            $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
            $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
            $strRuta = $arConfiguracion->getRutaAlmacenamiento() . "imagenes/empleados/" . $arEmpleado->getRutaFoto();        
            if(file_exists($strRuta)){
                $this->Image($strRuta, 167, 40, 30, 35);            
            }            
        }
        
        $this->SetXY(25, 20);
        $this->Cell(30, 35, "FECHA DE INGRESO" , 0, 0, 'L', 0);
        $this->Cell(100, 35, "______________________________________________________________" , 0, 0, 'C', 0);
        $this->SetXY(55, 20);
        if ($arEmpleado->getFechaContrato() == ""){
            $this->Cell(100, 35, "0000-00-00", 0, 0, 'C', 0);
        } else {
            $this->Cell(100, 35, $arEmpleado->getFechaContrato()->format('Y/m/d'), 0, 0, 'C', 0);
        }
        
        $this->SetXY(25, 26);
        $this->Cell(30, 40, "EMPRESA USUARIA" , 0, 0, 'L', 0);
        $this->Cell(100, 40, "______________________________________________________________" , 0, 0, 'C', 0);
        $this->SetXY(55, 26);
        $this->SetFont('Arial','b',7);
        if ($arEmpleado->getCentroCostoRel() == ""){
            $this->Cell(100, 40, "SIN DEFINIR" , 0, 0, 'C', 0);
        } else {
            $this->Cell(100, 40, utf8_decode($arEmpleado->getCentroCostoRel()->getNombre()) , 0, 0, 'C', 0);
        }
        
        $this->SetXY(25, 32);
        $this->SetFont('Arial','b',8);
        $this->Cell(30, 45, "CARGO" , 0, 0, 'L', 0);
        $this->Cell(100, 45, "______________________________________________________________" , 0, 0, 'C', 0);
        $this->SetXY(55, 32);
        $this->Cell(100, 45, utf8_decode($arEmpleado->getCargoDescripcion()) , 0, 0, 'C', 0);
        $this->SetXY(25, 38);
        $this->Cell(30, 50, "SALARIO" , 0, 0, 'L', 0);
        $this->Cell(100, 50, "______________________________________________________________" , 0, 0, 'C', 0);
        $this->SetXY(55, 38);
        $this->Cell(100, 50, number_format($arEmpleado->getVrSalario(), 2, '.', ','), 0, 0, 'C', 0);
        $this->SetXY(25, 44);
        $this->Cell(30, 55, "BONIFICACION" , 0, 0, 'L', 0);
        $this->Cell(100, 55, "______________________________________________________________" , 0, 0, 'C', 0);
        $this->SetXY(55, 44);
        $this->Cell(100, 55, "0.00" , 0, 0, 'C', 0);
        $this->SetXY(25, 50);
        $this->Cell(30, 60, "RIESGO ARP" , 0, 0, 'L', 0);
        $this->Cell(100, 60, "______________________________________________________________" , 0, 0, 'C', 0);
        $this->SetXY(55, 50);
        if ($arEmpleado->getClasificacionRiesgoRel() == ""){
            $this->Cell(100, 60, "SIN DEFINIR" , 0, 0, 'C', 0);
        } else {
            $this->Cell(100, 60, $arEmpleado->getClasificacionRiesgoRel()->getNombre() , 0, 0, 'C', 0);
        }
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find(self::$codigoEmpleado);
        //Traer los estudios del empleado
        $arEmpleadoEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
        $arEmpleadoEstudios = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->findBy(array('codigoEmpleadoFk' => self::$codigoEmpleado));
        //Traer los familiares del empleado
        $arEmpleadoFamiliares = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia();
        $arEmpleadoFamiliares = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoFamilia')->findBy(array('codigoEmpleadoFk' => self::$codigoEmpleado));
        $this->Ln(8);        
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);        
        $intX = 3;
        $intY = 90;
        $this->SetXY($intX, $intY);
        $this->Cell(204, 5, utf8_decode("INFORMACIÓN PERSONAL"), 1, 0, 'C', 0);
        $this->SetXY($intX, $intY + 5);
        $this->Cell(80, 5, "APELLIDOS", 1, 0, 'C', 0);
        $this->Cell(80, 5, "NOMBRES COMPLETOS", 1, 0, 'C', 0);
        $this->Cell(44, 5, "CEDULA", 1, 0, 'C', 0);
        $this->SetXY($intX, $intY + 10);
        $this->SetFont('Arial','',9);
        $this->Cell(80, 8, utf8_decode($arEmpleado->getApellido1()." ".$arEmpleado->getApellido2()), 1, 0, 'C', 0);
        $this->Cell(80, 8, utf8_decode($arEmpleado->getNombre1()." ".$arEmpleado->getNombre2()), 1, 0, 'C', 0);
        $this->Cell(44, 8, $arEmpleado->getNumeroIdentificacion(), 1, 0, 'C', 0);
        $this->SetXY($intX, $intY + 18);
        $this->SetFont('Arial','B',8);
        $this->Cell(39, 5, "FECHA DE NACIMIENTO", 1, 0, 'C', 0);
        $this->Cell(55, 5, "CIUDAD DE NACIMIENTO", 1, 0, 'C', 0);
        $this->Cell(24, 5, "GENERO", 1, 0, 'C', 0);
        $this->Cell(33, 5, utf8_decode("LIBRETA MILITAR N°"), 1, 0, 'C', 0);
        $this->Cell(53, 5, "LUGAR DE EXPEDICION C.C", 1, 0, 'C', 0);
        $this->SetXY($intX, $intY + 23);
        $this->SetFont('Arial','',8);
        $this->Cell(39, 8, $arEmpleado->getFechaNacimiento()->format('Y/m/d'), 1, 0, 'C', 1);
        $this->SetFont('Arial','',7);
        if ($arEmpleado->getCodigoCiudadNacimientoFk() != null){
            $ciudadNacimiento = $arEmpleado->getCiudadNacimientoRel()->getNombre();
        } else {
            $ciudadNacimiento = '';
        }
        $this->Cell(55, 8, utf8_decode($ciudadNacimiento), 1, 0, 'C', 1);
        if ($arEmpleado->getCodigoSexoFk() == "F") { 
            $this->Cell(24, 8, "FEMENINO", 1, 0, 'C', 1);
        }
        else {
            $this->Cell(24, 8, "MASCULINO", 1, 0, 'C', 1);
        }
        $this->SetFont('Arial','',8);
        $this->Cell(33, 8, $arEmpleado->getLibretaMilitar(), 1, 0, 'C', 0);
        $this->SetFont('Arial','',7);
        if ($arEmpleado->getCodigoCiudadExpedicionFk() != null){
            $ciudadExpedicion = $arEmpleado->getCiudadExpedicionRel()->getNombre();
        } else {
            $ciudadExpedicion = '';
        }
        $this->Cell(53, 8, utf8_decode($ciudadExpedicion), 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 31);
        $this->SetFont('Arial','B',7.5);
        $this->Cell(46, 5, utf8_decode("DIRECCIÓN DE RESIDENCIA"), 1, 0, 'C', 1);
        $this->Cell(34, 5, "DEPARTAMENTO", 1, 0, 'C', 1);
        $this->Cell(49, 5, "CUIDAD", 1, 0, 'C', 1);
        $this->Cell(46, 5, "BARRIO", 1, 0, 'C', 1);
        $this->Cell(29, 5, "TELEFONO", 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 36);
        $this->SetFont('Arial','',7);
        $this->Cell(46, 8, $arEmpleado->getDireccion(), 1, 0, 'C', 1);
        $this->Cell(34, 8, utf8_decode($arEmpleado->getCiudadRel()->getDepartamentoRel()->getNombre()), 1, 0, 'C', 1);
        $this->Cell(49, 8, utf8_decode($arEmpleado->getCiudadRel()->getNombre()), 1, 0, 'C', 1);
        $this->Cell(46, 8, utf8_decode($arEmpleado->getBarrio()), 1, 0, 'C', 1);
        $this->Cell(29, 8, $arEmpleado->getTelefono(), 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 44);
        $this->SetFont('Arial','B',8);
        $this->Cell(33, 5, "CELULAR", 1, 0, 'C', 1);
        $this->Cell(28, 5, "PADRE FAMILIA", 1, 0, 'C', 1);
        $this->Cell(29, 5, "CABEZA FAMILIA", 1, 0, 'C', 1);
        $this->Cell(24, 5, "E.CIVIL", 1, 0, 'C', 1);
        $this->Cell(17, 5, "GRUPO S", 1, 0, 'C', 1);
        $this->Cell(13, 5, "RH", 1, 0, 'C', 1);
        $this->Cell(60, 5, utf8_decode("CUENTA DE AHORROS N°"), 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 49);
        $this->SetFont('Arial','',8);
        $this->Cell(33, 8, $arEmpleado->getCelular(), 1, 0, 'C', 1);
        if ($arEmpleado->getPadreFamilia()== 1) {
            $this->Cell(28, 8, "SI", 1, 0, 'C', 1);
        } else {
            $this->Cell(28, 8, "NO", 1, 0, 'C', 1);
        }
        if ($arEmpleado->getCabezaHogar()== 1) {
            $this->Cell(29, 8, "SI", 1, 0, 'C', 1);
        } else {
            $this->Cell(29, 8, "NO", 1, 0, 'C', 1);
        }
        $this->Cell(24, 8, $arEmpleado->getEstadoCivilRel()->getNombre(), 1, 0, 'C', 1);
        if ($arEmpleado->getCodigoRhPk() != null){
            $rh = $arEmpleado->getRhRel()->getTipo();
        } else {
            $rh = '';
        }
        $this->Cell(17, 8, $rh, 1, 0, 'C', 1);
        $this->Cell(13, 8, $rh, 1, 0, 'C', 1);
        $this->Cell(60, 8, $arEmpleado->getCuenta(), 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 57);
        $this->SetFont('Arial','B',8);
        $this->Cell(61, 5, "EPS", 1, 0, 'C', 1);
        $this->Cell(78, 5, "AFP", 1, 0, 'C', 1);
        $this->Cell(65, 5, "CCF", 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 62);
        $this->SetFont('Arial','',6.5);
        if ($arEmpleado->getCodigoEntidadSaludFk()== null) {
            $this->Cell(61, 8, "SIN CONTRATO", 1, 0, 'C', 1);
        } else {
            $this->Cell(61, 8, utf8_decode($arEmpleado->getEntidadSaludRel()->getNombre()), 1, 0, 'C', 1);
        }
        if ($arEmpleado->getCodigoEntidadPensionFk()== null) {
            $this->Cell(78, 8, "SIN CONTRATO", 1, 0, 'C', 1);
        } else {
            $this->Cell(78, 8, utf8_decode($arEmpleado->getEntidadPensionRel()->getNombre()), 1, 0, 'C', 1);
        }
        if ($arEmpleado->getCodigoEntidadCajaFk()== null) {
            $this->Cell(65, 8, "SIN CONTRATO", 1, 0, 'C', 1);
        } else {
            $this->Cell(65, 8, utf8_decode($arEmpleado->getEntidadCajaRel()->getNombre()), 1, 0, 'C', 1);
        }
        $this->SetXY($intX, $intY + 70);
        $this->SetFont('Arial','B',8);
        $this->Cell(41, 5, "NIVEL DE ESTUDIO", 1, 0, 'C', 1);
        $this->Cell(13, 5, "EDAD", 1, 0, 'C', 1);
        $this->Cell(73, 5, "CORREO ELECTRONICO", 1, 0, 'C', 1);
        $this->Cell(22, 5, "CAMISA", 1, 0, 'C', 1); 
        $this->Cell(22, 5, "JEANS", 1, 0, 'C', 1);
        $this->Cell(33, 5, "CALZADO", 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 75);
        $this->SetFont('Arial','',8);
        $empleadoEstudio = "";
        if ($arEmpleado->getCodigoEmpleadoEstudioTipoFk() != null){
            $empleadoEstudio = $arEmpleado->getEmpleadoEstudioTipoRel()->getNombre();
        }
        $this->Cell(41, 8, $empleadoEstudio, 1, 0, 'C', 1);
        //Calculo edad
        $varFechaNacimientoAnio = $arEmpleado->getFechaNacimiento()->format('Y');
        $varFechaNacimientoMes = $arEmpleado->getFechaNacimiento()->format('m');
        $varMesActual = date('m');
        if ($varMesActual >= $varFechaNacimientoMes){
            $varEdad = date('Y') - $varFechaNacimientoAnio;
        } else {
            $varEdad = date('Y') - $varFechaNacimientoAnio -1;
        }
        //Fin calculo edad
        $this->Cell(13, 8, $varEdad, 1, 0, 'C', 1);
        $this->Cell(73, 8, $arEmpleado->getCorreo(), 1, 0, 'C', 1);
        $this->Cell(22, 8, $arEmpleado->getCamisa(), 1, 0, 'C', 1); 
        $this->Cell(22, 8, $arEmpleado->getJeans(), 1, 0, 'C', 1);
        $this->Cell(33, 8, $arEmpleado->getCalzado(), 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 85);
        $this->SetFont('Arial','B',8);
        $this->Cell(204, 5, utf8_decode("INFORMACIÓN ACADEMICA"), 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 90);
        $this->Cell(48, 6, "ESTUDIOS", 1, 0, 'C', 1);
        $this->Cell(61, 6, utf8_decode("INSTITUCIÓN"), 1, 0, 'C', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(34, 6, "CUIDAD", 1, 0, 'C', 1);
        $this->Cell(61, 6, "TITULO OBTENIDO", 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 96);
        $rango = 96;
        foreach ($arEmpleadoEstudios as $arEmpleadoEstudios){
               $this->SetXY($intX, $intY + $rango);
               $this->SetFont('Arial','',6);
               $this->Cell(48, 6, $arEmpleadoEstudios->getEmpleadoEstudioTipoRel()->getNombre(), 1, 0, 'L', 1);
               $this->SetFont('Arial','',6);
               $this->Cell(61, 6, utf8_decode($arEmpleadoEstudios->getInstitucion()), 1, 0, 'C', 1);
               $this->SetFont('Arial','',6);
               $this->Cell(34, 6, utf8_decode($arEmpleadoEstudios->getCiudadRel()->getNombre()), 1, 0, 'C', 1);
               $this->Cell(61, 6, utf8_decode($arEmpleadoEstudios->getTitulo()), 1, 0, 'C', 1);
               $this->Ln();
               $rango = $rango + 6;
        }
        $rango = $rango + 2;
        $this->SetXY($intX, $intY + $rango);
        $this->SetFont('Arial','b',8);
        $this->Cell(204, 5, utf8_decode("INFORMACIÓN FAMILIAR"), 1, 0, 'C', 1);
        $rango = $rango + 5;
        $this->SetXY($intX, $intY + $rango);
        $this->SetFont('Arial','b',8);
        $this->Cell(21, 5, "PARENTESCO", 1, 0, 'L', 1);
        $this->Cell(52, 5, "NOMBRES Y APELLIDOS", 1, 0, 'C', 1);
        $this->Cell(30, 5, "EPS", 1, 0, 'C', 1);
        $this->Cell(32, 5, "CCF", 1, 0, 'C', 1);
        $this->Cell(18, 5, "FECHA NAC", 1, 0, 'C', 1);
        $this->Cell(34, 5, "OCUPACION", 1, 0, 'C', 1);
        $this->Cell(17, 5, "TELEFONO", 1, 0, 'C', 1);
        $rango = $rango + 5; 
        foreach ($arEmpleadoFamiliares as $arEmpleadoFamiliares){
            $this->SetXY($intX, $intY + $rango);
            $this->SetFont('Arial','B',8);
            $this->Cell(21, 6, utf8_decode($arEmpleadoFamiliares->getEmpleadoFamiliaParentescoRel()->getNombre()), 1, 0, 'L', 1);      
            $this->SetFont('Arial','',6);
            $this->Cell(52, 6, utf8_decode($arEmpleadoFamiliares->getNombres()), 1, 0, 'L', 1);
            $this->Cell(30, 6, utf8_decode($arEmpleadoFamiliares->getEntidadSaludRel()->getNombre()), 1, 0, 'L', 1);
            $this->Cell(32, 6, utf8_decode($arEmpleadoFamiliares->getEntidadCajaRel()->getNombre()), 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $fechaNacimientoFamiliar = "";
            if ($arEmpleadoFamiliares->getFechaNacimiento() != null){
                $fechaNacimientoFamiliar = $arEmpleadoFamiliares->getFechaNacimiento()->format('Y/m/d');
            }
            $this->Cell(18, 6, $fechaNacimientoFamiliar, 1, 0, 'L', 1);
            $this->SetFont('Arial','',6);
            $this->Cell(34, 6, utf8_decode($arEmpleadoFamiliares->getOcupacion()), 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $this->Cell(17, 6, $arEmpleadoFamiliares->getTelefono(), 1, 0, 'L', 1);
            $this->Ln();
            $rango = $rango + 6;
        }
        
//creamos la cabecera de la tabla.
           
            
        //Restauraci�n de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);        
    }

    public function Footer() {
        /*$this->SetFont('Arial','B', 10); 
        $this->Cell(30, 35, "Firma empresa" , 0, 0, 'L', 0);        
        $this->Cell(30, 35, "Firma empleado" , 0, 0, 'L', 0); */
        
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find(self::$codigoEmpleado);
        $this->SetFont('Arial', 'B', 9);
        $this->Text(10, 285, "FIRMA: _____________________________________________");
        $this->Text(10, 289, $arEmpleado->getNombreCorto());
        $this->Text(10, 294, "C.C.:     ______________________ de ____________________");
        $this->Text(105, 285, "FIRMA: _____________________________________________");
        $this->Text(105, 289, $arConfiguracion->getNombreEmpresa());
        $this->Text(105, 294, "NIT: ". $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa());
        $this->SetFont('Arial', '', 8);
        $this->Text(185, 292, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
