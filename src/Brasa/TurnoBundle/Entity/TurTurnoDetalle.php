<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_turno_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurTurnoDetalleRepository")
 */
class TurTurnoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_turno_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTurnoDetallePk;  
    
    /**
     * @ORM\Column(name="codigo_turno_fk", type="string", length=5)
     */    
    private $codigoTurnoFk;        
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurTurno", inversedBy="turnosDetallesTurnoRel")
     * @ORM\JoinColumn(name="codigo_turno_fk", referencedColumnName="codigo_turno_pk")
     */
    protected $turnoRel;       

    

    /**
     * Get codigoTurnoDetallePk
     *
     * @return integer
     */
    public function getCodigoTurnoDetallePk()
    {
        return $this->codigoTurnoDetallePk;
    }

    /**
     * Set codigoTurnoFk
     *
     * @param string $codigoTurnoFk
     *
     * @return TurTurnoDetalle
     */
    public function setCodigoTurnoFk($codigoTurnoFk)
    {
        $this->codigoTurnoFk = $codigoTurnoFk;

        return $this;
    }

    /**
     * Get codigoTurnoFk
     *
     * @return string
     */
    public function getCodigoTurnoFk()
    {
        return $this->codigoTurnoFk;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return TurTurnoDetalle
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set turnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurTurno $turnoRel
     *
     * @return TurTurnoDetalle
     */
    public function setTurnoRel(\Brasa\TurnoBundle\Entity\TurTurno $turnoRel = null)
    {
        $this->turnoRel = $turnoRel;

        return $this;
    }

    /**
     * Get turnoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurTurno
     */
    public function getTurnoRel()
    {
        return $this->turnoRel;
    }
}
