<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_modalidad_servicio")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurModalidadServicioRepository")
 */
class TurModalidadServicio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_modalidad_servicio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoModalidadServicioPk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;    

    /**
     * @ORM\Column(name="tipo", type="integer")
     */    
    private $tipo = 0;    

    /**
     * @ORM\Column(name="porcentaje", type="float")
     */    
    private $porcentaje = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
  
    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalle", mappedBy="modalidadServicioRel")
     */
    protected $facturasDetallesModalidadServicioRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="modalidadServicioRel")
     */
    protected $pedidosDetallesModalidadServicioRel;     

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleCompuesto", mappedBy="modalidadServicioRel")
     */
    protected $pedidosDetallesCompuestosModalidadServicioRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalle", mappedBy="modalidadServicioRel")
     */
    protected $serviciosDetallesModalidadServicioRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalleCompuesto", mappedBy="modalidadServicioRel")
     */
    protected $serviciosDetallesCompuestosModalidadServicioRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCotizacionDetalle", mappedBy="modalidadServicioRel")
     */
    protected $cotizacionesDetallesModalidadServicioRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TurCostoServicio", mappedBy="modalidadServicioRel")
     */
    protected $costosServiciosModalidadServicioRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasDetallesModalidadServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidosDetallesModalidadServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidosDetallesCompuestosModalidadServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosDetallesModalidadServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosDetallesCompuestosModalidadServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cotizacionesDetallesModalidadServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->costosServiciosModalidadServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoModalidadServicioPk
     *
     * @return integer
     */
    public function getCodigoModalidadServicioPk()
    {
        return $this->codigoModalidadServicioPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurModalidadServicio
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
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return TurModalidadServicio
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set porcentaje
     *
     * @param float $porcentaje
     *
     * @return TurModalidadServicio
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return float
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurModalidadServicio
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
     * Add facturasDetallesModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesModalidadServicioRel
     *
     * @return TurModalidadServicio
     */
    public function addFacturasDetallesModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesModalidadServicioRel)
    {
        $this->facturasDetallesModalidadServicioRel[] = $facturasDetallesModalidadServicioRel;

        return $this;
    }

    /**
     * Remove facturasDetallesModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesModalidadServicioRel
     */
    public function removeFacturasDetallesModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesModalidadServicioRel)
    {
        $this->facturasDetallesModalidadServicioRel->removeElement($facturasDetallesModalidadServicioRel);
    }

    /**
     * Get facturasDetallesModalidadServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesModalidadServicioRel()
    {
        return $this->facturasDetallesModalidadServicioRel;
    }

    /**
     * Add pedidosDetallesModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesModalidadServicioRel
     *
     * @return TurModalidadServicio
     */
    public function addPedidosDetallesModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesModalidadServicioRel)
    {
        $this->pedidosDetallesModalidadServicioRel[] = $pedidosDetallesModalidadServicioRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesModalidadServicioRel
     */
    public function removePedidosDetallesModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesModalidadServicioRel)
    {
        $this->pedidosDetallesModalidadServicioRel->removeElement($pedidosDetallesModalidadServicioRel);
    }

    /**
     * Get pedidosDetallesModalidadServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesModalidadServicioRel()
    {
        return $this->pedidosDetallesModalidadServicioRel;
    }

    /**
     * Add pedidosDetallesCompuestosModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto $pedidosDetallesCompuestosModalidadServicioRel
     *
     * @return TurModalidadServicio
     */
    public function addPedidosDetallesCompuestosModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto $pedidosDetallesCompuestosModalidadServicioRel)
    {
        $this->pedidosDetallesCompuestosModalidadServicioRel[] = $pedidosDetallesCompuestosModalidadServicioRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesCompuestosModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto $pedidosDetallesCompuestosModalidadServicioRel
     */
    public function removePedidosDetallesCompuestosModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto $pedidosDetallesCompuestosModalidadServicioRel)
    {
        $this->pedidosDetallesCompuestosModalidadServicioRel->removeElement($pedidosDetallesCompuestosModalidadServicioRel);
    }

    /**
     * Get pedidosDetallesCompuestosModalidadServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesCompuestosModalidadServicioRel()
    {
        return $this->pedidosDetallesCompuestosModalidadServicioRel;
    }

    /**
     * Add serviciosDetallesModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesModalidadServicioRel
     *
     * @return TurModalidadServicio
     */
    public function addServiciosDetallesModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesModalidadServicioRel)
    {
        $this->serviciosDetallesModalidadServicioRel[] = $serviciosDetallesModalidadServicioRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesModalidadServicioRel
     */
    public function removeServiciosDetallesModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesModalidadServicioRel)
    {
        $this->serviciosDetallesModalidadServicioRel->removeElement($serviciosDetallesModalidadServicioRel);
    }

    /**
     * Get serviciosDetallesModalidadServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesModalidadServicioRel()
    {
        return $this->serviciosDetallesModalidadServicioRel;
    }

    /**
     * Add serviciosDetallesCompuestosModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto $serviciosDetallesCompuestosModalidadServicioRel
     *
     * @return TurModalidadServicio
     */
    public function addServiciosDetallesCompuestosModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto $serviciosDetallesCompuestosModalidadServicioRel)
    {
        $this->serviciosDetallesCompuestosModalidadServicioRel[] = $serviciosDetallesCompuestosModalidadServicioRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesCompuestosModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto $serviciosDetallesCompuestosModalidadServicioRel
     */
    public function removeServiciosDetallesCompuestosModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleCompuesto $serviciosDetallesCompuestosModalidadServicioRel)
    {
        $this->serviciosDetallesCompuestosModalidadServicioRel->removeElement($serviciosDetallesCompuestosModalidadServicioRel);
    }

    /**
     * Get serviciosDetallesCompuestosModalidadServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesCompuestosModalidadServicioRel()
    {
        return $this->serviciosDetallesCompuestosModalidadServicioRel;
    }

    /**
     * Add cotizacionesDetallesModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesModalidadServicioRel
     *
     * @return TurModalidadServicio
     */
    public function addCotizacionesDetallesModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesModalidadServicioRel)
    {
        $this->cotizacionesDetallesModalidadServicioRel[] = $cotizacionesDetallesModalidadServicioRel;

        return $this;
    }

    /**
     * Remove cotizacionesDetallesModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesModalidadServicioRel
     */
    public function removeCotizacionesDetallesModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesModalidadServicioRel)
    {
        $this->cotizacionesDetallesModalidadServicioRel->removeElement($cotizacionesDetallesModalidadServicioRel);
    }

    /**
     * Get cotizacionesDetallesModalidadServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCotizacionesDetallesModalidadServicioRel()
    {
        return $this->cotizacionesDetallesModalidadServicioRel;
    }

    /**
     * Add costosServiciosModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosModalidadServicioRel
     *
     * @return TurModalidadServicio
     */
    public function addCostosServiciosModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosModalidadServicioRel)
    {
        $this->costosServiciosModalidadServicioRel[] = $costosServiciosModalidadServicioRel;

        return $this;
    }

    /**
     * Remove costosServiciosModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosModalidadServicioRel
     */
    public function removeCostosServiciosModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosModalidadServicioRel)
    {
        $this->costosServiciosModalidadServicioRel->removeElement($costosServiciosModalidadServicioRel);
    }

    /**
     * Get costosServiciosModalidadServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCostosServiciosModalidadServicioRel()
    {
        return $this->costosServiciosModalidadServicioRel;
    }
}
