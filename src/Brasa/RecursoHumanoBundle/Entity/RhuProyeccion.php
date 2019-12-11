<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_proyeccion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuProyeccionRepository")
 */
class RhuProyeccion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_proyeccion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProyeccionPk;
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;     
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;      

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */    
    private $codigoContratoFk;        

    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0;     

    /**
     * @ORM\Column(name="dias_ausentismo", type="integer")
     */
    private $diasAusentismo = 0;     
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;     
    
    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */
    private $vrVacaciones = 0;         
    
    /**
     * @ORM\Column(name="dias_prima", type="integer")
     */
    private $diasPrima = 0;    
    
    /**
     * @ORM\Column(name="fecha_desde_prima", type="date", nullable=true)
     */    
    private $fechaDesdePrima;            
    
    /**
     * @ORM\Column(name="vr_primas", type="float")
     */
    private $vrPrimas = 0;     

    /**
     * @ORM\Column(name="vr_primas_real", type="float")
     */
    private $vrPrimasReal = 0; 
    
    /**
     * @ORM\Column(name="vr_salario_promedio_primas", type="float")
     */
    private $vrSalarioPromedioPrimas = 0;     
    
    /**
     * @ORM\Column(name="vr_salario_promedio_primas_real", type="float")
     */
    private $vrSalarioPromedioPrimasReal = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_primas", type="float")
     */
    private $porcentajePrimas = 0;     

    /**
     * @ORM\Column(name="diferencia_primas", type="float")
     */
    private $vrDiferenciaPrimas = 0;
    
    /**
     * @ORM\Column(name="dias_ausentismo_primas", type="integer")
     */
    private $diasAusentismoPrimas = 0;     
    
    /**
     * @ORM\Column(name="dias_cesantias", type="integer")
     */
    private $diasCesantias = 0;    
    
    /**
     * @ORM\Column(name="fecha_desde_cesantias", type="date", nullable=true)
     */    
    private $fechaDesdeCesantias;    
    
    /**
     * @ORM\Column(name="vr_cesantias", type="float")
     */
    private $vrCesantias = 0;         

    /**
     * @ORM\Column(name="vr_cesantias_real", type="float")
     */
    private $vrCesantiasReal = 0;
    
    /**
     * @ORM\Column(name="vr_intereses_cesantias", type="float")
     */
    private $vrInteresesCesantias = 0;         

    /**
     * @ORM\Column(name="vr_intereses_cesantias_real", type="float")
     */
    private $vrInteresesCesantiasReal = 0;    
    
    /**
     * @ORM\Column(name="vr_diferencia_intereses_cesantias", type="float")
     */
    private $vrDiferenciaInteresesCesantias = 0;    
    
    /**
     * @ORM\Column(name="vr_salario_promedio_cesantias", type="float")
     */
    private $vrSalarioPromedioCesantias = 0;    
    
    /**
     * @ORM\Column(name="vr_salario_promedio_cesantias_real", type="float")
     */
    private $vrSalarioPromedioCesantiasReal = 0;    
    
    /**
     * @ORM\Column(name="porcentaje_cesantias", type="float")
     */
    private $porcentajeCesantias = 0;    

    /**
     * @ORM\Column(name="diferencia_cesantias", type="float")
     */
    private $vrDiferenciaCesantias = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="proyeccionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="proyeccionesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;     



    /**
     * Get codigoProyeccionPk
     *
     * @return integer
     */
    public function getCodigoProyeccionPk()
    {
        return $this->codigoProyeccionPk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuProyeccion
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
     * @return RhuProyeccion
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
     * @return RhuProyeccion
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
     * @return RhuProyeccion
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
     * @return RhuProyeccion
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
     * Set diasAusentismo
     *
     * @param integer $diasAusentismo
     *
     * @return RhuProyeccion
     */
    public function setDiasAusentismo($diasAusentismo)
    {
        $this->diasAusentismo = $diasAusentismo;

        return $this;
    }

    /**
     * Get diasAusentismo
     *
     * @return integer
     */
    public function getDiasAusentismo()
    {
        return $this->diasAusentismo;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuProyeccion
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
     * Set vrVacaciones
     *
     * @param float $vrVacaciones
     *
     * @return RhuProyeccion
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
     * Set diasPrima
     *
     * @param integer $diasPrima
     *
     * @return RhuProyeccion
     */
    public function setDiasPrima($diasPrima)
    {
        $this->diasPrima = $diasPrima;

        return $this;
    }

    /**
     * Get diasPrima
     *
     * @return integer
     */
    public function getDiasPrima()
    {
        return $this->diasPrima;
    }

    /**
     * Set fechaDesdePrima
     *
     * @param \DateTime $fechaDesdePrima
     *
     * @return RhuProyeccion
     */
    public function setFechaDesdePrima($fechaDesdePrima)
    {
        $this->fechaDesdePrima = $fechaDesdePrima;

        return $this;
    }

    /**
     * Get fechaDesdePrima
     *
     * @return \DateTime
     */
    public function getFechaDesdePrima()
    {
        return $this->fechaDesdePrima;
    }

    /**
     * Set vrPrimas
     *
     * @param float $vrPrimas
     *
     * @return RhuProyeccion
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
     * Set vrSalarioPromedioPrimas
     *
     * @param float $vrSalarioPromedioPrimas
     *
     * @return RhuProyeccion
     */
    public function setVrSalarioPromedioPrimas($vrSalarioPromedioPrimas)
    {
        $this->vrSalarioPromedioPrimas = $vrSalarioPromedioPrimas;

        return $this;
    }

    /**
     * Get vrSalarioPromedioPrimas
     *
     * @return float
     */
    public function getVrSalarioPromedioPrimas()
    {
        return $this->vrSalarioPromedioPrimas;
    }

    /**
     * Set vrSalarioPromedioPrimasReal
     *
     * @param float $vrSalarioPromedioPrimasReal
     *
     * @return RhuProyeccion
     */
    public function setVrSalarioPromedioPrimasReal($vrSalarioPromedioPrimasReal)
    {
        $this->vrSalarioPromedioPrimasReal = $vrSalarioPromedioPrimasReal;

        return $this;
    }

    /**
     * Get vrSalarioPromedioPrimasReal
     *
     * @return float
     */
    public function getVrSalarioPromedioPrimasReal()
    {
        return $this->vrSalarioPromedioPrimasReal;
    }

    /**
     * Set porcentajePrimas
     *
     * @param float $porcentajePrimas
     *
     * @return RhuProyeccion
     */
    public function setPorcentajePrimas($porcentajePrimas)
    {
        $this->porcentajePrimas = $porcentajePrimas;

        return $this;
    }

    /**
     * Get porcentajePrimas
     *
     * @return float
     */
    public function getPorcentajePrimas()
    {
        return $this->porcentajePrimas;
    }

    /**
     * Set vrDiferenciaPrimas
     *
     * @param float $vrDiferenciaPrimas
     *
     * @return RhuProyeccion
     */
    public function setVrDiferenciaPrimas($vrDiferenciaPrimas)
    {
        $this->vrDiferenciaPrimas = $vrDiferenciaPrimas;

        return $this;
    }

    /**
     * Get vrDiferenciaPrimas
     *
     * @return float
     */
    public function getVrDiferenciaPrimas()
    {
        return $this->vrDiferenciaPrimas;
    }

    /**
     * Set diasCesantias
     *
     * @param integer $diasCesantias
     *
     * @return RhuProyeccion
     */
    public function setDiasCesantias($diasCesantias)
    {
        $this->diasCesantias = $diasCesantias;

        return $this;
    }

    /**
     * Get diasCesantias
     *
     * @return integer
     */
    public function getDiasCesantias()
    {
        return $this->diasCesantias;
    }

    /**
     * Set fechaDesdeCesantias
     *
     * @param \DateTime $fechaDesdeCesantias
     *
     * @return RhuProyeccion
     */
    public function setFechaDesdeCesantias($fechaDesdeCesantias)
    {
        $this->fechaDesdeCesantias = $fechaDesdeCesantias;

        return $this;
    }

    /**
     * Get fechaDesdeCesantias
     *
     * @return \DateTime
     */
    public function getFechaDesdeCesantias()
    {
        return $this->fechaDesdeCesantias;
    }

    /**
     * Set vrCesantias
     *
     * @param float $vrCesantias
     *
     * @return RhuProyeccion
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
     * @return RhuProyeccion
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
     * Set vrSalarioPromedioCesantias
     *
     * @param float $vrSalarioPromedioCesantias
     *
     * @return RhuProyeccion
     */
    public function setVrSalarioPromedioCesantias($vrSalarioPromedioCesantias)
    {
        $this->vrSalarioPromedioCesantias = $vrSalarioPromedioCesantias;

        return $this;
    }

    /**
     * Get vrSalarioPromedioCesantias
     *
     * @return float
     */
    public function getVrSalarioPromedioCesantias()
    {
        return $this->vrSalarioPromedioCesantias;
    }

    /**
     * Set vrSalarioPromedioCesantiasReal
     *
     * @param float $vrSalarioPromedioCesantiasReal
     *
     * @return RhuProyeccion
     */
    public function setVrSalarioPromedioCesantiasReal($vrSalarioPromedioCesantiasReal)
    {
        $this->vrSalarioPromedioCesantiasReal = $vrSalarioPromedioCesantiasReal;

        return $this;
    }

    /**
     * Get vrSalarioPromedioCesantiasReal
     *
     * @return float
     */
    public function getVrSalarioPromedioCesantiasReal()
    {
        return $this->vrSalarioPromedioCesantiasReal;
    }

    /**
     * Set porcentajeCesantias
     *
     * @param float $porcentajeCesantias
     *
     * @return RhuProyeccion
     */
    public function setPorcentajeCesantias($porcentajeCesantias)
    {
        $this->porcentajeCesantias = $porcentajeCesantias;

        return $this;
    }

    /**
     * Get porcentajeCesantias
     *
     * @return float
     */
    public function getPorcentajeCesantias()
    {
        return $this->porcentajeCesantias;
    }

    /**
     * Set vrDiferenciaCesantias
     *
     * @param float $vrDiferenciaCesantias
     *
     * @return RhuProyeccion
     */
    public function setVrDiferenciaCesantias($vrDiferenciaCesantias)
    {
        $this->vrDiferenciaCesantias = $vrDiferenciaCesantias;

        return $this;
    }

    /**
     * Get vrDiferenciaCesantias
     *
     * @return float
     */
    public function getVrDiferenciaCesantias()
    {
        return $this->vrDiferenciaCesantias;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuProyeccion
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
     * @return RhuProyeccion
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
     * Set vrPrimasReal
     *
     * @param float $vrPrimasReal
     *
     * @return RhuProyeccion
     */
    public function setVrPrimasReal($vrPrimasReal)
    {
        $this->vrPrimasReal = $vrPrimasReal;

        return $this;
    }

    /**
     * Get vrPrimasReal
     *
     * @return float
     */
    public function getVrPrimasReal()
    {
        return $this->vrPrimasReal;
    }

    /**
     * Set vrCesantiasReal
     *
     * @param float $vrCesantiasReal
     *
     * @return RhuProyeccion
     */
    public function setVrCesantiasReal($vrCesantiasReal)
    {
        $this->vrCesantiasReal = $vrCesantiasReal;

        return $this;
    }

    /**
     * Get vrCesantiasReal
     *
     * @return float
     */
    public function getVrCesantiasReal()
    {
        return $this->vrCesantiasReal;
    }

    /**
     * Set vrInteresesCesantiasReal
     *
     * @param float $vrInteresesCesantiasReal
     *
     * @return RhuProyeccion
     */
    public function setVrInteresesCesantiasReal($vrInteresesCesantiasReal)
    {
        $this->vrInteresesCesantiasReal = $vrInteresesCesantiasReal;

        return $this;
    }

    /**
     * Get vrInteresesCesantiasReal
     *
     * @return float
     */
    public function getVrInteresesCesantiasReal()
    {
        return $this->vrInteresesCesantiasReal;
    }

    /**
     * Set vrDiferenciaInteresesCesantias
     *
     * @param float $vrDiferenciaInteresesCesantias
     *
     * @return RhuProyeccion
     */
    public function setVrDiferenciaInteresesCesantias($vrDiferenciaInteresesCesantias)
    {
        $this->vrDiferenciaInteresesCesantias = $vrDiferenciaInteresesCesantias;

        return $this;
    }

    /**
     * Get vrDiferenciaInteresesCesantias
     *
     * @return float
     */
    public function getVrDiferenciaInteresesCesantias()
    {
        return $this->vrDiferenciaInteresesCesantias;
    }

    /**
     * Set diasAusentismoPrimas
     *
     * @param integer $diasAusentismoPrimas
     *
     * @return RhuProyeccion
     */
    public function setDiasAusentismoPrimas($diasAusentismoPrimas)
    {
        $this->diasAusentismoPrimas = $diasAusentismoPrimas;

        return $this;
    }

    /**
     * Get diasAusentismoPrimas
     *
     * @return integer
     */
    public function getDiasAusentismoPrimas()
    {
        return $this->diasAusentismoPrimas;
    }
}
