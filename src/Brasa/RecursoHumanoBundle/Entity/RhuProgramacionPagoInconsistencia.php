<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_programacion_pago_inconsistencia")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuProgramacionPagoInconsistenciaRepository")
 */
class RhuProgramacionPagoInconsistencia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_pago_inconsistencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionPagoInconsistenciaPk;   
    
    /**
     * @ORM\Column(name="codigo_programacion_pago_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionPagoFk;     
    
    /**
     * @ORM\Column(name="inconsistencia", type="string", length=250, nullable=true)
     */    
    private $inconsistencia;        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionPago", inversedBy="programacionesPagosInconsistenciasProgramacionPagoRel")
     * @ORM\JoinColumn(name="codigo_programacion_pago_fk", referencedColumnName="codigo_programacion_pago_pk")
     */
    protected $programacionPagoRel;    
    


    /**
     * Get codigoProgramacionPagoInconsistenciaPk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoInconsistenciaPk()
    {
        return $this->codigoProgramacionPagoInconsistenciaPk;
    }

    /**
     * Set codigoProgramacionPagoFk
     *
     * @param integer $codigoProgramacionPagoFk
     *
     * @return RhuProgramacionPagoInconsistencia
     */
    public function setCodigoProgramacionPagoFk($codigoProgramacionPagoFk)
    {
        $this->codigoProgramacionPagoFk = $codigoProgramacionPagoFk;

        return $this;
    }

    /**
     * Get codigoProgramacionPagoFk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoFk()
    {
        return $this->codigoProgramacionPagoFk;
    }

    /**
     * Set inconsistencia
     *
     * @param string $inconsistencia
     *
     * @return RhuProgramacionPagoInconsistencia
     */
    public function setInconsistencia($inconsistencia)
    {
        $this->inconsistencia = $inconsistencia;

        return $this;
    }

    /**
     * Get inconsistencia
     *
     * @return string
     */
    public function getInconsistencia()
    {
        return $this->inconsistencia;
    }

    /**
     * Set programacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel
     *
     * @return RhuProgramacionPagoInconsistencia
     */
    public function setProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel = null)
    {
        $this->programacionPagoRel = $programacionPagoRel;

        return $this;
    }

    /**
     * Get programacionPagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago
     */
    public function getProgramacionPagoRel()
    {
        return $this->programacionPagoRel;
    }
}
