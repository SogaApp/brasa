<?php

namespace Brasa\InventarioBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="inv_marca")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvMarcaRepository")
 */
class InvMarca
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_marca_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoMarcaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100)
     * @Assert\NotNull()(message="Debe escribir un nombre de marca")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="InvItem", mappedBy="marcaRel")
     */
    protected $itemesMarcaRel; 

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->itemesMarcaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoMarcaPk
     *
     * @return integer
     */
    public function getCodigoMarcaPk()
    {
        return $this->codigoMarcaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return InvMarca
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
     * Add itemesMarcaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemesMarcaRel
     *
     * @return InvMarca
     */
    public function addItemesMarcaRel(\Brasa\InventarioBundle\Entity\InvItem $itemesMarcaRel)
    {
        $this->itemesMarcaRel[] = $itemesMarcaRel;

        return $this;
    }

    /**
     * Remove itemesMarcaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemesMarcaRel
     */
    public function removeItemesMarcaRel(\Brasa\InventarioBundle\Entity\InvItem $itemesMarcaRel)
    {
        $this->itemesMarcaRel->removeElement($itemesMarcaRel);
    }

    /**
     * Get itemesMarcaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemesMarcaRel()
    {
        return $this->itemesMarcaRel;
    }
}
