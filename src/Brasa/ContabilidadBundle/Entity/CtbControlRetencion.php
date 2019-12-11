<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_control_retencion")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbControlRetencionRepository")
 */
class CtbControlRetencion
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_control_retenciones_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoControlRetencionesPk;    
    
    /**
     * @ORM\Column(name="annio", type="integer")
     */
    private $annio;

    /**
     * @ORM\Column(name="mes", type="smallint")
     */
    private $mes;  
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     * @Assert\NotBlank
     */    
    private $codigoTerceroFk;   
    
    /**
     * @ORM\Column(name="valor", type="float")
     */
    private $valor = 0; 
    
    /**
     * @ORM\Column(name="estado_recibido", type="boolean")
     */    
    private $estadoRecibido = 0;     
    
    /**
     * @ORM\Column(name="codigo_usuario_recibe_fk", type="integer", nullable=true)
     */
    private $codigoUsuarioRecibeFk;    
    
    /**
     * @ORM\Column(name="fecha_recibido", type="datetime", nullable=true)
     */    
    private $fechaRecibido;     
    
    /**
     * @ORM\Column(name="fecha_ultimo_comentario", type="datetime", nullable=true)
     */    
    private $fechaUltimoComentario;
       

     /**
     * @ORM\ManyToOne(targetEntity="Brasa\SeguridadBundle\Entity\User", inversedBy="CtbControlRetencion")
     * @ORM\JoinColumn(name="codigo_usuario_recibe_fk", referencedColumnName="id")
     */
    protected $usuarioRecibeRel;



    /**
     * Get codigoControlRetencionesPk
     *
     * @return integer
     */
    public function getCodigoControlRetencionesPk()
    {
        return $this->codigoControlRetencionesPk;
    }

    /**
     * Set annio
     *
     * @param integer $annio
     *
     * @return CtbControlRetencion
     */
    public function setAnnio($annio)
    {
        $this->annio = $annio;

        return $this;
    }

    /**
     * Get annio
     *
     * @return integer
     */
    public function getAnnio()
    {
        return $this->annio;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     *
     * @return CtbControlRetencion
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return integer
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return CtbControlRetencion
     */
    public function setCodigoTerceroFk($codigoTerceroFk)
    {
        $this->codigoTerceroFk = $codigoTerceroFk;

        return $this;
    }

    /**
     * Get codigoTerceroFk
     *
     * @return integer
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return CtbControlRetencion
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
     * Set estadoRecibido
     *
     * @param boolean $estadoRecibido
     *
     * @return CtbControlRetencion
     */
    public function setEstadoRecibido($estadoRecibido)
    {
        $this->estadoRecibido = $estadoRecibido;

        return $this;
    }

    /**
     * Get estadoRecibido
     *
     * @return boolean
     */
    public function getEstadoRecibido()
    {
        return $this->estadoRecibido;
    }

    /**
     * Set codigoUsuarioRecibeFk
     *
     * @param integer $codigoUsuarioRecibeFk
     *
     * @return CtbControlRetencion
     */
    public function setCodigoUsuarioRecibeFk($codigoUsuarioRecibeFk)
    {
        $this->codigoUsuarioRecibeFk = $codigoUsuarioRecibeFk;

        return $this;
    }

    /**
     * Get codigoUsuarioRecibeFk
     *
     * @return integer
     */
    public function getCodigoUsuarioRecibeFk()
    {
        return $this->codigoUsuarioRecibeFk;
    }

    /**
     * Set fechaRecibido
     *
     * @param \DateTime $fechaRecibido
     *
     * @return CtbControlRetencion
     */
    public function setFechaRecibido($fechaRecibido)
    {
        $this->fechaRecibido = $fechaRecibido;

        return $this;
    }

    /**
     * Get fechaRecibido
     *
     * @return \DateTime
     */
    public function getFechaRecibido()
    {
        return $this->fechaRecibido;
    }

    /**
     * Set fechaUltimoComentario
     *
     * @param \DateTime $fechaUltimoComentario
     *
     * @return CtbControlRetencion
     */
    public function setFechaUltimoComentario($fechaUltimoComentario)
    {
        $this->fechaUltimoComentario = $fechaUltimoComentario;

        return $this;
    }

    /**
     * Get fechaUltimoComentario
     *
     * @return \DateTime
     */
    public function getFechaUltimoComentario()
    {
        return $this->fechaUltimoComentario;
    }

    /**
     * Set usuarioRecibeRel
     *
     * @param \Brasa\SeguridadBundle\Entity\User $usuarioRecibeRel
     *
     * @return CtbControlRetencion
     */
    public function setUsuarioRecibeRel(\Brasa\SeguridadBundle\Entity\User $usuarioRecibeRel = null)
    {
        $this->usuarioRecibeRel = $usuarioRecibeRel;

        return $this;
    }

    /**
     * Get usuarioRecibeRel
     *
     * @return \Brasa\SeguridadBundle\Entity\User
     */
    public function getUsuarioRecibeRel()
    {
        return $this->usuarioRecibeRel;
    }
}
