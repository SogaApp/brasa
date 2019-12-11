<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_cierre_mes")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbCierreMesRepository")
 */
class CtbCierreMes
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_mes_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCierreMesPk;
    
    /**
     * @ORM\Column(name="annio", type="integer")
     */
    private $annio;

    /**
     * @ORM\Column(name="mes", type="smallint")
     */
    private $mes;

    /**
     * @ORM\Column(name="fecha_cierre", type="datetime", nullable=true)
     */    
    private $fechaCierre;
    
    /**
     * @ORM\Column(name="fecha_inicio", type="datetime", nullable=true)
     */    
    private $fechaInicio;      

    /**
     * @ORM\Column(name="fecha_fin", type="datetime", nullable=true)
     */    
    private $fechaFin;        
    
    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = 0;     
    


    /**
     * Get codigoCierreMesPk
     *
     * @return integer
     */
    public function getCodigoCierreMesPk()
    {
        return $this->codigoCierreMesPk;
    }

    /**
     * Set annio
     *
     * @param integer $annio
     *
     * @return CtbCierreMes
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
     * @return CtbCierreMes
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
     * Set fechaCierre
     *
     * @param \DateTime $fechaCierre
     *
     * @return CtbCierreMes
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;

        return $this;
    }

    /**
     * Get fechaCierre
     *
     * @return \DateTime
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return CtbCierreMes
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     *
     * @return CtbCierreMes
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return CtbCierreMes
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
