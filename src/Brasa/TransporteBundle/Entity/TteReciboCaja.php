<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_recibo_caja")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteReciboCajaRepository")
 */
class TteReciboCaja
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recibo_caja_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoReciboCajaPk;  
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;     
    
    /**
     * @ORM\Column(name="codigo_guia_fk", type="integer", nullable=true)
     */    
    private $codigoGuiaFk;     
    
    /**
     * @ORM\Column(name="vr_flete", type="float")
     */
    private $vrFlete = 0;
    
    /**
     * @ORM\Column(name="vr_manejo", type="float")
     */
    private $vrManejo = 0;    

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;      
    
    
    /**
     * @ORM\ManyToOne(targetEntity="TteGuia", inversedBy="recibosCajaRel")
     * @ORM\JoinColumn(name="codigo_guia_fk", referencedColumnName="codigo_guia_pk")
     */
    protected $guiaRel;    
    


    /**
     * Get codigoReciboCajaPk
     *
     * @return integer 
     */
    public function getCodigoReciboCajaPk()
    {
        return $this->codigoReciboCajaPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return TteReciboCaja
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set codigoGuiaFk
     *
     * @param integer $codigoGuiaFk
     * @return TteReciboCaja
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
     * Set vrFlete
     *
     * @param float $vrFlete
     * @return TteReciboCaja
     */
    public function setVrFlete($vrFlete)
    {
        $this->vrFlete = $vrFlete;

        return $this;
    }

    /**
     * Get vrFlete
     *
     * @return float 
     */
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * Set vrManejo
     *
     * @param float $vrManejo
     * @return TteReciboCaja
     */
    public function setVrManejo($vrManejo)
    {
        $this->vrManejo = $vrManejo;

        return $this;
    }

    /**
     * Get vrManejo
     *
     * @return float 
     */
    public function getVrManejo()
    {
        return $this->vrManejo;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     * @return TteReciboCaja
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float 
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * Set guiaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiaRel
     * @return TteReciboCaja
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
