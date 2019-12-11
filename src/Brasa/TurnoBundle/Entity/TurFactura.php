<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_factura")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurFacturaRepository")
 */
class TurFactura
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaPk;           
    
    /**
     * @ORM\Column(name="codigo_factura_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoFacturaTipoFk;      

    /**
     * @ORM\Column(name="codigo_factura_subtipo_fk", type="integer", nullable=true)
     */    
    private $codigoFacturaSubtipoFk;     
    
    /**
     * @ORM\Column(name="codigo_factura_servicio_fk", type="integer", nullable=true)
     */    
    private $codigoFacturaServicioFk; 
    
    /**
     * @ORM\Column(name="numero", type="integer")
     */    
    private $numero = 0;    
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;        

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */    
    private $fechaVence;    
    
    /**
     * @ORM\Column(name="soporte", type="string", length=30, nullable=true)
     */
    private $soporte;    
    
    /**
     * @ORM\Column(name="descripcion", type="string", length=100, nullable=true)
     */
    private $descripcion;    
    
    /**
     * @ORM\Column(name="titulo_relacion", type="string", length=120, nullable=true)
     */
    private $tituloRelacion;     
    
    /**
     * @ORM\Column(name="detalle_relacion", type="string", length=120, nullable=true)
     */
    private $detalleRelacion;    
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;                   
    
    /**
     * @ORM\Column(name="codigo_cliente_direccion_fk", type="integer", nullable=true)
     */    
    private $codigoClienteDireccionFk;    
    
    /**
     * @ORM\Column(name="codigo_proyecto_fk", type="integer", nullable=true)
     */    
    private $codigoProyectoFk;    
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;   

    /**     
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = false;    

    /**     
     * @ORM\Column(name="estado_contabilizado", type="boolean")
     */    
    private $estadoContabilizado = false;    
    
    /**
     * @ORM\Column(name="vr_subtotal_otros", type="float")
     */
    private $vrSubtotalOtros = 0;    
    
    /**
     * @ORM\Column(name="vr_base_aiu", type="float")
     */
    private $VrBaseAIU = 0;    
    
    /**
     * @ORM\Column(name="vr_base_retencion_fuente", type="float")
     */
    private $VrBaseRetencionFuente = 0;     
    
    /**
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $VrIva = 0; 
    
    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float")
     */
    private $VrRetencionFuente = 0;     
    
    /**
     * @ORM\Column(name="vr_retencion_iva", type="float")
     */
    private $VrRetencionIva = 0;    
    
    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;     
    
    /**
     * @ORM\Column(name="vr_subtotal_operado", type="float")
     */
    private $vrSubtotalOperado = 0;    
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;    
    
    /**
     * @ORM\Column(name="vr_total_neto", type="float")
     */
    private $vrTotalNeto = 0;
    
    /**     
     * @ORM\Column(name="imprimir_relacion", type="boolean")
     */    
    private $imprimirRelacion = false;
    
    /**     
     * @ORM\Column(name="imprimir_agrupada", type="boolean")
     */    
    private $imprimirAgrupada = false;    
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;                  

     /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;    
    
    /**     
     * @ORM\Column(name="afecta_valor_pedido", type="boolean")
     */    
    private $afectaValorPedido = true;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurFacturaTipo", inversedBy="facturasFacturaTipoRel")
     * @ORM\JoinColumn(name="codigo_factura_tipo_fk", referencedColumnName="codigo_factura_tipo_pk")
     */
    protected $facturaTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="TurFacturaSubtipo", inversedBy="facturasFacturaSubtipoRel")
     * @ORM\JoinColumn(name="codigo_factura_subtipo_fk", referencedColumnName="codigo_factura_subtipo_pk")
     */
    protected $facturaSubtipoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurFacturaServicio", inversedBy="facturasFacturaServicioRel")
     * @ORM\JoinColumn(name="codigo_factura_servicio_fk", referencedColumnName="codigo_factura_servicio_pk")
     */
    protected $facturaServicioRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="facturasClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurClienteDireccion", inversedBy="facturasClienteDireccionRel")
     * @ORM\JoinColumn(name="codigo_cliente_direccion_fk", referencedColumnName="codigo_cliente_direccion_pk")
     */
    protected $clienteDireccionRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurProyecto", inversedBy="facturasProyectoRel")
     * @ORM\JoinColumn(name="codigo_proyecto_fk", referencedColumnName="codigo_proyecto_pk")
     */
    protected $proyectoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalle", mappedBy="facturaRel", cascade={"persist", "remove"})
     */
    protected $facturasDetallesFacturaRel;     

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasDetallesFacturaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaPk
     *
     * @return integer
     */
    public function getCodigoFacturaPk()
    {
        return $this->codigoFacturaPk;
    }

    /**
     * Set codigoFacturaTipoFk
     *
     * @param integer $codigoFacturaTipoFk
     *
     * @return TurFactura
     */
    public function setCodigoFacturaTipoFk($codigoFacturaTipoFk)
    {
        $this->codigoFacturaTipoFk = $codigoFacturaTipoFk;

        return $this;
    }

    /**
     * Get codigoFacturaTipoFk
     *
     * @return integer
     */
    public function getCodigoFacturaTipoFk()
    {
        return $this->codigoFacturaTipoFk;
    }

    /**
     * Set codigoFacturaSubtipoFk
     *
     * @param integer $codigoFacturaSubtipoFk
     *
     * @return TurFactura
     */
    public function setCodigoFacturaSubtipoFk($codigoFacturaSubtipoFk)
    {
        $this->codigoFacturaSubtipoFk = $codigoFacturaSubtipoFk;

        return $this;
    }

    /**
     * Get codigoFacturaSubtipoFk
     *
     * @return integer
     */
    public function getCodigoFacturaSubtipoFk()
    {
        return $this->codigoFacturaSubtipoFk;
    }

    /**
     * Set codigoFacturaServicioFk
     *
     * @param integer $codigoFacturaServicioFk
     *
     * @return TurFactura
     */
    public function setCodigoFacturaServicioFk($codigoFacturaServicioFk)
    {
        $this->codigoFacturaServicioFk = $codigoFacturaServicioFk;

        return $this;
    }

    /**
     * Get codigoFacturaServicioFk
     *
     * @return integer
     */
    public function getCodigoFacturaServicioFk()
    {
        return $this->codigoFacturaServicioFk;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return TurFactura
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TurFactura
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
     * Set fechaVence
     *
     * @param \DateTime $fechaVence
     *
     * @return TurFactura
     */
    public function setFechaVence($fechaVence)
    {
        $this->fechaVence = $fechaVence;

        return $this;
    }

    /**
     * Get fechaVence
     *
     * @return \DateTime
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * Set soporte
     *
     * @param string $soporte
     *
     * @return TurFactura
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return TurFactura
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
     * Set tituloRelacion
     *
     * @param string $tituloRelacion
     *
     * @return TurFactura
     */
    public function setTituloRelacion($tituloRelacion)
    {
        $this->tituloRelacion = $tituloRelacion;

        return $this;
    }

    /**
     * Get tituloRelacion
     *
     * @return string
     */
    public function getTituloRelacion()
    {
        return $this->tituloRelacion;
    }

    /**
     * Set detalleRelacion
     *
     * @param string $detalleRelacion
     *
     * @return TurFactura
     */
    public function setDetalleRelacion($detalleRelacion)
    {
        $this->detalleRelacion = $detalleRelacion;

        return $this;
    }

    /**
     * Get detalleRelacion
     *
     * @return string
     */
    public function getDetalleRelacion()
    {
        return $this->detalleRelacion;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurFactura
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set codigoClienteDireccionFk
     *
     * @param integer $codigoClienteDireccionFk
     *
     * @return TurFactura
     */
    public function setCodigoClienteDireccionFk($codigoClienteDireccionFk)
    {
        $this->codigoClienteDireccionFk = $codigoClienteDireccionFk;

        return $this;
    }

    /**
     * Get codigoClienteDireccionFk
     *
     * @return integer
     */
    public function getCodigoClienteDireccionFk()
    {
        return $this->codigoClienteDireccionFk;
    }

    /**
     * Set codigoProyectoFk
     *
     * @param integer $codigoProyectoFk
     *
     * @return TurFactura
     */
    public function setCodigoProyectoFk($codigoProyectoFk)
    {
        $this->codigoProyectoFk = $codigoProyectoFk;

        return $this;
    }

    /**
     * Get codigoProyectoFk
     *
     * @return integer
     */
    public function getCodigoProyectoFk()
    {
        return $this->codigoProyectoFk;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return TurFactura
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
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     *
     * @return TurFactura
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
     * @return TurFactura
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
     * Set vrSubtotalOtros
     *
     * @param float $vrSubtotalOtros
     *
     * @return TurFactura
     */
    public function setVrSubtotalOtros($vrSubtotalOtros)
    {
        $this->vrSubtotalOtros = $vrSubtotalOtros;

        return $this;
    }

    /**
     * Get vrSubtotalOtros
     *
     * @return float
     */
    public function getVrSubtotalOtros()
    {
        return $this->vrSubtotalOtros;
    }

    /**
     * Set vrBaseAIU
     *
     * @param float $vrBaseAIU
     *
     * @return TurFactura
     */
    public function setVrBaseAIU($vrBaseAIU)
    {
        $this->VrBaseAIU = $vrBaseAIU;

        return $this;
    }

    /**
     * Get vrBaseAIU
     *
     * @return float
     */
    public function getVrBaseAIU()
    {
        return $this->VrBaseAIU;
    }

    /**
     * Set vrBaseRetencionFuente
     *
     * @param float $vrBaseRetencionFuente
     *
     * @return TurFactura
     */
    public function setVrBaseRetencionFuente($vrBaseRetencionFuente)
    {
        $this->VrBaseRetencionFuente = $vrBaseRetencionFuente;

        return $this;
    }

    /**
     * Get vrBaseRetencionFuente
     *
     * @return float
     */
    public function getVrBaseRetencionFuente()
    {
        return $this->VrBaseRetencionFuente;
    }

    /**
     * Set vrIva
     *
     * @param float $vrIva
     *
     * @return TurFactura
     */
    public function setVrIva($vrIva)
    {
        $this->VrIva = $vrIva;

        return $this;
    }

    /**
     * Get vrIva
     *
     * @return float
     */
    public function getVrIva()
    {
        return $this->VrIva;
    }

    /**
     * Set vrRetencionFuente
     *
     * @param float $vrRetencionFuente
     *
     * @return TurFactura
     */
    public function setVrRetencionFuente($vrRetencionFuente)
    {
        $this->VrRetencionFuente = $vrRetencionFuente;

        return $this;
    }

    /**
     * Get vrRetencionFuente
     *
     * @return float
     */
    public function getVrRetencionFuente()
    {
        return $this->VrRetencionFuente;
    }

    /**
     * Set vrRetencionIva
     *
     * @param float $vrRetencionIva
     *
     * @return TurFactura
     */
    public function setVrRetencionIva($vrRetencionIva)
    {
        $this->VrRetencionIva = $vrRetencionIva;

        return $this;
    }

    /**
     * Get vrRetencionIva
     *
     * @return float
     */
    public function getVrRetencionIva()
    {
        return $this->VrRetencionIva;
    }

    /**
     * Set vrSubtotal
     *
     * @param float $vrSubtotal
     *
     * @return TurFactura
     */
    public function setVrSubtotal($vrSubtotal)
    {
        $this->vrSubtotal = $vrSubtotal;

        return $this;
    }

    /**
     * Get vrSubtotal
     *
     * @return float
     */
    public function getVrSubtotal()
    {
        return $this->vrSubtotal;
    }

    /**
     * Set vrSubtotalOperado
     *
     * @param float $vrSubtotalOperado
     *
     * @return TurFactura
     */
    public function setVrSubtotalOperado($vrSubtotalOperado)
    {
        $this->vrSubtotalOperado = $vrSubtotalOperado;

        return $this;
    }

    /**
     * Get vrSubtotalOperado
     *
     * @return float
     */
    public function getVrSubtotalOperado()
    {
        return $this->vrSubtotalOperado;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return TurFactura
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * Set vrTotalNeto
     *
     * @param float $vrTotalNeto
     *
     * @return TurFactura
     */
    public function setVrTotalNeto($vrTotalNeto)
    {
        $this->vrTotalNeto = $vrTotalNeto;

        return $this;
    }

    /**
     * Get vrTotalNeto
     *
     * @return float
     */
    public function getVrTotalNeto()
    {
        return $this->vrTotalNeto;
    }

    /**
     * Set imprimirRelacion
     *
     * @param boolean $imprimirRelacion
     *
     * @return TurFactura
     */
    public function setImprimirRelacion($imprimirRelacion)
    {
        $this->imprimirRelacion = $imprimirRelacion;

        return $this;
    }

    /**
     * Get imprimirRelacion
     *
     * @return boolean
     */
    public function getImprimirRelacion()
    {
        return $this->imprimirRelacion;
    }

    /**
     * Set imprimirAgrupada
     *
     * @param boolean $imprimirAgrupada
     *
     * @return TurFactura
     */
    public function setImprimirAgrupada($imprimirAgrupada)
    {
        $this->imprimirAgrupada = $imprimirAgrupada;

        return $this;
    }

    /**
     * Get imprimirAgrupada
     *
     * @return boolean
     */
    public function getImprimirAgrupada()
    {
        return $this->imprimirAgrupada;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurFactura
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurFactura
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
     * Set operacion
     *
     * @param integer $operacion
     *
     * @return TurFactura
     */
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get operacion
     *
     * @return integer
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * Set afectaValorPedido
     *
     * @param boolean $afectaValorPedido
     *
     * @return TurFactura
     */
    public function setAfectaValorPedido($afectaValorPedido)
    {
        $this->afectaValorPedido = $afectaValorPedido;

        return $this;
    }

    /**
     * Get afectaValorPedido
     *
     * @return boolean
     */
    public function getAfectaValorPedido()
    {
        return $this->afectaValorPedido;
    }

    /**
     * Set facturaTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaTipo $facturaTipoRel
     *
     * @return TurFactura
     */
    public function setFacturaTipoRel(\Brasa\TurnoBundle\Entity\TurFacturaTipo $facturaTipoRel = null)
    {
        $this->facturaTipoRel = $facturaTipoRel;

        return $this;
    }

    /**
     * Get facturaTipoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurFacturaTipo
     */
    public function getFacturaTipoRel()
    {
        return $this->facturaTipoRel;
    }

    /**
     * Set facturaSubtipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaSubtipo $facturaSubtipoRel
     *
     * @return TurFactura
     */
    public function setFacturaSubtipoRel(\Brasa\TurnoBundle\Entity\TurFacturaSubtipo $facturaSubtipoRel = null)
    {
        $this->facturaSubtipoRel = $facturaSubtipoRel;

        return $this;
    }

    /**
     * Get facturaSubtipoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurFacturaSubtipo
     */
    public function getFacturaSubtipoRel()
    {
        return $this->facturaSubtipoRel;
    }

    /**
     * Set facturaServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaServicio $facturaServicioRel
     *
     * @return TurFactura
     */
    public function setFacturaServicioRel(\Brasa\TurnoBundle\Entity\TurFacturaServicio $facturaServicioRel = null)
    {
        $this->facturaServicioRel = $facturaServicioRel;

        return $this;
    }

    /**
     * Get facturaServicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurFacturaServicio
     */
    public function getFacturaServicioRel()
    {
        return $this->facturaServicioRel;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurFactura
     */
    public function setClienteRel(\Brasa\TurnoBundle\Entity\TurCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Set clienteDireccionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurClienteDireccion $clienteDireccionRel
     *
     * @return TurFactura
     */
    public function setClienteDireccionRel(\Brasa\TurnoBundle\Entity\TurClienteDireccion $clienteDireccionRel = null)
    {
        $this->clienteDireccionRel = $clienteDireccionRel;

        return $this;
    }

    /**
     * Get clienteDireccionRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurClienteDireccion
     */
    public function getClienteDireccionRel()
    {
        return $this->clienteDireccionRel;
    }

    /**
     * Set proyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProyecto $proyectoRel
     *
     * @return TurFactura
     */
    public function setProyectoRel(\Brasa\TurnoBundle\Entity\TurProyecto $proyectoRel = null)
    {
        $this->proyectoRel = $proyectoRel;

        return $this;
    }

    /**
     * Get proyectoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurProyecto
     */
    public function getProyectoRel()
    {
        return $this->proyectoRel;
    }

    /**
     * Add facturasDetallesFacturaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesFacturaRel
     *
     * @return TurFactura
     */
    public function addFacturasDetallesFacturaRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesFacturaRel)
    {
        $this->facturasDetallesFacturaRel[] = $facturasDetallesFacturaRel;

        return $this;
    }

    /**
     * Remove facturasDetallesFacturaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesFacturaRel
     */
    public function removeFacturasDetallesFacturaRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesFacturaRel)
    {
        $this->facturasDetallesFacturaRel->removeElement($facturasDetallesFacturaRel);
    }

    /**
     * Get facturasDetallesFacturaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesFacturaRel()
    {
        return $this->facturasDetallesFacturaRel;
    }
}
