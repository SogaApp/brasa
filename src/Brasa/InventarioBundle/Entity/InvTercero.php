<?php

namespace Brasa\InventarioBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_tercero")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvTerceroRepository")
 */
class InvTercero
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tercero_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTerceroPk;

    /**
     * @ORM\Column(name="nit", type="string", length=11)
     */
    private $nit;    
    
    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=1, nullable=true)
     */
    private $digitoVerificacion;     
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=50)
     */
    private $nombreCorto;   
    
    /**
     * @ORM\Column(name="nombres", type="string", length=50, nullable=true)
     */
    private $nombres;

     /**
     * @ORM\Column(name="apellido1", type="string", length=50, nullable=true)
     */   
    private $apellido1;

    /**
     * @ORM\Column(name="apellido2", type="string", length=50, nullable=true)
     */
    private $apellido2;

    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */    
    private $codigoAsesorFk;    
    
    /**
     * @ORM\Column(name="codigo_lista_precio_fk", type="integer", nullable=true)
     */    
    private $codigoListaPrecioFk;

    /**
     * @ORM\Column(name="codigo_lista_costo_fk", type="integer", nullable=true)
     */    
    private $codigoListaCostoFk;    

    /**
     * @ORM\Column(name="codigo_clasificacion_tributaria_fk", type="integer", nullable=true)
     */    
    private $codigoClasificacionTributariaFk;    

    /**
     * @ORM\Column(name="codigo_forma_pago_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoFormaPagoClienteFk;        

    /**
     * @ORM\Column(name="plazo_pago", type="integer")
     */    
    private $plazoPagoCliente = 0;
    
    /**
     * @ORM\Column(name="codigo_forma_pago_proveedor_fk", type="integer", nullable=true)
     */    
    private $codigoFormaPagoProveedorFk;    
    
    /**
     * @ORM\Column(name="plazo_pago_proveedor", type="integer")
     */    
    private $plazoPagoProveedor = 0;    
    
    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */
    private $direccion;    

    /**
     * @ORM\Column(name="telefono", type="string", length=20, nullable=true)
     */
    private $telefono;    

    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */
    private $celular;    
        
    /**
     * @ORM\Column(name="fax", type="string", length=20, nullable=true)
     */
    private $fax;    
    
    /**
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;    

    /**
     * @ORM\Column(name="retencion_fuente_ventas", type="boolean", nullable=true)
     */    
    private $retencionFuenteVentas;    

    /**
     * @ORM\Column(name="retencion_fuente_ventas_sin_base", type="boolean", nullable=true)
     */    
    private $retencionFuenteVentasSinBase;    
    
    
    /**
     * @ORM\Column(name="autoretenedor", type="boolean", nullable=true)
     */    
    private $autoretenedor ;    

    /**
     * @ORM\Column(name="contacto_cliente", type="string", length=80, nullable=true)
     */
    private $contactoCliente;    

    /**
     * @ORM\Column(name="calular_contacto_cliente", type="string", length=20, nullable=true)
     */
    private $celularContactoCliente;        
    
    /**
     * @ORM\Column(name="contacto_proveedor", type="string", length=80, nullable=true)
     */
    private $contactoProveedor;    

    /**
     * @ORM\Column(name="celular_contacto_proveedor", type="string", length=20, nullable=true)
     */
    private $celularContactoProveedor;    
    
    /**
     * @ORM\Column(name="codigo_actividad_economica", type="integer", nullable=true)
     */
    private $codigoActividadEconomica;    
    
    /**
     * @ORM\Column(name="porcentaje_cree", type="float")
     */
    private $porcentajeCREE = 0;                                    
                          
    /**
     * @ORM\OneToMany(targetEntity="InvMovimiento", mappedBy="terceroRel")
     */
    protected $movimientosTerceroRel;          
  
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientosTerceroRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoTerceroPk
     *
     * @return integer
     */
    public function getCodigoTerceroPk()
    {
        return $this->codigoTerceroPk;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return InvTercero
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set digitoVerificacion
     *
     * @param string $digitoVerificacion
     *
     * @return InvTercero
     */
    public function setDigitoVerificacion($digitoVerificacion)
    {
        $this->digitoVerificacion = $digitoVerificacion;

        return $this;
    }

    /**
     * Get digitoVerificacion
     *
     * @return string
     */
    public function getDigitoVerificacion()
    {
        return $this->digitoVerificacion;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return InvTercero
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     *
     * @return InvTercero
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set apellido1
     *
     * @param string $apellido1
     *
     * @return InvTercero
     */
    public function setApellido1($apellido1)
    {
        $this->apellido1 = $apellido1;

        return $this;
    }

    /**
     * Get apellido1
     *
     * @return string
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * Set apellido2
     *
     * @param string $apellido2
     *
     * @return InvTercero
     */
    public function setApellido2($apellido2)
    {
        $this->apellido2 = $apellido2;

        return $this;
    }

    /**
     * Get apellido2
     *
     * @return string
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * Set codigoAsesorFk
     *
     * @param integer $codigoAsesorFk
     *
     * @return InvTercero
     */
    public function setCodigoAsesorFk($codigoAsesorFk)
    {
        $this->codigoAsesorFk = $codigoAsesorFk;

        return $this;
    }

    /**
     * Get codigoAsesorFk
     *
     * @return integer
     */
    public function getCodigoAsesorFk()
    {
        return $this->codigoAsesorFk;
    }

    /**
     * Set codigoListaPrecioFk
     *
     * @param integer $codigoListaPrecioFk
     *
     * @return InvTercero
     */
    public function setCodigoListaPrecioFk($codigoListaPrecioFk)
    {
        $this->codigoListaPrecioFk = $codigoListaPrecioFk;

        return $this;
    }

    /**
     * Get codigoListaPrecioFk
     *
     * @return integer
     */
    public function getCodigoListaPrecioFk()
    {
        return $this->codigoListaPrecioFk;
    }

    /**
     * Set codigoListaCostoFk
     *
     * @param integer $codigoListaCostoFk
     *
     * @return InvTercero
     */
    public function setCodigoListaCostoFk($codigoListaCostoFk)
    {
        $this->codigoListaCostoFk = $codigoListaCostoFk;

        return $this;
    }

    /**
     * Get codigoListaCostoFk
     *
     * @return integer
     */
    public function getCodigoListaCostoFk()
    {
        return $this->codigoListaCostoFk;
    }

    /**
     * Set codigoClasificacionTributariaFk
     *
     * @param integer $codigoClasificacionTributariaFk
     *
     * @return InvTercero
     */
    public function setCodigoClasificacionTributariaFk($codigoClasificacionTributariaFk)
    {
        $this->codigoClasificacionTributariaFk = $codigoClasificacionTributariaFk;

        return $this;
    }

    /**
     * Get codigoClasificacionTributariaFk
     *
     * @return integer
     */
    public function getCodigoClasificacionTributariaFk()
    {
        return $this->codigoClasificacionTributariaFk;
    }

    /**
     * Set codigoFormaPagoClienteFk
     *
     * @param integer $codigoFormaPagoClienteFk
     *
     * @return InvTercero
     */
    public function setCodigoFormaPagoClienteFk($codigoFormaPagoClienteFk)
    {
        $this->codigoFormaPagoClienteFk = $codigoFormaPagoClienteFk;

        return $this;
    }

    /**
     * Get codigoFormaPagoClienteFk
     *
     * @return integer
     */
    public function getCodigoFormaPagoClienteFk()
    {
        return $this->codigoFormaPagoClienteFk;
    }

    /**
     * Set plazoPagoCliente
     *
     * @param integer $plazoPagoCliente
     *
     * @return InvTercero
     */
    public function setPlazoPagoCliente($plazoPagoCliente)
    {
        $this->plazoPagoCliente = $plazoPagoCliente;

        return $this;
    }

    /**
     * Get plazoPagoCliente
     *
     * @return integer
     */
    public function getPlazoPagoCliente()
    {
        return $this->plazoPagoCliente;
    }

    /**
     * Set codigoFormaPagoProveedorFk
     *
     * @param integer $codigoFormaPagoProveedorFk
     *
     * @return InvTercero
     */
    public function setCodigoFormaPagoProveedorFk($codigoFormaPagoProveedorFk)
    {
        $this->codigoFormaPagoProveedorFk = $codigoFormaPagoProveedorFk;

        return $this;
    }

    /**
     * Get codigoFormaPagoProveedorFk
     *
     * @return integer
     */
    public function getCodigoFormaPagoProveedorFk()
    {
        return $this->codigoFormaPagoProveedorFk;
    }

    /**
     * Set plazoPagoProveedor
     *
     * @param integer $plazoPagoProveedor
     *
     * @return InvTercero
     */
    public function setPlazoPagoProveedor($plazoPagoProveedor)
    {
        $this->plazoPagoProveedor = $plazoPagoProveedor;

        return $this;
    }

    /**
     * Get plazoPagoProveedor
     *
     * @return integer
     */
    public function getPlazoPagoProveedor()
    {
        return $this->plazoPagoProveedor;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return InvTercero
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
     * @return InvTercero
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
     * @return InvTercero
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
     * Set fax
     *
     * @param string $fax
     *
     * @return InvTercero
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return InvTercero
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set retencionFuenteVentas
     *
     * @param boolean $retencionFuenteVentas
     *
     * @return InvTercero
     */
    public function setRetencionFuenteVentas($retencionFuenteVentas)
    {
        $this->retencionFuenteVentas = $retencionFuenteVentas;

        return $this;
    }

    /**
     * Get retencionFuenteVentas
     *
     * @return boolean
     */
    public function getRetencionFuenteVentas()
    {
        return $this->retencionFuenteVentas;
    }

    /**
     * Set retencionFuenteVentasSinBase
     *
     * @param boolean $retencionFuenteVentasSinBase
     *
     * @return InvTercero
     */
    public function setRetencionFuenteVentasSinBase($retencionFuenteVentasSinBase)
    {
        $this->retencionFuenteVentasSinBase = $retencionFuenteVentasSinBase;

        return $this;
    }

    /**
     * Get retencionFuenteVentasSinBase
     *
     * @return boolean
     */
    public function getRetencionFuenteVentasSinBase()
    {
        return $this->retencionFuenteVentasSinBase;
    }

    /**
     * Set autoretenedor
     *
     * @param boolean $autoretenedor
     *
     * @return InvTercero
     */
    public function setAutoretenedor($autoretenedor)
    {
        $this->autoretenedor = $autoretenedor;

        return $this;
    }

    /**
     * Get autoretenedor
     *
     * @return boolean
     */
    public function getAutoretenedor()
    {
        return $this->autoretenedor;
    }

    /**
     * Set contactoCliente
     *
     * @param string $contactoCliente
     *
     * @return InvTercero
     */
    public function setContactoCliente($contactoCliente)
    {
        $this->contactoCliente = $contactoCliente;

        return $this;
    }

    /**
     * Get contactoCliente
     *
     * @return string
     */
    public function getContactoCliente()
    {
        return $this->contactoCliente;
    }

    /**
     * Set celularContactoCliente
     *
     * @param string $celularContactoCliente
     *
     * @return InvTercero
     */
    public function setCelularContactoCliente($celularContactoCliente)
    {
        $this->celularContactoCliente = $celularContactoCliente;

        return $this;
    }

    /**
     * Get celularContactoCliente
     *
     * @return string
     */
    public function getCelularContactoCliente()
    {
        return $this->celularContactoCliente;
    }

    /**
     * Set contactoProveedor
     *
     * @param string $contactoProveedor
     *
     * @return InvTercero
     */
    public function setContactoProveedor($contactoProveedor)
    {
        $this->contactoProveedor = $contactoProveedor;

        return $this;
    }

    /**
     * Get contactoProveedor
     *
     * @return string
     */
    public function getContactoProveedor()
    {
        return $this->contactoProveedor;
    }

    /**
     * Set celularContactoProveedor
     *
     * @param string $celularContactoProveedor
     *
     * @return InvTercero
     */
    public function setCelularContactoProveedor($celularContactoProveedor)
    {
        $this->celularContactoProveedor = $celularContactoProveedor;

        return $this;
    }

    /**
     * Get celularContactoProveedor
     *
     * @return string
     */
    public function getCelularContactoProveedor()
    {
        return $this->celularContactoProveedor;
    }

    /**
     * Set codigoActividadEconomica
     *
     * @param integer $codigoActividadEconomica
     *
     * @return InvTercero
     */
    public function setCodigoActividadEconomica($codigoActividadEconomica)
    {
        $this->codigoActividadEconomica = $codigoActividadEconomica;

        return $this;
    }

    /**
     * Get codigoActividadEconomica
     *
     * @return integer
     */
    public function getCodigoActividadEconomica()
    {
        return $this->codigoActividadEconomica;
    }

    /**
     * Set porcentajeCREE
     *
     * @param float $porcentajeCREE
     *
     * @return InvTercero
     */
    public function setPorcentajeCREE($porcentajeCREE)
    {
        $this->porcentajeCREE = $porcentajeCREE;

        return $this;
    }

    /**
     * Get porcentajeCREE
     *
     * @return float
     */
    public function getPorcentajeCREE()
    {
        return $this->porcentajeCREE;
    }

    /**
     * Add movimientosTerceroRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimiento $movimientosTerceroRel
     *
     * @return InvTercero
     */
    public function addMovimientosTerceroRel(\Brasa\InventarioBundle\Entity\InvMovimiento $movimientosTerceroRel)
    {
        $this->movimientosTerceroRel[] = $movimientosTerceroRel;

        return $this;
    }

    /**
     * Remove movimientosTerceroRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimiento $movimientosTerceroRel
     */
    public function removeMovimientosTerceroRel(\Brasa\InventarioBundle\Entity\InvMovimiento $movimientosTerceroRel)
    {
        $this->movimientosTerceroRel->removeElement($movimientosTerceroRel);
    }

    /**
     * Get movimientosTerceroRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientosTerceroRel()
    {
        return $this->movimientosTerceroRel;
    }
}
