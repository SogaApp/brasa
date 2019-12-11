<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_cierre_mes_item")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvCierreMesItemRepository")
 */
class InvCierreMesItem
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_mes_item_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCierreMesItemPk;
    
    /**
     * @ORM\Column(name="codigo_cierre_mes_fk", type="integer", nullable=true)
     */    
    private $codigoCierreMesFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="InvCierreMes", inversedBy="InvCierreMesItem")
     * @ORM\JoinColumn(name="codigo_cierre_mes_fk", referencedColumnName="codigo_cierre_mes_pk")
     */
    protected $cierreMesRel;    


    /**
     * Get codigoCierreMesItemPk
     *
     * @return integer 
     */
    public function getCodigoCierreMesItemPk()
    {
        return $this->codigoCierreMesItemPk;
    }

    /**
     * Set codigoCierreMesFk
     *
     * @param integer $codigoCierreMesFk
     * @return InvCierreMesItem
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
     * Set cierreMesRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvCierreMes $cierreMesRel
     * @return InvCierreMesItem
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
