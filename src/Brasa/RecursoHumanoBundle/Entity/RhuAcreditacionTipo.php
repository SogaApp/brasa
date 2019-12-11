<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_acreditacion_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuAcreditacionTipoRepository")
 */
class RhuAcreditacionTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_acreditacion_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAcreditacionTipoPk;
    
    /**
     * @ORM\Column(name="codigo", type="string", length=20, nullable=true)
     */    
    private $codigo;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="cargo", type="string", length=50, nullable=true)
     */    
    private $cargo; 
    
    /**
     * @ORM\Column(name="cargo_codigo", type="string", length=10, nullable=true)
     */    
    private $cargoCodigo;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuAcreditacion", mappedBy="acreditacionTipoRel")
     */
    protected $acreditacionesAcreditacionTipoRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->acreditacionesAcreditacionTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoAcreditacionTipoPk
     *
     * @return integer
     */
    public function getCodigoAcreditacionTipoPk()
    {
        return $this->codigoAcreditacionTipoPk;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     *
     * @return RhuAcreditacionTipo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuAcreditacionTipo
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
     * Set cargo
     *
     * @param string $cargo
     *
     * @return RhuAcreditacionTipo
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Add acreditacionesAcreditacionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesAcreditacionTipoRel
     *
     * @return RhuAcreditacionTipo
     */
    public function addAcreditacionesAcreditacionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesAcreditacionTipoRel)
    {
        $this->acreditacionesAcreditacionTipoRel[] = $acreditacionesAcreditacionTipoRel;

        return $this;
    }

    /**
     * Remove acreditacionesAcreditacionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesAcreditacionTipoRel
     */
    public function removeAcreditacionesAcreditacionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion $acreditacionesAcreditacionTipoRel)
    {
        $this->acreditacionesAcreditacionTipoRel->removeElement($acreditacionesAcreditacionTipoRel);
    }

    /**
     * Get acreditacionesAcreditacionTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAcreditacionesAcreditacionTipoRel()
    {
        return $this->acreditacionesAcreditacionTipoRel;
    }

    /**
     * Set cargoCodigo
     *
     * @param string $cargoCodigo
     *
     * @return RhuAcreditacionTipo
     */
    public function setCargoCodigo($cargoCodigo)
    {
        $this->cargoCodigo = $cargoCodigo;

        return $this;
    }

    /**
     * Get cargoCodigo
     *
     * @return string
     */
    public function getCargoCodigo()
    {
        return $this->cargoCodigo;
    }
}
