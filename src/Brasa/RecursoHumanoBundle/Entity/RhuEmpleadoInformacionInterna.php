<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado_informacion_interna")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoInformacionInternaRepository")
 */
class RhuEmpleadoInformacionInterna
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_informacion_interna_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoInformacionInternaPk;
    
    /**
     * @ORM\Column(name="codigo_empleado_informacion_interna_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoInformacionInternaTipoFk;
      
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;
      
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios; 
      
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="empleadosInformacionesInternasEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel; 

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleadoInformacionInternaTipo", inversedBy="EmpleadosInformacionesInternasEmpleadoInformacionInternaTipoRel")
     * @ORM\JoinColumn(name="codigo_empleado_informacion_interna_tipo_fk", referencedColumnName="codigo_empleado_informacion_interna_tipo_pk")
     */
    protected $empleadoInformacionInternaTipoRel;
    
    

    /**
     * Get codigoEmpleadoInformacionInternaPk
     *
     * @return integer
     */
    public function getCodigoEmpleadoInformacionInternaPk()
    {
        return $this->codigoEmpleadoInformacionInternaPk;
    }

    /**
     * Set codigoEmpleadoInformacionInternaTipoFk
     *
     * @param integer $codigoEmpleadoInformacionInternaTipoFk
     *
     * @return RhuEmpleadoInformacionInterna
     */
    public function setCodigoEmpleadoInformacionInternaTipoFk($codigoEmpleadoInformacionInternaTipoFk)
    {
        $this->codigoEmpleadoInformacionInternaTipoFk = $codigoEmpleadoInformacionInternaTipoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoInformacionInternaTipoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoInformacionInternaTipoFk()
    {
        return $this->codigoEmpleadoInformacionInternaTipoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuEmpleadoInformacionInterna
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuEmpleadoInformacionInterna
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuEmpleadoInformacionInterna
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuEmpleadoInformacionInterna
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
     * Set empleadoInformacionInternaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInternaTipo $empleadoInformacionInternaTipoRel
     *
     * @return RhuEmpleadoInformacionInterna
     */
    public function setEmpleadoInformacionInternaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInternaTipo $empleadoInformacionInternaTipoRel = null)
    {
        $this->empleadoInformacionInternaTipoRel = $empleadoInformacionInternaTipoRel;

        return $this;
    }

    /**
     * Get empleadoInformacionInternaTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInternaTipo
     */
    public function getEmpleadoInformacionInternaTipoRel()
    {
        return $this->empleadoInformacionInternaTipoRel;
    }
}
