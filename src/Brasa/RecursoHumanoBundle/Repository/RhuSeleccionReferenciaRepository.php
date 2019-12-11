<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuSeleccionReferenciaRepository extends EntityRepository {                   
    public function listaDQL($strNombre = "", $strCodigoCentroCosto = "", $boolMostrarAprobados = "", $strIdentificacion = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT s FROM BrasaRecursoHumanoBundle:RhuSeleccion s WHERE s.codigoSeleccionPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND s.nombreCorto LIKE '%" . $strNombre . "%'";
        }      
        if($strCodigoCentroCosto != "") {
            $dql .= " AND s.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }      
        if($boolMostrarAprobados == 1 ) {
            $dql .= " AND s.estadoAprobado = 1";
        } elseif($boolMostrarAprobados == 0) {
            $dql .= " AND s.estadoAprobado = 0";
        }
        if($strIdentificacion != "" ) {
            $dql .= " AND s.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }         
        $dql .= " ORDER BY s.nombreCorto";
        return $dql;
    }                            
}