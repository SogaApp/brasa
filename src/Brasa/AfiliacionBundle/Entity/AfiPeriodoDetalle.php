<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_periodo_detalle")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiPeriodoDetalleRepository")
 */
class AfiPeriodoDetalle
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
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;        

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta; 

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;    

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="dias", type="integer")
     */    
    private $dias = 0;    
    
    /**
     * @ORM\Column(name="salario", type="float")
     */
    private $salario = 0;             

    /**
     * @ORM\Column(name="ibc", type="float")
     */
    private $ibc = 0;    
    
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
     * @ORM\Column(name="afiliacion", type="float")
     */
    private $afiliacion = 0;    
    
    /**
     * @ORM\Column(name="administracion", type="float")
     */
    private $administracion = 0;     

    /**
     * @ORM\Column(name="subtotal", type="float")
     */
    private $subtotal = 0;    
    
    /**
     * @ORM\Column(name="porcentaje_iva", type="float")
     */
    private $porcentajeIva = 0;    
    
    /**
     * @ORM\Column(name="iva", type="float")
     */
    private $iva = 0;
    
    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0;
    
    /**     
     * @ORM\Column(name="ingreso", type="boolean")
     */    
    private $ingreso = 0;

    /**     
     * @ORM\Column(name="retiro", type="boolean")
     */    
    private $retiro = 0;    
    
    /**
     * @ORM\Column(name="aportes_fondo_solidaridad_pensional_solidaridad", type="float")
     */
    private $aportesFondoSolidaridadPensionalSolidaridad = 0;    
    
    /**
     * @ORM\Column(name="aportes_fondo_solidaridad_pensional_subsistencia", type="float")
     */
    private $aportesFondoSolidaridadPensionalSubsistencia = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiPeriodo", inversedBy="periodosDetallesPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $periodoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiEmpleado", inversedBy="periodosDetallesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiContrato", inversedBy="periodosDetallesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;     
    
    

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
     * @return AfiPeriodoDetalle
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return AfiPeriodoDetalle
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
     * @return AfiPeriodoDetalle
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return AfiPeriodoDetalle
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
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return AfiPeriodoDetalle
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set dias
     *
     * @param integer $dias
     *
     * @return AfiPeriodoDetalle
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
     * Set salario
     *
     * @param float $salario
     *
     * @return AfiPeriodoDetalle
     */
    public function setSalario($salario)
    {
        $this->salario = $salario;

        return $this;
    }

    /**
     * Get salario
     *
     * @return float
     */
    public function getSalario()
    {
        return $this->salario;
    }

    /**
     * Set salud
     *
     * @param float $salud
     *
     * @return AfiPeriodoDetalle
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
     * @return AfiPeriodoDetalle
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
     * @return AfiPeriodoDetalle
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
     * @return AfiPeriodoDetalle
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
     * @return AfiPeriodoDetalle
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
     * @return AfiPeriodoDetalle
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
     * Set afiliacion
     *
     * @param float $afiliacion
     *
     * @return AfiPeriodoDetalle
     */
    public function setAfiliacion($afiliacion)
    {
        $this->afiliacion = $afiliacion;

        return $this;
    }

    /**
     * Get afiliacion
     *
     * @return float
     */
    public function getAfiliacion()
    {
        return $this->afiliacion;
    }

    /**
     * Set administracion
     *
     * @param float $administracion
     *
     * @return AfiPeriodoDetalle
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
     * @return AfiPeriodoDetalle
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
     * @return AfiPeriodoDetalle
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
     * @return AfiPeriodoDetalle
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
     * Set ingreso
     *
     * @param boolean $ingreso
     *
     * @return AfiPeriodoDetalle
     */
    public function setIngreso($ingreso)
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    /**
     * Get ingreso
     *
     * @return boolean
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * Set aportesFondoSolidaridadPensionalSolidaridad
     *
     * @param float $aportesFondoSolidaridadPensionalSolidaridad
     *
     * @return AfiPeriodoDetalle
     */
    public function setAportesFondoSolidaridadPensionalSolidaridad($aportesFondoSolidaridadPensionalSolidaridad)
    {
        $this->aportesFondoSolidaridadPensionalSolidaridad = $aportesFondoSolidaridadPensionalSolidaridad;

        return $this;
    }

    /**
     * Get aportesFondoSolidaridadPensionalSolidaridad
     *
     * @return float
     */
    public function getAportesFondoSolidaridadPensionalSolidaridad()
    {
        return $this->aportesFondoSolidaridadPensionalSolidaridad;
    }

    /**
     * Set aportesFondoSolidaridadPensionalSubsistencia
     *
     * @param float $aportesFondoSolidaridadPensionalSubsistencia
     *
     * @return AfiPeriodoDetalle
     */
    public function setAportesFondoSolidaridadPensionalSubsistencia($aportesFondoSolidaridadPensionalSubsistencia)
    {
        $this->aportesFondoSolidaridadPensionalSubsistencia = $aportesFondoSolidaridadPensionalSubsistencia;

        return $this;
    }

    /**
     * Get aportesFondoSolidaridadPensionalSubsistencia
     *
     * @return float
     */
    public function getAportesFondoSolidaridadPensionalSubsistencia()
    {
        return $this->aportesFondoSolidaridadPensionalSubsistencia;
    }

    /**
     * Set periodoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodoRel
     *
     * @return AfiPeriodoDetalle
     */
    public function setPeriodoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodoRel = null)
    {
        $this->periodoRel = $periodoRel;

        return $this;
    }

    /**
     * Get periodoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiPeriodo
     */
    public function getPeriodoRel()
    {
        return $this->periodoRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadoRel
     *
     * @return AfiPeriodoDetalle
     */
    public function setEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set contratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $contratoRel
     *
     * @return AfiPeriodoDetalle
     */
    public function setContratoRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * Set porcentajeIva
     *
     * @param float $porcentajeIva
     *
     * @return AfiPeriodoDetalle
     */
    public function setPorcentajeIva($porcentajeIva)
    {
        $this->porcentajeIva = $porcentajeIva;

        return $this;
    }

    /**
     * Get porcentajeIva
     *
     * @return float
     */
    public function getPorcentajeIva()
    {
        return $this->porcentajeIva;
    }

    /**
     * Set ibc
     *
     * @param float $ibc
     *
     * @return AfiPeriodoDetalle
     */
    public function setIbc($ibc)
    {
        $this->ibc = $ibc;

        return $this;
    }

    /**
     * Get ibc
     *
     * @return float
     */
    public function getIbc()
    {
        return $this->ibc;
    }

    /**
     * Set retiro
     *
     * @param boolean $retiro
     *
     * @return AfiPeriodoDetalle
     */
    public function setRetiro($retiro)
    {
        $this->retiro = $retiro;

        return $this;
    }

    /**
     * Get retiro
     *
     * @return boolean
     */
    public function getRetiro()
    {
        return $this->retiro;
    }
}
