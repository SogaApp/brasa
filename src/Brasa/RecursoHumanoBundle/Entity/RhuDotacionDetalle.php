<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_dotacion_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDotacionDetalleRepository")
 */
class RhuDotacionDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_dotacion_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDotacionDetallePk;                    
    
    /**
     * @ORM\Column(name="codigo_dotacion_fk", type="integer", nullable=true)
     */    
    private $codigoDotacionFk;
    
    /**
     * @ORM\Column(name="codigo_dotacion_elemento_fk", type="integer", nullable=true)
     */    
    private $codigoDotacionElementoFk;
    
    /**
     * @ORM\Column(name="codigo_dotacion_detalle_enlace_fk", type="integer", nullable=true)
     */    
    private $codigoDotacionDetalleEnlaceFk;
    
    /**
     * @ORM\Column(name="cantidad_asignada", type="integer", nullable=true)
     */    
    private $cantidadAsignada = 0;
    
    /**
     * @ORM\Column(name="cantidad_devuelta", type="integer", nullable=true)
     */    
    private $cantidadDevuelta = 0;   
    
    /**
     * @ORM\Column(name="serie", type="string", nullable=false)
     */
    private $serie;
    
    /**
     * @ORM\Column(name="lote", type="string", nullable=false)
     */
    private $lote;         
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuDotacion", inversedBy="dotacionesDetallesDotacionRel")
     * @ORM\JoinColumn(name="codigo_dotacion_fk", referencedColumnName="codigo_dotacion_pk")
     */
    protected $dotacionRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuDotacionElemento", inversedBy="elementosDotacionesDetalleDotacionElementoRel")
     * @ORM\JoinColumn(name="codigo_dotacion_elemento_fk", referencedColumnName="codigo_dotacion_elemento_pk")
     */
    protected $dotacionElementoRel;
   

    /**
     * Get codigoDotacionDetallePk
     *
     * @return integer
     */
    public function getCodigoDotacionDetallePk()
    {
        return $this->codigoDotacionDetallePk;
    }

    /**
     * Set codigoDotacionFk
     *
     * @param integer $codigoDotacionFk
     *
     * @return RhuDotacionDetalle
     */
    public function setCodigoDotacionFk($codigoDotacionFk)
    {
        $this->codigoDotacionFk = $codigoDotacionFk;

        return $this;
    }

    /**
     * Get codigoDotacionFk
     *
     * @return integer
     */
    public function getCodigoDotacionFk()
    {
        return $this->codigoDotacionFk;
    }

    /**
     * Set codigoDotacionElementoFk
     *
     * @param integer $codigoDotacionElementoFk
     *
     * @return RhuDotacionDetalle
     */
    public function setCodigoDotacionElementoFk($codigoDotacionElementoFk)
    {
        $this->codigoDotacionElementoFk = $codigoDotacionElementoFk;

        return $this;
    }

    /**
     * Get codigoDotacionElementoFk
     *
     * @return integer
     */
    public function getCodigoDotacionElementoFk()
    {
        return $this->codigoDotacionElementoFk;
    }

    /**
     * Set codigoDotacionDetalleEnlaceFk
     *
     * @param integer $codigoDotacionDetalleEnlaceFk
     *
     * @return RhuDotacionDetalle
     */
    public function setCodigoDotacionDetalleEnlaceFk($codigoDotacionDetalleEnlaceFk)
    {
        $this->codigoDotacionDetalleEnlaceFk = $codigoDotacionDetalleEnlaceFk;

        return $this;
    }

    /**
     * Get codigoDotacionDetalleEnlaceFk
     *
     * @return integer
     */
    public function getCodigoDotacionDetalleEnlaceFk()
    {
        return $this->codigoDotacionDetalleEnlaceFk;
    }

    /**
     * Set cantidadAsignada
     *
     * @param integer $cantidadAsignada
     *
     * @return RhuDotacionDetalle
     */
    public function setCantidadAsignada($cantidadAsignada)
    {
        $this->cantidadAsignada = $cantidadAsignada;

        return $this;
    }

    /**
     * Get cantidadAsignada
     *
     * @return integer
     */
    public function getCantidadAsignada()
    {
        return $this->cantidadAsignada;
    }

    /**
     * Set cantidadDevuelta
     *
     * @param integer $cantidadDevuelta
     *
     * @return RhuDotacionDetalle
     */
    public function setCantidadDevuelta($cantidadDevuelta)
    {
        $this->cantidadDevuelta = $cantidadDevuelta;

        return $this;
    }

    /**
     * Get cantidadDevuelta
     *
     * @return integer
     */
    public function getCantidadDevuelta()
    {
        return $this->cantidadDevuelta;
    }

    /**
     * Set serie
     *
     * @param string $serie
     *
     * @return RhuDotacionDetalle
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie
     *
     * @return string
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set lote
     *
     * @param string $lote
     *
     * @return RhuDotacionDetalle
     */
    public function setLote($lote)
    {
        $this->lote = $lote;

        return $this;
    }

    /**
     * Get lote
     *
     * @return string
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * Set dotacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacion $dotacionRel
     *
     * @return RhuDotacionDetalle
     */
    public function setDotacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacion $dotacionRel = null)
    {
        $this->dotacionRel = $dotacionRel;

        return $this;
    }

    /**
     * Get dotacionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDotacion
     */
    public function getDotacionRel()
    {
        return $this->dotacionRel;
    }

    /**
     * Set dotacionElementoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento $dotacionElementoRel
     *
     * @return RhuDotacionDetalle
     */
    public function setDotacionElementoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento $dotacionElementoRel = null)
    {
        $this->dotacionElementoRel = $dotacionElementoRel;

        return $this;
    }

    /**
     * Get dotacionElementoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento
     */
    public function getDotacionElementoRel()
    {
        return $this->dotacionElementoRel;
    }
}
