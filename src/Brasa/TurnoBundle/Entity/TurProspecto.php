<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_prospecto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurProspectoRepository")
 */
class TurProspecto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_prospecto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProspectoPk;    
    
    /**
     * @ORM\Column(name="nit", type="string", length=15, nullable=false, unique=true)
     */
    private $nit;               
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=50)
     */
    private $nombreCorto;                         
    
    /**
     * @ORM\Column(name="estrato", type="string", length=5, nullable=true)
     */
    private $estrato;                
    
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
     * @ORM\OneToMany(targetEntity="TurCotizacion", mappedBy="prospectoRel")
     */
    protected $cotizacionesProspectoRel; 

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cotizacionesProspectoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoProspectoPk
     *
     * @return integer
     */
    public function getCodigoProspectoPk()
    {
        return $this->codigoProspectoPk;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return TurProspecto
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
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return TurProspecto
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
     * Set estrato
     *
     * @param string $estrato
     *
     * @return TurProspecto
     */
    public function setEstrato($estrato)
    {
        $this->estrato = $estrato;

        return $this;
    }

    /**
     * Get estrato
     *
     * @return string
     */
    public function getEstrato()
    {
        return $this->estrato;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     *
     * @return TurProspecto
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
     * @return TurProspecto
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
     * @return TurProspecto
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurProspecto
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
     * Add cotizacionesProspectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesProspectoRel
     *
     * @return TurProspecto
     */
    public function addCotizacionesProspectoRel(\Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesProspectoRel)
    {
        $this->cotizacionesProspectoRel[] = $cotizacionesProspectoRel;

        return $this;
    }

    /**
     * Remove cotizacionesProspectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesProspectoRel
     */
    public function removeCotizacionesProspectoRel(\Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesProspectoRel)
    {
        $this->cotizacionesProspectoRel->removeElement($cotizacionesProspectoRel);
    }

    /**
     * Get cotizacionesProspectoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCotizacionesProspectoRel()
    {
        return $this->cotizacionesProspectoRel;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurProspecto
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
