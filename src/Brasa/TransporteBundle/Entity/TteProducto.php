<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_producto")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteProductoRepository")
 */
class TteProducto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_producto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProductoPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;     

    /**
     * @ORM\OneToMany(targetEntity="TteListaPrecioDetalle", mappedBy="productoRel")
     */
    protected $listasPreciosDetallesRel;   
    
    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="productoRel")
     */
    protected $guiasRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listasPreciosDetallesRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->guiasRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoProductoPk
     *
     * @return integer
     */
    public function getCodigoProductoPk()
    {
        return $this->codigoProductoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TteProducto
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
     * Add listasPreciosDetallesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListaPrecioDetalle $listasPreciosDetallesRel
     *
     * @return TteProducto
     */
    public function addListasPreciosDetallesRel(\Brasa\TransporteBundle\Entity\TteListaPrecioDetalle $listasPreciosDetallesRel)
    {
        $this->listasPreciosDetallesRel[] = $listasPreciosDetallesRel;

        return $this;
    }

    /**
     * Remove listasPreciosDetallesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListaPrecioDetalle $listasPreciosDetallesRel
     */
    public function removeListasPreciosDetallesRel(\Brasa\TransporteBundle\Entity\TteListaPrecioDetalle $listasPreciosDetallesRel)
    {
        $this->listasPreciosDetallesRel->removeElement($listasPreciosDetallesRel);
    }

    /**
     * Get listasPreciosDetallesRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListasPreciosDetallesRel()
    {
        return $this->listasPreciosDetallesRel;
    }

    /**
     * Add guiasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasRel
     *
     * @return TteProducto
     */
    public function addGuiasRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasRel)
    {
        $this->guiasRel[] = $guiasRel;

        return $this;
    }

    /**
     * Remove guiasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasRel
     */
    public function removeGuiasRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasRel)
    {
        $this->guiasRel->removeElement($guiasRel);
    }

    /**
     * Get guiasRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGuiasRel()
    {
        return $this->guiasRel;
    }
}
