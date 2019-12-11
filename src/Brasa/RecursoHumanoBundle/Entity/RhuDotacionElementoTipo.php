<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_dotacion_elemento_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDotacionElementoTipoRepository")
 */
class RhuDotacionElementoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_dotacion_elemento_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDotacionElementoTipoPk;                                        
    
    /**
     * @ORM\Column(name="dotacion", type="string", nullable=true)
     */
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDotacionElemento", mappedBy="dotacionElementoTipoRel")
     */
    protected $dotacionesElementosDotacionElementoTipoRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dotacionesElementosDotacionElementoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDotacionElementoTipoPk
     *
     * @return integer
     */
    public function getCodigoDotacionElementoTipoPk()
    {
        return $this->codigoDotacionElementoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuDotacionElementoTipo
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
     * Add dotacionesElementosDotacionElementoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento $dotacionesElementosDotacionElementoTipoRel
     *
     * @return RhuDotacionElementoTipo
     */
    public function addDotacionesElementosDotacionElementoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento $dotacionesElementosDotacionElementoTipoRel)
    {
        $this->dotacionesElementosDotacionElementoTipoRel[] = $dotacionesElementosDotacionElementoTipoRel;

        return $this;
    }

    /**
     * Remove dotacionesElementosDotacionElementoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento $dotacionesElementosDotacionElementoTipoRel
     */
    public function removeDotacionesElementosDotacionElementoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento $dotacionesElementosDotacionElementoTipoRel)
    {
        $this->dotacionesElementosDotacionElementoTipoRel->removeElement($dotacionesElementosDotacionElementoTipoRel);
    }

    /**
     * Get dotacionesElementosDotacionElementoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDotacionesElementosDotacionElementoTipoRel()
    {
        return $this->dotacionesElementosDotacionElementoTipoRel;
    }
}
