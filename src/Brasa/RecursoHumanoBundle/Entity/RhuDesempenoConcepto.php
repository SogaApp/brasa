<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_desempeno_concepto")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDesempenoConceptoRepository")
 */
class RhuDesempenoConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_desempeno_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDesempenoConceptoPk; 
    
    /**
     * @ORM\Column(name="codigo_desempeno_concepto_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoDesempenoConceptoTipoFk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=350, nullable=true)
     */    
    private $nombre;         
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDesempenoDetalle", mappedBy="desempenoConceptoRel")
     */
    protected $desempenosDetallesDesempenoConceptoRel;         
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuDesempenoConceptoTipo", inversedBy="desempenosConceptosDesempenoConceptoTipoRel")
     * @ORM\JoinColumn(name="codigo_desempeno_concepto_tipo_fk", referencedColumnName="codigo_desempeno_concepto_tipo_pk")
     */
    protected $desempenoConceptoTipoRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->desempenosDetallesDesempenoConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDesempenoConceptoPk
     *
     * @return integer
     */
    public function getCodigoDesempenoConceptoPk()
    {
        return $this->codigoDesempenoConceptoPk;
    }

    /**
     * Set codigoDesempenoConceptoTipoFk
     *
     * @param integer $codigoDesempenoConceptoTipoFk
     *
     * @return RhuDesempenoConcepto
     */
    public function setCodigoDesempenoConceptoTipoFk($codigoDesempenoConceptoTipoFk)
    {
        $this->codigoDesempenoConceptoTipoFk = $codigoDesempenoConceptoTipoFk;

        return $this;
    }

    /**
     * Get codigoDesempenoConceptoTipoFk
     *
     * @return integer
     */
    public function getCodigoDesempenoConceptoTipoFk()
    {
        return $this->codigoDesempenoConceptoTipoFk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuDesempenoConcepto
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
     * Add desempenosDetallesDesempenoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle $desempenosDetallesDesempenoConceptoRel
     *
     * @return RhuDesempenoConcepto
     */
    public function addDesempenosDetallesDesempenoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle $desempenosDetallesDesempenoConceptoRel)
    {
        $this->desempenosDetallesDesempenoConceptoRel[] = $desempenosDetallesDesempenoConceptoRel;

        return $this;
    }

    /**
     * Remove desempenosDetallesDesempenoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle $desempenosDetallesDesempenoConceptoRel
     */
    public function removeDesempenosDetallesDesempenoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle $desempenosDetallesDesempenoConceptoRel)
    {
        $this->desempenosDetallesDesempenoConceptoRel->removeElement($desempenosDetallesDesempenoConceptoRel);
    }

    /**
     * Get desempenosDetallesDesempenoConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDesempenosDetallesDesempenoConceptoRel()
    {
        return $this->desempenosDetallesDesempenoConceptoRel;
    }

    /**
     * Set desempenoConceptoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConceptoTipo $desempenoConceptoTipoRel
     *
     * @return RhuDesempenoConcepto
     */
    public function setDesempenoConceptoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConceptoTipo $desempenoConceptoTipoRel = null)
    {
        $this->desempenoConceptoTipoRel = $desempenoConceptoTipoRel;

        return $this;
    }

    /**
     * Get desempenoConceptoTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConceptoTipo
     */
    public function getDesempenoConceptoTipoRel()
    {
        return $this->desempenoConceptoTipoRel;
    }
}
