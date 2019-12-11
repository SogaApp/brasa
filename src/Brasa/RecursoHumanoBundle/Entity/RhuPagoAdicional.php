<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_adicional")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoAdicionalRepository")
 */
class RhuPagoAdicional
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoAdicionalPk;         
        
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;       

    /**
     * @ORM\Column(name="tipo_adicional", type="integer", nullable=false)
     */    
    private $tipoAdicional;    
    
    /**
     * @ORM\Column(name="modalidad", type="integer", nullable=true)
     */    
    private $modalidad = 0;    

    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer", nullable=true)
     */    
    private $codigoPeriodoFk;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     
    
    /**
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0; 
    
    /**
     * @ORM\Column(name="valor", type="float")
     */
    private $valor = 0;     
    
    
    /**
     * @ORM\Column(name="codigo_programacion_pago_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionPagoFk;    
    
    /**     
     * @ORM\Column(name="permanente", type="boolean")
     */    
    private $permanente = 0;     

    /**     
     * @ORM\Column(name="aplica_dia_laborado", type="boolean")
     */    
    private $aplicaDiaLaborado = 0;                 
    
    /**     
     * @ORM\Column(name="aplica_dia_laborado_sin_descanso", type="boolean")
     */    
    private $aplicaDiaLaboradoSinDescanso = 0;     
    
    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */    
    private $detalle;                         
    
    /**     
     * @ORM\Column(name="prestacional", type="boolean")
     */    
    private $prestacional = 0;

    /**     
     * @ORM\Column(name="estado_inactivo", type="boolean")
     */    
    private $estadoInactivo = 0;    
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=true)
     */    
    private $fechaCreacion;     

    /**
     * @ORM\Column(name="fecha_ultima_edicion", type="datetime", nullable=true)
     */    
    private $fechaUltimaEdicion;
    
    /**
     * @ORM\Column(name="codigo_usuario_ultima_edicion", type="string", length=50, nullable=true)
     */    
    private $codigoUsuarioUltimaEdicion;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="pagosAdicionalesPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="pagosAdicionalesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionPago", inversedBy="pagosAdicionalesProgramacionPagoRel")
     * @ORM\JoinColumn(name="codigo_programacion_pago_fk", referencedColumnName="codigo_programacion_pago_pk")
     */
    protected $programacionPagoRel;    
    



    /**
     * Get codigoPagoAdicionalPk
     *
     * @return integer
     */
    public function getCodigoPagoAdicionalPk()
    {
        return $this->codigoPagoAdicionalPk;
    }

    /**
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuPagoAdicional
     */
    public function setCodigoPagoConceptoFk($codigoPagoConceptoFk)
    {
        $this->codigoPagoConceptoFk = $codigoPagoConceptoFk;

        return $this;
    }

    /**
     * Get codigoPagoConceptoFk
     *
     * @return integer
     */
    public function getCodigoPagoConceptoFk()
    {
        return $this->codigoPagoConceptoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuPagoAdicional
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
     * Set tipoAdicional
     *
     * @param integer $tipoAdicional
     *
     * @return RhuPagoAdicional
     */
    public function setTipoAdicional($tipoAdicional)
    {
        $this->tipoAdicional = $tipoAdicional;

        return $this;
    }

    /**
     * Get tipoAdicional
     *
     * @return integer
     */
    public function getTipoAdicional()
    {
        return $this->tipoAdicional;
    }

    /**
     * Set modalidad
     *
     * @param integer $modalidad
     *
     * @return RhuPagoAdicional
     */
    public function setModalidad($modalidad)
    {
        $this->modalidad = $modalidad;

        return $this;
    }

    /**
     * Get modalidad
     *
     * @return integer
     */
    public function getModalidad()
    {
        return $this->modalidad;
    }

    /**
     * Set codigoPeriodoFk
     *
     * @param integer $codigoPeriodoFk
     *
     * @return RhuPagoAdicional
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuPagoAdicional
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
     * Set cantidad
     *
     * @param float $cantidad
     *
     * @return RhuPagoAdicional
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return RhuPagoAdicional
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set codigoProgramacionPagoFk
     *
     * @param integer $codigoProgramacionPagoFk
     *
     * @return RhuPagoAdicional
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
     * Set permanente
     *
     * @param boolean $permanente
     *
     * @return RhuPagoAdicional
     */
    public function setPermanente($permanente)
    {
        $this->permanente = $permanente;

        return $this;
    }

    /**
     * Get permanente
     *
     * @return boolean
     */
    public function getPermanente()
    {
        return $this->permanente;
    }

    /**
     * Set aplicaDiaLaborado
     *
     * @param boolean $aplicaDiaLaborado
     *
     * @return RhuPagoAdicional
     */
    public function setAplicaDiaLaborado($aplicaDiaLaborado)
    {
        $this->aplicaDiaLaborado = $aplicaDiaLaborado;

        return $this;
    }

    /**
     * Get aplicaDiaLaborado
     *
     * @return boolean
     */
    public function getAplicaDiaLaborado()
    {
        return $this->aplicaDiaLaborado;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return RhuPagoAdicional
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
     * Set prestacional
     *
     * @param boolean $prestacional
     *
     * @return RhuPagoAdicional
     */
    public function setPrestacional($prestacional)
    {
        $this->prestacional = $prestacional;

        return $this;
    }

    /**
     * Get prestacional
     *
     * @return boolean
     */
    public function getPrestacional()
    {
        return $this->prestacional;
    }

    /**
     * Set estadoInactivo
     *
     * @param boolean $estadoInactivo
     *
     * @return RhuPagoAdicional
     */
    public function setEstadoInactivo($estadoInactivo)
    {
        $this->estadoInactivo = $estadoInactivo;

        return $this;
    }

    /**
     * Get estadoInactivo
     *
     * @return boolean
     */
    public function getEstadoInactivo()
    {
        return $this->estadoInactivo;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuPagoAdicional
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return RhuPagoAdicional
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaUltimaEdicion
     *
     * @param \DateTime $fechaUltimaEdicion
     *
     * @return RhuPagoAdicional
     */
    public function setFechaUltimaEdicion($fechaUltimaEdicion)
    {
        $this->fechaUltimaEdicion = $fechaUltimaEdicion;

        return $this;
    }

    /**
     * Get fechaUltimaEdicion
     *
     * @return \DateTime
     */
    public function getFechaUltimaEdicion()
    {
        return $this->fechaUltimaEdicion;
    }

    /**
     * Set pagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel
     *
     * @return RhuPagoAdicional
     */
    public function setPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel = null)
    {
        $this->pagoConceptoRel = $pagoConceptoRel;

        return $this;
    }

    /**
     * Get pagoConceptoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto
     */
    public function getPagoConceptoRel()
    {
        return $this->pagoConceptoRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuPagoAdicional
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
     * @return RhuPagoAdicional
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

    /**
     * Set codigoUsuarioUltimaEdicion
     *
     * @param string $codigoUsuarioUltimaEdicion
     *
     * @return RhuPagoAdicional
     */
    public function setCodigoUsuarioUltimaEdicion($codigoUsuarioUltimaEdicion)
    {
        $this->codigoUsuarioUltimaEdicion = $codigoUsuarioUltimaEdicion;

        return $this;
    }

    /**
     * Get codigoUsuarioUltimaEdicion
     *
     * @return string
     */
    public function getCodigoUsuarioUltimaEdicion()
    {
        return $this->codigoUsuarioUltimaEdicion;
    }

    /**
     * Set aplicaDiaLaboradoSinDescanso
     *
     * @param boolean $aplicaDiaLaboradoSinDescanso
     *
     * @return RhuPagoAdicional
     */
    public function setAplicaDiaLaboradoSinDescanso($aplicaDiaLaboradoSinDescanso)
    {
        $this->aplicaDiaLaboradoSinDescanso = $aplicaDiaLaboradoSinDescanso;

        return $this;
    }

    /**
     * Get aplicaDiaLaboradoSinDescanso
     *
     * @return boolean
     */
    public function getAplicaDiaLaboradoSinDescanso()
    {
        return $this->aplicaDiaLaboradoSinDescanso;
    }
}
