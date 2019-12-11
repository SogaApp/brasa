<?php

namespace Brasa\TesoreriaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tes_cuenta_pagar")
 * @ORM\Entity(repositoryClass="Brasa\TesoreriaBundle\Repository\TesCuentaPagarRepository")
 */
class TesCuentaPagar
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cuenta_pagar_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoCuentaPagarPk;        

    /**
     * @ORM\Column(name="fecha", type="date")
     */    
    private $fecha;     
    
    /**
     * @ORM\Column(name="codigo_movimiento_fk", type="integer", nullable=true)
     */     
    private $codigoMovimientoFk;
    
    /**
     * @ORM\Column(name="soporte", type="string", length=30, nullable=true)
     */    
    private $soporte;    
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer")
     */    
    private $codigoTerceroFk;        
    
    /**
     * @ORM\Column(name="condicion", type="integer")
     */    
    private $condicion = 0;
    
    /**
     * @ORM\Column(name="valor_original", type="float")
     */    
    private $valorOriginal = 0;    
    
    /**
     * @ORM\Column(name="saldo", type="float")
     */    
    private $saldo = 0;        
    
    /**
     * @ORM\Column(name="debitos", type="float")
     */    
    private $debitos = 0;
    
    /**
     * @ORM\Column(name="creditos", type="float")
     */    
    private $creditos = 0;              
   

    /**
     * Get codigoCuentaPagarPk
     *
     * @return integer
     */
    public function getCodigoCuentaPagarPk()
    {
        return $this->codigoCuentaPagarPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TesCuentaPagar
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set codigoMovimientoFk
     *
     * @param integer $codigoMovimientoFk
     *
     * @return TesCuentaPagar
     */
    public function setCodigoMovimientoFk($codigoMovimientoFk)
    {
        $this->codigoMovimientoFk = $codigoMovimientoFk;

        return $this;
    }

    /**
     * Get codigoMovimientoFk
     *
     * @return integer
     */
    public function getCodigoMovimientoFk()
    {
        return $this->codigoMovimientoFk;
    }

    /**
     * Set soporte
     *
     * @param string $soporte
     *
     * @return TesCuentaPagar
     */
    public function setSoporte($soporte)
    {
        $this->soporte = $soporte;

        return $this;
    }

    /**
     * Get soporte
     *
     * @return string
     */
    public function getSoporte()
    {
        return $this->soporte;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return TesCuentaPagar
     */
    public function setCodigoTerceroFk($codigoTerceroFk)
    {
        $this->codigoTerceroFk = $codigoTerceroFk;

        return $this;
    }

    /**
     * Get codigoTerceroFk
     *
     * @return integer
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * Set condicion
     *
     * @param integer $condicion
     *
     * @return TesCuentaPagar
     */
    public function setCondicion($condicion)
    {
        $this->condicion = $condicion;

        return $this;
    }

    /**
     * Get condicion
     *
     * @return integer
     */
    public function getCondicion()
    {
        return $this->condicion;
    }

    /**
     * Set valorOriginal
     *
     * @param float $valorOriginal
     *
     * @return TesCuentaPagar
     */
    public function setValorOriginal($valorOriginal)
    {
        $this->valorOriginal = $valorOriginal;

        return $this;
    }

    /**
     * Get valorOriginal
     *
     * @return float
     */
    public function getValorOriginal()
    {
        return $this->valorOriginal;
    }

    /**
     * Set saldo
     *
     * @param float $saldo
     *
     * @return TesCuentaPagar
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;

        return $this;
    }

    /**
     * Get saldo
     *
     * @return float
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Set debitos
     *
     * @param float $debitos
     *
     * @return TesCuentaPagar
     */
    public function setDebitos($debitos)
    {
        $this->debitos = $debitos;

        return $this;
    }

    /**
     * Get debitos
     *
     * @return float
     */
    public function getDebitos()
    {
        return $this->debitos;
    }

    /**
     * Set creditos
     *
     * @param float $creditos
     *
     * @return TesCuentaPagar
     */
    public function setCreditos($creditos)
    {
        $this->creditos = $creditos;

        return $this;
    }

    /**
     * Get creditos
     *
     * @return float
     */
    public function getCreditos()
    {
        return $this->creditos;
    }
}
