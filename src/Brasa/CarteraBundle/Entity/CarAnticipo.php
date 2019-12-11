<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_anticipo")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarAnticipoRepository")
 */
class CarAnticipo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_anticipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoAnticipoPk;        

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */     
    private $codigoClienteFk;
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="integer", nullable=true)
     */    
    private $codigoCuentaFk;
    
    
    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */    
    private $codigoAsesorFk;     
    
    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */    
    private $numero;
    
    /**
     * @ORM\Column(name="fecha_pago", type="date", nullable=true)
     */    
    private $fechaPago;
    
    /**
     * @ORM\Column(name="vr_total_descueto", type="float")
     */    
    private $vrTotalDescuento = 0;
    
    /**
     * @ORM\Column(name="vr_total_ajuste_peso", type="float")
     */    
    private $vrTotalAjustePeso = 0;
    
    /**
     * @ORM\Column(name="vr_total_rete_ica", type="float")
     */    
    private $vrTotalReteIca = 0;
    
    /**
     * @ORM\Column(name="vr_total_rete_iva", type="float")
     */    
    private $vrTotalReteIva = 0;
    
    /**
     * @ORM\Column(name="vr_total_rete_fuente", type="float")
     */    
    private $vrTotalReteFuente = 0;
    
    /**
     * @ORM\Column(name="vr_anticipo", type="float")
     */    
    private $vrAnticipo = 0;
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */    
    private $vrTotal = 0;      

    /**
     * @ORM\Column(name="vr_total_pago", type="float")
     */    
    private $vrTotalPago = 0;    
    
    /**     
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpreso = 0;
    
    /**     
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = 0;
    
    /**     
     * @ORM\Column(name="estado_exportado", type="boolean")
     */    
    private $estadoExportado = 0;
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=600, nullable=true)
     */    
    private $comentarios;
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;
    
    /**     
     * @ORM\Column(name="estado_impreso_anticipado", type="boolean")
     */    
    private $estadoImpresoAnticipado = false;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CarCliente", inversedBy="anticiposClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCuenta", inversedBy="carAnticiposCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;
         
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenAsesor", inversedBy="carAnticiposAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")
     */
    protected $asesorRel;

    /**
     * @ORM\OneToMany(targetEntity="CarAnticipoDetalle", mappedBy="anticipoRel")
     */
    protected $anticiposDetallesAnticiposRel;
    
   
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->anticiposDetallesAnticiposRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoAnticipoPk
     *
     * @return integer
     */
    public function getCodigoAnticipoPk()
    {
        return $this->codigoAnticipoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CarAnticipo
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
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return CarAnticipo
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
     * Set codigoCuentaFk
     *
     * @param integer $codigoCuentaFk
     *
     * @return CarAnticipo
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return integer
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * Set codigoAsesorFk
     *
     * @param integer $codigoAsesorFk
     *
     * @return CarAnticipo
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
     * Set numero
     *
     * @param string $numero
     *
     * @return CarAnticipo
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set fechaPago
     *
     * @param \DateTime $fechaPago
     *
     * @return CarAnticipo
     */
    public function setFechaPago($fechaPago)
    {
        $this->fechaPago = $fechaPago;

        return $this;
    }

    /**
     * Get fechaPago
     *
     * @return \DateTime
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * Set vrTotalDescuento
     *
     * @param float $vrTotalDescuento
     *
     * @return CarAnticipo
     */
    public function setVrTotalDescuento($vrTotalDescuento)
    {
        $this->vrTotalDescuento = $vrTotalDescuento;

        return $this;
    }

    /**
     * Get vrTotalDescuento
     *
     * @return float
     */
    public function getVrTotalDescuento()
    {
        return $this->vrTotalDescuento;
    }

    /**
     * Set vrTotalAjustePeso
     *
     * @param float $vrTotalAjustePeso
     *
     * @return CarAnticipo
     */
    public function setVrTotalAjustePeso($vrTotalAjustePeso)
    {
        $this->vrTotalAjustePeso = $vrTotalAjustePeso;

        return $this;
    }

    /**
     * Get vrTotalAjustePeso
     *
     * @return float
     */
    public function getVrTotalAjustePeso()
    {
        return $this->vrTotalAjustePeso;
    }

    /**
     * Set vrTotalReteIca
     *
     * @param float $vrTotalReteIca
     *
     * @return CarAnticipo
     */
    public function setVrTotalReteIca($vrTotalReteIca)
    {
        $this->vrTotalReteIca = $vrTotalReteIca;

        return $this;
    }

    /**
     * Get vrTotalReteIca
     *
     * @return float
     */
    public function getVrTotalReteIca()
    {
        return $this->vrTotalReteIca;
    }

    /**
     * Set vrTotalReteIva
     *
     * @param float $vrTotalReteIva
     *
     * @return CarAnticipo
     */
    public function setVrTotalReteIva($vrTotalReteIva)
    {
        $this->vrTotalReteIva = $vrTotalReteIva;

        return $this;
    }

    /**
     * Get vrTotalReteIva
     *
     * @return float
     */
    public function getVrTotalReteIva()
    {
        return $this->vrTotalReteIva;
    }

    /**
     * Set vrTotalReteFuente
     *
     * @param float $vrTotalReteFuente
     *
     * @return CarAnticipo
     */
    public function setVrTotalReteFuente($vrTotalReteFuente)
    {
        $this->vrTotalReteFuente = $vrTotalReteFuente;

        return $this;
    }

    /**
     * Get vrTotalReteFuente
     *
     * @return float
     */
    public function getVrTotalReteFuente()
    {
        return $this->vrTotalReteFuente;
    }

    /**
     * Set vrAnticipo
     *
     * @param float $vrAnticipo
     *
     * @return CarAnticipo
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
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return CarAnticipo
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
     * Set vrTotalPago
     *
     * @param float $vrTotalPago
     *
     * @return CarAnticipo
     */
    public function setVrTotalPago($vrTotalPago)
    {
        $this->vrTotalPago = $vrTotalPago;

        return $this;
    }

    /**
     * Get vrTotalPago
     *
     * @return float
     */
    public function getVrTotalPago()
    {
        return $this->vrTotalPago;
    }

    /**
     * Set estadoImpreso
     *
     * @param boolean $estadoImpreso
     *
     * @return CarAnticipo
     */
    public function setEstadoImpreso($estadoImpreso)
    {
        $this->estadoImpreso = $estadoImpreso;

        return $this;
    }

    /**
     * Get estadoImpreso
     *
     * @return boolean
     */
    public function getEstadoImpreso()
    {
        return $this->estadoImpreso;
    }

    /**
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     *
     * @return CarAnticipo
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
     * Set estadoExportado
     *
     * @param boolean $estadoExportado
     *
     * @return CarAnticipo
     */
    public function setEstadoExportado($estadoExportado)
    {
        $this->estadoExportado = $estadoExportado;

        return $this;
    }

    /**
     * Get estadoExportado
     *
     * @return boolean
     */
    public function getEstadoExportado()
    {
        return $this->estadoExportado;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return CarAnticipo
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return CarAnticipo
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
     * Set usuario
     *
     * @param string $usuario
     *
     * @return CarAnticipo
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
     * Set estadoImpresoAnticipado
     *
     * @param boolean $estadoImpresoAnticipado
     *
     * @return CarAnticipo
     */
    public function setEstadoImpresoAnticipado($estadoImpresoAnticipado)
    {
        $this->estadoImpresoAnticipado = $estadoImpresoAnticipado;

        return $this;
    }

    /**
     * Get estadoImpresoAnticipado
     *
     * @return boolean
     */
    public function getEstadoImpresoAnticipado()
    {
        return $this->estadoImpresoAnticipado;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCliente $clienteRel
     *
     * @return CarAnticipo
     */
    public function setClienteRel(\Brasa\CarteraBundle\Entity\CarCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Set cuentaRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCuenta $cuentaRel
     *
     * @return CarAnticipo
     */
    public function setCuentaRel(\Brasa\GeneralBundle\Entity\GenCuenta $cuentaRel = null)
    {
        $this->cuentaRel = $cuentaRel;

        return $this;
    }

    /**
     * Get cuentaRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCuenta
     */
    public function getCuentaRel()
    {
        return $this->cuentaRel;
    }

    /**
     * Set asesorRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenAsesor $asesorRel
     *
     * @return CarAnticipo
     */
    public function setAsesorRel(\Brasa\GeneralBundle\Entity\GenAsesor $asesorRel = null)
    {
        $this->asesorRel = $asesorRel;

        return $this;
    }

    /**
     * Get asesorRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenAsesor
     */
    public function getAsesorRel()
    {
        return $this->asesorRel;
    }

    /**
     * Add anticiposDetallesAnticiposRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarAnticipoDetalle $anticiposDetallesAnticiposRel
     *
     * @return CarAnticipo
     */
    public function addAnticiposDetallesAnticiposRel(\Brasa\CarteraBundle\Entity\CarAnticipoDetalle $anticiposDetallesAnticiposRel)
    {
        $this->anticiposDetallesAnticiposRel[] = $anticiposDetallesAnticiposRel;

        return $this;
    }

    /**
     * Remove anticiposDetallesAnticiposRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarAnticipoDetalle $anticiposDetallesAnticiposRel
     */
    public function removeAnticiposDetallesAnticiposRel(\Brasa\CarteraBundle\Entity\CarAnticipoDetalle $anticiposDetallesAnticiposRel)
    {
        $this->anticiposDetallesAnticiposRel->removeElement($anticiposDetallesAnticiposRel);
    }

    /**
     * Get anticiposDetallesAnticiposRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnticiposDetallesAnticiposRel()
    {
        return $this->anticiposDetallesAnticiposRel;
    }
}
