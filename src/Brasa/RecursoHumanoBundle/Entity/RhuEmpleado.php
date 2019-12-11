<?php

namespace Brasa\RecursoHumanoBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"numeroIdentificacion"},message="Ya existe este número de identificación") 
 */

class RhuEmpleado
{
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
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */    
    private $direccion; 
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadFk;
    
    /**
     * @ORM\Column(name="codigo_ciudad_expedicion_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadExpedicionFk;
    
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
     * @ORM\Column(name="codigo_ciudad_nacimiento_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadNacimientoFk;
    
     /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=1, nullable=true)
     */
    
    private $codigoEstadoCivilFk;
    
    /**
     * @ORM\Column(name="cuenta", type="string", length=80, nullable=true)
     */    
    private $cuenta;
    
    /**
     * @ORM\Column(name="tipo_cuenta", type="string", length=5, nullable=true)
     */    
    private $tipoCuenta;
    
    /**
     * @ORM\Column(name="codigo_banco_fk", type="integer", nullable=true)
     */    
    private $codigoBancoFk;         
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk; 
    
    /**
     * @ORM\Column(name="codigo_centro_costo_contabilidad_fk", type="string", length=20, nullable=true)
     */    
    private $codigoCentroCostoContabilidadFk;    
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk;    
    
    /**
     * @ORM\Column(name="cargo_descripcion", type="string", length=60, nullable=true)
     */    
    private $cargoDescripcion;      
    
    /**
     * @ORM\Column(name="auxilio_transporte", type="boolean")
     */    
    private $auxilioTransporte = false;     
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;
    
    
    /**
     * @ORM\Column(name="fecha_expedicion_identificacion", type="date", nullable=true)
     */ 
    
    private $fechaExpedicionIdentificacion;
    
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
     * @ORM\Column(name="codigo_tipo_pension_fk", type="integer", nullable=true)
     */    
    private $codigoTipoPensionFk;     

    /**
     * @ORM\Column(name="codigo_tipo_salud_fk", type="integer", nullable=true)
     */    
    private $codigoTipoSaludFk;     
    
    /**
     * @ORM\Column(name="codigo_entidad_caja_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadCajaFk;     
    
    /**     
     * @ORM\Column(name="estado_activo", type="boolean")
     */    
    private $estadoActivo = true;
    
    /**     
     * @ORM\Column(name="estado_contrato_activo", type="boolean")
     */    
    private $estadoContratoActivo = false;
    
    /**     
     * @ORM\Column(name="cabeza_hogar", type="boolean")
     */    
    private $cabezaHogar= false;
    
    
    /**     
     * @ORM\Column(name="camisa", type="string", length=10, nullable=true)
     */    
    private $camisa;
    
    /**     
     * @ORM\Column(name="jeans", type="string", length=10, nullable=true)
     */    
    private $jeans;
    
    /**     
     * @ORM\Column(name="calzado", type="string", length=10,  nullable=true)
     */    
    private $calzado;
    
    /**
     * @ORM\Column(name="codigo_clasificacion_riesgo_fk", type="integer", nullable=true)
     */    
    private $codigoClasificacionRiesgoFk;     
    
    /**
     * @ORM\Column(name="fecha_contrato", type="date", nullable=true)
     */    
    private $fechaContrato;   
    
    /**
     * @ORM\Column(name="fecha_finaliza_contrato", type="date", nullable=true)
     */    
    private $fechaFinalizaContrato;    
    
    /**     
     * @ORM\Column(name="contrato_indefinido", type="boolean")
     */    
    private $contratoIndefinido = false;
    
    /**     
     * Empleado pagado por la entidad de salud, exonerado de los pagos
     * @ORM\Column(name="pagado_entidad_salud", type="boolean")
     */    
    private $pagadoEntidadSalud = false;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\Column(name="codigo_tipo_tiempo_fk", type="integer", nullable=true)
     */    
    private $codigoTipoTiempoFk;     
    
    /**
     * @ORM\Column(name="horas_laboradas_periodo", type="float")
     */
    private $horasLaboradasPeriodo = 0;
    
    /**
     * @ORM\Column(name="padre_familia", type="float")
     */
    private $padreFamilia = 0;
    
    /**
     * @ORM\Column(name="codigo_empleado_estudio_tipo_fk", type="integer", length=4, nullable=true)
     */    
    private $codigoEmpleadoEstudioTipoFk;
    
    /**
     * @ORM\Column(name="codigo_tipo_cotizante_fk", type="integer", nullable=true)
     */    
    private $codigoTipoCotizanteFk;    

    /**
     * @ORM\Column(name="codigo_subtipo_cotizante_fk", type="integer", nullable=true)
     */    
    private $codigoSubtipoCotizanteFk;            
    
    /**
     * @ORM\Column(name="codigo_contrato_activo_fk", type="integer", nullable=true)
     */    
    private $codigoContratoActivoFk;     
    
    /**
     * @ORM\Column(name="codigo_contrato_ultimo_fk", type="integer", nullable=true)
     */    
    private $codigoContratoUltimoFk;     
    
    /**
     * @ORM\Column(name="ruta_foto", type="string", length=250, nullable=true)
     */    
    private $rutaFoto;
    
    /**
     * @ORM\Column(name="empleado_informacion_interna", type="boolean", nullable=true)
     */    
    private $empleadoInformacionInterna = false;
    
    /**
     * @ORM\Column(name="codigo_horario_fk", type="integer", nullable=true)
     */    
    private $codigoHorarioFk;
    
    /**
     * @ORM\Column(name="codigo_departamento_empresa_fk", type="integer", nullable=true)
     */    
    private $codigoDepartamentoEmpresaFk;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=30, nullable=true)
     */
    private $codigoInterface; 

    /**
     * @ORM\Column(name="codigo_tipo_libreta", type="integer", nullable=true)
     */    
    private $codigoTipoLibreta;
    
    /**
     * @ORM\Column(name="discapacidad", type="boolean")
     */    
    private $discapacidad = false;

    /**
     * @ORM\Column(name="codigo_zona_fk", type="integer", nullable=true)
     */    
    private $codigoZonaFk; 

    /**
     * @ORM\Column(name="codigo_subzona_fk", type="integer", nullable=true)
     */    
    private $codigoSubzonaFk;
    
    /**
     * @ORM\Column(name="codigo_empleado_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoTipoFk;    
    
    /**
     * Este campo es para meter cualquie informacion del empleado
     * @ORM\Column(name="dato", type="string", length=30, nullable=true)
     */    
    private $dato;
    
    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=2, nullable=true)
     */    
    private $digitoVerificacion;
    
    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuClasificacionRiesgo", inversedBy="empleadosClasificacionRiesgoRel")
     * @ORM\JoinColumn(name="codigo_clasificacion_riesgo_fk", referencedColumnName="codigo_clasificacion_riesgo_pk")
     */
    protected $clasificacionRiesgoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleadoEstudioTipo", inversedBy="empleadosEmpleadoEstudioTipoRel")
     * @ORM\JoinColumn(name="codigo_empleado_estudio_tipo_fk", referencedColumnName="codigo_empleado_estudio_tipo_pk")
     */
    protected $empleadoEstudioTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTipoIdentificacion", inversedBy="rhuEmpleadosTipoIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_tipo_identificacion_fk", referencedColumnName="codigo_tipo_identificacion_pk")
     */
    protected $tipoIdentificacionRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEstadoCivil", inversedBy="empleadosEstadoCivilRel")
     * @ORM\JoinColumn(name="codigo_estado_civil_fk", referencedColumnName="codigo_estado_civil_pk")
     */
    protected $estadoCivilRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="empleadosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;                              
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\ContabilidadBundle\Entity\CtbCentroCosto", inversedBy="rhuEmpleadosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_contabilidad_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoContabilidadRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuBanco", inversedBy="empleadosBancoRel")
     * @ORM\JoinColumn(name="codigo_banco_fk", referencedColumnName="codigo_banco_pk")
     */
    protected $bancoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadSalud", inversedBy="empleadosEntidadSaludRel")
     * @ORM\JoinColumn(name="codigo_entidad_salud_fk", referencedColumnName="codigo_entidad_salud_pk")
     */
    protected $entidadSaludRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadPension", inversedBy="empleadosEntidadPensionRel")
     * @ORM\JoinColumn(name="codigo_entidad_pension_fk", referencedColumnName="codigo_entidad_pension_pk")
     */
    protected $entidadPensionRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadCesantia", inversedBy="empleadosEntidadCesantiaRel")
     * @ORM\JoinColumn(name="codigo_entidad_cesantia_fk", referencedColumnName="codigo_entidad_cesantia_pk")
     */
    protected $entidadCesantiaRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadCaja", inversedBy="empleadosEntidadCajaRel")
     * @ORM\JoinColumn(name="codigo_entidad_caja_fk", referencedColumnName="codigo_entidad_caja_pk")
     */
    protected $entidadCajaRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoTiempo", inversedBy="empleadosTipoTiempoRel")
     * @ORM\JoinColumn(name="codigo_tipo_tiempo_fk", referencedColumnName="codigo_tipo_tiempo_pk")
     */
    protected $tipoTiempoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuEmpleadosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuEmpleadosCiudadNacimientoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_nacimiento_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadNacimientoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuEmpleadosCiudadExpedicionRel")
     * @ORM\JoinColumn(name="codigo_ciudad_expedicion_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadExpedicionRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="empleadosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoPension", inversedBy="empleadosTipoPensionRel")
     * @ORM\JoinColumn(name="codigo_tipo_pension_fk", referencedColumnName="codigo_tipo_pension_pk")
     */
    protected $tipoPensionRel;         

    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoSalud", inversedBy="empleadosTipoSaludRel")
     * @ORM\JoinColumn(name="codigo_tipo_salud_fk", referencedColumnName="codigo_tipo_salud_pk")
     */
    protected $tipoSaludRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoTipoCotizante", inversedBy="empleadosSsoTipoCotizanteRel")
     * @ORM\JoinColumn(name="codigo_tipo_cotizante_fk", referencedColumnName="codigo_tipo_cotizante_pk")
     */
    protected $ssoTipoCotizanteRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoSubtipoCotizante", inversedBy="empleadosSsoSubtipoCotizanteRel")
     * @ORM\JoinColumn(name="codigo_subtipo_cotizante_fk", referencedColumnName="codigo_subtipo_cotizante_pk")
     */
    protected $ssoSubtipoCotizanteRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuZona", inversedBy="empleadosZonaRel")
     * @ORM\JoinColumn(name="codigo_zona_fk", referencedColumnName="codigo_zona_pk")
     */
    protected $zonaRel; 

    /**
     * @ORM\ManyToOne(targetEntity="RhuSubzona", inversedBy="empleadosSubzonaRel")
     * @ORM\JoinColumn(name="codigo_subzona_fk", referencedColumnName="codigo_subzona_pk")
     */
    protected $subzonaRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleadoTipo", inversedBy="empleadosEmpleadoTipoRel")
     * @ORM\JoinColumn(name="codigo_empleado_tipo_fk", referencedColumnName="codigo_empleado_tipo_pk")
     */
    protected $empleadoTipoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="empleadoRel")
     */
    protected $pagosEmpleadoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuProvision", mappedBy="empleadoRel")
     */
    protected $provisionesEmpleadoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuServicioCobrar", mappedBy="empleadoRel")
     */
    protected $serviciosCobrarEmpleadoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoAdicional", mappedBy="empleadoRel")
     */
    protected $pagosAdicionalesEmpleadoRel;      
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCredito", mappedBy="empleadoRel")
     */
    protected $creditosEmpleadoRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="empleadoRel")
     */
    protected $incapacidadesEmpleadoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuEmbargo", mappedBy="empleadoRel")
     */
    protected $embargosEmpleadoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuVacacion", mappedBy="empleadoRel")
     */
    protected $vacacionesEmpleadoRel;           
    
    /**
     * @ORM\OneToMany(targetEntity="RhuLicencia", mappedBy="empleadoRel")
     */
    protected $licenciasEmpleadoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="empleadoRel")
     */
    protected $contratosEmpleadoRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionPagoDetalle", mappedBy="empleadoRel")
     */
    protected $programacionesPagosDetallesEmpleadoRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacion", mappedBy="empleadoRel")
     */
    protected $liquidacionesEmpleadoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuDisciplinario", mappedBy="empleadoRel")
     */
    protected $disciplinariosEmpleadoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDisciplinarioDescargo", mappedBy="empleadoRel")
     */
    protected $disciplinariosDescargosEmpleadoRel;
    
    
     /**
     * @ORM\ManyToOne(targetEntity="RhuRh", inversedBy="empleadosRhRel")
     * @ORM\JoinColumn(name="codigo_rh_fk", referencedColumnName="codigo_rh_pk")
     */
    protected $rhRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuHorario", inversedBy="empleadosHorarioRel")
     * @ORM\JoinColumn(name="codigo_horario_fk", referencedColumnName="codigo_horario_pk")
     */
    protected $horarioRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuDepartamentoEmpresa", inversedBy="empleadosDepartamentoEmpresaRel")
     * @ORM\JoinColumn(name="codigo_departamento_empresa_fk", referencedColumnName="codigo_departamento_empresa_pk")
     */
    protected $departamentoEmpresaRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoFamilia", mappedBy="empleadoRel")
     */
    protected $empleadosFamiliasEmpleadoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoEstudio", mappedBy="empleadoRel")
     */
    protected $empleadosEstudiosEmpleadoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuAcreditacion", mappedBy="empleadoRel")
     */
    protected $acreditacionesEmpleadoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSsoPeriodoEmpleado", mappedBy="empleadoRel")
     */
    protected $ssoPeriodosEmpleadosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuSsoAporte", mappedBy="empleadoRel")
     */
    protected $ssoAportesEmpleadoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDotacion", mappedBy="empleadoRel")
     */
    protected $dotacionesEmpleadoRel;
    
     /**
     * @ORM\OneToMany(targetEntity="RhuAccidenteTrabajo", mappedBy="empleadoRel")
     */
    protected $accidentesTrabajoEmpleadoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuFacturaDetalle", mappedBy="empleadoRel")
     */
    protected $facturasDetallesEmpleadoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCambioSalario", mappedBy="empleadoRel")
     */
    protected $cambiosSalariosEmpleadoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIngresoBase", mappedBy="empleadoRel")
     */
    protected $ingresosBasesEmpleadoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuProyeccion", mappedBy="empleadoRel")
     */
    protected $proyeccionesEmpleadoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuTrasladoPension", mappedBy="empleadoRel")
     */
    protected $trasladosPensionesEmpleadoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuTrasladoSalud", mappedBy="empleadoRel")
     */
    protected $trasladosSaludEmpleadoRel;
    
     /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoInformacionInterna", mappedBy="empleadoRel")
     */
    protected $empleadosInformacionesInternasEmpleadoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDesempeno", mappedBy="empleadoRel")
     */
    protected $desempenosEmpleadoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamen", mappedBy="empleadoRel")
     */
    protected $examenesEmpleadoRel;    
       
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurRecurso", mappedBy="empleadoRel")
     */
    protected $turRecursosEmpleadoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuHorarioAcceso", mappedBy="empleadoRel")
     */
    protected $horarioAccesoEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuSoportePagoHorarioDetalle", mappedBy="empleadoRel")
     */
    protected $soportesPagosHorariosDetallesEmpleadoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPermiso", mappedBy="empleadoRel")
     */
    protected $permisosEmpleadoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCarta", mappedBy="empleadoRel")
     */
    protected $cartasEmpleadoRel;
       
    /**
     * @ORM\OneToMany(targetEntity="RhuCapacitacionDetalle", mappedBy="empleadoRel")
     */
    protected $capacitacionesDetallesEmpleadoRel;
   
    /**
     * @ORM\OneToMany(targetEntity="RhuCambioTipoContrato", mappedBy="empleadoRel")
     */
    protected $cambiosTiposContratosEmpleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuReclamo", mappedBy="empleadoRel")
     */
    protected $reclamosEmpleadoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\TurnoBundle\Entity\TurPuesto", inversedBy="rhuEmpleadosPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoBancoDetalle", mappedBy="empleadoRel")
     */
    protected $pagosBancosDetallesEmpleadoRel;    
    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuVisita", mappedBy="empleadoRel")
     */
    protected $visitasEmpleadoRel;
    
        
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->provisionesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosCobrarEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosAdicionalesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creditosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incapacidadesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->embargosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->vacacionesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->licenciasEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->programacionesPagosDetallesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->liquidacionesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disciplinariosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disciplinariosDescargosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosFamiliasEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosEstudiosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->acreditacionesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ssoPeriodosEmpleadosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ssoAportesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dotacionesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accidentesTrabajoEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasDetallesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cambiosSalariosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ingresosBasesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->proyeccionesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->trasladosPensionesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->trasladosSaludEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosInformacionesInternasEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->desempenosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->examenesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->turRecursosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->horarioAccesoEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soportesPagosHorariosDetallesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->permisosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cartasEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->capacitacionesDetallesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cambiosTiposContratosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosBancosDetallesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->visitasEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * Set codigoCiudadExpedicionFk
     *
     * @param integer $codigoCiudadExpedicionFk
     *
     * @return RhuEmpleado
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
     * Set barrio
     *
     * @param string $barrio
     *
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * Set codigoEstadoCivilFk
     *
     * @param string $codigoEstadoCivilFk
     *
     * @return RhuEmpleado
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
     * Set cuenta
     *
     * @param string $cuenta
     *
     * @return RhuEmpleado
     */
    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    /**
     * Get cuenta
     *
     * @return string
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Set tipoCuenta
     *
     * @param string $tipoCuenta
     *
     * @return RhuEmpleado
     */
    public function setTipoCuenta($tipoCuenta)
    {
        $this->tipoCuenta = $tipoCuenta;

        return $this;
    }

    /**
     * Get tipoCuenta
     *
     * @return string
     */
    public function getTipoCuenta()
    {
        return $this->tipoCuenta;
    }

    /**
     * Set codigoBancoFk
     *
     * @param integer $codigoBancoFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoBancoFk($codigoBancoFk)
    {
        $this->codigoBancoFk = $codigoBancoFk;

        return $this;
    }

    /**
     * Get codigoBancoFk
     *
     * @return integer
     */
    public function getCodigoBancoFk()
    {
        return $this->codigoBancoFk;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuEmpleado
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
     * Set codigoCentroCostoContabilidadFk
     *
     * @param string $codigoCentroCostoContabilidadFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoCentroCostoContabilidadFk($codigoCentroCostoContabilidadFk)
    {
        $this->codigoCentroCostoContabilidadFk = $codigoCentroCostoContabilidadFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoContabilidadFk
     *
     * @return string
     */
    public function getCodigoCentroCostoContabilidadFk()
    {
        return $this->codigoCentroCostoContabilidadFk;
    }

    /**
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * Set auxilioTransporte
     *
     * @param boolean $auxilioTransporte
     *
     * @return RhuEmpleado
     */
    public function setAuxilioTransporte($auxilioTransporte)
    {
        $this->auxilioTransporte = $auxilioTransporte;

        return $this;
    }

    /**
     * Get auxilioTransporte
     *
     * @return boolean
     */
    public function getAuxilioTransporte()
    {
        return $this->auxilioTransporte;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuEmpleado
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
     * Set fechaExpedicionIdentificacion
     *
     * @param \DateTime $fechaExpedicionIdentificacion
     *
     * @return RhuEmpleado
     */
    public function setFechaExpedicionIdentificacion($fechaExpedicionIdentificacion)
    {
        $this->fechaExpedicionIdentificacion = $fechaExpedicionIdentificacion;

        return $this;
    }

    /**
     * Get fechaExpedicionIdentificacion
     *
     * @return \DateTime
     */
    public function getFechaExpedicionIdentificacion()
    {
        return $this->fechaExpedicionIdentificacion;
    }

    /**
     * Set codigoEntidadSaludFk
     *
     * @param integer $codigoEntidadSaludFk
     *
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * Set codigoTipoPensionFk
     *
     * @param integer $codigoTipoPensionFk
     *
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * Set codigoEntidadCajaFk
     *
     * @param integer $codigoEntidadCajaFk
     *
     * @return RhuEmpleado
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
     * Set estadoActivo
     *
     * @param boolean $estadoActivo
     *
     * @return RhuEmpleado
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
     * Set estadoContratoActivo
     *
     * @param boolean $estadoContratoActivo
     *
     * @return RhuEmpleado
     */
    public function setEstadoContratoActivo($estadoContratoActivo)
    {
        $this->estadoContratoActivo = $estadoContratoActivo;

        return $this;
    }

    /**
     * Get estadoContratoActivo
     *
     * @return boolean
     */
    public function getEstadoContratoActivo()
    {
        return $this->estadoContratoActivo;
    }

    /**
     * Set cabezaHogar
     *
     * @param boolean $cabezaHogar
     *
     * @return RhuEmpleado
     */
    public function setCabezaHogar($cabezaHogar)
    {
        $this->cabezaHogar = $cabezaHogar;

        return $this;
    }

    /**
     * Get cabezaHogar
     *
     * @return boolean
     */
    public function getCabezaHogar()
    {
        return $this->cabezaHogar;
    }

    /**
     * Set camisa
     *
     * @param string $camisa
     *
     * @return RhuEmpleado
     */
    public function setCamisa($camisa)
    {
        $this->camisa = $camisa;

        return $this;
    }

    /**
     * Get camisa
     *
     * @return string
     */
    public function getCamisa()
    {
        return $this->camisa;
    }

    /**
     * Set jeans
     *
     * @param string $jeans
     *
     * @return RhuEmpleado
     */
    public function setJeans($jeans)
    {
        $this->jeans = $jeans;

        return $this;
    }

    /**
     * Get jeans
     *
     * @return string
     */
    public function getJeans()
    {
        return $this->jeans;
    }

    /**
     * Set calzado
     *
     * @param string $calzado
     *
     * @return RhuEmpleado
     */
    public function setCalzado($calzado)
    {
        $this->calzado = $calzado;

        return $this;
    }

    /**
     * Get calzado
     *
     * @return string
     */
    public function getCalzado()
    {
        return $this->calzado;
    }

    /**
     * Set codigoClasificacionRiesgoFk
     *
     * @param integer $codigoClasificacionRiesgoFk
     *
     * @return RhuEmpleado
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
     * Set fechaContrato
     *
     * @param \DateTime $fechaContrato
     *
     * @return RhuEmpleado
     */
    public function setFechaContrato($fechaContrato)
    {
        $this->fechaContrato = $fechaContrato;

        return $this;
    }

    /**
     * Get fechaContrato
     *
     * @return \DateTime
     */
    public function getFechaContrato()
    {
        return $this->fechaContrato;
    }

    /**
     * Set fechaFinalizaContrato
     *
     * @param \DateTime $fechaFinalizaContrato
     *
     * @return RhuEmpleado
     */
    public function setFechaFinalizaContrato($fechaFinalizaContrato)
    {
        $this->fechaFinalizaContrato = $fechaFinalizaContrato;

        return $this;
    }

    /**
     * Get fechaFinalizaContrato
     *
     * @return \DateTime
     */
    public function getFechaFinalizaContrato()
    {
        return $this->fechaFinalizaContrato;
    }

    /**
     * Set contratoIndefinido
     *
     * @param boolean $contratoIndefinido
     *
     * @return RhuEmpleado
     */
    public function setContratoIndefinido($contratoIndefinido)
    {
        $this->contratoIndefinido = $contratoIndefinido;

        return $this;
    }

    /**
     * Get contratoIndefinido
     *
     * @return boolean
     */
    public function getContratoIndefinido()
    {
        return $this->contratoIndefinido;
    }

    /**
     * Set pagadoEntidadSalud
     *
     * @param boolean $pagadoEntidadSalud
     *
     * @return RhuEmpleado
     */
    public function setPagadoEntidadSalud($pagadoEntidadSalud)
    {
        $this->pagadoEntidadSalud = $pagadoEntidadSalud;

        return $this;
    }

    /**
     * Get pagadoEntidadSalud
     *
     * @return boolean
     */
    public function getPagadoEntidadSalud()
    {
        return $this->pagadoEntidadSalud;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuEmpleado
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
     * Set codigoTipoTiempoFk
     *
     * @param integer $codigoTipoTiempoFk
     *
     * @return RhuEmpleado
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
     * Set horasLaboradasPeriodo
     *
     * @param float $horasLaboradasPeriodo
     *
     * @return RhuEmpleado
     */
    public function setHorasLaboradasPeriodo($horasLaboradasPeriodo)
    {
        $this->horasLaboradasPeriodo = $horasLaboradasPeriodo;

        return $this;
    }

    /**
     * Get horasLaboradasPeriodo
     *
     * @return float
     */
    public function getHorasLaboradasPeriodo()
    {
        return $this->horasLaboradasPeriodo;
    }

    /**
     * Set padreFamilia
     *
     * @param float $padreFamilia
     *
     * @return RhuEmpleado
     */
    public function setPadreFamilia($padreFamilia)
    {
        $this->padreFamilia = $padreFamilia;

        return $this;
    }

    /**
     * Get padreFamilia
     *
     * @return float
     */
    public function getPadreFamilia()
    {
        return $this->padreFamilia;
    }

    /**
     * Set codigoEmpleadoEstudioTipoFk
     *
     * @param integer $codigoEmpleadoEstudioTipoFk
     *
     * @return RhuEmpleado
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
     * Set codigoTipoCotizanteFk
     *
     * @param integer $codigoTipoCotizanteFk
     *
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * Set codigoContratoActivoFk
     *
     * @param integer $codigoContratoActivoFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoContratoActivoFk($codigoContratoActivoFk)
    {
        $this->codigoContratoActivoFk = $codigoContratoActivoFk;

        return $this;
    }

    /**
     * Get codigoContratoActivoFk
     *
     * @return integer
     */
    public function getCodigoContratoActivoFk()
    {
        return $this->codigoContratoActivoFk;
    }

    /**
     * Set codigoContratoUltimoFk
     *
     * @param integer $codigoContratoUltimoFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoContratoUltimoFk($codigoContratoUltimoFk)
    {
        $this->codigoContratoUltimoFk = $codigoContratoUltimoFk;

        return $this;
    }

    /**
     * Get codigoContratoUltimoFk
     *
     * @return integer
     */
    public function getCodigoContratoUltimoFk()
    {
        return $this->codigoContratoUltimoFk;
    }

    /**
     * Set rutaFoto
     *
     * @param string $rutaFoto
     *
     * @return RhuEmpleado
     */
    public function setRutaFoto($rutaFoto)
    {
        $this->rutaFoto = $rutaFoto;

        return $this;
    }

    /**
     * Get rutaFoto
     *
     * @return string
     */
    public function getRutaFoto()
    {
        return $this->rutaFoto;
    }

    /**
     * Set empleadoInformacionInterna
     *
     * @param boolean $empleadoInformacionInterna
     *
     * @return RhuEmpleado
     */
    public function setEmpleadoInformacionInterna($empleadoInformacionInterna)
    {
        $this->empleadoInformacionInterna = $empleadoInformacionInterna;

        return $this;
    }

    /**
     * Get empleadoInformacionInterna
     *
     * @return boolean
     */
    public function getEmpleadoInformacionInterna()
    {
        return $this->empleadoInformacionInterna;
    }

    /**
     * Set codigoHorarioFk
     *
     * @param integer $codigoHorarioFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoHorarioFk($codigoHorarioFk)
    {
        $this->codigoHorarioFk = $codigoHorarioFk;

        return $this;
    }

    /**
     * Get codigoHorarioFk
     *
     * @return integer
     */
    public function getCodigoHorarioFk()
    {
        return $this->codigoHorarioFk;
    }

    /**
     * Set codigoDepartamentoEmpresaFk
     *
     * @param integer $codigoDepartamentoEmpresaFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoDepartamentoEmpresaFk($codigoDepartamentoEmpresaFk)
    {
        $this->codigoDepartamentoEmpresaFk = $codigoDepartamentoEmpresaFk;

        return $this;
    }

    /**
     * Get codigoDepartamentoEmpresaFk
     *
     * @return integer
     */
    public function getCodigoDepartamentoEmpresaFk()
    {
        return $this->codigoDepartamentoEmpresaFk;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuEmpleado
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
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return RhuEmpleado
     */
    public function setCodigoInterface($codigoInterface)
    {
        $this->codigoInterface = $codigoInterface;

        return $this;
    }

    /**
     * Get codigoInterface
     *
     * @return string
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * Set codigoTipoLibreta
     *
     * @param integer $codigoTipoLibreta
     *
     * @return RhuEmpleado
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
     * Set discapacidad
     *
     * @param boolean $discapacidad
     *
     * @return RhuEmpleado
     */
    public function setDiscapacidad($discapacidad)
    {
        $this->discapacidad = $discapacidad;

        return $this;
    }

    /**
     * Get discapacidad
     *
     * @return boolean
     */
    public function getDiscapacidad()
    {
        return $this->discapacidad;
    }

    /**
     * Set codigoZonaFk
     *
     * @param integer $codigoZonaFk
     *
     * @return RhuEmpleado
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
     * Set codigoSubzonaFk
     *
     * @param integer $codigoSubzonaFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoSubzonaFk($codigoSubzonaFk)
    {
        $this->codigoSubzonaFk = $codigoSubzonaFk;

        return $this;
    }

    /**
     * Get codigoSubzonaFk
     *
     * @return integer
     */
    public function getCodigoSubzonaFk()
    {
        return $this->codigoSubzonaFk;
    }

    /**
     * Set codigoEmpleadoTipoFk
     *
     * @param integer $codigoEmpleadoTipoFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoEmpleadoTipoFk($codigoEmpleadoTipoFk)
    {
        $this->codigoEmpleadoTipoFk = $codigoEmpleadoTipoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoTipoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoTipoFk()
    {
        return $this->codigoEmpleadoTipoFk;
    }

    /**
     * Set dato
     *
     * @param string $dato
     *
     * @return RhuEmpleado
     */
    public function setDato($dato)
    {
        $this->dato = $dato;

        return $this;
    }

    /**
     * Get dato
     *
     * @return string
     */
    public function getDato()
    {
        return $this->dato;
    }

    /**
     * Set digitoVerificacion
     *
     * @param string $digitoVerificacion
     *
     * @return RhuEmpleado
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
     * Set codigoPuestoFk
     *
     * @param integer $codigoPuestoFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoPuestoFk($codigoPuestoFk)
    {
        $this->codigoPuestoFk = $codigoPuestoFk;

        return $this;
    }

    /**
     * Get codigoPuestoFk
     *
     * @return integer
     */
    public function getCodigoPuestoFk()
    {
        return $this->codigoPuestoFk;
    }

    /**
     * Set clasificacionRiesgoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo $clasificacionRiesgoRel
     *
     * @return RhuEmpleado
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
     * Set empleadoEstudioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo $empleadoEstudioTipoRel
     *
     * @return RhuEmpleado
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
     * Set tipoIdentificacionRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTipoIdentificacion $tipoIdentificacionRel
     *
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * Set centroCostoContabilidadRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostoContabilidadRel
     *
     * @return RhuEmpleado
     */
    public function setCentroCostoContabilidadRel(\Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostoContabilidadRel = null)
    {
        $this->centroCostoContabilidadRel = $centroCostoContabilidadRel;

        return $this;
    }

    /**
     * Get centroCostoContabilidadRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbCentroCosto
     */
    public function getCentroCostoContabilidadRel()
    {
        return $this->centroCostoContabilidadRel;
    }

    /**
     * Set bancoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuBanco $bancoRel
     *
     * @return RhuEmpleado
     */
    public function setBancoRel(\Brasa\RecursoHumanoBundle\Entity\RhuBanco $bancoRel = null)
    {
        $this->bancoRel = $bancoRel;

        return $this;
    }

    /**
     * Get bancoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuBanco
     */
    public function getBancoRel()
    {
        return $this->bancoRel;
    }

    /**
     * Set entidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludRel
     *
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * Set tipoTiempoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoTiempo $tipoTiempoRel
     *
     * @return RhuEmpleado
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
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * @return RhuEmpleado
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
     * Set zonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuZona $zonaRel
     *
     * @return RhuEmpleado
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
     * Set subzonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSubzona $subzonaRel
     *
     * @return RhuEmpleado
     */
    public function setSubzonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuSubzona $subzonaRel = null)
    {
        $this->subzonaRel = $subzonaRel;

        return $this;
    }

    /**
     * Get subzonaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSubzona
     */
    public function getSubzonaRel()
    {
        return $this->subzonaRel;
    }

    /**
     * Set empleadoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoTipo $empleadoTipoRel
     *
     * @return RhuEmpleado
     */
    public function setEmpleadoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoTipo $empleadoTipoRel = null)
    {
        $this->empleadoTipoRel = $empleadoTipoRel;

        return $this;
    }

    /**
     * Get empleadoTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoTipo
     */
    public function getEmpleadoTipoRel()
    {
        return $this->empleadoTipoRel;
    }

    /**
     * Add pagosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addPagosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosEmpleadoRel)
    {
        $this->pagosEmpleadoRel[] = $pagosEmpleadoRel;

        return $this;
    }

    /**
     * Remove pagosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosEmpleadoRel
     */
    public function removePagosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosEmpleadoRel)
    {
        $this->pagosEmpleadoRel->removeElement($pagosEmpleadoRel);
    }

    /**
     * Get pagosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosEmpleadoRel()
    {
        return $this->pagosEmpleadoRel;
    }

    /**
     * Add provisionesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProvision $provisionesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addProvisionesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProvision $provisionesEmpleadoRel)
    {
        $this->provisionesEmpleadoRel[] = $provisionesEmpleadoRel;

        return $this;
    }

    /**
     * Remove provisionesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProvision $provisionesEmpleadoRel
     */
    public function removeProvisionesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProvision $provisionesEmpleadoRel)
    {
        $this->provisionesEmpleadoRel->removeElement($provisionesEmpleadoRel);
    }

    /**
     * Get provisionesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProvisionesEmpleadoRel()
    {
        return $this->provisionesEmpleadoRel;
    }

    /**
     * Add serviciosCobrarEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addServiciosCobrarEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarEmpleadoRel)
    {
        $this->serviciosCobrarEmpleadoRel[] = $serviciosCobrarEmpleadoRel;

        return $this;
    }

    /**
     * Remove serviciosCobrarEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarEmpleadoRel
     */
    public function removeServiciosCobrarEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarEmpleadoRel)
    {
        $this->serviciosCobrarEmpleadoRel->removeElement($serviciosCobrarEmpleadoRel);
    }

    /**
     * Get serviciosCobrarEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosCobrarEmpleadoRel()
    {
        return $this->serviciosCobrarEmpleadoRel;
    }

    /**
     * Add pagosAdicionalesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addPagosAdicionalesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesEmpleadoRel)
    {
        $this->pagosAdicionalesEmpleadoRel[] = $pagosAdicionalesEmpleadoRel;

        return $this;
    }

    /**
     * Remove pagosAdicionalesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesEmpleadoRel
     */
    public function removePagosAdicionalesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesEmpleadoRel)
    {
        $this->pagosAdicionalesEmpleadoRel->removeElement($pagosAdicionalesEmpleadoRel);
    }

    /**
     * Get pagosAdicionalesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosAdicionalesEmpleadoRel()
    {
        return $this->pagosAdicionalesEmpleadoRel;
    }

    /**
     * Add creditosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addCreditosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosEmpleadoRel)
    {
        $this->creditosEmpleadoRel[] = $creditosEmpleadoRel;

        return $this;
    }

    /**
     * Remove creditosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosEmpleadoRel
     */
    public function removeCreditosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosEmpleadoRel)
    {
        $this->creditosEmpleadoRel->removeElement($creditosEmpleadoRel);
    }

    /**
     * Get creditosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreditosEmpleadoRel()
    {
        return $this->creditosEmpleadoRel;
    }

    /**
     * Add incapacidadesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addIncapacidadesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEmpleadoRel)
    {
        $this->incapacidadesEmpleadoRel[] = $incapacidadesEmpleadoRel;

        return $this;
    }

    /**
     * Remove incapacidadesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEmpleadoRel
     */
    public function removeIncapacidadesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEmpleadoRel)
    {
        $this->incapacidadesEmpleadoRel->removeElement($incapacidadesEmpleadoRel);
    }

    /**
     * Get incapacidadesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesEmpleadoRel()
    {
        return $this->incapacidadesEmpleadoRel;
    }

    /**
     * Add embargosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmbargo $embargosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addEmbargosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmbargo $embargosEmpleadoRel)
    {
        $this->embargosEmpleadoRel[] = $embargosEmpleadoRel;

        return $this;
    }

    /**
     * Remove embargosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmbargo $embargosEmpleadoRel
     */
    public function removeEmbargosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmbargo $embargosEmpleadoRel)
    {
        $this->embargosEmpleadoRel->removeElement($embargosEmpleadoRel);
    }

    /**
     * Get embargosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmbargosEmpleadoRel()
    {
        return $this->embargosEmpleadoRel;
    }

    /**
     * Add vacacionesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addVacacionesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesEmpleadoRel)
    {
        $this->vacacionesEmpleadoRel[] = $vacacionesEmpleadoRel;

        return $this;
    }

    /**
     * Remove vacacionesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesEmpleadoRel
     */
    public function removeVacacionesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesEmpleadoRel)
    {
        $this->vacacionesEmpleadoRel->removeElement($vacacionesEmpleadoRel);
    }

    /**
     * Get vacacionesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVacacionesEmpleadoRel()
    {
        return $this->vacacionesEmpleadoRel;
    }

    /**
     * Add licenciasEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addLicenciasEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasEmpleadoRel)
    {
        $this->licenciasEmpleadoRel[] = $licenciasEmpleadoRel;

        return $this;
    }

    /**
     * Remove licenciasEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasEmpleadoRel
     */
    public function removeLicenciasEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasEmpleadoRel)
    {
        $this->licenciasEmpleadoRel->removeElement($licenciasEmpleadoRel);
    }

    /**
     * Get licenciasEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLicenciasEmpleadoRel()
    {
        return $this->licenciasEmpleadoRel;
    }

    /**
     * Add contratosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addContratosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEmpleadoRel)
    {
        $this->contratosEmpleadoRel[] = $contratosEmpleadoRel;

        return $this;
    }

    /**
     * Remove contratosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEmpleadoRel
     */
    public function removeContratosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEmpleadoRel)
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
     * Add programacionesPagosDetallesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addProgramacionesPagosDetallesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesEmpleadoRel)
    {
        $this->programacionesPagosDetallesEmpleadoRel[] = $programacionesPagosDetallesEmpleadoRel;

        return $this;
    }

    /**
     * Remove programacionesPagosDetallesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesEmpleadoRel
     */
    public function removeProgramacionesPagosDetallesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesEmpleadoRel)
    {
        $this->programacionesPagosDetallesEmpleadoRel->removeElement($programacionesPagosDetallesEmpleadoRel);
    }

    /**
     * Get programacionesPagosDetallesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesPagosDetallesEmpleadoRel()
    {
        return $this->programacionesPagosDetallesEmpleadoRel;
    }

    /**
     * Add liquidacionesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addLiquidacionesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesEmpleadoRel)
    {
        $this->liquidacionesEmpleadoRel[] = $liquidacionesEmpleadoRel;

        return $this;
    }

    /**
     * Remove liquidacionesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesEmpleadoRel
     */
    public function removeLiquidacionesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesEmpleadoRel)
    {
        $this->liquidacionesEmpleadoRel->removeElement($liquidacionesEmpleadoRel);
    }

    /**
     * Get liquidacionesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLiquidacionesEmpleadoRel()
    {
        return $this->liquidacionesEmpleadoRel;
    }

    /**
     * Add disciplinariosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addDisciplinariosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosEmpleadoRel)
    {
        $this->disciplinariosEmpleadoRel[] = $disciplinariosEmpleadoRel;

        return $this;
    }

    /**
     * Remove disciplinariosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosEmpleadoRel
     */
    public function removeDisciplinariosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosEmpleadoRel)
    {
        $this->disciplinariosEmpleadoRel->removeElement($disciplinariosEmpleadoRel);
    }

    /**
     * Get disciplinariosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinariosEmpleadoRel()
    {
        return $this->disciplinariosEmpleadoRel;
    }

    /**
     * Add disciplinariosDescargosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioDescargo $disciplinariosDescargosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addDisciplinariosDescargosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioDescargo $disciplinariosDescargosEmpleadoRel)
    {
        $this->disciplinariosDescargosEmpleadoRel[] = $disciplinariosDescargosEmpleadoRel;

        return $this;
    }

    /**
     * Remove disciplinariosDescargosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioDescargo $disciplinariosDescargosEmpleadoRel
     */
    public function removeDisciplinariosDescargosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioDescargo $disciplinariosDescargosEmpleadoRel)
    {
        $this->disciplinariosDescargosEmpleadoRel->removeElement($disciplinariosDescargosEmpleadoRel);
    }

    /**
     * Get disciplinariosDescargosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinariosDescargosEmpleadoRel()
    {
        return $this->disciplinariosDescargosEmpleadoRel;
    }

    /**
     * Set rhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRh $rhRel
     *
     * @return RhuEmpleado
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
     * Set horarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuHorario $horarioRel
     *
     * @return RhuEmpleado
     */
    public function setHorarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuHorario $horarioRel = null)
    {
        $this->horarioRel = $horarioRel;

        return $this;
    }

    /**
     * Get horarioRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuHorario
     */
    public function getHorarioRel()
    {
        return $this->horarioRel;
    }

    /**
     * Set departamentoEmpresaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa $departamentoEmpresaRel
     *
     * @return RhuEmpleado
     */
    public function setDepartamentoEmpresaRel(\Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa $departamentoEmpresaRel = null)
    {
        $this->departamentoEmpresaRel = $departamentoEmpresaRel;

        return $this;
    }

    /**
     * Get departamentoEmpresaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa
     */
    public function getDepartamentoEmpresaRel()
    {
        return $this->departamentoEmpresaRel;
    }

    /**
     * Add empleadosFamiliasEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addEmpleadosFamiliasEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEmpleadoRel)
    {
        $this->empleadosFamiliasEmpleadoRel[] = $empleadosFamiliasEmpleadoRel;

        return $this;
    }

    /**
     * Remove empleadosFamiliasEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEmpleadoRel
     */
    public function removeEmpleadosFamiliasEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEmpleadoRel)
    {
        $this->empleadosFamiliasEmpleadoRel->removeElement($empleadosFamiliasEmpleadoRel);
    }

    /**
     * Get empleadosFamiliasEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosFamiliasEmpleadoRel()
    {
        return $this->empleadosFamiliasEmpleadoRel;
    }

    /**
     * Add empleadosEstudiosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addEmpleadosEstudiosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEmpleadoRel)
    {
        $this->empleadosEstudiosEmpleadoRel[] = $empleadosEstudiosEmpleadoRel;

        return $this;
    }

    /**
     * Remove empleadosEstudiosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEmpleadoRel
     */
    public function removeEmpleadosEstudiosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEmpleadoRel)
    {
        $this->empleadosEstudiosEmpleadoRel->removeElement($empleadosEstudiosEmpleadoRel);
    }

    /**
     * Get empleadosEstudiosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEstudiosEmpleadoRel()
    {
        return $this->empleadosEstudiosEmpleadoRel;
    }

    /**
     * Add acreditacionesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addAcreditacionesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesEmpleadoRel)
    {
        $this->acreditacionesEmpleadoRel[] = $acreditacionesEmpleadoRel;

        return $this;
    }

    /**
     * Remove acreditacionesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesEmpleadoRel
     */
    public function removeAcreditacionesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesEmpleadoRel)
    {
        $this->acreditacionesEmpleadoRel->removeElement($acreditacionesEmpleadoRel);
    }

    /**
     * Get acreditacionesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAcreditacionesEmpleadoRel()
    {
        return $this->acreditacionesEmpleadoRel;
    }

    /**
     * Add ssoPeriodosEmpleadosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addSsoPeriodosEmpleadosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosEmpleadoRel)
    {
        $this->ssoPeriodosEmpleadosEmpleadoRel[] = $ssoPeriodosEmpleadosEmpleadoRel;

        return $this;
    }

    /**
     * Remove ssoPeriodosEmpleadosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosEmpleadoRel
     */
    public function removeSsoPeriodosEmpleadosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosEmpleadoRel)
    {
        $this->ssoPeriodosEmpleadosEmpleadoRel->removeElement($ssoPeriodosEmpleadosEmpleadoRel);
    }

    /**
     * Get ssoPeriodosEmpleadosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoPeriodosEmpleadosEmpleadoRel()
    {
        return $this->ssoPeriodosEmpleadosEmpleadoRel;
    }

    /**
     * Add ssoAportesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addSsoAportesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEmpleadoRel)
    {
        $this->ssoAportesEmpleadoRel[] = $ssoAportesEmpleadoRel;

        return $this;
    }

    /**
     * Remove ssoAportesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEmpleadoRel
     */
    public function removeSsoAportesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEmpleadoRel)
    {
        $this->ssoAportesEmpleadoRel->removeElement($ssoAportesEmpleadoRel);
    }

    /**
     * Get ssoAportesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoAportesEmpleadoRel()
    {
        return $this->ssoAportesEmpleadoRel;
    }

    /**
     * Add dotacionesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacion $dotacionesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addDotacionesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacion $dotacionesEmpleadoRel)
    {
        $this->dotacionesEmpleadoRel[] = $dotacionesEmpleadoRel;

        return $this;
    }

    /**
     * Remove dotacionesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacion $dotacionesEmpleadoRel
     */
    public function removeDotacionesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacion $dotacionesEmpleadoRel)
    {
        $this->dotacionesEmpleadoRel->removeElement($dotacionesEmpleadoRel);
    }

    /**
     * Get dotacionesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDotacionesEmpleadoRel()
    {
        return $this->dotacionesEmpleadoRel;
    }

    /**
     * Add accidentesTrabajoEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addAccidentesTrabajoEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoEmpleadoRel)
    {
        $this->accidentesTrabajoEmpleadoRel[] = $accidentesTrabajoEmpleadoRel;

        return $this;
    }

    /**
     * Remove accidentesTrabajoEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoEmpleadoRel
     */
    public function removeAccidentesTrabajoEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoEmpleadoRel)
    {
        $this->accidentesTrabajoEmpleadoRel->removeElement($accidentesTrabajoEmpleadoRel);
    }

    /**
     * Get accidentesTrabajoEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccidentesTrabajoEmpleadoRel()
    {
        return $this->accidentesTrabajoEmpleadoRel;
    }

    /**
     * Add facturasDetallesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addFacturasDetallesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesEmpleadoRel)
    {
        $this->facturasDetallesEmpleadoRel[] = $facturasDetallesEmpleadoRel;

        return $this;
    }

    /**
     * Remove facturasDetallesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesEmpleadoRel
     */
    public function removeFacturasDetallesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesEmpleadoRel)
    {
        $this->facturasDetallesEmpleadoRel->removeElement($facturasDetallesEmpleadoRel);
    }

    /**
     * Get facturasDetallesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesEmpleadoRel()
    {
        return $this->facturasDetallesEmpleadoRel;
    }

    /**
     * Add cambiosSalariosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario $cambiosSalariosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addCambiosSalariosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario $cambiosSalariosEmpleadoRel)
    {
        $this->cambiosSalariosEmpleadoRel[] = $cambiosSalariosEmpleadoRel;

        return $this;
    }

    /**
     * Remove cambiosSalariosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario $cambiosSalariosEmpleadoRel
     */
    public function removeCambiosSalariosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario $cambiosSalariosEmpleadoRel)
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

    /**
     * Add ingresosBasesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase $ingresosBasesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addIngresosBasesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase $ingresosBasesEmpleadoRel)
    {
        $this->ingresosBasesEmpleadoRel[] = $ingresosBasesEmpleadoRel;

        return $this;
    }

    /**
     * Remove ingresosBasesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase $ingresosBasesEmpleadoRel
     */
    public function removeIngresosBasesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase $ingresosBasesEmpleadoRel)
    {
        $this->ingresosBasesEmpleadoRel->removeElement($ingresosBasesEmpleadoRel);
    }

    /**
     * Get ingresosBasesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIngresosBasesEmpleadoRel()
    {
        return $this->ingresosBasesEmpleadoRel;
    }

    /**
     * Add proyeccionesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProyeccion $proyeccionesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addProyeccionesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProyeccion $proyeccionesEmpleadoRel)
    {
        $this->proyeccionesEmpleadoRel[] = $proyeccionesEmpleadoRel;

        return $this;
    }

    /**
     * Remove proyeccionesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProyeccion $proyeccionesEmpleadoRel
     */
    public function removeProyeccionesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProyeccion $proyeccionesEmpleadoRel)
    {
        $this->proyeccionesEmpleadoRel->removeElement($proyeccionesEmpleadoRel);
    }

    /**
     * Get proyeccionesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProyeccionesEmpleadoRel()
    {
        return $this->proyeccionesEmpleadoRel;
    }

    /**
     * Add trasladosPensionesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addTrasladosPensionesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesEmpleadoRel)
    {
        $this->trasladosPensionesEmpleadoRel[] = $trasladosPensionesEmpleadoRel;

        return $this;
    }

    /**
     * Remove trasladosPensionesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesEmpleadoRel
     */
    public function removeTrasladosPensionesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesEmpleadoRel)
    {
        $this->trasladosPensionesEmpleadoRel->removeElement($trasladosPensionesEmpleadoRel);
    }

    /**
     * Get trasladosPensionesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrasladosPensionesEmpleadoRel()
    {
        return $this->trasladosPensionesEmpleadoRel;
    }

    /**
     * Add trasladosSaludEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addTrasladosSaludEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludEmpleadoRel)
    {
        $this->trasladosSaludEmpleadoRel[] = $trasladosSaludEmpleadoRel;

        return $this;
    }

    /**
     * Remove trasladosSaludEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludEmpleadoRel
     */
    public function removeTrasladosSaludEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludEmpleadoRel)
    {
        $this->trasladosSaludEmpleadoRel->removeElement($trasladosSaludEmpleadoRel);
    }

    /**
     * Get trasladosSaludEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrasladosSaludEmpleadoRel()
    {
        return $this->trasladosSaludEmpleadoRel;
    }

    /**
     * Add empleadosInformacionesInternasEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInterna $empleadosInformacionesInternasEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addEmpleadosInformacionesInternasEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInterna $empleadosInformacionesInternasEmpleadoRel)
    {
        $this->empleadosInformacionesInternasEmpleadoRel[] = $empleadosInformacionesInternasEmpleadoRel;

        return $this;
    }

    /**
     * Remove empleadosInformacionesInternasEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInterna $empleadosInformacionesInternasEmpleadoRel
     */
    public function removeEmpleadosInformacionesInternasEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInterna $empleadosInformacionesInternasEmpleadoRel)
    {
        $this->empleadosInformacionesInternasEmpleadoRel->removeElement($empleadosInformacionesInternasEmpleadoRel);
    }

    /**
     * Get empleadosInformacionesInternasEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosInformacionesInternasEmpleadoRel()
    {
        return $this->empleadosInformacionesInternasEmpleadoRel;
    }

    /**
     * Add desempenosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno $desempenosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addDesempenosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDesempeno $desempenosEmpleadoRel)
    {
        $this->desempenosEmpleadoRel[] = $desempenosEmpleadoRel;

        return $this;
    }

    /**
     * Remove desempenosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno $desempenosEmpleadoRel
     */
    public function removeDesempenosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDesempeno $desempenosEmpleadoRel)
    {
        $this->desempenosEmpleadoRel->removeElement($desempenosEmpleadoRel);
    }

    /**
     * Get desempenosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDesempenosEmpleadoRel()
    {
        return $this->desempenosEmpleadoRel;
    }

    /**
     * Add examenesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addExamenesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesEmpleadoRel)
    {
        $this->examenesEmpleadoRel[] = $examenesEmpleadoRel;

        return $this;
    }

    /**
     * Remove examenesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesEmpleadoRel
     */
    public function removeExamenesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesEmpleadoRel)
    {
        $this->examenesEmpleadoRel->removeElement($examenesEmpleadoRel);
    }

    /**
     * Get examenesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesEmpleadoRel()
    {
        return $this->examenesEmpleadoRel;
    }

    /**
     * Add turRecursosEmpleadoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $turRecursosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addTurRecursosEmpleadoRel(\Brasa\TurnoBundle\Entity\TurRecurso $turRecursosEmpleadoRel)
    {
        $this->turRecursosEmpleadoRel[] = $turRecursosEmpleadoRel;

        return $this;
    }

    /**
     * Remove turRecursosEmpleadoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $turRecursosEmpleadoRel
     */
    public function removeTurRecursosEmpleadoRel(\Brasa\TurnoBundle\Entity\TurRecurso $turRecursosEmpleadoRel)
    {
        $this->turRecursosEmpleadoRel->removeElement($turRecursosEmpleadoRel);
    }

    /**
     * Get turRecursosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurRecursosEmpleadoRel()
    {
        return $this->turRecursosEmpleadoRel;
    }

    /**
     * Add horarioAccesoEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horarioAccesoEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addHorarioAccesoEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horarioAccesoEmpleadoRel)
    {
        $this->horarioAccesoEmpleadoRel[] = $horarioAccesoEmpleadoRel;

        return $this;
    }

    /**
     * Remove horarioAccesoEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horarioAccesoEmpleadoRel
     */
    public function removeHorarioAccesoEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horarioAccesoEmpleadoRel)
    {
        $this->horarioAccesoEmpleadoRel->removeElement($horarioAccesoEmpleadoRel);
    }

    /**
     * Get horarioAccesoEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHorarioAccesoEmpleadoRel()
    {
        return $this->horarioAccesoEmpleadoRel;
    }

    /**
     * Add soportesPagosHorariosDetallesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle $soportesPagosHorariosDetallesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addSoportesPagosHorariosDetallesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle $soportesPagosHorariosDetallesEmpleadoRel)
    {
        $this->soportesPagosHorariosDetallesEmpleadoRel[] = $soportesPagosHorariosDetallesEmpleadoRel;

        return $this;
    }

    /**
     * Remove soportesPagosHorariosDetallesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle $soportesPagosHorariosDetallesEmpleadoRel
     */
    public function removeSoportesPagosHorariosDetallesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorarioDetalle $soportesPagosHorariosDetallesEmpleadoRel)
    {
        $this->soportesPagosHorariosDetallesEmpleadoRel->removeElement($soportesPagosHorariosDetallesEmpleadoRel);
    }

    /**
     * Get soportesPagosHorariosDetallesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosHorariosDetallesEmpleadoRel()
    {
        return $this->soportesPagosHorariosDetallesEmpleadoRel;
    }

    /**
     * Add permisosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPermiso $permisosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addPermisosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPermiso $permisosEmpleadoRel)
    {
        $this->permisosEmpleadoRel[] = $permisosEmpleadoRel;

        return $this;
    }

    /**
     * Remove permisosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPermiso $permisosEmpleadoRel
     */
    public function removePermisosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPermiso $permisosEmpleadoRel)
    {
        $this->permisosEmpleadoRel->removeElement($permisosEmpleadoRel);
    }

    /**
     * Get permisosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermisosEmpleadoRel()
    {
        return $this->permisosEmpleadoRel;
    }

    /**
     * Add cartasEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCarta $cartasEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addCartasEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCarta $cartasEmpleadoRel)
    {
        $this->cartasEmpleadoRel[] = $cartasEmpleadoRel;

        return $this;
    }

    /**
     * Remove cartasEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCarta $cartasEmpleadoRel
     */
    public function removeCartasEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCarta $cartasEmpleadoRel)
    {
        $this->cartasEmpleadoRel->removeElement($cartasEmpleadoRel);
    }

    /**
     * Get cartasEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCartasEmpleadoRel()
    {
        return $this->cartasEmpleadoRel;
    }

    /**
     * Add capacitacionesDetallesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle $capacitacionesDetallesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addCapacitacionesDetallesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle $capacitacionesDetallesEmpleadoRel)
    {
        $this->capacitacionesDetallesEmpleadoRel[] = $capacitacionesDetallesEmpleadoRel;

        return $this;
    }

    /**
     * Remove capacitacionesDetallesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle $capacitacionesDetallesEmpleadoRel
     */
    public function removeCapacitacionesDetallesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle $capacitacionesDetallesEmpleadoRel)
    {
        $this->capacitacionesDetallesEmpleadoRel->removeElement($capacitacionesDetallesEmpleadoRel);
    }

    /**
     * Get capacitacionesDetallesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCapacitacionesDetallesEmpleadoRel()
    {
        return $this->capacitacionesDetallesEmpleadoRel;
    }

    /**
     * Add cambiosTiposContratosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addCambiosTiposContratosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosEmpleadoRel)
    {
        $this->cambiosTiposContratosEmpleadoRel[] = $cambiosTiposContratosEmpleadoRel;

        return $this;
    }

    /**
     * Remove cambiosTiposContratosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosEmpleadoRel
     */
    public function removeCambiosTiposContratosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosEmpleadoRel)
    {
        $this->cambiosTiposContratosEmpleadoRel->removeElement($cambiosTiposContratosEmpleadoRel);
    }

    /**
     * Get cambiosTiposContratosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCambiosTiposContratosEmpleadoRel()
    {
        return $this->cambiosTiposContratosEmpleadoRel;
    }

    /**
     * Set puestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestoRel
     *
     * @return RhuEmpleado
     */
    public function setPuestoRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestoRel = null)
    {
        $this->puestoRel = $puestoRel;

        return $this;
    }

    /**
     * Get puestoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPuesto
     */
    public function getPuestoRel()
    {
        return $this->puestoRel;
    }

    /**
     * Add pagosBancosDetallesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addPagosBancosDetallesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesEmpleadoRel)
    {
        $this->pagosBancosDetallesEmpleadoRel[] = $pagosBancosDetallesEmpleadoRel;

        return $this;
    }

    /**
     * Remove pagosBancosDetallesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesEmpleadoRel
     */
    public function removePagosBancosDetallesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesEmpleadoRel)
    {
        $this->pagosBancosDetallesEmpleadoRel->removeElement($pagosBancosDetallesEmpleadoRel);
    }

    /**
     * Get pagosBancosDetallesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosBancosDetallesEmpleadoRel()
    {
        return $this->pagosBancosDetallesEmpleadoRel;
    }

    /**
     * Add visitasEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addVisitasEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasEmpleadoRel)
    {
        $this->visitasEmpleadoRel[] = $visitasEmpleadoRel;

        return $this;
    }

    /**
     * Remove visitasEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasEmpleadoRel
     */
    public function removeVisitasEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasEmpleadoRel)
    {
        $this->visitasEmpleadoRel->removeElement($visitasEmpleadoRel);
    }

    /**
     * Get visitasEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitasEmpleadoRel()
    {
        return $this->visitasEmpleadoRel;
    }

    /**
     * Add reclamosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuReclamo $reclamosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addReclamosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuReclamo $reclamosEmpleadoRel)
    {
        $this->reclamosEmpleadoRel[] = $reclamosEmpleadoRel;

        return $this;
    }

    /**
     * Remove reclamosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuReclamo $reclamosEmpleadoRel
     */
    public function removeReclamosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuReclamo $reclamosEmpleadoRel)
    {
        $this->reclamosEmpleadoRel->removeElement($reclamosEmpleadoRel);
    }

    /**
     * Get reclamosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReclamosEmpleadoRel()
    {
        return $this->reclamosEmpleadoRel;
    }
}
