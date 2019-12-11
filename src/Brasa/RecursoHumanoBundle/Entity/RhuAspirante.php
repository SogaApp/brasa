<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Table(name="rhu_aspirante")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuAspiranteRepository")
 * @DoctrineAssert\UniqueEntity(fields={"numeroIdentificacion"},message="Ya existe este número de identificación") 
 */
class RhuAspirante
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aspirante_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAspirantePk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_tipo_identificacion_fk", type="integer")
     */
    private $codigoTipoIdentificacionFk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false, unique=true)
     */
    private $numeroIdentificacion;
    
    /**
     * @ORM\Column(name="libreta_militar", type="string", length=20, nullable=true)
     */
         
    private $libretaMilitar;

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
     * @ORM\Column(name="direccion", type="string", length=60, nullable=true)
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
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */
    private $estadoCerrado = false;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = false;
    
    /**
     * @ORM\Column(name="codigo_disponibilidad_fk", type="string", length=30, nullable=true)
     */
    private $codigoDisponibilidadFk;
    
    /**
     * @ORM\Column(name="bloqueado", type="boolean")
     */
    private $bloqueado = false;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\Column(name="peso", type="string", length=20, nullable=true)
     */
    private $peso;
    
    /**
     * @ORM\Column(name="estatura", type="string", length=20, nullable=true)
     */
    private $estatura;
    
    /**
     * @ORM\Column(name="codigo_tipo_libreta", type="integer", nullable=true)
     */    
    private $codigoTipoLibreta;
    
    /**
     * @ORM\Column(name="cargo_aspira", type="string", length=50, nullable=true)
     */
    private $cargoAspira;
    
    /**
     * @ORM\Column(name="recomendado", type="string", length=80, nullable=true)
     */
    private $recomendado;
    
    /**
     * @ORM\Column(name="operacion", type="string", length=50, nullable=true)
     */
    private $operacion;
    
    /**
     * @ORM\Column(name="reintegro", type="boolean")
     */
    private $reintegro = false;

    /**
     * @ORM\Column(name="codigo_zona_fk", type="integer", nullable=true)
     */    
    private $codigoZonaFk;     
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTipoIdentificacion", inversedBy="rhuAspirantesTipoIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_tipo_identificacion_fk", referencedColumnName="codigo_tipo_identificacion_pk")
     */
    protected $tipoIdentificacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEstadoCivil", inversedBy="aspirantesEstadoCivilRel")
     * @ORM\JoinColumn(name="codigo_estado_civil_fk", referencedColumnName="codigo_estado_civil_pk")
     */
    protected $estadoCivilRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuAspirantesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuAspirantesCiudadNacimientoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_nacimiento_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadNacimientoRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuAspirantesCiudadExpedicionRel")
     * @ORM\JoinColumn(name="codigo_ciudad_expedicion_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadExpedicionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuRh", inversedBy="aspirantesRhRel")
     * @ORM\JoinColumn(name="codigo_rh_fk", referencedColumnName="codigo_rh_pk")
     */
    protected $rhRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuZona", inversedBy="aspirantesZonaRel")
     * @ORM\JoinColumn(name="codigo_zona_fk", referencedColumnName="codigo_zona_pk")
     */
    protected $zonaRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionRequisicionAspirante", mappedBy="aspiranteRel")
     */
    protected $seleccionesRequisicionesAspirantesAspiranteRel;
    
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionesRequisicionesAspirantesAspiranteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoAspirantePk
     *
     * @return integer
     */
    public function getCodigoAspirantePk()
    {
        return $this->codigoAspirantePk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * Set libretaMilitar
     *
     * @param string $libretaMilitar
     *
     * @return RhuAspirante
     */
    public function setLibretaMilitar($libretaMilitar)
    {
        $this->libretaMilitar = $libretaMilitar;

        return $this;
    }

    /**
     * Get libretaMilitar
     *
     * @return string
     */
    public function getLibretaMilitar()
    {
        return $this->libretaMilitar;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * Set codigoDisponibilidadFk
     *
     * @param string $codigoDisponibilidadFk
     *
     * @return RhuAspirante
     */
    public function setCodigoDisponibilidadFk($codigoDisponibilidadFk)
    {
        $this->codigoDisponibilidadFk = $codigoDisponibilidadFk;

        return $this;
    }

    /**
     * Get codigoDisponibilidadFk
     *
     * @return string
     */
    public function getCodigoDisponibilidadFk()
    {
        return $this->codigoDisponibilidadFk;
    }

    /**
     * Set bloqueado
     *
     * @param boolean $bloqueado
     *
     * @return RhuAspirante
     */
    public function setBloqueado($bloqueado)
    {
        $this->bloqueado = $bloqueado;

        return $this;
    }

    /**
     * Get bloqueado
     *
     * @return boolean
     */
    public function getBloqueado()
    {
        return $this->bloqueado;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuAspirante
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
     * Set peso
     *
     * @param string $peso
     *
     * @return RhuAspirante
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get peso
     *
     * @return string
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set estatura
     *
     * @param string $estatura
     *
     * @return RhuAspirante
     */
    public function setEstatura($estatura)
    {
        $this->estatura = $estatura;

        return $this;
    }

    /**
     * Get estatura
     *
     * @return string
     */
    public function getEstatura()
    {
        return $this->estatura;
    }

    /**
     * Set codigoTipoLibreta
     *
     * @param integer $codigoTipoLibreta
     *
     * @return RhuAspirante
     */
    public function setCodigoTipoLibreta($codigoTipoLibreta)
    {
        $this->codigoTipoLibreta = $codigoTipoLibreta;

        return $this;
    }

    /**
     * Get codigoTipoLibreta
     *
     * @return integer
     */
    public function getCodigoTipoLibreta()
    {
        return $this->codigoTipoLibreta;
    }

    /**
     * Set cargoAspira
     *
     * @param string $cargoAspira
     *
     * @return RhuAspirante
     */
    public function setCargoAspira($cargoAspira)
    {
        $this->cargoAspira = $cargoAspira;

        return $this;
    }

    /**
     * Get cargoAspira
     *
     * @return string
     */
    public function getCargoAspira()
    {
        return $this->cargoAspira;
    }

    /**
     * Set recomendado
     *
     * @param string $recomendado
     *
     * @return RhuAspirante
     */
    public function setRecomendado($recomendado)
    {
        $this->recomendado = $recomendado;

        return $this;
    }

    /**
     * Get recomendado
     *
     * @return string
     */
    public function getRecomendado()
    {
        return $this->recomendado;
    }

    /**
     * Set operacion
     *
     * @param string $operacion
     *
     * @return RhuAspirante
     */
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get operacion
     *
     * @return string
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * Set reintegro
     *
     * @param boolean $reintegro
     *
     * @return RhuAspirante
     */
    public function setReintegro($reintegro)
    {
        $this->reintegro = $reintegro;

        return $this;
    }

    /**
     * Get reintegro
     *
     * @return boolean
     */
    public function getReintegro()
    {
        return $this->reintegro;
    }

    /**
     * Set codigoZonaFk
     *
     * @param integer $codigoZonaFk
     *
     * @return RhuAspirante
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
     * Set tipoIdentificacionRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTipoIdentificacion $tipoIdentificacionRel
     *
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * Set zonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuZona $zonaRel
     *
     * @return RhuAspirante
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
     * Add seleccionesRequisicionesAspirantesAspiranteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante $seleccionesRequisicionesAspirantesAspiranteRel
     *
     * @return RhuAspirante
     */
    public function addSeleccionesRequisicionesAspirantesAspiranteRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante $seleccionesRequisicionesAspirantesAspiranteRel)
    {
        $this->seleccionesRequisicionesAspirantesAspiranteRel[] = $seleccionesRequisicionesAspirantesAspiranteRel;

        return $this;
    }

    /**
     * Remove seleccionesRequisicionesAspirantesAspiranteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante $seleccionesRequisicionesAspirantesAspiranteRel
     */
    public function removeSeleccionesRequisicionesAspirantesAspiranteRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante $seleccionesRequisicionesAspirantesAspiranteRel)
    {
        $this->seleccionesRequisicionesAspirantesAspiranteRel->removeElement($seleccionesRequisicionesAspirantesAspiranteRel);
    }

    /**
     * Get seleccionesRequisicionesAspirantesAspiranteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesRequisicionesAspirantesAspiranteRel()
    {
        return $this->seleccionesRequisicionesAspirantesAspiranteRel;
    }
}
