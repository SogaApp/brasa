<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_cotizacion_otro")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCotizacionOtroRepository")
 */
class TurCotizacionOtro
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cotizacion_otro_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCotizacionOtroPk;  
    
    /**
     * @ORM\Column(name="codigo_cotizacion_fk", type="integer")
     */    
    private $codigoCotizacionFk;

    /**
     * @ORM\Column(name="detalle", type="string", length=50)
     */
    private $detalle;     
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCotizacion", inversedBy="cotizacionesOtrosCotizacionRel")
     * @ORM\JoinColumn(name="codigo_cotizacion_fk", referencedColumnName="codigo_cotizacion_pk")
     */
    protected $cotizacionRel;       



    /**
     * Get codigoCotizacionOtroPk
     *
     * @return integer
     */
    public function getCodigoCotizacionOtroPk()
    {
        return $this->codigoCotizacionOtroPk;
    }

    /**
     * Set codigoCotizacionFk
     *
     * @param integer $codigoCotizacionFk
     *
     * @return TurCotizacionOtro
     */
    public function setCodigoCotizacionFk($codigoCotizacionFk)
    {
        $this->codigoCotizacionFk = $codigoCotizacionFk;

        return $this;
    }

    /**
     * Get codigoCotizacionFk
     *
     * @return integer
     */
    public function getCodigoCotizacionFk()
    {
        return $this->codigoCotizacionFk;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return TurCotizacionOtro
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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return TurCotizacionOtro
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set cotizacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionRel
     *
     * @return TurCotizacionOtro
     */
    public function setCotizacionRel(\Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionRel = null)
    {
        $this->cotizacionRel = $cotizacionRel;

        return $this;
    }

    /**
     * Get cotizacionRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCotizacion
     */
    public function getCotizacionRel()
    {
        return $this->cotizacionRel;
    }
}
