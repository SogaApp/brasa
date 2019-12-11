<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_registro_exportar")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbRegistroExportarRepository")
 */
class CtbRegistroExportar
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_registro_exportar_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoRegistroExportarPk;                      
    
    
    /**
     * @ORM\Column(name="comprobante", type="string", length=10, nullable=true)
     */     
    private $comprobante;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="numero", type="string", length=20, nullable=true)
     */     
    private $numero;
    
    /**
     * @ORM\Column(name="numero_referencia", type="string", length=20, nullable=true)
     */     
    private $numeroReferencia;    
    
    /**
     * @ORM\Column(name="cuenta", type="string", length=20, nullable=true)
     */     
    private $cuenta;      
    
    /**
     * @ORM\Column(name="nit", type="string", length=20, nullable=true)
     */     
    private $nit;

    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=3, nullable=true)
     */     
    private $digitoVerificacion;    
    
    /**
     * @ORM\Column(name="centro_costo", type="string", length=20, nullable=true)
     */     
    private $centroCosto;
    
    /**
     * @ORM\Column(name="tipo", type="string", length=1, nullable=true)
     */     
    private $tipo;    
    
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
     * @ORM\Column(name="descripcion_contable", type="string", length=80, nullable=true)
     */    
    private $descripcionContable;     
    


    /**
     * Get codigoRegistroExportarPk
     *
     * @return integer
     */
    public function getCodigoRegistroExportarPk()
    {
        return $this->codigoRegistroExportarPk;
    }

    /**
     * Set comprobante
     *
     * @param string $comprobante
     *
     * @return CtbRegistroExportar
     */
    public function setComprobante($comprobante)
    {
        $this->comprobante = $comprobante;

        return $this;
    }

    /**
     * Get comprobante
     *
     * @return string
     */
    public function getComprobante()
    {
        return $this->comprobante;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CtbRegistroExportar
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
     * Set numero
     *
     * @param string $numero
     *
     * @return CtbRegistroExportar
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set cuenta
     *
     * @param string $cuenta
     *
     * @return CtbRegistroExportar
     */
    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    /**
     * Get cuenta
     *
     * @return string
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return CtbRegistroExportar
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set centroCosto
     *
     * @param string $centroCosto
     *
     * @return CtbRegistroExportar
     */
    public function setCentroCosto($centroCosto)
    {
        $this->centroCosto = $centroCosto;

        return $this;
    }

    /**
     * Get centroCosto
     *
     * @return string
     */
    public function getCentroCosto()
    {
        return $this->centroCosto;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return CtbRegistroExportar
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
     * Set debito
     *
     * @param float $debito
     *
     * @return CtbRegistroExportar
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
     * @return CtbRegistroExportar
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
     * @return CtbRegistroExportar
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
     * Set descripcionContable
     *
     * @param string $descripcionContable
     *
     * @return CtbRegistroExportar
     */
    public function setDescripcionContable($descripcionContable)
    {
        $this->descripcionContable = $descripcionContable;

        return $this;
    }

    /**
     * Get descripcionContable
     *
     * @return string
     */
    public function getDescripcionContable()
    {
        return $this->descripcionContable;
    }

    /**
     * Set numeroReferencia
     *
     * @param string $numeroReferencia
     *
     * @return CtbRegistroExportar
     */
    public function setNumeroReferencia($numeroReferencia)
    {
        $this->numeroReferencia = $numeroReferencia;

        return $this;
    }

    /**
     * Get numeroReferencia
     *
     * @return string
     */
    public function getNumeroReferencia()
    {
        return $this->numeroReferencia;
    }

    /**
     * Set digitoVerificacion
     *
     * @param string $digitoVerificacion
     *
     * @return CtbRegistroExportar
     */
    public function setDigitoVerificacion($digitoVerificacion)
    {
        $this->digitoVerificacion = $digitoVerificacion;

        return $this;
    }

    /**
     * Get digitoVerificacion
     *
     * @return string
     */
    public function getDigitoVerificacion()
    {
        return $this->digitoVerificacion;
    }
}
