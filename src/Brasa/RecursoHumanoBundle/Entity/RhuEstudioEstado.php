<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_estudio_estado")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEstudioEstadoRepository")
 */
class RhuEstudioEstado
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_estudio_estado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEstudioEstadoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoEstudio", mappedBy="estudioEstadoRel")
     */
    protected $empleadosEstudiosEstudioEstadoRel;



    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEstudiosEstudioEstadoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEstudioEstadoPk
     *
     * @return integer
     */
    public function getCodigoEstudioEstadoPk()
    {
        return $this->codigoEstudioEstadoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEstudioEstado
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
     * Add empleadosEstudiosEstudioEstadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEstudioEstadoRel
     *
     * @return RhuEstudioEstado
     */
    public function addEmpleadosEstudiosEstudioEstadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEstudioEstadoRel)
    {
        $this->empleadosEstudiosEstudioEstadoRel[] = $empleadosEstudiosEstudioEstadoRel;

        return $this;
    }

    /**
     * Remove empleadosEstudiosEstudioEstadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEstudioEstadoRel
     */
    public function removeEmpleadosEstudiosEstudioEstadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEstudioEstadoRel)
    {
        $this->empleadosEstudiosEstudioEstadoRel->removeElement($empleadosEstudiosEstudioEstadoRel);
    }

    /**
     * Get empleadosEstudiosEstudioEstadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEstudiosEstudioEstadoRel()
    {
        return $this->empleadosEstudiosEstudioEstadoRel;
    }
}
