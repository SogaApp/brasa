<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_detalle_sede")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoDetalleSedeRepository")
 */
class RhuPagoDetalleSede
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_detalle_sede_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoDetalleSedePk;
    
    /**
     * @ORM\Column(name="codigo_pago_fk", type="integer", nullable=true)
     */    
    private $codigoPagoFk;      
    
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
     * @ORM\Column(name="codigo_sede_fk", type="integer", nullable=true)
     */    
    private $codigoSedeFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPago", inversedBy="pagosDetallesSedesPagoRel")
     * @ORM\JoinColumn(name="codigo_pago_fk", referencedColumnName="codigo_pago_pk")
     */
    protected $pagoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="pagosDetallesSedesPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuSede", inversedBy="pagosDetallesSedesSedeRel")
     * @ORM\JoinColumn(name="codigo_sede_fk", referencedColumnName="codigo_sede_pk")
     */
    protected $sedeRel;     




    /**
     * Get codigoPagoDetalleSedePk
     *
     * @return integer
     */
    public function getCodigoPagoDetalleSedePk()
    {
        return $this->codigoPagoDetalleSedePk;
    }

    /**
     * Set codigoPagoFk
     *
     * @param integer $codigoPagoFk
     *
     * @return RhuPagoDetalleSede
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
     * Set vrPago
     *
     * @param float $vrPago
     *
     * @return RhuPagoDetalleSede
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
     * @return RhuPagoDetalleSede
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
     * @return RhuPagoDetalleSede
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
     * @return RhuPagoDetalleSede
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
     * @return RhuPagoDetalleSede
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
     * @return RhuPagoDetalleSede
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
     * Set vrDia
     *
     * @param float $vrDia
     *
     * @return RhuPagoDetalleSede
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
     * @return RhuPagoDetalleSede
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
     * @return RhuPagoDetalleSede
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
     * @return RhuPagoDetalleSede
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
     * @return RhuPagoDetalleSede
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
     * Set codigoSedeFk
     *
     * @param integer $codigoSedeFk
     *
     * @return RhuPagoDetalleSede
     */
    public function setCodigoSedeFk($codigoSedeFk)
    {
        $this->codigoSedeFk = $codigoSedeFk;

        return $this;
    }

    /**
     * Get codigoSedeFk
     *
     * @return integer
     */
    public function getCodigoSedeFk()
    {
        return $this->codigoSedeFk;
    }

    /**
     * Set pagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagoRel
     *
     * @return RhuPagoDetalleSede
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
     * @return RhuPagoDetalleSede
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
     * Set sedeRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSede $sedeRel
     *
     * @return RhuPagoDetalleSede
     */
    public function setSedeRel(\Brasa\RecursoHumanoBundle\Entity\RhuSede $sedeRel = null)
    {
        $this->sedeRel = $sedeRel;

        return $this;
    }

    /**
     * Get sedeRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSede
     */
    public function getSedeRel()
    {
        return $this->sedeRel;
    }
}
