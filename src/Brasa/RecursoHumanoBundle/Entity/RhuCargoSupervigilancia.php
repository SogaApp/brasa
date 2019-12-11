<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_cargo_supervigilancia")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCargoSupervigilanciaRepository")
 */
class RhuCargoSupervigilancia
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cargo_supervigilancia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCargoSupervigilanciaPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;
        
    /**
     * @ORM\OneToMany(targetEntity="RhuCargo", mappedBy="cargoSupervigilanciaRel")
     */
    protected $cargosCargoSupervigilanciaRel;
    

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cargosCargoSupervigilanciaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCargoSupervigilanciaPk
     *
     * @return integer
     */
    public function getCodigoCargoSupervigilanciaPk()
    {
        return $this->codigoCargoSupervigilanciaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCargoSupervigilancia
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
     * Add cargosCargoSupervigilanciaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargosCargoSupervigilanciaRel
     *
     * @return RhuCargoSupervigilancia
     */
    public function addCargosCargoSupervigilanciaRel(\Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargosCargoSupervigilanciaRel)
    {
        $this->cargosCargoSupervigilanciaRel[] = $cargosCargoSupervigilanciaRel;

        return $this;
    }

    /**
     * Remove cargosCargoSupervigilanciaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargosCargoSupervigilanciaRel
     */
    public function removeCargosCargoSupervigilanciaRel(\Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargosCargoSupervigilanciaRel)
    {
        $this->cargosCargoSupervigilanciaRel->removeElement($cargosCargoSupervigilanciaRel);
    }

    /**
     * Get cargosCargoSupervigilanciaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCargosCargoSupervigilanciaRel()
    {
        return $this->cargosCargoSupervigilanciaRel;
    }
}
