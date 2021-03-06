<?php

namespace Brasa\InventarioBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MovimientosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvMovimientoRepository extends EntityRepository { 
    
    public function listaDql($codigoDocumento = '', $strCodigo = '', $strNumero = '') {
        $dql   = "SELECT m FROM BrasaInventarioBundle:InvMovimiento m WHERE m.codigoDocumentoFk = $codigoDocumento ";
        if($strNumero != "" ) {
            $dql .= " AND m.numero = " . $strNumero;
        }
        if($strCodigo != "" ) {
            $dql .= " AND m.codigoMovimientoPk = " . $strCodigo;
        }       
        $dql .= " ORDER BY m.codigoMovimientoPk";
        return $dql;
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        $respuesta = false;                        
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                if($em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->numeroRegistros($codigo) <= 0) {
                    $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigo);
                    if ($arMovimiento->getEstadoAutorizado() == 1){
                        $respuesta = true;                        
                    } else {
                        if($arMovimiento->getEstadoAutorizado() == 0 && $arMovimiento->getNumero() == 0) {
                            $em->remove($arMovimiento);
                            $respuesta = false;
                        }
                    }
                }
            }
            $em->flush();
        }
        return $respuesta;
    }
    
    public function autorizar($codigoMovimiento) {
        $em = $this->getEntityManager();
        $respuesta = $this->validarIngreso($codigoMovimiento);
        if($respuesta == "") {
            $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
            $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);
            $dql   = "SELECT md.codigoBodegaFk, md.codigoItemFk, md.loteFk, md.fechaVencimiento, md.codigoBodegaFk, md.operacionInventario, md.cantidad FROM BrasaInventarioBundle:InvMovimientoDetalle md "
                    . "WHERE md.codigoMovimientoFk = " . $codigoMovimiento;
            $query = $em->createQuery($dql);
            $arrMovimientoDetalles = $query->getResult();
            foreach ($arrMovimientoDetalles as $arrMovimientoDetalle) {
                $em->getRepository('BrasaInventarioBundle:InvLote')->afectar(1, $arrMovimientoDetalle['operacionInventario'], $arrMovimientoDetalle['codigoItemFk'], $arrMovimientoDetalle['loteFk'], $arrMovimientoDetalle['fechaVencimiento'], $arrMovimientoDetalle['codigoBodegaFk'], $arrMovimientoDetalle['cantidad']);
            }
            $arMovimiento->setEstadoAutorizado(1);
            $em->persist($arMovimiento);
        }
        return $respuesta;
    }
    
    public function desautorizar($codigoMovimiento) {
        $em = $this->getEntityManager();
        $respuesta = "";
        if($respuesta == "") {
            $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
            $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);
            $dql   = "SELECT md.codigoBodegaFk, md.codigoItemFk, md.loteFk, md.fechaVencimiento, md.codigoBodegaFk, md.operacionInventario, md.cantidad FROM BrasaInventarioBundle:InvMovimientoDetalle md "
                    . "WHERE md.codigoMovimientoFk = " . $codigoMovimiento;
            $query = $em->createQuery($dql);
            $arrMovimientoDetalles = $query->getResult();
            foreach ($arrMovimientoDetalles as $arrMovimientoDetalle) {
                $em->getRepository('BrasaInventarioBundle:InvLote')->afectar(-1, $arrMovimientoDetalle['operacionInventario'], $arrMovimientoDetalle['codigoItemFk'], $arrMovimientoDetalle['loteFk'], $arrMovimientoDetalle['fechaVencimiento'], $arrMovimientoDetalle['codigoBodegaFk'], $arrMovimientoDetalle['cantidad']);
            }
            $arMovimiento->setEstadoAutorizado(0);
            $em->persist($arMovimiento);
        }
        return $respuesta;
    }    
    
    public function imprimir($codigoMovimiento) {
        $em = $this->getEntityManager();
        $respuesta = "";
        if($respuesta == "") {
            $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
            $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);
            if($arMovimiento->getNumero() <= 0) {
                if($arMovimiento->getDocumentoRel()->getAsignarConsecutivoImpresion()) {
                    $arDocumento = new \Brasa\InventarioBundle\Entity\InvDocumento();
                    $arDocumento = $em->getRepository('BrasaInventarioBundle:InvDocumento')->find($arMovimiento->getCodigoDocumentoFk());                
                    $consecutivo = $arDocumento->getConsecutivo();
                    $arDocumento->setConsecutivo($consecutivo + 1);
                    $em->persist($arDocumento);
                    $arMovimiento->setNumero($consecutivo);                                
                }                
            }                                                   
            $arMovimiento->setEstadoImpreso(1);
            $em->persist($arMovimiento);
        }
        return $respuesta;
    }    
    
    public function validarIngreso($codigoMovimiento) {
        $em = $this->getEntityManager();
        $respuesta = "";
        $validarNumeroRegistros = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->numeroRegistros($codigoMovimiento);
        if($validarNumeroRegistros <= 0) {
            $respuesta = "El movimiento no tiene registros";            
        }
        $validarCantidad = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->validarCantidad($codigoMovimiento);
        if($validarCantidad > 0) {
            $respuesta = "Existen detalles con cantidad en cero";
        }  
        $validarLote = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->validarLote($codigoMovimiento);
        if($validarLote > 0) {
            $respuesta = "Existen detalles sin lote";
        }         
        $validarBodega = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->validarBodega($codigoMovimiento);
        if($validarBodega > 0) {
            $respuesta = "Existen detalles sin bodega o con codigo de bodega incorrecta";
        }
        return $respuesta;        
    }
}