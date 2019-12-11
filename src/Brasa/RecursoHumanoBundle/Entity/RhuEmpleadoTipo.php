<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoTipoRepository")
 */
class RhuEmpleadoTipo
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;          
    
    /**
     * 1 - Administrativo
     * 2 - Operativo
     * 3 - Comercial
     * @ORM\Column(name="tipo", type="integer", nullable=true)
     */
    private $tipo = 0;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="empleadoTipoRel")
     */
    protected $empleadosEmpleadoTipoRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEmpleadoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEmpleadoTipoPk
     *
     * @return integer
     */
    public function getCodigoEmpleadoTipoPk()
    {
        return $this->codigoEmpleadoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEmpleadoTipo
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
     * Add empleadosEmpleadoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEmpleadoTipoRel
     *
     * @return RhuEmpleadoTipo
     */
    public function addEmpleadosEmpleadoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEmpleadoTipoRel)
    {
        $this->empleadosEmpleadoTipoRel[] = $empleadosEmpleadoTipoRel;

        return $this;
    }

    /**
     * Remove empleadosEmpleadoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEmpleadoTipoRel
     */
    public function removeEmpleadosEmpleadoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEmpleadoTipoRel)
    {
        $this->empleadosEmpleadoTipoRel->removeElement($empleadosEmpleadoTipoRel);
    }

    /**
     * Get empleadosEmpleadoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEmpleadoTipoRel()
    {
        return $this->empleadosEmpleadoTipoRel;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return RhuEmpleadoTipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
