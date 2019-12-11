<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_zona")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuZonaRepository")
 */
class RhuZona
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_zona_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoZonaPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;          
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="zonaRel")
     */
    protected $empleadosZonaRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionRequisito", mappedBy="zonaRel")
     */
    protected $seleccionesRequisitosZonaRel;    
   
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="zonaRel")
     */
    protected $seleccionesZonaRel;     
    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuAspirante", mappedBy="zonaRel")
     */
    protected $aspirantesZonaRel;     
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosZonaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoZonaPk
     *
     * @return integer
     */
    public function getCodigoZonaPk()
    {
        return $this->codigoZonaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuZona
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
     * Add empleadosZonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosZonaRel
     *
     * @return RhuZona
     */
    public function addEmpleadosZonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosZonaRel)
    {
        $this->empleadosZonaRel[] = $empleadosZonaRel;

        return $this;
    }

    /**
     * Remove empleadosZonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosZonaRel
     */
    public function removeEmpleadosZonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosZonaRel)
    {
        $this->empleadosZonaRel->removeElement($empleadosZonaRel);
    }

    /**
     * Get empleadosZonaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosZonaRel()
    {
        return $this->empleadosZonaRel;
    }

    /**
     * Add seleccionesRequisitosZonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosZonaRel
     *
     * @return RhuZona
     */
    public function addSeleccionesRequisitosZonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosZonaRel)
    {
        $this->seleccionesRequisitosZonaRel[] = $seleccionesRequisitosZonaRel;

        return $this;
    }

    /**
     * Remove seleccionesRequisitosZonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosZonaRel
     */
    public function removeSeleccionesRequisitosZonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosZonaRel)
    {
        $this->seleccionesRequisitosZonaRel->removeElement($seleccionesRequisitosZonaRel);
    }

    /**
     * Get seleccionesRequisitosZonaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesRequisitosZonaRel()
    {
        return $this->seleccionesRequisitosZonaRel;
    }

    /**
     * Add seleccionesZonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesZonaRel
     *
     * @return RhuZona
     */
    public function addSeleccionesZonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesZonaRel)
    {
        $this->seleccionesZonaRel[] = $seleccionesZonaRel;

        return $this;
    }

    /**
     * Remove seleccionesZonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesZonaRel
     */
    public function removeSeleccionesZonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesZonaRel)
    {
        $this->seleccionesZonaRel->removeElement($seleccionesZonaRel);
    }

    /**
     * Get seleccionesZonaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesZonaRel()
    {
        return $this->seleccionesZonaRel;
    }

    /**
     * Add aspirantesZonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesZonaRel
     *
     * @return RhuZona
     */
    public function addAspirantesZonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesZonaRel)
    {
        $this->aspirantesZonaRel[] = $aspirantesZonaRel;

        return $this;
    }

    /**
     * Remove aspirantesZonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesZonaRel
     */
    public function removeAspirantesZonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspirantesZonaRel)
    {
        $this->aspirantesZonaRel->removeElement($aspirantesZonaRel);
    }

    /**
     * Get aspirantesZonaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAspirantesZonaRel()
    {
        return $this->aspirantesZonaRel;
    }
}
