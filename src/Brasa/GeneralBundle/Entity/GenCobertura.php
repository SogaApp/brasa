<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_cobertura")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenCoberturaRepository")
 */
class GenCobertura
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cobertura_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoCoberturaPk;     
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */      
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCliente", mappedBy="coberturaRel")
     */
    protected $turClientesCoberturaRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->turClientesCoberturaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCoberturaPk
     *
     * @return integer
     */
    public function getCodigoCoberturaPk()
    {
        return $this->codigoCoberturaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenCobertura
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
     * Add turClientesCoberturaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesCoberturaRel
     *
     * @return GenCobertura
     */
    public function addTurClientesCoberturaRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesCoberturaRel)
    {
        $this->turClientesCoberturaRel[] = $turClientesCoberturaRel;

        return $this;
    }

    /**
     * Remove turClientesCoberturaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesCoberturaRel
     */
    public function removeTurClientesCoberturaRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesCoberturaRel)
    {
        $this->turClientesCoberturaRel->removeElement($turClientesCoberturaRel);
    }

    /**
     * Get turClientesCoberturaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurClientesCoberturaRel()
    {
        return $this->turClientesCoberturaRel;
    }
}
