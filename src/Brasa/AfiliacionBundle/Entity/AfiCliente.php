<?php

namespace Brasa\AfiliacionBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_cliente")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiClienteRepository")
 * @DoctrineAssert\UniqueEntity(fields={"nit"},message="Ya existe este nit")
 * @ORM\EntityListeners({"Brasa\GeneralBundle\MisClases\EntityListener"})
 */
class AfiCliente
{
    public $infoLog = [
        "primaryKey" => "codigoClientePk",
        "camposSeguimiento" => [
            "nit",
            "digitoVerificacion",
            "tipoIdentificacion",
            "nombreCorto",
            "codigoAsesorFk" => ["asesorRel", "codigoAsesorPk", "nombre"],
            "codigoFormaPagoFk" => ["formaPagoRel","codigoFormaPagoPk","nombre"],
            "plazoPago",
            "direccion",
            "barrio",
            "codigoCiudadFk" => ["ciudadRel","codigoCiudadPk","nombre"],
            "codigoRazonSocialFk" => ["razonSocialRel","codigoRazonSocialPk","nombre"],
            "codigoEntidadRiesgoFk" => ["entidadRiesgoRel","codigoEntidadRiesgoPk","nombre"]
        ],
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cliente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoClientePk;

    /**
     * @ORM\Column(name="nit", type="string", length=15, nullable=false, unique=true)
     */
    private $nit;

    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=1, nullable=true)
     */
    private $digitoVerificacion;

    /**
     * @ORM\Column(name="tipo_identificacion", type="string", length=2, nullable=true)
     */
    private $tipoIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=100)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */
    private $codigoAsesorFk;

    /**
     * @ORM\Column(name="codigo_forma_pago_fk", type="integer", nullable=true)
     */
    private $codigoFormaPagoFk;

    /**
     * @ORM\Column(name="plazo_pago", type="integer")
     */
    private $plazoPago = 0;

    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="barrio", type="string", length=120, nullable=true)
     */
    private $barrio;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="codigo_razon_social_fk" , type="integer" , nullable=true)
     */
    private $codigoRazonSocialFk;

    /**
     * @ORM\Column(name="codigo_entidad_riesgo_fk" ,type="integer" ,nullable=true)
     */
    private $codigoEntidadRiesgoFk;

    /**
     * @ORM\Column(name="telefono", type="string", length=40, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="celular", type="string", length=40, nullable=true, nullable=true)
     */
    private $celular;

    /**
     * @ORM\Column(name="fax", type="string", length=20, nullable=true, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(name="contacto", type="string", length=80, nullable=true)
     */
    private $contacto;

    /**
     * @ORM\Column(name="calular_contacto", type="string", length=20, nullable=true)
     */
    private $celularContacto;

    /**
     * @ORM\Column(name="telefono_contacto", type="string", length=20, nullable=true)
     */
    private $telefonoContacto;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="afiliacion", type="float")
     */
    private $afiliacion = 0;

    /**
     * @ORM\Column(name="administracion", type="float")
     */
    private $administracion = 0;

    /**
     * @ORM\Column(name="genera_salud", type="boolean")
     */
    private $genera_salud = false;

    /**
     * @ORM\Column(name="porcentaje_salud", type="float")
     */
    private $porcentajeSalud = 0;

    /**
     * @ORM\Column(name="genera_pension", type="boolean")
     */
    private $genera_pension = false;

    /**
     * @ORM\Column(name="porcentaje_pension", type="float")
     */
    private $porcentajePension = 0;

    /**
     * @ORM\Column(name="genera_caja", type="boolean")
     */
    private $genera_caja = false;

    /**
     * @ORM\Column(name="porcentaje_caja", type="float")
     */
    private $porcentajeCaja = 0;

    /**
     * @ORM\Column(name="genera_riesgos", type="boolean")
     */
    private $genera_riesgos = false;

    /**
     * @ORM\Column(name="genera_sena", type="boolean")
     */
    private $genera_sena = false;


    /**
     * @ORM\Column(name="genera_icbf", type="boolean")
     */
    private $genera_icbf = false;

    /**
     * @ORM\Column(name="codigo_sucursal", type="string", length=10, nullable=true)
     */
    private $codigoSucursal;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="redondear_cobro", type="boolean")
     */
    private $redondearCobro = true;

    /**
     * @ORM\Column(name="independiente", type="boolean")
     */
    private $independiente = false;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenAsesor", inversedBy="afiClientesAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")
     */
    protected $asesorRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenFormaPago", inversedBy="afiClientesFormaPagoRel")
     * @ORM\JoinColumn(name="codigo_forma_pago_fk", referencedColumnName="codigo_forma_pago_pk")
     */
    protected $formaPagoRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="afiClientesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\AfiliacionBundle\Entity\AfiRazonSocial", inversedBy="afiClientesRazonSocialRel")
     * @ORM\JoinColumn(name="codigo_razon_social_fk" , referencedColumnName="codigo_razon_social_pk")
     */
    private $razonSocialRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional" , inversedBy="afiClientesEntidadRiesgoRel")
     * @ORM\JoinColumn(name="codigo_entidad_riesgo_fk" ,referencedColumnName="codigo_entidad_riesgo_pk")
     */
    private $entidadRiesgoRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiEmpleado", mappedBy="clienteRel")
     */
    protected $empleadosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiContrato", mappedBy="clienteRel")
     */
    protected $contratosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiPeriodo", mappedBy="clienteRel")
     */
    protected $periodosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiFactura", mappedBy="clienteRel")
     */
    protected $facturasClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiCurso", mappedBy="clienteRel")
     */
    protected $cursosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiServicio", mappedBy="clienteRel")
     */
    protected $serviciosClienteRel;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->periodosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cursosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoClientePk
     *
     * @return integer
     */
    public function getCodigoClientePk()
    {
        return $this->codigoClientePk;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return AfiCliente
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set digitoVerificacion
     *
     * @param string $digitoVerificacion
     *
     * @return AfiCliente
     */
    public function setDigitoVerificacion($digitoVerificacion)
    {
        $this->digitoVerificacion = $digitoVerificacion;

        return $this;
    }

    /**
     * Get digitoVerificacion
     *
     * @return string
     */
    public function getDigitoVerificacion()
    {
        return $this->digitoVerificacion;
    }

    /**
     * Set tipoIdentificacion
     *
     * @param string $tipoIdentificacion
     *
     * @return AfiCliente
     */
    public function setTipoIdentificacion($tipoIdentificacion)
    {
        $this->tipoIdentificacion = $tipoIdentificacion;

        return $this;
    }

    /**
     * Get tipoIdentificacion
     *
     * @return string
     */
    public function getTipoIdentificacion()
    {
        return $this->tipoIdentificacion;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return AfiCliente
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
     * Set codigoAsesorFk
     *
     * @param integer $codigoAsesorFk
     *
     * @return AfiCliente
     */
    public function setCodigoAsesorFk($codigoAsesorFk)
    {
        $this->codigoAsesorFk = $codigoAsesorFk;

        return $this;
    }

    /**
     * Get codigoAsesorFk
     *
     * @return integer
     */
    public function getCodigoAsesorFk()
    {
        return $this->codigoAsesorFk;
    }

    /**
     * Set codigoFormaPagoFk
     *
     * @param integer $codigoFormaPagoFk
     *
     * @return AfiCliente
     */
    public function setCodigoFormaPagoFk($codigoFormaPagoFk)
    {
        $this->codigoFormaPagoFk = $codigoFormaPagoFk;

        return $this;
    }

    /**
     * Get codigoFormaPagoFk
     *
     * @return integer
     */
    public function getCodigoFormaPagoFk()
    {
        return $this->codigoFormaPagoFk;
    }

    /**
     * Set plazoPago
     *
     * @param integer $plazoPago
     *
     * @return AfiCliente
     */
    public function setPlazoPago($plazoPago)
    {
        $this->plazoPago = $plazoPago;

        return $this;
    }

    /**
     * Get plazoPago
     *
     * @return integer
     */
    public function getPlazoPago()
    {
        return $this->plazoPago;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return AfiCliente
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
     * Set barrio
     *
     * @param string $barrio
     *
     * @return AfiCliente
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
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return AfiCliente
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
     * Set telefono
     *
     * @param string $telefono
     *
     * @return AfiCliente
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
     * @return AfiCliente
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
     * Set fax
     *
     * @param string $fax
     *
     * @return AfiCliente
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return AfiCliente
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     *
     * @return AfiCliente
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return string
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set celularContacto
     *
     * @param string $celularContacto
     *
     * @return AfiCliente
     */
    public function setCelularContacto($celularContacto)
    {
        $this->celularContacto = $celularContacto;

        return $this;
    }

    /**
     * Get celularContacto
     *
     * @return string
     */
    public function getCelularContacto()
    {
        return $this->celularContacto;
    }

    /**
     * Set telefonoContacto
     *
     * @param string $telefonoContacto
     *
     * @return AfiCliente
     */
    public function setTelefonoContacto($telefonoContacto)
    {
        $this->telefonoContacto = $telefonoContacto;

        return $this;
    }

    /**
     * Get telefonoContacto
     *
     * @return string
     */
    public function getTelefonoContacto()
    {
        return $this->telefonoContacto;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return AfiCliente
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set afiliacion
     *
     * @param float $afiliacion
     *
     * @return AfiCliente
     */
    public function setAfiliacion($afiliacion)
    {
        $this->afiliacion = $afiliacion;

        return $this;
    }

    /**
     * Get afiliacion
     *
     * @return float
     */
    public function getAfiliacion()
    {
        return $this->afiliacion;
    }

    /**
     * Set administracion
     *
     * @param float $administracion
     *
     * @return AfiCliente
     */
    public function setAdministracion($administracion)
    {
        $this->administracion = $administracion;

        return $this;
    }

    /**
     * Get administracion
     *
     * @return float
     */
    public function getAdministracion()
    {
        return $this->administracion;
    }

    /**
     * Set generaSalud
     *
     * @param boolean $generaSalud
     *
     * @return AfiCliente
     */
    public function setGeneraSalud($generaSalud)
    {
        $this->genera_salud = $generaSalud;

        return $this;
    }

    /**
     * Get generaSalud
     *
     * @return boolean
     */
    public function getGeneraSalud()
    {
        return $this->genera_salud;
    }

    /**
     * Set porcentajeSalud
     *
     * @param float $porcentajeSalud
     *
     * @return AfiCliente
     */
    public function setPorcentajeSalud($porcentajeSalud)
    {
        $this->porcentajeSalud = $porcentajeSalud;

        return $this;
    }

    /**
     * Get porcentajeSalud
     *
     * @return float
     */
    public function getPorcentajeSalud()
    {
        return $this->porcentajeSalud;
    }

    /**
     * Set generaPension
     *
     * @param boolean $generaPension
     *
     * @return AfiCliente
     */
    public function setGeneraPension($generaPension)
    {
        $this->genera_pension = $generaPension;

        return $this;
    }

    /**
     * Get generaPension
     *
     * @return boolean
     */
    public function getGeneraPension()
    {
        return $this->genera_pension;
    }

    /**
     * Set porcentajePension
     *
     * @param float $porcentajePension
     *
     * @return AfiCliente
     */
    public function setPorcentajePension($porcentajePension)
    {
        $this->porcentajePension = $porcentajePension;

        return $this;
    }

    /**
     * Get porcentajePension
     *
     * @return float
     */
    public function getPorcentajePension()
    {
        return $this->porcentajePension;
    }

    /**
     * Set generaCaja
     *
     * @param boolean $generaCaja
     *
     * @return AfiCliente
     */
    public function setGeneraCaja($generaCaja)
    {
        $this->genera_caja = $generaCaja;

        return $this;
    }

    /**
     * Get generaCaja
     *
     * @return boolean
     */
    public function getGeneraCaja()
    {
        return $this->genera_caja;
    }

    /**
     * Set porcentajeCaja
     *
     * @param float $porcentajeCaja
     *
     * @return AfiCliente
     */
    public function setPorcentajeCaja($porcentajeCaja)
    {
        $this->porcentajeCaja = $porcentajeCaja;

        return $this;
    }

    /**
     * Get porcentajeCaja
     *
     * @return float
     */
    public function getPorcentajeCaja()
    {
        return $this->porcentajeCaja;
    }

    /**
     * Set generaRiesgos
     *
     * @param boolean $generaRiesgos
     *
     * @return AfiCliente
     */
    public function setGeneraRiesgos($generaRiesgos)
    {
        $this->genera_riesgos = $generaRiesgos;

        return $this;
    }

    /**
     * Get generaRiesgos
     *
     * @return boolean
     */
    public function getGeneraRiesgos()
    {
        return $this->genera_riesgos;
    }

    /**
     * Set generaSena
     *
     * @param boolean $generaSena
     *
     * @return AfiCliente
     */
    public function setGeneraSena($generaSena)
    {
        $this->genera_sena = $generaSena;

        return $this;
    }

    /**
     * Get generaSena
     *
     * @return boolean
     */
    public function getGeneraSena()
    {
        return $this->genera_sena;
    }

    /**
     * Set generaIcbf
     *
     * @param boolean $generaIcbf
     *
     * @return AfiCliente
     */
    public function setGeneraIcbf($generaIcbf)
    {
        $this->genera_icbf = $generaIcbf;

        return $this;
    }

    /**
     * Get generaIcbf
     *
     * @return boolean
     */
    public function getGeneraIcbf()
    {
        return $this->genera_icbf;
    }

    /**
     * Set codigoSucursal
     *
     * @param string $codigoSucursal
     *
     * @return AfiCliente
     */
    public function setCodigoSucursal($codigoSucursal)
    {
        $this->codigoSucursal = $codigoSucursal;

        return $this;
    }

    /**
     * Get codigoSucursal
     *
     * @return string
     */
    public function getCodigoSucursal()
    {
        return $this->codigoSucursal;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return AfiCliente
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
     * Set redondearCobro
     *
     * @param boolean $redondearCobro
     *
     * @return AfiCliente
     */
    public function setRedondearCobro($redondearCobro)
    {
        $this->redondearCobro = $redondearCobro;

        return $this;
    }

    /**
     * Get redondearCobro
     *
     * @return boolean
     */
    public function getRedondearCobro()
    {
        return $this->redondearCobro;
    }

    /**
     * Set independiente
     *
     * @param boolean $independiente
     *
     * @return AfiCliente
     */
    public function setIndependiente($independiente)
    {
        $this->independiente = $independiente;

        return $this;
    }

    /**
     * Get independiente
     *
     * @return boolean
     */
    public function getIndependiente()
    {
        return $this->independiente;
    }

    /**
     * Set asesorRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenAsesor $asesorRel
     *
     * @return AfiCliente
     */
    public function setAsesorRel(\Brasa\GeneralBundle\Entity\GenAsesor $asesorRel = null)
    {
        $this->asesorRel = $asesorRel;

        return $this;
    }

    /**
     * Get asesorRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenAsesor
     */
    public function getAsesorRel()
    {
        return $this->asesorRel;
    }

    /**
     * Set formaPagoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenFormaPago $formaPagoRel
     *
     * @return AfiCliente
     */
    public function setFormaPagoRel(\Brasa\GeneralBundle\Entity\GenFormaPago $formaPagoRel = null)
    {
        $this->formaPagoRel = $formaPagoRel;

        return $this;
    }

    /**
     * Get formaPagoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenFormaPago
     */
    public function getFormaPagoRel()
    {
        return $this->formaPagoRel;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return AfiCliente
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
     * Add empleadosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadosClienteRel
     *
     * @return AfiCliente
     */
    public function addEmpleadosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadosClienteRel)
    {
        $this->empleadosClienteRel[] = $empleadosClienteRel;

        return $this;
    }

    /**
     * Remove empleadosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadosClienteRel
     */
    public function removeEmpleadosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadosClienteRel)
    {
        $this->empleadosClienteRel->removeElement($empleadosClienteRel);
    }

    /**
     * Get empleadosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosClienteRel()
    {
        return $this->empleadosClienteRel;
    }

    /**
     * Add contratosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $contratosClienteRel
     *
     * @return AfiCliente
     */
    public function addContratosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $contratosClienteRel)
    {
        $this->contratosClienteRel[] = $contratosClienteRel;

        return $this;
    }

    /**
     * Remove contratosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $contratosClienteRel
     */
    public function removeContratosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $contratosClienteRel)
    {
        $this->contratosClienteRel->removeElement($contratosClienteRel);
    }

    /**
     * Get contratosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosClienteRel()
    {
        return $this->contratosClienteRel;
    }

    /**
     * Add periodosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodosClienteRel
     *
     * @return AfiCliente
     */
    public function addPeriodosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodosClienteRel)
    {
        $this->periodosClienteRel[] = $periodosClienteRel;

        return $this;
    }

    /**
     * Remove periodosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodosClienteRel
     */
    public function removePeriodosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodosClienteRel)
    {
        $this->periodosClienteRel->removeElement($periodosClienteRel);
    }

    /**
     * Get periodosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodosClienteRel()
    {
        return $this->periodosClienteRel;
    }

    /**
     * Add facturasClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFactura $facturasClienteRel
     *
     * @return AfiCliente
     */
    public function addFacturasClienteRel(\Brasa\AfiliacionBundle\Entity\AfiFactura $facturasClienteRel)
    {
        $this->facturasClienteRel[] = $facturasClienteRel;

        return $this;
    }

    /**
     * Remove facturasClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFactura $facturasClienteRel
     */
    public function removeFacturasClienteRel(\Brasa\AfiliacionBundle\Entity\AfiFactura $facturasClienteRel)
    {
        $this->facturasClienteRel->removeElement($facturasClienteRel);
    }

    /**
     * Get facturasClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasClienteRel()
    {
        return $this->facturasClienteRel;
    }

    /**
     * Add cursosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCurso $cursosClienteRel
     *
     * @return AfiCliente
     */
    public function addCursosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiCurso $cursosClienteRel)
    {
        $this->cursosClienteRel[] = $cursosClienteRel;

        return $this;
    }

    /**
     * Remove cursosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCurso $cursosClienteRel
     */
    public function removeCursosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiCurso $cursosClienteRel)
    {
        $this->cursosClienteRel->removeElement($cursosClienteRel);
    }

    /**
     * Get cursosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCursosClienteRel()
    {
        return $this->cursosClienteRel;
    }

    /**
     * Add serviciosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiServicio $serviciosClienteRel
     *
     * @return AfiCliente
     */
    public function addServiciosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiServicio $serviciosClienteRel)
    {
        $this->serviciosClienteRel[] = $serviciosClienteRel;

        return $this;
    }

    /**
     * Remove serviciosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiServicio $serviciosClienteRel
     */
    public function removeServiciosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiServicio $serviciosClienteRel)
    {
        $this->serviciosClienteRel->removeElement($serviciosClienteRel);
    }

    /**
     * Get serviciosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosClienteRel()
    {
        return $this->serviciosClienteRel;
    }

    /**
     * Set codigoRazonSocialFk
     *
     * @param integer $codigoRazonSocialFk
     *
     * @return AfiCliente
     */
    public function setCodigoRazonSocialFk($codigoRazonSocialFk)
    {
        $this->codigoRazonSocialFk = $codigoRazonSocialFk;

        return $this;
    }

    /**
     * Get codigoRazonSocialFk
     *
     * @return integer
     */
    public function getCodigoRazonSocialFk()
    {
        return $this->codigoRazonSocialFk;
    }

    /**
     * Set razonSocialRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiRazonSocial $razonSocialRel
     *
     * @return AfiCliente
     */
    public function setRazonSocialRel(\Brasa\AfiliacionBundle\Entity\AfiRazonSocial $razonSocialRel = null)
    {
        $this->razonSocialRel = $razonSocialRel;

        return $this;
    }

    /**
     * Get razonSocialRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiRazonSocial
     */
    public function getRazonSocialRel()
    {
        return $this->razonSocialRel;
    }

    /**
     * Set codigoEntidadRiesgoFk
     *
     * @param integer $codigoEntidadRiesgoFk
     *
     * @return AfiCliente
     */
    public function setCodigoEntidadRiesgoFk($codigoEntidadRiesgoFk)
    {
        $this->codigoEntidadRiesgoFk = $codigoEntidadRiesgoFk;

        return $this;
    }

    /**
     * Get codigoEntidadRiesgoFk
     *
     * @return integer
     */
    public function getCodigoEntidadRiesgoFk()
    {
        return $this->codigoEntidadRiesgoFk;
    }

    /**
     * Set entidadRiesgoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional $entidadRiesgoRel
     *
     * @return AfiCliente
     */
    public function setEntidadRiesgoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional $entidadRiesgoRel = null)
    {
        $this->entidadRiesgoRel = $entidadRiesgoRel;

        return $this;
    }

    /**
     * Get entidadRiesgoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional
     */
    public function getEntidadRiesgoRel()
    {
        return $this->entidadRiesgoRel;
    }
}
