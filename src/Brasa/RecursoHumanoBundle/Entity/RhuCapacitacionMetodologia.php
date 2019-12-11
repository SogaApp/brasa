<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_capacitacion_metodologia")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCapacitacionMetodologiaRepository")
 */
class RhuCapacitacionMetodologia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_capacitacion_metodologia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCapacitacionMetodologiaPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCapacitacion", mappedBy="capacitacionMetodologiaRel")
     */
    protected $capacitacionesCapacitacionMetodologiaRel;
    
    

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->capacitacionesCapacitacionMetodologiaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCapacitacionMetodologiaPk
     *
     * @return integer
     */
    public function getCodigoCapacitacionMetodologiaPk()
    {
        return $this->codigoCapacitacionMetodologiaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCapacitacionMetodologia
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
     * Add capacitacionesCapacitacionMetodologiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion $capacitacionesCapacitacionMetodologiaRel
     *
     * @return RhuCapacitacionMetodologia
     */
    public function addCapacitacionesCapacitacionMetodologiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion $capacitacionesCapacitacionMetodologiaRel)
    {
        $this->capacitacionesCapacitacionMetodologiaRel[] = $capacitacionesCapacitacionMetodologiaRel;

        return $this;
    }

    /**
     * Remove capacitacionesCapacitacionMetodologiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion $capacitacionesCapacitacionMetodologiaRel
     */
    public function removeCapacitacionesCapacitacionMetodologiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion $capacitacionesCapacitacionMetodologiaRel)
    {
        $this->capacitacionesCapacitacionMetodologiaRel->removeElement($capacitacionesCapacitacionMetodologiaRel);
    }

    /**
     * Get capacitacionesCapacitacionMetodologiaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCapacitacionesCapacitacionMetodologiaRel()
    {
        return $this->capacitacionesCapacitacionMetodologiaRel;
    }
}
