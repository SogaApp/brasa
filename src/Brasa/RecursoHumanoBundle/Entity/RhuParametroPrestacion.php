<?php

namespace Brasa\RecursoHumanoBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_parametro_prestacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuParametroPrestacionRepository")
 */
class RhuParametroPrestacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_parametro_prestacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoParametroPrestacionPk;
    
    /**
     * @ORM\Column(name="tipo", type="string", length=3, nullable=true)
     */    
    private $tipo;

    /**
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden = 0;    
    
    /**
     * @ORM\Column(name="dia_desde", type="integer")
     */
    private $diaDesde = 0;    
    
    /**
     * @ORM\Column(name="dia_hasta", type="integer")
     */
    private $diaHasta = 0;    

    /**
     * @ORM\Column(name="porcentaje", type="float")
     */
    private $porcentaje = 0;     

    /**
     * @ORM\Column(name="origen", type="string", length=3, nullable=true)
     */    
    private $origen;    
    
    /**
     * Get codigoParametroPrestacionPk
     *
     * @return integer
     */
    public function getCodigoParametroPrestacionPk()
    {
        return $this->codigoParametroPrestacionPk;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return RhuParametroPrestacion
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set diaDesde
     *
     * @param integer $diaDesde
     *
     * @return RhuParametroPrestacion
     */
    public function setDiaDesde($diaDesde)
    {
        $this->diaDesde = $diaDesde;

        return $this;
    }

    /**
     * Get diaDesde
     *
     * @return integer
     */
    public function getDiaDesde()
    {
        return $this->diaDesde;
    }

    /**
     * Set diaHasta
     *
     * @param integer $diaHasta
     *
     * @return RhuParametroPrestacion
     */
    public function setDiaHasta($diaHasta)
    {
        $this->diaHasta = $diaHasta;

        return $this;
    }

    /**
     * Get diaHasta
     *
     * @return integer
     */
    public function getDiaHasta()
    {
        return $this->diaHasta;
    }

    /**
     * Set porcentaje
     *
     * @param float $porcentaje
     *
     * @return RhuParametroPrestacion
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return float
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Set origen
     *
     * @param string $origen
     *
     * @return RhuParametroPrestacion
     */
    public function setOrigen($origen)
    {
        $this->origen = $origen;

        return $this;
    }

    /**
     * Get origen
     *
     * @return string
     */
    public function getOrigen()
    {
        return $this->origen;
    }

    /**
     * Set orden
     *
     * @param integer $orden
     *
     * @return RhuParametroPrestacion
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return integer
     */
    public function getOrden()
    {
        return $this->orden;
    }
}
