<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_sector")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurSectorRepository")
 */
class TurSector
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sector_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSectorPk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\Column(name="porcentaje", type="float")
     */    
    private $porcentaje = 0;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     

    /**
     * @ORM\OneToMany(targetEntity="TurCotizacion", mappedBy="sectorRel")
     */
    protected $cotizacionesSectorRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedido", mappedBy="sectorRel")
     */
    protected $pedidosSectorRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurServicio", mappedBy="sectorRel")
     */
    protected $serviciosSectorRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurCliente", mappedBy="sectorRel")
     */
    protected $clientesSectorRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cotizacionesSectorRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidosSectorRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosSectorRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->clientesSectorRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSectorPk
     *
     * @return integer
     */
    public function getCodigoSectorPk()
    {
        return $this->codigoSectorPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurSector
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
     * Set porcentaje
     *
     * @param float $porcentaje
     *
     * @return TurSector
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
     * @return TurSector
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
     * Add cotizacionesSectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesSectorRel
     *
     * @return TurSector
     */
    public function addCotizacionesSectorRel(\Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesSectorRel)
    {
        $this->cotizacionesSectorRel[] = $cotizacionesSectorRel;

        return $this;
    }

    /**
     * Remove cotizacionesSectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesSectorRel
     */
    public function removeCotizacionesSectorRel(\Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesSectorRel)
    {
        $this->cotizacionesSectorRel->removeElement($cotizacionesSectorRel);
    }

    /**
     * Get cotizacionesSectorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCotizacionesSectorRel()
    {
        return $this->cotizacionesSectorRel;
    }

    /**
     * Add pedidosSectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedido $pedidosSectorRel
     *
     * @return TurSector
     */
    public function addPedidosSectorRel(\Brasa\TurnoBundle\Entity\TurPedido $pedidosSectorRel)
    {
        $this->pedidosSectorRel[] = $pedidosSectorRel;

        return $this;
    }

    /**
     * Remove pedidosSectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedido $pedidosSectorRel
     */
    public function removePedidosSectorRel(\Brasa\TurnoBundle\Entity\TurPedido $pedidosSectorRel)
    {
        $this->pedidosSectorRel->removeElement($pedidosSectorRel);
    }

    /**
     * Get pedidosSectorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosSectorRel()
    {
        return $this->pedidosSectorRel;
    }

    /**
     * Add serviciosSectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicio $serviciosSectorRel
     *
     * @return TurSector
     */
    public function addServiciosSectorRel(\Brasa\TurnoBundle\Entity\TurServicio $serviciosSectorRel)
    {
        $this->serviciosSectorRel[] = $serviciosSectorRel;

        return $this;
    }

    /**
     * Remove serviciosSectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicio $serviciosSectorRel
     */
    public function removeServiciosSectorRel(\Brasa\TurnoBundle\Entity\TurServicio $serviciosSectorRel)
    {
        $this->serviciosSectorRel->removeElement($serviciosSectorRel);
    }

    /**
     * Get serviciosSectorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosSectorRel()
    {
        return $this->serviciosSectorRel;
    }

    /**
     * Add clientesSectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clientesSectorRel
     *
     * @return TurSector
     */
    public function addClientesSectorRel(\Brasa\TurnoBundle\Entity\TurCliente $clientesSectorRel)
    {
        $this->clientesSectorRel[] = $clientesSectorRel;

        return $this;
    }

    /**
     * Remove clientesSectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clientesSectorRel
     */
    public function removeClientesSectorRel(\Brasa\TurnoBundle\Entity\TurCliente $clientesSectorRel)
    {
        $this->clientesSectorRel->removeElement($clientesSectorRel);
    }

    /**
     * Get clientesSectorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClientesSectorRel()
    {
        return $this->clientesSectorRel;
    }
}
