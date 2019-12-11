<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_visita")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuVisitaRepository")
 */
class RhuVisita
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_visita_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVisitaPk;            
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;                 
    
    /**
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=true)
     */    
    private $fechaCreacion;    
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="codigo_visita_tipo_fk", type="integer")
     */    
    private $codigoVisitaTipoFk;
    
    /**
     * @ORM\Column(name="fecha_vence", type="date")
     */    
    private $fechaVence;    
    
    /**     
     * @ORM\Column(name="validar_vencimiento", type="boolean")
     */    
    private $validarVencimiento = false;
    
    /**
     * @ORM\Column(name="nombre_quien_visita", type="string", length=100, nullable=true)
     */    
    private $nombreQuienVisita;
    
    /**
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */    
    private $comentarios;    
       
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;
        
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="visitasEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;                        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuVisitaTipo", inversedBy="visitasVisitaTipoRel")
     * @ORM\JoinColumn(name="codigo_visita_tipo_fk", referencedColumnName="codigo_visita_tipo_pk")
     */
    protected $visitaTipoRel;

    

    /**
     * Get codigoVisitaPk
     *
     * @return integer
     */
    public function getCodigoVisitaPk()
    {
        return $this->codigoVisitaPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuVisita
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuVisita
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
     * Set codigoVisitaTipoFk
     *
     * @param integer $codigoVisitaTipoFk
     *
     * @return RhuVisita
     */
    public function setCodigoVisitaTipoFk($codigoVisitaTipoFk)
    {
        $this->codigoVisitaTipoFk = $codigoVisitaTipoFk;

        return $this;
    }

    /**
     * Get codigoVisitaTipoFk
     *
     * @return integer
     */
    public function getCodigoVisitaTipoFk()
    {
        return $this->codigoVisitaTipoFk;
    }

    /**
     * Set fechaVence
     *
     * @param \DateTime $fechaVence
     *
     * @return RhuVisita
     */
    public function setFechaVence($fechaVence)
    {
        $this->fechaVence = $fechaVence;

        return $this;
    }

    /**
     * Get fechaVence
     *
     * @return \DateTime
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * Set validarVencimiento
     *
     * @param boolean $validarVencimiento
     *
     * @return RhuVisita
     */
    public function setValidarVencimiento($validarVencimiento)
    {
        $this->validarVencimiento = $validarVencimiento;

        return $this;
    }

    /**
     * Get validarVencimiento
     *
     * @return boolean
     */
    public function getValidarVencimiento()
    {
        return $this->validarVencimiento;
    }

    /**
     * Set nombreQuienVisita
     *
     * @param string $nombreQuienVisita
     *
     * @return RhuVisita
     */
    public function setNombreQuienVisita($nombreQuienVisita)
    {
        $this->nombreQuienVisita = $nombreQuienVisita;

        return $this;
    }

    /**
     * Get nombreQuienVisita
     *
     * @return string
     */
    public function getNombreQuienVisita()
    {
        return $this->nombreQuienVisita;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuVisita
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
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuVisita
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuVisita
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
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuVisita
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
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuVisita
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
     * Set visitaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVisitaTipo $visitaTipoRel
     *
     * @return RhuVisita
     */
    public function setVisitaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuVisitaTipo $visitaTipoRel = null)
    {
        $this->visitaTipoRel = $visitaTipoRel;

        return $this;
    }

    /**
     * Get visitaTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuVisitaTipo
     */
    public function getVisitaTipoRel()
    {
        return $this->visitaTipoRel;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return RhuVisita
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }
}
