<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_requisito")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionRequisitoRepository")
 */
class RhuSeleccionRequisito
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_requisito_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionRequisitoPk;        
    
    /**
     * @ORM\Column(name="fecha", type="date")
     */ 
    
    private $fecha;                   
    
    /**     
     * @ORM\Column(name="nombre", type="string")
     */    
    
    private $nombre;           
                
    /**     
     * @ORM\Column(name="cantidad_solicitada", type="integer")
     */    
    private $cantidadSolicitada;
    
    /**
     * @ORM\Column(name="fecha_pruebas", type="datetime", nullable=true)
     */ 
    
    private $fechaPruebas;                     
    
    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */
    private $estadoCerrado = false;     
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk;
    
    /**
     * @ORM\Column(name="edad_minima", type="string", length=20, nullable=true)
     */
    private $edadMinima;
    
    /**
     * @ORM\Column(name="edad_maxima", type="string", length=20, nullable=true)
     */
    private $edadMaxima;
    
    /**
     * @ORM\Column(name="numero_hijos", type="integer", nullable=true)
     */
    private $numeroHijos;
    
    /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=1, nullable=true)
     */
    private $codigoEstadoCivilFk;
    
    /**
     * @ORM\Column(name="codigo_estudio_tipo_fk", type="integer", length=4, nullable=true)
     */    
    private $codigoEstudioTipoFk;
    
    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */
    private $codigoSexoFk;
    
    /**
     * @ORM\Column(name="codigo_religion_fk", type="string", length=20, nullable=true)
     */
    private $codigoReligionFk;
    
    /**
     * @ORM\Column(name="codigo_experiencia_requisicion_fk", type="integer", nullable=true)
     */
    private $codigoExperienciaRequisicionFk;
    
    /**
     * @ORM\Column(name="codigo_disponibilidad_fk", type="string", length=30, nullable=true)
     */
    private $codigoDisponibilidadFk;
    
    /**
     * @ORM\Column(name="codigo_tipo_vehiculo_fk", type="string", length=2, nullable=true)
     */
    private $codigoTipoVehiculoFk;
    
    /**
     * @ORM\Column(name="codigo_licencia_carro_fk", type="string", length=30, nullable=true)
     */
    private $codigoLicenciaCarroFk;
    
    /**
     * @ORM\Column(name="codigo_licencia_moto_fk", type="string", length=30, nullable=true)
     */
    private $codigoLicenciaMotoFk;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;
    
    /**
     * @ORM\Column(name="codigo_zona_fk", type="integer", nullable=true)
     */    
    private $codigoZonaFk;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="seleccionesRequisitosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="seleccionesRequisitosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccionRequisicionExperiencia", inversedBy="seleccionesRequisitosSeleccionRequisicionExperienciaRel")
     * @ORM\JoinColumn(name="codigo_experiencia_requisicion_fk", referencedColumnName="codigo_experiencia_requisicion_pk")
     */
    protected $experienciaRequisicionRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEstadoCivil", inversedBy="seleccionesRequisitosEstadoCivilRel")
     * @ORM\JoinColumn(name="codigo_estado_civil_fk", referencedColumnName="codigo_estado_civil_pk")
     */
    protected $estadoCivilRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuSeleccionesRequisitosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleadoEstudioTipo", inversedBy="seleccionesRequisitosEmpleadoEstudioTipoRel")
     * @ORM\JoinColumn(name="codigo_estudio_tipo_fk", referencedColumnName="codigo_empleado_estudio_tipo_pk")
     */
    protected $estudioTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuZona", inversedBy="seleccionesRequisitosZonaRel")
     * @ORM\JoinColumn(name="codigo_zona_fk", referencedColumnName="codigo_zona_pk")
     */
    protected $zonaRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="seleccionRequisitoRel")
     */
    protected $seleccionesSeleccionRequisitoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionRequisicionAspirante", mappedBy="seleccionRequisitoRel")
     */
    protected $seleccionesRequisicionesAspirantesSeleccionRequisitoRel;
    

    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionesSeleccionRequisitoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesRequisicionesAspirantesSeleccionRequisitoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSeleccionRequisitoPk
     *
     * @return integer
     */
    public function getCodigoSeleccionRequisitoPk()
    {
        return $this->codigoSeleccionRequisitoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuSeleccionRequisito
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSeleccionRequisito
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
     * Set cantidadSolicitada
     *
     * @param integer $cantidadSolicitada
     *
     * @return RhuSeleccionRequisito
     */
    public function setCantidadSolicitada($cantidadSolicitada)
    {
        $this->cantidadSolicitada = $cantidadSolicitada;

        return $this;
    }

    /**
     * Get cantidadSolicitada
     *
     * @return integer
     */
    public function getCantidadSolicitada()
    {
        return $this->cantidadSolicitada;
    }

    /**
     * Set fechaPruebas
     *
     * @param \DateTime $fechaPruebas
     *
     * @return RhuSeleccionRequisito
     */
    public function setFechaPruebas($fechaPruebas)
    {
        $this->fechaPruebas = $fechaPruebas;

        return $this;
    }

    /**
     * Get fechaPruebas
     *
     * @return \DateTime
     */
    public function getFechaPruebas()
    {
        return $this->fechaPruebas;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuSeleccionRequisito
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuSeleccionRequisito
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
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoCiudadFk($codigoCiudadFk)
    {
        $this->codigoCiudadFk = $codigoCiudadFk;

        return $this;
    }

    /**
     * Get codigoCiudadFk
     *
     * @return integer
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuSeleccionRequisito
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
     * Set edadMinima
     *
     * @param string $edadMinima
     *
     * @return RhuSeleccionRequisito
     */
    public function setEdadMinima($edadMinima)
    {
        $this->edadMinima = $edadMinima;

        return $this;
    }

    /**
     * Get edadMinima
     *
     * @return string
     */
    public function getEdadMinima()
    {
        return $this->edadMinima;
    }

    /**
     * Set edadMaxima
     *
     * @param string $edadMaxima
     *
     * @return RhuSeleccionRequisito
     */
    public function setEdadMaxima($edadMaxima)
    {
        $this->edadMaxima = $edadMaxima;

        return $this;
    }

    /**
     * Get edadMaxima
     *
     * @return string
     */
    public function getEdadMaxima()
    {
        return $this->edadMaxima;
    }

    /**
     * Set numeroHijos
     *
     * @param integer $numeroHijos
     *
     * @return RhuSeleccionRequisito
     */
    public function setNumeroHijos($numeroHijos)
    {
        $this->numeroHijos = $numeroHijos;

        return $this;
    }

    /**
     * Get numeroHijos
     *
     * @return integer
     */
    public function getNumeroHijos()
    {
        return $this->numeroHijos;
    }

    /**
     * Set codigoEstadoCivilFk
     *
     * @param string $codigoEstadoCivilFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoEstadoCivilFk($codigoEstadoCivilFk)
    {
        $this->codigoEstadoCivilFk = $codigoEstadoCivilFk;

        return $this;
    }

    /**
     * Get codigoEstadoCivilFk
     *
     * @return string
     */
    public function getCodigoEstadoCivilFk()
    {
        return $this->codigoEstadoCivilFk;
    }

    /**
     * Set codigoEstudioTipoFk
     *
     * @param integer $codigoEstudioTipoFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoEstudioTipoFk($codigoEstudioTipoFk)
    {
        $this->codigoEstudioTipoFk = $codigoEstudioTipoFk;

        return $this;
    }

    /**
     * Get codigoEstudioTipoFk
     *
     * @return integer
     */
    public function getCodigoEstudioTipoFk()
    {
        return $this->codigoEstudioTipoFk;
    }

    /**
     * Set codigoSexoFk
     *
     * @param string $codigoSexoFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoSexoFk($codigoSexoFk)
    {
        $this->codigoSexoFk = $codigoSexoFk;

        return $this;
    }

    /**
     * Get codigoSexoFk
     *
     * @return string
     */
    public function getCodigoSexoFk()
    {
        return $this->codigoSexoFk;
    }

    /**
     * Set codigoReligionFk
     *
     * @param string $codigoReligionFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoReligionFk($codigoReligionFk)
    {
        $this->codigoReligionFk = $codigoReligionFk;

        return $this;
    }

    /**
     * Get codigoReligionFk
     *
     * @return string
     */
    public function getCodigoReligionFk()
    {
        return $this->codigoReligionFk;
    }

    /**
     * Set codigoExperienciaRequisicionFk
     *
     * @param integer $codigoExperienciaRequisicionFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoExperienciaRequisicionFk($codigoExperienciaRequisicionFk)
    {
        $this->codigoExperienciaRequisicionFk = $codigoExperienciaRequisicionFk;

        return $this;
    }

    /**
     * Get codigoExperienciaRequisicionFk
     *
     * @return integer
     */
    public function getCodigoExperienciaRequisicionFk()
    {
        return $this->codigoExperienciaRequisicionFk;
    }

    /**
     * Set codigoDisponibilidadFk
     *
     * @param string $codigoDisponibilidadFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoDisponibilidadFk($codigoDisponibilidadFk)
    {
        $this->codigoDisponibilidadFk = $codigoDisponibilidadFk;

        return $this;
    }

    /**
     * Get codigoDisponibilidadFk
     *
     * @return string
     */
    public function getCodigoDisponibilidadFk()
    {
        return $this->codigoDisponibilidadFk;
    }

    /**
     * Set codigoTipoVehiculoFk
     *
     * @param string $codigoTipoVehiculoFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoTipoVehiculoFk($codigoTipoVehiculoFk)
    {
        $this->codigoTipoVehiculoFk = $codigoTipoVehiculoFk;

        return $this;
    }

    /**
     * Get codigoTipoVehiculoFk
     *
     * @return string
     */
    public function getCodigoTipoVehiculoFk()
    {
        return $this->codigoTipoVehiculoFk;
    }

    /**
     * Set codigoLicenciaCarroFk
     *
     * @param string $codigoLicenciaCarroFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoLicenciaCarroFk($codigoLicenciaCarroFk)
    {
        $this->codigoLicenciaCarroFk = $codigoLicenciaCarroFk;

        return $this;
    }

    /**
     * Get codigoLicenciaCarroFk
     *
     * @return string
     */
    public function getCodigoLicenciaCarroFk()
    {
        return $this->codigoLicenciaCarroFk;
    }

    /**
     * Set codigoLicenciaMotoFk
     *
     * @param string $codigoLicenciaMotoFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoLicenciaMotoFk($codigoLicenciaMotoFk)
    {
        $this->codigoLicenciaMotoFk = $codigoLicenciaMotoFk;

        return $this;
    }

    /**
     * Get codigoLicenciaMotoFk
     *
     * @return string
     */
    public function getCodigoLicenciaMotoFk()
    {
        return $this->codigoLicenciaMotoFk;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuSeleccionRequisito
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
     * Set codigoZonaFk
     *
     * @param integer $codigoZonaFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoZonaFk($codigoZonaFk)
    {
        $this->codigoZonaFk = $codigoZonaFk;

        return $this;
    }

    /**
     * Get codigoZonaFk
     *
     * @return integer
     */
    public function getCodigoZonaFk()
    {
        return $this->codigoZonaFk;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuSeleccionRequisito
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
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuSeleccionRequisito
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
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuSeleccionRequisito
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
     * Set experienciaRequisicionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionExperiencia $experienciaRequisicionRel
     *
     * @return RhuSeleccionRequisito
     */
    public function setExperienciaRequisicionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionExperiencia $experienciaRequisicionRel = null)
    {
        $this->experienciaRequisicionRel = $experienciaRequisicionRel;

        return $this;
    }

    /**
     * Get experienciaRequisicionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionExperiencia
     */
    public function getExperienciaRequisicionRel()
    {
        return $this->experienciaRequisicionRel;
    }

    /**
     * Set estadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil $estadoCivilRel
     *
     * @return RhuSeleccionRequisito
     */
    public function setEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil $estadoCivilRel = null)
    {
        $this->estadoCivilRel = $estadoCivilRel;

        return $this;
    }

    /**
     * Get estadoCivilRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil
     */
    public function getEstadoCivilRel()
    {
        return $this->estadoCivilRel;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuSeleccionRequisito
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
     * Set estudioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo $estudioTipoRel
     *
     * @return RhuSeleccionRequisito
     */
    public function setEstudioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo $estudioTipoRel = null)
    {
        $this->estudioTipoRel = $estudioTipoRel;

        return $this;
    }

    /**
     * Get estudioTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo
     */
    public function getEstudioTipoRel()
    {
        return $this->estudioTipoRel;
    }

    /**
     * Set zonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuZona $zonaRel
     *
     * @return RhuSeleccionRequisito
     */
    public function setZonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuZona $zonaRel = null)
    {
        $this->zonaRel = $zonaRel;

        return $this;
    }

    /**
     * Get zonaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuZona
     */
    public function getZonaRel()
    {
        return $this->zonaRel;
    }

    /**
     * Add seleccionesSeleccionRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionRequisitoRel
     *
     * @return RhuSeleccionRequisito
     */
    public function addSeleccionesSeleccionRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionRequisitoRel)
    {
        $this->seleccionesSeleccionRequisitoRel[] = $seleccionesSeleccionRequisitoRel;

        return $this;
    }

    /**
     * Remove seleccionesSeleccionRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionRequisitoRel
     */
    public function removeSeleccionesSeleccionRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionRequisitoRel)
    {
        $this->seleccionesSeleccionRequisitoRel->removeElement($seleccionesSeleccionRequisitoRel);
    }

    /**
     * Get seleccionesSeleccionRequisitoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesSeleccionRequisitoRel()
    {
        return $this->seleccionesSeleccionRequisitoRel;
    }

    /**
     * Add seleccionesRequisicionesAspirantesSeleccionRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante $seleccionesRequisicionesAspirantesSeleccionRequisitoRel
     *
     * @return RhuSeleccionRequisito
     */
    public function addSeleccionesRequisicionesAspirantesSeleccionRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante $seleccionesRequisicionesAspirantesSeleccionRequisitoRel)
    {
        $this->seleccionesRequisicionesAspirantesSeleccionRequisitoRel[] = $seleccionesRequisicionesAspirantesSeleccionRequisitoRel;

        return $this;
    }

    /**
     * Remove seleccionesRequisicionesAspirantesSeleccionRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante $seleccionesRequisicionesAspirantesSeleccionRequisitoRel
     */
    public function removeSeleccionesRequisicionesAspirantesSeleccionRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante $seleccionesRequisicionesAspirantesSeleccionRequisitoRel)
    {
        $this->seleccionesRequisicionesAspirantesSeleccionRequisitoRel->removeElement($seleccionesRequisicionesAspirantesSeleccionRequisitoRel);
    }

    /**
     * Get seleccionesRequisicionesAspirantesSeleccionRequisitoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesRequisicionesAspirantesSeleccionRequisitoRel()
    {
        return $this->seleccionesRequisicionesAspirantesSeleccionRequisitoRel;
    }
}
