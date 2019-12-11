<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_movimiento")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbMovimientoRepository")
 */
class CtbMovimiento
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoMovimientoPk;        
    
    /**
     * @ORM\Column(name="codigo_movimiento_tipo_fk", type="integer")
     */     
    private $codigoMovimientoTipoFk;    
    
    /**
     * @ORM\Column(name="codigo_movimiento_concepto_fk", type="integer")
     */     
    private $codigoMovimientoConceptoFk;      
    
    /**
     * @ORM\Column(name="numero_movimiento", type="integer", nullable=true)
     */    
    private $numeroMovimiento = 0;            
    
    /**
     * @ORM\Column(name="soporte", type="string", length=30, nullable=true)
     */    
    private $soporte;     
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;        
        
    /**
     * @ORM\Column(name="fecha_creacion", type="date", nullable=true)
     */    
    private $fecha_creacion;     
    
    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="integer", nullable=true)
     */     
    private $codigoComprobanteFk;     
    
    /**
     * @ORM\Column(name="codigo_banco_fk", type="integer", nullable=true)
     */     
    private $codigoBancoFk;     
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;    
    
    /**
     * @ORM\Column(name="total_debito", type="float")
     */    
    private $totalDebito = 0;
    
    /**
     * @ORM\Column(name="total_credito", type="float")
     */    
    private $totalCredito = 0;      
    
    /**
     * @ORM\Column(name="total_base", type="float")
     */    
    private $totalBase = 0;                

    /**
     * @ORM\Column(name="total_iva", type="float")
     */    
    private $totalIva = 0;    

    /**
     * @ORM\Column(name="total_retencion", type="float")
     */    
    private $totalRetencion = 0;    

    /**
     * @ORM\Column(name="total_retencion_cree", type="float")
     */    
    private $totalRetencionCree = 0;        

    /**
     * @ORM\Column(name="total_bruto", type="float")
     */    
    private $totalBruto = 0;            

    /**
     * @ORM\Column(name="total_neto", type="float")
     */    
    private $totalNeto = 0;    
    
    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;    

    /**
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpreso = 0;    
    
    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = 0;    
    
    /**
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = 0;    
    
    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean")
     */    
    private $estadoContabilizado = 0;       
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbMovimientoTipo", inversedBy="CtbMovimiento")
     * @ORM\JoinColumn(name="codigo_movimiento_tipo_fk", referencedColumnName="codigo_movimiento_tipo_pk")
     */
    protected $movimientoTipoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="CtbMovimientoConcepto", inversedBy="CtbMovimiento")
     * @ORM\JoinColumn(name="codigo_movimiento_concepto_fk", referencedColumnName="codigo_movimiento_concepto_pk")
     */
    protected $movimientoConceptoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbComprobante", inversedBy="CtbMovimiento")
     * @ORM\JoinColumn(name="codigo_comprobante_fk", referencedColumnName="codigo_comprobante_pk")
     */
    protected $comprobanteRel; 

    /**
     * @ORM\ManyToOne(targetEntity="CtbBanco", inversedBy="CtbMovimiento")
     * @ORM\JoinColumn(name="codigo_banco_fk", referencedColumnName="codigo_banco_pk")
     */
    protected $bancoRel;    
    


    /**
     * Get codigoMovimientoPk
     *
     * @return integer
     */
    public function getCodigoMovimientoPk()
    {
        return $this->codigoMovimientoPk;
    }

    /**
     * Set codigoMovimientoTipoFk
     *
     * @param integer $codigoMovimientoTipoFk
     *
     * @return CtbMovimiento
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
     * Set codigoMovimientoConceptoFk
     *
     * @param integer $codigoMovimientoConceptoFk
     *
     * @return CtbMovimiento
     */
    public function setCodigoMovimientoConceptoFk($codigoMovimientoConceptoFk)
    {
        $this->codigoMovimientoConceptoFk = $codigoMovimientoConceptoFk;

        return $this;
    }

    /**
     * Get codigoMovimientoConceptoFk
     *
     * @return integer
     */
    public function getCodigoMovimientoConceptoFk()
    {
        return $this->codigoMovimientoConceptoFk;
    }

    /**
     * Set numeroMovimiento
     *
     * @param integer $numeroMovimiento
     *
     * @return CtbMovimiento
     */
    public function setNumeroMovimiento($numeroMovimiento)
    {
        $this->numeroMovimiento = $numeroMovimiento;

        return $this;
    }

    /**
     * Get numeroMovimiento
     *
     * @return integer
     */
    public function getNumeroMovimiento()
    {
        return $this->numeroMovimiento;
    }

    /**
     * Set soporte
     *
     * @param string $soporte
     *
     * @return CtbMovimiento
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CtbMovimiento
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return CtbMovimiento
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fecha_creacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fecha_creacion;
    }

    /**
     * Set codigoComprobanteFk
     *
     * @param integer $codigoComprobanteFk
     *
     * @return CtbMovimiento
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
     * Set codigoBancoFk
     *
     * @param integer $codigoBancoFk
     *
     * @return CtbMovimiento
     */
    public function setCodigoBancoFk($codigoBancoFk)
    {
        $this->codigoBancoFk = $codigoBancoFk;

        return $this;
    }

    /**
     * Get codigoBancoFk
     *
     * @return integer
     */
    public function getCodigoBancoFk()
    {
        return $this->codigoBancoFk;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return CtbMovimiento
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
     * Set totalDebito
     *
     * @param float $totalDebito
     *
     * @return CtbMovimiento
     */
    public function setTotalDebito($totalDebito)
    {
        $this->totalDebito = $totalDebito;

        return $this;
    }

    /**
     * Get totalDebito
     *
     * @return float
     */
    public function getTotalDebito()
    {
        return $this->totalDebito;
    }

    /**
     * Set totalCredito
     *
     * @param float $totalCredito
     *
     * @return CtbMovimiento
     */
    public function setTotalCredito($totalCredito)
    {
        $this->totalCredito = $totalCredito;

        return $this;
    }

    /**
     * Get totalCredito
     *
     * @return float
     */
    public function getTotalCredito()
    {
        return $this->totalCredito;
    }

    /**
     * Set totalBase
     *
     * @param float $totalBase
     *
     * @return CtbMovimiento
     */
    public function setTotalBase($totalBase)
    {
        $this->totalBase = $totalBase;

        return $this;
    }

    /**
     * Get totalBase
     *
     * @return float
     */
    public function getTotalBase()
    {
        return $this->totalBase;
    }

    /**
     * Set totalIva
     *
     * @param float $totalIva
     *
     * @return CtbMovimiento
     */
    public function setTotalIva($totalIva)
    {
        $this->totalIva = $totalIva;

        return $this;
    }

    /**
     * Get totalIva
     *
     * @return float
     */
    public function getTotalIva()
    {
        return $this->totalIva;
    }

    /**
     * Set totalRetencion
     *
     * @param float $totalRetencion
     *
     * @return CtbMovimiento
     */
    public function setTotalRetencion($totalRetencion)
    {
        $this->totalRetencion = $totalRetencion;

        return $this;
    }

    /**
     * Get totalRetencion
     *
     * @return float
     */
    public function getTotalRetencion()
    {
        return $this->totalRetencion;
    }

    /**
     * Set totalRetencionCree
     *
     * @param float $totalRetencionCree
     *
     * @return CtbMovimiento
     */
    public function setTotalRetencionCree($totalRetencionCree)
    {
        $this->totalRetencionCree = $totalRetencionCree;

        return $this;
    }

    /**
     * Get totalRetencionCree
     *
     * @return float
     */
    public function getTotalRetencionCree()
    {
        return $this->totalRetencionCree;
    }

    /**
     * Set totalBruto
     *
     * @param float $totalBruto
     *
     * @return CtbMovimiento
     */
    public function setTotalBruto($totalBruto)
    {
        $this->totalBruto = $totalBruto;

        return $this;
    }

    /**
     * Get totalBruto
     *
     * @return float
     */
    public function getTotalBruto()
    {
        return $this->totalBruto;
    }

    /**
     * Set totalNeto
     *
     * @param float $totalNeto
     *
     * @return CtbMovimiento
     */
    public function setTotalNeto($totalNeto)
    {
        $this->totalNeto = $totalNeto;

        return $this;
    }

    /**
     * Get totalNeto
     *
     * @return float
     */
    public function getTotalNeto()
    {
        return $this->totalNeto;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return CtbMovimiento
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
     * Set estadoImpreso
     *
     * @param boolean $estadoImpreso
     *
     * @return CtbMovimiento
     */
    public function setEstadoImpreso($estadoImpreso)
    {
        $this->estadoImpreso = $estadoImpreso;

        return $this;
    }

    /**
     * Get estadoImpreso
     *
     * @return boolean
     */
    public function getEstadoImpreso()
    {
        return $this->estadoImpreso;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return CtbMovimiento
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     *
     * @return CtbMovimiento
     */
    public function setEstadoAnulado($estadoAnulado)
    {
        $this->estadoAnulado = $estadoAnulado;

        return $this;
    }

    /**
     * Get estadoAnulado
     *
     * @return boolean
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * Set estadoContabilizado
     *
     * @param boolean $estadoContabilizado
     *
     * @return CtbMovimiento
     */
    public function setEstadoContabilizado($estadoContabilizado)
    {
        $this->estadoContabilizado = $estadoContabilizado;

        return $this;
    }

    /**
     * Get estadoContabilizado
     *
     * @return boolean
     */
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return CtbMovimiento
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set movimientoTipoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbMovimientoTipo $movimientoTipoRel
     *
     * @return CtbMovimiento
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
     * Set movimientoConceptoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbMovimientoConcepto $movimientoConceptoRel
     *
     * @return CtbMovimiento
     */
    public function setMovimientoConceptoRel(\Brasa\ContabilidadBundle\Entity\CtbMovimientoConcepto $movimientoConceptoRel = null)
    {
        $this->movimientoConceptoRel = $movimientoConceptoRel;

        return $this;
    }

    /**
     * Get movimientoConceptoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbMovimientoConcepto
     */
    public function getMovimientoConceptoRel()
    {
        return $this->movimientoConceptoRel;
    }

    /**
     * Set comprobanteRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbComprobante $comprobanteRel
     *
     * @return CtbMovimiento
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
     * Set bancoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbBanco $bancoRel
     *
     * @return CtbMovimiento
     */
    public function setBancoRel(\Brasa\ContabilidadBundle\Entity\CtbBanco $bancoRel = null)
    {
        $this->bancoRel = $bancoRel;

        return $this;
    }

    /**
     * Get bancoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbBanco
     */
    public function getBancoRel()
    {
        return $this->bancoRel;
    }
}
