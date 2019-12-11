<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_factura_subtipo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurFacturaSubtipoRepository")
 */
class TurFacturaSubtipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_subtipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaSubtipoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                                
    
    /**     
     * @ORM\Column(name="afecta_valor_pedido", type="boolean")
     */    
    private $afectaValorPedido = false;        
    
    /**
     * @ORM\OneToMany(targetEntity="TurFactura", mappedBy="facturaSubtipoRel")
     */
    protected $facturasFacturaSubtipoRel; 
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasFacturaSubtipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaSubtipoPk
     *
     * @return integer
     */
    public function getCodigoFacturaSubtipoPk()
    {
        return $this->codigoFacturaSubtipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurFacturaSubtipo
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
     * Set afectaValorPedido
     *
     * @param boolean $afectaValorPedido
     *
     * @return TurFacturaSubtipo
     */
    public function setAfectaValorPedido($afectaValorPedido)
    {
        $this->afectaValorPedido = $afectaValorPedido;

        return $this;
    }

    /**
     * Get afectaValorPedido
     *
     * @return boolean
     */
    public function getAfectaValorPedido()
    {
        return $this->afectaValorPedido;
    }

    /**
     * Add facturasFacturaSubtipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaSubtipoRel
     *
     * @return TurFacturaSubtipo
     */
    public function addFacturasFacturaSubtipoRel(\Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaSubtipoRel)
    {
        $this->facturasFacturaSubtipoRel[] = $facturasFacturaSubtipoRel;

        return $this;
    }

    /**
     * Remove facturasFacturaSubtipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaSubtipoRel
     */
    public function removeFacturasFacturaSubtipoRel(\Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaSubtipoRel)
    {
        $this->facturasFacturaSubtipoRel->removeElement($facturasFacturaSubtipoRel);
    }

    /**
     * Get facturasFacturaSubtipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasFacturaSubtipoRel()
    {
        return $this->facturasFacturaSubtipoRel;
    }
}
