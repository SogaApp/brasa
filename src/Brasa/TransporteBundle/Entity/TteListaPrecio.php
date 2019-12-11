<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_lista_precio")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteListaPrecioRepository")
 */
class TteListaPrecio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_lista_precio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoListaPrecioPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;     
    
    /**
     * @ORM\Column(name="fecha_vencimiento", type="datetime", nullable=true)
     */    
    private $fechaVencimiento;    
    
    /**
     * @ORM\OneToMany(targetEntity="TteListaPrecioDetalle", mappedBy="listaPrecioRel")
     */
    protected $listasPreciosDetallesListaPrecioRel;
    
    /**
     * @ORM\OneToMany(targetEntity="TteCliente", mappedBy="listaPrecioRel")
     */
    protected $clientesListaPrecioRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listasPreciosDetallesListaPrecioRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->clientesListaPrecioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoListaPrecioPk
     *
     * @return integer
     */
    public function getCodigoListaPrecioPk()
    {
        return $this->codigoListaPrecioPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TteListaPrecio
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
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return TteListaPrecio
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Add listasPreciosDetallesListaPrecioRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListaPrecioDetalle $listasPreciosDetallesListaPrecioRel
     *
     * @return TteListaPrecio
     */
    public function addListasPreciosDetallesListaPrecioRel(\Brasa\TransporteBundle\Entity\TteListaPrecioDetalle $listasPreciosDetallesListaPrecioRel)
    {
        $this->listasPreciosDetallesListaPrecioRel[] = $listasPreciosDetallesListaPrecioRel;

        return $this;
    }

    /**
     * Remove listasPreciosDetallesListaPrecioRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListaPrecioDetalle $listasPreciosDetallesListaPrecioRel
     */
    public function removeListasPreciosDetallesListaPrecioRel(\Brasa\TransporteBundle\Entity\TteListaPrecioDetalle $listasPreciosDetallesListaPrecioRel)
    {
        $this->listasPreciosDetallesListaPrecioRel->removeElement($listasPreciosDetallesListaPrecioRel);
    }

    /**
     * Get listasPreciosDetallesListaPrecioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListasPreciosDetallesListaPrecioRel()
    {
        return $this->listasPreciosDetallesListaPrecioRel;
    }

    /**
     * Add clientesListaPrecioRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteCliente $clientesListaPrecioRel
     *
     * @return TteListaPrecio
     */
    public function addClientesListaPrecioRel(\Brasa\TransporteBundle\Entity\TteCliente $clientesListaPrecioRel)
    {
        $this->clientesListaPrecioRel[] = $clientesListaPrecioRel;

        return $this;
    }

    /**
     * Remove clientesListaPrecioRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteCliente $clientesListaPrecioRel
     */
    public function removeClientesListaPrecioRel(\Brasa\TransporteBundle\Entity\TteCliente $clientesListaPrecioRel)
    {
        $this->clientesListaPrecioRel->removeElement($clientesListaPrecioRel);
    }

    /**
     * Get clientesListaPrecioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClientesListaPrecioRel()
    {
        return $this->clientesListaPrecioRel;
    }
}
