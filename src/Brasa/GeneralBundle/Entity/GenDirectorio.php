<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_directorio")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenDirectorioRepository")
 */
class GenDirectorio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_directorio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDirectorioPk;
    
    /**
     * @ORM\Column(name="codigo_directorio_padre_fk", type="integer", nullable=true)
     */
    private $codigoDirectorioPadreFk;

    /**
     * @ORM\Column(name="ruta", type="string", length=500)
     */
    private $ruta; 
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="GenArchivo", mappedBy="directorioRel")
     */
    protected $directoriosArchivoRel;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->directoriosArchivoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDirectorioPk
     *
     * @return integer
     */
    public function getCodigoDirectorioPk()
    {
        return $this->codigoDirectorioPk;
    }

    /**
     * Set codigoDirectorioPadreFk
     *
     * @param integer $codigoDirectorioPadreFk
     *
     * @return GenDirectorio
     */
    public function setCodigoDirectorioPadreFk($codigoDirectorioPadreFk)
    {
        $this->codigoDirectorioPadreFk = $codigoDirectorioPadreFk;

        return $this;
    }

    /**
     * Get codigoDirectorioPadreFk
     *
     * @return integer
     */
    public function getCodigoDirectorioPadreFk()
    {
        return $this->codigoDirectorioPadreFk;
    }

    /**
     * Set ruta
     *
     * @param string $ruta
     *
     * @return GenDirectorio
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;

        return $this;
    }

    /**
     * Get ruta
     *
     * @return string
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenDirectorio
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
     * Add directoriosArchivoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenArchivo $directoriosArchivoRel
     *
     * @return GenDirectorio
     */
    public function addDirectoriosArchivoRel(\Brasa\GeneralBundle\Entity\GenArchivo $directoriosArchivoRel)
    {
        $this->directoriosArchivoRel[] = $directoriosArchivoRel;

        return $this;
    }

    /**
     * Remove directoriosArchivoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenArchivo $directoriosArchivoRel
     */
    public function removeDirectoriosArchivoRel(\Brasa\GeneralBundle\Entity\GenArchivo $directoriosArchivoRel)
    {
        $this->directoriosArchivoRel->removeElement($directoriosArchivoRel);
    }

    /**
     * Get directoriosArchivoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDirectoriosArchivoRel()
    {
        return $this->directoriosArchivoRel;
    }
}
