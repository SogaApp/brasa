<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_recibo_detalle")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarReciboDetalleRepository")
 */
class CarReciboDetalle
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recibo_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoReciboDetallePk;           
    
    /**
     * @ORM\Column(name="codigo_recibo_fk", type="integer", nullable=true)
     */     
    private $codigoReciboFk;
    
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
     * @ORM\ManyToOne(targetEntity="CarRecibo", inversedBy="recibosDetallesRecibosRel")
     * @ORM\JoinColumn(name="codigo_recibo_fk", referencedColumnName="codigo_recibo_pk")
     */
    protected $reciboRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrar", inversedBy="recibosDetallesCuentaCobrarRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarRel;

    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrarTipo", inversedBy="cuentasCobrarTiposReciboDetalleRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_tipo_fk", referencedColumnName="codigo_cuenta_cobrar_tipo_pk")
     */
    protected $cuentaCobrarTipoRel;
   



    /**
     * Get codigoReciboDetallePk
     *
     * @return integer
     */
    public function getCodigoReciboDetallePk()
    {
        return $this->codigoReciboDetallePk;
    }

    /**
     * Set codigoReciboFk
     *
     * @param integer $codigoReciboFk
     *
     * @return CarReciboDetalle
     */
    public function setCodigoReciboFk($codigoReciboFk)
    {
        $this->codigoReciboFk = $codigoReciboFk;

        return $this;
    }

    /**
     * Get codigoReciboFk
     *
     * @return integer
     */
    public function getCodigoReciboFk()
    {
        return $this->codigoReciboFk;
    }

    /**
     * Set codigoCuentaCobrarFk
     *
     * @param integer $codigoCuentaCobrarFk
     *
     * @return CarReciboDetalle
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
     * @return CarReciboDetalle
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
     * @return CarReciboDetalle
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
     * @return CarReciboDetalle
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
     * @return CarReciboDetalle
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
     * @return CarReciboDetalle
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
     * @return CarReciboDetalle
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
     * @return CarReciboDetalle
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
     * @return CarReciboDetalle
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
     * @return CarReciboDetalle
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
     * @return CarReciboDetalle
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
     * @return CarReciboDetalle
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
     * Set reciboRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $reciboRel
     *
     * @return CarReciboDetalle
     */
    public function setReciboRel(\Brasa\CarteraBundle\Entity\CarRecibo $reciboRel = null)
    {
        $this->reciboRel = $reciboRel;

        return $this;
    }

    /**
     * Get reciboRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarRecibo
     */
    public function getReciboRel()
    {
        return $this->reciboRel;
    }

    /**
     * Set cuentaCobrarRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarRel
     *
     * @return CarReciboDetalle
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
     * @return CarReciboDetalle
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
