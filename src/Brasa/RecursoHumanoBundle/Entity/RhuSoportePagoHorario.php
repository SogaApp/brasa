<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_soporte_pago_horario")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSoportePagoHorarioRepository")
 */
class RhuSoportePagoHorario
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_soporte_pago_horario_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSoportePagoHorarioPk;               
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;        

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;            

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;    
    
    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = false;    
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="soportesPagosHorariosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSoportePagoHorarioDetalle", mappedBy="soportePagoHorarioRel", cascade={"persist", "remove"})
     */
    protected $soportesPagosHorariosDetallesSoportePagoHorarioRel; 

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->soportesPagosHorariosDetallesSoportePagoHorarioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSoportePagoHorarioPk
     *
     * @return integer
     */
    public function getCodigoSoportePagoHorarioPk()
    {
        return $this->codigoSoportePagoHorarioPk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuSoportePagoHorario
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return RhuSoportePagoHorario
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return RhuSoportePagoHorario
     */
    public function setEstadoGenerado($estadoGenerado)
    {
        $this->estadoGenerado = $estadoGenerado;

        return $this;
    }

    /**
     * Get estadoGenerado
     *
     * @return boolean
     */
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuSoportePagoHorario
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Add soportesPagosHorariosDetallesSoportePagoHorarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle $soportesPagosHorariosDetallesSoportePagoHorarioRel
     *
     * @return RhuSoportePagoHorario
     */
    public function addSoportesPagosHorariosDetallesSoportePagoHorarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle $soportesPagosHorariosDetallesSoportePagoHorarioRel)
    {
        $this->soportesPagosHorariosDetallesSoportePagoHorarioRel[] = $soportesPagosHorariosDetallesSoportePagoHorarioRel;

        return $this;
    }

    /**
     * Remove soportesPagosHorariosDetallesSoportePagoHorarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle $soportesPagosHorariosDetallesSoportePagoHorarioRel
     */
    public function removeSoportesPagosHorariosDetallesSoportePagoHorarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle $soportesPagosHorariosDetallesSoportePagoHorarioRel)
    {
        $this->soportesPagosHorariosDetallesSoportePagoHorarioRel->removeElement($soportesPagosHorariosDetallesSoportePagoHorarioRel);
    }

    /**
     * Get soportesPagosHorariosDetallesSoportePagoHorarioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosHorariosDetallesSoportePagoHorarioRel()
    {
        return $this->soportesPagosHorariosDetallesSoportePagoHorarioRel;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuSoportePagoHorario
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return integer
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuSoportePagoHorario
     */
    public function setCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }
}
