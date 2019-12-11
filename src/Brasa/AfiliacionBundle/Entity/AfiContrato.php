<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_contrato")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiContratoRepository")
 */
class AfiContrato
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoPk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_sucursal_fk", type="integer", nullable=true)
     */
    private $codigoSucursalFk;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;

    /**
     * @ORM\Column(name="estado_activo", type="boolean")
     */
    private $estadoActivo = true;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="indefinido", type="boolean")
     */
    private $indefinido = false;

    /**
     * @ORM\Column(name="codigo_clasificacion_riesgo_fk", type="integer")
     */
    private $codigoClasificacionRiesgoFk;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer")
     */
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="codigo_tipo_cotizante_fk", type="integer", nullable=false)
     */
    private $codigoTipoCotizanteFk;

    /**
     * @ORM\Column(name="codigo_subtipo_cotizante_fk", type="integer", nullable=false)
     */
    private $codigoSubtipoCotizanteFk;

    /**
     * @ORM\Column(name="salario_integral", type="boolean")
     */
    private $salarioIntegral = false;

    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */
    private $codigoEntidadSaludFk;

    /**
     * @ORM\Column(name="codigo_entidad_pension_fk", type="integer", nullable=true)
     */
    private $codigoEntidadPensionFk;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\Column(name="codigo_entidad_caja_fk", type="integer", nullable=true)
     */
    private $codigoEntidadCajaFk;

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
     * @ORM\Column(name="estado_generado_cta_cobrar", type="boolean")
     */
    private $estadoGeneradoCtaCobrar = false;

    /**
     * @ORM\Column(name="estado_historial_contrato", type="boolean")
     */
    private $estadoHistorialContrato = false;

    /**
     * @ORM\Column(name="numero_recibo", type="string", length=30, nullable=true)
     */
    private $numeroRecibo;

    /**
     * @ORM\Column(name="valor", type="float")
     */
    private $valor = 0;

    /**
     * @ORM\Column(name="forma_pago", type="string", length=30, nullable=true)
     */
    private $formaPago;

    /**
     * @ORM\ManyToOne(targetEntity="AfiEmpleado", inversedBy="contratosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="AfiCliente", inversedBy="contratosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="AfiSucursal", inversedBy="contratosSucursalRel")
     * @ORM\JoinColumn(name="codigo_sucursal_fk", referencedColumnName="codigo_sucursal_pk")
     */
    protected $sucursalRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo", inversedBy="afiContratosClasificacionRiesgoRel")
     * @ORM\JoinColumn(name="codigo_clasificacion_riesgo_fk", referencedColumnName="codigo_clasificacion_riesgo_pk")
     */
    protected $clasificacionRiesgoRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuCargo", inversedBy="afiContratosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuSsoTipoCotizante", inversedBy="afiContratosSsoTipoCotizanteRel")
     * @ORM\JoinColumn(name="codigo_tipo_cotizante_fk", referencedColumnName="codigo_tipo_cotizante_pk")
     */
    protected $ssoTipoCotizanteRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuSsoSubtipoCotizante", inversedBy="afiContratosSsoSubtipoCotizanteRel")
     * @ORM\JoinColumn(name="codigo_subtipo_cotizante_fk", referencedColumnName="codigo_subtipo_cotizante_pk")
     */
    protected $ssoSubtipoCotizanteRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud", inversedBy="afiContratosEntidadSaludRel")
     * @ORM\JoinColumn(name="codigo_entidad_salud_fk", referencedColumnName="codigo_entidad_salud_pk")
     */
    protected $entidadSaludRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension", inversedBy="afiContratosEntidadPensionRel")
     * @ORM\JoinColumn(name="codigo_entidad_pension_fk", referencedColumnName="codigo_entidad_pension_pk")
     */
    protected $entidadPensionRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja", inversedBy="afiContratosEntidadCajaRel")
     * @ORM\JoinColumn(name="codigo_entidad_caja_fk", referencedColumnName="codigo_entidad_caja_pk")
     */
    protected $entidadCajaRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiPeriodoDetalle", mappedBy="contratoRel")
     */
    protected $periodosDetallesContratoRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiPeriodoDetallePago", mappedBy="contratoRel")
     */
    protected $periodosDetallesPagosContratoRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiPeriodoDetallePagoDetalle", mappedBy="contratoRel")
     */
    protected $periodosDetallesPagosDetallesContratoRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiNovedad", mappedBy="contratoRel")
     */
    protected $novedadesContratoRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiFacturaDetalleAfiliacion", mappedBy="contratoRel")
     */
    protected $facturasDetallesAfiliacionesContratosRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiCambioSalario", mappedBy="contratoRel")
     */
    protected $cambiosSalariosContratoRel;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->periodosDetallesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->periodosDetallesPagosContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->periodosDetallesPagosDetallesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->novedadesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasDetallesAfiliacionesContratosRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cambiosSalariosContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoContratoPk
     *
     * @return integer
     */
    public function getCodigoContratoPk()
    {
        return $this->codigoContratoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return AfiContrato
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
     * @return AfiContrato
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
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return AfiContrato
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
     * Set codigoSucursalFk
     *
     * @param integer $codigoSucursalFk
     *
     * @return AfiContrato
     */
    public function setCodigoSucursalFk($codigoSucursalFk)
    {
        $this->codigoSucursalFk = $codigoSucursalFk;

        return $this;
    }

    /**
     * Get codigoSucursalFk
     *
     * @return integer
     */
    public function getCodigoSucursalFk()
    {
        return $this->codigoSucursalFk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return AfiContrato
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
     * @return AfiContrato
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
     * Set numero
     *
     * @param string $numero
     *
     * @return AfiContrato
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
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return AfiContrato
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
     * @return AfiContrato
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
     * @return AfiContrato
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
     * Set indefinido
     *
     * @param boolean $indefinido
     *
     * @return AfiContrato
     */
    public function setIndefinido($indefinido)
    {
        $this->indefinido = $indefinido;

        return $this;
    }

    /**
     * Get indefinido
     *
     * @return boolean
     */
    public function getIndefinido()
    {
        return $this->indefinido;
    }

    /**
     * Set codigoClasificacionRiesgoFk
     *
     * @param integer $codigoClasificacionRiesgoFk
     *
     * @return AfiContrato
     */
    public function setCodigoClasificacionRiesgoFk($codigoClasificacionRiesgoFk)
    {
        $this->codigoClasificacionRiesgoFk = $codigoClasificacionRiesgoFk;

        return $this;
    }

    /**
     * Get codigoClasificacionRiesgoFk
     *
     * @return integer
     */
    public function getCodigoClasificacionRiesgoFk()
    {
        return $this->codigoClasificacionRiesgoFk;
    }

    /**
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return AfiContrato
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
     * Set codigoTipoCotizanteFk
     *
     * @param integer $codigoTipoCotizanteFk
     *
     * @return AfiContrato
     */
    public function setCodigoTipoCotizanteFk($codigoTipoCotizanteFk)
    {
        $this->codigoTipoCotizanteFk = $codigoTipoCotizanteFk;

        return $this;
    }

    /**
     * Get codigoTipoCotizanteFk
     *
     * @return integer
     */
    public function getCodigoTipoCotizanteFk()
    {
        return $this->codigoTipoCotizanteFk;
    }

    /**
     * Set codigoSubtipoCotizanteFk
     *
     * @param integer $codigoSubtipoCotizanteFk
     *
     * @return AfiContrato
     */
    public function setCodigoSubtipoCotizanteFk($codigoSubtipoCotizanteFk)
    {
        $this->codigoSubtipoCotizanteFk = $codigoSubtipoCotizanteFk;

        return $this;
    }

    /**
     * Get codigoSubtipoCotizanteFk
     *
     * @return integer
     */
    public function getCodigoSubtipoCotizanteFk()
    {
        return $this->codigoSubtipoCotizanteFk;
    }

    /**
     * Set salarioIntegral
     *
     * @param boolean $salarioIntegral
     *
     * @return AfiContrato
     */
    public function setSalarioIntegral($salarioIntegral)
    {
        $this->salarioIntegral = $salarioIntegral;

        return $this;
    }

    /**
     * Get salarioIntegral
     *
     * @return boolean
     */
    public function getSalarioIntegral()
    {
        return $this->salarioIntegral;
    }

    /**
     * Set codigoEntidadSaludFk
     *
     * @param integer $codigoEntidadSaludFk
     *
     * @return AfiContrato
     */
    public function setCodigoEntidadSaludFk($codigoEntidadSaludFk)
    {
        $this->codigoEntidadSaludFk = $codigoEntidadSaludFk;

        return $this;
    }

    /**
     * Get codigoEntidadSaludFk
     *
     * @return integer
     */
    public function getCodigoEntidadSaludFk()
    {
        return $this->codigoEntidadSaludFk;
    }

    /**
     * Set codigoEntidadPensionFk
     *
     * @param integer $codigoEntidadPensionFk
     *
     * @return AfiContrato
     */
    public function setCodigoEntidadPensionFk($codigoEntidadPensionFk)
    {
        $this->codigoEntidadPensionFk = $codigoEntidadPensionFk;

        return $this;
    }

    /**
     * Get codigoEntidadPensionFk
     *
     * @return integer
     */
    public function getCodigoEntidadPensionFk()
    {
        return $this->codigoEntidadPensionFk;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return AfiContrato
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
     * Set codigoEntidadCajaFk
     *
     * @param integer $codigoEntidadCajaFk
     *
     * @return AfiContrato
     */
    public function setCodigoEntidadCajaFk($codigoEntidadCajaFk)
    {
        $this->codigoEntidadCajaFk = $codigoEntidadCajaFk;

        return $this;
    }

    /**
     * Get codigoEntidadCajaFk
     *
     * @return integer
     */
    public function getCodigoEntidadCajaFk()
    {
        return $this->codigoEntidadCajaFk;
    }

    /**
     * Set generaSalud
     *
     * @param boolean $generaSalud
     *
     * @return AfiContrato
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
     * @return AfiContrato
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
     * @return AfiContrato
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
     * @return AfiContrato
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
     * @return AfiContrato
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
     * @return AfiContrato
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
     * @return AfiContrato
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
     * @return AfiContrato
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
     * @return AfiContrato
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
     * Set estadoGeneradoCtaCobrar
     *
     * @param boolean $estadoGeneradoCtaCobrar
     *
     * @return AfiContrato
     */
    public function setEstadoGeneradoCtaCobrar($estadoGeneradoCtaCobrar)
    {
        $this->estadoGeneradoCtaCobrar = $estadoGeneradoCtaCobrar;

        return $this;
    }

    /**
     * Get estadoGeneradoCtaCobrar
     *
     * @return boolean
     */
    public function getEstadoGeneradoCtaCobrar()
    {
        return $this->estadoGeneradoCtaCobrar;
    }

    /**
     * Set estadoHistorialContrato
     *
     * @param boolean $estadoHistorialContrato
     *
     * @return AfiContrato
     */
    public function setEstadoHistorialContrato($estadoHistorialContrato)
    {
        $this->estadoHistorialContrato = $estadoHistorialContrato;

        return $this;
    }

    /**
     * Get estadoHistorialContrato
     *
     * @return boolean
     */
    public function getEstadoHistorialContrato()
    {
        return $this->estadoHistorialContrato;
    }

    /**
     * Set numeroRecibo
     *
     * @param string $numeroRecibo
     *
     * @return AfiContrato
     */
    public function setNumeroRecibo($numeroRecibo)
    {
        $this->numeroRecibo = $numeroRecibo;

        return $this;
    }

    /**
     * Get numeroRecibo
     *
     * @return string
     */
    public function getNumeroRecibo()
    {
        return $this->numeroRecibo;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return AfiContrato
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
     * Set formaPago
     *
     * @param string $formaPago
     *
     * @return AfiContrato
     */
    public function setFormaPago($formaPago)
    {
        $this->formaPago = $formaPago;

        return $this;
    }

    /**
     * Get formaPago
     *
     * @return string
     */
    public function getFormaPago()
    {
        return $this->formaPago;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadoRel
     *
     * @return AfiContrato
     */
    public function setEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $clienteRel
     *
     * @return AfiContrato
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
     * Set sucursalRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiSucursal $sucursalRel
     *
     * @return AfiContrato
     */
    public function setSucursalRel(\Brasa\AfiliacionBundle\Entity\AfiSucursal $sucursalRel = null)
    {
        $this->sucursalRel = $sucursalRel;

        return $this;
    }

    /**
     * Get sucursalRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiSucursal
     */
    public function getSucursalRel()
    {
        return $this->sucursalRel;
    }

    /**
     * Set clasificacionRiesgoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo $clasificacionRiesgoRel
     *
     * @return AfiContrato
     */
    public function setClasificacionRiesgoRel(\Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo $clasificacionRiesgoRel = null)
    {
        $this->clasificacionRiesgoRel = $clasificacionRiesgoRel;

        return $this;
    }

    /**
     * Get clasificacionRiesgoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo
     */
    public function getClasificacionRiesgoRel()
    {
        return $this->clasificacionRiesgoRel;
    }

    /**
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return AfiContrato
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
     * Set ssoTipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoTipoCotizante $ssoTipoCotizanteRel
     *
     * @return AfiContrato
     */
    public function setSsoTipoCotizanteRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoTipoCotizante $ssoTipoCotizanteRel = null)
    {
        $this->ssoTipoCotizanteRel = $ssoTipoCotizanteRel;

        return $this;
    }

    /**
     * Get ssoTipoCotizanteRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoTipoCotizante
     */
    public function getSsoTipoCotizanteRel()
    {
        return $this->ssoTipoCotizanteRel;
    }

    /**
     * Set ssoSubtipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoSubtipoCotizante $ssoSubtipoCotizanteRel
     *
     * @return AfiContrato
     */
    public function setSsoSubtipoCotizanteRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoSubtipoCotizante $ssoSubtipoCotizanteRel = null)
    {
        $this->ssoSubtipoCotizanteRel = $ssoSubtipoCotizanteRel;

        return $this;
    }

    /**
     * Get ssoSubtipoCotizanteRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoSubtipoCotizante
     */
    public function getSsoSubtipoCotizanteRel()
    {
        return $this->ssoSubtipoCotizanteRel;
    }

    /**
     * Set entidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludRel
     *
     * @return AfiContrato
     */
    public function setEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludRel = null)
    {
        $this->entidadSaludRel = $entidadSaludRel;

        return $this;
    }

    /**
     * Get entidadSaludRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud
     */
    public function getEntidadSaludRel()
    {
        return $this->entidadSaludRel;
    }

    /**
     * Set entidadPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension $entidadPensionRel
     *
     * @return AfiContrato
     */
    public function setEntidadPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension $entidadPensionRel = null)
    {
        $this->entidadPensionRel = $entidadPensionRel;

        return $this;
    }

    /**
     * Get entidadPensionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension
     */
    public function getEntidadPensionRel()
    {
        return $this->entidadPensionRel;
    }

    /**
     * Set entidadCajaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja $entidadCajaRel
     *
     * @return AfiContrato
     */
    public function setEntidadCajaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja $entidadCajaRel = null)
    {
        $this->entidadCajaRel = $entidadCajaRel;

        return $this;
    }

    /**
     * Get entidadCajaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja
     */
    public function getEntidadCajaRel()
    {
        return $this->entidadCajaRel;
    }

    /**
     * Add periodosDetallesContratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle $periodosDetallesContratoRel
     *
     * @return AfiContrato
     */
    public function addPeriodosDetallesContratoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle $periodosDetallesContratoRel)
    {
        $this->periodosDetallesContratoRel[] = $periodosDetallesContratoRel;

        return $this;
    }

    /**
     * Remove periodosDetallesContratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle $periodosDetallesContratoRel
     */
    public function removePeriodosDetallesContratoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle $periodosDetallesContratoRel)
    {
        $this->periodosDetallesContratoRel->removeElement($periodosDetallesContratoRel);
    }

    /**
     * Get periodosDetallesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodosDetallesContratoRel()
    {
        return $this->periodosDetallesContratoRel;
    }

    /**
     * Add periodosDetallesPagosContratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosContratoRel
     *
     * @return AfiContrato
     */
    public function addPeriodosDetallesPagosContratoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosContratoRel)
    {
        $this->periodosDetallesPagosContratoRel[] = $periodosDetallesPagosContratoRel;

        return $this;
    }

    /**
     * Remove periodosDetallesPagosContratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosContratoRel
     */
    public function removePeriodosDetallesPagosContratoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosContratoRel)
    {
        $this->periodosDetallesPagosContratoRel->removeElement($periodosDetallesPagosContratoRel);
    }

    /**
     * Get periodosDetallesPagosContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodosDetallesPagosContratoRel()
    {
        return $this->periodosDetallesPagosContratoRel;
    }

    /**
     * Add periodosDetallesPagosDetallesContratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesContratoRel
     *
     * @return AfiContrato
     */
    public function addPeriodosDetallesPagosDetallesContratoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesContratoRel)
    {
        $this->periodosDetallesPagosDetallesContratoRel[] = $periodosDetallesPagosDetallesContratoRel;

        return $this;
    }

    /**
     * Remove periodosDetallesPagosDetallesContratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesContratoRel
     */
    public function removePeriodosDetallesPagosDetallesContratoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesContratoRel)
    {
        $this->periodosDetallesPagosDetallesContratoRel->removeElement($periodosDetallesPagosDetallesContratoRel);
    }

    /**
     * Get periodosDetallesPagosDetallesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodosDetallesPagosDetallesContratoRel()
    {
        return $this->periodosDetallesPagosDetallesContratoRel;
    }

    /**
     * Add novedadesContratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiNovedad $novedadesContratoRel
     *
     * @return AfiContrato
     */
    public function addNovedadesContratoRel(\Brasa\AfiliacionBundle\Entity\AfiNovedad $novedadesContratoRel)
    {
        $this->novedadesContratoRel[] = $novedadesContratoRel;

        return $this;
    }

    /**
     * Remove novedadesContratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiNovedad $novedadesContratoRel
     */
    public function removeNovedadesContratoRel(\Brasa\AfiliacionBundle\Entity\AfiNovedad $novedadesContratoRel)
    {
        $this->novedadesContratoRel->removeElement($novedadesContratoRel);
    }

    /**
     * Get novedadesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNovedadesContratoRel()
    {
        return $this->novedadesContratoRel;
    }

    /**
     * Add facturasDetallesAfiliacionesContratosRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion $facturasDetallesAfiliacionesContratosRel
     *
     * @return AfiContrato
     */
    public function addFacturasDetallesAfiliacionesContratosRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion $facturasDetallesAfiliacionesContratosRel)
    {
        $this->facturasDetallesAfiliacionesContratosRel[] = $facturasDetallesAfiliacionesContratosRel;

        return $this;
    }

    /**
     * Remove facturasDetallesAfiliacionesContratosRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion $facturasDetallesAfiliacionesContratosRel
     */
    public function removeFacturasDetallesAfiliacionesContratosRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion $facturasDetallesAfiliacionesContratosRel)
    {
        $this->facturasDetallesAfiliacionesContratosRel->removeElement($facturasDetallesAfiliacionesContratosRel);
    }

    /**
     * Get facturasDetallesAfiliacionesContratosRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesAfiliacionesContratosRel()
    {
        return $this->facturasDetallesAfiliacionesContratosRel;
    }

    /**
     * Add cambiosSalariosContratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCambioSalario $cambiosSalariosContratoRel
     *
     * @return AfiContrato
     */
    public function addCambiosSalariosContratoRel(\Brasa\AfiliacionBundle\Entity\AfiCambioSalario $cambiosSalariosContratoRel)
    {
        $this->cambiosSalariosContratoRel[] = $cambiosSalariosContratoRel;

        return $this;
    }

    /**
     * Remove cambiosSalariosContratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCambioSalario $cambiosSalariosContratoRel
     */
    public function removeCambiosSalariosContratoRel(\Brasa\AfiliacionBundle\Entity\AfiCambioSalario $cambiosSalariosContratoRel)
    {
        $this->cambiosSalariosContratoRel->removeElement($cambiosSalariosContratoRel);
    }

    /**
     * Get cambiosSalariosContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCambiosSalariosContratoRel()
    {
        return $this->cambiosSalariosContratoRel;
    }
}
