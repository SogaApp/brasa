<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen_restriccion_medica_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenRestriccionMedicaTipoRepository")
 */
class RhuExamenRestriccionMedicaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_restriccion_medica_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenRestriccionMedicaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;            
       
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenRestriccionMedicaDetalle", mappedBy="examenRestriccionMedicaTipoRel")
     */
    protected $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoExamenRestriccionMedicaTipoPk
     *
     * @return integer
     */
    public function getCodigoExamenRestriccionMedicaTipoPk()
    {
        return $this->codigoExamenRestriccionMedicaTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuExamenRestriccionMedicaTipo
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
     * Add examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel
     *
     * @return RhuExamenRestriccionMedicaTipo
     */
    public function addExamenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel)
    {
        $this->examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel[] = $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel;

        return $this;
    }

    /**
     * Remove examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel
     */
    public function removeExamenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel)
    {
        $this->examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel->removeElement($examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel);
    }

    /**
     * Get examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel()
    {
        return $this->examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel;
    }
}
