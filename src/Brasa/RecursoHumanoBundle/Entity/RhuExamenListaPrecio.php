<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen_lista_precio")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenListaPrecioRepository")
 */
class RhuExamenListaPrecio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_lista_precio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenListaPrecioPk;
    
    /**
     * @ORM\Column(name="codigo_entidad_examen_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadExamenFk;    
    
    /**
     * @ORM\Column(name="codigo_examen_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoExamenTipoFk;        
    
    /**
     * @ORM\Column(name="precio", type="float")
     */
    private $precio;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadExamen", inversedBy="examenesEntidadExamenRel")
     * @ORM\JoinColumn(name="codigo_entidad_examen_fk", referencedColumnName="codigo_entidad_examen_pk")
     */
    protected $entidadExamenRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenTipo", inversedBy="examenesExamenTipoRel")
     * @ORM\JoinColumn(name="codigo_examen_tipo_fk", referencedColumnName="codigo_examen_tipo_pk")
     */
    protected $examenTipoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenDetalle", mappedBy="examenRel")
     */
    protected $examenesExamenDetalleRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->examenesExamenDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoExamenListaPrecioPk
     *
     * @return integer
     */
    public function getCodigoExamenListaPrecioPk()
    {
        return $this->codigoExamenListaPrecioPk;
    }

    /**
     * Set codigoEntidadExamenFk
     *
     * @param integer $codigoEntidadExamenFk
     *
     * @return RhuExamenListaPrecio
     */
    public function setCodigoEntidadExamenFk($codigoEntidadExamenFk)
    {
        $this->codigoEntidadExamenFk = $codigoEntidadExamenFk;

        return $this;
    }

    /**
     * Get codigoEntidadExamenFk
     *
     * @return integer
     */
    public function getCodigoEntidadExamenFk()
    {
        return $this->codigoEntidadExamenFk;
    }

    /**
     * Set codigoExamenTipoFk
     *
     * @param integer $codigoExamenTipoFk
     *
     * @return RhuExamenListaPrecio
     */
    public function setCodigoExamenTipoFk($codigoExamenTipoFk)
    {
        $this->codigoExamenTipoFk = $codigoExamenTipoFk;

        return $this;
    }

    /**
     * Get codigoExamenTipoFk
     *
     * @return integer
     */
    public function getCodigoExamenTipoFk()
    {
        return $this->codigoExamenTipoFk;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return RhuExamenListaPrecio
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set entidadExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen $entidadExamenRel
     *
     * @return RhuExamenListaPrecio
     */
    public function setEntidadExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen $entidadExamenRel = null)
    {
        $this->entidadExamenRel = $entidadExamenRel;

        return $this;
    }

    /**
     * Get entidadExamenRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen
     */
    public function getEntidadExamenRel()
    {
        return $this->entidadExamenRel;
    }

    /**
     * Set examenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo $examenTipoRel
     *
     * @return RhuExamenListaPrecio
     */
    public function setExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo $examenTipoRel = null)
    {
        $this->examenTipoRel = $examenTipoRel;

        return $this;
    }

    /**
     * Get examenTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo
     */
    public function getExamenTipoRel()
    {
        return $this->examenTipoRel;
    }

    /**
     * Add examenesExamenDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesExamenDetalleRel
     *
     * @return RhuExamenListaPrecio
     */
    public function addExamenesExamenDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesExamenDetalleRel)
    {
        $this->examenesExamenDetalleRel[] = $examenesExamenDetalleRel;

        return $this;
    }

    /**
     * Remove examenesExamenDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesExamenDetalleRel
     */
    public function removeExamenesExamenDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesExamenDetalleRel)
    {
        $this->examenesExamenDetalleRel->removeElement($examenesExamenDetalleRel);
    }

    /**
     * Get examenesExamenDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesExamenDetalleRel()
    {
        return $this->examenesExamenDetalleRel;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuExamenListaPrecio
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
}
