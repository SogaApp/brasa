<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_motivo_cierre_seleccion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuMotivoCierreSeleccionRepository")
 */
class RhuMotivoCierreSeleccion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_motivo_cierre_seleccion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoMotivoCierreSeleccionPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="motivoCierreSeleccionRel")
     */
    protected $seleccionesMotivoCierreSeleccionRel;

    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionesMotivoCierreSeleccionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoMotivoCierreSeleccionPk
     *
     * @return integer
     */
    public function getCodigoMotivoCierreSeleccionPk()
    {
        return $this->codigoMotivoCierreSeleccionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuMotivoCierreSeleccion
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
     * Add seleccionesMotivoCierreSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesMotivoCierreSeleccionRel
     *
     * @return RhuMotivoCierreSeleccion
     */
    public function addSeleccionesMotivoCierreSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesMotivoCierreSeleccionRel)
    {
        $this->seleccionesMotivoCierreSeleccionRel[] = $seleccionesMotivoCierreSeleccionRel;

        return $this;
    }

    /**
     * Remove seleccionesMotivoCierreSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesMotivoCierreSeleccionRel
     */
    public function removeSeleccionesMotivoCierreSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesMotivoCierreSeleccionRel)
    {
        $this->seleccionesMotivoCierreSeleccionRel->removeElement($seleccionesMotivoCierreSeleccionRel);
    }

    /**
     * Get seleccionesMotivoCierreSeleccionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesMotivoCierreSeleccionRel()
    {
        return $this->seleccionesMotivoCierreSeleccionRel;
    }
}
