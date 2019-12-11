<?php

namespace Brasa\InventarioBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_bodega")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvBodegaRepository")
 */
class InvBodega
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_bodega_pk", type="string", length=10)     
     */
    private $codigoBodegaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="InvLote", mappedBy="bodegaRel")
     */
    protected $lotesBodegaRel;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lotesBodegaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoBodegaPk
     *
     * @param string $codigoBodegaPk
     *
     * @return InvBodega
     */
    public function setCodigoBodegaPk($codigoBodegaPk)
    {
        $this->codigoBodegaPk = $codigoBodegaPk;

        return $this;
    }

    /**
     * Get codigoBodegaPk
     *
     * @return string
     */
    public function getCodigoBodegaPk()
    {
        return $this->codigoBodegaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return InvBodega
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
     * Add lotesBodegaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvLote $lotesBodegaRel
     *
     * @return InvBodega
     */
    public function addLotesBodegaRel(\Brasa\InventarioBundle\Entity\InvLote $lotesBodegaRel)
    {
        $this->lotesBodegaRel[] = $lotesBodegaRel;

        return $this;
    }

    /**
     * Remove lotesBodegaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvLote $lotesBodegaRel
     */
    public function removeLotesBodegaRel(\Brasa\InventarioBundle\Entity\InvLote $lotesBodegaRel)
    {
        $this->lotesBodegaRel->removeElement($lotesBodegaRel);
    }

    /**
     * Get lotesBodegaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLotesBodegaRel()
    {
        return $this->lotesBodegaRel;
    }
}
