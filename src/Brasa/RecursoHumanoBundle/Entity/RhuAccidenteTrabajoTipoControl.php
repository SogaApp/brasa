<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_accidente_trabajo_tipo_control")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuAccidenteTrabajoTipoControlRepository")
 */
class RhuAccidenteTrabajoTipoControl
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_accidente_trabajo_tipo_control_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAccidenteTrabajoTipoControlPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuAccidenteTrabajo", mappedBy="tipoControlUnoRel")
     */
    protected $accidentesTrabajoAccidenteTrabajoTipoControlUnoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuAccidenteTrabajo", mappedBy="tipoControlDosRel")
     */
    protected $accidentesTrabajoAccidenteTrabajoTipoControlDosRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuAccidenteTrabajo", mappedBy="tipoControlTresRel")
     */
    protected $accidentesTrabajoAccidenteTrabajoTipoControlTresRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accidentesTrabajoAccidenteTrabajoTipoControlUnoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accidentesTrabajoAccidenteTrabajoTipoControlDosRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accidentesTrabajoAccidenteTrabajoTipoControlTresRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoAccidenteTrabajoTipoControlPk
     *
     * @return integer
     */
    public function getCodigoAccidenteTrabajoTipoControlPk()
    {
        return $this->codigoAccidenteTrabajoTipoControlPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuAccidenteTrabajoTipoControl
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
     * Add accidentesTrabajoAccidenteTrabajoTipoControlUnoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoControlUnoRel
     *
     * @return RhuAccidenteTrabajoTipoControl
     */
    public function addAccidentesTrabajoAccidenteTrabajoTipoControlUnoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoControlUnoRel)
    {
        $this->accidentesTrabajoAccidenteTrabajoTipoControlUnoRel[] = $accidentesTrabajoAccidenteTrabajoTipoControlUnoRel;

        return $this;
    }

    /**
     * Remove accidentesTrabajoAccidenteTrabajoTipoControlUnoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoControlUnoRel
     */
    public function removeAccidentesTrabajoAccidenteTrabajoTipoControlUnoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoControlUnoRel)
    {
        $this->accidentesTrabajoAccidenteTrabajoTipoControlUnoRel->removeElement($accidentesTrabajoAccidenteTrabajoTipoControlUnoRel);
    }

    /**
     * Get accidentesTrabajoAccidenteTrabajoTipoControlUnoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccidentesTrabajoAccidenteTrabajoTipoControlUnoRel()
    {
        return $this->accidentesTrabajoAccidenteTrabajoTipoControlUnoRel;
    }

    /**
     * Add accidentesTrabajoAccidenteTrabajoTipoControlDosRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoControlDosRel
     *
     * @return RhuAccidenteTrabajoTipoControl
     */
    public function addAccidentesTrabajoAccidenteTrabajoTipoControlDosRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoControlDosRel)
    {
        $this->accidentesTrabajoAccidenteTrabajoTipoControlDosRel[] = $accidentesTrabajoAccidenteTrabajoTipoControlDosRel;

        return $this;
    }

    /**
     * Remove accidentesTrabajoAccidenteTrabajoTipoControlDosRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoControlDosRel
     */
    public function removeAccidentesTrabajoAccidenteTrabajoTipoControlDosRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoControlDosRel)
    {
        $this->accidentesTrabajoAccidenteTrabajoTipoControlDosRel->removeElement($accidentesTrabajoAccidenteTrabajoTipoControlDosRel);
    }

    /**
     * Get accidentesTrabajoAccidenteTrabajoTipoControlDosRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccidentesTrabajoAccidenteTrabajoTipoControlDosRel()
    {
        return $this->accidentesTrabajoAccidenteTrabajoTipoControlDosRel;
    }

    /**
     * Add accidentesTrabajoAccidenteTrabajoTipoControlTresRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoControlTresRel
     *
     * @return RhuAccidenteTrabajoTipoControl
     */
    public function addAccidentesTrabajoAccidenteTrabajoTipoControlTresRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoControlTresRel)
    {
        $this->accidentesTrabajoAccidenteTrabajoTipoControlTresRel[] = $accidentesTrabajoAccidenteTrabajoTipoControlTresRel;

        return $this;
    }

    /**
     * Remove accidentesTrabajoAccidenteTrabajoTipoControlTresRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoControlTresRel
     */
    public function removeAccidentesTrabajoAccidenteTrabajoTipoControlTresRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoAccidenteTrabajoTipoControlTresRel)
    {
        $this->accidentesTrabajoAccidenteTrabajoTipoControlTresRel->removeElement($accidentesTrabajoAccidenteTrabajoTipoControlTresRel);
    }

    /**
     * Get accidentesTrabajoAccidenteTrabajoTipoControlTresRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccidentesTrabajoAccidenteTrabajoTipoControlTresRel()
    {
        return $this->accidentesTrabajoAccidenteTrabajoTipoControlTresRel;
    }
}
