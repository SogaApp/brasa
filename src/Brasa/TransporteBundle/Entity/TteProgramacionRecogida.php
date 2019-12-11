<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_programacion_recogida")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteProgramacionRecogidaRepository")
 */
class TteProgramacionRecogida
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_recogida_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionRecogidaPk;                 
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;           
    
    /**
     * @ORM\Column(name="codigo_punto_operacion_fk", type="integer", nullable=true)
     */    
    private $codigoPuntoOperacionFk;            
    
    /**
     * @ORM\Column(name="codigo_conductor_fk", type="integer", nullable=true)
     */    
    private $codigoConductorFk;    
    
    /**
     * @ORM\Column(name="codigo_vehiculo_fk", type="integer", nullable=true)
     */    
    private $codigoVehiculoFk;     
    
    /**
     * @ORM\Column(name="vr_flete_pagado", type="float")
     */
    private $vrFletePagado = 0;
       
    /**
     * @ORM\Column(name="ct_peso_real", type="integer")
     */
    private $ctPesoReal = 0;    

    /**
     * @ORM\Column(name="ct_peso_volumen", type="integer")
     */
    private $ctPesoVolumen = 0;        

    /**
     * @ORM\Column(name="ct_unidades", type="integer")
     */
    private $ctUnidades = 0;    
    
    /**
     * @ORM\Column(name="ct_recogidas", type="integer")
     */
    private $ctRecogidas = 0;    
         
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;                             
    
    /**
     * @ORM\Column(name="estado_descargado", type="boolean")
     */    
    private $estadoDescargado = false;    
    
    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TteConductor", inversedBy="programacionesRecogidasConductorRel")
     * @ORM\JoinColumn(name="codigo_conductor_fk", referencedColumnName="codigo_conductor_pk")
     */
    protected $conductorRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TteVehiculo", inversedBy="programacionesRecogidasVehiculoRel")
     * @ORM\JoinColumn(name="codigo_vehiculo_fk", referencedColumnName="codigo_vehiculo_pk")
     */
    protected $vehiculoRel;          

    /**
     * @ORM\ManyToOne(targetEntity="TtePuntoOperacion", inversedBy="programacionesRecogidasPuntoOperacionRel")
     * @ORM\JoinColumn(name="codigo_punto_operacion_fk", referencedColumnName="codigo_punto_operacion_pk")
     */
    protected $puntoOperacionRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TteRecogida", mappedBy="programacionRecogidaRel")
     */
    protected $recogidasProgramacionRecogidaRel; 
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recogidasProgramacionRecogidaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoProgramacionRecogidaPk
     *
     * @return integer
     */
    public function getCodigoProgramacionRecogidaPk()
    {
        return $this->codigoProgramacionRecogidaPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TteProgramacionRecogida
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
     * Set codigoPuntoOperacionFk
     *
     * @param integer $codigoPuntoOperacionFk
     *
     * @return TteProgramacionRecogida
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
     * Set codigoConductorFk
     *
     * @param integer $codigoConductorFk
     *
     * @return TteProgramacionRecogida
     */
    public function setCodigoConductorFk($codigoConductorFk)
    {
        $this->codigoConductorFk = $codigoConductorFk;

        return $this;
    }

    /**
     * Get codigoConductorFk
     *
     * @return integer
     */
    public function getCodigoConductorFk()
    {
        return $this->codigoConductorFk;
    }

    /**
     * Set codigoVehiculoFk
     *
     * @param integer $codigoVehiculoFk
     *
     * @return TteProgramacionRecogida
     */
    public function setCodigoVehiculoFk($codigoVehiculoFk)
    {
        $this->codigoVehiculoFk = $codigoVehiculoFk;

        return $this;
    }

    /**
     * Get codigoVehiculoFk
     *
     * @return integer
     */
    public function getCodigoVehiculoFk()
    {
        return $this->codigoVehiculoFk;
    }

    /**
     * Set vrFletePagado
     *
     * @param float $vrFletePagado
     *
     * @return TteProgramacionRecogida
     */
    public function setVrFletePagado($vrFletePagado)
    {
        $this->vrFletePagado = $vrFletePagado;

        return $this;
    }

    /**
     * Get vrFletePagado
     *
     * @return float
     */
    public function getVrFletePagado()
    {
        return $this->vrFletePagado;
    }

    /**
     * Set ctPesoReal
     *
     * @param integer $ctPesoReal
     *
     * @return TteProgramacionRecogida
     */
    public function setCtPesoReal($ctPesoReal)
    {
        $this->ctPesoReal = $ctPesoReal;

        return $this;
    }

    /**
     * Get ctPesoReal
     *
     * @return integer
     */
    public function getCtPesoReal()
    {
        return $this->ctPesoReal;
    }

    /**
     * Set ctPesoVolumen
     *
     * @param integer $ctPesoVolumen
     *
     * @return TteProgramacionRecogida
     */
    public function setCtPesoVolumen($ctPesoVolumen)
    {
        $this->ctPesoVolumen = $ctPesoVolumen;

        return $this;
    }

    /**
     * Get ctPesoVolumen
     *
     * @return integer
     */
    public function getCtPesoVolumen()
    {
        return $this->ctPesoVolumen;
    }

    /**
     * Set ctUnidades
     *
     * @param integer $ctUnidades
     *
     * @return TteProgramacionRecogida
     */
    public function setCtUnidades($ctUnidades)
    {
        $this->ctUnidades = $ctUnidades;

        return $this;
    }

    /**
     * Get ctUnidades
     *
     * @return integer
     */
    public function getCtUnidades()
    {
        return $this->ctUnidades;
    }

    /**
     * Set ctRecogidas
     *
     * @param integer $ctRecogidas
     *
     * @return TteProgramacionRecogida
     */
    public function setCtRecogidas($ctRecogidas)
    {
        $this->ctRecogidas = $ctRecogidas;

        return $this;
    }

    /**
     * Get ctRecogidas
     *
     * @return integer
     */
    public function getCtRecogidas()
    {
        return $this->ctRecogidas;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TteProgramacionRecogida
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
     * Set estadoDescargado
     *
     * @param boolean $estadoDescargado
     *
     * @return TteProgramacionRecogida
     */
    public function setEstadoDescargado($estadoDescargado)
    {
        $this->estadoDescargado = $estadoDescargado;

        return $this;
    }

    /**
     * Get estadoDescargado
     *
     * @return boolean
     */
    public function getEstadoDescargado()
    {
        return $this->estadoDescargado;
    }

    /**
     * Set conductorRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteConductor $conductorRel
     *
     * @return TteProgramacionRecogida
     */
    public function setConductorRel(\Brasa\TransporteBundle\Entity\TteConductor $conductorRel = null)
    {
        $this->conductorRel = $conductorRel;

        return $this;
    }

    /**
     * Get conductorRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteConductor
     */
    public function getConductorRel()
    {
        return $this->conductorRel;
    }

    /**
     * Set vehiculoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteVehiculo $vehiculoRel
     *
     * @return TteProgramacionRecogida
     */
    public function setVehiculoRel(\Brasa\TransporteBundle\Entity\TteVehiculo $vehiculoRel = null)
    {
        $this->vehiculoRel = $vehiculoRel;

        return $this;
    }

    /**
     * Get vehiculoRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteVehiculo
     */
    public function getVehiculoRel()
    {
        return $this->vehiculoRel;
    }

    /**
     * Set puntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePuntoOperacion $puntoOperacionRel
     *
     * @return TteProgramacionRecogida
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

    /**
     * Add recogidasProgramacionRecogidaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRecogida $recogidasProgramacionRecogidaRel
     *
     * @return TteProgramacionRecogida
     */
    public function addRecogidasProgramacionRecogidaRel(\Brasa\TransporteBundle\Entity\TteRecogida $recogidasProgramacionRecogidaRel)
    {
        $this->recogidasProgramacionRecogidaRel[] = $recogidasProgramacionRecogidaRel;

        return $this;
    }

    /**
     * Remove recogidasProgramacionRecogidaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRecogida $recogidasProgramacionRecogidaRel
     */
    public function removeRecogidasProgramacionRecogidaRel(\Brasa\TransporteBundle\Entity\TteRecogida $recogidasProgramacionRecogidaRel)
    {
        $this->recogidasProgramacionRecogidaRel->removeElement($recogidasProgramacionRecogidaRel);
    }

    /**
     * Get recogidasProgramacionRecogidaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecogidasProgramacionRecogidaRel()
    {
        return $this->recogidasProgramacionRecogidaRel;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return TteProgramacionRecogida
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }
}
