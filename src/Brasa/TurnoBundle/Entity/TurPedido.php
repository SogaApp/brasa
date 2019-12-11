<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_pedido")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPedidoRepository")
 */
class TurPedido
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPedidoPk;           
    
    /**
     * @ORM\Column(name="numero", type="integer")
     */    
    private $numero = 0;     
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="codigo_pedido_tipo_fk", type="integer")
     */    
    private $codigoPedidoTipoFk;
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;    
    
    /**
     * @ORM\Column(name="codigo_sector_fk", type="integer", nullable=true)
     */    
    private $codigoSectorFk;               

    /**
     * @ORM\Column(name="fecha_programacion", type="date", nullable=true)
     */    
    private $fechaProgramacion;              
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;     
    
    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = false;         
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    

    /**     
     * @ORM\Column(name="estado_programado", type="boolean")
     */    
    private $estadoProgramado = false;     

    /**     
     * @ORM\Column(name="estado_facturado", type="boolean")
     */    
    private $estadoFacturado = false;    
    
    /**     
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = false;     
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;    
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="horas_diurnas", type="integer")
     */    
    private $horasDiurnas = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas", type="integer")
     */    
    private $horasNocturnas = 0;    
    
    /**
     * @ORM\Column(name="vr_total_costo", type="float")
     */
    private $vrTotalCosto = 0;
    
    /**
     * @ORM\Column(name="vr_total_precio_ajustado", type="float")
     */
    private $vrTotalPrecioAjustado = 0;            

    /**
     * @ORM\Column(name="vr_total_precio_minimo", type="float")
     */
    private $vrTotalPrecioMinimo = 0;        
    
    /**
     * @ORM\Column(name="vr_total_otros", type="float")
     */
    private $vrTotalOtros = 0;    
    
    /**
     * @ORM\Column(name="vr_total_servicio", type="float")
     */
    private $vrTotalServicio = 0; 
    

    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0; 

    /**
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $vrIva = 0;    
    
    /**
     * @ORM\Column(name="vr_base_aiu", type="float")
     */
    private $vrBaseAiu = 0;     
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;   
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;         
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoTipo", inversedBy="pedidosPedidoTipoRel")
     * @ORM\JoinColumn(name="codigo_pedido_tipo_fk", referencedColumnName="codigo_pedido_tipo_pk")
     */
    protected $pedidoTipoRel;           
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="pedidosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurSector", inversedBy="pedidosSectorRel")
     * @ORM\JoinColumn(name="codigo_sector_fk", referencedColumnName="codigo_sector_pk")
     */
    protected $sectorRel;         
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="pedidoRel", cascade={"persist", "remove"})
     */
    protected $pedidosDetallesPedidoRel; 

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleConcepto", mappedBy="pedidoRel", cascade={"persist", "remove"})
     */
    protected $pedidosDetallesConceptosPedidoRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosDetallesPedidoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPedidoPk
     *
     * @return integer
     */
    public function getCodigoPedidoPk()
    {
        return $this->codigoPedidoPk;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return TurPedido
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
     * @return TurPedido
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
     * Set codigoPedidoTipoFk
     *
     * @param integer $codigoPedidoTipoFk
     *
     * @return TurPedido
     */
    public function setCodigoPedidoTipoFk($codigoPedidoTipoFk)
    {
        $this->codigoPedidoTipoFk = $codigoPedidoTipoFk;

        return $this;
    }

    /**
     * Get codigoPedidoTipoFk
     *
     * @return integer
     */
    public function getCodigoPedidoTipoFk()
    {
        return $this->codigoPedidoTipoFk;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurPedido
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
     * Set codigoSectorFk
     *
     * @param integer $codigoSectorFk
     *
     * @return TurPedido
     */
    public function setCodigoSectorFk($codigoSectorFk)
    {
        $this->codigoSectorFk = $codigoSectorFk;

        return $this;
    }

    /**
     * Get codigoSectorFk
     *
     * @return integer
     */
    public function getCodigoSectorFk()
    {
        return $this->codigoSectorFk;
    }

    /**
     * Set fechaProgramacion
     *
     * @param \DateTime $fechaProgramacion
     *
     * @return TurPedido
     */
    public function setFechaProgramacion($fechaProgramacion)
    {
        $this->fechaProgramacion = $fechaProgramacion;

        return $this;
    }

    /**
     * Get fechaProgramacion
     *
     * @return \DateTime
     */
    public function getFechaProgramacion()
    {
        return $this->fechaProgramacion;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return TurPedido
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
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return TurPedido
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return TurPedido
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
     * Set estadoProgramado
     *
     * @param boolean $estadoProgramado
     *
     * @return TurPedido
     */
    public function setEstadoProgramado($estadoProgramado)
    {
        $this->estadoProgramado = $estadoProgramado;

        return $this;
    }

    /**
     * Get estadoProgramado
     *
     * @return boolean
     */
    public function getEstadoProgramado()
    {
        return $this->estadoProgramado;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return TurPedido
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return TurPedido
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return integer
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set horasDiurnas
     *
     * @param integer $horasDiurnas
     *
     * @return TurPedido
     */
    public function setHorasDiurnas($horasDiurnas)
    {
        $this->horasDiurnas = $horasDiurnas;

        return $this;
    }

    /**
     * Get horasDiurnas
     *
     * @return integer
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * Set horasNocturnas
     *
     * @param integer $horasNocturnas
     *
     * @return TurPedido
     */
    public function setHorasNocturnas($horasNocturnas)
    {
        $this->horasNocturnas = $horasNocturnas;

        return $this;
    }

    /**
     * Get horasNocturnas
     *
     * @return integer
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * Set vrTotalCosto
     *
     * @param float $vrTotalCosto
     *
     * @return TurPedido
     */
    public function setVrTotalCosto($vrTotalCosto)
    {
        $this->vrTotalCosto = $vrTotalCosto;

        return $this;
    }

    /**
     * Get vrTotalCosto
     *
     * @return float
     */
    public function getVrTotalCosto()
    {
        return $this->vrTotalCosto;
    }

    /**
     * Set vrTotalPrecioAjustado
     *
     * @param float $vrTotalPrecioAjustado
     *
     * @return TurPedido
     */
    public function setVrTotalPrecioAjustado($vrTotalPrecioAjustado)
    {
        $this->vrTotalPrecioAjustado = $vrTotalPrecioAjustado;

        return $this;
    }

    /**
     * Get vrTotalPrecioAjustado
     *
     * @return float
     */
    public function getVrTotalPrecioAjustado()
    {
        return $this->vrTotalPrecioAjustado;
    }

    /**
     * Set vrTotalPrecioMinimo
     *
     * @param float $vrTotalPrecioMinimo
     *
     * @return TurPedido
     */
    public function setVrTotalPrecioMinimo($vrTotalPrecioMinimo)
    {
        $this->vrTotalPrecioMinimo = $vrTotalPrecioMinimo;

        return $this;
    }

    /**
     * Get vrTotalPrecioMinimo
     *
     * @return float
     */
    public function getVrTotalPrecioMinimo()
    {
        return $this->vrTotalPrecioMinimo;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return TurPedido
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurPedido
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
     * Set pedidoTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoTipo $pedidoTipoRel
     *
     * @return TurPedido
     */
    public function setPedidoTipoRel(\Brasa\TurnoBundle\Entity\TurPedidoTipo $pedidoTipoRel = null)
    {
        $this->pedidoTipoRel = $pedidoTipoRel;

        return $this;
    }

    /**
     * Get pedidoTipoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPedidoTipo
     */
    public function getPedidoTipoRel()
    {
        return $this->pedidoTipoRel;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurPedido
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
     * Set sectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSector $sectorRel
     *
     * @return TurPedido
     */
    public function setSectorRel(\Brasa\TurnoBundle\Entity\TurSector $sectorRel = null)
    {
        $this->sectorRel = $sectorRel;

        return $this;
    }

    /**
     * Get sectorRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurSector
     */
    public function getSectorRel()
    {
        return $this->sectorRel;
    }

    /**
     * Add pedidosDetallesPedidoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPedidoRel
     *
     * @return TurPedido
     */
    public function addPedidosDetallesPedidoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPedidoRel)
    {
        $this->pedidosDetallesPedidoRel[] = $pedidosDetallesPedidoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesPedidoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPedidoRel
     */
    public function removePedidosDetallesPedidoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPedidoRel)
    {
        $this->pedidosDetallesPedidoRel->removeElement($pedidosDetallesPedidoRel);
    }

    /**
     * Get pedidosDetallesPedidoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesPedidoRel()
    {
        return $this->pedidosDetallesPedidoRel;
    }

    /**
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     *
     * @return TurPedido
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
     * Set estadoFacturado
     *
     * @param boolean $estadoFacturado
     *
     * @return TurPedido
     */
    public function setEstadoFacturado($estadoFacturado)
    {
        $this->estadoFacturado = $estadoFacturado;

        return $this;
    }

    /**
     * Get estadoFacturado
     *
     * @return boolean
     */
    public function getEstadoFacturado()
    {
        return $this->estadoFacturado;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurPedido
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
     * Set vrTotalOtros
     *
     * @param float $vrTotalOtros
     *
     * @return TurPedido
     */
    public function setVrTotalOtros($vrTotalOtros)
    {
        $this->vrTotalOtros = $vrTotalOtros;

        return $this;
    }

    /**
     * Get vrTotalOtros
     *
     * @return float
     */
    public function getVrTotalOtros()
    {
        return $this->vrTotalOtros;
    }

    /**
     * Set vrTotalServicio
     *
     * @param float $vrTotalServicio
     *
     * @return TurPedido
     */
    public function setVrTotalServicio($vrTotalServicio)
    {
        $this->vrTotalServicio = $vrTotalServicio;

        return $this;
    }

    /**
     * Get vrTotalServicio
     *
     * @return float
     */
    public function getVrTotalServicio()
    {
        return $this->vrTotalServicio;
    }

    /**
     * Set vrSubtotal
     *
     * @param float $vrSubtotal
     *
     * @return TurPedido
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
     * Set vrIva
     *
     * @param float $vrIva
     *
     * @return TurPedido
     */
    public function setVrIva($vrIva)
    {
        $this->vrIva = $vrIva;

        return $this;
    }

    /**
     * Get vrIva
     *
     * @return float
     */
    public function getVrIva()
    {
        return $this->vrIva;
    }

    /**
     * Set vrBaseAiu
     *
     * @param float $vrBaseAiu
     *
     * @return TurPedido
     */
    public function setVrBaseAiu($vrBaseAiu)
    {
        $this->vrBaseAiu = $vrBaseAiu;

        return $this;
    }

    /**
     * Get vrBaseAiu
     *
     * @return float
     */
    public function getVrBaseAiu()
    {
        return $this->vrBaseAiu;
    }

    /**
     * Add pedidosDetallesConceptosPedidoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosPedidoRel
     *
     * @return TurPedido
     */
    public function addPedidosDetallesConceptosPedidoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosPedidoRel)
    {
        $this->pedidosDetallesConceptosPedidoRel[] = $pedidosDetallesConceptosPedidoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesConceptosPedidoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosPedidoRel
     */
    public function removePedidosDetallesConceptosPedidoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosPedidoRel)
    {
        $this->pedidosDetallesConceptosPedidoRel->removeElement($pedidosDetallesConceptosPedidoRel);
    }

    /**
     * Get pedidosDetallesConceptosPedidoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesConceptosPedidoRel()
    {
        return $this->pedidosDetallesConceptosPedidoRel;
    }
}
