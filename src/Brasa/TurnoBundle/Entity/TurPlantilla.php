<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_plantilla")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPlantillaRepository")
 */
class TurPlantilla
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_plantilla_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPlantillaPk;       

    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;     
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;    
    
    /**     
     * @ORM\Column(name="homologar_codigo_turno", type="boolean")
     */    
    private $homologarCodigoTurno = false;     
    
    /**
     * @ORM\Column(name="dias_secuencia", type="integer")
     */    
    private $diasSecuencia = 0;         
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;         
    
    /**
     * @ORM\OneToMany(targetEntity="TurPlantillaDetalle", mappedBy="plantillaRel", cascade={"persist", "remove"})
     */
    protected $plantillasDetallesPlantillaRel; 

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="plantillaRel")
     */
    protected $pedidosDetallesPlantillaRel;     

    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalle", mappedBy="plantillaRel")
     */
    protected $serviciosDetallesPlantillaRel;         

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->plantillasDetallesPlantillaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidosDetallesPlantillaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPlantillaPk
     *
     * @return integer
     */
    public function getCodigoPlantillaPk()
    {
        return $this->codigoPlantillaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurPlantilla
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
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return TurPlantilla
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

    /**
     * Set dias
     *
     * @param integer $dias
     *
     * @return TurPlantilla
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return integer
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurPlantilla
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
     * Add plantillasDetallesPlantillaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPlantillaDetalle $plantillasDetallesPlantillaRel
     *
     * @return TurPlantilla
     */
    public function addPlantillasDetallesPlantillaRel(\Brasa\TurnoBundle\Entity\TurPlantillaDetalle $plantillasDetallesPlantillaRel)
    {
        $this->plantillasDetallesPlantillaRel[] = $plantillasDetallesPlantillaRel;

        return $this;
    }

    /**
     * Remove plantillasDetallesPlantillaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPlantillaDetalle $plantillasDetallesPlantillaRel
     */
    public function removePlantillasDetallesPlantillaRel(\Brasa\TurnoBundle\Entity\TurPlantillaDetalle $plantillasDetallesPlantillaRel)
    {
        $this->plantillasDetallesPlantillaRel->removeElement($plantillasDetallesPlantillaRel);
    }

    /**
     * Get plantillasDetallesPlantillaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlantillasDetallesPlantillaRel()
    {
        return $this->plantillasDetallesPlantillaRel;
    }

    /**
     * Add pedidosDetallesPlantillaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPlantillaRel
     *
     * @return TurPlantilla
     */
    public function addPedidosDetallesPlantillaRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPlantillaRel)
    {
        $this->pedidosDetallesPlantillaRel[] = $pedidosDetallesPlantillaRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesPlantillaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPlantillaRel
     */
    public function removePedidosDetallesPlantillaRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPlantillaRel)
    {
        $this->pedidosDetallesPlantillaRel->removeElement($pedidosDetallesPlantillaRel);
    }

    /**
     * Get pedidosDetallesPlantillaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesPlantillaRel()
    {
        return $this->pedidosDetallesPlantillaRel;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return TurPlantilla
     */
    public function setVrSalario($vrSalario)
    {
        $this->vrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * Set homologarCodigoTurno
     *
     * @param boolean $homologarCodigoTurno
     *
     * @return TurPlantilla
     */
    public function setHomologarCodigoTurno($homologarCodigoTurno)
    {
        $this->homologarCodigoTurno = $homologarCodigoTurno;

        return $this;
    }

    /**
     * Get homologarCodigoTurno
     *
     * @return boolean
     */
    public function getHomologarCodigoTurno()
    {
        return $this->homologarCodigoTurno;
    }

    /**
     * Set diasSecuencia
     *
     * @param integer $diasSecuencia
     *
     * @return TurPlantilla
     */
    public function setDiasSecuencia($diasSecuencia)
    {
        $this->diasSecuencia = $diasSecuencia;

        return $this;
    }

    /**
     * Get diasSecuencia
     *
     * @return integer
     */
    public function getDiasSecuencia()
    {
        return $this->diasSecuencia;
    }

    /**
     * Add serviciosDetallesPlantillaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPlantillaRel
     *
     * @return TurPlantilla
     */
    public function addServiciosDetallesPlantillaRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPlantillaRel)
    {
        $this->serviciosDetallesPlantillaRel[] = $serviciosDetallesPlantillaRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesPlantillaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPlantillaRel
     */
    public function removeServiciosDetallesPlantillaRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPlantillaRel)
    {
        $this->serviciosDetallesPlantillaRel->removeElement($serviciosDetallesPlantillaRel);
    }

    /**
     * Get serviciosDetallesPlantillaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesPlantillaRel()
    {
        return $this->serviciosDetallesPlantillaRel;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurPlantilla
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
