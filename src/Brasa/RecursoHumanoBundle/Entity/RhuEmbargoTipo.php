<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_embargo_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmbargoTipoRepository")
 */
class RhuEmbargoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_embargo_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmbargoTipoPk;                              
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;                        
    
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="embargosTiposPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmbargo", mappedBy="embargoTipoRel")
     */
    protected $embargosEmbargoTipoRel; 
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->embargosEmbargoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEmbargoTipoPk
     *
     * @return integer
     */
    public function getCodigoEmbargoTipoPk()
    {
        return $this->codigoEmbargoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEmbargoTipo
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
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuEmbargoTipo
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
     * @return RhuEmbargoTipo
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
     * Add embargosEmbargoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmbargo $embargosEmbargoTipoRel
     *
     * @return RhuEmbargoTipo
     */
    public function addEmbargosEmbargoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmbargo $embargosEmbargoTipoRel)
    {
        $this->embargosEmbargoTipoRel[] = $embargosEmbargoTipoRel;

        return $this;
    }

    /**
     * Remove embargosEmbargoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmbargo $embargosEmbargoTipoRel
     */
    public function removeEmbargosEmbargoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmbargo $embargosEmbargoTipoRel)
    {
        $this->embargosEmbargoTipoRel->removeElement($embargosEmbargoTipoRel);
    }

    /**
     * Get embargosEmbargoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmbargosEmbargoTipoRel()
    {
        return $this->embargosEmbargoTipoRel;
    }
}
