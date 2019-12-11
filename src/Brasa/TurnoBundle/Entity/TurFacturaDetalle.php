<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_factura_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurFacturaDetalleRepository")
 */
class TurFacturaDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaDetallePk;  
    
    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer")
     */    
    private $codigoFacturaFk;     

    /**
     * @ORM\Column(name="codigo_concepto_servicio_fk", type="integer")
     */    
    private $codigoConceptoServicioFk;     
    
    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;    
    
    /**
     * @ORM\Column(name="codigo_modalidad_servicio_fk", type="integer", nullable=true)
     */    
    private $codigoModalidadServicioFk;    
    
    /**
     * @ORM\Column(name="codigo_grupo_facturacion_fk", type="integer", nullable=true)
     */    
    private $codigoGrupoFacturacionFk;    
    
    /**
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoPedidoDetalleFk;                          
    
    /**
     * @ORM\Column(name="codigo_pedido_detalle_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPedidoDetalleConceptoFk;    
    
    /**
     * @ORM\Column(name="codigo_factura_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoFacturaDetalleFk;    
    
    /**
     * @ORM\Column(name="fecha_programacion", type="date", nullable=true)
     */    
    private $fechaProgramacion;       
    
    /**
     * @ORM\Column(name="por_iva", type="integer")
     */
    private $porIva = 0;     

    /**
     * @ORM\Column(name="por_base_iva", type="integer")
     */
    private $porBaseIva = 0;    
    
    /**
     * @ORM\Column(name="base_iva", type="integer")
     */
    private $baseIva = 0;    
    
    /**
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0;    
    
    /**
     * @ORM\Column(name="vr_precio", type="float")
     */
    private $vrPrecio = 0;   
    
    /**
     * @ORM\Column(name="subtotal", type="float")
     */
    private $subtotal = 0; 

    /**
     * @ORM\Column(name="subtotal_operado", type="float")
     */
    private $subtotalOperado = 0;    
    
    /**
     * @ORM\Column(name="iva", type="float")
     */
    private $iva = 0;    
    
    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0;     
    
    /**
     * @ORM\Column(name="detalle", type="string", length=300, nullable=true)
     */
    private $detalle;    
    
     /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;
    
    /**
     * @ORM\Column(name="tipo_pedido", type="string", length=50, nullable=true)
     */    
    private $tipo_pedido;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurFactura", inversedBy="facturasDetallesFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;          

    /**
     * @ORM\ManyToOne(targetEntity="TurConceptoServicio", inversedBy="facturasDetallesConceptoServicioRel")
     * @ORM\JoinColumn(name="codigo_concepto_servicio_fk", referencedColumnName="codigo_concepto_servicio_pk")
     */
    protected $conceptoServicioRel; 

    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoDetalle", inversedBy="facturasDetallesPedidoDetalleRel")
     * @ORM\JoinColumn(name="codigo_pedido_detalle_fk", referencedColumnName="codigo_pedido_detalle_pk")
     */
    protected $pedidoDetalleRel;    

    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoDetalleConcepto", inversedBy="facturasDetallesPedidoDetalleConceptoRel")
     * @ORM\JoinColumn(name="codigo_pedido_detalle_concepto_fk", referencedColumnName="codigo_pedido_detalle_concepto_pk")
     */
    protected $pedidoDetalleConceptoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="TurFacturaDetalle", inversedBy="facturasDetallesFacturaDetalleRel")
     * @ORM\JoinColumn(name="codigo_factura_detalle_fk", referencedColumnName="codigo_factura_detalle_pk")
     */
    protected $facturaDetalleRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="facturasDetallesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;         

    /**
     * @ORM\ManyToOne(targetEntity="TurModalidadServicio", inversedBy="facturasDetallesModalidadServicioRel")
     * @ORM\JoinColumn(name="codigo_modalidad_servicio_fk", referencedColumnName="codigo_modalidad_servicio_pk")
     */
    protected $modalidadServicioRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurGrupoFacturacion", inversedBy="facturasDetallesGrupoFacturacionRel")
     * @ORM\JoinColumn(name="codigo_grupo_facturacion_fk", referencedColumnName="codigo_grupo_facturacion_pk")
     */
    protected $grupoFacturacionRel; 

    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalle", mappedBy="facturaDetalleRel")
     */
    protected $facturasDetallesFacturaDetalleRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasDetallesFacturaDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaDetallePk
     *
     * @return integer
     */
    public function getCodigoFacturaDetallePk()
    {
        return $this->codigoFacturaDetallePk;
    }

    /**
     * Set codigoFacturaFk
     *
     * @param integer $codigoFacturaFk
     *
     * @return TurFacturaDetalle
     */
    public function setCodigoFacturaFk($codigoFacturaFk)
    {
        $this->codigoFacturaFk = $codigoFacturaFk;

        return $this;
    }

    /**
     * Get codigoFacturaFk
     *
     * @return integer
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * Set codigoConceptoServicioFk
     *
     * @param integer $codigoConceptoServicioFk
     *
     * @return TurFacturaDetalle
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
     * Set codigoPuestoFk
     *
     * @param integer $codigoPuestoFk
     *
     * @return TurFacturaDetalle
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
     * Set codigoModalidadServicioFk
     *
     * @param integer $codigoModalidadServicioFk
     *
     * @return TurFacturaDetalle
     */
    public function setCodigoModalidadServicioFk($codigoModalidadServicioFk)
    {
        $this->codigoModalidadServicioFk = $codigoModalidadServicioFk;

        return $this;
    }

    /**
     * Get codigoModalidadServicioFk
     *
     * @return integer
     */
    public function getCodigoModalidadServicioFk()
    {
        return $this->codigoModalidadServicioFk;
    }

    /**
     * Set codigoGrupoFacturacionFk
     *
     * @param integer $codigoGrupoFacturacionFk
     *
     * @return TurFacturaDetalle
     */
    public function setCodigoGrupoFacturacionFk($codigoGrupoFacturacionFk)
    {
        $this->codigoGrupoFacturacionFk = $codigoGrupoFacturacionFk;

        return $this;
    }

    /**
     * Get codigoGrupoFacturacionFk
     *
     * @return integer
     */
    public function getCodigoGrupoFacturacionFk()
    {
        return $this->codigoGrupoFacturacionFk;
    }

    /**
     * Set codigoPedidoDetalleFk
     *
     * @param integer $codigoPedidoDetalleFk
     *
     * @return TurFacturaDetalle
     */
    public function setCodigoPedidoDetalleFk($codigoPedidoDetalleFk)
    {
        $this->codigoPedidoDetalleFk = $codigoPedidoDetalleFk;

        return $this;
    }

    /**
     * Get codigoPedidoDetalleFk
     *
     * @return integer
     */
    public function getCodigoPedidoDetalleFk()
    {
        return $this->codigoPedidoDetalleFk;
    }

    /**
     * Set codigoPedidoDetalleConceptoFk
     *
     * @param integer $codigoPedidoDetalleConceptoFk
     *
     * @return TurFacturaDetalle
     */
    public function setCodigoPedidoDetalleConceptoFk($codigoPedidoDetalleConceptoFk)
    {
        $this->codigoPedidoDetalleConceptoFk = $codigoPedidoDetalleConceptoFk;

        return $this;
    }

    /**
     * Get codigoPedidoDetalleConceptoFk
     *
     * @return integer
     */
    public function getCodigoPedidoDetalleConceptoFk()
    {
        return $this->codigoPedidoDetalleConceptoFk;
    }

    /**
     * Set codigoFacturaDetalleFk
     *
     * @param integer $codigoFacturaDetalleFk
     *
     * @return TurFacturaDetalle
     */
    public function setCodigoFacturaDetalleFk($codigoFacturaDetalleFk)
    {
        $this->codigoFacturaDetalleFk = $codigoFacturaDetalleFk;

        return $this;
    }

    /**
     * Get codigoFacturaDetalleFk
     *
     * @return integer
     */
    public function getCodigoFacturaDetalleFk()
    {
        return $this->codigoFacturaDetalleFk;
    }

    /**
     * Set fechaProgramacion
     *
     * @param \DateTime $fechaProgramacion
     *
     * @return TurFacturaDetalle
     */
    public function setFechaProgramacion($fechaProgramacion)
    {
        $this->fechaProgramacion = $fechaProgramacion;

        return $this;
    }

    /**
     * Get fechaProgramacion
     *
     * @return \DateTime
     */
    public function getFechaProgramacion()
    {
        return $this->fechaProgramacion;
    }

    /**
     * Set porIva
     *
     * @param integer $porIva
     *
     * @return TurFacturaDetalle
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
     * @return TurFacturaDetalle
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
     * Set baseIva
     *
     * @param integer $baseIva
     *
     * @return TurFacturaDetalle
     */
    public function setBaseIva($baseIva)
    {
        $this->baseIva = $baseIva;

        return $this;
    }

    /**
     * Get baseIva
     *
     * @return integer
     */
    public function getBaseIva()
    {
        return $this->baseIva;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     *
     * @return TurFacturaDetalle
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
     * Set vrPrecio
     *
     * @param float $vrPrecio
     *
     * @return TurFacturaDetalle
     */
    public function setVrPrecio($vrPrecio)
    {
        $this->vrPrecio = $vrPrecio;

        return $this;
    }

    /**
     * Get vrPrecio
     *
     * @return float
     */
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * Set subtotal
     *
     * @param float $subtotal
     *
     * @return TurFacturaDetalle
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
     * Set iva
     *
     * @param float $iva
     *
     * @return TurFacturaDetalle
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
     * Set total
     *
     * @param float $total
     *
     * @return TurFacturaDetalle
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
     * Set detalle
     *
     * @param string $detalle
     *
     * @return TurFacturaDetalle
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set facturaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturaRel
     *
     * @return TurFacturaDetalle
     */
    public function setFacturaRel(\Brasa\TurnoBundle\Entity\TurFactura $facturaRel = null)
    {
        $this->facturaRel = $facturaRel;

        return $this;
    }

    /**
     * Get facturaRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurFactura
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * Set conceptoServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurConceptoServicio $conceptoServicioRel
     *
     * @return TurFacturaDetalle
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
     * Set pedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidoDetalleRel
     *
     * @return TurFacturaDetalle
     */
    public function setPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidoDetalleRel = null)
    {
        $this->pedidoDetalleRel = $pedidoDetalleRel;

        return $this;
    }

    /**
     * Get pedidoDetalleRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPedidoDetalle
     */
    public function getPedidoDetalleRel()
    {
        return $this->pedidoDetalleRel;
    }

    /**
     * Set pedidoDetalleConceptoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidoDetalleConceptoRel
     *
     * @return TurFacturaDetalle
     */
    public function setPedidoDetalleConceptoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidoDetalleConceptoRel = null)
    {
        $this->pedidoDetalleConceptoRel = $pedidoDetalleConceptoRel;

        return $this;
    }

    /**
     * Get pedidoDetalleConceptoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto
     */
    public function getPedidoDetalleConceptoRel()
    {
        return $this->pedidoDetalleConceptoRel;
    }

    /**
     * Set facturaDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturaDetalleRel
     *
     * @return TurFacturaDetalle
     */
    public function setFacturaDetalleRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturaDetalleRel = null)
    {
        $this->facturaDetalleRel = $facturaDetalleRel;

        return $this;
    }

    /**
     * Get facturaDetalleRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurFacturaDetalle
     */
    public function getFacturaDetalleRel()
    {
        return $this->facturaDetalleRel;
    }

    /**
     * Set puestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestoRel
     *
     * @return TurFacturaDetalle
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
     * Set modalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurModalidadServicio $modalidadServicioRel
     *
     * @return TurFacturaDetalle
     */
    public function setModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurModalidadServicio $modalidadServicioRel = null)
    {
        $this->modalidadServicioRel = $modalidadServicioRel;

        return $this;
    }

    /**
     * Get modalidadServicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurModalidadServicio
     */
    public function getModalidadServicioRel()
    {
        return $this->modalidadServicioRel;
    }

    /**
     * Set grupoFacturacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurGrupoFacturacion $grupoFacturacionRel
     *
     * @return TurFacturaDetalle
     */
    public function setGrupoFacturacionRel(\Brasa\TurnoBundle\Entity\TurGrupoFacturacion $grupoFacturacionRel = null)
    {
        $this->grupoFacturacionRel = $grupoFacturacionRel;

        return $this;
    }

    /**
     * Get grupoFacturacionRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurGrupoFacturacion
     */
    public function getGrupoFacturacionRel()
    {
        return $this->grupoFacturacionRel;
    }

    /**
     * Add facturasDetallesFacturaDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesFacturaDetalleRel
     *
     * @return TurFacturaDetalle
     */
    public function addFacturasDetallesFacturaDetalleRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesFacturaDetalleRel)
    {
        $this->facturasDetallesFacturaDetalleRel[] = $facturasDetallesFacturaDetalleRel;

        return $this;
    }

    /**
     * Remove facturasDetallesFacturaDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesFacturaDetalleRel
     */
    public function removeFacturasDetallesFacturaDetalleRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesFacturaDetalleRel)
    {
        $this->facturasDetallesFacturaDetalleRel->removeElement($facturasDetallesFacturaDetalleRel);
    }

    /**
     * Get facturasDetallesFacturaDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesFacturaDetalleRel()
    {
        return $this->facturasDetallesFacturaDetalleRel;
    }

    /**
     * Set tipoPedido
     *
     * @param string $tipoPedido
     *
     * @return TurFacturaDetalle
     */
    public function setTipoPedido($tipoPedido)
    {
        $this->tipo_pedido = $tipoPedido;

        return $this;
    }

    /**
     * Get tipoPedido
     *
     * @return string
     */
    public function getTipoPedido()
    {
        return $this->tipo_pedido;
    }

    /**
     * Set subtotalOperado
     *
     * @param float $subtotalOperado
     *
     * @return TurFacturaDetalle
     */
    public function setSubtotalOperado($subtotalOperado)
    {
        $this->subtotalOperado = $subtotalOperado;

        return $this;
    }

    /**
     * Get subtotalOperado
     *
     * @return float
     */
    public function getSubtotalOperado()
    {
        return $this->subtotalOperado;
    }

    /**
     * Set operacion
     *
     * @param integer $operacion
     *
     * @return TurFacturaDetalle
     */
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get operacion
     *
     * @return integer
     */
    public function getOperacion()
    {
        return $this->operacion;
    }
}
