<?php
namespace Brasa\RecursoHumanoBundle\Formatos;

class FormatoDesempenos extends \FPDF_FPDF {
    public static $em;

    public static $codigoDesempeno;

    public function Generar($miThis, $codigoDesempeno) {
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoDesempeno = $codigoDesempeno;
        $pdf = new FormatoDesempenos();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Desempeno.pdf", 'D');
    }

    public function Header() {

        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',10);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(14);
        $this->SetFillColor(200, 200, 200);
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
        $this->Cell(90, 20, utf8_decode("GESTIÓN DEL DESEMPEÑO"), 1, 0, 'C', 1); //cuardo mitad medio
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(60, 40);
        $this->Cell(90, 10, utf8_decode(" "), 1, 0, 'C', 1); //cuardo mitad abajo
        $this->SetXY(150, 10);
        $this->Cell(53, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuadro derecho arriba
        $this->SetXY(150, 20);
        $this->Cell(53, 20, utf8_decode($arContenidoFormatoA->getCodigoFormatoIso()), 1, 0, 'C', 1); //cuadro derecho mitad 1
        $this->SetXY(150, 40);
        $this->Cell(53, 5, utf8_decode($arContenidoFormatoA->getVersion()), 1, 0, 'C', 1); //cuadro derecho abajo 1
        $this->SetXY(150, 45);
        $this->Cell(53, 5, $arContenidoFormatoA->getFechaVersion()->format('Y-m-d'), 1, 0, 'C', 1); //cuadro derecho abajo 2

        //Restauracion de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {

        $arDesempeno = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno();
        $arDesempeno = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDesempeno')->find(self::$codigoDesempeno);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetXY(10, 55);
        //titulo
        $pdf->Cell(193, 5, utf8_decode("EVALUACIÓN DEL COLABORADOR"), 1, 0, 'C',1);
        //linea 1
        $pdf->SetXY(10, 60);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(39, 5, utf8_decode("CÓDIGO:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(57, 5, $arDesempeno->getCodigoDesempenoPk(), 1, 0, 'L',1);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(46, 5, utf8_decode("FECHA:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(51, 5, $arDesempeno->getFecha()->format('Y-m-d'), 1, 0, 'L',1);
        //linea 2
        $pdf->SetXY(10, 65);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(39, 5, utf8_decode("CÓDIGO EMPLEADO:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(57, 5, $arDesempeno->getCodigoEmpleadoFk(), 1, 0, 'L',1);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(46, 5, utf8_decode("IDENTIFICACIÓN:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(51, 5, $arDesempeno->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L',1);
        //linea 3
        $pdf->SetXY(10, 70);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(39, 5, utf8_decode("EMPLEADO:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.1);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(57, 5, $arDesempeno->getEmpleadoRel()->getNombreCorto(), 1, 0, 'L',1);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(46, 5, utf8_decode("DEPENDECIA DEL EVALUADO:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(51, 5, $arDesempeno->getDependenciaEvaluado(), 1, 0, 'L',1);
        //linea 4
        $pdf->SetXY(10, 75);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(39, 5, utf8_decode("CARGO:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(57, 5, $arDesempeno->getCargoRel()->getNombre(), 1, 0, 'L',1);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(46, 5, utf8_decode("JEFE QUE EVALUA:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.1);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(51, 5, $arDesempeno->getJefeEvalua(), 1, 0, 'L',1);
        //linea 6
        $pdf->SetXY(10, 80);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(39, 5, utf8_decode("CARGO JEFE QUE EVALUA:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.1);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(57, 5, $arDesempeno->getCargoJefeEvalua(), 1, 0, 'L',1);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(46, 5, utf8_decode("DEPENDENCIA DEL QUE EVALUA:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(51, 5, $arDesempeno->getDependenciaEvalua(), 1, 0, 'L',1);
        //linea 7
        $pdf->SetXY(10, 85);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(39, 5, utf8_decode("ESTADO AUTORIZADO:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        if ($arDesempeno->getEstadoAutorizado() == 1){
            $estadoAutorizado = "SI";
        }else{
            $estadoAutorizado = "NO";
        }
        $pdf->Cell(57, 5, $estadoAutorizado, 1, 0, 'L',1);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(46, 5, utf8_decode("ESTADO CERRADO:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        if ($arDesempeno->getEstadoCerrado() == 1){
            $estadoCerrado = "SI";
        }else{
            $estadoCerrado = "NO";
        }
        $pdf->Cell(51, 5, $estadoCerrado, 1, 0, 'L',1);
        
        //area profesional
        $pdf->SetXY(10, 95);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(193, 5, utf8_decode("ÁREA PROFESIONAL"), 1, 0, 'C',1);
        $pdf->SetXY(10, 100);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(142, 10, utf8_decode("ASPECTOS A EVALUAR"), 1, 0, 'C',1);
        $pdf->SetFont('Arial','B',7.1);
        $pdf->Cell(11, 10, utf8_decode("Siempre"), 1, 0, 'C',1);
        $pdf->Cell(11, 5, utf8_decode("Casi"), 1, 0, 'C',1);
        $pdf->SetXY(163, 105);
        $pdf->Cell(11, 5, utf8_decode("Siempre"), 1, 0, 'C',1);
        $pdf->SetXY(174, 100);
        $pdf->Cell(11, 5, utf8_decode("Algunas"), 1, 0, 'C',1);
        $pdf->SetXY(174, 105);
        $pdf->Cell(11, 5, utf8_decode("Veces"), 1, 0, 'C',1);
        $pdf->SetXY(185, 100);
        $pdf->Cell(9, 5, utf8_decode("Casi"), 1, 0, 'C',1);
        $pdf->SetXY(185, 105);
        $pdf->Cell(9, 5, utf8_decode("Nunca"), 1, 0, 'C',1);
        $pdf->SetXY(194, 100);
        $pdf->Cell(9, 10, utf8_decode("Nunca"), 1, 0, 'C',1);
        $arDesempenoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle();
        $arDesempenoDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoDetalle')->findBy(array('codigoDesempenoFk' => self::$codigoDesempeno));
        $pdf->SetXY(10, 110);
        $pdf->SetFont('Arial','B',5);
        $pdf->SetFillColor(272, 272, 272);
        foreach ($arDesempenoDetalles as $arDesempenoDetalle){
            if ($arDesempenoDetalle->getDesempenoConceptoRel()->getCodigoDesempenoConceptoTipoFk() == 1){
                $pdf->Cell(142, 5, utf8_decode($arDesempenoDetalle->getDesempenoConceptoRel()->getNombre()), 1, 0, 'L');
                if ($arDesempenoDetalle->getSiempre() == 1){
                    $siempreAreaProf = "X";
                }else{
                    $siempreAreaProf = ""; 
                }
                $pdf->Cell(11, 5, $siempreAreaProf, 1, 0, 'C');
                if ($arDesempenoDetalle->getCasiSiempre() == 1){
                    $CasiSiempreAreaProf = "X";
                }else{
                    $CasiSiempreAreaProf = ""; 
                }
                $pdf->Cell(11, 5, $CasiSiempreAreaProf, 1, 0, 'C');
                if ($arDesempenoDetalle->getAlgunasVeces() == 1){
                    $AlgunasVecesAreaProf = "X";
                }else{
                    $AlgunasVecesAreaProf = ""; 
                }
                $pdf->Cell(11, 5, $AlgunasVecesAreaProf, 1, 0, 'C');
                if ($arDesempenoDetalle->getCasiNunca() == 1){
                    $CasiNuncaAreaProf = "X";
                }else{
                    $CasiNuncaAreaProf = ""; 
                }
                $pdf->Cell(9, 5, $CasiNuncaAreaProf, 1, 0, 'C');
                if ($arDesempenoDetalle->getNunca() == 1){
                    $nuncaAreaProf = "X";
                }else{
                    $nuncaAreaProf = ""; 
                }
                $pdf->Cell(9, 5, $nuncaAreaProf, 1, 0, 'C');
            }
            $pdf->Ln();
        }
        $pdf->SetXY(10, 145);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(193, 5, utf8_decode("COMPROMISOS"), 1, 0, 'C',1);
        $pdf->SetXY(10, 115);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->SetFont('Arial','B',5);
        foreach ($arDesempenoDetalles as $arDesempenoDetalle){
            if ($arDesempenoDetalle->getDesempenoConceptoRel()->getCodigoDesempenoConceptoTipoFk() == 2){
                $pdf->Cell(142, 5, utf8_decode($arDesempenoDetalle->getDesempenoConceptoRel()->getNombre()), 1, 0, 'L');
                if ($arDesempenoDetalle->getSiempre() == 1){
                    $siempreCompromisos = "X";
                }else{
                    $siempreCompromisos = ""; 
                }
                $pdf->Cell(11, 5, $siempreCompromisos, 1, 0, 'C');
                if ($arDesempenoDetalle->getCasiSiempre() == 1){
                    $CasiSiempreCompromisos = "X";
                }else{
                    $CasiSiempreCompromisos = ""; 
                }
                $pdf->Cell(11, 5, $CasiSiempreCompromisos, 1, 0, 'C');
                if ($arDesempenoDetalle->getAlgunasVeces() == 1){
                    $AlgunasVecesCompromisos = "X";
                }else{
                    $AlgunasVecesCompromisos = ""; 
                }
                $pdf->Cell(11, 5, $AlgunasVecesCompromisos, 1, 0, 'C');
                if ($arDesempenoDetalle->getCasiNunca() == 1){
                    $CasiNuncaCompromisos = "X";
                }else{
                    $CasiNuncaCompromisos = ""; 
                }
                $pdf->Cell(9, 5, $CasiNuncaCompromisos, 1, 0, 'C');
                if ($arDesempenoDetalle->getNunca() == 1){
                    $nuncaCompromisos = "X";
                }else{
                    $nuncaCompromisos = ""; 
                }
                $pdf->Cell(9, 5, $nuncaCompromisos, 1, 0, 'C');
            }
            $pdf->Ln();
        }
        $pdf->SetXY(10, 180);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(193, 5, utf8_decode("IMÁGEN PERSONAL"), 1, 0, 'C',1);
        $pdf->SetXY(10, 120);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->SetFont('Arial','B',5);
        foreach ($arDesempenoDetalles as $arDesempenoDetalle){
            if ($arDesempenoDetalle->getDesempenoConceptoRel()->getCodigoDesempenoConceptoTipoFk() == 3){
                $pdf->Cell(142, 5, utf8_decode($arDesempenoDetalle->getDesempenoConceptoRel()->getNombre()), 1, 0, 'L');
                if ($arDesempenoDetalle->getSiempre() == 1){
                    $siempreImagen = "X";
                }else{
                    $siempreImagen = ""; 
                }
                $pdf->Cell(11, 5, $siempreImagen, 1, 0, 'C');
                if ($arDesempenoDetalle->getCasiSiempre() == 1){
                    $CasiSiempreImagen = "X";
                }else{
                    $CasiSiempreImagen = ""; 
                }
                $pdf->Cell(11, 5, $CasiSiempreImagen, 1, 0, 'C');
                if ($arDesempenoDetalle->getAlgunasVeces() == 1){
                    $AlgunasVecesImagen = "X";
                }else{
                    $AlgunasVecesImagen = ""; 
                }
                $pdf->Cell(11, 5, $AlgunasVecesImagen, 1, 0, 'C');
                if ($arDesempenoDetalle->getCasiNunca() == 1){
                    $CasiNuncaImagen = "X";
                }else{
                    $CasiNuncaImagen = ""; 
                }
                $pdf->Cell(9, 5, $CasiNuncaImagen, 1, 0, 'C');
                if ($arDesempenoDetalle->getNunca() == 1){
                    $nuncaImagen = "X";
                }else{
                    $nuncaImagen = ""; 
                }
                $pdf->Cell(9, 5, $nuncaImagen, 1, 0, 'C');
            }
            $pdf->Ln();
        }
        $pdf->SetXY(10, 215);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(193, 5, utf8_decode("VALORES"), 1, 0, 'C',1);
        $pdf->SetXY(10, 125);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->SetFont('Arial','B',5);
        foreach ($arDesempenoDetalles as $arDesempenoDetalle){
            if ($arDesempenoDetalle->getDesempenoConceptoRel()->getCodigoDesempenoConceptoTipoFk() == 4){
                $pdf->Cell(142, 5, utf8_decode($arDesempenoDetalle->getDesempenoConceptoRel()->getNombre()), 1, 0, 'L');
                if ($arDesempenoDetalle->getSiempre() == 1){
                    $siempreValores = "X";
                }else{
                    $siempreValores = ""; 
                }
                $pdf->Cell(11, 5, $siempreValores, 1, 0, 'C');
                if ($arDesempenoDetalle->getCasiSiempre() == 1){
                    $CasiSiempreValores = "X";
                }else{
                    $CasiSiempreValores = ""; 
                }
                $pdf->Cell(11, 5, $CasiSiempreValores, 1, 0, 'C');
                if ($arDesempenoDetalle->getAlgunasVeces() == 1){
                    $AlgunasVecesValores = "X";
                }else{
                    $AlgunasVecesValores = ""; 
                }
                $pdf->Cell(11, 5, $AlgunasVecesValores, 1, 0, 'C');
                if ($arDesempenoDetalle->getCasiNunca() == 1){
                    $CasiNuncaValores = "X";
                }else{
                    $CasiNuncaValores = ""; 
                }
                $pdf->Cell(9, 5, $CasiNuncaValores, 1, 0, 'C');
                if ($arDesempenoDetalle->getNunca() == 1){
                    $nuncaValores = "X";
                }else{
                    $nuncaValores = ""; 
                }
                $pdf->Cell(9, 5, $nuncaValores, 1, 0, 'C');
            }
            $pdf->Ln();
        }
        $pdf->SetXY(10, 251);
        $pdf->Cell(193, 7, "", 0, 0, 'C',1);
        $pdf->SetXY(10, 260);
        $pdf->Cell(193, 7, "", 0, 0, 'C',1);
        $pdf->SetXY(10, 271);
        $pdf->Cell(193, 7, "", 0, 0, 'C',1);
        $pdf->SetXY(10, 55);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(193, 5, utf8_decode("COMPETENCIAS"), 1, 0, 'C',1);
        $pdf->SetXY(10, 60);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(142, 10, utf8_decode("ASPECTOS A EVALUAR"), 1, 0, 'C',1);
        $pdf->SetFont('Arial','B',7.1);
        $pdf->Cell(11, 10, utf8_decode("Siempre"), 1, 0, 'C',1);
        $pdf->Cell(11, 5, utf8_decode("Casi"), 1, 0, 'C',1);
        $pdf->SetXY(163, 65);
        $pdf->Cell(11, 5, utf8_decode("Siempre"), 1, 0, 'C',1);
        $pdf->SetXY(174, 60);
        $pdf->Cell(11, 5, utf8_decode("Algunas"), 1, 0, 'C',1);
        $pdf->SetXY(174, 65);
        $pdf->Cell(11, 5, utf8_decode("Veces"), 1, 0, 'C',1);
        $pdf->SetXY(185, 65);
        $pdf->Cell(9, 5, utf8_decode("Casi"), 1, 0, 'C',1);
        $pdf->SetXY(185, 60);
        $pdf->Cell(9, 5, utf8_decode("Nunca"), 1, 0, 'C',1);
        $pdf->SetXY(194, 60);
        $pdf->Cell(9, 10, utf8_decode("Nunca"), 1, 0, 'C',1);
        
        $pdf->SetXY(10, 70);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->SetFont('Arial','B',5);
        foreach ($arDesempenoDetalles as $arDesempenoDetalle){
            if ($arDesempenoDetalle->getDesempenoConceptoRel()->getCodigoDesempenoConceptoTipoFk() == 5){
                $pdf->Cell(142, 5, utf8_decode($arDesempenoDetalle->getDesempenoConceptoRel()->getNombre()), 1, 0, 'L');
                if ($arDesempenoDetalle->getSiempre() == 1){
                    $siempreCompetencias = "X";
                }else{
                    $siempreCompetencias = ""; 
                }
                $pdf->Cell(11, 5, $siempreCompetencias, 1, 0, 'C');
                if ($arDesempenoDetalle->getCasiSiempre() == 1){
                    $CasiSiempreCompetencias = "X";
                }else{
                    $CasiSiempreCompetencias = ""; 
                }
                $pdf->Cell(11, 5, $CasiSiempreCompetencias, 1, 0, 'C');
                if ($arDesempenoDetalle->getAlgunasVeces() == 1){
                    $AlgunasVecesCompetencias = "X";
                }else{
                    $AlgunasVecesCompetencias = ""; 
                }
                $pdf->Cell(11, 5, $AlgunasVecesCompetencias, 1, 0, 'C');
                if ($arDesempenoDetalle->getCasiNunca() == 1){
                    $CasiNuncaCompetencias = "X";
                }else{
                    $CasiNuncaCompetencias = ""; 
                }
                $pdf->Cell(9, 5, $CasiNuncaCompetencias, 1, 0, 'C');
                if ($arDesempenoDetalle->getNunca() == 1){
                    $nuncaCompetencias = "X";
                }else{
                    $nuncaCompetencias = ""; 
                }
                $pdf->Cell(9, 5, $nuncaCompetencias, 1, 0, 'C');
                $pdf->Ln();
            }
        }
        $pdf->SetXY(10, 125);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(193, 5, utf8_decode("OBSERVACIONES"), 1, 0, 'C',1);
        $pdf->SetXY(10, 130);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->SetFont('Arial','B',6);
        $pdf->Cell(193, 15, utf8_decode($arDesempeno->getObservaciones()), 1, 0, 'C',1);
        
        $pdf->SetXY(10, 150);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(193, 5, utf8_decode("QUE ASPECTOS MEJORARÍA EN SU COLABORACIÓN"), 1, 0, 'C',1);
        $pdf->SetXY(10, 155);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->SetFont('Arial','B',6);
        $pdf->Cell(193, 15, utf8_decode($arDesempeno->getAspectosMejorar()), 1, 0, 'C',1);
        $pdf->SetXY(10, 175);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(193, 5, utf8_decode("RESULTADOS"), 1, 0, 'C',1);
        $pdf->SetXY(10, 180);
        $pdf->Cell(193, 5, utf8_decode("ÁREAS EVALUADAS:"), 1, 0, 'L',1);
        $pdf->SetXY(10, 185);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(150, 5, utf8_decode("ÁREA PROFESIONAL"), 1, 0, 'L',1);
        $pdf->Cell(43, 5, $arDesempeno->getAreaProfesional(), 1, 0, 'C',1);
        $pdf->SetXY(10, 190);
        $pdf->Cell(150, 5, utf8_decode("COMPROMISO"), 1, 0, 'L',1);
        $pdf->Cell(43, 5, $arDesempeno->getCompromiso(), 1, 0, 'C',1);
        $pdf->SetXY(10, 195);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(150, 5, utf8_decode("URBANIDAD"), 1, 0, 'L',1);
        $pdf->Cell(43, 5, $arDesempeno->getUrbanidad(), 1, 0, 'C',1);
        $pdf->SetXY(10, 200);
        $pdf->Cell(150, 5, utf8_decode("VALORES"), 1, 0, 'L',1);
        $pdf->Cell(43, 5, $arDesempeno->getValores(), 1, 0, 'C',1);
        $pdf->SetXY(10, 205);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(150, 5, utf8_decode("TOTAL ÁREAS EVALUADAS"), 1, 0, 'R',1);
        $pdf->Cell(43, 5, $arDesempeno->getSubTotal1(), 1, 0, 'C',1);
        $pdf->SetXY(10, 210);
        $pdf->Cell(193, 5, utf8_decode("COMPETENCIAS:"), 1, 0, 'L',1);
        $pdf->SetXY(10, 215);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(150, 5, utf8_decode("ORIENTACION AL CLIENTE"), 1, 0, 'L',1);
        $pdf->Cell(43, 5, $arDesempeno->getOrientacionCliente(), 1, 0, 'C',1);
        $pdf->SetXY(10, 220);
        $pdf->Cell(150, 5, utf8_decode("ORIENTACIÓN A RESULTADOS"), 1, 0, 'L',1);
        $pdf->Cell(43, 5, $arDesempeno->getOrientacionResultados(), 1, 0, 'C',1);
        $pdf->SetXY(10, 225);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(150, 5, utf8_decode("CONSTRUCCIÓN Y MANTENIMIENTO DE RELACIONES"), 1, 0, 'L',1);
        $pdf->Cell(43, 5, $arDesempeno->getConstruccionMantenimientoRelaciones(), 1, 0, 'C',1);
        $pdf->SetXY(10, 230);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(150, 5, utf8_decode("TOTAL COMPETENCIAS"), 1, 0, 'R',1);
        $pdf->Cell(43, 5, $arDesempeno->getSubTotal2(), 1, 0, 'C',1);
        $pdf->SetXY(10, 235);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(150, 5, utf8_decode("TOTAL DESEMPEÑO"), 1, 0, 'R',1);
        $pdf->Cell(43, 5, $arDesempeno->getTotalDesempeno(), 1, 0, 'C',1);
        $pdf->Line(10, 50, 203, 50);
        $pdf->SetAutoPageBreak(true, 15);
    }

    public function Footer() {
        $this->SetFont('Arial','B',8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');;
    }
}

?>
