<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_accidente_trabajo_tipo_accidente")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuAccidenteTrabajoTipoAccidenteRepository")
 */
class RhuAccidenteTrabajoTipoAccidente
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_accidente_trabajo_tipo_accidente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAccidenteTrabajoTipoAccidentePk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuAccidenteTrabajo", mappedBy="tipoAccidenteRel")
     */
    protected $accidentesTrabajoAccidenteTrabajoTipoAccidenteRel;
    
    
  
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accidentesTrabajoAccidenteTrabajoTipoAccidenteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoAccidenteTrabajoTipoAccidentePk
     *
     * @return integer
     */
    public function getCodigoAccidenteTrabajoTipoAccidentePk()
    {
        return $this->codigoAccidenteTrabajoTipoAccidentePk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuAccidenteTrabajoTipoAccidente
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
     * Add accidentesTrabajoAccidenteTrabajoTipoAccidenteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoAccidenteRel
     *
     * @return RhuAccidenteTrabajoTipoAccidente
     */
    public function addAccidentesTrabajoAccidenteTrabajoTipoAccidenteRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoAccidenteRel)
    {
        $this->accidentesTrabajoAccidenteTrabajoTipoAccidenteRel[] = $accidentesTrabajoAccidenteTrabajoTipoAccidenteRel;

        return $this;
    }

    /**
     * Remove accidentesTrabajoAccidenteTrabajoTipoAccidenteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoAccidenteRel
     */
    public function removeAccidentesTrabajoAccidenteTrabajoTipoAccidenteRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoAccidenteRel)
    {
        $this->accidentesTrabajoAccidenteTrabajoTipoAccidenteRel->removeElement($accidentesTrabajoAccidenteTrabajoTipoAccidenteRel);
    }

    /**
     * Get accidentesTrabajoAccidenteTrabajoTipoAccidenteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccidentesTrabajoAccidenteTrabajoTipoAccidenteRel()
    {
        return $this->accidentesTrabajoAccidenteTrabajoTipoAccidenteRel;
    }
}
