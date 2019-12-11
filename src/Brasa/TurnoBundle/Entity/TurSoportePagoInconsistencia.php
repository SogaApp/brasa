<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_soporte_pago_inconsistencia")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurSoportePagoInconsistenciaRepository")
 */
class TurSoportePagoInconsistencia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_soporte_pago_inconsistencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSoportePagoInconsistenciaPk;         
    
    /**
     * @ORM\Column(name="codigo_soporte_pago_periodo_fk", type="integer", nullable=true)
     */    
    private $codigoSoportePagoPeriodoFk;    
    
    /**
     * @ORM\Column(name="detalle", type="string", length=200, nullable=true)
     */    
    private $detalle;                    

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */    
    private $numeroIdentificacion;      
    
    /**
     * @ORM\Column(name="codigo_recurso", type="integer", nullable=true)
     */    
    private $codigoRecurso;  
    
    /**
     * @ORM\Column(name="recurso", type="string", length=120, nullable=true)
     */    
    private $recurso;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurSoportePagoPeriodo", inversedBy="soportesPagosInconsistenciasSoportePagoPeriodoRel")
     * @ORM\JoinColumn(name="codigo_soporte_pago_periodo_fk", referencedColumnName="codigo_soporte_pago_periodo_pk")
     */
    protected $soportePagoPeriodoRel;    
    

    /**
     * Get codigoSoportePagoInconsistenciaPk
     *
     * @return integer
     */
    public function getCodigoSoportePagoInconsistenciaPk()
    {
        return $this->codigoSoportePagoInconsistenciaPk;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return TurSoportePagoInconsistencia
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return TurSoportePagoInconsistencia
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
     * Set codigoRecurso
     *
     * @param integer $codigoRecurso
     *
     * @return TurSoportePagoInconsistencia
     */
    public function setCodigoRecurso($codigoRecurso)
    {
        $this->codigoRecurso = $codigoRecurso;

        return $this;
    }

    /**
     * Get codigoRecurso
     *
     * @return integer
     */
    public function getCodigoRecurso()
    {
        return $this->codigoRecurso;
    }

    /**
     * Set codigoSoportePagoPeriodoFk
     *
     * @param integer $codigoSoportePagoPeriodoFk
     *
     * @return TurSoportePagoInconsistencia
     */
    public function setCodigoSoportePagoPeriodoFk($codigoSoportePagoPeriodoFk)
    {
        $this->codigoSoportePagoPeriodoFk = $codigoSoportePagoPeriodoFk;

        return $this;
    }

    /**
     * Get codigoSoportePagoPeriodoFk
     *
     * @return integer
     */
    public function getCodigoSoportePagoPeriodoFk()
    {
        return $this->codigoSoportePagoPeriodoFk;
    }

    /**
     * Set soportePagoPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportePagoPeriodoRel
     *
     * @return TurSoportePagoInconsistencia
     */
    public function setSoportePagoPeriodoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportePagoPeriodoRel = null)
    {
        $this->soportePagoPeriodoRel = $soportePagoPeriodoRel;

        return $this;
    }

    /**
     * Get soportePagoPeriodoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo
     */
    public function getSoportePagoPeriodoRel()
    {
        return $this->soportePagoPeriodoRel;
    }

    /**
     * Set recurso
     *
     * @param string $recurso
     *
     * @return TurSoportePagoInconsistencia
     */
    public function setRecurso($recurso)
    {
        $this->recurso = $recurso;

        return $this;
    }

    /**
     * Get recurso
     *
     * @return string
     */
    public function getRecurso()
    {
        return $this->recurso;
    }
}
