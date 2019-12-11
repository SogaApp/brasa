<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_tipo_referencia")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionTipoReferenciaRepository")
 */
class RhuSeleccionTipoReferencia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_tipo_referencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionTipoReferenciaPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionReferencia", mappedBy="seleccionTipoReferenciaRel")
     */
    protected $seleccionesReferenciasSelecionTipoReferenciaRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionesReferenciasSelecionTipoReferenciaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSeleccionTipoReferenciaPk
     *
     * @return integer
     */
    public function getCodigoSeleccionTipoReferenciaPk()
    {
        return $this->codigoSeleccionTipoReferenciaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSeleccionTipoReferencia
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
     * Add seleccionesReferenciasSelecionTipoReferenciaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia $seleccionesReferenciasSelecionTipoReferenciaRel
     *
     * @return RhuSeleccionTipoReferencia
     */
    public function addSeleccionesReferenciasSelecionTipoReferenciaRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia $seleccionesReferenciasSelecionTipoReferenciaRel)
    {
        $this->seleccionesReferenciasSelecionTipoReferenciaRel[] = $seleccionesReferenciasSelecionTipoReferenciaRel;

        return $this;
    }

    /**
     * Remove seleccionesReferenciasSelecionTipoReferenciaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia $seleccionesReferenciasSelecionTipoReferenciaRel
     */
    public function removeSeleccionesReferenciasSelecionTipoReferenciaRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia $seleccionesReferenciasSelecionTipoReferenciaRel)
    {
        $this->seleccionesReferenciasSelecionTipoReferenciaRel->removeElement($seleccionesReferenciasSelecionTipoReferenciaRel);
    }

    /**
     * Get seleccionesReferenciasSelecionTipoReferenciaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesReferenciasSelecionTipoReferenciaRel()
    {
        return $this->seleccionesReferenciasSelecionTipoReferenciaRel;
    }
}
