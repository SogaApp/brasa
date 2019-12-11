<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_supervigilancia_parafiscales")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSupervigilanciaParafiscalesRepository")
 */
class RhuSupervigilanciaParafiscales
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_supervigilancia_parafiscales_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSupervigilanciaParafiscalesPk;
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;                      

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;    

    /**
     * @ORM\Column(name="cargo", type="string", length=80, nullable=true)
     */    
    private $cargo;     
    
    /**
     * @ORM\Column(name="empleados", type="integer")
     */
    private $empleados = 0;     

    /**
     * @ORM\Column(name="vr_nomina", type="float")
     */
    private $vrNomina = 0;     

    /**
     * @ORM\Column(name="vr_eps", type="float")
     */
    private $vrEps = 0;    

    /**
     * @ORM\Column(name="vr_pension", type="float")
     */
    private $vrPension = 0;    
    
    /**
     * @ORM\Column(name="vr_arl", type="float")
     */
    private $vrArl = 0;         
    
    /**
     * @ORM\Column(name="vr_sena", type="float")
     */
    private $vrSena = 0;     
    
    /**
     * @ORM\Column(name="vr_icbf", type="float")
     */
    private $vrIcbf = 0;         
    
    /**
     * @ORM\Column(name="vr_ccf", type="float")
     */
    private $vrCcf = 0;         
    


    /**
     * Get codigoSupervigilanciaParafiscalesPk
     *
     * @return integer
     */
    public function getCodigoSupervigilanciaParafiscalesPk()
    {
        return $this->codigoSupervigilanciaParafiscalesPk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuSupervigilanciaParafiscales
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
     * @return RhuSupervigilanciaParafiscales
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
     * Set mes
     *
     * @param integer $mes
     *
     * @return RhuSupervigilanciaParafiscales
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
     * Set empleados
     *
     * @param integer $empleados
     *
     * @return RhuSupervigilanciaParafiscales
     */
    public function setEmpleados($empleados)
    {
        $this->empleados = $empleados;

        return $this;
    }

    /**
     * Get empleados
     *
     * @return integer
     */
    public function getEmpleados()
    {
        return $this->empleados;
    }

    /**
     * Set vrNomina
     *
     * @param float $vrNomina
     *
     * @return RhuSupervigilanciaParafiscales
     */
    public function setVrNomina($vrNomina)
    {
        $this->vrNomina = $vrNomina;

        return $this;
    }

    /**
     * Get vrNomina
     *
     * @return float
     */
    public function getVrNomina()
    {
        return $this->vrNomina;
    }

    /**
     * Set vrEps
     *
     * @param float $vrEps
     *
     * @return RhuSupervigilanciaParafiscales
     */
    public function setVrEps($vrEps)
    {
        $this->vrEps = $vrEps;

        return $this;
    }

    /**
     * Get vrEps
     *
     * @return float
     */
    public function getVrEps()
    {
        return $this->vrEps;
    }

    /**
     * Set vrPension
     *
     * @param float $vrPension
     *
     * @return RhuSupervigilanciaParafiscales
     */
    public function setVrPension($vrPension)
    {
        $this->vrPension = $vrPension;

        return $this;
    }

    /**
     * Get vrPension
     *
     * @return float
     */
    public function getVrPension()
    {
        return $this->vrPension;
    }

    /**
     * Set vrArl
     *
     * @param float $vrArl
     *
     * @return RhuSupervigilanciaParafiscales
     */
    public function setVrArl($vrArl)
    {
        $this->vrArl = $vrArl;

        return $this;
    }

    /**
     * Get vrArl
     *
     * @return float
     */
    public function getVrArl()
    {
        return $this->vrArl;
    }

    /**
     * Set vrSena
     *
     * @param float $vrSena
     *
     * @return RhuSupervigilanciaParafiscales
     */
    public function setVrSena($vrSena)
    {
        $this->vrSena = $vrSena;

        return $this;
    }

    /**
     * Get vrSena
     *
     * @return float
     */
    public function getVrSena()
    {
        return $this->vrSena;
    }

    /**
     * Set vrIcbf
     *
     * @param float $vrIcbf
     *
     * @return RhuSupervigilanciaParafiscales
     */
    public function setVrIcbf($vrIcbf)
    {
        $this->vrIcbf = $vrIcbf;

        return $this;
    }

    /**
     * Get vrIcbf
     *
     * @return float
     */
    public function getVrIcbf()
    {
        return $this->vrIcbf;
    }

    /**
     * Set vrCcf
     *
     * @param float $vrCcf
     *
     * @return RhuSupervigilanciaParafiscales
     */
    public function setVrCcf($vrCcf)
    {
        $this->vrCcf = $vrCcf;

        return $this;
    }

    /**
     * Get vrCcf
     *
     * @return float
     */
    public function getVrCcf()
    {
        return $this->vrCcf;
    }

    /**
     * Set cargo
     *
     * @param string $cargo
     *
     * @return RhuSupervigilanciaParafiscales
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }
}
