<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_banco")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenBancoRepository")
 */
class GenBanco
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_banco_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoBancoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=60)
     */
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="GenCuenta", mappedBy="bancoRel")
     */
    protected $cuentasBancoRel;

    /**
     * Get codigoBancoPk
     *
     * @return integer
     */
    public function getCodigoBancoPk()
    {
        return $this->codigoBancoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenBanco
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
     * Constructor
     */
    public function __construct()
    {
        $this->cuentasBancoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add cuentasBancoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCuenta $cuentasBancoRel
     *
     * @return GenBanco
     */
    public function addCuentasBancoRel(\Brasa\GeneralBundle\Entity\GenCuenta $cuentasBancoRel)
    {
        $this->cuentasBancoRel[] = $cuentasBancoRel;

        return $this;
    }

    /**
     * Remove cuentasBancoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCuenta $cuentasBancoRel
     */
    public function removeCuentasBancoRel(\Brasa\GeneralBundle\Entity\GenCuenta $cuentasBancoRel)
    {
        $this->cuentasBancoRel->removeElement($cuentasBancoRel);
    }

    /**
     * Get cuentasBancoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentasBancoRel()
    {
        return $this->cuentasBancoRel;
    }
}
