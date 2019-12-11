<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_rh")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuRhRepository")
 */
class RhuRh
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_rh_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRhPk;
    
    /**
     * @ORM\Column(name="tipo", type="string", length=80, nullable=true)
     */    
    private $tipo;      

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="rhRel")
     */
    protected $empleadosRhRel;    
  
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="estadoCivilRel")
     */
    protected $seleccionesEstadoCivilRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="rhRel")
     */
    protected $seleccionesRhRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuAspirante", mappedBy="rhRel")
     */
    protected $aspirantesRhRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiEmpleado", mappedBy="rhRel")
     */
    protected $afiEmpleadosRhRel; 


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosRhRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesEstadoCivilRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesRhRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->aspirantesRhRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->afiEmpleadosRhRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoRhPk
     *
     * @return integer
     */
    public function getCodigoRhPk()
    {
        return $this->codigoRhPk;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return RhuRh
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Add empleadosRhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosRhRel
     *
     * @return RhuRh
     */
    public function addEmpleadosRhRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosRhRel)
    {
        $this->empleadosRhRel[] = $empleadosRhRel;

        return $this;
    }

    /**
     * Remove empleadosRhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosRhRel
     */
    public function removeEmpleadosRhRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosRhRel)
    {
        $this->empleadosRhRel->removeElement($empleadosRhRel);
    }

    /**
     * Get empleadosRhRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosRhRel()
    {
        return $this->empleadosRhRel;
    }

    /**
     * Add seleccionesEstadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesEstadoCivilRel
     *
     * @return RhuRh
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
     * Add seleccionesRhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesRhRel
     *
     * @return RhuRh
     */
    public function addSeleccionesRhRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesRhRel)
    {
        $this->seleccionesRhRel[] = $seleccionesRhRel;

        return $this;
    }

    /**
     * Remove seleccionesRhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesRhRel
     */
    public function removeSeleccionesRhRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesRhRel)
    {
        $this->seleccionesRhRel->removeElement($seleccionesRhRel);
    }

    /**
     * Get seleccionesRhRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesRhRel()
    {
        return $this->seleccionesRhRel;
    }

    /**
     * Add aspirantesRhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesRhRel
     *
     * @return RhuRh
     */
    public function addAspirantesRhRel(\Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesRhRel)
    {
        $this->aspirantesRhRel[] = $aspirantesRhRel;

        return $this;
    }

    /**
     * Remove aspirantesRhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesRhRel
     */
    public function removeAspirantesRhRel(\Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesRhRel)
    {
        $this->aspirantesRhRel->removeElement($aspirantesRhRel);
    }

    /**
     * Get aspirantesRhRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAspirantesRhRel()
    {
        return $this->aspirantesRhRel;
    }

    /**
     * Add afiEmpleadosRhRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEmpleado $afiEmpleadosRhRel
     *
     * @return RhuRh
     */
    public function addAfiEmpleadosRhRel(\Brasa\AfiliacionBundle\Entity\AfiEmpleado $afiEmpleadosRhRel)
    {
        $this->afiEmpleadosRhRel[] = $afiEmpleadosRhRel;

        return $this;
    }

    /**
     * Remove afiEmpleadosRhRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEmpleado $afiEmpleadosRhRel
     */
    public function removeAfiEmpleadosRhRel(\Brasa\AfiliacionBundle\Entity\AfiEmpleado $afiEmpleadosRhRel)
    {
        $this->afiEmpleadosRhRel->removeElement($afiEmpleadosRhRel);
    }

    /**
     * Get afiEmpleadosRhRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiEmpleadosRhRel()
    {
        return $this->afiEmpleadosRhRel;
    }
}
