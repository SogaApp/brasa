<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_entrevista_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionEntrevistaTipoRepository")
 */
class RhuSeleccionEntrevistaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_entrevista_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionEntrevistaTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionEntrevista", mappedBy="seleccionEntrevistaTipoRel")
     */
    protected $seleccionesEntrevistasSelecionEntrevistaTipoRel;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;

 
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionesEntrevistasSelecionEntrevistaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSeleccionEntrevistaTipoPk
     *
     * @return integer
     */
    public function getCodigoSeleccionEntrevistaTipoPk()
    {
        return $this->codigoSeleccionEntrevistaTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSeleccionEntrevistaTipo
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
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuSeleccionEntrevistaTipo
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * Add seleccionesEntrevistasSelecionEntrevistaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevista $seleccionesEntrevistasSelecionEntrevistaTipoRel
     *
     * @return RhuSeleccionEntrevistaTipo
     */
    public function addSeleccionesEntrevistasSelecionEntrevistaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevista $seleccionesEntrevistasSelecionEntrevistaTipoRel)
    {
        $this->seleccionesEntrevistasSelecionEntrevistaTipoRel[] = $seleccionesEntrevistasSelecionEntrevistaTipoRel;

        return $this;
    }

    /**
     * Remove seleccionesEntrevistasSelecionEntrevistaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevista $seleccionesEntrevistasSelecionEntrevistaTipoRel
     */
    public function removeSeleccionesEntrevistasSelecionEntrevistaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevista $seleccionesEntrevistasSelecionEntrevistaTipoRel)
    {
        $this->seleccionesEntrevistasSelecionEntrevistaTipoRel->removeElement($seleccionesEntrevistasSelecionEntrevistaTipoRel);
    }

    /**
     * Get seleccionesEntrevistasSelecionEntrevistaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesEntrevistasSelecionEntrevistaTipoRel()
    {
        return $this->seleccionesEntrevistasSelecionEntrevistaTipoRel;
    }
}
