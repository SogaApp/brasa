<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_cliente_direccion")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurClienteDireccionRepository")
 */
class TurClienteDireccion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cliente_direccion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoClienteDireccionPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer")
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer")
     */
    private $codigoCiudadFk;    

    /**
     * @ORM\Column(name="barrio", type="string", length=100, nullable=true)
     */
    private $barrio;       

    /**
     * @ORM\Column(name="telefono", type="string", length=50, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="clientesDireccionesClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;     

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="turClientesDireccionesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;         

    /**
     * @ORM\OneToMany(targetEntity="TurFactura", mappedBy="clienteDireccionRel")
     */
    protected $facturasClienteDireccionRel; 

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasClienteDireccionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoClienteDireccionPk
     *
     * @return integer
     */
    public function getCodigoClienteDireccionPk()
    {
        return $this->codigoClienteDireccionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurClienteDireccion
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
     * @return TurClienteDireccion
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
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurClienteDireccion
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
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return TurClienteDireccion
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
     * Set barrio
     *
     * @param string $barrio
     *
     * @return TurClienteDireccion
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
     * Set telefono
     *
     * @param string $telefono
     *
     * @return TurClienteDireccion
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
     * Set email
     *
     * @param string $email
     *
     * @return TurClienteDireccion
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
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurClienteDireccion
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
     * @return TurClienteDireccion
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
     * Add facturasClienteDireccionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturasClienteDireccionRel
     *
     * @return TurClienteDireccion
     */
    public function addFacturasClienteDireccionRel(\Brasa\TurnoBundle\Entity\TurFactura $facturasClienteDireccionRel)
    {
        $this->facturasClienteDireccionRel[] = $facturasClienteDireccionRel;

        return $this;
    }

    /**
     * Remove facturasClienteDireccionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturasClienteDireccionRel
     */
    public function removeFacturasClienteDireccionRel(\Brasa\TurnoBundle\Entity\TurFactura $facturasClienteDireccionRel)
    {
        $this->facturasClienteDireccionRel->removeElement($facturasClienteDireccionRel);
    }

    /**
     * Get facturasClienteDireccionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasClienteDireccionRel()
    {
        return $this->facturasClienteDireccionRel;
    }
}
