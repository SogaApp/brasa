<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_motivo_terminacion_contrato")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuMotivoTerminacionContratoRepository")
 */
class RhuMotivoTerminacionContrato
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_motivo_terminacion_contrato_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoMotivoTerminacionContratoPk;
    
    /**
     * @ORM\Column(name="motivo", type="string", length=80, nullable=true)
     */    
    private $motivo;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="terminacionContratoRel")
     */
    protected $contratosMotivoTerminacionContratoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacion", mappedBy="motivoTerminacionRel")
     */
    protected $liquidacionesMotivoTerminacionContratoRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosMotivoTerminacionContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->liquidacionesMotivoTerminacionContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoMotivoTerminacionContratoPk
     *
     * @return integer
     */
    public function getCodigoMotivoTerminacionContratoPk()
    {
        return $this->codigoMotivoTerminacionContratoPk;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     *
     * @return RhuMotivoTerminacionContrato
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo
     *
     * @return string
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Add contratosMotivoTerminacionContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosMotivoTerminacionContratoRel
     *
     * @return RhuMotivoTerminacionContrato
     */
    public function addContratosMotivoTerminacionContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosMotivoTerminacionContratoRel)
    {
        $this->contratosMotivoTerminacionContratoRel[] = $contratosMotivoTerminacionContratoRel;

        return $this;
    }

    /**
     * Remove contratosMotivoTerminacionContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosMotivoTerminacionContratoRel
     */
    public function removeContratosMotivoTerminacionContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosMotivoTerminacionContratoRel)
    {
        $this->contratosMotivoTerminacionContratoRel->removeElement($contratosMotivoTerminacionContratoRel);
    }

    /**
     * Get contratosMotivoTerminacionContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosMotivoTerminacionContratoRel()
    {
        return $this->contratosMotivoTerminacionContratoRel;
    }

    /**
     * Add liquidacionesMotivoTerminacionContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesMotivoTerminacionContratoRel
     *
     * @return RhuMotivoTerminacionContrato
     */
    public function addLiquidacionesMotivoTerminacionContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesMotivoTerminacionContratoRel)
    {
        $this->liquidacionesMotivoTerminacionContratoRel[] = $liquidacionesMotivoTerminacionContratoRel;

        return $this;
    }

    /**
     * Remove liquidacionesMotivoTerminacionContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesMotivoTerminacionContratoRel
     */
    public function removeLiquidacionesMotivoTerminacionContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesMotivoTerminacionContratoRel)
    {
        $this->liquidacionesMotivoTerminacionContratoRel->removeElement($liquidacionesMotivoTerminacionContratoRel);
    }

    /**
     * Get liquidacionesMotivoTerminacionContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLiquidacionesMotivoTerminacionContratoRel()
    {
        return $this->liquidacionesMotivoTerminacionContratoRel;
    }
}
