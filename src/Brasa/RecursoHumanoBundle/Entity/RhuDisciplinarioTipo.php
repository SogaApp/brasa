<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_disciplinario_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDisciplinarioTipoRepository")
 */
class RhuDisciplinarioTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_disciplinario_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDisciplinarioTipoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */    
    private $nombre; 
    
    /**     
     * @ORM\Column(name="suspension", type="boolean")
     */    
    private $suspension = false;    
    
    /**
     * @ORM\Column(name="codigo_contenido_formato_fk", type="integer", nullable=true)
     */    
    private $codigoContenidoFormatoFk;
       
    /**
     * @ORM\OneToMany(targetEntity="RhuDisciplinario", mappedBy="disciplinarioTipoRel")
     */
    protected $disciplinariosDisciplinarioTipoRel;    
    
     /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenContenidoFormato", inversedBy="disciplinariosTiposContenidoFormatoRel")
     * @ORM\JoinColumn(name="codigo_contenido_formato_fk", referencedColumnName="codigo_contenido_formato_pk")
     */
    protected $contenidoFormatoRel;
    
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->disciplinariosDisciplinarioTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDisciplinarioTipoPk
     *
     * @return integer
     */
    public function getCodigoDisciplinarioTipoPk()
    {
        return $this->codigoDisciplinarioTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuDisciplinarioTipo
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
     * Set codigoContenidoFormatoFk
     *
     * @param integer $codigoContenidoFormatoFk
     *
     * @return RhuDisciplinarioTipo
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
     * Add disciplinariosDisciplinarioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosDisciplinarioTipoRel
     *
     * @return RhuDisciplinarioTipo
     */
    public function addDisciplinariosDisciplinarioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosDisciplinarioTipoRel)
    {
        $this->disciplinariosDisciplinarioTipoRel[] = $disciplinariosDisciplinarioTipoRel;

        return $this;
    }

    /**
     * Remove disciplinariosDisciplinarioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosDisciplinarioTipoRel
     */
    public function removeDisciplinariosDisciplinarioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosDisciplinarioTipoRel)
    {
        $this->disciplinariosDisciplinarioTipoRel->removeElement($disciplinariosDisciplinarioTipoRel);
    }

    /**
     * Get disciplinariosDisciplinarioTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinariosDisciplinarioTipoRel()
    {
        return $this->disciplinariosDisciplinarioTipoRel;
    }

    /**
     * Set contenidoFormatoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenContenidoFormato $contenidoFormatoRel
     *
     * @return RhuDisciplinarioTipo
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

    /**
     * Set suspension
     *
     * @param boolean $suspension
     *
     * @return RhuDisciplinarioTipo
     */
    public function setSuspension($suspension)
    {
        $this->suspension = $suspension;

        return $this;
    }

    /**
     * Get suspension
     *
     * @return boolean
     */
    public function getSuspension()
    {
        return $this->suspension;
    }
}
