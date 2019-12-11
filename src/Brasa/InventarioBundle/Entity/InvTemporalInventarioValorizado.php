<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="inv_temporal_inventario_valorizado")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvTemporalInventarioValorizadoRepository")
 */
class InvTemporalInventarioValorizado
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_temporal_inventario_valorizado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoTemporalInventarioValorizadoPk;
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;     
    
    /**
     * @ORM\Column(name="codigo_item_fk", type="integer", nullable=true)
     */     
    private $codigoItemFk; 
    
    /**
     * @ORM\Column(name="saldo", type="integer")
     */        
    private $saldo = 0;
    
    /**
     * @ORM\Column(name="costo_promedio", type="float")
     */    
    private $costoPromedio = 0;
    
    /**
     * @ORM\Column(name="total_promedio", type="float")
     */    
    private $totalPromedio = 0;    
    
    /**
     * @ORM\Column(name="codigo_usuario_fk", type="string", length=20, nullable=true)
     */    
    private $codigoUsuarioFk;     
        
    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="InvTemporalInventarioValorizado")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel; 
    

    /**
     * Get codigoTemporalInventarioValorizadoPk
     *
     * @return integer 
     */
    public function getCodigoTemporalInventarioValorizadoPk()
    {
        return $this->codigoTemporalInventarioValorizadoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return InvTemporalInventarioValorizado
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set codigoItemFk
     *
     * @param integer $codigoItemFk
     * @return InvTemporalInventarioValorizado
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
     * Set saldo
     *
     * @param integer $saldo
     * @return InvTemporalInventarioValorizado
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;

        return $this;
    }

    /**
     * Get saldo
     *
     * @return integer 
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Set costoPromedio
     *
     * @param float $costoPromedio
     * @return InvTemporalInventarioValorizado
     */
    public function setCostoPromedio($costoPromedio)
    {
        $this->costoPromedio = $costoPromedio;

        return $this;
    }

    /**
     * Get costoPromedio
     *
     * @return float 
     */
    public function getCostoPromedio()
    {
        return $this->costoPromedio;
    }

    /**
     * Set totalPromedio
     *
     * @param float $totalPromedio
     * @return InvTemporalInventarioValorizado
     */
    public function setTotalPromedio($totalPromedio)
    {
        $this->totalPromedio = $totalPromedio;

        return $this;
    }

    /**
     * Get totalPromedio
     *
     * @return float 
     */
    public function getTotalPromedio()
    {
        return $this->totalPromedio;
    }

    /**
     * Set codigoUsuarioFk
     *
     * @param string $codigoUsuarioFk
     * @return InvTemporalInventarioValorizado
     */
    public function setCodigoUsuarioFk($codigoUsuarioFk)
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;

        return $this;
    }

    /**
     * Get codigoUsuarioFk
     *
     * @return string 
     */
    public function getCodigoUsuarioFk()
    {
        return $this->codigoUsuarioFk;
    }

    /**
     * Set itemRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemRel
     * @return InvTemporalInventarioValorizado
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
