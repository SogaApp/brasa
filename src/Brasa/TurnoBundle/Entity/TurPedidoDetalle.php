<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_pedido_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPedidoDetalleRepository")
 */
class TurPedidoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPedidoDetallePk;  
    
    /**
     * @ORM\Column(name="codigo_pedido_fk", type="integer")
     */    
    private $codigoPedidoFk;

    /**
     * @ORM\Column(name="codigo_proyecto_fk", type="integer", nullable=true)
     */    
    private $codigoProyectoFk;    
    
    /**
     * @ORM\Column(name="codigo_grupo_facturacion_fk", type="integer", nullable=true)
     */    
    private $codigoGrupoFacturacionFk;     
    
    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;           
    
    /**
     * @ORM\Column(name="codigo_concepto_servicio_fk", type="integer")
     */    
    private $codigoConceptoServicioFk;    
    
    /**
     * @ORM\Column(name="codigo_modalidad_servicio_fk", type="integer")
     */    
    private $codigoModalidadServicioFk;           
    
    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer")
     */    
    private $codigoPeriodoFk;      
    
    /**
     * @ORM\Column(name="codigo_servicio_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoServicioDetalleFk;    
    
    /**
     * @ORM\Column(name="codigo_plantilla_fk", type="integer", nullable=true)
     */    
    private $codigoPlantillaFk;    
    
    /**
     * @ORM\Column(name="anio", type="integer")
     */    
    private $anio = 0;    
    
    /**
     * @ORM\Column(name="mes", type="integer")
     */    
    private $mes = 0;     
    
    /**
     * @ORM\Column(name="dia_desde", type="integer")
     */    
    private $diaDesde = 1;     

    /**
     * @ORM\Column(name="dia_hasta", type="integer")
     */    
    private $diaHasta = 1;         
    
    /**     
     * @ORM\Column(name="liquidar_dias_reales", type="boolean")
     */    
    private $liquidarDiasReales = false;    
    
    /**     
     * @ORM\Column(name="compuesto", type="boolean")
     */    
    private $compuesto = false;     
    
    /**
     * @ORM\Column(name="dias", type="integer")
     */    
    private $dias = 0; 
    
    /**
     * @ORM\Column(name="horas", type="float")
     */    
    private $horas = 0;    

    /**
     * @ORM\Column(name="horas_diurnas", type="float")
     */    
    private $horasDiurnas = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas", type="float")
     */    
    private $horasNocturnas = 0;     

    /**
     * @ORM\Column(name="horas_programadas", type="float")
     */    
    private $horasProgramadas = 0;    

    /**
     * @ORM\Column(name="horas_diurnas_programadas", type="float")
     */    
    private $horasDiurnasProgramadas = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas_programadas", type="float")
     */    
    private $horasNocturnasProgramadas = 0; 
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;     
    
    /**
     * @ORM\Column(name="cantidad_recurso", type="integer")
     */    
    private $cantidadRecurso = 0;         
    
    /**
     * @ORM\Column(name="vr_costo", type="float")
     */
    private $vrCosto = 0;
    
    /**
     * @ORM\Column(name="vr_precio_ajustado", type="float")
     */
    private $vrPrecioAjustado = 0;            

    /**
     * @ORM\Column(name="vr_precio_minimo", type="float")
     */
    private $vrPrecioMinimo = 0;        
    
    /**
     * @ORM\Column(name="vr_precio", type="float")
     */
    private $vrPrecio = 0;     
    
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
     * @ORM\Column(name="vr_total_detalle", type="float")
     */
    private $vrTotalDetalle = 0; 

    /**
     * @ORM\Column(name="vr_total_detalle_afectado", type="float")
     */
    private $vrTotalDetalleAfectado = 0; 
    
    /**
     * @ORM\Column(name="vr_total_detalle_pendiente", type="float")
     */
    private $vrTotalDetallePendiente = 0;    
    
    /**     
     * @ORM\Column(name="lunes", type="boolean")
     */    
    private $lunes = false;    
    
    /**     
     * @ORM\Column(name="martes", type="boolean")
     */    
    private $martes = false;        
    
    /**     
     * @ORM\Column(name="miercoles", type="boolean")
     */    
    private $miercoles = false;        
    
    /**     
     * @ORM\Column(name="jueves", type="boolean")
     */    
    private $jueves = false;        
    
    /**     
     * @ORM\Column(name="viernes", type="boolean")
     */    
    private $viernes = false;    
    
    /**     
     * @ORM\Column(name="sabado", type="boolean")
     */    
    private $sabado = false;        
    
    /**     
     * @ORM\Column(name="domingo", type="boolean")
     */    
    private $domingo = false;        
    
    /**     
     * @ORM\Column(name="festivo", type="boolean")
     */    
    private $festivo = false;        
    
    /**     
     * @ORM\Column(name="dia_31", type="boolean")
     */    
    private $dia31 = false;    
    
    /**     
     * @ORM\Column(name="estado_programado", type="boolean")
     */    
    private $estadoProgramado = false; 
    
    /**     
     * @ORM\Column(name="estado_facturado", type="boolean")
     */    
    private $estadoFacturado = false;     
    
    /**
     * @ORM\Column(name="fecha_inicia_plantilla", type="date", nullable=true)
     */    
    private $fechaIniciaPlantilla;    
    
    /**     
     * @ORM\Column(name="marca", type="boolean")
     */    
    private $marca = false;     

    /**     
     * @ORM\Column(name="ajuste_programacion", type="boolean")
     */    
    private $ajusteProgramacion = false;    
    
    /**
     * @ORM\Column(name="detalle", type="string", length=300, nullable=true)
     */
    private $detalle;    
    
    /**
     * @ORM\Column(name="detalle_puesto", type="string", length=200, nullable=true)
     */    
    private $detallePuesto;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPedido", inversedBy="pedidosDetallesPedidoRel")
     * @ORM\JoinColumn(name="codigo_pedido_fk", referencedColumnName="codigo_pedido_pk")
     */
    protected $pedidoRel;       

    /**
     * @ORM\ManyToOne(targetEntity="TurProyecto", inversedBy="pedidosDetallesProyectoRel")
     * @ORM\JoinColumn(name="codigo_proyecto_fk", referencedColumnName="codigo_proyecto_pk")
     */
    protected $proyectoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurGrupoFacturacion", inversedBy="pedidosDetallesGrupoFacturacionRel")
     * @ORM\JoinColumn(name="codigo_grupo_facturacion_fk", referencedColumnName="codigo_grupo_facturacion_pk")
     */
    protected $grupoFacturacionRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="pedidosDetallesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurConceptoServicio", inversedBy="pedidosDetallesConceptoServicioRel")
     * @ORM\JoinColumn(name="codigo_concepto_servicio_fk", referencedColumnName="codigo_concepto_servicio_pk")
     */
    protected $conceptoServicioRel;      

    /**
     * @ORM\ManyToOne(targetEntity="TurModalidadServicio", inversedBy="pedidosDetallesModalidadServicioRel")
     * @ORM\JoinColumn(name="codigo_modalidad_servicio_fk", referencedColumnName="codigo_modalidad_servicio_pk")
     */
    protected $modalidadServicioRel;            
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPeriodo", inversedBy="pedidosDetallesPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $periodoRel;      
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPlantilla", inversedBy="pedidosDetallesPlantillaRel")
     * @ORM\JoinColumn(name="codigo_plantilla_fk", referencedColumnName="codigo_plantilla_pk")
     */
    protected $plantillaRel;    

    /**
     * @ORM\ManyToOne(targetEntity="TurServicioDetalle", inversedBy="pedidosDetallesServicioDetalleRel")
     * @ORM\JoinColumn(name="codigo_servicio_detalle_fk", referencedColumnName="codigo_servicio_detalle_pk")
     */
    protected $servicioDetalleRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleCompuesto", mappedBy="pedidoDetalleRel", cascade={"persist", "remove"})
     */
    protected $pedidosDetallesCompuestosPedidoDetalleRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleRecurso", mappedBy="pedidoDetalleRel", cascade={"persist", "remove"})
     */
    protected $pedidosDetallesRecursosPedidoDetalleRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionDetalle", mappedBy="pedidoDetalleRel")
     */
    protected $programacionesDetallesPedidoDetalleRel; 

    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalle", mappedBy="pedidoDetalleRel")
     */
    protected $facturasDetallesPedidoDetalleRel;     

    /**
     * @ORM\OneToMany(targetEntity="TurSoportePagoDetalle", mappedBy="pedidoDetalleRel")
     */
    protected $soportesPagosDetallesPedidoDetalleRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCostoServicio", mappedBy="pedidoDetalleRel")
     */
    protected $costosServiciosPedidoDetalleRel;    
    

    /**
     * @ORM\OneToMany(targetEntity="TurCostoRecursoDetalle", mappedBy="pedidoDetalleRel")
     */
    protected $costosRecursosDetallesPedidoDetalleRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosDetallesCompuestosPedidoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidosDetallesRecursosPedidoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->programacionesDetallesPedidoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasDetallesPedidoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soportesPagosDetallesPedidoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->costosServiciosPedidoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->costosRecursosDetallesPedidoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPedidoDetallePk
     *
     * @return integer
     */
    public function getCodigoPedidoDetallePk()
    {
        return $this->codigoPedidoDetallePk;
    }

    /**
     * Set codigoPedidoFk
     *
     * @param integer $codigoPedidoFk
     *
     * @return TurPedidoDetalle
     */
    public function setCodigoPedidoFk($codigoPedidoFk)
    {
        $this->codigoPedidoFk = $codigoPedidoFk;

        return $this;
    }

    /**
     * Get codigoPedidoFk
     *
     * @return integer
     */
    public function getCodigoPedidoFk()
    {
        return $this->codigoPedidoFk;
    }

    /**
     * Set codigoProyectoFk
     *
     * @param integer $codigoProyectoFk
     *
     * @return TurPedidoDetalle
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
     * Set codigoGrupoFacturacionFk
     *
     * @param integer $codigoGrupoFacturacionFk
     *
     * @return TurPedidoDetalle
     */
    public function setCodigoGrupoFacturacionFk($codigoGrupoFacturacionFk)
    {
        $this->codigoGrupoFacturacionFk = $codigoGrupoFacturacionFk;

        return $this;
    }

    /**
     * Get codigoGrupoFacturacionFk
     *
     * @return integer
     */
    public function getCodigoGrupoFacturacionFk()
    {
        return $this->codigoGrupoFacturacionFk;
    }

    /**
     * Set codigoPuestoFk
     *
     * @param integer $codigoPuestoFk
     *
     * @return TurPedidoDetalle
     */
    public function setCodigoPuestoFk($codigoPuestoFk)
    {
        $this->codigoPuestoFk = $codigoPuestoFk;

        return $this;
    }

    /**
     * Get codigoPuestoFk
     *
     * @return integer
     */
    public function getCodigoPuestoFk()
    {
        return $this->codigoPuestoFk;
    }

    /**
     * Set codigoConceptoServicioFk
     *
     * @param integer $codigoConceptoServicioFk
     *
     * @return TurPedidoDetalle
     */
    public function setCodigoConceptoServicioFk($codigoConceptoServicioFk)
    {
        $this->codigoConceptoServicioFk = $codigoConceptoServicioFk;

        return $this;
    }

    /**
     * Get codigoConceptoServicioFk
     *
     * @return integer
     */
    public function getCodigoConceptoServicioFk()
    {
        return $this->codigoConceptoServicioFk;
    }

    /**
     * Set codigoModalidadServicioFk
     *
     * @param integer $codigoModalidadServicioFk
     *
     * @return TurPedidoDetalle
     */
    public function setCodigoModalidadServicioFk($codigoModalidadServicioFk)
    {
        $this->codigoModalidadServicioFk = $codigoModalidadServicioFk;

        return $this;
    }

    /**
     * Get codigoModalidadServicioFk
     *
     * @return integer
     */
    public function getCodigoModalidadServicioFk()
    {
        return $this->codigoModalidadServicioFk;
    }

    /**
     * Set codigoPeriodoFk
     *
     * @param integer $codigoPeriodoFk
     *
     * @return TurPedidoDetalle
     */
    public function setCodigoPeriodoFk($codigoPeriodoFk)
    {
        $this->codigoPeriodoFk = $codigoPeriodoFk;

        return $this;
    }

    /**
     * Get codigoPeriodoFk
     *
     * @return integer
     */
    public function getCodigoPeriodoFk()
    {
        return $this->codigoPeriodoFk;
    }

    /**
     * Set codigoServicioDetalleFk
     *
     * @param integer $codigoServicioDetalleFk
     *
     * @return TurPedidoDetalle
     */
    public function setCodigoServicioDetalleFk($codigoServicioDetalleFk)
    {
        $this->codigoServicioDetalleFk = $codigoServicioDetalleFk;

        return $this;
    }

    /**
     * Get codigoServicioDetalleFk
     *
     * @return integer
     */
    public function getCodigoServicioDetalleFk()
    {
        return $this->codigoServicioDetalleFk;
    }

    /**
     * Set codigoPlantillaFk
     *
     * @param integer $codigoPlantillaFk
     *
     * @return TurPedidoDetalle
     */
    public function setCodigoPlantillaFk($codigoPlantillaFk)
    {
        $this->codigoPlantillaFk = $codigoPlantillaFk;

        return $this;
    }

    /**
     * Get codigoPlantillaFk
     *
     * @return integer
     */
    public function getCodigoPlantillaFk()
    {
        return $this->codigoPlantillaFk;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return TurPedidoDetalle
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return integer
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     *
     * @return TurPedidoDetalle
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return integer
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set diaDesde
     *
     * @param integer $diaDesde
     *
     * @return TurPedidoDetalle
     */
    public function setDiaDesde($diaDesde)
    {
        $this->diaDesde = $diaDesde;

        return $this;
    }

    /**
     * Get diaDesde
     *
     * @return integer
     */
    public function getDiaDesde()
    {
        return $this->diaDesde;
    }

    /**
     * Set diaHasta
     *
     * @param integer $diaHasta
     *
     * @return TurPedidoDetalle
     */
    public function setDiaHasta($diaHasta)
    {
        $this->diaHasta = $diaHasta;

        return $this;
    }

    /**
     * Get diaHasta
     *
     * @return integer
     */
    public function getDiaHasta()
    {
        return $this->diaHasta;
    }

    /**
     * Set liquidarDiasReales
     *
     * @param boolean $liquidarDiasReales
     *
     * @return TurPedidoDetalle
     */
    public function setLiquidarDiasReales($liquidarDiasReales)
    {
        $this->liquidarDiasReales = $liquidarDiasReales;

        return $this;
    }

    /**
     * Get liquidarDiasReales
     *
     * @return boolean
     */
    public function getLiquidarDiasReales()
    {
        return $this->liquidarDiasReales;
    }

    /**
     * Set compuesto
     *
     * @param boolean $compuesto
     *
     * @return TurPedidoDetalle
     */
    public function setCompuesto($compuesto)
    {
        $this->compuesto = $compuesto;

        return $this;
    }

    /**
     * Get compuesto
     *
     * @return boolean
     */
    public function getCompuesto()
    {
        return $this->compuesto;
    }

    /**
     * Set dias
     *
     * @param integer $dias
     *
     * @return TurPedidoDetalle
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return integer
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * Set horas
     *
     * @param float $horas
     *
     * @return TurPedidoDetalle
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return float
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set horasDiurnas
     *
     * @param float $horasDiurnas
     *
     * @return TurPedidoDetalle
     */
    public function setHorasDiurnas($horasDiurnas)
    {
        $this->horasDiurnas = $horasDiurnas;

        return $this;
    }

    /**
     * Get horasDiurnas
     *
     * @return float
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * Set horasNocturnas
     *
     * @param float $horasNocturnas
     *
     * @return TurPedidoDetalle
     */
    public function setHorasNocturnas($horasNocturnas)
    {
        $this->horasNocturnas = $horasNocturnas;

        return $this;
    }

    /**
     * Get horasNocturnas
     *
     * @return float
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * Set horasProgramadas
     *
     * @param float $horasProgramadas
     *
     * @return TurPedidoDetalle
     */
    public function setHorasProgramadas($horasProgramadas)
    {
        $this->horasProgramadas = $horasProgramadas;

        return $this;
    }

    /**
     * Get horasProgramadas
     *
     * @return float
     */
    public function getHorasProgramadas()
    {
        return $this->horasProgramadas;
    }

    /**
     * Set horasDiurnasProgramadas
     *
     * @param float $horasDiurnasProgramadas
     *
     * @return TurPedidoDetalle
     */
    public function setHorasDiurnasProgramadas($horasDiurnasProgramadas)
    {
        $this->horasDiurnasProgramadas = $horasDiurnasProgramadas;

        return $this;
    }

    /**
     * Get horasDiurnasProgramadas
     *
     * @return float
     */
    public function getHorasDiurnasProgramadas()
    {
        return $this->horasDiurnasProgramadas;
    }

    /**
     * Set horasNocturnasProgramadas
     *
     * @param float $horasNocturnasProgramadas
     *
     * @return TurPedidoDetalle
     */
    public function setHorasNocturnasProgramadas($horasNocturnasProgramadas)
    {
        $this->horasNocturnasProgramadas = $horasNocturnasProgramadas;

        return $this;
    }

    /**
     * Get horasNocturnasProgramadas
     *
     * @return float
     */
    public function getHorasNocturnasProgramadas()
    {
        return $this->horasNocturnasProgramadas;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return TurPedidoDetalle
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
     * Set cantidadRecurso
     *
     * @param integer $cantidadRecurso
     *
     * @return TurPedidoDetalle
     */
    public function setCantidadRecurso($cantidadRecurso)
    {
        $this->cantidadRecurso = $cantidadRecurso;

        return $this;
    }

    /**
     * Get cantidadRecurso
     *
     * @return integer
     */
    public function getCantidadRecurso()
    {
        return $this->cantidadRecurso;
    }

    /**
     * Set vrCosto
     *
     * @param float $vrCosto
     *
     * @return TurPedidoDetalle
     */
    public function setVrCosto($vrCosto)
    {
        $this->vrCosto = $vrCosto;

        return $this;
    }

    /**
     * Get vrCosto
     *
     * @return float
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * Set vrPrecioAjustado
     *
     * @param float $vrPrecioAjustado
     *
     * @return TurPedidoDetalle
     */
    public function setVrPrecioAjustado($vrPrecioAjustado)
    {
        $this->vrPrecioAjustado = $vrPrecioAjustado;

        return $this;
    }

    /**
     * Get vrPrecioAjustado
     *
     * @return float
     */
    public function getVrPrecioAjustado()
    {
        return $this->vrPrecioAjustado;
    }

    /**
     * Set vrPrecioMinimo
     *
     * @param float $vrPrecioMinimo
     *
     * @return TurPedidoDetalle
     */
    public function setVrPrecioMinimo($vrPrecioMinimo)
    {
        $this->vrPrecioMinimo = $vrPrecioMinimo;

        return $this;
    }

    /**
     * Get vrPrecioMinimo
     *
     * @return float
     */
    public function getVrPrecioMinimo()
    {
        return $this->vrPrecioMinimo;
    }

    /**
     * Set vrPrecio
     *
     * @param float $vrPrecio
     *
     * @return TurPedidoDetalle
     */
    public function setVrPrecio($vrPrecio)
    {
        $this->vrPrecio = $vrPrecio;

        return $this;
    }

    /**
     * Get vrPrecio
     *
     * @return float
     */
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * Set vrSubtotal
     *
     * @param float $vrSubtotal
     *
     * @return TurPedidoDetalle
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
     * @return TurPedidoDetalle
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
     * @return TurPedidoDetalle
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
     * Set vrTotalDetalle
     *
     * @param float $vrTotalDetalle
     *
     * @return TurPedidoDetalle
     */
    public function setVrTotalDetalle($vrTotalDetalle)
    {
        $this->vrTotalDetalle = $vrTotalDetalle;

        return $this;
    }

    /**
     * Get vrTotalDetalle
     *
     * @return float
     */
    public function getVrTotalDetalle()
    {
        return $this->vrTotalDetalle;
    }

    /**
     * Set vrTotalDetalleAfectado
     *
     * @param float $vrTotalDetalleAfectado
     *
     * @return TurPedidoDetalle
     */
    public function setVrTotalDetalleAfectado($vrTotalDetalleAfectado)
    {
        $this->vrTotalDetalleAfectado = $vrTotalDetalleAfectado;

        return $this;
    }

    /**
     * Get vrTotalDetalleAfectado
     *
     * @return float
     */
    public function getVrTotalDetalleAfectado()
    {
        return $this->vrTotalDetalleAfectado;
    }

    /**
     * Set vrTotalDetallePendiente
     *
     * @param float $vrTotalDetallePendiente
     *
     * @return TurPedidoDetalle
     */
    public function setVrTotalDetallePendiente($vrTotalDetallePendiente)
    {
        $this->vrTotalDetallePendiente = $vrTotalDetallePendiente;

        return $this;
    }

    /**
     * Get vrTotalDetallePendiente
     *
     * @return float
     */
    public function getVrTotalDetallePendiente()
    {
        return $this->vrTotalDetallePendiente;
    }

    /**
     * Set lunes
     *
     * @param boolean $lunes
     *
     * @return TurPedidoDetalle
     */
    public function setLunes($lunes)
    {
        $this->lunes = $lunes;

        return $this;
    }

    /**
     * Get lunes
     *
     * @return boolean
     */
    public function getLunes()
    {
        return $this->lunes;
    }

    /**
     * Set martes
     *
     * @param boolean $martes
     *
     * @return TurPedidoDetalle
     */
    public function setMartes($martes)
    {
        $this->martes = $martes;

        return $this;
    }

    /**
     * Get martes
     *
     * @return boolean
     */
    public function getMartes()
    {
        return $this->martes;
    }

    /**
     * Set miercoles
     *
     * @param boolean $miercoles
     *
     * @return TurPedidoDetalle
     */
    public function setMiercoles($miercoles)
    {
        $this->miercoles = $miercoles;

        return $this;
    }

    /**
     * Get miercoles
     *
     * @return boolean
     */
    public function getMiercoles()
    {
        return $this->miercoles;
    }

    /**
     * Set jueves
     *
     * @param boolean $jueves
     *
     * @return TurPedidoDetalle
     */
    public function setJueves($jueves)
    {
        $this->jueves = $jueves;

        return $this;
    }

    /**
     * Get jueves
     *
     * @return boolean
     */
    public function getJueves()
    {
        return $this->jueves;
    }

    /**
     * Set viernes
     *
     * @param boolean $viernes
     *
     * @return TurPedidoDetalle
     */
    public function setViernes($viernes)
    {
        $this->viernes = $viernes;

        return $this;
    }

    /**
     * Get viernes
     *
     * @return boolean
     */
    public function getViernes()
    {
        return $this->viernes;
    }

    /**
     * Set sabado
     *
     * @param boolean $sabado
     *
     * @return TurPedidoDetalle
     */
    public function setSabado($sabado)
    {
        $this->sabado = $sabado;

        return $this;
    }

    /**
     * Get sabado
     *
     * @return boolean
     */
    public function getSabado()
    {
        return $this->sabado;
    }

    /**
     * Set domingo
     *
     * @param boolean $domingo
     *
     * @return TurPedidoDetalle
     */
    public function setDomingo($domingo)
    {
        $this->domingo = $domingo;

        return $this;
    }

    /**
     * Get domingo
     *
     * @return boolean
     */
    public function getDomingo()
    {
        return $this->domingo;
    }

    /**
     * Set festivo
     *
     * @param boolean $festivo
     *
     * @return TurPedidoDetalle
     */
    public function setFestivo($festivo)
    {
        $this->festivo = $festivo;

        return $this;
    }

    /**
     * Get festivo
     *
     * @return boolean
     */
    public function getFestivo()
    {
        return $this->festivo;
    }

    /**
     * Set dia31
     *
     * @param boolean $dia31
     *
     * @return TurPedidoDetalle
     */
    public function setDia31($dia31)
    {
        $this->dia31 = $dia31;

        return $this;
    }

    /**
     * Get dia31
     *
     * @return boolean
     */
    public function getDia31()
    {
        return $this->dia31;
    }

    /**
     * Set estadoProgramado
     *
     * @param boolean $estadoProgramado
     *
     * @return TurPedidoDetalle
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
     * Set estadoFacturado
     *
     * @param boolean $estadoFacturado
     *
     * @return TurPedidoDetalle
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
     * Set fechaIniciaPlantilla
     *
     * @param \DateTime $fechaIniciaPlantilla
     *
     * @return TurPedidoDetalle
     */
    public function setFechaIniciaPlantilla($fechaIniciaPlantilla)
    {
        $this->fechaIniciaPlantilla = $fechaIniciaPlantilla;

        return $this;
    }

    /**
     * Get fechaIniciaPlantilla
     *
     * @return \DateTime
     */
    public function getFechaIniciaPlantilla()
    {
        return $this->fechaIniciaPlantilla;
    }

    /**
     * Set marca
     *
     * @param boolean $marca
     *
     * @return TurPedidoDetalle
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * Get marca
     *
     * @return boolean
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * Set ajusteProgramacion
     *
     * @param boolean $ajusteProgramacion
     *
     * @return TurPedidoDetalle
     */
    public function setAjusteProgramacion($ajusteProgramacion)
    {
        $this->ajusteProgramacion = $ajusteProgramacion;

        return $this;
    }

    /**
     * Get ajusteProgramacion
     *
     * @return boolean
     */
    public function getAjusteProgramacion()
    {
        return $this->ajusteProgramacion;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return TurPedidoDetalle
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set detallePuesto
     *
     * @param string $detallePuesto
     *
     * @return TurPedidoDetalle
     */
    public function setDetallePuesto($detallePuesto)
    {
        $this->detallePuesto = $detallePuesto;

        return $this;
    }

    /**
     * Get detallePuesto
     *
     * @return string
     */
    public function getDetallePuesto()
    {
        return $this->detallePuesto;
    }

    /**
     * Set pedidoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedido $pedidoRel
     *
     * @return TurPedidoDetalle
     */
    public function setPedidoRel(\Brasa\TurnoBundle\Entity\TurPedido $pedidoRel = null)
    {
        $this->pedidoRel = $pedidoRel;

        return $this;
    }

    /**
     * Get pedidoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPedido
     */
    public function getPedidoRel()
    {
        return $this->pedidoRel;
    }

    /**
     * Set proyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProyecto $proyectoRel
     *
     * @return TurPedidoDetalle
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
     * Set grupoFacturacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurGrupoFacturacion $grupoFacturacionRel
     *
     * @return TurPedidoDetalle
     */
    public function setGrupoFacturacionRel(\Brasa\TurnoBundle\Entity\TurGrupoFacturacion $grupoFacturacionRel = null)
    {
        $this->grupoFacturacionRel = $grupoFacturacionRel;

        return $this;
    }

    /**
     * Get grupoFacturacionRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurGrupoFacturacion
     */
    public function getGrupoFacturacionRel()
    {
        return $this->grupoFacturacionRel;
    }

    /**
     * Set puestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestoRel
     *
     * @return TurPedidoDetalle
     */
    public function setPuestoRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestoRel = null)
    {
        $this->puestoRel = $puestoRel;

        return $this;
    }

    /**
     * Get puestoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPuesto
     */
    public function getPuestoRel()
    {
        return $this->puestoRel;
    }

    /**
     * Set conceptoServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurConceptoServicio $conceptoServicioRel
     *
     * @return TurPedidoDetalle
     */
    public function setConceptoServicioRel(\Brasa\TurnoBundle\Entity\TurConceptoServicio $conceptoServicioRel = null)
    {
        $this->conceptoServicioRel = $conceptoServicioRel;

        return $this;
    }

    /**
     * Get conceptoServicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurConceptoServicio
     */
    public function getConceptoServicioRel()
    {
        return $this->conceptoServicioRel;
    }

    /**
     * Set modalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurModalidadServicio $modalidadServicioRel
     *
     * @return TurPedidoDetalle
     */
    public function setModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurModalidadServicio $modalidadServicioRel = null)
    {
        $this->modalidadServicioRel = $modalidadServicioRel;

        return $this;
    }

    /**
     * Get modalidadServicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurModalidadServicio
     */
    public function getModalidadServicioRel()
    {
        return $this->modalidadServicioRel;
    }

    /**
     * Set periodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPeriodo $periodoRel
     *
     * @return TurPedidoDetalle
     */
    public function setPeriodoRel(\Brasa\TurnoBundle\Entity\TurPeriodo $periodoRel = null)
    {
        $this->periodoRel = $periodoRel;

        return $this;
    }

    /**
     * Get periodoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPeriodo
     */
    public function getPeriodoRel()
    {
        return $this->periodoRel;
    }

    /**
     * Set plantillaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPlantilla $plantillaRel
     *
     * @return TurPedidoDetalle
     */
    public function setPlantillaRel(\Brasa\TurnoBundle\Entity\TurPlantilla $plantillaRel = null)
    {
        $this->plantillaRel = $plantillaRel;

        return $this;
    }

    /**
     * Get plantillaRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPlantilla
     */
    public function getPlantillaRel()
    {
        return $this->plantillaRel;
    }

    /**
     * Set servicioDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $servicioDetalleRel
     *
     * @return TurPedidoDetalle
     */
    public function setServicioDetalleRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $servicioDetalleRel = null)
    {
        $this->servicioDetalleRel = $servicioDetalleRel;

        return $this;
    }

    /**
     * Get servicioDetalleRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurServicioDetalle
     */
    public function getServicioDetalleRel()
    {
        return $this->servicioDetalleRel;
    }

    /**
     * Add pedidosDetallesCompuestosPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto $pedidosDetallesCompuestosPedidoDetalleRel
     *
     * @return TurPedidoDetalle
     */
    public function addPedidosDetallesCompuestosPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto $pedidosDetallesCompuestosPedidoDetalleRel)
    {
        $this->pedidosDetallesCompuestosPedidoDetalleRel[] = $pedidosDetallesCompuestosPedidoDetalleRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesCompuestosPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto $pedidosDetallesCompuestosPedidoDetalleRel
     */
    public function removePedidosDetallesCompuestosPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto $pedidosDetallesCompuestosPedidoDetalleRel)
    {
        $this->pedidosDetallesCompuestosPedidoDetalleRel->removeElement($pedidosDetallesCompuestosPedidoDetalleRel);
    }

    /**
     * Get pedidosDetallesCompuestosPedidoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesCompuestosPedidoDetalleRel()
    {
        return $this->pedidosDetallesCompuestosPedidoDetalleRel;
    }

    /**
     * Add pedidosDetallesRecursosPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosPedidoDetalleRel
     *
     * @return TurPedidoDetalle
     */
    public function addPedidosDetallesRecursosPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosPedidoDetalleRel)
    {
        $this->pedidosDetallesRecursosPedidoDetalleRel[] = $pedidosDetallesRecursosPedidoDetalleRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesRecursosPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosPedidoDetalleRel
     */
    public function removePedidosDetallesRecursosPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosPedidoDetalleRel)
    {
        $this->pedidosDetallesRecursosPedidoDetalleRel->removeElement($pedidosDetallesRecursosPedidoDetalleRel);
    }

    /**
     * Get pedidosDetallesRecursosPedidoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesRecursosPedidoDetalleRel()
    {
        return $this->pedidosDetallesRecursosPedidoDetalleRel;
    }

    /**
     * Add programacionesDetallesPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPedidoDetalleRel
     *
     * @return TurPedidoDetalle
     */
    public function addProgramacionesDetallesPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPedidoDetalleRel)
    {
        $this->programacionesDetallesPedidoDetalleRel[] = $programacionesDetallesPedidoDetalleRel;

        return $this;
    }

    /**
     * Remove programacionesDetallesPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPedidoDetalleRel
     */
    public function removeProgramacionesDetallesPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPedidoDetalleRel)
    {
        $this->programacionesDetallesPedidoDetalleRel->removeElement($programacionesDetallesPedidoDetalleRel);
    }

    /**
     * Get programacionesDetallesPedidoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesDetallesPedidoDetalleRel()
    {
        return $this->programacionesDetallesPedidoDetalleRel;
    }

    /**
     * Add facturasDetallesPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPedidoDetalleRel
     *
     * @return TurPedidoDetalle
     */
    public function addFacturasDetallesPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPedidoDetalleRel)
    {
        $this->facturasDetallesPedidoDetalleRel[] = $facturasDetallesPedidoDetalleRel;

        return $this;
    }

    /**
     * Remove facturasDetallesPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPedidoDetalleRel
     */
    public function removeFacturasDetallesPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPedidoDetalleRel)
    {
        $this->facturasDetallesPedidoDetalleRel->removeElement($facturasDetallesPedidoDetalleRel);
    }

    /**
     * Get facturasDetallesPedidoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesPedidoDetalleRel()
    {
        return $this->facturasDetallesPedidoDetalleRel;
    }

    /**
     * Add soportesPagosDetallesPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesPedidoDetalleRel
     *
     * @return TurPedidoDetalle
     */
    public function addSoportesPagosDetallesPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesPedidoDetalleRel)
    {
        $this->soportesPagosDetallesPedidoDetalleRel[] = $soportesPagosDetallesPedidoDetalleRel;

        return $this;
    }

    /**
     * Remove soportesPagosDetallesPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesPedidoDetalleRel
     */
    public function removeSoportesPagosDetallesPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesPedidoDetalleRel)
    {
        $this->soportesPagosDetallesPedidoDetalleRel->removeElement($soportesPagosDetallesPedidoDetalleRel);
    }

    /**
     * Get soportesPagosDetallesPedidoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosDetallesPedidoDetalleRel()
    {
        return $this->soportesPagosDetallesPedidoDetalleRel;
    }

    /**
     * Add costosServiciosPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPedidoDetalleRel
     *
     * @return TurPedidoDetalle
     */
    public function addCostosServiciosPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPedidoDetalleRel)
    {
        $this->costosServiciosPedidoDetalleRel[] = $costosServiciosPedidoDetalleRel;

        return $this;
    }

    /**
     * Remove costosServiciosPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPedidoDetalleRel
     */
    public function removeCostosServiciosPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPedidoDetalleRel)
    {
        $this->costosServiciosPedidoDetalleRel->removeElement($costosServiciosPedidoDetalleRel);
    }

    /**
     * Get costosServiciosPedidoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCostosServiciosPedidoDetalleRel()
    {
        return $this->costosServiciosPedidoDetalleRel;
    }

    /**
     * Add costosRecursosDetallesPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoRecursoDetalle $costosRecursosDetallesPedidoDetalleRel
     *
     * @return TurPedidoDetalle
     */
    public function addCostosRecursosDetallesPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurCostoRecursoDetalle $costosRecursosDetallesPedidoDetalleRel)
    {
        $this->costosRecursosDetallesPedidoDetalleRel[] = $costosRecursosDetallesPedidoDetalleRel;

        return $this;
    }

    /**
     * Remove costosRecursosDetallesPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoRecursoDetalle $costosRecursosDetallesPedidoDetalleRel
     */
    public function removeCostosRecursosDetallesPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurCostoRecursoDetalle $costosRecursosDetallesPedidoDetalleRel)
    {
        $this->costosRecursosDetallesPedidoDetalleRel->removeElement($costosRecursosDetallesPedidoDetalleRel);
    }

    /**
     * Get costosRecursosDetallesPedidoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCostosRecursosDetallesPedidoDetalleRel()
    {
        return $this->costosRecursosDetallesPedidoDetalleRel;
    }
}
