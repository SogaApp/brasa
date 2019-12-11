<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_permiso")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPermisoRepository")
 */
class RhuPermiso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_permiso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPermisoPk;

    /**
     * @ORM\Column(name="codigo_permiso_tipo_fk", type="integer", nullable=true)
     */
    private $codigoPermisoTipoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_departamento_empresa_fk", type="integer", nullable=true)
     */
    private $codigoDepartamentoEmpresaFk;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */
    private $codigoCargoFk;
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="fecha_permiso", type="date", nullable=true)
     */
    private $fechaPermiso;

    /**
     * @ORM\Column(name="hora_salida", type="time", nullable=true)
     */
    private $horaSalida;

    /**
     * @ORM\Column(name="hora_llegada", type="time", nullable=true)
     */
    private $horaLlegada;

    /**
     * @ORM\Column(name="horas_permiso", type="float")
     */
    private $horasPermiso = 0;

    /**
     * @ORM\Column(name="motivo", type="string", length=120, nullable=true)
     */
    private $motivo;

    /**
     * @ORM\Column(name="jefe_autoriza", type="string", length=120, nullable=true)
     */
    private $jefeAutoriza;

    /**
     * @ORM\Column(name="afecta_horario", type="boolean")
     */
    private $afectaHorario = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = 0;

    /**
     * @ORM\Column(name="observaciones", type="string", length=250, nullable=true)
     */
    private $observaciones;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;


    /**
     * @ORM\ManyToOne(targetEntity="RhuPermisoTipo", inversedBy="permisosPermisoTipoRel")
     * @ORM\JoinColumn(name="codigo_permiso_tipo_fk", referencedColumnName="codigo_permiso_tipo_pk")
     */
    protected $permisoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="permisosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuDepartamentoEmpresa", inversedBy="permisosDepartamentoEmpresaRel")
     * @ORM\JoinColumn(name="codigo_departamento_empresa_fk", referencedColumnName="codigo_departamento_empresa_pk")
     */
    protected $departamentoEmpresaRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="permisosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="permisosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;

    


    /**
     * Get codigoPermisoPk
     *
     * @return integer
     */
    public function getCodigoPermisoPk()
    {
        return $this->codigoPermisoPk;
    }

    /**
     * Set codigoPermisoTipoFk
     *
     * @param integer $codigoPermisoTipoFk
     *
     * @return RhuPermiso
     */
    public function setCodigoPermisoTipoFk($codigoPermisoTipoFk)
    {
        $this->codigoPermisoTipoFk = $codigoPermisoTipoFk;

        return $this;
    }

    /**
     * Get codigoPermisoTipoFk
     *
     * @return integer
     */
    public function getCodigoPermisoTipoFk()
    {
        return $this->codigoPermisoTipoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuPermiso
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
     * Set codigoDepartamentoEmpresaFk
     *
     * @param integer $codigoDepartamentoEmpresaFk
     *
     * @return RhuPermiso
     */
    public function setCodigoDepartamentoEmpresaFk($codigoDepartamentoEmpresaFk)
    {
        $this->codigoDepartamentoEmpresaFk = $codigoDepartamentoEmpresaFk;

        return $this;
    }

    /**
     * Get codigoDepartamentoEmpresaFk
     *
     * @return integer
     */
    public function getCodigoDepartamentoEmpresaFk()
    {
        return $this->codigoDepartamentoEmpresaFk;
    }

    /**
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuPermiso
     */
    public function setCodigoCargoFk($codigoCargoFk)
    {
        $this->codigoCargoFk = $codigoCargoFk;

        return $this;
    }

    /**
     * Get codigoCargoFk
     *
     * @return integer
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuPermiso
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return integer
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set fechaPermiso
     *
     * @param \DateTime $fechaPermiso
     *
     * @return RhuPermiso
     */
    public function setFechaPermiso($fechaPermiso)
    {
        $this->fechaPermiso = $fechaPermiso;

        return $this;
    }

    /**
     * Get fechaPermiso
     *
     * @return \DateTime
     */
    public function getFechaPermiso()
    {
        return $this->fechaPermiso;
    }

    /**
     * Set horaSalida
     *
     * @param \DateTime $horaSalida
     *
     * @return RhuPermiso
     */
    public function setHoraSalida($horaSalida)
    {
        $this->horaSalida = $horaSalida;

        return $this;
    }

    /**
     * Get horaSalida
     *
     * @return \DateTime
     */
    public function getHoraSalida()
    {
        return $this->horaSalida;
    }

    /**
     * Set horaLlegada
     *
     * @param \DateTime $horaLlegada
     *
     * @return RhuPermiso
     */
    public function setHoraLlegada($horaLlegada)
    {
        $this->horaLlegada = $horaLlegada;

        return $this;
    }

    /**
     * Get horaLlegada
     *
     * @return \DateTime
     */
    public function getHoraLlegada()
    {
        return $this->horaLlegada;
    }

    /**
     * Set horasPermiso
     *
     * @param float $horasPermiso
     *
     * @return RhuPermiso
     */
    public function setHorasPermiso($horasPermiso)
    {
        $this->horasPermiso = $horasPermiso;

        return $this;
    }

    /**
     * Get horasPermiso
     *
     * @return float
     */
    public function getHorasPermiso()
    {
        return $this->horasPermiso;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     *
     * @return RhuPermiso
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo
     *
     * @return string
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set jefeAutoriza
     *
     * @param string $jefeAutoriza
     *
     * @return RhuPermiso
     */
    public function setJefeAutoriza($jefeAutoriza)
    {
        $this->jefeAutoriza = $jefeAutoriza;

        return $this;
    }

    /**
     * Get jefeAutoriza
     *
     * @return string
     */
    public function getJefeAutoriza()
    {
        return $this->jefeAutoriza;
    }

    /**
     * Set afectaHorario
     *
     * @param boolean $afectaHorario
     *
     * @return RhuPermiso
     */
    public function setAfectaHorario($afectaHorario)
    {
        $this->afectaHorario = $afectaHorario;

        return $this;
    }

    /**
     * Get afectaHorario
     *
     * @return boolean
     */
    public function getAfectaHorario()
    {
        return $this->afectaHorario;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuPermiso
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return RhuPermiso
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuPermiso
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
     * Set permisoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPermisoTipo $permisoTipoRel
     *
     * @return RhuPermiso
     */
    public function setPermisoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPermisoTipo $permisoTipoRel = null)
    {
        $this->permisoTipoRel = $permisoTipoRel;

        return $this;
    }

    /**
     * Get permisoTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPermisoTipo
     */
    public function getPermisoTipoRel()
    {
        return $this->permisoTipoRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuPermiso
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
     * Set departamentoEmpresaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa $departamentoEmpresaRel
     *
     * @return RhuPermiso
     */
    public function setDepartamentoEmpresaRel(\Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa $departamentoEmpresaRel = null)
    {
        $this->departamentoEmpresaRel = $departamentoEmpresaRel;

        return $this;
    }

    /**
     * Get departamentoEmpresaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa
     */
    public function getDepartamentoEmpresaRel()
    {
        return $this->departamentoEmpresaRel;
    }

    /**
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuPermiso
     */
    public function setCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel = null)
    {
        $this->cargoRel = $cargoRel;

        return $this;
    }

    /**
     * Get cargoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCargo
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuPermiso
     */
    public function setCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }
}
