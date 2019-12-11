<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_asiento")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbAsientoRepository")
 */
class CtbAsiento
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_asiento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoAsientoPk;    
    
    /**
     * @ORM\Column(name="numero_asiento", type="string", length=30, nullable=true)
     */     
    private $numeroAsiento;    

    /**
     * @ORM\Column(name="soporte", type="string", length=30, nullable=true)
     */    
    private $soporte;      
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     

    /**
     * @ORM\Column(name="fecha_creacion", type="date", nullable=true)
     */    
    private $fecha_creacion; 
    
    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="integer", nullable=true)
     */     
    private $codigoComprobanteFk;
    
    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;    

    /**
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpreso = 0;    
    
    /**
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = 0;    
    
    /**
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = 0;    
    
    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean")
     */    
    private $estadoContabilizado = 0;     
    
    /**
     * @ORM\Column(name="total_debito", type="float")
     */
    private $totalDebito = 0;    
    
    /**
     * @ORM\Column(name="total_credito", type="float")
     */
    private $totalCredito = 0;
    
    /**
     * @ORM\Column(name="diferencia", type="float")
     */
    private $diferencia = 0;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;                 

    /**
     * @ORM\ManyToOne(targetEntity="CtbComprobante", inversedBy="asientosComprobanteRel")
     * @ORM\JoinColumn(name="codigo_comprobante_fk", referencedColumnName="codigo_comprobante_pk")
     */
    protected $comprobanteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CtbAsientoDetalle", mappedBy="asientoRel")
     */
    protected $asientosDetallesAsientoRel;

    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->asientosDetallesAsientoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoAsientoPk
     *
     * @return integer
     */
    public function getCodigoAsientoPk()
    {
        return $this->codigoAsientoPk;
    }

    /**
     * Set numeroAsiento
     *
     * @param string $numeroAsiento
     *
     * @return CtbAsiento
     */
    public function setNumeroAsiento($numeroAsiento)
    {
        $this->numeroAsiento = $numeroAsiento;

        return $this;
    }

    /**
     * Get numeroAsiento
     *
     * @return string
     */
    public function getNumeroAsiento()
    {
        return $this->numeroAsiento;
    }

    /**
     * Set soporte
     *
     * @param string $soporte
     *
     * @return CtbAsiento
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CtbAsiento
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return CtbAsiento
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fecha_creacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fecha_creacion;
    }

    /**
     * Set codigoComprobanteFk
     *
     * @param integer $codigoComprobanteFk
     *
     * @return CtbAsiento
     */
    public function setCodigoComprobanteFk($codigoComprobanteFk)
    {
        $this->codigoComprobanteFk = $codigoComprobanteFk;

        return $this;
    }

    /**
     * Get codigoComprobanteFk
     *
     * @return integer
     */
    public function getCodigoComprobanteFk()
    {
        return $this->codigoComprobanteFk;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return CtbAsiento
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set estadoImpreso
     *
     * @param boolean $estadoImpreso
     *
     * @return CtbAsiento
     */
    public function setEstadoImpreso($estadoImpreso)
    {
        $this->estadoImpreso = $estadoImpreso;

        return $this;
    }

    /**
     * Get estadoImpreso
     *
     * @return boolean
     */
    public function getEstadoImpreso()
    {
        return $this->estadoImpreso;
    }

    /**
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return CtbAsiento
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     *
     * @return CtbAsiento
     */
    public function setEstadoAnulado($estadoAnulado)
    {
        $this->estadoAnulado = $estadoAnulado;

        return $this;
    }

    /**
     * Get estadoAnulado
     *
     * @return boolean
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * Set estadoContabilizado
     *
     * @param boolean $estadoContabilizado
     *
     * @return CtbAsiento
     */
    public function setEstadoContabilizado($estadoContabilizado)
    {
        $this->estadoContabilizado = $estadoContabilizado;

        return $this;
    }

    /**
     * Get estadoContabilizado
     *
     * @return boolean
     */
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * Set totalDebito
     *
     * @param float $totalDebito
     *
     * @return CtbAsiento
     */
    public function setTotalDebito($totalDebito)
    {
        $this->totalDebito = $totalDebito;

        return $this;
    }

    /**
     * Get totalDebito
     *
     * @return float
     */
    public function getTotalDebito()
    {
        return $this->totalDebito;
    }

    /**
     * Set totalCredito
     *
     * @param float $totalCredito
     *
     * @return CtbAsiento
     */
    public function setTotalCredito($totalCredito)
    {
        $this->totalCredito = $totalCredito;

        return $this;
    }

    /**
     * Get totalCredito
     *
     * @return float
     */
    public function getTotalCredito()
    {
        return $this->totalCredito;
    }

    /**
     * Set diferencia
     *
     * @param float $diferencia
     *
     * @return CtbAsiento
     */
    public function setDiferencia($diferencia)
    {
        $this->diferencia = $diferencia;

        return $this;
    }

    /**
     * Get diferencia
     *
     * @return float
     */
    public function getDiferencia()
    {
        return $this->diferencia;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return CtbAsiento
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

    /**
     * Set comprobanteRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbComprobante $comprobanteRel
     *
     * @return CtbAsiento
     */
    public function setComprobanteRel(\Brasa\ContabilidadBundle\Entity\CtbComprobante $comprobanteRel = null)
    {
        $this->comprobanteRel = $comprobanteRel;

        return $this;
    }

    /**
     * Get comprobanteRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbComprobante
     */
    public function getComprobanteRel()
    {
        return $this->comprobanteRel;
    }

    /**
     * Add asientosDetallesAsientoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesAsientoRel
     *
     * @return CtbAsiento
     */
    public function addAsientosDetallesAsientoRel(\Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesAsientoRel)
    {
        $this->asientosDetallesAsientoRel[] = $asientosDetallesAsientoRel;

        return $this;
    }

    /**
     * Remove asientosDetallesAsientoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesAsientoRel
     */
    public function removeAsientosDetallesAsientoRel(\Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesAsientoRel)
    {
        $this->asientosDetallesAsientoRel->removeElement($asientosDetallesAsientoRel);
    }

    /**
     * Get asientosDetallesAsientoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAsientosDetallesAsientoRel()
    {
        return $this->asientosDetallesAsientoRel;
    }
}
