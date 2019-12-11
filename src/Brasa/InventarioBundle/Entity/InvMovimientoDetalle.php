<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Table(name="inv_movimiento_detalle")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvMovimientoDetalleRepository")
 */
class InvMovimientoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_detalle_movimiento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoDetalleMovimientoPk;
    
    /**
     * @ORM\Column(name="codigo_movimiento_fk", type="integer", nullable=true)
     */     
    private $codigoMovimientoFk;    
    
    /**
     * @ORM\Column(name="codigo_item_fk", type="integer", nullable=true)
     */     
    private $codigoItemFk;    

    /**
     * @ORM\Column(name="codigo_bodega_fk", type="string", length=10, nullable=true)
     */     
    private $codigoBodegaFk;

    /**
     * @ORM\Column(name="codigo_bodega_destino_fk", type="integer", nullable=true)
     */     
    private $codigoBodegaDestinoFk;    
    
    /**
     * @ORM\Column(name="lote_fk", type="string", length=40, nullable=true)
     */      
    private $loteFk;
    
    /**
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */      
    private $fechaVencimiento;
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */        
    private $cantidad = 0;

    /**
     * @ORM\Column(name="cantidad_operada", type="integer")
     */        
    private $cantidadOperada = 0;    

    /**
     * @ORM\Column(name="cantidad_afectada", type="integer")
     */        
    private $cantidadAfectada = 0;      
    
    /**
     * @ORM\Column(name="vr_costo", type="float")
     */    
    private $vrCosto = 0;

    /**
     * @ORM\Column(name="vr_total_costo", type="float")
     */    
    private $vrTotalCosto = 0;    
    
    /**
     * @ORM\Column(name="vr_costo_promedio", type="float")
     */    
    private $vrCostoPromedio = 0;    
    
    /**
     * @ORM\Column(name="vr_precio", type="float")
     */    
    private $vrPrecio = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */    
    private $vrSubTotal = 0;

    /**
     * @ORM\Column(name="porcentaje_iva", type="integer")
     */    
    private $porcentajeIva = 0;
    
    /**
     * @ORM\Column(name="vr_iva", type="float")
     */    
    private $vrIva = 0;

    /**
     * @ORM\Column(name="porcentaje_descuento", type="float")
     */    
    private $porcentajeDescuento = 0;

    /**
     * @ORM\Column(name="vr_descuento", type="float")
     */    
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="vr_bruto", type="float")
     */    
    private $vrBruto = 0;    
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */    
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="operacion_inventario", type="bigint")
     */    
    private $operacionInventario = 0;

    /**
     * @ORM\Column(name="operacion_comercial", type="bigint")
     */    
    private $operacionComercial = 0;

    /**
     * @ORM\Column(name="afectar_remision", type="bigint")
     */    
    private $afectarRemision = 0;    
    
    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = 0;    

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = 0;    
    
    /**
     * @ORM\Column(name="codigo_detalle_movimiento_enlace", type="integer", nullable=true)     
     */        
    private $codigoDetalleMovimientoEnlace;         
    
    /**
     * @ORM\ManyToOne(targetEntity="InvMovimiento", inversedBy="movimientosDetallesMovimientoRel")
     * @ORM\JoinColumn(name="codigo_movimiento_fk", referencedColumnName="codigo_movimiento_pk")
     */
    protected $movimientoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="movimientosDetallesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;
    


    /**
     * Get codigoDetalleMovimientoPk
     *
     * @return integer
     */
    public function getCodigoDetalleMovimientoPk()
    {
        return $this->codigoDetalleMovimientoPk;
    }

    /**
     * Set codigoMovimientoFk
     *
     * @param integer $codigoMovimientoFk
     *
     * @return InvMovimientoDetalle
     */
    public function setCodigoMovimientoFk($codigoMovimientoFk)
    {
        $this->codigoMovimientoFk = $codigoMovimientoFk;

        return $this;
    }

    /**
     * Get codigoMovimientoFk
     *
     * @return integer
     */
    public function getCodigoMovimientoFk()
    {
        return $this->codigoMovimientoFk;
    }

    /**
     * Set codigoItemFk
     *
     * @param integer $codigoItemFk
     *
     * @return InvMovimientoDetalle
     */
    public function setCodigoItemFk($codigoItemFk)
    {
        $this->codigoItemFk = $codigoItemFk;

        return $this;
    }

    /**
     * Get codigoItemFk
     *
     * @return integer
     */
    public function getCodigoItemFk()
    {
        return $this->codigoItemFk;
    }

    /**
     * Set codigoBodegaFk
     *
     * @param string $codigoBodegaFk
     *
     * @return InvMovimientoDetalle
     */
    public function setCodigoBodegaFk($codigoBodegaFk)
    {
        $this->codigoBodegaFk = $codigoBodegaFk;

        return $this;
    }

    /**
     * Get codigoBodegaFk
     *
     * @return string
     */
    public function getCodigoBodegaFk()
    {
        return $this->codigoBodegaFk;
    }

    /**
     * Set codigoBodegaDestinoFk
     *
     * @param integer $codigoBodegaDestinoFk
     *
     * @return InvMovimientoDetalle
     */
    public function setCodigoBodegaDestinoFk($codigoBodegaDestinoFk)
    {
        $this->codigoBodegaDestinoFk = $codigoBodegaDestinoFk;

        return $this;
    }

    /**
     * Get codigoBodegaDestinoFk
     *
     * @return integer
     */
    public function getCodigoBodegaDestinoFk()
    {
        return $this->codigoBodegaDestinoFk;
    }

    /**
     * Set loteFk
     *
     * @param string $loteFk
     *
     * @return InvMovimientoDetalle
     */
    public function setLoteFk($loteFk)
    {
        $this->loteFk = $loteFk;

        return $this;
    }

    /**
     * Get loteFk
     *
     * @return string
     */
    public function getLoteFk()
    {
        return $this->loteFk;
    }

    /**
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return InvMovimientoDetalle
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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return InvMovimientoDetalle
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set cantidadOperada
     *
     * @param integer $cantidadOperada
     *
     * @return InvMovimientoDetalle
     */
    public function setCantidadOperada($cantidadOperada)
    {
        $this->cantidadOperada = $cantidadOperada;

        return $this;
    }

    /**
     * Get cantidadOperada
     *
     * @return integer
     */
    public function getCantidadOperada()
    {
        return $this->cantidadOperada;
    }

    /**
     * Set cantidadAfectada
     *
     * @param integer $cantidadAfectada
     *
     * @return InvMovimientoDetalle
     */
    public function setCantidadAfectada($cantidadAfectada)
    {
        $this->cantidadAfectada = $cantidadAfectada;

        return $this;
    }

    /**
     * Get cantidadAfectada
     *
     * @return integer
     */
    public function getCantidadAfectada()
    {
        return $this->cantidadAfectada;
    }

    /**
     * Set vrCosto
     *
     * @param float $vrCosto
     *
     * @return InvMovimientoDetalle
     */
    public function setVrCosto($vrCosto)
    {
        $this->vrCosto = $vrCosto;

        return $this;
    }

    /**
     * Get vrCosto
     *
     * @return float
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * Set vrTotalCosto
     *
     * @param float $vrTotalCosto
     *
     * @return InvMovimientoDetalle
     */
    public function setVrTotalCosto($vrTotalCosto)
    {
        $this->vrTotalCosto = $vrTotalCosto;

        return $this;
    }

    /**
     * Get vrTotalCosto
     *
     * @return float
     */
    public function getVrTotalCosto()
    {
        return $this->vrTotalCosto;
    }

    /**
     * Set vrCostoPromedio
     *
     * @param float $vrCostoPromedio
     *
     * @return InvMovimientoDetalle
     */
    public function setVrCostoPromedio($vrCostoPromedio)
    {
        $this->vrCostoPromedio = $vrCostoPromedio;

        return $this;
    }

    /**
     * Get vrCostoPromedio
     *
     * @return float
     */
    public function getVrCostoPromedio()
    {
        return $this->vrCostoPromedio;
    }

    /**
     * Set vrPrecio
     *
     * @param float $vrPrecio
     *
     * @return InvMovimientoDetalle
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
     * Set vrSubTotal
     *
     * @param float $vrSubTotal
     *
     * @return InvMovimientoDetalle
     */
    public function setVrSubTotal($vrSubTotal)
    {
        $this->vrSubTotal = $vrSubTotal;

        return $this;
    }

    /**
     * Get vrSubTotal
     *
     * @return float
     */
    public function getVrSubTotal()
    {
        return $this->vrSubTotal;
    }

    /**
     * Set porcentajeIva
     *
     * @param integer $porcentajeIva
     *
     * @return InvMovimientoDetalle
     */
    public function setPorcentajeIva($porcentajeIva)
    {
        $this->porcentajeIva = $porcentajeIva;

        return $this;
    }

    /**
     * Get porcentajeIva
     *
     * @return integer
     */
    public function getPorcentajeIva()
    {
        return $this->porcentajeIva;
    }

    /**
     * Set vrIva
     *
     * @param float $vrIva
     *
     * @return InvMovimientoDetalle
     */
    public function setVrIva($vrIva)
    {
        $this->vrIva = $vrIva;

        return $this;
    }

    /**
     * Get vrIva
     *
     * @return float
     */
    public function getVrIva()
    {
        return $this->vrIva;
    }

    /**
     * Set porcentajeDescuento
     *
     * @param float $porcentajeDescuento
     *
     * @return InvMovimientoDetalle
     */
    public function setPorcentajeDescuento($porcentajeDescuento)
    {
        $this->porcentajeDescuento = $porcentajeDescuento;

        return $this;
    }

    /**
     * Get porcentajeDescuento
     *
     * @return float
     */
    public function getPorcentajeDescuento()
    {
        return $this->porcentajeDescuento;
    }

    /**
     * Set vrDescuento
     *
     * @param float $vrDescuento
     *
     * @return InvMovimientoDetalle
     */
    public function setVrDescuento($vrDescuento)
    {
        $this->vrDescuento = $vrDescuento;

        return $this;
    }

    /**
     * Get vrDescuento
     *
     * @return float
     */
    public function getVrDescuento()
    {
        return $this->vrDescuento;
    }

    /**
     * Set vrBruto
     *
     * @param float $vrBruto
     *
     * @return InvMovimientoDetalle
     */
    public function setVrBruto($vrBruto)
    {
        $this->vrBruto = $vrBruto;

        return $this;
    }

    /**
     * Get vrBruto
     *
     * @return float
     */
    public function getVrBruto()
    {
        return $this->vrBruto;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return InvMovimientoDetalle
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * Set operacionInventario
     *
     * @param integer $operacionInventario
     *
     * @return InvMovimientoDetalle
     */
    public function setOperacionInventario($operacionInventario)
    {
        $this->operacionInventario = $operacionInventario;

        return $this;
    }

    /**
     * Get operacionInventario
     *
     * @return integer
     */
    public function getOperacionInventario()
    {
        return $this->operacionInventario;
    }

    /**
     * Set operacionComercial
     *
     * @param integer $operacionComercial
     *
     * @return InvMovimientoDetalle
     */
    public function setOperacionComercial($operacionComercial)
    {
        $this->operacionComercial = $operacionComercial;

        return $this;
    }

    /**
     * Get operacionComercial
     *
     * @return integer
     */
    public function getOperacionComercial()
    {
        return $this->operacionComercial;
    }

    /**
     * Set afectarRemision
     *
     * @param integer $afectarRemision
     *
     * @return InvMovimientoDetalle
     */
    public function setAfectarRemision($afectarRemision)
    {
        $this->afectarRemision = $afectarRemision;

        return $this;
    }

    /**
     * Get afectarRemision
     *
     * @return integer
     */
    public function getAfectarRemision()
    {
        return $this->afectarRemision;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return InvMovimientoDetalle
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     *
     * @return InvMovimientoDetalle
     */
    public function setEstadoAnulado($estadoAnulado)
    {
        $this->estadoAnulado = $estadoAnulado;

        return $this;
    }

    /**
     * Get estadoAnulado
     *
     * @return boolean
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return InvMovimientoDetalle
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Set codigoDetalleMovimientoEnlace
     *
     * @param integer $codigoDetalleMovimientoEnlace
     *
     * @return InvMovimientoDetalle
     */
    public function setCodigoDetalleMovimientoEnlace($codigoDetalleMovimientoEnlace)
    {
        $this->codigoDetalleMovimientoEnlace = $codigoDetalleMovimientoEnlace;

        return $this;
    }

    /**
     * Get codigoDetalleMovimientoEnlace
     *
     * @return integer
     */
    public function getCodigoDetalleMovimientoEnlace()
    {
        return $this->codigoDetalleMovimientoEnlace;
    }

    /**
     * Set movimientoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimiento $movimientoRel
     *
     * @return InvMovimientoDetalle
     */
    public function setMovimientoRel(\Brasa\InventarioBundle\Entity\InvMovimiento $movimientoRel = null)
    {
        $this->movimientoRel = $movimientoRel;

        return $this;
    }

    /**
     * Get movimientoRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvMovimiento
     */
    public function getMovimientoRel()
    {
        return $this->movimientoRel;
    }

    /**
     * Set itemRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemRel
     *
     * @return InvMovimientoDetalle
     */
    public function setItemRel(\Brasa\InventarioBundle\Entity\InvItem $itemRel = null)
    {
        $this->itemRel = $itemRel;

        return $this;
    }

    /**
     * Get itemRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvItem
     */
    public function getItemRel()
    {
        return $this->itemRel;
    }
}
