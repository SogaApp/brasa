<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_provision_periodo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuProvisionPeriodoRepository")
 */
class RhuProvisionPeriodo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_provision_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProvisionPeriodoPk;        
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;

    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */    
    private $anio;    
    
    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */    
    private $mes;    
    
    /**
     * @ORM\Column(name="vr_pension", type="float")
     */
    private $vrPension = 0;        

    /**
     * @ORM\Column(name="vr_salud", type="float")
     */
    private $vrSalud = 0;
    
    /**
     * @ORM\Column(name="vr_riesgos", type="float")
     */
    private $vrRiesgos = 0;     
    
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
     * @ORM\Column(name="vr_intereses_cesantias", type="float")
     */
    private $vrInteresesCesantias = 0;    
    
    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */
    private $vrVacaciones = 0;           
    
    /**
     * @ORM\Column(name="vr_primas", type="float")
     */
    private $vrPrimas = 0;     
    
    /**
     * @ORM\Column(name="vr_indemnizacion", type="float")
     */
    private $vrIndemnizacion = 0;                                    
    
    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", type="float")
     */
    private $vrIngresoBaseCotizacion = 0;    

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion", type="float")
     */
    private $vrIngresoBasePrestacion = 0;    
    
    /**
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = 0;    
    
    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = 0;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuProvision", mappedBy="provisionPeriodoRel")
     */
    protected $provisionesProvisionPeriodoRel;      

    /**
     * Get codigoProvisionPeriodoPk
     *
     * @return integer
     */
    public function getCodigoProvisionPeriodoPk()
    {
        return $this->codigoProvisionPeriodoPk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuProvisionPeriodo
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
     * @return RhuProvisionPeriodo
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
     * Constructor
     */
    public function __construct()
    {
        $this->provisionesProvisionPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add provisionesProvisionPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProvision $provisionesProvisionPeriodoRel
     *
     * @return RhuProvisionPeriodo
     */
    public function addProvisionesProvisionPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProvision $provisionesProvisionPeriodoRel)
    {
        $this->provisionesProvisionPeriodoRel[] = $provisionesProvisionPeriodoRel;

        return $this;
    }

    /**
     * Remove provisionesProvisionPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProvision $provisionesProvisionPeriodoRel
     */
    public function removeProvisionesProvisionPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProvision $provisionesProvisionPeriodoRel)
    {
        $this->provisionesProvisionPeriodoRel->removeElement($provisionesProvisionPeriodoRel);
    }

    /**
     * Get provisionesProvisionPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProvisionesProvisionPeriodoRel()
    {
        return $this->provisionesProvisionPeriodoRel;
    }

    /**
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return RhuProvisionPeriodo
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
     * Set vrPension
     *
     * @param float $vrPension
     *
     * @return RhuProvisionPeriodo
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
     * Set vrSalud
     *
     * @param float $vrSalud
     *
     * @return RhuProvisionPeriodo
     */
    public function setVrSalud($vrSalud)
    {
        $this->vrSalud = $vrSalud;

        return $this;
    }

    /**
     * Get vrSalud
     *
     * @return float
     */
    public function getVrSalud()
    {
        return $this->vrSalud;
    }

    /**
     * Set vrRiesgos
     *
     * @param float $vrRiesgos
     *
     * @return RhuProvisionPeriodo
     */
    public function setVrRiesgos($vrRiesgos)
    {
        $this->vrRiesgos = $vrRiesgos;

        return $this;
    }

    /**
     * Get vrRiesgos
     *
     * @return float
     */
    public function getVrRiesgos()
    {
        return $this->vrRiesgos;
    }

    /**
     * Set vrCaja
     *
     * @param float $vrCaja
     *
     * @return RhuProvisionPeriodo
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
     * @return RhuProvisionPeriodo
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
     * @return RhuProvisionPeriodo
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
     * @return RhuProvisionPeriodo
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
     * Set vrInteresesCesantias
     *
     * @param float $vrInteresesCesantias
     *
     * @return RhuProvisionPeriodo
     */
    public function setVrInteresesCesantias($vrInteresesCesantias)
    {
        $this->vrInteresesCesantias = $vrInteresesCesantias;

        return $this;
    }

    /**
     * Get vrInteresesCesantias
     *
     * @return float
     */
    public function getVrInteresesCesantias()
    {
        return $this->vrInteresesCesantias;
    }

    /**
     * Set vrVacaciones
     *
     * @param float $vrVacaciones
     *
     * @return RhuProvisionPeriodo
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
     * Set vrPrimas
     *
     * @param float $vrPrimas
     *
     * @return RhuProvisionPeriodo
     */
    public function setVrPrimas($vrPrimas)
    {
        $this->vrPrimas = $vrPrimas;

        return $this;
    }

    /**
     * Get vrPrimas
     *
     * @return float
     */
    public function getVrPrimas()
    {
        return $this->vrPrimas;
    }

    /**
     * Set vrIndemnizacion
     *
     * @param float $vrIndemnizacion
     *
     * @return RhuProvisionPeriodo
     */
    public function setVrIndemnizacion($vrIndemnizacion)
    {
        $this->vrIndemnizacion = $vrIndemnizacion;

        return $this;
    }

    /**
     * Get vrIndemnizacion
     *
     * @return float
     */
    public function getVrIndemnizacion()
    {
        return $this->vrIndemnizacion;
    }

    /**
     * Set vrIngresoBaseCotizacion
     *
     * @param float $vrIngresoBaseCotizacion
     *
     * @return RhuProvisionPeriodo
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
     * Set vrIngresoBasePrestacion
     *
     * @param float $vrIngresoBasePrestacion
     *
     * @return RhuProvisionPeriodo
     */
    public function setVrIngresoBasePrestacion($vrIngresoBasePrestacion)
    {
        $this->vrIngresoBasePrestacion = $vrIngresoBasePrestacion;

        return $this;
    }

    /**
     * Get vrIngresoBasePrestacion
     *
     * @return float
     */
    public function getVrIngresoBasePrestacion()
    {
        return $this->vrIngresoBasePrestacion;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuProvisionPeriodo
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
     * Set anio
     *
     * @param integer $anio
     *
     * @return RhuProvisionPeriodo
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
     * @return RhuProvisionPeriodo
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
}
