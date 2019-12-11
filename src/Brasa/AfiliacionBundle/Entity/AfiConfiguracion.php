<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_configuracion")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiConfiguracionRepository")
 */
class AfiConfiguracion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     */
    private $codigoConfiguracionPk;

    /**
     * @ORM\Column(name="informacion_legal_factura", type="text", nullable=true)
     */
    private $informacionLegalFactura;

    /**
     * @ORM\Column(name="informacion_pago_factura", type="text", nullable=true)
     */
    private $informacionPagoFactura;

    /**
     * @ORM\Column(name="informacion_contacto_factura", type="text", nullable=true)
     */
    private $informacionContactoFactura;

    /**
     * @ORM\Column(name="informacion_resolucion_dian_factura", type="text", nullable=true)
     */
    private $informacionResolucionDianFactura;

    /**
     * @ORM\Column(name="informacion_resolucion_supervigilancia_factura", type="text", nullable=true)
     */
    private $informacionResolucionSupervigilanciaFactura;

    /**
     * @ORM\Column(name="porcentaje_interes", type="float", nullable=true)
     */
    private $porcentajeInteres;

    /**
     * @ORM\Column(name="dias_interes", type="float", nullable=true)
     */
    private $diasInteres;


    /**
     * Set codigoConfiguracionPk
     *
     * @param integer $codigoConfiguracionPk
     *
     * @return AfiConfiguracion
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk)
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;

        return $this;
    }

    /**
     * Get codigoConfiguracionPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * Set informacionLegalFactura
     *
     * @param string $informacionLegalFactura
     *
     * @return AfiConfiguracion
     */
    public function setInformacionLegalFactura($informacionLegalFactura)
    {
        $this->informacionLegalFactura = $informacionLegalFactura;

        return $this;
    }

    /**
     * Get informacionLegalFactura
     *
     * @return string
     */
    public function getInformacionLegalFactura()
    {
        return $this->informacionLegalFactura;
    }

    /**
     * Set informacionPagoFactura
     *
     * @param string $informacionPagoFactura
     *
     * @return AfiConfiguracion
     */
    public function setInformacionPagoFactura($informacionPagoFactura)
    {
        $this->informacionPagoFactura = $informacionPagoFactura;

        return $this;
    }

    /**
     * Get informacionPagoFactura
     *
     * @return string
     */
    public function getInformacionPagoFactura()
    {
        return $this->informacionPagoFactura;
    }

    /**
     * Set informacionContactoFactura
     *
     * @param string $informacionContactoFactura
     *
     * @return AfiConfiguracion
     */
    public function setInformacionContactoFactura($informacionContactoFactura)
    {
        $this->informacionContactoFactura = $informacionContactoFactura;

        return $this;
    }

    /**
     * Get informacionContactoFactura
     *
     * @return string
     */
    public function getInformacionContactoFactura()
    {
        return $this->informacionContactoFactura;
    }

    /**
     * Set informacionResolucionDianFactura
     *
     * @param string $informacionResolucionDianFactura
     *
     * @return AfiConfiguracion
     */
    public function setInformacionResolucionDianFactura($informacionResolucionDianFactura)
    {
        $this->informacionResolucionDianFactura = $informacionResolucionDianFactura;

        return $this;
    }

    /**
     * Get informacionResolucionDianFactura
     *
     * @return string
     */
    public function getInformacionResolucionDianFactura()
    {
        return $this->informacionResolucionDianFactura;
    }

    /**
     * Set informacionResolucionSupervigilanciaFactura
     *
     * @param string $informacionResolucionSupervigilanciaFactura
     *
     * @return AfiConfiguracion
     */
    public function setInformacionResolucionSupervigilanciaFactura($informacionResolucionSupervigilanciaFactura)
    {
        $this->informacionResolucionSupervigilanciaFactura = $informacionResolucionSupervigilanciaFactura;

        return $this;
    }

    /**
     * Get informacionResolucionSupervigilanciaFactura
     *
     * @return string
     */
    public function getInformacionResolucionSupervigilanciaFactura()
    {
        return $this->informacionResolucionSupervigilanciaFactura;
    }

    /**
     * Set porcentajeInteres
     *
     * @param integer $porcentajeInteres
     *
     * @return AfiConfiguracion
     */
    public function setPorcentajeInteres($porcentajeInteres)
    {
        $this->porcentajeInteres = $porcentajeInteres;

        return $this;
    }

    /**
     * Get porcentajeInteres
     *
     * @return integer
     */
    public function getPorcentajeInteres()
    {
        return $this->porcentajeInteres;
    }

    /**
     * Set diasInteres
     *
     * @param float $diasInteres
     *
     * @return AfiConfiguracion
     */
    public function setDiasInteres($diasInteres)
    {
        $this->diasInteres = $diasInteres;

        return $this;
    }

    /**
     * Get diasInteres
     *
     * @return float
     */
    public function getDiasInteres()
    {
        return $this->diasInteres;
    }
}
