<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurRecursoGrupoRepository extends EntityRepository {    
    
    public function listaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT rg FROM BrasaTurnoBundle:TurRecursoGrupo rg WHERE rg.codigoRecursoGrupoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND rg.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND rg.codigoRecursoGrupoPk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY rg.nombre";
        return $dql;
    }  
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            try{
                foreach ($arrSeleccionados AS $codigo) {
                    $ar = $em->getRepository('BrasaTurnoBundle:TurRecursoGrupo')->find($codigo);
                    $em->remove($ar);
                }
                $em->flush();                
            } catch (Exception $ex) {

            }
        }
    }        
        
}