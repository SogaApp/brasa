<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_liquidacion_adicionales")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuLiquidacionAdicionalesRepository")
 */
class RhuLiquidacionAdicionales
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_liquidacion_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLiquidacionAdicionalPk;
    
    /**
     * @ORM\Column(name="codigo_liquidacion_fk", type="integer", nullable=true)
     */    
    private $codigoLiquidacionFk;
    
    /**
     * @ORM\Column(name="codigo_credito_fk", type="integer", nullable=true)
     */    
    private $codigoCreditoFk;
    
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;    
    
    /**
     * @ORM\Column(name="codigo_liquidacion_adicional_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoLiquidacionAdicionalConceptoFk;    
    
    /**
     * @ORM\Column(name="vr_deduccion", type="float")
     */
    private $vrDeduccion = 0;
    
    /**
     * @ORM\Column(name="vr_bonificacion", type="float")
     */
    private $vrBonificacion = 0;
    
    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */    
    private $detalle;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
        
    /**
     * @ORM\ManyToOne(targetEntity="RhuLiquidacion", inversedBy="liquidacionesAdicionalesLiquidacionRel")
     * @ORM\JoinColumn(name="codigo_liquidacion_fk", referencedColumnName="codigo_liquidacion_pk")
     */
    protected $liquidacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCredito", inversedBy="liquidacionesAdicionalesCreditoRel")
     * @ORM\JoinColumn(name="codigo_credito_fk", referencedColumnName="codigo_credito_pk")
     */
    protected $creditoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuLiquidacionAdicionalesConcepto", inversedBy="liquidacionesAdicionalesLiquidacionAdicionalConceptoRel")
     * @ORM\JoinColumn(name="codigo_liquidacion_adicional_concepto_fk", referencedColumnName="codigo_liquidacion_adicional_concepto_pk")
     */
    protected $liquidacionAdicionalConceptoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="liquidacionesAdicionalesPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel; 

    /**
     * Get codigoLiquidacionAdicionalPk
     *
     * @return integer
     */
    public function getCodigoLiquidacionAdicionalPk()
    {
        return $this->codigoLiquidacionAdicionalPk;
    }

    /**
     * Set codigoLiquidacionFk
     *
     * @param integer $codigoLiquidacionFk
     *
     * @return RhuLiquidacionAdicionales
     */
    public function setCodigoLiquidacionFk($codigoLiquidacionFk)
    {
        $this->codigoLiquidacionFk = $codigoLiquidacionFk;

        return $this;
    }

    /**
     * Get codigoLiquidacionFk
     *
     * @return integer
     */
    public function getCodigoLiquidacionFk()
    {
        return $this->codigoLiquidacionFk;
    }

    /**
     * Set codigoCreditoFk
     *
     * @param integer $codigoCreditoFk
     *
     * @return RhuLiquidacionAdicionales
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
     * Set codigoLiquidacionAdicionalConceptoFk
     *
     * @param integer $codigoLiquidacionAdicionalConceptoFk
     *
     * @return RhuLiquidacionAdicionales
     */
    public function setCodigoLiquidacionAdicionalConceptoFk($codigoLiquidacionAdicionalConceptoFk)
    {
        $this->codigoLiquidacionAdicionalConceptoFk = $codigoLiquidacionAdicionalConceptoFk;

        return $this;
    }

    /**
     * Get codigoLiquidacionAdicionalConceptoFk
     *
     * @return integer
     */
    public function getCodigoLiquidacionAdicionalConceptoFk()
    {
        return $this->codigoLiquidacionAdicionalConceptoFk;
    }

    /**
     * Set vrDeduccion
     *
     * @param float $vrDeduccion
     *
     * @return RhuLiquidacionAdicionales
     */
    public function setVrDeduccion($vrDeduccion)
    {
        $this->vrDeduccion = $vrDeduccion;

        return $this;
    }

    /**
     * Get vrDeduccion
     *
     * @return float
     */
    public function getVrDeduccion()
    {
        return $this->vrDeduccion;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return RhuLiquidacionAdicionales
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
     * Set liquidacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionRel
     *
     * @return RhuLiquidacionAdicionales
     */
    public function setLiquidacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionRel = null)
    {
        $this->liquidacionRel = $liquidacionRel;

        return $this;
    }

    /**
     * Get liquidacionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion
     */
    public function getLiquidacionRel()
    {
        return $this->liquidacionRel;
    }

    /**
     * Set creditoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditoRel
     *
     * @return RhuLiquidacionAdicionales
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
     * Set liquidacionAdicionalConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionalesConcepto $liquidacionAdicionalConceptoRel
     *
     * @return RhuLiquidacionAdicionales
     */
    public function setLiquidacionAdicionalConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionalesConcepto $liquidacionAdicionalConceptoRel = null)
    {
        $this->liquidacionAdicionalConceptoRel = $liquidacionAdicionalConceptoRel;

        return $this;
    }

    /**
     * Get liquidacionAdicionalConceptoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionalesConcepto
     */
    public function getLiquidacionAdicionalConceptoRel()
    {
        return $this->liquidacionAdicionalConceptoRel;
    }

    /**
     * Set vrBonificacion
     *
     * @param float $vrBonificacion
     *
     * @return RhuLiquidacionAdicionales
     */
    public function setVrBonificacion($vrBonificacion)
    {
        $this->vrBonificacion = $vrBonificacion;

        return $this;
    }

    /**
     * Get vrBonificacion
     *
     * @return float
     */
    public function getVrBonificacion()
    {
        return $this->vrBonificacion;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuLiquidacionAdicionales
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
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuLiquidacionAdicionales
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
     * Set pagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel
     *
     * @return RhuLiquidacionAdicionales
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
}
