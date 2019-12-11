<?php

namespace Brasa\AdministracionDocumentalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ad_archivo")
 * @ORM\Entity(repositoryClass="Brasa\AdministracionDocumentalBundle\Repository\AdArchivoRepository")
 */
class AdArchivo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_archivo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoArchivoPk;         

    /**
     * @ORM\Column(name="codigo_documento_fk", type="integer", nullable=true)
     */    
    private $codigoDocumentoFk;    
    
    /**
     * @ORM\Column(name="codigo_directorio_fk", type="integer", nullable=true)
     */    
    private $codigoDirectorioFk;    
    
    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */    
    private $numero = 0;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=250, nullable=true)
     */    
    private $nombre;     
    
    /**
     * @ORM\Column(name="extensionOriginal", type="string", length=250, nullable=true)
     */    
    private $extensionOriginal;
    
    /**
     * @ORM\Column(name="tipo", type="string", length=250, nullable=true)
     */    
    private $tipo;            
    
    /**
     * @ORM\Column(name="tamano", type="float", nullable=true)
     */    
    private $tamano = 0;               
    
    /**
     * @ORM\Column(name="descripcion", type="string", length=100, nullable=true)
     */    
    private $descripcion;      
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=250, nullable=true)
     */    
    private $comentarios;      
    
    /**
     * @ORM\ManyToOne(targetEntity="AdDocumento", inversedBy="archivosDocumentoRel")
     * @ORM\JoinColumn(name="codigo_documento_fk", referencedColumnName="codigo_documento_pk")
     */
    protected $documentoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="AdDirectorio", inversedBy="archivosDirectorioRel")
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
     * Set codigoDocumentoFk
     *
     * @param integer $codigoDocumentoFk
     *
     * @return AdArchivo
     */
    public function setCodigoDocumentoFk($codigoDocumentoFk)
    {
        $this->codigoDocumentoFk = $codigoDocumentoFk;

        return $this;
    }

    /**
     * Get codigoDocumentoFk
     *
     * @return integer
     */
    public function getCodigoDocumentoFk()
    {
        return $this->codigoDocumentoFk;
    }

    /**
     * Set codigoDirectorioFk
     *
     * @param integer $codigoDirectorioFk
     *
     * @return AdArchivo
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
     * Set numero
     *
     * @param integer $numero
     *
     * @return AdArchivo
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
     * @return AdArchivo
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
     * Set extensionOriginal
     *
     * @param string $extensionOriginal
     *
     * @return AdArchivo
     */
    public function setExtensionOriginal($extensionOriginal)
    {
        $this->extensionOriginal = $extensionOriginal;

        return $this;
    }

    /**
     * Get extensionOriginal
     *
     * @return string
     */
    public function getExtensionOriginal()
    {
        return $this->extensionOriginal;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return AdArchivo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set tamano
     *
     * @param float $tamano
     *
     * @return AdArchivo
     */
    public function setTamano($tamano)
    {
        $this->tamano = $tamano;

        return $this;
    }

    /**
     * Get tamano
     *
     * @return float
     */
    public function getTamano()
    {
        return $this->tamano;
    }

    /**
     * Set documentoRel
     *
     * @param \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento $documentoRel
     *
     * @return AdArchivo
     */
    public function setDocumentoRel(\Brasa\AdministracionDocumentalBundle\Entity\AdDocumento $documentoRel = null)
    {
        $this->documentoRel = $documentoRel;

        return $this;
    }

    /**
     * Get documentoRel
     *
     * @return \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento
     */
    public function getDocumentoRel()
    {
        return $this->documentoRel;
    }

    /**
     * Set directorioRel
     *
     * @param \Brasa\AdministracionDocumentalBundle\Entity\AdDirectorio $directorioRel
     *
     * @return AdArchivo
     */
    public function setDirectorioRel(\Brasa\AdministracionDocumentalBundle\Entity\AdDirectorio $directorioRel = null)
    {
        $this->directorioRel = $directorioRel;

        return $this;
    }

    /**
     * Get directorioRel
     *
     * @return \Brasa\AdministracionDocumentalBundle\Entity\AdDirectorio
     */
    public function getDirectorioRel()
    {
        return $this->directorioRel;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return AdArchivo
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return AdArchivo
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }
}
