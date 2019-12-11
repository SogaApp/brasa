<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_embargo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmbargoRepository")
 */
class RhuEmbargo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_embargo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmbargoPk;                          
    
    /**
     * @ORM\Column(name="codigo_embargo_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoEmbargoTipoFk;    
    
    /**
     * @ORM\Column(name="fecha", type="date")
     */    
    private $fecha;           
    
    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */    
    private $numero;     
           
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;                                                         
    
    /**     
     * @ORM\Column(name="estado_activo", type="boolean")
     */    
    private $estadoActivo = false;    
    
    /**     
     * @ORM\Column(name="valor_fijo", type="boolean")
     */    
    private $valorFijo = false;    
    
    /**     
     * @ORM\Column(name="porcentaje_devengado", type="boolean")
     */    
    private $porcentajeDevengado = false;    

    /**     
     * @ORM\Column(name="porcentaje_devengado_prestacional", type="boolean")
     */    
    private $porcentajeDevengadoPrestacional = false;     
    
    /**     
     * @ORM\Column(name="porcentaje_devengado_menos_descuento_ley", type="boolean")
     */    
    private $porcentajeDevengadoMenosDescuentoLey = false;    

    /**     
     * @ORM\Column(name="porcentajeExcedaSalarioMinimo", type="boolean")
     */    
    private $porcentajeExcedaSalarioMinimo = false; 
    
    /**     
     * @ORM\Column(name="partesExcedaSalarioMinimo", type="boolean")
     */    
    private $partesExcedaSalarioMinimo = false; 
    
    /**
     * @ORM\Column(name="partes", type="float")
     */
    private $partes = 0;    
    
    /**
     * @ORM\Column(name="valor", type="float")
     */
    private $valor = 0;

    /**
     * @ORM\Column(name="porcentaje", type="float")
     */
    private $porcentaje = 0;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
       
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmbargoTipo", inversedBy="embargosEmbargoTipoRel")
     * @ORM\JoinColumn(name="codigo_embargo_tipo_fk", referencedColumnName="codigo_embargo_tipo_pk")
     */
    protected $embargoTipoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="embargosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    


    /**
     * Get codigoEmbargoPk
     *
     * @return integer
     */
    public function getCodigoEmbargoPk()
    {
        return $this->codigoEmbargoPk;
    }

    /**
     * Set codigoEmbargoTipoFk
     *
     * @param integer $codigoEmbargoTipoFk
     *
     * @return RhuEmbargo
     */
    public function setCodigoEmbargoTipoFk($codigoEmbargoTipoFk)
    {
        $this->codigoEmbargoTipoFk = $codigoEmbargoTipoFk;

        return $this;
    }

    /**
     * Get codigoEmbargoTipoFk
     *
     * @return integer
     */
    public function getCodigoEmbargoTipoFk()
    {
        return $this->codigoEmbargoTipoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuEmbargo
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
     * Set numero
     *
     * @param string $numero
     *
     * @return RhuEmbargo
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuEmbargo
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
     * Set estadoActivo
     *
     * @param boolean $estadoActivo
     *
     * @return RhuEmbargo
     */
    public function setEstadoActivo($estadoActivo)
    {
        $this->estadoActivo = $estadoActivo;

        return $this;
    }

    /**
     * Get estadoActivo
     *
     * @return boolean
     */
    public function getEstadoActivo()
    {
        return $this->estadoActivo;
    }

    /**
     * Set valorFijo
     *
     * @param boolean $valorFijo
     *
     * @return RhuEmbargo
     */
    public function setValorFijo($valorFijo)
    {
        $this->valorFijo = $valorFijo;

        return $this;
    }

    /**
     * Get valorFijo
     *
     * @return boolean
     */
    public function getValorFijo()
    {
        return $this->valorFijo;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return RhuEmbargo
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuEmbargo
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuEmbargo
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
     * Set embargoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmbargoTipo $embargoTipoRel
     *
     * @return RhuEmbargo
     */
    public function setEmbargoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmbargoTipo $embargoTipoRel = null)
    {
        $this->embargoTipoRel = $embargoTipoRel;

        return $this;
    }

    /**
     * Get embargoTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmbargoTipo
     */
    public function getEmbargoTipoRel()
    {
        return $this->embargoTipoRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuEmbargo
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
     * Set porcentajeDevengado
     *
     * @param boolean $porcentajeDevengado
     *
     * @return RhuEmbargo
     */
    public function setPorcentajeDevengado($porcentajeDevengado)
    {
        $this->porcentajeDevengado = $porcentajeDevengado;

        return $this;
    }

    /**
     * Get porcentajeDevengado
     *
     * @return boolean
     */
    public function getPorcentajeDevengado()
    {
        return $this->porcentajeDevengado;
    }

    /**
     * Set porcentaje
     *
     * @param float $porcentaje
     *
     * @return RhuEmbargo
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return float
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Set partesExcedaSalarioMinimo
     *
     * @param boolean $partesExcedaSalarioMinimo
     *
     * @return RhuEmbargo
     */
    public function setPartesExcedaSalarioMinimo($partesExcedaSalarioMinimo)
    {
        $this->partesExcedaSalarioMinimo = $partesExcedaSalarioMinimo;

        return $this;
    }

    /**
     * Get partesExcedaSalarioMinimo
     *
     * @return boolean
     */
    public function getPartesExcedaSalarioMinimo()
    {
        return $this->partesExcedaSalarioMinimo;
    }

    /**
     * Set partes
     *
     * @param float $partes
     *
     * @return RhuEmbargo
     */
    public function setPartes($partes)
    {
        $this->partes = $partes;

        return $this;
    }

    /**
     * Get partes
     *
     * @return float
     */
    public function getPartes()
    {
        return $this->partes;
    }

    /**
     * Set porcentajeDevengadoMenosDescuentoLey
     *
     * @param boolean $porcentajeDevengadoMenosDescuentoLey
     *
     * @return RhuEmbargo
     */
    public function setPorcentajeDevengadoMenosDescuentoLey($porcentajeDevengadoMenosDescuentoLey)
    {
        $this->porcentajeDevengadoMenosDescuentoLey = $porcentajeDevengadoMenosDescuentoLey;

        return $this;
    }

    /**
     * Get porcentajeDevengadoMenosDescuentoLey
     *
     * @return boolean
     */
    public function getPorcentajeDevengadoMenosDescuentoLey()
    {
        return $this->porcentajeDevengadoMenosDescuentoLey;
    }

    /**
     * Set porcentajeDevengadoPrestacional
     *
     * @param boolean $porcentajeDevengadoPrestacional
     *
     * @return RhuEmbargo
     */
    public function setPorcentajeDevengadoPrestacional($porcentajeDevengadoPrestacional)
    {
        $this->porcentajeDevengadoPrestacional = $porcentajeDevengadoPrestacional;

        return $this;
    }

    /**
     * Get porcentajeDevengadoPrestacional
     *
     * @return boolean
     */
    public function getPorcentajeDevengadoPrestacional()
    {
        return $this->porcentajeDevengadoPrestacional;
    }

    /**
     * Set porcentajeExcedaSalarioMinimo
     *
     * @param boolean $porcentajeExcedaSalarioMinimo
     *
     * @return RhuEmbargo
     */
    public function setPorcentajeExcedaSalarioMinimo($porcentajeExcedaSalarioMinimo)
    {
        $this->porcentajeExcedaSalarioMinimo = $porcentajeExcedaSalarioMinimo;

        return $this;
    }

    /**
     * Get porcentajeExcedaSalarioMinimo
     *
     * @return boolean
     */
    public function getPorcentajeExcedaSalarioMinimo()
    {
        return $this->porcentajeExcedaSalarioMinimo;
    }
}
