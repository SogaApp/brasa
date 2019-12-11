<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_item")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvItemRepository")
 */
class InvItem
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_item_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoItemPk;    
    
    /**
     * @ORM\Column(name="codigo_marca_fk", type="integer", nullable=true)
     */    
    private $codigoMarcaFk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="vr_costo_predeterminado", type="float", nullable=true)
     */
    private $vrCostoPredeterminado = 0;

    /**
     * @ORM\Column(name="vr_costo_promedio", type="float")
     */
    private $vrCostoPromedio = 0;    
    
    /**
     * @ORM\Column(name="vr_precio_predeterminado", type="float", nullable=true)
     */
    private $vrPrecioPredeterminado = 0;

    /**
     * @ORM\Column(name="codigo_ean", type="string", length=80, nullable=true)
     */    
    private $codigoEAN;    
    
    /**
     * @ORM\Column(name="codigo_barras", type="string", length=80, nullable=true)
     */    
    private $codigoBarras;                 

    /**
     * @ORM\Column(name="porcentaje_iva", type="integer")
     */    
    private $porcentajeIva = 0;    

    /**
     * @ORM\Column(name="cantidad_existencia", type="integer")
     */    
    private $cantidadExistencia = 0;        
    
    /**
     * @ORM\Column(name="cantidad_remisionada", type="integer")
     */    
    private $cantidadRemisionada = 0;        

    /**
     * @ORM\Column(name="cantidad_reservada", type="integer")
     */    
    private $cantidadReservada = 0;    
    
    /**
     * @ORM\Column(name="cantidad_disponible", type="integer")
     */    
    private $cantidadDisponible = 0;        

    /**
     * @ORM\Column(name="cantidad_orden_compra", type="integer")
     */    
    private $cantidadOrdenCompra = 0;    
    
    /**
     * @ORM\Column(name="permitir_inventario_negativo", type="boolean")
     */    
    private $permitirInventarioNegativo = 0; 
       
    /**
     * @ORM\Column(name="codigo_unidad_medida_fk", type="string", length=25, nullable=true)
     */    
    private $codigoUnidadMedidaFk;           
    
    /**
     * @ORM\Column(name="servicio", type="boolean")
     */    
    private $servicio = 0;              

    /**
     * @ORM\Column(name="materia_prima", type="boolean")
     */    
    private $materiaPrima = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="InvMarca", inversedBy="itemesMarcaRel")
     * @ORM\JoinColumn(name="codigo_marca_fk", referencedColumnName="codigo_marca_pk")
     */
    protected $marcaRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="InvMovimientoDetalle", mappedBy="itemRel")
     */
    protected $movimientosDetallesItemRel;    
       
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientosDetallesItemRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoItemPk
     *
     * @return integer
     */
    public function getCodigoItemPk()
    {
        return $this->codigoItemPk;
    }

    /**
     * Set codigoMarcaFk
     *
     * @param integer $codigoMarcaFk
     *
     * @return InvItem
     */
    public function setCodigoMarcaFk($codigoMarcaFk)
    {
        $this->codigoMarcaFk = $codigoMarcaFk;

        return $this;
    }

    /**
     * Get codigoMarcaFk
     *
     * @return integer
     */
    public function getCodigoMarcaFk()
    {
        return $this->codigoMarcaFk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return InvItem
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
     * Set vrCostoPredeterminado
     *
     * @param float $vrCostoPredeterminado
     *
     * @return InvItem
     */
    public function setVrCostoPredeterminado($vrCostoPredeterminado)
    {
        $this->vrCostoPredeterminado = $vrCostoPredeterminado;

        return $this;
    }

    /**
     * Get vrCostoPredeterminado
     *
     * @return float
     */
    public function getVrCostoPredeterminado()
    {
        return $this->vrCostoPredeterminado;
    }

    /**
     * Set vrCostoPromedio
     *
     * @param float $vrCostoPromedio
     *
     * @return InvItem
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
     * Set vrPrecioPredeterminado
     *
     * @param float $vrPrecioPredeterminado
     *
     * @return InvItem
     */
    public function setVrPrecioPredeterminado($vrPrecioPredeterminado)
    {
        $this->vrPrecioPredeterminado = $vrPrecioPredeterminado;

        return $this;
    }

    /**
     * Get vrPrecioPredeterminado
     *
     * @return float
     */
    public function getVrPrecioPredeterminado()
    {
        return $this->vrPrecioPredeterminado;
    }

    /**
     * Set codigoEAN
     *
     * @param string $codigoEAN
     *
     * @return InvItem
     */
    public function setCodigoEAN($codigoEAN)
    {
        $this->codigoEAN = $codigoEAN;

        return $this;
    }

    /**
     * Get codigoEAN
     *
     * @return string
     */
    public function getCodigoEAN()
    {
        return $this->codigoEAN;
    }

    /**
     * Set codigoBarras
     *
     * @param string $codigoBarras
     *
     * @return InvItem
     */
    public function setCodigoBarras($codigoBarras)
    {
        $this->codigoBarras = $codigoBarras;

        return $this;
    }

    /**
     * Get codigoBarras
     *
     * @return string
     */
    public function getCodigoBarras()
    {
        return $this->codigoBarras;
    }

    /**
     * Set porcentajeIva
     *
     * @param integer $porcentajeIva
     *
     * @return InvItem
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
     * Set cantidadExistencia
     *
     * @param integer $cantidadExistencia
     *
     * @return InvItem
     */
    public function setCantidadExistencia($cantidadExistencia)
    {
        $this->cantidadExistencia = $cantidadExistencia;

        return $this;
    }

    /**
     * Get cantidadExistencia
     *
     * @return integer
     */
    public function getCantidadExistencia()
    {
        return $this->cantidadExistencia;
    }

    /**
     * Set cantidadRemisionada
     *
     * @param integer $cantidadRemisionada
     *
     * @return InvItem
     */
    public function setCantidadRemisionada($cantidadRemisionada)
    {
        $this->cantidadRemisionada = $cantidadRemisionada;

        return $this;
    }

    /**
     * Get cantidadRemisionada
     *
     * @return integer
     */
    public function getCantidadRemisionada()
    {
        return $this->cantidadRemisionada;
    }

    /**
     * Set cantidadReservada
     *
     * @param integer $cantidadReservada
     *
     * @return InvItem
     */
    public function setCantidadReservada($cantidadReservada)
    {
        $this->cantidadReservada = $cantidadReservada;

        return $this;
    }

    /**
     * Get cantidadReservada
     *
     * @return integer
     */
    public function getCantidadReservada()
    {
        return $this->cantidadReservada;
    }

    /**
     * Set cantidadDisponible
     *
     * @param integer $cantidadDisponible
     *
     * @return InvItem
     */
    public function setCantidadDisponible($cantidadDisponible)
    {
        $this->cantidadDisponible = $cantidadDisponible;

        return $this;
    }

    /**
     * Get cantidadDisponible
     *
     * @return integer
     */
    public function getCantidadDisponible()
    {
        return $this->cantidadDisponible;
    }

    /**
     * Set cantidadOrdenCompra
     *
     * @param integer $cantidadOrdenCompra
     *
     * @return InvItem
     */
    public function setCantidadOrdenCompra($cantidadOrdenCompra)
    {
        $this->cantidadOrdenCompra = $cantidadOrdenCompra;

        return $this;
    }

    /**
     * Get cantidadOrdenCompra
     *
     * @return integer
     */
    public function getCantidadOrdenCompra()
    {
        return $this->cantidadOrdenCompra;
    }

    /**
     * Set permitirInventarioNegativo
     *
     * @param boolean $permitirInventarioNegativo
     *
     * @return InvItem
     */
    public function setPermitirInventarioNegativo($permitirInventarioNegativo)
    {
        $this->permitirInventarioNegativo = $permitirInventarioNegativo;

        return $this;
    }

    /**
     * Get permitirInventarioNegativo
     *
     * @return boolean
     */
    public function getPermitirInventarioNegativo()
    {
        return $this->permitirInventarioNegativo;
    }

    /**
     * Set codigoUnidadMedidaFk
     *
     * @param string $codigoUnidadMedidaFk
     *
     * @return InvItem
     */
    public function setCodigoUnidadMedidaFk($codigoUnidadMedidaFk)
    {
        $this->codigoUnidadMedidaFk = $codigoUnidadMedidaFk;

        return $this;
    }

    /**
     * Get codigoUnidadMedidaFk
     *
     * @return string
     */
    public function getCodigoUnidadMedidaFk()
    {
        return $this->codigoUnidadMedidaFk;
    }

    /**
     * Set servicio
     *
     * @param boolean $servicio
     *
     * @return InvItem
     */
    public function setServicio($servicio)
    {
        $this->servicio = $servicio;

        return $this;
    }

    /**
     * Get servicio
     *
     * @return boolean
     */
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * Set materiaPrima
     *
     * @param boolean $materiaPrima
     *
     * @return InvItem
     */
    public function setMateriaPrima($materiaPrima)
    {
        $this->materiaPrima = $materiaPrima;

        return $this;
    }

    /**
     * Get materiaPrima
     *
     * @return boolean
     */
    public function getMateriaPrima()
    {
        return $this->materiaPrima;
    }

    /**
     * Set marcaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMarca $marcaRel
     *
     * @return InvItem
     */
    public function setMarcaRel(\Brasa\InventarioBundle\Entity\InvMarca $marcaRel = null)
    {
        $this->marcaRel = $marcaRel;

        return $this;
    }

    /**
     * Get marcaRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvMarca
     */
    public function getMarcaRel()
    {
        return $this->marcaRel;
    }

    /**
     * Add movimientosDetallesItemRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientoDetalle $movimientosDetallesItemRel
     *
     * @return InvItem
     */
    public function addMovimientosDetallesItemRel(\Brasa\InventarioBundle\Entity\InvMovimientoDetalle $movimientosDetallesItemRel)
    {
        $this->movimientosDetallesItemRel[] = $movimientosDetallesItemRel;

        return $this;
    }

    /**
     * Remove movimientosDetallesItemRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientoDetalle $movimientosDetallesItemRel
     */
    public function removeMovimientosDetallesItemRel(\Brasa\InventarioBundle\Entity\InvMovimientoDetalle $movimientosDetallesItemRel)
    {
        $this->movimientosDetallesItemRel->removeElement($movimientosDetallesItemRel);
    }

    /**
     * Get movimientosDetallesItemRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientosDetallesItemRel()
    {
        return $this->movimientosDetallesItemRel;
    }
}
