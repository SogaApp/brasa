<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_centro_costo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCentroCostoRepository")
 */
class TurCentroCosto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_costo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCentroCostoPk;                    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;                         
    
    /**
     * @ORM\OneToMany(targetEntity="TurRecurso", mappedBy="centroCostoRel")
     */
    protected $recursosCentroCostoRel; 
   
    /**
     * @ORM\OneToMany(targetEntity="TurSoportePagoPeriodo", mappedBy="centroCostoRel")
     */
    protected $soportesPagosPeriodosCentroCostoRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recursosCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soportesPagosPeriodosCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCentroCostoPk
     *
     * @return integer
     */
    public function getCodigoCentroCostoPk()
    {
        return $this->codigoCentroCostoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurCentroCosto
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
     * Add recursosCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursosCentroCostoRel
     *
     * @return TurCentroCosto
     */
    public function addRecursosCentroCostoRel(\Brasa\TurnoBundle\Entity\TurRecurso $recursosCentroCostoRel)
    {
        $this->recursosCentroCostoRel[] = $recursosCentroCostoRel;

        return $this;
    }

    /**
     * Remove recursosCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursosCentroCostoRel
     */
    public function removeRecursosCentroCostoRel(\Brasa\TurnoBundle\Entity\TurRecurso $recursosCentroCostoRel)
    {
        $this->recursosCentroCostoRel->removeElement($recursosCentroCostoRel);
    }

    /**
     * Get recursosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecursosCentroCostoRel()
    {
        return $this->recursosCentroCostoRel;
    }

    /**
     * Add soportesPagosPeriodosCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportesPagosPeriodosCentroCostoRel
     *
     * @return TurCentroCosto
     */
    public function addSoportesPagosPeriodosCentroCostoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportesPagosPeriodosCentroCostoRel)
    {
        $this->soportesPagosPeriodosCentroCostoRel[] = $soportesPagosPeriodosCentroCostoRel;

        return $this;
    }

    /**
     * Remove soportesPagosPeriodosCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportesPagosPeriodosCentroCostoRel
     */
    public function removeSoportesPagosPeriodosCentroCostoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportesPagosPeriodosCentroCostoRel)
    {
        $this->soportesPagosPeriodosCentroCostoRel->removeElement($soportesPagosPeriodosCentroCostoRel);
    }

    /**
     * Get soportesPagosPeriodosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosPeriodosCentroCostoRel()
    {
        return $this->soportesPagosPeriodosCentroCostoRel;
    }
}