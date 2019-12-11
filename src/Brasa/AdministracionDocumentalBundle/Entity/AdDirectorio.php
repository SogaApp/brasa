<?php

namespace Brasa\AdministracionDocumentalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ad_directorio")
 * @ORM\Entity(repositoryClass="Brasa\AdministracionDocumentalBundle\Repository\AdDirectorioRepository")
 */
class AdDirectorio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_directorio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDirectorioPk;    

    /**
     * @ORM\Column(name="nombre", type="string", length=250, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */    
    private $numero = 0;  
    
    /**
     * @ORM\Column(name="numeroArchivos", type="integer", nullable=true)
     */    
    private $numeroArchivos = 0;    

    /**
     * @ORM\Column(name="ruta_principal", type="string", length=250, nullable=true)
     */    
    private $rutaPrincipal;    
    
    /**
     * @ORM\OneToMany(targetEntity="AdArchivo", mappedBy="directorioRel")
     */
    protected $archivosDirectorioRel;      
    
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
     * Set numero
     *
     * @param integer $numero
     *
     * @return AdDirectorio
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return AdDirectorio
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
     * Set numeroArchivos
     *
     * @param integer $numeroArchivos
     *
     * @return AdDirectorio
     */
    public function setNumeroArchivos($numeroArchivos)
    {
        $this->numeroArchivos = $numeroArchivos;

        return $this;
    }

    /**
     * Get numeroArchivos
     *
     * @return integer
     */
    public function getNumeroArchivos()
    {
        return $this->numeroArchivos;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivosDirectorioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add archivosDirectorioRel
     *
     * @param \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo $archivosDirectorioRel
     *
     * @return AdDirectorio
     */
    public function addArchivosDirectorioRel(\Brasa\AdministracionDocumentalBundle\Entity\AdArchivo $archivosDirectorioRel)
    {
        $this->archivosDirectorioRel[] = $archivosDirectorioRel;

        return $this;
    }

    /**
     * Remove archivosDirectorioRel
     *
     * @param \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo $archivosDirectorioRel
     */
    public function removeArchivosDirectorioRel(\Brasa\AdministracionDocumentalBundle\Entity\AdArchivo $archivosDirectorioRel)
    {
        $this->archivosDirectorioRel->removeElement($archivosDirectorioRel);
    }

    /**
     * Get archivosDirectorioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArchivosDirectorioRel()
    {
        return $this->archivosDirectorioRel;
    }

    /**
     * Set rutaPrincipal
     *
     * @param string $rutaPrincipal
     *
     * @return AdDirectorio
     */
    public function setRutaPrincipal($rutaPrincipal)
    {
        $this->rutaPrincipal = $rutaPrincipal;

        return $this;
    }

    /**
     * Get rutaPrincipal
     *
     * @return string
     */
    public function getRutaPrincipal()
    {
        return $this->rutaPrincipal;
    }
}
