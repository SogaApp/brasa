<?php
namespace Brasa\TurnoBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class CierreMesController extends Controller
{
    /**
     * @Route("/tur/proceso/cierre/mes", name="brs_tur_proceso_cierre_mes")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 8)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioGenerar();
        $form->handleRequest($request);        
        if ($form->isValid()) {            
            if($request->request->get('OpGenerar')) { 
                set_time_limit(0);
                ini_set("memory_limit", -1);                
                $codigoCierreMes = $request->request->get('OpGenerar');
                $arCierreMes = new \Brasa\TurnoBundle\Entity\TurCierreMes();
                $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigoCierreMes);
                $strSql = "DELETE FROM tur_cierre_mes_servicio WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;           
                $em->getConnection()->executeQuery($strSql); 
                $strSql = "DELETE FROM tur_recurso_puesto WHERE anio = " . $arCierreMes->getAnio() . " AND mes = " . $arCierreMes->getMes();           
                $em->getConnection()->executeQuery($strSql);                                
                $strSql = "DELETE FROM rhu_empleado_centro_costo WHERE anio = " . $arCierreMes->getAnio() . " AND mes = " . $arCierreMes->getMes();           
                $em->getConnection()->executeQuery($strSql);                 
                
                $strUltimoDiaMes = date("d",(mktime(0,0,0,$arCierreMes->getMes()+1,1,$arCierreMes->getAnio())-1));
                $strFechaDesde = $arCierreMes->getAnio() . "/" . $arCierreMes->getMes() . "/01";
                $strFechaHasta = $arCierreMes->getAnio() . "/" . $arCierreMes->getMes() . "/" . $strUltimoDiaMes;
                
                //Recursos que tuvieron programacion en el periodo de cierre                
                $arrRecursos = $em->getRepository('BrasaTurnoBundle:TurRecurso')->programacionFecha($arCierreMes->getAnio(), $arCierreMes->getMes(), "2220");
                foreach ($arrRecursos as $arrRecurso) {
                    $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                    $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arrRecurso['codigo_recurso_fk']);
                    $devengado = 0;
                    $arrPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->pagoDevengadoFecha($strFechaDesde, $strFechaHasta, $arrRecurso['codigo_empleado_fk']);                    
                    if($arrPagos) {
                        $devengado = $arrPagos[0]['vrDevengado'];                            
                    }                        
                    $arProvision = new \Brasa\RecursoHumanoBundle\Entity\RhuProvision();
                    $arProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvision')->findOneBy(array('codigoEmpleadoFk' => $arRecurso->getCodigoEmpleadoFk(), 'anio' => $arCierreMes->getAnio(), 'mes' => $arCierreMes->getMes()));
                    $prestaciones = 0;
                    $seguridadSocial = 0;
                    if($arProvision) {
                        $prestaciones = $arProvision->getVrCesantias() + $arProvision->getVrInteresesCesantias() + $arProvision->getVrPrimas() + $arProvision->getVrVacaciones() + $arProvision->getVrIndemnizacion();
                        $seguridadSocial = $arProvision->getVrPension() + $arProvision->getVrSalud() + $arProvision->getVrCaja() + $arProvision->getVrRiesgos() + $arProvision->getVrSena() + $arProvision->getVrIcbf();
                    }
                    $costoRecurso = $devengado + $prestaciones + $seguridadSocial;
                    $dql   = "SELECT spd.codigoPedidoDetalleFk, "                                
                            . "SUM(spd.horasDescanso) as horasDescanso, "                                
                            . "SUM(spd.horasDiurnas) as horasDiurnas, "
                            . "SUM(spd.horasNocturnas) as horasNocturnas, "
                            . "SUM(spd.horasFestivasDiurnas) as horasFestivasDiurnas, "
                            . "SUM(spd.horasFestivasNocturnas) as horasFestivasNocturnas, "                
                            . "SUM(spd.horasExtrasOrdinariasDiurnas) as horasExtrasOrdinariasDiurnas, "
                            . "SUM(spd.horasExtrasOrdinariasNocturnas) as horasExtrasOrdinariasNocturnas, "
                            . "SUM(spd.horasExtrasFestivasDiurnas) as horasExtrasFestivasDiurnas, "
                            . "SUM(spd.horasExtrasFestivasNocturnas) as horasExtrasFestivasNocturnas, "
                            . "SUM(spd.horasRecargoNocturno) as horasRecargoNocturno, "
                            . "SUM(spd.horasRecargoFestivoDiurno) as horasRecargoFestivoDiurno, "
                            . "SUM(spd.horasRecargoFestivoNocturno) as horasRecargoFestivoNocturno, "
                            . "SUM(spd.horasDescanso)*100 as pDS, "                                
                            . "SUM(spd.horasDiurnas)*100 as pD, "
                            . "SUM(spd.horasNocturnas)*135 as pN, "
                            . "SUM(spd.horasFestivasDiurnas)*175 as pFD, "
                            . "SUM(spd.horasFestivasNocturnas)*210 as pFN, "                
                            . "SUM(spd.horasExtrasOrdinariasDiurnas)*125 as pEOD, "
                            . "SUM(spd.horasExtrasOrdinariasNocturnas)*175 as pEON, "
                            . "SUM(spd.horasExtrasFestivasDiurnas)*200 as pEFD, "
                            . "SUM(spd.horasExtrasFestivasNocturnas)*250 as pEFN, "
                            . "SUM(spd.horasRecargoNocturno)*35 as pRN, "
                            . "SUM(spd.horasRecargoFestivoDiurno)*75 as pRFD, "
                            . "SUM(spd.horasRecargoFestivoNocturno)*110 as pRFN "                                
                            . "FROM BrasaTurnoBundle:TurSoportePagoDetalle spd "
                            . "WHERE spd.anio =  " . $arCierreMes->getAnio() . " AND spd.mes =  " . $arCierreMes->getMes() . " AND spd.codigoRecursoFk = " . $arrRecurso['codigo_recurso_fk'] . " "
                            . "GROUP BY spd.codigoPedidoDetalleFk" ;
                    $query = $em->createQuery($dql);
                    $arrayResultados = $query->getResult(); 
                    $pesoTotal = 0;
                    foreach ($arrayResultados as $detalle) {
                        $peso = $detalle['pDS'] + $detalle['pD'] + $detalle['pN'] + $detalle['pFD'] + $detalle['pFN'] + $detalle['pEOD'] + $detalle['pEON'] + $detalle['pEFD'] + $detalle['pEFN'] + $detalle['pRN'] + $detalle['pRFD'] + $detalle['pRFN']; 
                        $pesoTotal += $peso;
                    }
                    foreach ($arrayResultados as $detalle) {
                        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($detalle['codigoPedidoDetalleFk']);
                        $arCostoRecursoDetalle = new \Brasa\TurnoBundle\Entity\TurCostoRecursoDetalle();
                        $arCostoRecursoDetalle->setAnio($arCierreMes->getAnio());
                        $arCostoRecursoDetalle->setMes($arCierreMes->getMes());
                        $arCostoRecursoDetalle->setCodigoCierreMesFk($arCierreMes->getCodigoCierreMesPk());
                        $arCostoRecursoDetalle->setRecursoRel($arRecurso);
                        $arCostoRecursoDetalle->setPedidoDetalleRel($arPedidoDetalle);                        
                        $arCostoRecursoDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                        $arCostoRecursoDetalle->setClienteRel($arPedidoDetalle->getPedidoRel()->getClienteRel());
                        $arCostoRecursoDetalle->setHorasDescanso($detalle['horasDescanso']);
                        $arCostoRecursoDetalle->setHorasDiurnas($detalle['horasDiurnas']);
                        $arCostoRecursoDetalle->setHorasNocturnas($detalle['horasNocturnas']);
                        $arCostoRecursoDetalle->setHorasFestivasDiurnas($detalle['horasFestivasDiurnas']);
                        $arCostoRecursoDetalle->setHorasFestivasNocturnas($detalle['horasFestivasNocturnas']);
                        $arCostoRecursoDetalle->setHorasExtrasOrdinariasDiurnas($detalle['horasExtrasOrdinariasDiurnas']);
                        $arCostoRecursoDetalle->setHorasExtrasOrdinariasNocturnas($detalle['horasExtrasOrdinariasNocturnas']);
                        $arCostoRecursoDetalle->setHorasExtrasFestivasDiurnas($detalle['horasExtrasFestivasDiurnas']);
                        $arCostoRecursoDetalle->setHorasExtrasFestivasNocturnas($detalle['horasExtrasFestivasNocturnas']);
                        $arCostoRecursoDetalle->setHorasRecargoNocturno($detalle['horasRecargoNocturno']);
                        $arCostoRecursoDetalle->setHorasRecargoFestivoDiurno($detalle['horasRecargoFestivoDiurno']);
                        $arCostoRecursoDetalle->setHorasRecargoFestivoNocturno($detalle['horasRecargoFestivoNocturno']);
                        
                        $peso = $detalle['pDS'] + $detalle['pD'] + $detalle['pN'] + $detalle['pFD'] + $detalle['pFN'] + $detalle['pEOD'] + $detalle['pEON'] + $detalle['pEFD'] + $detalle['pEFN'] + $detalle['pRN'] + $detalle['pRFD'] + $detalle['pRFN']; 
                        $participacionRecurso = 0;
                        if($peso > 0) {
                            $participacionRecurso = $peso / $pesoTotal;
                        }
                        $costoDetalle = $participacionRecurso * $costoRecurso;                        
                        $costoDetalleNomina =  $participacionRecurso * $devengado;
                        $costoDetalleSeguridadSocial =  $participacionRecurso * $seguridadSocial;
                        $costoDetallePrestaciones =  $participacionRecurso * $prestaciones;
                        $participacion = 0;
                        
                        if($detalle['pDS'] > 0) {
                            $participacion = $detalle['pDS'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoRecursoDetalle->setHorasDescansoCosto($costo);
                        
                        $participacion = 0;
                        if($detalle['pD'] > 0) {
                            $participacion = $detalle['pD'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoRecursoDetalle->setHorasDiurnasCosto($costo);
                        
                        $participacion = 0;
                        if($detalle['pN'] > 0) {
                            $participacion = $detalle['pN'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;                        
                        $arCostoRecursoDetalle->setHorasNocturnasCosto($costo);
                        
                        $participacion = 0;
                        if($detalle['pFD'] > 0) {
                            $participacion = $detalle['pFD'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;                        
                        $arCostoRecursoDetalle->setHorasFestivasDiurnasCosto($costo);
                        
                        $participacion = 0;
                        if($detalle['pFN'] > 0) {
                            $participacion = $detalle['pFN'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;                        
                        $arCostoRecursoDetalle->setHorasFestivasNocturnasCosto($costo);
                        
                        $participacion = 0;
                        if($detalle['pEOD'] > 0) {
                            $participacion = $detalle['pEOD'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;                        
                        $arCostoRecursoDetalle->setHorasExtrasOrdinariasDiurnasCosto($costo);
                        
                        $participacion = 0;
                        if($detalle['pEON'] > 0) {
                            $participacion = $detalle['pEON'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;                        
                        $arCostoRecursoDetalle->setHorasExtrasOrdinariasNocturnasCosto($costo);
                        
                        $participacion = 0;
                        if($detalle['pEFD'] > 0) {
                            $participacion = $detalle['pEFD'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;                        
                        $arCostoRecursoDetalle->setHorasExtrasFestivasDiurnasCosto($costo);
                        
                        $participacion = 0;
                        if($detalle['pEFN'] > 0) {
                            $participacion = $detalle['pEFN'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;                        
                        $arCostoRecursoDetalle->setHorasExtrasFestivasNocturnasCosto($costo);
                        
                        $participacion = 0;
                        if($detalle['pRN'] > 0) {
                            $participacion = $detalle['pRN'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;                        
                        $arCostoRecursoDetalle->setHorasRecargoNocturnoCosto($costo);
                        
                        $participacion = 0;
                        if($detalle['pRFD'] > 0) {
                            $participacion = $detalle['pRFD'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;                        
                        $arCostoRecursoDetalle->setHorasRecargoFestivoDiurnoCosto($costo);

                        $participacion = 0;
                        if($detalle['pRFN'] > 0) {
                            $participacion = $detalle['pRFN'] / $peso;
                        }
                        $costo = $participacion * $costoDetalle;
                        $arCostoRecursoDetalle->setHorasRecargoFestivoNocturnoCosto($costo);                                                

                        $arCostoRecursoDetalle->setParticipacion($participacionRecurso * 100);
                        $arCostoRecursoDetalle->setPeso($peso);
                        $arCostoRecursoDetalle->setCosto($costoDetalle);
                        $arCostoRecursoDetalle->setCostoNomina($costoDetalleNomina);
                        $arCostoRecursoDetalle->setCostoSeguridadSocial($costoDetalleSeguridadSocial);
                        $arCostoRecursoDetalle->setCostoPrestaciones($costoDetallePrestaciones);
                        $em->persist($arCostoRecursoDetalle);
                    }
                    $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arrRecurso['codigo_recurso_fk']);
                    $arCostoRecurso = new \Brasa\TurnoBundle\Entity\TurCostoRecurso();
                    $arCostoRecurso->setCierreMesRel($arCierreMes);
                    $arCostoRecurso->setRecursoRel($arRecurso);
                    $arCostoRecurso->setAnio($arCierreMes->getAnio());
                    $arCostoRecurso->setMes($arCierreMes->getMes());
                    $arCostoRecurso->setVrNomina($devengado); 
                    $arCostoRecurso->setVrPrestaciones($prestaciones);
                    $arCostoRecurso->setVrAportesSociales($seguridadSocial);                    
                    $arCostoRecurso->setVrCostoTotal($costoRecurso);
                    $em->persist($arCostoRecurso);                                                                                                                              
                }
                $em->flush();
                
                //Asignar los centros de costos donde mas trabajo el recurso                
                foreach ($arrRecursos as $arrRecurso) {
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arrRecurso['codigo_empleado_fk']);
                    if($arEmpleado) {
                        if($arEmpleado->getEmpleadoTipoRel()->getTipo() == 2) {
                            $arrProgramaciones = $em->getRepository('BrasaTurnoBundle:TurRecurso')->programacionFechaRecurso($arCierreMes->getAnio(), $arCierreMes->getMes(), $arrRecurso['codigo_recurso_fk']);
                            if($arrProgramaciones) {
                                $codigoPuesto = $arrProgramaciones[0]['codigo_puesto_fk'];
                                if($codigoPuesto) {                                    
                                    $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($codigoPuesto);
                                    if($arPuesto) {
                                        $arEmpleado->setPuestoRel($arPuesto);
                                        $arEmpleado->setCentroCostoContabilidadRel($arPuesto->getCentroCostoContabilidadRel());
                                        $em->persist($arEmpleado); 
                                        
                                        $arRecursoPuesto = new \Brasa\TurnoBundle\Entity\TurRecursoPuesto();
                                        $arRecursoPuesto->setAnio($arCierreMes->getAnio());
                                        $arRecursoPuesto->setMes($arCierreMes->getMes());
                                        $arRecursoPuesto->setCodigoPuestoFk($codigoPuesto);
                                        $arRecursoPuesto->setCodigoRecursoFk($arrRecurso['codigo_recurso_fk']);
                                        $arRecursoPuesto->setCodigoEmpleadoFk($arrRecurso['codigo_empleado_fk']);
                                        $arRecursoPuesto->setCodigoCentroCostoFk($arPuesto->getCodigoCentroCostoContabilidadFk());
                                        $em->persist($arRecursoPuesto);
                                    }
                                }
                            }
                        }
                    }
                } 
                $em->flush();                                
                
                
                //Asignar centro de costo a empleados
                $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();                                
                $dql   = "SELECT c.codigoEmpleadoFk FROM BrasaRecursoHumanoBundle:RhuContrato c "
                        ." WHERE (c.fechaHasta >= '" . $strFechaDesde . "' OR c.indefinido = 1) "
                        . "AND c.fechaDesde <= '" . $strFechaHasta . "' GROUP BY c.codigoEmpleadoFk";
                $query = $em->createQuery($dql);        
                $arContratos = $query->getResult();                        
                foreach ($arContratos as $arContrato) {
                    $arEmpleadoCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoCentroCosto();
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato['codigoEmpleadoFk']);                    
                    $arEmpleadoCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoCentroCosto();
                    $arEmpleadoCentroCosto->setAnio($arCierreMes->getAnio());
                    $arEmpleadoCentroCosto->setMes($arCierreMes->getMes());
                    $arEmpleadoCentroCosto->setCodigoEmpleadoFk($arContrato['codigoEmpleadoFk']);
                    $arRecursoPuesto = new \Brasa\TurnoBundle\Entity\TurRecursoPuesto();
                    $arRecursoPuesto = $em->getRepository('BrasaTurnoBundle:TurRecursoPuesto')->findOneBy(array('anio' => $arCierreMes->getAnio(), 'mes' => $arCierreMes->getMes(), 'codigoEmpleadoFk' => $arContrato['codigoEmpleadoFk']));
                    if($arRecursoPuesto) {
                        $arEmpleadoCentroCosto->setCodigoCentroCostoFk($arRecursoPuesto->getCodigoCentroCostoFk());
                        $arEmpleadoCentroCosto->setCodigoPuestoFk($arRecursoPuesto->getCodigoPuestoFk());                        
                    } else {
                        $arEmpleadoCentroCosto->setCodigoCentroCostoFk($arEmpleado->getCodigoCentroCostoContabilidadFk());
                        $arEmpleadoCentroCosto->setCodigoPuestoFk(0);                        
                    }
                    $em->persist($arEmpleadoCentroCosto);
                }
                $em->flush();
                
            
                //Creo los servicios (Detalles de pedido)
                $arPedidosDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();                
                $arPedidosDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->fecha($strFechaDesde, $strFechaHasta);                                
                foreach ($arPedidosDetalles as $arPedidoDetalle) {
                    $dql   = "SELECT SUM(crd.costo) as costo "                                                                
                            . "FROM BrasaTurnoBundle:TurCostoRecursoDetalle crd "
                            . "WHERE crd.anio =  " . $arCierreMes->getAnio() . " AND crd.mes =  " . $arCierreMes->getMes() . " AND crd.codigoPedidoDetalleFk = " . $arPedidoDetalle->getCodigoPedidoDetallePk();
                    $query = $em->createQuery($dql);
                    $arrayResultados = $query->getResult(); 
                    $costo = 0;
                    if($arrayResultados[0]['costo']) {
                        $costo = $arrayResultados[0]['costo'];
                    }
                    $arCostoServicio = new \Brasa\TurnoBundle\Entity\TurCostoServicio();
                    $arCostoServicio->setCierreMesRel($arCierreMes);
                    $arCostoServicio->setAnio($arCierreMes->getAnio());
                    $arCostoServicio->setMes($arCierreMes->getMes());
                    $arCostoServicio->setPedidoDetalleRel($arPedidoDetalle);
                    $arCostoServicio->setClienteRel($arPedidoDetalle->getPedidoRel()->getClienteRel());
                    $arCostoServicio->setPuestoRel($arPedidoDetalle->getPuestoRel());
                    $arCostoServicio->setConceptoServicioRel($arPedidoDetalle->getConceptoServicioRel());
                    $arCostoServicio->setModalidadServicioRel($arPedidoDetalle->getModalidadServicioRel());
                    $arCostoServicio->setPeriodoRel($arPedidoDetalle->getPeriodoRel());
                    $arCostoServicio->setDiaDesde($arPedidoDetalle->getDiaDesde());
                    $arCostoServicio->setDiaHasta($arPedidoDetalle->getDiaHasta());
                    $arCostoServicio->setDias($arPedidoDetalle->getDias());
                    $arCostoServicio->setHoras($arPedidoDetalle->getHoras());
                    $arCostoServicio->setHorasDiurnas($arPedidoDetalle->getHorasDiurnas());
                    $arCostoServicio->setHorasNocturnas($arPedidoDetalle->getHorasNocturnas());
                    $arCostoServicio->setCantidad($arPedidoDetalle->getCantidad());
                    $arCostoServicio->setVrTotal($arPedidoDetalle->getVrTotalDetalle());
                    $arCostoServicio->setVrCostoRecurso($costo);
                    //$arrProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->detallesPedido($arPedidoDetalle->getCodigoPedidoDetallePk(), $arCierreMes->getAnio(), $arCierreMes->getMes());                    
                    //if($arrProgramacionDetalles['horas'] != NULL) {
                    //    $arCierreMesServicio->setHorasProgramadas($arrProgramacionDetalles['horas']);
                    //    $arCierreMesServicio->setVrCostoRecurso($arrProgramacionDetalles['vrRecurso']);                        
                    //}
                    $em->persist($arCostoServicio);                         
                }
                $em->flush();                   
                 
                $arCierreMes->setEstadoGenerado(1);
                $em->persist($arCierreMes);
                $em->flush();   
                  
                              
                return $this->redirect($this->generateUrl('brs_tur_proceso_cierre_mes'));
            }
            if($request->request->get('OpDeshacer')) {                
                $codigoCierreMes = $request->request->get('OpDeshacer');
                $arCierreMes = new \Brasa\TurnoBundle\Entity\TurCierreMes();
                $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigoCierreMes);
                
                $strSql = "DELETE FROM tur_costo_recurso WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;           
                $em->getConnection()->executeQuery($strSql);
                $strSql = "DELETE FROM tur_costo_recurso_detalle WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;           
                $em->getConnection()->executeQuery($strSql);                
                $strSql = "DELETE FROM tur_costo_servicio WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;           
                $em->getConnection()->executeQuery($strSql); 
                $strSql = "DELETE FROM tur_recurso_puesto WHERE anio = " . $arCierreMes->getAnio() . " AND mes = " . $arCierreMes->getMes();           
                $em->getConnection()->executeQuery($strSql); 
                $strSql = "DELETE FROM rhu_empleado_centro_costo WHERE anio = " . $arCierreMes->getAnio() . " AND mes = " . $arCierreMes->getMes();           
                $em->getConnection()->executeQuery($strSql);                
                
                $arCierreMes->setEstadoGenerado(0);
                $em->persist($arCierreMes);
                $em->flush();                                                  
                return $this->redirect($this->generateUrl('brs_tur_proceso_cierre_mes'));                
            }
                       
            /*if($request->request->get('OpCerrar')) {
                $codigoSoportePagoPeriodo = $request->request->get('OpCerrar');
                $arSoportePagoPeriodo = NEW \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                $arSoportePagoPeriodo->setEstadoCerrado(1);                
                $em->persist($arSoportePagoPeriodo);
                $em->flush();                                                   
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
            }*/            
            
            
        }
        $dql = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->listaDql();
        $arCierreMes = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Procesos/CierreMes:lista.html.twig', array(
            'arCierreMes' => $arCierreMes,
            'form' => $form->createView()));
    }
    
    private function formularioGenerar() {
        $form = $this->createFormBuilder()                  
            ->getForm();
        return $form;
    }    
}