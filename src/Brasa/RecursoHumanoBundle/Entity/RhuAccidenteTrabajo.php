<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_accidente_trabajo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuAccidenteTrabajoRepository")
 */
class RhuAccidenteTrabajo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_accidente_trabajo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAccidenteTrabajoPk;
    
    /**
     * @ORM\Column(name="codigo_furat", type="integer", nullable=true)
     */    
    private $codigoFurat;
    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="codigo_entidad_riesgo_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadRiesgoFk;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="fecha_accidente", type="date", nullable=true)
     */    
    private $fechaAccidente;
    
    /**
     * @ORM\Column(name="codigo_accidente_trabajo_tipo_accidente_fk", type="integer", nullable=true)
     */    
    private $codigoAccidenteTrabajoTipoAccidenteFk;
    
    /**
     * @ORM\Column(name="fecha_envia_investigacion", type="date", nullable=true)
     */    
    private $fechaEnviaInvestigacion;
    
    /**
     * @ORM\Column(name="coordinador_encargado", type="string", length=100, nullable=true)
     */    
    private $coordinadorEncargado;
    
    /**
     * @ORM\Column(name="cargo_coordinador_encargado", type="string", length=100, nullable=true)
     */    
    private $cargoCoordinadorEncargado;
    
    /**
     * @ORM\Column(name="codigo_ciudad_accidente_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadAccidenteFk;
    
    /**
     * @ORM\Column(name="fecha_incapacidad_desde", type="date", nullable=true)
     */    
    private $fechaIncapacidadDesde;
    
    /**
     * @ORM\Column(name="fecha_incapacidad_hasta", type="date", nullable=true)
     */    
    private $fechaIncapacidadHasta;
    
    /**
     * @ORM\Column(name="dias", type="integer", nullable=true)
     */
    private $dias;
    
    /**
     * @ORM\Column(name="cie10", type="string", length=20, nullable=true)
     */    
    private $cie10;
    
    /**
     * @ORM\Column(name="diagnostico", type="string", length=1000, nullable=true)
     */    
    private $diagnostico;
    
    /**
     * @ORM\Column(name="naturaleza_lesion", type="string", length=200, nullable=true)
     */    
    private $naturalezaLesion;
    
    /**
     * @ORM\Column(name="cuerpo_afectado", type="string", length=200, nullable=true)
     */    
    private $cuerpoAfectado;
    
    /**
     * @ORM\Column(name="agente", type="string", length=200, nullable=true)
     */    
    private $agente;
    
    /**
     * @ORM\Column(name="mecanismo_accidente", type="string", length=200, nullable=true)
     */    
    private $mecanismoAccidente;
    
    /**
     * @ORM\Column(name="oficio_habitual", type="string", length=120, nullable=true)
     */    
    private $oficioHabitual;
    
    /**
     * @ORM\Column(name="lugar_accidente", type="string", length=100, nullable=true)
     */    
    private $lugarAccidente;
    
    /**
     * @ORM\Column(name="tiempo_servicio_empleado", type="string", length=20, nullable=true)
     */    
    private $tiempoServicioEmpleado;
    
    /**
     * @ORM\Column(name="tarea_desarrollada_momento_accidente", type="string", length=120, nullable=true)
     */    
    private $tareaDesarrolladamomentoAccidente;
    
    /**
     * @ORM\Column(name="accidente_ocurrido_lugar_habitual", type="boolean")
     */    
    private $accidenteOcurrioLugarHabitual = 0;
    
    /**
     * @ORM\Column(name="descripcion_accidente", type="string", length=1000, nullable=true)
     */    
    private $descripcionAccidente;
    
    /**
     * @ORM\Column(name="acto_inseguro", type="string", length=100, nullable=true)
     */    
    private $actoInseguro;
    
    /**
     * @ORM\Column(name="condicion_insegura", type="string", length=100, nullable=true)
     */    
    private $condicionInsegura;
    
    /**
     * @ORM\Column(name="factor_personal", type="string", length=200, nullable=true)
     */    
    private $factorPersonal;
    
    /**
     * @ORM\Column(name="factor_trabajo", type="string", length=200, nullable=true)
     */    
    private $factorTrabajo;
    
    /**
     * @ORM\Column(name="plan_accion_1", type="string", length=200, nullable=true)
     */    
    private $planAccion1;
    
    /**
     * @ORM\Column(name="codigo_accidente_trabajo_tipo_control_uno_fk", type="integer", nullable=true)
     */    
    private $codigoAccidenteTrabajoTipoControlUnoFk;
    
    /**
     * @ORM\Column(name="fecha_verificacion_1", type="date", nullable=true)
     */    
    private $fechaVerificacion1;
    
    /**
     * @ORM\Column(name="area_responsable_1", type="string", length=50, nullable=true)
     */    
    private $areaResponsable1;
    
    /**
     * @ORM\Column(name="plan_accion_2", type="string", length=200, nullable=true)
     */    
    private $planAccion2;
    
    /**
     * @ORM\Column(name="codigo_accidente_trabajo_tipo_control_dos_fk", type="integer", nullable=true)
     */    
    private $codigoAccidenteTrabajoTipoControlDosFk;
    
    /**
     * @ORM\Column(name="fecha_verificacion_2", type="date", nullable=true)
     */    
    private $fechaVerificacion2;
    
    /**
     * @ORM\Column(name="area_responsable_2", type="string", length=50, nullable=true)
     */    
    private $areaResponsable2;
    
    /**
     * @ORM\Column(name="plan_accion_3", type="string", length=200, nullable=true)
     */    
    private $planAccion3;
    
    /**
     * @ORM\Column(name="codigo_accidente_trabajo_tipo_control_tres_fk", type="integer", nullable=true)
     */    
    private $codigoAccidenteTrabajoTipoControlTresFk;
    
    /**
     * @ORM\Column(name="fecha_verificacion_3", type="date", nullable=true)
     */    
    private $fechaVerificacion3;
    
    /**
     * @ORM\Column(name="area_responsable_3", type="string", length=50, nullable=true)
     */    
    private $areaResponsable3;
    
    /**
     * @ORM\Column(name="participante_investigacion_1", type="string", length=100, nullable=true)
     */    
    private $participanteInvestigacion1;
    
    /**
     * @ORM\Column(name="cargo_participante_investigacion_1", type="string", length=100, nullable=true)
     */    
    private $cargoParticipanteInvestigacion1;
    
    /**
     * @ORM\Column(name="participante_investigacion_2", type="string", length=100, nullable=true)
     */    
    private $participanteInvestigacion2;
    
    /**
     * @ORM\Column(name="cargo_participante_investigacion_2", type="string", length=100, nullable=true)
     */    
    private $cargoParticipanteInvestigacion2;
    
    /**
     * @ORM\Column(name="participante_investigacion_3", type="string", length=100, nullable=true)
     */    
    private $participanteInvestigacion3;
    
    /**
     * @ORM\Column(name="cargo_participante_investigacion_3", type="string", length=100, nullable=true)
     */    
    private $cargoParticipanteInvestigacion3;
    
    /**
     * @ORM\Column(name="representante_legal", type="string", length=100, nullable=true)
     */    
    private $representanteLegal;
    
    /**
     * @ORM\Column(name="cargo_representante_legal", type="string", length=100, nullable=true)
     */    
    private $CargoRepresentanteLegal;
    
    
    /**
     * @ORM\Column(name="licencia", type="string", length=20, nullable=true)
     */    
    private $licencia;
    
    /**
     * @ORM\Column(name="fecha_verificacion", type="date", nullable=true)
     */    
    private $fechaVerificacion;
    
    /**
     * @ORM\Column(name="responsable_verificacion", type="string", length=100, nullable=true)
     */    
    private $responsableVerificacion;
    
    /**
     * @ORM\Column(name="estado_accidente", type="boolean")
     */    
    private $estadoAccidente = 0;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="accidentesTrabajoCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="accidentesTrabajoEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuaccidentesTrabajoCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_accidente_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadRiesgoProfesional", inversedBy="accidentesTrabajoEntidadRiesgoRel")
     * @ORM\JoinColumn(name="codigo_entidad_riesgo_fk", referencedColumnName="codigo_entidad_riesgo_pk")
     */
    protected $entidadRiesgoProfesionalRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuAccidenteTrabajoTipoAccidente", inversedBy="accidentesTrabajoAccidenteTrabajoTipoAccidenteRel")
     * @ORM\JoinColumn(name="codigo_accidente_trabajo_tipo_accidente_fk", referencedColumnName="codigo_accidente_trabajo_tipo_accidente_pk")
     */
    protected $tipoAccidenteRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuAccidenteTrabajoTipoControl", inversedBy="accidentesTrabajoAccidenteTrabajoTipoControlUnoRel")
     * @ORM\JoinColumn(name="codigo_accidente_trabajo_tipo_control_uno_fk", referencedColumnName="codigo_accidente_trabajo_tipo_control_pk")
     */
    protected $tipoControlUnoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuAccidenteTrabajoTipoControl", inversedBy="accidentesTrabajoAccidenteTrabajoTipoControlDosRel")
     * @ORM\JoinColumn(name="codigo_accidente_trabajo_tipo_control_dos_fk", referencedColumnName="codigo_accidente_trabajo_tipo_control_pk")
     */
    protected $tipoControlDosRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuAccidenteTrabajoTipoControl", inversedBy="accidentesTrabajoAccidenteTrabajoTipoControlTresRel")
     * @ORM\JoinColumn(name="codigo_accidente_trabajo_tipo_control_tres_fk", referencedColumnName="codigo_accidente_trabajo_tipo_control_pk")
     */
    protected $tipoControlTresRel;
    
   

    /**
     * Get codigoAccidenteTrabajoPk
     *
     * @return integer
     */
    public function getCodigoAccidenteTrabajoPk()
    {
        return $this->codigoAccidenteTrabajoPk;
    }

    /**
     * Set codigoFurat
     *
     * @param integer $codigoFurat
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCodigoFurat($codigoFurat)
    {
        $this->codigoFurat = $codigoFurat;

        return $this;
    }

    /**
     * Get codigoFurat
     *
     * @return integer
     */
    public function getCodigoFurat()
    {
        return $this->codigoFurat;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuAccidenteTrabajo
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
     * Set codigoEntidadRiesgoFk
     *
     * @param integer $codigoEntidadRiesgoFk
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCodigoEntidadRiesgoFk($codigoEntidadRiesgoFk)
    {
        $this->codigoEntidadRiesgoFk = $codigoEntidadRiesgoFk;

        return $this;
    }

    /**
     * Get codigoEntidadRiesgoFk
     *
     * @return integer
     */
    public function getCodigoEntidadRiesgoFk()
    {
        return $this->codigoEntidadRiesgoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuAccidenteTrabajo
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
     * Set fechaAccidente
     *
     * @param \DateTime $fechaAccidente
     *
     * @return RhuAccidenteTrabajo
     */
    public function setFechaAccidente($fechaAccidente)
    {
        $this->fechaAccidente = $fechaAccidente;

        return $this;
    }

    /**
     * Get fechaAccidente
     *
     * @return \DateTime
     */
    public function getFechaAccidente()
    {
        return $this->fechaAccidente;
    }

    /**
     * Set codigoAccidenteTrabajoTipoAccidenteFk
     *
     * @param integer $codigoAccidenteTrabajoTipoAccidenteFk
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCodigoAccidenteTrabajoTipoAccidenteFk($codigoAccidenteTrabajoTipoAccidenteFk)
    {
        $this->codigoAccidenteTrabajoTipoAccidenteFk = $codigoAccidenteTrabajoTipoAccidenteFk;

        return $this;
    }

    /**
     * Get codigoAccidenteTrabajoTipoAccidenteFk
     *
     * @return integer
     */
    public function getCodigoAccidenteTrabajoTipoAccidenteFk()
    {
        return $this->codigoAccidenteTrabajoTipoAccidenteFk;
    }

    /**
     * Set fechaEnviaInvestigacion
     *
     * @param \DateTime $fechaEnviaInvestigacion
     *
     * @return RhuAccidenteTrabajo
     */
    public function setFechaEnviaInvestigacion($fechaEnviaInvestigacion)
    {
        $this->fechaEnviaInvestigacion = $fechaEnviaInvestigacion;

        return $this;
    }

    /**
     * Get fechaEnviaInvestigacion
     *
     * @return \DateTime
     */
    public function getFechaEnviaInvestigacion()
    {
        return $this->fechaEnviaInvestigacion;
    }

    /**
     * Set codigoCiudadAccidenteFk
     *
     * @param integer $codigoCiudadAccidenteFk
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCodigoCiudadAccidenteFk($codigoCiudadAccidenteFk)
    {
        $this->codigoCiudadAccidenteFk = $codigoCiudadAccidenteFk;

        return $this;
    }

    /**
     * Get codigoCiudadAccidenteFk
     *
     * @return integer
     */
    public function getCodigoCiudadAccidenteFk()
    {
        return $this->codigoCiudadAccidenteFk;
    }

    /**
     * Set fechaIncapacidadDesde
     *
     * @param \DateTime $fechaIncapacidadDesde
     *
     * @return RhuAccidenteTrabajo
     */
    public function setFechaIncapacidadDesde($fechaIncapacidadDesde)
    {
        $this->fechaIncapacidadDesde = $fechaIncapacidadDesde;

        return $this;
    }

    /**
     * Get fechaIncapacidadDesde
     *
     * @return \DateTime
     */
    public function getFechaIncapacidadDesde()
    {
        return $this->fechaIncapacidadDesde;
    }

    /**
     * Set fechaIncapacidadHasta
     *
     * @param \DateTime $fechaIncapacidadHasta
     *
     * @return RhuAccidenteTrabajo
     */
    public function setFechaIncapacidadHasta($fechaIncapacidadHasta)
    {
        $this->fechaIncapacidadHasta = $fechaIncapacidadHasta;

        return $this;
    }

    /**
     * Get fechaIncapacidadHasta
     *
     * @return \DateTime
     */
    public function getFechaIncapacidadHasta()
    {
        return $this->fechaIncapacidadHasta;
    }

    /**
     * Set dias
     *
     * @param integer $dias
     *
     * @return RhuAccidenteTrabajo
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
     * Set cie10
     *
     * @param string $cie10
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCie10($cie10)
    {
        $this->cie10 = $cie10;

        return $this;
    }

    /**
     * Get cie10
     *
     * @return string
     */
    public function getCie10()
    {
        return $this->cie10;
    }

    /**
     * Set diagnostico
     *
     * @param string $diagnostico
     *
     * @return RhuAccidenteTrabajo
     */
    public function setDiagnostico($diagnostico)
    {
        $this->diagnostico = $diagnostico;

        return $this;
    }

    /**
     * Get diagnostico
     *
     * @return string
     */
    public function getDiagnostico()
    {
        return $this->diagnostico;
    }

    /**
     * Set naturalezaLesion
     *
     * @param string $naturalezaLesion
     *
     * @return RhuAccidenteTrabajo
     */
    public function setNaturalezaLesion($naturalezaLesion)
    {
        $this->naturalezaLesion = $naturalezaLesion;

        return $this;
    }

    /**
     * Get naturalezaLesion
     *
     * @return string
     */
    public function getNaturalezaLesion()
    {
        return $this->naturalezaLesion;
    }

    /**
     * Set cuerpoAfectado
     *
     * @param string $cuerpoAfectado
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCuerpoAfectado($cuerpoAfectado)
    {
        $this->cuerpoAfectado = $cuerpoAfectado;

        return $this;
    }

    /**
     * Get cuerpoAfectado
     *
     * @return string
     */
    public function getCuerpoAfectado()
    {
        return $this->cuerpoAfectado;
    }

    /**
     * Set agente
     *
     * @param string $agente
     *
     * @return RhuAccidenteTrabajo
     */
    public function setAgente($agente)
    {
        $this->agente = $agente;

        return $this;
    }

    /**
     * Get agente
     *
     * @return string
     */
    public function getAgente()
    {
        return $this->agente;
    }

    /**
     * Set mecanismoAccidente
     *
     * @param string $mecanismoAccidente
     *
     * @return RhuAccidenteTrabajo
     */
    public function setMecanismoAccidente($mecanismoAccidente)
    {
        $this->mecanismoAccidente = $mecanismoAccidente;

        return $this;
    }

    /**
     * Get mecanismoAccidente
     *
     * @return string
     */
    public function getMecanismoAccidente()
    {
        return $this->mecanismoAccidente;
    }

    /**
     * Set lugarAccidente
     *
     * @param string $lugarAccidente
     *
     * @return RhuAccidenteTrabajo
     */
    public function setLugarAccidente($lugarAccidente)
    {
        $this->lugarAccidente = $lugarAccidente;

        return $this;
    }

    /**
     * Get lugarAccidente
     *
     * @return string
     */
    public function getLugarAccidente()
    {
        return $this->lugarAccidente;
    }

    /**
     * Set descripcionAccidente
     *
     * @param string $descripcionAccidente
     *
     * @return RhuAccidenteTrabajo
     */
    public function setDescripcionAccidente($descripcionAccidente)
    {
        $this->descripcionAccidente = $descripcionAccidente;

        return $this;
    }

    /**
     * Get descripcionAccidente
     *
     * @return string
     */
    public function getDescripcionAccidente()
    {
        return $this->descripcionAccidente;
    }

    /**
     * Set actoInseguro
     *
     * @param string $actoInseguro
     *
     * @return RhuAccidenteTrabajo
     */
    public function setActoInseguro($actoInseguro)
    {
        $this->actoInseguro = $actoInseguro;

        return $this;
    }

    /**
     * Get actoInseguro
     *
     * @return string
     */
    public function getActoInseguro()
    {
        return $this->actoInseguro;
    }

    /**
     * Set condicionInsegura
     *
     * @param string $condicionInsegura
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCondicionInsegura($condicionInsegura)
    {
        $this->condicionInsegura = $condicionInsegura;

        return $this;
    }

    /**
     * Get condicionInsegura
     *
     * @return string
     */
    public function getCondicionInsegura()
    {
        return $this->condicionInsegura;
    }

    /**
     * Set factorPersonal
     *
     * @param string $factorPersonal
     *
     * @return RhuAccidenteTrabajo
     */
    public function setFactorPersonal($factorPersonal)
    {
        $this->factorPersonal = $factorPersonal;

        return $this;
    }

    /**
     * Get factorPersonal
     *
     * @return string
     */
    public function getFactorPersonal()
    {
        return $this->factorPersonal;
    }

    /**
     * Set factorTrabajo
     *
     * @param string $factorTrabajo
     *
     * @return RhuAccidenteTrabajo
     */
    public function setFactorTrabajo($factorTrabajo)
    {
        $this->factorTrabajo = $factorTrabajo;

        return $this;
    }

    /**
     * Get factorTrabajo
     *
     * @return string
     */
    public function getFactorTrabajo()
    {
        return $this->factorTrabajo;
    }

    /**
     * Set planAccion1
     *
     * @param string $planAccion1
     *
     * @return RhuAccidenteTrabajo
     */
    public function setPlanAccion1($planAccion1)
    {
        $this->planAccion1 = $planAccion1;

        return $this;
    }

    /**
     * Get planAccion1
     *
     * @return string
     */
    public function getPlanAccion1()
    {
        return $this->planAccion1;
    }

    /**
     * Set codigoAccidenteTrabajoTipoControlUnoFk
     *
     * @param integer $codigoAccidenteTrabajoTipoControlUnoFk
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCodigoAccidenteTrabajoTipoControlUnoFk($codigoAccidenteTrabajoTipoControlUnoFk)
    {
        $this->codigoAccidenteTrabajoTipoControlUnoFk = $codigoAccidenteTrabajoTipoControlUnoFk;

        return $this;
    }

    /**
     * Get codigoAccidenteTrabajoTipoControlUnoFk
     *
     * @return integer
     */
    public function getCodigoAccidenteTrabajoTipoControlUnoFk()
    {
        return $this->codigoAccidenteTrabajoTipoControlUnoFk;
    }

    /**
     * Set fechaVerificacion1
     *
     * @param \DateTime $fechaVerificacion1
     *
     * @return RhuAccidenteTrabajo
     */
    public function setFechaVerificacion1($fechaVerificacion1)
    {
        $this->fechaVerificacion1 = $fechaVerificacion1;

        return $this;
    }

    /**
     * Get fechaVerificacion1
     *
     * @return \DateTime
     */
    public function getFechaVerificacion1()
    {
        return $this->fechaVerificacion1;
    }

    /**
     * Set areaResponsable1
     *
     * @param string $areaResponsable1
     *
     * @return RhuAccidenteTrabajo
     */
    public function setAreaResponsable1($areaResponsable1)
    {
        $this->areaResponsable1 = $areaResponsable1;

        return $this;
    }

    /**
     * Get areaResponsable1
     *
     * @return string
     */
    public function getAreaResponsable1()
    {
        return $this->areaResponsable1;
    }

    /**
     * Set planAccion2
     *
     * @param string $planAccion2
     *
     * @return RhuAccidenteTrabajo
     */
    public function setPlanAccion2($planAccion2)
    {
        $this->planAccion2 = $planAccion2;

        return $this;
    }

    /**
     * Get planAccion2
     *
     * @return string
     */
    public function getPlanAccion2()
    {
        return $this->planAccion2;
    }

    /**
     * Set codigoAccidenteTrabajoTipoControlDosFk
     *
     * @param integer $codigoAccidenteTrabajoTipoControlDosFk
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCodigoAccidenteTrabajoTipoControlDosFk($codigoAccidenteTrabajoTipoControlDosFk)
    {
        $this->codigoAccidenteTrabajoTipoControlDosFk = $codigoAccidenteTrabajoTipoControlDosFk;

        return $this;
    }

    /**
     * Get codigoAccidenteTrabajoTipoControlDosFk
     *
     * @return integer
     */
    public function getCodigoAccidenteTrabajoTipoControlDosFk()
    {
        return $this->codigoAccidenteTrabajoTipoControlDosFk;
    }

    /**
     * Set fechaVerificacion2
     *
     * @param \DateTime $fechaVerificacion2
     *
     * @return RhuAccidenteTrabajo
     */
    public function setFechaVerificacion2($fechaVerificacion2)
    {
        $this->fechaVerificacion2 = $fechaVerificacion2;

        return $this;
    }

    /**
     * Get fechaVerificacion2
     *
     * @return \DateTime
     */
    public function getFechaVerificacion2()
    {
        return $this->fechaVerificacion2;
    }

    /**
     * Set areaResponsable2
     *
     * @param string $areaResponsable2
     *
     * @return RhuAccidenteTrabajo
     */
    public function setAreaResponsable2($areaResponsable2)
    {
        $this->areaResponsable2 = $areaResponsable2;

        return $this;
    }

    /**
     * Get areaResponsable2
     *
     * @return string
     */
    public function getAreaResponsable2()
    {
        return $this->areaResponsable2;
    }

    /**
     * Set planAccion3
     *
     * @param string $planAccion3
     *
     * @return RhuAccidenteTrabajo
     */
    public function setPlanAccion3($planAccion3)
    {
        $this->planAccion3 = $planAccion3;

        return $this;
    }

    /**
     * Get planAccion3
     *
     * @return string
     */
    public function getPlanAccion3()
    {
        return $this->planAccion3;
    }

    /**
     * Set codigoAccidenteTrabajoTipoControlTresFk
     *
     * @param integer $codigoAccidenteTrabajoTipoControlTresFk
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCodigoAccidenteTrabajoTipoControlTresFk($codigoAccidenteTrabajoTipoControlTresFk)
    {
        $this->codigoAccidenteTrabajoTipoControlTresFk = $codigoAccidenteTrabajoTipoControlTresFk;

        return $this;
    }

    /**
     * Get codigoAccidenteTrabajoTipoControlTresFk
     *
     * @return integer
     */
    public function getCodigoAccidenteTrabajoTipoControlTresFk()
    {
        return $this->codigoAccidenteTrabajoTipoControlTresFk;
    }

    /**
     * Set fechaVerificacion3
     *
     * @param \DateTime $fechaVerificacion3
     *
     * @return RhuAccidenteTrabajo
     */
    public function setFechaVerificacion3($fechaVerificacion3)
    {
        $this->fechaVerificacion3 = $fechaVerificacion3;

        return $this;
    }

    /**
     * Get fechaVerificacion3
     *
     * @return \DateTime
     */
    public function getFechaVerificacion3()
    {
        return $this->fechaVerificacion3;
    }

    /**
     * Set areaResponsable3
     *
     * @param string $areaResponsable3
     *
     * @return RhuAccidenteTrabajo
     */
    public function setAreaResponsable3($areaResponsable3)
    {
        $this->areaResponsable3 = $areaResponsable3;

        return $this;
    }

    /**
     * Get areaResponsable3
     *
     * @return string
     */
    public function getAreaResponsable3()
    {
        return $this->areaResponsable3;
    }

    /**
     * Set participanteInvestigacion1
     *
     * @param string $participanteInvestigacion1
     *
     * @return RhuAccidenteTrabajo
     */
    public function setParticipanteInvestigacion1($participanteInvestigacion1)
    {
        $this->participanteInvestigacion1 = $participanteInvestigacion1;

        return $this;
    }

    /**
     * Get participanteInvestigacion1
     *
     * @return string
     */
    public function getParticipanteInvestigacion1()
    {
        return $this->participanteInvestigacion1;
    }

    /**
     * Set cargoParticipanteInvestigacion1
     *
     * @param string $cargoParticipanteInvestigacion1
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCargoParticipanteInvestigacion1($cargoParticipanteInvestigacion1)
    {
        $this->cargoParticipanteInvestigacion1 = $cargoParticipanteInvestigacion1;

        return $this;
    }

    /**
     * Get cargoParticipanteInvestigacion1
     *
     * @return string
     */
    public function getCargoParticipanteInvestigacion1()
    {
        return $this->cargoParticipanteInvestigacion1;
    }

    /**
     * Set participanteInvestigacion2
     *
     * @param string $participanteInvestigacion2
     *
     * @return RhuAccidenteTrabajo
     */
    public function setParticipanteInvestigacion2($participanteInvestigacion2)
    {
        $this->participanteInvestigacion2 = $participanteInvestigacion2;

        return $this;
    }

    /**
     * Get participanteInvestigacion2
     *
     * @return string
     */
    public function getParticipanteInvestigacion2()
    {
        return $this->participanteInvestigacion2;
    }

    /**
     * Set cargoParticipanteInvestigacion2
     *
     * @param string $cargoParticipanteInvestigacion2
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCargoParticipanteInvestigacion2($cargoParticipanteInvestigacion2)
    {
        $this->cargoParticipanteInvestigacion2 = $cargoParticipanteInvestigacion2;

        return $this;
    }

    /**
     * Get cargoParticipanteInvestigacion2
     *
     * @return string
     */
    public function getCargoParticipanteInvestigacion2()
    {
        return $this->cargoParticipanteInvestigacion2;
    }

    /**
     * Set participanteInvestigacion3
     *
     * @param string $participanteInvestigacion3
     *
     * @return RhuAccidenteTrabajo
     */
    public function setParticipanteInvestigacion3($participanteInvestigacion3)
    {
        $this->participanteInvestigacion3 = $participanteInvestigacion3;

        return $this;
    }

    /**
     * Get participanteInvestigacion3
     *
     * @return string
     */
    public function getParticipanteInvestigacion3()
    {
        return $this->participanteInvestigacion3;
    }

    /**
     * Set cargoParticipanteInvestigacion3
     *
     * @param string $cargoParticipanteInvestigacion3
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCargoParticipanteInvestigacion3($cargoParticipanteInvestigacion3)
    {
        $this->cargoParticipanteInvestigacion3 = $cargoParticipanteInvestigacion3;

        return $this;
    }

    /**
     * Get cargoParticipanteInvestigacion3
     *
     * @return string
     */
    public function getCargoParticipanteInvestigacion3()
    {
        return $this->cargoParticipanteInvestigacion3;
    }

    /**
     * Set representanteLegal
     *
     * @param string $representanteLegal
     *
     * @return RhuAccidenteTrabajo
     */
    public function setRepresentanteLegal($representanteLegal)
    {
        $this->representanteLegal = $representanteLegal;

        return $this;
    }

    /**
     * Get representanteLegal
     *
     * @return string
     */
    public function getRepresentanteLegal()
    {
        return $this->representanteLegal;
    }

    /**
     * Set cargoRepresentanteLegal
     *
     * @param string $cargoRepresentanteLegal
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCargoRepresentanteLegal($cargoRepresentanteLegal)
    {
        $this->CargoRepresentanteLegal = $cargoRepresentanteLegal;

        return $this;
    }

    /**
     * Get cargoRepresentanteLegal
     *
     * @return string
     */
    public function getCargoRepresentanteLegal()
    {
        return $this->CargoRepresentanteLegal;
    }

    /**
     * Set licencia
     *
     * @param string $licencia
     *
     * @return RhuAccidenteTrabajo
     */
    public function setLicencia($licencia)
    {
        $this->licencia = $licencia;

        return $this;
    }

    /**
     * Get licencia
     *
     * @return string
     */
    public function getLicencia()
    {
        return $this->licencia;
    }

    /**
     * Set fechaVerificacion
     *
     * @param \DateTime $fechaVerificacion
     *
     * @return RhuAccidenteTrabajo
     */
    public function setFechaVerificacion($fechaVerificacion)
    {
        $this->fechaVerificacion = $fechaVerificacion;

        return $this;
    }

    /**
     * Get fechaVerificacion
     *
     * @return \DateTime
     */
    public function getFechaVerificacion()
    {
        return $this->fechaVerificacion;
    }

    /**
     * Set responsableVerificacion
     *
     * @param string $responsableVerificacion
     *
     * @return RhuAccidenteTrabajo
     */
    public function setResponsableVerificacion($responsableVerificacion)
    {
        $this->responsableVerificacion = $responsableVerificacion;

        return $this;
    }

    /**
     * Get responsableVerificacion
     *
     * @return string
     */
    public function getResponsableVerificacion()
    {
        return $this->responsableVerificacion;
    }

    /**
     * Set estadoAccidente
     *
     * @param boolean $estadoAccidente
     *
     * @return RhuAccidenteTrabajo
     */
    public function setEstadoAccidente($estadoAccidente)
    {
        $this->estadoAccidente = $estadoAccidente;

        return $this;
    }

    /**
     * Get estadoAccidente
     *
     * @return boolean
     */
    public function getEstadoAccidente()
    {
        return $this->estadoAccidente;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuAccidenteTrabajo
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

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuAccidenteTrabajo
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
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCiudadRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel = null)
    {
        $this->ciudadRel = $ciudadRel;

        return $this;
    }

    /**
     * Get ciudadRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * Set entidadRiesgoProfesionalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional $entidadRiesgoProfesionalRel
     *
     * @return RhuAccidenteTrabajo
     */
    public function setEntidadRiesgoProfesionalRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional $entidadRiesgoProfesionalRel = null)
    {
        $this->entidadRiesgoProfesionalRel = $entidadRiesgoProfesionalRel;

        return $this;
    }

    /**
     * Get entidadRiesgoProfesionalRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional
     */
    public function getEntidadRiesgoProfesionalRel()
    {
        return $this->entidadRiesgoProfesionalRel;
    }

    /**
     * Set tipoAccidenteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajoTipoAccidente $tipoAccidenteRel
     *
     * @return RhuAccidenteTrabajo
     */
    public function setTipoAccidenteRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajoTipoAccidente $tipoAccidenteRel = null)
    {
        $this->tipoAccidenteRel = $tipoAccidenteRel;

        return $this;
    }

    /**
     * Get tipoAccidenteRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajoTipoAccidente
     */
    public function getTipoAccidenteRel()
    {
        return $this->tipoAccidenteRel;
    }

    /**
     * Set tipoControlUnoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajoTipoControl $tipoControlUnoRel
     *
     * @return RhuAccidenteTrabajo
     */
    public function setTipoControlUnoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajoTipoControl $tipoControlUnoRel = null)
    {
        $this->tipoControlUnoRel = $tipoControlUnoRel;

        return $this;
    }

    /**
     * Get tipoControlUnoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajoTipoControl
     */
    public function getTipoControlUnoRel()
    {
        return $this->tipoControlUnoRel;
    }

    /**
     * Set tipoControlDosRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajoTipoControl $tipoControlDosRel
     *
     * @return RhuAccidenteTrabajo
     */
    public function setTipoControlDosRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajoTipoControl $tipoControlDosRel = null)
    {
        $this->tipoControlDosRel = $tipoControlDosRel;

        return $this;
    }

    /**
     * Get tipoControlDosRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajoTipoControl
     */
    public function getTipoControlDosRel()
    {
        return $this->tipoControlDosRel;
    }

    /**
     * Set tipoControlTresRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajoTipoControl $tipoControlTresRel
     *
     * @return RhuAccidenteTrabajo
     */
    public function setTipoControlTresRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajoTipoControl $tipoControlTresRel = null)
    {
        $this->tipoControlTresRel = $tipoControlTresRel;

        return $this;
    }

    /**
     * Get tipoControlTresRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajoTipoControl
     */
    public function getTipoControlTresRel()
    {
        return $this->tipoControlTresRel;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuAccidenteTrabajo
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
     * Set coordinadorEncargado
     *
     * @param string $coordinadorEncargado
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCoordinadorEncargado($coordinadorEncargado)
    {
        $this->coordinadorEncargado = $coordinadorEncargado;

        return $this;
    }

    /**
     * Get coordinadorEncargado
     *
     * @return string
     */
    public function getCoordinadorEncargado()
    {
        return $this->coordinadorEncargado;
    }

    /**
     * Set cargoCoordinadorEncargado
     *
     * @param string $cargoCoordinadorEncargado
     *
     * @return RhuAccidenteTrabajo
     */
    public function setCargoCoordinadorEncargado($cargoCoordinadorEncargado)
    {
        $this->cargoCoordinadorEncargado = $cargoCoordinadorEncargado;

        return $this;
    }

    /**
     * Get cargoCoordinadorEncargado
     *
     * @return string
     */
    public function getCargoCoordinadorEncargado()
    {
        return $this->cargoCoordinadorEncargado;
    }

    /**
     * Set oficioHabitual
     *
     * @param string $oficioHabitual
     *
     * @return RhuAccidenteTrabajo
     */
    public function setOficioHabitual($oficioHabitual)
    {
        $this->oficioHabitual = $oficioHabitual;

        return $this;
    }

    /**
     * Get oficioHabitual
     *
     * @return string
     */
    public function getOficioHabitual()
    {
        return $this->oficioHabitual;
    }

    /**
     * Set tiempoServicioEmpleado
     *
     * @param string $tiempoServicioEmpleado
     *
     * @return RhuAccidenteTrabajo
     */
    public function setTiempoServicioEmpleado($tiempoServicioEmpleado)
    {
        $this->tiempoServicioEmpleado = $tiempoServicioEmpleado;

        return $this;
    }

    /**
     * Get tiempoServicioEmpleado
     *
     * @return string
     */
    public function getTiempoServicioEmpleado()
    {
        return $this->tiempoServicioEmpleado;
    }

    /**
     * Set tareaDesarrolladamomentoAccidente
     *
     * @param string $tareaDesarrolladamomentoAccidente
     *
     * @return RhuAccidenteTrabajo
     */
    public function setTareaDesarrolladamomentoAccidente($tareaDesarrolladamomentoAccidente)
    {
        $this->tareaDesarrolladamomentoAccidente = $tareaDesarrolladamomentoAccidente;

        return $this;
    }

    /**
     * Get tareaDesarrolladamomentoAccidente
     *
     * @return string
     */
    public function getTareaDesarrolladamomentoAccidente()
    {
        return $this->tareaDesarrolladamomentoAccidente;
    }

    /**
     * Set accidenteOcurrioLugarHabitual
     *
     * @param boolean $accidenteOcurrioLugarHabitual
     *
     * @return RhuAccidenteTrabajo
     */
    public function setAccidenteOcurrioLugarHabitual($accidenteOcurrioLugarHabitual)
    {
        $this->accidenteOcurrioLugarHabitual = $accidenteOcurrioLugarHabitual;

        return $this;
    }

    /**
     * Get accidenteOcurrioLugarHabitual
     *
     * @return boolean
     */
    public function getAccidenteOcurrioLugarHabitual()
    {
        return $this->accidenteOcurrioLugarHabitual;
    }
}
