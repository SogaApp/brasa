<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_factura_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuFacturaDetalleRepository")
 */
class RhuFacturaDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaDetallePk;
    
    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer", nullable=true)
     */    
    private $codigoFacturaFk;
    
    /**
     * @ORM\Column(name="codigo_servicio_cobrar_fk", type="integer", nullable=true)
     */    
    private $codigoServicioCobrarFk;
    
    /**
     * @ORM\Column(name="codigo_pago_fk", type="integer", nullable=true)
     */    
    private $codigoPagoFk;
    
    /**
     * @ORM\Column(name="codigo_programacion_pago_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionPagoFk;
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0; 
    
    /**
     * Es el salario corerspondiente a los dias * VrDia
     * @ORM\Column(name="vr_salario_periodo", type="float")
     */
    private $vrSalarioPeriodo = 0;    

    /**
     * Es el salario que tenia el empleado cuando se genero el pago
     * @ORM\Column(name="vr_salario_empleado", type="float")
     */
    private $vrSalarioEmpleado = 0;
    
    /**
     * @ORM\Column(name="vr_devengado", type="float")
     */
    private $vrDevengado = 0;    

    /**
     * @ORM\Column(name="vr_deducciones", type="float")
     */
    private $vrDeducciones = 0;    

    /**
     * @ORM\Column(name="vr_adicional_tiempo", type="float")
     */
    private $vrAdicionalTiempo = 0;     

    /**
     * @ORM\Column(name="vr_adicional_valor", type="float")
     */
    private $vrAdicionalValor = 0;    
    
    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float")
     */
    private $vrAuxilioTransporte = 0;    
    
    /**
     * @ORM\Column(name="vr_arp", type="float")
     */
    private $vrArp = 0;    
    
    /**
     * @ORM\Column(name="vr_eps", type="float")
     */
    private $vrEps = 0;    
    
    /**
     * @ORM\Column(name="vr_pension", type="float")
     */
    private $vrPension = 0;    
    
    /**
     * @ORM\Column(name="vr_caja", type="float")
     */
    private $vrCaja = 0;    
    
    /**
     * @ORM\Column(name="vr_sena", type="float")
     */
    private $vrSena = 0;    
    
    /**
     * @ORM\Column(name="vr_icbf", type="float")
     */
    private $vrIcbf = 0;    
    
    /**
     * @ORM\Column(name="vr_cesantias", type="float")
     */
    private $vrCesantias = 0;    
    
    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */
    private $vrVacaciones = 0;    
    
    /**
     * @ORM\Column(name="vr_administracion", type="float")
     */
    private $vrAdministracion = 0;    
    
    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;    
    
    /**
     * @ORM\Column(name="vr_bruto", type="float")
     */
    private $vrBruto = 0;                    
    
    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", type="float")
     */
    private $vrIngresoBaseCotizacion = 0;     
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;
    
    /**
     * @ORM\Column(name="vr_auxilio_transporte_cotizacion", type="float")
     */
    private $vrAuxilioTransporteCotizacion = 0;
    
    /**
     * @ORM\Column(name="vr_total_cobrar", type="float")
     */
    private $vrTotalCobrar = 0;
    
    /**
     * @ORM\Column(name="vr_costo", type="float")
     */
    private $vrCosto = 0;
    
    /**
     * @ORM\Column(name="estado_cobrado", type="boolean")
     */    
    private $estadoCobrado = 0;     
    
    /**
     * @ORM\Column(name="dias_periodo", type="integer")
     */
    private $diasPeriodo = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuFactura", inversedBy="facturasDetallesFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuPago", inversedBy="facturasDetallesPagoRel")
     * @ORM\JoinColumn(name="codigo_pago_fk", referencedColumnName="codigo_pago_pk")
     */
    protected $pagoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuServicioCobrar", inversedBy="facturasDetallesServicioCobrarRel")
     * @ORM\JoinColumn(name="codigo_servicio_cobrar_fk", referencedColumnName="codigo_servicio_cobrar_pk")
     */
    protected $servicioCobrarRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="facturasDetallesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="facturasDetallesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionPago", inversedBy="facturasDetallesProgramacionPagoRel")
     * @ORM\JoinColumn(name="codigo_programacion_pago_fk", referencedColumnName="codigo_programacion_pago_pk")
     */
    protected $programacionPagoRel;    

    

    /**
     * Get codigoFacturaDetallePk
     *
     * @return integer
     */
    public function getCodigoFacturaDetallePk()
    {
        return $this->codigoFacturaDetallePk;
    }

    /**
     * Set codigoFacturaFk
     *
     * @param integer $codigoFacturaFk
     *
     * @return RhuFacturaDetalle
     */
    public function setCodigoFacturaFk($codigoFacturaFk)
    {
        $this->codigoFacturaFk = $codigoFacturaFk;

        return $this;
    }

    /**
     * Get codigoFacturaFk
     *
     * @return integer
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * Set codigoServicioCobrarFk
     *
     * @param integer $codigoServicioCobrarFk
     *
     * @return RhuFacturaDetalle
     */
    public function setCodigoServicioCobrarFk($codigoServicioCobrarFk)
    {
        $this->codigoServicioCobrarFk = $codigoServicioCobrarFk;

        return $this;
    }

    /**
     * Get codigoServicioCobrarFk
     *
     * @return integer
     */
    public function getCodigoServicioCobrarFk()
    {
        return $this->codigoServicioCobrarFk;
    }

    /**
     * Set codigoPagoFk
     *
     * @param integer $codigoPagoFk
     *
     * @return RhuFacturaDetalle
     */
    public function setCodigoPagoFk($codigoPagoFk)
    {
        $this->codigoPagoFk = $codigoPagoFk;

        return $this;
    }

    /**
     * Get codigoPagoFk
     *
     * @return integer
     */
    public function getCodigoPagoFk()
    {
        return $this->codigoPagoFk;
    }

    /**
     * Set codigoProgramacionPagoFk
     *
     * @param integer $codigoProgramacionPagoFk
     *
     * @return RhuFacturaDetalle
     */
    public function setCodigoProgramacionPagoFk($codigoProgramacionPagoFk)
    {
        $this->codigoProgramacionPagoFk = $codigoProgramacionPagoFk;

        return $this;
    }

    /**
     * Get codigoProgramacionPagoFk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoFk()
    {
        return $this->codigoProgramacionPagoFk;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuFacturaDetalle
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuFacturaDetalle
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk)
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuFacturaDetalle
     */
    public function setVrSalario($vrSalario)
    {
        $this->vrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * Set vrSalarioPeriodo
     *
     * @param float $vrSalarioPeriodo
     *
     * @return RhuFacturaDetalle
     */
    public function setVrSalarioPeriodo($vrSalarioPeriodo)
    {
        $this->vrSalarioPeriodo = $vrSalarioPeriodo;

        return $this;
    }

    /**
     * Get vrSalarioPeriodo
     *
     * @return float
     */
    public function getVrSalarioPeriodo()
    {
        return $this->vrSalarioPeriodo;
    }

    /**
     * Set vrSalarioEmpleado
     *
     * @param float $vrSalarioEmpleado
     *
     * @return RhuFacturaDetalle
     */
    public function setVrSalarioEmpleado($vrSalarioEmpleado)
    {
        $this->vrSalarioEmpleado = $vrSalarioEmpleado;

        return $this;
    }

    /**
     * Get vrSalarioEmpleado
     *
     * @return float
     */
    public function getVrSalarioEmpleado()
    {
        return $this->vrSalarioEmpleado;
    }

    /**
     * Set vrDevengado
     *
     * @param float $vrDevengado
     *
     * @return RhuFacturaDetalle
     */
    public function setVrDevengado($vrDevengado)
    {
        $this->vrDevengado = $vrDevengado;

        return $this;
    }

    /**
     * Get vrDevengado
     *
     * @return float
     */
    public function getVrDevengado()
    {
        return $this->vrDevengado;
    }

    /**
     * Set vrDeducciones
     *
     * @param float $vrDeducciones
     *
     * @return RhuFacturaDetalle
     */
    public function setVrDeducciones($vrDeducciones)
    {
        $this->vrDeducciones = $vrDeducciones;

        return $this;
    }

    /**
     * Get vrDeducciones
     *
     * @return float
     */
    public function getVrDeducciones()
    {
        return $this->vrDeducciones;
    }

    /**
     * Set vrAdicionalTiempo
     *
     * @param float $vrAdicionalTiempo
     *
     * @return RhuFacturaDetalle
     */
    public function setVrAdicionalTiempo($vrAdicionalTiempo)
    {
        $this->vrAdicionalTiempo = $vrAdicionalTiempo;

        return $this;
    }

    /**
     * Get vrAdicionalTiempo
     *
     * @return float
     */
    public function getVrAdicionalTiempo()
    {
        return $this->vrAdicionalTiempo;
    }

    /**
     * Set vrAdicionalValor
     *
     * @param float $vrAdicionalValor
     *
     * @return RhuFacturaDetalle
     */
    public function setVrAdicionalValor($vrAdicionalValor)
    {
        $this->vrAdicionalValor = $vrAdicionalValor;

        return $this;
    }

    /**
     * Get vrAdicionalValor
     *
     * @return float
     */
    public function getVrAdicionalValor()
    {
        return $this->vrAdicionalValor;
    }

    /**
     * Set vrAuxilioTransporte
     *
     * @param float $vrAuxilioTransporte
     *
     * @return RhuFacturaDetalle
     */
    public function setVrAuxilioTransporte($vrAuxilioTransporte)
    {
        $this->vrAuxilioTransporte = $vrAuxilioTransporte;

        return $this;
    }

    /**
     * Get vrAuxilioTransporte
     *
     * @return float
     */
    public function getVrAuxilioTransporte()
    {
        return $this->vrAuxilioTransporte;
    }

    /**
     * Set vrArp
     *
     * @param float $vrArp
     *
     * @return RhuFacturaDetalle
     */
    public function setVrArp($vrArp)
    {
        $this->vrArp = $vrArp;

        return $this;
    }

    /**
     * Get vrArp
     *
     * @return float
     */
    public function getVrArp()
    {
        return $this->vrArp;
    }

    /**
     * Set vrEps
     *
     * @param float $vrEps
     *
     * @return RhuFacturaDetalle
     */
    public function setVrEps($vrEps)
    {
        $this->vrEps = $vrEps;

        return $this;
    }

    /**
     * Get vrEps
     *
     * @return float
     */
    public function getVrEps()
    {
        return $this->vrEps;
    }

    /**
     * Set vrPension
     *
     * @param float $vrPension
     *
     * @return RhuFacturaDetalle
     */
    public function setVrPension($vrPension)
    {
        $this->vrPension = $vrPension;

        return $this;
    }

    /**
     * Get vrPension
     *
     * @return float
     */
    public function getVrPension()
    {
        return $this->vrPension;
    }

    /**
     * Set vrCaja
     *
     * @param float $vrCaja
     *
     * @return RhuFacturaDetalle
     */
    public function setVrCaja($vrCaja)
    {
        $this->vrCaja = $vrCaja;

        return $this;
    }

    /**
     * Get vrCaja
     *
     * @return float
     */
    public function getVrCaja()
    {
        return $this->vrCaja;
    }

    /**
     * Set vrSena
     *
     * @param float $vrSena
     *
     * @return RhuFacturaDetalle
     */
    public function setVrSena($vrSena)
    {
        $this->vrSena = $vrSena;

        return $this;
    }

    /**
     * Get vrSena
     *
     * @return float
     */
    public function getVrSena()
    {
        return $this->vrSena;
    }

    /**
     * Set vrIcbf
     *
     * @param float $vrIcbf
     *
     * @return RhuFacturaDetalle
     */
    public function setVrIcbf($vrIcbf)
    {
        $this->vrIcbf = $vrIcbf;

        return $this;
    }

    /**
     * Get vrIcbf
     *
     * @return float
     */
    public function getVrIcbf()
    {
        return $this->vrIcbf;
    }

    /**
     * Set vrCesantias
     *
     * @param float $vrCesantias
     *
     * @return RhuFacturaDetalle
     */
    public function setVrCesantias($vrCesantias)
    {
        $this->vrCesantias = $vrCesantias;

        return $this;
    }

    /**
     * Get vrCesantias
     *
     * @return float
     */
    public function getVrCesantias()
    {
        return $this->vrCesantias;
    }

    /**
     * Set vrVacaciones
     *
     * @param float $vrVacaciones
     *
     * @return RhuFacturaDetalle
     */
    public function setVrVacaciones($vrVacaciones)
    {
        $this->vrVacaciones = $vrVacaciones;

        return $this;
    }

    /**
     * Get vrVacaciones
     *
     * @return float
     */
    public function getVrVacaciones()
    {
        return $this->vrVacaciones;
    }

    /**
     * Set vrAdministracion
     *
     * @param float $vrAdministracion
     *
     * @return RhuFacturaDetalle
     */
    public function setVrAdministracion($vrAdministracion)
    {
        $this->vrAdministracion = $vrAdministracion;

        return $this;
    }

    /**
     * Get vrAdministracion
     *
     * @return float
     */
    public function getVrAdministracion()
    {
        return $this->vrAdministracion;
    }

    /**
     * Set vrNeto
     *
     * @param float $vrNeto
     *
     * @return RhuFacturaDetalle
     */
    public function setVrNeto($vrNeto)
    {
        $this->vrNeto = $vrNeto;

        return $this;
    }

    /**
     * Get vrNeto
     *
     * @return float
     */
    public function getVrNeto()
    {
        return $this->vrNeto;
    }

    /**
     * Set vrBruto
     *
     * @param float $vrBruto
     *
     * @return RhuFacturaDetalle
     */
    public function setVrBruto($vrBruto)
    {
        $this->vrBruto = $vrBruto;

        return $this;
    }

    /**
     * Get vrBruto
     *
     * @return float
     */
    public function getVrBruto()
    {
        return $this->vrBruto;
    }

    /**
     * Set vrIngresoBaseCotizacion
     *
     * @param float $vrIngresoBaseCotizacion
     *
     * @return RhuFacturaDetalle
     */
    public function setVrIngresoBaseCotizacion($vrIngresoBaseCotizacion)
    {
        $this->vrIngresoBaseCotizacion = $vrIngresoBaseCotizacion;

        return $this;
    }

    /**
     * Get vrIngresoBaseCotizacion
     *
     * @return float
     */
    public function getVrIngresoBaseCotizacion()
    {
        return $this->vrIngresoBaseCotizacion;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuFacturaDetalle
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return RhuFacturaDetalle
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set vrAuxilioTransporteCotizacion
     *
     * @param float $vrAuxilioTransporteCotizacion
     *
     * @return RhuFacturaDetalle
     */
    public function setVrAuxilioTransporteCotizacion($vrAuxilioTransporteCotizacion)
    {
        $this->vrAuxilioTransporteCotizacion = $vrAuxilioTransporteCotizacion;

        return $this;
    }

    /**
     * Get vrAuxilioTransporteCotizacion
     *
     * @return float
     */
    public function getVrAuxilioTransporteCotizacion()
    {
        return $this->vrAuxilioTransporteCotizacion;
    }

    /**
     * Set vrTotalCobrar
     *
     * @param float $vrTotalCobrar
     *
     * @return RhuFacturaDetalle
     */
    public function setVrTotalCobrar($vrTotalCobrar)
    {
        $this->vrTotalCobrar = $vrTotalCobrar;

        return $this;
    }

    /**
     * Get vrTotalCobrar
     *
     * @return float
     */
    public function getVrTotalCobrar()
    {
        return $this->vrTotalCobrar;
    }

    /**
     * Set vrCosto
     *
     * @param float $vrCosto
     *
     * @return RhuFacturaDetalle
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
     * Set estadoCobrado
     *
     * @param boolean $estadoCobrado
     *
     * @return RhuFacturaDetalle
     */
    public function setEstadoCobrado($estadoCobrado)
    {
        $this->estadoCobrado = $estadoCobrado;

        return $this;
    }

    /**
     * Get estadoCobrado
     *
     * @return boolean
     */
    public function getEstadoCobrado()
    {
        return $this->estadoCobrado;
    }

    /**
     * Set diasPeriodo
     *
     * @param integer $diasPeriodo
     *
     * @return RhuFacturaDetalle
     */
    public function setDiasPeriodo($diasPeriodo)
    {
        $this->diasPeriodo = $diasPeriodo;

        return $this;
    }

    /**
     * Get diasPeriodo
     *
     * @return integer
     */
    public function getDiasPeriodo()
    {
        return $this->diasPeriodo;
    }

    /**
     * Set facturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturaRel
     *
     * @return RhuFacturaDetalle
     */
    public function setFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturaRel = null)
    {
        $this->facturaRel = $facturaRel;

        return $this;
    }

    /**
     * Get facturaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuFactura
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * Set pagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagoRel
     *
     * @return RhuFacturaDetalle
     */
    public function setPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagoRel = null)
    {
        $this->pagoRel = $pagoRel;

        return $this;
    }

    /**
     * Get pagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPago
     */
    public function getPagoRel()
    {
        return $this->pagoRel;
    }

    /**
     * Set servicioCobrarRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $servicioCobrarRel
     *
     * @return RhuFacturaDetalle
     */
    public function setServicioCobrarRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $servicioCobrarRel = null)
    {
        $this->servicioCobrarRel = $servicioCobrarRel;

        return $this;
    }

    /**
     * Get servicioCobrarRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar
     */
    public function getServicioCobrarRel()
    {
        return $this->servicioCobrarRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuFacturaDetalle
     */
    public function setCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuFacturaDetalle
     */
    public function setEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set programacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel
     *
     * @return RhuFacturaDetalle
     */
    public function setProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel = null)
    {
        $this->programacionPagoRel = $programacionPagoRel;

        return $this;
    }

    /**
     * Get programacionPagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago
     */
    public function getProgramacionPagoRel()
    {
        return $this->programacionPagoRel;
    }
}
