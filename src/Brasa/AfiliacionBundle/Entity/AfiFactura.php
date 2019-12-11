<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_factura")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiFacturaRepository")
 */
class AfiFactura
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaPk;    
        
    /**
     * @ORM\Column(name="codigo_factura_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoFacturaTipoFk;     
    
    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */    
    private $numero = 0;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;        

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */    
    private $fechaVence;    
    
    /**
     * @ORM\Column(name="soporte", type="string", length=30, nullable=true)
     */
    private $soporte;     
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;    

    /**
     * @ORM\Column(name="curso", type="float")
     */
    private $curso = 0; 
    
    /**
     * @ORM\Column(name="subtotal", type="float")
     */
    private $subTotal = 0;     
    
    /**
     * @ORM\Column(name="iva", type="float")
     */
    private $iva = 0;     
    
    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0;     
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;

    /**     
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = false;    
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;  
    
    /**     
     * @ORM\Column(name="afiliacion", type="boolean")
     */    
    private $afiliacion = false;
    
    /**
     * @ORM\Column(name="interes_mora", type="float")
     */
    private $interesMora = 0;

    /**
     * @ORM\Column(name="cantidad", type="integer", nullable=true)
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="valor_unitario", type="integer", nullable=true)
     */
    private $valorPorEmpleado = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiCliente", inversedBy="facturasClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    

    /**
     * @ORM\ManyToOne(targetEntity="AfiFacturaTipo", inversedBy="facturasFacturaTipoRel")
     * @ORM\JoinColumn(name="codigo_factura_tipo_fk", referencedColumnName="codigo_factura_tipo_pk")
     */
    protected $facturaTipoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="AfiFacturaDetalle", mappedBy="facturaRel")
     */
    protected $facturasDetallesFacturaRel;
    
    
    /**
     * @ORM\OneToMany(targetEntity="AfiFacturaDetalleCurso", mappedBy="facturaRel")
     */
    protected $facturasDetallesCursosFacturaRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiFacturaDetalleAfiliacion", mappedBy="contratoRel")
     */
    protected $facturasDetallesAfiliacionesContratosRel;
    
    /**
     * @ORM\OneToMany(targetEntity="AfiFacturaDetalleAfiliacion", mappedBy="facturaRel")
     */
    protected $facturasDetallesAfiliacionesFacturaRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasDetallesFacturaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasDetallesCursosFacturaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasDetallesAfiliacionesContratosRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasDetallesAfiliacionesFacturaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaPk
     *
     * @return integer
     */
    public function getCodigoFacturaPk()
    {
        return $this->codigoFacturaPk;
    }

    /**
     * Set codigoFacturaTipoFk
     *
     * @param integer $codigoFacturaTipoFk
     *
     * @return AfiFactura
     */
    public function setCodigoFacturaTipoFk($codigoFacturaTipoFk)
    {
        $this->codigoFacturaTipoFk = $codigoFacturaTipoFk;

        return $this;
    }

    /**
     * Get codigoFacturaTipoFk
     *
     * @return integer
     */
    public function getCodigoFacturaTipoFk()
    {
        return $this->codigoFacturaTipoFk;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return AfiFactura
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return AfiFactura
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set fechaVence
     *
     * @param \DateTime $fechaVence
     *
     * @return AfiFactura
     */
    public function setFechaVence($fechaVence)
    {
        $this->fechaVence = $fechaVence;

        return $this;
    }

    /**
     * Get fechaVence
     *
     * @return \DateTime
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * Set soporte
     *
     * @param string $soporte
     *
     * @return AfiFactura
     */
    public function setSoporte($soporte)
    {
        $this->soporte = $soporte;

        return $this;
    }

    /**
     * Get soporte
     *
     * @return string
     */
    public function getSoporte()
    {
        return $this->soporte;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return AfiFactura
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
     * Set curso
     *
     * @param float $curso
     *
     * @return AfiFactura
     */
    public function setCurso($curso)
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get curso
     *
     * @return float
     */
    public function getCurso()
    {
        return $this->curso;
    }

    /**
     * Set subTotal
     *
     * @param float $subTotal
     *
     * @return AfiFactura
     */
    public function setSubTotal($subTotal)
    {
        $this->subTotal = $subTotal;

        return $this;
    }

    /**
     * Get subTotal
     *
     * @return float
     */
    public function getSubTotal()
    {
        return $this->subTotal;
    }

    /**
     * Set iva
     *
     * @param float $iva
     *
     * @return AfiFactura
     */
    public function setIva($iva)
    {
        $this->iva = $iva;

        return $this;
    }

    /**
     * Get iva
     *
     * @return float
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return AfiFactura
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return AfiFactura
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     *
     * @return AfiFactura
     */
    public function setEstadoAnulado($estadoAnulado)
    {
        $this->estadoAnulado = $estadoAnulado;

        return $this;
    }

    /**
     * Get estadoAnulado
     *
     * @return boolean
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return AfiFactura
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return AfiFactura
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
     * Set afiliacion
     *
     * @param boolean $afiliacion
     *
     * @return AfiFactura
     */
    public function setAfiliacion($afiliacion)
    {
        $this->afiliacion = $afiliacion;

        return $this;
    }

    /**
     * Get afiliacion
     *
     * @return boolean
     */
    public function getAfiliacion()
    {
        return $this->afiliacion;
    }

    /**
     * Set interesMora
     *
     * @param float $interesMora
     *
     * @return AfiFactura
     */
    public function setInteresMora($interesMora)
    {
        $this->interesMora = $interesMora;

        return $this;
    }

    /**
     * Get interesMora
     *
     * @return float
     */
    public function getInteresMora()
    {
        return $this->interesMora;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $clienteRel
     *
     * @return AfiFactura
     */
    public function setClienteRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Set facturaTipoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaTipo $facturaTipoRel
     *
     * @return AfiFactura
     */
    public function setFacturaTipoRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaTipo $facturaTipoRel = null)
    {
        $this->facturaTipoRel = $facturaTipoRel;

        return $this;
    }

    /**
     * Get facturaTipoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiFacturaTipo
     */
    public function getFacturaTipoRel()
    {
        return $this->facturaTipoRel;
    }

    /**
     * Add facturasDetallesFacturaRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle $facturasDetallesFacturaRel
     *
     * @return AfiFactura
     */
    public function addFacturasDetallesFacturaRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle $facturasDetallesFacturaRel)
    {
        $this->facturasDetallesFacturaRel[] = $facturasDetallesFacturaRel;

        return $this;
    }

    /**
     * Remove facturasDetallesFacturaRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle $facturasDetallesFacturaRel
     */
    public function removeFacturasDetallesFacturaRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle $facturasDetallesFacturaRel)
    {
        $this->facturasDetallesFacturaRel->removeElement($facturasDetallesFacturaRel);
    }

    /**
     * Get facturasDetallesFacturaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesFacturaRel()
    {
        return $this->facturasDetallesFacturaRel;
    }

    /**
     * Add facturasDetallesCursosFacturaRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso $facturasDetallesCursosFacturaRel
     *
     * @return AfiFactura
     */
    public function addFacturasDetallesCursosFacturaRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso $facturasDetallesCursosFacturaRel)
    {
        $this->facturasDetallesCursosFacturaRel[] = $facturasDetallesCursosFacturaRel;

        return $this;
    }

    /**
     * Remove facturasDetallesCursosFacturaRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso $facturasDetallesCursosFacturaRel
     */
    public function removeFacturasDetallesCursosFacturaRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso $facturasDetallesCursosFacturaRel)
    {
        $this->facturasDetallesCursosFacturaRel->removeElement($facturasDetallesCursosFacturaRel);
    }

    /**
     * Get facturasDetallesCursosFacturaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesCursosFacturaRel()
    {
        return $this->facturasDetallesCursosFacturaRel;
    }

    /**
     * Add facturasDetallesAfiliacionesContratosRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion $facturasDetallesAfiliacionesContratosRel
     *
     * @return AfiFactura
     */
    public function addFacturasDetallesAfiliacionesContratosRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion $facturasDetallesAfiliacionesContratosRel)
    {
        $this->facturasDetallesAfiliacionesContratosRel[] = $facturasDetallesAfiliacionesContratosRel;

        return $this;
    }

    /**
     * Remove facturasDetallesAfiliacionesContratosRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion $facturasDetallesAfiliacionesContratosRel
     */
    public function removeFacturasDetallesAfiliacionesContratosRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion $facturasDetallesAfiliacionesContratosRel)
    {
        $this->facturasDetallesAfiliacionesContratosRel->removeElement($facturasDetallesAfiliacionesContratosRel);
    }

    /**
     * Get facturasDetallesAfiliacionesContratosRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesAfiliacionesContratosRel()
    {
        return $this->facturasDetallesAfiliacionesContratosRel;
    }

    /**
     * Add facturasDetallesAfiliacionesFacturaRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion $facturasDetallesAfiliacionesFacturaRel
     *
     * @return AfiFactura
     */
    public function addFacturasDetallesAfiliacionesFacturaRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion $facturasDetallesAfiliacionesFacturaRel)
    {
        $this->facturasDetallesAfiliacionesFacturaRel[] = $facturasDetallesAfiliacionesFacturaRel;

        return $this;
    }

    /**
     * Remove facturasDetallesAfiliacionesFacturaRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion $facturasDetallesAfiliacionesFacturaRel
     */
    public function removeFacturasDetallesAfiliacionesFacturaRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleAfiliacion $facturasDetallesAfiliacionesFacturaRel)
    {
        $this->facturasDetallesAfiliacionesFacturaRel->removeElement($facturasDetallesAfiliacionesFacturaRel);
    }

    /**
     * Get facturasDetallesAfiliacionesFacturaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesAfiliacionesFacturaRel()
    {
        return $this->facturasDetallesAfiliacionesFacturaRel;
    }

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return mixed
     */
    public function getValorPorEmpleado()
    {
        return $this->valorPorEmpleado;
    }

    /**
     * @param mixed $valorPorEmpleado
     */
    public function setValorPorEmpleado($valorPorEmpleado)
    {
        $this->valorPorEmpleado = $valorPorEmpleado;
    }


}
