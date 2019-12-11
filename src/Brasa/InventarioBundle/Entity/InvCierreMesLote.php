<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_cierre_mes_lote")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvCierreMesLoteRepository")
 */
class InvCierreMesLote
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_mes_lote_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCierreMesLotePk;
    
    /**
     * @ORM\Column(name="codigo_cierre_mes_fk", type="integer", nullable=true)
     */    
    private $codigoCierreMesFk;    
    
    /**
     * @ORM\Column(name="codigo_item_fk", type="integer")
     */     
    private $codigoItemFk;     
    
    /**
     * @ORM\Column(name="lote_fk", type="string", length=40)
     */      
    private $loteFk;
    
    /**
     * @ORM\Column(name="codigo_bodega_fk", type="smallint")
     */     
    private $codigoBodegaFk;
    
    /**
     * @ORM\Column(name="existencia", type="integer")
     */            
    private $existencia = 0;

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
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="InvLote")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="InvCierreMes", inversedBy="InvCierreMesLote")
     * @ORM\JoinColumn(name="codigo_cierre_mes_fk", referencedColumnName="codigo_cierre_mes_pk")
     */
    protected $cierreMesRel;    



    /**
     * Get codigoCierreMesLotePk
     *
     * @return integer 
     */
    public function getCodigoCierreMesLotePk()
    {
        return $this->codigoCierreMesLotePk;
    }

    /**
     * Set codigoCierreMesFk
     *
     * @param integer $codigoCierreMesFk
     * @return InvCierreMesLote
     */
    public function setCodigoCierreMesFk($codigoCierreMesFk)
    {
        $this->codigoCierreMesFk = $codigoCierreMesFk;

        return $this;
    }

    /**
     * Get codigoCierreMesFk
     *
     * @return integer 
     */
    public function getCodigoCierreMesFk()
    {
        return $this->codigoCierreMesFk;
    }

    /**
     * Set codigoItemFk
     *
     * @param integer $codigoItemFk
     * @return InvCierreMesLote
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
     * @return InvCierreMesLote
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
     * @param integer $codigoBodegaFk
     * @return InvCierreMesLote
     */
    public function setCodigoBodegaFk($codigoBodegaFk)
    {
        $this->codigoBodegaFk = $codigoBodegaFk;

        return $this;
    }

    /**
     * Get codigoBodegaFk
     *
     * @return integer 
     */
    public function getCodigoBodegaFk()
    {
        return $this->codigoBodegaFk;
    }

    /**
     * Set existencia
     *
     * @param integer $existencia
     * @return InvCierreMesLote
     */
    public function setExistencia($existencia)
    {
        $this->existencia = $existencia;

        return $this;
    }

    /**
     * Get existencia
     *
     * @return integer 
     */
    public function getExistencia()
    {
        return $this->existencia;
    }

    /**
     * Set cantidadRemisionada
     *
     * @param integer $cantidadRemisionada
     * @return InvCierreMesLote
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
     * @return InvCierreMesLote
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
     * @return InvCierreMesLote
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
     * Set itemRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemRel
     * @return InvCierreMesLote
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
     * Set cierreMesRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvCierreMes $cierreMesRel
     * @return InvCierreMesLote
     */
    public function setCierreMesRel(\Brasa\InventarioBundle\Entity\InvCierreMes $cierreMesRel = null)
    {
        $this->cierreMesRel = $cierreMesRel;

        return $this;
    }

    /**
     * Get cierreMesRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvCierreMes 
     */
    public function getCierreMesRel()
    {
        return $this->cierreMesRel;
    }
}
