<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_examen")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoExamenRepository")
 */
class RhuPagoExamen
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_examen_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoExamenPk;
    
    /**
     * @ORM\Column(name="codigo_entidad_examen_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadExamenFk;                 
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;
    
    /**
     * @ORM\Column(name="numero_soporte", type="string", length=20, nullable=true)
     */    
    private $numeroSoporte;
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;

    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadExamen", inversedBy="pagosExamenesEntidadExamenRel")
     * @ORM\JoinColumn(name="codigo_entidad_examen_fk", referencedColumnName="codigo_entidad_examen_pk")
     */
    protected $entidadExamenRel;
    
       /**
     * @ORM\OneToMany(targetEntity="RhuPagoExamenDetalle", mappedBy="pagoExamenRel")
     */
    protected $pagosExamenesDetallesPagoExamenRel;
    
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosExamenesDetallesPagoExamenRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPagoExamenPk
     *
     * @return integer
     */
    public function getCodigoPagoExamenPk()
    {
        return $this->codigoPagoExamenPk;
    }

    /**
     * Set codigoEntidadExamenFk
     *
     * @param integer $codigoEntidadExamenFk
     *
     * @return RhuPagoExamen
     */
    public function setCodigoEntidadExamenFk($codigoEntidadExamenFk)
    {
        $this->codigoEntidadExamenFk = $codigoEntidadExamenFk;

        return $this;
    }

    /**
     * Get codigoEntidadExamenFk
     *
     * @return integer
     */
    public function getCodigoEntidadExamenFk()
    {
        return $this->codigoEntidadExamenFk;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return RhuPagoExamen
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * Set numeroSoporte
     *
     * @param string $numeroSoporte
     *
     * @return RhuPagoExamen
     */
    public function setNumeroSoporte($numeroSoporte)
    {
        $this->numeroSoporte = $numeroSoporte;

        return $this;
    }

    /**
     * Get numeroSoporte
     *
     * @return string
     */
    public function getNumeroSoporte()
    {
        return $this->numeroSoporte;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuPagoExamen
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set entidadExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen $entidadExamenRel
     *
     * @return RhuPagoExamen
     */
    public function setEntidadExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen $entidadExamenRel = null)
    {
        $this->entidadExamenRel = $entidadExamenRel;

        return $this;
    }

    /**
     * Get entidadExamenRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen
     */
    public function getEntidadExamenRel()
    {
        return $this->entidadExamenRel;
    }

    /**
     * Add pagosExamenesDetallesPagoExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $pagosExamenesDetallesPagoExamenRel
     *
     * @return RhuPagoExamen
     */
    public function addPagosExamenesDetallesPagoExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $pagosExamenesDetallesPagoExamenRel)
    {
        $this->pagosExamenesDetallesPagoExamenRel[] = $pagosExamenesDetallesPagoExamenRel;

        return $this;
    }

    /**
     * Remove pagosExamenesDetallesPagoExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $pagosExamenesDetallesPagoExamenRel
     */
    public function removePagosExamenesDetallesPagoExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $pagosExamenesDetallesPagoExamenRel)
    {
        $this->pagosExamenesDetallesPagoExamenRel->removeElement($pagosExamenesDetallesPagoExamenRel);
    }

    /**
     * Get pagosExamenesDetallesPagoExamenRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosExamenesDetallesPagoExamenRel()
    {
        return $this->pagosExamenesDetallesPagoExamenRel;
    }
}
