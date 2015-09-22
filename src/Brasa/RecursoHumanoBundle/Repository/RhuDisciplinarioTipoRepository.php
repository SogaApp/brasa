<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuDisciplinarioTipoRepository extends EntityRepository {
    
    public function listaDql() {        
        $dql   = "SELECT dt FROM BrasaRecursoHumanoBundle:RhuDisciplinarioTipo dt WHERE dt.codigoDisciplinarioTipoPk <> 0";        
        $dql .= " ORDER BY dt.nombre";
        return $dql;
    }       
    
}