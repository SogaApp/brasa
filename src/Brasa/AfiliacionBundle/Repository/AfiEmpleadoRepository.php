<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiEmpleadoRepository extends EntityRepository {    
    
    public function listaDql($strNombre = "", $codigoCliente = "", $identificacion = "" , $boolActivo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT e FROM BrasaAfiliacionBundle:AfiEmpleado e WHERE e.codigoEmpleadoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND e.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($identificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $identificacion . "%'";
        }        
        if($codigoCliente != "" ) {
            $dql .= " AND e.codigoClienteFk = " . $codigoCliente;
        }
        if($boolActivo != ""){
            $dql .= " AND e.estadoActivo = " . $boolActivo;
        }
        
        $dql .= " ORDER BY e.nombreCorto";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }        
}