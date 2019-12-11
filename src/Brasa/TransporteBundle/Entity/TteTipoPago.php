<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_tipo_pago")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteTipoPagoRepository")
 */
class TteTipoPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTipoPagoPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    
 
    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="tipoPagoRel")
     */
    protected $guiasRel;     



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->guiasRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoTipoPagoPk
     *
     * @return integer 
     */
    public function getCodigoTipoPagoPk()
    {
        return $this->codigoTipoPagoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return TteTipoPago
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
     * Add guiasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasRel
     * @return TteTipoPago
     */
    public function addGuiasRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasRel)
    {
        $this->guiasRel[] = $guiasRel;

        return $this;
    }

    /**
     * Remove guiasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasRel
     */
    public function removeGuiasRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasRel)
    {
        $this->guiasRel->removeElement($guiasRel);
    }

    /**
     * Get guiasRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGuiasRel()
    {
        return $this->guiasRel;
    }
}
