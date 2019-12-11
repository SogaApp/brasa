<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_movimiento_concepto")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbMovimientoConceptoRepository")
 */
class CtbMovimientoConcepto
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoMovimientoConceptoPk;        
    
    /**
     * @ORM\Column(name="nombre_movimiento_concepto", type="string", length=40)
     */     
    private $nombreMovimientoConcepto;       
    
    /**
     * @ORM\Column(name="codigo_movimiento_tipo_fk", type="integer")
     */     
    private $codigoMovimientoTipoFk;            

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="integer", nullable=true)
     */     
    private $codigoComprobanteFk;
    
    /**
     * @ORM\Column(name="codigo_cuenta_total_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaTotalFk;     

    /**
     * @ORM\Column(name="codigo_cuenta_bruto_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaBrutoFk;     
    
    /**
     * @ORM\Column(name="tipo_registro", type="smallint")
     */    
    private $tipoRegistro = 1;  

    /**
     * @ORM\Column(name="aplica_cuentas_pagar", type="boolean")
     */    
    private $aplicaCuentasPagar = 0;      

    /**
     * @ORM\Column(name="aplica_cuentas_cobrar", type="boolean")
     */    
    private $aplicaCuentasCobrar = 0;    
    
    /**
     * @ORM\Column(name="afecta_cuentas_pagar", type="boolean")
     */    
    private $afectaCuentasPagar = 0;      

    /**
     * @ORM\Column(name="afecta_cuentas_cobrar", type="boolean")
     */    
    private $afectaCuentasCobrar = 0;          
    
    /**
     * @ORM\Column(name="tipo_registro_total", type="smallint", nullable=true)
     */    
    private $tipoRegistroTotal;    
    
    /**
     * @ORM\Column(name="maneja_impuesto_iva", type="boolean")
     */    
    private $manejaImpuestoIva = 0;     

    /**
     * @ORM\Column(name="maneja_impuesto_retencion_fuente", type="boolean")
     */    
    private $manejaImpuestoRetencionFuente = 0;    
    
    /**
     * @ORM\Column(name="maneja_impuesto_retencion_cree", type="boolean")
     */    
    private $manejaImpuestoRetencionCREE = 0;     

    /**
     * @ORM\Column(name="requiere_tercero", type="boolean")
     */    
    private $requiereTercero = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbMovimientoTipo", inversedBy="CtbMovimientoConcepto")
     * @ORM\JoinColumn(name="codigo_movimiento_tipo_fk", referencedColumnName="codigo_movimiento_tipo_pk")
     */
    protected $movimientoTipoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbCuenta", inversedBy="CtbMovimientoConcepto")
     * @ORM\JoinColumn(name="codigo_cuenta_total_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaTotalRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbCuenta", inversedBy="CtbMovimientoConcepto")
     * @ORM\JoinColumn(name="codigo_cuenta_bruto_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaBrutoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbComprobante", inversedBy="CtbMovimientoConcepto")
     * @ORM\JoinColumn(name="codigo_comprobante_fk", referencedColumnName="codigo_comprobante_pk")
     */
    protected $comprobanteRel; 
    
    /**
     * @ORM\Column(name="consecutivo", type="integer")
     */        
    private $consecutivo = 1;     
    



    /**
     * Get codigoMovimientoConceptoPk
     *
     * @return integer
     */
    public function getCodigoMovimientoConceptoPk()
    {
        return $this->codigoMovimientoConceptoPk;
    }

    /**
     * Set nombreMovimientoConcepto
     *
     * @param string $nombreMovimientoConcepto
     *
     * @return CtbMovimientoConcepto
     */
    public function setNombreMovimientoConcepto($nombreMovimientoConcepto)
    {
        $this->nombreMovimientoConcepto = $nombreMovimientoConcepto;

        return $this;
    }

    /**
     * Get nombreMovimientoConcepto
     *
     * @return string
     */
    public function getNombreMovimientoConcepto()
    {
        return $this->nombreMovimientoConcepto;
    }

    /**
     * Set codigoMovimientoTipoFk
     *
     * @param integer $codigoMovimientoTipoFk
     *
     * @return CtbMovimientoConcepto
     */
    public function setCodigoMovimientoTipoFk($codigoMovimientoTipoFk)
    {
        $this->codigoMovimientoTipoFk = $codigoMovimientoTipoFk;

        return $this;
    }

    /**
     * Get codigoMovimientoTipoFk
     *
     * @return integer
     */
    public function getCodigoMovimientoTipoFk()
    {
        return $this->codigoMovimientoTipoFk;
    }

    /**
     * Set codigoComprobanteFk
     *
     * @param integer $codigoComprobanteFk
     *
     * @return CtbMovimientoConcepto
     */
    public function setCodigoComprobanteFk($codigoComprobanteFk)
    {
        $this->codigoComprobanteFk = $codigoComprobanteFk;

        return $this;
    }

    /**
     * Get codigoComprobanteFk
     *
     * @return integer
     */
    public function getCodigoComprobanteFk()
    {
        return $this->codigoComprobanteFk;
    }

    /**
     * Set codigoCuentaTotalFk
     *
     * @param string $codigoCuentaTotalFk
     *
     * @return CtbMovimientoConcepto
     */
    public function setCodigoCuentaTotalFk($codigoCuentaTotalFk)
    {
        $this->codigoCuentaTotalFk = $codigoCuentaTotalFk;

        return $this;
    }

    /**
     * Get codigoCuentaTotalFk
     *
     * @return string
     */
    public function getCodigoCuentaTotalFk()
    {
        return $this->codigoCuentaTotalFk;
    }

    /**
     * Set codigoCuentaBrutoFk
     *
     * @param string $codigoCuentaBrutoFk
     *
     * @return CtbMovimientoConcepto
     */
    public function setCodigoCuentaBrutoFk($codigoCuentaBrutoFk)
    {
        $this->codigoCuentaBrutoFk = $codigoCuentaBrutoFk;

        return $this;
    }

    /**
     * Get codigoCuentaBrutoFk
     *
     * @return string
     */
    public function getCodigoCuentaBrutoFk()
    {
        return $this->codigoCuentaBrutoFk;
    }

    /**
     * Set tipoRegistro
     *
     * @param integer $tipoRegistro
     *
     * @return CtbMovimientoConcepto
     */
    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = $tipoRegistro;

        return $this;
    }

    /**
     * Get tipoRegistro
     *
     * @return integer
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    /**
     * Set aplicaCuentasPagar
     *
     * @param boolean $aplicaCuentasPagar
     *
     * @return CtbMovimientoConcepto
     */
    public function setAplicaCuentasPagar($aplicaCuentasPagar)
    {
        $this->aplicaCuentasPagar = $aplicaCuentasPagar;

        return $this;
    }

    /**
     * Get aplicaCuentasPagar
     *
     * @return boolean
     */
    public function getAplicaCuentasPagar()
    {
        return $this->aplicaCuentasPagar;
    }

    /**
     * Set aplicaCuentasCobrar
     *
     * @param boolean $aplicaCuentasCobrar
     *
     * @return CtbMovimientoConcepto
     */
    public function setAplicaCuentasCobrar($aplicaCuentasCobrar)
    {
        $this->aplicaCuentasCobrar = $aplicaCuentasCobrar;

        return $this;
    }

    /**
     * Get aplicaCuentasCobrar
     *
     * @return boolean
     */
    public function getAplicaCuentasCobrar()
    {
        return $this->aplicaCuentasCobrar;
    }

    /**
     * Set afectaCuentasPagar
     *
     * @param boolean $afectaCuentasPagar
     *
     * @return CtbMovimientoConcepto
     */
    public function setAfectaCuentasPagar($afectaCuentasPagar)
    {
        $this->afectaCuentasPagar = $afectaCuentasPagar;

        return $this;
    }

    /**
     * Get afectaCuentasPagar
     *
     * @return boolean
     */
    public function getAfectaCuentasPagar()
    {
        return $this->afectaCuentasPagar;
    }

    /**
     * Set afectaCuentasCobrar
     *
     * @param boolean $afectaCuentasCobrar
     *
     * @return CtbMovimientoConcepto
     */
    public function setAfectaCuentasCobrar($afectaCuentasCobrar)
    {
        $this->afectaCuentasCobrar = $afectaCuentasCobrar;

        return $this;
    }

    /**
     * Get afectaCuentasCobrar
     *
     * @return boolean
     */
    public function getAfectaCuentasCobrar()
    {
        return $this->afectaCuentasCobrar;
    }

    /**
     * Set tipoRegistroTotal
     *
     * @param integer $tipoRegistroTotal
     *
     * @return CtbMovimientoConcepto
     */
    public function setTipoRegistroTotal($tipoRegistroTotal)
    {
        $this->tipoRegistroTotal = $tipoRegistroTotal;

        return $this;
    }

    /**
     * Get tipoRegistroTotal
     *
     * @return integer
     */
    public function getTipoRegistroTotal()
    {
        return $this->tipoRegistroTotal;
    }

    /**
     * Set manejaImpuestoIva
     *
     * @param boolean $manejaImpuestoIva
     *
     * @return CtbMovimientoConcepto
     */
    public function setManejaImpuestoIva($manejaImpuestoIva)
    {
        $this->manejaImpuestoIva = $manejaImpuestoIva;

        return $this;
    }

    /**
     * Get manejaImpuestoIva
     *
     * @return boolean
     */
    public function getManejaImpuestoIva()
    {
        return $this->manejaImpuestoIva;
    }

    /**
     * Set manejaImpuestoRetencionFuente
     *
     * @param boolean $manejaImpuestoRetencionFuente
     *
     * @return CtbMovimientoConcepto
     */
    public function setManejaImpuestoRetencionFuente($manejaImpuestoRetencionFuente)
    {
        $this->manejaImpuestoRetencionFuente = $manejaImpuestoRetencionFuente;

        return $this;
    }

    /**
     * Get manejaImpuestoRetencionFuente
     *
     * @return boolean
     */
    public function getManejaImpuestoRetencionFuente()
    {
        return $this->manejaImpuestoRetencionFuente;
    }

    /**
     * Set manejaImpuestoRetencionCREE
     *
     * @param boolean $manejaImpuestoRetencionCREE
     *
     * @return CtbMovimientoConcepto
     */
    public function setManejaImpuestoRetencionCREE($manejaImpuestoRetencionCREE)
    {
        $this->manejaImpuestoRetencionCREE = $manejaImpuestoRetencionCREE;

        return $this;
    }

    /**
     * Get manejaImpuestoRetencionCREE
     *
     * @return boolean
     */
    public function getManejaImpuestoRetencionCREE()
    {
        return $this->manejaImpuestoRetencionCREE;
    }

    /**
     * Set requiereTercero
     *
     * @param boolean $requiereTercero
     *
     * @return CtbMovimientoConcepto
     */
    public function setRequiereTercero($requiereTercero)
    {
        $this->requiereTercero = $requiereTercero;

        return $this;
    }

    /**
     * Get requiereTercero
     *
     * @return boolean
     */
    public function getRequiereTercero()
    {
        return $this->requiereTercero;
    }

    /**
     * Set consecutivo
     *
     * @param integer $consecutivo
     *
     * @return CtbMovimientoConcepto
     */
    public function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;

        return $this;
    }

    /**
     * Get consecutivo
     *
     * @return integer
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * Set movimientoTipoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbMovimientoTipo $movimientoTipoRel
     *
     * @return CtbMovimientoConcepto
     */
    public function setMovimientoTipoRel(\Brasa\ContabilidadBundle\Entity\CtbMovimientoTipo $movimientoTipoRel = null)
    {
        $this->movimientoTipoRel = $movimientoTipoRel;

        return $this;
    }

    /**
     * Get movimientoTipoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbMovimientoTipo
     */
    public function getMovimientoTipoRel()
    {
        return $this->movimientoTipoRel;
    }

    /**
     * Set cuentaTotalRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaTotalRel
     *
     * @return CtbMovimientoConcepto
     */
    public function setCuentaTotalRel(\Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaTotalRel = null)
    {
        $this->cuentaTotalRel = $cuentaTotalRel;

        return $this;
    }

    /**
     * Get cuentaTotalRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbCuenta
     */
    public function getCuentaTotalRel()
    {
        return $this->cuentaTotalRel;
    }

    /**
     * Set cuentaBrutoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaBrutoRel
     *
     * @return CtbMovimientoConcepto
     */
    public function setCuentaBrutoRel(\Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaBrutoRel = null)
    {
        $this->cuentaBrutoRel = $cuentaBrutoRel;

        return $this;
    }

    /**
     * Get cuentaBrutoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbCuenta
     */
    public function getCuentaBrutoRel()
    {
        return $this->cuentaBrutoRel;
    }

    /**
     * Set comprobanteRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbComprobante $comprobanteRel
     *
     * @return CtbMovimientoConcepto
     */
    public function setComprobanteRel(\Brasa\ContabilidadBundle\Entity\CtbComprobante $comprobanteRel = null)
    {
        $this->comprobanteRel = $comprobanteRel;

        return $this;
    }

    /**
     * Get comprobanteRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbComprobante
     */
    public function getComprobanteRel()
    {
        return $this->comprobanteRel;
    }
}
