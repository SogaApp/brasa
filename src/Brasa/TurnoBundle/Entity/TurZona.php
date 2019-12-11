<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_zona")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurZonaRepository")
 */
class TurZona
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_zona_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoZonaPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                                                  
    
    /**
     * @ORM\OneToMany(targetEntity="TurPuesto", mappedBy="zonaRel")
     */
    protected $puestosZonaRel;                  

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->puestosZonaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoZonaPk
     *
     * @return integer
     */
    public function getCodigoZonaPk()
    {
        return $this->codigoZonaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurZona
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
     * Add puestosZonaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestosZonaRel
     *
     * @return TurZona
     */
    public function addPuestosZonaRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestosZonaRel)
    {
        $this->puestosZonaRel[] = $puestosZonaRel;

        return $this;
    }

    /**
     * Remove puestosZonaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestosZonaRel
     */
    public function removePuestosZonaRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestosZonaRel)
    {
        $this->puestosZonaRel->removeElement($puestosZonaRel);
    }

    /**
     * Get puestosZonaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuestosZonaRel()
    {
        return $this->puestosZonaRel;
    }
}
