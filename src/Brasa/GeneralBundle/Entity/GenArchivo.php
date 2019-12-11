<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_archivo")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenArchivoRepository")
 */
class GenArchivo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_archivo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoArchivoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;
    
    /**
     * @ORM\Column(name="codigo_directorio_fk", type="integer", nullable=true)
     */
    private $codigoDirectorioFk;

    /**
     * @ORM\Column(name="archivo", type="string", length=250)
     */
    private $archivo;
    
    /**
     * @ORM\Column(name="descripcion", type="string", length=70, nullable=true)
     */
    private $descripcion;
   
    /**
     * @ORM\ManyToOne(targetEntity="GenDirectorio", inversedBy="directoriosArchivoRel")
     * @ORM\JoinColumn(name="codigo_directorio_fk", referencedColumnName="codigo_directorio_pk")
     */
    protected $directorioRel;    
    


    

    /**
     * Get codigoArchivoPk
     *
     * @return integer
     */
    public function getCodigoArchivoPk()
    {
        return $this->codigoArchivoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenArchivo
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
     * Set codigoDirectorioFk
     *
     * @param integer $codigoDirectorioFk
     *
     * @return GenArchivo
     */
    public function setCodigoDirectorioFk($codigoDirectorioFk)
    {
        $this->codigoDirectorioFk = $codigoDirectorioFk;

        return $this;
    }

    /**
     * Get codigoDirectorioFk
     *
     * @return integer
     */
    public function getCodigoDirectorioFk()
    {
        return $this->codigoDirectorioFk;
    }

    /**
     * Set archivo
     *
     * @param string $archivo
     *
     * @return GenArchivo
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;

        return $this;
    }

    /**
     * Get archivo
     *
     * @return string
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return GenArchivo
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set directorioRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenDirectorio $directorioRel
     *
     * @return GenArchivo
     */
    public function setDirectorioRel(\Brasa\GeneralBundle\Entity\GenDirectorio $directorioRel = null)
    {
        $this->directorioRel = $directorioRel;

        return $this;
    }

    /**
     * Get directorioRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenDirectorio
     */
    public function getDirectorioRel()
    {
        return $this->directorioRel;
    }
}
