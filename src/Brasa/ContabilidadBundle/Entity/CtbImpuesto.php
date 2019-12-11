<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_impuesto")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbImpuestoRepository")
 */
class CtbImpuesto
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_impuesto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoImpuestoPk; 
    
    /**
     * @ORM\Column(name="nombre_impuesto", type="string", length=40)
     */     
    private $nombreImpuesto;
    
    /**
     * @ORM\Column(name="porcentaje", type="float")
     */     
    private $porcentaje = 0;
    
    /**
     * @ORM\Column(name="tope", type="integer")
     */     
    private $tope = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaFk;
    
    /**
     * @ORM\Column(name="tipo", type="integer")
     * Debito
     * Credito
     */     
    private $tipo = 0;        
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbCuenta", inversedBy="CtbImpuesto")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;    




    /**
     * Get codigoImpuestoPk
     *
     * @return integer
     */
    public function getCodigoImpuestoPk()
    {
        return $this->codigoImpuestoPk;
    }

    /**
     * Set nombreImpuesto
     *
     * @param string $nombreImpuesto
     *
     * @return CtbImpuesto
     */
    public function setNombreImpuesto($nombreImpuesto)
    {
        $this->nombreImpuesto = $nombreImpuesto;

        return $this;
    }

    /**
     * Get nombreImpuesto
     *
     * @return string
     */
    public function getNombreImpuesto()
    {
        return $this->nombreImpuesto;
    }

    /**
     * Set porcentaje
     *
     * @param float $porcentaje
     *
     * @return CtbImpuesto
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
     * Set tope
     *
     * @param integer $tope
     *
     * @return CtbImpuesto
     */
    public function setTope($tope)
    {
        $this->tope = $tope;

        return $this;
    }

    /**
     * Get tope
     *
     * @return integer
     */
    public function getTope()
    {
        return $this->tope;
    }

    /**
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return CtbImpuesto
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
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return CtbImpuesto
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set cuentaRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaRel
     *
     * @return CtbImpuesto
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
}
