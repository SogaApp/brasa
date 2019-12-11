<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_cliente")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarClienteRepository")
 */
class CarCliente
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cliente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoClientePk;        
    
    /**
     * @ORM\Column(name="nit", type="string", length=15, nullable=false, unique=true)
     */
    private $nit;        
    
    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=1, nullable=true)
     */
    private $digitoVerificacion;             
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=200)
     */
    private $nombreCorto;      

    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */    
    private $codigoAsesorFk;    
    
    /**
     * @ORM\Column(name="codigo_forma_pago_fk", type="integer", nullable=true)
     */    
    private $codigoFormaPagoFk; 
    
    /**
     * @ORM\Column(name="plazo_pago", type="integer")
     */    
    private $plazoPago = 0;     
    
    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */
    private $direccion;   
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;         
    
    /**
     * @ORM\Column(name="telefono", type="string", length=40, nullable=true)
     */
    private $telefono;     
    
    /**
     * @ORM\Column(name="celular", type="string", length=40, nullable=true, nullable=true)
     */
    private $celular;    
        
    /**
     * @ORM\Column(name="fax", type="string", length=20, nullable=true, nullable=true)
     */
    private $fax;    
    
    /**
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenAsesor", inversedBy="carClientesAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")
     */
    protected $asesorRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenFormaPago", inversedBy="carClientesFormaPagoRel")
     * @ORM\JoinColumn(name="codigo_forma_pago_fk", referencedColumnName="codigo_forma_pago_pk")
     */
    protected $formaPagoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="carClientesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarCuentaCobrar", mappedBy="clienteRel")
     */
    protected $cuentaCobrarClientesRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarRecibo", mappedBy="clienteRel")
     */
    protected $recibosClienteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarNotaDebito", mappedBy="clienteRel")
     */
    protected $notasDebitosClienteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarNotaCredito", mappedBy="clienteRel")
     */
    protected $notasCreditosClienteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarAnticipo", mappedBy="clienteRel")
     */
    protected $anticiposClienteRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cuentaCobrarClientesRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recibosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notasDebitosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notasCreditosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->anticiposClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoClientePk
     *
     * @return integer
     */
    public function getCodigoClientePk()
    {
        return $this->codigoClientePk;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return CarCliente
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
     * @return CarCliente
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
     * @return CarCliente
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
     * Set codigoAsesorFk
     *
     * @param integer $codigoAsesorFk
     *
     * @return CarCliente
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
     * Set codigoFormaPagoFk
     *
     * @param integer $codigoFormaPagoFk
     *
     * @return CarCliente
     */
    public function setCodigoFormaPagoFk($codigoFormaPagoFk)
    {
        $this->codigoFormaPagoFk = $codigoFormaPagoFk;

        return $this;
    }

    /**
     * Get codigoFormaPagoFk
     *
     * @return integer
     */
    public function getCodigoFormaPagoFk()
    {
        return $this->codigoFormaPagoFk;
    }

    /**
     * Set plazoPago
     *
     * @param integer $plazoPago
     *
     * @return CarCliente
     */
    public function setPlazoPago($plazoPago)
    {
        $this->plazoPago = $plazoPago;

        return $this;
    }

    /**
     * Get plazoPago
     *
     * @return integer
     */
    public function getPlazoPago()
    {
        return $this->plazoPago;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return CarCliente
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
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return CarCliente
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
     * Set telefono
     *
     * @param string $telefono
     *
     * @return CarCliente
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
     * @return CarCliente
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
     * @return CarCliente
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
     * @return CarCliente
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
     * Set usuario
     *
     * @param string $usuario
     *
     * @return CarCliente
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
     * Set asesorRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenAsesor $asesorRel
     *
     * @return CarCliente
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
     * Set formaPagoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenFormaPago $formaPagoRel
     *
     * @return CarCliente
     */
    public function setFormaPagoRel(\Brasa\GeneralBundle\Entity\GenFormaPago $formaPagoRel = null)
    {
        $this->formaPagoRel = $formaPagoRel;

        return $this;
    }

    /**
     * Get formaPagoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenFormaPago
     */
    public function getFormaPagoRel()
    {
        return $this->formaPagoRel;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return CarCliente
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
     * Add cuentaCobrarClientesRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarClientesRel
     *
     * @return CarCliente
     */
    public function addCuentaCobrarClientesRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarClientesRel)
    {
        $this->cuentaCobrarClientesRel[] = $cuentaCobrarClientesRel;

        return $this;
    }

    /**
     * Remove cuentaCobrarClientesRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarClientesRel
     */
    public function removeCuentaCobrarClientesRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarClientesRel)
    {
        $this->cuentaCobrarClientesRel->removeElement($cuentaCobrarClientesRel);
    }

    /**
     * Get cuentaCobrarClientesRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentaCobrarClientesRel()
    {
        return $this->cuentaCobrarClientesRel;
    }

    /**
     * Add recibosClienteRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $recibosClienteRel
     *
     * @return CarCliente
     */
    public function addRecibosClienteRel(\Brasa\CarteraBundle\Entity\CarRecibo $recibosClienteRel)
    {
        $this->recibosClienteRel[] = $recibosClienteRel;

        return $this;
    }

    /**
     * Remove recibosClienteRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $recibosClienteRel
     */
    public function removeRecibosClienteRel(\Brasa\CarteraBundle\Entity\CarRecibo $recibosClienteRel)
    {
        $this->recibosClienteRel->removeElement($recibosClienteRel);
    }

    /**
     * Get recibosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecibosClienteRel()
    {
        return $this->recibosClienteRel;
    }

    /**
     * Add notasDebitosClienteRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebito $notasDebitosClienteRel
     *
     * @return CarCliente
     */
    public function addNotasDebitosClienteRel(\Brasa\CarteraBundle\Entity\CarNotaDebito $notasDebitosClienteRel)
    {
        $this->notasDebitosClienteRel[] = $notasDebitosClienteRel;

        return $this;
    }

    /**
     * Remove notasDebitosClienteRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebito $notasDebitosClienteRel
     */
    public function removeNotasDebitosClienteRel(\Brasa\CarteraBundle\Entity\CarNotaDebito $notasDebitosClienteRel)
    {
        $this->notasDebitosClienteRel->removeElement($notasDebitosClienteRel);
    }

    /**
     * Get notasDebitosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotasDebitosClienteRel()
    {
        return $this->notasDebitosClienteRel;
    }

    /**
     * Add notasCreditosClienteRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaCredito $notasCreditosClienteRel
     *
     * @return CarCliente
     */
    public function addNotasCreditosClienteRel(\Brasa\CarteraBundle\Entity\CarNotaCredito $notasCreditosClienteRel)
    {
        $this->notasCreditosClienteRel[] = $notasCreditosClienteRel;

        return $this;
    }

    /**
     * Remove notasCreditosClienteRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaCredito $notasCreditosClienteRel
     */
    public function removeNotasCreditosClienteRel(\Brasa\CarteraBundle\Entity\CarNotaCredito $notasCreditosClienteRel)
    {
        $this->notasCreditosClienteRel->removeElement($notasCreditosClienteRel);
    }

    /**
     * Get notasCreditosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotasCreditosClienteRel()
    {
        return $this->notasCreditosClienteRel;
    }

    /**
     * Add anticiposClienteRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarAnticipo $anticiposClienteRel
     *
     * @return CarCliente
     */
    public function addAnticiposClienteRel(\Brasa\CarteraBundle\Entity\CarAnticipo $anticiposClienteRel)
    {
        $this->anticiposClienteRel[] = $anticiposClienteRel;

        return $this;
    }

    /**
     * Remove anticiposClienteRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarAnticipo $anticiposClienteRel
     */
    public function removeAnticiposClienteRel(\Brasa\CarteraBundle\Entity\CarAnticipo $anticiposClienteRel)
    {
        $this->anticiposClienteRel->removeElement($anticiposClienteRel);
    }

    /**
     * Get anticiposClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnticiposClienteRel()
    {
        return $this->anticiposClienteRel;
    }
}
