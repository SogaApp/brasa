<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_capacitacion_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCapacitacionTipoRepository")
 */
class RhuCapacitacionTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_capacitacion_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCapacitacionTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=200)
     */         
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuCapacitacion", mappedBy="capacitacionTipoRel")
     */
    protected $capacitacionesCapacitacionTipoRel;     
    

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->capacitacionesCapacitacionTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCapacitacionTipoPk
     *
     * @return integer
     */
    public function getCodigoCapacitacionTipoPk()
    {
        return $this->codigoCapacitacionTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCapacitacionTipo
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
     * Add capacitacionesCapacitacionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion $capacitacionesCapacitacionTipoRel
     *
     * @return RhuCapacitacionTipo
     */
    public function addCapacitacionesCapacitacionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion $capacitacionesCapacitacionTipoRel)
    {
        $this->capacitacionesCapacitacionTipoRel[] = $capacitacionesCapacitacionTipoRel;

        return $this;
    }

    /**
     * Remove capacitacionesCapacitacionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion $capacitacionesCapacitacionTipoRel
     */
    public function removeCapacitacionesCapacitacionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion $capacitacionesCapacitacionTipoRel)
    {
        $this->capacitacionesCapacitacionTipoRel->removeElement($capacitacionesCapacitacionTipoRel);
    }

    /**
     * Get capacitacionesCapacitacionTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCapacitacionesCapacitacionTipoRel()
    {
        return $this->capacitacionesCapacitacionTipoRel;
    }
}
