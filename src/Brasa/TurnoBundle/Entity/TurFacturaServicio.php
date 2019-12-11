<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_factura_servicio")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurFacturaServicioRepository")
 */
class TurFacturaServicio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_servicio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaServicioPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                                                    
    
    /**
     * @ORM\Column(name="por_base_retencion_fuente", type="float")
     */
    private $porBaseRetencionFuente = 0;    
    
    /**
     * @ORM\Column(name="por_retencion_fuente", type="float")
     */
    private $porRetencionFuente = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_cartera_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaCarteraFk;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_retencion_fuente_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaRetencionFuenteFk;  
    
    /**
     * @ORM\Column(name="codigo_cuenta_iva_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaIvaFk;            
    
    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaIngresoFk;           
    
    /**
     * @ORM\Column(name="codigo_cuenta_iva_devolucion_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaIvaDevolucionFk;          
    
    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_devolucion_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaIngresoDevolucionFk;        
        
    
    /**
     * @ORM\OneToMany(targetEntity="TurFactura", mappedBy="facturaServicioRel")
     */
    protected $facturasFacturaServicioRel;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasFacturaServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaServicioPk
     *
     * @return integer
     */
    public function getCodigoFacturaServicioPk()
    {
        return $this->codigoFacturaServicioPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurFacturaServicio
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
     * Set porBaseRetencionFuente
     *
     * @param float $porBaseRetencionFuente
     *
     * @return TurFacturaServicio
     */
    public function setPorBaseRetencionFuente($porBaseRetencionFuente)
    {
        $this->porBaseRetencionFuente = $porBaseRetencionFuente;

        return $this;
    }

    /**
     * Get porBaseRetencionFuente
     *
     * @return float
     */
    public function getPorBaseRetencionFuente()
    {
        return $this->porBaseRetencionFuente;
    }

    /**
     * Set porRetencionFuente
     *
     * @param float $porRetencionFuente
     *
     * @return TurFacturaServicio
     */
    public function setPorRetencionFuente($porRetencionFuente)
    {
        $this->porRetencionFuente = $porRetencionFuente;

        return $this;
    }

    /**
     * Get porRetencionFuente
     *
     * @return float
     */
    public function getPorRetencionFuente()
    {
        return $this->porRetencionFuente;
    }

    /**
     * Set codigoCuentaCarteraFk
     *
     * @param string $codigoCuentaCarteraFk
     *
     * @return TurFacturaServicio
     */
    public function setCodigoCuentaCarteraFk($codigoCuentaCarteraFk)
    {
        $this->codigoCuentaCarteraFk = $codigoCuentaCarteraFk;

        return $this;
    }

    /**
     * Get codigoCuentaCarteraFk
     *
     * @return string
     */
    public function getCodigoCuentaCarteraFk()
    {
        return $this->codigoCuentaCarteraFk;
    }

    /**
     * Set codigoCuentaRetencionFuenteFk
     *
     * @param string $codigoCuentaRetencionFuenteFk
     *
     * @return TurFacturaServicio
     */
    public function setCodigoCuentaRetencionFuenteFk($codigoCuentaRetencionFuenteFk)
    {
        $this->codigoCuentaRetencionFuenteFk = $codigoCuentaRetencionFuenteFk;

        return $this;
    }

    /**
     * Get codigoCuentaRetencionFuenteFk
     *
     * @return string
     */
    public function getCodigoCuentaRetencionFuenteFk()
    {
        return $this->codigoCuentaRetencionFuenteFk;
    }

    /**
     * Set codigoCuentaIvaFk
     *
     * @param string $codigoCuentaIvaFk
     *
     * @return TurFacturaServicio
     */
    public function setCodigoCuentaIvaFk($codigoCuentaIvaFk)
    {
        $this->codigoCuentaIvaFk = $codigoCuentaIvaFk;

        return $this;
    }

    /**
     * Get codigoCuentaIvaFk
     *
     * @return string
     */
    public function getCodigoCuentaIvaFk()
    {
        return $this->codigoCuentaIvaFk;
    }

    /**
     * Set codigoCuentaIngresoFk
     *
     * @param string $codigoCuentaIngresoFk
     *
     * @return TurFacturaServicio
     */
    public function setCodigoCuentaIngresoFk($codigoCuentaIngresoFk)
    {
        $this->codigoCuentaIngresoFk = $codigoCuentaIngresoFk;

        return $this;
    }

    /**
     * Get codigoCuentaIngresoFk
     *
     * @return string
     */
    public function getCodigoCuentaIngresoFk()
    {
        return $this->codigoCuentaIngresoFk;
    }

    /**
     * Set codigoCuentaIvaDevolucionFk
     *
     * @param string $codigoCuentaIvaDevolucionFk
     *
     * @return TurFacturaServicio
     */
    public function setCodigoCuentaIvaDevolucionFk($codigoCuentaIvaDevolucionFk)
    {
        $this->codigoCuentaIvaDevolucionFk = $codigoCuentaIvaDevolucionFk;

        return $this;
    }

    /**
     * Get codigoCuentaIvaDevolucionFk
     *
     * @return string
     */
    public function getCodigoCuentaIvaDevolucionFk()
    {
        return $this->codigoCuentaIvaDevolucionFk;
    }

    /**
     * Set codigoCuentaIngresoDevolucionFk
     *
     * @param string $codigoCuentaIngresoDevolucionFk
     *
     * @return TurFacturaServicio
     */
    public function setCodigoCuentaIngresoDevolucionFk($codigoCuentaIngresoDevolucionFk)
    {
        $this->codigoCuentaIngresoDevolucionFk = $codigoCuentaIngresoDevolucionFk;

        return $this;
    }

    /**
     * Get codigoCuentaIngresoDevolucionFk
     *
     * @return string
     */
    public function getCodigoCuentaIngresoDevolucionFk()
    {
        return $this->codigoCuentaIngresoDevolucionFk;
    }

    /**
     * Add facturasFacturaServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaServicioRel
     *
     * @return TurFacturaServicio
     */
    public function addFacturasFacturaServicioRel(\Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaServicioRel)
    {
        $this->facturasFacturaServicioRel[] = $facturasFacturaServicioRel;

        return $this;
    }

    /**
     * Remove facturasFacturaServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaServicioRel
     */
    public function removeFacturasFacturaServicioRel(\Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaServicioRel)
    {
        $this->facturasFacturaServicioRel->removeElement($facturasFacturaServicioRel);
    }

    /**
     * Get facturasFacturaServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasFacturaServicioRel()
    {
        return $this->facturasFacturaServicioRel;
    }
}
