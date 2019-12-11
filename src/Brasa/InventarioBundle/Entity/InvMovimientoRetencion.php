<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="inv_movimiento_retencion")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvMovimientoRetencionRepository")
 */
class InvMovimientoRetencion
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_retencion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoMovimientoRetencionPk;
    
    /**
     * @ORM\Column(name="codigo_movimiento_fk", type="integer", nullable=true)
     */     
    private $codigoMovimientoFk; 

    /**
     * @ORM\Column(name="codigo_concepto_retencion_fk", type="integer", nullable=true)
     */     
    private $codigoConceptoRetencionFk;     
    
    /**
     * @ORM\Column(name="base_retencion", type="float")
     */    
    private $baseRetencion = 0;    
    
    /**
     * @ORM\Column(name="porcentaje_retencion", type="float")
     */    
    private $porcentajeRetencion = 0;      
    
    /**
     * @ORM\Column(name="vr_retencion", type="float")
     */    
    private $vrRetencion = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=40, nullable=true)
     */      
    private $comentarios;            
     
    


    /**
     * Get codigoMovimientoRetencionPk
     *
     * @return integer 
     */
    public function getCodigoMovimientoRetencionPk()
    {
        return $this->codigoMovimientoRetencionPk;
    }

    /**
     * Set codigoMovimientoFk
     *
     * @param integer $codigoMovimientoFk
     * @return InvMovimientoRetencion
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
     * Set codigoConceptoRetencionFk
     *
     * @param integer $codigoConceptoRetencionFk
     * @return InvMovimientoRetencion
     */
    public function setCodigoConceptoRetencionFk($codigoConceptoRetencionFk)
    {
        $this->codigoConceptoRetencionFk = $codigoConceptoRetencionFk;

        return $this;
    }

    /**
     * Get codigoConceptoRetencionFk
     *
     * @return integer 
     */
    public function getCodigoConceptoRetencionFk()
    {
        return $this->codigoConceptoRetencionFk;
    }

    /**
     * Set baseRetencion
     *
     * @param float $baseRetencion
     * @return InvMovimientoRetencion
     */
    public function setBaseRetencion($baseRetencion)
    {
        $this->baseRetencion = $baseRetencion;

        return $this;
    }

    /**
     * Get baseRetencion
     *
     * @return float 
     */
    public function getBaseRetencion()
    {
        return $this->baseRetencion;
    }

    /**
     * Set porcentajeRetencion
     *
     * @param float $porcentajeRetencion
     * @return InvMovimientoRetencion
     */
    public function setPorcentajeRetencion($porcentajeRetencion)
    {
        $this->porcentajeRetencion = $porcentajeRetencion;

        return $this;
    }

    /**
     * Get porcentajeRetencion
     *
     * @return float 
     */
    public function getPorcentajeRetencion()
    {
        return $this->porcentajeRetencion;
    }

    /**
     * Set vrRetencion
     *
     * @param float $vrRetencion
     * @return InvMovimientoRetencion
     */
    public function setVrRetencion($vrRetencion)
    {
        $this->vrRetencion = $vrRetencion;

        return $this;
    }

    /**
     * Get vrRetencion
     *
     * @return float 
     */
    public function getVrRetencion()
    {
        return $this->vrRetencion;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     * @return InvMovimientoRetencion
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string 
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }
}
