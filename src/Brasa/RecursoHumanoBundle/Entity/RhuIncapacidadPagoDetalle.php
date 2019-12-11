<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_incapacidad_pago_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuIncapacidadPagoDetalleRepository")
 */
class RhuIncapacidadPagoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_incapacidad_pago_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIncapacidadPagoDetallePk;                    
    
    /**
     * @ORM\Column(name="codigo_incapacidad_pago_fk", type="integer", nullable=true)
     */    
    private $codigoIncapacidadPagoFk;     
    
    /**
     * @ORM\Column(name="codigo_incapacidad_fk", type="integer", nullable=true)
     */    
    private $codigoIncapacidadFk;    
    
    /**
     * @ORM\Column(name="vr_pago", type="float")
     */
    private $vrPago = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuIncapacidadPago", inversedBy="incapacidadesPagosDetallesIncapacidadPagoRel")
     * @ORM\JoinColumn(name="codigo_incapacidad_pago_fk", referencedColumnName="codigo_incapacidad_pago_pk")
     */
    protected $incapacidadPagoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuIncapacidad", inversedBy="incapacidadesIncapacidadPagoRel")
     * @ORM\JoinColumn(name="codigo_incapacidad_fk", referencedColumnName="codigo_incapacidad_pk")
     */
    protected $incapacidadRel;
    

    

    /**
     * Get codigoIncapacidadPagoDetallePk
     *
     * @return integer
     */
    public function getCodigoIncapacidadPagoDetallePk()
    {
        return $this->codigoIncapacidadPagoDetallePk;
    }

    /**
     * Set codigoIncapacidadPagoFk
     *
     * @param integer $codigoIncapacidadPagoFk
     *
     * @return RhuIncapacidadPagoDetalle
     */
    public function setCodigoIncapacidadPagoFk($codigoIncapacidadPagoFk)
    {
        $this->codigoIncapacidadPagoFk = $codigoIncapacidadPagoFk;

        return $this;
    }

    /**
     * Get codigoIncapacidadPagoFk
     *
     * @return integer
     */
    public function getCodigoIncapacidadPagoFk()
    {
        return $this->codigoIncapacidadPagoFk;
    }

    /**
     * Set codigoIncapacidadFk
     *
     * @param integer $codigoIncapacidadFk
     *
     * @return RhuIncapacidadPagoDetalle
     */
    public function setCodigoIncapacidadFk($codigoIncapacidadFk)
    {
        $this->codigoIncapacidadFk = $codigoIncapacidadFk;

        return $this;
    }

    /**
     * Get codigoIncapacidadFk
     *
     * @return integer
     */
    public function getCodigoIncapacidadFk()
    {
        return $this->codigoIncapacidadFk;
    }

    /**
     * Set vrPago
     *
     * @param float $vrPago
     *
     * @return RhuIncapacidadPagoDetalle
     */
    public function setVrPago($vrPago)
    {
        $this->vrPago = $vrPago;

        return $this;
    }

    /**
     * Get vrPago
     *
     * @return float
     */
    public function getVrPago()
    {
        return $this->vrPago;
    }

    /**
     * Set incapacidadPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago $incapacidadPagoRel
     *
     * @return RhuIncapacidadPagoDetalle
     */
    public function setIncapacidadPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago $incapacidadPagoRel = null)
    {
        $this->incapacidadPagoRel = $incapacidadPagoRel;

        return $this;
    }

    /**
     * Get incapacidadPagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago
     */
    public function getIncapacidadPagoRel()
    {
        return $this->incapacidadPagoRel;
    }

    /**
     * Set incapacidadRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadRel
     *
     * @return RhuIncapacidadPagoDetalle
     */
    public function setIncapacidadRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadRel = null)
    {
        $this->incapacidadRel = $incapacidadRel;

        return $this;
    }

    /**
     * Get incapacidadRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad
     */
    public function getIncapacidadRel()
    {
        return $this->incapacidadRel;
    }
}
