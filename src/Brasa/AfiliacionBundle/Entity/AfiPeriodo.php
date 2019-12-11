<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_periodo")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiPeriodoRepository")
 */
class AfiPeriodo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoPk;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="fecha_pago", type="date", nullable=true)
     */
    private $fechaPago;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */
    private $anio;

    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */
    private $mes;

    /**
     * @ORM\Column(name="anio_pago", type="integer", nullable=true)
     */
    private $anioPago;

    /**
     * @ORM\Column(name="mes_pago", type="integer", nullable=true)
     */
    private $mesPago;

    /**
     * @ORM\Column(name="estado_facturado", type="boolean")
     */
    private $estadoFacturado = false;

    /**
     * @ORM\Column(name="estado_generado", type="boolean")
     */
    private $estadoGenerado = false;

    /**
     * @ORM\Column(name="estado_pago_generado", type="boolean")
     */
    private $estadoPagoGenerado = false;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */
    private $estadoCerrado = false;

    /**
     * @ORM\Column(name="salud", type="float")
     */
    private $salud = 0;

    /**
     * @ORM\Column(name="pension", type="float")
     */
    private $pension = 0;

    /**
     * @ORM\Column(name="caja", type="float")
     */
    private $caja = 0;

    /**
     * @ORM\Column(name="riesgos", type="float")
     */
    private $riesgos = 0;

    /**
     * @ORM\Column(name="sena", type="float")
     */
    private $sena = 0;

    /**
     * @ORM\Column(name="icbf", type="float")
     */
    private $icbf = 0;

    /**
     * @ORM\Column(name="administracion", type="float")
     */
    private $administracion = 0;

    /**
     * @ORM\Column(name="subtotal", type="float")
     */
    private $subtotal = 0;

    /**
     * @ORM\Column(name="iva", type="float")
     */
    private $iva = 0;

    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0;

    /**
     * @ORM\Column(name="numero_empleados", type="integer", nullable=true)
     */
    private $numeroEmpleados = 0;

    /**
     * @ORM\Column(name="interes_mora", type="float")
     */
    private $interesMora = 0;

    /**
     * @ORM\Column(name="total_anterior", type="float")
     */
    private $totalAnterior = 0;

    /**
     * @ORM\Column(name="subtotal_anterior", type="float")
     */
    private $subtotalAnterior = 0;

    /**
     * @ORM\ManyToOne(targetEntity="AfiCliente", inversedBy="periodosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiPeriodoDetalle", mappedBy="periodoRel")
     */
    protected $periodosDetallesPeriodoRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiPeriodoDetallePago", mappedBy="periodoRel")
     */
    protected $periodosDetallesPagosPeriodoRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiPeriodoDetallePagoDetalle", mappedBy="periodoRel")
     */
    protected $periodosDetallesPagosDetallesPeriodoRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiFacturaDetalle", mappedBy="periodoRel")
     */
    protected $facturasDetallesPeriodoRel;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->periodosDetallesPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->periodosDetallesPagosPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasDetallesPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPeriodoPk
     *
     * @return integer
     */
    public function getCodigoPeriodoPk()
    {
        return $this->codigoPeriodoPk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return AfiPeriodo
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
     * @return AfiPeriodo
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
     * Set fechaPago
     *
     * @param \DateTime $fechaPago
     *
     * @return AfiPeriodo
     */
    public function setFechaPago($fechaPago)
    {
        $this->fechaPago = $fechaPago;

        return $this;
    }

    /**
     * Get fechaPago
     *
     * @return \DateTime
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return AfiPeriodo
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
     * Set anio
     *
     * @param integer $anio
     *
     * @return AfiPeriodo
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
     * @return AfiPeriodo
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
     * Set anioPago
     *
     * @param integer $anioPago
     *
     * @return AfiPeriodo
     */
    public function setAnioPago($anioPago)
    {
        $this->anioPago = $anioPago;

        return $this;
    }

    /**
     * Get anioPago
     *
     * @return integer
     */
    public function getAnioPago()
    {
        return $this->anioPago;
    }

    /**
     * Set mesPago
     *
     * @param integer $mesPago
     *
     * @return AfiPeriodo
     */
    public function setMesPago($mesPago)
    {
        $this->mesPago = $mesPago;

        return $this;
    }

    /**
     * Get mesPago
     *
     * @return integer
     */
    public function getMesPago()
    {
        return $this->mesPago;
    }

    /**
     * Set estadoFacturado
     *
     * @param boolean $estadoFacturado
     *
     * @return AfiPeriodo
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
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return AfiPeriodo
     */
    public function setEstadoGenerado($estadoGenerado)
    {
        $this->estadoGenerado = $estadoGenerado;

        return $this;
    }

    /**
     * Get estadoGenerado
     *
     * @return boolean
     */
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * Set estadoPagoGenerado
     *
     * @param boolean $estadoPagoGenerado
     *
     * @return AfiPeriodo
     */
    public function setEstadoPagoGenerado($estadoPagoGenerado)
    {
        $this->estadoPagoGenerado = $estadoPagoGenerado;

        return $this;
    }

    /**
     * Get estadoPagoGenerado
     *
     * @return boolean
     */
    public function getEstadoPagoGenerado()
    {
        return $this->estadoPagoGenerado;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return AfiPeriodo
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
     * Set salud
     *
     * @param float $salud
     *
     * @return AfiPeriodo
     */
    public function setSalud($salud)
    {
        $this->salud = $salud;

        return $this;
    }

    /**
     * Get salud
     *
     * @return float
     */
    public function getSalud()
    {
        return $this->salud;
    }

    /**
     * Set pension
     *
     * @param float $pension
     *
     * @return AfiPeriodo
     */
    public function setPension($pension)
    {
        $this->pension = $pension;

        return $this;
    }

    /**
     * Get pension
     *
     * @return float
     */
    public function getPension()
    {
        return $this->pension;
    }

    /**
     * Set caja
     *
     * @param float $caja
     *
     * @return AfiPeriodo
     */
    public function setCaja($caja)
    {
        $this->caja = $caja;

        return $this;
    }

    /**
     * Get caja
     *
     * @return float
     */
    public function getCaja()
    {
        return $this->caja;
    }

    /**
     * Set riesgos
     *
     * @param float $riesgos
     *
     * @return AfiPeriodo
     */
    public function setRiesgos($riesgos)
    {
        $this->riesgos = $riesgos;

        return $this;
    }

    /**
     * Get riesgos
     *
     * @return float
     */
    public function getRiesgos()
    {
        return $this->riesgos;
    }

    /**
     * Set sena
     *
     * @param float $sena
     *
     * @return AfiPeriodo
     */
    public function setSena($sena)
    {
        $this->sena = $sena;

        return $this;
    }

    /**
     * Get sena
     *
     * @return float
     */
    public function getSena()
    {
        return $this->sena;
    }

    /**
     * Set icbf
     *
     * @param float $icbf
     *
     * @return AfiPeriodo
     */
    public function setIcbf($icbf)
    {
        $this->icbf = $icbf;

        return $this;
    }

    /**
     * Get icbf
     *
     * @return float
     */
    public function getIcbf()
    {
        return $this->icbf;
    }

    /**
     * Set administracion
     *
     * @param float $administracion
     *
     * @return AfiPeriodo
     */
    public function setAdministracion($administracion)
    {
        $this->administracion = $administracion;

        return $this;
    }

    /**
     * Get administracion
     *
     * @return float
     */
    public function getAdministracion()
    {
        return $this->administracion;
    }

    /**
     * Set subtotal
     *
     * @param float $subtotal
     *
     * @return AfiPeriodo
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get subtotal
     *
     * @return float
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set iva
     *
     * @param float $iva
     *
     * @return AfiPeriodo
     */
    public function setIva($iva)
    {
        $this->iva = $iva;

        return $this;
    }

    /**
     * Get iva
     *
     * @return float
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return AfiPeriodo
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set numeroEmpleados
     *
     * @param integer $numeroEmpleados
     *
     * @return AfiPeriodo
     */
    public function setNumeroEmpleados($numeroEmpleados)
    {
        $this->numeroEmpleados = $numeroEmpleados;

        return $this;
    }

    /**
     * Get numeroEmpleados
     *
     * @return integer
     */
    public function getNumeroEmpleados()
    {
        return $this->numeroEmpleados;
    }

    /**
     * Set interesMora
     *
     * @param float $interesMora
     *
     * @return AfiPeriodo
     */
    public function setInteresMora($interesMora)
    {
        $this->interesMora = $interesMora;

        return $this;
    }

    /**
     * Get interesMora
     *
     * @return float
     */
    public function getInteresMora()
    {
        return $this->interesMora;
    }

    /**
     * Set totalAnterior
     *
     * @param float $totalAnterior
     *
     * @return AfiPeriodo
     */
    public function setTotalAnterior($totalAnterior)
    {
        $this->totalAnterior = $totalAnterior;

        return $this;
    }

    /**
     * Get totalAnterior
     *
     * @return float
     */
    public function getTotalAnterior()
    {
        return $this->totalAnterior;
    }

    /**
     * Set subtotalAnterior
     *
     * @param float $subtotalAnterior
     *
     * @return AfiPeriodo
     */
    public function setSubtotalAnterior($subtotalAnterior)
    {
        $this->subtotalAnterior = $subtotalAnterior;

        return $this;
    }

    /**
     * Get subtotalAnterior
     *
     * @return float
     */
    public function getSubtotalAnterior()
    {
        return $this->subtotalAnterior;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $clienteRel
     *
     * @return AfiPeriodo
     */
    public function setClienteRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Add periodosDetallesPeriodoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle $periodosDetallesPeriodoRel
     *
     * @return AfiPeriodo
     */
    public function addPeriodosDetallesPeriodoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle $periodosDetallesPeriodoRel)
    {
        $this->periodosDetallesPeriodoRel[] = $periodosDetallesPeriodoRel;

        return $this;
    }

    /**
     * Remove periodosDetallesPeriodoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle $periodosDetallesPeriodoRel
     */
    public function removePeriodosDetallesPeriodoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle $periodosDetallesPeriodoRel)
    {
        $this->periodosDetallesPeriodoRel->removeElement($periodosDetallesPeriodoRel);
    }

    /**
     * Get periodosDetallesPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodosDetallesPeriodoRel()
    {
        return $this->periodosDetallesPeriodoRel;
    }

    /**
     * Add periodosDetallesPagosPeriodoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosPeriodoRel
     *
     * @return AfiPeriodo
     */
    public function addPeriodosDetallesPagosPeriodoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosPeriodoRel)
    {
        $this->periodosDetallesPagosPeriodoRel[] = $periodosDetallesPagosPeriodoRel;

        return $this;
    }

    /**
     * Remove periodosDetallesPagosPeriodoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosPeriodoRel
     */
    public function removePeriodosDetallesPagosPeriodoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosPeriodoRel)
    {
        $this->periodosDetallesPagosPeriodoRel->removeElement($periodosDetallesPagosPeriodoRel);
    }

    /**
     * Get periodosDetallesPagosPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodosDetallesPagosPeriodoRel()
    {
        return $this->periodosDetallesPagosPeriodoRel;
    }

    /**
     * Add facturasDetallesPeriodoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle $facturasDetallesPeriodoRel
     *
     * @return AfiPeriodo
     */
    public function addFacturasDetallesPeriodoRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle $facturasDetallesPeriodoRel)
    {
        $this->facturasDetallesPeriodoRel[] = $facturasDetallesPeriodoRel;

        return $this;
    }

    /**
     * Remove facturasDetallesPeriodoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle $facturasDetallesPeriodoRel
     */
    public function removeFacturasDetallesPeriodoRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle $facturasDetallesPeriodoRel)
    {
        $this->facturasDetallesPeriodoRel->removeElement($facturasDetallesPeriodoRel);
    }

    /**
     * Get facturasDetallesPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesPeriodoRel()
    {
        return $this->facturasDetallesPeriodoRel;
    }

    /**
     * Add periodosDetallesPagosDetallesPeriodoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesPeriodoRel
     *
     * @return AfiPeriodo
     */
    public function addPeriodosDetallesPagosDetallesPeriodoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesPeriodoRel)
    {
        $this->periodosDetallesPagosDetallesPeriodoRel[] = $periodosDetallesPagosDetallesPeriodoRel;

        return $this;
    }

    /**
     * Remove periodosDetallesPagosDetallesPeriodoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesPeriodoRel
     */
    public function removePeriodosDetallesPagosDetallesPeriodoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesPeriodoRel)
    {
        $this->periodosDetallesPagosDetallesPeriodoRel->removeElement($periodosDetallesPagosDetallesPeriodoRel);
    }

    /**
     * Get periodosDetallesPagosDetallesPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodosDetallesPagosDetallesPeriodoRel()
    {
        return $this->periodosDetallesPagosDetallesPeriodoRel;
    }
}
