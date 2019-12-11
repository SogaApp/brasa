<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_cierre_mes")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvCierreMesRepository")
 */
class InvCierreMes
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
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes;

    /**
     * @ORM\Column(name="fecha_inicio", type="datetime", nullable=true)
     */    
    private $fechaInicio;      

    /**
     * @ORM\Column(name="fecha_fin", type="datetime", nullable=true)
     */    
    private $fechaFin;    
    
    /**
     * @ORM\Column(name="total_ventas", type="float")
     */
    private $totalVentas = 0;    
    
    /**
     * @ORM\Column(name="total_compras", type="float")
     */
    private $totalCompras = 0;    
    
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
     * @return InvCierreMes
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
     * @return InvCierreMes
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return InvCierreMes
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
     * @return InvCierreMes
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
     * Set totalVentas
     *
     * @param float $totalVentas
     * @return InvCierreMes
     */
    public function setTotalVentas($totalVentas)
    {
        $this->totalVentas = $totalVentas;

        return $this;
    }

    /**
     * Get totalVentas
     *
     * @return float 
     */
    public function getTotalVentas()
    {
        return $this->totalVentas;
    }

    /**
     * Set totalCompras
     *
     * @param float $totalCompras
     * @return InvCierreMes
     */
    public function setTotalCompras($totalCompras)
    {
        $this->totalCompras = $totalCompras;

        return $this;
    }

    /**
     * Get totalCompras
     *
     * @return float 
     */
    public function getTotalCompras()
    {
        return $this->totalCompras;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     * @return InvCierreMes
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
