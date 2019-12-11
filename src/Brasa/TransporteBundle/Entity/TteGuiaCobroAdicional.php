<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_guia_cobro_adicional")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteGuiaCobroAdicionalRepository")
 */
class TteGuiaCobroAdicional
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_guia_cobro_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoGuiaCobroAdicionalPk;
    
    /**
     * @ORM\Column(name="codigo_guia_fk", type="integer", nullable=true)
     */    
    private $codigoGuiaFk; 
    
    /**
     * @ORM\Column(name="vr_cobro", type="float")
     */
    private $vrCobro = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="TteGuia", inversedBy="guiasCobrosAdicionalesRel")
     * @ORM\JoinColumn(name="codigo_guia_fk", referencedColumnName="codigo_guia_pk")
     */
    protected $guiaRel;    
    


    /**
     * Get codigoGuiaCobroAdicionalPk
     *
     * @return integer 
     */
    public function getCodigoGuiaCobroAdicionalPk()
    {
        return $this->codigoGuiaCobroAdicionalPk;
    }

    /**
     * Set codigoGuiaFk
     *
     * @param integer $codigoGuiaFk
     * @return TteGuiaCobroAdicional
     */
    public function setCodigoGuiaFk($codigoGuiaFk)
    {
        $this->codigoGuiaFk = $codigoGuiaFk;

        return $this;
    }

    /**
     * Get codigoGuiaFk
     *
     * @return integer 
     */
    public function getCodigoGuiaFk()
    {
        return $this->codigoGuiaFk;
    }

    /**
     * Set vrCobro
     *
     * @param float $vrCobro
     * @return TteGuiaCobroAdicional
     */
    public function setVrCobro($vrCobro)
    {
        $this->vrCobro = $vrCobro;

        return $this;
    }

    /**
     * Get vrCobro
     *
     * @return float 
     */
    public function getVrCobro()
    {
        return $this->vrCobro;
    }

    /**
     * Set guiaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiaRel
     * @return TteGuiaCobroAdicional
     */
    public function setGuiaRel(\Brasa\TransporteBundle\Entity\TteGuia $guiaRel = null)
    {
        $this->guiaRel = $guiaRel;

        return $this;
    }

    /**
     * Get guiaRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteGuia 
     */
    public function getGuiaRel()
    {
        return $this->guiaRel;
    }
}
