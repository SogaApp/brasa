<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_desempeno_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDesempenoDetalleRepository")
 */
class RhuDesempenoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_desempeno_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDesempenoDetallePk;                    
    
    /**
     * @ORM\Column(name="codigo_desempeno_fk", type="integer", nullable=true)
     */    
    private $codigoDesempenoFk;   
    
    /**
     * @ORM\Column(name="codigo_desempeno_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoDesempenoConceptoFk;    
    
    /**     
     * @ORM\Column(name="siempre", type="boolean")
     */    
    private $siempre = 0;
    
    /**     
     * @ORM\Column(name="casi_siempre", type="boolean")
     */    
    private $casiSiempre = 0;
    
    /**     
     * @ORM\Column(name="algunas_veces", type="boolean")
     */    
    private $algunasVeces = 0;
    
    /**     
     * @ORM\Column(name="casi_nunca", type="boolean")
     */    
    private $casiNunca = 0;

    /**     
     * @ORM\Column(name="nunca", type="boolean")
     */    
    private $nunca = 0;         
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuDesempeno", inversedBy="desempenosDetallesDesempenoRel")
     * @ORM\JoinColumn(name="codigo_desempeno_fk", referencedColumnName="codigo_desempeno_pk")
     */
    protected $desempenoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuDesempenoConcepto", inversedBy="desempenosDetallesDesempenoConceptoRel")
     * @ORM\JoinColumn(name="codigo_desempeno_concepto_fk", referencedColumnName="codigo_desempeno_concepto_pk")
     */
    protected $desempenoConceptoRel;    
    


    

    /**
     * Get codigoDesempenoDetallePk
     *
     * @return integer
     */
    public function getCodigoDesempenoDetallePk()
    {
        return $this->codigoDesempenoDetallePk;
    }

    /**
     * Set codigoDesempenoFk
     *
     * @param integer $codigoDesempenoFk
     *
     * @return RhuDesempenoDetalle
     */
    public function setCodigoDesempenoFk($codigoDesempenoFk)
    {
        $this->codigoDesempenoFk = $codigoDesempenoFk;

        return $this;
    }

    /**
     * Get codigoDesempenoFk
     *
     * @return integer
     */
    public function getCodigoDesempenoFk()
    {
        return $this->codigoDesempenoFk;
    }

    /**
     * Set codigoDesempenoConceptoFk
     *
     * @param integer $codigoDesempenoConceptoFk
     *
     * @return RhuDesempenoDetalle
     */
    public function setCodigoDesempenoConceptoFk($codigoDesempenoConceptoFk)
    {
        $this->codigoDesempenoConceptoFk = $codigoDesempenoConceptoFk;

        return $this;
    }

    /**
     * Get codigoDesempenoConceptoFk
     *
     * @return integer
     */
    public function getCodigoDesempenoConceptoFk()
    {
        return $this->codigoDesempenoConceptoFk;
    }

    /**
     * Set siempre
     *
     * @param boolean $siempre
     *
     * @return RhuDesempenoDetalle
     */
    public function setSiempre($siempre)
    {
        $this->siempre = $siempre;

        return $this;
    }

    /**
     * Get siempre
     *
     * @return boolean
     */
    public function getSiempre()
    {
        return $this->siempre;
    }

    /**
     * Set casiSiempre
     *
     * @param boolean $casiSiempre
     *
     * @return RhuDesempenoDetalle
     */
    public function setCasiSiempre($casiSiempre)
    {
        $this->casiSiempre = $casiSiempre;

        return $this;
    }

    /**
     * Get casiSiempre
     *
     * @return boolean
     */
    public function getCasiSiempre()
    {
        return $this->casiSiempre;
    }

    /**
     * Set algunasVeces
     *
     * @param boolean $algunasVeces
     *
     * @return RhuDesempenoDetalle
     */
    public function setAlgunasVeces($algunasVeces)
    {
        $this->algunasVeces = $algunasVeces;

        return $this;
    }

    /**
     * Get algunasVeces
     *
     * @return boolean
     */
    public function getAlgunasVeces()
    {
        return $this->algunasVeces;
    }

    /**
     * Set casiNunca
     *
     * @param boolean $casiNunca
     *
     * @return RhuDesempenoDetalle
     */
    public function setCasiNunca($casiNunca)
    {
        $this->casiNunca = $casiNunca;

        return $this;
    }

    /**
     * Get casiNunca
     *
     * @return boolean
     */
    public function getCasiNunca()
    {
        return $this->casiNunca;
    }

    /**
     * Set nunca
     *
     * @param boolean $nunca
     *
     * @return RhuDesempenoDetalle
     */
    public function setNunca($nunca)
    {
        $this->nunca = $nunca;

        return $this;
    }

    /**
     * Get nunca
     *
     * @return boolean
     */
    public function getNunca()
    {
        return $this->nunca;
    }

    /**
     * Set desempenoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno $desempenoRel
     *
     * @return RhuDesempenoDetalle
     */
    public function setDesempenoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDesempeno $desempenoRel = null)
    {
        $this->desempenoRel = $desempenoRel;

        return $this;
    }

    /**
     * Get desempenoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno
     */
    public function getDesempenoRel()
    {
        return $this->desempenoRel;
    }

    /**
     * Set desempenoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto $desempenoConceptoRel
     *
     * @return RhuDesempenoDetalle
     */
    public function setDesempenoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto $desempenoConceptoRel = null)
    {
        $this->desempenoConceptoRel = $desempenoConceptoRel;

        return $this;
    }

    /**
     * Get desempenoConceptoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto
     */
    public function getDesempenoConceptoRel()
    {
        return $this->desempenoConceptoRel;
    }
}
