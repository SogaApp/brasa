<?php

namespace Brasa\RecursoHumanoBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_academia")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuAcademiaRepository")
 * @DoctrineAssert\UniqueEntity(fields={"nit"},message="Ya existe este nit")
 */
class RhuAcademia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_academia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAcademiaPk;
    
    /**
     * @ORM\Column(name="nit", type="string", length=21, nullable=false, unique=true)
     */    
    private $nit;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=160, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="sede", type="string", length=60, nullable=true)
     */    
    private $sede;
    
    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */    
    private $direccion;
    
    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */    
    private $telefono;
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadFk;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuAcademiasCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoEstudio", mappedBy="academiaRel")
     */
    protected $empleadosEstudiosAcademiaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAcreditacion", mappedBy="academiaRel")
     */
    protected $acreditacionesAcademiaRel;    


    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEstudiosAcademiaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->acreditacionesAcademiaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoAcademiaPk
     *
     * @return integer
     */
    public function getCodigoAcademiaPk()
    {
        return $this->codigoAcademiaPk;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return RhuAcademia
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuAcademia
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
     * Set sede
     *
     * @param string $sede
     *
     * @return RhuAcademia
     */
    public function setSede($sede)
    {
        $this->sede = $sede;

        return $this;
    }

    /**
     * Get sede
     *
     * @return string
     */
    public function getSede()
    {
        return $this->sede;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return RhuAcademia
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return RhuAcademia
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return RhuAcademia
     */
    public function setCodigoCiudadFk($codigoCiudadFk)
    {
        $this->codigoCiudadFk = $codigoCiudadFk;

        return $this;
    }

    /**
     * Get codigoCiudadFk
     *
     * @return integer
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuAcademia
     */
    public function setCiudadRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel = null)
    {
        $this->ciudadRel = $ciudadRel;

        return $this;
    }

    /**
     * Get ciudadRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * Add empleadosEstudiosAcademiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosAcademiaRel
     *
     * @return RhuAcademia
     */
    public function addEmpleadosEstudiosAcademiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosAcademiaRel)
    {
        $this->empleadosEstudiosAcademiaRel[] = $empleadosEstudiosAcademiaRel;

        return $this;
    }

    /**
     * Remove empleadosEstudiosAcademiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosAcademiaRel
     */
    public function removeEmpleadosEstudiosAcademiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosAcademiaRel)
    {
        $this->empleadosEstudiosAcademiaRel->removeElement($empleadosEstudiosAcademiaRel);
    }

    /**
     * Get empleadosEstudiosAcademiaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEstudiosAcademiaRel()
    {
        return $this->empleadosEstudiosAcademiaRel;
    }

    /**
     * Add acreditacionesAcademiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesAcademiaRel
     *
     * @return RhuAcademia
     */
    public function addAcreditacionesAcademiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesAcademiaRel)
    {
        $this->acreditacionesAcademiaRel[] = $acreditacionesAcademiaRel;

        return $this;
    }

    /**
     * Remove acreditacionesAcademiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesAcademiaRel
     */
    public function removeAcreditacionesAcademiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesAcademiaRel)
    {
        $this->acreditacionesAcademiaRel->removeElement($acreditacionesAcademiaRel);
    }

    /**
     * Get acreditacionesAcademiaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAcreditacionesAcademiaRel()
    {
        return $this->acreditacionesAcademiaRel;
    }
}
