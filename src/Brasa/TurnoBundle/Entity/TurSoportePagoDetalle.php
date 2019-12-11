<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_soporte_pago_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurSoportePagoDetalleRepository")
 */
class TurSoportePagoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_soporte_pago_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSoportePagoDetallePk;         
    
    /**
     * @ORM\Column(name="codigo_soporte_pago_fk", type="integer", nullable=true)
     */    
    private $codigoSoportePagoFk;    
    
    /**
     * @ORM\Column(name="codigo_soporte_pago_periodo_fk", type="integer", nullable=true)
     */    
    private $codigoSoportePagoPeriodoFk;     
    
    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoFk;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;        
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    
    
    /**
     * @ORM\Column(name="descanso", type="boolean")
     */    
    private $descanso = false;     

    /**
     * @ORM\Column(name="novedad", type="boolean")
     */    
    private $novedad = false;    
    
    /**
     * @ORM\Column(name="incapacidad", type="boolean")
     */    
    private $incapacidad = false;
    
    /**
     * @ORM\Column(name="licencia", type="boolean")
     */    
    private $licencia = false;     
    
    /**
     * @ORM\Column(name="licencia_no_remunerada", type="integer")
     */    
    private $licenciaNoRemunerada = 0;    
    
    /**
     * @ORM\Column(name="vacacion", type="boolean")
     */    
    private $vacacion = false;    

    /**
     * @ORM\Column(name="ingreso", type="boolean")
     */    
    private $ingreso = false; 
    
    /**
     * @ORM\Column(name="retiro", type="boolean")
     */    
    private $retiro = false;     
    
    /**
     * @ORM\Column(name="induccion", type="integer")
     */    
    private $induccion = 0;    
    
    /**
     * @ORM\Column(name="dias", type="float")
     */    
    private $dias = 0;     
    
    /**
     * @ORM\Column(name="horas", type="float")
     */    
    private $horas = 0;    
    
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
     * @ORM\Column(name="codigo_turno_fk", type="string", length=5)
     */    
    private $codigoTurnoFk;    

    /**
     * @ORM\Column(name="codigo_programacion_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionDetalleFk;   
    
    /**
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoPedidoDetalleFk;       
    
    /**
     * @ORM\Column(name="anio", type="integer")
     */    
    private $anio = 0;    
    
    /**
     * @ORM\Column(name="mes", type="integer")
     */    
    private $mes = 0;     
    
    /**
     * @ORM\Column(name="festivo", type="boolean")
     */    
    private $festivo = false;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurSoportePago", inversedBy="soportesPagosDetallesSoportePagoRel")
     * @ORM\JoinColumn(name="codigo_soporte_pago_fk", referencedColumnName="codigo_soporte_pago_pk")
     */
    protected $soportePagoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="TurSoportePagoPeriodo", inversedBy="soportesPagosDetallesSoportePagoPeriodoRel")
     * @ORM\JoinColumn(name="codigo_soporte_pago_periodo_fk", referencedColumnName="codigo_soporte_pago_periodo_pk")
     */
    protected $soportePagoPeriodoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurRecurso", inversedBy="soportesPagosDetallesRecursoRel")
     * @ORM\JoinColumn(name="codigo_recurso_fk", referencedColumnName="codigo_recurso_pk")
     */
    protected $recursoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="TurProgramacionDetalle", inversedBy="soportesPagosDetallesProgramacionDetalleRel")
     * @ORM\JoinColumn(name="codigo_programacion_detalle_fk", referencedColumnName="codigo_programacion_detalle_pk")
     */
    protected $programacionDetalleRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoDetalle", inversedBy="soportesPagosDetallesPedidoDetalleRel")
     * @ORM\JoinColumn(name="codigo_pedido_detalle_fk", referencedColumnName="codigo_pedido_detalle_pk")
     */
    protected $pedidoDetalleRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurTurno", inversedBy="soportesPagosDetallesTurnoRel")
     * @ORM\JoinColumn(name="codigo_turno_fk", referencedColumnName="codigo_turno_pk")
     */
    protected $turnoRel;    
    


    /**
     * Get codigoSoportePagoDetallePk
     *
     * @return integer
     */
    public function getCodigoSoportePagoDetallePk()
    {
        return $this->codigoSoportePagoDetallePk;
    }

    /**
     * Set codigoSoportePagoFk
     *
     * @param integer $codigoSoportePagoFk
     *
     * @return TurSoportePagoDetalle
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
     * Set codigoSoportePagoPeriodoFk
     *
     * @param integer $codigoSoportePagoPeriodoFk
     *
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TurSoportePagoDetalle
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return TurSoportePagoDetalle
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
     * Set descanso
     *
     * @param boolean $descanso
     *
     * @return TurSoportePagoDetalle
     */
    public function setDescanso($descanso)
    {
        $this->descanso = $descanso;

        return $this;
    }

    /**
     * Get descanso
     *
     * @return boolean
     */
    public function getDescanso()
    {
        return $this->descanso;
    }

    /**
     * Set novedad
     *
     * @param boolean $novedad
     *
     * @return TurSoportePagoDetalle
     */
    public function setNovedad($novedad)
    {
        $this->novedad = $novedad;

        return $this;
    }

    /**
     * Get novedad
     *
     * @return boolean
     */
    public function getNovedad()
    {
        return $this->novedad;
    }

    /**
     * Set incapacidad
     *
     * @param boolean $incapacidad
     *
     * @return TurSoportePagoDetalle
     */
    public function setIncapacidad($incapacidad)
    {
        $this->incapacidad = $incapacidad;

        return $this;
    }

    /**
     * Get incapacidad
     *
     * @return boolean
     */
    public function getIncapacidad()
    {
        return $this->incapacidad;
    }

    /**
     * Set licencia
     *
     * @param boolean $licencia
     *
     * @return TurSoportePagoDetalle
     */
    public function setLicencia($licencia)
    {
        $this->licencia = $licencia;

        return $this;
    }

    /**
     * Get licencia
     *
     * @return boolean
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
     * @return TurSoportePagoDetalle
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
     * @param boolean $vacacion
     *
     * @return TurSoportePagoDetalle
     */
    public function setVacacion($vacacion)
    {
        $this->vacacion = $vacacion;

        return $this;
    }

    /**
     * Get vacacion
     *
     * @return boolean
     */
    public function getVacacion()
    {
        return $this->vacacion;
    }

    /**
     * Set ingreso
     *
     * @param boolean $ingreso
     *
     * @return TurSoportePagoDetalle
     */
    public function setIngreso($ingreso)
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    /**
     * Get ingreso
     *
     * @return boolean
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * Set retiro
     *
     * @param boolean $retiro
     *
     * @return TurSoportePagoDetalle
     */
    public function setRetiro($retiro)
    {
        $this->retiro = $retiro;

        return $this;
    }

    /**
     * Get retiro
     *
     * @return boolean
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
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * Set horas
     *
     * @param float $horas
     *
     * @return TurSoportePagoDetalle
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
     * Set horasDiurnas
     *
     * @param float $horasDiurnas
     *
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * Set codigoTurnoFk
     *
     * @param string $codigoTurnoFk
     *
     * @return TurSoportePagoDetalle
     */
    public function setCodigoTurnoFk($codigoTurnoFk)
    {
        $this->codigoTurnoFk = $codigoTurnoFk;

        return $this;
    }

    /**
     * Get codigoTurnoFk
     *
     * @return string
     */
    public function getCodigoTurnoFk()
    {
        return $this->codigoTurnoFk;
    }

    /**
     * Set codigoProgramacionDetalleFk
     *
     * @param integer $codigoProgramacionDetalleFk
     *
     * @return TurSoportePagoDetalle
     */
    public function setCodigoProgramacionDetalleFk($codigoProgramacionDetalleFk)
    {
        $this->codigoProgramacionDetalleFk = $codigoProgramacionDetalleFk;

        return $this;
    }

    /**
     * Get codigoProgramacionDetalleFk
     *
     * @return integer
     */
    public function getCodigoProgramacionDetalleFk()
    {
        return $this->codigoProgramacionDetalleFk;
    }

    /**
     * Set codigoPedidoDetalleFk
     *
     * @param integer $codigoPedidoDetalleFk
     *
     * @return TurSoportePagoDetalle
     */
    public function setCodigoPedidoDetalleFk($codigoPedidoDetalleFk)
    {
        $this->codigoPedidoDetalleFk = $codigoPedidoDetalleFk;

        return $this;
    }

    /**
     * Get codigoPedidoDetalleFk
     *
     * @return integer
     */
    public function getCodigoPedidoDetalleFk()
    {
        return $this->codigoPedidoDetalleFk;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * Set festivo
     *
     * @param boolean $festivo
     *
     * @return TurSoportePagoDetalle
     */
    public function setFestivo($festivo)
    {
        $this->festivo = $festivo;

        return $this;
    }

    /**
     * Get festivo
     *
     * @return boolean
     */
    public function getFestivo()
    {
        return $this->festivo;
    }

    /**
     * Set soportePagoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePago $soportePagoRel
     *
     * @return TurSoportePagoDetalle
     */
    public function setSoportePagoRel(\Brasa\TurnoBundle\Entity\TurSoportePago $soportePagoRel = null)
    {
        $this->soportePagoRel = $soportePagoRel;

        return $this;
    }

    /**
     * Get soportePagoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurSoportePago
     */
    public function getSoportePagoRel()
    {
        return $this->soportePagoRel;
    }

    /**
     * Set soportePagoPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportePagoPeriodoRel
     *
     * @return TurSoportePagoDetalle
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
     * @return TurSoportePagoDetalle
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
     * Set programacionDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionDetalleRel
     *
     * @return TurSoportePagoDetalle
     */
    public function setProgramacionDetalleRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionDetalleRel = null)
    {
        $this->programacionDetalleRel = $programacionDetalleRel;

        return $this;
    }

    /**
     * Get programacionDetalleRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurProgramacionDetalle
     */
    public function getProgramacionDetalleRel()
    {
        return $this->programacionDetalleRel;
    }

    /**
     * Set pedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidoDetalleRel
     *
     * @return TurSoportePagoDetalle
     */
    public function setPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidoDetalleRel = null)
    {
        $this->pedidoDetalleRel = $pedidoDetalleRel;

        return $this;
    }

    /**
     * Get pedidoDetalleRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPedidoDetalle
     */
    public function getPedidoDetalleRel()
    {
        return $this->pedidoDetalleRel;
    }

    /**
     * Set turnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurTurno $turnoRel
     *
     * @return TurSoportePagoDetalle
     */
    public function setTurnoRel(\Brasa\TurnoBundle\Entity\TurTurno $turnoRel = null)
    {
        $this->turnoRel = $turnoRel;

        return $this;
    }

    /**
     * Get turnoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurTurno
     */
    public function getTurnoRel()
    {
        return $this->turnoRel;
    }
}
