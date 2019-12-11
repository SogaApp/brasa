<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen_restriccion_medica")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenRestriccionMedicaRepository")
 */
class RhuExamenRestriccionMedica
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_restriccion_medica_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenRestriccionMedicaPk;
    
    /**
     * @ORM\Column(name="codigo_examen_fk", type="integer", nullable=true)
     */    
    private $codigoExamenFk;
    
    /**
     * @ORM\Column(name="codigo_examen_revision_medica_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoExamenRevisionMedicaTipoFk;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */ 
    
    private $fecha; 
    
    /**
     * @ORM\Column(name="dias", type="string", length=3, nullable=true)
     */    
    private $dias;
    
    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */ 
    
    private $fechaVence;
    
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;

    /**
     * @ORM\ManyToOne(targetEntity="RhuExamen", inversedBy="examenesExamenRestriccionMedicaRel")
     * @ORM\JoinColumn(name="codigo_examen_fk", referencedColumnName="codigo_examen_pk")
     */
    protected $examenRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenRevisionMedicaTipo", inversedBy="examenesRestriccionesMedicasExamenRevisionMedicaTipoRel")
     * @ORM\JoinColumn(name="codigo_examen_revision_medica_tipo_fk", referencedColumnName="codigo_examen_revision_medica_tipo_pk")
     */
    protected $examenRevisionMedicaTipoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenRestriccionMedicaDetalle", mappedBy="examenRestriccionMedicaDetalleRel")
     */
    protected $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoExamenRestriccionMedicaPk
     *
     * @return integer
     */
    public function getCodigoExamenRestriccionMedicaPk()
    {
        return $this->codigoExamenRestriccionMedicaPk;
    }

    /**
     * Set codigoExamenFk
     *
     * @param integer $codigoExamenFk
     *
     * @return RhuExamenRestriccionMedica
     */
    public function setCodigoExamenFk($codigoExamenFk)
    {
        $this->codigoExamenFk = $codigoExamenFk;

        return $this;
    }

    /**
     * Get codigoExamenFk
     *
     * @return integer
     */
    public function getCodigoExamenFk()
    {
        return $this->codigoExamenFk;
    }

    /**
     * Set codigoExamenRevisionMedicaTipoFk
     *
     * @param integer $codigoExamenRevisionMedicaTipoFk
     *
     * @return RhuExamenRestriccionMedica
     */
    public function setCodigoExamenRevisionMedicaTipoFk($codigoExamenRevisionMedicaTipoFk)
    {
        $this->codigoExamenRevisionMedicaTipoFk = $codigoExamenRevisionMedicaTipoFk;

        return $this;
    }

    /**
     * Get codigoExamenRevisionMedicaTipoFk
     *
     * @return integer
     */
    public function getCodigoExamenRevisionMedicaTipoFk()
    {
        return $this->codigoExamenRevisionMedicaTipoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuExamenRestriccionMedica
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set dias
     *
     * @param string $dias
     *
     * @return RhuExamenRestriccionMedica
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return string
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuExamenRestriccionMedica
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * Set examenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenRel
     *
     * @return RhuExamenRestriccionMedica
     */
    public function setExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenRel = null)
    {
        $this->examenRel = $examenRel;

        return $this;
    }

    /**
     * Get examenRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuExamen
     */
    public function getExamenRel()
    {
        return $this->examenRel;
    }

    /**
     * Set examenRevisionMedicaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenRevisionMedicaTipo $examenRevisionMedicaTipoRel
     *
     * @return RhuExamenRestriccionMedica
     */
    public function setExamenRevisionMedicaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenRevisionMedicaTipo $examenRevisionMedicaTipoRel = null)
    {
        $this->examenRevisionMedicaTipoRel = $examenRevisionMedicaTipoRel;

        return $this;
    }

    /**
     * Get examenRevisionMedicaTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuExamenRevisionMedicaTipo
     */
    public function getExamenRevisionMedicaTipoRel()
    {
        return $this->examenRevisionMedicaTipoRel;
    }

    /**
     * Add examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel
     *
     * @return RhuExamenRestriccionMedica
     */
    public function addExamenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel)
    {
        $this->examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel[] = $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel;

        return $this;
    }

    /**
     * Remove examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel
     */
    public function removeExamenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle $examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel)
    {
        $this->examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel->removeElement($examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel);
    }

    /**
     * Get examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel()
    {
        return $this->examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel;
    }

    /**
     * Set fechaVence
     *
     * @param \DateTime $fechaVence
     *
     * @return RhuExamenRestriccionMedica
     */
    public function setFechaVence($fechaVence)
    {
        $this->fechaVence = $fechaVence;

        return $this;
    }

    /**
     * Get fechaVence
     *
     * @return \DateTime
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }
}
