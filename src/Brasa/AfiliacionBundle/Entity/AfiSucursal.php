<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_sucursal")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiSucursalRepository")
 */
class AfiSucursal
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sucursal_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSucursalPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=50)
     */
    private $codigoInterface;

    /**
     * @ORM\OneToMany(targetEntity="AfiContrato", mappedBy="sucursalRel")
     */
    protected $contratosSucursalRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiPeriodoDetallePago", mappedBy="sucursalRel")
     */
    protected $periodosDetallesPagosSucursalRel;

    /**
     * @ORM\OneToMany(targetEntity="AfiPeriodoDetallePagoDetalle", mappedBy="sucursalRel")
     */
    protected $periodosDetallesPagosDetallesSucursalRel;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosSucursalRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->periodosDetallesPagosSucursalRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSucursalPk
     *
     * @return integer
     */
    public function getCodigoSucursalPk()
    {
        return $this->codigoSucursalPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return AfiSucursal
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
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return AfiSucursal
     */
    public function setCodigoInterface($codigoInterface)
    {
        $this->codigoInterface = $codigoInterface;

        return $this;
    }

    /**
     * Get codigoInterface
     *
     * @return string
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * Add contratosSucursalRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $contratosSucursalRel
     *
     * @return AfiSucursal
     */
    public function addContratosSucursalRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $contratosSucursalRel)
    {
        $this->contratosSucursalRel[] = $contratosSucursalRel;

        return $this;
    }

    /**
     * Remove contratosSucursalRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $contratosSucursalRel
     */
    public function removeContratosSucursalRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $contratosSucursalRel)
    {
        $this->contratosSucursalRel->removeElement($contratosSucursalRel);
    }

    /**
     * Get contratosSucursalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosSucursalRel()
    {
        return $this->contratosSucursalRel;
    }

    /**
     * Add periodosDetallesPagosSucursalRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosSucursalRel
     *
     * @return AfiSucursal
     */
    public function addPeriodosDetallesPagosSucursalRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosSucursalRel)
    {
        $this->periodosDetallesPagosSucursalRel[] = $periodosDetallesPagosSucursalRel;

        return $this;
    }

    /**
     * Remove periodosDetallesPagosSucursalRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosSucursalRel
     */
    public function removePeriodosDetallesPagosSucursalRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago $periodosDetallesPagosSucursalRel)
    {
        $this->periodosDetallesPagosSucursalRel->removeElement($periodosDetallesPagosSucursalRel);
    }

    /**
     * Get periodosDetallesPagosSucursalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodosDetallesPagosSucursalRel()
    {
        return $this->periodosDetallesPagosSucursalRel;
    }

    /**
     * Add periodosDetallesPagosDetallesSucursalRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesSucursalRel
     *
     * @return AfiSucursal
     */
    public function addPeriodosDetallesPagosDetallesSucursalRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesSucursalRel)
    {
        $this->periodosDetallesPagosDetallesSucursalRel[] = $periodosDetallesPagosDetallesSucursalRel;

        return $this;
    }

    /**
     * Remove periodosDetallesPagosDetallesSucursalRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesSucursalRel
     */
    public function removePeriodosDetallesPagosDetallesSucursalRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePagoDetalle $periodosDetallesPagosDetallesSucursalRel)
    {
        $this->periodosDetallesPagosDetallesSucursalRel->removeElement($periodosDetallesPagosDetallesSucursalRel);
    }

    /**
     * Get periodosDetallesPagosDetallesSucursalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodosDetallesPagosDetallesSucursalRel()
    {
        return $this->periodosDetallesPagosDetallesSucursalRel;
    }
}
