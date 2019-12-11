<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_despacho")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteDespachoRepository")
 */
class TteDespacho
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_despacho_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDespachoPk;         
    
    /**
     * @ORM\Column(name="codigo_despacho_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoDespachoTipoFk;     
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="codigo_ciudad_origen_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadOrigenFk;     

    /**
     * @ORM\Column(name="codigo_ciudad_destino_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadDestinoFk;    
    
    /**
     * @ORM\Column(name="codigo_punto_operacion_fk", type="integer", nullable=true)
     */    
    private $codigoPuntoOperacionFk;    
    
    /**
     * @ORM\Column(name="codigo_ruta_fk", type="integer", nullable=true)
     */    
    private $codigoRutaFk;     
    
    /**
     * @ORM\Column(name="codigo_conductor_fk", type="integer", nullable=true)
     */    
    private $codigoConductorFk;    
    
    /**
     * @ORM\Column(name="codigo_vehiculo_fk", type="integer", nullable=true)
     */    
    private $codigoVehiculoFk;     
    
    /**
     * @ORM\Column(name="vr_flete", type="float")
     */
    private $vrFlete = 0;
    
    /**
     * @ORM\Column(name="vr_anticipo", type="float")
     */
    private $vrAnticipo = 0;    
    
    /**
     * @ORM\Column(name="vr_industria_comercio", type="float")
     */
    private $vrIndustriaComercio = 0;    
    
    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float")
     */
    private $vrRetencionFuente = 0;    
    
    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;      
    
    /**
     * @ORM\Column(name="vr_otros_descuentos", type="float")
     */
    private $vrOtrosDescuentos = 0;     

    /**
     * @ORM\Column(name="ct_peso_real", type="integer")
     */
    private $ctPesoReal = 0;    

    /**
     * @ORM\Column(name="ct_peso_volumen", type="integer")
     */
    private $ctPesoVolumen = 0;        

    /**
     * @ORM\Column(name="ct_unidades", type="integer")
     */
    private $ctUnidades = 0;    
    
    /**
     * @ORM\Column(name="ct_guias", type="integer")
     */
    private $ctGuias = 0;    
    
    /**
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = 0;  

    /**
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = 0;      

    /**
     * @ORM\Column(name="estado_descargado", type="boolean")
     */    
    private $estadoDescargado = 0;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;     
    
    
    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="despachoRel")
     */
    protected $guiasDetallesRel;     

    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="despachosCiudadOrigenRel")
     * @ORM\JoinColumn(name="codigo_ciudad_origen_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadOrigenRel;     

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="despachosCiudadDestinoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_destino_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadDestinoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TteRuta", inversedBy="despachosRel")
     * @ORM\JoinColumn(name="codigo_ruta_fk", referencedColumnName="codigo_ruta_pk")
     */
    protected $rutaRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TteConductor", inversedBy="despachosRel")
     * @ORM\JoinColumn(name="codigo_conductor_fk", referencedColumnName="codigo_conductor_pk")
     */
    protected $conductorRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TteVehiculo", inversedBy="despachosRel")
     * @ORM\JoinColumn(name="codigo_vehiculo_fk", referencedColumnName="codigo_vehiculo_pk")
     */
    protected $vehiculoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TteDespachoTipo", inversedBy="despachosRel")
     * @ORM\JoinColumn(name="codigo_despacho_tipo_fk", referencedColumnName="codigo_despacho_tipo_pk")
     */
    protected $despachoTipoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TtePuntoOperacion", inversedBy="despachosPuntoOperacionRel")
     * @ORM\JoinColumn(name="codigo_punto_operacion_fk", referencedColumnName="codigo_punto_operacion_pk")
     */
    protected $puntoOperacionRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->guiasDetallesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDespachoPk
     *
     * @return integer 
     */
    public function getCodigoDespachoPk()
    {
        return $this->codigoDespachoPk;
    }

    /**
     * Set codigoDespachoTipoFk
     *
     * @param integer $codigoDespachoTipoFk
     * @return TteDespacho
     */
    public function setCodigoDespachoTipoFk($codigoDespachoTipoFk)
    {
        $this->codigoDespachoTipoFk = $codigoDespachoTipoFk;

        return $this;
    }

    /**
     * Get codigoDespachoTipoFk
     *
     * @return integer 
     */
    public function getCodigoDespachoTipoFk()
    {
        return $this->codigoDespachoTipoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return TteDespacho
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
     * Set codigoCiudadOrigenFk
     *
     * @param integer $codigoCiudadOrigenFk
     * @return TteDespacho
     */
    public function setCodigoCiudadOrigenFk($codigoCiudadOrigenFk)
    {
        $this->codigoCiudadOrigenFk = $codigoCiudadOrigenFk;

        return $this;
    }

    /**
     * Get codigoCiudadOrigenFk
     *
     * @return integer 
     */
    public function getCodigoCiudadOrigenFk()
    {
        return $this->codigoCiudadOrigenFk;
    }

    /**
     * Set codigoCiudadDestinoFk
     *
     * @param integer $codigoCiudadDestinoFk
     * @return TteDespacho
     */
    public function setCodigoCiudadDestinoFk($codigoCiudadDestinoFk)
    {
        $this->codigoCiudadDestinoFk = $codigoCiudadDestinoFk;

        return $this;
    }

    /**
     * Get codigoCiudadDestinoFk
     *
     * @return integer 
     */
    public function getCodigoCiudadDestinoFk()
    {
        return $this->codigoCiudadDestinoFk;
    }

    /**
     * Set codigoPuntoOperacionFk
     *
     * @param integer $codigoPuntoOperacionFk
     * @return TteDespacho
     */
    public function setCodigoPuntoOperacionFk($codigoPuntoOperacionFk)
    {
        $this->codigoPuntoOperacionFk = $codigoPuntoOperacionFk;

        return $this;
    }

    /**
     * Get codigoPuntoOperacionFk
     *
     * @return integer 
     */
    public function getCodigoPuntoOperacionFk()
    {
        return $this->codigoPuntoOperacionFk;
    }

    /**
     * Set codigoRutaFk
     *
     * @param integer $codigoRutaFk
     * @return TteDespacho
     */
    public function setCodigoRutaFk($codigoRutaFk)
    {
        $this->codigoRutaFk = $codigoRutaFk;

        return $this;
    }

    /**
     * Get codigoRutaFk
     *
     * @return integer 
     */
    public function getCodigoRutaFk()
    {
        return $this->codigoRutaFk;
    }

    /**
     * Set codigoConductorFk
     *
     * @param integer $codigoConductorFk
     * @return TteDespacho
     */
    public function setCodigoConductorFk($codigoConductorFk)
    {
        $this->codigoConductorFk = $codigoConductorFk;

        return $this;
    }

    /**
     * Get codigoConductorFk
     *
     * @return integer 
     */
    public function getCodigoConductorFk()
    {
        return $this->codigoConductorFk;
    }

    /**
     * Set codigoVehiculoFk
     *
     * @param integer $codigoVehiculoFk
     * @return TteDespacho
     */
    public function setCodigoVehiculoFk($codigoVehiculoFk)
    {
        $this->codigoVehiculoFk = $codigoVehiculoFk;

        return $this;
    }

    /**
     * Get codigoVehiculoFk
     *
     * @return integer 
     */
    public function getCodigoVehiculoFk()
    {
        return $this->codigoVehiculoFk;
    }

    /**
     * Set vrFlete
     *
     * @param float $vrFlete
     * @return TteDespacho
     */
    public function setVrFlete($vrFlete)
    {
        $this->vrFlete = $vrFlete;

        return $this;
    }

    /**
     * Get vrFlete
     *
     * @return float 
     */
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * Set vrAnticipo
     *
     * @param float $vrAnticipo
     * @return TteDespacho
     */
    public function setVrAnticipo($vrAnticipo)
    {
        $this->vrAnticipo = $vrAnticipo;

        return $this;
    }

    /**
     * Get vrAnticipo
     *
     * @return float 
     */
    public function getVrAnticipo()
    {
        return $this->vrAnticipo;
    }

    /**
     * Set vrIndustriaComercio
     *
     * @param float $vrIndustriaComercio
     * @return TteDespacho
     */
    public function setVrIndustriaComercio($vrIndustriaComercio)
    {
        $this->vrIndustriaComercio = $vrIndustriaComercio;

        return $this;
    }

    /**
     * Get vrIndustriaComercio
     *
     * @return float 
     */
    public function getVrIndustriaComercio()
    {
        return $this->vrIndustriaComercio;
    }

    /**
     * Set vrRetencionFuente
     *
     * @param float $vrRetencionFuente
     * @return TteDespacho
     */
    public function setVrRetencionFuente($vrRetencionFuente)
    {
        $this->vrRetencionFuente = $vrRetencionFuente;

        return $this;
    }

    /**
     * Get vrRetencionFuente
     *
     * @return float 
     */
    public function getVrRetencionFuente()
    {
        return $this->vrRetencionFuente;
    }

    /**
     * Set vrNeto
     *
     * @param float $vrNeto
     * @return TteDespacho
     */
    public function setVrNeto($vrNeto)
    {
        $this->vrNeto = $vrNeto;

        return $this;
    }

    /**
     * Get vrNeto
     *
     * @return float 
     */
    public function getVrNeto()
    {
        return $this->vrNeto;
    }

    /**
     * Set vrOtrosDescuentos
     *
     * @param float $vrOtrosDescuentos
     * @return TteDespacho
     */
    public function setVrOtrosDescuentos($vrOtrosDescuentos)
    {
        $this->vrOtrosDescuentos = $vrOtrosDescuentos;

        return $this;
    }

    /**
     * Get vrOtrosDescuentos
     *
     * @return float 
     */
    public function getVrOtrosDescuentos()
    {
        return $this->vrOtrosDescuentos;
    }

    /**
     * Set ctPesoReal
     *
     * @param integer $ctPesoReal
     * @return TteDespacho
     */
    public function setCtPesoReal($ctPesoReal)
    {
        $this->ctPesoReal = $ctPesoReal;

        return $this;
    }

    /**
     * Get ctPesoReal
     *
     * @return integer 
     */
    public function getCtPesoReal()
    {
        return $this->ctPesoReal;
    }

    /**
     * Set ctPesoVolumen
     *
     * @param integer $ctPesoVolumen
     * @return TteDespacho
     */
    public function setCtPesoVolumen($ctPesoVolumen)
    {
        $this->ctPesoVolumen = $ctPesoVolumen;

        return $this;
    }

    /**
     * Get ctPesoVolumen
     *
     * @return integer 
     */
    public function getCtPesoVolumen()
    {
        return $this->ctPesoVolumen;
    }

    /**
     * Set ctUnidades
     *
     * @param integer $ctUnidades
     * @return TteDespacho
     */
    public function setCtUnidades($ctUnidades)
    {
        $this->ctUnidades = $ctUnidades;

        return $this;
    }

    /**
     * Get ctUnidades
     *
     * @return integer 
     */
    public function getCtUnidades()
    {
        return $this->ctUnidades;
    }

    /**
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     * @return TteDespacho
     */
    public function setEstadoAnulado($estadoAnulado)
    {
        $this->estadoAnulado = $estadoAnulado;

        return $this;
    }

    /**
     * Get estadoAnulado
     *
     * @return boolean 
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     * @return TteDespacho
     */
    public function setEstadoGenerado($estadoGenerado)
    {
        $this->estadoGenerado = $estadoGenerado;

        return $this;
    }

    /**
     * Get estadoGenerado
     *
     * @return boolean 
     */
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * Set estadoDescargado
     *
     * @param boolean $estadoDescargado
     * @return TteDespacho
     */
    public function setEstadoDescargado($estadoDescargado)
    {
        $this->estadoDescargado = $estadoDescargado;

        return $this;
    }

    /**
     * Get estadoDescargado
     *
     * @return boolean 
     */
    public function getEstadoDescargado()
    {
        return $this->estadoDescargado;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     * @return TteDespacho
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
     * Add guiasDetallesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasDetallesRel
     * @return TteDespacho
     */
    public function addGuiasDetallesRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasDetallesRel)
    {
        $this->guiasDetallesRel[] = $guiasDetallesRel;

        return $this;
    }

    /**
     * Remove guiasDetallesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasDetallesRel
     */
    public function removeGuiasDetallesRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasDetallesRel)
    {
        $this->guiasDetallesRel->removeElement($guiasDetallesRel);
    }

    /**
     * Get guiasDetallesRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGuiasDetallesRel()
    {
        return $this->guiasDetallesRel;
    }

    /**
     * Set ciudadOrigenRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadOrigenRel
     * @return TteDespacho
     */
    public function setCiudadOrigenRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadOrigenRel = null)
    {
        $this->ciudadOrigenRel = $ciudadOrigenRel;

        return $this;
    }

    /**
     * Get ciudadOrigenRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad 
     */
    public function getCiudadOrigenRel()
    {
        return $this->ciudadOrigenRel;
    }

    /**
     * Set ciudadDestinoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadDestinoRel
     * @return TteDespacho
     */
    public function setCiudadDestinoRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadDestinoRel = null)
    {
        $this->ciudadDestinoRel = $ciudadDestinoRel;

        return $this;
    }

    /**
     * Get ciudadDestinoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad 
     */
    public function getCiudadDestinoRel()
    {
        return $this->ciudadDestinoRel;
    }

    /**
     * Set rutaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRuta $rutaRel
     * @return TteDespacho
     */
    public function setRutaRel(\Brasa\TransporteBundle\Entity\TteRuta $rutaRel = null)
    {
        $this->rutaRel = $rutaRel;

        return $this;
    }

    /**
     * Get rutaRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteRuta 
     */
    public function getRutaRel()
    {
        return $this->rutaRel;
    }

    /**
     * Set conductorRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteConductor $conductorRel
     * @return TteDespacho
     */
    public function setConductorRel(\Brasa\TransporteBundle\Entity\TteConductor $conductorRel = null)
    {
        $this->conductorRel = $conductorRel;

        return $this;
    }

    /**
     * Get conductorRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteConductor 
     */
    public function getConductorRel()
    {
        return $this->conductorRel;
    }

    /**
     * Set vehiculoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteVehiculo $vehiculoRel
     * @return TteDespacho
     */
    public function setVehiculoRel(\Brasa\TransporteBundle\Entity\TteVehiculo $vehiculoRel = null)
    {
        $this->vehiculoRel = $vehiculoRel;

        return $this;
    }

    /**
     * Get vehiculoRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteVehiculo 
     */
    public function getVehiculoRel()
    {
        return $this->vehiculoRel;
    }

    /**
     * Set despachoTipoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespachoTipo $despachoTipoRel
     * @return TteDespacho
     */
    public function setDespachoTipoRel(\Brasa\TransporteBundle\Entity\TteDespachoTipo $despachoTipoRel = null)
    {
        $this->despachoTipoRel = $despachoTipoRel;

        return $this;
    }

    /**
     * Get despachoTipoRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteDespachoTipo 
     */
    public function getDespachoTipoRel()
    {
        return $this->despachoTipoRel;
    }

    /**
     * Set puntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePuntoOperacion $puntoOperacionRel
     * @return TteDespacho
     */
    public function setPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TtePuntoOperacion $puntoOperacionRel = null)
    {
        $this->puntoOperacionRel = $puntoOperacionRel;

        return $this;
    }

    /**
     * Get puntoOperacionRel
     *
     * @return \Brasa\TransporteBundle\Entity\TtePuntoOperacion 
     */
    public function getPuntoOperacionRel()
    {
        return $this->puntoOperacionRel;
    }

    /**
     * Set ctGuias
     *
     * @param integer $ctGuias
     * @return TteDespacho
     */
    public function setCtGuias($ctGuias)
    {
        $this->ctGuias = $ctGuias;

        return $this;
    }

    /**
     * Get ctGuias
     *
     * @return integer 
     */
    public function getCtGuias()
    {
        return $this->ctGuias;
    }
}
