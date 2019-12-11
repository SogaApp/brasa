<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_usuario_configuracion")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteUsuarioConfiguracionRepository")
 */
class TteUsuarioConfiguracion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_usuario_configuracion_pk", type="integer")
     */
    private $codigoUsuarioConfiguracionPk;  
    
    /**
     * @ORM\Column(name="codigo_usuario_fk", type="integer")
     */    
    private $codigoUsuarioFk;    
    
    /**
     * @ORM\Column(name="codigo_punto_operacion_fk", type="integer")
     */    
    private $codigoPuntoOperacionFk;        

     /**
     * @ORM\ManyToOne(targetEntity="Brasa\SeguridadBundle\Entity\User", inversedBy="usuariosConfiguracionRel")
     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="id")
     */
    protected $usuarioRel;

     /**
     * @ORM\ManyToOne(targetEntity="TtePuntoOperacion", inversedBy="usuariosConfiguracionRel")
     * @ORM\JoinColumn(name="codigo_punto_operacion_fk", referencedColumnName="codigo_punto_operacion_pk")
     */
    protected $puntoOperacionRel;    
    



    /**
     * Set codigoUsuarioConfiguracionPk
     *
     * @param integer $codigoUsuarioConfiguracionPk
     * @return TteUsuarioConfiguracion
     */
    public function setCodigoUsuarioConfiguracionPk($codigoUsuarioConfiguracionPk)
    {
        $this->codigoUsuarioConfiguracionPk = $codigoUsuarioConfiguracionPk;

        return $this;
    }

    /**
     * Get codigoUsuarioConfiguracionPk
     *
     * @return integer 
     */
    public function getCodigoUsuarioConfiguracionPk()
    {
        return $this->codigoUsuarioConfiguracionPk;
    }

    /**
     * Set codigoUsuarioFk
     *
     * @param integer $codigoUsuarioFk
     * @return TteUsuarioConfiguracion
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
     * Set codigoPuntoOperacionFk
     *
     * @param integer $codigoPuntoOperacionFk
     * @return TteUsuarioConfiguracion
     */
    public function setCodigoPuntoOperacionFk($codigoPuntoOperacionFk)
    {
        $this->codigoPuntoOperacionFk = $codigoPuntoOperacionFk;

        return $this;
    }

    /**
     * Get codigoPuntoOperacionFk
     *
     * @return integer 
     */
    public function getCodigoPuntoOperacionFk()
    {
        return $this->codigoPuntoOperacionFk;
    }

    /**
     * Set usuarioRel
     *
     * @param \Brasa\SeguridadBundle\Entity\User $usuarioRel
     * @return TteUsuarioConfiguracion
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

    /**
     * Set puntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePuntoOperacion $puntoOperacionRel
     * @return TteUsuarioConfiguracion
     */
    public function setPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TtePuntoOperacion $puntoOperacionRel = null)
    {
        $this->puntoOperacionRel = $puntoOperacionRel;

        return $this;
    }

    /**
     * Get puntoOperacionRel
     *
     * @return \Brasa\TransporteBundle\Entity\TtePuntoOperacion 
     */
    public function getPuntoOperacionRel()
    {
        return $this->puntoOperacionRel;
    }
}
