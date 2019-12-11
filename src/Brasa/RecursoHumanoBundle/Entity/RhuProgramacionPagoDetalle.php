<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_programacion_pago_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuProgramacionPagoDetalleRepository")
 */
class RhuProgramacionPagoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_pago_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionPagoDetallePk;
    
    /**
     * @ORM\Column(name="codigo_programacion_pago_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionPagoFk;   
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;           

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */    
    private $codigoContratoFk;    
    
    /**
     * @ORM\Column(name="horas_periodo", type="integer")
     */
    private $horasPeriodo = 0;     

    /**
     * @ORM\Column(name="dias_reales", type="integer")
     */
    private $diasReales = 0;    
    
    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0; 

    /**
     * Para el auxilio de transporte
     * @ORM\Column(name="dias_transporte", type="integer")
     */
    private $diasTransporte = 0;     
    
    /**
     * Para el auxilio de transporte
     * @ORM\Column(name="factor_dia", type="integer")
     */
    private $factor_dia = 0;    
    
    /**
     * @ORM\Column(name="horas_periodo_reales", type="integer")
     */
    private $horasPeriodoReales = 0;    
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;    

    /**
     * @ORM\Column(name="vr_salario_prima", type="float")
     */
    private $vrSalarioPrima = 0;
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;    

    /**
     * @ORM\Column(name="fecha_desde_pago", type="date", nullable=true)
     */    
    private $fechaDesdePago;          
    
    /**
     * @ORM\Column(name="fecha_hasta_pago", type="date", nullable=true)
     */    
    private $fechaHastaPago;    
    
    /**     
     * @ORM\Column(name="indefinido", type="boolean")
     */    
    private $indefinido = 0;    
    
    /**
     * @ORM\Column(name="vr_devengado", type="float")
     */
    private $vrDevengado = 0;    

    /**
     * @ORM\Column(name="vr_deducciones", type="float", nullable=true)
     */
    private $vrDeducciones = 0;     

    /**
     * @ORM\Column(name="vr_creditos", type="float", nullable=true)
     */
    private $vrCreditos = 0;    
    
    /**
     * @ORM\Column(name="vr_neto_pagar", type="float", nullable=true)
     */
    private $vrNetoPagar = 0;     
    
    /**
     * @ORM\Column(name="vr_dia", type="float")
     */
    private $vrDia = 0;    
    
    /**
     * @ORM\Column(name="vr_hora", type="float")
     */
    private $vrHora = 0;    

    /**
     * @ORM\Column(name="descuento_salud", type="boolean")
     */
    private $descuentoSalud = 1;    

    /**
     * @ORM\Column(name="descuento_pension", type="boolean")
     */
    private $descuentoPension = 1;        

    /**
     * @ORM\Column(name="pago_auxilio_transporte", type="boolean")
     */
    private $pagoAuxilioTransporte = 1;            

    /**     
     * @ORM\Column(name="dias_incapacidad", type="integer")
     */
    private $diasIncapacidad = 0;
    
    /**     
     * @ORM\Column(name="dias_licencia", type="integer")
     */
    private $diasLicencia = 0;    
    
    /**     
     * @ORM\Column(name="dias_vacaciones", type="integer")
     */
    private $diasVacaciones = 0;     

    /**
     * @ORM\Column(name="ibc_vacaciones", type="float")
     */    
    private $ibcVacaciones = 0;      
    
    /**     
     * @ORM\Column(name="salario_integral", type="boolean")
     */
    private $salarioIntegral = 0;     
    
    /**
     * @ORM\Column(name="soporte_turno", type="boolean")
     */
    private $soporteTurno = 1;    

    /**
     * @ORM\Column(name="codigo_soporte_pago_fk", type="integer", nullable=true)
     */    
    private $codigoSoportePagoFk; 
    
    /**
     * @ORM\Column(name="horas_diurnas", type="float")
     */    
    private $horasDiurnas = 0;     

    /**
     * @ORM\Column(name="horas_nocturnas", type="float")
     */    
    private $horasNocturnas = 0;    
    
    /**
     * @ORM\Column(name="horas_festivas_diurnas", type="float")
     */    
    private $horasFestivasDiurnas = 0;     

    /**
     * @ORM\Column(name="horas_festivas_nocturnas", type="float")
     */    
    private $horasFestivasNocturnas = 0;    
    
    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas", type="float")
     */    
    private $horasExtrasOrdinariasDiurnas = 0;    

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas", type="float")
     */    
    private $horasExtrasOrdinariasNocturnas = 0;        

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas", type="float")
     */    
    private $horasExtrasFestivasDiurnas = 0;    

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas", type="float")
     */    
    private $horasExtrasFestivasNocturnas = 0;    

    /**
     * @ORM\Column(name="horas_recargo_nocturno", type="float")
     */    
    private $horasRecargoNocturno = 0;    
    
    /**
     * @ORM\Column(name="horas_recargo_festivo_diurno", type="float")
     */    
    private $horasRecargoFestivoDiurno = 0;    
    
    /**
     * @ORM\Column(name="horas_recargo_festivo_nocturno", type="float")
     */    
    private $horasRecargoFestivoNocturno = 0;     
    
    /**
     * @ORM\Column(name="horas_descanso", type="float")
     */    
    private $horasDescanso = 0;     
    
    /**
     * @ORM\Column(name="horas_novedad", type="float")
     */    
    private $horasNovedad = 0;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=150, nullable=true)
     */    
    private $comentarios;    
    
    /**
     * @ORM\Column(name="marca", type="boolean", nullable=true)
     */
    private $marca = 0;    
    
    /**
     * @ORM\Column(name="vr_ajuste_devengado", type="float")
     */
    private $vrAjusteDevengado = 0;      
    
    /**
     * @ORM\Column(name="porcentaje_ibp", type="float")
     */
    private $porcentajeIbp = 0;    
    
    /**
     * @ORM\Column(name="vr_salario_prima_propuesto", type="float")
     */
    private $vrSalarioPrimaPropuesto = 0;    

    /**
     * @ORM\Column(name="dias_ausentismo", type="integer")
     */
    private $diasAusentismo = 0;  
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionPago", inversedBy="programacionesPagosDetallesProgramacionPagoRel")
     * @ORM\JoinColumn(name="codigo_programacion_pago_fk", referencedColumnName="codigo_programacion_pago_pk")
     */
    protected $programacionPagoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="programacionesPagosDetallesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="programacionesPagosDetallesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;          
    
    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionPagoDetalleSede", mappedBy="programacionPagoDetalleRel")
     */
    protected $programacionesPagosDetallesSedesProgramacionPagoDetalleRel; 

    /**
     * @ORM\OneToMany(targetEntity="RhuPagoDetalle", mappedBy="programacionPagoDetalleRel")
     */
    protected $pagosDetallesProgramacionPagoDetalleRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="programacionPagoDetalleRel")
     */
    protected $pagosProgramacionPagoDetalleRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programacionesPagosDetallesSedesProgramacionPagoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosDetallesProgramacionPagoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosProgramacionPagoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoProgramacionPagoDetallePk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoDetallePk()
    {
        return $this->codigoProgramacionPagoDetallePk;
    }

    /**
     * Set codigoProgramacionPagoFk
     *
     * @param integer $codigoProgramacionPagoFk
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setCodigoProgramacionPagoFk($codigoProgramacionPagoFk)
    {
        $this->codigoProgramacionPagoFk = $codigoProgramacionPagoFk;

        return $this;
    }

    /**
     * Get codigoProgramacionPagoFk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoFk()
    {
        return $this->codigoProgramacionPagoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuProgramacionPagoDetalle
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
     * @return RhuProgramacionPagoDetalle
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
     * Set horasPeriodo
     *
     * @param integer $horasPeriodo
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasPeriodo($horasPeriodo)
    {
        $this->horasPeriodo = $horasPeriodo;

        return $this;
    }

    /**
     * Get horasPeriodo
     *
     * @return integer
     */
    public function getHorasPeriodo()
    {
        return $this->horasPeriodo;
    }

    /**
     * Set diasReales
     *
     * @param integer $diasReales
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setDiasReales($diasReales)
    {
        $this->diasReales = $diasReales;

        return $this;
    }

    /**
     * Get diasReales
     *
     * @return integer
     */
    public function getDiasReales()
    {
        return $this->diasReales;
    }

    /**
     * Set dias
     *
     * @param integer $dias
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return integer
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * Set diasTransporte
     *
     * @param integer $diasTransporte
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setDiasTransporte($diasTransporte)
    {
        $this->diasTransporte = $diasTransporte;

        return $this;
    }

    /**
     * Get diasTransporte
     *
     * @return integer
     */
    public function getDiasTransporte()
    {
        return $this->diasTransporte;
    }

    /**
     * Set factorDia
     *
     * @param integer $factorDia
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setFactorDia($factorDia)
    {
        $this->factor_dia = $factorDia;

        return $this;
    }

    /**
     * Get factorDia
     *
     * @return integer
     */
    public function getFactorDia()
    {
        return $this->factor_dia;
    }

    /**
     * Set horasPeriodoReales
     *
     * @param integer $horasPeriodoReales
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasPeriodoReales($horasPeriodoReales)
    {
        $this->horasPeriodoReales = $horasPeriodoReales;

        return $this;
    }

    /**
     * Get horasPeriodoReales
     *
     * @return integer
     */
    public function getHorasPeriodoReales()
    {
        return $this->horasPeriodoReales;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuProgramacionPagoDetalle
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
     * Set vrSalarioPrima
     *
     * @param float $vrSalarioPrima
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setVrSalarioPrima($vrSalarioPrima)
    {
        $this->vrSalarioPrima = $vrSalarioPrima;

        return $this;
    }

    /**
     * Get vrSalarioPrima
     *
     * @return float
     */
    public function getVrSalarioPrima()
    {
        return $this->vrSalarioPrima;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuProgramacionPagoDetalle
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
     * @return RhuProgramacionPagoDetalle
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
     * Set fechaDesdePago
     *
     * @param \DateTime $fechaDesdePago
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setFechaDesdePago($fechaDesdePago)
    {
        $this->fechaDesdePago = $fechaDesdePago;

        return $this;
    }

    /**
     * Get fechaDesdePago
     *
     * @return \DateTime
     */
    public function getFechaDesdePago()
    {
        return $this->fechaDesdePago;
    }

    /**
     * Set fechaHastaPago
     *
     * @param \DateTime $fechaHastaPago
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setFechaHastaPago($fechaHastaPago)
    {
        $this->fechaHastaPago = $fechaHastaPago;

        return $this;
    }

    /**
     * Get fechaHastaPago
     *
     * @return \DateTime
     */
    public function getFechaHastaPago()
    {
        return $this->fechaHastaPago;
    }

    /**
     * Set indefinido
     *
     * @param boolean $indefinido
     *
     * @return RhuProgramacionPagoDetalle
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
     * Set vrDevengado
     *
     * @param float $vrDevengado
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setVrDevengado($vrDevengado)
    {
        $this->vrDevengado = $vrDevengado;

        return $this;
    }

    /**
     * Get vrDevengado
     *
     * @return float
     */
    public function getVrDevengado()
    {
        return $this->vrDevengado;
    }

    /**
     * Set vrDeducciones
     *
     * @param float $vrDeducciones
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setVrDeducciones($vrDeducciones)
    {
        $this->vrDeducciones = $vrDeducciones;

        return $this;
    }

    /**
     * Get vrDeducciones
     *
     * @return float
     */
    public function getVrDeducciones()
    {
        return $this->vrDeducciones;
    }

    /**
     * Set vrCreditos
     *
     * @param float $vrCreditos
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setVrCreditos($vrCreditos)
    {
        $this->vrCreditos = $vrCreditos;

        return $this;
    }

    /**
     * Get vrCreditos
     *
     * @return float
     */
    public function getVrCreditos()
    {
        return $this->vrCreditos;
    }

    /**
     * Set vrNetoPagar
     *
     * @param float $vrNetoPagar
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setVrNetoPagar($vrNetoPagar)
    {
        $this->vrNetoPagar = $vrNetoPagar;

        return $this;
    }

    /**
     * Get vrNetoPagar
     *
     * @return float
     */
    public function getVrNetoPagar()
    {
        return $this->vrNetoPagar;
    }

    /**
     * Set vrDia
     *
     * @param float $vrDia
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setVrDia($vrDia)
    {
        $this->vrDia = $vrDia;

        return $this;
    }

    /**
     * Get vrDia
     *
     * @return float
     */
    public function getVrDia()
    {
        return $this->vrDia;
    }

    /**
     * Set vrHora
     *
     * @param float $vrHora
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setVrHora($vrHora)
    {
        $this->vrHora = $vrHora;

        return $this;
    }

    /**
     * Get vrHora
     *
     * @return float
     */
    public function getVrHora()
    {
        return $this->vrHora;
    }

    /**
     * Set descuentoSalud
     *
     * @param boolean $descuentoSalud
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setDescuentoSalud($descuentoSalud)
    {
        $this->descuentoSalud = $descuentoSalud;

        return $this;
    }

    /**
     * Get descuentoSalud
     *
     * @return boolean
     */
    public function getDescuentoSalud()
    {
        return $this->descuentoSalud;
    }

    /**
     * Set descuentoPension
     *
     * @param boolean $descuentoPension
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setDescuentoPension($descuentoPension)
    {
        $this->descuentoPension = $descuentoPension;

        return $this;
    }

    /**
     * Get descuentoPension
     *
     * @return boolean
     */
    public function getDescuentoPension()
    {
        return $this->descuentoPension;
    }

    /**
     * Set pagoAuxilioTransporte
     *
     * @param boolean $pagoAuxilioTransporte
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setPagoAuxilioTransporte($pagoAuxilioTransporte)
    {
        $this->pagoAuxilioTransporte = $pagoAuxilioTransporte;

        return $this;
    }

    /**
     * Get pagoAuxilioTransporte
     *
     * @return boolean
     */
    public function getPagoAuxilioTransporte()
    {
        return $this->pagoAuxilioTransporte;
    }

    /**
     * Set diasIncapacidad
     *
     * @param integer $diasIncapacidad
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setDiasIncapacidad($diasIncapacidad)
    {
        $this->diasIncapacidad = $diasIncapacidad;

        return $this;
    }

    /**
     * Get diasIncapacidad
     *
     * @return integer
     */
    public function getDiasIncapacidad()
    {
        return $this->diasIncapacidad;
    }

    /**
     * Set diasLicencia
     *
     * @param integer $diasLicencia
     *
     * @return RhuProgramacionPagoDetalle
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
     * Set diasVacaciones
     *
     * @param integer $diasVacaciones
     *
     * @return RhuProgramacionPagoDetalle
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
     * Set ibcVacaciones
     *
     * @param float $ibcVacaciones
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setIbcVacaciones($ibcVacaciones)
    {
        $this->ibcVacaciones = $ibcVacaciones;

        return $this;
    }

    /**
     * Get ibcVacaciones
     *
     * @return float
     */
    public function getIbcVacaciones()
    {
        return $this->ibcVacaciones;
    }

    /**
     * Set salarioIntegral
     *
     * @param boolean $salarioIntegral
     *
     * @return RhuProgramacionPagoDetalle
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
     * Set soporteTurno
     *
     * @param boolean $soporteTurno
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setSoporteTurno($soporteTurno)
    {
        $this->soporteTurno = $soporteTurno;

        return $this;
    }

    /**
     * Get soporteTurno
     *
     * @return boolean
     */
    public function getSoporteTurno()
    {
        return $this->soporteTurno;
    }

    /**
     * Set codigoSoportePagoFk
     *
     * @param integer $codigoSoportePagoFk
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setCodigoSoportePagoFk($codigoSoportePagoFk)
    {
        $this->codigoSoportePagoFk = $codigoSoportePagoFk;

        return $this;
    }

    /**
     * Get codigoSoportePagoFk
     *
     * @return integer
     */
    public function getCodigoSoportePagoFk()
    {
        return $this->codigoSoportePagoFk;
    }

    /**
     * Set horasDiurnas
     *
     * @param float $horasDiurnas
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasDiurnas($horasDiurnas)
    {
        $this->horasDiurnas = $horasDiurnas;

        return $this;
    }

    /**
     * Get horasDiurnas
     *
     * @return float
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * Set horasNocturnas
     *
     * @param float $horasNocturnas
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasNocturnas($horasNocturnas)
    {
        $this->horasNocturnas = $horasNocturnas;

        return $this;
    }

    /**
     * Get horasNocturnas
     *
     * @return float
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * Set horasFestivasDiurnas
     *
     * @param float $horasFestivasDiurnas
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasFestivasDiurnas($horasFestivasDiurnas)
    {
        $this->horasFestivasDiurnas = $horasFestivasDiurnas;

        return $this;
    }

    /**
     * Get horasFestivasDiurnas
     *
     * @return float
     */
    public function getHorasFestivasDiurnas()
    {
        return $this->horasFestivasDiurnas;
    }

    /**
     * Set horasFestivasNocturnas
     *
     * @param float $horasFestivasNocturnas
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasFestivasNocturnas($horasFestivasNocturnas)
    {
        $this->horasFestivasNocturnas = $horasFestivasNocturnas;

        return $this;
    }

    /**
     * Get horasFestivasNocturnas
     *
     * @return float
     */
    public function getHorasFestivasNocturnas()
    {
        return $this->horasFestivasNocturnas;
    }

    /**
     * Set horasExtrasOrdinariasDiurnas
     *
     * @param float $horasExtrasOrdinariasDiurnas
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasExtrasOrdinariasDiurnas($horasExtrasOrdinariasDiurnas)
    {
        $this->horasExtrasOrdinariasDiurnas = $horasExtrasOrdinariasDiurnas;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasDiurnas
     *
     * @return float
     */
    public function getHorasExtrasOrdinariasDiurnas()
    {
        return $this->horasExtrasOrdinariasDiurnas;
    }

    /**
     * Set horasExtrasOrdinariasNocturnas
     *
     * @param float $horasExtrasOrdinariasNocturnas
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasExtrasOrdinariasNocturnas($horasExtrasOrdinariasNocturnas)
    {
        $this->horasExtrasOrdinariasNocturnas = $horasExtrasOrdinariasNocturnas;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasNocturnas
     *
     * @return float
     */
    public function getHorasExtrasOrdinariasNocturnas()
    {
        return $this->horasExtrasOrdinariasNocturnas;
    }

    /**
     * Set horasExtrasFestivasDiurnas
     *
     * @param float $horasExtrasFestivasDiurnas
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasExtrasFestivasDiurnas($horasExtrasFestivasDiurnas)
    {
        $this->horasExtrasFestivasDiurnas = $horasExtrasFestivasDiurnas;

        return $this;
    }

    /**
     * Get horasExtrasFestivasDiurnas
     *
     * @return float
     */
    public function getHorasExtrasFestivasDiurnas()
    {
        return $this->horasExtrasFestivasDiurnas;
    }

    /**
     * Set horasExtrasFestivasNocturnas
     *
     * @param float $horasExtrasFestivasNocturnas
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasExtrasFestivasNocturnas($horasExtrasFestivasNocturnas)
    {
        $this->horasExtrasFestivasNocturnas = $horasExtrasFestivasNocturnas;

        return $this;
    }

    /**
     * Get horasExtrasFestivasNocturnas
     *
     * @return float
     */
    public function getHorasExtrasFestivasNocturnas()
    {
        return $this->horasExtrasFestivasNocturnas;
    }

    /**
     * Set horasRecargoNocturno
     *
     * @param float $horasRecargoNocturno
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasRecargoNocturno($horasRecargoNocturno)
    {
        $this->horasRecargoNocturno = $horasRecargoNocturno;

        return $this;
    }

    /**
     * Get horasRecargoNocturno
     *
     * @return float
     */
    public function getHorasRecargoNocturno()
    {
        return $this->horasRecargoNocturno;
    }

    /**
     * Set horasRecargoFestivoDiurno
     *
     * @param float $horasRecargoFestivoDiurno
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasRecargoFestivoDiurno($horasRecargoFestivoDiurno)
    {
        $this->horasRecargoFestivoDiurno = $horasRecargoFestivoDiurno;

        return $this;
    }

    /**
     * Get horasRecargoFestivoDiurno
     *
     * @return float
     */
    public function getHorasRecargoFestivoDiurno()
    {
        return $this->horasRecargoFestivoDiurno;
    }

    /**
     * Set horasRecargoFestivoNocturno
     *
     * @param float $horasRecargoFestivoNocturno
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasRecargoFestivoNocturno($horasRecargoFestivoNocturno)
    {
        $this->horasRecargoFestivoNocturno = $horasRecargoFestivoNocturno;

        return $this;
    }

    /**
     * Get horasRecargoFestivoNocturno
     *
     * @return float
     */
    public function getHorasRecargoFestivoNocturno()
    {
        return $this->horasRecargoFestivoNocturno;
    }

    /**
     * Set horasDescanso
     *
     * @param float $horasDescanso
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasDescanso($horasDescanso)
    {
        $this->horasDescanso = $horasDescanso;

        return $this;
    }

    /**
     * Get horasDescanso
     *
     * @return float
     */
    public function getHorasDescanso()
    {
        return $this->horasDescanso;
    }

    /**
     * Set horasNovedad
     *
     * @param float $horasNovedad
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasNovedad($horasNovedad)
    {
        $this->horasNovedad = $horasNovedad;

        return $this;
    }

    /**
     * Get horasNovedad
     *
     * @return float
     */
    public function getHorasNovedad()
    {
        return $this->horasNovedad;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuProgramacionPagoDetalle
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
     * Set marca
     *
     * @param boolean $marca
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * Get marca
     *
     * @return boolean
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * Set vrAjusteDevengado
     *
     * @param float $vrAjusteDevengado
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setVrAjusteDevengado($vrAjusteDevengado)
    {
        $this->vrAjusteDevengado = $vrAjusteDevengado;

        return $this;
    }

    /**
     * Get vrAjusteDevengado
     *
     * @return float
     */
    public function getVrAjusteDevengado()
    {
        return $this->vrAjusteDevengado;
    }

    /**
     * Set porcentajeIbp
     *
     * @param float $porcentajeIbp
     *
     * @return RhuProgramacionPagoDetalle
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
     * Set vrSalarioPrimaPropuesto
     *
     * @param float $vrSalarioPrimaPropuesto
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setVrSalarioPrimaPropuesto($vrSalarioPrimaPropuesto)
    {
        $this->vrSalarioPrimaPropuesto = $vrSalarioPrimaPropuesto;

        return $this;
    }

    /**
     * Get vrSalarioPrimaPropuesto
     *
     * @return float
     */
    public function getVrSalarioPrimaPropuesto()
    {
        return $this->vrSalarioPrimaPropuesto;
    }

    /**
     * Set programacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel = null)
    {
        $this->programacionPagoRel = $programacionPagoRel;

        return $this;
    }

    /**
     * Get programacionPagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago
     */
    public function getProgramacionPagoRel()
    {
        return $this->programacionPagoRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuProgramacionPagoDetalle
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
     * @return RhuProgramacionPagoDetalle
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
     * Add programacionesPagosDetallesSedesProgramacionPagoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede $programacionesPagosDetallesSedesProgramacionPagoDetalleRel
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function addProgramacionesPagosDetallesSedesProgramacionPagoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede $programacionesPagosDetallesSedesProgramacionPagoDetalleRel)
    {
        $this->programacionesPagosDetallesSedesProgramacionPagoDetalleRel[] = $programacionesPagosDetallesSedesProgramacionPagoDetalleRel;

        return $this;
    }

    /**
     * Remove programacionesPagosDetallesSedesProgramacionPagoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede $programacionesPagosDetallesSedesProgramacionPagoDetalleRel
     */
    public function removeProgramacionesPagosDetallesSedesProgramacionPagoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede $programacionesPagosDetallesSedesProgramacionPagoDetalleRel)
    {
        $this->programacionesPagosDetallesSedesProgramacionPagoDetalleRel->removeElement($programacionesPagosDetallesSedesProgramacionPagoDetalleRel);
    }

    /**
     * Get programacionesPagosDetallesSedesProgramacionPagoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesPagosDetallesSedesProgramacionPagoDetalleRel()
    {
        return $this->programacionesPagosDetallesSedesProgramacionPagoDetalleRel;
    }

    /**
     * Add pagosDetallesProgramacionPagoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesProgramacionPagoDetalleRel
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function addPagosDetallesProgramacionPagoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesProgramacionPagoDetalleRel)
    {
        $this->pagosDetallesProgramacionPagoDetalleRel[] = $pagosDetallesProgramacionPagoDetalleRel;

        return $this;
    }

    /**
     * Remove pagosDetallesProgramacionPagoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesProgramacionPagoDetalleRel
     */
    public function removePagosDetallesProgramacionPagoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesProgramacionPagoDetalleRel)
    {
        $this->pagosDetallesProgramacionPagoDetalleRel->removeElement($pagosDetallesProgramacionPagoDetalleRel);
    }

    /**
     * Get pagosDetallesProgramacionPagoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosDetallesProgramacionPagoDetalleRel()
    {
        return $this->pagosDetallesProgramacionPagoDetalleRel;
    }

    /**
     * Add pagosProgramacionPagoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosProgramacionPagoDetalleRel
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function addPagosProgramacionPagoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosProgramacionPagoDetalleRel)
    {
        $this->pagosProgramacionPagoDetalleRel[] = $pagosProgramacionPagoDetalleRel;

        return $this;
    }

    /**
     * Remove pagosProgramacionPagoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosProgramacionPagoDetalleRel
     */
    public function removePagosProgramacionPagoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosProgramacionPagoDetalleRel)
    {
        $this->pagosProgramacionPagoDetalleRel->removeElement($pagosProgramacionPagoDetalleRel);
    }

    /**
     * Get pagosProgramacionPagoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosProgramacionPagoDetalleRel()
    {
        return $this->pagosProgramacionPagoDetalleRel;
    }

    /**
     * Set diasAusentismo
     *
     * @param integer $diasAusentismo
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setDiasAusentismo($diasAusentismo)
    {
        $this->diasAusentismo = $diasAusentismo;

        return $this;
    }

    /**
     * Get diasAusentismo
     *
     * @return integer
     */
    public function getDiasAusentismo()
    {
        return $this->diasAusentismo;
    }
}
