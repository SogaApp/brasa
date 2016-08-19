<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuPagoBancoRepository extends EntityRepository {        
        
    public function listaDQL($strFecha = "") {                
        $dql   = "SELECT pb FROM BrasaRecursoHumanoBundle:RhuPagoBanco pb WHERE pb.codigoPagoBancoPk <> 0";
        if($strFecha != "") {
            $dql .= " AND pb.fechaAplicacion = '" .$strFecha. "'";
        }    
        
        $dql .= " ORDER BY pb.codigoPagoBancoPk DESC";
        return $dql;
    }                            
    
    public function liquidar($codigoPagoBanco) {
        $em = $this->getEntityManager();
        $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);
        $arPagoBancoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array('codigoPagoBancoFk' => $codigoPagoBanco));
        $douTotal = 0;
        $intNumeroRegistros = 0;
        foreach ($arPagoBancoDetalles AS $arPagoBancoDetalle) {
            $douTotal += $arPagoBancoDetalle->getVrPago();
            $intNumeroRegistros++;
        }
        $arPagoBanco->setVrTotalPago($douTotal);
        $arPagoBanco->setNumeroRegistros($intNumeroRegistros);
        $em->persist($arPagoBanco);
        $em->flush();
    }     
    
}