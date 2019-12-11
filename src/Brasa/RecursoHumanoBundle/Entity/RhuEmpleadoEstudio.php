<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado_estudio")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoEstudioRepository")
 */
class RhuEmpleadoEstudio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_estudio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoEstudioPk;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="codigo_empleado_estudio_tipo_fk", type="integer")
     */    
    private $codigoEmpleadoEstudioTipoFk;
    
    /**
     * @ORM\Column(name="institucion", type="string", length=150, nullable=true)
     */    
    private $institucion;
    
  
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadFk;
    
    /**
     * @ORM\Column(name="titulo", type="string", length=120, nullable=true)
     */    
    private $titulo;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */     
    
    private $fecha;
    
    /**
     * @ORM\Column(name="fecha_inicio", type="date", nullable=true)
     */     
    
    private $fechaInicio;
    
    /**
     * @ORM\Column(name="fecha_terminacion", type="date", nullable=true)
     */     
    
    private $fechaTerminacion;
    
    /**
     * @ORM\Column(name="fecha_vencimiento_curso", type="date", nullable=true)
     */     
    
    private $fechaVencimientoCurso;
    
    /**
     * @ORM\Column(name="fecha_inicio_acreditacion", type="date", nullable=true)
     */     
    
    private $fechaInicioAcreditacion;
    
    /**
     * @ORM\Column(name="fecha_vencimiento_acreditacion", type="date", nullable=true)
     */     
    
    private $fechaVencimientoAcreditacion;
    
    /**     
     * @ORM\Column(name="validar_vencimiento", type="boolean")
     */    
    private $validarVencimiento = false;
    
    /**
     * @ORM\Column(name="codigo_grado_bachiller_fk", type="integer", nullable=true)
     */    
    private $codigoGradoBachillerFk;
    
    /**
     * @ORM\Column(name="codigo_academia_fk", type="integer", nullable=true)
     */    
    private $codigoAcademiaFk;
    
    /**     
     * @ORM\Column(name="graduado", type="boolean")
     */    
    private $graduado = false;
    
    /**
     * @ORM\Column(name="numero_registro", type="string", length=20, nullable=true)
     */    
    private $numeroRegistro;
    
    /**
     * @ORM\Column(name="numero_acreditacion", type="string", length=20, nullable=true)
     */    
    private $numeroAcreditacion;
    
    /**
     * @ORM\Column(name="codigo_estudio_tipo_acreditacion_fk", type="integer", nullable=true)
     */    
    private $codigoEstudioTipoAcreditacionFk;
    
    /**
     * @ORM\Column(name="codigo_estudio_estado_fk", type="integer", nullable=true)
     */    
    private $codigoEstudioEstadoFk;
    
    /**
     * @ORM\Column(name="codigo_estudio_estado_invalido_fk", type="integer", nullable=true)
     */    
    private $codigoEstudioEstadoInvalidoFk;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\Column(name="fecha_estado", type="date", nullable=true)
     */     
    
    private $fechaEstado;
    
    /**
     * @ORM\Column(name="fecha_estado_invalido", type="date", nullable=true)
     */     
    
    private $fechaEstadoInvalido;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="empleadosEstudiosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleadoEstudioTipo", inversedBy="empleadosEstudiosEmpleadoEstudioTipoRel")
     * @ORM\JoinColumn(name="codigo_empleado_estudio_tipo_fk", referencedColumnName="codigo_empleado_estudio_tipo_pk")
     */
    protected $empleadoEstudioTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuEmpleadosEstudiosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuAcademia", inversedBy="empleadosEstudiosAcademiaRel")
     * @ORM\JoinColumn(name="codigo_academia_fk", referencedColumnName="codigo_academia_pk")
     */
    protected $academiaRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuGradoBachiller", inversedBy="empleadosEstudiosGradoBachillerRel")
     * @ORM\JoinColumn(name="codigo_grado_bachiller_fk", referencedColumnName="codigo_grado_bachiller_pk")
     */
    protected $gradoBachillerRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEstudioTipoAcreditacion", inversedBy="empleadosEstudiosEstudioTipoAcreditacionRel")
     * @ORM\JoinColumn(name="codigo_estudio_tipo_acreditacion_fk", referencedColumnName="codigo_estudio_tipo_acreditacion_pk")
     */
    protected $estudioTipoAcreditacionRel;


    /**
     * @ORM\ManyToOne(targetEntity="RhuEstudioEstado", inversedBy="empleadosEstudiosEstudioEstadoRel")
     * @ORM\JoinColumn(name="codigo_estudio_estado_fk", referencedColumnName="codigo_estudio_estado_pk")
     */
    protected $estudioEstadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEstudioEstadoInvalido", inversedBy="empleadosEstudiosEstudioEstadoInvalidoRel")
     * @ORM\JoinColumn(name="codigo_estudio_estado_invalido_fk", referencedColumnName="codigo_estudio_estado_invalido_pk")
     */
    protected $estudioEstadoInvalidoRel;
    
    
    

    

    /**
     * Get codigoEmpleadoEstudioPk
     *
     * @return integer
     */
    public function getCodigoEmpleadoEstudioPk()
    {
        return $this->codigoEmpleadoEstudioPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuEmpleadoEstudio
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
     * Set codigoEmpleadoEstudioTipoFk
     *
     * @param integer $codigoEmpleadoEstudioTipoFk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoEmpleadoEstudioTipoFk($codigoEmpleadoEstudioTipoFk)
    {
        $this->codigoEmpleadoEstudioTipoFk = $codigoEmpleadoEstudioTipoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoEstudioTipoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoEstudioTipoFk()
    {
        return $this->codigoEmpleadoEstudioTipoFk;
    }

    /**
     * Set institucion
     *
     * @param string $institucion
     *
     * @return RhuEmpleadoEstudio
     */
    public function setInstitucion($institucion)
    {
        $this->institucion = $institucion;

        return $this;
    }

    /**
     * Get institucion
     *
     * @return string
     */
    public function getInstitucion()
    {
        return $this->institucion;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoCiudadFk($codigoCiudadFk)
    {
        $this->codigoCiudadFk = $codigoCiudadFk;

        return $this;
    }

    /**
     * Get codigoCiudadFk
     *
     * @return integer
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     *
     * @return RhuEmpleadoEstudio
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuEmpleadoEstudio
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return RhuEmpleadoEstudio
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaTerminacion
     *
     * @param \DateTime $fechaTerminacion
     *
     * @return RhuEmpleadoEstudio
     */
    public function setFechaTerminacion($fechaTerminacion)
    {
        $this->fechaTerminacion = $fechaTerminacion;

        return $this;
    }

    /**
     * Get fechaTerminacion
     *
     * @return \DateTime
     */
    public function getFechaTerminacion()
    {
        return $this->fechaTerminacion;
    }

    /**
     * Set fechaVencimientoCurso
     *
     * @param \DateTime $fechaVencimientoCurso
     *
     * @return RhuEmpleadoEstudio
     */
    public function setFechaVencimientoCurso($fechaVencimientoCurso)
    {
        $this->fechaVencimientoCurso = $fechaVencimientoCurso;

        return $this;
    }

    /**
     * Get fechaVencimientoCurso
     *
     * @return \DateTime
     */
    public function getFechaVencimientoCurso()
    {
        return $this->fechaVencimientoCurso;
    }

    /**
     * Set fechaInicioAcreditacion
     *
     * @param \DateTime $fechaInicioAcreditacion
     *
     * @return RhuEmpleadoEstudio
     */
    public function setFechaInicioAcreditacion($fechaInicioAcreditacion)
    {
        $this->fechaInicioAcreditacion = $fechaInicioAcreditacion;

        return $this;
    }

    /**
     * Get fechaInicioAcreditacion
     *
     * @return \DateTime
     */
    public function getFechaInicioAcreditacion()
    {
        return $this->fechaInicioAcreditacion;
    }

    /**
     * Set fechaVencimientoAcreditacion
     *
     * @param \DateTime $fechaVencimientoAcreditacion
     *
     * @return RhuEmpleadoEstudio
     */
    public function setFechaVencimientoAcreditacion($fechaVencimientoAcreditacion)
    {
        $this->fechaVencimientoAcreditacion = $fechaVencimientoAcreditacion;

        return $this;
    }

    /**
     * Get fechaVencimientoAcreditacion
     *
     * @return \DateTime
     */
    public function getFechaVencimientoAcreditacion()
    {
        return $this->fechaVencimientoAcreditacion;
    }

    /**
     * Set validarVencimiento
     *
     * @param boolean $validarVencimiento
     *
     * @return RhuEmpleadoEstudio
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
     * Set codigoGradoBachillerFk
     *
     * @param integer $codigoGradoBachillerFk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoGradoBachillerFk($codigoGradoBachillerFk)
    {
        $this->codigoGradoBachillerFk = $codigoGradoBachillerFk;

        return $this;
    }

    /**
     * Get codigoGradoBachillerFk
     *
     * @return integer
     */
    public function getCodigoGradoBachillerFk()
    {
        return $this->codigoGradoBachillerFk;
    }

    /**
     * Set codigoAcademiaFk
     *
     * @param integer $codigoAcademiaFk
     *
     * @return RhuEmpleadoEstudio
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
     * Set graduado
     *
     * @param boolean $graduado
     *
     * @return RhuEmpleadoEstudio
     */
    public function setGraduado($graduado)
    {
        $this->graduado = $graduado;

        return $this;
    }

    /**
     * Get graduado
     *
     * @return boolean
     */
    public function getGraduado()
    {
        return $this->graduado;
    }

    /**
     * Set numeroRegistro
     *
     * @param string $numeroRegistro
     *
     * @return RhuEmpleadoEstudio
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
     * Set numeroAcreditacion
     *
     * @param string $numeroAcreditacion
     *
     * @return RhuEmpleadoEstudio
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
     * Set codigoEstudioTipoAcreditacionFk
     *
     * @param integer $codigoEstudioTipoAcreditacionFk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoEstudioTipoAcreditacionFk($codigoEstudioTipoAcreditacionFk)
    {
        $this->codigoEstudioTipoAcreditacionFk = $codigoEstudioTipoAcreditacionFk;

        return $this;
    }

    /**
     * Get codigoEstudioTipoAcreditacionFk
     *
     * @return integer
     */
    public function getCodigoEstudioTipoAcreditacionFk()
    {
        return $this->codigoEstudioTipoAcreditacionFk;
    }

    /**
     * Set codigoEstudioEstadoFk
     *
     * @param integer $codigoEstudioEstadoFk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoEstudioEstadoFk($codigoEstudioEstadoFk)
    {
        $this->codigoEstudioEstadoFk = $codigoEstudioEstadoFk;

        return $this;
    }

    /**
     * Get codigoEstudioEstadoFk
     *
     * @return integer
     */
    public function getCodigoEstudioEstadoFk()
    {
        return $this->codigoEstudioEstadoFk;
    }

    /**
     * Set codigoEstudioEstadoInvalidoFk
     *
     * @param integer $codigoEstudioEstadoInvalidoFk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoEstudioEstadoInvalidoFk($codigoEstudioEstadoInvalidoFk)
    {
        $this->codigoEstudioEstadoInvalidoFk = $codigoEstudioEstadoInvalidoFk;

        return $this;
    }

    /**
     * Get codigoEstudioEstadoInvalidoFk
     *
     * @return integer
     */
    public function getCodigoEstudioEstadoInvalidoFk()
    {
        return $this->codigoEstudioEstadoInvalidoFk;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuEmpleadoEstudio
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
     * @return RhuEmpleadoEstudio
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
     * Set fechaEstado
     *
     * @param \DateTime $fechaEstado
     *
     * @return RhuEmpleadoEstudio
     */
    public function setFechaEstado($fechaEstado)
    {
        $this->fechaEstado = $fechaEstado;

        return $this;
    }

    /**
     * Get fechaEstado
     *
     * @return \DateTime
     */
    public function getFechaEstado()
    {
        return $this->fechaEstado;
    }

    /**
     * Set fechaEstadoInvalido
     *
     * @param \DateTime $fechaEstadoInvalido
     *
     * @return RhuEmpleadoEstudio
     */
    public function setFechaEstadoInvalido($fechaEstadoInvalido)
    {
        $this->fechaEstadoInvalido = $fechaEstadoInvalido;

        return $this;
    }

    /**
     * Get fechaEstadoInvalido
     *
     * @return \DateTime
     */
    public function getFechaEstadoInvalido()
    {
        return $this->fechaEstadoInvalido;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuEmpleadoEstudio
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
     * Set empleadoEstudioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo $empleadoEstudioTipoRel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setEmpleadoEstudioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo $empleadoEstudioTipoRel = null)
    {
        $this->empleadoEstudioTipoRel = $empleadoEstudioTipoRel;

        return $this;
    }

    /**
     * Get empleadoEstudioTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo
     */
    public function getEmpleadoEstudioTipoRel()
    {
        return $this->empleadoEstudioTipoRel;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCiudadRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel = null)
    {
        $this->ciudadRel = $ciudadRel;

        return $this;
    }

    /**
     * Get ciudadRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * Set academiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAcademia $academiaRel
     *
     * @return RhuEmpleadoEstudio
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
     * Set gradoBachillerRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuGradoBachiller $gradoBachillerRel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setGradoBachillerRel(\Brasa\RecursoHumanoBundle\Entity\RhuGradoBachiller $gradoBachillerRel = null)
    {
        $this->gradoBachillerRel = $gradoBachillerRel;

        return $this;
    }

    /**
     * Get gradoBachillerRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuGradoBachiller
     */
    public function getGradoBachillerRel()
    {
        return $this->gradoBachillerRel;
    }

    /**
     * Set estudioTipoAcreditacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEstudioTipoAcreditacion $estudioTipoAcreditacionRel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setEstudioTipoAcreditacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEstudioTipoAcreditacion $estudioTipoAcreditacionRel = null)
    {
        $this->estudioTipoAcreditacionRel = $estudioTipoAcreditacionRel;

        return $this;
    }

    /**
     * Get estudioTipoAcreditacionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEstudioTipoAcreditacion
     */
    public function getEstudioTipoAcreditacionRel()
    {
        return $this->estudioTipoAcreditacionRel;
    }

    /**
     * Set estudioEstadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEstudioEstado $estudioEstadoRel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setEstudioEstadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEstudioEstado $estudioEstadoRel = null)
    {
        $this->estudioEstadoRel = $estudioEstadoRel;

        return $this;
    }

    /**
     * Get estudioEstadoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEstudioEstado
     */
    public function getEstudioEstadoRel()
    {
        return $this->estudioEstadoRel;
    }

    /**
     * Set estudioEstadoInvalidoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEstudioEstadoInvalido $estudioEstadoInvalidoRel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setEstudioEstadoInvalidoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEstudioEstadoInvalido $estudioEstadoInvalidoRel = null)
    {
        $this->estudioEstadoInvalidoRel = $estudioEstadoInvalidoRel;

        return $this;
    }

    /**
     * Get estudioEstadoInvalidoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEstudioEstadoInvalido
     */
    public function getEstudioEstadoInvalidoRel()
    {
        return $this->estudioEstadoInvalidoRel;
    }
}
