<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sso_periodo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSsoPeriodoRepository")
 */
class RhuSsoPeriodo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoPk;   
    
    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;
    
    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0; 
    
    /**
     * @ORM\Column(name="anio_pago", type="integer")
     */
    private $anioPago = 0;
    
    /**
     * @ORM\Column(name="mes_pago", type="integer")
     */
    private $mesPago = 0;    
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;     
    
    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = 0; 
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = 0;

    /**
     * @ORM\Column(name="fecha_pago", type="date", nullable=true)
     */    
    private $fechaPago;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSsoPeriodoDetalle", mappedBy="ssoPeriodoRel")
     */
    protected $ssoPeriodosDetallesSsoPeriodoRel; 

    /**
     * @ORM\OneToMany(targetEntity="RhuSsoPeriodoEmpleado", mappedBy="ssoPeriodoRel")
     */
    protected $ssoPeriodosEmpleadosSsoPeriodoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSsoAporte", mappedBy="ssoPeriodoRel")
     */
    protected $ssoAportesSsoPeriodoRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ssoPeriodosDetallesSsoPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPeriodoPk
     *
     * @return integer
     */
    public function getCodigoPeriodoPk()
    {
        return $this->codigoPeriodoPk;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return RhuSsoPeriodo
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return integer
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     *
     * @return RhuSsoPeriodo
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuSsoPeriodo
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return RhuSsoPeriodo
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return RhuSsoPeriodo
     */
    public function setEstadoGenerado($estadoGenerado)
    {
        $this->estadoGenerado = $estadoGenerado;

        return $this;
    }

    /**
     * Get estadoGenerado
     *
     * @return boolean
     */
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * Add ssoPeriodosDetallesSsoPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodosDetallesSsoPeriodoRel
     *
     * @return RhuSsoPeriodo
     */
    public function addSsoPeriodosDetallesSsoPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodosDetallesSsoPeriodoRel)
    {
        $this->ssoPeriodosDetallesSsoPeriodoRel[] = $ssoPeriodosDetallesSsoPeriodoRel;

        return $this;
    }

    /**
     * Remove ssoPeriodosDetallesSsoPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodosDetallesSsoPeriodoRel
     */
    public function removeSsoPeriodosDetallesSsoPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodosDetallesSsoPeriodoRel)
    {
        $this->ssoPeriodosDetallesSsoPeriodoRel->removeElement($ssoPeriodosDetallesSsoPeriodoRel);
    }

    /**
     * Get ssoPeriodosDetallesSsoPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoPeriodosDetallesSsoPeriodoRel()
    {
        return $this->ssoPeriodosDetallesSsoPeriodoRel;
    }

    /**
     * Add ssoPeriodosEmpleadosSsoPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosSsoPeriodoRel
     *
     * @return RhuSsoPeriodo
     */
    public function addSsoPeriodosEmpleadosSsoPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosSsoPeriodoRel)
    {
        $this->ssoPeriodosEmpleadosSsoPeriodoRel[] = $ssoPeriodosEmpleadosSsoPeriodoRel;

        return $this;
    }

    /**
     * Remove ssoPeriodosEmpleadosSsoPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosSsoPeriodoRel
     */
    public function removeSsoPeriodosEmpleadosSsoPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosSsoPeriodoRel)
    {
        $this->ssoPeriodosEmpleadosSsoPeriodoRel->removeElement($ssoPeriodosEmpleadosSsoPeriodoRel);
    }

    /**
     * Get ssoPeriodosEmpleadosSsoPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoPeriodosEmpleadosSsoPeriodoRel()
    {
        return $this->ssoPeriodosEmpleadosSsoPeriodoRel;
    }

    /**
     * Add ssoAportesSsoPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesSsoPeriodoRel
     *
     * @return RhuSsoPeriodo
     */
    public function addSsoAportesSsoPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesSsoPeriodoRel)
    {
        $this->ssoAportesSsoPeriodoRel[] = $ssoAportesSsoPeriodoRel;

        return $this;
    }

    /**
     * Remove ssoAportesSsoPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesSsoPeriodoRel
     */
    public function removeSsoAportesSsoPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesSsoPeriodoRel)
    {
        $this->ssoAportesSsoPeriodoRel->removeElement($ssoAportesSsoPeriodoRel);
    }

    /**
     * Get ssoAportesSsoPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoAportesSsoPeriodoRel()
    {
        return $this->ssoAportesSsoPeriodoRel;
    }

    /**
     * Set anioPago
     *
     * @param integer $anioPago
     *
     * @return RhuSsoPeriodo
     */
    public function setAnioPago($anioPago)
    {
        $this->anioPago = $anioPago;

        return $this;
    }

    /**
     * Get anioPago
     *
     * @return integer
     */
    public function getAnioPago()
    {
        return $this->anioPago;
    }

    /**
     * Set mesPago
     *
     * @param integer $mesPago
     *
     * @return RhuSsoPeriodo
     */
    public function setMesPago($mesPago)
    {
        $this->mesPago = $mesPago;

        return $this;
    }

    /**
     * Get mesPago
     *
     * @return integer
     */
    public function getMesPago()
    {
        return $this->mesPago;
    }

    /**
     * Set fechaPago
     *
     * @param \DateTime $fechaPago
     *
     * @return RhuSsoPeriodo
     */
    public function setFechaPago($fechaPago)
    {
        $this->fechaPago = $fechaPago;

        return $this;
    }

    /**
     * Get fechaPago
     *
     * @return \DateTime
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuSsoPeriodo
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }
}
