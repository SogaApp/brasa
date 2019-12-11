<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen_revision_medica_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenRevisionMedicaTipoRepository")
 */
class RhuExamenRevisionMedicaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_revision_medica_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenRevisionMedicaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;            
       
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenRestriccionMedica", mappedBy="examenRevisionMedicaTipoRel")
     */
    protected $examenesRestriccionesMedicasExamenRevisionMedicaTipoRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->examenesRestriccionesMedicasExamenRevisionMedicaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoExamenRevisionMedicaTipoPk
     *
     * @return integer
     */
    public function getCodigoExamenRevisionMedicaTipoPk()
    {
        return $this->codigoExamenRevisionMedicaTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuExamenRevisionMedicaTipo
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
     * Add examenesRestriccionesMedicasExamenRevisionMedicaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica $examenesRestriccionesMedicasExamenRevisionMedicaTipoRel
     *
     * @return RhuExamenRevisionMedicaTipo
     */
    public function addExamenesRestriccionesMedicasExamenRevisionMedicaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica $examenesRestriccionesMedicasExamenRevisionMedicaTipoRel)
    {
        $this->examenesRestriccionesMedicasExamenRevisionMedicaTipoRel[] = $examenesRestriccionesMedicasExamenRevisionMedicaTipoRel;

        return $this;
    }

    /**
     * Remove examenesRestriccionesMedicasExamenRevisionMedicaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica $examenesRestriccionesMedicasExamenRevisionMedicaTipoRel
     */
    public function removeExamenesRestriccionesMedicasExamenRevisionMedicaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica $examenesRestriccionesMedicasExamenRevisionMedicaTipoRel)
    {
        $this->examenesRestriccionesMedicasExamenRevisionMedicaTipoRel->removeElement($examenesRestriccionesMedicasExamenRevisionMedicaTipoRel);
    }

    /**
     * Get examenesRestriccionesMedicasExamenRevisionMedicaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesRestriccionesMedicasExamenRevisionMedicaTipoRel()
    {
        return $this->examenesRestriccionesMedicasExamenRevisionMedicaTipoRel;
    }
}
