<?php

namespace Brasa\AfiliacionBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_razon_social")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiRazonSocialRepository")
 * @DoctrineAssert\UniqueEntity(fields={"nit"},message="Ya existe este nit")
 */
class AfiRazonSocial
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_razon_social_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRazonSocialPk;

    /**
     * @ORM\Column(name="nombre" , type="string")
     */
    private $nombre;

    /**
     * @ORM\Column(name="nit" , type="string")
     */
    private $nit;

    /**
     * @ORM\Column(name="dv" , type="string" ,length=1)
     */
    private $dv;

    /**
     * @ORM\Column(name="tipo_identificacion", type="string", length=2, nullable=true)
     */
    private $tipoIdentificacion;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiCliente" , mappedBy="razonSocialRel")
     */
    private $afiClientesRazonSocialRel;

    /**
     * Get codigoRazonSocialPk
     *
     * @return integer
     */
    public function getCodigoRazonSocialPk()
    {
        return $this->codigoRazonSocialPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return AfiRazonSocial
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
     * Set nit
     *
     * @param string $nit
     *
     * @return AfiRazonSocial
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set dv
     *
     * @param string $dv
     *
     * @return AfiRazonSocial
     */
    public function setDv($dv)
    {
        $this->dv = $dv;

        return $this;
    }

    /**
     * Get dv
     *
     * @return string
     */
    public function getDv()
    {
        return $this->dv;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->afiClientesRazonSocialRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add afiClientesRazonSocialRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesRazonSocialRel
     *
     * @return AfiRazonSocial
     */
    public function addAfiClientesRazonSocialRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesRazonSocialRel)
    {
        $this->afiClientesRazonSocialRel[] = $afiClientesRazonSocialRel;

        return $this;
    }

    /**
     * Remove afiClientesRazonSocialRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesRazonSocialRel
     */
    public function removeAfiClientesRazonSocialRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesRazonSocialRel)
    {
        $this->afiClientesRazonSocialRel->removeElement($afiClientesRazonSocialRel);
    }

    /**
     * Get afiClientesRazonSocialRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiClientesRazonSocialRel()
    {
        return $this->afiClientesRazonSocialRel;
    }

    /**
     * Set tipoIdentificacion
     *
     * @param string $tipoIdentificacion
     *
     * @return AfiRazonSocial
     */
    public function setTipoIdentificacion($tipoIdentificacion)
    {
        $this->tipoIdentificacion = $tipoIdentificacion;

        return $this;
    }

    /**
     * Get tipoIdentificacion
     *
     * @return string
     */
    public function getTipoIdentificacion()
    {
        return $this->tipoIdentificacion;
    }
}
