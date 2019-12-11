<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_dotacion_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDotacionTipoRepository")
 */
class RhuDotacionTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_dotacion_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDotacionTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDotacion", mappedBy="dotacionTipoRel")
     */
    protected $dotacionesDotacionTipoRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dotacionesDotacionTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDotacionTipoPk
     *
     * @return integer
     */
    public function getCodigoDotacionTipoPk()
    {
        return $this->codigoDotacionTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuDotacionTipo
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
     * Add dotacionesDotacionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacion $dotacionesDotacionTipoRel
     *
     * @return RhuDotacionTipo
     */
    public function addDotacionesDotacionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacion $dotacionesDotacionTipoRel)
    {
        $this->dotacionesDotacionTipoRel[] = $dotacionesDotacionTipoRel;

        return $this;
    }

    /**
     * Remove dotacionesDotacionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacion $dotacionesDotacionTipoRel
     */
    public function removeDotacionesDotacionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacion $dotacionesDotacionTipoRel)
    {
        $this->dotacionesDotacionTipoRel->removeElement($dotacionesDotacionTipoRel);
    }

    /**
     * Get dotacionesDotacionTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDotacionesDotacionTipoRel()
    {
        return $this->dotacionesDotacionTipoRel;
    }
}
