<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_novedad")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiNovedadRepository")
 */
class AfiNovedad
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_novedad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoNovedadPk;    
       
    /**
     * @ORM\Column(name="codigo_novedad_tipo_fk", type="integer")
     */    
    private $codigoNovedadTipoFk;     
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;        

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;            
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;    

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContatoFk;
    
    /**
     * @ORM\Column(name="valor", type="float")
     */
    private $valor = 0; 
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiNovedadTipo", inversedBy="novedadesNovedadTipoRel")
     * @ORM\JoinColumn(name="codigo_novedad_tipo_fk", referencedColumnName="codigo_novedad_tipo_pk")
     */
    protected $novedadTipoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiEmpleado", inversedBy="novedadesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
 
    /**
     * @ORM\ManyToOne(targetEntity="AfiContrato", inversedBy="novedadesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;    


    /**
     * Get codigoNovedadPk
     *
     * @return integer
     */
    public function getCodigoNovedadPk()
    {
        return $this->codigoNovedadPk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return AfiNovedad
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
     * @return AfiNovedad
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
     * @return AfiNovedad
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

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return AfiNovedad
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadoRel
     *
     * @return AfiNovedad
     */
    public function setEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set codigoContatoFk
     *
     * @param integer $codigoContatoFk
     *
     * @return AfiNovedad
     */
    public function setCodigoContatoFk($codigoContatoFk)
    {
        $this->codigoContatoFk = $codigoContatoFk;

        return $this;
    }

    /**
     * Get codigoContatoFk
     *
     * @return integer
     */
    public function getCodigoContatoFk()
    {
        return $this->codigoContatoFk;
    }

    /**
     * Set contratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $contratoRel
     *
     * @return AfiNovedad
     */
    public function setContratoRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * Set codigoNovedadTipoFk
     *
     * @param integer $codigoNovedadTipoFk
     *
     * @return AfiNovedad
     */
    public function setCodigoNovedadTipoFk($codigoNovedadTipoFk)
    {
        $this->codigoNovedadTipoFk = $codigoNovedadTipoFk;

        return $this;
    }

    /**
     * Get codigoNovedadTipoFk
     *
     * @return integer
     */
    public function getCodigoNovedadTipoFk()
    {
        return $this->codigoNovedadTipoFk;
    }

    /**
     * Set novedadTipoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiNovedadTipo $novedadTipoRel
     *
     * @return AfiNovedad
     */
    public function setNovedadTipoRel(\Brasa\AfiliacionBundle\Entity\AfiNovedadTipo $novedadTipoRel = null)
    {
        $this->novedadTipoRel = $novedadTipoRel;

        return $this;
    }

    /**
     * Get novedadTipoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiNovedadTipo
     */
    public function getNovedadTipoRel()
    {
        return $this->novedadTipoRel;
    }
}
