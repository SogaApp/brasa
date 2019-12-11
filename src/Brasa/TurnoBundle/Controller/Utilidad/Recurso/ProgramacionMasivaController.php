<?php

namespace Brasa\TurnoBundle\Controller\Utilidad\Recurso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProgramacionMasivaController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/utilidad/recurso/programacion/masiva/{anio}/{mes}/{codigoRecurso}", name="brs_tur_utilidad_recurso_programacion_masiva")
     */    
    public function detalleAction(Request $request, $anio, $mes, $codigoRecurso) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
        $form = $this->formularioDetalleEditar();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGuardar')->isClicked()) {                
                $arrControles = $request->request->All();
                $resultado = $this->actualizarDetalle($arrControles); 
                if($resultado == false) {
                    $em->flush();                        
                    echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";                                
                }                    
            }
        }
        $strAnioMes = $anio."/".$mes;
        $arrDiaSemana = array();
        for($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana);
        }        
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array ('anio' => $anio, 'mes' => $mes, 'codigoRecursoFk' => $codigoRecurso));                            
        return $this->render('BrasaTurnoBundle:Utilidades/Recurso:detalle.html.twig', array(
                    'arProgramacionDetalle' => $arProgramacionDetalle,
                    'arRecurso' => $arRecurso,
                    'arrDiaSemana' => $arrDiaSemana,
                    'form' => $form->createView(),                    
                    ));
    }
    
    private function formularioDetalleEditar() {
        $form = $this->createFormBuilder(array(), array('csrf_protection' => false))                    
                    ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
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
    
    private function actualizarDetalle ($arrControles) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $error = false;
        $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $validarHoras = $arConfiguracion->getValidarHorasProgramacion();
        $intIndice = 0;
        $boolTurnosSobrepasados = false;
        foreach ($arrControles['LblCodigo'] as $intCodigo) {
            $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
            $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($intCodigo);
            $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
            $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arProgramacionDetalle->getCodigoPedidoDetalleFk());                                                   
            $validar = $this->validarHoras($intCodigo, $arrControles);             
            if($validar['validado']) {
                $horasDiurnasPendientes = $arPedidoDetalle->getHorasDiurnas() - ($arPedidoDetalle->getHorasDiurnasProgramadas() - $arProgramacionDetalle->getHorasDiurnas());
                $horasNocturnasPendientes = $arPedidoDetalle->getHorasNocturnas() - ($arPedidoDetalle->getHorasNocturnasProgramadas() - $arProgramacionDetalle->getHorasNocturnas());
                if($horasDiurnasPendientes >= $validar['horasDiurnas'] || $validarHoras == false ) {
                    if($horasNocturnasPendientes >= $validar['horasNocturnas'] || $validarHoras == false) {
                        $horasDiurnasProgramadas = ($arPedidoDetalle->getHorasDiurnasProgramadas() - $arProgramacionDetalle->getHorasDiurnas()) + $validar['horasDiurnas'];                
                        $horasNocturnasProgramadas = ($arPedidoDetalle->getHorasNocturnasProgramadas() - $arProgramacionDetalle->getHorasNocturnas()) + $validar['horasNocturnas'];                
                        $horasProgramadas = $horasDiurnasProgramadas + $horasNocturnasProgramadas;
                        $arPedidoDetalle->setHorasDiurnasProgramadas($horasDiurnasProgramadas);
                        $arPedidoDetalle->setHorasNocturnasProgramadas($horasNocturnasProgramadas);
                        $arPedidoDetalle->setHorasProgramadas($horasProgramadas);                                          
                        $em->persist($arPedidoDetalle);                                                                                       

                        if($arrControles['TxtDia01D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia1($arrControles['TxtDia01D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia1(null);
                        }                                    
                        if($arrControles['TxtDia02D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia2($arrControles['TxtDia02D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia2(null);
                        }
                        if($arrControles['TxtDia03D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia3($arrControles['TxtDia03D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia3(null);
                        }
                        if($arrControles['TxtDia04D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia4($arrControles['TxtDia04D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia4(null);
                        }
                        if($arrControles['TxtDia05D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia5($arrControles['TxtDia05D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia5(null);
                        }
                        if($arrControles['TxtDia06D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia6($arrControles['TxtDia06D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia6(null);
                        }
                        if($arrControles['TxtDia07D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia7($arrControles['TxtDia07D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia7(null);
                        }
                        if($arrControles['TxtDia08D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia8($arrControles['TxtDia08D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia8(null);
                        }
                        if($arrControles['TxtDia09D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia9($arrControles['TxtDia09D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia9(null);
                        }
                        if($arrControles['TxtDia10D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia10($arrControles['TxtDia10D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia10(null);
                        }
                        if($arrControles['TxtDia11D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia11($arrControles['TxtDia11D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia11(null);
                        }
                        if($arrControles['TxtDia12D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia12($arrControles['TxtDia12D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia12(null);
                        }
                        if($arrControles['TxtDia13D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia13($arrControles['TxtDia13D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia13(null);
                        }
                        if($arrControles['TxtDia14D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia14($arrControles['TxtDia14D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia14(null);
                        }
                        if($arrControles['TxtDia15D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia15($arrControles['TxtDia15D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia15(null);
                        }
                        if($arrControles['TxtDia16D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia16($arrControles['TxtDia16D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia16(null);
                        }
                        if($arrControles['TxtDia17D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia17($arrControles['TxtDia17D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia17(null);
                        }
                        if($arrControles['TxtDia18D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia18($arrControles['TxtDia18D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia18(null);
                        }
                        if($arrControles['TxtDia19D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia19($arrControles['TxtDia19D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia19(null);
                        }
                        if($arrControles['TxtDia20D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia20($arrControles['TxtDia20D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia20(null);
                        }
                        if($arrControles['TxtDia21D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia21($arrControles['TxtDia21D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia21(null);
                        }
                        if($arrControles['TxtDia22D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia22($arrControles['TxtDia22D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia22(null);
                        }
                        if($arrControles['TxtDia23D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia23($arrControles['TxtDia23D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia23(null);
                        }
                        if($arrControles['TxtDia24D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia24($arrControles['TxtDia24D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia24(null);
                        }
                        if($arrControles['TxtDia25D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia25($arrControles['TxtDia25D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia25(null);
                        }
                        if($arrControles['TxtDia26D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia26($arrControles['TxtDia26D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia26(null);
                        }
                        if($arrControles['TxtDia27D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia27($arrControles['TxtDia27D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia27(null);
                        }
                        if($arrControles['TxtDia28D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia28($arrControles['TxtDia28D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia28(null);
                        }
                        if($arrControles['TxtDia29D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia29($arrControles['TxtDia29D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia29(null);
                        }
                        if($arrControles['TxtDia30D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia30($arrControles['TxtDia30D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia30(null);
                        }
                        if($arrControles['TxtDia31D'.$intCodigo] != '') {
                            $arProgramacionDetalle->setDia31($arrControles['TxtDia31D'.$intCodigo]);                                            
                        } else {
                            $arProgramacionDetalle->setDia31(null);
                        }                        
                        $arProgramacionDetalle->setHorasDiurnas($validar['horasDiurnas']);
                        $arProgramacionDetalle->setHorasNocturnas($validar['horasNocturnas']);
                        $arProgramacionDetalle->setHoras($validar['horasDiurnas']+$validar['horasNocturnas']);
                        $em->persist($arProgramacionDetalle);        
                    } else {
                        $error = true;
                        $objMensaje->Mensaje("error", "Horas nocturnas superan las horas del pedido disponibles para programar detalle " . $intCodigo, $this);
                    }
                } else {
                    $error = true;
                    $objMensaje->Mensaje("error", "Horas diurnas superan las horas del pedido disponibles para programar detalle " . $intCodigo, $this);                
                }                
            } else {
                $error = true;
                $objMensaje->Mensaje("error", $validar['mensaje'], $this);                
            }
            if($error) {
                break;
            }
        }           
        return $error;
    }

    private function validarTurno($strTurno) {
        $em = $this->getDoctrine()->getManager();        
        $arrTurno = array('turno' => null, 'horasDiurnas' => 0, 'horasNocturnas' => 0, 'errado' => false);
        if($strTurno != "") {
            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurno);
            if($arTurno) {
                $arrTurno['turno'] = $strTurno;
                $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas();
            } else {
                $arrTurno['errado'] = true;
            } 
        }

        return $arrTurno;
    }
    
    private function validarHoras($codigoProgramacionDetalle, $arrControles) {        
        $arrDetalle = array('validado' => true, 'horasDiurnas' => 0, 'horasNocturnas' => 0, 'mensaje' => '');
        $horasDiurnas = 0;
        $horasNocturnas = 0;
        for($i=1; $i<=31; $i++) {
            $dia = $i;
            if(strlen($dia) < 2) {
                $dia = "0" . $i;
            }
            if($arrControles['TxtDia'.$dia.'D'.$codigoProgramacionDetalle] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia'.$dia.'D'.$codigoProgramacionDetalle]);                                        
                if($arrTurno['errado'] == true) {
                    $arrDetalle['validado'] = false;
                    $arrDetalle['mensaje'] = "Turno " . $arrControles['TxtDia'.$dia.'D'.$codigoProgramacionDetalle] . " no esta creado";
                    break;
                }
                $horasDiurnas += $arrTurno['horasDiurnas'];
                $horasNocturnas += $arrTurno['horasNocturnas'];                        
            }            
        }
        $arrDetalle['horasDiurnas'] = $horasDiurnas;
        $arrDetalle['horasNocturnas'] = $horasNocturnas;
        return $arrDetalle;
    }   
    
}
