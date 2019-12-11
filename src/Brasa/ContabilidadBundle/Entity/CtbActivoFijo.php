<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_activo_fijo")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbActivoFijoRepository")
 */
class CtbActivoFijo
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_activo_fijo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoActivoFijoPk;
    
    /**
     * @ORM\Column(name="codigo_activo_fijo_tipo_fk", type="integer", nullable=true)
     */     
    private $codigoActivoFijoTipoFk;     
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;

    /**
     * @ORM\Column(name="ubicacion", type="string", length=60, nullable=true)
     */    
    private $ubicacion;     
    
    /**
     * @ORM\Column(name="fecha_compra", type="date", nullable=true)
     */    
    private $fechaCompra;
    
    /**
     * @ORM\Column(name="costo", type="float")
     */
    private $costo = 0;     
    
    /**
     * @ORM\Column(name="codigo_tercero_compra_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroCompraFk;    
    
    /**
     * @ORM\Column(name="documento_compra", type="string", length=40, nullable=true)
     */    
    private $documentoCompra;      
    
    /**
     * @ORM\Column(name="vida_util", type="integer")
     */    
    private $vidaUtil = 0;    

    /**
     * @ORM\Column(name="vida_util_transcurrida", type="integer")
     */    
    private $vidaUtilTranscurrida = 0;    
    
    /**
     * @ORM\Column(name="tasa_depreciacion", type="integer")
     */    
    private $tasaDepreciacion = 0;    
    
    /**
     * @ORM\Column(name="fecha_venta", type="date", nullable=true)
     */    
    private $fechaVenta;    
    
    /**
     * @ORM\Column(name="valorSalvamento", type="float")
     */
    private $valorSalvamento = 0;    
    
    /**
     * @ORM\Column(name="precio_venta", type="float")
     */
    private $precioVenta = 0;    
    
    /**
     * @ORM\Column(name="marca", type="string", length=80, nullable=true)
     */    
    private $marca;     

    /**
     * @ORM\Column(name="modelo", type="string", length=80, nullable=true)
     */    
    private $modelo;    
    
    /**
     * @ORM\Column(name="serie", type="string", length=80, nullable=true)
     */    
    private $serie; 
    
    /**
     * @ORM\Column(name="placa_activo_fijo", type="string", length=80, nullable=true)
     */    
    private $placaActivoFijo;
    
    /**
     * @ORM\Column(name="numeroPoliza", type="string", length=80, nullable=true)
     */    
    private $numeroPoliza;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios; 
    
    /**
     * @ORM\Column(name="estado_depreciable", type="boolean")
     */    
    private $estadoDepreciable = 0; 
    
    /**
     * @ORM\Column(name="estado_improductivo", type="boolean")
     */    
    private $estadoImproductivo = 0; 
    
    /**
     * @ORM\Column(name="estado_en_transito", type="boolean")
     */    
    private $estadoEnTransito = 0; 
    
    /**
     * @ORM\Column(name="estado_vendido", type="boolean")
     */    
    private $estadoVendido = 0; 
    
    /**
     * @ORM\Column(name="estado_depreciado", type="boolean")
     */    
    private $estadoDepreciado = 0; 
    
    /**
     * @ORM\Column(name="estado_consumido", type="boolean")
     */    
    private $estadoConsumido = 0; 
    
    /**
     * @ORM\Column(name="estado_no_depreciable", type="boolean")
     */    
    private $estadoNoDepreciable = 0;         
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbActivoFijoTipo", inversedBy="CtbActivoFijo")
     * @ORM\JoinColumn(name="codigo_activo_fijo_tipo_fk", referencedColumnName="codigo_activo_fijo_tipo_pk")
     */
    protected $ctbActivoFijoTipoRel;     
    



    /**
     * Get codigoActivoFijoPk
     *
     * @return integer
     */
    public function getCodigoActivoFijoPk()
    {
        return $this->codigoActivoFijoPk;
    }

    /**
     * Set codigoActivoFijoTipoFk
     *
     * @param integer $codigoActivoFijoTipoFk
     *
     * @return CtbActivoFijo
     */
    public function setCodigoActivoFijoTipoFk($codigoActivoFijoTipoFk)
    {
        $this->codigoActivoFijoTipoFk = $codigoActivoFijoTipoFk;

        return $this;
    }

    /**
     * Get codigoActivoFijoTipoFk
     *
     * @return integer
     */
    public function getCodigoActivoFijoTipoFk()
    {
        return $this->codigoActivoFijoTipoFk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CtbActivoFijo
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
     * Set ubicacion
     *
     * @param string $ubicacion
     *
     * @return CtbActivoFijo
     */
    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;

        return $this;
    }

    /**
     * Get ubicacion
     *
     * @return string
     */
    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    /**
     * Set fechaCompra
     *
     * @param \DateTime $fechaCompra
     *
     * @return CtbActivoFijo
     */
    public function setFechaCompra($fechaCompra)
    {
        $this->fechaCompra = $fechaCompra;

        return $this;
    }

    /**
     * Get fechaCompra
     *
     * @return \DateTime
     */
    public function getFechaCompra()
    {
        return $this->fechaCompra;
    }

    /**
     * Set costo
     *
     * @param float $costo
     *
     * @return CtbActivoFijo
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set codigoTerceroCompraFk
     *
     * @param integer $codigoTerceroCompraFk
     *
     * @return CtbActivoFijo
     */
    public function setCodigoTerceroCompraFk($codigoTerceroCompraFk)
    {
        $this->codigoTerceroCompraFk = $codigoTerceroCompraFk;

        return $this;
    }

    /**
     * Get codigoTerceroCompraFk
     *
     * @return integer
     */
    public function getCodigoTerceroCompraFk()
    {
        return $this->codigoTerceroCompraFk;
    }

    /**
     * Set documentoCompra
     *
     * @param string $documentoCompra
     *
     * @return CtbActivoFijo
     */
    public function setDocumentoCompra($documentoCompra)
    {
        $this->documentoCompra = $documentoCompra;

        return $this;
    }

    /**
     * Get documentoCompra
     *
     * @return string
     */
    public function getDocumentoCompra()
    {
        return $this->documentoCompra;
    }

    /**
     * Set vidaUtil
     *
     * @param integer $vidaUtil
     *
     * @return CtbActivoFijo
     */
    public function setVidaUtil($vidaUtil)
    {
        $this->vidaUtil = $vidaUtil;

        return $this;
    }

    /**
     * Get vidaUtil
     *
     * @return integer
     */
    public function getVidaUtil()
    {
        return $this->vidaUtil;
    }

    /**
     * Set vidaUtilTranscurrida
     *
     * @param integer $vidaUtilTranscurrida
     *
     * @return CtbActivoFijo
     */
    public function setVidaUtilTranscurrida($vidaUtilTranscurrida)
    {
        $this->vidaUtilTranscurrida = $vidaUtilTranscurrida;

        return $this;
    }

    /**
     * Get vidaUtilTranscurrida
     *
     * @return integer
     */
    public function getVidaUtilTranscurrida()
    {
        return $this->vidaUtilTranscurrida;
    }

    /**
     * Set tasaDepreciacion
     *
     * @param integer $tasaDepreciacion
     *
     * @return CtbActivoFijo
     */
    public function setTasaDepreciacion($tasaDepreciacion)
    {
        $this->tasaDepreciacion = $tasaDepreciacion;

        return $this;
    }

    /**
     * Get tasaDepreciacion
     *
     * @return integer
     */
    public function getTasaDepreciacion()
    {
        return $this->tasaDepreciacion;
    }

    /**
     * Set fechaVenta
     *
     * @param \DateTime $fechaVenta
     *
     * @return CtbActivoFijo
     */
    public function setFechaVenta($fechaVenta)
    {
        $this->fechaVenta = $fechaVenta;

        return $this;
    }

    /**
     * Get fechaVenta
     *
     * @return \DateTime
     */
    public function getFechaVenta()
    {
        return $this->fechaVenta;
    }

    /**
     * Set valorSalvamento
     *
     * @param float $valorSalvamento
     *
     * @return CtbActivoFijo
     */
    public function setValorSalvamento($valorSalvamento)
    {
        $this->valorSalvamento = $valorSalvamento;

        return $this;
    }

    /**
     * Get valorSalvamento
     *
     * @return float
     */
    public function getValorSalvamento()
    {
        return $this->valorSalvamento;
    }

    /**
     * Set precioVenta
     *
     * @param float $precioVenta
     *
     * @return CtbActivoFijo
     */
    public function setPrecioVenta($precioVenta)
    {
        $this->precioVenta = $precioVenta;

        return $this;
    }

    /**
     * Get precioVenta
     *
     * @return float
     */
    public function getPrecioVenta()
    {
        return $this->precioVenta;
    }

    /**
     * Set marca
     *
     * @param string $marca
     *
     * @return CtbActivoFijo
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * Get marca
     *
     * @return string
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * Set modelo
     *
     * @param string $modelo
     *
     * @return CtbActivoFijo
     */
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;

        return $this;
    }

    /**
     * Get modelo
     *
     * @return string
     */
    public function getModelo()
    {
        return $this->modelo;
    }

    /**
     * Set serie
     *
     * @param string $serie
     *
     * @return CtbActivoFijo
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie
     *
     * @return string
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set placaActivoFijo
     *
     * @param string $placaActivoFijo
     *
     * @return CtbActivoFijo
     */
    public function setPlacaActivoFijo($placaActivoFijo)
    {
        $this->placaActivoFijo = $placaActivoFijo;

        return $this;
    }

    /**
     * Get placaActivoFijo
     *
     * @return string
     */
    public function getPlacaActivoFijo()
    {
        return $this->placaActivoFijo;
    }

    /**
     * Set numeroPoliza
     *
     * @param string $numeroPoliza
     *
     * @return CtbActivoFijo
     */
    public function setNumeroPoliza($numeroPoliza)
    {
        $this->numeroPoliza = $numeroPoliza;

        return $this;
    }

    /**
     * Get numeroPoliza
     *
     * @return string
     */
    public function getNumeroPoliza()
    {
        return $this->numeroPoliza;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return CtbActivoFijo
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
     * Set estadoDepreciable
     *
     * @param boolean $estadoDepreciable
     *
     * @return CtbActivoFijo
     */
    public function setEstadoDepreciable($estadoDepreciable)
    {
        $this->estadoDepreciable = $estadoDepreciable;

        return $this;
    }

    /**
     * Get estadoDepreciable
     *
     * @return boolean
     */
    public function getEstadoDepreciable()
    {
        return $this->estadoDepreciable;
    }

    /**
     * Set estadoImproductivo
     *
     * @param boolean $estadoImproductivo
     *
     * @return CtbActivoFijo
     */
    public function setEstadoImproductivo($estadoImproductivo)
    {
        $this->estadoImproductivo = $estadoImproductivo;

        return $this;
    }

    /**
     * Get estadoImproductivo
     *
     * @return boolean
     */
    public function getEstadoImproductivo()
    {
        return $this->estadoImproductivo;
    }

    /**
     * Set estadoEnTransito
     *
     * @param boolean $estadoEnTransito
     *
     * @return CtbActivoFijo
     */
    public function setEstadoEnTransito($estadoEnTransito)
    {
        $this->estadoEnTransito = $estadoEnTransito;

        return $this;
    }

    /**
     * Get estadoEnTransito
     *
     * @return boolean
     */
    public function getEstadoEnTransito()
    {
        return $this->estadoEnTransito;
    }

    /**
     * Set estadoVendido
     *
     * @param boolean $estadoVendido
     *
     * @return CtbActivoFijo
     */
    public function setEstadoVendido($estadoVendido)
    {
        $this->estadoVendido = $estadoVendido;

        return $this;
    }

    /**
     * Get estadoVendido
     *
     * @return boolean
     */
    public function getEstadoVendido()
    {
        return $this->estadoVendido;
    }

    /**
     * Set estadoDepreciado
     *
     * @param boolean $estadoDepreciado
     *
     * @return CtbActivoFijo
     */
    public function setEstadoDepreciado($estadoDepreciado)
    {
        $this->estadoDepreciado = $estadoDepreciado;

        return $this;
    }

    /**
     * Get estadoDepreciado
     *
     * @return boolean
     */
    public function getEstadoDepreciado()
    {
        return $this->estadoDepreciado;
    }

    /**
     * Set estadoConsumido
     *
     * @param boolean $estadoConsumido
     *
     * @return CtbActivoFijo
     */
    public function setEstadoConsumido($estadoConsumido)
    {
        $this->estadoConsumido = $estadoConsumido;

        return $this;
    }

    /**
     * Get estadoConsumido
     *
     * @return boolean
     */
    public function getEstadoConsumido()
    {
        return $this->estadoConsumido;
    }

    /**
     * Set estadoNoDepreciable
     *
     * @param boolean $estadoNoDepreciable
     *
     * @return CtbActivoFijo
     */
    public function setEstadoNoDepreciable($estadoNoDepreciable)
    {
        $this->estadoNoDepreciable = $estadoNoDepreciable;

        return $this;
    }

    /**
     * Get estadoNoDepreciable
     *
     * @return boolean
     */
    public function getEstadoNoDepreciable()
    {
        return $this->estadoNoDepreciable;
    }

    /**
     * Set ctbActivoFijoTipoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbActivoFijoTipo $ctbActivoFijoTipoRel
     *
     * @return CtbActivoFijo
     */
    public function setCtbActivoFijoTipoRel(\Brasa\ContabilidadBundle\Entity\CtbActivoFijoTipo $ctbActivoFijoTipoRel = null)
    {
        $this->ctbActivoFijoTipoRel = $ctbActivoFijoTipoRel;

        return $this;
    }

    /**
     * Get ctbActivoFijoTipoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbActivoFijoTipo
     */
    public function getCtbActivoFijoTipoRel()
    {
        return $this->ctbActivoFijoTipoRel;
    }
}
