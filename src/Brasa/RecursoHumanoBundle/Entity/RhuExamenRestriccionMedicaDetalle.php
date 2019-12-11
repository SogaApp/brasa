<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen_restriccion_medica_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenRestriccionMedicaDetalleRepository")
 */
class RhuExamenRestriccionMedicaDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_restriccion_medica_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenRestriccionMedicaDetallePk;
    
    /**
     * @ORM\Column(name="codigo_examen_restriccion_medica_fk", type="integer", nullable=true)
     */    
    private $codigoExamenRestriccionMedicaFk;
    
    /**
     * @ORM\Column(name="codigo_examen_restriccion_medica_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoExamenRestriccionMedicaTipoFk;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenRestriccionMedicaTipo", inversedBy="examenesRestriccionesMedicasDetallesExamenRestriccionMedicaTipoRel")
     * @ORM\JoinColumn(name="codigo_examen_restriccion_medica_tipo_fk", referencedColumnName="codigo_examen_restriccion_medica_tipo_pk")
     */
    protected $examenRestriccionMedicaTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenRestriccionMedica", inversedBy="examenesRestriccionesMedicasDetallesExamenRestriccionMedicaRel")
     * @ORM\JoinColumn(name="codigo_examen_restriccion_medica_fk", referencedColumnName="codigo_examen_restriccion_medica_pk")
     */
    protected $examenRestriccionMedicaDetalleRel;

    


    /**
     * Get codigoExamenRestriccionMedicaDetallePk
     *
     * @return integer
     */
    public function getCodigoExamenRestriccionMedicaDetallePk()
    {
        return $this->codigoExamenRestriccionMedicaDetallePk;
    }

    /**
     * Set codigoExamenRestriccionMedicaFk
     *
     * @param integer $codigoExamenRestriccionMedicaFk
     *
     * @return RhuExamenRestriccionMedicaDetalle
     */
    public function setCodigoExamenRestriccionMedicaFk($codigoExamenRestriccionMedicaFk)
    {
        $this->codigoExamenRestriccionMedicaFk = $codigoExamenRestriccionMedicaFk;

        return $this;
    }

    /**
     * Get codigoExamenRestriccionMedicaFk
     *
     * @return integer
     */
    public function getCodigoExamenRestriccionMedicaFk()
    {
        return $this->codigoExamenRestriccionMedicaFk;
    }

    /**
     * Set codigoExamenRestriccionMedicaTipoFk
     *
     * @param integer $codigoExamenRestriccionMedicaTipoFk
     *
     * @return RhuExamenRestriccionMedicaDetalle
     */
    public function setCodigoExamenRestriccionMedicaTipoFk($codigoExamenRestriccionMedicaTipoFk)
    {
        $this->codigoExamenRestriccionMedicaTipoFk = $codigoExamenRestriccionMedicaTipoFk;

        return $this;
    }

    /**
     * Get codigoExamenRestriccionMedicaTipoFk
     *
     * @return integer
     */
    public function getCodigoExamenRestriccionMedicaTipoFk()
    {
        return $this->codigoExamenRestriccionMedicaTipoFk;
    }

    /**
     * Set examenRestriccionMedicaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaTipo $examenRestriccionMedicaTipoRel
     *
     * @return RhuExamenRestriccionMedicaDetalle
     */
    public function setExamenRestriccionMedicaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaTipo $examenRestriccionMedicaTipoRel = null)
    {
        $this->examenRestriccionMedicaTipoRel = $examenRestriccionMedicaTipoRel;

        return $this;
    }

    /**
     * Get examenRestriccionMedicaTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaTipo
     */
    public function getExamenRestriccionMedicaTipoRel()
    {
        return $this->examenRestriccionMedicaTipoRel;
    }

    /**
     * Set examenRestriccionMedicaDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica $examenRestriccionMedicaDetalleRel
     *
     * @return RhuExamenRestriccionMedicaDetalle
     */
    public function setExamenRestriccionMedicaDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica $examenRestriccionMedicaDetalleRel = null)
    {
        $this->examenRestriccionMedicaDetalleRel = $examenRestriccionMedicaDetalleRel;

        return $this;
    }

    /**
     * Get examenRestriccionMedicaDetalleRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica
     */
    public function getExamenRestriccionMedicaDetalleRel()
    {
        return $this->examenRestriccionMedicaDetalleRel;
    }
}
