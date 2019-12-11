<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_pais")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenPaisRepository")
 */
class GenPais
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pais_pk", type="integer")
     */
    private $codigoPaisPk;

    /**
     * @ORM\Column(name="pais", type="string", length=50)
     * @Assert\NotNull()(message="Debe escribir un pais")
     */
    private $pais;
    
    /**
     * @ORM\Column(name="gentilicio", type="string", length=50)
     * @Assert\NotNull()(message="Debe escribir un gentilicio")
     */
    private $gentilicio;
    
    /**
     * @ORM\OneToMany(targetEntity="GenDepartamento", mappedBy="paisRel")
     */
    protected $departamentosRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->departamentosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoPaisPk
     *
     * @param integer $codigoPaisPk
     *
     * @return GenPais
     */
    public function setCodigoPaisPk($codigoPaisPk)
    {
        $this->codigoPaisPk = $codigoPaisPk;

        return $this;
    }

    /**
     * Get codigoPaisPk
     *
     * @return integer
     */
    public function getCodigoPaisPk()
    {
        return $this->codigoPaisPk;
    }

    /**
     * Set pais
     *
     * @param string $pais
     *
     * @return GenPais
     */
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Get pais
     *
     * @return string
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Set gentilicio
     *
     * @param string $gentilicio
     *
     * @return GenPais
     */
    public function setGentilicio($gentilicio)
    {
        $this->gentilicio = $gentilicio;

        return $this;
    }

    /**
     * Get gentilicio
     *
     * @return string
     */
    public function getGentilicio()
    {
        return $this->gentilicio;
    }

    /**
     * Add departamentosRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenDepartamento $departamentosRel
     *
     * @return GenPais
     */
    public function addDepartamentosRel(\Brasa\GeneralBundle\Entity\GenDepartamento $departamentosRel)
    {
        $this->departamentosRel[] = $departamentosRel;

        return $this;
    }

    /**
     * Remove departamentosRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenDepartamento $departamentosRel
     */
    public function removeDepartamentosRel(\Brasa\GeneralBundle\Entity\GenDepartamento $departamentosRel)
    {
        $this->departamentosRel->removeElement($departamentosRel);
    }

    /**
     * Get departamentosRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDepartamentosRel()
    {
        return $this->departamentosRel;
    }
}
