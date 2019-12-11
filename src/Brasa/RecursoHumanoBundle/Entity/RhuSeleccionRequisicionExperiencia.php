<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_requisicion_experiencia")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionRequisicionExperienciaRepository")
 */
class RhuSeleccionRequisicionExperiencia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_experiencia_requisicion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExperienciaRequisicionPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionRequisito", mappedBy="experienciaRequisicionRel")
     */
    protected $seleccionesRequisitosSeleccionRequisicionExperienciaRel;    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionesRequisitosSeleccionRequisicionExperienciaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoExperienciaRequisicionPk
     *
     * @return integer
     */
    public function getCodigoExperienciaRequisicionPk()
    {
        return $this->codigoExperienciaRequisicionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSeleccionRequisicionExperiencia
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
     * Add seleccionesRequisitosSeleccionRequisicionExperienciaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosSeleccionRequisicionExperienciaRel
     *
     * @return RhuSeleccionRequisicionExperiencia
     */
    public function addSeleccionesRequisitosSeleccionRequisicionExperienciaRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosSeleccionRequisicionExperienciaRel)
    {
        $this->seleccionesRequisitosSeleccionRequisicionExperienciaRel[] = $seleccionesRequisitosSeleccionRequisicionExperienciaRel;

        return $this;
    }

    /**
     * Remove seleccionesRequisitosSeleccionRequisicionExperienciaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosSeleccionRequisicionExperienciaRel
     */
    public function removeSeleccionesRequisitosSeleccionRequisicionExperienciaRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosSeleccionRequisicionExperienciaRel)
    {
        $this->seleccionesRequisitosSeleccionRequisicionExperienciaRel->removeElement($seleccionesRequisitosSeleccionRequisicionExperienciaRel);
    }

    /**
     * Get seleccionesRequisitosSeleccionRequisicionExperienciaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesRequisitosSeleccionRequisicionExperienciaRel()
    {
        return $this->seleccionesRequisitosSeleccionRequisicionExperienciaRel;
    }
}
