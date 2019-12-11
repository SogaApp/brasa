<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_costo_recurso_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCostoRecursoDetalleRepository")
 */
class TurCostoRecursoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_recurso_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCostoRecursoDetallePk;             
    
    /**
     * @ORM\Column(name="codigo_cierre_mes_fk", type="integer")
     */    
    private $codigoCierreMesFk;     
    
    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */    
    private $anio;    
    
    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */    
    private $mes;                     
    
    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoFk;    

    /**
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoPedidoDetalleFk;    
    
    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;         

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;     
    
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
     * @ORM\Column(name="horas_diurnas_costo", type="float")
     */    
    private $horasDiurnasCosto = 0;     

    /**
     * @ORM\Column(name="horas_nocturnas_costo", type="float")
     */    
    private $horasNocturnasCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_festivas_diurnas_costo", type="float")
     */    
    private $horasFestivasDiurnasCosto = 0;     

    /**
     * @ORM\Column(name="horas_festivas_nocturnas_costo", type="float")
     */    
    private $horasFestivasNocturnasCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas_costo", type="float")
     */    
    private $horasExtrasOrdinariasDiurnasCosto = 0;    

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas_costo", type="float")
     */    
    private $horasExtrasOrdinariasNocturnasCosto = 0;        

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas_costo", type="float")
     */    
    private $horasExtrasFestivasDiurnasCosto = 0;    

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas_costo", type="float")
     */    
    private $horasExtrasFestivasNocturnasCosto = 0;    

    /**
     * @ORM\Column(name="horas_recargo_nocturno_costo", type="float")
     */    
    private $horasRecargoNocturnoCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_recargo_festivo_diurno_costo", type="float")
     */    
    private $horasRecargoFestivoDiurnoCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_recargo_festivo_nocturno_costo", type="float")
     */    
    private $horasRecargoFestivoNocturnoCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_descanso_costo", type="float")
     */    
    private $horasDescansoCosto = 0;    
    
    /**
     * @ORM\Column(name="peso", type="float")
     */    
    private $peso = 0;     

    /**
     * @ORM\Column(name="participacion", type="float")
     */    
    private $participacion = 0;     
    
    /**
     * @ORM\Column(name="costo", type="float")
     */    
    private $costo = 0;     

    /**
     * @ORM\Column(name="costo_nomina", type="float")
     */    
    private $costoNomina = 0;    
    
    /**
     * @ORM\Column(name="costo_seguridad_social", type="float")
     */    
    private $costoSeguridadSocial = 0;
    
    /**
     * @ORM\Column(name="costo_prestaciones", type="float")
     */    
    private $costoPrestaciones = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurRecurso", inversedBy="costosRecursosDetallesRecursoRel")
     * @ORM\JoinColumn(name="codigo_recurso_fk", referencedColumnName="codigo_recurso_pk")
     */
    protected $recursoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="costosRecursosDetallesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;             
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoDetalle", inversedBy="costosRecursosDetallesPedidoDetalleRel")
     * @ORM\JoinColumn(name="codigo_pedido_detalle_fk", referencedColumnName="codigo_pedido_detalle_pk")
     */
    protected $pedidoDetalleRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="costosRecursosDetallesClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    
    
    /**
     * Get codigoCostoRecursoDetallePk
     *
     * @return integer
     */
    public function getCodigoCostoRecursoDetallePk()
    {
        return $this->codigoCostoRecursoDetallePk;
    }

    /**
     * Set codigoCierreMesFk
     *
     * @param integer $codigoCierreMesFk
     *
     * @return TurCostoRecursoDetalle
     */
    public function setCodigoCierreMesFk($codigoCierreMesFk)
    {
        $this->codigoCierreMesFk = $codigoCierreMesFk;

        return $this;
    }

    /**
     * Get codigoCierreMesFk
     *
     * @return integer
     */
    public function getCodigoCierreMesFk()
    {
        return $this->codigoCierreMesFk;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return TurCostoRecursoDetalle
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
     * @return TurCostoRecursoDetalle
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
     * Set codigoRecursoFk
     *
     * @param integer $codigoRecursoFk
     *
     * @return TurCostoRecursoDetalle
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
     * Set codigoPedidoDetalleFk
     *
     * @param integer $codigoPedidoDetalleFk
     *
     * @return TurCostoRecursoDetalle
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
     * Set codigoPuestoFk
     *
     * @param integer $codigoPuestoFk
     *
     * @return TurCostoRecursoDetalle
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
     * Set horas
     *
     * @param float $horas
     *
     * @return TurCostoRecursoDetalle
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
     * @return TurCostoRecursoDetalle
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
     * @return TurCostoRecursoDetalle
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
     * @return TurCostoRecursoDetalle
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
     * @return TurCostoRecursoDetalle
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
     * @return TurCostoRecursoDetalle
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
     * @return TurCostoRecursoDetalle
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
     * @return TurCostoRecursoDetalle
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
     * @return TurCostoRecursoDetalle
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
     * @return TurCostoRecursoDetalle
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
     * @return TurCostoRecursoDetalle
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
     * @return TurCostoRecursoDetalle
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
     * @return TurCostoRecursoDetalle
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
     * Set peso
     *
     * @param float $peso
     *
     * @return TurCostoRecursoDetalle
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get peso
     *
     * @return float
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set participacion
     *
     * @param float $participacion
     *
     * @return TurCostoRecursoDetalle
     */
    public function setParticipacion($participacion)
    {
        $this->participacion = $participacion;

        return $this;
    }

    /**
     * Get participacion
     *
     * @return float
     */
    public function getParticipacion()
    {
        return $this->participacion;
    }

    /**
     * Set costo
     *
     * @param float $costo
     *
     * @return TurCostoRecursoDetalle
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set puestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestoRel
     *
     * @return TurCostoRecursoDetalle
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
     * Set recursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursoRel
     *
     * @return TurCostoRecursoDetalle
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
     * Set horasDiurnasCosto
     *
     * @param float $horasDiurnasCosto
     *
     * @return TurCostoRecursoDetalle
     */
    public function setHorasDiurnasCosto($horasDiurnasCosto)
    {
        $this->horasDiurnasCosto = $horasDiurnasCosto;

        return $this;
    }

    /**
     * Get horasDiurnasCosto
     *
     * @return float
     */
    public function getHorasDiurnasCosto()
    {
        return $this->horasDiurnasCosto;
    }

    /**
     * Set horasNocturnasCosto
     *
     * @param float $horasNocturnasCosto
     *
     * @return TurCostoRecursoDetalle
     */
    public function setHorasNocturnasCosto($horasNocturnasCosto)
    {
        $this->horasNocturnasCosto = $horasNocturnasCosto;

        return $this;
    }

    /**
     * Get horasNocturnasCosto
     *
     * @return float
     */
    public function getHorasNocturnasCosto()
    {
        return $this->horasNocturnasCosto;
    }

    /**
     * Set horasFestivasDiurnasCosto
     *
     * @param float $horasFestivasDiurnasCosto
     *
     * @return TurCostoRecursoDetalle
     */
    public function setHorasFestivasDiurnasCosto($horasFestivasDiurnasCosto)
    {
        $this->horasFestivasDiurnasCosto = $horasFestivasDiurnasCosto;

        return $this;
    }

    /**
     * Get horasFestivasDiurnasCosto
     *
     * @return float
     */
    public function getHorasFestivasDiurnasCosto()
    {
        return $this->horasFestivasDiurnasCosto;
    }

    /**
     * Set horasFestivasNocturnasCosto
     *
     * @param float $horasFestivasNocturnasCosto
     *
     * @return TurCostoRecursoDetalle
     */
    public function setHorasFestivasNocturnasCosto($horasFestivasNocturnasCosto)
    {
        $this->horasFestivasNocturnasCosto = $horasFestivasNocturnasCosto;

        return $this;
    }

    /**
     * Get horasFestivasNocturnasCosto
     *
     * @return float
     */
    public function getHorasFestivasNocturnasCosto()
    {
        return $this->horasFestivasNocturnasCosto;
    }

    /**
     * Set horasExtrasOrdinariasDiurnasCosto
     *
     * @param float $horasExtrasOrdinariasDiurnasCosto
     *
     * @return TurCostoRecursoDetalle
     */
    public function setHorasExtrasOrdinariasDiurnasCosto($horasExtrasOrdinariasDiurnasCosto)
    {
        $this->horasExtrasOrdinariasDiurnasCosto = $horasExtrasOrdinariasDiurnasCosto;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasDiurnasCosto
     *
     * @return float
     */
    public function getHorasExtrasOrdinariasDiurnasCosto()
    {
        return $this->horasExtrasOrdinariasDiurnasCosto;
    }

    /**
     * Set horasExtrasOrdinariasNocturnasCosto
     *
     * @param float $horasExtrasOrdinariasNocturnasCosto
     *
     * @return TurCostoRecursoDetalle
     */
    public function setHorasExtrasOrdinariasNocturnasCosto($horasExtrasOrdinariasNocturnasCosto)
    {
        $this->horasExtrasOrdinariasNocturnasCosto = $horasExtrasOrdinariasNocturnasCosto;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasNocturnasCosto
     *
     * @return float
     */
    public function getHorasExtrasOrdinariasNocturnasCosto()
    {
        return $this->horasExtrasOrdinariasNocturnasCosto;
    }

    /**
     * Set horasExtrasFestivasDiurnasCosto
     *
     * @param float $horasExtrasFestivasDiurnasCosto
     *
     * @return TurCostoRecursoDetalle
     */
    public function setHorasExtrasFestivasDiurnasCosto($horasExtrasFestivasDiurnasCosto)
    {
        $this->horasExtrasFestivasDiurnasCosto = $horasExtrasFestivasDiurnasCosto;

        return $this;
    }

    /**
     * Get horasExtrasFestivasDiurnasCosto
     *
     * @return float
     */
    public function getHorasExtrasFestivasDiurnasCosto()
    {
        return $this->horasExtrasFestivasDiurnasCosto;
    }

    /**
     * Set horasExtrasFestivasNocturnasCosto
     *
     * @param float $horasExtrasFestivasNocturnasCosto
     *
     * @return TurCostoRecursoDetalle
     */
    public function setHorasExtrasFestivasNocturnasCosto($horasExtrasFestivasNocturnasCosto)
    {
        $this->horasExtrasFestivasNocturnasCosto = $horasExtrasFestivasNocturnasCosto;

        return $this;
    }

    /**
     * Get horasExtrasFestivasNocturnasCosto
     *
     * @return float
     */
    public function getHorasExtrasFestivasNocturnasCosto()
    {
        return $this->horasExtrasFestivasNocturnasCosto;
    }

    /**
     * Set horasRecargoNocturnoCosto
     *
     * @param float $horasRecargoNocturnoCosto
     *
     * @return TurCostoRecursoDetalle
     */
    public function setHorasRecargoNocturnoCosto($horasRecargoNocturnoCosto)
    {
        $this->horasRecargoNocturnoCosto = $horasRecargoNocturnoCosto;

        return $this;
    }

    /**
     * Get horasRecargoNocturnoCosto
     *
     * @return float
     */
    public function getHorasRecargoNocturnoCosto()
    {
        return $this->horasRecargoNocturnoCosto;
    }

    /**
     * Set horasRecargoFestivoDiurnoCosto
     *
     * @param float $horasRecargoFestivoDiurnoCosto
     *
     * @return TurCostoRecursoDetalle
     */
    public function setHorasRecargoFestivoDiurnoCosto($horasRecargoFestivoDiurnoCosto)
    {
        $this->horasRecargoFestivoDiurnoCosto = $horasRecargoFestivoDiurnoCosto;

        return $this;
    }

    /**
     * Get horasRecargoFestivoDiurnoCosto
     *
     * @return float
     */
    public function getHorasRecargoFestivoDiurnoCosto()
    {
        return $this->horasRecargoFestivoDiurnoCosto;
    }

    /**
     * Set horasRecargoFestivoNocturnoCosto
     *
     * @param float $horasRecargoFestivoNocturnoCosto
     *
     * @return TurCostoRecursoDetalle
     */
    public function setHorasRecargoFestivoNocturnoCosto($horasRecargoFestivoNocturnoCosto)
    {
        $this->horasRecargoFestivoNocturnoCosto = $horasRecargoFestivoNocturnoCosto;

        return $this;
    }

    /**
     * Get horasRecargoFestivoNocturnoCosto
     *
     * @return float
     */
    public function getHorasRecargoFestivoNocturnoCosto()
    {
        return $this->horasRecargoFestivoNocturnoCosto;
    }

    /**
     * Set horasDescansoCosto
     *
     * @param float $horasDescansoCosto
     *
     * @return TurCostoRecursoDetalle
     */
    public function setHorasDescansoCosto($horasDescansoCosto)
    {
        $this->horasDescansoCosto = $horasDescansoCosto;

        return $this;
    }

    /**
     * Get horasDescansoCosto
     *
     * @return float
     */
    public function getHorasDescansoCosto()
    {
        return $this->horasDescansoCosto;
    }

    /**
     * Set pedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidoDetalleRel
     *
     * @return TurCostoRecursoDetalle
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
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurCostoRecursoDetalle
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurCostoRecursoDetalle
     */
    public function setClienteRel(\Brasa\TurnoBundle\Entity\TurCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Set costoSeguridadSocial
     *
     * @param float $costoSeguridadSocial
     *
     * @return TurCostoRecursoDetalle
     */
    public function setCostoSeguridadSocial($costoSeguridadSocial)
    {
        $this->costoSeguridadSocial = $costoSeguridadSocial;

        return $this;
    }

    /**
     * Get costoSeguridadSocial
     *
     * @return float
     */
    public function getCostoSeguridadSocial()
    {
        return $this->costoSeguridadSocial;
    }

    /**
     * Set costoPrestaciones
     *
     * @param float $costoPrestaciones
     *
     * @return TurCostoRecursoDetalle
     */
    public function setCostoPrestaciones($costoPrestaciones)
    {
        $this->costoPrestaciones = $costoPrestaciones;

        return $this;
    }

    /**
     * Get costoPrestaciones
     *
     * @return float
     */
    public function getCostoPrestaciones()
    {
        return $this->costoPrestaciones;
    }

    /**
     * Set costoNomina
     *
     * @param float $costoNomina
     *
     * @return TurCostoRecursoDetalle
     */
    public function setCostoNomina($costoNomina)
    {
        $this->costoNomina = $costoNomina;

        return $this;
    }

    /**
     * Get costoNomina
     *
     * @return float
     */
    public function getCostoNomina()
    {
        return $this->costoNomina;
    }
}
