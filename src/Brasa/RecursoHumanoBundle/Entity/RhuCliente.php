<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_cliente")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuClienteRepository")
 */
class RhuCliente
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
     * @ORM\Column(name="nombre_corto", type="string", length=50)
     */
    private $nombreCorto;                             
    
    /**
     * @ORM\Column(name="plazo_pago", type="integer")
     */    
    private $plazoPago = 0;    
    
    /**
     * @ORM\Column(name="codigo_forma_pago_fk", type="integer", nullable=true)
     */    
    private $codigoFormaPagoFk;     
    
    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="barrio", type="string", length=120, nullable=true)
     */
    private $barrio;    
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;         
    
    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;     
    
    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true, nullable=true)
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
     * @ORM\Column(name="gerente", type="string", length=80, nullable=true)
     */
    private $gerente;    
    
    /**
     * @ORM\Column(name="calular_gerente", type="string", length=20, nullable=true)
     */
    private $celularGerente;  
    
    /**
     * @ORM\Column(name="financiero", type="string", length=80, nullable=true)
     */
    private $financiero;    
    
    /**
     * @ORM\Column(name="calular_financiero", type="string", length=20, nullable=true)
     */
    private $celularFinanciero;     
    
    /**
     * @ORM\Column(name="contacto", type="string", length=80, nullable=true)
     */
    private $contacto;    

    /**
     * @ORM\Column(name="calular_contacto", type="string", length=20, nullable=true)
     */
    private $celularContacto;     

    /**
     * @ORM\Column(name="telefono_contacto", type="string", length=20, nullable=true)
     */
    private $telefonoContacto;    
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;                            
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenFormaPago", inversedBy="rhuClientesFormaPagoRel")
     * @ORM\JoinColumn(name="codigo_forma_pago_fk", referencedColumnName="codigo_forma_pago_pk")
     */
    protected $formaPagoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuClientesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;         

    /**
     * @ORM\OneToMany(targetEntity="RhuCentroCosto", mappedBy="clienteRel")
     */
    protected $centrosCostosClienteRel;     
    

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
     * @return RhuCliente
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
     * @return RhuCliente
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
     * @return RhuCliente
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
     * Set plazoPago
     *
     * @param integer $plazoPago
     *
     * @return RhuCliente
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
     * Set codigoFormaPagoFk
     *
     * @param integer $codigoFormaPagoFk
     *
     * @return RhuCliente
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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return RhuCliente
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
     * Set barrio
     *
     * @param string $barrio
     *
     * @return RhuCliente
     */
    public function setBarrio($barrio)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return string
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return RhuCliente
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
     * @return RhuCliente
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
     * @return RhuCliente
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
     * @return RhuCliente
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
     * @return RhuCliente
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
     * Set gerente
     *
     * @param string $gerente
     *
     * @return RhuCliente
     */
    public function setGerente($gerente)
    {
        $this->gerente = $gerente;

        return $this;
    }

    /**
     * Get gerente
     *
     * @return string
     */
    public function getGerente()
    {
        return $this->gerente;
    }

    /**
     * Set celularGerente
     *
     * @param string $celularGerente
     *
     * @return RhuCliente
     */
    public function setCelularGerente($celularGerente)
    {
        $this->celularGerente = $celularGerente;

        return $this;
    }

    /**
     * Get celularGerente
     *
     * @return string
     */
    public function getCelularGerente()
    {
        return $this->celularGerente;
    }

    /**
     * Set financiero
     *
     * @param string $financiero
     *
     * @return RhuCliente
     */
    public function setFinanciero($financiero)
    {
        $this->financiero = $financiero;

        return $this;
    }

    /**
     * Get financiero
     *
     * @return string
     */
    public function getFinanciero()
    {
        return $this->financiero;
    }

    /**
     * Set celularFinanciero
     *
     * @param string $celularFinanciero
     *
     * @return RhuCliente
     */
    public function setCelularFinanciero($celularFinanciero)
    {
        $this->celularFinanciero = $celularFinanciero;

        return $this;
    }

    /**
     * Get celularFinanciero
     *
     * @return string
     */
    public function getCelularFinanciero()
    {
        return $this->celularFinanciero;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     *
     * @return RhuCliente
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
     * Set celularContacto
     *
     * @param string $celularContacto
     *
     * @return RhuCliente
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
     * Set telefonoContacto
     *
     * @param string $telefonoContacto
     *
     * @return RhuCliente
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
     * Set usuario
     *
     * @param string $usuario
     *
     * @return RhuCliente
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
     * @return RhuCliente
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
     * Set formaPagoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenFormaPago $formaPagoRel
     *
     * @return RhuCliente
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
     * @return RhuCliente
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
     * Constructor
     */
    public function __construct()
    {
        $this->centrosCostosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add centrosCostosClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosClienteRel
     *
     * @return RhuCliente
     */
    public function addCentrosCostosClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosClienteRel)
    {
        $this->centrosCostosClienteRel[] = $centrosCostosClienteRel;

        return $this;
    }

    /**
     * Remove centrosCostosClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosClienteRel
     */
    public function removeCentrosCostosClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosClienteRel)
    {
        $this->centrosCostosClienteRel->removeElement($centrosCostosClienteRel);
    }

    /**
     * Get centrosCostosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentrosCostosClienteRel()
    {
        return $this->centrosCostosClienteRel;
    }
}
