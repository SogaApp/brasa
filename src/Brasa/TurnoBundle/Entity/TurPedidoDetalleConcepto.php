<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_pedido_detalle_concepto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPedidoDetalleConceptoRepository")
 */
class TurPedidoDetalleConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_detalle_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPedidoDetalleConceptoPk;  
    
    /**
     * @ORM\Column(name="codigo_pedido_fk", type="integer")
     */    
    private $codigoPedidoFk;                    
    
    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;    
    
    /**
     * @ORM\Column(name="codigo_concepto_servicio_fk", type="integer", nullable=true)
     */    
    private $codigoConceptoServicioFk;     
    
    /**
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0;    
    
    /**
     * @ORM\Column(name="precio", type="float")
     */
    private $precio = 0;     
    
    /**
     * @ORM\Column(name="por_iva", type="integer")
     */
    private $porIva = 0;    

    /**
     * @ORM\Column(name="por_base_iva", type="integer")
     */
    private $porBaseIva = 0;     
    
    /**
     * @ORM\Column(name="iva", type="float")
     */
    private $iva = 0;         
    
    /**
     * @ORM\Column(name="subtotal", type="float")
     */
    private $subtotal = 0;    

    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0;    
    
    /**     
     * @ORM\Column(name="estado_facturado", type="boolean")
     */    
    private $estadoFacturado = false;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPedido", inversedBy="pedidosDetallesConceptosPedidoRel")
     * @ORM\JoinColumn(name="codigo_pedido_fk", referencedColumnName="codigo_pedido_pk")
     */
    protected $pedidoRel;          
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="pedidosDetallesConceptosPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurConceptoServicio", inversedBy="pedidosDetallesConceptosConceptoServicioRel")
     * @ORM\JoinColumn(name="codigo_concepto_servicio_fk", referencedColumnName="codigo_concepto_servicio_pk")
     */
    protected $conceptoServicioRel;         

    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalle", mappedBy="pedidoDetalleConceptoRel")
     */
    protected $facturasDetallesPedidoDetalleConceptoRel; 
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasDetallesPedidoDetalleConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPedidoDetalleConceptoPk
     *
     * @return integer
     */
    public function getCodigoPedidoDetalleConceptoPk()
    {
        return $this->codigoPedidoDetalleConceptoPk;
    }

    /**
     * Set codigoPedidoFk
     *
     * @param integer $codigoPedidoFk
     *
     * @return TurPedidoDetalleConcepto
     */
    public function setCodigoPedidoFk($codigoPedidoFk)
    {
        $this->codigoPedidoFk = $codigoPedidoFk;

        return $this;
    }

    /**
     * Get codigoPedidoFk
     *
     * @return integer
     */
    public function getCodigoPedidoFk()
    {
        return $this->codigoPedidoFk;
    }

    /**
     * Set codigoPuestoFk
     *
     * @param integer $codigoPuestoFk
     *
     * @return TurPedidoDetalleConcepto
     */
    public function setCodigoPuestoFk($codigoPuestoFk)
    {
        $this->codigoPuestoFk = $codigoPuestoFk;

        return $this;
    }

    /**
     * Get codigoPuestoFk
     *
     * @return integer
     */
    public function getCodigoPuestoFk()
    {
        return $this->codigoPuestoFk;
    }

    /**
     * Set codigoConceptoServicioFk
     *
     * @param integer $codigoConceptoServicioFk
     *
     * @return TurPedidoDetalleConcepto
     */
    public function setCodigoConceptoServicioFk($codigoConceptoServicioFk)
    {
        $this->codigoConceptoServicioFk = $codigoConceptoServicioFk;

        return $this;
    }

    /**
     * Get codigoConceptoServicioFk
     *
     * @return integer
     */
    public function getCodigoConceptoServicioFk()
    {
        return $this->codigoConceptoServicioFk;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     *
     * @return TurPedidoDetalleConcepto
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return TurPedidoDetalleConcepto
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set porIva
     *
     * @param integer $porIva
     *
     * @return TurPedidoDetalleConcepto
     */
    public function setPorIva($porIva)
    {
        $this->porIva = $porIva;

        return $this;
    }

    /**
     * Get porIva
     *
     * @return integer
     */
    public function getPorIva()
    {
        return $this->porIva;
    }

    /**
     * Set porBaseIva
     *
     * @param integer $porBaseIva
     *
     * @return TurPedidoDetalleConcepto
     */
    public function setPorBaseIva($porBaseIva)
    {
        $this->porBaseIva = $porBaseIva;

        return $this;
    }

    /**
     * Get porBaseIva
     *
     * @return integer
     */
    public function getPorBaseIva()
    {
        return $this->porBaseIva;
    }

    /**
     * Set iva
     *
     * @param float $iva
     *
     * @return TurPedidoDetalleConcepto
     */
    public function setIva($iva)
    {
        $this->iva = $iva;

        return $this;
    }

    /**
     * Get iva
     *
     * @return float
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set subtotal
     *
     * @param float $subtotal
     *
     * @return TurPedidoDetalleConcepto
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get subtotal
     *
     * @return float
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return TurPedidoDetalleConcepto
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set estadoFacturado
     *
     * @param boolean $estadoFacturado
     *
     * @return TurPedidoDetalleConcepto
     */
    public function setEstadoFacturado($estadoFacturado)
    {
        $this->estadoFacturado = $estadoFacturado;

        return $this;
    }

    /**
     * Get estadoFacturado
     *
     * @return boolean
     */
    public function getEstadoFacturado()
    {
        return $this->estadoFacturado;
    }

    /**
     * Set pedidoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedido $pedidoRel
     *
     * @return TurPedidoDetalleConcepto
     */
    public function setPedidoRel(\Brasa\TurnoBundle\Entity\TurPedido $pedidoRel = null)
    {
        $this->pedidoRel = $pedidoRel;

        return $this;
    }

    /**
     * Get pedidoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPedido
     */
    public function getPedidoRel()
    {
        return $this->pedidoRel;
    }

    /**
     * Set puestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestoRel
     *
     * @return TurPedidoDetalleConcepto
     */
    public function setPuestoRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestoRel = null)
    {
        $this->puestoRel = $puestoRel;

        return $this;
    }

    /**
     * Get puestoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPuesto
     */
    public function getPuestoRel()
    {
        return $this->puestoRel;
    }

    /**
     * Set conceptoServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurConceptoServicio $conceptoServicioRel
     *
     * @return TurPedidoDetalleConcepto
     */
    public function setConceptoServicioRel(\Brasa\TurnoBundle\Entity\TurConceptoServicio $conceptoServicioRel = null)
    {
        $this->conceptoServicioRel = $conceptoServicioRel;

        return $this;
    }

    /**
     * Get conceptoServicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurConceptoServicio
     */
    public function getConceptoServicioRel()
    {
        return $this->conceptoServicioRel;
    }

    /**
     * Add facturasDetallesPedidoDetalleConceptoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPedidoDetalleConceptoRel
     *
     * @return TurPedidoDetalleConcepto
     */
    public function addFacturasDetallesPedidoDetalleConceptoRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPedidoDetalleConceptoRel)
    {
        $this->facturasDetallesPedidoDetalleConceptoRel[] = $facturasDetallesPedidoDetalleConceptoRel;

        return $this;
    }

    /**
     * Remove facturasDetallesPedidoDetalleConceptoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPedidoDetalleConceptoRel
     */
    public function removeFacturasDetallesPedidoDetalleConceptoRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPedidoDetalleConceptoRel)
    {
        $this->facturasDetallesPedidoDetalleConceptoRel->removeElement($facturasDetallesPedidoDetalleConceptoRel);
    }

    /**
     * Get facturasDetallesPedidoDetalleConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesPedidoDetalleConceptoRel()
    {
        return $this->facturasDetallesPedidoDetalleConceptoRel;
    }
}
