<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_factura_detalle")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiFacturaDetalleRepository")
 */
class AfiFacturaDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaDetallePk;    
    
    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer")
     */    
    private $codigoFacturaFk;             

    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer")
     */    
    private $codigoPeriodoFk;
    
    /**
     * @ORM\Column(name="precio", type="float")
     */
    private $precio = 0;             
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;        

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;    
    
    /**
     * @ORM\Column(name="salud", type="float")
     */
    private $salud = 0;
    
    /**
     * @ORM\Column(name="pension", type="float")
     */
    private $pension = 0;           

    /**
     * @ORM\Column(name="caja", type="float")
     */
    private $caja = 0;
    
    /**
     * @ORM\Column(name="riesgos", type="float")
     */
    private $riesgos = 0;
    
    /**
     * @ORM\Column(name="sena", type="float")
     */
    private $sena = 0;    
    
    /**
     * @ORM\Column(name="icbf", type="float")
     */
    private $icbf = 0;      
    
    /**
     * @ORM\Column(name="administracion", type="float")
     */
    private $administracion = 0;    

    /**
     * @ORM\Column(name="subtotal", type="float")
     */
    private $subtotal = 0;
    
    /**
     * @ORM\Column(name="iva", type="float")
     */
    private $iva = 0;    
    
    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0; 

    /**
     * @ORM\Column(name="interes_mora", type="float")
     */
    private $interesMora = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiFactura", inversedBy="facturasDetallesFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;         
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiPeriodo", inversedBy="facturasDetallesPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $periodoRel;    
    

    

    /**
     * Get codigoFacturaDetallePk
     *
     * @return integer
     */
    public function getCodigoFacturaDetallePk()
    {
        return $this->codigoFacturaDetallePk;
    }

    /**
     * Set codigoFacturaFk
     *
     * @param integer $codigoFacturaFk
     *
     * @return AfiFacturaDetalle
     */
    public function setCodigoFacturaFk($codigoFacturaFk)
    {
        $this->codigoFacturaFk = $codigoFacturaFk;

        return $this;
    }

    /**
     * Get codigoFacturaFk
     *
     * @return integer
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * Set codigoPeriodoFk
     *
     * @param integer $codigoPeriodoFk
     *
     * @return AfiFacturaDetalle
     */
    public function setCodigoPeriodoFk($codigoPeriodoFk)
    {
        $this->codigoPeriodoFk = $codigoPeriodoFk;

        return $this;
    }

    /**
     * Get codigoPeriodoFk
     *
     * @return integer
     */
    public function getCodigoPeriodoFk()
    {
        return $this->codigoPeriodoFk;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return AfiFacturaDetalle
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return AfiFacturaDetalle
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return AfiFacturaDetalle
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set salud
     *
     * @param float $salud
     *
     * @return AfiFacturaDetalle
     */
    public function setSalud($salud)
    {
        $this->salud = $salud;

        return $this;
    }

    /**
     * Get salud
     *
     * @return float
     */
    public function getSalud()
    {
        return $this->salud;
    }

    /**
     * Set pension
     *
     * @param float $pension
     *
     * @return AfiFacturaDetalle
     */
    public function setPension($pension)
    {
        $this->pension = $pension;

        return $this;
    }

    /**
     * Get pension
     *
     * @return float
     */
    public function getPension()
    {
        return $this->pension;
    }

    /**
     * Set caja
     *
     * @param float $caja
     *
     * @return AfiFacturaDetalle
     */
    public function setCaja($caja)
    {
        $this->caja = $caja;

        return $this;
    }

    /**
     * Get caja
     *
     * @return float
     */
    public function getCaja()
    {
        return $this->caja;
    }

    /**
     * Set riesgos
     *
     * @param float $riesgos
     *
     * @return AfiFacturaDetalle
     */
    public function setRiesgos($riesgos)
    {
        $this->riesgos = $riesgos;

        return $this;
    }

    /**
     * Get riesgos
     *
     * @return float
     */
    public function getRiesgos()
    {
        return $this->riesgos;
    }

    /**
     * Set sena
     *
     * @param float $sena
     *
     * @return AfiFacturaDetalle
     */
    public function setSena($sena)
    {
        $this->sena = $sena;

        return $this;
    }

    /**
     * Get sena
     *
     * @return float
     */
    public function getSena()
    {
        return $this->sena;
    }

    /**
     * Set icbf
     *
     * @param float $icbf
     *
     * @return AfiFacturaDetalle
     */
    public function setIcbf($icbf)
    {
        $this->icbf = $icbf;

        return $this;
    }

    /**
     * Get icbf
     *
     * @return float
     */
    public function getIcbf()
    {
        return $this->icbf;
    }

    /**
     * Set administracion
     *
     * @param float $administracion
     *
     * @return AfiFacturaDetalle
     */
    public function setAdministracion($administracion)
    {
        $this->administracion = $administracion;

        return $this;
    }

    /**
     * Get administracion
     *
     * @return float
     */
    public function getAdministracion()
    {
        return $this->administracion;
    }

    /**
     * Set subtotal
     *
     * @param float $subtotal
     *
     * @return AfiFacturaDetalle
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
     * Set iva
     *
     * @param float $iva
     *
     * @return AfiFacturaDetalle
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
     * @return AfiFacturaDetalle
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
     * Set interesMora
     *
     * @param float $interesMora
     *
     * @return AfiFacturaDetalle
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
     * Set facturaRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFactura $facturaRel
     *
     * @return AfiFacturaDetalle
     */
    public function setFacturaRel(\Brasa\AfiliacionBundle\Entity\AfiFactura $facturaRel = null)
    {
        $this->facturaRel = $facturaRel;

        return $this;
    }

    /**
     * Get facturaRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiFactura
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * Set periodoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodoRel
     *
     * @return AfiFacturaDetalle
     */
    public function setPeriodoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodoRel = null)
    {
        $this->periodoRel = $periodoRel;

        return $this;
    }

    /**
     * Get periodoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiPeriodo
     */
    public function getPeriodoRel()
    {
        return $this->periodoRel;
    }
}
