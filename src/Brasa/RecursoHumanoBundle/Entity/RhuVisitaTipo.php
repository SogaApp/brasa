<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_visita_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuVisitaTipoRepository")
 */
class RhuVisitaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_visita_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVisitaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;            
    
    /**
     * @ORM\OneToMany(targetEntity="RhuVisita", mappedBy="visitaTipoRel")
     */
    protected $visitasVisitaTipoRel;
      
   
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->visitasVisitaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoVisitaTipoPk
     *
     * @return integer
     */
    public function getCodigoVisitaTipoPk()
    {
        return $this->codigoVisitaTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuVisitaTipo
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
     * Add visitasVisitaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasVisitaTipoRel
     *
     * @return RhuVisitaTipo
     */
    public function addVisitasVisitaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasVisitaTipoRel)
    {
        $this->visitasVisitaTipoRel[] = $visitasVisitaTipoRel;

        return $this;
    }

    /**
     * Remove visitasVisitaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasVisitaTipoRel
     */
    public function removeVisitasVisitaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasVisitaTipoRel)
    {
        $this->visitasVisitaTipoRel->removeElement($visitasVisitaTipoRel);
    }

    /**
     * Get visitasVisitaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitasVisitaTipoRel()
    {
        return $this->visitasVisitaTipoRel;
    }
}
