<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_entidad_entrenamiento")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiEntidadEntrenamientoRepository")
 */
class AfiEntidadEntrenamiento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_entrenamiento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadEntrenamientoPk;                   
    
    /**
     * @ORM\Column(name="nit", type="string", length=15, nullable=false)
     */
    private $nit;        
    
    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=1, nullable=true)
     */
    private $digitoVerificacion;     
    
    /**
     * @ORM\Column(name="nombreCorto", type="string", length=50)
     */
    private $nombreCorto;                             
      
    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */
    private $direccion;          
    
    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;     
    
    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true, nullable=true)
     */
    private $celular;            
    
    /**
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;             
    
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
     * @ORM\OneToMany(targetEntity="AfiCurso", mappedBy="entidadEntrenamientoRel")
     */
    protected $cursosEntidadEntrenamientoRel;     

    /**
     * @ORM\OneToMany(targetEntity="AfiEntidadEntrenamientoCosto", mappedBy="entidadEntrenamientoRel")
     */
    protected $entidadesEntrenamientosCostosEntidadEntrenamientoRel;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cursosEntidadEntrenamientoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->entidadesEntrenamientosCostosEntidadEntrenamientoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEntidadEntrenamientoPk
     *
     * @return integer
     */
    public function getCodigoEntidadEntrenamientoPk()
    {
        return $this->codigoEntidadEntrenamientoPk;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return AfiEntidadEntrenamiento
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
     * @return AfiEntidadEntrenamiento
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
     * @return AfiEntidadEntrenamiento
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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return AfiEntidadEntrenamiento
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
     * @return AfiEntidadEntrenamiento
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
     * @return AfiEntidadEntrenamiento
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
     * @return AfiEntidadEntrenamiento
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
     * Set contacto
     *
     * @param string $contacto
     *
     * @return AfiEntidadEntrenamiento
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
     * @return AfiEntidadEntrenamiento
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
     * @return AfiEntidadEntrenamiento
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
     * @return AfiEntidadEntrenamiento
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
     * @return AfiEntidadEntrenamiento
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
     * Add cursosEntidadEntrenamientoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCurso $cursosEntidadEntrenamientoRel
     *
     * @return AfiEntidadEntrenamiento
     */
    public function addCursosEntidadEntrenamientoRel(\Brasa\AfiliacionBundle\Entity\AfiCurso $cursosEntidadEntrenamientoRel)
    {
        $this->cursosEntidadEntrenamientoRel[] = $cursosEntidadEntrenamientoRel;

        return $this;
    }

    /**
     * Remove cursosEntidadEntrenamientoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCurso $cursosEntidadEntrenamientoRel
     */
    public function removeCursosEntidadEntrenamientoRel(\Brasa\AfiliacionBundle\Entity\AfiCurso $cursosEntidadEntrenamientoRel)
    {
        $this->cursosEntidadEntrenamientoRel->removeElement($cursosEntidadEntrenamientoRel);
    }

    /**
     * Get cursosEntidadEntrenamientoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCursosEntidadEntrenamientoRel()
    {
        return $this->cursosEntidadEntrenamientoRel;
    }

    /**
     * Add entidadesEntrenamientosCostosEntidadEntrenamientoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamientoCosto $entidadesEntrenamientosCostosEntidadEntrenamientoRel
     *
     * @return AfiEntidadEntrenamiento
     */
    public function addEntidadesEntrenamientosCostosEntidadEntrenamientoRel(\Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamientoCosto $entidadesEntrenamientosCostosEntidadEntrenamientoRel)
    {
        $this->entidadesEntrenamientosCostosEntidadEntrenamientoRel[] = $entidadesEntrenamientosCostosEntidadEntrenamientoRel;

        return $this;
    }

    /**
     * Remove entidadesEntrenamientosCostosEntidadEntrenamientoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamientoCosto $entidadesEntrenamientosCostosEntidadEntrenamientoRel
     */
    public function removeEntidadesEntrenamientosCostosEntidadEntrenamientoRel(\Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamientoCosto $entidadesEntrenamientosCostosEntidadEntrenamientoRel)
    {
        $this->entidadesEntrenamientosCostosEntidadEntrenamientoRel->removeElement($entidadesEntrenamientosCostosEntidadEntrenamientoRel);
    }

    /**
     * Get entidadesEntrenamientosCostosEntidadEntrenamientoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntidadesEntrenamientosCostosEntidadEntrenamientoRel()
    {
        return $this->entidadesEntrenamientosCostosEntidadEntrenamientoRel;
    }
}
