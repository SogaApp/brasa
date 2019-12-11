<?php
namespace Brasa\AfiliacionBundle\Formatos;
class Empleado extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoEmpleado;
    
    public function Generar($miThis, $codigoEmpleado) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoEmpleado = $codigoEmpleado;
        $pdf = new Empleado();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Empleado$codigoEmpleado.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("HOJA DE VIDA EMPLEADO"), 0, 0, 'C', 1);
        $this->SetXY(53, 18);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);        
        
        $arEmpleado = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
        $arEmpleado = self::$em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find(self::$codigoEmpleado);        
        if ($arEmpleado->getCodigoContratoActivo() == null){
            $codigoContratoActivo = 0;
        } else {
            $codigoContratoActivo = $arEmpleado->getCodigoContratoActivo();
        }
        $arContrato = new \Brasa\AfiliacionBundle\Entity\AfiContrato();
        $arContrato = self::$em->getRepository('BrasaAfiliacionBundle:AfiContrato')->findOneBy(array('codigoContratoPk' => $codigoContratoActivo, 'indefinido' => 1));        
        if ($arContrato == null) {
            $mensaje = "EL EMPLEADO NO TIENE CONTRATO";
            $this->SetXY(10, 40);
            $this->SetFont('Arial','B',8);
            $this->Cell(197, 5, utf8_decode($mensaje) , 0, 0, 'C', 1);
        } else {
            $this->SetFillColor(236, 236, 236);        
            $this->SetFont('Arial','B',10);
            $intY = 40;
            //linea cliente
            $this->SetFillColor(200, 200, 200); 
            $this->SetXY(10, $intY);
            $this->SetFont('Arial','B',8);
            $this->Cell(197, 5, utf8_decode("CLIENTE:") , 1, 0, 'L', 1);
            //linea 1
            $this->SetFillColor(272, 272, 272); 
            $this->SetXY(10, $intY+5);
            $this->SetFont('Arial','B',7);
            $this->Cell(20, 5, utf8_decode("CODIGO:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $this->Cell(49, 5, $arEmpleado->getClienteRel()->getCodigoClientePk(), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(19, 5, utf8_decode("NIT:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $this->Cell(47, 5, $arEmpleado->getClienteRel()->getNit(), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17, 5, utf8_decode("DV:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $this->Cell(45, 5, $arEmpleado->getClienteRel()->getDigitoVerificacion(), 1, 0, 'L', 1);
            //linea 2
            $this->SetXY(10, $intY+10);
            $this->SetFont('Arial','B',7);
            $this->Cell(20, 5, utf8_decode("NOMBRE:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',6.7);
            $this->Cell(49, 5, $arEmpleado->getClienteRel()->getNombreCorto(), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(19, 5, utf8_decode("ASESOR:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',6.4);
            $asesor = "";
            if ($arEmpleado->getClienteRel()->getCodigoAsesorFk() != null){
                $asesor = $arEmpleado->getClienteRel()->getAsesorRel()->getNombre();
            }
            $this->Cell(47, 5, utf8_decode($asesor), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17, 5, 'EMAIL', 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $this->Cell(45, 5, $arEmpleado->getClienteRel()->getEmail(), 1, 0, 'L', 1);
            //linea 3
            $this->SetXY(10, $intY+15);
            $this->SetFont('Arial','B',7);
            $this->Cell(20, 5, utf8_decode("TELEFONO:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $this->Cell(49, 5, $arEmpleado->getClienteRel()->getTelefono(), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(19, 5, utf8_decode("CELULAR:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $this->Cell(47, 5, $arEmpleado->getClienteRel()->getCelular(), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17, 5, 'F. PAGO', 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $formaPago = "";
            if ($arEmpleado->getClienteRel()->getCodigoFormaPagoFk() != null){
                $formaPago = $arEmpleado->getClienteRel()->getFormaPagoRel()->getNombre();
            }
            $this->Cell(45, 5, $formaPago, 1, 0, 'L', 1);
            //linea 4
            $this->SetXY(10, $intY+20);
            $this->SetFont('Arial','B',7);
            $this->Cell(20, 5, "" , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $this->Cell(49, 5, '', 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(19, 5, '', 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $this->Cell(47, 5, '', 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17, 5, "AFILIACION:" , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $this->Cell(45, 5, number_format($arEmpleado->getClienteRel()->getAfiliacion(), 2, '.', ','), 1, 0, 'R', 1);
            //linea empleado
            $this->SetFillColor(200, 200, 200); 
            $this->SetXY(10, $intY+25);
            $this->SetFont('Arial','B',8);
            $this->Cell(197, 5, utf8_decode("EMPLEADO:") , 1, 0, 'L', 1);
            //linea 5
            $this->SetFillColor(272, 272, 272); 
            $this->SetXY(10, $intY+30);
            $this->SetFont('Arial','B',7);
            $this->Cell(20, 5, utf8_decode("CODIGO:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $this->Cell(49, 5, $arEmpleado->getCodigoEmpleadoPk(), 1, 0, 'L', 1);
            /*$this->Cell(20, 5, utf8_decode("CIUDAD:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $ciudad = "";
            if ($arEmpleado->getCodigoCiudadFk() != null){
                $ciudad = $arEmpleado->getCiudadRel()->getNombre();
            }
            $this->Cell(49, 5, $ciudad, 1, 0, 'L', 1);*/
            $this->SetFont('Arial','B',6.1);
            $this->Cell(19, 5, utf8_decode("IDENTIFICACION:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $this->Cell(47, 5, $arEmpleado->getNumeroIdentificacion(), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17, 5, 'TIPO ID:' , 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $tipoIdentificacion = "";
            if ($arEmpleado->getCodigoTipoIdentificacionFk() != null){
                $tipoIdentificacion = $arEmpleado->getTipoIdentificacionRel()->getNombre();
            }
            $this->Cell(45, 5, utf8_decode($tipoIdentificacion), 1, 0, 'L', 1);
            //linea 6
            $this->SetXY(10, $intY+35);
            $this->SetFont('Arial','B',7);
            $this->Cell(20, 5, utf8_decode("EMPLEADO:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',6.7);
            $this->Cell(49, 5, $arEmpleado->getNombreCorto(), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(19, 5, 'CIUDAD:', 1, 0, 'L', 1);
            $this->SetFont('Arial','',6);
            $ciudad = "";
            if ($arEmpleado->getCodigoCiudadFk() != null){
                $ciudad = $arEmpleado->getCiudadRel()->getNombre();
            }
            $this->Cell(47, 5, $ciudad, 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17, 5, utf8_decode("DIRECCION:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $this->Cell(45, 5, $arEmpleado->getDireccion(), 1, 0, 'L', 1);
            //linea 7
            $this->SetXY(10, $intY+40);
            $this->SetFont('Arial','B',7);
            $this->Cell(20, 5, utf8_decode("BARRRIO:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $this->Cell(49, 5, $arEmpleado->getBarrio(), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',6);
            $this->Cell(19, 5, 'TELEFONO:', 1, 0, 'L', 1);
            $this->Cell(47, 5, $arEmpleado->getBarrio(), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17, 5, utf8_decode("CELULAR") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $this->Cell(45, 5, $arEmpleado->getCelular(), 1, 0, 'L', 1);
            //linea 8
            $this->SetXY(10, $intY+45);
            $this->SetFont('Arial','B',7);
            $this->Cell(20, 5, utf8_decode("EMAIL:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $this->Cell(49, 5, $arEmpleado->getCorreo(), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(19, 5, 'RH:', 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $rh = "";
            if ($arEmpleado->getCodigoRhPk() != null){
                $rh = $arEmpleado->getRhRel()->getTipo();
            }
            $this->Cell(47, 5, $rh, 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17, 5, "ESTADO C:" , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $estadoCivil = "";
            if ($arEmpleado->getCodigoEstadoCivilFk() != null){
                $estadoCivil = $arEmpleado->getEstadoCivilRel()->getNombre();
            }
            $this->Cell(45, 5, $estadoCivil, 1, 0, 'L', 1);
            //linea 9
            $this->SetXY(10, $intY+50);
            $this->SetFont('Arial','B',7);
            $this->Cell(20, 5, utf8_decode("FECHA. NAC:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $fechaNacimiento = "";
            if ($arEmpleado->getFechaNacimiento() != null){
                $fechaNacimiento = $arEmpleado->getFechaNacimiento()->format('Y-m-d');
            }
            $this->Cell(49, 5, $fechaNacimiento, 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(19, 5, 'SEXO:', 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            if ($arEmpleado->getCodigoSexoFk() == "M"){
                $sexo = "MASCULINO";
            } else {
                $sexo = "FEMENINO";
            }
            $this->Cell(47, 5, $sexo, 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17, 5, "" , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $this->Cell(45, 5, "", 1, 0, 'L', 1);
            //linea contrato
            $this->SetFillColor(200, 200, 200); 
            $this->SetXY(10, $intY+55);
            $this->SetFont('Arial','B',8);
            $this->Cell(197, 5, utf8_decode("CONTRATO:") , 1, 0, 'L', 1);
            //linea 10
            $this->SetFillColor(272, 272, 272);
            $this->SetXY(10, $intY+60);
            $this->SetFont('Arial','B',7);
            $this->Cell(20, 5, utf8_decode("CARGO:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',6);
            $cargo = "";
            if ($arContrato->getCodigoCargoFk() != null){
                $cargo = $arContrato->getCargoRel()->getNombre();
            }
            $this->Cell(49, 5, $cargo, 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(19, 5, 'F. DESDE:', 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $this->Cell(47, 5, $arContrato->getFechaDesde()->format('Y-m-d'), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17, 5, "F. HASTA:" , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $this->Cell(45, 5, $arContrato->getFechaHasta()->format('Y-m-d'), 1, 0, 'L', 1);
            //linea 11
            $this->SetXY(10, $intY+65);
            $this->SetFont('Arial','B',7);
            $this->Cell(20, 5, "INDEFINIDO" , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            if ($arContrato->getIndefinido() == 1){
                $indefinido = "SI";
            } else {
                $indefinido = "NO";
            }
            $this->Cell(49, 5, $indefinido, 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(19, 5, 'ACTIVO:', 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            if ($arContrato->getEstadoActivo() == 1){
                $activo = "SI";
            } else {
                $activo = "NO";
            }
            $this->Cell(47, 5, $activo, 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17, 5, "SALARIO:" , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $this->Cell(45, 5, number_format($arContrato->getVrSalario(), 2, '.', ','), 1, 0, 'R', 1);
            //linea 12
            $this->SetXY(10, $intY+70);
            $this->SetFont('Arial','B',7);
            $this->Cell(20, 5, utf8_decode("T. COTIZANTE:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',8);
            $tipoCotizante = "";
            if ($arContrato->getCodigoTipoCotizanteFk() != null){
                $tipoCotizante = $arContrato->getSsoTipoCotizanteRel()->getNombre();
            } 
            $this->Cell(49, 5, $tipoCotizante, 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(19, 5, 'SUCURSAL:', 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $sucursal = "";
            if ($arContrato->getCodigoSucursalFk() != null){
                $sucursal = $arContrato->getSucursalRel()->getNombre();
            }
            $this->Cell(47, 5, $sucursal, 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17, 5, '' , 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $this->Cell(45, 5, '', 1, 0, 'L', 1);
            //linea 13
            $this->SetXY(10, $intY+75);
            $this->SetFont('Arial','B',7);
            $this->Cell(20, 5, utf8_decode("PENSION:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $pension = "";
            if ($arContrato->getCodigoEntidadPensionFk() != null){
                $pension = $arContrato->getEntidadPensionRel()->getNombre();
            }
            $this->Cell(49, 5, utf8_decode($pension), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(19, 5, 'SALUD', 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $salud = "";
            if ($arContrato->getCodigoEntidadSaludFk() != null){
                $salud = $arContrato->getEntidadSaludRel()->getNombre();
            }
            $this->Cell(47, 5, utf8_decode($salud), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17, 5, 'ARL' , 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $arl = "";
            if ($arContrato->getCodigoClasificacionRiesgoFk() != null){
                $arl = $arContrato->getClasificacionRiesgoRel()->getNombre();
            }
            $this->Cell(45, 5, utf8_decode($arl), 1, 0, 'L', 1);
            //linea 14
            $this->SetXY(10, $intY+80);
            $this->SetFont('Arial','B',7);
            $this->Cell(20, 5, utf8_decode("CAJA:") , 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $caja = "";
            if ($arContrato->getCodigoEntidadCajaFk() != null){
                $caja = $arContrato->getEntidadCajaRel()->getNombre();
            }
            $this->Cell(49, 5, utf8_decode($caja), 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(19, 5, '', 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);

            $this->Cell(47, 5, "", 1, 0, 'L', 1);
            $this->SetFont('Arial','B',7);
            $this->Cell(17, 5, '' , 1, 0, 'L', 1);
            $this->SetFont('Arial','',7);
            $this->Cell(45, 5, "", 1, 0, 'L', 1);
            //linea 15
            $this->SetXY(10, $intY+85);
            $this->SetFont('Arial','B',8);
            $this->Cell(197, 5, utf8_decode("COMENTARIOS:").' '.$arEmpleado->getComentarios() , 1, 0, 'L', 1); 
            //linea usuario
            $this->SetXY(10, $intY+90);
            $this->SetFont('Arial','B',8);
            $this->Cell(197, 5, utf8_decode("Usuario sistema: ").' '.$arEmpleado->getCodigoUsuario() , 0, 0, 'L', 1); 
            
            $this->EncabezadoDetalles();
        }
    }

    public function EncabezadoDetalles() {
        /*$this->Ln(14);
        $header = array('COD', 'CURSO', 'PRECIO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7.5);

        //creamos la cabecera de la tabla.
        $w = array(11, 167, 15);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);*/
    }

    public function Body($pdf) {
        /*$arEmpleadoDetalles = new \Brasa\AfiliacionBundle\Entity\AfiEmpleadoDetalle();
        $arEmpleadoDetalles = self::$em->getRepository('BrasaAfiliacionBundle:AfiEmpleadoDetalle')->findBy(array('codigoEmpleadoFk' => self::$codigoEmpleado));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arEmpleadoDetalles as $arEmpleadoDetalle) {            
            $pdf->Cell(11, 4, $arEmpleadoDetalle->getCodigoEmpleadoDetallePk(), 1, 0, 'L');
            $pdf->Cell(167, 4, $arEmpleadoDetalle->getEmpleadoTipoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(15, 4, number_format($arEmpleadoDetalle->getPrecio(), 2, '.', ','), 1, 0, 'R');                                             
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }*/
    }

    public function Footer() {
        $this->SetFont('Arial', 'B', 9);
        
        /*/*$this->Text(10, 240, "FIRMA: _____________________________________________");
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');*/
    }    
}

?>
