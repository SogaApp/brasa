<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_nota_credito_concepto")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarNotaCreditoConceptoRepository")
 */
class CarNotaCreditoConcepto
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_nota_credito_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoNotaCreditoConceptoPk;        

    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    
    /**
     * @ORM\OneToMany(targetEntity="CarNotaCredito", mappedBy="notaCreditoConceptoRel")
     */
    protected $notasCreditosConceptoRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notasCreditosConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoNotaCreditoConceptoPk
     *
     * @return integer
     */
    public function getCodigoNotaCreditoConceptoPk()
    {
        return $this->codigoNotaCreditoConceptoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CarNotaCreditoConcepto
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
     * Add notasCreditosConceptoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaCredito $notasCreditosConceptoRel
     *
     * @return CarNotaCreditoConcepto
     */
    public function addNotasCreditosConceptoRel(\Brasa\CarteraBundle\Entity\CarNotaCredito $notasCreditosConceptoRel)
    {
        $this->notasCreditosConceptoRel[] = $notasCreditosConceptoRel;

        return $this;
    }

    /**
     * Remove notasCreditosConceptoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaCredito $notasCreditosConceptoRel
     */
    public function removeNotasCreditosConceptoRel(\Brasa\CarteraBundle\Entity\CarNotaCredito $notasCreditosConceptoRel)
    {
        $this->notasCreditosConceptoRel->removeElement($notasCreditosConceptoRel);
    }

    /**
     * Get notasCreditosConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotasCreditosConceptoRel()
    {
        return $this->notasCreditosConceptoRel;
    }
}
