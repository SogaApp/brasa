<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_origen_capital")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenOrigenCapitalRepository")
 */
class GenOrigenCapital
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_origen_capital_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoOrigenCapitalPk;     
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */      
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCliente", mappedBy="origenCapitalRel")
     */
    protected $turClientesOrigenCapitalRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->turClientesOrigenCapitalRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoOrigenCapitalPk
     *
     * @return integer
     */
    public function getCodigoOrigenCapitalPk()
    {
        return $this->codigoOrigenCapitalPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenOrigenCapital
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
     * Add turClientesOrigenCapitalRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesOrigenCapitalRel
     *
     * @return GenOrigenCapital
     */
    public function addTurClientesOrigenCapitalRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesOrigenCapitalRel)
    {
        $this->turClientesOrigenCapitalRel[] = $turClientesOrigenCapitalRel;

        return $this;
    }

    /**
     * Remove turClientesOrigenCapitalRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesOrigenCapitalRel
     */
    public function removeTurClientesOrigenCapitalRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesOrigenCapitalRel)
    {
        $this->turClientesOrigenCapitalRel->removeElement($turClientesOrigenCapitalRel);
    }

    /**
     * Get turClientesOrigenCapitalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurClientesOrigenCapitalRel()
    {
        return $this->turClientesOrigenCapitalRel;
    }
}
