<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurFacturaTipoRepository extends EntityRepository {    
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT nt FROM BrasaTurnoBundle:TurFacturaTipo nt WHERE nt.codigoFacturaTipoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND nt.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND nt.codigoFacturaTipoPk = " . $strCodigo;
        }        
        $dql .= " ORDER BY nt.nombre";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurFacturaTipo')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }  
    
    public function consecutivo($codigoFacturaTipo) {
        $em = $this->getEntityManager();
        $intNumero = 0;
        $arFacturaTipo = new \Brasa\TurnoBundle\Entity\TurFacturaTipo();
        $arFacturaTipo = $em->getRepository('BrasaTurnoBundle:TurFacturaTipo')->find($codigoFacturaTipo);     
        $intNumero = $arFacturaTipo->getConsecutivo();
        $arFacturaTipo->setConsecutivo($intNumero + 1);
        $em->persist($arFacturaTipo);
        $em->flush();
        return $intNumero;
    }      
    
}