<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_departamento")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenDepartamentoRepository")
 */
class GenDepartamento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_departamento_pk", type="integer")
     */
    private $codigoDepartamentoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     * @Assert\NotNull()(message="Debe escribir un nombre")
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_dane", type="string", length=2)
     */
    private $codigoDane;

    /**
     * @ORM\Column(name="codigo_pais_fk", type="integer", nullable=true)
     */
    private $codigoPaisFk;
    
    /**
     * @ORM\OneToMany(targetEntity="GenCiudad", mappedBy="departamentoRel")
     */
    protected $ciudadesRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="GenPais", inversedBy="departamentosRel")
     * @ORM\JoinColumn(name="codigo_pais_fk", referencedColumnName="codigo_pais_pk")
     */
    protected $paisRel;


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ciudadesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoDepartamentoPk
     *
     * @param integer $codigoDepartamentoPk
     *
     * @return GenDepartamento
     */
    public function setCodigoDepartamentoPk($codigoDepartamentoPk)
    {
        $this->codigoDepartamentoPk = $codigoDepartamentoPk;

        return $this;
    }

    /**
     * Get codigoDepartamentoPk
     *
     * @return integer
     */
    public function getCodigoDepartamentoPk()
    {
        return $this->codigoDepartamentoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenDepartamento
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
     * Set codigoDane
     *
     * @param string $codigoDane
     *
     * @return GenDepartamento
     */
    public function setCodigoDane($codigoDane)
    {
        $this->codigoDane = $codigoDane;

        return $this;
    }

    /**
     * Get codigoDane
     *
     * @return string
     */
    public function getCodigoDane()
    {
        return $this->codigoDane;
    }

    /**
     * Set codigoPaisFk
     *
     * @param integer $codigoPaisFk
     *
     * @return GenDepartamento
     */
    public function setCodigoPaisFk($codigoPaisFk)
    {
        $this->codigoPaisFk = $codigoPaisFk;

        return $this;
    }

    /**
     * Get codigoPaisFk
     *
     * @return integer
     */
    public function getCodigoPaisFk()
    {
        return $this->codigoPaisFk;
    }

    /**
     * Add ciudadesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadesRel
     *
     * @return GenDepartamento
     */
    public function addCiudadesRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadesRel)
    {
        $this->ciudadesRel[] = $ciudadesRel;

        return $this;
    }

    /**
     * Remove ciudadesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadesRel
     */
    public function removeCiudadesRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadesRel)
    {
        $this->ciudadesRel->removeElement($ciudadesRel);
    }

    /**
     * Get ciudadesRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCiudadesRel()
    {
        return $this->ciudadesRel;
    }

    /**
     * Set paisRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenPais $paisRel
     *
     * @return GenDepartamento
     */
    public function setPaisRel(\Brasa\GeneralBundle\Entity\GenPais $paisRel = null)
    {
        $this->paisRel = $paisRel;

        return $this;
    }

    /**
     * Get paisRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenPais
     */
    public function getPaisRel()
    {
        return $this->paisRel;
    }
}
