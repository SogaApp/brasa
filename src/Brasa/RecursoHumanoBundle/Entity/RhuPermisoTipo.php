<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_permiso_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPermisoTipoRepository")
 */
class RhuPermisoTipo
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_permiso_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPermisoTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;          
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPermiso", mappedBy="permisoTipoRel")
     */
    protected $permisosPermisoTipoRel;    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permisosPermisoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPermisoTipoPk
     *
     * @return integer
     */
    public function getCodigoPermisoTipoPk()
    {
        return $this->codigoPermisoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuPermisoTipo
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
     * Add permisosPermisoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPermiso $permisosPermisoTipoRel
     *
     * @return RhuPermisoTipo
     */
    public function addPermisosPermisoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPermiso $permisosPermisoTipoRel)
    {
        $this->permisosPermisoTipoRel[] = $permisosPermisoTipoRel;

        return $this;
    }

    /**
     * Remove permisosPermisoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPermiso $permisosPermisoTipoRel
     */
    public function removePermisosPermisoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPermiso $permisosPermisoTipoRel)
    {
        $this->permisosPermisoTipoRel->removeElement($permisosPermisoTipoRel);
    }

    /**
     * Get permisosPermisoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermisosPermisoTipoRel()
    {
        return $this->permisosPermisoTipoRel;
    }
}
