<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_prueba_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionPruebaTipoRepository")
 */
class RhuSeleccionPruebaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_prueba_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionPruebaTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionPrueba", mappedBy="seleccionPruebaTipoRel")
     */
    protected $seleccionesPruebasSelecionPruebaTipoRel;

 
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionesPruebasSelecionPruebaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSeleccionPruebaTipoPk
     *
     * @return integer
     */
    public function getCodigoSeleccionPruebaTipoPk()
    {
        return $this->codigoSeleccionPruebaTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSeleccionPruebaTipo
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
     * Add seleccionesPruebasSelecionPruebaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba $seleccionesPruebasSelecionPruebaTipoRel
     *
     * @return RhuSeleccionPruebaTipo
     */
    public function addSeleccionesPruebasSelecionPruebaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba $seleccionesPruebasSelecionPruebaTipoRel)
    {
        $this->seleccionesPruebasSelecionPruebaTipoRel[] = $seleccionesPruebasSelecionPruebaTipoRel;

        return $this;
    }

    /**
     * Remove seleccionesPruebasSelecionPruebaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba $seleccionesPruebasSelecionPruebaTipoRel
     */
    public function removeSeleccionesPruebasSelecionPruebaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba $seleccionesPruebasSelecionPruebaTipoRel)
    {
        $this->seleccionesPruebasSelecionPruebaTipoRel->removeElement($seleccionesPruebasSelecionPruebaTipoRel);
    }

    /**
     * Get seleccionesPruebasSelecionPruebaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesPruebasSelecionPruebaTipoRel()
    {
        return $this->seleccionesPruebasSelecionPruebaTipoRel;
    }
}
