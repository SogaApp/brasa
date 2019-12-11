<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_contrato_sede")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuContratoSedeRepository")
 */
class RhuContratoSede
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_sede_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoSedePk;        
    
    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContratoFk;     

    /**
     * @ORM\Column(name="codigo_sede_fk", type="integer")
     */    
    private $codigoSedeFk;         
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="contratosSedesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSede", inversedBy="contratosSedesSedeRel")
     * @ORM\JoinColumn(name="codigo_sede_fk", referencedColumnName="codigo_sede_pk")
     */
    protected $sedeRel;    


    /**
     * Get codigoContratoSedePk
     *
     * @return integer
     */
    public function getCodigoContratoSedePk()
    {
        return $this->codigoContratoSedePk;
    }

    /**
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuContratoSede
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set codigoSedeFk
     *
     * @param integer $codigoSedeFk
     *
     * @return RhuContratoSede
     */
    public function setCodigoSedeFk($codigoSedeFk)
    {
        $this->codigoSedeFk = $codigoSedeFk;

        return $this;
    }

    /**
     * Get codigoSedeFk
     *
     * @return integer
     */
    public function getCodigoSedeFk()
    {
        return $this->codigoSedeFk;
    }

    /**
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuContratoSede
     */
    public function setContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * Set sedeRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSede $sedeRel
     *
     * @return RhuContratoSede
     */
    public function setSedeRel(\Brasa\RecursoHumanoBundle\Entity\RhuSede $sedeRel = null)
    {
        $this->sedeRel = $sedeRel;

        return $this;
    }

    /**
     * Get sedeRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSede
     */
    public function getSedeRel()
    {
        return $this->sedeRel;
    }
}
