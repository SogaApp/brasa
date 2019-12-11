<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_pedido_tipo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPedidoTipoRepository")
 */
class TurPedidoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPedidoTipoPk;               
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */    
    private $nombre;             
    
    /**
     * @ORM\Column(name="tipo", type="string", length=50, nullable=true)
     */    
    private $tipo;    
    
    /**     
     * @ORM\Column(name="control", type="boolean")
     */    
    private $control = false;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedido", mappedBy="pedidoTipoRel")
     */
    protected $pedidosPedidoTipoRel; 


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosPedidoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPedidoTipoPk
     *
     * @return integer
     */
    public function getCodigoPedidoTipoPk()
    {
        return $this->codigoPedidoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurPedidoTipo
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
     * Set control
     *
     * @param boolean $control
     *
     * @return TurPedidoTipo
     */
    public function setControl($control)
    {
        $this->control = $control;

        return $this;
    }

    /**
     * Get control
     *
     * @return boolean
     */
    public function getControl()
    {
        return $this->control;
    }

    /**
     * Add pedidosPedidoTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedido $pedidosPedidoTipoRel
     *
     * @return TurPedidoTipo
     */
    public function addPedidosPedidoTipoRel(\Brasa\TurnoBundle\Entity\TurPedido $pedidosPedidoTipoRel)
    {
        $this->pedidosPedidoTipoRel[] = $pedidosPedidoTipoRel;

        return $this;
    }

    /**
     * Remove pedidosPedidoTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedido $pedidosPedidoTipoRel
     */
    public function removePedidosPedidoTipoRel(\Brasa\TurnoBundle\Entity\TurPedido $pedidosPedidoTipoRel)
    {
        $this->pedidosPedidoTipoRel->removeElement($pedidosPedidoTipoRel);
    }

    /**
     * Get pedidosPedidoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosPedidoTipoRel()
    {
        return $this->pedidosPedidoTipoRel;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return TurPedidoTipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
