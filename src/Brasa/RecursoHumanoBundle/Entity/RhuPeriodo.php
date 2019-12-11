<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_periodo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPeriodoRepository")
 */
class RhuPeriodo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoPk;    

    /**
     * @ORM\Column(name="codigo_periodo_pago_fk", type="integer", nullable=true)
     */    
    private $codigoPeriodoPagoFk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\Column(name="mes", type="boolean")
     */    
    private $mes;

    /**
     * @ORM\Column(name="dia_inicio", type="boolean")
     */    
    private $dia_inicio;    

    /**
     * @ORM\Column(name="bisiesto", type="boolean")
     */    
    private $bisiesto;     


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
     * Set codigoPeriodoPagoFk
     *
     * @param integer $codigoPeriodoPagoFk
     *
     * @return RhuPeriodo
     */
    public function setCodigoPeriodoPagoFk($codigoPeriodoPagoFk)
    {
        $this->codigoPeriodoPagoFk = $codigoPeriodoPagoFk;

        return $this;
    }

    /**
     * Get codigoPeriodoPagoFk
     *
     * @return integer
     */
    public function getCodigoPeriodoPagoFk()
    {
        return $this->codigoPeriodoPagoFk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuPeriodo
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
     * Set mes
     *
     * @param boolean $mes
     *
     * @return RhuPeriodo
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return boolean
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set diaInicio
     *
     * @param boolean $diaInicio
     *
     * @return RhuPeriodo
     */
    public function setDiaInicio($diaInicio)
    {
        $this->dia_inicio = $diaInicio;

        return $this;
    }

    /**
     * Get diaInicio
     *
     * @return boolean
     */
    public function getDiaInicio()
    {
        return $this->dia_inicio;
    }

    /**
     * Set bisiesto
     *
     * @param boolean $bisiesto
     *
     * @return RhuPeriodo
     */
    public function setBisiesto($bisiesto)
    {
        $this->bisiesto = $bisiesto;

        return $this;
    }

    /**
     * Get bisiesto
     *
     * @return boolean
     */
    public function getBisiesto()
    {
        return $this->bisiesto;
    }
}
