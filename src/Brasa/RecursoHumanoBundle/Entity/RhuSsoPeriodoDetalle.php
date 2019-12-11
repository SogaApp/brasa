<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sso_periodo_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSsoPeriodoDetalleRepository")
 */
class RhuSsoPeriodoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoDetallePk;   

    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer")
     */    
    private $codigoPeriodoFk;    
    
    /**
     * @ORM\Column(name="codigo_sucursal_fk", type="integer")
     */    
    private $codigoSucursalFk;
    
    /**
     * @ORM\Column(name="detalle", type="string", length=50)
     */    
    private $detalle;
    
    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = 0;
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = 0;
    
    /**     
     * @ORM\Column(name="estado_actualizado", type="boolean")
     */    
    private $estadoActualizado = 0;
    
    /**
     * @ORM\Column(name="numero_registros", type="integer", nullable=true)
     */    
    private $numeroRegistros;
    
    /**
     * @ORM\Column(name="numero_empleados", type="integer", nullable=true)
     */    
    private $numeroEmpleados;
    
    /**
     * @ORM\Column(name="total_cotizacion", type="float" , nullable=true)
     */
    private $totalCotizacion = 0;
    
    /**
     * @ORM\Column(name="total_ingreso_base_cotizacion_caja", type="float" , nullable=true)
     */
    private $totalIngresoBaseCotizacionCaja = 0;    
    
    /**
     * @ORM\Column(name="estado_pago_banco", type="boolean")
     */
    private $estadoPagoBanco = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoPeriodo", inversedBy="ssoPeriodosDetallesSsoPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $ssoPeriodoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoSucursal", inversedBy="ssoPeriodosDetallesSsoSucursalRel")
     * @ORM\JoinColumn(name="codigo_sucursal_fk", referencedColumnName="codigo_sucursal_pk")
     */
    protected $ssoSucursalRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSsoAporte", mappedBy="ssoPeriodoDetalleRel")
     */
    protected $ssoAportesSsoPeriodoDetalleRel; 

    /**
     * @ORM\OneToMany(targetEntity="RhuSsoPeriodoEmpleado", mappedBy="ssoPeriodoDetalleRel")
     */
    protected $ssoPeriodosEmpleadosSsoPeriodoDetalleRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuPagoBancoDetalle", mappedBy="ssoPeriodoDetalleRel")
     */
    protected $pagosBancosDetallesSsoPeriodoDetalleRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ssoAportesSsoPeriodoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ssoPeriodosEmpleadosSsoPeriodoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosBancosDetallesSsoPeriodoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPeriodoDetallePk
     *
     * @return integer
     */
    public function getCodigoPeriodoDetallePk()
    {
        return $this->codigoPeriodoDetallePk;
    }

    /**
     * Set codigoPeriodoFk
     *
     * @param integer $codigoPeriodoFk
     *
     * @return RhuSsoPeriodoDetalle
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
     * Set codigoSucursalFk
     *
     * @param integer $codigoSucursalFk
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setCodigoSucursalFk($codigoSucursalFk)
    {
        $this->codigoSucursalFk = $codigoSucursalFk;

        return $this;
    }

    /**
     * Get codigoSucursalFk
     *
     * @return integer
     */
    public function getCodigoSucursalFk()
    {
        return $this->codigoSucursalFk;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return RhuSsoPeriodoDetalle
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
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return RhuSsoPeriodoDetalle
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuSsoPeriodoDetalle
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
     * Set estadoActualizado
     *
     * @param boolean $estadoActualizado
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setEstadoActualizado($estadoActualizado)
    {
        $this->estadoActualizado = $estadoActualizado;

        return $this;
    }

    /**
     * Get estadoActualizado
     *
     * @return boolean
     */
    public function getEstadoActualizado()
    {
        return $this->estadoActualizado;
    }

    /**
     * Set numeroRegistros
     *
     * @param integer $numeroRegistros
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setNumeroRegistros($numeroRegistros)
    {
        $this->numeroRegistros = $numeroRegistros;

        return $this;
    }

    /**
     * Get numeroRegistros
     *
     * @return integer
     */
    public function getNumeroRegistros()
    {
        return $this->numeroRegistros;
    }

    /**
     * Set numeroEmpleados
     *
     * @param integer $numeroEmpleados
     *
     * @return RhuSsoPeriodoDetalle
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
     * Set totalCotizacion
     *
     * @param float $totalCotizacion
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setTotalCotizacion($totalCotizacion)
    {
        $this->totalCotizacion = $totalCotizacion;

        return $this;
    }

    /**
     * Get totalCotizacion
     *
     * @return float
     */
    public function getTotalCotizacion()
    {
        return $this->totalCotizacion;
    }

    /**
     * Set totalIngresoBaseCotizacionCaja
     *
     * @param float $totalIngresoBaseCotizacionCaja
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setTotalIngresoBaseCotizacionCaja($totalIngresoBaseCotizacionCaja)
    {
        $this->totalIngresoBaseCotizacionCaja = $totalIngresoBaseCotizacionCaja;

        return $this;
    }

    /**
     * Get totalIngresoBaseCotizacionCaja
     *
     * @return float
     */
    public function getTotalIngresoBaseCotizacionCaja()
    {
        return $this->totalIngresoBaseCotizacionCaja;
    }

    /**
     * Set estadoPagoBanco
     *
     * @param boolean $estadoPagoBanco
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setEstadoPagoBanco($estadoPagoBanco)
    {
        $this->estadoPagoBanco = $estadoPagoBanco;

        return $this;
    }

    /**
     * Get estadoPagoBanco
     *
     * @return boolean
     */
    public function getEstadoPagoBanco()
    {
        return $this->estadoPagoBanco;
    }

    /**
     * Set ssoPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo $ssoPeriodoRel
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setSsoPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo $ssoPeriodoRel = null)
    {
        $this->ssoPeriodoRel = $ssoPeriodoRel;

        return $this;
    }

    /**
     * Get ssoPeriodoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo
     */
    public function getSsoPeriodoRel()
    {
        return $this->ssoPeriodoRel;
    }

    /**
     * Set ssoSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal $ssoSucursalRel
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setSsoSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal $ssoSucursalRel = null)
    {
        $this->ssoSucursalRel = $ssoSucursalRel;

        return $this;
    }

    /**
     * Get ssoSucursalRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal
     */
    public function getSsoSucursalRel()
    {
        return $this->ssoSucursalRel;
    }

    /**
     * Add ssoAportesSsoPeriodoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesSsoPeriodoDetalleRel
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function addSsoAportesSsoPeriodoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesSsoPeriodoDetalleRel)
    {
        $this->ssoAportesSsoPeriodoDetalleRel[] = $ssoAportesSsoPeriodoDetalleRel;

        return $this;
    }

    /**
     * Remove ssoAportesSsoPeriodoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesSsoPeriodoDetalleRel
     */
    public function removeSsoAportesSsoPeriodoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesSsoPeriodoDetalleRel)
    {
        $this->ssoAportesSsoPeriodoDetalleRel->removeElement($ssoAportesSsoPeriodoDetalleRel);
    }

    /**
     * Get ssoAportesSsoPeriodoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoAportesSsoPeriodoDetalleRel()
    {
        return $this->ssoAportesSsoPeriodoDetalleRel;
    }

    /**
     * Add ssoPeriodosEmpleadosSsoPeriodoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosSsoPeriodoDetalleRel
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function addSsoPeriodosEmpleadosSsoPeriodoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosSsoPeriodoDetalleRel)
    {
        $this->ssoPeriodosEmpleadosSsoPeriodoDetalleRel[] = $ssoPeriodosEmpleadosSsoPeriodoDetalleRel;

        return $this;
    }

    /**
     * Remove ssoPeriodosEmpleadosSsoPeriodoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosSsoPeriodoDetalleRel
     */
    public function removeSsoPeriodosEmpleadosSsoPeriodoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosSsoPeriodoDetalleRel)
    {
        $this->ssoPeriodosEmpleadosSsoPeriodoDetalleRel->removeElement($ssoPeriodosEmpleadosSsoPeriodoDetalleRel);
    }

    /**
     * Get ssoPeriodosEmpleadosSsoPeriodoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoPeriodosEmpleadosSsoPeriodoDetalleRel()
    {
        return $this->ssoPeriodosEmpleadosSsoPeriodoDetalleRel;
    }

    /**
     * Add pagosBancosDetallesSsoPeriodoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesSsoPeriodoDetalleRel
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function addPagosBancosDetallesSsoPeriodoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesSsoPeriodoDetalleRel)
    {
        $this->pagosBancosDetallesSsoPeriodoDetalleRel[] = $pagosBancosDetallesSsoPeriodoDetalleRel;

        return $this;
    }

    /**
     * Remove pagosBancosDetallesSsoPeriodoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesSsoPeriodoDetalleRel
     */
    public function removePagosBancosDetallesSsoPeriodoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesSsoPeriodoDetalleRel)
    {
        $this->pagosBancosDetallesSsoPeriodoDetalleRel->removeElement($pagosBancosDetallesSsoPeriodoDetalleRel);
    }

    /**
     * Get pagosBancosDetallesSsoPeriodoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosBancosDetallesSsoPeriodoDetalleRel()
    {
        return $this->pagosBancosDetallesSsoPeriodoDetalleRel;
    }
}
