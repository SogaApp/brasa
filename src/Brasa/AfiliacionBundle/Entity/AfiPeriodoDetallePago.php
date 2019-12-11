<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_periodo_detalle_pago")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiPeriodoDetallePagoRepository")
 */
class AfiPeriodoDetallePago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_detalle_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoDetallePagoPk;

    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer")
     */
    private $codigoPeriodoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
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
    private $tipo_documento;

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
     * @ORM\Column(name="dias_incapacidad_laboral", type="integer")
     */
    private $diasIncapacidadLaboral = 0;

    /**
     * @ORM\Column(name="codigo_entidad_pension_pertenece", type="string", length=6)
     */
    private $codigoEntidadPensionPertenece;

    /**
     * @ORM\Column(name="codigo_entidad_pension_traslada", type="string", length=6)
     */
    private $codigoEntidadPensionTraslada = '      ';

    /**
     * @ORM\Column(name="codigo_entidad_salud_pertenece", type="string", length=6)
     */
    private $codigoEntidadSaludPertenece;

    /**
     * @ORM\Column(name="codigo_entidad_salud_traslada", type="string", length=6)
     */
    private $codigoEntidadSaludTraslada = '      ';

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
     * @ORM\Column(name="salario_integral", type="string", length=1)
     */
    private $salarioIntegral = ' ';

    /**
     * @ORM\Column(name="suplementario", type="float")
     */
    private $suplementario = 0;

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
     * @ORM\Column(name="aporte_voluntario_fondo_pensiones_obligatorias", type="float")
     */
    private $aporteVoluntarioFondoPensionesObligatorias = 0;

    /**
     * @ORM\Column(name="cotizacion_voluntario_fondo_pensiones_obligatorias", type="float")
     */
    private $cotizacionVoluntarioFondoPensionesObligatorias = 0;

    /**
     * @ORM\Column(name="total_cotizacion", type="float")
     */
    private $totalCotizacion = 0;

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
     * @ORM\Column(name="codigo_sucursal_fk", type="integer", nullable=true)
     */
    private $codigoSucursalFk;

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
     * @ORM\ManyToOne(targetEntity="AfiPeriodo", inversedBy="periodosDetallesPagosPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $periodoRel;

    /**
     * @ORM\ManyToOne(targetEntity="AfiEmpleado", inversedBy="periodosDetallesPagosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="AfiContrato", inversedBy="periodosDetallesPagosContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="AfiSucursal", inversedBy="periodosDetallesPagosSucursalRel")
     * @ORM\JoinColumn(name="codigo_sucursal_fk", referencedColumnName="codigo_sucursal_pk")
     */
    protected $sucursalRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiPeriodoDetallePagoDetalle", mappedBy="periodoDetallePagoRel")
     */
    protected $periodosDetallesPagosDetallesPeriodoDetallePagoRel;

    /**
     * Get codigoPeriodoDetallePagoPk
     *
     * @return integer
     */
    public function getCodigoPeriodoDetallePagoPk()
    {
        return $this->codigoPeriodoDetallePagoPk;
    }

    /**
     * Set codigoPeriodoFk
     *
     * @param integer $codigoPeriodoFk
     *
     * @return AfiPeriodoDetallePago
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipo_documento = $tipoDocumento;

        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return string
     */
    public function getTipoDocumento()
    {
        return $this->tipo_documento;
    }

    /**
     * Set tipoCotizante
     *
     * @param integer $tipoCotizante
     *
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * Set diasIncapacidadLaboral
     *
     * @param integer $diasIncapacidadLaboral
     *
     * @return AfiPeriodoDetallePago
     */
    public function setDiasIncapacidadLaboral($diasIncapacidadLaboral)
    {
        $this->diasIncapacidadLaboral = $diasIncapacidadLaboral;

        return $this;
    }

    /**
     * Get diasIncapacidadLaboral
     *
     * @return integer
     */
    public function getDiasIncapacidadLaboral()
    {
        return $this->diasIncapacidadLaboral;
    }

    /**
     * Set codigoEntidadPensionPertenece
     *
     * @param string $codigoEntidadPensionPertenece
     *
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * Set salarioIntegral
     *
     * @param string $salarioIntegral
     *
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * Set ibcPension
     *
     * @param float $ibcPension
     *
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @param float $aporteVoluntarioFondoPensionesObligatorias
     *
     * @return AfiPeriodoDetallePago
     */
    public function setAporteVoluntarioFondoPensionesObligatorias($aporteVoluntarioFondoPensionesObligatorias)
    {
        $this->aporteVoluntarioFondoPensionesObligatorias = $aporteVoluntarioFondoPensionesObligatorias;

        return $this;
    }

    /**
     * Get aporteVoluntarioFondoPensionesObligatorias
     *
     * @return float
     */
    public function getAporteVoluntarioFondoPensionesObligatorias()
    {
        return $this->aporteVoluntarioFondoPensionesObligatorias;
    }

    /**
     * Set cotizacionVoluntarioFondoPensionesObligatorias
     *
     * @param float $cotizacionVoluntarioFondoPensionesObligatorias
     *
     * @return AfiPeriodoDetallePago
     */
    public function setCotizacionVoluntarioFondoPensionesObligatorias($cotizacionVoluntarioFondoPensionesObligatorias)
    {
        $this->cotizacionVoluntarioFondoPensionesObligatorias = $cotizacionVoluntarioFondoPensionesObligatorias;

        return $this;
    }

    /**
     * Get cotizacionVoluntarioFondoPensionesObligatorias
     *
     * @return float
     */
    public function getCotizacionVoluntarioFondoPensionesObligatorias()
    {
        return $this->cotizacionVoluntarioFondoPensionesObligatorias;
    }

    /**
     * Set totalCotizacion
     *
     * @param float $totalCotizacion
     *
     * @return AfiPeriodoDetallePago
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
     * Set aportesFondoSolidaridadPensionalSolidaridad
     *
     * @param float $aportesFondoSolidaridadPensionalSolidaridad
     *
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * Set codigoSucursalFk
     *
     * @param integer $codigoSucursalFk
     *
     * @return AfiPeriodoDetallePago
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
     * Set codigoEntidadPensionFk
     *
     * @param integer $codigoEntidadPensionFk
     *
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * Set indicadorTarifaEspecialPensiones
     *
     * @param string $indicadorTarifaEspecialPensiones
     *
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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
     * @return AfiPeriodoDetallePago
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

    /**
     * Set periodoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodoRel
     *
     * @return AfiPeriodoDetallePago
     */
    public function setPeriodoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodoRel = null)
    {
        $this->periodoRel = $periodoRel;

        return $this;
    }

    /**
     * Get periodoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiPeriodo
     */
    public function getPeriodoRel()
    {
        return $this->periodoRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadoRel
     *
     * @return AfiPeriodoDetallePago
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
     * Set contratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $contratoRel
     *
     * @return AfiPeriodoDetallePago
     */
    public function setContratoRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * Set sucursalRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiSucursal $sucursalRel
     *
     * @return AfiPeriodoDetallePago
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
     * Constructor
     */
    public function __construct()
    {
        $this->periodosDetallesPagosDetallesPeriodoDetallePagoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add periodosDetallesPagosDetallesPeriodoDetallePagoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesPeriodoDetallePagoRel
     *
     * @return AfiPeriodoDetallePago
     */
    public function addPeriodosDetallesPagosDetallesPeriodoDetallePagoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesPeriodoDetallePagoRel)
    {
        $this->periodosDetallesPagosDetallesPeriodoDetallePagoRel[] = $periodosDetallesPagosDetallesPeriodoDetallePagoRel;

        return $this;
    }

    /**
     * Remove periodosDetallesPagosDetallesPeriodoDetallePagoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesPeriodoDetallePagoRel
     */
    public function removePeriodosDetallesPagosDetallesPeriodoDetallePagoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesPeriodoDetallePagoRel)
    {
        $this->periodosDetallesPagosDetallesPeriodoDetallePagoRel->removeElement($periodosDetallesPagosDetallesPeriodoDetallePagoRel);
    }

    /**
     * Get periodosDetallesPagosDetallesPeriodoDetallePagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodosDetallesPagosDetallesPeriodoDetallePagoRel()
    {
        return $this->periodosDetallesPagosDetallesPeriodoDetallePagoRel;
    }
}
