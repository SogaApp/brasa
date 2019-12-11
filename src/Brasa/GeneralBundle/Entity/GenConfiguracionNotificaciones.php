<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_configuracion_notificaciones")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenConfiguracionNotificacionesRepository")
 */
class GenConfiguracionNotificaciones
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_notificaciones_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoConfiguracionNotificacionesPk;
    
    
    /**
     * @ORM\Column(name="correo_turno_inconsistencia", type="string", length=200, nullable=true)
     */      
    private $correoTurnoInconsistencia;



    /**
     * Get codigoConfiguracionNotificacionesPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionNotificacionesPk()
    {
        return $this->codigoConfiguracionNotificacionesPk;
    }

    /**
     * Set correoTurnoInconsistencia
     *
     * @param string $correoTurnoInconsistencia
     *
     * @return GenConfiguracionNotificaciones
     */
    public function setCorreoTurnoInconsistencia($correoTurnoInconsistencia)
    {
        $this->correoTurnoInconsistencia = $correoTurnoInconsistencia;

        return $this;
    }

    /**
     * Get correoTurnoInconsistencia
     *
     * @return string
     */
    public function getCorreoTurnoInconsistencia()
    {
        return $this->correoTurnoInconsistencia;
    }
}
