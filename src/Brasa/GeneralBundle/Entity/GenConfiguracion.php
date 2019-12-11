<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_configuracion")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenConfiguracionRepository")
 */
class GenConfiguracion
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="smallint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoConfiguracionPk;    
    
    /**
     * @ORM\Column(name="base_retencion_fuente", type="float")
     */
    private $baseRetencionFuente = 0; 

    /**
     * @ORM\Column(name="base_retencion_cree", type="float")
     */
    private $baseRetencionCREE = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_retencion_fuente", type="float")
     */
    private $porcentajeRetencionFuente = 0;     

    /**
     * @ORM\Column(name="porcentaje_retencion_cree", type="float")
     */
    private $porcentajeRetencionCREE = 0;     
    
    /**
     * @ORM\Column(name="base_retencion_iva_ventas", type="float")
     */
    private $baseRetencionIvaVentas = 0;     

    /**
     * @ORM\Column(name="porcentaje_retencion_iva_ventas", type="float")
     */
    private $porcentajeRetencionIvaVentas = 0;     
    
    /**
     * @ORM\Column(name="fecha_ultimo_cierre", type="date", nullable=true)
     */    
    private $fechaUltimoCierre;     

    /**
     * @ORM\Column(name="nit_ventas_mostrador", type="integer")
     */
    private $nitVentasMostrador = 0;         

    /**
     * @ORM\Column(name="ruta_temporal", type="string", length=500, nullable=true)
     */      
    private $rutaTemporal;    

    /**
     * @ORM\Column(name="ruta_almacenamiento", type="string", length=500, nullable=true)
     */      
    private $rutaAlmacenamiento;    

    /**
     * @ORM\Column(name="ruta_imagenes", type="string", length=500, nullable=true)
     */      
    private $rutaImagenes;    
    /**
     * @ORM\Column(name="nit_empresa", type="string", length=20, nullable=true)
     */      
    private $nitEmpresa;         
    
    /**
     * @ORM\Column(name="digito_verificacion_empresa", type="string", length=2, nullable=true)
     */    
    private $digitoVerificacionEmpresa;
    
    /**
     * @ORM\Column(name="nombre_empresa", type="string", length=90, nullable=true)
     */    
    private $nombreEmpresa;
    
    /**
     * @ORM\Column(name="identificacion_representante_legal", type="string", length=80, nullable=true)
     */      
    private $identificacionRepresentanteLegal;
    
    /**
     * @ORM\Column(name="representante_legal", type="string", length=80, nullable=true)
     */      
    private $representanteLegal;        
    
    /**
     * @ORM\Column(name="sigla", type="string", length=90, nullable=true)
     */    
    private $sigla;
    
    /**
     * @ORM\Column(name="telefono_empresa", type="string", length=25, nullable=true)
     */    
    private $telefonoEmpresa;
    
    /**
     * @ORM\Column(name="direccion_empresa", type="string", length=120, nullable=true)
     */    
    private $direccionEmpresa;
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;
    
    /**
     * @ORM\Column(name="ruta_directorio", type="string", length=500, nullable=true)
     */      
    private $rutaDirectorio;
    
    /**
     * @ORM\Column(name="pagina_web", type="string", length=100, nullable=true)
     */      
    private $paginaWeb;
        
    /**
     * @ORM\Column(name="inhabilitado", type="boolean")
     */    
    private $inhabilitado = false;
    
    /**
     * @ORM\Column(name="clave_identificacion_portal_empleados", type="boolean")
     */    
    private $claveIdentificacionPortalEmpleados = false;    
    
    /**
     * @ORM\Column(name="correo_general", type="string", length=100, nullable=true)
     */    
    private $correoGeneral;
    
    /**
     * @ORM\ManyToOne(targetEntity="GenCiudad", inversedBy="configuracionesRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;
    

    

    /**
     * Get codigoConfiguracionPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * Set baseRetencionFuente
     *
     * @param float $baseRetencionFuente
     *
     * @return GenConfiguracion
     */
    public function setBaseRetencionFuente($baseRetencionFuente)
    {
        $this->baseRetencionFuente = $baseRetencionFuente;

        return $this;
    }

    /**
     * Get baseRetencionFuente
     *
     * @return float
     */
    public function getBaseRetencionFuente()
    {
        return $this->baseRetencionFuente;
    }

    /**
     * Set baseRetencionCREE
     *
     * @param float $baseRetencionCREE
     *
     * @return GenConfiguracion
     */
    public function setBaseRetencionCREE($baseRetencionCREE)
    {
        $this->baseRetencionCREE = $baseRetencionCREE;

        return $this;
    }

    /**
     * Get baseRetencionCREE
     *
     * @return float
     */
    public function getBaseRetencionCREE()
    {
        return $this->baseRetencionCREE;
    }

    /**
     * Set porcentajeRetencionFuente
     *
     * @param float $porcentajeRetencionFuente
     *
     * @return GenConfiguracion
     */
    public function setPorcentajeRetencionFuente($porcentajeRetencionFuente)
    {
        $this->porcentajeRetencionFuente = $porcentajeRetencionFuente;

        return $this;
    }

    /**
     * Get porcentajeRetencionFuente
     *
     * @return float
     */
    public function getPorcentajeRetencionFuente()
    {
        return $this->porcentajeRetencionFuente;
    }

    /**
     * Set porcentajeRetencionCREE
     *
     * @param float $porcentajeRetencionCREE
     *
     * @return GenConfiguracion
     */
    public function setPorcentajeRetencionCREE($porcentajeRetencionCREE)
    {
        $this->porcentajeRetencionCREE = $porcentajeRetencionCREE;

        return $this;
    }

    /**
     * Get porcentajeRetencionCREE
     *
     * @return float
     */
    public function getPorcentajeRetencionCREE()
    {
        return $this->porcentajeRetencionCREE;
    }

    /**
     * Set baseRetencionIvaVentas
     *
     * @param float $baseRetencionIvaVentas
     *
     * @return GenConfiguracion
     */
    public function setBaseRetencionIvaVentas($baseRetencionIvaVentas)
    {
        $this->baseRetencionIvaVentas = $baseRetencionIvaVentas;

        return $this;
    }

    /**
     * Get baseRetencionIvaVentas
     *
     * @return float
     */
    public function getBaseRetencionIvaVentas()
    {
        return $this->baseRetencionIvaVentas;
    }

    /**
     * Set porcentajeRetencionIvaVentas
     *
     * @param float $porcentajeRetencionIvaVentas
     *
     * @return GenConfiguracion
     */
    public function setPorcentajeRetencionIvaVentas($porcentajeRetencionIvaVentas)
    {
        $this->porcentajeRetencionIvaVentas = $porcentajeRetencionIvaVentas;

        return $this;
    }

    /**
     * Get porcentajeRetencionIvaVentas
     *
     * @return float
     */
    public function getPorcentajeRetencionIvaVentas()
    {
        return $this->porcentajeRetencionIvaVentas;
    }

    /**
     * Set fechaUltimoCierre
     *
     * @param \DateTime $fechaUltimoCierre
     *
     * @return GenConfiguracion
     */
    public function setFechaUltimoCierre($fechaUltimoCierre)
    {
        $this->fechaUltimoCierre = $fechaUltimoCierre;

        return $this;
    }

    /**
     * Get fechaUltimoCierre
     *
     * @return \DateTime
     */
    public function getFechaUltimoCierre()
    {
        return $this->fechaUltimoCierre;
    }

    /**
     * Set nitVentasMostrador
     *
     * @param integer $nitVentasMostrador
     *
     * @return GenConfiguracion
     */
    public function setNitVentasMostrador($nitVentasMostrador)
    {
        $this->nitVentasMostrador = $nitVentasMostrador;

        return $this;
    }

    /**
     * Get nitVentasMostrador
     *
     * @return integer
     */
    public function getNitVentasMostrador()
    {
        return $this->nitVentasMostrador;
    }

    /**
     * Set rutaTemporal
     *
     * @param string $rutaTemporal
     *
     * @return GenConfiguracion
     */
    public function setRutaTemporal($rutaTemporal)
    {
        $this->rutaTemporal = $rutaTemporal;

        return $this;
    }

    /**
     * Get rutaTemporal
     *
     * @return string
     */
    public function getRutaTemporal()
    {
        return $this->rutaTemporal;
    }

    /**
     * Set rutaAlmacenamiento
     *
     * @param string $rutaAlmacenamiento
     *
     * @return GenConfiguracion
     */
    public function setRutaAlmacenamiento($rutaAlmacenamiento)
    {
        $this->rutaAlmacenamiento = $rutaAlmacenamiento;

        return $this;
    }

    /**
     * Get rutaAlmacenamiento
     *
     * @return string
     */
    public function getRutaAlmacenamiento()
    {
        return $this->rutaAlmacenamiento;
    }

    /**
     * Set rutaImagenes
     *
     * @param string $rutaImagenes
     *
     * @return GenConfiguracion
     */
    public function setRutaImagenes($rutaImagenes)
    {
        $this->rutaImagenes = $rutaImagenes;

        return $this;
    }

    /**
     * Get rutaImagenes
     *
     * @return string
     */
    public function getRutaImagenes()
    {
        return $this->rutaImagenes;
    }

    /**
     * Set nitEmpresa
     *
     * @param string $nitEmpresa
     *
     * @return GenConfiguracion
     */
    public function setNitEmpresa($nitEmpresa)
    {
        $this->nitEmpresa = $nitEmpresa;

        return $this;
    }

    /**
     * Get nitEmpresa
     *
     * @return string
     */
    public function getNitEmpresa()
    {
        return $this->nitEmpresa;
    }

    /**
     * Set digitoVerificacionEmpresa
     *
     * @param string $digitoVerificacionEmpresa
     *
     * @return GenConfiguracion
     */
    public function setDigitoVerificacionEmpresa($digitoVerificacionEmpresa)
    {
        $this->digitoVerificacionEmpresa = $digitoVerificacionEmpresa;

        return $this;
    }

    /**
     * Get digitoVerificacionEmpresa
     *
     * @return string
     */
    public function getDigitoVerificacionEmpresa()
    {
        return $this->digitoVerificacionEmpresa;
    }

    /**
     * Set nombreEmpresa
     *
     * @param string $nombreEmpresa
     *
     * @return GenConfiguracion
     */
    public function setNombreEmpresa($nombreEmpresa)
    {
        $this->nombreEmpresa = $nombreEmpresa;

        return $this;
    }

    /**
     * Get nombreEmpresa
     *
     * @return string
     */
    public function getNombreEmpresa()
    {
        return $this->nombreEmpresa;
    }

    /**
     * Set identificacionRepresentanteLegal
     *
     * @param string $identificacionRepresentanteLegal
     *
     * @return GenConfiguracion
     */
    public function setIdentificacionRepresentanteLegal($identificacionRepresentanteLegal)
    {
        $this->identificacionRepresentanteLegal = $identificacionRepresentanteLegal;

        return $this;
    }

    /**
     * Get identificacionRepresentanteLegal
     *
     * @return string
     */
    public function getIdentificacionRepresentanteLegal()
    {
        return $this->identificacionRepresentanteLegal;
    }

    /**
     * Set representanteLegal
     *
     * @param string $representanteLegal
     *
     * @return GenConfiguracion
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
     * Set sigla
     *
     * @param string $sigla
     *
     * @return GenConfiguracion
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;

        return $this;
    }

    /**
     * Get sigla
     *
     * @return string
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * Set telefonoEmpresa
     *
     * @param string $telefonoEmpresa
     *
     * @return GenConfiguracion
     */
    public function setTelefonoEmpresa($telefonoEmpresa)
    {
        $this->telefonoEmpresa = $telefonoEmpresa;

        return $this;
    }

    /**
     * Get telefonoEmpresa
     *
     * @return string
     */
    public function getTelefonoEmpresa()
    {
        return $this->telefonoEmpresa;
    }

    /**
     * Set direccionEmpresa
     *
     * @param string $direccionEmpresa
     *
     * @return GenConfiguracion
     */
    public function setDireccionEmpresa($direccionEmpresa)
    {
        $this->direccionEmpresa = $direccionEmpresa;

        return $this;
    }

    /**
     * Get direccionEmpresa
     *
     * @return string
     */
    public function getDireccionEmpresa()
    {
        return $this->direccionEmpresa;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return GenConfiguracion
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
     * Set rutaDirectorio
     *
     * @param string $rutaDirectorio
     *
     * @return GenConfiguracion
     */
    public function setRutaDirectorio($rutaDirectorio)
    {
        $this->rutaDirectorio = $rutaDirectorio;

        return $this;
    }

    /**
     * Get rutaDirectorio
     *
     * @return string
     */
    public function getRutaDirectorio()
    {
        return $this->rutaDirectorio;
    }

    /**
     * Set paginaWeb
     *
     * @param string $paginaWeb
     *
     * @return GenConfiguracion
     */
    public function setPaginaWeb($paginaWeb)
    {
        $this->paginaWeb = $paginaWeb;

        return $this;
    }

    /**
     * Get paginaWeb
     *
     * @return string
     */
    public function getPaginaWeb()
    {
        return $this->paginaWeb;
    }

    /**
     * Set inhabilitado
     *
     * @param boolean $inhabilitado
     *
     * @return GenConfiguracion
     */
    public function setInhabilitado($inhabilitado)
    {
        $this->inhabilitado = $inhabilitado;

        return $this;
    }

    /**
     * Get inhabilitado
     *
     * @return boolean
     */
    public function getInhabilitado()
    {
        return $this->inhabilitado;
    }

    /**
     * Set correoGeneral
     *
     * @param string $correoGeneral
     *
     * @return GenConfiguracion
     */
    public function setCorreoGeneral($correoGeneral)
    {
        $this->correoGeneral = $correoGeneral;

        return $this;
    }

    /**
     * Get correoGeneral
     *
     * @return string
     */
    public function getCorreoGeneral()
    {
        return $this->correoGeneral;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return GenConfiguracion
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
     * Set claveIdentificacionPortalEmpleados
     *
     * @param boolean $claveIdentificacionPortalEmpleados
     *
     * @return GenConfiguracion
     */
    public function setClaveIdentificacionPortalEmpleados($claveIdentificacionPortalEmpleados)
    {
        $this->claveIdentificacionPortalEmpleados = $claveIdentificacionPortalEmpleados;

        return $this;
    }

    /**
     * Get claveIdentificacionPortalEmpleados
     *
     * @return boolean
     */
    public function getClaveIdentificacionPortalEmpleados()
    {
        return $this->claveIdentificacionPortalEmpleados;
    }
}
