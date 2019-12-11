<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_contrato_clase")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuContratoClaseRepository")
 */
class RhuContratoClase
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_clase_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoClasePk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=200, nullable=true)
     */    
    private $nombre;                      
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContratoTipo", mappedBy="contratoClaseRel")
     */
    protected $contratosTiposContratoClaseRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="contratoClaseRel")
     */
    protected $contratosContratoClaseRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosTiposContratoClaseRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoContratoClasePk
     *
     * @return integer
     */
    public function getCodigoContratoClasePk()
    {
        return $this->codigoContratoClasePk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuContratoClase
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
     * Add contratosTiposContratoClaseRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratosTiposContratoClaseRel
     *
     * @return RhuContratoClase
     */
    public function addContratosTiposContratoClaseRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratosTiposContratoClaseRel)
    {
        $this->contratosTiposContratoClaseRel[] = $contratosTiposContratoClaseRel;

        return $this;
    }

    /**
     * Remove contratosTiposContratoClaseRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratosTiposContratoClaseRel
     */
    public function removeContratosTiposContratoClaseRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratosTiposContratoClaseRel)
    {
        $this->contratosTiposContratoClaseRel->removeElement($contratosTiposContratoClaseRel);
    }

    /**
     * Get contratosTiposContratoClaseRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosTiposContratoClaseRel()
    {
        return $this->contratosTiposContratoClaseRel;
    }

    /**
     * Add contratosContratoClaseRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoClaseRel
     *
     * @return RhuContratoClase
     */
    public function addContratosContratoClaseRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoClaseRel)
    {
        $this->contratosContratoClaseRel[] = $contratosContratoClaseRel;

        return $this;
    }

    /**
     * Remove contratosContratoClaseRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoClaseRel
     */
    public function removeContratosContratoClaseRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoClaseRel)
    {
        $this->contratosContratoClaseRel->removeElement($contratosContratoClaseRel);
    }

    /**
     * Get contratosContratoClaseRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosContratoClaseRel()
    {
        return $this->contratosContratoClaseRel;
    }
}
