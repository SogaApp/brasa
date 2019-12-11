<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_lista_costo_detalle")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvListaCostoDetalleRepository")
 */
class InvListaCostoDetalle
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_lista_costos_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoListaCostosDetallePk;

    /**
     * @ORM\Column(name="codigo_lista_costos_fk", type="integer")     
     */        
    private $codigoListaCostosFk;    
    
    /**
     * @ORM\Column(name="codigo_item_fk", type="integer", nullable=true)
     */     
    private $codigoItemFk;    
    
    /**
     * @ORM\Column(name="costo", type="float")
     */    
    private $costo = 0;    
    
    /**
     * @ORM\Column(name="factor", type="integer")
     */    
    private $factor = 0;
    
    /**
     * @ORM\Column(name="costo_umm", type="float")
     */    
    private $costoUMM = 0;    

    /**
     * @ORM\Column(name="estado_inactiva", type="boolean")
     */    
    private $estadoInactiva = 0;     
           
    /**
     * @ORM\ManyToOne(targetEntity="InvListaCosto", inversedBy="InvListaCostoDetalle")
     * @ORM\JoinColumn(name="codigo_lista_costos_fk", referencedColumnName="codigo_lista_costos_pk")
     */
    protected $listaCostosRel;    

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="InvListaCostoDetalle")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;    




    /**
     * Get codigoListaCostosDetallePk
     *
     * @return integer 
     */
    public function getCodigoListaCostosDetallePk()
    {
        return $this->codigoListaCostosDetallePk;
    }

    /**
     * Set codigoListaCostosFk
     *
     * @param integer $codigoListaCostosFk
     * @return InvListaCostoDetalle
     */
    public function setCodigoListaCostosFk($codigoListaCostosFk)
    {
        $this->codigoListaCostosFk = $codigoListaCostosFk;

        return $this;
    }

    /**
     * Get codigoListaCostosFk
     *
     * @return integer 
     */
    public function getCodigoListaCostosFk()
    {
        return $this->codigoListaCostosFk;
    }

    /**
     * Set codigoItemFk
     *
     * @param integer $codigoItemFk
     * @return InvListaCostoDetalle
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
     * Set costo
     *
     * @param float $costo
     * @return InvListaCostoDetalle
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float 
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set factor
     *
     * @param integer $factor
     * @return InvListaCostoDetalle
     */
    public function setFactor($factor)
    {
        $this->factor = $factor;

        return $this;
    }

    /**
     * Get factor
     *
     * @return integer 
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Set costoUMM
     *
     * @param float $costoUMM
     * @return InvListaCostoDetalle
     */
    public function setCostoUMM($costoUMM)
    {
        $this->costoUMM = $costoUMM;

        return $this;
    }

    /**
     * Get costoUMM
     *
     * @return float 
     */
    public function getCostoUMM()
    {
        return $this->costoUMM;
    }

    /**
     * Set estadoInactiva
     *
     * @param boolean $estadoInactiva
     * @return InvListaCostoDetalle
     */
    public function setEstadoInactiva($estadoInactiva)
    {
        $this->estadoInactiva = $estadoInactiva;

        return $this;
    }

    /**
     * Get estadoInactiva
     *
     * @return boolean 
     */
    public function getEstadoInactiva()
    {
        return $this->estadoInactiva;
    }

    /**
     * Set listaCostosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvListaCosto $listaCostosRel
     * @return InvListaCostoDetalle
     */
    public function setListaCostosRel(\Brasa\InventarioBundle\Entity\InvListaCosto $listaCostosRel = null)
    {
        $this->listaCostosRel = $listaCostosRel;

        return $this;
    }

    /**
     * Get listaCostosRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvListaCosto 
     */
    public function getListaCostosRel()
    {
        return $this->listaCostosRel;
    }

    /**
     * Set itemRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemRel
     * @return InvListaCostoDetalle
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
