<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_desempeno")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDesempenoRepository")
 */
class RhuDesempeno
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_desempeno_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDesempenoPk;                               
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk; 
    
    /**
     * @ORM\Column(name="dependencia_evaluado", type="string", length=80, nullable=true)
     */    
    private $dependenciaEvaluado;
    
    /**
     * @ORM\Column(name="jefe_evalua", type="string", length=80, nullable=true)
     */    
    private $jefeEvalua;
    
    /**
     * @ORM\Column(name="dependencia_evalua", type="string", length=80, nullable=true)
     */    
    private $dependenciaEvalua;
    
    /**
     * @ORM\Column(name="cargo_jefe_evalua", type="string", length=80, nullable=true)
     */    
    private $cargoJefeEvalua;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="observaciones", type="string", length=300, nullable=true)
     */    
    private $observaciones; 
    
    /**
     * @ORM\Column(name="aspectos_mejorar", type="string", length=300, nullable=true)
     */    
    private $aspectosMejorar;
    
    /**
     * @ORM\Column(name="area_profesional", type="float")
     */
    private $areaProfesional = 0;
    
    /**
     * @ORM\Column(name="compromiso", type="float")
     */
    private $compromiso = 0;
    
    /**
     * @ORM\Column(name="urbanidad", type="float")
     */
    private $urbanidad = 0;
    
    /**
     * @ORM\Column(name="valores", type="float")
     */
    private $valores = 0;
    
    /**
     * @ORM\Column(name="orientacion_cliente", type="float")
     */
    private $orientacionCliente = 0;
    
    /**
     * @ORM\Column(name="orientacion_resultados", type="float")
     */
    private $orientacionResultados = 0;
    
    /**
     * @ORM\Column(name="construccion_mantenimiento_relaciones", type="float")
     */
    private $construccionMantenimientoRelaciones = 0;
    
    /**
     * @ORM\Column(name="subtotal1", type="float")
     */
    private $subTotal1 = 0;
    
    /**
     * @ORM\Column(name="subtotal2", type="float")
     */
    private $subTotal2 = 0;
    
    /**
     * @ORM\Column(name="total_desempeno", type="float")
     */
    private $totalDesempeno = 0;
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;       
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = 0;
    
    /**     
     * @ORM\Column(name="inconsistencia", type="boolean")
     */    
    private $inconsistencia = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="desempenosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="desempenosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDesempenoDetalle", mappedBy="desempenoRel", cascade={"persist", "remove"})
     */
    protected $desempenosDetallesDesempenoRel;

    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->desempenosDetallesDesempenoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDesempenoPk
     *
     * @return integer
     */
    public function getCodigoDesempenoPk()
    {
        return $this->codigoDesempenoPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuDesempeno
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
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuDesempeno
     */
    public function setCodigoCargoFk($codigoCargoFk)
    {
        $this->codigoCargoFk = $codigoCargoFk;

        return $this;
    }

    /**
     * Get codigoCargoFk
     *
     * @return integer
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * Set dependenciaEvaluado
     *
     * @param string $dependenciaEvaluado
     *
     * @return RhuDesempeno
     */
    public function setDependenciaEvaluado($dependenciaEvaluado)
    {
        $this->dependenciaEvaluado = $dependenciaEvaluado;

        return $this;
    }

    /**
     * Get dependenciaEvaluado
     *
     * @return string
     */
    public function getDependenciaEvaluado()
    {
        return $this->dependenciaEvaluado;
    }

    /**
     * Set jefeEvalua
     *
     * @param string $jefeEvalua
     *
     * @return RhuDesempeno
     */
    public function setJefeEvalua($jefeEvalua)
    {
        $this->jefeEvalua = $jefeEvalua;

        return $this;
    }

    /**
     * Get jefeEvalua
     *
     * @return string
     */
    public function getJefeEvalua()
    {
        return $this->jefeEvalua;
    }

    /**
     * Set dependenciaEvalua
     *
     * @param string $dependenciaEvalua
     *
     * @return RhuDesempeno
     */
    public function setDependenciaEvalua($dependenciaEvalua)
    {
        $this->dependenciaEvalua = $dependenciaEvalua;

        return $this;
    }

    /**
     * Get dependenciaEvalua
     *
     * @return string
     */
    public function getDependenciaEvalua()
    {
        return $this->dependenciaEvalua;
    }

    /**
     * Set cargoJefeEvalua
     *
     * @param string $cargoJefeEvalua
     *
     * @return RhuDesempeno
     */
    public function setCargoJefeEvalua($cargoJefeEvalua)
    {
        $this->cargoJefeEvalua = $cargoJefeEvalua;

        return $this;
    }

    /**
     * Get cargoJefeEvalua
     *
     * @return string
     */
    public function getCargoJefeEvalua()
    {
        return $this->cargoJefeEvalua;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuDesempeno
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return RhuDesempeno
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set aspectosMejorar
     *
     * @param string $aspectosMejorar
     *
     * @return RhuDesempeno
     */
    public function setAspectosMejorar($aspectosMejorar)
    {
        $this->aspectosMejorar = $aspectosMejorar;

        return $this;
    }

    /**
     * Get aspectosMejorar
     *
     * @return string
     */
    public function getAspectosMejorar()
    {
        return $this->aspectosMejorar;
    }

    /**
     * Set areaProfesional
     *
     * @param float $areaProfesional
     *
     * @return RhuDesempeno
     */
    public function setAreaProfesional($areaProfesional)
    {
        $this->areaProfesional = $areaProfesional;

        return $this;
    }

    /**
     * Get areaProfesional
     *
     * @return float
     */
    public function getAreaProfesional()
    {
        return $this->areaProfesional;
    }

    /**
     * Set compromiso
     *
     * @param float $compromiso
     *
     * @return RhuDesempeno
     */
    public function setCompromiso($compromiso)
    {
        $this->compromiso = $compromiso;

        return $this;
    }

    /**
     * Get compromiso
     *
     * @return float
     */
    public function getCompromiso()
    {
        return $this->compromiso;
    }

    /**
     * Set urbanidad
     *
     * @param float $urbanidad
     *
     * @return RhuDesempeno
     */
    public function setUrbanidad($urbanidad)
    {
        $this->urbanidad = $urbanidad;

        return $this;
    }

    /**
     * Get urbanidad
     *
     * @return float
     */
    public function getUrbanidad()
    {
        return $this->urbanidad;
    }

    /**
     * Set valores
     *
     * @param float $valores
     *
     * @return RhuDesempeno
     */
    public function setValores($valores)
    {
        $this->valores = $valores;

        return $this;
    }

    /**
     * Get valores
     *
     * @return float
     */
    public function getValores()
    {
        return $this->valores;
    }

    /**
     * Set orientacionCliente
     *
     * @param float $orientacionCliente
     *
     * @return RhuDesempeno
     */
    public function setOrientacionCliente($orientacionCliente)
    {
        $this->orientacionCliente = $orientacionCliente;

        return $this;
    }

    /**
     * Get orientacionCliente
     *
     * @return float
     */
    public function getOrientacionCliente()
    {
        return $this->orientacionCliente;
    }

    /**
     * Set orientacionResultados
     *
     * @param float $orientacionResultados
     *
     * @return RhuDesempeno
     */
    public function setOrientacionResultados($orientacionResultados)
    {
        $this->orientacionResultados = $orientacionResultados;

        return $this;
    }

    /**
     * Get orientacionResultados
     *
     * @return float
     */
    public function getOrientacionResultados()
    {
        return $this->orientacionResultados;
    }

    /**
     * Set construccionMantenimientoRelaciones
     *
     * @param float $construccionMantenimientoRelaciones
     *
     * @return RhuDesempeno
     */
    public function setConstruccionMantenimientoRelaciones($construccionMantenimientoRelaciones)
    {
        $this->construccionMantenimientoRelaciones = $construccionMantenimientoRelaciones;

        return $this;
    }

    /**
     * Get construccionMantenimientoRelaciones
     *
     * @return float
     */
    public function getConstruccionMantenimientoRelaciones()
    {
        return $this->construccionMantenimientoRelaciones;
    }

    /**
     * Set subTotal1
     *
     * @param float $subTotal1
     *
     * @return RhuDesempeno
     */
    public function setSubTotal1($subTotal1)
    {
        $this->subTotal1 = $subTotal1;

        return $this;
    }

    /**
     * Get subTotal1
     *
     * @return float
     */
    public function getSubTotal1()
    {
        return $this->subTotal1;
    }

    /**
     * Set subTotal2
     *
     * @param float $subTotal2
     *
     * @return RhuDesempeno
     */
    public function setSubTotal2($subTotal2)
    {
        $this->subTotal2 = $subTotal2;

        return $this;
    }

    /**
     * Get subTotal2
     *
     * @return float
     */
    public function getSubTotal2()
    {
        return $this->subTotal2;
    }

    /**
     * Set totalDesempeno
     *
     * @param float $totalDesempeno
     *
     * @return RhuDesempeno
     */
    public function setTotalDesempeno($totalDesempeno)
    {
        $this->totalDesempeno = $totalDesempeno;

        return $this;
    }

    /**
     * Get totalDesempeno
     *
     * @return float
     */
    public function getTotalDesempeno()
    {
        return $this->totalDesempeno;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuDesempeno
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuDesempeno
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
     * Set inconsistencia
     *
     * @param boolean $inconsistencia
     *
     * @return RhuDesempeno
     */
    public function setInconsistencia($inconsistencia)
    {
        $this->inconsistencia = $inconsistencia;

        return $this;
    }

    /**
     * Get inconsistencia
     *
     * @return boolean
     */
    public function getInconsistencia()
    {
        return $this->inconsistencia;
    }

    /**
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuDesempeno
     */
    public function setCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel = null)
    {
        $this->cargoRel = $cargoRel;

        return $this;
    }

    /**
     * Get cargoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCargo
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuDesempeno
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
     * Add desempenosDetallesDesempenoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle $desempenosDetallesDesempenoRel
     *
     * @return RhuDesempeno
     */
    public function addDesempenosDetallesDesempenoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle $desempenosDetallesDesempenoRel)
    {
        $this->desempenosDetallesDesempenoRel[] = $desempenosDetallesDesempenoRel;

        return $this;
    }

    /**
     * Remove desempenosDetallesDesempenoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle $desempenosDetallesDesempenoRel
     */
    public function removeDesempenosDetallesDesempenoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle $desempenosDetallesDesempenoRel)
    {
        $this->desempenosDetallesDesempenoRel->removeElement($desempenosDetallesDesempenoRel);
    }

    /**
     * Get desempenosDetallesDesempenoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDesempenosDetallesDesempenoRel()
    {
        return $this->desempenosDetallesDesempenoRel;
    }
}
