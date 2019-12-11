<?php
namespace Brasa\GeneralBundle\MisClases;

use Symfony\Component\HttpFoundation\Request;

class Funciones {

    /**
     * Construye los parametros requeridos para generar un mensaje
     * @param string $strTipo El tipo de mensaje a generar  se debe enviar en minuscula <br> error, informacion
     * @param string $strMensaje El mensaje que se mostrara
     * @param string $vista la vista donde se mostrara el mensaje
     */
    public function devuelveBoolean($dato) {
        $strResultado = "";
        if($dato == TRUE) {
            $strResultado = 'SI';
        } else {
            $strResultado = 'NO';
        }
        return $strResultado;                
    }
    
    public function diaSemana($dateFecha) {
        
    }
    
    public function sumarDiasFecha($intDias, $dateFecha) {
        $fecha = $dateFecha->format('Y-m-j');
        $nuevafecha = strtotime ( '+'.$intDias.' day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
        $dateNuevaFecha = date_create($nuevafecha);                
        return $dateNuevaFecha;
    }
    
    public function ultimoDiaMes($strAnio = "", $strMes = "") {
        $strUltimoDiaMes = date("d",(mktime(0,0,0,$strMes+1,1,$strAnio)-1));
        return $intUltimoDiaMes;
    }
    
    public static function devolverNumeroLetras($num, $fem = true, $dec = true) {

    //if (strlen($num) > 14) die("El n?mero introducido es demasiado grande");

       $matuni[2]  = "dos";

       $matuni[3]  = "tres";

       $matuni[4]  = "cuatro";

       $matuni[5]  = "cinco";

       $matuni[6]  = "seis";

       $matuni[7]  = "siete";

       $matuni[8]  = "ocho";

       $matuni[9]  = "nueve";

       $matuni[10] = "diez";

       $matuni[11] = "once";

       $matuni[12] = "doce";

       $matuni[13] = "trece";

       $matuni[14] = "catorce";

       $matuni[15] = "quince";

       $matuni[16] = "dieciseis";

       $matuni[17] = "diecisiete";

       $matuni[18] = "dieciocho";

       $matuni[19] = "diecinueve";

       $matuni[20] = "veinte";

       $matunisub[2] = "dos";

       $matunisub[3] = "tres";

       $matunisub[4] = "cuatro";

       $matunisub[5] = "quin";

       $matunisub[6] = "seis";

       $matunisub[7] = "sete";

       $matunisub[8] = "ocho";

       $matunisub[9] = "nove";



       $matdec[2] = "veint";

       $matdec[3] = "treinta";

       $matdec[4] = "cuarenta";

       $matdec[5] = "cincuenta";

       $matdec[6] = "sesenta";

       $matdec[7] = "setenta";

       $matdec[8] = "ochenta";

       $matdec[9] = "noventa";

       $matsub[3]  = 'mill';

       $matsub[5]  = 'bill';

       $matsub[7]  = 'mill';

       $matsub[9]  = 'trill';

       $matsub[11] = 'mill';

       $matsub[13] = 'bill';

       $matsub[15] = 'mill';

       $matmil[4]  = 'millones';

       $matmil[6]  = 'billones';

       $matmil[7]  = 'de billones';

       $matmil[8]  = 'millones de billones';

       $matmil[10] = 'trillones';

       $matmil[11] = 'de trillones';

       $matmil[12] = 'millones de trillones';

       $matmil[13] = 'de trillones';

       $matmil[14] = 'billones de trillones';

       $matmil[15] = 'de billones de trillones';

       $matmil[16] = 'millones de billones de trillones';


       if($num == '')
           $num = 0;

       $num = trim((string)@$num);

       if ($num[0] == '-') {

          $neg = 'menos ';

          $num = substr($num, 1);

       }else

          $neg = '';

       while ($num[0] == '0') $num = substr($num, 1);

       if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;

       $zeros = true;

       $punt = false;

       $ent = '';

       $fra = '';

       for ($c = 0; $c < strlen($num); $c++) {

          $n = $num[$c];

          if (! (strpos(".,'''", $n) === false)) {

             if ($punt) break;

             else{

                $punt = true;

                continue;

             }



          }elseif (! (strpos('0123456789', $n) === false)) {

             if ($punt) {

                if ($n != '0') $zeros = false;

                $fra .= $n;

             }else



                $ent .= $n;

          }else



             break;



       }

       $ent = '     ' . $ent;

       if ($dec and $fra and ! $zeros) {

          $fin = ' coma';

          for ($n = 0; $n < strlen($fra); $n++) {

             if (($s = $fra[$n]) == '0')

                $fin .= ' cero';

             elseif ($s == '1')

                $fin .= $fem ? ' una' : ' un';

             else

                $fin .= ' ' . $matuni[$s];

          }

       }else

          $fin = '';

       if ((int)$ent === 0) return 'Cero ' . $fin;

       $tex = '';

       $sub = 0;

       $mils = 0;

       $neutro = false;

       while ( ($num = substr($ent, -3)) != '   ') {

          $ent = substr($ent, 0, -3);

          if (++$sub < 3 and $fem) {

             $matuni[1] = 'una';

             $subcent = 'as';

          }else{

             $matuni[1] = $neutro ? 'un' : 'uno';

             $subcent = 'os';

          }

          $t = '';

          $n2 = substr($num, 1);

          if ($n2 == '00') {

          }elseif ($n2 < 21)

             $t = ' ' . $matuni[(int)$n2];

          elseif ($n2 < 30) {

             $n3 = $num[2];

             if ($n3 != 0) $t = 'i' . $matuni[$n3];

             $n2 = $num[1];

             $t = ' ' . $matdec[$n2] . $t;

          }else{

             $n3 = $num[2];

             if ($n3 != 0) $t = ' y ' . $matuni[$n3];

             $n2 = $num[1];

             $t = ' ' . $matdec[$n2] . $t;

          }

          $n = $num[0];

          if ($n == 1) {

             $t = ' ciento' . $t;

          }elseif ($n == 5){

             $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;

          }elseif ($n != 0){

             $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;

          }

          if ($sub == 1) {

          }elseif (! isset($matsub[$sub])) {

             if ($num == 1) {

                $t = ' mil';

             }elseif ($num > 1){

                $t .= ' mil';

             }

          }elseif ($num == 1) {

             $t .= ' ' . $matsub[$sub] . 'on';

          }elseif ($num > 1){

             $t .= ' ' . $matsub[$sub] . 'ones';

          }

          if ($num == '000') $mils ++;

          elseif ($mils != 0) {

             if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];

             $mils = 0;

          }

          $neutro = true;

          $tex = $t . $tex;

       }

       $tex = $neg . substr($tex, 1) . $fin;

       return ucfirst($tex);

    }         
 
    public static function devuelveDiaSemanaEspaniol ($dateFecha) {
        $strDia = "";
        switch ($dateFecha->format('N')) {
            case 1:
                $strDia = "l";
                break;
            case 2:
                $strDia = "m";
                break;
            case 3:
                $strDia = "i";
                break;
            case 4:
                $strDia = "j";
                break;
            case 5:
                $strDia = "v";
                break;
            case 6:
                $strDia = "s";
                break;
            case 7:
                $strDia = "d";
                break;
        }

        return $strDia;
    }     
 
    public static function festivo($arFestivos, $dateFecha) {
        $boolFestivo = 0;
        foreach ($arFestivos as $arFestivo) {
            if($arFestivo['fecha'] == $dateFecha) {
                $boolFestivo = 1;
            }
        }
        return $boolFestivo;
    }    
    
    public static function devuelveTurnoDia($arProgramacionDetalle, $intDia) {        
        $strTurno = NULL;
        if($intDia == 1) {
            $strTurno = $arProgramacionDetalle->getDia1();
        }
        if($intDia == 2) {
            $strTurno = $arProgramacionDetalle->getDia2();
        }
        if($intDia == 3) {
            $strTurno = $arProgramacionDetalle->getDia3();
        }
        if($intDia == 4) {
            $strTurno = $arProgramacionDetalle->getDia4();
        }
        if($intDia == 5) {
            $strTurno = $arProgramacionDetalle->getDia5();
        }
        if($intDia == 6) {
            $strTurno = $arProgramacionDetalle->getDia6();
        }
        if($intDia == 7) {
            $strTurno = $arProgramacionDetalle->getDia7();
        }
        if($intDia == 8) {
            $strTurno = $arProgramacionDetalle->getDia8();
        }
        if($intDia == 9) {
            $strTurno = $arProgramacionDetalle->getDia9();
        }
        if($intDia == 10) {
            $strTurno = $arProgramacionDetalle->getDia10();
        }
        if($intDia == 11) {
            $strTurno = $arProgramacionDetalle->getDia11();
        }
        if($intDia == 12) {
            $strTurno = $arProgramacionDetalle->getDia12();
        }
        if($intDia == 13) {
            $strTurno = $arProgramacionDetalle->getDia13();
        }
        if($intDia == 14) {
            $strTurno = $arProgramacionDetalle->getDia14();
        }
        if($intDia == 15) {
            $strTurno = $arProgramacionDetalle->getDia15();
        }
        if($intDia == 16) {
            $strTurno = $arProgramacionDetalle->getDia16();
        }
        if($intDia == 17) {
            $strTurno = $arProgramacionDetalle->getDia17();
        }
        if($intDia == 18) {
            $strTurno = $arProgramacionDetalle->getDia18();
        }
        if($intDia == 19) {
            $strTurno = $arProgramacionDetalle->getDia19();
        }
        if($intDia == 20) {
            $strTurno = $arProgramacionDetalle->getDia20();
        }
        if($intDia == 21) {
            $strTurno = $arProgramacionDetalle->getDia21();
        }
        if($intDia == 22) {
            $strTurno = $arProgramacionDetalle->getDia22();
        }
        if($intDia == 23) {
            $strTurno = $arProgramacionDetalle->getDia23();
        }
        if($intDia == 24) {
            $strTurno = $arProgramacionDetalle->getDia24();
        }
        if($intDia == 25) {
            $strTurno = $arProgramacionDetalle->getDia25();
        }
        if($intDia == 26) {
            $strTurno = $arProgramacionDetalle->getDia26();
        }
        if($intDia == 27) {
            $strTurno = $arProgramacionDetalle->getDia27();
        }
        if($intDia == 28) {
            $strTurno = $arProgramacionDetalle->getDia28();
        }
        if($intDia == 29) {
            $strTurno = $arProgramacionDetalle->getDia29();
        }
        if($intDia == 30) {
            $strTurno = $arProgramacionDetalle->getDia30();
        }        
        if($intDia == 31) {
            $strTurno = $arProgramacionDetalle->getDia31();
        }
        return $strTurno;
    }    
    
    public static function turnoHoras($intHoraInicio, $intMinutoInicio, $intHoraFinal, $boolFestivo, $intHoras, $boolNovedad = 0, $boolDescanso = 0) {        
        $objFunciones = new Funciones();
        if($boolNovedad == 0) {
            $intHorasNocturnas = $objFunciones->calcularTiempo($intHoraInicio, $intHoraFinal, 0, 6);        
            $intHorasExtrasNocturnas = 0;
            $intTotalHoras = $intHorasNocturnas + $intHoras;
            if($intTotalHoras > 8) {
                $intHorasJornada = 8 - $intHoras;
                if($intHorasJornada >= 1) {
                    $intHorasNocturnasReales = $intHorasNocturnas - $intHorasJornada;
                    $intHorasNocturnas = $intHorasNocturnas - $intHorasNocturnasReales;
                    $intHorasExtrasNocturnas = $intHorasNocturnasReales;
                } else {
                    $intHorasExtrasNocturnas = $intHorasNocturnas;
                    $intHorasNocturnas = 0;
                }
            }

            $intHorasDiurnas = $objFunciones->calcularTiempo($intHoraInicio, $intHoraFinal, 6, 22);            
            $intHorasExtrasDiurnas = 0;
            $intTotalHoras = $intHoras + $intHorasNocturnas + $intHorasExtrasNocturnas + $intHorasDiurnas;
            if($intTotalHoras > 8) {
                $intHorasJornada = 8 - ($intHoras + $intHorasNocturnas + $intHorasExtrasNocturnas);                    
                if($intHorasJornada > 1) {
                    $intHorasDiurnasReales = $intHorasDiurnas - $intHorasJornada;
                    $intHorasDiurnas = $intHorasDiurnas - $intHorasDiurnasReales;
                    $intHorasExtrasDiurnas = $intHorasDiurnasReales;
                } else {
                    $intHorasExtrasDiurnas = $intHorasDiurnas;
                    $intHorasDiurnas = 0;
                }            
            }

            $intHorasNocturnasNoche = $objFunciones->calcularTiempo($intHoraInicio, $intHoraFinal, 22, 24); 
            $intHorasExtrasNocturnasNoche = 0;
            $intTotalHoras = $intHorasDiurnas + $intHorasExtrasDiurnas + $intHorasNocturnas + $intHorasNocturnasNoche;                                        
            if($intTotalHoras > 8) {                    
                $intHorasJornada = 8 - ($intHorasNocturnas + $intHorasDiurnas + $intHorasExtrasDiurnas);                    
                if($intHorasJornada > 1) {
                    $intHorasNocturnasNocheReales = $intHorasNocturnasNoche - $intHorasJornada;
                    $intHorasNocturnasNoche = $intHorasNocturnasNoche - $intHorasNocturnasNocheReales;
                    $intHorasExtrasNocturnasNoche = $intHorasNocturnasNocheReales;                        
                } else {
                    $intHorasExtrasNocturnasNoche = $intHorasNocturnasNoche;
                    $intHorasNocturnasNoche = 0;
                }
            }
            $intHorasNocturnas += $intHorasNocturnasNoche;        
            $intHorasExtrasNocturnas += $intHorasExtrasNocturnasNoche;

            $intHorasFestivasDiurnas = 0;
            $intHorasFestivasNocturnas = 0;
            $intHorasExtrasFestivasDiurnas = 0;
            $intHorasExtrasFestivasNocturnas = 0;
            if($boolFestivo == 1) {
                $intHorasFestivasDiurnas = $intHorasDiurnas;
                $intHorasDiurnas = 0;
                $intHorasFestivasNocturnas = $intHorasNocturnas;
                $intHorasNocturnas = 0;
                $intHorasExtrasFestivasDiurnas = $intHorasExtrasDiurnas;
                $intHorasExtrasDiurnas = 0;
                $intHorasExtrasFestivasNocturnas = $intHorasExtrasNocturnas;
                $intHorasExtrasNocturnas = 0;
            }                
            $intTotalHoras = $intHorasDiurnas+$intHorasNocturnas+$intHorasExtrasDiurnas+$intHorasExtrasNocturnas+$intHorasFestivasDiurnas+$intHorasFestivasNocturnas+$intHorasExtrasFestivasDiurnas+$intHorasExtrasFestivasNocturnas;            
            if($boolDescanso == 1) {                
                $arrHoras = array(
                    'horasDescanso' => $intTotalHoras,
                    'horasNovedad' => 0,
                    'horasDiurnas' => 0, 
                    'horasNocturnas' => 0, 
                    'horasExtrasDiurnas' => 0, 
                    'horasExtrasNocturnas' => 0,
                    'horasFestivasDiurnas' => 0, 
                    'horasFestivasNocturnas' => 0, 
                    'horasExtrasFestivasDiurnas' => 0, 
                    'horasExtrasFestivasNocturnas' => 0,
                    'horas' => $intTotalHoras);                
            } else {
                $arrHoras = array(
                    'horasDescanso' => 0,
                    'horasNovedad' => 0,
                    'horasDiurnas' => $intHorasDiurnas, 
                    'horasNocturnas' => $intHorasNocturnas, 
                    'horasExtrasDiurnas' => $intHorasExtrasDiurnas, 
                    'horasExtrasNocturnas' => $intHorasExtrasNocturnas,
                    'horasFestivasDiurnas' => $intHorasFestivasDiurnas, 
                    'horasFestivasNocturnas' => $intHorasFestivasNocturnas, 
                    'horasExtrasFestivasDiurnas' => $intHorasExtrasFestivasDiurnas, 
                    'horasExtrasFestivasNocturnas' => $intHorasExtrasFestivasNocturnas,
                    'horas' => $intTotalHoras);                
            }
            
        } else {
            $arrHoras = array(
                'horasDescanso' => 0,
                'horasNovedad' => 8,
                'horasDiurnas' => 0, 
                'horasNocturnas' => 0, 
                'horasExtrasDiurnas' => 0, 
                'horasExtrasNocturnas' => 0,
                'horasFestivasDiurnas' => 0, 
                'horasFestivasNocturnas' => 0, 
                'horasExtrasFestivasDiurnas' => 0, 
                'horasExtrasFestivasNocturnas' => 0,
                'horas' => 0);            
        }  
        
        return $arrHoras;
    }        
    
    public static function calcularTiempo($intInicial, $intFinal, $intParametroInicio, $intParametroFinal) {
        $intHoras = 0;
        $intHoraIniciaTemporal = 0;
        $intHoraTerminaTemporal = 0;
        if($intInicial < $intParametroInicio) {
            $intHoraIniciaTemporal = $intParametroInicio;
        } else {
            $intHoraIniciaTemporal = $intInicial;
        }
        if($intFinal > $intParametroFinal) {
            if($intInicial > $intParametroFinal) {
                $intHoraTerminaTemporal = $intInicial;
            } else {
                $intHoraTerminaTemporal = $intParametroFinal;
            }
        } else {
            if($intFinal > $intParametroInicio) {
                $intHoraTerminaTemporal = $intFinal;
            } else {
                $intHoraTerminaTemporal = $intParametroInicio;
            }
        }
        $intHoras = $intHoraTerminaTemporal - $intHoraIniciaTemporal;
        return $intHoras;
    }        
    
    public static function diasMes($fecha, $arFestivos) {
        $strAnioMes = $fecha->format('Y/m');
        $arrDiaSemana = array();
        for($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = "";
            switch ($dateFecha->format('N')) {
                case 1:
                    $diaSemana = "l";
                    break;
                case 2:
                    $diaSemana = "m";
                    break;
                case 3:
                    $diaSemana = "i";
                    break;
                case 4:
                    $diaSemana = "j";
                    break;
                case 5:
                    $diaSemana = "v";
                    break;
                case 6:
                    $diaSemana = "s";
                    break;
                case 7:
                    $diaSemana = "d";
                    break;
            }                        
            $boolFestivo = 0;
            if($diaSemana == 'd') {
                $boolFestivo = 1;
            }
            foreach ($arFestivos as $arFestivo) {
                if($arFestivo['fecha']->format('d') == $i) {
                    $boolFestivo = 1;
                }
            }
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana, 'festivo' => $boolFestivo);
        }
        return $arrDiaSemana;
    }    
    
    public static function devuelveDigitoVerificacion($nit) {
        if (!is_numeric($nit)) {
            return false;
        }

        $arr = array(1 => 3, 4 => 17, 7 => 29, 10 => 43, 13 => 59, 2 => 7, 5 => 19,
            8 => 37, 11 => 47, 14 => 67, 3 => 13, 6 => 23, 9 => 41, 12 => 53, 15 => 71);
        $x = 0;
        $y = 0;
        $z = strlen($nit);
        $dv = '';

        for ($i = 0; $i < $z; $i++) {
            $y = substr($nit, $i, 1);
            $x += ($y * $arr[$z - $i]);
        }

        $y = $x % 11;

        if ($y > 1) {
            $dv = 11 - $y;
            return $dv;
        } else {
            $dv = $y;
            return $dv;
        }
    }

    public static function diasPrestaciones($dateFechaDesde, $dateFechaHasta) {
        $objFunciones = new Funciones();
        $intDias = 0;
        $intAnioInicio = $dateFechaDesde->format('Y');
        $intAnioFin = $dateFechaHasta->format('Y');
        $intAnios = 0;
        $intMeses = 0;
        if($dateFechaHasta >= $dateFechaDesde) {
            if($dateFechaHasta->format('d') == '31' && ($dateFechaHasta == $dateFechaDesde)){
                $intDias = 0;
            } else { 
                if($intAnioInicio != $intAnioFin) {
                    $intDiferenciaAnio = $intAnioFin - $intAnioInicio;            
                    if(($intDiferenciaAnio) > 1) {
                        $intAnios = $intDiferenciaAnio - 1;
                        $intAnios = $intAnios * 12 * 30;                        
                    }

                    $dateFechaTemporal = date_create_from_format('Y-m-d H:i', $intAnioInicio . '-12-30' . "00:00");
                    if($dateFechaDesde->format('n') != $dateFechaTemporal->format('n')) {                        
                        $intMeses = $dateFechaTemporal->format('n') - $dateFechaDesde->format('n') - 1;
                        $intDiasInicio = $objFunciones->diasPrestacionesMes($dateFechaDesde->format('j'), 1);
                        $intDiasFinal = $objFunciones->diasPrestacionesMes($dateFechaTemporal->format('j'), 0);
                        $intDias = $intDiasInicio + $intDiasFinal + ($intMeses * 30);
                    } else {
                        if($dateFechaTemporal->format('j') == $dateFechaDesde->format('j')) {
                            $intDias = 0;
                        } else {
                            $intDias = 1 + ($dateFechaTemporal->format('j') - $dateFechaDesde->format('j'));                               
                        }                
                    }

                    $dateFechaTemporal = date_create_from_format('Y-m-d H:i', $intAnioFin . '-01-01' . "00:00");
                    if($dateFechaTemporal->format('n') != $dateFechaHasta->format('n')) {                        
                        $intMeses = $dateFechaHasta->format('n') - $dateFechaTemporal->format('n') - 1;
                        $intDiasInicio = $objFunciones->diasPrestacionesMes($dateFechaTemporal->format('j'), 1);
                        $intDiasFinal = $objFunciones->diasPrestacionesMes($dateFechaHasta->format('j'), 0);
                        $intDias += $intDiasInicio + $intDiasFinal + ($intMeses * 30);
                    } else {
                        $intDias += 1 + ($dateFechaHasta->format('j') - $dateFechaTemporal->format('j'));                               
                    }
                    $intDias += $intAnios;
                } else {                                           
                    if($dateFechaDesde->format('n') != $dateFechaHasta->format('n')) {                        
                        $intMeses = $dateFechaHasta->format('n') - $dateFechaDesde->format('n') - 1;
                        $intDiasInicio = $objFunciones->diasPrestacionesMes($dateFechaDesde->format('j'), 1);
                        $intDiasFinal = $objFunciones->diasPrestacionesMes($dateFechaHasta->format('j'), 0);
                        $intDias = $intDiasInicio + $intDiasFinal + ($intMeses * 30);
                    } else {
                        if($dateFechaHasta->format('j') == 31) {
                            $intDias = ($dateFechaHasta->format('j') - $dateFechaDesde->format('j'));                                                                               
                        } else {
                            $intDias = 1 + ($dateFechaHasta->format('j') - $dateFechaDesde->format('j'));                                                                               
                        }
                        
                    }                        
                } 
            }
        } else {
            $intDias = 0;
        }
        return $intDias;
    }    
    
    public static function diasPrestacionesMes($intDia, $intDesde) {
        $intDiasDevolver = 0;
        if($intDesde == 1) {
            $intDiasDevolver = 31 - $intDia;
        } else {
            $intDiasDevolver = $intDia;
            if($intDia == 31) {
                $intDiasDevolver =  30;
            }
        }          
        return $intDiasDevolver;
    }     
}
?>

