<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionRepository")
 */
class RhuSeleccion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionPk;

    /**
     * @ORM\Column(name="codigo_seleccion_requisito_fk", type="integer", nullable=true)
     */
    private $codigoSeleccionRequisitoFk;

    /**
     * @ORM\Column(name="codigo_seleccion_tipo_fk", type="integer")
     */
    private $codigoSeleccionTipoFk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_tipo_identificacion_fk", type="integer")
     */
    private $codigoTipoIdentificacionFk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="nombre1", type="string", length=30, nullable=true)
     */
    private $nombre1;

    /**
     * @ORM\Column(name="nombre2", type="string", length=30, nullable=true)
     */
    private $nombre2;

    /**
     * @ORM\Column(name="apellido1", type="string", length=30, nullable=true)
     */
    private $apellido1;

    /**
     * @ORM\Column(name="apellido2", type="string", length=30, nullable=true)
     */
    private $apellido2;

    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */
    private $celular;

    /**
     * @ORM\Column(name="direccion", type="string", length=150, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="barrio", type="string", length=100, nullable=true)
     */
    private $barrio;


    /**
     * @ORM\Column(name="codigo_rh_fk", type="integer", nullable=true)
     */
    private $codigoRhFk;

     /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=1, nullable=true)
     */
    private $codigoEstadoCivilFk;

    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */
    private $codigoSexoFk;

    /**
     * @ORM\Column(name="correo", type="string", length=80, nullable=true)
     */
    private $correo;

    /**
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */
    private $fechaNacimiento;

    /**
     * @ORM\Column(name="codigo_ciudad_nacimiento_fk", type="integer", nullable=true)
     */
    private $codigoCiudadNacimientoFk;

    /**
     * @ORM\Column(name="codigo_ciudad_expedicion_fk", type="integer", nullable=true)
     */
    private $codigoCiudadExpedicionFk;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */
    private $estadoAprobado = 0;

    /**
     * @ORM\Column(name="presenta_pruebas", type="boolean")
     */
    private $presentaPruebas = 0;

    /**
     * @ORM\Column(name="referencias_verificadas", type="boolean")
     */
    private $referenciasVerificadas = 0;

    /**
     * @ORM\Column(name="estado_cobrado", type="boolean")
     */
    private $estadoCobrado = 0;

    /**
     * @ORM\Column(name="fecha_entrevista", type="datetime", nullable=true)
     */
    private $fecha_entrevista;

    /**
     * @ORM\Column(name="fecha_pruebas", type="datetime", nullable=true)
     */
    private $fecha_pruebas;

    /**
     * @ORM\Column(name="vr_servicio", type="float")
     */
    private $vrServicio = 0;

    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer", nullable=true)
     */
    private $codigoFacturaFk;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */
    private $estadoCerrado = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = 0;
    
    /**
     * @ORM\Column(name="fechaCierre", type="datetime", nullable=true)
     */
    private $fechaCierre;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;

    /**
     * @ORM\Column(name="codigo_zona_fk", type="integer", nullable=true)
     */    
    private $codigoZonaFk;

    /**
     * @ORM\Column(name="codigo_motivo_cierre_seleccion_fk", type="integer", nullable=true)
     */    
    private $codigoMotivoCierreSeleccionFk;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccionTipo", inversedBy="seleccionesSeleccionTipoRel")
     * @ORM\JoinColumn(name="codigo_seleccion_tipo_fk", referencedColumnName="codigo_seleccion_tipo_pk")
     */
    protected $seleccionTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTipoIdentificacion", inversedBy="rhuSeleccionesTipoIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_tipo_identificacion_fk", referencedColumnName="codigo_tipo_identificacion_pk")
     */
    protected $tipoIdentificacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEstadoCivil", inversedBy="seleccionesEstadoCivilRel")
     * @ORM\JoinColumn(name="codigo_estado_civil_fk", referencedColumnName="codigo_estado_civil_pk")
     */
    protected $estadoCivilRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="seleccionesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuSeleccionesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuSeleccionesCiudadNacimientoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_nacimiento_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadNacimientoRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuSeleccionesCiudadExpedicionRel")
     * @ORM\JoinColumn(name="codigo_ciudad_expedicion_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadExpedicionRel;


    /**
     * @ORM\ManyToOne(targetEntity="RhuRh", inversedBy="seleccionesRhRel")
     * @ORM\JoinColumn(name="codigo_rh_fk", referencedColumnName="codigo_rh_pk")
     */
    protected $rhRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccionRequisito", inversedBy="seleccionesSeleccionRequisitoRel")
     * @ORM\JoinColumn(name="codigo_seleccion_requisito_fk", referencedColumnName="codigo_seleccion_requisito_pk")
     */
    protected $seleccionRequisitoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuFactura", inversedBy="seleccionesFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="seleccionesCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuZona", inversedBy="seleccionesZonaRel")
     * @ORM\JoinColumn(name="codigo_zona_fk", referencedColumnName="codigo_zona_pk")
     */
    protected $zonaRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuMotivoCierreSeleccion", inversedBy="seleccionesMotivoCierreSeleccionRel")
     * @ORM\JoinColumn(name="codigo_motivo_cierre_seleccion_fk", referencedColumnName="codigo_motivo_cierre_seleccion_pk")
     */
    protected $motivoCierreSeleccionRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionReferencia", mappedBy="seleccionRel")
     */
    protected $seleccionesReferenciasSeleccionRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionPrueba", mappedBy="seleccionRel")
     */
    protected $seleccionesPruebasSeleccionRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionVisita", mappedBy="seleccionRel")
     */
    protected $seleccionesVisitasSeleccionRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionEntrevista", mappedBy="seleccionRel")
     */
    protected $seleccionesEntrevistasSeleccionRel;
    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionesReferenciasSeleccionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesPruebasSeleccionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesVisitasSeleccionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesEntrevistasSeleccionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSeleccionPk
     *
     * @return integer
     */
    public function getCodigoSeleccionPk()
    {
        return $this->codigoSeleccionPk;
    }

    /**
     * Set codigoSeleccionRequisitoFk
     *
     * @param integer $codigoSeleccionRequisitoFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoSeleccionRequisitoFk($codigoSeleccionRequisitoFk)
    {
        $this->codigoSeleccionRequisitoFk = $codigoSeleccionRequisitoFk;

        return $this;
    }

    /**
     * Get codigoSeleccionRequisitoFk
     *
     * @return integer
     */
    public function getCodigoSeleccionRequisitoFk()
    {
        return $this->codigoSeleccionRequisitoFk;
    }

    /**
     * Set codigoSeleccionTipoFk
     *
     * @param integer $codigoSeleccionTipoFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoSeleccionTipoFk($codigoSeleccionTipoFk)
    {
        $this->codigoSeleccionTipoFk = $codigoSeleccionTipoFk;

        return $this;
    }

    /**
     * Get codigoSeleccionTipoFk
     *
     * @return integer
     */
    public function getCodigoSeleccionTipoFk()
    {
        return $this->codigoSeleccionTipoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuSeleccion
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
     * Set codigoTipoIdentificacionFk
     *
     * @param integer $codigoTipoIdentificacionFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoTipoIdentificacionFk($codigoTipoIdentificacionFk)
    {
        $this->codigoTipoIdentificacionFk = $codigoTipoIdentificacionFk;

        return $this;
    }

    /**
     * Get codigoTipoIdentificacionFk
     *
     * @return integer
     */
    public function getCodigoTipoIdentificacionFk()
    {
        return $this->codigoTipoIdentificacionFk;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuSeleccion
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuSeleccion
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set nombre1
     *
     * @param string $nombre1
     *
     * @return RhuSeleccion
     */
    public function setNombre1($nombre1)
    {
        $this->nombre1 = $nombre1;

        return $this;
    }

    /**
     * Get nombre1
     *
     * @return string
     */
    public function getNombre1()
    {
        return $this->nombre1;
    }

    /**
     * Set nombre2
     *
     * @param string $nombre2
     *
     * @return RhuSeleccion
     */
    public function setNombre2($nombre2)
    {
        $this->nombre2 = $nombre2;

        return $this;
    }

    /**
     * Get nombre2
     *
     * @return string
     */
    public function getNombre2()
    {
        return $this->nombre2;
    }

    /**
     * Set apellido1
     *
     * @param string $apellido1
     *
     * @return RhuSeleccion
     */
    public function setApellido1($apellido1)
    {
        $this->apellido1 = $apellido1;

        return $this;
    }

    /**
     * Get apellido1
     *
     * @return string
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * Set apellido2
     *
     * @param string $apellido2
     *
     * @return RhuSeleccion
     */
    public function setApellido2($apellido2)
    {
        $this->apellido2 = $apellido2;

        return $this;
    }

    /**
     * Get apellido2
     *
     * @return string
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return RhuSeleccion
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set celular
     *
     * @param string $celular
     *
     * @return RhuSeleccion
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Get celular
     *
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return RhuSeleccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return RhuSeleccion
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
     * Set barrio
     *
     * @param string $barrio
     *
     * @return RhuSeleccion
     */
    public function setBarrio($barrio)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return string
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Set codigoRhFk
     *
     * @param integer $codigoRhFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoRhFk($codigoRhFk)
    {
        $this->codigoRhFk = $codigoRhFk;

        return $this;
    }

    /**
     * Get codigoRhFk
     *
     * @return integer
     */
    public function getCodigoRhFk()
    {
        return $this->codigoRhFk;
    }

    /**
     * Set codigoEstadoCivilFk
     *
     * @param string $codigoEstadoCivilFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoEstadoCivilFk($codigoEstadoCivilFk)
    {
        $this->codigoEstadoCivilFk = $codigoEstadoCivilFk;

        return $this;
    }

    /**
     * Get codigoEstadoCivilFk
     *
     * @return string
     */
    public function getCodigoEstadoCivilFk()
    {
        return $this->codigoEstadoCivilFk;
    }

    /**
     * Set codigoSexoFk
     *
     * @param string $codigoSexoFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoSexoFk($codigoSexoFk)
    {
        $this->codigoSexoFk = $codigoSexoFk;

        return $this;
    }

    /**
     * Get codigoSexoFk
     *
     * @return string
     */
    public function getCodigoSexoFk()
    {
        return $this->codigoSexoFk;
    }

    /**
     * Set correo
     *
     * @param string $correo
     *
     * @return RhuSeleccion
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return RhuSeleccion
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set codigoCiudadNacimientoFk
     *
     * @param integer $codigoCiudadNacimientoFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoCiudadNacimientoFk($codigoCiudadNacimientoFk)
    {
        $this->codigoCiudadNacimientoFk = $codigoCiudadNacimientoFk;

        return $this;
    }

    /**
     * Get codigoCiudadNacimientoFk
     *
     * @return integer
     */
    public function getCodigoCiudadNacimientoFk()
    {
        return $this->codigoCiudadNacimientoFk;
    }

    /**
     * Set codigoCiudadExpedicionFk
     *
     * @param integer $codigoCiudadExpedicionFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoCiudadExpedicionFk($codigoCiudadExpedicionFk)
    {
        $this->codigoCiudadExpedicionFk = $codigoCiudadExpedicionFk;

        return $this;
    }

    /**
     * Get codigoCiudadExpedicionFk
     *
     * @return integer
     */
    public function getCodigoCiudadExpedicionFk()
    {
        return $this->codigoCiudadExpedicionFk;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuSeleccion
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
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoCargoFk($codigoCargoFk)
    {
        $this->codigoCargoFk = $codigoCargoFk;

        return $this;
    }

    /**
     * Get codigoCargoFk
     *
     * @return integer
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuSeleccion
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
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return RhuSeleccion
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set presentaPruebas
     *
     * @param boolean $presentaPruebas
     *
     * @return RhuSeleccion
     */
    public function setPresentaPruebas($presentaPruebas)
    {
        $this->presentaPruebas = $presentaPruebas;

        return $this;
    }

    /**
     * Get presentaPruebas
     *
     * @return boolean
     */
    public function getPresentaPruebas()
    {
        return $this->presentaPruebas;
    }

    /**
     * Set referenciasVerificadas
     *
     * @param boolean $referenciasVerificadas
     *
     * @return RhuSeleccion
     */
    public function setReferenciasVerificadas($referenciasVerificadas)
    {
        $this->referenciasVerificadas = $referenciasVerificadas;

        return $this;
    }

    /**
     * Get referenciasVerificadas
     *
     * @return boolean
     */
    public function getReferenciasVerificadas()
    {
        return $this->referenciasVerificadas;
    }

    /**
     * Set estadoCobrado
     *
     * @param boolean $estadoCobrado
     *
     * @return RhuSeleccion
     */
    public function setEstadoCobrado($estadoCobrado)
    {
        $this->estadoCobrado = $estadoCobrado;

        return $this;
    }

    /**
     * Get estadoCobrado
     *
     * @return boolean
     */
    public function getEstadoCobrado()
    {
        return $this->estadoCobrado;
    }

    /**
     * Set fechaEntrevista
     *
     * @param \DateTime $fechaEntrevista
     *
     * @return RhuSeleccion
     */
    public function setFechaEntrevista($fechaEntrevista)
    {
        $this->fecha_entrevista = $fechaEntrevista;

        return $this;
    }

    /**
     * Get fechaEntrevista
     *
     * @return \DateTime
     */
    public function getFechaEntrevista()
    {
        return $this->fecha_entrevista;
    }

    /**
     * Set fechaPruebas
     *
     * @param \DateTime $fechaPruebas
     *
     * @return RhuSeleccion
     */
    public function setFechaPruebas($fechaPruebas)
    {
        $this->fecha_pruebas = $fechaPruebas;

        return $this;
    }

    /**
     * Get fechaPruebas
     *
     * @return \DateTime
     */
    public function getFechaPruebas()
    {
        return $this->fecha_pruebas;
    }

    /**
     * Set vrServicio
     *
     * @param float $vrServicio
     *
     * @return RhuSeleccion
     */
    public function setVrServicio($vrServicio)
    {
        $this->vrServicio = $vrServicio;

        return $this;
    }

    /**
     * Get vrServicio
     *
     * @return float
     */
    public function getVrServicio()
    {
        return $this->vrServicio;
    }

    /**
     * Set codigoFacturaFk
     *
     * @param integer $codigoFacturaFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoFacturaFk($codigoFacturaFk)
    {
        $this->codigoFacturaFk = $codigoFacturaFk;

        return $this;
    }

    /**
     * Get codigoFacturaFk
     *
     * @return integer
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * Set fechaCierre
     *
     * @param \DateTime $fechaCierre
     *
     * @return RhuSeleccion
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;

        return $this;
    }

    /**
     * Get fechaCierre
     *
     * @return \DateTime
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuSeleccion
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
     * Set codigoZonaFk
     *
     * @param integer $codigoZonaFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoZonaFk($codigoZonaFk)
    {
        $this->codigoZonaFk = $codigoZonaFk;

        return $this;
    }

    /**
     * Get codigoZonaFk
     *
     * @return integer
     */
    public function getCodigoZonaFk()
    {
        return $this->codigoZonaFk;
    }

    /**
     * Set codigoMotivoCierreSeleccionFk
     *
     * @param integer $codigoMotivoCierreSeleccionFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoMotivoCierreSeleccionFk($codigoMotivoCierreSeleccionFk)
    {
        $this->codigoMotivoCierreSeleccionFk = $codigoMotivoCierreSeleccionFk;

        return $this;
    }

    /**
     * Get codigoMotivoCierreSeleccionFk
     *
     * @return integer
     */
    public function getCodigoMotivoCierreSeleccionFk()
    {
        return $this->codigoMotivoCierreSeleccionFk;
    }

    /**
     * Set seleccionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionTipo $seleccionTipoRel
     *
     * @return RhuSeleccion
     */
    public function setSeleccionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionTipo $seleccionTipoRel = null)
    {
        $this->seleccionTipoRel = $seleccionTipoRel;

        return $this;
    }

    /**
     * Get seleccionTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionTipo
     */
    public function getSeleccionTipoRel()
    {
        return $this->seleccionTipoRel;
    }

    /**
     * Set tipoIdentificacionRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTipoIdentificacion $tipoIdentificacionRel
     *
     * @return RhuSeleccion
     */
    public function setTipoIdentificacionRel(\Brasa\GeneralBundle\Entity\GenTipoIdentificacion $tipoIdentificacionRel = null)
    {
        $this->tipoIdentificacionRel = $tipoIdentificacionRel;

        return $this;
    }

    /**
     * Get tipoIdentificacionRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenTipoIdentificacion
     */
    public function getTipoIdentificacionRel()
    {
        return $this->tipoIdentificacionRel;
    }

    /**
     * Set estadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil $estadoCivilRel
     *
     * @return RhuSeleccion
     */
    public function setEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil $estadoCivilRel = null)
    {
        $this->estadoCivilRel = $estadoCivilRel;

        return $this;
    }

    /**
     * Get estadoCivilRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil
     */
    public function getEstadoCivilRel()
    {
        return $this->estadoCivilRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuSeleccion
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
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuSeleccion
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
     * Set ciudadNacimientoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadNacimientoRel
     *
     * @return RhuSeleccion
     */
    public function setCiudadNacimientoRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadNacimientoRel = null)
    {
        $this->ciudadNacimientoRel = $ciudadNacimientoRel;

        return $this;
    }

    /**
     * Get ciudadNacimientoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadNacimientoRel()
    {
        return $this->ciudadNacimientoRel;
    }

    /**
     * Set ciudadExpedicionRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadExpedicionRel
     *
     * @return RhuSeleccion
     */
    public function setCiudadExpedicionRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadExpedicionRel = null)
    {
        $this->ciudadExpedicionRel = $ciudadExpedicionRel;

        return $this;
    }

    /**
     * Get ciudadExpedicionRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadExpedicionRel()
    {
        return $this->ciudadExpedicionRel;
    }

    /**
     * Set rhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRh $rhRel
     *
     * @return RhuSeleccion
     */
    public function setRhRel(\Brasa\RecursoHumanoBundle\Entity\RhuRh $rhRel = null)
    {
        $this->rhRel = $rhRel;

        return $this;
    }

    /**
     * Get rhRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuRh
     */
    public function getRhRel()
    {
        return $this->rhRel;
    }

    /**
     * Set seleccionRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionRequisitoRel
     *
     * @return RhuSeleccion
     */
    public function setSeleccionRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionRequisitoRel = null)
    {
        $this->seleccionRequisitoRel = $seleccionRequisitoRel;

        return $this;
    }

    /**
     * Get seleccionRequisitoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito
     */
    public function getSeleccionRequisitoRel()
    {
        return $this->seleccionRequisitoRel;
    }

    /**
     * Set facturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturaRel
     *
     * @return RhuSeleccion
     */
    public function setFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturaRel = null)
    {
        $this->facturaRel = $facturaRel;

        return $this;
    }

    /**
     * Get facturaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuFactura
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuSeleccion
     */
    public function setCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel = null)
    {
        $this->cargoRel = $cargoRel;

        return $this;
    }

    /**
     * Get cargoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCargo
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
    }

    /**
     * Set zonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuZona $zonaRel
     *
     * @return RhuSeleccion
     */
    public function setZonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuZona $zonaRel = null)
    {
        $this->zonaRel = $zonaRel;

        return $this;
    }

    /**
     * Get zonaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuZona
     */
    public function getZonaRel()
    {
        return $this->zonaRel;
    }

    /**
     * Set motivoCierreSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuMotivoCierreSeleccion $motivoCierreSeleccionRel
     *
     * @return RhuSeleccion
     */
    public function setMotivoCierreSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuMotivoCierreSeleccion $motivoCierreSeleccionRel = null)
    {
        $this->motivoCierreSeleccionRel = $motivoCierreSeleccionRel;

        return $this;
    }

    /**
     * Get motivoCierreSeleccionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuMotivoCierreSeleccion
     */
    public function getMotivoCierreSeleccionRel()
    {
        return $this->motivoCierreSeleccionRel;
    }

    /**
     * Add seleccionesReferenciasSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia $seleccionesReferenciasSeleccionRel
     *
     * @return RhuSeleccion
     */
    public function addSeleccionesReferenciasSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia $seleccionesReferenciasSeleccionRel)
    {
        $this->seleccionesReferenciasSeleccionRel[] = $seleccionesReferenciasSeleccionRel;

        return $this;
    }

    /**
     * Remove seleccionesReferenciasSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia $seleccionesReferenciasSeleccionRel
     */
    public function removeSeleccionesReferenciasSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia $seleccionesReferenciasSeleccionRel)
    {
        $this->seleccionesReferenciasSeleccionRel->removeElement($seleccionesReferenciasSeleccionRel);
    }

    /**
     * Get seleccionesReferenciasSeleccionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesReferenciasSeleccionRel()
    {
        return $this->seleccionesReferenciasSeleccionRel;
    }

    /**
     * Add seleccionesPruebasSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba $seleccionesPruebasSeleccionRel
     *
     * @return RhuSeleccion
     */
    public function addSeleccionesPruebasSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba $seleccionesPruebasSeleccionRel)
    {
        $this->seleccionesPruebasSeleccionRel[] = $seleccionesPruebasSeleccionRel;

        return $this;
    }

    /**
     * Remove seleccionesPruebasSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba $seleccionesPruebasSeleccionRel
     */
    public function removeSeleccionesPruebasSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba $seleccionesPruebasSeleccionRel)
    {
        $this->seleccionesPruebasSeleccionRel->removeElement($seleccionesPruebasSeleccionRel);
    }

    /**
     * Get seleccionesPruebasSeleccionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesPruebasSeleccionRel()
    {
        return $this->seleccionesPruebasSeleccionRel;
    }

    /**
     * Add seleccionesVisitasSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionVisita $seleccionesVisitasSeleccionRel
     *
     * @return RhuSeleccion
     */
    public function addSeleccionesVisitasSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionVisita $seleccionesVisitasSeleccionRel)
    {
        $this->seleccionesVisitasSeleccionRel[] = $seleccionesVisitasSeleccionRel;

        return $this;
    }

    /**
     * Remove seleccionesVisitasSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionVisita $seleccionesVisitasSeleccionRel
     */
    public function removeSeleccionesVisitasSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionVisita $seleccionesVisitasSeleccionRel)
    {
        $this->seleccionesVisitasSeleccionRel->removeElement($seleccionesVisitasSeleccionRel);
    }

    /**
     * Get seleccionesVisitasSeleccionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesVisitasSeleccionRel()
    {
        return $this->seleccionesVisitasSeleccionRel;
    }

    /**
     * Add seleccionesEntrevistasSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevista $seleccionesEntrevistasSeleccionRel
     *
     * @return RhuSeleccion
     */
    public function addSeleccionesEntrevistasSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevista $seleccionesEntrevistasSeleccionRel)
    {
        $this->seleccionesEntrevistasSeleccionRel[] = $seleccionesEntrevistasSeleccionRel;

        return $this;
    }

    /**
     * Remove seleccionesEntrevistasSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevista $seleccionesEntrevistasSeleccionRel
     */
    public function removeSeleccionesEntrevistasSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevista $seleccionesEntrevistasSeleccionRel)
    {
        $this->seleccionesEntrevistasSeleccionRel->removeElement($seleccionesEntrevistasSeleccionRel);
    }

    /**
     * Get seleccionesEntrevistasSeleccionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesEntrevistasSeleccionRel()
    {
        return $this->seleccionesEntrevistasSeleccionRel;
    }
}
