<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoPermiso extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoPermiso;
    
    public function Generar($miThis, $codigoPermiso) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoPermiso = $codigoPermiso;
        $pdf = new FormatoPermiso();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Permiso$codigoPermiso.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arPermiso = new \Brasa\RecursoHumanoBundle\Entity\RhuPermiso();
        $arPermiso = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPermiso')->find(self::$codigoPermiso);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(15);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 14);
        $this->Image('imagenes/logos/logo.jpg', 12, 15, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("PERMISO ". $arPermiso->getPermisoTipoRel()->getNombre().""), 0, 0, 'C', 1);
        $this->SetXY(53, 22);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 30);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 34);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);
        //FORMATO ISO
        $this->SetXY(168, 22);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(35, 6, "CODIGO: ".$arContenidoFormatoA->getCodigoFormatoIso(), 1, 0, 'L', 1);
        $this->SetXY(168, 28);
        $this->Cell(35, 6, utf8_decode("VERSIÓN: ".$arContenidoFormatoA->getVersion()), 1, 0, 'L', 1);
        $this->SetXY(168, 34);
        $this->Cell(35, 6, utf8_decode("FECHA: ".$arContenidoFormatoA->getFechaVersion()->format('Y-m-d')), 1, 0, 'L', 1);
        //FILA 1
        $this->SetXY(7, 50);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(78, 6, $arPermiso->getCodigoPermisoPk() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(24, 6, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(74, 6, $arPermiso->getFechaPermiso()->format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        //FILA 2
        $this->SetXY(7, 56);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("EMPLEADO:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',7);
        $this->Cell(78, 6, utf8_decode($arPermiso->getEmpleadoRel()->getNombreCorto()) , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(24, 6, utf8_decode("IDENTIFICACIÓN:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(74, 6, $arPermiso->getEmpleadoRel()->getNumeroIdentificacion() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        //FILA 3
        $this->SetXY(7, 62);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("CENTRO COSTO:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',7);
        $centroCosto = "";
        if ($arPermiso->getCodigoCentroCostoFk() != null){
            $centroCosto = $arPermiso->getCentroCostoRel()->getNombre();
        }
        $this->Cell(78, 6, utf8_decode($centroCosto) , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(24, 6, utf8_decode("DEPARTAMENTO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(74, 6, $arPermiso->getDepartamentoEmpresaRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        //FILA 4
        $this->SetXY(7, 68);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("CARGO:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',7);
        $this->Cell(78, 6, utf8_decode($arPermiso->getCargoRel()->getNombre()) , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(24, 6, utf8_decode("TIPO PERMISO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(74, 6, $arPermiso->getPermisoTipoRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        //FILA 5
        $this->SetXY(7, 74);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("HORA SALIDA:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(78, 6, utf8_decode($arPermiso->getHoraSalida()->format('H:i')) , 1, 0, 'L', 1);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("HORA LLEGADA:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(74, 6, utf8_decode($arPermiso->getHoraLlegada()->format('H:i')) , 1, 0, 'L', 1);
        $this->SetFillColor(200, 200, 200);
        //FILA 6
        $this->SetXY(7, 80);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("JEFE AUTORIZA:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',6.5);
        $this->Cell(78, 6, utf8_decode($arPermiso->getJefeAutoriza()) , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(24, 6, utf8_decode("HORAS PERMISO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(74, 6, $arPermiso->getHorasPermiso() , 1, 0, 'L', 1);
        $this->SetFillColor(200, 200, 200);
        //FILA 7
        $this->SetXY(7, 86);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("AFECTA HORARIO:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        if ($arPermiso->getAfectaHorario() == 1){
            $afectaSalario = "SI";
        } else {
            $afectaSalario = "NO";
        }
        $this->Cell(78, 6, $afectaSalario , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(24, 6, utf8_decode("AUTORIZADO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(255, 255, 255);
        if ($arPermiso->getEstadoAutorizado() == 1){
            $autorizado = "SI";
        } else {
            $autorizado = "NO";
        }
        $this->Cell(74, 6, $autorizado , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        //FILA 8
        $this->SetXY(7, 92);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("MOTIVO:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',6.5);
        $this->Cell(176, 6, utf8_decode($arPermiso->getMotivo()) , 1, 0, 'L', 1);
        $this->SetFillColor(200, 200, 200);
        //FILA 9
        $this->SetXY(7, 98);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("OBSERVACIONES:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',6.5);
        $this->Cell(176, 6, utf8_decode($arPermiso->getObservaciones()) , 1, 0, 'L', 1);
        
        $this->SetFillColor(255, 255, 255);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        
            $pdf->Ln(8);
            $pdf->SetFont('Arial', 'B', 7);
           
                    
    }

    public function Footer() {
        
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        
        $arPermiso = new \Brasa\RecursoHumanoBundle\Entity\RhuPermiso();
        $arPermiso = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPermiso')->find(self::$codigoPermiso);
        $arUsers = self::$em->getRepository('BrasaSeguridadBundle:User')->findOneBy(array('username' => $arPermiso->getCodigoUsuario()));
        $this->SetFont('Arial', 'B', 9);
        
        $this->Text(10, 120, "FIRMA: _____________________________________________");
        $this->Text(10, 127, $arPermiso->getEmpleadoRel()->getNombreCorto());
        $this->Text(10, 134, "C.C.:     ______________________ de ____________________");
        $this->Text(105, 120, "FIRMA: _____________________________________________");
        $this->Text(105, 127, $arConfiguracion->getNombreEmpresa());
        $this->Text(105, 134, "NIT: ". $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa());
        $this->Text(105, 140, $arUsers->getNombreCorto());
        $this->SetFont('Arial', '', 8);
        $this->Text(173, 143, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
