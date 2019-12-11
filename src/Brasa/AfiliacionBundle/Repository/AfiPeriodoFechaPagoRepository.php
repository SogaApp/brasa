<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiPeriodoFechaPagoRepository extends EntityRepository {

    public function DiaHabilPagar($anioActual, $dosUltimosDigitosNitCliente) {
        $em = $this->getEntityManager();
        $dql = "SELECT p FROM BrasaAfiliacionBundle:AfiPeriodoFechaPago p WHERE p.codigoPeriodoFechaPagoPk <> 0";
        if ($anioActual != "") {
            $dql .= " AND p.anio = " . $anioActual;
        }
        if ($dosUltimosDigitosNitCliente != "") {
            $dql .= " AND p.dosUltimosDigitosInicioNit <= {$dosUltimosDigitosNitCliente} AND p.dosUltimosDigitosFinNit >= {$dosUltimosDigitosNitCliente} ";
        }
        $query = $em->createQuery($dql)
                ->setMaxResults(1)
                ->getResult();

        return $query[0];
    }
    
     public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoFechaPago')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }    

}
