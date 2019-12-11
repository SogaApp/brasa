<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado_informacion_interna_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoInformacionInternaTipoRepository")
 */
class RhuEmpleadoInformacionInternaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_informacion_interna_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoInformacionInternaTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=250, nullable=true)
     */    
    private $nombre;
    
    /**     
     * @ORM\Column(name="accion", type="boolean")
     */    
    private $accion = 0;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoInformacionInterna", mappedBy="empleadoInformacionInternaTipoRel")
     */
    protected $EmpleadosInformacionesInternasEmpleadoInformacionInternaTipoRel;
      
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->EmpleadosInformacionesInternasEmpleadoInformacionInternaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEmpleadoInformacionInternaTipoPk
     *
     * @return integer
     */
    public function getCodigoEmpleadoInformacionInternaTipoPk()
    {
        return $this->codigoEmpleadoInformacionInternaTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEmpleadoInformacionInternaTipo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set accion
     *
     * @param boolean $accion
     *
     * @return RhuEmpleadoInformacionInternaTipo
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;

        return $this;
    }

    /**
     * Get accion
     *
     * @return boolean
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * Add empleadosInformacionesInternasEmpleadoInformacionInternaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInterna $empleadosInformacionesInternasEmpleadoInformacionInternaTipoRel
     *
     * @return RhuEmpleadoInformacionInternaTipo
     */
    public function addEmpleadosInformacionesInternasEmpleadoInformacionInternaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInterna $empleadosInformacionesInternasEmpleadoInformacionInternaTipoRel)
    {
        $this->EmpleadosInformacionesInternasEmpleadoInformacionInternaTipoRel[] = $empleadosInformacionesInternasEmpleadoInformacionInternaTipoRel;

        return $this;
    }

    /**
     * Remove empleadosInformacionesInternasEmpleadoInformacionInternaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInterna $empleadosInformacionesInternasEmpleadoInformacionInternaTipoRel
     */
    public function removeEmpleadosInformacionesInternasEmpleadoInformacionInternaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInterna $empleadosInformacionesInternasEmpleadoInformacionInternaTipoRel)
    {
        $this->EmpleadosInformacionesInternasEmpleadoInformacionInternaTipoRel->removeElement($empleadosInformacionesInternasEmpleadoInformacionInternaTipoRel);
    }

    /**
     * Get empleadosInformacionesInternasEmpleadoInformacionInternaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosInformacionesInternasEmpleadoInformacionInternaTipoRel()
    {
        return $this->EmpleadosInformacionesInternasEmpleadoInformacionInternaTipoRel;
    }
}
