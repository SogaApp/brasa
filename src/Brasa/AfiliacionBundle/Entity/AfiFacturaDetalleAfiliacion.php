<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_factura_detalle_afiliacion")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiFacturaDetalleAfiliacionRepository")
 */
class AfiFacturaDetalleAfiliacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_detalle_afiliacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaDetalleAfiliacionPk;    
    
    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer")
     */    
    private $codigoFacturaFk;            

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContratoFk; 
    
    /**
     * @ORM\Column(name="precio", type="float")
     */
    private $precio = 0;             
    
    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiFactura", inversedBy="facturasDetallesAfiliacionesFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiContrato", inversedBy="facturasDetallesAfiliacionesContratosRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel; 
    

    

    

    /**
     * Get codigoFacturaDetalleAfiliacionPk
     *
     * @return integer
     */
    public function getCodigoFacturaDetalleAfiliacionPk()
    {
        return $this->codigoFacturaDetalleAfiliacionPk;
    }

    /**
     * Set codigoFacturaFk
     *
     * @param integer $codigoFacturaFk
     *
     * @return AfiFacturaDetalleAfiliacion
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
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return AfiFacturaDetalleAfiliacion
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return AfiFacturaDetalleAfiliacion
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
     * Set total
     *
     * @param float $total
     *
     * @return AfiFacturaDetalleAfiliacion
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
     * Set facturaRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFactura $facturaRel
     *
     * @return AfiFacturaDetalleAfiliacion
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
     * Set contratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $contratoRel
     *
     * @return AfiFacturaDetalleAfiliacion
     */
    public function setContratoRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }
}
