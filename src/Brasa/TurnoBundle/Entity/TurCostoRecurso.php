<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_costo_recurso")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCostoRecursoRepository")
 */
class TurCostoRecurso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_recurso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCostoRecursoPk;             
    
    /**
     * @ORM\Column(name="codigo_cierre_mes_fk", type="integer")
     */    
    private $codigoCierreMesFk;     
    
    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */    
    private $anio;    
    
    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */    
    private $mes;                     
    
    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoFk;    
    
    /**
     * @ORM\Column(name="vr_nomina", type="float")
     */
    private $vrNomina = 0;    
    
    /**
     * @ORM\Column(name="vr_prestaciones", type="float")
     */
    private $vrPrestaciones = 0;    
    
    /**
     * @ORM\Column(name="vr_aportes_sociales", type="float")
     */
    private $vrAportesSociales = 0;    

    /**
     * @ORM\Column(name="vr_costo_total", type="float")
     */
    private $vrCostoTotal = 0;
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="vr_hora", type="float")
     */
    private $vrHora = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCierreMes", inversedBy="costosRecursosCierreMesRel")
     * @ORM\JoinColumn(name="codigo_cierre_mes_fk", referencedColumnName="codigo_cierre_mes_pk")
     */
    protected $cierreMesRel;  

    /**
     * @ORM\ManyToOne(targetEntity="TurRecurso", inversedBy="costosRecursosRecursoRel")
     * @ORM\JoinColumn(name="codigo_recurso_fk", referencedColumnName="codigo_recurso_pk")
     */
    protected $recursoRel;
    

    /**
     * Get codigoCostoRecursoPk
     *
     * @return integer
     */
    public function getCodigoCostoRecursoPk()
    {
        return $this->codigoCostoRecursoPk;
    }

    /**
     * Set codigoCierreMesFk
     *
     * @param integer $codigoCierreMesFk
     *
     * @return TurCostoRecurso
     */
    public function setCodigoCierreMesFk($codigoCierreMesFk)
    {
        $this->codigoCierreMesFk = $codigoCierreMesFk;

        return $this;
    }

    /**
     * Get codigoCierreMesFk
     *
     * @return integer
     */
    public function getCodigoCierreMesFk()
    {
        return $this->codigoCierreMesFk;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return TurCostoRecurso
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return integer
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     *
     * @return TurCostoRecurso
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return integer
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set codigoRecursoFk
     *
     * @param integer $codigoRecursoFk
     *
     * @return TurCostoRecurso
     */
    public function setCodigoRecursoFk($codigoRecursoFk)
    {
        $this->codigoRecursoFk = $codigoRecursoFk;

        return $this;
    }

    /**
     * Get codigoRecursoFk
     *
     * @return integer
     */
    public function getCodigoRecursoFk()
    {
        return $this->codigoRecursoFk;
    }

    /**
     * Set vrNomina
     *
     * @param float $vrNomina
     *
     * @return TurCostoRecurso
     */
    public function setVrNomina($vrNomina)
    {
        $this->vrNomina = $vrNomina;

        return $this;
    }

    /**
     * Get vrNomina
     *
     * @return float
     */
    public function getVrNomina()
    {
        return $this->vrNomina;
    }

    /**
     * Set vrPrestaciones
     *
     * @param float $vrPrestaciones
     *
     * @return TurCostoRecurso
     */
    public function setVrPrestaciones($vrPrestaciones)
    {
        $this->vrPrestaciones = $vrPrestaciones;

        return $this;
    }

    /**
     * Get vrPrestaciones
     *
     * @return float
     */
    public function getVrPrestaciones()
    {
        return $this->vrPrestaciones;
    }

    /**
     * Set vrAportesSociales
     *
     * @param float $vrAportesSociales
     *
     * @return TurCostoRecurso
     */
    public function setVrAportesSociales($vrAportesSociales)
    {
        $this->vrAportesSociales = $vrAportesSociales;

        return $this;
    }

    /**
     * Get vrAportesSociales
     *
     * @return float
     */
    public function getVrAportesSociales()
    {
        return $this->vrAportesSociales;
    }

    /**
     * Set cierreMesRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCierreMes $cierreMesRel
     *
     * @return TurCostoRecurso
     */
    public function setCierreMesRel(\Brasa\TurnoBundle\Entity\TurCierreMes $cierreMesRel = null)
    {
        $this->cierreMesRel = $cierreMesRel;

        return $this;
    }

    /**
     * Get cierreMesRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCierreMes
     */
    public function getCierreMesRel()
    {
        return $this->cierreMesRel;
    }

    /**
     * Set recursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursoRel
     *
     * @return TurCostoRecurso
     */
    public function setRecursoRel(\Brasa\TurnoBundle\Entity\TurRecurso $recursoRel = null)
    {
        $this->recursoRel = $recursoRel;

        return $this;
    }

    /**
     * Get recursoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurRecurso
     */
    public function getRecursoRel()
    {
        return $this->recursoRel;
    }

    /**
     * Set vrCostoTotal
     *
     * @param float $vrCostoTotal
     *
     * @return TurCostoRecurso
     */
    public function setVrCostoTotal($vrCostoTotal)
    {
        $this->vrCostoTotal = $vrCostoTotal;

        return $this;
    }

    /**
     * Get vrCostoTotal
     *
     * @return float
     */
    public function getVrCostoTotal()
    {
        return $this->vrCostoTotal;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return TurCostoRecurso
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return integer
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set vrHora
     *
     * @param float $vrHora
     *
     * @return TurCostoRecurso
     */
    public function setVrHora($vrHora)
    {
        $this->vrHora = $vrHora;

        return $this;
    }

    /**
     * Get vrHora
     *
     * @return float
     */
    public function getVrHora()
    {
        return $this->vrHora;
    }
}
