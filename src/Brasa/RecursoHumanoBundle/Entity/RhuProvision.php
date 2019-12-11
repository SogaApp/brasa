<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_provision")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuProvisionRepository")
 */
class RhuProvision
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_provision_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProvisionPk;
    
    /**
     * @ORM\Column(name="codigo_provision_periodo_fk", type="integer", nullable=false)
     */    
    private $codigoProvisionPeriodoFk;               
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;      

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */    
    private $codigoContratoFk;    
    
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
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;     
    
    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean")
     */    
    private $estadoContabilizado = 0; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuProvisionPeriodo", inversedBy="provisionesProvisionPeriodoRel")
     * @ORM\JoinColumn(name="codigo_provision_periodo_fk", referencedColumnName="codigo_provision_periodo_pk")
     */
    protected $provisionPeriodoRel;     
        
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="provisionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="provisionesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;  
       

    /**
     * Get codigoProvisionPk
     *
     * @return integer
     */
    public function getCodigoProvisionPk()
    {
        return $this->codigoProvisionPk;
    }

    /**
     * Set codigoProvisionPeriodoFk
     *
     * @param integer $codigoProvisionPeriodoFk
     *
     * @return RhuProvision
     */
    public function setCodigoProvisionPeriodoFk($codigoProvisionPeriodoFk)
    {
        $this->codigoProvisionPeriodoFk = $codigoProvisionPeriodoFk;

        return $this;
    }

    /**
     * Get codigoProvisionPeriodoFk
     *
     * @return integer
     */
    public function getCodigoProvisionPeriodoFk()
    {
        return $this->codigoProvisionPeriodoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuProvision
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
     * @return RhuProvision
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
     * Set vrPension
     *
     * @param float $vrPension
     *
     * @return RhuProvision
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
     * @return RhuProvision
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
     * @return RhuProvision
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
     * @return RhuProvision
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
     * @return RhuProvision
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
     * @return RhuProvision
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
     * @return RhuProvision
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
     * @return RhuProvision
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
     * @return RhuProvision
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
     * @return RhuProvision
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
     * @return RhuProvision
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
     * @return RhuProvision
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
     * @return RhuProvision
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
     * Set estadoContabilizado
     *
     * @param boolean $estadoContabilizado
     *
     * @return RhuProvision
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
     * Set provisionPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProvisionPeriodo $provisionPeriodoRel
     *
     * @return RhuProvision
     */
    public function setProvisionPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProvisionPeriodo $provisionPeriodoRel = null)
    {
        $this->provisionPeriodoRel = $provisionPeriodoRel;

        return $this;
    }

    /**
     * Get provisionPeriodoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuProvisionPeriodo
     */
    public function getProvisionPeriodoRel()
    {
        return $this->provisionPeriodoRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuProvision
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
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuProvision
     */
    public function setContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuProvision
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
     * Set anio
     *
     * @param integer $anio
     *
     * @return RhuProvision
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
     * @return RhuProvision
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
