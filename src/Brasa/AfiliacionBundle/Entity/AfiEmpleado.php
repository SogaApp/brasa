<?php

namespace Brasa\AfiliacionBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_empleado")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiEmpleadoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"numeroIdentificacion"},message="Ya existe este número de identificación")
 * @ORM\EntityListeners({"Brasa\GeneralBundle\MisClases\EntityListener"})
 */
class AfiEmpleado
{
    public $infoLog = [
        "primaryKey" => "codigoEmpleadoPk",
        "camposSeguimiento" => [
            "codigoTipoIdentificacionFk" => ["tipoIdentificacionRel", "codigoTipoIdentificacionPk", "nombre"],
            "numeroIdentificacion",
            "nombreCorto",
            "codigoCiudadFk" => ["ciudadRel","codigoCiudadPk","nombre"],
            "codigoEstadoCivilFk" => ["estadoCivilRel","codigoEstadoCivilPk","nombre"],
            "VrSalario"
        ],
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoPk;

    /**
     * @ORM\Column(name="codigo_tipo_identificacion_fk", type="integer")
     */
    private $codigoTipoIdentificacionFk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false, unique=true)
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
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
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
    private $codigoRhPk;

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
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=1, nullable=true)
     */

    private $codigoEstadoCivilFk;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;

    /**
     * @ORM\Column(name="estado_activo", type="boolean")
     */
    private $estadoActivo = 1;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="codigo_contrato_activo", type="integer", nullable=true)
     */
    private $codigoContratoActivo;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTipoIdentificacion", inversedBy="afiEmpleadosTipoIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_tipo_identificacion_fk", referencedColumnName="codigo_tipo_identificacion_pk")
     */
    protected $tipoIdentificacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil", inversedBy="afiEmpleadosEstadoCivilRel")
     * @ORM\JoinColumn(name="codigo_estado_civil_fk", referencedColumnName="codigo_estado_civil_pk")
     */
    protected $estadoCivilRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="afiEmpleadosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuRh", inversedBy="afiEmpleadosRhRel")
     * @ORM\JoinColumn(name="codigo_rh_fk", referencedColumnName="codigo_rh_pk")
     */
    protected $rhRel;

    /**
     * @ORM\ManyToOne(targetEntity="AfiCliente", inversedBy="empleadosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;


    /**
     * @ORM\OneToMany(targetEntity="AfiContrato", mappedBy="empleadoRel")
     */
    protected $contratosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiPeriodoDetalle", mappedBy="empleadoRel")
     */
    protected $periodosDetallesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiPeriodoDetallePago", mappedBy="empleadoRel")
     */
    protected $periodosDetallesPagosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiPeriodoDetallePagoDetalle", mappedBy="empleadoRel")
     */
    protected $periodosDetallesPagosDetallesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiCurso", mappedBy="empleadoRel")
     */
    protected $cursosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiNovedad", mappedBy="empleadoRel")
     */
    protected $novedadesEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiCambioSalario", mappedBy="empleadoRel")
     */
    protected $cambiosSalariosEmpleadoRel;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->periodosDetallesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->periodosDetallesPagosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->periodosDetallesPagosDetallesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cursosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->novedadesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cambiosSalariosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEmpleadoPk
     *
     * @return integer
     */
    public function getCodigoEmpleadoPk()
    {
        return $this->codigoEmpleadoPk;
    }

    /**
     * Set codigoTipoIdentificacionFk
     *
     * @param integer $codigoTipoIdentificacionFk
     *
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * Set codigoRhPk
     *
     * @param integer $codigoRhPk
     *
     * @return AfiEmpleado
     */
    public function setCodigoRhPk($codigoRhPk)
    {
        $this->codigoRhPk = $codigoRhPk;

        return $this;
    }

    /**
     * Get codigoRhPk
     *
     * @return integer
     */
    public function getCodigoRhPk()
    {
        return $this->codigoRhPk;
    }

    /**
     * Set codigoSexoFk
     *
     * @param string $codigoSexoFk
     *
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * Set codigoEstadoCivilFk
     *
     * @param string $codigoEstadoCivilFk
     *
     * @return AfiEmpleado
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
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return AfiEmpleado
     */
    public function setVrSalario($vrSalario)
    {
        $this->VrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->VrSalario;
    }

    /**
     * Set estadoActivo
     *
     * @param boolean $estadoActivo
     *
     * @return AfiEmpleado
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return AfiEmpleado
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
     * Set codigoContratoActivo
     *
     * @param integer $codigoContratoActivo
     *
     * @return AfiEmpleado
     */
    public function setCodigoContratoActivo($codigoContratoActivo)
    {
        $this->codigoContratoActivo = $codigoContratoActivo;

        return $this;
    }

    /**
     * Get codigoContratoActivo
     *
     * @return integer
     */
    public function getCodigoContratoActivo()
    {
        return $this->codigoContratoActivo;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return AfiEmpleado
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
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return AfiEmpleado
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set tipoIdentificacionRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTipoIdentificacion $tipoIdentificacionRel
     *
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * @return AfiEmpleado
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
     * Set rhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRh $rhRel
     *
     * @return AfiEmpleado
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
     * Set clienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $clienteRel
     *
     * @return AfiEmpleado
     */
    public function setClienteRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Add contratosEmpleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $contratosEmpleadoRel
     *
     * @return AfiEmpleado
     */
    public function addContratosEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $contratosEmpleadoRel)
    {
        $this->contratosEmpleadoRel[] = $contratosEmpleadoRel;

        return $this;
    }

    /**
     * Remove contratosEmpleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $contratosEmpleadoRel
     */
    public function removeContratosEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $contratosEmpleadoRel)
    {
        $this->contratosEmpleadoRel->removeElement($contratosEmpleadoRel);
    }

    /**
     * Get contratosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosEmpleadoRel()
    {
        return $this->contratosEmpleadoRel;
    }

    /**
     * Add periodosDetallesEmpleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle $periodosDetallesEmpleadoRel
     *
     * @return AfiEmpleado
     */
    public function addPeriodosDetallesEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle $periodosDetallesEmpleadoRel)
    {
        $this->periodosDetallesEmpleadoRel[] = $periodosDetallesEmpleadoRel;

        return $this;
    }

    /**
     * Remove periodosDetallesEmpleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle $periodosDetallesEmpleadoRel
     */
    public function removePeriodosDetallesEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle $periodosDetallesEmpleadoRel)
    {
        $this->periodosDetallesEmpleadoRel->removeElement($periodosDetallesEmpleadoRel);
    }

    /**
     * Get periodosDetallesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodosDetallesEmpleadoRel()
    {
        return $this->periodosDetallesEmpleadoRel;
    }

    /**
     * Add periodosDetallesPagosEmpleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosEmpleadoRel
     *
     * @return AfiEmpleado
     */
    public function addPeriodosDetallesPagosEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosEmpleadoRel)
    {
        $this->periodosDetallesPagosEmpleadoRel[] = $periodosDetallesPagosEmpleadoRel;

        return $this;
    }

    /**
     * Remove periodosDetallesPagosEmpleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosEmpleadoRel
     */
    public function removePeriodosDetallesPagosEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosEmpleadoRel)
    {
        $this->periodosDetallesPagosEmpleadoRel->removeElement($periodosDetallesPagosEmpleadoRel);
    }

    /**
     * Get periodosDetallesPagosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodosDetallesPagosEmpleadoRel()
    {
        return $this->periodosDetallesPagosEmpleadoRel;
    }

    /**
     * Add periodosDetallesPagosDetallesEmpleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesEmpleadoRel
     *
     * @return AfiEmpleado
     */
    public function addPeriodosDetallesPagosDetallesEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesEmpleadoRel)
    {
        $this->periodosDetallesPagosDetallesEmpleadoRel[] = $periodosDetallesPagosDetallesEmpleadoRel;

        return $this;
    }

    /**
     * Remove periodosDetallesPagosDetallesEmpleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesEmpleadoRel
     */
    public function removePeriodosDetallesPagosDetallesEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesEmpleadoRel)
    {
        $this->periodosDetallesPagosDetallesEmpleadoRel->removeElement($periodosDetallesPagosDetallesEmpleadoRel);
    }

    /**
     * Get periodosDetallesPagosDetallesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodosDetallesPagosDetallesEmpleadoRel()
    {
        return $this->periodosDetallesPagosDetallesEmpleadoRel;
    }

    /**
     * Add cursosEmpleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCurso $cursosEmpleadoRel
     *
     * @return AfiEmpleado
     */
    public function addCursosEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiCurso $cursosEmpleadoRel)
    {
        $this->cursosEmpleadoRel[] = $cursosEmpleadoRel;

        return $this;
    }

    /**
     * Remove cursosEmpleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCurso $cursosEmpleadoRel
     */
    public function removeCursosEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiCurso $cursosEmpleadoRel)
    {
        $this->cursosEmpleadoRel->removeElement($cursosEmpleadoRel);
    }

    /**
     * Get cursosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCursosEmpleadoRel()
    {
        return $this->cursosEmpleadoRel;
    }

    /**
     * Add novedadesEmpleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiNovedad $novedadesEmpleadoRel
     *
     * @return AfiEmpleado
     */
    public function addNovedadesEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiNovedad $novedadesEmpleadoRel)
    {
        $this->novedadesEmpleadoRel[] = $novedadesEmpleadoRel;

        return $this;
    }

    /**
     * Remove novedadesEmpleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiNovedad $novedadesEmpleadoRel
     */
    public function removeNovedadesEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiNovedad $novedadesEmpleadoRel)
    {
        $this->novedadesEmpleadoRel->removeElement($novedadesEmpleadoRel);
    }

    /**
     * Get novedadesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNovedadesEmpleadoRel()
    {
        return $this->novedadesEmpleadoRel;
    }

    /**
     * Add cambiosSalariosEmpleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCambioSalario $cambiosSalariosEmpleadoRel
     *
     * @return AfiEmpleado
     */
    public function addCambiosSalariosEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiCambioSalario $cambiosSalariosEmpleadoRel)
    {
        $this->cambiosSalariosEmpleadoRel[] = $cambiosSalariosEmpleadoRel;

        return $this;
    }

    /**
     * Remove cambiosSalariosEmpleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCambioSalario $cambiosSalariosEmpleadoRel
     */
    public function removeCambiosSalariosEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiCambioSalario $cambiosSalariosEmpleadoRel)
    {
        $this->cambiosSalariosEmpleadoRel->removeElement($cambiosSalariosEmpleadoRel);
    }

    /**
     * Get cambiosSalariosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCambiosSalariosEmpleadoRel()
    {
        return $this->cambiosSalariosEmpleadoRel;
    }
}
