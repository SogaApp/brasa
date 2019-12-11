<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCotizacionOtroRepository extends EntityRepository {

    public function pendientesCliente($codigoCliente) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoClienteFk = " . $codigoCliente;
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arCotizacionDetalle = $em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->find($codigo);                
                $em->remove($arCotizacionDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function numeroRegistros($codigoCotizacion) {
        $em = $this->getEntityManager();
        $arCotizacionDetalles = $em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->findBy(array('codigoCotizacionFk' => $codigoCotizacion));
        return count($arCotizacionDetalles);
    }      
}