<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_sector_economico")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenSectorEconomicoRepository")
 */
class GenSectorEconomico
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sector_economico_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoSectorEconomicoPk;     
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */      
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCliente", mappedBy="sectorEconomicoRel")
     */
    protected $turClientesSectorEconomicoRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->turClientesSectorEconomicoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSectorEconomicoPk
     *
     * @return integer
     */
    public function getCodigoSectorEconomicoPk()
    {
        return $this->codigoSectorEconomicoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenSectorEconomico
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
     * Add turClientesSectorEconomicoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesSectorEconomicoRel
     *
     * @return GenSectorEconomico
     */
    public function addTurClientesSectorEconomicoRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesSectorEconomicoRel)
    {
        $this->turClientesSectorEconomicoRel[] = $turClientesSectorEconomicoRel;

        return $this;
    }

    /**
     * Remove turClientesSectorEconomicoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesSectorEconomicoRel
     */
    public function removeTurClientesSectorEconomicoRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesSectorEconomicoRel)
    {
        $this->turClientesSectorEconomicoRel->removeElement($turClientesSectorEconomicoRel);
    }

    /**
     * Get turClientesSectorEconomicoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurClientesSectorEconomicoRel()
    {
        return $this->turClientesSectorEconomicoRel;
    }
}
