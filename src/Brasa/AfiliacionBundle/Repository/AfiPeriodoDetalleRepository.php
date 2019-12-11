<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiPeriodoDetalleRepository extends EntityRepository
{

    public function listaDql($codigoPeriodo = "")
    {
        $em = $this->getEntityManager();
        $dql = "SELECT pd FROM BrasaAfiliacionBundle:AfiPeriodoDetalle pd WHERE pd.codigoPeriodoDetallePk <> 0";
        if ($codigoPeriodo != "") {
            $dql .= " AND pd.codigoPeriodoFk =" . $codigoPeriodo;
        }
        $dql .= " ORDER BY pd.codigoPeriodoDetallePk";
        return $dql;
    }

    public function listaConsultaDql($codigo = '', $codigoCliente = '', $estadoFacturado = '', $strDesde = "", $strHasta = "", $strAsesor = "")
    {
        //$em = $this->getEntityManager();
        $dql = "SELECT pd FROM BrasaAfiliacionBundle:AfiPeriodoDetalle pd JOIN pd.periodoRel p JOIN p.clienteRel c WHERE pd.codigoPeriodoDetallePk <> 0 ";
        if ($codigoCliente != '') {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;
        }
        if ($estadoFacturado == 1) {
            $dql .= " AND p.estadoFacturado = 1";
        }
        if ($estadoFacturado == "0") {
            $dql .= " AND p.estadoFacturado = 0";
        }
        if ($strDesde != "") {
            $dql .= " AND p.fechaDesde >='" . $strDesde . "'";
        }
        if ($strHasta != "") {
            $dql .= " AND p.fechaDesde <='" . $strHasta . "'";
        }
        if ($strAsesor != "") {
            $dql .= " AND c.codigoAsesorFk ='" . $strAsesor . "'";
        }

        $dql .= " ORDER BY pd.codigoPeriodoDetallePk";
        return $dql;
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        $registros = false;
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetalle')->find($codigo);
                $arFacturaDetalle = $em->createQueryBuilder()->from("BrasaAfiliacionBundle:AfiFacturaDetalle", "fd")
                    ->select("fd")
                    ->join("fd.facturaRel", "f")
                    ->where("fd.codigoPeriodoFk = {$ar->getCodigoPeriodoFk()}")
                    ->andWhere("f.estadoAnulado = 0")
                    ->getQuery()->getResult();
                if ($arFacturaDetalle != null) {
                    $registros = true;
                } else {
                    $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($ar->getCodigoPeriodoFk());
                    $arPeriodo->setAdministracion($arPeriodo->getAdministracion() - $ar->getAdministracion());
                    $arPeriodo->setSubtotal($arPeriodo->getSubtotal() - $ar->getSubtotal());
                    $arPeriodo->setIva($arPeriodo->getIva() - $ar->getIva());
                    $arPeriodo->setTotal($arPeriodo->getTotal() - $ar->getTotal());
                    $arPeriodo->setNumeroEmpleados($arPeriodo->getNumeroEmpleados() - 1);
                    $em->persist($arPeriodo);
                    $em->remove($ar);
                }
            }
            $em->flush();
            return $registros;
        }
    }

    public function trasladoNuevo($arrSeleccionados, $codigoPeriodo)
    {
        $em = $this->getEntityManager();
        if (count($arrSeleccionados) > 0) {
            $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
            $arPeriodoTraslado = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
            $arPeriodoTraslado->setClienteRel($arPeriodo->getClienteRel());
            $arPeriodoTraslado->setFechaDesde($arPeriodo->getFechaDesde());
            $arPeriodoTraslado->setFechaHasta($arPeriodo->getFechaHasta());
            $arPeriodoTraslado->setEstadoGenerado(1);
            $arPeriodoTraslado->setFechaPago($arPeriodo->getFechaPago());
            $arPeriodoTraslado->setAnio($arPeriodo->getAnio());
            $arPeriodoTraslado->setMes($arPeriodo->getMes());
            $arPeriodoTraslado->setAnioPago($arPeriodo->getAnioPago());
            $arPeriodoTraslado->setMesPago($arPeriodo->getMesPago());
            $em->persist($arPeriodoTraslado);
            $salud = 0;
            $pension = 0;
            $caja = 0;
            $riesgos = 0;
            $iva = 0;
            $administracion = 0;
            $subtotal = 0;
            $total = 0;
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetalle')->find($codigo);
                $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find($ar->getCodigoEmpleadoFK());
                $arContrato = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->find($ar->getCodigoContratoFk());
                $arTraslado = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle();
                $arTraslado->setPeriodoRel($arPeriodoTraslado);
                $arTraslado->setEmpleadoRel($arEmpleado);
                $arTraslado->setContratoRel($arContrato);
                $arTraslado->setIngreso($ar->getIngreso());
                $arTraslado->setRetiro($ar->getRetiro());
                $arTraslado->setFechaDesde($ar->getFechaDesde());
                $arTraslado->setFechaHasta($ar->getFechaHasta());
                $arTraslado->setDias($ar->getDias());
                $arTraslado->setSalario($ar->getSalario());
                $arTraslado->setSalud($ar->getSalud());
                $arTraslado->setPension($ar->getPension());
                $arTraslado->setCaja($ar->getCaja());
                $arTraslado->setRiesgos($ar->getRiesgos());
                $arTraslado->setIngreso($ar->getIngreso());
                $arTraslado->setAdministracion($ar->getAdministracion());
                $arTraslado->setIva($ar->getIva());
                $arTraslado->setSubtotal($ar->getSubtotal());
                $arTraslado->setTotal($ar->getTotal());
                $salud = $salud + $ar->getSalud();
                $pension = $pension + $ar->getPension();
                $caja = $caja + $ar->getCaja();
                $riesgos = $riesgos + $ar->getRiesgos();
                $administracion = $administracion + $ar->getAdministracion();
                $iva = $iva + $ar->getIva();
                $subtotal = $subtotal + $ar->getSubtotal();
                $total = $total + $ar->getTotal();
                $em->persist($arTraslado);
                $em->remove($ar);
            }
            $arPeriodoTraslado->setNumeroEmpleados(count($arrSeleccionados));
            $arPeriodoTraslado->setSalud($salud);
            $arPeriodoTraslado->setPension($pension);
            $arPeriodoTraslado->setCaja($caja);
            $arPeriodoTraslado->setRiesgos($riesgos);
            $arPeriodoTraslado->setAdministracion($administracion);
            $arPeriodoTraslado->setSubtotal($subtotal);
            $arPeriodoTraslado->setIva($iva);
            $arPeriodoTraslado->setTotal($total);

            $arPeriodo->setNumeroEmpleados($arPeriodo->getNumeroEmpleados() - 1);
            $arPeriodo->setAdministracion($arPeriodo->getAdministracion() - $administracion);
            $arPeriodo->setSubtotal($arPeriodo->getSubtotal() - $subtotal);
            $arPeriodo->setIva($arPeriodo->getIva() - $iva);
            $arPeriodo->setTotal($arPeriodo->getTotal() - $total);
            $arPeriodo->setNumeroEmpleados($arPeriodo->getNumeroEmpleados() - 1);

            $em->persist($arPeriodoTraslado);
            $em->persist($arPeriodo);
            $em->flush();
        }
    }

    public function actualizarDetalleCobro($codigoPeriodo)
    {
        set_time_limit(0);
        ob_clean();
        $em = $this->getEntityManager();
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
        $totalPension = 0;
        $totalSalud = 0;
        $totalCaja = 0;
        $totalRiesgos = 0;
        $totalSena = 0;
        $totalIcbf = 0;
        $totalAdministracion = 0;
        $subtotalGeneral = 0;
        $ivaGeneral = 0;
        $totalGeneral = 0;
        $arPeriodoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle();
        $arPeriodoDetalles = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetalle')->findBy(array('codigoPeriodoFk' => $codigoPeriodo));
        $numeroRegistros = count($arPeriodoDetalles);
        foreach ($arPeriodoDetalles as $arPeriodoDetalle) {
            $totalPension += $arPeriodoDetalle->getPension();
            $totalSalud += $arPeriodoDetalle->getSalud();
            $totalCaja += $arPeriodoDetalle->getCaja();
            $totalRiesgos += $arPeriodoDetalle->getRiesgos();
            $totalSena += $arPeriodoDetalle->getSena();
            $totalIcbf += $arPeriodoDetalle->getIcbf();
            $totalAdministracion += $arPeriodoDetalle->getAdministracion();
            $subtotalGeneral += $arPeriodoDetalle->getSubtotal();
            $ivaGeneral += $arPeriodoDetalle->getIva();
            $totalGeneral += $arPeriodoDetalle->getTotal();
        }
        $arPeriodo->setEstadoGenerado(1);
        $arPeriodo->setPension($totalPension);
        $arPeriodo->setSalud($totalSalud);
        $arPeriodo->setCaja($totalCaja);
        $arPeriodo->setRiesgos($totalRiesgos);
        $arPeriodo->setSena($totalSena);
        $arPeriodo->setIcbf($totalIcbf);
        $arPeriodo->setAdministracion($totalAdministracion);
        $arPeriodo->setSubtotal($subtotalGeneral);
        $arPeriodo->setIva($ivaGeneral);
        $arPeriodo->setTotal($totalGeneral);
        $arPeriodo->setNumeroEmpleados($numeroRegistros);
        $em->persist($arPeriodo);
        $em->flush();
        $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->generarInteresMora($codigoPeriodo);
    }

}