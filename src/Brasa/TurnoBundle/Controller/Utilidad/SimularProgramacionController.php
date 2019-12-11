<?php

namespace Brasa\TurnoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class SimularProgramacionController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/utilidad/simular/programacion/{codigoServicio}/{codigoServicioDetalle}", name="brs_tur_utilidad_simular_programacion")
     */    
    public function listaAction(Request $request, $codigoServicio, $codigoServicioDetalle) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator'); 
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $form = $this->formularioLista();
        $form->handleRequest($request);        
        $fechaProgramacion = $form->get('fecha')->getData();
        $usuario = $this->getUser()->getUserName();
        if($form->isValid()) {
            if($form->get('BtnGenerar')->isClicked()) {      
                
                $strSql = "DELETE FROM tur_simulacion_detalle WHERE usuario = '" . $usuario . "'";           
                $em->getConnection()->executeQuery($strSql);      

                
                $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
                $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio); 
                $arServicioDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                if($codigoServicioDetalle == 0) {                    
                    $arServicioDetalles = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $codigoServicio));                                    
                } else {
                    $arServicioDetalles = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $codigoServicio, 'codigoServicioDetallePk' => $codigoServicioDetalle));                                    
                }                
                foreach ($arServicioDetalles as $arServicioDetalle) {            
                    $em->getRepository('BrasaTurnoBundle:TurSimulacionDetalle')->nuevo($arServicioDetalle->getCodigoServicioDetallePk(), $fechaProgramacion, $usuario);
                }     
                $fechaProgramacion = $form->get('fecha')->getData();
                $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
                $arConfiguracion->setFechaUltimaSimulacion($fechaProgramacion);
                $em->persist($arConfiguracion);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_utilidad_simular_programacion', array('codigoServicio' => $codigoServicio, 'codigoServicioDetalle' => $codigoServicioDetalle))); 
            }             
            if($form->get('BtnExcel')->isClicked()) {            
                $this->generarExcel();
            }
            if($form->get('BtnGenerarSoportePago')->isClicked()) { 
                $fechaDesdeSimulacion = date_create($fechaProgramacion->format('Y/m/') . "1");
                $intUltimoDia = date("d",(mktime(0,0,0,$fechaProgramacion->format('m')+1,1,$fechaProgramacion->format('Y'))-1));
                $fechaHastaSimulacion = date_create($fechaProgramacion->format('Y/m/') . $intUltimoDia);
                $strSql = "DELETE FROM tur_simulacion_detalle_recurso WHERE usuario = '" . $usuario . "'";           
                $em->getConnection()->executeQuery($strSql); 
                $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($fechaProgramacion->format('Y-m-').'01', $fechaProgramacion->format('Y-m-').'31');                
                $arSimulacionDetalles = new \Brasa\TurnoBundle\Entity\TurSimulacionDetalle();
                $arSimulacionDetalles = $em->getRepository('BrasaTurnoBundle:TurSimulacionDetalle')->findBy(array('usuario' => $usuario));                
                foreach ($arSimulacionDetalles as $arSimulacionDetalle) {
                    $arSimulacionDetalleAct = new \Brasa\TurnoBundle\Entity\TurSimulacionDetalle();
                    $arSimulacionDetalleAct = $em->getRepository('BrasaTurnoBundle:TurSimulacionDetalle')->find($arSimulacionDetalle->getCodigoSimulacionDetallePk());
                    $arrHoras['horasDiurnas'] = 0;
                    $arrHoras['horasNocturnas'] = 0;
                    $arrHoras['horasExtrasDiurnas'] = 0;
                    $arrHoras['horasExtrasNocturnas'] = 0;
                    $arrHoras['horasFestivasDiurnas'] = 0;
                    $arrHoras['horasFestivasNocturnas'] = 0;
                    $arrHoras['horasExtrasFestivasDiurnas'] = 0;
                    $arrHoras['horasExtrasFestivasNocturnas'] = 0;
                    $arrHoras['horasRecargoNocturno'] = 0;
                    $arrHoras['horasRecargoFestivoDiurno'] = 0;
                    $arrHoras['horasRecargoFestivoNocturno'] = 0;
                    $arrHoras['horasDescanso'] = 0;
                    $arrHoras['horasNovedad'] = 0;  
                    $arrHoras['horas'] = 0;                     
                    for($i = 1; $i <= 31; $i++) {
                        $strFecha = $fechaProgramacion->format('Y/m/') . $i;
                        $dateFecha = date_create($strFecha);
                        $nuevafecha = strtotime ( '+1 day' , strtotime ( $strFecha ) ) ;
                        $dateFecha2 = date ( 'Y/m/j' , $nuevafecha );
                        $dateFecha2 = date_create($dateFecha2);
                        $boolFestivo = $objFunciones->festivo($arFestivos, $dateFecha);
                        $boolFestivo2 = $objFunciones->festivo($arFestivos, $dateFecha2);
                        $strTurno = $objFunciones->devuelveTurnoDia($arSimulacionDetalle, $i);                                                                                                                                                
                        if($strTurno) {
                            $horas = $this->devolverHorasTurno($strTurno, $dateFecha, $dateFecha2, $boolFestivo, $boolFestivo2);
                            $arrHoras['horasDiurnas'] += $horas['horasDiurnas'];
                            $arrHoras['horasNocturnas'] += $horas['horasNocturnas'];
                            $arrHoras['horasExtrasDiurnas'] += $horas['horasExtrasDiurnas'];
                            $arrHoras['horasExtrasNocturnas'] += $horas['horasExtrasNocturnas'];
                            $arrHoras['horasFestivasDiurnas'] += $horas['horasFestivasDiurnas'];
                            $arrHoras['horasFestivasNocturnas'] += $horas['horasFestivasNocturnas'];
                            $arrHoras['horasExtrasFestivasDiurnas'] += $horas['horasExtrasFestivasDiurnas'];
                            $arrHoras['horasExtrasFestivasNocturnas'] += $horas['horasExtrasFestivasNocturnas'];
                            $arrHoras['horasDescanso'] += $horas['horasDescanso'];
                            $arrHoras['horasNovedad'] += $horas['horasNovedad'];  
                            $arrHoras['horas'] += $horas['horas'];                            
                        }                                   
                    }
                    $arSimulacionDetalleAct->setHorasDiurnasPago($arrHoras['horasDiurnas']);
                    $arSimulacionDetalleAct->setHorasNocturnasPago($arrHoras['horasNocturnas']);
                    $arSimulacionDetalleAct->setHorasFestivasDiurnas($arrHoras['horasFestivasDiurnas']);
                    $arSimulacionDetalleAct->setHorasFestivasNocturnas($arrHoras['horasFestivasNocturnas']);
                    $arSimulacionDetalleAct->setHorasExtrasOrdinariasDiurnas($arrHoras['horasExtrasDiurnas']);
                    $arSimulacionDetalleAct->setHorasExtrasOrdinariasNocturnas($arrHoras['horasExtrasNocturnas']);
                    $arSimulacionDetalleAct->setHorasExtrasFestivasDiurnas($arrHoras['horasExtrasFestivasDiurnas']);
                    $arSimulacionDetalleAct->setHorasExtrasFestivasNocturnas($arrHoras['horasExtrasFestivasNocturnas']);                    
                    $arSimulacionDetalleAct->setHorasRecargoNocturno($arrHoras['horasRecargoNocturno']);
                    $arSimulacionDetalleAct->setHorasRecargoFestivoDiurno($arrHoras['horasRecargoFestivoDiurno']);
                    $arSimulacionDetalleAct->setHorasRecargoFestivoNocturno($arrHoras['horasRecargoFestivoNocturno']);
                    $arSimulacionDetalleAct->setHorasNovedad($arrHoras['horasNovedad']);
                    $arSimulacionDetalleAct->setHorasDescanso($arrHoras['horasDescanso']);
                    $arSimulacionDetalleAct->setHorasPago($arrHoras['horas']);
                    $em->persist($arSimulacionDetalleAct);
                }
                $em->flush();
                
                $dql   = "SELECT sd.codigoRecursoFk, "
                        . "SUM(sd.horasDescanso) as horasDescanso, "
                        . "SUM(sd.horasNovedad) as horasNovedad, "
                        . "SUM(sd.horasDiurnasPago) as horasDiurnas, "
                        . "SUM(sd.horasNocturnasPago) as horasNocturnas, "
                        . "SUM(sd.horasFestivasDiurnas) as horasFestivasDiurnas, "
                        . "SUM(sd.horasFestivasNocturnas) as horasFestivasNocturnas, "                
                        . "SUM(sd.horasExtrasOrdinariasDiurnas) as horasExtrasOrdinariasDiurnas, "
                        . "SUM(sd.horasExtrasOrdinariasNocturnas) as horasExtrasOrdinariasNocturnas, "
                        . "SUM(sd.horasExtrasFestivasDiurnas) as horasExtrasFestivasDiurnas, "
                        . "SUM(sd.horasExtrasFestivasNocturnas) as horasExtrasFestivasNocturnas, "
                        . "SUM(sd.horasRecargoNocturno) as horasRecargoNocturno, "
                        . "SUM(sd.horasRecargoFestivoDiurno) as horasRecargoFestivoDiurno, "
                        . "SUM(sd.horasRecargoFestivoNocturno) as horasRecargoFestivoNocturno "
                        . "FROM BrasaTurnoBundle:TurSimulacionDetalle sd "
                        . "WHERE sd.usuario =  '" . $usuario . "' "
                        . "GROUP BY sd.codigoRecursoFk" ;
                $query = $em->createQuery($dql);
                $arrayResultados = $query->getResult();
                foreach ($arrayResultados as $recurso) {
                    $arSimulacionDetalleRecurso = new \Brasa\TurnoBundle\Entity\TurSimulacionDetalleRecurso();
                    $arSimulacionDetalleRecurso->setCodigoRecursoFk($recurso['codigoRecursoFk']);
                    $arSimulacionDetalleRecurso->setUsuario($usuario); 
                    $arSimulacionDetalleRecurso->setDias(30);
                    $arSimulacionDetalleRecurso->setHorasDiurnas($recurso['horasDiurnas']);
                    $arSimulacionDetalleRecurso->setHorasNocturnas($recurso['horasNocturnas']);
                    $arSimulacionDetalleRecurso->setHorasFestivasDiurnas($recurso['horasFestivasDiurnas']);
                    $arSimulacionDetalleRecurso->setHorasFestivasNocturnas($recurso['horasFestivasNocturnas']);
                    $arSimulacionDetalleRecurso->setHorasExtrasOrdinariasDiurnas($recurso['horasExtrasOrdinariasDiurnas']);
                    $arSimulacionDetalleRecurso->setHorasExtrasOrdinariasNocturnas($recurso['horasExtrasOrdinariasNocturnas']);
                    $arSimulacionDetalleRecurso->setHorasExtrasFestivasDiurnas($recurso['horasExtrasFestivasDiurnas']);
                    $arSimulacionDetalleRecurso->setHorasExtrasFestivasNocturnas($recurso['horasExtrasFestivasNocturnas']);                    
                    $arSimulacionDetalleRecurso->setHorasRecargoNocturno($recurso['horasRecargoNocturno']);
                    $arSimulacionDetalleRecurso->setHorasRecargoFestivoDiurno($recurso['horasRecargoFestivoDiurno']);
                    $arSimulacionDetalleRecurso->setHorasRecargoFestivoNocturno($recurso['horasRecargoFestivoNocturno']);
                                       
                    $descanso = 4;
                    $diasPeriodo = 30;
                    $horasPeriodo =  $diasPeriodo * 8;
                    $horasDescanso = $descanso * 8;                
                    $horasTope = $horasPeriodo - $horasDescanso;                                   
                                
                    $horasDescansoSoportePago = $descanso * 8;
                    $horasDia = $arSimulacionDetalleRecurso->getHorasDiurnas();
                    $horasNoche = $arSimulacionDetalleRecurso->getHorasNocturnas();
                    $horasFestivasDia = $arSimulacionDetalleRecurso->getHorasFestivasDiurnas();
                    $horasFestivasNoche = $arSimulacionDetalleRecurso->getHorasFestivasNocturnas();
                    $horasExtraDia = $arSimulacionDetalleRecurso->getHorasExtrasOrdinariasDiurnas();                    
                    $horasExtraNoche = $arSimulacionDetalleRecurso->getHorasExtrasOrdinariasNocturnas();
                    $horasExtraFestivasDia = $arSimulacionDetalleRecurso->getHorasExtrasFestivasDiurnas();                    
                    $horasExtraFestivasNoche = $arSimulacionDetalleRecurso->getHorasExtrasFestivasNocturnas();                    
                    $totalHoras = $horasDia + $horasNoche + $horasFestivasDia + $horasFestivasNoche;
                    $horasPorCompensar = $horasTope - $totalHoras;
                    $totalExtras = $horasExtraDia + $horasExtraNoche + $horasExtraFestivasDia + $horasExtraFestivasNoche;
                    if($horasPorCompensar > $totalExtras) {
                        $horasPorCompensar = $totalExtras;
                    }
                    $porExtraDiurna = 0;
                    $porExtraNocturna = 0;
                    $porExtraFestivaDiurna = 0;
                    $porExtraFestivaNocturna = 0; 
                    if($totalExtras > 0) {
                        $porExtraDiurna = $horasExtraDia / $totalExtras;
                        $porExtraNocturna = $horasExtraNoche / $totalExtras;
                        $porExtraFestivaDiurna = $horasExtraFestivasDia / $totalExtras;
                        $porExtraFestivaNocturna = $horasExtraFestivasNoche / $totalExtras;
                    }

                    $horasCompensarDia = round($porExtraDiurna * $horasPorCompensar);
                    $horasCompensarNoche = round($porExtraNocturna * $horasPorCompensar);
                    $horasCompensarFestivaDia = round($porExtraFestivaDiurna * $horasPorCompensar);
                    $horasCompensarFestivaNoche = round($porExtraFestivaNocturna * $horasPorCompensar);                    
                    //Para tema de redondeo
                    $horasCompensadas = $horasCompensarDia + $horasCompensarNoche + $horasCompensarFestivaDia + $horasCompensarFestivaNoche;
                    if($horasCompensadas > $horasPorCompensar) {
                        $horasCompensarFestivaNoche -= 1;
                    }

                    $horasDia += $horasCompensarDia;
                    $horasNoche += $horasCompensarNoche;
                    $horasFestivasDia += $horasCompensarFestivaDia;
                    $horasFestivasNoche += $horasCompensarFestivaNoche;
                    $horasExtraDia -= $horasCompensarDia;                    
                    $horasExtraNoche -= $horasCompensarNoche;
                    $horasExtraFestivasDia -= $horasCompensarFestivaDia;                    
                    $horasExtraFestivasNoche -= $horasCompensarFestivaNoche;                    

                    $arSimulacionDetalleRecurso->setHorasDiurnas($horasDia);
                    $arSimulacionDetalleRecurso->setHorasNocturnas($horasNoche);
                    $arSimulacionDetalleRecurso->setHorasFestivasDiurnas($horasFestivasDia);
                    $arSimulacionDetalleRecurso->setHorasFestivasNocturnas($horasFestivasNoche);
                    $arSimulacionDetalleRecurso->setHorasExtrasOrdinariasDiurnas($horasExtraDia);
                    $arSimulacionDetalleRecurso->setHorasExtrasOrdinariasNocturnas($horasExtraNoche);
                    $arSimulacionDetalleRecurso->setHorasExtrasFestivasDiurnas($horasExtraFestivasDia);
                    $arSimulacionDetalleRecurso->setHorasExtrasFestivasNocturnas($horasExtraFestivasNoche);                                        
                    $arSimulacionDetalleRecurso->setHorasDescanso($horasDescansoSoportePago);                    
                    $horas = $horasDia + $horasNoche + $horasFestivasDia + $horasFestivasNoche + $horasDescansoSoportePago;            
                    $arSimulacionDetalleRecurso->setHoras($horas);                           
                    
                    $diaAuxilioTransporte = 77700 / 30;                    
                    $porNocturna = 135;
                    $porFestivaDiurna = 175;
                    $porFestivaNocturna = 210;
                    $porExtraOrdinariaDiurna = 125;
                    $porExtraOrdinariaNocturna = 175;        
                    $porExtraFestivaDiurna = 200;
                    $porExtraFestivaNocturna = 250;                     
                    $porRecargoNocturno = 35;
                    $porRecargoFestivoDiurno = 75;
                    $porRecargoFestivoNocturno = 110;  
                    $salario = 689455;
                    $vrDia = $salario / 30;
                    $vrHora = $vrDia / 8;
                    $vrDiurna = $vrHora * $horasDia;
                    $vrNocturna = (($vrHora * $porNocturna)/100) * $horasNoche;
                    $vrDescanso = $vrHora * $horasDescansoSoportePago;
                    $vrFestivaDiurna = (($vrHora * $porFestivaDiurna)/100) * $horasFestivasDia;
                    $vrFestivaNocturna = (($vrHora * $porFestivaNocturna)/100) * $horasFestivasNoche;
                    $vrExtraOrdinariaDiurna = (($vrHora * $porExtraOrdinariaDiurna)/100) * $horasExtraDia;
                    $vrExtraOrdinariaNocturna = (($vrHora * $porExtraOrdinariaNocturna)/100) * $horasExtraNoche;                        
                    $vrExtraFestivaDiurna = (($vrHora * $porExtraFestivaDiurna)/100) * $horasExtraFestivasDia;
                    $vrExtraFestivaNocturna = (($vrHora * $porExtraFestivaNocturna)/100) * $horasExtraFestivasNoche;            
                    $vrRecargoNocturno = (($vrHora * $porRecargoNocturno)/100) * $arSimulacionDetalleRecurso->getHorasRecargoNocturno();            
                    $vrRecargoFestivoDiurno = (($vrHora * $porRecargoFestivoDiurno)/100) * $arSimulacionDetalleRecurso->getHorasRecargoFestivoDiurno();            
                    $vrRecargoFestivoNocturno = (($vrHora * $porRecargoFestivoNocturno)/100) * $arSimulacionDetalleRecurso->getHorasRecargoFestivoNocturno();            
                    
                    $arSimulacionDetalleRecurso->setVrDiurnas($vrDiurna);
                    $arSimulacionDetalleRecurso->setVrDescanso($vrDescanso);
                    $arSimulacionDetalleRecurso->setVrNocturnas($vrNocturna);
                    $arSimulacionDetalleRecurso->setVrFestivasDiurnas($vrFestivaDiurna);
                    $arSimulacionDetalleRecurso->setVrFestivasNocturnas($vrFestivaNocturna);
                    $arSimulacionDetalleRecurso->setVrExtrasOrdinariasDiurnas($vrExtraOrdinariaDiurna);
                    $arSimulacionDetalleRecurso->setVrExtrasOrdinariasNocturnas($vrExtraOrdinariaNocturna);
                    $arSimulacionDetalleRecurso->setVrExtrasFestivasDiurnas($vrExtraFestivaDiurna);
                    $arSimulacionDetalleRecurso->setVrExtrasFestivasNocturnas($vrExtraFestivaNocturna);                                        
                    $arSimulacionDetalleRecurso->setVrRecargoNocturno($vrRecargoNocturno);
                    $arSimulacionDetalleRecurso->setVrRecargoFestivoDiurno($vrRecargoFestivoDiurno);
                    $arSimulacionDetalleRecurso->setVrRecargoFestivoNocturno($vrRecargoFestivoNocturno);
                    
                    $vrAuxilioTransporte = $diaAuxilioTransporte * $diasPeriodo;
                    $vrPago = $vrDiurna + $vrNocturna + $vrDescanso + $vrFestivaDiurna + $vrFestivaNocturna + $vrExtraOrdinariaDiurna + $vrExtraOrdinariaNocturna + $vrExtraFestivaDiurna + $vrExtraFestivaNocturna+$vrRecargoNocturno+$vrRecargoFestivoDiurno+$vrRecargoFestivoNocturno;
                    $vrDevengado = $vrPago + $vrAuxilioTransporte;                    
                    $arSimulacionDetalleRecurso->setVrDevengado($vrDevengado);
                    $em->persist($arSimulacionDetalleRecurso);                     
                    
                }
                $em->flush();
            }            
        }                 
        $strAnioMes = $fechaProgramacion->format('Y/m');
        $arrDiaSemana = array();
        for($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $boolFestivo = 0;
            if($diaSemana == 'd') {
                $boolFestivo = 1;
            }
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana, 'festivo' => $boolFestivo);
        }        
        //$dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionInconsistencia')->listaDql();
        //$arProgramacionInconsistencias = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 200);
        $arSimulacionDetalle = $em->getRepository('BrasaTurnoBundle:TurSimulacionDetalle')->findBy(array('usuario' => $usuario));
        return $this->render('BrasaTurnoBundle:Utilidades/Simular:programacion.html.twig', array(            
            'arSimulacionDetalle' => $arSimulacionDetalle,
            'arrDiaSemana' => $arrDiaSemana,
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {                
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);         
        $form = $this->createFormBuilder()                        
            ->add('fecha', DateType::class, array('data' => $arConfiguracion->getFechaUltimaSimulacion(), 'format' => 'yyyyMMdd'))                            
            ->add('BtnGenerarSoportePago', SubmitType::class, array('label'  => 'Generar soporte pago'))       
            ->add('BtnGenerar', SubmitType::class, array('label'  => 'Generar'))       
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))       
            ->add('BtnPdf', SubmitType::class, array('label'  => 'PDF'))       
            ->getForm();        
        return $form;
    }           

    private function devuelveDiaSemanaEspaniol ($dateFecha) {
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
    
    private function generarExcel() {
        ob_clean();
        $usuario = $this->getUser()->getUserName();
        $em = $this->getDoctrine()->getManager();        
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'AN'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }                   
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ANIO')
                    ->setCellValue('B1', 'MES')
                    ->setCellValue('C1', 'PUESTO')
                    ->setCellValue('D1', 'RECURSO')
                    ->setCellValue('E1', 'D1')
                    ->setCellValue('F1', 'D2')
                    ->setCellValue('G1', 'D3')
                    ->setCellValue('H1', 'D4')
                    ->setCellValue('I1', 'D5')
                    ->setCellValue('J1', 'D6')
                    ->setCellValue('K1', 'D7')
                    ->setCellValue('L1', 'D8')
                    ->setCellValue('M1', 'D9')
                    ->setCellValue('N1', 'D10')
                    ->setCellValue('O1', 'D11')
                    ->setCellValue('P1', 'D12')
                    ->setCellValue('Q1', 'D13')
                    ->setCellValue('R1', 'D14')
                    ->setCellValue('S1', 'D15')
                    ->setCellValue('T1', 'D16')
                    ->setCellValue('U1', 'D17')
                    ->setCellValue('V1', 'D18')
                    ->setCellValue('W1', 'D19')
                    ->setCellValue('X1', 'D20')
                    ->setCellValue('Y1', 'D21')
                    ->setCellValue('Z1', 'D22')
                    ->setCellValue('AA1', 'D23')
                    ->setCellValue('AB1', 'D24')
                    ->setCellValue('AC1', 'D25')
                    ->setCellValue('AD1', 'D26')
                    ->setCellValue('AE1', 'D27')
                    ->setCellValue('AF1', 'D28')
                    ->setCellValue('AG1', 'D29')
                    ->setCellValue('AH1', 'D30')
                    ->setCellValue('AI1', 'D31');
        
        $i = 2;        
        $dql = $em->getRepository('BrasaTurnoBundle:TurSimulacionDetalle')->listaDql($usuario);
        $query = $em->createQuery($dql);
        $arSimulacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arSimulacionDetalles = $query->getResult();        
        foreach ($arSimulacionDetalles as $arSimulacionDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSimulacionDetalle->getAnio())
                    ->setCellValue('B' . $i, $arSimulacionDetalle->getMes())
                    ->setCellValue('E' . $i, $arSimulacionDetalle->getDia1())
                    ->setCellValue('F' . $i, $arSimulacionDetalle->getDia2())
                    ->setCellValue('G' . $i, $arSimulacionDetalle->getDia3())
                    ->setCellValue('H' . $i, $arSimulacionDetalle->getDia4())
                    ->setCellValue('I' . $i, $arSimulacionDetalle->getDia5())
                    ->setCellValue('J' . $i, $arSimulacionDetalle->getDia6())
                    ->setCellValue('K' . $i, $arSimulacionDetalle->getDia7())
                    ->setCellValue('L' . $i, $arSimulacionDetalle->getDia8())
                    ->setCellValue('M' . $i, $arSimulacionDetalle->getDia9())
                    ->setCellValue('N' . $i, $arSimulacionDetalle->getDia10())
                    ->setCellValue('O' . $i, $arSimulacionDetalle->getDia11())
                    ->setCellValue('P' . $i, $arSimulacionDetalle->getDia12())
                    ->setCellValue('Q' . $i, $arSimulacionDetalle->getDia13())
                    ->setCellValue('R' . $i, $arSimulacionDetalle->getDia14())
                    ->setCellValue('S' . $i, $arSimulacionDetalle->getDia15())
                    ->setCellValue('T' . $i, $arSimulacionDetalle->getDia16())
                    ->setCellValue('U' . $i, $arSimulacionDetalle->getDia17())
                    ->setCellValue('V' . $i, $arSimulacionDetalle->getDia18())
                    ->setCellValue('W' . $i, $arSimulacionDetalle->getDia19())
                    ->setCellValue('X' . $i, $arSimulacionDetalle->getDia20())
                    ->setCellValue('Y' . $i, $arSimulacionDetalle->getDia21())
                    ->setCellValue('Z' . $i, $arSimulacionDetalle->getDia22())
                    ->setCellValue('AA' . $i, $arSimulacionDetalle->getDia23())
                    ->setCellValue('AB' . $i, $arSimulacionDetalle->getDia24())
                    ->setCellValue('AC' . $i, $arSimulacionDetalle->getDia25())
                    ->setCellValue('AD' . $i, $arSimulacionDetalle->getDia26())
                    ->setCellValue('AE' . $i, $arSimulacionDetalle->getDia27())
                    ->setCellValue('AF' . $i, $arSimulacionDetalle->getDia28())
                    ->setCellValue('AG' . $i, $arSimulacionDetalle->getDia29())
                    ->setCellValue('AH' . $i, $arSimulacionDetalle->getDia30())
                    ->setCellValue('AI' . $i, $arSimulacionDetalle->getDia31());  
            
            if($arSimulacionDetalle->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C' . $i, $arSimulacionDetalle->getPuestoRel()->getNombre());
            }
            if($arSimulacionDetalle->getRecursoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arSimulacionDetalle->getRecursoRel()->getNombreCorto());
            }
            
            $i++;
        }
        $intNum = count($arSimulacionDetalles);
        $intNum += 1;                        
        $objPHPExcel->getActiveSheet()->setTitle('SimulacionDetalle');
        
        $objPHPExcel->createSheet(1)->setTitle('SoportePago')
                ->setCellValue('A1', 'CODIGO')
                ->setCellValue('B1', 'DOCUMENTO')
                ->setCellValue('C1', 'EMPLEADO')
                ->setCellValue('D1', 'DIAS')
                ->setCellValue('E1', 'HORAS')
                ->setCellValue('F1', 'HDS')
                ->setCellValue('G1', 'HD')
                ->setCellValue('H1', 'HN')
                ->setCellValue('I1', 'FD')
                ->setCellValue('J1', 'FN')
                ->setCellValue('K1', 'ED')
                ->setCellValue('L1', 'EN')
                ->setCellValue('M1', 'EFD')
                ->setCellValue('N1', 'EFN')
                ->setCellValue('O1', 'RN')
                ->setCellValue('P1', 'RFD')
                ->setCellValue('Q1', 'RFN')
                ->setCellValue('R1', 'VR.HDS')
                ->setCellValue('S1', 'VR.HD')
                ->setCellValue('T1', 'VR.HN')
                ->setCellValue('U1', 'VR.FD')
                ->setCellValue('V1', 'VR.FN')
                ->setCellValue('W1', 'VR.ED')
                ->setCellValue('X1', 'VR.EN')
                ->setCellValue('Y1', 'VR.EFD')
                ->setCellValue('Z1', 'VR.EFN')
                ->setCellValue('AA1', 'VR.RN')
                ->setCellValue('AB1', 'VR.RFD')
                ->setCellValue('AC1', 'VR.RFN')
                ->setCellValue('AD1', 'VR.AUX.TRANS')
                ->setCellValue('AE1', 'VR.DEVENGADO');

        $objPHPExcel->setActiveSheetIndex(1); 
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet(1)->getStyle('1')->getFont()->setBold(true);     
        for($col = 'A'; $col !== 'AF'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                  
        }            
        for($col = 'D'; $col !== 'AF'; $col++) { 
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');                 
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }             

        $i = 2;
        $dql = $em->getRepository('BrasaTurnoBundle:TurSimulacionDetalleRecurso')->listaDql($usuario);
        $query = $em->createQuery($dql);
        $arSimulacionDetallesRecursos = new \Brasa\TurnoBundle\Entity\TurSimulacionDetalleRecurso();
        $arSimulacionDetallesRecursos = $query->getResult(); 
        foreach ($arSimulacionDetallesRecursos as $arSimulacionDetalleRecurso) {
            $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arSimulacionDetalleRecurso->getCodigoRecursoFk());
            $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('A' . $i, $arRecurso->getCodigoEmpleadoFk())
                    ->setCellValue('B' . $i, $arRecurso->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arRecurso->getNombreCorto())
                    ->setCellValue('D' . $i, $arSimulacionDetalleRecurso->getDias())
                    ->setCellValue('E' . $i, $arSimulacionDetalleRecurso->getHoras())
                    ->setCellValue('F' . $i, $arSimulacionDetalleRecurso->getHorasDescanso())
                    ->setCellValue('G' . $i, $arSimulacionDetalleRecurso->getHorasDiurnas())
                    ->setCellValue('H' . $i, $arSimulacionDetalleRecurso->getHorasNocturnas())
                    ->setCellValue('I' . $i, $arSimulacionDetalleRecurso->getHorasFestivasDiurnas())
                    ->setCellValue('J' . $i, $arSimulacionDetalleRecurso->getHorasFestivasNocturnas())
                    ->setCellValue('K' . $i, $arSimulacionDetalleRecurso->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('L' . $i, $arSimulacionDetalleRecurso->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('M' . $i, $arSimulacionDetalleRecurso->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('N' . $i, $arSimulacionDetalleRecurso->getHorasExtrasFestivasNocturnas())
                    ->setCellValue('O' . $i, $arSimulacionDetalleRecurso->getHorasRecargoNocturno())
                    ->setCellValue('P' . $i, $arSimulacionDetalleRecurso->getHorasRecargoFestivoDiurno())
                    ->setCellValue('Q' . $i, $arSimulacionDetalleRecurso->getHorasRecargoFestivoNocturno())
                    ->setCellValue('R' . $i, $arSimulacionDetalleRecurso->getVrDescanso())
                    ->setCellValue('S' . $i, $arSimulacionDetalleRecurso->getVrDiurnas())
                    ->setCellValue('T' . $i, $arSimulacionDetalleRecurso->getVrNocturnas())
                    ->setCellValue('U' . $i, $arSimulacionDetalleRecurso->getVrFestivasDiurnas())
                    ->setCellValue('V' . $i, $arSimulacionDetalleRecurso->getVrFestivasNocturnas())
                    ->setCellValue('W' . $i, $arSimulacionDetalleRecurso->getVrExtrasOrdinariasDiurnas())
                    ->setCellValue('X' . $i, $arSimulacionDetalleRecurso->getVrExtrasOrdinariasNocturnas())
                    ->setCellValue('Y' . $i, $arSimulacionDetalleRecurso->getVrExtrasFestivasDiurnas())
                    ->setCellValue('Z' . $i, $arSimulacionDetalleRecurso->getVrExtrasFestivasNocturnas())
                    ->setCellValue('AA' . $i, $arSimulacionDetalleRecurso->getVrRecargoNocturno())
                    ->setCellValue('AB' . $i, $arSimulacionDetalleRecurso->getVrRecargoFestivoDiurno())
                    ->setCellValue('AC' . $i, $arSimulacionDetalleRecurso->getVrRecargoFestivoNocturno())
                    ->setCellValue('AD' . $i, $arSimulacionDetalleRecurso->getVrAuxilioTransporte())
                    ->setCellValue('AE' . $i, $arSimulacionDetalleRecurso->getVrDevengado());
            
            $i++;
        }         
        
        
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ProgramacionDetalle.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }          
 
    public function devolverHorasTurno ($codigoTurno, $dateFecha, $dateFecha2, $boolFestivo, $boolFestivo2) {        
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $em = $this->getDoctrine()->getManager();                              
        $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
        $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigoTurno);        
        
        $intDias = 0;                       
        $intMinutoInicio = (($arTurno->getHoraDesde()->format('i') * 100)/60)/100;
        $intHoraInicio = $arTurno->getHoraDesde()->format('G');        
        $intHoraInicio += $intMinutoInicio;
        $intMinutoFinal = (($arTurno->getHoraHasta()->format('i') * 100)/60)/100;
        $intHoraFinal = $arTurno->getHoraHasta()->format('G');
        $intHoraFinal += $intMinutoFinal;
        $diaSemana = $dateFecha->format('N');
        $diaSemana2 = $dateFecha2->format('N');
        if($arTurno->getNovedad() == 0) {
            $intDias += 1;
        }                    
        if($diaSemana == 7) {
            $boolFestivo = 1;
        }
        if($diaSemana2 == 7) {
            $boolFestivo2 = 1;
        }        
        $arrHoras1 = null;
        if(($intHoraInicio + $intMinutoInicio) <= $intHoraFinal){  
            $arrHoras = $objFunciones->turnoHoras($intHoraInicio, $intMinutoInicio, $intHoraFinal, $boolFestivo, 0, $arTurno->getNovedad(), $arTurno->getDescanso());
        } else {
            $arrHoras = $objFunciones->turnoHoras($intHoraInicio, $intMinutoInicio, 24, $boolFestivo, 0, $arTurno->getNovedad(), $arTurno->getDescanso());
            $arrHoras1 = $objFunciones->turnoHoras(0, 0, $intHoraFinal, $boolFestivo2, $arrHoras['horas'], $arTurno->getNovedad(), $arTurno->getDescanso());                 
        }        
        if($arrHoras1) {            
            $arrHoras['horasDiurnas'] += $arrHoras1['horasDiurnas'];
            $arrHoras['horasNocturnas'] += $arrHoras1['horasNocturnas'];
            $arrHoras['horasExtrasDiurnas'] += $arrHoras1['horasExtrasDiurnas'];
            $arrHoras['horasExtrasNocturnas'] += $arrHoras1['horasExtrasNocturnas'];
            $arrHoras['horasFestivasDiurnas'] += $arrHoras1['horasFestivasDiurnas'];
            $arrHoras['horasFestivasNocturnas'] += $arrHoras1['horasFestivasNocturnas'];
            $arrHoras['horasExtrasFestivasDiurnas'] += $arrHoras1['horasExtrasFestivasDiurnas'];
            $arrHoras['horasExtrasFestivasNocturnas'] += $arrHoras1['horasExtrasFestivasNocturnas'];
            $arrHoras['horasDescanso'] += $arrHoras1['horasDescanso'];
            $arrHoras['horasNovedad'] += $arrHoras1['horasNovedad'];  
            $arrHoras['horas'] += $arrHoras1['horas'];  
        }
        if($dateFecha->format('d') == 31) {                   
            $arrHoras['horasRecargoNocturno'] = $arrHoras['horasNocturnas'];
            $arrHoras['horasRecargoFestivoDiurno'] = $arrHoras['horasFestivasDiurnas'];
            $arrHoras['horasRecargoFestivoNocturno'] = $arrHoras['horasFestivasNocturnas'];
            $arrHoras['horasDiurnas'] = 0;
            $arrHoras['horasNocturnas'] = 0;
            $arrHoras['horasFestivasDiurnas'] = 0;
            $arrHoras['horasFestivasNocturnas'] = 0;
        }        
        return $arrHoras;
    }        
    
}
