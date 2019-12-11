<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_nota_debito_concepto")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarNotaDebitoConceptoRepository")
 */
class CarNotaDebitoConcepto
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_nota_debito_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoNotaDebitoConceptoPk;        

    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    
    /**
     * @ORM\OneToMany(targetEntity="CarNotaDebito", mappedBy="notaDebitoConceptoRel")
     */
    protected $notasDebitosConceptoRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notasDebitosConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoNotaDebitoConceptoPk
     *
     * @return integer
     */
    public function getCodigoNotaDebitoConceptoPk()
    {
        return $this->codigoNotaDebitoConceptoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CarNotaDebitoConcepto
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
     * Add notasDebitosConceptoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebito $notasDebitosConceptoRel
     *
     * @return CarNotaDebitoConcepto
     */
    public function addNotasDebitosConceptoRel(\Brasa\CarteraBundle\Entity\CarNotaDebito $notasDebitosConceptoRel)
    {
        $this->notasDebitosConceptoRel[] = $notasDebitosConceptoRel;

        return $this;
    }

    /**
     * Remove notasDebitosConceptoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebito $notasDebitosConceptoRel
     */
    public function removeNotasDebitosConceptoRel(\Brasa\CarteraBundle\Entity\CarNotaDebito $notasDebitosConceptoRel)
    {
        $this->notasDebitosConceptoRel->removeElement($notasDebitosConceptoRel);
    }

    /**
     * Get notasDebitosConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotasDebitosConceptoRel()
    {
        return $this->notasDebitosConceptoRel;
    }
}
