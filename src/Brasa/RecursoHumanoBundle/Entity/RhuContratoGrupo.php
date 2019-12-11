<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_contrato_grupo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuContratoGrupoRepository")
 */
class RhuContratoGrupo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_grupo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoGrupoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=200, nullable=true)
     */    
    private $nombre;         
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="contratoGrupoRel")
     */
    protected $contratosContratoGrupoRel;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosContratoGrupoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoContratoGrupoPk
     *
     * @return integer
     */
    public function getCodigoContratoGrupoPk()
    {
        return $this->codigoContratoGrupoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuContratoGrupo
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
     * Add contratosContratoGrupoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoGrupoRel
     *
     * @return RhuContratoGrupo
     */
    public function addContratosContratoGrupoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoGrupoRel)
    {
        $this->contratosContratoGrupoRel[] = $contratosContratoGrupoRel;

        return $this;
    }

    /**
     * Remove contratosContratoGrupoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoGrupoRel
     */
    public function removeContratosContratoGrupoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoGrupoRel)
    {
        $this->contratosContratoGrupoRel->removeElement($contratosContratoGrupoRel);
    }

    /**
     * Get contratosContratoGrupoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosContratoGrupoRel()
    {
        return $this->contratosContratoGrupoRel;
    }
}
