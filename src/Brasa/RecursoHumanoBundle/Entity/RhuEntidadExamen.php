<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_entidad_examen")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEntidadExamenRepository")
 */
class RhuEntidadExamen
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_examen_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadExamenPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\Column(name="nit", type="string", length=20, nullable=true)
     */    
    private $nit;    
    
    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */    
    private $direccion;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=80, nullable=true)
     */    
    private $telefono;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamen", mappedBy="entidadExamenRel")
     */
    protected $examenesEntidadExamenRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoExamen", mappedBy="entidadExamenRel")
     */
    protected $pagosExamenesEntidadExamenRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->examenesEntidadExamenRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosExamenesEntidadExamenRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEntidadExamenPk
     *
     * @return integer
     */
    public function getCodigoEntidadExamenPk()
    {
        return $this->codigoEntidadExamenPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEntidadExamen
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
     * Set nit
     *
     * @param string $nit
     *
     * @return RhuEntidadExamen
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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return RhuEntidadExamen
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
     * @return RhuEntidadExamen
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
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuEntidadExamen
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * Add examenesEntidadExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesEntidadExamenRel
     *
     * @return RhuEntidadExamen
     */
    public function addExamenesEntidadExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesEntidadExamenRel)
    {
        $this->examenesEntidadExamenRel[] = $examenesEntidadExamenRel;

        return $this;
    }

    /**
     * Remove examenesEntidadExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesEntidadExamenRel
     */
    public function removeExamenesEntidadExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesEntidadExamenRel)
    {
        $this->examenesEntidadExamenRel->removeElement($examenesEntidadExamenRel);
    }

    /**
     * Get examenesEntidadExamenRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesEntidadExamenRel()
    {
        return $this->examenesEntidadExamenRel;
    }

    /**
     * Add pagosExamenesEntidadExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen $pagosExamenesEntidadExamenRel
     *
     * @return RhuEntidadExamen
     */
    public function addPagosExamenesEntidadExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen $pagosExamenesEntidadExamenRel)
    {
        $this->pagosExamenesEntidadExamenRel[] = $pagosExamenesEntidadExamenRel;

        return $this;
    }

    /**
     * Remove pagosExamenesEntidadExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen $pagosExamenesEntidadExamenRel
     */
    public function removePagosExamenesEntidadExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen $pagosExamenesEntidadExamenRel)
    {
        $this->pagosExamenesEntidadExamenRel->removeElement($pagosExamenesEntidadExamenRel);
    }

    /**
     * Get pagosExamenesEntidadExamenRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosExamenesEntidadExamenRel()
    {
        return $this->pagosExamenesEntidadExamenRel;
    }
}
