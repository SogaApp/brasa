<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionTipoRepository")
 */
class RhuSeleccionTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionTipoPk;            
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="seleccionTipoRel")
     */
    protected $seleccionesSeleccionTipoRel; 
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionesSeleccionTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSeleccionTipoPk
     *
     * @return integer
     */
    public function getCodigoSeleccionTipoPk()
    {
        return $this->codigoSeleccionTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSeleccionTipo
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
     * Add seleccionesSeleccionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionTipoRel
     *
     * @return RhuSeleccionTipo
     */
    public function addSeleccionesSeleccionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionTipoRel)
    {
        $this->seleccionesSeleccionTipoRel[] = $seleccionesSeleccionTipoRel;

        return $this;
    }

    /**
     * Remove seleccionesSeleccionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionTipoRel
     */
    public function removeSeleccionesSeleccionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionTipoRel)
    {
        $this->seleccionesSeleccionTipoRel->removeElement($seleccionesSeleccionTipoRel);
    }

    /**
     * Get seleccionesSeleccionTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesSeleccionTipoRel()
    {
        return $this->seleccionesSeleccionTipoRel;
    }
}
