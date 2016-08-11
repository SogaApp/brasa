<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuPagoDetalleRepository extends EntityRepository {
    
    public function listaDql($codigoPago = "", $codigoProgramacionPagoDetalle = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd WHERE pd.codigoPagoDetallePk <> 0";

        if($codigoPago != "") {
            $dql .= " AND pd.codigoPagoFk = " . $codigoPago;
        } 
        if($codigoProgramacionPagoDetalle != "") {
            $dql .= " AND pd.codigoProgramacionPagoDetalleFk = " . $codigoProgramacionPagoDetalle;
        }        
        $dql .= " ORDER BY pd.codigoPagoConceptoFk";
        return $dql;
    }                            
    
    public function listaDetalleDql($intNumero = 0, $strCodigoCentroCosto = "", $strIdentificacion = "", $intTipo = "", $strDesde = "", $strHasta = "", $strCodigoPagoConcepto = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pd, p, e FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN p.empleadoRel e WHERE p.codigoPagoPk <> 0";
        if($intNumero != "" && $intNumero != 0) {
            $dql .= " AND p.numero = " . $intNumero;
        }
        if($strCodigoPagoConcepto != "") {
            $dql .= " AND pd.codigoPagoConceptoFk = " . $strCodigoPagoConcepto;
        }
        if($strCodigoCentroCosto != "") {
            $dql .= " AND p.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }
        if($intTipo != "" && $intTipo != 0) {
            $dql .= " AND p.codigoPagoTipoFk =" . $intTipo;
        }        
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if($strDesde != "" || $strDesde != 0){
            $dql .= " AND p.fechaDesde >='" . date_format($strDesde, ('Y-m-d')) . "'";
        }
        if($strHasta != "" || $strHasta != 0) {
            $dql .= " AND p.fechaHasta <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        $dql .= " ORDER BY p.codigoPagoPk DESC";
        return $dql;
    }      
    
    public function pagosDetallesProgramacionPago($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p "
                . "WHERE p.codigoProgramacionPagoFk = " . $codigoProgramacionPago . " ORDER BY p.codigoEmpleadoFk, pd.codigoPagoConceptoFk";
        $query = $em->createQuery($dql);
        $arPagosDetalles = $query->getResult();                
        return $arPagosDetalles;
    }
    
    public function devuelveRetencionFuenteEmpleadoFecha($codigoEmpleado, $strFechaCertificado) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.vrPago) as Retencion FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p "
                . "WHERE p.codigoEmpleadoFk = " . $codigoEmpleado . " "
                . "AND pd.codigoPagoConceptoFk = 26"
                . "AND p.fechaDesde like '%" . $strFechaCertificado . "%' ";
        $query = $em->createQuery($dql);
        $douRetencion = $query->getSingleScalarResult();
        return $douRetencion;
    }
    
    public function devuelveInteresesCesantiasFechaCertificadoIngreso($codigoEmpleado, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.vrPago) as Neto FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p "
                . "WHERE p.codigoEmpleadoFk = " . $codigoEmpleado . " AND p.estadoPagado = 1 AND p.codigoPagoTipoFk = 3"
                . "AND p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaDesde <= '" . $fechaHasta . "'"
                . "AND pd.codigoPagoConceptoFk = 30 ";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
    
    public function listaDqlPagosDetallePeriodoAportes($strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e, pd FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN p.empleadoRel e WHERE p.codigoPagoTipoFk = 1 AND p.estadoPagado = 1";
        
        if ($strDesde != ""){
            $dql .= " AND p.fechaDesdePago >='" . $strDesde->format('Y-m-d'). "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaHastaPago <='" . $strHasta->format('Y-m-d') . "'";
        }

        $query = $em->createQuery($dql);
        $arPagosDetalles = $query->getResult();                
        return $arPagosDetalles;
    }
}