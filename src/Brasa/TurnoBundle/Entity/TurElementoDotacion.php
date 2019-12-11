<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_elemento_dotacion")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurElementoDotacionRepository")
 */
class TurElementoDotacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_elemento_dotacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoElementoDotacionPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */    
    private $nombre;     

    /**
     * @ORM\Column(name="costo", type="float")
     */
    private $costo = 0;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurPuestoDotacion", mappedBy="elementoDotacionRel")
     */
    protected $puestosDotacionesElementoDotacionRel;
    
    /**
     * Get codigoElementoDotacionPk
     *
     * @return integer
     */
    public function getCodigoElementoDotacionPk()
    {
        return $this->codigoElementoDotacionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurElementoDotacion
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
     * Set costo
     *
     * @param float $costo
     *
     * @return TurElementoDotacion
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float
     */
    public function getCosto()
    {
        return $this->costo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->puestosDotacionesElementoDotacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add puestosDotacionesElementoDotacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesElementoDotacionRel
     *
     * @return TurElementoDotacion
     */
    public function addPuestosDotacionesElementoDotacionRel(\Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesElementoDotacionRel)
    {
        $this->puestosDotacionesElementoDotacionRel[] = $puestosDotacionesElementoDotacionRel;

        return $this;
    }

    /**
     * Remove puestosDotacionesElementoDotacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesElementoDotacionRel
     */
    public function removePuestosDotacionesElementoDotacionRel(\Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesElementoDotacionRel)
    {
        $this->puestosDotacionesElementoDotacionRel->removeElement($puestosDotacionesElementoDotacionRel);
    }

    /**
     * Get puestosDotacionesElementoDotacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuestosDotacionesElementoDotacionRel()
    {
        return $this->puestosDotacionesElementoDotacionRel;
    }
}
