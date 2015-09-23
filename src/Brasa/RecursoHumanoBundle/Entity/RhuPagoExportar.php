<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_exportar")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoExportarRepository")
 */
class RhuPagoExportar
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_exportar_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoExportarPk;
    
    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false)
     */
         
    private $numeroIdentificacion;
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */    
    private $nombreCorto;
    
    /**
     * @ORM\Column(name="cuenta", type="string", length=80, nullable=true)
     */    
    private $cuenta;
    
    /**
     * @ORM\Column(name="vr_pago", type="float")
     */
    private $vrPago = 0;
    
    /**
     * @ORM\Column(name="tipo", type="string", length=80, nullable=true)
     */    
    private $tipo;
    
    /**
     * @ORM\Column(name="soporte", type="string", length=80, nullable=true)
     */    
    private $soporte;

    

    /**
     * Get codigoPagoExportarPk
     *
     * @return integer
     */
    public function getCodigoPagoExportarPk()
    {
        return $this->codigoPagoExportarPk;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuPagoExportar
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuPagoExportar
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set cuenta
     *
     * @param string $cuenta
     *
     * @return RhuPagoExportar
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
     * Set vrPago
     *
     * @param float $vrPago
     *
     * @return RhuPagoExportar
     */
    public function setVrPago($vrPago)
    {
        $this->vrPago = $vrPago;

        return $this;
    }

    /**
     * Get vrPago
     *
     * @return float
     */
    public function getVrPago()
    {
        return $this->vrPago;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return RhuPagoExportar
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
     * Set soporte
     *
     * @param string $soporte
     *
     * @return RhuPagoExportar
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
}
