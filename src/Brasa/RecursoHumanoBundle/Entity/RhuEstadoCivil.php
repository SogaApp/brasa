<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_estado_civil")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEstadoCivilRepository")
 */
class RhuEstadoCivil
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_estado_civil_pk", type="string", length=1)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEstadoCivilPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;      

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="estadoCivilRel")
     */
    protected $empleadosEstadoCivilRel;    
  
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="estadoCivilRel")
     */
    protected $seleccionesEstadoCivilRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionRequisito", mappedBy="estadoCivilRel")
     */
    protected $seleccionesRequisitosEstadoCivilRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiEmpleado", mappedBy="estadoCivilRel")
     */
    protected $afiEmpleadosEstadoCivilRel; 

    /**
     * @ORM\OneToMany(targetEntity="RhuAspirante", mappedBy="estadoCivilRel")
     */
    protected $aspirantesEstadoCivilRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEstadoCivilRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesEstadoCivilRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesRequisitosEstadoCivilRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->afiEmpleadosEstadoCivilRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->aspirantesEstadoCivilRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEstadoCivilPk
     *
     * @return string
     */
    public function getCodigoEstadoCivilPk()
    {
        return $this->codigoEstadoCivilPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEstadoCivil
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
     * Add empleadosEstadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEstadoCivilRel
     *
     * @return RhuEstadoCivil
     */
    public function addEmpleadosEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEstadoCivilRel)
    {
        $this->empleadosEstadoCivilRel[] = $empleadosEstadoCivilRel;

        return $this;
    }

    /**
     * Remove empleadosEstadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEstadoCivilRel
     */
    public function removeEmpleadosEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEstadoCivilRel)
    {
        $this->empleadosEstadoCivilRel->removeElement($empleadosEstadoCivilRel);
    }

    /**
     * Get empleadosEstadoCivilRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEstadoCivilRel()
    {
        return $this->empleadosEstadoCivilRel;
    }

    /**
     * Add seleccionesEstadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesEstadoCivilRel
     *
     * @return RhuEstadoCivil
     */
    public function addSeleccionesEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesEstadoCivilRel)
    {
        $this->seleccionesEstadoCivilRel[] = $seleccionesEstadoCivilRel;

        return $this;
    }

    /**
     * Remove seleccionesEstadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesEstadoCivilRel
     */
    public function removeSeleccionesEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesEstadoCivilRel)
    {
        $this->seleccionesEstadoCivilRel->removeElement($seleccionesEstadoCivilRel);
    }

    /**
     * Get seleccionesEstadoCivilRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesEstadoCivilRel()
    {
        return $this->seleccionesEstadoCivilRel;
    }

    /**
     * Add seleccionesRequisitosEstadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosEstadoCivilRel
     *
     * @return RhuEstadoCivil
     */
    public function addSeleccionesRequisitosEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosEstadoCivilRel)
    {
        $this->seleccionesRequisitosEstadoCivilRel[] = $seleccionesRequisitosEstadoCivilRel;

        return $this;
    }

    /**
     * Remove seleccionesRequisitosEstadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosEstadoCivilRel
     */
    public function removeSeleccionesRequisitosEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosEstadoCivilRel)
    {
        $this->seleccionesRequisitosEstadoCivilRel->removeElement($seleccionesRequisitosEstadoCivilRel);
    }

    /**
     * Get seleccionesRequisitosEstadoCivilRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesRequisitosEstadoCivilRel()
    {
        return $this->seleccionesRequisitosEstadoCivilRel;
    }

    /**
     * Add afiEmpleadosEstadoCivilRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEmpleado $afiEmpleadosEstadoCivilRel
     *
     * @return RhuEstadoCivil
     */
    public function addAfiEmpleadosEstadoCivilRel(\Brasa\AfiliacionBundle\Entity\AfiEmpleado $afiEmpleadosEstadoCivilRel)
    {
        $this->afiEmpleadosEstadoCivilRel[] = $afiEmpleadosEstadoCivilRel;

        return $this;
    }

    /**
     * Remove afiEmpleadosEstadoCivilRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEmpleado $afiEmpleadosEstadoCivilRel
     */
    public function removeAfiEmpleadosEstadoCivilRel(\Brasa\AfiliacionBundle\Entity\AfiEmpleado $afiEmpleadosEstadoCivilRel)
    {
        $this->afiEmpleadosEstadoCivilRel->removeElement($afiEmpleadosEstadoCivilRel);
    }

    /**
     * Get afiEmpleadosEstadoCivilRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiEmpleadosEstadoCivilRel()
    {
        return $this->afiEmpleadosEstadoCivilRel;
    }

    /**
     * Add aspirantesEstadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesEstadoCivilRel
     *
     * @return RhuEstadoCivil
     */
    public function addAspirantesEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesEstadoCivilRel)
    {
        $this->aspirantesEstadoCivilRel[] = $aspirantesEstadoCivilRel;

        return $this;
    }

    /**
     * Remove aspirantesEstadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesEstadoCivilRel
     */
    public function removeAspirantesEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesEstadoCivilRel)
    {
        $this->aspirantesEstadoCivilRel->removeElement($aspirantesEstadoCivilRel);
    }

    /**
     * Get aspirantesEstadoCivilRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAspirantesEstadoCivilRel()
    {
        return $this->aspirantesEstadoCivilRel;
    }
}
