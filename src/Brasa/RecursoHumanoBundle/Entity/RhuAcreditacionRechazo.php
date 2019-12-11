<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_acreditacion_rechazo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuAcreditacionRechazoRepository")
 */
class RhuAcreditacionRechazo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_acreditacion_rechazo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAcreditacionRechazoPk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuAcreditacion", mappedBy="acreditacionRechazoRel")
     */
    protected $acreditacionesAcreditacionRechazoRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->acreditacionesAcreditacionRechazoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoAcreditacionRechazoPk
     *
     * @return integer
     */
    public function getCodigoAcreditacionRechazoPk()
    {
        return $this->codigoAcreditacionRechazoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuAcreditacionRechazo
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
     * Add acreditacionesAcreditacionRechazoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesAcreditacionRechazoRel
     *
     * @return RhuAcreditacionRechazo
     */
    public function addAcreditacionesAcreditacionRechazoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesAcreditacionRechazoRel)
    {
        $this->acreditacionesAcreditacionRechazoRel[] = $acreditacionesAcreditacionRechazoRel;

        return $this;
    }

    /**
     * Remove acreditacionesAcreditacionRechazoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesAcreditacionRechazoRel
     */
    public function removeAcreditacionesAcreditacionRechazoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesAcreditacionRechazoRel)
    {
        $this->acreditacionesAcreditacionRechazoRel->removeElement($acreditacionesAcreditacionRechazoRel);
    }

    /**
     * Get acreditacionesAcreditacionRechazoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAcreditacionesAcreditacionRechazoRel()
    {
        return $this->acreditacionesAcreditacionRechazoRel;
    }
}
