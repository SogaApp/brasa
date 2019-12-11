<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_acreditacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuAcreditacionRepository")
 */
class RhuAcreditacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_acreditacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAcreditacionPk;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;              
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */     
    
    private $fecha;    

    /**
     * @ORM\Column(name="fechaVenceCurso", type="date", nullable=true)
     */     
    
    private $fechaVenceCurso;     
    
    /**
     * @ORM\Column(name="fecha_validacion", type="date", nullable=true)
     */         
    private $fechaValidacion;            
    
    /**
     * @ORM\Column(name="fecha_acreditacion", type="date", nullable=true)
     */         
    private $fechaAcreditacion;    
    
    /**
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */         
    private $fechaVencimiento;            
    
    /**
     * @ORM\Column(name="codigo_academia_fk", type="integer", nullable=true)
     */    
    private $codigoAcademiaFk;    
    
    /**
     * @ORM\Column(name="numero_registro", type="string", length=20, nullable=true)
     */    
    private $numeroRegistro;

    /**
     * @ORM\Column(name="numero_validacion", type="string", length=20, nullable=true)
     */    
    private $numeroValidacion;
    
    /**
     * @ORM\Column(name="numero_acreditacion", type="string", length=20, nullable=true)
     */    
    private $numeroAcreditacion;
    
    /**
     * @ORM\Column(name="codigo_acreditacion_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoAcreditacionTipoFk;        
    
    /**
     * @ORM\Column(name="codigo_acreditacion_rechazo_fk", type="integer", nullable=true)
     */    
    private $codigoAcreditacionRechazoFk;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;

    /**
     * @ORM\Column(name="detalle_validacion", type="string", length=150, nullable=true)
     */    
    private $detalleValidacion;    
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;    
    
    /**     
     * @ORM\Column(name="estado_validado", type="boolean")
     */    
    private $estadoValidado = false;    
    
    /**     
     * @ORM\Column(name="estado_acreditado", type="boolean")
     */    
    private $estadoAcreditado = false;    

    /**     
     * @ORM\Column(name="estado_rechazado", type="boolean")
     */    
    private $estadoRechazado = false; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="acreditacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuAcademia", inversedBy="acreditacionesAcademiaRel")
     * @ORM\JoinColumn(name="codigo_academia_fk", referencedColumnName="codigo_academia_pk")
     */
    protected $academiaRel;        
   
    /**
     * @ORM\ManyToOne(targetEntity="RhuAcreditacionTipo", inversedBy="acreditacionesAcreditacionTipoRel")
     * @ORM\JoinColumn(name="codigo_acreditacion_tipo_fk", referencedColumnName="codigo_acreditacion_tipo_pk")
     */
    protected $acreditacionTipoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuAcreditacionRechazo", inversedBy="acreditacionesAcreditacionRechazoRel")
     * @ORM\JoinColumn(name="codigo_acreditacion_rechazo_fk", referencedColumnName="codigo_acreditacion_rechazo_pk")
     */
    protected $acreditacionRechazoRel;     



    /**
     * Get codigoAcreditacionPk
     *
     * @return integer
     */
    public function getCodigoAcreditacionPk()
    {
        return $this->codigoAcreditacionPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuAcreditacion
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
     * @return RhuAcreditacion
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
     * Set fechaValidacion
     *
     * @param \DateTime $fechaValidacion
     *
     * @return RhuAcreditacion
     */
    public function setFechaValidacion($fechaValidacion)
    {
        $this->fechaValidacion = $fechaValidacion;

        return $this;
    }

    /**
     * Get fechaValidacion
     *
     * @return \DateTime
     */
    public function getFechaValidacion()
    {
        return $this->fechaValidacion;
    }

    /**
     * Set fechaAcreditacion
     *
     * @param \DateTime $fechaAcreditacion
     *
     * @return RhuAcreditacion
     */
    public function setFechaAcreditacion($fechaAcreditacion)
    {
        $this->fechaAcreditacion = $fechaAcreditacion;

        return $this;
    }

    /**
     * Get fechaAcreditacion
     *
     * @return \DateTime
     */
    public function getFechaAcreditacion()
    {
        return $this->fechaAcreditacion;
    }

    /**
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return RhuAcreditacion
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set codigoAcademiaFk
     *
     * @param integer $codigoAcademiaFk
     *
     * @return RhuAcreditacion
     */
    public function setCodigoAcademiaFk($codigoAcademiaFk)
    {
        $this->codigoAcademiaFk = $codigoAcademiaFk;

        return $this;
    }

    /**
     * Get codigoAcademiaFk
     *
     * @return integer
     */
    public function getCodigoAcademiaFk()
    {
        return $this->codigoAcademiaFk;
    }

    /**
     * Set numeroRegistro
     *
     * @param string $numeroRegistro
     *
     * @return RhuAcreditacion
     */
    public function setNumeroRegistro($numeroRegistro)
    {
        $this->numeroRegistro = $numeroRegistro;

        return $this;
    }

    /**
     * Get numeroRegistro
     *
     * @return string
     */
    public function getNumeroRegistro()
    {
        return $this->numeroRegistro;
    }

    /**
     * Set numeroValidacion
     *
     * @param string $numeroValidacion
     *
     * @return RhuAcreditacion
     */
    public function setNumeroValidacion($numeroValidacion)
    {
        $this->numeroValidacion = $numeroValidacion;

        return $this;
    }

    /**
     * Get numeroValidacion
     *
     * @return string
     */
    public function getNumeroValidacion()
    {
        return $this->numeroValidacion;
    }

    /**
     * Set numeroAcreditacion
     *
     * @param string $numeroAcreditacion
     *
     * @return RhuAcreditacion
     */
    public function setNumeroAcreditacion($numeroAcreditacion)
    {
        $this->numeroAcreditacion = $numeroAcreditacion;

        return $this;
    }

    /**
     * Get numeroAcreditacion
     *
     * @return string
     */
    public function getNumeroAcreditacion()
    {
        return $this->numeroAcreditacion;
    }

    /**
     * Set codigoAcreditacionTipoFk
     *
     * @param integer $codigoAcreditacionTipoFk
     *
     * @return RhuAcreditacion
     */
    public function setCodigoAcreditacionTipoFk($codigoAcreditacionTipoFk)
    {
        $this->codigoAcreditacionTipoFk = $codigoAcreditacionTipoFk;

        return $this;
    }

    /**
     * Get codigoAcreditacionTipoFk
     *
     * @return integer
     */
    public function getCodigoAcreditacionTipoFk()
    {
        return $this->codigoAcreditacionTipoFk;
    }

    /**
     * Set codigoAcreditacionRechazoFk
     *
     * @param integer $codigoAcreditacionRechazoFk
     *
     * @return RhuAcreditacion
     */
    public function setCodigoAcreditacionRechazoFk($codigoAcreditacionRechazoFk)
    {
        $this->codigoAcreditacionRechazoFk = $codigoAcreditacionRechazoFk;

        return $this;
    }

    /**
     * Get codigoAcreditacionRechazoFk
     *
     * @return integer
     */
    public function getCodigoAcreditacionRechazoFk()
    {
        return $this->codigoAcreditacionRechazoFk;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuAcreditacion
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
     * Set detalleValidacion
     *
     * @param string $detalleValidacion
     *
     * @return RhuAcreditacion
     */
    public function setDetalleValidacion($detalleValidacion)
    {
        $this->detalleValidacion = $detalleValidacion;

        return $this;
    }

    /**
     * Get detalleValidacion
     *
     * @return string
     */
    public function getDetalleValidacion()
    {
        return $this->detalleValidacion;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuAcreditacion
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
     * Set estadoValidado
     *
     * @param boolean $estadoValidado
     *
     * @return RhuAcreditacion
     */
    public function setEstadoValidado($estadoValidado)
    {
        $this->estadoValidado = $estadoValidado;

        return $this;
    }

    /**
     * Get estadoValidado
     *
     * @return boolean
     */
    public function getEstadoValidado()
    {
        return $this->estadoValidado;
    }

    /**
     * Set estadoAcreditado
     *
     * @param boolean $estadoAcreditado
     *
     * @return RhuAcreditacion
     */
    public function setEstadoAcreditado($estadoAcreditado)
    {
        $this->estadoAcreditado = $estadoAcreditado;

        return $this;
    }

    /**
     * Get estadoAcreditado
     *
     * @return boolean
     */
    public function getEstadoAcreditado()
    {
        return $this->estadoAcreditado;
    }

    /**
     * Set estadoRechazado
     *
     * @param boolean $estadoRechazado
     *
     * @return RhuAcreditacion
     */
    public function setEstadoRechazado($estadoRechazado)
    {
        $this->estadoRechazado = $estadoRechazado;

        return $this;
    }

    /**
     * Get estadoRechazado
     *
     * @return boolean
     */
    public function getEstadoRechazado()
    {
        return $this->estadoRechazado;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuAcreditacion
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
     * Set academiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAcademia $academiaRel
     *
     * @return RhuAcreditacion
     */
    public function setAcademiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuAcademia $academiaRel = null)
    {
        $this->academiaRel = $academiaRel;

        return $this;
    }

    /**
     * Get academiaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuAcademia
     */
    public function getAcademiaRel()
    {
        return $this->academiaRel;
    }

    /**
     * Set acreditacionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacionTipo $acreditacionTipoRel
     *
     * @return RhuAcreditacion
     */
    public function setAcreditacionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAcreditacionTipo $acreditacionTipoRel = null)
    {
        $this->acreditacionTipoRel = $acreditacionTipoRel;

        return $this;
    }

    /**
     * Get acreditacionTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacionTipo
     */
    public function getAcreditacionTipoRel()
    {
        return $this->acreditacionTipoRel;
    }

    /**
     * Set acreditacionRechazoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacionRechazo $acreditacionRechazoRel
     *
     * @return RhuAcreditacion
     */
    public function setAcreditacionRechazoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAcreditacionRechazo $acreditacionRechazoRel = null)
    {
        $this->acreditacionRechazoRel = $acreditacionRechazoRel;

        return $this;
    }

    /**
     * Get acreditacionRechazoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacionRechazo
     */
    public function getAcreditacionRechazoRel()
    {
        return $this->acreditacionRechazoRel;
    }

    /**
     * Set fechaVenceCurso
     *
     * @param \DateTime $fechaVenceCurso
     *
     * @return RhuAcreditacion
     */
    public function setFechaVenceCurso($fechaVenceCurso)
    {
        $this->fechaVenceCurso = $fechaVenceCurso;

        return $this;
    }

    /**
     * Get fechaVenceCurso
     *
     * @return \DateTime
     */
    public function getFechaVenceCurso()
    {
        return $this->fechaVenceCurso;
    }
}
