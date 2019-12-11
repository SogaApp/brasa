<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_banco")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbBancoRepository")
 */
class CtbBanco
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_banco_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoBancoPk;                              

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;         

    /**
     * @ORM\Column(name="numero_cuenta", type="string", length=40, nullable=true)
     */    
    private $numeroCuenta;    
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20)
     */     
    private $codigoCuentaFk;          
    
    /**
     * @ORM\Column(name="codigo", type="string", length=80, nullable=true)
     */    
    private $codigo;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbCuenta", inversedBy="CtbBanco")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;       
         


    /**
     * Get codigoBancoPk
     *
     * @return integer
     */
    public function getCodigoBancoPk()
    {
        return $this->codigoBancoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CtbBanco
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
     * Set numeroCuenta
     *
     * @param string $numeroCuenta
     *
     * @return CtbBanco
     */
    public function setNumeroCuenta($numeroCuenta)
    {
        $this->numeroCuenta = $numeroCuenta;

        return $this;
    }

    /**
     * Get numeroCuenta
     *
     * @return string
     */
    public function getNumeroCuenta()
    {
        return $this->numeroCuenta;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return CtbBanco
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
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return CtbBanco
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
     * Set codigo
     *
     * @param string $codigo
     *
     * @return CtbBanco
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set cuentaRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaRel
     *
     * @return CtbBanco
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
