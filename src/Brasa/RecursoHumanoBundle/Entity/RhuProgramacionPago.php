<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_programacion_pago")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuProgramacionPagoRepository")
 */
class RhuProgramacionPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionPagoPk;
    
    /**
     * @ORM\Column(name="codigo_pago_tipo_fk", type="integer", nullable=false)
     */    
    private $codigoPagoTipoFk;     
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;

    /**
     * @ORM\Column(name="fecha_hasta_real", type="date", nullable=true)
     */    
    private $fechaHastaReal;    
    
    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0;         
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;     
    
    /**
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = 0;    
    
    /**
     * @ORM\Column(name="estado_pagado", type="boolean")
     */    
    private $estadoPagado = 0;    
    
    /**
     * @ORM\Column(name="estado_pagado_banco", type="boolean")
     */    
    private $estadoPagadoBanco = 0;    
    
    /**
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = 0;            
    
    /**     
     * @ORM\Column(name="verificar_pagos_adicionales", type="boolean")
     */    
    private $verificarPagosAdicionales = 0;    

    /**     
     * @ORM\Column(name="verificar_incapacidades", type="boolean")
     */    
    private $verificarIncapacidades = 0;     
    
    /**
     * @ORM\Column(name="novedades_verificadas", type="boolean")
     */    
    private $novedadesVerificadas = 0;     
    
    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vr_neto = 0;    
    
    /**
     * @ORM\Column(name="empleados_generados", type="boolean")
     */    
    private $empleadosGenerados = 0;
    
    /**     
     * Cuando se deshace un periodo esta propiedad ayuda a que no vuelva a generar periodo nuevo
     * @ORM\Column(name="no_generar_periodo", type="boolean")
     */    
    private $noGeneraPeriodo = 0;     
    
    /**
     * @ORM\Column(name="numero_empleados", type="integer")
     */    
    private $numeroEmpleados = 0;    
    
    /**
     * @ORM\Column(name="inconsistencias", type="boolean")
     */    
    private $inconsistencias = 0;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\Column(name="mensaje_pago", type="string", length=400, nullable=true)
     */    
    private $mensaje_pago;    
    
    /**
     * @ORM\Column(name="codigo_soporte_pago_periodo_fk", type="integer", nullable=true)
     */    
    private $codigoSoportePagoPeriodoFk;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoTipo", inversedBy="programacionesPagosPagoTipoRel")
     * @ORM\JoinColumn(name="codigo_pago_tipo_fk", referencedColumnName="codigo_pago_tipo_pk")
     */
    protected $pagoTipoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="programacionesPagosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionPagoDetalle", mappedBy="programacionPagoRel")
     */
    protected $programacionesPagosDetallesProgramacionPagoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="programacionPagoRel")
     */
    protected $pagosProgramacionPagoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuServicioCobrar", mappedBy="programacionPagoRel")
     */
    protected $serviciosCobrarProgramacionPagoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoAdicional", mappedBy="programacionPagoRel")
     */
    protected $pagosAdicionalesProgramacionPagoRel;          

    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionPagoInconsistencia", mappedBy="programacionPagoRel")
     */
    protected $programacionesPagosInconsistenciasProgramacionPagoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuFacturaDetalle", mappedBy="programacionPagoRel")
     */
    protected $facturasDetallesProgramacionPagoRel;
  

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programacionesPagosDetallesProgramacionPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosProgramacionPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosCobrarProgramacionPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosAdicionalesProgramacionPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->programacionesPagosInconsistenciasProgramacionPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasDetallesProgramacionPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoProgramacionPagoPk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoPk()
    {
        return $this->codigoProgramacionPagoPk;
    }

    /**
     * Set codigoPagoTipoFk
     *
     * @param integer $codigoPagoTipoFk
     *
     * @return RhuProgramacionPago
     */
    public function setCodigoPagoTipoFk($codigoPagoTipoFk)
    {
        $this->codigoPagoTipoFk = $codigoPagoTipoFk;

        return $this;
    }

    /**
     * Get codigoPagoTipoFk
     *
     * @return integer
     */
    public function getCodigoPagoTipoFk()
    {
        return $this->codigoPagoTipoFk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuProgramacionPago
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
     * @return RhuProgramacionPago
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
     * Set fechaHastaReal
     *
     * @param \DateTime $fechaHastaReal
     *
     * @return RhuProgramacionPago
     */
    public function setFechaHastaReal($fechaHastaReal)
    {
        $this->fechaHastaReal = $fechaHastaReal;

        return $this;
    }

    /**
     * Get fechaHastaReal
     *
     * @return \DateTime
     */
    public function getFechaHastaReal()
    {
        return $this->fechaHastaReal;
    }

    /**
     * Set dias
     *
     * @param integer $dias
     *
     * @return RhuProgramacionPago
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuProgramacionPago
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
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return RhuProgramacionPago
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
     * Set estadoPagado
     *
     * @param boolean $estadoPagado
     *
     * @return RhuProgramacionPago
     */
    public function setEstadoPagado($estadoPagado)
    {
        $this->estadoPagado = $estadoPagado;

        return $this;
    }

    /**
     * Get estadoPagado
     *
     * @return boolean
     */
    public function getEstadoPagado()
    {
        return $this->estadoPagado;
    }

    /**
     * Set estadoPagadoBanco
     *
     * @param boolean $estadoPagadoBanco
     *
     * @return RhuProgramacionPago
     */
    public function setEstadoPagadoBanco($estadoPagadoBanco)
    {
        $this->estadoPagadoBanco = $estadoPagadoBanco;

        return $this;
    }

    /**
     * Get estadoPagadoBanco
     *
     * @return boolean
     */
    public function getEstadoPagadoBanco()
    {
        return $this->estadoPagadoBanco;
    }

    /**
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     *
     * @return RhuProgramacionPago
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
     * Set verificarPagosAdicionales
     *
     * @param boolean $verificarPagosAdicionales
     *
     * @return RhuProgramacionPago
     */
    public function setVerificarPagosAdicionales($verificarPagosAdicionales)
    {
        $this->verificarPagosAdicionales = $verificarPagosAdicionales;

        return $this;
    }

    /**
     * Get verificarPagosAdicionales
     *
     * @return boolean
     */
    public function getVerificarPagosAdicionales()
    {
        return $this->verificarPagosAdicionales;
    }

    /**
     * Set verificarIncapacidades
     *
     * @param boolean $verificarIncapacidades
     *
     * @return RhuProgramacionPago
     */
    public function setVerificarIncapacidades($verificarIncapacidades)
    {
        $this->verificarIncapacidades = $verificarIncapacidades;

        return $this;
    }

    /**
     * Get verificarIncapacidades
     *
     * @return boolean
     */
    public function getVerificarIncapacidades()
    {
        return $this->verificarIncapacidades;
    }

    /**
     * Set novedadesVerificadas
     *
     * @param boolean $novedadesVerificadas
     *
     * @return RhuProgramacionPago
     */
    public function setNovedadesVerificadas($novedadesVerificadas)
    {
        $this->novedadesVerificadas = $novedadesVerificadas;

        return $this;
    }

    /**
     * Get novedadesVerificadas
     *
     * @return boolean
     */
    public function getNovedadesVerificadas()
    {
        return $this->novedadesVerificadas;
    }

    /**
     * Set vrNeto
     *
     * @param float $vrNeto
     *
     * @return RhuProgramacionPago
     */
    public function setVrNeto($vrNeto)
    {
        $this->vr_neto = $vrNeto;

        return $this;
    }

    /**
     * Get vrNeto
     *
     * @return float
     */
    public function getVrNeto()
    {
        return $this->vr_neto;
    }

    /**
     * Set empleadosGenerados
     *
     * @param boolean $empleadosGenerados
     *
     * @return RhuProgramacionPago
     */
    public function setEmpleadosGenerados($empleadosGenerados)
    {
        $this->empleadosGenerados = $empleadosGenerados;

        return $this;
    }

    /**
     * Get empleadosGenerados
     *
     * @return boolean
     */
    public function getEmpleadosGenerados()
    {
        return $this->empleadosGenerados;
    }

    /**
     * Set noGeneraPeriodo
     *
     * @param boolean $noGeneraPeriodo
     *
     * @return RhuProgramacionPago
     */
    public function setNoGeneraPeriodo($noGeneraPeriodo)
    {
        $this->noGeneraPeriodo = $noGeneraPeriodo;

        return $this;
    }

    /**
     * Get noGeneraPeriodo
     *
     * @return boolean
     */
    public function getNoGeneraPeriodo()
    {
        return $this->noGeneraPeriodo;
    }

    /**
     * Set numeroEmpleados
     *
     * @param integer $numeroEmpleados
     *
     * @return RhuProgramacionPago
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
     * Set inconsistencias
     *
     * @param boolean $inconsistencias
     *
     * @return RhuProgramacionPago
     */
    public function setInconsistencias($inconsistencias)
    {
        $this->inconsistencias = $inconsistencias;

        return $this;
    }

    /**
     * Get inconsistencias
     *
     * @return boolean
     */
    public function getInconsistencias()
    {
        return $this->inconsistencias;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuProgramacionPago
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * Set mensajePago
     *
     * @param string $mensajePago
     *
     * @return RhuProgramacionPago
     */
    public function setMensajePago($mensajePago)
    {
        $this->mensaje_pago = $mensajePago;

        return $this;
    }

    /**
     * Get mensajePago
     *
     * @return string
     */
    public function getMensajePago()
    {
        return $this->mensaje_pago;
    }

    /**
     * Set pagoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoTipo $pagoTipoRel
     *
     * @return RhuProgramacionPago
     */
    public function setPagoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoTipo $pagoTipoRel = null)
    {
        $this->pagoTipoRel = $pagoTipoRel;

        return $this;
    }

    /**
     * Get pagoTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoTipo
     */
    public function getPagoTipoRel()
    {
        return $this->pagoTipoRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuProgramacionPago
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
     * Add programacionesPagosDetallesProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesProgramacionPagoRel
     *
     * @return RhuProgramacionPago
     */
    public function addProgramacionesPagosDetallesProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesProgramacionPagoRel)
    {
        $this->programacionesPagosDetallesProgramacionPagoRel[] = $programacionesPagosDetallesProgramacionPagoRel;

        return $this;
    }

    /**
     * Remove programacionesPagosDetallesProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesProgramacionPagoRel
     */
    public function removeProgramacionesPagosDetallesProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesProgramacionPagoRel)
    {
        $this->programacionesPagosDetallesProgramacionPagoRel->removeElement($programacionesPagosDetallesProgramacionPagoRel);
    }

    /**
     * Get programacionesPagosDetallesProgramacionPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesPagosDetallesProgramacionPagoRel()
    {
        return $this->programacionesPagosDetallesProgramacionPagoRel;
    }

    /**
     * Add pagosProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosProgramacionPagoRel
     *
     * @return RhuProgramacionPago
     */
    public function addPagosProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosProgramacionPagoRel)
    {
        $this->pagosProgramacionPagoRel[] = $pagosProgramacionPagoRel;

        return $this;
    }

    /**
     * Remove pagosProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosProgramacionPagoRel
     */
    public function removePagosProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosProgramacionPagoRel)
    {
        $this->pagosProgramacionPagoRel->removeElement($pagosProgramacionPagoRel);
    }

    /**
     * Get pagosProgramacionPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosProgramacionPagoRel()
    {
        return $this->pagosProgramacionPagoRel;
    }

    /**
     * Add serviciosCobrarProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarProgramacionPagoRel
     *
     * @return RhuProgramacionPago
     */
    public function addServiciosCobrarProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarProgramacionPagoRel)
    {
        $this->serviciosCobrarProgramacionPagoRel[] = $serviciosCobrarProgramacionPagoRel;

        return $this;
    }

    /**
     * Remove serviciosCobrarProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarProgramacionPagoRel
     */
    public function removeServiciosCobrarProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarProgramacionPagoRel)
    {
        $this->serviciosCobrarProgramacionPagoRel->removeElement($serviciosCobrarProgramacionPagoRel);
    }

    /**
     * Get serviciosCobrarProgramacionPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosCobrarProgramacionPagoRel()
    {
        return $this->serviciosCobrarProgramacionPagoRel;
    }

    /**
     * Add pagosAdicionalesProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesProgramacionPagoRel
     *
     * @return RhuProgramacionPago
     */
    public function addPagosAdicionalesProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesProgramacionPagoRel)
    {
        $this->pagosAdicionalesProgramacionPagoRel[] = $pagosAdicionalesProgramacionPagoRel;

        return $this;
    }

    /**
     * Remove pagosAdicionalesProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesProgramacionPagoRel
     */
    public function removePagosAdicionalesProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesProgramacionPagoRel)
    {
        $this->pagosAdicionalesProgramacionPagoRel->removeElement($pagosAdicionalesProgramacionPagoRel);
    }

    /**
     * Get pagosAdicionalesProgramacionPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosAdicionalesProgramacionPagoRel()
    {
        return $this->pagosAdicionalesProgramacionPagoRel;
    }

    /**
     * Add programacionesPagosInconsistenciasProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoInconsistencia $programacionesPagosInconsistenciasProgramacionPagoRel
     *
     * @return RhuProgramacionPago
     */
    public function addProgramacionesPagosInconsistenciasProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoInconsistencia $programacionesPagosInconsistenciasProgramacionPagoRel)
    {
        $this->programacionesPagosInconsistenciasProgramacionPagoRel[] = $programacionesPagosInconsistenciasProgramacionPagoRel;

        return $this;
    }

    /**
     * Remove programacionesPagosInconsistenciasProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoInconsistencia $programacionesPagosInconsistenciasProgramacionPagoRel
     */
    public function removeProgramacionesPagosInconsistenciasProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoInconsistencia $programacionesPagosInconsistenciasProgramacionPagoRel)
    {
        $this->programacionesPagosInconsistenciasProgramacionPagoRel->removeElement($programacionesPagosInconsistenciasProgramacionPagoRel);
    }

    /**
     * Get programacionesPagosInconsistenciasProgramacionPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesPagosInconsistenciasProgramacionPagoRel()
    {
        return $this->programacionesPagosInconsistenciasProgramacionPagoRel;
    }

    /**
     * Add facturasDetallesProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesProgramacionPagoRel
     *
     * @return RhuProgramacionPago
     */
    public function addFacturasDetallesProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesProgramacionPagoRel)
    {
        $this->facturasDetallesProgramacionPagoRel[] = $facturasDetallesProgramacionPagoRel;

        return $this;
    }

    /**
     * Remove facturasDetallesProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesProgramacionPagoRel
     */
    public function removeFacturasDetallesProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesProgramacionPagoRel)
    {
        $this->facturasDetallesProgramacionPagoRel->removeElement($facturasDetallesProgramacionPagoRel);
    }

    /**
     * Get facturasDetallesProgramacionPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesProgramacionPagoRel()
    {
        return $this->facturasDetallesProgramacionPagoRel;
    }

    /**
     * Set codigoSoportePagoPeriodoFk
     *
     * @param integer $codigoSoportePagoPeriodoFk
     *
     * @return RhuProgramacionPago
     */
    public function setCodigoSoportePagoPeriodoFk($codigoSoportePagoPeriodoFk)
    {
        $this->codigoSoportePagoPeriodoFk = $codigoSoportePagoPeriodoFk;

        return $this;
    }

    /**
     * Get codigoSoportePagoPeriodoFk
     *
     * @return integer
     */
    public function getCodigoSoportePagoPeriodoFk()
    {
        return $this->codigoSoportePagoPeriodoFk;
    }
}
