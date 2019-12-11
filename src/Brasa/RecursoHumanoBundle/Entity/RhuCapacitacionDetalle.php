<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_capacitacion_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCapacitacionDetalleRepository")
 */
class RhuCapacitacionDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_capacitacion_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCapacitacionDetallePk;                    
    
    /**
     * @ORM\Column(name="codigo_capacitacion_fk", type="integer", nullable=true)
     */    
    private $codigoCapacitacionFk;   

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false)
     */         
    private $numeroIdentificacion;
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */    
    private $nombreCorto;
    
    /**     
     * @ORM\Column(name="asistencia", type="boolean")
     */    
    private $asistencia = false;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="evaluacion", type="string", length=80, nullable=true)
     */    
    private $evaluacion;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCapacitacion", inversedBy="capacitacionesDetallesCapacitacionRel")
     * @ORM\JoinColumn(name="codigo_capacitacion_fk", referencedColumnName="codigo_capacitacion_pk")
     */
    protected $capacitacionRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="capacitacionesDetallesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;




    /**
     * Get codigoCapacitacionDetallePk
     *
     * @return integer
     */
    public function getCodigoCapacitacionDetallePk()
    {
        return $this->codigoCapacitacionDetallePk;
    }

    /**
     * Set codigoCapacitacionFk
     *
     * @param integer $codigoCapacitacionFk
     *
     * @return RhuCapacitacionDetalle
     */
    public function setCodigoCapacitacionFk($codigoCapacitacionFk)
    {
        $this->codigoCapacitacionFk = $codigoCapacitacionFk;

        return $this;
    }

    /**
     * Get codigoCapacitacionFk
     *
     * @return integer
     */
    public function getCodigoCapacitacionFk()
    {
        return $this->codigoCapacitacionFk;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuCapacitacionDetalle
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuCapacitacionDetalle
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set asistencia
     *
     * @param boolean $asistencia
     *
     * @return RhuCapacitacionDetalle
     */
    public function setAsistencia($asistencia)
    {
        $this->asistencia = $asistencia;

        return $this;
    }

    /**
     * Get asistencia
     *
     * @return boolean
     */
    public function getAsistencia()
    {
        return $this->asistencia;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuCapacitacionDetalle
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
     * Set capacitacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion $capacitacionRel
     *
     * @return RhuCapacitacionDetalle
     */
    public function setCapacitacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion $capacitacionRel = null)
    {
        $this->capacitacionRel = $capacitacionRel;

        return $this;
    }

    /**
     * Get capacitacionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion
     */
    public function getCapacitacionRel()
    {
        return $this->capacitacionRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuCapacitacionDetalle
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
     * Set evaluacion
     *
     * @param string $evaluacion
     *
     * @return RhuCapacitacionDetalle
     */
    public function setEvaluacion($evaluacion)
    {
        $this->evaluacion = $evaluacion;

        return $this;
    }

    /**
     * Get evaluacion
     *
     * @return string
     */
    public function getEvaluacion()
    {
        return $this->evaluacion;
    }
}
