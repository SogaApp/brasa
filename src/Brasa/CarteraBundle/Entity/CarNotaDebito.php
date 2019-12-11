<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_nota_debito")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarNotaDebitoRepository")
 */
class CarNotaDebito
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_nota_debito_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoNotaDebitoPk;        

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
     * @ORM\Column(name="codigo_nota_debito_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoNotaDebitoConceptoFk;
    
    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */    
    private $numero;
    
    /**
     * @ORM\Column(name="valor", type="float")
     */    
    private $valor = 0;
    
    /**
     * @ORM\Column(name="fecha_pago", type="date", nullable=true)
     */    
    private $fechaPago;
    
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
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="CarCliente", inversedBy="notasDebitosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarNotaDebitoDetalle", mappedBy="notaDebitoRel")
     */
    protected $notasDebitosDetallesNotaDebitoRel;

    /**
     * @ORM\ManyToOne(targetEntity="CarNotaDebitoConcepto", inversedBy="notasDebitosConceptoRel")
     * @ORM\JoinColumn(name="codigo_nota_debito_concepto_fk", referencedColumnName="codigo_nota_debito_concepto_pk")
     */
    protected $notaDebitoConceptoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCuenta", inversedBy="carNotasDebitosCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notasDebitosDetallesNotaDebitoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoNotaDebitoPk
     *
     * @return integer
     */
    public function getCodigoNotaDebitoPk()
    {
        return $this->codigoNotaDebitoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CarNotaDebito
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
     * @return CarNotaDebito
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
     * @return CarNotaDebito
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
     * Set codigoNotaDebitoConceptoFk
     *
     * @param integer $codigoNotaDebitoConceptoFk
     *
     * @return CarNotaDebito
     */
    public function setCodigoNotaDebitoConceptoFk($codigoNotaDebitoConceptoFk)
    {
        $this->codigoNotaDebitoConceptoFk = $codigoNotaDebitoConceptoFk;

        return $this;
    }

    /**
     * Get codigoNotaDebitoConceptoFk
     *
     * @return integer
     */
    public function getCodigoNotaDebitoConceptoFk()
    {
        return $this->codigoNotaDebitoConceptoFk;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return CarNotaDebito
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
     * Set valor
     *
     * @param float $valor
     *
     * @return CarNotaDebito
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set fechaPago
     *
     * @param \DateTime $fechaPago
     *
     * @return CarNotaDebito
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
     * Set estadoImpreso
     *
     * @param boolean $estadoImpreso
     *
     * @return CarNotaDebito
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
     * @return CarNotaDebito
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
     * @return CarNotaDebito
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
     * @return CarNotaDebito
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
     * @return CarNotaDebito
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
     * @return CarNotaDebito
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
     * Set clienteRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCliente $clienteRel
     *
     * @return CarNotaDebito
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
     * Add notasDebitosDetallesNotaDebitoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $notasDebitosDetallesNotaDebitoRel
     *
     * @return CarNotaDebito
     */
    public function addNotasDebitosDetallesNotaDebitoRel(\Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $notasDebitosDetallesNotaDebitoRel)
    {
        $this->notasDebitosDetallesNotaDebitoRel[] = $notasDebitosDetallesNotaDebitoRel;

        return $this;
    }

    /**
     * Remove notasDebitosDetallesNotaDebitoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $notasDebitosDetallesNotaDebitoRel
     */
    public function removeNotasDebitosDetallesNotaDebitoRel(\Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $notasDebitosDetallesNotaDebitoRel)
    {
        $this->notasDebitosDetallesNotaDebitoRel->removeElement($notasDebitosDetallesNotaDebitoRel);
    }

    /**
     * Get notasDebitosDetallesNotaDebitoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotasDebitosDetallesNotaDebitoRel()
    {
        return $this->notasDebitosDetallesNotaDebitoRel;
    }

    /**
     * Set notaDebitoConceptoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebitoConcepto $notaDebitoConceptoRel
     *
     * @return CarNotaDebito
     */
    public function setNotaDebitoConceptoRel(\Brasa\CarteraBundle\Entity\CarNotaDebitoConcepto $notaDebitoConceptoRel = null)
    {
        $this->notaDebitoConceptoRel = $notaDebitoConceptoRel;

        return $this;
    }

    /**
     * Get notaDebitoConceptoRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarNotaDebitoConcepto
     */
    public function getNotaDebitoConceptoRel()
    {
        return $this->notaDebitoConceptoRel;
    }

    /**
     * Set cuentaRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCuenta $cuentaRel
     *
     * @return CarNotaDebito
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
}
