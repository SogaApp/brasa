<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_servicio_detalle_compuesto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurServicioDetalleCompuestoRepository")
 */
class TurServicioDetalleCompuesto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_servicio_detalle_compuesto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoServicioDetalleCompuestoPk;  
    
    /**
     * @ORM\Column(name="codigo_servicio_detalle_fk", type="integer")
     */    
    private $codigoServicioDetalleFk;                  
    
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
     * @ORM\Column(name="liquidar_dias_reales", type="boolean")
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
     * @ORM\Column(name="vr_precio_ajustado", type="float")
     */
    private $vrPrecioAjustado = 0;            

    /**
     * @ORM\Column(name="vr_precio_minimo", type="float")
     */
    private $vrPrecioMinimo = 0;        

    /**
     * @ORM\Column(name="vr_precio", type="float")
     */
    private $vrPrecio = 0; 
    
    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0; 

    /**
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $vrIva = 0;    
    
    /**
     * @ORM\Column(name="vr_base_aiu", type="float")
     */
    private $vrBaseAiu = 0;     
    
    /**
     * @ORM\Column(name="vr_total_detalle", type="float")
     */
    private $vrTotalDetalle = 0;
    
    /**     
     * @ORM\Column(name="lunes", type="boolean")
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
     * @ORM\ManyToOne(targetEntity="TurServicioDetalle", inversedBy="serviciosDetallesCompuestosServicioDetalleRel")
     * @ORM\JoinColumn(name="codigo_servicio_detalle_fk", referencedColumnName="codigo_servicio_detalle_pk")
     */
    protected $servicioDetalleRel;                     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurConceptoServicio", inversedBy="serviciosDetallesCompuestosConceptoServicioRel")
     * @ORM\JoinColumn(name="codigo_concepto_servicio_fk", referencedColumnName="codigo_concepto_servicio_pk")
     */
    protected $conceptoServicioRel;      

    /**
     * @ORM\ManyToOne(targetEntity="TurModalidadServicio", inversedBy="serviciosDetallesCompuestosModalidadServicioRel")
     * @ORM\JoinColumn(name="codigo_modalidad_servicio_fk", referencedColumnName="codigo_modalidad_servicio_pk")
     */
    protected $modalidadServicioRel;            
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPeriodo", inversedBy="serviciosDetallesCompuestosPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $periodoRel;      


    /**
     * Get codigoServicioDetalleCompuestoPk
     *
     * @return integer
     */
    public function getCodigoServicioDetalleCompuestoPk()
    {
        return $this->codigoServicioDetalleCompuestoPk;
    }

    /**
     * Set codigoServicioDetalleFk
     *
     * @param integer $codigoServicioDetalleFk
     *
     * @return TurServicioDetalleCompuesto
     */
    public function setCodigoServicioDetalleFk($codigoServicioDetalleFk)
    {
        $this->codigoServicioDetalleFk = $codigoServicioDetalleFk;

        return $this;
    }

    /**
     * Get codigoServicioDetalleFk
     *
     * @return integer
     */
    public function getCodigoServicioDetalleFk()
    {
        return $this->codigoServicioDetalleFk;
    }

    /**
     * Set codigoConceptoServicioFk
     *
     * @param integer $codigoConceptoServicioFk
     *
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * Set liquidarDiasReales
     *
     * @param boolean $liquidarDiasReales
     *
     * @return TurServicioDetalleCompuesto
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
     * Set dias
     *
     * @param integer $dias
     *
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * Set vrPrecioAjustado
     *
     * @param float $vrPrecioAjustado
     *
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * Set vrPrecio
     *
     * @param float $vrPrecio
     *
     * @return TurServicioDetalleCompuesto
     */
    public function setVrPrecio($vrPrecio)
    {
        $this->vrPrecio = $vrPrecio;

        return $this;
    }

    /**
     * Get vrPrecio
     *
     * @return float
     */
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * Set vrSubtotal
     *
     * @param float $vrSubtotal
     *
     * @return TurServicioDetalleCompuesto
     */
    public function setVrSubtotal($vrSubtotal)
    {
        $this->vrSubtotal = $vrSubtotal;

        return $this;
    }

    /**
     * Get vrSubtotal
     *
     * @return float
     */
    public function getVrSubtotal()
    {
        return $this->vrSubtotal;
    }

    /**
     * Set vrIva
     *
     * @param float $vrIva
     *
     * @return TurServicioDetalleCompuesto
     */
    public function setVrIva($vrIva)
    {
        $this->vrIva = $vrIva;

        return $this;
    }

    /**
     * Get vrIva
     *
     * @return float
     */
    public function getVrIva()
    {
        return $this->vrIva;
    }

    /**
     * Set vrBaseAiu
     *
     * @param float $vrBaseAiu
     *
     * @return TurServicioDetalleCompuesto
     */
    public function setVrBaseAiu($vrBaseAiu)
    {
        $this->vrBaseAiu = $vrBaseAiu;

        return $this;
    }

    /**
     * Get vrBaseAiu
     *
     * @return float
     */
    public function getVrBaseAiu()
    {
        return $this->vrBaseAiu;
    }

    /**
     * Set vrTotalDetalle
     *
     * @param float $vrTotalDetalle
     *
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * Set servicioDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $servicioDetalleRel
     *
     * @return TurServicioDetalleCompuesto
     */
    public function setServicioDetalleRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $servicioDetalleRel = null)
    {
        $this->servicioDetalleRel = $servicioDetalleRel;

        return $this;
    }

    /**
     * Get servicioDetalleRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurServicioDetalle
     */
    public function getServicioDetalleRel()
    {
        return $this->servicioDetalleRel;
    }

    /**
     * Set conceptoServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurConceptoServicio $conceptoServicioRel
     *
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
     * @return TurServicioDetalleCompuesto
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
}
