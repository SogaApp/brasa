<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sso_periodo_empleado")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSsoPeriodoEmpleadoRepository")
 */
class RhuSsoPeriodoEmpleado
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_empleado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoEmpleadoPk;   
    
    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer")
     */    
    private $codigoPeriodoFk;    

    /**
     * @ORM\Column(name="codigo_periodo_detalle_fk", type="integer")
     */    
    private $codigoPeriodoDetalleFk;     
    
    /**
     * @ORM\Column(name="codigo_sucursal_fk", type="integer")
     */    
    private $codigoSucursalFk; 

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
     * @ORM\Column(name="vr_salario", type="float")
     */    
    private $vrSalario = 0;

    /**
     * @ORM\Column(name="vr_suplementario", type="float")
     */    
    private $vrSuplementario = 0;    
    
    /**
     * @ORM\Column(name="ibc", type="float")
     */    
    private $Ibc = 0;    
    
    /**
     * @ORM\Column(name="vrVacaciones", type="float")
     */    
    private $vrVacaciones = 0;    
    
    /**
     * @ORM\Column(name="ingreso", type="string", length=1)
     */
    private $ingreso = ' ';    

    /**
     * @ORM\Column(name="retiro", type="string", length=1)
     */
    private $retiro = ' ';    
    
    /**
     * @ORM\Column(name="salario_integral", type="string", length=1)
     */
    private $salarioIntegral = ' ';    
    
    /**
     * @ORM\Column(name="variacion_transitoria_salario", type="string", length=1)
     */
    private $variacionTransitoriaSalario = ' ';    
    
    /**
     * @ORM\Column(name="dias_licencia", type="integer")
     */    
    private $diasLicencia = 0;    
    
    /**
     * @ORM\Column(name="dias_incapacidad_general", type="integer")
     */    
    private $diasIncapacidadGeneral = 0;    

    /**
     * @ORM\Column(name="dias_licencia_maternidad", type="integer")
     */    
    private $diasLicenciaMaternidad = 0;    
    
    /**
     * @ORM\Column(name="dias_incapacidad_laboral", type="integer")
     */    
    private $diasIncapacidadLaboral = 0;    

    /**
     * @ORM\Column(name="dias_vacaciones", type="integer")
     */    
    private $diasVacaciones = 0;    
    
    /**
     * @ORM\Column(name="tarifa_pension", type="float")
     */    
    private $tarifaPension = 0;    
    
    /**
     * @ORM\Column(name="tarifa_riesgos", type="float")
     */    
    private $tarifaRiesgos = 0;    
    
    /**
     * @ORM\Column(name="codigo_entidad_pension_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadPensionPertenece;    
    
    /**
     * @ORM\Column(name="codigo_entidad_salud_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadSaludPertenece;    
    
    /**
     * @ORM\Column(name="codigo_entidad_caja_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadCajaPertenece;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoPeriodo", inversedBy="ssoPeriodosEmpleadosSsoPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $ssoPeriodoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoPeriodoDetalle", inversedBy="ssoPeriodosEmpleadosSsoPeriodoDetalleRel")
     * @ORM\JoinColumn(name="codigo_periodo_detalle_fk", referencedColumnName="codigo_periodo_detalle_pk")
     */
    protected $ssoPeriodoDetalleRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoSucursal", inversedBy="ssoPeriodosEmpleadosSsoSucursalRel")
     * @ORM\JoinColumn(name="codigo_sucursal_fk", referencedColumnName="codigo_sucursal_pk")
     */
    protected $ssoSucursalRel;       
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="ssoPeriodosEmpleadosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;      

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="ssoPeriodosEmpleadosContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;     




    /**
     * Get codigoPeriodoEmpleadoPk
     *
     * @return integer
     */
    public function getCodigoPeriodoEmpleadoPk()
    {
        return $this->codigoPeriodoEmpleadoPk;
    }

    /**
     * Set codigoPeriodoFk
     *
     * @param integer $codigoPeriodoFk
     *
     * @return RhuSsoPeriodoEmpleado
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
     * Set codigoPeriodoDetalleFk
     *
     * @param integer $codigoPeriodoDetalleFk
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setCodigoPeriodoDetalleFk($codigoPeriodoDetalleFk)
    {
        $this->codigoPeriodoDetalleFk = $codigoPeriodoDetalleFk;

        return $this;
    }

    /**
     * Get codigoPeriodoDetalleFk
     *
     * @return integer
     */
    public function getCodigoPeriodoDetalleFk()
    {
        return $this->codigoPeriodoDetalleFk;
    }

    /**
     * Set codigoSucursalFk
     *
     * @param integer $codigoSucursalFk
     *
     * @return RhuSsoPeriodoEmpleado
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuSsoPeriodoEmpleado
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
     * @return RhuSsoPeriodoEmpleado
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
     * @return RhuSsoPeriodoEmpleado
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
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuSsoPeriodoEmpleado
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
     * Set vrSuplementario
     *
     * @param float $vrSuplementario
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setVrSuplementario($vrSuplementario)
    {
        $this->vrSuplementario = $vrSuplementario;

        return $this;
    }

    /**
     * Get vrSuplementario
     *
     * @return float
     */
    public function getVrSuplementario()
    {
        return $this->vrSuplementario;
    }

    /**
     * Set ingreso
     *
     * @param string $ingreso
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setIngreso($ingreso)
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    /**
     * Get ingreso
     *
     * @return string
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * Set retiro
     *
     * @param string $retiro
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setRetiro($retiro)
    {
        $this->retiro = $retiro;

        return $this;
    }

    /**
     * Get retiro
     *
     * @return string
     */
    public function getRetiro()
    {
        return $this->retiro;
    }

    /**
     * Set salarioIntegral
     *
     * @param string $salarioIntegral
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setSalarioIntegral($salarioIntegral)
    {
        $this->salarioIntegral = $salarioIntegral;

        return $this;
    }

    /**
     * Get salarioIntegral
     *
     * @return string
     */
    public function getSalarioIntegral()
    {
        return $this->salarioIntegral;
    }

    /**
     * Set diasLicencia
     *
     * @param integer $diasLicencia
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setDiasLicencia($diasLicencia)
    {
        $this->diasLicencia = $diasLicencia;

        return $this;
    }

    /**
     * Get diasLicencia
     *
     * @return integer
     */
    public function getDiasLicencia()
    {
        return $this->diasLicencia;
    }

    /**
     * Set diasIncapacidadGeneral
     *
     * @param integer $diasIncapacidadGeneral
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setDiasIncapacidadGeneral($diasIncapacidadGeneral)
    {
        $this->diasIncapacidadGeneral = $diasIncapacidadGeneral;

        return $this;
    }

    /**
     * Get diasIncapacidadGeneral
     *
     * @return integer
     */
    public function getDiasIncapacidadGeneral()
    {
        return $this->diasIncapacidadGeneral;
    }

    /**
     * Set diasLicenciaMaternidad
     *
     * @param integer $diasLicenciaMaternidad
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setDiasLicenciaMaternidad($diasLicenciaMaternidad)
    {
        $this->diasLicenciaMaternidad = $diasLicenciaMaternidad;

        return $this;
    }

    /**
     * Get diasLicenciaMaternidad
     *
     * @return integer
     */
    public function getDiasLicenciaMaternidad()
    {
        return $this->diasLicenciaMaternidad;
    }

    /**
     * Set diasIncapacidadLaboral
     *
     * @param integer $diasIncapacidadLaboral
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setDiasIncapacidadLaboral($diasIncapacidadLaboral)
    {
        $this->diasIncapacidadLaboral = $diasIncapacidadLaboral;

        return $this;
    }

    /**
     * Get diasIncapacidadLaboral
     *
     * @return integer
     */
    public function getDiasIncapacidadLaboral()
    {
        return $this->diasIncapacidadLaboral;
    }

    /**
     * Set diasVacaciones
     *
     * @param integer $diasVacaciones
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setDiasVacaciones($diasVacaciones)
    {
        $this->diasVacaciones = $diasVacaciones;

        return $this;
    }

    /**
     * Get diasVacaciones
     *
     * @return integer
     */
    public function getDiasVacaciones()
    {
        return $this->diasVacaciones;
    }

    /**
     * Set tarifaPension
     *
     * @param float $tarifaPension
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setTarifaPension($tarifaPension)
    {
        $this->tarifaPension = $tarifaPension;

        return $this;
    }

    /**
     * Get tarifaPension
     *
     * @return float
     */
    public function getTarifaPension()
    {
        return $this->tarifaPension;
    }

    /**
     * Set tarifaRiesgos
     *
     * @param float $tarifaRiesgos
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setTarifaRiesgos($tarifaRiesgos)
    {
        $this->tarifaRiesgos = $tarifaRiesgos;

        return $this;
    }

    /**
     * Get tarifaRiesgos
     *
     * @return float
     */
    public function getTarifaRiesgos()
    {
        return $this->tarifaRiesgos;
    }

    /**
     * Set codigoEntidadPensionPertenece
     *
     * @param string $codigoEntidadPensionPertenece
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setCodigoEntidadPensionPertenece($codigoEntidadPensionPertenece)
    {
        $this->codigoEntidadPensionPertenece = $codigoEntidadPensionPertenece;

        return $this;
    }

    /**
     * Get codigoEntidadPensionPertenece
     *
     * @return string
     */
    public function getCodigoEntidadPensionPertenece()
    {
        return $this->codigoEntidadPensionPertenece;
    }

    /**
     * Set codigoEntidadSaludPertenece
     *
     * @param string $codigoEntidadSaludPertenece
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setCodigoEntidadSaludPertenece($codigoEntidadSaludPertenece)
    {
        $this->codigoEntidadSaludPertenece = $codigoEntidadSaludPertenece;

        return $this;
    }

    /**
     * Get codigoEntidadSaludPertenece
     *
     * @return string
     */
    public function getCodigoEntidadSaludPertenece()
    {
        return $this->codigoEntidadSaludPertenece;
    }

    /**
     * Set codigoEntidadCajaPertenece
     *
     * @param string $codigoEntidadCajaPertenece
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setCodigoEntidadCajaPertenece($codigoEntidadCajaPertenece)
    {
        $this->codigoEntidadCajaPertenece = $codigoEntidadCajaPertenece;

        return $this;
    }

    /**
     * Get codigoEntidadCajaPertenece
     *
     * @return string
     */
    public function getCodigoEntidadCajaPertenece()
    {
        return $this->codigoEntidadCajaPertenece;
    }

    /**
     * Set ssoPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo $ssoPeriodoRel
     *
     * @return RhuSsoPeriodoEmpleado
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
     * Set ssoPeriodoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodoDetalleRel
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setSsoPeriodoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodoDetalleRel = null)
    {
        $this->ssoPeriodoDetalleRel = $ssoPeriodoDetalleRel;

        return $this;
    }

    /**
     * Get ssoPeriodoDetalleRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle
     */
    public function getSsoPeriodoDetalleRel()
    {
        return $this->ssoPeriodoDetalleRel;
    }

    /**
     * Set ssoSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal $ssoSucursalRel
     *
     * @return RhuSsoPeriodoEmpleado
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
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuSsoPeriodoEmpleado
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
     * @return RhuSsoPeriodoEmpleado
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
     * Set ibc
     *
     * @param float $ibc
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setIbc($ibc)
    {
        $this->Ibc = $ibc;

        return $this;
    }

    /**
     * Get ibc
     *
     * @return float
     */
    public function getIbc()
    {
        return $this->Ibc;
    }

    /**
     * Set vrVacaciones
     *
     * @param float $vrVacaciones
     *
     * @return RhuSsoPeriodoEmpleado
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
     * Set variacionTransitoriaSalario
     *
     * @param string $variacionTransitoriaSalario
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setVariacionTransitoriaSalario($variacionTransitoriaSalario)
    {
        $this->variacionTransitoriaSalario = $variacionTransitoriaSalario;

        return $this;
    }

    /**
     * Get variacionTransitoriaSalario
     *
     * @return string
     */
    public function getVariacionTransitoriaSalario()
    {
        return $this->variacionTransitoriaSalario;
    }
}
