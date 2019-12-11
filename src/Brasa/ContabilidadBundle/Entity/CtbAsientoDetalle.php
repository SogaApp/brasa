<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_asiento_detalle")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbAsientoDetalleRepository")
 */
class CtbAsientoDetalle
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_asiento_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoAsientoDetallePk;
    
    /**
     * @ORM\Column(name="codigo_asiento_fk", type="integer", nullable=false)
     */     
    private $codigoAsientoFk;

    /**
     * @ORM\Column(name="debito", type="float")
     */
    private $debito = 0;    

    /**
     * @ORM\Column(name="credito", type="float")
     */
    private $credito = 0;    

    /**
     * @ORM\Column(name="valor_base", type="float")
     */
    private $valorBase = 0;    
    
    /**    
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=40)
     */ 
    private $codigo_cuenta_fk;
    
    /**    
     * @ORM\Column(name="soporte", type="string", length=40)
     */ 
    private $soporte;
    
    /**    
     * @ORM\Column(name="documento_referente", type="string", length=40)
     */ 
    private $documentoReferente;
    
    /**    
     * @ORM\Column(name="plazo", type="string", length=40)
     */ 
    private $plazo;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */     
    private $codigoCentroCostoFk; 
    
    /**
     * @ORM\Column(name="codigo_asiento_tipo_fk", type="integer", nullable=false)
     */     
    private $codigoAsientoTipoFk;
    
    /**
     * @ORM\Column(name="descripcion", type="string", length=150, nullable=true)
     */    
    private $descripcion; 
    
    
    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;           

     /**
     * @ORM\ManyToOne(targetEntity="CtbAsiento", inversedBy="asientosDetallesAsientoRel")
     * @ORM\JoinColumn(name="codigo_asiento_fk", referencedColumnName="codigo_asiento_pk")
     */
    protected $asientoRel; 

    /**
     * @ORM\ManyToOne(targetEntity="CtbCuenta", inversedBy="asientosDetallesCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    private $cuentaRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbTercero", inversedBy="asientosDetallesTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbAsientoTipo", inversedBy="asientosDetallesAsientoTipoRel")
     * @ORM\JoinColumn(name="codigo_asiento_tipo_fk", referencedColumnName="codigo_asiento_tipo_pk")
     */
    protected $asientoTipoRel;
    
    


    /**
     * Get codigoAsientoDetallePk
     *
     * @return integer
     */
    public function getCodigoAsientoDetallePk()
    {
        return $this->codigoAsientoDetallePk;
    }

    /**
     * Set codigoAsientoFk
     *
     * @param integer $codigoAsientoFk
     *
     * @return CtbAsientoDetalle
     */
    public function setCodigoAsientoFk($codigoAsientoFk)
    {
        $this->codigoAsientoFk = $codigoAsientoFk;

        return $this;
    }

    /**
     * Get codigoAsientoFk
     *
     * @return integer
     */
    public function getCodigoAsientoFk()
    {
        return $this->codigoAsientoFk;
    }

    /**
     * Set debito
     *
     * @param float $debito
     *
     * @return CtbAsientoDetalle
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
     * @return CtbAsientoDetalle
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
     * Set valorBase
     *
     * @param float $valorBase
     *
     * @return CtbAsientoDetalle
     */
    public function setValorBase($valorBase)
    {
        $this->valorBase = $valorBase;

        return $this;
    }

    /**
     * Get valorBase
     *
     * @return float
     */
    public function getValorBase()
    {
        return $this->valorBase;
    }

    /**
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return CtbAsientoDetalle
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigo_cuenta_fk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return string
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigo_cuenta_fk;
    }

    /**
     * Set soporte
     *
     * @param string $soporte
     *
     * @return CtbAsientoDetalle
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
     * Set documentoReferente
     *
     * @param string $documentoReferente
     *
     * @return CtbAsientoDetalle
     */
    public function setDocumentoReferente($documentoReferente)
    {
        $this->documentoReferente = $documentoReferente;

        return $this;
    }

    /**
     * Get documentoReferente
     *
     * @return string
     */
    public function getDocumentoReferente()
    {
        return $this->documentoReferente;
    }

    /**
     * Set plazo
     *
     * @param string $plazo
     *
     * @return CtbAsientoDetalle
     */
    public function setPlazo($plazo)
    {
        $this->plazo = $plazo;

        return $this;
    }

    /**
     * Get plazo
     *
     * @return string
     */
    public function getPlazo()
    {
        return $this->plazo;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return CtbAsientoDetalle
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
     * @param integer $codigoCentroCostoFk
     *
     * @return CtbAsientoDetalle
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return integer
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set codigoAsientoTipoFk
     *
     * @param integer $codigoAsientoTipoFk
     *
     * @return CtbAsientoDetalle
     */
    public function setCodigoAsientoTipoFk($codigoAsientoTipoFk)
    {
        $this->codigoAsientoTipoFk = $codigoAsientoTipoFk;

        return $this;
    }

    /**
     * Get codigoAsientoTipoFk
     *
     * @return integer
     */
    public function getCodigoAsientoTipoFk()
    {
        return $this->codigoAsientoTipoFk;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return CtbAsientoDetalle
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return CtbAsientoDetalle
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
     * Set asientoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsiento $asientoRel
     *
     * @return CtbAsientoDetalle
     */
    public function setAsientoRel(\Brasa\ContabilidadBundle\Entity\CtbAsiento $asientoRel = null)
    {
        $this->asientoRel = $asientoRel;

        return $this;
    }

    /**
     * Get asientoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbAsiento
     */
    public function getAsientoRel()
    {
        return $this->asientoRel;
    }

    /**
     * Set cuentaRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaRel
     *
     * @return CtbAsientoDetalle
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
     * @return CtbAsientoDetalle
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
     * Set asientoTipoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsientoTipo $asientoTipoRel
     *
     * @return CtbAsientoDetalle
     */
    public function setAsientoTipoRel(\Brasa\ContabilidadBundle\Entity\CtbAsientoTipo $asientoTipoRel = null)
    {
        $this->asientoTipoRel = $asientoTipoRel;

        return $this;
    }

    /**
     * Get asientoTipoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbAsientoTipo
     */
    public function getAsientoTipoRel()
    {
        return $this->asientoTipoRel;
    }
}
