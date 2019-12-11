<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_simulacion_detalle_recurso")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurSimulacionDetalleRecursoRepository")
 */
class TurSimulacionDetalleRecurso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_simulacion_detalle_recurso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSimulacionDetalleRecursoPk;                    
    
    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoFk;    
    
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
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;    

    /**
     * @ORM\Column(name="vr_diurnas", type="float")
     */    
    private $vrDiurnas = 0;     

    /**
     * @ORM\Column(name="vr_nocturnas", type="float")
     */    
    private $vrNocturnas = 0;    
    
    /**
     * @ORM\Column(name="vr_festivas_diurnas", type="float")
     */    
    private $vrFestivasDiurnas = 0;     

    /**
     * @ORM\Column(name="vr_festivas_nocturnas", type="float")
     */    
    private $vrFestivasNocturnas = 0;    
    
    /**
     * @ORM\Column(name="vr_extras_ordinarias_diurnas", type="float")
     */    
    private $vrExtrasOrdinariasDiurnas = 0;    

    /**
     * @ORM\Column(name="vr_extras_ordinarias_nocturnas", type="float")
     */    
    private $vrExtrasOrdinariasNocturnas = 0;        

    /**
     * @ORM\Column(name="vr_extras_festivas_diurnas", type="float")
     */    
    private $vrExtrasFestivasDiurnas = 0;    

    /**
     * @ORM\Column(name="vr_extras_festivas_nocturnas", type="float")
     */    
    private $vrExtrasFestivasNocturnas = 0;    

    /**
     * @ORM\Column(name="vr_recargo_nocturno", type="float")
     */    
    private $vrRecargoNocturno = 0;    
    
    /**
     * @ORM\Column(name="vr_recargo_festivo_diurno", type="float")
     */    
    private $vrRecargoFestivoDiurno = 0;    
    
    /**
     * @ORM\Column(name="vr_recargo_festivo_nocturno", type="float")
     */    
    private $vrRecargoFestivoNocturno = 0;    
    
    /**
     * @ORM\Column(name="vr_descanso", type="float")
     */    
    private $vrDescanso = 0;     
    
    /**
     * @ORM\Column(name="vr_devengado", type="float")
     */    
    private $vrDevengado = 0;    

    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float")
     */    
    private $vrAuxilioTransporte = 0;     

    /**
     * Get codigoSimulacionDetalleRecursoPk
     *
     * @return integer
     */
    public function getCodigoSimulacionDetalleRecursoPk()
    {
        return $this->codigoSimulacionDetalleRecursoPk;
    }

    /**
     * Set codigoRecursoFk
     *
     * @param integer $codigoRecursoFk
     *
     * @return TurSimulacionDetalleRecurso
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
     * Set dias
     *
     * @param float $dias
     *
     * @return TurSimulacionDetalleRecurso
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
     * @return TurSimulacionDetalleRecurso
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
     * @return TurSimulacionDetalleRecurso
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
     * @return TurSimulacionDetalleRecurso
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
     * @return TurSimulacionDetalleRecurso
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
     * @return TurSimulacionDetalleRecurso
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
     * @return TurSimulacionDetalleRecurso
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
     * @return TurSimulacionDetalleRecurso
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
     * @return TurSimulacionDetalleRecurso
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
     * @return TurSimulacionDetalleRecurso
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
     * @return TurSimulacionDetalleRecurso
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
     * @return TurSimulacionDetalleRecurso
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
     * @return TurSimulacionDetalleRecurso
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
     * @return TurSimulacionDetalleRecurso
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
     * @return TurSimulacionDetalleRecurso
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
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurSimulacionDetalleRecurso
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
     * Set vrDiurnas
     *
     * @param float $vrDiurnas
     *
     * @return TurSimulacionDetalleRecurso
     */
    public function setVrDiurnas($vrDiurnas)
    {
        $this->vrDiurnas = $vrDiurnas;

        return $this;
    }

    /**
     * Get vrDiurnas
     *
     * @return float
     */
    public function getVrDiurnas()
    {
        return $this->vrDiurnas;
    }

    /**
     * Set vrNocturnas
     *
     * @param float $vrNocturnas
     *
     * @return TurSimulacionDetalleRecurso
     */
    public function setVrNocturnas($vrNocturnas)
    {
        $this->vrNocturnas = $vrNocturnas;

        return $this;
    }

    /**
     * Get vrNocturnas
     *
     * @return float
     */
    public function getVrNocturnas()
    {
        return $this->vrNocturnas;
    }

    /**
     * Set vrFestivasDiurnas
     *
     * @param float $vrFestivasDiurnas
     *
     * @return TurSimulacionDetalleRecurso
     */
    public function setVrFestivasDiurnas($vrFestivasDiurnas)
    {
        $this->vrFestivasDiurnas = $vrFestivasDiurnas;

        return $this;
    }

    /**
     * Get vrFestivasDiurnas
     *
     * @return float
     */
    public function getVrFestivasDiurnas()
    {
        return $this->vrFestivasDiurnas;
    }

    /**
     * Set vrFestivasNocturnas
     *
     * @param float $vrFestivasNocturnas
     *
     * @return TurSimulacionDetalleRecurso
     */
    public function setVrFestivasNocturnas($vrFestivasNocturnas)
    {
        $this->vrFestivasNocturnas = $vrFestivasNocturnas;

        return $this;
    }

    /**
     * Get vrFestivasNocturnas
     *
     * @return float
     */
    public function getVrFestivasNocturnas()
    {
        return $this->vrFestivasNocturnas;
    }

    /**
     * Set vrExtrasOrdinariasDiurnas
     *
     * @param float $vrExtrasOrdinariasDiurnas
     *
     * @return TurSimulacionDetalleRecurso
     */
    public function setVrExtrasOrdinariasDiurnas($vrExtrasOrdinariasDiurnas)
    {
        $this->vrExtrasOrdinariasDiurnas = $vrExtrasOrdinariasDiurnas;

        return $this;
    }

    /**
     * Get vrExtrasOrdinariasDiurnas
     *
     * @return float
     */
    public function getVrExtrasOrdinariasDiurnas()
    {
        return $this->vrExtrasOrdinariasDiurnas;
    }

    /**
     * Set vrExtrasOrdinariasNocturnas
     *
     * @param float $vrExtrasOrdinariasNocturnas
     *
     * @return TurSimulacionDetalleRecurso
     */
    public function setVrExtrasOrdinariasNocturnas($vrExtrasOrdinariasNocturnas)
    {
        $this->vrExtrasOrdinariasNocturnas = $vrExtrasOrdinariasNocturnas;

        return $this;
    }

    /**
     * Get vrExtrasOrdinariasNocturnas
     *
     * @return float
     */
    public function getVrExtrasOrdinariasNocturnas()
    {
        return $this->vrExtrasOrdinariasNocturnas;
    }

    /**
     * Set vrExtrasFestivasDiurnas
     *
     * @param float $vrExtrasFestivasDiurnas
     *
     * @return TurSimulacionDetalleRecurso
     */
    public function setVrExtrasFestivasDiurnas($vrExtrasFestivasDiurnas)
    {
        $this->vrExtrasFestivasDiurnas = $vrExtrasFestivasDiurnas;

        return $this;
    }

    /**
     * Get vrExtrasFestivasDiurnas
     *
     * @return float
     */
    public function getVrExtrasFestivasDiurnas()
    {
        return $this->vrExtrasFestivasDiurnas;
    }

    /**
     * Set vrExtrasFestivasNocturnas
     *
     * @param float $vrExtrasFestivasNocturnas
     *
     * @return TurSimulacionDetalleRecurso
     */
    public function setVrExtrasFestivasNocturnas($vrExtrasFestivasNocturnas)
    {
        $this->vrExtrasFestivasNocturnas = $vrExtrasFestivasNocturnas;

        return $this;
    }

    /**
     * Get vrExtrasFestivasNocturnas
     *
     * @return float
     */
    public function getVrExtrasFestivasNocturnas()
    {
        return $this->vrExtrasFestivasNocturnas;
    }

    /**
     * Set vrRecargoNocturno
     *
     * @param float $vrRecargoNocturno
     *
     * @return TurSimulacionDetalleRecurso
     */
    public function setVrRecargoNocturno($vrRecargoNocturno)
    {
        $this->vrRecargoNocturno = $vrRecargoNocturno;

        return $this;
    }

    /**
     * Get vrRecargoNocturno
     *
     * @return float
     */
    public function getVrRecargoNocturno()
    {
        return $this->vrRecargoNocturno;
    }

    /**
     * Set vrRecargoFestivoDiurno
     *
     * @param float $vrRecargoFestivoDiurno
     *
     * @return TurSimulacionDetalleRecurso
     */
    public function setVrRecargoFestivoDiurno($vrRecargoFestivoDiurno)
    {
        $this->vrRecargoFestivoDiurno = $vrRecargoFestivoDiurno;

        return $this;
    }

    /**
     * Get vrRecargoFestivoDiurno
     *
     * @return float
     */
    public function getVrRecargoFestivoDiurno()
    {
        return $this->vrRecargoFestivoDiurno;
    }

    /**
     * Set vrRecargoFestivoNocturno
     *
     * @param float $vrRecargoFestivoNocturno
     *
     * @return TurSimulacionDetalleRecurso
     */
    public function setVrRecargoFestivoNocturno($vrRecargoFestivoNocturno)
    {
        $this->vrRecargoFestivoNocturno = $vrRecargoFestivoNocturno;

        return $this;
    }

    /**
     * Get vrRecargoFestivoNocturno
     *
     * @return float
     */
    public function getVrRecargoFestivoNocturno()
    {
        return $this->vrRecargoFestivoNocturno;
    }

    /**
     * Set vrDescanso
     *
     * @param float $vrDescanso
     *
     * @return TurSimulacionDetalleRecurso
     */
    public function setVrDescanso($vrDescanso)
    {
        $this->vrDescanso = $vrDescanso;

        return $this;
    }

    /**
     * Get vrDescanso
     *
     * @return float
     */
    public function getVrDescanso()
    {
        return $this->vrDescanso;
    }

    /**
     * Set vrDevengado
     *
     * @param float $vrDevengado
     *
     * @return TurSimulacionDetalleRecurso
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
     * Set vrAuxilioTransporte
     *
     * @param float $vrAuxilioTransporte
     *
     * @return TurSimulacionDetalleRecurso
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
}
