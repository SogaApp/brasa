<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_consulta_pago_concepto")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuConsultaPagoConceptoRepository")
 */
class RhuConsultaPagoConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_consulta_pago_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoConsultaPagoConceptoPk;
    
    /**
     * @ORM\Column(name="origen", type="string", length=30, nullable=true)
     */    
    private $origen;    

    /**
     * @ORM\Column(name="numero", type="integer")
     */    
    private $numero = 0; 

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk = 0;    
    
    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */
         
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */    
    private $nombreCorto;

    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk; 

    /**
     * @ORM\Column(name="nombreConcepto", type="string", length=80, nullable=true)
     */    
    private $nombreConcepto; 

    /**
     * @ORM\Column(name="vr_deduccion", type="float")
     */
    private $vrDeduccion = 0;         

    /**
     * @ORM\Column(name="vr_bonificacion", type="float")
     */
    private $vrBonificacion = 0;    
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;     



    /**
     * Get codigoConsultaPagoConceptoPk
     *
     * @return integer
     */
    public function getCodigoConsultaPagoConceptoPk()
    {
        return $this->codigoConsultaPagoConceptoPk;
    }

    /**
     * Set origen
     *
     * @param string $origen
     *
     * @return RhuConsultaPagoConcepto
     */
    public function setOrigen($origen)
    {
        $this->origen = $origen;

        return $this;
    }

    /**
     * Get origen
     *
     * @return string
     */
    public function getOrigen()
    {
        return $this->origen;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return RhuConsultaPagoConcepto
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuConsultaPagoConcepto
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
     * @return RhuConsultaPagoConcepto
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
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuConsultaPagoConcepto
     */
    public function setCodigoPagoConceptoFk($codigoPagoConceptoFk)
    {
        $this->codigoPagoConceptoFk = $codigoPagoConceptoFk;

        return $this;
    }

    /**
     * Get codigoPagoConceptoFk
     *
     * @return integer
     */
    public function getCodigoPagoConceptoFk()
    {
        return $this->codigoPagoConceptoFk;
    }

    /**
     * Set nombreConcepto
     *
     * @param string $nombreConcepto
     *
     * @return RhuConsultaPagoConcepto
     */
    public function setNombreConcepto($nombreConcepto)
    {
        $this->nombreConcepto = $nombreConcepto;

        return $this;
    }

    /**
     * Get nombreConcepto
     *
     * @return string
     */
    public function getNombreConcepto()
    {
        return $this->nombreConcepto;
    }

    /**
     * Set vrDeduccion
     *
     * @param float $vrDeduccion
     *
     * @return RhuConsultaPagoConcepto
     */
    public function setVrDeduccion($vrDeduccion)
    {
        $this->vrDeduccion = $vrDeduccion;

        return $this;
    }

    /**
     * Get vrDeduccion
     *
     * @return float
     */
    public function getVrDeduccion()
    {
        return $this->vrDeduccion;
    }

    /**
     * Set vrBonificacion
     *
     * @param float $vrBonificacion
     *
     * @return RhuConsultaPagoConcepto
     */
    public function setVrBonificacion($vrBonificacion)
    {
        $this->vrBonificacion = $vrBonificacion;

        return $this;
    }

    /**
     * Get vrBonificacion
     *
     * @return float
     */
    public function getVrBonificacion()
    {
        return $this->vrBonificacion;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuConsultaPagoConcepto
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return RhuConsultaPagoConcepto
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuConsultaPagoConcepto
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk)
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }
}
