<?php

namespace Brasa\AfiliacionBundle\Repository;

/**
 * AfiFacturaDetalleCursoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AfiFacturaDetalleAfiliacionRepository extends \Doctrine\ORM\EntityRepository
{
    public function ListaDql($codigoFactura) {
        $em = $this->getEntityManager();
        $dql   = "SELECT fda FROM BrasaAfiliacionBundle:AfiFacturaDetalleAfiliacion fda WHERE fda.codigoFacturaDetalleAfiliacionPk <> 0 AND fda.codigoFacturaFk = " . $codigoFactura;
        $dql .= " ORDER BY fda.codigoFacturaDetalleAfiliacionPk";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleAfiliacion')->find($codigo);
                $arAfiliacion = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->find($ar->getCodigoContratoFk());
                $arAfiliacion->setEstadoGeneradoCtaCobrar(0);
                $em->persist($arAfiliacion);
                $em->remove($ar);
            }
            $em->flush();
        }
    }     
}
