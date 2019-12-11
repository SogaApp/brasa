<?php

namespace Brasa\ContabilidadBundle\Entity;


use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_cuenta")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbCuentaRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoCuentaPk"},message="Ya existe el código de la cuenta")
 */
class CtbCuenta
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cuenta_pk", type="string", length=20)
     */        
    private $codigoCuentaPk;
    
    /**
     * @ORM\Column(name="nombre_cuenta", type="string", length=120)
     */     
    private $nombreCuenta;     
    
    /**
     * @ORM\Column(name="codigo_cuenta_padre_fk", type="string", length=20, nullable=true)
     */ 
    private $codigo_cuenta_padre_fk;    

    /**
     * @ORM\Column(name="permite_movimientos", type="boolean")
     */    
    private $permiteMovimientos = 0;      

    /**
     * @ORM\Column(name="exige_nit", type="boolean")
     */    
    private $exigeNit = 0;    
    
    /**
     * @ORM\Column(name="exige_centro_costos", type="boolean")
     */    
    private $exigeCentroCostos = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_retencion", type="float")
     */    
    private $porcentajeRetencion = 0; 
    
    /**
     * @ORM\OneToMany(targetEntity="CtbAsientoDetalle", mappedBy="cuentaRel")
     */
    protected $asientosDetallesCuentaRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CtbRegistro", mappedBy="cuentaRel")
     */
    protected $registrosCuentasRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->asientosDetallesCuentaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->registrosCuentasRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoCuentaPk
     *
     * @param string $codigoCuentaPk
     *
     * @return CtbCuenta
     */
    public function setCodigoCuentaPk($codigoCuentaPk)
    {
        $this->codigoCuentaPk = $codigoCuentaPk;

        return $this;
    }

    /**
     * Get codigoCuentaPk
     *
     * @return string
     */
    public function getCodigoCuentaPk()
    {
        return $this->codigoCuentaPk;
    }

    /**
     * Set nombreCuenta
     *
     * @param string $nombreCuenta
     *
     * @return CtbCuenta
     */
    public function setNombreCuenta($nombreCuenta)
    {
        $this->nombreCuenta = $nombreCuenta;

        return $this;
    }

    /**
     * Get nombreCuenta
     *
     * @return string
     */
    public function getNombreCuenta()
    {
        return $this->nombreCuenta;
    }

    /**
     * Set codigoCuentaPadreFk
     *
     * @param string $codigoCuentaPadreFk
     *
     * @return CtbCuenta
     */
    public function setCodigoCuentaPadreFk($codigoCuentaPadreFk)
    {
        $this->codigo_cuenta_padre_fk = $codigoCuentaPadreFk;

        return $this;
    }

    /**
     * Get codigoCuentaPadreFk
     *
     * @return string
     */
    public function getCodigoCuentaPadreFk()
    {
        return $this->codigo_cuenta_padre_fk;
    }

    /**
     * Set permiteMovimientos
     *
     * @param boolean $permiteMovimientos
     *
     * @return CtbCuenta
     */
    public function setPermiteMovimientos($permiteMovimientos)
    {
        $this->permiteMovimientos = $permiteMovimientos;

        return $this;
    }

    /**
     * Get permiteMovimientos
     *
     * @return boolean
     */
    public function getPermiteMovimientos()
    {
        return $this->permiteMovimientos;
    }

    /**
     * Set exigeNit
     *
     * @param boolean $exigeNit
     *
     * @return CtbCuenta
     */
    public function setExigeNit($exigeNit)
    {
        $this->exigeNit = $exigeNit;

        return $this;
    }

    /**
     * Get exigeNit
     *
     * @return boolean
     */
    public function getExigeNit()
    {
        return $this->exigeNit;
    }

    /**
     * Set exigeCentroCostos
     *
     * @param boolean $exigeCentroCostos
     *
     * @return CtbCuenta
     */
    public function setExigeCentroCostos($exigeCentroCostos)
    {
        $this->exigeCentroCostos = $exigeCentroCostos;

        return $this;
    }

    /**
     * Get exigeCentroCostos
     *
     * @return boolean
     */
    public function getExigeCentroCostos()
    {
        return $this->exigeCentroCostos;
    }

    /**
     * Set porcentajeRetencion
     *
     * @param float $porcentajeRetencion
     *
     * @return CtbCuenta
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
     * Add asientosDetallesCuentaRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCuentaRel
     *
     * @return CtbCuenta
     */
    public function addAsientosDetallesCuentaRel(\Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCuentaRel)
    {
        $this->asientosDetallesCuentaRel[] = $asientosDetallesCuentaRel;

        return $this;
    }

    /**
     * Remove asientosDetallesCuentaRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCuentaRel
     */
    public function removeAsientosDetallesCuentaRel(\Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCuentaRel)
    {
        $this->asientosDetallesCuentaRel->removeElement($asientosDetallesCuentaRel);
    }

    /**
     * Get asientosDetallesCuentaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAsientosDetallesCuentaRel()
    {
        return $this->asientosDetallesCuentaRel;
    }

    /**
     * Add registrosCuentasRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbRegistro $registrosCuentasRel
     *
     * @return CtbCuenta
     */
    public function addRegistrosCuentasRel(\Brasa\ContabilidadBundle\Entity\CtbRegistro $registrosCuentasRel)
    {
        $this->registrosCuentasRel[] = $registrosCuentasRel;

        return $this;
    }

    /**
     * Remove registrosCuentasRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbRegistro $registrosCuentasRel
     */
    public function removeRegistrosCuentasRel(\Brasa\ContabilidadBundle\Entity\CtbRegistro $registrosCuentasRel)
    {
        $this->registrosCuentasRel->removeElement($registrosCuentasRel);
    }

    /**
     * Get registrosCuentasRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegistrosCuentasRel()
    {
        return $this->registrosCuentasRel;
    }
}
