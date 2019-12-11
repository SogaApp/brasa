<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_programacion_pago_detalle_sede")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuProgramacionPagoDetalleSedeRepository")
 */
class RhuProgramacionPagoDetalleSede
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_pago_detalle_sede_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionPagoDetalleSedePk;
    
    /**
     * @ORM\Column(name="codigo_programacion_pago_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionPagoDetalleFk;   
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;           

    /**
     * @ORM\Column(name="codigo_sede_fk", type="integer", nullable=true)
     */    
    private $codigoSedeFk;    
    
    /**
     * @ORM\Column(name="horas_periodo", type="float")
     */
    private $horasPeriodo = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_participacion", type="float")
     */
    private $porcentajeParticipacion = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionPagoDetalle", inversedBy="programacionesPagosDetallesSedesProgramacionPagoDetalleRel")
     * @ORM\JoinColumn(name="codigo_programacion_pago_detalle_fk", referencedColumnName="codigo_programacion_pago_detalle_pk")
     */
    protected $programacionPagoDetalleRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="programacionesPagosDetallesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSede", inversedBy="programacionesPagosDetallesSedeRel")
     * @ORM\JoinColumn(name="codigo_sede_fk", referencedColumnName="codigo_sede_pk")
     */
    protected $sedeRel;     
      


    /**
     * Get codigoProgramacionPagoDetalleSedePk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoDetalleSedePk()
    {
        return $this->codigoProgramacionPagoDetalleSedePk;
    }

    /**
     * Set codigoProgramacionPagoDetalleFk
     *
     * @param integer $codigoProgramacionPagoDetalleFk
     *
     * @return RhuProgramacionPagoDetalleSede
     */
    public function setCodigoProgramacionPagoDetalleFk($codigoProgramacionPagoDetalleFk)
    {
        $this->codigoProgramacionPagoDetalleFk = $codigoProgramacionPagoDetalleFk;

        return $this;
    }

    /**
     * Get codigoProgramacionPagoDetalleFk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoDetalleFk()
    {
        return $this->codigoProgramacionPagoDetalleFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuProgramacionPagoDetalleSede
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk)
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * Set codigoSedeFk
     *
     * @param integer $codigoSedeFk
     *
     * @return RhuProgramacionPagoDetalleSede
     */
    public function setCodigoSedeFk($codigoSedeFk)
    {
        $this->codigoSedeFk = $codigoSedeFk;

        return $this;
    }

    /**
     * Get codigoSedeFk
     *
     * @return integer
     */
    public function getCodigoSedeFk()
    {
        return $this->codigoSedeFk;
    }

    /**
     * Set horasPeriodo
     *
     * @param float $horasPeriodo
     *
     * @return RhuProgramacionPagoDetalleSede
     */
    public function setHorasPeriodo($horasPeriodo)
    {
        $this->horasPeriodo = $horasPeriodo;

        return $this;
    }

    /**
     * Get horasPeriodo
     *
     * @return float
     */
    public function getHorasPeriodo()
    {
        return $this->horasPeriodo;
    }

    /**
     * Set programacionPagoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionPagoDetalleRel
     *
     * @return RhuProgramacionPagoDetalleSede
     */
    public function setProgramacionPagoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionPagoDetalleRel = null)
    {
        $this->programacionPagoDetalleRel = $programacionPagoDetalleRel;

        return $this;
    }

    /**
     * Get programacionPagoDetalleRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle
     */
    public function getProgramacionPagoDetalleRel()
    {
        return $this->programacionPagoDetalleRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuProgramacionPagoDetalleSede
     */
    public function setEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set sedeRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSede $sedeRel
     *
     * @return RhuProgramacionPagoDetalleSede
     */
    public function setSedeRel(\Brasa\RecursoHumanoBundle\Entity\RhuSede $sedeRel = null)
    {
        $this->sedeRel = $sedeRel;

        return $this;
    }

    /**
     * Get sedeRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSede
     */
    public function getSedeRel()
    {
        return $this->sedeRel;
    }

    /**
     * Set porcentajeParticipacion
     *
     * @param float $porcentajeParticipacion
     *
     * @return RhuProgramacionPagoDetalleSede
     */
    public function setPorcentajeParticipacion($porcentajeParticipacion)
    {
        $this->porcentajeParticipacion = $porcentajeParticipacion;

        return $this;
    }

    /**
     * Get porcentajeParticipacion
     *
     * @return float
     */
    public function getPorcentajeParticipacion()
    {
        return $this->porcentajeParticipacion;
    }
}
