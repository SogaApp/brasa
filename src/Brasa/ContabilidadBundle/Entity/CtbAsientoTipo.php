<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_asiento_tipo")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbAsientoTipoRepository")
 */
class CtbAsientoTipo
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_asiento_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoAsientoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;      

    /**
     * @ORM\Column(name="consecutivo", type="integer")
     */        
    private $consecutivo = 1;          
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbComprobante", inversedBy="CtbAsientoTipo")
     * @ORM\JoinColumn(name="codigo_comprobante_fk", referencedColumnName="codigo_comprobante_pk")
     */
    protected $comprobanteRel;

    /**
     * @ORM\OneToMany(targetEntity="CtbAsientoDetalle", mappedBy="asientoTipoRel")
     */
    protected $asientosDetallesAsientoTipoRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->asientosDetallesAsientoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoAsientoTipoPk
     *
     * @return integer
     */
    public function getCodigoAsientoTipoPk()
    {
        return $this->codigoAsientoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CtbAsientoTipo
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
     * Set consecutivo
     *
     * @param integer $consecutivo
     *
     * @return CtbAsientoTipo
     */
    public function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;

        return $this;
    }

    /**
     * Get consecutivo
     *
     * @return integer
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * Set comprobanteRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbComprobante $comprobanteRel
     *
     * @return CtbAsientoTipo
     */
    public function setComprobanteRel(\Brasa\ContabilidadBundle\Entity\CtbComprobante $comprobanteRel = null)
    {
        $this->comprobanteRel = $comprobanteRel;

        return $this;
    }

    /**
     * Get comprobanteRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbComprobante
     */
    public function getComprobanteRel()
    {
        return $this->comprobanteRel;
    }

    /**
     * Add asientosDetallesAsientoTipoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesAsientoTipoRel
     *
     * @return CtbAsientoTipo
     */
    public function addAsientosDetallesAsientoTipoRel(\Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesAsientoTipoRel)
    {
        $this->asientosDetallesAsientoTipoRel[] = $asientosDetallesAsientoTipoRel;

        return $this;
    }

    /**
     * Remove asientosDetallesAsientoTipoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesAsientoTipoRel
     */
    public function removeAsientosDetallesAsientoTipoRel(\Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesAsientoTipoRel)
    {
        $this->asientosDetallesAsientoTipoRel->removeElement($asientosDetallesAsientoTipoRel);
    }

    /**
     * Get asientosDetallesAsientoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAsientosDetallesAsientoTipoRel()
    {
        return $this->asientosDetallesAsientoTipoRel;
    }
}
