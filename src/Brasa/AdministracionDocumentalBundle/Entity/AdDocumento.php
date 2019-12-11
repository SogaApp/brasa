<?php

namespace Brasa\AdministracionDocumentalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ad_documento")
 * @ORM\Entity(repositoryClass="Brasa\AdministracionDocumentalBundle\Repository\AdDocumentoRepository")
 */
class AdDocumento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDocumentoPk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;  
    
    /**
     * @ORM\OneToMany(targetEntity="AdArchivo", mappedBy="documentoRel")
     */
    protected $archivosDocumentoRel;     
   

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivosDocumentoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDocumentoPk
     *
     * @return integer
     */
    public function getCodigoDocumentoPk()
    {
        return $this->codigoDocumentoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return AdDocumento
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
     * Add archivosDocumentoRel
     *
     * @param \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo $archivosDocumentoRel
     *
     * @return AdDocumento
     */
    public function addArchivosDocumentoRel(\Brasa\AdministracionDocumentalBundle\Entity\AdArchivo $archivosDocumentoRel)
    {
        $this->archivosDocumentoRel[] = $archivosDocumentoRel;

        return $this;
    }

    /**
     * Remove archivosDocumentoRel
     *
     * @param \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo $archivosDocumentoRel
     */
    public function removeArchivosDocumentoRel(\Brasa\AdministracionDocumentalBundle\Entity\AdArchivo $archivosDocumentoRel)
    {
        $this->archivosDocumentoRel->removeElement($archivosDocumentoRel);
    }

    /**
     * Get archivosDocumentoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArchivosDocumentoRel()
    {
        return $this->archivosDocumentoRel;
    }
}
