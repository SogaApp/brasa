<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_credito_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCreditoTipoRepository")
 */
class RhuCreditoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_credito_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCreditoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="cupo_maximo", type="integer", nullable=true)
     */    
    private $cupoMaximo;
    
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="creditosTiposPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCredito", mappedBy="creditoTipoRel")
     */
    protected $creditosCreditoTipoRel;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->creditosCreditoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCreditoTipoPk
     *
     * @return integer
     */
    public function getCodigoCreditoTipoPk()
    {
        return $this->codigoCreditoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCreditoTipo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add creditosCreditoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCreditoTipoRel
     *
     * @return RhuCreditoTipo
     */
    public function addCreditosCreditoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCreditoTipoRel)
    {
        $this->creditosCreditoTipoRel[] = $creditosCreditoTipoRel;

        return $this;
    }

    /**
     * Remove creditosCreditoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCreditoTipoRel
     */
    public function removeCreditosCreditoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCreditoTipoRel)
    {
        $this->creditosCreditoTipoRel->removeElement($creditosCreditoTipoRel);
    }

    /**
     * Get creditosCreditoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreditosCreditoTipoRel()
    {
        return $this->creditosCreditoTipoRel;
    }

    /**
     * Set cupoMaximo
     *
     * @param integer $cupoMaximo
     *
     * @return RhuCreditoTipo
     */
    public function setCupoMaximo($cupoMaximo)
    {
        $this->cupoMaximo = $cupoMaximo;

        return $this;
    }

    /**
     * Get cupoMaximo
     *
     * @return integer
     */
    public function getCupoMaximo()
    {
        return $this->cupoMaximo;
    }

    /**
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuCreditoTipo
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
     * @return RhuCreditoTipo
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
