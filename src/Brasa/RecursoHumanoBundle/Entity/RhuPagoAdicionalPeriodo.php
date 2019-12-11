<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_adicional_periodo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoAdicionalPeriodoRepository")
 */
class RhuPagoAdicionalPeriodo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_adicional_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoAdicionalPeriodoPk;                     
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     
    


    /**
     * Get codigoPagoAdicionalPeriodoPk
     *
     * @return integer
     */
    public function getCodigoPagoAdicionalPeriodoPk()
    {
        return $this->codigoPagoAdicionalPeriodoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuPagoAdicionalPeriodo
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }
}
