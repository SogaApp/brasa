<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_recogida_programada")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteRecogidaProgramadaRepository")
 */
class TteRecogidaProgramada
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recogida_programada_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRecogidaProgramadaPk;                 

    /**
     * @ORM\Column(name="hora_recogida", type="time", nullable=true)
     */    
    private $horaRecogida;     
       
    /**
     * @ORM\Column(name="anunciante", type="string", length=80, nullable=true)
     */    
    private $anunciante;

    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */    
    private $direccion;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=25, nullable=true)
     */    
    private $telefono;    
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;    
       
    /**
     * @ORM\Column(name="codigo_punto_operacion_fk", type="integer", nullable=true)
     */    
    private $codigoPuntoOperacionFk;    
          
    
    /**
     * @ORM\ManyToOne(targetEntity="TtePuntoOperacion", inversedBy="recogidasProgramadasRel")
     * @ORM\JoinColumn(name="codigo_punto_operacion_fk", referencedColumnName="codigo_punto_operacion_pk")
     */
    protected $puntoOperacionRel;     
     

    /**
     * Get codigoRecogidaProgramadaPk
     *
     * @return integer
     */
    public function getCodigoRecogidaProgramadaPk()
    {
        return $this->codigoRecogidaProgramadaPk;
    }

    /**
     * Set horaRecogida
     *
     * @param \DateTime $horaRecogida
     *
     * @return TteRecogidaProgramada
     */
    public function setHoraRecogida($horaRecogida)
    {
        $this->horaRecogida = $horaRecogida;

        return $this;
    }

    /**
     * Get horaRecogida
     *
     * @return \DateTime
     */
    public function getHoraRecogida()
    {
        return $this->horaRecogida;
    }

    /**
     * Set anunciante
     *
     * @param string $anunciante
     *
     * @return TteRecogidaProgramada
     */
    public function setAnunciante($anunciante)
    {
        $this->anunciante = $anunciante;

        return $this;
    }

    /**
     * Get anunciante
     *
     * @return string
     */
    public function getAnunciante()
    {
        return $this->anunciante;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return TteRecogidaProgramada
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
     * @return TteRecogidaProgramada
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
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return TteRecogidaProgramada
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
     * Set codigoPuntoOperacionFk
     *
     * @param integer $codigoPuntoOperacionFk
     *
     * @return TteRecogidaProgramada
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
     * Set puntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePuntoOperacion $puntoOperacionRel
     *
     * @return TteRecogidaProgramada
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
