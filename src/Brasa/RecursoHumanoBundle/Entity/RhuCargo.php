<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_cargo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCargoRepository")
 */
class RhuCargo
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cargo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCargoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="codigo_cargo_supervigilancia_fk", type="integer", nullable=true)
     */    
    private $codigoCargoSupervigilanciaFk;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="cargoRel")
     */
    protected $contratosCargoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="cargoRel")
     */
    protected $empleadosCargoRel;   
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSsoAporte", mappedBy="cargoRel")
     */
    protected $ssoAportesCargoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuRequisitoCargo", mappedBy="cargoRel")
     */
    protected $requisitosCargosCargoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuRequisito", mappedBy="cargoRel")
     */
    protected $requisitosCargoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDesempeno", mappedBy="cargoRel")
     */
    protected $desempenosCargoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionRequisito", mappedBy="cargoRel")
     */
    protected $seleccionesRequisitosCargoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPermiso", mappedBy="cargoRel")
     */
    protected $permisosCargoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamen", mappedBy="cargoRel")
     */
    protected $examenesCargoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="cargoRel")
     */
    protected $seleccionesCargoRel;
        
    /**
     * @ORM\OneToMany(targetEntity="RhuDisciplinario", mappedBy="cargoRel")
     */
    protected $disciplinariosCargoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenCargo", mappedBy="cargoRel")
     */
    protected $examenesCargosCargoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDotacionCargo", mappedBy="cargoRel")
     */
    protected $dotacionesCargosCargoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiContrato", mappedBy="cargoRel")
     */
    protected $afiContratosCargoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargoSupervigilancia", inversedBy="cargosCargoSupervigilanciaRel")
     * @ORM\JoinColumn(name="codigo_cargo_supervigilancia_fk", referencedColumnName="codigo_cargo_supervigilancia_pk")
     */
    protected $cargoSupervigilanciaRel;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ssoAportesCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requisitosCargosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requisitosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->desempenosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesRequisitosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->permisosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->examenesCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disciplinariosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->examenesCargosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dotacionesCargosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->afiContratosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCargoPk
     *
     * @return integer
     */
    public function getCodigoCargoPk()
    {
        return $this->codigoCargoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCargo
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
     * Set codigoCargoSupervigilanciaFk
     *
     * @param integer $codigoCargoSupervigilanciaFk
     *
     * @return RhuCargo
     */
    public function setCodigoCargoSupervigilanciaFk($codigoCargoSupervigilanciaFk)
    {
        $this->codigoCargoSupervigilanciaFk = $codigoCargoSupervigilanciaFk;

        return $this;
    }

    /**
     * Get codigoCargoSupervigilanciaFk
     *
     * @return integer
     */
    public function getCodigoCargoSupervigilanciaFk()
    {
        return $this->codigoCargoSupervigilanciaFk;
    }

    /**
     * Add contratosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosCargoRel
     *
     * @return RhuCargo
     */
    public function addContratosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosCargoRel)
    {
        $this->contratosCargoRel[] = $contratosCargoRel;

        return $this;
    }

    /**
     * Remove contratosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosCargoRel
     */
    public function removeContratosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosCargoRel)
    {
        $this->contratosCargoRel->removeElement($contratosCargoRel);
    }

    /**
     * Get contratosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosCargoRel()
    {
        return $this->contratosCargoRel;
    }

    /**
     * Add empleadosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCargoRel
     *
     * @return RhuCargo
     */
    public function addEmpleadosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCargoRel)
    {
        $this->empleadosCargoRel[] = $empleadosCargoRel;

        return $this;
    }

    /**
     * Remove empleadosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCargoRel
     */
    public function removeEmpleadosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCargoRel)
    {
        $this->empleadosCargoRel->removeElement($empleadosCargoRel);
    }

    /**
     * Get empleadosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosCargoRel()
    {
        return $this->empleadosCargoRel;
    }

    /**
     * Add ssoAportesCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesCargoRel
     *
     * @return RhuCargo
     */
    public function addSsoAportesCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesCargoRel)
    {
        $this->ssoAportesCargoRel[] = $ssoAportesCargoRel;

        return $this;
    }

    /**
     * Remove ssoAportesCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesCargoRel
     */
    public function removeSsoAportesCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesCargoRel)
    {
        $this->ssoAportesCargoRel->removeElement($ssoAportesCargoRel);
    }

    /**
     * Get ssoAportesCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoAportesCargoRel()
    {
        return $this->ssoAportesCargoRel;
    }

    /**
     * Add requisitosCargosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoCargo $requisitosCargosCargoRel
     *
     * @return RhuCargo
     */
    public function addRequisitosCargosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisitoCargo $requisitosCargosCargoRel)
    {
        $this->requisitosCargosCargoRel[] = $requisitosCargosCargoRel;

        return $this;
    }

    /**
     * Remove requisitosCargosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoCargo $requisitosCargosCargoRel
     */
    public function removeRequisitosCargosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisitoCargo $requisitosCargosCargoRel)
    {
        $this->requisitosCargosCargoRel->removeElement($requisitosCargosCargoRel);
    }

    /**
     * Get requisitosCargosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitosCargosCargoRel()
    {
        return $this->requisitosCargosCargoRel;
    }

    /**
     * Add requisitosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisito $requisitosCargoRel
     *
     * @return RhuCargo
     */
    public function addRequisitosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisito $requisitosCargoRel)
    {
        $this->requisitosCargoRel[] = $requisitosCargoRel;

        return $this;
    }

    /**
     * Remove requisitosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisito $requisitosCargoRel
     */
    public function removeRequisitosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisito $requisitosCargoRel)
    {
        $this->requisitosCargoRel->removeElement($requisitosCargoRel);
    }

    /**
     * Get requisitosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitosCargoRel()
    {
        return $this->requisitosCargoRel;
    }

    /**
     * Add desempenosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno $desempenosCargoRel
     *
     * @return RhuCargo
     */
    public function addDesempenosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDesempeno $desempenosCargoRel)
    {
        $this->desempenosCargoRel[] = $desempenosCargoRel;

        return $this;
    }

    /**
     * Remove desempenosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno $desempenosCargoRel
     */
    public function removeDesempenosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDesempeno $desempenosCargoRel)
    {
        $this->desempenosCargoRel->removeElement($desempenosCargoRel);
    }

    /**
     * Get desempenosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDesempenosCargoRel()
    {
        return $this->desempenosCargoRel;
    }

    /**
     * Add seleccionesRequisitosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosCargoRel
     *
     * @return RhuCargo
     */
    public function addSeleccionesRequisitosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosCargoRel)
    {
        $this->seleccionesRequisitosCargoRel[] = $seleccionesRequisitosCargoRel;

        return $this;
    }

    /**
     * Remove seleccionesRequisitosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosCargoRel
     */
    public function removeSeleccionesRequisitosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosCargoRel)
    {
        $this->seleccionesRequisitosCargoRel->removeElement($seleccionesRequisitosCargoRel);
    }

    /**
     * Get seleccionesRequisitosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesRequisitosCargoRel()
    {
        return $this->seleccionesRequisitosCargoRel;
    }

    /**
     * Add permisosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPermiso $permisosCargoRel
     *
     * @return RhuCargo
     */
    public function addPermisosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPermiso $permisosCargoRel)
    {
        $this->permisosCargoRel[] = $permisosCargoRel;

        return $this;
    }

    /**
     * Remove permisosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPermiso $permisosCargoRel
     */
    public function removePermisosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPermiso $permisosCargoRel)
    {
        $this->permisosCargoRel->removeElement($permisosCargoRel);
    }

    /**
     * Get permisosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermisosCargoRel()
    {
        return $this->permisosCargoRel;
    }

    /**
     * Add examenesCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesCargoRel
     *
     * @return RhuCargo
     */
    public function addExamenesCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesCargoRel)
    {
        $this->examenesCargoRel[] = $examenesCargoRel;

        return $this;
    }

    /**
     * Remove examenesCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesCargoRel
     */
    public function removeExamenesCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesCargoRel)
    {
        $this->examenesCargoRel->removeElement($examenesCargoRel);
    }

    /**
     * Get examenesCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesCargoRel()
    {
        return $this->examenesCargoRel;
    }

    /**
     * Add seleccionesCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesCargoRel
     *
     * @return RhuCargo
     */
    public function addSeleccionesCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesCargoRel)
    {
        $this->seleccionesCargoRel[] = $seleccionesCargoRel;

        return $this;
    }

    /**
     * Remove seleccionesCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesCargoRel
     */
    public function removeSeleccionesCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesCargoRel)
    {
        $this->seleccionesCargoRel->removeElement($seleccionesCargoRel);
    }

    /**
     * Get seleccionesCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesCargoRel()
    {
        return $this->seleccionesCargoRel;
    }

    /**
     * Add disciplinariosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosCargoRel
     *
     * @return RhuCargo
     */
    public function addDisciplinariosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosCargoRel)
    {
        $this->disciplinariosCargoRel[] = $disciplinariosCargoRel;

        return $this;
    }

    /**
     * Remove disciplinariosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosCargoRel
     */
    public function removeDisciplinariosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosCargoRel)
    {
        $this->disciplinariosCargoRel->removeElement($disciplinariosCargoRel);
    }

    /**
     * Get disciplinariosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinariosCargoRel()
    {
        return $this->disciplinariosCargoRel;
    }

    /**
     * Add examenesCargosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenCargo $examenesCargosCargoRel
     *
     * @return RhuCargo
     */
    public function addExamenesCargosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenCargo $examenesCargosCargoRel)
    {
        $this->examenesCargosCargoRel[] = $examenesCargosCargoRel;

        return $this;
    }

    /**
     * Remove examenesCargosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenCargo $examenesCargosCargoRel
     */
    public function removeExamenesCargosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenCargo $examenesCargosCargoRel)
    {
        $this->examenesCargosCargoRel->removeElement($examenesCargosCargoRel);
    }

    /**
     * Get examenesCargosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesCargosCargoRel()
    {
        return $this->examenesCargosCargoRel;
    }

    /**
     * Add dotacionesCargosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo $dotacionesCargosCargoRel
     *
     * @return RhuCargo
     */
    public function addDotacionesCargosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo $dotacionesCargosCargoRel)
    {
        $this->dotacionesCargosCargoRel[] = $dotacionesCargosCargoRel;

        return $this;
    }

    /**
     * Remove dotacionesCargosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo $dotacionesCargosCargoRel
     */
    public function removeDotacionesCargosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo $dotacionesCargosCargoRel)
    {
        $this->dotacionesCargosCargoRel->removeElement($dotacionesCargosCargoRel);
    }

    /**
     * Get dotacionesCargosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDotacionesCargosCargoRel()
    {
        return $this->dotacionesCargosCargoRel;
    }

    /**
     * Add afiContratosCargoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosCargoRel
     *
     * @return RhuCargo
     */
    public function addAfiContratosCargoRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosCargoRel)
    {
        $this->afiContratosCargoRel[] = $afiContratosCargoRel;

        return $this;
    }

    /**
     * Remove afiContratosCargoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosCargoRel
     */
    public function removeAfiContratosCargoRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosCargoRel)
    {
        $this->afiContratosCargoRel->removeElement($afiContratosCargoRel);
    }

    /**
     * Get afiContratosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiContratosCargoRel()
    {
        return $this->afiContratosCargoRel;
    }

    /**
     * Set cargoSupervigilanciaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargoSupervigilancia $cargoSupervigilanciaRel
     *
     * @return RhuCargo
     */
    public function setCargoSupervigilanciaRel(\Brasa\RecursoHumanoBundle\Entity\RhuCargoSupervigilancia $cargoSupervigilanciaRel = null)
    {
        $this->cargoSupervigilanciaRel = $cargoSupervigilanciaRel;

        return $this;
    }

    /**
     * Get cargoSupervigilanciaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCargoSupervigilancia
     */
    public function getCargoSupervigilanciaRel()
    {
        return $this->cargoSupervigilanciaRel;
    }
}
