<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_servicio")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurServicioRepository")
 */
class TurServicio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_servicio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoServicioPk;                      
    
    /**
     * @ORM\Column(name="fecha_generacion", type="date", nullable=true)
     */    
    private $fechaGeneracion;    
    
    /**
     * @ORM\Column(name="soporte", type="string", length=30, nullable=true)
     */
    private $soporte;     
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;    
    
    /**
     * @ORM\Column(name="codigo_sector_fk", type="integer", nullable=true)
     */    
    private $codigoSectorFk;                            
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;                 
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    
    
    
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
     * @ORM\Column(name="vr_total_otros", type="float")
     */
    private $vrTotalOtros = 0;    
    
    /**
     * @ORM\Column(name="vr_total_servicio", type="float")
     */
    private $vrTotalServicio = 0;     
    
    /**
     * @ORM\Column(name="vr_total_precio_ajustado", type="float")
     */
    private $vrTotalPrecioAjustado = 0;            

    /**
     * @ORM\Column(name="vr_total_precio_minimo", type="float")
     */
    private $vrTotalPrecioMinimo = 0;        

    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0; 

    /**
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $vrIva = 0;    
    
    /**
     * @ORM\Column(name="vr_base_aiu", type="float")
     */
    private $vrBaseAiu = 0; 
    
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
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="serviciosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurSector", inversedBy="serviciosSectorRel")
     * @ORM\JoinColumn(name="codigo_sector_fk", referencedColumnName="codigo_sector_pk")
     */
    protected $sectorRel;         
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalle", mappedBy="servicioRel", cascade={"persist", "remove"})
     */
    protected $serviciosDetallesServicioRel; 

    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalleConcepto", mappedBy="servicioRel", cascade={"persist", "remove"})
     */
    protected $serviciosDetallesConceptosServicioRel;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->serviciosDetallesServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosDetallesConceptosServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoServicioPk
     *
     * @return integer
     */
    public function getCodigoServicioPk()
    {
        return $this->codigoServicioPk;
    }

    /**
     * Set fechaGeneracion
     *
     * @param \DateTime $fechaGeneracion
     *
     * @return TurServicio
     */
    public function setFechaGeneracion($fechaGeneracion)
    {
        $this->fechaGeneracion = $fechaGeneracion;

        return $this;
    }

    /**
     * Get fechaGeneracion
     *
     * @return \DateTime
     */
    public function getFechaGeneracion()
    {
        return $this->fechaGeneracion;
    }

    /**
     * Set soporte
     *
     * @param string $soporte
     *
     * @return TurServicio
     */
    public function setSoporte($soporte)
    {
        $this->soporte = $soporte;

        return $this;
    }

    /**
     * Get soporte
     *
     * @return string
     */
    public function getSoporte()
    {
        return $this->soporte;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurServicio
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
     * Set codigoSectorFk
     *
     * @param integer $codigoSectorFk
     *
     * @return TurServicio
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
     * @return TurServicio
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return TurServicio
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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return TurServicio
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
     * @return TurServicio
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
     * @return TurServicio
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
     * @return TurServicio
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
     * @return TurServicio
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
     * Set vrTotalOtros
     *
     * @param float $vrTotalOtros
     *
     * @return TurServicio
     */
    public function setVrTotalOtros($vrTotalOtros)
    {
        $this->vrTotalOtros = $vrTotalOtros;

        return $this;
    }

    /**
     * Get vrTotalOtros
     *
     * @return float
     */
    public function getVrTotalOtros()
    {
        return $this->vrTotalOtros;
    }

    /**
     * Set vrTotalServicio
     *
     * @param float $vrTotalServicio
     *
     * @return TurServicio
     */
    public function setVrTotalServicio($vrTotalServicio)
    {
        $this->vrTotalServicio = $vrTotalServicio;

        return $this;
    }

    /**
     * Get vrTotalServicio
     *
     * @return float
     */
    public function getVrTotalServicio()
    {
        return $this->vrTotalServicio;
    }

    /**
     * Set vrTotalPrecioAjustado
     *
     * @param float $vrTotalPrecioAjustado
     *
     * @return TurServicio
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
     * @return TurServicio
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
     * @return TurServicio
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
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurServicio
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

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurServicio
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
     * @return TurServicio
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
     * Set sectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSector $sectorRel
     *
     * @return TurServicio
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
     * Add serviciosDetallesServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesServicioRel
     *
     * @return TurServicio
     */
    public function addServiciosDetallesServicioRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesServicioRel)
    {
        $this->serviciosDetallesServicioRel[] = $serviciosDetallesServicioRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesServicioRel
     */
    public function removeServiciosDetallesServicioRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesServicioRel)
    {
        $this->serviciosDetallesServicioRel->removeElement($serviciosDetallesServicioRel);
    }

    /**
     * Get serviciosDetallesServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesServicioRel()
    {
        return $this->serviciosDetallesServicioRel;
    }

    /**
     * Add serviciosDetallesConceptosServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosServicioRel
     *
     * @return TurServicio
     */
    public function addServiciosDetallesConceptosServicioRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosServicioRel)
    {
        $this->serviciosDetallesConceptosServicioRel[] = $serviciosDetallesConceptosServicioRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesConceptosServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosServicioRel
     */
    public function removeServiciosDetallesConceptosServicioRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosServicioRel)
    {
        $this->serviciosDetallesConceptosServicioRel->removeElement($serviciosDetallesConceptosServicioRel);
    }

    /**
     * Get serviciosDetallesConceptosServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesConceptosServicioRel()
    {
        return $this->serviciosDetallesConceptosServicioRel;
    }

    /**
     * Set vrSubtotal
     *
     * @param float $vrSubtotal
     *
     * @return TurServicio
     */
    public function setVrSubtotal($vrSubtotal)
    {
        $this->vrSubtotal = $vrSubtotal;

        return $this;
    }

    /**
     * Get vrSubtotal
     *
     * @return float
     */
    public function getVrSubtotal()
    {
        return $this->vrSubtotal;
    }

    /**
     * Set vrBaseAiu
     *
     * @param float $vrBaseAiu
     *
     * @return TurServicio
     */
    public function setVrBaseAiu($vrBaseAiu)
    {
        $this->vrBaseAiu = $vrBaseAiu;

        return $this;
    }

    /**
     * Get vrBaseAiu
     *
     * @return float
     */
    public function getVrBaseAiu()
    {
        return $this->vrBaseAiu;
    }

    /**
     * Set vrIva
     *
     * @param float $vrIva
     *
     * @return TurServicio
     */
    public function setVrIva($vrIva)
    {
        $this->vrIva = $vrIva;

        return $this;
    }

    /**
     * Get vrIva
     *
     * @return float
     */
    public function getVrIva()
    {
        return $this->vrIva;
    }
}
