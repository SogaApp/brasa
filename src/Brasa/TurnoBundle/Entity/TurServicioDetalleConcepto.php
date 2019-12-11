<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_servicio_detalle_concepto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurServicioDetalleConceptoRepository")
 */
class TurServicioDetalleConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_servicio_detalle_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoServicioDetalleConceptoPk;  
    
    /**
     * @ORM\Column(name="codigo_servicio_fk", type="integer")
     */    
    private $codigoServicioFk;                   
    
    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;    
    
    /**
     * @ORM\Column(name="codigo_concepto_servicio_fk", type="integer", nullable=true)
     */    
    private $codigoConceptoServicioFk;     
    
    /**
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0;    
    
    /**
     * @ORM\Column(name="precio", type="float")
     */
    private $precio = 0;     
    
    /**
     * @ORM\Column(name="por_iva", type="integer")
     */
    private $porIva = 0;    

    /**
     * @ORM\Column(name="por_base_iva", type="integer")
     */
    private $porBaseIva = 0;     
    
    /**
     * @ORM\Column(name="iva", type="float")
     */
    private $iva = 0;         
    
    /**
     * @ORM\Column(name="subtotal", type="float")
     */
    private $subtotal = 0;    

    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurServicio", inversedBy="serviciosDetallesConceptosServicioRel")
     * @ORM\JoinColumn(name="codigo_servicio_fk", referencedColumnName="codigo_servicio_pk")
     */
    protected $servicioRel;          
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="serviciosDetallesConceptosPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="TurConceptoServicio", inversedBy="serviciosDetallesConceptosConceptoServicioRel")
     * @ORM\JoinColumn(name="codigo_concepto_servicio_fk", referencedColumnName="codigo_concepto_servicio_pk")
     */
    protected $conceptoServicioRel;     
    


    /**
     * Get codigoServicioDetalleConceptoPk
     *
     * @return integer
     */
    public function getCodigoServicioDetalleConceptoPk()
    {
        return $this->codigoServicioDetalleConceptoPk;
    }

    /**
     * Set codigoServicioFk
     *
     * @param integer $codigoServicioFk
     *
     * @return TurServicioDetalleConcepto
     */
    public function setCodigoServicioFk($codigoServicioFk)
    {
        $this->codigoServicioFk = $codigoServicioFk;

        return $this;
    }

    /**
     * Get codigoServicioFk
     *
     * @return integer
     */
    public function getCodigoServicioFk()
    {
        return $this->codigoServicioFk;
    }

    /**
     * Set codigoPuestoFk
     *
     * @param integer $codigoPuestoFk
     *
     * @return TurServicioDetalleConcepto
     */
    public function setCodigoPuestoFk($codigoPuestoFk)
    {
        $this->codigoPuestoFk = $codigoPuestoFk;

        return $this;
    }

    /**
     * Get codigoPuestoFk
     *
     * @return integer
     */
    public function getCodigoPuestoFk()
    {
        return $this->codigoPuestoFk;
    }

    /**
     * Set codigoConceptoServicioFk
     *
     * @param integer $codigoConceptoServicioFk
     *
     * @return TurServicioDetalleConcepto
     */
    public function setCodigoConceptoServicioFk($codigoConceptoServicioFk)
    {
        $this->codigoConceptoServicioFk = $codigoConceptoServicioFk;

        return $this;
    }

    /**
     * Get codigoConceptoServicioFk
     *
     * @return integer
     */
    public function getCodigoConceptoServicioFk()
    {
        return $this->codigoConceptoServicioFk;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     *
     * @return TurServicioDetalleConcepto
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return TurServicioDetalleConcepto
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set porIva
     *
     * @param integer $porIva
     *
     * @return TurServicioDetalleConcepto
     */
    public function setPorIva($porIva)
    {
        $this->porIva = $porIva;

        return $this;
    }

    /**
     * Get porIva
     *
     * @return integer
     */
    public function getPorIva()
    {
        return $this->porIva;
    }

    /**
     * Set porBaseIva
     *
     * @param integer $porBaseIva
     *
     * @return TurServicioDetalleConcepto
     */
    public function setPorBaseIva($porBaseIva)
    {
        $this->porBaseIva = $porBaseIva;

        return $this;
    }

    /**
     * Get porBaseIva
     *
     * @return integer
     */
    public function getPorBaseIva()
    {
        return $this->porBaseIva;
    }

    /**
     * Set iva
     *
     * @param float $iva
     *
     * @return TurServicioDetalleConcepto
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
     * Set subtotal
     *
     * @param float $subtotal
     *
     * @return TurServicioDetalleConcepto
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get subtotal
     *
     * @return float
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return TurServicioDetalleConcepto
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
     * Set servicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicio $servicioRel
     *
     * @return TurServicioDetalleConcepto
     */
    public function setServicioRel(\Brasa\TurnoBundle\Entity\TurServicio $servicioRel = null)
    {
        $this->servicioRel = $servicioRel;

        return $this;
    }

    /**
     * Get servicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurServicio
     */
    public function getServicioRel()
    {
        return $this->servicioRel;
    }

    /**
     * Set puestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestoRel
     *
     * @return TurServicioDetalleConcepto
     */
    public function setPuestoRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestoRel = null)
    {
        $this->puestoRel = $puestoRel;

        return $this;
    }

    /**
     * Get puestoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPuesto
     */
    public function getPuestoRel()
    {
        return $this->puestoRel;
    }

    /**
     * Set conceptoServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurConceptoServicio $conceptoServicioRel
     *
     * @return TurServicioDetalleConcepto
     */
    public function setConceptoServicioRel(\Brasa\TurnoBundle\Entity\TurConceptoServicio $conceptoServicioRel = null)
    {
        $this->conceptoServicioRel = $conceptoServicioRel;

        return $this;
    }

    /**
     * Get conceptoServicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurConceptoServicio
     */
    public function getConceptoServicioRel()
    {
        return $this->conceptoServicioRel;
    }
}
