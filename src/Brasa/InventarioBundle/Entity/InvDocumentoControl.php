<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_documento_control")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDocumentoControlRepository")
 */
class InvDocumentoControl
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_padre_pk", type="integer")
     */ 
    private $codigoDocumentoPadrePk;    

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_hijo_pk", type="integer")
     */ 
    private $codigoDocumentoHijoPk;    
    
    /**
     * @ORM\Column(name="hereda_precio", type="boolean")
     */    
    private $heredaPrecio = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="InvDocumento", inversedBy="InvDocumentoControl")
     * @ORM\JoinColumn(name="codigo_documento_padre_pk", referencedColumnName="codigo_documento_pk")
     */
    protected $documentoPadreRel;      

    /**
     * @ORM\ManyToOne(targetEntity="InvDocumento", inversedBy="InvDocumentoControl")
     * @ORM\JoinColumn(name="codigo_documento_hijo_pk", referencedColumnName="codigo_documento_pk")
     */
    protected $documentoHijoRel;    
    

    /**
     * Set codigoDocumentoPadrePk
     *
     * @param integer $codigoDocumentoPadrePk
     * @return InvDocumentoControl
     */
    public function setCodigoDocumentoPadrePk($codigoDocumentoPadrePk)
    {
        $this->codigoDocumentoPadrePk = $codigoDocumentoPadrePk;

        return $this;
    }

    /**
     * Get codigoDocumentoPadrePk
     *
     * @return integer 
     */
    public function getCodigoDocumentoPadrePk()
    {
        return $this->codigoDocumentoPadrePk;
    }

    /**
     * Set codigoDocumentoHijoPk
     *
     * @param integer $codigoDocumentoHijoPk
     * @return InvDocumentoControl
     */
    public function setCodigoDocumentoHijoPk($codigoDocumentoHijoPk)
    {
        $this->codigoDocumentoHijoPk = $codigoDocumentoHijoPk;

        return $this;
    }

    /**
     * Get codigoDocumentoHijoPk
     *
     * @return integer 
     */
    public function getCodigoDocumentoHijoPk()
    {
        return $this->codigoDocumentoHijoPk;
    }

    /**
     * Set heredaPrecio
     *
     * @param boolean $heredaPrecio
     * @return InvDocumentoControl
     */
    public function setHeredaPrecio($heredaPrecio)
    {
        $this->heredaPrecio = $heredaPrecio;

        return $this;
    }

    /**
     * Get heredaPrecio
     *
     * @return boolean 
     */
    public function getHeredaPrecio()
    {
        return $this->heredaPrecio;
    }

    /**
     * Set documentoPadreRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumento $documentoPadreRel
     * @return InvDocumentoControl
     */
    public function setDocumentoPadreRel(\Brasa\InventarioBundle\Entity\InvDocumento $documentoPadreRel = null)
    {
        $this->documentoPadreRel = $documentoPadreRel;

        return $this;
    }

    /**
     * Get documentoPadreRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvDocumento 
     */
    public function getDocumentoPadreRel()
    {
        return $this->documentoPadreRel;
    }

    /**
     * Set documentoHijoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumento $documentoHijoRel
     * @return InvDocumentoControl
     */
    public function setDocumentoHijoRel(\Brasa\InventarioBundle\Entity\InvDocumento $documentoHijoRel = null)
    {
        $this->documentoHijoRel = $documentoHijoRel;

        return $this;
    }

    /**
     * Get documentoHijoRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvDocumento 
     */
    public function getDocumentoHijoRel()
    {
        return $this->documentoHijoRel;
    }
}
