<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_incapacidad")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuIncapacidadRepository")
 */
class RhuIncapacidad
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_incapacidad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIncapacidadPk;                    
    
    /**
     * @ORM\Column(name="numero", type="integer")
     */    
    private $numero = 0;     
    
    /**
     * @ORM\Column(name="fecha", type="date")
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="fecha_desde", type="date")
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date")
     */    
    private $fechaHasta;    
    
    /**
     * @ORM\Column(name="numero_eps", type="string", length=30, nullable=true)
     */    
    private $numeroEps;     
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk; 
    
    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */    
    private $codigoContratoFk;    
    
    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadSaludFk;
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad = 0;                       

    /**
     * @ORM\Column(name="dias_cobro", type="integer")
     */
    private $diasCobro = 0;    
    
    /**
     * @ORM\Column(name="vr_cobro", type="float")
     */
    private $vrCobro = 0;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;         
    
    /**
     * @ORM\Column(name="codigo_incapacidad_diagnostico_fk", type="integer", nullable=true)
     */    
    private $codigoIncapacidadDiagnosticoFk;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\Column(name="codigo_incapacidad_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoIncapacidadTipoFk;
    
    /**     
     * @ORM\Column(name="estado_transcripcion", type="boolean")
     */    
    private $estadoTranscripcion = 0;
    
    /**     
     * @ORM\Column(name="estado_cobrar", type="boolean")
     */    
    private $estadoCobrar = 0;
    
    /**     
     * @ORM\Column(name="estado_prorroga", type="boolean")
     */    
    private $estadoProrroga = 0;
    
    /**
     * @ORM\Column(name="vr_incapacidad", type="float")
     */
    private $vrIncapacidad = 0;
    
    /**
     * @ORM\Column(name="vr_pagado", type="float")
     */
    private $vrPagado = 0;
    
    /**
     * @ORM\Column(name="vr_saldo", type="float")
     */
    private $vrSaldo = 0;
         
    /**
     * @ORM\Column(name="porcentaje_pago", type="float")
     */
    private $porcentajePago = 0;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**     
     * @ORM\Column(name="estado_legalizado", type="boolean")
     */    
    private $estadoLegalizado = false;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuIncapacidadTipo", inversedBy="incapacidadesIncapacidadTipoRel")
     * @ORM\JoinColumn(name="codigo_incapacidad_tipo_fk", referencedColumnName="codigo_incapacidad_tipo_pk")
     */
    protected $incapacidadTipoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="incapacidadesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadSalud", inversedBy="incapacidadesEntidadSaludRel")
     * @ORM\JoinColumn(name="codigo_entidad_salud_fk", referencedColumnName="codigo_entidad_salud_pk")
     */
    protected $entidadSaludRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="incapacidadesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="incapacidadesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuIncapacidadDiagnostico", inversedBy="incapacidadesIncapacidadDiagnosticoRel")
     * @ORM\JoinColumn(name="codigo_incapacidad_diagnostico_fk", referencedColumnName="codigo_incapacidad_diagnostico_pk")
     */
    protected $incapacidadDiagnosticoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidadPagoDetalle", mappedBy="incapacidadRel")
     */
    protected $incapacidadesIncapacidadPagoRel;
      
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosDetallesIncapacidadRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incapacidadesIncapacidadPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoIncapacidadPk
     *
     * @return integer
     */
    public function getCodigoIncapacidadPk()
    {
        return $this->codigoIncapacidadPk;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return RhuIncapacidad
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuIncapacidad
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuIncapacidad
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
     * @return RhuIncapacidad
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
     * Set numeroEps
     *
     * @param string $numeroEps
     *
     * @return RhuIncapacidad
     */
    public function setNumeroEps($numeroEps)
    {
        $this->numeroEps = $numeroEps;

        return $this;
    }

    /**
     * Get numeroEps
     *
     * @return string
     */
    public function getNumeroEps()
    {
        return $this->numeroEps;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuIncapacidad
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
     * Set codigoEntidadSaludFk
     *
     * @param integer $codigoEntidadSaludFk
     *
     * @return RhuIncapacidad
     */
    public function setCodigoEntidadSaludFk($codigoEntidadSaludFk)
    {
        $this->codigoEntidadSaludFk = $codigoEntidadSaludFk;

        return $this;
    }

    /**
     * Get codigoEntidadSaludFk
     *
     * @return integer
     */
    public function getCodigoEntidadSaludFk()
    {
        return $this->codigoEntidadSaludFk;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return RhuIncapacidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuIncapacidad
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
     * Set codigoIncapacidadDiagnosticoFk
     *
     * @param integer $codigoIncapacidadDiagnosticoFk
     *
     * @return RhuIncapacidad
     */
    public function setCodigoIncapacidadDiagnosticoFk($codigoIncapacidadDiagnosticoFk)
    {
        $this->codigoIncapacidadDiagnosticoFk = $codigoIncapacidadDiagnosticoFk;

        return $this;
    }

    /**
     * Get codigoIncapacidadDiagnosticoFk
     *
     * @return integer
     */
    public function getCodigoIncapacidadDiagnosticoFk()
    {
        return $this->codigoIncapacidadDiagnosticoFk;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuIncapacidad
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
     * Set codigoIncapacidadTipoFk
     *
     * @param integer $codigoIncapacidadTipoFk
     *
     * @return RhuIncapacidad
     */
    public function setCodigoIncapacidadTipoFk($codigoIncapacidadTipoFk)
    {
        $this->codigoIncapacidadTipoFk = $codigoIncapacidadTipoFk;

        return $this;
    }

    /**
     * Get codigoIncapacidadTipoFk
     *
     * @return integer
     */
    public function getCodigoIncapacidadTipoFk()
    {
        return $this->codigoIncapacidadTipoFk;
    }

    /**
     * Set estadoTranscripcion
     *
     * @param boolean $estadoTranscripcion
     *
     * @return RhuIncapacidad
     */
    public function setEstadoTranscripcion($estadoTranscripcion)
    {
        $this->estadoTranscripcion = $estadoTranscripcion;

        return $this;
    }

    /**
     * Get estadoTranscripcion
     *
     * @return boolean
     */
    public function getEstadoTranscripcion()
    {
        return $this->estadoTranscripcion;
    }

    /**
     * Set estadoCobrar
     *
     * @param boolean $estadoCobrar
     *
     * @return RhuIncapacidad
     */
    public function setEstadoCobrar($estadoCobrar)
    {
        $this->estadoCobrar = $estadoCobrar;

        return $this;
    }

    /**
     * Get estadoCobrar
     *
     * @return boolean
     */
    public function getEstadoCobrar()
    {
        return $this->estadoCobrar;
    }

    /**
     * Set estadoProrroga
     *
     * @param boolean $estadoProrroga
     *
     * @return RhuIncapacidad
     */
    public function setEstadoProrroga($estadoProrroga)
    {
        $this->estadoProrroga = $estadoProrroga;

        return $this;
    }

    /**
     * Get estadoProrroga
     *
     * @return boolean
     */
    public function getEstadoProrroga()
    {
        return $this->estadoProrroga;
    }

    /**
     * Set vrIncapacidad
     *
     * @param float $vrIncapacidad
     *
     * @return RhuIncapacidad
     */
    public function setVrIncapacidad($vrIncapacidad)
    {
        $this->vrIncapacidad = $vrIncapacidad;

        return $this;
    }

    /**
     * Get vrIncapacidad
     *
     * @return float
     */
    public function getVrIncapacidad()
    {
        return $this->vrIncapacidad;
    }

    /**
     * Set vrPagado
     *
     * @param float $vrPagado
     *
     * @return RhuIncapacidad
     */
    public function setVrPagado($vrPagado)
    {
        $this->vrPagado = $vrPagado;

        return $this;
    }

    /**
     * Get vrPagado
     *
     * @return float
     */
    public function getVrPagado()
    {
        return $this->vrPagado;
    }

    /**
     * Set vrSaldo
     *
     * @param float $vrSaldo
     *
     * @return RhuIncapacidad
     */
    public function setVrSaldo($vrSaldo)
    {
        $this->vrSaldo = $vrSaldo;

        return $this;
    }

    /**
     * Get vrSaldo
     *
     * @return float
     */
    public function getVrSaldo()
    {
        return $this->vrSaldo;
    }

    /**
     * Set porcentajePago
     *
     * @param float $porcentajePago
     *
     * @return RhuIncapacidad
     */
    public function setPorcentajePago($porcentajePago)
    {
        $this->porcentajePago = $porcentajePago;

        return $this;
    }

    /**
     * Get porcentajePago
     *
     * @return float
     */
    public function getPorcentajePago()
    {
        return $this->porcentajePago;
    }

    /**
     * Set incapacidadTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadTipo $incapacidadTipoRel
     *
     * @return RhuIncapacidad
     */
    public function setIncapacidadTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadTipo $incapacidadTipoRel = null)
    {
        $this->incapacidadTipoRel = $incapacidadTipoRel;

        return $this;
    }

    /**
     * Get incapacidadTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadTipo
     */
    public function getIncapacidadTipoRel()
    {
        return $this->incapacidadTipoRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuIncapacidad
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
     * Set entidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludRel
     *
     * @return RhuIncapacidad
     */
    public function setEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludRel = null)
    {
        $this->entidadSaludRel = $entidadSaludRel;

        return $this;
    }

    /**
     * Get entidadSaludRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud
     */
    public function getEntidadSaludRel()
    {
        return $this->entidadSaludRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuIncapacidad
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
     * Set incapacidadDiagnosticoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadDiagnostico $incapacidadDiagnosticoRel
     *
     * @return RhuIncapacidad
     */
    public function setIncapacidadDiagnosticoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadDiagnostico $incapacidadDiagnosticoRel = null)
    {
        $this->incapacidadDiagnosticoRel = $incapacidadDiagnosticoRel;

        return $this;
    }

    /**
     * Get incapacidadDiagnosticoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadDiagnostico
     */
    public function getIncapacidadDiagnosticoRel()
    {
        return $this->incapacidadDiagnosticoRel;
    }

    /**
     * Add pagosDetallesIncapacidadRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesIncapacidadRel
     *
     * @return RhuIncapacidad
     */
    public function addPagosDetallesIncapacidadRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesIncapacidadRel)
    {
        $this->pagosDetallesIncapacidadRel[] = $pagosDetallesIncapacidadRel;

        return $this;
    }

    /**
     * Remove pagosDetallesIncapacidadRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesIncapacidadRel
     */
    public function removePagosDetallesIncapacidadRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesIncapacidadRel)
    {
        $this->pagosDetallesIncapacidadRel->removeElement($pagosDetallesIncapacidadRel);
    }

    /**
     * Get pagosDetallesIncapacidadRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosDetallesIncapacidadRel()
    {
        return $this->pagosDetallesIncapacidadRel;
    }

    /**
     * Add incapacidadesIncapacidadPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPagoDetalle $incapacidadesIncapacidadPagoRel
     *
     * @return RhuIncapacidad
     */
    public function addIncapacidadesIncapacidadPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPagoDetalle $incapacidadesIncapacidadPagoRel)
    {
        $this->incapacidadesIncapacidadPagoRel[] = $incapacidadesIncapacidadPagoRel;

        return $this;
    }

    /**
     * Remove incapacidadesIncapacidadPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPagoDetalle $incapacidadesIncapacidadPagoRel
     */
    public function removeIncapacidadesIncapacidadPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPagoDetalle $incapacidadesIncapacidadPagoRel)
    {
        $this->incapacidadesIncapacidadPagoRel->removeElement($incapacidadesIncapacidadPagoRel);
    }

    /**
     * Get incapacidadesIncapacidadPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesIncapacidadPagoRel()
    {
        return $this->incapacidadesIncapacidadPagoRel;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuIncapacidad
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
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuIncapacidad
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
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuIncapacidad
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
     * Set estadoLegalizado
     *
     * @param boolean $estadoLegalizado
     *
     * @return RhuIncapacidad
     */
    public function setEstadoLegalizado($estadoLegalizado)
    {
        $this->estadoLegalizado = $estadoLegalizado;

        return $this;
    }

    /**
     * Get estadoLegalizado
     *
     * @return boolean
     */
    public function getEstadoLegalizado()
    {
        return $this->estadoLegalizado;
    }

    /**
     * Set diasCobrados
     *
     * @param integer $diasCobrados
     *
     * @return RhuIncapacidad
     */
    public function setDiasCobrados($diasCobrados)
    {
        $this->diasCobrados = $diasCobrados;

        return $this;
    }

    /**
     * Get diasCobrados
     *
     * @return integer
     */
    public function getDiasCobrados()
    {
        return $this->diasCobrados;
    }

    /**
     * Set diasCobro
     *
     * @param integer $diasCobro
     *
     * @return RhuIncapacidad
     */
    public function setDiasCobro($diasCobro)
    {
        $this->diasCobro = $diasCobro;

        return $this;
    }

    /**
     * Get diasCobro
     *
     * @return integer
     */
    public function getDiasCobro()
    {
        return $this->diasCobro;
    }

    /**
     * Set vrCobro
     *
     * @param float $vrCobro
     *
     * @return RhuIncapacidad
     */
    public function setVrCobro($vrCobro)
    {
        $this->vrCobro = $vrCobro;

        return $this;
    }

    /**
     * Get vrCobro
     *
     * @return float
     */
    public function getVrCobro()
    {
        return $this->vrCobro;
    }
}
