<?php

namespace Brasa\InventarioBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_documento_tipo")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDocumentoTipoRepository")
 */
class InvDocumentoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDocumentoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="InvMovimiento", mappedBy="documentoTipoRel")
     */
    protected $movimientosDocumentoTipoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="InvDocumento", mappedBy="documentoTipoRel")
     */
    protected $documentosDocumentoTipoRel;    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientosDocumentoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documentosDocumentoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDocumentoTipoPk
     *
     * @return integer
     */
    public function getCodigoDocumentoTipoPk()
    {
        return $this->codigoDocumentoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return InvDocumentoTipo
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
     * Add movimientosDocumentoTipoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimiento $movimientosDocumentoTipoRel
     *
     * @return InvDocumentoTipo
     */
    public function addMovimientosDocumentoTipoRel(\Brasa\InventarioBundle\Entity\InvMovimiento $movimientosDocumentoTipoRel)
    {
        $this->movimientosDocumentoTipoRel[] = $movimientosDocumentoTipoRel;

        return $this;
    }

    /**
     * Remove movimientosDocumentoTipoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimiento $movimientosDocumentoTipoRel
     */
    public function removeMovimientosDocumentoTipoRel(\Brasa\InventarioBundle\Entity\InvMovimiento $movimientosDocumentoTipoRel)
    {
        $this->movimientosDocumentoTipoRel->removeElement($movimientosDocumentoTipoRel);
    }

    /**
     * Get movimientosDocumentoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientosDocumentoTipoRel()
    {
        return $this->movimientosDocumentoTipoRel;
    }

    /**
     * Add documentosDocumentoTipoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumento $documentosDocumentoTipoRel
     *
     * @return InvDocumentoTipo
     */
    public function addDocumentosDocumentoTipoRel(\Brasa\InventarioBundle\Entity\InvDocumento $documentosDocumentoTipoRel)
    {
        $this->documentosDocumentoTipoRel[] = $documentosDocumentoTipoRel;

        return $this;
    }

    /**
     * Remove documentosDocumentoTipoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumento $documentosDocumentoTipoRel
     */
    public function removeDocumentosDocumentoTipoRel(\Brasa\InventarioBundle\Entity\InvDocumento $documentosDocumentoTipoRel)
    {
        $this->documentosDocumentoTipoRel->removeElement($documentosDocumentoTipoRel);
    }

    /**
     * Get documentosDocumentoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumentosDocumentoTipoRel()
    {
        return $this->documentosDocumentoTipoRel;
    }
}
