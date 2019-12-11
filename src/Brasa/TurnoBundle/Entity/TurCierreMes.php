<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_cierre_mes")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCierreMesRepository")
 */
class TurCierreMes
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_mes_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCierreMesPk;             
    
    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */    
    private $anio;    
    
    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */    
    private $mes;               
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    

    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = false;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCostoServicio", mappedBy="cierreMesRel", cascade={"persist", "remove"})
     */
    protected $costosServiciosCierreMesRel;         

    /**
     * @ORM\OneToMany(targetEntity="TurCostoRecurso", mappedBy="cierreMesRel", cascade={"persist", "remove"})
     */
    protected $costosRecursosCierreMesRel;
       
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->costosServiciosCierreMesRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->costosRecursosCierreMesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set anio
     *
     * @param integer $anio
     *
     * @return TurCierreMes
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
     * @return TurCierreMes
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return TurCierreMes
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

    /**
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return TurCierreMes
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
     * Add costosServiciosCierreMesRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosCierreMesRel
     *
     * @return TurCierreMes
     */
    public function addCostosServiciosCierreMesRel(\Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosCierreMesRel)
    {
        $this->costosServiciosCierreMesRel[] = $costosServiciosCierreMesRel;

        return $this;
    }

    /**
     * Remove costosServiciosCierreMesRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosCierreMesRel
     */
    public function removeCostosServiciosCierreMesRel(\Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosCierreMesRel)
    {
        $this->costosServiciosCierreMesRel->removeElement($costosServiciosCierreMesRel);
    }

    /**
     * Get costosServiciosCierreMesRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCostosServiciosCierreMesRel()
    {
        return $this->costosServiciosCierreMesRel;
    }

    /**
     * Add costosRecursosCierreMesRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoRecurso $costosRecursosCierreMesRel
     *
     * @return TurCierreMes
     */
    public function addCostosRecursosCierreMesRel(\Brasa\TurnoBundle\Entity\TurCostoRecurso $costosRecursosCierreMesRel)
    {
        $this->costosRecursosCierreMesRel[] = $costosRecursosCierreMesRel;

        return $this;
    }

    /**
     * Remove costosRecursosCierreMesRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoRecurso $costosRecursosCierreMesRel
     */
    public function removeCostosRecursosCierreMesRel(\Brasa\TurnoBundle\Entity\TurCostoRecurso $costosRecursosCierreMesRel)
    {
        $this->costosRecursosCierreMesRel->removeElement($costosRecursosCierreMesRel);
    }

    /**
     * Get costosRecursosCierreMesRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCostosRecursosCierreMesRel()
    {
        return $this->costosRecursosCierreMesRel;
    }
}
