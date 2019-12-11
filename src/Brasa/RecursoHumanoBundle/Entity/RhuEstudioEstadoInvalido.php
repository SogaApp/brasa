<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_estudio_estado_invalido")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEstudioEstadoInvalidoRepository")
 */
class RhuEstudioEstadoInvalido
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_estudio_estado_invalido_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEstudioEstadoInvalidoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoEstudio", mappedBy="estudioEstadoInvalidoRel")
     */
    protected $empleadosEstudiosEstudioEstadoInvalidoRel;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEstudiosEstudioEstadoInvalidoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEstudioEstadoInvalidoPk
     *
     * @return integer
     */
    public function getCodigoEstudioEstadoInvalidoPk()
    {
        return $this->codigoEstudioEstadoInvalidoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEstudioEstadoInvalido
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
     * Add empleadosEstudiosEstudioEstadoInvalidoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEstudioEstadoInvalidoRel
     *
     * @return RhuEstudioEstadoInvalido
     */
    public function addEmpleadosEstudiosEstudioEstadoInvalidoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEstudioEstadoInvalidoRel)
    {
        $this->empleadosEstudiosEstudioEstadoInvalidoRel[] = $empleadosEstudiosEstudioEstadoInvalidoRel;

        return $this;
    }

    /**
     * Remove empleadosEstudiosEstudioEstadoInvalidoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEstudioEstadoInvalidoRel
     */
    public function removeEmpleadosEstudiosEstudioEstadoInvalidoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEstudioEstadoInvalidoRel)
    {
        $this->empleadosEstudiosEstudioEstadoInvalidoRel->removeElement($empleadosEstudiosEstudioEstadoInvalidoRel);
    }

    /**
     * Get empleadosEstudiosEstudioEstadoInvalidoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEstudiosEstudioEstadoInvalidoRel()
    {
        return $this->empleadosEstudiosEstudioEstadoInvalidoRel;
    }
}
