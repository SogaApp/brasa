<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_proveedor")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiProveedorRepository")
 */
class AfiProveedor
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_proveedor_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProveedorPk;                   
    
    /**
     * @ORM\Column(name="nit", type="string", length=15, nullable=false)
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
     * @ORM\OneToMany(targetEntity="AfiPagoCurso", mappedBy="proveedorRel")
     */
    protected $pagosCursosProveedorRel;    

    /**
     * @ORM\OneToMany(targetEntity="AfiCursoDetalle", mappedBy="proveedorRel")
     */
    protected $cursosDetallesProveedorRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="AfiCursoTipo", mappedBy="proveedorRel")
     */
    protected $cursosTiposProveedorRel;    
    
    /**
     * Get codigoProveedorPk
     *
     * @return integer
     */
    public function getCodigoProveedorPk()
    {
        return $this->codigoProveedorPk;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return AfiProveedor
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
     * @return AfiProveedor
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
     * @return AfiProveedor
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
     * @return AfiProveedor
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
     * @return AfiProveedor
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
     * @return AfiProveedor
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
     * @return AfiProveedor
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
     * @return AfiProveedor
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
     * @return AfiProveedor
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
     * @return AfiProveedor
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
     * @return AfiProveedor
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
     * @return AfiProveedor
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
     * Constructor
     */
    public function __construct()
    {
        $this->pagosCursosProveedorRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pagosCursosProveedorRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPagoCurso $pagosCursosProveedorRel
     *
     * @return AfiProveedor
     */
    public function addPagosCursosProveedorRel(\Brasa\AfiliacionBundle\Entity\AfiPagoCurso $pagosCursosProveedorRel)
    {
        $this->pagosCursosProveedorRel[] = $pagosCursosProveedorRel;

        return $this;
    }

    /**
     * Remove pagosCursosProveedorRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPagoCurso $pagosCursosProveedorRel
     */
    public function removePagosCursosProveedorRel(\Brasa\AfiliacionBundle\Entity\AfiPagoCurso $pagosCursosProveedorRel)
    {
        $this->pagosCursosProveedorRel->removeElement($pagosCursosProveedorRel);
    }

    /**
     * Get pagosCursosProveedorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosCursosProveedorRel()
    {
        return $this->pagosCursosProveedorRel;
    }

    /**
     * Add cursosDetallesProveedorRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesProveedorRel
     *
     * @return AfiProveedor
     */
    public function addCursosDetallesProveedorRel(\Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesProveedorRel)
    {
        $this->cursosDetallesProveedorRel[] = $cursosDetallesProveedorRel;

        return $this;
    }

    /**
     * Remove cursosDetallesProveedorRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesProveedorRel
     */
    public function removeCursosDetallesProveedorRel(\Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesProveedorRel)
    {
        $this->cursosDetallesProveedorRel->removeElement($cursosDetallesProveedorRel);
    }

    /**
     * Get cursosDetallesProveedorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCursosDetallesProveedorRel()
    {
        return $this->cursosDetallesProveedorRel;
    }

    /**
     * Add cursosTiposProveedorRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCursoTipo $cursosTiposProveedorRel
     *
     * @return AfiProveedor
     */
    public function addCursosTiposProveedorRel(\Brasa\AfiliacionBundle\Entity\AfiCursoTipo $cursosTiposProveedorRel)
    {
        $this->cursosTiposProveedorRel[] = $cursosTiposProveedorRel;

        return $this;
    }

    /**
     * Remove cursosTiposProveedorRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCursoTipo $cursosTiposProveedorRel
     */
    public function removeCursosTiposProveedorRel(\Brasa\AfiliacionBundle\Entity\AfiCursoTipo $cursosTiposProveedorRel)
    {
        $this->cursosTiposProveedorRel->removeElement($cursosTiposProveedorRel);
    }

    /**
     * Get cursosTiposProveedorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCursosTiposProveedorRel()
    {
        return $this->cursosTiposProveedorRel;
    }
}
