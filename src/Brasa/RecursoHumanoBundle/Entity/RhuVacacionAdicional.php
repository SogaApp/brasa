<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_vacacion_adicional")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuVacacionAdicionalRepository")
 */
class RhuVacacionAdicional
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_vacacion_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVacacionAdicionalPk;
    
    /**
     * @ORM\Column(name="codigo_vacacion_fk", type="integer", nullable=true)
     */    
    private $codigoVacacionFk;
    
    /**
     * @ORM\Column(name="codigo_credito_fk", type="integer", nullable=true)
     */    
    private $codigoCreditoFk;
    
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;    
    
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
     * @ORM\ManyToOne(targetEntity="RhuVacacion", inversedBy="vacacionesAdicionalesVacacionRel")
     * @ORM\JoinColumn(name="codigo_vacacion_fk", referencedColumnName="codigo_vacacion_pk")
     */
    protected $vacacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCredito", inversedBy="vacacionesAdicionalesCreditoRel")
     * @ORM\JoinColumn(name="codigo_credito_fk", referencedColumnName="codigo_credito_pk")
     */
    protected $creditoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="vacacionesAdicionalesPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;    



    /**
     * Get codigoVacacionAdicionalPk
     *
     * @return integer
     */
    public function getCodigoVacacionAdicionalPk()
    {
        return $this->codigoVacacionAdicionalPk;
    }

    /**
     * Set codigoVacacionFk
     *
     * @param integer $codigoVacacionFk
     *
     * @return RhuVacacionAdicional
     */
    public function setCodigoVacacionFk($codigoVacacionFk)
    {
        $this->codigoVacacionFk = $codigoVacacionFk;

        return $this;
    }

    /**
     * Get codigoVacacionFk
     *
     * @return integer
     */
    public function getCodigoVacacionFk()
    {
        return $this->codigoVacacionFk;
    }

    /**
     * Set codigoCreditoFk
     *
     * @param integer $codigoCreditoFk
     *
     * @return RhuVacacionAdicional
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
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuVacacionAdicional
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
     * Set vrDeduccion
     *
     * @param float $vrDeduccion
     *
     * @return RhuVacacionAdicional
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
     * Set vrBonificacion
     *
     * @param float $vrBonificacion
     *
     * @return RhuVacacionAdicional
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
     * Set detalle
     *
     * @param string $detalle
     *
     * @return RhuVacacionAdicional
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
     * Set vacacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionRel
     *
     * @return RhuVacacionAdicional
     */
    public function setVacacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionRel = null)
    {
        $this->vacacionRel = $vacacionRel;

        return $this;
    }

    /**
     * Get vacacionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuVacacion
     */
    public function getVacacionRel()
    {
        return $this->vacacionRel;
    }

    /**
     * Set creditoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditoRel
     *
     * @return RhuVacacionAdicional
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
     * Set pagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel
     *
     * @return RhuVacacionAdicional
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
