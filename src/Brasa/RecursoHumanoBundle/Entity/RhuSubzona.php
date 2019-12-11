<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_subzona")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSubzonaRepository")
 */
class RhuSubzona
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_subzona_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSubzonaPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;          
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="subzonaRel")
     */
    protected $empleadosSubzonaRel;    
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosSubzonaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSubzonaPk
     *
     * @return integer
     */
    public function getCodigoSubzonaPk()
    {
        return $this->codigoSubzonaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSubzona
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
     * Add empleadosSubzonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSubzonaRel
     *
     * @return RhuSubzona
     */
    public function addEmpleadosSubzonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSubzonaRel)
    {
        $this->empleadosSubzonaRel[] = $empleadosSubzonaRel;

        return $this;
    }

    /**
     * Remove empleadosSubzonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSubzonaRel
     */
    public function removeEmpleadosSubzonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSubzonaRel)
    {
        $this->empleadosSubzonaRel->removeElement($empleadosSubzonaRel);
    }

    /**
     * Get empleadosSubzonaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosSubzonaRel()
    {
        return $this->empleadosSubzonaRel;
    }
}
