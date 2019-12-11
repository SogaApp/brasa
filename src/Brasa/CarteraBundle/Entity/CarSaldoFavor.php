<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_saldo_favor")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarSaldoFavorRepository")
 */
class CarSaldoFavor
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_saldo_favor_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSaldoFavorPk;

    /**
     * @ORM\Column(name="codigo_recibo_fk", type="integer", nullable=true)
     */
    private $codigoReciboFk;

    /**
     * @ORM\Column(name="valor", type="integer", nullable=true)
     */
    private $valor;

    /**
     * @ORM\Column(name="saldo", type="integer", nullable=true)
     */
    private $saldo;

    /**
     * @ORM\Column(name="abono", type="integer", nullable=true)
     */
    private $abono;

    /**
     * @ORM\ManyToOne(targetEntity="CarRecibo", inversedBy="saldoFavorReciboRel")
     * @ORM\JoinColumn(name="codigo_recibo_fk", referencedColumnName="codigo_recibo_pk")
     */
    protected $reciboRel;

    /**
     * Get codigoSaldoFavorPk
     *
     * @return integer
     */
    public function getCodigoSaldoFavorPk()
    {
        return $this->codigoSaldoFavorPk;
    }

    /**
     * Set codigoReciboFk
     *
     * @param integer $codigoReciboFk
     *
     * @return CarSaldoFavor
     */
    public function setCodigoReciboFk($codigoReciboFk)
    {
        $this->codigoReciboFk = $codigoReciboFk;

        return $this;
    }

    /**
     * Get codigoReciboFk
     *
     * @return integer
     */
    public function getCodigoReciboFk()
    {
        return $this->codigoReciboFk;
    }

    /**
     * Set valor
     *
     * @param integer $valor
     *
     * @return CarSaldoFavor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return integer
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set saldo
     *
     * @param integer $saldo
     *
     * @return CarSaldoFavor
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;

        return $this;
    }

    /**
     * Get saldo
     *
     * @return integer
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Set abono
     *
     * @param integer $abono
     *
     * @return CarSaldoFavor
     */
    public function setAbono($abono)
    {
        $this->abono = $abono;

        return $this;
    }

    /**
     * Get abono
     *
     * @return integer
     */
    public function getAbono()
    {
        return $this->abono;
    }

    /**
     * Set reciboRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $reciboRel
     *
     * @return CarSaldoFavor
     */
    public function setReciboRel(\Brasa\CarteraBundle\Entity\CarRecibo $reciboRel = null)
    {
        $this->reciboRel = $reciboRel;

        return $this;
    }

    /**
     * Get reciboRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarRecibo
     */
    public function getReciboRel()
    {
        return $this->reciboRel;
    }
}
