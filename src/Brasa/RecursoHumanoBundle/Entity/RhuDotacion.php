<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_dotacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDotacionRepository")
 */
class RhuDotacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_dotacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDotacionPk;                    
    
    /**
     * @ORM\Column(name="numero_interno_referencia", type="integer", nullable=true)
     */    
    private $codigoInternoReferencia;        
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;            
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="codigo_dotacion_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoDotacionTipoFk;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;

    /**
     * @ORM\Column(name="fecha_entrega", type="date", nullable=true)
     */    
    private $fechaEntrega;    
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = 0;

    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios; 
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="dotacionesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;            
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="dotacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuDotacionTipo", inversedBy="dotacionesDotacionTipoRel")
     * @ORM\JoinColumn(name="codigo_dotacion_tipo_fk", referencedColumnName="codigo_dotacion_tipo_pk")
     */
    protected $dotacionTipoRel;
    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDotacionDetalle", mappedBy="dotacionRel")
     */
    protected $dotacionesDetallesDotacionRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dotacionesDetallesDotacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDotacionPk
     *
     * @return integer
     */
    public function getCodigoDotacionPk()
    {
        return $this->codigoDotacionPk;
    }

    /**
     * Set codigoInternoReferencia
     *
     * @param integer $codigoInternoReferencia
     *
     * @return RhuDotacion
     */
    public function setCodigoInternoReferencia($codigoInternoReferencia)
    {
        $this->codigoInternoReferencia = $codigoInternoReferencia;

        return $this;
    }

    /**
     * Get codigoInternoReferencia
     *
     * @return integer
     */
    public function getCodigoInternoReferencia()
    {
        return $this->codigoInternoReferencia;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuDotacion
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuDotacion
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
     * Set codigoDotacionTipoFk
     *
     * @param integer $codigoDotacionTipoFk
     *
     * @return RhuDotacion
     */
    public function setCodigoDotacionTipoFk($codigoDotacionTipoFk)
    {
        $this->codigoDotacionTipoFk = $codigoDotacionTipoFk;

        return $this;
    }

    /**
     * Get codigoDotacionTipoFk
     *
     * @return integer
     */
    public function getCodigoDotacionTipoFk()
    {
        return $this->codigoDotacionTipoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuDotacion
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
     * Set fechaEntrega
     *
     * @param \DateTime $fechaEntrega
     *
     * @return RhuDotacion
     */
    public function setFechaEntrega($fechaEntrega)
    {
        $this->fechaEntrega = $fechaEntrega;

        return $this;
    }

    /**
     * Get fechaEntrega
     *
     * @return \DateTime
     */
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuDotacion
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
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuDotacion
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuDotacion
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
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuDotacion
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
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuDotacion
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
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuDotacion
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
     * Set dotacionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionTipo $dotacionTipoRel
     *
     * @return RhuDotacion
     */
    public function setDotacionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionTipo $dotacionTipoRel = null)
    {
        $this->dotacionTipoRel = $dotacionTipoRel;

        return $this;
    }

    /**
     * Get dotacionTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDotacionTipo
     */
    public function getDotacionTipoRel()
    {
        return $this->dotacionTipoRel;
    }

    /**
     * Add dotacionesDetallesDotacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $dotacionesDetallesDotacionRel
     *
     * @return RhuDotacion
     */
    public function addDotacionesDetallesDotacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $dotacionesDetallesDotacionRel)
    {
        $this->dotacionesDetallesDotacionRel[] = $dotacionesDetallesDotacionRel;

        return $this;
    }

    /**
     * Remove dotacionesDetallesDotacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $dotacionesDetallesDotacionRel
     */
    public function removeDotacionesDetallesDotacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $dotacionesDetallesDotacionRel)
    {
        $this->dotacionesDetallesDotacionRel->removeElement($dotacionesDetallesDotacionRel);
    }

    /**
     * Get dotacionesDetallesDotacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDotacionesDetallesDotacionRel()
    {
        return $this->dotacionesDetallesDotacionRel;
    }
}
