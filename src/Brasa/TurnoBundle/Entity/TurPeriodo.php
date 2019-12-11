<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_periodo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPeriodoRepository")
 */
class TurPeriodo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoPk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;    
      
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="periodoRel")
     */
    protected $pedidosDetallesPeriodoRel;     

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleCompuesto", mappedBy="periodoRel")
     */
    protected $pedidosDetallesCompuestosPeriodoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalle", mappedBy="periodoRel")
     */
    protected $serviciosDetallesPeriodoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalleCompuesto", mappedBy="periodoRel")
     */
    protected $serviciosDetallesCompuestosPeriodoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCotizacionDetalle", mappedBy="periodoRel")
     */
    protected $cotizacionesDetallesPeriodoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurCostoServicio", mappedBy="periodoRel")
     */
    protected $costosServiciosPeriodoRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosDetallesPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidosDetallesCompuestosPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosDetallesPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosDetallesCompuestosPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cotizacionesDetallesPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->costosServiciosPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPeriodoPk
     *
     * @return integer
     */
    public function getCodigoPeriodoPk()
    {
        return $this->codigoPeriodoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurPeriodo
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurPeriodo
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Add pedidosDetallesPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPeriodoRel
     *
     * @return TurPeriodo
     */
    public function addPedidosDetallesPeriodoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPeriodoRel)
    {
        $this->pedidosDetallesPeriodoRel[] = $pedidosDetallesPeriodoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPeriodoRel
     */
    public function removePedidosDetallesPeriodoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPeriodoRel)
    {
        $this->pedidosDetallesPeriodoRel->removeElement($pedidosDetallesPeriodoRel);
    }

    /**
     * Get pedidosDetallesPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesPeriodoRel()
    {
        return $this->pedidosDetallesPeriodoRel;
    }

    /**
     * Add pedidosDetallesCompuestosPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto $pedidosDetallesCompuestosPeriodoRel
     *
     * @return TurPeriodo
     */
    public function addPedidosDetallesCompuestosPeriodoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto $pedidosDetallesCompuestosPeriodoRel)
    {
        $this->pedidosDetallesCompuestosPeriodoRel[] = $pedidosDetallesCompuestosPeriodoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesCompuestosPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto $pedidosDetallesCompuestosPeriodoRel
     */
    public function removePedidosDetallesCompuestosPeriodoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto $pedidosDetallesCompuestosPeriodoRel)
    {
        $this->pedidosDetallesCompuestosPeriodoRel->removeElement($pedidosDetallesCompuestosPeriodoRel);
    }

    /**
     * Get pedidosDetallesCompuestosPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesCompuestosPeriodoRel()
    {
        return $this->pedidosDetallesCompuestosPeriodoRel;
    }

    /**
     * Add serviciosDetallesPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPeriodoRel
     *
     * @return TurPeriodo
     */
    public function addServiciosDetallesPeriodoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPeriodoRel)
    {
        $this->serviciosDetallesPeriodoRel[] = $serviciosDetallesPeriodoRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPeriodoRel
     */
    public function removeServiciosDetallesPeriodoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPeriodoRel)
    {
        $this->serviciosDetallesPeriodoRel->removeElement($serviciosDetallesPeriodoRel);
    }

    /**
     * Get serviciosDetallesPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesPeriodoRel()
    {
        return $this->serviciosDetallesPeriodoRel;
    }

    /**
     * Add serviciosDetallesCompuestosPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto $serviciosDetallesCompuestosPeriodoRel
     *
     * @return TurPeriodo
     */
    public function addServiciosDetallesCompuestosPeriodoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto $serviciosDetallesCompuestosPeriodoRel)
    {
        $this->serviciosDetallesCompuestosPeriodoRel[] = $serviciosDetallesCompuestosPeriodoRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesCompuestosPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto $serviciosDetallesCompuestosPeriodoRel
     */
    public function removeServiciosDetallesCompuestosPeriodoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto $serviciosDetallesCompuestosPeriodoRel)
    {
        $this->serviciosDetallesCompuestosPeriodoRel->removeElement($serviciosDetallesCompuestosPeriodoRel);
    }

    /**
     * Get serviciosDetallesCompuestosPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesCompuestosPeriodoRel()
    {
        return $this->serviciosDetallesCompuestosPeriodoRel;
    }

    /**
     * Add cotizacionesDetallesPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesPeriodoRel
     *
     * @return TurPeriodo
     */
    public function addCotizacionesDetallesPeriodoRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesPeriodoRel)
    {
        $this->cotizacionesDetallesPeriodoRel[] = $cotizacionesDetallesPeriodoRel;

        return $this;
    }

    /**
     * Remove cotizacionesDetallesPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesPeriodoRel
     */
    public function removeCotizacionesDetallesPeriodoRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesPeriodoRel)
    {
        $this->cotizacionesDetallesPeriodoRel->removeElement($cotizacionesDetallesPeriodoRel);
    }

    /**
     * Get cotizacionesDetallesPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCotizacionesDetallesPeriodoRel()
    {
        return $this->cotizacionesDetallesPeriodoRel;
    }

    /**
     * Add costosServiciosPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPeriodoRel
     *
     * @return TurPeriodo
     */
    public function addCostosServiciosPeriodoRel(\Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPeriodoRel)
    {
        $this->costosServiciosPeriodoRel[] = $costosServiciosPeriodoRel;

        return $this;
    }

    /**
     * Remove costosServiciosPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPeriodoRel
     */
    public function removeCostosServiciosPeriodoRel(\Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPeriodoRel)
    {
        $this->costosServiciosPeriodoRel->removeElement($costosServiciosPeriodoRel);
    }

    /**
     * Get costosServiciosPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCostosServiciosPeriodoRel()
    {
        return $this->costosServiciosPeriodoRel;
    }
}
