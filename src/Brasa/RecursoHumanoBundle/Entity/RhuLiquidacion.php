<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_liquidacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuLiquidacionRepository")
 */
class RhuLiquidacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_liquidacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLiquidacionPk;            

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;               
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContratoFk;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer")
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="codigo_motivo_terminacion_contrato_fk", type="integer", nullable=true)
     */    
    private $codigoMotivoTerminacionContratoFk;
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta; 
    
    /**
     * @ORM\Column(name="numero_dias", type="string", length=30, nullable=true)
     */    
    private $numeroDias;                 
    
    /**
     * @ORM\Column(name="vr_cesantias", type="float")
     */
    private $VrCesantias = 0;    

    /**
     * @ORM\Column(name="vr_intereses_cesantias", type="float")
     */
    private $VrInteresesCesantias = 0;        

    /**
     * @ORM\Column(name="vr_prima", type="float")
     */
    private $VrPrima = 0;    

    /**
     * @ORM\Column(name="vr_deduccion_prima", type="float")
     */
    private $VrDeduccionPrima = 0;    
    
    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */
    private $VrVacaciones = 0;    
    
    /**
     * @ORM\Column(name="vr_indemnizacion", type="float")
     */
    private $VrIndemnizacion = 0;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;        
    
    /**
     * @ORM\Column(name="dias_cesantias", type="integer")
     */    
    private $diasCesantias = 0;     

    /**
     * @ORM\Column(name="dias_cesantiasAusentismo", type="integer")
     */    
    private $diasCesantiasAusentismo = 0;
    
    /**
     * @ORM\Column(name="dias_vacaciones", type="integer")
     */    
    private $diasVacaciones = 0;                

    /**
     * @ORM\Column(name="dias_vacaciones_ausentismo", type="integer")
     */    
    private $diasVacacionesAusentismo = 0; 
    
    /**
     * @ORM\Column(name="dias_primas", type="integer")
     */    
    private $diasPrimas = 0;           
    
    /**
     * @ORM\Column(name="dias_laborados", type="integer")
     */    
    private $diasLaborados = 0;    
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago", type="date", nullable=true)
     */    
    private $fechaUltimoPago;        
    
    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_adicional", type="float")
     */
    private $VrIngresoBasePrestacionAdicional = 0;        
    
    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_cesantias", type="float")
     */
    private $VrIngresoBasePrestacionCesantias = 0;     

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_primas", type="float")
     */
    private $VrIngresoBasePrestacionPrimas = 0; 
    
    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_cesantias_inicial", type="float")
     */
    private $VrIngresoBasePrestacionCesantiasInicial = 0;     

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_primas_inicial", type="float")
     */
    private $VrIngresoBasePrestacionPrimasInicial = 0;    
    
    /**
     * @ORM\Column(name="dias_adicionales_ibp", type="integer")
     */    
    private $diasAdicionalesIBP = 0;            
    
    /**
     * @ORM\Column(name="vr_base_prestaciones", type="float")
     */
    private $VrBasePrestaciones = 0;    

    /**
     * @ORM\Column(name="vr_base_prestaciones_total", type="float")
     */
    private $VrBasePrestacionesTotal = 0;    
    
    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float")
     */
    private $VrAuxilioTransporte = 0;    
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;     

    /**
     * @ORM\Column(name="vr_salario_promedio_cesantias", type="float")
     */
    private $VrSalarioPromedioCesantias = 0;    

    /**
     * @ORM\Column(name="vr_salario_promedio_primas", type="float")
     */
    private $VrSalarioPromedioPrimas = 0;
    
    /**
     * @ORM\Column(name="vr_salario_vacaciones", type="float")
     */
    private $VrSalarioVacaciones = 0;     
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $VrTotal = 0;    
    
    /**     
     * @ORM\Column(name="liquidar_cesantias", type="boolean")
     */    
    private $liquidarCesantias = 0;

    /**     
     * @ORM\Column(name="liquidar_vacaciones", type="boolean")
     */    
    private $liquidarVacaciones = 0;    

    /**     
     * @ORM\Column(name="liquidar_prima", type="boolean")
     */    
    private $liquidarPrima = 0;        
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_primas", type="date", nullable=true)
     */    
    private $fechaUltimoPagoPrimas;
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_vacaciones", type="date", nullable=true)
     */    
    private $fechaUltimoPagoVacaciones;
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_cesantias", type="date", nullable=true)
     */    
    private $fechaUltimoPagoCesantias;    
    
    /**
     * @ORM\Column(name="vr_deducciones", type="float")
     */
    private $VrDeducciones = 0; 
    
    /**
     * @ORM\Column(name="vr_bonificaciones", type="float")
     */
    private $VrBonificaciones = 0;
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;
    
    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = 0;
    
    /**
     * @ORM\Column(name="fecha_inicio_contrato", type="date", nullable=true)
     */    
    private $fechaInicioContrato;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**     
     * @ORM\Column(name="liquidar_manual", type="boolean")
     */    
    private $liquidarManual = 0;

    /**
     * @ORM\Column(name="estado_pago_generado", type="boolean")
     */
    private $estadoPagoGenerado = 0;
    
    /**
     * @ORM\Column(name="estado_pago_banco", type="boolean")
     */
    private $estadoPagoBanco = 0;    
    
    /**     
     * @ORM\Column(name="liquidar_salario", type="boolean")
     */    
    private $liquidarSalario = 0;    
    
    /**
     * @ORM\Column(name="porcentaje_ibp", type="float")
     */
    private $porcentajeIbp = 100;    
    
    /**     
     * @ORM\Column(name="estado_contabilizado", type="boolean")
     */    
    private $estadoContabilizado = false;    
    
    /**
     * @ORM\Column(name="dias_ausentismo_adicional", type="integer")
     */    
    private $diasAusentismoAdicional = 0;    
    
    /**
     * @ORM\Column(name="vr_salario_vacacion_propuesto", type="float")
     */
    private $VrSalarioVacacionPropuesto = 0;    

    /**
     * @ORM\Column(name="vr_salario_prima_propuesto", type="float")
     */
    private $VrSalarioPrimaPropuesto = 0; 

    /**
     * @ORM\Column(name="vr_salario_cesantias_propuesto", type="float")
     */
    private $VrSalarioCesantiasPropuesto = 0;
    
    /**     
     * @ORM\Column(name="eliminar_ausentismo", type="boolean")
     */    
    private $eliminarAusentismo = false;    
    
    /**
     * @ORM\Column(name="dias_ausentismo_propuesto", type="integer")
     */    
    private $diasAusentismoPropuesto = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="liquidacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="liquidacionesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="liquidacionesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuMotivoTerminacionContrato", inversedBy="liquidacionesMotivoTerminacionContratoRel")
     * @ORM\JoinColumn(name="codigo_motivo_terminacion_contrato_fk", referencedColumnName="codigo_motivo_terminacion_contrato_pk")
     */
    protected $motivoTerminacionRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacionAdicionales", mappedBy="liquidacionRel")
     */
    protected $liquidacionesAdicionalesLiquidacionRel;  
   
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoBancoDetalle", mappedBy="liquidacionRel")
     */
    protected $pagosBancosDetallesLiquidacionRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->liquidacionesAdicionalesLiquidacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosBancosDetallesLiquidacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoLiquidacionPk
     *
     * @return integer
     */
    public function getCodigoLiquidacionPk()
    {
        return $this->codigoLiquidacionPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuLiquidacion
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
     * @return RhuLiquidacion
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
     * @return RhuLiquidacion
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuLiquidacion
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
     * Set codigoMotivoTerminacionContratoFk
     *
     * @param integer $codigoMotivoTerminacionContratoFk
     *
     * @return RhuLiquidacion
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuLiquidacion
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
     * @return RhuLiquidacion
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
     * Set numeroDias
     *
     * @param string $numeroDias
     *
     * @return RhuLiquidacion
     */
    public function setNumeroDias($numeroDias)
    {
        $this->numeroDias = $numeroDias;

        return $this;
    }

    /**
     * Get numeroDias
     *
     * @return string
     */
    public function getNumeroDias()
    {
        return $this->numeroDias;
    }

    /**
     * Set vrCesantias
     *
     * @param float $vrCesantias
     *
     * @return RhuLiquidacion
     */
    public function setVrCesantias($vrCesantias)
    {
        $this->VrCesantias = $vrCesantias;

        return $this;
    }

    /**
     * Get vrCesantias
     *
     * @return float
     */
    public function getVrCesantias()
    {
        return $this->VrCesantias;
    }

    /**
     * Set vrInteresesCesantias
     *
     * @param float $vrInteresesCesantias
     *
     * @return RhuLiquidacion
     */
    public function setVrInteresesCesantias($vrInteresesCesantias)
    {
        $this->VrInteresesCesantias = $vrInteresesCesantias;

        return $this;
    }

    /**
     * Get vrInteresesCesantias
     *
     * @return float
     */
    public function getVrInteresesCesantias()
    {
        return $this->VrInteresesCesantias;
    }

    /**
     * Set vrPrima
     *
     * @param float $vrPrima
     *
     * @return RhuLiquidacion
     */
    public function setVrPrima($vrPrima)
    {
        $this->VrPrima = $vrPrima;

        return $this;
    }

    /**
     * Get vrPrima
     *
     * @return float
     */
    public function getVrPrima()
    {
        return $this->VrPrima;
    }

    /**
     * Set vrDeduccionPrima
     *
     * @param float $vrDeduccionPrima
     *
     * @return RhuLiquidacion
     */
    public function setVrDeduccionPrima($vrDeduccionPrima)
    {
        $this->VrDeduccionPrima = $vrDeduccionPrima;

        return $this;
    }

    /**
     * Get vrDeduccionPrima
     *
     * @return float
     */
    public function getVrDeduccionPrima()
    {
        return $this->VrDeduccionPrima;
    }

    /**
     * Set vrVacaciones
     *
     * @param float $vrVacaciones
     *
     * @return RhuLiquidacion
     */
    public function setVrVacaciones($vrVacaciones)
    {
        $this->VrVacaciones = $vrVacaciones;

        return $this;
    }

    /**
     * Get vrVacaciones
     *
     * @return float
     */
    public function getVrVacaciones()
    {
        return $this->VrVacaciones;
    }

    /**
     * Set vrIndemnizacion
     *
     * @param float $vrIndemnizacion
     *
     * @return RhuLiquidacion
     */
    public function setVrIndemnizacion($vrIndemnizacion)
    {
        $this->VrIndemnizacion = $vrIndemnizacion;

        return $this;
    }

    /**
     * Get vrIndemnizacion
     *
     * @return float
     */
    public function getVrIndemnizacion()
    {
        return $this->VrIndemnizacion;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuLiquidacion
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
     * Set diasCesantias
     *
     * @param integer $diasCesantias
     *
     * @return RhuLiquidacion
     */
    public function setDiasCesantias($diasCesantias)
    {
        $this->diasCesantias = $diasCesantias;

        return $this;
    }

    /**
     * Get diasCesantias
     *
     * @return integer
     */
    public function getDiasCesantias()
    {
        return $this->diasCesantias;
    }

    /**
     * Set diasCesantiasAusentismo
     *
     * @param integer $diasCesantiasAusentismo
     *
     * @return RhuLiquidacion
     */
    public function setDiasCesantiasAusentismo($diasCesantiasAusentismo)
    {
        $this->diasCesantiasAusentismo = $diasCesantiasAusentismo;

        return $this;
    }

    /**
     * Get diasCesantiasAusentismo
     *
     * @return integer
     */
    public function getDiasCesantiasAusentismo()
    {
        return $this->diasCesantiasAusentismo;
    }

    /**
     * Set diasVacaciones
     *
     * @param integer $diasVacaciones
     *
     * @return RhuLiquidacion
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
     * Set diasVacacionesAusentismo
     *
     * @param integer $diasVacacionesAusentismo
     *
     * @return RhuLiquidacion
     */
    public function setDiasVacacionesAusentismo($diasVacacionesAusentismo)
    {
        $this->diasVacacionesAusentismo = $diasVacacionesAusentismo;

        return $this;
    }

    /**
     * Get diasVacacionesAusentismo
     *
     * @return integer
     */
    public function getDiasVacacionesAusentismo()
    {
        return $this->diasVacacionesAusentismo;
    }

    /**
     * Set diasPrimas
     *
     * @param integer $diasPrimas
     *
     * @return RhuLiquidacion
     */
    public function setDiasPrimas($diasPrimas)
    {
        $this->diasPrimas = $diasPrimas;

        return $this;
    }

    /**
     * Get diasPrimas
     *
     * @return integer
     */
    public function getDiasPrimas()
    {
        return $this->diasPrimas;
    }

    /**
     * Set diasLaborados
     *
     * @param integer $diasLaborados
     *
     * @return RhuLiquidacion
     */
    public function setDiasLaborados($diasLaborados)
    {
        $this->diasLaborados = $diasLaborados;

        return $this;
    }

    /**
     * Get diasLaborados
     *
     * @return integer
     */
    public function getDiasLaborados()
    {
        return $this->diasLaborados;
    }

    /**
     * Set fechaUltimoPago
     *
     * @param \DateTime $fechaUltimoPago
     *
     * @return RhuLiquidacion
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
     * Set vrIngresoBasePrestacionAdicional
     *
     * @param float $vrIngresoBasePrestacionAdicional
     *
     * @return RhuLiquidacion
     */
    public function setVrIngresoBasePrestacionAdicional($vrIngresoBasePrestacionAdicional)
    {
        $this->VrIngresoBasePrestacionAdicional = $vrIngresoBasePrestacionAdicional;

        return $this;
    }

    /**
     * Get vrIngresoBasePrestacionAdicional
     *
     * @return float
     */
    public function getVrIngresoBasePrestacionAdicional()
    {
        return $this->VrIngresoBasePrestacionAdicional;
    }

    /**
     * Set vrIngresoBasePrestacionCesantias
     *
     * @param float $vrIngresoBasePrestacionCesantias
     *
     * @return RhuLiquidacion
     */
    public function setVrIngresoBasePrestacionCesantias($vrIngresoBasePrestacionCesantias)
    {
        $this->VrIngresoBasePrestacionCesantias = $vrIngresoBasePrestacionCesantias;

        return $this;
    }

    /**
     * Get vrIngresoBasePrestacionCesantias
     *
     * @return float
     */
    public function getVrIngresoBasePrestacionCesantias()
    {
        return $this->VrIngresoBasePrestacionCesantias;
    }

    /**
     * Set vrIngresoBasePrestacionPrimas
     *
     * @param float $vrIngresoBasePrestacionPrimas
     *
     * @return RhuLiquidacion
     */
    public function setVrIngresoBasePrestacionPrimas($vrIngresoBasePrestacionPrimas)
    {
        $this->VrIngresoBasePrestacionPrimas = $vrIngresoBasePrestacionPrimas;

        return $this;
    }

    /**
     * Get vrIngresoBasePrestacionPrimas
     *
     * @return float
     */
    public function getVrIngresoBasePrestacionPrimas()
    {
        return $this->VrIngresoBasePrestacionPrimas;
    }

    /**
     * Set vrIngresoBasePrestacionCesantiasInicial
     *
     * @param float $vrIngresoBasePrestacionCesantiasInicial
     *
     * @return RhuLiquidacion
     */
    public function setVrIngresoBasePrestacionCesantiasInicial($vrIngresoBasePrestacionCesantiasInicial)
    {
        $this->VrIngresoBasePrestacionCesantiasInicial = $vrIngresoBasePrestacionCesantiasInicial;

        return $this;
    }

    /**
     * Get vrIngresoBasePrestacionCesantiasInicial
     *
     * @return float
     */
    public function getVrIngresoBasePrestacionCesantiasInicial()
    {
        return $this->VrIngresoBasePrestacionCesantiasInicial;
    }

    /**
     * Set vrIngresoBasePrestacionPrimasInicial
     *
     * @param float $vrIngresoBasePrestacionPrimasInicial
     *
     * @return RhuLiquidacion
     */
    public function setVrIngresoBasePrestacionPrimasInicial($vrIngresoBasePrestacionPrimasInicial)
    {
        $this->VrIngresoBasePrestacionPrimasInicial = $vrIngresoBasePrestacionPrimasInicial;

        return $this;
    }

    /**
     * Get vrIngresoBasePrestacionPrimasInicial
     *
     * @return float
     */
    public function getVrIngresoBasePrestacionPrimasInicial()
    {
        return $this->VrIngresoBasePrestacionPrimasInicial;
    }

    /**
     * Set diasAdicionalesIBP
     *
     * @param integer $diasAdicionalesIBP
     *
     * @return RhuLiquidacion
     */
    public function setDiasAdicionalesIBP($diasAdicionalesIBP)
    {
        $this->diasAdicionalesIBP = $diasAdicionalesIBP;

        return $this;
    }

    /**
     * Get diasAdicionalesIBP
     *
     * @return integer
     */
    public function getDiasAdicionalesIBP()
    {
        return $this->diasAdicionalesIBP;
    }

    /**
     * Set vrBasePrestaciones
     *
     * @param float $vrBasePrestaciones
     *
     * @return RhuLiquidacion
     */
    public function setVrBasePrestaciones($vrBasePrestaciones)
    {
        $this->VrBasePrestaciones = $vrBasePrestaciones;

        return $this;
    }

    /**
     * Get vrBasePrestaciones
     *
     * @return float
     */
    public function getVrBasePrestaciones()
    {
        return $this->VrBasePrestaciones;
    }

    /**
     * Set vrBasePrestacionesTotal
     *
     * @param float $vrBasePrestacionesTotal
     *
     * @return RhuLiquidacion
     */
    public function setVrBasePrestacionesTotal($vrBasePrestacionesTotal)
    {
        $this->VrBasePrestacionesTotal = $vrBasePrestacionesTotal;

        return $this;
    }

    /**
     * Get vrBasePrestacionesTotal
     *
     * @return float
     */
    public function getVrBasePrestacionesTotal()
    {
        return $this->VrBasePrestacionesTotal;
    }

    /**
     * Set vrAuxilioTransporte
     *
     * @param float $vrAuxilioTransporte
     *
     * @return RhuLiquidacion
     */
    public function setVrAuxilioTransporte($vrAuxilioTransporte)
    {
        $this->VrAuxilioTransporte = $vrAuxilioTransporte;

        return $this;
    }

    /**
     * Get vrAuxilioTransporte
     *
     * @return float
     */
    public function getVrAuxilioTransporte()
    {
        return $this->VrAuxilioTransporte;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuLiquidacion
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
     * Set vrSalarioPromedioCesantias
     *
     * @param float $vrSalarioPromedioCesantias
     *
     * @return RhuLiquidacion
     */
    public function setVrSalarioPromedioCesantias($vrSalarioPromedioCesantias)
    {
        $this->VrSalarioPromedioCesantias = $vrSalarioPromedioCesantias;

        return $this;
    }

    /**
     * Get vrSalarioPromedioCesantias
     *
     * @return float
     */
    public function getVrSalarioPromedioCesantias()
    {
        return $this->VrSalarioPromedioCesantias;
    }

    /**
     * Set vrSalarioPromedioPrimas
     *
     * @param float $vrSalarioPromedioPrimas
     *
     * @return RhuLiquidacion
     */
    public function setVrSalarioPromedioPrimas($vrSalarioPromedioPrimas)
    {
        $this->VrSalarioPromedioPrimas = $vrSalarioPromedioPrimas;

        return $this;
    }

    /**
     * Get vrSalarioPromedioPrimas
     *
     * @return float
     */
    public function getVrSalarioPromedioPrimas()
    {
        return $this->VrSalarioPromedioPrimas;
    }

    /**
     * Set vrSalarioVacaciones
     *
     * @param float $vrSalarioVacaciones
     *
     * @return RhuLiquidacion
     */
    public function setVrSalarioVacaciones($vrSalarioVacaciones)
    {
        $this->VrSalarioVacaciones = $vrSalarioVacaciones;

        return $this;
    }

    /**
     * Get vrSalarioVacaciones
     *
     * @return float
     */
    public function getVrSalarioVacaciones()
    {
        return $this->VrSalarioVacaciones;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return RhuLiquidacion
     */
    public function setVrTotal($vrTotal)
    {
        $this->VrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->VrTotal;
    }

    /**
     * Set liquidarCesantias
     *
     * @param boolean $liquidarCesantias
     *
     * @return RhuLiquidacion
     */
    public function setLiquidarCesantias($liquidarCesantias)
    {
        $this->liquidarCesantias = $liquidarCesantias;

        return $this;
    }

    /**
     * Get liquidarCesantias
     *
     * @return boolean
     */
    public function getLiquidarCesantias()
    {
        return $this->liquidarCesantias;
    }

    /**
     * Set liquidarVacaciones
     *
     * @param boolean $liquidarVacaciones
     *
     * @return RhuLiquidacion
     */
    public function setLiquidarVacaciones($liquidarVacaciones)
    {
        $this->liquidarVacaciones = $liquidarVacaciones;

        return $this;
    }

    /**
     * Get liquidarVacaciones
     *
     * @return boolean
     */
    public function getLiquidarVacaciones()
    {
        return $this->liquidarVacaciones;
    }

    /**
     * Set liquidarPrima
     *
     * @param boolean $liquidarPrima
     *
     * @return RhuLiquidacion
     */
    public function setLiquidarPrima($liquidarPrima)
    {
        $this->liquidarPrima = $liquidarPrima;

        return $this;
    }

    /**
     * Get liquidarPrima
     *
     * @return boolean
     */
    public function getLiquidarPrima()
    {
        return $this->liquidarPrima;
    }

    /**
     * Set fechaUltimoPagoPrimas
     *
     * @param \DateTime $fechaUltimoPagoPrimas
     *
     * @return RhuLiquidacion
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
     * Set fechaUltimoPagoVacaciones
     *
     * @param \DateTime $fechaUltimoPagoVacaciones
     *
     * @return RhuLiquidacion
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
     * Set fechaUltimoPagoCesantias
     *
     * @param \DateTime $fechaUltimoPagoCesantias
     *
     * @return RhuLiquidacion
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
     * Set vrDeducciones
     *
     * @param float $vrDeducciones
     *
     * @return RhuLiquidacion
     */
    public function setVrDeducciones($vrDeducciones)
    {
        $this->VrDeducciones = $vrDeducciones;

        return $this;
    }

    /**
     * Get vrDeducciones
     *
     * @return float
     */
    public function getVrDeducciones()
    {
        return $this->VrDeducciones;
    }

    /**
     * Set vrBonificaciones
     *
     * @param float $vrBonificaciones
     *
     * @return RhuLiquidacion
     */
    public function setVrBonificaciones($vrBonificaciones)
    {
        $this->VrBonificaciones = $vrBonificaciones;

        return $this;
    }

    /**
     * Get vrBonificaciones
     *
     * @return float
     */
    public function getVrBonificaciones()
    {
        return $this->VrBonificaciones;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuLiquidacion
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
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return RhuLiquidacion
     */
    public function setEstadoGenerado($estadoGenerado)
    {
        $this->estadoGenerado = $estadoGenerado;

        return $this;
    }

    /**
     * Get estadoGenerado
     *
     * @return boolean
     */
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * Set fechaInicioContrato
     *
     * @param \DateTime $fechaInicioContrato
     *
     * @return RhuLiquidacion
     */
    public function setFechaInicioContrato($fechaInicioContrato)
    {
        $this->fechaInicioContrato = $fechaInicioContrato;

        return $this;
    }

    /**
     * Get fechaInicioContrato
     *
     * @return \DateTime
     */
    public function getFechaInicioContrato()
    {
        return $this->fechaInicioContrato;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuLiquidacion
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
     * Set liquidarManual
     *
     * @param boolean $liquidarManual
     *
     * @return RhuLiquidacion
     */
    public function setLiquidarManual($liquidarManual)
    {
        $this->liquidarManual = $liquidarManual;

        return $this;
    }

    /**
     * Get liquidarManual
     *
     * @return boolean
     */
    public function getLiquidarManual()
    {
        return $this->liquidarManual;
    }

    /**
     * Set estadoPagoGenerado
     *
     * @param boolean $estadoPagoGenerado
     *
     * @return RhuLiquidacion
     */
    public function setEstadoPagoGenerado($estadoPagoGenerado)
    {
        $this->estadoPagoGenerado = $estadoPagoGenerado;

        return $this;
    }

    /**
     * Get estadoPagoGenerado
     *
     * @return boolean
     */
    public function getEstadoPagoGenerado()
    {
        return $this->estadoPagoGenerado;
    }

    /**
     * Set estadoPagoBanco
     *
     * @param boolean $estadoPagoBanco
     *
     * @return RhuLiquidacion
     */
    public function setEstadoPagoBanco($estadoPagoBanco)
    {
        $this->estadoPagoBanco = $estadoPagoBanco;

        return $this;
    }

    /**
     * Get estadoPagoBanco
     *
     * @return boolean
     */
    public function getEstadoPagoBanco()
    {
        return $this->estadoPagoBanco;
    }

    /**
     * Set liquidarSalario
     *
     * @param boolean $liquidarSalario
     *
     * @return RhuLiquidacion
     */
    public function setLiquidarSalario($liquidarSalario)
    {
        $this->liquidarSalario = $liquidarSalario;

        return $this;
    }

    /**
     * Get liquidarSalario
     *
     * @return boolean
     */
    public function getLiquidarSalario()
    {
        return $this->liquidarSalario;
    }

    /**
     * Set porcentajeIbp
     *
     * @param float $porcentajeIbp
     *
     * @return RhuLiquidacion
     */
    public function setPorcentajeIbp($porcentajeIbp)
    {
        $this->porcentajeIbp = $porcentajeIbp;

        return $this;
    }

    /**
     * Get porcentajeIbp
     *
     * @return float
     */
    public function getPorcentajeIbp()
    {
        return $this->porcentajeIbp;
    }

    /**
     * Set estadoContabilizado
     *
     * @param boolean $estadoContabilizado
     *
     * @return RhuLiquidacion
     */
    public function setEstadoContabilizado($estadoContabilizado)
    {
        $this->estadoContabilizado = $estadoContabilizado;

        return $this;
    }

    /**
     * Get estadoContabilizado
     *
     * @return boolean
     */
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * Set diasAusentismoAdicional
     *
     * @param integer $diasAusentismoAdicional
     *
     * @return RhuLiquidacion
     */
    public function setDiasAusentismoAdicional($diasAusentismoAdicional)
    {
        $this->diasAusentismoAdicional = $diasAusentismoAdicional;

        return $this;
    }

    /**
     * Get diasAusentismoAdicional
     *
     * @return integer
     */
    public function getDiasAusentismoAdicional()
    {
        return $this->diasAusentismoAdicional;
    }

    /**
     * Set vrSalarioVacacionPropuesto
     *
     * @param float $vrSalarioVacacionPropuesto
     *
     * @return RhuLiquidacion
     */
    public function setVrSalarioVacacionPropuesto($vrSalarioVacacionPropuesto)
    {
        $this->VrSalarioVacacionPropuesto = $vrSalarioVacacionPropuesto;

        return $this;
    }

    /**
     * Get vrSalarioVacacionPropuesto
     *
     * @return float
     */
    public function getVrSalarioVacacionPropuesto()
    {
        return $this->VrSalarioVacacionPropuesto;
    }

    /**
     * Set vrSalarioPrimaPropuesto
     *
     * @param float $vrSalarioPrimaPropuesto
     *
     * @return RhuLiquidacion
     */
    public function setVrSalarioPrimaPropuesto($vrSalarioPrimaPropuesto)
    {
        $this->VrSalarioPrimaPropuesto = $vrSalarioPrimaPropuesto;

        return $this;
    }

    /**
     * Get vrSalarioPrimaPropuesto
     *
     * @return float
     */
    public function getVrSalarioPrimaPropuesto()
    {
        return $this->VrSalarioPrimaPropuesto;
    }

    /**
     * Set vrSalarioCesantiasPropuesto
     *
     * @param float $vrSalarioCesantiasPropuesto
     *
     * @return RhuLiquidacion
     */
    public function setVrSalarioCesantiasPropuesto($vrSalarioCesantiasPropuesto)
    {
        $this->VrSalarioCesantiasPropuesto = $vrSalarioCesantiasPropuesto;

        return $this;
    }

    /**
     * Get vrSalarioCesantiasPropuesto
     *
     * @return float
     */
    public function getVrSalarioCesantiasPropuesto()
    {
        return $this->VrSalarioCesantiasPropuesto;
    }

    /**
     * Set eliminarAusentismo
     *
     * @param boolean $eliminarAusentismo
     *
     * @return RhuLiquidacion
     */
    public function setEliminarAusentismo($eliminarAusentismo)
    {
        $this->eliminarAusentismo = $eliminarAusentismo;

        return $this;
    }

    /**
     * Get eliminarAusentismo
     *
     * @return boolean
     */
    public function getEliminarAusentismo()
    {
        return $this->eliminarAusentismo;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuLiquidacion
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
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuLiquidacion
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
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuLiquidacion
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
     * Set motivoTerminacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuMotivoTerminacionContrato $motivoTerminacionRel
     *
     * @return RhuLiquidacion
     */
    public function setMotivoTerminacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuMotivoTerminacionContrato $motivoTerminacionRel = null)
    {
        $this->motivoTerminacionRel = $motivoTerminacionRel;

        return $this;
    }

    /**
     * Get motivoTerminacionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuMotivoTerminacionContrato
     */
    public function getMotivoTerminacionRel()
    {
        return $this->motivoTerminacionRel;
    }

    /**
     * Add liquidacionesAdicionalesLiquidacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales $liquidacionesAdicionalesLiquidacionRel
     *
     * @return RhuLiquidacion
     */
    public function addLiquidacionesAdicionalesLiquidacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales $liquidacionesAdicionalesLiquidacionRel)
    {
        $this->liquidacionesAdicionalesLiquidacionRel[] = $liquidacionesAdicionalesLiquidacionRel;

        return $this;
    }

    /**
     * Remove liquidacionesAdicionalesLiquidacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales $liquidacionesAdicionalesLiquidacionRel
     */
    public function removeLiquidacionesAdicionalesLiquidacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales $liquidacionesAdicionalesLiquidacionRel)
    {
        $this->liquidacionesAdicionalesLiquidacionRel->removeElement($liquidacionesAdicionalesLiquidacionRel);
    }

    /**
     * Get liquidacionesAdicionalesLiquidacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLiquidacionesAdicionalesLiquidacionRel()
    {
        return $this->liquidacionesAdicionalesLiquidacionRel;
    }

    /**
     * Add pagosBancosDetallesLiquidacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesLiquidacionRel
     *
     * @return RhuLiquidacion
     */
    public function addPagosBancosDetallesLiquidacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesLiquidacionRel)
    {
        $this->pagosBancosDetallesLiquidacionRel[] = $pagosBancosDetallesLiquidacionRel;

        return $this;
    }

    /**
     * Remove pagosBancosDetallesLiquidacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesLiquidacionRel
     */
    public function removePagosBancosDetallesLiquidacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesLiquidacionRel)
    {
        $this->pagosBancosDetallesLiquidacionRel->removeElement($pagosBancosDetallesLiquidacionRel);
    }

    /**
     * Get pagosBancosDetallesLiquidacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosBancosDetallesLiquidacionRel()
    {
        return $this->pagosBancosDetallesLiquidacionRel;
    }

    /**
     * Set diasAusentismoPropuesto
     *
     * @param integer $diasAusentismoPropuesto
     *
     * @return RhuLiquidacion
     */
    public function setDiasAusentismoPropuesto($diasAusentismoPropuesto)
    {
        $this->diasAusentismoPropuesto = $diasAusentismoPropuesto;

        return $this;
    }

    /**
     * Get diasAusentismoPropuesto
     *
     * @return integer
     */
    public function getDiasAusentismoPropuesto()
    {
        return $this->diasAusentismoPropuesto;
    }
}
