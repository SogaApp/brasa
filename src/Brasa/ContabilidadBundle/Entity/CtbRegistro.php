<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_registro")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbRegistroRepository")
 */
class CtbRegistro
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_registro_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoRegistroPk;                      
    
    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="integer", nullable=true)
     */     
    private $codigoComprobanteFk;     
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */    
    private $numero = 0; 
    
    /**
     * @ORM\Column(name="numero_referencia", type="integer", nullable=true)
     */    
    private $numeroReferencia = 0;    
    
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
     * @ORM\Column(name="debito", type="float")
     */
    private $debito = 0;    

    /**
     * @ORM\Column(name="credito", type="float")
     */
    private $credito = 0;    
    
    /**
     * @ORM\Column(name="base", type="float")
     */    
    private $base = 0;                

    /**
     * @ORM\Column(name="descripcion_contable", type="string", length=80, nullable=true)
     */    
    private $descripcionContable;     
    
    /**
     * @ORM\Column(name="exportado", type="boolean")
     */    
    private $exportado = 0;    
              
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbCuenta", inversedBy="registrosCuentasRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;                  
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbCentroCosto", inversedBy="CtbRegistro")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    private $centroCostoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbTercero", inversedBy="CtbRegistro")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbComprobante", inversedBy="CtbRegistro")
     * @ORM\JoinColumn(name="codigo_comprobante_fk", referencedColumnName="codigo_comprobante_pk")
     */
    protected $comprobanteRel;     



    /**
     * Get codigoRegistroPk
     *
     * @return integer
     */
    public function getCodigoRegistroPk()
    {
        return $this->codigoRegistroPk;
    }

    /**
     * Set codigoComprobanteFk
     *
     * @param integer $codigoComprobanteFk
     *
     * @return CtbRegistro
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CtbRegistro
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
     * Set numero
     *
     * @param integer $numero
     *
     * @return CtbRegistro
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
     * Set numeroReferencia
     *
     * @param integer $numeroReferencia
     *
     * @return CtbRegistro
     */
    public function setNumeroReferencia($numeroReferencia)
    {
        $this->numeroReferencia = $numeroReferencia;

        return $this;
    }

    /**
     * Get numeroReferencia
     *
     * @return integer
     */
    public function getNumeroReferencia()
    {
        return $this->numeroReferencia;
    }

    /**
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return CtbRegistro
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
     * @return CtbRegistro
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
     * @return CtbRegistro
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
     * Set debito
     *
     * @param float $debito
     *
     * @return CtbRegistro
     */
    public function setDebito($debito)
    {
        $this->debito = $debito;

        return $this;
    }

    /**
     * Get debito
     *
     * @return float
     */
    public function getDebito()
    {
        return $this->debito;
    }

    /**
     * Set credito
     *
     * @param float $credito
     *
     * @return CtbRegistro
     */
    public function setCredito($credito)
    {
        $this->credito = $credito;

        return $this;
    }

    /**
     * Get credito
     *
     * @return float
     */
    public function getCredito()
    {
        return $this->credito;
    }

    /**
     * Set base
     *
     * @param float $base
     *
     * @return CtbRegistro
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
     * @return CtbRegistro
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
     * Set exportado
     *
     * @param boolean $exportado
     *
     * @return CtbRegistro
     */
    public function setExportado($exportado)
    {
        $this->exportado = $exportado;

        return $this;
    }

    /**
     * Get exportado
     *
     * @return boolean
     */
    public function getExportado()
    {
        return $this->exportado;
    }

    /**
     * Set cuentaRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaRel
     *
     * @return CtbRegistro
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
     * Set terceroRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbTercero $terceroRel
     *
     * @return CtbRegistro
     */
    public function setTerceroRel(\Brasa\ContabilidadBundle\Entity\CtbTercero $terceroRel = null)
    {
        $this->terceroRel = $terceroRel;

        return $this;
    }

    /**
     * Get terceroRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbTercero
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * Set comprobanteRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbComprobante $comprobanteRel
     *
     * @return CtbRegistro
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

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostoRel
     *
     * @return CtbRegistro
     */
    public function setCentroCostoRel(\Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }
}
