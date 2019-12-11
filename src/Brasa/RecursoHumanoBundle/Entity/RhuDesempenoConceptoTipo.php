<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_desempeno_concepto_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDesempenoConceptoTipoRepository")
 */
class RhuDesempenoConceptoTipo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_desempeno_concepto_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDesempenoConceptoTipoPk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDesempenoConcepto", mappedBy="desempenoConceptoTipoRel")
     */
    protected $desempenosConceptosDesempenoConceptoTipoRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->desempenosConceptosDesempenoConceptoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDesempenoConceptoTipoPk
     *
     * @return integer
     */
    public function getCodigoDesempenoConceptoTipoPk()
    {
        return $this->codigoDesempenoConceptoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuDesempenoConceptoTipo
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
     * Add desempenosConceptosDesempenoConceptoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto $desempenosConceptosDesempenoConceptoTipoRel
     *
     * @return RhuDesempenoConceptoTipo
     */
    public function addDesempenosConceptosDesempenoConceptoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto $desempenosConceptosDesempenoConceptoTipoRel)
    {
        $this->desempenosConceptosDesempenoConceptoTipoRel[] = $desempenosConceptosDesempenoConceptoTipoRel;

        return $this;
    }

    /**
     * Remove desempenosConceptosDesempenoConceptoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto $desempenosConceptosDesempenoConceptoTipoRel
     */
    public function removeDesempenosConceptosDesempenoConceptoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto $desempenosConceptosDesempenoConceptoTipoRel)
    {
        $this->desempenosConceptosDesempenoConceptoTipoRel->removeElement($desempenosConceptosDesempenoConceptoTipoRel);
    }

    /**
     * Get desempenosConceptosDesempenoConceptoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDesempenosConceptosDesempenoConceptoTipoRel()
    {
        return $this->desempenosConceptosDesempenoConceptoTipoRel;
    }
}
