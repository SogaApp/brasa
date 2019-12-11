<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_lote")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvLoteRepository")
 */
class InvLote
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_item_fk", type="integer")
     */     
    private $codigoItemFk;     
    
    /**
     * @ORM\Id
     * @ORM\Column(name="lote_fk", type="string", length=40)
     */      
    private $loteFk;
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_bodega_fk", type="string", length=10)
     */     
    private $codigoBodegaFk;
    
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
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */            
    private $fechaVencimiento;    

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="InvLote")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="InvBodega", inversedBy="lotesBodegaRel")
     * @ORM\JoinColumn(name="codigo_bodega_fk", referencedColumnName="codigo_bodega_pk")
     */
    protected $bodegaRel;    
    


    /**
     * Set codigoItemFk
     *
     * @param integer $codigoItemFk
     *
     * @return InvLote
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
     * Set loteFk
     *
     * @param string $loteFk
     *
     * @return InvLote
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
     * Set codigoBodegaFk
     *
     * @param string $codigoBodegaFk
     *
     * @return InvLote
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
     * Set cantidadExistencia
     *
     * @param integer $cantidadExistencia
     *
     * @return InvLote
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
     * @return InvLote
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
     * @return InvLote
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
     * @return InvLote
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
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return InvLote
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
     * Set itemRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemRel
     *
     * @return InvLote
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

    /**
     * Set bodegaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvBodega $bodegaRel
     *
     * @return InvLote
     */
    public function setBodegaRel(\Brasa\InventarioBundle\Entity\InvBodega $bodegaRel = null)
    {
        $this->bodegaRel = $bodegaRel;

        return $this;
    }

    /**
     * Get bodegaRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvBodega
     */
    public function getBodegaRel()
    {
        return $this->bodegaRel;
    }
}
