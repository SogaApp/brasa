<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_grupo_facturacion")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurGrupoFacturacionRepository")
 */
class TurGrupoFacturacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_grupo_facturacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoGrupoFacturacionPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */
    private $nombre;                       
    
    /**
     * @ORM\Column(name="abreviatura", type="string", length=10, nullable=true)
     */
    private $abreviatura;    
    
    /**
     * @ORM\Column(name="concepto", type="string", length=120, nullable=true)
     */
    private $concepto;    
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;            
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="gruposFacturacionesClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;              
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalle", mappedBy="grupoFacturacionRel")
     */
    protected $serviciosDetallesGrupoFacturacionRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="grupoFacturacionRel")
     */
    protected $pedidosDetallesGrupoFacturacionRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalle", mappedBy="grupoFacturacionRel")
     */
    protected $facturasDetallesGrupoFacturacionRel;     

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->serviciosDetallesGrupoFacturacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidosDetallesGrupoFacturacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoGrupoFacturacionPk
     *
     * @return integer
     */
    public function getCodigoGrupoFacturacionPk()
    {
        return $this->codigoGrupoFacturacionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurGrupoFacturacion
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
     * Set abreviatura
     *
     * @param string $abreviatura
     *
     * @return TurGrupoFacturacion
     */
    public function setAbreviatura($abreviatura)
    {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * Get abreviatura
     *
     * @return string
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurGrupoFacturacion
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
     * @return TurGrupoFacturacion
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
     * Add serviciosDetallesGrupoFacturacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesGrupoFacturacionRel
     *
     * @return TurGrupoFacturacion
     */
    public function addServiciosDetallesGrupoFacturacionRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesGrupoFacturacionRel)
    {
        $this->serviciosDetallesGrupoFacturacionRel[] = $serviciosDetallesGrupoFacturacionRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesGrupoFacturacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesGrupoFacturacionRel
     */
    public function removeServiciosDetallesGrupoFacturacionRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesGrupoFacturacionRel)
    {
        $this->serviciosDetallesGrupoFacturacionRel->removeElement($serviciosDetallesGrupoFacturacionRel);
    }

    /**
     * Get serviciosDetallesGrupoFacturacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesGrupoFacturacionRel()
    {
        return $this->serviciosDetallesGrupoFacturacionRel;
    }

    /**
     * Add pedidosDetallesGrupoFacturacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesGrupoFacturacionRel
     *
     * @return TurGrupoFacturacion
     */
    public function addPedidosDetallesGrupoFacturacionRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesGrupoFacturacionRel)
    {
        $this->pedidosDetallesGrupoFacturacionRel[] = $pedidosDetallesGrupoFacturacionRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesGrupoFacturacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesGrupoFacturacionRel
     */
    public function removePedidosDetallesGrupoFacturacionRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesGrupoFacturacionRel)
    {
        $this->pedidosDetallesGrupoFacturacionRel->removeElement($pedidosDetallesGrupoFacturacionRel);
    }

    /**
     * Get pedidosDetallesGrupoFacturacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesGrupoFacturacionRel()
    {
        return $this->pedidosDetallesGrupoFacturacionRel;
    }

    /**
     * Add facturasDetallesGrupoFacturacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesGrupoFacturacionRel
     *
     * @return TurGrupoFacturacion
     */
    public function addFacturasDetallesGrupoFacturacionRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesGrupoFacturacionRel)
    {
        $this->facturasDetallesGrupoFacturacionRel[] = $facturasDetallesGrupoFacturacionRel;

        return $this;
    }

    /**
     * Remove facturasDetallesGrupoFacturacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesGrupoFacturacionRel
     */
    public function removeFacturasDetallesGrupoFacturacionRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesGrupoFacturacionRel)
    {
        $this->facturasDetallesGrupoFacturacionRel->removeElement($facturasDetallesGrupoFacturacionRel);
    }

    /**
     * Get facturasDetallesGrupoFacturacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesGrupoFacturacionRel()
    {
        return $this->facturasDetallesGrupoFacturacionRel;
    }

    /**
     * Set concepto
     *
     * @param string $concepto
     *
     * @return TurGrupoFacturacion
     */
    public function setConcepto($concepto)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get concepto
     *
     * @return string
     */
    public function getConcepto()
    {
        return $this->concepto;
    }
}
