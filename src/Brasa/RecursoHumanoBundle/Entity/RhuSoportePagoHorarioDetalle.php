<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_soporte_pago_horario_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSoportePagoHorarioDetalleRepository")
 */
class RhuSoportePagoHorarioDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_soporte_pago_horario_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSoportePagoHorarioDetallePk;         

    /**
     * @ORM\Column(name="codigo_soporte_pago_horario_fk", type="integer")
     */    
    private $codigoSoportePagoHorarioFk;    
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;    
    
    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContratoFk;    
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;        

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;            
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    

    /**
     * @ORM\Column(name="descanso", type="integer")
     */    
    private $descanso = 0;    

    /**
     * @ORM\Column(name="incapacidad", type="integer")
     */    
    private $incapacidad = 0; 
    
    /**
     * @ORM\Column(name="licencia", type="integer")
     */    
    private $licencia = 0;     
    
    /**
     * @ORM\Column(name="vacacion", type="integer")
     */    
    private $vacacion = 0;     
    
    /**
     * @ORM\Column(name="dias", type="integer")
     */    
    private $dias = 0;    
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    

    /**
     * @ORM\Column(name="horas_descanso", type="integer")
     */    
    private $horasDescanso = 0;

    /**
     * @ORM\Column(name="horas_permiso", type="integer")
     */    
    private $horasPermiso = 0;

    /**
     * @ORM\Column(name="horas_novedad", type="integer")
     */    
    private $horasNovedad = 0;
    
    /**
     * @ORM\Column(name="horas_diurnas", type="integer")
     */    
    private $horasDiurnas = 0;     

    /**
     * @ORM\Column(name="horas_nocturnas", type="integer")
     */    
    private $horasNocturnas = 0;    
    
    /**
     * @ORM\Column(name="horas_festivas_diurnas", type="integer")
     */    
    private $horasFestivasDiurnas = 0;     

    /**
     * @ORM\Column(name="horas_festivas_nocturnas", type="integer")
     */    
    private $horasFestivasNocturnas = 0;     
    
    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas", type="integer")
     */    
    private $horasExtrasOrdinariasDiurnas = 0;    

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas", type="integer")
     */    
    private $horasExtrasOrdinariasNocturnas = 0;        

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas", type="integer")
     */    
    private $horasExtrasFestivasDiurnas = 0;    

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas", type="integer")
     */    
    private $horasExtrasFestivasNocturnas = 0;       

    /**
     * @ORM\ManyToOne(targetEntity="RhuSoportePagoHorario", inversedBy="soportesPagosHorariosDetallesSoportePagoHorarioRel")
     * @ORM\JoinColumn(name="codigo_soporte_pago_horario_fk", referencedColumnName="codigo_soporte_pago_horario_pk")
     */
    protected $soportePagoHorarioRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="soportesPagosHorariosDetallesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="soportesPagosHorariosDetallesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;    



    /**
     * Get codigoSoportePagoHorarioDetallePk
     *
     * @return integer
     */
    public function getCodigoSoportePagoHorarioDetallePk()
    {
        return $this->codigoSoportePagoHorarioDetallePk;
    }

    /**
     * Set codigoSoportePagoHorarioFk
     *
     * @param integer $codigoSoportePagoHorarioFk
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setCodigoSoportePagoHorarioFk($codigoSoportePagoHorarioFk)
    {
        $this->codigoSoportePagoHorarioFk = $codigoSoportePagoHorarioFk;

        return $this;
    }

    /**
     * Get codigoSoportePagoHorarioFk
     *
     * @return integer
     */
    public function getCodigoSoportePagoHorarioFk()
    {
        return $this->codigoSoportePagoHorarioFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuSoportePagoHorarioDetalle
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
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Set descanso
     *
     * @param integer $descanso
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setDescanso($descanso)
    {
        $this->descanso = $descanso;

        return $this;
    }

    /**
     * Get descanso
     *
     * @return integer
     */
    public function getDescanso()
    {
        return $this->descanso;
    }

    /**
     * Set incapacidad
     *
     * @param integer $incapacidad
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setIncapacidad($incapacidad)
    {
        $this->incapacidad = $incapacidad;

        return $this;
    }

    /**
     * Get incapacidad
     *
     * @return integer
     */
    public function getIncapacidad()
    {
        return $this->incapacidad;
    }

    /**
     * Set licencia
     *
     * @param integer $licencia
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setLicencia($licencia)
    {
        $this->licencia = $licencia;

        return $this;
    }

    /**
     * Get licencia
     *
     * @return integer
     */
    public function getLicencia()
    {
        return $this->licencia;
    }

    /**
     * Set vacacion
     *
     * @param integer $vacacion
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setVacacion($vacacion)
    {
        $this->vacacion = $vacacion;

        return $this;
    }

    /**
     * Get vacacion
     *
     * @return integer
     */
    public function getVacacion()
    {
        return $this->vacacion;
    }

    /**
     * Set dias
     *
     * @param integer $dias
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return integer
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return integer
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set horasDescanso
     *
     * @param integer $horasDescanso
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setHorasDescanso($horasDescanso)
    {
        $this->horasDescanso = $horasDescanso;

        return $this;
    }

    /**
     * Get horasDescanso
     *
     * @return integer
     */
    public function getHorasDescanso()
    {
        return $this->horasDescanso;
    }

    /**
     * Set horasDiurnas
     *
     * @param integer $horasDiurnas
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setHorasDiurnas($horasDiurnas)
    {
        $this->horasDiurnas = $horasDiurnas;

        return $this;
    }

    /**
     * Get horasDiurnas
     *
     * @return integer
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * Set horasNocturnas
     *
     * @param integer $horasNocturnas
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setHorasNocturnas($horasNocturnas)
    {
        $this->horasNocturnas = $horasNocturnas;

        return $this;
    }

    /**
     * Get horasNocturnas
     *
     * @return integer
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * Set horasFestivasDiurnas
     *
     * @param integer $horasFestivasDiurnas
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setHorasFestivasDiurnas($horasFestivasDiurnas)
    {
        $this->horasFestivasDiurnas = $horasFestivasDiurnas;

        return $this;
    }

    /**
     * Get horasFestivasDiurnas
     *
     * @return integer
     */
    public function getHorasFestivasDiurnas()
    {
        return $this->horasFestivasDiurnas;
    }

    /**
     * Set horasFestivasNocturnas
     *
     * @param integer $horasFestivasNocturnas
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setHorasFestivasNocturnas($horasFestivasNocturnas)
    {
        $this->horasFestivasNocturnas = $horasFestivasNocturnas;

        return $this;
    }

    /**
     * Get horasFestivasNocturnas
     *
     * @return integer
     */
    public function getHorasFestivasNocturnas()
    {
        return $this->horasFestivasNocturnas;
    }

    /**
     * Set horasExtrasOrdinariasDiurnas
     *
     * @param integer $horasExtrasOrdinariasDiurnas
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setHorasExtrasOrdinariasDiurnas($horasExtrasOrdinariasDiurnas)
    {
        $this->horasExtrasOrdinariasDiurnas = $horasExtrasOrdinariasDiurnas;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasDiurnas
     *
     * @return integer
     */
    public function getHorasExtrasOrdinariasDiurnas()
    {
        return $this->horasExtrasOrdinariasDiurnas;
    }

    /**
     * Set horasExtrasOrdinariasNocturnas
     *
     * @param integer $horasExtrasOrdinariasNocturnas
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setHorasExtrasOrdinariasNocturnas($horasExtrasOrdinariasNocturnas)
    {
        $this->horasExtrasOrdinariasNocturnas = $horasExtrasOrdinariasNocturnas;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasNocturnas
     *
     * @return integer
     */
    public function getHorasExtrasOrdinariasNocturnas()
    {
        return $this->horasExtrasOrdinariasNocturnas;
    }

    /**
     * Set horasExtrasFestivasDiurnas
     *
     * @param integer $horasExtrasFestivasDiurnas
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setHorasExtrasFestivasDiurnas($horasExtrasFestivasDiurnas)
    {
        $this->horasExtrasFestivasDiurnas = $horasExtrasFestivasDiurnas;

        return $this;
    }

    /**
     * Get horasExtrasFestivasDiurnas
     *
     * @return integer
     */
    public function getHorasExtrasFestivasDiurnas()
    {
        return $this->horasExtrasFestivasDiurnas;
    }

    /**
     * Set horasExtrasFestivasNocturnas
     *
     * @param integer $horasExtrasFestivasNocturnas
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setHorasExtrasFestivasNocturnas($horasExtrasFestivasNocturnas)
    {
        $this->horasExtrasFestivasNocturnas = $horasExtrasFestivasNocturnas;

        return $this;
    }

    /**
     * Get horasExtrasFestivasNocturnas
     *
     * @return integer
     */
    public function getHorasExtrasFestivasNocturnas()
    {
        return $this->horasExtrasFestivasNocturnas;
    }

    /**
     * Set soportePagoHorarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorario $soportePagoHorarioRel
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setSoportePagoHorarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorario $soportePagoHorarioRel = null)
    {
        $this->soportePagoHorarioRel = $soportePagoHorarioRel;

        return $this;
    }

    /**
     * Get soportePagoHorarioRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorario
     */
    public function getSoportePagoHorarioRel()
    {
        return $this->soportePagoHorarioRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuSoportePagoHorarioDetalle
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
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * Set horasPermiso
     *
     * @param integer $horasPermiso
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setHorasPermiso($horasPermiso)
    {
        $this->horasPermiso = $horasPermiso;

        return $this;
    }

    /**
     * Get horasPermiso
     *
     * @return integer
     */
    public function getHorasPermiso()
    {
        return $this->horasPermiso;
    }

    /**
     * Set horasNovedad
     *
     * @param integer $horasNovedad
     *
     * @return RhuSoportePagoHorarioDetalle
     */
    public function setHorasNovedad($horasNovedad)
    {
        $this->horasNovedad = $horasNovedad;

        return $this;
    }

    /**
     * Get horasNovedad
     *
     * @return integer
     */
    public function getHorasNovedad()
    {
        return $this->horasNovedad;
    }
}
