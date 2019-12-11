<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_configuracion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuConfiguracionRepository")
 */
class RhuConfiguracion
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     */
    private $codigoConfiguracionPk;

    /**
     * @ORM\Column(name="codigo_entidad_riesgo_fk", type="integer")
     */
    private $codigoEntidadRiesgoFk;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario;

    /**
     * @ORM\Column(name="codigo_auxilio_transporte", type="integer")
     */
    private $codigoAuxilioTransporte;

    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float")
     */
    private $vrAuxilioTransporte;

    /**
     * @ORM\Column(name="codigo_credito", type="integer")
     */
    private $codigoCredito;

    /**
     * @ORM\Column(name="codigo_seguro", type="integer")
     */
    private $codigoSeguro;

    /**
     * @ORM\Column(name="codigo_tiempo_suplementario", type="integer")
     */
    private $codigoTiempoSuplementario;

    /**
     * @ORM\Column(name="codigo_hora_diurna_trabajada", type="integer")
     */
    private $codigoHoraDiurnaTrabajada;

    /**
     * @ORM\Column(name="codigo_salario_integral", type="integer")
     */
    private $codigoSalarioIntegral;    
    
    /**
     * @ORM\Column(name="porcentaje_pension_extra", type="float")
     */
    private $porcentajePensionExtra;

    /**
     * @ORM\Column(name="codigo_incapacidad", type="integer")
     */
    private $codigoIncapacidad;

    /**
     * @ORM\Column(name="anio_actual", type="integer")
     */
    private $anioActual;

    /**
     * @ORM\Column(name="porcentaje_iva", type="float")
     */
    private $porcentajeIva;

    /**
     * @ORM\Column(name="codigo_retencion_fuente", type="integer")
     */
    private $codigoRetencionFuente;

    /**
     * @ORM\Column(name="edad_minima_empleado", type="integer")
     */
    private $edadMinimaEmpleado;

    /**
     * @ORM\Column(name="porcentaje_bonificacion_no_prestacional", type="float")
     */
    private $porcentajeBonificacionNoPrestacional = 40;

    /**
     * @ORM\Column(name="codigo_entidad_examen_ingreso", type="integer")
     */
    private $codigoEntidadExamenIngreso;

    /**
     * @ORM\Column(name="codigo_comprobante_pago_nomina", type="integer")
     */
    private $codigoComprobantePagoNomina;

    /**
     * @ORM\Column(name="codigo_comprobante_provision", type="integer")
     */
    private $codigoComprobanteProvision;    
    
    /**
     * @ORM\Column(name="codigo_comprobante_liquidacion", type="integer")
     */
    private $codigoComprobanteLiquidacion;    
    
    /**
     * @ORM\Column(name="codigo_comprobante_vacacion", type="integer")
     */
    private $codigoComprobanteVacacion;    
    
    /**
     * @ORM\Column(name="codigo_comprobante_pago_banco", type="integer")
     */
    private $codigoComprobantePagoBanco;

    /**
     * @ORM\Column(name="control_pago", type="boolean")
     */
    private $controlPago = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_cesantias", type="float")
     */
    private $prestacionesPorcentajeCesantias = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_intereses_cesantias", type="float")
     */
    private $prestacionesPorcentajeInteresesCesantias = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_primas", type="float")
     */
    private $prestacionesPorcentajePrimas = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_vacaciones", type="float")
     */
    private $prestacionesPorcentajeVacaciones = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_aporte_vacaciones", type="float")
     */
    private $prestacionesPorcentajeAporteVacaciones = 0;    

    /**
     * @ORM\Column(name="prestaciones_porcentaje_indemnizacion", type="float")
     */
    private $prestacionesPorcentajeIndemnizacion = 0; 
    
    /**
     * @ORM\Column(name="aportes_porcentaje_caja", type="float")
     */
    private $aportesPorcentajeCaja = 0;    
    
    /**
     * @ORM\Column(name="aportes_porcentaje_vacaciones", type="float")
     */
    private $aportesPorcentajeVacaciones = 0;
    
    /**
     * @ORM\Column(name="cuenta_nomina_pagar", type="string", length=20, nullable=true)
     */
    private $cuentaNominaPagar;
    
    /**
     * @ORM\Column(name="cuenta_pago", type="string", length=20, nullable=true)
     */
    private $cuentaPago;
    
    /**
     * @ORM\Column(name="codigo_hora_descanso", type="integer")
     */
    private $codigoHoraDescanso;

    /**
     * @ORM\Column(name="codigo_hora_nocturna", type="integer")
     */
    private $codigoHoraNocturna;

    /**
     * @ORM\Column(name="codigo_hora_festiva_diurna", type="integer")
     */
    private $codigoHoraFestivaDiurna;

    /**
     * @ORM\Column(name="codigo_hora_festiva_nocturna", type="integer")
     */
    private $codigoHoraFestivaNocturna;

    /**
     * @ORM\Column(name="codigo_hora_extra_ordinaria_diurna", type="integer")
     */
    private $codigoHoraExtraOrdinariaDiurna;

    /**
     * @ORM\Column(name="codigo_hora_extra_ordinaria_nocturna", type="integer")
     */
    private $codigoHoraExtraOrdinariaNocturna;

    /**
     * @ORM\Column(name="codigo_hora_extra_festiva_diurna", type="integer")
     */
    private $codigoHoraExtraFestivaDiurna;

    /**
     * @ORM\Column(name="codigo_hora_extra_festiva_nocturna", type="integer")
     */
    private $codigoHoraExtraFestivaNocturna;    
    
    /**
     * @ORM\Column(name="codigo_hora_recargo_nocturno", type="integer")
     */
    private $codigoHoraRecargoNocturno;    
    
    /**
     * @ORM\Column(name="codigo_hora_recargo_festivo_diurno", type="integer")
     */
    private $codigoHoraRecargoFestivoDiurno;    
    
    /**
     * @ORM\Column(name="codigo_hora_recargo_festivo_nocturno", type="integer")
     */
    private $codigoHoraRecargoFestivoNocturno;   
    
    /**
     * @ORM\Column(name="codigo_vacacion", type="integer")
     */
    private $codigoVacacion;
    
    /**
     * @ORM\Column(name="codigo_ajuste_devengado", type="integer")
     */
    private $codigoAjusteDevengado;    
    
    /**
     * @ORM\Column(name="afecta_vacaciones_parafiscales", type="boolean")
     */
    private $afectaVacacionesParafiscales = false;
    
    /**
     * @ORM\Column(name="codigo_formato_pago", type="integer")
     */    
    private $codigoFormatoPago = 0;    
    
    /**
     * @ORM\Column(name="codigo_formato_liquidacion", type="integer")
     */    
    private $codigoFormatoLiquidacion = 0;
    
    /**
     * @ORM\Column(name="codigo_formato_carta", type="integer")
     */    
    private $codigoFormatoCarta = 0;
    
    /**
     * @ORM\Column(name="codigo_formato_disciplinario", type="integer")
     */    
    private $codigoFormatoDisciplinario = 0;
    
    /**
     * @ORM\Column(name="codigo_formato_descargo", type="integer")
     */    
    private $codigoFormatoDescargo = 0;
    
    /**
     * Tipo de base para la liquidacion de vacaciones 1-salario 2-salario+prestaciones 3-salario+recargos
     * @ORM\Column(name="tipo_base_pago_vacaciones", type="integer")
     */
    private $tipoBasePagoVacaciones;    
    
    /**
     * Se activa cuando el cliente maneja porcentajes en las liquidaciones
     * @ORM\Column(name="genera_porcentaje_liquidacion", type="boolean")
     */
    private $generaPorcetnajeLiquidacion = false;    
    
    /**
     * @ORM\Column(name="correo_nomina", type="string", length=100, nullable=true)
     */    
    private $correoNomina;  
    
    /**
     * Si esta activado muestra el mensaje en la colilla de pago
     * @ORM\Column(name="imprimir_mensaje_pago", type="boolean")
     */
    private $imprimirMensajePago = false;   
    
    /**
     * Tipo de planilla pago seguridad social s-sucursal u-unica
     * @ORM\Column(name="tipo_planilla_sso", type="string", length=1, nullable=true)
     */
    private $tipoPlanillaSso;
    
    /**
     * @ORM\Column(name="codigo_prima", type="integer")
     */
    private $codigoPrima;     
    
    /**
     * Si en el pago de primas se aplica un porcentaje en el salario
     * @ORM\Column(name="prestaciones_aplicar_porcentaje_salario", type="boolean")
     */
    private $prestacionesAplicaPorcentajeSalario = false;     

    /**
     * @ORM\Column(name="nit_sena", type="string", length=20, nullable=false)
     */
    private $nitSena;    

    /**
     * @ORM\Column(name="nit_icbf", type="string", length=20, nullable=false)
     */
    private $nitIcbf;    
    
    /**
     * Si se tiene en cuenta o no los dias de ausentismo en primas
     * @ORM\Column(name="dias_ausentismo_primas", type="boolean")
     */
    private $diasAusentismoPrimas = false;      
    
    /**
     * Promedio primas utilizado por seracis
     * @ORM\Column(name="promedio_primas_laborado", type="boolean")
     */
    private $promedioPrimasLaborado = false;     
    
    /**
     * Si estos dias tiene valor el sistema divide el promedio en estos dias
     * @ORM\Column(name="promedio_primas_laborado_dias", type="integer")
     */    
    private $promedioPrimasLaboradoDias = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadRiesgoProfesional", inversedBy="configuracionEntidadRiesgoProfesionalRel")
     * @ORM\JoinColumn(name="codigo_entidad_riesgo_fk", referencedColumnName="codigo_entidad_riesgo_pk")
     */
    protected $entidadRiesgoProfesionalRel;  
        
   

    /**
     * Set codigoConfiguracionPk
     *
     * @param integer $codigoConfiguracionPk
     *
     * @return RhuConfiguracion
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk)
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;

        return $this;
    }

    /**
     * Get codigoConfiguracionPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * Set codigoEntidadRiesgoFk
     *
     * @param integer $codigoEntidadRiesgoFk
     *
     * @return RhuConfiguracion
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
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuConfiguracion
     */
    public function setVrSalario($vrSalario)
    {
        $this->vrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * Set codigoAuxilioTransporte
     *
     * @param integer $codigoAuxilioTransporte
     *
     * @return RhuConfiguracion
     */
    public function setCodigoAuxilioTransporte($codigoAuxilioTransporte)
    {
        $this->codigoAuxilioTransporte = $codigoAuxilioTransporte;

        return $this;
    }

    /**
     * Get codigoAuxilioTransporte
     *
     * @return integer
     */
    public function getCodigoAuxilioTransporte()
    {
        return $this->codigoAuxilioTransporte;
    }

    /**
     * Set vrAuxilioTransporte
     *
     * @param float $vrAuxilioTransporte
     *
     * @return RhuConfiguracion
     */
    public function setVrAuxilioTransporte($vrAuxilioTransporte)
    {
        $this->vrAuxilioTransporte = $vrAuxilioTransporte;

        return $this;
    }

    /**
     * Get vrAuxilioTransporte
     *
     * @return float
     */
    public function getVrAuxilioTransporte()
    {
        return $this->vrAuxilioTransporte;
    }

    /**
     * Set codigoCredito
     *
     * @param integer $codigoCredito
     *
     * @return RhuConfiguracion
     */
    public function setCodigoCredito($codigoCredito)
    {
        $this->codigoCredito = $codigoCredito;

        return $this;
    }

    /**
     * Get codigoCredito
     *
     * @return integer
     */
    public function getCodigoCredito()
    {
        return $this->codigoCredito;
    }

    /**
     * Set codigoSeguro
     *
     * @param integer $codigoSeguro
     *
     * @return RhuConfiguracion
     */
    public function setCodigoSeguro($codigoSeguro)
    {
        $this->codigoSeguro = $codigoSeguro;

        return $this;
    }

    /**
     * Get codigoSeguro
     *
     * @return integer
     */
    public function getCodigoSeguro()
    {
        return $this->codigoSeguro;
    }

    /**
     * Set codigoTiempoSuplementario
     *
     * @param integer $codigoTiempoSuplementario
     *
     * @return RhuConfiguracion
     */
    public function setCodigoTiempoSuplementario($codigoTiempoSuplementario)
    {
        $this->codigoTiempoSuplementario = $codigoTiempoSuplementario;

        return $this;
    }

    /**
     * Get codigoTiempoSuplementario
     *
     * @return integer
     */
    public function getCodigoTiempoSuplementario()
    {
        return $this->codigoTiempoSuplementario;
    }

    /**
     * Set codigoHoraDiurnaTrabajada
     *
     * @param integer $codigoHoraDiurnaTrabajada
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraDiurnaTrabajada($codigoHoraDiurnaTrabajada)
    {
        $this->codigoHoraDiurnaTrabajada = $codigoHoraDiurnaTrabajada;

        return $this;
    }

    /**
     * Get codigoHoraDiurnaTrabajada
     *
     * @return integer
     */
    public function getCodigoHoraDiurnaTrabajada()
    {
        return $this->codigoHoraDiurnaTrabajada;
    }

    /**
     * Set codigoSalarioIntegral
     *
     * @param integer $codigoSalarioIntegral
     *
     * @return RhuConfiguracion
     */
    public function setCodigoSalarioIntegral($codigoSalarioIntegral)
    {
        $this->codigoSalarioIntegral = $codigoSalarioIntegral;

        return $this;
    }

    /**
     * Get codigoSalarioIntegral
     *
     * @return integer
     */
    public function getCodigoSalarioIntegral()
    {
        return $this->codigoSalarioIntegral;
    }

    /**
     * Set porcentajePensionExtra
     *
     * @param float $porcentajePensionExtra
     *
     * @return RhuConfiguracion
     */
    public function setPorcentajePensionExtra($porcentajePensionExtra)
    {
        $this->porcentajePensionExtra = $porcentajePensionExtra;

        return $this;
    }

    /**
     * Get porcentajePensionExtra
     *
     * @return float
     */
    public function getPorcentajePensionExtra()
    {
        return $this->porcentajePensionExtra;
    }

    /**
     * Set codigoIncapacidad
     *
     * @param integer $codigoIncapacidad
     *
     * @return RhuConfiguracion
     */
    public function setCodigoIncapacidad($codigoIncapacidad)
    {
        $this->codigoIncapacidad = $codigoIncapacidad;

        return $this;
    }

    /**
     * Get codigoIncapacidad
     *
     * @return integer
     */
    public function getCodigoIncapacidad()
    {
        return $this->codigoIncapacidad;
    }

    /**
     * Set anioActual
     *
     * @param integer $anioActual
     *
     * @return RhuConfiguracion
     */
    public function setAnioActual($anioActual)
    {
        $this->anioActual = $anioActual;

        return $this;
    }

    /**
     * Get anioActual
     *
     * @return integer
     */
    public function getAnioActual()
    {
        return $this->anioActual;
    }

    /**
     * Set porcentajeIva
     *
     * @param float $porcentajeIva
     *
     * @return RhuConfiguracion
     */
    public function setPorcentajeIva($porcentajeIva)
    {
        $this->porcentajeIva = $porcentajeIva;

        return $this;
    }

    /**
     * Get porcentajeIva
     *
     * @return float
     */
    public function getPorcentajeIva()
    {
        return $this->porcentajeIva;
    }

    /**
     * Set codigoRetencionFuente
     *
     * @param integer $codigoRetencionFuente
     *
     * @return RhuConfiguracion
     */
    public function setCodigoRetencionFuente($codigoRetencionFuente)
    {
        $this->codigoRetencionFuente = $codigoRetencionFuente;

        return $this;
    }

    /**
     * Get codigoRetencionFuente
     *
     * @return integer
     */
    public function getCodigoRetencionFuente()
    {
        return $this->codigoRetencionFuente;
    }

    /**
     * Set edadMinimaEmpleado
     *
     * @param integer $edadMinimaEmpleado
     *
     * @return RhuConfiguracion
     */
    public function setEdadMinimaEmpleado($edadMinimaEmpleado)
    {
        $this->edadMinimaEmpleado = $edadMinimaEmpleado;

        return $this;
    }

    /**
     * Get edadMinimaEmpleado
     *
     * @return integer
     */
    public function getEdadMinimaEmpleado()
    {
        return $this->edadMinimaEmpleado;
    }

    /**
     * Set porcentajeBonificacionNoPrestacional
     *
     * @param float $porcentajeBonificacionNoPrestacional
     *
     * @return RhuConfiguracion
     */
    public function setPorcentajeBonificacionNoPrestacional($porcentajeBonificacionNoPrestacional)
    {
        $this->porcentajeBonificacionNoPrestacional = $porcentajeBonificacionNoPrestacional;

        return $this;
    }

    /**
     * Get porcentajeBonificacionNoPrestacional
     *
     * @return float
     */
    public function getPorcentajeBonificacionNoPrestacional()
    {
        return $this->porcentajeBonificacionNoPrestacional;
    }

    /**
     * Set codigoEntidadExamenIngreso
     *
     * @param integer $codigoEntidadExamenIngreso
     *
     * @return RhuConfiguracion
     */
    public function setCodigoEntidadExamenIngreso($codigoEntidadExamenIngreso)
    {
        $this->codigoEntidadExamenIngreso = $codigoEntidadExamenIngreso;

        return $this;
    }

    /**
     * Get codigoEntidadExamenIngreso
     *
     * @return integer
     */
    public function getCodigoEntidadExamenIngreso()
    {
        return $this->codigoEntidadExamenIngreso;
    }

    /**
     * Set codigoComprobantePagoNomina
     *
     * @param integer $codigoComprobantePagoNomina
     *
     * @return RhuConfiguracion
     */
    public function setCodigoComprobantePagoNomina($codigoComprobantePagoNomina)
    {
        $this->codigoComprobantePagoNomina = $codigoComprobantePagoNomina;

        return $this;
    }

    /**
     * Get codigoComprobantePagoNomina
     *
     * @return integer
     */
    public function getCodigoComprobantePagoNomina()
    {
        return $this->codigoComprobantePagoNomina;
    }

    /**
     * Set codigoComprobanteProvision
     *
     * @param integer $codigoComprobanteProvision
     *
     * @return RhuConfiguracion
     */
    public function setCodigoComprobanteProvision($codigoComprobanteProvision)
    {
        $this->codigoComprobanteProvision = $codigoComprobanteProvision;

        return $this;
    }

    /**
     * Get codigoComprobanteProvision
     *
     * @return integer
     */
    public function getCodigoComprobanteProvision()
    {
        return $this->codigoComprobanteProvision;
    }

    /**
     * Set codigoComprobanteLiquidacion
     *
     * @param integer $codigoComprobanteLiquidacion
     *
     * @return RhuConfiguracion
     */
    public function setCodigoComprobanteLiquidacion($codigoComprobanteLiquidacion)
    {
        $this->codigoComprobanteLiquidacion = $codigoComprobanteLiquidacion;

        return $this;
    }

    /**
     * Get codigoComprobanteLiquidacion
     *
     * @return integer
     */
    public function getCodigoComprobanteLiquidacion()
    {
        return $this->codigoComprobanteLiquidacion;
    }

    /**
     * Set codigoComprobanteVacacion
     *
     * @param integer $codigoComprobanteVacacion
     *
     * @return RhuConfiguracion
     */
    public function setCodigoComprobanteVacacion($codigoComprobanteVacacion)
    {
        $this->codigoComprobanteVacacion = $codigoComprobanteVacacion;

        return $this;
    }

    /**
     * Get codigoComprobanteVacacion
     *
     * @return integer
     */
    public function getCodigoComprobanteVacacion()
    {
        return $this->codigoComprobanteVacacion;
    }

    /**
     * Set codigoComprobantePagoBanco
     *
     * @param integer $codigoComprobantePagoBanco
     *
     * @return RhuConfiguracion
     */
    public function setCodigoComprobantePagoBanco($codigoComprobantePagoBanco)
    {
        $this->codigoComprobantePagoBanco = $codigoComprobantePagoBanco;

        return $this;
    }

    /**
     * Get codigoComprobantePagoBanco
     *
     * @return integer
     */
    public function getCodigoComprobantePagoBanco()
    {
        return $this->codigoComprobantePagoBanco;
    }

    /**
     * Set controlPago
     *
     * @param boolean $controlPago
     *
     * @return RhuConfiguracion
     */
    public function setControlPago($controlPago)
    {
        $this->controlPago = $controlPago;

        return $this;
    }

    /**
     * Get controlPago
     *
     * @return boolean
     */
    public function getControlPago()
    {
        return $this->controlPago;
    }

    /**
     * Set prestacionesPorcentajeCesantias
     *
     * @param float $prestacionesPorcentajeCesantias
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajeCesantias($prestacionesPorcentajeCesantias)
    {
        $this->prestacionesPorcentajeCesantias = $prestacionesPorcentajeCesantias;

        return $this;
    }

    /**
     * Get prestacionesPorcentajeCesantias
     *
     * @return float
     */
    public function getPrestacionesPorcentajeCesantias()
    {
        return $this->prestacionesPorcentajeCesantias;
    }

    /**
     * Set prestacionesPorcentajeInteresesCesantias
     *
     * @param float $prestacionesPorcentajeInteresesCesantias
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajeInteresesCesantias($prestacionesPorcentajeInteresesCesantias)
    {
        $this->prestacionesPorcentajeInteresesCesantias = $prestacionesPorcentajeInteresesCesantias;

        return $this;
    }

    /**
     * Get prestacionesPorcentajeInteresesCesantias
     *
     * @return float
     */
    public function getPrestacionesPorcentajeInteresesCesantias()
    {
        return $this->prestacionesPorcentajeInteresesCesantias;
    }

    /**
     * Set prestacionesPorcentajePrimas
     *
     * @param float $prestacionesPorcentajePrimas
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajePrimas($prestacionesPorcentajePrimas)
    {
        $this->prestacionesPorcentajePrimas = $prestacionesPorcentajePrimas;

        return $this;
    }

    /**
     * Get prestacionesPorcentajePrimas
     *
     * @return float
     */
    public function getPrestacionesPorcentajePrimas()
    {
        return $this->prestacionesPorcentajePrimas;
    }

    /**
     * Set prestacionesPorcentajeVacaciones
     *
     * @param float $prestacionesPorcentajeVacaciones
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajeVacaciones($prestacionesPorcentajeVacaciones)
    {
        $this->prestacionesPorcentajeVacaciones = $prestacionesPorcentajeVacaciones;

        return $this;
    }

    /**
     * Get prestacionesPorcentajeVacaciones
     *
     * @return float
     */
    public function getPrestacionesPorcentajeVacaciones()
    {
        return $this->prestacionesPorcentajeVacaciones;
    }

    /**
     * Set prestacionesPorcentajeAporteVacaciones
     *
     * @param float $prestacionesPorcentajeAporteVacaciones
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajeAporteVacaciones($prestacionesPorcentajeAporteVacaciones)
    {
        $this->prestacionesPorcentajeAporteVacaciones = $prestacionesPorcentajeAporteVacaciones;

        return $this;
    }

    /**
     * Get prestacionesPorcentajeAporteVacaciones
     *
     * @return float
     */
    public function getPrestacionesPorcentajeAporteVacaciones()
    {
        return $this->prestacionesPorcentajeAporteVacaciones;
    }

    /**
     * Set prestacionesPorcentajeIndemnizacion
     *
     * @param float $prestacionesPorcentajeIndemnizacion
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajeIndemnizacion($prestacionesPorcentajeIndemnizacion)
    {
        $this->prestacionesPorcentajeIndemnizacion = $prestacionesPorcentajeIndemnizacion;

        return $this;
    }

    /**
     * Get prestacionesPorcentajeIndemnizacion
     *
     * @return float
     */
    public function getPrestacionesPorcentajeIndemnizacion()
    {
        return $this->prestacionesPorcentajeIndemnizacion;
    }

    /**
     * Set aportesPorcentajeCaja
     *
     * @param float $aportesPorcentajeCaja
     *
     * @return RhuConfiguracion
     */
    public function setAportesPorcentajeCaja($aportesPorcentajeCaja)
    {
        $this->aportesPorcentajeCaja = $aportesPorcentajeCaja;

        return $this;
    }

    /**
     * Get aportesPorcentajeCaja
     *
     * @return float
     */
    public function getAportesPorcentajeCaja()
    {
        return $this->aportesPorcentajeCaja;
    }

    /**
     * Set aportesPorcentajeVacaciones
     *
     * @param float $aportesPorcentajeVacaciones
     *
     * @return RhuConfiguracion
     */
    public function setAportesPorcentajeVacaciones($aportesPorcentajeVacaciones)
    {
        $this->aportesPorcentajeVacaciones = $aportesPorcentajeVacaciones;

        return $this;
    }

    /**
     * Get aportesPorcentajeVacaciones
     *
     * @return float
     */
    public function getAportesPorcentajeVacaciones()
    {
        return $this->aportesPorcentajeVacaciones;
    }

    /**
     * Set cuentaNominaPagar
     *
     * @param string $cuentaNominaPagar
     *
     * @return RhuConfiguracion
     */
    public function setCuentaNominaPagar($cuentaNominaPagar)
    {
        $this->cuentaNominaPagar = $cuentaNominaPagar;

        return $this;
    }

    /**
     * Get cuentaNominaPagar
     *
     * @return string
     */
    public function getCuentaNominaPagar()
    {
        return $this->cuentaNominaPagar;
    }

    /**
     * Set cuentaPago
     *
     * @param string $cuentaPago
     *
     * @return RhuConfiguracion
     */
    public function setCuentaPago($cuentaPago)
    {
        $this->cuentaPago = $cuentaPago;

        return $this;
    }

    /**
     * Get cuentaPago
     *
     * @return string
     */
    public function getCuentaPago()
    {
        return $this->cuentaPago;
    }

    /**
     * Set codigoHoraDescanso
     *
     * @param integer $codigoHoraDescanso
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraDescanso($codigoHoraDescanso)
    {
        $this->codigoHoraDescanso = $codigoHoraDescanso;

        return $this;
    }

    /**
     * Get codigoHoraDescanso
     *
     * @return integer
     */
    public function getCodigoHoraDescanso()
    {
        return $this->codigoHoraDescanso;
    }

    /**
     * Set codigoHoraNocturna
     *
     * @param integer $codigoHoraNocturna
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraNocturna($codigoHoraNocturna)
    {
        $this->codigoHoraNocturna = $codigoHoraNocturna;

        return $this;
    }

    /**
     * Get codigoHoraNocturna
     *
     * @return integer
     */
    public function getCodigoHoraNocturna()
    {
        return $this->codigoHoraNocturna;
    }

    /**
     * Set codigoHoraFestivaDiurna
     *
     * @param integer $codigoHoraFestivaDiurna
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraFestivaDiurna($codigoHoraFestivaDiurna)
    {
        $this->codigoHoraFestivaDiurna = $codigoHoraFestivaDiurna;

        return $this;
    }

    /**
     * Get codigoHoraFestivaDiurna
     *
     * @return integer
     */
    public function getCodigoHoraFestivaDiurna()
    {
        return $this->codigoHoraFestivaDiurna;
    }

    /**
     * Set codigoHoraFestivaNocturna
     *
     * @param integer $codigoHoraFestivaNocturna
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraFestivaNocturna($codigoHoraFestivaNocturna)
    {
        $this->codigoHoraFestivaNocturna = $codigoHoraFestivaNocturna;

        return $this;
    }

    /**
     * Get codigoHoraFestivaNocturna
     *
     * @return integer
     */
    public function getCodigoHoraFestivaNocturna()
    {
        return $this->codigoHoraFestivaNocturna;
    }

    /**
     * Set codigoHoraExtraOrdinariaDiurna
     *
     * @param integer $codigoHoraExtraOrdinariaDiurna
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraExtraOrdinariaDiurna($codigoHoraExtraOrdinariaDiurna)
    {
        $this->codigoHoraExtraOrdinariaDiurna = $codigoHoraExtraOrdinariaDiurna;

        return $this;
    }

    /**
     * Get codigoHoraExtraOrdinariaDiurna
     *
     * @return integer
     */
    public function getCodigoHoraExtraOrdinariaDiurna()
    {
        return $this->codigoHoraExtraOrdinariaDiurna;
    }

    /**
     * Set codigoHoraExtraOrdinariaNocturna
     *
     * @param integer $codigoHoraExtraOrdinariaNocturna
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraExtraOrdinariaNocturna($codigoHoraExtraOrdinariaNocturna)
    {
        $this->codigoHoraExtraOrdinariaNocturna = $codigoHoraExtraOrdinariaNocturna;

        return $this;
    }

    /**
     * Get codigoHoraExtraOrdinariaNocturna
     *
     * @return integer
     */
    public function getCodigoHoraExtraOrdinariaNocturna()
    {
        return $this->codigoHoraExtraOrdinariaNocturna;
    }

    /**
     * Set codigoHoraExtraFestivaDiurna
     *
     * @param integer $codigoHoraExtraFestivaDiurna
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraExtraFestivaDiurna($codigoHoraExtraFestivaDiurna)
    {
        $this->codigoHoraExtraFestivaDiurna = $codigoHoraExtraFestivaDiurna;

        return $this;
    }

    /**
     * Get codigoHoraExtraFestivaDiurna
     *
     * @return integer
     */
    public function getCodigoHoraExtraFestivaDiurna()
    {
        return $this->codigoHoraExtraFestivaDiurna;
    }

    /**
     * Set codigoHoraExtraFestivaNocturna
     *
     * @param integer $codigoHoraExtraFestivaNocturna
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraExtraFestivaNocturna($codigoHoraExtraFestivaNocturna)
    {
        $this->codigoHoraExtraFestivaNocturna = $codigoHoraExtraFestivaNocturna;

        return $this;
    }

    /**
     * Get codigoHoraExtraFestivaNocturna
     *
     * @return integer
     */
    public function getCodigoHoraExtraFestivaNocturna()
    {
        return $this->codigoHoraExtraFestivaNocturna;
    }

    /**
     * Set codigoHoraRecargoNocturno
     *
     * @param integer $codigoHoraRecargoNocturno
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraRecargoNocturno($codigoHoraRecargoNocturno)
    {
        $this->codigoHoraRecargoNocturno = $codigoHoraRecargoNocturno;

        return $this;
    }

    /**
     * Get codigoHoraRecargoNocturno
     *
     * @return integer
     */
    public function getCodigoHoraRecargoNocturno()
    {
        return $this->codigoHoraRecargoNocturno;
    }

    /**
     * Set codigoHoraRecargoFestivoDiurno
     *
     * @param integer $codigoHoraRecargoFestivoDiurno
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraRecargoFestivoDiurno($codigoHoraRecargoFestivoDiurno)
    {
        $this->codigoHoraRecargoFestivoDiurno = $codigoHoraRecargoFestivoDiurno;

        return $this;
    }

    /**
     * Get codigoHoraRecargoFestivoDiurno
     *
     * @return integer
     */
    public function getCodigoHoraRecargoFestivoDiurno()
    {
        return $this->codigoHoraRecargoFestivoDiurno;
    }

    /**
     * Set codigoHoraRecargoFestivoNocturno
     *
     * @param integer $codigoHoraRecargoFestivoNocturno
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraRecargoFestivoNocturno($codigoHoraRecargoFestivoNocturno)
    {
        $this->codigoHoraRecargoFestivoNocturno = $codigoHoraRecargoFestivoNocturno;

        return $this;
    }

    /**
     * Get codigoHoraRecargoFestivoNocturno
     *
     * @return integer
     */
    public function getCodigoHoraRecargoFestivoNocturno()
    {
        return $this->codigoHoraRecargoFestivoNocturno;
    }

    /**
     * Set codigoVacacion
     *
     * @param integer $codigoVacacion
     *
     * @return RhuConfiguracion
     */
    public function setCodigoVacacion($codigoVacacion)
    {
        $this->codigoVacacion = $codigoVacacion;

        return $this;
    }

    /**
     * Get codigoVacacion
     *
     * @return integer
     */
    public function getCodigoVacacion()
    {
        return $this->codigoVacacion;
    }

    /**
     * Set codigoAjusteDevengado
     *
     * @param integer $codigoAjusteDevengado
     *
     * @return RhuConfiguracion
     */
    public function setCodigoAjusteDevengado($codigoAjusteDevengado)
    {
        $this->codigoAjusteDevengado = $codigoAjusteDevengado;

        return $this;
    }

    /**
     * Get codigoAjusteDevengado
     *
     * @return integer
     */
    public function getCodigoAjusteDevengado()
    {
        return $this->codigoAjusteDevengado;
    }

    /**
     * Set afectaVacacionesParafiscales
     *
     * @param boolean $afectaVacacionesParafiscales
     *
     * @return RhuConfiguracion
     */
    public function setAfectaVacacionesParafiscales($afectaVacacionesParafiscales)
    {
        $this->afectaVacacionesParafiscales = $afectaVacacionesParafiscales;

        return $this;
    }

    /**
     * Get afectaVacacionesParafiscales
     *
     * @return boolean
     */
    public function getAfectaVacacionesParafiscales()
    {
        return $this->afectaVacacionesParafiscales;
    }

    /**
     * Set codigoFormatoPago
     *
     * @param integer $codigoFormatoPago
     *
     * @return RhuConfiguracion
     */
    public function setCodigoFormatoPago($codigoFormatoPago)
    {
        $this->codigoFormatoPago = $codigoFormatoPago;

        return $this;
    }

    /**
     * Get codigoFormatoPago
     *
     * @return integer
     */
    public function getCodigoFormatoPago()
    {
        return $this->codigoFormatoPago;
    }

    /**
     * Set codigoFormatoLiquidacion
     *
     * @param integer $codigoFormatoLiquidacion
     *
     * @return RhuConfiguracion
     */
    public function setCodigoFormatoLiquidacion($codigoFormatoLiquidacion)
    {
        $this->codigoFormatoLiquidacion = $codigoFormatoLiquidacion;

        return $this;
    }

    /**
     * Get codigoFormatoLiquidacion
     *
     * @return integer
     */
    public function getCodigoFormatoLiquidacion()
    {
        return $this->codigoFormatoLiquidacion;
    }

    /**
     * Set codigoFormatoCarta
     *
     * @param integer $codigoFormatoCarta
     *
     * @return RhuConfiguracion
     */
    public function setCodigoFormatoCarta($codigoFormatoCarta)
    {
        $this->codigoFormatoCarta = $codigoFormatoCarta;

        return $this;
    }

    /**
     * Get codigoFormatoCarta
     *
     * @return integer
     */
    public function getCodigoFormatoCarta()
    {
        return $this->codigoFormatoCarta;
    }

    /**
     * Set codigoFormatoDisciplinario
     *
     * @param integer $codigoFormatoDisciplinario
     *
     * @return RhuConfiguracion
     */
    public function setCodigoFormatoDisciplinario($codigoFormatoDisciplinario)
    {
        $this->codigoFormatoDisciplinario = $codigoFormatoDisciplinario;

        return $this;
    }

    /**
     * Get codigoFormatoDisciplinario
     *
     * @return integer
     */
    public function getCodigoFormatoDisciplinario()
    {
        return $this->codigoFormatoDisciplinario;
    }

    /**
     * Set codigoFormatoDescargo
     *
     * @param integer $codigoFormatoDescargo
     *
     * @return RhuConfiguracion
     */
    public function setCodigoFormatoDescargo($codigoFormatoDescargo)
    {
        $this->codigoFormatoDescargo = $codigoFormatoDescargo;

        return $this;
    }

    /**
     * Get codigoFormatoDescargo
     *
     * @return integer
     */
    public function getCodigoFormatoDescargo()
    {
        return $this->codigoFormatoDescargo;
    }

    /**
     * Set tipoBasePagoVacaciones
     *
     * @param integer $tipoBasePagoVacaciones
     *
     * @return RhuConfiguracion
     */
    public function setTipoBasePagoVacaciones($tipoBasePagoVacaciones)
    {
        $this->tipoBasePagoVacaciones = $tipoBasePagoVacaciones;

        return $this;
    }

    /**
     * Get tipoBasePagoVacaciones
     *
     * @return integer
     */
    public function getTipoBasePagoVacaciones()
    {
        return $this->tipoBasePagoVacaciones;
    }

    /**
     * Set generaPorcetnajeLiquidacion
     *
     * @param boolean $generaPorcetnajeLiquidacion
     *
     * @return RhuConfiguracion
     */
    public function setGeneraPorcetnajeLiquidacion($generaPorcetnajeLiquidacion)
    {
        $this->generaPorcetnajeLiquidacion = $generaPorcetnajeLiquidacion;

        return $this;
    }

    /**
     * Get generaPorcetnajeLiquidacion
     *
     * @return boolean
     */
    public function getGeneraPorcetnajeLiquidacion()
    {
        return $this->generaPorcetnajeLiquidacion;
    }

    /**
     * Set correoNomina
     *
     * @param string $correoNomina
     *
     * @return RhuConfiguracion
     */
    public function setCorreoNomina($correoNomina)
    {
        $this->correoNomina = $correoNomina;

        return $this;
    }

    /**
     * Get correoNomina
     *
     * @return string
     */
    public function getCorreoNomina()
    {
        return $this->correoNomina;
    }

    /**
     * Set imprimirMensajePago
     *
     * @param boolean $imprimirMensajePago
     *
     * @return RhuConfiguracion
     */
    public function setImprimirMensajePago($imprimirMensajePago)
    {
        $this->imprimirMensajePago = $imprimirMensajePago;

        return $this;
    }

    /**
     * Get imprimirMensajePago
     *
     * @return boolean
     */
    public function getImprimirMensajePago()
    {
        return $this->imprimirMensajePago;
    }

    /**
     * Set tipoPlanillaSso
     *
     * @param string $tipoPlanillaSso
     *
     * @return RhuConfiguracion
     */
    public function setTipoPlanillaSso($tipoPlanillaSso)
    {
        $this->tipoPlanillaSso = $tipoPlanillaSso;

        return $this;
    }

    /**
     * Get tipoPlanillaSso
     *
     * @return string
     */
    public function getTipoPlanillaSso()
    {
        return $this->tipoPlanillaSso;
    }

    /**
     * Set codigoPrima
     *
     * @param integer $codigoPrima
     *
     * @return RhuConfiguracion
     */
    public function setCodigoPrima($codigoPrima)
    {
        $this->codigoPrima = $codigoPrima;

        return $this;
    }

    /**
     * Get codigoPrima
     *
     * @return integer
     */
    public function getCodigoPrima()
    {
        return $this->codigoPrima;
    }

    /**
     * Set prestacionesAplicaPorcentajeSalario
     *
     * @param boolean $prestacionesAplicaPorcentajeSalario
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesAplicaPorcentajeSalario($prestacionesAplicaPorcentajeSalario)
    {
        $this->prestacionesAplicaPorcentajeSalario = $prestacionesAplicaPorcentajeSalario;

        return $this;
    }

    /**
     * Get prestacionesAplicaPorcentajeSalario
     *
     * @return boolean
     */
    public function getPrestacionesAplicaPorcentajeSalario()
    {
        return $this->prestacionesAplicaPorcentajeSalario;
    }

    /**
     * Set nitSena
     *
     * @param string $nitSena
     *
     * @return RhuConfiguracion
     */
    public function setNitSena($nitSena)
    {
        $this->nitSena = $nitSena;

        return $this;
    }

    /**
     * Get nitSena
     *
     * @return string
     */
    public function getNitSena()
    {
        return $this->nitSena;
    }

    /**
     * Set nitIcbf
     *
     * @param string $nitIcbf
     *
     * @return RhuConfiguracion
     */
    public function setNitIcbf($nitIcbf)
    {
        $this->nitIcbf = $nitIcbf;

        return $this;
    }

    /**
     * Get nitIcbf
     *
     * @return string
     */
    public function getNitIcbf()
    {
        return $this->nitIcbf;
    }

    /**
     * Set entidadRiesgoProfesionalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional $entidadRiesgoProfesionalRel
     *
     * @return RhuConfiguracion
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
     * Set diasAusentismoPrimas
     *
     * @param boolean $diasAusentismoPrimas
     *
     * @return RhuConfiguracion
     */
    public function setDiasAusentismoPrimas($diasAusentismoPrimas)
    {
        $this->diasAusentismoPrimas = $diasAusentismoPrimas;

        return $this;
    }

    /**
     * Get diasAusentismoPrimas
     *
     * @return boolean
     */
    public function getDiasAusentismoPrimas()
    {
        return $this->diasAusentismoPrimas;
    }

    /**
     * Set promedioPrimasLaborado
     *
     * @param boolean $promedioPrimasLaborado
     *
     * @return RhuConfiguracion
     */
    public function setPromedioPrimasLaborado($promedioPrimasLaborado)
    {
        $this->promedioPrimasLaborado = $promedioPrimasLaborado;

        return $this;
    }

    /**
     * Get promedioPrimasLaborado
     *
     * @return boolean
     */
    public function getPromedioPrimasLaborado()
    {
        return $this->promedioPrimasLaborado;
    }

    /**
     * Set promedioPrimasLaboradoDias
     *
     * @param integer $promedioPrimasLaboradoDias
     *
     * @return RhuConfiguracion
     */
    public function setPromedioPrimasLaboradoDias($promedioPrimasLaboradoDias)
    {
        $this->promedioPrimasLaboradoDias = $promedioPrimasLaboradoDias;

        return $this;
    }

    /**
     * Get promedioPrimasLaboradoDias
     *
     * @return integer
     */
    public function getPromedioPrimasLaboradoDias()
    {
        return $this->promedioPrimasLaboradoDias;
    }
}
