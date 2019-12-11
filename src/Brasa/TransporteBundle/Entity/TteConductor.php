<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_conductor")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteConductorRepository")
 */
class TteConductor
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_conductor_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoConductorPk;  
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=50)
     */
    private $nombreCorto; 
    
    /**
     * @ORM\OneToMany(targetEntity="TteDespacho", mappedBy="conductorRel")
     */
    protected $despachosRel;    

    /**
     * @ORM\OneToMany(targetEntity="TteProgramacionRecogida", mappedBy="conductorRel")
     */
    protected $programacionesRecogidasConductorRel;    


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->despachosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoConductorPk
     *
     * @return integer
     */
    public function getCodigoConductorPk()
    {
        return $this->codigoConductorPk;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return TteConductor
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Add despachosRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespacho $despachosRel
     *
     * @return TteConductor
     */
    public function addDespachosRel(\Brasa\TransporteBundle\Entity\TteDespacho $despachosRel)
    {
        $this->despachosRel[] = $despachosRel;

        return $this;
    }

    /**
     * Remove despachosRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespacho $despachosRel
     */
    public function removeDespachosRel(\Brasa\TransporteBundle\Entity\TteDespacho $despachosRel)
    {
        $this->despachosRel->removeElement($despachosRel);
    }

    /**
     * Get despachosRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDespachosRel()
    {
        return $this->despachosRel;
    }

    /**
     * Add programacionesRecogidasConductorRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteProgramacionRecogida $programacionesRecogidasConductorRel
     *
     * @return TteConductor
     */
    public function addProgramacionesRecogidasConductorRel(\Brasa\TransporteBundle\Entity\TteProgramacionRecogida $programacionesRecogidasConductorRel)
    {
        $this->programacionesRecogidasConductorRel[] = $programacionesRecogidasConductorRel;

        return $this;
    }

    /**
     * Remove programacionesRecogidasConductorRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteProgramacionRecogida $programacionesRecogidasConductorRel
     */
    public function removeProgramacionesRecogidasConductorRel(\Brasa\TransporteBundle\Entity\TteProgramacionRecogida $programacionesRecogidasConductorRel)
    {
        $this->programacionesRecogidasConductorRel->removeElement($programacionesRecogidasConductorRel);
    }

    /**
     * Get programacionesRecogidasConductorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesRecogidasConductorRel()
    {
        return $this->programacionesRecogidasConductorRel;
    }
}
