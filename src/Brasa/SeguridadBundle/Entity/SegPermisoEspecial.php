<?php

namespace Brasa\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="seg_permiso_especial")
 * @ORM\Entity(repositoryClass="Brasa\SeguridadBundle\Repository\SegPermisoEspecialRepository")
 */
class SegPermisoEspecial
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_permiso_especial_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPermisoEspecialPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;   
    
    /**
     * @ORM\Column(name="tipo", type="string", length=30, nullable=true)
     */    
    private $tipo;
    
    /**
     * @ORM\Column(name="modulo", type="string", length=30, nullable=true)
     */    
    private $modulo;    
    
    /**
     * @ORM\OneToMany(targetEntity="SegUsuarioPermisoEspecial", mappedBy="permisoEspecialRel")
     */
    protected $segPermisoEspecialSegUsuarioPermisoEspecialRel;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->segPermisoEspecialSegUsuarioPermisoEspecialRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPermisoEspecialPk
     *
     * @return integer
     */
    public function getCodigoPermisoEspecialPk()
    {
        return $this->codigoPermisoEspecialPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return SegPermisoEspecial
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
     * Add segPermisoEspecialSegUsuarioPermisoEspecialRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial $segPermisoEspecialSegUsuarioPermisoEspecialRel
     *
     * @return SegPermisoEspecial
     */
    public function addSegPermisoEspecialSegUsuarioPermisoEspecialRel(\Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial $segPermisoEspecialSegUsuarioPermisoEspecialRel)
    {
        $this->segPermisoEspecialSegUsuarioPermisoEspecialRel[] = $segPermisoEspecialSegUsuarioPermisoEspecialRel;

        return $this;
    }

    /**
     * Remove segPermisoEspecialSegUsuarioPermisoEspecialRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial $segPermisoEspecialSegUsuarioPermisoEspecialRel
     */
    public function removeSegPermisoEspecialSegUsuarioPermisoEspecialRel(\Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial $segPermisoEspecialSegUsuarioPermisoEspecialRel)
    {
        $this->segPermisoEspecialSegUsuarioPermisoEspecialRel->removeElement($segPermisoEspecialSegUsuarioPermisoEspecialRel);
    }

    /**
     * Get segPermisoEspecialSegUsuarioPermisoEspecialRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSegPermisoEspecialSegUsuarioPermisoEspecialRel()
    {
        return $this->segPermisoEspecialSegUsuarioPermisoEspecialRel;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return SegPermisoEspecial
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
     * Set modulo
     *
     * @param string $modulo
     *
     * @return SegPermisoEspecial
     */
    public function setModulo($modulo)
    {
        $this->modulo = $modulo;

        return $this;
    }

    /**
     * Get modulo
     *
     * @return string
     */
    public function getModulo()
    {
        return $this->modulo;
    }
}
