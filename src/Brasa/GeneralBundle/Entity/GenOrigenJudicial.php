<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_origen_judicial")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenOrigenJudicialRepository")
 */
class GenOrigenJudicial
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_origen_judicial_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoOrigenJudicialPk;     
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */      
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCliente", mappedBy="origenJudicialRel")
     */
    protected $turClientesOrigenJudicialRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->turClientesOrigenJudicialRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoOrigenJudicialPk
     *
     * @return integer
     */
    public function getCodigoOrigenJudicialPk()
    {
        return $this->codigoOrigenJudicialPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenOrigenJudicial
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
     * Add turClientesOrigenJudicialRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesOrigenJudicialRel
     *
     * @return GenOrigenJudicial
     */
    public function addTurClientesOrigenJudicialRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesOrigenJudicialRel)
    {
        $this->turClientesOrigenJudicialRel[] = $turClientesOrigenJudicialRel;

        return $this;
    }

    /**
     * Remove turClientesOrigenJudicialRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesOrigenJudicialRel
     */
    public function removeTurClientesOrigenJudicialRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesOrigenJudicialRel)
    {
        $this->turClientesOrigenJudicialRel->removeElement($turClientesOrigenJudicialRel);
    }

    /**
     * Get turClientesOrigenJudicialRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurClientesOrigenJudicialRel()
    {
        return $this->turClientesOrigenJudicialRel;
    }
}
