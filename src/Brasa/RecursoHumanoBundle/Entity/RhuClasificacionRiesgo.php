<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_clasificacion_riesgo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuClasificacionRiesgoRepository")
 */
class RhuClasificacionRiesgo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_clasificacion_riesgo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoClasificacionRiesgoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\Column(name="porcentaje", type="float")
     */
    private $porcentaje = 0;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="clasificacionRiesgoRel")
     */
    protected $empleadosClasificacionRiesgoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="clasificacionRiesgoRel")
     */
    protected $contratosClasificacionRiesgoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiContrato", mappedBy="clasificacionRiesgoRel")
     */
    protected $afiContratosClasificacionRiesgoRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosClasificacionRiesgoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoClasificacionRiesgoPk
     *
     * @return integer
     */
    public function getCodigoClasificacionRiesgoPk()
    {
        return $this->codigoClasificacionRiesgoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuClasificacionRiesgo
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
     * Add empleadosClasificacionRiesgoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosClasificacionRiesgoRel
     *
     * @return RhuClasificacionRiesgo
     */
    public function addEmpleadosClasificacionRiesgoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosClasificacionRiesgoRel)
    {
        $this->empleadosClasificacionRiesgoRel[] = $empleadosClasificacionRiesgoRel;

        return $this;
    }

    /**
     * Remove empleadosClasificacionRiesgoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosClasificacionRiesgoRel
     */
    public function removeEmpleadosClasificacionRiesgoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosClasificacionRiesgoRel)
    {
        $this->empleadosClasificacionRiesgoRel->removeElement($empleadosClasificacionRiesgoRel);
    }

    /**
     * Get empleadosClasificacionRiesgoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosClasificacionRiesgoRel()
    {
        return $this->empleadosClasificacionRiesgoRel;
    }

    /**
     * Set porcentaje
     *
     * @param float $porcentaje
     *
     * @return RhuClasificacionRiesgo
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return float
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Add contratosClasificacionRiesgoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosClasificacionRiesgoRel
     *
     * @return RhuClasificacionRiesgo
     */
    public function addContratosClasificacionRiesgoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosClasificacionRiesgoRel)
    {
        $this->contratosClasificacionRiesgoRel[] = $contratosClasificacionRiesgoRel;

        return $this;
    }

    /**
     * Remove contratosClasificacionRiesgoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosClasificacionRiesgoRel
     */
    public function removeContratosClasificacionRiesgoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosClasificacionRiesgoRel)
    {
        $this->contratosClasificacionRiesgoRel->removeElement($contratosClasificacionRiesgoRel);
    }

    /**
     * Get contratosClasificacionRiesgoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosClasificacionRiesgoRel()
    {
        return $this->contratosClasificacionRiesgoRel;
    }

    /**
     * Add afiContratosClasificacionRiesgoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosClasificacionRiesgoRel
     *
     * @return RhuClasificacionRiesgo
     */
    public function addAfiContratosClasificacionRiesgoRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosClasificacionRiesgoRel)
    {
        $this->afiContratosClasificacionRiesgoRel[] = $afiContratosClasificacionRiesgoRel;

        return $this;
    }

    /**
     * Remove afiContratosClasificacionRiesgoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosClasificacionRiesgoRel
     */
    public function removeAfiContratosClasificacionRiesgoRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosClasificacionRiesgoRel)
    {
        $this->afiContratosClasificacionRiesgoRel->removeElement($afiContratosClasificacionRiesgoRel);
    }

    /**
     * Get afiContratosClasificacionRiesgoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiContratosClasificacionRiesgoRel()
    {
        return $this->afiContratosClasificacionRiesgoRel;
    }
}
