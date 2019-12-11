<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_cotizacion_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCotizacionDetalleRepository")
 */
class TurCotizacionDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cotizacion_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCotizacionDetallePk;  
    
    /**
     * @ORM\Column(name="codigo_cotizacion_fk", type="integer")
     */    
    private $codigoCotizacionFk;    
    
    /**
     * @ORM\Column(name="codigo_proyecto_fk", type="integer", nullable=true)
     */    
    private $codigoProyectoFk;     
    
    /**
     * @ORM\Column(name="codigo_concepto_servicio_fk", type="integer")
     */    
    private $codigoConceptoServicioFk;  
    
    /**
     * @ORM\Column(name="codigo_modalidad_servicio_fk", type="integer")
     */    
    private $codigoModalidadServicioFk;       

    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer")
     */    
    private $codigoPeriodoFk;     
        
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;    
    
    /**     
     * @ORM\Column(name="liquidarDiasReales", type="boolean")
     */    
    private $liquidarDiasReales = false;    
    
    /**
     * @ORM\Column(name="dias", type="integer")
     */    
    private $dias = 0; 
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    

    /**
     * @ORM\Column(name="horas_diurnas", type="integer")
     */    
    private $horasDiurnas = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas", type="integer")
     */    
    private $horasNocturnas = 0;     
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;     
    
    /**
     * @ORM\Column(name="vr_costo", type="float")
     */
    private $vrCosto = 0;
    
    /**
     * @ORM\Column(name="vr_precio_ajustado", type="float")
     */
    private $vrPrecioAjustado = 0;            

    /**
     * @ORM\Column(name="vr_precio_minimo", type="float")
     */
    private $vrPrecioMinimo = 0;        
    
    /**
     * @ORM\Column(name="vr_total_detalle", type="float")
     */
    private $vrTotalDetalle = 0; 
    
    /**     
     * @ORM\Column(name="lunes", type="boolean", nullable=true)
     */    
    private $lunes = false;    
    
    /**     
     * @ORM\Column(name="martes", type="boolean")
     */    
    private $martes = false;        
    
    /**     
     * @ORM\Column(name="miercoles", type="boolean")
     */    
    private $miercoles = false;        
    
    /**     
     * @ORM\Column(name="jueves", type="boolean")
     */    
    private $jueves = false;        
    
    /**     
     * @ORM\Column(name="viernes", type="boolean")
     */    
    private $viernes = false;    
    
    /**     
     * @ORM\Column(name="sabado", type="boolean")
     */    
    private $sabado = false;        
    
    /**     
     * @ORM\Column(name="domingo", type="boolean")
     */    
    private $domingo = false;        
    
    /**     
     * @ORM\Column(name="festivo", type="boolean")
     */    
    private $festivo = false;                      
    
    /**     
     * @ORM\Column(name="dia_31", type="boolean")
     */    
    private $dia31 = false;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCotizacion", inversedBy="cotizacionesDetallesCotizacionRel")
     * @ORM\JoinColumn(name="codigo_cotizacion_fk", referencedColumnName="codigo_cotizacion_pk")
     */
    protected $cotizacionRel;       

    /**
     * @ORM\ManyToOne(targetEntity="TurProyecto", inversedBy="cotizacionesDetallesProyectoRel")
     * @ORM\JoinColumn(name="codigo_proyecto_fk", referencedColumnName="codigo_proyecto_pk")
     */
    protected $proyectoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurConceptoServicio", inversedBy="cotizacionesDetallesConceptoServicioRel")
     * @ORM\JoinColumn(name="codigo_concepto_servicio_fk", referencedColumnName="codigo_concepto_servicio_pk")
     */
    protected $conceptoServicioRel;      

    /**
     * @ORM\ManyToOne(targetEntity="TurModalidadServicio", inversedBy="cotizacionesDetallesModalidadServicioRel")
     * @ORM\JoinColumn(name="codigo_modalidad_servicio_fk", referencedColumnName="codigo_modalidad_servicio_pk")
     */
    protected $modalidadServicioRel;    
  
    /**
     * @ORM\ManyToOne(targetEntity="TurPeriodo", inversedBy="cotizacionesDetallesPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $periodoRel;     



    /**
     * Get codigoCotizacionDetallePk
     *
     * @return integer
     */
    public function getCodigoCotizacionDetallePk()
    {
        return $this->codigoCotizacionDetallePk;
    }

    /**
     * Set codigoCotizacionFk
     *
     * @param integer $codigoCotizacionFk
     *
     * @return TurCotizacionDetalle
     */
    public function setCodigoCotizacionFk($codigoCotizacionFk)
    {
        $this->codigoCotizacionFk = $codigoCotizacionFk;

        return $this;
    }

    /**
     * Get codigoCotizacionFk
     *
     * @return integer
     */
    public function getCodigoCotizacionFk()
    {
        return $this->codigoCotizacionFk;
    }

    /**
     * Set codigoConceptoServicioFk
     *
     * @param integer $codigoConceptoServicioFk
     *
     * @return TurCotizacionDetalle
     */
    public function setCodigoConceptoServicioFk($codigoConceptoServicioFk)
    {
        $this->codigoConceptoServicioFk = $codigoConceptoServicioFk;

        return $this;
    }

    /**
     * Get codigoConceptoServicioFk
     *
     * @return integer
     */
    public function getCodigoConceptoServicioFk()
    {
        return $this->codigoConceptoServicioFk;
    }

    /**
     * Set codigoModalidadServicioFk
     *
     * @param integer $codigoModalidadServicioFk
     *
     * @return TurCotizacionDetalle
     */
    public function setCodigoModalidadServicioFk($codigoModalidadServicioFk)
    {
        $this->codigoModalidadServicioFk = $codigoModalidadServicioFk;

        return $this;
    }

    /**
     * Get codigoModalidadServicioFk
     *
     * @return integer
     */
    public function getCodigoModalidadServicioFk()
    {
        return $this->codigoModalidadServicioFk;
    }

    /**
     * Set codigoPeriodoFk
     *
     * @param integer $codigoPeriodoFk
     *
     * @return TurCotizacionDetalle
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return TurCotizacionDetalle
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
     * @return TurCotizacionDetalle
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
     * Set dias
     *
     * @param integer $dias
     *
     * @return TurCotizacionDetalle
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
     * Set horas
     *
     * @param integer $horas
     *
     * @return TurCotizacionDetalle
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return integer
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set horasDiurnas
     *
     * @param integer $horasDiurnas
     *
     * @return TurCotizacionDetalle
     */
    public function setHorasDiurnas($horasDiurnas)
    {
        $this->horasDiurnas = $horasDiurnas;

        return $this;
    }

    /**
     * Get horasDiurnas
     *
     * @return integer
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * Set horasNocturnas
     *
     * @param integer $horasNocturnas
     *
     * @return TurCotizacionDetalle
     */
    public function setHorasNocturnas($horasNocturnas)
    {
        $this->horasNocturnas = $horasNocturnas;

        return $this;
    }

    /**
     * Get horasNocturnas
     *
     * @return integer
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return TurCotizacionDetalle
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set vrCosto
     *
     * @param float $vrCosto
     *
     * @return TurCotizacionDetalle
     */
    public function setVrCosto($vrCosto)
    {
        $this->vrCosto = $vrCosto;

        return $this;
    }

    /**
     * Get vrCosto
     *
     * @return float
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * Set vrPrecioAjustado
     *
     * @param float $vrPrecioAjustado
     *
     * @return TurCotizacionDetalle
     */
    public function setVrPrecioAjustado($vrPrecioAjustado)
    {
        $this->vrPrecioAjustado = $vrPrecioAjustado;

        return $this;
    }

    /**
     * Get vrPrecioAjustado
     *
     * @return float
     */
    public function getVrPrecioAjustado()
    {
        return $this->vrPrecioAjustado;
    }

    /**
     * Set vrPrecioMinimo
     *
     * @param float $vrPrecioMinimo
     *
     * @return TurCotizacionDetalle
     */
    public function setVrPrecioMinimo($vrPrecioMinimo)
    {
        $this->vrPrecioMinimo = $vrPrecioMinimo;

        return $this;
    }

    /**
     * Get vrPrecioMinimo
     *
     * @return float
     */
    public function getVrPrecioMinimo()
    {
        return $this->vrPrecioMinimo;
    }

    /**
     * Set vrTotalDetalle
     *
     * @param float $vrTotalDetalle
     *
     * @return TurCotizacionDetalle
     */
    public function setVrTotalDetalle($vrTotalDetalle)
    {
        $this->vrTotalDetalle = $vrTotalDetalle;

        return $this;
    }

    /**
     * Get vrTotalDetalle
     *
     * @return float
     */
    public function getVrTotalDetalle()
    {
        return $this->vrTotalDetalle;
    }

    /**
     * Set lunes
     *
     * @param boolean $lunes
     *
     * @return TurCotizacionDetalle
     */
    public function setLunes($lunes)
    {
        $this->lunes = $lunes;

        return $this;
    }

    /**
     * Get lunes
     *
     * @return boolean
     */
    public function getLunes()
    {
        return $this->lunes;
    }

    /**
     * Set martes
     *
     * @param boolean $martes
     *
     * @return TurCotizacionDetalle
     */
    public function setMartes($martes)
    {
        $this->martes = $martes;

        return $this;
    }

    /**
     * Get martes
     *
     * @return boolean
     */
    public function getMartes()
    {
        return $this->martes;
    }

    /**
     * Set miercoles
     *
     * @param boolean $miercoles
     *
     * @return TurCotizacionDetalle
     */
    public function setMiercoles($miercoles)
    {
        $this->miercoles = $miercoles;

        return $this;
    }

    /**
     * Get miercoles
     *
     * @return boolean
     */
    public function getMiercoles()
    {
        return $this->miercoles;
    }

    /**
     * Set jueves
     *
     * @param boolean $jueves
     *
     * @return TurCotizacionDetalle
     */
    public function setJueves($jueves)
    {
        $this->jueves = $jueves;

        return $this;
    }

    /**
     * Get jueves
     *
     * @return boolean
     */
    public function getJueves()
    {
        return $this->jueves;
    }

    /**
     * Set viernes
     *
     * @param boolean $viernes
     *
     * @return TurCotizacionDetalle
     */
    public function setViernes($viernes)
    {
        $this->viernes = $viernes;

        return $this;
    }

    /**
     * Get viernes
     *
     * @return boolean
     */
    public function getViernes()
    {
        return $this->viernes;
    }

    /**
     * Set sabado
     *
     * @param boolean $sabado
     *
     * @return TurCotizacionDetalle
     */
    public function setSabado($sabado)
    {
        $this->sabado = $sabado;

        return $this;
    }

    /**
     * Get sabado
     *
     * @return boolean
     */
    public function getSabado()
    {
        return $this->sabado;
    }

    /**
     * Set domingo
     *
     * @param boolean $domingo
     *
     * @return TurCotizacionDetalle
     */
    public function setDomingo($domingo)
    {
        $this->domingo = $domingo;

        return $this;
    }

    /**
     * Get domingo
     *
     * @return boolean
     */
    public function getDomingo()
    {
        return $this->domingo;
    }

    /**
     * Set festivo
     *
     * @param boolean $festivo
     *
     * @return TurCotizacionDetalle
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
     * Set dia31
     *
     * @param boolean $dia31
     *
     * @return TurCotizacionDetalle
     */
    public function setDia31($dia31)
    {
        $this->dia31 = $dia31;

        return $this;
    }

    /**
     * Get dia31
     *
     * @return boolean
     */
    public function getDia31()
    {
        return $this->dia31;
    }

    /**
     * Set cotizacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionRel
     *
     * @return TurCotizacionDetalle
     */
    public function setCotizacionRel(\Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionRel = null)
    {
        $this->cotizacionRel = $cotizacionRel;

        return $this;
    }

    /**
     * Get cotizacionRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCotizacion
     */
    public function getCotizacionRel()
    {
        return $this->cotizacionRel;
    }

    /**
     * Set conceptoServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurConceptoServicio $conceptoServicioRel
     *
     * @return TurCotizacionDetalle
     */
    public function setConceptoServicioRel(\Brasa\TurnoBundle\Entity\TurConceptoServicio $conceptoServicioRel = null)
    {
        $this->conceptoServicioRel = $conceptoServicioRel;

        return $this;
    }

    /**
     * Get conceptoServicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurConceptoServicio
     */
    public function getConceptoServicioRel()
    {
        return $this->conceptoServicioRel;
    }

    /**
     * Set modalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurModalidadServicio $modalidadServicioRel
     *
     * @return TurCotizacionDetalle
     */
    public function setModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurModalidadServicio $modalidadServicioRel = null)
    {
        $this->modalidadServicioRel = $modalidadServicioRel;

        return $this;
    }

    /**
     * Get modalidadServicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurModalidadServicio
     */
    public function getModalidadServicioRel()
    {
        return $this->modalidadServicioRel;
    }

    /**
     * Set periodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPeriodo $periodoRel
     *
     * @return TurCotizacionDetalle
     */
    public function setPeriodoRel(\Brasa\TurnoBundle\Entity\TurPeriodo $periodoRel = null)
    {
        $this->periodoRel = $periodoRel;

        return $this;
    }

    /**
     * Get periodoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPeriodo
     */
    public function getPeriodoRel()
    {
        return $this->periodoRel;
    }

    /**
     * Set liquidarDiasReales
     *
     * @param boolean $liquidarDiasReales
     *
     * @return TurCotizacionDetalle
     */
    public function setLiquidarDiasReales($liquidarDiasReales)
    {
        $this->liquidarDiasReales = $liquidarDiasReales;

        return $this;
    }

    /**
     * Get liquidarDiasReales
     *
     * @return boolean
     */
    public function getLiquidarDiasReales()
    {
        return $this->liquidarDiasReales;
    }

    /**
     * Set codigoProyectoFk
     *
     * @param integer $codigoProyectoFk
     *
     * @return TurCotizacionDetalle
     */
    public function setCodigoProyectoFk($codigoProyectoFk)
    {
        $this->codigoProyectoFk = $codigoProyectoFk;

        return $this;
    }

    /**
     * Get codigoProyectoFk
     *
     * @return integer
     */
    public function getCodigoProyectoFk()
    {
        return $this->codigoProyectoFk;
    }

    /**
     * Set proyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProyecto $proyectoRel
     *
     * @return TurCotizacionDetalle
     */
    public function setProyectoRel(\Brasa\TurnoBundle\Entity\TurProyecto $proyectoRel = null)
    {
        $this->proyectoRel = $proyectoRel;

        return $this;
    }

    /**
     * Get proyectoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurProyecto
     */
    public function getProyectoRel()
    {
        return $this->proyectoRel;
    }
}
