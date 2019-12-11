<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_ruta")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteRutaRepository")
 */
class TteRuta
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_ruta_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRutaPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    

    /**
     * @ORM\OneToMany(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", mappedBy="rutaRel")
     */
    protected $ciudadesRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="rutaRel")
     */
    protected $guiasRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TteDespacho", mappedBy="rutaRel")
     */
    protected $despachosRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ciudadesRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->guiasRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->despachosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoRutaPk
     *
     * @return integer 
     */
    public function getCodigoRutaPk()
    {
        return $this->codigoRutaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return TteRuta
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
     * Add ciudadesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadesRel
     * @return TteRuta
     */
    public function addCiudadesRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadesRel)
    {
        $this->ciudadesRel[] = $ciudadesRel;

        return $this;
    }

    /**
     * Remove ciudadesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadesRel
     */
    public function removeCiudadesRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadesRel)
    {
        $this->ciudadesRel->removeElement($ciudadesRel);
    }

    /**
     * Get ciudadesRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCiudadesRel()
    {
        return $this->ciudadesRel;
    }

    /**
     * Add guiasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasRel
     * @return TteRuta
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

    /**
     * Add despachosRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespacho $despachosRel
     * @return TteRuta
     */
    public function addDespachosRel(\Brasa\TransporteBundle\Entity\TteDespacho $despachosRel)
    {
        $this->despachosRel[] = $despachosRel;

        return $this;
    }

    /**
     * Remove despachosRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespacho $despachosRel
     */
    public function removeDespachosRel(\Brasa\TransporteBundle\Entity\TteDespacho $despachosRel)
    {
        $this->despachosRel->removeElement($despachosRel);
    }

    /**
     * Get despachosRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDespachosRel()
    {
        return $this->despachosRel;
    }
}
