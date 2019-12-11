<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_puesto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPuestoRepository")
 */
class TurPuesto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_puesto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPuestoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=65)
     */
    private $nombre;      
            
    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */
    private $direccion;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;     
    
    /**
     * @ORM\Column(name="celular", type="string", length=30, nullable=true)
     */
    private $celular;     
    
    /**
     * @ORM\Column(name="contacto", type="string", length=90, nullable=true)
     */
    private $contacto;    

    /**
     * @ORM\Column(name="telefono_contacto", type="string", length=30, nullable=true)
     */
    private $telefonoContacto;    

    /**
     * @ORM\Column(name="celular_contacto", type="string", length=30, nullable=true)
     */
    private $celularContacto;    
    
    /**
     * @ORM\Column(name="costo_dotacion", type="float", nullable=true)
     */    
    private $costoDotacion = 0;     
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;    
    
    /**
     * @ORM\Column(name="codigo_programador_fk", type="integer", nullable=true)
     */    
    private $codigoProgramadorFk;    
    
    /**
     * @ORM\Column(name="codigo_zona_fk", type="integer", nullable=true)
     */    
    private $codigoZonaFk;     
    
    /**
     * @ORM\Column(name="codigo_operacion_fk", type="integer", nullable=true)
     */    
    private $codigoOperacionFk;    
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;   
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=30, nullable=true)
     */
    private $codigoInterface;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_contabilidad_fk", type="string", length=20, nullable=true)
     */    
    private $codigoCentroCostoContabilidadFk;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="puestosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="turPuestosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurProgramador", inversedBy="puestosProgramadorRel")
     * @ORM\JoinColumn(name="codigo_programador_fk", referencedColumnName="codigo_programador_pk")
     */
    protected $programadorRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurZona", inversedBy="puestosZonaRel")
     * @ORM\JoinColumn(name="codigo_zona_fk", referencedColumnName="codigo_zona_pk")
     */
    protected $zonaRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurOperacion", inversedBy="puestosOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    protected $operacionRel;                   
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\ContabilidadBundle\Entity\CtbCentroCosto", inversedBy="turPuestosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_contabilidad_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoContabilidadRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="puestoRel")
     */
    protected $pedidosDetallesPuestoRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleConcepto", mappedBy="puestoRel")
     */
    protected $pedidosDetallesConceptosPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalle", mappedBy="puestoRel")
     */
    protected $serviciosDetallesPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalleConcepto", mappedBy="puestoRel")
     */
    protected $serviciosDetallesConceptosPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionDetalle", mappedBy="puestoRel")
     */
    protected $programacionesDetallesPuestoRel;        

    /**
     * @ORM\OneToMany(targetEntity="TurCostoServicio", mappedBy="puestoRel")
     */
    protected $costosServiciosPuestoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TurPuestoDotacion", mappedBy="puestoRel")
     */
    protected $puestosDotacionesPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurSimulacionDetalle", mappedBy="puestoRel")
     */
    protected $simulacionesDetallesPuestoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalle", mappedBy="puestoRel")
     */
    protected $facturasDetallesPuestoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", mappedBy="puestoRel")
     */
    protected $rhuEmpleadosPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCostoRecursoDetalle", mappedBy="puestoRel")
     */
    protected $costosRecursosDetallesPuestoRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidosDetallesConceptosPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosDetallesConceptosPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->programacionesDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->costosServiciosPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->puestosDotacionesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->simulacionesDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rhuEmpleadosPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->costosRecursosDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPuestoPk
     *
     * @return integer
     */
    public function getCodigoPuestoPk()
    {
        return $this->codigoPuestoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurPuesto
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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return TurPuesto
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return TurPuesto
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set celular
     *
     * @param string $celular
     *
     * @return TurPuesto
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Get celular
     *
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     *
     * @return TurPuesto
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return string
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set telefonoContacto
     *
     * @param string $telefonoContacto
     *
     * @return TurPuesto
     */
    public function setTelefonoContacto($telefonoContacto)
    {
        $this->telefonoContacto = $telefonoContacto;

        return $this;
    }

    /**
     * Get telefonoContacto
     *
     * @return string
     */
    public function getTelefonoContacto()
    {
        return $this->telefonoContacto;
    }

    /**
     * Set celularContacto
     *
     * @param string $celularContacto
     *
     * @return TurPuesto
     */
    public function setCelularContacto($celularContacto)
    {
        $this->celularContacto = $celularContacto;

        return $this;
    }

    /**
     * Get celularContacto
     *
     * @return string
     */
    public function getCelularContacto()
    {
        return $this->celularContacto;
    }

    /**
     * Set costoDotacion
     *
     * @param float $costoDotacion
     *
     * @return TurPuesto
     */
    public function setCostoDotacion($costoDotacion)
    {
        $this->costoDotacion = $costoDotacion;

        return $this;
    }

    /**
     * Get costoDotacion
     *
     * @return float
     */
    public function getCostoDotacion()
    {
        return $this->costoDotacion;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurPuesto
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set codigoProgramadorFk
     *
     * @param integer $codigoProgramadorFk
     *
     * @return TurPuesto
     */
    public function setCodigoProgramadorFk($codigoProgramadorFk)
    {
        $this->codigoProgramadorFk = $codigoProgramadorFk;

        return $this;
    }

    /**
     * Get codigoProgramadorFk
     *
     * @return integer
     */
    public function getCodigoProgramadorFk()
    {
        return $this->codigoProgramadorFk;
    }

    /**
     * Set codigoZonaFk
     *
     * @param integer $codigoZonaFk
     *
     * @return TurPuesto
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
     * Set codigoOperacionFk
     *
     * @param integer $codigoOperacionFk
     *
     * @return TurPuesto
     */
    public function setCodigoOperacionFk($codigoOperacionFk)
    {
        $this->codigoOperacionFk = $codigoOperacionFk;

        return $this;
    }

    /**
     * Get codigoOperacionFk
     *
     * @return integer
     */
    public function getCodigoOperacionFk()
    {
        return $this->codigoOperacionFk;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return TurPuesto
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurPuesto
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
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return TurPuesto
     */
    public function setCodigoInterface($codigoInterface)
    {
        $this->codigoInterface = $codigoInterface;

        return $this;
    }

    /**
     * Get codigoInterface
     *
     * @return string
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * Set codigoCentroCostoContabilidadFk
     *
     * @param string $codigoCentroCostoContabilidadFk
     *
     * @return TurPuesto
     */
    public function setCodigoCentroCostoContabilidadFk($codigoCentroCostoContabilidadFk)
    {
        $this->codigoCentroCostoContabilidadFk = $codigoCentroCostoContabilidadFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoContabilidadFk
     *
     * @return string
     */
    public function getCodigoCentroCostoContabilidadFk()
    {
        return $this->codigoCentroCostoContabilidadFk;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurPuesto
     */
    public function setClienteRel(\Brasa\TurnoBundle\Entity\TurCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return TurPuesto
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
     * Set programadorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramador $programadorRel
     *
     * @return TurPuesto
     */
    public function setProgramadorRel(\Brasa\TurnoBundle\Entity\TurProgramador $programadorRel = null)
    {
        $this->programadorRel = $programadorRel;

        return $this;
    }

    /**
     * Get programadorRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurProgramador
     */
    public function getProgramadorRel()
    {
        return $this->programadorRel;
    }

    /**
     * Set zonaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurZona $zonaRel
     *
     * @return TurPuesto
     */
    public function setZonaRel(\Brasa\TurnoBundle\Entity\TurZona $zonaRel = null)
    {
        $this->zonaRel = $zonaRel;

        return $this;
    }

    /**
     * Get zonaRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurZona
     */
    public function getZonaRel()
    {
        return $this->zonaRel;
    }

    /**
     * Set operacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurOperacion $operacionRel
     *
     * @return TurPuesto
     */
    public function setOperacionRel(\Brasa\TurnoBundle\Entity\TurOperacion $operacionRel = null)
    {
        $this->operacionRel = $operacionRel;

        return $this;
    }

    /**
     * Get operacionRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurOperacion
     */
    public function getOperacionRel()
    {
        return $this->operacionRel;
    }

    /**
     * Set centroCostoContabilidadRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostoContabilidadRel
     *
     * @return TurPuesto
     */
    public function setCentroCostoContabilidadRel(\Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostoContabilidadRel = null)
    {
        $this->centroCostoContabilidadRel = $centroCostoContabilidadRel;

        return $this;
    }

    /**
     * Get centroCostoContabilidadRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbCentroCosto
     */
    public function getCentroCostoContabilidadRel()
    {
        return $this->centroCostoContabilidadRel;
    }

    /**
     * Add pedidosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addPedidosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPuestoRel)
    {
        $this->pedidosDetallesPuestoRel[] = $pedidosDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPuestoRel
     */
    public function removePedidosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPuestoRel)
    {
        $this->pedidosDetallesPuestoRel->removeElement($pedidosDetallesPuestoRel);
    }

    /**
     * Get pedidosDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesPuestoRel()
    {
        return $this->pedidosDetallesPuestoRel;
    }

    /**
     * Add pedidosDetallesConceptosPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosPuestoRel
     *
     * @return TurPuesto
     */
    public function addPedidosDetallesConceptosPuestoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosPuestoRel)
    {
        $this->pedidosDetallesConceptosPuestoRel[] = $pedidosDetallesConceptosPuestoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesConceptosPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosPuestoRel
     */
    public function removePedidosDetallesConceptosPuestoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosPuestoRel)
    {
        $this->pedidosDetallesConceptosPuestoRel->removeElement($pedidosDetallesConceptosPuestoRel);
    }

    /**
     * Get pedidosDetallesConceptosPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesConceptosPuestoRel()
    {
        return $this->pedidosDetallesConceptosPuestoRel;
    }

    /**
     * Add serviciosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addServiciosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPuestoRel)
    {
        $this->serviciosDetallesPuestoRel[] = $serviciosDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPuestoRel
     */
    public function removeServiciosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPuestoRel)
    {
        $this->serviciosDetallesPuestoRel->removeElement($serviciosDetallesPuestoRel);
    }

    /**
     * Get serviciosDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesPuestoRel()
    {
        return $this->serviciosDetallesPuestoRel;
    }

    /**
     * Add serviciosDetallesConceptosPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosPuestoRel
     *
     * @return TurPuesto
     */
    public function addServiciosDetallesConceptosPuestoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosPuestoRel)
    {
        $this->serviciosDetallesConceptosPuestoRel[] = $serviciosDetallesConceptosPuestoRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesConceptosPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosPuestoRel
     */
    public function removeServiciosDetallesConceptosPuestoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosPuestoRel)
    {
        $this->serviciosDetallesConceptosPuestoRel->removeElement($serviciosDetallesConceptosPuestoRel);
    }

    /**
     * Get serviciosDetallesConceptosPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesConceptosPuestoRel()
    {
        return $this->serviciosDetallesConceptosPuestoRel;
    }

    /**
     * Add programacionesDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addProgramacionesDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPuestoRel)
    {
        $this->programacionesDetallesPuestoRel[] = $programacionesDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove programacionesDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPuestoRel
     */
    public function removeProgramacionesDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPuestoRel)
    {
        $this->programacionesDetallesPuestoRel->removeElement($programacionesDetallesPuestoRel);
    }

    /**
     * Get programacionesDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesDetallesPuestoRel()
    {
        return $this->programacionesDetallesPuestoRel;
    }

    /**
     * Add costosServiciosPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPuestoRel
     *
     * @return TurPuesto
     */
    public function addCostosServiciosPuestoRel(\Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPuestoRel)
    {
        $this->costosServiciosPuestoRel[] = $costosServiciosPuestoRel;

        return $this;
    }

    /**
     * Remove costosServiciosPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPuestoRel
     */
    public function removeCostosServiciosPuestoRel(\Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPuestoRel)
    {
        $this->costosServiciosPuestoRel->removeElement($costosServiciosPuestoRel);
    }

    /**
     * Get costosServiciosPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCostosServiciosPuestoRel()
    {
        return $this->costosServiciosPuestoRel;
    }

    /**
     * Add puestosDotacionesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesPuestoRel
     *
     * @return TurPuesto
     */
    public function addPuestosDotacionesPuestoRel(\Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesPuestoRel)
    {
        $this->puestosDotacionesPuestoRel[] = $puestosDotacionesPuestoRel;

        return $this;
    }

    /**
     * Remove puestosDotacionesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesPuestoRel
     */
    public function removePuestosDotacionesPuestoRel(\Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesPuestoRel)
    {
        $this->puestosDotacionesPuestoRel->removeElement($puestosDotacionesPuestoRel);
    }

    /**
     * Get puestosDotacionesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuestosDotacionesPuestoRel()
    {
        return $this->puestosDotacionesPuestoRel;
    }

    /**
     * Add simulacionesDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSimulacionDetalle $simulacionesDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addSimulacionesDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurSimulacionDetalle $simulacionesDetallesPuestoRel)
    {
        $this->simulacionesDetallesPuestoRel[] = $simulacionesDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove simulacionesDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSimulacionDetalle $simulacionesDetallesPuestoRel
     */
    public function removeSimulacionesDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurSimulacionDetalle $simulacionesDetallesPuestoRel)
    {
        $this->simulacionesDetallesPuestoRel->removeElement($simulacionesDetallesPuestoRel);
    }

    /**
     * Get simulacionesDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSimulacionesDetallesPuestoRel()
    {
        return $this->simulacionesDetallesPuestoRel;
    }

    /**
     * Add facturasDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addFacturasDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPuestoRel)
    {
        $this->facturasDetallesPuestoRel[] = $facturasDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove facturasDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPuestoRel
     */
    public function removeFacturasDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPuestoRel)
    {
        $this->facturasDetallesPuestoRel->removeElement($facturasDetallesPuestoRel);
    }

    /**
     * Get facturasDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesPuestoRel()
    {
        return $this->facturasDetallesPuestoRel;
    }

    /**
     * Add rhuEmpleadosPuestoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosPuestoRel
     *
     * @return TurPuesto
     */
    public function addRhuEmpleadosPuestoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosPuestoRel)
    {
        $this->rhuEmpleadosPuestoRel[] = $rhuEmpleadosPuestoRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadosPuestoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosPuestoRel
     */
    public function removeRhuEmpleadosPuestoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosPuestoRel)
    {
        $this->rhuEmpleadosPuestoRel->removeElement($rhuEmpleadosPuestoRel);
    }

    /**
     * Get rhuEmpleadosPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadosPuestoRel()
    {
        return $this->rhuEmpleadosPuestoRel;
    }

    /**
     * Add costosRecursosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoRecursoDetalle $costosRecursosDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addCostosRecursosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurCostoRecursoDetalle $costosRecursosDetallesPuestoRel)
    {
        $this->costosRecursosDetallesPuestoRel[] = $costosRecursosDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove costosRecursosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoRecursoDetalle $costosRecursosDetallesPuestoRel
     */
    public function removeCostosRecursosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurCostoRecursoDetalle $costosRecursosDetallesPuestoRel)
    {
        $this->costosRecursosDetallesPuestoRel->removeElement($costosRecursosDetallesPuestoRel);
    }

    /**
     * Get costosRecursosDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCostosRecursosDetallesPuestoRel()
    {
        return $this->costosRecursosDetallesPuestoRel;
    }
}
