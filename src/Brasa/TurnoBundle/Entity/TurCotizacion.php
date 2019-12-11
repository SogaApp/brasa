<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_cotizacion")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCotizacionRepository")
 */
class TurCotizacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cotizacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCotizacionPk;         
    
    /**
     * @ORM\Column(name="numero", type="integer")
     */    
    private $numero = 0;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;    

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */    
    private $fechaVence;    
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;    

    /**
     * @ORM\Column(name="codigo_prospecto_fk", type="integer", nullable=true)
     */    
    private $codigoProspectoFk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=90, nullable=true)
     */
    private $nombre;    
    
    /**
     * @ORM\Column(name="codigo_sector_fk", type="integer", nullable=true)
     */    
    private $codigoSectorFk;    
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;     

    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = false;    
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;    
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="horas_diurnas", type="integer")
     */    
    private $horasDiurnas = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas", type="integer")
     */    
    private $horasNocturnas = 0;    
    
    /**
     * @ORM\Column(name="vr_total_costo", type="float")
     */
    private $vrTotalCosto = 0;
    
    /**
     * @ORM\Column(name="vr_total_precio_ajustado", type="float")
     */
    private $vrTotalPrecioAjustado = 0;            

    /**
     * @ORM\Column(name="vr_total_precio_minimo", type="float")
     */
    private $vrTotalPrecioMinimo = 0;        
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0; 
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;                  

    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="cotizacionesClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;     

    /**
     * @ORM\ManyToOne(targetEntity="TurProspecto", inversedBy="cotizacionesProspectoRel")
     * @ORM\JoinColumn(name="codigo_prospecto_fk", referencedColumnName="codigo_prospecto_pk")
     */
    protected $prospectoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurSector", inversedBy="cotizacionesSectorRel")
     * @ORM\JoinColumn(name="codigo_sector_fk", referencedColumnName="codigo_sector_pk")
     */
    protected $sectorRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCotizacionDetalle", mappedBy="cotizacionRel", cascade={"persist", "remove"})
     */
    protected $cotizacionesDetallesCotizacionRel; 

    /**
     * @ORM\OneToMany(targetEntity="TurCotizacionOtro", mappedBy="cotizacionRel", cascade={"persist", "remove"})
     */
    protected $cotizacionesOtrosCotizacionRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cotizacionesDetallesCotizacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cotizacionesOtrosCotizacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCotizacionPk
     *
     * @return integer
     */
    public function getCodigoCotizacionPk()
    {
        return $this->codigoCotizacionPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TurCotizacion
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
     * Set fechaVence
     *
     * @param \DateTime $fechaVence
     *
     * @return TurCotizacion
     */
    public function setFechaVence($fechaVence)
    {
        $this->fechaVence = $fechaVence;

        return $this;
    }

    /**
     * Get fechaVence
     *
     * @return \DateTime
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurCotizacion
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
     * Set codigoProspectoFk
     *
     * @param integer $codigoProspectoFk
     *
     * @return TurCotizacion
     */
    public function setCodigoProspectoFk($codigoProspectoFk)
    {
        $this->codigoProspectoFk = $codigoProspectoFk;

        return $this;
    }

    /**
     * Get codigoProspectoFk
     *
     * @return integer
     */
    public function getCodigoProspectoFk()
    {
        return $this->codigoProspectoFk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurCotizacion
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
     * Set codigoSectorFk
     *
     * @param integer $codigoSectorFk
     *
     * @return TurCotizacion
     */
    public function setCodigoSectorFk($codigoSectorFk)
    {
        $this->codigoSectorFk = $codigoSectorFk;

        return $this;
    }

    /**
     * Get codigoSectorFk
     *
     * @return integer
     */
    public function getCodigoSectorFk()
    {
        return $this->codigoSectorFk;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return TurCotizacion
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
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return TurCotizacion
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return TurCotizacion
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
     * Set horas
     *
     * @param integer $horas
     *
     * @return TurCotizacion
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
     * Set horasDiurnas
     *
     * @param integer $horasDiurnas
     *
     * @return TurCotizacion
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
     * @return TurCotizacion
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
     * Set vrTotalCosto
     *
     * @param float $vrTotalCosto
     *
     * @return TurCotizacion
     */
    public function setVrTotalCosto($vrTotalCosto)
    {
        $this->vrTotalCosto = $vrTotalCosto;

        return $this;
    }

    /**
     * Get vrTotalCosto
     *
     * @return float
     */
    public function getVrTotalCosto()
    {
        return $this->vrTotalCosto;
    }

    /**
     * Set vrTotalPrecioAjustado
     *
     * @param float $vrTotalPrecioAjustado
     *
     * @return TurCotizacion
     */
    public function setVrTotalPrecioAjustado($vrTotalPrecioAjustado)
    {
        $this->vrTotalPrecioAjustado = $vrTotalPrecioAjustado;

        return $this;
    }

    /**
     * Get vrTotalPrecioAjustado
     *
     * @return float
     */
    public function getVrTotalPrecioAjustado()
    {
        return $this->vrTotalPrecioAjustado;
    }

    /**
     * Set vrTotalPrecioMinimo
     *
     * @param float $vrTotalPrecioMinimo
     *
     * @return TurCotizacion
     */
    public function setVrTotalPrecioMinimo($vrTotalPrecioMinimo)
    {
        $this->vrTotalPrecioMinimo = $vrTotalPrecioMinimo;

        return $this;
    }

    /**
     * Get vrTotalPrecioMinimo
     *
     * @return float
     */
    public function getVrTotalPrecioMinimo()
    {
        return $this->vrTotalPrecioMinimo;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return TurCotizacion
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurCotizacion
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
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurCotizacion
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
     * Set prospectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProspecto $prospectoRel
     *
     * @return TurCotizacion
     */
    public function setProspectoRel(\Brasa\TurnoBundle\Entity\TurProspecto $prospectoRel = null)
    {
        $this->prospectoRel = $prospectoRel;

        return $this;
    }

    /**
     * Get prospectoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurProspecto
     */
    public function getProspectoRel()
    {
        return $this->prospectoRel;
    }

    /**
     * Set sectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSector $sectorRel
     *
     * @return TurCotizacion
     */
    public function setSectorRel(\Brasa\TurnoBundle\Entity\TurSector $sectorRel = null)
    {
        $this->sectorRel = $sectorRel;

        return $this;
    }

    /**
     * Get sectorRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurSector
     */
    public function getSectorRel()
    {
        return $this->sectorRel;
    }

    /**
     * Add cotizacionesDetallesCotizacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesCotizacionRel
     *
     * @return TurCotizacion
     */
    public function addCotizacionesDetallesCotizacionRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesCotizacionRel)
    {
        $this->cotizacionesDetallesCotizacionRel[] = $cotizacionesDetallesCotizacionRel;

        return $this;
    }

    /**
     * Remove cotizacionesDetallesCotizacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesCotizacionRel
     */
    public function removeCotizacionesDetallesCotizacionRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesCotizacionRel)
    {
        $this->cotizacionesDetallesCotizacionRel->removeElement($cotizacionesDetallesCotizacionRel);
    }

    /**
     * Get cotizacionesDetallesCotizacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCotizacionesDetallesCotizacionRel()
    {
        return $this->cotizacionesDetallesCotizacionRel;
    }

    /**
     * Add cotizacionesOtrosCotizacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionOtro $cotizacionesOtrosCotizacionRel
     *
     * @return TurCotizacion
     */
    public function addCotizacionesOtrosCotizacionRel(\Brasa\TurnoBundle\Entity\TurCotizacionOtro $cotizacionesOtrosCotizacionRel)
    {
        $this->cotizacionesOtrosCotizacionRel[] = $cotizacionesOtrosCotizacionRel;

        return $this;
    }

    /**
     * Remove cotizacionesOtrosCotizacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionOtro $cotizacionesOtrosCotizacionRel
     */
    public function removeCotizacionesOtrosCotizacionRel(\Brasa\TurnoBundle\Entity\TurCotizacionOtro $cotizacionesOtrosCotizacionRel)
    {
        $this->cotizacionesOtrosCotizacionRel->removeElement($cotizacionesOtrosCotizacionRel);
    }

    /**
     * Get cotizacionesOtrosCotizacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCotizacionesOtrosCotizacionRel()
    {
        return $this->cotizacionesOtrosCotizacionRel;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return TurCotizacion
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
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurCotizacion
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
