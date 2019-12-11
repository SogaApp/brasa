<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado_estudio_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoEstudioTipoRepository")
 */
class RhuEmpleadoEstudioTipo
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_estudio_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $empleadoEstudioTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;          
    
    /**     
     * @ORM\Column(name="validar_vencimiento", type="boolean")
     */    
    private $validarVencimiento = 0;        
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoEstudio", mappedBy="empleadoEstudioTipoRel")
     */
    protected $empleadosEstudiosEmpleadoEstudioTipoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="empleadoEstudioTipoRel")
     */
    protected $empleadosEmpleadoEstudioTipoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionRequisito", mappedBy="estudioTipoRel")
     */
    protected $seleccionesRequisitosEmpleadoEstudioTipoRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEstudiosEmpleadoEstudioTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosEmpleadoEstudioTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesRequisitosEmpleadoEstudioTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get empleadoEstudioTipoPk
     *
     * @return integer
     */
    public function getEmpleadoEstudioTipoPk()
    {
        return $this->empleadoEstudioTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEmpleadoEstudioTipo
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
     * Set validarVencimiento
     *
     * @param boolean $validarVencimiento
     *
     * @return RhuEmpleadoEstudioTipo
     */
    public function setValidarVencimiento($validarVencimiento)
    {
        $this->validarVencimiento = $validarVencimiento;

        return $this;
    }

    /**
     * Get validarVencimiento
     *
     * @return boolean
     */
    public function getValidarVencimiento()
    {
        return $this->validarVencimiento;
    }

    /**
     * Add empleadosEstudiosEmpleadoEstudioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEmpleadoEstudioTipoRel
     *
     * @return RhuEmpleadoEstudioTipo
     */
    public function addEmpleadosEstudiosEmpleadoEstudioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEmpleadoEstudioTipoRel)
    {
        $this->empleadosEstudiosEmpleadoEstudioTipoRel[] = $empleadosEstudiosEmpleadoEstudioTipoRel;

        return $this;
    }

    /**
     * Remove empleadosEstudiosEmpleadoEstudioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEmpleadoEstudioTipoRel
     */
    public function removeEmpleadosEstudiosEmpleadoEstudioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEmpleadoEstudioTipoRel)
    {
        $this->empleadosEstudiosEmpleadoEstudioTipoRel->removeElement($empleadosEstudiosEmpleadoEstudioTipoRel);
    }

    /**
     * Get empleadosEstudiosEmpleadoEstudioTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEstudiosEmpleadoEstudioTipoRel()
    {
        return $this->empleadosEstudiosEmpleadoEstudioTipoRel;
    }

    /**
     * Add empleadosEmpleadoEstudioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEmpleadoEstudioTipoRel
     *
     * @return RhuEmpleadoEstudioTipo
     */
    public function addEmpleadosEmpleadoEstudioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEmpleadoEstudioTipoRel)
    {
        $this->empleadosEmpleadoEstudioTipoRel[] = $empleadosEmpleadoEstudioTipoRel;

        return $this;
    }

    /**
     * Remove empleadosEmpleadoEstudioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEmpleadoEstudioTipoRel
     */
    public function removeEmpleadosEmpleadoEstudioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEmpleadoEstudioTipoRel)
    {
        $this->empleadosEmpleadoEstudioTipoRel->removeElement($empleadosEmpleadoEstudioTipoRel);
    }

    /**
     * Get empleadosEmpleadoEstudioTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEmpleadoEstudioTipoRel()
    {
        return $this->empleadosEmpleadoEstudioTipoRel;
    }

    /**
     * Add seleccionesRequisitosEmpleadoEstudioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosEmpleadoEstudioTipoRel
     *
     * @return RhuEmpleadoEstudioTipo
     */
    public function addSeleccionesRequisitosEmpleadoEstudioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosEmpleadoEstudioTipoRel)
    {
        $this->seleccionesRequisitosEmpleadoEstudioTipoRel[] = $seleccionesRequisitosEmpleadoEstudioTipoRel;

        return $this;
    }

    /**
     * Remove seleccionesRequisitosEmpleadoEstudioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosEmpleadoEstudioTipoRel
     */
    public function removeSeleccionesRequisitosEmpleadoEstudioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosEmpleadoEstudioTipoRel)
    {
        $this->seleccionesRequisitosEmpleadoEstudioTipoRel->removeElement($seleccionesRequisitosEmpleadoEstudioTipoRel);
    }

    /**
     * Get seleccionesRequisitosEmpleadoEstudioTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesRequisitosEmpleadoEstudioTipoRel()
    {
        return $this->seleccionesRequisitosEmpleadoEstudioTipoRel;
    }
}
