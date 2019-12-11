<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuProgramacionPagoInconsistenciaRepository extends EntityRepository {
    
    /**
     * Elimina las inconsistencias de una programacion de pago.
     * 
     * @author		Mario Estrada
     * 
     * @param integer	Codigo de la programacion de pago
     */     
    public function eliminarProgramacionPago($codigoProgramacionPago) {        
        $em = $this->getEntityManager();
        $strSql = "DELETE FROM rhu_programacion_pago_inconsistencia WHERE codigo_programacion_pago_fk = " . $codigoProgramacionPago;
        $em->getConnection()->executeQuery($strSql);        
        return true;
    }        
}