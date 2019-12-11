<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_referencia")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionReferenciaRepository")
 */
class RhuSeleccionReferencia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_referencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionReferenciaPk;            
    
    /**
     * @ORM\Column(name="codigo_seleccion_fk", type="integer")
     */    
    private $codigoSeleccionFk; 
    
    /**
     * @ORM\Column(name="codigo_seleccion_tipo_referencia_fk", type="integer")
     */    
    private $codigoSeleccionTipoReferenciaFk;
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */    
    private $nombreCorto;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */    
    private $telefono;    
    
    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */    
    private $celular; 
    
    /**
     * @ORM\Column(name="direccion", type="string", length=30, nullable=true)
     */    
    private $direccion;         
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadFk;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;    
    
    /**
     * @ORM\Column(name="estado_verificada", type="boolean")
     */    
    private $estadoVerificada = 0; 
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccion", inversedBy="seleccionesReferenciasSeleccionRel")
     * @ORM\JoinColumn(name="codigo_seleccion_fk", referencedColumnName="codigo_seleccion_pk")
     */
    protected $seleccionRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccionTipoReferencia", inversedBy="seleccionesReferenciasSelecionTipoReferenciaRel")
     * @ORM\JoinColumn(name="codigo_seleccion_tipo_referencia_fk", referencedColumnName="codigo_seleccion_tipo_referencia_pk")
     */
    protected $seleccionTipoReferenciaRel;
   
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuSeleccionesReferenciasCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;    
    


    

    /**
     * Get codigoSeleccionReferenciaPk
     *
     * @return integer
     */
    public function getCodigoSeleccionReferenciaPk()
    {
        return $this->codigoSeleccionReferenciaPk;
    }

    /**
     * Set codigoSeleccionFk
     *
     * @param integer $codigoSeleccionFk
     *
     * @return RhuSeleccionReferencia
     */
    public function setCodigoSeleccionFk($codigoSeleccionFk)
    {
        $this->codigoSeleccionFk = $codigoSeleccionFk;

        return $this;
    }

    /**
     * Get codigoSeleccionFk
     *
     * @return integer
     */
    public function getCodigoSeleccionFk()
    {
        return $this->codigoSeleccionFk;
    }

    /**
     * Set codigoSeleccionTipoReferenciaFk
     *
     * @param integer $codigoSeleccionTipoReferenciaFk
     *
     * @return RhuSeleccionReferencia
     */
    public function setCodigoSeleccionTipoReferenciaFk($codigoSeleccionTipoReferenciaFk)
    {
        $this->codigoSeleccionTipoReferenciaFk = $codigoSeleccionTipoReferenciaFk;

        return $this;
    }

    /**
     * Get codigoSeleccionTipoReferenciaFk
     *
     * @return integer
     */
    public function getCodigoSeleccionTipoReferenciaFk()
    {
        return $this->codigoSeleccionTipoReferenciaFk;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuSeleccionReferencia
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
     * Set telefono
     *
     * @param string $telefono
     *
     * @return RhuSeleccionReferencia
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
     * @return RhuSeleccionReferencia
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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return RhuSeleccionReferencia
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
     * @return RhuSeleccionReferencia
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuSeleccionReferencia
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
     * Set estadoVerificada
     *
     * @param boolean $estadoVerificada
     *
     * @return RhuSeleccionReferencia
     */
    public function setEstadoVerificada($estadoVerificada)
    {
        $this->estadoVerificada = $estadoVerificada;

        return $this;
    }

    /**
     * Get estadoVerificada
     *
     * @return boolean
     */
    public function getEstadoVerificada()
    {
        return $this->estadoVerificada;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuSeleccionReferencia
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * Set seleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionRel
     *
     * @return RhuSeleccionReferencia
     */
    public function setSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionRel = null)
    {
        $this->seleccionRel = $seleccionRel;

        return $this;
    }

    /**
     * Get seleccionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion
     */
    public function getSeleccionRel()
    {
        return $this->seleccionRel;
    }

    /**
     * Set seleccionTipoReferenciaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionTipoReferencia $seleccionTipoReferenciaRel
     *
     * @return RhuSeleccionReferencia
     */
    public function setSeleccionTipoReferenciaRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionTipoReferencia $seleccionTipoReferenciaRel = null)
    {
        $this->seleccionTipoReferenciaRel = $seleccionTipoReferenciaRel;

        return $this;
    }

    /**
     * Get seleccionTipoReferenciaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionTipoReferencia
     */
    public function getSeleccionTipoReferenciaRel()
    {
        return $this->seleccionTipoReferenciaRel;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuSeleccionReferencia
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
}
