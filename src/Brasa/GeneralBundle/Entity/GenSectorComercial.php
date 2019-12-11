<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_sector_comercial")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenSectorComercialRepository")
 */
class GenSectorComercial
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sector_comercial_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoSectorComercialPk;     
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */      
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCliente", mappedBy="sectorComercialRel")
     */
    protected $turClientesSectorComercialRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->turClientesSectorComercialRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSectorComercialPk
     *
     * @return integer
     */
    public function getCodigoSectorComercialPk()
    {
        return $this->codigoSectorComercialPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenSectorComercial
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
     * Add turClientesSectorComercialRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesSectorComercialRel
     *
     * @return GenSectorComercial
     */
    public function addTurClientesSectorComercialRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesSectorComercialRel)
    {
        $this->turClientesSectorComercialRel[] = $turClientesSectorComercialRel;

        return $this;
    }

    /**
     * Remove turClientesSectorComercialRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesSectorComercialRel
     */
    public function removeTurClientesSectorComercialRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesSectorComercialRel)
    {
        $this->turClientesSectorComercialRel->removeElement($turClientesSectorComercialRel);
    }

    /**
     * Get turClientesSectorComercialRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurClientesSectorComercialRel()
    {
        return $this->turClientesSectorComercialRel;
    }
}
