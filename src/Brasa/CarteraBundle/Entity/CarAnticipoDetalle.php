<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_anticipo_detalle")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarAnticipoDetalleRepository")
 */
class CarAnticipoDetalle
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_anticipo_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoAnticipoDetallePk;           
    
    /**
     * @ORM\Column(name="codigo_anticipo_fk", type="integer", nullable=true)
     */     
    private $codigoAnticipoFk;
    
    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_fk", type="integer", nullable=true)
     */     
    private $codigoCuentaCobrarFk;
    
    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoCuentaCobrarTipoFk;
    
    /**
     * @ORM\Column(name="numero_factura", type="integer", nullable=true)
     */     
    private $numeroFactura;
    
    /**
     * @ORM\Column(name="valor", type="float")
     */    
    private $valor = 0;
    
    /**
     * @ORM\Column(name="vr_descuento", type="float")
     */    
    private $vrDescuento = 0;
    
    /**
     * @ORM\Column(name="vr_ajuste_peso", type="float")
     */    
    private $vrAjustePeso = 0;
    
    /**
     * @ORM\Column(name="vr_rete_ica", type="float")
     */    
    private $vrReteIca = 0;
    
     /**
     * @ORM\Column(name="vr_rete_iva", type="float")
     */    
    private $vrReteIva = 0;
    
    /**
     * @ORM\Column(name="vr_rete_fuente", type="float")
     */    
    private $vrReteFuente = 0;
    
    /**
     * @ORM\Column(name="vr_pago_detalle", type="float")
     */    
    private $vrPagoDetalle = 0;
    
    /**     
     * @ORM\Column(name="estado_inconsistencia", type="boolean")
     */    
    private $estadoInconsistencia = 0;
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="CarAnticipo", inversedBy="anticiposDetallesAnticiposRel")
     * @ORM\JoinColumn(name="codigo_anticipo_fk", referencedColumnName="codigo_anticipo_pk")
     */
    protected $anticipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrar", inversedBy="anticiposDetallesCuentaCobrarRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarRel;

    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrarTipo", inversedBy="cuentasCobrarTiposAnticipoDetalleRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_tipo_fk", referencedColumnName="codigo_cuenta_cobrar_tipo_pk")
     */
    protected $cuentaCobrarTipoRel;
   


    

    /**
     * Get codigoAnticipoDetallePk
     *
     * @return integer
     */
    public function getCodigoAnticipoDetallePk()
    {
        return $this->codigoAnticipoDetallePk;
    }

    /**
     * Set codigoAnticipoFk
     *
     * @param integer $codigoAnticipoFk
     *
     * @return CarAnticipoDetalle
     */
    public function setCodigoAnticipoFk($codigoAnticipoFk)
    {
        $this->codigoAnticipoFk = $codigoAnticipoFk;

        return $this;
    }

    /**
     * Get codigoAnticipoFk
     *
     * @return integer
     */
    public function getCodigoAnticipoFk()
    {
        return $this->codigoAnticipoFk;
    }

    /**
     * Set codigoCuentaCobrarFk
     *
     * @param integer $codigoCuentaCobrarFk
     *
     * @return CarAnticipoDetalle
     */
    public function setCodigoCuentaCobrarFk($codigoCuentaCobrarFk)
    {
        $this->codigoCuentaCobrarFk = $codigoCuentaCobrarFk;

        return $this;
    }

    /**
     * Get codigoCuentaCobrarFk
     *
     * @return integer
     */
    public function getCodigoCuentaCobrarFk()
    {
        return $this->codigoCuentaCobrarFk;
    }

    /**
     * Set codigoCuentaCobrarTipoFk
     *
     * @param integer $codigoCuentaCobrarTipoFk
     *
     * @return CarAnticipoDetalle
     */
    public function setCodigoCuentaCobrarTipoFk($codigoCuentaCobrarTipoFk)
    {
        $this->codigoCuentaCobrarTipoFk = $codigoCuentaCobrarTipoFk;

        return $this;
    }

    /**
     * Get codigoCuentaCobrarTipoFk
     *
     * @return integer
     */
    public function getCodigoCuentaCobrarTipoFk()
    {
        return $this->codigoCuentaCobrarTipoFk;
    }

    /**
     * Set numeroFactura
     *
     * @param integer $numeroFactura
     *
     * @return CarAnticipoDetalle
     */
    public function setNumeroFactura($numeroFactura)
    {
        $this->numeroFactura = $numeroFactura;

        return $this;
    }

    /**
     * Get numeroFactura
     *
     * @return integer
     */
    public function getNumeroFactura()
    {
        return $this->numeroFactura;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return CarAnticipoDetalle
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set vrDescuento
     *
     * @param float $vrDescuento
     *
     * @return CarAnticipoDetalle
     */
    public function setVrDescuento($vrDescuento)
    {
        $this->vrDescuento = $vrDescuento;

        return $this;
    }

    /**
     * Get vrDescuento
     *
     * @return float
     */
    public function getVrDescuento()
    {
        return $this->vrDescuento;
    }

    /**
     * Set vrAjustePeso
     *
     * @param float $vrAjustePeso
     *
     * @return CarAnticipoDetalle
     */
    public function setVrAjustePeso($vrAjustePeso)
    {
        $this->vrAjustePeso = $vrAjustePeso;

        return $this;
    }

    /**
     * Get vrAjustePeso
     *
     * @return float
     */
    public function getVrAjustePeso()
    {
        return $this->vrAjustePeso;
    }

    /**
     * Set vrReteIca
     *
     * @param float $vrReteIca
     *
     * @return CarAnticipoDetalle
     */
    public function setVrReteIca($vrReteIca)
    {
        $this->vrReteIca = $vrReteIca;

        return $this;
    }

    /**
     * Get vrReteIca
     *
     * @return float
     */
    public function getVrReteIca()
    {
        return $this->vrReteIca;
    }

    /**
     * Set vrReteIva
     *
     * @param float $vrReteIva
     *
     * @return CarAnticipoDetalle
     */
    public function setVrReteIva($vrReteIva)
    {
        $this->vrReteIva = $vrReteIva;

        return $this;
    }

    /**
     * Get vrReteIva
     *
     * @return float
     */
    public function getVrReteIva()
    {
        return $this->vrReteIva;
    }

    /**
     * Set vrReteFuente
     *
     * @param float $vrReteFuente
     *
     * @return CarAnticipoDetalle
     */
    public function setVrReteFuente($vrReteFuente)
    {
        $this->vrReteFuente = $vrReteFuente;

        return $this;
    }

    /**
     * Get vrReteFuente
     *
     * @return float
     */
    public function getVrReteFuente()
    {
        return $this->vrReteFuente;
    }

    /**
     * Set vrPagoDetalle
     *
     * @param float $vrPagoDetalle
     *
     * @return CarAnticipoDetalle
     */
    public function setVrPagoDetalle($vrPagoDetalle)
    {
        $this->vrPagoDetalle = $vrPagoDetalle;

        return $this;
    }

    /**
     * Get vrPagoDetalle
     *
     * @return float
     */
    public function getVrPagoDetalle()
    {
        return $this->vrPagoDetalle;
    }

    /**
     * Set estadoInconsistencia
     *
     * @param boolean $estadoInconsistencia
     *
     * @return CarAnticipoDetalle
     */
    public function setEstadoInconsistencia($estadoInconsistencia)
    {
        $this->estadoInconsistencia = $estadoInconsistencia;

        return $this;
    }

    /**
     * Get estadoInconsistencia
     *
     * @return boolean
     */
    public function getEstadoInconsistencia()
    {
        return $this->estadoInconsistencia;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return CarAnticipoDetalle
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
     * Set anticipoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarAnticipo $anticipoRel
     *
     * @return CarAnticipoDetalle
     */
    public function setAnticipoRel(\Brasa\CarteraBundle\Entity\CarAnticipo $anticipoRel = null)
    {
        $this->anticipoRel = $anticipoRel;

        return $this;
    }

    /**
     * Get anticipoRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarAnticipo
     */
    public function getAnticipoRel()
    {
        return $this->anticipoRel;
    }

    /**
     * Set cuentaCobrarRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarRel
     *
     * @return CarAnticipoDetalle
     */
    public function setCuentaCobrarRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarRel = null)
    {
        $this->cuentaCobrarRel = $cuentaCobrarRel;

        return $this;
    }

    /**
     * Get cuentaCobrarRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarCuentaCobrar
     */
    public function getCuentaCobrarRel()
    {
        return $this->cuentaCobrarRel;
    }

    /**
     * Set cuentaCobrarTipoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrarTipo $cuentaCobrarTipoRel
     *
     * @return CarAnticipoDetalle
     */
    public function setCuentaCobrarTipoRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrarTipo $cuentaCobrarTipoRel = null)
    {
        $this->cuentaCobrarTipoRel = $cuentaCobrarTipoRel;

        return $this;
    }

    /**
     * Get cuentaCobrarTipoRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarCuentaCobrarTipo
     */
    public function getCuentaCobrarTipoRel()
    {
        return $this->cuentaCobrarTipoRel;
    }
}
