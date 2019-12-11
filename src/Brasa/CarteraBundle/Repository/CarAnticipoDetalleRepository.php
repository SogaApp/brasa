<?php

namespace Brasa\CarteraBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CarAnticipoDetalleRepository extends EntityRepository {
    
    public function detalleConsultaDql($numero = "", $codigoCliente = "", $codigoCuentaCobrarTipo = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT ad FROM BrasaCarteraBundle:CarAnticipoDetalle ad JOIN ad.anticipoRel a WHERE ad.codigoAnticipoDetallePk <> 0 ";
        if($numero != "") {
            $dql .= " AND ad.numeroFactura = " . $numero;  
        }
        if($codigoCliente != "") {
            $dql .= " AND a.codigoClienteFk = " . $codigoCliente;  
        }
        if($codigoCuentaCobrarTipo != "") {
            $dql .= " AND ad.codigoCuentaCobrarTipoFk = " . $codigoCuentaCobrarTipo;  
        }
        if ($strFechaDesde != ""){
            $dql .= " AND a.fecha >='" . $strFechaDesde. "'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND a.fecha <='" . $strFechaHasta . "'";
        }        
        return $dql;
    }
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arAnticipoDetalle = $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->find($codigo);                
                $em->remove($arAnticipoDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function numeroRegistros($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(ad.codigoAnticipoDetallePk) as numeroRegistros FROM BrasaCarteraBundle:CarAnticipoDetalle ad "
                . "WHERE ad.codigoAnticipoFk = " . $codigo;
        $query = $em->createQuery($dql);
        $arrAnticipoDetalles = $query->getSingleResult(); 
        if($arrAnticipoDetalles) {
            $intNumeroRegistros = $arrAnticipoDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }  

    public function liquidar($codigoAnticipo) {        
        $em = $this->getEntityManager();        
        $arAnticipo = new \Brasa\CarteraBundle\Entity\CarAnticipo();        
        $arAnticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipo')->find($codigoAnticipo); 
        $intCantidad = 0;
        $floValor = 0;
        $floValorPago = 0;
        $floDescuento = 0;
        $floAjustePeso = 0;
        $floReteIca = 0;
        $floReteIva = 0;
        $floReteFuente = 0;
        $arAnticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipo')->find($codigoAnticipo);         
        $arAnticiposDetalle = new \Brasa\CarteraBundle\Entity\CarAnticipoDetalle();        
        $arAnticiposDetalle = $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->findBy(array('codigoAnticipoFk' => $codigoAnticipo));         
        foreach ($arAnticiposDetalle as $arAnticiposDetalle) {         
            $floDescuento += $arAnticiposDetalle->getVrDescuento();
            $floAjustePeso += $arAnticiposDetalle->getVrAjustePeso();
            $floReteIca += $arAnticiposDetalle->getVrReteIca();
            $floReteIva += $arAnticiposDetalle->getVrReteIva();
            $floReteFuente += $arAnticiposDetalle->getVrReteFuente();
            $floValor += $arAnticiposDetalle->getValor();
            $floValorPago += $arAnticiposDetalle->getVrPagoDetalle();
        }                 
        $arAnticipo->setVrTotal($floValor);
        $arAnticipo->setVrTotalPago($floValorPago);
        $arAnticipo->setVrTotalDescuento($floDescuento);
        $arAnticipo->setVrTotalAjustePeso($floAjustePeso);
        $arAnticipo->setVrTotalReteIca($floReteIca);
        $arAnticipo->setVrTotalReteIva($floReteIva);
        $arAnticipo->setVrTotalReteFuente($floReteFuente);
        $em->persist($arAnticipo);
        $em->flush();
        return true;
    }
    
    public function validarCuenta($codigoCuenta, $codigoAnticipo) {        
        $em = $this->getEntityManager();
        $boolValidar = TRUE;        
        $dql   = "SELECT COUNT(ad.codigoAnticipoDetallePk) as numeroRegistros FROM BrasaCarteraBundle:CarAnticipoDetalle ad "
                . "WHERE ad.codigoCuentaCobrarFk = " . $codigoCuenta . " AND ad.codigoAnticipoFk = " . $codigoAnticipo;
        $query = $em->createQuery($dql);
        $arrAnticipoDetalles = $query->getSingleResult(); 
        if($arrAnticipoDetalles) {
            $intNumeroRegistros = $arrAnticipoDetalles['numeroRegistros'];
            if($intNumeroRegistros > 0) {
                $boolValidar = FALSE;
            }
        }
        return $boolValidar;
    }
    
    public function listaConsultaPagoAfiliacionesDql($strCodigo = '', $strNumero = '', $strIdentificacion = '',$strEmpleado = '', $codigoCliente = '', $strAsesor = '', $strCuenta = '', $strDesde = "", $strHasta = "") {
        $em = $this->getEntityManager();        
        $strSql = "SELECT
        car_anticipo.codigo_anticipo_pk AS codigo,
        car_anticipo.numero AS numero,
        car_anticipo.fecha_pago AS fechaPago,
        afi_cliente.nit AS nit,
        afi_cliente.nombre_corto AS cliente,
        gen_asesor.codigo_asesor_pk AS ccAsesor,
        gen_asesor.nombre AS asesor,
        afi_empleado.numero_identificacion AS ccEmpleado,
        afi_empleado.nombre_corto AS empleado,
        afi_factura_detalle_afiliacion.codigo_contrato_fk AS contrato,
        car_anticipo.codigo_cuenta_fk AS codigoCuenta,
        gen_cuenta.nombre AS cuenta,
        afi_cliente.afiliacion AS afiliacion,
        afi_cliente.administracion AS administracion,
        afi_factura_detalle_afiliacion.total AS pago,
        afi_contrato.estado_historial_contrato AS tipo
        FROM
        afi_cliente
        INNER JOIN afi_factura ON afi_factura.codigo_cliente_fk = afi_cliente.codigo_cliente_pk
        INNER JOIN afi_factura_detalle_afiliacion ON afi_factura_detalle_afiliacion.codigo_factura_fk = afi_factura.codigo_factura_pk
        INNER JOIN car_anticipo_detalle ON afi_factura.numero = car_anticipo_detalle.numero_factura
        INNER JOIN car_anticipo ON car_anticipo_detalle.codigo_anticipo_fk = car_anticipo.codigo_anticipo_pk
        INNER JOIN gen_asesor ON gen_asesor.codigo_asesor_pk = afi_cliente.codigo_asesor_fk
        INNER JOIN afi_contrato ON afi_contrato.codigo_cliente_fk = afi_cliente.codigo_cliente_pk AND afi_contrato.codigo_contrato_pk = afi_factura_detalle_afiliacion.codigo_contrato_fk
        INNER JOIN afi_empleado ON afi_empleado.codigo_cliente_fk = afi_cliente.codigo_cliente_pk AND afi_contrato.codigo_empleado_fk = afi_empleado.codigo_empleado_pk
        INNER JOIN gen_cuenta ON gen_cuenta.codigo_cuenta_pk = car_anticipo.codigo_cuenta_fk
        WHERE car_anticipo.codigo_anticipo_pk <> 0 AND car_anticipo.estado_anulado = 0 AND car_anticipo.numero > 0 ";
        if($strCodigo != '') {
            $strSql .= " AND car_anticipo.codigo_anticipo_pk =" . $strCodigo ;
        }
        if($strNumero != '') {
            $strSql .= " AND car_anticipo.numero =" . $strNumero ;
        }
        if($strIdentificacion != '') {
            $strSql .= " AND afi_empleado.numero_identificacion = " . $strIdentificacion;
        }
        if($strEmpleado != '') {
            $strSql .= " AND afi_empleado.nombre_corto LIKE '%" . $strEmpleado . "%'";
        }
        if($codigoCliente != '') {
            $strSql .= " AND nit = " . $codigoCliente;
        }
        if($strAsesor != '') {
            $strSql .= " AND gen_asesor.codigo_asesor_pk =" . $strAsesor ;
        }
        if($strCuenta != '') {
            $strSql .= " AND car_anticipo.codigo_cuenta_fk =" . $strCuenta ;
        }
        if($strDesde != "") {
            $strSql .= " AND car_anticipo.fecha_pago >='" . $strDesde . "'";
        }
        if($strHasta != "") {
            $strSql .= " AND car_anticipo.fecha_pago <='" . $strHasta . "'";
        }
        
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $strSql = $statement->fetchAll();
        return $strSql;
    }
        

}