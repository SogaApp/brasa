<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_periodo_pago")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPeriodoPagoRepository")
 */
class RhuPeriodoPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoPagoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre; 
    
    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0; 

    /**
     * @ORM\Column(name="limite_horas_extra", type="integer")
     */
    private $limiteHorasExtra = 0;
    
    /**
     * Esta propiedad define si tiene cortes en el mes o no 10 15 30
     * @ORM\Column(name="continuo", type="boolean")
     */    
    private $continuo = 0;    
    
    /**
     * Especifica de cuantos periodos consta el mes, aplica solo para no continuos
     * @ORM\Column(name="periodos_mes", type="float")
     */
    private $periodosMes = 0;               

    /**
     * @ORM\OneToMany(targetEntity="RhuCentroCosto", mappedBy="periodoPagoRel")
     */
    protected $centrosCostosPeriodoPagoRel;     
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->centrosCostosPeriodoPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPeriodoPagoPk
     *
     * @return integer
     */
    public function getCodigoPeriodoPagoPk()
    {
        return $this->codigoPeriodoPagoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuPeriodoPago
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
     * Set dias
     *
     * @param integer $dias
     *
     * @return RhuPeriodoPago
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
     * Set continuo
     *
     * @param boolean $continuo
     *
     * @return RhuPeriodoPago
     */
    public function setContinuo($continuo)
    {
        $this->continuo = $continuo;

        return $this;
    }

    /**
     * Get continuo
     *
     * @return boolean
     */
    public function getContinuo()
    {
        return $this->continuo;
    }

    /**
     * Set periodosMes
     *
     * @param integer $periodosMes
     *
     * @return RhuPeriodoPago
     */
    public function setPeriodosMes($periodosMes)
    {
        $this->periodosMes = $periodosMes;

        return $this;
    }

    /**
     * Get periodosMes
     *
     * @return integer
     */
    public function getPeriodosMes()
    {
        return $this->periodosMes;
    }

    /**
     * Add centrosCostosPeriodoPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosPeriodoPagoRel
     *
     * @return RhuPeriodoPago
     */
    public function addCentrosCostosPeriodoPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosPeriodoPagoRel)
    {
        $this->centrosCostosPeriodoPagoRel[] = $centrosCostosPeriodoPagoRel;

        return $this;
    }

    /**
     * Remove centrosCostosPeriodoPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosPeriodoPagoRel
     */
    public function removeCentrosCostosPeriodoPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosPeriodoPagoRel)
    {
        $this->centrosCostosPeriodoPagoRel->removeElement($centrosCostosPeriodoPagoRel);
    }

    /**
     * Get centrosCostosPeriodoPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentrosCostosPeriodoPagoRel()
    {
        return $this->centrosCostosPeriodoPagoRel;
    }

    /**
     * Set limiteHorasExtra
     *
     * @param integer $limiteHorasExtra
     *
     * @return RhuPeriodoPago
     */
    public function setLimiteHorasExtra($limiteHorasExtra)
    {
        $this->limiteHorasExtra = $limiteHorasExtra;

        return $this;
    }

    /**
     * Get limiteHorasExtra
     *
     * @return integer
     */
    public function getLimiteHorasExtra()
    {
        return $this->limiteHorasExtra;
    }
}
