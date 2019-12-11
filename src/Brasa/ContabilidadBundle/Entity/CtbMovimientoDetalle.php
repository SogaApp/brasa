<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_movimiento_detalle")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbMovimientoDetalleRepository")
 */
class CtbMovimientoDetalle
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoMovimientoDetallePk;              

    /**
     * @ORM\Column(name="codigo_movimiento_fk", type="integer")
     */     
    private $codigoMovimientoFk;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20)
     */     
    private $codigoCuentaFk;      
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCentroCostoFk;              

    /**
     * @ORM\Column(name="codigo_cuenta_pagar_fk", type="integer", nullable=true)
     */     
    private $codigoCuentaPagarFk;                          
    
    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_fk", type="integer", nullable=true)
     */     
    private $codigoCuentaCobrarFk;     
    
    /**
     * @ORM\Column(name="valor", type="float")
     */    
    private $valor = 0;       
    
    /**
     * @ORM\Column(name="codigo_registro_tipo_fk", type="smallint")
     */    
    private $codigoRegistroTipoFk;    
    
    /**
     * @ORM\Column(name="base", type="float")
     */    
    private $base = 0;                

    /**
     * @ORM\Column(name="descripcion_contable", type="string", length=80, nullable=true)
     */    
    private $descripcionContable;     
    
    /**
     * @ORM\Column(name="codigo_impuesto_iva_fk", type="integer", nullable=true)
     */     
    private $codigoImpuestoIvaFk;    

    /**
     * @ORM\Column(name="codigo_impuesto_retencion_fk", type="integer", nullable=true)
     */     
    private $codigoImpuestoRetencionFk;        

    /**
     * @ORM\Column(name="codigo_impuesto_cree_fk", type="integer", nullable=true)
     * Contribucion empresarial para la equidad
     */     
    private $codigoImpuestoCreeFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbMovimiento", inversedBy="CtbMovimientoDetalle")
     * @ORM\JoinColumn(name="codigo_movimiento_fk", referencedColumnName="codigo_movimiento_pk")
     */
    protected $movimientoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbCuenta", inversedBy="CtbMovimientoDetalle")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;               

    /**
     * @ORM\ManyToOne(targetEntity="CtbImpuesto", inversedBy="CtbMovimientoDetalle")
     * @ORM\JoinColumn(name="codigo_impuesto_iva_fk", referencedColumnName="codigo_impuesto_pk")
     */
    private $impuestoIvaRel;    

    /**
     * @ORM\ManyToOne(targetEntity="CtbImpuesto", inversedBy="CtbMovimientoDetalle")
     * @ORM\JoinColumn(name="codigo_impuesto_retencion_fk", referencedColumnName="codigo_impuesto_pk")
     */
    private $impuestoRetencionRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbImpuesto", inversedBy="CtbMovimientoDetalle")
     * @ORM\JoinColumn(name="codigo_impuesto_cree_fk", referencedColumnName="codigo_impuesto_pk")
     */
    private $impuestoCreeRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\TesoreriaBundle\Entity\TesCuentaPagar", inversedBy="CtbMovimientoDetalle")
     * @ORM\JoinColumn(name="codigo_cuenta_pagar_fk", referencedColumnName="codigo_cuenta_pagar_pk")
     */
    protected $cuentaPagarRel;    

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\CarteraBundle\Entity\CarCuentaCobrar", inversedBy="CtbMovimientoDetalle")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarRel; 

    /**
     * @ORM\ManyToOne(targetEntity="CtbRegistroTipo", inversedBy="CtbMovimientoDetalle")
     * @ORM\JoinColumn(name="codigo_registro_tipo_fk", referencedColumnName="codigo_registro_tipo_pk")
     */
    private $registroTipoRel;    
    
   

    /**
     * Get codigoMovimientoDetallePk
     *
     * @return integer
     */
    public function getCodigoMovimientoDetallePk()
    {
        return $this->codigoMovimientoDetallePk;
    }

    /**
     * Set codigoMovimientoFk
     *
     * @param integer $codigoMovimientoFk
     *
     * @return CtbMovimientoDetalle
     */
    public function setCodigoMovimientoFk($codigoMovimientoFk)
    {
        $this->codigoMovimientoFk = $codigoMovimientoFk;

        return $this;
    }

    /**
     * Get codigoMovimientoFk
     *
     * @return integer
     */
    public function getCodigoMovimientoFk()
    {
        return $this->codigoMovimientoFk;
    }

    /**
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return CtbMovimientoDetalle
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return string
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return CtbMovimientoDetalle
     */
    public function setCodigoTerceroFk($codigoTerceroFk)
    {
        $this->codigoTerceroFk = $codigoTerceroFk;

        return $this;
    }

    /**
     * Get codigoTerceroFk
     *
     * @return integer
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param string $codigoCentroCostoFk
     *
     * @return CtbMovimientoDetalle
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return string
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set codigoCuentaPagarFk
     *
     * @param integer $codigoCuentaPagarFk
     *
     * @return CtbMovimientoDetalle
     */
    public function setCodigoCuentaPagarFk($codigoCuentaPagarFk)
    {
        $this->codigoCuentaPagarFk = $codigoCuentaPagarFk;

        return $this;
    }

    /**
     * Get codigoCuentaPagarFk
     *
     * @return integer
     */
    public function getCodigoCuentaPagarFk()
    {
        return $this->codigoCuentaPagarFk;
    }

    /**
     * Set codigoCuentaCobrarFk
     *
     * @param integer $codigoCuentaCobrarFk
     *
     * @return CtbMovimientoDetalle
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
     * Set valor
     *
     * @param float $valor
     *
     * @return CtbMovimientoDetalle
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
     * Set codigoRegistroTipoFk
     *
     * @param integer $codigoRegistroTipoFk
     *
     * @return CtbMovimientoDetalle
     */
    public function setCodigoRegistroTipoFk($codigoRegistroTipoFk)
    {
        $this->codigoRegistroTipoFk = $codigoRegistroTipoFk;

        return $this;
    }

    /**
     * Get codigoRegistroTipoFk
     *
     * @return integer
     */
    public function getCodigoRegistroTipoFk()
    {
        return $this->codigoRegistroTipoFk;
    }

    /**
     * Set base
     *
     * @param float $base
     *
     * @return CtbMovimientoDetalle
     */
    public function setBase($base)
    {
        $this->base = $base;

        return $this;
    }

    /**
     * Get base
     *
     * @return float
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Set descripcionContable
     *
     * @param string $descripcionContable
     *
     * @return CtbMovimientoDetalle
     */
    public function setDescripcionContable($descripcionContable)
    {
        $this->descripcionContable = $descripcionContable;

        return $this;
    }

    /**
     * Get descripcionContable
     *
     * @return string
     */
    public function getDescripcionContable()
    {
        return $this->descripcionContable;
    }

    /**
     * Set codigoImpuestoIvaFk
     *
     * @param integer $codigoImpuestoIvaFk
     *
     * @return CtbMovimientoDetalle
     */
    public function setCodigoImpuestoIvaFk($codigoImpuestoIvaFk)
    {
        $this->codigoImpuestoIvaFk = $codigoImpuestoIvaFk;

        return $this;
    }

    /**
     * Get codigoImpuestoIvaFk
     *
     * @return integer
     */
    public function getCodigoImpuestoIvaFk()
    {
        return $this->codigoImpuestoIvaFk;
    }

    /**
     * Set codigoImpuestoRetencionFk
     *
     * @param integer $codigoImpuestoRetencionFk
     *
     * @return CtbMovimientoDetalle
     */
    public function setCodigoImpuestoRetencionFk($codigoImpuestoRetencionFk)
    {
        $this->codigoImpuestoRetencionFk = $codigoImpuestoRetencionFk;

        return $this;
    }

    /**
     * Get codigoImpuestoRetencionFk
     *
     * @return integer
     */
    public function getCodigoImpuestoRetencionFk()
    {
        return $this->codigoImpuestoRetencionFk;
    }

    /**
     * Set codigoImpuestoCreeFk
     *
     * @param integer $codigoImpuestoCreeFk
     *
     * @return CtbMovimientoDetalle
     */
    public function setCodigoImpuestoCreeFk($codigoImpuestoCreeFk)
    {
        $this->codigoImpuestoCreeFk = $codigoImpuestoCreeFk;

        return $this;
    }

    /**
     * Get codigoImpuestoCreeFk
     *
     * @return integer
     */
    public function getCodigoImpuestoCreeFk()
    {
        return $this->codigoImpuestoCreeFk;
    }

    /**
     * Set movimientoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbMovimiento $movimientoRel
     *
     * @return CtbMovimientoDetalle
     */
    public function setMovimientoRel(\Brasa\ContabilidadBundle\Entity\CtbMovimiento $movimientoRel = null)
    {
        $this->movimientoRel = $movimientoRel;

        return $this;
    }

    /**
     * Get movimientoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbMovimiento
     */
    public function getMovimientoRel()
    {
        return $this->movimientoRel;
    }

    /**
     * Set cuentaRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaRel
     *
     * @return CtbMovimientoDetalle
     */
    public function setCuentaRel(\Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaRel = null)
    {
        $this->cuentaRel = $cuentaRel;

        return $this;
    }

    /**
     * Get cuentaRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbCuenta
     */
    public function getCuentaRel()
    {
        return $this->cuentaRel;
    }

    /**
     * Set impuestoIvaRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbImpuesto $impuestoIvaRel
     *
     * @return CtbMovimientoDetalle
     */
    public function setImpuestoIvaRel(\Brasa\ContabilidadBundle\Entity\CtbImpuesto $impuestoIvaRel = null)
    {
        $this->impuestoIvaRel = $impuestoIvaRel;

        return $this;
    }

    /**
     * Get impuestoIvaRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbImpuesto
     */
    public function getImpuestoIvaRel()
    {
        return $this->impuestoIvaRel;
    }

    /**
     * Set impuestoRetencionRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbImpuesto $impuestoRetencionRel
     *
     * @return CtbMovimientoDetalle
     */
    public function setImpuestoRetencionRel(\Brasa\ContabilidadBundle\Entity\CtbImpuesto $impuestoRetencionRel = null)
    {
        $this->impuestoRetencionRel = $impuestoRetencionRel;

        return $this;
    }

    /**
     * Get impuestoRetencionRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbImpuesto
     */
    public function getImpuestoRetencionRel()
    {
        return $this->impuestoRetencionRel;
    }

    /**
     * Set impuestoCreeRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbImpuesto $impuestoCreeRel
     *
     * @return CtbMovimientoDetalle
     */
    public function setImpuestoCreeRel(\Brasa\ContabilidadBundle\Entity\CtbImpuesto $impuestoCreeRel = null)
    {
        $this->impuestoCreeRel = $impuestoCreeRel;

        return $this;
    }

    /**
     * Get impuestoCreeRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbImpuesto
     */
    public function getImpuestoCreeRel()
    {
        return $this->impuestoCreeRel;
    }

    /**
     * Set cuentaPagarRel
     *
     * @param \Brasa\TesoreriaBundle\Entity\TesCuentaPagar $cuentaPagarRel
     *
     * @return CtbMovimientoDetalle
     */
    public function setCuentaPagarRel(\Brasa\TesoreriaBundle\Entity\TesCuentaPagar $cuentaPagarRel = null)
    {
        $this->cuentaPagarRel = $cuentaPagarRel;

        return $this;
    }

    /**
     * Get cuentaPagarRel
     *
     * @return \Brasa\TesoreriaBundle\Entity\TesCuentaPagar
     */
    public function getCuentaPagarRel()
    {
        return $this->cuentaPagarRel;
    }

    /**
     * Set cuentaCobrarRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarRel
     *
     * @return CtbMovimientoDetalle
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
     * Set registroTipoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbRegistroTipo $registroTipoRel
     *
     * @return CtbMovimientoDetalle
     */
    public function setRegistroTipoRel(\Brasa\ContabilidadBundle\Entity\CtbRegistroTipo $registroTipoRel = null)
    {
        $this->registroTipoRel = $registroTipoRel;

        return $this;
    }

    /**
     * Get registroTipoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbRegistroTipo
     */
    public function getRegistroTipoRel()
    {
        return $this->registroTipoRel;
    }
}
