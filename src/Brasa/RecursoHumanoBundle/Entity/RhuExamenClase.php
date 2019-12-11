<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen_clase")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenClaseRepository")
 */
class RhuExamenClase
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_clase_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenClasePk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;            
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamen", mappedBy="examenClaseRel")
     */
    protected $examenesExamenClaseRel;
      
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->examenesExamenClaseRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoExamenClasePk
     *
     * @return integer
     */
    public function getCodigoExamenClasePk()
    {
        return $this->codigoExamenClasePk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuExamenClase
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
     * Add examenesExamenClaseRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesExamenClaseRel
     *
     * @return RhuExamenClase
     */
    public function addExamenesExamenClaseRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesExamenClaseRel)
    {
        $this->examenesExamenClaseRel[] = $examenesExamenClaseRel;

        return $this;
    }

    /**
     * Remove examenesExamenClaseRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesExamenClaseRel
     */
    public function removeExamenesExamenClaseRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesExamenClaseRel)
    {
        $this->examenesExamenClaseRel->removeElement($examenesExamenClaseRel);
    }

    /**
     * Get examenesExamenClaseRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesExamenClaseRel()
    {
        return $this->examenesExamenClaseRel;
    }
}
