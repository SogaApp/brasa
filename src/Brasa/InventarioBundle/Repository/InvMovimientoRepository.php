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
    
    public function listaDql($strCodigo = '', $strNumero = '') {
        $dql   = "SELECT m FROM BrasaInventarioBundle:InvMovimiento m WHERE m.codigoMovimientoPk <> 0 ";
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
        $respuesta = "";                
        $validarCantidad = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->validarCantidad($codigoMovimiento);
        if($validarCantidad > 0) {
            $respuesta = "Existen detalles con cantidad en cero";
        }        
        return $respuesta;
    }
        
}