<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_estudio_tipo_acreditacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEstudioTipoAcreditacionRepository")
 */
class RhuEstudioTipoAcreditacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_estudio_tipo_acreditacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEstudioTipoAcreditacionPk;
    
    /**
     * @ORM\Column(name="codigo_estudio_acreditacion", type="string", length=10, nullable=true)
     */    
    private $codigoEstudioAcreditacion;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="cargo", type="string", length=50, nullable=true)
     */    
    private $cargo; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoEstudio", mappedBy="estudioTipoAcreditacionRel")
     */
    protected $empleadosEstudiosEstudioTipoAcreditacionRel;


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEstudiosEstudioTipoAcreditacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEstudioTipoAcreditacionPk
     *
     * @return integer
     */
    public function getCodigoEstudioTipoAcreditacionPk()
    {
        return $this->codigoEstudioTipoAcreditacionPk;
    }

    /**
     * Set codigoEstudioAcreditacion
     *
     * @param string $codigoEstudioAcreditacion
     *
     * @return RhuEstudioTipoAcreditacion
     */
    public function setCodigoEstudioAcreditacion($codigoEstudioAcreditacion)
    {
        $this->codigoEstudioAcreditacion = $codigoEstudioAcreditacion;

        return $this;
    }

    /**
     * Get codigoEstudioAcreditacion
     *
     * @return string
     */
    public function getCodigoEstudioAcreditacion()
    {
        return $this->codigoEstudioAcreditacion;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEstudioTipoAcreditacion
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
     * Set cargo
     *
     * @param string $cargo
     *
     * @return RhuEstudioTipoAcreditacion
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Add empleadosEstudiosEstudioTipoAcreditacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEstudioTipoAcreditacionRel
     *
     * @return RhuEstudioTipoAcreditacion
     */
    public function addEmpleadosEstudiosEstudioTipoAcreditacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEstudioTipoAcreditacionRel)
    {
        $this->empleadosEstudiosEstudioTipoAcreditacionRel[] = $empleadosEstudiosEstudioTipoAcreditacionRel;

        return $this;
    }

    /**
     * Remove empleadosEstudiosEstudioTipoAcreditacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEstudioTipoAcreditacionRel
     */
    public function removeEmpleadosEstudiosEstudioTipoAcreditacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEstudioTipoAcreditacionRel)
    {
        $this->empleadosEstudiosEstudioTipoAcreditacionRel->removeElement($empleadosEstudiosEstudioTipoAcreditacionRel);
    }

    /**
     * Get empleadosEstudiosEstudioTipoAcreditacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEstudiosEstudioTipoAcreditacionRel()
    {
        return $this->empleadosEstudiosEstudioTipoAcreditacionRel;
    }
}
