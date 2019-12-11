<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_programador")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurProgramadorRepository")
 */
class TurProgramador
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programador_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramadorPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                                                  
    
    /**
     * @ORM\OneToMany(targetEntity="TurPuesto", mappedBy="programadorRel")
     */
    protected $puestosProgramadorRel;                  

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->puestosProgramadorRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoProgramadorPk
     *
     * @return integer
     */
    public function getCodigoProgramadorPk()
    {
        return $this->codigoProgramadorPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurProgramador
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
     * Add puestosProgramadorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestosProgramadorRel
     *
     * @return TurProgramador
     */
    public function addPuestosProgramadorRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestosProgramadorRel)
    {
        $this->puestosProgramadorRel[] = $puestosProgramadorRel;

        return $this;
    }

    /**
     * Remove puestosProgramadorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestosProgramadorRel
     */
    public function removePuestosProgramadorRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestosProgramadorRel)
    {
        $this->puestosProgramadorRel->removeElement($puestosProgramadorRel);
    }

    /**
     * Get puestosProgramadorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuestosProgramadorRel()
    {
        return $this->puestosProgramadorRel;
    }
}
