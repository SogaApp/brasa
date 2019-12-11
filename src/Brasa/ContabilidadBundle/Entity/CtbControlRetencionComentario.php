<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_control_retencion_comentario")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbControlRetencionComentarioRepository")
 */
class CtbControlRetencionComentario
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_control_retenciones_comentario_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoControlRetencionesComentarioPk;
    
    /**
     * @ORM\Column(name="codigo_control_retenciones_fk", type="integer")
     */     
    private $codigoControlRetencionesFk;    
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;    

    /**
     * @ORM\Column(name="codigo_usuario_fk", type="integer", nullable=true)
     */
    private $codigoUsuarioFk;    
    
    /**
     * @ORM\Column(name="comentario", type="string", length=200, nullable=true)
     */    
    private $comentario;        
    
     /**
     * @ORM\ManyToOne(targetEntity="CtbControlRetencion", inversedBy="CtbControlRetencionComentario")
     * @ORM\JoinColumn(name="codigo_control_retenciones_fk", referencedColumnName="codigo_control_retenciones_pk")
     */
    protected $CtbControlRetencionRel;      
    
     /**
     * @ORM\ManyToOne(targetEntity="Brasa\SeguridadBundle\Entity\User", inversedBy="CtbControlRetencionComentario")
     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="id")
     */
    protected $usuarioRel;    
    


    /**
     * Get codigoControlRetencionesComentarioPk
     *
     * @return integer
     */
    public function getCodigoControlRetencionesComentarioPk()
    {
        return $this->codigoControlRetencionesComentarioPk;
    }

    /**
     * Set codigoControlRetencionesFk
     *
     * @param integer $codigoControlRetencionesFk
     *
     * @return CtbControlRetencionComentario
     */
    public function setCodigoControlRetencionesFk($codigoControlRetencionesFk)
    {
        $this->codigoControlRetencionesFk = $codigoControlRetencionesFk;

        return $this;
    }

    /**
     * Get codigoControlRetencionesFk
     *
     * @return integer
     */
    public function getCodigoControlRetencionesFk()
    {
        return $this->codigoControlRetencionesFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CtbControlRetencionComentario
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
     * Set codigoUsuarioFk
     *
     * @param integer $codigoUsuarioFk
     *
     * @return CtbControlRetencionComentario
     */
    public function setCodigoUsuarioFk($codigoUsuarioFk)
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;

        return $this;
    }

    /**
     * Get codigoUsuarioFk
     *
     * @return integer
     */
    public function getCodigoUsuarioFk()
    {
        return $this->codigoUsuarioFk;
    }

    /**
     * Set comentario
     *
     * @param string $comentario
     *
     * @return CtbControlRetencionComentario
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set ctbControlRetencionRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbControlRetencion $ctbControlRetencionRel
     *
     * @return CtbControlRetencionComentario
     */
    public function setCtbControlRetencionRel(\Brasa\ContabilidadBundle\Entity\CtbControlRetencion $ctbControlRetencionRel = null)
    {
        $this->CtbControlRetencionRel = $ctbControlRetencionRel;

        return $this;
    }

    /**
     * Get ctbControlRetencionRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbControlRetencion
     */
    public function getCtbControlRetencionRel()
    {
        return $this->CtbControlRetencionRel;
    }

    /**
     * Set usuarioRel
     *
     * @param \Brasa\SeguridadBundle\Entity\User $usuarioRel
     *
     * @return CtbControlRetencionComentario
     */
    public function setUsuarioRel(\Brasa\SeguridadBundle\Entity\User $usuarioRel = null)
    {
        $this->usuarioRel = $usuarioRel;

        return $this;
    }

    /**
     * Get usuarioRel
     *
     * @return \Brasa\SeguridadBundle\Entity\User
     */
    public function getUsuarioRel()
    {
        return $this->usuarioRel;
    }
}
