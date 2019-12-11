<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_contrato")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuContratoRepository")
 */
class RhuContrato
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoPk;        
    
    /**
     * @ORM\Column(name="codigo_contrato_tipo_fk", type="integer")
     */    
    private $codigoContratoTipoFk;         
    
    /**
     * @ORM\Column(name="codigo_contrato_clase_fk", type="integer", nullable=true)
     */    
    private $codigoContratoClaseFk;    
    
    /**
     * @ORM\Column(name="codigo_salario_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoSalarioTipoFk;  
    
    /**
     * @ORM\Column(name="codigo_clasificacion_riesgo_fk", type="integer")
     */    
    private $codigoClasificacionRiesgoFk;
    
    /**
     * @ORM\Column(name="codigo_motivo_terminacion_contrato_fk", type="integer", nullable=true)
     */    
    private $codigoMotivoTerminacionContratoFk;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     
    
    /**
     * @ORM\Column(name="codigo_tipo_tiempo_fk", type="integer")
     */    
    private $codigoTipoTiempoFk;    

    /**
     * @ORM\Column(name="codigo_tipo_pension_fk", type="integer")
     */    
    private $codigoTipoPensionFk;    

    /**
     * @ORM\Column(name="codigo_tipo_salud_fk", type="integer", nullable=true)
     */    
    private $codigoTipoSaludFk; 
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;
    
    /**
     * @ORM\Column(name="fecha_prorroga_inicio", type="date", nullable=true)
     */    
    private $fechaProrrogaInicio;    
    
    /**
     * @ORM\Column(name="fecha_prorroga_final", type="date", nullable=true)
     */    
    private $fechaProrrogaFinal;
    
    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */    
    private $numero;     
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer")
     */    
    private $codigoCargoFk;    
    
    /**
     * @ORM\Column(name="cargo_descripcion", type="string", length=60, nullable=true)
     */    
    private $cargoDescripcion;
    
    /**
     * @ORM\Column(name="horario_trabajo", type="string", length=100, nullable=true)
     */    
    private $horarioTrabajo;
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;    
    
    /**
     * @ORM\Column(name="vr_salario_pago", type="float")
     */
    private $VrSalarioPago = 0;    
    
    /**     
     * @ORM\Column(name="estado_activo", type="boolean")
     */    
    private $estadoActivo = 1;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;    
    
    /**
     * @ORM\Column(name="comentarios_terminacion", type="string", length=200, nullable=true)
     */    
    private $comentariosTerminacion;    
    
    /**     
     * @ORM\Column(name="indefinido", type="boolean")
     */    
    private $indefinido = 0;     
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer")
     */    
    private $codigoCentroCostoFk;     
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_cesantias", type="date", nullable=true)
     */    
    private $fechaUltimoPagoCesantias;    

    /**
     * @ORM\Column(name="fecha_ultimo_pago_vacaciones", type="date", nullable=true)
     */    
    private $fechaUltimoPagoVacaciones;    
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_primas", type="date", nullable=true)
     */    
    private $fechaUltimoPagoPrimas;        
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago", type="date", nullable=true)
     */    
    private $fechaUltimoPago;             
    
    /**     
     * @ORM\Column(name="estado_liquidado", type="boolean")
     */    
    private $estadoLiquidado = 0; 
    
    /**     
     * @ORM\Column(name="estado_terminado", type="boolean")
     */    
    private $estadoTerminado = 0;        

    /**
     * @ORM\Column(name="ibp_cesantias_inicial", type="float", nullable=true)
     */
    private $ibpCesantiasInicial = 0;

    /**
     * @ORM\Column(name="ibp_primas_inicial", type="float", nullable=true)
     */
    private $ibpPrimasInicial = 0;
    
    /**
     * Se utiliza para liquidar vacaciones cuando no se tiene historia de los recargos nocturnos
     * @ORM\Column(name="promedio_recargo_nocturno_inicial", type="float", nullable=true)
     */
    private $promedioRecargoNocturnoInicial = 0;    
    
    /**
     * Este factor se utiliza para saber de cuantas horas se compone un dia
     * @ORM\Column(name="factor", type="integer", nullable=true)     
     */    
    private $factor = 0;     
    
    /**
     * @ORM\Column(name="factor_horas_dia", type="integer", nullable=true)
     */    
    private $factorHorasDia = 0;    
    
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
     * @ORM\Column(name="codigo_entidad_cesantia_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadCesantiaFk;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;

    /**
     * @ORM\Column(name="codigo_usuario_termina", type="string", length=50, nullable=true)
     */    
    private $codigoUsuarioTermina;    
    
     /**
     * @ORM\Column(name="codigo_entidad_caja_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadCajaFk;
    
    /**
     * @ORM\Column(name="codigo_ciudad_contrato_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadContratoFk;
    
    /**
     * @ORM\Column(name="codigo_ciudad_labora_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadLaboraFk;
    
    /**
     * @ORM\Column(name="codigo_contrato_grupo_fk", type="integer", nullable=true)
     */    
    private $codigoContratoGrupoFk;     
    
    /**     
     * @ORM\Column(name="limitarHoraExtra", type="boolean")
     */    
    private $limitarHoraExtra = false;     
    
    /**
     * @ORM\Column(name="vr_devengado_pactado", type="float")
     */
    private $VrDevengadoPactado = 0;     
    
    /**     
     * @ORM\Column(name="turno_fijo_ordinario", type="boolean")
     */    
    private $turnoFijoOrdinario = false;     
    
    /**
     * @ORM\Column(name="secuencia", type="integer", nullable=true)
     */    
    private $secuencia;            
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="contratosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoTiempo", inversedBy="contratosTipoTiempoRel")
     * @ORM\JoinColumn(name="codigo_tipo_tiempo_fk", referencedColumnName="codigo_tipo_tiempo_pk")
     */
    protected $tipoTiempoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="empleadosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuContratoTipo", inversedBy="contratosContratoTipoRel")
     * @ORM\JoinColumn(name="codigo_contrato_tipo_fk", referencedColumnName="codigo_contrato_tipo_pk")
     */
    protected $contratoTipoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuContratoGrupo", inversedBy="contratosContratoGrupoRel")
     * @ORM\JoinColumn(name="codigo_contrato_grupo_fk", referencedColumnName="codigo_contrato_grupo_pk")
     */
    protected $contratoGrupoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuContratoClase", inversedBy="contratosContratoClaseRel")
     * @ORM\JoinColumn(name="codigo_contrato_clase_fk", referencedColumnName="codigo_contrato_clase_pk")
     */
    protected $contratoClaseRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSalarioTipo", inversedBy="contratosSalarioTipoRel")
     * @ORM\JoinColumn(name="codigo_salario_tipo_fk", referencedColumnName="codigo_salario_tipo_pk")
     */
    protected $salarioTipoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuClasificacionRiesgo", inversedBy="contratosClasificacionRiesgoRel")
     * @ORM\JoinColumn(name="codigo_clasificacion_riesgo_fk", referencedColumnName="codigo_clasificacion_riesgo_pk")
     */
    protected $clasificacionRiesgoRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="contratosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoPension", inversedBy="contratosTipoPensionRel")
     * @ORM\JoinColumn(name="codigo_tipo_pension_fk", referencedColumnName="codigo_tipo_pension_pk")
     */
    protected $tipoPensionRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoSalud", inversedBy="contratosTipoSaludRel")
     * @ORM\JoinColumn(name="codigo_tipo_salud_fk", referencedColumnName="codigo_tipo_salud_pk")
     */
    protected $tipoSaludRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoTipoCotizante", inversedBy="contratosSsoTipoCotizanteRel")
     * @ORM\JoinColumn(name="codigo_tipo_cotizante_fk", referencedColumnName="codigo_tipo_cotizante_pk")
     */
    protected $ssoTipoCotizanteRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoSubtipoCotizante", inversedBy="contratosSsoSubtipoCotizanteRel")
     * @ORM\JoinColumn(name="codigo_subtipo_cotizante_fk", referencedColumnName="codigo_subtipo_cotizante_pk")
     */
    protected $ssoSubtipoCotizanteRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuMotivoTerminacionContrato", inversedBy="contratosMotivoTerminacionContratoRel")
     * @ORM\JoinColumn(name="codigo_motivo_terminacion_contrato_fk", referencedColumnName="codigo_motivo_terminacion_contrato_pk")
     */
    protected $terminacionContratoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadSalud", inversedBy="contratosEntidadSaludRel")
     * @ORM\JoinColumn(name="codigo_entidad_salud_fk", referencedColumnName="codigo_entidad_salud_pk")
     */
    protected $entidadSaludRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadPension", inversedBy="contratosEntidadPensionRel")
     * @ORM\JoinColumn(name="codigo_entidad_pension_fk", referencedColumnName="codigo_entidad_pension_pk")
     */
    protected $entidadPensionRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadCesantia", inversedBy="contratosEntidadCesantiaRel")
     * @ORM\JoinColumn(name="codigo_entidad_cesantia_fk", referencedColumnName="codigo_entidad_cesantia_pk")
     */
    protected $entidadCesantiaRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadCaja", inversedBy="contratosEntidadCajaRel")
     * @ORM\JoinColumn(name="codigo_entidad_caja_fk", referencedColumnName="codigo_entidad_caja_pk")
     */
    protected $entidadCajaRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuContratosCiudadContratoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_contrato_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadContratoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuContratosCiudadLaboraRel")
     * @ORM\JoinColumn(name="codigo_ciudad_labora_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadLaboraRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacion", mappedBy="contratoRel")
     */
    protected $liquidacionesContratoRel; 

    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionPagoDetalle", mappedBy="contratoRel")
     */
    protected $programacionesPagosDetallesContratoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="contratoRel")
     */
    protected $pagosContratoRel;      
    
    /**
     * @ORM\OneToMany(targetEntity="RhuProvision", mappedBy="contratoRel")
     */
    protected $provisionesContratoRel;    
    
     /**
     * @ORM\OneToMany(targetEntity="RhuSsoPeriodoEmpleado", mappedBy="contratoRel")
     */
    protected $ssoPeriodosEmpleadosContratoRel;    

     /**
     * @ORM\OneToMany(targetEntity="RhuSsoAporte", mappedBy="contratoRel")
     */
    protected $ssoAportesContratoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCambioSalario", mappedBy="contratoRel")
     */
    protected $cambiosSalariosContratoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuVacacion", mappedBy="contratoRel")
     */
    protected $vacacionesContratoRel;             
    
    /**
     * @ORM\OneToMany(targetEntity="RhuLicencia", mappedBy="contratoRel")
     */
    protected $licenciasContratoRel;  
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="contratoRel")
     */
    protected $incapacidadesContratoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIngresoBase", mappedBy="contratoRel")
     */
    protected $ingresosBasesContratoRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuContratoSede", mappedBy="contratoRel")
     */
    protected $contratosSedesContratoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuProyeccion", mappedBy="contratoRel")
     */
    protected $proyeccionesContratoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuTrasladoPension", mappedBy="contratoRel")
     */
    protected $trasladosPensionesContratoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuTrasladoSalud", mappedBy="contratoRel")
     */
    protected $trasladosSaludContratoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContratoProrroga", mappedBy="contratoRel")
     */
    protected $contratosProrrogasContratoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSoportePagoHorarioDetalle", mappedBy="contratoRel")
     */
    protected $soportesPagosHorariosDetallesContratoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCambioTipoContrato", mappedBy="contratoRel")
     */
    protected $cambiosTiposContratosContratoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDisciplinario", mappedBy="contratoRel")
     */
    protected $disciplinariosContratoRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->liquidacionesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->programacionesPagosDetallesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->provisionesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ssoPeriodosEmpleadosContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ssoAportesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cambiosSalariosContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->vacacionesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->licenciasContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incapacidadesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ingresosBasesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosSedesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->proyeccionesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->trasladosPensionesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->trasladosSaludContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosProrrogasContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soportesPagosHorariosDetallesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cambiosTiposContratosContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disciplinariosContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set codigoContratoTipoFk
     *
     * @param integer $codigoContratoTipoFk
     *
     * @return RhuContrato
     */
    public function setCodigoContratoTipoFk($codigoContratoTipoFk)
    {
        $this->codigoContratoTipoFk = $codigoContratoTipoFk;

        return $this;
    }

    /**
     * Get codigoContratoTipoFk
     *
     * @return integer
     */
    public function getCodigoContratoTipoFk()
    {
        return $this->codigoContratoTipoFk;
    }

    /**
     * Set codigoContratoClaseFk
     *
     * @param integer $codigoContratoClaseFk
     *
     * @return RhuContrato
     */
    public function setCodigoContratoClaseFk($codigoContratoClaseFk)
    {
        $this->codigoContratoClaseFk = $codigoContratoClaseFk;

        return $this;
    }

    /**
     * Get codigoContratoClaseFk
     *
     * @return integer
     */
    public function getCodigoContratoClaseFk()
    {
        return $this->codigoContratoClaseFk;
    }

    /**
     * Set codigoSalarioTipoFk
     *
     * @param integer $codigoSalarioTipoFk
     *
     * @return RhuContrato
     */
    public function setCodigoSalarioTipoFk($codigoSalarioTipoFk)
    {
        $this->codigoSalarioTipoFk = $codigoSalarioTipoFk;

        return $this;
    }

    /**
     * Get codigoSalarioTipoFk
     *
     * @return integer
     */
    public function getCodigoSalarioTipoFk()
    {
        return $this->codigoSalarioTipoFk;
    }

    /**
     * Set codigoClasificacionRiesgoFk
     *
     * @param integer $codigoClasificacionRiesgoFk
     *
     * @return RhuContrato
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
     * Set codigoMotivoTerminacionContratoFk
     *
     * @param integer $codigoMotivoTerminacionContratoFk
     *
     * @return RhuContrato
     */
    public function setCodigoMotivoTerminacionContratoFk($codigoMotivoTerminacionContratoFk)
    {
        $this->codigoMotivoTerminacionContratoFk = $codigoMotivoTerminacionContratoFk;

        return $this;
    }

    /**
     * Get codigoMotivoTerminacionContratoFk
     *
     * @return integer
     */
    public function getCodigoMotivoTerminacionContratoFk()
    {
        return $this->codigoMotivoTerminacionContratoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuContrato
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
     * Set codigoTipoTiempoFk
     *
     * @param integer $codigoTipoTiempoFk
     *
     * @return RhuContrato
     */
    public function setCodigoTipoTiempoFk($codigoTipoTiempoFk)
    {
        $this->codigoTipoTiempoFk = $codigoTipoTiempoFk;

        return $this;
    }

    /**
     * Get codigoTipoTiempoFk
     *
     * @return integer
     */
    public function getCodigoTipoTiempoFk()
    {
        return $this->codigoTipoTiempoFk;
    }

    /**
     * Set codigoTipoPensionFk
     *
     * @param integer $codigoTipoPensionFk
     *
     * @return RhuContrato
     */
    public function setCodigoTipoPensionFk($codigoTipoPensionFk)
    {
        $this->codigoTipoPensionFk = $codigoTipoPensionFk;

        return $this;
    }

    /**
     * Get codigoTipoPensionFk
     *
     * @return integer
     */
    public function getCodigoTipoPensionFk()
    {
        return $this->codigoTipoPensionFk;
    }

    /**
     * Set codigoTipoSaludFk
     *
     * @param integer $codigoTipoSaludFk
     *
     * @return RhuContrato
     */
    public function setCodigoTipoSaludFk($codigoTipoSaludFk)
    {
        $this->codigoTipoSaludFk = $codigoTipoSaludFk;

        return $this;
    }

    /**
     * Get codigoTipoSaludFk
     *
     * @return integer
     */
    public function getCodigoTipoSaludFk()
    {
        return $this->codigoTipoSaludFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuContrato
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuContrato
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
     * @return RhuContrato
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
     * Set fechaProrrogaInicio
     *
     * @param \DateTime $fechaProrrogaInicio
     *
     * @return RhuContrato
     */
    public function setFechaProrrogaInicio($fechaProrrogaInicio)
    {
        $this->fechaProrrogaInicio = $fechaProrrogaInicio;

        return $this;
    }

    /**
     * Get fechaProrrogaInicio
     *
     * @return \DateTime
     */
    public function getFechaProrrogaInicio()
    {
        return $this->fechaProrrogaInicio;
    }

    /**
     * Set fechaProrrogaFinal
     *
     * @param \DateTime $fechaProrrogaFinal
     *
     * @return RhuContrato
     */
    public function setFechaProrrogaFinal($fechaProrrogaFinal)
    {
        $this->fechaProrrogaFinal = $fechaProrrogaFinal;

        return $this;
    }

    /**
     * Get fechaProrrogaFinal
     *
     * @return \DateTime
     */
    public function getFechaProrrogaFinal()
    {
        return $this->fechaProrrogaFinal;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return RhuContrato
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
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuContrato
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
     * Set cargoDescripcion
     *
     * @param string $cargoDescripcion
     *
     * @return RhuContrato
     */
    public function setCargoDescripcion($cargoDescripcion)
    {
        $this->cargoDescripcion = $cargoDescripcion;

        return $this;
    }

    /**
     * Get cargoDescripcion
     *
     * @return string
     */
    public function getCargoDescripcion()
    {
        return $this->cargoDescripcion;
    }

    /**
     * Set horarioTrabajo
     *
     * @param string $horarioTrabajo
     *
     * @return RhuContrato
     */
    public function setHorarioTrabajo($horarioTrabajo)
    {
        $this->horarioTrabajo = $horarioTrabajo;

        return $this;
    }

    /**
     * Get horarioTrabajo
     *
     * @return string
     */
    public function getHorarioTrabajo()
    {
        return $this->horarioTrabajo;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuContrato
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
     * Set vrSalarioPago
     *
     * @param float $vrSalarioPago
     *
     * @return RhuContrato
     */
    public function setVrSalarioPago($vrSalarioPago)
    {
        $this->VrSalarioPago = $vrSalarioPago;

        return $this;
    }

    /**
     * Get vrSalarioPago
     *
     * @return float
     */
    public function getVrSalarioPago()
    {
        return $this->VrSalarioPago;
    }

    /**
     * Set estadoActivo
     *
     * @param boolean $estadoActivo
     *
     * @return RhuContrato
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
     * @return RhuContrato
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
     * Set comentariosTerminacion
     *
     * @param string $comentariosTerminacion
     *
     * @return RhuContrato
     */
    public function setComentariosTerminacion($comentariosTerminacion)
    {
        $this->comentariosTerminacion = $comentariosTerminacion;

        return $this;
    }

    /**
     * Get comentariosTerminacion
     *
     * @return string
     */
    public function getComentariosTerminacion()
    {
        return $this->comentariosTerminacion;
    }

    /**
     * Set indefinido
     *
     * @param boolean $indefinido
     *
     * @return RhuContrato
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuContrato
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
     * Set fechaUltimoPagoCesantias
     *
     * @param \DateTime $fechaUltimoPagoCesantias
     *
     * @return RhuContrato
     */
    public function setFechaUltimoPagoCesantias($fechaUltimoPagoCesantias)
    {
        $this->fechaUltimoPagoCesantias = $fechaUltimoPagoCesantias;

        return $this;
    }

    /**
     * Get fechaUltimoPagoCesantias
     *
     * @return \DateTime
     */
    public function getFechaUltimoPagoCesantias()
    {
        return $this->fechaUltimoPagoCesantias;
    }

    /**
     * Set fechaUltimoPagoVacaciones
     *
     * @param \DateTime $fechaUltimoPagoVacaciones
     *
     * @return RhuContrato
     */
    public function setFechaUltimoPagoVacaciones($fechaUltimoPagoVacaciones)
    {
        $this->fechaUltimoPagoVacaciones = $fechaUltimoPagoVacaciones;

        return $this;
    }

    /**
     * Get fechaUltimoPagoVacaciones
     *
     * @return \DateTime
     */
    public function getFechaUltimoPagoVacaciones()
    {
        return $this->fechaUltimoPagoVacaciones;
    }

    /**
     * Set fechaUltimoPagoPrimas
     *
     * @param \DateTime $fechaUltimoPagoPrimas
     *
     * @return RhuContrato
     */
    public function setFechaUltimoPagoPrimas($fechaUltimoPagoPrimas)
    {
        $this->fechaUltimoPagoPrimas = $fechaUltimoPagoPrimas;

        return $this;
    }

    /**
     * Get fechaUltimoPagoPrimas
     *
     * @return \DateTime
     */
    public function getFechaUltimoPagoPrimas()
    {
        return $this->fechaUltimoPagoPrimas;
    }

    /**
     * Set fechaUltimoPago
     *
     * @param \DateTime $fechaUltimoPago
     *
     * @return RhuContrato
     */
    public function setFechaUltimoPago($fechaUltimoPago)
    {
        $this->fechaUltimoPago = $fechaUltimoPago;

        return $this;
    }

    /**
     * Get fechaUltimoPago
     *
     * @return \DateTime
     */
    public function getFechaUltimoPago()
    {
        return $this->fechaUltimoPago;
    }

    /**
     * Set estadoLiquidado
     *
     * @param boolean $estadoLiquidado
     *
     * @return RhuContrato
     */
    public function setEstadoLiquidado($estadoLiquidado)
    {
        $this->estadoLiquidado = $estadoLiquidado;

        return $this;
    }

    /**
     * Get estadoLiquidado
     *
     * @return boolean
     */
    public function getEstadoLiquidado()
    {
        return $this->estadoLiquidado;
    }

    /**
     * Set estadoTerminado
     *
     * @param boolean $estadoTerminado
     *
     * @return RhuContrato
     */
    public function setEstadoTerminado($estadoTerminado)
    {
        $this->estadoTerminado = $estadoTerminado;

        return $this;
    }

    /**
     * Get estadoTerminado
     *
     * @return boolean
     */
    public function getEstadoTerminado()
    {
        return $this->estadoTerminado;
    }

    /**
     * Set ibpCesantiasInicial
     *
     * @param float $ibpCesantiasInicial
     *
     * @return RhuContrato
     */
    public function setIbpCesantiasInicial($ibpCesantiasInicial)
    {
        $this->ibpCesantiasInicial = $ibpCesantiasInicial;

        return $this;
    }

    /**
     * Get ibpCesantiasInicial
     *
     * @return float
     */
    public function getIbpCesantiasInicial()
    {
        return $this->ibpCesantiasInicial;
    }

    /**
     * Set ibpPrimasInicial
     *
     * @param float $ibpPrimasInicial
     *
     * @return RhuContrato
     */
    public function setIbpPrimasInicial($ibpPrimasInicial)
    {
        $this->ibpPrimasInicial = $ibpPrimasInicial;

        return $this;
    }

    /**
     * Get ibpPrimasInicial
     *
     * @return float
     */
    public function getIbpPrimasInicial()
    {
        return $this->ibpPrimasInicial;
    }

    /**
     * Set promedioRecargoNocturnoInicial
     *
     * @param float $promedioRecargoNocturnoInicial
     *
     * @return RhuContrato
     */
    public function setPromedioRecargoNocturnoInicial($promedioRecargoNocturnoInicial)
    {
        $this->promedioRecargoNocturnoInicial = $promedioRecargoNocturnoInicial;

        return $this;
    }

    /**
     * Get promedioRecargoNocturnoInicial
     *
     * @return float
     */
    public function getPromedioRecargoNocturnoInicial()
    {
        return $this->promedioRecargoNocturnoInicial;
    }

    /**
     * Set factor
     *
     * @param integer $factor
     *
     * @return RhuContrato
     */
    public function setFactor($factor)
    {
        $this->factor = $factor;

        return $this;
    }

    /**
     * Get factor
     *
     * @return integer
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Set factorHorasDia
     *
     * @param integer $factorHorasDia
     *
     * @return RhuContrato
     */
    public function setFactorHorasDia($factorHorasDia)
    {
        $this->factorHorasDia = $factorHorasDia;

        return $this;
    }

    /**
     * Get factorHorasDia
     *
     * @return integer
     */
    public function getFactorHorasDia()
    {
        return $this->factorHorasDia;
    }

    /**
     * Set codigoTipoCotizanteFk
     *
     * @param integer $codigoTipoCotizanteFk
     *
     * @return RhuContrato
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
     * @return RhuContrato
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
     * @return RhuContrato
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
     * @return RhuContrato
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
     * @return RhuContrato
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
     * Set codigoEntidadCesantiaFk
     *
     * @param integer $codigoEntidadCesantiaFk
     *
     * @return RhuContrato
     */
    public function setCodigoEntidadCesantiaFk($codigoEntidadCesantiaFk)
    {
        $this->codigoEntidadCesantiaFk = $codigoEntidadCesantiaFk;

        return $this;
    }

    /**
     * Get codigoEntidadCesantiaFk
     *
     * @return integer
     */
    public function getCodigoEntidadCesantiaFk()
    {
        return $this->codigoEntidadCesantiaFk;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuContrato
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
     * Set codigoUsuarioTermina
     *
     * @param string $codigoUsuarioTermina
     *
     * @return RhuContrato
     */
    public function setCodigoUsuarioTermina($codigoUsuarioTermina)
    {
        $this->codigoUsuarioTermina = $codigoUsuarioTermina;

        return $this;
    }

    /**
     * Get codigoUsuarioTermina
     *
     * @return string
     */
    public function getCodigoUsuarioTermina()
    {
        return $this->codigoUsuarioTermina;
    }

    /**
     * Set codigoEntidadCajaFk
     *
     * @param integer $codigoEntidadCajaFk
     *
     * @return RhuContrato
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
     * Set codigoCiudadContratoFk
     *
     * @param integer $codigoCiudadContratoFk
     *
     * @return RhuContrato
     */
    public function setCodigoCiudadContratoFk($codigoCiudadContratoFk)
    {
        $this->codigoCiudadContratoFk = $codigoCiudadContratoFk;

        return $this;
    }

    /**
     * Get codigoCiudadContratoFk
     *
     * @return integer
     */
    public function getCodigoCiudadContratoFk()
    {
        return $this->codigoCiudadContratoFk;
    }

    /**
     * Set codigoCiudadLaboraFk
     *
     * @param integer $codigoCiudadLaboraFk
     *
     * @return RhuContrato
     */
    public function setCodigoCiudadLaboraFk($codigoCiudadLaboraFk)
    {
        $this->codigoCiudadLaboraFk = $codigoCiudadLaboraFk;

        return $this;
    }

    /**
     * Get codigoCiudadLaboraFk
     *
     * @return integer
     */
    public function getCodigoCiudadLaboraFk()
    {
        return $this->codigoCiudadLaboraFk;
    }

    /**
     * Set limitarHoraExtra
     *
     * @param boolean $limitarHoraExtra
     *
     * @return RhuContrato
     */
    public function setLimitarHoraExtra($limitarHoraExtra)
    {
        $this->limitarHoraExtra = $limitarHoraExtra;

        return $this;
    }

    /**
     * Get limitarHoraExtra
     *
     * @return boolean
     */
    public function getLimitarHoraExtra()
    {
        return $this->limitarHoraExtra;
    }

    /**
     * Set vrDevengadoPactado
     *
     * @param float $vrDevengadoPactado
     *
     * @return RhuContrato
     */
    public function setVrDevengadoPactado($vrDevengadoPactado)
    {
        $this->VrDevengadoPactado = $vrDevengadoPactado;

        return $this;
    }

    /**
     * Get vrDevengadoPactado
     *
     * @return float
     */
    public function getVrDevengadoPactado()
    {
        return $this->VrDevengadoPactado;
    }

    /**
     * Set turnoFijoOrdinario
     *
     * @param boolean $turnoFijoOrdinario
     *
     * @return RhuContrato
     */
    public function setTurnoFijoOrdinario($turnoFijoOrdinario)
    {
        $this->turnoFijoOrdinario = $turnoFijoOrdinario;

        return $this;
    }

    /**
     * Get turnoFijoOrdinario
     *
     * @return boolean
     */
    public function getTurnoFijoOrdinario()
    {
        return $this->turnoFijoOrdinario;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuContrato
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
     * Set tipoTiempoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoTiempo $tipoTiempoRel
     *
     * @return RhuContrato
     */
    public function setTipoTiempoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTipoTiempo $tipoTiempoRel = null)
    {
        $this->tipoTiempoRel = $tipoTiempoRel;

        return $this;
    }

    /**
     * Get tipoTiempoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuTipoTiempo
     */
    public function getTipoTiempoRel()
    {
        return $this->tipoTiempoRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuContrato
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
     * Set contratoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratoTipoRel
     *
     * @return RhuContrato
     */
    public function setContratoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratoTipoRel = null)
    {
        $this->contratoTipoRel = $contratoTipoRel;

        return $this;
    }

    /**
     * Get contratoTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo
     */
    public function getContratoTipoRel()
    {
        return $this->contratoTipoRel;
    }

    /**
     * Set contratoClaseRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoClase $contratoClaseRel
     *
     * @return RhuContrato
     */
    public function setContratoClaseRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoClase $contratoClaseRel = null)
    {
        $this->contratoClaseRel = $contratoClaseRel;

        return $this;
    }

    /**
     * Get contratoClaseRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContratoClase
     */
    public function getContratoClaseRel()
    {
        return $this->contratoClaseRel;
    }

    /**
     * Set salarioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSalarioTipo $salarioTipoRel
     *
     * @return RhuContrato
     */
    public function setSalarioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSalarioTipo $salarioTipoRel = null)
    {
        $this->salarioTipoRel = $salarioTipoRel;

        return $this;
    }

    /**
     * Get salarioTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSalarioTipo
     */
    public function getSalarioTipoRel()
    {
        return $this->salarioTipoRel;
    }

    /**
     * Set clasificacionRiesgoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo $clasificacionRiesgoRel
     *
     * @return RhuContrato
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
     * @return RhuContrato
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
     * Set tipoPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoPension $tipoPensionRel
     *
     * @return RhuContrato
     */
    public function setTipoPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuTipoPension $tipoPensionRel = null)
    {
        $this->tipoPensionRel = $tipoPensionRel;

        return $this;
    }

    /**
     * Get tipoPensionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuTipoPension
     */
    public function getTipoPensionRel()
    {
        return $this->tipoPensionRel;
    }

    /**
     * Set tipoSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoSalud $tipoSaludRel
     *
     * @return RhuContrato
     */
    public function setTipoSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuTipoSalud $tipoSaludRel = null)
    {
        $this->tipoSaludRel = $tipoSaludRel;

        return $this;
    }

    /**
     * Get tipoSaludRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuTipoSalud
     */
    public function getTipoSaludRel()
    {
        return $this->tipoSaludRel;
    }

    /**
     * Set ssoTipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoTipoCotizante $ssoTipoCotizanteRel
     *
     * @return RhuContrato
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
     * @return RhuContrato
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
     * Set terminacionContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuMotivoTerminacionContrato $terminacionContratoRel
     *
     * @return RhuContrato
     */
    public function setTerminacionContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuMotivoTerminacionContrato $terminacionContratoRel = null)
    {
        $this->terminacionContratoRel = $terminacionContratoRel;

        return $this;
    }

    /**
     * Get terminacionContratoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuMotivoTerminacionContrato
     */
    public function getTerminacionContratoRel()
    {
        return $this->terminacionContratoRel;
    }

    /**
     * Set entidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludRel
     *
     * @return RhuContrato
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
     * @return RhuContrato
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
     * Set entidadCesantiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCesantia $entidadCesantiaRel
     *
     * @return RhuContrato
     */
    public function setEntidadCesantiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadCesantia $entidadCesantiaRel = null)
    {
        $this->entidadCesantiaRel = $entidadCesantiaRel;

        return $this;
    }

    /**
     * Get entidadCesantiaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCesantia
     */
    public function getEntidadCesantiaRel()
    {
        return $this->entidadCesantiaRel;
    }

    /**
     * Set entidadCajaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja $entidadCajaRel
     *
     * @return RhuContrato
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
     * Set ciudadContratoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadContratoRel
     *
     * @return RhuContrato
     */
    public function setCiudadContratoRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadContratoRel = null)
    {
        $this->ciudadContratoRel = $ciudadContratoRel;

        return $this;
    }

    /**
     * Get ciudadContratoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadContratoRel()
    {
        return $this->ciudadContratoRel;
    }

    /**
     * Set ciudadLaboraRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadLaboraRel
     *
     * @return RhuContrato
     */
    public function setCiudadLaboraRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadLaboraRel = null)
    {
        $this->ciudadLaboraRel = $ciudadLaboraRel;

        return $this;
    }

    /**
     * Get ciudadLaboraRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadLaboraRel()
    {
        return $this->ciudadLaboraRel;
    }

    /**
     * Add liquidacionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesContratoRel
     *
     * @return RhuContrato
     */
    public function addLiquidacionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesContratoRel)
    {
        $this->liquidacionesContratoRel[] = $liquidacionesContratoRel;

        return $this;
    }

    /**
     * Remove liquidacionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesContratoRel
     */
    public function removeLiquidacionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesContratoRel)
    {
        $this->liquidacionesContratoRel->removeElement($liquidacionesContratoRel);
    }

    /**
     * Get liquidacionesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLiquidacionesContratoRel()
    {
        return $this->liquidacionesContratoRel;
    }

    /**
     * Add programacionesPagosDetallesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesContratoRel
     *
     * @return RhuContrato
     */
    public function addProgramacionesPagosDetallesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesContratoRel)
    {
        $this->programacionesPagosDetallesContratoRel[] = $programacionesPagosDetallesContratoRel;

        return $this;
    }

    /**
     * Remove programacionesPagosDetallesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesContratoRel
     */
    public function removeProgramacionesPagosDetallesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesContratoRel)
    {
        $this->programacionesPagosDetallesContratoRel->removeElement($programacionesPagosDetallesContratoRel);
    }

    /**
     * Get programacionesPagosDetallesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesPagosDetallesContratoRel()
    {
        return $this->programacionesPagosDetallesContratoRel;
    }

    /**
     * Add pagosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosContratoRel
     *
     * @return RhuContrato
     */
    public function addPagosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosContratoRel)
    {
        $this->pagosContratoRel[] = $pagosContratoRel;

        return $this;
    }

    /**
     * Remove pagosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosContratoRel
     */
    public function removePagosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosContratoRel)
    {
        $this->pagosContratoRel->removeElement($pagosContratoRel);
    }

    /**
     * Get pagosContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosContratoRel()
    {
        return $this->pagosContratoRel;
    }

    /**
     * Add provisionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProvision $provisionesContratoRel
     *
     * @return RhuContrato
     */
    public function addProvisionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProvision $provisionesContratoRel)
    {
        $this->provisionesContratoRel[] = $provisionesContratoRel;

        return $this;
    }

    /**
     * Remove provisionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProvision $provisionesContratoRel
     */
    public function removeProvisionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProvision $provisionesContratoRel)
    {
        $this->provisionesContratoRel->removeElement($provisionesContratoRel);
    }

    /**
     * Get provisionesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProvisionesContratoRel()
    {
        return $this->provisionesContratoRel;
    }

    /**
     * Add ssoPeriodosEmpleadosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosContratoRel
     *
     * @return RhuContrato
     */
    public function addSsoPeriodosEmpleadosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosContratoRel)
    {
        $this->ssoPeriodosEmpleadosContratoRel[] = $ssoPeriodosEmpleadosContratoRel;

        return $this;
    }

    /**
     * Remove ssoPeriodosEmpleadosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosContratoRel
     */
    public function removeSsoPeriodosEmpleadosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosContratoRel)
    {
        $this->ssoPeriodosEmpleadosContratoRel->removeElement($ssoPeriodosEmpleadosContratoRel);
    }

    /**
     * Get ssoPeriodosEmpleadosContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoPeriodosEmpleadosContratoRel()
    {
        return $this->ssoPeriodosEmpleadosContratoRel;
    }

    /**
     * Add ssoAportesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesContratoRel
     *
     * @return RhuContrato
     */
    public function addSsoAportesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesContratoRel)
    {
        $this->ssoAportesContratoRel[] = $ssoAportesContratoRel;

        return $this;
    }

    /**
     * Remove ssoAportesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesContratoRel
     */
    public function removeSsoAportesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesContratoRel)
    {
        $this->ssoAportesContratoRel->removeElement($ssoAportesContratoRel);
    }

    /**
     * Get ssoAportesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoAportesContratoRel()
    {
        return $this->ssoAportesContratoRel;
    }

    /**
     * Add cambiosSalariosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario $cambiosSalariosContratoRel
     *
     * @return RhuContrato
     */
    public function addCambiosSalariosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario $cambiosSalariosContratoRel)
    {
        $this->cambiosSalariosContratoRel[] = $cambiosSalariosContratoRel;

        return $this;
    }

    /**
     * Remove cambiosSalariosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario $cambiosSalariosContratoRel
     */
    public function removeCambiosSalariosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario $cambiosSalariosContratoRel)
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

    /**
     * Add vacacionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesContratoRel
     *
     * @return RhuContrato
     */
    public function addVacacionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesContratoRel)
    {
        $this->vacacionesContratoRel[] = $vacacionesContratoRel;

        return $this;
    }

    /**
     * Remove vacacionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesContratoRel
     */
    public function removeVacacionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesContratoRel)
    {
        $this->vacacionesContratoRel->removeElement($vacacionesContratoRel);
    }

    /**
     * Get vacacionesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVacacionesContratoRel()
    {
        return $this->vacacionesContratoRel;
    }

    /**
     * Add licenciasContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasContratoRel
     *
     * @return RhuContrato
     */
    public function addLicenciasContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasContratoRel)
    {
        $this->licenciasContratoRel[] = $licenciasContratoRel;

        return $this;
    }

    /**
     * Remove licenciasContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasContratoRel
     */
    public function removeLicenciasContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasContratoRel)
    {
        $this->licenciasContratoRel->removeElement($licenciasContratoRel);
    }

    /**
     * Get licenciasContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLicenciasContratoRel()
    {
        return $this->licenciasContratoRel;
    }

    /**
     * Add incapacidadesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesContratoRel
     *
     * @return RhuContrato
     */
    public function addIncapacidadesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesContratoRel)
    {
        $this->incapacidadesContratoRel[] = $incapacidadesContratoRel;

        return $this;
    }

    /**
     * Remove incapacidadesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesContratoRel
     */
    public function removeIncapacidadesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesContratoRel)
    {
        $this->incapacidadesContratoRel->removeElement($incapacidadesContratoRel);
    }

    /**
     * Get incapacidadesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesContratoRel()
    {
        return $this->incapacidadesContratoRel;
    }

    /**
     * Add ingresosBasesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase $ingresosBasesContratoRel
     *
     * @return RhuContrato
     */
    public function addIngresosBasesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase $ingresosBasesContratoRel)
    {
        $this->ingresosBasesContratoRel[] = $ingresosBasesContratoRel;

        return $this;
    }

    /**
     * Remove ingresosBasesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase $ingresosBasesContratoRel
     */
    public function removeIngresosBasesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase $ingresosBasesContratoRel)
    {
        $this->ingresosBasesContratoRel->removeElement($ingresosBasesContratoRel);
    }

    /**
     * Get ingresosBasesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIngresosBasesContratoRel()
    {
        return $this->ingresosBasesContratoRel;
    }

    /**
     * Add contratosSedesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoSede $contratosSedesContratoRel
     *
     * @return RhuContrato
     */
    public function addContratosSedesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoSede $contratosSedesContratoRel)
    {
        $this->contratosSedesContratoRel[] = $contratosSedesContratoRel;

        return $this;
    }

    /**
     * Remove contratosSedesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoSede $contratosSedesContratoRel
     */
    public function removeContratosSedesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoSede $contratosSedesContratoRel)
    {
        $this->contratosSedesContratoRel->removeElement($contratosSedesContratoRel);
    }

    /**
     * Get contratosSedesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosSedesContratoRel()
    {
        return $this->contratosSedesContratoRel;
    }

    /**
     * Add proyeccionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProyeccion $proyeccionesContratoRel
     *
     * @return RhuContrato
     */
    public function addProyeccionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProyeccion $proyeccionesContratoRel)
    {
        $this->proyeccionesContratoRel[] = $proyeccionesContratoRel;

        return $this;
    }

    /**
     * Remove proyeccionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProyeccion $proyeccionesContratoRel
     */
    public function removeProyeccionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProyeccion $proyeccionesContratoRel)
    {
        $this->proyeccionesContratoRel->removeElement($proyeccionesContratoRel);
    }

    /**
     * Get proyeccionesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProyeccionesContratoRel()
    {
        return $this->proyeccionesContratoRel;
    }

    /**
     * Add trasladosPensionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesContratoRel
     *
     * @return RhuContrato
     */
    public function addTrasladosPensionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesContratoRel)
    {
        $this->trasladosPensionesContratoRel[] = $trasladosPensionesContratoRel;

        return $this;
    }

    /**
     * Remove trasladosPensionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesContratoRel
     */
    public function removeTrasladosPensionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesContratoRel)
    {
        $this->trasladosPensionesContratoRel->removeElement($trasladosPensionesContratoRel);
    }

    /**
     * Get trasladosPensionesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrasladosPensionesContratoRel()
    {
        return $this->trasladosPensionesContratoRel;
    }

    /**
     * Add trasladosSaludContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludContratoRel
     *
     * @return RhuContrato
     */
    public function addTrasladosSaludContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludContratoRel)
    {
        $this->trasladosSaludContratoRel[] = $trasladosSaludContratoRel;

        return $this;
    }

    /**
     * Remove trasladosSaludContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludContratoRel
     */
    public function removeTrasladosSaludContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludContratoRel)
    {
        $this->trasladosSaludContratoRel->removeElement($trasladosSaludContratoRel);
    }

    /**
     * Get trasladosSaludContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrasladosSaludContratoRel()
    {
        return $this->trasladosSaludContratoRel;
    }

    /**
     * Add contratosProrrogasContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoProrroga $contratosProrrogasContratoRel
     *
     * @return RhuContrato
     */
    public function addContratosProrrogasContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoProrroga $contratosProrrogasContratoRel)
    {
        $this->contratosProrrogasContratoRel[] = $contratosProrrogasContratoRel;

        return $this;
    }

    /**
     * Remove contratosProrrogasContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoProrroga $contratosProrrogasContratoRel
     */
    public function removeContratosProrrogasContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoProrroga $contratosProrrogasContratoRel)
    {
        $this->contratosProrrogasContratoRel->removeElement($contratosProrrogasContratoRel);
    }

    /**
     * Get contratosProrrogasContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosProrrogasContratoRel()
    {
        return $this->contratosProrrogasContratoRel;
    }

    /**
     * Add soportesPagosHorariosDetallesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle $soportesPagosHorariosDetallesContratoRel
     *
     * @return RhuContrato
     */
    public function addSoportesPagosHorariosDetallesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle $soportesPagosHorariosDetallesContratoRel)
    {
        $this->soportesPagosHorariosDetallesContratoRel[] = $soportesPagosHorariosDetallesContratoRel;

        return $this;
    }

    /**
     * Remove soportesPagosHorariosDetallesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle $soportesPagosHorariosDetallesContratoRel
     */
    public function removeSoportesPagosHorariosDetallesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle $soportesPagosHorariosDetallesContratoRel)
    {
        $this->soportesPagosHorariosDetallesContratoRel->removeElement($soportesPagosHorariosDetallesContratoRel);
    }

    /**
     * Get soportesPagosHorariosDetallesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosHorariosDetallesContratoRel()
    {
        return $this->soportesPagosHorariosDetallesContratoRel;
    }

    /**
     * Add cambiosTiposContratosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosContratoRel
     *
     * @return RhuContrato
     */
    public function addCambiosTiposContratosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosContratoRel)
    {
        $this->cambiosTiposContratosContratoRel[] = $cambiosTiposContratosContratoRel;

        return $this;
    }

    /**
     * Remove cambiosTiposContratosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosContratoRel
     */
    public function removeCambiosTiposContratosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosContratoRel)
    {
        $this->cambiosTiposContratosContratoRel->removeElement($cambiosTiposContratosContratoRel);
    }

    /**
     * Get cambiosTiposContratosContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCambiosTiposContratosContratoRel()
    {
        return $this->cambiosTiposContratosContratoRel;
    }

    /**
     * Add disciplinariosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosContratoRel
     *
     * @return RhuContrato
     */
    public function addDisciplinariosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosContratoRel)
    {
        $this->disciplinariosContratoRel[] = $disciplinariosContratoRel;

        return $this;
    }

    /**
     * Remove disciplinariosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosContratoRel
     */
    public function removeDisciplinariosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosContratoRel)
    {
        $this->disciplinariosContratoRel->removeElement($disciplinariosContratoRel);
    }

    /**
     * Get disciplinariosContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinariosContratoRel()
    {
        return $this->disciplinariosContratoRel;
    }

    /**
     * Set secuencia
     *
     * @param integer $secuencia
     *
     * @return RhuContrato
     */
    public function setSecuencia($secuencia)
    {
        $this->secuencia = $secuencia;

        return $this;
    }

    /**
     * Get secuencia
     *
     * @return integer
     */
    public function getSecuencia()
    {
        return $this->secuencia;
    }

    /**
     * Set codigoContratoGrupoFk
     *
     * @param integer $codigoContratoGrupoFk
     *
     * @return RhuContrato
     */
    public function setCodigoContratoGrupoFk($codigoContratoGrupoFk)
    {
        $this->codigoContratoGrupoFk = $codigoContratoGrupoFk;

        return $this;
    }

    /**
     * Get codigoContratoGrupoFk
     *
     * @return integer
     */
    public function getCodigoContratoGrupoFk()
    {
        return $this->codigoContratoGrupoFk;
    }

    /**
     * Set contratoGrupoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoGrupo $contratoGrupoRel
     *
     * @return RhuContrato
     */
    public function setContratoGrupoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoGrupo $contratoGrupoRel = null)
    {
        $this->contratoGrupoRel = $contratoGrupoRel;

        return $this;
    }

    /**
     * Get contratoGrupoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContratoGrupo
     */
    public function getContratoGrupoRel()
    {
        return $this->contratoGrupoRel;
    }
}
