<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_asesor")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenAsesorRepository")
 */
class GenAsesor
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_asesor_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoAsesorPk;    

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=15, nullable=false)
     */
    private $numeroIdentificacion; 
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */      
    private $nombre;

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
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(name="estado", type="boolean")
     */    
    private $estado = false;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\CarteraBundle\Entity\CarCliente", mappedBy="asesorRel")
     */
    protected $carClientesAsesorRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\CarteraBundle\Entity\CarCuentaCobrar", mappedBy="asesorRel")
     */
    protected $carCuentasCobrarAsesorRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\CarteraBundle\Entity\CarRecibo", mappedBy="asesorRel")
     */
    protected $carRecibosAsesorRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiCliente", mappedBy="asesorRel")
     */
    protected $afiClientesAsesorRel;     

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCliente", mappedBy="asesorRel")
     */
    protected $turClientesAsesorRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\CarteraBundle\Entity\CarAnticipo", mappedBy="asesorRel")
     */
    protected $carAnticiposAsesorRel;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->carClientesAsesorRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carCuentasCobrarAsesorRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carRecibosAsesorRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->afiClientesAsesorRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->turClientesAsesorRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carAnticiposAsesorRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoAsesorPk
     *
     * @return integer
     */
    public function getCodigoAsesorPk()
    {
        return $this->codigoAsesorPk;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return GenAsesor
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenAsesor
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
     * @return GenAsesor
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
     * @return GenAsesor
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
     * @return GenAsesor
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
     * Set email
     *
     * @param string $email
     *
     * @return GenAsesor
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
     * Add carClientesAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCliente $carClientesAsesorRel
     *
     * @return GenAsesor
     */
    public function addCarClientesAsesorRel(\Brasa\CarteraBundle\Entity\CarCliente $carClientesAsesorRel)
    {
        $this->carClientesAsesorRel[] = $carClientesAsesorRel;

        return $this;
    }

    /**
     * Remove carClientesAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCliente $carClientesAsesorRel
     */
    public function removeCarClientesAsesorRel(\Brasa\CarteraBundle\Entity\CarCliente $carClientesAsesorRel)
    {
        $this->carClientesAsesorRel->removeElement($carClientesAsesorRel);
    }

    /**
     * Get carClientesAsesorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarClientesAsesorRel()
    {
        return $this->carClientesAsesorRel;
    }

    /**
     * Add carCuentasCobrarAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $carCuentasCobrarAsesorRel
     *
     * @return GenAsesor
     */
    public function addCarCuentasCobrarAsesorRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $carCuentasCobrarAsesorRel)
    {
        $this->carCuentasCobrarAsesorRel[] = $carCuentasCobrarAsesorRel;

        return $this;
    }

    /**
     * Remove carCuentasCobrarAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $carCuentasCobrarAsesorRel
     */
    public function removeCarCuentasCobrarAsesorRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $carCuentasCobrarAsesorRel)
    {
        $this->carCuentasCobrarAsesorRel->removeElement($carCuentasCobrarAsesorRel);
    }

    /**
     * Get carCuentasCobrarAsesorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarCuentasCobrarAsesorRel()
    {
        return $this->carCuentasCobrarAsesorRel;
    }

    /**
     * Add carRecibosAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $carRecibosAsesorRel
     *
     * @return GenAsesor
     */
    public function addCarRecibosAsesorRel(\Brasa\CarteraBundle\Entity\CarRecibo $carRecibosAsesorRel)
    {
        $this->carRecibosAsesorRel[] = $carRecibosAsesorRel;

        return $this;
    }

    /**
     * Remove carRecibosAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $carRecibosAsesorRel
     */
    public function removeCarRecibosAsesorRel(\Brasa\CarteraBundle\Entity\CarRecibo $carRecibosAsesorRel)
    {
        $this->carRecibosAsesorRel->removeElement($carRecibosAsesorRel);
    }

    /**
     * Get carRecibosAsesorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarRecibosAsesorRel()
    {
        return $this->carRecibosAsesorRel;
    }

    /**
     * Add afiClientesAsesorRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesAsesorRel
     *
     * @return GenAsesor
     */
    public function addAfiClientesAsesorRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesAsesorRel)
    {
        $this->afiClientesAsesorRel[] = $afiClientesAsesorRel;

        return $this;
    }

    /**
     * Remove afiClientesAsesorRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesAsesorRel
     */
    public function removeAfiClientesAsesorRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesAsesorRel)
    {
        $this->afiClientesAsesorRel->removeElement($afiClientesAsesorRel);
    }

    /**
     * Get afiClientesAsesorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiClientesAsesorRel()
    {
        return $this->afiClientesAsesorRel;
    }

    /**
     * Add turClientesAsesorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesAsesorRel
     *
     * @return GenAsesor
     */
    public function addTurClientesAsesorRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesAsesorRel)
    {
        $this->turClientesAsesorRel[] = $turClientesAsesorRel;

        return $this;
    }

    /**
     * Remove turClientesAsesorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesAsesorRel
     */
    public function removeTurClientesAsesorRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesAsesorRel)
    {
        $this->turClientesAsesorRel->removeElement($turClientesAsesorRel);
    }

    /**
     * Get turClientesAsesorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurClientesAsesorRel()
    {
        return $this->turClientesAsesorRel;
    }

    /**
     * Add carAnticiposAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarAnticipo $carAnticiposAsesorRel
     *
     * @return GenAsesor
     */
    public function addCarAnticiposAsesorRel(\Brasa\CarteraBundle\Entity\CarAnticipo $carAnticiposAsesorRel)
    {
        $this->carAnticiposAsesorRel[] = $carAnticiposAsesorRel;

        return $this;
    }

    /**
     * Remove carAnticiposAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarAnticipo $carAnticiposAsesorRel
     */
    public function removeCarAnticiposAsesorRel(\Brasa\CarteraBundle\Entity\CarAnticipo $carAnticiposAsesorRel)
    {
        $this->carAnticiposAsesorRel->removeElement($carAnticiposAsesorRel);
    }

    /**
     * Get carAnticiposAsesorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarAnticiposAsesorRel()
    {
        return $this->carAnticiposAsesorRel;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     *
     * @return GenAsesor
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean
     */
    public function getEstado()
    {
        return $this->estado;
    }
}
