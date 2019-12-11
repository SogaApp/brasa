<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_credito_tipo_pago")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCreditoTipoPagoRepository")
 */
class RhuCreditoTipoPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_credito_tipo_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCreditoTipoPagoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;            
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCredito", mappedBy="creditoTipoPagoRel")
     */
    protected $creditosCreditoTipoPagoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCreditoPago", mappedBy="creditoTipoPagoRel")
     */
    protected $creditosPagosCreditoTipoPagoRel;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->creditosCreditoTipoPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creditosPagosCreditoTipoPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCreditoTipoPagoPk
     *
     * @return integer
     */
    public function getCodigoCreditoTipoPagoPk()
    {
        return $this->codigoCreditoTipoPagoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCreditoTipoPago
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
     * Add creditosCreditoTipoPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCreditoTipoPagoRel
     *
     * @return RhuCreditoTipoPago
     */
    public function addCreditosCreditoTipoPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCreditoTipoPagoRel)
    {
        $this->creditosCreditoTipoPagoRel[] = $creditosCreditoTipoPagoRel;

        return $this;
    }

    /**
     * Remove creditosCreditoTipoPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCreditoTipoPagoRel
     */
    public function removeCreditosCreditoTipoPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCreditoTipoPagoRel)
    {
        $this->creditosCreditoTipoPagoRel->removeElement($creditosCreditoTipoPagoRel);
    }

    /**
     * Get creditosCreditoTipoPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreditosCreditoTipoPagoRel()
    {
        return $this->creditosCreditoTipoPagoRel;
    }

    /**
     * Add creditosPagosCreditoTipoPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago $creditosPagosCreditoTipoPagoRel
     *
     * @return RhuCreditoTipoPago
     */
    public function addCreditosPagosCreditoTipoPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago $creditosPagosCreditoTipoPagoRel)
    {
        $this->creditosPagosCreditoTipoPagoRel[] = $creditosPagosCreditoTipoPagoRel;

        return $this;
    }

    /**
     * Remove creditosPagosCreditoTipoPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago $creditosPagosCreditoTipoPagoRel
     */
    public function removeCreditosPagosCreditoTipoPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago $creditosPagosCreditoTipoPagoRel)
    {
        $this->creditosPagosCreditoTipoPagoRel->removeElement($creditosPagosCreditoTipoPagoRel);
    }

    /**
     * Get creditosPagosCreditoTipoPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreditosPagosCreditoTipoPagoRel()
    {
        return $this->creditosPagosCreditoTipoPagoRel;
    }
}
