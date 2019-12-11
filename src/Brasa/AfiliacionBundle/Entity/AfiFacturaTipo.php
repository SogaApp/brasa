<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_factura_tipo")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiFacturaTipoRepository")
 */
class AfiFacturaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaTipoPk;                   
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                             
    
    /**
     * @ORM\OneToMany(targetEntity="AfiFactura", mappedBy="facturaTipoRel")
     */
    protected $facturasFacturaTipoRel; 


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasFacturaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaTipoPk
     *
     * @return integer
     */
    public function getCodigoFacturaTipoPk()
    {
        return $this->codigoFacturaTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return AfiFacturaTipo
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
     * Add facturasFacturaTipoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFactura $facturasFacturaTipoRel
     *
     * @return AfiFacturaTipo
     */
    public function addFacturasFacturaTipoRel(\Brasa\AfiliacionBundle\Entity\AfiFactura $facturasFacturaTipoRel)
    {
        $this->facturasFacturaTipoRel[] = $facturasFacturaTipoRel;

        return $this;
    }

    /**
     * Remove facturasFacturaTipoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFactura $facturasFacturaTipoRel
     */
    public function removeFacturasFacturaTipoRel(\Brasa\AfiliacionBundle\Entity\AfiFactura $facturasFacturaTipoRel)
    {
        $this->facturasFacturaTipoRel->removeElement($facturasFacturaTipoRel);
    }

    /**
     * Get facturasFacturaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasFacturaTipoRel()
    {
        return $this->facturasFacturaTipoRel;
    }
}
