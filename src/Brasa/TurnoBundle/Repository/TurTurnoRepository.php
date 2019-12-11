<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurTurnoRepository extends EntityRepository {
    public function listaDQL() {
        $dql   = "SELECT t FROM BrasaTurnoBundle:TurTurno t WHERE t.codigoTurnoPk <> ''";
        return $dql;
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }    
}