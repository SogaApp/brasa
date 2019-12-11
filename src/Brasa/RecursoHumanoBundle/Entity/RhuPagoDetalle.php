<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoDetalleRepository")
 */
class RhuPagoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoDetallePk;
    
    /**
     * @ORM\Column(name="codigo_pago_fk", type="integer", nullable=true)
     */    
    private $codigoPagoFk;
    
    /**
     * @ORM\Column(name="codigo_credito_fk", type="integer", nullable=true)
     */    
    private $codigoCreditoFk;         
    
    /**
     * @ORM\Column(name="vr_pago", type="float")
     */
    private $vrPago = 0;     

    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;
    
    /**
     * @ORM\Column(name="vr_pago_operado", type="float")
     */
    private $vrPagoOperado = 0;    
    
    /**
     * @ORM\Column(name="numero_horas", type="float")
     */
    private $numeroHoras = 0;    
    
    /**
     * @ORM\Column(name="vr_hora", type="float")
     */
    private $vrHora = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_aplicado", type="float")
     */
    private $porcentajeAplicado = 0;    
    
    /**
     * @ORM\Column(name="numero_dias", type="integer")
     */
    private $numeroDias = 0;     
    
    /**
     * @ORM\Column(name="vr_dia", type="float")
     */
    private $vrDia = 0;    
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;     
    
    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */    
    private $detalle;     
    
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;    
    
    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", type="float")
     */
    private $vrIngresoBaseCotizacion = 0;     

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion", type="float")
     */
    private $vrIngresoBasePrestacion = 0;    

    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion_salario", type="float")
     */
    private $vrIngresoBaseCotizacionSalario = 0;
    
    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion_adicional", type="float")
     */
    private $vrIngresoBaseCotizacionAdicional = 0;           
    
    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion_incapacidad", type="float")
     */
    private $vrIngresoBaseCotizacionIncapacidad = 0;    
    
    /**
     * @ORM\Column(name="vr_extra", type="float")
     */
    private $vrExtra= 0;     
    
    /**
     * @ORM\Column(name="codigo_programacion_pago_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionPagoDetalleFk;     
    
    /**
     * @ORM\Column(name="dias_ausentismo", type="integer")
     */
    private $diasAusentismo = 0;     

    /**
     * @ORM\Column(name="adicional", type="boolean")
     */
    private $adicional = 0;    

    /**
     * @ORM\Column(name="prestacional", type="boolean")
     */
    private $prestacional = 0;    

    /**
     * @ORM\Column(name="cotizacion", type="boolean")
     */
    private $cotizacion = 0;
    
    /**
     * @ORM\Column(name="salud", type="boolean")
     */    
    private $salud = false;

    /**
     * @ORM\Column(name="pension", type="boolean")
     */    
    private $pension = false;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPago", inversedBy="pagosDetallesPagoRel")
     * @ORM\JoinColumn(name="codigo_pago_fk", referencedColumnName="codigo_pago_pk")
     */
    protected $pagoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="pagosDetallesPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;     


    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionPagoDetalle", inversedBy="pagosDetallesProgramacionPagoDetalleRel")
     * @ORM\JoinColumn(name="codigo_programacion_pago_detalle_fk", referencedColumnName="codigo_programacion_pago_detalle_pk")
     */
    protected $programacionPagoDetalleRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCredito", inversedBy="pagosDetallesCreditoRel")
     * @ORM\JoinColumn(name="codigo_credito_fk", referencedColumnName="codigo_credito_pk")
     */
    protected $creditoRel;
    



    /**
     * Get codigoPagoDetallePk
     *
     * @return integer
     */
    public function getCodigoPagoDetallePk()
    {
        return $this->codigoPagoDetallePk;
    }

    /**
     * Set codigoPagoFk
     *
     * @param integer $codigoPagoFk
     *
     * @return RhuPagoDetalle
     */
    public function setCodigoPagoFk($codigoPagoFk)
    {
        $this->codigoPagoFk = $codigoPagoFk;

        return $this;
    }

    /**
     * Get codigoPagoFk
     *
     * @return integer
     */
    public function getCodigoPagoFk()
    {
        return $this->codigoPagoFk;
    }

    /**
     * Set codigoCreditoFk
     *
     * @param integer $codigoCreditoFk
     *
     * @return RhuPagoDetalle
     */
    public function setCodigoCreditoFk($codigoCreditoFk)
    {
        $this->codigoCreditoFk = $codigoCreditoFk;

        return $this;
    }

    /**
     * Get codigoCreditoFk
     *
     * @return integer
     */
    public function getCodigoCreditoFk()
    {
        return $this->codigoCreditoFk;
    }

    /**
     * Set vrPago
     *
     * @param float $vrPago
     *
     * @return RhuPagoDetalle
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
     * Set operacion
     *
     * @param integer $operacion
     *
     * @return RhuPagoDetalle
     */
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get operacion
     *
     * @return integer
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * Set vrPagoOperado
     *
     * @param float $vrPagoOperado
     *
     * @return RhuPagoDetalle
     */
    public function setVrPagoOperado($vrPagoOperado)
    {
        $this->vrPagoOperado = $vrPagoOperado;

        return $this;
    }

    /**
     * Get vrPagoOperado
     *
     * @return float
     */
    public function getVrPagoOperado()
    {
        return $this->vrPagoOperado;
    }

    /**
     * Set numeroHoras
     *
     * @param float $numeroHoras
     *
     * @return RhuPagoDetalle
     */
    public function setNumeroHoras($numeroHoras)
    {
        $this->numeroHoras = $numeroHoras;

        return $this;
    }

    /**
     * Get numeroHoras
     *
     * @return float
     */
    public function getNumeroHoras()
    {
        return $this->numeroHoras;
    }

    /**
     * Set vrHora
     *
     * @param float $vrHora
     *
     * @return RhuPagoDetalle
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
     * Set porcentajeAplicado
     *
     * @param float $porcentajeAplicado
     *
     * @return RhuPagoDetalle
     */
    public function setPorcentajeAplicado($porcentajeAplicado)
    {
        $this->porcentajeAplicado = $porcentajeAplicado;

        return $this;
    }

    /**
     * Get porcentajeAplicado
     *
     * @return float
     */
    public function getPorcentajeAplicado()
    {
        return $this->porcentajeAplicado;
    }

    /**
     * Set numeroDias
     *
     * @param float $numeroDias
     *
     * @return RhuPagoDetalle
     */
    public function setNumeroDias($numeroDias)
    {
        $this->numeroDias = $numeroDias;

        return $this;
    }

    /**
     * Get numeroDias
     *
     * @return float
     */
    public function getNumeroDias()
    {
        return $this->numeroDias;
    }

    /**
     * Set vrDia
     *
     * @param float $vrDia
     *
     * @return RhuPagoDetalle
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
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return RhuPagoDetalle
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return RhuPagoDetalle
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuPagoDetalle
     */
    public function setCodigoPagoConceptoFk($codigoPagoConceptoFk)
    {
        $this->codigoPagoConceptoFk = $codigoPagoConceptoFk;

        return $this;
    }

    /**
     * Get codigoPagoConceptoFk
     *
     * @return integer
     */
    public function getCodigoPagoConceptoFk()
    {
        return $this->codigoPagoConceptoFk;
    }

    /**
     * Set vrIngresoBaseCotizacion
     *
     * @param float $vrIngresoBaseCotizacion
     *
     * @return RhuPagoDetalle
     */
    public function setVrIngresoBaseCotizacion($vrIngresoBaseCotizacion)
    {
        $this->vrIngresoBaseCotizacion = $vrIngresoBaseCotizacion;

        return $this;
    }

    /**
     * Get vrIngresoBaseCotizacion
     *
     * @return float
     */
    public function getVrIngresoBaseCotizacion()
    {
        return $this->vrIngresoBaseCotizacion;
    }

    /**
     * Set vrIngresoBasePrestacion
     *
     * @param float $vrIngresoBasePrestacion
     *
     * @return RhuPagoDetalle
     */
    public function setVrIngresoBasePrestacion($vrIngresoBasePrestacion)
    {
        $this->vrIngresoBasePrestacion = $vrIngresoBasePrestacion;

        return $this;
    }

    /**
     * Get vrIngresoBasePrestacion
     *
     * @return float
     */
    public function getVrIngresoBasePrestacion()
    {
        return $this->vrIngresoBasePrestacion;
    }

    /**
     * Set codigoProgramacionPagoDetalleFk
     *
     * @param integer $codigoProgramacionPagoDetalleFk
     *
     * @return RhuPagoDetalle
     */
    public function setCodigoProgramacionPagoDetalleFk($codigoProgramacionPagoDetalleFk)
    {
        $this->codigoProgramacionPagoDetalleFk = $codigoProgramacionPagoDetalleFk;

        return $this;
    }

    /**
     * Get codigoProgramacionPagoDetalleFk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoDetalleFk()
    {
        return $this->codigoProgramacionPagoDetalleFk;
    }

    /**
     * Set pagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagoRel
     *
     * @return RhuPagoDetalle
     */
    public function setPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagoRel = null)
    {
        $this->pagoRel = $pagoRel;

        return $this;
    }

    /**
     * Get pagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPago
     */
    public function getPagoRel()
    {
        return $this->pagoRel;
    }

    /**
     * Set pagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel
     *
     * @return RhuPagoDetalle
     */
    public function setPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel = null)
    {
        $this->pagoConceptoRel = $pagoConceptoRel;

        return $this;
    }

    /**
     * Get pagoConceptoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto
     */
    public function getPagoConceptoRel()
    {
        return $this->pagoConceptoRel;
    }

    /**
     * Set programacionPagoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionPagoDetalleRel
     *
     * @return RhuPagoDetalle
     */
    public function setProgramacionPagoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionPagoDetalleRel = null)
    {
        $this->programacionPagoDetalleRel = $programacionPagoDetalleRel;

        return $this;
    }

    /**
     * Get programacionPagoDetalleRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle
     */
    public function getProgramacionPagoDetalleRel()
    {
        return $this->programacionPagoDetalleRel;
    }

    /**
     * Set creditoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditoRel
     *
     * @return RhuPagoDetalle
     */
    public function setCreditoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditoRel = null)
    {
        $this->creditoRel = $creditoRel;

        return $this;
    }

    /**
     * Get creditoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCredito
     */
    public function getCreditoRel()
    {
        return $this->creditoRel;
    }

    /**
     * Set diasAusentismo
     *
     * @param integer $diasAusentismo
     *
     * @return RhuPagoDetalle
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

    /**
     * Set adicional
     *
     * @param boolean $adicional
     *
     * @return RhuPagoDetalle
     */
    public function setAdicional($adicional)
    {
        $this->adicional = $adicional;

        return $this;
    }

    /**
     * Get adicional
     *
     * @return boolean
     */
    public function getAdicional()
    {
        return $this->adicional;
    }

    /**
     * Set prestacional
     *
     * @param boolean $prestacional
     *
     * @return RhuPagoDetalle
     */
    public function setPrestacional($prestacional)
    {
        $this->prestacional = $prestacional;

        return $this;
    }

    /**
     * Get prestacional
     *
     * @return boolean
     */
    public function getPrestacional()
    {
        return $this->prestacional;
    }

    /**
     * Set cotizacion
     *
     * @param boolean $cotizacion
     *
     * @return RhuPagoDetalle
     */
    public function setCotizacion($cotizacion)
    {
        $this->cotizacion = $cotizacion;

        return $this;
    }

    /**
     * Get cotizacion
     *
     * @return boolean
     */
    public function getCotizacion()
    {
        return $this->cotizacion;
    }

    /**
     * Set vrIngresoBaseCotizacionAdicional
     *
     * @param float $vrIngresoBaseCotizacionAdicional
     *
     * @return RhuPagoDetalle
     */
    public function setVrIngresoBaseCotizacionAdicional($vrIngresoBaseCotizacionAdicional)
    {
        $this->vrIngresoBaseCotizacionAdicional = $vrIngresoBaseCotizacionAdicional;

        return $this;
    }

    /**
     * Get vrIngresoBaseCotizacionAdicional
     *
     * @return float
     */
    public function getVrIngresoBaseCotizacionAdicional()
    {
        return $this->vrIngresoBaseCotizacionAdicional;
    }

    /**
     * Set vrIngresoBaseCotizacionSalario
     *
     * @param float $vrIngresoBaseCotizacionSalario
     *
     * @return RhuPagoDetalle
     */
    public function setVrIngresoBaseCotizacionSalario($vrIngresoBaseCotizacionSalario)
    {
        $this->vrIngresoBaseCotizacionSalario = $vrIngresoBaseCotizacionSalario;

        return $this;
    }

    /**
     * Get vrIngresoBaseCotizacionSalario
     *
     * @return float
     */
    public function getVrIngresoBaseCotizacionSalario()
    {
        return $this->vrIngresoBaseCotizacionSalario;
    }

    /**
     * Set vrExtra
     *
     * @param float $vrExtra
     *
     * @return RhuPagoDetalle
     */
    public function setVrExtra($vrExtra)
    {
        $this->vrExtra = $vrExtra;

        return $this;
    }

    /**
     * Get vrExtra
     *
     * @return float
     */
    public function getVrExtra()
    {
        return $this->vrExtra;
    }

    /**
     * Set vrIngresoBaseCotizacionIncapacidad
     *
     * @param float $vrIngresoBaseCotizacionIncapacidad
     *
     * @return RhuPagoDetalle
     */
    public function setVrIngresoBaseCotizacionIncapacidad($vrIngresoBaseCotizacionIncapacidad)
    {
        $this->vrIngresoBaseCotizacionIncapacidad = $vrIngresoBaseCotizacionIncapacidad;

        return $this;
    }

    /**
     * Get vrIngresoBaseCotizacionIncapacidad
     *
     * @return float
     */
    public function getVrIngresoBaseCotizacionIncapacidad()
    {
        return $this->vrIngresoBaseCotizacionIncapacidad;
    }

    /**
     * Set salud
     *
     * @param boolean $salud
     *
     * @return RhuPagoDetalle
     */
    public function setSalud($salud)
    {
        $this->salud = $salud;

        return $this;
    }

    /**
     * Get salud
     *
     * @return boolean
     */
    public function getSalud()
    {
        return $this->salud;
    }

    /**
     * Set pension
     *
     * @param boolean $pension
     *
     * @return RhuPagoDetalle
     */
    public function setPension($pension)
    {
        $this->pension = $pension;

        return $this;
    }

    /**
     * Get pension
     *
     * @return boolean
     */
    public function getPension()
    {
        return $this->pension;
    }
}
