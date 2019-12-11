<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_novedad")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteNovedadRepository")
 */
class TteNovedad
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_novedad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoNovedadPk;  
    
    /**
     * @ORM\Column(name="fecha_reporte", type="datetime", nullable=true)
     */    
    private $fechaReporte;    
    
    /**
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=true)
     */    
    private $fechaRegistro; 
    
    /**
     * @ORM\Column(name="fecha_novedad", type="datetime", nullable=true)
     */    
    private $fechaNovedad;  
    
    /**
     * @ORM\Column(name="fecha_registro_solucion", type="datetime", nullable=true)
     */    
    private $fechaRegistroSolucion;     
    
    /**
     * @ORM\Column(name="fecha_solucion", type="datetime", nullable=true)
     */    
    private $fechaSolucion;    
    
    /**
     * @ORM\Column(name="codigo_guia_fk", type="integer", nullable=true)
     */    
    private $codigoGuiaFk;     
    
    /**
     * @ORM\Column(name="codigo_novedad_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoNovedadConceptoFk; 

    /**
     * @ORM\Column(name="novedad", type="string", length=500, nullable=true)
     */    
    private $novedad;     
    
    /**
     * @ORM\Column(name="solucion", type="string", length=500, nullable=true)
     */    
    private $solucion;
    
    /**
     * @ORM\Column(name="estado_solucionada", type="boolean")
     */    
    private $estadoSolucionada = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TteNovedadConcepto", inversedBy="novedadesRel")
     * @ORM\JoinColumn(name="codigo_novedad_concepto_fk", referencedColumnName="codigo_novedad_concepto_pk")
     */
    protected $novedadConceptoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="TteGuia", inversedBy="novedadesRel")
     * @ORM\JoinColumn(name="codigo_guia_fk", referencedColumnName="codigo_guia_pk")
     */
    protected $guiaRel; 
    
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
     * Set nombre
     *
     * @param string $nombre
     * @return TteNovedad
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
     * Set codigoNovedadConceptoFk
     *
     * @param integer $codigoNovedadConceptoFk
     * @return TteNovedad
     */
    public function setCodigoNovedadConceptoFk($codigoNovedadConceptoFk)
    {
        $this->codigoNovedadConceptoFk = $codigoNovedadConceptoFk;

        return $this;
    }

    /**
     * Get codigoNovedadConceptoFk
     *
     * @return integer 
     */
    public function getCodigoNovedadConceptoFk()
    {
        return $this->codigoNovedadConceptoFk;
    }

    /**
     * Set novedadConceptoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteNovedadConcepto $novedadConceptoRel
     * @return TteNovedad
     */
    public function setNovedadConceptoRel(\Brasa\TransporteBundle\Entity\TteNovedadConcepto $novedadConceptoRel = null)
    {
        $this->novedadConceptoRel = $novedadConceptoRel;

        return $this;
    }

    /**
     * Get novedadConceptoRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteNovedadConcepto 
     */
    public function getNovedadConceptoRel()
    {
        return $this->novedadConceptoRel;
    }

    /**
     * Set codigoGuiaFk
     *
     * @param integer $codigoGuiaFk
     * @return TteNovedad
     */
    public function setCodigoGuiaFk($codigoGuiaFk)
    {
        $this->codigoGuiaFk = $codigoGuiaFk;

        return $this;
    }

    /**
     * Get codigoGuiaFk
     *
     * @return integer 
     */
    public function getCodigoGuiaFk()
    {
        return $this->codigoGuiaFk;
    }

    /**
     * Set guiaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiaRel
     * @return TteNovedad
     */
    public function setGuiaRel(\Brasa\TransporteBundle\Entity\TteGuia $guiaRel = null)
    {
        $this->guiaRel = $guiaRel;

        return $this;
    }

    /**
     * Get guiaRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteGuia 
     */
    public function getGuiaRel()
    {
        return $this->guiaRel;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     * @return TteNovedad
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
     * Set fechaReporte
     *
     * @param \DateTime $fechaReporte
     * @return TteNovedad
     */
    public function setFechaReporte($fechaReporte)
    {
        $this->fechaReporte = $fechaReporte;

        return $this;
    }

    /**
     * Get fechaReporte
     *
     * @return \DateTime 
     */
    public function getFechaReporte()
    {
        return $this->fechaReporte;
    }

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return TteNovedad
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime 
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Set fechaNovedad
     *
     * @param \DateTime $fechaNovedad
     * @return TteNovedad
     */
    public function setFechaNovedad($fechaNovedad)
    {
        $this->fechaNovedad = $fechaNovedad;

        return $this;
    }

    /**
     * Get fechaNovedad
     *
     * @return \DateTime 
     */
    public function getFechaNovedad()
    {
        return $this->fechaNovedad;
    }

    /**
     * Set fechaSolucion
     *
     * @param \DateTime $fechaSolucion
     * @return TteNovedad
     */
    public function setFechaSolucion($fechaSolucion)
    {
        $this->fechaSolucion = $fechaSolucion;

        return $this;
    }

    /**
     * Get fechaSolucion
     *
     * @return \DateTime 
     */
    public function getFechaSolucion()
    {
        return $this->fechaSolucion;
    }

    /**
     * Set novedad
     *
     * @param string $novedad
     * @return TteNovedad
     */
    public function setNovedad($novedad)
    {
        $this->novedad = $novedad;

        return $this;
    }

    /**
     * Get novedad
     *
     * @return string 
     */
    public function getNovedad()
    {
        return $this->novedad;
    }

    /**
     * Set solucion
     *
     * @param string $solucion
     * @return TteNovedad
     */
    public function setSolucion($solucion)
    {
        $this->solucion = $solucion;

        return $this;
    }

    /**
     * Get solucion
     *
     * @return string 
     */
    public function getSolucion()
    {
        return $this->solucion;
    }

    /**
     * Set estadoSolucionada
     *
     * @param boolean $estadoSolucionada
     * @return TteNovedad
     */
    public function setEstadoSolucionada($estadoSolucionada)
    {
        $this->estadoSolucionada = $estadoSolucionada;

        return $this;
    }

    /**
     * Get estadoSolucionada
     *
     * @return boolean 
     */
    public function getEstadoSolucionada()
    {
        return $this->estadoSolucionada;
    }

    /**
     * Set fechaReporteSolucion
     *
     * @param \DateTime $fechaReporteSolucion
     * @return TteNovedad
     */
    public function setFechaReporteSolucion($fechaReporteSolucion)
    {
        $this->fechaReporteSolucion = $fechaReporteSolucion;

        return $this;
    }

    /**
     * Get fechaReporteSolucion
     *
     * @return \DateTime 
     */
    public function getFechaReporteSolucion()
    {
        return $this->fechaReporteSolucion;
    }

    /**
     * Set fechaRegistroSolucion
     *
     * @param \DateTime $fechaRegistroSolucion
     * @return TteNovedad
     */
    public function setFechaRegistroSolucion($fechaRegistroSolucion)
    {
        $this->fechaRegistroSolucion = $fechaRegistroSolucion;

        return $this;
    }

    /**
     * Get fechaRegistroSolucion
     *
     * @return \DateTime 
     */
    public function getFechaRegistroSolucion()
    {
        return $this->fechaRegistroSolucion;
    }
}
