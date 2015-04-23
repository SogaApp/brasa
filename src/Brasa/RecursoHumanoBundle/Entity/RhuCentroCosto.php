<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_centro_costo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCentroCostoRepository")
 */
class RhuCentroCosto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_costo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCentroCostoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    

    /**
     * @ORM\Column(name="codigo_periodo_pago_fk", type="integer", nullable=true)
     */    
    private $codigoPeriodoPagoFk;    
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_programado", type="date", nullable=true)
     */    
    private $fechaUltimoPagoProgramado;    
    
    /**
     * Si existen programaciones de pago pendientes
     * @ORM\Column(name="pago_abierto", type="boolean")
     */    
    private $pagoAbierto = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPeriodoPago", inversedBy="centrosCostosPeriodoPagoRel")
     * @ORM\JoinColumn(name="codigo_periodo_pago_fk", referencedColumnName="codigo_periodo_pago_pk")
     */
    protected $periodoPagoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionPago", mappedBy="centroCostoRel")
     */
    protected $programacionesPagosCentroCostoRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="centroCostoRel")
     */
    protected $empleadosCentroCostoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoAdicional", mappedBy="centroCostoRel")
     */
    protected $pagosAdicionalesCentroCostoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="centroCostoRel")
     */
    protected $incapacidadesCentroCostoRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programacionesPagosCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosAdicionalesCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCentroCostoPk
     *
     * @return integer
     */
    public function getCodigoCentroCostoPk()
    {
        return $this->codigoCentroCostoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCentroCosto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set codigoPeriodoPagoFk
     *
     * @param integer $codigoPeriodoPagoFk
     *
     * @return RhuCentroCosto
     */
    public function setCodigoPeriodoPagoFk($codigoPeriodoPagoFk)
    {
        $this->codigoPeriodoPagoFk = $codigoPeriodoPagoFk;

        return $this;
    }

    /**
     * Get codigoPeriodoPagoFk
     *
     * @return integer
     */
    public function getCodigoPeriodoPagoFk()
    {
        return $this->codigoPeriodoPagoFk;
    }

    /**
     * Set fechaUltimoPagoProgramado
     *
     * @param \DateTime $fechaUltimoPagoProgramado
     *
     * @return RhuCentroCosto
     */
    public function setFechaUltimoPagoProgramado($fechaUltimoPagoProgramado)
    {
        $this->fechaUltimoPagoProgramado = $fechaUltimoPagoProgramado;

        return $this;
    }

    /**
     * Get fechaUltimoPagoProgramado
     *
     * @return \DateTime
     */
    public function getFechaUltimoPagoProgramado()
    {
        return $this->fechaUltimoPagoProgramado;
    }

    /**
     * Set pagoAbierto
     *
     * @param boolean $pagoAbierto
     *
     * @return RhuCentroCosto
     */
    public function setPagoAbierto($pagoAbierto)
    {
        $this->pagoAbierto = $pagoAbierto;

        return $this;
    }

    /**
     * Get pagoAbierto
     *
     * @return boolean
     */
    public function getPagoAbierto()
    {
        return $this->pagoAbierto;
    }

    /**
     * Set periodoPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPeriodoPago $periodoPagoRel
     *
     * @return RhuCentroCosto
     */
    public function setPeriodoPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPeriodoPago $periodoPagoRel = null)
    {
        $this->periodoPagoRel = $periodoPagoRel;

        return $this;
    }

    /**
     * Get periodoPagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPeriodoPago
     */
    public function getPeriodoPagoRel()
    {
        return $this->periodoPagoRel;
    }

    /**
     * Add programacionesPagosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addProgramacionesPagosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosCentroCostoRel)
    {
        $this->programacionesPagosCentroCostoRel[] = $programacionesPagosCentroCostoRel;

        return $this;
    }

    /**
     * Remove programacionesPagosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosCentroCostoRel
     */
    public function removeProgramacionesPagosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosCentroCostoRel)
    {
        $this->programacionesPagosCentroCostoRel->removeElement($programacionesPagosCentroCostoRel);
    }

    /**
     * Get programacionesPagosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesPagosCentroCostoRel()
    {
        return $this->programacionesPagosCentroCostoRel;
    }

    /**
     * Add empleadosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addEmpleadosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCentroCostoRel)
    {
        $this->empleadosCentroCostoRel[] = $empleadosCentroCostoRel;

        return $this;
    }

    /**
     * Remove empleadosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCentroCostoRel
     */
    public function removeEmpleadosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCentroCostoRel)
    {
        $this->empleadosCentroCostoRel->removeElement($empleadosCentroCostoRel);
    }

    /**
     * Get empleadosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosCentroCostoRel()
    {
        return $this->empleadosCentroCostoRel;
    }

    /**
     * Add pagosAdicionalesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addPagosAdicionalesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesCentroCostoRel)
    {
        $this->pagosAdicionalesCentroCostoRel[] = $pagosAdicionalesCentroCostoRel;

        return $this;
    }

    /**
     * Remove pagosAdicionalesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesCentroCostoRel
     */
    public function removePagosAdicionalesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesCentroCostoRel)
    {
        $this->pagosAdicionalesCentroCostoRel->removeElement($pagosAdicionalesCentroCostoRel);
    }

    /**
     * Get pagosAdicionalesCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosAdicionalesCentroCostoRel()
    {
        return $this->pagosAdicionalesCentroCostoRel;
    }

    /**
     * Add incapacidadesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addIncapacidadesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesCentroCostoRel)
    {
        $this->incapacidadesCentroCostoRel[] = $incapacidadesCentroCostoRel;

        return $this;
    }

    /**
     * Remove incapacidadesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesCentroCostoRel
     */
    public function removeIncapacidadesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesCentroCostoRel)
    {
        $this->incapacidadesCentroCostoRel->removeElement($incapacidadesCentroCostoRel);
    }

    /**
     * Get incapacidadesCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesCentroCostoRel()
    {
        return $this->incapacidadesCentroCostoRel;
    }
}
