<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_contrato_prorroga")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuContratoProrrogaRepository")
 */
class RhuContratoProrroga
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_prorroga_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoProrrogaPk;        
    
    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContratoFk;         
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;    
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;
    
    /**
     * @ORM\Column(name="fecha_inicial_anterior", type="date", nullable=true)
     */    
    private $fechaInicialAnterior;
    
    /**
     * @ORM\Column(name="fecha_inicial_nueva", type="date", nullable=true)
     */    
    private $fechaInicialNueva;
    
    /**
     * @ORM\Column(name="fecha_final_anterior", type="date", nullable=true)
     */    
    private $fechaFinalAnterior;
    
    /**
     * @ORM\Column(name="fecha_final_nueva", type="date", nullable=true)
     */    
    private $fechaFinalNueva;
    
    /**
     * @ORM\Column(name="meses", type="integer")
     */
    private $meses = 0;    
    
    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */    
    private $detalle;
    
    /**     
     * @ORM\Column(name="estado_vigente", type="boolean")
     */    
    private $estadoVigente = 0;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
        
    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="contratosProrrogasContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;     



    /**
     * Get codigoContratoProrrogaPk
     *
     * @return integer
     */
    public function getCodigoContratoProrrogaPk()
    {
        return $this->codigoContratoProrrogaPk;
    }

    /**
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuContratoProrroga
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuContratoProrroga
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk)
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuContratoProrroga
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

    /**
     * Set fechaInicialAnterior
     *
     * @param \DateTime $fechaInicialAnterior
     *
     * @return RhuContratoProrroga
     */
    public function setFechaInicialAnterior($fechaInicialAnterior)
    {
        $this->fechaInicialAnterior = $fechaInicialAnterior;

        return $this;
    }

    /**
     * Get fechaInicialAnterior
     *
     * @return \DateTime
     */
    public function getFechaInicialAnterior()
    {
        return $this->fechaInicialAnterior;
    }

    /**
     * Set fechaInicialNueva
     *
     * @param \DateTime $fechaInicialNueva
     *
     * @return RhuContratoProrroga
     */
    public function setFechaInicialNueva($fechaInicialNueva)
    {
        $this->fechaInicialNueva = $fechaInicialNueva;

        return $this;
    }

    /**
     * Get fechaInicialNueva
     *
     * @return \DateTime
     */
    public function getFechaInicialNueva()
    {
        return $this->fechaInicialNueva;
    }

    /**
     * Set fechaFinalAnterior
     *
     * @param \DateTime $fechaFinalAnterior
     *
     * @return RhuContratoProrroga
     */
    public function setFechaFinalAnterior($fechaFinalAnterior)
    {
        $this->fechaFinalAnterior = $fechaFinalAnterior;

        return $this;
    }

    /**
     * Get fechaFinalAnterior
     *
     * @return \DateTime
     */
    public function getFechaFinalAnterior()
    {
        return $this->fechaFinalAnterior;
    }

    /**
     * Set fechaFinalNueva
     *
     * @param \DateTime $fechaFinalNueva
     *
     * @return RhuContratoProrroga
     */
    public function setFechaFinalNueva($fechaFinalNueva)
    {
        $this->fechaFinalNueva = $fechaFinalNueva;

        return $this;
    }

    /**
     * Get fechaFinalNueva
     *
     * @return \DateTime
     */
    public function getFechaFinalNueva()
    {
        return $this->fechaFinalNueva;
    }

    /**
     * Set meses
     *
     * @param integer $meses
     *
     * @return RhuContratoProrroga
     */
    public function setMeses($meses)
    {
        $this->meses = $meses;

        return $this;
    }

    /**
     * Get meses
     *
     * @return integer
     */
    public function getMeses()
    {
        return $this->meses;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return RhuContratoProrroga
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuContratoProrroga
     */
    public function setContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuContratoProrroga
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * Set estadoVigente
     *
     * @param boolean $estadoVigente
     *
     * @return RhuContratoProrroga
     */
    public function setEstadoVigente($estadoVigente)
    {
        $this->estadoVigente = $estadoVigente;

        return $this;
    }

    /**
     * Get estadoVigente
     *
     * @return boolean
     */
    public function getEstadoVigente()
    {
        return $this->estadoVigente;
    }
}
