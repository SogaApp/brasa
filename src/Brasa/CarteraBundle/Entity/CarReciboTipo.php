<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_recibo_tipo")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarReciboTipoRepository")
 */
class CarReciboTipo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recibo_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoReciboTipoPk;        

    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    
    /**
     * @ORM\OneToMany(targetEntity="CarRecibo", mappedBy="reciboTipoRel")
     */
    protected $recibosReciboTipoRel;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recibosReciboTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoReciboTipoPk
     *
     * @return integer
     */
    public function getCodigoReciboTipoPk()
    {
        return $this->codigoReciboTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CarReciboTipo
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
     * Add recibosReciboTipoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $recibosReciboTipoRel
     *
     * @return CarReciboTipo
     */
    public function addRecibosReciboTipoRel(\Brasa\CarteraBundle\Entity\CarRecibo $recibosReciboTipoRel)
    {
        $this->recibosReciboTipoRel[] = $recibosReciboTipoRel;

        return $this;
    }

    /**
     * Remove recibosReciboTipoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $recibosReciboTipoRel
     */
    public function removeRecibosReciboTipoRel(\Brasa\CarteraBundle\Entity\CarRecibo $recibosReciboTipoRel)
    {
        $this->recibosReciboTipoRel->removeElement($recibosReciboTipoRel);
    }

    /**
     * Get recibosReciboTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecibosReciboTipoRel()
    {
        return $this->recibosReciboTipoRel;
    }
}
