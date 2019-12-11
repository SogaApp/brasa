<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AfiPeriodoFechaPago
 *
 * @ORM\Table(name="afi_periodo_fecha_pago")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiPeriodoFechaPagoRepository")
 */
class AfiPeriodoFechaPago {

    /**
     * @var integer
     *
     * @ORM\Column(name="codigo_periodo_fecha_pago_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoFechaPagoPk;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="dia_habil", type="integer")
     */
    private $diaHabil;

    /**
     * @var integer
     *
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio;

    /**
     * @var string
     *
     * @ORM\Column(name="dos_ultimos_digitos_inicio_nit", type="string", length=5)
     */
    private $dosUltimosDigitosInicioNit;

    /**
     * @var string
     *
     * @ORM\Column(name="dos_ultimos_digitos_fin_nit", type="string", length=5)
     */
    private $dosUltimosDigitosFinNit;


    /**
     * Get codigoPeriodoFechaPagoPk
     *
     * @return integer
     */
    public function getCodigoPeriodoFechaPagoPk()
    {
        return $this->codigoPeriodoFechaPagoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return AfiPeriodoFechaPago
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
     * Set diaHabil
     *
     * @param integer $diaHabil
     *
     * @return AfiPeriodoFechaPago
     */
    public function setDiaHabil($diaHabil)
    {
        $this->diaHabil = $diaHabil;

        return $this;
    }

    /**
     * Get diaHabil
     *
     * @return integer
     */
    public function getDiaHabil()
    {
        return $this->diaHabil;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return AfiPeriodoFechaPago
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
     * Set dosUltimosDigitosInicioNit
     *
     * @param string $dosUltimosDigitosInicioNit
     *
     * @return AfiPeriodoFechaPago
     */
    public function setDosUltimosDigitosInicioNit($dosUltimosDigitosInicioNit)
    {
        $this->dosUltimosDigitosInicioNit = $dosUltimosDigitosInicioNit;

        return $this;
    }

    /**
     * Get dosUltimosDigitosInicioNit
     *
     * @return string
     */
    public function getDosUltimosDigitosInicioNit()
    {
        return $this->dosUltimosDigitosInicioNit;
    }

    /**
     * Set dosUltimosDigitosFinNit
     *
     * @param string $dosUltimosDigitosFinNit
     *
     * @return AfiPeriodoFechaPago
     */
    public function setDosUltimosDigitosFinNit($dosUltimosDigitosFinNit)
    {
        $this->dosUltimosDigitosFinNit = $dosUltimosDigitosFinNit;

        return $this;
    }

    /**
     * Get dosUltimosDigitosFinNit
     *
     * @return string
     */
    public function getDosUltimosDigitosFinNit()
    {
        return $this->dosUltimosDigitosFinNit;
    }
}
