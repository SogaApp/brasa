<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_carta_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCartaTipoRepository")
 */
class RhuCartaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_carta_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCartaTipoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=200, nullable=true)
     */    
    private $nombre;
    
    /**     
     * @ORM\Column(name="especial", type="boolean")
     */    
    private $especial = 0;
    
    /**
     * @ORM\Column(name="codigo_contenido_formato_fk", type="integer", nullable=true)
     */    
    private $codigoContenidoFormatoFk;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCarta", mappedBy="cartaTipoRel")
     */
    protected $cartasCartaTipoRel;    

       
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenContenidoFormato", inversedBy="cartasTiposContenidoFormatoRel")
     * @ORM\JoinColumn(name="codigo_contenido_formato_fk", referencedColumnName="codigo_contenido_formato_pk")
     */
    protected $contenidoFormatoRel;
    
  
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cartasCartaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCartaTipoPk
     *
     * @return integer
     */
    public function getCodigoCartaTipoPk()
    {
        return $this->codigoCartaTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCartaTipo
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
     * Set especial
     *
     * @param boolean $especial
     *
     * @return RhuCartaTipo
     */
    public function setEspecial($especial)
    {
        $this->especial = $especial;

        return $this;
    }

    /**
     * Get especial
     *
     * @return boolean
     */
    public function getEspecial()
    {
        return $this->especial;
    }

    /**
     * Set codigoContenidoFormatoFk
     *
     * @param integer $codigoContenidoFormatoFk
     *
     * @return RhuCartaTipo
     */
    public function setCodigoContenidoFormatoFk($codigoContenidoFormatoFk)
    {
        $this->codigoContenidoFormatoFk = $codigoContenidoFormatoFk;

        return $this;
    }

    /**
     * Get codigoContenidoFormatoFk
     *
     * @return integer
     */
    public function getCodigoContenidoFormatoFk()
    {
        return $this->codigoContenidoFormatoFk;
    }

    /**
     * Add cartasCartaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCarta $cartasCartaTipoRel
     *
     * @return RhuCartaTipo
     */
    public function addCartasCartaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCarta $cartasCartaTipoRel)
    {
        $this->cartasCartaTipoRel[] = $cartasCartaTipoRel;

        return $this;
    }

    /**
     * Remove cartasCartaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCarta $cartasCartaTipoRel
     */
    public function removeCartasCartaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCarta $cartasCartaTipoRel)
    {
        $this->cartasCartaTipoRel->removeElement($cartasCartaTipoRel);
    }

    /**
     * Get cartasCartaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCartasCartaTipoRel()
    {
        return $this->cartasCartaTipoRel;
    }

    /**
     * Set contenidoFormatoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenContenidoFormato $contenidoFormatoRel
     *
     * @return RhuCartaTipo
     */
    public function setContenidoFormatoRel(\Brasa\GeneralBundle\Entity\GenContenidoFormato $contenidoFormatoRel = null)
    {
        $this->contenidoFormatoRel = $contenidoFormatoRel;

        return $this;
    }

    /**
     * Get contenidoFormatoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenContenidoFormato
     */
    public function getContenidoFormatoRel()
    {
        return $this->contenidoFormatoRel;
    }
}
