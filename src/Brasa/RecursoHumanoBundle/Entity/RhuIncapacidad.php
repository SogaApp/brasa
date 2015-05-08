<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_incapacidad")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuIncapacidadRepository")
 */
class RhuIncapacidad
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_incapacidad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIncapacidadPk;                    
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;    
    
    /**
     * @ORM\Column(name="numero_eps", type="string", length=30, nullable=true)
     */    
    private $numeroEps;     
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;            
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad = 0;                

    /**
     * @ORM\Column(name="cantidadPendiente", type="integer")
     */
    private $cantidadPendiente = 0;    
    
    /**
     * @ORM\Column(name="incapacidad_general", type="boolean")
     */    
    private $incapacidadGeneral = 0;     
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;   
    
    /**
     * @ORM\Column(name="codigo_incapacidad_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoIncapacidadTipoFk;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="incapacidadesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuIncapacidadTipo", inversedBy="incapacidadesIncapacidadTipoRel")
     * @ORM\JoinColumn(name="codigo_incapacidad_tipo_fk", referencedColumnName="codigo_incapacidad_tipo_pk")
     */
    protected $incapacidadTipoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="incapacidadesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    

    /**
     * Get codigoIncapacidadPk
     *
     * @return integer
     */
    public function getCodigoIncapacidadPk()
    {
        return $this->codigoIncapacidadPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuIncapacidad
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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return RhuIncapacidad
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
     * Set cantidadPendiente
     *
     * @param integer $cantidadPendiente
     *
     * @return RhuIncapacidad
     */
    public function setCantidadPendiente($cantidadPendiente)
    {
        $this->cantidadPendiente = $cantidadPendiente;

        return $this;
    }

    /**
     * Get cantidadPendiente
     *
     * @return integer
     */
    public function getCantidadPendiente()
    {
        return $this->cantidadPendiente;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuIncapacidad
     */
    public function setEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuIncapacidad
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
     * Set incapacidadGeneral
     *
     * @param boolean $incapacidadGeneral
     *
     * @return RhuIncapacidad
     */
    public function setIncapacidadGeneral($incapacidadGeneral)
    {
        $this->incapacidadGeneral = $incapacidadGeneral;

        return $this;
    }

    /**
     * Get incapacidadGeneral
     *
     * @return boolean
     */
    public function getIncapacidadGeneral()
    {
        return $this->incapacidadGeneral;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuIncapacidad
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
     * @return RhuIncapacidad
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

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuIncapacidad
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
     * @return RhuIncapacidad
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
     * Set numeroIncapacidadEps
     *
     * @param string $numeroIncapacidadEps
     *
     * @return RhuIncapacidad
     */
    public function setNumeroIncapacidadEps($numeroIncapacidadEps)
    {
        $this->numeroIncapacidadEps = $numeroIncapacidadEps;

        return $this;
    }

    /**
     * Get numeroIncapacidadEps
     *
     * @return string
     */
    public function getNumeroIncapacidadEps()
    {
        return $this->numeroIncapacidadEps;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuIncapacidad
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set numeroEps
     *
     * @param string $numeroEps
     *
     * @return RhuIncapacidad
     */
    public function setNumeroEps($numeroEps)
    {
        $this->numeroEps = $numeroEps;

        return $this;
    }

    /**
     * Get numeroEps
     *
     * @return string
     */
    public function getNumeroEps()
    {
        return $this->numeroEps;
    }

    /**
     * Set codigoIncapacidadTipoFk
     *
     * @param integer $codigoIncapacidadTipoFk
     *
     * @return RhuIncapacidad
     */
    public function setCodigoIncapacidadTipoFk($codigoIncapacidadTipoFk)
    {
        $this->codigoIncapacidadTipoFk = $codigoIncapacidadTipoFk;

        return $this;
    }

    /**
     * Get codigoIncapacidadTipoFk
     *
     * @return integer
     */
    public function getCodigoIncapacidadTipoFk()
    {
        return $this->codigoIncapacidadTipoFk;
    }

    /**
     * Set incapacidadTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadTipo $incapacidadTipoRel
     *
     * @return RhuIncapacidad
     */
    public function setIncapacidadTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadTipo $incapacidadTipoRel = null)
    {
        $this->incapacidadTipoRel = $incapacidadTipoRel;

        return $this;
    }

    /**
     * Get incapacidadTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadTipo
     */
    public function getIncapacidadTipoRel()
    {
        return $this->incapacidadTipoRel;
    }
}
