<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_soporte_pago")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurSoportePagoRepository")
 */
class TurSoportePago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_soporte_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSoportePagoPk;         
    
    /**
     * @ORM\Column(name="codigo_soporte_pago_periodo_fk", type="integer", nullable=true)
     */    
    private $codigoSoportePagoPeriodoFk;     
    
    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoFk;    
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;        

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;            
    
    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */    
    private $codigoContratoFk;    
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */    
    private $vrSalario = 0;    

    /**
     * @ORM\Column(name="vr_devengado_pactado", type="float")
     */    
    private $vrDevengadoPactado = 0; 
    
    /**
     * @ORM\Column(name="vr_ajuste_devengado_pactado", type="float")
     */    
    private $vrAjusteDevengadoPactado = 0;     
    
    /**
     * @ORM\Column(name="vr_ajuste_compensacion", type="float")
     */    
    private $vrAjusteCompensacion = 0;     
    
    /**
     * @ORM\Column(name="vr_pago", type="float")
     */    
    private $vrPago = 0;    
    
    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float")
     */    
    private $vrAuxilioTransporte = 0;             
    
    /**
     * @ORM\Column(name="vr_devengado", type="float")
     */    
    private $vrDevengado = 0;     
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean", nullable=true)
     */    
    private $estadoCerrado = false;       
    
    /**     
     * @ORM\Column(name="turno_fijo", type="boolean", nullable=true)
     */    
    private $turnoFijo = false;    

    /**     
     * @ORM\Column(name="descanso_ordinario", type="boolean", nullable=true)
     */    
    private $descansoOrdinario = false; 
    
    /**
     * @ORM\Column(name="descanso", type="float")
     */    
    private $descanso = 0;    

    /**
     * @ORM\Column(name="novedad", type="integer")
     */    
    private $novedad = 0;

    /**
     * @ORM\Column(name="incapacidad", type="integer")
     */    
    private $incapacidad = 0;
    
    /**
     * @ORM\Column(name="licencia", type="integer")
     */    
    private $licencia = 0;    

    /**
     * @ORM\Column(name="licencia_no_remunerada", type="integer")
     */    
    private $licenciaNoRemunerada = 0; 
    
    /**
     * @ORM\Column(name="vacacion", type="integer")
     */    
    private $vacacion = 0;    

    /**
     * @ORM\Column(name="ingreso", type="integer")
     */    
    private $ingreso = 0;
    
    /**
     * @ORM\Column(name="retiro", type="integer")
     */    
    private $retiro = 0;    

    /**
     * @ORM\Column(name="induccion", type="integer")
     */    
    private $induccion = 0;
    
    /**
     * @ORM\Column(name="dias", type="float")
     */    
    private $dias = 0;    
    
    /**
     * @ORM\Column(name="dias_trasnporte", type="integer")
     */    
    private $diasTransporte = 0;    
    
    /**
     * @ORM\Column(name="horas", type="float")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="horas_pago", type="float")
     */    
    private $horasPago = 0;    
    
    /**
     * @ORM\Column(name="horas_descanso", type="float")
     */    
    private $horasDescanso = 0;    

    /**
     * @ORM\Column(name="horas_novedad", type="float")
     */    
    private $horasNovedad = 0;
    
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
     * @ORM\Column(name="horas_descanso_reales", type="float")
     */    
    private $horasDescansoReales = 0;    
    
    /**
     * @ORM\Column(name="horas_diurnas_reales", type="float")
     */    
    private $horasDiurnasReales = 0;     

    /**
     * @ORM\Column(name="horas_nocturnas_reales", type="float")
     */    
    private $horasNocturnasReales = 0;    
        
    /**
     * @ORM\Column(name="horas_festivas_diurnas_reales", type="float")
     */    
    private $horasFestivasDiurnasReales = 0;     

    /**
     * @ORM\Column(name="horas_festivas_nocturnas_reales", type="float")
     */    
    private $horasFestivasNocturnasReales = 0;     
    
    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas_reales", type="float")
     */    
    private $horasExtrasOrdinariasDiurnasReales = 0;    

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas_reales", type="float")
     */    
    private $horasExtrasOrdinariasNocturnasReales = 0;        

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas_reales", type="float")
     */    
    private $horasExtrasFestivasDiurnasReales = 0;    

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas_reales", type="float")
     */    
    private $horasExtrasFestivasNocturnasReales = 0;    
    
    /**     
     * @ORM\Column(name="terminacion_turno", type="integer", nullable=true)
     */    
    private $terminacionTurno;     
    
    /**
     * @ORM\Column(name="diasPeriodoCompensar", type="float")
     */    
    private $diasPeriodoCompensar = 0; 
    
    /**
     * @ORM\Column(name="diasPeriodoDescansoCompensar", type="float")
     */    
    private $diasPeriodoDescansoCompensar = 0;    
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;         
    
    /**
     * @ORM\Column(name="anio", type="integer")
     */    
    private $anio = 0;    
    
    /**
     * @ORM\Column(name="mes", type="integer")
     */    
    private $mes = 0;     
    
    /**
     * @ORM\Column(name="secuencia", type="integer", nullable=true)
     */    
    private $secuencia;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurSoportePagoPeriodo", inversedBy="soportesPagosSoportePagoPeriodoRel")
     * @ORM\JoinColumn(name="codigo_soporte_pago_periodo_fk", referencedColumnName="codigo_soporte_pago_periodo_pk")
     */
    protected $soportePagoPeriodoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurRecurso", inversedBy="soportesPagosRecursoRel")
     * @ORM\JoinColumn(name="codigo_recurso_fk", referencedColumnName="codigo_recurso_pk")
     */
    protected $recursoRel;   
    
   /**
     * @ORM\OneToMany(targetEntity="TurSoportePagoDetalle", mappedBy="soportePagoRel", cascade={"persist", "remove"})
     */
    protected $soportesPagosDetallesSoportePagoRel;     

   /**
     * @ORM\OneToMany(targetEntity="TurProgramacionAlterna", mappedBy="soportePagoRel")
     */
    protected $programacionesAlternasSoportePagoRel;         
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->soportesPagosDetallesSoportePagoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSoportePagoPk
     *
     * @return integer
     */
    public function getCodigoSoportePagoPk()
    {
        return $this->codigoSoportePagoPk;
    }

    /**
     * Set codigoSoportePagoPeriodoFk
     *
     * @param integer $codigoSoportePagoPeriodoFk
     *
     * @return TurSoportePago
     */
    public function setCodigoSoportePagoPeriodoFk($codigoSoportePagoPeriodoFk)
    {
        $this->codigoSoportePagoPeriodoFk = $codigoSoportePagoPeriodoFk;

        return $this;
    }

    /**
     * Get codigoSoportePagoPeriodoFk
     *
     * @return integer
     */
    public function getCodigoSoportePagoPeriodoFk()
    {
        return $this->codigoSoportePagoPeriodoFk;
    }

    /**
     * Set codigoRecursoFk
     *
     * @param integer $codigoRecursoFk
     *
     * @return TurSoportePago
     */
    public function setCodigoRecursoFk($codigoRecursoFk)
    {
        $this->codigoRecursoFk = $codigoRecursoFk;

        return $this;
    }

    /**
     * Get codigoRecursoFk
     *
     * @return integer
     */
    public function getCodigoRecursoFk()
    {
        return $this->codigoRecursoFk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return TurSoportePago
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
     * @return TurSoportePago
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
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return TurSoportePago
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
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return TurSoportePago
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
     * Set vrPago
     *
     * @param float $vrPago
     *
     * @return TurSoportePago
     */
    public function setVrPago($vrPago)
    {
        $this->vrPago = $vrPago;

        return $this;
    }

    /**
     * Get vrPago
     *
     * @return float
     */
    public function getVrPago()
    {
        return $this->vrPago;
    }

    /**
     * Set vrAuxilioTransporte
     *
     * @param float $vrAuxilioTransporte
     *
     * @return TurSoportePago
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
     * Set vrDevengado
     *
     * @param float $vrDevengado
     *
     * @return TurSoportePago
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return TurSoportePago
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Set turnoFijo
     *
     * @param boolean $turnoFijo
     *
     * @return TurSoportePago
     */
    public function setTurnoFijo($turnoFijo)
    {
        $this->turnoFijo = $turnoFijo;

        return $this;
    }

    /**
     * Get turnoFijo
     *
     * @return boolean
     */
    public function getTurnoFijo()
    {
        return $this->turnoFijo;
    }

    /**
     * Set descansoOrdinario
     *
     * @param boolean $descansoOrdinario
     *
     * @return TurSoportePago
     */
    public function setDescansoOrdinario($descansoOrdinario)
    {
        $this->descansoOrdinario = $descansoOrdinario;

        return $this;
    }

    /**
     * Get descansoOrdinario
     *
     * @return boolean
     */
    public function getDescansoOrdinario()
    {
        return $this->descansoOrdinario;
    }

    /**
     * Set descanso
     *
     * @param float $descanso
     *
     * @return TurSoportePago
     */
    public function setDescanso($descanso)
    {
        $this->descanso = $descanso;

        return $this;
    }

    /**
     * Get descanso
     *
     * @return float
     */
    public function getDescanso()
    {
        return $this->descanso;
    }

    /**
     * Set novedad
     *
     * @param integer $novedad
     *
     * @return TurSoportePago
     */
    public function setNovedad($novedad)
    {
        $this->novedad = $novedad;

        return $this;
    }

    /**
     * Get novedad
     *
     * @return integer
     */
    public function getNovedad()
    {
        return $this->novedad;
    }

    /**
     * Set incapacidad
     *
     * @param integer $incapacidad
     *
     * @return TurSoportePago
     */
    public function setIncapacidad($incapacidad)
    {
        $this->incapacidad = $incapacidad;

        return $this;
    }

    /**
     * Get incapacidad
     *
     * @return integer
     */
    public function getIncapacidad()
    {
        return $this->incapacidad;
    }

    /**
     * Set licencia
     *
     * @param integer $licencia
     *
     * @return TurSoportePago
     */
    public function setLicencia($licencia)
    {
        $this->licencia = $licencia;

        return $this;
    }

    /**
     * Get licencia
     *
     * @return integer
     */
    public function getLicencia()
    {
        return $this->licencia;
    }

    /**
     * Set licenciaNoRemunerada
     *
     * @param integer $licenciaNoRemunerada
     *
     * @return TurSoportePago
     */
    public function setLicenciaNoRemunerada($licenciaNoRemunerada)
    {
        $this->licenciaNoRemunerada = $licenciaNoRemunerada;

        return $this;
    }

    /**
     * Get licenciaNoRemunerada
     *
     * @return integer
     */
    public function getLicenciaNoRemunerada()
    {
        return $this->licenciaNoRemunerada;
    }

    /**
     * Set vacacion
     *
     * @param integer $vacacion
     *
     * @return TurSoportePago
     */
    public function setVacacion($vacacion)
    {
        $this->vacacion = $vacacion;

        return $this;
    }

    /**
     * Get vacacion
     *
     * @return integer
     */
    public function getVacacion()
    {
        return $this->vacacion;
    }

    /**
     * Set ingreso
     *
     * @param integer $ingreso
     *
     * @return TurSoportePago
     */
    public function setIngreso($ingreso)
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    /**
     * Get ingreso
     *
     * @return integer
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * Set retiro
     *
     * @param integer $retiro
     *
     * @return TurSoportePago
     */
    public function setRetiro($retiro)
    {
        $this->retiro = $retiro;

        return $this;
    }

    /**
     * Get retiro
     *
     * @return integer
     */
    public function getRetiro()
    {
        return $this->retiro;
    }

    /**
     * Set induccion
     *
     * @param integer $induccion
     *
     * @return TurSoportePago
     */
    public function setInduccion($induccion)
    {
        $this->induccion = $induccion;

        return $this;
    }

    /**
     * Get induccion
     *
     * @return integer
     */
    public function getInduccion()
    {
        return $this->induccion;
    }

    /**
     * Set dias
     *
     * @param float $dias
     *
     * @return TurSoportePago
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return float
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
     * @return TurSoportePago
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
     * Set horas
     *
     * @param float $horas
     *
     * @return TurSoportePago
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return float
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set horasPago
     *
     * @param float $horasPago
     *
     * @return TurSoportePago
     */
    public function setHorasPago($horasPago)
    {
        $this->horasPago = $horasPago;

        return $this;
    }

    /**
     * Get horasPago
     *
     * @return float
     */
    public function getHorasPago()
    {
        return $this->horasPago;
    }

    /**
     * Set horasDescanso
     *
     * @param float $horasDescanso
     *
     * @return TurSoportePago
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
     * @return TurSoportePago
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
     * Set horasDiurnas
     *
     * @param float $horasDiurnas
     *
     * @return TurSoportePago
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
     * @return TurSoportePago
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
     * @return TurSoportePago
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
     * @return TurSoportePago
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
     * @return TurSoportePago
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
     * @return TurSoportePago
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
     * @return TurSoportePago
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
     * @return TurSoportePago
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
     * @return TurSoportePago
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
     * @return TurSoportePago
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
     * @return TurSoportePago
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
     * Set horasDescansoReales
     *
     * @param float $horasDescansoReales
     *
     * @return TurSoportePago
     */
    public function setHorasDescansoReales($horasDescansoReales)
    {
        $this->horasDescansoReales = $horasDescansoReales;

        return $this;
    }

    /**
     * Get horasDescansoReales
     *
     * @return float
     */
    public function getHorasDescansoReales()
    {
        return $this->horasDescansoReales;
    }

    /**
     * Set horasDiurnasReales
     *
     * @param float $horasDiurnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasDiurnasReales($horasDiurnasReales)
    {
        $this->horasDiurnasReales = $horasDiurnasReales;

        return $this;
    }

    /**
     * Get horasDiurnasReales
     *
     * @return float
     */
    public function getHorasDiurnasReales()
    {
        return $this->horasDiurnasReales;
    }

    /**
     * Set horasNocturnasReales
     *
     * @param float $horasNocturnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasNocturnasReales($horasNocturnasReales)
    {
        $this->horasNocturnasReales = $horasNocturnasReales;

        return $this;
    }

    /**
     * Get horasNocturnasReales
     *
     * @return float
     */
    public function getHorasNocturnasReales()
    {
        return $this->horasNocturnasReales;
    }

    /**
     * Set horasFestivasDiurnasReales
     *
     * @param float $horasFestivasDiurnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasFestivasDiurnasReales($horasFestivasDiurnasReales)
    {
        $this->horasFestivasDiurnasReales = $horasFestivasDiurnasReales;

        return $this;
    }

    /**
     * Get horasFestivasDiurnasReales
     *
     * @return float
     */
    public function getHorasFestivasDiurnasReales()
    {
        return $this->horasFestivasDiurnasReales;
    }

    /**
     * Set horasFestivasNocturnasReales
     *
     * @param float $horasFestivasNocturnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasFestivasNocturnasReales($horasFestivasNocturnasReales)
    {
        $this->horasFestivasNocturnasReales = $horasFestivasNocturnasReales;

        return $this;
    }

    /**
     * Get horasFestivasNocturnasReales
     *
     * @return float
     */
    public function getHorasFestivasNocturnasReales()
    {
        return $this->horasFestivasNocturnasReales;
    }

    /**
     * Set horasExtrasOrdinariasDiurnasReales
     *
     * @param float $horasExtrasOrdinariasDiurnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasExtrasOrdinariasDiurnasReales($horasExtrasOrdinariasDiurnasReales)
    {
        $this->horasExtrasOrdinariasDiurnasReales = $horasExtrasOrdinariasDiurnasReales;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasDiurnasReales
     *
     * @return float
     */
    public function getHorasExtrasOrdinariasDiurnasReales()
    {
        return $this->horasExtrasOrdinariasDiurnasReales;
    }

    /**
     * Set horasExtrasOrdinariasNocturnasReales
     *
     * @param float $horasExtrasOrdinariasNocturnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasExtrasOrdinariasNocturnasReales($horasExtrasOrdinariasNocturnasReales)
    {
        $this->horasExtrasOrdinariasNocturnasReales = $horasExtrasOrdinariasNocturnasReales;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasNocturnasReales
     *
     * @return float
     */
    public function getHorasExtrasOrdinariasNocturnasReales()
    {
        return $this->horasExtrasOrdinariasNocturnasReales;
    }

    /**
     * Set horasExtrasFestivasDiurnasReales
     *
     * @param float $horasExtrasFestivasDiurnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasExtrasFestivasDiurnasReales($horasExtrasFestivasDiurnasReales)
    {
        $this->horasExtrasFestivasDiurnasReales = $horasExtrasFestivasDiurnasReales;

        return $this;
    }

    /**
     * Get horasExtrasFestivasDiurnasReales
     *
     * @return float
     */
    public function getHorasExtrasFestivasDiurnasReales()
    {
        return $this->horasExtrasFestivasDiurnasReales;
    }

    /**
     * Set horasExtrasFestivasNocturnasReales
     *
     * @param float $horasExtrasFestivasNocturnasReales
     *
     * @return TurSoportePago
     */
    public function setHorasExtrasFestivasNocturnasReales($horasExtrasFestivasNocturnasReales)
    {
        $this->horasExtrasFestivasNocturnasReales = $horasExtrasFestivasNocturnasReales;

        return $this;
    }

    /**
     * Get horasExtrasFestivasNocturnasReales
     *
     * @return float
     */
    public function getHorasExtrasFestivasNocturnasReales()
    {
        return $this->horasExtrasFestivasNocturnasReales;
    }

    /**
     * Set terminacionTurno
     *
     * @param integer $terminacionTurno
     *
     * @return TurSoportePago
     */
    public function setTerminacionTurno($terminacionTurno)
    {
        $this->terminacionTurno = $terminacionTurno;

        return $this;
    }

    /**
     * Get terminacionTurno
     *
     * @return integer
     */
    public function getTerminacionTurno()
    {
        return $this->terminacionTurno;
    }

    /**
     * Set diasPeriodoCompensar
     *
     * @param float $diasPeriodoCompensar
     *
     * @return TurSoportePago
     */
    public function setDiasPeriodoCompensar($diasPeriodoCompensar)
    {
        $this->diasPeriodoCompensar = $diasPeriodoCompensar;

        return $this;
    }

    /**
     * Get diasPeriodoCompensar
     *
     * @return float
     */
    public function getDiasPeriodoCompensar()
    {
        return $this->diasPeriodoCompensar;
    }

    /**
     * Set diasPeriodoDescansoCompensar
     *
     * @param float $diasPeriodoDescansoCompensar
     *
     * @return TurSoportePago
     */
    public function setDiasPeriodoDescansoCompensar($diasPeriodoDescansoCompensar)
    {
        $this->diasPeriodoDescansoCompensar = $diasPeriodoDescansoCompensar;

        return $this;
    }

    /**
     * Get diasPeriodoDescansoCompensar
     *
     * @return float
     */
    public function getDiasPeriodoDescansoCompensar()
    {
        return $this->diasPeriodoDescansoCompensar;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurSoportePago
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
     * Set anio
     *
     * @param integer $anio
     *
     * @return TurSoportePago
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
     * @return TurSoportePago
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
     * Set soportePagoPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportePagoPeriodoRel
     *
     * @return TurSoportePago
     */
    public function setSoportePagoPeriodoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportePagoPeriodoRel = null)
    {
        $this->soportePagoPeriodoRel = $soportePagoPeriodoRel;

        return $this;
    }

    /**
     * Get soportePagoPeriodoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo
     */
    public function getSoportePagoPeriodoRel()
    {
        return $this->soportePagoPeriodoRel;
    }

    /**
     * Set recursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursoRel
     *
     * @return TurSoportePago
     */
    public function setRecursoRel(\Brasa\TurnoBundle\Entity\TurRecurso $recursoRel = null)
    {
        $this->recursoRel = $recursoRel;

        return $this;
    }

    /**
     * Get recursoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurRecurso
     */
    public function getRecursoRel()
    {
        return $this->recursoRel;
    }

    /**
     * Add soportesPagosDetallesSoportePagoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoRel
     *
     * @return TurSoportePago
     */
    public function addSoportesPagosDetallesSoportePagoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoRel)
    {
        $this->soportesPagosDetallesSoportePagoRel[] = $soportesPagosDetallesSoportePagoRel;

        return $this;
    }

    /**
     * Remove soportesPagosDetallesSoportePagoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoRel
     */
    public function removeSoportesPagosDetallesSoportePagoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoRel)
    {
        $this->soportesPagosDetallesSoportePagoRel->removeElement($soportesPagosDetallesSoportePagoRel);
    }

    /**
     * Get soportesPagosDetallesSoportePagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosDetallesSoportePagoRel()
    {
        return $this->soportesPagosDetallesSoportePagoRel;
    }

    /**
     * Add programacionesAlternasSoportePagoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionAlterna $programacionesAlternasSoportePagoRel
     *
     * @return TurSoportePago
     */
    public function addProgramacionesAlternasSoportePagoRel(\Brasa\TurnoBundle\Entity\TurProgramacionAlterna $programacionesAlternasSoportePagoRel)
    {
        $this->programacionesAlternasSoportePagoRel[] = $programacionesAlternasSoportePagoRel;

        return $this;
    }

    /**
     * Remove programacionesAlternasSoportePagoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionAlterna $programacionesAlternasSoportePagoRel
     */
    public function removeProgramacionesAlternasSoportePagoRel(\Brasa\TurnoBundle\Entity\TurProgramacionAlterna $programacionesAlternasSoportePagoRel)
    {
        $this->programacionesAlternasSoportePagoRel->removeElement($programacionesAlternasSoportePagoRel);
    }

    /**
     * Get programacionesAlternasSoportePagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesAlternasSoportePagoRel()
    {
        return $this->programacionesAlternasSoportePagoRel;
    }

    /**
     * Set vrDevengadoPactado
     *
     * @param float $vrDevengadoPactado
     *
     * @return TurSoportePago
     */
    public function setVrDevengadoPactado($vrDevengadoPactado)
    {
        $this->vrDevengadoPactado = $vrDevengadoPactado;

        return $this;
    }

    /**
     * Get vrDevengadoPactado
     *
     * @return float
     */
    public function getVrDevengadoPactado()
    {
        return $this->vrDevengadoPactado;
    }

    /**
     * Set vrAjusteDevengadoPactado
     *
     * @param float $vrAjusteDevengadoPactado
     *
     * @return TurSoportePago
     */
    public function setVrAjusteDevengadoPactado($vrAjusteDevengadoPactado)
    {
        $this->vrAjusteDevengadoPactado = $vrAjusteDevengadoPactado;

        return $this;
    }

    /**
     * Get vrAjusteDevengadoPactado
     *
     * @return float
     */
    public function getVrAjusteDevengadoPactado()
    {
        return $this->vrAjusteDevengadoPactado;
    }

    /**
     * Set vrAjusteCompensacion
     *
     * @param float $vrAjusteCompensacion
     *
     * @return TurSoportePago
     */
    public function setVrAjusteCompensacion($vrAjusteCompensacion)
    {
        $this->vrAjusteCompensacion = $vrAjusteCompensacion;

        return $this;
    }

    /**
     * Get vrAjusteCompensacion
     *
     * @return float
     */
    public function getVrAjusteCompensacion()
    {
        return $this->vrAjusteCompensacion;
    }

    /**
     * Set secuencia
     *
     * @param integer $secuencia
     *
     * @return TurSoportePago
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
}
