<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_movimiento_resumen")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbMovimientoResumenRepository")
 */
class CtbMovimientoResumen
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_resumen_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoMovimientoResumenPk;       

    /**
     * @ORM\Column(name="codigo_cierre_mes_fk", type="integer")
     */     
    private $codigoCierreMesFk;     
    
    /**
     * @ORM\Column(name="annio", type="integer")
     */    
    private $annio;    

    /**
     * @ORM\Column(name="mes", type="smallint")
     */    
    private $mes;
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20)
     */     
    private $codigoCuentaFk;     
    
    /**
     * @ORM\Column(name="debito", type="float")
     */    
    private $debito = 0;
    
    /**
     * @ORM\Column(name="credito", type="float")
     */    
    private $credito = 0; 
    
    /**
     * @ORM\Column(name="base", type="float")
     */    
    private $base = 0;     
  
    /**
     * @ORM\ManyToOne(targetEntity="CtbCuenta", inversedBy="CtbMovimientoResumen")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;     

    /**
     * @ORM\ManyToOne(targetEntity="CtbCierreMes", inversedBy="CtbMovimientoResumen")
     * @ORM\JoinColumn(name="codigo_cierre_mes_fk", referencedColumnName="codigo_cierre_mes_pk")
     */
    protected $ciereMesContabilidadRel;     

    


    /**
     * Get codigoMovimientoResumenPk
     *
     * @return integer
     */
    public function getCodigoMovimientoResumenPk()
    {
        return $this->codigoMovimientoResumenPk;
    }

    /**
     * Set codigoCierreMesFk
     *
     * @param integer $codigoCierreMesFk
     *
     * @return CtbMovimientoResumen
     */
    public function setCodigoCierreMesFk($codigoCierreMesFk)
    {
        $this->codigoCierreMesFk = $codigoCierreMesFk;

        return $this;
    }

    /**
     * Get codigoCierreMesFk
     *
     * @return integer
     */
    public function getCodigoCierreMesFk()
    {
        return $this->codigoCierreMesFk;
    }

    /**
     * Set annio
     *
     * @param integer $annio
     *
     * @return CtbMovimientoResumen
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
     *
     * @return CtbMovimientoResumen
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
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return CtbMovimientoResumen
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return string
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * Set debito
     *
     * @param float $debito
     *
     * @return CtbMovimientoResumen
     */
    public function setDebito($debito)
    {
        $this->debito = $debito;

        return $this;
    }

    /**
     * Get debito
     *
     * @return float
     */
    public function getDebito()
    {
        return $this->debito;
    }

    /**
     * Set credito
     *
     * @param float $credito
     *
     * @return CtbMovimientoResumen
     */
    public function setCredito($credito)
    {
        $this->credito = $credito;

        return $this;
    }

    /**
     * Get credito
     *
     * @return float
     */
    public function getCredito()
    {
        return $this->credito;
    }

    /**
     * Set base
     *
     * @param float $base
     *
     * @return CtbMovimientoResumen
     */
    public function setBase($base)
    {
        $this->base = $base;

        return $this;
    }

    /**
     * Get base
     *
     * @return float
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Set cuentaRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaRel
     *
     * @return CtbMovimientoResumen
     */
    public function setCuentaRel(\Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaRel = null)
    {
        $this->cuentaRel = $cuentaRel;

        return $this;
    }

    /**
     * Get cuentaRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbCuenta
     */
    public function getCuentaRel()
    {
        return $this->cuentaRel;
    }

    /**
     * Set ciereMesContabilidadRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCierreMes $ciereMesContabilidadRel
     *
     * @return CtbMovimientoResumen
     */
    public function setCiereMesContabilidadRel(\Brasa\ContabilidadBundle\Entity\CtbCierreMes $ciereMesContabilidadRel = null)
    {
        $this->ciereMesContabilidadRel = $ciereMesContabilidadRel;

        return $this;
    }

    /**
     * Get ciereMesContabilidadRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbCierreMes
     */
    public function getCiereMesContabilidadRel()
    {
        return $this->ciereMesContabilidadRel;
    }
}
