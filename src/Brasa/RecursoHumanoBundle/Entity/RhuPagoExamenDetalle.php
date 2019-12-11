<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_examen_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoExamenDetalleRepository")
 */
class RhuPagoExamenDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_examen_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoExamenDetallePk;
    
    /**
     * @ORM\Column(name="codigo_pago_examen_fk", type="integer", nullable=true)
     */    
    private $codigoPagoExamenFk;
    
    /**
     * @ORM\Column(name="codigo_examen_fk", type="integer", nullable=true)
     */    
    private $codigoExamenFk;    
    
    /**
     * @ORM\Column(name="vr_precio", type="float")
     */
    private $vrPrecio;  

    /**
     * @ORM\ManyToOne(targetEntity="RhuExamen", inversedBy="pagosExamenesDetallesExamenRel")
     * @ORM\JoinColumn(name="codigo_examen_fk", referencedColumnName="codigo_examen_pk")
     */
    protected $examenRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoExamen", inversedBy="pagosExamenesDetallesPagoExamenRel")
     * @ORM\JoinColumn(name="codigo_pago_examen_fk", referencedColumnName="codigo_pago_examen_pk")
     */
    protected $pagoExamenRel;         
       



    /**
     * Get codigoPagoExamenDetallePk
     *
     * @return integer
     */
    public function getCodigoPagoExamenDetallePk()
    {
        return $this->codigoPagoExamenDetallePk;
    }

    /**
     * Set codigoPagoExamenFk
     *
     * @param integer $codigoPagoExamenFk
     *
     * @return RhuPagoExamenDetalle
     */
    public function setCodigoPagoExamenFk($codigoPagoExamenFk)
    {
        $this->codigoPagoExamenFk = $codigoPagoExamenFk;

        return $this;
    }

    /**
     * Get codigoPagoExamenFk
     *
     * @return integer
     */
    public function getCodigoPagoExamenFk()
    {
        return $this->codigoPagoExamenFk;
    }

    /**
     * Set codigoExamenFk
     *
     * @param integer $codigoExamenFk
     *
     * @return RhuPagoExamenDetalle
     */
    public function setCodigoExamenFk($codigoExamenFk)
    {
        $this->codigoExamenFk = $codigoExamenFk;

        return $this;
    }

    /**
     * Get codigoExamenFk
     *
     * @return integer
     */
    public function getCodigoExamenFk()
    {
        return $this->codigoExamenFk;
    }

    /**
     * Set vrPrecio
     *
     * @param float $vrPrecio
     *
     * @return RhuPagoExamenDetalle
     */
    public function setVrPrecio($vrPrecio)
    {
        $this->vrPrecio = $vrPrecio;

        return $this;
    }

    /**
     * Get vrPrecio
     *
     * @return float
     */
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * Set examenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenRel
     *
     * @return RhuPagoExamenDetalle
     */
    public function setExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenRel = null)
    {
        $this->examenRel = $examenRel;

        return $this;
    }

    /**
     * Get examenRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuExamen
     */
    public function getExamenRel()
    {
        return $this->examenRel;
    }

    /**
     * Set pagoExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen $pagoExamenRel
     *
     * @return RhuPagoExamenDetalle
     */
    public function setPagoExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen $pagoExamenRel = null)
    {
        $this->pagoExamenRel = $pagoExamenRel;

        return $this;
    }

    /**
     * Get pagoExamenRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen
     */
    public function getPagoExamenRel()
    {
        return $this->pagoExamenRel;
    }
}
