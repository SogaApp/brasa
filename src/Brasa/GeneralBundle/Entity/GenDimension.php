<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_dimension")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenDimensionRepository")
 */
class GenDimension
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_dimension_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoDimensionPk;     
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */      
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCliente", mappedBy="dimensionRel")
     */
    protected $turClientesDimensionRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->turClientesDimensionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDimensionPk
     *
     * @return integer
     */
    public function getCodigoDimensionPk()
    {
        return $this->codigoDimensionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenDimension
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
     * Add turClientesDimensionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesDimensionRel
     *
     * @return GenDimension
     */
    public function addTurClientesDimensionRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesDimensionRel)
    {
        $this->turClientesDimensionRel[] = $turClientesDimensionRel;

        return $this;
    }

    /**
     * Remove turClientesDimensionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesDimensionRel
     */
    public function removeTurClientesDimensionRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesDimensionRel)
    {
        $this->turClientesDimensionRel->removeElement($turClientesDimensionRel);
    }

    /**
     * Get turClientesDimensionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurClientesDimensionRel()
    {
        return $this->turClientesDimensionRel;
    }
}
