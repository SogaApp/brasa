<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoCertificadoIngreso extends \FPDF_FPDF {
    public static $em;
    public static $codigoEmpleado;
    public static $strFechaCertificado;
    public static $strFechaExpedicion;
    public static $strLugarExpedicion;
    public static $strAfc;
    public static $strCertifico1;
    public static $strCertifico2;
    public static $strCertifico3;
    public static $strCertifico4;
    public static $strCertifico5;
    public static $strCertifico6;
    public static $totalPrestacional;
    public static $floPension;
    public static $floSalud;
    public static $datFechaInicio;
    public static $datFechaFin; 
    public static $totalCesantiaseIntereses;
    public static $douRetencion; 
    public static $duoGestosRepresentacion;
    public static $douOtrosIngresos;
    public static $duoTotalIngresos;
    
    public function Generar($miThis, $codigoEmpleado,$strFechaExpedicion,$strLugarExpedicion,$strFechaCertificado,$strAfc,$strCertifico1,$strCertifico2,$strCertifico3,$strCertifico4,$strCertifico5,$strCertifico6,$totalPrestacional,$floPension,$floSalud,$datFechaInicio,$datFechaFin,$totalCesantiaseIntereses,$douRetencion,$duoGestosRepresentacion,$douOtrosIngresos,$duoTotalIngresos,$strRuta) {
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoEmpleado = $codigoEmpleado;
        self::$strFechaCertificado = $strFechaCertificado;
        self::$strFechaExpedicion = $strFechaExpedicion;
        self::$strLugarExpedicion = $strLugarExpedicion;
        self::$strAfc = $strAfc;
        self::$strCertifico1 = $strCertifico1;
        self::$strCertifico2 = $strCertifico2;
        self::$strCertifico3 = $strCertifico3;
        self::$strCertifico4 = $strCertifico4;
        self::$strCertifico5 = $strCertifico5;
        self::$strCertifico6 = $strCertifico6;
        self::$totalPrestacional = $totalPrestacional;
        self::$floPension = $floPension;
        self::$floSalud = $floSalud;
        self::$datFechaInicio = $datFechaInicio;
        self::$datFechaFin = $datFechaFin;
        self::$totalCesantiaseIntereses = $totalCesantiaseIntereses;
        self::$douRetencion = $douRetencion;
        self::$duoGestosRepresentacion = $duoGestosRepresentacion;
        self::$douOtrosIngresos = $douOtrosIngresos;
        self::$duoTotalIngresos = $duoTotalIngresos;
        $pdf = new FormatoCertificadoIngreso();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        //$pdf->Output("CertificadoIngreso_$codigoEmpleado.pdf", 'D');
        
        if($strRuta == "") {
            $pdf->Output("certificado$codigoEmpleado.pdf", 'D');        
        } else {
            $pdf->Output($strRuta."certificado$codigoEmpleado.pdf", 'F');        
        }
        
    } 
    
    public function Header() {
        
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find(self::$codigoEmpleado);        
        $arCiudad = new \Brasa\GeneralBundle\Entity\GenCiudad();
        $arCiudad = self::$em->getRepository('BrasaGeneralBundle:GenCiudad')->find(self::$strLugarExpedicion);        
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);        
        $this->SetFillColor(255, 255, 255);
        $this->SetDrawColor(00, 99, 00);
        $this->SetFont('Arial','B',12);
        //logo dian
        $this->SetXY(5, 5);
        $this->Line(5, 5, 35, 5);
        $this->Line(5, 5, 5, 20);
        $this->Line(5, 20, 35, 20);
        $this->Line(35, 5, 35, 20);
        $this->Image('imagenes/logos/dian.png', 6, 6, 28, 12);
        //titulo del certificado
        $this->SetXY(35, 5);
        $this->Line(35, 5, 145, 5);
        $this->Cell(110, 7.5, "Certificado de ingresos y Retenciones para Personas" , 0, 0, 'C', 1);
        $this->SetXY(35, 13);
        $this->Line(35, 20, 145, 20);
        $this->Cell(110, 6.5, utf8_decode("Naturales Empleados Año Gravable ". self::$strFechaCertificado) , 0, 0, 'C', 1);
        //logo muisca
        $this->Line(145, 5, 175, 5);
        $this->Line(145, 5, 145, 20);
        $this->Line(145, 20,175, 20);
        $this->Line(175, 5, 175, 20);
        $this->Image('imagenes/logos/muisca.png', 146, 6, 28, 15);
        //logo 220
        $this->Line(175, 5, 205, 5);
        $this->Line(175, 5, 175, 20);
        $this->Line(175, 20, 205, 20);
        $this->Line(205, 5, 205, 20);
        $this->Image('imagenes/logos/220.png', 175, 5, 30, 15);
        $this->SetXY(5, 20);
        $this->SetFont('Arial','',8);
        $this->Cell(100, 10, "Antes de diligenciar este formulario lea cuidadosamente las instrucciones" , 1, 0, 'C', 1);
        $this->SetFont('Arial','b',8);
        $this->Cell(100, 10, utf8_decode("4. Número de formulario") , 1, 0, 'L', 1);
        $this->SetXY(12, 30);
        //Retenedor
        $this->Image('imagenes/logos/retenedor.jpg', 5, 31, 5, 20);
        $this->Line(5, 30, 5, 54);//linea derecha de la imagen retenedor
        $this->Cell(60, 6, utf8_decode("5. Número de identificación Tributaria (NIT)") , 1, 0, 'L', 1);
        $this->Cell(13, 6, utf8_decode("6. DV") , 1, 0, 'L', 1);
        $this->Cell(30, 6, utf8_decode("7. Primer Apellido") , 1, 0, 'L', 1);
        $this->Cell(30, 6, utf8_decode("8. Segundo Apellido") , 1, 0, 'L', 1);
        $this->Cell(30, 6, utf8_decode("9. Primer Nombre") , 1, 0, 'L', 1);
        $this->Cell(30, 6, utf8_decode("10. Otros Nombres") , 1, 0, 'L', 1);
        $this->Line(5, 54, 12, 54);//linea abajo de la imagen retenedor
        $this->SetXY(12, 36);
        $this->SetFont('Arial','',8);
        $this->Cell(55, 6, $arConfiguracion->getNitEmpresa() , 1, 0, 'R', 1);
        $this->Cell(5, 6, " - " , 1, 0, 'C', 1);
        $this->Cell(13, 6, $arConfiguracion->getDigitoVerificacionEmpresa() , 1, 0, 'C', 1);
        $this->Cell(30, 6, "" , 1, 0, 'C', 1);
        $this->Cell(30, 6, "" , 1, 0, 'C', 1);
        $this->Cell(30, 6, "" , 1, 0, 'C', 1);
        $this->Cell(30, 6, "" , 1, 0, 'C', 1);
        $this->SetXY(12, 42);
        $this->SetFont('Arial','b',8);
        $this->Cell(193, 6, utf8_decode("11. Razón Social") , 1, 0, 'L', 1);
        $this->SetXY(12, 48);
        $this->SetFont('Arial','',8);
        $this->Cell(193, 6, utf8_decode($arConfiguracion->getNombreEmpresa()) , 1, 0, 'L', 1);
        //Asalariado
        $this->Image('imagenes/logos/asociado.jpg', 4, 55, 7, 16);
        $this->Line(5, 54, 5, 72);//linea derecha de la imagen retenedor
        $this->SetXY(12, 54);
        $this->SetFont('Arial','b',7);
        $this->Cell(31, 6, utf8_decode("24. Cod Tipo documento") , 1, 0, 'L', 1);
        $this->Cell(47, 6, utf8_decode("25. N° de documento de identificación") , 1, 0, 'L', 1);
        $this->SetFont('Arial','b',8);
        $this->Cell(115, 6, utf8_decode("Apellidos y Nombres") , 1, 0, 'L', 1);
        $this->SetXY(12, 60);
        $this->SetFont('Arial','',7);
        if ($arEmpleado->getTipoIdentificacionRel()->getCodigoTipoIdentificacionPk() == "C"){
            $this->Cell(31, 12, "CC", 1, 0, 'C', 1);
        }
        else {
            if ($arEmpleado->getTipoIdentificacionRel()->getCodigoTipoIdentificacionPk() == "E"){
                $this->Cell(31, 12, "CE", 1, 0, 'C', 1);
            }
            else {
                $this->Cell(31, 12, "TI", 1, 0, 'C', 1);
            }
        }
        
        $this->Cell(47, 12, $arEmpleado->getNumeroIdentificacion() , 1, 0, 'C', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(28, 6, utf8_decode($arEmpleado->getApellido1()) , 1, 0, 'C', 1);
        $this->Cell(30, 6, utf8_decode($arEmpleado->getApellido2()) , 1, 0, 'C', 1);
        $this->Cell(29, 6, utf8_decode($arEmpleado->getNombre1()) , 1, 0, 'C', 1);
        $this->Cell(28, 6, utf8_decode($arEmpleado->getNombre2()) , 1, 0, 'C', 1);
        $this->SetXY(90, 66);
        $this->SetFont('Arial','b',8);
        $this->Cell(28, 6, utf8_decode("26. Primer Apellido") , 1, 0, 'L', 1);
        $this->Cell(30, 6, utf8_decode("27. Segundo Apellido") , 1, 0, 'L', 1);
        $this->Cell(29, 6, utf8_decode("28. Primer Nombre") , 1, 0, 'L', 1);
        $this->Cell(28, 6, utf8_decode("29. Otros Nombres") , 1, 0, 'L', 1);
        $this->Line(5, 72, 12, 72);//linea abajo de la imagen retenedor
        //periodo de certificación
        $this->SetXY(5, 72);
        $this->Cell(65, 6, utf8_decode("Periodo de la Certificación") , 1, 0, 'C', 1);
        $this->Cell(33, 6, utf8_decode("32. Fecha Expedición") , 1, 0, 'L', 1);
        $this->Cell(60, 6, utf8_decode("33. Lugar donde se practicó la retención") , 1, 0, 'L', 1);
        $this->Cell(20, 6, utf8_decode("34. Cod Dpto") , 1, 0, 'L', 1);
        $this->Cell(22, 6, utf8_decode("35. Cod Ciudad") , 1, 0, 'L', 1);
        $this->SetXY(5, 78);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, utf8_decode("30. DE: " . self::$datFechaInicio . "  31. A: ". self::$datFechaFin . "") , 1, 0, 'C', 1);
        $this->Cell(33, 6, self::$strFechaExpedicion->format('Y/m/d') , 1, 0, 'C', 1);
        $this->Cell(60, 6, utf8_decode($arCiudad->getNombre()) , 1, 0, 'C', 1);
        $this->Cell(20, 6, substr($arCiudad->getCodigoInterface(), 0, 2) , 1, 0, 'C', 1);  // bcd
        $this->Cell(22, 6, substr($arCiudad->getCodigoInterface(), 2, 8) , 1, 0, 'C', 1);  // bcd
        //numero de sucursales asociadas
        $this->SetXY(5, 84);
        $this->SetFont('Arial','b',8);
        $this->Cell(178, 6, utf8_decode("36. Número de agencias, sucursales, filiales o subsidios de la empresa retenedora cuyos montos de retención se consolidan:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(22, 6, utf8_decode("") , 1, 0, 'R', 1);
        //Concepto de los ingresos
        $this->SetXY(5, 90);
        $this->SetFont('Arial','b',8);
        $this->Cell(158, 6, utf8_decode("Concepto de los ingresos") , 1, 0, 'C', 1);
        $this->Cell(42, 6, utf8_decode("Valor") , 1, 0, 'C', 1);
        $this->SetXY(5, 96);
        $this->SetFont('Arial','',8);
        $this->Cell(158, 6, utf8_decode("Pagos al empleado (No incluye valores de las casillas 38 a 41)") , 1, 0, 'L', 1);
        $this->Cell(8, 6, utf8_decode("37.") , 1, 0, 'C', 1);
        $this->Cell(34, 6, round(self::$totalPrestacional) , 1, 0, 'R', 1);
        $this->SetXY(5, 102);
        $this->Cell(158, 6, utf8_decode("Cesantías e intereses de cesantías efectivamente pagadas en el periodo") , 1, 0, 'L', 1);
        $this->Cell(8, 6, utf8_decode("38.") , 1, 0, 'C', 1);
        $this->Cell(34, 6, round(self::$totalCesantiaseIntereses) , 1, 0, 'R', 1);
        $this->SetXY(5, 108);
        $this->Cell(158, 6, utf8_decode("Gastos de representación") , 1, 0, 'L', 1);
        $this->Cell(8, 6, utf8_decode("39.") , 1, 0, 'C', 1);
        $this->Cell(34, 6, round(self::$duoGestosRepresentacion) , 1, 0, 'R', 1);
        $this->SetXY(5, 114);
        $this->Cell(158, 6, utf8_decode("Pensiones de jubilación vejez o invalidez") , 1, 0, 'L', 1);
        $this->Cell(8, 6, utf8_decode("40.") , 1, 0, 'C', 1);
        $this->Cell(34, 6, utf8_decode("0") , 1, 0, 'R', 1);
        $this->SetXY(5, 120);
        $this->Cell(158, 6, utf8_decode("Otros ingresos como empleado") , 1, 0, 'L', 1);
        $this->Cell(8, 6, utf8_decode("41.") , 1, 0, 'C', 1);
        $this->Cell(34, 6, round(self::$douOtrosIngresos) , 1, 0, 'R', 1);
        $this->SetXY(5, 126);
        $this->SetFont('Arial','b',8);
        $this->Cell(158, 6, utf8_decode("Total de ingresos brutos (Suma casillas 37 a 41)") , 1, 0, 'L', 1);
        $this->Cell(8, 6, utf8_decode("42.") , 1, 0, 'C', 1);
        $this->Cell(34, 6, round(self::$duoTotalIngresos) , 1, 0, 'R', 1);
        //Concepto a los aportes
        $this->SetXY(5, 132);
        $this->Cell(158, 5, utf8_decode("Concepto de los aportes") , 1, 0, 'C', 1);
        $this->Cell(42, 5, utf8_decode("Valor") , 1, 0, 'C', 1);
        $this->SetXY(5, 137);
        $this->SetFont('Arial','',8);
        $this->Cell(158, 5, utf8_decode("Aportes obligatorios por salud") , 1, 0, 'L', 1);
        $this->Cell(8, 5, utf8_decode("43.") , 1, 0, 'C', 1);
        $this->Cell(34, 5, round(self::$floSalud) , 1, 0, 'R', 1);
        $this->SetXY(5, 142);
        $this->SetFont('Arial','',8);
        $this->Cell(158, 5, utf8_decode("Aportes obligatorios a fondos de pensiones y solidaridad pensional") , 1, 0, 'L', 1);
        $this->Cell(8, 5, utf8_decode("44.") , 1, 0, 'C', 1);
        $this->Cell(34, 5, round(self::$floPension) , 1, 0, 'R', 1);
        $this->SetXY(5, 147);
        $this->SetFont('Arial','',8);
        $this->Cell(158, 5, utf8_decode("Aportes obligatorios a fondos de pensiones y cuentas AFC") , 1, 0, 'L', 1);
        $this->Cell(8, 5, utf8_decode("45.") , 1, 0, 'C', 1);
        $this->Cell(34, 5, round(self::$strAfc) , 1, 0, 'R', 1);
        $this->SetXY(5, 152);
        $this->SetFont('Arial','b',8);
        $this->Cell(158, 5, utf8_decode("Valor de la retención en la fuente por salarios y demás pagos laborados") , 1, 0, 'L', 1);
        $this->Cell(8, 5, utf8_decode("46.") , 1, 0, 'C', 1);
        $this->Cell(34, 5, round(self::$douRetencion) , 1, 0, 'R', 1);
        //firma del retenedor
        $this->SetXY(5, 157);
        $this->Cell(200, 8, utf8_decode("Firma del Retenedor: ") , 1, 0, 'L', 1);
        //datos a cargo del asalariado
        $this->SetXY(5, 165);
        $this->Cell(130, 5, utf8_decode("Datos a cargo del asalariado") , 1, 0, 'C', 1);
        $this->Cell(35, 5, utf8_decode("Valor Recibido") , 1, 0, 'C', 1);
        $this->Cell(35, 5, utf8_decode("Valor Retenido") , 1, 0, 'C', 1);
        $this->SetXY(5, 170);
        $this->SetFont('Arial','',8);
        $this->Cell(130, 5, utf8_decode("Arrendamientos") , 1, 0, 'L', 1);
        $this->Cell(8, 5, utf8_decode("47.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        $this->Cell(8, 5, utf8_decode("54.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        $this->SetXY(5, 175);
        $this->Cell(130, 5, utf8_decode("Honorarios, comisiones y servicios") , 1, 0, 'L', 1);
        $this->Cell(8, 5, utf8_decode("48.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        $this->Cell(8, 5, utf8_decode("55.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        $this->SetXY(5, 180);
        $this->Cell(130, 5, utf8_decode("Intereses y rendimientos financieros") , 1, 0, 'L', 1);
        $this->Cell(8, 5, utf8_decode("49.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        $this->Cell(8, 5, utf8_decode("56.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        $this->SetXY(5, 185);
        $this->Cell(130, 5, utf8_decode("Enajenación de activos fijos") , 1, 0, 'L', 1);
        $this->Cell(8, 5, utf8_decode("50.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        $this->Cell(8, 5, utf8_decode("57.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        $this->SetXY(5, 190);
        $this->Cell(130, 5, utf8_decode("Loterias, rifas, apuestas y similares") , 1, 0, 'L', 1);
        $this->Cell(8, 5, utf8_decode("51.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        $this->Cell(8, 5, utf8_decode("58.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        $this->SetXY(5, 195);
        $this->Cell(130, 5, utf8_decode("Otros") , 1, 0, 'L', 1);
        $this->Cell(8, 5, utf8_decode("52.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        $this->Cell(8, 5, utf8_decode("59.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        $this->SetXY(5, 200);
        $this->SetFont('Arial','b',8);
        $this->Cell(130, 5, utf8_decode("Totales (valor recibido: Suma casillas 47 a 52). (valor retenido: Suma casillas 54 a 59)") , 1, 0, 'L', 1);
        $this->Cell(8, 5, utf8_decode("53.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        $this->Cell(8, 5, utf8_decode("60.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        $this->SetXY(5, 205);
        $this->Cell(165, 5, utf8_decode("Total retenciones año gravable " . self::$strFechaCertificado. " (Suma casillas 46 + 60)") , 1, 0, 'L', 1);
        $this->Cell(8, 5, utf8_decode("61.") , 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode("-") , 1, 0, 'R', 1);
        //identificacion de bienes
        $this->SetXY(5, 210);
        $this->Cell(7, 5, utf8_decode("Item") , 1, 0, 'C', 1);
        $this->Cell(158, 5, utf8_decode("Identificación de los bienes poseidos") , 1, 0, 'C', 1);
        $this->Cell(35, 5, utf8_decode("Valor patrimonial") , 1, 0, 'C', 1);
        $this->SetFont('Arial','',8);
        $this->SetXY(5, 215);
        $this->Cell(7, 4, utf8_decode("1") , 1, 0, 'C', 1);
        $this->Cell(158, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode("") , 1, 0, 'R', 1);
        $this->SetXY(5, 219);
        $this->Cell(7, 4, utf8_decode("2") , 1, 0, 'C', 1);
        $this->Cell(158, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode("") , 1, 0, 'R', 1);
        $this->SetXY(5, 223);
        $this->Cell(7, 4, utf8_decode("3") , 1, 0, 'C', 1);
        $this->Cell(158, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode("") , 1, 0, 'R', 1);
        $this->SetXY(5, 227);
        $this->Cell(7, 4, utf8_decode("4") , 1, 0, 'C', 1);
        $this->Cell(158, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode("") , 1, 0, 'R', 1);
        $this->SetXY(5, 231);
        $this->Cell(7, 4, utf8_decode("5") , 1, 0, 'C', 1);
        $this->Cell(158, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode("") , 1, 0, 'R', 1);
        $this->SetXY(5, 235);
        $this->Cell(7, 4, utf8_decode("6") , 1, 0, 'C', 1);
        $this->Cell(158, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode("") , 1, 0, 'R', 1);
        $this->SetXY(5, 239);
        $this->Cell(7, 4, utf8_decode("7") , 1, 0, 'C', 1);
        $this->Cell(158, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode("") , 1, 0, 'R', 1);
        
        $this->SetXY(5, 243);
        $this->SetFont('Arial','b',8);
        $this->Cell(165, 4, utf8_decode("Deudas vigentes a 31 de Diciembre de ". self::$strFechaCertificado) , 1, 0, 'L', 1);
        $this->Cell(9, 4, utf8_decode("64.") , 1, 0, 'C', 1);
        $this->Cell(26, 4, utf8_decode("-") , 1, 0, 'R', 1);   
        //Identificación de las personas dependientes
        $this->SetXY(5, 247);
        $this->Cell(200, 4, utf8_decode("Identificación de las personas dependientes de acuerdo al páragrafo 2 del articulo 387 del E.T.") , 1, 0, 'C', 1);
        $this->SetXY(5, 251);
        $this->Cell(7, 4, utf8_decode("Item") , 1, 0, 'C', 1);
        $this->Cell(30, 4, utf8_decode("65. C.C. o NIT") , 1, 0, 'C', 1);
        $this->Cell(128, 4, utf8_decode("66. Apellidos y Nombres") , 1, 0, 'C', 1);
        $this->Cell(35, 4, utf8_decode("67. Parentesco") , 1, 0, 'C', 1);
        $this->SetXY(5, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(7, 4, utf8_decode("1") , 1, 0, 'C', 1);
        $this->Cell(30, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(128, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->SetXY(5, 259);
        $this->Cell(7, 4, utf8_decode("2") , 1, 0, 'C', 1);
        $this->Cell(30, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(128, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->SetXY(5, 263);
        $this->Cell(7, 4, utf8_decode("3") , 1, 0, 'C', 1);
        $this->Cell(30, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(128, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->SetXY(5, 267);
        $this->Cell(7, 4, utf8_decode("4") , 1, 0, 'C', 1);
        $this->Cell(30, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(128, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode("") , 1, 0, 'L', 1);
        $this->SetXY(5, 271);
        $this->Cell(160, 3, utf8_decode("Certifico que durante el año gravable ".self::$strFechaCertificado) , 1, 0, 'L', 1);
        $this->SetFont('Arial','b',8);
        $this->Cell(40, 3, utf8_decode("Firma del asalariado") , 1, 0, 'C', 1);
        $this->SetXY(5, 274);
        $this->SetFont('Arial','',7);
        $this->Cell(160, 3, utf8_decode(self::$strCertifico1) , 1, 0, 'L', 1);
        $this->Cell(40, 21, utf8_decode("") , 1, 0, 'L', 1);
        $this->SetXY(5, 277);
        $this->Cell(160, 3, utf8_decode(self::$strCertifico2) , 1, 0, 'L', 1);
        $this->SetXY(5, 280);
        $this->Cell(160, 3, utf8_decode(self::$strCertifico3) , 1, 0, 'L', 1);
        $this->SetXY(5, 283);
        $this->Cell(160, 3, utf8_decode(self::$strCertifico4) , 1, 0, 'L', 1);
        $this->SetXY(5, 286);
        $this->Cell(160, 3, utf8_decode(self::$strCertifico5) , 1, 0, 'L', 1);
        $this->SetXY(5, 289);
        $this->Cell(160, 3, utf8_decode(self::$strCertifico6) , 1, 0, 'L', 1);
        $this->SetXY(5, 292);
        $this->SetFont('Arial','',8);
        $this->Cell(160, 3, utf8_decode("Por lo tanto manifiesto que no estoy obligado a presentar declaracióon de renta y complementarios por el año gravable ". self::$strFechaCertificado) , 1, 0, 'L', 1);
    }

    public function Body($pdf) {
               
    }

    public function Footer() {
        
        
    }    
}

?>
