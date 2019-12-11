<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sso_aporte")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSsoAporteRepository")
 */
class RhuSsoAporte
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aporte_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAportePk;

    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer")
     */
    private $codigoPeriodoFk;

    /**
     * @ORM\Column(name="codigo_periodo_detalle_fk", type="integer")
     */
    private $codigoPeriodoDetalleFk;

    /**
     * @ORM\Column(name="codigo_sucursal_fk", type="integer")
     */
    private $codigoSucursalFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;


    /**
     * @ORM\Column(name="tipo_registro", type="bigint")
     */
    private $tipoRegistro;

    /**
     * @ORM\Column(name="secuencia", type="smallint")
     */
    private $secuencia;

    /**
     * @ORM\Column(name="tipo_documento", type="string", length=2)
     */
    private $tipoDocumento;

    /**
     * @ORM\Column(name="tipo_cotizante", type="smallint")
     */
    private $tipoCotizante;

    /**
     * @ORM\Column(name="subtipo_cotizante", type="smallint")
     */
    private $subtipoCotizante;

    /**
     * @ORM\Column(name="extranjero_no_obligado_cotizar_pension", type="string", length=1)
     */
    private $extranjeroNoObligadoCotizarPension;

    /**
     * @ORM\Column(name="colombiano_residente_exterior", type="string", length=1)
     */
    private $colombianoResidenteExterior;

    /**
     * @ORM\Column(name="codigo_departamento_ubicacion_laboral", type="string", length=2)
     */
    private $codigoDepartamentoUbicacionlaboral;

    /**
     * @ORM\Column(name="codigo_municipio_ubicacion_laboral", type="string", length=3)
     */
    private $codigoMunicipioUbicacionlaboral;

    /**
     * @ORM\Column(name="primer_nombre", type="string", length=20)
     */
    private $primerNombre;

    /**
     * @ORM\Column(name="segundo_nombre", type="string", length=30, nullable=true)
     */
    private $segundoNombre;

    /**
     * @ORM\Column(name="primer_apellido", type="string", length=20)
     */
    private $primerApellido;

    /**
     * @ORM\Column(name="segundo_apellido", type="string", length=30, nullable=true)
     */
    private $segundoApellido;

    /**
     * @ORM\Column(name="ingreso", type="string", length=1)
     */
    private $ingreso = ' ';

    /**
     * @ORM\Column(name="retiro", type="string", length=1)
     */
    private $retiro = ' ';

    /**
     * @ORM\Column(name="traslado_desde_otra_eps", type="string", length=1)
     */
    private $trasladoDesdeOtraEps = ' ';

    /**
     * @ORM\Column(name="traslado_a_otra_eps", type="string", length=1)
     */
    private $trasladoAOtraEps = ' ';

    /**
     * @ORM\Column(name="traslado_desde_otra_pension", type="string", length=1)
     */
    private $trasladoDesdeOtraPension = ' ';

    /**
     * @ORM\Column(name="traslado_a_otra_pension", type="string", length=1)
     */
    private $trasladoAOtraPension = ' ';

    /**
     * @ORM\Column(name="variacion_permanente_salario", type="string", length=1)
     */
    private $variacionPermanenteSalario = ' ';

    /**
     * @ORM\Column(name="correcciones", type="string", length=1)
     */
    private $correcciones = ' ';

    /**
     * @ORM\Column(name="variacion_transitoria_salario", type="string", length=1)
     */
    private $variacionTransitoriaSalario = ' ';

    /**
     * @ORM\Column(name="suspension_temporal_contrato_licencia_servicios", type="string", length=1)
     */
    private $suspensionTemporalContratoLicenciaServicios = ' ';

    /**
     * @ORM\Column(name="dias_licencia", type="integer")
     */
    private $diasLicencia = 0;

    /**
     * @ORM\Column(name="incapacidad_general", type="string", length=1)
     */
    private $incapacidadGeneral = ' ';

    /**
     * @ORM\Column(name="dias_incapacidad_general", type="integer")
     */
    private $diasIncapacidadGeneral = 0;

    /**
     * @ORM\Column(name="licencia_maternidad", type="string", length=1)
     */
    private $licenciaMaternidad = ' ';

    /**
     * @ORM\Column(name="dias_licencia_maternidad", type="integer")
     */
    private $diasLicenciaMaternidad = 0;

    /**
     * @ORM\Column(name="vacaciones", type="string", length=1)
     */
    private $vacaciones = ' ';

    /**
     * @ORM\Column(name="dias_vacaciones", type="integer")
     */
    private $diasVacaciones = 0;

    /**
     * @ORM\Column(name="aporte_voluntario", type="string", length=1)
     */
    private $aporteVoluntario = ' ';

    /**
     * @ORM\Column(name="variacion_centros_trabajo", type="string", length=1)
     */
    private $variacionCentrosTrabajo = ' ';

    /**
     * @ORM\Column(name="incapacidad_accidente_trabajo_enfermedad_profesional", type="integer")
     */
    private $incapacidadAccidenteTrabajoEnfermedadProfesional = 0;

    /**
     * @ORM\Column(name="codigo_entidad_pension_pertenece", type="string", length=6)
     */
    private $codigoEntidadPensionPertenece;

    /**
     * @ORM\Column(name="codigo_entidad_pension_traslada", type="string", length=6, nullable=true)
     */
    private $codigoEntidadPensionTraslada;

    /**
     * @ORM\Column(name="codigo_entidad_salud_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadSaludPertenece;

    /**
     * @ORM\Column(name="codigo_entidad_salud_traslada", type="string", length=6, nullable=true)
     */
    private $codigoEntidadSaludTraslada;

    /**
     * @ORM\Column(name="codigo_entidad_caja_pertenece", type="string", length=6)
     */
    private $codigoEntidadCajaPertenece;

    /**
     * @ORM\Column(name="dias_cotizados_pension", type="integer")
     */
    private $diasCotizadosPension = 0;

    /**
     * @ORM\Column(name="dias_cotizados_salud", type="integer")
     */
    private $diasCotizadosSalud = 0;

    /**
     * @ORM\Column(name="dias_cotizados_riesgos_profesionales", type="integer")
     */
    private $diasCotizadosRiesgosProfesionales = 0;

    /**
     * @ORM\Column(name="dias_cotizados_caja_compensacion", type="integer")
     */
    private $diasCotizadosCajaCompensacion = 0;

    /**
     * @ORM\Column(name="salario_basico", type="float")
     */
    private $salarioBasico = 0;

    /**
     * @ORM\Column(name="salario_mes_anterior", type="float")
     */
    private $salarioMesAnterior = 0;

    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */
    private $vrVacaciones = 0;

    /**
     * @ORM\Column(name="salario_integral", type="string", length=1)
     */
    private $salarioIntegral = ' ';

    /**
     * @ORM\Column(name="suplementario", type="float")
     */
    private $suplementario = 0;

    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", type="float")
     */
    private $vrIngresoBaseCotizacion = 0;

    /**
     * @ORM\Column(name="ibc_pension", type="float")
     */
    private $ibcPension = 0;

    /**
     * @ORM\Column(name="ibc_salud", type="float")
     */
    private $ibcSalud = 0;

    /**
     * @ORM\Column(name="ibc_riesgos_profesionales", type="float")
     */
    private $ibcRiesgosProfesionales = 0;

    /**
     * @ORM\Column(name="ibc_caja", type="float")
     */
    private $ibcCaja = 0;

    /**
     * @ORM\Column(name="tarifa_pension", type="float")
     */
    private $tarifaPension = 0;

    /**
     * @ORM\Column(name="tarifa_salud", type="float")
     */
    private $tarifaSalud = 0;

    /**
     * @ORM\Column(name="tarifa_riesgos", type="float")
     */
    private $tarifaRiesgos = 0;

    /**
     * @ORM\Column(name="tarifa_caja", type="float")
     */
    private $tarifaCaja = 0;

    /**
     * @ORM\Column(name="tarifa_sena", type="float")
     */
    private $tarifaSena = 0;

    /**
     * @ORM\Column(name="tarifa_icbf", type="float")
     */
    private $tarifaIcbf = 0;

    /**
     * @ORM\Column(name="cotizacion_pension", type="float")
     */
    private $cotizacionPension = 0;

    /**
     * @ORM\Column(name="cotizacion_salud", type="float")
     */
    private $cotizacionSalud = 0;

    /**
     * @ORM\Column(name="cotizacion_riesgos", type="float")
     */
    private $cotizacionRiesgos = 0;

    /**
     * @ORM\Column(name="cotizacion_caja", type="float")
     */
    private $cotizacionCaja = 0;

    /**
     * @ORM\Column(name="cotizacion_sena", type="float")
     */
    private $cotizacionSena = 0;

    /**
     * @ORM\Column(name="cotizacion_icbf", type="float")
     */
    private $cotizacionIcbf = 0;

    /**
     * @ORM\Column(name="aporte_voluntario_fondo_pensiones_obligatorias", type="string", length=9, nullable=true)
     */
    private $aporteVoluntarioFondoPensionesObligatorias;

    /**
     * @ORM\Column(name="cotizacion_voluntario_fondo_pensiones_obligatorias", type="string", length=9, nullable=true)
     */
    private $cotizacionVoluntarioFondoPensionesObligatorias;

    /**
     * @ORM\Column(name="total_cotizacion_fondos", type="float")
     */
    private $totalCotizacionFondos;

    /**
     * @ORM\Column(name="aportes_fondo_solidaridad_pensional_solidaridad", type="float")
     */
    private $aportesFondoSolidaridadPensionalSolidaridad = 0;

    /**
     * @ORM\Column(name="aportes_fondo_solidaridad_pensional_subsistencia", type="float")
     */
    private $aportesFondoSolidaridadPensionalSubsistencia = 0;

    /**
     * @ORM\Column(name="valor_upc_adicional", type="float")
     */
    private $valorUpcAdicional = 0;

    /**
     * @ORM\Column(name="numero_autorizacion_incapacidad_enfermedad_general", type="string", length=30, nullable=true)
     */
    private $numeroAutorizacionIncapacidadEnfermedadGeneral;

    /**
     * @ORM\Column(name="valor_incapacidad_enfermedad_general", type="float")
     */
    private $valorIncapacidadEnfermedadGeneral = 0;

    /**
     * @ORM\Column(name="numero_autorizacion_licencia_maternidad_paternidad", type="string", length=30, nullable=true)
     */
    private $numeroAutorizacionLicenciaMaternidadPaternidad;

    /**
     * @ORM\Column(name="valor_incapacidad_licencia_maternidad_paternidad", type="float")
     */
    private $valorIncapacidadLicenciaMaternidadPaternidad = 0;

    /**
     * @ORM\Column(name="centro_trabajo_codigo_ct", type="string", length=30, nullable=true)
     */
    private $centroTrabajoCodigoCt;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="tarifa_aporte_esap", type="float")
     */
    private $tarifaAportesESAP = 0;

    /**
     * @ORM\Column(name="valor_aporte_esap", type="float")
     */
    private $valorAportesESAP = 0;

    /**
     * @ORM\Column(name="tarifa_aporte_men", type="float")
     */
    private $tarifaAportesMEN = 0;

    /**
     * @ORM\Column(name="valor_aporte_men", type="float")
     */
    private $valorAportesMEN = 0;

    /**
     * @ORM\Column(name="tipo_documento_responsable_upc", type="string", length=4, nullable=true)
     */
    private $tipoDocumentoResponsableUPC;

    /**
     * @ORM\Column(name="numero_identificacion_responsable_upc_adicional", type="string", length=30, nullable=true)
     */
    private $numeroIdentificacionResponsableUPCAdicional;

    /**
     * @ORM\Column(name="cotizante_exonerado_pago_aporte_parafiscales_salud", type="string", length=20, nullable=true)
     */
    private $cotizanteExoneradoPagoAporteParafiscalesSalud;

    /**
     * @ORM\Column(name="codigo_administradora_riesgos_laborales", type="string", length=20, nullable=true)
     */
    private $codigoAdministradoraRiesgosLaborales;

    /**
     * @ORM\Column(name="clase_riesgo_afiliado", type="string", length=20, nullable=true)
     */
    private $claseRiesgoAfiliado;

    /**
     * @ORM\Column(name="total_cotizacion", type="float")
     */
    private $totalCotizacion;

    /**
     * @ORM\Column(name="codigo_entidad_pension_fk", type="integer", nullable=true)
     */
    private $codigoEntidadPensionFk;

    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */
    private $codigoEntidadSaludFk;

    /**
     * @ORM\Column(name="codigo_entidad_riesgo_fk", type="integer", nullable=true)
     */
    private $codigoEntidadRiesgoFk;

    /**
     * @ORM\Column(name="codigo_entidad_caja_fk", type="integer", nullable=true)
     */
    private $codigoEntidadCajaFk;

    /**
     * @ORM\Column(name="indicador_tarifa_especial_pensiones", type="string", length=1, nullable=true)
     */
    private $indicadorTarifaEspecialPensiones;

    /**
     * @ORM\Column(name="fecha_ingreso", type="string", length=10, nullable=true)
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(name="fecha_retiro", type="string", length=10, nullable=true)
     */
    private $fechaRetiro;

    /**
     * @ORM\Column(name="fecha_inicio_vsp", type="string", length=10, nullable=true)
     */
    private $fechaInicioVsp;

    /**
     * @ORM\Column(name="fecha_inicio_sln", type="string", length=10, nullable=true)
     */
    private $fechaInicioSln;

    /**
     * @ORM\Column(name="fecha_fin_sln", type="string", length=10, nullable=true)
     */
    private $fechaFinSln;

    /**
     * @ORM\Column(name="fecha_inicio_ige", type="string", length=10, nullable=true)
     */
    private $fechaInicioIge;

    /**
     * @ORM\Column(name="fecha_fin_ige", type="string", length=10, nullable=true)
     */
    private $fechaFinIge;

    /**
     * @ORM\Column(name="fecha_inicio_lma", type="string", length=10, nullable=true)
     */
    private $fechaInicioLma;

    /**
     * @ORM\Column(name="fecha_fin_lma", type="string", length=10, nullable=true)
     */
    private $fechaFinLma;

    /**
     * @ORM\Column(name="fecha_inicio_vac_lr", type="string", length=10, nullable=true)
     */
    private $fechaInicioVacLr;

    /**
     * @ORM\Column(name="fecha_fin_vac_lr", type="string", length=10, nullable=true)
     */
    private $fechaFinVacLr;

    /**
     * @ORM\Column(name="fecha_inicio_vct", type="string", length=10, nullable=true)
     */
    private $fechaInicioVct;

    /**
     * @ORM\Column(name="fecha_fin_vct", type="string", length=10, nullable=true)
     */
    private $fechaFinVct;

    /**
     * @ORM\Column(name="fecha_inicio_irl", type="string", length=10, nullable=true)
     */
    private $fechaInicioIrl;

    /**
     * @ORM\Column(name="fecha_fin_irl", type="string", length=10, nullable=true)
     */
    private $fechaFinIrl;

    /**
     * @ORM\Column(name="ibc_otros_parafiscales_diferentes_ccf", type="float", nullable=true)
     */
    private $ibcOtrosParafiscalesDiferentesCcf = 0;

    /**
     * @ORM\Column(name="numero_horas_laboradas", type="float", nullable=true)
     */
    private $numeroHorasLaboradas = 0;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoPeriodo", inversedBy="ssoAportesSsoPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $ssoPeriodoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoPeriodoDetalle", inversedBy="ssoAportesSsoPeriodoDetalleRel")
     * @ORM\JoinColumn(name="codigo_periodo_detalle_fk", referencedColumnName="codigo_periodo_detalle_pk")
     */
    protected $ssoPeriodoDetalleRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoSucursal", inversedBy="ssoAportesSsoSucursalRel")
     * @ORM\JoinColumn(name="codigo_sucursal_fk", referencedColumnName="codigo_sucursal_pk")
     */
    protected $ssoSucursalRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="ssoAportesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="ssoAportesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="ssoAportesCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadPension", inversedBy="ssoAportesEntidadPensionRel")
     * @ORM\JoinColumn(name="codigo_entidad_pension_fk", referencedColumnName="codigo_entidad_pension_pk")
     */
    protected $entidadPensionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadSalud", inversedBy="ssoAportesEntidadSaludRel")
     * @ORM\JoinColumn(name="codigo_entidad_salud_fk", referencedColumnName="codigo_entidad_salud_pk")
     */
    protected $entidadSaludRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadRiesgoProfesional", inversedBy="ssoAportesEntidadRiesgoProfesionalRel")
     * @ORM\JoinColumn(name="codigo_entidad_riesgo_fk", referencedColumnName="codigo_entidad_riesgo_pk")
     */
    protected $entidadRiesgoProfesionalRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadCaja", inversedBy="ssoAportesEntidadCajaRel")
     * @ORM\JoinColumn(name="codigo_entidad_caja_fk", referencedColumnName="codigo_entidad_caja_pk")
     */
    protected $entidadCajaRel;


    /**
     * Get codigoAportePk
     *
     * @return integer
     */
    public function getCodigoAportePk()
    {
        return $this->codigoAportePk;
    }

    /**
     * Set codigoPeriodoFk
     *
     * @param integer $codigoPeriodoFk
     *
     * @return RhuSsoAporte
     */
    public function setCodigoPeriodoFk($codigoPeriodoFk)
    {
        $this->codigoPeriodoFk = $codigoPeriodoFk;

        return $this;
    }

    /**
     * Get codigoPeriodoFk
     *
     * @return integer
     */
    public function getCodigoPeriodoFk()
    {
        return $this->codigoPeriodoFk;
    }

    /**
     * Set codigoPeriodoDetalleFk
     *
     * @param integer $codigoPeriodoDetalleFk
     *
     * @return RhuSsoAporte
     */
    public function setCodigoPeriodoDetalleFk($codigoPeriodoDetalleFk)
    {
        $this->codigoPeriodoDetalleFk = $codigoPeriodoDetalleFk;

        return $this;
    }

    /**
     * Get codigoPeriodoDetalleFk
     *
     * @return integer
     */
    public function getCodigoPeriodoDetalleFk()
    {
        return $this->codigoPeriodoDetalleFk;
    }

    /**
     * Set codigoSucursalFk
     *
     * @param integer $codigoSucursalFk
     *
     * @return RhuSsoAporte
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuSsoAporte
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
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuSsoAporte
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return RhuSsoAporte
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return integer
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     *
     * @return RhuSsoAporte
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return integer
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuSsoAporte
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
     * @return RhuSsoAporte
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
     * Set tipoRegistro
     *
     * @param integer $tipoRegistro
     *
     * @return RhuSsoAporte
     */
    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = $tipoRegistro;

        return $this;
    }

    /**
     * Get tipoRegistro
     *
     * @return integer
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    /**
     * Set secuencia
     *
     * @param integer $secuencia
     *
     * @return RhuSsoAporte
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
     * Set tipoDocumento
     *
     * @param string $tipoDocumento
     *
     * @return RhuSsoAporte
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return string
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set tipoCotizante
     *
     * @param integer $tipoCotizante
     *
     * @return RhuSsoAporte
     */
    public function setTipoCotizante($tipoCotizante)
    {
        $this->tipoCotizante = $tipoCotizante;

        return $this;
    }

    /**
     * Get tipoCotizante
     *
     * @return integer
     */
    public function getTipoCotizante()
    {
        return $this->tipoCotizante;
    }

    /**
     * Set subtipoCotizante
     *
     * @param integer $subtipoCotizante
     *
     * @return RhuSsoAporte
     */
    public function setSubtipoCotizante($subtipoCotizante)
    {
        $this->subtipoCotizante = $subtipoCotizante;

        return $this;
    }

    /**
     * Get subtipoCotizante
     *
     * @return integer
     */
    public function getSubtipoCotizante()
    {
        return $this->subtipoCotizante;
    }

    /**
     * Set extranjeroNoObligadoCotizarPension
     *
     * @param string $extranjeroNoObligadoCotizarPension
     *
     * @return RhuSsoAporte
     */
    public function setExtranjeroNoObligadoCotizarPension($extranjeroNoObligadoCotizarPension)
    {
        $this->extranjeroNoObligadoCotizarPension = $extranjeroNoObligadoCotizarPension;

        return $this;
    }

    /**
     * Get extranjeroNoObligadoCotizarPension
     *
     * @return string
     */
    public function getExtranjeroNoObligadoCotizarPension()
    {
        return $this->extranjeroNoObligadoCotizarPension;
    }

    /**
     * Set colombianoResidenteExterior
     *
     * @param string $colombianoResidenteExterior
     *
     * @return RhuSsoAporte
     */
    public function setColombianoResidenteExterior($colombianoResidenteExterior)
    {
        $this->colombianoResidenteExterior = $colombianoResidenteExterior;

        return $this;
    }

    /**
     * Get colombianoResidenteExterior
     *
     * @return string
     */
    public function getColombianoResidenteExterior()
    {
        return $this->colombianoResidenteExterior;
    }

    /**
     * Set codigoDepartamentoUbicacionlaboral
     *
     * @param string $codigoDepartamentoUbicacionlaboral
     *
     * @return RhuSsoAporte
     */
    public function setCodigoDepartamentoUbicacionlaboral($codigoDepartamentoUbicacionlaboral)
    {
        $this->codigoDepartamentoUbicacionlaboral = $codigoDepartamentoUbicacionlaboral;

        return $this;
    }

    /**
     * Get codigoDepartamentoUbicacionlaboral
     *
     * @return string
     */
    public function getCodigoDepartamentoUbicacionlaboral()
    {
        return $this->codigoDepartamentoUbicacionlaboral;
    }

    /**
     * Set codigoMunicipioUbicacionlaboral
     *
     * @param string $codigoMunicipioUbicacionlaboral
     *
     * @return RhuSsoAporte
     */
    public function setCodigoMunicipioUbicacionlaboral($codigoMunicipioUbicacionlaboral)
    {
        $this->codigoMunicipioUbicacionlaboral = $codigoMunicipioUbicacionlaboral;

        return $this;
    }

    /**
     * Get codigoMunicipioUbicacionlaboral
     *
     * @return string
     */
    public function getCodigoMunicipioUbicacionlaboral()
    {
        return $this->codigoMunicipioUbicacionlaboral;
    }

    /**
     * Set primerNombre
     *
     * @param string $primerNombre
     *
     * @return RhuSsoAporte
     */
    public function setPrimerNombre($primerNombre)
    {
        $this->primerNombre = $primerNombre;

        return $this;
    }

    /**
     * Get primerNombre
     *
     * @return string
     */
    public function getPrimerNombre()
    {
        return $this->primerNombre;
    }

    /**
     * Set segundoNombre
     *
     * @param string $segundoNombre
     *
     * @return RhuSsoAporte
     */
    public function setSegundoNombre($segundoNombre)
    {
        $this->segundoNombre = $segundoNombre;

        return $this;
    }

    /**
     * Get segundoNombre
     *
     * @return string
     */
    public function getSegundoNombre()
    {
        return $this->segundoNombre;
    }

    /**
     * Set primerApellido
     *
     * @param string $primerApellido
     *
     * @return RhuSsoAporte
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = $primerApellido;

        return $this;
    }

    /**
     * Get primerApellido
     *
     * @return string
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * Set segundoApellido
     *
     * @param string $segundoApellido
     *
     * @return RhuSsoAporte
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;

        return $this;
    }

    /**
     * Get segundoApellido
     *
     * @return string
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * Set ingreso
     *
     * @param string $ingreso
     *
     * @return RhuSsoAporte
     */
    public function setIngreso($ingreso)
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    /**
     * Get ingreso
     *
     * @return string
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * Set retiro
     *
     * @param string $retiro
     *
     * @return RhuSsoAporte
     */
    public function setRetiro($retiro)
    {
        $this->retiro = $retiro;

        return $this;
    }

    /**
     * Get retiro
     *
     * @return string
     */
    public function getRetiro()
    {
        return $this->retiro;
    }

    /**
     * Set trasladoDesdeOtraEps
     *
     * @param string $trasladoDesdeOtraEps
     *
     * @return RhuSsoAporte
     */
    public function setTrasladoDesdeOtraEps($trasladoDesdeOtraEps)
    {
        $this->trasladoDesdeOtraEps = $trasladoDesdeOtraEps;

        return $this;
    }

    /**
     * Get trasladoDesdeOtraEps
     *
     * @return string
     */
    public function getTrasladoDesdeOtraEps()
    {
        return $this->trasladoDesdeOtraEps;
    }

    /**
     * Set trasladoAOtraEps
     *
     * @param string $trasladoAOtraEps
     *
     * @return RhuSsoAporte
     */
    public function setTrasladoAOtraEps($trasladoAOtraEps)
    {
        $this->trasladoAOtraEps = $trasladoAOtraEps;

        return $this;
    }

    /**
     * Get trasladoAOtraEps
     *
     * @return string
     */
    public function getTrasladoAOtraEps()
    {
        return $this->trasladoAOtraEps;
    }

    /**
     * Set trasladoDesdeOtraPension
     *
     * @param string $trasladoDesdeOtraPension
     *
     * @return RhuSsoAporte
     */
    public function setTrasladoDesdeOtraPension($trasladoDesdeOtraPension)
    {
        $this->trasladoDesdeOtraPension = $trasladoDesdeOtraPension;

        return $this;
    }

    /**
     * Get trasladoDesdeOtraPension
     *
     * @return string
     */
    public function getTrasladoDesdeOtraPension()
    {
        return $this->trasladoDesdeOtraPension;
    }

    /**
     * Set trasladoAOtraPension
     *
     * @param string $trasladoAOtraPension
     *
     * @return RhuSsoAporte
     */
    public function setTrasladoAOtraPension($trasladoAOtraPension)
    {
        $this->trasladoAOtraPension = $trasladoAOtraPension;

        return $this;
    }

    /**
     * Get trasladoAOtraPension
     *
     * @return string
     */
    public function getTrasladoAOtraPension()
    {
        return $this->trasladoAOtraPension;
    }

    /**
     * Set variacionPermanenteSalario
     *
     * @param string $variacionPermanenteSalario
     *
     * @return RhuSsoAporte
     */
    public function setVariacionPermanenteSalario($variacionPermanenteSalario)
    {
        $this->variacionPermanenteSalario = $variacionPermanenteSalario;

        return $this;
    }

    /**
     * Get variacionPermanenteSalario
     *
     * @return string
     */
    public function getVariacionPermanenteSalario()
    {
        return $this->variacionPermanenteSalario;
    }

    /**
     * Set correcciones
     *
     * @param string $correcciones
     *
     * @return RhuSsoAporte
     */
    public function setCorrecciones($correcciones)
    {
        $this->correcciones = $correcciones;

        return $this;
    }

    /**
     * Get correcciones
     *
     * @return string
     */
    public function getCorrecciones()
    {
        return $this->correcciones;
    }

    /**
     * Set variacionTransitoriaSalario
     *
     * @param string $variacionTransitoriaSalario
     *
     * @return RhuSsoAporte
     */
    public function setVariacionTransitoriaSalario($variacionTransitoriaSalario)
    {
        $this->variacionTransitoriaSalario = $variacionTransitoriaSalario;

        return $this;
    }

    /**
     * Get variacionTransitoriaSalario
     *
     * @return string
     */
    public function getVariacionTransitoriaSalario()
    {
        return $this->variacionTransitoriaSalario;
    }

    /**
     * Set suspensionTemporalContratoLicenciaServicios
     *
     * @param string $suspensionTemporalContratoLicenciaServicios
     *
     * @return RhuSsoAporte
     */
    public function setSuspensionTemporalContratoLicenciaServicios($suspensionTemporalContratoLicenciaServicios)
    {
        $this->suspensionTemporalContratoLicenciaServicios = $suspensionTemporalContratoLicenciaServicios;

        return $this;
    }

    /**
     * Get suspensionTemporalContratoLicenciaServicios
     *
     * @return string
     */
    public function getSuspensionTemporalContratoLicenciaServicios()
    {
        return $this->suspensionTemporalContratoLicenciaServicios;
    }

    /**
     * Set diasLicencia
     *
     * @param integer $diasLicencia
     *
     * @return RhuSsoAporte
     */
    public function setDiasLicencia($diasLicencia)
    {
        $this->diasLicencia = $diasLicencia;

        return $this;
    }

    /**
     * Get diasLicencia
     *
     * @return integer
     */
    public function getDiasLicencia()
    {
        return $this->diasLicencia;
    }

    /**
     * Set incapacidadGeneral
     *
     * @param string $incapacidadGeneral
     *
     * @return RhuSsoAporte
     */
    public function setIncapacidadGeneral($incapacidadGeneral)
    {
        $this->incapacidadGeneral = $incapacidadGeneral;

        return $this;
    }

    /**
     * Get incapacidadGeneral
     *
     * @return string
     */
    public function getIncapacidadGeneral()
    {
        return $this->incapacidadGeneral;
    }

    /**
     * Set diasIncapacidadGeneral
     *
     * @param integer $diasIncapacidadGeneral
     *
     * @return RhuSsoAporte
     */
    public function setDiasIncapacidadGeneral($diasIncapacidadGeneral)
    {
        $this->diasIncapacidadGeneral = $diasIncapacidadGeneral;

        return $this;
    }

    /**
     * Get diasIncapacidadGeneral
     *
     * @return integer
     */
    public function getDiasIncapacidadGeneral()
    {
        return $this->diasIncapacidadGeneral;
    }

    /**
     * Set licenciaMaternidad
     *
     * @param string $licenciaMaternidad
     *
     * @return RhuSsoAporte
     */
    public function setLicenciaMaternidad($licenciaMaternidad)
    {
        $this->licenciaMaternidad = $licenciaMaternidad;

        return $this;
    }

    /**
     * Get licenciaMaternidad
     *
     * @return string
     */
    public function getLicenciaMaternidad()
    {
        return $this->licenciaMaternidad;
    }

    /**
     * Set diasLicenciaMaternidad
     *
     * @param integer $diasLicenciaMaternidad
     *
     * @return RhuSsoAporte
     */
    public function setDiasLicenciaMaternidad($diasLicenciaMaternidad)
    {
        $this->diasLicenciaMaternidad = $diasLicenciaMaternidad;

        return $this;
    }

    /**
     * Get diasLicenciaMaternidad
     *
     * @return integer
     */
    public function getDiasLicenciaMaternidad()
    {
        return $this->diasLicenciaMaternidad;
    }

    /**
     * Set vacaciones
     *
     * @param string $vacaciones
     *
     * @return RhuSsoAporte
     */
    public function setVacaciones($vacaciones)
    {
        $this->vacaciones = $vacaciones;

        return $this;
    }

    /**
     * Get vacaciones
     *
     * @return string
     */
    public function getVacaciones()
    {
        return $this->vacaciones;
    }

    /**
     * Set diasVacaciones
     *
     * @param integer $diasVacaciones
     *
     * @return RhuSsoAporte
     */
    public function setDiasVacaciones($diasVacaciones)
    {
        $this->diasVacaciones = $diasVacaciones;

        return $this;
    }

    /**
     * Get diasVacaciones
     *
     * @return integer
     */
    public function getDiasVacaciones()
    {
        return $this->diasVacaciones;
    }

    /**
     * Set aporteVoluntario
     *
     * @param string $aporteVoluntario
     *
     * @return RhuSsoAporte
     */
    public function setAporteVoluntario($aporteVoluntario)
    {
        $this->aporteVoluntario = $aporteVoluntario;

        return $this;
    }

    /**
     * Get aporteVoluntario
     *
     * @return string
     */
    public function getAporteVoluntario()
    {
        return $this->aporteVoluntario;
    }

    /**
     * Set variacionCentrosTrabajo
     *
     * @param string $variacionCentrosTrabajo
     *
     * @return RhuSsoAporte
     */
    public function setVariacionCentrosTrabajo($variacionCentrosTrabajo)
    {
        $this->variacionCentrosTrabajo = $variacionCentrosTrabajo;

        return $this;
    }

    /**
     * Get variacionCentrosTrabajo
     *
     * @return string
     */
    public function getVariacionCentrosTrabajo()
    {
        return $this->variacionCentrosTrabajo;
    }

    /**
     * Set incapacidadAccidenteTrabajoEnfermedadProfesional
     *
     * @param integer $incapacidadAccidenteTrabajoEnfermedadProfesional
     *
     * @return RhuSsoAporte
     */
    public function setIncapacidadAccidenteTrabajoEnfermedadProfesional($incapacidadAccidenteTrabajoEnfermedadProfesional)
    {
        $this->incapacidadAccidenteTrabajoEnfermedadProfesional = $incapacidadAccidenteTrabajoEnfermedadProfesional;

        return $this;
    }

    /**
     * Get incapacidadAccidenteTrabajoEnfermedadProfesional
     *
     * @return integer
     */
    public function getIncapacidadAccidenteTrabajoEnfermedadProfesional()
    {
        return $this->incapacidadAccidenteTrabajoEnfermedadProfesional;
    }

    /**
     * Set codigoEntidadPensionPertenece
     *
     * @param string $codigoEntidadPensionPertenece
     *
     * @return RhuSsoAporte
     */
    public function setCodigoEntidadPensionPertenece($codigoEntidadPensionPertenece)
    {
        $this->codigoEntidadPensionPertenece = $codigoEntidadPensionPertenece;

        return $this;
    }

    /**
     * Get codigoEntidadPensionPertenece
     *
     * @return string
     */
    public function getCodigoEntidadPensionPertenece()
    {
        return $this->codigoEntidadPensionPertenece;
    }

    /**
     * Set codigoEntidadPensionTraslada
     *
     * @param string $codigoEntidadPensionTraslada
     *
     * @return RhuSsoAporte
     */
    public function setCodigoEntidadPensionTraslada($codigoEntidadPensionTraslada)
    {
        $this->codigoEntidadPensionTraslada = $codigoEntidadPensionTraslada;

        return $this;
    }

    /**
     * Get codigoEntidadPensionTraslada
     *
     * @return string
     */
    public function getCodigoEntidadPensionTraslada()
    {
        return $this->codigoEntidadPensionTraslada;
    }

    /**
     * Set codigoEntidadSaludPertenece
     *
     * @param string $codigoEntidadSaludPertenece
     *
     * @return RhuSsoAporte
     */
    public function setCodigoEntidadSaludPertenece($codigoEntidadSaludPertenece)
    {
        $this->codigoEntidadSaludPertenece = $codigoEntidadSaludPertenece;

        return $this;
    }

    /**
     * Get codigoEntidadSaludPertenece
     *
     * @return string
     */
    public function getCodigoEntidadSaludPertenece()
    {
        return $this->codigoEntidadSaludPertenece;
    }

    /**
     * Set codigoEntidadSaludTraslada
     *
     * @param string $codigoEntidadSaludTraslada
     *
     * @return RhuSsoAporte
     */
    public function setCodigoEntidadSaludTraslada($codigoEntidadSaludTraslada)
    {
        $this->codigoEntidadSaludTraslada = $codigoEntidadSaludTraslada;

        return $this;
    }

    /**
     * Get codigoEntidadSaludTraslada
     *
     * @return string
     */
    public function getCodigoEntidadSaludTraslada()
    {
        return $this->codigoEntidadSaludTraslada;
    }

    /**
     * Set codigoEntidadCajaPertenece
     *
     * @param string $codigoEntidadCajaPertenece
     *
     * @return RhuSsoAporte
     */
    public function setCodigoEntidadCajaPertenece($codigoEntidadCajaPertenece)
    {
        $this->codigoEntidadCajaPertenece = $codigoEntidadCajaPertenece;

        return $this;
    }

    /**
     * Get codigoEntidadCajaPertenece
     *
     * @return string
     */
    public function getCodigoEntidadCajaPertenece()
    {
        return $this->codigoEntidadCajaPertenece;
    }

    /**
     * Set diasCotizadosPension
     *
     * @param integer $diasCotizadosPension
     *
     * @return RhuSsoAporte
     */
    public function setDiasCotizadosPension($diasCotizadosPension)
    {
        $this->diasCotizadosPension = $diasCotizadosPension;

        return $this;
    }

    /**
     * Get diasCotizadosPension
     *
     * @return integer
     */
    public function getDiasCotizadosPension()
    {
        return $this->diasCotizadosPension;
    }

    /**
     * Set diasCotizadosSalud
     *
     * @param integer $diasCotizadosSalud
     *
     * @return RhuSsoAporte
     */
    public function setDiasCotizadosSalud($diasCotizadosSalud)
    {
        $this->diasCotizadosSalud = $diasCotizadosSalud;

        return $this;
    }

    /**
     * Get diasCotizadosSalud
     *
     * @return integer
     */
    public function getDiasCotizadosSalud()
    {
        return $this->diasCotizadosSalud;
    }

    /**
     * Set diasCotizadosRiesgosProfesionales
     *
     * @param integer $diasCotizadosRiesgosProfesionales
     *
     * @return RhuSsoAporte
     */
    public function setDiasCotizadosRiesgosProfesionales($diasCotizadosRiesgosProfesionales)
    {
        $this->diasCotizadosRiesgosProfesionales = $diasCotizadosRiesgosProfesionales;

        return $this;
    }

    /**
     * Get diasCotizadosRiesgosProfesionales
     *
     * @return integer
     */
    public function getDiasCotizadosRiesgosProfesionales()
    {
        return $this->diasCotizadosRiesgosProfesionales;
    }

    /**
     * Set diasCotizadosCajaCompensacion
     *
     * @param integer $diasCotizadosCajaCompensacion
     *
     * @return RhuSsoAporte
     */
    public function setDiasCotizadosCajaCompensacion($diasCotizadosCajaCompensacion)
    {
        $this->diasCotizadosCajaCompensacion = $diasCotizadosCajaCompensacion;

        return $this;
    }

    /**
     * Get diasCotizadosCajaCompensacion
     *
     * @return integer
     */
    public function getDiasCotizadosCajaCompensacion()
    {
        return $this->diasCotizadosCajaCompensacion;
    }

    /**
     * Set salarioBasico
     *
     * @param float $salarioBasico
     *
     * @return RhuSsoAporte
     */
    public function setSalarioBasico($salarioBasico)
    {
        $this->salarioBasico = $salarioBasico;

        return $this;
    }

    /**
     * Get salarioBasico
     *
     * @return float
     */
    public function getSalarioBasico()
    {
        return $this->salarioBasico;
    }

    /**
     * Set salarioMesAnterior
     *
     * @param float $salarioMesAnterior
     *
     * @return RhuSsoAporte
     */
    public function setSalarioMesAnterior($salarioMesAnterior)
    {
        $this->salarioMesAnterior = $salarioMesAnterior;

        return $this;
    }

    /**
     * Get salarioMesAnterior
     *
     * @return float
     */
    public function getSalarioMesAnterior()
    {
        return $this->salarioMesAnterior;
    }

    /**
     * Set vrVacaciones
     *
     * @param float $vrVacaciones
     *
     * @return RhuSsoAporte
     */
    public function setVrVacaciones($vrVacaciones)
    {
        $this->vrVacaciones = $vrVacaciones;

        return $this;
    }

    /**
     * Get vrVacaciones
     *
     * @return float
     */
    public function getVrVacaciones()
    {
        return $this->vrVacaciones;
    }

    /**
     * Set salarioIntegral
     *
     * @param string $salarioIntegral
     *
     * @return RhuSsoAporte
     */
    public function setSalarioIntegral($salarioIntegral)
    {
        $this->salarioIntegral = $salarioIntegral;

        return $this;
    }

    /**
     * Get salarioIntegral
     *
     * @return string
     */
    public function getSalarioIntegral()
    {
        return $this->salarioIntegral;
    }

    /**
     * Set suplementario
     *
     * @param float $suplementario
     *
     * @return RhuSsoAporte
     */
    public function setSuplementario($suplementario)
    {
        $this->suplementario = $suplementario;

        return $this;
    }

    /**
     * Get suplementario
     *
     * @return float
     */
    public function getSuplementario()
    {
        return $this->suplementario;
    }

    /**
     * Set vrIngresoBaseCotizacion
     *
     * @param float $vrIngresoBaseCotizacion
     *
     * @return RhuSsoAporte
     */
    public function setVrIngresoBaseCotizacion($vrIngresoBaseCotizacion)
    {
        $this->vrIngresoBaseCotizacion = $vrIngresoBaseCotizacion;

        return $this;
    }

    /**
     * Get vrIngresoBaseCotizacion
     *
     * @return float
     */
    public function getVrIngresoBaseCotizacion()
    {
        return $this->vrIngresoBaseCotizacion;
    }

    /**
     * Set ibcPension
     *
     * @param float $ibcPension
     *
     * @return RhuSsoAporte
     */
    public function setIbcPension($ibcPension)
    {
        $this->ibcPension = $ibcPension;

        return $this;
    }

    /**
     * Get ibcPension
     *
     * @return float
     */
    public function getIbcPension()
    {
        return $this->ibcPension;
    }

    /**
     * Set ibcSalud
     *
     * @param float $ibcSalud
     *
     * @return RhuSsoAporte
     */
    public function setIbcSalud($ibcSalud)
    {
        $this->ibcSalud = $ibcSalud;

        return $this;
    }

    /**
     * Get ibcSalud
     *
     * @return float
     */
    public function getIbcSalud()
    {
        return $this->ibcSalud;
    }

    /**
     * Set ibcRiesgosProfesionales
     *
     * @param float $ibcRiesgosProfesionales
     *
     * @return RhuSsoAporte
     */
    public function setIbcRiesgosProfesionales($ibcRiesgosProfesionales)
    {
        $this->ibcRiesgosProfesionales = $ibcRiesgosProfesionales;

        return $this;
    }

    /**
     * Get ibcRiesgosProfesionales
     *
     * @return float
     */
    public function getIbcRiesgosProfesionales()
    {
        return $this->ibcRiesgosProfesionales;
    }

    /**
     * Set ibcCaja
     *
     * @param float $ibcCaja
     *
     * @return RhuSsoAporte
     */
    public function setIbcCaja($ibcCaja)
    {
        $this->ibcCaja = $ibcCaja;

        return $this;
    }

    /**
     * Get ibcCaja
     *
     * @return float
     */
    public function getIbcCaja()
    {
        return $this->ibcCaja;
    }

    /**
     * Set tarifaPension
     *
     * @param float $tarifaPension
     *
     * @return RhuSsoAporte
     */
    public function setTarifaPension($tarifaPension)
    {
        $this->tarifaPension = $tarifaPension;

        return $this;
    }

    /**
     * Get tarifaPension
     *
     * @return float
     */
    public function getTarifaPension()
    {
        return $this->tarifaPension;
    }

    /**
     * Set tarifaSalud
     *
     * @param float $tarifaSalud
     *
     * @return RhuSsoAporte
     */
    public function setTarifaSalud($tarifaSalud)
    {
        $this->tarifaSalud = $tarifaSalud;

        return $this;
    }

    /**
     * Get tarifaSalud
     *
     * @return float
     */
    public function getTarifaSalud()
    {
        return $this->tarifaSalud;
    }

    /**
     * Set tarifaRiesgos
     *
     * @param float $tarifaRiesgos
     *
     * @return RhuSsoAporte
     */
    public function setTarifaRiesgos($tarifaRiesgos)
    {
        $this->tarifaRiesgos = $tarifaRiesgos;

        return $this;
    }

    /**
     * Get tarifaRiesgos
     *
     * @return float
     */
    public function getTarifaRiesgos()
    {
        return $this->tarifaRiesgos;
    }

    /**
     * Set tarifaCaja
     *
     * @param float $tarifaCaja
     *
     * @return RhuSsoAporte
     */
    public function setTarifaCaja($tarifaCaja)
    {
        $this->tarifaCaja = $tarifaCaja;

        return $this;
    }

    /**
     * Get tarifaCaja
     *
     * @return float
     */
    public function getTarifaCaja()
    {
        return $this->tarifaCaja;
    }

    /**
     * Set tarifaSena
     *
     * @param float $tarifaSena
     *
     * @return RhuSsoAporte
     */
    public function setTarifaSena($tarifaSena)
    {
        $this->tarifaSena = $tarifaSena;

        return $this;
    }

    /**
     * Get tarifaSena
     *
     * @return float
     */
    public function getTarifaSena()
    {
        return $this->tarifaSena;
    }

    /**
     * Set tarifaIcbf
     *
     * @param float $tarifaIcbf
     *
     * @return RhuSsoAporte
     */
    public function setTarifaIcbf($tarifaIcbf)
    {
        $this->tarifaIcbf = $tarifaIcbf;

        return $this;
    }

    /**
     * Get tarifaIcbf
     *
     * @return float
     */
    public function getTarifaIcbf()
    {
        return $this->tarifaIcbf;
    }

    /**
     * Set cotizacionPension
     *
     * @param float $cotizacionPension
     *
     * @return RhuSsoAporte
     */
    public function setCotizacionPension($cotizacionPension)
    {
        $this->cotizacionPension = $cotizacionPension;

        return $this;
    }

    /**
     * Get cotizacionPension
     *
     * @return float
     */
    public function getCotizacionPension()
    {
        return $this->cotizacionPension;
    }

    /**
     * Set cotizacionSalud
     *
     * @param float $cotizacionSalud
     *
     * @return RhuSsoAporte
     */
    public function setCotizacionSalud($cotizacionSalud)
    {
        $this->cotizacionSalud = $cotizacionSalud;

        return $this;
    }

    /**
     * Get cotizacionSalud
     *
     * @return float
     */
    public function getCotizacionSalud()
    {
        return $this->cotizacionSalud;
    }

    /**
     * Set cotizacionRiesgos
     *
     * @param float $cotizacionRiesgos
     *
     * @return RhuSsoAporte
     */
    public function setCotizacionRiesgos($cotizacionRiesgos)
    {
        $this->cotizacionRiesgos = $cotizacionRiesgos;

        return $this;
    }

    /**
     * Get cotizacionRiesgos
     *
     * @return float
     */
    public function getCotizacionRiesgos()
    {
        return $this->cotizacionRiesgos;
    }

    /**
     * Set cotizacionCaja
     *
     * @param float $cotizacionCaja
     *
     * @return RhuSsoAporte
     */
    public function setCotizacionCaja($cotizacionCaja)
    {
        $this->cotizacionCaja = $cotizacionCaja;

        return $this;
    }

    /**
     * Get cotizacionCaja
     *
     * @return float
     */
    public function getCotizacionCaja()
    {
        return $this->cotizacionCaja;
    }

    /**
     * Set cotizacionSena
     *
     * @param float $cotizacionSena
     *
     * @return RhuSsoAporte
     */
    public function setCotizacionSena($cotizacionSena)
    {
        $this->cotizacionSena = $cotizacionSena;

        return $this;
    }

    /**
     * Get cotizacionSena
     *
     * @return float
     */
    public function getCotizacionSena()
    {
        return $this->cotizacionSena;
    }

    /**
     * Set cotizacionIcbf
     *
     * @param float $cotizacionIcbf
     *
     * @return RhuSsoAporte
     */
    public function setCotizacionIcbf($cotizacionIcbf)
    {
        $this->cotizacionIcbf = $cotizacionIcbf;

        return $this;
    }

    /**
     * Get cotizacionIcbf
     *
     * @return float
     */
    public function getCotizacionIcbf()
    {
        return $this->cotizacionIcbf;
    }

    /**
     * Set aporteVoluntarioFondoPensionesObligatorias
     *
     * @param string $aporteVoluntarioFondoPensionesObligatorias
     *
     * @return RhuSsoAporte
     */
    public function setAporteVoluntarioFondoPensionesObligatorias($aporteVoluntarioFondoPensionesObligatorias)
    {
        $this->aporteVoluntarioFondoPensionesObligatorias = $aporteVoluntarioFondoPensionesObligatorias;

        return $this;
    }

    /**
     * Get aporteVoluntarioFondoPensionesObligatorias
     *
     * @return string
     */
    public function getAporteVoluntarioFondoPensionesObligatorias()
    {
        return $this->aporteVoluntarioFondoPensionesObligatorias;
    }

    /**
     * Set cotizacionVoluntarioFondoPensionesObligatorias
     *
     * @param string $cotizacionVoluntarioFondoPensionesObligatorias
     *
     * @return RhuSsoAporte
     */
    public function setCotizacionVoluntarioFondoPensionesObligatorias($cotizacionVoluntarioFondoPensionesObligatorias)
    {
        $this->cotizacionVoluntarioFondoPensionesObligatorias = $cotizacionVoluntarioFondoPensionesObligatorias;

        return $this;
    }

    /**
     * Get cotizacionVoluntarioFondoPensionesObligatorias
     *
     * @return string
     */
    public function getCotizacionVoluntarioFondoPensionesObligatorias()
    {
        return $this->cotizacionVoluntarioFondoPensionesObligatorias;
    }

    /**
     * Set totalCotizacionFondos
     *
     * @param float $totalCotizacionFondos
     *
     * @return RhuSsoAporte
     */
    public function setTotalCotizacionFondos($totalCotizacionFondos)
    {
        $this->totalCotizacionFondos = $totalCotizacionFondos;

        return $this;
    }

    /**
     * Get totalCotizacionFondos
     *
     * @return float
     */
    public function getTotalCotizacionFondos()
    {
        return $this->totalCotizacionFondos;
    }

    /**
     * Set aportesFondoSolidaridadPensionalSolidaridad
     *
     * @param float $aportesFondoSolidaridadPensionalSolidaridad
     *
     * @return RhuSsoAporte
     */
    public function setAportesFondoSolidaridadPensionalSolidaridad($aportesFondoSolidaridadPensionalSolidaridad)
    {
        $this->aportesFondoSolidaridadPensionalSolidaridad = $aportesFondoSolidaridadPensionalSolidaridad;

        return $this;
    }

    /**
     * Get aportesFondoSolidaridadPensionalSolidaridad
     *
     * @return float
     */
    public function getAportesFondoSolidaridadPensionalSolidaridad()
    {
        return $this->aportesFondoSolidaridadPensionalSolidaridad;
    }

    /**
     * Set aportesFondoSolidaridadPensionalSubsistencia
     *
     * @param float $aportesFondoSolidaridadPensionalSubsistencia
     *
     * @return RhuSsoAporte
     */
    public function setAportesFondoSolidaridadPensionalSubsistencia($aportesFondoSolidaridadPensionalSubsistencia)
    {
        $this->aportesFondoSolidaridadPensionalSubsistencia = $aportesFondoSolidaridadPensionalSubsistencia;

        return $this;
    }

    /**
     * Get aportesFondoSolidaridadPensionalSubsistencia
     *
     * @return float
     */
    public function getAportesFondoSolidaridadPensionalSubsistencia()
    {
        return $this->aportesFondoSolidaridadPensionalSubsistencia;
    }

    /**
     * Set valorUpcAdicional
     *
     * @param float $valorUpcAdicional
     *
     * @return RhuSsoAporte
     */
    public function setValorUpcAdicional($valorUpcAdicional)
    {
        $this->valorUpcAdicional = $valorUpcAdicional;

        return $this;
    }

    /**
     * Get valorUpcAdicional
     *
     * @return float
     */
    public function getValorUpcAdicional()
    {
        return $this->valorUpcAdicional;
    }

    /**
     * Set numeroAutorizacionIncapacidadEnfermedadGeneral
     *
     * @param string $numeroAutorizacionIncapacidadEnfermedadGeneral
     *
     * @return RhuSsoAporte
     */
    public function setNumeroAutorizacionIncapacidadEnfermedadGeneral($numeroAutorizacionIncapacidadEnfermedadGeneral)
    {
        $this->numeroAutorizacionIncapacidadEnfermedadGeneral = $numeroAutorizacionIncapacidadEnfermedadGeneral;

        return $this;
    }

    /**
     * Get numeroAutorizacionIncapacidadEnfermedadGeneral
     *
     * @return string
     */
    public function getNumeroAutorizacionIncapacidadEnfermedadGeneral()
    {
        return $this->numeroAutorizacionIncapacidadEnfermedadGeneral;
    }

    /**
     * Set valorIncapacidadEnfermedadGeneral
     *
     * @param float $valorIncapacidadEnfermedadGeneral
     *
     * @return RhuSsoAporte
     */
    public function setValorIncapacidadEnfermedadGeneral($valorIncapacidadEnfermedadGeneral)
    {
        $this->valorIncapacidadEnfermedadGeneral = $valorIncapacidadEnfermedadGeneral;

        return $this;
    }

    /**
     * Get valorIncapacidadEnfermedadGeneral
     *
     * @return float
     */
    public function getValorIncapacidadEnfermedadGeneral()
    {
        return $this->valorIncapacidadEnfermedadGeneral;
    }

    /**
     * Set numeroAutorizacionLicenciaMaternidadPaternidad
     *
     * @param string $numeroAutorizacionLicenciaMaternidadPaternidad
     *
     * @return RhuSsoAporte
     */
    public function setNumeroAutorizacionLicenciaMaternidadPaternidad($numeroAutorizacionLicenciaMaternidadPaternidad)
    {
        $this->numeroAutorizacionLicenciaMaternidadPaternidad = $numeroAutorizacionLicenciaMaternidadPaternidad;

        return $this;
    }

    /**
     * Get numeroAutorizacionLicenciaMaternidadPaternidad
     *
     * @return string
     */
    public function getNumeroAutorizacionLicenciaMaternidadPaternidad()
    {
        return $this->numeroAutorizacionLicenciaMaternidadPaternidad;
    }

    /**
     * Set valorIncapacidadLicenciaMaternidadPaternidad
     *
     * @param float $valorIncapacidadLicenciaMaternidadPaternidad
     *
     * @return RhuSsoAporte
     */
    public function setValorIncapacidadLicenciaMaternidadPaternidad($valorIncapacidadLicenciaMaternidadPaternidad)
    {
        $this->valorIncapacidadLicenciaMaternidadPaternidad = $valorIncapacidadLicenciaMaternidadPaternidad;

        return $this;
    }

    /**
     * Get valorIncapacidadLicenciaMaternidadPaternidad
     *
     * @return float
     */
    public function getValorIncapacidadLicenciaMaternidadPaternidad()
    {
        return $this->valorIncapacidadLicenciaMaternidadPaternidad;
    }

    /**
     * Set centroTrabajoCodigoCt
     *
     * @param string $centroTrabajoCodigoCt
     *
     * @return RhuSsoAporte
     */
    public function setCentroTrabajoCodigoCt($centroTrabajoCodigoCt)
    {
        $this->centroTrabajoCodigoCt = $centroTrabajoCodigoCt;

        return $this;
    }

    /**
     * Get centroTrabajoCodigoCt
     *
     * @return string
     */
    public function getCentroTrabajoCodigoCt()
    {
        return $this->centroTrabajoCodigoCt;
    }

    /**
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuSsoAporte
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
     * Set tarifaAportesESAP
     *
     * @param float $tarifaAportesESAP
     *
     * @return RhuSsoAporte
     */
    public function setTarifaAportesESAP($tarifaAportesESAP)
    {
        $this->tarifaAportesESAP = $tarifaAportesESAP;

        return $this;
    }

    /**
     * Get tarifaAportesESAP
     *
     * @return float
     */
    public function getTarifaAportesESAP()
    {
        return $this->tarifaAportesESAP;
    }

    /**
     * Set valorAportesESAP
     *
     * @param float $valorAportesESAP
     *
     * @return RhuSsoAporte
     */
    public function setValorAportesESAP($valorAportesESAP)
    {
        $this->valorAportesESAP = $valorAportesESAP;

        return $this;
    }

    /**
     * Get valorAportesESAP
     *
     * @return float
     */
    public function getValorAportesESAP()
    {
        return $this->valorAportesESAP;
    }

    /**
     * Set tarifaAportesMEN
     *
     * @param float $tarifaAportesMEN
     *
     * @return RhuSsoAporte
     */
    public function setTarifaAportesMEN($tarifaAportesMEN)
    {
        $this->tarifaAportesMEN = $tarifaAportesMEN;

        return $this;
    }

    /**
     * Get tarifaAportesMEN
     *
     * @return float
     */
    public function getTarifaAportesMEN()
    {
        return $this->tarifaAportesMEN;
    }

    /**
     * Set valorAportesMEN
     *
     * @param float $valorAportesMEN
     *
     * @return RhuSsoAporte
     */
    public function setValorAportesMEN($valorAportesMEN)
    {
        $this->valorAportesMEN = $valorAportesMEN;

        return $this;
    }

    /**
     * Get valorAportesMEN
     *
     * @return float
     */
    public function getValorAportesMEN()
    {
        return $this->valorAportesMEN;
    }

    /**
     * Set tipoDocumentoResponsableUPC
     *
     * @param string $tipoDocumentoResponsableUPC
     *
     * @return RhuSsoAporte
     */
    public function setTipoDocumentoResponsableUPC($tipoDocumentoResponsableUPC)
    {
        $this->tipoDocumentoResponsableUPC = $tipoDocumentoResponsableUPC;

        return $this;
    }

    /**
     * Get tipoDocumentoResponsableUPC
     *
     * @return string
     */
    public function getTipoDocumentoResponsableUPC()
    {
        return $this->tipoDocumentoResponsableUPC;
    }

    /**
     * Set numeroIdentificacionResponsableUPCAdicional
     *
     * @param string $numeroIdentificacionResponsableUPCAdicional
     *
     * @return RhuSsoAporte
     */
    public function setNumeroIdentificacionResponsableUPCAdicional($numeroIdentificacionResponsableUPCAdicional)
    {
        $this->numeroIdentificacionResponsableUPCAdicional = $numeroIdentificacionResponsableUPCAdicional;

        return $this;
    }

    /**
     * Get numeroIdentificacionResponsableUPCAdicional
     *
     * @return string
     */
    public function getNumeroIdentificacionResponsableUPCAdicional()
    {
        return $this->numeroIdentificacionResponsableUPCAdicional;
    }

    /**
     * Set cotizanteExoneradoPagoAporteParafiscalesSalud
     *
     * @param string $cotizanteExoneradoPagoAporteParafiscalesSalud
     *
     * @return RhuSsoAporte
     */
    public function setCotizanteExoneradoPagoAporteParafiscalesSalud($cotizanteExoneradoPagoAporteParafiscalesSalud)
    {
        $this->cotizanteExoneradoPagoAporteParafiscalesSalud = $cotizanteExoneradoPagoAporteParafiscalesSalud;

        return $this;
    }

    /**
     * Get cotizanteExoneradoPagoAporteParafiscalesSalud
     *
     * @return string
     */
    public function getCotizanteExoneradoPagoAporteParafiscalesSalud()
    {
        return $this->cotizanteExoneradoPagoAporteParafiscalesSalud;
    }

    /**
     * Set codigoAdministradoraRiesgosLaborales
     *
     * @param string $codigoAdministradoraRiesgosLaborales
     *
     * @return RhuSsoAporte
     */
    public function setCodigoAdministradoraRiesgosLaborales($codigoAdministradoraRiesgosLaborales)
    {
        $this->codigoAdministradoraRiesgosLaborales = $codigoAdministradoraRiesgosLaborales;

        return $this;
    }

    /**
     * Get codigoAdministradoraRiesgosLaborales
     *
     * @return string
     */
    public function getCodigoAdministradoraRiesgosLaborales()
    {
        return $this->codigoAdministradoraRiesgosLaborales;
    }

    /**
     * Set claseRiesgoAfiliado
     *
     * @param string $claseRiesgoAfiliado
     *
     * @return RhuSsoAporte
     */
    public function setClaseRiesgoAfiliado($claseRiesgoAfiliado)
    {
        $this->claseRiesgoAfiliado = $claseRiesgoAfiliado;

        return $this;
    }

    /**
     * Get claseRiesgoAfiliado
     *
     * @return string
     */
    public function getClaseRiesgoAfiliado()
    {
        return $this->claseRiesgoAfiliado;
    }

    /**
     * Set totalCotizacion
     *
     * @param float $totalCotizacion
     *
     * @return RhuSsoAporte
     */
    public function setTotalCotizacion($totalCotizacion)
    {
        $this->totalCotizacion = $totalCotizacion;

        return $this;
    }

    /**
     * Get totalCotizacion
     *
     * @return float
     */
    public function getTotalCotizacion()
    {
        return $this->totalCotizacion;
    }

    /**
     * Set codigoEntidadPensionFk
     *
     * @param integer $codigoEntidadPensionFk
     *
     * @return RhuSsoAporte
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
     * Set codigoEntidadSaludFk
     *
     * @param integer $codigoEntidadSaludFk
     *
     * @return RhuSsoAporte
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
     * Set codigoEntidadRiesgoFk
     *
     * @param integer $codigoEntidadRiesgoFk
     *
     * @return RhuSsoAporte
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
     * Set codigoEntidadCajaFk
     *
     * @param integer $codigoEntidadCajaFk
     *
     * @return RhuSsoAporte
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
     * Set ssoPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo $ssoPeriodoRel
     *
     * @return RhuSsoAporte
     */
    public function setSsoPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo $ssoPeriodoRel = null)
    {
        $this->ssoPeriodoRel = $ssoPeriodoRel;

        return $this;
    }

    /**
     * Get ssoPeriodoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo
     */
    public function getSsoPeriodoRel()
    {
        return $this->ssoPeriodoRel;
    }

    /**
     * Set ssoPeriodoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodoDetalleRel
     *
     * @return RhuSsoAporte
     */
    public function setSsoPeriodoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodoDetalleRel = null)
    {
        $this->ssoPeriodoDetalleRel = $ssoPeriodoDetalleRel;

        return $this;
    }

    /**
     * Get ssoPeriodoDetalleRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle
     */
    public function getSsoPeriodoDetalleRel()
    {
        return $this->ssoPeriodoDetalleRel;
    }

    /**
     * Set ssoSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal $ssoSucursalRel
     *
     * @return RhuSsoAporte
     */
    public function setSsoSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal $ssoSucursalRel = null)
    {
        $this->ssoSucursalRel = $ssoSucursalRel;

        return $this;
    }

    /**
     * Get ssoSucursalRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal
     */
    public function getSsoSucursalRel()
    {
        return $this->ssoSucursalRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuSsoAporte
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
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuSsoAporte
     */
    public function setContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuSsoAporte
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
     * Set entidadPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension $entidadPensionRel
     *
     * @return RhuSsoAporte
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
     * Set entidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludRel
     *
     * @return RhuSsoAporte
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
     * Set entidadRiesgoProfesionalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional $entidadRiesgoProfesionalRel
     *
     * @return RhuSsoAporte
     */
    public function setEntidadRiesgoProfesionalRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional $entidadRiesgoProfesionalRel = null)
    {
        $this->entidadRiesgoProfesionalRel = $entidadRiesgoProfesionalRel;

        return $this;
    }

    /**
     * Get entidadRiesgoProfesionalRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional
     */
    public function getEntidadRiesgoProfesionalRel()
    {
        return $this->entidadRiesgoProfesionalRel;
    }

    /**
     * Set entidadCajaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja $entidadCajaRel
     *
     * @return RhuSsoAporte
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
     * Set indicadorTarifaEspecialPensiones
     *
     * @param string $indicadorTarifaEspecialPensiones
     *
     * @return RhuSsoAporte
     */
    public function setIndicadorTarifaEspecialPensiones($indicadorTarifaEspecialPensiones)
    {
        $this->indicadorTarifaEspecialPensiones = $indicadorTarifaEspecialPensiones;

        return $this;
    }

    /**
     * Get indicadorTarifaEspecialPensiones
     *
     * @return string
     */
    public function getIndicadorTarifaEspecialPensiones()
    {
        return $this->indicadorTarifaEspecialPensiones;
    }

    /**
     * Set fechaIngreso
     *
     * @param string $fechaIngreso
     *
     * @return RhuSsoAporte
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return string
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * Set fechaRetiro
     *
     * @param string $fechaRetiro
     *
     * @return RhuSsoAporte
     */
    public function setFechaRetiro($fechaRetiro)
    {
        $this->fechaRetiro = $fechaRetiro;

        return $this;
    }

    /**
     * Get fechaRetiro
     *
     * @return string
     */
    public function getFechaRetiro()
    {
        return $this->fechaRetiro;
    }

    /**
     * Set fechaInicioVsp
     *
     * @param string $fechaInicioVsp
     *
     * @return RhuSsoAporte
     */
    public function setFechaInicioVsp($fechaInicioVsp)
    {
        $this->fechaInicioVsp = $fechaInicioVsp;

        return $this;
    }

    /**
     * Get fechaInicioVsp
     *
     * @return string
     */
    public function getFechaInicioVsp()
    {
        return $this->fechaInicioVsp;
    }

    /**
     * Set fechaInicioSln
     *
     * @param string $fechaInicioSln
     *
     * @return RhuSsoAporte
     */
    public function setFechaInicioSln($fechaInicioSln)
    {
        $this->fechaInicioSln = $fechaInicioSln;

        return $this;
    }

    /**
     * Get fechaInicioSln
     *
     * @return string
     */
    public function getFechaInicioSln()
    {
        return $this->fechaInicioSln;
    }

    /**
     * Set fechaFinSln
     *
     * @param string $fechaFinSln
     *
     * @return RhuSsoAporte
     */
    public function setFechaFinSln($fechaFinSln)
    {
        $this->fechaFinSln = $fechaFinSln;

        return $this;
    }

    /**
     * Get fechaFinSln
     *
     * @return string
     */
    public function getFechaFinSln()
    {
        return $this->fechaFinSln;
    }

    /**
     * Set fechaInicioIge
     *
     * @param string $fechaInicioIge
     *
     * @return RhuSsoAporte
     */
    public function setFechaInicioIge($fechaInicioIge)
    {
        $this->fechaInicioIge = $fechaInicioIge;

        return $this;
    }

    /**
     * Get fechaInicioIge
     *
     * @return string
     */
    public function getFechaInicioIge()
    {
        return $this->fechaInicioIge;
    }

    /**
     * Set fechaFinIge
     *
     * @param string $fechaFinIge
     *
     * @return RhuSsoAporte
     */
    public function setFechaFinIge($fechaFinIge)
    {
        $this->fechaFinIge = $fechaFinIge;

        return $this;
    }

    /**
     * Get fechaFinIge
     *
     * @return string
     */
    public function getFechaFinIge()
    {
        return $this->fechaFinIge;
    }

    /**
     * Set fechaInicioLma
     *
     * @param string $fechaInicioLma
     *
     * @return RhuSsoAporte
     */
    public function setFechaInicioLma($fechaInicioLma)
    {
        $this->fechaInicioLma = $fechaInicioLma;

        return $this;
    }

    /**
     * Get fechaInicioLma
     *
     * @return string
     */
    public function getFechaInicioLma()
    {
        return $this->fechaInicioLma;
    }

    /**
     * Set fechaFinLma
     *
     * @param string $fechaFinLma
     *
     * @return RhuSsoAporte
     */
    public function setFechaFinLma($fechaFinLma)
    {
        $this->fechaFinLma = $fechaFinLma;

        return $this;
    }

    /**
     * Get fechaFinLma
     *
     * @return string
     */
    public function getFechaFinLma()
    {
        return $this->fechaFinLma;
    }

    /**
     * Set fechaInicioVacLr
     *
     * @param string $fechaInicioVacLr
     *
     * @return RhuSsoAporte
     */
    public function setFechaInicioVacLr($fechaInicioVacLr)
    {
        $this->fechaInicioVacLr = $fechaInicioVacLr;

        return $this;
    }

    /**
     * Get fechaInicioVacLr
     *
     * @return string
     */
    public function getFechaInicioVacLr()
    {
        return $this->fechaInicioVacLr;
    }

    /**
     * Set fechaFinVacLr
     *
     * @param string $fechaFinVacLr
     *
     * @return RhuSsoAporte
     */
    public function setFechaFinVacLr($fechaFinVacLr)
    {
        $this->fechaFinVacLr = $fechaFinVacLr;

        return $this;
    }

    /**
     * Get fechaFinVacLr
     *
     * @return string
     */
    public function getFechaFinVacLr()
    {
        return $this->fechaFinVacLr;
    }

    /**
     * Set fechaInicioVct
     *
     * @param string $fechaInicioVct
     *
     * @return RhuSsoAporte
     */
    public function setFechaInicioVct($fechaInicioVct)
    {
        $this->fechaInicioVct = $fechaInicioVct;

        return $this;
    }

    /**
     * Get fechaInicioVct
     *
     * @return string
     */
    public function getFechaInicioVct()
    {
        return $this->fechaInicioVct;
    }

    /**
     * Set fechaFinVct
     *
     * @param string $fechaFinVct
     *
     * @return RhuSsoAporte
     */
    public function setFechaFinVct($fechaFinVct)
    {
        $this->fechaFinVct = $fechaFinVct;

        return $this;
    }

    /**
     * Get fechaFinVct
     *
     * @return string
     */
    public function getFechaFinVct()
    {
        return $this->fechaFinVct;
    }

    /**
     * Set fechaInicioIrl
     *
     * @param string $fechaInicioIrl
     *
     * @return RhuSsoAporte
     */
    public function setFechaInicioIrl($fechaInicioIrl)
    {
        $this->fechaInicioIrl = $fechaInicioIrl;

        return $this;
    }

    /**
     * Get fechaInicioIrl
     *
     * @return string
     */
    public function getFechaInicioIrl()
    {
        return $this->fechaInicioIrl;
    }

    /**
     * Set fechaFinIrl
     *
     * @param string $fechaFinIrl
     *
     * @return RhuSsoAporte
     */
    public function setFechaFinIrl($fechaFinIrl)
    {
        $this->fechaFinIrl = $fechaFinIrl;

        return $this;
    }

    /**
     * Get fechaFinIrl
     *
     * @return string
     */
    public function getFechaFinIrl()
    {
        return $this->fechaFinIrl;
    }

    /**
     * Set ibcOtrosParafiscalesDiferentesCcf
     *
     * @param float $ibcOtrosParafiscalesDiferentesCcf
     *
     * @return RhuSsoAporte
     */
    public function setIbcOtrosParafiscalesDiferentesCcf($ibcOtrosParafiscalesDiferentesCcf)
    {
        $this->ibcOtrosParafiscalesDiferentesCcf = $ibcOtrosParafiscalesDiferentesCcf;

        return $this;
    }

    /**
     * Get ibcOtrosParafiscalesDiferentesCcf
     *
     * @return float
     */
    public function getIbcOtrosParafiscalesDiferentesCcf()
    {
        return $this->ibcOtrosParafiscalesDiferentesCcf;
    }

    /**
     * Set numeroHorasLaboradas
     *
     * @param float $numeroHorasLaboradas
     *
     * @return RhuSsoAporte
     */
    public function setNumeroHorasLaboradas($numeroHorasLaboradas)
    {
        $this->numeroHorasLaboradas = $numeroHorasLaboradas;

        return $this;
    }

    /**
     * Get numeroHorasLaboradas
     *
     * @return float
     */
    public function getNumeroHorasLaboradas()
    {
        return $this->numeroHorasLaboradas;
    }
}
