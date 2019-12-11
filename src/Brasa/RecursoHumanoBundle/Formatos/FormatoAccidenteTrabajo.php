<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoAccidenteTrabajo extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoAccidenteTrabajo;
    
    public function Generar($miThis, $codigoAccidenteTrabajo) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoAccidenteTrabajo = $codigoAccidenteTrabajo;
        $pdf = new FormatoAccidenteTrabajo();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("AccidenteTrabajo$codigoAccidenteTrabajo.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);                        
        $this->EncabezadoDetalles();
    }
    
    public function EncabezadoDetalles() {
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(13);
        $this->SetFillColor(272, 272, 272);        
        $this->SetFont('Arial','b',8);
        $this->SetXY(10, 5);
        $this->Line(10, 5, 60, 5);
        $this->Line(10, 5, 10, 26);
        $this->Line(10, 26, 60, 26);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        $this->SetXY(50, 5);
        $this->SetFont('Arial','b',12);
        $this->Cell(100, 21, "INCIDENTES Y ACCIDENTES DE TRABAJO" , 1, 0, 'C', 1);
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
    }

    public function Body($pdf) {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arAccidenteTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo();
        $arAccidenteTrabajo = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuAccidenteTrabajo')->find(self::$codigoAccidenteTrabajo);
        $pdf->Ln(8);        
        $pdf->SetLineWidth(.2);
        //$this->SetFont('', 'B', 8);        
        $intX = 7;
        $intY = 30;
        $pdf->SetXY($intX, $intY);
        $pdf->SetFont('Arial','b',8);
        $pdf->Cell(40, 5, "TIPO DE EVENTO: ", 1, 0, 'C', 0);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(40, 5, utf8_decode($arAccidenteTrabajo->getTipoAccidenteRel()->getNombre()), 1, 0, 'C', 0);
        $pdf->SetFont('Arial','b',8);
        $pdf->Cell(80, 5, utf8_decode("FECHA EN QUE SE ENVÍA LA INVESTIGACIÓN:"), 1, 0, 'C', 0);
        $pdf->SetFont('Arial','',8);
        $fechaEnviaInvestigacion = "";
        if ($arAccidenteTrabajo->getFechaEnviaInvestigacion() != null){
            $fechaEnviaInvestigacion = $arAccidenteTrabajo->getFechaEnviaInvestigacion()->format('Y-m-d');
        }
        $pdf->Cell(38, 5, $fechaEnviaInvestigacion, 1, 0, 'C', 0);
        $pdf->SetXY($intX, $intY+5);
        $pdf->SetFont('Arial','b',8);
        $pdf->Cell(198, 5, utf8_decode("INFORMACIÓN GENERAL DE LA EMPRESA"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+10);
        $pdf->Cell(198, 5, utf8_decode("RAZÓN SOCIAL O NIT:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+15);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(198, 5, utf8_decode($arConfiguracion->getNombreEmpresa(). "   -   " . $arConfiguracion->getNitEmpresa()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+20);
        $pdf->SetFont('Arial','b',8);
        $pdf->Cell(130, 5, utf8_decode("DIRECCIÓN:"), 1, 0, 'L', 0);
        $pdf->Cell(68, 5, utf8_decode("TELÉFONO:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+25);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(130, 5, utf8_decode($arConfiguracion->getDireccionEmpresa()), 1, 0, 'L', 0);
        $pdf->Cell(68, 5, utf8_decode($arConfiguracion->getTelefonoEmpresa()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+30);
        $pdf->SetFont('Arial','b',8);
        $pdf->Cell(100, 5, utf8_decode("COORDINADOR DELEGADO:"), 1, 0, 'L', 0);
        $pdf->Cell(98, 5, utf8_decode("CARGO:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+35);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(100, 5, utf8_decode($arAccidenteTrabajo->getCoordinadorEncargado()), 1, 0, 'L', 0);
        $pdf->Cell(98, 5, utf8_decode($arAccidenteTrabajo->getCargoCoordinadorEncargado()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+40);
        $pdf->SetFont('Arial','b',8);
        $pdf->Cell(198, 5, utf8_decode("INFORMACIÓN GENERAL DEL TRABAJADOR"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+45);
        $pdf->SetFont('Arial','b',8);
        $pdf->Cell(198, 5, utf8_decode("NOMBRES Y APELLIDOS:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+50);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(198, 5, utf8_decode($arAccidenteTrabajo->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+55);
        $pdf->SetFont('Arial','B',8);
        //Calculo edad
        $varFechaNacimientoAnio = $arAccidenteTrabajo->getEmpleadoRel()->getFechaNacimiento()->format('Y');
        $varFechaNacimientoMes = $arAccidenteTrabajo->getEmpleadoRel()->getFechaNacimiento()->format('m');
        $varMesActual = date('m');
        if ($varMesActual >= $varFechaNacimientoMes){
            $varEdad = date('Y') - $varFechaNacimientoAnio;
        } else {
            $varEdad = date('Y') - $varFechaNacimientoAnio -1;
        }
        //Fin calculo edad
        $pdf->Cell(100, 5, utf8_decode("EDAD: ". $varEdad .""), 1, 0, 'L', 0);
        $pdf->Cell(98, 5, utf8_decode("N° IDENTIFICACIÓN: ". $arAccidenteTrabajo->getEmpleadoRel()->getNumeroIdentificacion() .""), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+60);
        $pdf->Cell(66, 5, utf8_decode("TIEMPO DE SERVICIO:"), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode("OFICIO HABITUAL:"), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode("AREA O SECCIÓN:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+65);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getTiempoServicioEmpleado()), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getOficioHabitual()), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getEmpleadoRel()->getDepartamentoEmpresaRel()->getNombre()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+70);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(198, 5, utf8_decode("EL ACCIDENTE OCURRIÓ REALIZANDO SU OFICIO HABITUAL: "), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+75);
        $pdf->SetFont('Arial','',8);
        if ($arAccidenteTrabajo->getAccidenteOcurrioLugarHabitual() == 0){
            $accidenteOcurrio = "NO";
        } else {
            $accidenteOcurrio = "SI";
        }
        $pdf->Cell(198, 5, utf8_decode($accidenteOcurrio), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+80);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(198, 5, utf8_decode("INFORMACIÓN GENERAL DEL ACCIDENTE"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+85);
        $pdf->Cell(80, 5, utf8_decode("CIUDAD ACCIDENTE:"), 1, 0, 'L', 0);
        $pdf->Cell(59, 5, utf8_decode("RADICADO FURAT:"), 1, 0, 'L', 0);
        $pdf->Cell(59, 5, utf8_decode("FECHA:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+90);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(80, 5, utf8_decode($arAccidenteTrabajo->getCiudadRel()->getNombre()), 1, 0, 'L', 0);
        $pdf->Cell(59, 5, utf8_decode($arAccidenteTrabajo->getCodigoFurat()), 1, 0, 'L', 0);
        $fechaAccidente = "";
        if ($arAccidenteTrabajo->getFechaAccidente() != null){
            $fechaAccidente = $arAccidenteTrabajo->getFechaAccidente()->format('Y-m-d');
        }
        $pdf->Cell(59, 5, $arAccidenteTrabajo->getFechaAccidente()->format('Y-m-d'), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+95);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(100, 5, utf8_decode("ARL:"), 1, 0, 'L', 0);
        $pdf->Cell(98, 5, utf8_decode("TAREA DESARROLLA AL MOMENTO DEL ACCIDENTE:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+100);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(100, 5, utf8_decode($arAccidenteTrabajo->getEntidadRiesgoProfesionalRel()->getNombre()), 1, 0, 'L', 0);
        $pdf->Cell(98, 5, utf8_decode($arAccidenteTrabajo->getTareaDesarrolladaMomentoAccidente()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+105);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(66, 5, utf8_decode("INCAPACIDAD DESDE:"), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode("INCAPACIDAD HASTA:"), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode("DÍAS:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+110);
        $pdf->SetFont('Arial','',8);
        $fechaIncacidadDesde = "";
        if ($arAccidenteTrabajo->getFechaIncapacidadDesde() != null){
            $fechaIncacidadDesde = $arAccidenteTrabajo->getFechaIncapacidadDesde()->format('Y-m-d');
        }
        $fechaIncacidadHasta = "";
        if ($arAccidenteTrabajo->getFechaIncapacidadHasta() != null){
            $fechaIncacidadHasta = $arAccidenteTrabajo->getFechaIncapacidadHasta()->format('Y-m-d');
        }
        $pdf->Cell(66, 5, $fechaIncacidadDesde, 1, 0, 'L', 0);
        $pdf->Cell(66, 5, $fechaIncacidadHasta, 1, 0, 'L', 0);
        $pdf->Cell(66, 5, $arAccidenteTrabajo->getDias(), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+115);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(198, 5, utf8_decode("ANALISIS DEL ACCIDENTE O INCIDENTE"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+120);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(66, 5, utf8_decode("CIE10:"), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode("DIAGNÓSTICO:"), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode("NATURALEZA DE LA LESIÓN:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+125);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getCie10()), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getDiagnostico()), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getNaturalezaLesion()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+130);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(66, 5, utf8_decode("PARTE DEL CUERPO AFECTADO:"), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode("AGENTE:"), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode("MECANISMO DEL ACCIDENTE:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+135);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getCuerpoAfectado()), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getAgente()), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getMecanismoAccidente()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+140);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(66, 5, utf8_decode("LUGAR DEL ACCIDENTE:"), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode("DESCRIPCIÓN ACCIDENTE:"), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode("ACTO INSEGURO:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+145);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getLugarAccidente()), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getDescripcionAccidente()), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getActoInseguro()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+150);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(66, 5, utf8_decode("CONDICIÓN INSEGURA:"), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode("FACTOR PERSONAL:"), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode("FACTOR TRABAJO:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+155);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getCondicionInsegura()), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getFactorPersonal()), 1, 0, 'L', 0);
        $pdf->Cell(66, 5, utf8_decode($arAccidenteTrabajo->getFactorTrabajo()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+160);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(198, 5, utf8_decode("LISTA PRIORIZADA DE ACCIONES DE INTERVENCIÓN"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+165);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(70, 5, utf8_decode("PLAN DE ACCIÓN:"), 1, 0, 'L', 0);
        $pdf->Cell(30, 5, utf8_decode("TIPO CONTROL:"), 1, 0, 'L', 0);
        $pdf->Cell(35, 5, utf8_decode("FECHA VERIFICACIÓN:"), 1, 0, 'L', 0);
        $pdf->Cell(63, 5, utf8_decode("AREA RESPONSABLE:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+170);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(70, 5, utf8_decode($arAccidenteTrabajo->getPlanAccion1()), 1, 0, 'L', 0);
        $pdf->Cell(30, 5, utf8_decode($arAccidenteTrabajo->getTipoControlUnoRel()->getNombre()), 1, 0, 'L', 0);
        $fechaVerificacion1 = "";
        if ($arAccidenteTrabajo->getFechaVerificacion1() != null){
            $fechaVerificacion1 = $arAccidenteTrabajo->getFechaVerificacion1()->format('Y-m-d');
        }
        $pdf->Cell(35, 5, utf8_decode($fechaVerificacion1), 1, 0, 'L', 0);
        $pdf->Cell(63, 5, utf8_decode($arAccidenteTrabajo->getAreaResponsable1()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+175);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(70, 5, utf8_decode("PLAN DE ACCIÓN:"), 1, 0, 'L', 0);
        $pdf->Cell(30, 5, utf8_decode("TIPO CONTROL:"), 1, 0, 'L', 0);
        $pdf->Cell(35, 5, utf8_decode("FECHA VERIFICACIÓN:"), 1, 0, 'L', 0);
        $pdf->Cell(63, 5, utf8_decode("AREA RESPONSABLE:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+180);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(70, 5, utf8_decode($arAccidenteTrabajo->getPlanAccion2()), 1, 0, 'L', 0);
        $pdf->Cell(30, 5, utf8_decode($arAccidenteTrabajo->getTipoControlDosRel()->getNombre()), 1, 0, 'L', 0);
        $fechaVerificacion2 = "";
        if ($arAccidenteTrabajo->getFechaVerificacion2() != null){
            $fechaVerificacion2 = $arAccidenteTrabajo->getFechaVerificacion2()->format('Y-m-d');
        }
        $pdf->Cell(35, 5, utf8_decode($fechaVerificacion2), 1, 0, 'L', 0);
        $pdf->Cell(63, 5, utf8_decode($arAccidenteTrabajo->getAreaResponsable2()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+185);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(70, 5, utf8_decode("PLAN DE ACCIÓN:"), 1, 0, 'L', 0);
        $pdf->Cell(30, 5, utf8_decode("TIPO CONTROL:"), 1, 0, 'L', 0);
        $pdf->Cell(35, 5, utf8_decode("FECHA VERIFICACIÓN:"), 1, 0, 'L', 0);
        $pdf->Cell(63, 5, utf8_decode("AREA RESPONSABLE:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+190);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(70, 5, utf8_decode($arAccidenteTrabajo->getPlanAccion3()), 1, 0, 'L', 0);
        $pdf->Cell(30, 5, utf8_decode($arAccidenteTrabajo->getTipoControlTresRel()->getNombre()), 1, 0, 'L', 0);
        $fechaVerificacion3 = "";
        if ($arAccidenteTrabajo->getFechaVerificacion3() != null){
            $fechaVerificacion3 = $arAccidenteTrabajo->getFechaVerificacion3()->format('Y-m-d');
        }
        $pdf->Cell(35, 5, utf8_decode($fechaVerificacion3), 1, 0, 'L', 0);
        $pdf->Cell(63, 5, utf8_decode($arAccidenteTrabajo->getAreaResponsable3()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+195);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(198, 5, utf8_decode("PARTICIPANTES DE LA INVESTIGACIÓN"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+200);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(100, 5, utf8_decode("PARTICIPANTE DE LA INVESTIGACIÓN:"), 1, 0, 'L', 0);
        $pdf->Cell(98, 5, utf8_decode("CARGO:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+205);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(100, 5, utf8_decode($arAccidenteTrabajo->getParticipanteInvestigacion1()), 1, 0, 'L', 0);
        $pdf->Cell(98, 5, utf8_decode($arAccidenteTrabajo->getCargoParticipanteInvestigacion1()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+210);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(100, 5, utf8_decode("PARTICIPANTE DE LA INVESTIGACIÓN:"), 1, 0, 'L', 0);
        $pdf->Cell(98, 5, utf8_decode("CARGO:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+215);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(100, 5, utf8_decode($arAccidenteTrabajo->getParticipanteInvestigacion2()), 1, 0, 'L', 0);
        $pdf->Cell(98, 5, utf8_decode($arAccidenteTrabajo->getCargoParticipanteInvestigacion2()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+220);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(100, 5, utf8_decode("PARTICIPANTE DE LA INVESTIGACIÓN:"), 1, 0, 'L', 0);
        $pdf->Cell(98, 5, utf8_decode("CARGO:"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+225);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(100, 5, utf8_decode($arAccidenteTrabajo->getParticipanteInvestigacion3()), 1, 0, 'L', 0);
        $pdf->Cell(98, 5, utf8_decode($arAccidenteTrabajo->getCargoParticipanteInvestigacion3()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+230);
        $pdf->SetFont('Arial','b',8);
        $pdf->Cell(80, 5, utf8_decode("REPRESENTANTE LEGAL"), 1, 0, 'L', 0);
        $pdf->Cell(80, 5, utf8_decode("CARGO"), 1, 0, 'L', 0);
        $pdf->Cell(38, 5, utf8_decode("LICENCIA"), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+235);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(80, 5, utf8_decode($arAccidenteTrabajo->getRepresentanteLegal()), 1, 0, 'L', 0);
        $pdf->Cell(80, 5, utf8_decode($arAccidenteTrabajo->getCargoRepresentanteLegal()), 1, 0, 'L', 0);
        $pdf->Cell(38, 5, utf8_decode($arAccidenteTrabajo->getLicencia()), 1, 0, 'L', 0);
        $pdf->SetXY($intX, $intY+240);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(118, 5, utf8_decode("RESPONSABLE: ". $arAccidenteTrabajo->getResponsableVerificacion() .""), 1, 0, 'L', 0);
        $fechaVerificacion = "";
        if ($arAccidenteTrabajo->getFechaVerificacion() != null){
           $fechaVerificacion = $arAccidenteTrabajo->getFechaVerificacion()->format('Y-m-d'); 
        }
        $pdf->Cell(80, 5, utf8_decode("FECHA VERIFICACIÓN: ". $fechaVerificacion .""), 1, 0, 'L', 0);
    }

    public function Footer() {
               
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arAccidenteTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo();
        $arAccidenteTrabajo = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuAccidenteTrabajo')->find(self::$codigoAccidenteTrabajo);
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arAccidenteTrabajo->getCodigoEmpleadoFk());
        $this->SetFont('Arial', 'B', 9);
        $this->Text(10, 292, "FIRMA: ___________________________________________ C.C.: ____________________ de ___________________");
        $this->SetFont('Arial', '', 8);
        $this->Text(185, 292, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
