<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_horario_acceso")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuHorarioAccesoRepository")
 */
class RhuHorarioAcceso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_horario_acceso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoHorarioAccesoPk;                         

    /**
     * @ORM\Column(name="codigo_horario_periodo_fk", type="integer")
     */    
    private $codigoHorarioPeriodoFk;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="codigo_turno_fk",  type="string", length=250, nullable=true)
     */    
    private $codigoTurnoFk;
    
    /**
     * @ORM\Column(name="fecha_entrada", type="datetime", nullable=true)
     */    
    private $fechaEntrada;
    
    /**
     * @ORM\Column(name="fecha_salida", type="datetime", nullable=true)
     */    
    private $fechaSalida;        

    /**
     * @ORM\Column(name="fecha_entrada_pago", type="datetime", nullable=true)
     */    
    private $fechaEntradaPago;
    
    /**
     * @ORM\Column(name="fecha_salida_pago", type="datetime", nullable=true)
     */    
    private $fechaSalidaPago;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=250, nullable=true)
     */    
    private $comentarios;
       
    /**
     * @ORM\Column(name="duracion_registro", type="integer")
     */    
    private $duracionRegistro = 0;
    
    /**
     * @ORM\Column(name="duracion_entrada_tarde", type="integer")
     */    
    private $duracionEntradaTarde = 0;
    
    /**
     * @ORM\Column(name="duracion_salida_antes", type="integer")
     */    
    private $duracionSalidaAntes = 0;
    
    /**     
     * @ORM\Column(name="estado_entrada", type="boolean")
     */    
    private $estadoEntrada = FALSE;
    
    /**     
     * @ORM\Column(name="estado_salida", type="boolean")
     */    
    private $estadoSalida = FALSE;
    
    /**     
     * @ORM\Column(name="entrada_tarde", type="boolean")
     */    
    private $entradaTarde = FALSE;
    
    /**     
     * @ORM\Column(name="salida_antes", type="boolean")
     */    
    private $salidaAntes = FALSE;
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="hora_entrada_turno", type="time", nullable=true)
     */    
    private $horaEntradaTurno;

    /**     
     * @ORM\Column(name="anulado", type="boolean")
     */    
    private $anulado = FALSE;

    /**
     * @ORM\Column(name="hora_salida_turno", type="time", nullable=true)
     */    
    private $horaSalidaTurno;

    /**
     * @ORM\Column(name="salida_dia_siguiente", type="boolean")
     */    
    private $salidaDiaSiguiente = false;    
    
    /**
     * @ORM\Column(name="genera_hora_extra", type="boolean")
     */    
    private $generaHoraExtra = false;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuHorarioPeriodo", inversedBy="horariosAccesosHorarioPeriodoRel")
     * @ORM\JoinColumn(name="codigo_horario_periodo_fk", referencedColumnName="codigo_horario_periodo_pk")
     */
    protected $horarioPeriodoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="horarioAccesoEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuTurno", inversedBy="horariosAccesosTurnoRel")
     * @ORM\JoinColumn(name="codigo_turno_fk", referencedColumnName="codigo_turno_pk")
     */
    protected $turnoRel;
        

    /**
     * Get codigoHorarioAccesoPk
     *
     * @return integer
     */
    public function getCodigoHorarioAccesoPk()
    {
        return $this->codigoHorarioAccesoPk;
    }

    /**
     * Set codigoHorarioPeriodoFk
     *
     * @param integer $codigoHorarioPeriodoFk
     *
     * @return RhuHorarioAcceso
     */
    public function setCodigoHorarioPeriodoFk($codigoHorarioPeriodoFk)
    {
        $this->codigoHorarioPeriodoFk = $codigoHorarioPeriodoFk;

        return $this;
    }

    /**
     * Get codigoHorarioPeriodoFk
     *
     * @return integer
     */
    public function getCodigoHorarioPeriodoFk()
    {
        return $this->codigoHorarioPeriodoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuHorarioAcceso
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
     * Set codigoTurnoFk
     *
     * @param string $codigoTurnoFk
     *
     * @return RhuHorarioAcceso
     */
    public function setCodigoTurnoFk($codigoTurnoFk)
    {
        $this->codigoTurnoFk = $codigoTurnoFk;

        return $this;
    }

    /**
     * Get codigoTurnoFk
     *
     * @return string
     */
    public function getCodigoTurnoFk()
    {
        return $this->codigoTurnoFk;
    }

    /**
     * Set fechaEntrada
     *
     * @param \DateTime $fechaEntrada
     *
     * @return RhuHorarioAcceso
     */
    public function setFechaEntrada($fechaEntrada)
    {
        $this->fechaEntrada = $fechaEntrada;

        return $this;
    }

    /**
     * Get fechaEntrada
     *
     * @return \DateTime
     */
    public function getFechaEntrada()
    {
        return $this->fechaEntrada;
    }

    /**
     * Set fechaSalida
     *
     * @param \DateTime $fechaSalida
     *
     * @return RhuHorarioAcceso
     */
    public function setFechaSalida($fechaSalida)
    {
        $this->fechaSalida = $fechaSalida;

        return $this;
    }

    /**
     * Get fechaSalida
     *
     * @return \DateTime
     */
    public function getFechaSalida()
    {
        return $this->fechaSalida;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuHorarioAcceso
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set duracionRegistro
     *
     * @param integer $duracionRegistro
     *
     * @return RhuHorarioAcceso
     */
    public function setDuracionRegistro($duracionRegistro)
    {
        $this->duracionRegistro = $duracionRegistro;

        return $this;
    }

    /**
     * Get duracionRegistro
     *
     * @return integer
     */
    public function getDuracionRegistro()
    {
        return $this->duracionRegistro;
    }

    /**
     * Set duracionEntradaTarde
     *
     * @param integer $duracionEntradaTarde
     *
     * @return RhuHorarioAcceso
     */
    public function setDuracionEntradaTarde($duracionEntradaTarde)
    {
        $this->duracionEntradaTarde = $duracionEntradaTarde;

        return $this;
    }

    /**
     * Get duracionEntradaTarde
     *
     * @return integer
     */
    public function getDuracionEntradaTarde()
    {
        return $this->duracionEntradaTarde;
    }

    /**
     * Set duracionSalidaAntes
     *
     * @param integer $duracionSalidaAntes
     *
     * @return RhuHorarioAcceso
     */
    public function setDuracionSalidaAntes($duracionSalidaAntes)
    {
        $this->duracionSalidaAntes = $duracionSalidaAntes;

        return $this;
    }

    /**
     * Get duracionSalidaAntes
     *
     * @return integer
     */
    public function getDuracionSalidaAntes()
    {
        return $this->duracionSalidaAntes;
    }

    /**
     * Set estadoEntrada
     *
     * @param boolean $estadoEntrada
     *
     * @return RhuHorarioAcceso
     */
    public function setEstadoEntrada($estadoEntrada)
    {
        $this->estadoEntrada = $estadoEntrada;

        return $this;
    }

    /**
     * Get estadoEntrada
     *
     * @return boolean
     */
    public function getEstadoEntrada()
    {
        return $this->estadoEntrada;
    }

    /**
     * Set estadoSalida
     *
     * @param boolean $estadoSalida
     *
     * @return RhuHorarioAcceso
     */
    public function setEstadoSalida($estadoSalida)
    {
        $this->estadoSalida = $estadoSalida;

        return $this;
    }

    /**
     * Get estadoSalida
     *
     * @return boolean
     */
    public function getEstadoSalida()
    {
        return $this->estadoSalida;
    }

    /**
     * Set entradaTarde
     *
     * @param boolean $entradaTarde
     *
     * @return RhuHorarioAcceso
     */
    public function setEntradaTarde($entradaTarde)
    {
        $this->entradaTarde = $entradaTarde;

        return $this;
    }

    /**
     * Get entradaTarde
     *
     * @return boolean
     */
    public function getEntradaTarde()
    {
        return $this->entradaTarde;
    }

    /**
     * Set salidaAntes
     *
     * @param boolean $salidaAntes
     *
     * @return RhuHorarioAcceso
     */
    public function setSalidaAntes($salidaAntes)
    {
        $this->salidaAntes = $salidaAntes;

        return $this;
    }

    /**
     * Get salidaAntes
     *
     * @return boolean
     */
    public function getSalidaAntes()
    {
        return $this->salidaAntes;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return RhuHorarioAcceso
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
     * Set horaEntradaTurno
     *
     * @param \DateTime $horaEntradaTurno
     *
     * @return RhuHorarioAcceso
     */
    public function setHoraEntradaTurno($horaEntradaTurno)
    {
        $this->horaEntradaTurno = $horaEntradaTurno;

        return $this;
    }

    /**
     * Get horaEntradaTurno
     *
     * @return \DateTime
     */
    public function getHoraEntradaTurno()
    {
        return $this->horaEntradaTurno;
    }

    /**
     * Set anulado
     *
     * @param boolean $anulado
     *
     * @return RhuHorarioAcceso
     */
    public function setAnulado($anulado)
    {
        $this->anulado = $anulado;

        return $this;
    }

    /**
     * Get anulado
     *
     * @return boolean
     */
    public function getAnulado()
    {
        return $this->anulado;
    }

    /**
     * Set horaSalidaTurno
     *
     * @param \DateTime $horaSalidaTurno
     *
     * @return RhuHorarioAcceso
     */
    public function setHoraSalidaTurno($horaSalidaTurno)
    {
        $this->horaSalidaTurno = $horaSalidaTurno;

        return $this;
    }

    /**
     * Get horaSalidaTurno
     *
     * @return \DateTime
     */
    public function getHoraSalidaTurno()
    {
        return $this->horaSalidaTurno;
    }

    /**
     * Set salidaDiaSiguiente
     *
     * @param boolean $salidaDiaSiguiente
     *
     * @return RhuHorarioAcceso
     */
    public function setSalidaDiaSiguiente($salidaDiaSiguiente)
    {
        $this->salidaDiaSiguiente = $salidaDiaSiguiente;

        return $this;
    }

    /**
     * Get salidaDiaSiguiente
     *
     * @return boolean
     */
    public function getSalidaDiaSiguiente()
    {
        return $this->salidaDiaSiguiente;
    }

    /**
     * Set horarioPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuHorarioPeriodo $horarioPeriodoRel
     *
     * @return RhuHorarioAcceso
     */
    public function setHorarioPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuHorarioPeriodo $horarioPeriodoRel = null)
    {
        $this->horarioPeriodoRel = $horarioPeriodoRel;

        return $this;
    }

    /**
     * Get horarioPeriodoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuHorarioPeriodo
     */
    public function getHorarioPeriodoRel()
    {
        return $this->horarioPeriodoRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuHorarioAcceso
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
     * Set turnoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTurno $turnoRel
     *
     * @return RhuHorarioAcceso
     */
    public function setTurnoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTurno $turnoRel = null)
    {
        $this->turnoRel = $turnoRel;

        return $this;
    }

    /**
     * Get turnoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuTurno
     */
    public function getTurnoRel()
    {
        return $this->turnoRel;
    }

    /**
     * Set fechaEntradaPago
     *
     * @param \DateTime $fechaEntradaPago
     *
     * @return RhuHorarioAcceso
     */
    public function setFechaEntradaPago($fechaEntradaPago)
    {
        $this->fechaEntradaPago = $fechaEntradaPago;

        return $this;
    }

    /**
     * Get fechaEntradaPago
     *
     * @return \DateTime
     */
    public function getFechaEntradaPago()
    {
        return $this->fechaEntradaPago;
    }

    /**
     * Set fechaSalidaPago
     *
     * @param \DateTime $fechaSalidaPago
     *
     * @return RhuHorarioAcceso
     */
    public function setFechaSalidaPago($fechaSalidaPago)
    {
        $this->fechaSalidaPago = $fechaSalidaPago;

        return $this;
    }

    /**
     * Get fechaSalidaPago
     *
     * @return \DateTime
     */
    public function getFechaSalidaPago()
    {
        return $this->fechaSalidaPago;
    }

    /**
     * Set generaHoraExtra
     *
     * @param boolean $generaHoraExtra
     *
     * @return RhuHorarioAcceso
     */
    public function setGeneraHoraExtra($generaHoraExtra)
    {
        $this->generaHoraExtra = $generaHoraExtra;

        return $this;
    }

    /**
     * Get generaHoraExtra
     *
     * @return boolean
     */
    public function getGeneraHoraExtra()
    {
        return $this->generaHoraExtra;
    }
}
