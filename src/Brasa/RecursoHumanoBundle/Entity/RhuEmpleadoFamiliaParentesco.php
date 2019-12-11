<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado_familia_parentesco")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoFamiliaParentescoRepository")
 */
class RhuEmpleadoFamiliaParentesco
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_familia_parentesco_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $empleadoFamiliaParentescoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;          
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoFamilia", mappedBy="empleadoFamiliaParentescoRel")
     */
    protected $empleadosFamiliasEmpleadoFamiliaParentescoRel;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosFamiliasEmpleadoFamiliaParentescoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get empleadoFamiliaParentescoPk
     *
     * @return integer
     */
    public function getEmpleadoFamiliaParentescoPk()
    {
        return $this->empleadoFamiliaParentescoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEmpleadoFamiliaParentesco
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
     * Add empleadosFamiliasEmpleadoFamiliaParentescoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEmpleadoFamiliaParentescoRel
     *
     * @return RhuEmpleadoFamiliaParentesco
     */
    public function addEmpleadosFamiliasEmpleadoFamiliaParentescoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEmpleadoFamiliaParentescoRel)
    {
        $this->empleadosFamiliasEmpleadoFamiliaParentescoRel[] = $empleadosFamiliasEmpleadoFamiliaParentescoRel;

        return $this;
    }

    /**
     * Remove empleadosFamiliasEmpleadoFamiliaParentescoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEmpleadoFamiliaParentescoRel
     */
    public function removeEmpleadosFamiliasEmpleadoFamiliaParentescoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEmpleadoFamiliaParentescoRel)
    {
        $this->empleadosFamiliasEmpleadoFamiliaParentescoRel->removeElement($empleadosFamiliasEmpleadoFamiliaParentescoRel);
    }

    /**
     * Get empleadosFamiliasEmpleadoFamiliaParentescoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosFamiliasEmpleadoFamiliaParentescoRel()
    {
        return $this->empleadosFamiliasEmpleadoFamiliaParentescoRel;
    }
}
