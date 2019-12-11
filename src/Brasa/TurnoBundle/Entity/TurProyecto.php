<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_proyecto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurProyectoRepository")
 */
class TurProyecto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_proyecto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProyectoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                       
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;            
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="proyectosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;          
    
    /**
     * @ORM\OneToMany(targetEntity="TurOperacion", mappedBy="proyectoRel")
     */
    protected $operacionesProyectoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCotizacionDetalle", mappedBy="proyectoRel")
     */
    protected $cotizacionesDetallesProyectoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalle", mappedBy="proyectoRel")
     */
    protected $serviciosDetallesProyectoRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="proyectoRel")
     */
    protected $pedidosDetallesProyectoRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionDetalle", mappedBy="proyectoRel")
     */
    protected $programacionesDetallesProyectoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurFactura", mappedBy="proyectoRel")
     */
    protected $facturasProyectoRel;    
    
    /**
     * Get codigoProyectoPk
     *
     * @return integer
     */
    public function getCodigoProyectoPk()
    {
        return $this->codigoProyectoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurProyecto
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
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurProyecto
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurProyecto
     */
    public function setClienteRel(\Brasa\TurnoBundle\Entity\TurCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->serviciosDetallesProyectoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidosDetallesProyectoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->programacionesDetallesProyectoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add serviciosDetallesProyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesProyectoRel
     *
     * @return TurProyecto
     */
    public function addServiciosDetallesProyectoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesProyectoRel)
    {
        $this->serviciosDetallesProyectoRel[] = $serviciosDetallesProyectoRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesProyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesProyectoRel
     */
    public function removeServiciosDetallesProyectoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesProyectoRel)
    {
        $this->serviciosDetallesProyectoRel->removeElement($serviciosDetallesProyectoRel);
    }

    /**
     * Get serviciosDetallesProyectoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesProyectoRel()
    {
        return $this->serviciosDetallesProyectoRel;
    }

    /**
     * Add pedidosDetallesProyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesProyectoRel
     *
     * @return TurProyecto
     */
    public function addPedidosDetallesProyectoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesProyectoRel)
    {
        $this->pedidosDetallesProyectoRel[] = $pedidosDetallesProyectoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesProyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesProyectoRel
     */
    public function removePedidosDetallesProyectoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesProyectoRel)
    {
        $this->pedidosDetallesProyectoRel->removeElement($pedidosDetallesProyectoRel);
    }

    /**
     * Get pedidosDetallesProyectoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesProyectoRel()
    {
        return $this->pedidosDetallesProyectoRel;
    }

    /**
     * Add programacionesDetallesProyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesProyectoRel
     *
     * @return TurProyecto
     */
    public function addProgramacionesDetallesProyectoRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesProyectoRel)
    {
        $this->programacionesDetallesProyectoRel[] = $programacionesDetallesProyectoRel;

        return $this;
    }

    /**
     * Remove programacionesDetallesProyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesProyectoRel
     */
    public function removeProgramacionesDetallesProyectoRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesProyectoRel)
    {
        $this->programacionesDetallesProyectoRel->removeElement($programacionesDetallesProyectoRel);
    }

    /**
     * Get programacionesDetallesProyectoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesDetallesProyectoRel()
    {
        return $this->programacionesDetallesProyectoRel;
    }

    /**
     * Add cotizacionesDetallesProyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesProyectoRel
     *
     * @return TurProyecto
     */
    public function addCotizacionesDetallesProyectoRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesProyectoRel)
    {
        $this->cotizacionesDetallesProyectoRel[] = $cotizacionesDetallesProyectoRel;

        return $this;
    }

    /**
     * Remove cotizacionesDetallesProyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesProyectoRel
     */
    public function removeCotizacionesDetallesProyectoRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesProyectoRel)
    {
        $this->cotizacionesDetallesProyectoRel->removeElement($cotizacionesDetallesProyectoRel);
    }

    /**
     * Get cotizacionesDetallesProyectoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCotizacionesDetallesProyectoRel()
    {
        return $this->cotizacionesDetallesProyectoRel;
    }

    /**
     * Add facturasProyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturasProyectoRel
     *
     * @return TurProyecto
     */
    public function addFacturasProyectoRel(\Brasa\TurnoBundle\Entity\TurFactura $facturasProyectoRel)
    {
        $this->facturasProyectoRel[] = $facturasProyectoRel;

        return $this;
    }

    /**
     * Remove facturasProyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturasProyectoRel
     */
    public function removeFacturasProyectoRel(\Brasa\TurnoBundle\Entity\TurFactura $facturasProyectoRel)
    {
        $this->facturasProyectoRel->removeElement($facturasProyectoRel);
    }

    /**
     * Get facturasProyectoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasProyectoRel()
    {
        return $this->facturasProyectoRel;
    }

    /**
     * Add operacionesProyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurOperacion $operacionesProyectoRel
     *
     * @return TurProyecto
     */
    public function addOperacionesProyectoRel(\Brasa\TurnoBundle\Entity\TurOperacion $operacionesProyectoRel)
    {
        $this->operacionesProyectoRel[] = $operacionesProyectoRel;

        return $this;
    }

    /**
     * Remove operacionesProyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurOperacion $operacionesProyectoRel
     */
    public function removeOperacionesProyectoRel(\Brasa\TurnoBundle\Entity\TurOperacion $operacionesProyectoRel)
    {
        $this->operacionesProyectoRel->removeElement($operacionesProyectoRel);
    }

    /**
     * Get operacionesProyectoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOperacionesProyectoRel()
    {
        return $this->operacionesProyectoRel;
    }
}
