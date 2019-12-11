<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_grado_bachiller")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuGradoBachillerRepository")
 */
class RhuGradoBachiller
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_grado_bachiller_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoGradoBachillerPk;
    
    /**
     * @ORM\Column(name="grado", type="string", length=60, nullable=true)
     */    
    private $grado;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoEstudio", mappedBy="gradoBachillerRel")
     */
    protected $empleadosEstudiosGradoBachillerRel;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEstudiosGradoBachillerRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoGradoBachillerPk
     *
     * @return integer
     */
    public function getCodigoGradoBachillerPk()
    {
        return $this->codigoGradoBachillerPk;
    }

    /**
     * Set grado
     *
     * @param string $grado
     *
     * @return RhuGradoBachiller
     */
    public function setGrado($grado)
    {
        $this->grado = $grado;

        return $this;
    }

    /**
     * Get grado
     *
     * @return string
     */
    public function getGrado()
    {
        return $this->grado;
    }

    /**
     * Add empleadosEstudiosGradoBachillerRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosGradoBachillerRel
     *
     * @return RhuGradoBachiller
     */
    public function addEmpleadosEstudiosGradoBachillerRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosGradoBachillerRel)
    {
        $this->empleadosEstudiosGradoBachillerRel[] = $empleadosEstudiosGradoBachillerRel;

        return $this;
    }

    /**
     * Remove empleadosEstudiosGradoBachillerRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosGradoBachillerRel
     */
    public function removeEmpleadosEstudiosGradoBachillerRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosGradoBachillerRel)
    {
        $this->empleadosEstudiosGradoBachillerRel->removeElement($empleadosEstudiosGradoBachillerRel);
    }

    /**
     * Get empleadosEstudiosGradoBachillerRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEstudiosGradoBachillerRel()
    {
        return $this->empleadosEstudiosGradoBachillerRel;
    }
}
