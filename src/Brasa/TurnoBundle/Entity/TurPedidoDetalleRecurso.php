<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_pedido_detalle_recurso")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPedidoDetalleRecursoRepository")
 */
class TurPedidoDetalleRecurso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_detalle_recurso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPedidoDetalleRecursoPk;  
    
    /**
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer")
     */    
    private $codigoPedidoDetalleFk;

    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer")
     */    
    private $codigoRecursoFk;    
    
    /**
     * @ORM\Column(name="posicion", type="integer")
     */    
    private $posicion = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoDetalle", inversedBy="pedidosDetallesRecursosPedidoDetalleRel")
     * @ORM\JoinColumn(name="codigo_pedido_detalle_fk", referencedColumnName="codigo_pedido_detalle_pk")
     */
    protected $pedidoDetalleRel;       

    /**
     * @ORM\ManyToOne(targetEntity="TurRecurso", inversedBy="pedidosDetallesRecursosRecursoRel")
     * @ORM\JoinColumn(name="codigo_recurso_fk", referencedColumnName="codigo_recurso_pk")
     */
    protected $recursoRel;           


    /**
     * Get codigoPedidoDetalleRecursoPk
     *
     * @return integer
     */
    public function getCodigoPedidoDetalleRecursoPk()
    {
        return $this->codigoPedidoDetalleRecursoPk;
    }

    /**
     * Set codigoPedidoDetalleFk
     *
     * @param integer $codigoPedidoDetalleFk
     *
     * @return TurPedidoDetalleRecurso
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
     * Set posicion
     *
     * @param integer $posicion
     *
     * @return TurPedidoDetalleRecurso
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;

        return $this;
    }

    /**
     * Get posicion
     *
     * @return integer
     */
    public function getPosicion()
    {
        return $this->posicion;
    }

    /**
     * Set pedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidoDetalleRel
     *
     * @return TurPedidoDetalleRecurso
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
     * Set codigoRecursoFk
     *
     * @param integer $codigoRecursoFk
     *
     * @return TurPedidoDetalleRecurso
     */
    public function setCodigoRecursoFk($codigoRecursoFk)
    {
        $this->codigoRecursoFk = $codigoRecursoFk;

        return $this;
    }

    /**
     * Get codigoRecursoFk
     *
     * @return integer
     */
    public function getCodigoRecursoFk()
    {
        return $this->codigoRecursoFk;
    }

    /**
     * Set recursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursoRel
     *
     * @return TurPedidoDetalleRecurso
     */
    public function setRecursoRel(\Brasa\TurnoBundle\Entity\TurRecurso $recursoRel = null)
    {
        $this->recursoRel = $recursoRel;

        return $this;
    }

    /**
     * Get recursoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurRecurso
     */
    public function getRecursoRel()
    {
        return $this->recursoRel;
    }
}
